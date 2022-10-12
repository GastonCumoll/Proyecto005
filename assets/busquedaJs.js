
import './app.js';

import 'bootstrap';

$(document).ready(function(){

    var cadenaRuta=document.getElementById("cadenaRuta");
    var cadena= cadenaRuta.innerText

    var longitud=cadena.length;
    console.log(screen.width);
    if(screen.width>=1920){
        if (longitud > 145) {
            var cadena1=cadena.substring(0,145);
            cadena1=cadena1 + " ...";
        }else{
            var cadena1=cadena;
        }
    }else if(screen.width<1920 && screen.width>1300){
        if (longitud > 80) {
            var cadena1=cadena.substring(0,80);
            cadena1=cadena1 + " ...";
        }else{
            var cadena1=cadena;
        }
    }else if((screen.width<1300 && screen.width>800)){
        if (longitud > 50) {
            var cadena1=cadena.substring(0,50);
            cadena1=cadena1 + " ...";
        }else{
            var cadena1=cadena;
        }
    }
    cadenaRuta.remove();
    let texto = document.createTextNode(cadena1)
    let p = document.createElement("p");
    p.append(texto);
    document.getElementById("txt").append(p);


    window.addEventListener("unload", function() {
        
        document.getElementById("spinnerFormulario").remove();
    });

    // $("#busquedaRapida").keypress(function(event){
    //     //me redirecciona a una pagina donde estan todas los titulos de las normas que tienen la palabra buscada
    //     var keycode=(event.keyCode ? event.keyCode : event.which);
    //     if(keycode == '13'){
    //     var id=document.getElementById("busquedaRapida");
    //     var idPalabra=id.value;
    //     if(idPalabra==""){
    //         idPalabra="-1";
    //     }
    //     const palabraNueva=idPalabra.replace('/','§');
    //     var urlController="/norma/"+palabraNueva+"/busquedaRapida";
    //     window.location.href = urlController
    //     }
    //     })
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