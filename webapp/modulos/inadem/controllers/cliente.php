<?php
//ini_set('display_errors', 1);
//Carga la funciones comunes top y footer
require('common.php');

//Carga el modelo para este controlador
require("models/cliente/cliente.php");

class Cliente extends Common {

    public $clienteModel;

    function __construct() {
        //Se crea el objeto que instancia al modelo que se va a utilizar
        $this->clienteModel = new clienteModel();
    }

      function imprimeGrid() {
        $clientes = $this->clienteModel->gridCliente();
        $filtros = $this->clienteModel->filtros();
        require('views/cliente/cliente.php');
      }

      function indexNotas(){
   
        $notas = $this->clienteModel->gridNotas();

        //$filtros = $this->clienteModel->filtros();

        require('views/cliente/gridNotas.php');
      }
      function gridCliente(){
        $clientes = $this->clienteModel->gridCliente();
        echo json_encode($clientes);
      }
      function guardaError(){
        $error = $_POST['error'];
        $idInadem = $_POST['id'];
        $x = $this->clienteModel->guardaError($error,$idInadem);

        echo json_decode($x);
      }
      function guardaErrorNota(){
        $error = $_POST['error'];
        $idInadem = $_POST['id'];
        $x = $this->clienteModel->guardaErrorNota($error,$idInadem);

        echo json_decode($x);
      }
      function buscar(){
        $convocatoria = $_POST['convocatoria'];
        $folio = $_POST['folio'];
        $organismo = $_POST['organismo'];

        $grid = $this->clienteModel->buscar($convocatoria,$folio,$organismo);

        echo json_encode($grid);
      }
      function clienteForm(){
        $idCliente =  $_GET['pe'];
        $cliente = $this->clienteModel->getinfoInadem($idCliente);
        $clienteBasicos = $this->clienteModel->getinfoBasics($idCliente);
        require('views/cliente/clienteForm.php');
      }
      function saveClient(){
        $idInademCliente = $_POST['folioInaCliente'];
        $idInadem=$_POST['folioIna'];
        $convocatoria = $_POST['convocatoria'];
        $vitrina = $_POST['vitrina'];
        $cupon = $_POST['cupon'];
        $beneficio = $_POST['beneficio'];
        $aportacion = $_POST['aportacion'];
        $organismo = $_POST['organismo'];
        $promotor = $_POST['promotor'];
        $respNwm = $_POST['respNwm'];
        $fecha = $_POST['fecha'];
        $instancia = $_POST['instancia'];
        $respLegal = $_POST['respLegal'];

        $respSave = $this->clienteModel->saveClient($idInademCliente,$idInadem,$convocatoria,$vitrina,$cupon,$beneficio,$aportacion,$organismo,$promotor,$respNwm,$fecha,$instancia,$respLegal);
         echo json_encode($respSave);
      }
      function clienteFormulario2016(){
        require('views/cliente/clienteForm.php');
      }
      function clienteGridInadem(){
        $clientes = $this->clienteModel->gridCliente();
        require('views/cliente/clienteGridInadem.php');
      }
      function gridFacturados(){
        $res = $this->clienteModel->gridFacturados();

        echo json_encode($res);
      }
      function subeLayout()
      {
          $directorio = "importacion/";
          if (isset($_FILES["layout"])) 
          {
                  if($_FILES['layout']['name'])
                  {
                      if (move_uploaded_file($_FILES['layout']['tmp_name'], $directorio.basename("clientes_temp.xls" ) )) 
                      {
                          echo "Validando archivo...<br/>";
                          include($directorio."import_clients.php");
                      } 
                      else 
                      {
                          echo "No se subio el archivo de Productos <br/>";
                      }
                  }
          }
      }
      function subeLayoutNotas()
      {
          $directorio = "importacion/";
          if (isset($_FILES["layout"])) 
          {
                  if($_FILES['layout']['name'])
                  {
                      if (move_uploaded_file($_FILES['layout']['tmp_name'], $directorio.basename("notas_temp.xls" ) )) 
                      {
                          echo "Validando archivo...<br/>";
                          include($directorio."import_notas.php");
                      } 
                      else 
                      {
                          echo "No se subio el archivo de Productos <br/>";
                      }
                  }
          }
      }
      function facturaIndem(){
        $idCliente = $_POST['idCliente'];
        $res = $this->clienteModel->facturaIndem($idCliente);

        echo json_encode($res);
      }
      function notaInadem(){
        $idReg = $_POST['idReg'];
        $res = $this->clienteModel->notaInadem($idReg);

        //echo json_encode($res);
      }
    function envioFactura(){
        $uid = $_POST['uid'];
        $correo = $_POST['correo'];
        $azurian = $_POST['azurian'];
        $doc = $_POST['doc'];

        $resultado = $this->clienteModel->envioFactura($uid, $correo, $azurian,$doc);

        echo json_encode($resultado);
    }
    function GuardaFacInadem(){
      $idInadem = $_POST['idIndem'];
      $uuid = $_POST['UUID2'];
      $venta = $_POST['venta'];

      $resultado = $this->clienteModel->GuardaFacInadem($uuid,$idInadem,$venta);

      echo json_encode($resultado);

    } 
    function guardaNota(){
        $res = $this->clienteModel->guardaNota($_POST["UUID"],$_POST["noCertificadoSAT"],$_POST["selloCFD"],$_POST["selloSAT"],$_POST["FechaTimbrado"],$_POST["idComprobante"],$_POST["idFact"],$_POST["idVenta"],$_POST["noCertificado"],$_POST["tipoComp"],$_POST["monto"],$_POST["cliente"],$_POST["trackId"],$_POST["idRefact"],$_POST["azurian"],$_POST["total"],$_POST['idFacRela']);

        echo json_encode($res);
    }
    function GuardaNotaInadem(){
      
      $uuid = $_POST['UUID2'];
      $idRegNota = $_POST['idRegNota'];

      $resultado = $this->clienteModel->GuardaNotaInadem($uuid,$idRegNota);

      echo json_encode($resultado);

    } 
      function guardarFacturacion(){

        $UUID = $_POST['UUID'];
        $noCertificadoSAT = $_POST['noCertificadoSAT'];
        $selloCFD = $_POST['selloCFD'];
        $selloSAT = $_POST['selloSAT'];
        $FechaTimbrado = $_POST['FechaTimbrado'];
        $idComprobante = $_POST['idComprobante'];
        $idFact = $_POST['idFact'];
        $idVenta = $_POST['idVenta'];
        $noCertificado = $_POST['noCertificado'];
        $trackId = $_POST['trackId'];
        $monto = $_POST['monto'];
        $cliente = $_POST['cliente'];
        $idRefact = $_POST['idRefact'];
        $azurian = $_POST['azurian'];
        $tipoComp = $_POST['tipoComp'];
        $estatus = $_POST['estatus'];

        if($_POST['doc'] == 3)
        {
            $tipoComp = "R";
        }

        $resultado = $this->clienteModel->guardarFacturacion($UUID,$noCertificadoSAT,$selloCFD,$selloSAT,$FechaTimbrado,$idComprobante,$idFact,$idVenta,$noCertificado,$tipoComp,$trackId,$monto,$cliente,$idRefact,$azurian,$estatus);

        echo json_encode($resultado);
    }

    

}

?>