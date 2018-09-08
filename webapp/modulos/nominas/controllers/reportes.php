    <?php
    require('controllers/nominalibre.php');
    require("models/reportes.php");

    class Reportes extends Nominalibre
    {
     public $ReportesModel;
     public $NominalibreModel;
     function __construct()
     {
      $this->ReportesModel = new ReportesModel();
      $this->NominalibreModel = $this->ReportesModel;
      $this->ReportesModel->connect();
    }

    function __destruct()
    {
      $this->ReportesModel->close();
    }

    function reporteNominas(){
      $reporteNomi = $this->ReportesModel->listadoNominas($_REQUEST['fechainicial'],$_REQUEST['fechafinal'],$_REQUEST['empleados']);
      $empleados   = $this->ReportesModel->empleados();

      if(!$reporteNomi->num_rows>0){
       $reporteNomi=0;
     }
     require ("views/reportes/reportenomina.php");
   }

   function reporteEntradas(){
    $reporteEntradas = $this->ReportesModel->entradaSalidasEmple($_REQUEST['fechainicio'],$_REQUEST['fechafin'],$_REQUEST['empleados'],$_REQUEST['idtipop'],$_REQUEST['idnomp'],$_REQUEST['empleadosdos'],$_REQUEST['idEmpleado']);
    $logo1           = $this->ReportesModel->logo();
    $infoEmpresa     = $this->ReportesModel->infoEmpresa();
    $empleados       = $this->ReportesModel->empleados();
    $empleadosdos    = $this->ReportesModel->empleados();
    $nominas         = $this->ReportesModel->cargaPeriodo();
    $tipoperiodo = $this->ReportesModel->tipoperiodo();
    if(!$reporteEntradas->num_rows>0){
     $reporteEntradas=0;
   }
   $nominaActual  = $this->ReportesModel->nominasActivas();
   if($nominaActual['total']>0){
     $fi = $nominaActual["rows"][0]["fechainicio"];
     $ff = $nominaActual["rows"][0]["fechafin"];
   }else{
     $fi ='';
     $ff='';
   }
   require ("views/reportes/reporte_entr_sali_empleado.php");
 }

 function actHoras(){
  $reporte = $this->ReportesModel->listadoHoras($_POST['vali'],$_POST['input']);

}

function periodo(){
  $tipo=$_POST['idtipop'];
  $idnomp = $_POST['idnomp'];
  $nominasD = $this->ReportesModel->cargaPeriodoD($tipo, $idnomp);
}

function reporteIncidencias(){
  $reporteIncidencias = $this->ReportesModel->incidencias($_REQUEST['fechainicio'],$_REQUEST['fechafin'],$_REQUEST['empleados'],$_REQUEST['nombre'],$_REQUEST['nominas'],$_REQUEST['empleadosdos'],$_REQUEST['incidencias'],$_REQUEST['incidenciasdos']);


  $infoEmpresa = $this->ReportesModel->infoEmpresa();
  $logo1       = $this->ReportesModel->logo();

  $empleados            = $this->ReportesModel->empleados();
  $empleadosdos         = $this->ReportesModel->empleados();
  $nominas              = $this->ReportesModel->cargaPeriodo();
  $tipoperiodo          = $this->ReportesModel->tipoperiodo();
  $incidencias          = $this->ReportesModel->incidenciasfiltro();
  $incidenciasfiltrodos = $this->ReportesModel->incidenciasfiltro();
  if(!$reporteIncidencias->num_rows>0){
   $reporteIncidencias=0;
 }

 $nominaActual  = $this->ReportesModel->nominasActivas();
 if($nominaActual['total']>0){
   $fi = $nominaActual["rows"][0]["fechainicio"];
   $ff = $nominaActual["rows"][0]["fechafin"];

 }else{
   $fi ='';
   $ff='';
 }
 require ("views/reportes/reporte_incidencias.php");
}


/*R E P O RT E  D E   P R E N O M I N A *//*L A D O  V I S U A L */
function tablaReporteSobrerecibo(){

 $infoEmpresa = $this->ReportesModel->infoEmpresa();
 $logo1       = $this->ReportesModel->logo();

 $encabezadosReporteSobrerecibo   = $this->ReportesModel->cargaEncabezadosPercepcionesFiltros($_REQUEST['idtipop'],$_REQUEST['idnomp'],$_REQUEST['idEmpleado']);
 $reporteSobrerecibo   = $this->ReportesModel->cargaPercepcionesFiltros($_REQUEST['idtipop'],$_REQUEST['idnomp'],$_REQUEST['idEmpleado']);

 echo "<div class='table responsive col-sm-12 alert alert-info table-bordered ' style='overflow-y: scroll; overflow-x: auto;display: block;'; >";
 echo "<div class='container-fluid row' align='left'>";
 $url = explode('/modulos',$_SERVER['REQUEST_URI']);
 if($logo1 == 'logo.png') $logo1= 'x.png';
 $logo1 = str_replace(' ', '%20', $logo1);  
 echo "<img src=http://".$_SERVER['SERVER_NAME'].$url[0]."/netwarelog/archivos/1/organizaciones/$logo1 style='width: 180px;height: 35px;padding-right:30px'>";
 echo "<b style='font-weight:17.5' class='siz'>".$infoEmpresa['nombreorganizacion']."</b>";
 echo "<b style='font-weight:17.5' class='siz'>".$infoEmpresa['RFC']."</b>";
 echo "</div>";

 if ($_REQUEST['idtipop'] !="*" && $_REQUEST['idnomp']!="*"){

  echo "<p style='padding-top: 40px; font-weight: bold;text-align:center;padding-left: 15px;font-weight:17.5px;'  class='siz'>"."Periodo"." "."del".$_REQUEST['nomi']." al ".$_REQUEST['nomidos']."</p>";

  echo "<p style='font-weight: bold;text-align:center;padding-left: 15px;font-weight:17.5px;'  class='siz'>"."Nomina"." ".$_REQUEST['idtipop']."</p>";

}
else if ($_REQUEST['idtipop'] =="*" && $_REQUEST['idnomp']=="*") {

  echo"<p style='padding-top: 40px; font-weight: bold;text-align:center;padding-left: 15px;;font-weight:17.5px;'  class='siz'>"."Todos los periodos existentes"."</p>";
  echo"<p style='font-weight: bold;text-align:center;padding-left: 15px;font-weight:17.5px'  class='siz'>"."Todas las nominas existentes"."</p>";
}
echo "<br>";
echo"<table id='divVisualx' cellpadding=\"0\" class=\"taman table table-striped table-bordered dt-responsive nowrap\" width='100%'; style='border:solid .1em;font-size:12.5px;' border='1' bordercolor='#0000FF'>";
echo "<thead style='background-color:rgb(180,191,193);color:black;border:4px'>";
echo "<tr style='background-color:rgb(180,191,193);font-weight:bold'>";
$arrayCols  = array();
$arrayTipos = array();
$suma =0;
echo "<th class='colemple taman'>EMPLEADO</th>";
while($e = $encabezadosReporteSobrerecibo->fetch_object()) {  

  echo "<th class='coluno taman'>".$e->Descripcion."</th>";
  array_push($arrayCols, $e->idConcepto);
  array_push($arrayTipos, $e->idtipop);
}; 
echo "<th class='taman coluno'>TOTAL</th>";
echo "</tr>";
echo "</thead>";
echo "<tbody>";
while($e = $reporteSobrerecibo->fetch_object()) {

  $suma = 0;                       
  echo "<tr>";
  echo "<td class='colemple taman'>".$e->empleado."</td>";
  for ($f=0; $f < count($arrayCols); $f++){
    $fieldname =  "CONCEPTO_".(string)$arrayCols[$f];
    echo "<td class='coluno taman'>".$e->$fieldname."</td>";

        //$suma = $suma + ($arrayTipos[$f] == 1 ? ($e->$fieldname)  : ($e->$fieldname  * -1) );
    $suma = $suma + ($arrayTipos[$f] == 1 ? ($e->$fieldname)  : ($e->$fieldname  * 1) );
      // echo $arrayCols;
  }
  echo "<td class='taman coluno'>".$suma."</td>";
  echo "</tr>"; 
}
echo "</tbody>";
echo "</table>";
echo "<div>";
}



function reporteSobrerecibo(){
  
  $codigo         = $this->ReportesModel->empleados();
  $codigodos      = $this->ReportesModel->empleados();
  $empleadosdos   = $this->ReportesModel->empleados();
  $nominas        = $this->ReportesModel->cargaPeriodo();
  $tipoperiodo    = $this->ReportesModel->tipoperiodo();
  
  if ($_REQUEST['idtipop']=='' || $_REQUEST['idnomp']=='' ) {
   
  }else{
    $reporteSobrerecibo   = $this->ReportesModel->cargaPerceFiltros($_REQUEST['idtipop'],$_REQUEST['idnomp'],$_REQUEST['idEmpleado'],$_REQUEST['codigouno'],$_REQUEST['codigodos'],$_REQUEST['origen']);
  }
  require ("views/reportes/reporte_prenomina.php");

}

function cargaPerceFiltros(){
  $infoEmpresa   = $this->ReportesModel->infoEmpresa();
  $regPatronal   = $this->ReportesModel->infoRegPatronalRecibo();
  
  $logo1         = $this->ReportesModel->logo();
  $cargaPerceFiltros   = $this->ReportesModel->cargaPerceFiltros($_REQUEST['idtipop'],$_REQUEST['idnomp'],$_REQUEST['idEmpleado'],$_REQUEST['codigouno'],$_REQUEST['codigodos'],$_REQUEST['origen']);

  require ("views/reportes/PrenominaReport.php");
}
/*T E R M I N A   R E P O R T E   D E P R E N O M I N A*/


/* R E P O R T E   D E  A C U M U L A D O*/
function reporteAcumulado(){

  $reporteAcumulado = $this->ReportesModel->reporteAcumulado($_REQUEST['empleadosdos'],$_REQUEST['tipoperiodo'],$_REQUEST['nominas'],$_REQUEST['nominasdos'],$_REQUEST['origen'],$_REQUEST['idEmpleado']);

  $logo1           = $this->ReportesModel->logo();
  $infoEmpresa     = $this->ReportesModel->infoEmpresa();
  $empleados       = $this->ReportesModel->empleados();
  $empleadosdos    = $this->ReportesModel->empleados();
  $nominas         = $this->ReportesModel->cargaPeriodo();
  $tipoperiodo     = $this->ReportesModel->tipoperiodo();
  if(!$reporteAcumulado->num_rows>0){
   $reporteAcumulado=0;
 }
 require ("views/reportes/reporte_acumulado.php");

}
/*TERMINA REPORTE DE ACUMULADO*/




// R E S U M E N  A N A L I T I C O  P O R   D E P A R T A M E N T O
function resumenAnaliticoDepa(){

  $nominas         = $this->ReportesModel->cargaPeriodo();
  $tipoperiodo     = $this->ReportesModel->tipoperiodo();
  $departamentos   = $this->ReportesModel->departamentos();

  require ("views/reportes/resumenAnaliticoDep.php");
}


function resumenAnaliticoDep(){

  $resumenAnaliticoDep = $this->ReportesModel->resumenAnaliticoDep($_REQUEST['idtipop'],
    $_REQUEST['nomi'], $_REQUEST['nomidos'],$_REQUEST['depa'],$_REQUEST['idnomi']);


      if($resumenAnaliticoDep!=0){

       echo $resumenAnaliticoDep;
        while($in = $resumenAnaliticoDep->fetch_object()){

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


//R E P O R T E   D E   P R E N O M I N A   D E T A L L A D O
function reportePrenominaDetallado(){



   $reportePrenominaDetallado = $this->ReportesModel->reportePrenominaDetallado($_REQUEST['empleados'],$_REQUEST['nombre'],$_REQUEST['nominas']);

   $sumasconceptos =  $this->ReportesModel->sumasConceptos($_REQUEST['empleados'],$_REQUEST['nombre'],$_REQUEST['nominas']);

 $tipoperiodo     = $this->ReportesModel->tipoperiodo();
 $nominas         = $this->ReportesModel->cargaPeriodo();
 $empleados       = $this->ReportesModel->empleados();
 $logo1           = $this->ReportesModel->logo();
 $infoEmpresa     = $this->ReportesModel->infoEmpresa();

 if(!$reportePrenominaDetallado->num_rows>0){
   $reportePrenominaDetallado=0;
 }
 require ("views/reportes/reporte_prenomina_detallado.php");


}

}

?>