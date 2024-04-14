# Foottrack
Miembros del grupo: Eric, Ricard y Jorge

github: https://github.com/EricMolina/Gincana

proyecto: https://github.com/users/EricMolina/projects/6

## Introducción
Este es nuestro proyecto de mapas para crear gincanas, desde esta aplicación se pueden hacer las siguientes cosas:
* Moverte por un mapa, por defecto el punto en el que se posiciona el mapa es la ubicación del usuario (para el correcto funionamiento de la página es necesario tener habilitado la ubicación en el navegador).
* Se pueden ver los puntos creados (el administrador es el encargado de la creación de los mismos y clasificación de los mismos)
* Los usuarios pueden crear sus propias categorías que solo podrán ser visto por ellos mismos.
* Creación de gincanas basandose en los puntos creados por defecto
* Los usuarios pueden crear sesiones de una gincana creada y formar grupos.

## Inicio de sesión

La pagina inicial consiste en un login en el que todos los usuarios pueden registrarse y probar la pagina web, al registrarte tu usuario será un usuario normal por lo que no tienes acceso a los CRUDs, eso pasa a ser funciones que solo los admins pueden hacer.

Credencales para iniciar sesión como admin:

* Usuarios: admin@gmail.com
* Contraseña: asdASD123@

![image](https://github.com/EricMolina/Gincana/assets/91189374/207bb674-ddc7-46cf-b5fd-d9c653a5c5f0)

![image](https://github.com/EricMolina/Gincana/assets/91189374/a9b126c6-b113-4bfb-9089-ea5b2985fcbe)

## Pantalla principal

Una vez iniciamos sesión en la pagina web accedemos al mapa principal, aquí podremos ver nuestra ubicación en el mapa, los puntos y las gincanas disponibles.

![image](https://github.com/EricMolina/Gincana/assets/91189374/ca13629c-0452-45bc-afa8-3ff910070215)

Por defecto la ubicación del mapa es nuestra posición actual y veremos los puntos definidos por el administrador, cambiando entre los botones cambiamos entre puntos y gincanas.


## Funcionamiento de las gincanas

Las gincanas son creadas por los usuarios, una vez se ven en el mapa los usuarios pueden hacer una sesión, de esta forma se podría hacer por ejemplo una sesión por la mañana y otra por la tarde. Al unirte a una sesión puedes buscar un grupo o crear uno.

Al iniciar la sesión todos los usuarios reciben una primera pista, es entonces cuando los miembros del grupo deben buscar el punto, no es hasta que todos los miembros del grupo están en el punto cuando se dará la siguiente pista del siguiente punto.

Gana el grupo que llegue al final.

### Creación de la gincana
Un usuario registrado puede crear una gincana el cual pide los siguientes datos:
* Nombre
* Descripción
* Ubicación general (por defecto es la ubicación del usuario)
* Puntos, los cuales tienen las siguientes caracteriticas:
    - Pueden ser todos los puntos que el usuario quiera.
    - La ubicación del punto debe ser una localización que ya exista en el mapa.
    - Debe existir una pista.
Una vez creada las gincanas se pueden ver en la sección de gincanas de la pagina principal.

![image](https://github.com/EricMolina/Gincana/assets/91189374/de4a8043-2dd3-4542-8134-b202aac1db15)


### Unirse a una gincana

Puedes unirte a una gincana dandole click en la ubicación, entonces veremos las sesiones creadas para esa gincana, puedes unirte o crear uno.

![image](https://github.com/EricMolina/Gincana/assets/91189374/24556d46-ff23-455a-95c7-861c2f8486de)

![image](https://github.com/EricMolina/Gincana/assets/91189374/911f6f96-3e0e-41cd-9027-b0c3934acdcc)

Entramos en una sesión y entonces podremos elegir entre los grupos ya creados para unirte a otros jugadores o crear el tuyo propio

Una vez unidos al grupo estamos listos para que el creador de la sesión la incie.

![image](https://github.com/EricMolina/Gincana/assets/91189374/aec7094c-a5c8-40b2-8807-1451ca8cf64a)

# CRUDS
Esta sección de la pagina a diferencia de la sección de usuarios está pensada para ser usada en un PC, por eso si la resolución no es correcta (por ejemplo con el movil en vertical) se muestra un mensaje avisando.

![image](https://github.com/EricMolina/Gincana/assets/91189374/7deb5d01-84e8-488a-8425-809f429ed96f)

Desde aquí los administradores pueden gestionar los siguientes recursos.

## Panel del administrador
Para la correcta gestión de la pagina web se ha creado un panel para gestionar los usuarios, puntos y las categorías principales (no incluye las personales de los usuarios) 

![image](https://github.com/EricMolina/Gincana/assets/91189374/6a840ad2-9828-41c3-b898-b27b26ec4dda)

![image](https://github.com/EricMolina/Gincana/assets/91189374/87ef8488-bfcd-429a-b349-cdd1a97cad53)

![image](https://github.com/EricMolina/Gincana/assets/91189374/c91ec834-cb96-4216-a81f-3a914172f35a)




