<?php
//Carga la funciones comunes top y footer
require('common.php');

//Carga el modelo para este controlador
require("models/reportes.php");

class Reportes extends Common
{
	public $ReportesModel;

	function __construct()
	{
		//Se crea el objeto que instancia al modelo que se va a utilizar

		$this->ReportesModel = new ReportesModel();
		$this->ReportesModel->connect();
	}

	function __destruct()
	{
		//Se destruye el objeto que instancia al modelo que se va a utilizar
		$this->ReportesModel->close();
	}
/// VENTAS DIARIAS
    function mas(){
        $diaH = $_POST['diaH'];

        $diaH = strtotime ( '+1 day' , strtotime ( $diaH ) ) ;
        $diaH = date ( 'Y-m-j' , $diaH );
        
        $diaD = strtotime ( '-5 day' , strtotime ( $diaH ) ) ;
        $diaD = date ( 'Y-m-j' , $diaD );
        
       $multArraMovi = array('diaD' => $diaD, 'diaH' => $diaH);
       echo json_encode($multArraMovi);

    }
    function menos(){
        $diaH = $_POST['diaH'];

        $diaH = strtotime ( '-1 day' , strtotime ( $diaH ) ) ;
        $diaH = date ( 'Y-m-j' , $diaH );
        
        $diaD = strtotime ( '-5 day' , strtotime ( $diaH ) ) ;
        $diaD = date ( 'Y-m-j' , $diaD );
        
        $multArraMovi = array('diaD' => $diaD, 'diaH' => $diaH);
        echo json_encode($multArraMovi);

    }
    function ventasdiarias(){

        $fecha = date('Y-m-j');
        
        $fecha = strtotime ( '0 day' , strtotime ( $fecha ) ) ; // omitir si sale un dia menos
        $fecha = date ( 'Y-m-j' , $fecha ); // omitir si sale un dia menos

        $nuevafecha = strtotime ( '-5 day' , strtotime ( $fecha ) ) ;
        $nuevafecha = date ( 'Y-m-j' , $nuevafecha );
        
        require('views/reportes/ventasdiarias.php');   
    }
    function reloadVD(){
        $diaD = $_POST['diaD'];
        $diaH = $_POST['diaH'];

        $dias = array('Domingo','Lunes','Martes','Miercoles','Jueves','Viernes','Sabado');

        $dia5 = $diaH;
        $dia5L = $dias[date('N', strtotime($dia5))];

        $dia4 = strtotime ( '-1 day' , strtotime ( $dia5 ) ) ;
        $dia4 = date ( 'Y-m-j' , $dia4 ); 
        $dia4L = $dias[date('N', strtotime($dia4))];

        $dia3 = strtotime ( '-1 day' , strtotime ( $dia4 ) ) ;
        $dia3 = date ( 'Y-m-j' , $dia3 ); 
        $dia3L = $dias[date('N', strtotime($dia3))];

        $dia2 = strtotime ( '-1 day' , strtotime ( $dia3 ) ) ;
        $dia2 = date ( 'Y-m-j' , $dia2 );
        $dia2L = $dias[date('N', strtotime($dia2))];

        $dia1 = strtotime ( '-1 day' , strtotime ( $dia2 ) ) ;
        $dia1 = date ( 'Y-m-j' , $dia1 );
        $dia1L = $dias[date('N', strtotime($dia1))];

        $dia[0] = array( dia => $dia1, diaL => $dia1L);
        $dia[1] = array( dia => $dia2, diaL => $dia2L);
        $dia[2] = array( dia => $dia3, diaL => $dia3L);
        $dia[3] = array( dia => $dia4, diaL => $dia4L);
        $dia[4] = array( dia => $dia5, diaL => $dia5L);

        $ventasR = $this->ReportesModel->ventas2($dia1,$dia2,$dia3,$dia4,$dia5);

        $arrayV[]=array(
                    diaL        => $dia1L,
                    dia         => $dia1,
                    cancelaciones     => $ventasR['dia1'][0]['cancelaciones'],
                    devoluciones     => $ventasR['dia1'][0]['devoluciones'],
                    importe     => $ventasR['dia1'][0]['importe'],
                    costo       => $ventasR['dia1'][0]['costo'],
                    ganancia    => $ventasR['dia1'][0]['ganancia'],
                    //ganancia    => $ventasR['dia1'][0]['importe'] - $ventasR['dia1'][0]['costo'],
                    gananciaPor => $ventasR['dia1'][0]['gananciaPor'],
                    //gananciaPor => (($ventasR['dia1'][0]['importe'] - $ventasR['dia1'][0]['costo'])*100)/$ventasR['dia1'][0]['importe'],
                    gananciaPor2 => (($ventasR['dia1'][0]['ganancia'] * 100) /  $ventasR['dia1'][0]['costo']),
            );
        $arrayV[]=array(
                    diaL        => $dia2L,
                    dia         => $dia2,
                    cancelaciones     => $ventasR['dia2'][0]['cancelaciones'],
                    devoluciones     => $ventasR['dia2'][0]['devoluciones'],
                    importe     => $ventasR['dia2'][0]['importe'],
                    costo       => $ventasR['dia2'][0]['costo'],
                    ganancia    => $ventasR['dia2'][0]['ganancia'],
                    //ganancia    => $ventasR['dia2'][0]['importe'] - $ventasR['dia2'][0]['costo'],
                    gananciaPor => $ventasR['dia2'][0]['gananciaPor'],
                    //gananciaPor => (($ventasR['dia2'][0]['importe'] - $ventasR['dia2'][0]['costo'])*100)/$ventasR['dia2'][0]['importe'],
                    gananciaPor2 => (($ventasR['dia2'][0]['ganancia'] * 100) /  $ventasR['dia2'][0]['costo']),
            );
        $arrayV[]=array(
                    diaL        => $dia3L,
                    dia         => $dia3,
                    cancelaciones     => $ventasR['dia3'][0]['cancelaciones'],
                    devoluciones     => $ventasR['dia3'][0]['devoluciones'],
                    importe     => $ventasR['dia3'][0]['importe'],
                    costo       => $ventasR['dia3'][0]['costo'],
                    ganancia    => $ventasR['dia3'][0]['ganancia'],
                    //ganancia    => $ventasR['dia3'][0]['importe'] - $ventasR['dia3'][0]['costo'],
                    gananciaPor => $ventasR['dia3'][0]['gananciaPor'],
                    //gananciaPor => (($ventasR['dia3'][0]['importe'] - $ventasR['dia3'][0]['costo'])*100)/$ventasR['dia3'][0]['importe'],
                    gananciaPor2 => (($ventasR['dia3'][0]['ganancia'] * 100) /  $ventasR['dia3'][0]['costo']),
            );
        $arrayV[]=array(
                    diaL        => $dia4L,
                    dia         => $dia4,
                    cancelaciones     => $ventasR['dia4'][0]['cancelaciones'],
                    devoluciones     => $ventasR['dia4'][0]['devoluciones'],
                    importe     => $ventasR['dia4'][0]['importe'],
                    costo       => $ventasR['dia4'][0]['costo'],
                    ganancia    => $ventasR['dia4'][0]['ganancia'],
                    //ganancia    => $ventasR['dia4'][0]['importe'] - $ventasR['dia4'][0]['costo'],
                    gananciaPor => $ventasR['dia4'][0]['gananciaPor'],
                    //gananciaPor => (($ventasR['dia4'][0]['importe'] - $ventasR['dia4'][0]['costo'])*100)/$ventasR['dia4'][0]['importe'],
                    gananciaPor2 => (($ventasR['dia4'][0]['ganancia'] * 100) /  $ventasR['dia4'][0]['costo']),
            );
        $arrayV[]=array(
                    diaL        => $dia5L,
                    dia         => $dia5,
                    cancelaciones     => $ventasR['dia5'][0]['cancelaciones'],
                    devoluciones     => $ventasR['dia5'][0]['devoluciones'],
                    importe     => $ventasR['dia5'][0]['importe'],
                    costo       => $ventasR['dia5'][0]['costo'],
                    ganancia    => $ventasR['dia5'][0]['ganancia'],
                    //ganancia    => $ventasR['dia5'][0]['importe'] - $ventasR['dia5'][0]['costo'],
                    gananciaPor => $ventasR['dia5'][0]['gananciaPor'],
                    //gananciaPor => (($ventasR['dia5'][0]['importe'] - $ventasR['dia5'][0]['costo'])*100)/$ventasR['dia5'][0]['importe'],
                    gananciaPor2 => (($ventasR['dia5'][0]['ganancia'] * 100) /  $ventasR['dia5'][0]['costo']),
            );


        foreach ($arrayV as $key => $v) {
            $cancelaciones        = number_format($v['cancelaciones'],2);
            $devoluciones        = number_format($v['devoluciones'],2);
            $importe        = number_format($v['importe'],2);
            $costo          = number_format($v['costo'],2);
            $ganancia       = number_format($v['ganancia'],2);
            $gananciaPor    = number_format($v['gananciaPor'],2);
            $gananciaPor2    = number_format($v['gananciaPor2'],2);

            

            if($v['diaL'] == ''){
                $diaL = 'Domingo';
            }else{
                $diaL = $v['diaL'];
            }

                $ventas2[] = array(
                            diaL          => $diaL,
                            dia           => $v['dia'],  
                            cancelaciones       => $cancelaciones, 
                            devoluciones       => $devoluciones,                             
                            importe       => $importe,
                            costo         => $costo,
                            ganancia      => $ganancia,
                            gananciaPor   => $gananciaPor,
                            gananciaPor2   => $gananciaPor2,
                            fecha2        => $v['fecha2'],
                        );
        }

        
        //$html = '<div class="col-md-1"><br><br><br><br><br><br><br><br><button onclick="menos();"><i class="fa fa-chevron-left" aria-hidden="true"></i></button></div>';
        $html = '<div class="col-md-1"><br><br><br><br><br><br><br><br><i onclick="menos();" class="fa fa-chevron-left fa-2x" aria-hidden="true" style="cursor: pointer;"></i></div>';
        foreach ($ventas2 as $k => $v2) {
            // APPMINISTRA
            
                $html .= '<div class="col-md-2 col-md-offset-0" style="margin: 0; padding:5px;">
                            <div id="'.$v2['dia'].'" style="background-color:#d3d3d3;  border-radius: 5px;">
                                <label>'.$v2['diaL'].'</label><br>
                                <label> '.date("d-m-Y", strtotime($v2['dia'])).'</label>
                            </div>
                            <div style="padding-bottom:5px;"></div>
                            <div id="'.$v2['dia'].'2"style="border: solid #d3d3d3;  border-radius: 5px; background-color: white; padding:0px;">                                
                                <div><h4>$ '.$v2['importe'].' </h4></div>
                                <div style="color:#084B8A; border-bottom:1px solid #428bca;"><label>Total Ventas</label></div>
                                
                                <div><h4>$ '.$v2['cancelaciones'].'</h4></div>
                                <div style="color:#084B8A;"><label>Cancelaciones</label></div>

                                <div><h4>$ '.$v2['devoluciones'].'</h4></div>
                                <div style="color:#084B8A; border-bottom:1px solid #428bca;"><label>Devoluciones</label></div>
                                                                
                                <div><h4>$ '.$v2['costo'].'</h4></div>
                                <div style="color:#084B8A; border-bottom:1px solid #428bca;"><label>Total Costo de Ventas</label></div>

                                <div><h4>$ '.$v2['ganancia'].'</h4></div>
                                <div style="color:#084B8A; border-bottom:1px solid #428bca;"><label>Ganancias</label></div>                                
                    
                                <div><h4> '.$v2['gananciaPor'].'</h4></div>
                                <div style="color:#084B8A;"><label>% de Utilidad</label></div>
                            </div>                    
                        </div>'; 
                
               
                // FOODWARE -> BONISIMO_CAFE
                /*
                 $html .= '<div class="col-md-2 col-md-offset-0" style="margin: 0; padding:5px;">
                            <div id="'.$v2['dia'].'" style="background-color:#d3d3d3;  border-radius: 5px;">
                                <label>'.$v2['diaL'].'</label><br>
                                <label> '.date("d-m-Y", strtotime($v2['dia'])).'</label>
                            </div>
                            <div style="padding-bottom:5px;"></div>
                            <div id="'.$v2['dia'].'2"style="border: solid #d3d3d3;  border-radius: 5px; background-color: white; padding:0px;">                                
                                <div><h4>$ '.$v2['importe'].' </h4></div>
                                <div style="color:#084B8A; border-bottom:1px solid #428bca;"><label>Total Ventas</label></div>
                                                                                                
                                <div><h4>$ '.$v2['costo'].'</h4></div>
                                <div style="color:#084B8A; border-bottom:1px solid #428bca;"><label>Total Costo de Ventas</label></div>

                                <div><h4>$ '.$v2['ganancia'].'</h4></div>
                                <div style="color:#084B8A; border-bottom:1px solid #428bca;"><label>Ganancias</label></div>                                
                                
                                 <div><h4> '.$v2['gananciaPor2'].'</h4></div>
                                <div style="color:#084B8A;"><label>% de Utilidad</label></div>
                            </div>                    
                        </div>';
                */
        }
        //$html .= '<div class="col-md-1"><br><br><br><br><br><br><br><br><button id=btnnext onclick="mas();"><i class="fa fa-chevron-right" aria-hidden="true"></i></button></div>';
        $html .= '<div class="col-md-1"><br><br><br><br><br><br><br><br><i id=btnnext onclick="mas();" class="fa fa-chevron-right fa-2x" aria-hidden="true" style="cursor: pointer;"></i></div>';

        echo $html;

    }
/// VENTAS DIARIAS FIN
    function estados(){
        $idpais = $_POST['idpais'];
        $estados  = $this->ReportesModel->estados($idpais);
        echo json_encode($estados);
    }

    function caract($desde,$hasta){
        
        $kardexC    = $this->ReportesModel->movCart($desde,$hasta); // solo para el array de las carac
        $caract     = $this->ReportesModel->caract();
        $Padre      = $caract['padre'];
        $Hija       = $caract['hija'];
        $padre1     = '';
        $hija1      = '';

        foreach ($kardexC as $key => $val) { // Recorre el array principal para traspasos 
                                    $id                 = $val['id'];
                                    $caract             = $val['caract'];
                                    if($caract =="'0'"){
                                        
                                    }else{

                                        $exparray=explode(',', $caract);

                                        foreach ($exparray as $k => $v) {
                                           $expv=explode('=>', $v);

                                            $ip=$expv[0];
                                            $ip = str_replace("'", "", $ip); /// elimina las comillas
                                            $ip = $ip*1;

                                            $ih=$expv[1];
                                            $ih = str_replace("'", "", $ih); /// elimina las comillas
                                            $ih = $ih*1;

                                            foreach ($Padre as $key => $valor) {
                                                $idPadre = $valor['id'];
                                                $nombreP = $valor['nombre'];
                                                if($idPadre == $ip){
                                                    $padre1 = $nombreP;
                                                }

                                            }
                                            foreach ($Hija as $key => $valor) {
                                                $idHija = $valor['id'];
                                                $nombreH = $valor['nombre'];
                                                if($idHija == $ih){
                                                    $hija1 = $nombreH;
                                                }

                                            }
                                            $arrCaract[] = array(
                                                id           => $id,
                                                padre1       => $padre1,                                            
                                                hija1        => $hija1,
                                            );
                                        }
                                    }                                
            }// Fin foreach 

            $arrCaractR=array();
            foreach ($arrCaract as $key => $value) {
                $id      = $value['id'];
                $padre1  = $value['padre1'];
                $hija1   = $value['hija1'];
                if(array_key_exists($id, $arrCaractR)){
                    $arrCaractR[$id]['id']=$id;
                    $arrCaractR[$id]['caractR'].=$padre1.": ".$hija1." ";
                }else{
                    $arrCaractR[$id]['id']=$id;
                    $arrCaractR[$id]['caractR'].=$padre1.": ".$hija1." ";
                }
            }
        /// ARREGLO CON LAS CARACTERISTICAS RELACIONADAS CON EL ID DEL MOV
        return $arrCaractR; 
    }
    function selectProductos(){
        $tipo = $_POST['tipo'];
        $selectProductos  = $this->ReportesModel->selectProductosM($tipo);
        echo json_encode($selectProductos);
    }
    function selectDepartamento(){
        $selectDepartamento  = $this->ReportesModel->selectedDepartamento();
        echo json_encode($selectDepartamento);
    }
    function selectProves(){
        $selectDepartamento  = $this->ReportesModel->selectProves();
        echo json_encode($selectDepartamento);
    }
    function selectUnidades(){
        $selectUnidades  = $this->ReportesModel->selectUnidadesM();
        echo json_encode($selectUnidades);
    }
    function selectMonedas(){
        $selectMonedas  = $this->ReportesModel->selectMonedasM();
        echo json_encode($selectMonedas);
    }
    function selectVV(){
        $selectVV  = $this->ReportesModel->selectMVV();
        echo json_encode($selectVV);
    }
    function selectDP(){
        $selectDP  = $this->ReportesModel->selectMDP();
        echo json_encode($selectDP);
    }
    function listProductos(){
        $listProductos  = $this->ReportesModel->listarProductos();
        echo json_encode($listProductos);
    }
    function listAlmacen(){
        $idSuc      = $_POST['idSuc'];
        $listAlmacen  = $this->ReportesModel->listarAlmacen($idSuc);
        echo json_encode($listAlmacen);
    }
    function listSucursal(){
        $listSucursal  = $this->ReportesModel->listarSucursal();
        echo json_encode($listSucursal);
    }
    function cataproductos(){
        require('views/reportes/cataproductos.php'); 
    }
    function listProductosCP(){
        
        $idProducto = $_POST['producto'];
        $idUnidad   = $_POST['unidad'];
        $idMoneda   = $_POST['moneda'];
        $lote       = $_POST['lotes'];
        $series     = $_POST['series'];
        $pedi       = $_POST['pedi'];
        $carac      = $_POST['caract'];
        $act        = $_POST['act'];
        $tipoPro    = $_POST['tipoPro'];

        $tablaP = '<table id="tablepro" class="table table-striped table-bordered sizeprint" cellspacing="0" width="100%">'.
                            '<thead>'.
                            '<tr>'.
                            '<th width="90">Codigo</th>'.
                            '<th>Producto</th>'.
                            '<th width="30">Tipo</th>'.
                            '<th width="30">Unidad</th>'.
                            '<th width="30">Caracteristicas</th>'.
                            '<th width="30">Lotes</th>'.
                            '<th width="30">Series</th>'.
                            '<th width="30">Pedimentos</th>'.
                            '<th width="30">Moneda</th>'.
                            '<th width="30">Impuestos</th>'.
                          '</tr>'.
                        '</thead>';

        $tablaS = '<table id="tableser" class="table table-striped table-bordered sizeprint" cellspacing="0" width="100%">'.
                            '<thead>'.
                            '<tr>'.
                            '<th width="90">Codigo</th>'.
                            '<th>Producto</th>'.
                            '<th width="30">Tipo</th>'.
                            '<th width="30">Unidad</th>'.
                            '<th width="30">Caracteristicas</th>'.
                            '<th width="30">Lotes</th>'.
                            '<th width="30">Series</th>'.
                            '<th width="30">Pedimentos</th>'.
                            '<th width="30">Moneda</th>'.
                            '<th width="30">Impuestos</th>'.
                          '</tr>'.
                        '</thead>';

        $listProductos  = $this->ReportesModel->listarProductosCP($idProducto,$idUnidad,$idMoneda,$lote,$series,$pedi,$carac,$act,$tipoPro);

        foreach ($listProductos as $k => $v) {

            $producto = $v['producto'];
            $producto = str_replace("&#39;", ' ', $producto); // remplaza la comilla siumple
            //$producto = str_replace("#", 'no.', $producto);   // remplaza #

            if($v['tipo_producto'] == 1){ $tipoPro = 'Producto'; }
            if($v['tipo_producto'] == 2){ $tipoPro = 'Servicio'; }
            if($v['tipo_producto'] == 3){ $tipoPro = 'Insumo'; }
            if($v['tipo_producto'] == 4){ $tipoPro = 'Insumo Preparado'; }
            if($v['tipo_producto'] == 5){ $tipoPro = 'Receta'; }
            if($v['tipo_producto'] == 6){ $tipoPro = 'Kit'; }

            if($v['tipo_producto'] != 2){

                  $p ='<tr>'.
                        '<td>'.$v['codigo'].'</td>'.
                        '<td> <a onclick="verProducto(\''.$v['id'].'\',\'' . 'modal_form_conv_edit' . '\',\'' . '1' . '\',\'' . $v['caracteristicas'] . '\',\'' . $v['lotes'] . '\',\'' . $v['series'] . '\',\'' . $v['pedimentos'] . '\');">'.addslashes($producto).'</a></td>'.
                        '<td>'.$tipoPro.'</td>'.
                        '<td>'.$v['unidad'].'</td>'.
                        '<td>'.$v['caracteristicas'].'</td>'.
                        '<td>'.$v['lotes'].'</td>'.
                        '<td>'.$v['series'].'</td>'.
                        '<td>'.$v['pedimentos'].'</td>'.
                        '<td>'.$v['moneda'].'</td>'.
                        '<td>'.$v['impuestos'].'</td></tr>';
                $arrFinalP[] = array( row => $p ); // array para datatable
                $tablaP .= $p;  
            }
            if($v['tipo_producto'] == 2){
                  $s ='<tr>'.
                        '<td>'.$v['codigo'].'</td>'.                
                        '<td> <a onclick="verProducto(\''.$v['id'].'\',\'' . 'modal_form_conv_edit' . '\',\'' . '1' . '\');">'.addslashes($producto).'</a></td>'.
                        '<td>'.$tipoPro.'</td>'.
                        '<td>'.$v['unidad'].'</td>'.
                        '<td>'.$v['caracteristicas'].'</td>'.
                        '<td>'.$v['lotes'].'</td>'.
                        '<td>'.$v['series'].'</td>'.
                        '<td>'.$v['pedimentos'].'</td>'.
                        '<td>'.$v['moneda'].'</td>'.
                        '<td>'.$v['impuestos'].'</td></tr>';
                $arrFinalS[] = array( row => $s ); // array para datatable
                $tablaS .= $s;  
            }            
        }

        echo $tablaP.' ª '.$tablaS;

    }
    function listProducto(){
        $id = $_POST['id'];
        $listProducto  = $this->ReportesModel->listarProducto($id);
        echo json_encode($listProducto);
    }
    function textAreaCP(){
        $id = $_POST['id'];
        $textAreaCP  = $this->ReportesModel->textAreaCPM($id);
        echo json_encode($textAreaCP);
    }

    function ventasVendedor(){
        require('views/reportes/ventasvendedor.php'); 
    }
    function listventasVendedor(){
        $desde      = $_POST['desde'];
        $hasta      = $_POST['hasta'];
        $vendedor   = $_POST['vendedor'];
        $cliente    = $_POST['cliente'];
        $documento  = $_POST['documento'];
        $R1         = $_POST['R1']*1;
        $status = 0;
        $statusF = '';

        $count = 1;
        $listventasVendedor = $this->ReportesModel->listarventasVendedor($desde,$hasta,$vendedor,$cliente,$documento);

        foreach ($listventasVendedor['ventas'] as $key => $valu) {
                            $total                = $valu['total'];
                            $abono                = $valu['abono'];
                            $abonoT               = $valu['abonoT'];

                            if ($abono == -1) {
                                $status = 1;                 // COBRADA
                                $statusF = 'COBRADA';
                            }
                            if($abono == 0){
                                $status = 3;                 // PENDIENTE
                                $statusF = 'PENDIENTE COBRO';
                                if($total == $abonoT){
                                    $status = 1;             // COBRADA
                                    $statusF = 'COBRADA';
                                }
                                if($total > $abonoT){
                                    $status = 2;             // PARCIALMENTE
                                    $statusF = 'PARCIALMENTE COBRADA';
                                }
                                if($abonoT == 0){
                                    $status = 3;             // PARCIALMENTE
                                    $statusF = 'PENDIENTE COBRO';
                                }
                            }

                            $ventasR[] = array(
                                iden           => $valu['iden'],
                                idVendedor     => $$valu['idVendedor'],
                                vendedor       => $valu['vendedor'],
                                id             => $valu['id'],
                                nombrecliente  => $valu['nombrecliente'],
                                fecha          => $valu['fecha'],
                                folio          => $valu['folio'],
                                importe        => $valu['importe'],
                                imp            => $valu['imp'],
                                total          => $total,
                                factura        => $valu['factura'],
                                abono          => $abono,
                                abonoT         => $abonoT,
                                status         => $status,
                                statusF        => $statusF,
                        );   
        }

        foreach ($ventasR as $key => $valor) {

                    $status               = $valor['status'];

                    if($R1 == $status){

                        $ventasS[] = array(
                                    iden           => $valor['iden'],
                                    idVendedor     => $valor['idVendedor'],
                                    vendedor       => $valor['vendedor'],
                                    id             => $valor['id'],
                                    nombrecliente  => $valor['nombrecliente'],
                                    fecha          => $valor['fecha'],
                                    folio          => $valor['folio'],
                                    importe        => $valor['importe'],
                                    imp            => $valor['imp'],
                                    total          => $valor['total'],
                                    factura        => $valor['factura'],
                                    abono          => $valor['abono'],
                                    abonoT         => $valor['abonoT'],
                                    status         => $valor['status'],
                                    statusF        => $valor['statusF'],
                        );
                    }
        }
        
        foreach ($listventasVendedor['ventasD'] as $key => $value) {
                           
            $id = $value['id_oventa'];

            if($id == $idAnt){
                $count++;
            }else{
                $count = 1;
            }

            $vendasD[] = array(
                                id           => $id,
                                codigo       => $value['codigo'],
                                nombre       => $value['nombre'],
                                cantidad     => $value['cantidad'],
                                costo        => $value['costo'],
                                importe      => $value['importe'],
                                iva          => $value['iva'],
                                total        => $value['total'],
                                count        => $count,
                        );
            $idAnt              = $value['id_oventa'];
        }
        foreach ($vendasD as $key => $val) {
            $id                 = $val['id'];
            $count              = $val['count'];
            if($count > 1){
                $vendasDC[] = array(
                                id           => $id,
                                count        => $count,
                        );
            }
        }
        foreach ($vendasD as $key => $v) {

                            $id                 = $v['id'];

                            $aux = 0;
                            foreach ($vendasDC as $key => $va) {
                                $idDC = $va['id'];
                                //$count = $va['count'];
                                if($idDC == $id){
                                    $aux = 1;
                                    break;
                                }else{
                                    $aux = 0;
                                }
                            }

                            $vendasDF[] = array(
                                id_oventa    => $id,
                                codigo       => $v['codigo'],
                                nombre       => $v['nombre'],
                                cantidad     => $v['cantidad'],
                                costo        => $v['costo'],
                                importe      => $v['importe'],
                                iva          => $v['iva'],
                                total        => $v['total'],
                                aux          => $aux,
                        );
        }

        $multArraMovi = array('ventas' => $ventasS, 'ventasD' => $vendasDF);
        
        echo json_encode($multArraMovi);
    }

    function devolucionespro(){
        require('views/reportes/devolucionespro.php'); 
    }
    function graficarDevoluciones(){
        $desde      = $_POST['desde'];
        $hasta      = $_POST['hasta'];
        $proveedor  = $_POST['proveedor'];
        $producto   = $_POST['producto'];
        $sucursal   = $_POST['sucursal'];
        $almacen    = $_POST['almacen'];
        $empleado   = $_POST['empleado'];
        $listDevoluciones  = $this->ReportesModel->graficarDevoluciones($desde,$hasta,$proveedor,$producto,$sucursal,$almacen,$empleado);
        echo json_encode($listDevoluciones);
    }
    function listaDevoluciones(){
        $desde      = $_POST['desde'];
        $hasta      = $_POST['hasta'];
        $proveedor  = $_POST['proveedor'];
        $producto   = $_POST['producto'];
        $sucursal   = $_POST['sucursal'];
        $almacen    = $_POST['almacen'];
        $empleado   = $_POST['empleado'];
        $listDevoluciones  = $this->ReportesModel->listarDevolucionesPro($desde,$hasta,$proveedor,$producto,$sucursal,$almacen,$empleado);
        echo json_encode($listDevoluciones);
    }
    function listDevolucionespro(){
        $desde      = $_POST['desde'];
        $hasta      = $_POST['hasta'];
        $proveedor  = $_POST['proveedor'];
        $producto   = $_POST['producto'];
        $sucursal   = $_POST['sucursal'];
        $almacen    = $_POST['almacen'];
        $empleado   = $_POST['empleado'];
        $listDevoluciones  = $this->ReportesModel->listarDevolucionesPro($desde,$hasta,$proveedor,$producto,$sucursal,$almacen,$empleado);
        $contPrv  = $contPro  = $sumCan = $sumImpu  = $sumTot = $sumCanC  = $sumImpoC = $sumImpuC = $sumTotC = 0;
        foreach ($listDevoluciones as $k => $v) {
            $proveedor   = $v['proveedor'];
            $producto    = $v['producto'];
            $id          = $v['id'];
            $fecha       = $v['fecha_devolucion'];
            $id_dev      = $v['id'];
            $cantidad    = $v['cantidad']*1;                                                                                                
            $importe     = $v['subtotal']*1;
            $total       = $v['total']*1;
            $unidad      = $v['unidad'];
            $impuestos   = $total - $importe;
            $unitario    = $importe / $cantidad;

            if($proveedor != $proveedorAnt){
                $contPrv  = $contPro  = $sumCan = $sumImpu  = $sumTot = $sumCanC  = $sumImpoC = $sumImpuC = $sumTotC = 0;
            }
            if($producto != $productoAnt){
                $contPro = $sumCan = $sumImpo = $sumImpu = $sumTot = 0;
            }
            $contPrv++;
            $contPro++;
            $sumCan   += $cantidad;
            $sumImpo  += $importe;
            $sumImpu  += $impuestos;
            $sumTot   += $total;
            $sumCanC  += $cantidad;
            $sumImpoC += $importe;
            $sumImpuC += $impuestos;
            $sumTotC  += $total;

            $arraDevP[] = array(
                proveedor       => $proveedor,
                producto        => $producto,
                id              => $id,
                fecha           => $fecha,
                id_dev          => $id_dev,
                cantidad        => $cantidad,
                importe         => $importe,
                total           => $total,
                unidad          => $unidad,
                impuestos       => $impuestos,
                unitario        => $unitario,
                contPrv         => $contPrv,
                contPro         => $contPro,
                sumCan          => $sumCan,
                sumImpo         => $sumImpo,
                sumImpu         => $sumImpu,
                sumTot          => $sumTot,
                sumCanC          => $sumCanC,
                sumImpoC         => $sumImpoC,
                sumImpuC         => $sumImpuC,
                sumTotC          => $sumTotC,
                );
            $productoAnt    = $v['producto'];
            $proveedorAnt   = $v['proveedor'];
        }
                $arraDevPR = array_reverse($arraDevP);
        //echo json_encode($arraDevR);
        foreach ($arraDevPR as $k => $va) {
            $contPrv      = $va['contPrv'];
            $contPro      = $va['contPro'];

            if($contPrv > $contPrvAnt){
                $auxPrv = 1;
            }else{
                $auxPrv = 0;
            }
            if($contPro > $contProAnt){
                $auxPro = 1;
            }else{
                $auxPro = 0;
                if($contPrv > $contPrvAnt){
                    $auxPro = 1;
                }else{
                    $auxPro = 0;
                }
            }
            //format
            $importe    = '$'.number_format($va['importe'],2);
            $total      = '$'.number_format($va['total'],2);
            $impuestos  = '$'.number_format($va['impuestos'],2);
            $unitario   = '$'.number_format($va['unitario'],2);
            //$sumCan     = number_format($va['sumCan'],2);
            $sumTot     = '$'.number_format($va['sumTot'],2);
            $sumImpu    = '$'.number_format($va['sumImpu'],2);
            $sumImpo    = '$'.number_format($va['sumImpo'],2);
            //$sumCanC    = number_format($va['sumCanC'],2);
            $sumTotC    = '$'.number_format($va['sumTotC'],2);
            $sumImpuC   = '$'.number_format($va['sumImpuC'],2);
            $sumImpoC   = '$'.number_format($va['sumImpoC'],2);

            $arraDevPFR[] = array(
                proveedor       => $va['proveedor'],
                producto        => $va['producto'],
                id              => $va['id'],
                fecha           => $va['fecha'],
                id_dev          => $va['id_dev'],
                cantidad        => $va['cantidad'],
                importe         => $importe,
                total           => $total,
                unidad          => $va['unidad'],
                impuestos       => $impuestos,
                unitario        => $unitario,
                contPrv         => $contPrv,
                contPro         => $contPro,
                sumCan          => $va['sumCan'],
                sumImpo         => $sumImpo,
                sumImpu         => $sumImpu,
                sumTot          => $sumTot,
                sumCanC         => $va['sumCanC'],
                sumImpoC        => $sumImpoC,
                sumImpuC        => $sumImpuC,
                sumTotC         => $sumTotC,
                auxPrv          => $auxPrv,
                auxPro          => $auxPro,
                );

            $contPrvAnt      = $va['contPrv'];
            $contProAnt      = $va['contPro'];
        }

            $arraDevPF = array_reverse($arraDevPFR); 
            echo json_encode($arraDevPF); 
    }

    function devoluciones(){
        require('views/reportes/devoluciones.php'); 
    }
    function listDevoluciones(){

        $desde      = $_POST['desde'];
        $hasta      = $_POST['hasta'];
        $cliente    = $_POST['cliente'];
        $producto   = $_POST['producto'];
        $sucursal   = $_POST['sucursal'];
        $almacen    = $_POST['almacen'];
        $empleado   = $_POST['empleado'];
        $listDevoluciones  = $this->ReportesModel->listarDevoluciones($desde,$hasta,$cliente,$producto,$sucursal,$almacen,$empleado);
        
        $contCli  = $contPro = $sumCan = $sumImpo = $sumImpu = $sumTot = $sumCanC = $sumImpoC = $sumImpuC = $sumTotC  = 0;
        foreach ($listDevoluciones as $k => $v) {
            $cliente     = $v['nombrecliente'];
            $producto    = $v['producto'];
            $id          = $v['id'];
            $fecha       = $v['fecha_devolucion'];
            $id_dev      = $v['id'];
            $cantidad    = $v['cantidad']*1;                                                                                                
            $importe     = $v['subtotal']*1;
            $total       = $v['total']*1;
            $unidad      = $v['unidad'];
            $impuestos   = $total - $importe;
            $unitario    = $importe / $cantidad;
            
            if($cliente != $clienteAnt){
                $contCli  = $contPro = $sumCan = $sumImpo = $sumImpu = $sumTot = $sumCanC = $sumImpoC = $sumImpuC = $sumTotC  = 0; 
            }
            if($producto != $productoAnt){
                $contPro = $sumCan = $sumImpo = $sumImpu = $sumTot  = 0;
            }
            $contCli++;
            $contPro++;
            $sumCan  += $cantidad;
            $sumImpo += $importe;
            $sumImpu += $impuestos;
            $sumTot  += $total;
            $sumCanC  += $cantidad;
            $sumImpoC += $importe;
            $sumImpuC += $impuestos;
            $sumTotC  += $total;

            $arraDev[] = array(
                cliente         => $cliente,
                producto        => $producto,
                id              => $id,
                fecha           => $fecha,
                id_dev          => $id_dev,
                cantidad        => $cantidad,
                importe         => $importe,
                total           => $total,
                unidad          => $unidad,
                impuestos       => $impuestos,
                unitario        => $unitario,
                contCli         => $contCli,
                contPro         => $contPro,
                sumCan          => $sumCan,
                sumImpo         => $sumImpo,
                sumImpu         => $sumImpu,
                sumTot          => $sumTot,
                sumCanC          => $sumCanC,
                sumImpoC         => $sumImpoC,
                sumImpuC         => $sumImpuC,
                sumTotC          => $sumTotC,
                );
            
            $clienteAnt     = $v['nombrecliente'];
            $productoAnt    = $v['producto'];  
        }
        //echo json_encode($arraDev);
        $arraDevR = array_reverse($arraDev);
        //echo json_encode($arraDevR);
        foreach ($arraDevR as $k => $va) {
            $contCli      = $va['contCli'];
            $contPro      = $va['contPro'];

            if($contCli > $contCliAnt){
                $auxCli = 1;
            }else{
                $auxCli = 0;
            }
            if($contPro > $contProAnt){
                $auxPro = 1;
            }else{
                $auxPro = 0;
                if($contCli > $contCliAnt){
                    $auxPro = 1;
                }else{
                    $auxPro = 0;
                }
            }
            //format
            $importe    = '$'.number_format($va['importe'],2);
            $total      = '$'.number_format($va['total'],2);
            $impuestos  = '$'.number_format($va['impuestos'],2);
            $unitario   = '$'.number_format($va['unitario'],2);
            //$sumCan     = number_format($va['sumCan'],2);
            $sumTot     = '$'.number_format($va['sumTot'],2);
            $sumImpu    = '$'.number_format($va['sumImpu'],2);
            $sumImpo    = '$'.number_format($va['sumImpo'],2);
            //$sumCanC    = number_format($va['sumCanC'],2);
            $sumTotC    = '$'.number_format($va['sumTotC'],2);
            $sumImpuC   = '$'.number_format($va['sumImpuC'],2);
            $sumImpoC   = '$'.number_format($va['sumImpoC'],2);

            $arraDevFR[] = array(
                cliente         => $va['cliente'],
                producto        => $va['producto'],
                id              => $va['id'],
                fecha           => $va['fecha'],
                id_dev          => $va['id_dev'],
                cantidad        => $va['cantidad'],
                importe         => $importe,
                total           => $total,
                unidad          => $va['unidad'],
                impuestos       => $impuestos,
                unitario        => $unitario,
                contCli         => $contCli,
                contPro         => $contPro,
                sumCan          => $va['sumCan'],
                sumImpo         => $sumImpo,
                sumImpu         => $sumImpu,
                sumTot          => $sumTot,
                sumCanC         => $va['sumCanC'],
                sumImpoC        => $sumImpoC,
                sumImpuC        => $sumImpuC,
                sumTotC         => $sumTotC,
                auxCli          => $auxCli,
                auxPro          => $auxPro,
                );

            $contCliAnt      = $va['contCli'];
            $contProAnt      = $va['contPro'];
        }
        $arraDevF = array_reverse($arraDevFR); 
        echo json_encode($arraDevF); 
    }

    function listCostoEsp(){
        $listCostoEsp  = $this->ReportesModel->listarCostoEsp();
        echo json_encode($listCostoEsp);
    }
////////   NEW EXISTENCIAS ///////////////
    function existencias(){
        $listProductos  = $this->ReportesModel->listarProductos();
        $listAlmacen  = $this->ReportesModel->listarAlmacen(0);
        $listunidades  = $this->ReportesModel->unidades();
        require('views/reportes/existencias.php'); 
    }
    function existenciasList(){
    
            $almacenSe  = $_POST['almacen'];
            $producto   = $_POST['producto'];
            $unidades   = $_POST['unidades'];
            $desde      = '1950-01-01 00:00:01'; //default
            $hasta      = $_POST['hasta'];
            $tipo       = 'movs';
            $All        = $_POST['All'];

            $inventarioActualU  = $this->ReportesModel->ubicCaractMov($desde,$hasta,$tipo,$producto,$tipo,2,$unidades); // totdos los mov incluyendo trasp

            $desde = ($desde == '') ? '1900-01-01' : $desde.' 00:00:01';
            $hasta = ($hasta == '') ? '2900-01-01' : $hasta.' 23:59:59';
           
            /// FUNCTION PARA LAS CARACTERISTICAS
            $arrCaractR = $this->caract($desde,$hasta);

            $caract = '';
            $existencia = 0;   

            //////////// COSTEOS //////////////////////
                $costoEntrada = $costoSalida = $prom = $neto = $saldo = $promedio = $existenciaFF = $existencia = 0;
            /*
            foreach ($inventarioActualU as $k => $v) {

                $codigo         = $v['codigo'];
                $cantidad       = $v['cantidad'];
                $costo          = $v['costo'];
                $importe        = $v['importe'];
                //$existencia     = $v['existencia'];
                $traspasoaux    = $v['traspasoaux'];
                $tipo_traspaso  = $v['tipo_traspaso'];
                $costeo         = $v['costeo'];
                $caract = '';

                //new AGREGA CAMPO CARACTERISTICA
                foreach ($arrCaractR as $value) {
                    $idCar      = $value['id'];
                    $idCar      = $idCar*1;
                    $caractR    = $value['caractR'];
                    if($idCar == $v['id']){
                        $caract = "(".$caractR.")";
                        break;
                    }
                }

                ///// EXCISTENCIA POR PRODUCTO EN TODOS LOS ALMACENES
                if($codigo != $codigoAnt){
                    $existencia = 0;
                }
                if($traspasoaux == 0){//salida
                    $existencia = $existencia - $cantidad;
                }
                if($traspasoaux == 1){//entrada
                    $existencia = $existencia + $cantidad;
                    }
                ///// EXCISTENCIA POR PRODUCTO EN TODOS LOS ALMACENES FIN

                    // 1 -> PROMEDIO
                if($costeo == 1 and $costeo != 6){       
                    if($codigo != $codigoAnt){
                        $costoEntrada = $costoSalida = $prom = $neto = $saldo = $promedio = $existenciaFF = 0;
                    }
                    if($traspasoaux == 1 and $tipo_traspaso == 1){
                        $costoEntrada    = $costo;
                        $costoSalida     = 0;
                        $costoTotalEntrada = $costoEntrada * $cantidad;
                        $neto += $costoTotalEntrada;
                        $promedio = $neto / $existencia;
                        $entradaFF = $cantidad * $costoEntrada;
                        $salidaFF = 0;
                        $existenciaFF += $cantidad * $costoEntrada;
                        $cantidadI = $entradaFF;
                    }                
                    if($traspasoaux == 0 and $tipo_traspaso == 0){
                        $costoEntrada    = 0;
                        $costoSalida     = $promedio;
                        $costoTotalSalida = $costoSalida * $cantidad;
                        $neto -= $costoTotalSalida;
                        $promedio = $promedio;
                        $salidaFF = $cantidad * $promedio;
                        $entradaFF = 0;
                        $existenciaFF -= $cantidad * $promedio;
                        $cantidadI = $salidaFF;
                    }
                    ///////////////////  EN EL CASO DE LOS TRASPASO SE CONSIDERA EL ULTIMO PROMEDIO ENTRE MOVIMIENTOS
                    if($tipo_traspaso == 2){  
                        if($tipo_traspasoaux == 0){
                            $costoEntrada    = 0;
                            $costoSalida     = $promedio;
                            $costoTotalSalida = $costoSalida * $cantidad;
                            $neto -= $costoTotalSalida;
                            $promedio = $promedio;
                            $salidaFF = $cantidad * $promedio;
                            $entradaFF = 0;
                            $existenciaFF -= $cantidad * $promedio;
                            $cantidadI = $salidaFF;
                        }
                        if($tipo_traspasoaux == 1){
                            $costoSalida    = 0;
                            $costoEntrada     = $promedio;
                            $costoTotalEntrada = $costoEntrada * $cantidad;
                            $neto += $costoTotalEntrada;
                            $promedio = $promedio;
                            $entradaFF = $cantidad * $promedio;
                            $salidaFF = 0;
                            $existenciaFF += $cantidad * $promedio;
                            $cantidadI = $entradaFF;
                        }   
                    }

                }
                // 1 -> PROMEDIO FIN
                // 1 -> ESPESIFICO
                if($costeo == 6){
                    $cantidadI = $importe;
                } 
                // 1 -> ESPESIFICO FIN
                                
                $arrMICost[] = array(
                    id               => $v['id'],
                    nombre           => $v['nombre'],
                    codigo           => $codigo,
                    cantidad         => $v['cantidad'],
                    cantidadI        => $cantidadI,
                    costo            => $v['costo'],
                    importe          => $v['importe'],
                    fecha            => $v['fecha'],
                    id_producto      => $v['id_producto'],
                    traspasoaux      => $traspasoaux,
                    tipo_traspaso    => $tipo_traspaso,
                    unidad           => $v['unidad'],
                    moneda           => $v['moneda'],
                    nombreAlmacen    => $v['nombreAlmacen'],
                    almacenRR        => $v['almacenRR'],
                    codigo_sistema   => $v['codigo_sistema'],
                    almacenUbicacion => $v['almacenUbicacion'],
                    idubicacion      => $v['idubicacion'],
                    caract           => $caract,
                    costeo           => $costeo,
                    existencia       => $existencia,
                );
                $codigoAnt = $v['codigo'];
            }
            */
            //unset($inventarioActualU,$codigo,$cantidad,$costo,$importe,$existencia,$traspasoaux,$tipo_traspaso,$costeo,$arrCaractR);

            foreach($inventarioActualU as $val){ // ordenamiento
                $auxAl[] = $val['almacenRR'];
                $auxAU[] = $val['idubicacion'];
                $auxCo[] = $val['codigo'];
                $auxFe[] = $val['fecha'];
                $auxCa[] = $val['caract'];
            }


            $arrOrdF = $inventarioActualU;
            

            array_multisort($auxAl, SORT_ASC, $auxCo, SORT_ASC, $auxFe, SORT_ASC, $arrOrdF);
            unset($inventarioActualU,$auxAl,$auxAU,$auxCo,$auxFe,$auxCa);

            //// RECORRER EL ARRA PARA SUMAR CANTIDADES E IMPORTES FINALES PARA EXSITENCIAS
                $existencia = $existenImpore = 0;

                foreach ($arrOrdF as $ke => $va) {
                    $codigo         = $va['codigo'];
                    $cantidad       = $va['cantidad'];
                    $cantidadI      = $va['importe'];
                    $traspasoaux    = $va['traspasoaux'];
                    $caract = '';

                                    //new AGREGA CAMPO CARACTERISTICA
                                    foreach ($arrCaractR as $value) {
                                        $idCar      = $value['id'];
                                        $idCar      = $idCar*1;
                                        $caractR    = $value['caractR'];
                                        if($idCar == $va['id']){
                                            $caract = "(".$caractR.")";
                                            break;
                                        }
                                    }


                                    ///// EXCISTENCIA POR PRODUCTO EN TODOS LOS ALMACENES
                                        if($codigo != $codigoAnt){
                                            $existencia = 0;
                                            $existenImpore = 0;
                                        }
                                        if($traspasoaux == 0){//salida
                                            $existencia = $existencia - $cantidad;
                                            $existenImpore = $existenImpore - $cantidadI;
                                        }
                                        if($traspasoaux == 1){//entrada
                                            $existencia = $existencia + $cantidad;
                                            $existenImpore = $existenImpore + $cantidadI;
                                        }
                                    ///// EXCISTENCIA POR PRODUCTO EN TODOS LOS ALMACENES FIN
                        $arrOrdF2[] = array(
                            id               => $va['id'],
                            nombre           => $va['nombre'],
                            codigo           => $codigo,
                            cantidad         => $va['cantidad'],
                            cantidadI        => $cantidadI,
                            costo            => $va['costo'],
                            importe          => $va['importe'],
                            fecha            => $va['fecha'],
                            id_producto      => $va['id_producto'],
                            traspasoaux      => $traspasoaux,
                            tipo_traspaso    => $va['tipo_traspaso'],
                            unidad           => $va['unidad'],
                            moneda           => $va['moneda'],
                            nombreAlmacen    => $va['nombreAlmacen'],
                            almacenRR        => $va['almacenRR'],
                            codigo_sistema   => $va['codigo_sistema'],
                            almacenUbicacion => $va['almacenUbicacion'],
                            idubicacion      => $va['idubicacion'],
                            caract           => $va['caract'],
                            costeo           => $va['costeo'],
                            existencia       => $existencia,
                            existenImpore    => $existenImpore,
                            //promedio         => $va['promedio'],
                        );
                    $codigoAnt         = $va['codigo'];
                }

                unset($arrOrdF);
            //////////// COSTEOS  FIN//////////////////
               
            foreach($arrOrdF2 as $vall){ // ordenamiento
                $auxAlmRR[] = $vall['almacenRR'];
                $auxCo123[] = $vall['codigo'];
                $auxAl123[] = $vall['idubicacion'];
                $auxCa123[] = $vall['caract'];
                $auxFe123[] = $vall['fecha'];
            }

            array_multisort($auxAlmRR, SORT_ASC, $auxCo123, SORT_ASC, $auxAl123, SORT_ASC, $auxCa123, SORT_DESC, $auxFe123, SORT_ASC, $arrOrdF2);
            $arrESTU2 = $arrOrdF2;
            unset($arrOrdF2,$auxAlmRR,$auxCo123,$auxAl123,$auxCa123,$auxFe123);

            $exisP = $exisU = $exisC = $exisAl = $totalE = $totalS = $exisPI = $exisUI = $exisCI = $exisAlI = $totalEI = $totalSI = 0;
            foreach ($arrESTU2 as $value) {
                                    
                $codigo             = $value['codigo']; 
                $cantidad           = $value['cantidad'];
                $cantidadI          = $value['cantidadI'];
                $fecha              = $value['fecha'];
                $traspasoaux        = $value['traspasoaux'];
                $almacenRR          = $value['almacenRR'];
                $idubicacion        = $value['idubicacion'];
                $caract             = $value['caract'];

                                
                    if($almacenRR != $almacenRRAnt){
                        $exisUPRR = $exisUPRRI = 0;
                    }

                    if($codigo != $codigoUAnt){
                        $exisUpro = $exisU = $exisUcar = $exisUPRR = $exisUproI = $exisUI = $exisUcarI = $exisUPRRI = 0;
                    }
                    if($idubicacion != $idubicacionAnt){
                        $exisU = $exisUcar = $exisUI = $exisUcarI = 0;
                    }
                    if($caract != $caractAnt){
                        $exisUcar = $exisUcarI = 0;
                    }
                    if($traspasoaux == 0){//salida
                        $exisUpro   = $exisUpro - $cantidad;
                        $exisU      = $exisU - $cantidad;
                        $exisUcar   = $exisUcar - $cantidad;
                        $exisUPRR   = $exisUPRR - $cantidad;

                        $exisAl     = $exisAl - $cantidad;
                        $totalS     = $totalS + $cantidad;

                        $exisUproI   = $exisUproI - $cantidadI;
                        $exisUI      = $exisUI - $cantidadI;
                        $exisUcarI   = $exisUcarI - $cantidadI;
                        $exisUPRRI   = $exisUPRRI - $cantidadI;

                        $exisAlI     = $exisAlI - $cantidadI;
                        $totalSI     = $totalSI + $cantidadI;
                    }
                    if($traspasoaux == 1){//entrada
                        $exisUpro   = $exisUpro + $cantidad;
                        $exisU      = $exisU + $cantidad;
                        $exisUcar   = $exisUcar + $cantidad;
                        $exisUPRR   = $exisUPRR + $cantidad;

                        $exisAl     = $exisAl + $cantidad;
                        $totalE     = $totalE + $cantidad; 

                        $exisUproI   = $exisUproI + $cantidadI;
                        $exisUI      = $exisUI + $cantidadI;
                        $exisUcarI   = $exisUcarI + $cantidadI;
                        $exisUPRRI   = $exisUPRRI + $cantidadI;

                        $exisAlI     = $exisAlI + $cantidadI;
                        $totalEI     = $totalEI + $cantidadI;                                
                    }

                    if($almacenRR == $almacenRRAnt){
                        $contRR++;
                    }else{
                        $contRR=1;
                    }
                    if($codigo == $codigoUAnt){
                        if($almacenRR == $almacenRRAnt){
                            $contP++;
                        }else{
                            $contP=1; // se podria omitir

                            $salR = $salRI = $entR = $entRI =0;
                        }
                    }else{
                        $contP=1;

                        $salR = $salRI = $entR = $entRI =0;
                    }
                    if($idubicacion == $idubicacionAnt){
                        $contU++;
                    }else{
                        $contU=1;
                    }
                    if($caract == $caractAnt){
                        $contC++;
                    }else{
                        $contC=1;
                    }  

                    if($fecha >= $desde and $fecha <= $hasta){
                        if($traspasoaux == 0){//salida
                            $salR = $salR + $cantidad;
                            $salRI = $salRI + $cantidadI;
                        }
                        if($traspasoaux == 1){//entrada
                            $entR = $entR + $cantidad;
                            $entRI = $entRI + $cantidadI;
                        }
                    }

                        $arrExs[] = array(
                            id                 => $value['id'],
                            nombre             => $value['nombre'],
                            fecha              => $fecha,
                            cantidad           => $cantidad,
                            codigo             => $codigo,
                            traspasoaux        => $traspasoaux,
                            unidad             => $unidad,
                            nombreAlmacen      => $value['nombreAlmacen'],
                            caract             => $caract,
                            exisUpro           => $exisUpro,
                            exisUcar           => $exisUcar,
                            exisUPRR           => $exisUPRR,
                            exisUPRRI          => $exisUPRRI,
                            exisU              => $exisU,
                            exisAl             => $exisAl,
                            contP              => $contP,
                            contU              => $contU,
                            contC              => $contC,
                            contRR             => $contRR,
                            almacenUbicacion   => $value['almacenUbicacion'],
                            almacenRR          => $almacenRR,
                            idubicacion        => $idubicacion,
                            totalE             => $totalE,
                            totalS             => $totalS,
                            codigo_sistema     => $value['codigo_sistema'],

                            cantidadI          => $cantidadI,
                            exisUproI          => $exisUproI,
                            exisUcarI          => $exisUcarI,
                            exisUI             => $exisUI,
                            exisAlI            => $exisAlI,

                            totalEI            => $totalEI,
                            totalSI            => $totalSI,

                            entR               => $entR,
                            entRI              => $entRI,
                            salR               => $salR,
                            salRI              => $salRI,

                            unidad             => $value['unidad'],
                            moneda             => $value['moneda'],
                        ); 


                $almacenRRAnt           = $value['almacenRR'];
                $idubicacionAnt         = $value['idubicacion'];
                $codigoUAnt             = $value['codigo'];
                $caractAnt              = $value['caract'];                            
            }
            unset($arrESTU2);

            $arrExsR = array_reverse($arrExs);
            unset($arrExs,$almacenRR,$idubicacion,$codigoU,$caract,$almacenRRAnt,$idubicacionAnt,$codigoUAnt,$caractAnt,$exisP,$exisU,$exisC,$exisAl,$totalE,$totalS,$exisPI,$exisUI,$exisCI,$exisAlI,$totalEI,$totalSI,$fecha,$cantidad,$codigo,$traspasoaux);

            foreach ($arrExsR as $k => $va) {
                                    
                $contP      = $va['contP'];
                $contU      = $va['contU'];
                $contC      = $va['contC'];
                $contRR     = $va['contRR'];
 
                if($contRR >= $contRRAnt){
                    $auxRR = 1;
                }else{        /// Carac
                    $auxRR = 0; 
                }
                if($contC >= $contCAnt){
                    $auxC = 1;
                }else{        /// Carac
                    $auxC = 0; 
                }
                if($contU >= $contUAnt){
                    $auxU = $auxC = 1;
                }else{        /// Ubica
                    $auxU = 0; 
                }
                if($contP >= $contPAnt){
                    $auxP = $auxU = $auxC = 1;

                }else{        /// Produ
                    if($auxRR == 1){
                        $auxP = 1; 
                    }else{ //// CUANDO SE HACE UN FILTRADO  QUE MUESTRA UN SOLO PRODUCTO EN DIFERENTES ALMACENES
                        $auxP = 0;   
                    }
                }
            
                $arrExsFR[] = array(
                    id                  => $va['id'],
                    nombre              => $va['nombre'],           
                    fecha               => $va['fecha'],                    
                    codigo              => $va['codigo'],
                    exisUpro            => $va['exisUpro'],
                    almacenRR           => $va['almacenRR'],
                    auxP                => $auxP,
                    exisUproI          => $va['exisUproI'],
                    unidad             => $va['unidad'],
                    moneda             => $va['moneda'],

                );

                $contRRAnt     = $va['contRR'];
                $countAnt      = $va['count'];
                $contPAnt      = $va['contP'];
                $contUAnt      = $va['contU'];
                $contCAnt      = $va['contC'];
            }
            unset($arrExsR);

            $arrExsF = array_reverse($arrExsFR);
            unset($arrExsFR,$contRR,$count,$contP,$contU,$contC,$contRRAnt,$countAnt,$contPAnt,$contUAnt,$contCAnt);

            foreach ($arrExsF as $k => $v) {  /// POR PRODUCTO -> ALMACEN GENERAL
                $auxP       = $v['auxP'];
                $almacenRR  = $v['almacenRR'];
                $exisUpro   = $v['exisUpro'];
                
                $exisUproF = number_format($exisUpro,2);
                if($auxP == 1){

                        if($almacenRR == $almacenSe){ /// TIPO: IN  -> MYSQL ... LOS ALMACENES SELECCCIONADOS
                            $arrExsFUP[] = array( // EXISTENCIAS FINAL UBICACION CARACTERISTICAS
                                            id                  => $v['id'],
                                            nombre              => $v['nombre'],           
                                            fecha               => $v['fecha'],
                                            codigo              => $v['codigo'],
                                            unidad              => $v['unidad'],
                                            exisUpro            => $v['exisUpro'],
                                            exisUproF           => $exisUproF,
                                            exisUproI           => $v['exisUproI'],
                                            unidad              => $v['unidad'],
                                            moneda              => $v['moneda'],
                                            );
                    }                
                }
            }
            unset($arrExsF);

            $arrExsR2 = array_reverse($arrExsFUP);
            //unset($arrExsFUP);

                            // GREFICAS PARA EL TOP 5 EN IMPORTE Y EN CANTIDAD
                            foreach($arrExsR2 as $val){ // ordenamiento
                                    $OrdImpor[] = $val['exisUproI'];
                                    $OrdUnid[]  = $val['exisUpro'];
                            }
                            $arratop = $arrExsR2;
                            array_multisort($OrdImpor, SORT_DESC, $arratop);

                            $arratopU = $arrExsR2;
                            array_multisort($OrdUnid, SORT_DESC, $arratopU);

                            $topI5 = 0;
                            foreach ($arratop as $val) {
                                    $productos           = $val['nombre'];
                                    $existenciaImporte   = $val['exisUproI'];
                                    $existenciaImporteF = number_format($existenciaImporte, 2, '.', '');

                                    if($topI5 <= 4){
                                        $topI5++;
                                                    $arrTopEI[] = array( // SE CREAR UN ARRAY CON LOS MAXIMOS CONTADORES AGRUPADOS
                                                        y      => $productos,
                                                        b      => $existenciaImporteF,
                                                    );
                                    }else{
                                        break;
                                    }
                            }
                            $topU5 = 0;
                            foreach ($arratopU as $val) {
                                    $productos           = $val['nombre'];
                                    $existenciaUnidades  = $val['exisUpro'];
                                    $existenciaUnidadesF = number_format($existenciaUnidades, 2, '.', '');

                                    if($topU5 <= 4){
                                        $topU5++;
                                                    $arrTopEU[] = array( // SE CREAR UN ARRAY CON LOS MAXIMOS CONTADORES AGRUPADOS
                                                        y     => $productos,
                                                        b     => $existenciaUnidadesF,
                                                    );
                                    }else{
                                        break;
                                    }
                            }
                            // GREFICAS PARA EL TOP 5 EN IMPORTE Y EN CANTIDAD FIN

            if($All == 1){
                $multArraiA = array('exis' => $arrExsFUP, 'graficaI' => $arrTopEI, 'graficaU' => $arrTopEU);    
            }
            if($All == 0){
                $multArraiA = array('graficaI' => $arrTopEI, 'graficaU' => $arrTopEU);
            }
            
            echo json_encode($multArraiA); 
            unset($multArraiA,$auxP,$almacenRR,$exisUpro);    
    }
////////   NEW EXISTENCIAS FIN ///////////////

////////   NEW KARDEX ///////////////
    function kardex(){
        require('views/reportes/kardex.php'); 
    }
    function listUbicCaractMov(){

            $almacenSe  = $_POST['almacen'];
            $producto   = $_POST['producto'];
            $desde      = $_POST['desde'];
            $hasta      = $_POST['hasta'];
            $tipo       = $_POST['tipo'];
            $idSucursal = $_POST['sucursal'];
            $almacenNombreSelect = $_POST['almacenSlct'];

            //// EXISTENCIA ///////////////////
            
                $desde1      = $_POST['desde'];
                $hasta1      = $_POST['hasta'];
                if($desde1 == ''){ $desde1 = '1900-01-01'; }
                if($hasta1 == ''){ $hasta1 = '2900-01-01'; }

                $inventarioActualUE  = $this->ReportesModel->ubicCaractMov($desde1,$hasta1,'exis',$producto); // totdos los mov incluyendo trasp

                $arrCaractR = $this->caract($desde1,$hasta1);
                $caract = '';
                $costoEntrada = $costoSalida = $prom = $neto = $saldo = $promedio = $existenciaFF = $existencia = 0;

                foreach($inventarioActualUE as $val){ // ordenamiento
                    $auxAlE[] = $val['almacenRR'];
                    $auxAUE[] = $val['idubicacion'];
                    $auxCoE[] = $val['codigo'];
                    $auxFeE[] = $val['fecha'];
                    $auxCaE[] = $val['caract'];
                }

                $arrOrdFE = $inventarioActualUE;
                unset($inventarioActualUE);
                array_multisort($auxAlE, SORT_ASC, $auxCoE, SORT_ASC, $auxFeE, SORT_ASC, $arrOrdFE);

                $existencia = 0;
                $existenImpore = 0;
                $contA = 0;

                foreach ($arrOrdFE as $va) {
                        $codigo         = $va['codigo'];
                        $cantidad       = $va['cantidad'];
                        $cantidadI      = $va['importe'];
                        $traspasoaux    = $va['traspasoaux'];
                        $caract = '';

                        $contA++;

                        if($va['almacenRR'] != $almacenRRAnt){
                            $existencia = $existenImpore = 0;
                            $contA = 1;
                        }

                         //new AGREGA CAMPO CARACTERISTICA
                        foreach ($arrCaractR as $value) {
                            $idCar      = $value['id'];
                            $idCar      = $idCar*1;
                            $caractR    = $value['caractR'];
                            if($idCar == $v['id']){
                                $caract = "(".$caractR.")";
                                break;
                            }
                        }

                        ///// EXCISTENCIA POR PRODUCTO EN TODOS LOS ALMACENES
                            if($codigo != $codigoAnt){
                                $existencia = $existenImpore = 0;
                                $contA = 1;
                            }
                            if($traspasoaux == 0){//salida
                                $existencia = $existencia - $cantidad;
                                $existenImpore = $existenImpore - $cantidadI;
                            }
                            if($traspasoaux == 1){//entrada
                                $existencia = $existencia + $cantidad;
                                $existenImpore = $existenImpore + $cantidadI;
                            }
                        ///// EXCISTENCIA POR PRODUCTO EN TODOS LOS ALMACENES FIN
                            $arrOrdF2E[] = array(                            
                                codigo           => $codigo,
                                almacenRR        => $va['almacenRR'],
                                existencia       => $existencia,
                                existenImpore    => $existenImpore, 
                                contA            => $contA,                            
                            );
                        $codigoAnt         = $va['codigo'];
                        $almacenRRAnt      = $va['almacenRR'];
                    }

                    $arrOrdF2RE = array_reverse($arrOrdF2E);
                    foreach ($arrOrdF2RE as $key => $val) {
                         $contA  = $val['contA']; 
                         if($contA >= $contAAnt){
                            $arrOrdF3E[] = array(
                                    codigo           => $val['codigo'],
                                    almacenRR        => $val['almacenRR'],
                                    existencia       => $val['existencia'],
                                    existenImpore    => $val['existenImpore'],                            
                                );
                         }

                         $contAAnt  = $val['contA'];  
                    }
                    $arraExisE = array_reverse($arrOrdF3E);
                //echo json_encode($arraExisE);
                //exit();
            
            /////////////////// EXISTENCIA FIN
            

            $inventarioActualU  = $this->ReportesModel->ubicCaractMov($desde,$hasta,$tipo,$producto); // totdos los mov incluyendo trasp

            $desde = ($desde == '') ? '1900-01-01' : $desde.' 00:00:01';
            $hasta = ($hasta == '') ? '2900-01-01' : $hasta.' 23:59:59';
            
            /// FUNCTION PARA LAS CARACTERISTICAS
            $arrCaractR = $this->caract($desde,$hasta);

            $caract = '';
            $costoEntrada = $costoSalida = $prom = $neto = $saldo = $promedio = $existenciaFF = $existencia = 0;

            foreach($inventarioActualU as $val){ // ordenamiento
                $auxAl[] = $val['almacenRR'];
                $auxAU[] = $val['idubicacion'];
                $auxCo[] = $val['codigo'];
                $auxFe[] = $val['fecha'];
                $auxCa[] = $val['caract'];
            }
            $arrOrdF = $inventarioActualU;
            unset($inventarioActualU);
            array_multisort($auxAl, SORT_ASC, $auxCo, SORT_ASC, $auxFe, SORT_ASC, $arrOrdF);

            //// RECORRER EL ARRA PARA SUMAR CANTIDADES E IMPORTES FINALES PARA EXSITENCIAS
            $existencia = 0;
            $existenImpore = 0;
            foreach ($arrOrdF as $va) { /// Recorre el array principal para añadir la existencia en unidades e importe
                $codigo         = $va['codigo'];
                $cantidad       = $va['cantidad'];
                $cantidadI      = $va['importe'];
                $traspasoaux    = $va['traspasoaux'];
                $caract = '';

                //new AGREGA CAMPO CARACTERISTICA
                foreach ($arrCaractR as $value) {
                    $idCar      = $value['id'];
                    $idCar      = $idCar*1;
                    $caractR    = $value['caractR'];
                    if($idCar == $va['id']){
                        $caract = "(".$caractR.")";
                        break;
                    }
                }

                ///// EXCISTENCIA POR PRODUCTO EN TODOS LOS ALMACENES
                if($codigo != $codigoAnt){
                    $existencia = $existenImpore = 0;
                }
                if($traspasoaux == 0){//salida
                    $existencia = $existencia - $cantidad;
                    $existenImpore = $existenImpore - $cantidadI;
                }
                if($traspasoaux == 1){//entrada
                    $existencia = $existencia + $cantidad;
                    $existenImpore = $existenImpore + $cantidadI;
                }

                $arrOrdF2[] = array(
                    id               => $va['id'],
                    nombre           => $va['nombre'],
                    codigo           => $codigo,
                    cantidad         => $va['cantidad'],
                    cantidadI        => $cantidadI,
                    costo            => $va['costo'],
                    importe          => $va['importe'],
                    fecha            => $va['fecha'],
                    id_producto      => $va['id_producto'],
                    traspasoaux      => $traspasoaux,
                    tipo_traspaso    => $va['tipo_traspaso'],
                    nombreAlmacen    => $va['nombreAlmacen'],
                    almacenRR        => $va['almacenRR'],
                    codigo_sistema   => $va['codigo_sistema'],
                    almacenUbicacion => $va['almacenUbicacion'],
                    idubicacion      => $va['idubicacion'],
                    caract           => $caract,
                    costeo           => $va['costeo'],
                    existencia       => $existencia,
                    existenImpore    => $existenImpore,
                    id_sucursal      => $va['id_sucursal'],
                );
                $codigoAnt         = $va['codigo'];
            }
            unset($arrOrdF);
            //////////// COSTEOS  FIN//////////////////
               

            foreach($arrOrdF2 as $vall){ // ordenamiento
                $auxCo123[] = $vall['codigo'];
                $auxAl123[] = $vall['idubicacion'];
                $auxCa123[] = $vall['caract'];
                $auxFe123[] = $vall['fecha'];
            }
            array_multisort($auxCo123, SORT_ASC, $auxAl123, SORT_ASC, $auxCa123, SORT_DESC, $auxFe123, SORT_ASC, $arrOrdF2);
            $arrESTU2 = $arrOrdF2;
            unset($arrOrdF2);


            foreach ($arrESTU2 as $ve) { /// array para hacer algun filtrado
                $almacenRR   = $ve['almacenRR'];
                $id_sucursal   = $ve['id_sucursal'];
                $fechaF     = $ve['fecha'];
                 
                //if($almacenRR == $almacenSe){
                if(($fechaF >= $desde && $fechaF <= $hasta) && ($almacenSe == $almacenRR)){
                    $arreglado[] = array(
                        id                 => $ve['id'],
                        nombre             => $ve['nombre'],
                        codigo             => $ve['codigo'], 
                        cantidad           => $ve['cantidad'],
                        cantidadI          => $ve['cantidadI'],
                        costo              => $ve['costo'],                       
                        importe            => $ve['importe'],
                        fecha              => $ve['fecha'],
                        id_producto        => $ve['id_producto'],
                        costeo             => $ve['costeo'],
                        traspasoaux        => $ve['traspasoaux'],
                        tipo_traspaso      => $ve['tipo_traspaso'],
                        unidad             => $ve['unidad'],
                        moneda             => $ve['moneda'],
                        nombreAlmacen      => $ve['nombreAlmacen'],
                        almacenRR          => $ve['almacenRR'],
                        codigo_sistema     => $ve['codigo_sistema'],
                        almacenUbicacion   => $ve['almacenUbicacion'],
                        idubicacion        => $ve['idubicacion'],
                        caract             => $ve['caract'],
                        id_sucursal        => $ve['id_sucursal'],
                    );
                }  
                 
            }
            
            //unset($arrESTU2);

            // Inicializando existencias
            $exisP = $exisU = $exisC = $exisAl = $totalE = $totalS = $exisPI = $exisUI = $exisCI = $exisAlI = $totalEI = $totalSI = 0;
            foreach ($arreglado as $value) { // array para añadir existencias por producto, ubicacion y caracteristica
                
                $almacenRR          = $value['almacenRR'];                    
                if($almacenRR == $almacenSe){

                    //$id                 = $value['id'];
                    //$nombre             = $value['nombre'];
                    $codigo             = $value['codigo']; 
                    $cantidad           = $value['cantidad'];
                    $cantidadI          = $value['cantidadI'];
                    //$costo              = $value['costo'];                       
                    //$importe            = $value['importe'];
                    //$fecha              = $value['fecha'];
                    //$id_producto        = $value['id_producto'];
                    //$costeo             = $value['costeo'];
                    $traspasoaux        = $value['traspasoaux'];
                    //$unidad             = $value['unidad'];
                    //$moneda             = $value['moneda'];
                    //$nombreAlmacen      = $value['nombreAlmacen'];
                    $almacenRR          = $value['almacenRR'];
                    //$codigo_sistema     = $value['codigo_sistema'];
                    //$almacenUbicacion   = $value['almacenUbicacion'];
                    $idubicacion        = $value['idubicacion'];
                    $caract             = $value['caract'];
                    //$id_sucursal        = $value['id_sucursal'];

                                    
                    if($almacenRR != $almacenRRAnt){
                        $exisUPRR = $exisUPRRI =0;
                    }
                    if($codigo != $codigoUAnt){
                        $exisUpro = $exisU = $exisUcar = $exisUPRR = $exisUproI = $exisUI = $exisUcarI = $exisUPRRI = 0;
                    }
                    if($idubicacion != $idubicacionAnt){
                        $exisU = $exisUcar = $exisUI = $exisUcarI = 0;
                    }
                    if($caract != $caractAnt){
                        $exisUcar = $exisUcarI  = 0;
                    }
                    if($traspasoaux == 0){//salida
                        $exisUpro   = $exisUpro - $cantidad;
                        $exisU      = $exisU - $cantidad;
                        $exisUcar   = $exisUcar - $cantidad;
                        $exisUPRR   = $exisUPRR - $cantidad;

                        $exisAl     = $exisAl - $cantidad;
                        $totalS     = $totalS + $cantidad;

                        $exisUproI   = $exisUproI - $cantidadI;
                        $exisUI      = $exisUI - $cantidadI;
                        $exisUcarI   = $exisUcarI - $cantidadI;
                        $exisUPRRI   = $exisUPRRI - $cantidadI;

                        $exisAlI     = $exisAlI - $cantidadI;
                        $totalSI     = $totalSI + $cantidadI;
                    }
                    if($traspasoaux == 1){//entrada
                        $exisUpro   = $exisUpro + $cantidad;
                        $exisU      = $exisU + $cantidad;
                        $exisUcar   = $exisUcar + $cantidad;
                        $exisUPRR   = $exisUPRR + $cantidad;

                        $exisAl     = $exisAl + $cantidad;
                        $totalE     = $totalE + $cantidad; 

                        $exisUproI   = $exisUproI + $cantidadI;
                        $exisUI      = $exisUI + $cantidadI;
                        $exisUcarI   = $exisUcarI + $cantidadI;
                        $exisUPRRI   = $exisUPRRI + $cantidadI;

                        $exisAlI     = $exisAlI + $cantidadI;
                        $totalEI     = $totalEI + $cantidadI;                                
                    }

                    if($almacenRR == $almacenRRAnt){
                        $contRR++;
                    }else{
                        $contRR=1;
                    }
                    if($codigo == $codigoUAnt){
                        $contP++;
                    }else{
                        $contP=1;
                    }
                    if($idubicacion == $idubicacionAnt){
                        $contU++;
                    }else{
                        $contU=1;
                    }
                    if($caract == $caractAnt){
                        $contC++;
                    }else{
                        $contC=1;
                    }              
                    $arrExs[] = array(
                        id                 => $value['id'],
                        nombre             => $value['nombre'],
                        fecha              => $value['fecha'],
                        tipo_traspaso      => $value['tipo_traspaso'],
                        cantidad           => $cantidad,
                        codigo             => $codigo,
                        traspasoaux        => $traspasoaux,
                        //unidad             => $value['unidad'],
                        //nombreAlmacen      => $value['nombreAlmacen'],
                        caract             => $caract,
                        exisUpro           => $exisUpro,
                        exisUcar           => $exisUcar,
                        exisUPRR           => $exisUPRR,
                        exisUPRRI          => $exisUPRRI,
                        exisU              => $exisU,
                        //exisAl             => $exisAl,
                        contP              => $contP,
                        contU              => $contU,
                        contC              => $contC,
                        //contRR             => $contRR,
                        almacenUbicacion   => $value['almacenUbicacion'],
                        almacenRR          => $almacenRR,
                        //idubicacion        => $idubicacion,
                        //totalE             => $totalE,
                        //totalS             => $totalS,
                        //codigo_sistema     => $value['codigo_sistema'],

                        cantidadI          => $cantidadI,
                        exisUproI          => $exisUproI,
                        exisUcarI          => $exisUcarI,
                        exisUI             => $exisUI,
                        exisAlI            => $exisAlI,

                        //totalEI            => $totalEI,
                        //totalSI            => $totalSI,
                        id_sucursal        => $value['id_sucursal'],
                    ); 

                    $almacenRRAnt           = $value['almacenRR'];
                    $idubicacionAnt         = $value['idubicacion'];
                    $codigoUAnt             = $value['codigo'];
                    $caractAnt              = $value['caract']; 
                }// fin if almacen select                               
            }
            //unset($arreglado);
            unset($almacenRR,$idubicacion,$codigoU,$caract,$almacenRRAnt,$idubicacionAnt,$codigoUAnt,$caractAnt,$arrESTU2,$exisP,$exisU,$exisC,$exisAl,$totalE,$totalS,$exisPI,$exisUI,$exisCI,$exisAlI,$totalEI,$totalSI,$exisUpro,$exisUcar,$exisUPRR,$exisUproI,$exisUcarI,$exisUPRRI);


            $arrExsR = array_reverse($arrExs);
            unset($arrExs);


            foreach ($arrExsR as $va) { /// array para añadir axuiliares al cambiar de almacen, producto, ubicacion y caracteristica
                                    
                $contP      = $va['contP'];
                $contU      = $va['contU'];
                $contC      = $va['contC'];
                $contRR     = $va['contRR'];

                /*
                if($contRR >= $contRRAnt){
                    $auxRR = 1;
                }else{        /// Carac
                    $auxRR = 0; 
                }
                */
                if($contC >= $contCAnt){
                    $auxC = 1;
                }else{        /// Carac
                    $auxC = 0; 
                }
                if($contU >= $contUAnt){
                    $auxU = $auxC = 1;
                }else{        /// Ubica
                    $auxU = 0; 
                }
                if($contP >= $contPAnt){
                    $auxP = $auxU = $auxC = 1;
                }else{        /// Produ
                    $auxP = 0; 
                }
                $arrExsFR[] = array(
                    id                  => $va['id'],
                    nombre              => $va['nombre'],           
                    fecha               => $va['fecha'],
                    cantidad            => $va['cantidad'],
                    codigo              => $va['codigo'],
                    traspasoaux         => $va['traspasoaux'],
                    tipo_traspaso       => $va['tipo_traspaso'],
                    //unidad              => $va['unidad'],
                    caract              => $va['caract'],
                    exisUpro            => $va['exisUpro'],
                    exisUcar            => $va['exisUcar'],
                    exisUPRR            => $va['exisUPRR'],
                    exisUPRRI           => $va['exisUPRRI'],
                    exisU               => $va['exisU'],
                    //exisAl              => $va['exisAl'],
                    //contP               => $va['contP'],
                    //contU               => $va['contU'],
                    //contC               => $va['contC'],
                    almacenUbicacion    => $va['almacenUbicacion'],
                    almacenRR           => $va['almacenRR'],
                    //idubicacion         => $va['idubicacion'],
                    auxP                => $auxP,
                    auxU                => $auxU,
                    auxC                => $auxC,
                    //auxRR               => $auxRR,
                    //totalE              => $va['totalE'],
                    //totalS              => $va['totalS'],
                    //codigo_sistema      => $va['codigo_sistema'],

                    cantidadI          => $va['cantidadI'],
                    exisUproI          => $va['exisUproI'],
                    exisUcarI          => $va['exisUcarI'],
                    exisUI             => $va['exisUI'],
                    //exisAlI            => $va['exisAlI'],
                    //totalEI            => $va['totalEI'],
                    //totalSI            => $va['totalSI'],
                    id_sucursal        => $va['id_sucursal'],

                );

                $contRRAnt     = $va['contRR'];
                $countAnt      = $va['count'];
                $contPAnt      = $va['contP'];
                $contUAnt      = $va['contU'];
                $contCAnt      = $va['contC'];
            }
            unset($arrExsR);            

            $arrExsF = array_reverse($arrExsFR);
            unset($arrExsFR);

        /*
            foreach ($arrExsF as $k => $v) {  /// POR PRODUCTO -> ALMACEN GENERAL
                $auxP       = $v['auxP'];
                $almacenRR  = $v['almacenRR'];
                
                //format
                $exisUcar = number_format($v['exisUcar'],2);
                $exisUcarI = number_format($v['exisUcarI'],2);

                if($auxP == 1){
                    /// condicion para sucursal
                    
                    if(in_array("0", $almacenSe)){
                        $arrExsFUP[] = array( // EXISTENCIAS FINAL UBICACION CARACTERISTICAS
                                            id                  => $v['id'],
                                            nombre              => $v['nombre'],           
                                            fecha               => $v['fecha'],
                                            cantidad            => $v['cantidad'],
                                            codigo              => $v['codigo'],
                                            traspasoaux         => $v['traspasoaux'],
                                            tipo_traspaso       => $v['tipo_traspaso'],
                                            unidad              => $v['unidad'],
                                            caract              => $v['caract'],
                                            exisUpro            => $v['exisUpro'],
                                            exisUcar            => $exisUcar,
                                            exisUPRRI           => $v['exisUPRRI'],
                                            exisUPRR            => $v['exisUPRR'],
                                            exisU               => $v['exisU'],
                                            exisAl              => $v['exisAl'],
                                            contP               => $v['contP'],
                                            contU               => $v['contU'],
                                            contC               => $v['contC'],
                                            contRR              => $v['contRR'],
                                            almacenUbicacion    => $v['almacenUbicacion'],
                                            almacenRR           => $v['almacenRR'],
                                            nombreAlmacen       => $v['nombreAlmacen'],
                                            idubicacion         => $v['idubicacion'],
                                            auxCon              => $v['auxCon'],
                                            auxP                => $v['auxP'],
                                            auxU                => $v['auxU'],
                                            auxC                => $v['auxC'],
                                            auxRR               => $v['auxRR'],
                                            totalE              => $v['totalE'],
                                            totalS              => $v['totalS'],
                                            codigo_sistema      => $v['codigo_sistema'],

                                            cantidadI           => $v['cantidadI'],
                                            exisUproI           => $v['exisUproI'],
                                            exisUcarI           => '$'.$exisUcarI,
                                            exisUI              => $v['exisUI'],
                                            exisAlI             => $v['exisAlI'],
                                            totalEI             => $v['totalEI'],
                                            totalSI             => $v['totalSI'],

                                            entR               => $v['entR'],
                                            entRI              => $v['entRI'],
                                            salR               => $v['salR'],
                                            salRI              => $v['salRI'],
                                            costeo             => $v['costeo'],

                                            no_pedimento       => $v['no_pedimento'],
                                            no_aduana          => $v['no_aduana'],
                                            tipo_cambio        => $v['tipo_cambio'],
                                            no_lote            => $v['no_lote'],
                                            fecha_fabricacion  => $v['fecha_fabricacion'],
                                            fecha_caducidad    => $v['fecha_caducidad'],
                                            id_pedimento       => $v['id_pedimento'],
                                            id_lote            => $v['id_lote'],
                                            fecha_pedimento    => $v['fecha_pedimento'],
                                            idMove             => $v['idMove'],
                                            concepMove         => $v['concepMove'],
                                            razon_social       => $v['razon_social'],
                                            nombretienda       => $v['nombretienda'],
                                        );
                    }else
                    {
                        if(in_array($almacenRR, $almacenSe)){ /// TIPO: IN  -> MYSQL ... LOS ALMACENES SELECCCIONADOS
                            $arrExsFUP[] = array( // EXISTENCIAS FINAL UBICACION CARACTERISTICAS
                                            id                  => $v['id'],
                                            nombre              => $v['nombre'],           
                                            fecha               => $v['fecha'],
                                            cantidad            => $v['cantidad'],
                                            codigo              => $v['codigo'],
                                            traspasoaux         => $v['traspasoaux'],
                                            tipo_traspaso       => $v['tipo_traspaso'],
                                            unidad              => $v['unidad'],
                                            caract              => $v['caract'],
                                            exisUpro            => $v['exisUpro'],
                                            exisUcar            => $exisUcar,
                                            exisUPRRI           => $v['exisUPRRI'],
                                            exisUPRR            => $v['exisUPRR'],
                                            exisU               => $v['exisU'],
                                            exisAl              => $v['exisAl'],
                                            contP               => $v['contP'],
                                            contU               => $v['contU'],
                                            contC               => $v['contC'],
                                            contRR              => $v['contRR'],
                                            almacenUbicacion    => $v['almacenUbicacion'],
                                            almacenRR           => $v['almacenRR'],
                                            nombreAlmacen       => $v['nombreAlmacen'],
                                            idubicacion         => $v['idubicacion'],
                                            auxCon              => $v['auxCon'],
                                            auxP                => $v['auxP'],
                                            auxU                => $v['auxU'],
                                            auxC                => $v['auxC'],
                                            auxRR               => $v['auxRR'],
                                            totalE              => $v['totalE'],
                                            totalS              => $v['totalS'],
                                            codigo_sistema      => $v['codigo_sistema'],

                                            cantidadI           => $v['cantidadI'],
                                            exisUproI           => $v['exisUproI'],
                                            exisUcarI           => '$'.$exisUcarI,
                                            exisUI              => $v['exisUI'],
                                            exisAlI             => $v['exisAlI'],
                                            totalEI             => $v['totalEI'],
                                            totalSI             => $v['totalSI'],

                                            entR               => $v['entR'],
                                            entRI              => $v['entRI'],
                                            salR               => $v['salR'],
                                            salRI              => $v['salRI'],
                                            costeo             => $v['costeo'],

                                            no_pedimento       => $v['no_pedimento'],
                                            no_aduana          => $v['no_aduana'],
                                            tipo_cambio        => $v['tipo_cambio'],
                                            no_lote            => $v['no_lote'],
                                            fecha_fabricacion  => $v['fecha_fabricacion'],
                                            fecha_caducidad    => $v['fecha_caducidad'],
                                            id_pedimento       => $v['id_pedimento'],
                                            id_lote            => $v['id_lote'],
                                            fecha_pedimento    => $v['fecha_pedimento'],
                                            idMove             => $v['idMove'],
                                            concepMove         => $v['concepMove'],
                                            razon_social       => $v['razon_social'],
                                            nombretienda       => $v['nombretienda'],
                                        );
                        }
                    }  
                }
            }
            foreach ($arrExsF as $k => $v) {  /// POR UBICACION -> BODEGA, PASILLO, RACK
                $auxU      = $v['auxU'];
                
                //format
                $exisUcar = number_format($v['exisUcar'],2);

                if($auxU == 1){
                    $arrExsFUU[] = array( // EXISTENCIAS FINAL UBICACION CARACTERISTICAS
                                        id                  => $v['id'],
                                        nombre              => $v['nombre'],           
                                        fecha               => $v['fecha'],
                                        cantidad            => $v['cantidad'],
                                        codigo              => $v['codigo'],
                                        traspasoaux         => $v['traspasoaux'],
                                        unidad              => $v['unidad'],
                                        caract              => $v['caract'],
                                        exisUpro            => $v['exisUpro'],
                                        exisUcar            => $exisUcar,
                                        exisUPRRI           => $v['exisUPRRI'],
                                        exisUPRR            => $v['exisUPRR'],
                                        exisU               => $v['exisU'],
                                        exisAl              => $v['exisAl'],
                                        contP               => $v['contP'],
                                        contU               => $v['contU'],
                                        contC               => $v['contC'],
                                        contRR              => $v['contRR'],
                                        almacenUbicacion    => $v['almacenUbicacion'],
                                        almacenRR           => $v['almacenRR'],
                                        idubicacion         => $v['idubicacion'],
                                        auxCon              => $v['auxCon'],
                                        auxP                => $v['auxP'],
                                        auxU                => $v['auxU'],
                                        auxC                => $v['auxC'],
                                        auxRR               => $v['auxRR'],
                                        totalE              => $v['totalE'],
                                        totalS              => $v['totalS'],
                                        codigo_sistema      => $v['codigo_sistema'],

                                        cantidadI           => $v['cantidadI'],
                                        exisUproI           => $v['exisUproI'],
                                        exisUcarI           => '$'.$exisUcarI,
                                        exisUI              => $v['exisUI'],
                                        exisAlI             => $v['exisAlI'],
                                        totalEI             => $v['totalEI'],
                                        totalSI             => $v['totalSI'],
                                        );
                }
            }
            foreach ($arrExsF as $k => $v) { /// POR CARACTERISTICA -> CARACTERISTICA, SIN CARACTERISTICA
                $auxC      = $v['auxC'];
                
                //format
                $exisUcar = number_format($v['exisUcar'],2);

                if($auxC == 1){
                    $arrExsFUC[] = array( // EXISTENCIAS FINAL UBICACION CARACTERISTICAS
                                        id                  => $v['id'],
                                        nombre              => $v['nombre'],           
                                        fecha               => $v['fecha'],
                                        cantidad            => $v['cantidad'],
                                        codigo              => $v['codigo'],
                                        traspasoaux         => $v['traspasoaux'],
                                        unidad              => $v['unidad'],
                                        caract              => $v['caract'],
                                        exisUpro            => $v['exisUpro'],
                                        exisUcar            => $exisUcar,
                                        exisUPRRI           => $v['exisUPRRI'],
                                        exisUPRR            => $v['exisUPRR'],
                                        exisU               => $v['exisU'],
                                        exisAl              => $v['exisAl'],
                                        contP               => $v['contP'],
                                        contU               => $v['contU'],
                                        contC               => $v['contC'],
                                        contRR              => $v['contRR'],
                                        almacenUbicacion    => $v['almacenUbicacion'],
                                        almacenRR           => $v['almacenRR'],
                                        idubicacion         => $v['idubicacion'],
                                        auxCon              => $v['auxCon'],
                                        auxP                => $v['auxP'],
                                        auxU                => $v['auxU'],
                                        auxC                => $v['auxC'],
                                        auxRR               => $v['auxRR'],
                                        totalE              => $v['totalE'],
                                        totalS              => $v['totalS'],
                                        codigo_sistema      => $v['codigo_sistema'],

                                        cantidadI           => $v['cantidadI'],
                                        exisUproI           => $v['exisUproI'],
                                        exisUcarI           => '$'.$exisUcarI,
                                        exisUI              => $v['exisUI'],
                                        exisAlI             => $v['exisAlI'],
                                        totalEI             => $v['totalEI'],
                                        totalSI             => $v['totalSI'],
                                        );
                }
            }
        */

            ///// FILTRADO Y FORMATO ////////
            //echo json_encode($arrExsF);
            //exit();
            foreach ($arrExsF as $values) {
                $fechaF     = $values['fecha'];
                $almacenRR  = $values['almacenRR'];
                $id_sucursal = $values['id_sucursal'];
            
                //if(($fechaF >= $desde && $fechaF <= $hasta) && ($almacenSe == $almacenRR)){
                if($almacenSe == $almacenRR){
                                $arrKarFil[] = array(
                                    id                 => $values['id'],
                                    nombre             => $values['nombre'],
                                    fecha              => $values['fecha'],
                                    cantidad           => $values['cantidad'],
                                    codigo             => $values['codigo'],
                                    traspasoaux        => $values['traspasoaux'],
                                    tipo_traspaso      => $values['tipo_traspaso'],
                                    //unidad             => $values['unidad'],
                                    //nombreAlmacen      => $values['nombreAlmacen'],
                                    caract             => $values['caract'],
                                    exisUpro           => $values['exisUpro'],
                                    exisUcar           => $values['exisUcar'],
                                    exisUPRRI          => $values['exisUPRRI'],
                                    exisUPRR           => $values['exisUPRR'],
                                    exisU              => $values['exisU'],
                                    //exisAl             => $values['exisAl'],
                                    //contP              => $values['contP'],
                                    //contU              => $values['contU'],
                                    //contC              => $values['contC'],
                                    //contRR             => $values['contRR'],
                                    almacenUbicacion   => $values['almacenUbicacion'],
                                    almacenRR          => $values['almacenRR'],
                                    //idubicacion        => $values['idubicacion'],
                                    //totalE             => $values['totalE'],
                                    //totalS             => $values['totalS'],
                                    //codigo_sistema     => $values['codigo_sistema'],
                                    cantidadI          => $values['cantidadI'],
                                    exisUproI          => $values['exisUproI'],
                                    exisUcarI          => $values['exisUcarI'],
                                    exisUI             => $values['exisUI'],
                                    //exisAlI            => $values['exisAlI'],
                                    //totalEI            => $values['totalEI'],
                                    //totalSI            => $values['totalSI'],
                                    auxP               => $values['auxP'],
                                    auxU               => $values['auxU'],
                                    auxC               => $values['auxC'],
                                    //auxRR              => $values['auxRR'],
                                    //id_sucursal        => $values['id_sucursal'],
                                ); 
                    }                            
            }


    /// new 
    
            $tablau = '<table id="tableu" class="table table-striped table-bordered sizeprint" cellspacing="0" width="100%">'.
                            '<thead>'.
                            '<tr>'.
                            '<th width="80">Fecha</th>'.
                            '<th width="25">Folio</th>'.
                            '<th width="90">Concepto</th>'.
                            '<th width="40">Almacen</th>'.
                            '<th width="20">Entradas</th>'.
                            '<th width="20">Salidas</th>'.
                            '<th width="20">Existencia</th>'.
                          '</tr>'.
                        '</thead>';
            
            $tablai = '<table id="tableu" class="table table-striped table-bordered sizeprint" cellspacing="0" width="100%">'.
                            '<thead>'.
                            '<tr>'.
                            '<th width="80">Fecha</th>'.
                            '<th width="25">Folio</th>'.
                            '<th width="90">Concepto</th>'.
                            '<th width="40">Almacen</th>'.
                            '<th width="20">Entradas</th>'.
                            '<th width="20">Salidas</th>'.
                            '<th width="20">Existencia</th>'.
                          '</tr>'.
                        '</thead>';                        


            $exist = $totalE = $totalS = $entrada = $salida = $existI = $totalEI = $totalSI = $entradaI = $salidaI = $cont = 0;
            $movimiento = $entradaF = $salidaF = $entradaIF = $salidaIF = '';

            $codigoAnt = -111111;
            foreach ($arrKarFil as $v) { 
                $codigo = $v['codigo'];
                $almacenRR = $v['almacenRR'];

                if($codigo != $codigoAnt){
                        $exist = 0;
                        $existI = 0;
                }
                if($v['tipo_traspaso'] == 1 || $v['traspasoaux'] == 1){
                    if($v['tipo_traspaso'] == 2){
                        $movimiento = 'Entrada Almacen (Traspaso)';
                    }else{
                        $movimiento = 'Entrada Almacen';
                    }
                    $entrada     = $v['cantidad']*1;
                    $entradaF    = number_format($entrada,2);
                    $salidaF     = "";
                    $totalE = $totalE + $entrada;
                    $exist += $entrada; 

                    $entradaI     = $v['cantidadI']*1;
                    $entradaIF    = '$'.number_format($entradaI,2);
                    $salidaIF     = "";
                    $totalEI = $totalEI + $entradaI;
                    $existI += $entradaI;                        
                }
                if($v['tipo_traspaso'] == 0 || $v['traspasoaux'] == 0){
                    if($v['tipo_traspaso'] == 2){
                        $movimiento = 'Salida Almacen (Traspaso)';
                    }else{
                        $movimiento = 'Salida Almacen';
                    }
                    $salida = $v['cantidad']*1;
                    $salidaF = number_format($salida,2);
                    $entradaF="";
                    $totalS = $totalS + $salida;
                    $exist -= $salida; 

                    $salidaI = $v['cantidadI']*1;
                    $salidaIF = '$'.number_format($salidaI,2);
                    $entradaIF="";
                    $totalSI = $totalSI + $salidaI;
                    $existI -= $salidaI;                       
                }
                //EXIS 
                foreach ($arraExisE as $va) {
                    $codigoE = $va['codigo'];
                    $almacenRRE = $va['almacenRR'];

                    if($codigoE == $codigo and $almacenRR == $almacenRRE){
                        $ExisInicial = $va['existencia'];
                        $ExisInicialI = $va['existenImpore'];
                        $ExisInicialIF = '$'.number_format($ExisInicialI,2);
                        break; /// se sale del cicloe despues de asignar el valor
                    }else{
                        $ExisInicial = 0;
                        $ExisInicialI = 0;
                    }  
                }
                //EXIS FIN
                if($codigo != $codigoAnt){ // sub encabezado                    
                        $cont= 0;
                            $h ='<tr>'.
                                    '<td><b>Producto:</b></td>'.
                                    '<td><b>'.$codigo.'</b></td>'.
                                    '<td><b>'.$v['nombre'].'</b></td>'.
                                    '<td></td>'.
                                    '<td></td>'.
                                    '<td></td>'.
                                    '<td></td>'.
                                '</tr>';
                            $tablau .= $h;

                            $tablai .= $h;

                            $h2 ='<tr>'.
                                    '<td></td>'.
                                    '<td></td>'.
                                    '<td><b>Inventario Inicial</td></td>'.
                                    '<td><b>'.$almacenNombreSelect.'</td></td>'.
                                    '<td align="right" ><b>'.$ExisInicial.'</b></td>'.
                                    '<td></td>'.
                                    '<td></td>'.
                                    '</tr>'; 
                            $tablau .= $h2;

                            $h2i ='<tr>'.
                                    '<td></td>'.
                                    '<td></td>'.
                                    '<td><b>Inventario Inicial</td></td>'.
                                    '<td><b>'.$almacenNombreSelect.'</td></td>'.
                                    '<td align="right" ><b>'.$ExisInicialIF.'</b></td>'.
                                    '<td></td>'.
                                    '<td></td>'.
                                    '</tr>'; 
                            $tablai .= $h2i;

                            $exist += $ExisInicial; 
                            $existI += $ExisInicialI*1;                                  
                    }

                    $existTotF = number_format($exist,2);
                    $existTotIF = '$'.number_format($existI,2);

                    $x ='<tr>'.
                            '<td>'.$v['fecha'].'</td>'.
                            '<td align="center">'.$v['id'].'</td>'.
                            '<td align="center">'.$movimiento.' '.$v['caract'].'</td>'.
                            '<td align="center">'.$v['almacenUbicacion'].'</td>'.
                            '<td align="right">'.$entradaF.'</td>'.
                            '<td align="right">'.$salidaF.'</td>'.
                            '<td align="right">'.$existTotF.'</td>'.
                        '</tr>';  
                    $tablau .= $x;

                    $xI ='<tr>'.
                            '<td>'.$v['fecha'].'</td>'.
                            '<td align="center">'.$v['id'].'</td>'.
                            '<td align="center">'.$movimiento.' '.$v['caract'].'</td>'.
                            '<td align="center">'.$v['almacenUbicacion'].'</td>'.
                            '<td align="right">'.$entradaIF.'</td>'.
                            '<td align="right">'.$salidaIF.'</td>'.
                            '<td align="right">'.$existTotIF.'</td>'.
                        '</tr>';  
                    $tablai .= $xI;

                    if($v['auxC'] == 1){
                        $exisUcarF = number_format($v['exisUcar'],2);
                        $exisUcarIF = number_format($v['exisUcarI'],2);
                        
                        $x ='<tr>'.
                                '<td></td>'.
                                '<td><b>Total Caracteristica:</td>'.
                                '<td colspan="2"><b>'.$v['caract'].'</td>'.
                                '<td style="display: none;">'.
                                '<td></td>'.
                                '<td></td>'.
                                '<td align="right"><b><b>'.$exisUcarF.'</td>'.
                            '</tr>';
                            $tablau .= $x;  

                        $xI ='<tr>'.
                                '<td></td>'.
                                '<td><b>Total Caracteristica:</td>'.
                                '<td colspan="2"><b>'.$v['caract'].'</td>'.
                                '<td style="display: none;">'.
                                '<td></td>'.
                                '<td></td>'.
                                '<td align="right"><b><b>$'.$exisUcarIF.'</td>'.
                            '</tr>';
                            $tablai .= $xI;

                    }
                    if($v['auxU'] == 1){
                        $exisUF = number_format($v['exisU'],2);
                        $exisUIF = number_format($v['exisUI'],2);

                        $x ='<tr>'.
                                '<td></td>'.
                                '<td><b>Total Ubicacion:</td>'.
                                '<td colspan="2"><b>'.$v['almacenUbicacion'].'</td>'.
                                '<td style="display: none;">'.
                                '<td></td>'.
                                '<td></td>'.
                                '<td align="right"><b><b>'.$exisUF.'</td>'.
                            '</tr>';                        
                        $tablau .= $x;  

                        $xI ='<tr>'.
                                '<td></td>'.
                                '<td><b>Total Ubicacion:</td>'.
                                '<td colspan="2"><b>'.$v['almacenUbicacion'].'</td>'.
                                '<td style="display: none;">'.
                                '<td></td>'.
                                '<td></td>'.
                                '<td align="right"><b><b>$'.$exisUIF.'</td>'.
                            '</tr>';                        
                        $tablai .= $xI;

                    } 
                    if($v['auxP'] == 1){ 
                        $exisUproF = number_format($v['exisUpro'],2);
                        $exisUproIF = number_format($v['exisUproI'],2);
                        $x ='<tr>'.
                                '<td></td>'.
                                '<td><b>Total Producto</td>'.
                                '<td colspan="2"><b>'.$v['nombre'].'</td>'.
                                '<td style="display: none;">'.
                                '<td></td>'.
                                '<td></td>'.
                                '<td align="right"><b>'.$exisUproF.'</td>'.
                            '</tr>';  
                            $tablau .= $x;

                        $xI ='<tr>'.
                                '<td></td>'.
                                '<td><b>Total Producto</td>'.
                                '<td colspan="2"><b>'.$v['nombre'].'</td>'.
                                '<td style="display: none;">'.
                                '<td></td>'.
                                '<td></td>'.
                                '<td align="right"><b>$'.$exisUproIF.'</td>'.
                            '</tr>';  
                            $tablai .= $xI;
                        
                        $x ='<tr>'.
                                '<td>.</td>'.
                                '<td></td>'.
                                '<td></td>'.
                                '<td></td>'.
                                '<td></td>'.
                                '<td></td>'.
                                '<td></td>'.                               
                            '</tr>';  
                            $tablau .= $x;
                            $tablai .= $x;
                    }
                $codigoAnt = $v['codigo'];
            }

            $exis = 0;
            $exis =  $totalE - $totalS;                    
            $totalEF =number_format($totalE,2);
            $totalSF =number_format($totalS,2);
            $exisF =number_format($exis,2);

            $x =   '<tfoot>'.
                    '<tr>'.                    
                    '<th></th>'.
                    '<th></th>'.
                    '<th></th>'.
                    '<th>TOTAL GENERAL:</th>'.
                    '<th>'.$totalEF.'</th>'.
                    '<th>'.$totalSF.'</th>'.
                    '<th>'.$exisF.'</th>'.                               
                '</tr>'.
                '</tfoot>';   
            $tablau .= $x;

            $exisI = 0;
            $exisI =  $totalEI - $totalSI;                    
            $totalEIF =number_format($totalEI,2);
            $totalSIF =number_format($totalSI,2);
            $exisIF =number_format($exisI,2);

            $xI =   '<tfoot>'.
                    '<tr>'.                    
                    '<th></th>'.
                    '<th></th>'.
                    '<th></th>'.
                    '<th>TOTAL GENERAL:</th>'.
                    '<th>$'.$totalEIF.'</th>'.
                    '<th>$'.$totalSIF.'</th>'.
                    '<th>$'.$exisIF.'</th>'.                               
                '</tr>'.
                '</tfoot>';  
            $tablai .= $xI;
                    
            
            echo $tablau.' ª '.$tablai;
            //echo $tablai;
            exit();
             
    // new fin
            

            //$multArraiA = array('kardex' => $arrOrdF2, 'kardexF' => $arrKarFil, 'invAP' => $arrExsFUP, 'invAU' => $arrExsFUU, 'invAC' => $arrExsFUC);
            $multArraiA = array('kardexF' => $arrKarFil);
            unset($arrKarFil);
            echo json_encode($multArraiA);
            unset($multArraiA);
    }
    function listUbicCaractExis(){

            $producto   = $_POST['producto'];
            $almacen    = $_POST['almacen'];
            $desde      = $_POST['desde'];
            $hasta      = $_POST['hasta'];
            $tipo       = $_POST['tipo'];
            //$idSucursal = 17;

            if($desde == ''){
                $desde = '1900-01-01';
            }
            if($hasta == ''){
                $hasta = '2900-01-01';
            }

            $inventarioActualU  = $this->ReportesModel->ubicCaractMov($desde,$hasta,$tipo,$producto); // totdos los mov incluyendo trasp

            /// FUNCTION PARA LAS CARACTERISTICAS
            $arrCaractR = $this->caract($desde,$hasta);

            $caract = '';
            $costoEntrada = $costoSalida = $prom = $neto = $saldo = $promedio = $existenciaFF = $existencia = 0;

            /*
            foreach ($inventarioActualU as $v) {

                $codigo         = $v['codigo'];
                $cantidad       = $v['cantidad'];
                $costo          = $v['costo'];
                $importe        = $v['importe'];
                $traspasoaux    = $v['traspasoaux'];
                $tipo_traspaso  = $v['tipo_traspaso'];
                $costeo         = $v['costeo'];


                //new AGREGA CAMPO CARACTERISTICA
                foreach ($arrCaractR as $value) {
                    $idCar      = $value['id'];
                    $idCar      = $idCar*1;
                    $caractR    = $value['caractR'];
                    if($idCar == $v['id']){
                        $caract = "(".$caractR.")";
                        break;
                    }
                }

                ///// EXCISTENCIA POR PRODUCTO EN TODOS LOS ALMACENES
                    if($codigo != $codigoAnt){
                        $existencia = 0;
                    }
                    if($traspasoaux == 0){//salida
                        $existencia = $existencia - $cantidad;
                    }
                    if($traspasoaux == 1){//entrada
                        $existencia = $existencia + $cantidad;
                    }
                ///// EXCISTENCIA POR PRODUCTO EN TODOS LOS ALMACENES FIN

                // 1 -> PROMEDIO
                if($costeo == 1 and $costeo != 6){       
                    if($codigo != $codigoAnt){
                        $costoEntrada = $costoSalida = $prom = $neto = $saldo = $promedio = $existenciaFF = 0;
                    }                    
                    if($traspasoaux == 1 and $tipo_traspaso == 1){                    
                        $costoEntrada    = $costo;
                        $costoSalida     = 0;
                        $costoTotalEntrada = $costoEntrada * $cantidad;
                        $neto += $costoTotalEntrada;
                        $promedio = $neto / $existencia;
                        $entradaFF = $cantidad * $costoEntrada;
                        $salidaFF = 0;
                        $existenciaFF += $cantidad * $costoEntrada;
                        $cantidadI = $entradaFF;
                    }
                    if($traspasoaux == 0 and $tipo_traspaso == 0){
                        $costoEntrada    = 0;
                        $costoSalida     = $promedio;
                        $costoTotalSalida = $costoSalida * $cantidad;
                        $neto -= $costoTotalSalida;
                        $promedio = $promedio;
                        $salidaFF = $cantidad * $promedio;
                        $entradaFF = 0;
                        $existenciaFF -= $cantidad * $promedio;
                        $cantidadI = $salidaFF;
                    }
                    ///////////////////  EN EL CASO DE LOS TRASPASO SE CONSIDERA EL ULTIMO PROMEDIO ENTRE MOVIMIENTOS
                    if($tipo_traspaso == 2){  
                        if($tipo_traspasoaux == 0){
                            $costoEntrada    = 0;
                            $costoSalida     = $promedio;
                            $costoTotalSalida = $costoSalida * $cantidad;
                            $neto -= $costoTotalSalida;
                            $promedio = $promedio;
                            $salidaFF = $cantidad * $promedio;
                            $entradaFF = 0;
                            $existenciaFF -= $cantidad * $promedio;
                            $cantidadI = $salidaFF;
                        }
                        if($tipo_traspasoaux == 1){
                            $costoSalida    = 0;
                            $costoEntrada     = $promedio;
                            $costoTotalEntrada = $costoEntrada * $cantidad;
                            $neto += $costoTotalEntrada;
                            $promedio = $promedio;
                            $entradaFF = $cantidad * $promedio;
                            $salidaFF = 0;
                            $existenciaFF += $cantidad * $promedio;
                            $cantidadI = $entradaFF;
                        }   
                    }

                }
                // 1 -> PROMEDIO FIN
                // 1 -> ESPESIFICO
                if($costeo == 6){
                    $cantidadI = $importe;
                } 
                // 1 -> ESPESIFICO FIN
                 
                $arrMICost[] = array(
                    id               => $v['id'],
                    nombre           => $v['nombre'],
                    codigo           => $codigo,
                    cantidad         => $v['cantidad'],
                    cantidadI        => $cantidadI,
                    costo            => $v['costo'],
                    importe          => $v['importe'],
                    fecha            => $v['fecha'],
                    id_producto      => $v['id_producto'],
                    traspasoaux      => $traspasoaux,
                    tipo_traspaso    => $tipo_traspaso,
                    unidad           => $v['unidad'],
                    moneda           => $v['moneda'],
                    nombreAlmacen    => $v['nombreAlmacen'],
                    almacenRR        => $v['almacenRR'],
                    codigo_sistema   => $v['codigo_sistema'],
                    almacenUbicacion => $v['almacenUbicacion'],
                    idubicacion      => $v['idubicacion'],
                    caract           => $caract,
                    costeo           => $costeo,
                    existencia       => $existencia,
                    id_sucursal      => $v['id_sucursal'],

                );
                $codigoAnt = $v['codigo'];
            }
            unset($arrESTR);
            */
            foreach($inventarioActualU as $val){ // ordenamiento
                $auxAl[] = $val['almacenRR'];
                $auxAU[] = $val['idubicacion'];
                $auxCo[] = $val['codigo'];
                $auxFe[] = $val['fecha'];
                $auxCa[] = $val['caract'];
            }

            $arrOrdF = $inventarioActualU;
            unset($inventarioActualU);
            array_multisort($auxAl, SORT_ASC, $auxCo, SORT_ASC, $auxFe, SORT_ASC, $arrOrdF);

            //// RECORRER EL ARRA PARA SUMAR CANTIDADES E IMPORTES FINALES PARA EXSITENCIAS
                $existencia = 0;
                $existenImpore = 0;
                $contA = 0;

                foreach ($arrOrdF as $va) {
                    $codigo         = $va['codigo'];
                    $cantidad       = $va['cantidad'];
                    $cantidadI      = $va['importe'];
                    $traspasoaux    = $va['traspasoaux'];
                    $caract = '';

                    $contA++;

                    if($va['almacenRR'] != $almacenRRAnt){
                        $existencia = $existenImpore = 0;
                        $contA = 1;
                    }

                     //new AGREGA CAMPO CARACTERISTICA
                    foreach ($arrCaractR as $value) {
                        $idCar      = $value['id'];
                        $idCar      = $idCar*1;
                        $caractR    = $value['caractR'];
                        if($idCar == $v['id']){
                            $caract = "(".$caractR.")";
                            break;
                        }
                    }

                    ///// EXCISTENCIA POR PRODUCTO EN TODOS LOS ALMACENES
                        if($codigo != $codigoAnt){
                            $existencia = $existenImpore = 0;
                            $contA = 1;
                        }
                        if($traspasoaux == 0){//salida
                            $existencia = $existencia - $cantidad;
                            $existenImpore = $existenImpore - $cantidadI;
                        }
                        if($traspasoaux == 1){//entrada
                            $existencia = $existencia + $cantidad;
                            $existenImpore = $existenImpore + $cantidadI;
                        }
                    ///// EXCISTENCIA POR PRODUCTO EN TODOS LOS ALMACENES FIN
                        $arrOrdF2[] = array(                            
                            codigo           => $codigo,
                            almacenRR        => $va['almacenRR'],
                            existencia       => $existencia,
                            existenImpore    => $existenImpore, 
                            contA            => $contA,                            
                        );
                    $codigoAnt         = $va['codigo'];
                    $almacenRRAnt      = $va['almacenRR'];
                }
            //// RECORRER EL ARRA PARA SUMAR CANTIDADES E IMPORTES FINALES PARA EXSITENCIAS FIN
            $arrOrdF2R = array_reverse($arrOrdF2);
            foreach ($arrOrdF2R as $key => $val) {
                 $contA  = $val['contA']; 
                 if($contA >= $contAAnt){
                    $arrOrdF3[] = array(
                            codigo           => $val['codigo'],
                            almacenRR        => $val['almacenRR'],
                            existencia       => $val['existencia'],
                            existenImpore    => $val['existenImpore'],                            
                        );
                 }

                 $contAAnt  = $val['contA'];  
            }
            $arraExis = array_reverse($arrOrdF3);
            echo json_encode($arraExis);
            unset($arraExis);
            //////////// COSTEOS  FIN//////////////////

    }
////////   NEW KARDEX  FIN///////////////

////////   NEW INVENTARIO ACTUAL ///////////////
    function inventarioactual(){

         $unidades  = $this->ReportesModel->unidades(); // totdos los 

        require('views/reportes/inventarioactual.php'); 
    }
    function listInvActMov(){


            $tipoProIA  = $_POST['tipoProIA'];
            $almacenSe  = $_POST['almacen'];
            $producto   = $_POST['producto'];
            $desde      = $_POST['desde'];
            $hasta      = $_POST['hasta'];
            $tipo       = $_POST['tipo'];
            $tipo2      = $_POST['tipo2'];
            $rep        = $_POST['rep'];
            $unid       = $_POST['unid'];
            $unidades   = $_POST['unidades'];
            $provedor = $_POST['provedor'];
            $consigna = $_POST['consigna'];
            
            $tablaphp = '<table class="table table-striped table-bordered sizeprint" cellspacing="0" width="100%">'.
                            '<thead>'.
                            '<tr>'.
                            '<th width="90">Codigo de producto</th>'.
                            '<th>Nombre</th>'.
                            '<th width="30">Unidades</th>'.
                            '<th width="30">Inicial</th>'.
                            '<th width="30">Entradas</th>'.
                            '<th width="30">Salidas</th>'.
                            '<th width="30">Existencia Actual</th>'.
                          '</tr>'.
                        '</thead>';

            $inventarioActualU  = $this->ReportesModel->ubicCaractMov($desde,$hasta,$tipo,$producto,$tipoProIA,$unid,$unidades,$provedor,$consigna); // totdos los mov incluyendo trasp

            $desde = ($desde == '') ? '1900-01-01' : $desde.' 00:00:01';
            $hasta = ($hasta == '') ? '2900-01-01' : $hasta.' 23:59:59';

            /// FUNCTION PARA LAS CARACTERISTICAS
            $arrCaractR = $this->caract($desde,$hasta);

        /// INICIAL
            $inventarioActualUI  = $this->ReportesModel->ubicCaractMov($desde,$hasta,'exis',$producto,$tipoProIA,$unid,$unidades,$provedor,$consigna); // totdos los mov incluyendo trasp
            foreach($inventarioActualUI as $valI){ // ordenamiento
                $auxAlI[] = $valI['almacenRR'];
                $auxAUI[] = $valI['idubicacion'];
                $auxCoI[] = $valI['codigo'];
                $auxFeI[] = $valI['fecha'];
                $auxCaI[] = $valI['caract'];
            }
            $arrOrdFI = $inventarioActualUI;
            unset($inventarioActualUI);
            array_multisort($auxAlI, SORT_ASC, $auxCoI, SORT_ASC, $auxFeI, SORT_ASC, $arrOrdFI);
            $existenciaI = $existenImporeI = $contAI= 0;
            foreach ($arrOrdFI as $ke => $vaI) {

                    if($unid == 1){
                        $cantidad = ($vaI['cantidad'] / $vaI['converC']);

                    }else{
                        $cantidad = $vaI['cantidad'];
                    }

                    $codigoI         = $vaI['codigo'];
                    $cantidadI       = $cantidad;
                    $cantidadII      = $vaI['importe'];
                    $traspasoauxI    = $vaI['traspasoaux'];
                    $contA++;
                    if($vaI['almacenRR'] != $almacenRRAnt){
                        $existenciaI = $existenImporeI = 0;
                        $contA = 1;
                    }
                                    ///// EXCISTENCIA POR PRODUCTO EN TODOS LOS ALMACENES
                                        if($codigoI != $codigoAnt){
                                            $existenciaI = 0;
                                            $existenImporeI = 0;
                                            $contA = 1;
                                        }
                                        if($traspasoauxI == 0){//salida
                                            $existenciaI = $existenciaI - $cantidadI;
                                            $existenImporeI = $existenImporeI - $cantidadII;
                                        }
                                        if($traspasoauxI == 1){//entrada
                                            $existenciaI = $existenciaI + $cantidadI;
                                            $existenImporeI = $existenImporeI + $cantidadII;
                                        }
                                    ///// EXCISTENCIA POR PRODUCTO EN TODOS LOS ALMACENES FIN
                        $arrOrdF2I[] = array(
                            codigo           => $codigoI,
                            almacenRR        => $vaI['almacenRR'],
                            existencia       => $existenciaI,
                            existenImpore    => $existenImporeI,
                            contA            => $contA,
                        );
                    $codigoAnt         = $vaI['codigo'];
                    $almacenRRAnt      = $vaI['almacenRR'];
            }
                $arrOrdF2RI = array_reverse($arrOrdF2I);
                foreach ($arrOrdF2RI as $key => $valI) {
                     $contA  = $valI['contA']; 
                     if($contA >= $contAAnt){
                        $arrOrdF3I[] = array(
                                codigo           => $valI['codigo'],
                                almacenRR        => $valI['almacenRR'],
                                existencia       => $valI['existencia'],
                                existenImpore    => $valI['existenImpore'],                            
                            );
                     }
                     $contAAnt  = $valI['contA'];  
                }
                $arraExis = array_reverse($arrOrdF3I);
        /// INICIAL FIN

            $caract = '';
            $costoEntrada = $costoSalida = $prom = $neto = $saldo = $promedio = $existenciaFF = $existencia =0;

            foreach($inventarioActualU as $val){ // ordenamiento
                $auxAl[] = $val['almacenRR'];
                $auxAU[] = $val['idubicacion'];
                $auxCo[] = $val['codigo'];
                $auxFe[] = $val['fecha'];
                $auxCa[] = $val['caract'];
            }


            $arrOrdF = $inventarioActualU;
            unset($inventarioActualU);

            array_multisort($auxAl, SORT_ASC, $auxCo, SORT_ASC, $auxFe, SORT_ASC, $arrOrdF);

            //// RECORRER EL ARRA PARA SUMAR CANTIDADES E IMPORTES FINALES PARA EXSITENCIAS
                $existencia = $existenImpore = 0;

                foreach ($arrOrdF as $ke => $va) {

                    if($unid == 1){
                        $cantidad = ($va['cantidad'] / $va['converC']);

                    }else{
                        $cantidad = $va['cantidad'];
                    }

                    $codigo         = $va['codigo'];
                    //$cantidad       = $cantidad;
                    $cantidadI      = $va['importe'];
                    $traspasoaux    = $va['traspasoaux'];
                    $caract = '';

                     //new AGREGA CAMPO CARACTERISTICA
                        foreach ($arrCaractR as $value) {
                            $idCar      = $value['id'];
                            $idCar      = $idCar*1;
                            $caractR    = $value['caractR'];
                            if($idCar == $va['id']){
                                $caract = "(".$caractR.")";
                                break;
                            }
                        }

                    ///// EXCISTENCIA POR PRODUCTO EN TODOS LOS ALMACENES
                        if($codigo != $codigoAnt){
                            $existencia = $existenImpore = 0;
                        }
                        if($traspasoaux == 0){//salida
                            $existencia = $existencia - $cantidad;
                            $existenImpore = $existenImpore - $cantidadI;
                        }
                        if($traspasoaux == 1){//entrada
                            $existencia = $existencia + $cantidad;
                            $existenImpore = $existenImpore + $cantidadI;
                        }
                    ///// EXCISTENCIA POR PRODUCTO EN TODOS LOS ALMACENES FIN
                        if($unid == 1){
                            $unidadF = $va['unidadC'];
                        }else{
                            $unidadF = $va['unidad'];
                        }
                        $arrOrdF2[] = array(
                            id               => $va['id'],
                            nombre           => $va['nombre'],
                            id_producto      => $va['id_producto'],
                            codigo           => $codigo,
                            cantidad         => $cantidad,
                            cantidadI        => $cantidadI,
                            costo            => $va['costo'],
                            importe          => $va['importe'],
                            fecha            => $va['fecha'],
                            id_producto      => $va['id_producto'],
                            traspasoaux      => $traspasoaux,
                            tipo_traspaso    => $va['tipo_traspaso'],
                            unidad           => $unidadF,
                            moneda           => $va['moneda'],
                            nombreAlmacen    => $va['nombreAlmacen'],
                            almacenRR        => $va['almacenRR'],
                            codigo_sistema   => $va['codigo_sistema'],
                            almacenUbicacion => $va['almacenUbicacion'],
                            idubicacion      => $va['idubicacion'],
                            caract           => $caract,
                            costeo           => $va['costeo'],
                            existencia       => $existencia,
                            existenImpore    => $existenImpore,
                            //promedio         => $va['promedio'],
                        );
                    $codigoAnt         = $va['codigo'];
                }
            //// RECORRER EL ARRA PARA SUMAR CANTIDADES E IMPORTES FINALES PARA EXSITENCIAS FIN
                        
            //////////// COSTEOS  FIN//////////////////
               
            foreach($arrOrdF2 as $vall){ // ordenamiento
                $auxAlmRR[] = $vall['almacenRR'];
                $auxCo123[] = $vall['codigo'];
                $auxAl123[] = $vall['idubicacion'];
                $auxCa123[] = $vall['caract'];
                $auxFe123[] = $vall['fecha'];
            }
            
            array_multisort($auxAlmRR, SORT_ASC, $auxCo123, SORT_ASC, $auxAl123, SORT_ASC, $auxCa123, SORT_DESC, $auxFe123, SORT_ASC, $arrOrdF2);
            
            $arrESTU2 = $arrOrdF2;
            unset($arrOrdF2);

            foreach ($arrESTU2 as $ke => $ve) {
                $almacenRR   = $ve['almacenRR'];
                //if($almacenRR == $almacenSe){
                    $arreglado[] = array(
                            id                 => $ve['id'],
                            nombre             => $ve['nombre'],
                            id_producto        => $ve['id_producto'],
                            codigo             => $ve['codigo'], 
                            cantidad           => $ve['cantidad'],
                            cantidadI          => $ve['cantidadI'],
                            costo              => $ve['costo'],                       
                            importe            => $ve['importe'],
                            fecha              => $ve['fecha'],
                            id_producto        => $ve['id_producto'],
                            costeo             => $ve['costeo'],
                            traspasoaux        => $ve['traspasoaux'],
                            unidad             => $ve['unidad'],
                            moneda             => $ve['moneda'],
                            nombreAlmacen      => $ve['nombreAlmacen'],
                            almacenRR          => $ve['almacenRR'],
                            codigo_sistema     => $ve['codigo_sistema'],
                            almacenUbicacion   => $ve['almacenUbicacion'],
                            idubicacion        => $ve['idubicacion'],
                            caract             => $ve['caract'],
                        );
                //}
            }

            $exisP = $exisU = $exisC = $exisAl = $totalE = $totalS = $exisPI = $exisUI = $exisCI = $exisAlI = $totalEI = $totalSI = 0;

            foreach ($arreglado as $value) {
                                    
                $id                 = $value['id'];
                $nombre             = $value['nombre'];
                $id_producto        = $value['id_producto'];
                $codigo             = $value['codigo']; 
                $cantidad           = $value['cantidad'];
                $cantidadI          = $value['cantidadI'];
                $costo              = $value['costo'];                       
                $importe            = $value['importe'];
                $fecha              = $value['fecha'];
                $costeo             = $value['costeo'];
                $traspasoaux        = $value['traspasoaux'];
                $unidad             = $value['unidad'];
                $moneda             = $value['moneda'];
                $nombreAlmacen      = $value['nombreAlmacen'];
                $almacenRR          = $value['almacenRR'];
                $codigo_sistema     = $value['codigo_sistema'];
                $almacenUbicacion   = $value['almacenUbicacion'];
                $idubicacion        = $value['idubicacion'];
                $caract             = $value['caract'];

                                
                    if($almacenRR != $almacenRRAnt){
                        $exisUPRR = $exisUPRRI = 0;
                    }

                    if($codigo != $codigoUAnt){
                        $exisUpro = $exisU = $exisUcar = $exisUPRR = $exisUproI = $exisUI = $exisUcarI = $exisUPRRI = 0;
                    }
                    if($idubicacion != $idubicacionAnt){
                        $exisU = $exisUcar = $exisUI = $exisUcarI = 0;
                    }
                    if($caract != $caractAnt){
                        $exisUcar = $exisUcarI = 0;
                    }
                    if($traspasoaux == 0){//salida
                        $exisUpro   = $exisUpro - $cantidad;
                        $exisU      = $exisU - $cantidad;
                        $exisUcar   = $exisUcar - $cantidad;
                        $exisUPRR   = $exisUPRR - $cantidad;

                        $exisAl     = $exisAl - $cantidad;
                        $totalS     = $totalS + $cantidad;

                        $exisUproI   = $exisUproI - $cantidadI;
                        $exisUI      = $exisUI - $cantidadI;
                        $exisUcarI   = $exisUcarI - $cantidadI;
                        $exisUPRRI   = $exisUPRRI - $cantidadI;

                        $exisAlI     = $exisAlI - $cantidadI;
                        $totalSI     = $totalSI + $cantidadI;
                    }
                    if($traspasoaux == 1){//entrada
                        $exisUpro   = $exisUpro + $cantidad;
                        $exisU      = $exisU + $cantidad;
                        $exisUcar   = $exisUcar + $cantidad;
                        $exisUPRR   = $exisUPRR + $cantidad;

                        $exisAl     = $exisAl + $cantidad;
                        $totalE     = $totalE + $cantidad; 

                        $exisUproI   = $exisUproI + $cantidadI;
                        $exisUI      = $exisUI + $cantidadI;
                        $exisUcarI   = $exisUcarI + $cantidadI;
                        $exisUPRRI   = $exisUPRRI + $cantidadI;

                        $exisAlI     = $exisAlI + $cantidadI;
                        $totalEI     = $totalEI + $cantidadI;                                
                    }

                    if($almacenRR == $almacenRRAnt){
                        $contRR++;
                    }else{
                        $contRR=1;
                    }
                    if($codigo == $codigoUAnt){
                        if($almacenRR == $almacenRRAnt){
                            $contP++;
                        }else{
                            $contP=1; // se podria omitir

                            $salR = $salRI = $entR = $entRI = 0;
                        }
                    }else{
                        $contP=1;

                        $salR = $salRI = $entR = $entRI = 0;
                    }
                    if($idubicacion == $idubicacionAnt){
                        $contU++;
                    }else{
                        $contU=1;
                    }
                    if($caract == $caractAnt){
                        $contC++;
                    }else{
                        $contC=1;
                    }  

                    if($fecha >= $desde and $fecha <= $hasta){
                        if($traspasoaux == 0){//salida
                            $salR = $salR + $cantidad;
                            $salRI = $salRI + $cantidadI;
                        }
                        if($traspasoaux == 1){//entrada
                            $entR = $entR + $cantidad;
                            $entRI = $entRI + $cantidadI;
                        }
                    }

                        $arrExs[] = array(
                            id                 => $id,
                            nombre             => $nombre,
                            id_producto        => $id_producto,
                            fecha              => $fecha,
                            cantidad           => $cantidad,
                            codigo             => $codigo,
                            traspasoaux        => $traspasoaux,
                            unidad             => $unidad,
                            nombreAlmacen      => $nombreAlmacen,
                            caract             => $caract,
                            exisUpro           => $exisUpro,
                            exisUcar           => $exisUcar,
                            exisUPRR           => $exisUPRR,
                            exisUPRRI          => $exisUPRRI,
                            exisU              => $exisU,
                            exisAl             => $exisAl,
                            contP              => $contP,
                            contU              => $contU,
                            contC              => $contC,
                            contRR             => $contRR,
                            almacenUbicacion   => $almacenUbicacion,
                            almacenRR          => $almacenRR,
                            idubicacion        => $idubicacion,
                            totalE             => $totalE,
                            totalS             => $totalS,
                            codigo_sistema     => $codigo_sistema,

                            cantidadI          => $cantidadI,
                            exisUproI          => $exisUproI,
                            exisUcarI          => $exisUcarI,
                            exisUI             => $exisUI,
                            exisAlI            => $exisAlI,

                            totalEI            => $totalEI,
                            totalSI            => $totalSI,

                            entR               => $entR,
                            entRI              => $entRI,
                            salR               => $salR,
                            salRI              => $salRI,
                        ); 

               // }

                $almacenRRAnt           = $value['almacenRR'];
                $idubicacionAnt         = $value['idubicacion'];
                $codigoUAnt             = $value['codigo'];
                $caractAnt              = $value['caract'];                            
            }

            $arrExsR = array_reverse($arrExs);
            unset($arrExs);

            foreach ($arrExsR as $k => $va) {
                                    
                $contP      = $va['contP'];
                $contU      = $va['contU'];
                $contC      = $va['contC'];
                $contRR     = $va['contRR'];

                
                if($contRR >= $contRRAnt){
                    $auxRR = 1;
                }else{        /// Carac
                    $auxRR = 0; 
                }
                if($contC >= $contCAnt){
                    $auxC = 1;
                }else{        /// Carac
                    $auxC = 0; 
                }
                if($contU >= $contUAnt){
                    $auxU = $auxC = 1;
                }else{        /// Ubica
                    $auxU = 0; 
                }
                if($contP >= $contPAnt){
                    $auxP = $auxU = $auxC = 1;

                }else{        /// Produ
                    if($auxRR == 1){
                        $auxP = 1; 
                    }else{ //// CUANDO SE HACE UN FILTRADO  QUE MUESTRA UN SOLO PRODUCTO EN DIFERENTES ALMACENES
                        $auxP = 0;   
                    }
                }

                

                $arrExsFR[] = array(
                    id                  => $va['id'],
                    nombre              => $va['nombre'],
                    id_producto         => $va['id_producto'],           
                    fecha               => $va['fecha'],
                    cantidad            => $va['cantidad'],
                    codigo              => $va['codigo'],
                    traspasoaux         => $va['traspasoaux'],
                    unidad              => $va['unidad'],
                    caract              => $va['caract'],
                    exisUpro            => $va['exisUpro'],
                    exisUcar            => $va['exisUcar'],
                    exisUPRR            => $va['exisUPRR'],
                    exisUPRRI           => $va['exisUPRRI'],
                    exisU               => $va['exisU'],
                    exisAl              => $va['exisAl'],
                    contP               => $va['contP'],
                    contU               => $va['contU'],
                    contC               => $va['contC'],
                    almacenUbicacion    => $va['almacenUbicacion'],
                    almacenRR           => $va['almacenRR'],
                    nombreAlmacen       => $va['nombreAlmacen'],
                    idubicacion         => $va['idubicacion'],
                    auxP                => $auxP,
                    auxU                => $auxU,
                    auxC                => $auxC,
                    auxRR               => $auxRR,
                    totalE              => $va['totalE'],
                    totalS              => $va['totalS'],
                    codigo_sistema      => $va['codigo_sistema'],

                    cantidadI          => $va['cantidadI'],
                    exisUproI          => $va['exisUproI'],
                    exisUcarI          => $va['exisUcarI'],
                    exisUI             => $va['exisUI'],
                    exisAlI            => $va['exisAlI'],
                    totalEI            => $va['totalEI'],
                    totalSI            => $va['totalSI'],

                    entR               => $va['entR'],
                    entRI              => $va['entRI'],
                    salR               => $va['salR'],
                    salRI              => $va['salRI'],

                );

                $contRRAnt     = $va['contRR'];
                $countAnt      = $va['count'];
                $contPAnt      = $va['contP'];
                $contUAnt      = $va['contU'];
                $contCAnt      = $va['contC'];
            }

            $arrExsF = array_reverse($arrExsFR);
            unset($arrExsFR);

            //echo json_encode($arrExsF);   /// <- entradas y salidas GOOD

            /// PARA CONOCER LAS EXISTENCIAS POR UBICACION, CARACTERISTICA  Y PRODUCTO
            foreach ($arrExsF as $k => $v) {  /// POR PRODUCTO -> ALMACEN GENERAL
                $auxRR      = $v['auxRR'];
                
                //format
                $exisUcar = number_format($v['exisUcar'],2);
                $exisUcarI = number_format($v['exisUcarI'],2);

                if($auxRR == 1){
                    $arrExsFURR[] = array( // EXISTENCIAS FINAL UBICACION CARACTERISTICAS
                                        id                  => $v['id'],
                                        nombre              => $v['nombre'],
                                        id_producto         => $v['id_producto'],           
                                        fecha               => $v['fecha'],
                                        cantidad            => $v['cantidad'],
                                        codigo              => $v['codigo'],
                                        traspasoaux         => $v['traspasoaux'],
                                        unidad              => $v['unidad'],
                                        caract              => $v['caract'],
                                        exisUpro            => $v['exisUpro'],
                                        exisUcar            => $exisUcar,
                                        exisUPRRI           => $v['exisUPRRI'],
                                        exisUPRR            => $v['exisUPRR'],
                                        exisU               => $v['exisU'],
                                        exisAl              => $v['exisAl'],
                                        contP               => $v['contP'],
                                        contU               => $v['contU'],
                                        contC               => $v['contC'],
                                        contRR              => $v['contRR'],
                                        almacenUbicacion    => $v['almacenUbicacion'],
                                        almacenRR           => $v['almacenRR'],
                                        nombreAlmacen       => $v['nombreAlmacen'],
                                        idubicacion         => $v['idubicacion'],
                                        auxCon              => $v['auxCon'],
                                        auxP                => $v['auxP'],
                                        auxU                => $v['auxU'],
                                        auxC                => $v['auxC'],
                                        auxRR               => $v['auxRR'],
                                        totalE              => $v['totalE'],
                                        totalS              => $v['totalS'],
                                        codigo_sistema      => $v['codigo_sistema'],

                                        cantidadI           => $v['cantidadI'],
                                        exisUproI           => $v['exisUproI'],
                                        exisUcarI           => $v['exisUcarI'],
                                        exisUI              => $v['exisUI'],
                                        exisAlI             => $v['exisAlI'],
                                        totalEI             => $v['totalEI'],
                                        totalSI             => $v['totalSI'],
                                        );
                }
            }
            foreach ($arrExsF as $k => $v) {  /// POR PRODUCTO -> ALMACEN GENERAL
                $auxP       = $v['auxP'];
                $almacenRR  = $v['almacenRR'];
                $codigo_sistema  = $v['codigo_sistema'];
                
                //format
                $exisUcar = number_format($v['exisUcar'],2);
                $exisUcarI = number_format($v['exisUcarI'],2);

                if($auxP == 1){
                    /// condicion para sucursal
                    
                    if(in_array("0", $almacenSe) and ($codigo_sistema != 999)){
                        $arrExsFUP[] = array( // EXISTENCIAS FINAL UBICACION CARACTERISTICAS
                                            id                  => $v['id'],
                                            nombre              => $v['nombre'],
                                            id_producto         => $v['id_producto'],           
                                            fecha               => $v['fecha'],
                                            cantidad            => $v['cantidad'],
                                            codigo              => $v['codigo'],
                                            traspasoaux         => $v['traspasoaux'],
                                            unidad              => $v['unidad'],
                                            caract              => $v['caract'],
                                            exisUpro            => $v['exisUpro'],
                                            exisUcar            => $exisUcar,
                                            exisUPRRI           => $v['exisUPRRI'],
                                            exisUPRR            => $v['exisUPRR'],
                                            exisU               => $v['exisU'],
                                            exisAl              => $v['exisAl'],
                                            contP               => $v['contP'],
                                            contU               => $v['contU'],
                                            contC               => $v['contC'],
                                            contRR              => $v['contRR'],
                                            almacenUbicacion    => $v['almacenUbicacion'],
                                            almacenRR           => $v['almacenRR'],
                                            nombreAlmacen       => $v['nombreAlmacen'],
                                            idubicacion         => $v['idubicacion'],
                                            auxCon              => $v['auxCon'],
                                            auxP                => $v['auxP'],
                                            auxU                => $v['auxU'],
                                            auxC                => $v['auxC'],
                                            auxRR               => $v['auxRR'],
                                            totalE              => $v['totalE'],
                                            totalS              => $v['totalS'],
                                            codigo_sistema      => $v['codigo_sistema'],

                                            cantidadI           => $v['cantidadI'],
                                            exisUproI           => $v['exisUproI'],
                                            exisUcarI           => $v['exisUcarI'],
                                            exisUI              => $v['exisUI'],
                                            exisAlI             => $v['exisAlI'],
                                            totalEI             => $v['totalEI'],
                                            totalSI             => $v['totalSI'],

                                            entR               => $v['entR'],
                                            entRI              => $v['entRI'],
                                            salR               => $v['salR'],
                                            salRI              => $v['salRI'],
                                            );
                    }else
                    {
                        if(in_array($almacenRR, $almacenSe) and ($codigo_sistema != 999)){ /// TIPO: IN  -> MYSQL ... LOS ALMACENES SELECCCIONADOS
                            $arrExsFUP[] = array( // EXISTENCIAS FINAL UBICACION CARACTERISTICAS
                                            id                  => $v['id'],
                                            nombre              => $v['nombre'], 
                                            id_producto         => $v['id_producto'],           
                                            fecha               => $v['fecha'],
                                            cantidad            => $v['cantidad'],
                                            codigo              => $v['codigo'],
                                            traspasoaux         => $v['traspasoaux'],
                                            unidad              => $v['unidad'],
                                            caract              => $v['caract'],
                                            exisUpro            => $v['exisUpro'],
                                            exisUcar            => $exisUcar,
                                            exisUPRRI           => $v['exisUPRRI'],
                                            exisUPRR            => $v['exisUPRR'],
                                            exisU               => $v['exisU'],
                                            exisAl              => $v['exisAl'],
                                            contP               => $v['contP'],
                                            contU               => $v['contU'],
                                            contC               => $v['contC'],
                                            contRR              => $v['contRR'],
                                            almacenUbicacion    => $v['almacenUbicacion'],
                                            almacenRR           => $v['almacenRR'],
                                            nombreAlmacen       => $v['nombreAlmacen'],
                                            idubicacion         => $v['idubicacion'],
                                            auxCon              => $v['auxCon'],
                                            auxP                => $v['auxP'],
                                            auxU                => $v['auxU'],
                                            auxC                => $v['auxC'],
                                            auxRR               => $v['auxRR'],
                                            totalE              => $v['totalE'],
                                            totalS              => $v['totalS'],
                                            codigo_sistema      => $v['codigo_sistema'],

                                            cantidadI           => $v['cantidadI'],
                                            exisUproI           => $v['exisUproI'],
                                            exisUcarI           => $v['exisUcarI'],
                                            exisUI              => $v['exisUI'],
                                            exisAlI             => $v['exisAlI'],
                                            totalEI             => $v['totalEI'],
                                            totalSI             => $v['totalSI'],

                                            entR               => $v['entR'],
                                            entRI              => $v['entRI'],
                                            salR               => $v['salR'],
                                            salRI              => $v['salRI'],
                                            );
                        }
                    }
                    
                    
                }
            }
            foreach ($arrExsF as $k => $v) {  /// POR UBICACION -> BODEGA, PASILLO, RACK
                $auxU      = $v['auxU'];
                
                //format
                $exisUcar = number_format($v['exisUcar'],2);
                $exisU    = number_format($v['exisU'],2);
                $exisUcarI = number_format($v['exisUcarI'],2);
                $exisUI = number_format($v['exisUI'],2);

                if($auxU == 1){
                    $arrExsFUU[] = array( // EXISTENCIAS FINAL UBICACION CARACTERISTICAS
                                        id                  => $v['id'],
                                        nombre              => $v['nombre'],
                                        id_producto         => $v['id_producto'],           
                                        fecha               => $v['fecha'],
                                        cantidad            => $v['cantidad'],
                                        codigo              => $v['codigo'],
                                        traspasoaux         => $v['traspasoaux'],
                                        unidad              => $v['unidad'],
                                        caract              => $v['caract'],
                                        exisUpro            => $v['exisUpro'],
                                        exisUcar            => $exisUcar,
                                        exisUPRRI           => $v['exisUPRRI'],
                                        exisUPRR            => $v['exisUPRR'],
                                        exisU               => $exisU,
                                        exisAl              => $v['exisAl'],
                                        contP               => $v['contP'],
                                        contU               => $v['contU'],
                                        contC               => $v['contC'],
                                        contRR              => $v['contRR'],
                                        almacenUbicacion    => $v['almacenUbicacion'],
                                        almacenRR           => $v['almacenRR'],
                                        nombreAlmacen       => $v['nombreAlmacen'],
                                        idubicacion         => $v['idubicacion'],
                                        auxCon              => $v['auxCon'],
                                        auxP                => $v['auxP'],
                                        auxU                => $v['auxU'],
                                        auxC                => $v['auxC'],
                                        auxRR               => $v['auxRR'],
                                        totalE              => $v['totalE'],
                                        totalS              => $v['totalS'],
                                        codigo_sistema      => $v['codigo_sistema'],

                                        cantidadI           => $v['cantidadI'],
                                        exisUproI           => $v['exisUproI'],
                                        exisUcarI           => $exisUcarI,
                                        exisUI              => $exisUI,
                                        exisAlI             => $v['exisAlI'],
                                        totalEI             => $v['totalEI'],
                                        totalSI             => $v['totalSI'],
                                        );
                }
            }
            foreach ($arrExsF as $k => $v) { /// POR CARACTERISTICA -> CARACTERISTICA, SIN CARACTERISTICA
                $auxC      = $v['auxC'];
                
                //format
                $exisUcar = number_format($v['exisUcar'],2);
                $exisUcarI = number_format($v['exisUcarI'],2);

                if($auxC == 1){
                    $arrExsFUC[] = array( // EXISTENCIAS FINAL UBICACION CARACTERISTICAS
                                        id                  => $v['id'],
                                        nombre              => $v['nombre'],
                                        id_producto         => $v['id_producto'],           
                                        fecha               => $v['fecha'],
                                        cantidad            => $v['cantidad'],
                                        codigo              => $v['codigo'],
                                        traspasoaux         => $v['traspasoaux'],
                                        unidad              => $v['unidad'],
                                        caract              => $v['caract'],
                                        exisUpro            => $v['exisUpro'],
                                        exisUcar            => $exisUcar,
                                        exisUPRRI           => $v['exisUPRRI'],
                                        exisUPRR            => $v['exisUPRR'],
                                        exisU               => $v['exisU'],
                                        exisAl              => $v['exisAl'],
                                        contP               => $v['contP'],
                                        contU               => $v['contU'],
                                        contC               => $v['contC'],
                                        contRR              => $v['contRR'],
                                        almacenUbicacion    => $v['almacenUbicacion'],
                                        almacenRR           => $v['almacenRR'],
                                        nombreAlmacen       => $v['nombreAlmacen'],
                                        idubicacion         => $v['idubicacion'],
                                        auxCon              => $v['auxCon'],
                                        auxP                => $v['auxP'],
                                        auxU                => $v['auxU'],
                                        auxC                => $v['auxC'],
                                        auxRR               => $v['auxRR'],
                                        totalE              => $v['totalE'],
                                        totalS              => $v['totalS'],
                                        codigo_sistema      => $v['codigo_sistema'],

                                        cantidadI           => $v['cantidadI'],
                                        exisUproI           => $v['exisUproI'],
                                        exisUcarI           => $exisUcarI,
                                        exisUI              => $v['exisUI'],
                                        exisAlI             => $v['exisAlI'],
                                        totalEI             => $v['totalEI'],
                                        totalSI             => $v['totalSI'],
                                        );
                }
            }

            ///// FILTRADO Y FORMATO ////////
            foreach ($arrExsF as $keys => $values) {
                $fechaF     = $values['fecha'];
                $almacenRR  = $values['almacenRR'];
                $codigo_sistema  = $values['codigo_sistema'];

                if(in_array($almacenRR, $almacenSe)){
                    $arrKarFil[] = array(
                        id                 => $values['id'],
                        nombre             => $values['nombre'],
                        id_producto        => $values['id_producto'],
                        fecha              => $values['fecha'],
                        cantidad           => $values['cantidad'],
                        codigo             => $values['codigo'],
                        traspasoaux        => $values['traspasoaux'],
                        unidad             => $values['unidad'],
                        //nombreAlmacen      => $values['nombreAlmacen'],
                        caract             => $values['caract'],
                        exisUpro           => $values['exisUpro'],
                        exisUcar           => $values['exisUcar'],
                        exisUPRRI          => $values['exisUPRRI'],
                        exisUPRR           => $values['exisUPRR'],
                        exisU              => $values['exisU'],
                        exisAl             => $values['exisAl'],
                        contP              => $values['contP'],
                        contU              => $values['contU'],
                        contC              => $values['contC'],
                        contRR             => $values['contRR'],
                        almacenUbicacion   => $values['almacenUbicacion'],
                        almacenRR          => $values['almacenRR'],
                        nombreAlmacen      => $values['nombreAlmacen'],
                        idubicacion        => $values['idubicacion'],
                        totalE             => $values['totalE'],
                        totalS             => $values['totalS'],
                        codigo_sistema     => $values['codigo_sistema'],
                        cantidadI          => $values['cantidadI'],
                        exisUproI          => $values['exisUproI'],
                        exisUcarI          => $values['exisUcarI'],
                        exisUI             => $values['exisUI'],
                        exisAlI            => $values['exisAlI'],
                        totalEI            => $values['totalEI'],
                        totalSI            => $values['totalSI'],
                        auxP               => $values['auxP'],
                        auxU               => $values['auxU'],
                        auxC               => $values['auxC'],
                        auxRR              => $values['auxRR'],
                    ); 
                }
            }

            //$multArraiA = array('kardex' => $arrOrdF2, 'kardexF' => $arrKarFil, 'invRR' => $arrExsFURR, 'invA' => $arrExsFUP, 'invAU' => $arrExsFUU, 'invAC' => $arrExsFUC);
            //echo json_encode($multArraiA);
            //echo json_encode($arraExis);


        // NEW CH@
            $x = $c = $u = $y = $f = $zz = '';
            $maxCount2 = $almacenRRCont = $idMax = $almacenRRAnt = $inicial = $TotalInic = $TotalEntr = $TotalSali = $TotalExis = 0;

            foreach ($arrExsFUP as $keys => $val) {
                $almacenRR        = $val['almacenRR'];
                $nombreAlmacen    = $val['nombreAlmacen'];
                $codigo           = $val['codigo'];
                $nombre           = $val['nombre'];
                $unidad           = $val['unidad'];

                if($tipo2 == 'uni'){
                    $exisUPRR         = $val['exisUPRR'];
                    $entR             = $val['entR'];
                    $salR             = $val['salR'];
                }
                
                if($tipo2 == 'imp'){
                    $exisUPRR         = $val['exisUPRRI'];
                    $entR             = $val['entRI'];
                    $salR             = $val['salRI'];
                }
                
                $codigo_sistema   = $val['codigo_sistema'];
                
                /// EXISTENCIA
                foreach ($arraExis as $keys => $value) {
                    $almacenRR1          = $value['almacenRR']; 
                    $codigo1             = $value['codigo'];
                    if($tipo2 == 'uni'){
                        $existenciaActual1   = $value['existencia']*1;
                    }
                    
                    if($tipo2 == 'imp'){
                        $existenciaActual1   = $value['existenImpore']*1;
                    }
                    
                    $inicial =0;
                        if($codigo1 == $codigo and $almacenRR1 == $almacenRR){
                            $inicial = $existenciaActual1;
                            break;
                        } 
                }
                /// 

                if($almacenRR != $almacenRRAnt){ // ENCABEZADOS
                        $y ='<tr>'.
                                '<td><b>'.$nombreAlmacen.'</b></td>'.
                                '<td><b>'.$sucursalSelect.'</b></td>'.
                                '<td></td>'.
                                '<td></td>'.
                                '<td></td>'.
                                '<td></td>'.
                                '<td></td>'.
                        '</tr>';
                        $arrFinal[] = array( row => $y );
                        $tablaphp .= $y;
                    }

                    $btnU = '';                       
                    $btnU = '<button class="btn btn-default btn-xs" onclick="rowU(\''.$codigo.'\')"><i id="iU'.$codigo.'" class="glyphicon glyphicon-chevron-down"></i></button>';

                    $TotalInic = $TotalInic + $inicial*1;                                                                
                    $TotalEntr = $TotalEntr + $entR*1;                                                            
                    $TotalSali = $TotalSali + $salR*1;                                                                
                    $TotalExis = $TotalExis + $exisUPRR*1;

                    if($tipo2 == 'uni'){
                        $inicialF = number_format($inicial,2);                    
                        $entRF = number_format($entR,2);                    
                        $salRF = number_format($salR,2);                    
                        $exisUPRRF = number_format($exisUPRR,2);
                    }                        
                    if($tipo2 == 'imp'){
                        $inicialF = '$'.number_format($inicial,2);                    
                        $entRF = '$'.number_format($entR,2);                    
                        $salRF = '$'.number_format($salR,2);                    
                        $exisUPRRF = '$'.number_format($exisUPRR,2);
                    }
                                                            
                    

                    $x ='<tr>'.
                                '<td>'.$btnU.' '.$codigo.'</td>'.
                                '<td><a onclick="verProducto(\'' . $val['id_producto'] . '\',\'' . 'modalIA' . '\',\'' . '0' . '\');">'.$nombre.'</a></td>'.
                                '<td>'.$unidad.'</td>'.                                                   
                                '<td align="right">'.$inicialF.'</td>'.
                                '<td align="right">'.$entRF.'</td>'.//costo
                                '<td align="right">'.$salRF.'</td>'.
                                '<td align="right">'.$exisUPRRF.'</td>'.
                        '</tr>';
                    
                    $arrFinal[] = array( row => $x );   //// normal 
                    $tablaphp .= $x;      

                //UBICACION
                    foreach ($arrExsFUU as $keys => $valor) {
                        $codigoU                 = $valor['codigo'];
                        if($tipo2 == 'uni'){
                            $existenciaUbicacion     = $valor['exisU'];
                        }                        
                        if($tipo2 == 'imp'){
                           $existenciaUbicacion     = '$'.$valor['exisUI']; 
                        }                        
                        
                        $almacenUbicacion        = $valor['almacenUbicacion'];
                        $idrralmacen             = $valor['idubicacion'];
                        $almacenRRU              = $valor['almacenRR'];
                        $codigo_sistema          = $valor['codigo_sistema'];

                        if($codigo_sistema == null){
                            $codigo_sistema = '';
                        }else{
                            $codigo_sistema = $codigo_sistema;
                        }
                        if($codigo == $codigoU and $almacenRR == $almacenRRU){
                            $btnU2 = '<button class="btn btn-default btn-xs" onclick="rowU2(\''.$idrralmacen.'_'.$codigoU.'\')"><i id="iU2'.$codigo.'" class="glyphicon glyphicon-chevron-down"></i></button>';

                            $u ='<tr class="rowU'.$codigoU.' rowhide">'.
                                            '<td></td>'. // +codigo_sistema+'
                                            '<td>'.$btnU2.' '.$almacenUbicacion.'</td>'.
                                            '<td>'.$unidad.'</td>'.                                                   
                                            '<td align="right"></td>'.
                                            '<td align="right"></td>'.// +almacenRR+
                                            '<td align="right"></td>'.
                                            '<td align="right">'.$existenciaUbicacion.'</td>'.
                                        '</tr>';
                            $arrFinal[] = array( row => $u );
                            $tablaphp .= $u; 
                            
                        // CARACTERISTICAS
                            foreach ($arrExsFUC as $keys => $valo) {
                                $codigoC             = $valo['codigo'];
                                if($tipo2 == 'uni'){
                                    $existenciaCar       = $valo['exisUcar'];
                                }                                
                                if($tipo2 == 'imp'){
                                    $existenciaCar       = '$'.$valo['exisUcarI'];
                                } 
                                                               
                                $caract              = $valo['caract'];
                                $almacenUbicacionC   = $valo['almacenUbicacion'];
                                $idrralmacenC        = $valo['idubicacion'];

                                if($caract == ''){
                                    $caract = 'Sin Caracteristica';
                                }else{
                                    $caract = $caract;
                                }

                                if($codigo == $codigoC and $almacenUbicacionC == $almacenUbicacion){
                                            $c ='<tr class="rowU2'.$idrralmacen.'_'.$codigoC.' rowhide '.$codigoC.'">'.
                                                    '<td></td>'.
                                                    '<td>'.$caract.'</td>'.
                                                    '<td>'.$unidad.'</td>'.                                                   
                                                   // '<td colspan="3"></td>'.
                                                    '<td colspan="3"></td>'.
                                                    '<td style="display: none;"></td>'.
                                                    '<td style="display: none;"></td>'.
                                                    '<td align="right">'.$existenciaCar.'</td>'.
                                                '</tr>';
                                    $arrFinal[] = array( row => $c ); 
                                    $tablaphp .= $c;
                                }
                            } // CARACTERISTICAS FIN
                        }
                    } //UBICACION FIN

                    if($tipo2 == 'uni'){
                        $TotalInicF = number_format($TotalInic,2);
                        $TotalEntrF = number_format($TotalEntr,2); 
                        $TotalSaliF = number_format($TotalSali,2); 
                        $TotalExisF = number_format($TotalExis,2);
                    }                                
                    if($tipo2 == 'imp'){
                        $TotalInicF = '$'.number_format($TotalInic,2);
                        $TotalEntrF = '$'.number_format($TotalEntr,2); 
                        $TotalSaliF = '$'.number_format($TotalSali,2); 
                        $TotalExisF = '$'.number_format($TotalExis,2);
                    } 
                     

                /*
                    $f ='<tr>'.                                                                                                    
                                                    '<td colspan="2"></td>'.
                                                    '<td style="display: none;"></td>'.
                                                    '<td><b>TOTAL</b></td>'.
                                                    '<td align="right"><b>'.$TotalInicF.'</b></td>'.
                                                    '<td align="right"><b>'.$TotalEntrF.'</b></td>'.
                                                    '<td align="right"><b>'.$TotalSaliF.'</b></td>'.
                                                    '<td align="right"><b>'.$TotalExisF.'</b></td>'.
                                                '</tr>';
                */
                    

                $almacenRRAnt        = $val['almacenRR'];
                
            }// foreach principal

            //$arrFinal[] = array( row => $f );
            //$tablaphp .= $f;

            if($rep == 1){  echo json_encode($arrFinal); }
            if($rep == 2){  echo $tablaphp; }
            //echo json_encode($arrFinal);
            //unset($arrFinal); // limpia la var
            //echo $tablaphp;            
    }    
////////   NEW INVENTARIO ACTUAL  FIN///////////////

///////    NEW MOVIMIENTOS INVENTARIO ///////////////////
    function movinventario(){
        require('views/reportes/movinventario.php'); 
    }
    function listMovInvMov(){

            $almacenSe  = $_POST['almacen'];
            $producto   = $_POST['producto'];
            $desde      = $_POST['desde'];
            $hasta      = $_POST['hasta'];

            $inventarioActualU  = $this->ReportesModel->ubicCaractMovMI($desde,$hasta,$producto); // totdos los mov incluyendo trasp

            $desde = ($desde == '') ? '1900-01-01' : $desde.' 00:00:01';
            $hasta = ($hasta == '') ? '2900-01-01' : $hasta.' 23:59:59';
            
            /// FUNCTION PARA LAS CARACTERISTICAS
            $arrCaractR = $this->caract($desde,$hasta);

            $caract = '';
            $existencia = 0;
            /*
            foreach ($inventarioActualU['movs'] as $key => $val) { // Recorre el array principal para añadir existencia y caracteristicas
                                    $id                 = $val['id'];                                    
                                    $codigo             = $val['codigo'];
                                    $cantidad           = $val['cantidad'];
                                    $traspasoaux        = $val['traspasoaux']; 
                                    $caract = ''; 

                                    //new AGREGA CAMPO CARACTERISTICA
                                    foreach ($arrCaractR as $key => $value) {
                                        $idCar      = $value['id'];
                                        $idCar      = $idCar*1;
                                        $caractR    = $value['caractR'];
                                        if($idCar == $id){
                                            $caract = "(".$caractR.")";
                                            break;
                                        }
                                    }

                                    ///// EXCISTENCIA POR PRODUCTO EN TODOS LOS ALMACENES
                                        if($codigo != $codigoAnt){
                                            $existencia = 0;
                                        }
                                        if($traspasoaux == 0){//salida
                                            $existencia = $existencia - $cantidad;
                                        }
                                        if($traspasoaux == 1){//entrada
                                            $existencia = $existencia + $cantidad;
                                        }
                                    ///// EXCISTENCIA POR PRODUCTO EN TODOS LOS ALMACENES FIN
                
                                         $arrESTR[] = array(
                                                id               => $id,
                                                nombre           => $val['nombre'],
                                                codigo           => $codigo,
                                                cantidad         => $cantidad,
                                                costo            => $val['costo'],
                                                importe          => $val['importe'],
                                                fecha            => $val['fecha'],
                                                id_producto      => $val['id_producto'],
                                                costeo           => $val['costeo'],
                                                traspasoaux      => $traspasoaux,
                                                tipo_traspaso    => $val['tipo_traspaso'],
                                                unidad           => $val['unidad'],
                                                moneda           => $val['moneda'],
                                                nombreAlmacen    => $val['nombreAlmacen'], /// cambia var
                                                almacenRR        => $val['almacenRR'],
                                                codigo_sistema   => $val['codigo_sistema'],
                                                almacenUbicacion => $val['almacenUbicacion'],
                                                idubicacion      => $val['idubicacion'],
                                                caract           => $caract,
                                                existencia       => $existencia,

                                                no_pedimento     => $val['no_pedimento'],
                                                no_aduana        => $val['no_aduana'],
                                                tipo_cambio      => $val['tipo_cambio'],
                                                no_lote          => $val['no_lote'],
                                                fecha_fabricacion => $val['fecha_fabricacion'],
                                                fecha_caducidad   => $val['fecha_caducidad'],
                                                id_pedimento      => $val['id_pedimento'],
                                                id_lote           => $val['id_lote'],
                                                fecha_pedimento   => $val['fecha_pedimento'],
                                                idMove            => $val['idMove'],
                                                concepMove        => $val['concepMove'],
                                                razon_social      => $val['razon_social'],
                                                nombretienda      => $val['nombretienda'],

                                                origen            => $val['origen'],
                                                nombretiendapos   => $val['nombretiendapos'],
                                                razon_socialpos   => $val['razon_socialpos'],
                                                idMovepos         => $val['idMovepos'],

                                            );

                                    $caract = '';
                                    $codigoAnt             = $val['codigo'];
            }

            $costoEntrada = $costoSalida = $prom = $neto = $saldo = $promedio = $existenciaFF = $existencia = 0;

            foreach ($arrESTR as $k => $v) { /// Recorre el array principal para añadir el importe basado en el COSTEO

                $codigo         = $v['codigo'];
                $cantidad       = $v['cantidad'];
                $costo          = $v['costo'];
                $importe        = $v['importe'];
                $existencia     = $v['existencia'];
                $traspasoaux    = $v['traspasoaux'];
                $tipo_traspaso  = $v['tipo_traspaso'];
                $costeo         = $v['costeo'];
                // 1 -> PROMEDIO
                if($costeo == 1 and $costeo != 6){       
                    if($codigo != $codigoAnt){
                        $costoEntrada = $costoSalida = $prom = $neto = $saldo = $promedio = $existenciaFF = 0;
                    }
                    if($traspasoaux == 1 and $tipo_traspaso == 1){
                        $costoEntrada    = $costo;
                        $costoSalida     = 0;
                        $costoTotalEntrada = $costoEntrada * $cantidad;
                        $neto += $costoTotalEntrada;
                        $promedio = $neto / $existencia;                
                        $entradaFF = $cantidad * $costoEntrada;
                        $salidaFF = 0;
                        $existenciaFF += $cantidad * $costoEntrada;
                        $cantidadI = $entradaFF;
                    }
                    if($traspasoaux == 0 and $tipo_traspaso == 0){
                        $costoEntrada    = 0;
                        $costoSalida     = $promedio;
                        $costoTotalSalida = $costoSalida * $cantidad;
                        $neto -= $costoTotalSalida;                    
                        $promedio = $promedio;
                        $salidaFF = $cantidad * $promedio;
                        $entradaFF = 0;                        
                        $existenciaFF -= $salidas * $promedio;
                        $cantidadI = $salidaFF;
                    }
                    ///////////////////  EN EL CASO DE LOS TRASPASO SE CONSIDERA EL ULTIMO PROMEDIO ENTRE MOVIMIENTOS
                    if($tipo_traspaso == 2){  
                        if($tipo_traspasoaux == 0){
                            $costoEntrada    = 0;
                            $costoSalida     = $promedio;
                            $costoTotalSalida = $costoSalida * $cantidad;
                            $neto -= $costoTotalSalida;
                            $promedio = $promedio;
                            $salidaFF = $cantidad * $promedio;
                            $entradaFF = 0;
                            $existenciaFF -= $cantidad * $promedio;
                            $cantidadI = $salidaFF;
                        }
                        if($tipo_traspasoaux == 1){
                            $costoSalida    = 0;
                            $costoEntrada     = $promedio;
                            $costoTotalEntrada = $costoEntrada * $cantidad;
                            $neto += $costoTotalEntrada;
                            $promedio = $promedio;
                            $entradaFF = $cantidad * $promedio;
                            $salidaFF = 0;
                            $existenciaFF += $cantidad * $promedio;
                            $cantidadI = $entradaFF;
                        }   
                    }
                }
                // 1 -> PROMEDIO FIN
                // 1 -> ESPESIFICO
                if($costeo == 6){
                        $cantidadI = $importe;
                } 
                // 1 -> ESPESIFICO FIN

                                
                $arrMICost[] = array(
                    id               => $v['id'],
                    nombre           => $v['nombre'],
                    codigo           => $codigo,
                    cantidad         => $v['cantidad'],
                    cantidadI        => $cantidadI,
                    costo            => $v['costo'],
                    importe          => $v['importe'],
                    fecha            => $v['fecha'],
                    id_producto      => $v['id_producto'],
                    traspasoaux      => $traspasoaux,
                    tipo_traspaso    => $tipo_traspaso,
                    unidad           => $v['unidad'],
                    moneda           => $v['moneda'],
                    nombreAlmacen    => $v['nombreAlmacen'],
                    almacenRR        => $v['almacenRR'],
                    codigo_sistema   => $v['codigo_sistema'],
                    almacenUbicacion => $v['almacenUbicacion'],
                    idubicacion      => $v['idubicacion'],
                    caract           => $v['caract'],
                    costeo           => $costeo,
                    existencia       => $existencia,

                    no_pedimento     => $v['no_pedimento'],
                    no_aduana        => $v['no_aduana'],
                    tipo_cambio      => $v['tipo_cambio'],
                    no_lote          => $v['no_lote'],
                    fecha_fabricacion => $v['fecha_fabricacion'],
                    fecha_caducidad   => $v['fecha_caducidad'],
                    id_pedimento      => $v['id_pedimento'],
                    id_lote           => $v['id_lote'],
                    fecha_pedimento   => $v['fecha_pedimento'],
                    idMove            => $v['idMove'],
                    concepMove        => $v['concepMove'],
                    razon_social      => $v['razon_social'],
                    nombretienda      => $v['nombretienda'],

                    origen            => $v['origen'],
                    nombretiendapos   => $v['nombretiendapos'],
                    razon_socialpos   => $v['razon_socialpos'],
                    idMovepos         => $v['idMovepos'],
                );
                $codigoAnt = $v['codigo'];
            }
            */

            foreach($inventarioActualU['movs'] as $val){ // ordenamiento
                $auxAl[] = $val['almacenRR'];
                $auxAU[] = $val['idubicacion'];
                $auxCo[] = $val['codigo'];
                $auxFe[] = $val['fecha'];
                $auxCa[] = $val['caract'];
            }

            $arrOrdF = $inventarioActualU['movs'];
            array_multisort($auxAl, SORT_ASC, $auxCo, SORT_ASC, $auxFe, SORT_ASC, $arrOrdF);

            //// RECORRER EL ARRA PARA SUMAR CANTIDADES E IMPORTES FINALES PARA EXSITENCIAS
                $existencia = $existenImpore = 0;
                foreach ($arrOrdF as $ke => $va) { /// Recorre el array principal para añadir la existencia en unidades e importe
                    $codigo         = $va['codigo'];
                    $cantidad       = $va['cantidad'];
                    $cantidadI      = $va['importe'];
                    $traspasoaux    = $va['traspasoaux']; 
                    $caract = '';

                    //new AGREGA CAMPO CARACTERISTICA
                                    foreach ($arrCaractR as $key => $value) {
                                        $idCar      = $value['id'];
                                        $idCar      = $idCar*1;
                                        $caractR    = $value['caractR'];
                                        if($idCar == $va['id']){
                                            $caract = "(".$caractR.")";
                                            break;
                                        }
                                    }                   

                    ///// EXCISTENCIA POR PRODUCTO EN TODOS LOS ALMACENES
                        if($codigo != $codigoAnt){
                            $existencia = 0;
                            $existenImpore = 0;
                        }
                        if($traspasoaux == 0){//salida
                            $existencia = $existencia - $cantidad;
                            $existenImpore = $existenImpore - $cantidadI;
                        }
                        if($traspasoaux == 1){//entrada
                            $existencia = $existencia + $cantidad;
                            $existenImpore = $existenImpore + $cantidadI;
                        }
                    ///// EXCISTENCIA POR PRODUCTO EN TODOS LOS ALMACENES FIN
                        $arrOrdF2[] = array(
                            id               => $va['id'],
                            nombre           => $va['nombre'],
                            codigo           => $codigo,
                            cantidad         => $va['cantidad'],
                            cantidadI        => $cantidadI,
                            costo            => $va['costo'],
                            importe          => $va['importe'],
                            fecha            => $va['fecha'],
                            id_producto      => $va['id_producto'],
                            traspasoaux      => $traspasoaux,
                            tipo_traspaso    => $va['tipo_traspaso'],
                            unidad           => $va['unidad'],
                            moneda           => $va['moneda'],
                            nombreAlmacen    => $va['nombreAlmacen'],
                            almacenRR        => $va['almacenRR'],
                            codigo_sistema   => $va['codigo_sistema'],
                            almacenUbicacion => $va['almacenUbicacion'],
                            idubicacion      => $va['idubicacion'],
                            caract           => $caract,
                            costeo           => $va['costeo'],
                            existencia       => $existencia,
                            existenImpore    => $existenImpore,
                            
                            no_pedimento     => $va['no_pedimento'],
                            no_aduana        => $va['no_aduana'],
                            tipo_cambio      => $va['tipo_cambio'],
                            no_lote          => $va['no_lote'],
                            fecha_fabricacion => $va['fecha_fabricacion'],
                            fecha_caducidad   => $va['fecha_caducidad'],
                            id_pedimento      => $va['id_pedimento'],
                            id_lote           => $va['id_lote'],
                            fecha_pedimento   => $va['fecha_pedimento'],
                            idMove            => $va['idMove'],
                            concepMove        => $va['concepMove'],
                            razon_social      => $va['razon_social'],
                            nombretienda      => $va['nombretienda'],

                            nombretiendapos   => $va['nombretiendapos'],
                            razon_socialpos   => $va['razon_socialpos'],
                            idMovepos         => $va['idMovepos'],
                            origen            => $va['origen'],
                        );
                    $codigoAnt         = $va['codigo'];
                }
            
            //////////// COSTEOS  FIN//////////////////
               
            foreach($arrOrdF2 as $vall){ // ordenamiento
                $auxAlmRR[] = $vall['almacenRR'];
                $auxCo123[] = $vall['codigo'];
                $auxAl123[] = $vall['idubicacion'];
                $auxCa123[] = $vall['caract'];
                $auxFe123[] = $vall['fecha'];
            }

            array_multisort($auxCo123, SORT_ASC, $auxAlmRR, SORT_ASC, $auxAl123, SORT_ASC, $auxCa123, SORT_DESC, $auxFe123, SORT_ASC, $arrOrdF2);
            //array_multisort($auxAl123, SORT_ASC, $auxCo123, SORT_ASC, $auxCa123, SORT_DESC, $auxFe123, SORT_ASC, $arrOrdF2);
            $arrESTU2 = $arrOrdF2;
            //echo json_encode($arrESTU2);
            //exit();

            foreach ($arrESTU2 as $ke => $ve) { // filtro
                $almacenRR   = $ve['almacenRR'];
                $fechaF   = $ve['fecha'];
                $codigo_sistema   = $ve['codigo_sistema'];

                if($fechaF >= $desde and $fechaF <= $hasta){
                   if(in_array(0, $almacenSe) and ($codigo_sistema != 999)){
                        $arreglado[] = array(
                                    id                 => $ve['id'],
                                    nombre             => $ve['nombre'],
                                    codigo             => $ve['codigo'], 
                                    cantidad           => $ve['cantidad'],
                                    cantidadI          => $ve['cantidadI'],
                                    costo              => $ve['costo'],                       
                                    importe            => $ve['importe'],
                                    fecha              => $ve['fecha'],
                                    id_producto        => $ve['id_producto'],
                                    costeo             => $ve['costeo'],
                                    traspasoaux        => $ve['traspasoaux'],
                                    tipo_traspaso      => $ve['tipo_traspaso'],
                                    unidad             => $ve['unidad'],
                                    moneda             => $ve['moneda'],
                                    nombreAlmacen      => $ve['nombreAlmacen'],
                                    almacenRR          => $ve['almacenRR'],
                                    codigo_sistema     => $ve['codigo_sistema'],
                                    almacenUbicacion   => $ve['almacenUbicacion'],
                                    idubicacion        => $ve['idubicacion'],
                                    caract             => $ve['caract'],

                                    no_pedimento       => $ve['no_pedimento'],
                                    no_aduana          => $ve['no_aduana'],
                                    tipo_cambio        => $ve['tipo_cambio'],
                                    no_lote            => $ve['no_lote'],
                                    fecha_fabricacion  => $ve['fecha_fabricacion'],
                                    fecha_caducidad    => $ve['fecha_caducidad'],
                                    id_pedimento       => $ve['id_pedimento'],
                                    id_lote            => $ve['id_lote'],
                                    fecha_pedimento   => $ve['fecha_pedimento'],
                                    idMove            => $ve['idMove'],
                                    concepMove        => $ve['concepMove'],
                                    razon_social      => $ve['razon_social'],
                                    nombretienda      => $ve['nombretienda'],

                                    nombretiendapos   => $ve['nombretiendapos'],
                                    razon_socialpos   => $ve['razon_socialpos'],
                                    idMovepos         => $ve['idMovepos'],
                                    origen            => $ve['origen'],
                                );
                    }else{
                        if(in_array($almacenRR, $almacenSe) and ($codigo_sistema != 999)){ /// TIPO: IN  -> MYSQL ... LOS ALMACENES SELECCCIONADOS
                            $arreglado[] = array(
                                    id                 => $ve['id'],
                                    nombre             => $ve['nombre'],
                                    codigo             => $ve['codigo'], 
                                    cantidad           => $ve['cantidad'],
                                    cantidadI          => $ve['cantidadI'],
                                    costo              => $ve['costo'],                       
                                    importe            => $ve['importe'],
                                    fecha              => $ve['fecha'],
                                    id_producto        => $ve['id_producto'],
                                    costeo             => $ve['costeo'],
                                    traspasoaux        => $ve['traspasoaux'],
                                    tipo_traspaso      => $ve['tipo_traspaso'],
                                    unidad             => $ve['unidad'],
                                    moneda             => $ve['moneda'],
                                    nombreAlmacen      => $ve['nombreAlmacen'],
                                    almacenRR          => $ve['almacenRR'],
                                    codigo_sistema     => $ve['codigo_sistema'],
                                    almacenUbicacion   => $ve['almacenUbicacion'],
                                    idubicacion        => $ve['idubicacion'],
                                    caract             => $ve['caract'],

                                    no_pedimento       => $ve['no_pedimento'],
                                    no_aduana          => $ve['no_aduana'],
                                    tipo_cambio        => $ve['tipo_cambio'],
                                    no_lote            => $ve['no_lote'],
                                    fecha_fabricacion  => $ve['fecha_fabricacion'],
                                    fecha_caducidad    => $ve['fecha_caducidad'],
                                    id_pedimento       => $ve['id_pedimento'],
                                    id_lote            => $ve['id_lote'],
                                    fecha_pedimento   => $ve['fecha_pedimento'],
                                    idMove            => $ve['idMove'],
                                    concepMove        => $ve['concepMove'],
                                    razon_social      => $ve['razon_social'],
                                    nombretienda      => $ve['nombretienda'],

                                    nombretiendapos   => $ve['nombretiendapos'],
                                    razon_socialpos   => $ve['razon_socialpos'],
                                    idMovepos         => $ve['idMovepos'],
                                    origen            => $ve['origen'],
                                );
                        }
                    }   
                }   
            }

            $exisP = $exisU = $exisC = $exisAl = $totalE = $totalS = $exisPI = $exisUI = $exisCI = $exisAlI = $totalEI = $totalSI = 0;

            foreach ($arreglado as $value) {
                                    
                $id                 = $value['id'];
                $nombre             = $value['nombre'];
                $codigo             = $value['codigo']; 
                $cantidad           = $value['cantidad'];
                $cantidadI          = $value['cantidadI'];
                $costo              = $value['costo'];                       
                $importe            = $value['importe'];
                $fecha              = $value['fecha'];
                $id_producto        = $value['id_producto'];
                $costeo             = $value['costeo'];
                $traspasoaux        = $value['traspasoaux'];
                $unidad             = $value['unidad'];
                $moneda             = $value['moneda'];
                $nombreAlmacen      = $value['nombreAlmacen'];
                $almacenRR          = $value['almacenRR'];
                $codigo_sistema     = $value['codigo_sistema'];
                $almacenUbicacion   = $value['almacenUbicacion'];
                $idubicacion        = $value['idubicacion'];
                $caract             = $value['caract'];

                
                    if($almacenRR != $almacenRRAnt){
                        $exisUPRR = $exisUPRRI =0;
                    }
                    if($codigo != $codigoUAnt){
                        $exisUpro = $exisU = $exisUcar = $exisUPRR = $exisUproI = $exisUI = $exisUcarI = $exisUPRRI = 0;
                    }
                    if($idubicacion != $idubicacionAnt){
                        $exisU = $exisUcar = $exisUI = $exisUcarI = 0;
                    }
                    if($caract != $caractAnt){
                        $exisUcar = $exisUcarI  = 0;
                    }
                    if($traspasoaux == 0){//salida
                        $exisUpro   = $exisUpro - $cantidad;
                        $exisU      = $exisU - $cantidad;
                        $exisUcar   = $exisUcar - $cantidad;
                        $exisUPRR   = $exisUPRR - $cantidad;

                        $exisAl     = $exisAl - $cantidad;
                        $totalS     = $totalS + $cantidad;

                        $exisUproI   = $exisUproI - $cantidadI;
                        $exisUI      = $exisUI - $cantidadI;
                        $exisUcarI   = $exisUcarI - $cantidadI;
                        $exisUPRRI   = $exisUPRRI - $cantidadI;

                        $exisAlI     = $exisAlI - $cantidadI;
                        $totalSI     = $totalSI + $cantidadI;
                    }
                    if($traspasoaux == 1){//entrada
                        $exisUpro   = $exisUpro + $cantidad;
                        $exisU      = $exisU + $cantidad;
                        $exisUcar   = $exisUcar + $cantidad;
                        $exisUPRR   = $exisUPRR + $cantidad;

                        $exisAl     = $exisAl + $cantidad;
                        $totalE     = $totalE + $cantidad; 

                        $exisUproI   = $exisUproI + $cantidadI;
                        $exisUI      = $exisUI + $cantidadI;
                        $exisUcarI   = $exisUcarI + $cantidadI;
                        $exisUPRRI   = $exisUPRRI + $cantidadI;

                        $exisAlI     = $exisAlI + $cantidadI;
                        $totalEI     = $totalEI + $cantidadI;                                
                    }

                    if($almacenRR == $almacenRRAnt){
                        $contRR++;
                    }else{
                        $contRR=1;
                    }
                    if($codigo == $codigoUAnt){
                        if($almacenRR == $almacenRRAnt){
                            $contP++;
                        }else{
                            $contP=1; // se podria omitir
                            $salR = $salRI = $entR = $entRI =0;
                        }
                    }else{
                        $contP=1;
                        $salR = $salRI = $entR = $entRI =0;
                    }
                    if($idubicacion == $idubicacionAnt){
                        $contU++;
                    }else{
                        $contU=1;
                    }
                    if($caract == $caractAnt){
                        $contC++;
                    }else{
                        $contC=1;
                    }  


                    if($fecha >= $desde and $fecha <= $hasta){
                        if($traspasoaux == 0){//salida
                            $salR = $salR + $cantidad;
                            $salRI = $salRI + $cantidadI;
                        }
                        if($traspasoaux == 1){//entrada
                            $entR = $entR + $cantidad;
                            $entRI = $entRI + $cantidadI;
                        }

                        $arrExs[] = array(
                            id                 => $id,
                            nombre             => $nombre,
                            fecha              => $fecha,
                            cantidad           => $cantidad,
                            codigo             => $codigo,
                            traspasoaux        => $traspasoaux,
                            tipo_traspaso      => $value['tipo_traspaso'],
                            unidad             => $unidad,
                            nombreAlmacen      => $nombreAlmacen,
                            caract             => $caract,
                            exisUpro           => $exisUpro,
                            exisUcar           => $exisUcar,
                            exisUPRR           => $exisUPRR,
                            exisUPRRI          => $exisUPRRI,
                            exisU              => $exisU,
                            exisAl             => $exisAl,
                            contP              => $contP,
                            contU              => $contU,
                            contC              => $contC,
                            contRR             => $contRR,
                            almacenUbicacion   => $almacenUbicacion,
                            almacenRR          => $almacenRR,
                            idubicacion        => $idubicacion,
                            totalE             => $totalE,
                            totalS             => $totalS,
                            codigo_sistema     => $codigo_sistema,

                            cantidadI          => $cantidadI,
                            exisUproI          => $exisUproI,
                            exisUcarI          => $exisUcarI,
                            exisUI             => $exisUI,
                            exisAlI            => $exisAlI,

                            totalEI            => $totalEI,
                            totalSI            => $totalSI,

                            entR               => $entR,
                            entRI              => $entRI,
                            salR               => $salR,
                            salRI              => $salRI,
                            costeo             => $value['costeo'],

                            no_pedimento       => $value['no_pedimento'],
                            no_aduana          => $value['no_aduana'],
                            tipo_cambio        => $value['tipo_cambio'],
                            no_lote            => $value['no_lote'],
                            fecha_fabricacion  => $value['fecha_fabricacion'],
                            fecha_caducidad    => $value['fecha_caducidad'],
                            id_pedimento       => $value['id_pedimento'],
                            id_lote            => $value['id_lote'],
                            fecha_pedimento    => $value['fecha_pedimento'],
                            idMove             => $value['idMove'],
                            concepMove         => $value['concepMove'],
                            razon_social       => $value['razon_social'],
                            nombretienda       => $value['nombretienda'],

                            nombretiendapos    => $value['nombretiendapos'],
                            razon_socialpos    => $value['razon_socialpos'],
                            idMovepos          => $value['idMovepos'],
                            origen             => $value['origen'],
                        ); 

                }

                $almacenRRAnt           = $value['almacenRR'];
                $idubicacionAnt         = $value['idubicacion'];
                $codigoUAnt             = $value['codigo'];
                $caractAnt              = $value['caract'];                            
            }

            $arrExsR = array_reverse($arrExs);

            foreach ($arrExsR as $k => $va) { /// array para añadir axuiliares al cambiar de almacen, producto, ubicacion y caracteristica
                                    
                $contP      = $va['contP'];
                $contU      = $va['contU'];
                $contC      = $va['contC'];
                $contRR     = $va['contRR'];
                
                if($contRR >= $contRRAnt){
                    $auxRR = 1;
                }else{        /// Carac
                    $auxRR = 0; 
                }
                if($contC >= $contCAnt){
                    $auxC = 1;
                }else{        /// Carac
                    $auxC = 0; 
                }
                if($contU >= $contUAnt){
                    $auxU = $auxC = 1;
                }else{        /// Ubica
                    $auxU = 0; 
                }
                if($contP >= $contPAnt){
                    $auxP = $auxU = $auxC = 1;
                }else{        /// Produ
                    if($auxRR == 1){
                        $auxP = 1; 
                    }else{ //// CUANDO SE HACE UN FILTRADO  QUE MUESTRA UN SOLO PRODUCTO EN DIFERENTES ALMACENES
                        $auxP = 0;   
                    }
                }

                $arrExsFR[] = array(
                    id                  => $va['id'],
                    nombre              => $va['nombre'],           
                    fecha               => $va['fecha'],
                    cantidad            => $va['cantidad'],
                    codigo              => $va['codigo'],
                    traspasoaux         => $va['traspasoaux'],
                    tipo_traspaso       => $va['tipo_traspaso'],
                    unidad              => $va['unidad'],
                    caract              => $va['caract'],
                    exisUpro            => $va['exisUpro'],
                    exisUcar            => $va['exisUcar'],
                    exisUPRR            => $va['exisUPRR'],
                    exisUPRRI           => $va['exisUPRRI'],
                    exisU               => $va['exisU'],
                    exisAl              => $va['exisAl'],
                    contP               => $va['contP'],
                    contU               => $va['contU'],
                    contC               => $va['contC'],
                    almacenUbicacion    => $va['almacenUbicacion'],
                    almacenRR           => $va['almacenRR'],
                    nombreAlmacen       => $va['nombreAlmacen'],
                    idubicacion         => $va['idubicacion'],
                    auxP                => $auxP,
                    auxU                => $auxU,
                    auxC                => $auxC,
                    auxRR               => $auxRR,
                    totalE              => $va['totalE'],
                    totalS              => $va['totalS'],
                    codigo_sistema      => $va['codigo_sistema'],

                    cantidadI          => $va['cantidadI'],
                    exisUproI          => $va['exisUproI'],
                    exisUcarI          => $va['exisUcarI'],
                    exisUI             => $va['exisUI'],
                    exisAlI            => $va['exisAlI'],
                    totalEI            => $va['totalEI'],
                    totalSI            => $va['totalSI'],

                    entR               => $va['entR'],
                    entRI              => $va['entRI'],
                    salR               => $va['salR'],
                    salRI              => $va['salRI'],
                    costeo             => $va['costeo'],

                    no_pedimento       => $va['no_pedimento'],
                    no_aduana          => $va['no_aduana'],
                    tipo_cambio        => $va['tipo_cambio'],
                    no_lote            => $va['no_lote'],
                    fecha_fabricacion  => $va['fecha_fabricacion'],
                    fecha_caducidad    => $va['fecha_caducidad'],
                    id_pedimento       => $va['id_pedimento'],
                    id_lote            => $va['id_lote'],
                    fecha_pedimento    => $va['fecha_pedimento'],
                    idMove             => $va['idMove'],
                    concepMove         => $va['concepMove'],
                    razon_social       => $va['razon_social'],
                    nombretienda       => $va['nombretienda'],

                    nombretiendapos    => $va['nombretiendapos'],
                    razon_socialpos    => $va['razon_socialpos'],
                    idMovepos          => $va['idMovepos'],
                    origen             => $va['origen'],

                );

                $contRRAnt     = $va['contRR'];
                $countAnt      = $va['count'];
                $contPAnt      = $va['contP'];
                $contUAnt      = $va['contU'];
                $contCAnt      = $va['contC'];
            }

            $arrExsF = array_reverse($arrExsFR);

            ///// FILTRADO Y FORMATO ////////
            foreach ($arrExsF as $keys => $values) {
                $fechaF     = $values['fecha'];
                $almacenRR  = $values['almacenRR'];
                $id         = $values['id'];
                
                $serie = '';
                foreach ($inventarioActualU['series'] as $keys => $s) {
                    $idS        = $s['id'];
                    $serieS     = $s['series'];
                    if($idS == $id){
                        $serie = $serieS;   
                        break;
                    }                
                }
                
                $arrKarFil[] = array(
                        id                 => $values['id'],
                        nombre             => $values['nombre'],
                        fecha              => $values['fecha'],
                        cantidad           => $values['cantidad'],
                        codigo             => $values['codigo'],
                        traspasoaux        => $values['traspasoaux'],
                        tipo_traspaso      => $values['tipo_traspaso'],
                        unidad             => $values['unidad'],
                        nombreAlmacen      => $values['nombreAlmacen'],
                        caract             => $values['caract'],
                        exisUpro           => $values['exisUpro'],
                        exisUcar           => $values['exisUcar'],
                        exisUPRRI          => $values['exisUPRRI'],
                        exisUPRR           => $values['exisUPRR'],
                        exisU              => $values['exisU'],
                        exisAl             => $values['exisAl'],
                        contP              => $values['contP'],
                        contU              => $values['contU'],
                        contC              => $values['contC'],
                        contRR             => $values['contRR'],
                        almacenUbicacion   => $values['almacenUbicacion'],
                        almacenRR          => $values['almacenRR'],
                        nombreAlmacen      => $values['nombreAlmacen'],
                        idubicacion        => $values['idubicacion'],
                        totalE             => $values['totalE'],
                        totalS             => $values['totalS'],
                        codigo_sistema     => $values['codigo_sistema'],
                        cantidadI          => $values['cantidadI'],
                        exisUproI          => $values['exisUproI'],
                        exisUcarI          => $values['exisUcarI'],
                        exisUI             => $values['exisUI'],
                        exisAlI            => $values['exisAlI'],
                        totalEI            => $values['totalEI'],
                        totalSI            => $values['totalSI'],
                        auxP               => $values['auxP'],
                        auxU               => $values['auxU'],
                        auxC               => $values['auxC'],
                        auxRR              => $values['auxRR'],
                        costeo             => $values['costeo'],

                        no_pedimento       => $values['no_pedimento'],
                        no_aduana          => $values['no_aduana'],
                        tipo_cambio        => $values['tipo_cambio'],
                        no_lote            => $values['no_lote'],
                        fecha_fabricacion  => $values['fecha_fabricacion'],
                        fecha_caducidad    => $values['fecha_caducidad'],
                        id_pedimento       => $values['id_pedimento'],
                        id_lote            => $values['id_lote'],
                        fecha_pedimento    => $values['fecha_pedimento'],
                        idMove             => $values['idMove'],
                        concepMove         => $values['concepMove'],
                        razon_social       => $values['razon_social'],
                        nombretienda       => $values['nombretienda'],

                        nombretiendapos    => $values['nombretiendapos'],
                        razon_socialpos    => $values['razon_socialpos'],
                        idMovepos          => $values['idMovepos'],
                        origen             => $values['origen'],
                        series             => $serie,
                ); 
                $serie = '';
            }

            //$multArraiA = array('kardexF' => $arrKarFil, 'movs' => $arrExsF, 'invA' => $arrExsFUP, 'invU' => $arrExsFUU, 'invC' => $arrExsFUC, 'series' => $inventarioActualU['series']);
            $multArraiA = array('kardexF' => $arrKarFil, 'series' => $inventarioActualU['series'], 'pediT' => $inventarioActualU['pediT'], 'loteT' => $inventarioActualU['loteT']);
            echo json_encode($multArraiA);
            unset($multArraiA);
    }
///////    NEW MOVIMIENTOS INVENTARIO FIN ////////////////






    function vista_utilidad($objeto) {
        $productos = $this -> ReportesModel -> getProducts($objeto);
        $productos = $productos['rows'];

    // Consulta los empleado sy los regresa en un array
        //$empleados = $this -> ReportesModel -> listar_empleados($objeto);
        
    // Consulta las sucursales y las regresa en un array
        $sucursales = $this -> ReportesModel -> listar_sucursales($objeto);
        $sucursales = $sucursales['rows'];
        //$clientes = $this->ReportesModel->listar_clientes($objeto);
        
        require ('views/reportes/utilidad_vista.php');
    }

    function listar_utilidades2($objeto)
    {
        $utilidades = ($this->ReportesModel->listar_utilidades2($_GET['f_ini'], $_GET['f_fin'], $_GET['sucursal'], $_GET['producto'])) ;
        $utilidades = $utilidades['rows'];
$dona = [];
            foreach ($utilidades as $key => $value) {
                $tmp['label'] = $value['producto'];
                $tmp['value'] = $value['utilidad'];
                array_push($dona, $tmp);
            }
        

        require ('views/reportes/utilidad_listar.php');


    }
}
?>
