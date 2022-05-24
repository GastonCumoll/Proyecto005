import './app.js';

import 'bootstrap';

$(document).ready(function(){

    $("#busquedaParam").keypress(function(event){
        //me redirecciona a una pagina donde estan todas los titulos de las normas que tienen la palabra buscada
        var keycode=(event.keyCode ? event.keyCode : event.which);
        if(keycode == '13'){
        var id=document.getElementById("busquedaParam");
        var idPalabra=id.value;
        const palabraNueva=idPalabra.replace('/','§');
        //console.log(palabraNueva);
        var urlController="/norma/"+palabraNueva+"/busquedaParam";
        window.location.href = urlController
        }
        })
        $("#btnBusquedaParam").click(function(){
            //me redirecciona a una pagina donde estan todas los titulos de las normas que tienen la palabra buscada
            var id=document.getElementById("busquedaParam");
            
            var idPalabra=id.value;
            const palabraNueva=idPalabra.replace('/','§');
            //console.log(palabraNueva);
            var urlController="/norma/"+palabraNueva+"/busquedaParam";
            window.location.href = urlController
        })
    }
)