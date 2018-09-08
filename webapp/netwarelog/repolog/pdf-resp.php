<?php
/* 
 * Este modulo arma el sql de un id dado
 */

	$pf="100";
	$pf = $_GET["p"]; //Porcentaje de fuente
	if($pf=="") $pf="100";
	if(isset($pf)==false) $pf="100";
	if($pf==null) $pf="100";
	if($pf==0) $pf="100";

    session_start();
    $idorg = $_SESSION["accelog_idorganizacion"];
    $sql = $_SESSION["sequel"];
    $descripcion = $_SESSION["desc"];
    $idestiloomision = $_SESSION["iestilo"];
    $url_include = $_SESSION["url_include"];

    include("parametros.php");  

    //OBTENCION DE FILTROS SELECCIONADOS EN MODO HUM
    $filtros_seleccionados_tit = "";
    $filtros_seleccionados_tr = "";
    
    if(isset($_SESSION["repolog_filtros"])){
        $filtros_etiquetas =$_SESSION["repolog_filtros"];
        $filtros_valores_hum = $_SESSION["repolog_valores_hum"];
        $filtros_cuantos = $_SESSION["repolog_cuantos"];
    } else {
        $filtros_cuantos = 0;
    }
    

	$incluir = 1;
    for($i = 1; $i<=$filtros_cuantos; $i++){
		
		$incluir=1;
		
        if($filtros_valores_hum[$i]!=""){
            $pos_barra=strrpos($filtros_etiquetas[$i],"#");
            if(is_numeric($pos_barra)){
                $filtros_seleccionados_tit.=strtoupper(substr($filtros_etiquetas[$i],1));
                $filtros_seleccionados_tr.=strtoupper(substr($filtros_etiquetas[$i],1));
            } else {

                    $pos_barra=strrpos($filtros_etiquetas[$i],"@");
                    if(is_numeric($pos_barra)){

                        $caracter_pregunta = ";";
                        $pos_etiqueta=strpos($filtros_etiquetas[$i],$caracter_pregunta);                                               
                        $pos_barra+=1;
                        $etiqueta = substr($filtros_etiquetas[$i], $pos_barra, $pos_etiqueta-$pos_barra);                                                                                                
                        $filtros_seleccionados_tit.= strtoupper($etiqueta);
                        $filtros_seleccionados_tr.= strtoupper($etiqueta);

                    } else {

			            $pos_barra=strrpos($filtros_etiquetas[$i],"!");
			            if(is_numeric($pos_barra)){
							$incluir = 0;
							//ESTOS NO SE IMPRIMEN POR SER SESION 
							//QUEDA EL CODIGO POR SI ALGUNA VEZ SE DECIDE DEJARLOS
			                //$filtros_seleccionados_tit.=strtoupper(substr($filtros_etiquetas[$i],1));
			                //$filtros_seleccionados_tr.=strtoupper(substr($filtros_etiquetas[$i],1));
			            } else {
	                        $filtros_seleccionados_tit.= strtoupper($filtros_etiquetas[$i]);
	                        $filtros_seleccionados_tr.= strtoupper($filtros_etiquetas[$i]);
						}

                    }

            }
			if($incluir==1){
	            $filtros_seleccionados_tit.= "=<b>".strtoupper($filtros_valores_hum[$i])."</b> &nbsp; ";
	            $filtros_seleccionados_tr.= "= ".strtoupper($filtros_valores_hum[$i])."   ";				
			}

        }

    }



    //REGISTRO TRANSACCIONES -- 2010-10-01
    if($filtros_seleccionados_tr!=""){
        $filtros_seleccionados_tr="   Filtros -  ".$filtros_seleccionados_tr;
    }
    $conexion->transaccion("REPOLOG - ".$descripcion."  ".$filtros_seleccionados_tr,$sql);



	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	/////////////INICIA LA PAGINA/////////////////////////////////////////////////////////////////////////////////////
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	

	$html="";
	$html.= "<html>";
	$html.= "<head>";
		$html.= "<LINK href='../utilerias/css_repolog/estilo-".$idestiloomision.".css' title='estilo' rel='stylesheet' type='text/css' />";
		$html.= "<meta http-equiv='Content-Type' content='text/html;charset=utf-8'>";
		$html.= "<meta name='author-icons' content='Rachel Fu'>";
	$html.= "</head>";
	$html.= "<body>";
	$html.= "<font face='helvetica'>";
		
	$html.="<table width='100%'>
				<tr>
					<td><img src='../utilerias/img_org/".$idorg.".gif'></td>
					<td align='right'><font color='gray' face='helvetica' size=1><b>".date('d/m/Y h:i:s A')."</b></font></td>
				</tr>
			</table>";
		
	$html.="<br><br>";
	
	//PROCESO
    if($url_include!=null){
        if($url_include!=""){
           include($url_include);
        }
    }


	$html.="<center>";
	
	//DESCRIPCION Y FILTROS
	$html.="	
        <table>
            <tr>
            <td align='center'>
                <font size='3' face='helvetica'  color='gray'><b>".$descripcion."<br></b></font>
                <font size='1' face='helvetica'  color='gray'>".$filtros_seleccionados_tit."</font></td>
            </tr>
        </table>
			";
	
	//EMPIEZA EL REPORTE
	$html.="<table class='reporte'>
        	<tbody>";
                
				/////////////////////////////////////////////////////////////
				/////////////////////////////////////////////////////////////
				//SQL DEL REPORTE////////////////////////////////////////////
				/////////////////////////////////////////////////////////////
				$resultado = $conexion->consultar($sql);
				
				/////////////////////////////////////////////////////////////
				/////////////////////////////////////////////////////////////
				/////////////////////////////////////////////////////////////

                
	$html.="<tr class='trencabezado' height='10' >";
           $i=0;
           while($i < mysql_num_fields($resultado)){
               $meta = mysql_fetch_field($resultado, $i);
               if(!$meta){
                   $html.="información no disponible";
               } else {
				   $html.="<td><font face='helvetica' style='font-size:".$pf."%'>".$meta->name."</font></td>";
               }
               $i++;
           }
    $html.="</tr>";


	//CICLO DE LLENADO
				$valortotal="Sub Total";				
                while($rs = $conexion->siguiente($resultado)){					
                                    $linea="";
                                    $cambiaestilo=false;
						
                                    $i=0;
                                    while($i < mysql_num_fields($resultado)){
                                        $meta = mysql_fetch_field($resultado, $i);
                                        if(!$meta){		
                                            $html.="información no disponible";
                                        } else {


                                            $d = $rs{$meta->name};
					    if(strtolower($d)=="sub total"||strtolower($d)=="subtotal"||strtolower($d)=="total"){
						$cambiaestilo=true;                                                
                                            }

                                            if(strpos($d,"TOTAL")!=false){
                                                $cambiaestilo=true;
                                            }


                                            $estilotd = "tdcontenido";
                                            if($cambiaestilo){
                                                $estilotd = "tdsubtotal";
                                            } else {

                                                $signopesos = strpos($rs{$meta->name},"$"); //echo "      --".$rs{$meta->name}."  ".$signopesos."--    ";
                                                if($signopesos !== false){
                                                    $estilotd = "tdmoneda";
                                                }

                                                $signoporcentaje = strpos($rs{$meta->name},"%");
                                                if($signoporcentaje !== false){
                                                    $estilotd = "tdmoneda";
                                                }

                                            }
                                            $linea.="<td class='".$estilotd."' title='".$meta->name."'><font face='helvetica' style='font-size:".$pf."%'>".$rs{$meta->name}."</font></td>";

                                        }
                                        $i++;
                                    }
						
                                    if($cambiaestilo){
                                            $linea = "<tr bgcolor='#ececec' class='trsubtotal'><font face='helvetica' size=2>".$linea."</font></tr>";
                                    } else {
                                            $linea = "<tr class='trcontenido'><font face='helvetica' size=2>".$linea."</font></tr>";
                                    }
				    $html.=$linea;

                }

                $conexion->cerrar_consulta($resultado);


	$html.="</tbody>";
	$html.="</table>";
	//CONCLUYE TABLA DE LLENADO
	
	$html.="</center>";
	$html.="</font>";
	$html.="</body>";
	$html.="</html>";

	require_once("dompdf-0.5.1/dompdf_config.inc.php");
	/* ESTE CODIGO ES DE EJEMPLO
	$html =
	  '<html><body>'.
	  '<p>Put your html here, or generate it with your favourite '.
	  'templating system.</p>'.
	  '</body></html>';
	*/

	ini_set("memory_limit",$tamano_buffer);  // Configurable webconfig
	
	$dompdf = new DOMPDF();
	$dompdf->set_paper('letter', 'landscape');
	$dompdf->load_html($html);
	$dompdf->render();
	$dompdf->stream($_SESSION["nombrereporte"]."-".date('Y-m-d--h-i-s-A').".pdf");

?>
<html>
	PDF GENERADO
	
	
</html>



