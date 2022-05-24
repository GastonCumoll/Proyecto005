import './app.js';



import 'bootstrap';

$(document).ready(function(){
    //var boton = document.getElementById("boton_guardar");
    var contador = 0;
    // boton.disabled = true;

    var textoAnterior;
    var textoNuevo;
    const texto = document.getElementsByClassName("fr-element fr-view");
    const id = document.getElementById("id");
    const norma = document.getElementById("idNombre");
    //console.log(id.value);
    // textoAnterior = objeto;
    //console.log(norma.value);
    var hoy = new Date();
    var fecha = hoy.getDate()+ '-'+(hoy.getMonth()+1)+'-'+hoy.getFullYear()+'-'+hoy.getHours()+':'+hoy.getMinutes();
    var idNorma= id.value;
    var normaNombre = norma.value;
    // console.log(normaNombre);
    var name= normaNombre+'-MODIFICADA-'+fecha+'.pdf';
        const $elementoParaConvertir = texto[0]; // <-- Aquí puedes elegir cualquier elemento del DOM
        
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

            setTimeout(function(){
                var url='/norma/'+idNorma+'/moverArchivo/'+name;
                $.post(
                    url,

                )
            }, 1000);
            
            

    // $(objeto).mouseover(function() {

    //     const hijo = this.firstChild;
    //     //console.log(hijo);

    //     const texto = hijo.firstChild;
    //     if(contador == 0)
    //     {
    //         textoAnterior= texto.nodeValue;
    //         // console.log(textoAnterior);
    //         //console.log('hola');
    //         //console.log(textoNuevo);
    //     }
    //     $(objeto).on('click', function(){
    //         $(objeto).on('paste', function() {
    //             contador = 1;
    //             console.log(textoAnterior);
    //             textoNuevo = texto.nodeValue;
    //             if(textoAnterior != textoNuevo)
    //             {
    //                 boton.disabled = false;
    //             }
    //             else
    //             {
    //                 boton.disabled = true;
    //             }

    //         })
    //     })

    //         document.addEventListener('onfocus', (event) => {
    //             contador = 1;
    //             console.log("wep");
    //             textoNuevo = texto.nodeValue;
    //             if(textoAnterior != textoNuevo)
    //             {
    //                 boton.disabled = false;
    //             }
    //             else
    //             {
    //                 boton.disabled = true;
    //             }

    //         }, false);
    //     })
        
    


});

import './bootstrap';