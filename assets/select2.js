
import './styles/select2totree.css';
import $ from 'jquery';
import 'select2';
import './select2totree';
import 'select2/dist/js/i18n/es.js'

$.get('/indice/generate/tree',function(data){
        var midata = data;
        
        function addSelectedById(data, id) {
                for (var i = 0; i < data.length; i++) {
                        if (data[i].id === id) {
                                data[i].selected = "true";
                        return true;
                        } else if (data[i].inc) {
                                if (addSelectedById(data[i].inc, id)) {
                                        return true;
                                }
                        }
                }
                return false;
        }
        var URLactual = window.location.href;
        if (URLactual.includes('/norma') && URLactual.includes('/edit')) {
                var selectItems = document.getElementById('selectIds');
                // selectItems.style.display="none";
                var cantidadOpt = document.getElementById('selectIds').length;

                for (let i = 0; i < cantidadOpt; i++) {
                        addSelectedById(midata,parseInt(selectItems[i].value));        
                }
        }
        

        $("#decreto_type_edit_items").select2ToTree({treeData: {dataArr: midata},translations:{
                'No results found': 'No se encontraron resultados',
                'Searching...': 'Buscando...',
                'You can only select 1 item' : 'Sólo se puede seleccionar 1 item'
        },language : 'es'});
        $("#decreto_items").select2ToTree({treeData: {dataArr: midata},translations:{
                'No results found': 'No se encontraron resultados',
                'Searching...': 'Buscando...',
                'You can only select 1 item' : 'Sólo se puede seleccionar 1 item'
        },language : 'es'});
        $("#ley_items").select2ToTree({treeData: {dataArr: midata},translations:{
                'No results found': 'No se encontraron resultados',
                'Searching...': 'Buscando...',
                'You can only select 1 item' : 'Sólo se puede seleccionar 1 item'
        },language : 'es'});
        $("#ordenanza_items").select2ToTree({treeData: {dataArr: midata},translations:{
                'No results found': 'No se encontraron resultados',
                'Searching...': 'Buscando...',
                'You can only select 1 item' : 'Sólo se puede seleccionar 1 item'
        },language : 'es'});
        $("#circular_items").select2ToTree({treeData: {dataArr: midata},translations:{
                'No results found': 'No se encontraron resultados',
                'Searching...': 'Buscando...',
                'You can only select 1 item' : 'Sólo se puede seleccionar 1 item'
        },language : 'es'});
        $("#resolucion_items").select2ToTree({treeData: {dataArr: midata},translations:{
                'No results found': 'No se encontraron resultados',
                'Searching...': 'Buscando...',
                'You can only select 1 item' : 'Sólo se puede seleccionar 1 item'
        },language : 'es'});
        $("#ley_type_edit_items").select2ToTree({treeData: {dataArr: midata},translations:{
                'No results found': 'No se encontraron resultados',
                'Searching...': 'Buscando...',
                'You can only select 1 item' : 'Sólo se puede seleccionar 1 item'
        },language : 'es'});
        $("#ordenanza_type_edit_items").select2ToTree({treeData: {dataArr: midata},translations:{
                'No results found': 'No se encontraron resultados',
                'Searching...': 'Buscando...',
                'You can only select 1 item' : 'Sólo se puede seleccionar 1 item'
        },language : 'es'});
        $("#circular_type_edit_items").select2ToTree({treeData: {dataArr: midata},translations:{
                'No results found': 'No se encontraron resultados',
                'Searching...': 'Buscando...',
                'You can only select 1 item' : 'Sólo se puede seleccionar 1 item'
        },language : 'es'});
        $("#resolucion_type_edit_items").select2ToTree({treeData: {dataArr: midata},translations:{
                'No results found': 'No se encontraron resultados',
                'Searching...': 'Buscando...',
                'You can only select 1 item' : 'Sólo se puede seleccionar 1 item'
        },language : 'es'});

});
