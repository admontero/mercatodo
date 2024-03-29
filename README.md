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

**Antes de ejecutar las migraciones asegurate de crear la base datos y de usar la información correspondiente para conectarla desde el archivo .env**

Ejecuta las migraciones de la base de datos

    php artisan migrate

Prepara Laravel Passport para su uso

    php artisan passport:install

Ejecuta todos los seeders configurados para la aplicación

    php artisan db:seed

Instalar las dependencias de JavaScript usando npm

    npm install

Compila los paquetes en desarrollo

    npm run dev

Levanta el servidor de desarrollo

    php artisan serve

Ahora puedes acceder al servidor desde http://localhost:8000

## Configuración de variables de entorno

Recuerda configurar las variables de entorno relacionadas al envío de correos electrónicos y las de la integración con la plataforma de pagos PlaceToPay.

------------

## Dependencias

- [barryvdh/laravel-debugbar](https://github.com/barryvdh/laravel-debugbar) - Para monitorizar el rendimiento de la aplicación.
- [dompdf/dompdf](https://github.com/dompdf/dompdf) - Para generar archivos PDF.
- [intervention/image](https://github.com/Intervention/image) - Para manipular (redimensionar, cortar, ajustar) las imágenes.
- [laravel/passport](https://github.com/laravel/passport) - Para la autenticación de usuarios.
- [laravel/ui](https://github.com/laravel/ui) - Para el scaffolding de bootstrap y vue incluyendo el login y registro.
- [Laraveles/spanish](https://github.com/Laraveles/spanish) - Para laravel en español (traducciones por defecto).
- [nunomaduro/larastan](https://github.com/nunomaduro/larastan) - Para analizar y mejorar la calidad del código.
- [opcodesio/log-viewer](https://github.com/opcodesio/log-viewer) - Para la gestión de logs.
- [PHP-CS-Fixer/PHP-CS-Fixer](https://github.com/PHP-CS-Fixer/PHP-CS-Fixer) - Para arreglar automáticamente los errores en estándares de código.
- [predis/predis](https://github.com/predis/predis) - Para interactuar con Redis.
- [spatie/laravel-model-states](https://github.com/spatie/laravel-model-states) - Para gestionar los cambios de estado de los modelos, implementando el patrón state y máquinas de estado.
- [spatie/laravel-permission](https://github.com/spatie/laravel-permission) - Para la gestión de roles y permisos.

## Documentación API

[MercaTodo API](https://documenter.getpostman.com/view/9609007/2s946fdsFX#c2cec13f-d4c4-4399-8dd5-a4aa1b219b6f)

## Autor

- [Andrés Montero](https://github.com/admontero)
