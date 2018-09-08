<?php
//ini_set('display_errors', 1);
//Carga la funciones comunes top y footer
require_once('common.php');

//Carga el modelo para este controlador
require_once("models/caja.php");

class Caja extends Common
{
    public $CajaModel;

    function __construct()
    {
        //Se crea el objeto que instancia al modelo que se va a utilizar

        $this->CajaModel = new CajaModel();
        $this->CajaModel->connect();
    }

    function __destruct()
    {
        //Se destruye el objeto que instancia al modelo que se va a utilizar
        $this->CajaModel->close();
    } 

    function indexCaja(){
    
    }

    function pendienteFacturacion(){

        $azurian = $_POST["azurian"];
        $idFact = $_POST["idFact"];
        $monto = $_POST["monto"];
        $cliente = $_POST["cliente"];
        $trackId = $_POST["trackId"];
        $idVenta = $_POST["idVenta"];
        $documento = $_POST["doc"];

        $resultado = $this->CajaModel->pendienteFacturacion($idFact,$monto,$cliente,$idVenta,$trackId,$azurian,$documento);

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

        $resultado = $this->CajaModel->guardarFacturacion($UUID,$noCertificadoSAT,$selloCFD,$selloSAT,$FechaTimbrado,$idComprobante,$idFact,$idVenta,$noCertificado,$tipoComp,$trackId,$monto,$cliente,$idRefact,$azurian,$estatus);

        echo json_encode($resultado);
    }

    function envioFactura(){
        $uid = $_POST['uid'];
        $correo = $_POST['correo'];
        $azurian = $_POST['azurian'];
        $doc = $_POST['doc'];

        $resultado = $this->CajaModel->envioFactura($uid, $correo, $azurian,$doc);

        echo json_encode($resultado);
    }

    function datosorganizacion(){
        $res = $this->CajaModel->datosorganizacion();
        return $res;
    }

    function datosventa($idVenta){
        $res = $this->CajaModel->datosventa($idVenta);
        return $res;
    } 

    function obtenerIdVenta($codigoTicket){
        return $this->CajaModel->obtenerIdVenta($codigoTicket);
    }

    function verificaFacturacionValida(){
        echo $this->CajaModel->verificaFacturacionValida($_POST["codigoTicket"]);
    }

    function formatofecha($fecha){
        list($anio,$mes,$rest)=explode("-",$fecha);
        list($dia,$hora)=explode(" ",$rest);
        
        return $dia."/".$mes."/".$anio." ".$hora;
    }

    function productosventa($idVenta){
        $res = $this->CajaModel->productosventa($idVenta);
        return $res;
    }

    function object_to_array($data) {
        if (is_array($data) || is_object($data)) {
            $result = array();
            foreach ($data as $key => $value) {
                $result[$key] = $this->object_to_array($value);
            }
            return $result;
        }
    return $data;
    }

    function pagos($idVenta){
        $res = $this->CajaModel->pagos($idVenta);
        return $res;
    }

    function verificaRfcmodal(){
        $rfc = $_POST['rfc'];
        $res = $this->CajaModel->verificaRfcmodal($rfc);
        echo json_encode($res);
    }

    function datosFacturacionCliente(){
        $idFact = $_POST['id'];
        $datos = $this->CajaModel->datosFacturacionCliente($idFact);
        echo json_encode($datos);
    }

    function guardaClientFact(){
        $idFac = $_POST['idFac'];
        $rfc = $_POST['rfc'];
        $razSoc = $_POST['razSoc'];
        $email = $_POST['email'];
        $pais = $_POST['pais'];
        $regimen = $_POST['regimen'];
        $domicilio = $_POST['domicilio'];
        $numero = $_POST['numero'];
        $cp = $_POST['cp'];
        $col = $_POST['col'];
        $estado = $_POST['estado'];
        $municipio = $_POST['municipio'];
        $ciudad = $_POST['ciudad'];

        if($idFac!=''){
            $dataFact = $this->CajaModel->updateDatosFac($idFac,$rfc,$razSoc,$email,$pais,$regimen,$domicilio,$numero,$cp,$col,$estado,$municipio,$ciudad);
        }else{
            $dataFact = $this->CajaModel->newClientDatfact($idFac,$rfc,$razSoc,$email,$pais,$regimen,$domicilio,$numero,$cp,$col,$estado,$municipio,$ciudad);
        }
        echo json_encode($dataFact);

    }

    function oneFact(){
        $idComunFactu = $_POST['idComunFactu'];
        $idVenta = $_POST['venta'];

        $respuesta = $this->CajaModel->oneFact($idComunFactu,$idVenta);

        echo json_encode($respuesta);
    }

    function municipios(){
        $idEstado = $_POST['estado'];
        $municipios = $this->CajaModel->municipios($idEstado);

        echo json_encode($municipios);
    }
}


?>
