import './app.js';

import 'bootstrap';

$(document).ready(function(){
document.getElementById("boton_guardar").addEventListener("click", function(event){
    document.getElementById("boton_guardar").disabled=true;
    document.getElementById("form_etiqueta").submit();
});
})