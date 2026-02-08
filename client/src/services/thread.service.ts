/**
 * Servicio de Threads y Mensajes
 */
import api from './api';
import {
  Thread,
  Message,
  PaginatedResponse,
  ApiResponse,
  CreateThreadData,
  CreateMessageData,
} from '../types';

export const threadService = {
  /**
   * Obtener lista de threads (paginada)
   */
  async getThreads(page: number = 1): Promise<PaginatedResponse<Thread>> {
    const response = await api.get<PaginatedResponse<Thread>>(`/threads?page=${page}`);
    return response.data;
  },

  /**
   * Crear nuevo thread
   */
  async createThread(data: CreateThreadData): Promise<ApiResponse<Thread>> {
    const response = await api.post<ApiResponse<Thread>>('/threads', data);
    return response.data;
  },

  /**
   * Obtener detalles de un thread
   */
  async getThread(id: number): Promise<ApiResponse<Thread>> {
    const response = await api.get<ApiResponse<Thread>>(`/threads/${id}`);
    return response.data;
  },

  /**
   * Eliminar thread
   */
  async deleteThread(id: number): Promise<ApiResponse<null>> {
    const response = await api.delete<ApiResponse<null>>(`/threads/${id}`);
    return response.data;
  },

  /**
   * Enviar mensaje en un thread
   */
  async sendMessage(
    threadId: number,
    data: CreateMessageData
  ): Promise<ApiResponse<Message>> {
    const response = await api.post<ApiResponse<Message>>(
      `/threads/${threadId}/messages`,
      data
    );
    return response.data;
  },
};
