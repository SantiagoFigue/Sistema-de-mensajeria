/**
 * Barra de navegación
 */
import React from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';

const Navbar: React.FC = () => {
  const { user, logout, isAdmin } = useAuth();
  const navigate = useNavigate();

  const handleLogout = async () => {
    await logout();
    navigate('/login');
  };

  return (
    <nav className="navbar">
      <div className="navbar-container">
        <Link to="/" className="navbar-brand">
          💬 Inbox
        </Link>

        <div className="navbar-user">
          <span className="user-name">{user?.name}</span>
          <span className={`user-badge ${isAdmin ? 'admin' : 'user'}`}>
            {isAdmin ? '👑 Admin' : '👤 User'}
          </span>
          <button onClick={handleLogout} className="btn btn-sm btn-logout">
            Cerrar Sesión
          </button>
        </div>
      </div>
    </nav>
  );
};

export default Navbar;
