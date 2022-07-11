import './app.js';

import 'bootstrap';

//este script tiene como objetivo redireccionar a "/logout"

setTimeout(function(){
    
    var link = "/logout";
    window.location.href = link;

}, 3000);