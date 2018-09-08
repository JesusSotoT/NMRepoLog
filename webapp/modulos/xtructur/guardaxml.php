<?php
date_default_timezone_set('America/Mexico_City');
$fechahoy=date('Y-m-d h:i:s');

include_once("../../netwarelog/webconfig.php");
include("../../libraries/xml2json/xml2json.php");
$db = mysql_connect($servidor, $usuariobd, $clavebd)
or die("Connection Error: " . mysql_error());
mysql_select_db($bd) or die("Error conecting to db.");
mysql_query("set names 'utf8'");

	$fac_folio=$_POST['fac_folio'];
    $fac_fecha=$_POST['fac_fecha'];
    $fac_total=$_POST['fac_total'];
    $fac_uuid=$_POST['fac_uuid'];
    $fac_concepto=$_POST['concepto'];
    $xmlfile=$_POST['xmlfile'];
    $idoc=$_POST['idoc'];
    $estiid=$_POST['estiid'];
    $fac_subtotal=$_POST['fac_subtotal'];


	date_default_timezone_set("Mexico/General");
    $fecha_subida=date('Y-m-d H:i:s'); 

    $cookie_xtructur = unserialize($_COOKIE['xtructur']);
    $id_obra = $cookie_xtructur['id_obra'];
    session_start();
    $idusr = $_SESSION['accelog_idempleado'];

    if($idoc>0){
        mysql_query(" INSERT INTO constru_xml_pedis (id_obra,id_uduario,id_pedi,fecha_subida,xml_file,borrado,folio,total,fecha_fac,id_estimacion, dedonde) VALUES ('$id_obra','$idusr','$idoc','$fecha_subida','$xmlfile',0,'$fac_uuid','$fac_total','$fac_fecha','$estiid','bit_prov') ") or die("Couldn t execute query.".mysql_error());
    }

    if($idoc==0){
        mysql_query(" INSERT INTO constru_xml_pedis (id_obra,id_uduario,id_pedi,fecha_subida,xml_file,borrado,folio,total,fecha_fac,id_estimacion,dedonde) VALUES ('$id_obra','$idusr','$idoc','$fecha_subida','$xmlfile',0,'$fac_uuid','$fac_total','$fac_fecha','$estiid','bit_subc') ") or die("Couldn t execute query.".mysql_error());
    }






$cont_xml = simplexml_load_file('../../modulos/cont/xmls/facturas/temporales/'.$xmlfile);
$json = xmlToArray($cont_xml);

if( isset($json['Comprobante']['@Version']) ){
    //3.3
    $folio = $json['Comprobante']['@Folio'];
    $uuid=$fac_uuid;
    $er='R';
    $tipo='Egreso';
    $serie= $json['Comprobante']['@Serie'];
    $emisor= $json['Comprobante']['cfdi:Emisor']['@Nombre'];
    $receptor= $json['Comprobante']['cfdi:Receptor']['@Nombre'];
    $importe= $json['Comprobante']['@Total'];
    $moneda= $json['Comprobante']['@Moneda'];
    $rfc= $json['Comprobante']['cfdi:Emisor']['@Rfc'];
    $fecha=$json['Comprobante']['@Fecha'];
    $fecha_subida=$fechahoy;
    $xml='../../modulos/cont/xmls/facturas/temporales/'.$uuid.'.xml';
    $version=$json['Comprobante']['@Version'];
    $cancelada=0;
    $json=json_encode($json,JSON_HEX_APOS);
    $temporal=1;
}else{
    //3.2
    $folio = $json['Comprobante']['@folio'];
    $uuid=$fac_uuid;
    $er='R';
    $tipo='Egreso';
    $serie= $json['Comprobante']['@serie'];
    $emisor= $json['Comprobante']['cfdi:Emisor']['@nombre'];
    $receptor= $json['Comprobante']['cfdi:Receptor']['@nombre'];
    $importe= $json['Comprobante']['@total'];
    $moneda= $json['Comprobante']['@Moneda'];
    $rfc= $json['Comprobante']['cfdi:Emisor']['@rfc'];
    $fecha=$json['Comprobante']['@fecha'];
    $fecha_subida=$fechahoy;
    $xml='../../modulos/cont/xmls/facturas/temporales/'.$uuid.'.xml';
    $version=$json['Comprobante']['@version'];
    $cancelada=0;
    $json=json_encode($json,JSON_HEX_APOS);
    $temporal=1;


}

mysql_query(" INSERT INTO cont_facturas (folio,uuid,er,tipo,serie,emisor,receptor,importe,moneda,rfc,fecha,fecha_subida,xml,version,cancelada,json,temporal) VALUES ('$folio','$uuid','$er','$tipo','$serie','$emisor','$receptor','$importe','$moneda','$rfc','$fecha','$fecha_subida','$xml','$version','$cancelada','$json','$temporal') ") or die("Couldn t execute query.".mysql_error());



echo 1;
?>