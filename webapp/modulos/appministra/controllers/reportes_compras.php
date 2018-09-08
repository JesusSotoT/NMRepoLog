<?php
//Carga la funciones comunes top y footer
require('common.php');

//Carga el modelo para este controlador
require("models/reportes_compras.php");

class Reportes_Compras extends Common
{
	public $Reportes_ComprasModel;

	function __construct()
	{
		//Se crea el objeto que instancia al modelo que se va a utilizar

		$this->Reportes_ComprasModel = new Reportes_ComprasModel();
		$this->Reportes_ComprasModel->connect();
	}

	function __destruct()
	{
		//Se destruye el objeto que instancia al modelo que se va a utilizar
		$this->Reportes_ComprasModel->close();
	}

	function prov_prod()
	{
		$listaProveedores = $this->Reportes_ComprasModel->listaProveedores();
		$listaSucursales = $this->Reportes_ComprasModel->listaSucursales();
		$listaUsuarios = $this->Reportes_ComprasModel->listaUsuarios();
		$listaProductos = $this->Reportes_ComprasModel->listaProductos();
		$listaDepartamentos = $this->Reportes_ComprasModel->listaDepartamentos();
		$listaUnidadesBase = $this->Reportes_ComprasModel->listaUnidadesBase();
		$listaCaracteristicas = $this->Reportes_ComprasModel->listaCaracteristicas();
		require("views/compras/reporte_prov_prod.php");
	}

	function prov_prod_reporte()
	{
		$carac_padre = json_decode($this->Reportes_ComprasModel->carac_padre());
		$carac_hija = json_decode($this->Reportes_ComprasModel->carac_hija());

		if(intval($_POST['tipo_doc']) != 3)
			$resultado = $this->Reportes_ComprasModel->prov_prod_reporte($_POST);
		else
			$resultado = $this->Reportes_ComprasModel->prov_prod_reporte_req($_POST);

		$tabla = '';
		$ImporteProd = $ImporteProv = 0;
		$ImpuestosProd = $ImpuestosProv = 0;
		$TotalProd = $TotalProv = 0;
		$CantidadProd = $CantidadProv = 0;
		$UnidadProd = $UnidadProv = '';
		$UnitarioProd = $UnitarioProv = 0;
		$cont = 0;
		

		while($r = $resultado->fetch_assoc())
		{
			$caracteristicas = '';
			if($r['caracteristica'] != 0)
			{
				$carac = explode(',',$r['caracteristica']);
				for($j = 0; $j <= count($carac)-1; $j++)
				{
					$subcarac = explode('=>',$carac[$j]);
					$caracteristicas .= $carac_padre->{$subcarac[0]}.": ".$carac_hija->{$subcarac[1]}." / ";
				}
			}
			$impuestosTotal = 0;
			if($r['impuestos'])
			{
				$impuestos = explode(',',$r['impuestos']);
				for($i = 0; $i <= count($impuestos)-1; $i++)
				{
					$cant_impuesto = explode('-',$impuestos[$i]);
					$impuestosTotal += floatval($cant_impuesto[2]);
				}
			}
			$muestra = 1;
			if($r['id_proveedor'] != $provAnterior)
			{
				$muestra = 0;
				if($cont)
				{
					$UnitarioProv += $ImporteProd/$CantidadProd;
					$tabla .= "<tr style='font-weight:bold;background-color:white;'><td>Total Producto:</td><td></td><td></td><td>".$CantidadProd."</td><td>".$UnidadProd."</td><td>".number_format(($ImporteProd/$CantidadProd),2)."</td><td>".number_format($ImporteProd,2)."</td><td>".number_format($ImpuestosProd,2)."</td><td>".number_format($TotalProd,2)."</td></tr>";
				}

				$ImporteProd = 0;
				$ImpuestosProd = 0;
				$TotalProd = 0;
				$CantidadProd = 0;
				$UnidadProd = '';
				$UnitarioProd = 0;
				

				//$tabla .= "<tr class='linea_fac'><td>Producto: </td><td colspan='8'>".$r['Producto'].$carac_hija->{1}."</td></tr>";
				if($cont)
					$tabla .= "<tr style='font-weight:bold;background-color:white;'><td>Total Proveedor:</td><td></td><td></td><td>".$CantidadProv."</td><td>".$UnidadProv."</td><td>".number_format($UnitarioProv,2)."</td><td>".number_format($ImporteProv,2)."</td><td>".number_format($ImpuestosProv,2)."</td><td>".number_format($TotalProv,2)."</td></tr>";

				$ImporteProv = 0;
				$ImpuestosProv = 0;
				$TotalProv = 0;
				$CantidadProv = 0;
				$UnidadProv = '';
				$UnitarioProv = 0;

				$tabla .= "<tr class='linea_prov'><td width=250>Proveedor: </td><td colspan='8'>".$r['Proveedor']."</td></tr>";
			}

			if($r['id_producto'] != $prodAnterior)
			{
				if($cont && $muestra)
				{
					$UnitarioProv += $ImporteProd/$CantidadProd;
					$tabla .= "<tr style='font-weight:bold;background-color:white;'><td>Total Producto:</td><td></td><td></td><td>".$CantidadProd."</td><td>".$UnidadProd."</td><td>".number_format(($ImporteProd/$CantidadProd),2)."</td><td>".number_format($ImporteProd,2)."</td><td>".number_format($ImpuestosProd,2)."</td><td>".number_format($TotalProd,2)."</td></tr>";
				}

				$ImporteProd = 0;
				$ImpuestosProd = 0;
				$TotalProd = 0;
				$CantidadProd = 0;
				$UnidadProd = '';
				$UnitarioProd = 0;

				$tabla .= "<tr class='linea_fac'><td>Producto: </td><td colspan='8'>".$r['Producto'].$carac_hija->{1}."</td></tr>";
			}

			$UnidadBase = explode('*|*',$r['UnidadBase']);

			if(!intval($_POST['imp2']))
				$tabla .= "<tr class='detalle'><td>$caracteristicas</td><td>".$r['fecha']."</td><td>".$r['id_compra']."</td><td>".(floatval($r['cantidad'])/floatval($UnidadBase[1]))."</td><td>".$UnidadBase[0]."</td><td>".$r['costo']."</td><td>".number_format(floatval($r['Importe'])/floatval($UnidadBase[1]),2)."</td><td>".number_format($impuestosTotal,2)."</td><td>".number_format(((floatval($r['Importe']) + $impuestosTotal)/floatval($UnidadBase[1])),2)."</td></tr>";

			$provAnterior = $r['id_proveedor'];
			$prodAnterior = $r['id_producto'];
			$ImporteProv += (floatval($r['Importe'])/floatval($UnidadBase[1]));
			$ImpuestosProv += $impuestosTotal;
			$TotalProv += ((floatval($r['Importe']) + $impuestosTotal)/floatval($UnidadBase[1]));
			$CantidadProv += (floatval($r['cantidad'])/floatval($UnidadBase[1]));
			$UnidadProv = $UnidadBase[0];
			//$UnitarioProv += floatval($r['costo']);	
			$ImporteProd += (floatval($r['Importe'])/floatval($UnidadBase[1]));
			$ImpuestosProd += $impuestosTotal;
			$TotalProd += ((floatval($r['Importe']) + $impuestosTotal)/floatval($UnidadBase[1]));
			$CantidadProd += (floatval($r['cantidad'])/floatval($UnidadBase[1]));
			$UnidadProd = $UnidadBase[0];
			//$UnitarioProd += (floatval($r['Importe'])/floatval($UnidadBase[1])) / (floatval($r['cantidad'])/floatval($UnidadBase[1]));
			$cont++;

		}
		$UnitarioProv += $ImporteProd/$CantidadProd;
		$tabla .= "<tr style='font-weight:bold;background-color:white;'><td>Total Producto:</td><td></td><td></td><td>".$CantidadProd."</td><td>".$UnidadProd."</td><td>".number_format(($ImporteProd/$CantidadProd),2)."</td><td>".number_format($ImporteProd,2)."</td><td>".number_format($ImpuestosProd,2)."</td><td>".number_format($TotalProd,2)."</td></tr>";

		$tabla .= "<tr style='font-weight:bold;background-color:white;'><td>Total Proveedor:</td><td></td><td></td><td>".$CantidadProv."</td><td>".$UnidadProv."</td><td>".number_format($UnitarioProv,2)."</td><td>".number_format($ImporteProv,2)."</td><td>".number_format($ImpuestosProv,2)."</td><td>".number_format($TotalProv,2)."</td></tr>";

		echo $tabla;
		
	}

	function prov_prod_reportem()
	{
		$carac_padre = json_decode($this->Reportes_ComprasModel->carac_padre());
		$carac_hija = json_decode($this->Reportes_ComprasModel->carac_hija());

		if(intval($_POST['tipo_doc']) != 3)
			$resultado = $this->Reportes_ComprasModel->prov_prod_reporte($_POST);
		else
			$resultado = $this->Reportes_ComprasModel->prov_prod_reporte_req($_POST);

		$tabla = '';
		$ImporteProd = $ImporteProv = 0;
		$ImpuestosProd = $ImpuestosProv = 0;
		$TotalProd = $TotalProv = 0;
		$CantidadProd = $CantidadProv = 0;
		$UnidadProd = $UnidadProv = '';
		$UnitarioProd = $UnitarioProv = 0;
		$cont = 0;
		

		while($r = $resultado->fetch_assoc())
		{
			$caracteristicas = '';
			if($r['caracteristica'] != 0)
			{
				$carac = explode(',',$r['caracteristica']);
				for($j = 0; $j <= count($carac)-1; $j++)
				{
					$subcarac = explode('=>',$carac[$j]);
					$caracteristicas .= $carac_padre->{$subcarac[0]}.": ".$carac_hija->{$subcarac[1]}." / ";
				}
			}
			$impuestosTotal = 0;
			if($r['impuestos'])
			{
				$impuestos = explode(',',$r['impuestos']);
				for($i = 0; $i <= count($impuestos)-1; $i++)
				{
					$cant_impuesto = explode('-',$impuestos[$i]);
					$impuestosTotal += floatval($cant_impuesto[2]);
				}
			}
			$muestra = 1;
			if($r['id_proveedor'] != $provAnterior)
			{
				$muestra = 0;
				if($cont)
				{
					$UnitarioProv += $ImporteProd/$CantidadProd;
					$tabla .= "<tr style='font-weight:bold;background-color:white;'><td>Total Producto:</td><td></td><td></td><td>".$CantidadProd."</td><td>".$UnidadProd."</td><td>".number_format(($ImporteProd/$CantidadProd),2)."</td><td>".number_format($ImporteProd,2)."</td><td>".number_format($ImpuestosProd,2)."</td><td>".number_format($TotalProd,2)."</td></tr>";
				}

				$ImporteProd = 0;
				$ImpuestosProd = 0;
				$TotalProd = 0;
				$CantidadProd = 0;
				$UnidadProd = '';
				$UnitarioProd = 0;
				

				//$tabla .= "<tr class='linea_fac'><td>Producto: </td><td colspan='8'>".$r['Producto'].$carac_hija->{1}."</td></tr>";
				if($cont)
					$tabla .= "<tr style='font-weight:bold;background-color:white;'><td>Total Proveedor:</td><td></td><td></td><td>".$CantidadProv."</td><td>".$UnidadProv."</td><td>".number_format($UnitarioProv,2)."</td><td>".number_format($ImporteProv,2)."</td><td>".number_format($ImpuestosProv,2)."</td><td>".number_format($TotalProv,2)."</td></tr>";

				$ImporteProv = 0;
				$ImpuestosProv = 0;
				$TotalProv = 0;
				$CantidadProv = 0;
				$UnidadProv = '';
				$UnitarioProv = 0;

				$tabla .= "<tr class='linea_prov'><td width=250>Proveedor: </td><td colspan='8'>".$r['Proveedor']."</td></tr>";
			}

			if($r['id_producto'] != $prodAnterior)
			{
				if($cont && $muestra)
				{
					$UnitarioProv += $ImporteProd/$CantidadProd;
					$tabla .= "<tr style='font-weight:bold;background-color:white;'><td>Total Producto:</td><td></td><td></td><td>".$CantidadProd."</td><td>".$UnidadProd."</td><td>".number_format(($ImporteProd/$CantidadProd),2)."</td><td>".number_format($ImporteProd,2)."</td><td>".number_format($ImpuestosProd,2)."</td><td>".number_format($TotalProd,2)."</td></tr>";
				}

				$ImporteProd = 0;
				$ImpuestosProd = 0;
				$TotalProd = 0;
				$CantidadProd = 0;
				$UnidadProd = '';
				$UnitarioProd = 0;

				$tabla .= "<tr class='linea_fac'><td>Producto: </td><td colspan='8'>".$r['Producto']."</td></tr>";
			}

			$UnidadBase = explode('*|*',$r['UnidadBase']);

			if(!intval($_POST['imp2']))
				$tabla .= "<tr class='detalle'><td>$caracteristicas</td><td>".$r['fecha']."</td><td>".$r['id_compra']."</td><td>".(floatval($r['cantidad'])/floatval($UnidadBase[1]))."</td><td>".$UnidadBase[0]."</td><td>".$r['costo']."</td><td>".number_format(floatval($r['Importe'])/floatval($UnidadBase[1]),2)."</td><td>".number_format($impuestosTotal,2)."</td><td>".number_format(((floatval($r['Importe']) + $impuestosTotal)/floatval($UnidadBase[1])),2)."</td></tr>";

			$provAnterior = $r['id_proveedor'];
			$prodAnterior = $r['id_producto'];
			$ImporteProv += (floatval($r['Importe'])/floatval($UnidadBase[1]));
			$ImpuestosProv += $impuestosTotal;
			$TotalProv += ((floatval($r['Importe']) + $impuestosTotal)/floatval($UnidadBase[1]));
			$CantidadProv += (floatval($r['cantidad'])/floatval($UnidadBase[1]));
			$UnidadProv = $UnidadBase[0];
			//$UnitarioProv += floatval($r['costo']);	
			$ImporteProd += (floatval($r['Importe'])/floatval($UnidadBase[1]));
			$ImpuestosProd += $impuestosTotal;
			$TotalProd += ((floatval($r['Importe']) + $impuestosTotal)/floatval($UnidadBase[1]));
			$CantidadProd += (floatval($r['cantidad'])/floatval($UnidadBase[1]));
			$UnidadProd = $UnidadBase[0];
			//$UnitarioProd += (floatval($r['Importe'])/floatval($UnidadBase[1])) / (floatval($r['cantidad'])/floatval($UnidadBase[1]));
			$cont++;

		}
		$UnitarioProv += $ImporteProd/$CantidadProd;
		$tabla .= "<tr style='font-weight:bold;background-color:white;'><td>Total Producto:</td><td></td><td></td><td>".$CantidadProd."</td><td>".$UnidadProd."</td><td>".number_format(($ImporteProd/$CantidadProd),2)."</td><td>".number_format($ImporteProd,2)."</td><td>".number_format($ImpuestosProd,2)."</td><td>".number_format($TotalProd,2)."</td></tr>";

		$tabla .= "<tr style='font-weight:bold;background-color:white;'><td>Total Proveedor:</td><td></td><td></td><td>".$CantidadProv."</td><td>".$UnidadProv."</td><td>".number_format($UnitarioProv,2)."</td><td>".number_format($ImporteProv,2)."</td><td>".number_format($ImpuestosProv,2)."</td><td>".number_format($TotalProv,2)."</td></tr>";

		echo $tabla;
		
	}

	function grafi_compras()
    {
        require('views/compras/v_grafi_compras.php'); 
    }

    function a_reporte()
    {
        $desde=$_POST['desde'];
        $hasta=$_POST['hasta'];
        $ordenar=$_POST['ordenar'];
        $radio=$_POST['radio'];
        $resultReq = $this->Reportes_ComprasModel->reforteGrafico($desde,$hasta,$ordenar,$radio);

        if($resultReq->num_rows>0){
        while ($r = $resultReq->fetch_assoc()) {
            $productos[]=$r;
            $dproductos[]=(object)array('label'=>$r->nombre, 'value'=>$r->total);
        }
        $JSON = array('success' =>1, 'datos'=>$productos);
        }else{
        $JSON = array('success' =>0);
        }
          
        echo json_encode($JSON);

    }

    function listaAlmacenes()
    {
    	$lista = $this->Reportes_ComprasModel->listaAlmacenes($_POST['idSuc']);
    	$select = "<option value='0'>Todos</option>";
    	while($l = $lista->fetch_object())
    	{
    		$select .= "<option value='$l->id'>($l->codigo_manual) $l->nombre</option>";
    	}
    	echo $select;
    }

    function listaFamilias()
    {
    	$lista = $this->Reportes_ComprasModel->listaFamilias($_POST['id_departamento']);
    	$select = "<option value='0'>Todos</option>";
    	while($l = $lista->fetch_object())
    	{
    		$select .= "<option value='$l->id'>$l->nombre</option>";
    	}
    	echo $select;
    }

    function listaCaracteristicasHija()
    {
    	$lista = $this->Reportes_ComprasModel->listaCaracteristicasHija($_POST['id_padre']);
    	$select = "<option value='0'>Todos</option>";
    	while($l = $lista->fetch_object())
    	{
    		$select .= "<option value='$l->id'>$l->nombre</option>";
    	}
    	echo $select;
    }

    function listaLineas()
    {
    	$lista = $this->Reportes_ComprasModel->listaLineas($_POST['id_familia']);
    	$select = "<option value='0'>Todos</option>";
    	while($l = $lista->fetch_object())
    	{
    		$select .= "<option value='$l->id'>$l->nombre</option>";
    	}
    	echo $select;
    }

    function listaMedida()
    {
    	$lista = $this->Reportes_ComprasModel->listaMedida($_POST['id_base']);
    	$select = "";
    	while($l = $lista->fetch_object())
    	{
    		$select .= "<option value='$l->id'>($l->clave) $l->nombre</option>";
    	}
    	echo $select;
    }


}
/*SELECT (SELECT razon_social FROM mrp_proveedor WHERE idPrv = c.id_proveedor) AS Proveedor, 
p.nombre,
c.fecha_entrega,
c.id,
cd.cantidad,
(SELECT clave FROM app_unidades_medida WHERE id = p.id_unidad_compra) AS Unidad,
cd.costo,
c.subtotal,
(c.total - c.subtotal) AS Impuestos,
c.total

FROM app_ocompra_datos cd
INNER JOIN app_ocompra c ON c.id = cd.id_ocompra
INNER JOIN app_productos p ON p.id = cd.id_producto
WHERE cd.activo = 1
AND c.fecha_entrega BETWEEN '2014-01-01' AND '2014-01-31'
AND cd.almacen = 1
AND c.id_usrcompra = 1
AND c.id_proveedor = 1
AND cd.id_producto = 8*/
?>
