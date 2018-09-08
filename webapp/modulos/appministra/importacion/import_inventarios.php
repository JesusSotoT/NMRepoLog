Cargando Registros...

<?php
//	ini_set("display_errors",0);
//	ini_set('memory_limit', '-1');
	//error_reporting(E_WARNING);

	require_once '../../libraries/Excel/reader.php';

	$data = new Spreadsheet_Excel_Reader();
	$data->setOutputEncoding('CP1251');
	$data->read(dirname(__FILE__).'/inventarios_temp.xls');

	$dato = array();
	$sigue = 1;

	//Pedimentos
	for ($i = 2; $i <= $data->sheets[1]['numRows']; $i++) {
		$dato[1] = trim($data->sheets[1]["cells"][$i][1]); //# pedimento
		$dato[2] = trim($data->sheets[1]["cells"][$i][2]); //aduana
		$dato[3] = trim($data->sheets[1]["cells"][$i][3]); //# aduana
		$dato[4] = trim($data->sheets[1]["cells"][$i][4]); //tipo cambio
		$dato[5] = trim($data->sheets[1]["cells"][$i][5]); //fecha

		if ($dato[1] != '' && $dato[2] != '' && $dato[3] != '' && $dato[4] != '' && $dato[5] != '')
			$this->InventariosModel->guardarLayPed($dato);
		else {
			//        $this->InventariosModel->borrar();
			echo "<br /><b style='color:red;'>Existen registros con campos obligatorios vacios en los pedimentos y no se guardaron los registros, revise su layout.</b>";
			echo "<br /><br /><a style='background-color:gray; color:black;text-decoration:none;width:200px;height:50px;border:1px solid black;' href='index.php?c=inventarios&f=entradas'>Regresar</a>";
			$sigue = 0;
			break;
		}
	}

	//Lotes
	if($sigue) {
		for ($i = 2; $i <= $data->sheets[2]['numRows']; $i++) {
			$dato[1] = trim($data->sheets[2]["cells"][$i][1]); //# lote
			$dato[2] = trim($data->sheets[2]["cells"][$i][2]); //fecha lote
			$dato[3] = trim($data->sheets[2]["cells"][$i][3]); //fecha caducidad

			if($dato[1] != '' && $dato[2] != '' && $dato[3] != '')
				$this->InventariosModel->guardarLayLot($dato);
			else {
				$this->InventariosModel->borrar();
				echo "<br /><b style='color:red;'>Existen registros con campos obligatorios vacios en los lotes y no se guardaron los registros, revise su layout.</b>";
				echo "<br /><br /><a style='background-color:gray; color:black;text-decoration:none;width:200px;height:50px;border:1px solid black;' href='index.php?c=inventarios&f=entradas'>Regresar</a>";
				$sigue = 0;
				break;
			}
		}
	}

	//Movimientos
	if($sigue) {
		for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++) {
			$dato[1] = trim($data->sheets[0]["cells"][$i][1]); //id producto
			$dato[2] = trim($data->sheets[0]["cells"][$i][2]); //ids caracteristicas
			$dato[3] = trim($data->sheets[0]["cells"][$i][3]); //# pedimento
			$dato[4] = trim($data->sheets[0]["cells"][$i][4]); //# lote
			$dato[5] = trim($data->sheets[0]["cells"][$i][5]); //cantidad
			$dato[6] = trim($data->sheets[0]["cells"][$i][6]); //importe
			$dato[7] = trim($data->sheets[0]["cells"][$i][7]); //id almacen
			$dato[8] = trim($data->sheets[0]["cells"][$i][8]); //costo
			$dato[9] = trim($data->sheets[0]["cells"][$i][9]); //referencia
			$dato[10] = trim($data->sheets[0]["cells"][$i][10]); //series

			if($dato[2] == '') $dato[2] = '0';
			if($dato[3] == '') $dato[3] = '0';
			if($dato[4] == '') $dato[4] = '0';

			if($dato[1] != '' && $dato[5] != '' && $dato[6] != '' && $dato[7] != '' && $dato[8] != '')
				$this->InventariosModel->guardarLayMovs($dato);
			else {
				$this->InventariosModel->borrar();
				echo "<br /><b style='color:red;'>Existen registros con campos obligatorios vacios en los movimientos y no se guardaron los registros, revise su layout.</b>";
				echo "<br /><br /><a style='background-color:gray; color:black;text-decoration:none;width:200px;height:50px;border:1px solid black;' href='index.php?c=inventarios&f=entradas'>Regresar</a>";
				break;
			}
		}
	}

	unlink(dirname(__FILE__).'/inventarios_temp.xls');
	echo "<script type='text/javascript'>window.location = 'index.php?c=inventarios&f=entradas'</script>";
?>
