<?php

//Carga la funciones comunes top y footer
require('common.php');

//Carga el modelo para este controlador
require("models/ordenservicio.php");

class ordenservicio extends Common
{
	public $ordenservicioModel;

	function __construct()
	{
		//Se crea el objeto que instancia al modelo que se va a utilizar

		$this->ordenservicioModel = new ordenservicioModel();
		$this->ordenservicioModel->connect();
	}

	function __destruct()
	{
		//Se destruye el objeto que instancia al modelo que se va a utilizar
		$this->ordenservicioModel->close();
	}

	//Funcion que genera la vista inicial donde se presentan las polizas del periodo
  /*
	function lista()
	{
		require('views/ordenservicio/ordenservicio.php');
	}
  */
	function convenios()
	{
		require('views/ordenservicio/convenios.php');
	}
  function evidenciarequerimientos()
  { 
    require('views/ordenservicio/requerimientosevidencias.php');
  }
  function solicitudes()
  {
    require('views/ordenservicio/solicitudes.php');
  }
  function ordenservicio()
  {
    require('views/ordenservicio/ordenservicio.php');
  }

  function licencias()
  {
    require('views/ordenservicio/licencias.php');
  }

  function bitacora()
  {
    require('views/ordenservicio/bitacora.php');
  }
  
  function anticipos()
  {
    require('views/ordenservicio/anticipos.php');
  } 
  function altauni()
  {
    require('views/ordenservicio/unidades.php');
  }

/*
    function lista2(){
      $lista2 = $this->ordenservicioModel->listar2();
      echo json_encode($lista2);
    }
*/


	////////////////// LISTAR //////////////////////////////////////////////
  //
  ///
  ///
  ///
      function listaConvenios(){
      $listaConvenios = $this->ordenservicioModel->listarConvenios();
      echo json_encode($listaConvenios);
    }
    function listaConveniosSol(){
      $idsolicitud = $_POST['folio'];
      $listaConveniosSol = $this->ordenservicioModel->listarConveniosSol($idsolicitud);
      echo json_encode($listaConveniosSol);
    }


    function listaRelSolCon(){
      $folio = $_POST['folio'];
      $id = $_POST['id'];
      $incluir =$_POST['incluir'];
      $idsolicitud = $_POST ['idsolicitud'];
      $listaRelSolCon = $this->ordenservicioModel->listarRelSolCon($idsolicitud,$id,$folio,$incluir);
      echo json_encode($listaRelSolCon);
    }


    function listaConveniosEdit(){
      $idconvenio = $_POST['id'];
      $listaConveniosEdit = $this->ordenservicioModel->listarConveniosEdit($idconvenio);
      echo json_encode($listaConveniosEdit);
    }

    function cargaConvenioNew(){
        $idsolicitud = $_POST['idsolicitud'];
        $lastid = $_POST['lastid'];
      $cargaConvenioNew = $this->ordenservicioModel->cargaConvenioNewM($idsolicitud,$lastid);
      echo json_encode($cargaConvenioNew);
    }

    function listaClientes(){
     	 $listaClientes = $this->ordenservicioModel->listarClientes();
     	 echo json_encode($listaClientes);
    }
    function listaClientesconv(){
        $lastid = $_POST['lastid'];
       $listaClientes = $this->ordenservicioModel->listarClientesconv($lastid);
       echo json_encode($listaClientes);

    }
     function listaDestinatarios(){
       $listaDestinatarios = $this->ordenservicioModel->listarDestinatarios();
       echo json_encode($listaDestinatarios);
    }
    

    function listaOperadores(){
          
        $listaOperadores = $this->ordenservicioModel->listarOperadores();
        echo json_encode($listaOperadores);
    }
    function listaOperadores1(){
       $idOS = $_POST['idOS'];
       $listaOperadores1 = $this->ordenservicioModel->listarOperadores1($idOS);
       echo json_encode($listaOperadores1);
    }
    function listaClientes1(){
       $idcliente = $_POST['idcliente'];
       $listaClientes1 = $this->ordenservicioModel->listarClientes1($idcliente);
       echo json_encode($listaClientes1);
    }
     function listaDestinatarios1(){
       $iddestinatario = $_POST['iddestinatario'];
       $listaDestinatarios1 = $this->ordenservicioModel->listarDestinatarios1($iddestinatario);
       echo json_encode($listaDestinatarios1);
    }
    function listaCapacidad(){
     	 $listaCapacidad = $this->ordenservicioModel->listarCapacidad();
     	 echo json_encode($listaCapacidad);
    }
    function listaUnidades(){
      $idordenservicio = $_POST['idordenservicio'];
      //$espesifico = $_POST['espesifico'];
      $idunidad = $_POST['idunidad'];
      $listaUnidades = $this->ordenservicioModel->listarUnidades($idordenservicio,$idunidad);
      echo json_encode($listaUnidades);
    }
    function listaCajas(){
      $idordenservicio = $_POST['idordenservicio'];
      $espesifico = $_POST['espesifico'];
      $idcajatractor = $_POST['idcajatractor'];
      $listaCajas = $this->ordenservicioModel->listarCajas($idordenservicio,$espesifico,$idcajatractor);
      echo json_encode($listaCajas);
    }
    function listaEstados(){
     	 $listaEstados = $this->ordenservicioModel->listarEstados();
     	 echo json_encode($listaEstados);
    }

    function listaEmbalaje(){
       $listaEmbalaje = $this->ordenservicioModel->listarEmbalaje();
       echo json_encode($listaEmbalaje);
    }

    function listaFabricante(){
      $listaFabricante = $this->ordenservicioModel->listarFabricante();
      echo json_encode($listaFabricante);
    }


    function listaTipocarga(){
       $listaTipocarga = $this->ordenservicioModel->listarTipocarga();
       echo json_encode($listaTipocarga);
    } 

    function listaTipounidad(){
       $listaTipounidad = $this->ordenservicioModel->listarTipounidad();
       echo json_encode($listaTipounidad);
    }

    function listaTipogas(){
      $listaTipogas = $this->ordenservicioModel->listarTipogas();
      echo json_encode($listaTipogas);
    }

    function listaTipocaja(){
      $listaTipocaja = $this->ordenservicioModel->listarTipocaja();
      echo json_encode($listaTipocaja);
    }

    function listaDatoscarga(){
      $idcliente = $_POST['idcliente'];
      $listaDatoscarga = $this->ordenservicioModel->listarDatoscarga($idcliente);
      echo json_encode($listaDatoscarga);
    }
    function listaDatoscarga1(){
      $iddatoscarga = $_POST['iddatoscarga'];
      $listaDatoscarga1 = $this->ordenservicioModel->listarDatoscarga1($iddatoscarga);
      echo json_encode($listaDatoscarga1);
    }
    function listaDatosentrega(){
      $iddestinatario = $_POST['iddestinatario'];
      $listaDatosentrega = $this->ordenservicioModel->listarDatosentrega($iddestinatario);
      echo json_encode($listaDatosentrega);
    }
    function listaDatosentrega1(){
      $iddatosentrega = $_POST['iddatosentrega'];
      $listaDatosentrega1 = $this->ordenservicioModel->listarDatosentrega1($iddatosentrega);
      echo json_encode($listaDatosentrega1);
    }
    function listaCiudades(){
    	$idest = $_POST['idest'];
      	$listaCiudades = $this->ordenservicioModel->listarCiudades($idest);
     	echo json_encode($listaCiudades);
    }
    function listaDesccorta(){
    	$idest = $_POST['idest'];
      	$listaDesccorta = $this->ordenservicioModel->listarDesccorta();
     	echo json_encode($listaDesccorta);
    }
    function listaDesccortaGastos(){
        $listaDesccortaGastos = $this->ordenservicioModel->listaDesccortaGastos();
      echo json_encode($listaDesccortaGastos);
    }
    function lastIdCon(){
      $id = $_POST['id'];
      $folio = $_POST['folio'];
      $idsolicitud = $_POST['idsolicitud'];
      $incluir = $_POST['incluir'];
        $lastIdCon = $this->ordenservicioModel->lastIdConM($id,$folio,$idsolicitud,$incluir);
      echo json_encode($lastIdCon);
    }
    ////////////////////////////////////////////////////////////////////////
    /// GUARDAR CONVENIO 
    function saveConvenio(){
      $lastid = $_POST['lastid'];
    	$idsolicitud = $_POST['idsolicitud'];
    	$desc = $_POST['desc'];
    	$desccorta = $_POST['desccorta'];
    	$precioclie = $_POST['precioclie'];
    	$retencion = $_POST['retencion'];
    	$comisionporc = $_POST['comisionporc'];
      $saveConvenio = $this->ordenservicioModel->savedConvenio($lastid,$idsolicitud,$desc,$desccorta,$precioclie,$retencion,$comisionporc);
     	echo_json_encode ($saveConvenio);
    }
    /// UPDATE CONVENIO 
    function editConvenio(){
      $idconvenioE = $_POST['idconvenioE'];
      $cli = $_POST['cli'];
      $est = $_POST['est'];
      $ciu = $_POST['ciu'];
      $cap = $_POST['cap'];
      $temp = $_POST['temp'];
      $desc = $_POST['desc'];
      $desccorta = $_POST['desccorta'];
      $precioclie = $_POST['precioclie'];
      $preciopro = $_POST['preciopro'];
      $retencion = $_POST['retencion'];
      $comisionfija = $_POST['comisionfija'];
      $comisionporc = $_POST['comisionporc'];
      $coor = $_POST['coor'];
      $editConvenio = $this->ordenservicioModel->editedConvenio($idconvenioE,$cli,$est,$ciu,$cap,$temp,$desc,$desccorta,$precioclie,$preciopro,$retencion,$comisionfija,$comisionporc,$coor);
      echo ($editConvenio);
    }
    /// DELETE CONVENIO 
    function deleteConvenio(){
      $idconvenioD = $_POST['idconvenioD'];
      $deleteConvenio = $this->ordenservicioModel->deletedConvenio($idconvenioD);
      echo ($deleteConvenio);
    }

    //////////////////////////////////////////////////SOLICITUDES//////////////////////////////////////////////////////////////////////////////////////////
    function listaSolicitudes(){
      $listaSolicitudes = $this->ordenservicioModel->listarSolicitudes();
      echo json_encode($listaSolicitudes);
    }
    function listaSolicitudEdit(){
      $idsolicitud = $_POST['idsolicitud'];
      $listaSolicitudEdit = $this->ordenservicioModel->listarSolicitudEdit($idsolicitud);
      echo json_encode($listaSolicitudEdit);
    }
    /// GUARDAR SOLICITUD 
    function saveSolicitud(){
      $idcliente = $_POST['idcliente'];
      $fechaD = $_POST['fechaD'];
      $horaD = $_POST['horaD'];
      $embalaje = $_POST['embalaje'];
      $peso = $_POST['peso'];
      $grados = $_POST['grados'];
      $fechaC = $_POST['fechaC'];
      $horaC = $_POST['horaC'];
      $fechaE = $_POST['fechaE'];
      $horaE = $_POST['horaE'];
      $atencion = $_POST['atencion'];
      $recomendaciones = $_POST['recomendaciones'];
      $requerimientos = $_POST['requerimientos'];
      $evidencias = $_POST['evidencias'];
      $idcapacidad = $_POST['idcapacidad'];
      $idtipocarga = $_POST['idtipocarga'];
      $iddatoscarga = $_POST['iddatoscarga'];
      $iddatosentrega = $_POST['iddatosentrega'];
      $temp = $_POST['temp'];
      $servi = $_POST['servi'];
      $viaje = $_POST['viaje'];
      $estatus = $_POST['estatus'];
      $iddestinatario = $_POST['iddestinatario'];

      $saveSolicitud = $this->ordenservicioModel->savedSolicitud($idcliente,$fechaD,$horaD,$embalaje,$peso,
        $grados,$fechaC,$horaC,$fechaE,$horaE,$atencion,$recomendaciones,$requerimientos,$evidencias,$idcapacidad,$idtipocarga,
        $iddatoscarga,$iddatosentrega,$temp,$servi,$viaje,$estatus,$iddestinatario);
      echo ($saveSolicitud);
    }
    function editSolicitud(){
      $idsolicitud = $_POST['idsolicitud'];

      $idcliente = $_POST['idcliente'];
      $fechaD = $_POST['fechaD'];
      $horaD = $_POST['horaD'];
      $embalaje = $_POST['embalaje'];
      $peso = $_POST['peso'];
      $grados = $_POST['grados'];
      $fechaC = $_POST['fechaC'];
      $horaC = $_POST['horaC'];
      $fechaE = $_POST['fechaE'];
      $horaE = $_POST['horaE'];
      $atencion = $_POST['atencion'];
      $recomendaciones = $_POST['recomendaciones'];
      $requerimientos = $_POST['requerimientos'];
      $evidencias = $_POST['evidencias'];
      $idcapacidad = $_POST['idcapacidad'];
      $idtipocarga = $_POST['idtipocarga'];
      $iddatoscarga = $_POST['iddatoscarga'];
      $iddatosentrega = $_POST['iddatosentrega'];
      $temp = $_POST['temp'];
      $servi = $_POST['servi'];
      $viaje = $_POST['viaje'];

      $editSolicitud = $this->ordenservicioModel->editedSolicitud($idsolicitud,$idcliente,$fechaD,$horaD,$embalaje,$peso,
        $grados,$fechaC,$horaC,$fechaE,$horaE,$atencion,$recomendaciones,$requerimientos,$evidencias,$idcapacidad,$idtipocarga,
        $iddatoscarga,$iddatosentrega,$temp,$servi,$viaje);
      echo ($editSolicitud);
    }
    function saveLugarcarga(){
      $carga_en = $_POST['carga_en'];
      $calleC1 = $_POST['calleC1'];
      $estadoC1 = $_POST['estadoC1'];
      $ciudadC1 = $_POST['ciudadC1'];
      $referenciaC1 = $_POST['referenciaC1'];
      $coloniaC1 = $_POST['coloniaC1'];
      $preguntarC1 = $_POST['preguntarC1'];
      $telefonoC1 = $_POST['telefonoC1'];
      $fechaC1 = $_POST['fechaC1'];
      $horaC1 = $_POST['horaC1'];
      $idcliente = $_POST['idcliente'];
 
      $saveLugarcarga = $this->ordenservicioModel->savedLugarcarga($carga_en,$calleC1,$estadoC1,$ciudadC1,$referenciaC1,$coloniaC1,$preguntarC1,$telefonoC1,$idcliente);
      echo ($saveLugarcarga);
    }
    function saveLugarentrega(){
      $entrega_en = $_POST['entrega_en'];
      $calleE1 = $_POST['calleE1'];
      $estadoE1 = $_POST['estadoE1'];
      $ciudadE1 = $_POST['ciudadE1'];
      $referenciaE1 = $_POST['referenciaE1'];
      $coloniaE1 = $_POST['coloniaE1'];
      $preguntarE1 = $_POST['preguntarE1'];
      $telefonoE1 = $_POST['telefonoE1'];
      $fechaE1 = $_POST['fechaE1'];
      $horaE1 = $_POST['horaE1'];
      $iddestinatario = $_POST['iddestinatario'];
      $saveLugarentrega = $this->ordenservicioModel->savedLugarentrega($entrega_en,$calleE1,$estadoE1,$ciudadE1,$referenciaE1,$coloniaE1,$preguntarE1,$telefonoE1,$iddestinatario);
      echo ($saveLugarentrega);
    }


    function deleteSolicitud(){
      $folio = $_POST['folio'];
      $deleteSolicitud = $this->ordenservicioModel->deletedSolicitud($folio);
      echo ($deleteSolicitud);
    }

//////////////ORDEN DE SERVICIO/////////////
  function listaOS(){
      $listaOS = $this->ordenservicioModel->listarOS();
      echo json_encode ($listaOS);
    }
  function listaOS1(){
      $idordenservicio = $_POST['idordenservicio'];
      $idsolicitud = $_POST['idsolicitud'];
      $listaOS1 = $this->ordenservicioModel->listarOS1($idordenservicio);
      echo json_encode ($listaOS1);
    }
/*
  function listaDescCorta(){
      $listaDescCorta = $this->ordenservicioModel->listarDescCorta();
      echo json_encode ($listaDescCorta);
    }
*/
function listaFormaspago(){
      $listaFormaspago = $this->ordenservicioModel->listarFormaspago();
      echo json_encode ($listaFormaspago);
    }
function listaCuenta(){
      $listaCuenta = $this->ordenservicioModel->listarCuenta();
      echo json_encode ($listaCuenta);
    }
function listaAsignacion(){
      $idordenservicio = $_POST['idordenservicio'];
      $listaAsignacion = $this->ordenservicioModel->listarAsignacion($idordenservicio);
      echo json_encode ($listaAsignacion);
    }
function saveGastoExtra(){
      $idOS = $_POST['idOS'];
      $desc = $_POST['desc'];
      $desccorta = $_POST['desccorta'];
      $clave = $_POST['clave'];
      $monto = $_POST['monto'];
      $obser = $_POST['obser'];
      $cobro = $_POST['cobro'];
      $saveGastoExtra = $this->ordenservicioModel->savedGastoExtra($idOS,$desc,$desccorta,$clave,$monto,$obser,$cobro);
      echo ($saveGastoExtra);
    }
function saveAnticipo(){
      $idOS = $_POST['idOS'];
      $fecha = $_POST['fecha'];
      $operador = $_POST['operador'];
      $formapago = $_POST['formapago'];
      $cuenta = $_POST['cuenta'];
      $referencia = $_POST['referencia'];
      $importe = $_POST['importe'];
      $estatus = 1; // OTORGADO
      $saveAnticipo = $this->ordenservicioModel->savedAnticipo($idOS,$fecha,$operador,$formapago,$cuenta,$referencia,$importe,$estatus);
      echo ($saveAnticipo);
    }
function saveAyudante(){
      $idOS = $_POST['idOS'];
      $operador = $_POST['operador'];
      $concepto = $_POST['concepto'];
      $monto = $_POST['monto'];
      $observ = $_POST['observ'];
      $saveAyudante = $this->ordenservicioModel->savedAyudante($idOS,$operador,$concepto,$monto,$observ);
      echo ($saveAyudante);
    }
function saveCartaPorte(){
      $idordenservicio = $_POST['idordenservicio'];
      $fecha = $_POST['fecha'];
      $valoruni = $_POST['valoruni'];
      $valordec = $_POST['valordec'];
      $condiciones = $_POST['condiciones'];
      $saveCartaPorte = $this->ordenservicioModel->savedCartaPorte($idordenservicio,$fecha,$valoruni,$valordec,$condiciones);
      echo ($saveCartaPorte);
}
function listaGastoX($idordenservicio){
      $idordenservicio = $_POST['idordenservicio'];
      $listaGastoX = $this->ordenservicioModel->listarGastoX($idordenservicio);
      echo json_encode ($listaGastoX);
    }
function listaAnticipos($idordenservicio){
      $idordenservicio = $_POST['idordenservicio'];
      $listaAnticipos = $this->ordenservicioModel->listarAnticipos($idordenservicio);
      echo json_encode ($listaAnticipos);
    }
function listaBitacora($idordenservicio){
      $idordenservicio = $_POST['idordenservicio'];
      $listaBitacora = $this->ordenservicioModel->listarBitacora($idordenservicio);
      echo json_encode ($listaBitacora);
    }
function listaCartaPorte($idordenservicio){
      $idordenservicio = $_POST['idordenservicio'];
      $listaCartaPorte = $this->ordenservicioModel->listarCartaPorte($idordenservicio);
      echo json_encode ($listaCartaPorte);
    }
function editRelSolCon(){
      $json = $_POST['json'];
      $json1 = trim($json, ','); // eliminado la ultima coma de la cadena
      $jsonF="[".$json1."]";      // terminado el estilo json de la cadena
      $array = json_decode($jsonF); 
      $query = ""; 
      foreach($array as $obj){
        $idrel = $obj->idrel;
        $obser = $obj->obser;
        $query .= "UPDATE tran_relacion_sol_con SET tran_relacion_sol_con.observaciones = '$obser' WHERE tran_relacion_sol_con.idrel = '$idrel';";
        }
        $editRelSolCon = $this->ordenservicioModel->editedRelSolCon($query);
        echo ($editRelSolCon);
    }

////////AVANZADAS
  function listaConInc(){
      $idsolicitud = $_POST['idsolicitud'];
      $listaConInc = $this->ordenservicioModel->listarConInc($idsolicitud);
      echo json_encode ($listaConInc);
    }

  function ligarConSol(){
      $idconvenio = $_POST['idconvenio'];
      $idsolicitud = $_POST['idsolicitud'];
      $ligarConSol = $this->ordenservicioModel->ligarConSolM($idconvenio,$idsolicitud);
      echo ($ligarConSol);
    }
  function editConvenioCant(){
      $idsolicitud = $_POST['idsolicitud'];
      $idconvenio = $_POST['idconvenio'];
      $cantidad = $_POST['cantidad'];
      $idcliente = $_POST['id'];
      $editConvenioCant = $this->ordenservicioModel->editedConvenioCant($idsolicitud,$idconvenio,$cantidad,$idcliente);
      echo ($editConvenioCant);
    }
  function editConvenioInc(){
      $idsolicitud = $_POST['folio'];
      $idconvenio = $_POST['idconvenio'];
      $idcliente = $_POST['id'];
      $cantidad = $_POST['cantidad'];

      $editConvenioInc = $this->ordenservicioModel->editedConvenioInc($idsolicitud,$idconvenio,$idcliente);
      echo ($editConvenioInc);
    }
    function deleteRelSolConv(){
      $idsolicitud = $_POST['folio'];
      $idconvenio = $_POST['idconvenio'];
      $idcliente = $_POST['id'];
      $cantidad = $_POST['cantidad'];

      $editConvenioInc = $this->ordenservicioModel->editedConvenioNO($idsolicitud,$idconvenio,$idcliente);
      echo ($editConvenioInc);
    }
    function convagree(){
      $id = $_POST['id'];
      $folio = $_POST['folio'];
      $idsolicitud = $_POST['idsolicitud'];
      $incluir = $_POST['incluir'];
      $convagreed = $this->ordenservicioModel->convagreeM($id,$folio,$idsolicitud,$incluir);
      echo json_encode($convagreed);
    }
     function editConvenioUnchk(){
      $idsolicitud = $_POST['folio'];
      $idconvenio = $_POST['idconvenio'];
      $idcliente = $_POST['id'];
      
      $editConvenioUnchk = $this->ordenservicioModel->editedConvenioUnchk($idsolicitud,$idconvenio,$idcliente);
      echo ($editConvenioUnchk);
    }

   function SaveNewOpe(){
    $nombre = $_POST['nom_operador'];
    $apellido = $_POST['ape_operador'];
    $telefono = $_POST['tel_operador'];
    $rfc      = $_POST['rfc_operador'];
    $edad     = $_POST['age_operador'];
    $estado   = $_POST['est_operador'];
    $ciudad   = $_POST['ciu_operador'];
    $domicilio = $_POST['calle_operador'];
    $numero_ext = $_POST['num_exterior'];
    $numero_int = $_POST['num_interior'];
    $colonia  = $_POST['col_operador'];
    $codigopostal = $_POST['cp_operador'];
    $licencia = $_POST['num_lic'];
    $fecha    = $_POST['fecha_ingreso'];
    $tel1     = $_POST['tel1'];
    $tel2     = $_POST['tel2'];
    $nom_emer = $_POST['nom_emer'];
    
    $SaveNewope = $this->ordenservicioModel->SavedNewope($nombre,$apellido,$telefono,$rfc,$edad,$estado,$ciudad,$domicilio,$numero_ext,$numero_int,$colonia,$codigopostal,$licencia,$fecha,$tel1,$tel2,$nom_emer);
       echo ($SaveNewope);
    }

    function SaveNewcaja(){

      $numero = $_POST['numero'];
      $placas = $_POST['placas'];
      $ejes   = $_POST['ejes'];
      $color  = $_POST['color'];
      $obs    = $_POST['obs'];
      $fecha  = $_POST['fecha'];
      $tipo   = $_POST['tipo'];

      $SaveNewcaja = $this->ordenservicioModel->SavedNewcaja($numero,$placas,$ejes,$color,$obs,$fecha,$tipo);
      echo json_encode($SaveNewcaja);
    }


 function  borrarOS(){
  $idordenservicio = $_POST['idordenservicio'];
  $idcajatractor = $_POST['idcajatractor'];
  $idEmpleado = $_POST['idEmpleado'];
  $idunidad = $_POST['idunidad'];
$borrarOS = $this->ordenservicioModel->borrarOSM($idordenservicio,$idcajatractor,$idEmpleado,$idunidad);
    echo json_encode($borrarOS);
  }

  function deletegastoX(){
    $idgastoextra = $_POST['idgastoextra'];
    $borrargasto = $this->ordenservicioModel->deletedgastoX($idgastoextra);
  }

   function delete_anticipo(){
    $idanticipo = $_POST['idanticipo'];
    $borraranticipo = $this->ordenservicioModel->deletedanticipo($idanticipo);
  }

  function saveAsignacion(){
      $fechaA = $_POST['fechaA'];
      $idcaja = $_POST['idcaja'];
      $idope = $_POST['idope'];
      $idsolicitud = $_POST['idsolicitud'];
      $iduni = $_POST['iduni'];
     
      $estaus_operador = "1";
      $saveAsignacion = $this->ordenservicioModel->savedAsignacion($fechaA,$idope,$iduni,$idcaja,$idsolicitud,$estaus_operador);
      echo ($saveAsignacion);
    }

    
  function printCarta (){
    $cartaHTML = $_POST['bitacoraHTML'];
    $resultado = str_replace($cartaHTML);
    echo $resultado;
    echo $cartaHTML;
  }

  function SaveNewunidad(){
   $numero_ec = $_POST['numero_ec'];
   $placas = $_POST['placas'];
   $modelo = $_POST['modelo'];
   $anio = $_POST['anio'];
   $color = $_POST['color'];
   $tanque =$_POST['tanque'];
   $observaciones = $_POST['observaciones'];
   $fecha = $_POST['fecha'];
   $tipo = $_POST['tipo'];
   $capacidad = $_POST['capacidad'];
   $marca = $_POST['marca'];
   $tipogas = $_POST['tipogas'];

   $SaveNewunidad = $this->ordenservicioModel->SavedNewunidad($numero_ec,$placas,$modelo,$anio,$color,$tanque,
    $observaciones,$fecha,$tipo,$capacidad,$marca,$tipogas);
   echo json_encode($SaveNewunidad);



  }


//////////////////////////////////////// EVIDENCIA REQUERIMIENTOS/////////////////////////////////////////////////////////
 function listaevireq(){
      $listaevireq = $this->ordenservicioModel->listarevireq();
      echo json_encode($listaevireq);

}

function deleteEvireq(){

   $idevireq = $_POST['idevireq'];
      $deleteEvireq = $this->ordenservicioModel->deletedEvireq($idevireq);
      echo ($deleteEvireq);


}

function saveEvireq(){

  $cliente = $_POST['cliente'];
  $requerimientos = $_POST['requerimientos'];
  $evidencias = $_POST['evidencias'];
  $saveEvireq = $this->ordenservicioModel->savedEvireq($cliente,$requerimientos,$evidencias);
  echo_json_encode($saveEvireq);

}


function edit_evireq(){

  $idevireq = $_POST['idevireq'];
  $idcliente = $_POST['idcliente'];
  $edit_evireq = $this->ordenservicioModel->edited_evireq($idevireq,$idcliente);
  echo json_encode($edit_evireq);
    
}

 function send_edit_evireq(){
 
  $idevireqE  = $_POST['idevireqE'];
  $evidencias = $_POST['evidencias'];
  $requerimientos = $_POST['requerimientos'];
  
  $send_edit_evireq = $this->ordenservicioModel->send_edited_evireq($idevireqE,$evidencias,$requerimientos);
//($send_edit_evireq);
 }

/////////////////////////<<<<<<<<<<<<< LICENCIAS >>>>>>>>>>>>>>>>>>>>>////////////////////////////////

function reload_lic(){
  $table_lic = $this->ordenservicioModel->table_licM();
  echo json_encode($table_lic);
}

function listaConductores(){
  $list = $this->ordenservicioModel->listconduct();
  echo json_encode($list);
}

function listaConductores1(){
  $idchofer = $_POST['idchofer'];
  $list2 = $this->ordenservicioModel->list2conduct($idchofer);
  echo json_encode($list2);

}

function saveLic(){
  $chofer = $_POST['chofer'];
  $licencia = $_POST['licencia'];
  $tipoLic = $_POST['tipoLic'];
  $vigencia = $_POST['vigencia'];
  $saveLic = $this->ordenservicioModel->savedLic($chofer,$licencia,$tipoLic,$vigencia);
  echo $savedLic;
  
}

/////////////////////////////// inicio ANTICIPOS ////////////////////////////////////////////////////////////////

function reload_table_ant(){
$listant = $this->ordenservicioModel->listarant();
echo json_encode($listant);
}

function aprobar_ant(){
$idanticipo = $_POST['idanticipo'];
$idordenservicio = $_POST['idordenservicio'];
$aprob = $this->ordenservicioModel->aprobado_ant($idanticipo,$idordenservicio);
echo json_encode($aprob);
}

function rechazar_ant(){
$idanticipo = $_POST['idanticipo'];
$idordenservicio = $_POST['idordenservicio'];
$rechazado = $this->ordenservicioModel->rechazado_ant($idanticipo,$idordenservicio);
echo json_encode($rechazado);
}



/////////////////////////////// FIN ANTICIPOS ////////////////////////////////////////////////////////////////+
//


////////////////////////////////////INICIO PORCENTAJES OPERADORES///////////////////////////////////////////




////////////////////////////////////FIN PORCENTAJES OPERADORES///////////////////////////////////////////




///////////////////////////////////ALTA UNIDADES//////////////////////////////////////////////////////////////+

function listaunidadesalta(){
  $listaunidades = $this->ordenservicioModel->listarunidadesalta();
  echo json_encode($listaunidades);
}

function infoUni(){
  $idunidad = $_POST['idunidad'];
  $infoUnidad = $this->ordenservicioModel->infoUniList($idunidad);
  echo json_encode($infoUnidad);
}
function save_addUni(){
  $refrigerado = $_POST['refrigerado'];
  $no_economico = $_POST['no_economico'];
  $marca = $_POST['marca'];
  $anio = $_POST['anio'];
  $placas = $_POST['placas'];
  $color = $_POST['color'];
  $tipo = $_POST['tipo'];
  $capacidad = $_POST['capacidad'];
  $tipocomb = $_POST['tipocomb'];
  $tamanotanUni = $_POST['tamanotanUni'];
  $rendforUni = $_POST['rendforUni'];
  $rendlocUni = $_POST['rendlocUni'];
  $tamtanqthem = $_POST['tamtanqthem'];
  $rendthermfor = $_POST['rendthermfor'];
  $rendthermloc = $_POST['rendthermloc'];
  $fechaaddUni = $_POST['fechaaddUni'];
  $kmadquisicion = $_POST['kmadquisicion'];
  $kmtotal = $_POST['kmtotal'];
  $observaciones = $_POST['observaciones'];
  $modelo = $_POST['modelo'];
  $save_newUni = $this->ordenservicioModel->saved_addUni($refrigerado,$no_economico,$marca,$anio,$placas,$color,$tipo,$capacidad,$tipocomb,$tamanotanUni,$rendforUni,$rendlocUni,$tamtanqthem,$rendthermfor,$rendthermloc,$fechaaddUni,$kmadquisicion,$kmtotal,$observaciones,$modelo);
  echo json_encode($save_newUni);
}

function listarselect_marca(){
  $listaselect = $this->ordenservicioModel->listado_select_marca();
  echo json_encode($listaselect);
}


function listarselect_tipoUni(){
  $listaselect = $this->ordenservicioModel->listado_select_tipouni();
  echo json_encode($listaselect);
}


function listarselect_capUni(){
  $listaselect = $this->ordenservicioModel->listado_select_capuni();
  echo json_encode($listaselect);
}


function listarselect_tipocomb(){
  $listaselect = $this->ordenservicioModel->listado_select_tipocomb();
  echo json_encode($listaselect);
}























/// fin class
}
?>