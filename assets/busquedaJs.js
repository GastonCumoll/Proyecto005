
import './app.js';

import 'bootstrap';

$(document).ready(function(){

    $("#busquedaRapida").keypress(function(event){
        //me redirecciona a una pagina donde estan todas los titulos de las normas que tienen la palabra buscada
        var keycode=(event.keyCode ? event.keyCode : event.which);
        if(keycode == '13'){
        var id=document.getElementById("busquedaRapida");
        var idPalabra=id.value;
        if(idPalabra==""){
            idPalabra="-1";
        }
        const palabraNueva=idPalabra.replace('/','§');
        var urlController="/norma/"+palabraNueva+"/busquedaRapida";
        window.location.href = urlController
        }
        })
        $("#botonLupita").click(function(){
            //me redirecciona a una pagina donde estan todas los titulos de las normas que tienen la palabra buscada
            var id=document.getElementById("busquedaRapida");
            
            var idPalabra=id.value;
            if(idPalabra==""){
                idPalabra="-1";
            }
            const palabraNueva=idPalabra.replace('/','§');
            
            var urlController="/norma/"+palabraNueva+"/busquedaRapida";
            window.location.href = urlController
        })
        //$("#busquedaRapida").keyup(function(){
            //me va trayendo las coincidencias
            //var id=document.getElementById("busquedaRapida");
            // console.log(id.value);
            //var idPalabra=id.value;
            //const palabraNueva=idPalabra.replace('/','§');
            //var urlController="/norma/"+palabraNueva+"/busquedaRapida";
            //$.ajax({
                //method: 'POST',
                //url: urlController,
                //data: datos,
                //dataType: 'json',
                //done:function(){
                    //console.log(data);
                //}
            //})
        //})
        
    
}
    )