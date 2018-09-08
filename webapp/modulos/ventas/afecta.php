<?php 

//Alistando Clases

        //Clase Inventarios
        include("../../modulos/inventarios/clases/clinventarios.php");
        $movimientos = new clinventarios();
        //Clase CXC
        include("../../modulos/cp/clcuentascp.php");
        $ocuentascp = new clcuentascp();
                
//Limpiando Variables
    
        $idtipomovimiento = "";   
        $idproducto = "";
        $idlote = "";
        $idestadoproducto = "";
        $cantidad = "";
        $fecha = "";
        $doctoorigen = 16;
        $folioorigen = -1;
        $idalmacen="";        
        $folioorigen=$catalog_id_utilizado;
        $importe=0;
        $iddeudor=0;


        
//Consulta Principal    
       $sQuery = "SELECT  2 idtipomovimiento,vd.idventa, vd.idproducto,
                                    vd.idlote,vd.idestadoproducto,vt.idalmacen,vd.cantidad,vt.fecha, vt.fechaentrega, cl.diascredito, 
                                    cl.idcliente, cl.razonsocial nombrecliente, vt.idestadoventa, vt.total 
                            FROM ventas_titulo vt 
                                left JOIN ventas_detalle vd ON vd.idventa=vt.idventa   
                                left join clientes cl on cl.idcliente=vt.idcliente
                            WHERE vt.idventa=".$catalog_id_utilizado;

		$result = $conexion->consultar($sQuery);
		while($rs = $conexion->siguiente($result)){
                                $idtipomovimiento =$rs["idtipomovimiento"];   
                                $idalmacen = $rs["idalmacen"];
                                $idproducto = $rs["idproducto"];
                                $idlote = $rs["idlote"];
                                $idestadoproducto = $rs["idestadoproducto"];
                                $cantidad = $rs["cantidad"];
                                $fecha = $rs["fecha"];
                                $doctoorigen=16;//Se deja fijo el Numero de Documento
                                $folioorigen=$catalog_id_utilizado;
                                $idestadoventa=$rs["idestadoventa"];
                                $fechacargo=$rs["fechaentrega"];
                                $fechavencimiento=$rs["fechaentrega"];
                                $dias=$rs["diascredito"];
                                //$fechavencimiento = strtotime ( "+$dias day" , strtotime ( $fechacargo ) ) ;
                                //$fechavencimiento = date ( 'Y-m-j' , $fechavencimiento );
                                $idcliente=$rs["idcliente"];
                                $nombrecliente=$rs["nombrecliente"];
                                $idventa=$rs["idventa"];
                                $total=$rs["total"];
           
            //Agrega Cuentas por Cobrar (Si esta entregado el cliente debe la cantidad de venta)
                       
                        if($idestadoventa==2){
                            //verifica que no exista cargo anterior
                            $cargoanterior=0;
                            $sqlcxc="Select * from admin_cxc where iddocumento=$doctoorigen and foliodocumento=$folioorigen";
                            $result2=$conexion->consultar($sqlcxc);
                            while($rs2 = $conexion->siguiente($result2)){
                                $cargoanterior=-1;
                            }
                            $conexion->cerrar_consulta($result2);
                            
                           
                            if ($cargoanterior==0){
                                    $iddeudor=-1;
                                //Verifica que exista el deudor si no existe lo agrega
                                    $sqlcxc="select iddeudor from admin_deudores where idclaveorigen=$idcliente and idestructura=19";
                                    $result2=$conexion->consultar($sqlcxc);
                                    while($rs2 = $conexion->siguiente($result2)){
                                        $iddeudor=$rs2["iddeudor"];
                                    }
                                    echo "$iddeudor";
                                    $conexion->cerrar_consulta($result2);
                                    if($iddeudor==-1){
                                        //Inserta Deudor
                                        $sqlcl = "insert into admin_deudores 
                                                    (nombredeudor, idclaveorigen, idestructura)
                                                    values
                                                    ('".$nombrecliente."','".$idcliente."','19')";
                                                
                                                $conexion->consultar($sqlcl);
                                                $iddeudor = $conexion->insert_id();          
                                    }
                                //Agrega CXC ($fechacargo,$fechavencimiento,$iddeudor,$idconcepto, $descripcion,$saldoinicial,$abonos,$saldoactual,$iddocumento,$foliodocumento,$activo,$conexion){
                                    //$ocuentascp->agregarmovimientocxc("2010-9-01","2010-9-10", 1, 1, "Descripcion Cargo", 1000, 0, 1000, 1,1,-1, $conexion);
                                    $ocuentascp->agregarmovimientocxc($fechacargo,$fechavencimiento, $iddeudor,1, "Venta Folio: $idventa", $total, 0, $total, $doctoorigen,$idventa,-1, $conexion);
                            }
                        }    
            //Agrega Movimiento Almacen (Si el cliente recibio el producto hay que hacer una salida)
                        $idlote=-1;
                        $idestadoproducto=-1;
                        if ($idestadoventa==2){
                            $movimientos->agregarmovimiento($idtipomovimiento,$idproducto,$idlote,$idestadoproducto,$idalmacen,$cantidad,$fecha,$doctoorigen,$folioorigen,$conexion);
                        }
		} 
        
        

                echo "<br>Afecta Componentes Adicionales";


   
?>