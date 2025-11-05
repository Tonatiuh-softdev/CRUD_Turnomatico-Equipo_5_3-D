#  NEXORA - Sistema de Gestión de Turnos

> **Un sistema moderno y amigable para tomar turnos online con panel de administración integrado**

![Estado](https://img.shields.io/badge/Estado-En%20Desarrollo-blue?style=flat-square)
![PHP](https://img.shields.io/badge/PHP-7.4+-purple?style=flat-square)
![MySQL](https://img.shields.io/badge/MySQL-5.7+-blue?style=flat-square)
![Licencia](https://img.shields.io/badge/Licencia-MIT-green?style=flat-square)

---

##  Tabla de Contenidos

- [ Características](#-características)
- [ Tecnologías Usadas](#-tecnologías-usadas)
- [ Instalación](#-instalación)
- [ Uso](#-uso)
- [ Estructura del Proyecto](#-estructura-del-proyecto)
- [ Configuración](#-configuración)
- [ Documentación de Módulos](#-documentación-de-módulos)
- [ Contribuidores](#-contribuidores)

---

##  Características

###  Para Clientes
-  Interfaz intuitiva para tomar turnos
-  Login y registro de clientes
-  Control de sesiones seguro
-  Vista de estado del turno en tiempo real

###  Para Empleados & Administración
-  Panel de administración completo
-  Gestión de empleados
-  Administración de servicios
-  Gestión de cajas de atención
-  Gestión de clientes
-  Estadísticas y reportes
-  Búsqueda en tiempo real con barra de búsqueda mejorada

---

##  Tecnologías Usadas

| Tecnología | Descripción |
|------------|-------------|
| **PHP 7.4+** | Backend del servidor |
| **MySQL/MariaDB** | Base de datos relacional |
| **HTML5** | Estructura semántica |
| **CSS3** | Estilos responsive |
| **JavaScript (Vanilla)** | Interactividad del cliente |
| **Font Awesome 6.5** | Iconografía |
| **Git** | Control de versiones |

---

##  Instalación

### Requisitos Previos
-  PHP 7.4 o superior
-  MySQL/MariaDB 5.7+
-  Servidor web (Apache/Nginx)
-  Git

### Pasos de Instalación

\\\ash
# 1. Clonar el repositorio
git clone https://github.com/Tonatiuh-softdev/CRUD_Turnomatico-Equipo_5_3-D.git

# 2. Configurar la base de datos
mysql -u root -p nexora < database.sql

# 3. Configurar conexión a BD
# Editar: Recursos/PHP/conexion.php

# 4. Iniciar servidor
php -S localhost:8000
\\\

---

##  Documentación de Módulos

###  Barra de Búsqueda (Nuevo )

Componente reutilizable para búsqueda en tiempo real en todas las tablas.

**Archivos:**
- BarraBusqueda.html - Estructura
- BarraBusqueda.css - Estilos dropdown
- BarraBusqueda.js - Lógica cliente
- BarraBusqueda.php - Endpoint servidor

**Características:**
-  Debounce 300ms
-  Búsqueda en múltiples tablas
-  Filtro en tiempo real
-  Prepared statements

**Páginas integradas:**
- clientes.html 
- empleados.html 
- cajas.html 
- servicios.html 

---

##  Contribuidores

|  Nombre |  Rol |  Email |  Contribución |
|-----------|--------|---------|-----------------|
| **Alcala Juan 
(AlkaDev1)**  |  Dev Full Stack | jalcala5@ucol.mx  |  Barra búsqueda, Filtros, Iconos  |
| **Preciado Roberto
(Tonatiuh-Softdev)**  |  Arquitecto | rpreciado9@ucol.mx  |  Backend, Estructura, Organización |
| **Equipo 5-3D**  |  Equipo Dev | equipo5@colima.edu.mx |  Desarrollo, Testing, Integración |
| **TBD**  |  UI/UX Designer | diseño@project.com |  Interfaz, Estilos, Layout |

###  Contribuciones Destacadas

 **Pablo Alcalá** - Implementación completa de:
  - Componente de búsqueda reutilizable
  - Filtro en tiempo real en 4 páginas
  - Iconos SVG en botones
  - Optimizaciones de UX

 **Tonatiuh Softdev** - Arquitectura y base:
  - Estructura modular del proyecto
  - Configuración de BD
  - Sistema de autenticación
  - Control de sesiones

 **Equipo 5-3D** - Desarrollo colaborativo:
  - Testing integrado
  - Bugfixes y mejoras
  - Documentación
  - Integración continua

---

##  Contacto & Soporte

 Email: soporte@nexora.com
 Issues: GitHub Issues
 Preguntas: GitHub Discussions

---

##  Licencia

Licencia MIT © 2025

---

**Hecho con y mucho amor por el Equipo NEXORA**

* ¡Porque cada turno cuenta! *

*Última actualización: Octubre 2025*