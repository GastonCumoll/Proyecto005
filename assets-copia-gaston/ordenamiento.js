import './app.js';



import 'bootstrap';


$(document).ready(function(){
    var norma=document.getElementsByClassName("norma");
    var longitud=norma.length
    var element=[];
    for (let i = 0; i < longitud; i++) {
        element[i] = norma[i].getAttribute('value');
    }
    JSON.stringify(element)
    
    console.log(element);

})