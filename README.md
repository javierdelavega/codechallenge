# Codechallenge #

Misión: Desarrollar una API para la gestión de una cesta de la compra.

## Requisitos ##

* Gestión de productos eficiente que permita: añadir, actualizar y eliminar productos del carrito.
* Obtener el número total de productos en el carrito.
* Confirmar la compra del carrito.
* Debe estar desacoplado del dominio.

## Instalación ##

Para la instalación del entorno de desarrollo y pruebas he preparado un contenedor docker. Para instalarlo:

```git clone https://github.com/javierdelavega/codechallenge.git```  
```cd codechallenge/docker```  
```docker compose up -d --build```

NOTA: La primera vez el contenedor tardará en iniciar completamente de 1-2 minutos, mientras realiza las tareas de preparación de la BD y la instalación de los paquetes con composer install.

Tras la instalación tendremos dos servicios:

* **http://localhost:8005** la API.
* **http://localhost:8006** el frontend de demostración.

## Documentación y tests ##

La documentación está accesible a través del servicio frontend:

* **http://localhost:8006/appdoc/** la documentación de la App.
* **http://localhost:8006/apidoc/** la documentación y especificación de la api y sus endpoints.
* **http://localhost:8006/test-report/** el coverage report de los tests.

Para la realización de los tests:

```docker exec -it codechallenge run-test-coverage```

## Pruebas performance ##

Para realizar pruebas realistas de rendimiento, he subido la app a un servidor **se nota una gran diferencia de rendimiento** con el contenedor docker de desarrollo, al servirse desde nginx, y tener Symfony en producción, cache de composer, rutas, etc.

* **https://codechallenge.smartidea.es** la API
* **https://codechallenge-front.smartidea.es** el frontend de demostración
* **https://codechallenge-front.smartidea.es/appdoc/** la documentación de la App.
* **https://codechallenge-front.smartidea.es/apidoc/** la documentación y especificación de la api y sus endpoints.
* **https://codechallenge-front.smartidea.es/test-report/** el coverage report de los tests.

## Diseño ##

* Se ha utilizado el framework Symfony en su version 6.3 (current).
* Se ha utilizado Doctrine para la persistencia.
* Se ha diseñado siguiendo **D**omain **D**riven **D**esign. El dominio está desacoplado de los conceptos de insfraestructura como la persistencia o la API.
* Las invariantes del dominio están gestionadas principalmente por las entidades, o lo más próximo a ellas posible.
* Se definen los siguientes Bounded Contexts: **Auth** (gestión de usuarios), **Billing** (carrito y pedidos), **Catalog** (productos de la tienda).
* Se han utilizado repositorios orientados a persistencia. Una aproximación mas purista hubiera sido con repositorios orientados a colecciones. Es un tema que sigo probando.
* El cálculo del precio total del carrito lo realiza el servicio **UpdateCartTotalService** suscrito a un evento de dominio **CartContentChanged**. La entidad **Cart** publica ese evento cuando procede.
* No se persisten los eventos de dominio para simplificar y al no tener una aplicación práctica en esta demo.
* La API funciona con conexiones stateless, y estarán autenticadas con un Bearer Token.
* Para la gestión de los tokens de acceso y la autenticación se ha utilizado el Access Token Authentication de Symfony, se han escrito User Providers y Token Handlers personalizados y adaptados a las necesidades.
* Se ha desacoplado la entidad de dominio **User** de los aspectos de autenticación a través de la clase de infraestructura **SecurityUser** siendo esta la que trata con el sistema de seguridad. 
* Se obtendrá una lista de productos de la tienda desde una BD y el carrito solo permitirá añadir productos existentes en esa BD.
* El carrito estará asociado a un usuario para que pueda guardar los artículos en la cesta y posteriormente realizar la compra.

La aproximación que he seguido para los usuarios es la siguiente: 

* Las requests a los endpoints de la api deberán estar autenticadas. Excepto el endpoint para obtener un nuevo token de acceso y el endpoint para realizar el login.
* Si el cliente no presenta un token de acceso (primera visita) Debe solicitar uno nuevo a la api, creando para el un nuevo usuario Invitado. 
* Los usuarios invitados pueden registrarse, pasando a ser usuarios registrados, que se podrán identificar con unos credenciales en cualquier momento.
* Los usuarios invitados tienen una caducidad. Si no se registran se eliminarán de la BD pasado un tiempo (una semana para este ejemplo).

## Desarrollo ##


* En primer lugar se define la lógica de negocio, y las invariables del dominio.
* Se define la estructura y los endpoints de la API.
* Se desarrolla la capa de dominio.
* Se desarrolla la capa de infraestructura encargada de la persistencia.
* Se desarrolla la capa de infraestructura encargada de la entrega (Los controllers de la API)
* Se desarrolla un pequeño frontend de demostración utilizando [vue](https://vuejs.org) + [axios](https://axios-http.com) + [vuetify](https://vuetifyjs.com/en/)
* Se escriben los tests de funcionalidades.
* Se realiza una documentación de la app. Y una documentación de la api con la especificación de los endpoints de la API.