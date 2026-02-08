# Prompt de Diseño - Frontend Sistema de Mensajería

## Contexto del Proyecto
Necesito diseñar el frontend de un sistema de mensajería en tiempo real similar a un inbox de email o Slack. La aplicación debe ser moderna, minimalista y fácil de usar, con una interfaz limpia y profesional.

## Stack Tecnológico
- React 18 + TypeScript
- React Router para navegación
- Axios para peticiones HTTP
- JWT Authentication
- Vite como build tool

## Requisitos de Diseño

### 1. Paleta de Colores
- **Color primario**: Índigo moderno (#4f46e5) para botones principales y elementos destacados
- **Color secundario**: Gris neutro (#6b7280) para texto secundario
- **Color de fondo**: Blanco (#ffffff) con áreas secundarias en gris muy claro (#f9fafb)
- **Color de peligro**: Rojo (#ef4444) para acciones destructivas
- **Color de éxito**: Verde (#10b981) para confirmaciones

### 2. Página de Login/Registro
**Diseño:**
- Formulario centrado en la página con fondo blanco
- Logo o título "📬 InboxApp" en la parte superior
- Campos de entrada con bordes suaves y transiciones
- Botón principal destacado con el color índigo
- Link para alternar entre login y registro
- Mostrar credenciales de demo visibles debajo del formulario
- Mensajes de error en rojo suave con fondo (#fee2e2)
- Loading spinner durante el proceso de autenticación

**Elementos específicos:**
- Input de email con icono 📧
- Input de password con icono 🔒
- Selector de rol (admin/user) en registro con badges visuales
- Animación suave al cambiar entre login/registro

### 3. Navbar Principal
**Diseño:**
- Barra superior fija con fondo blanco y sombra sutil
- Logo/título a la izquierda: "📬 InboxApp"
- Información del usuario a la derecha:
  - Nombre del usuario
  - Badge de rol (Admin en azul índigo, User en gris)
  - Botón de logout en rojo con icono 🚪

### 4. Lista de Conversaciones (ThreadList)
**Layout:**
- Lista vertical de tarjetas con hover effect
- Cada tarjeta debe mostrar:
  - Asunto en negrita como título principal
  - Metadata: Creador y número de mensajes
  - Preview del último mensaje (máximo 100 caracteres)
  - Fecha de última actualización en formato español
  - Botón de eliminar (🗑️) visible solo para admin o creador

**Interactividad:**
- Hover: Cambiar color de fondo sutilmente
- Click en tarjeta: Navegar a la conversación
- Botón flotante "➕ Nueva Conversación" en esquina inferior derecha
- Paginación en la parte inferior (← Anterior | Página X de Y | Siguiente →)

**Estados:**
- Loading: Spinner centrado con animación de rotación
- Empty state: Mensaje amigable "No hay conversaciones aún" con emoji 💬
- Error: Mensaje en rojo con opción de reintentar

### 5. Modal de Nueva Conversación
**Diseño:**
- Overlay oscuro semi-transparente sobre toda la pantalla
- Modal centrado con fondo blanco y sombra grande
- Header del modal:
  - Título "📝 Nueva Conversación"
  - Botón cerrar (✕) en la esquina superior derecha

**Formulario:**
- Campo "Asunto": Input de texto con placeholder
- Campo "Mensaje inicial": Textarea de 4 filas
- Campo "Participantes": React-Select multi-selección con:
  - Búsqueda por nombre
  - Chips/tags de color índigo para usuarios seleccionados
  - Mostrar email junto al nombre en las opciones
  - Placeholder: "Selecciona participantes..."

**Footer del modal:**
- Botón "Cancelar" (gris)
- Botón "Crear Conversación" (índigo primario)
- Deshabilitar botones durante loading
- Mostrar "Creando..." cuando esté procesando

### 6. Vista de Conversación (ThreadView)
**Layout principal:**
- Header fijo:
  - Asunto de la conversación en grande
  - Lista de participantes con avatares o iniciales
  - Botón de eliminar conversación (solo admin/creador)

**Área de mensajes:**
- Estilo chat con burbujas:
  - Mensajes propios alineados a la derecha (fondo índigo, texto blanco)
  - Mensajes de otros alineados a la izquierda (fondo gris claro)
  - Mostrar nombre del usuario arriba de cada mensaje
  - Badge "Admin" si el usuario es administrador
  - Timestamp debajo de cada mensaje en formato legible
- Auto-scroll al último mensaje al cargar
- Separador visual entre días

**Formulario de nuevo mensaje:**
- Fijo en la parte inferior
- Textarea con altura automática (max 5 líneas)
- Botón "Enviar" destacado
- Deshabilitar mientras se envía el mensaje

### 7. Diseño Responsive

**Desktop (> 768px):**
- Ancho máximo de contenido: 1200px centrado
- Lista de conversaciones con 2 columnas en pantallas grandes
- Modal ocupa 60% del ancho

**Mobile (< 768px):**
- Lista de conversaciones en columna única
- Modal ocupa 95% del ancho
- Navbar compacta con logo más pequeño
- Mensajes ocupan 100% del ancho disponible
- Botón flotante más pequeño y pegado al borde

### 8. Microinteracciones y Animaciones

**Transiciones suaves:**
- Buttons: hover scale 1.02 y cambio de color
- Cards: hover shadow más pronunciada
- Modals: fade-in al aparecer (0.2s)
- Inputs: focus con borde índigo y sombra sutil

**Loading states:**
- Spinner de carga rotando continuamente
- Skeleton screens para carga inicial de listas
- Deshabilitación visual de elementos durante operaciones

**Feedback visual:**
- Errores: Shake animation en formularios inválidos
- Éxito: Check verde con fade-out después de 2s
- Eliminación: Confirm dialog antes de borrar

### 9. Tipografía
- **Font family**: System fonts (-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto)
- **Títulos principales**: 1.5rem bold
- **Títulos secundarios**: 1.125rem semi-bold
- **Texto normal**: 1rem regular
- **Texto pequeño**: 0.875rem para metadata
- **Line height**: 1.5 para legibilidad

### 10. Espaciado y Layout
- **Padding general**: 1rem (16px)
- **Padding de contenedores**: 2rem (32px)
- **Gap entre elementos**: 1rem
- **Border radius**: 0.375rem (6px) para elementos redondeados
- **Sombras**: Usar box-shadow sutil para depth
  - Small: `0 1px 3px rgba(0,0,0,0.1)`
  - Large: `0 10px 15px rgba(0,0,0,0.1)`

### 11. Accesibilidad
- Todos los botones con labels descriptivos
- Inputs con labels visibles
- Contraste de color WCAG AA mínimo
- Estados focus visibles con outline índigo
- Mensajes de error legibles por screen readers
- Navegación por teclado completa (Tab, Enter, Esc)

### 12. Componentes Adicionales Sugeridos

**Badges:**
- Admin: Fondo índigo (#4f46e5), texto blanco, pequeño y pill-shaped
- User: Fondo gris (#9ca3af), texto blanco

**Loading Spinner:**
- Borde gris con top índigo
- Animación de rotación suave
- Centrado con margin auto

**Empty States:**
- Emoji grande (3rem)
- Mensaje descriptivo
- Botón de acción primaria si aplica

**Botones:**
- Primary: Fondo índigo, texto blanco, hover más oscuro
- Secondary: Fondo gris, texto gris oscuro
- Danger: Fondo rojo, texto blanco
- Small: Padding reducido para botones compactos

## Instrucciones de Implementación

1. **Usar CSS Variables** para colores y valores reutilizables
2. **Mobile-first approach** con media queries para desktop
3. **Componentes reutilizables** para buttons, inputs, modals, badges
4. **Consistencia visual** en toda la aplicación
5. **Performance**: Lazy loading de componentes pesados
6. **Testing**: Verificar en Chrome, Firefox, Safari y Edge

## Referencias de Inspiración
- Gmail (estructura de inbox)
- Slack (sistema de mensajería)
- Linear (UI moderna y limpia)
- Tailwind UI (componentes bien diseñados)

## Notas Adicionales
- Mantener diseño minimalista y profesional
- Priorizar usabilidad sobre efectos fancy
- Asegurar tiempos de carga rápidos
- Considerar dark mode como mejora futura
