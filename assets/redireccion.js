import './app.js';

import 'bootstrap';

//este script tiene como objetivo redireccionar a "/inicio" luego de mostrar el cartel de "consulta enviada correctamente" pasado 2 segundos

setTimeout(function(){
    
    var link = "/inicio";
    window.location.href = link;

}, 3000);