<?php
//Carga la funciones comunes top y footer
require('common.php');

//Carga el modelo para este controlador
require("models/valescaja.php");

class ValesCaja extends Common
{
	public $ValesCajaModel;

	function __construct()
	{
		//Se crea el objeto que instancia al modelo que se va a utilizar

		$this->ValesCajaModel = new ValesCajaModel();
		$this->ValesCajaModel->connect();
	}

	function __destruct()
	{
		//Se destruye el objeto que instancia al modelo que se va a utilizar
		$this->ValesCajaModel->close();
	}

	function vales()
	{
		require('views/valescaja/valescaja.php');
	}
  function diversos()
  {
    require('views/valescaja/prestamosdiversos.php');
  }
  function operadores()
  {
    require('views/valescaja/prestamosoperadores.php');
  }

  function listaVales(){
    $estatus = $_POST['estatus'];
    $folio = $_POST['folio'];
    if ($folio > 0){ 
      $where = " and a.idanticipo = $folio";
      $estatus = 1;
    }else{
      $where = "";
    }
    $listaVales = $this->ValesCajaModel->listarVales($estatus,$where);
    echo json_encode ($listaVales);
  }
  function listaVales1(){
    $estatus = $_POST['estatus'];
    $folio = $_POST['folio'];
    if ($folio > 0){ 
      $where = " and a.idanticipo = $folio";
    }else{
      $where = "";
    }
    $listaVales = $this->ValesCajaModel->listarVales1($where);
    echo json_encode ($listaVales);
  }
  function listaCuentas(){
    $where="";
    $listaCuentas = $this->ValesCajaModel->listarCuentas($where);
    echo json_encode($listaCuentas);
  }
  function listaOperadores(){
    $where="";
    $listaOperadores = $this->ValesCajaModel->listarOperadores($where);
    echo json_encode($listaOperadores);
  }
  function listaOS(){
    $filtro = $_POST['filtro'];
    $id = $_POST['id'];
    if($filtro == 1){
      $where=" WHERE a.idEmpleado = ".$id.";";
    }
    if($filtro == 2){
      $where=" WHERE a.idordenservicio = ".$id.";";
    }

    $listaOS = $this->ValesCajaModel->listarOS($where);
    echo json_encode($listaOS);
  }
  function listaOS1(){
    $fecha1 = $_POST['fecha1'];
    $fecha2 = $_POST['fecha2'];
    $idoperador = $_POST['operF'];
    $idordenservicio = $_POST['idordenservicio'];

    if($fecha1 == 0){
    $where = " WHERE (a.fecha >= '1999-12-12' and a.fecha <= '2033-12-12')";
    }else{
    $where = " WHERE (a.fecha >= '$fecha1' and a.fecha <= '$fecha2')";
    }
    
    if($idoperador == 0){
        $where .= "";
      }else{
        $where .= " AND b.idEmpleado = ".$idoperador;
      }
    if($idordenservicio == 0){
        $where .= "";
      }else{
        $where .= " AND a.idordenservicio = ".$idordenservicio;
      }
    
    $listaOS1 = $this->ValesCajaModel->listarOS1($where);
    echo json_encode($listaOS1);
  }
  function updateAnticipo(){
    $estatus = $_POST['estatus'];
    $folio = $_POST['folio'];
    $idcuenta = $_POST['idcuenta'];
    $updateAnticipo = $this->ValesCajaModel->updatedAnticipo($folio,$idcuenta,$estatus);
    echo ($updateAnticipo);
  }
  function cancelAnticipo(){
    $estatus = $_POST['estatus'];
    $folio = $_POST['folio'];
    $cancelAnticipo = $this->ValesCajaModel->canceledAnticipo($folio,$estatus);
    echo ($cancelAnticipo);
  }
  function saveAnticipo(){
    $idOS = $_POST['idOS'];
    $fechaCaptura = $_POST['fechaCaptura'];
    $idcuenta = $_POST['idcuenta'];
    $idEmpleado = $_POST['idEmpleado'];
    $idFormapago = $_POST['idFormapago'];
    $referencia = $_POST['referencia'];
    $importe = $_POST['importe'];
    $estatus = 1; // 1 otorgado,  2 autorizado, 3 cancelado
    $tipo = 0; // 0 anticipo, 1 prestamo diverso, 2 prestao operadores
    $saveAnticipo = $this->ValesCajaModel->savedAnticipo($idOS,$fechaCaptura,$idcuenta,$idEmpleado,$idFormapago,$referencia,$importe,$estatus,$tipo);
    echo ($saveAnticipo);
  }
  function saveDiverso(){
    $idOS = 0;
    $fechaCaptura = $_POST['fechaCaptura'];
    $idoperador = $_POST['idoperador'];
    $idconcepto = $_POST['idconcepto'];
    $idformapago = $_POST['idformapago'];
    $idcuenta = $_POST['idcuenta'];
    $observaciones = $_POST['observaciones'];
    $cantidad = $_POST['cantidad'];
    $estatus = 1; // 1 otorgado,  2 autorizado, 3 cancelado
    $tipo = 1; // 0 anticipo, 1 prestamo diverso, 2 prestao operadores
    $saveDiverso = $this->ValesCajaModel->savedDiverso($idOS,$fechaCaptura,$idoperador,$idconcepto,$idformapago,$idcuenta,$observaciones,$cantidad,$estatus,$tipo);
    echo ($saveDiverso);
  }
  
  function savePrestamoOperadores(){
    /// PRESTAMOS OPERADORES 2
    $idOS = 0; // se usara para prestamos diveros y prestamos operadores
    $fechaCaptura = $_POST['fecha'];
    $idEmpleado = $_POST['idEmpleado'];
    $idFormapago = $_POST['idFormapago'];
    $importe = $_POST['importe'];
    $idcuenta = $_POST['idcuenta'];
    $referencia = $_POST['observaciones'];
    $estatus = 1; // 1 otorgado,  2 autorizado, 3 cancelado
    $tipo = 2; // 0 anticipo, 1 prestamo diverso, 2 prestao operadores
    $savePrestamoOperadores = $this->ValesCajaModel->savedPrestamoOperadores($idOS,$fechaCaptura,$idcuenta,$idEmpleado,$idFormapago,$referencia,$importe,$estatus,$tipo);
    echo ($savePrestamoOperadores);
  }
  function pagosjson(){
      $json = $_POST['json'];
      $idprestamo = $_POST['idprestamo'];
      $json1 = trim($json, ','); // eliminado la ultima coma de la cadena
      $jsonF="[".$json1."]";      // terminado el estilo json de la cadena
      
      $array = json_decode($jsonF); 
      //var_dump($array);

      $query = ""; 
      foreach($array as $obj){
        $pagos = $obj->pagos;
        $pagos_de = $obj->pagos_de;
        //$query .= "UPDATE tran_relacion_sol_con SET tran_relacion_sol_con.observaciones = '$obser' WHERE tran_relacion_sol_con.idrel = '$idrel';";
        if($pagos > 1){
          for ($i=0; $i < $pagos; $i++) { 
            $n = $i+1;
            //$query .="\n numero de pago .... $n  pagos ... '$pagos' pagos de ... '$pagos_de";
            $query .="INSERT INTO tran_pagos (idprestamo,num_pago,pago,estatus) VALUES ('$idprestamo','$n',$pagos_de,'1');";
          }
        }
        }
        //echo $query;
        $pagosjson = $this->ValesCajaModel->pagosjsonM($query);
        echo ($pagosjson);
        
  }
  function listaFormaspago(){
      $folio = $_POST['folio'];
      $idFormapago = $_POST['idFormapago'];
      if($folio > 0){
        $where = " where a.idFormapago = $idFormapago";
      }else{
        $where ="";
      }
      $listaFormaspago = $this->ValesCajaModel->listarFormaspago($where);
      echo json_encode ($listaFormaspago);
  }
  function listaConceptos(){
    $listaConceptos = $this->ValesCajaModel->listarConceptos();
    echo json_encode($listaConceptos);
  }
  



}/// fin class

?>