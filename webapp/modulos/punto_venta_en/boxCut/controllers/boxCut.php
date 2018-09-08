<?php 
	/*==============================================================================
	=            controllers/boxCut.php - Miguel Angel Velazco Martinez            =
	==============================================================================*/
	
	
	
	/*-----  End of controllers/boxCut.php - Miguel Angel Velazco Martinez  ------*/

	require '../models/boxCut.php';


	$method = ( isset($_POST['method']) ) ? $_POST['method'] : $_GET['method'];
	$boxCut = new BoxCut();
	switch ( $method )
	{
		case 'getBoxCuts':
			$boxCut = new BoxCut();
			$arg1 = $_GET['page'];
			$arg2 = $_GET['init'];
			$arg3 = $_GET['end'];
			$arg4 = $_GET['user'];
			echo $boxCut->$method( $arg1, $arg2, $arg3, $arg4 );
			break;
		case 'getSales':
			$data = $boxCut->$method();
			echo "data =" . $data . ";";
			break;
		case 'newCut':
			$data = $boxCut->$method();
		case 'sales':
			$date = @$_POST['date'];
			if( isset( $date ) )
			{
				$data = $boxCut->$method( $_POST['date'] );
				echo $data;
			}
			else
			{
				echo 1000000;
			}
			
			break;
			
		case 'listar_usuarios':
			$usuarios = $boxCut->$method($_REQUEST['id'], $_REQUEST['usuario']);
			
			echo $usuarios;
			
		break;
		case 'getRetiros':
			$desde = $_POST['desde'];
			$hasta = $_POST['hasta'];
			$idretiro = $_POST['idcorte'];
		
			$data = $boxCut->$method($desde,$hasta,$idretiro);
			echo $data;
			break; 
	}



?>