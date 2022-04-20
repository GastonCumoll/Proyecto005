import './app.js';



import 'bootstrap';

$(document).ready(function(){
    var boton = document.getElementById("boton_guardar");
    var contador = 0;
    boton.disabled = true;

    var textoAnterior;
    var textoNuevo;
    const objeto = document.getElementsByClassName("fr-element fr-view");
    textoAnterior = objeto;
    
    $(objeto).mouseover(function() {

        const hijo = this.firstChild;
        //console.log(hijo);

        const texto = hijo.firstChild;
        if(contador == 0)
        {
            textoAnterior= texto.nodeValue;
            // console.log(textoAnterior);
            //console.log('hola');
            //console.log(textoNuevo);
        }
        $(objeto).on('click', function(){
            $(objeto).on('paste', function() {
                contador = 1;
                console.log(textoAnterior);
                textoNuevo = texto.nodeValue;
                if(textoAnterior != textoNuevo)
                {
                    boton.disabled = false;
                }
                else
                {
                    boton.disabled = true;
                }

            })
        })

            document.addEventListener('onfocus', (event) => {
                contador = 1;
                console.log("wep");
                textoNuevo = texto.nodeValue;
                if(textoAnterior != textoNuevo)
                {
                    boton.disabled = false;
                }
                else
                {
                    boton.disabled = true;
                }

            }, false);
        })
        
    


});

import './bootstrap';