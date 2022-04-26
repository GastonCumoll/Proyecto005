import './app.js';

import 'bootstrap';

$(document).ready(function()
{
    var a=document.getElementById("editar");
    // console.log(a.value);
    a.onclick = alerta;

    function alerta(){
        var link= "/norma/"+a.value+"/editTexto";
        console.log(a);
        var link2="/norma/"+a.value+"/edit";
        if (confirm('¿Desea generar un pdf del texto ordenado?')) {
            window.location.href = link;
            
            console.log('Thing was saved to the database.');
        } else {
            window.location.href = link2;
            console.log('Thing was not saved to the database.');
        }
    }

});
import './bootstrap';