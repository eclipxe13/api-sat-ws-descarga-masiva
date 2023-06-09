# Documentación

## Acerca de

Este proyecto es una JSON API para consumir la librería [`phpcfdi/sat-ws-descarga-masiva`](https://github.com/phpcfdi/api-sat-ws-descarga-masiva).

## Instalación por clonación del proyecto

```shell
git clone --branch main https://github.com/phpcfdi/api-sat-ws-descarga-masiva.git api-sat-ws-descarga-masiva
cd api-sat-ws-descarga-masiva
composer install --no-dev --prefer-dist --optimize-autoloader
```

## Ejecución con variables de entorno

```shell
env AUTHORIZATION_TOKEN='$2y$10$guL9tPaNOeS/6rMGwIy.ZeH/1BmPbcRGiGzjjkRS7SDI0bM9mBMV' \
    php -d variables_order=EGPCS -S 0.0.0.0:8080 -t public/
```

## Configuración

- [Variables de configuración del entorno](configuracion.md)

## API

- [Documentación de la API](api.md)

## Docker

Este proyecto provee los archivos necesarios para crear una imagen y la documentación para ejecutar contenedores.

- [Construcción de la imagen](docker-construir.md)
- [Crear una instancia de ejecución](docker-ejecutar.md)
