import './app.js';

import 'bootstrap';

$(document).ready(function(){

    document.getElementById("boton123").addEventListener("click", function(event){
        document.getElementById("boton123").disabled=true;
        document.getElementById("rela_form").submit();
    });
})