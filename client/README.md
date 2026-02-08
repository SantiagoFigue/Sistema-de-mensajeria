# Inbox System - Frontend

Sistema de mensajería tipo "inbox" - Frontend con React + TypeScript

> **Nota:** Esta es la carpeta del frontend. Para la documentación completa del proyecto, consulta el README principal en la raíz.

## Stack Tecnológico

- React 18
- TypeScript
- Vite
- Axios (cliente HTTP)
- React Router (navegación)

## Instalación Rápida

```bash
# Instalar dependencias
npm install

# Copiar archivo de entorno
cp .env.example .env

# Iniciar desarrollo
npm run dev
```

## Scripts Disponibles

```bash
# Desarrollo
npm run dev

# Build de producción
npm run build

# Preview del build
npm run preview

# Linting
npm run lint

# Tests
npm run test
```

## Configuración

Editar el archivo `.env` con la URL del backend:

```
VITE_API_BASE_URL=http://localhost:8000/api
```

## Estructura del Proyecto

```
src/
├── components/     # Componentes reutilizables
├── pages/          # Páginas/vistas
├── services/       # Servicios API
├── types/          # Tipos TypeScript
├── utils/          # Utilidades
├── contexts/       # Contextos React
├── hooks/          # Custom hooks
├── App.tsx         # Componente principal
└── main.tsx        # Punto de entrada
```

## Desarrollo

El servidor de desarrollo se ejecuta en `http://localhost:3000`

Los cambios se reflejan automáticamente (Hot Module Replacement).
