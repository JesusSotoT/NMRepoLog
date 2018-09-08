<?php

//Carga la funciones comunes top y footer
require('common.php');

//Carga el modelo para este controlador
require("models/implementa.php");

class Implementa extends Common
{
	public $ImplementaModel;
	

	function __construct()
	{
		//Se crea el objeto que instancia al modelo que se va a utilizar
		$this->ImplementaModel = new ImplementaModel();
		$this->ImplementaModel->connect();
		session_start();	
		//$_SESSION['misproductos'] = '1001|1002|';
		//$_SESSION['misproductos'] = '1002|';
		//$_SESSION['misproductos'] = '1001|';
		$this->misproductos = $_SESSION['misproductos'];		
	}

	function __destruct()
	{
		//Se destruye el objeto que instancia al modelo que se va a utilizar
		$this->ImplementaModel->close();
	}
	function process($misproductos){

		$productos = explode('|', $misproductos);		
		$sol = '';
		foreach ($productos as $k => $v) {
			if($v == 1001){$sol .= ',1001'; $sis .=', Appministra - POS Emprendedor';} 
			if($v == 1002){$sol .= ',1001,1002'; $sis .=', Foodware - Emprendedor';} /// hereda los pasos de 1001
			if($v == 1004){$sol .= ',1004'; $sis .=', Acontia - Emprendedor';} 
			if($v == 1011){$sol .= ',1011'; $sis .=', Xtructur - Negocio';}
			if($v == 1014){$sol .= ',1014'; $sis .=', Appministra - POS Negocio';} 
			if($v == 1015){$sol .= ',1015'; $sis .=', Appministra - POS Empresar';} 
			if($v == 1016){$sol .= ',1016'; $sis .=', Appministra - Comercial';} 
			if($v == 1019){$sol .= ',1019'; $sis .=', Foodware - Negocio';} 
			if($v == 1020){$sol .= ',1020'; $sis .=', Foodware - Empresarial';} 
			if($v == 1021){$sol .= ',1021'; $sis .=', Acontia - Negocio';} 
			if($v == 1022){$sol .= ',1022'; $sis .=', Acontia - Empresarial';} 
			if($v == 1023){$sol .= ',1023'; $sis .=', Xtructur - Negocio Plus';} 
			if($v == 2024){$sol .= ',2024'; $sis .=', Xtructur - Empresarial';} 
			if($v == 2025){$sol .= ',2025'; $sis .=', Xtructur - Corporativo';}
		}
		$sol=substr($sol,1);
		$sis=substr($sis,1);
		return array('sol' => $sol, 'sis' => $sis);
	}	
	
	function saveIncio(){
		$misproductos = $this->misproductos;

		$arr = $this->process($misproductos);
		$sol = $arr['sol'];
		
		$inicio = $this->ImplementaModel->saveIncio($sol);
		echo $inicio;
	}

	function saveFin(){
		$misproductos = $this->misproductos;

		$arr = $this->process($misproductos);
		$sol = $arr['sol'];

		$fin = $this->ImplementaModel->saveFin($sol);
		echo $fin;
	}
	

	function implementa()
	{				

		$misproductos = $this->misproductos;

		$arr = $this->process($misproductos);
		$sol = $arr['sol'];
		$sis = $arr['sis'];

		$configuracion = $this->ImplementaModel->configuracion($sol);
		$app = $configuracion['app'];

		$app[0]['solucion'] = $sis;
		

		/// para obtener el progreso

		foreach($configuracion['act'] as $val){ // ordenamiento
                $auxPaso[] = $val['paso'];
                $auxAct[] = $val['id_actividad'];
            }

        array_multisort($auxPaso, SORT_ASC, $auxAct, SORT_ASC, $configuracion['act']);
		$cont = $actC = $progres = 0;
		$aux = 1;

		foreach ($configuracion['act'] as $key => $v) {
				$paso = $v['paso'];

				if($paso != $pasoAnt || $aux == 1){
					$cont = 0;
					$actC = 0;
				}
				$aux = 0;
				$cont++;
				if($v['estatus'] == 2){
						$actC++;
					}
				// progres
				$progres = ($actC / $cont) * 100;
				// progres fin
				$apps2[] = array(
                                    id_actividad    => $v['id_actividad'],
                                    nombre        	=> $v['nombre'],
                                    menu           	=> $v['menu'],
                                    desc_larga      => $v['desc_larga'],
                                    link           	=> $v['link'],
                                    opcional        => $v['opcional'],
                                    estatus         => $v['estatus'],
                                    id_paso         => $v['id_paso'],
                                    paso      	    => $v['paso'],
                                    cont 			=>$cont,
                                    actC 			=>$actC,
                                    progres         =>$progres
                        );
				$pasoAnt = $v['paso'];
		}

		//echo json_encode($apps2);

	/*
		foreach($configuracion['act'] as $val){ // ordenamiento
                $auxPaso[] = $val['id_paso'];
        }            

        array_multisort($auxPaso, SORT_ASC, $configuracion['act']);
		$cont = $actC = $progres = 0;
		$aux = 1;

			foreach ($configuracion['act'] as $key => $v) {
				$paso = $v['id_paso'];

				if($paso != $pasoAnt || $aux == 1){
					$cont = 0;
					$actC = 0;
				}
				$aux = 0;
				$cont++;
				if($v['estatus'] == 2){
						$actC++;
					}
				// progres
				$progres = ($actC / $cont) * 100;
				// progres fin
				$apps2[] = array(
                                    id_actividad    => $v['id_actividad'],
                                    nombre        	=> $v['nombre'],
                                    menu           	=> $v['menu'],
                                    desc_larga      => $v['desc_larga'],
                                    link           	=> $v['link'],
                                    opcional        => $v['opcional'],
                                    estatus         => $v['estatus'],
                                    id_paso         => $v['id_paso'],
                                    cont 			=>$cont,
                                    actC 			=>$actC,
                                    progres         =>$progres
                        );
				$pasoAnt = $v['id_paso'];
			}
	*/

			$apps2R = array_reverse($apps2);

			foreach ($apps2R as $key => $va) {
				$id_paso = $va['id_paso'];

				if($id_paso != $id_pasoAnt){
					$apps3[] = array(
                                    id_paso    => $va['id_paso'],
                                    progres    => $va['progres'],
                        );
				}
				$id_pasoAnt = $va['id_paso'];
			}
		/// para obtener el progreso fin
			$progres = 0;
		foreach($configuracion['pasos'] as $val){
			$aux = 1;
			
			foreach ($configuracion['act'] as $kk => $vv) { // es para que no guarde los pasos sin sub pasos
				if($vv['id_paso'] == $val['id_paso']){					
					$aux = 1;
					break;
				}else{
					$aux = 0;
				}
			}

			if($aux == 1){
				$id_paso = $val['id_paso'];
				foreach($apps3 as $valor){
					$id_paso2 = $valor['id_paso'];
					if($id_paso2 == $id_paso){
						$progres = $valor['progres'];
						break;
					}
				}
				
				$pasos[] = array(
	                                    solucion    => $val['solucion'],
	                                    id_paso    	=> $val['id_paso'],
	                                    paso    	=> $val['paso'],
	                                    nombre    	=> $val['nombre'],
	                                    link    	=> $val['link'],
	                                    desc_larga 	=> $val['desc_larga'],
	                                    progres     => $progres
	                        );
				$progres = 0;
			}
		}
		//echo json_encode($app);
		$fechaI = $configuracion['app'][0]['fechaInicio'];
		if($fechaI == ''){
			$fechaI = date("Y").'-'.date("m").'-'.date("d");
		}
		$fecha=explode("-",$fechaI);
		$fechaI1=mktime(0,0,0,$fecha[1],$fecha[2],$fecha[0]);

		
		$fechaA = date("Y").'-'.date("m").'-'.date("d");
		//$fechaA = '2017-07-01';
		$fecha2=explode("-",$fechaA);
		$fechaI2=mktime(0,0,0,$fecha2[1],$fecha2[2],$fecha2[0]);

		$diferencia=$fechaI2-$fechaI1;
		$dias=$diferencia/(60*60*24);


		$diasT = $dias;
		require('views/implementa/implementa.php');
	}
	function menu(){
		$menuP = $_POST['menu'];
		$this->ImplementaModel->updateProgress($menuP);
		$menu = $this->ImplementaModel->menu($menuP);		
		echo json_encode($menu);
	}
	function fechas(){

		$misproductos = $this->misproductos;
		$arr = $this->process($misproductos);
		$sol = $arr['sol'];

		$fechas = $this->ImplementaModel->fechas($sol);

		//ACTUAL
		$fechaA = date("Y").'-'.date("m").'-'.date("d");
		//INICIO
		$fechaI = $fechas[0]['fechaInicio'];
		// FIN
		$fechaF = $fechas[0]['fechaFinal'];

		/// dias trans
		if($fechaF == 0 || $fechaF == ''){

			$fechaA1=explode("-",$fechaA);
			$fechaA2=mktime(0,0,0,$fechaA1[1],$fechaA1[2],$fechaA1[0]);

			$fechaI1=explode("-",$fechaI);
			$fechaI2=mktime(0,0,0,$fechaI1[1],$fechaI1[2],$fechaI1[0]);

			$diferencia=$fechaA2-$fechaI2;
			$dias=$diferencia/(60*60*24);

		}else{
			$fechaF1=explode("-",$fechaF);
			$fechaF2=mktime(0,0,0,$fechaF1[1],$fechaF1[2],$fechaF1[0]);

			$fechaI1=explode("-",$fechaI);
			$fechaI2=mktime(0,0,0,$fechaI1[1],$fechaI1[2],$fechaI1[0]);

			$diferencia=$fechaF2-$fechaI2;
			$dias=$diferencia/(60*60*24);
		}

		$arrayF[]=array(
                    fechaI      => $fechaI,
                    fechaF      => $fechaF,
                    fechaA     	=> $fechaA,
                    diasT     	=> $dias,
            );



		echo json_encode($arrayF);
	}
}


?>