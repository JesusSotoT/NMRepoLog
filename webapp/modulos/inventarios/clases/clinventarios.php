<?php
/* 
 * Clase para que puedan utilizar los otros módulos del scm.
 * Automáticamente crea ya un registro de entrada.
 */

class clinventarios{
    /*Regresa Existencias Del Dia*/
    function regresaexistenciaventas($idproducto,$idlote,$idestadoproducto,$idalmacen,$conexion){
        $existencia=0;
        $sqlexistencias="select ifnull(im.cantidad,0) 'fisica', 
                            from inventarios_saldos im 
                        where im.idproducto=$idproducto and im.idlote=$idlote and im.idestadoproducto=$idestadoproducto and im.idalmacen=$idalmacen";

        $result = $conexion->consultar($sqlexistencias);
        while($rs = $conexion->siguiente($result)){
            $existencia = $rs{"fisica"};
        }
        $conexion->cerrar_consulta($result);
        return $existencia;
    }
    /*Regresa Existencia Del Dia*/

    
    /* Documentación clase */
    function agregarmovimiento($tipomovimiento,$idproducto,$idlote,$idestadoproducto,$idalmacen,$cantidad,$fecha,$doctoorigen,$folioorigen,$conexion){
        
        //Agrega Movimiento Detallado a Kardex
        $sql = "
				insert into inventarios_movimientos 
				(idtipomovimiento, idproducto, idlote, idestadoproducto, idalmacen, cantidad, fecha, doctoorigen, folioorigen)
				 values
				('".$tipomovimiento."','".$idproducto."','".$idlote."','".$idestadoproducto."','".$idalmacen."','".$cantidad."','".$fecha."','".$doctoorigen."','".$folioorigen."')";
        $conexion->consultar($sql);
                    
        //Agrega Acumulado
                $efectoinventario=0;
                $entradas=0;
                $salidas=0;
                $saldo=0;
                $entradassecundario=0;
                $salidassecundario=0;
                $saldosecundario=0;
                
        $sQuery = "SELECT idtipomovimiento, efectoinventario FROM inventarios_tiposmovimiento i where idtipomovimiento=".$tipomovimiento;
		$result = $conexion->consultar($sQuery);
		while($rs = $conexion->siguiente($result)){
			$efectoinventario = $rs['efectoinventario'];
		}
		$conexion->cerrar_consulta($result);
        
		//Define Tipo Movimiento  y Asigna Cantidad     
                if($efectoinventario==-1){
                    $entradas=0;
                    $salidas=$cantidad;
                }
                if($efectoinventario==1){
                    $entradas=$cantidad;
                    $salidas=0;
                }
                $cantidadafectar=0;
                $cantidadafectar=($cantidad*$efectoinventario);
                
                $agregasaldo=1;
        //Verifica si existen registros en la tabla Inventarios_saldos
                $sQuery = "SELECT idproducto FROM inventarios_saldos i where  idproducto=".$idproducto.
                            " And idlote=".$idlote." And idestadoproducto=".$idestadoproducto. " And idalmacen='".$idalmacen."'";

            $result = $conexion->consultar($sQuery);
		while($rs = $conexion->siguiente($result)){
			$agregasaldo = 0;
		}
		$conexion->cerrar_consulta($result);
                if($agregasaldo==1){
                    //Si no existe movimiento provio en saldos crea un registro
                    $saldo=$entradas-$salidas;
                    $saldosecundario=$entradassecundario-$salidassecundario;
                    $sql="Insert Into inventarios_saldos 
                           (idproducto, idlote, idestadoproducto, idalmacen,cantidad) Values  							
                           ('".$idproducto."','".$idlote."','".$idestadoproducto."','".$idalmacen."','".$cantidadafectar."')";
                }
                elseif ($agregasaldo==0){
                    //Si ya existe previo edita el actual
                    $sql="Update inventarios_saldos set cantidad=cantidad+".$cantidadafectar."
                          	where idproducto=".$idproducto." And idlote=".$idlote." 
							And idestadoproducto=".$idestadoproducto." And idalmacen=".$idalmacen;                    
                }
                //echo $sql;
                $conexion->consultar($sql);
        

    }

}

?>
