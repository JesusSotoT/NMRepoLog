<?php

	$idcampo = $_POST['ic'];
	$campovalor = $_POST['cv'];
	$campodesc = $_POST['cd'];
	$dependenciatabla = $_POST['dt'];
	$sqlw = $_POST['sw'];
		//echo "recibí:".$_SERVER["REQUEST_URI"]."   sqlw:".$sqlw;
			if(!isset($sql_exec)) $sql_exec="";
	$sqlw = str_replace("\\","",$sqlw);

	if(strpos($sqlw,"%3D")!==false) $sqlw = urldecode($sqlw);
	error_log("[doclog/f_detalles_dependenciacompuesta.php:34]\nsqlw:".$sqlw);
		//echo " -- id:".$idcampo." sw:".$sqlw;
	
	$seleccionado_m = $_POST['sm'];
	$deshabilitado = $_POST['de'];
	$formato = $_POST['fo'];


	//echo "cargando... ".$idcampo." ".$campovalor." ".$campodesc." ".$dependenciatabla." ".$sqlw;
	
	//include("conexionbd.php");
	include("../catalog/conexionbd.php");
         
	
            $objeto="<select style='width:200px' id='i".$idcampo."' name='i".$idcampo."' class='seleccion' onchange='campo_onchange(this,true)' ".$deshabilitado." >";		     



										$campodesc=str_replace(",",",' ',",$campodesc);	
										$campodesc=" concat(".$campodesc.") as catalog_campodesc ";	

                    $sql="select ".$campovalor.", ".$campodesc." from ".$dependenciatabla." where ".$sqlw." order by catalog_campodesc";
                    
										$campodesc="catalog_campodesc";
										error_log("[doclog/f_detalles_dependenciacompuesta.php:34]\n".$sql);
                    $rsdependenciasimple = $conexion->consultar($sql);

                    $inicio=1;

                    $encontrealgo = " encontrando ... ";
                    while($regsimple=$conexion->siguiente($rsdependenciasimple)){
                            $encontrealgo.= "1";

                            $selecciona_m="";
                            if($seleccionado_m!=""){
                                    if($regsimple{$campovalor}==$seleccionado_m){
                                            $selecciona_m="selected";
                                    } else {
                                            $selecciona_m="";
                                    }
                            } else {
                                    if($inicio==1){
                                            $selecciona_m="selected";
                                            $inicio=0;
                                    }					
                            }			

                            $datoenelcombo = $regsimple{$campodesc}; 
                            if(($formato=="$")||($formato=="$.00")){
                                    $datoenelcombo = "$ ".number_format($datoenelcombo);
                            }

                            if($formato=="0.00"){
                                    $datoenelcombo = number_format($datoenelcombo);
                            }			

                            $objeto.="<option value='".$regsimple{$campovalor}."' ".$selecciona_m." >".$datoenelcombo."</option>";				
                    }
                    $conexion->cerrar_consulta($rsdependenciasimple);

            $objeto.="</select>";

            //$encontrealgo="si entre y use:".$sqlw.$encontrealgo;
        

	echo $objeto;
	
	$conexion->cerrar();
	
	
	
?>
