<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

    session_start();
    $idorg = $_SESSION["accelog_idorganizacion"];
    $sql = $_SESSION["sequel"];
    $idestiloomision = $_SESSION["iestilo"];
    $descripcion = $_SESSION["desc"];

    include("parametros.php");

		//CSRF
		$reset_vars = true;
		include "../catalog/clases/clcsrf.php";


    //Obteniendo etiquetas y posición
    $filtros_etiquetas = array();
    $filtros_posicion_inicio = array();
    $filtros_posicion_fin = array();
    $filtros_cuantos = 0;
    

    //Recorriendo cadena de sql
    $armando_parametro = false;
    $posicion_inicio = 0;
    $etiqueta = "";
    //echo $sql;
    for($i = 0; $i<=strlen($sql); $i++){
        $caracter = substr($sql,$i,1);
        //echo $caracter;
        if($armando_parametro){
            if($caracter=="]"){

                $filtros_cuantos++;
                $filtros_etiquetas[$filtros_cuantos] = $etiqueta;
                $filtros_posicion_inicio[$filtros_cuantos] = $posicion_inicio;
                $filtros_posicion_fin[$filtros_cuantos] = $i;

                $armando_parametro = false;
                $posicion = 0;
                $etiqueta = "";

            } else {
                $etiqueta.=$caracter;
            }
        }
        if($caracter=="["){
            $armando_parametro = true;
            $posicion_inicio = $i;
        }

    }

    $_SESSION["filtros_cuantos"] = $filtros_cuantos;
    $_SESSION["filtros_etiquetas"] = $filtros_etiquetas;
    $_SESSION["filtros_posicion_inicio"] = $filtros_posicion_inicio;
    $_SESSION["filtros_posicion_fin"] = $filtros_posicion_fin;
    

?>
<html lang="sp">
	<head>

                <!--CSS-->
                <!--LINK href="../utilerias/css_repolog/estilo-<?php echo $idestiloomision; ?>.css" title="estilo" rel="stylesheet" type="text/css" /-->
                <LINK href="<?php echo $url_catalog; ?>/css/view.css" title="estilo" rel="stylesheet" type="text/css" />
        <?php include('../design/css.php');?>
        <LINK href="../design/<?php echo $strGNetwarlogCSS;?>/netwarlog.css" title="estilo" rel="stylesheet" type="text/css" /> <!--NETWARLOG CSS-->
                <!--JS-->
		<script type="text/javascript" src="<?php echo $url_catalog; ?>/js/view.js"></script>
		<script type="text/javascript" src="js/calendar.js"></script>

        <script type="text/javascript" src="../catalog/js/jquery.js"></script>

		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<!--TITULO VENTANA-->
		<title><?php echo $descripcion; ?></title>
		<meta name="generator" content="Netbeans">
		<meta name="author" content="Omar Mendoza"><!-- Date: 2010-08-07 -->
		<meta name="author-icons" content="Rachel Fu"><!-- Date: 2010-08-07 -->
	</head>

	<body>

    <div style="" class=" nmwatitles "><?php echo $descripcion; ?></div>
            <br><br>
            
            <form id="frmfiltros" action="filtros_procesar.php" method="post">
								<?php 
									//CSRF - FORM
									echo $csrf->input_token($token_id,$token_value);	 
								?>
                <table>
                    <tbody>
                        <?php
							
							
							//Si solo hay filtros de session cargarlos y continuar inmediatamente.
							$algunfiltro = 0;

                            for($i = 1; $i<=$filtros_cuantos; $i++){
                              ?>
                                <tr>

                                    <?php
                                        $caracter_pregunta = "#";
                                        
                                        //echo $filtros_etiquetas[$i]."   ".$caracter_pregunta;
                                        //echo strrpos($filtros_etiquetas[$i],$caracter_pregunta);

                                        $pos_barra=strrpos($filtros_etiquetas[$i],$caracter_pregunta);
										
										
										//FILTRO TIPO FECHA
                                        if(is_numeric($pos_barra)){
											$algunfiltro=-1;
                                            ?>

                                                    <td class="nmrepologlabel">
                                                        <?php
                                                            $etiqueta = $filtros_etiquetas[$i];
                                                            echo substr($etiqueta,1);
                                                        ?>
                                                    </td>

                                                    <td>

														<input class="nminputtext" id='f<?php echo $i; ?>_2' name='f<?php echo $i; ?>_2' title='Día'
														    size='2' maxlength='2' value='<?php echo date("d"); ?>' type='text'> /

														<input class="nminputtext" id='f<?php echo $i; ?>_1' name='f<?php echo $i; ?>_1' title='Mes'
														    size='2' maxlength='2' value='<?php echo date("m"); ?>' type='text'> /

										 				<input class="nminputtext" id='f<?php echo $i; ?>_3' name='f<?php echo $i; ?>_3' title='Año'
										 				    size='4' maxlength='4' value='<?php echo date("Y"); ?>' type='text'>
									
														&nbsp;<img id='f<?php echo $i; ?>_img' style="display: inline; vertical-align: middle;" src='../design/default/calendar.png'
														alt='Seleccione una fecha.' title='Haga clic para seleccionar una fecha.'>&nbsp;

														<script type='text/javascript'>

															Calendar.setup({
																inputField	 : 'f<?php echo $i; ?>_3',
																baseField    : 'f<?php echo $i; ?>',
																displayArea  : 'f<?php echo $i; ?>_area',
																button		 : 'f<?php echo $i; ?>_img',
																ifFormat	 : '%B %e, %Y',
																onSelect	 : selectDate
															});

														</script>
                                                                                                        
                                                    </td>
                                             <?php
                                        } else {


                                                //FILTRO DE TIPO SQL
                                                $caracter_pregunta = "@";
                                                $pos_barra=strrpos($filtros_etiquetas[$i],$caracter_pregunta);
                                                if(is_numeric($pos_barra)) {
													$algunfiltro=-1;
                                                    $caracter_pregunta = ";";
                                                    $pos_etiqueta=strpos($filtros_etiquetas[$i],$caracter_pregunta);
                                                    //echo "filtros_etiquetas=".$filtros_etiquetas[$i]."<br>";

                                                    $pos_barra+=1;
                                                    //echo "pos_barra=".$pos_barra;
                                                    $etiqueta = substr($filtros_etiquetas[$i], $pos_barra, $pos_etiqueta-$pos_barra);

                                                    ?>
                                                        <td class="nmrepologlabel">
                                                            <?php
                                                                echo $etiqueta;
                                                            ?>
                                                        </td>
                                                     <?php

                                                     $pos_campovalor = strpos($filtros_etiquetas[$i], $caracter_pregunta,$pos_etiqueta+1);
                                                     $campovalor = substr($filtros_etiquetas[$i],$pos_etiqueta+1,$pos_campovalor-$pos_etiqueta-1);
                                                     //echo "campovalor=".$campovalor;

                                                     $pos_campodescripcion = strpos($filtros_etiquetas[$i], $caracter_pregunta,$pos_campovalor+1);
                                                     $campodescripcion = substr($filtros_etiquetas[$i],$pos_campovalor+1,$pos_campodescripcion-$pos_campovalor-1);
                                                     //echo "<br>campodescripcion=".$campodescripcion;
                                                     
                                                     $sql_dependencia = substr($filtros_etiquetas[$i],$pos_campodescripcion+1);
                                                     //echo "<br>sql=".$sql_dependencia;

                                                     ?>
                                                     <td>
                                                         
                                                         <input type="hidden" name="dep<?php echo $i; ?>" id="dep<?php echo $i; ?>">
                                                         <script type="text/javascript">
                                                             function llena<?php echo $i; ?>(){
                                                                 var dep<?php echo $i; ?> = document.getElementById("dep<?php echo $i; ?>");
                                                                 var txt<?php echo $i; ?> = document.getElementById("txt<?php echo $i; ?>");
                                                                 dep<?php echo $i; ?>.value = txt<?php echo $i; ?>.options[txt<?php echo $i; ?>.selectedIndex].text;
                                                             }                                                             
                                                         </script>
                                                         <select class="nminputselect" name="txt<?php echo $i; ?>" id="txt<?php echo $i; ?>" onchange="llena<?php echo $i; ?>()">
                                                             <?php

                                                                 //EJECUTA EL SQL DEL VALIDABLE

                                                                 $result = $conexion->consultar($sql_dependencia);
                                                                 while($rs = $conexion->siguiente($result)){
                                                                    ?>
                                                                    <option value="<?php echo $rs{$campovalor}; ?>" ><?php echo $rs{$campodescripcion}; ?></option>
                                                                     <?php
                                                                 }
                                                             ?>
                                                         </select>
                                                         <script type="text/javascript">
                                                             llena<?php echo $i; ?>();
                                                          </script>
                                                     <?php

                                                     $conexion->cerrar_consulta($result);

                                                     ///////////////////////
                                                } else {


	                                                //FILTRO DE LA SESSION
	                                                $caracter_pregunta = "!";
	                                                $pos_barra=strrpos($filtros_etiquetas[$i],$caracter_pregunta);
	                                                if(is_numeric($pos_barra)) {
		
														//FILTRO PARA LA SESION
														?>
														<input name="txt<?php echo $i; ?>" 
														type="hidden" 
														value="<?php echo $_SESSION[substr($filtros_etiquetas[$i],1)]; ?>">
														<?php
		
													} else {
														$algunfiltro=-1;
														
	                                                    //FILTRO DE TIPO NORMAL SOLO TEXTO O NUMERO
	                                                    ?>
	                                                    <td class="nmrepologlabel">
	                                                        <?php
	                                                            echo $filtros_etiquetas[$i];
	                                                        ?>
	                                                    </td>
	                                                    <td><input class="nminputtext" name="txt<?php echo $i; ?>" id="txt<?php echo $i; ?>" type="text"></td>
	                                                    <?php
	                                                    ///////////////////////////
														
													}

                                                }
                                        }
                                    ?>
                                </tr>
                              <?php
                            }
                        ?>
                        <tr>
                            <th colspan="2" align="right" style="padding-top: 25px;">
								<?php
									if($algunfiltro==-1){
										?>
                                			<input type="button" value="Generar" onclick="CreaReporte();" class="nminputbutton">
                                            <script type="text/javascript">
                                                function CreaReporte() {
                                                    $('#nmloader_div', window.parent.document).show();
                                                    document.getElementById("frmfiltros").submit();
                                                }
                                            </script>
										<?php
									} else {
										?>                      
										   <script type="text/javascript">
                                               $('#nmloader_div',window.parent.document).show();
                                               document.getElementById("frmfiltros").submit();
										   </script>
										<?php										
									}
								?>
	

                            </th>
                        </tr>
                    </tbody>
                </table>
                
            </form>
            
        </body>
</html>
<script>
    $('#nmloader_div',window.parent.document).hide();
</script>
