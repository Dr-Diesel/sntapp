
## SNT API

Simple REST API app based on `contributte/apitte`

## Install with [docker compose](https://github.com/docker/compose)

1) Clone repository

2) Modify `config/local.neon` and set `host` parameter in `database`, demo uses MariaDB

   ```neon
   # Host Config
   parameters:

       # Database
       database:

           # MariaDB
           driver: pdo_mysql
           host: mariadb
           dbname: sntdb
           user: sntuser
           password: sntpwd
           port: 3306
   ```

3) Run `docker-compose up -d`
   If `php` container in initial setup stops, try to start it again, `mariadb` container startup sometimes doesn't respond quickly enough : `docker compose start`

4) Open http://localhost:4444

   Take a look at:
    - [GET] http://localhost:4444/api/public/v1/openapi/meta (Swagger format)
    - [GET] http://localhost:4444/api/v1/devices
    - [GET] http://localhost:4444/api/v1/devices?_access_token=admin
    - [GET] http://localhost:4444/api/v1/devices/1?_access_token=admin
    - [GET] http://localhost:4444/api/v1/devices/999?_access_token=admin
    - [PUT] http://localhost:4444/api/v1/devices

5) Adminer available at http://localhost:4445/ . Evt. comment out port at `docker-compose.yml`

6) MariaDB exposed at `localhost:4446` . Evt. comment out port at `docker-compose.yml`

7) Local volume for appdir commented out in `docker-compose.yml` - wsl2 communication to host machine is slow. In case necessary remove comment and the appdir is taken from docker host.

## REST API documentation

Swagger bundled in public `www` dir. Try at
- http://localhost:4444/swagger/index.html



