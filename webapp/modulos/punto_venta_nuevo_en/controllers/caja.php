<?php

//Carga la funciones comunes top y footer
require('common.php');

//Carga el modelo para este controlador
require("models/caja/caja.php");

class Caja extends Common {

    public $cajaModel;

    function __construct() {
        //Se crea el objeto que instancia al modelo que se va a utilizar
        $this->cajaModel = new cajaModel();
    }

    function pintaRegistros() {

        echo json_encode($this->cajaModel->pintaRegistros());
    }

    function imprimecaja() {
      $response=explode("-",$this->cajaModel->variablesFlash());
      $vars=$response[0];
      //$formasDePagoEn = $this->cajaModel->formasDePagoEn();
      $formasDePago = $this->cajaModel->formasDePago();
      //$enabled=$response[1];
      $enabled=1;
      require('views/caja/caja.php');
  }
  function claseVenta(){
    $idProducto = $_GET['idProducto'];
    $cantidad = $_GET['cantidad'];
    $resutl = $this->cajaModel->claseVenta(); 
    echo json_encode($resutl);
  }
    

  function buscaClientes() {
    $term = $_GET["term"];

    $resultado = $this->cajaModel->buscaClientes($term);

    echo json_encode($resultado);
}

function buscaProductos() {
    $term = $_GET["term"];

    $resultado = $this->cajaModel->buscaProductos($term);

    echo json_encode($resultado);
}

function agregaProducto() {
    $idProducto = $_POST["id"];
    $cantidadInicial = $_POST["cantidadInicial"];

    $resultado = $this->cajaModel->agregaProducto($idProducto,$cantidadInicial);

    echo json_encode($resultado);
}

function agregarPropina() {
    $idArticulo = $_POST["idArticulo"];
    $cantidad = $_POST["cantidad"];

    $resultado = $this->cajaModel->agregarPropina($idArticulo, $cantidad);

    echo json_encode($resultado);
}
function configuraPropina(){
    $resultado = $this->cajaModel->configuraPropina();

    echo json_encode($resultado);
}

function cancelarCaja() {

    $resultado = $this->cajaModel->cancelarCaja();

    echo json_encode($resultado);
}

function eliminaProducto() {
    $idProducto = $_POST["id"];

    $resultado = $this->cajaModel->eliminaProducto($idProducto);

    echo json_encode($resultado);
}

function checaExistencias() {
    $idProducto = $_POST["id"];

    $resultado = $this->cajaModel->checaExistencias($idProducto);

    echo json_encode($resultado);
}
function checaPrecios() {
    $idProducto = $_POST["id"];

    $resultado = $this->cajaModel->checaPrecios($idProducto);

    echo json_encode($resultado);
}
function checaPrecioVenta() {
    $idProducto = $_POST["id"];

    $resultado = $this->cajaModel->checaPrecioVenta($idProducto);

    echo json_encode($resultado);
}

function cambiaCantidad() {
    $idProducto = $_POST["idArticulo"];
    $cantidad = $_POST["cantidad"];
    $tipo = $_POST["tipo"];
    $descuento = $_POST["descuento"];
    $comentario = $_POST["comentario"];
    $precionuevo = $_POST["precionuevo"];
    $resultado = $this->cajaModel->cambiaCantidad($idProducto, $cantidad, $tipo, $descuento,$comentario,$precionuevo);
    //echo $precionuevo;
    echo json_encode($resultado);
}

function checarPagos() {
    $resultado = $this->cajaModel->checarPagos();

    echo json_encode($resultado);
}

function agregaPago() {

    $tipos = $_POST["tipo"];
    $tipostr = $_POST["tipostr"];
    $cantidad = $_POST["cantidad"];
    $txtReferencia = $_POST["txtReferencia"];

    $resultado = $this->cajaModel->agregaPago($tipos, $tipostr, $cantidad, $txtReferencia);

    echo json_encode($resultado);
}

function guardarVenta() {

    $idFact = $_POST["idFact"];
    $documento = $_POST["documento"];
    $cliente = $_POST["cliente"];
    $suspendida = $_POST["suspendida"];
    $propina = $_POST["propina"];
    $comentario = $_POST["comentario"];

    $resultado = $this->cajaModel->guardarVenta($cliente, $idFact, $documento, $suspendida, $propina, $comentario);

    echo json_encode($resultado);
}

function facturar() {

    $idFact = $_POST["idFact"];
    $idVenta = $_POST["idVenta"];
    $doc = $_POST["doc"];
    $mensaje = $_POST["mensaje"];
    $consumo = $_POST["consumo"];

    if($doc == 3)
    {

        $resultado = $this->cajaModel->facturarRecibo($idFact, $idVenta, 0,$mensaje,$consumo);
    }else
    {
        if($doc == 2)
        {
            $bloqueado = 0;
        }else
        {
            $bloqueado = 1;
        }
        $resultado = $this->cajaModel->facturar($idFact, $idVenta, $bloqueado,$mensaje,$consumo);
    }

    echo json_encode($resultado);
}

function cargaRfcs() {
    $idCliente = $_POST['idCliente'];

    $resultado = $this->cajaModel->cargaRfcs($idCliente);

    echo json_encode($resultado);
}

function checatimbres() {
    $resultado = $this->cajaModel->checatimbres();

    echo json_encode($resultado);
}

function suspenderVenta() {

    $idFact = $_POST['idFact'];
    $documento = $_POST['documento'];
    $cliente = $_POST['cliente'];
    $nombre = $_POST['nombre'];
    $suspendida = $_POST['suspendida'];

    $resultado = $this->cajaModel->suspenderVenta($idFact, $documento, $cliente, $nombre, $suspendida);

    echo json_encode($resultado);
}
function buscaFoodwear(){
    $result = $this->cajaModel->buscaFoodwear();
    echo json_encode($result);
}

function cargarSuspendida() {
    $id_susp = $_POST['id_susp'];

    $resultado = $this->cajaModel->cargarSuspendida($id_susp);

    echo json_encode($resultado);
}

function eliminarSuspendida() {
    $suspendida = $_POST['suspendida'];

    $resultado = $this->cajaModel->eliminarSuspendida($suspendida);

    echo json_encode($resultado);
}

function eliminarPago() {
    $pago = $_POST['pago'];

    $resultado = $this->cajaModel->eliminarPago($pago);

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

    $resultado = $this->cajaModel->guardarFacturacion($UUID,$noCertificadoSAT,$selloCFD,$selloSAT,$FechaTimbrado,$idComprobante,$idFact,$idVenta,$noCertificado,$tipoComp,$trackId,$monto,$cliente,$idRefact,$azurian,$estatus);

    echo json_encode($resultado);
}

function envioFactura()
{
    $uid = $_POST['uid'];
    $correo = $_POST['correo'];
    $azurian = $_POST['azurian'];
    $doc = $_POST['doc'];

    $resultado = $this->cajaModel->envioFactura($uid, $correo, $azurian,$doc);

    echo json_encode($resultado);
}

function checatarjetaregalo()
{
    $numero = $_POST['numero'];
    $monto = $_POST['monto'];

    $resultado = $this->cajaModel->checatarjetaregalo($numero,$monto);

    echo json_encode($resultado);
}

function Iniciarcaja($sucursal,$monto){

    $sucursal = $_POST['sucursal'];
    $monto = $_POST['monto'];

    $resultado = $this->cajaModel->Iniciarcaja($sucursal,$monto);

    echo json_encode($resultado);
}

function observacionFactura(){

    $observacion = $_POST['observacion'];

    $resultado = $this->cajaModel->observacionFactura($observacion);

    echo json_encode($resultado);
}

function checalimitecredito(){

 $cliente = $_POST['cliente'];
 $monto = $_POST['monto'];

 $resultado = $this->cajaModel->checalimitecredito($cliente,$monto);

 echo json_encode($resultado);
}

function pendienteFacturacion(){

    $azurian = $_POST["azurian"];
    $idFact = $_POST["idFact"];
    $monto = $_POST["monto"];
    $cliente = $_POST["cliente"];
    $trackId = $_POST["trackId"];
    $idVenta = $_POST["idVenta"];
    $documento = $_POST["doc"];

    $resultado = $this->cajaModel->pendienteFacturacion($idFact,$monto,$cliente,$idVenta,$trackId,$azurian,$documento);

    echo json_encode($resultado);
}

function alter()
{
   $resultado = $this->cajaModel->alter();

   echo json_encode($resultado);
}

function viewSimulacion()
{
    require('views/caja/simulaFactura.php');
}
function ajustaTotal(){
    $ajuste = $_POST['ajuste'];
   
    $resultado = $this->cajaModel->ajustaTotal($ajuste);

    echo json_encode($resultado);

}   

function simulaFactura()
{
    $ids = $_POST["ids"];
    $resultado = $this->cajaModel->generaCaja($ids);

   echo json_encode($resultado);
}
function findAddenda(){
    $rfc = $_POST['rfc'];
    $resultado = $this->cajaModel->findAddenda($rfc);

   echo json_encode($resultado);
}
    function readFile(){
            //var_dump($_GET['files']);
   
            $data = array();

            if(isset($_GET['files']))
            {  

                $error = false;
                $files = array();

                $uploaddir = './ventasCasio/';
                foreach($_FILES as $file)
                {
                    if(move_uploaded_file($file['tmp_name'], $uploaddir .basename($file['name'])))
                    {
                        $files[] = $uploaddir .$file['name'];
                    }
                    else
                    {
                        $error = true;
                    }
                }
                $data = ($error) ? array('error' => 'There was an error uploading your files') : array('files' => $files);
            }
            else
            {   
                //$result = $this->cajaModel->readFile($uploaddir.$file['name']);
                $data = array('success' => 'Form was submitted', 'formData' => $_POST);
            }

            $result = $this->cajaModel->readFile($uploaddir.$file['name']);
            echo json_encode($data);
        /*$target_dir = "ventasCasio/";
        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
        $uploadOk = 1;
        $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
        // Check if image file is a actual image or fake image
        if(isset($_POST["submit"])) {
            $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
            if($check !== false) {
                echo "File is an image - " . $check["mime"] . ".";
                $uploadOk = 1;
            } else {
                echo "File is not an image.";
                $uploadOk = 1;
            }
        }
        //echo 'Direccion='.$target_file;
                // Check if $uploadOk is set to 0 by an error
                if ($uploadOk == 0) {
                    echo "Sorry, your file was not uploaded.";
                // if everything is ok, try to upload file
                } else {
                    
                    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                        echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
                    } else {
                        echo "Sorry, there was an error uploading your file.";
                    }
                }
                //echo $target_file;
                $result = $this->cajaModel->readFile($target_file);
                echo json_encode($result); */
    }

}

?>