var mysql = require('mysql');
var mqtt = require('mqtt');

//CREDENCIALES MYSQL
var con = mysql.createConnection({
  host: "cursoiot.ga",
  user: "admin_cursoiot",
  password: "121212",
  database: "admin_cursoiot"
});

//CREDENCIALES MQTT
var options = {
  port: 1883,
  host: 'cursoiot.ga',
  clientId: 'acces_control_server_' + Math.round(Math.random() * (0- 10000) * -1) ,
  username: 'web_client',
  password: '121212',
  keepalive: 60,
  reconnectPeriod: 1000,
  protocolId: 'MQIsdp',
  protocolVersion: 3,
  clean: true,
  encoding: 'utf8'
};

var client = mqtt.connect("mqtt://cursoiot.ga", options);

//SE REALIZA LA CONEXION
client.on('connect', function () {
  console.log("Conexión  MQTT Exitosa!");
  client.subscribe('+/#', function (err) {
    console.log("Subscripción exitosa!")
  });
})

//CUANDO SE RECIBE MENSAJE
client.on('message', function (topic, message) {
  console.log("Mensaje recibido desde -> " + topic + " Mensaje -> " + message.toString());

  var topic_splitted = topic.split("/");
  var serial_number = topic_splitted[0];
  var query = topic_splitted[1];

  if(query=="access_query"){
    var rfid_number = message.toString();

    //hacemos la consulta
    var query = "SELECT * FROM cards_habs WHERE cards_number = '" + rfid_number + "' AND devices_serie = '" + serial_number + "'";
    con.query(query, function (err, result, fields) {
      if (err) throw err;

      //consultamos rfid y devolvemos mensaje
      if(result.length==1){
        //GRANTED
        client.publish(serial_number + "/user_name",result[0].hab_name);
        client.publish(serial_number + "/command","granted");
        console.log("Acceso permitido a..." + result[0].hab_name);

      }else{
        //REFUSED
        client.publish(serial_number + "/command","refused");
      }

      var query = "INSERT INTO `traffic` (`traffic_hab_id`) VALUES (" + result[0].hab_id + ");";
      con.query(query, function (err, result, fields) {
        if (err) throw err;
        console.log("Ingreso registrado en 'TRAFFIC' ");
      });



    });

  }


  if (topic == "values"){
    var msg = message.toString();
    var sp = msg.split(",");
    var temp1 = sp[0];
    var temp2 = sp[1];
    var volts = sp[2];




    //hacemos la consulta para insertar....
    var query = "INSERT INTO `admin_cursoiot`.`data` (`data_temp1`, `data_temp2`, `data_volts`) VALUES (" + temp1 + ", " + temp2 + ", " + volts + ");";
    con.query(query, function (err, result, fields) {
      if (err) throw err;
      console.log("Fila insertada correctamente");
    });
  }
});




//nos conectamos
con.connect(function(err){
  if (err) throw err;

  //una vez conectados, podemos hacer consultas.
  console.log("Conexión a MYSQL exitosa!!!")

  //hacemos la consulta
  var query = "SELECT * FROM devices WHERE 1";
  con.query(query, function (err, result, fields) {
    if (err) throw err;
    if(result.length>0){
      console.log(result);
    }
  });

});



//para mantener la sesión con mysql abierta
setInterval(function () {
  var query ='SELECT 1 + 1 as result';

  con.query(query, function (err, result, fields) {
    if (err) throw err;
  });

}, 5000);
