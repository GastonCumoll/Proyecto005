import './app.js';

import 'bootstrap';
import { contains } from 'jquery';


	// var botonTexto = document.getElementById("botonBuscarTexto");
	// botonTexto.addEventListener("click",function(){
	// var texto = document.getElementById("busquedaTexto");
	// const textCompleto =document.getElementById("textoCompleto");
	// console.log(textCompleto.textContent);
	// const textoSeparado=textCompleto.textContent.split(" ");
	// var tamaño=textoSeparado.length;
	// for (let i = 0; i < tamaño; i++) {
	// 	if(textoSeparado[i]==texto.value){

	// 	}
	// 	const element = array[index];
	// 	array
	// }
	// console.log(textoSeparado);
	// });

var primVez = 0;
var cont = 0;
$(document).ready(function(){

	var boton = document.getElementsByClassName("botonAtras")[0];
	var urlAnt = document.referrer;
	var vuelta = $('#volverFrom').val();
	boton.addEventListener("click", function(){
			if(urlAnt.includes("/indice/Vigentes")){
				boton.setAttribute("href","/indice/Vigentes");
			}
			else if (urlAnt.includes("/indice/NoVigentes")){
				boton.setAttribute("href","/indice/NoVigentes");
			}
			else if(urlAnt.includes("/formularioBusquedaResult") || urlAnt.includes("/busquedaFiltro") || urlAnt.includes("/busquedaRapida") ){
				boton.setAttribute("href",urlAnt);
			}
			else if(urlAnt.includes("/edit") && vuelta == 'borrador'){
				boton.setAttribute("href","/norma/borrador");
			}
			else if(urlAnt.includes("/edit") && vuelta == 'todas'){
				boton.setAttribute("href","/norma");
			}
			else if(urlAnt.includes("/edit") && vuelta == 'listas'){
				boton.setAttribute("href","/norma/listas");
			}
			else if(urlAnt.includes("/showEdit") && vuelta == 'borrador'){
				boton.setAttribute("href","/norma/borrador");
			}
			else if(urlAnt.includes("/showEdit") && vuelta == 'todas'){
				boton.setAttribute("href","/norma");
			}
			else if(urlAnt.includes("/showEdit") && vuelta == 'listas'){
				boton.setAttribute("href","/norma/listas");
			}
			else if(urlAnt.includes("/agregarArchivo") && vuelta == 'borrador'){
				boton.setAttribute("href","/norma/borrador");
			}
			else if(urlAnt.includes("/agregarArchivo") && vuelta == 'todas'){
				boton.setAttribute("href","/norma");
			}
			else if(urlAnt.includes("/agregarArchivo") && vuelta == 'listas'){
				boton.setAttribute("href","/norma/listas");
			}
			else if(urlAnt.includes("/relaFormEdit") && vuelta == 'borrador'){
				boton.setAttribute("href","/norma/borrador");
			}
			else if(urlAnt.includes("/relaFormEdit") && vuelta == 'todas'){
				boton.setAttribute("href","/norma");
			}
			else if(urlAnt.includes("/relaFormEdit") && vuelta == 'listas'){
				boton.setAttribute("href","/norma/listas");
			}
			else if(urlAnt.includes("/busquedaId") && urlAnt.includes("/etiqueta/")){
				boton.setAttribute("href",urlAnt);
			}
			else if(urlAnt.includes("/listas")){
				boton.setAttribute("href","/norma/listas");
			}
			else if(urlAnt.includes("/borrador")){
				boton.setAttribute("href","/norma/borrador");
			}
			else if(urlAnt.includes("/norma/")){
				boton.setAttribute("href","/norma/");
			}
			else if(urlAnt==window.location){
				boton.setAttribute("href",'/inicio');
			}
			else{
				boton.setAttribute("href",'/inicio');
			}
	});
	
	// var botonTexto = document.getElementById("botonBuscarTexto");
	// var busquedaTexto =document.getElementById("busquedaTexto");
	// var id=0;
	// botonTexto.addEventListener("click",function(){
	// 	var text = document.getElementById("busquedaTexto").value;
	// 	var query = new RegExp("(\\b" + text + "\\b)", "gim");
	// 	var e = document.getElementById("parrafo").innerHTML;
	// 	var enew = e.replace(/(<span>|<\/span>)/igm, "");
	// 	document.getElementById("parrafo").innerHTML = enew;
	// 	var newe = enew.replace(query, "<span>$1</span>");
	// 	document.getElementById("parrafo").innerHTML = newe;
	// })

	//busquedaTexto id del input
	var palabrasBuscadas ;
	var busquedaTexto = document.getElementById("busquedaTexto");
	
	
    busquedaTexto.addEventListener("keyup", function(event) {
		event.preventDefault();
		if (event.keyCode === 13) {

			var palabra = document.getElementById("busquedaTexto").value;

			if (palabra.length > 0 && cont == 0 && primVez == 0) {
				primVez++;
				//palabrasBuscadas.push(palabra); // Agregar la palabra buscada al arreglo

				var query = new RegExp("(\\b" + palabra + "\\b)", "gim");
				var eInicial = document.getElementById("parrafo").innerHTML;
				var e = document.getElementById("parrafo").innerHTML;
				var enew = e.replace(/(<span>|<\/span>)/igm, "");

				document.getElementById("parrafo").innerHTML = enew;
				var newe = enew.replace(query, '<span class="palabraResaltada">$1</span>');
				document.getElementById("parrafo").innerHTML = newe;

				palabrasBuscadas = document.getElementsByClassName("palabraResaltada");
				

				mostrarAlerta(palabra,eInicial); // Mostrar el div de alerta con la palabra buscada
				
			}
			var element = palabrasBuscadas[cont];
			element.scrollIntoView({block: "center", behavior: "smooth"});
			cont++;
			if(cont == palabrasBuscadas.length){
				cont = 0;
			}
		}
	})
	
function mostrarAlerta(palabra,eInicial) {
    var alertDiv = document.getElementById("alertFiltro");

    // Limpiar el contenido existente
    alertDiv.innerHTML = "";
	alertDiv.classList.add("alert-info");
    var mensaje = document.createElement("span");
    mensaje.innerHTML = "Palabra buscada: <strong>" + palabra + "</strong>";
    alertDiv.appendChild(mensaje);

    var closeButton = document.createElement("button");
	
	closeButton.setAttribute("type", "button");
	closeButton.setAttribute("class", "close");
	closeButton.setAttribute("aria-label", "Close");
	closeButton.setAttribute("id", "btnCerrar");

    closeButton.innerHTML = "x";
    closeButton.addEventListener("click", function() {
        alertDiv.innerHTML = ""; // Limpiar el contenido al hacer clic en el botón "x"
		document.getElementById("parrafo").innerHTML = eInicial;
		document.getElementById("busquedaTexto").value = "";
		alertDiv.classList.remove("alert-info");
		primVez=0;
		cont=0;

    });
    alertDiv.appendChild(closeButton);
}







	// 	var botonTexto = document.getElementById("botonBuscarTexto");
	// 	botonTexto.addEventListener("click",function(){
	//  	var texto = document.getElementById("busquedaTexto");
	// 	const textCompleto =document.getElementById("parrafo");
	// 	//console.log(texto.value);
	// 	//const textoSeparado=textCompleto.split(" ");
	// 	//console.log(textoSeparado);
	// 	$("#parrafo p").each(function(){
	// 		console.log($(this).html());
	// 		$(this).html("<span>" + $(this).html().split(" ").join("</span> <span>") + "</span");
	// 	});
	// 	$("#parrafo span:contains('" + texto.value + "')").css("background-color","yellow");
	// })
		

	

});

$('.ir-arriba').click(function(){
	$('body, html').animate({
		scrollTop: '0px'
	}, 300);
});

$(window).scroll(function(){
	if( $(this).scrollTop() > 0 ){
		$('.ir-arriba').slideDown(300);
	} else {
		$('.ir-arriba').slideUp(300);
	}
});