/**
 * Tipos TypeScript para el Sistema de Mensajería
 */

export interface User {
  id: number;
  name: string;
  email: string;
  role: 'admin' | 'user';
  email_verified_at: string | null;
  created_at: string;
  updated_at: string;
}

export interface Thread {
  id: number;
  subject: string;
  created_by: number;
  created_at: string;
  updated_at: string;
  deleted_at: string | null;
  messages_count?: number;
  creator?: User;
  participants?: ThreadParticipant[];
  messages?: Message[];
  latest_message?: Message;
}

export interface Message {
  id: number;
  thread_id: number;
  user_id: number;
  body: string;
  created_at: string;
  updated_at: string;
  deleted_at: string | null;
  user?: User;
}

export interface ThreadParticipant {
  id: number;
  name: string;
  email: string;
  pivot?: {
    thread_id: number;
    user_id: number;
    last_read_at: string | null;
    created_at: string;
    updated_at: string;
  };
}

export interface LoginCredentials {
  email: string;
  password: string;
}

export interface RegisterData {
  name: string;
  email: string;
  password: string;
  password_confirmation: string;
  role?: 'admin' | 'user';
}

export interface AuthResponse {
  success: boolean;
  message?: string;
  user: User;
  authorization: {
    token: string;
    type: string;
  };
}

export interface ApiResponse<T> {
  success: boolean;
  data?: T;
  message?: string;
  errors?: Record<string, string[]>;
}

export interface PaginatedResponse<T> {
  success: boolean;
  data: {
    current_page: number;
    data: T[];
    per_page: number;
    total: number;
    last_page: number;
    from: number;
    to: number;
  };
}

export interface CreateThreadData {
  subject: string;
  body: string;
  participants: number[];
}

export interface CreateMessageData {
  body: string;
}
