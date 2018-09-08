<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

 

    //Conexión a la base de datos
    include("../../netwarelog/catalog/conexionbd.php");


    //Objeto resultados creación de la instancia...
    include("clcuentascp.php");
    $ocuentascp = new clcuentascp();
    


    //Parametros funcion agregarmovimientocxc($fechacargo,$fechavencimiento,$iddeudor,$idconcepto, $descripcion,$saldoinicial,$abonos,$saldoactual,$iddocumento,$foliodocumento,$activo,$conexion){
        $ocuentascp->agregarmovimientocxc("2010-9-01","2010-9-10", 1, 1, "Descripcion Cargo", 1000, 0, 1000, 1,1,-1, $conexion);
        $ocuentascp->agregarmovimientocxc("2010-9-01","2010-9-10", 1, 1, "Descripcion Cargo", 1000, 0, 1000, 1,2,-1, $conexion);
        
    //Parametros function agregarpagocxc($idcxc,$fechapago,$pago,$idformapago,$referencia,$idcuentabancaria,$observaciones,$conexion){
        $ocuentascp->agregarpagocxc(1,"2010-9-10", 500, 1,"123",1,"Observa", $conexion);
        $ocuentascp->agregarpagocxc(2,"2010-9-10", 500, 1,"456",1,"Observa", $conexion);
        
      
        
         
    //Parametros funcion agregarmovimientocxc($fechacargo,$fechavencimiento,$iddeudor,$idconcepto, $descripcion,$saldoinicial,$abonos,$saldoactual,$iddocumento,$foliodocumento,$activo,$conexion){
        $ocuentascp->agregarmovimientocxp("2010-9-01","2010-9-10", 1, 1, "Descripcion Cargo", 1000, 0, 1000, 1,1,-1, $conexion);
        $ocuentascp->agregarmovimientocxp("2010-9-01","2010-9-10", 1, 1, "Descripcion Cargo", 1000, 0, 1000, 1,2,-1, $conexion);
        
    //Parametros function agregarpagocxc($idcxc,$fechapago,$pago,$idformapago,$referencia,$idcuentabancaria,$observaciones,$conexion){
        $ocuentascp->agregarpagocxp(1,"2010-9-10", 500, 1,"123",1,"Observa", $conexion);
        $ocuentascp->agregarpagocxp(2,"2010-9-10", 500, 1,"456",1,"Observa", $conexion);        

?>
PRUEBA CONCLUIDA