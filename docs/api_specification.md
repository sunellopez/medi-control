# Especificación de API REST - MediControl

## Autenticación (Público)
### `POST /api/auth/login`
- **Body**: `{ "email": "...", "password": "..." }`
- **Response**: `{ "user": {...}, "access_token": "...", "token_type": "Bearer" }`

### `POST /api/auth/register`
- **Body**: `{ "name": "...", "email": "...", "password": "...", "password_confirmation": "...", "role": "patient|doctor" }`
- **Response**: `{ "user": {...}, "access_token": "..." }`

## Endpoints Seguros (Requieren Bearer Token)

### Usuarios
- `GET /api/auth/user` - Retorna los datos y rol del usuario logueado.

### Pacientes (Sugerido para implementación posterior)
- `GET /api/patients` - Lista los pacientes del consultorio.
- `POST /api/patients` - Registra un nuevo paciente (`first_name`, `last_name`, `date_of_birth`, etc).

> Nota: Todas las respuestas de error en formato JSON devuelven cabeceras 4XX/5XX, consistentes para facilidad de integración con interceptores Angular.
