<?php
//Carga la funciones comunes top y footer
require('common.php');

//Carga el modelo para este controlador
require("models/inventarios.php");

class Inventarios extends Common {
	public $InventariosModel;

	function __construct() {
		//Se crea el objeto que instancia al modelo que se va a utilizar

		$this->InventariosModel = new InventariosModel();
		$this->InventariosModel->connect();
	}

	function __destruct()
	{
		//Se destruye el objeto que instancia al modelo que se va a utilizar
		$this->InventariosModel->close();
	}

		//INICIAN FUNCIONES DE ENTRADAS

	function entradas()
	{
		$listaProductos = $this->InventariosModel->listaProductos();
		$listaAlmacenes = $this->InventariosModel->listaAlmacenes(0,0);
		$salidasSinExistencia = $this->InventariosModel->salidasSinExistencia();
		$configuracionPeriodos = $this->InventariosModel->configPeriodos();
		$primer_ejercicio = $this->InventariosModel->ejerciciosDisponibles('ASC',$configuracionPeriodos['permitir_cerrados']);
		$ultimo_ejercicio = $this->InventariosModel->ejerciciosDisponibles('DESC',$configuracionPeriodos['permitir_cerrados']);
        $this->InventariosModel->generarAlmacenTransito('999');

		require("views/inventarios/entradas.php");
	}

    function sol_traspasos()
    {
        $listaProductos = $this->InventariosModel->listaProductos();
        $listaAlmacenes = $this->InventariosModel->listaAlmacenes(0,0);
        $salidasSinExistencia = $this->InventariosModel->salidasSinExistencia();
        $configuracionPeriodos = $this->InventariosModel->configPeriodos();
        $primer_ejercicio = $this->InventariosModel->ejerciciosDisponibles('ASC',$configuracionPeriodos['permitir_cerrados']);
        $ultimo_ejercicio = $this->InventariosModel->ejerciciosDisponibles('DESC',$configuracionPeriodos['permitir_cerrados']);
        $this->InventariosModel->generarAlmacenTransito('999');
        $idAlmacenTransito = $this->InventariosModel->idAlmacenTransito('999');

        require("views/inventarios/sol_traspasos.php");
    }

    function recepciones()
    {
        $idAlmacenTransito = $this->InventariosModel->idAlmacenTransito('999');
        require("views/inventarios/traslados.php");
    }

	function listaMovimientos()
	{
		$listaEntradas = $this->InventariosModel->listaMovimientos();
		$datos=array(); 
        $carac_hija = json_decode($this->InventariosModel->carac_hija());
		while($l = $listaEntradas->fetch_object())
		{
			
    		switch ($l->tipo_traspaso) {
    			case '0':
    				$tipo_traspaso = "Salida";
    				break;
    			case '1':
    				$tipo_traspaso = "Entrada";
    				break;
    			case '2':
    				$tipo_traspaso = "Traspaso";
    				break;	
                case '3':
                    $tipo_traspaso = "Apartado";//Tipo apartados
                    break;                      
    		}
            /*
            $rel = $this->InventariosModel->extraeApartados($idprod)
            $rel = $rel->fetch_assoc();
            $rel = $rel['apart'];
            */
    		$accion = "";

    		$producto = explode("*/*",$l->producto);
    		if(!intval($l->origen) && intval($l->estatus) && !intval($producto[1]))
            {
                if($tipo_traspaso != "Traspaso")
    			$accion = "<button style='width:60px;' class='btn btn-danger btn-xs' id='butt_".$l->id."' onclick='cancelar_accion(".$l->id.")'>Cancelar</button>";
            }
                
            if(!intval($l->estatus))
            {
                $accion = "<span class='label label-danger'>Cancelado</span>";
            }
            else
            {
                $accion .= "<a style='width:60px;margin-top:5px;' class='btn btn-info btn-xs' href='index.php?c=inventarios&f=printer&idMov=".$l->id."' target='_blank' role='button'>Imprimir</a>";
            }    
            
		
            if(intval($l->tipo_traspaso) == 1 && intval($l->origen) == 1)
            {
                $ref = explode('/',$l->referencia);
                $ref = explode('-',$ref[1]);
                if($ref[1])
                    $l->referencia .= " / <a href='index.php?c=compras&f=recepcion&id_rec=".$ref[1]."&v=1' target='_blank'>RE:".$ref[1]."</a>";
            }

            $txt_caracs = '';
            if($l->id_producto_caracteristica != "" && $l->id_producto_caracteristica != "'0'")
            {

                $carac = explode(',',$l->id_producto_caracteristica);
                for($j = 0; $j <= count($carac)-1; $j++)
                {
                    if($j!=0)
                        $txt_caracs .= ", ";
                    $subcarac = explode('=>',$carac[$j]);
                    $subcarac[1] = str_replace("'", "", $subcarac[1]);
                    $subcarac[1] = str_replace("'", "", $subcarac[1]);
                    $txt_caracs .= $carac_hija->{$subcarac[1]};
                }
            }

            $referencia = $l->referencia;
            if($l->almacen_destino == "(tra-1) Transito")
            {
                $referencia = explode("Destino:",$l->referencia);
                $referencia = $referencia[0]." Destino: ".$l->destino_final;
            }

			/*array_push($datos,array(
				'id' => intval($l->id),
				'producto' => utf8_encode($producto[0])." ".utf8_encode($txt_caracs),
				'cantidad' => utf8_encode($l->cantidad),
				'importe' => "<span title='Costo Unitario: ".utf8_encode($l->costo)."'>".utf8_encode($l->importe)."</span>",
				'almacen_origen' => utf8_encode($l->almacen_origen),
				'almacen_destino' => utf8_encode($l->almacen_destino),
				'fecha' => "<label id='lab_".$l->id."' style='font-size:10px;'>".date("d-m-Y H:i:s",strtotime($l->fecha))."</label>",
				'empleado' => utf8_encode($l->empleado),
				'tipo_traspaso' => utf8_encode($tipo_traspaso),
				'referencia' => utf8_encode($referencia),
				'accion' => utf8_encode($accion)
				)); */
            array_push($datos,array(
                'id' => intval($l->id),
                'producto' => $producto[0]." ".utf8_encode($txt_caracs),
                'cantidad' => utf8_encode($l->cantidad),
                'importe' => "<span title='Costo Unitario: ".utf8_encode($l->costo)."'>".utf8_encode($l->importe)."</span>",
                'almacen_origen' => $l->almacen_origen,
                'almacen_destino' => $l->almacen_destino,
                'fecha' => "<label id='lab_".$l->id."' style='font-size:10px;'>".date("d-m-Y H:i:s",strtotime($l->fecha))."</label>",
                'empleado' => $l->empleado,
                'tipo_traspaso' => $tipo_traspaso,
                'referencia' => $referencia,
                'accion' => $accion
                ));
		}
		echo json_encode($datos);
	}

    function listaTraspasos()
    {
        $listaTras = $this->InventariosModel->listaTraspasos($_GET['t']);
        $datos=array(); 
        while($l = $listaTras->fetch_object())
        {
            if(!floatval($l->cerrado) && !intval($_GET['t']))
            {

                $accion = "<button style='width:60px;' class='btn btn-primary btn-xs' id='butt_rec_".$l->id."' onclick='modificar_traspaso(".$l->id.")' data-toggle='modal' data-target='.bs-traslado-modal-lg'>Modificar</button>";
                $accion .= "<button style='width:60px;margin-left:10px;' class='btn btn-danger btn-xs' id='butt_".$l->id."' onclick='cancelar_traspaso($l->id)'>Cancelar</button>";
            }
            elseif(floatval($l->cerrado) == 1 && !intval($_GET['t']))
                $accion = "<i style='font-size:12px;'>Este traslado ha sido recibido.</i>";
            else
                $accion = "<i style='font-size:12px;'>Este traslado ha sido <b style='color:red;'>Cancelado</b>.</i>";

            if(intval($_GET['t']))
            {
                $accion = "<button style='width:60px;' class='btn btn-primary btn-xs' id='butt_rec_".$l->id."' onclick='recibir_traspaso(".$l->id.")' data-backdrop='static' data-keyboard='false' data-toggle='modal' data-target='.bs-recepcion-modal-lg'>Recibir</button>";
            }

            array_push($datos,array(
                'clave' => intval($l->clave),
                'origen' => utf8_encode($l->almacen_origen),
                'destino' => utf8_encode($l->almacen_destino),
                'solicitante' => utf8_encode($l->empleado),
                'fecha' => utf8_encode($l->fecha),
                'referencia' => utf8_encode($l->referencia),
                'accion' => utf8_encode($accion)
                ));
        }
        echo json_encode($datos);
    }

    function info_traslado_movimientos()
    {
        $listaEntradas = $this->InventariosModel->info_traslado_movimientos($_POST['id_tras']);
        
        $carac_hija = json_decode($this->InventariosModel->carac_hija());

        $tabla = "<tr style='background-color:gray;'><td width='150'>Id Movimiento</td><td width='150'>Producto</td><td width='150'>Cantidad</td><td width='150'>Importe</td><td width='150'>Recibidas</td><td width='150'>Faltantes</td></tr>";

        while($l = $listaEntradas->fetch_object())
        {
              
            $txt_caracs = "";
            if($l->id_producto_caracteristica != "" && $l->id_producto_caracteristica != "'0'")
            {

                $carac = explode(',',$l->id_producto_caracteristica);
                for($j = 0; $j <= count($carac)-1; $j++)
                {
                    if($j!=0)
                        $txt_caracs .= ", ";
                    $subcarac = explode('=>',$carac[$j]);
                    $subcarac[1] = str_replace("'", "", $subcarac[1]);
                    $subcarac[1] = str_replace("'", "", $subcarac[1]);
                    $txt_caracs .= $carac_hija->{$subcarac[1]};
                }
            }

            $tabla .= "<tr><td>$l->id</td><td>$l->Producto $txt_caracs</td><td id='cant-$l->id'>$l->cantidad</td><td>$l->importe</td><td><input type='text' class='rec' id='rec-$l->id' value='$l->cantidad' onchange='faltantes($l->id)'></td><td><input type='text' class='fal' id='fal-$l->id' value='0'></td></tr>";

           
        }
        echo $tabla;
    }

    function guardar_recepcion()
    {
        $cont = 0;
        if($this->InventariosModel->cerrar_traslado($_POST))
            $cont++;

        $ids = explode("**/**",$_POST['idsMovs']);
        $tope = count($ids);
        for($i=0;$i<=$tope-1;$i++)
        {
            if($this->InventariosModel->guardar_recepcion($ids[$i],$_POST))
                $cont++;
        }
        $tope++;
        if($tope == $cont)
            $num = 1;
        else
            $num = 0;
        echo $num;
    }

	function guardar_movimiento()
	{
        if(intval($_POST['costeo']) == 6 && $_POST['series'] != '' && $_POST['tipo'] != 1)
        {
            $cont = 0;
            $series = explode("@|@",$_POST['series']);
            

            $res = $this->InventariosModel->costosSeries($_POST['series']);
            while($r = $res->fetch_assoc())
            {
                if($this->InventariosModel->guardar_movimiento($_POST['idprod'],1,$r['costo'],$_POST['almacen_origen'],$_POST['almacen_destino'],$_POST['tipo'],$r['costo'],$_POST['fecha'],$_POST['referencia'],$_POST['caracteristicas'],$_POST['pedimentos'],$_POST['lotes'],$r['id']."@|@",$_POST['tras']))
                    $cont++;
            }



            if($cont == count($series)-1)
                echo true;
            else
                echo false;
        }
        else
        {
            echo $this->InventariosModel->guardar_movimiento($_POST['idprod'],$_POST['cantidad'],$_POST['importe'],$_POST['almacen_origen'],$_POST['almacen_destino'],$_POST['tipo'],$_POST['costo'],$_POST['fecha'],$_POST['referencia'],$_POST['caracteristicas'],$_POST['pedimentos'],$_POST['lotes'],$_POST['series'],$_POST['tras']); 
        }
	}

	function listaAlmacenesInv()
	{
		$listaAlmacenes = $this->InventariosModel->listaAlmacenes(1,$_POST['idprod'],$_POST['caracteristicas'],$_POST['pedimentos'],$_POST['lotes'],$_POST['series']);
		//echo $listaAlmacenes;
		$nombre_anterior = ''; 
        $codigo_sistema_anterior = 'z';
                          
        while($l = $listaAlmacenes->fetch_assoc())
        {
        	$num = substr_count($l['codigo_sistema'], '.');
            $vacio = "";
            for($i=1;$i<=$num;$i++)
            	$vacio .= "|&nbsp;&nbsp;&nbsp;";
                                                
            $select .= "<option value='".$l['id']."' cantidad='".$l['cantidad']."'>$vacio".$l['nombre']." (".$l['cantidad'].")</option>";
                            
        }    
        echo $select;

	}

	function caracteristicasProd()
	{
		$lista = $this->InventariosModel->caracteristicasProd($_POST['idprod']);
		$echo = "";
		$IdPadreAnterior = 0;
		$cont = 0;
		while($l = $lista->fetch_assoc())
		{
			if(intval($IdPadreAnterior) != intval($l['IdPadre']))
			{
				$cont++;
				if(intval($IdPadreAnterior))
					$echo .= "</select></div></div>";
				$echo .= "<div class='row'><div class='col-xs-1 col-md-3'>".$l['NombrePadre'].":</div><div class='col-xs-3 col-md-6'> <select id='carac-$cont' idpadre = '".$l['IdPadre']."' class='form-control' onchange='inv(0)' style='width:250px;'>";
				$echo .= "<option value='".$l['IdHija']."'>".$l['NombreHija']."</option>";
			}

			if(intval($IdPadreAnterior) == intval($l['IdPadre']))
				$echo .= "<option value='".$l['IdHija']."'>".$l['NombreHija']."</option>";

	
			$IdPadreAnterior = $l['IdPadre'];
		}
		if($cont)
		{
			$echo .= "</select></div></div>";
			$echo .= "<input type='hidden' id='numCarac' value='$cont'>";
		}
		else
			$echo = '0';
		
		echo $echo;
	}

	function otrasCarac()
	{
		$carac = $this->InventariosModel->otrasCarac($_POST['idprod']);
		echo $carac['series']."|".$carac['lotes']."|".$carac['pedimentos'];
	}

	function pls()
	{
        $opciones = "";
		if($opc = $this->InventariosModel->pls($_POST['idprod'],$_POST['pls'],$_POST['idped'],$_POST['idalmacen']))
        {
            while($o = $opc->fetch_assoc())
            {
                $opciones .= "<option value='".$o['id']."'>".$o['nombre']."</option>";
            }
        }
		echo $opciones;
	}

	function cancelar_accion()
	{
		$this->InventariosModel->cancelar_accion($_POST['idmov']);
	}
	//TERMINAN FUNCIONES DE ENTRADAS

	////////CH
    function kardex3(){
        $idProducto     = $_GET["idProducto"];
        $idalmacen      = $_GET["idalmacen"];
        $desde          = $_GET["desde"];
        $hasta          = $_GET["hasta"];
        $R1             = $_GET["R1"];
        $iddep          = $_GET["iddep"];
        $idfa           = $_GET["idfa"];
        $idli           = $_GET["idli"];

        $inventarioActual2 = $this->InventariosModel->indexGrid2($idProducto,$idalmacen,$desde,$hasta,$R1,$iddep,$idfa,$idli);
        require('views/inventarios/kardex3.php'); 
      
    }
    function listFamilia(){
        $iddepartamento = $_POST['iddepartamento'];
        $listFamilia  = $this->InventariosModel->listarFamilia($iddepartamento);
        echo json_encode($listFamilia);
    }
    function listLinea(){
        $idfamilia = $_POST['idfamilia'];
        $listLinea  = $this->InventariosModel->listarLinea($idfamilia);
        echo json_encode($listLinea);
    }
    function existencias(){
    	$idProducto     = $_GET["idProducto"];
        $idalmacen      = $_GET["idalmacen"];
        $desde          = $_GET["desde"];
        $hasta          = $_GET["hasta"];
        $R1             = $_GET["R1"];
        $iddep          = $_GET["iddep"];
        $idfa           = $_GET["idfa"];
        $idli           = $_GET["idli"];
        
        $existencias = $this->InventariosModel->existenciasGrid($idProducto,$idalmacen,$desde,$hasta,$R1,$iddep,$idfa,$idli);
        require('views/inventarios/existencias.php'); 
    }
    function cataproductos(){
        //$inventarioActual2 = $this->InventariosModel->indexGrid2($idProducto,$idalmacen,$desde,$hasta,$R1,$iddep,$idfa,$idli);
        require('views/inventarios/cataproductos.php'); 
    }
    function listProductos(){
    	
    	$idProducto = $_POST['producto'];
    	$idUnidad 	= $_POST['unidad'];
    	$idMoneda 	= $_POST['moneda'];
    	$lote 		= $_POST['lotes'];
    	$series 	= $_POST['series'];
    	$pedi 		= $_POST['pedi'];
    	$carac 		= $_POST['caract'];

        $listProductos  = $this->InventariosModel->listarProductos($idProducto,$idUnidad,$idMoneda,$lote,$series,$pedi,$carac);
        echo json_encode($listProductos);
    }
    function selectProductos(){
        $selectProductos  = $this->InventariosModel->selectProductosM();
        echo json_encode($selectProductos);
    }
    function selectUnidades(){
        $selectUnidades  = $this->InventariosModel->selectUnidadesM();
        echo json_encode($selectUnidades);
    }
    function selectMonedas(){
        $selectMonedas  = $this->InventariosModel->selectMonedasM();
        echo json_encode($selectMonedas);
    }

    function reporte_slp()
    {
    	/*$listaProductos = $this->InventariosModel->listaProductos();
    	$lp = "<option value='0'>Todos</option>";
    	while($l = $listaProductos->fetch_assoc())
    		$lp .= "<option value='".$l['id']."'>(".$l['codigo'].") ".$l['nombre']."</option>";
*/
    	$listaSucursales = $this->InventariosModel->listaSucursales();
    	$ls = "<option value='0'>Todos</option>";
    	while($l = $listaSucursales->fetch_assoc())
    		$ls .= "<option value='".$l['idSuc']."'>(".$l['clave'].") ".$l['nombre']."</option>";
    	$vista = $_GET['vista'];
        if($vista==2){
            require("views/inventarios/reporte_slp2.php");
        }else{
            require("views/inventarios/reporte_slp.php");
        }
    	
    }

    public function buscarProductos()
    {
        $opcion = $_GET['opcion'];
        $patron = $_GET['patron'];
        echo json_encode( $this->InventariosModel->buscarProductos($opcion, $patron) );
    }

    function listaAlmacenesSuc()
    {
    	$lista = $this->InventariosModel->listaAlmacenesSuc($_POST['idSuc']);
    	$select = "<option value='0'>Todos</option>";
    	while($l = $lista->fetch_assoc())
    		$select .= "<option value='".$l['id']."'>(".$l['codigo_manual'].") ".$l['nombre']."</option>";
    	echo $select;
    }

/*
    function slp()
    {

    	//Si el reporte es de series.
    	if(intval($_POST['opc']) == 1)
    	{
    		$tabla .= "<thead><tr><th colspan='2'></th><th class='titulo' colspan='4'>Entradas</th><th class='titulo' colspan='4'>Salidas</th></tr>";
    		$tabla .= "<tr class='titulo'><th># Serie</th><th>Estado</th><th>Fecha</th><th>Folio</th><th>Concepto</th><th>Almacen</th><th>Fecha</th><th>Folio</th><th>Concepto</th><th>Almacen</th></tr></thead>";
    		$tabla .= "<tbody>";
    		$series = $this->InventariosModel->series_slp($_POST);
    		//echo $series;
    		while($s = $series->fetch_assoc())
    		{
    			if($prodAnterior != intval($s['id_producto']))
    				$tabla .= "<tr class='prod_row'><td colspan='10'><b>".$s['Producto']."</b></td></tr>";
    			$estatus = "Disponible";
    			if(intval($s['estatus']))
    				$estatus = "No Disponible";
    			$tabla .= "<tr><td>".$s['serie']."</td><td>$estatus</td>";

    			//Entrada
    			if(!intval($s['id_almacen_origen']))
    			{
    				$concepto = "Compra";
    				if($s['origen'])
    					$concepto = "Entrada";
    				$tabla .= "<td>".$s['fecha']."</td><td>".$s['Folio']."</td><td>$concepto</td><td>".$s['Almacen_Destino']."</td><td></td><td></td><td></td><td></td></tr>";
    			}
    			//Salida
    			if(!intval($s['id_almacen_destino']))
    			{
    				$concepto = "Venta";
    				if($s['origen'])
    					$concepto = "Salida";
    				$tabla .= "<td></td><td></td><td></td><td></td><td>".$s['fecha']."</td><td>".$s['Folio']."</td><td>$concepto</td><td>".$s['Almacen_Origen']."</td></tr>";
    			}
    			//Traspaso
    			if(intval($s['id_almacen_origen']) && intval($s['id_almacen_destino']))
    			{
    				$tabla .= "<td>".$s['fecha']."</td><td>".$s['Folio']."</td><td>Traspaso Entrada</td><td>".$s['Almacen_Destino']."</td><td>".$s['fecha']."</td><td>".$s['Folio']."</td><td>Traspaso Salida</td><td>".$s['Almacen_Origen']."</td></tr>";
    			}
    			$prodAnterior = intval($s['id_producto']);
    		}
    		$tabla .= "</tbody>";
    	}

    	//Si el reporte es de lotes o pedimentos.
    	if(intval($_POST['opc']) == 2 || intval($_POST['opc']) == 3)
    	{
    		$tabla .= "<thead><tr><th class='titulo' colspan='5'>Entradas</th><th class='titulo' colspan='5'>Salidas</th></tr>";
    		$tabla .= "<tr class='titulo'><th>Fecha</th><th>Folio</th><th>Concepto</th><th>Almacen</th><th>Cantidad</th><th>Fecha</th><th>Folio</th><th>Concepto</th><th>Almacen</th><th>Cantidad</th></tr></thead>";
    		$tabla .= "<tbody>";
    		$lotes_ped = $this->InventariosModel->pedimentos_lotes_slp($_POST);
    		//echo $lotes_ped;
    		while($s = $lotes_ped->fetch_assoc())
    		{
    			if($prodAnterior != intval($s['id_producto']))
    				$tabla .= "<tr class='prod_row'><td colspan='10'><b>".$s['Producto']."</b></td></tr>";

    			//Entrada
    			if(!intval($s['id_almacen_origen']))
    			{
    				$concepto = "Compra";
    				if($s['origen'])
    					$concepto = "Entrada";
    				$tabla .= "<td>".$s['fecha']."</td><td>".$s['Folio']."</td><td>$concepto</td><td>".$s['Almacen_Destino']."</td><td>".$s['cantidad']."</td><td></td><td></td><td></td><td></td><td></td></tr>";
    			}
    			//Salida
    			if(!intval($s['id_almacen_destino']))
    			{
    				$concepto = "Venta";
    				if($s['origen'])
    					$concepto = "Salida";
    				$tabla .= "<td></td><td></td><td></td><td></td><td><td>".$s['fecha']."</td><td>".$s['Folio']."</td><td>$concepto</td><td>".$s['Almacen_Origen']."</td><td>".$s['cantidad']."</td></tr>";
    			}
    			//Traspaso
    			if(intval($s['id_almacen_origen']) && intval($s['id_almacen_destino']))
    			{
    				$tabla .= "<td>".$s['fecha']."</td><td>".$s['Folio']."</td><td>Traspaso Entrada</td><td>".$s['Almacen_Destino']."</td><td>".$s['cantidad']."</td><td>".$s['fecha']."</td><td>".$s['Folio']."</td><td>Traspaso Salida</td><td>".$s['Almacen_Origen']."</td><td>".$s['cantidad']."</td></tr>";
    			}
    			$prodAnterior = intval($s['id_producto']);
    		}
    		$tabla .= "</tbody>";
    	}

    	//Si el reporte es de productos caducos.
    	if(intval($_POST['opc']) == 4)
    	{
    		$tabla = "<thead><tr class='titulo'><th>Codigo</th><th>Producto</th><th>Lote</th><th>Caducidad</th><th>Fabricacion</th><th>Disponibles</th></tr></thead>";
    		$tabla .= "<tbody>";
    		$caducos = $this->InventariosModel->caducos_slp($_POST);
    		$cont = 0;
    		$totalAlmacen = $totalGeneral = 0;
    		//echo $caducos;
    		while($s = $caducos->fetch_assoc())
    		{
    			if($almacenPadre != intval($s['Almacen_Padre']))
    			{
    				if($cont)
    				{
    					$tabla .= "<tr class='titulo'><td colspan='4'></td><td><b>Total en: $nombrePadre</b></td><td>$totalAlmacen</td></tr>";
    					$totalAlmacen = 0;
    				}

    			}
    			if($almacenAnterior != intval($s['id_almacen']))
    			{

    				$tabla .= "<tr class='prod_row'><td colspan='6'><b>".$s['Almacen']."</b></td></tr>";
    			}

    			$tabla .= "<tr><td>".$s['codigo']."</td><td>".$s['nombre']."</td><td>".$s['no_lote']."</td><td>".$s['fecha_caducidad']."</td><td>".$s['fecha_fabricacion']."</td><td>".$s['disponibles']."</td></tr>";

    			$almacenAnterior = intval($s['id_almacen']);
    			$almacenPadre = intval($s['Almacen_Padre']);
    			$cont++;
    			$totalAlmacen += $s['disponibles'];
    			$totalGeneral += $s['disponibles'];
    			$nombrePadre = $s['Nombre_Padre'];
    		}
    		$tabla .= "<tr class='titulo'><td colspan='4'></td><td><b>Total en: $nombrePadre</b></td><td>$totalAlmacen</td></tr>";
    		$tabla .= "<tr class='titulo'><td colspan='4'></td><td><b>Total en todos los almacenes</b></td><td>$totalGeneral</td></tr>";
    		$tabla .= "</tbody>";
    	}

    	echo $tabla;
    }
*/
    function slp2() //ch@
    {
        $tabla = '';
        //Si el reporte es de series.
        if(intval($_POST['opc']) == 1){
            $series = $this->InventariosModel->series_slp($_POST);

            while($s = $series->fetch_assoc())
            {
                    $fecha = substr($s['fecha'], 0,10);
                    if($s['Folio'] != null){
                        $folioO1 = $s['Folio'];
                    }else{
                        $$folioO1 = '';
                    }

                if($prodAnterior != intval($s['id_producto'])){
                    // encabezado
                    $seriesR[] = array(
                                Producto       => $s['Producto'],
                                aux            => 'H'
                        ); 
                }
                
                $estatus = "Disponible";
                if(intval($s['estatus']))
                    $estatus = "No Disponible"; 

                //Entrada
                if(!intval($s['id_almacen_origen']))
                {
                    $concepto = "Compra";
                    if($s['origen'] == 0)
                        $concepto = "Entrada";

                    $seriesR[] = array(
                                Producto            => $s['Producto'],
                                serie               => $s['serie'],
                                estatus             => $estatus,
                                fechaE              => $fecha,
                                FolioE              => $folioO1,
                                concepto            => $concepto,                                
                                AlmacenE            => '', 
                                fechaS              => '',
                                FolioS              => '',                                
                                AlmacenS            => $s['Almacen_Destino'],
                                cliente             => '',
                                aux                 => 'B'                                                                                        
                        ); 
                }
                //Salida
                if(!intval($s['id_almacen_destino']))
                {
                    $concepto = "Venta";
                    if($s['origen'] == 0)
                        $concepto = "Salida";

$folioO1= $s['folioO1'];
$sqlfolio01 = "SELECT  id FROM app_envios WHERE id_oventa = '$folioO1';";
$res = $this->InventariosModel->queryArray($sqlfolio01);
                    $seriesR[] = array(
                                Producto            => $s['Producto'],
                                serie               => $s['serie'],
                                estatus             => $estatus,
                                fechaE              => '',
                                FolioE              => '',
                                concepto            => $concepto,
                                AlmacenE            => $s['Almacen_Origen'],
                                fechaS              => $fecha,
                                FolioS              => $res['rows'][0]['id'],                                
                                AlmacenS            => '',                                
                                cliente             => $s['cliente'],
                                aux                 => 'B'                                                                                         
                        ); 
                }
                // traspaso
                if(intval($s['id_almacen_origen']) && intval($s['id_almacen_destino']))
                {
                    $seriesR[] = array(
                                Producto            => $s['Producto'],
                                serie               => $s['serie'],
                                estatus             => $estatus,
                                fechaE              => $fecha,
                                FolioE              => $folioO1,
                                concepto            => 'Traspaso',
                                AlmacenE            => $s['Almacen_Origen'],
                                fechaS              => $fecha,
                                FolioS              => $folioO1,                                
                                AlmacenS            => $s['Almacen_Destino'],
                                cliente             => '',
                                aux                 => 'B'                                                                                        
                        ); 
                }
                $prodAnterior = intval($s['id_producto']);    
            }
            $tabla = $seriesR;
        }
        //Si el reporte es de lotes o pedimentos.
        if(intval($_POST['opc']) == 2 || intval($_POST['opc']) == 3){
            $lotes_ped = $this->InventariosModel->pedimentos_lotes_slp($_POST);
            while($s = $lotes_ped->fetch_assoc())
            {
                
                if($prodAnterior != intval($s['id_producto'])){
                    // encabezado
                    $lotePediR[] = array(
                                Producto       => $s['Producto'],
                                aux            => 'H'
                        ); 
                } 
                                  
                   
                //Entrada
                if(!intval($s['id_almacen_origen']))
                {
                    $concepto = "Compra";
                    if($s['origen'] == 0)
                        $concepto = "Entrada";
                    
                    $lotePediR[] = array(
                                Producto            => $s['Producto'],
                                concepto            => $concepto,
                                AlmacenS            => '', 
                                AlmacenE            => $s['Almacen_Destino'],
                                fechaE              => $fecha,
                                FolioE              => $s['Folio'],                            
                                fechaS              => '',
                                FolioS              => '',
                                cantidad            => $s['cantidad'],
                                aux                 => 'B'
                        ); 
                    
                }
                //Salida
                if(!intval($s['id_almacen_destino']))
                {
                    $concepto = "Venta";
                    if($s['origen'] == 0)
                        $concepto = "Salida";
                    
                    $lotePediR[] = array(
                                Producto            => $s['Producto'],
                                concepto            => $concepto,
                                AlmacenS            => $s['Almacen_Origen'], 
                                AlmacenE            => '',
                                fechaE              => '',
                                FolioE              => '',
                                fechaS              => $fecha,
                                FolioS              => $s['Folio'],
                                cantidad            => $s['cantidad'],
                                aux                 => 'B'                                                                                        
                        ); 
                    
                }
                //Traspaso
                if(intval($s['id_almacen_origen']) && intval($s['id_almacen_destino']))
                {
                    $lotePediR[] = array(
                                Producto            => $s['Producto'],
                                concepto            => 'Traspaso',  
                                AlmacenS            => $s['Almacen_Origen'],  
                                AlmacenE            => $s['Almacen_Destino'],                            
                                fechaE              => $fecha,
                                FolioE              => $s['Folio'],                                                                
                                fechaS              => $fecha,
                                FolioS              => $s['Folio'],                                
                                cantidad            => $s['cantidad'],
                                aux                 => 'B'                                                                                        
                        ); 
                }
                $prodAnterior = intval($s['id_producto']);
            }
            $tabla = $lotePediR; 
        }
        //Si el reporte es de productos caducos.
        if(intval($_POST['opc']) == 4){
            $caducos = $this->InventariosModel->caducos_slp($_POST);
            $cont = 0;
            $totalAlmacen = $totalGeneral = 0;

            while($s = $caducos->fetch_assoc())
            {
                /*if($almacenPadre != intval($s['Almacen_Padre']))
                {
                    if($cont)
                    {
                        $coducos[] = array(
                                Totalen            => $nombrePadre,
                                aux                => 'H'
                        ); 
                        $totalAlmacen = 0;
                    }

                }
                if($almacenAnterior != intval($s['id_almacen']))
                {
        
                    $coducos[] = array(
                                almacen            => $s['Almacen'],
                                aux                => 'H'
                        ); 
                }*/

                
                $coducos[] = array(
                                codigo              => $s['codigo'],
                                nombre              => $s['nombre'],
                                no_lote             => $s['no_lote'],
                                fecha_caducidad     => $s['fecha_caducidad'],
                                fecha_fabricacion   => $s['fecha_fabricacion'],
                                disponibles         => $s['disponibles'],
                                aux                 => 'B',
                                unidad_compra       => $s['unidad_compra'],
                                unidad_venta       => $s['unidad_venta'],
                                almacen       => $s['Almacen'],
                        ); 

                $almacenAnterior = intval($s['id_almacen']);
                $almacenPadre = intval($s['Almacen_Padre']);
                $cont++;
                $totalAlmacen += $s['disponibles'];
                $totalGeneral += $s['disponibles'];
                $nombrePadre = $s['Nombre_Padre'];
            }
            /*$coducos[] = array(
                                Totalen            => $nombrePadre,
                                totalAlmacen       => $totalAlmacen,
                                aux                => 'F1'
                        ); 
            $coducos[] = array( Totaltodos => $totalGeneral, aux => 'F2');*/

            $tabla = $coducos; 
        }
        echo json_encode($tabla);
    }
    

    function printer()
    {
        $info = $this->InventariosModel->printer($_REQUEST['idMov']);
        require("views/inventarios/print.php");
    }

    function subeLayout()
    {
        $directorio = "importacion/";
        if (isset($_FILES["layout"])) 
        {
                if($_FILES['layout']['name'])
                {
                    if (move_uploaded_file($_FILES['layout']['tmp_name'], $directorio.basename("inventarios_temp.xls" ) )) 
                    {
                        echo "Validando archivo...  <br/>";
                        include($directorio."import_inventarios.php");
                    } 
                    else 
                    {
                        echo "No se subio el archivo de Inventarios <br/>";
                    }
                }
        }
    }


    function costoS()
    {
        $html = '';
        $suma = 0;
        $cant = 0;
        if($_POST['series'] != '')
        {
            $res = $this->InventariosModel->costosSeries($_POST['series']);
            while($r = $res->fetch_object())
            {
                $html .= "<b>$r->serie :</b> $r->costo, ";
                $suma += $r->costo;
                $cant++;
            }
        }

        echo $html."*|||*".$suma."*|||*".$cant;
    }

    function costoLP()
    {
        if($_POST['tipo'])
        {
            echo $this->InventariosModel->costoP($_POST['id']);
        }
        else
        {
            echo $this->InventariosModel->costoL($_POST['id']);
        }

    }

    function costeoProd()
    {   
        $idtipocosto = $this->InventariosModel->tipoCosteoProd($_POST['idprod']);
            if($idtipocosto==1){
                $elunit = $this->InventariosModel->costeoProd($_POST['idprod']);
            }else if($idtipocosto==3){
                $elunit = $this->InventariosModel->costeoUltimoCosto($_POST['idprod']);
            }else{
                $elunit = $this->InventariosModel->costeoProd($_POST['idprod']);
            }
            $elcost = ($elunit*1);
        echo $elcost;
    }

    function info_traslado()
    {
        echo $this->InventariosModel->info_traslado($_POST['id_tras']);
    }

    function genera_traslado()
    {
        echo $this->InventariosModel->genera_traslado();
    }

    function info_traspaso_mod()
    {
        echo $this->InventariosModel->info_traspaso_mod($_POST['idtras']);
    }


    function tras_prods()
    {
        $info = $this->InventariosModel->tras_prods($_POST['tras']);
        $tabla = "<tr style='background-color:gray;'><td width='150'>Id Producto</td><td width='150'>Producto</td><td width='150'>Cantidad</td><td width='150'>Importe</td><td width='150'></td></tr>";
        $carac_hija = json_decode($this->InventariosModel->carac_hija());
        while($i = $info->fetch_object())
        {
            $txt_caracs = '';
            if($i->id_producto_caracteristica != "" && $i->id_producto_caracteristica != "'0'")
            {

                $carac = explode(',',$i->id_producto_caracteristica);
                for($j = 0; $j <= count($carac)-1; $j++)
                {
                    if($j!=0)
                        $txt_caracs .= ", ";
                    $subcarac = explode('=>',$carac[$j]);
                    $subcarac[1] = str_replace("'", "", $subcarac[1]);
                    $subcarac[1] = str_replace("'", "", $subcarac[1]);
                    $txt_caracs .= $carac_hija->{$subcarac[1]};
                }
            }

            $tabla .= "<tr><td>$i->codigo</td><td>$i->nombre $txt_caracs</td><td>$i->cantidad</td><td>$ ".number_format($i->importe,2)."</td><td><a href='javascript:cancelar_movto($i->id);'>Cancelar</a></td></tr>";
        }
        echo $tabla;
    }

    function guardar_traslado()
    {
        echo $this->InventariosModel->guardar_traslado($_POST);
    }

    function cancelar_traspaso()
    {
        echo $this->InventariosModel->cancelar_traspaso($_POST['idtras']);
    }

    function cancelar_movto()
    {
        echo $this->InventariosModel->cancelar_movto($_POST['id']);
    }
}
?>
