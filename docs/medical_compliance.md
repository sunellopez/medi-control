# Cumplimiento y Seguridad de Datos Médicos (Compliance)

## Confidencialidad
El sistema **MediControl** establece directrices de base inspiradas en regulaciones médicas como la **Ley HIPAA** (EE.UU.) y el **RGPD** (Europa) o equivalentes nacionales:

- **Acceso Basado en Roles (RBAC)**: Solo el personal autorizado (médicos, administradores) tiene privilegios completos para acceder y modificar prescripciones o notas médicas (MedicalRecords).
- **Protección de Identidad**: Toda contraseña se gestiona a través de *Bcrypt* con un sistema inviolable administrado por Laravel.

## Trazabilidad y Seguridad de Transporte
- **OAuth2 con Passport**: En lugar de cookies vulnerables, se utilizan Bearer Access Tokens de ciclo de vida configurado, lo que impide la suplantación.
- **Integridad Referencial de DB**: El diseño de la base de datos restringe la pérdida accidental o eliminación en cascada no deseada (con uso de NullOnDelete para IDs de citas).

## Recomendaciones para Producción
1. Servir estrictamente TODO el contenido sobre protocolos **HTTPS/WSS**.
2. Restringir y auditar los accesos al archivo `database.sqlite` o a la base de datos transaccional en producción (MySQL/PostgreSQL).
