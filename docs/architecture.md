# Arquitectura del Sistema - MediControl

## Resumen
MediControl es un Sistema de Gestión de Consultorios Médicos diseñado bajo principios de Clean Architecture y Single Responsibility Principle (SRP).

## Componentes Principales
### Frontend (Angular)
- **Standalone Components**: Arquitectura modular sin NgModules.
- **Signals**: Gestión reactiva del estado sin dependencia pesada de RxJS.
- **Tailwind CSS & PrimeNG**: Interfaz dinámica, moderna y altamente responsiva para optimizar el flujo de trabajo clínico.

### Backend (Laravel 12)
- **Servicios y Acciones**: Los controladores son ligeros y delegan la lógica de negocio a los servicios (ej. `AuthService`), cumpliendo principios SOLID.
- **Passport (OAuth2)**: Autenticación segura mediante tokens, esencial para accesos médicos.
- **Modelos**: Las bases de datos relacionales integran rigurosamente `User`, `Patient`, `Doctor`, `Appointment` y `MedicalRecord`.

## Patrones Adicionales
- **Client-Server Separation**: Desacoplamiento total a través de API REST.
- **Token-based Security**: Uso de Bearer Tokens (sin estados locales en servidor).
