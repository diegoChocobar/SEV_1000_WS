<?php


//momento de conectarnos a db
$severname_db = "localhost";
$database_db = "Datos_SEV";
$username_db = "root";
$password_db = "";

//momento de conectarnos a db
$conn = mysqli_connect($severname_db,$username_db,$password_db,$database_db);

if ($conn==false){
  echo "Hubo un problema al conectarse a Base de Datos";
  die();
}else{
  //echo "Coneccion exitosa a Base de Datos <br>";
}


 ?>
