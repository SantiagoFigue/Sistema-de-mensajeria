Prueba Técnica para Desarrollador Senior Laravel

Esta prueba técnica está diseñada para evaluar tus habilidades como desarrollador(a) 
Full‑Stack con tecnologías Laravel, React y TypeScript, tomando en cuenta un entorno 
moderno de desarrollo en el que puedes hacer uso de herramientas de Inteligencia Artificial 
(IA) como apoyo, siempre y cuando sepas adaptar y justificar su uso. 
El reto consiste en construir un módulo de sistema de mensajería tipo "inbox", como el que 
encontrarías en plataformas de soporte o correo electrónico interno. El sistema debe permitir 
listar conversaciones, ver mensajes dentro de un hilo, y responder a mensajes existentes. 
Puedes elegir uno de los siguientes enfoques: 
● Full‑Stack: implementar tanto el backend en Laravel como el frontend en React. 
● Back‑End: enfocarte exclusivamente en la creación de APIs RESTful con Laravel. 
● Front‑End: construir la interfaz en React/TypeScript consumiendo APIs simuladas 
(mock). 
El objetivo no es solo tener una aplicación funcional, sino demostrar cómo estructuras tus 
ideas, tomas decisiones técnicas, escribes código de calidad y pruebas lo que construyes. 
El candidato podrá optar por uno de los siguientes enfoques: 
● Implementación Full‑Stack (Laravel + React/TypeScript + APIs completas) 
● Enfoque Directo a Back‑End (Laravel + diseño de APIs REST sin interfaz de usuario) 
● Enfoque Directo a Front‑End (React/TypeScript + consumo de APIs simuladas sin 
backend real) 

1. Mockup de interfaz

image.png de referencia dentro del directorio

2. APIs a construir (opción Full‑Stack o Backend‑Only) 
1. Autenticación / Identidad 
○ JWT 
○ datos del usuario actual 
2. Conversaciones 
○ lista de hilos (paginada, filtros, búsqueda) 
○ detalles del hilo + mensajes 
3. Mensajes 
○ crear nuevo hilo con primer mensaje 
○ enviar respuesta en hilo 
4. Notificaciones (bonus) 
○ mensajes no leídos, etc. 
Formato: JSON 
Autorización: Bearer token (JWT)

3. Entregables esperados 
● Diseño de la UI (Mockup en Figma, Boceto en dibujo, o código de componentes React). 
● Implementación Back‑End 
○ Rutas, controladores, modelos y migraciones en Laravel. 
○ Validaciones y tests básicos (PHPUnit). 
○ Documentación de la API. 
● Implementación Front‑End 
○ Componentes React con TypeScript. 
○ Llamadas a la API, manejo de estados. 
○ Estilos y diseño responsivo. 
○ Tests básicos (Jest + React Testing Library).
● Instrucciones de despliegue (README): instalación, arranque de back y front, 
variables de entorno.

4. Reglas de evaluación 
Criterio 
Qué buscamos 
Calidad del código Limpieza, consistencia, comentarios 
claros. 
Arquitectura y 
diseño 
Uso de Laravel / 
React TS 
Pruebas 
Documentación 
Control de 
versiones 
UX/UI y 
accesibilidad 
Separación de capas, patrones , 
escalabilidad. 
Uso de features idiomáticos 
(Eloquent, Middleware, Hooks, 
Context). 
Cobertura mínima de 
rutas/componentes clave. 
README claro con pasos, 
descripción de endpoints, 
decisiones. 
Commits frecuentes, mensajes 
descriptivos. 
Indicadores de uso de IA vs. 
aporte propio 
Estructura lógica, no “pegado” 
de snippets sin adaptación. 
Explicación en el README de 
decisiones de diseño. 
Código “hand‑made” que 
aproveche el framework, no 
copiado. 
Tests que cubran escenarios, 
no solo importación de librerías. 
Documentación escrita por el 
candidato, no genérica. 
Historial coherente, no un solo 
commit enorme. 
Interacciones intuitivas, labels, foco. Explicación de elecciones de 
UX; no solo “funciona”. 
Originalidad y 
justificación 
Notas en el README sobre retos y 
soluciones. 
Reflexión personal y referencias 
a fuentes/IA usadas.

Nota sobre IA: 
● Está permitido consultar ChatGPT, Copilot u otras herramientas, pero debe 
anotarse en el README qué partes fueron generadas por IA y cómo se 
adaptaron. 
● El candidato debe justificar cada fragmento “sacado” de IA, mostrar que 
entendió y personalizó el código. 
● Para la inclusión de grandes bloques “copy‑paste” escribir los prompts 
utilizados en el README. 