var socket;
var client;

window.onload = function() {

/*
*******************************************************************************
*******************    CONEXION SETUP MQTT   **********************************
*******************************************************************************
*/
      //Esta parte esta funcionado para conexion por mqtt (demora mucho)
      var options = {
        connectTimeout: 2000,
        // Authentication
        clientId: '<?php echo "web_" . rand(1,999) ; ?>',
        //username: '<?php echo "SEV1000_WS" ; ?>',
        //password: '',

        keepalive: 60, //tiempo de mensaje interno hacia el brouker para avisar que estamos conectados
        clean: true,   //iniciamos en una session limpia (es una session no percistente)
      }


      // WebSocket connect url
      var WebSocket_URL = 'ws://10.42.0.1:8093/mqtt'

      //var client = mqtt.connect(WebSocket_URL, options)
      client = mqtt.connect(WebSocket_URL, options)



      client.on('connect', () => {//original

        console.log('Conexion exito con brouker')
        top_topic = 'SEV_V'+'/#';

        client.subscribe(top_topic, { qos: 0 }, (error) => {
          if (!error) {
            console.log('Suscripción exitosa topics ->'+top_topic);
            //alert ('Suscripción exitosa topics ->'+top_topic);
          }else{
            console.log('Suscripción fallida!')
            //alert ('Suscripción fallida topics ->'+top_topic);
          }
        })
        top_topic_i = 'SEV_I'+'/#';

        client.subscribe(top_topic_i, { qos: 0 }, (error) => {
          if (!error) {
            console.log('Suscripción exitosa topics ->'+top_topic_i);
            //alert ('Suscripción exitosa topics ->'+top_topic);
          }else{
            console.log('Suscripción fallida!')
            //alert ('Suscripción fallida topics ->'+top_topic);
          }
        })


      })

      client.on('error', (error) => {
        console.log('Connect Error:', error)
      })
/*
*******************************************************************************
*******************************************************************************
*/



/*
*******************************************************************************
*********************     MENSAJE MQTT   **************************************
*******************************************************************************
*/
client.on('message', (topic, message) => {

  console.log('Mensaje recibido: ',topic, ' -> ', message.toString());
  //var topic = event.topic;
  let arr_topic = topic.split('/');
  var dispositivo_emisor = arr_topic[0];
  var dispositivo_receptor = arr_topic[1];
  var Function_1 = arr_topic[2];
  var Function_2 = arr_topic[3];
  var calcular = 0;
  var value_data = message;
  //console.log(`Datos recibidos del servidor: ${event.data}`);
  console.log(`Datos recibidos. Emisor: ` + dispositivo_emisor + ` Receptor: ` + dispositivo_receptor + ` Function_1: `+ Function_1 + ` Function_2: ` + Function_2);
  

      if(dispositivo_emisor == "SEV_I"){
        
        if(Function_2 == "Config"){

            let data = JSON.parse(value_data);
            //Extraer las variables
            let promedio = data.promedio;$("#Promedio_I").val(promedio);
            let desvio = data.desvio_standar;$("#Desvio_I").val(desvio);
            let valor = data.valor;$("#corriente").val(valor);
            let Iteraciones = data.Iteraciones;$("#Iteraciones_I").val(Iteraciones);
            let nmedidas = data.nmedidas;$("#nmedidas_I").val(nmedidas);
            let Escala = data.Escala;$("#Escala_I").val(Escala);
            let Confianza = data.Confianza;$("#Confianza_I").val(Confianza);
            let ADS_Frec = data.ADS_Frec;$("#ADS_Frec_I").val(ADS_Frec);
            let tiempo_total = data.tiempo_total;data.tamano;$("#Time_I").val(tiempo_total);
        }


      }


      if(dispositivo_emisor == "SEV_V"){
        
        if(Function_2 == "Config"){

            let data = JSON.parse(value_data);
            //Extraer las variables
            let promedio = data.promedio;$("#Promedio_V").val(promedio);
            let desvio = data.desvio_standar;$("#Desvio_V").val(desvio);
            let valor = data.valor;$("#tension").val(valor);
            let Iteraciones = data.Iteraciones;$("#Iteraciones_V").val(Iteraciones);
            let nmedidas = data.nmedidas;$("#nmedidas_V").val(nmedidas);
            let Escala = data.Escala;$("#Escala_V").val(Escala);
            let Confianza = data.Confianza;$("#Confianza_V").val(Confianza);
            let ADS_Frec = data.ADS_Frec;$("#ADS_Frec_V").val(ADS_Frec);
            let tiempo_total = data.tiempo_total;data.tamano;$("#Time_V").val(tiempo_total);
        }


      }

})
/*
*******************************************************************************
*******************************************************************************
*/


};


function Config_I(){

  var Iteraciones_I = $("#Iteraciones_Config_I").val();
  var ADS_Frec_I = $("#ADS_Frec_Config_I").val();
  var Confianza_I = $("#Confianza_Config_I").val();
  var Escala_I = $("#Escala_Config_I").val();

    // 🔹 Armo el objeto con los valores
  var data_valores = {
      Iteraciones: parseInt(Iteraciones_I),
      Frecuencia_ADS: parseInt(ADS_Frec_I),
      Escala: parseInt(Escala_I),
      Confianza: parseFloat(Confianza_I)
  };
    // 🔹 Convertir a JSON antes de enviar
  var mensaje = JSON.stringify(data_valores);

  var claseI = $('#buttonConfI').attr('class');

  if (claseI.includes("md-btn md-fab m-b-sm success")) {
      $('#buttonConfI').removeClass('md-btn md-fab m-b-sm success');
      $('#buttonConfI').addClass('md-btn md-fab m-b-sm danger');
        // 🔹 Publicar por MQTT (habilitamos recepcion de datos)
        client.publish('SEV_C/SEV_I/Config/OFF', mensaje, (error) => {
          console.log(error || 'Mensaje enviado!!! >', 'SEV_C/SEV_I/Config/OFF');
        });

        alert("Enviamos Deshabilitacion de recepcion de datos full");
  }

  if (claseI.includes("md-btn md-fab m-b-sm danger")) {
      $('#buttonConfI').removeClass('md-btn md-fab m-b-sm danger');
      $('#buttonConfI').addClass('md-btn md-fab m-b-sm success');
        // 🔹 Publicar por MQTT (habilitamos recepcion de datos)
        client.publish('SEV_C/SEV_I/Config/Values', mensaje, (error) => {
          console.log(error || 'Mensaje enviado!!! >', 'SEV_C/SEV_I/Config/Values', data_valores);
        });

        alert("Enviamos Configuración I" +
              "\nIteraciones: " + Iteraciones_I +
              "\nFrec. ADS: " + ADS_Frec_I +
              "\nEscala: " + Escala_I +
              "\nConfianza: " + Confianza_I
        );
  }



}


function Config_V(){

  var Iteraciones_V = $("#Iteraciones_Config_V").val();
  var ADS_Frec_V = $("#ADS_Frec_Config_V").val();
  var Confianza_V = $("#Confianza_Config_V").val();
  var Escala_V = $("#Escala_Config_V").val();

    // 🔹 Armo el objeto con los valores
  var data_valores = {
      Iteraciones: parseInt(Iteraciones_V),
      Frecuencia_ADS: parseInt(ADS_Frec_V),
      Escala: parseInt(Escala_V),
      Confianza: parseFloat(Confianza_V)
  };
    // 🔹 Convertir a JSON antes de enviar
  var mensaje = JSON.stringify(data_valores);

  var claseV = $('#buttonConfV').attr('class');

  if (claseV.includes("md-btn md-fab m-b-sm success")) {
      $('#buttonConfV').removeClass('md-btn md-fab m-b-sm success');
      $('#buttonConfV').addClass('md-btn md-fab m-b-sm danger');
        // 🔹 Publicar por MQTT (habilitamos recepcion de datos)
        client.publish('SEV_C/SEV_V/Config/OFF', mensaje, (error) => {
          console.log(error || 'Mensaje enviado!!! >', 'SEV_C/SEV_V/Config/OFF');
        });

        alert("Enviamos Deshabilitacion de recepcion de datos full");
  }

  if (claseV.includes("md-btn md-fab m-b-sm danger")) {
      $('#buttonConfV').removeClass('md-btn md-fab m-b-sm danger');
      $('#buttonConfV').addClass('md-btn md-fab m-b-sm success');
        // 🔹 Publicar por MQTT (habilitamos recepcion de datos)
        client.publish('SEV_C/SEV_V/Config/Values', mensaje, (error) => {
          console.log(error || 'Mensaje enviado!!! >', 'SEV_C/SEV_V/Config/Values', data_valores);
        });
        //*
        alert("Enviamos Configuración V" +
              "\nIteraciones: " + Iteraciones_V +
              "\nFrec. ADS: " + ADS_Frec_V +
              "\nEscala: " + Escala_V +
              "\nConfianza: " + Confianza_V
        );
        //*/
  }



}
