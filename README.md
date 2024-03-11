# Additional CLI commands for Shopware 6

## About this plugin

When installing and managing Shopware instances, especially when using Ansible for configuration management, a lot of crucial CLI commands are just plain MISSING. This project is intended for learning purposes and for having others participate from my learning.

Developing with Docker image (like [shyim/shopware](https://hub.docker.com/r/shyim/shopware/)), which is often used for production environments.

Initialized via (but modified afterwards):
```sh
docker compose run --rm -it --entrypoint /usr/bin/sudo shopware -E -u www-data php /var/www/html/bin/console plugin:create --create-config DynamicFilesCLIAdditions
```

## Dev-Setup
Some commands I am using while development after `docker compose up`:

### Install and activate plugins
```
docker compose run --rm -it --entrypoint /usr/bin/sudo shopware -E -u www-data php /var/www/html/bin/console plugin:install DFCLIAdditions
docker compose run --rm -it --entrypoint /usr/bin/sudo shopware -E -u www-data php /var/www/html/bin/console plugin:activate DFCLIAdditions
```

### Skip that nasty First-Run-Wizard
```
docker compose run --rm -it --entrypoint /usr/bin/sudo shopware -E -u www-data php /var/www/html/bin/console system:config:set core.frw.completedAt "2024-03-01T12:00:00+00:00"
```

## Knowhow
https://symfony.com/doc/current/service_container/debug.html
```
docker compose run --rm -it --entrypoint /usr/bin/sudo shopware -E -u www-data php /var/www/html/bin/console debug:container
```

```
docker compose run --rm -it --entrypoint /usr/bin/sudo shopware -E -u www-data php /var/www/html/bin/console cli-additions --help
```
