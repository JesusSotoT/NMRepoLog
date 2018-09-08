<?php
//ini_set('display_errors', 1);
//Carga la funciones comunes top y footer
require('common.php');

//Carga el modelo para este controlador
require("models/reporte.php");

class Reporte extends Common
{
    public $ReporteModel;

    function __construct()
    {
        //Se crea el objeto que instancia al modelo que se va a utilizar

        $this->ReporteModel = new ReporteModel();
        $this->ReporteModel->connect();
    }

    function __destruct()
    {
        //Se destruye el objeto que instancia al modelo que se va a utilizar
        $this->ReporteModel->close();
    }
    function pintaRegistros() {

        //echo json_encode($this->ReporteModel->pintaRegistros());
      //print_r($_SESSION['caja']);
    }
    function indexReportes(){
        $filtros = $this->ReporteModel->filtros();

        $resp = $this->ReporteModel->repProductos($desde,$hasta,$sucursal,$orden);

        require('views/reporte/reporte.php');

    }
    function repProductos(){
        $desde = $_POST['desde'];
        $hasta = $_POST['hasta'];
        $sucursal = $_POST['sucursal'];
        $orden = $_POST['orden'];
        //echo 'eokeodke';
        $resp = $this->ReporteModel->repProductos($desde,$hasta,$sucursal,$orden);

        echo json_encode($resp);


    }
    function repFormaDePago(){
        $desde = $_POST['desde'];
        $hasta = $_POST['hasta'];
        $sucursal = $_POST['sucursal'];
        $orden = $_POST['orden'];
        //echo 'eokeodke';
        $resp = $this->ReporteModel->repFormaDePago($desde,$hasta,$sucursal,$orden);

        echo json_encode($resp);
    }
    function repEmpleadoVenta(){
        $desde = $_POST['desde'];
        $hasta = $_POST['hasta'];
        $sucursal = $_POST['sucursal'];
        $orden = $_POST['orden'];
        //echo 'eokeodke';
        $resp = $this->ReporteModel->repEmpleadoVenta($desde,$hasta,$sucursal,$orden);
         echo json_encode($resp);
    }
    function repDepartamento(){
    	$desde = $_POST['desde'];
        $hasta = $_POST['hasta'];
        $sucursal = $_POST['sucursal'];
        $orden = $_POST['orden'];

        $resp = $this->ReporteModel->repDepartamento($desde,$hasta,$sucursal,$orden);
         echo json_encode($resp);
    }
    function repFamilia(){
        $desde = $_POST['desde'];
        $hasta = $_POST['hasta'];
        $sucursal = $_POST['sucursal'];
        $orden = $_POST['orden'];

        $resp = $this->ReporteModel->repFamilia($desde,$hasta,$sucursal,$orden);
         echo json_encode($resp);
    }
    function repLinea(){
        $desde = $_POST['desde'];
        $hasta = $_POST['hasta'];
        $sucursal = $_POST['sucursal'];
        $orden = $_POST['orden'];

        $resp = $this->ReporteModel->repLinea($desde,$hasta,$sucursal,$orden);
         echo json_encode($resp);
    }
///////////////// ******** ---- 			listar_ventas_cliente_producto			------ ************ //////////////////
//////// Lista las ventas del cliente por los productos
	// Como parametros puede recibir:
		// f_ini -> Fecha de inicio
		// f_fin -> Fecha final
		// sucursal -> ID de las sucursal
		// graficar -> 1 -> dia, 2 -> semana, 3 -> mes, 4 -> año

	function listar_ventas_cliente_producto($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

        $result = $this -> ReporteModel -> listar_ventas_cliente_producto($objeto);
		$resp['result'] = $result['rows'];

	// ** Consulta las comandas y las regresa en un array para la grafica lineal
	// Valida por que debe agrupar la consulta
		switch ($objeto['grafica']) {
		// Dia
			case 1 :
				$objeto['agrupar'] = "DATE_FORMAT(v.fecha, ' %Y %m %d')";
				break;
		// Semana
			case 2 :
				$objeto['agrupar'] = "DATE_FORMAT(v.fecha, ' %Y %m %v')";
				break;
		// Mes
			case 3 :
				$objeto['agrupar'] = "DATE_FORMAT(v.fecha, ' %Y %m')";
				break;
		// Año
			case 4 :
				$objeto['agrupar'] = "DATE_FORMAT(v.fecha, ' %Y')";
				break;
		}

       	$result_2 = $this -> ReporteModel -> listar_ventas_cliente_producto($objeto);
		$result_2 = $result_2['rows'];

	// Arma el array para la grafica lineal
		foreach ($result_2 as $key => $value) {
			$resp['lineal'][$key]['ventas'] = $value['monto'];
			$resp['lineal'][$key]['fecha'] = $value['fecha'];
		}

	// ** Arma el array para la grafica de dona
		foreach ($resp['result'] as $key => $value) {
			$datos[$value['nombre']]['value'] += $value['monto'];
			$datos[$value['nombre']]['label'] = $value['nombre'];
		}
		$dona = array();
		foreach ($datos as $key => $value) {
			array_push($dona, $value);
		}
		$resp['dona'] = $dona;

		$resp['status'] = (!empty($resp['result'])) ? 1 : 2 ;

	// Regresa al ajax el mensaje
		echo json_encode($resp);
	}

///////////////// ******** ---- 			FIN listar_ventas_cliente_producto		------ ************ //////////////////

}


?>
