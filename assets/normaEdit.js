import './app.js';

import 'bootstrap';


    var modal = document.getElementById("myModal");
    var span = document.getElementsByClassName("close")[0];
    var body = document.getElementsByTagName("body")[0];
    var botonEditar = document.getElementsByClassName("editar");
    var botonSi = document.getElementById("botonSi");
    var botonNo = document.getElementById("botonNo");
    var botonLupita = document.getElementById("botonLupita");

    var cantidad = botonEditar.length;
    for(let i = 0; i<cantidad; i++){
        botonEditar[i].addEventListener('click', function(){
            botonLupita.disabled = true;
            modal.style.display = "block";
   
            body.style.position = "static";
            body.style.height = "100%";
            body.style.overflow = "hidden";
   
            botonSi.addEventListener('click', function(){
                var link= "/norma/"+botonEditar[i].value+"/generarPDF";

                botonLupita.disabled = false;

                modal.style.display = "none";
       
                body.style.position = "inherit";
                body.style.height = "auto";
                body.style.overflow = "visible";

                window.location.href = link;
                   
            });
       
            botonNo.addEventListener('click', function(){
                var link2="/norma/"+botonEditar[i].value+"/edit";

                botonLupita.disabled = false;

                modal.style.display = "none";
       
                body.style.position = "inherit";
                body.style.height = "auto";
                body.style.overflow = "visible";

                window.location.href = link2;
            });
       
            span.onclick = function() {

                botonLupita.disabled = false;

                modal.style.display = "none";
       
                body.style.position = "inherit";
                body.style.height = "auto";
                body.style.overflow = "visible";
            };
        });
    }