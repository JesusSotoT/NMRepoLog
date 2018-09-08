<?php
//Carga la clase de coneccion con sus metodos para consultas o transacciones
//require("models/connection.php"); // funciones mySQL 
require("models/connection_sqli_manual.php"); // funciones mySQLi

class Pre_AdmModel extends Connection
{
    function sectores($pub,$fin,$eco1,$eco2,$idd)
    {
        
        
        switch($idd)
        {
            case 1:
                    //Imprime esto en los sectors publicos por default
                    $select = "Sector_Publico as num, Descripcion as des";
                    $where = "Sector_Publico != 0 AND Sector_Financiero = 0 AND Sector_Economia1 = 0 AND Sector_Economia2 = 0 AND Entes_Publicos = 0";
                    break;
            case 2: //Imprime los sectores financieros
                    $select = "Sector_Financiero as num, Descripcion as des";
                    $where = "Sector_Publico = $pub AND Sector_Financiero != 0 AND Sector_Economia1 = 0 AND Sector_Economia2 = 0 AND Entes_Publicos = 0";
                    break;
            case 3: //Imprime los sectores economia 1
                    $select = "Sector_Economia1 as num, Descripcion as des";
                    $where = "Sector_Publico = $pub AND Sector_Financiero = $fin AND Sector_Economia1 != 0 AND Sector_Economia2 = 0 AND Entes_Publicos = 0";
                    break;
            case 4: //Imprime los sectores economia 2
                    $select = "Sector_Economia2 as num, Descripcion as des";
                    $where = "Sector_Publico = $pub AND Sector_Financiero = $fin AND Sector_Economia1 = $eco1 AND Sector_Economia2 != 0 AND Entes_Publicos = 0";
                    break;
            case 5: //Imprime los entes publicos
                    $select = "Entes_Publicos as num, Descripcion as des";
                    $where = "Sector_Publico = $pub AND Sector_Financiero = $fin AND Sector_Economia1 = $eco1 AND Sector_Economia2 = $eco2 AND Entes_Publicos != 0";
                    break;       
        }


        $resultado = $this->query("SELECT $select FROM pre_clasif_adm WHERE $where");
        return $resultado;
    }

    function Guardar($vars)
    {
        if(!intval($vars['idd']))
        {
            $myQuery = "INSERT INTO pre_clave_adm(Clave,Descripcion) VALUES('".$vars['clave']."','".$vars['desc']."')";
        }
        else
        {
            $myQuery = "UPDATE pre_clave_adm SET Clave = '".$vars['clave']."' , Descripcion = '".$vars['desc']."' WHERE Id = ".$vars['idd'];
        }
        $this->query($myQuery);
        return 1;
    }

    function Eliminar($id)
    {
        $myQuery = "DELETE FROM pre_clave_adm WHERE Id = ".$id;
        
        $this->query($myQuery);
        return 1;
    }

    function getClavesAdm()
    {
        $myQuery = "SELECT* FROM pre_clave_adm order by Clave";
        $claves = $this->query($myQuery);
        return $claves;
    }
}
?>
