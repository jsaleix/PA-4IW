# PA-Symfony
Repository projet annuel en Symfony 4a

### Getting started

```bash
docker-compose build --pull --no-cache
docker-compose up -d
```
###Load data fixtures
Use the following command in the php container

```bash
php bin/console doctrine:fixtures:load
```