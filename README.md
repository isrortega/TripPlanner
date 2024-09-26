# TripPlanner
A system for managing trip reservations, assigning available drivers and vehicles according to the selected date and the license type.

**Note:** There is a Spanish version of this file available as `README.es.md` if you prefer to read it in that language.
**Nota:** Hay una versión en español de este archivo disponible como `README.es.md` si prefiere leerlo en ese idioma.


## Table of Contents
- [Overview](#overview)
- [Requirements](#requirements)
- [Technical Decisions](#technical-decisions)
- [Project Structure](#project-structure)
- [Installation](#installation)
- [Usage](#usage)
- [Testing](#testing)
- [Future Improvements](#future-improvements)

## Overview
This project is a trip reservation system where users can select a date, and the system will display available vehicles and drivers. It was developed using PHP 8.2 and the Symfony framework, applying SOLID principles and an object-oriented approach.

## Requirements
List the technologies and versions needed to run the project
- Docker and Docker Compose

## Technical Decisions
- **Symfony Framework:** Symfony was chosen due to its robustness and extensive documentation.
- **Docker Environment:** Docker was used to simplify the environment setup and ensure consistent behavior across different machines.
- **Bootstrap for Frontend:** Bootstrap was selected as the frontend framework due to its simplicity and responsiveness, allowing for a quick and consistent design implementation across devices.

## Project Structure
TripPlanner/
├── docker-compose.yml (Docker Compose configuration file for setting up containers)
├── docker-entrypoint.sh (Custom entrypoint script to initial composer install)
├── Dockerfile (Dockerfile that defines the image setup for the application)
├── nginx/
│   └── nginx.conf (Main Nginx configuration file)
│   └── conf.d/
│       └── default.conf (Nginx configuration for virtual hosts)
├── public/
│   └── (Symfony public files like como index.php, etc.)
├── src/
│   └── (Symfony application source code)
├── var/
│   └── (Symfony temporary and cache files)
├── vendor/
│   └── (Composer dependencies)
├── .env (Environment configuration file for the Symfony project)
└── .gitignore (Git ignore file specifying files and directories to exclude from version control)

## Installation
### Docker
1. Clone the repository:
   ```bash
   git clone https://github.com/your_username/TripPlanner.git
   ```
2. Navigate to the project folder:
   ```bash
   cd TripPlanner
   ```
3. Start Docker containers:
   ```bash
   docker-compose up -d
   ```
   There is no need to run the migrations because the docker-entrypoint file handles this

## Usage
- Access the trip list: `http://localhost:8080/trips`
   You can use a different port number by modifying the **_NGINX_PORT_** value in the **.env** file

## Testing
To run unit tests:
```bash
docker-compose exec php bin/phpunit
```

## Future Improvements
- Implement user authentication.

## Author
- [Ismael Romero Ortega](https://github.com/isrortega)
