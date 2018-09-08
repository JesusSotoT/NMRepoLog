
								//Respaldo de codigo
							   //Inicio modo asincrono
							  $(\"#div".$reg{'idcampo'}."\").load(a,function(response,status,xhr){								
								campo_onchange(document.getElementById('i".$reg{'idcampo'}."'),true);
								//dependenciascompuestas('".$reg{'idcampo'}."');
								//alert('entre a dependencia compuesta me llamo:'+idcampo+' y yo soy:".$reg{'nombrecampo'}."');
								//document.getElementById('txtesperarcompuesta').value = 0;
								/*
								document.getElementById('divdepurar').innerHTML = 		
										document.getElementById('divdepurar').innerHTML + 
										' -- soy <b>".$reg{'nombrecampo'}."</b> entre por:' + idcampo + 
										'  idm=' + document.getElementById('i64').value + 
										'  mande:' + response + 
										' s:' + status + 
										' x:' + xhr.status + ' --    ';
										*/
										
										//Si se esta editando y es el ultimo campo con dependencia
										//compuesta se manda llamar la carga de los datos iniciales
										//del detalle.
										if(cargandoparaeditar){
											if(ultimocampocondependenciacompuesta=='".$reg{'idcampo'}."'){
												//alert('listo ya cargue la ruta, este es el campo ".$reg{'idcampo'}." el ultimo es: '+ultimocampocondependenciacompuesta);												
												carga_datos_iniciales();
												cargandoparaeditar=false;
											}
										}
										
							  });
							  //FIN DE modo asincrono
