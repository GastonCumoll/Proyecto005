import './app.js';

import 'bootstrap';

$(document).ready(function()
{
    var boton=document.getElementById("botonario");
    boton.disabled=true;
//     var textario= new FroalaEditor('#textario',{
//         events: {
//             'charCounter.update':function(){
//                 boton.disabled=false;
//             }
//         }
//     });
    var textario=document.getElementsByClassName("fr-wrapper");
    console.log(textario);

    $(".fr-wrapper").change(function(){
    boton.disabled=false;
    });

});
 import './bootstrap';