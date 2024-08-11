# SEW_1000_WS
Pagina WEB que controla el equipo de SEV


## Instrucciones para instalar Python

1. Instalar Python en un sistema operativo Windows con Microsoft Store:

   - Ir al menú Inicio (icono de Windows de la esquina inferior izquierda), escribir "Microsoft Store" y seleccionar el vínculo para abrir Store.

    - Una vez que abierto el Store, seleccionar Buscar en el menú superior derecho y escribir "Python". Seleccionar la versión de Python a instalar en los resultados de la opción Aplicaciones. Se recomienda usar la más reciente. Una vez definida la versión a instalar, seleccionar **Obtener**.

    - Una vez que Python haya completado el proceso de descarga e instalación, abrir la consola de Windows (PowerShell) mediante el menú Inicio (icono de Windows de la esquina inferior izquierda). Cuando la consola esté abierta, escribir:
      ```console
      > python --version 
      ```

      para confirmar que Python3 está instalado en la máquina.

    - La instalación de Microsoft Store de Python incluye PIP, el administrador de paquetes estándar. PIP permite instalar y administrar paquetes adicionales que no forman parte de la biblioteca estándar de Python. Para confirmar que PIP ha sido instalado correctamente, escribir en la consola:
      ```console
      > pip --version.
      ```

   - Para el uso correcto de este código, instalar los siguientes paquetes numpy, pandas, y scipy. En la consola de comandos tipear:
      ```console
      > pip install numpy pandas scipy
      ```

2. En sistema operativo Linux:
   - Se recomienda el uso de Anaconda para instalar Python. Para más detalles de instación de Anaconda, ver su [sitio web](https://docs.anaconda.com/anaconda/install/).
   - Para confirmar que PIP ha sido instalado correctamente, activar el environment ``base``
      ```console
      (base) $ conda activate
      ```

     y escribir en consola.
      ```console
      (base) $ pip --version
      ```

   - Para el uso correcto de este código, instalar los siguientes paquetes numpy, pandas, y scipy. En la consola (asegurarse que el environment ``base`` haya sido activado) de comandos tipear:
      ```console
      (base) $ pip install numpy pandas scipy
      ```

## Configurar path a ejecutable de Python

Este código requiere configurar manualmente el path al ejecutable de Python instalado, el cual se denomina `python_interp`. Para saber cual es el path correcto al ejecutable, en la consola escribir:
- En Windows:
  ```console
  > where python
  ```

  El resultado de este comando debería tener la forma:
  ```console
  > C:\Python3.11\python.exe"
  ```

  Una vez definido el path al ejecutable de Python, modificar la variable `python_interp` (de tipo string) en el archivo `recibe.php` (actualmente, línea 493):
  ```php
  489    // for windows
  490    try {
  491      $command = escapeshellcmd("ver");
  492      $output = shell_exec($command);
  493      if (strpos($output, "Microsoft") !== false) {
  494        $python_interp = "C:\\Python3.11\\python.exe";
  ```
  Notar que al ser una variable de tipo string, la barra inclinada a la izquierda debe ser ingresada de forma doble.

- Linux:
  El código no necesita ser modificado ya que asume que se ha instalado Python usando anaconda. El path es determinado automáticamente. Ver detalles en `recibe.php`:
  ```php
  469    // for linux
  470    try {
  471      $command = escapeshellcmd("uname");
  472      $output = shell_exec($command);
  473      if (strpos($output, "Linux") !== false) {
  474        $username = getenv("SUDO_USER");
  475        $python_interp = "/home/".$username."/anaconda3/bin/python";
  ```