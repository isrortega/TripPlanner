
# TripPlanner
Un sistema para gestionar reservas de viajes, asignando conductores y vehículos disponibles de acuerdo con la fecha seleccionada y el tipo de licencia.

**Nota:** Hay una versión en inglés de este archivo disponible como `README.md` si prefiere leerlo en ese idioma.
**Note:** There is an English version of this file available as `README.md` if you prefer to read it in that language.

## Tabla de Contenidos
- [Descripción general](#descripción-general)
- [Requisitos](#requisitos)
- [Decisiones Técnicas](#decisiones-técnicas)
- [Estructura del Proyecto](#estructura-del-proyecto)
- [Instalación](#instalación)
- [Uso](#uso)
- [Pruebas](#pruebas)
- [Mejoras Futuras](#mejoras-futuras)

## Descripción general
Este proyecto es un sistema de reservas de viajes donde los usuarios pueden seleccionar una fecha, y el sistema mostrará los vehículos y conductores disponibles. Fue desarrollado utilizando PHP 8.2 y el framework Symfony, aplicando principios SOLID y un enfoque orientado a objetos.

## Requisitos
Lista de tecnologías y versiones necesarias para ejecutar el proyecto:
- Docker y Docker Compose

## Decisiones Técnicas
- **Framework Symfony:** Se eligió Symfony por su robustez y amplia documentación.
- **Entorno Docker:** Se utilizó Docker para simplificar la configuración del entorno y garantizar un comportamiento consistente en diferentes máquinas.
- **Bootstrap para el Frontend:** Se seleccionó Bootstrap como framework frontend debido a su simplicidad y capacidad de respuesta, permitiendo una implementación rápida y consistente del diseño en dispositivos.

## Estructura del Proyecto
TripPlanner/
├── docker-compose.yml (Archivo de configuración de Docker Compose para configurar los contenedores)
├── docker-entrypoint.sh (Script de entrada personalizado para la instalación inicial de composer)
├── Dockerfile (Archivo Dockerfile que define la configuración de la imagen para la aplicación)
├── nginx/
│   └── nginx.conf (Archivo principal de configuración de Nginx)
│   └── conf.d/
│       └── default.conf (Configuración de Nginx para hosts virtuales)
├── public/
│   └── (Archivos públicos de Symfony como index.php, etc.)
├── src/
│   └── (Código fuente de la aplicación Symfony)
├── var/
│   └── (Archivos temporales y de caché de Symfony)
├── vendor/
│   └── (Dependencias de Composer)
├── .env (Archivo de configuración del entorno para el proyecto Symfony)
└── .gitignore (Archivo Git ignore que especifica los archivos y directorios a excluir del control de versiones)

## Instalación
### Docker
1. Clona el repositorio:
   ```bash
   git clone https://github.com/your_username/TripPlanner.git
   ```
2. Navega a la carpeta del proyecto:
   ```bash
   cd TripPlanner
   ```
3. Inicia los contenedores Docker:
   ```bash
   docker-compose up -d
   ```
   No es necesario ejecutar las migraciones porque el archivo docker-entrypoint se encarga de esto.

## Uso
- Accede a la lista de viajes: `http://localhost:8080/trips`
   Puedes usar un número de puerto diferente modificando el valor **_NGINX_PORT_** en el archivo **.env**

## Pruebas
Para ejecutar pruebas unitarias:
```bash
docker-compose exec trip_planner php ./vendor/bin/phpunit
```

## Mejoras Futuras
- Implementar autenticación de usuarios.
- Corregir pruebas con claves únicas.
- Limitar la reserva a fechas futuras.

## Autor
- [Ismael Romero Ortega](https://github.com/isrortega)
