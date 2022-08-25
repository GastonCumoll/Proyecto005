import './app.js';

import 'bootstrap';

window.onload = function(){

    if (document.getElementById("spinnerFormulario") !== null) {
        document.getElementById("spinnerFormulario").remove();
    }

    var urlActual = window.location;
    var body = document.getElementsByTagName("body")[0];
    var spinner = document.getElementsByClassName("spring-spinner");
    var botonLupita = document.getElementById("botonLupita");
    var itemPageActive = document.getElementsByClassName("page-item active")[0];
    var footer = document.getElementById("footer");
    var botonBuscar = document.getElementsByClassName("buscarNorma")[0];
    var formulario = document.getElementsByTagName("formulario")[0];
    var contenedorDinamicoSpinner = document.getElementsByClassName("contenedorDinamicoSpinner")[0];

    var elemento1 = document.createElement('div');
        elemento1.setAttribute("id","spinnerFormulario");
        elemento1.setAttribute("class","spinnerContainer");

        var elemento2 = document.createElement('div');
        elemento2.setAttribute("class","spring-spinner");

        var elemento3 = document.createElement('div');
        elemento3.setAttribute("class","spring-spinner-part top");

        var elemento4 = document.createElement('div');
        elemento4.setAttribute("class","spring-spinner-rotator");

        var elemento5 = document.createElement('div');
        elemento5.setAttribute("class","spring-spinner-part bottom");

        var elemento6 = document.createElement('div');
        elemento6.setAttribute("class","spring-spinner-rotator");

        var elemento7 = document.createElement('div');
        elemento7.setAttribute("class","textoSpinner text-center");

        var elemento8 = document.createElement('h3');
        elemento8.setAttribute("class","textoSpinner text-center");
        
        var elemento9 = document.createTextNode("Buscando normas...");

        elemento8.appendChild(elemento9);
        elemento7.append(elemento8);
        elemento5.append(elemento6);
        elemento3.append(elemento4);
        elemento2.append(elemento3);
        elemento2.append(elemento5);
        elemento1.append(elemento2);
        elemento1.append(elemento7);


    botonLupita.addEventListener('click', function(){
        
        contenedorDinamicoSpinner.append(elemento1);
        botonLupita.style.visibility = "hidden";
        itemPageActive.style.visibility = "hidden";
        footer.style.visibility = "hidden";
        spinnerContainer.style.display = "block";
        body.style.position = "static";
        body.style.height = "100%";
        body.style.overflow = "hidden";


    });

    botonBuscar.addEventListener('click', function(){

        setTimeout(function(){
            document.formulario.submit();
        },3000);

        contenedorDinamicoSpinner.append(elemento1);
        botonLupita.style.visibility = "hidden";
        itemPageActive.style.visibility = "hidden";
        footer.style.visibility = "hidden";
        spinnerContainer.style.display = "block";
        body.style.position = "static";
        body.style.height = "100%";
        body.style.overflow = "hidden";

        


    });


    




    // if(urlActual.includes("formularioBusqueda")){
        
    //     var spinnerContainer = document.getElementById("spinnerFormulario");
        
    //     botonLupita.addEventListener('click', function(){
    //         botonLupita.style.visibility = "hidden";
    //         itemPageActive.style.visibility = "hidden";
    //         footer.style.visibility = "hidden";
    //         spinnerContainer.style.display = "block";
    //         body.style.position = "static";
    //         body.style.height = "100%";
    //         body.style.overflow = "hidden";
    //     });
        
    //         botonBuscar.addEventListener('click', function(){
    //             alert("hola");
    //             botonLupita.style.visibility = "hidden";
    //             itemPageActive.style.visibility = "hidden";
    //             footer.style.visibility = "hidden";
    //             spinnerContainer.style.display = "block";
    //             body.style.position = "static";
    //             body.style.height = "100%";
    //             body.style.overflow = "hidden";
    //             setTimeout(function(){
    //                 document.formulario.submit();
    //             },3000);
        
    //     });
    // }
    // else{

    //     var spinnerContainer = document.getElementById("spinner");

    //     botonLupita.addEventListener('click', function(){

    //         botonLupita.style.visibility = "hidden";
    //         itemPageActive.style.visibility = "hidden";
    //         footer.style.visibility = "hidden";
    //         spinnerContainer.style.display = "block";
    //         body.style.position = "static";
    //         body.style.height = "100%";
    //         body.style.overflow = "hidden";
    //     });

    //         botonBuscar.addEventListener('click', function(){
                
    //             botonLupita.style.visibility = "hidden";
    //             itemPageActive.style.visibility = "hidden";
    //             footer.style.visibility = "hidden";
    //             spinnerContainer.style.display = "block";
    //             body.style.position = "static";
    //             body.style.height = "100%";
    //             body.style.overflow = "hidden";
    //             setTimeout(function(){
    //                 document.formulario.submit();
    //             },3000);
        
    //     });
    // }
    
    



    

    // // setTimeout(function(){
    // //     botonLupita.style.visibility = "visible";
    // //     itemPageActive.style.visibility = "visible";
    // //     footer.style.visibility = "visible";
    // //     spinnerContainer.style.display = "none";
    // //     body.style.position = "inherit";
    // //     body.style.height = "auto";
    // //     body.style.overflow = "visible";
    // // },3000);

}





