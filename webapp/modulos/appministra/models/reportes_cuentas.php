<?php
//Carga la clase de coneccion con sus metodos para consultas o transacciones
//require("models/connection.php"); // funciones mySQL 
require("models/cuentas.php"); // funciones mySQLi

class ReportesCuentasModel extends Connection
{
    public function listaProveedores()
    {
        return CuentasModel::listaProveedores();
    }

    public function listaClientes()
    {
        return CuentasModel::listaClientes();
    }

    public function generar_reporte($vars)
    {

        //MIGRACION DE DATOS DE RECEPCION A RECEPCION XML
        /*INSERT INTO app_recepcion_xml(id_oc,fecha_factura,imp_factura,xmlfile,concepto,fecha_subida) SELECT r.id_oc, r.fecha_factura, r.imp_factura, r.xmlfile, r.desc_concepto, r.fecha_recepcion FROM app_recepcion r WHERE r.xmlfile != '' AND r.estatus = 1 AND r.activo = 4*/

        $idPrvs = $vars['idPrvs'];
        $proveedores = "";
        $proveedores2 = "";
        
        if(intval($vars['rango']))
        {
            $proveedores .= " AND pa.id_prov_cli BETWEEN ".$idPrvs[0]." AND ".$idPrvs[1];
            $proveedores2 .= " AND co.id_proveedor BETWEEN ".$idPrvs[0]." AND ".$idPrvs[1];
        }
        else
        {
            if($idPrvs[0])
            {
                $proveedores .= " AND (";
                $proveedores2 .= " AND (";
                for($i=0;$i<=count($idPrvs)-1;$i++)
                {
                    if($i>0)
                    {
                        $proveedores .= " OR ";
                        $proveedores2 .= " OR ";
                    }
                    $proveedores .= " pa.id_prov_cli = ".$idPrvs[$i];
                    $proveedores2 .= " co.id_proveedor = ".$idPrvs[$i];
                }
                $proveedores .= ") ";
                $proveedores2 .= ") ";
            }   
        }
        
        $myQuery = "SELECT pr.id AS id_relacion, pa.id_prov_cli, (SELECT razon_social FROM mrp_proveedor WHERE IdPrv = pa.id_prov_cli) AS nombre_proveedor, 
                    re.id_oc AS Folio, re.concepto AS SacarConcepto, 
                    SUM(CASE rq.id_moneda 
                    WHEN 1 THEN re.imp_factura
                    WHEN 2 THEN re.imp_factura*rq.tipo_cambio END) AS ImporteDoc, 
                    re.fecha_factura AS fecha_documento, 
                    pa.fecha_pago, pa.concepto, ROUND(pr.abono*pa.tipo_cambio,2) AS abono, pr.id_tipo, 1 AS Fac, pa.numero_cheque  
                    FROM app_pagos_relacion pr
                    INNER JOIN app_pagos pa ON pa.id = pr.id_pago
                    LEFT JOIN app_recepcion_xml re ON re.id_oc = pr.id_documento
                    INNER JOIN app_ocompra co ON co.id = re.id_oc
                    INNER JOIN app_requisiciones rq ON rq.id = co.id_requisicion
                    WHERE pa.cobrar_pagar = 1
                    $proveedores2 
                    AND pr.id_tipo = 1
                    AND pa.fecha_pago BETWEEN '".$vars['f_ini']."' AND '".$vars['f_fin']."' 
                    GROUP BY Folio

                    UNION ALL

                    SELECT
                    '' AS id_relacion, co.id_proveedor AS id_prov_cli, 
                    (SELECT razon_social FROM mrp_proveedor WHERE IdPrv = co.id_proveedor) AS nombre_proveedor, 
                    re.id_oc AS Folio, re.concepto AS SacarConcepto, 
                    SUM(CASE rq.id_moneda 
                    WHEN 1 THEN re.imp_factura
                    WHEN 2 THEN re.imp_factura*rq.tipo_cambio END) AS ImporteDoc, 
                    re.fecha_factura AS fecha_documento, 
                    '' AS fecha_pago, '' AS concepto, '' AS abono, '' AS id_tipo, 1 AS Fac, '' as numero_cheque 
                    FROM app_recepcion_xml re
                    INNER JOIN app_ocompra co ON co.id = re.id_oc
                    INNER JOIN app_requisiciones rq ON rq.id = co.id_requisicion
                    WHERE re.xmlfile != ''
                    AND CAST(re.fecha_factura AS DATE) <= '".$vars['f_fin']."' 
                    $proveedores2 
                    AND re.id_oc NOT IN(SELECT ppr.id_documento FROM app_pagos_relacion ppr INNER JOIN app_pagos pp ON pp.id = ppr.id_pago WHERE ppr.id_tipo = 1 AND pp.cobrar_pagar = 1)
                    GROUP BY Folio
                    
                    
                    UNION ALL

                    SELECT pr.id AS id_relacion, pa.id_prov_cli, (SELECT razon_social FROM mrp_proveedor WHERE IdPrv = pa.id_prov_cli) AS nombre_proveedor, 
                    pa2.id AS Folio, pa2.concepto AS SacarConcepto, pa2.cargo*pa2.tipo_cambio AS ImporteDoc, pa2.fecha_pago AS fecha_documento,
                    pa.fecha_pago, pa.concepto, pr.abono*pa.tipo_cambio, pr.id_tipo, 0 AS Fac , pa.numero_cheque
                    FROM app_pagos_relacion pr
                    INNER JOIN app_pagos pa ON pa.id = pr.id_pago
                    LEFT JOIN app_pagos pa2 ON pa2.id = pr.id_documento
                    WHERE pa.cobrar_pagar = 1
                    $proveedores 
                    AND pr.id_tipo = 0
                    AND pa.fecha_pago BETWEEN '".$vars['f_ini']."' AND '".$vars['f_fin']."' 

                    UNION ALL

                    SELECT '' AS id_relacion, 
                    pa.id_prov_cli, 
                    (SELECT razon_social FROM mrp_proveedor WHERE IdPrv = pa.id_prov_cli) AS nombre_proveedor, 
                    pa.id AS Folio, pa.concepto AS SacarConcepto, 
                    (pa.cargo*pa.tipo_cambio) AS ImporteDoc, 
                    pa.fecha_pago AS fecha_documento, 
                    '' AS fecha_pago, '' AS concepto, '' AS abono, '' AS id_tipo, 0 AS Fac , pa.numero_cheque
                    FROM app_pagos pa
                    WHERE pa.fecha_pago BETWEEN '".$vars['f_ini']."' AND '".$vars['f_fin']."' 
                    $proveedores 
                    AND pa.abono = 0
                    AND cobrar_pagar = 1
                    AND pa.id NOT IN (SELECT id_documento FROM app_pagos_relacion WHERE id_tipo = 0)

                    
                    UNION ALL

                    SELECT '' AS id_relacion, pa.id_prov_cli, (SELECT razon_social FROM mrp_proveedor WHERE IdPrv = pa.id_prov_cli) AS nombre_proveedor, CONCAT('Pago ',pa.id) AS Folio, pa.concepto AS SacarConcepto, IFNULL((SELECT SUM(re.abono*pa.tipo_cambio) FROM app_pagos_relacion re WHERE re.id_pago = pa.id),0)-(pa.abono*pa.tipo_cambio) AS ImporteDoc, pa.fecha_pago AS fecha_documento, '' AS fecha_pago, '' AS concepto, '' AS abono, '' AS id_tipo, 0 AS Fac , pa.numero_cheque
                        FROM app_pagos pa
                        WHERE 
                        cobrar_pagar = 1
                        $proveedores
                        AND pa.cargo = 0 
                        AND pa.fecha_pago BETWEEN '".$vars['f_ini']."' AND '".$vars['f_fin']."' 
                    ";
                    if(!intval($vars['todos_prov']))
                    {
                        $prov2 = str_replace('pa.id_prov_cli', 'idPrv', $proveedores);
                        $myQuery .= "UNION ALL 
                                    SELECT '' AS id_relacion, idPrv AS id_prov_cli, razon_social AS nombre_proveedor, '' AS Folio, '' AS SacarConcepto, '' AS ImporteDoc, '' AS fecha_documento, '' AS fecha_pago, '' AS concepto, '' AS abono, '' AS id_tipo, 0 AS Fac ,'' as numero_cheque 
                                    FROM mrp_proveedor
                                    WHERE idPrv NOT IN (SELECT id_prov_cli FROM app_pagos WHERE cobrar_pagar = 1) $prov2
                                    ";
                    }
                    $myQuery .= "ORDER BY id_prov_cli, Folio, fecha_pago, id_relacion";

        return $this->query($myQuery);
    }

    public function foliosFac($id_oc)
    {
        $lista = '';
        $res = $this->query("SELECT xmlfile FROM app_recepcion_xml WHERE id_oc = $id_oc");
        while($r = $res->fetch_object())
        {
            $a = explode('_',$r->xmlfile);
            if(!$a[0])
                $a[0] = $a[2];
            $lista .= $a[0].", ";
        }
        return $lista;
    }

    function saldoInicial($id_prov_cli,$f_ini)
    {
        $myQuery = "SELECT 
                    @f := (SELECT IFNULL(SUM(CASE rq.id_moneda 
                    WHEN 1 THEN re.imp_factura
                    WHEN 2 THEN re.imp_factura*rq.tipo_cambio END),0) FROM app_recepcion_xml re INNER JOIN app_ocompra co ON co.id = re.id_oc INNER JOIN app_requisiciones rq ON rq.id = co.id_requisicion WHERE co.id_proveedor = $id_prov_cli AND re.fecha_factura < '$f_ini' AND xmlfile != ''),
                    @c := (SELECT IFNULL(SUM(cargo*tipo_cambio),0) FROM app_pagos WHERE abono = 0 AND id_prov_cli = $id_prov_cli AND cobrar_pagar = 1 AND fecha_pago < '$f_ini'),

                    @p := (SELECT IFNULL(SUM(abono*tipo_cambio),0) FROM app_pagos WHERE cargo = 0 AND id_prov_cli = $id_prov_cli AND cobrar_pagar = 1 AND fecha_pago < '$f_ini'),

                    @a := (SELECT IFNULL(SUM(pr.abono*pa.tipo_cambio),0) FROM app_pagos_relacion pr INNER JOIN app_pagos pa ON pa.id = pr.id_pago WHERE pa.cobrar_pagar = 1 AND pa.id_prov_cli = $id_prov_cli AND pa.fecha_pago < '$f_ini'),

                    (@f + @c - @p) AS resultado
";
        $saldo = $this->query($myQuery);
        $abonos = $saldo->fetch_assoc();
        return $abonos['resultado'];
    }

    public function listaFormasPago()
    {
        return CuentasModel::listaFormasPago();
    }

    public function aux_movimientos_reporte($vars)
    {
        $idPrvs = $vars['idPrvs'];
        $proveedores = $proveedores2 = "";
        
        if(intval($vars['rango']))
        {
            $proveedores .= " AND pa.id_prov_cli BETWEEN ".$idPrvs[0]." AND ".$idPrvs[1];
            $proveedores2 .= " AND co.id_proveedor BETWEEN ".$idPrvs[0]." AND ".$idPrvs[1];
        }
        else
        {
            if($idPrvs[0])
            {
                $proveedores .= " AND (";
                $proveedores2 .= " AND (";

                for($i=0;$i<=count($idPrvs)-1;$i++)
                {
                    if($i>0)
                    {
                        $proveedores .= " OR ";
                        $proveedores2 .= " OR ";
                    }
                    $proveedores .= " pa.id_prov_cli = ".$idPrvs[$i];
                    $proveedores2 .= " co.id_proveedor = ".$idPrvs[$i];
                }
                $proveedores .= ") ";
                $proveedores2 .= ") ";
            }   
        }

        $formaPago = "";
        if(intval($vars['formaPago']))
            $formaPago = "AND pa.id_forma_pago = ".$vars['formaPago'];

        
        $myQuery = "SELECT pr.id AS id_relacion, pr.id_documento AS id_documento, pa.id_prov_cli, (SELECT CONCAT(razon_social,'*/*',diascredito) FROM mrp_proveedor WHERE IdPrv = pa.id_prov_cli) AS info_proveedor, 
                    re.id_oc AS Folio, re.concepto AS SacarConcepto, SUM(CASE rq.id_moneda WHEN 1 THEN re.imp_factura WHEN 2 THEN re.imp_factura*rq.tipo_cambio END) AS ImporteDoc, re.fecha_factura AS fecha_documento, 
                    pa.fecha_pago, pa.concepto, (pr.abono*pa.tipo_cambio) AS abono, pr.id_tipo, 1 AS Fac
                    FROM app_pagos_relacion pr
                    INNER JOIN app_pagos pa ON pa.id = pr.id_pago
                    LEFT JOIN app_recepcion_xml re ON re.id_oc = pr.id_documento
                    INNER JOIN app_ocompra co ON co.id = re.id_oc
                    INNER JOIN app_requisiciones rq ON rq.id = co.id_requisicion 
                    WHERE pa.cobrar_pagar = 1
                    $proveedores 
                    AND pr.id_tipo = 1
                    AND pa.fecha_pago BETWEEN '".$vars['f_ini']."' AND '".$vars['f_fin']."' $formaPago 
                    GROUP BY re.id_oc

                    UNION ALL 

                    SELECT '' AS id_relacion, re.id_oc AS id_documento, co.id_proveedor, 
                    (SELECT CONCAT(razon_social,'*/*',diascredito) FROM mrp_proveedor WHERE IdPrv = co.id_proveedor) AS info_proveedor, 
                    re.id_oc AS Folio, re.concepto AS SacarConcepto, 
                    SUM(CASE rq.id_moneda 
                    WHEN 1 THEN re.imp_factura
                    WHEN 2 THEN re.imp_factura*rq.tipo_cambio END) AS ImporteDoc, 
                    re.fecha_factura AS fecha_documento, 
                    '' AS fecha_pago, '' AS concepto, '' AS abono, 2 AS id_tipo, 1 AS Fac
                    FROM app_recepcion_xml re
                    INNER JOIN app_ocompra co ON co.id = re.id_oc
                    LEFT JOIN app_requisiciones rq ON rq.id = co.id_requisicion
                    WHERE re.fecha_factura BETWEEN '".$vars['f_ini']."' AND '".$vars['f_fin']."'
                    $proveedores2 
                    AND re.id_oc NOT IN(SELECT ppr.id_documento FROM app_pagos_relacion ppr INNER JOIN app_pagos pp ON pp.id = ppr.id_pago WHERE ppr.id_tipo = 1 AND pp.cobrar_pagar = 1)
                    GROUP BY re.id_oc

                    UNION ALL

                    SELECT pr.id AS id_relacion, pr.id_documento, pa.id_prov_cli, (SELECT CONCAT(razon_social,'*/*',diascredito) FROM mrp_proveedor WHERE IdPrv = pa.id_prov_cli) AS info_proveedor, 
                    pa2.id AS Folio, pa2.concepto AS SacarConcepto, (pa2.cargo*pa2.tipo_cambio) AS ImporteDoc, pa2.fecha_pago AS fecha_documento,
                    pa.fecha_pago, pa.concepto, (pr.abono*pa.tipo_cambio) AS abono, pr.id_tipo, 0 AS Fac
                    FROM app_pagos_relacion pr
                    INNER JOIN app_pagos pa ON pa.id = pr.id_pago
                    LEFT JOIN app_pagos pa2 ON pa2.id = pr.id_documento
                    WHERE pa.cobrar_pagar = 1
                    $proveedores 
                    AND pr.id_tipo = 0
                    AND pa.fecha_pago BETWEEN '".$vars['f_ini']."' AND '".$vars['f_fin']."'  $formaPago 

                     UNION ALL

                    SELECT '' AS id_relacion,pa.id AS id_documento, 
                    pa.id_prov_cli, 
                    (SELECT CONCAT(razon_social,'*/*',diascredito) FROM mrp_proveedor WHERE IdPrv = pa.id_prov_cli) AS info_proveedor, 
                    pa.id AS Folio, pa.concepto AS SacarConcepto, 
                    (pa.cargo*pa.tipo_cambio) AS ImporteDoc, 
                    pa.fecha_pago AS fecha_documento, 
                    '' AS fecha_pago, '' AS concepto, '' AS abono, 0 AS id_tipo, 0 AS Fac
                    FROM app_pagos pa
                    WHERE pa.fecha_pago BETWEEN '".$vars['f_ini']."' AND '".$vars['f_fin']."' 
                    $proveedores
                    AND pa.abono = 0
                    AND cobrar_pagar = 1
                    AND pa.id NOT IN (SELECT id_documento FROM app_pagos_relacion WHERE id_tipo = 0)

                     UNION ALL

                    SELECT '' AS id_relacion, pa.id AS id_documento, pa.id_prov_cli, (SELECT CONCAT(razon_social,'*/*',diascredito) FROM mrp_proveedor WHERE IdPrv = pa.id_prov_cli) AS info_proveedor, CONCAT('Pago ',pa.id) AS Folio, pa.concepto AS SacarConcepto, IFNULL((SELECT SUM(re.abono*pa.tipo_cambio) FROM app_pagos_relacion re WHERE re.id_pago = pa.id),0)-(pa.abono*tipo_cambio) AS ImporteDoc, pa.fecha_pago AS fecha_documento, '' AS fecha_pago, '' AS concepto, '' AS abono, 2 AS id_tipo, 0 AS Fac
                        FROM app_pagos pa
                        WHERE 
                        cobrar_pagar = 1
                        $proveedores
                        AND pa.cargo = 0 
                        AND pa.fecha_pago BETWEEN '".$vars['f_ini']."' AND '".$vars['f_fin']."'

                    ORDER BY id_prov_cli, Folio, id_documento, fecha_pago, id_relacion
                    ";
                    

        return $this->query($myQuery);
    }

    function saldoInicialFactura($id_documento,$id_tipo,$f_ini,$cp)//Se hizo copia de esta funcion en cuentas.php
    {
        $myQuery = "SELECT IFNULL(SUM(pr.abono*pa.tipo_cambio),0) AS resultado FROM app_pagos_relacion pr INNER JOIN app_pagos pa ON pa.id = pr.id_pago WHERE pa.cobrar_pagar = $cp AND pr.id_documento = $id_documento AND pa.fecha_pago < '$f_ini' AND pr.id_tipo = $id_tipo";

        $saldo = $this->query($myQuery);
        $abonos = $saldo->fetch_assoc();
        return $abonos['resultado'];
    }

    function ant_saldos_reporte($vars)
    {
        $idPrvs = $vars['idPrvs'];
        $proveedores = $proveedores2 = "";
        
        if(intval($vars['rango']))
        {
            $proveedores .= " AND pa.id_prov_cli BETWEEN ".$idPrvs[0]." AND ".$idPrvs[1];
            $proveedores2 .= " AND co.id_proveedor BETWEEN ".$idPrvs[0]." AND ".$idPrvs[1];
        }
        else
        {
            if($idPrvs[0])
            {
                $proveedores .= " AND (";
                $proveedores2 .= " AND (";
                for($i=0;$i<=count($idPrvs)-1;$i++)
                {
                    if($i>0)
                    {
                        $proveedores .= " OR ";
                        $proveedores2 .= " OR ";
                    }
                    $proveedores .= " pa.id_prov_cli = ".$idPrvs[$i];
                    $proveedores2 .= " co.id_proveedor = ".$idPrvs[$i];
                }
                $proveedores .= ") ";
                $proveedores2 .= ") ";
            }   
        }

        $where1 = "AND pa.fecha_pago <= '".$vars['f_cor']."' AND re.fecha_factura <= '".$vars['f_cor']."'";
        $where2 = "AND pa.fecha_pago <= '".$vars['f_cor']."' AND pa2.fecha_pago <= '".$vars['f_cor']."'";
        $where3 = "AND re.fecha_factura <= '".$vars['f_cor']."'";
        $where4 = "AND pa.fecha_pago <= '".$vars['f_cor']."'";
        if(!isset($vars['f_cor']))
        {
            $where1 = "AND pa.fecha_pago <= '".$vars['f_fin']."' HAVING DATE_ADD(fecha_documento, INTERVAL SUBSTRING_INDEX(info_proveedor,'*/*',-1) DAY) BETWEEN  '".$vars['f_ini']."' AND '".$vars['f_fin']."' ";
            $where2 = "AND pa.fecha_pago <= '".$vars['f_fin']."' HAVING DATE_ADD(fecha_documento, INTERVAL SUBSTRING_INDEX(info_proveedor,'*/*',-1) DAY) BETWEEN  '".$vars['f_ini']."' AND '".$vars['f_fin']."' ";
            $where3 = "AND CAST(re.fecha_factura AS DATE) <= '".$vars['f_fin']."' HAVING DATE_ADD(fecha_documento, INTERVAL SUBSTRING_INDEX(info_proveedor,'*/*',-1) DAY) BETWEEN  '".$vars['f_ini']."' AND '".$vars['f_fin']."' ";
            $where4 = $where1;
        }
        
        $myQuery = "SELECT pr.id AS id_relacion, pr.id_documento, pa.id_prov_cli, (SELECT CONCAT(razon_social,'*/*',diascredito) FROM mrp_proveedor WHERE IdPrv = pa.id_prov_cli) AS info_proveedor, 
                    re.id_oc AS Folio, re.concepto AS SacarConcepto, SUM(CASE rq.id_moneda WHEN 1 THEN re.imp_factura WHEN 2 THEN re.imp_factura*rq.tipo_cambio END) AS ImporteDoc, re.fecha_factura AS fecha_documento, 
                    pa.fecha_pago, pa.concepto, pr.abono, pr.id_tipo, 1 AS Fac 
                    FROM app_pagos_relacion pr
                    INNER JOIN app_pagos pa ON pa.id = pr.id_pago
                    LEFT JOIN app_recepcion_xml re ON re.id_oc = pr.id_documento
                    INNER JOIN app_ocompra co ON co.id = re.id_oc
                    INNER JOIN app_requisiciones rq ON rq.id = co.id_requisicion 
                    WHERE pa.cobrar_pagar = 1
                    $proveedores
                    AND pr.id_tipo = 1
                    $where1 
                    GROUP BY re.id_oc
                    
                    
                    UNION ALL

                    SELECT '' AS id_relacion, re.id_oc AS id_documento , co.id_proveedor AS id_prov_cli, (SELECT CONCAT(razon_social,'*/*',diascredito) FROM mrp_proveedor WHERE IdPrv = co.id_proveedor) AS info_proveedor, re.id_oc AS Folio, re.concepto AS SacarConcepto, SUM(CASE rq.id_moneda WHEN 1 THEN re.imp_factura WHEN 2 THEN re.imp_factura*rq.tipo_cambio END) AS ImporteDoc, re.fecha_factura AS fecha_documento,
                    '' AS fecha_pago, '' AS concepto, '' AS abono, 1 AS id_tipo, 1 AS Fac 
                    FROM app_ocompra co
                    INNER JOIN app_requisiciones rq ON rq.id = co.id_requisicion 
                    INNER JOIN app_recepcion_xml re ON re.id_oc = co.id
                    WHERE re.xmlfile != ''
                    $proveedores2 
                    $where3 
                    AND re.id_oc NOT IN(SELECT ppr.id_documento FROM app_pagos_relacion ppr INNER JOIN app_pagos pp ON pp.id = ppr.id_pago WHERE ppr.id_tipo = 1 AND pp.cobrar_pagar = 1)
                    GROUP BY re.id_oc

                    UNION ALL

                    SELECT pr.id AS id_relacion, pr.id_documento, pa.id_prov_cli, (SELECT CONCAT(razon_social,'*/*',diascredito) FROM mrp_proveedor WHERE IdPrv = pa.id_prov_cli) AS info_proveedor, 
                    pa2.id AS Folio, pa2.concepto AS SacarConcepto, (pa2.cargo*pa2.tipo_cambio) AS ImporteDoc, pa2.fecha_pago AS fecha_documento,
                    pa.fecha_pago, pa.concepto, pr.abono, pr.id_tipo, 0 AS Fac 
                    FROM app_pagos_relacion pr
                    INNER JOIN app_pagos pa ON pa.id = pr.id_pago
                    LEFT JOIN app_pagos pa2 ON pa2.id = pr.id_documento
                    WHERE pa.cobrar_pagar = 1 
                    $proveedores 
                    AND pr.id_tipo = 0
                    $where2 

                    UNION ALL

                    SELECT '' AS id_relacion, pa.id AS id_documento, pa.id_prov_cli, (SELECT CONCAT(razon_social,'*/*',diascredito) FROM mrp_proveedor WHERE IdPrv = pa.id_prov_cli) AS info_proveedor, 
                    pa.id AS Folio, pa.concepto AS SacarConcepto, (pa.cargo*pa.tipo_cambio) AS ImporteDoc, pa.fecha_pago AS fecha_documento,
                    '' AS fecha_pago, '' AS concepto, '' AS abono, 2 AS id_tipo, 0 AS Fac 
                    FROM app_pagos pa
                    WHERE pa.cobrar_pagar = 1 
                    $proveedores 
                    AND pa.id NOT IN (SELECT id_documento FROM app_pagos_relacion WHERE id_tipo = 0)

                    UNION ALL

                    SELECT '' AS id_relacion, pa.id AS id_documento, pa.id_prov_cli, (SELECT razon_social FROM mrp_proveedor WHERE idPrv = pa.id_prov_cli) AS nombre_cliente,  CONCAT('Pago ',pa.id) AS Folio, pa.concepto AS SacarConcepto, IFNULL((SELECT SUM(re.abono*pa.tipo_cambio) FROM app_pagos_relacion re WHERE re.id_pago = pa.id),0)-(pa.abono*tipo_cambio) AS ImporteDoc, pa.fecha_pago AS fecha_documento, '' AS fecha_pago, '' AS concepto, '' AS abono, 2 AS id_tipo, 0 AS Fac 
                        FROM app_pagos pa
                        WHERE 
                        cobrar_pagar = 1
                        $proveedores
                        AND pa.cargo = 0 
                        $where4


                    
                    ORDER BY id_prov_cli, Folio, fecha_pago, id_relacion
                    ";
                    

        return $this->query($myQuery);
    }

    //-----------CUENTAS POR COBRAR------------

     public function generar_reporte_cobrar($vars)
    {
         $ids = $vars['ids'];
        $clientes = "";
        $clientes2 = "";
        
        if(intval($vars['rango']))
        {
            $clientes .= " AND pa.id_prov_cli BETWEEN ".$ids[0]." AND ".$ids[1];
            $clientes2 .= " AND v.idCliente BETWEEN ".$ids[0]." AND ".$ids[1];
        }
        else
        {
            if($ids[0])
            {
                $clientes .= " AND (";
                $clientes2 .= " AND (";
                for($i=0;$i<=count($ids)-1;$i++)
                {
                    if($i>0)
                    {
                        $clientes .= " OR ";
                        $clientes2 .= " OR ";
                    }
                    $clientes .= " pa.id_prov_cli = ".$ids[$i];
                    $clientes2 .= " v.idCliente = ".$ids[$i];
                }
                $clientes .= ") ";
                $clientes2 .= ") ";
            }   
        }
        //Trae las facturas que tienen pagos asociados
        $myQuery = "SELECT pr.id AS id_relacion, pa.id_prov_cli, (SELECT nombre FROM comun_cliente WHERE id = pa.id_prov_cli) AS nombre_cliente,  
                    rf.folio AS Folio, CONCAT('Venta POS: ',v.idVenta)  AS SacarConcepto, 
                    CASE v.moneda
                    WHEN 0 THEN vp.monto
                    WHEN 1 THEN vp.monto
                    WHEN 2 THEN (vp.monto/IF(v.tipo_cambio = 0,1,v.tipo_cambio)) END AS ImporteDoc, 
                    rf.fecha AS fecha_documento, 
                    pa.fecha_pago, pa.concepto, ROUND(pr.abono*pa.tipo_cambio,2) AS abono, pr.id_tipo
                    FROM app_pagos_relacion pr
                    INNER JOIN app_pagos pa ON pa.id = pr.id_pago
                    LEFT JOIN app_respuestaFacturacion rf ON rf.id = pr.id_documento
                    INNER JOIN app_pos_venta_pagos vp ON vp.idVenta = rf.IdSale
                    INNER JOIN app_pos_venta v ON v.idVenta = vp.idVenta
                    WHERE pa.cobrar_pagar = 0
                    $clientes
                    AND pr.id_tipo = 1 AND rf.borrado = 0
                    AND pa.fecha_pago BETWEEN '".$vars['f_ini']."' AND '".$vars['f_fin']."' ";

        
        //Trae las facturas que no tienen pagos asociados
        $myQuery .= "
                    UNION ALL

                    SELECT
                    '' AS id_relacion, v.idCliente AS id_prov_cli, 
                    (SELECT nombre FROM comun_cliente WHERE id = v.idCliente) AS nombre_cliente, 
                    rf.folio AS Folio, CONCAT('Venta POS: ',v.idVenta)  AS SacarConcepto, 
                    CASE v.moneda
                    WHEN 0 THEN vp.monto
                    WHEN 1 THEN vp.monto
                    WHEN 2 THEN (vp.monto/IF(v.tipo_cambio = 0,1,v.tipo_cambio)) END AS ImporteDoc, 
                     rf.fecha AS fecha_documento, 
                    '' AS fecha_pago, '' AS concepto, '' AS abono, '' AS id_tipo
                    FROM app_respuestaFacturacion rf
                    INNER JOIN app_pos_venta_pagos vp ON vp.idVenta = rf.IdSale
                    INNER JOIN app_pos_venta v ON v.idVenta = vp.idVenta
                    WHERE 
                    rf.fecha <= '".$vars['f_fin']."' 
                    $clientes2 
                    AND rf.tipoComp = 'F' AND rf.borrado = 0 AND vp.idFormapago = 6 
                    AND rf.id NOT IN(SELECT ppr.id_documento FROM app_pagos_relacion ppr INNER JOIN app_pagos pp ON pp.id = ppr.id_pago WHERE ppr.id_tipo = 1 AND pp.cobrar_pagar = 0) ";

                    
        //Trae los cargos asociados a pagos            
        $myQuery .= "
                    UNION ALL

                    SELECT pr.id AS id_relacion, pa.id_prov_cli, (SELECT nombre FROM comun_cliente WHERE id = pa.id_prov_cli) AS nombre_cliente, 
                    pa2.id AS Folio, pa2.concepto AS SacarConcepto, pa2.cargo*pa2.tipo_cambio AS ImporteDoc, pa2.fecha_pago AS fecha_documento,
                    pa.fecha_pago, pa.concepto, (pr.abono*pa.tipo_cambio) AS abono, pr.id_tipo
                    FROM app_pagos_relacion pr
                    INNER JOIN app_pagos pa ON pa.id = pr.id_pago
                    LEFT JOIN app_pagos pa2 ON pa2.id = pr.id_documento
                    WHERE pa.cobrar_pagar = 0
                    $clientes
                    AND pr.id_tipo = 0
                    AND pa.fecha_pago BETWEEN '".$vars['f_ini']."' AND '".$vars['f_fin']."' ";

         //Trae los cargos que no estan asociados a pagos
        $myQuery .= "
                    UNION ALL

                    SELECT '' AS id_relacion, 
                    pa.id_prov_cli, 
                    (SELECT nombre FROM comun_cliente WHERE id = pa.id_prov_cli) AS nombre_cliente, 
                    pa.id AS Folio, pa.concepto AS SacarConcepto, 
                    (pa.cargo*pa.tipo_cambio) AS ImporteDoc, 
                    pa.fecha_pago AS fecha_documento, 
                    '' AS fecha_pago, '' AS concepto, '' AS abono, '' AS id_tipo
                    FROM app_pagos pa
                    WHERE pa.fecha_pago BETWEEN '".$vars['f_ini']."' AND '".$vars['f_fin']."' 
                    $clientes 
                    AND pa.abono = 0
                    AND cobrar_pagar = 0
                    AND pa.id NOT IN (SELECT id_documento FROM app_pagos_relacion WHERE id_tipo = 0) ";

        //Trae los pagos sin asociar
        $myQuery .= "
                   UNION ALL

                    SELECT '' AS id_relacion, pa.id_prov_cli, (SELECT nombre FROM comun_cliente WHERE id = pa.id_prov_cli) AS nombre_cliente,  CONCAT('Pago ',pa.id) AS Folio, pa.concepto AS SacarConcepto, IFNULL((SELECT SUM(re.abono*pa.tipo_cambio) FROM app_pagos_relacion re WHERE re.id_pago = pa.id),0)-(pa.abono*tipo_cambio) AS ImporteDoc, pa.fecha_pago AS fecha_documento, '' AS fecha_pago, '' AS concepto, '' AS abono, '' AS id_tipo
                        FROM app_pagos pa
                        WHERE 
                        cobrar_pagar = 0
                        $clientes 
                        AND pa.cargo = 0 
                        AND pa.fecha_pago BETWEEN '".$vars['f_ini']."' AND '".$vars['f_fin']."' 
                    
                    
                    ";
                   if(!intval($vars['todos_cli']))
                    {
                        $cli2 = str_replace('pa.id_prov_cli', 'id', $clientes);

                        //Trae el resto de los clientes que no tienen pagos
                        $myQuery .= "UNION ALL 
                                    SELECT '' AS id_relacion, id AS id_prov_cli, nombre AS nombre_cliente, '' AS Folio, '' AS SacarConcepto, '' AS ImporteDoc, '' AS fecha_documento, '' AS fecha_pago, '' AS concepto, '' AS abono, '' AS id_tipo
                                    FROM comun_cliente
                                    WHERE id NOT IN (SELECT id_prov_cli FROM app_pagos WHERE cobrar_pagar = 0) $cli2

                                    ";
                    }

                    $myQuery .= "ORDER BY id_prov_cli, Folio, fecha_pago, id_relacion";

        return $this->query($myQuery);
    }

    function saldoInicialCobrar($id_prov_cli,$f_ini)
    {
       
        $myQuery = "SELECT 
                    @fs := (SELECT IFNULL(SUM(CASE rq.id_moneda 
                    WHEN 1 THEN en.total
                    WHEN 2 THEN en.total*rq.tipo_cambio END),0) FROM app_respuestaFacturacion rf INNER JOIN app_envios en ON en.id = rf.idSale INNER JOIN app_oventa ve ON ve.id = en.id_oventa INNER JOIN app_requisiciones_venta rq ON rq.id = ve.id_requisicion WHERE ve.id_cliente = $id_prov_cli AND rf.tipoComp = 'F' AND rf.fecha < '$f_ini'),
                    @fm := (SELECT IFNULL(SUM(CASE rq.id_moneda 
                    WHEN 1 THEN pf.monto
                    WHEN 2 THEN pf.monto*rq.tipo_cambio END),0) FROM app_pendienteFactura pf LEFT JOIN app_respuestaFacturacion rf ON pf.id_respFact = rf.id
                    INNER JOIN app_envios en ON en.id = pf.id_sale AND en.forma_pago = 6 INNER JOIN app_oventa ve ON ve.id = en.id_oventa INNER JOIN app_requisiciones_venta rq ON rq.id = ve.id_requisicion WHERE rf.fecha < '$f_ini' 
                    AND ve.id_cliente = $id_prov_cli AND rf.tipoComp = 'F' AND pf.id_respFact != 0 AND rf.idSale = 0 AND rf.idFact != 0  ),

                    @c := (SELECT IFNULL(SUM(cargo*tipo_cambio),0) FROM app_pagos WHERE abono = 0 AND id_prov_cli = $id_prov_cli AND cobrar_pagar = 0 AND fecha_pago < '$f_ini'),
                    @p := (SELECT IFNULL(SUM(abono*tipo_cambio),0) FROM app_pagos WHERE cargo = 0 AND id_prov_cli = $id_prov_cli AND cobrar_pagar = 0 AND fecha_pago < '$f_ini'),

                    @a := (SELECT IFNULL(SUM(pr.abono*pa.tipo_cambio),0) FROM app_pagos_relacion pr INNER JOIN app_pagos pa ON pa.id = pr.id_pago WHERE pa.cobrar_pagar = 0 AND pa.id_prov_cli = $id_prov_cli AND pa.fecha_pago < '$f_ini'),

                    (@fs + @fm + @c - @p) AS resultado
                    ";
        $saldo = $this->query($myQuery);
        $abonos = $saldo->fetch_assoc();
        return $abonos['resultado'];
    }

    public function aux_movimientos_reporte_cobrar($vars)
    {
        $ids = $vars['ids'];
        $clientes = $clientes2 = "";
        
        if(intval($vars['rango']))
        {
            $clientes .= " AND pa.id_prov_cli BETWEEN ".$ids[0]." AND ".$ids[1];
            $clientes2 .= " AND ve.id_cliente BETWEEN ".$ids[0]." AND ".$ids[1];
        }
        else
        {
            if($ids[0])
            {
                $clientes .= " AND (";
                $clientes2 .= " AND (";

                for($i=0;$i<=count($ids)-1;$i++)
                {
                    if($i>0)
                    {
                        $clientes .= " OR ";
                        $clientes2 .= " OR ";
                    }
                    $clientes .= " pa.id_prov_cli = ".$ids[$i];
                    $clientes2 .= " ve.id_cliente = ".$ids[$i];
                }
                $clientes .= ") ";
                $clientes2 .= ") ";
            }   
        }

        $formaPago = "";
        if(intval($vars['formaPago']))
            $formaPago = "AND pa.id_forma_pago = ".$vars['formaPago'];

        $myQuery = "SELECT pr.id AS id_relacion, pr.id_documento, pa.id_prov_cli, (SELECT CONCAT(nombre,'*/*',dias_credito) FROM comun_cliente WHERE id = pa.id_prov_cli) AS info_cliente, 
                    rf.folio AS Folio, en.desc_concepto AS SacarConcepto, 
                    CASE rq.id_moneda 
                    WHEN 1 THEN en.total
                    WHEN 2 THEN en.total*rq.tipo_cambio END AS ImporteDoc, 
                    rf.fecha AS fecha_documento, 
                    pa.fecha_pago, pa.concepto, (pr.abono*pa.tipo_cambio) AS abono, pr.id_tipo
                    FROM app_pagos_relacion pr
                    INNER JOIN app_pagos pa ON pa.id = pr.id_pago
                    LEFT JOIN app_respuestaFacturacion rf ON rf.id = pr.id_documento
                    LEFT JOIN app_envios en ON en.id = rf.idSale
                    LEFT JOIN app_oventa ve ON ve.id = en.id_oventa
                    LEFT JOIN app_requisiciones_venta rq ON rq.id = ve.id_requisicion
                    
                    WHERE pa.cobrar_pagar = 0
                    $clientes 
                    AND pr.id_tipo = 1
                    AND pa.fecha_pago BETWEEN '".$vars['f_ini']."' AND '".$vars['f_fin']."' $formaPago 
                    AND rf.idSale != 0

                    UNION ALL

                    SELECT '' AS id_relacion, en.id AS id_documento, ve.id_cliente, 
                    (SELECT nombre FROM comun_cliente WHERE id = ve.id_cliente) AS nombre_cliente,  
                    rf.folio AS Folio, en.desc_concepto AS SacarConcepto, 
                    CASE rq.id_moneda 
                    WHEN 1 THEN en.total
                    WHEN 2 THEN en.total*rq.tipo_cambio END AS ImporteDoc, 
                    rf.fecha AS fecha_documento, 
                    '' AS fecha_pago, '' AS concepto, '' AS abono, 2 AS id_tipo
                    FROM app_respuestaFacturacion rf 
                    INNER JOIN app_envios en ON en.id = rf.idSale
                    INNER JOIN app_oventa ve ON ve.id = en.id_oventa
                    LEFT JOIN app_requisiciones_venta rq ON rq.id = ve.id_requisicion
                    
                    WHERE rf.fecha BETWEEN '".$vars['f_ini']."' AND '".$vars['f_fin']."'
                    $clientes2 
                    AND rf.tipoComp = 'F'
                    AND rf.idSale != 0
                    AND en.id NOT IN(SELECT ppr.id_documento FROM app_pagos_relacion ppr INNER JOIN app_pagos pp ON pp.id = ppr.id_pago WHERE ppr.id_tipo = 1 AND pp.cobrar_pagar = 0)

                    UNION ALL

                     SELECT pr.id AS id_relacion, pr.id_documento, pa.id_prov_cli, (SELECT CONCAT(nombre,'*/*',dias_credito) FROM comun_cliente WHERE id = pa.id_prov_cli) AS info_cliente, 
                    rf.folio AS Folio, en.desc_concepto AS SacarConcepto, 
                    CASE rq.id_moneda 
                    WHEN 1 THEN SUM(pf.monto)
                    WHEN 2 THEN SUM(pf.monto*rq.tipo_cambio) END AS ImporteDoc, 
                    rf.fecha AS fecha_documento, 
                    pa.fecha_pago, pa.concepto, (pr.abono*pa.tipo_cambio) AS abono, pr.id_tipo
                    FROM app_pagos_relacion pr
                    INNER JOIN app_pagos pa ON pa.id = pr.id_pago
                    LEFT JOIN app_respuestaFacturacion rf ON rf.id = pr.id_documento
                    LEFT JOIN app_pendienteFactura pf ON pf.id_respFact = rf.id
                    LEFT JOIN app_envios en ON en.id = pf.id_sale
                    INNER JOIN app_oventa ve ON ve.id = en.id_oventa
                    INNER JOIN app_requisiciones_venta rq ON rq.id = ve.id_requisicion
                    WHERE pa.cobrar_pagar = 0
                    $clientes
                    AND pr.id_tipo = 1
                    AND pa.fecha_pago BETWEEN '".$vars['f_ini']."' AND '".$vars['f_fin']."'
                    AND pf.id_respFact != 0
                    AND rf.idSale = 0
                    GROUP BY id_relacion

                    UNION ALL 

                    SELECT '' AS id_relacion, en.id AS id_documento, ve.id_cliente, 
                    (SELECT nombre FROM comun_cliente WHERE id = ve.id_cliente) AS nombre_cliente,  
                    rf.folio AS Folio, en.desc_concepto AS SacarConcepto, 
                    CASE rq.id_moneda 
                    WHEN 1 THEN SUM(pf.monto)
                    WHEN 2 THEN SUM(pf.monto*rq.tipo_cambio) END AS ImporteDoc, 
                    rf.fecha AS fecha_documento, 
                    '' AS fecha_pago, '' AS concepto, '' AS abono, 2 AS id_tipo
                    FROM app_respuestaFacturacion rf
                    LEFT JOIN app_pendienteFactura pf ON pf.id_respFact = rf.id
                    LEFT JOIN app_envios en ON en.id = pf.id_sale
                    INNER JOIN app_oventa ve ON ve.id = en.id_oventa
                    INNER JOIN app_requisiciones_venta rq ON rq.id = ve.id_requisicion
                    WHERE 
                    rf.fecha BETWEEN '".$vars['f_ini']."' AND '".$vars['f_fin']."'
                    $clientes2
                    AND pf.id_respFact != 0
                    AND rf.idSale = 0
                    AND rf.idFact != 0
                    AND rf.id NOT IN(SELECT ppr.id_documento FROM app_pagos_relacion ppr INNER JOIN app_pagos pp ON pp.id = ppr.id_pago WHERE ppr.id_tipo = 1 AND pp.cobrar_pagar = 0)
                    GROUP BY pf.id_respFact

                    UNION ALL

                    SELECT pr.id AS id_relacion, pr.id_documento, pa.id_prov_cli, (SELECT CONCAT(nombre,'*/*',dias_credito) FROM comun_cliente WHERE id = pa.id_prov_cli) AS info_cliente, 
                    pa2.id AS Folio, pa2.concepto AS SacarConcepto, (pa2.cargo*pa2.tipo_cambio) AS ImporteDoc, pa2.fecha_pago AS fecha_documento,
                    pa.fecha_pago, pa.concepto, (pr.abono*pa.tipo_cambio) AS abono, pr.id_tipo
                    FROM app_pagos_relacion pr
                    INNER JOIN app_pagos pa ON pa.id = pr.id_pago
                    LEFT JOIN app_pagos pa2 ON pa2.id = pr.id_documento
                    WHERE pa.cobrar_pagar = 0
                    $clientes 
                    AND pr.id_tipo = 0
                    AND pa.fecha_pago BETWEEN '".$vars['f_ini']."' AND '".$vars['f_fin']."'  $formaPago 

                    UNION ALL

                    SELECT '' AS id_relacion,pa.id AS id_documento, 
                    pa.id_prov_cli, 
                    (SELECT nombre FROM comun_cliente WHERE id = pa.id_prov_cli) AS info_cliente, 
                    pa.id AS Folio, pa.concepto AS SacarConcepto, 
                    (pa.cargo*pa.tipo_cambio) AS ImporteDoc, 
                    pa.fecha_pago AS fecha_documento, 
                    '' AS fecha_pago, '' AS concepto, '' AS abono, 0 AS id_tipo
                    FROM app_pagos pa
                    WHERE pa.fecha_pago BETWEEN '".$vars['f_ini']."' AND '".$vars['f_fin']."' 
                    $clientes 
                    AND pa.abono = 0
                    AND cobrar_pagar = 0
                    AND pa.id NOT IN (SELECT id_documento FROM app_pagos_relacion WHERE id_tipo = 0)

                     UNION ALL

                    SELECT '' AS id_relacion, pa.id AS id_documento, pa.id_prov_cli, (SELECT nombre FROM comun_cliente WHERE id = pa.id_prov_cli) AS nombre_cliente,  CONCAT('Pago ',pa.id) AS Folio, pa.concepto AS SacarConcepto, IFNULL((SELECT SUM(re.abono*pa.tipo_cambio) FROM app_pagos_relacion re WHERE re.id_pago = pa.id),0)-(pa.abono*tipo_cambio) AS ImporteDoc, pa.fecha_pago AS fecha_documento, '' AS fecha_pago, '' AS concepto, '' AS abono, 2 AS id_tipo
                        FROM app_pagos pa
                        WHERE 
                        cobrar_pagar = 0
                        $clientes 
                        AND pa.cargo = 0 
                        AND pa.fecha_pago BETWEEN '".$vars['f_ini']."' AND '".$vars['f_fin']."'

                    ORDER BY id_prov_cli, Folio, fecha_pago, id_relacion
                    ";
                    

        return $this->query($myQuery);
    }

    function ant_saldos_reporte_cxc($vars)
    {
        
        $ids = $vars['ids'];
        $clientes = $clientes2 = "";
        
        if(intval($vars['rango']))
        {
            $clientes .= " AND pa.id_prov_cli BETWEEN ".$ids[0]." AND ".$ids[1];
            $clientes2 .= " AND ve.id_cliente BETWEEN ".$ids[0]." AND ".$ids[1];
        }
        else
        {
            if($ids[0])
            {
                $clientes .= " AND (";
                $clientes2 .= " AND (";
                for($i=0;$i<=count($ids)-1;$i++)
                {
                    if($i>0)
                    {
                        $clientes .= " OR ";
                        $clientes2 .= " OR ";
                    }
                    $clientes .= " pa.id_prov_cli = ".$ids[$i];
                    $clientes2 .= " ve.id_cliente = ".$ids[$i];
                }
                $clientes .= ") ";
                $clientes2 .= ") ";
            }   
        }

        $where1 = "AND pa.fecha_pago <= '".$vars['f_cor']."' AND en.fecha_factura <= '".$vars['f_cor']."'";
        $where2 = "AND pa.fecha_pago <= '".$vars['f_cor']."' AND pa2.fecha_pago <= '".$vars['f_cor']."'";
        $where3 = "AND en.fecha_factura <= '".$vars['f_cor']."'";
        $where4 = "AND pa.fecha_pago <= '".$vars['f_cor']."'";
        if(!isset($vars['f_cor']))
        {
            $where1 = "AND pa.fecha_pago <= '".$vars['f_fin']."' HAVING DATE_ADD(fecha_documento, INTERVAL SUBSTRING_INDEX(info_cliente,'*/*',-1) DAY) BETWEEN  '".$vars['f_ini']."' AND '".$vars['f_fin']."' ";
            $where2 = "AND pa.fecha_pago <= '".$vars['f_fin']."' HAVING DATE_ADD(fecha_documento, INTERVAL SUBSTRING_INDEX(info_cliente,'*/*',-1) DAY) BETWEEN  '".$vars['f_ini']."' AND '".$vars['f_fin']."' ";
            $where3 = "AND CAST(en.fecha_factura AS DATE) <= '".$vars['f_fin']."' HAVING DATE_ADD(fecha_documento, INTERVAL SUBSTRING_INDEX(info_proveedor,'*/*',-1) DAY) BETWEEN  '".$vars['f_ini']."' AND '".$vars['f_fin']."' ";
            $where4 = $where1;
        }
        
        $myQuery = "SELECT pr.id AS id_relacion, pr.id_documento, pa.id_prov_cli, (SELECT CONCAT(nombre,'*/*',dias_credito) FROM comun_cliente WHERE id = pa.id_prov_cli) AS info_cliente, 
                    rf.folio AS Folio, en.desc_concepto AS SacarConcepto, 
                    CASE rq.id_moneda 
                    WHEN 1 THEN en.total
                    WHEN 2 THEN en.total*rq.tipo_cambio END AS ImporteDoc, 
                    rf.fecha AS fecha_documento, 
                    pa.fecha_pago, pa.concepto, ROUND(pr.abono*pa.tipo_cambio,2) AS abono, pr.id_tipo
                    FROM app_pagos_relacion pr
                    INNER JOIN app_pagos pa ON pa.id = pr.id_pago
                    LEFT JOIN app_respuestaFacturacion rf ON rf.id = pr.id_documento
                    LEFT JOIN app_envios en ON en.id = rf.idSale
                    LEFT JOIN app_oventa ve ON ve.id = en.id_oventa
                    LEFT JOIN app_requisiciones rq ON rq.id = ve.id_requisicion 
                    WHERE pa.cobrar_pagar = 0
                    $clientes
                    AND pr.id_tipo = 1
                    $where1 
                    
                    UNION ALL

                    SELECT '' AS id_relacion, en.id AS id_documento , ve.id_cliente AS id_prov_cli, (SELECT CONCAT(nombre,'*/*',dias_credito) FROM comun_cliente WHERE id = ve.id_cliente) AS info_cliente, rf.folio AS Folio, en.desc_concepto AS SacarConcepto, 
                    CASE rq.id_moneda 
                    WHEN 1 THEN en.total
                    WHEN 2 THEN en.total*rq.tipo_cambio END AS ImporteDoc, 
                    rf.fecha AS fecha_documento,
                    '' AS fecha_pago, '' AS concepto, '' AS abono, 1 AS id_tipo
                    FROM app_respuestaFacturacion rf 
                    INNER JOIN app_envios en ON en.id = rf.idSale
                    LEFT JOIN app_oventa ve ON ve.id = en.id_oventa
                    LEFT JOIN app_requisiciones rq ON rq.id = ve.id_requisicion 
                    WHERE 
                    rf.id NOT IN(SELECT ppr.id_documento FROM app_pagos_relacion ppr INNER JOIN app_pagos pp ON pp.id = ppr.id_pago WHERE ppr.id_tipo = 1 AND pp.cobrar_pagar = 0)
                    $clientes2 
                    $where3 

                    UNION ALL

                    SELECT pr.id AS id_relacion, pr.id_documento, pa.id_prov_cli, (SELECT CONCAT(nombre,'*/*',dias_credito) FROM comun_cliente WHERE id = pa.id_prov_cli) AS info_cliente, 
                    rf.folio AS Folio, en.desc_concepto AS SacarConcepto, 
                    CASE rq.id_moneda 
                    WHEN 1 THEN SUM(pf.monto)
                    WHEN 2 THEN SUM(pf.monto*rq.tipo_cambio) END AS ImporteDoc, 
                    rf.fecha AS fecha_documento, 
                    pa.fecha_pago, pa.concepto, ROUND(pr.abono*pa.tipo_cambio,2) AS abono, pr.id_tipo
                    FROM app_pagos_relacion pr
                    INNER JOIN app_pagos pa ON pa.id = pr.id_pago
                    LEFT JOIN app_respuestaFacturacion rf ON rf.id = pr.id_documento
                    LEFT JOIN app_pendienteFactura pf ON pf.id_respFact = rf.id
                    LEFT JOIN app_envios en ON en.id = pf.id_sale
                    INNER JOIN app_oventa ve ON ve.id = en.id_oventa
                    INNER JOIN app_requisiciones_venta rq ON rq.id = ve.id_requisicion
                    WHERE pa.cobrar_pagar = 0
                    $clientes
                    AND pr.id_tipo = 1
                    $where1 
                    AND pf.id_respFact != 0
                    AND rf.idSale = 0
                    GROUP BY id_relacion
                    
                    UNION ALL

                     SELECT '' AS id_relacion, en.id AS id_documento , ve.id_cliente AS id_prov_cli, (SELECT CONCAT(nombre,'*/*',dias_credito) FROM comun_cliente WHERE id = ve.id_cliente) AS info_cliente, rf.folio AS Folio, en.desc_concepto AS SacarConcepto, 
                    CASE rq.id_moneda 
                    WHEN 1 THEN SUM(pf.monto)
                    WHEN 2 THEN SUM(pf.monto*rq.tipo_cambio) END AS ImporteDoc, 
                    rf.fecha AS fecha_documento,
                    '' AS fecha_pago, '' AS concepto, '' AS abono, 1 AS id_tipo
                    FROM app_respuestaFacturacion rf
                    LEFT JOIN app_pendienteFactura pf ON pf.id_respFact = rf.id
                    LEFT JOIN app_envios en ON en.id = pf.id_sale
                    INNER JOIN app_oventa ve ON ve.id = en.id_oventa
                    INNER JOIN app_requisiciones_venta rq ON rq.id = ve.id_requisicion
                    WHERE 
                    pf.id_respFact != 0
                    $clientes2 
                    AND rf.idSale = 0
                    AND rf.idFact != 0
                    AND rf.id NOT IN(SELECT ppr.id_documento FROM app_pagos_relacion ppr INNER JOIN app_pagos pp ON pp.id = ppr.id_pago WHERE ppr.id_tipo = 1 AND pp.cobrar_pagar = 0)
                    $where3 
                    GROUP BY pf.id_respFact

                    UNION ALL

                    SELECT pr.id AS id_relacion, pr.id_documento, pa.id_prov_cli, (SELECT CONCAT(nombre,'*/*',dias_credito) FROM comun_cliente WHERE id = pa.id_prov_cli) AS info_cliente, 
                    pa2.id AS Folio, pa2.concepto AS SacarConcepto, (pa2.cargo*pa2.tipo_cambio) AS ImporteDoc, pa2.fecha_pago AS fecha_documento,
                    pa.fecha_pago, pa.concepto, pr.abono, pr.id_tipo
                    FROM app_pagos_relacion pr
                    INNER JOIN app_pagos pa ON pa.id = pr.id_pago
                    LEFT JOIN app_pagos pa2 ON pa2.id = pr.id_documento
                    WHERE pa.cobrar_pagar = 0 
                    $clientes
                    AND pr.id_tipo = 0
                    $where2 

                    UNION ALL

                    SELECT '' AS id_relacion, pa.id AS id_documento, pa.id_prov_cli, (SELECT CONCAT(nombre,'*/*',dias_credito) FROM comun_cliente WHERE id = pa.id_prov_cli) AS info_cliente, 
                    pa.id AS Folio, pa.concepto AS SacarConcepto, (pa.cargo*pa.tipo_cambio) AS ImporteDoc, pa.fecha_pago AS fecha_documento,
                    '' AS fecha_pago, '' AS concepto, '' AS abono, 0 AS id_tipo
                    FROM app_pagos pa
                    WHERE pa.cobrar_pagar = 0 
                    $clientes 
                    AND pa.id NOT IN (SELECT id_documento FROM app_pagos_relacion WHERE id_tipo = 0)

                    UNION ALL

                     SELECT '' AS id_relacion, pa.id AS id_documento, pa.id_prov_cli, (SELECT nombre FROM comun_cliente WHERE id = pa.id_prov_cli) AS nombre_cliente,  CONCAT('Pago ',pa.id) AS Folio, pa.concepto AS SacarConcepto, IFNULL((SELECT SUM(re.abono*pa.tipo_cambio) FROM app_pagos_relacion re WHERE re.id_pago = pa.id),0)-(pa.abono*tipo_cambio) AS ImporteDoc, pa.fecha_pago AS fecha_documento, '' AS fecha_pago, '' AS concepto, '' AS abono, 2 AS id_tipo
                        FROM app_pagos pa
                        WHERE 
                        cobrar_pagar = 0
                        $clientes 
                        AND pa.cargo = 0 
                        $where4
                    
                    ORDER BY id_prov_cli, Folio, fecha_pago, id_relacion
                    ";
                    

        return $this->query($myQuery);
    }
}
?>
