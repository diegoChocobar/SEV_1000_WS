$(document).ready(function() {

    $('#linkPrincipal').click(function(){
       window.location = page_position+"dashboard.php";
       return false;

    });
    $('#linkInformacion').click(function(){

       //location.reload();
       window.location = page_position+"info.php";
       return false;

    });

    $('#linkEnsayo').click(function(){

       if(page_position.trim() === ""){
         window.location = "html/ensayo.php";
       }else{
         window.location = "ensayo.php";
       }
         //*/
       return false;

    });

    $('#linkManual').click(function(){

       //location.reload();
       //window.location = "http://localhost/SEV_1000_WS/archivos/SEV_Prueba_04-04-2022.txt";
       var link = "archivos/SEV_Manual_Usuario.pdf";
       window.open(link, '_blank'); window.focus();
       return false;

    });

  });
