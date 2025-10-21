Version 1.0 raspeberry pi
    Tareas Realizadas:
    * En esta version de cambio los links que contenian "localhost" para mejorar el acceso desde fuera del servidor.
    * Se agrego un archio de control de versiones.
    Tareas Pendientes:
    * En los achivos vincualdos con el ajuste del calculo, debemos cambiar los link de "localhost"

Version 1.1 raspberry pi5
    Tareas Realizadas:
    * Se acomodaron los links para que puedan acceder desde afuera.
    * Se eliminaron los login de tal manera que no se soliciten

Version 1.2 raspberry pi5
    Tareas Realizadas:
    * Se corrigio el tema de que no se podia acceder a las paginas desde la barra lateral izquierda, cuando el panel izquierdo no estaba desplegado (esto sucedia en tablet y telefono con pantallas pequeñas).
    * Se volvio a incluir el tema de los links segun en que pagina estemos y dejamos de usar "localhost" o una "ip-local" para acceder a determinados lugares.
    ***Se resolvio el tema del redireccionamiento de paginas en barra lateral izquierda

Version 1.23 Raspberry pi5
    Tareas Realizadas:
    * Volvimos a la version antes de tratar de ignorar los cambios que realizemos en conectionDB.php por gitignore


Version 1.24 Raspberry pi5
    Tareas Realizadas:
    * Ee dieron privilegios 777 a todos los archivos del proyecto.
    * BarraIzquierda.php:
        * Se modifico la dimension para que entre en tablet
        * Se elimino la parte baja donde se mostraba una foto del usuario
    * Se agrego el archivo mqtt.min.js para manejar la conexion mqtt
    * Myscripts_Ws.js:
        * Se elimino la conexion websocket
        * Se moficaron las propiedades de los ejes del grafico, ahora el logaritmo esta en proporcion
    * ensayo.php
        * Se modifico las dimensiones del box principal para que se visualice mejor en la tablet
        * Se elimino lo referente a websocket
        * Se agrego configuracion y conexion a mqtt.
        * Se agrego logica de conection mqtt
    
Version 1.25 Raspberry pi5
    Tareas Realizadas:
    * READMY.md:
        * Se modifico las indicaciones para la instalacion de python y sus componentes para que funciones raspberry pi5
    * Myscripts_Ws.js:
        * Se modifico para que el eje logaritmico "y" se visualicen los valores multiplos de 1 2 y 5
    * Ajunste.js
        * Se arreglaron las escalas y visualizacion de los ejes del grafico
        * Al hacer cambio de capas se omitio un cartel alert

Version 1.26 Raspberry pi5
    Tareas Realizadas:
    * ensayo.php:
        * Se establecio comunicacion mqtt y envio y recepcion de los mensajes

Version 1.27 Raspberry pi5  20-10-25
    Tareas Realizadas:
        * SEV_Manual_Usuario.php: 
            - Se actualizo el manual de usuario a una version mas reciente
        * Ajustes.php, GraficScript.js, Myscripts_Ws.js:
            - Se mejoro la manera en que se visualizan los ejes del grafico.
        * linkPage.js:
            - Se corrigio el link en el que se visualiza el manual, ahora se tiene en cuenta la posicion.

Version 1.28 Raspberry pi5  21-10-25
    Tareas Realizadas:
        * ensayo.php: 
            - Se corrigio para que la tabla sea responsiva.
        * Myscripts_Ws.js:
            - Se agrego linea para que la tabla inicialice con el formato de paginas de 5 filas
        * Ajuste.js, GraficScript.js:
            - Se eliminaron los maximos en el eje Y del grafico, para que el mismo busque solo su limite

  