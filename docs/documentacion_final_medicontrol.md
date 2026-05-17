# DOCUMENTACIÓN FINAL DEL PROYECTO
## Sistema MediControl - Gestión de Consultorio Médico

---

**Universidad Hipócrates**  
**Ingeniería de Software**  
**Proyecto Final**

**Nombre del Proyecto:** MediControl - Sistema de Gestión de Consultorios Médicos  
**Versión:** 1.0  
**Fecha de Elaboración:** 27 de Enero de 2026  
**Equipo de Desarrollo:** Sunel Adalid López Dámaso

---

# ÍNDICE

1. [Descripción del Proyecto](#1-descripción-del-proyecto)
2. [Modelado y Análisis](#2-modelado-y-análisis)
   - 2.1 Diagramas de Casos de Uso
   - 2.2 Diagramas de Clases
   - 2.3 Análisis de Requisitos
3. [Diseño y Documentación](#3-diseño-y-documentación)
   - 3.1 Arquitectura del Sistema
   - 3.2 Diseño de Componentes
   - 3.3 Diseño de Interfaces
   - 3.4 Lógica de Negocio
4. [Especificaciones Técnicas](#4-especificaciones-técnicas)
5. [Manual del Usuario](#5-manual-del-usuario)
6. [Manual Técnico](#6-manual-técnico)
7. [Conclusiones](#7-conclusiones)

---

# 1. DESCRIPCIÓN DEL PROYECTO

## 1.1 Propósito

**MediControl** es un sistema web diseñado para la gestión integral de consultorios médicos, que automatiza procesos administrativos y clínicos, eliminando la dependencia de documentos físicos y hojas de cálculo dispersas.

## 1.2 Alcance

El sistema permitirá:
- Gestionar la agenda de citas médicas con validación automática de disponibilidad
- Mantener expedientes clínicos electrónicos completos y seguros
- Controlar el inventario de medicamentos con alertas de stock y caducidad
- Enviar recordatorios automáticos a pacientes vía SMS y email
- Generar reportes estadísticos y análisis de métricas del consultorio

## 1.3 Requisitos del Sistema

### Requisitos Funcionales Principales:
1. **RF01:** Gestión de Agenda de Citas
2. **RF02:** Expediente Clínico Electrónico
3. **RF03:** Control de Inventario de Medicamentos
4. **RF04:** Sistema de Recordatorios Automáticos
5. **RF05:** Generación de Reportes y Estadísticas

### Requisitos No Funcionales Críticos:
- Tiempo de respuesta < 2 segundos
- Disponibilidad del 99%
- Seguridad: Autenticación JWT + Encriptación bcrypt
- Compatibilidad: Chrome 90+, Firefox 88+, Edge 90+
- Responsive design para tablets y escritorio

## 1.4 Objetivos

### Objetivo General:
Desarrollar un sistema web que optimice la gestión administrativa y clínica de consultorios médicos, reduciendo errores operativos y mejorando la experiencia del paciente.

### Objetivos Específicos:
1. Reducir el tiempo administrativo en un 70%
2. Disminuir errores de agendamiento en un 95%
3. Mejorar la confirmación de citas de 80% a 95%
4. Eliminar desabasto de medicamentos mediante alertas automáticas
5. Proporcionar métricas en tiempo real para toma de decisiones

---

# 2. MODELADO Y ANÁLISIS

## 2.1 Diagramas de Casos de Uso

### Caso de Uso 1: Gestión de Citas

```
┌─────────────────────────────────────────────────────────┐
│                 GESTIÓN DE CITAS                        │
├─────────────────────────────────────────────────────────┤
│                                                         │
│   ┌──────────┐                                         │
│   │Recepción │                                         │
│   │          │─────→ (Agendar Cita)                   │
│   └──────────┘         │                               │
│        │               ├───→ (Verificar Disponibilidad)│
│        │               │                               │
│        └──────→ (Modificar Cita)                       │
│                        │                               │
│                        ├───→ (Validar Conflictos)      │
│                        │                               │
│                 (Cancelar Cita)                        │
│                        │                               │
│                        └───→ (Notificar al Paciente)   │
│                                                         │
│   Sistema envía recordatorio automático 24h antes      │
│                                                         │
└─────────────────────────────────────────────────────────┘

Actores: Recepcionista, Sistema (actor secundario)
Precondiciones: Paciente debe estar registrado en el sistema
Postcondiciones: Cita registrada y recordatorio programado
```

**Flujo Principal:**
1. Recepcionista selecciona "Nueva Cita"
2. Sistema muestra formulario de búsqueda de paciente
3. Recepcionista busca paciente por nombre o ID
4. Sistema muestra datos del paciente y calendario de médicos
5. Recepcionista selecciona fecha, hora y médico
6. Sistema valida disponibilidad
7. Recepcionista confirma registro
8. Sistema programa recordatorio automático
9. Sistema muestra confirmación con número de folio

**Flujos Alternativos:**
- 6a. Si el horario no está disponible → mostrar alternativas cercanas
- 3a. Si el paciente no existe → opción de registrar nuevo paciente
- 8a. Si falla envío de recordatorio → marcar como pendiente de envío

---

### Caso de Uso 2: Consulta de Expediente Clínico

```
┌─────────────────────────────────────────────────────────┐
│            CONSULTA DE EXPEDIENTE CLÍNICO               │
├─────────────────────────────────────────────────────────┤
│                                                         │
│   ┌──────────┐                                         │
│   │  Médico  │                                         │
│   │          │─────→ (Buscar Paciente)                │
│   └──────────┘         │                               │
│                        ├───→ (Ver Historial Completo)  │
│                        │                               │
│                 (Registrar Consulta)                   │
│                        │                               │
│                        ├───→ (Diagnóstico)             │
│                        ├───→ (Tratamiento)             │
│                        └───→ (Receta Médica)           │
│                                                         │
│                 (Generar PDF)                          │
│                        │                               │
│                        └───→ [Documento imprimible]    │
│                                                         │
└─────────────────────────────────────────────────────────┘

Actores: Médico
Precondiciones: Médico autenticado, Paciente con cita agendada
Postcondiciones: Consulta registrada en expediente permanentemente
```

**Flujo Principal:**
1. Médico busca paciente en sistema
2. Sistema muestra expediente completo ordenado cronológicamente
3. Médico revisa historial de consultas anteriores
4. Médico selecciona "Nueva Consulta"
5. Sistema presenta formulario estructurado
6. Médico registra: síntomas, diagnóstico, tratamiento, estudios
7. Sistema valida campos obligatorios
8. Médico guarda consulta
9. Sistema registra fecha/hora y médico automáticamente
10. Sistema ofrece opción de imprimir receta en PDF

---

### Caso de Uso 3: Control de Inventario

```
┌─────────────────────────────────────────────────────────┐
│            CONTROL DE INVENTARIO                        │
├─────────────────────────────────────────────────────────┤
│                                                         │
│   ┌──────────┐                                         │
│   │Recepción │                                         │
│   │   o      │─────→ (Registrar Entrada)              │
│   │ Médico   │         │                               │
│   └──────────┘         ├───→ (Validar Lote)           │
│                        │                               │
│                 (Registrar Salida)                     │
│                        │                               │
│                        ├───→ (Actualizar Stock)        │
│                        │                               │
│   ┌──────────┐         └───→ (Verificar Mínimo)       │
│   │ Sistema  │                      │                  │
│   │          │←──── [Stock < Mínimo]                  │
│   └─────┬────┘                                         │
│         │                                              │
│         └────→ (Generar Alerta en Dashboard)          │
│                                                         │
└─────────────────────────────────────────────────────────┘

Actores: Recepcionista, Médico, Sistema
Precondiciones: Medicamento debe estar en catálogo
Postcondiciones: Stock actualizado y alertas generadas si aplica
```

---

## 2.2 Diagramas de Clases

### Diagrama de Clases Principal

```
┌─────────────────────────────┐
│         Usuario             │
├─────────────────────────────┤
│ - id: int                   │
│ - nombre: string            │
│ - email: string             │
│ - password: string          │
│ - rol: enum                 │
│ - activo: boolean           │
├─────────────────────────────┤
│ + login()                   │
│ + logout()                  │
│ + cambiarPassword()         │
└──────────┬──────────────────┘
           │
           │ ◄──hereda
           │
    ┌──────┴───────┬──────────────┐
    │              │              │
┌───▼──────┐  ┌───▼──────┐  ┌───▼──────┐
│  Médico  │  │Recepción │  │  Admin   │
├──────────┤  ├──────────┤  ├──────────┤
│-cedula   │  │-turno    │  │-permisos │
│-espec.   │  │-sucursal │  │          │
├──────────┤  ├──────────┤  ├──────────┤
│+atender()│  │+agendar()│  │+config() │
└──────────┘  └──────────┘  └──────────┘


┌─────────────────────────────┐
│         Paciente            │
├─────────────────────────────┤
│ - id: int                   │
│ - nombre: string            │
│ - fechaNacimiento: date     │
│ - sexo: char                │
│ - telefono: string          │
│ - email: string             │
│ - tipoSangre: string        │
│ - alergias: text            │
├─────────────────────────────┤
│ + registrar()               │
│ + actualizar()              │
│ + buscar()                  │
└──────────┬──────────────────┘
           │
           │ 1 tiene *
           │
┌──────────▼──────────────────┐
│          Cita               │
├─────────────────────────────┤
│ - id: int                   │
│ - paciente_id: int          │
│ - medico_id: int            │
│ - fecha: date               │
│ - hora: time                │
│ - tipo: string              │
│ - estado: enum              │
│ - motivo: text              │
│ - recordatorioEnviado: bool │
├─────────────────────────────┤
│ + agendar()                 │
│ + modificar()               │
│ + cancelar()                │
│ + enviarRecordatorio()      │
└──────────┬──────────────────┘
           │
           │ * pertenece a 1
           │
┌──────────▼──────────────────┐
│       Expediente            │
├─────────────────────────────┤
│ - id: int                   │
│ - paciente_id: int          │
│ - medico_id: int            │
│ - fecha: datetime           │
│ - sintomas: text            │
│ - diagnostico: text         │
│ - tratamiento: text         │
│ - observaciones: text       │
│ - presionArterial: string   │
│ - peso: decimal             │
│ - estatura: decimal         │
├─────────────────────────────┤
│ + registrarConsulta()       │
│ + consultarHistorial()      │
│ + generarPDF()              │
└─────────────────────────────┘


┌─────────────────────────────┐
│       Medicamento           │
├─────────────────────────────┤
│ - id: int                   │
│ - nombre: string            │
│ - descripcion: text         │
│ - lote: string              │
│ - fechaCaducidad: date      │
│ - stockActual: int          │
│ - stockMinimo: int          │
│ - precioUnitario: decimal   │
├─────────────────────────────┤
│ + registrar()               │
│ + actualizarStock()         │
│ + verificarCaducidad()      │
│ + generarAlerta()           │
└──────────┬──────────────────┘
           │
           │ 1 tiene *
           │
┌──────────▼──────────────────┐
│   MovimientoInventario      │
├─────────────────────────────┤
│ - id: int                   │
│ - medicamento_id: int       │
│ - tipo: enum (E/S/A)        │
│ - cantidad: int             │
│ - fecha: datetime           │
│ - usuario_id: int           │
│ - motivo: string            │
├─────────────────────────────┤
│ + registrarEntrada()        │
│ + registrarSalida()         │
│ + registrarAjuste()         │
└─────────────────────────────┘
```

**Relaciones:**
- Usuario (1) → (0..*) Cita [realiza/registra]
- Paciente (1) → (0..*) Cita [tiene]
- Médico (1) → (0..*) Cita [atiende]
- Paciente (1) → (0..*) Expediente [posee]
- Médico (1) → (0..*) Expediente [genera]
- Medicamento (1) → (0..*) MovimientoInventario [registra]

---

## 2.3 Análisis Detallado de Requisitos

### 2.3.1 Identificación de Necesidades del Usuario

**Stakeholders Identificados:**

| Stakeholder | Necesidad Principal | Frecuencia de Uso |
|-------------|---------------------|-------------------|
| Recepcionista | Agendar citas rápidamente sin conflictos | Diaria (50-80 citas/día) |
| Médico | Acceso inmediato a historial completo del paciente | Diaria (15-25 consultas/día) |
| Administrador | Reportes de rendimiento y métricas financieras | Semanal/Mensual |
| Paciente | Recibir recordatorios oportunos | Ocasional (1-2 veces/mes) |

**Análisis de Requisitos por Actor:**

**Recepcionista necesita:**
- Búsqueda rápida de pacientes (< 1 segundo)
- Vista de calendario con disponibilidad en tiempo real
- Registro de nuevos pacientes en < 2 minutos
- Confirmación visual inmediata de citas guardadas
- Capacidad de imprimir comprobantes

**Médico necesita:**
- Historial completo en una sola pantalla
- Formularios con autocompletado de diagnósticos comunes
- Generación de recetas en PDF con un clic
- Acceso a información del paciente durante la consulta
- Registro de consulta en < 3 minutos

**Administrador necesita:**
- Dashboard con métricas principales al login
- Exportación de reportes a Excel para análisis externo
- Configuración de horarios y días no laborales
- Gestión de usuarios (altas, bajas, permisos)
- Auditoría de acciones críticas

---

### 2.3.2 Análisis de Requisitos Funcionales Detallado

**RF01: Gestión de Agenda de Citas**

| Aspecto | Detalle |
|---------|---------|
| **Entrada** | ID Paciente, Médico seleccionado, Fecha, Hora, Tipo de consulta, Motivo |
| **Validaciones** | - Fecha no puede ser en el pasado<br>- Hora dentro de horario laboral (8am-8pm)<br>- Médico disponible en ese horario<br>- No conflicto con otras citas<br>- Paciente no debe tener otra cita el mismo día |
| **Proceso** | 1. Verificar disponibilidad en BD<br>2. Crear registro de cita<br>3. Programar job de recordatorio<br>4. Generar número de folio único<br>5. Commit de transacción |
| **Salida** | Confirmación con folio, fecha, hora, médico. Recordatorio programado |
| **Reglas de Negocio** | - Máximo 12 citas por médico por día<br>- Duración estándar: 30 minutos<br>- No agendar en domingos ni días festivos<br>- Recordatorio enviado 24 hrs antes a las 10am |

**RF02: Expediente Clínico Electrónico**

| Aspecto | Detalle |
|---------|---------|
| **Entrada** | Síntomas, Diagnóstico, Tratamiento, Signos vitales, Observaciones |
| **Validaciones** | - Campos obligatorios: Diagnóstico y Tratamiento<br>- Diagnóstico mínimo 10 caracteres<br>- Presión arterial formato XX/XX<br>- Peso y estatura valores numéricos positivos |
| **Proceso** | 1. Cargar historial existente<br>2. Mostrar últimas 5 consultas destacadas<br>3. Permitir registro de nueva consulta<br>4. Asociar automáticamente médico y timestamp<br>5. Guardar con trigger de auditoría |
| **Salida** | Expediente actualizado, opción de generar PDF con consulta actual |
| **Reglas de Negocio** | - Solo médicos pueden modificar expedientes<br>- Cada cambio queda auditado<br>- Expedientes retenidos por 5 años<br>- No se pueden eliminar consultas, solo marcar como anuladas |

**RF03: Control de Inventario de Medicamentos**

| Aspecto | Detalle |
|---------|---------|
| **Entrada** | Nombre medicamento, Lote, Cantidad, Fecha caducidad, Tipo movimiento (E/S/A) |
| **Validaciones** | - Cantidad debe ser entero positivo<br>- Fecha de caducidad debe ser futura<br>- Stock no puede ser negativo<br>- Lote debe ser único por medicamento |
| **Proceso** | 1. Registrar movimiento en tabla MovimientoInventario<br>2. Actualizar stock_actual en tabla Medicamentos<br>3. Verificar si stock < stock_minimo → generar alerta<br>4. Verificar si fecha_caducidad - 30 días → generar alerta |
| **Salida** | Stock actualizado, alertas generadas si aplica, log de movimiento |
| **Reglas de Negocio** | - Stock mínimo por defecto: 10 unidades<br>- Alertas visibles en dashboard<br>- Salidas requieren motivo obligatorio<br>- Medicamentos caducados bloqueados para salida |

---

### 2.3.3 Casos de Uso Expandidos

**Caso de Uso Expandido: Agendar Cita**

**ID:** CU-01  
**Nombre:** Agendar Cita Médica  
**Actores:** Recepcionista (primario), Sistema (secundario)  
**Tipo:** Primario, Esencial  
**Descripción:** Permite registrar una nueva cita para un paciente con validación de disponibilidad

**Precondiciones:**
- Recepcionista autenticado en el sistema
- Paciente debe estar registrado (o registrarse en el momento)
- Al menos un médico debe tener horarios disponibles

**Postcondiciones:**
- **Éxito:** Cita registrada en BD, recordatorio programado, folio generado
- **Fallo:** Sistema muestra error y no se crea registro

**Flujo Normal:**
1. Recepcionista selecciona "Nueva Cita" en menú principal
2. Sistema muestra formulario de búsqueda de paciente
3. Recepcionista ingresa nombre o ID del paciente y presiona "Buscar"
4. Sistema consulta base de datos y muestra datos del paciente
5. Recepcionista confirma que es el paciente correcto
6. Sistema muestra calendario con disponibilidad de médicos
7. Recepcionista selecciona: Médico, Fecha, Hora, Tipo de consulta
8. Recepcionista ingresa motivo de la consulta (opcional)
9. Recepcionista marca opciones: ☑ Enviar recordatorio Email ☑ SMS
10. Recepcionista presiona "Guardar Cita"
11. Sistema valida disponibilidad del médico en horario seleccionado
12. Sistema verifica que no hay conflictos
13. Sistema genera número de folio único (formato: CITA-YYYYMMDD-XXXX)
14. Sistema registra cita en base de datos
15. Sistema programa job para envío de recordatorio 24 horas antes
16. Sistema muestra confirmación: "Cita registrada exitosamente. Folio: CITA-20260125-0042"
17. Sistema ofrece opción de imprimir comprobante
18. Caso de uso termina

**Flujos Alternativos:**

**4a. Paciente no encontrado:**
- 4a.1. Sistema muestra mensaje "Paciente no encontrado"
- 4a.2. Sistema ofrece botón "Registrar Nuevo Paciente"
- 4a.3. Si recepcionista acepta, sistema abre formulario de registro (ver CU-05)
- 4a.4. Después de registro exitoso, continúa en paso 5

**11a. Horario no disponible:**
- 11a.1. Sistema muestra mensaje "El horario seleccionado no está disponible"
- 11a.2. Sistema sugiere 3 horarios alternativos más cercanos
- 11a.3. Recepcionista puede seleccionar alternativa o cambiar médico/fecha
- 11a.4. Flujo continúa en paso 7

**12a. Paciente ya tiene cita ese día:**
- 12a.1. Sistema muestra advertencia "El paciente ya tiene una cita el [fecha] a las [hora]"
- 12a.2. Sistema pregunta "¿Desea agendar de todas formas?"
- 12a.3. Si recepcionista confirma, continúa en paso 13
- 12a.4. Si recepcionista cancela, regresa a paso 6

**Frecuencia de Uso:** 50-80 veces por día  
**Prioridad:** Alta (Crítica para operación)  
**Complejidad:** Media

---

# 3. DISEÑO Y DOCUMENTACIÓN

## 3.1 Arquitectura del Sistema

### 3.1.1 Arquitectura General

**Patrón Arquitectónico:** Arquitectura de 3 Capas + API REST

```
                    CAPA DE PRESENTACIÓN
┌─────────────────────────────────────────────────────────┐
│                                                         │
│              ANGULAR 17 (SPA Frontend)                  │
│                                                         │
│  ┌──────────┐  ┌──────────┐  ┌──────────┐            │
│  │Dashboard │  │ Citas    │  │Expediente│            │
│  │Component │  │Component │  │Component │  ...       │
│  └──────────┘  └──────────┘  └──────────┘            │
│                                                         │
│  ┌─────────────────────────────────────────────┐      │
│  │    Services (HTTP Client + RxJS)            │      │
│  │    - AuthService                             │      │
│  │    - PacientesService                        │      │
│  │    - CitasService                            │      │
│  │    - ExpedientesService                      │      │
│  └─────────────────────────────────────────────┘      │
│                                                         │
│  Guards: AuthGuard, RoleGuard                          │
│  Interceptors: TokenInterceptor, ErrorInterceptor      │
│                                                         │
└────────────────────┬────────────────────────────────────┘
                     │
                     │ HTTP/REST API
                     │ JSON + JWT Token
                     │
┌────────────────────▼────────────────────────────────────┐
│                                                         │
│              LARAVEL 11 (Backend API)                   │
│                                                         │
│  ┌──────────────────────────────────────────────┐     │
│  │              API ROUTES                       │     │
│  │  - POST   /api/auth/login                    │     │
│  │  - GET    /api/pacientes                     │     │
│  │  - POST   /api/citas                         │     │
│  │  - GET    /api/expedientes/{id}              │     │
│  └──────────────────────────────────────────────┘     │
│                                                         │
│  ┌──────────────────────────────────────────────┐     │
│  │            CONTROLLERS                        │     │
│  │  AuthController, PacientesController, etc.   │     │
│  └──────────────────────────────────────────────┘     │
│                                                         │
│  ┌──────────────────────────────────────────────┐     │
│  │         MIDDLEWARE STACK                      │     │
│  │  - Sanctum Authentication                     │     │
│  │  - CORS Headers                               │     │
│  │  - Request Validation                         │     │
│  │  - Rate Limiting                              │     │
│  └──────────────────────────────────────────────┘     │
│                                                         │
│  ┌──────────────────────────────────────────────┐     │
│  │        BUSINESS LOGIC (Services)              │     │
│  │  - CitaService (validación disponibilidad)   │     │
│  │  - NotificationService (envío recordatorios) │     │
│  │  - InventarioService (alertas stock)         │     │
│  └──────────────────────────────────────────────┘     │
│                                                         │
│  ┌──────────────────────────────────────────────┐     │
│  │           ELOQUENT MODELS (ORM)               │     │
│  │  Paciente, Cita, Expediente, Medicamento...  │     │
│  └──────────────────────────────────────────────┘     │
│                                                         │
│  ┌──────────────────────────────────────────────┐     │
│  │            JOBS & QUEUES                      │     │
│  │  - EnviarRecordatorioJob (SMS/Email)         │     │
│  │  - VerificarInventarioJob (Stock diario)     │     │
│  └──────────────────────────────────────────────┘     │
│                                                         │
└────────────────────┬────────────────────────────────────┘
                     │
                     │ Eloquent ORM
                     │ PDO + Prepared Statements
                     │
┌────────────────────▼────────────────────────────────────┐
│                                                         │
│               MYSQL 8.0 (Base de Datos)                │
│                                                         │
│  ┌──────────┐  ┌──────────┐  ┌───────────┐           │
│  │  users   │  │pacientes │  │   citas   │  ...      │
│  └──────────┘  └──────────┘  └───────────┘           │
│                                                         │
│  Stored Procedures (opcional para reportes complejos)  │
│  Triggers para auditoría automática                    │
│  Índices en columnas de búsqueda frecuente            │
│                                                         │
└─────────────────────────────────────────────────────────┘


              SERVICIOS EXTERNOS (INTEGRACIONES)
┌──────────────────────────────────────────────────────────┐
│                                                          │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐  │
│  │ Gmail SMTP   │  │   Twilio     │  │   DomPDF     │  │
│  │ (Emails)     │  │   (SMS)      │  │  (PDFs)      │  │
│  └──────────────┘  └──────────────┘  └──────────────┘  │
│                                                          │
└──────────────────────────────────────────────────────────┘
```

### 3.1.2 Flujo de Autenticación

```
1. Usuario ingresa credenciales en Angular

2. AuthService envía POST /api/auth/login
   {
     "email": "medico@clinica.com",
     "password": "******"
   }

3. AuthController valida credenciales

4. Si válido: Laravel genera token JWT con Sanctum
   {
     "access_token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
     "token_type": "Bearer",
     "user": {
       "id": 1,
       "nombre": "Dr. Juan Pérez",
       "rol": "medico"
     }
   }

5. Angular guarda token en sessionStorage

6. Todas las peticiones posteriores incluyen header:
   Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGc...

7. Middleware Sanctum valida token en cada request

8. Si token inválido/expirado: retorna 401 Unauthorized
   → Angular redirige a login automáticamente
```

---

## 3.2 Diseño de Componentes

### 3.2.1 Componentes Frontend (Angular)

**Estructura de Carpetas:**
```
src/
├── app/
│   ├── core/
│   │   ├── guards/
│   │   │   ├── auth.guard.ts
│   │   │   └── role.guard.ts
│   │   ├── interceptors/
│   │   │   ├── token.interceptor.ts
│   │   │   └── error.interceptor.ts
│   │   └── services/
│   │       └── api.service.ts
│   │
│   ├── shared/
│   │   ├── components/
│   │   │   ├── navbar/
│   │   │   ├── sidebar/
│   │   │   └── loading-spinner/
│   │   └── pipes/
│   │       └── fecha-formato.pipe.ts
│   │
│   ├── features/
│   │   ├── auth/
│   │   │   ├── login/
│   │   │   └── auth.service.ts
│   │   │
│   │   ├── dashboard/
│   │   │   ├── dashboard.component.ts
│   │   │   ├── dashboard.component.html
│   │   │   └── dashboard.component.scss
│   │   │
│   │   ├── pacientes/
│   │   │   ├── lista-pacientes/
│   │   │   ├── form-paciente/
│   │   │   └── pacientes.service.ts
│   │   │
│   │   ├── citas/
│   │   │   ├── agenda/
│   │   │   ├── form-cita/
│   │   │   └── citas.service.ts
│   │   │
│   │   ├── expedientes/
│   │   │   ├── ver-expediente/
│   │   │   ├── registrar-consulta/
│   │   │   └── expedientes.service.ts
│   │   │
│   │   ├── inventario/
│   │   │   ├── lista-medicamentos/
│   │   │   ├── movimientos/
│   │   │   └── inventario.service.ts
│   │   │
│   │   └── reportes/
│   │       ├── dashboard-reportes/
│   │       └── reportes.service.ts
│   │
│   └── app-routing.module.ts
```

**Componente Ejemplo: Dashboard**

```typescript
// dashboard.component.ts
import { Component, OnInit } from '@angular/core';
import { DashboardService } from './dashboard.service';
import { Chart } from 'chart.js';

@Component({
  selector: 'app-dashboard',
  templateUrl: './dashboard.component.html',
  styleUrls: ['./dashboard.component.scss']
})
export class DashboardComponent implements OnInit {
  estadisticas = {
    citasHoy: 0,
    pacientesActivos: 0,
    medicamentosStockBajo: 0
  };
  
  citasHoy: any[] = [];
  alertas: any[] = [];
  cargando = true;

  constructor(private dashboardService: DashboardService) {}

  ngOnInit(): void {
    this.cargarDatos();
  }

  cargarDatos(): void {
    this.dashboardService.getEstadisticas().subscribe({
      next: (data) => {
        this.estadisticas = data.estadisticas;
        this.citasHoy = data.citasHoy;
        this.alertas = data.alertas;
        this.cargando = false;
        this.renderizarGraficas();
      },
      error: (error) => {
        console.error('Error al cargar dashboard:', error);
        this.cargando = false;
      }
    });
  }

  renderizarGraficas(): void {
    // Implementación de Chart.js
    const ctx = document.getElementById('grafica-citas') as HTMLCanvasElement;
    new Chart(ctx, {
      type: 'line',
      data: {
        // Configuración de datos
      },
      options: {
        responsive: true
      }
    });
  }
}
```

---

### 3.2.2 Componentes Backend (Laravel)

**Estructura de Carpetas:**
```
app/
├── Http/
│   ├── Controllers/
│   │   ├── AuthController.php
│   │   ├── PacienteController.php
│   │   ├── CitaController.php
│   │   ├── ExpedienteController.php
│   │   ├── MedicamentoController.php
│   │   └── ReporteController.php
│   │
│   ├── Middleware/
│   │   ├── CheckRole.php
│   │   └── ValidateCitaDisponibilidad.php
│   │
│   └── Requests/
│       ├── StoreCitaRequest.php
│       ├── StorePacienteRequest.php
│       └── StoreExpedienteRequest.php
│
├── Models/
│   ├── User.php
│   ├── Paciente.php
│   ├── Cita.php
│   ├── Expediente.php
│   ├── Medicamento.php
│   └── MovimientoInventario.php
│
├── Services/
│   ├── CitaService.php
│   ├── NotificationService.php
│   └── InventarioService.php
│
└── Jobs/
    ├── EnviarRecordatorioJob.php
    └── VerificarInventarioJob.php
```

**Controller Ejemplo: CitaController**

```php
<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Http\Requests\StoreCitaRequest;
use App\Services\CitaService;
use Illuminate\Http\JsonResponse;

class CitaController extends Controller
{
    protected $citaService;

    public function __construct(CitaService $citaService)
    {
        $this->citaService = $citaService;
    }

    /**
     * Lista todas las citas (con filtros opcionales)
     */
    public function index(): JsonResponse
    {
        $citas = Cita::with(['paciente', 'medico'])
            ->when(request('fecha'), function($query) {
                return $query->whereDate('fecha', request('fecha'));
            })
            ->when(request('medico_id'), function($query) {
                return $query->where('medico_id', request('medico_id'));
            })
            ->orderBy('fecha', 'desc')
            ->orderBy('hora', 'desc')
            ->paginate(20);

        return response()->json($citas);
    }

    /**
     * Crea una nueva cita
     */
    public function store(StoreCitaRequest $request): JsonResponse
    {
        try {
            // Validar disponibilidad usando servicio
            $disponible = $this->citaService->verificarDisponibilidad(
                $request->medico_id,
                $request->fecha,
                $request->hora
            );

            if (!$disponible) {
                return response()->json([
                    'message' => 'El horario seleccionado no está disponible',
                    'sugerencias' => $this->citaService->sugerirHorarios(
                        $request->medico_id,
                        $request->fecha
                    )
                ], 422);
            }

            // Crear cita
            $cita = Cita::create($request->validated());

            // Programar recordatorio
            $this->citaService->programarRecordatorio($cita);

            return response()->json([
                'message' => 'Cita registrada exitosamente',
                'cita' => $cita->load(['paciente', 'medico']),
                'folio' => $cita->folio
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al registrar cita',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Actualiza una cita existente
     */
    public function update(StoreCitaRequest $request, Cita $cita): JsonResponse
    {
        try {
            $cita->update($request->validated());
            
            return response()->json([
                'message' => 'Cita actualizada exitosamente',
                'cita' => $cita->load(['paciente', 'medico'])
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al actualizar cita',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cancela una cita
     */
    public function destroy(Cita $cita): JsonResponse
    {
        try {
            $cita->update(['estado' => 'cancelada']);
            
            // Notificar al paciente
            $this->citaService->notificarCancelacion($cita);

            return response()->json([
                'message' => 'Cita cancelada exitosamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al cancelar cita',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
```

---

## 3.3 Diseño de Interfaces

### 3.3.1 Pantalla de Login

**Descripción:** Pantalla de autenticación con validación de credenciales

**Elementos:**
- Logo de MediControl
- Campo de email
- Campo de contraseña
- Checkbox "Recordarme"
- Botón "Iniciar Sesión"
- Enlace "¿Olvidaste tu contraseña?"

**Validaciones:**
- Email formato válido
- Contraseña mínimo 6 caracteres
- Mensaje de error claro si credenciales incorrectas
- Deshabilitar botón durante proceso de login (evitar doble clic)

**Wireframe:**
```
┌─────────────────────────────────────────────────────────┐
│                                                         │
│                   [Logo MediControl]                    │
│                                                         │
│              Iniciar Sesión en el Sistema               │
│                                                         │
│  ┌───────────────────────────────────────────────────┐  │
│  │ Email:                                            │  │
│  │ [___________________________________________]     │  │
│  │                                                   │  │
│  │ Contraseña:                                       │  │
│  │ [___________________________________________] 👁   │  │
│  │                                                   │  │
│  │ ☐ Recordarme en este dispositivo                 │  │
│  │                                                   │  │
│  │              [  Iniciar Sesión  ]                │  │
│  │                                                   │  │
│  │          ¿Olvidaste tu contraseña?                │  │
│  │                                                   │  │
│  └───────────────────────────────────────────────────┘  │
│                                                         │
│                                                         │
│           © 2026 MediControl - v1.0                     │
└─────────────────────────────────────────────────────────┘
```

---

### 3.3.2 Dashboard Principal

**Descripción:** Pantalla inicial que muestra resumen de actividad del día

**Elementos:**
- Menú lateral con navegación principal
- Header con nombre de usuario y botón de salir
- 3 tarjetas con métricas principales (Citas Hoy, Pacientes Activos, Medicamentos Stock Bajo)
- Lista de citas del día con estados
- Sección de alertas destacadas
- Gráfica de consultas de la semana

**Interacciones:**
- Click en cita → Ver detalle o marcar como atendida
- Click en alerta → Navegar a sección correspondiente
- Actualización automática cada 5 minutos

**Wireframe:**
```
┌────┬──────────────────────────────────────────────────────────┐
│ ☰  │  MediControl                    Dr. Ana López  [⚙][🚪]│
├────┼──────────────────────────────────────────────────────────┤
│📊  │                                                          │
│Dash│  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐     │
│    │  │   Citas     │  │  Pacientes  │  │ Medicamentos│     │
│📅  │  │     Hoy     │  │   Activos   │  │ Stock Bajo  │     │
│Cita│  │     18      │  │     642     │  │      3      │     │
│    │  └─────────────┘  └─────────────┘  └─────────────┘     │
│👥  │                                                          │
│Pac.│  AGENDA DE HOY - Miércoles 21 de Enero                  │
│    │  ┌──────────────────────────────────────────────────┐   │
│💊  │  │ 09:00 ✓ Dr. Martínez - Juan Pérez (Atendido)    │   │
│Inv.│  │ 09:30 ⏳ Dra. López - María García (En espera)   │   │
│    │  │ 10:00 ⏱ Dr. Sánchez - Carlos Ruiz (Próxima)     │   │
│📊  │  │ 10:30 [ ] Dr. Martínez (Disponible)              │   │
│Rep.│  └──────────────────────────────────────────────────┘   │
│    │                                                          │
│⚙  │  ALERTAS                                                 │
│Conf│  ⚠️ 3 medicamentos requieren reabastecimiento          │
│    │  🔔 5 pacientes no han confirmado cita de hoy           │
│    │  📋 2 expedientes requieren actualización                │
│    │                                                          │
│    │  [+ Nueva Cita]  [Buscar Paciente]  [Ver Reportes]     │
│    │                                                          │
└────┴──────────────────────────────────────────────────────────┘
```

---

### 3.3.3 Formulario de Nueva Cita

**Descripción:** Formulario para agendar citas con validación en tiempo real

**Elementos:**
- Búsqueda de paciente con autocompletado
- Selector de médico (dropdown)
- Date picker para fecha
- Selector de hora (dropdown con horarios disponibles)
- Dropdown de tipo de consulta
- Textarea para motivo
- Checkboxes para recordatorios (Email/SMS)
- Botones: Cancelar, Guardar

**Validaciones en Tiempo Real:**
- Búsqueda de paciente: mínimo 3 caracteres
- Fecha: no puede ser anterior a hoy
- Hora: solo mostrar horarios disponibles del médico seleccionado
- Tipo de consulta: campo obligatorio
- Deshabilitar botón guardar si hay campos inválidos

**Flujo:**
1. Usuario busca paciente
2. Sistema muestra resultados filtrados
3. Usuario selecciona paciente
4. Sistema carga datos y muestra calendario
5. Usuario selecciona médico
6. Sistema filtra horarios disponibles
7. Usuario completa formulario
8. Sistema valida y guarda

**Wireframe:**
```
┌───────────────────────────────────────────────────────────┐
│  ← Volver                  NUEVA CITA                     │
├───────────────────────────────────────────────────────────┤
│                                                           │
│  Buscar Paciente                                          │
│  ┌─────────────────────────────────────────────────────┐  │
│  │ [Juan Pérez_____________________] [🔍 Buscar]      │  │
│  │                                                     │  │
│  │ Resultados:                                         │  │
│  │ ✓ Juan Pérez García (PAC-001234)                   │  │
│  │   Tel: 442-123-4567 | Última visita: 15/12/2025    │  │
│  │                                                     │  │
│  │   [o] Registrar Nuevo Paciente                      │  │
│  └─────────────────────────────────────────────────────┘  │
│                                                           │
│  Detalles de la Cita                                      │
│  ┌─────────────────────────────────────────────────────┐  │
│  │ Médico: *                                           │  │
│  │ [Dra. Ana López ▼]                                  │  │
│  │                                                     │  │
│  │ Fecha: *            Hora: *                         │  │
│  │ [25/01/2026 📅]    [10:00 ▼]                       │  │
│  │                                                     │  │
│  │ Tipo de Consulta: *                                 │  │
│  │ [Consulta General ▼]                                │  │
│  │                                                     │  │
│  │ Motivo:                                             │  │
│  │ [___________________________________________]       │  │
│  │ [___________________________________________]       │  │
│  │                                                     │  │
│  │ Recordatorios:                                      │  │
│  │ ☑ Enviar email 24 hrs antes                         │  │
│  │ ☑ Enviar SMS 24 hrs antes                           │  │
│  └─────────────────────────────────────────────────────┘  │
│                                                           │
│                    [Cancelar]  [Guardar Cita]            │
└───────────────────────────────────────────────────────────┘
```

---

## 3.4 Lógica de Negocio

### 3.4.1 Reglas de Negocio Documentadas

**RN-01: Agendamiento de Citas**
- Duración estándar de consulta: 30 minutos
- Horario laboral: Lunes a Sábado, 8:00 AM - 8:00 PM
- Máximo 12 citas por médico por día
- No se permiten citas en domingos ni días festivos configurados
- No se permite agendar con menos de 2 horas de anticipación
- Paciente no puede tener más de 1 cita el mismo día

**RN-02: Recordatorios**
- Envío automático 24 horas antes de la cita
- Hora de envío: 10:00 AM
- Máximo 3 intentos de envío si falla
- Contenido del mensaje debe incluir: Nombre paciente, Fecha, Hora, Médico, Ubicación
- Paciente puede desactivar recordatorios desde su perfil

**RN-03: Expediente Clínico**
- Campos obligatorios: Diagnóstico, Tratamiento
- Solo el médico que atendió puede modificar su propia consulta
- Expedientes se conservan por 5 años mínimo
- No se pueden eliminar consultas, solo anular con motivo
- Cada modificación genera registro en auditoría
- PDF de receta debe incluir: Logo, Nombre consultorio, Cédula médico, Tratamiento, Fecha

**RN-04: Inventario**
- Stock mínimo por defecto: 10 unidades (configurable por medicamento)
- Alerta de caducidad: 30 días antes
- No permitir salidas si stock resultante < 0
- No permitir salidas de medicamentos caducados
- Movimientos de ajuste requieren autorización de administrador
- Reporte de inventario se genera automáticamente cada inicio de mes

**RN-05: Control de Acceso**
- Roles: Administrador, Médico, Recepcionista
- Administrador: acceso completo
- Médico: solo sus propias citas y expedientes
- Recepcionista: no puede ver expedientes médicos completos
- Sesión expira después de 8 horas de inactividad
- Máximo 3 intentos de login antes de bloqueo temporal (15 minutos)

---

### 3.4.2 Algoritmos Críticos

**Algoritmo: Verificación de Disponibilidad de Cita**

```
FUNCIÓN verificarDisponibilidad(medico_id, fecha, hora):
    // 1. Validar que la fecha no sea pasada
    SI fecha < HOY():
        RETORNAR falso, "No se pueden agendar citas en fechas pasadas"
    
    // 2. Validar día laboral
    dia_semana = obtenerDiaSemana(fecha)
    SI dia_semana == DOMINGO:
        RETORNAR falso, "No se trabaja los domingos"
    
    SI fecha EN dias_festivos_configurados:
        RETORNAR falso, "Día festivo, consultorio cerrado"
    
    // 3. Validar horario laboral
    SI hora < 08:00 O hora > 20:00:
        RETORNAR falso, "Fuera de horario laboral (8am-8pm)"
    
    // 4. Verificar que el médico existe y está activo
    medico = buscarMedico(medico_id)
    SI medico == null O medico.activo == false:
        RETORNAR falso, "Médico no disponible"
    
    // 5. Contar citas del médico ese día
    total_citas = contarCitas(medico_id, fecha)
    SI total_citas >= 12:
        RETORNAR falso, "Médico ha alcanzado máximo de citas del día"
    
    // 6. Verificar conflicto de horario exacto
    cita_existente = buscarCita(medico_id, fecha, hora)
    SI cita_existente != null:
        RETORNAR falso, "Horario ya ocupado"
    
    // 7. Verificar solapamiento (30 min antes/después)
    hora_inicio = hora - 30min
    hora_fin = hora + 30min
    citas_cercanas = buscarCitasEnRango(medico_id, fecha, hora_inicio, hora_fin)
    SI citas_cercanas.longitud > 0:
        RETORNAR falso, "Conflicto con cita cercana"
    
    // 8. Todo validado correctamente
    RETORNAR verdadero, "Horario disponible"
FIN FUNCIÓN
```

**Algoritmo: Generación de Alertas de Inventario**

```
FUNCIÓN generarAlertasInventario():
    alertas = []
    
    // Obtener todos los medicamentos activos
    medicamentos = obtenerMedicamentosActivos()
    
    PARA CADA medicamento EN medicamentos:
        // Alerta 1: Stock bajo
        SI medicamento.stock_actual < medicamento.stock_minimo:
            alerta = {
                tipo: "STOCK_BAJO",
                medicamento: medicamento.nombre,
                stock_actual: medicamento.stock_actual,
                stock_minimo: medicamento.stock_minimo,
                urgencia: calcularUrgencia(medicamento.stock_actual, medicamento.stock_minimo)
            }
            alertas.agregar(alerta)
        
        // Alerta 2: Próximo a caducar (30 días)
        dias_para_caducidad = medicamento.fecha_caducidad - HOY()
        SI dias_para_caducidad <= 30 Y dias_para_caducidad > 0:
            alerta = {
                tipo: "PROXIMO_CADUCIDAD",
                medicamento: medicamento.nombre,
                fecha_caducidad: medicamento.fecha_caducidad,
                dias_restantes: dias_para_caducidad,
                urgencia: calcularUrgenciaCaducidad(dias_para_caducidad)
            }
            alertas.agregar(alerta)
        
        // Alerta 3: Ya caducado
        SI medicamento.fecha_caducidad < HOY():
            alerta = {
                tipo: "CADUCADO",
                medicamento: medicamento.nombre,
                fecha_caducidad: medicamento.fecha_caducidad,
                urgencia: "CRÍTICA"
            }
            alertas.agregar(alerta)
            // Marcar medicamento como no disponible
            medicamento.disponible = false
            medicamento.guardar()
    
    // Guardar alertas en base de datos
    PARA CADA alerta EN alertas:
        guardarAlerta(alerta)
    
    // Notificar al administrador si hay alertas críticas
    alertas_criticas = alertas.filtrar(a => a.urgencia == "CRÍTICA")
    SI alertas_criticas.longitud > 0:
        enviarEmailAdministrador(alertas_criticas)
    
    RETORNAR alertas
FIN FUNCIÓN

FUNCIÓN calcularUrgencia(stock_actual, stock_minimo):
    porcentaje = (stock_actual / stock_minimo) * 100
    SI porcentaje <= 25:
        RETORNAR "CRÍTICA"
    SI NO SI porcentaje <= 50:
        RETORNAR "ALTA"
    SI NO:
        RETORNAR "MEDIA"
FIN FUNCIÓN
```

---

# 4. ESPECIFICACIONES TÉCNICAS

## 4.1 Modelo de Base de Datos

### 4.1.1 Diagrama Entidad-Relación

```
┌────────────────────┐
│       users        │
├────────────────────┤
│ PK │ id            │
│    │ nombre        │
│    │ email (unique)│
│    │ password      │
│    │ rol (enum)    │
│    │ activo        │
│    │ timestamps    │
└─────────┬──────────┘
          │
          │ 1:N hereda
          │
    ┌─────┴─────┐
    │           │
┌───▼────┐ ┌───▼────┐
│ medicos│ │recep.  │
├────────┤ ├────────┤
│PK│id   │ │PK│id   │
│FK│u_id │ │FK│u_id │
│  │ced. │ │  │turn │
│  │esp. │ └────────┘
└───┬────┘
    │
    │ 1:N atiende
    │
┌───▼────────────────┐         ┌────────────────────┐
│       citas        │         │     pacientes      │
├────────────────────┤         ├────────────────────┤
│ PK │ id            │ N:1     │ PK │ id            │
│ FK │ paciente_id   │◄────────│    │ nombre        │
│ FK │ medico_id     │         │    │ fecha_nac     │
│    │ fecha         │         │    │ sexo          │
│    │ hora          │         │    │ telefono      │
│    │ tipo          │         │    │ email         │
│    │ estado (enum) │         │    │ tipo_sangre   │
│    │ motivo        │         │    │ alergias      │
│    │ recordatorio_e│         │    │ direccion     │
│    │ folio (unique)│         │    │ timestamps    │
│    │ timestamps    │         └────────┬───────────┘
└────────────────────┘                  │
                                        │ 1:N tiene
                                        │
                              ┌─────────▼───────────┐
                              │    expedientes      │
                              ├─────────────────────┤
                              │ PK │ id             │
                              │ FK │ paciente_id    │
                              │ FK │ medico_id      │
                              │    │ fecha_consulta │
                              │    │ sintomas       │
                              │    │ diagnostico    │
                              │    │ tratamiento    │
                              │    │ observaciones  │
                              │    │ presion_art    │
                              │    │ peso           │
                              │    │ estatura       │
                              │    │ temperatura    │
                              │    │ timestamps     │
                              └─────────────────────┘


┌────────────────────────────┐
│      medicamentos          │
├────────────────────────────┤
│ PK │ id                    │
│    │ nombre                │
│    │ descripcion           │
│    │ lote                  │
│    │ fecha_caducidad       │
│    │ stock_actual          │
│    │ stock_minimo          │
│    │ precio_unitario       │
│    │ disponible            │
│    │ timestamps            │
└──────────┬─────────────────┘
           │
           │ 1:N registra
           │
┌──────────▼─────────────────┐
│  movimientos_inventario    │
├────────────────────────────┤
│ PK │ id                    │
│ FK │ medicamento_id        │
│ FK │ usuario_id            │
│    │ tipo (E/S/A)          │
│    │ cantidad              │
│    │ motivo                │
│    │ stock_anterior        │
│    │ stock_nuevo           │
│    │ timestamps            │
└────────────────────────────┘
```

### 4.1.2 Diccionario de Datos

**Tabla: users**
| Campo | Tipo | Restricciones | Descripción |
|-------|------|---------------|-------------|
| id | INT | PK, AUTO_INCREMENT | Identificador único |
| nombre | VARCHAR(100) | NOT NULL | Nombre completo del usuario |
| email | VARCHAR(100) | NOT NULL, UNIQUE | Email de acceso (login) |
| password | VARCHAR(255) | NOT NULL | Contraseña encriptada (bcrypt) |
| rol | ENUM('admin','medico','recepcion') | NOT NULL | Rol del usuario |
| activo | BOOLEAN | DEFAULT TRUE | Usuario activo/inactivo |
| created_at | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Fecha de creación |
| updated_at | TIMESTAMP | ON UPDATE CURRENT_TIMESTAMP | Última actualización |

**Tabla: pacientes**
| Campo | Tipo | Restricciones | Descripción |
|-------|------|---------------|-------------|
| id | INT | PK, AUTO_INCREMENT | Identificador único |
| nombre | VARCHAR(150) | NOT NULL | Nombre completo del paciente |
| fecha_nacimiento | DATE | NOT NULL | Fecha de nacimiento |
| sexo | CHAR(1) | NOT NULL | M=Masculino, F=Femenino |
| telefono | VARCHAR(15) | NULL | Teléfono de contacto |
| email | VARCHAR(100) | NULL | Email del paciente |
| tipo_sangre | VARCHAR(5) | NULL | Tipo de sangre (O+, A-, etc.) |
| alergias | TEXT | NULL | Alergias conocidas |
| direccion | VARCHAR(255) | NULL | Dirección completa |
| created_at | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Fecha de registro |
| updated_at | TIMESTAMP | ON UPDATE CURRENT_TIMESTAMP | Última actualización |

**Índices:**
- INDEX idx_nombre ON pacientes(nombre)
- INDEX idx_telefono ON pacientes(telefono)

**Tabla: citas**
| Campo | Tipo | Restricciones | Descripción |
|-------|------|---------------|-------------|
| id | INT | PK, AUTO_INCREMENT | Identificador único |
| paciente_id | INT | FK → pacientes(id), NOT NULL | Paciente de la cita |
| medico_id | INT | FK → users(id), NOT NULL | Médico asignado |
| fecha | DATE | NOT NULL | Fecha de la cita |
| hora | TIME | NOT NULL | Hora de la cita |
| tipo | VARCHAR(50) | NOT NULL | Tipo de consulta |
| estado | ENUM('agendada','atendida','cancelada','no_asistio') | DEFAULT 'agendada' | Estado actual |
| motivo | TEXT | NULL | Motivo de la consulta |
| recordatorio_enviado | BOOLEAN | DEFAULT FALSE | Si se envió recordatorio |
| folio | VARCHAR(30) | UNIQUE, NOT NULL | Folio único (CITA-YYYYMMDD-XXXX) |
| created_at | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Fecha de creación |
| updated_at | TIMESTAMP | ON UPDATE CURRENT_TIMESTAMP | Última actualización |

**Índices:**
- INDEX idx_fecha ON citas(fecha)
- INDEX idx_medico_fecha ON citas(medico_id, fecha)
- UNIQUE INDEX idx_folio ON citas(folio)

**Tabla: expedientes**
| Campo | Tipo | Restricciones | Descripción |
|-------|------|---------------|-------------|
| id | INT | PK, AUTO_INCREMENT | Identificador único |
| paciente_id | INT | FK → pacientes(id), NOT NULL | Paciente del expediente |
| medico_id | INT | FK → users(id), NOT NULL | Médico que atendió |
| fecha_consulta | DATETIME | NOT NULL | Fecha y hora de consulta |
| sintomas | TEXT | NULL | Síntomas reportados |
| diagnostico | TEXT | NOT NULL | Diagnóstico médico |
| tratamiento | TEXT | NOT NULL | Tratamiento prescrito |
| observaciones | TEXT | NULL | Observaciones adicionales |
| presion_arterial | VARCHAR(10) | NULL | Presión arterial (120/80) |
| peso | DECIMAL(5,2) | NULL | Peso en kg |
| estatura | DECIMAL(4,2) | NULL | Estatura en metros |
| temperatura | DECIMAL(4,2) | NULL | Temperatura en °C |
| created_at | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Fecha de registro |
| updated_at | TIMESTAMP | ON UPDATE CURRENT_TIMESTAMP | Última actualización |

**Índices:**
- INDEX idx_paciente ON expedientes(paciente_id)
- INDEX idx_fecha ON expedientes(fecha_consulta)

**Tabla: medicamentos**
| Campo | Tipo | Restricciones | Descripción |
|-------|------|---------------|-------------|
| id | INT | PK, AUTO_INCREMENT | Identificador único |
| nombre | VARCHAR(150) | NOT NULL | Nombre del medicamento |
| descripcion | TEXT | NULL | Descripción/Presentación |
| lote | VARCHAR(50) | NULL | Número de lote |
| fecha_caducidad | DATE | NOT NULL | Fecha de caducidad |
| stock_actual | INT | DEFAULT 0, CHECK >= 0 | Stock actual |
| stock_minimo | INT | DEFAULT 10 | Stock mínimo |
| precio_unitario | DECIMAL(8,2) | DEFAULT 0 | Precio por unidad |
| disponible | BOOLEAN | DEFAULT TRUE | Disponible para salida |
| created_at | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Fecha de registro |
| updated_at | TIMESTAMP | ON UPDATE CURRENT_TIMESTAMP | Última actualización |

**Índices:**
- INDEX idx_nombre ON medicamentos(nombre)
- INDEX idx_caducidad ON medicamentos(fecha_caducidad)
- INDEX idx_stock ON medicamentos(stock_actual)

**Tabla: movimientos_inventario**
| Campo | Tipo | Restricciones | Descripción |
|-------|------|---------------|-------------|
| id | INT | PK, AUTO_INCREMENT | Identificador único |
| medicamento_id | INT | FK → medicamentos(id), NOT NULL | Medicamento afectado |
| usuario_id | INT | FK → users(id), NOT NULL | Usuario que registra |
| tipo | ENUM('E','S','A') | NOT NULL | E=Entrada, S=Salida, A=Ajuste |
| cantidad | INT | NOT NULL | Cantidad del movimiento |
| motivo | VARCHAR(255) | NULL | Motivo del movimiento |
| stock_anterior | INT | NOT NULL | Stock antes del movimiento |
| stock_nuevo | INT | NOT NULL | Stock después del movimiento |
| created_at | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Fecha del movimiento |

**Índices:**
- INDEX idx_medicamento ON movimientos_inventario(medicamento_id)
- INDEX idx_fecha ON movimientos_inventario(created_at)

---

## 4.2 API REST - Documentación de Endpoints

### 4.2.1 Autenticación

**POST /api/auth/login**
```json
Request:
{
  "email": "medico@clinica.com",
  "password": "password123"
}

Response 200:
{
  "access_token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
  "token_type": "Bearer",
  "expires_in": 28800,
  "user": {
    "id": 1,
    "nombre": "Dr. Juan Pérez",
    "email": "medico@clinica.com",
    "rol": "medico"
  }
}

Response 401:
{
  "message": "Credenciales incorrectas"
}
```

**POST /api/auth/logout**
```
Headers: Authorization: Bearer {token}

Response 200:
{
  "message": "Sesión cerrada exitosamente"
}
```

---

### 4.2.2 Pacientes

**GET /api/pacientes**
```
Headers: Authorization: Bearer {token}
Query Params:
  - search (opcional): Buscar por nombre
  - page (opcional): Número de página
  - per_page (opcional): Registros por página (default: 20)

Response 200:
{
  "data": [
    {
      "id": 1,
      "nombre": "Juan Pérez García",
      "fecha_nacimiento": "1980-05-15",
      "edad": 45,
      "sexo": "M",
      "telefono": "442-123-4567",
      "email": "juan@email.com",
      "tipo_sangre": "O+",
      "ultima_visita": "2025-12-15"
    }
  ],
  "meta": {
    "current_page": 1,
    "total": 642,
    "per_page": 20
  }
}
```

**POST /api/pacientes**
```
Headers: Authorization: Bearer {token}
Request:
{
  "nombre": "María García López",
  "fecha_nacimiento": "1985-03-20",
  "sexo": "F",
  "telefono": "442-987-6543",
  "email": "maria@email.com",
  "tipo_sangre": "A+",
  "alergias": "Penicilina",
  "direccion": "Calle Principal #123, Col. Centro"
}

Response 201:
{
  "message": "Paciente registrado exitosamente",
  "paciente": {
    "id": 643,
    "nombre": "María García López",
    ...
  }
}

Response 422: // Validación fallida
{
  "message": "Error de validación",
  "errors": {
    "email": ["El email ya está registrado"],
    "telefono": ["El formato del teléfono es inválido"]
  }
}
```

---

### 4.2.3 Citas

**GET /api/citas**
```
Headers: Authorization: Bearer {token}
Query Params:
  - fecha (opcional): Filtrar por fecha específica
  - medico_id (opcional): Filtrar por médico
  - estado (opcional): Filtrar por estado

Response 200:
{
  "data": [
    {
      "id": 1,
      "folio": "CITA-20260121-0042",
      "paciente": {
        "id": 1,
        "nombre": "Juan Pérez García"
      },
      "medico": {
        "id": 2,
        "nombre": "Dra. Ana López"
      },
      "fecha": "2026-01-25",
      "hora": "10:00:00",
      "tipo": "Consulta General",
      "estado": "agendada",
      "motivo": "Revisión de presión arterial"
    }
  ]
}
```

**POST /api/citas**
```
Headers: Authorization: Bearer {token}
Request:
{
  "paciente_id": 1,
  "medico_id": 2,
  "fecha": "2026-01-25",
  "hora": "10:00",
  "tipo": "Consulta General",
  "motivo": "Revisión de presión arterial",
  "enviar_recordatorio_email": true,
  "enviar_recordatorio_sms": true
}

Response 201:
{
  "message": "Cita registrada exitosamente",
  "cita": {
    "id": 245,
    "folio": "CITA-20260125-0245",
    "paciente": {...},
    "medico": {...},
    "fecha": "2026-01-25",
    "hora": "10:00:00"
  }
}

Response 422: // Horario no disponible
{
  "message": "El horario seleccionado no está disponible",
  "sugerencias": [
    {"fecha": "2026-01-25", "hora": "10:30"},
    {"fecha": "2026-01-25", "hora": "11:00"},
    {"fecha": "2026-01-25", "hora": "14:00"}
  ]
}
```

**PUT /api/citas/{id}**
```
Headers: Authorization: Bearer {token}
Request:
{
  "fecha": "2026-01-26",
  "hora": "11:00",
  "estado": "agendada"
}

Response 200:
{
  "message": "Cita actualizada exitosamente",
  "cita": {...}
}
```

**DELETE /api/citas/{id}**
```
Headers: Authorization: Bearer {token}

Response 200:
{
  "message": "Cita cancelada exitosamente"
}
```

---

### 4.2.4 Expedientes

**GET /api/expedientes/{paciente_id}**
```
Headers: Authorization: Bearer {token}

Response 200:
{
  "paciente": {
    "id": 1,
    "nombre": "Juan Pérez García",
    "fecha_nacimiento": "1980-05-15",
    "tipo_sangre": "O+",
    "alergias": "Ninguna"
  },
  "expedientes": [
    {
      "id": 50,
      "fecha_consulta": "2025-12-15 09:30:00",
      "medico": {
        "id": 2,
        "nombre": "Dra. Ana López"
      },
      "sintomas": "Dolor de cabeza, fatiga",
      "diagnostico": "Hipertensión arterial controlada",
      "tratamiento": "Losartán 50mg cada 24 horas",
      "presion_arterial": "130/85",
      "peso": 75.5,
      "observaciones": "Control en 30 días"
    },
    ...
  ]
}
```

**POST /api/expedientes**
```
Headers: Authorization: Bearer {token}
Request:
{
  "paciente_id": 1,
  "sintomas": "Dolor de pecho, dificultad para respirar",
  "diagnostico": "Posible angina de pecho",
  "tratamiento": "Nitroglicerina sublingual PRN, referencia a cardiólogo",
  "observaciones": "Realizar electrocardiograma urgente",
  "presion_arterial": "140/95",
  "peso": 76.2,
  "estatura": 1.75,
  "temperatura": 36.8
}

Response 201:
{
  "message": "Consulta registrada exitosamente",
  "expediente": {
    "id": 51,
    ...
  },
  "pdf_url": "/api/expedientes/51/pdf"
}
```

**GET /api/expedientes/{id}/pdf**
```
Headers: Authorization: Bearer {token}

Response 200: 
[Archivo PDF descargable con receta médica]
```

---

### 4.2.5 Medicamentos e Inventario

**GET /api/medicamentos**
```
Headers: Authorization: Bearer {token}
Query Params:
  - search (opcional): Buscar por nombre
  - stock_bajo (opcional): true/false
  - proximos_caducar (opcional): true/false

Response 200:
{
  "data": [
    {
      "id": 1,
      "nombre": "Paracetamol 500mg",
      "descripcion": "Caja con 10 tabletas",
      "lote": "LOT-2025-001",
      "fecha_caducidad": "2026-12-31",
      "stock_actual": 50,
      "stock_minimo": 20,
      "precio_unitario": 15.50,
      "disponible": true,
      "dias_para_caducidad": 349,
      "alerta_stock": false,
      "alerta_caducidad": false
    },
    {
      "id": 2,
      "nombre": "Losartán 50mg",
      "stock_actual": 5,
      "stock_minimo": 10,
      "alerta_stock": true,  // Stock bajo!
      ...
    }
  ]
}
```

**POST /api/medicamentos**
```
Headers: Authorization: Bearer {token}
Request:
{
  "nombre": "Ibuprofeno 400mg",
  "descripcion": "Caja con 20 tabletas",
  "lote": "LOT-2026-015",
  "fecha_caducidad": "2027-06-30",
  "stock_actual": 100,
  "stock_minimo": 30,
  "precio_unitario": 25.00
}

Response 201:
{
  "message": "Medicamento registrado exitosamente",
  "medicamento": {...}
}
```

**POST /api/inventario/movimiento**
```
Headers: Authorization: Bearer {token}
Request:
{
  "medicamento_id": 1,
  "tipo": "S",  // E=Entrada, S=Salida, A=Ajuste
  "cantidad": 10,
  "motivo": "Venta mostrador"
}

Response 201:
{
  "message": "Movimiento registrado exitosamente",
  "movimiento": {
    "id": 150,
    "medicamento": "Paracetamol 500mg",
    "tipo": "Salida",
    "cantidad": 10,
    "stock_anterior": 50,
    "stock_nuevo": 40
  },
  "alertas": []  // Si se generaron alertas
}

Response 422: // Stock insuficiente
{
  "message": "Stock insuficiente",
  "stock_actual": 5,
  "cantidad_solicitada": 10
}
```

---

### 4.2.6 Reportes y Estadísticas

**GET /api/dashboard**
```
Headers: Authorization: Bearer {token}

Response 200:
{
  "estadisticas": {
    "citas_hoy": 18,
    "citas_pendientes": 5,
    "pacientes_activos": 642,
    "medicamentos_stock_bajo": 3
  },
  "citas_hoy": [
    {
      "hora": "09:00",
      "paciente": "Juan Pérez",
      "medico": "Dr. Martínez",
      "estado": "atendida"
    },
    ...
  ],
  "alertas": [
    {
      "tipo": "STOCK_BAJO",
      "mensaje": "Losartán 50mg: solo 5 unidades disponibles",
      "urgencia": "ALTA"
    },
    {
      "tipo": "CITA_SIN_CONFIRMAR",
      "mensaje": "5 pacientes no han confirmado cita de hoy",
      "urgencia": "MEDIA"
    }
  ]
}
```

**GET /api/reportes/consultas**
```
Headers: Authorization: Bearer {token}
Query Params:
  - fecha_inicio: Fecha inicial del reporte
  - fecha_fin: Fecha final del reporte
  - medico_id (opcional): Filtrar por médico
  - formato (opcional): json|pdf|excel

Response 200:
{
  "periodo": {
    "inicio": "2026-01-01",
    "fin": "2026-01-31"
  },
  "total_consultas": 450,
  "por_medico": [
    {
      "medico": "Dra. Ana López",
      "total_consultas": 180,
      "promedio_diario": 8.2
    },
    ...
  ],
  "por_tipo": [
    {
      "tipo": "Consulta General",
      "total": 280
    },
    {
      "tipo": "Seguimiento",
      "total": 120
    },
    {
      "tipo": "Primera Vez",
      "total": 50
    }
  ]
}
```

---

## 4.3 Seguridad

### 4.3.1 Medidas de Seguridad Implementadas

**Autenticación y Autorización:**
- JWT (JSON Web Tokens) con Laravel Sanctum
- Tokens con expiración de 8 horas
- Refresh tokens no implementados en v1.0 (sesión única)
- Middleware de autenticación en todas las rutas protegidas
- Control de acceso basado en roles (RBAC)

**Encriptación:**
- Contraseñas hasheadas con bcrypt (factor 12)
- Comunicación HTTPS obligatoria en producción
- Certificado SSL/TLS con Let's Encrypt
- Variables sensibles en archivo .env (excluido de Git)

**Validación de Datos:**
- Validación en frontend (Angular Reactive Forms)
- Validación en backend (Laravel Form Requests)
- Protección contra SQL Injection (ORM Eloquent con prepared statements)
- Protección contra XSS (sanitización automática de Laravel)
- Protección contra CSRF (tokens en formularios)

**Rate Limiting:**
- 60 peticiones por minuto por IP
- 5 intentos de login antes de bloqueo temporal (15 min)
- Throttling en endpoints de búsqueda intensiva

**Auditoría:**
- Log de acciones críticas (modificación de expedientes, cancelación de citas)
- Registro de intentos de login fallidos
- Laravel Log con niveles: ERROR, WARNING, INFO, DEBUG
- Logs rotan automáticamente (7 días de retención)

**Respaldos:**
- Respaldo automático de BD diario (2:00 AM)
- Retención de 30 días
- Almacenamiento encriptado
- Pruebas de restauración mensuales

---

# 5. MANUAL DEL USUARIO

## 5.1 Introducción

Bienvenido a **MediControl**, el sistema diseñado para facilitar la gestión de su consultorio médico. Este manual le guiará paso a paso en el uso de las funcionalidades principales del sistema.

### 5.1.1 Requisitos Mínimos

- Navegador web actualizado (Chrome 90+, Firefox 88+, Edge 90+)
- Conexión a Internet estable
- Resolución de pantalla mínima: 1024x768
- Credenciales de acceso proporcionadas por el administrador

---

## 5.2 Acceso al Sistema

1. Abra su navegador web
2. Ingrese a la dirección: https://medicontrol.clinica.com
3. Ingrese su email y contraseña
4. Haga clic en "Iniciar Sesión"

![Pantalla de Login - Simulación]

**Nota:** Si olvida su contraseña, contacte al administrador del sistema.

---

## 5.3 Guía para Recepcionistas

### 5.3.1 Agendar una Cita

1. En el menú lateral, haga clic en "📅 Citas"
2. Presione el botón verde "+ Nueva Cita"
3. En el campo de búsqueda, ingrese el nombre del paciente
4. Seleccione al paciente de los resultados
   - Si el paciente no existe, presione "Registrar Nuevo Paciente" (ver sección 5.3.2)
5. Seleccione el médico del menú desplegable
6. Seleccione la fecha haciendo clic en el calendario
7. Elija la hora disponible (solo se mostrarán horarios libres)
8. Seleccione el tipo de consulta
9. Opcionalmente, ingrese el motivo de la consulta
10. Marque las opciones de recordatorio (Email y/o SMS)
11. Presione "Guardar Cita"
12. El sistema mostrará una confirmación con el número de folio
13. Opcionalmente, puede imprimir el comprobante

**Consejos:**
- El sistema no permite agendar en horarios ocupados
- Si el horario deseado no está disponible, el sistema sugerirá alternativas
- Los recordatorios se envían automáticamente 24 horas antes

---

### 5.3.2 Registrar un Nuevo Paciente

1. En el menú lateral, haga clic en "👥 Pacientes"
2. Presione "+ Nuevo Paciente"
3. Complete los campos obligatorios (marcados con *):
   - Nombre completo
   - Fecha de nacimiento
   - Sexo
   - Teléfono
4. Complete campos opcionales según corresponda:
   - Email
   - Tipo de sangre
   - Alergias conocidas
   - Dirección
5. Presione "Guardar Paciente"
6. El sistema asignará automáticamente un ID al paciente

**Importante:** Verifique que el paciente no esté ya registrado antes de crear uno nuevo.

---

### 5.3.3 Buscar un Paciente

1. En el menú lateral, haga clic en "👥 Pacientes"
2. En el campo de búsqueda, ingrese:
   - Nombre completo o parcial
   - Teléfono
   - ID del paciente
3. El sistema mostrará resultados mientras escribe
4. Haga clic en el paciente deseado para ver su información completa

---

### 5.3.4 Modificar o Cancelar una Cita

**Modificar:**
1. Busque la cita en el calendario o lista de citas
2. Haga clic en el ícono de editar (lápiz)
3. Modifique los datos necesarios
4. Presione "Guardar Cambios"

**Cancelar:**
1. Busque la cita en el calendario o lista de citas
2. Haga clic en el ícono de cancelar (X roja)
3. Confirme la cancelación
4. El sistema enviará notificación al paciente automáticamente

**Nota:** Las citas canceladas no se eliminan, quedan marcadas como "canceladas" en el historial.

---

## 5.4 Guía para Médicos

### 5.4.1 Ver Agenda del Día

1. Al iniciar sesión, verá automáticamente su agenda del día
2. Las citas aparecen ordenadas por hora
3. Los colores indican el estado:
   - 🟢 Verde: Cita atendida
   - 🟡 Amarillo: En espera
   - 🔵 Azul: Próxima cita
   - ⚪ Gris: Disponible

---

### 5.4.2 Consultar Expediente de un Paciente

1. Haga clic en la cita del paciente que va a atender
2. O busque al paciente en "👥 Pacientes"
3. Haga clic en "Ver Expediente"
4. El sistema mostrará:
   - Datos personales del paciente
   - Historial completo de consultas (más reciente primero)
   - Alergias y tipo de sangre destacados
5. Puede hacer clic en cualquier consulta anterior para ver detalles

---

### 5.4.3 Registrar una Consulta

1. Desde el expediente del paciente, haga clic en "+ Nueva Consulta"
2. Complete los campos:
   - **Síntomas:** Describa los síntomas que refiere el paciente
   - **Diagnóstico:** * Obligatorio - Diagnóstico médico
   - **Tratamiento:** * Obligatorio - Tratamiento prescrito
   - **Observaciones:** Notas adicionales
3. Registre signos vitales:
   - Presión arterial (formato 120/80)
   - Peso en kg
   - Estatura en metros
   - Temperatura en °C
4. Presione "Guardar Consulta"
5. El sistema le preguntará si desea generar la receta en PDF
6. Si selecciona "Sí", se descargará automáticamente el PDF con:
   - Logo del consultorio
   - Datos del paciente
   - Tratamiento prescrito
   - Su cédula profesional
   - Fecha y firma digital

**Importante:** Una vez guardada, la consulta no puede eliminarse, solo puede anotar correcciones en observaciones de una nueva entrada.

---

### 5.4.4 Buscar en el Historial

1. Desde el expediente del paciente
2. Puede filtrar por:
   - Rango de fechas
   - Tipo de consulta
   - Diagnóstico (búsqueda de texto)
3. Use el campo de búsqueda rápida para encontrar palabras clave

---

## 5.5 Guía para Administradores

### 5.5.1 Dashboard de Reportes

1. En el menú lateral, haga clic en "📊 Reportes"
2. Verá el dashboard con:
   - Gráficas de consultas por médico
   - Pacientes frecuentes
   - Medicamentos más recetados
   - Análisis de ingresos

3. Puede filtrar por:
   - Rango de fechas
   - Médico específico
   - Tipo de reporte

---

### 5.5.2 Control de Inventario

**Ver Stock Actual:**
1. Haga clic en "💊 Inventario"
2. Verá lista de todos los medicamentos con:
   - Stock actual
   - Stock mínimo
   - Fecha de caducidad
   - Alertas (si aplican)

**Registrar Entrada de Medicamentos:**
1. Haga clic en "+ Entrada"
2. Seleccione el medicamento (o agregue uno nuevo)
3. Ingrese:
   - Cantidad
   - Lote
   - Fecha de caducidad
   - Precio unitario
4. Presione "Guardar"

**Registrar Salida:**
1. Haga clic en "Salida" en el medicamento correspondiente
2. Ingrese cantidad
3. Ingrese motivo (venta, uso interno, etc.)
4. Presione "Guardar"

**Nota:** El sistema no permitirá salidas si resulta en stock negativo.

---

### 5.5.3 Gestión de Usuarios

1. Haga clic en "⚙ Configuración" → "Usuarios"
2. Verá lista de usuarios del sistema

**Crear Nuevo Usuario:**
1. Presione "+ Nuevo Usuario"
2. Complete:
   - Nombre completo
   - Email (será su usuario de login)
   - Contraseña inicial
   - Rol (Administrador, Médico, Recepcionista)
3. Si es médico, complete además:
   - Cédula profesional
   - Especialidad
4. Presione "Guardar"
5. El usuario recibirá un email con sus credenciales

**Desactivar Usuario:**
1. Haga clic en el usuario
2. Cambie el estado a "Inactivo"
3. Presione "Guardar"

**Nota:** Nunca elimine usuarios, solo desactívelos para mantener la integridad de los registros históricos.

---

### 5.5.4 Configuración de Días No Laborales

1. Vaya a "⚙ Configuración" → "Calendario"
2. Marque en el calendario los días festivos
3. El sistema no permitirá agendar citas en esos días

---

### 5.5.5 Exportar Reportes

1. Desde cualquier reporte, haga clic en "Exportar"
2. Seleccione el formato:
   - PDF: Para impresión
   - Excel: Para análisis adicional
3. El archivo se descargará automáticamente

---

## 5.6 Preguntas Frecuentes (FAQ)

**P: ¿Qué hago si olvido mi contraseña?**  
R: Contacte al administrador del sistema para que le asigne una nueva contraseña temporal.

**P: ¿Puedo eliminar una consulta registrada por error?**  
R: No, las consultas no se pueden eliminar por seguridad médica y legal. Puede agregar una nota en observaciones aclarando el error.

**P: ¿Los recordatorios se envían automáticamente?**  
R: Sí, el sistema envía recordatorios automáticamente 24 horas antes de la cita a las 10:00 AM.

**P: ¿Qué hago si un paciente llega sin cita?**  
R: Puede agendar una cita con fecha/hora actual si el médico tiene espacio disponible, o agendarlo para otro día.

**P: ¿Puedo acceder al sistema desde mi celular?**  
R: Sí, el sistema es responsive y se adapta a pantallas móviles, aunque recomendamos usar computadora para mayor comodidad.

**P: ¿Cuánto tiempo se guardan los expedientes?**  
R: Los expedientes se conservan por 5 años mínimo, de acuerdo a normatividad.

**P: ¿Qué pasa si se va la luz o Internet?**  
R: Si pierde la conexión, al recuperarla el sistema sincronizará automáticamente. Los datos ya guardados no se pierden.

---

## 5.7 Soporte Técnico

Si experimenta problemas técnicos o tiene dudas:

📧 **Email:** soporte@medicontrol.com  
📞 **Teléfono:** 442-123-4567 ext. 100  
🕐 **Horario:** Lunes a Viernes, 9:00 AM - 6:00 PM

---

# 6. MANUAL TÉCNICO

## 6.1 Requisitos del Sistema

### 6.1.1 Servidor de Producción

**Sistema Operativo:**
- Ubuntu Server 22.04 LTS o superior
- CentOS 8+ (alternativa)

**Software Base:**
- Nginx 1.24+
- PHP 8.2+
- MySQL 8.0+
- Node.js 18+ (para compilación de Angular)

**Extensiones PHP Requeridas:**
```
- php8.2-fpm
- php8.2-mysql
- php8.2-mbstring
- php8.2-xml
- php8.2-curl
- php8.2-zip
- php8.2-gd
- php8.2-bcmath
```

**Recursos Mínimos:**
- 2 CPU cores
- 4 GB RAM
- 50 GB almacenamiento SSD
- 100 Mbps ancho de banda

---

## 6.2 Instalación

### 6.2.1 Instalación del Backend (Laravel)

1. **Clonar repositorio:**
```bash
cd /var/www
git clone https://github.com/tu-usuario/medicontrol-backend.git
cd medicontrol-backend
```

2. **Instalar dependencias:**
```bash
composer install --optimize-autoloader --no-dev
```

3. **Configurar variables de entorno:**
```bash
cp .env.example .env
nano .env
```

Configurar:
```
APP_NAME=MediControl
APP_ENV=production
APP_DEBUG=false
APP_URL=https://medicontrol.clinica.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=medicontrol_db
DB_USERNAME=medicontrol_user
DB_PASSWORD=[CONTRASEÑA_SEGURA]

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=tu-email@gmail.com
MAIL_PASSWORD=[APP_PASSWORD]

TWILIO_SID=[TU_TWILIO_SID]
TWILIO_TOKEN=[TU_TWILIO_TOKEN]
TWILIO_FROM=[TU_NUMERO_TWILIO]
```

4. **Generar clave de aplicación:**
```bash
php artisan key:generate
```

5. **Ejecutar migraciones:**
```bash
php artisan migrate --force
```

6. **Ejecutar seeders (datos iniciales):**
```bash
php artisan db:seed
```

7. **Optimizar aplicación:**
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

8. **Configurar permisos:**
```bash
chown -R www-data:www-data /var/www/medicontrol-backend
chmod -R 755 /var/www/medicontrol-backend/storage
chmod -R 755 /var/www/medicontrol-backend/bootstrap/cache
```

---

### 6.2.2 Instalación del Frontend (Angular)

1. **Clonar repositorio:**
```bash
cd /var/www
git clone https://github.com/tu-usuario/medicontrol-frontend.git
cd medicontrol-frontend
```

2. **Instalar dependencias:**
```bash
npm install
```

3. **Configurar entorno de producción:**
```bash
nano src/environments/environment.prod.ts
```

```typescript
export const environment = {
  production: true,
  apiUrl: 'https://medicontrol.clinica.com/api'
};
```

4. **Compilar para producción:**
```bash
npm run build --prod
```

5. **Mover archivos compilados:**
```bash
cp -r dist/medicontrol-frontend/* /var/www/html/
```

---

### 6.2.3 Configuración de Nginx

Crear archivo de configuración:
```bash
nano /etc/nginx/sites-available/medicontrol
```

Contenido:
```nginx
server {
    listen 80;
    listen [::]:80;
    server_name medicontrol.clinica.com;
    return 301 https://$host$request_uri;
}

server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name medicontrol.clinica.com;

    # SSL Configuration
    ssl_certificate /etc/letsencrypt/live/medicontrol.clinica.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/medicontrol.clinica.com/privkey.pem;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5;

    # Frontend (Angular)
    root /var/www/html;
    index index.html;

    location / {
        try_files $uri $uri/ /index.html;
    }

    # Backend (Laravel API)
    location /api {
        alias /var/www/medicontrol-backend/public;
        try_files $uri $uri/ @api;

        location ~ \.php$ {
            fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
            fastcgi_index index.php;
            include fastcgi_params;
            fastcgi_param SCRIPT_FILENAME $request_filename;
        }
    }

    location @api {
        rewrite /api/(.*)$ /api/index.php?/$1 last;
    }

    # Security headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header Referrer-Policy "no-referrer-when-downgrade" always;

    # Logs
    access_log /var/log/nginx/medicontrol_access.log;
    error_log /var/log/nginx/medicontrol_error.log;
}
```

Activar sitio:
```bash
ln -s /etc/nginx/sites-available/medicontrol /etc/nginx/sites-enabled/
nginx -t
systemctl restart nginx
```

---

### 6.2.4 Configuración de SSL (Let's Encrypt)

```bash
apt install certbot python3-certbot-nginx
certbot --nginx -d medicontrol.clinica.com
```

Configurar renovación automática:
```bash
crontab -e
```

Agregar:
```
0 3 * * * certbot renew --quiet
```

---

## 6.3 Respaldos Automáticos

### 6.3.1 Script de Respaldo

Crear script:
```bash
nano /usr/local/bin/backup-medicontrol.sh
```

Contenido:
```bash
#!/bin/bash

# Configuración
DB_NAME="medicontrol_db"
DB_USER="medicontrol_user"
DB_PASS="[CONTRASEÑA]"
BACKUP_DIR="/backups/medicontrol"
DATE=$(date +"%Y%m%d_%H%M%S")
RETENTION_DAYS=30

# Crear directorio si no existe
mkdir -p $BACKUP_DIR

# Respaldar base de datos
mysqldump -u $DB_USER -p$DB_PASS $DB_NAME | gzip > $BACKUP_DIR/db_$DATE.sql.gz

# Respaldar archivos subidos (si existen)
tar -czf $BACKUP_DIR/storage_$DATE.tar.gz /var/www/medicontrol-backend/storage/app

# Eliminar respaldos antiguos
find $BACKUP_DIR -name "db_*.sql.gz" -mtime +$RETENTION_DAYS -delete
find $BACKUP_DIR -name "storage_*.tar.gz" -mtime +$RETENTION_DAYS -delete

# Log
echo "$(date) - Respaldo completado: db_$DATE.sql.gz" >> /var/log/medicontrol-backup.log
```

Dar permisos de ejecución:
```bash
chmod +x /usr/local/bin/backup-medicontrol.sh
```

Programar ejecución automática:
```bash
crontab -e
```

Agregar:
```
0 2 * * * /usr/local/bin/backup-medicontrol.sh
```

---

## 6.4 Monitoreo y Mantenimiento

### 6.4.1 Logs del Sistema

**Laravel logs:**
```bash
tail -f /var/www/medicontrol-backend/storage/logs/laravel.log
```

**Nginx access log:**
```bash
tail -f /var/log/nginx/medicontrol_access.log
```

**Nginx error log:**
```bash
tail -f /var/log/nginx/medicontrol_error.log
```

### 6.4.2 Limpieza de Logs

Configurar rotación automática:
```bash
nano /etc/logrotate.d/medicontrol
```

Contenido:
```
/var/www/medicontrol-backend/storage/logs/*.log {
    daily
    missingok
    rotate 7
    compress
    delaycompress
    notifempty
    create 0640 www-data www-data
    sharedscripts
}
```

---

## 6.5 Troubleshooting

### 6.5.1 Problemas Comunes

**Error: "500 Internal Server Error"**
- Verificar permisos de storage y bootstrap/cache
- Revisar logs de Laravel
- Verificar configuración de .env

**Error: "CORS Policy Error"**
- Verificar configuración de CORS en Laravel
- Asegurar que apiUrl en Angular coincide con dominio del backend

**Error: "Database connection failed"**
- Verificar credenciales en .env
- Verificar que MySQL está ejecutándose: `systemctl status mysql`
- Verificar que el usuario tiene permisos: `GRANT ALL ON medicontrol_db.* TO 'medicontrol_user'@'localhost';`

**Error: "Token expired"**
- El token JWT expira cada 8 horas
- Usuario debe volver a iniciar sesión
- Verificar que la fecha/hora del servidor es correcta

---

## 6.6 Actualización del Sistema

### 6.6.1 Actualizar Backend

```bash
cd /var/www/medicontrol-backend
git pull origin main
composer install --optimize-autoloader --no-dev
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
systemctl restart php8.2-fpm
```

### 6.6.2 Actualizar Frontend

```bash
cd /var/www/medicontrol-frontend
git pull origin main
npm install
npm run build --prod
cp -r dist/medicontrol-frontend/* /var/www/html/
```

---

# 7. CONCLUSIONES

## 7.1 Resultados Esperados

La implementación del sistema MediControl transformará la operación del consultorio médico "Salud Integral", brindando los siguientes beneficios cuantificables:

**Eficiencia Operativa:**
- Reducción del 70% en tiempo administrativo (de 25 a 7.5 horas semanales)
- Eliminación del 95% de errores en agendamiento de citas
- Acceso instantáneo a expedientes (< 2 segundos vs 5-10 minutos en papel)
- Aumento de 20% en capacidad de atención de pacientes

**Beneficios Económicos:**
- Recuperación de $35,000 MXN mensuales en pérdidas operativas
- ROI proyectado en 5 meses
- Ahorro del 60% vs soluciones comerciales existentes
- Reducción de costos de papelería y archivo físico

**Mejora en Calidad de Servicio:**
- Incremento de confirmación de citas de 80% a 95%
- Reducción de 90% en desabasto de medicamentos
- Expedientes completos y legibles disponibles inmediatamente
- Métricas en tiempo real para toma de decisiones estratégicas

## 7.2 Cumplimiento de Objetivos

El proyecto cumplió satisfactoriamente con todos los objetivos planteados:

✅ **Objetivo General:** Sistema web funcional que optimiza gestión administrativa y clínica  
✅ **Análisis completo:** Requisitos funcionales y no funcionales documentados  
✅ **Diseño detallado:** Arquitectura, diagramas de clases y casos de uso  
✅ **Modelado de datos:** Base de datos normalizada con 9 tablas principales  
✅ **Documentación completa:** Manuales de usuario y técnico  

## 7.3 Lecciones Aprendidas

**Técnicas:**
- La arquitectura SPA (Angular + Laravel API) facilita el mantenimiento y escalabilidad
- El uso de ORM (Eloquent) reduce significativamente vulnerabilidades de seguridad
- Los seeders son fundamentales para pruebas rápidas y demostración del sistema
- La documentación de API con Swagger agiliza la integración frontend-backend

**Metodológicas:**
- El modelo en cascada con retroalimentación fue adecuado para requisitos bien definidos
- Las revisiones con el cliente al final de cada fase evitaron malentendidos
- La capacitación temprana del personal facilitó la adopción del sistema

**De Negocio:**
- La resistencia al cambio se superó demostrando beneficios tangibles inmediatos
- El período de acompañamiento post-entrega es crítico para el éxito
- La migración de datos desde Excel requiere validación manual exhaustiva

## 7.4 Trabajo Futuro

**Fase 2 - Mejoras Planeadas:**
- Integración con sistema de facturación electrónica (CFDI)
- Aplicación móvil nativa para iOS y Android
- Portal para pacientes (consulta de citas, historial propio)
- Telemedicina con videoconsultas
- Integración con laboratorios externos
- Dashboard de BI avanzado con Power BI

**Escalabilidad:**
- Soporte multi-tenant para múltiples consultorios
- Sistema de referencia entre médicos
- Integración con farmacias para recetas electrónicas
- Conexión con Expediente Clínico Nacional (cuando esté disponible)

## 7.5 Reflexión Final

El desarrollo de MediControl demuestra que la digitalización de consultorios médicos pequeños y medianos es no solo viable, sino necesaria en el contexto actual. La inversión en un sistema propio, adaptado a necesidades específicas, resulta más rentable y flexible que las soluciones comerciales genéricas.

La metodología aplicada, las tecnologías seleccionadas (Angular 17 + Laravel 11) y el enfoque en usabilidad resultaron en un sistema robusto que cumple con estándares profesionales de la industria del software.

Este proyecto sienta las bases para la transformación digital del sector salud a pequeña escala, democratizando el acceso a tecnología que tradicionalmente solo estaba al alcance de grandes instituciones.

---

**Elaboró:**  
Sunel Adalid López Dámaso

**Revisó:**  
Luis Alberto Castañeda Benítez

**Fecha de entrega:**  
16 de mayo 2026

---

**FIN DEL DOCUMENTO**