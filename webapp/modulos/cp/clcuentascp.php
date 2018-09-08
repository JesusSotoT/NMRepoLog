<?php
/* 
 * Clase para que puedan utilizar los  módulos del sistema este genera cuentas por cobrar o por pagar.
 * Automáticamente crea ya un objeto de nombre $oresultados.
 */

class clcuentascp{

        /* Funciones de Cuentas por Cobrar */
        function agregarmovimientocxc($fechacargo,$fechavencimiento,$iddeudor,$idconcepto, $descripcion,$saldoinicial,$abonos,$saldoactual,$iddocumento,$foliodocumento,$activo,$conexion){
            $sqlcl = "
                        insert into admin_cxc
                            (fechacargo, fechavencimiento, iddeudor,idconcepto, descripcion,saldoinicial,abonos,saldoactual,iddocumento,foliodocumento,activo)
                         values
                            ('".$fechacargo."','".$fechavencimiento."',".$iddeudor.",".$idconcepto.",'".$descripcion."',".$saldoinicial.",".$abonos.",".$saldoactual.",".$iddocumento.",".$foliodocumento.",".$activo.")
                      ";
            //echo $sqlcl."<br>";
            $conexion->consultar($sqlcl);
            //exit();
        }
        
        function agregarpagocxc($idcxc,$fechapago,$pago,$idformapago,$referencia,$idcuenta,$observaciones,$conexion){
            
            //genera el movimiento del pago
            $sqlcl = "
                        insert into admin_cxcpagos
                            (idcxc, fechapago, pago, idformapago, referencia,idcuenta, observaciones)
                         values
                            (".$idcxc.",'".$fechapago."',".$pago.",".$idformapago.",'".$referencia."',".$idcuenta.",'".$observaciones."')
                            ";
    //echo $sqlcl."<br>";  
            $conexion->consultar($sqlcl);
            
            //Actaliza el Titulo relacionado
            $sqlcl=" update admin_cxc set saldoactual=(saldoinicial-(SELECT sum(pago) pagos FROM admin_cxcpagos a 
                        where a.idcxc=".$idcxc.")),abonos=(SELECT sum(pago) pagos 
                    FROM admin_cxcpagos a where a.idcxc=".$idcxc.") where idcxc=".$idcxc;
    //echo $sqlcl."<br>";           
            $conexion->consultar($sqlcl);
            
            
            
        }
        
        
    
    /*Funciones de Cuentas por Pagar*/
  
      function agregarmovimientocxp($fechacargo,$fechavencimiento,$idacreedor,$idconcepto, $descripcion,$saldoinicial,$abonos,$saldoactual,$iddocumento,$foliodocumento,$activo,$conexion){
            $sqlcl = "
                        insert into admin_cxp
                            (fechacargo, fechavencimiento, idacreedor,idconcepto, descripcion,saldoinicial,abonos,saldoactual,iddocumento,foliodocumento,activo)
                         values
                            ('".$fechacargo."','".$fechavencimiento."',".$idacreedor.",".$idconcepto.",'".$descripcion."',".$saldoinicial.",".$abonos.",".$saldoactual.",".$iddocumento.",".$foliodocumento.",".$activo.")
                      ";
    //echo $sqlcl."<br>";
            $conexion->consultar($sqlcl);
        }
        
        function agregarpagocxp($idcxp,$fechapago,$pago,$idformapago,$referencia,$idcuenta,$observaciones,$conexion){
            
            //genera el movimiento del pago
            $sqlcl = "
                        insert into admin_cxppagos
                            (idcxp, fechapago, pago, idformapago, referencia,idcuenta, observaciones)
                         values
                            (".$idcxp.",'".$fechapago."',".$pago.",".$idformapago.",'".$referencia."',".$idcuenta.",'".$observaciones."')
                            ";
    //echo $sqlcl."<br>";  
            $conexion->consultar($sqlcl);
            
            //Actaliza el Titulo relacionado
            $sqlcl=" update admin_cxp set saldoactual=(saldoinicial-(SELECT sum(pago) pagos FROM admin_cxppagos a 
                        where a.idcxp=".$idcxp.")),abonos=(SELECT sum(pago) pagos 
                    FROM admin_cxppagos a where a.idcxp=".$idcxp.") where idcxp=".$idcxp;
    //echo $sqlcl."<br>";           
            $conexion->consultar($sqlcl);
            
            
            
        }
      
  
}

?>
