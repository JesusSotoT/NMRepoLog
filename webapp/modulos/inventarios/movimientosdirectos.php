<?php 
        $idtipomovimiento = "";   
        $idproducto = "";
        $idlote = "";
        $idestadoproducto = "";
        $cantidad = "";
        $fecha = "";
        $doctoorigen = 1;
        $folioorigen = -1;
        $idalmacen="";        

 if( $a == 1 ){       
            //Si el movimiento es Nuevo
			
		include("clases/clinventarios.php");
		$movimientos = new clinventarios();
		$folioorigen=$catalog_id_utilizado;
        //Consulta Registros afectar
		$sQuery = "SELECT  imd.idtipomovimiento, imd.idproducto,
                                    imd.idlote,imd.idestadoproducto,imt.idalmacen,imd.cantidad,imt.fecha 
                            FROM inventarios_movimientostitulo imt 
                                inner join inventarios_movimientosdetalle imd on imd.idmovimiento=imt.idmovimiento 
                            where imt.idmovimiento=".$catalog_id_utilizado;
		$result = $conexion->consultar($sQuery);
		while($rs = $conexion->siguiente($result)){
                                $idtipomovimiento =$rs["idtipomovimiento"];   
                                $idalmacen = $rs["idalmacen"];
                                $idproducto = $rs["idproducto"];
                                $idlote = $rs["idlote"];
                                $idestadoproducto = $rs["idestadoproducto"];
                                $cantidad = $rs["cantidad"];
                                $fecha = $rs["fecha"];
                                $doctoorigen=1;//Se deja fijo el Numero de Documento
                                $folioorigen=$catalog_id_utilizado;
                                
                                
            //Agrega Movimiento Almacen
			$movimientos->agregarmovimiento($idtipomovimiento,$idproducto,$idlote,$idestadoproducto,$idalmacen,$cantidad,$fecha,$doctoorigen,$folioorigen,$conexion);
		}
		$conexion->cerrar_consulta($result);
                
            //Marca como procesado el documento
                $sQuery="Update inventarios_movimientostitulo set idestadodocumento=2 where idmovimiento=".$catalog_id_utilizado;
                $conexion->consultar($sQuery);
                
                echo "<br>Proceso Concluido con Exito ";
 }


?>