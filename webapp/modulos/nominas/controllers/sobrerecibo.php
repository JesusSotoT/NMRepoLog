<?php
require('controllers/prenomina.php');
require("models/sobrerecibo.php");

class Sobrerecibo extends Prenomina
{
	public $PrenominaModel;
	public $SobrereciboModel;
	
	function __construct()
	{
		
		$this->SobrereciboModel = new SobrereciboModel();
		$this->PrenominaModel = $this->SobrereciboModel;
		$this->SobrereciboModel->connect();
		
	}
	
	function __destruct()
	{
		
		$this->SobrereciboModel->close();
	}
	
	
	function actpercepcionDeduccion(){
		
		echo $this->SobrereciboModel->actualizarpercepcionesDeducciones($_POST['vali'],$_POST['input']);
		
	}
	function actualizarDias(){
		
		echo $this->SobrereciboModel->actualizarDias($_REQUEST['idnomp'], $_REQUEST['valor'], $_REQUEST['idempleado'], $_REQUEST['campo']);
		
	}
	
	
	function cargaPercepcion(){
		
		$cargaPercepDeduccion   = $this->SobrereciboModel->cargaPercepciones($_REQUEST['idEmpleado'],$_REQUEST['idnomp'] );
		if($cargaPercepDeduccion>0){
			while($e = $cargaPercepDeduccion->fetch_object()){
				
				if ($e->valor=='1' || $e->concpAgre=='1') {
					echo "<tr class='uno' style='height:25px;' onMouseDown='adicional(".$e->idEmpleado.");' id='".$e->idEmpleado."'>
					<td align='center'>".$e->concepto."</td>
					<td align='left'>".mb_strtoupper($e->descripcion,'utf-8')."</td>
					<td id='".$e->idcal."_1' onclick=editar('".$e->idcal."_1');	 align='right' class='importePercepciones' title='Click para editar'>".number_format($e->importe,2,'.',',')."</td>
					<td class='accOculta' style='text-align: center;'><a title='Eliminar' href='#' class='btn btn-danger btn-xs active' onclick=accionEliminarConceptoSobre('".$e->idconfpre."')><span class='fa fa-trash-o fa-lg' id='".$e->idconfpre."'></span></a>	</td>
				</tr>";	
			}	else{
				echo "<tr  class='uno' style='height:25px;' onMouseDown='adicional(".$e->idEmpleado.");' id='".$e->idEmpleado."'>
				<td align='center'>".$e->concepto."</td>
				<td align='left'>".mb_strtoupper($e->descripcion,'utf-8')."</td>
				<td align='right' class='importePercepciones'>".number_format($e->importe,2,'.',',')."</td>
				<td class='accOculta' style='text-align: center;'><a  title='Eliminar' href='#' class='btn btn-danger btn-xs active' onclick=accionEliminarConceptoSobre('".$e->idconfpre."')><span class='fa fa-trash-o fa-lg' id='".$e->idconfpre."'></span></a>	</td>
			</tr>”;	
		</tr>";	
	}
}
}
}

function cargaDeduccion(){
	$cargaDeduccion   = $this->SobrereciboModel->cargaDeduccion($_REQUEST['idEmpleado'],$_REQUEST['idnomp']);
	if($cargaDeduccion>0){
		while($e = $cargaDeduccion->fetch_object()){

			if ($e->valor=='1' || $e->concpAgre=='1') {
				echo "<tr   class='uno' style='height:25px;' onMouseDown='adicional(".$e->idEmpleado.");' id='".$e->idEmpleado."'>
				<td align='center'>".$e->concepto."</td>
				<td align='left'>".mb_strtoupper($e->descripcion,'utf-8')."</td>
				<td align='right' id='".$e->idcal."_1'  onclick=editar('".$e->idcal."_1'); class='importeDeducciones' onkeyup='sumaPercepciones();' title='Click para editar'>".number_format($e->importe,2,'.',',')."</td>

				<td class='accOculta' style='text-align: center;'><a title='Eliminar' href='#' class='btn btn-danger btn-xs active' onclick=accionEliminarConceptoSobre('".$e->idconfpre."')><span class='fa fa-trash-o fa-lg' id='".$e->idconfpre."'></span></a>	</td>
			</tr>";	
		}
		else{
			echo "<tr  class='uno' style='height:25px;'  onMouseDown='adicional(".$e->idEmpleado.");' id='".$e->idEmpleado."'>
			<td align='center'>".$e->concepto."</td>
			<td align='left'>".mb_strtoupper($e->descripcion,'utf-8')."</td>
			<td align='right' id='".$e->idcal."_1' class='importeDeducciones' >".number_format($e->importe,2,'.',',')."</td>
			
			<td class='accOculta' style='text-align: center;'><a title='Eliminar' href='#' class='btn btn-danger btn-xs active' onclick=accionEliminarConceptoSobre('".$e->idconfpre."')><span class='fa fa-trash-o fa-lg' id='".$e->idconfpre."'></span></a>	</td>
		</tr>";
	}
}	
}
}

function sobrereciboview(){

	$logo1                    = $this->SobrereciboModel->logo();
	$infoEmpresa              = $this->SobrereciboModel->infoEmpresa();
	$regPatronal              = $this->SobrereciboModel->infoRegPatronal();
	$nominaActiva 		      = $this->PrenominaModel->nominaActiva();
	$listaEmpleados 		  = $this->SobrereciboModel->empleadoSobreRecibo();
	$tipoconcepto		      = $this->PrenominaModel->tipoconcepto();
	$listaConceptos 		  = $this->PrenominaModel->listaConceptos();
	$listaMovPermanentes	  = $this->SobrereciboModel->listaMovPermanentes();
	$listameses 			  = $this->SobrereciboModel->listameses();
	$listaincapacidades 	  = $this->SobrereciboModel->listaIncapacidades();
	$listaFonacot 			  = $this->SobrereciboModel->listaFonacot();
	$listaInfonavit			  = $this->SobrereciboModel->listaInfonavit();
	$incapacidades			  = $this->SobrereciboModel->incapacidades();
	$secuelaconsecuencia      = $this->SobrereciboModel->secuelaconsecuencia();
	$controlIncapacidad		  = $this->SobrereciboModel->controlIncapacidad();
	$ramoIncapacidad		  = $this->SobrereciboModel->ramoIncapacidad();

//AGREGAR PERCEPCION O DEDUCCION EN SOBRERECIBO
	$selectperce		     = $this->SobrereciboModel->selectperce();
	$selectdedu		         = $this->SobrereciboModel->selectdedu();
	   // $accionEliminarConceptoSobre = $this->SobrereciboModel->accionEliminarConceptoSobre($_REQUEST['idconcepto']);
	

	if($_REQUEST['inf']){
		$empleado = ($_REQUEST['inf']);
			//echo $empleado;
	}
	require("views/sobrerecibo/sobrerecibo.php");

}

//INSERTAR PERCEPCION O DEDUCCION EN SOBRERECIBO


function guardarPercDedu(){
	echo $guardarPercDedu = $this->SobrereciboModel->guardarPercDedu($_POST['empleado'],$_POST['percepcion'],$_POST['deduccion'],$_POST['nominaactiva']);
}



function datosEmpleado(){

	$empleado = $this->SobrereciboModel->empleadoSobreRecibodetalle($_REQUEST['idEmpleado'],$_REQUEST['idnomp']);
	echo $empleado->idEmpleado."/".$empleado->idDep."/".$empleado->diaspagados."/".$empleado->diaslaborados."/".$empleado->codigo."/".$empleado->rfc."/".$empleado->nombre."/"."Del"." ".$empleado->fechainicio." al ".$empleado->fechafin."/".$empleado->curp."/".$empleado->nss."/".$empleado->apellidoPaterno." ".$empleado->apellidoMaterno." ".$empleado->nombreEmpleado."/".$empleado->descripcion."/".number_format($empleado->salario,2,'.',',')."/".number_format($empleado->salario,2,'.',',')."/".$empleado->fechaActiva."/".$empleado->numnomina."/".$empleado->horas."/".$empleado->diaslabproporcion."/".$empleado->diasvac."/".$empleado->diasfestivo;
	

}

function listaconceptotipo(){
	$lista = $this->PrenominaModel->conceptosprenomina($_REQUEST['tipo']);
	while($c = $lista->fetch_object()){
		echo "<option value='".$c->idconcepto."'>".$c->concepto." - ".$c->descripcion."</option>";
	}
}
function permanentesEdicion(){
	$array = array();
	$listaMovPermanentesXEmpleado = $this->SobrereciboModel->listaMovPermanentesXEmpleadomovimiento($_REQUEST['idempleado'],$_REQUEST['idmovper']);
	while($p = $listaMovPermanentesXEmpleado->fetch_object()){
		$array[] = array('idmovper'		=> $p->idmovper,
			'descripcion' 	                => utf8_encode($p->descripcion),
			'idtipo'		                => $p->idtipo,
			'idconcepto' 	                => $p->idconcepto,
			'fechainicio'                   => $p->fechainicio,
			'importe' 		                => $p->importe,
			'vecesaplica'                   => $p->vecesaplica,
			'montolimite' 	                => $p->montolimite,
			'montoacumulado'                => $p->montoacumulado,
			'fecharegistro'                 => $p->fecharegistro,
			'numerocontrol'                 => $p->numerocontrol,
			'estatus' 		                => $p->estatus,
			'imporvalor'	                => $p->imporvalor,
			'vecesaplicado'                 => $p->vecesaplicado
			);
	}
	echo '' . json_encode($array) . '';
	
}
function infonavitEdicion(){
	$array = array();
	$infonavitXEmpleado = $this->SobrereciboModel->infonavitXEmpleadomovimiento($_REQUEST['idempleado'],$_REQUEST['idmovper']);
	while($p = $infonavitXEmpleado->fetch_object()){
		$array[] = array('idinfonavit'		=> $p->idinfonavit,
			'numinfonavit' 		                => utf8_encode($p->numinfonavit),
			'descripcion'		                => $p->descripcion,
			'tipocredito' 	                    => $p->tipocredito,
			'importecredito'   	                => $p->importecredito,
			'incluirpagoseguro'                 => $p->incluirpagoseguro,
			'fechaaplicacion' 	                => $p->fechaaplicacion,
			'montoacumulado' 	                => $p->montoacumulado,
			'vecesaplicado'		                => $p->vecesaplicado,
			'fecharegistro' 	                => $p->fecharegistro,
			'estatus' 			                => $p->estatus,
			'pagodeseguro' 		                => $p->pagodeseguro,
			'factormensual' 	                => $p->importecreditofactormensual
			);
	}
	echo '' . json_encode($array) . '';

}
function fonacotEdicion(){
	$array = array();
	$fonacotXEmpleado = $this->SobrereciboModel->fonacotXEmpleadomovimiento($_REQUEST['idempleado'],$_REQUEST['idmovper']);
	while($p = $fonacotXEmpleado->fetch_object()){
		$array[] = array('idfonacotsobre'	    => $p->idfonacotsobre,
			'numcredito' 		                    => ($p->numcredito),
			'descripcion'		                    => $p->descripcion,
			'mes' 					                => $p->mes,
			'ejercicio' 			                => $p->ejercicio,
			'calculoretencion' 		                => $p->calculoretencion,
			'importecredito' 		                => $p->importecredito,
			'retencionmensual' 		                => $p->retencionmensual,
			'pagohechosotros'		                => $p->pagohechosotros,
			'montoacumuladoretenido'                => $p->montoacumuladoretenido,
			'saldo' 				                => $p->saldo,
			'estatus' 				                => $p->estatus,
			'obervaciones'			                => $p->obervaciones
			);
	}
	echo '' . json_encode($array) . '';

}
function incapacidadesEdicion(){
	$array = array();
	$incapacidadesXEmpleado = $this->SobrereciboModel->incapacidadesXEmpleadomovimiento($_REQUEST['idempleado'],$_REQUEST['idmovper']);
	while($p = $incapacidadesXEmpleado->fetch_object()){
		$array[] = array('idincapacidadsobre'=> $p->idincapacidadsobre,
			'folio' 				             => ($p->folio),
			'idtipoincidencia'		             => $p->idtipoincidencia,
			'diasautorizados'		             => $p->diasautorizados,
			'fechainicio' 			             => $p->fechainicio,
			'ramoseguro' 			             => $p->ramoseguro,
			'tiporiesgo' 			             => $p->tiporiesgo,
			'porcentajeincapacidad'              => $p->porcentajeincapacidad,
			'idsecuela'				             => $p->idsecuela,
			'montoacumuladoretenido'             => $p->montoacumuladoretenido,
			'idcontrol' 			             => $p->idcontrol,
			'descripcion' 			             => $p->descripcion,
			);
	}
	echo '' . json_encode($array) . '';

}
function vacacionesEdicion(){
	$array = array();
	$vacacionesXEmpleado = $this->SobrereciboModel->vacacionesXEmpleadomovimiento($_REQUEST['idempleado'],$_REQUEST['idmovper']);
	while($p = $vacacionesXEmpleado->fetch_object()){
		   $array[] = array('idvacasobrerecibo'	=> $p->idvacasobrerecibo,
			'tipocaptura' 			                => ($p->tipocaptura),
			'fechainicial'			                => $p->fechainicial,
			'fechafinal'		  	                => $p->fechafinal,
			'fechapago' 			                => $p->fechapago,
			'diasdescansoseptimo'	                => $p->diasdescansoseptimo,
			'diasvacaciones' 		                => $p->diasvacaciones,
			'diasvacprimavac' 		                => $p->diasvacprimavac,
			'diasprimaacumulado'	                => $p->diasprimaacumulado,
			'vacacionespendientes'	                => $p->vacacionespendientes,
			'diasprimapendiente'	                => $p->diasprimapendiente,
			'vacacionesacumuladas'	                => $p->vacacionesacumuladas
			);
	}
	echo '' . json_encode($array) . '';

}
function permanentesEmpleado(){
	$array = array();
	$listaMovPermanentesXEmpleado = $this->SobrereciboModel->listaMovPermanentesXEmpleado($_REQUEST['idempleado']);
	if( $listaMovPermanentesXEmpleado->num_rows>0 ){
		while($p = $listaMovPermanentesXEmpleado->fetch_object()){
			echo '<li>
			<a data-toggle="tab" href="" onclick="javascript:permanentesEdicion('.$p->idmovper.')">
				'.strtoupper($p->descripcion).'
			</a>
		</li>';

	}
}else{
	echo '
	<li>
		<a data-toggle="tab" href="">
			No tiene Movimientos agregados 
		</a>
	</li>';
} 

}
function infonavitEmpleado(){
	$array = array();
	$listaInfonavitXEmpleado = $this->SobrereciboModel->listaInfonavitXEmpleado($_REQUEST['idempleado']);
	if( $listaInfonavitXEmpleado->num_rows>0 ){
		while($p = $listaInfonavitXEmpleado->fetch_object()){
			echo '<li>
			<a data-toggle="tab" href="" onclick="javascript:infonavitEdicion('.$p->idinfonavit.')">
				'.strtoupper($p->numinfonavit. " - ".$p->descripcion).'
			</a>
		</li>';

	}
}else{
	echo '
	<li>
		<a data-toggle="tab" href="">
			No tiene Creditos agregados 
		</a>
	</li>';
} 


}
function fonacotEmpleado(){
	$array = array();
	$listaFonacotXEmpleado = $this->SobrereciboModel->listaFonacotXEmpleado($_REQUEST['idempleado']);
	if( $listaFonacotXEmpleado->num_rows>0 ){
		while($p = $listaFonacotXEmpleado->fetch_object()){
			echo '<li>
			<a data-toggle="tab" href="" onclick="javascript:fonacotEdicion('.$p->idfonacotsobre.')">
				'.$p->numcredito.'
			</a>
		</li>';

	}
}else{
	echo '
	<li>
		<a data-toggle="tab" href="">
			No tiene Creditos agregados 
		</a>
	</li>';
} 

}
function incapacidadesEmpleado(){
	$array = array();
	$listaIncapacidadesXEmpleado = $this->SobrereciboModel->listaIncapacidadesXEmpleado($_REQUEST['idempleado']);
	if( $listaIncapacidadesXEmpleado->num_rows>0 ){
		while($p = $listaIncapacidadesXEmpleado->fetch_object()){
			echo '<li>
			<a data-toggle="tab" href="" onclick="javascript:incapacidadEdicion('.$p->idincapacidadsobre.')">
				'.$p->folio.'
			</a>
		</li>';

	}
}else{
	echo '
	<li>
		<a data-toggle="tab" href="">
			No tiene Incapacidades agregadas 
		</a>
	</li>';
} 


}
function vacacionesEmpleado(){
	$array = array();
	$vacacionesXEmpleado = $this->SobrereciboModel->listaVacacionesXEmpleado($_REQUEST['idempleado']);
	if( $vacacionesXEmpleado->num_rows>0 ){
		while($p = $vacacionesXEmpleado->fetch_object()){
			echo '<li>
			<a data-toggle="tab" href="" onclick="javascript:vacacionesEdicion('.$p->idvacasobrerecibo.')">
				'.$p->fechainicial.' | '.$p->fechafinal.'
			</a>
		</li>';

	}
}else{
	echo '
	<li>
		<a data-toggle="tab" href="">
			No tiene periodos vacacionales agregados 
		</a>
	</li>';
} 


}
function almacenaPermanentes(){
	if( $_REQUEST['opc'] == 1){
		echo $this->SobrereciboModel->almacenaPermanente($_REQUEST['nominaactiva'],$_REQUEST['descripcion'], $_REQUEST['tipoconcepto'], $_REQUEST['concepto'], $_REQUEST['fechaaplicacionpermanente'], $_REQUEST['importeOvalor'], $_REQUEST['imporvalor'], $_REQUEST['vecesaplica'], $_REQUEST['vecesaplicadopermanente'], $_REQUEST['montolimite'], $_REQUEST['montoacumulado'], $_REQUEST['fecharegistropermanente'], $_REQUEST['numcontrol'], $_REQUEST['estatuspermanente'], $_REQUEST['idempleado']);
	}else{
		echo $this->SobrereciboModel->updatePermanente($_REQUEST['nominaactiva'],$_REQUEST['descripcion'], $_REQUEST['tipoconcepto'], $_REQUEST['concepto'], $_REQUEST['fechaaplicacionpermanente'], $_REQUEST['importeOvalor'], $_REQUEST['imporvalor'], $_REQUEST['vecesaplica'], $_REQUEST['vecesaplicadopermanente'], $_REQUEST['montolimite'], $_REQUEST['montoacumulado'], $_REQUEST['fecharegistropermanente'], $_REQUEST['numcontrol'], $_REQUEST['estatuspermanente'], $_REQUEST['idmovper']);
		
	}
}
function almacenaInfonavit(){
	if( $_REQUEST['opc'] == 1){
		echo $this->SobrereciboModel->almacenaInfonavit($_REQUEST['nominaactiva'],$_REQUEST['numinfonavit'],$_REQUEST['descripcioninfonavit'], $_REQUEST['tipocreditoinfonavit'],$_REQUEST['factormensual'],$_REQUEST['pagodeseguro'], $_REQUEST['fechaaplicacioninfonavit'], $_REQUEST['montoacumulado'], $_REQUEST['vecesaplicadoinfonavit'], $_REQUEST['fecharegistroinfonavit'], $_REQUEST['estatusinfonavit'], $_REQUEST['idempleado']);
	}else{
		echo $this->SobrereciboModel->updateInfonavit($_REQUEST['nominaactiva'],$_REQUEST['numinfonavit'],$_REQUEST['descripcioninfonavit'], $_REQUEST['tipocreditoinfonavit'],$_REQUEST['factormensual'],$_REQUEST['pagodeseguro'], $_REQUEST['fechaaplicacioninfonavit'], $_REQUEST['montoacumulado'], $_REQUEST['vecesaplicadoinfonavit'], $_REQUEST['fecharegistroinfonavit'], $_REQUEST['estatusinfonavit'], $_REQUEST['idinfonavit']);
		
	}
}
function almacenaFonacot(){
	if( $_REQUEST['opc'] == 1){
		echo $this->SobrereciboModel->almacenaFonacot($_REQUEST['nominaactiva'],$_REQUEST['numcreditofonacot'],$_REQUEST['descripcionfonacot'], $_REQUEST['mesfonacot'],$_REQUEST['ejerciciofonacot'],$_REQUEST['calculoretencion'],$_REQUEST['importecreditofonacot'], $_REQUEST['retencionmensual'], $_REQUEST['pagohechosotros'], $_REQUEST['saldofonacot'], $_REQUEST['estatusfonacot'],$_REQUEST['observacionesfonacot'], $_REQUEST['idempleado']);
	}else{
		echo $this->SobrereciboModel->updateFonacot($_REQUEST['nominaactiva'],$_REQUEST['numcreditofonacot'],$_REQUEST['descripcionfonacot'], $_REQUEST['mesfonacot'],$_REQUEST['ejerciciofonacot'],$_REQUEST['calculoretencion'], $_REQUEST['importecreditofonacot'],$_REQUEST['retencionmensual'], $_REQUEST['pagohechosotros'], $_REQUEST['saldofonacot'], $_REQUEST['estatusfonacot'], $_REQUEST['observacionesfonacot'],$_REQUEST['idfonacot']);
		
	}
}
function almacenaIncapacidad(){
	if( $_REQUEST['opc'] == 1){
		echo $this->SobrereciboModel->almacenaIncapacidad($_REQUEST['nominaactiva'],$_REQUEST['folioincapacidad'],$_REQUEST['tipoincidenciaincapacidad'], $_REQUEST['diasautorizadosincapacidad'],$_REQUEST['fechainicioincapacidad'],$_REQUEST['ramoincapacidad'],$_REQUEST['tiporiesgoincapacidad'], $_REQUEST['porcentajeincapacidad'], $_REQUEST['secuelaincapacidad'], $_REQUEST['controlincapacidad'], $_REQUEST['hechosincapacidad'], $_REQUEST['idempleado']);
	}else{
		echo $this->SobrereciboModel->updateIncapacidad($_REQUEST['nominaactiva'],$_REQUEST['folioincapacidad'],$_REQUEST['tipoincidenciaincapacidad'], $_REQUEST['diasautorizadosincapacidad'],$_REQUEST['fechainicioincapacidad'],$_REQUEST['ramoincapacidad'], $_REQUEST['tiporiesgoincapacidad'],$_REQUEST['porcentajeincapacidad'], $_REQUEST['secuelaincapacidad'], $_REQUEST['controlincapacidad'], $_REQUEST['hechosincapacidad'],$_REQUEST['idincapacidadsobre'], $_REQUEST['idempleado']);
		
	}
}
function almacenaVacaciones(){

	if(empty($_REQUEST['diasdescansoseptimo'])){$_REQUEST['diasdescansoseptimo']=0;}
	if(empty($_REQUEST['diasvacprimavac'])){$_REQUEST['diasvacprimavac']=0;}
	if( $_REQUEST['opc'] == 1){
		echo $this->SobrereciboModel->almacenaVacaciones($_REQUEST['nominaactiva'],$_REQUEST['fechaactiva'],$_REQUEST['tipocapturavacaciones'],$_REQUEST['fechainiciovacaciones'], $_REQUEST['fechafinalvacaciones'],$_REQUEST['fechapagovacaciones'],$_REQUEST['diasdescansoseptimo'],$_REQUEST['diasvacaciones'], $_REQUEST['diasvacprimavac'], $_REQUEST['idempleado']);
	}else{
		echo $this->SobrereciboModel->updateVacaciones($_REQUEST['nominaactiva'],$_REQUEST['fechaactiva'],$_REQUEST['tipocapturavacaciones'],$_REQUEST['fechainiciovacaciones'], $_REQUEST['fechafinalvacaciones'],$_REQUEST['fechapagovacaciones'],$_REQUEST['diasdescansoseptimo'], $_REQUEST['diasvacaciones'],$_REQUEST['diasvacprimavac'],$_REQUEST['idvacasobrerecibo'], $_REQUEST['idempleado']);
	}
	$this->SobrereciboModel->updateVacacionesAcumulado($_REQUEST['vacacionesacumuladas'], $_REQUEST['primaacumuladovacaciones'], $_REQUEST['vacapendientevacaciones'], $_REQUEST['diaprimapendientevacaciones'], $_REQUEST['idempleado']);
}
function eliminaMov(){
	echo $this->SobrereciboModel->eliminaMov($_REQUEST['idmovper'],$_REQUEST['opc']);
	
}
function calculaVaciones(){
	$anosAntiguedad = $this->SobrereciboModel->antiguedadXEmpleado($_REQUEST['idempleado'],$_REQUEST['pinicial'],$_REQUEST['pfinal'],$_REQUEST['fechainiciavac']);
	$array[] = array('anos'				=> $anosAntiguedad->anos,
		'diastomados' 		=> $anosAntiguedad->diastomados,
		'diasvacacionesley'	=> $anosAntiguedad->diasvacacionesley,
		'diasrestantes'		=> $anosAntiguedad->diasrestantes,
		'diasprima'			=> $anosAntiguedad->diasprima
		);

	echo '' . json_encode($array) . '';
}

function periodo(){
	$tipo=$_POST['idtipo'];
	$nominasD = $this->SobrereciboModel->cargaPeriodoD($tipo);
}

//C A L C U L O   P T U 
function calculoptuview(){
	$cargadeConceptos  = $this->SobrereciboModel->cargadeConceptos();
	$conceptos         = $this->SobrereciboModel->conceptos();
	$deducciones       = $this->SobrereciboModel->conceptosPorTipo(2);
	

	if ($_REQUEST['montoRepartir'] != ""){		
		$calculoptuview = $this->SobrereciboModel->calculoptuview($_REQUEST['montoRepartir'], $_REQUEST['descontarincidencias'],$_REQUEST['ptu']);  
	}
	require("views/prenomina/calculo_ptu.php");
}

function existePTU(){
	$sql      = $this->SobrereciboModel->existePTU(); 
	$encode   = array();
	while($in = $sql->fetch_assoc()){
		$encode[] = $in;
	}
	echo json_encode($encode);
}

function guardarPTU(){
	echo $this->SobrereciboModel->guardarPTU($_REQUEST['montoRepartir'], $_REQUEST['descontarincidencias'],$_REQUEST['ejercicio'],$_REQUEST['ptu']); 
}

function obtenAcumulado(){
	$acumulado =  $this->SobrereciboModel->obtenAcumulado(); 
	$in        = $acumulado->fetch_assoc();
	echo $in["total_importe"];
}
//T E R M IN A   C A L C U L O   P T U


//ELIMINAR CONCEPTO DE SOBRERECIBO	
function accionEliminarConceptoSobre(){ 

		 echo $accionEliminarConceptoSobre = $this->SobrereciboModel->accionEliminarConceptoSobre($_POST['empleado'],$_POST['concepto']);
	}



//A U M E N T O  S A L A R I O S 
	function aumentoSalarios(){

		$registroPatronal  = $this->SobrereciboModel->registropatronal();
		$departamentos     = $this->SobrereciboModel->departamentos();
		$empleados         = $this->SobrereciboModel->empleados();
		$tipoperiodo       = $this->SobrereciboModel->tipoperiodo();
		
		
		require("views/prenomina/aumentoSalarios.php");
	}
	function aumentarsala(){

		if ($_REQUEST['montosalario'] != ""){

			$cargartablaaumento = $this->SobrereciboModel->montoAumeSalario($_REQUEST['checkbox1'],$_REQUEST['emple'],$_REQUEST['radio'],$_REQUEST['montosalario']);

			if($cargartablaaumento!=0){

				while($in = $cargartablaaumento->fetch_object()){

					echo"<tr>
					<td>".($in->codigo)."</td>
					<td align='right'>".(number_format($in->salarioAnterior,2,'.',','))."</td>
					<td align='right'>".(number_format($in->salarioNuevo,2,'.',','))."</td>
					<td align='right'>".(number_format($in->sbcfija,2,'.',','))."</td>
					<td align='right'>".(number_format($in->sdi,2,'.',','))."</td>
					<td>".($in->nombreEmpleado)." ".($in->apellidoPaterno)." ".($in->apellidoMaterno)."</</td> 
				</tr>";
			} 
		}
	}
}




	function cargaEmple(){
		$filtroemple = $this->SobrereciboModel->cargaEmple($_POST['registro'],$_POST['idtipop'],$_POST['dep']);
	}

	function guardarAumentoSalaHisto(){
		echo $cargartablaaumento = $this->SobrereciboModel->guardarAumentoSalaHisto($_REQUEST['checkbox1'],$_REQUEST['emple'],$_REQUEST['radio'],$_REQUEST['montosalario'],$_REQUEST['txtfecha'], $_REQUEST['idnomp'], $_REQUEST['idtipop']);
	}

	function existeAumento(){
		echo $sql      = $this->SobrereciboModel->existeAumento($_REQUEST['txtfecha'],$_REQUEST['pru']); 	
	}	
//T É R M I N A   A U M E N T O   D E   S A L A R I O S 

//C O N C I L I A C I O N   D E   R F C  


function conciliacion(){
  
	$empleadosConc = $this->SobrereciboModel->conciliacion();
	$empleadosConcdos = $this->SobrereciboModel->conciliacion();

// select * from nomi_empleados where activo=-1;
  require("views/prenomina/conciliacion.php");

}

function guardarRespuesta(){

	 $guardarRespuesta=$this->Sobrerecibo->guardarRespuesta($_REQUEST['tableData']);





}

}
?>


