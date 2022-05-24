
import './app.js';

import 'bootstrap';

// import 'html2pdf';
var nombre;
    $(document).ready(function()
    {
        var variable=document.getElementsByClassName("botonPDF");
        $(".botonPDF").mouseover(function(){
            nombre=this.value;
            console.log(nombre+'.pdf');
        })        
    });

    document.addEventListener("DOMContentLoaded", () => {
    // Escuchamos el click del botón
    var $boton = document.getElementById("btnCrearPdf");
    
        $boton.addEventListener("click",()=>{
            
            var texto = document.getElementsByClassName("fr-view");

        const $elementoParaConvertir = texto[0]; // <-- Aquí puedes elegir cualquier elemento del DOM
        var name= nombre+'.pdf';
        html2pdf()
            .set({
                margin: 1,
                filename:name,
                image: {
                    type: 'jpeg',
                    quality: 0.98
                },
                html2canvas: {
                    scale: 3, // A mayor escala, mejores gráficos, pero más peso
                    letterRendering: true,
                },
                jsPDF: {
                    unit: "in",
                    format: "a4",
                    orientation: 'portrait' // landscape o portrait
                }
            })
            .from($elementoParaConvertir)
            .save()
        })
});

import './bootstrap';

