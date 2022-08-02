import './app.js';

import 'bootstrap';
import { contains } from 'jquery';

$(document).ready(function(){
	var boton=document.getElementById("botonVolverAtras");
	var urlAnt=document.referrer;
	console.log(urlAnt);
	if(urlAnt.includes("/indice/Vigentes")){
		boton.setAttribute("href","/indice/Vigentes");
	}else if (urlAnt.includes("/indice/NoVigentes")){
		boton.setAttribute("href","/indice/NoVigentes");
	}else{
		boton.setAttribute("href","/norma/");
	}

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

});