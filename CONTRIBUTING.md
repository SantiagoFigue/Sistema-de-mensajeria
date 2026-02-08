# Guía de Contribución

## 📋 Estándares de Código

### Idioma de Comentarios

**IMPORTANTE:** Todos los comentarios en el código deben estar en **ESPAÑOL**.

Esto incluye:
- Comentarios de documentación (PHPDoc, JSDoc)
- Comentarios inline
- Comentarios de funciones, clases y métodos
- Descripciones en migraciones
- Comentarios en archivos de configuración

#### ✅ Correcto:
```php
/**
 * Obtener el usuario autenticado.
 * 
 * @return \Illuminate\Http\JsonResponse
 */
public function me()
{
    // Retornar el usuario actual
    return response()->json([
        'success' => true,
        'user' => auth('api')->user()
    ]);
}
```

#### ❌ Incorrecto:
```php
/**
 * Get the authenticated user.
 * 
 * @return \Illuminate\Http\JsonResponse
 */
public function me()
{
    // Return current user
    return response()->json([
        'success' => true,
        'user' => auth('api')->user()
    ]);
}
```

### Convenciones de Nomenclatura

#### Backend (Laravel/PHP)
- **Clases:** PascalCase - `ThreadController`, `AuthService`
- **Métodos:** camelCase - `getUser()`, `createThread()`
- **Variables:** camelCase - `$userName`, `$threadId`
- **Constantes:** UPPER_SNAKE_CASE - `MAX_UPLOAD_SIZE`
- **Rutas de API:** kebab-case con plural - `/api/threads`, `/api/auth/login`
- **Nombres de tablas:** snake_case plural - `threads`, `thread_participants`
- **Columnas de BD:** snake_case - `created_by`, `last_read_at`

#### Frontend (React/TypeScript)
- **Componentes:** PascalCase - `ThreadList`, `MessageCard`
- **Hooks:** camelCase con prefijo "use" - `useAuth()`, `useThreads()`
- **Variables/Funciones:** camelCase - `userName`, `handleSubmit()`
- **Constantes:** UPPER_SNAKE_CASE - `API_BASE_URL`
- **Interfaces/Types:** PascalCase - `UserInterface`, `ThreadType`

### Estructura de Archivos

```
proyect/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── Api/          # Controladores de API agrupados
│   │   └── Middleware/
│   └── Models/               # Modelos Eloquent
├── database/
│   ├── migrations/           # Migraciones en orden cronológico
│   └── seeders/
├── routes/
│   └── api.php              # Rutas de API
├── client/                  # Frontend React
│   ├── src/
│   │   ├── components/      # Componentes reutilizables
│   │   ├── pages/           # Páginas/vistas
│   │   ├── services/        # Servicios API
│   │   ├── hooks/           # Custom hooks
│   │   └── types/           # Tipos TypeScript
│   └── package.json
└── tests/                   # Tests backend
```

### Formato de Código

#### PHP
- Usar PSR-12 como estándar
- Indentación: 4 espacios
- Llaves de apertura en nueva línea para clases y funciones
- Array syntax: `[]` preferido sobre `array()`

#### JavaScript/TypeScript
- Usar ESLint + Prettier
- Indentación: 2 espacios
- Punto y coma obligatorio
- Comillas simples para strings

### Git Commits

Utilizar el formato Conventional Commits:

```
tipo(alcance): descripción breve

Descripción detallada (opcional)

- Punto 1
- Punto 2
```

**Tipos permitidos:**
- `feat`: Nueva funcionalidad
- `fix`: Corrección de bugs
- `docs`: Cambios en documentación
- `style`: Cambios de formato (no afectan la lógica)
- `refactor`: Refactorización de código
- `test`: Agregar o modificar tests
- `chore`: Tareas de mantenimiento

**Ejemplos:**
```bash
feat: Implementar autenticación JWT completa

- Crear AuthController con endpoints register, login, logout
- Configurar middleware de autenticación
- Agregar validaciones de datos

fix: Corregir middleware Authenticate para APIs

Cambiar redirect por respuesta 401 JSON en rutas /api/*
```

### Testing

- Todo controlador debe tener su test correspondiente
- Cobertura mínima: 80%
- Usar factories y seeders para datos de prueba
- Nomenclatura de tests en español:
  ```php
  public function test_usuario_puede_crear_thread()
  {
      // ...
  }
  ```

### Documentación

- Documentar todos los métodos públicos
- Incluir tipos de parámetros y valores de retorno
- Explicar la lógica compleja
- Mantener README.md actualizado
- Documentar cambios en la API

### Pull Requests

1. Crear rama descriptiva: `feat/auth-jwt`, `fix/soft-delete-threads`
2. Commits atómicos y bien descritos
3. Actualizar tests
4. Actualizar documentación si aplica
5. Solicitar revisión antes de merge

## 🚀 Flujo de Trabajo

1. Crear issue describiendo la tarea
2. Crear rama desde `main`
3. Desarrollar con commits frecuentes
4. Escribir/actualizar tests
5. Actualizar documentación
6. Push y crear PR
7. Code review
8. Merge a `main`

## 📞 Contacto

Si tienes dudas sobre las convenciones, abre un issue o contacta al equipo de desarrollo.
