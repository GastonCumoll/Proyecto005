import './app.js';

import 'bootstrap';
import bsCustomFileInput from 'bs-custom-file-input';

$(document).ready(function () {
    bsCustomFileInput.init()
  })

    var arreglo=document.getElementById('decreto_etiquetas');//no seleccionadas    
    var arreglo1=document.getElementById('decreto_etiquetas_de_norma');//seleccionadas

    var long=arreglo.length;
    
    var seleccionados=[];
    var noSeleccionados=[];
    var s=0;
    var ns=0;
    for (let i = 0; i < long; i++) {
        
        if(arreglo[i].selected){
            arreglo[i].setAttribute("name","seleccionada");
            seleccionados[s]=arreglo[i];
            s++;
        }else{
            arreglo[i].setAttribute("name","noSeleccionada")
            noSeleccionados[ns]=arreglo[i];
            ns++;
            
        }
    }
    while(arreglo.firstChild){
        arreglo.removeChild(arreglo.firstChild);
    }
    while(arreglo1.firstChild){
        arreglo1.removeChild(arreglo1.firstChild);
    }
    for (let i = 0; i < s; i++) {
        arreglo1.appendChild(seleccionados[i]);
    }
    for (let i = 0; i < ns; i++) {
        arreglo.appendChild(noSeleccionados[i]);
    }

    var arrNoSelec=document.getElementsByName('noSeleccionada');

    // console.log(arrSelec);

    for (let i = 0; i < arrNoSelec.length;i++) {
        
    arrNoSelec[i].addEventListener('dblclick',function(){
        console.log(this);
        //var aux=arrSelec[i];

        this.setAttribute("name","seleccionada");
        arreglo1.append(this);

    })
    
}

    var arrSelec=document.getElementsByName('seleccionada');

    // console.log(arrSelec);

    for (let i = 0; i < arrSelec.length;i++) {
        
    arrSelec[i].addEventListener('dblclick',function(){
        console.log(this);
        //var aux=arrSelec[i];

        this.setAttribute("name","noSeleccionada");
        arreglo.append(this);

    })
    
}
// var arrNSelec=document.getElementsByName('decreto_type_edit[etiquetas][]');
// for (let i = 0; i < arrNSelec; i++) {
//     arrNSelec[i].setAttribute("name","noSeleccionada");
    
// }

        //arreglo.append(arrSelec[i]);
        //ns++;
        //arreglo1.remove(arrSelec[i]);
        //s--;