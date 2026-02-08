/**
 * Lista de Conversaciones (Threads)
 */
import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import Select, { MultiValue } from 'react-select';
import { threadService } from '../services/thread.service';
import { userService } from '../services/user.service';
import { Thread, User } from '../types';
import { useAuth } from '../context/AuthContext';

/**
 * Tipo para las opciones del select
 */
interface SelectOption {
  value: number;
  label: string;
  role?: string;
}

const ThreadList: React.FC = () => {
  const [threads, setThreads] = useState<Thread[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');
  const [currentPage, setCurrentPage] = useState(1);
  const [totalPages, setTotalPages] = useState(1);
  const [showNewThreadModal, setShowNewThreadModal] = useState(false);
  const { isAdmin } = useAuth();

  useEffect(() => {
    loadThreads(currentPage);
  }, [currentPage]);

  const loadThreads = async (page: number) => {
    try {
      setLoading(true);
      setError('');
      const response = await threadService.getThreads(page);
      setThreads(response.data.data);
      setCurrentPage(response.data.current_page);
      setTotalPages(response.data.last_page);
    } catch (err: any) {
      setError(err.response?.data?.message || 'Error al cargar conversaciones');
    } finally {
      setLoading(false);
    }
  };

  const handleDelete = async (id: number) => {
    if (!window.confirm('¿Estás seguro de eliminar esta conversación?')) {
      return;
    }

    try {
      await threadService.deleteThread(id);
      // Recargar threads
      loadThreads(currentPage);
    } catch (err: any) {
      alert(err.response?.data?.message || 'Error al eliminar conversación');
    }
  };

  const formatDate = (dateString: string) => {
    const date = new Date(dateString);
    return date.toLocaleDateString('es-ES', {
      day: '2-digit',
      month: 'short',
      hour: '2-digit',
      minute: '2-digit',
    });
  };

  if (loading && threads.length === 0) {
    return (
      <div className="loading-container">
        <div className="loading-spinner"></div>
        <p>Cargando conversaciones...</p>
      </div>
    );
  }

  return (
    <div className="thread-list-container">
      <div className="thread-list-header">
        <h2>
          {isAdmin ? '📬 Todas las Conversaciones' : '💬 Mis Conversaciones'}
        </h2>
        <button
          onClick={() => setShowNewThreadModal(true)}
          className="btn btn-primary"
        >
          ➕ Nueva Conversación
        </button>
      </div>

      {error && <div className="error-message">{error}</div>}

      {threads.length === 0 ? (
        <div className="empty-state">
          <p>📭 No tienes conversaciones aún</p>
          <button
            onClick={() => setShowNewThreadModal(true)}
            className="btn btn-primary"
          >
            Crear tu primera conversación
          </button>
        </div>
      ) : (
        <>
          <div className="thread-list">
            {threads.map((thread) => (
              <div key={thread.id} className="thread-item">
                <Link to={`/thread/${thread.id}`} className="thread-link">
                  <div className="thread-info">
                    <h3>{thread.subject}</h3>
                    <p className="thread-meta">
                      Por: {thread.creator?.name} · {thread.messages_count} mensajes
                    </p>
                    {thread.latest_message && (
                      <p className="thread-preview">
                        {thread.latest_message.user?.name}: {thread.latest_message.body.substring(0, 100)}
                        {thread.latest_message.body.length > 100 ? '...' : ''}
                      </p>
                    )}
                    <p className="thread-date">{formatDate(thread.updated_at)}</p>
                  </div>
                </Link>
                {(isAdmin || thread.creator?.id === thread.created_by) && (
                  <button
                    onClick={(e) => {
                      e.preventDefault();
                      handleDelete(thread.id);
                    }}
                    className="btn btn-sm btn-danger thread-delete"
                    title="Eliminar conversación"
                  >
                    🗑️
                  </button>
                )}
              </div>
            ))}
          </div>

          {totalPages > 1 && (
            <div className="pagination">
              <button
                onClick={() => setCurrentPage(currentPage - 1)}
                disabled={currentPage === 1}
                className="btn btn-sm"
              >
                ← Anterior
              </button>
              <span className="pagination-info">
                Página {currentPage} de {totalPages}
              </span>
              <button
                onClick={() => setCurrentPage(currentPage + 1)}
                disabled={currentPage === totalPages}
                className="btn btn-sm"
              >
                Siguiente →
              </button>
            </div>
          )}
        </>
      )}

      {showNewThreadModal && (
        <NewThreadModal
          onClose={() => setShowNewThreadModal(false)}
          onSuccess={() => {
            setShowNewThreadModal(false);
            loadThreads(1);
          }}
        />
      )}
    </div>
  );
};

/**
 * Modal para crear nueva conversación
 */
interface NewThreadModalProps {
  onClose: () => void;
  onSuccess: () => void;
}

const NewThreadModal: React.FC<NewThreadModalProps> = ({ onClose, onSuccess }) => {
  const { user: currentUser } = useAuth();
  const [subject, setSubject] = useState('');
  const [body, setBody] = useState('');
  const [selectedParticipants, setSelectedParticipants] = useState<MultiValue<SelectOption>>([]);
  const [userOptions, setUserOptions] = useState<SelectOption[]>([]);
  const [loading, setLoading] = useState(false);
  const [loadingUsers, setLoadingUsers] = useState(true);
  const [error, setError] = useState('');

  useEffect(() => {
    loadUsers();
  }, []);

  const loadUsers = async () => {
    try {
      const data = await userService.getUsers();
      const options = data
        .filter(u => u.id !== currentUser?.id) // Excluir usuario actual
        .map(u => ({
          value: u.id,
          label: `${u.name} (${u.email})`,
          role: u.role
        }));
      setUserOptions(options);
      setLoadingUsers(false);
    } catch (err) {
      setError('Error al cargar usuarios');
      setLoadingUsers(false);
    }
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setError('');

    if (selectedParticipants.length === 0) {
      setError('Debes seleccionar al menos un participante');
      return;
    }

    setLoading(true);

    try {
      const participantIds = selectedParticipants.map(p => p.value);
      await threadService.createThread({
        subject,
        body,
        participants: participantIds,
      });

      onSuccess();
    } catch (err: any) {
      const errorMsg = err.response?.data?.errors 
        ? Object.values(err.response.data.errors).flat().join(', ')
        : err.response?.data?.message || 'Error al crear conversación';
      setError(errorMsg);
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="modal-overlay" onClick={onClose}>
      <div className="modal-content" onClick={(e) => e.stopPropagation()}>
        <div className="modal-header">
          <h3>📝 Nueva Conversación</h3>
          <button onClick={onClose} className="modal-close">✕</button>
        </div>

        {error && <div className="error-message">{error}</div>}

        <form onSubmit={handleSubmit}>
          <div className="form-group">
            <label htmlFor="subject">Asunto</label>
            <input
              type="text"
              id="subject"
              value={subject}
              onChange={(e) => setSubject(e.target.value)}
              required
              placeholder="Tema de la conversación"
              disabled={loading}
            />
          </div>

          <div className="form-group">
            <label htmlFor="body">Mensaje inicial</label>
            <textarea
              id="body"
              value={body}
              onChange={(e) => setBody(e.target.value)}
              required
              placeholder="Escribe tu mensaje..."
              rows={4}
              disabled={loading}
            />
          </div>

          <div className="form-group">
            <label>Participantes</label>
            {loadingUsers ? (
              <p className="text-muted">Cargando usuarios...</p>
            ) : (
              <Select
                isMulti
                options={userOptions}
                value={selectedParticipants}
                onChange={(selected) => setSelectedParticipants(selected)}
                placeholder="Selecciona participantes..."
                noOptionsMessage={() => "No hay usuarios disponibles"}
                isDisabled={loading}
                className="react-select-container"
                classNamePrefix="react-select"
                styles={{
                  control: (base) => ({
                    ...base,
                    borderColor: '#e5e7eb',
                    '&:hover': {
                      borderColor: '#4f46e5'
                    }
                  }),
                  multiValue: (base) => ({
                    ...base,
                    backgroundColor: '#e0e7ff'
                  }),
                  multiValueLabel: (base) => ({
                    ...base,
                    color: '#4338ca'
                  }),
                  multiValueRemove: (base) => ({
                    ...base,
                    color: '#4338ca',
                    '&:hover': {
                      backgroundColor: '#c7d2fe',
                      color: '#3730a3'
                    }
                  })
                }}
              />
            )}
          </div>

          <div className="modal-footer">
            <button type="button" onClick={onClose} className="btn" disabled={loading}>
              Cancelar
            </button>
            <button type="submit" className="btn btn-primary" disabled={loading}>
              {loading ? 'Creando...' : 'Crear Conversación'}
            </button>
          </div>
        </form>
      </div>
    </div>
  );
};

export default ThreadList;
