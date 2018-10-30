# Plantilla WebServices - REST (Slim - PHP).

### Ministerio de Salud de El Salvador.

Plantilla WebServices -REST que utiliza el [Framework Slim de PHP](https://www.slimframework.com/) en su versión más reciente 3.x, el objetivo de este proyecto es facilitar y agilizar la generación de Servicios Webs ofreciendo una estrucutra básica y simple que permita la creación de estos.



## Tabla de Contenido

* [Requisitos](#requisitos)
  * [Imagen de Doker](#imagen-de-docker)
* [Estructura de directorios](#estructura-de-directorios)
* [Instalación](#instalación)
* [Configuración](#configuración)
  * [Conexión a la base de datos](#conexión-a-la-base-de-datos)
  * [Tabla ejemplo de la base](#tabla-ejemplo-de-la-base)
  * [Datos de ejemplo](#datos-de-ejemplo)
  * [Configuración de los puertos de Docker](#configuración-de-los-puertos-de-docker)
* [Ejecutar la app](#ejecutar-la-app)

## **Requisitos**

| **Software**                                                 | **Versión**      |
| ------------------------------------------------------------ | :--------------- |
| Apache \| NGINX                                               | 2.4 o superior \| 1.5 o superior |
| PHP                                                          | 7.1 o superior |
| [Composer](https://getcomposer.org/download/) | lastest |
| [Slim-PHP](https://www.slimframework.com/docs/v3/start/installation.html) | 3.0 o superior |
| [PostgreSQL](https://www.postgresql.org/) | 9.4 o superior |
| [Docker CE](https://docs.docker.com/install/) | 18 o superior |
| [Docker Compose](https://docs.docker.com/compose/install/) | 1.16 o superior |

La instalación y configuración de la base de datos no es objetivo de esta guía por lo cual se omitirán en los pasos de instalación y configuración.



### Imagen de Docker

Antes de poder empezar a utilizar el framework, es necesario tener instalado [docker](https://docs.docker.com/install/) y [docker-compose](https://docs.docker.com/compose/install/) en su última versión. Una vez que se encuentran instalados se requiere descargar la imagen de php 7.1 o superiro, lo cuál se puede realizar a través de dos opciones: a través del repositorio oficial de php en **[dockerhub](https://hub.docker.com/_/php/)** o través del repositorio de la **[imagen preconfigurada](https://github.com/klb-rodriguez/docker)** proporcionada por el MINSAL.

La desición de uso dependerá del usuario, la ventaja de utilizar la imagen proporcionada por el MINSAL es que esta ya se encuentra configurada con todas las librerías de PHP y Apache necesario para el funcionamiento correcto del Framework.

En el caso que se decida utilizar una imagen diferente a la proporcionada, será necesario que  el usuario preconfigure la imagen antes de usuarla.

Para utilizar utilizar la imagen proporcinada por el MINSAL es necesario seguir los pasos de compilación que se encuentran disponible en el siguiente enlace: https://github.com/klb-rodriguez/docker



## Estructura de directorios

A continuación se muestra la estructura de directorios del poryecto.

```
.
├── logs
├── public
│   ├── .htaccess
│   └── index.php
├── src
│   ├── config
│   │   ├── database.php
│   │   └── settings.php
│   ├── middleware
│   │   └── middleware.php
│   ├── routes
│   │   └── routes.php
│   ├── jsonSchemas
│   └── dependencies.php
├── templates
├── tests
├── vendor
├── .env
├── .env.dist
├── .gitignore
├── composer.json
├── composer.lock
├── CONTRIBUTING.md
├── docker-compose.yml
├── phpunit.xml
├── README.md
└── LICENSE

```

De la estructura anterior se destacarán los suiguientes archivos y directorios los cuales serán de importancia para el desarrollo del servicio web:

- **logs**: Como su nombre lo indica en este directorio se almacenarán todos los logs que permitirán realizar DEBUG en el caso de que existan errores en la aplicación, en este directorio se almacenará un archivo llamado **`app.log`** que contendrá dicha información.
- **public**: Directorio que contiene el front-controller (index.php) que se encarga de cargar todas las configuraciones y rutas que se han de colocar para su posterior consumo.
- **src**: Directorio que contiene los archivos fuentes del proyecto.
  - **config**: Almacena los archivos de configuración y conexión a la base de datos.
  - **routes**: Contiene los archivos en el que se declaran las rutas (Endpoint) del Servicio Web.
  - **jsonSchemas**: Contiene los archivos de validación de json.
- **.env**: Archivo que contiene los datos sensibles de configuración como lo son credenciales a la base de datos, etc., este archivo debe ser creado a partir del  **`.env.dist`**, este archivo se omite en el gitignore.
- **docker-compose.yml**:  Archivo de configuración de docker-compose que permite modificar los parametros de creación del contenedor de docker.



## Instalación

Descargar desde el repositorio de github:

```bash
git clone https://github.com/klb-rodriguez/slim-api-skeleton.git app
```

En donde:

- **app**: Es el nombre que se le dará al directorio que se clonará.



## Configuración

Ante de proceder a ejecutar la aplicación es necesario realizar las siguientes configuraciones:



### Conexión a la base de datos

Para establecer los parámetros de conexión a la base de datos es necesario editar el archivo **`.env`** que se encuentra dentro del directorio raíz del proyecto, si este archivo no se encuentra, será necesario crearlo a parti del archivo  **`.env.dist`**.

Reemplazar los parámetros según las configuraciones de la base de datos.

```bash
###> database ###
DB_DRIVER=pgsql
DB_HOST=192.168.1.2
DB_NAME=slim
DB_USER=slim
DB_PASSWORD=slim
###< database ###
```

Es neceario tener en cuenta que en el caso de la IP del HOST **no se deberá de colocar** localhost o 127.0.0.1, en cambio se debe de colocar la IP estático o porporcionada por el DHCP, debido a que el contenedor de docker que se ha de generar posee su porpia red interna.

Habilitar las conexiones remotas para el rango de IP de docker, editando el archivo pg_hba, para más información de como realizar esta acción ingresar al siguiente [enlace](https://blog.bigbinary.com/2016/01/23/configure-postgresql-to-allow-remote-connection.html).



### Tabla ejemplo de la base

En la base de datos crear una tabla con el nombre de **libro** y cuya estructura sea similar a la siguiente:

```
                            Tabla «libro»
      Columna      |            Tipo             | Nullable
-------------------+-----------------------------+----------
 id                | serial                      | not null
 isbn              | text                        |
 descripcion       | text                        |
 autor             | text                        |
 fecha_publicacion | timestamp without time zone |
Índices:
    "pk_libro" PRIMARY KEY (id)
```



### Datos de ejemplo.

Dentro del proyecto se encuentra un archivo **CSV** llamado **`libros.csv`** el cual se ha proporcionado con datos de ejemplo que se requerirán para el desarrollo de esta guía, por lo cuál se recomienda cargar dichos datos a la base.



### Configuración de los puertos de Docker

Por defecto se ha configurado para que docker utilice la imagen de **php:7.2-dtic** proporcionada por el MINSAL y el **puerto 90**, si se ha de utilizar otra imagen y/o el puerto está en uso por otra aplicación será necesario cambiarlo, para lo cuál editar el archivo **`docker-compose.yml`** que se encuentra dentro del directorio raíz del proyecto

```bash
services:
    slim:
        image: php:7.2-dtic
        # ...
        ports:
            - 90:80
        # ...
```



En donde:

- **image**: Contiene el nombre de la imagen a utilizar.
- **ports**: Contiene el puerto a utilizar (cambiar el 90 por otro puerto).



## Ejecutar la app

En caso de que no se utilice docker omitir esta sección, pero requerirá que se tenga un ambiente configurado con Apache o NGINX junto con todas las librerías que esto requiere.  Para poder ejecutar la aplicación solamente es necesario ejecutar el comando docker-compose dentro del directorio raíz del proyecto como se describe a continuación:

```bash
docker-compose up -d
```

Instalar los vendors utilizando la ultima versión de [composer](https://getcomposer.org/download/):

```bash
docker exec -ti app_slim_1 bash -c "composer install"
```

Una vez ejecutado el comando anterior ya se puede ingresar al aplicativo para verificar que esté ejecutandose correctamente, para ello es neceario digitar a la url **[http://localhost:90](http://localhost:90)** en donde 90 es el puerto por defecto, si se ha modificado, será necesario colocar dicho puerto.



## Creación Métodos REST

Una vez ya configurado y ejecutandose la app, lo siguiente es crear los **endpoints** o métodos del WebServices que permitirá la interacción entre cliente y WebServices, basándose en los [Estándares de Desarrollo de Servicios Web](https://github.com/klb-rodriguez/EstandaresInteroperabilidad/blob/master/Desarrollo.md) desarrollados por diferentes instituciones en cooridnación con Gobierno Electrónico de El Salvador.

### GET

Endpoint que permite listar todos los libros según los parámetros de búsqueda proporcionados.

versión: **v1**

uri: **/libros**

dato de respuesta: **JSON**



Editar el archivo **routes.php** que se encuentra dentro del directorio `src/routes` y agregar el código que se lista a continuación:

```php
<?php

// Codigo...

// Endpoint GET que permite listar todos los libros segun los parametros de busqueda
// proporcionados
$app->get('/v1/libros', function (Request $request, Response $response, array $args) {
    // Inicialización de variables
    $datos = array();
    $conn  = $this->db->getConnection();
    $where = array();

    // Obteniendo parámetros de búsqueda
    $isbn             = $request->getParam('isbn');
    $descripcion      = $request->getParam('descripcion');
    $autor            = $request->getParam('autor');
    $fechaPublicacion = $request->getParam('fechaPublicacion');

    // verificando que al menos un parametro de busqueda sea proporcionado
    if( $isbn || $descripcion || $autor || $fechaPublicacion ) {
        if( $isbn )
            $where[] = "isbn = '".$isbn."'";

        if( $descripcion )
            $where[] = "descripcion = '".$descripcion."'";

        if( $autor )
            $where[] = "autor = '".$autor."'";

        if( $fechaPublicacion )
            $where[] = "fecha_publicacion = '".$fechaPublicacion."'";

        $where = count( $where ) > 0 ? "WHERE ".implode(" AND ", $where) : "";

        $sql = "SELECT * FROM libro $where";

        try {
            $stm = $conn->prepare($sql);
            $stm->execute();
            $datos = $stm->fetchAll(PDO::FETCH_ASSOC);
        } catch(Exception $ex) {
            return $response->withJson( array("Error al procesar la petición"), 500 );
        }
    }

    return $response->withJson( $datos, 200 );
});
```



**Ejemplo de consumo**

```bash
curl -X GET "http://localhost:90/v1/libros?isbn=9780530239033"
```

**Resultado:**

**Response**: 200 Ok

```json
[
	{
		"id": 1,
		"isbn": "9780530239033",
		"descripcion": "Iste modi accusantium autem suscipit quia et et dolorum.",
		"autor": "Roslyn Morissette",
		"fecha_publicacion": "2002-09-08 00:00:00"
	}
]
```



### GET/{id}

Endpoint que permite obtener un libro, para este método es requerido proporcionar el id o llave del libro que se desea obtener.

versión: **v1**

uri: **/libros/{id}**

dato de respuesta: **JSON**



Editar el archivo **routes.php** que se encuentra dentro del directorio `src/routes` y agregar el código que se lista a continuación:

```php
<?php

// Codigo...

// Endpoint GET que permite obtener un recurso de libro en específico
// según el id proporcionado
$app->get('/v1/libros/{id}', function (Request $request, Response $response, array $args) {
    // obteniendo el identificador del libro
    $id = $args['id'];

    // inicialización de variables
    $dato = array();
    $conn = $this->db->getConnection();

    $sql = "SELECT * FROM libro WHERE id = :id";

    try {
        $stm = $conn->prepare($sql);
        $stm->bindValue(':id', $id);
        $stm->execute();
        $dato = $stm->fetchAll(PDO::FETCH_ASSOC);
    } catch(Exception $ex) {
        return $response->withJson( array("Error al procesar la petición"), 500 );
    }

    // verificando que exista el libro con el id proporcionado
    if( count( $dato ) === 0 ) {
        return $response->withJson( array('Recurso no econtrado'), 404);
    }

    // retornando el dato correspondiente al libro
    $dato = $dato[0];

    return $response->withJson( $dato, 200 );
});
```

**Ejemplo de consumo**

```bash
curl -X GET "http://localhost:90/v1/libros/1"
```

**Resultado:**

**Response**: 200 Ok

```json
{
	"id": 1,
	"isbn": "9780530239033",
	"descripcion": "Iste modi accusantium autem suscipit quia et et dolorum.",
	"autor": "Roslyn Morissette",
	"fecha_publicacion": "2002-09-08 00:00:00"
}
```



### POST

Enpoint que permite insertar uno o más libros a la base a través del servicio web.

versión: **v1**

uri: **/libros**

datos de entrada: **Array de JSONs**

dato de respuesta: **JSON**



En el directorio `src/jsonSchemas` crear un archivo llamado **libros.json** el cual debe de contener las restricciones JSON que se han de utilizar para validar los datos entrantes similar al siguiente código:

```json
{
    "$schema": "http://json-schema.org/draft-06/schema#",
    "title": "Libros",
    "type": "array",
    "minItems": 1,
    "uniqueItems": true,
    "definitions": {
        "stringNoBlank": {
            "type": "string",
            "minLength": 1
        },
        "stringOptional": {
            "type": ["string", "null"],
            "minLength": 1
        },
        "dateTimeRequired": {
            "type": "string",
            "format": "date-time"
        }
    },
    "items": {
        "required": [ "isbn", "autor", "fechaPublicacion"],
        "additionalProperties": false,
        "properties": {
            "isbn": {
                "$ref": "#/definitions/stringNoBlank"
            },
            "autor": {
                "$ref": "#/definitions/stringNoBlank"
            },
            "descripcion": {
                "$ref": "#/definitions/stringOptional"
            },
            "fechaPublicacion": {
                "$ref": "#/definitions/dateTimeRequired"
            }
        }
    }
}


```



Editar el archivo **routes.php** que se encuentra dentro del directorio `src/routes` y agregar el código que se lista a continuación:

```php
<?php

// codigo...
use \JsonSchema\Validator AS JsonValidator;

// codigo...
$app->post('/v1/libros', function (Request $request, Response $response) {
    $headers = $request->getHeaders();

    // validando los encabezados requeridos
    if( array_key_exists('HTTP_CONTENT_TYPE', $headers) == false ) {
        return $response->withJson( array('Error en la petición'), 400);
    }

    // obteniendo el contenido del cuerpo en formato Array
    $jsons = $request->getParsedBody();

    // validando que se envie al menos un elemento a insertar
    if( !$jsons ) {
        return $response->withJson( array('Entrada invalida documento JSON vacio'), 400);
    }

    $jsonsRaw = json_encode($jsons);
    // Obteniendo el JSON SCHEMA
    $schema = json_decode( file_get_contents( __DIR__.'/../jsonSchemas/libros.json' ) );

    $jsonValidator = new JsonValidator();

    // Validando el json con el schema
    $jsonValidator->validate( json_decode( $jsonsRaw ), $schema );

    if ( $jsonValidator->isValid() === false ) {
        return $response->withJson( array( 'error' => 'Entrada invalida, estructura del documento JSON no válido', 'detalle' => $jsonValidator->getErrors() ), 400);
    }

    // boteniendo la conexion a la base
    $conn = $this->db->getConnection();

    $sql    = "INSERT INTO libro(isbn, autor, descripcion, fecha_publicacion) VALUES";
    $values = array();
    // recorriendo cada json a insertar
    foreach ( $jsons as $json ) {
        $isbn             = $json['isbn'];
        $autor            = $json['autor'];
        $descripcion      = $json['descripcion'] ? "'".$json['descripcion']."'" : null;
        $fechaPublicacion = $json['fechaPublicacion'];

        $values[] = "('$isbn', '$autor', $descripcion, '$fechaPublicacion')";
    }

    $sql .= implode(", ", $values);

    try {
        $stm = $conn->prepare($sql);
        $stm->execute();
        $dato = $stm->fetchAll();
    } catch(Exception $ex) {
        return $response->withJson( array("Error al procesar la petición"), 500 );
    }

    return $response->withJson( array('Recurso de libro creado'), 201);
});

```

**Ejemplo de consumo**

json a enviar:

```json
[
    {
    	"isbn": "9793695330927",
   		"descripcion": "Ea et sit enim molestias sunt. Aperiam tenetur rerum aut tempore dolorem. Libero maxime voluptatem quidem.",
   		"autor": "Miss Priscilla Adams",
   		"fechaPublicacion": "1993-06-06T00:00:00+00:00"
    },
    {
        "isbn": "9782575801305",
        "descripcion": "Ab necessitatibus exercitationem nemo et expedita culpa. Mollitia et veniam eaque et recusandae. Qui tenetur aut perspiciatis molestias sed dicta.",
        "autor": "Mr. Odell Schuster V",
        "fechaPublicacion": "1990-02-18T00:00:00+00:00"
    }
]
```

Consumo del endpoint:

```bash
curl -X POST "http://localhost:90/v1/libros" -H "accept: application/json" -H "Content-Type: application/json" -d "[ { \"isbn\": \"9793695330927\", \"descripcion\": \"Ea et sit enim molestias sunt. Aperiam tenetur rerum aut tempore dolorem. Libero maxime voluptatem quidem.\", \"autor\": \"Miss Priscilla Adams\", \"fechaPublicacion\": \"1993-06-06T00:00:00+00:00\"}, { \"isbn\": \"9782575801305\", \"descripcion\": \"Ab necessitatibus exercitationem nemo et expedita culpa. Mollitia et veniam eaque et recusandae. Qui tenetur aut perspiciatis molestias sed dicta.\", \"autor\": \"Mr. Odell Schuster V\", \"fechaPublicacion\": \"1990-02-18T00:00:00+00:00\"} ]"
```

**Resultado:**

**Response**: 201 Created

```json
["Recurso de libro creado"]
```

