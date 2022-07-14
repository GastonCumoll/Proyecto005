import './app.js';

import 'bootstrap';

let elementosCol = document.getElementsByClassName("cambio");
let elementosRow = document.getElementsByClassName("modificable");
let cantidadCol = elementosCol.length;
let cantidadRow = elementosRow.length;
let ancho = screen.width;

if(ancho < 992){

    for (var i = 0; i < cantidadRow; i++) {
        elementosRow[i].classList.remove("row");
    }

    for (var i = 0; i < cantidadCol; i++) {
        elementosCol[i].classList.remove("col-3");
        elementosCol[i].classList.remove("col-6");
        //elementosCol[i].classList.add("row");
    }

}