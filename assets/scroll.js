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
			else if(urlAnt.includes("/listas")){
				boton.setAttribute("href","/norma/listas");
			}
			else if(urlAnt.includes("/borrador")){
				boton.setAttribute("href","/norma/borrador");
			}
			else if(urlAnt.includes("/norma/")){
				boton.setAttribute("href","/norma/");
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
			else if(urlAnt==window.location){
				boton.setAttribute("href",'/inicio');
			}else{
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

	var busquedaTexto = document.getElementById("busquedaTexto");
	// console.log(busquedaTexto.);
    busquedaTexto.addEventListener("keyup", function(event) {
    event.preventDefault();
    if (event.keyCode === 13) {
        //document.getElementById("id_of_button").click();
		var text = document.getElementById("busquedaTexto").value;
		
		if(text.length > 0){
			var query = new RegExp("(\\b" + text + "\\b)", "gim");
			var e = document.getElementById("parrafo").innerHTML;
			var enew = e.replace(/(<span>|<\/span>)/igm, "");
			document.getElementById("parrafo").innerHTML = enew;
			var newe = enew.replace(query, '<span id="palabraResaltada">$1</span>');
			document.getElementById("parrafo").innerHTML = newe;
			
            var element = document.getElementById("palabraResaltada");
			element.scrollIntoView({block: "center", behavior: "smooth"});
			// window.;
		}
    }
})
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