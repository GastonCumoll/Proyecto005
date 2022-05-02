import './styles/boton.css';
import './app.js';

import 'bootstrap';
import 'bootstrap/js/dist/collapse';






$(document).ready(function()
        {   
            

            var variable = document.getElementsByClassName("valor123");
            $(".valor123").click(function(){
                //para obtener el id de un obj colocamos el valor en el atributo value de una etiqueta <button> y no en una etiqueta <a>, porque no devuelve value
                var id = this.value;
                
                $.get("/norma/"+id+"/normasAjax",function(data){
                    var c = $('<div class="card" id="tarjeta"></div >');  
                        $('#container').html('');  
                        $('#container').append(c);
                        for(var i = 0; i < data.length; i++) {  
                            var norma = data[i];
                            
                            var c =$('<tr><td class="" id="tipoNorma"></td><td id="numero"></td><td id="titulo"></td></tr> ');
                            var c =$('<div class="card text-center  shadow mb-5 bg-white rounded"><div class="card-header "><h5 style="display:inline"; id="tipoNorma"></h5><h5 style="display:inline"; id="n"></h5><h5 style="display:inline"; id="numero"></h5></div><div class="card-body"><h6 class="card-title" id="titulo"></h6></div><div class="card-footer text-muted"><a href="" id="verNorma" class="btn btn-primary" >Ver Norma</a></div></div>');
                            
                            $('#tipoNorma', c).html(norma['tipo']);
                            $('#n',c).html(" N° ");
                            $('#numero', c).html(norma['numero']);
                            $('#titulo', c).html(norma['titulo']);
                            
                            $('#container').append(c);
                            var link = norma['id'];
                            //var link 2 es el comentado no el noComentado
                            var link2 = "/norma/"+link;
                            // var link2= "/norma/"+link+"/"+id ;
                            
                            $('#verNorma', c).attr('href',link2);
                            $('#container').append(c);
                        }
                        
                        //window.scrollTo(0, 1500);
                        //scroll suave
                        var element = document.getElementById("container");
                        element.scrollIntoView({behavior: "smooth"});
                        
                    })
            });
            
            $("#accordionItem").on("hide.bs.collapse show.bs.collapse", e => {
                $(e.target)
                .prev()
                .find("i:last-child")
                .toggleClass("fa-minus fa-plus");
            });
        });

        // start the Stimulus application
import './bootstrap';

    