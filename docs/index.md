# EAGLE-FOX Docs

La aplicación se forma de varias capas y entornos.

## PHPDoc

Los documentos de PHPDoc se generan usando el sistema de anotaciones de PHP.

[Documentación de PHP](phpdoc/index.html)

## Consideraciones

Solo hay un `.env` que engloba toda la configuración.

Este fichero se montará en cada uno de los volúmenes que apunta a donde sea necesario,
esto permite que un solo fichero `.env`, el cual, **está ignorado de git** por cuestiones
de seguridad básicas, sea el único que tengamos que custodiar al margen del repositorio.

> **PRIMERA VEZ:** Debes proporcionar el fichero `.env` que incluye todas las configuraciones necesarias,
> desde MySQL hasta PHP.

### Ejemplo de fichero `.env`

Tendrás que rellenar tus datos acorde a tu entorno.

```dotenv
MYSQL_ROOT_PASSWORD= 
MYSQL_DATABASE=
GIT_USERNAME=
GIT_TOKEN=""

APP_NAME=eagle-fox
APP_ENV=local
APP_KEY=
APP_DOWN=false
APP_DEBUG=true
APP_PORT=5500
APP_URL=http://localhost:5500/

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=eagle-fox
DB_USERNAME=
DB_PASSWORD=
DB_CHARSET=utf8
DB_COLLATION=utf8_unicode_ci

MAIL_DRIVER=smtp
MAIL_HOST=
MAIL_PORT=
MAIL_DEBUG=
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null

PROD_SERVER=
PROD_PORT=22
PROD_USER=

SERVER_NAME=LEAF_SERVER
SERVER_PORT=5500
SERVER_USER=
SERVER_PASSWORD=

APPLICATION_DIR=leaf
APPLICATION_PATH=leaf
```

### ¿Por qué hay contextos?

Usamos Dockerfiles para construir los contenedores, por lo que, para evitar tener que estar
usando rutas relativas, se han establecido contextos, que no son más que punteros hacia sus carpetas,
de modo que al hacer:

```yaml
  php8:
    container_name: fox-php8
    hostname: php8.fox
    build:
      context: back/
      dockerfile: ../docker/Dockerfile-php
    volumes:
      - './back/api/:/var/www/html/api'
      - './.env:/var/www/html/api/.env'
```

Si reviso su Dockerfile veré que el contexto es `back/`, por lo que, al hacer `COPY . .` en el Dockerfile,

````dockerfile
WORKDIR /var/www
RUN chown -R www-data:www-data /var/www && chmod -R 755 /var/www
COPY 000-default.conf /etc/apache2/sites-available/000-default.conf
RUN a2enmod rewrite headers
````

etc...

### Por qué hay dos docker-compose

Además del fichero `.env`, a docker-compose se le puede pasar al iniciar el contenedor
diversas variables de entorno, por lo que, para evitar tener que estar cambiando el fichero
`.env` cada vez que queramos cambiar de entorno, se han creado dos ficheros `docker-compose`.

El segundo tiene unas banderas a mayores, las cuales son:

```yaml
    environment:
      LEAF_DEV_TOOLS: 'true'
```

Y

```yaml
    environment:
      - PHP_SERVER = "https://tuserver.com"
```

Por ejemplo, tanto desde PHP como Node / Bun, tenemos acceso a ellas,

```php
$devTools = getenv('LEAF_DEV_TOOLS');
```

```javascript
const devTools = process.env.LEAF_DEV_TOOLS;
```

Por tanto, si estamos desplegando en local, deberíamos hacer:

```bash
docker-compose up
```

Pero en producción, deberíamos hacer:

```bash
docker-compose -f docker-compose.prod.yml up
```

Para especificarle a Docker que use el otro entorno, el cual por ejemplo, oculta la depuración
de PHP o establece las variables de Node hacia el servidor por el CORS esperado ya de antemano.

### Base de datos

Se ha escogido MySQL como base de datos, por lo que, en el fichero `.env` deberás
rellenar los datos de conexión a la base de datos.

Así mismo, hay un fichero `.init.sql` que se ejecutará al arrancar el contenedor de MySQL por primera vez.

> **NOTA**: Si haces algún cambio en el `.init.sql`, deberás borrar el volumen de MySQL para que se vuelva a ejecutar.
> Esto se hace con `docker volume ls` y `docker volume rm <volume_name>`.
> O, lo cargas usando Workbench o cualquier otro gestor de MySQL.
> Un simple compose-down no eliminará el volumen con el schema antiguo.

#### Fecha y hora

Si nos fijamos en la variable:

```dotenv
    environment:
      TZ: Europe/Madrid
```

Esto establece la zona horaria de MySQL, por lo que, si tienes problemas con las fechas,
asegúrate de que la zona horaria es la correcta.

### PHP

PHP tiene una construcción de Dockerfile que se basa en la imagen oficial de PHP, pero se le añade:

- Composer y se le añaide al PATH.
- XDebug y se habilita.
- Compresión Zlib dado que la API trabaja con JSON y las respuestas pueden ser grandes pero con texto fácilmente
  comprimible.

Así mismo, una vez se reconstruya el contenedor, se instalarán las dependencias de Composer.

En el Docker-compose vemos la acción `command`, a la cual le mandamos un array de acciones en `bash`:

```yaml
    command: [
  'bash',
  '-c',
  'composer update && apache2-foreground'
]
```

Estamos indicándole que primero ejecute composer, luego ponga a la escucha Apache.

### Node / Bun

Hemos usado Vue para la interfaz, pero como Node (el runtime de JavaScript) es un poco pesado, hemos usado la
alternativa
de Bun, que es un runtime de JavaScript que se ejecuta en el servidor y está compilado en Zig, y ofrece un mejor
rendimiento
que Node.

Para ello, hemos creado un contenedor que se basa en la imagen de Bun, y que copia todo
el contenido de Vue y lo ejecuta.

#### ¿Por qué no se usa un volumen compartido?

En el caso de Node / Bun hemos tenido problemas con usuarios Windows, Bun no reconoce el cambio ni Node
de ficheros en tiempo real, por lo que, **para dev** se ha optado por que uses Node / Bun de modo local,
y la imagen al desplegar, mantiene su contenido en el contenedor.

Así mismo, tampoco depende de los `node_modules`, lo hará él internamente pero ten en cuenta que no está activado
el desarrollo aquí, sólo es para desplegar Bun!

En local deberás hacer:

```bash
cd front/
npm run dev
```

O bien

```bash
cd front/
bun dev
```