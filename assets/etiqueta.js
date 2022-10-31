import './styles/estilo.css';
import './app.js';
import 'select2';
import 'select2-theme-bootstrap4/dist/select2-bootstrap.css';                   
//import 'select2/dist/css/select2.css';
//import "select2/dist/js/select2.min.js";

// import "@ttskch/select2-bootstrap4-theme/dist/select2-bootstrap4.css";
import bsCustomFileInput from 'bs-custom-file-input';


//import 'select2-bootstrap-theme/dist/select2-bootstrap4.css';
//<link rel="stylesheet" href="/path/to/select2-bootstrap4.min.css"></link>

//import * as select2 from 'select2';
// $(document).ready(function() {
//     $('.js-example-basic-multiple').select2({
//         theme: 'bootstrap4',
//     });
// });
$(document).ready(function() {

    bsCustomFileInput.init();

    $('.js-example-basic-multiple').select2({
        //theme: 'bootstrap4',
        placeholder:{
        id: -1,
        text: 'Buscar ...',
        },
        allowClear: true,
    });
    // var p=window.performance.getEntriesByType("navigation")[0].type;
    // alert(p);
    // if(p==='onload'){
        document.getElementById("boton_guardar").addEventListener("click", function(event){
            document.getElementById("boton_guardar").disabled=true;
            document.getElementById("norma_nueva_form").submit();
        });
    // }
});
