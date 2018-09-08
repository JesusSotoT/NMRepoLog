   
<?PHP 
    include("parametros.php");  
    //Redirecciona de manera manual cuando necesita algun reporte pasar algun proceso
    $reporte=0;
    $reporte=$_REQUEST["txtidreporte"];
     //FACTURACION 
	    if($reporte==15){
                //Recupera Variables de seleccion
                $vacio=empty($_REQUEST['chk']);
                 if($vacio==""){ 
                    $countos=0;
                    $whereordenes=" Where ";
                    $first="";
                    foreach ($_REQUEST['chk'] as $checkbox){ 
                        $whereordenes.=$first." os.idordenservicio=".$checkbox." ";
                        $first=" Or ";
                    }
                    $sqlos="select  os.idoperador, os.idcliente, os.idunidad from operaciones_ordenesservicio os ".$whereordenes."
                    and idestadoregistro=2 Group By os.idcliente";
                    $resultos = $conexion->consultar($sqlos);
                    while($rsos=$conexion->siguiente($resultos)){
                        $countos=$countos+1;
                    } 
                    $conexion->cerrar_consulta($resultos);
                    if($countos>1){
                        //SI NO CUMPLE CON POLITICAS LOS REGRESA
                        ?>
                            <script>
                                alert("Es necesario seleccionar ordenes de servicio del mismo cliente para facturar");
                                javascript:history.back(1);
                            </script>
                        <?PHP
                    }else{
                        //REDIRECCIONA A PROCESO
                         ?>
                            <script>
                                var pagina='../../Modulos/facturacion/generafacturas2.php?sqlwhereos=<?PHP echo $whereordenes ?>';
                                document.location.href=pagina;
                            </script>
                        <?PHP   
                    }  
                         
                }elseif($vacio==1){
                         ?>
                            <script>
                                alert("Seleccione cuando menos una orden de servicio para facturarla");
                                javascript:history.back(1);
                            </script>
                        <?PHP   
                }
    }
	
    //REPORTE DE LIQUIDACIONES SOLO PARA SISTEMA DOMAIN TRANSPORTE ELIMINAR ESTO EN CASO DE QUE NO SEA DICHO SISTEMA
    if($reporte==12){
                //Recupera Variables de seleccion
                $vacio=empty($_REQUEST['chk']);
                 if($vacio==""){ 
                    $countos=0;
                    $whereordenes=" Where (";
                    $first="";
                    foreach ($_REQUEST['chk'] as $checkbox){ 
                        $whereordenes.=$first." os.idordenservicio=".$checkbox." ";
                        $first=" Or ";
                    }
                    $whereordenes.=")";
                    $sqlos="select  os.idoperador, os.idunidad from operaciones_ordenesservicio os ".$whereordenes."
                    Group By os.idoperador";
                    $resultos = $conexion->consultar($sqlos);
                    while($rsos=$conexion->siguiente($resultos)){
                        $countos=$countos+1;
                    } 
                    $conexion->cerrar_consulta($resultos);
                    if($countos>1){
                        //SI NO CUMPLE CON POLITICAS LOS REGRESA
                        ?>
                            <script>
                                alert("Para liquidar varias Ordenes de Servicio deben pertenecer al Mismo: Operador y Unidad, Seleccione de nuevo las ordenes a liquidar");
                                javascript:history.back(1);
                            </script>
                        <?PHP
                    }else{
                        //REDIRECCIONA A PROCESO
                         ?>
                            <script>
                                var pagina='../../Modulos/liquidaciones/procesaliquidaciones/pliquidaciones2.php?sqlwhereos=<?PHP echo $whereordenes ?>';
                                document.location.href=pagina;
                            </script>
                        <?PHP   
                    }  
                         
                }elseif($vacio==1){
                         ?>
                            <script>
                                alert("Seleccione cuando menos una orden de servicio para liquidarla");
                                javascript:history.back(1);
                            </script>
                        <?PHP   
                }
    }
	
    if( $reporte == 18 ){
            $aDeposito = array();
            $sDeposito = "";
            $vacio = 0;
            
            //Recupera Variables de seleccion
            $aDeposito = $_REQUEST['deposito'];
            foreach ($aDeposito as $key => $value) {
                if( !empty($value) )
                    $vacio++;
            }
            
            $typeu = $_REQUEST['typeu'];

            if( $vacio>0 ){
                //foreach($_REQUEST['chk'] as $ind=>$checkbox){
                    foreach($aDeposito as $i=>$deposito){
                        if( !empty($deposito) ) // $i == $ind &&
                            $sDeposito .= $deposito;
                        if( !empty($_REQUEST['deposito'][$i+1]) ) $sDeposito .= "|"; // && $i == $ind
                    }
                //}
            }

            $sQuery="Select idempleado, nombrecuentabancaria
                    From administracion_cuentasusuarios cu Inner Join administracion_cuentasbancarias cb On cb.idcuentabancaria = cu.idcuentabancaria
                    Where idempleado = ".$typeu."";

            $resulto = $conexion->consultar($sQuery);
            $count = 0;
            while($rsos=$conexion->siguiente($resulto))
                    $count++;
            $conexion->cerrar_consulta($resulto);
            if($count>0){

                if( $vacio!="" ){
                    
                        $countos=0;
                        $whereordenes=" Where ";
                        $first="";
                        foreach ($_REQUEST['idcxc'] as $i=>$checkbox){
                            if( !empty($_REQUEST['deposito'][$i]) ){
                                $whereordenes.=$first." c.idcxc=".$checkbox." ";
                                $first=" Or ";
                            }
                        }
                        $sQuery="select c.idcxc, c.iddeudor, c.idconcepto from administracion_cuentasporcobrar_titulo c ".$whereordenes."
                        Group By c.iddeudor";

                        $resultos = $conexion->consultar($sQuery);
                        while($rsos=$conexion->siguiente($resultos)){
                                $countos=$countos+1;
                        } 
                        $conexion->cerrar_consulta($resultos);
                        if($countos>1){
                                //SI NO CUMPLE CON POLITICAS LOS REGRESA
                                ?>
                                    <script>
                                            alert("Para realizar varios pagos, deben pertenecer al Mismo: Deudor o Concepto, Seleccione de nuevo las ordenes a liquidar");
                                            javascript:history.back(1);
                                    </script>
                                <?php
                        }else{
                                //REDIRECCIONA A PROCESO
                                 ?>
                                    <script>
                                            var pagina='../../Modulos/facturacion/ppagos2.php?sqlwhereos=<?php echo $whereordenes ?>&vals=<?php echo $sDeposito?>&typeu=<?php echo $typeu; ?>&iestilo=<?php echo $_REQUEST['iestilo']; ?>';
                                            document.location.href=pagina;
                                    </script>
                                <?php   
                        }  

                }elseif($vacio==0){
                 ?>
                        <script>
                                alert("Ingrese una cantidad de deposito cuando menos para continuar");
                                javascript:history.back(1);
                        </script>
                <?php
                }
        }elseif($count==0){
         ?>
                <script>
                        alert("Aviso: No tiene permiso para realizar esta acción!");
                        javascript:history.back(1);
                </script>
        <?php
        }
    }
    
?>