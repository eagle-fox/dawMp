![Logotipo circular](./assets/logo_circle.svg)

# Documentación

  

## Índice

  

### 1. Integrantes e idea

### 2. Docker

### 3. Carpetas

#### 3.1 Front

#### 3.2 Back

#### 3.3 DB

#### 3.4 Chema

#### 3.5 Webhook

### 4 Programación

  

## 1. Integrantes e idea

  

-   Yeison Gonzalez Rascado

-   Ricardo Vega Alonso

-   Pedro Seoane Prado

  

Con la creacion de Eagle-Fox se pretendía dar solución a un problema que atañe a la tenencia de animales, tanto de forma doméstica como a modo de explotación agrícola. Bajo esta idea y gracias al avance de la tecnología determinamos un sencillo problema. Los precios de los localizadores de la competencia son muy poco accesibles al comprador al por menor, representan un mantenimiento costoso y un constante problema por su poca precisión. Con esto en mente decidimos explotar el nicho de mercado, llegando a las conclusiones que se citarán en el siguiente apartado.

  

## 2. Docker

  

El funcionamiento de nuestro docker cumple una idea básica, crear el entorno de forma automática, sin necesidad de generar configuraciones extra. Con esta idea podemos destacar los 4 micro-servicios principales:

a) **IOT**
b) **php8**
c) **MySQL**
d) **Chema**

Con esto claro, solo queda desgranar cada micro-servicio para comprender su utilidad:

a) IOT:
- **Nombre del Contenedor:** fox-iot 
- **Configuración de Construcción:** 
	- Contexto: Generación de datos similares a los que ofrecería una placa
	- Archivo Docker: ../docker/Dockerfile-iot-go 
- **Mapeo de Puertos:** 2006:2006
- **Montaje de Volumen:** Se monta en '/go/src/app/dawMp'

b) php8:
- **Nombre del Contenedor:** fox-php8 
- **Configuración de Construcción:** 
	- Contexto: Gestión de los datos, se realizan peticiones y se reciben mediante .json
	- Archivo Docker: ../docker/Dockerfile-php 
- **Montaje de Volumen:** Se monta './back/api/' en '/var/www/html/api'
- **Mapeo de Puertos:** 2003:80
- **Datos adicionales:** Implementa LEAF_DEV_TOOLS para el análisis de sesiones coockies...

c) MySQL:
- **Nombre del Contenedor:** fox-mysql 
- **Configuración de Construcción:** 
	- Contexto: Uso de base de datos
- **Montaje de Volumen:** Se monta 'fox-eagle-db' en '/var/lib/mysql' y se monta './db/init.sql' en '/docker-entrypoint-initdb.d/init.sql'
- **Mapeo de Puertos:** 2005:3306

d) Chema:
- **Nombre del Contenedor:** fox-chema
- **Configuración de Construcción:** 
	- Contexto: Generación de HTML estático para mostrar la base de datos y sus problemas
- **Montaje de Volumen:** Se monta './chema/output' en '/output' y './chema/config' en '/config'

Una vez realizada la explicación de los 4 servicios es necesario aclarar también una serie de detalles, para comenzar, existen 2 redes distintas, divididas de esta forma:


| Nombre | Tipo | Explicación |
|----------|----------|----------|
| foxtrot-api-lan    | Interna   | Resuelve las conexiones entre los propios servicios, estableciendo el envío y respuesta de información entre back (PHP) y front (vite)  |
| foxtrot-api-lan    | Bridge   | Permite la comunicación interna y externa tanto de los contenedores como de medios externos|

Además, todas las variables de entorno de los ficheros se sacan de sus respectivos elementos .env para evitar mayor complicación y aumentar la seguridad de el completo de docker.


