/**
 * Servicio de Usuarios
 */
import api from './api';
import { User, ApiResponse } from '../types';

export const userService = {
  /**
   * Obtener lista de todos los usuarios
   */
  async getUsers(): Promise<User[]> {
    const response = await api.get<ApiResponse<User[]>>('/users');
    return response.data.data;
  },
};

export default userService;
