import './app.js';

import 'bootstrap';

$(document).ready(function(){

    document.getElementById("boton123").addEventListener("click", function(event){
        document.getElementById("boton123").disabled=true;
        document.getElementById("form_tipo_consulta").submit();
    });
})