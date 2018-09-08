////////////////////////////////////////////////////////////////////////////////////////////							
						function abrir(nuevo,modificar,eliminar){
                                var url = "";
                                if(nuevo==1){
									    		url="../../modulos/mrp/index.php/product/form";
                                } else {
                                        if(modificar==1){
                                                url="../../modulos/mrp/index.php/product/grid";
                                        } else {
                                                //url="../../modulos/mrp/index.php/product/grid/2";
												url="../../modulos/mrp/index.php/product/grid/2";
                                        }
                                }
                                var frop = document.getElementById("opciones");
                                frop.src = url;
                        }
						
						
						//Redirigir al usuario segun su eleccion: Agregar, Modificar o Eliminar (orden de compra)
/////////////////////////////////////////////////////////////////////////////////////////////							
						function abrir_orden_compra(nuevo,modificar,eliminar){
                                var url = "";
                                if(nuevo==1){
                                        url="../../../../webapp/modulos/mrp/index.php/buy_order/form";
                                } else {
                                        if(modificar==1){
                                                url="../../../../webapp/modulos/mrp/index.php/buy_order/grid";
                                        } else { 
                                        		url="../../../../webapp/modulos/mrp/index.php/buy_order/grid/2";
                                        }
                                }
                                var frop = document.getElementById("opciones");
                                frop.src = url;
                        }
                   
                        
/////////////////////////////////////////////////////////////////////////////////////////////						
						function redimensionar(){
                            var frop=document.getElementById("opciones");

                            var altura = parent.innerHeight;
                            
                            if(altura==null){ //IE
                                altura = document.documentElement.clientHeight;
                                //alert(altura);
                                altura = altura-80;
                                //alert(altura);
                            } else { //otros browser
                                altura = altura-205;
                            }                         
                                                        
                            frop.setAttribute("height", altura);                            
                        }

/////////////////////////////////////////////////////////////////////////////////////////////	            