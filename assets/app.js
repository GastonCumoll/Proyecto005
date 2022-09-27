/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
//import './styles/app.css';
require('@fortawesome/fontawesome-free/js/all.js');
require('bootstrap-datepicker');
require('select2');
import $ from 'jquery';
import 'bootstrap';
import 'bootstrap-select';


global.$ = global.jQuery = $;

import select2 from 'select2';

// start the Stimulus application
import './styles/estilo.css';

import './bootstrap';

import 'bootstrap-select/dist/css/bootstrap-select.css';

$(function () {
    $('#selectpicker').selectpicker();
    // var urlActual = window.location.href;
    
    // if(urlActual.includes("/inicio")){
    //     if(document.getElementsByClassName("headerAdmin")[0]){
    //         var menuAdmin = document.getElementsByClassName("headerAdmin")[0];
    //         menuAdmin.style.visibility = "hidden";
    //     }
        
    //     if(document.getElementsByClassName("headerBusqueda")[0]){
    //         var menuPublic = document.getElementsByClassName("headerBusqueda")[0];
    //         menuPublic.style.visibility = "hidden";
    //     }
    // }
    
});
