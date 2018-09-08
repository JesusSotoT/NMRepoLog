<?php
//Carga la clase de coneccion con sus metodos para consultas o transacciones
//require("models/connection.php"); // funciones mySQL 
require("models/connection_sqli_manual.php"); // funciones mySQLi

class Pre_GastosModel extends Connection
{
    function funcionalGasto($fin,$fun)
    {
        
        //Imprime esto en funcionalidades por default
        if($fin == 0 && $fun == 0)
        {
            $select = "Finalidades as num, Descripcion as des";
            $where = "Finalidades != 0 AND Funcion = 0 AND SubFuncion = 0";
        }

        //Imprime las funciones
        if($fin != 0 && $fun == 0)
        {
            $select = "Funcion as num, Descripcion as des";
            $where = "Finalidades = $fin AND Funcion != 0 AND SubFuncion = 0";
        }

        //Imprime las subfunciones
        if($fin != 0 && $fun != 0)
        {
            $select = "SubFuncion as num, Descripcion as des";
            $where = "Finalidades = $fin AND Funcion = $fun AND SubFuncion != 0";
        }
        

        $myQuery = "SELECT $select FROM pre_funcional_gasto WHERE $where";
        $resultado = $this->query($myQuery);
        return $resultado;
    }

    function Guardar($vars)
    {
        if(!intval($vars['idd']))
        {
            $myQuery = "INSERT INTO pre_clave_gasto(Clave,Descripcion) VALUES('".$vars['clave']."','".$vars['desc']."')";
        }
        else
        {
            $myQuery = "UPDATE pre_clave_gasto SET Clave = '".$vars['clave']."' , Descripcion = '".$vars['desc']."' WHERE Id = ".$vars['idd'];
        }
        $this->query($myQuery);
        return 1;
    }

    function Eliminar($id)
    {
        $myQuery = "DELETE FROM pre_clave_gasto WHERE Id = ".$id;
        
        $this->query($myQuery);
        return 1;
    }

    function getClaves()
    {
        $myQuery = "SELECT* FROM pre_clave_gasto order by Clave";
        $claves = $this->query($myQuery);
        return $claves;
    }
}
?>
