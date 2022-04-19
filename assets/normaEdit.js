import './app.js';

import 'bootstrap';

$(document).ready(function()
{
    var a=document.getElementById("editar");
    a.onclick = alerta;
    function alerta(){
        
        if (confirm('¿Desea generar un pdf del texto ordenado?')) {
            
            var link2 = "/norma/"+link+"/generarPDF";
            $("#hla")attr.('href',link2);
            console.log('Thing was saved to the database.');
        } else {
            // Do nothing!
            console.log('Thing was not saved to the database.');
        }
    }

});
 import './bootstrap';