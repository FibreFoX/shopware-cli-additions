# Additional CLI commands for Shopware 6

function ideas:

* Settings > Shop > Rule Builder (Tabelle "rule")
* Settings > System > User&Roles (Tabelle "acl_role")
    array trom database table column "privileges"
    
Initialized via:
```sh
docker compose run --rm -it --entrypoint /usr/bin/sudo shopware -E -u www-data php /var/www/html/bin/console plugin:create --create-config DynamicFilesCLIAdditions
```
