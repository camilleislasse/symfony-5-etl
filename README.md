Symfony Demo Application
========================

Test ETL in SF5

Requirements
------------

  * Docker

Installation
------------
Monter services docker 
```bash
docker-compose up -d
```
composer install
```bash
docker-compose exec php composer install
```
Migrations
```bash
docker-compose exec php bin/console doctrine:schema:update --force
```

Command using ETL
```bash
docker-compose exec php bin/console load-batch-user batchSize=1000
```
