/**
 * Vista de Conversación (Thread) con Mensajes
 */
import React, { useState, useEffect, useRef } from 'react';
import { useParams, useNavigate, Link } from 'react-router-dom';
import { threadService } from '../services/thread.service';
import { Thread, Message } from '../types';
import { useAuth } from '../context/AuthContext';

const ThreadView: React.FC = () => {
  const { id } = useParams<{ id: string }>();
  const [thread, setThread] = useState<Thread | null>(null);
  const [newMessage, setNewMessage] = useState('');
  const [loading, setLoading] = useState(true);
  const [sendingMessage, setSendingMessage] = useState(false);
  const [error, setError] = useState('');
  const { user, isAdmin } = useAuth();
  const navigate = useNavigate();
  const messagesEndRef = useRef<HTMLDivElement>(null);

  useEffect(() => {
    if (id) {
      loadThread(parseInt(id));
    }
  }, [id]);

  useEffect(() => {
    // Auto-scroll al último mensaje
    messagesEndRef.current?.scrollIntoView({ behavior: 'smooth' });
  }, [thread?.messages]);

  const loadThread = async (threadId: number) => {
    try {
      setLoading(true);
      setError('');
      const response = await threadService.getThread(threadId);
      setThread(response.data);
    } catch (err: any) {
      setError(err.response?.data?.message || 'Error al cargar conversación');
    } finally {
      setLoading(false);
    }
  };

  const handleSendMessage = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!newMessage.trim() || !id) return;

    setSendingMessage(true);
    try {
      await threadService.sendMessage(parseInt(id), { body: newMessage });
      setNewMessage('');
      // Recargar thread para mostrar nuevo mensaje
      await loadThread(parseInt(id));
    } catch (err: any) {
      alert(err.response?.data?.message || 'Error al enviar mensaje');
    } finally {
      setSendingMessage(false);
    }
  };

  const handleDelete = async () => {
    if (!thread || !window.confirm('¿Estás seguro de eliminar esta conversación?')) {
      return;
    }

    try {
      await threadService.deleteThread(thread.id);
      navigate('/');
    } catch (err: any) {
      alert(err.response?.data?.message || 'Error al eliminar conversación');
    }
  };

  const formatDate = (dateString: string) => {
    const date = new Date(dateString);
    return date.toLocaleString('es-ES', {
      day: '2-digit',
      month: 'short',
      year: 'numeric',
      hour: '2-digit',
      minute: '2-digit',
    });
  };

  if (loading) {
    return (
      <div className="loading-container">
        <div className="loading-spinner"></div>
        <p>Cargando conversación...</p>
      </div>
    );
  }

  if (error || !thread) {
    return (
      <div className="error-container">
        <div className="error-message">{error || 'Conversación no encontrada'}</div>
        <Link to="/" className="btn">← Volver a inicio</Link>
      </div>
    );
  }

  const canDelete = isAdmin || thread.created_by === user?.id;

  return (
    <div className="thread-view-container">
      <div className="thread-header">
        <div className="thread-header-info">
          <Link to="/" className="back-link">← Volver</Link>
          <h2>{thread.subject}</h2>
          <p className="thread-meta">
            Creado por <strong>{thread.creator?.name}</strong> el {formatDate(thread.created_at)}
          </p>
          <div className="thread-participants">
            <strong>Participantes:</strong>
            {thread.participants?.map((participant) => (
              <span key={participant.id} className="participant-badge">
                {participant.name}
              </span>
            ))}
          </div>
        </div>
        {canDelete && (
          <button onClick={handleDelete} className="btn btn-danger" title="Eliminar conversación">
            🗑️ Eliminar
          </button>
        )}
      </div>

      <div className="messages-container">
        {thread.messages && thread.messages.length > 0 ? (
          thread.messages.map((message: Message) => (
            <div
              key={message.id}
              className={`message ${message.user_id === user?.id ? 'message-own' : 'message-other'}`}
            >
              <div className="message-header">
                <strong>{message.user?.name}</strong>
                {message.user?.role === 'admin' && (
                  <span className="admin-badge">👑 Admin</span>
                )}
                <span className="message-date">{formatDate(message.created_at)}</span>
              </div>
              <div className="message-body">{message.body}</div>
            </div>
          ))
        ) : (
          <div className="empty-state">
            <p>No hay mensajes en esta conversación</p>
          </div>
        )}
        <div ref={messagesEndRef} />
      </div>

      <form onSubmit={handleSendMessage} className="message-form">
        <textarea
          value={newMessage}
          onChange={(e) => setNewMessage(e.target.value)}
          placeholder="Escribe tu mensaje..."
          rows={3}
          disabled={sendingMessage}
          required
        />
        <button
          type="submit"
          className="btn btn-primary"
          disabled={sendingMessage || !newMessage.trim()}
        >
          {sendingMessage ? 'Enviando...' : '📤 Enviar'}
        </button>
      </form>
    </div>
  );
};

export default ThreadView;
