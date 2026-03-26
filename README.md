# 🩺 Medicontrol

Aplicación web para la gestión de información médica, diseñada para optimizar el control de pacientes, historiales clínicos y procesos administrativos mediante una interfaz moderna, clara y eficiente.

---

## ✨ Características

* 🧑‍⚕️ Gestión de pacientes
* 📋 Historiales clínicos
* 🔐 Autenticación segura
* 📊 Panel administrativo
* 🌙 Soporte para modo oscuro
* 🎨 UI moderna basada en *Liquid Glass*
* ⚡ Alto rendimiento y arquitectura escalable

---

## 🧱 Estructura del proyecto

```
medicontrol/
├── frontend/   # Angular + Tailwind CSS + PrimeNG
├── backend/    # Laravel API
├── docs/       # Documentación técnica
```

---

## 🚀 Tecnologías utilizadas

### Frontend

* Angular
* Tailwind CSS
* PrimeNG
* TypeScript

### Backend

* Laravel
* PHP
* MySQL

---

## ⚙️ Instalación

### 1. Clonar repositorio

```bash
git clone https://github.com/sunellopez/medi-control.git
cd medicontrol
```

---

### 2. Configurar Frontend

```bash
cd frontend
npm install
npm start
```

La aplicación estará disponible en:

```
http://localhost:4200
```

---

### 3. Configurar Backend

```bash
cd backend
composer install
cp .env.example .env
php artisan key:generate
php artisan serve
```

El backend correrá en:

```
http://localhost:8000
```

---

## 🔐 Variables de entorno

Configura el archivo `.env` en el backend:

```env
DB_DATABASE=medicontrol
DB_USERNAME=root
DB_PASSWORD=
```

---

## 📡 API

La API está construida en Laravel y expone endpoints para:

* Autenticación
* Gestión de usuarios
* Pacientes
* Historial clínico

---

## 🎨 Diseño UI

El sistema implementa un diseño moderno basado en:

* ✨ *Liquid Glass UI*
* 🌙 Dark Mode
* 🎯 Enfoque en accesibilidad y legibilidad

---

## 🛠️ Scripts útiles

### Frontend

```bash
npm start
npm run build
```

### Backend

```bash
php artisan serve
php artisan migrate
```

---

## 🤝 Contribución

1. Haz un fork del proyecto
2. Crea una rama (`feature/nueva-funcionalidad`)
3. Haz commit de tus cambios
4. Abre un Pull Request

---

## 📄 Licencia

Este proyecto está bajo la licencia MIT.

---

## 👨‍💻 Autor

Desarrollado por Sunel Lopez
