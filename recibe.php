<?php
session_start();
header('Content-Type:application/json');

$_SESSION['logged'] = true;
$ensayo = $_SESSION['ensayo'];

include 'conectionDB.php';

//echo "Recibiendo datos--->";

if(isset($_POST['Tension_ESPWS'])) {

  $data = array();

  $tension= strip_tags($_POST['value']);
  $_SESSION['tension'] = $tension;

  $actualiza = $conn->query("UPDATE `puente` SET `tension`='$tension',`status_v`='1',`fecha`= CURRENT_TIMESTAMP WHERE `id` = '1' ");
  
  if($actualiza === TRUE){
    $data['status'] = 'TRUE';
    if($_SESSION['corriente']!=0){
      $data['calcular'] = 'TRUE';
    }else{
      $data['calcular'] = 'FALSE';
    }
  }else{
    $data['status'] = 'FALSE';
    $data['error'] = 'Insert DB datos';
  }

  echo json_encode($data, JSON_FORCE_OBJECT);

}

if(isset($_POST['Corriente_ESPWS'])) {

  $data = array();

  $corriente= strip_tags($_POST['value']);
  $_SESSION['corriente'] = $corriente;

  $actualiza = $conn->query("UPDATE `puente` SET `corriente`='$corriente',`status_i`='1',`fecha`= CURRENT_TIMESTAMP WHERE `id` = '1' ");
  
  if($actualiza === TRUE){
    $data['status'] = 'TRUE';
    if($_SESSION['tension'] != 0){
      $data['calcular'] = 'TRUE';
      $_SESSION['calcular'] = 1;
    }else{
      $data['calcular'] = 'FALSE';
      $_SESSION['calcular'] = 0;
    }
  }else{
    $data['status'] = 'FALSE';
    $data['error'] = 'Insert DB datos';
  }

  echo json_encode($data, JSON_FORCE_OBJECT);

}

if(isset($_POST['esp_tension']) && isset($_POST['esp_corriente'])) {


  $tension= strip_tags($_POST['esp_tension']);
  $corriente = strip_tags($_POST['esp_corriente']);

  $actualiza = $conn->query("UPDATE `puente` SET `tension`='$tension',`corriente`='$corriente',`status_v`='1',`status_i`='1',`fecha`= CURRENT_TIMESTAMP WHERE `id` = '1' ");

  $dato_oa = 'true';
  $dato_mn = 'true';

  echo "tension:" . $tension . ", corriente: " . $corriente . ", status_v: " . $dato_oa . ", status_i " . $dato_mn;

}

if(isset($_POST['esp_tension']) && isset($_POST['esp_status_v'])) {


  $tension= strip_tags($_POST['esp_tension']);
  $status_v = strip_tags($_POST['esp_status_v']);

  $actualiza = $conn->query("UPDATE `puente` SET `tension`='$tension',`status_v`='$status_v',`fecha`= CURRENT_TIMESTAMP WHERE `id` = '1' ");

}

if(isset($_POST['esp_corriente']) && isset($_POST['esp_status_i'])) {


  $corriente= strip_tags($_POST['esp_corriente']);
  $status_i = strip_tags($_POST['esp_status_i']);

  $actualiza = $conn->query("UPDATE `puente` SET `corriente`='$corriente',`status_i`='$status_i',`fecha`= CURRENT_TIMESTAMP WHERE `id` = '1' ");

}

/////////Actualizacion de base de datos a partir de los datos cargados en la pagina
////////la funcion que dispara esta accion es la del boton calc
if(isset($_POST['ActualizaDB_Cal'])) {

  $_SESSION['tension'] = 0;
  $_SESSION['corriente'] = 0;
    $data = array();

    $dato_oa = strip_tags($_POST['db_OA']);
    $dato_k = strip_tags($_POST['db_K']);
    $dato_mn = strip_tags($_POST['db_MN']);
    $dato_ensayo = strip_tags($_POST['db_Ensayo']);
    $dato_modelo = strip_tags($_POST['db_Modelo']);
    $dato_v = strip_tags($_POST['db_tension']);
    $dato_i = strip_tags($_POST['db_corriente']);

    if($dato_i != 0 && $dato_v != 0){$resistividad = ($dato_v / $dato_i)*$dato_k;}
    else{$resistividad = 0;}


    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////// Base de datos del ensayo ////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    if($resistividad != 0 ){

      $result = $conn->query("SELECT * FROM `datos` WHERE `OA`='".$dato_oa."' AND `MN`='".$dato_mn."' AND `trabajo`='".$dato_ensayo."' AND `modelo`='".$dato_modelo."' ");
      $datos = $result->fetch_all(MYSQLI_ASSOC);
      $datos_num = count($datos);

      if($datos_num > 0){
        $actualiza = $conn->query("UPDATE `datos` SET `tension`='$dato_v',`corriente`='$dato_i',`OA`='$dato_oa',`MN`='$dato_mn',`K`='$dato_k',`resistividad`='$resistividad',`fecha`= CURRENT_TIMESTAMP WHERE `trabajo` = '".$dato_ensayo."' AND `OA`='".$dato_oa."' AND `MN`='".$dato_mn."' AND `modelo`='".$dato_modelo."' ");

        if($actualiza === TRUE){
            $data['status'] = true;
            $data['detalle'] = "Actualizamos con exito el dato en DB";
            ////////////LOGICA PARA CARGAR ARRAY PARA GRAFICAR/////////////////

            $result_d = $conn->query("SELECT * FROM `datos` WHERE `trabajo`='".$dato_ensayo."' AND `modelo`='".$dato_modelo."' ORDER BY `OA` ASC ");
            $datos_d = $result_d->fetch_all(MYSQLI_ASSOC);
            $datos_num_d = count($datos_d);

            $stringdata = '{"data":[';

              for ($i=0; $i < $datos_num_d ; $i++) {
                $stringdata .= '{';
                $stringdata .= '"x":';
                $stringdata .= $datos_d[$i]['OA'];
                $stringdata .= ',';
                $stringdata .= '"y":';
                $stringdata .= $datos_d[$i]['resistividad'];
                if($i != $datos_num_d-1 ){
                  $stringdata .= '},';
                }else{
                  $stringdata .= '}]}';
                }

              }
              $data['dato'] = $stringdata;

            ///////////////////////////////////////////////////////////
        }else{
            $data['status'] = false;
            $data['error'] = 'Update DB datos';
        }
      }else{
        //$insertar = $conn->query("INSERT INTO `Datos` SET `OA`='$dato_oa',`MN`='$dato_mn',`K`='$dato_k',`fecha`= CURRENT_TIMESTAMP WHERE `id` = '1' ");
        $insertar = $conn->query("INSERT INTO `datos`(`id`,`trabajo`,`modelo`,`tension`,`corriente`,`OA`,`MN`,`K`,`resistividad`,`fecha`) VALUES (NULL,'$dato_ensayo','$dato_modelo','$dato_v','$dato_i','$dato_oa','$dato_mn','$dato_k','$resistividad',CURRENT_TIMESTAMP)");

        if($insertar === TRUE){
            $data['status'] = true;
            $data['detalle'] = "Dato Insertado con Exito en DB";
            ////////////LOGICA PARA CARGAR ARRAY PARA GRAFICAR/////////////////

            $result_d = $conn->query("SELECT * FROM `datos` WHERE `trabajo`='".$dato_ensayo."' AND `modelo`='".$dato_modelo."' ORDER BY `OA` ASC ");
            $datos_d = $result_d->fetch_all(MYSQLI_ASSOC);
            $datos_num_d = count($datos_d);

            $stringdata = '{"data":[';

              for ($i=0; $i < $datos_num_d ; $i++) {
                $stringdata .= '{';
                $stringdata .= '"x":';
                $stringdata .= $datos_d[$i]['OA'];
                $stringdata .= ',';
                $stringdata .= '"y":';
                $stringdata .= $datos_d[$i]['resistividad'];
                if($i != $datos_num_d-1 ){
                  $stringdata .= '},';
                }else{
                  $stringdata .= '}]}';
                }

              }
              $data['dato'] = $stringdata;

            ///////////////////////////////////////////////////////////
        }else{
            $data['status'] = false;
            $data['error'] = 'Error al insertar dato en DB';
        }

      }

    }else {
        $data['status'] = false;
        $data['error'] = 'Error en ingreso de datos ( el valor de resistividad debe ser distinto de 0 )';
    }
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    echo json_encode($data, JSON_FORCE_OBJECT);



}
////////////////////////////////////////////////////////////////////////////////////////

if(isset($_POST['Cambio_Ensayo'])){

  $data = array();

  $_SESSION['ensayo'] = strip_tags($_POST['Cambio_Ensayo']);
  $_SESSION['modelo'] = strip_tags($_POST['Modelo_Ensayo']);
  $data['status'] = 'TRUE';
  $data['ensayo'] = $_SESSION['ensayo'];
  $data['modelo'] = $_SESSION['modelo'];

  echo json_encode($data, JSON_FORCE_OBJECT);

}

if(isset($_POST['Cambio_Modelo'])){

  $data = array();

  $_SESSION['ensayo'] = "Prueba";
  $_SESSION['modelo'] = strip_tags($_POST['Modelo_Ensayo']);
  $data['status'] = 'TRUE';
  $data['ensayo'] = $_SESSION['ensayo'];
  $data['modelo'] = $_SESSION['modelo'];

  echo json_encode($data, JSON_FORCE_OBJECT);

}

if(isset($_POST['Nuevo_Ensayo'])){
  $data = array();
  $Nombre_Ensayo = $_POST['Nombre_Ensayo'];
  $Modelo_Ensayo = $_POST['Modelo_Ensayo'];

  if($Nombre_Ensayo != ""){

    $result = $conn->query("SELECT * FROM `ensayo` WHERE `nombre`='".$Nombre_Ensayo."' AND `modelo`='".$Modelo_Ensayo."' AND `status`='1' ");
    $datos = $result->fetch_all(MYSQLI_ASSOC);
    $datos_num = count($datos);

    if ($datos_num > 0) {
      $data['status'] = 'FALSE';
      $data['error'] = 'Ensayo ya existe';
    }else{

      $insertar = $conn->query("INSERT INTO `ensayo`(`id`,`nombre`,`modelo`,`fecha`,`status`) VALUES (NULL,'$Nombre_Ensayo','$Modelo_Ensayo',CURRENT_TIMESTAMP,'1')");

      if($insertar === TRUE){
          $data['status'] = 'TRUE';
          //$data['detalle'] = $datos_num;
      }else{
          $data['status'] = 'FALSE';
          $data['error'] = 'Insert DB datos';
      }

    }

  }else {
    $data['status'] = 'FALSE';
    $data['error'] = 'Ensayo no cargado';
  }

  echo json_encode($data, JSON_FORCE_OBJECT);

}

if(isset($_POST['Calcular_Iniciales'])){
  
  $data = array();

  $ensayo = strip_tags($_POST['Ensayo']);
  $modelo = strip_tags($_POST['Modelo_Datos']);
  $nlayers = strip_tags($_POST['nlayers']);

  $output = Calcular_Valores_Iniciales($ensayo, $nlayers, $modelo);

  $data['status'] = True;
  $data['resultados'] = $output;

  echo json_encode($data, JSON_FORCE_OBJECT);

}


if(isset($_GET['data']) ) {

  echo "Hola " . $_SESSION['user'] . " tu contraseña es: " . $_SESSION['pass'];

}

if(isset($_POST['Eliminar_Ensayo'])){

  $data = array();
  $Nombre_Ensayo = $_POST['Nombre_Ensayo'];
  $Modelo_Ensayo = $_POST['Modelo_Ensayo'];

  if($Nombre_Ensayo != ""){
    ///*
    $result = $conn->query("SELECT * FROM `ensayo` WHERE `nombre`='".$Nombre_Ensayo."' AND `modelo`='".$Modelo_Ensayo."' AND `status`='1' ");
    $datos = $result->fetch_all(MYSQLI_ASSOC);
    $datos_num = count($datos);

    if ($datos_num == 0) {
      //no exite el ensayo que queremos eliminar
      $data['status'] = 'FALSE';
      $data['error'] = 'Ensayo no existe en DB. No podemos elimiar';
    }else{

      $delet = $conn->query("UPDATE `ensayo` SET `nombre`='$Nombre_Ensayo',`fecha`=CURRENT_TIMESTAMP,`status`='0' WHERE `nombre`='".$Nombre_Ensayo."' AND `modelo`='".$Modelo_Ensayo."' AND `status`='1' ");

      if($delet === TRUE){
          $data['status'] = 'TRUE';
          $_SESSION['ensayo'] = 'Prueba';
          //$data['detalle'] = $datos_num;
      }else{
          $data['status'] = 'FALSE';
          $data['error'] = 'Delet DB Ensayo';
      }

    }
    //*/
  }else {
    $data['status'] = 'FALSE';
    $data['error'] = 'Ensayo no cargado';
  }

  echo json_encode($data, JSON_FORCE_OBJECT);

}

///*
if(isset($_POST['Exportar_Datos'])){

  $data = array();
  $Nombre_Ensayo = $_POST['Nombre_Ensayo'];
  $Modelo_Datos = $_POST['Modelo_Datos'];

  if($Nombre_Ensayo != ""){
    //$data['status'] = 'TRUE';
    //$data['detalle'] = $Nombre_Ensayo;
    $data = Exportar_Datos($Nombre_Ensayo,$Modelo_Datos);
  }else{
    $data['status'] = 'FALSE';
    $data['error'] = 'Ensayo no cargado';
  }

  echo json_encode($data, JSON_FORCE_OBJECT);
}

function Exportar_Datos($Nombre_Ensayo,$Modelo_Datos){

  include 'conectionDB.php';
  $data = array();
  $fecha_actual = date("d-m-Y");
  //$stringinicial = "id,trabajo,modelo,tension,corriente,OA,MN,K,resistividad,fecha"."\n";

  $file_name ='archivos/SEV_' . $Nombre_Ensayo .'_' . $Modelo_Datos . '_' . $fecha_actual . '.csv';

    $result = $conn->query("SELECT * FROM `datos` WHERE `trabajo`='".$Nombre_Ensayo."' AND `modelo`='".$Modelo_Datos."' ORDER BY `OA` ASC ");
    $datos = $result->fetch_all(MYSQLI_ASSOC);
    $datos_num = count($datos);

    if($result == TRUE){
      ///*
      if($datos_num>0){

        ///*
        $f=fopen($file_name,"w+");

        if($f){
          fputs($f,$stringdata);
          for ($i=0; $i < $datos_num ; $i++) {
            //$stringdata = $datos[$i]['id'] . "," . $datos[$i]['trabajo'] . "," . $datos[$i]['modelo'] . "," . $datos[$i]['tension'] . "," . $datos[$i]['corriente'] .",". $datos[$i]['OA'] . "," . $datos[$i]['MN'] .",". $datos[$i]['resistividad'] .",". $datos[$i]['fecha'] . "\n";
            // Envolver cada valor en comillas y construir la cadena
            $id_value = 'NULL';
            // Envolver cada valor en comillas y construir la cadena
            $stringdata = $id_value . ',' . implode(",", array_map(function($value) {
                return '"' . $value . '"';
            }, [
                $datos[$i]['trabajo'],
                $datos[$i]['modelo'],
                $datos[$i]['tension'],
                $datos[$i]['corriente'],
                $datos[$i]['OA'],
                $datos[$i]['MN'],
                $datos[$i]['K'],
                $datos[$i]['resistividad'],
                $datos[$i]['fecha']
            ])) . "\n";
            fputs($f,$stringdata);
          }
          fclose($f);
          $data['status'] = 'TRUE';
          $data['detalle'] = $Nombre_Ensayo;
          $data['file'] = $file_name;
        }else{
          //error fopen
          $data['status'] = 'FALSE';
          $data['error'] = 'Error al crear archivo. '. $file_name;
          //$data['error'] = $file_name;
        }
        //*/

      }else{
          $data['status'] = 'FALSE';
          $data['error'] = 'Error. No se econtraron datos para exportar';
      }
      //*/
    }else{
      $data['status'] = 'FALSE';
      $data['error'] = 'Error al consultar con DB Datos';
    }

    return $data;
}

function Formatear_Data_Para_Graficar($datos,$modelo) {

  $datos_num = count($datos);
  
  $stringdata = '{"data":[';

  for ($i=0; $i < $datos_num ; $i++) {
    $stringdata .= '{';
    $stringdata .= '"x":';
    if($modelo == "Schlumberger"){$stringdata .= $datos[$i]['OA'];}
    if($modelo == "Wenner"){$stringdata .= $datos[$i]['MN'];}
    $stringdata .= ',';
    $stringdata .= '"y":';
    $stringdata .= $datos[$i]['resistividad'];
    if($i != $datos_num-1 ){
      $stringdata .= '},';
    }else{
      $stringdata .= '}]}';
    }

  }

  return $stringdata;
}

function Pull_Data_From_DataBase($ensayo, $nlayers, $modelo) {

  include 'conectionDB.php';

  // query data
  $result = $conn->query("SELECT * FROM `datos` WHERE `trabajo`='".$ensayo."' AND `modelo`='".$modelo."' ORDER BY `OA` ASC  ");
  $data_raw = $result->fetch_all(MYSQLI_ASSOC);
  $datos_num = count($data_raw);

  // prepare json with input data
  $x = array();
  $y = array();
  for ($i=0; $i < $datos_num ; $i++) {
        $xidx = array_push($x, floatval($data_raw[$i]['OA']));
        $yidx = array_push($y, floatval($data_raw[$i]['resistividad']));
  };
  $data_proc = [
    "nlayers" => $nlayers,
    "OA" => $x,
    "R" => $y,
  ];
  $output = [
    "data_proc" => $data_proc,
    "data_raw" => $data_raw,
  ];

  return $output;
}

function Define_Python_Commands($keyword) {

  // for linux
  try {
    $command = escapeshellcmd("uname");
    $output = shell_exec($command);
    if (strpos($output, "Linux") !== false) {
      $username = getenv("SUDO_USER");
      $python_interp = "/home/".$username."/anaconda3/bin/python";
      $package_path = "/opt/lampp/htdocs/SEV_1000_WS";
      if ($keyword == "init_values") {
        $python_file = $package_path."/html/python/compute_init_layers_backend.py";
      }
      if ($keyword == "fit_values") {
        $python_file = $package_path."/html/python/fit_VES_backend.py";
      }
    }
  } catch (Exception $e) { 
    print_r("linux". $e);
  }

  // for windows
  try {
    $command = escapeshellcmd("ver");
    $output = shell_exec($command);
    if (strpos($output, "Microsoft") !== false) {
      $python_interp = "C:\\Users\\sev10\\AppData\\Local\\Microsoft\\WindowsApps\\python.exe";
      $package_path = "C:\\xampp\\htdocs\\SEV_1000_WS";
      if ($keyword == "init_values") {
        $python_file = $package_path."\\html\\python\\compute_init_layers_backend.py";
    }
      if ($keyword == "fit_values") {
        $python_file = $package_path."\\html\\python\\fit_VES_backend.py";
    }
    }
  } catch (Exception $e) { 
    print_r("windows". $e);
  }

  return $python_interp." ".$python_file." ";
}

function Calcular_Valores_Iniciales($ensayo, $nlayers, $modelo){

  // compute initial values
  $database = Pull_Data_From_DataBase($ensayo, $nlayers, $modelo);
  $data_proc = $database['data_proc'];
  $arguments = escapeshellarg(json_encode($data_proc));
  $shellcomand = Define_Python_Commands("init_values");

  $output = shell_exec($shellcomand.$arguments);
  if (strpos($output, "failed python") !== false) {
    return "python failed: " . $output;
  };

  return $output;
}

function Calcular_Ajuste($data_proc, $nlayers, $rho0, $thick0, $checkR, $checkP){

  // calcular ajuste
  $shellcomand = Define_Python_Commands("fit_values");
  $command = escapeshellcmd($shellcomand);
  $data = [
    "nlayers" => $data_proc["nlayers"],
    "checkR" => $checkR,
    "checkP" => $checkP,
    "OA" => $data_proc["OA"],
    "R" => $data_proc["R"],
    "rho0" => array($rho0),
    "thick0" => array($thick0),
  ];
  $arguments = escapeshellarg(json_encode($data));

  $output = shell_exec($shellcomand.$arguments);

  if (strpos($output, "failed python") !== false) {
    return "python failed: " . $output;
  };

  return $output;
}

//*/

if(isset($_POST['EliminarDato'])){

  $data = array();

  $dato_oa = strip_tags($_POST['OA']);
  $dato_mn = strip_tags($_POST['MN']);
  $dato_ensayo = strip_tags($_POST['Ensayo']);
  $dato_modelo = strip_tags($_POST['Modelo']);

  $result = $conn->query("SELECT * FROM `datos` WHERE `OA`='".$dato_oa."' AND `MN`='".$dato_mn."' AND `trabajo`='".$dato_ensayo."' AND `modelo`='".$dato_modelo."' ");
  $datos = $result->fetch_all(MYSQLI_ASSOC);
  $datos_num = count($datos);

  if($datos_num > 0){
    
    $borrar = $conn->query("DELETE FROM `datos` WHERE `OA`='".$dato_oa."' AND `MN`='".$dato_mn."' AND `trabajo`='".$dato_ensayo."' AND `modelo`='".$dato_modelo."' ");
    if($borrar === TRUE){
      $data['status'] = 'TRUE';
      $data['detalle'] = 'Dato Eliminado con exito';
    }else{
        $data['status'] = 'FALSE';
        $data['error'] = 'Error al eliminar Dato de DB';
    }
  }
  else{
    //no existe el dato a eliminar
    $data['status'] = 'FALSE';
    $data['error'] = 'Dato no encontrado en base de datos';
  }
  echo json_encode($data, JSON_FORCE_OBJECT);
}

if(isset($_POST['Ajustar'])){

  // get data from frontend
  $ensayo = strip_tags($_POST['Ensayo']);
  $modelo = strip_tags($_POST['Modelo']);
  $nlayers = strip_tags($_POST['nlayers']);
  $checkR = strip_tags($_POST['checkR']);
  $checkP = strip_tags($_POST['checkP']);
  $rho0_string = strip_tags($_POST['Rho0']);
  $thick0_string = strip_tags($_POST['Thick0']);
  $rho0 = explode(",", $rho0_string); 
  $thick0 = explode(",", $thick0_string); 

  ////////////////////////////////////////////////////////////////////////////////////
  $database = Pull_Data_From_DataBase($ensayo, $nlayers, $modelo);
  $data_raw = $database['data_raw'];
  $stringdata = Formatear_Data_Para_Graficar($data_raw,$modelo);
  $data_proc = $database['data_proc'];
  $output = Calcular_Ajuste($data_proc, $nlayers, $rho0, $thick0, $checkR, $checkP);

  $data = array();
  if(strpos($output, "failed python") !== false){
    $data['status'] = 'FALSE';
    $data['error'] = $output;
  } else{
    $data['status'] = 'TRUE';
    $data['detalle'] = 'Dato Ajustar recibido. Ensayo:' . $ensayo . ' Capas:' . $nlayers . ' checkR:' . $checkR . ' checkP:' . $checkP;
    $data['detalle'] .= " Resistividades Iniciales: " . implode(", ", $rho0) . " Thick: " . implode(", ", $thick0);
    $data['detalle'] .= " Resultados: " . $output;
    $data['resultados'] = $output;
    $data['dato'] = $stringdata;
  }

  echo json_encode($data, JSON_FORCE_OBJECT);

}

if(isset($_POST['CambiaCapas'])){

  $data = array();

  $ensayo = strip_tags($_POST['Ensayo']);
  $modelo = strip_tags($_POST['Modelo']);
  $nlayers = strip_tags($_POST['nlayers']);
  $checkR = strip_tags($_POST['checkR']);
  $checkP = strip_tags($_POST['checkP']);

  //////aqui falta agregar la variable modelo
  $output = Calcular_Valores_Iniciales($ensayo, $nlayers, $modelo);

  $data['status'] = 'TRUE';
  $data['detalle'] = 'Dato recibido. Ensayo:' . $ensayo . ' Capas:' . $nlayers . ' checkR:' . $checkR . ' checkP:' . $checkP;
  // $data['detalle'] .= " Resultados: nlayers = " . $nlayers;
  // $data['detalle'] .= ", rho0 = ". implode(", ",$output['rho0']);
  // $data['detalle'] .= ", thick0 = ". implode(",",$output['thick0']);
  $data['resultados'] = $output;

  echo json_encode($data, JSON_FORCE_OBJECT);
  
}

if(isset($_POST['Data_Ensayo'])){
  // get data from frontend
  $ensayo = strip_tags($_POST['Nombre_Ensayo']);
  $modelo = strip_tags($_POST['Modelo_Datos']);
  $nlayers = 3;

  $database = Pull_Data_From_DataBase($ensayo, $nlayers, $modelo);
  $data_raw = $database['data_raw'];
  $stringdata = Formatear_Data_Para_Graficar($data_raw,$modelo);
  
  

  $data['status'] = true;
  $data['dato'] = $stringdata;
  echo json_encode($data, JSON_FORCE_OBJECT);

}

if( isset($_POST['Subir_Archivo']) ) {
  $data = array();
  $data['status']=false;
  $data['data'] = "";
  $data['error'] = "Error al subir archivo.\n";
  $data['files'] = "";
  $data['files_cont'] = 0;
  $data['error_cont'] = 0;
  $maxFileSize = (double)(8 * 1024 * 1024); // 8 MB en bytes

  if($_FILES['archivo']['error'] === UPLOAD_ERR_OK){
    $fileName = $_FILES['archivo']['name'];
    $fileType = $_FILES['archivo']['type'];
    $fileError= $_FILES['archivo']['error'];
    $fileMaxSize = (double)$_FILES['archivo']['size'];
    $fileNameCmps = explode(".", $fileName);
    $fileExtension = strtolower(end($fileNameCmps));
    $extension = $fileExtension;

    $allowedfileExtensions = array('csv', 'xlsx');

    if (in_array($fileExtension, $allowedfileExtensions)) {

      $data['status'] = true;
      $data['data'] = "Archivo subido de forma correcta\n";
  
      // Ruta temporal del archivo cargado
      $tmpFilePath = $_FILES['archivo']['tmp_name'];
      
      if ($fileExtension === 'csv') {
          $file = fopen($tmpFilePath, 'r');
          $csvDataArray = array();
          
          // Leer cada línea del archivo CSV
          while (($row = fgetcsv($file, 1000, ",")) !== FALSE) {
              if (count($row) > 1) {
                  $csvDataArray[] = $row;
              }
          }
          fclose($file);
  
          // Añadir el array CSV al array de respuesta
          $data['csv_array'] = $csvDataArray;
          $insertResult = insertIntoDatabase($csvDataArray, $conn);

            // Actualizar el estado según el resultado de la inserción
            $data['status'] = $insertResult['status'];
            $data['data'] .= $insertResult['message'];
            $data['error'] .= $insertResult['error'];


      }

      /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    }else{
      $data['status']=false;
      $data['error'] .= "Extensión Incorrecta";
    }


  }else{
    
    $data['status']=false;

    if ($_FILES['archivo']['error']=="1") {//El fichero subido excede la directiva upload_max_filesize de php.ini.
      $data['error'] .= "El archivo es más grande de lo permitido (8Mb)";
    }
    if ($_FILES['archivo']['error']=="2") {//El fichero subido excede la directiva MAX_FILE_SIZE especificada en el formulario HTML.
      $data['error'] .= "El archivo es más grande de lo permitido (8Mb)";
    }
    if ($_FILES['archivo']['error']=="4") {//No se subió ningún fichero.
      $data['errror'] .= "No cargo ningun archivo";
    }

  }


  echo json_encode($data, JSON_FORCE_OBJECT);

}

function insertIntoDatabase($csvDataArray, $conn) {
  // Inicializa la respuesta de estado
  $data = array();
  $data['status'] = false;  // Por defecto asumimos que puede fallar
  $data['message'] = "";  // Mensajes informativos
  $data['error'] = "Error: ";
  $errorCount = 0;

  $Nombre_Ensayo = $csvDataArray[0][1]; // Primera fila (índice 0), columna 1 (índice 1)
  $Modelo_Ensayo = $csvDataArray[0][2];

  if($Nombre_Ensayo != ""){
    ///*
    $result = $conn->query("SELECT * FROM `ensayo` WHERE `nombre`='".$Nombre_Ensayo."' AND `modelo`='".$Modelo_Ensayo."' ");
    $datos = $result->fetch_all(MYSQLI_ASSOC);
    $datos_num = count($datos);

    if ($datos_num == 0) {
      //no exite el ensayo podemos agregar el nuevo ensayo
      $insert = $conn->query("INSERT INTO `ensayo`(`id`, `nombre`, `modelo`, `fecha`, `status`) VALUES (NULL,'$Nombre_Ensayo','$Modelo_Ensayo',CURRENT_TIMESTAMP,'1')");

      if($insert === TRUE){
          $data['status'] = true;
          //$data['detalle'] = $datos_num;
      }else{
          $data['status'] = false;
          $data['error'] .= 'Insert DB Ensayo';
          $errorCount++;
      }

    }else{

      $data['status'] = false;
      $data['error'] .= 'Ensayo ya EXISTE en DB. No podemos agregar';
      $errorCount++;


    }
    //*/
  }else {
    $data['status'] = false;
    $data['error'] .= 'Error en el nombre de Ensayo';
    $errorCount++;
  }

  if( $data['status'] == true ){
      // Prepara la declaración SQL
      $stmt = $conn->prepare("INSERT INTO datos (trabajo, modelo, tension, corriente, OA, MN, K, resistividad, fecha) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

      // Recorre cada fila del array
      foreach ($csvDataArray as $index => $row) {
          //echo "Fila $index tiene " . count($row) . " columnas: ";
          //print_r($row); // Mostrar el contenido de la fila
          // Asegúrate de que la cantidad de columnas coincida con la tabla
          if (count($row) == 10) { // Asumiendo que tienes 9 columnas en tu CSV
              // Asigna los valores a variables
              $trabajo = $row[1];  // Cambia el índice si es necesario
              $modelo = $row[2];   // Cambia el índice si es necesario
              $tension = (float)$row[3];
              $corriente = (float)$row[4];
              $OA = (float)$row[5];
              $MN = (int)$row[6];
              $K = (float)$row[7];
              $resistividad = (float)$row[8];
                // Verifica y convierte la fecha
              try {
                $fechaObj = new DateTime($row[9]);  // Convierte la fecha
                $fecha = $fechaObj->format('Y-m-d H:i:s');  // Formato MySQL
              } catch (Exception $e) {
                  $data['error'] .= "Error en la conversión de fecha en la fila $index: " . $e->getMessage() ."\n";
                  $errorCount++;
                  continue;  // Saltar esta fila si la fecha es incorrecta
              }
              // Vincula los parámetros
              $stmt->bind_param("ssddiddds", $trabajo, $modelo, $tension, $corriente, $OA, $MN, $K, $resistividad, $fecha);

              // Ejecuta la declaración
              if (!$stmt->execute()) {
                  $data['error'] .= "Error en la inserción de la fila $index: " . $stmt->error . "\n";
                  $errorCount++;
              }
          } else {
              $data['error'] .= "Error: Fila $index tiene datos incompletos\n";
              $errorCount++;
          }
      }
      // Cierra la declaración
      $stmt->close();
  }
  // Verifica si hubo errores
  if ($errorCount == 0) {
    $data['status'] = true;
    $data['message'] = "Inserción exitosa de todas filas";
  }else{
    $data['status'] = false;
  }

  return $data;  // Devuelve el array con el estado y mensajes

}

?>
