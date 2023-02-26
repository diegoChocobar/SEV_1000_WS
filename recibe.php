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
  }else{
    $data['status'] = 'FALSE';
    $data['error'] = 'Insert DB datos';
  }

  echo json_encode($data, JSON_FORCE_OBJECT);

}

if(isset($_POST['Corriente_ESPWS'])) {

  $data = array();

  $corriente= strip_tags($_POST['value']);

  $actualiza = $conn->query("UPDATE `puente` SET `corriente`='$corriente',`status_i`='1',`fecha`= CURRENT_TIMESTAMP WHERE `id` = '1' ");
  
  if($actualiza === TRUE){
    $data['status'] = 'TRUE';
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

    $data = array();

    $dato_oa = strip_tags($_POST['db_OA']);
    $dato_k = strip_tags($_POST['db_K']);
    $dato_mn = strip_tags($_POST['db_MN']);
    $dato_ensayo = strip_tags($_POST['db_Ensayo']);
    $dato_v = strip_tags($_POST['db_tension']);
    $dato_i = strip_tags($_POST['db_corriente']);

    if($dato_i != 0 && $dato_v != 0){$resistividad = ($dato_v / $dato_i)*$dato_k;}
    else{$resistividad = 0;}


    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////// Base de datos del ensayo ////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    if($resistividad != 0 ){

      $result = $conn->query("SELECT * FROM `datos` WHERE `OA`='".$dato_oa."' AND `MN`='".$dato_mn."' AND `trabajo`='".$dato_ensayo."' ");
      $datos = $result->fetch_all(MYSQLI_ASSOC);
      $datos_num = count($datos);

      if($datos_num > 0){
        $actualiza = $conn->query("UPDATE `datos` SET `tension`='$dato_v',`corriente`='$dato_i',`OA`='$dato_oa',`MN`='$dato_mn',`K`='$dato_k',`resistividad`='$resistividad',`fecha`= CURRENT_TIMESTAMP WHERE `trabajo` = '".$dato_ensayo."' AND `OA`='".$dato_oa."' AND `MN`='".$dato_mn."' ");

        if($actualiza === TRUE){
            $data['status'] = true;
            $data['detalle'] = "Actualizamos con exito el dato en DB";
            ////////////LOGICA PARA CARGAR ARRAY PARA GRAFICAR/////////////////

            $result_d = $conn->query("SELECT * FROM `datos` WHERE `trabajo`='".$dato_ensayo."' ORDER BY `OA` ASC ");
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
        $insertar = $conn->query("INSERT INTO `datos`(`id`,`trabajo`,`tension`,`corriente`,`OA`,`MN`,`K`,`resistividad`,`fecha`) VALUES (NULL,'$dato_ensayo','$dato_v','$dato_i','$dato_oa','$dato_mn','$dato_k','$resistividad',CURRENT_TIMESTAMP)");

        if($insertar === TRUE){
            $data['status'] = true;
            $data['detalle'] = "Dato Insertado con Exito en DB";
            ////////////LOGICA PARA CARGAR ARRAY PARA GRAFICAR/////////////////

            $result_d = $conn->query("SELECT * FROM `datos` WHERE `trabajo`='".$dato_ensayo."' ORDER BY `OA` ASC ");
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
  $data['status'] = 'TRUE';
  $data['ensayo'] = $_SESSION['ensayo'];

  echo json_encode($data, JSON_FORCE_OBJECT);

}

if(isset($_POST['Nuevo_Ensayo'])){
  $data = array();
  $Nombre_Ensayo = $_POST['Nombre_Ensayo'];

  if($Nombre_Ensayo != ""){

    $result = $conn->query("SELECT * FROM `ensayo` WHERE `nombre`='".$Nombre_Ensayo."' AND `nombre`='1' ");
    $datos = $result->fetch_all(MYSQLI_ASSOC);
    $datos_num = count($datos);

    if ($datos_num > 0) {
      $data['status'] = 'FALSE';
      $data['error'] = 'Ensayo ya existe';
    }else{

      $insertar = $conn->query("INSERT INTO `ensayo`(`id`,`nombre`,`fecha`,`status`) VALUES (NULL,'$Nombre_Ensayo',CURRENT_TIMESTAMP,'1')");

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

if(isset($_POST['Data_Ensayo'])){

  $data = array();
  $Nombre_Ensayo = $_POST['Nombre_Ensayo'];
  //$array_x = array('2.5','3.2','4','5','6.5');
  //$array_y = array('42.33','38.66','36.77','38.01','43.55');
  //$num_array = count($array_x);

  $stringdata = Formatear_Data_Para_Graficar($Nombre_Ensayo);

  if($Nombre_Ensayo != ""){
    $data['status'] = TRUE;
    //$data['dato'] = '{"data":[{"x":2.5,"y":42.33},{"x":3.2,"y":38.66},{"x":4,"y":36.77},{"x":5,"y":38.01},{"x":6.55,"y":43.55},{"x":8,"y":50.63},{"x":10,"y":57.96},{"x":13,"y":66.80},{"x":16,"y":71.98},{"x":20,"y":74.96},{"x":25,"y":76.0},{"x":32,"y":68.34},{"x":40,"y":59.11},{"x":50,"y":49.35}]}';
    $data['dato'] = $stringdata;
  }else {
    $data['status'] = FALSE;
    $data['error'] = 'Datos de Ensayo no cargado';
    $data['dato'] ='';
  }

  echo json_encode($data, JSON_FORCE_OBJECT);

}

if(isset($_POST['Calcular_Iniciales'])){
  
  $data = array();

  $ensayo = strip_tags($_POST['Ensayo']);
  $nlayers = strip_tags($_POST['nlayers']);

  $output = Calcular_Valores_Iniciales($ensayo, $nlayers);

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

  if($Nombre_Ensayo != ""){
    ///*
    $result = $conn->query("SELECT * FROM `ensayo` WHERE `nombre`='".$Nombre_Ensayo."' AND `status`='1' ");
    $datos = $result->fetch_all(MYSQLI_ASSOC);
    $datos_num = count($datos);

    if ($datos_num == 0) {
      //no exite el ensayo que queremos eliminar
      $data['status'] = 'FALSE';
      $data['error'] = 'Ensayo no existe en DB. No podemos elimiar';
    }else{

      $delet = $conn->query("UPDATE `ensayo` SET `nombre`='$Nombre_Ensayo',`fecha`=CURRENT_TIMESTAMP,`status`='0' WHERE `nombre`='".$Nombre_Ensayo."' AND `status`='1' ");

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

  if($Nombre_Ensayo != ""){
    //$data['status'] = 'TRUE';
    //$data['detalle'] = $Nombre_Ensayo;
    $data = Exportar_Datos($Nombre_Ensayo);
  }else{
    $data['status'] = 'FALSE';
    $data['error'] = 'Ensayo no cargado';
  }

  echo json_encode($data, JSON_FORCE_OBJECT);
}

function Exportar_Datos($Nombre_Ensayo){

  include 'conectionDB.php';
  $data = array();
  $fecha_actual = date("d-m-Y");
  $stringdata = "OA;MN;V;I;R"."\n";

  $file_name ='archivos/SEV_' . $Nombre_Ensayo .'_'. $fecha_actual . '.txt';

    $result = $conn->query("SELECT * FROM `datos` WHERE `trabajo`='".$Nombre_Ensayo."' ORDER BY `OA` ASC ");
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
            $stringdata = $datos[$i]['OA'] . ";" .$datos[$i]['MN'] . ";" . $datos[$i]['tension'] . ";" . $datos[$i]['corriente'] . ";". $datos[$i]['resistividad'] . "\n";
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

function Formatear_Data_Para_Graficar($ensayo) {

  include 'conectionDB.php';
  
  $result = $conn->query("SELECT * FROM `datos` WHERE `trabajo`='".$ensayo."' ORDER BY `OA` ASC ");
  $datos = $result->fetch_all(MYSQLI_ASSOC);
  $datos_num = count($datos);
  
  $stringdata = '{"data":[';
  
  for ($i=0; $i < $datos_num ; $i++) {
    $stringdata .= '{';
    $stringdata .= '"x":';
    $stringdata .= $datos[$i]['OA'];
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

function Pull_Data_From_DataBase($ensayo, $nlayers) {

  include 'conectionDB.php';

  // query data
  $result = $conn->query("SELECT * FROM `datos` WHERE `trabajo`='".$ensayo."' ORDER BY `OA` ASC  ");
  $datos = $result->fetch_all(MYSQLI_ASSOC);
  $datos_num = count($datos);

  // prepare json with input data
  $x = array();
  $y = array();
  for ($i=0; $i < $datos_num ; $i++) {
        $xidx = array_push($x, floatval($datos[$i]['OA']));
        $yidx = array_push($y, floatval($datos[$i]['resistividad']));
  };
  $data = [
    "nlayers" => $nlayers,
    "OA" => $x,
    "R" => $y,
  ];

  return $data;
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
      $python_interp = "C:\\Users\\cdcel\\AppData\\Local\\Microsoft\\WindowsApps\\python.exe";
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

function Calcular_Valores_Iniciales($ensayo, $nlayers){

  // compute initial values
  $data = Pull_Data_From_DataBase($ensayo, $nlayers);
  $arguments = escapeshellarg(json_encode($data));
  $shellcomand = Define_Python_Commands("init_values");

  $output = shell_exec($shellcomand.$arguments);
  if (strpos($output, "failed python") !== false) {
    return "python failed: " . $output;
  };

  // $output_decode = json_decode($output, true);
  // return $output_decode;
  return $output;
}

// function Calcular_Ajuste($ensayo, $nlayers, $rho0, $thick0){
function Calcular_Ajuste($ensayo, $nlayers, $rho0, $thick0, $checkR, $checkP){

  // calcular ajuste
  $shellcomand = Define_Python_Commands("fit_values");
  $command = escapeshellcmd($shellcomand);
  $database = Pull_Data_From_DataBase($ensayo, $nlayers);
  $data = [
    "nlayers" => $database["nlayers"],
    "checkR" => $checkR,
    "checkP" => $checkP,
    "OA" => $database["OA"],
    "R" => $database["R"],
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

  $result = $conn->query("SELECT * FROM `datos` WHERE `OA`='".$dato_oa."' AND `MN`='".$dato_mn."' AND `trabajo`='".$dato_ensayo."' ");
  $datos = $result->fetch_all(MYSQLI_ASSOC);
  $datos_num = count($datos);

  if($datos_num > 0){
    
    $borrar = $conn->query("DELETE FROM `datos` WHERE `OA`='".$dato_oa."' AND `MN`='".$dato_mn."' AND `trabajo`='".$dato_ensayo."' ");
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
  $nlayers = strip_tags($_POST['nlayers']);
  $checkR = strip_tags($_POST['checkR']);
  $checkP = strip_tags($_POST['checkP']);
  $rho0_string = strip_tags($_POST['Rho0']);
  $thick0_string = strip_tags($_POST['Thick0']);
  $rho0 = explode(",", $rho0_string); 
  $thick0 = explode(",", $thick0_string); 

  $stringdata = Formatear_Data_Para_Graficar($ensayo);
  // $output = Calcular_Ajuste($ensayo, $nlayers, $rho0, $thick0);
  $output = Calcular_Ajuste($ensayo, $nlayers, $rho0, $thick0, $checkR, $checkP);

  $data = array();
  $data['status'] = 'TRUE';
  $data['detalle'] = 'Dato Ajustar recibido. Ensayo:' . $ensayo . ' Capas:' . $nlayers . ' checkR:' . $checkR . ' checkP:' . $checkP;
  $data['detalle'] .= " Resistividades Iniciales: " . implode(", ", $rho0) . " Thick: " . implode(", ", $thick0);
  $data['detalle'] .= " Resultados: " . $output;
  $data['resultados'] = $output;
  $data['dato'] = $stringdata;

  echo json_encode($data, JSON_FORCE_OBJECT);

}

if(isset($_POST['CambiaCapas'])){

  $data = array();

  $ensayo = strip_tags($_POST['Ensayo']);
  $nlayers = strip_tags($_POST['nlayers']);
  $checkR = strip_tags($_POST['checkR']);
  $checkP = strip_tags($_POST['checkP']);

  $output = Calcular_Valores_Iniciales($ensayo, $nlayers);

  $data['status'] = 'TRUE';
  $data['detalle'] = 'Dato recibido. Ensayo:' . $ensayo . ' Capas:' . $nlayers . ' checkR:' . $checkR . ' checkP:' . $checkP;
  // $data['detalle'] .= " Resultados: nlayers = " . $nlayers;
  // $data['detalle'] .= ", rho0 = ". implode(", ",$output['rho0']);
  // $data['detalle'] .= ", thick0 = ". implode(",",$output['thick0']);
  $data['resultados'] = $output;

  echo json_encode($data, JSON_FORCE_OBJECT);
  
}

?>
