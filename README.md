# MercaTodo

MercaTodo es una aplicación web para administrar productos de mercardería online, con el fin de que puedan ser vendidos a los clientes que hagan uso de la plataforma. Ademas, MercaTodo contará con la posibilidad de realizar pagos online y de generar reportes que sirvan de apoyo para la toma de decisiones.

## Instalación

Clonar el repositorio

    git clone https://github.com/admontero/mercatodo.git

Cambiar a la carpeta del repositorio

    cd mercatodo

Instalar las dependencias de PHP usando composer

    composer install

Copia el archivo ejemplo de variables de entorno y haz las configuraciones requeridas en tu archivo .env

    cp .env.example .env

Genera una key para la aplicación

    php artisan key:generate

Ejecuta las migraciones de la base de datos

    php artisan migrate

Prepara Laravel Passport para su uso

    php artisan passport:install

Instalar las dependencias de JavaScript usando npm

    npm install

Compila los paquetes en desarrollo

    npm run dev

Levanta el servidor de desarrollo

    php artisan serve

Ahora puedes acceder al servidor desde http://localhost:8000

**Listado de comandos**

    git clone https://github.com/admontero/mercatodo.git
    cd mercatodo
    composer install
    cp .env.example .env
    php artisan key:generate

**Antes de ejecutar las migraciones asegurate de crear la base datos y de usar la información correspondiente para conectarla desde el archivo .env**

    php artisan migrate
    php artisan passport:install
    npm install
    npm run dev
    php artisan serve

## Seeding de la base de datos

Ejecuta todos los seeders configurados para la aplicación

    php artisan db:seed

***Nota*** : Se recomienda que la base de datos esté limpia antes de ejecutar los seeders de la aplicación, para ello puedes ejecutar el siguiente comando

    php artisan migrate:refresh

## Configuración de variables de entorno

Recuerda configurar las variables de entorno relacionadas al envío de correos electrónicos y las de la integración con la plataforma de pagos PlaceToPay.

------------

## Resumen del código

## Dependencias

- [intervention/image](https://github.com/Intervention/image) - Para manipular (redimensionar, cortar, ajustar) las imágenes.
- [larastan](https://github.com/nunomaduro/larastan) - Para analizar y mejorar la calidad del código.
- [laravel-debugbar](https://github.com/barryvdh/laravel-debugbar) - Para monitorizar el rendimiento de la aplicación.
- [laravel-model-states](https://github.com/spatie/laravel-model-states) - Para gestionar los cambios de estado de los modelos, implementando el patrón state y máquinas de estado.
- [laravel-permission](https://github.com/spatie/laravel-permission) - Para la gestión de roles y permisos.
- [log-viewer](https://github.com/opcodesio/log-viewer) - Para la gestión de logs.
- [passport](https://github.com/laravel/passport) - Para la autenticación de usuarios.
- [php-cs-fixer](https://github.com/PHP-CS-Fixer/PHP-CS-Fixer) - Para arreglar automáticamente los errores en estándares de código.
- [predis](https://github.com/predis/predis) - Para interactuar con Redis.
- [spanish](https://github.com/laravel/ui) - Para laravel en español (traducciones por defecto).
- [ui](https://github.com/laravel/ui) - Para el scaffolding de bootstrap y vue incluyendo el login y registro.

## Autor

- [Andrés Montero](https://github.com/admontero)
