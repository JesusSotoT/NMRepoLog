<?php

        include ("parametros.php");
		
				//CSRF
				$reset_vars = false;
				include("../../catalog/clases/clcsrf.php");	
				if(!$csrf->check_valid('post')){
						$accelog_access->raise_404(); 
						exit();
				}

        $idreporte = mysql_real_escape_string($conexion->escapalog($_REQUEST['txtidreporte']));
        $nombrereporte = mysql_real_escape_string($conexion->escapalog($_REQUEST['txtnombrereporte']));
        $descripcion = mysql_real_escape_string($conexion->escapalog($_REQUEST['txtdesc']));
        $idestilo = mysql_real_escape_string($conexion->escapalog($_REQUEST['cmbestilo']));
        $sql_select = repolog_RFI($conexion->escapalog($_REQUEST['txtselect']));
        $sql_from = repolog_RFI($conexion->escapalog($_REQUEST['txtfrom']));
        $sql_where = repolog_RFI($conexion->escapalog($_REQUEST['txtwhere']));
        $sql_groupby = repolog_RFI($conexion->escapalog($_REQUEST['txtgroupby']));
        $sql_having = repolog_RFI($conexion->escapalog($_REQUEST['txthaving']));
        $sql_orderby = repolog_RFI($conexion->escapalog($_REQUEST['txtorderby']));
        $url_include= repolog_RFI($conexion->escapalog($_REQUEST['txturl_include']));
        $url_include_despues=repolog_RFI($conexion->escapalog($_REQUEST['txturl_include_despues']));
        $url_include= repolog_RFI($conexion->escapalog($_REQUEST['txturl_include']));
        $url_include_despues=repolog_RFI($conexion->escapalog($_REQUEST['txturl_include_despues']));
        $subtotales_agrupaciones = repolog_RFI($conexion->escapalog($_REQUEST['txtagrupaciones']));
        $subtotales_funciones = repolog_RFI($conexion->escapalog($_REQUEST['txtfunciones']));
        $subtotales_subtotal = repolog_RFI($conexion->escapalog($_REQUEST['txtsubtotales']));

        $sql = "";
        if($idreporte==-1){
            $sql = "
                    insert into repolog_reportes
                    (nombrereporte, descripcion, fechacreacion, fechamodificacion, estatus, idestiloomision,
                        sql_select, sql_from, sql_where, sql_groupby, sql_having, sql_orderby, url_include, url_include_despues, subtotales_agrupaciones, subtotales_funciones, subtotales_subtotal)
                     values
                     ('".$nombrereporte."', '".$descripcion."', now(), now(), 'G', ".$idestilo.",
                      '".$sql_select."', '".$sql_from."', '".$sql_where."', '".$sql_groupby."', '".$sql_having."', '".$sql_orderby."', '".$url_include."', '".$url_include_despues."', '".$subtotales_agrupaciones."', '".$subtotales_funciones."', '".$subtotales_subtotal."'
                    )";

						//REGISTRO TRANSACCIONES -- 2013-10-04
        		$conexion->transaccion("REPOLOG - ADMIN - INSERCION - ".$nombrereporte,$sql);



        } else {
            $sql = "
                    update repolog_reportes set
                            nombrereporte = '".$nombrereporte."',
                            descripcion='".$descripcion."',
                            fechamodificacion=now(),
                            idestiloomision=".$idestilo.",
                            sql_select = '".$sql_select."',
                            sql_from = '".$sql_from."',
                            sql_where = '".$sql_where."',
                            sql_groupby = '".$sql_groupby."',
                            sql_having = '".$sql_having."',
                            sql_orderby = '".$sql_orderby."',
                            url_include = '".$url_include."',
                            url_include_despues = '".$url_include_despues."',
                            subtotales_agrupaciones = '".$subtotales_agrupaciones."',
                            subtotales_funciones = '".$subtotales_funciones."',
                            subtotales_subtotal = '".$subtotales_subtotal."' 
                       where idreporte = ".$idreporte."
                    ";              

							//REGISTRO TRANSACCIONES -- 2013-10-04
      			  $conexion->transaccion("REPOLOG - ADMIN - ACTUALIZACION - ".$nombrereporte,$sql);




        }
        $conexion->consultar($sql);

				



        //echo $sql;
        //exit();
	$conexion->cerrar();
		
?>

<html>
    <head>

    </head>
    <body>
				  <script type='text/javascript'>
						window.location = 'index.php';
				  </script>
    </body>
    
</html>
