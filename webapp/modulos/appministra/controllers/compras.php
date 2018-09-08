<?php
//Carga la funciones comunes top y footer
require('common.php');

//Carga el modelo para este controlador
require("models/compras.php");

class Compras extends Common
{
    public $ComprasModel;

    function __construct()
    {
        //Se crea el objeto que instancia al modelo que se va a utilizar
        $this->ComprasModel = new ComprasModel();
        $this->ComprasModel->connect();
    }

    function __destruct()
    {
        //Se destruye el objeto que instancia al modelo que se va a utilizar
        $this->ComprasModel->close();
        
                    
    }

    function a_verificarPagos(){
        $idoc = $_POST['idoc'];
        $pagos = $this->ComprasModel->verificarPagos($idoc);
        echo $pagos;
    }

    function calculaPrecios(){
      $productos = $_POST['productos'];

      $precios = $this->ComprasModel->calculaImpuestos($productos);
      echo json_encode($precios);
    }

    function quitar_tildes($cadena) {
    $no_permitidas= array ("á","é","í","ó","ú","Á","É","Í","Ó","Ú","ñ","À","Ã","Ì","Ò","Ù","Ã™","Ã ","Ã¨","Ã¬","Ã²","Ã¹","ç","Ç","Ã¢","ê","Ã®","Ã´","Ã»","Ã‚","ÃŠ","ÃŽ","Ã”","Ã›","ü","Ã¶","Ã–","Ã¯","Ã¤","«","Ò","Ã","Ã„","Ã‹","/");
    $permitidas= array ("a","e","i","o","u","A","E","I","O","U","n","N","A","E","I","O","U","a","e","i","o","u","c","C","a","e","i","o","u","A","E","I","O","U","u","o","O","i","a","e","U","I","A","E","");
    $texto = str_replace($no_permitidas, $permitidas ,$cadena);
    return $texto;
  }

    function existeXML($nombreArchivo){
    $ruta = "../cont/xmls/facturas/";
    $directorio = opendir($ruta);
    $rutas="";
    while($carpeta = readdir($directorio)){
      if($carpeta != '.' && $carpeta != '..' && $carpeta != '.file' && $carpeta !='.DS_Store'){
          if (is_dir($ruta.$carpeta)){
              $dir = opendir($ruta.$carpeta);
              while($archivo = readdir($dir))
          {
            if($archivo != '.' && $archivo != '..' && $archivo != '.file' && $archivo !='.DS_Store' && $archivo != '.file.rtf'){
              $archivo = str_replace("-Cobro", "", $archivo);
              $archivo = str_replace("-Pago", "", $archivo);
              $archivo = str_replace("Parcial-", "", $archivo);
              $archivo = str_replace("-Nomina", "", $archivo);
              $archiv = $this->quitar_tildes($archivo."");
              $nombreArchiv= $this->quitar_tildes($nombreArchivo);
              
              if (preg_match("/".$nombreArchiv."/i", $archiv)){//i para no diferenciar mayus y minus
              //if($nombreArchivo == $archivo){
                if($carpeta!="repetidos"){
                  if($carpeta!="temporales"){
                    $poliza =  $this->CaptPolizasModel->GetAllPolizaInfoActiva($carpeta);
                    if($poliza!=0){
                      switch($poliza['idtipopoliza']){
                        case 1: $p="Ingresos"; break;
                        case 2: $p="Egresos"; break;
                        case 3: $p="Diario"; break;
                      }
                      $rutas.= " (Poliza:".$poliza['numpol']." ".$p." ".$poliza['fecha'].")";
                    }
                  }else{
                    $rutas.= " (Almacen)";
                  }
                }
              }
              
            }
          }
              
            }
      }
    
        }
    return $rutas;
  } 

    function valida_xsd($version,$xml) 
  {

    libxml_use_internal_errors(true);   
    switch ($version) 
    {
        case "2.0":
          $ok = $xml->schemaValidate("../cont/xmls/valida_xmls/xsds/cfdv2complemento.xsd");
          break;
        case "2.2":
          $ok = $xml->schemaValidate("../cont/xmls/valida_xmls/xsds/cfdv22complemento.xsd");
          break;
        case "3.0":
          $ok = $xml->schemaValidate("../cont/xmls/valida_xmls/xsds/cfdv3complemento.xsd");
          break;
        case "3.2":
          $ok = $xml->schemaValidate("../cont/xmls/valida_xmls/xsds/cfdv32.xsd");
          break;
        default:
          $ok = 0;
    }
    return $ok;
  }

  function getpath($qry) 
  {
    global $xp;
    $prm = array();
    $nodelist = $xp->query($qry);
    foreach ($nodelist as $tmpnode)  
    {
        $prm[] = trim($tmpnode->nodeValue);
      }
    $ret = (sizeof($prm)<=1) ? $prm[0] : $prm;
    return($ret);
  }

  function valida_en_sat($rfc,$rfc_receptor,$total,$uuid) 
  {
      error_reporting(E_ALL);
      require_once('../cont/xmls/valida_xmls/nusoap/nusoap.php');
      error_reporting(E_ALL & ~(E_STRICT|E_NOTICE|E_WARNING|E_DEPRECATED));
      $url = "https://consultaqr.facturaelectronica.sat.gob.mx/consultacfdiservice.svc?wsdl";

      $soapclient = new nusoap_client($url,$esWSDL=true);
      $soapclient->soap_defencoding = 'UTF-8'; 
      $soapclient->decode_utf8 = false;

      $rfc_emisor = utf8_encode($rfc);
      $rfc_receptor = utf8_encode($rfc_receptor);
      $impo = (double)$total;
      $impo=sprintf("%.6f", $impo);
      $impo = str_pad($impo,17,"0",STR_PAD_LEFT);

      $uuid = strtoupper($uuid);

      $factura = "?re=$rfc_emisor&rr=$rfc_receptor&tt=$impo&id=$uuid";

      $prm = array('expresionImpresa'=>$factura);

      $buscar=$soapclient->call('Consulta',$prm);

      //echo "Status del C&oacute;digo: ".$buscar['ConsultaResult']['CodigoEstatus']."<br>";
      //echo "Status: ".$buscar['ConsultaResult']['Estado']."<br>";
      if($buscar['ConsultaResult']['Estado'] == "Cancelado")
      {
        return 0;
      }
      else
      {
        return 1;
      }

  }

    function subeFactura()
    {
        $nn=0;
        global $xp;
        $facturasNoValidas = $facturasValidas = '';
        $numeroInvalidos = $numeroValidos = $no_hay_problema = 0;
        $nombre = "";
        $maximo = count($_FILES['factura']['name']);
        $maximo = (intval($maximo)-1);
        $ruta = "../cont/xmls/facturas/temporales/";
      
        $extension = end(explode('.', $_FILES['factura']['name'][0]));
        if($extension == "zip")
        {
          $zipoxml = "tempo.zip";
        }
        if($extension == "xml")
        {
          $zipoxml = "tempo.xml";
        }

        if(move_uploaded_file($_FILES["factura"]["tmp_name"][0], $ruta.$zipoxml))
        {
          $zip = new ZipArchive;
          if($extension == "xml")
          {
            mkdir($ruta."tempo/", 0777);
            copy($ruta.$zipoxml,$ruta."tempo/".$zipoxml);
            unlink($ruta.$zipoxml);
            $zip->open($ruta.'tempo.zip', ZipArchive::CREATE);
            $zip->addFile($ruta."tempo/".$zipoxml,"tempo/".$zipoxml);
            $zip->close();    
            unlink($ruta."tempo/".$zipoxml);
            rmdir($ruta."tempo/");
          }
          mkdir($ruta."ziptempo/", 0777);
          

          if ($zip->open($ruta."tempo.zip") === TRUE)
          {
              $zip->extractTo($ruta."ziptempo/");
              $zip->close();
              //unlink($ruta."tempo.zip");
              
              if($extension == "xml")
            {
              $foldername = "tempo";
            }

            if($extension == "zip")
            {
              $foldername = $_FILES['factura']['name'][0];
                $foldername = str_replace('.zip', '', $foldername);
            }

              if($directorio = opendir($ruta."ziptempo/$foldername/"))
            {
              while ($archivo = readdir($directorio)) 
              {
                if(is_dir($ruta."ziptempo/$foldername/$archivo"))
                {
                  rmdir($ruta."ziptempo/$foldername/$archivo/");
                }
                elseif($archivo != '.' AND $archivo != '..' AND $archivo != '.DS_Store' AND $archivo != '.file')
                {
                  //Comienza obtener UUID---------------------------
                  $file   = $ruta."ziptempo/$foldername/".$archivo;
                  $texto  = file_get_contents($file);
                  $texto  = preg_replace('{<Addenda.*/Addenda>}is', '<Addenda/>', $texto);
                  $texto  = preg_replace('{<cfdi:Addenda.*/cfdi:Addenda>}is', '<cfdi:Addenda/>', $texto);
                  $xml  = new DOMDocument();
                  $xml->loadXML($texto);
                  

                  $xp = new DOMXpath($xml);

                  $data['uuid']   = $this->getpath("//@UUID");
                  $data['folio']  = $this->getpath("//@folio");
                  $data['emisor'] = $this->getpath("//@nombre");
                  $data['version'] = $this->getpath("//@version");
                  $data['fecha'] = $this->getpath("//@FechaTimbrado");
                  $data['descripcion'] = $this->getpath("//@descripcion");

                  //$comprobante = $xp->query("/cfdi:Conceptos");
                  /*
                  $elements = $xp->query("//cfdi:Conceptos");

                  if (!is_null($elements)) {
                    foreach ($elements as $element) {
                      //var_dump($element->childNodes);
                      foreach ($element->childNodes as $ele2) {
                        var_dump($ele2);
                      }
                      //echo "<br/>[". $element->nodeName. "]";
                      foreach ($nodes as $node) {
                        var_dump($node->parentNode);
                        echo '1'.$node->parentNode. "\n";
                      }
                      exit();
                    }
                  }
*/
                      $version = $data['version'];

                  
                  $data['total'] = $this->getpath("//@total");
                  $data['subTotal'] = $this->getpath("//@subtotal");
                  $data['rfc'] = $this->getpath("//@rfc");
                  
                  $tipo = explode('.',$archivo);
                  //Termina obtener UUID---------------------------
          
                  $rfcOrganizacion= $this->ComprasModel->rfcOrganizacion();
                 
                  if($data['rfc'][0] == $rfcOrganizacion['RFC'])
                  {
                    $nombre = $data['emisor'][1];
                  }
                  elseif($data['rfc'][1] == $rfcOrganizacion['RFC'])
                  {
                    $nombre = $data['emisor'][0];
                    
                  }
                  //echo $version[0];
                  if($this->valida_xsd($version[0],$xml) && strtolower($tipo[1]) == "xml")
                  {

                    if($rfcOrganizacion['RFC'] != $data['rfc'][0] &&  $rfcOrganizacion['RFC']!= $data['rfc'][1])
                    {


                      $noOrganizacion = 0;
                      $numeroInvalidos++;
                      $facturasNoValidas .= $archivo."(RFC no de Organizacion),\n";
                    }
                    else
                    { 
                      
                      $noOrganizacion = 1; 
                    }

                    
                    $nombreArchivo = $data['folio']."_".$nombre."_".$data['uuid'].".xml";
                    if($noOrganizacion){
                      
                      $almacen="facturas/repetidos/";
                      $validaexiste = $this->existeXML($nombreArchivo);
                      $repetidos=0;
                      if($validaexiste){
                        $numeroInvalidos++;
                        $noOrganizacion=0;
                        $facturasNoValidas .= $archivo." Ya existe en $validaexiste.\n";
                         $repetidos=1;
                         mkdir ($almacen,0777);
                        rename($file, $almacen.$this->quitar_tildes($nombreArchivo));

                      }else{ $noOrganizacion = 1; }
                    }

                    if($noOrganizacion)
                    {
                      copy($ruta."ziptempo/$foldername/".$archivo,$ruta."/".$this->quitar_tildes($nombreArchivo));
                      $numeroValidos++;
                      $facturasValidas .= $archivo.",\n";
                    }
                    unlink($ruta."ziptempo/$foldername/".$archivo);
                    
                  }
                  else
                  {
                    unlink($ruta."ziptempo/$foldername/".$archivo);
                    $numeroInvalidos++;
                    $facturasNoValidas .= $archivo.",\n";
                  }
                }
              }
              $folder_invalido = 0;
              $files = glob($ruta."ziptempo/$foldername/*/*"); 
                  foreach($files as $file)
                  { 
                    if(is_file($file))
                      unlink($file); 
                    elseif(is_dir($file))
                      rmdir($file);
                    $folder_invalido++;
                  }
              $files = glob($ruta."ziptempo/$foldername/*"); 
                  foreach($files as $file)
                  { 
                    if(is_file($file))
                      unlink($file); 
                    elseif(is_dir($file))
                      rmdir($file);
                  }   
              //rmdir($ruta."ziptempo/$foldername/$foldername/");   
              rmdir($ruta."ziptempo/$foldername/.DS_Store");
              rmdir($ruta."ziptempo/$foldername/");
              rmdir($ruta."ziptempo/");
              unlink($ruta."tempo.zip");
              if(!intval($folder_invalido))
                $funciono = 1;
              else
                $funciono = 0;
            }
            else
            {
              unlink($ruta."tempo.zip");
              $files = glob($ruta.'ziptempo/*/*'); 
              foreach($files as $file)
              { 
                if(is_file($file))
                  unlink($file); 
              }
              $files = glob($ruta.'ziptempo/*'); 
              foreach($files as $file)
              { 
                if(is_file($file))
                  unlink($file); 
              }
              if($directorio = opendir($ruta."ziptempo/"))
              {
                while ($archivo = readdir($directorio)) 
                {
                  if(is_dir($ruta."ziptempo/$archivo"))
                  {
                    rmdir($ruta."ziptempo/$archivo/");
                  }
                }
              }
              
              rmdir($ruta."ziptempo/");
              $funciono = 0;
            }
          }
        }

       /* if( ($funciono*1)>0  && ($numeroValidos*1)>0 ){
          session_start();

        }*/
        
        $data['descripcion']=addslashes($data['descripcion']);
        $datosfactura=$data['folio'].'##'.$data['fecha'].'##'.$data['total'].'##'.$data['uuid'].'##'.$data['descripcion'].'##'.$data['subTotal'];

        echo $funciono."-/-*".$numeroValidos."-/-*".$facturasValidas."-/-*".$numeroInvalidos."-/-*".$facturasNoValidas."-/-*".$repetidos."-/-*".$nombreArchivo."-/-*".$datosfactura;
    }

    function a_quitafactasoc(){
      $xmlfile=$_POST['xmlfile'];
      $ruta = "../cont/xmls/facturas/temporales/";
      unlink($ruta.$xmlfile);
      if (file_exists($ruta.$xmlfile)) {
          echo 1;
      } else {
          echo 0;
      }
    }

    function a_tienesR(){
      $resultReq = $this->ComprasModel->getReqsAutorizar();
      if($resultReq->num_rows>0){
        $reqs = $resultReq->fetch_assoc();
        $treqs=$reqs['reqs'];
        echo $treqs;
      }else{
        echo 0;
      }
    }

    function recepcion(){

      if(isset($_GET['v'])){
        $id_oc=$_GET['id_oc'];
        $id_rec=$_GET['id_rec'];
        $vv=$_GET['v'];

      }else{
        $vv=0;
      }

      $resultReq = $this->ComprasModel->getAlmacenes();
      $arreglo=array();
      $arreglo2=array();
      $idpadre=array();
      if($resultReq['total']>0){
        $x=0;
        foreach ($resultReq['rows'] as $k => $v2) {
          if($v2['id_almacen_tipo']==1){

            $idpadre[]=array('code' => $v2['codigo_sistema'], 'nombre' =>$v2['nombre']);
            $arreglo[$v2['codigo_sistema']]=array('id' => $v2['id'], 'code'=>$v2['codigo_sistema'], 'nombre'=>$v2['nombre']);

          }elseif($v2['id_almacen_tipo']==2){
            $exp=explode('.', $v2['codigo_sistema']);
            foreach ($idpadre as $k3 => $v3) {
     
              if($v3['code']==$exp[0]){
                $idpadre2[]=array('code' => $exp[1], 'nombre' =>$v2['nombre']);
                $name=$v3['nombre'].' > '.$v2['nombre'];
                $arreglo[$v2['codigo_sistema']]=array('id' => $v2['id'], 'code'=>$v2['codigo_sistema'], 'nombre'=>$name);
              }
            }
          }elseif($v2['id_almacen_tipo']==3){
            $exp=explode('.', $v2['codigo_sistema']);
            foreach ($idpadre as $k3 => $v3) {
              if($v3['code']==$exp[0]){
                foreach ($idpadre2 as $k4 => $v4) {
                  if($v4['code']==$exp[1]){
                    $idpadre3[]=array('code' => $exp[2], 'nombre' =>$v2['nombre']);
                    $name=$v3['nombre'].' > '.$v4['nombre'].' > '.$v2['nombre'];
                    $arreglo[$v2['codigo_sistema']]=array('id' => $v2['id'], 'code'=>$v2['codigo_sistema'], 'nombre'=>$name);
                  }
                }
              }
            }
          }elseif($v2['id_almacen_tipo']==4){
            $exp=explode('.', $v2['codigo_sistema']);
            foreach ($idpadre as $k3 => $v3) {
              if($v3['code']==$exp[0]){
                foreach ($idpadre2 as $k4 => $v4) {
                  if($v4['code']==$exp[1]){
                    foreach ($idpadre3 as $k5 => $v5) {
                      if($v4['code']==$exp[2]){
                        $idpadre4[]=array('code' => $exp[3], 'nombre' =>$v2['nombre']);
                        $name=$v3['nombre'].' > '.$v4['nombre'].' > '.$v5['nombre'].' > '.$v2['nombre'];
                        $arreglo[$v2['codigo_sistema']]=array('id' => $v2['id'], 'code'=>$v2['codigo_sistema'], 'nombre'=>$name);
                      }
                    }
                  }
                }
              }
            }
          }
        }
 
      }else{
        $arreglo=0;
      }
      ksort($arreglo);

      /* NUMERO REQUISICIONES POR AUTORIZAR =========
      =============================================== */
      $resultReq = $this->ComprasModel->getReqsAutorizar();
      if($resultReq->num_rows>0){
        $reqs = $resultReq->fetch_assoc();
        $treqs=$reqs['reqs'];
        if($treqs==1){
          $rtext='requisicion / orden';
        }else if($treqs>1){
          $rtext='requisiciones / ordenes';
        }else{
          $rtext='';
        }
      }else{
        $treqs=0;
      }



      /* REQUIERO DE CONFIGURACION ==================
      =============================================== */
      $resultReq = $this->ComprasModel->getPeriodoFecha();
      if($resultReq->num_rows>0){
        $periodoFecha = $resultReq->fetch_assoc();

        $ano=$periodoFecha['ano'];
        $mes=$periodoFecha['mes'];
        $cerrado=$periodoFecha['cerrado'];
        $pc=$periodoFecha['permitir_cerrados'];
        $pa=$periodoFecha['periodos_abiertos'];
        $diaActual=date('d');
        if(strlen($mes)==1){ $mes='0'.$mes; }

        if($cerrado==1 && $pc==0){
          $sd=$ano."-".$mes."-".$diaActual;
          $ed=$ano."-".$mes."-".$diaActual;

          $sd2=$ano."-".$mes."-".$diaActual;
          $ed2=$ano."-".$mes."-".$diaActual;
        }

        if($cerrado==1 && $pc==1 && $pa==1){
          $sd=$ano."-".$mes."-".$diaActual;
          $ed=($ano+1)."-".$mes."-".$diaActual;

          $sd2=$ano."-".$mes."-".$diaActual;
          $ed2=($ano+1)."-".$mes."-".$diaActual;
        }

        if($cerrado==1 && $pc==1 && $pa==0){
          $sd=$ano."-".$mes."-".$diaActual;
          $ed=($ano+1)."-".$mes."-31";

          $sd2=$ano."-".$mes."-".$diaActual;
          $ed2=($ano+1)."-".$mes."-31";
        }

        if($cerrado==1 && $pc==0 && $pa==1){
          $sd=$ano."-".$mes."-".$diaActual;
          $ed=($ano+1)."-".$mes."-".$diaActual;

          $sd2=$ano."-".$mes."-".$diaActual;
          $ed2=($ano+1)."-".$mes."-".$diaActual;
        }

        if($cerrado==0 && $pa==0){
          $sd=$ano."-".$mes."-".$diaActual;
          $ed=$ano."-".$mes."-31";

          $sd2=$ano."-".$mes."-".$diaActual;
          $ed2=($ano+1)."-12-31";
        }

        if($cerrado==0 && $pa==1){
          $sd=$ano."-".$mes."-01";
          $ed=($ano)."-12-31";

          $sd2=$ano."-".$mes."-01";
          $ed2=($ano+1)."-12-31";
        }

       //$sd=$ano."-".$mes."-".$diaActual;
       //echo '<br>';
       //$ed=$ano."-".$mes."-31";

        



      }else{
        $periodoFecha=0;
      }

      $resultReq = $this->ComprasModel->getAlmacen();
      if($resultReq->num_rows>0){
        while ($r = $resultReq->fetch_assoc()) {
          $almacenes[]=$r;
        }
      }else{
        $almacenes=0;
      }



      $resultReq = $this->ComprasModel->getEmpleados();
      if($resultReq->num_rows>0){
        while ($r = $resultReq->fetch_assoc()) {
          $empleados[]=$r;
        }
      }else{
        $empleados=0;
      }

      $resultReq = $this->ComprasModel->getTipoGasto();
      if($resultReq->num_rows>0){
        while ($r = $resultReq->fetch_assoc()) {
          $tipoGasto[]=$r;
        }
      }else{
        $tipoGasto=0;
      }

      $resultReq = $this->ComprasModel->getProveedores();
      if($resultReq->num_rows>0){
        while ($r = $resultReq->fetch_assoc()) {
          $proveedores[]=$r;
        }
      }else{
        $proveedores=0;
      }

      $resultReq = $this->ComprasModel->getProductos();
      if($resultReq->num_rows>0){
        while ($r = $resultReq->fetch_assoc()) {
          $productos[]=$r;
        }
      }else{
        $productos=0;
      }

      $resultReq = $this->ComprasModel->getMonedas();
      if($resultReq->num_rows>0){
        while ($r = $resultReq->fetch_assoc()) {
          $monedas[]=$r;
        }
      }else{
        $monedas=0;
      }
      require('views/compras/recepcion.php');
    }

    function a_adjuntarxml(){
      $idoc=$_POST['idoc'];
      $resultReq =  $this->ComprasModel->listaRecepcionesAdju($idoc);
      $res=array();
      $res['rows']='';
      $res['total']=0;

      if($resultReq->num_rows>0){
        $sumatotal=0;
        while ($r = $resultReq->fetch_array()) {
          $res['rows'][]=$r;
          $sumatotal+=$r['total'];
        }
        $res['total']=$sumatotal;
      }

      $res['xmls']='';
      $res['totalxmls']=0;
      $resultReq =  $this->ComprasModel->listaXmlsCompra($idoc);
      if($resultReq->num_rows>0){
        $sumaxml=0;
        while ($r = $resultReq->fetch_array()) {
          $r['fecha_subida']=substr($r['fecha_subida'],0,10);
          $res['xmls'][]=$r;
          $sumaxml+=$r['imp_factura'];
        }
        $res['totalxmls']=$sumaxml;
      }else{
        $res['xmls']=0;
        $res['totalxmls']=0;
      }

      echo json_encode($res);
    }

    function a_listaRecepciones(){
      $idoc=$_GET['idoc'];
      $resultReq =  $this->ComprasModel->listaRecepciones($idoc);
      $listas=array();
      $listas['data']='';

      if($resultReq->num_rows>0){
        while ($r = $resultReq->fetch_array()) {
          $link='<a  class="btn btn-default btn-xs btn-block">'.$r['id'].'</span></a>';
          $elimin='<a onclick="editReq('.$r['idr'].',0,'.$r['id'].');" class="btn btn-primary btn-xs">Recibir orden</a>';
          if($r['urgente']==0){
            $r[6]='<span class="label label-default" style="cursor:pointer;">Normal</span>';
          }
          if($r['urgente']==1){
            $r[6]='<span class="label label-danger" style="cursor:pointer;">Urgente</span>';
          }
          if($r['activo']==0){
            $elimin='<a onclick="editReq('.$r['id'].');"  class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-edit"></span> Editar</a>
            <a onclick="eliminaReq('.$r['id'].')" class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-remove"></span> Borrar</a>';
            $r[7]='<span class="label label-warning" style="cursor:pointer;">Pendiente autorizar</span>';
          }
          if($r['activo']==1){

            $r[7]='<span class="label label-success" style="cursor:pointer;">Autorizada</span>';
          }
          if($r['activo']==2){

            $link='<a href="#" class="btn btn-default btn-xs disabled btn-block">'.$r['id'].'</a>';
            $r[7]='<span class="label label-default" style="cursor:pointer;">Cancelada</span>';
          }

          if($r['activo']==4 && $r['id_consignacion']==0){

            $link='<a href="#" class="btn btn-default btn-xs disabled btn-block">'.$r['id'].'</a>';
            $r[7]='<span class="label label-success" style="cursor:pointer;">Recibido OK</span>';
            $elimin='<a style="margin:2px;" onclick="editRec('.$r['idr'].',4,'.$r['id'].');"  class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-search"></span> Ver recepcion</a>';
          }

          if($r['activo']==4 && $r['id_consignacion']>0  && $r['fin_consigna']==0) {

            $link='<a href="#" class="btn btn-default btn-xs disabled btn-block">'.$r['id'].'</a>';
            $r[7]='<span class="label label-success" style="cursor:pointer;">Recibido OK</span>';
            $elimin='<a style="margin:2px;" onclick="editRec('.$r['idr'].',4,'.$r['id'].');"  class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-search"></span> Ver recepcion</a><!--<a style="margin:2px;" onclick="editCon('.$r['idr'].',4,'.$r['id'].');"  class="btn btn-primary btn-xs disabled">Comprar consignacion</a>-->';
          }

          if($r['activo']==4 && $r['id_consignacion']>0 && $r['fin_consigna']==1) {

            $link='<a href="#" class="btn btn-default btn-xs disabled btn-block">'.$r['id'].'</a>';
            $r[7]='<span class="label label-success" style="cursor:pointer;">Recibido OK</span>';
            $elimin='<a style="margin:2px;" onclick="editRec('.$r['idr'].',4,'.$r['id'].');"  class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-search"></span> Ver recepcion</a><!--<a style="margin:2px;" onclick="editCon('.$r['idr'].',4,'.$r['id'].');"  class="btn btn-primary btn-xs disabled">Comprar consignacion</a>-->';
          }

          if($r['activo']==5 && $r['id_consignacion']==0){

            $link='<a href="#" class="btn btn-default btn-xs disabled btn-block">'.$r['id'].'</a>';
            $r[7]='<span class="label label-success" style="cursor:pointer;">Recepcion Parcial</span>';
            $elimin='<a style="margin:2px;" onclick="editRec('.$r['idr'].',4,'.$r['id'].');"  class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-search"></span> Ver recepcion</a>';
          }
          if($r['activo']==5 && $r['id_consignacion']>0){

            $link='<a href="#" class="btn btn-default btn-xs disabled btn-block">'.$r['id'].'</a>';
            $r[7]='<span class="label label-success" style="cursor:pointer;">Recepcion Parcial</span>';
            $elimin='<a style="margin:2px;" onclick="editRec('.$r['idr'].',4,'.$r['id'].');"  class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-search"></span> Ver recepcion</a><!--<a style="margin:2px;" onclick="editCon('.$r['idr'].',4,'.$r['id'].');"  class="btn btn-primary btn-xs disabled">Comprar consignacion</a>-->';
          }

          if($r['activo']==6){

            $link='<a href="#" class="btn btn-default btn-xs disabled btn-block">'.$r['idoc'].'</a>';
            $r[7]='<span class="label label-warning" style="cursor:pointer;">Pendiente aclaracion</span>';
            $elimin='<a onclick="editReq('.$r['idreq'].',0,'.$r['idoc'].');" class="btn btn-primary btn-xs">Recibir</a>';
          }



          $r[8]=$elimin;
          $listas['data'][]=$r;
        }
      }else{
        $listas['data']=array();
      }

      echo json_encode($listas);
    }

    function a_listaOrdenesRecepcion(){
      $resultReq =  $this->ComprasModel->listaOrdenesCompra();
      $listas=array();
      $listas['data']='';
      if($resultReq->num_rows>0){
        while ($r = $resultReq->fetch_array()) {
          $link='<a  class="btn btn-default btn-xs btn-block">'.$r['idoc'].'</span></a>';
          $elimin='<a onclick="editReq('.$r['idreq'].',0,'.$r['idoc'].');" class="btn btn-primary btn-xs">Recibir</a>';
          if($r['urgente']==0){
            $r[7]='<span class="label label-default" style="cursor:pointer;">Normal</span>';
          }
          if($r['urgente']==1){
            $r[7]='<span class="label label-danger" style="cursor:pointer;">Urgente</span>';
          }
          if($r['activo']==0){
            $elimin='<a onclick="editReq('.$r['idoc'].');"  class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-edit"></span> Editar</a>
            <a onclick="eliminaReq('.$r['idoc'].')" class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-remove"></span> Borrar</a>';
            $r[8]='<span class="label label-warning" style="cursor:pointer;">Pendiente autorizar</span>';
          }
          if($r['activo']==1){

            $r[8]='<span class="label label-success" style="cursor:pointer;">Autorizada</span>';
          }
          if($r['activo']==2){

            $link='<a href="#" class="btn btn-default btn-xs disabled btn-block">'.$r['idoc'].'</a>';
            $r[8]='<span class="label label-default" style="cursor:pointer;">Cancelada</span>';
          }

          if($r['activo']==4){

            $link='<a href="#" class="btn btn-default btn-xs disabled btn-block">'.$r['idoc'].'</a>';
            $r[8]='<span class="label label-success" style="cursor:pointer;">Recibido OK</span>';
            $elimin='<a onclick="listarec('.$r['idoc'].');"  class="btn btn-default btn-xs"><span class="glyphicon glyphicon-th-list"></span> Ver recepciones</a> <a style="margin-top:4px" onclick="adjuntarxml('.$r['idoc'].');"  class="btn btn-default btn-xs"><span class="glyphicon glyphicon-upload"></span> Adjuntar factura\'s</a>';
          }

          if($r['activo']==5){

            $link='<a href="#" class="btn btn-default btn-xs disabled btn-block">'.$r['idoc'].'</a>';
            $r[8]='<span class="label label-success" style="cursor:pointer;">Recepcion Parcial</span>';
            $elimin=' <a onclick="editReq('.$r['idreq'].',0,'.$r['idoc'].');" class="btn btn-primary btn-xs">Recibir</a> <a onclick="listarec('.$r['idoc'].');"  class="btn btn-default btn-xs"><span class="glyphicon glyphicon-th-list"></span> Ver recepciones</a> <a style="margin-top:4px" onclick="adjuntarxml('.$r['idoc'].');"  class="btn btn-default btn-xs"><span class="glyphicon glyphicon-upload"></span> Adjuntar factura\'s</a>';
          }

          if($r['activo']==6){

            $link='<a href="#" class="btn btn-default btn-xs disabled btn-block">'.$r['idoc'].'</a>';
            $r[8]='<span class="label label-warning" style="cursor:pointer;">Pendiente aclaracion</span>';
            $elimin='<a onclick="editReq('.$r['idreq'].',0,'.$r['idoc'].');" class="btn btn-primary btn-xs">Recibir</a>';
          }



          $r[9]=$elimin;
          $listas['data'][]=$r;
        }
      }else{
        $listas['data']=array();
      }

      echo json_encode($listas);
    }

    function a_change_idoc_idreq(){
      $idoc=$_POST['idoc'];
      $resultReq = $this->ComprasModel->a_change_idoc_idreq($idoc);
      echo $resultReq['rows'][0]['id_requisicion'];
    }

    function a_get_idoc_idrec(){
      $idrec=$_POST['idrec'];
      $resultReq = $this->ComprasModel->a_get_idoc_idrec($idrec);
      echo $resultReq['rows'][0]['id_oc'];
    }

    function a_guardaXmlAdju(){


      $fac_folio=$_POST['fac_folio'];
      $fac_fecha=$_POST['fac_fecha'];
      $fac_total=$_POST['fac_total'];
      $fac_uuid=$_POST['fac_uuid'];
      $fac_concepto=$_POST['concepto'];
      $xmlfile=$_POST['xmlfile'];
      $idoc=$_POST['idoc'];
      $fac_subtotal=$_POST['fac_subtotal'];


      $resu = $this->ComprasModel->guardaXmlAdju($fac_folio,$fac_fecha,$fac_total,$fac_uuid,$fac_concepto,$xmlfile,$idoc,$fac_subtotal);
      echo $resu;
    }

    function ordenes()
    {

      
      

      if(isset($_GET['v'])){
        $id_oc=$_GET['id_oc'];
        $vv=$_GET['v'];

      }else{
        $vv=0;        
      }

      if(isset($_GET['re'])){
        $re=$_GET['re'];
      }else{
        $re=0;
      }

      /* NUMERO REQUISICIONES POR AUTORIZAR =========
      =============================================== */
      $resultReq = $this->ComprasModel->getReqsAutorizar();
      if($resultReq->num_rows>0){
        $reqs = $resultReq->fetch_assoc();
        $treqs=$reqs['reqs'];
        if($treqs==1){
          $rtext='requisicion / orden';
        }else if($treqs>1){
          $rtext='requisiciones / ordenes';
        }else{
          $rtext='';
        }
      }else{
        $treqs=0;
      }



      /* REQUIERO DE CONFIGURACION ==================
      =============================================== */
      $resultReq = $this->ComprasModel->getPeriodoFecha();
      if($resultReq->num_rows>0){
        $periodoFecha = $resultReq->fetch_assoc();

        $ano=$periodoFecha['ano'];
        $mes=$periodoFecha['mes'];
        $cerrado=$periodoFecha['cerrado'];
        $pc=$periodoFecha['permitir_cerrados'];
        $pa=$periodoFecha['periodos_abiertos'];
        $diaActual=date('d');
        if(strlen($mes)==1){ $mes='0'.$mes; }

        if($cerrado==1 && $pc==0){
          $sd=$ano."-".$mes."-".$diaActual;
          $ed=$ano."-".$mes."-".$diaActual;

          $sd2=$ano."-".$mes."-".$diaActual;
          $ed2=$ano."-".$mes."-".$diaActual;
        }

        if($cerrado==1 && $pc==1 && $pa==1){
          $sd=$ano."-".$mes."-".$diaActual;
          $ed=($ano+1)."-".$mes."-".$diaActual;

          $sd2=$ano."-".$mes."-".$diaActual;
          $ed2=($ano+1)."-".$mes."-".$diaActual;
        }

        if($cerrado==1 && $pc==1 && $pa==0){
          $sd=$ano."-".$mes."-".$diaActual;
          $ed=($ano+1)."-".$mes."-31";

          $sd2=$ano."-".$mes."-".$diaActual;
          $ed2=($ano+1)."-".$mes."-31";
        }

        if($cerrado==1 && $pc==0 && $pa==1){
          $sd=$ano."-".$mes."-".$diaActual;
          $ed=($ano+1)."-".$mes."-".$diaActual;

          $sd2=$ano."-".$mes."-".$diaActual;
          $ed2=($ano+1)."-".$mes."-".$diaActual;
        }

        if($cerrado==0 && $pa==0){
          $sd=$ano."-".$mes."-".$diaActual;
          $ed=$ano."-".$mes."-31";

          $sd2=$ano."-".$mes."-".$diaActual;
          $ed2=($ano+1)."-12-31";
        }

        if($cerrado==0 && $pa==1){
          $sd=$ano."-".$mes."-01";
          $ed=($ano)."-12-31";

          $sd2=$ano."-".$mes."-01";
          $ed2=($ano+1)."-12-31";
        }

       //$sd=$ano."-".$mes."-".$diaActual;
       //echo '<br>';
       //$ed=$ano."-".$mes."-31";

        



      }else{
        $periodoFecha=0;
      }

      $resultReq = $this->ComprasModel->getAlmacen();
      if($resultReq->num_rows>0){
        while ($r = $resultReq->fetch_assoc()) {
          $almacenes[]=$r;
        }
      }else{
        $almacenes=0;
      }



      $resultReq = $this->ComprasModel->getEmpleados();
      if($resultReq->num_rows>0){
        while ($r = $resultReq->fetch_assoc()) {
          $empleados[]=$r;
        }
      }else{
        $empleados=0;
      }

      $resultReq = $this->ComprasModel->getTipoGasto();
      if($resultReq->num_rows>0){
        while ($r = $resultReq->fetch_assoc()) {
          $tipoGasto[]=$r;
        }
      }else{
        $tipoGasto=0;
      }

      $resultReq = $this->ComprasModel->getProveedores();
      if($resultReq->num_rows>0){
        while ($r = $resultReq->fetch_assoc()) {
          $proveedores[]=$r;
        }
      }else{
        $proveedores=0;
      }

      $resultReq = $this->ComprasModel->getProductos();
      if($resultReq->num_rows>0){
        while ($r = $resultReq->fetch_assoc()) {
          $productos[]=$r;
        }
      }else{
        $productos=0;
      }

      $resultReq = $this->ComprasModel->getMonedas();
      if($resultReq->num_rows>0){
        while ($r = $resultReq->fetch_assoc()) {
          $monedas[]=$r;
        }
      }else{
        $monedas=0;
      }

      $resultReq = $this->ComprasModel->getUsuario();
      if($resultReq->num_rows>0){
        $set = $resultReq->fetch_assoc();
        $username=$set['username'];
        $iduser=$set['idempleado'];
      }else{
        $username='Favor de salir y loguearse nuevamente';
        $iduser='0';
      }

      $resultReq = $this->ComprasModel->modCostoCompras();
      if($resultReq->num_rows>0){
        $set = $resultReq->fetch_assoc();
        $modCosto=$set['mod_costo_compras'];
      }else{
        $modCosto=1;
      }

      require('views/compras/ordenes2.php');
    }

    function requisiciones()
    {

      /* REQUIERO DE CONFIGURACION ==================
      =============================================== */
      $resultReq = $this->ComprasModel->getPeriodoFecha();
      if($resultReq->num_rows>0){
        $periodoFecha = $resultReq->fetch_assoc();

        $ano=$periodoFecha['ano'];
        $mes=$periodoFecha['mes'];
        $cerrado=$periodoFecha['cerrado'];
        $pc=$periodoFecha['permitir_cerrados'];
        $pa=$periodoFecha['periodos_abiertos'];
        $diaActual=date('d');
        if(strlen($mes)==1){ $mes='0'.$mes; }

        if($cerrado==1 && $pc==0){
          $sd=$ano."-".$mes."-".$diaActual;
          $ed=$ano."-".$mes."-".$diaActual;

          $sd2=$ano."-".$mes."-".$diaActual;
          $ed2=$ano."-".$mes."-".$diaActual;
        }

        if($cerrado==1 && $pc==1 && $pa==1){
          $sd=$ano."-".$mes."-".$diaActual;
          $ed=($ano+1)."-".$mes."-".$diaActual;

          $sd2=$ano."-".$mes."-".$diaActual;
          $ed2=($ano+1)."-".$mes."-".$diaActual;
        }

        if($cerrado==1 && $pc==1 && $pa==0){
          $sd=$ano."-".$mes."-".$diaActual;
          $ed=($ano+1)."-".$mes."-31";

          $sd2=$ano."-".$mes."-".$diaActual;
          $ed2=($ano+1)."-".$mes."-31";
        }

        if($cerrado==1 && $pc==0 && $pa==1){
          $sd=$ano."-".$mes."-".$diaActual;
          $ed=($ano+1)."-".$mes."-".$diaActual;

          $sd2=$ano."-".$mes."-".$diaActual;
          $ed2=($ano+1)."-".$mes."-".$diaActual;
        }

        if($cerrado==0 && $pa==0){
          $sd=$ano."-".$mes."-".$diaActual;
          $ed=$ano."-".$mes."-31";

          $sd2=$ano."-".$mes."-".$diaActual;
          $ed2=($ano+1)."-12-31";
        }

        if($cerrado==0 && $pa==1){
          $sd=$ano."-".$mes."-01";
          $ed=($ano)."-12-31";

          $sd2=$ano."-".$mes."-01";
          $ed2=($ano+1)."-12-31";
        }

       //$sd=$ano."-".$mes."-".$diaActual;
       //echo '<br>';
       //$ed=$ano."-".$mes."-31";

        



      }else{
        $periodoFecha=0;
      }

      $resultReq = $this->ComprasModel->getAlmacen();
      if($resultReq->num_rows>0){
        while ($r = $resultReq->fetch_assoc()) {
          $almacenes[]=$r;
        }
      }else{
        $almacenes=0;
      }

      $resultReq = $this->ComprasModel->getEmpleados();
      if($resultReq->num_rows>0){
        while ($r = $resultReq->fetch_assoc()) {
          $empleados[]=$r;
        }
      }else{
        $empleados=0;
      }

      $resultReq = $this->ComprasModel->getTipoGasto();
      if($resultReq->num_rows>0){
        while ($r = $resultReq->fetch_assoc()) {
          $tipoGasto[]=$r;
        }
      }else{
        $tipoGasto=0;
      }

      $resultReq = $this->ComprasModel->getProveedores();
      if($resultReq->num_rows>0){
        while ($r = $resultReq->fetch_assoc()) {
          $proveedores[]=$r;
        }
      }else{
        $proveedores=0;
      }

      $resultReq = $this->ComprasModel->getProductos();
      if($resultReq->num_rows>0){
        while ($r = $resultReq->fetch_assoc()) {
          $productos[]=$r;
        }
      }else{
        $productos=0;
      }

      $resultReq = $this->ComprasModel->getMonedas();
      if($resultReq->num_rows>0){
        while ($r = $resultReq->fetch_assoc()) {
          $monedas[]=$r;
        }
      }else{
        $monedas=0;
      }

      $resultReq = $this->ComprasModel->getUsuario();
      if($resultReq->num_rows>0){
        $set = $resultReq->fetch_assoc();
        $username=$set['username'];
        $iduser=$set['idempleado'];
      }else{
        $username='Favor de salir y loguearse nuevamente';
        $iduser='0';
      }

      require('views/compras/requisiciones.php');
    }

    function a_nuevarequisicion()
    {
      $resultReq = $this->ComprasModel->getLastNumRequisicion();
      if($resultReq->num_rows>0){
        $row = $resultReq->fetch_array();
        $JSON = array('success' =>1, 'requisicion'=>$row['id']);
      }else{
        $JSON = array('success' =>0);
      }
      
      echo json_encode($JSON);

    }

    function a_imprimir()
    {

      $idsProductos=$_POST['idsProductos'];
      $solicitante=$_POST['solicitante'];
      $tipogasto=$_POST['tipogasto'];
      $moneda=$_POST['moneda'];
      $proveedor=$_POST['proveedor'];
      $urgente=$_POST['urgente'];
      $inventariable=$_POST['inventariable'];
      $moneda_tc=$_POST['moneda_tc'];
      $fechahoy=trim($_POST['fechahoy']);
      $fechaentrega=trim($_POST['fechaentrega']);
      $option=trim($_POST['option']);
      $idrequi=trim($_POST['idrequi']);
      $almacen=trim($_POST['almacen']);
      $obs=trim($_POST['obs']);
      $imp=$_POST['imp'];

      $imps=$_POST['imps'];


      $resultReq = $this->ComprasModel->datosImpresion($proveedor);
      if($resultReq->num_rows>0){
      
        $row = $resultReq->fetch_assoc();
        $row['fecha']=$fechahoy;
        $row['fecha_entrega']=$fechaentrega;
        $data['reqdata']['fecha']=$fechahoy;
        $data['reqdata']['fecha_entrega']=$fechaentrega;
        $data['reqdata']['cotizacion']=1;
        $data['reqdata']['nombre']=$row['nombre'];
        $data['reqdata']['direccion']=$row['direccion'];
        $data['reqdata']['email']=$row['email'];
        $data['reqdata']['empresa']=$row['nombreorganizacion'];
        $data['reqdata']['direccionempresa']=$row['domicilio'];
        $data['reqdata']['logoempresa']=$row['logoempresa'];
        $data['reqdata']['doc']='Requisicion de compra';

        /*if($op==0){
          $msgemail=$data['reqdata']['empresa'].' le ha enviado la siguiente cotizacion';
          $msgcoti='Cotizacion';
        }
        if($op==1){
          $msgemail='Se ha generado la siguiente Orden de venta';
          $msgcoti='Orden de venta';
        }*/
        $productos = explode(',#', $idsProductos);
        $row2=array();
        $r=1;
        foreach ($productos as $k => $v) {
            $exp=explode('>#', $v);
            $idprod=$exp[0];
            $cant=$exp[1];
            $caracteristica=$exp[2];
            $costo=$exp[3];


            $nomprod = $this->ComprasModel->productosTicket($idprod);
            $resultCaras = $this->ComprasModel->caracteristicaReq($caracteristica);
            $row2['id']=$nomprod['id'];
            $row2['codigo']=$nomprod['codigo'];
            $row2['nomprod']=$nomprod['nombre'].' '.$resultCaras;
            $row2['costo']=$costo;
            $row2['cantidad']=$cant;
            $data['prodata'][]=$row2;

        }


        
        session_start();
        $_SESSION['ticketventaenv']='';
        unset($_SESSION['ticketventaenv']);
        $data['imps']=$imps;

        $_SESSION['ticketventaenv']=$data;
        //require('views/ventas/v_ticket.php');
      }
    }

    function a_editarrequisicion()
    {
      $idReq=$_POST['idReq'];
      $m=$_POST['m'];
      $mod=$_POST['mod'];
      $pr=$_POST['pr']; //proviene
      $resultReq = $this->ComprasModel->editarRequisicion($idReq,$pr);
      if($resultReq->num_rows>0){
      
        $row = $resultReq->fetch_assoc();
        $row['fecha']=substr($row['fecha'],0,10);
        $row['fecha_entrega']=substr($row['fecha_entrega'],0,10);

        $resultReq2 = $this->ComprasModel->productosRequisicion($idReq,$row['id_proveedor'],$m,$mod);
        while ($row2 = $resultReq2->fetch_assoc()) {
          if($row2['caracteristica']!='0'){
              $resultCaras = $this->ComprasModel->caracteristicaReq($row2['caracteristica']);
              $row2['nombre']=$row2['nombre'].' '.$resultCaras;
          }

if($modo==1){
        $nolote=$_POST['nolote'];
        $datelotefab=$_POST['datelotefab'];
        $datelotecad=$_POST['datelotecad'];
        $_SESSION['rePr'][$modo][$idProd]=array('cantrecibid' => $cantrecibid, 'nolote' => $nolote, 'datelotefab' => $datelotefab, 'datelotecad' => $datelotecad);
        echo $modo.'->-'.$idProd.'->-'.$cantrecibid.'->-'.$nolote.'->-'.$datelotefab.'->-'.$datelotecad;
      }
      if($modo==2){
        $nseries=$_POST['nseries'];
        $seriesprods=$_POST['seriesprods'];
        $_SESSION['rePr'][$modo][$idProd]=array('cantrecibid' => $cantrecibid, 'nseries' => $nseries, 'seriesprods' => $seriesprods);
        echo $modo.'->-'.$idProd.'->-'.$cantrecibid.'->-'.$nseries.'->-'.$seriesprods;
      }

      if($modo==3){
        $nopedimento=$_POST['nopedimento'];
        $aduanatext=$_POST['aduanatext'];
        $noaduana=$_POST['noaduana'];
        $tipcambio=$_POST['tipcambio'];
        $datepedimento=$_POST['datepedimento'];

        $nseries=$_POST['nseries'];
        $seriesprods=$_POST['seriesprods'];
        $_SESSION['rePr'][$modo][$idProd]=array('cantrecibid' => $cantrecibid, 'nopedimento' => $nopedimento, 'aduanatext' => $aduanatext, 'noaduana' => $noaduana, 'tipcambio' => $tipcambio, 'datepedimento' => $datepedimento, 'nseries' => $nseries, 'seriesprods' => $seriesprods);
        echo $modo.'->-'.$idProd.'->-'.$cantrecibid.'->-'.$nopedimento.'->-'.$aduanatext.'->-'.$noaduana.'->-'.$tipcambio.'->-'.$datepedimento.'->-'.$nseries.'->-'.$seriesprods;
      }

      if($modo==4){
        $nopedimento=$_POST['nopedimento'];
        $aduanatext=$_POST['aduanatext'];
        $noaduana=$_POST['noaduana'];
        $tipcambio=$_POST['tipcambio'];
        $datepedimento=$_POST['datepedimento'];
        $_SESSION['rePr'][$modo][$idProd]=array('cantrecibid' => $cantrecibid, 'nopedimento' => $nopedimento, 'aduanatext' => $aduanatext, 'noaduana' => $noaduana, 'tipcambio' => $tipcambio, 'datepedimento' => $datepedimento);
        echo $modo.'->-'.$idProd.'->-'.$cantrecibid.'->-'.$nopedimento.'->-'.$aduanatext.'->-'.$noaduana.'->-'.$tipcambio.'->-'.$datepedimento;
      }



          if($row2['lotes']==1 && $row2['series']==0 && $row2['pedimentos']==0){
            $resultReq3 = $this->ComprasModel->getLoteProd($idReq);
          }elseif($row2['series']==1 && $row2['pedimentos']==0 && $row2['lotes']==0){

          }elseif($row2['series']==1 && $row2['pedimentos']==1 && $row2['lotes']==0){
            $resultReq3 = $this->ComprasModel->getSPProd($idReq);
          }elseif($row2['pedimentos']==1 && $row2['series']==0 && $row2['lotes']==0){

          }

          $productos[]=$row2;
        }
       // $row2 = $resultReq2->fetch_assoc();
        
        $JSON = array('success' =>1, 'requisicion'=>$row, 'productos'=>$productos);
      }else{
        $JSON = array('success' =>0);
      }
      
      echo json_encode($JSON);

    }

    function a_verConsignacion()
    {
      $idRec=$_POST['idRec'];
      $m=$_POST['m'];
      $mod=$_POST['mod'];
      $m=4;
      $resultReq = $this->ComprasModel->editarRequisicionRec($idRec);
      if($resultReq->num_rows>0){
      
        $row = $resultReq->fetch_assoc();
        $idReq=$row['idreq'];
        $row['fecha']=substr($row['fecha'],0,10);
        $row['fecha_entrega']=substr($row['fecha_entrega'],0,10);

        $resultReq2 = $this->ComprasModel->productosRequisicion($idReq,$row['id_proveedor'],$m,$mod,$idRec);
        while ($row2 = $resultReq2->fetch_assoc()) {
          if($row2['caracteristica']!='0'){
              $resultCaras = $this->ComprasModel->caracteristicaReq($row2['caracteristica']);
              $row2['nombre']=$row2['nombre'].' '.$resultCaras;
          }
          $productos[]=$row2;
        }

      $JSON = array('success' =>1, 'requisicion'=>$row, 'productos'=>$productos);
      }else{
        $JSON = array('success' =>0);
      }
      
      echo json_encode($JSON);

    }

    function a_verRecepcion()
    {
      $idRec=$_POST['idRec'];
      $m=$_POST['m'];
      $mod=$_POST['mod'];
      $resultReq = $this->ComprasModel->editarRequisicionRec($idRec);
      
      if($resultReq->num_rows>0){
      
        $row = $resultReq->fetch_assoc();
        $idReq=$row['idreq'];
        $row['fecha']=substr($row['fecha'],0,10);
        $row['fecha_entrega']=substr($row['fecha_entrega'],0,10);

        $resultReq2 = $this->ComprasModel->productosRequisicion($idReq,$row['id_proveedor'],$m,$mod,$idRec);
        while ($row2 = $resultReq2->fetch_assoc()) {
          if($row2['caracteristica']!='0'){
              $resultCaras = $this->ComprasModel->caracteristicaReq($row2['caracteristica']);
              $row2['nombre']=$row2['nombre'].' '.$resultCaras;
          }


if($modo==1){
        $nolote=$_POST['nolote'];
        $datelotefab=$_POST['datelotefab'];
        $datelotecad=$_POST['datelotecad'];
        $_SESSION['rePr'][$modo][$idProd]=array('cantrecibid' => $cantrecibid, 'nolote' => $nolote, 'datelotefab' => $datelotefab, 'datelotecad' => $datelotecad);
        echo $modo.'->-'.$idProd.'->-'.$cantrecibid.'->-'.$nolote.'->-'.$datelotefab.'->-'.$datelotecad;
      }
      if($modo==2){
        $nseries=$_POST['nseries'];
        $seriesprods=$_POST['seriesprods'];
        $_SESSION['rePr'][$modo][$idProd]=array('cantrecibid' => $cantrecibid, 'nseries' => $nseries, 'seriesprods' => $seriesprods);
        echo $modo.'->-'.$idProd.'->-'.$cantrecibid.'->-'.$nseries.'->-'.$seriesprods;
      }

      if($modo==3){
        $nopedimento=$_POST['nopedimento'];
        $aduanatext=$_POST['aduanatext'];
        $noaduana=$_POST['noaduana'];
        $tipcambio=$_POST['tipcambio'];
        $datepedimento=$_POST['datepedimento'];

        $nseries=$_POST['nseries'];
        $seriesprods=$_POST['seriesprods'];
        $_SESSION['rePr'][$modo][$idProd]=array('cantrecibid' => $cantrecibid, 'nopedimento' => $nopedimento, 'aduanatext' => $aduanatext, 'noaduana' => $noaduana, 'tipcambio' => $tipcambio, 'datepedimento' => $datepedimento, 'nseries' => $nseries, 'seriesprods' => $seriesprods);
        echo $modo.'->-'.$idProd.'->-'.$cantrecibid.'->-'.$nopedimento.'->-'.$aduanatext.'->-'.$noaduana.'->-'.$tipcambio.'->-'.$datepedimento.'->-'.$nseries.'->-'.$seriesprods;
      }

      if($modo==4){
        $nopedimento=$_POST['nopedimento'];
        $aduanatext=$_POST['aduanatext'];
        $noaduana=$_POST['noaduana'];
        $tipcambio=$_POST['tipcambio'];
        $datepedimento=$_POST['datepedimento'];
        $_SESSION['rePr'][$modo][$idProd]=array('cantrecibid' => $cantrecibid, 'nopedimento' => $nopedimento, 'aduanatext' => $aduanatext, 'noaduana' => $noaduana, 'tipcambio' => $tipcambio, 'datepedimento' => $datepedimento);
        echo $modo.'->-'.$idProd.'->-'.$cantrecibid.'->-'.$nopedimento.'->-'.$aduanatext.'->-'.$noaduana.'->-'.$tipcambio.'->-'.$datepedimento;
      }



          if($row2['lotes']==1 && $row2['series']==0 && $row2['pedimentos']==0){
            $resultReq3 = $this->ComprasModel->getLoteProd($idReq);
          }elseif($row2['series']==1 && $row2['pedimentos']==0 && $row2['lotes']==0){

          }elseif($row2['series']==1 && $row2['pedimentos']==1 && $row2['lotes']==0){
            $resultReq3 = $this->ComprasModel->getSPProd($idReq);
          }elseif($row2['pedimentos']==1 && $row2['series']==0 && $row2['lotes']==0){

          }

          $productos[]=$row2;
        }
       // $row2 = $resultReq2->fetch_assoc();
        
        $JSON = array('success' =>1, 'requisicion'=>$row, 'productos'=>$productos);
      }else{
        $JSON = array('success' =>0);
      }
      
      echo json_encode($JSON);

    }

    function a_eliminaRequisicion()
    {
      $idReq=$_POST['idReq'];
      $resultReq = $this->ComprasModel->deleteReq($idReq);
      echo $resultReq;

    }

    function a_addGeneraReq()
    {
      $idProducto=$_POST['idProducto'];
      $resultReq = $this->ComprasModel->addProductoReq($idProducto);
      if($resultReq->num_rows>0){
        $row = $resultReq->fetch_array();
        $producto[]=$row;
      }else{
        echo "Error Error Error"; exit();
      }
      
      echo json_encode($producto);

    }

    function a_gethijas(){
      $cp=$_POST['cp'];
      $resultCarH = $this->ComprasModel->getCaracteristicasProdH($cp);
      if($resultCarP->num_rows>0){
        $optionp='<select id="optionh">';
        while ($r = $resultCarP->fetch_assoc()) {
          $optionp.='<option value="'.$r['id'].'">'.$r['nombre'].'</option>';
        }
        $optionp.='</select>';
      }

      var_dump($optionp);
    }

    function combinations($arrays, $i = 0) {
      if (!isset($arrays[$i])) {
          return array();
      }
      if ($i == count($arrays) - 1) {
          return $arrays[$i];
      }

      // get combinations from subsequent arrays
      $tmp = $this->combinations($arrays, $i + 1);

      $result = array();

      // concat each array from tmp with each element from $arrays[$i]
      foreach ($arrays[$i] as $v) {
          foreach ($tmp as $t) {
              $result[] = is_array($t) ? 
                  array_merge(array($v), $t) :
                  array($v, $t);
          }
      }

      return $result;
  }



    function a_addProductoReq()
    {
      $idProducto=$_POST['idProducto'];
      $idProveedor=$_POST['idProveedor'];
      $resultReq = $this->ComprasModel->addProductoReq($idProducto,$idProveedor);
      $resultCarP = $this->ComprasModel->getCaracteristicasProdP($idProducto);
      if($resultCarP->num_rows>0){
        $nocars = $resultCarP->num_rows;
        $elsel=array();
        $allt=array();
        $car=array();
        $final=array();

        $html='';
        $tata='<table id="tata" border="1" style="border:1px solid #aaa">';
        $tata.='<tr>';

        $alltable='<table id="alltable" border="1" style="border:1px solid #aaa">';
        $alltable.='<tr>';


        $o=0;
        $rt=0;
        $filtot=0;
        $oo=0;
        while ($r = $resultCarP->fetch_assoc()) {
          
          $tata.='<th style="padding:4px;">'.$r['nombrecp'].'</th>';

          $alltable.='<th style="padding:4px;min-width:94px;">'.$r['nombrecp'].'</th>';

          $html.='<div class="s7 col-sm-12" style="padding-top:10px;"><div class="form-group">';
          $html.='<label id="npadre" class="col-sm-6 control-label text-left">'.$r['nombrecp'].'</label>';
          $html.='<div class="col-sm-6" style="color:#000;">';
          $resultCarH = $this->ComprasModel->getCaracteristicasProdH($r['idcp']);
          if($resultCarH->num_rows>0){
            $html.='<select class="carh">';
            $elsel[$o].='<option value="0">Seleccione</option>';  
            
            

            $hijascant = $resultCarH->num_rows;

            while ($rH = $resultCarH->fetch_assoc()) {
              $html.='<option value="'.$r['idcp'].'=>'.$rH['id'].'">'.$rH['nombre'].'</option>';
              $elsel[$o].='<option value="'.$r['idcp'].'=>'.$rH['id'].'">'.$rH['nombre'].'</option>';

              $allt[$rt][]='<td style="padding:4px;">'.$rH['nombre'].'</td>';
              $car[$rt][]='<td padre="'.$r['nombrecp'].'" ccvv="'.$r['idcp'].'=>'.$rH['id'].'" style="padding:4px;">'.$rH['nombre'].'</td>';
              $oo++;
            }
            $o++;
            $html.='</select>';
          }
          $html.='</div></div></div><div class="row"></div>';
          $final[]=$car[$rt];
          $rt++;

          
        }

$lasc = $this->combinations($final);

        $tata.='<td style="padding:4px;">Cant</td>';
        $tata.='<td style="padding:4px;">Quitar</td>';
        $tata.='</tr>';

        $alltable.='<th style="padding:4px;">Cant</th>';
       // $alltable.='<th style="padding:4px;">Quitar</th>';
        $alltable.='</tr>';

        $tata.='<tr id="clon1">';

        
        $ctr=0;
        foreach ($lasc as $kk => $vv) {
          $alltable.='<tr id="ctr_'.$ctr.'">';
          if(is_array($vv)){
            foreach ($vv as $kk2 => $vv2) {
              $alltable.=$vv2;
            }
            $alltable.='<td padre="x.x.x." style="padding:4px;"><input ctr="'.$ctr.'" type="text" value="0" style="width:90px;"></td></tr>';
          }else{
            $alltable.=$vv.'<td padre="x.x.x." style="padding:4px;"><input ctr="'.$ctr.'" type="text" value="0" style="width:90px;"></td></tr>';
          }
          $ctr++;;
        }

        //echo $alltable;

        //exit();
        /*  
        for ($i=0; $i < $nocars; $i++) { 
          $tata.='<td style="padding:4px;"><select class="carh">'.$elsel[$i].'</select></td>';
        }
        */
       

        $tata.='<td style="padding:4px;"><input type="text" value="1" style="width:40px;"></td>';
        $tata.='<td style="padding:4px;"><input type="button" value=" X " onclick="quitarfilacar(this);"></td>';
        $tata.='</tr>';
        $tata.='</table>';

        $tata.='<div style="margin-top:10px;"><input type="button" value="Agregar Otro" onclick="addfilacar();">';

        $html=$alltable;

      }else{
        $html='';
      }

 

      if($resultReq->num_rows>0){
        $row = $resultReq->fetch_array();
        $producto[]=$row;
        $producto['productop']=$optionp;

        $JSON = array('success' =>1, 'datos'=>$producto, 'car'=>$html);
      }else{
        $JSON = array('success' =>0);
      }
      
      echo json_encode($JSON);

    }

    
    function Capturar()
    {
      //  $numPoliza          =    $this->CaptPolizasModel->getLastNumPoliza();
      //  $Exercise           =    $this->CaptPolizasModel->getExerciseInfo();
      //  $Ex                 =    $Exercise->fetch_assoc();
        echo 123;
       // require('views/captpolizas/capturapolizas.php');
    }

    function a_solacla(){
      $idrequi=$_POST['idrequi'];
      $resultReq = $this->ComprasModel->solacla($idrequi);
      if($resultReq->num_rows>0){
        $JSON = array('success' =>1);
      }else{
        $JSON = array('success' =>0);
      }
      
      echo json_encode($JSON);

    }   

    function a_getProvProducto(){
      $idProveedor=$_POST['idProveedor'];
      $idmoneda=$_POST['idmoneda'];
      $resultReq = $this->ComprasModel->getProvProducto($idProveedor,$idmoneda);
      if($resultReq->num_rows>0){
        while ($r = $resultReq->fetch_assoc()) {
          $productos[]=$r;
        }
        $JSON = array('success' =>1, 'datos'=>$productos);
      }else{
        $JSON = array('success' =>0);
      }
      
      echo json_encode($JSON);

    }

    function a_compraConsignacion(){
      $idsProductos=$_POST['idsProductos'];
      $solicitante=$_POST['solicitante'];
      $tipogasto=$_POST['tipogasto'];
      $moneda=$_POST['moneda'];
      $proveedor=$_POST['proveedor'];
      $urgente=$_POST['urgente'];
      $inventariable=$_POST['inventariable'];
      $moneda_tc=$_POST['moneda_tc'];
      $fechahoy=trim($_POST['fechahoy']);
      $fechaentrega=trim($_POST['fechaentrega']);
      $date_recep=trim($_POST['date_recep']);
      $option=trim($_POST['option']);
      $idOC=trim($_POST['idrequi']);
      $almacen=trim($_POST['almacen']);
      //$total=trim($_POST['total']);
      $idactivo=trim($_POST['idactivo']);

      $nofactrec=trim($_POST['nofactrec']);
      $date_recepcion=trim($_POST['date_recepcion']);
      $impfactrec=trim($_POST['impfactrec']);

      $activo=trim($_POST['activo']);
      $xmlfile=trim($_POST['xmlfile']);
      $ist=trim($_POST['ist']);
      $it=trim($_POST['it']);
      $esconsig=trim($_POST['esconsig']);
      $desc_concepto=trim($_POST['desc_concepto']);


       if($option==1){
        session_start();
        $resultReq = $this->ComprasModel->saveConsignacion($_SESSION['rePr'],$idOC,$nofactrec,$date_recepcion,$impfactrec,$idsProductos,$activo,$xmlfile,$desc_concepto,$proveedor,$inventariable,$ist,$it,$date_recep,$esconsig);
        echo $resultReq;
      }


    }

    function a_modalRecepcionv(){
      $idProd=$_POST['idProd'];
      $modo=$_POST['modo'];
      $cadcar=$_POST['cadcar'];
      $cantrecibid=$_POST['modalcantrecibida'];
      

      session_start();
      if($modo==0){
        $existencias=$_POST['existencias'];
        $cantsexistencias=$_POST['cantsexistencias'];
        $existencias_imp=implode(',', $existencias);
        $_SESSION['v_rePrv'][$modo][$idProd][$cadcar]=array('cantrecibid' => $cantrecibid, 'existencias' => $existencias, 'cantsexistencias'=>$cantsexistencias);
        echo $modo.'->-'.$idProd.'->-'.$cantrecibid.'->-'.$existencias_imp.'->-'.$cantsexistencias;
      }
      if($modo==1){
        $lotes=$_POST['lotes'];
        $cantslotes=$_POST['cantslotes'];
        $lotes_imp=implode(',', $lotes);
        $_SESSION['v_rePrv'][$modo][$idProd][$cadcar]=array('cantrecibid' => $cantrecibid, 'lotes' => $lotes, 'cantslotes'=>$cantslotes);
        echo $modo.'->-'.$idProd.'->-'.$cantrecibid.'->-'.$lotes_imp.'->-'.$cantslotes;
      }
      if($modo==2){
        $series=$_POST['series'];
        $series_imp=implode(',', $series);
        $_SESSION['v_rePrv'][$modo][$idProd][$cadcar]=array('cantrecibid' => $cantrecibid, 'series' => $series);

        echo $modo.'->-'.$idProd.'->-'.$cantrecibid.'->-'.$series_imp;
      }

      if($modo==3){
        $pedimentos=$_POST['pedimentos'];
        $pedimentos_imp=implode(',', $pedimentos);
        $series=$_POST['series'];
        $series_imp=implode(',', $series);

        foreach ($pedimentos as $k => $v) {
          $r=explode('-', $v);
        
          $si = preg_match_all('/('.$r[0].'-'.$r[1].'\|)/i', $series_imp, $matches);

          if($si==0){
            unset($pedimentos[$k]);
          }else{
            $pedimentos[$k].='-'.$si;
          }
        }




        $_SESSION['v_rePrv'][$modo][$idProd][$cadcar]=array('cantrecibid' => $cantrecibid, 'pedimentos' => $pedimentos, 'series' => $series);
        echo $modo.'->-'.$idProd.'->-'.$cantrecibid.'->-'.$pedimentos_imp.'->-'.$series_imp;
      }

      if($modo==4){
        $pedimentos=$_POST['pedimentos'];
        $cantspedimentos=$_POST['cantspedimentos'];
        $pedimentos_imp=implode(',', $pedimentos);
        $_SESSION['v_rePrv'][$modo][$idProd][$cadcar]=array('cantrecibid' => $cantrecibid, 'pedimentos' => $pedimentos, 'cantspedimentos' => $cantspedimentos);
        echo $modo.'->-'.$idProd.'->-'.$cantrecibid.'->-'.$pedimentos_imp.'->-'.$cantspedimentos;
      }

      if($modo==5){

        $pedimentoslote=$_POST['pedimentoslote'];
        $cantspedimentos=$_POST['cantspedimentos'];
        $pedimentos_imp=implode(',', $pedimentoslote);
        $_SESSION['v_rePrv'][$modo][$idProd][$cadcar]=array('cantrecibid' => $cantrecibid, 'pedimentos' => $pedimentoslote, 'cantspedimentos' => $cantspedimentos);
        echo $modo.'->-'.$idProd.'->-'.$cantrecibid.'->-'.$pedimentos_imp.'->-'.$cantspedimentos;

      }
      
      //var_dump($_SESSION['rePr']);

    }

    function a_devolucionProveedor(){
      $idsProductos=$_POST['idsProductos'];
      $solicitante=$_POST['solicitante'];
      $tipogasto=$_POST['tipogasto'];
      $moneda=$_POST['moneda'];
      $proveedor=$_POST['proveedor'];
      $urgente=$_POST['urgente'];
      $inventariable=$_POST['inventariable'];
      $moneda_tc=$_POST['moneda_tc'];
      $fechahoy=trim($_POST['fechahoy']);
      $fechaentrega=trim($_POST['fechaentrega']);
      $date_recep=trim($_POST['date_recep']);
      $option=trim($_POST['option']);
      $idOC=trim($_POST['idrequi']);
      $id_rec=trim($_POST['id_rec']);
      $almacen=trim($_POST['almacen']);
      //$total=trim($_POST['total']);
      $idactivo=trim($_POST['idactivo']);

      $nofactrec=trim($_POST['nofactrec']);
      $date_recepcion=trim($_POST['date_recepcion']);
      $impfactrec=trim($_POST['impfactrec']);

      $activo=trim($_POST['activo']);
      $xmlfile=trim($_POST['xmlfile']);
      $ist=trim($_POST['ist']);
      $it=trim($_POST['it']);
      $esconsig=trim($_POST['esconsig']);
      $desc_concepto=trim($_POST['desc_concepto']);


       if($option==1){
        session_start();
        $resultReq = $this->ComprasModel->saveDevolucion2($_SESSION['v_rePrv'],$idOC,$nofactrec,$date_recepcion,$impfactrec,$idsProductos,$activo,$xmlfile,$desc_concepto,$proveedor,$inventariable,$ist,$it,$date_recep,$esconsig,$id_rec);
        echo $resultReq;
      }


    }

    function a_recepcionOrden(){
      $idsProductos=$_POST['idsProductos'];
      $solicitante=$_POST['solicitante'];
      $tipogasto=$_POST['tipogasto'];
      $moneda=$_POST['moneda'];
      $proveedor=$_POST['proveedor'];
      $urgente=$_POST['urgente'];
      $inventariable=$_POST['inventariable'];
      $moneda_tc=$_POST['moneda_tc'];
      $fechahoy=trim($_POST['fechahoy']);
      $fechaentrega=trim($_POST['fechaentrega']);
      $date_recep=trim($_POST['date_recep']);
      $option=trim($_POST['option']);
      $idOC=trim($_POST['idrequi']);
      $cxp=trim($_POST['cxp']);
      $almacen=trim($_POST['almacen']);
      //$total=trim($_POST['total']);
      $idactivo=trim($_POST['idactivo']);


      

      $activo=trim($_POST['activo']);
      
      $ist=trim($_POST['ist']);
      $it=trim($_POST['it']);
      $esconsig=trim($_POST['esconsig']);

/*
      $nofactrec=trim($_POST['nofactrec']);
      $desc_concepto=trim($_POST['desc_concepto']);
      $date_recepcion=trim($_POST['date_recepcion']);
      $impfactrec=trim($_POST['impfactrec']);
      $xmlfile=trim($_POST['xmlfile']);

*/

      $nofactrec='';
      $desc_concepto='';
      $date_recepcion='';
      $impfactrec='';
      $xmlfile='';




       if($option==1){
        session_start();
        $resultReq = $this->ComprasModel->saveRecepcion($_SESSION['rePr'],$idOC,$nofactrec,$date_recepcion,$impfactrec,$idsProductos,$activo,$xmlfile,$desc_concepto,$proveedor,$inventariable,$ist,$it,$date_recep,$esconsig,$moneda,$moneda_tc,$cxp);
        echo $resultReq;
      }


    }

    function a_enviarOrden()
    {
      $op=$_POST['op'];

      $idOc=$_POST['idOc'];
      $imps=$_POST['imps'];
      $pr=1;
      $resultReq = $this->ComprasModel->editarRequisicionEnvio($idOc,$pr);
      if($resultReq->num_rows>0){
      
        $row = $resultReq->fetch_assoc();
        $row['fecha']=substr($row['fecha'],0,10);
        $row['fecha_entrega']=substr($row['fecha_entrega'],0,10);
        $data['reqdata']['fecha']=substr($row['fecha'],0,10);
        $data['reqdata']['fecha_entrega']=substr($row['fecha_entrega'],0,10);
        $data['reqdata']['cotizacion']=$idCoti;
        $data['reqdata']['nombre']=$row['nombre'];
        $data['reqdata']['direccion']=$row['direccion'];
        $data['reqdata']['email']=$row['email'];
        $data['reqdata']['empresa']=$row['nombreorganizacion'];
        $data['reqdata']['direccionempresa']=$row['domicilio'];
        $data['reqdata']['logoempresa']=$row['logoempresa'];

        if($op==0){
          $msgemail=$data['reqdata']['empresa'].' le ha enviado la siguiente orden de compra';
          $msgcoti='Orden de compra';
        }
        if($op==1){
          $msgemail='Se ha generado la siguiente Orden de compra';
          $msgcoti='Orden de compra';
        }
        $m=2;
        $resultReq2 = $this->ComprasModel->productosRequisicion($row['idreq'],$row['id_proveedor'],$m,$mod);
        while ($row2 = $resultReq2->fetch_assoc()) {
          if($row2['caracteristica']!='0'){
              $resultCaras = $this->ComprasModel->caracteristicaReq($row2['caracteristica']);
              $row2['nomprod']=$row2['nomprod'].' '.$resultCaras;
          }
          $data['prodata'][]=$row2;
        }

        require('views/compras/v_cotiCompra.php');
      }
    }

    function a_guardarOrden(){
      $idsProductos=$_POST['idsProductos'];
      $solicitante=$_POST['solicitante'];
      $tipogasto=$_POST['tipogasto'];
      $moneda=$_POST['moneda'];
      $proveedor=$_POST['proveedor'];
      $urgente=$_POST['urgente'];
      $cxp=$_POST['cxp'];
      $inventariable=$_POST['inventariable'];
      $moneda_tc=$_POST['moneda_tc'];
      $fechahoy=trim($_POST['fechahoy']);
      $fechaentrega=trim($_POST['fechaentrega']);
      $option=trim($_POST['option']);
      $idrequi=trim($_POST['idrequi']);
      $almacen=trim($_POST['almacen']);
      $tipo_compra=trim($_POST['tipo_compra']);
      $num_fact=trim($_POST['num_fact']);
      //$total=trim($_POST['total']);
      $idactivo=trim($_POST['idactivo']);
      $obs=trim($_POST['obs']);
      $ist=trim($_POST['ist']);
      $it=trim($_POST['it']);
      $cadimps=trim($_POST['cadimps'],'|');
      $iduserlog=trim($_POST['iduserlog']);

      //$resultReq = $this->ComprasModel->saveOrden($idsProductos,$solicitante,$tipogasto,$moneda,$proveedor,$urgente,$inventariable,$moneda_tc,$fechahoy,$fechaentrega,$total,$option,$idrequi,$almacen,$idactivo);

       if($option==1){
          $resultReq = $this->ComprasModel->saveOrden($idsProductos,$solicitante,$tipogasto,$moneda,$proveedor,$urgente,$inventariable,$moneda_tc,$fechahoy,$fechaentrega,$total,$option,$idrequi,$almacen,$idactivo,$obs,$ist,$it,$cadimps,$iduserlog,$num_fact, $tipo_compra);
        
          if($tipo_compra == 2){
            $date_recepcion='';
            $esconsig = 0;
            $activo = 4;
            $resultReq2 = $this->ComprasModel->saveRecepcion2($_SESSION['rePr'],$resultReq,$nofactrec,$date_recepcion,$impfactrec,$idsProductos,$activo,$xmlfile,$desc_concepto,$proveedor,$inventariable,$ist,$it,$date_recep,$esconsig,$moneda,$moneda_tc, $almacen, $cxp);
          }
        echo $resultReq;
      }

      if($option==2){
      //$resultReq = $this->ComprasModel->modifyRequisicion($idsProductos,$solicitante,$tipogasto,$moneda,$proveedor,$urgente,$inventariable,$moneda_tc,$fechahoy,$fechaentrega,$idrequi,$almacen);

      $resultReq = $this->ComprasModel->modifyOrden($idsProductos,$solicitante,$tipogasto,$moneda,$proveedor,$urgente,$inventariable,$moneda_tc,$fechahoy,$fechaentrega,$total,$option,$idrequi,$almacen,$idactivo,$obs,$ist,$it,$cadimps,$iduserlog,$num_fact);
        echo $resultReq;
      }

    }

    function a_checkNoti(){
      $idCoti=$_POST['idOc'];
      $resultCheck = $this->ComprasModel->checkNoti();
      if($resultCheck!=''){
        $resultReq = $this->ComprasModel->editarRequisicionEnvio($idCoti,1);
          if($resultReq->num_rows>0){
          $m=2;
          $row = $resultReq->fetch_assoc();
          $productos='';
          $impuestos='';
          $resultReq2 = $this->ComprasModel->productosRequisicion($idCoti,$row['id_proveedor'],$m,1);
          while ($row2 = $resultReq2->fetch_assoc()) {
            $productos.=$row2['id'].'-'.$row2['costo'].'-'.$row2['cantidad'].'-1'.'-'.$row2['caracteristica'].'/';

            $impuestos[$row2['id']]['idProducto']=$row2['id'];
            $impuestos[$row2['id']]['codigo']=$row2['codigo'];
            $impuestos[$row2['id']]['nombre']=$row2['nomprod'];
            $impuestos[$row2['id']]['descripcion']=$row2['nomprod'];
            //$impuestos[$row2['id']]['unidad']=$row2['nomprod'];
            $impuestos[$row2['id']]['medida']=$row2["clave"];
            //$impuestos[$row2['id']]['idunidad']=$valueImpuestos["id_unidad_venta"];
            $impuestos[$row2['id']]['precio']=$row2['costo'];
            $impuestos[$row2['id']]['cantidad']=$row2['cantidad'];

          
          }
          $productos=trim($productos,'/');
          //var_dump($data['prodata']);

          //idProdcuto-precio-cantidad-formula/idProducto2-precio2-cantidad2-formula2/
              //$productos = '41-100-1-0/42-50-1-2/44-100-1-1';
          session_start();
          
          
          $resultReq4 =  $this->ComprasModel->calculaImpuestosFact($productos,$impuestos);

          $st=$row['st'];
          $tt=$row['tt'];
          if($row['st']==null){ $st=$row['rst']; }
          if($row['tt']==null){ $tt=$row['rtt']; }
          
        
          $_SESSION["caja"]=$resultReq4;
          $_SESSION['caja']['cargos']['subtotal'] = $st;
          $_SESSION['caja']['cargos']['total'] = $tt;

          if($row['observaciones']==''){
            $br='';
          }else{
            $br='<br><br>';
          }
          
          $autorizaciones.='<b>Requisicion:</b> '.$row['username1'];
          $autorizaciones.='<br>';
          $print=0;
          $tipo='Requisicion';
          //$autorizaciones.='<b>Autorización:</b> '.$row['username2'];

          $this->ComprasModel->save($row['id_proveedor'],'',$idCoti,$print,$op,$row['moneda'],$row['observaciones'].$br.'<b>Fecha de entrega:</b> '.substr($row['fecha_entrega'],0,10).' <br>'.$autorizaciones,$tipo,$resultCheck,'1');
          
          unset($_SESSION["caja"]);
        }
      }
    }

    function a_enviarPedido2(){
      $idCoti=$_POST['idOc'];
      $modo=$_POST['modo'];
      $print=$_POST['print'];
      $op=$_POST['op'];
      $tipo=$_POST['tipo'];
      if($op==9){
          $resultReq = $this->ComprasModel->a_change_idoc_idreq($idCoti,1);
          $idCoti = $resultReq['rows'][0]['id_requisicion'];
      }
      $resultReq = $this->ComprasModel->editarRequisicionEnvio($idCoti,1);
      
      if($resultReq->num_rows>0){
        $m=2;
        $row = $resultReq->fetch_assoc();
        $productos='';
        $impuestos='';
        $resultReq2 = $this->ComprasModel->productosRequisicion($idCoti,$row['id_proveedor'],$m,$modo);
        while ($row2 = $resultReq2->fetch_assoc()) {
          $productos.=$row2['id'].'-'.$row2['costo'].'-'.$row2['cantidad'].'-1'.'-'.$row2['caracteristica'].'/';

          $impuestos[$row2['id']]['idProducto']=$row2['id'];
          $impuestos[$row2['id']]['codigo']=$row2['codigo'];
          $impuestos[$row2['id']]['nombre']=$row2['nomprod'];
          $impuestos[$row2['id']]['descripcion']=$row2['nomprod'];
          //$impuestos[$row2['id']]['unidad']=$row2['nomprod'];
          $impuestos[$row2['id']]['medida']=$row2["clave"];
          //$impuestos[$row2['id']]['idunidad']=$valueImpuestos["id_unidad_venta"];
          $impuestos[$row2['id']]['precio']=$row2['costo'];
          $impuestos[$row2['id']]['cantidad']=$row2['cantidad'];

        
        }
        $productos=trim($productos,'/');
        //var_dump($data['prodata']);

        //idProdcuto-precio-cantidad-formula/idProducto2-precio2-cantidad2-formula2/
            //$productos = '41-100-1-0/42-50-1-2/44-100-1-1';
        session_start();
        
        
        $resultReq4 =  $this->ComprasModel->calculaImpuestosFact($productos,$impuestos);
        
        $st=$row['st'];
        $tt=$row['tt'];
        if($row['st']==null){ $st=$row['rst']; }
        if($row['tt']==null){ $tt=$row['rtt']; }
        
      
        $_SESSION["caja"]=$resultReq4;
        $_SESSION['caja']['cargos']['subtotal'] = $st;
        $_SESSION['caja']['cargos']['total'] = $tt;

        if($row['observaciones']==''){
          $br='';
        }else{
          $br='<br><br>';
        }
        
        $autorizaciones.='<b>Requisicion:</b> '.$row['fecha_creacion1'].' - '.$row['username1'];
        $autorizaciones.='<br>';
        $autorizaciones.='<b>Autorización:</b> '.$row['fecha_creacion2'].' - '.$row['username2'];

        $this->ComprasModel->save($row['id_proveedor'],'',$idCoti,$print,$op,$row['moneda'],$row['observaciones'].$br.'<b>Fecha de entrega:</b> '.substr($row['fecha_entrega'],0,10).' <br>'.$autorizaciones,$tipo);
        
        unset($_SESSION["caja"]);
      }
    }

    function a_enviarPedido3(){
      $idCoti=$_POST['idOc'];
      $modo=$_POST['modo'];
      $print=$_POST['print'];
      $op=$_POST['op'];
      $tipo=$_POST['tipo'];
      if($op==9){
          $resultReq = $this->ComprasModel->a_change_idoc_idreq($idCoti,1);
          $idCoti = $resultReq['rows'][0]['id_requisicion'];
      }
      $resultReq = $this->ComprasModel->editarRequisicionEnvio($idCoti,1);
      
      if($resultReq->num_rows>0){
        $m=2;
        $row = $resultReq->fetch_assoc();
        $productos='';
        $impuestos='';
        $resultReq2 = $this->ComprasModel->productosRequisicion($idCoti,$row['id_proveedor'],$m,$modo);
        while ($row2 = $resultReq2->fetch_assoc()) {
          $productos.=$row2['id'].'-'.$row2['costo'].'-'.$row2['cantidad'].'-1'.'-'.$row2['caracteristica'].'/';

          $impuestos[$row2['id']]['idProducto']=$row2['id'];
          $impuestos[$row2['id']]['codigo']=$row2['codigo'];
          $impuestos[$row2['id']]['nombre']=$row2['nomprod'];
          $impuestos[$row2['id']]['descripcion']=$row2['nomprod'];
          //$impuestos[$row2['id']]['unidad']=$row2['nomprod'];
          $impuestos[$row2['id']]['medida']=$row2["clave"];
          //$impuestos[$row2['id']]['idunidad']=$valueImpuestos["id_unidad_venta"];
          $impuestos[$row2['id']]['precio']=$row2['costo'];
          $impuestos[$row2['id']]['cantidad']=$row2['cantidad'];

        
        }
        $productos=trim($productos,'/');
        //var_dump($data['prodata']);

        //idProdcuto-precio-cantidad-formula/idProducto2-precio2-cantidad2-formula2/
            //$productos = '41-100-1-0/42-50-1-2/44-100-1-1';
        session_start();
        
        
        $resultReq4 =  $this->ComprasModel->calculaImpuestosFact($productos,$impuestos);
        
        $st=$row['st'];
        $tt=$row['tt'];
        if($row['st']==null){ $st=$row['rst']; }
        if($row['tt']==null){ $tt=$row['rtt']; }
        
      
        $_SESSION["caja"]=$resultReq4;
        $_SESSION['caja']['cargos']['subtotal'] = $st;
        $_SESSION['caja']['cargos']['total'] = $tt;

        if($row['observaciones']==''){
          $br='';
        }else{
          $br='<br><br>';
        }
        
        $autorizaciones.='<b>Requisicion:</b> '.$row['fecha_creacion1'].' - '.$row['username1'];
        $autorizaciones.='<br>';
        $autorizaciones.='<b>Autorización:</b> '.$row['fecha_creacion2'].' - '.$row['username2'];

        

        $fechaactual = date('Y-m-d H:i:s');
       $expedicion = 'México';

        
        $datos_coti = $this->ComprasModel->get_datos_coti($idCoti);

       //emisor
        $emisor = $this->ComprasModel->get_emisor();

       //proveedor
        $proveedor = $this->ComprasModel->get_proveedor($datos_coti['id_proveedor']);

        $src = "../../netwarelog/archivos/1/organizaciones/" . $emisor['logoempresa'];
        //print_r($src);
        $logo = (file_exists($src)) ? $src : '';
        /*if ($result2["rows"][0]["logoempresa"] != "")
                $obj->generar("../../netwarelog/archivos/1/organizaciones/" . $result2["rows"][0]["logoempresa"] . "", 0);
        else
            $obj->generar("", 0);*/
        
        $nrec = $result["rows"][0]["num_ext"].' Int.'.$result["rows"][0]["num_int"];
        
        $observaciones = $row['observaciones'].$br.'<b>Fecha de entrega:</b> '.substr($row['fecha_entrega'],0,10).' <br>'.$autorizaciones;

        $subTotal = $_SESSION['caja']['cargos']['subtotal'];
        $total = $_SESSION['caja']['cargos']['total'];

        $nn2 = $_SESSION['caja']['cargos']['impuestosFactura'];
        $nnf = $_SESSION['caja']['cargos']['impuestosPdf'];

        $nevo=array();
        foreach ($nn2 as $o => $p) {
            foreach ($p as $i => $n) {
                $nevo[$o][$o.' '.$i]=$n;
            }
        }

        $nevo=$nnf; //Cambio en impuestos que hizo Omar
        if($nevo==null){
            $nevo=array();
        }
        $x=0;
        foreach ($_SESSION['caja'] as $key => $producto) {

            if ($key != 'cargos') {
                $producto = (object) $producto;
                $descuentogeneral = 0;
                $conceptosDatos[$x]["Cantidad"] = $producto->cantidad;
                $conceptosDatos[$x]["Unidad"] = $producto->medida;
                $conceptosDatos[$x]["Precio"] = $producto->precio;
                if ( $producto->nombre != '') {
                    $conceptosDatos[$x]["Descripcion"] = trim($producto->nombre);
                } else {
                    $conceptosDatos[$x]["Descripcion"] = trim($producto->descripcion);
                }

                //$conceptosDatos[$x]["Descripcion"] = trim($producto->descripcion . " " . $textodescuento);

                $textodescuento = '';
                //$conceptosDatos[$x]['Importe'] = ($producto->cantidad * $producto->precio - str_replace(",", "", $producto->descuento) );
                $conceptosDatos[$x]['Importe'] = ($producto->cantidad * $producto->precio);
                $consumoTotal +=  $conceptosDatos[$x]['Importe']*1;
                $x++;


            }//fin del if del ciclo
        }//fin del cilo de llenar conceptos
        

        unset($_SESSION["caja"]);
    }

    require('views/compras/imp_ticket.php');
      
    }

    function a_guardarRequisicion(){
      $idsProductos=$_POST['idsProductos'];
      $solicitante=$_POST['solicitante'];
      $tipogasto=$_POST['tipogasto'];
      $moneda=$_POST['moneda'];
      $proveedor=$_POST['proveedor'];
      $urgente=$_POST['urgente'];
      $inventariable=$_POST['inventariable'];
      $moneda_tc=$_POST['moneda_tc'];
      $fechahoy=trim($_POST['fechahoy']);
      $fechaentrega=trim($_POST['fechaentrega']);
      $option=trim($_POST['option']);
      $idrequi=trim($_POST['idrequi']);
      $almacen=trim($_POST['almacen']);
      $obs=trim($_POST['obs']);
      $ist=trim($_POST['ist']);
      $it=trim($_POST['it']);
      $iduserlog=trim($_POST['iduserlog']);


      if($option==1){
      $resultReq = $this->ComprasModel->saveRequisicion($idsProductos,$solicitante,$tipogasto,$moneda,$proveedor,$urgente,$inventariable,$moneda_tc,$fechahoy,$fechaentrega,$almacen,$obs,$ist,$it,$iduserlog);
        echo $resultReq;
      }

      if($option==2){
      $resultReq = $this->ComprasModel->modifyRequisicion($idsProductos,$solicitante,$tipogasto,$moneda,$proveedor,$urgente,$inventariable,$moneda_tc,$fechahoy,$fechaentrega,$idrequi,$almacen,$obs,$ist,$it,$iduserlog);
        echo $resultReq;
      }

    }

/*
SELECT 
0 a.id, 
1 SUBSTRING(a.fecha,1,10), 
2 pr.razon_social, 
3 b.nombreEmpleado as nombre, 
4 a.total, 
5 a.urgente, 
6 a.activo
*/
    function a_listaRequisiciones(){
      $resultReq =  $this->ComprasModel->listaRequisiciones();
      $listas=array();
      $listas['data']='';
      if($resultReq->num_rows>0){
        while ($r = $resultReq->fetch_array()) {
          $link='<a  class="btn btn-default btn-xs btn-block">'.$r['id'].'</span></a>';
          $elimin='<a onclick="editReq('.$r['id'].',0);" class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-search"></span> Ver</a>
            <a onclick="" class="btn btn-default btn-xs disabled"><span class="glyphicon glyphicon-remove"></span> Borrar</a>';
          if($r['urgente']==0){
            $r[5]='<span class="label label-default" style="cursor:pointer;">Normal</span>';
          }
          if($r['urgente']==1){
            $r[5]='<span class="label label-danger" style="cursor:pointer;">Urgente</span>';
          }
          if($r['activo']==0){
            $elimin='<a onclick="editReq('.$r['id'].');"  class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-edit"></span> Editar</a>
            <a onclick="eliminaReq('.$r['id'].')" class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-remove"></span> Borrar</a>';
            $r[6]='<span class="label label-warning" style="cursor:pointer;">Pendiente autorizar</span>';
          }
          if($r['activo']==1){

            $r[6]='<span class="label label-success" style="cursor:pointer;">Autorizada</span>';
          }
          if($r['activo']==5){

            $r[6]='<span class="label label-success" style="cursor:pointer;">Autorizada</span>';
          }
          if($r['activo']==2){

            $link='<a href="#" class="btn btn-default btn-xs disabled btn-block">'.$r['id'].'</a>';
            $r[6]='<span class="label label-default" style="cursor:pointer;">Cancelada</span>';
          }
          if($r['activo']==3){

            $link='<a href="#" class="btn btn-default btn-xs disabled btn-block">'.$r['id'].'</a>';
            $r[6]='<span class="label label-success" style="cursor:pointer;">OC activa</span>';
            $elimin='<a onclick="editReq('.$r['id'].',0);" class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-search"></span> Ver</a>
            <a onclick="" class="btn btn-default btn-xs disabled"><span class="glyphicon glyphicon-remove"></span> Borrar.</a>';
          }

          if($r['activo']==4){

            $link='<a href="#" class="btn btn-default btn-xs disabled btn-block">'.$r['id'].'</a>';
            $r[6]='<span class="label label-success" style="cursor:pointer;">OK recibida ok</span>';
            $elimin='<a onclick="editReq('.$r['id'].',0);" class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-search"></span> Ver</a>
            <a onclick="" class="btn btn-default btn-xs disabled"><span class="glyphicon glyphicon-remove"></span> Borrar.</a>';
          }


          if($r['activo']==6){

            $link='<a href="#" class="btn btn-default btn-xs disabled btn-block">'.$r['id'].'</a>';
            $r[6]='<span class="label label-warning" style="cursor:pointer;">Pendiente aclaracion</span>';
            $elimin='<a onclick="editReq('.$r['id'].',0);" class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-search"></span> Ver</a>';
          }

          if($r['activo']==0){
            $mode=1;
          }else{
            $mode=0;
          }

          $r[7]=$elimin;
          $r[7].=' <button id="btn_imprimir_'.$r['id'].'_" onclick="imprimir2('.$r['id'].','.$mode.');" class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-print"></span> </button>';
          $listas['data'][]=$r;
        }
      }else{
        //$listas=0;
        $listas['data']=array();
      }

      echo json_encode($listas);
  

    }

    function a_modalRecepcion(){
      $idProd=$_POST['idProd'];
      $modo=$_POST['modo'];
      $cadcar=$_POST['cadcar'];
      $cantrecibid=$_POST['modalcantrecibida'];
      

      session_start();
      if($modo==1){
        $nolote=$_POST['nolote'];
        $datelotefab=$_POST['datelotefab'];
        $datelotecad=$_POST['datelotecad'];
        $_SESSION['rePr'][$modo][$idProd][$cadcar]=array('cantrecibid' => $cantrecibid, 'nolote' => $nolote, 'datelotefab' => $datelotefab, 'datelotecad' => $datelotecad);
        echo $modo.'->-'.$idProd.'->-'.$cantrecibid.'->-'.$nolote.'->-'.$datelotefab.'->-'.$datelotecad;
      }
      if($modo==2){
        $nseries=$_POST['nseries'];
        $seriesprods=$_POST['seriesprods'];
        $_SESSION['rePr'][$modo][$idProd][$cadcar]=array('cantrecibid' => $cantrecibid, 'nseries' => $nseries, 'seriesprods' => $seriesprods);
        echo $modo.'->-'.$idProd.'->-'.$cantrecibid.'->-'.$nseries.'->-'.$seriesprods;
      }

      if($modo==3){
        $nopedimento=$_POST['nopedimento'];
        $aduanatext=$_POST['aduanatext'];
        $noaduana=$_POST['noaduana'];
        $tipcambio=$_POST['tipcambio'];
        $datepedimento=$_POST['datepedimento'];

        $nseries=$_POST['nseries'];
        $seriesprods=$_POST['seriesprods'];
        $_SESSION['rePr'][$modo][$idProd][$cadcar]=array('cantrecibid' => $cantrecibid, 'nopedimento' => $nopedimento, 'aduanatext' => $aduanatext, 'noaduana' => $noaduana, 'tipcambio' => $tipcambio, 'datepedimento' => $datepedimento, 'nseries' => $nseries, 'seriesprods' => $seriesprods);
        echo $modo.'->-'.$idProd.'->-'.$cantrecibid.'->-'.$nopedimento.'->-'.$aduanatext.'->-'.$noaduana.'->-'.$tipcambio.'->-'.$datepedimento.'->-'.$nseries.'->-'.$seriesprods;
      }

      if($modo==4){

        $nopedimento=$_POST['nopedimento'];
        $aduanatext=$_POST['aduanatext'];
        $noaduana=$_POST['noaduana'];
        $tipcambio=$_POST['tipcambio'];
        $datepedimento=$_POST['datepedimento'];
        $_SESSION['rePr'][$modo][$idProd][$cadcar]=array('cantrecibid' => $cantrecibid, 'nopedimento' => $nopedimento, 'aduanatext' => $aduanatext, 'noaduana' => $noaduana, 'tipcambio' => $tipcambio, 'datepedimento' => $datepedimento);
        echo $modo.'->-'.$idProd.'->-'.$cantrecibid.'->-'.$nopedimento.'->-'.$aduanatext.'->-'.$noaduana.'->-'.$tipcambio.'->-'.$datepedimento;
      }
      if($modo==5){
        $nopedimento=$_POST['nopedimento'];
        $aduanatext=$_POST['aduanatext'];
        $noaduana=$_POST['noaduana'];
        $tipcambio=$_POST['tipcambio'];
        $datepedimento=$_POST['datepedimento'];
        $nolote=$_POST['nolote'];
        $datelotefab=$_POST['datelotefab'];
        $datelotecad=$_POST['datelotecad'];
        $_SESSION['rePr'][$modo][$idProd][$cadcar]=array('cantrecibid' => $cantrecibid, 'nopedimento' => $nopedimento, 'aduanatext' => $aduanatext, 'noaduana' => $noaduana, 'tipcambio' => $tipcambio, 'datepedimento' => $datepedimento, 'nolote' => $nolote, 'datelotefab' => $datelotefab, 'datelotecad' => $datelotecad);
        echo $modo.'->-'.$idProd.'->-'.$cantrecibid.'->-'.$nopedimento.'->-'.$aduanatext.'->-'.$noaduana.'->-'.$tipcambio.'->-'.$datepedimento.'->-'.$nolote.'->-'.$datelotefab.'->-'.$datelotecad;
      }
      
      //var_dump($_SESSION['rePr']);

    }

    function a_listaRequisicionesCompra(){
      $resultReq =  $this->ComprasModel->listaRequisicionesCompra();
      $listas=array();
      $listas['data']='';
      if($resultReq->num_rows>0){
        while ($r = $resultReq->fetch_array()) {
          $link='<a  class="btn btn-default btn-xs btn-block">'.$r['id'].'</span></a>';
           $elimin='<a onclick="editReq('.$r['id'].',0);"  class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-search"></span> Ver</a>
            <a onclick="" class="btn btn-default btn-xs disabled"><span class="glyphicon glyphicon-remove"></span> Borrar</a>';
          if($r['urgente']==0){
            $r[8]='<span class="label label-default" style="cursor:pointer;">Normal</span>';
          }
          if($r['urgente']==1){
            $r[8]='<span class="label label-danger" style="cursor:pointer;">Urgente</span>';
          }
          if($r['activo']==0){
            $elimin='<a onclick="editReq('.$r['id'].','.$r['modi'].');"  class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-edit"></span> Editar</a>
            <a onclick="eliminaReq('.$r['id'].')" class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-remove"></span> Borrar</a>';
            $r[9]='<span class="label label-warning" style="cursor:pointer;">Pendiente autorizar</span>';
          }

          if($r['activo']==3){
            $elimin='<a onclick="editReq('.$r['id'].','.$r['modi'].');"  class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-edit"></span> Editar</a>
            <a onclick="eliminaReq('.$r['id'].')" class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-remove"></span> Borrar</a>';
            $r[9]='<span class="label label-warning" style="cursor:pointer;">Pendiente autorizar</span>';
          }

          if($r['activo']==1){

            $r[9]='<span class="label label-success" style="cursor:pointer;">Autorizada</span>';
          }
          if($r['activo']==5){

            $r[9]='<span class="label label-success" style="cursor:pointer;">Autorizada</span>';
          }
          if($r['activo']==2){

            $link='<a href="#" class="btn btn-default btn-xs disabled btn-block">'.$r['id'].'</a>';
            $r[9]='<span class="label label-default" style="cursor:pointer;">Cancelada</span>';
          }

          if($r['activo']==4){

            $link='<a href="#" class="btn btn-default btn-xs disabled btn-block">'.$r['id'].'</a>';
            $r[9]='<span class="label label-success" style="cursor:pointer;">Autorizada</span>';
            $elimin='<a onclick="editReq('.$r['id'].',0);"  class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-search"></span> Ver</a>
            <a onclick="" class="btn btn-default btn-xs disabled"><span class="glyphicon glyphicon-remove"></span> Borrar</a>';
          }

          if($r['activo']==6){

            $link='<a href="#" class="btn btn-default btn-xs disabled btn-block">'.$r['idoc'].'</a>';
            $r[9]='<span class="label label-warning" style="cursor:pointer;">Pendiente aclaracion</span>';
            $elimin='<a onclick="editReq('.$r['id'].',0);"  class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-search"></span> Ver</a>
            <a onclick="" class="btn btn-default btn-xs disabled"><span class="glyphicon glyphicon-remove"></span> Borrar</a>';
          }


          $r[10]=$elimin;

          if($r['activo']==0){
            $mode=1;
          }else{
            $mode=0;
          }
          $r[10].=' <button id="btn_imprimir_ticket_'.$r['id'].'_" onclick="imprimir_ticket('.$r['id'].','.$mode.');" class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-print"></span> </button>
          <button id="btn_imprimir_'.$r['id'].'_" onclick="imprimir2('.$r['id'].','.$mode.');" class="btn btn-default btn-xs"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></button>';
          $listas['data'][]=$r;
        }
      }else{
        $listas['data']=array();
      }

      echo json_encode($listas);
  

    }

}


?>
