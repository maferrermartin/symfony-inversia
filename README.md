# Symfony Inversiva

Se trata de una pequeña aplicación API Rest desarrollada con Symfony
Contiene dos controladores para dos entidades: Category y Product

Las direcciones y protocolos son:

https://localhost/categories - GET -> Devuelve una lista en formato JSON de los datos de todas las categorias

https://localhost/categories/{id} - GET -> Devuelve un objeto en formato JSON de la categoria con el ID dado

https://localhost/categories - POST -> Permite añadir una nueva categoria

https://localhost/categories/{id} - POST/PUT/PATCH -> Permite editar una categoria

https://localhost/categories/{id} - DELETE -> Elimina la categoria asociada al ID dado

https://localhost/products - GET -> Devuelve una lista en formato JSON de los datos de todas los productos

https://localhost/products/{id} - GET -> Devuelve un objeto en formato JSON del producto con el ID dado

https://localhost/products - POST -> Permite añadir un nuevo producto

https://localhost/products/{id} - POST/PUT/PATCH -> Permite editar un producto

https://localhost/products/{id} - DELETE -> Elimina el producto asociada al ID dado

Nota: Por algún motivo cuando hacía pruebas con POSTMAN no me permitia realizar actualizaciones de entidades con PUT/PATCH, no he conseguido que pasará los parámetros, por ese motivo está habilitado el método POST en la actualización

En la carpeta tests/Postman hay un fichero con una configuración de POSTMAN con todas las llamadas configuradas para hacer pruebas

# 1: Iniciar proyecto:

El proyecto se encuentra contenido en un contenedor docker:

1. Instalar docker si no se dispone del programa, [install Docker Compose](https://docs.docker.com/compose/install/) (v2.10+)
2. Iniciar `docker compose build --no-cache` para cargar los contenedores
3. Iniciar `docker compose up --pull always -d --wait` para iniciar el proyecto
4. Abrir `https://localhost` en el navegador y [accept the auto-generated TLS certificate](https://stackoverflow.com/a/15076602/1352334)
5. Iniciar `docker compose down --remove-orphans` cuando se quiera detener los contenedores

# 2. API y Testing

Se han hecho pequeñas pruebas para hacer pruebas de API y mostrar algunos datos de una interfaz documentada de la API

En la URL: https://localhost/api se puede observar una pequeña definición de los datos y métodos de la entidad de Category
Estaría aún incompleta y con datos por complementar, se ha realizado con API Platform (https://api-platform.com)

Para el testing se ha creado una pequeña prueba mediante pruebas de API:

1. Se han generado Factory para las entidades, son configuraciones para generar datos aleatorios en la base de datos de dev. El objetivo de esto es poder hacer pruebas de escalabilidad y generar datos para realizar los test

2. Para generar datos de pruebas hay que executar el comando:
```shell
docker compose exec php bin/console doctrine:fixtures:load
```
Nota: Si se quisiera aumentar o disminuir la cantidad de datos habría que modificar los valores de las clases DefaultCategoryStory y DefaultProductStory situadas en App/src/Story

3. Los tests se executan con el comando:

```shell
docker compose exec php bin/phpunit
```

Nota: Hay un test que hace una prueba de llamada a un controlador para simular una petición. Cada vez que se inician los tests la BBDD es restaurada y se cargan datos aleatorios. Sólo hay un test para la primera llamada GET, por algún motivo no he conseguido que en las llamadas de tipo POST pasará los parámetros en el body o json.
