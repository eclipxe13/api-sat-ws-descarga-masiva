# Configuración de entorno

Estas variables de configuración son utilizadas por el proyecto y controlan su ejecución.

## `AUTHORIZATION_TOKEN`

Establece el *hash* del token de autorización. Como todos los tokens, si se sospecha que se ha perdido,
se debe usar uno nuevo, para ello solo es necesario cambiar este valor.

### Uso de `bin/create-authorization-token.php`

Esta es una herramienta que genera un token de autorización aleatorio y su *hash* para almacenarlo como
una variable de configuración, y nos muestra el `AUTHORIZATION_TOKEN` y la cabecera HTTP `Authorization`:

```shell
php bin/create-authorization-token.php
```

```text
Set up the environment with AUTHORIZATION_TOKEN='$2y$10$WW0N4Ei1zUId7q5uapV2WOlbx9EQJyhLcc3kGkrhkey9I6ip1cCgS'
Your client must use the HTTP authorization header:
   Authorization: Bearer ad418b254561de0b16253a312b360f3973ca8a16
```
