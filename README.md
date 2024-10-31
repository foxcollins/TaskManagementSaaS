# Task Management SaaS Application

Esta es una aplicación de Gestión de Tareas desarrollada como prueba técnica para un sistema SaaS. La aplicación permite a los usuarios gestionar sus tareas y aprovechar funcionalidades de suscripción y sugerencias de IA. 

## Características

1. **Autenticación y Registro de Usuarios**
   - Inicio de sesión y registro de usuarios mediante autenticación API First.
   - Integración con Laravel Jetstream y manejo de sesiones en el frontend con Livewire.

2. **Gestión de Tareas**
   - Crear, editar y eliminar tareas con un flujo sencillo de UI en tiempo real.
   - Posibilidad de cambiar el estado de las tareas (`pending`, `in_progress`, `completed`) mediante un sistema de arrastrar y soltar usando `Sortable.js`.
   - Límites de tareas en función del plan de suscripción del usuario (gratuito y premium).
   - Sugerencias de títulos y descripciones de tareas mediante la API de ChatGPT.

3. **Sistema de Suscripción**
   - Planes de suscripción gestionados a través de PayPal y `srmklive/paypal`.
   - Asignación de planes gratuitos por defecto y control de tareas limitado según el plan del usuario.
   - Rutas de retorno (`success` y `cancel`) para el proceso de suscripción con PayPal.
   - Gestión de la suscripción en el backend con historial de planes y la posibilidad de cancelar y cambiar suscripciones.

4. **Interfaz de Usuario (Frontend)**
   - Frontend interactivo y dinámico utilizando Livewire 3 y Tailwind CSS.
   - Componentes personalizados para la lista de tareas, vista de planes y cabecera con datos del usuario.
   - Integración de notificaciones `Toastr` para mensajes en tiempo real.
   
5. **API de Sugerencias IA**
   - Integración con la API de OpenAI para sugerencias de títulos y descripciones de tareas.
   - Límite de uso de la API configurado para evitar sobrecargar el límite de cuota.

## Configuración del Proyecto

1. **Clonar el repositorio**

   ```bash
   git clone https://github.com/tu_usuario/task-management-saas.git
   cd task-management-saas


2. **Instalación de dependencias**
    ```bash
    composer install
    npm install && npm run dev

3. **Configuración del archivo .env**
    ```bash
    cp .env.example .env

Luego, configura los siguientes parámetros:

## Base de datos: Define los datos de tu base de datos (DB_DATABASE, DB_USERNAME, DB_PASSWORD).
## Clave API de OpenAI: Obtén la clave en OpenAI y configúrala.
## Credenciales de PayPal: Agrega las credenciales de srmklive/paypal en modo de desarrollo.

4. **Generar la clave de la aplicación y migrar la base de datos**
    ```bash
    php artisan key:generate
    php artisan migrate --seed

5. **Configurar Laragon (opcional)**

    Si usas Laragon, configura la URL para la aplicación en el archivo .env:
    ```plaintext
    APP_URL=https://taskmanagement.mn/


## Ejecución del Proyecto
Inicia el servidor de desarrollo:
    ```bash
    php artisan serve


## Funcionalidades en Detalle
1. **Arrastrar y Soltar Tareas**

    - Las tareas pueden moverse entre columnas de estado (pending, in_progress, completed) utilizando Sortable.js.
    - Después de mover una tarea, el estado se actualiza en el backend manteniendo la posición actual.
    - Limitación de Tareas según Suscripción

2. **Control de la cantidad de tareas disponibles para cada tipo de plan (gratuito/premium).**
    - Mensaje de alerta al usuario cuando se alcanza el límite de tareas.
    - Sugerencias de Títulos y Descripciones con OpenAI

3. **La API de OpenAI proporciona sugerencias de título y descripción mientras el usuario escribe.**
    - Configurado para limitar el número de llamadas a la API según el límite de uso gratuito.
    - Endpoints y API
    - La aplicación sigue un enfoque API First para la mayoría de sus funcionalidades. Aquí algunos endpoints destacados:

4. **Usuarios: /api/users - Registro y autenticación de usuarios.**
    - Tareas: /api/tasks - CRUD de tareas.
    - Suscripciones: /api/subscriptions - Gestión de planes y suscripciones.


## Tecnologías Utilizadas
    - Backend: Laravel 11, Livewire 3
    - Frontend: Tailwind CSS, Sortable.js, Toastr
    - API de Pagos: PayPal (srmklive/paypal)
    - API de Sugerencias: OpenAI GPT API


## Contribuciones
    Las contribuciones son bienvenidas. Por favor, sigue estos pasos para colaborar:

    Fork el proyecto.
    Crea una nueva rama (git checkout -b feature/nueva-feature).
    Realiza tu commit (git commit -am 'Añade una nueva feature').
    Haz push de la rama (git push origin feature/nueva-feature).
    Abre un Pull Request.

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
