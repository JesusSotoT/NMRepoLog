<?php

    class clsetup{

        function agregacampocatalog($idestructura,$nombre,$descripcion,$longitud,$tipo_datos,$requerido,$orden,$llaveprimaria,$conexion){
            $sql = " 
                        insert into catalog_campos
                        values
                        (null,".$idestructura.",'".$nombre."','".$descripcion."','".$descripcion."',".$longitud.",'".$tipo_datos."','NA','',".$requerido.",'',".$orden.",".$llaveprimaria.") ";
            $conexion->consultar($sql);
        }

        function agregadependencia($idcampo,$tabla,$campovalor,$campodesc,$conexion){
            $sql = "
                        insert into catalog_dependencias
                        values
                        (".$idcampo.",'S','".$tabla."','".$campovalor."','".$campodesc."')";
            $conexion->consultar($sql);
        }

    }

    $setup = new clsetup();
	
?>