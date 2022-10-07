$(document).ready(function() {

    $('#linkPrincipal').click(function(){

       window.location = "http://localhost/SEV_1000_WS/dashboard.php";
       return false;

    });
    $('#linkInformacion').click(function(){

       //location.reload();
       window.location = "http://localhost/SEV_1000_WS/info.php";
       return false;

    });

    $('#linkEnsayo').click(function(){

       //location.reload();
       window.location = "http://localhost/SEV_1000_WS/html/ensayo.php";
       return false;

    });

    $('#linkManual').click(function(){

       //location.reload();
       //window.location = "http://localhost/SEV_1000_WS/archivos/SEV_Prueba_04-04-2022.txt";
       var link = "http://localhost/SEV_1000_WS/archivos/SEV_Manual_Usuario.pdf";
       window.open(link, '_blank'); window.focus();
       return false;

    });

  });
