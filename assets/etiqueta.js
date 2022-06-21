import './styles/estilo.css';
import './app.js';

import "select2/dist/js/select2.min.js";
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
        placeholder:{
        id: -1,
        text: 'Buscar ...',
        },
        //theme: 'bootstrap4', 
        allowClear: true,
    });
    
});
