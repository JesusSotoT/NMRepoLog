<table class="listado" border="1" id="tbldetalles">
	<tbody>
<?php

	$idestructuradetalle=$_SESSION["idestructuradetalle"];
        
        
        //PARCIALLOG
        $parciallog_detalle = new clparciallog($_SESSION['nombreestructuradetalle'],$_SESSION["accelog_idperfil"],$conexion);
        
        
	$sql_campos_detalles = "
                select * from catalog_campos 
                where idestructura=".$idestructuradetalle." and formato<>'O'                       
                order by orden 
                ";
        //echo $sql_campos_detalles;
        
	$result_campos_detalles = $conexion->consultar($sql_campos_detalles);
	$campos_detalles="";
	$campos_fila_nuevo="";
	$objeto="";
	$campo_detalle=0;
	$script_inputmask="";
	
	$script_calculaformulas="";
	$cuantos_formato=0;
	$valor="";
	
	
	//Para los campos fecha ya no poner la i del campo ya que ya la incluye
	$script_fechas = "";
	///////////////////////////////////////////////////////////////////////
	
	$script_detalles_dependenciacompuesta="";
	$script_detalles_dependenciascargar="";
	
	
	while($rsd = $conexion->siguiente($result_campos_detalles)){
		
		
		$IDCAMPO = "i".$rsd{'idcampo'}."__FILA";
			
		if($campo_detalle==0){ //||$campo_detalle==1){
			
			//Estos campo no se deben mostrar ya que:
			//El primero de ellos se utiliza para sincronizar con la tabla títulos
			//y siempre deberá al principio de la tabla.
			
		} else {
			
                        //PARCIALLOG
                        $permiso_parciallog = $parciallog_detalle->get_permiso($rsd{'nombrecampo'});                    
			if($permiso_parciallog!="O"){
                            if($rsd{"requerido"}){
                                    $campos_detalles.= "<td>&nbsp;&nbsp;".$rsd{"nombrecampousuario"}."<font color=gray>*</font>&nbsp;&nbsp;</td>";								
                            } else {
                                    $campos_detalles.= "<td>&nbsp;&nbsp;".$rsd{"nombrecampousuario"}."&nbsp;&nbsp;</td>";
                            }                            
                        }
                        
                        
			


			//investigando si tiene dependencias...
			$sql = " select * from catalog_dependencias where idcampo=".$rsd{'idcampo'};
			$rsdependencias = $conexion->consultar($sql);
			$tienedependencia=false;
			if($regd = $conexion->siguiente($rsdependencias)){
				if($regd{'tipodependencia'}!="N"){
					$tienedependencia=true;
				}
			}
			
			if($tienedependencia){
				
				include("f_detalles_dependencia.php");
				
			} else {
				
				include "f_detalles_formato.php";						
				
				include "f_detalles_objeto.php";						
				$objeto = str_replace("\n","",$objeto);
				$objeto = str_replace("\r","",$objeto);		
				
				include("f_detalles_formula.php");
									
			}
			
			$conexion->cerrar_consulta($rsdependencias);
			
                        
                        
                        
                        
                        
                        ////////////////////////////////////////////////////////////////////////////////
                        //PARCIALLOG
                        ////////////////////////////////////////////////////////////////////////////////
                            
                            if($permiso_parciallog=="O"){
       
                                $objeto="<input type='hidden'  name='".$IDCAMPO ."'  id='".$IDCAMPO."' value='' /> ";
                                $campos_fila_nuevo.= "".$objeto."";
                                
                            } else if($permiso_parciallog=="L"){
                                                                
                                $tamano = "50"; //TAMAÑO MAXIMO
                                if($rsd{'longitud'}<$tamano){
                                    $tamano=$rsd{'longitud'};
                                }                                
                                /*
                                if(($tienedependencia)&&($a==0)){                                    
                                    $sql_validable = " 
                                        select ".$campodesc." 
                                        from ".$dependenciatabla." 
                                        where ".$reg{'nombrecampo'}." = '".$valor_registro_parciallog."'                                    
                                      ";
                                     //echo $sql_validable;
                                     $result_datos_validable = $conexion->consultar($sql_validable);
                                     if(($rs_datos_validable = $conexion->siguiente($result_datos_validable))){
                                        //echo "entre  ".$rs_datos_validable{$campodesc};
                                        $valor_registro_parciallog_traducido=$rs_datos_validable{$campodesc};
                                     } 
                                     $conexion->cerrar_consulta($result_datos_validable);                                        
                                }*/ 
                                
                                $objeto =" <div name='i".$rsd{'idcampo'}."dis__FILA' id='i".$rsd{'idcampo'}."dis__FILA' size='".$tamano."' ></div> ";
                                $objeto.=" <input type='hidden'  name='".$IDCAMPO."'  id='".$IDCAMPO."' value='' /> ";
                                
                                $campos_fila_nuevo.= "<td>".$objeto."</td>";
                                
                            } else {
                                
                                $campos_fila_nuevo.= "<td>".$objeto."</td>";
                            }
                            
                        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                        // FIN PARCIALLOG //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////                        
                        
                        			

		}
		
		$campo_detalle+=1;
		
	}
	
	//$campos_fila_nuevo="<input type='hidden' id='idlinea__FILA' name='idlinea__FILA'><td style='background:none;border:none;cursor:pointer;'><img onclick='quitar(this.parentNode.parentNode.rowIndex)' src='img/menos.png' title='Eliminar fila'></td>".$campos_fila_nuevo."";
	$campos_fila_nuevo="<td style='background:none;border:none;cursor:pointer;'><img onclick='quitar(this.parentNode.parentNode.rowIndex)' src='img/menos.png' title='Eliminar fila'></td>".$campos_fila_nuevo."";
	

	$campos_detalles="<tr class='titulo'><td style='background:none;border:none;cursor:pointer;'><img onclick='agregar()' src='img/mas.png' title='Agregar fila'></td>".$campos_detalles."</tr>";
	$conexion->cerrar_consulta($result_campos_detalles);
	
	$_SESSION['controlesd']=$controlesd;
	
	echo $campos_detalles;


?>
		
	</tbody>
</table>
<input type="hidden" value="0" id="txt_filasdetalles" name="txt_filasdetalles" >
<!--<input type="button" onclick="dependenciasporomision_detalles()" value="actualizar" />-->
<script>
	
	var filas = 0;
	var filastabla = 0;
	var cargandofilasiniciales = false;

	//////////////////////////////////////////////////////////////////////
	/*AGREGAR FILA*/
	function agregar(){
		
		crearfila();	
		//window.setTimeout('dependenciasporomision_detalles()',2000);
		dependenciasporomision_detalles();
		
	} //function agregar()
	
	function crearfila(){
		//Incrementa las filas
		filas = filas+1;		
		filastabla = filastabla+1;
		var txtfilasdetalles = document.getElementById("txt_filasdetalles");
		txtfilasdetalles.value=filas;
		
		//Carga la tabla de detalles
		var tbldetalles = document.getElementById("tbldetalles");
		var r = tbldetalles.insertRow(filastabla);				
		
		
		//Obtiene los campos para añadir en el detalle
		inputcampo="";
		inputcampo="<?php echo $campos_fila_nuevo; ?>";
		inputcampo=inputcampo.replace(/__FILA/g,"_"+filas);		
		
		
		//inputcampo=inputcampo.replace(/:/g,"-");
		//alert(inputcampo);		
		r.innerHTML=inputcampo;
		r=null;
		tbldetalles=null;
		//alert(r);
		//alert(inputcampo);
		
		//Aplica el botón si es que hay fechas
		activa_fecha(filas);				
		
		//Aplica las máscaras		
		aplicar_mascara_detalle(filas);
		
		//r.innerHTML="<td>hola1</td><td>hola 2</td>";
		
		//var nuevaCelda = r.insertCell(0);
		//nuevaCelda.innerHTML = "<input type='text'>";		
	}
	//////////////////////////////////////////////////////////////////////
	
	

	
	function activa_fecha(fila){
		<?php
			if($script_fechas!=""){
				$script_fechas = str_ireplace("__FILA","_'+fila+'",$script_fechas);								
				echo $script_fechas;								
			}				
		?>		
	}
	
	
	function aplicar_mascara_detalle(fila){
		<?php echo $script_inputmask; ?>
		
		loadimask();
	}
	
	function quitartodaslasfilas(){
		//alert("lo siento debo quitar to_do ---> cargandofilasiniciales="+cargandofilasiniciales);
		if(!cargandoparaeditar){
			for(i=filastabla;i>=1;i--){
				//alert(i+"ok");
				quitar(i);
				//alert(i+"eliminado");
			}				
		}
	}
	
	function quitar(fila){
		var tbldetalles = document.getElementById("tbldetalles");
		tbldetalles.deleteRow(fila);
		filastabla=filastabla-1;
		
		//filas=filas-1;		
		//var txtfilasdetalles = document.getElementById("txt_filasdetalles");
		//txtfilasdetalles.value=filas;
		
		//alert('<?php echo $imask_en_detalle; ?>');
		
		<?php if($imask_en_detalle){  ?>
			ultimo_campo = ultimo_campo-<?php echo $cuantos_formato; ?>;
		<?php } ?>
		
		//alert(ultimo_campo);
	}
	
	function calcula_formulas_detalles(){
		for(i=1;i<=filas;i++){
			//alert(i);
			<?php echo $script_calculaformulas; ?>
		}
	}
	
	
	function dependenciasporomision_detalles(){
		//alert('dependencias por omision');
			cfi=cargandofilasiniciales;
			cargandofilasiniciales=false;
		<?php echo $script_detalles_dependenciascargar; ?>		
			cargandofilasiniciales=cfi;
	}

	function dependenciascompuestas_detalles(idcampo){
		    
			//alert(idcampo+" "+cargandofilasiniciales);
			
			var sidcampo = ""+idcampo;
			var filaactual = sidcampo.substring(sidcampo.indexOf("_")+1);	
			
			//alert(" idcampo="+sidcampo+" filaactual="+filaactual+"  cargandofilasiniciales="+cargandofilasiniciales);	
			
			//alert(sidcampo+"   FILA:"+filaactual+"  VALOR:"+$('#i'+sidcampo).attr('value')+"  INDEX:"+$('#i'+sidcampo).attr('selectedIndex')); 				
			<?php echo $script_detalles_dependenciacompuesta; ?>	
					
			
	}
	
	
	
	function suma_campo_detalles(idcampo){		
		var total = 0;
		//alert("filas: "+filas);
		for(i=1;i<=filas;i++){
			if(document.getElementById('i'+idcampo+'_'+i)!=null){
				total+=regresanumero(document.getElementById('i'+idcampo+'_'+i).value);
			}
			//alert("fila: "+i+"  TOTAL:"+total);
		}		
		return total;
	}
	
	
	function seleccionacombo(combo,dato){
		for (var o=0;o<combo.length;o++){ 
       		if(combo[o].value==dato){ 
       	 		combo.selectedIndex=o; 
       	 	} 
	   	}				
	}
	
	function existe_combo(id_combo){
		
	}
	
	function verifica_existencia(){
		
	}
	
	
	//Esta función carga los datos iniciales en caso de edición en el detalle
	function carga_datos_iniciales(){
		//alert("entre");
		var cmb;
		cargandofilasiniciales = false;
		
		<?php
		
			//EDICION
			if($a==0){
				
				$script_cargacombos="";
				
				$sqldatosdetalles = " select * 
						      from ".$_SESSION["nombreestructuradetalle"]."
                                                      where ".$_SESSION["campofolio"]."='".$VALORCAMPOFOLIO."'									  
						  ";
				//echo "alert('".$sqldatosdetalles."');";
				$resultdatosdetalles = $conexion->consultar($sqldatosdetalles);
				while($rsdatosd = $conexion->siguiente($resultdatosdetalles)){
					
					//Agrega la fila en el detalle
					echo " agregar(); \n ";
					//echo " alert('agregue fila'); ";						
					
					//Registra el campo idlinea
					//echo " document.getElementById('idlinea_'+filas).value=".$rsdatosd{$_SESSION["campoidlinea"]}.";  \n ";
					
					//registra los campos
					$controlesd = $_SESSION['controlesd'];
					$nombrescamposd = $controlesd->getcampos();
					foreach ($nombrescamposd as $nombrecampo => $idcampo){
                                            
                                            
                                            
                                                $permiso_parciallog = $parciallog_detalle->get_permiso($nombrecampo);                    
                                                if($permiso_parciallog=="O"){
                                                     
                                                    echo " document.getElementById('i".$idcampo."_'+filas).value='".$rsdatosd{$nombrecampo}."'; \n ";
                                                     
                                                } else if ($permiso_parciallog=="L"){
                                                    
                                                    echo " document.getElementById('i".$idcampo."_'+filas).value='".$rsdatosd{$nombrecampo}."'; \n ";
                                                    echo " document.getElementById('i".$idcampo."dis_'+filas).innerHTML='".$rsdatosd{$nombrecampo}."'; \n ";
                                                    
                                                } else {
                                                                                        
                                                    $para_grabar=$controlesd->getgrabar($nombrecampo);
                                                    switch($para_grabar){
                                                            /*
                                                            case "auto_increment":
                                                            */

                                                            case "archivo":
                                                            		echo " $('#i".$idcampo."_'+filas).append('".$rsdatosd{$nombrecampo}."'); \n ";
                                                            		$ahref="../descarga_archivo_fisico.php?d=1&f=".$rsdatosd{$nombrecampo}."&ne=".$_SESSION['nombreestructura'];
                                                            		echo " $('#i".$idcampo."_'+filas).attr('href','".$ahref."'); \n ";
                                                            		echo " $('#i".$idcampo."_'+filas).attr('target','_blank'); \n ";
                                                                    echo " document.getElementById('i".$idcampo."_'+filas).value='".$rsdatosd{$nombrecampo}."'; \n ";
                                                                    echo " document.getElementById('i".$idcampo."_'+filas+'dato').value='".$rsdatosd{$nombrecampo}."'; \n ";
                                                            		break;

                                                            case "varchar":
                                                                    $datose=str_ireplace(chr(13),"\\n",$rsdatosd{$nombrecampo});
                                                                    $datose=str_ireplace(chr(10),"",$datose);
                                                                    echo " document.getElementById('i".$idcampo."_'+filas).value='".$datose."'; \n ";
                                                                    break;

                                                                    //echo " document.getElementById('i".$idcampo."_'+filas).value='".$rsdatosd{$nombrecampo}."'; \n ";
                                                                    //break;

                                                            case "boolean":
                                                                    echo " document.getElementById('i".$idcampo."_'+filas).value='".$rsdatosd{$nombrecampo}."'; \n ";
                                                                    break;

                                                            case "select":								
                                                                    echo " campovaloromision = document.getElementById('i".$idcampo."_'+filas+'_omision'); \n ";
                                                                    echo " campovaloromision.value = '".$rsdatosd{$nombrecampo}."'; \n ";
																																		//error_log("[doclog/f_detalles.php:399]\nnombrecampo:".$nombrecampo);
                                                                    //echo " alert(sidcampo+'_omision -- cargando: '+campovaloromision.value); ";

                                                                    //$script_cargacombos.=" if(document.getElementById('i".$idcampo."_'+filas)==null){ alert('no esta el combo'); }; \n ";
                                                                    $script_cargacombos.=" cmb = document.getElementById('i".$idcampo."_'+filas); \n ";
                                                                    //$script_cargacombos.=" alert(cmb+'".$idcampo."=".$rsdatosd{$nombrecampo}."'); ";								
                                                                    $script_cargacombos.=" seleccionacombo(cmb,'".$rsdatosd{$nombrecampo}."'); \n ";	
                                                                    $script_cargacombos.=" cargandofilasiniciales=false; \n ";	
                                                                    //$script_cargacombos.=" alert(".$idcampo."_'+filas) \n ";																								
                                                                    $script_cargacombos.=" dependenciascompuestas_detalles('".$idcampo."_'+filas); \n ";
                                                                    $script_cargacombos.=" cargandofilasiniciales=true; \n ";				
                                                                    break;

                                                            case "bigint":
                                                                    echo " document.getElementById('i".$idcampo."_'+filas).value='".$rsdatosd{$nombrecampo}."'; \n ";
                                                                    break;

                                                            case "int":
                                                                    echo " document.getElementById('i".$idcampo."_'+filas).value='".$rsdatosd{$nombrecampo}."'; \n ";
                                                                    break;

                                                            case "double":
                                                                    echo " document.getElementById('i".$idcampo."_'+filas).value='".$rsdatosd{$nombrecampo}."'; \n ";
                                                                    break;

                                                            case "date":
																																			
																																		$dia=""; $mes=""; $anual="";
																																		$hora="";$minutos="";$segundos="";$ampm="";
																																		
																																		
																																		if(strtotime($rsdatosd{$nombrecampo})!="0000-00-00 00:00:00"){
                                                                    	$fecha_m = strtotime($rsdatosd{$nombrecampo});
                                                                    	$dia = date("d",$fecha_m);
                                                                    	$mes = date("m",$fecha_m);
                                                                    	$anual = date("Y",$fecha_m);							
																																		}

                                                                    echo " document.getElementById('i".$idcampo."_'+filas+'_2').value='".$dia."'; \n ";
                                                                    echo " document.getElementById('i".$idcampo."_'+filas+'_1').value='".$mes."'; \n ";
                                                                    echo " document.getElementById('i".$idcampo."_'+filas+'_3').value='".$anual."'; \n ";							
                                                                    break;

                                                            case "time":							

		
																																		$dia=""; $mes=""; $anual="";
																																		$hora="";$minutos="";$segundos="";$ampm="";
																																		
																																		
																																		
																																		if(strtotime($rsdatosd{$nombrecampo})!="0000-00-00 00:00:00"){
                                                                    	$hora_m = strtotime($rsdatosd{$nombrecampo});
                                                                    	$hora = "0".date("h",$hora_m);		
                                                                    	if(strlen($hora)==3) $hora=date("h",$hora_m);
                                                                    	$minutos = "0".date("i",$hora_m);
                                                                    	if(strlen($minutos)==3) $minutos=date("i",$hora_m);		
                                                                    	$ampm = date("A",$hora_m);
																																		}

                                                                    echo " document.getElementById('i".$idcampo."_'+filas+'t').value='".$hora.":".$minutos."'; \n ";
                                                                    echo " cmb = document.getElementById('i".$idcampo."_'+filas+'ampm'); \n ";
                                                                    echo " seleccionacombo(cmb,'".strtolower($ampm)."'); \n ";																	
                                                                    break;

                                                            case "datetime":
																																		
																																	
																																		$dia=""; $mes=""; $anual="";
																																		$hora="";$minutos="";$segundos="";$ampm="";
																																		
																																		
																																		if(strtotime($rsdatosd{$nombrecampo})!="0000-00-00 00:00:00"){
                                                                    	$fecha_m = strtotime($rsdatosd{$nombrecampo});
                                                                    	$dia = date("d",$fecha_m);
                                                                    	$mes = date("m",$fecha_m);
                                                                    	$anual = date("Y",$fecha_m);							
																																		}

                                                                    echo " document.getElementById('i".$idcampo."_'+filas+'_2').value='".$dia."'; \n ";
                                                                    echo " document.getElementById('i".$idcampo."_'+filas+'_1').value='".$mes."'; \n ";
                                                                    echo " document.getElementById('i".$idcampo."_'+filas+'_3').value='".$anual."'; \n ";

	
																																		if(strtotime($rsdatosd{$nombrecampo})!="0000-00-00 00:00:00"){
                                                                    	$hora_m = strtotime($rsdatosd{$nombrecampo});
                                                                    	$hora = "0".date("h",$hora_m);		
                                                                    	if(strlen($hora)==3) $hora=date("h",$hora_m);
                                                                    	$minutos = "0".date("i",$hora_m);
                                                                    	if(strlen($minutos)==3) $minutos=date("i",$hora_m);		
                                                                    	$ampm = date("A",$hora_m);
																																		}

                                                                    echo " document.getElementById('i".$idcampo."_'+filas+'t').value='".$hora.":".$minutos."'; \n ";
                                                                    echo " cmb = document.getElementById('i".$idcampo."_'+filas+'ampm'); \n ";
                                                                    echo " seleccionacombo(cmb,'".strtolower($ampm)."'); \n ";														
                                                                    break;


                                                            case "datetime_seg":
																																		
																																	
																																		$dia=""; $mes=""; $anual="";
																																		$hora="";$minutos="";$segundos="";$ampm="";

	
																																		if(strtotime($rsdatosd{$nombrecampo})!="0000-00-00 00:00:00"){
                                                                    	$fecha_m = strtotime($rsdatosd{$nombrecampo});
                                                                    	$dia = date("d",$fecha_m);
                                                                    	$mes = date("m",$fecha_m);
                                                                    	$anual = date("Y",$fecha_m);							
																																		}

                                                                    echo " document.getElementById('i".$idcampo."_'+filas+'_2').value='".$dia."'; \n ";
                                                                    echo " document.getElementById('i".$idcampo."_'+filas+'_1').value='".$mes."'; \n ";
                                                                    echo " document.getElementById('i".$idcampo."_'+filas+'_3').value='".$anual."'; \n ";


																																		if(strtotime($rsdatosd{$nombrecampo})!="0000-00-00 00:00:00"){
                                                                    	$hora_m = strtotime($rsdatosd{$nombrecampo});
                                                                    	$hora = "0".date("h",$hora_m);		
                                                                    	if(strlen($hora)==3) $hora=date("h",$hora_m);
                                                                    	$minutos = "0".date("i",$hora_m);				
                                                                    	if(strlen($minutos)==3) $minutos=date("i",$hora_m);		
                                                                    	$segundos = "0".date("s",$hora_m);				
                                                                    	if(strlen($segundos)==3) $segundos=date("s",$hora_m);
                                                                    	$ampm = date("A",$hora_m);
																																		}

                                                                    echo " document.getElementById('i".$idcampo."_'+filas+'t').value='".$hora.":".$minutos.":".$segundos."'; \n ";
                                                                    echo " cmb = document.getElementById('i".$idcampo."_'+filas+'ampm'); \n ";
                                                                    echo " seleccionacombo(cmb,'".strtolower($ampm)."'); \n ";														
                                                                    break;	

                                                            case "datetime_seg_hr":
																																		
																																		if(strtotime($rsdatosd{$nombrecampo})!="0000-00-00 00:00:00"){
                                                                    	$fecha_m = strtotime($rsdatosd{$nombrecampo});
                                                                    	$dia = date("d",$fecha_m);
                                                                    	$mes = date("m",$fecha_m);
                                                                    	$anual = date("Y",$fecha_m);							
																																		}

                                                                    echo " document.getElementById('i".$idcampo."_'+filas+'_2').value='".$dia."'; \n ";
                                                                    echo " document.getElementById('i".$idcampo."_'+filas+'_1').value='".$mes."'; \n ";
                                                                    echo " document.getElementById('i".$idcampo."_'+filas+'_3').value='".$anual."'; \n ";

																																		if(strtotime($rsdatosd{$nombrecampo})!="0000-00-00 00:00:00"){
                                                                    	$hora_m = strtotime($rsdatosd{$nombrecampo});
                                                                    	$hora = "0".date("H",$hora_m);		
                                                                    	if(strlen($hora)==3) $hora=date("H",$hora_m);
                                                                    	$minutos = "0".date("i",$hora_m);				
                                                                    	if(strlen($minutos)==3) $minutos=date("i",$hora_m);		
                                                                    	$segundos = "0".date("s",$hora_m);				
																																		}

                                                                    echo " document.getElementById('i".$idcampo."_'+filas+'t').value='".$hora.":".$minutos.":".$segundos."'; \n ";
                                                                    //echo " cmb = document.getElementById('i".$idcampo."_'+filas+'ampm'); \n ";
                                                                    //echo " seleccionacombo(cmb,'".strtolower($ampm)."'); \n ";														
                                                                    break;							


                                                    } //switch($para_grabar)
                                                
                                                } //PARCIALLOG
						
																								
					} //ciclo de campos		//// fin registra campos
					
					
					
										
					echo $script_cargacombos;
					$script_cargacombos="";	
					//echo " alert('fila cargada'); \n ";			
						
					
					
				} //while($rsdatosd = $conexion->siguiente($resultdatosdetalles))
												
			}						
		
		?>
		
		
		
		cargandofilasiniciales=false;
			
	} //function carga_datos_iniciales()
	
		
	<?php 
	
	
		//Si el título no tiene dependencias compuestas entonces 
		//los datos iniciales se mandaran llamar inmediatamente
		if(($a==0)&&($ultimocampocondependenciacompuesta==="")){
			echo "carga_datos_iniciales();"; 
		} 					
		
	?>
	
</script>
