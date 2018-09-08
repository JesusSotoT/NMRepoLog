<?php
include_once("../../../netwarelog/catalog/conexionbd.php"); 

if(isset($_POST['metodo'])){

    switch($_POST['metodo']){
        
        case 'guardarFacturacion':
            guardarFacturacion($_POST['UUID'], $_POST['noCertificadoSAT'], $_POST['selloCFD'], $_POST['selloSAT'], $_POST['FechaTimbrado'], $_POST['idComprobante'], $_POST['idFact'], $_POST['idVenta'], $_POST['noCertificado'], $_POST['tipoComp'], $_POST['monto'], $_POST['cliente'], $_POST['trackId'], $_POST['idRefact'], $_POST['azurian']);
        break;

        case 'pendienteFacturacion':
            pendienteFacturacion($_POST['idFact'], $_POST['precio'], $_POST['cliente'], $_POST['idVenta'], $_POST['trackId'], $_POST['azurian']);
        break;

        case 'envioFactura':
            envioFactura($_POST['uid'], $_POST['correo'], $_POST['azurian'], $_POST['doc'], $_POST['carpeta']);
        break;

        case 'crearZip':
            crearzip($_POST['carpeta']);
        break;
    }
}

function guardarFacturacion($UUID,$noCertificadoSAT,$selloCFD,$selloSAT,$FechaTimbrado,$idComprobante,$idFact,$idVenta,$noCertificado,$tipoComp,$monto,$cliente,$trackId,$idRefact,$azurian){
    $azurian=base64_encode($azurian);
    $fechaactual=preg_replace('/T/', ' ', $FechaTimbrado); 
    if($idRefact=='c'){
        $tipoComp='C';
        $q=mysql_query("UPDATE pvt_respuestaFacturacion SET borrado=2 WHERE idSale='$idVenta'");
    }
    $q=mysql_query("INSERT INTO pvt_respuestaFacturacion
        (idSale,idFact,folio,factNum,serieCsdSat,serieCsdEmisor,selloDigitalSat,selloDigitalEmisor,fecha,borrado,tipoComp,idComprobante,cadenaOriginal) VALUES ('".$idVenta."','".$idFact."','".$UUID."','".$trackId."','".$noCertificadoSAT."','".$noCertificado."','".$selloSAT."','".$selloCFD."','".$fechaactual."',0,'".$tipoComp."','".$idComprobante."','".$azurian."');");
    $insertedId=mysql_insert_id();
    if(is_numeric($insertedId))
    {
        mysql_query("UPDATE pvt_contadorFacturas set total=total+1 where id=1");
        mysql_query("UPDATE pvt_serie_folio SET folio=folio+1 where id=1");
    }

    if(preg_match('/all/', $idRefact)){
        $idRefact=preg_replace('/all/', '', $idRefact);
        mysql_query("UPDATE pvt_pendienteFactura SET facturado=1 WHERE id_sale in (".$idRefact.")");
    }

    if($idRefact>0 && $idRefact!='c'){
        mysql_query("UPDATE pvt_pendienteFactura SET facturado=1 WHERE id_sale='$idRefact'");
    }

    return $insertedId;
}

function pendienteFacturacion($idFacturacion,$monto,$cliente,$idventa,$trackId,$azurian){
    $azurian=base64_encode($azurian);
    $fechaactual=date('Y-m-d H:i:s'); 

    if(is_numeric($cliente)){
        mysql_query("insert into pvt_pendienteFactura values('',".$idventa.",'".$fechaactual."',".$cliente.",'".$monto."',0,'".$trackId."','".$azurian."');");
    }
    else{
        mysql_query("insert into pvt_pendienteFactura values('',".$idventa.",'".$fechaactual."',NULL,'".$monto."',0,'".$trackId."','".$azurian."');");
    }
}

function envioFactura($uid,$Email,$azurian,$doc,$carpeta)
{
    $azurian=json_decode($azurian);
    $azurian=object_to_array($azurian);
    //var_dump($azurian);
    $datosTimbrado=$azurian['datosTimbrado'];

    if($azurian['FiscalesEmisor']['noExterior']==''){
        $nemi='';
    }else{
        $nemi=' #'.$azurian['FiscalesEmisor']['noExterior'];
    }

    if($azurian['DomicilioReceptor']['noExterior']==''){
        $nrec='';
    }else{
        $nrec=' #'.$azurian['DomicilioReceptor']['noExterior'];
    }

    mysql_query("UPDATE venta SET observacion='".$azurian['Observacion']['Observacion']."' WHERE idVenta='".$azurian['Venta']['venta']."'; ");
    
    include "../../../modulos/SAT/PDF/CFDIPDF.php";

    $obj=new CFDIPDF();
    //$obj->ponerColor('#333333');
    $obj->datosCFD($datosTimbrado['UUID'],$azurian['Basicos']['folio'],$datosTimbrado['noCertificado'],$datosTimbrado['FechaTimbrado'],$datosTimbrado['FechaTimbrado'],$datosTimbrado['noCertificadoSAT'],$azurian['Basicos']['formaDePago'],$azurian['Basicos']['tipoDeComprobante']);
    $obj->lugarE($azurian['Basicos']['LugarExpedicion']);
    $obj->datosEmisor($azurian['Emisor']['nombre'],$azurian['Emisor']['rfc'],$azurian['FiscalesEmisor']['calle'].$nemi,$azurian['FiscalesEmisor']['localidad'],$azurian['FiscalesEmisor']['colonia'],$azurian['FiscalesEmisor']['municipio'],$azurian['FiscalesEmisor']['estado'],$azurian['FiscalesEmisor']['codigoPostal'],$azurian['FiscalesEmisor']['pais'],$azurian['Regimen']['Regimen']);
    $obj->datosReceptor($azurian['Receptor']['nombre'],$azurian['Receptor']['rfc'],$azurian['DomicilioReceptor']['calle'].$nrec,$azurian['DomicilioReceptor']['localidad'],$azurian['DomicilioReceptor']['colonia'],$azurian['DomicilioReceptor']['municipio'],$azurian['DomicilioReceptor']['estado'],$azurian['DomicilioReceptor']['codigoPostal'],$azurian['DomicilioReceptor']['pais']);
    $obj->agregarConceptos($azurian['Conceptos']['conceptosOri']);
    $obj->agregarTotal($azurian['Basicos']['subTotal'],$azurian['Basicos']['total'],$azurian['nn']['nn']);
    $obj->agregarMetodo($azurian['Basicos']['metodoDePago'],'',$azurian['Basicos']['total']);   
    $obj->agregarSellos($datosTimbrado['csdComplemento'],$datosTimbrado['selloCFD'],$datosTimbrado['selloSAT']);
    $obj->agregarObservaciones($azurian['Observacion']['Observacion']);
    $obj->generar("../../../netwarelog/archivos/1/organizaciones/".$azurian['org']['logo']."",0,"../");
    $obj->borrarConcepto();

    if($Email!=''){

        require_once('../../modulos/phpmailer/sendMail.php');

        $mail->From = "mailer@netwarmonitor.com";
        $mail->FromName = "NetwareMonitor";
        $mail->Subject = "Factura Generada";
        $mail->AltBody = "NetwarMonitor";
        $mail->MsgHTML('Factura Generada');
        $mail->AddAttachment('../../../modulos/facturas/'.$uid.".xml");
        $mail->AddAttachment('../../../modulos/facturas/'.$uid.".pdf");
        $mail->AddAddress($Email,$Email);

        @$mail->Send();
    }

    @copy("../../../modulos/facturas/".$uid.".xml",$carpeta."/".$uid.".xml");
    @copy("../../../modulos/facturas/".$uid.".pdf",$carpeta."/".$uid.".pdf");
    //
    exit();
}

function crearzip($carpeta){
    $zip = new ZipArchive();
    $filename = $carpeta.'.zip';

    if($handle = opendir($carpeta)){
        if($zip->open($filename,ZIPARCHIVE::CREATE)===true) {
            while(false !== ($entry = readdir($handle))){
                if(pathinfo($entry, PATHINFO_EXTENSION)=="pdf" || pathinfo($entry, PATHINFO_EXTENSION)=="xml")
                    $zip->addFile($carpeta."/".$entry);
            }
            $zip->close();
        }
        closedir($handle);
    }
    echo $filename;
}

function object_to_array($data){
    if (is_array($data) || is_object($data))
    {
        $result = array();
        foreach ($data as $key => $value){
            $result[$key] = object_to_array($value);
        }
        return $result;
    }
    return $data;
}
?>
