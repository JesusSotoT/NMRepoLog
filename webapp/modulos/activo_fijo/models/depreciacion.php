<?php
//Carga la clase de coneccion con sus metodos para consultas o transacciones
//require("models/connection.php"); // funciones mySQL 
require("models/connection_sqli_manual.php"); // funciones mySQLi

class DepreciacionModel extends Connection
{
    public function lista_activos()
    {
        $myQuery = "SELECT* FROM afi_bienes WHERE estatus != 0";
        $resultados = $this->query($myQuery);
        return $resultados;
    }

    public function lista_altas()
    {
        return $this->query("SELECT* FROM afi_cat_altas WHERE activo = 1");
    }

    public function categorias()
    {
        return $this->query("SELECT* FROM afi_categorias_bienes WHERE activo = 1");
    }

    public function responsables()
    {
        return $this->query("SELECT idEmpleado,codigo,nombreEmpleado, apellidoPaterno FROM nomi_empleados WHERE activo = -1");
    }

    public function segmentos()
    {
        return $this->query("SELECT idSuc, nombre, clave FROM cont_segmentos WHERE activo = -1");
    }

    public function sucursales()
    {
        return $this->query("SELECT idSuc,clave,nombre FROM mrp_sucursal WHERE activo = -1");
    }

    public function tasa_depr()
    {
        return $this->query("SELECT* FROM afi_porc_depr WHERE activo = 1");
    }

    public function id_prv($rfc)
    {
        $res = $this->query("SELECT idPrv,razon_social FROM mrp_proveedor WHERE rfc = '$rfc' AND status = -1");
        $res = $res->fetch_assoc();
        return $res;
    }

    public function get_cuentas($t)
    {   
        $where = '';
        if($t)
            $where = "AND account_type = $t";
        return $this->query("SELECT account_id, manual_code, description FROM cont_accounts WHERE main_account = 3 AND removed = 0 $where");
    }

    public function guardar($vars)
    {
        //Guardar nuevo
        if(!intval($vars['id_reg']))
        {
            $myQuery = "INSERT INTO afi_bienes VALUES(0,'".$vars['etiqueta']."','".$vars['factura']."','".$vars['num_factura']."',".$vars['id_proveedor'].",'".$vars['fecha']."',".$vars['moi'].",".$vars['concepto_alta'].",'".$vars['descripcion']."','".$vars['n_serie']."','".$vars['modelo']."','".$vars['marca']."','".$vars['color']."',".$vars['ubicacion'].",".$vars['responsable'].",".$vars['categoria'].",".$vars['segmento'].",".$vars['sucursal'].",'".$vars['barras']."',".$vars['cuenta_asoc'].",".$vars['base_calculo'].",".$vars['estatus'].")";
            return $this->insert_id($myQuery);
        }
        else
        {
            //Actualizar existente
            $myQuery = "UPDATE afi_bienes SET 
                        codigo          = '".$vars['etiqueta']."',
                        factura         = '".$vars['factura']."',
                        num_fact        = '".$vars['num_factura']."',
                        id_proveedor    = ".$vars['id_proveedor'].",
                        fecha_fact      = '".$vars['fecha']."',
                        moi             = ".$vars['moi'].",
                        id_concepto_alta = ".$vars['concepto_alta'].",
                        descripcion     = '".$vars['descripcion']."',
                        n_serie         = '".$vars['n_serie']."',
                        modelo          = '".$vars['modelo']."',
                        marca           = '".$vars['marca']."',
                        color           = '".$vars['color']."',
                        ubicacion       = ".$vars['ubicacion'].",
                        id_responsable  = ".$vars['responsable'].",
                        id_categoria    = ".$vars['categoria'].",
                        id_segmento     = ".$vars['segmento'].",
                        id_sucursal     = ".$vars['sucursal'].",
                        codigo_barras   = '".$vars['barras']."',
                        id_cuenta       = ".$vars['cuenta_asoc'].",
                        base_calculo    = ".$vars['base_calculo'].",
                        estatus         = ".$vars['estatus']." 
                        WHERE id = ".$vars['id_reg'];
            if($this->query($myQuery))
                return $vars['id_reg'];
        }
        
    }

    public function guardar_cont($vars)
    {
        //Guardar nuevo
        $existe = $this->query("SELECT COUNT(*) AS cant FROM afi_bienes_datos_contable WHERE activo = 1 AND id_bien = ".$vars['id_reg']);
        $existe = $existe->fetch_object();
        
        if(intval($vars['base_calculo']) != 3)
                $this->query("UPDATE afi_bienes_datos_fiscal SET activo = 0 WHERE id_bien = ".$vars['id_reg']);

        if(!intval($existe->cant))
        {
            $myQuery = "INSERT INTO afi_bienes_datos_contable VALUES(0,".$vars['id_reg'].",'".$vars['fecha_depr']."',".$vars['moi_cont'].",".$vars['tasa_depr'].",".$vars['porc_deducible'].",'".$vars['fecha_ultima_depr']."',".$vars['depr_ejercicio'].",".$vars['depr_acumulada'].",".$vars['cuenta_activo'].",".$vars['cuenta_resultados'].",".$vars['no_deducible'].",1)";
            return $this->insert_id($myQuery);
        }
        else
        {
            //Actualizar existente
             $myQuery = "UPDATE afi_bienes_datos_contable SET 
                        id_bien             = ".$vars['id_reg'].",
                        fecha_inicio_depr   = '".$vars['fecha_depr']."',
                        moi                 = ".$vars['moi_cont'].",
                        tasa_depr           = ".$vars['tasa_depr'].",
                        porc_deducible      = ".$vars['porc_deducible'].",
                        fecha_ult_depr      = '".$vars['fecha_ultima_depr']."',
                        depr_ejercicio      = ".$vars['depr_ejercicio'].",
                        depr_acumulada      = ".$vars['depr_acumulada'].",
                        id_cuenta_activo    = ".$vars['cuenta_activo'].",
                        id_cuenta_resultado = ".$vars['cuenta_resultados'].",
                        id_cuenta_no_deducible= ".$vars['no_deducible']."
                        WHERE activo = 1 AND id_bien = ".$vars['id_reg'];
            if($this->query($myQuery))
                return $vars['id_reg'];
        }
        
    }

    public function guardar_fiscal($vars)
    {
        //Guardar nuevo
        $existe = $this->query("SELECT COUNT(*) AS cant FROM afi_bienes_datos_fiscal WHERE activo = 1 AND id_bien = ".$vars['id_reg']);
        $existe = $existe->fetch_object();

        if(intval($vars['base_calculo']) != 3)
                $this->query("UPDATE afi_bienes_datos_contable SET activo = 0 WHERE id_bien = ".$vars['id_reg']);

        if(!intval($existe->cant))
        {   
            $myQuery = "INSERT INTO afi_bienes_datos_fiscal VALUES(0,".$vars['id_reg'].",'".$vars['fecha_depr']."',".$vars['deduccion_inmediata'].",".$vars['tasa_depr'].",".$vars['porc_deducible'].",'".$vars['fecha_ultima_depr']."',".$vars['depr_ejercicio'].",".$vars['depr_acumulada'].",".$vars['cuenta_orden_acr'].",".$vars['cuenta_orden_deu'].",1)";
            return $this->insert_id($myQuery);
        }
        else
        {
            //Actualizar existente
           $myQuery = "UPDATE afi_bienes_datos_fiscal SET 
                        id_bien             = ".$vars['id_reg'].",
                        fecha_inicio_depr   = '".$vars['fecha_depr']."',
                        deduccion_inmediata = ".$vars['deduccion_inmediata'].",
                        tasa_depr           = ".$vars['tasa_depr'].",
                        porc_deducible      = ".$vars['porc_deducible'].",
                        fecha_ult_depr      = '".$vars['fecha_ultima_depr']."',
                        depr_ejercicio      = ".$vars['depr_ejercicio'].",
                        depr_acumulada      = ".$vars['depr_acumulada'].",
                        id_cuenta_acreedora = ".$vars['cuenta_orden_acr'].",
                        id_cuenta_deudora   = ".$vars['cuenta_orden_deu']."
                        WHERE activo = 1 AND id_bien = ".$vars['id_reg'];
            if($this->query($myQuery))
                return $vars['id_reg'];
        }
        
    }

    public function getDatosActivo($id_reg)
    {
        $res = $this->query("SELECT *, (SELECT razon_social FROM mrp_proveedor WHERE idPrv = id_proveedor) AS nombre_proveedor FROM afi_bienes WHERE id = ".$id_reg);
        $res = $res->fetch_assoc();
        return $res;
    }

    public function getDatosActivoContFisc($id_reg,$base)
    {
        if(intval($base) == 1)
            $base_nom = "contable";

        if(intval($base) == 2)
            $base_nom = "fiscal";

        $myQuery = "SELECT* FROM afi_bienes_datos_" . $base_nom . " WHERE id_bien = " . $id_reg . " AND activo = 1";

        $res = $this->query($myQuery);
        $res = $res->fetch_assoc();
        return $res;
    }
}
?>
