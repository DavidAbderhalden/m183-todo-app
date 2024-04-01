# LB2 Applikation
Diese Applikation ist bewusst unsicher programmiert und sollte nie in produktiven Umgebungen zum Einsatz kommen. Ziel der Applikation ist es, Lernende für mögliche Schwachstellen in Applikationen zu sensibilisieren, diese anzuleiten, wie die Schwachstellen aufgespürt und geschlossen werden können.

Die Applikation wird im Rahmen der LB2 im [Modul 183](https://gitlab.com/ch-tbz-it/Stud/m183/m183) durch die Lernenden bearbeitet.

## Hinweise zur Installation
Bevor mit `docker compose up` die Applikation gestartet wird, muss der Source-Pfad für's Volume an Ihre Umgebung angepasst werden (dass die todo-list-Applikation auch korrekt in den Container rein gelinkt wird). Wichtig: die DB wird nicht automatisch erzeugt. Verbinden Sie sich dafür mit einem SQL-Client Ihrer Wahl auf den Datenbankcontainer (localhost port 3306) und verwenden Sie [m183_lb2.sql](src/m183_lb2.sql), um die Datenbank / Datenstruktur zu erzeugen. Beachten Sie, dass die Datenbank nach einem "neu bauen" des Containers wieder weg sein wird und Sie diese nochmals anlegen müssten.

## Run
In order to be able to run the application either in production mode or debug mode you have to create two files:

### `./docker/mysql/.env`

For development purposes you can just use the following contents:

```
MARIADB_RANDOM_ROOT_PASSWORD=yes
MARIADB_ROOT_HOST=localhost
MARIADB_DATABASE=m183_lb2
MARIADB_USER=dev
MARIADB_PASSWORD=3a929f2b-aa0c-4243-95e0-907b56eae698
```

### `./docker/php/.env`

```
DBSERVER=m183-mysql
```

Both of these files hold information about the database configuration. 

Also a `./src/config.php` file needs to be provided as well as the `./src/google.json` file, If you want to make use of the google oauth feature.

### `./src/config.php`
_This will do for dev purposes if you used the .env file example above_
```php
<?php
// Database credentials
const DB_HOST = 'm183-mysql';
const DB_USER = 'dev';
const DB_PASS = '3a929f2b-aa0c-4243-95e0-907b56eae698';
const DB_NAME = 'm183_lb2';
```

Make sure you have php composer installed on your local machine and run the following commands inside the `./src` directory:
```
composer update --no-plattform-reqs
composer install
```

Make sure your docker daemon is running before proceeding.

### Production Mode

Run the following commands to start the docker containers.

```
docker compose -f docker-compose.yml build --no-cache
docker compose -f docker-compose.yml up
```

### Debug Mode

Make sure you've enabled "Listening for PHP Debug connections" in your IDE. 
Then run the following commands to start the docker containers.

```
docker compose -f docker-compose.dev.yml build --no-cache
docker compose -f docker-compose.dev.yml up
```