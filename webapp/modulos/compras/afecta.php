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
        $doctoorigen = 1;
        $folioorigen = -1;
        $idalmacen="";        
        $folioorigen=$catalog_id_utilizado;
        $importe=0;
        $iddeudor=0;


        
//Consulta Principal    
       $sQuery = "SELECT  3 idtipomovimiento,ct.idcompra,
                                    ct.idalmacen,ct.fecha, pr.diascredito, 
                                    pr.idproveedor, pr.razonsocial nombreproveedor, ct.total 
                            FROM compras_titulo ct 
                                left join proveedores pr on pr.idproveedor=ct.idproveedor
                            WHERE ct.idcompra=".$catalog_id_utilizado;

                
		$result = $conexion->consultar($sQuery);
		while($rs = $conexion->siguiente($result)){
                             
                                $fecha = $rs["fecha"];
                                $doctoorigen=17;//Se deja fijo el Numero de Documento
                                $folioorigen=$catalog_id_utilizado;
                                $fechacargo=$rs["fecha"];
                                $fechavencimiento=$rs["fecha"];
                                $dias=$rs["diascredito"];
                                //$fechavencimiento = strtotime ( "+$dias day" , strtotime ( $fechacargo ) ) ;
                                //$fechavencimiento = date ( 'Y-m-j' , $fechavencimiento );
                                $idproveedor=$rs["idproveedor"];
                                $nombreproveedor=$rs["nombreproveedor"];
                                $idcompra=$rs["idcompra"];
                                $total=$rs["total"];
           
            //Agrega Cuentas por Cobrar (Si esta entregado el cliente debe la cantidad de venta)
                            
                        
                            //verifica que no exista cargo anterior de la misma compra
                            $cargoanterior=0;
                            $sqlcxp="Select * from admin_cxp where iddocumento=17 and foliodocumento=$folioorigen";
                            $result2=$conexion->consultar($sqlcxp);
                            while($rs2 = $conexion->siguiente($result2)){
                                $cargoanterior=-1;
                            }
                            $conexion->cerrar_consulta($result2);
                            
                           
                            if ($cargoanterior==0){
                                    $idacreedor=-1;
                                //Verifica que exista el deudor si no existe lo agrega
                                    $sqlcxp="select idacreedor from admin_acreedores where idclaveorigen=$idproveedor and idestructura=15";
                                    $result2=$conexion->consultar($sqlcxp);
                                    while($rs2 = $conexion->siguiente($result2)){
                                        $idacreedor=$rs2["idacreedor"];
                                    }
                                    $conexion->cerrar_consulta($result2);
                                    if($idacreedor==-1){
                                        //Inserta Deudor
                                        $sqlcl = "insert into admin_acreedores
                                                    (nombreacreedor, idclaveorigen, idestructura)
                                                    values
                                                    ('".$nombreproveedor."','".$idproveedor."','15')";
                                                
                                                $conexion->consultar($sqlcl);
                                                $idacreedor = $conexion->insert_id();          
                                    }
                                  
                                    //$ocuentascp->agregarmovimientocxc("2010-9-01","2010-9-10", 1, 1, "Descripcion Cargo", 1000, 0, 1000, 1,1,-1, $conexion);
                                    $ocuentascp->agregarmovimientocxp($fechacargo,$fechavencimiento, $idacreedor,2, "Compra Folio: $idcompra", $total, 0, $total, $doctoorigen,$idcompra,-1, $conexion);
                            }
                        }    
            //Agrega Movimiento Almacen (Si el cliente recibio el producto hay que hacer una salida)
                        //$idlote=-1;
                        //$idestadoproducto=-1;
                        //if ($idestadoventa==2){
                        //    $movimientos->agregarmovimiento($idtipomovimiento,$idproducto,$idlote,$idestadoproducto,$idalmacen,$cantidad,$fecha,$doctoorigen,$folioorigen,$conexion);
                        //}
		
        
        

                echo "<br>Afecta Componentes Adicionales";


   
?>