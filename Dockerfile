FROM debian:bullseye

RUN set -e \
    && export DEBIAN_FRONTEND=noninteractive \
    && apt-get update -y \
    && apt-get dist-upgrade -y \
    && apt-get install -y supervisor zip unzip git wget \
    && wget -q -O - https://packages.sury.org/php/README.txt | bash \
    && apt-get install -y git php-cli php-zip php-mbstring php-xml php-curl \
    && apt-get -y autoremove \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

ARG GIT_SOURCE=1
ARG GIT_REPO="https://github.com/phpcfdi/api-sat-ws-descarga-masiva.git"
ARG GIT_BRANCH="main"

COPY . /opt/sources

RUN set -e && \
    if [ "$GIT_SOURCE" -eq 1 ]; then \
        git clone -b "${GIT_BRANCH}" "${GIT_REPO}" /opt/api-sat-ws-descarga-masiva; \
    else \
        cp -r /opt/sources/ /opt/api-sat-ws-descarga-masiva; \
    fi

WORKDIR /opt/api-sat-ws-descarga-masiva

RUN set -e \
    && export COMPOSER_ALLOW_SUPERUSER=1 COMPOSER_NO_INTERACTION=1 \
    && composer self-update \
    && composer --version \
    && composer config --list \
    && curl --silent https://composer.github.io/releases.pub  --output "$(composer config home --global)"/keys.dev.pub \
    && curl --silent https://composer.github.io/snapshots.pub --output "$(composer config home --global)"/keys.tags.pub \
    && composer diagnose \
    && composer update --no-progress --prefer-dist --no-dev --optimize-autoloader \
    && rm -rf "$(composer config cache-dir --global)" "$(composer config data-dir --global)" "$(composer config home --global)"

EXPOSE 80

COPY ./docker/supervisord.conf /etc/supervisor/supervisord.conf

ENTRYPOINT ["/usr/bin/supervisord", "-c", "/etc/supervisor/supervisord.conf"]
