# E-dent

## Instructions

### Running with Docker Compose

```bash
docker-compose up -d
```

### Running with docker

```bash
docker image build --tag edent-app:v1.0.0 -f Dockerfile .
docker container run --name edent-app edent-app:v1.0.0
```

### Running appart (requires a web server and MySQL)

> To import data run the following command in terminal/shell;

```bash
mysql -u ${MYSQL_USER} -p${MYSQL_PASS} -h ${MYSQL_HOST} -P ${MYSQL_PORT} < ./migrations/data.sql
```

* Execution

> The project must be executed in na web server, like Apache, Nginx, or even in the embedded PHP web server with the command:

```bash
php -S 0.0.0.0:80
```
