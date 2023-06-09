# Construcción de la imagen

Para construir la imagen de docker no es necesario todo el proyecto. Puedes seguir estos pasos:

```shell
mkdir -p /tmp/docker-api-sat-ws-descarga-masiva/docker/
wget -O /tmp/docker-api-sat-ws-descarga-masiva/Dockerfile \
    https://raw.githubusercontent.com/phpcfdi/api-sat-ws-descarga-masiva/main/Dockerfile
wget -O /tmp/docker-api-sat-ws-descarga-masiva/docker/supervisord.conf \
    https://raw.githubusercontent.com/phpcfdi/api-sat-ws-descarga-masiva/main/docker/supervisord.conf
docker build --tag api-sat-ws-descarga-masiva /tmp/docker-api-sat-ws-descarga-masiva/
rm -rf /tmp/docker-api-sat-ws-descarga-masiva/
```

O si tienes clonado el proyecto simplemente:

```shell
docker build --build-arg GIT_SOURCE=0 --tag api-sat-ws-descarga-masiva .
```

La construcción permite que especifiques los argumentos `GIT_REPO` y `GIT_BRANCH` para construir la imagen,
de esta forma podrás especificar otro repositorio y la rama con la que se creará la imagen, esto es muy útil
para hacer pruebas, por ejemplo:

```shell
docker build \
    --build-arg GIT_REPO="https://github.com/gh-username/api-sat-ws-descarga-masiva.git" \
    --build-arg GIT_BRANCH="feature/my-cool-feature" \
    --tag api-sat-ws-descarga-masiva:my-cool-feature .
```
