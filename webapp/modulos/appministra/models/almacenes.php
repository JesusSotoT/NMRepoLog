<?php
//Carga la clase de coneccion con sus metodos para consultas o transacciones
//require("models/connection.php"); // funciones mySQL 
require("models/connection_sqli_manual.php"); // funciones mySQLi

class AlmacenesModel extends Connection
{
    function listaAlmacenes()
    {
        
        $accounts = "";
        //$qry = "SELECT *,(SELECT COUNT(*) FROM cont_movimientos m WHERE m.Cuenta = c.account_id AND m.Activo = 1) AS activity FROM cont_accounts c ORDER BY father_account_id,account_id;";
        $qry = "SELECT * FROM app_almacenes WHERE codigo_sistema != '999' ORDER BY id_padre, id";
        $result = $this->query($qry);
        $i = 0;
        for ($i=0; $i < $result->num_rows ; $i++)
        { 
            $data = $result->fetch_array(MYSQLI_ASSOC);
            $accounts[$i] = $data;
        }
        return $accounts;
    }

    function tipos()
    {
        return $this->query("SELECT* FROM app_almacen_tipo");
    }

    function padres()
    {
        return $this->query("SELECT id,codigo_manual,nombre,id_almacen_tipo FROM app_almacenes WHERE id_almacen_tipo < 4 AND activo = 1");
    }

    function sucursales()
    {
        return $this->query("SELECT idSuc,nombre FROM mrp_sucursal");
    }

    function estados()
    {
        return $this->query("SELECT idestado,estado FROM estados WHERE idpais = 1");
    }

    function municipios($idestado)
    {
        return $this->query("SELECT idmunicipio,municipio FROM municipios WHERE idestado = $idestado");
    }

    function empleados()
    {
        return $this->query("SELECT idEmpleado,codigo,nombreEmpleado,apellidoPaterno FROM nomi_empleados WHERE activo = -1");
    }

    function clasificadores()
    {
        return $this->query("SELECT id,nombre,clave FROM app_clasificadores WHERE activo = 1 AND tipo = 4 AND padre != 0");
    }

    function infopadre($id)
    {
        return $this->query("SELECT* FROM app_almacenes WHERE id = $id");
    }

    function getmunicipios($id)
    {
        return $this->query("SELECT idmunicipio, municipio FROM municipios WHERE idestado = $id");
    }

    function getdatos($id)
    {
        return $this->query("SELECT* FROM app_almacenes WHERE id = $id");
    }

    function guardar($id,$clave,$nombre,$tipo,$depende,$sucursal,$estado,$municipio,$direccion,$encargado,$telefono,$ext,$consignacion,$clasificador,$status)
    {
        if(intval($id))
        { 
            $myQuery = "UPDATE app_almacenes SET 
            id                      =   $id, 
            codigo_manual           =   '$clave', 
            nombre                  =   '$nombre', 
            id_padre                =   $depende, 
            id_sucursal             =   $sucursal, 
            id_estado               =   $estado, 
            id_municipio            =   $municipio, 
            direccion               =   '$direccion', 
            id_almacen_tipo         =   $tipo, 
            id_empleado_encargado   =   $encargado, 
            telefono                =   '$telefono', 
            ext                     =   '$ext', 
            es_consignacion         =   $consignacion, 
            id_clasificador         =   $clasificador, 
            activo                  =   $status
            WHERE id= $id; ";
           
            if(intval($tipo) == 1)
            {
                $cod = "SELECT codigo_sistema FROM app_almacenes WHERE id = $id";
                $cod = $this->query($cod);
                $cod = $cod->fetch_assoc();
                $cod = $cod["codigo_sistema"];
                
                $activo = "";
                if(!intval($status))
                    $activo = ", activo = 0";

                $myQuery .= "UPDATE app_almacenes SET id_sucursal = $sucursal, id_estado = $estado, id_municipio = $municipio, direccion = '$direccion' $activo WHERE codigo_sistema LIKE '$cod%'; ";
            }

            return $this->multi_query($myQuery);
        }
        else
        {
            $codigo_sistema = $this->buscaCodigoSistema($depende);
            $myQuery = "INSERT INTO app_almacenes(id, codigo_sistema, codigo_manual, nombre, id_padre, id_sucursal, id_estado, id_municipio, direccion, id_almacen_tipo, id_empleado_encargado, telefono, ext, es_consignacion, id_clasificador, activo)
                        VALUES(0, '$codigo_sistema', '$clave', '$nombre', $depende, $sucursal, $estado, $municipio, '$direccion', $tipo, $encargado, '$telefono', '$ext', $consignacion, $clasificador, $status)";
            return $this->query($myQuery);
        }
    }

   function buscaCodigoSistema($padre)
    {
        //Busca el numero de cuenta del padre
        $myQuery = "SELECT codigo_sistema FROM app_almacenes WHERE id = $padre";
        $codigoPadre = $this->query($myQuery);
        $codigoPadre = $codigoPadre->fetch_assoc();
        $codigoPadre = $codigoPadre["codigo_sistema"];
        $nuevo_numero = '';

        //Si tiene padre busca entre sus hermanos
        if(intval($padre))
        {
            $myQuery = "SELECT codigo_sistema FROM app_almacenes WHERE id_padre = $padre ORDER BY id DESC LIMIT 1";
            $res = $this->query($myQuery);
            
            //si tiene hermanos busca el ultimo y le suma uno al ultimo array
            if($res->num_rows)
            {
                $res = $res->fetch_assoc();
                $res = explode(".",$res['codigo_sistema']);
                $nuevo_hijo = end($res);
                $nuevo_hijo++;
                
                $nuevo_numero = $codigoPadre . "." . $nuevo_hijo;
            }
            else
            {
                //Si no tiene hermano le agrega 1 al ultimo array;
                $nuevo_numero = $codigoPadre.".1";
            }

        }
        else
        {
            //Si no tiene padre busca el numero del ultimo almacen exceptuando el 999 que pertenece al almacen de transito
            $myQuery = "SELECT codigo_sistema FROM app_almacenes WHERE id_padre = 0 AND codigo_sistema != '999' ORDER BY codigo_sistema DESC LIMIT 1";
            $res = $this->query($myQuery);
            $res = $res->fetch_assoc();
            $nuevo_numero = intval($res['codigo_sistema']) + 1;
        }

        //Nuevo numero generado
        return $nuevo_numero;
    }


}
?>
