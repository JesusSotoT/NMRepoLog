<?php
//Carga la funciones comunes top y footer
require('common.php');

//Carga el modelo para este controlador
require("models/ventas.php");

class Ventas extends Common
{
    public $VentasModel;

    function __construct()
    {
        //Se crea el objeto que instancia al modelo que se va a utilizar
        $this->VentasModel = new VentasModel();
        $this->VentasModel->connect();
    }

    function __destruct()
    {
        //Se destruye el objeto que instancia al modelo que se va a utilizar
        $this->VentasModel->close();
    }

    function calculaPrecios(){
      $productos = $_POST['productos'];
      $precios = $this->VentasModel->calculaImpuestos($productos);
      echo json_encode($precios);
    }

    function masiva(){
      require('views/ventas/v_masiva.php');
    }

    function facturas(){
      require('views/ventas/v_facturas.php');
    }

    function coti(){
      require('views/ventas/v_coti.php');
    }

    function quitar_tildes($cadena) {
    $no_permitidas= array ("á","é","í","ó","ú","Á","É","Í","Ó","Ú","ñ","À","Ã","Ì","Ò","Ù","Ã™","Ã ","Ã¨","Ã¬","Ã²","Ã¹","ç","Ç","Ã¢","ê","Ã®","Ã´","Ã»","Ã‚","ÃŠ","ÃŽ","Ã”","Ã›","ü","Ã¶","Ã–","Ã¯","Ã¤","«","Ò","Ã","Ã„","Ã‹","/");
    $permitidas= array ("a","e","i","o","u","A","E","I","O","U","n","N","A","E","I","O","U","a","e","i","o","u","c","C","a","e","i","o","u","A","E","I","O","U","u","o","O","i","a","e","U","I","A","E","");
    $texto = str_replace($no_permitidas, $permitidas ,$cadena);
    return $texto;
  }

    function existeXML($nombreArchivo){
    $ruta = "facturas/";
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
        $ruta = "facturas/temporales/";
      
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

                      $version = $data['version'];

                  
                  $data['total'] = $this->getpath("//@total");
                  $data['rfc'] = $this->getpath("//@rfc");
                  
                  $tipo = explode('.',$archivo);
                  //Termina obtener UUID---------------------------
          
                  $rfcOrganizacion= $this->VentasModel->rfcOrganizacion();
                 
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

        echo $funciono."-/-*".$numeroValidos."-/-*".$facturasValidas."-/-*".$numeroInvalidos."-/-*".$facturasNoValidas."-/-*".$repetidos."-/-*".$nombreArchivo;
    }

    function envios(){

      $resultReq = $this->VentasModel->getAlmacenes();
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
      $resultReq = $this->VentasModel->getReqsAutorizar();
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

      $resultReq = $this->VentasModel->getClientes();
      if($resultReq->num_rows>0){
        while ($r = $resultReq->fetch_assoc()) {
          $clientes[]=$r;
        }
      }else{
        $clientes=0;
      }

      /* REQUIERO DE CONFIGURACION ==================
      =============================================== */
      $resultReq = $this->VentasModel->getPeriodoFecha();
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

      $resultReq = $this->VentasModel->getAlmacen();
      if($resultReq->num_rows>0){
        while ($r = $resultReq->fetch_assoc()) {
          $almacenes[]=$r;
        }
      }else{
        $almacenes=0;
      }



      $resultReq = $this->VentasModel->getEmpleados();
      if($resultReq->num_rows>0){
        while ($r = $resultReq->fetch_assoc()) {
          $empleados[]=$r;
        }
      }else{
        $empleados=0;
      }

      $resultReq = $this->VentasModel->getTipoGasto();
      if($resultReq->num_rows>0){
        while ($r = $resultReq->fetch_assoc()) {
          $tipoGasto[]=$r;
        }
      }else{
        $tipoGasto=0;
      }

      $resultReq = $this->VentasModel->getProveedores();
      if($resultReq->num_rows>0){
        while ($r = $resultReq->fetch_assoc()) {
          $proveedores[]=$r;
        }
      }else{
        $proveedores=0;
      }

      $resultReq = $this->VentasModel->getProductos();
      if($resultReq->num_rows>0){
        while ($r = $resultReq->fetch_assoc()) {
          $productos[]=$r;
        }
      }else{
        $productos=0;
      }

      $resultReq = $this->VentasModel->getMonedas();
      if($resultReq->num_rows>0){
        while ($r = $resultReq->fetch_assoc()) {
          $monedas[]=$r;
        }
      }else{
        $monedas=0;
      }

      $resultReq = $this->VentasModel->getFormaPago();
      if($resultReq->num_rows>0){
        while ($r = $resultReq->fetch_assoc()) {
          $fp[]=$r;
        }
      }else{
        $fp=0;
      }



      require('views/ventas/v_envios.php');
    }

    function a_getRFCcliente(){
      $idCliente=$_POST['cliente'];
      $resultReq =  $this->VentasModel->getRfcCliente($idCliente);
      if($resultReq->num_rows>0){
        while ($r = $resultReq->fetch_assoc()) {
          $rfcs[]=$r;
        }
        echo json_encode($rfcs);
      }else{
        $rfcs=0;
        echo 0;
      }
    }

    function a_checkNoti(){


      /*
      $op=$_POST['op'];
      //$obs=$_POST['obs'];

      $resultReq = $this->VentasModel->editarRequisicionEnvio($idCoti,1);
      if($resultReq->num_rows>0){
        $row = $resultReq->fetch_assoc();
        $productos='';
        $impuestos='';
        $resultReq2 = $this->VentasModel->productosRequisicion($idCoti,$row['id_cliente'],$m,$mod);
        while ($row2 = $resultReq2->fetch_assoc()) {
          $productos.=$row2['id'].'-'.$row2['costo'].'-'.$row2['cantidad'].'-1'.'-'.$row2['caracteristica'].'/';

        }
         $productos=trim($productos,'/');
        //var_dump($impuestos);

        //idProdcuto-precio-cantidad-formula/idProducto2-precio2-cantidad2-formula2/
            //$productos = '41-100-1-0/42-50-1-2/44-100-1-1';

        
        $resultReq4 =  $this->VentasModel->calculaImpuestosFact($productos);
        $st=$row['st'];
        $tt=$row['tt'];
        if($row['st']==null){ $st=$row['rst']; }
        if($row['tt']==null){ $tt=$row['rtt']; }
        session_start();
        $_SESSION["caja"]=$resultReq4;
        $_SESSION['caja']['cargos']['subtotal'] = $st;
        $_SESSION['caja']['cargos']['total'] = $tt;

        //$this->VentasModel->save($row['id_cliente'],'',$idCoti,$print,$op,$row['moneda'],$row['observaciones'].'. Fecha de entrega: '.substr($row['fecha_entrega'],0,10));


        if($row['observaciones']==''){
          $br='';
        }else{
          $br='<br><br>';
        }
        
        $autorizaciones.='<b>Cotización:</b> '.$row['username1'];
        $autorizaciones.='<br>';
        $autorizaciones.='<b>Autorización:</b> '.$row['username2'];

        $this->VentasModel->save($row['id_cliente'],'',$idCoti,$print,$op,$row['moneda'],$row['observaciones'].$br.'<b>Fecha de entrega:</b> '.substr($row['fecha_entrega'],0,10).' <br>'.$autorizaciones);
        
        
        unset($_SESSION["caja"]);
        */
      $idCoti=$_POST['idOc'];
      $resultCheck = $this->VentasModel->checkNoti();
      if($resultCheck!=''){
        $resultReq = $this->VentasModel->editarRequisicionEnvio($idCoti,1);
          if($resultReq->num_rows>0){
          $m=2;
          $row = $resultReq->fetch_assoc();
          $productos='';
          $impuestos='';
          $resultReq2 = $this->VentasModel->productosRequisicion($idCoti,$row['id_proveedor'],$m,1);
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
          
          
          $resultReq4 =  $this->VentasModel->calculaImpuestosFact($productos,$impuestos);

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
          
          $autorizaciones.='<b>Cotizacion:</b> '.$row['username1'];
          $autorizaciones.='<br>';
          $print=0;
          $tipo='Cotizacion';
          //$autorizaciones.='<b>Autorización:</b> '.$row['username2'];

          $this->VentasModel->save($row['id_cliente'],'',$idCoti,$print,$op,$row['moneda'],$row['observaciones'].$br.'<b>Fecha de entrega:</b> '.substr($row['fecha_entrega'],0,10).' <br>'.$autorizaciones,$tipo,$resultCheck,'1');
          
          unset($_SESSION["caja"]);
        }
      }
    }

    function oneFact(){
        $idComunFactu = $_POST['idFact'];
        $idVenta = $_POST['idSale'];
        $txtobs = $_POST['txtobs'];

        $respuesta = $this->VentasModel->oneFact($idComunFactu,$idVenta,$txtobs);

        echo json_encode($respuesta);
    }

    function oneFact2(){
        $idComunFactu = $_POST['idFact'];
        $idVenta = $_POST['idSale'];
        $txtobs = $_POST['txtobs'];

        $respuesta = $this->VentasModel->oneFact2($idComunFactu,$idVenta,$txtobs);

        echo json_encode($respuesta);
    }

    function a_getRFCFact(){
      $idFact=$_POST['idFact'];
      $resultReq =  $this->VentasModel->getRfcFact($idFact);
      if($resultReq->num_rows>0){
        while ($r = $resultReq->fetch_assoc()) {
          $rfcs[]=$r;
        }
        echo json_encode($rfcs);
      }else{
        $rfcs=0;
        echo 0;
      }
    }

    function envioFactura(){
        $uid = $_POST['uid'];
        $correo = $_POST['correo'];
        $azurian = $_POST['azurian'];
        $doc = $_POST['doc'];
        $moneda = $_POST['moneda'];

        $resultado = $this->VentasModel->envioFactura($uid, $correo, $azurian,$doc,$moneda);

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
        $xmlfile = $_POST['xmlfile'];
        $fp = $_POST['fp'];
        $numpago = $_POST['numpago'];


        if($_POST['doc'] == 3)
        {
            $tipoComp = "R";
        }


        $resultado = $this->VentasModel->guardarFacturacion($UUID,$noCertificadoSAT,$selloCFD,$selloSAT,$FechaTimbrado,$idComprobante,$idFact,$idVenta,$noCertificado,$tipoComp,$trackId,$monto,$cliente,$idRefact,$azurian,$estatus,$xmlfile,$fp,$numpago);

        echo json_encode($resultado);
    }

    function guardarFacturacionAll(){

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
        $idRefact = trim($_POST['idRefact'],',');
        $azurian = $_POST['azurian'];
        $tipoComp = $_POST['tipoComp'];
        $estatus = $_POST['estatus'];
        $xmlfile = $_POST['xmlfile'];


        if($_POST['doc'] == 3)
        {
            $tipoComp = "R";
        }

        $resultado = $this->VentasModel->guardarFacturacionAll($UUID,$noCertificadoSAT,$selloCFD,$selloSAT,$FechaTimbrado,$idComprobante,$idFact,$idVenta,$noCertificado,$tipoComp,$trackId,$monto,$cliente,$idRefact,$azurian,$estatus,$xmlfile);

        echo json_encode($resultado);
    }

    function a_facturaMasiva(){
      $ids=trim($_POST['ids'],',');
      $idfactu=trim($_POST['idfactu']);
      $listaprods =  $this->VentasModel->listaProdsFactMasiva($ids,$idfactu);
    }

    function a_factProceso(){
      $idCliente=$_POST['cliente'];
      $productos=$_POST['idsProductos'];
      $idProdFact=$_POST['idProdFact'];
      $ist=$_POST['ist'];
      $it=$_POST['it'];
      $cadimps=$_POST['cadimps'];
      $idventa=$_POST['idventa'];

      $obs=$_POST['obs'];
      $idFact=$_POST['idFact'];
      $fp=$_POST['fp'];
      $facturo=$_POST['facturo'];
      $devo=$_POST['devo'];
      $obs=$_POST['obs'];

      if($facturo==1){
        $bloqueo=0;
      }else{
        $bloqueo=1;
      }



      $resultReq =  $this->VentasModel->calculaImpuestosFact($idProdFact);


      session_start();
      $_SESSION["caja"]=$resultReq;
      $_SESSION['caja']['cargos']['subtotal'] = $ist;
      $_SESSION['caja']['cargos']['total'] = $it;
      
      $lafact =  $this->VentasModel->facturar($idFact, $idventa, $bloqueo, $obs, 0, $fp, $devo,$obs);


    }

    function a_cancelaFactura(){
        $idFact = $_POST["idFact"];

        $tieneNC = $this->VentasModel->revisaVentaTieneNota($idFact);
        if($tieneNC>0){
          $JSON = array('success' =>0, 
                    'mensaje'=>'Esta factura no puede ser cancelada ya que se realizo una devolucion con nota de credito anteriormente.');
          echo json_encode($JSON);
          exit();
        }

        $tienedias = $this->VentasModel->revisaDiasCancelacion($idFact);
        if($tienedias>0){
          $JSON = array('success' =>0, 
                    'mensaje'=>'Se han excedido los dias de cancelacion para esta factura.');
          echo json_encode($JSON);
          exit();
        }
        $total_pagos = $this->VentasModel->revisaPagosFactura($idFact);
        if($total_pagos>0){
          $JSON = array('success' =>0, 
                    'mensaje'=>'No puedes cancelar esta factura ya que tiene pagos realizados.');
          echo json_encode($JSON);
        }else{
          $this->VentasModel->cancelaFactura($idFact);
        }
        

       // echo json_encode($resultado);
    }

    function a_acuse(){
        $idFact = $_POST["idFact"];
        $this->VentasModel->acuse($idFact);

       
    }

    function a_actualizaCancelFact(){
        $idFact = $_POST["idFact"];
        $resultado = $this->VentasModel->actualizaCancelFact($idFact);

    }

    function pendienteFacturacion(){

        $azurian = $_POST["azurian"];
        $idFact = $_POST["idFact"];
        $monto = $_POST["monto"];
        $cliente = $_POST["cliente"];
        $trackId = $_POST["trackId"];
        $idVenta = $_POST["idVenta"];
        $documento = $_POST["doc"];
        $devo = $_POST["devo"];

        $resultado = $this->VentasModel->pendienteFacturacion($idFact,$monto,$cliente,$idVenta,$trackId,$azurian,$documento,$devo);

        echo json_encode($resultado);
    }

    function a_listaRecepciones(){
      $idoc=$_GET['idoc'];
      $resultReq =  $this->VentasModel->listaRecepciones($idoc);
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
            $r[7]='<span class="label label-warning" style="cursor:pointer;">Inactiva</span>';
          }
          if($r['activo']==1){

            $r[7]='<span class="label label-success" style="cursor:pointer;">Autorizada</span>';
          }
          if($r['activo']==2){

            $link='<a href="#" class="btn btn-default btn-xs disabled btn-block">'.$r['id'].'</a>';
            $r[7]='<span class="label label-default" style="cursor:pointer;">Cancelada</span>';
          }

          if($r['activo']==4){

            $link='<a href="#" class="btn btn-default btn-xs disabled btn-block">'.$r['id'].'</a>';
            $r[7]='<span class="label label-success" style="cursor:pointer;">Enviado OK</span>';
            $elimin='<a onclick="editRec('.$r['idr'].',4,'.$r['id'].');"  class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-search"></span> Ver</a>';
          }

          if($r['activo']==5){

            $link='<a href="#" class="btn btn-default btn-xs disabled btn-block">'.$r['id'].'</a>';
            $r[7]='<span class="label label-success" style="cursor:pointer;">Recepcion Parcial</span>';
            $elimin='<a onclick="editRec('.$r['idr'].',4,'.$r['id'].');"  class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-search"></span> Ver</a>';
          }



          $r[8]=$elimin;
          $listas['data'][]=$r;
        }
      }else{
        $listas['data']=array();
      }

      echo json_encode($listas);
    }

    function a_listaVentas(){
      $idoc=$_GET['idoc'];
      $resultReq =  $this->VentasModel->listaVentas($idoc);
      $listas=array();
      $listas['data']='';

      if($resultReq->num_rows>0){
        while ($r = $resultReq->fetch_array()) {
          $link='<a  class="btn btn-default btn-xs btn-block">'.$r['id'].'</span></a>';
          $elimin='<a onclick="editReq('.$r['idr'].',0,'.$r['id'].');" class="btn btn-primary btn-xs">Recibir orden</a>';
          if($r['urgente']==0){
            $r[5]='<span class="label label-default" style="cursor:pointer;">Normal</span>';
          }
          if($r['urgente']==1){
            $r[5]='<span class="label label-danger" style="cursor:pointer;">Urgente</span>';
          }
          if($r['activo']==0){
            $elimin='<a onclick="editReq('.$r['id'].');"  class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-edit"></span> Editar</a>
            <a onclick="eliminaReq('.$r['id'].')" class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-remove"></span> Borrar</a>';
            $r[6]='<span class="label label-warning" style="cursor:pointer;">Inactiva</span>';
          }
          if($r['activo']==1){

            $r[6]='<span class="label label-success" style="cursor:pointer;">Autorizada</span>';
          }
          if($r['activo']==2){

            $link='<a href="#" class="btn btn-default btn-xs disabled btn-block">'.$r['id'].'</a>';
            $r[6]='<span class="label label-default" style="cursor:pointer;">Cancelada</span>';
          }

          if($r['activo']==4){

            $link='<a href="#" class="btn btn-default btn-xs disabled btn-block">'.$r['id'].'</a>';
            $r[6]='<span class="label label-success" style="cursor:pointer;">Enviado OK</span>';
            $elimin='<a onclick="editRec('.$r['idr'].',4,'.$r['id'].');"  class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-search"></span> Ver venta</a> <a onclick="reprintRec('.$r['idr'].');"  class="btn btn-primary btn-xs">PDF</a>';            
          }

          if($r['activo']==5){

            $link='<a href="#" class="btn btn-default btn-xs disabled btn-block">'.$r['id'].'</a>';
            $r[6]='<span class="label label-success" style="cursor:pointer;">Recepcion Parcial</span>';
            $elimin='<a onclick="editRec('.$r['idr'].',4,'.$r['id'].');"  class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-search"></span> Ver venta</a> <a onclick="reprintRec('.$r['idr'].');"  class="btn btn-primary btn-xs">PDF</a>';
          }



          if($r[7]==0 && $r[9]==0){
            $r[8]='<a onclick="refacturar('.$r['idr'].','.$r['idFact'].','.$r['total'].');"  class="btn btn-primary btn-xs"> Facturar ticket</a>';
          }
          if($r[7]==0 && $r[9]==1){
            $r[8]='<a onclick="refacturar('.$r['idr'].','.$r['idFact'].','.$r['total'].');"  class="btn btn-primary btn-xs"> Refacturar</a>';
          }
          if($r[7]>0){
            $r[8]='<a onclick="cancelarFactura('.$r[7].');"  class="btn btn-danger btn-xs">Cancelar factura</a><a onclick="verFactura('.$r[7].');"  class="btn btn-primary btn-xs">XML</a> <a onclick="verFacturaPdf('.$r[7].');"  class="btn btn-primary btn-xs">PDF</a>';
          }

          if($r[12]>0){
            $r[8]='<a onclick="cancelarFactura('.$r[12].');"  class="btn btn-danger btn-xs">Cancelar factura</a><a onclick="verFactura('.$r[12].');"  class="btn btn-primary btn-xs">XML</a> <a onclick="verFacturaPdf('.$r[12].');"  class="btn btn-primary btn-xs">PDF</a>';
          }

          if($r['borrado']==3 || $r['borrado2']==3){
            $r[8]='<a onclick="acuse('.$r[7].');"  class="btn btn-danger btn-xs">Factura cancelada</a> <a onclick="refacturar2('.$r['idr'].','.$r['idFact'].','.$r['total'].');"  class="btn btn-primary btn-xs"> Refacturar</a>';
          }


          $r[7]=$elimin;

          $listas['data'][]=$r;
        }
      }else{
        $listas['data']=array();
      }

      echo json_encode($listas);
    }

    function a_getPedimentosProd(){
      $idProd=$_POST['idProd'];
      $modo=$_POST['modo'];
      $cadcar=$_POST['cadcar'];
      $resultReq =  $this->VentasModel->getPedimentosProd($idProd,$cadcar);

      echo json_encode($resultReq);

    }

  

    function a_getPedimentosProd4(){
      $idProd=$_POST['idProd'];
      $modo=$_POST['modo'];
      $resultReq =  $this->VentasModel->getPedimentosProd4($idProd);

      echo json_encode($resultReq);

    }

    function a_verFactura(){
      $idFact=$_POST['idFact'];
      $resultReq =  $this->VentasModel->verFactura($idFact);
      if($resultReq->num_rows>0){
        $r = $resultReq->fetch_assoc();
        //$r['xmlfile']=preg_replace('/(.xml)$/', '.pdf', $r['xmlfile']);
        echo $r['xmlfile'];
      }else{
        echo 0;
      }


    }

    function a_verFacturaPdf(){
      $idFact=$_POST['idFact'];
      $resultReq =  $this->VentasModel->verFacturaPdf($idFact);
      if($resultReq->num_rows>0){
        $r = $resultReq->fetch_assoc();
        //$r['xmlfile']=preg_replace('/(.xml)$/', '.pdf', $r['xmlfile']);
        echo $r['folio'];
      }else{
        echo 0;
      }


    }

    function a_getLotes(){
      $idProd=$_POST['idProd'];
      $modo=$_POST['modo'];
      $cadcar=$_POST['cadcar'];
      $resultReq =  $this->VentasModel->getLotes($idProd,$cadcar);

      echo json_encode($resultReq);

    }

    function a_getPedimentos(){
      $idProd=$_POST['idProd'];
      $modo=$_POST['modo'];
      $cadcar=$_POST['cadcar'];
      $resultReq =  $this->VentasModel->getPedimentos($idProd,$cadcar);

      echo json_encode($resultReq);

    }

    function a_getPedimentosLotes(){
      $idProd=$_POST['idProd'];
      $modo=$_POST['modo'];
      $cadcar=$_POST['cadcar'];
      $resultReq =  $this->VentasModel->getPedimentosLotes($idProd,$cadcar);

      echo json_encode($resultReq);

    }

    function a_getExistencias(){
      $idProd=$_POST['idProd'];
      $modo=$_POST['modo'];
      $cadcar=$_POST['cadcar'];
      $resultReq =  $this->VentasModel->getExistencias($idProd,$cadcar);

      echo json_encode($resultReq);

    }

    function a_getSeriesProd(){
      $idProd=$_POST['idProd'];
      $modo=$_POST['modo'];
      $cadcar=$_POST['cadcar'];
      $resultReq =  $this->VentasModel->getSeriesProd($idProd,$cadcar);

      echo json_encode($resultReq);

    }

    function a_getSeriesPed(){
      $idProd=$_POST['idProd'];
      $pedimentos=$_POST['pedimentos'];
      $resultReq =  $this->VentasModel->getSeriesPed($idProd,$pedimentos);

      echo json_encode($resultReq);

    }

    function a_listadoFacturasReporte(){
      $fini=$_GET['fini'];
      $ffin=$_GET['ffin'];
      $t=$_GET['t'];
      $resultReq =  $this->VentasModel->listadoFacturasReporte($fini,$ffin,$t);
      $listas=array();
      $listas['data']='';
      if($resultReq->num_rows>0){
        while ($r = $resultReq->fetch_array()) {

          if($r['facturado']==1 && $r['id_respFact']==0){
            $r[5]='<span class="label label-success" style="cursor:pointer;">Facturada</span>';
            if($r['tcomp']=='F'){
              $r[6]='Factura: '.$r['idfact'];
            }else if ($r['tcomp']=='C') {
              $r[6]='Nota de credito';
            }
            // Re facturado
          }
          if( $r['facturado']==1 && $r['id_respFact']>0 ){
            $r[5]='<span class="label label-success" style="cursor:pointer;">Facturada (Masiva)</span>'; 
            $r[6]='Factura: '.$r['idfact'];
            // Re factura masiva
          }
          
          if( $r['idsale']>0 && $r['facturado']==0 ){
            $r[5]='<span class="label label-success" style="cursor:pointer;">Facturada</span>'; // Facturado
            if($r['tcomp']=='F'){
              $r[6]='Factura: '.$r['idfact'];
            }else if ($r['tcomp']=='C') {
              $r[6]='Nota de credito';
            }
          }

          if( $r['xmlmasivo']==null && $r['xmlfile']==null ){
            $r[5]='<span class="label label-default" style="cursor:pointer;">No Facturada</span>'; // Facturado
            $r[6]='';
          }

          if( $r['xfile']!=null && $r['folio']!=null ){
            $r[7]='<a onclick="verFactura('.$r['idfact'].');"  class="btn btn-primary btn-xs">XML</a> <a onclick="verFacturaPdf('.$r['idfact'].');"  class="btn btn-primary btn-xs">PDF</a>'; // Facturado
          }else{
            $r[7]=''; // Facturado
          }

          if( $r['borrado']==3 or $r['borrado2']==3 ){
            $r[5]='<span class="label label-danger" style="cursor:pointer;">Factura cancelada</span>'; 
            $r[6]='Factura: '.$r['idfact'];
            // Cancelado
          }

          $listas['data'][]=$r;
        }
      }else{
        $listas['data']=array();
        //$listas=0;
      }

      echo json_encode($listas);
    }

    function a_listadoFacturas(){
      $fini=$_GET['fini'];
      $ffin=$_GET['ffin'];
      $resultReq =  $this->VentasModel->listadoFacturas($fini,$ffin);
      $listas=array();
      $listas['data']='';
      if($resultReq->num_rows>0){
        while ($r = $resultReq->fetch_array()) {
          if($r['tieneabono']==0){
            $listas['data'][]=$r;
          }
        }
      }else{
        $listas['data']=array();
      }

      echo json_encode($listas);
    }

    function a_listaOrdenesEnvio(){
      $resultReq =  $this->VentasModel->listaOrdenesCompra();
      $listas=array();
      $listas['data']='';
      if($resultReq->num_rows>0){
        while ($r = $resultReq->fetch_array()) {
          $link='<a  class="btn btn-default btn-xs btn-block">'.$r['id'].'</span></a>';
          $elimin='<a onclick="editReq('.$r['idreq'].',0,'.$r['id'].');" class="btn btn-primary btn-xs">Hacer envio</a>';
          if($r['urgente']==0){
            $r[6]='<span class="label label-default" style="cursor:pointer;">Normal</span>';
          }
          if($r['urgente']==1){
            $r[6]='<span class="label label-danger" style="cursor:pointer;">Urgente</span>';
          }
          if($r['activo']==0){
            $elimin='<a onclick="editReq('.$r['id'].');"  class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-edit"></span> Editar</a>
            <a onclick="eliminaReq('.$r['id'].')" class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-remove"></span> Borrar</a>';
            $r[7]='<span class="label label-warning" style="cursor:pointer;">Inactiva</span>';
          }
          if($r['activo']==1){

            $r[7]='<span class="label label-success" style="cursor:pointer;">Autorizada</span>';
          }
          if($r['activo']==2){

            $link='<a href="#" class="btn btn-default btn-xs disabled btn-block">'.$r['id'].'</a>';
            $r[7]='<span class="label label-default" style="cursor:pointer;">Cancelada</span>';
          }

          if($r['activo']==4){

            $link='<a href="#" class="btn btn-default btn-xs disabled btn-block">'.$r['id'].'</a>';
            $r[7]='<span class="label label-success" style="cursor:pointer;">Enviado OK</span>';
            $elimin='<a onclick="listarec('.$r['id'].');"  class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-th-list"></span> Envios</a> ';
          }

          if($r['activo']==5){

            $link='<a href="#" class="btn btn-default btn-xs disabled btn-block">'.$r['id'].'</a>';
            $r[7]='<span class="label label-success" style="cursor:pointer;">Recepcion Parcial</span>';
            $elimin='<a onclick="listarec('.$r['id'].');"  class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-th-list"></span> Envios</a> <a onclick="editReq('.$r['idreq'].',0,'.$r['id'].');" class="btn btn-primary btn-xs">Recibir</a>';
          }

          if($r['activo']==6){

            $link='<a href="#" class="btn btn-default btn-xs disabled btn-block">'.$r['id'].'</a>';
            $r[7]='<span class="label label-warning" style="cursor:pointer;">Pendiente aclaracion</span>';
            $elimin='<a onclick="editReq('.$r['idreq'].',0,'.$r['id'].');" class="btn btn-primary btn-xs">Recibir</a>';
          }



          $r[8]=$elimin;
          $listas['data'][]=$r;
        }
      }else{
        $listas['data']=array();
      }

      echo json_encode($listas);
    }

    function a_change_idoc_idreq(){
      $idoc=$_POST['idoc'];
      $resultReq = $this->VentasModel->a_change_idoc_idreq($idoc);
      echo $resultReq['rows'][0]['id_requisicion'];
    }

    function ordenes()
    {

      if(isset($_GET['v'])){
        $id_oc=$_GET['id_oventa'];
        $vv=$_GET['v'];

      }else{
        $vv=0;
      }

      /* NUMERO REQUISICIONES POR AUTORIZAR =========
      =============================================== */
      $resultReq = $this->VentasModel->getReqsAutorizar();
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

      $resultReq = $this->VentasModel->getClientes();
      if($resultReq->num_rows>0){
        while ($r = $resultReq->fetch_assoc()) {
          $clientes[]=$r;
        }
      }else{
        $clientes=0;
      }


      /* REQUIERO DE CONFIGURACION ==================
      =============================================== */
      $resultReq = $this->VentasModel->getPeriodoFecha();
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

      $resultReq = $this->VentasModel->getAlmacen();
      if($resultReq->num_rows>0){
        while ($r = $resultReq->fetch_assoc()) {
          $almacenes[]=$r;
        }
      }else{
        $almacenes=0;
      }



      $resultReq = $this->VentasModel->getEmpleados();
      if($resultReq->num_rows>0){
        while ($r = $resultReq->fetch_assoc()) {
          $empleados[]=$r;
        }
      }else{
        $empleados=0;
      }

      $resultReq = $this->VentasModel->getTipoGasto();
      if($resultReq->num_rows>0){
        while ($r = $resultReq->fetch_assoc()) {
          $tipoGasto[]=$r;
        }
      }else{
        $tipoGasto=0;
      }

      $resultReq = $this->VentasModel->getProveedores();
      if($resultReq->num_rows>0){
        while ($r = $resultReq->fetch_assoc()) {
          $proveedores[]=$r;
        }
      }else{
        $proveedores=0;
      }

      $resultReq = $this->VentasModel->getProductos();
      if($resultReq->num_rows>0){
        while ($r = $resultReq->fetch_assoc()) {
          $productos[]=$r;
        }
      }else{
        $productos=0;
      }

      $resultReq = $this->VentasModel->getMonedas();
      if($resultReq->num_rows>0){
        while ($r = $resultReq->fetch_assoc()) {
          $monedas[]=$r;
        }
      }else{
        $monedas=0;
      }

      $resultReq = $this->VentasModel->getUsuario();
      if($resultReq->num_rows>0){
        $set = $resultReq->fetch_assoc();
        $username=$set['username'];
        $iduser=$set['idempleado'];
      }else{
        $username='Favor de salir y loguearse nuevamente';
        $iduser='0';
      }


      require('views/ventas/v_ordenes.php');
    }

    function requisiciones()
    {

      /* REQUIERO DE CONFIGURACION ==================
      =============================================== */
      $resultReq = $this->VentasModel->getPeriodoFecha();
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

      $resultReq = $this->VentasModel->getClientes();
      if($resultReq->num_rows>0){
        while ($r = $resultReq->fetch_assoc()) {
          $clientes[]=$r;
        }
      }else{
        $clientes=0;
      }

      $resultReq = $this->VentasModel->getAlmacen();
      if($resultReq->num_rows>0){
        while ($r = $resultReq->fetch_assoc()) {
          $almacenes[]=$r;
        }
      }else{
        $almacenes=0;
      }

      $resultReq = $this->VentasModel->getEmpleados();
      if($resultReq->num_rows>0){
        while ($r = $resultReq->fetch_assoc()) {
          $empleados[]=$r;
        }
      }else{
        $empleados=0;
      }

      $resultReq = $this->VentasModel->getTipoGasto();
      if($resultReq->num_rows>0){
        while ($r = $resultReq->fetch_assoc()) {
          $tipoGasto[]=$r;
        }
      }else{
        $tipoGasto=0;
      }

      $resultReq = $this->VentasModel->getProveedores();
      if($resultReq->num_rows>0){
        while ($r = $resultReq->fetch_assoc()) {
          $proveedores[]=$r;
        }
      }else{
        $proveedores=0;
      }

      $resultReq = $this->VentasModel->getProductos();
      if($resultReq->num_rows>0){
        while ($r = $resultReq->fetch_assoc()) {
          $productos[]=$r;
        }
      }else{
        $productos=0;
      }

      $resultReq = $this->VentasModel->getMonedas();
      if($resultReq->num_rows>0){
        while ($r = $resultReq->fetch_assoc()) {
          $monedas[]=$r;
        }
      }else{
        $monedas=0;
      }

      $resultReq = $this->VentasModel->getUsuario();
      if($resultReq->num_rows>0){
        $set = $resultReq->fetch_assoc();
        $username=$set['username'];
        $iduser=$set['idempleado'];
      }else{
        $username='Favor de salir y loguearse nuevamente';
        $iduser='0';
      }


      require('views/ventas/v_requisiciones.php');
    }

    function configDesc(){
        $configDesc = $this->VentasModel->configDesc();
        echo json_encode($configDesc);
    }

    function a_nuevarequisicion()
    {
      $resultReq = $this->VentasModel->getLastNumRequisicion();
      if($resultReq->num_rows>0){
        $row = $resultReq->fetch_array();
        $JSON = array('success' =>1, 'requisicion'=>$row['id']);
      }else{
        $JSON = array('success' =>0);
      }
      
      echo json_encode($JSON);

    }

    function a_nuevaov()
    {
      $resultReq = $this->VentasModel->getLastNumOV();
      if($resultReq->num_rows>0){
        $row = $resultReq->fetch_array();
        $JSON = array('success' =>1, 'ov'=>$row['id']);
      }else{
        $JSON = array('success' =>0);
      }
      
      echo json_encode($JSON);

    }

    function a_enviarVenta2(){
      $idCoti=$_POST['idCoti'];
      $modo=$_POST['modo'];
      $print=$_POST['print'];
      $op=$_POST['op'];
      //$obs=$_POST['obs'];

      $resultReq = $this->VentasModel->editarRequisicionEnvio($idCoti,1);
      if($resultReq->num_rows>0){
        $row = $resultReq->fetch_assoc();
        $productos='';
        $impuestos='';
        $resultReq2 = $this->VentasModel->productosRequisicion($idCoti,$row['id_cliente'],3,$mod);
        while ($row2 = $resultReq2->fetch_assoc()) {
          $productos.=$row2['id'].'-'.$row2['costo'].'-'.$row2['cantidad'].'-1'.'-'.$row2['caracteristica'].'/';
        }
         $productos=trim($productos,'/');
        //var_dump($impuestos);

        //idProdcuto-precio-cantidad-formula/idProducto2-precio2-cantidad2-formula2/
            //$productos = '41-100-1-0/42-50-1-2/44-100-1-1';

        
        $resultReq4 =  $this->VentasModel->calculaImpuestosFact($productos);
        $st=$row['st'];
        $tt=$row['tt'];
        if($row['st']==null){ $st=$row['rst']; }
        if($row['tt']==null){ $tt=$row['rtt']; }
        session_start();
        $_SESSION["caja"]=$resultReq4;
        $_SESSION['caja']['cargos']['subtotal'] = $st;
        $_SESSION['caja']['cargos']['total'] = $tt;

        //$this->VentasModel->save($row['id_cliente'],'',$idCoti,$print,$op,$row['moneda'],$row['observaciones'].'. Fecha de entrega: '.substr($row['fecha_entrega'],0,10));


        if($row['observaciones']==''){
          $br='';
        }else{
          $br='<br><br>';
        }
        
        $autorizaciones.='<b>Cotización:</b> '.$row['fecha_creacion1'].' - '.$row['username1'];
        $autorizaciones.='<br>';
        $autorizaciones.='<b>Autorización:</b> '.$row['fecha_creacion2'].' - '.$row['username2'];

        $this->VentasModel->save($row['id_cliente'],'',$idCoti,$print,$op,$row['moneda'],$row['observaciones'].$br.'<b>Fecha de entrega:</b> '.substr($row['fecha_entrega'],0,10).' <br>'.$autorizaciones);
        
        
        unset($_SESSION["caja"]);
      }
    }

    function a_enviarCotizacion2(){

      $idCoti=$_POST['idCoti'];

      $modo=$_POST['modo'];

      $print=$_POST['print'];
      $op=$_POST['op'];
      $tipo=$_POST['tipo'];
      //$obs=$_POST['obs'];

      $resultReq = $this->VentasModel->editarRequisicionEnvio($idCoti,1);

      if($resultReq->num_rows>0){
        $row = $resultReq->fetch_assoc();

        //ch@
        $desc = $row['descc'];
        $descCant =$row['monto_desc'];

        $productos='';
        $impuestos='';

        $resultReq2 = $this->VentasModel->productosRequisicion($idCoti,$row['id_cliente'],$m,$mod);

        while ($row2 = $resultReq2->fetch_assoc()) {
          $productos.=$row2['id'].'-'.$row2['costo'].'-'.$row2['cantidad'].'-1'.'-'.$row2['caracteristica'].'/';

        }

         $productos=trim($productos,'/');         
        //var_dump($impuestos);

        //idProdcuto-precio-cantidad-formula/idProducto2-precio2-cantidad2-formula2/
            //$productos = '41-100-1-0/42-50-1-2/44-100-1-1';

        
        $resultReq4 =  $this->VentasModel->calculaImpuestosFact($productos);
        $st=$row['st'];
        $tt=$row['tt'];
        $idOV=$row['idOV'];
        if($row['st']==null){ $st=$row['rst']; }
        if($row['tt']==null){ $tt=$row['rtt']; }
        session_start();
        $_SESSION["caja"]=$resultReq4;
        $_SESSION['caja']['cargos']['subtotal'] = $st;
        $_SESSION['caja']['cargos']['total'] = $tt;
        $_SESSION['idOV']=$idOV;

        //$this->VentasModel->save($row['id_cliente'],'',$idCoti,$print,$op,$row['moneda'],$row['observaciones'].'. Fecha de entrega: '.substr($row['fecha_entrega'],0,10));


        if($row['observaciones']==''){
          $br='';
        }else{
          $br='<br><br>';
        }
        
        if($tipo=='Cotizacion'){
          $_SESSION['idOV']='';
        }


        $autorizaciones.='<b>Cotización:</b> '.$row['fecha_creacion1'].' - '.$row['username1'];
        $autorizaciones.='<br>';
        $autorizaciones.='<b>Autorización:</b> '.$row['fecha_creacion2'].' - '.$row['username2'];

        //$this->VentasModel->save($row['id_cliente'],'',$idCoti,$print,$op,$row['moneda'],$row['observaciones'].$br.'<b>Fecha de entrega:</b> '.substr($row['fecha_entrega'],0,10).' <br>'.$autorizaciones,$tipo);
        $this->VentasModel->save($row['id_cliente'],'',$idCoti,$print,$op,$row['moneda'],$row['observaciones'].$br.'<br>'.$autorizaciones,$tipo,'','',$desc,$descCant);
        
        
        unset($_SESSION["caja"]);

        echo $idOV;
      }
    }

    function a_enviarCotizacion()
    {
      $op=$_POST['op'];

      $idCoti=$_POST['idCoti'];
      $imps=$_POST['imps'];
      $pr=1;
      $resultReq = $this->VentasModel->editarRequisicionEnvio($idCoti,$pr);
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
        //$data['reqdata']['logoempresa']=$row['logoempresa'];

        if($op==0){
          $msgemail=$data['reqdata']['empresa'].' le ha enviado la siguiente cotizacion';
          $msgcoti='Cotizacion';
        }
        if($op==1){
          $msgemail='Se ha generado la siguiente Orden de venta';
          $msgcoti='Orden de venta';
        }

        $resultReq2 = $this->VentasModel->productosRequisicion($idCoti,$row['id_cliente'],$m,$mod);
        while ($row2 = $resultReq2->fetch_assoc()) {
          if($row2['caracteristica']!='0'){
              $resultCaras = $this->VentasModel->caracteristicaReq($row2['caracteristica']);
              $row2['nomprod']=$row2['nomprod'].' '.$resultCaras;
          }
          $data['prodata'][]=$row2;
        }

        require('views/ventas/v_coti.php');
      }
    }

    function a_imprimir2(){

    }

    function a_imprimir()
    {

      $idsProductos=$_POST['idsProductos'];
      $solicitante=$_POST['solicitante'];
      $tipogasto=$_POST['tipogasto'];
      $moneda=$_POST['moneda'];
      $cliente=$_POST['cliente'];
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


      $resultReq = $this->VentasModel->datosImpresion($cliente);
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
        $data['reqdata']['doc']='Cotizacion de venta';

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
            $caracteristica=$exp[4];
            $costo=$exp[2];
            $idlista=$exp[3];

            $nomprod = $this->VentasModel->productosTicket($idprod);
            $resultCaras = $this->VentasModel->caracteristicaReq($caracteristica);
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

    function a_verTicketN(){

      $idCoti=$_POST['idCoti'];

      $modo=$_POST['modo'];

      $print=$_POST['print'];
      $op=$_POST['op'];
      $tipo=$_POST['tipo'];
      $imps=$_POST['imps'];

      $st=$_POST['ist'];
      $tt=$_POST['it'];


      $r=$_POST['venta'];
      $pr=1;
      //$obs=$_POST['obs'];

      $idoc=$_POST['idoc'];
      $resultReq = $this->VentasModel->a_change_idoc_idreq($idCoti);

      $iddd=$resultReq['rows'][0]['id_requisicion'];

      $resultReq = $this->VentasModel->editarRequisicionEnvio($iddd,$pr);

      if($resultReq->num_rows>0){
        $row = $resultReq->fetch_assoc();

        if($op==0){
          $msgemail=$data['reqdata']['empresa'].' le ha enviado la siguiente cotizacion';
          $msgcoti='Cotizacion';
        }
        if($op==1){
          $msgemail='Se ha generado la siguiente Orden de venta';
          $msgcoti='Orden de venta';
        }
        $m=3;

        $productos='';
        $impuestos='';
/*
        $resultReq2 = $this->VentasModel->productosRequisicion($idCoti,$row['id_cliente'],$m,$mod);

        while ($row2 = $resultReq2->fetch_assoc()) {
          $productos.=$row2['id'].'-'.$row2['costo'].'-'.$row2['cantidad'].'-1'.'-'.$row2['caracteristica'].'/';

        }
         $productos=trim($productos,'/');
*/

         $resultReq2 = $this->VentasModel->productosRequisicion($iddd,$row['id_cliente'],$m,$mod);
        while ($row2 = $resultReq2->fetch_assoc()) {
          $productos.=$row2['id'].'-'.$row2['costo'].'-'.$row2['cantidadr'].'-1'.'-'.$row2['caracteristica'].'/';

          if($row2['caracteristica']!='0'){
              $resultCaras = $this->VentasModel->caracteristicaReq($row2['caracteristica']);
              $row2['nomprod']=$row2['nomprod'].' '.$resultCaras;
          }else{
            $row2['nomprod']=$row2['nomprod'];
          }
          if($row2['series']=='1'){
              $resultSerie = $this->VentasModel->getSerieVenta($row2['id'],$envio);
              $row2['nomprod'].=' | '.$resultSerie;
          }
          $data['prodata'][]=$row2;
        }




        //idProdcuto-precio-cantidad-formula/idProducto2-precio2-cantidad2-formula2/
            //$productos = '41-100-1-0/42-50-1-2/44-100-1-1';

        
        $resultReq4 =  $this->VentasModel->calculaImpuestosFact($productos);




        //if($row['st']==null){ $st=$row['rst']; }
        //if($row['tt']==null){ $tt=$row['rtt']; }
        session_start();
        $_SESSION["caja"]=$resultReq4;
        $_SESSION['caja']['cargos']['subtotal'] = $st;
        $_SESSION['caja']['cargos']['total'] = $tt;

        //$this->VentasModel->save($row['id_cliente'],'',$idCoti,$print,$op,$row['moneda'],$row['observaciones'].'. Fecha de entrega: '.substr($row['fecha_entrega'],0,10));


        if($row['observaciones']==''){
          $br='';
        }else{
          $br='<br><br>';
        }
        
        $autorizaciones.='<b>Cotización:</b> '.$row['fecha_creacion1'].' - '.$row['username1'];
        $autorizaciones.='<br>';
        $autorizaciones.='<b>Autorización:</b> '.$row['fecha_creacion2'].' - '.$row['username2'];

        $tipo='Envio';
        $print=1;

        if($r>0 && $r!=''){
          $idCoti=$r;
        }
        $this->VentasModel->save($row['id_cliente'],'',$idCoti,$print,$op,$row['moneda'],$row['observaciones'].$br.'<b>Fecha de entrega:</b> '.substr($row['fecha_entrega'],0,10).' <br>'.$autorizaciones,$tipo);
        
        
        unset($_SESSION["caja"]);
      }
    }

    function a_verTicket()
    {

      $op=$_POST['op'];
      $envio=$_POST['venta'];

      $idCoti=$_POST['idCoti'];
      $imps=$_POST['imps'];
      $pr=1;

      $idoc=$_POST['idoc'];
      $resultReq = $this->VentasModel->a_change_idoc_idreq($idCoti);

      $iddd=$resultReq['rows'][0]['id_requisicion'];

      $resultReq = $this->VentasModel->editarRequisicionEnvio($iddd,$pr);
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
          $msgemail=$data['reqdata']['empresa'].' le ha enviado la siguiente cotizacion';
          $msgcoti='Cotizacion';
        }
        if($op==1){
          $msgemail='Se ha generado la siguiente Orden de venta';
          $msgcoti='Orden de venta';
        }
        $m=3;
        
        $resultReq2 = $this->VentasModel->productosRequisicion($iddd,$row['id_cliente'],$m,$mod);
        while ($row2 = $resultReq2->fetch_assoc()) {
          if($row2['caracteristica']!='0'){
              $resultCaras = $this->VentasModel->caracteristicaReq($row2['caracteristica']);
              $row2['nomprod']=$row2['nomprod'].' '.$resultCaras;
          }else{
            $row2['nomprod']=$row2['nomprod'];
          }
          if($row2['series']=='1'){
              $resultSerie = $this->VentasModel->getSerieVenta($row2['id'],$envio);
              $row2['nomprod'].=' | '.$resultSerie;
          }
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
      $resultReq = $this->VentasModel->editarRequisicion($idReq,$pr);
      $sinexist = $this->VentasModel->sinexist();
      if($resultReq->num_rows>0){
      
        $row = $resultReq->fetch_assoc();
        $row['fecha']=substr($row['fecha'],0,10);
        $row['fecha_entrega']=substr($row['fecha_entrega'],0,10);
 
        $resultReq2 = $this->VentasModel->productosRequisicion($idReq,$row['id_cliente'],$m,$mod);
        while ($row2 = $resultReq2->fetch_assoc()) {
          if($row2['caracteristica']!='0'){
              $resultCaras = $this->VentasModel->caracteristicaReq($row2['caracteristica']);
              $row2['nomprod']=$row2['nomprod'].' '.$resultCaras;
          }

          if($row2['id_lista']==0){
            $fs=' selected ';
          }else{
            $fs='';
          }

          if($row2['caracteristica']!='0'){
            $disa=' disabled ';
          }else{
            $disa='';
          }



          $row2['adds']='<select '.$disa.' id="prelis" onchange="refreshCants('.$row2['id'].',\''.$row2['caracteristica'].'\')">
          <option '.$fs.' value="'.$row2['precioorig'].'>0">$'.$row2['precioorig'].' Precio lista</option>';

            $resultReq = $this->VentasModel->addListasPrecio($row2['id'],$row['id_cliente']);
            if($resultReq->num_rows>0){
              while ($r = $resultReq->fetch_assoc()) {
                if($row2['id_lista']==$r['idlista']){
                  $ss=' selected ';
                }else{
                  $ss='';
                }

                if($row2['id_lista']!='0' && $row2['id_lista']!='x'){
                  $row2['adds'].='<option '.$ss.' value="'.$r['valorpre'].'>'.$r['idlista'].'">$'.$r['valorpre'].' '.$r['nombre'].'</option>';
                }
              }
            }else{
              $row2['adds']='';
            }

            if($row2['id_lista']=='x'){
              $row2['adds'].='<option selected value="'.$row2['costo'].'>x">$'.$row2['costo'].'</option>';
            }else{
              $row2['adds'].='<option value="OTRO>x">Otro precio</option>';
            }

            // DESC             
            if($row2['monto_desc']>'0'){
              $removeD= '</select><a id="elinew_'.$row2['id'].'_'.$row2['caracteristica'].'" ch="'.$row2['caracteristica'].'" style="cursor:pointer;" onclick="restartOTRO2('.$row2['id'].',\''.$row2['caracteristica'].'\',1);"> x </a>';
            }else{
              $removeD='</select>';
            }    
            $row2['adds'].=$removeD;          
            // DESC FIN

            

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
            $resultReq3 = $this->VentasModel->getLoteProd($idReq);
          }elseif($row2['series']==1 && $row2['pedimentos']==0 && $row2['lotes']==0){

          }elseif($row2['series']==1 && $row2['pedimentos']==1 && $row2['lotes']==0){
            $resultReq3 = $this->VentasModel->getSPProd($idReq);
          }elseif($row2['pedimentos']==1 && $row2['series']==0 && $row2['lotes']==0){

          }

          $productos[]=$row2;
        }
       // $row2 = $resultReq2->fetch_assoc();
        
        $JSON = array('success' =>1, 'requisicion'=>$row, 'productos'=>$productos, 'adds'=>$adds, 'ss'=>$sinexist);
      }else{
        $JSON = array('success' =>0);
      }
      
      echo json_encode($JSON);

    }

    function a_verEnvio()
    {
      $idRec=$_POST['idRec'];
      $idOC=$_POST['idOC'];
      $m=$_POST['m'];
      $mod=$_POST['mod'];
      $resultReq = $this->VentasModel->editarRequisicionRec($idRec,$idOC);
      if($resultReq->num_rows>0){
      
        $row = $resultReq->fetch_assoc();
        $idReq=$row['idreq'];
        $row['fecha']=substr($row['fecha'],0,10);
        $row['fecha_entrega']=substr($row['fecha_entrega'],0,10);

        $resultReq2 = $this->VentasModel->productosRequisicion($idReq,$row['id_proveedor'],$m,$mod,$idRec);
        while ($row2 = $resultReq2->fetch_assoc()) {
          if($row2['caracteristica']!='0'){
              $resultCaras = $this->VentasModel->caracteristicaReq($row2['caracteristica']);
              $row2['nomprod']=$row2['nomprod'].' '.$resultCaras;
          }
  
          if($m==3){
            $resEnviados = $this->VentasModel->getEnviados($idRec,$row2['id'],$row2['caracteristica']);
            if($resEnviados->num_rows>0){
              $rowenv = $resEnviados->fetch_assoc();
              $cantdev=$rowenv['cantdev'];
              $row2['cantdev']=$cantdev;
            }else{
              $row2['cantdev']=0;
            }
          }

          if($row2['lotes']==1 && $row2['series']==0 && $row2['pedimentos']==1){
            $modo=5;
            $idProd=$row2['id'];
            $cadcar=$row2['caracteristica'];
            $cantrecibid=$row2['recibidorec'];
            $pedimentos_imp=$row2['no_pedimento'].'->-'.$row2['aduana'].'->-'.$row2['no_aduana'].'->-'.$row2['tipo_cambio'].'->-'.$row2['fecha_pedimento'].'->-'.$row2['no_lote'].'->-'.$row2['fecha_fabricacion'].'->-'.$row2['fecha_caducidad'];
            $echo=$modo.'->-'.$idProd.'->-'.$cantrecibid.'->-'.$pedimentos_imp;
          }elseif($row2['series']==0 && $row2['pedimentos']==0 && $row2['lotes']==1){
            $modo=1;
            $idProd=$row2['id'];
            $cadcar=$row2['caracteristica'];
            $cantrecibid=$row2['recibidorec'];
            $echo=$modo.'->-'.$idProd.'->-'.$cantrecibid.'->-'.$row2['no_lote'].'->-'.$row2['fecha_fabricacion'].'->-'.$row2['fecha_caducidad'];
          }elseif($row2['series']==1 && $row2['pedimentos']==0 && $row2['lotes']==0){
            $resultSA = $this->VentasModel->getSeriesActivas($idRec,$row2['id']);
            if($resultSA->num_rows>0){
              $rowx = $resultSA->fetch_assoc();
              $seriesx=$rowx['seriesx'];
              $idsx=$rowx['idsx'];

            }
            $modo=2;
            $idProd=$row2['id'];
            $cadcar=$row2['caracteristica'];
            $cantrecibid=$row2['recibidorec'];
            $nser=($cantrecibid*1)-($row2['cantdev']*1);
            $echo=$modo.'->-'.$idProd.'->-'.$nser.'->-'.$nser.'->-'.$seriesx;
          }elseif($row2['series']==1 && $row2['pedimentos']==1 && $row2['lotes']==0){
            $resultSA = $this->VentasModel->getSeriesActivas($idRec,$row2['id']);
            if($resultSA->num_rows>0){
              $rowx = $resultSA->fetch_assoc();
              $seriesx=$rowx['seriesx'];
              $idsx=$rowx['idsx'];
            }
            $modo=3;
            $idProd=$row2['id'];
            $cadcar=$row2['caracteristica'];
            $cantrecibid=$row2['recibidorec'];
            $nser=($cantrecibid*1)-($row2['cantdev']*1);
            $pedimentos_imp=$row2['no_pedimento'].'->-'.$row2['aduana'].'->-'.$row2['no_aduana'].'->-'.$row2['tipo_cambio'].'->-'.$row2['fecha_pedimento'];
            $echo=$modo.'->-'.$idProd.'->-'.$nser.'->-'.$pedimentos_imp.'->-'.$nser.'->-'.$seriesx;
          }elseif($row2['pedimentos']==1 && $row2['series']==0 && $row2['lotes']==0){
            $modo=4;
            $idProd=$row2['id'];
            $cadcar=$row2['caracteristica'];
            $cantrecibid=$row2['recibidorec'];
            $pedimentos_imp=$row2['no_pedimento'].'->-'.$row2['aduana'].'->-'.$row2['no_aduana'].'->-'.$row2['tipo_cambio'].'->-'.$row2['fecha_pedimento'];
            $echo=$modo.'->-'.$idProd.'->-'.$cantrecibid.'->-'.$pedimentos_imp;
          }else{
            $echo='';
          }
          $row2['eecho']=$echo;
          $productos[]=$row2;
        }
       // $row2 = $resultReq2->fetch_assoc();
        
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
      $resultReq = $this->VentasModel->editarRequisicionRec($idRec);
      if($resultReq->num_rows>0){
      
        $row = $resultReq->fetch_assoc();
        $idReq=$row['idreq'];
        $row['fecha']=substr($row['fecha'],0,10);
        $row['fecha_entrega']=substr($row['fecha_entrega'],0,10);

        $resultReq2 = $this->VentasModel->productosRequisicion($idReq,$row['id_proveedor'],$m,$mod);
        while ($row2 = $resultReq2->fetch_assoc()) {


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
            $resultReq3 = $this->VentasModel->getLoteProd($idReq);
          }elseif($row2['series']==1 && $row2['pedimentos']==0 && $row2['lotes']==0){

          }elseif($row2['series']==1 && $row2['pedimentos']==1 && $row2['lotes']==0){
            $resultReq3 = $this->VentasModel->getSPProd($idReq);
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
      $resultReq = $this->VentasModel->deleteReq($idReq);
      echo $resultReq;

    }

    function a_getAlmacenes()
    {

      $resultReq = $this->VentasModel->getAlmacenes2();
      if($resultReq['total']>0){
        echo json_encode($resultReq['rows']);
      }

    }

    function a_addGeneraReq()
    {
      $idProducto=$_POST['idProducto'];
      $resultReq = $this->VentasModel->addProductoReq($idProducto);
      if($resultReq->num_rows>0){
        $row = $resultReq->fetch_array();
        $producto[]=$row;
      }else{
        echo "Error Error Error"; exit();
      }
      
      echo json_encode($producto);

    }

    function a_addProductoReq() //eliminar func
    {
      $idProducto=$_POST['idProducto'];
      $idProveedor=$_POST['idProveedor'];
      $resultReq = $this->VentasModel->addProductoReq($idProducto,$idProveedor);
      if($resultReq->num_rows>0){
        $row = $resultReq->fetch_array();
        $producto[]=$row;
        $JSON = array('success' =>1, 'datos'=>$producto);
      }else{
        $JSON = array('success' =>0);
      }
      
      echo json_encode($JSON);

    }

    function a_addProductoVenta() //eliminar func
    {
      $idProducto=$_POST['idProducto'];
      $idCliente=$_POST['idCliente'];
      $resultReq = $this->VentasModel->addProductoVenta($idProducto,$idCliente);
      $resultCarP = $this->VentasModel->getCaracteristicasProdP($idProducto);
      if($resultCarP->num_rows>0){
        $html='';
        $cccar='';
        while ($r = $resultCarP->fetch_assoc()) {
          $html.='<div class="s7 col-sm-12" style="padding-top:10px;"><div class="form-group">';
          $html.='<label id="npadre" class="col-sm-6 control-label text-left">'.$r['nombrecp'].'</label>';
          $html.='<div class="col-sm-6" style="color:#000;">';
          $resultCarH = $this->VentasModel->getCaracteristicasProdH($r['idcp']);
          if($resultCarH->num_rows>0){
            $html.='<select class="carh">';
            while ($rH = $resultCarH->fetch_assoc()) {
              $cccar.=$r['idcp'].'=>'.$rH['id'].',';
              $html.='<option value="'.$r['idcp'].'=>'.$rH['id'].'">'.$rH['nombre'].'</option>';
            }
            $cccar=trim($cccar,',');
            $html.='</select>';
          }else{
            $cccar=0;
          }
          $html.='</div></div></div><div class="row"></div>';
        }

      }else{
        $cccar=0;
        $html='';
      }

      if($resultReq->num_rows>0){
        $row = $resultReq->fetch_array();
        $producto[]=$row;

        $adds='<select id="prelis" onchange="refreshCants('.$producto[0]['id'].',0,0)">
          <option value="'.$producto[0]['costo'].'>0">$'.$producto[0]['costo'].' Precio lista</option>';
        $resultReq = $this->VentasModel->addListasPrecio($idProducto,$idCliente);
        if($resultReq->num_rows>0){
          while ($r = $resultReq->fetch_assoc()) {
            if($r['tienelista']!='0'){
              $adds.='<option value="'.$r['valorpre'].'>'.$r['idlista'].'">$'.$r['valorpre'].' '.$r['nombre'].'</option>';
            }
          }
        }else{
          //$adds='';
        }

        $adds.='<option value="OTRO>x">Otro precio</option>';

        $JSON = array('success' =>1, 'datos'=>$producto, 'adds'=>$adds, 'car'=>$html, 'cccar'=>$cccar);
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
      $resultReq = $this->VentasModel->solacla($idrequi);
      if($resultReq->num_rows>0){
        $JSON = array('success' =>1);
      }else{
        $JSON = array('success' =>0);
      }
      
      echo json_encode($JSON);

    }   

    function a_getProvProducto(){
      $idProveedor=$_POST['idProveedor'];
      $resultReq = $this->VentasModel->getProvProducto($idProveedor);
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

    function a_getProdMoneda(){
      $idMoneda=$_POST['idMoneda'];
      $resultReq = $this->VentasModel->a_getProdMoneda($idMoneda);
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

    function a_envioOrden(){
      $idsProductos=$_POST['idsProductos'];
      $solicitante=$_POST['solicitante'];
      $tipogasto=$_POST['tipogasto'];
      $moneda=$_POST['moneda'];
      $proveedor=$_POST['proveedor'];
      $cliente=$_POST['cliente'];
      $urgente=$_POST['urgente'];
      $inventariable=$_POST['inventariable'];
      $moneda_tc=$_POST['moneda_tc'];
      $fechahoy=trim($_POST['fechahoy']);
      $fechaentrega=trim($_POST['fechaentrega']);
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
      $fp=trim($_POST['fp']);
      $facturo=trim($_POST['facturo']);
      $obs=trim($_POST['obs']);
      $concept=trim($_POST['concept']);
      $cadimps=trim($_POST['cadimps'],'|');
      $fptext=trim($_POST['fptext']);


      //var_dump($_SESSION['rePr']);
      //ksort($_SESSION['rePr'][][]);


      //var_dump($data);
      //exit();
      //$resultReq = $this->VentasModel->saveOrden($idsProductos,$solicitante,$tipogasto,$moneda,$proveedor,$urgente,$inventariable,$moneda_tc,$fechahoy,$fechaentrega,$total,$option,$idrequi,$almacen,$idactivo);

       if($option==1){
        session_start();
        $resultReq = $this->VentasModel->saveEnvio($_SESSION['v_rePr'],$idOC,$nofactrec,$date_recepcion,$impfactrec,$idsProductos,$activo,$xmlfile,$ist,$it,$fp,$facturo,$obs,$solicitante,$concept,$cadimps,$cliente,$moneda,$moneda_tc,$fptext);
        echo $resultReq;
      }


      if($option==2){
      //$resultReq = $this->VentasModel->modifyRequisicion($idsProductos,$solicitante,$tipogasto,$moneda,$proveedor,$urgente,$inventariable,$moneda_tc,$fechahoy,$fechaentrega,$idrequi,$almacen);

      $resultReq = $this->VentasModel->modifyOrden($idsProductos,$solicitante,$tipogasto,$moneda,$proveedor,$urgente,$inventariable,$moneda_tc,$fechahoy,$fechaentrega,$total,$option,$idrequi,$almacen,$idactivo,$xmlfile);
        echo $resultReq;
      }

    }

    function a_ordenVentaInv(){
      $idsProductos=$_POST['idsProductos'];
      $solicitante=$_POST['solicitante'];
      $tipogasto=$_POST['tipogasto'];
      $moneda=$_POST['moneda'];
      $urgente=$_POST['urgente'];
      $inventariable=$_POST['inventariable'];
      $moneda_tc=$_POST['moneda_tc'];
      $fechahoy=trim($_POST['fechahoy']);
      $fechaentrega=trim($_POST['fechaentrega']);
      $option=trim($_POST['option']);
      $idrequi=trim($_POST['idrequi']);
      //$total=trim($_POST['total']);
      $idactivo=trim($_POST['idactivo']);
      $obs=trim($_POST['obs']);
      $ist=trim($_POST['ist']);
      $it=trim($_POST['it']);
      $cliente=trim($_POST['cliente']);
      $cadimps=trim($_POST['cadimps'],'|');
      $iduserlog=trim($_POST['iduserlog']);

      $total=trim($_POST['total']);
      $monto_desc=trim($_POST['monto_desc']);
      $descc=trim($_POST['descc']);


      $resultReq = $this->VentasModel->ordenVentaInv($idsProductos);
      $t=count($resultReq);
      if($t>0){
        $JSON = array('success' =>1, 'data'=>$resultReq['data'], 'prov'=>$resultReq['prov'], 'ids'=>$resultReq['ids']);
      }else{
        $JSON = array('success' =>0);
      }
        echo json_encode($JSON);

    }

    function a_loki(){
      $idProv=$_POST['idProv'];
      $ids=$_POST['ids'];
      $resultReq = $this->VentasModel->costosCompraExpres($idProv,$ids);

      $JSON = array('success' =>1, 'data'=>$resultReq);
      
      echo json_encode($JSON);
    }

    function a_guardarOrden(){
      $idsProductos=$_POST['idsProductos'];
      $solicitante=$_POST['solicitante'];
      $tipogasto=$_POST['tipogasto'];
      $moneda=$_POST['moneda'];
      $urgente=$_POST['urgente'];
      $inventariable=$_POST['inventariable'];
      $moneda_tc=$_POST['moneda_tc'];
      $fechahoy=trim($_POST['fechahoy']);
      $fechaentrega=trim($_POST['fechaentrega']);
      $option=trim($_POST['option']);
      $idrequi=trim($_POST['idrequi']);
      //$total=trim($_POST['total']);
      $idactivo=trim($_POST['idactivo']);
      $obs=trim($_POST['obs']);
      $ist=trim($_POST['ist']);
      $it=trim($_POST['it']);
      $cliente=trim($_POST['cliente']);
      $cadimps=trim($_POST['cadimps'],'|');
      $iduserlog=trim($_POST['iduserlog']);

      $total=trim($_POST['total']);
      $monto_desc=trim($_POST['monto_desc']);
      $descc=trim($_POST['descc']);




      //$resultReq = $this->VentasModel->saveOrden($idsProductos,$solicitante,$tipogasto,$moneda,$proveedor,$urgente,$inventariable,$moneda_tc,$fechahoy,$fechaentrega,$total,$option,$idrequi,$almacen,$idactivo);

       if($option==1){

      $resultReq = $this->VentasModel->saveOrden($idsProductos,$solicitante,$tipogasto,$moneda,$urgente,$inventariable,$moneda_tc,$fechahoy,$fechaentrega,$total,$option,$idrequi,$idactivo,$obs,$ist,$it,$cadimps,$cliente,$iduserlog,$total,$monto_desc,$descc);
        echo $resultReq;
      }

      if($option==2){
      //$resultReq = $this->VentasModel->modifyRequisicion($idsProductos,$solicitante,$tipogasto,$moneda,$proveedor,$urgente,$inventariable,$moneda_tc,$fechahoy,$fechaentrega,$idrequi,$almacen);

      $resultReq = $this->VentasModel->modifyOrden($idsProductos,$solicitante,$tipogasto,$moneda,$urgente,$inventariable,$moneda_tc,$fechahoy,$fechaentrega,$total,$option,$idrequi,$idactivo,$obs,$ist,$it,$cadimps,$cliente,$iduserlog,$total,$monto_desc,$descc);
        echo $resultReq;
      }

    }

    function a_guardarRequisicionCaja(){
      $idsProductos=$_POST['idsProductos'];
      $solicitante=$_POST['solicitante'];
      $cliente=$_POST['cliente'];
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
      $cadimps=trim($_POST['cadimps']);
      $iduserlog=trim($_POST['iduserlog']);

      $total=trim($_POST['total']);
      $monto_desc=trim($_POST['monto_desc']);
      $descc=trim($_POST['descc']);


      if($option==4){
      $resultReq = $this->VentasModel->saveRequisicionCaja($idsProductos,$solicitante,$tipogasto,$moneda,$proveedor,$urgente,$inventariable,$moneda_tc,$fechahoy,$fechaentrega,$almacen,$obs,$cliente,$ist,$it,$iduserlog,$total,$monto_desc,$descc);
        echo $resultReq;
      }


    }

    function a_guardarRequisicion(){
      $idsProductos=$_POST['idsProductos'];
      $solicitante=$_POST['solicitante'];
      $cliente=$_POST['cliente'];
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
      $cadimps=trim($_POST['cadimps']);
      $iduserlog=trim($_POST['iduserlog']);

      $total=trim($_POST['total']);
      $monto_desc=trim($_POST['monto_desc']);
      $descc=trim($_POST['descc']);


      if($option==1){
      $resultReq = $this->VentasModel->saveRequisicion($idsProductos,$solicitante,$tipogasto,$moneda,$proveedor,$urgente,$inventariable,$moneda_tc,$fechahoy,$fechaentrega,$almacen,$obs,$cliente,$ist,$it,$iduserlog,$total,$monto_desc,$descc);
        echo $resultReq;
      }

      if($option==2){
      $resultReq = $this->VentasModel->modifyRequisicion($idsProductos,$solicitante,$tipogasto,$moneda,$proveedor,$urgente,$inventariable,$moneda_tc,$fechahoy,$fechaentrega,$idrequi,$almacen,$obs,$cliente,$ist,$it,$iduserlog,$total,$monto_desc,$descc);
        echo $resultReq;
      }

    }

    function a_listaRequisiciones(){
      $resultReq =  $this->VentasModel->listaRequisiciones();
      $listas=array();
      $listas['data']='';
      if($resultReq->num_rows>0){
        while ($r = $resultReq->fetch_array()) {
          $link='<a  class="btn btn-default btn-xs btn-block">'.$r['id'].'</span></a>';
          $elimin='<a style="margin-top:4px;" onclick="editReq('.$r['id'].',0);" class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-search"></span> Ver</a>
            <a style="margin-top:4px;" onclick="" class="btn btn-default btn-xs disabled"><span class="glyphicon glyphicon-remove"></span> Borrar</a>';
          if($r['urgente']==0){
            $r[5]='<span class="label label-default" style="cursor:pointer;">Normal</span>';
          }
          if($r['urgente']==1){
            $r[5]='<span class="label label-danger" style="cursor:pointer;">Urgente</span>';
          }
          if($r['activo']==0){
            $elimin='<a style="margin-top:4px;" onclick="editReq('.$r['id'].');"  class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-edit"></span> Editar</a>
            <a style="margin-top:4px;" onclick="eliminaReq('.$r['id'].')" class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-remove"></span> Inactivar </a>';
            $r[6]='<span class="label label-warning" style="cursor:pointer;">Nueva</span>';
          }
          if($r['activo']==1){

            $r[6]='<span class="label label-success" style="cursor:pointer;">OV Autorizada</span>';
          }
          if($r['activo']==2){

            $link='<a href="#" class="btn btn-default btn-xs disabled btn-block">'.$r['id'].'</a>';
            $r[6]='<span class="label label-default" style="cursor:pointer;">Inactiva</span>';
          }
          if($r['activo']==3){

            $link='<a href="#" class="btn btn-default btn-xs disabled btn-block">'.$r['id'].'</a>';
            $r[6]='<span class="label label-success" style="cursor:pointer;">OV activa</span>';
            $elimin='<a style="margin-top:4px;" onclick="editReq('.$r['id'].',0);" class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-search"></span> Ver</a>
            <a style="margin-top:4px;" onclick="" class="btn btn-default btn-xs disabled"><span class="glyphicon glyphicon-remove"></span> Borrar.</a>';
          }

          if($r['activo']==4){

            $link='<a href="#" class="btn btn-default btn-xs disabled btn-block">'.$r['id'].'</a>';
            $r[6]='<span class="label label-success" style="cursor:pointer;">OK recibida ok</span>';
            $elimin='<a style="margin-top:4px;" onclick="editReq('.$r['id'].',0);" class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-search"></span> Ver</a>
            <a style="margin-top:4px;" onclick="" class="btn btn-default btn-xs disabled"><span class="glyphicon glyphicon-remove"></span> Borrar.</a>';
          }

          if($r['aceptada']==1){
            $r[6].=' <span class="label label-success" style="cursor:pointer;">Aceptada por cliente</span>';
          }

          
          

          $r[7]=$elimin;
          if($r['cadenaCoti']!=null){
            $nuevos='';
            if($r['cnuevos']>0){
              $nuevos='('.$r['cnuevos'].')';
            }
            $r[7].=' <button style="margin-top:4px;"  onclick="vercomcli(\''.$r['cadenaCoti'].'\');" class="btn btn-default btn-xs">Comentarios '.$nuevos.'</button>';
          }

          if($r['activo']==6){

            $link='<a href="#" class="btn btn-default btn-xs disabled btn-block">'.$r['id'].'</a>';

            if($r['status']==1){
              $r[6]='<span class="label label-info" style="cursor:pointer;">Venta en Caja (PMP'.$r['idcotpe'].')</span>';
            }else if($r['status']==5){
              $r[6]='<span class="label label-success" style="cursor:pointer;">Venta realizada en caja</span>';
            }
            $elimin='<a style="margin-top:4px;" onclick="editReq('.$r['id'].',0);" class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-search"></span> Ver</a>
            <a style="margin-top:4px;" onclick="" class="btn btn-default btn-xs disabled"><span class="glyphicon glyphicon-remove"></span> Borrar.</a>';
            
          }else{
          $r[7].=' <button style="margin-top:4px;" id="btn_imprimir_'.$r['id'].'_" onclick="imprimir2('.$r['id'].',2);" class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-print"></span> </button>';
        }


          $listas['data'][]=$r;
        }
      }else{
        $listas['data']=array();
      }

      echo json_encode($listas);
  

    }

    function a_devolucionCliente(){
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
      $id_rec=trim($_POST['id_rec']);
      $cliente=trim($_POST['cliente']);
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




        session_start();
        $resultReq = $this->VentasModel->saveDevoCliente($_SESSION['rePr'],$idOC,$nofactrec,$date_recepcion,$impfactrec,$idsProductos,$activo,$xmlfile,$desc_concepto,$proveedor,$inventariable,$ist,$it,$date_recep,$esconsig,$id_rec,$cliente);
        echo $resultReq;
      


    }


    function a_modalRecepcion(){
      $idProd=$_POST['idProd'];
      $modo=$_POST['modo'];
      $cadcar=$_POST['cadcar'];
      $cantrecibid=$_POST['modalcantrecibida'];
      $ss=$_POST['ss'];

      if(isset($_POST['sinex']) && $_POST['sinex']==1){
        //$_SESSION['v_rePr'][$modo][$idProd][$cadcar]=array('cantrecibid' => $cantrecibid, 'existencias' => $existencias, 'cantsexistencias'=>$cantsexistencias);
      }
      

      session_start();
      if($modo==0){
        $existencias=$_POST['existencias'];
        $cantsexistencias=$_POST['cantsexistencias'];
        if($ss==0){
          $existencias_imp=implode(',', $existencias);
        }else{
          $existencias_imp=$existencias;
        }
        $_SESSION['v_rePr'][$modo][$idProd][$cadcar]=array('cantrecibid' => $cantrecibid, 'existencias' => $existencias, 'cantsexistencias'=>$cantsexistencias);
        echo $modo.'->-'.$idProd.'->-'.$cantrecibid.'->-'.$existencias_imp.'->-'.$cantsexistencias.'->-'.$ss;
      }
      if($modo==1){
        $lotes=$_POST['lotes'];
        $cantslotes=$_POST['cantslotes'];
        $lotes_imp=implode(',', $lotes);
        $_SESSION['v_rePr'][$modo][$idProd][$cadcar]=array('cantrecibid' => $cantrecibid, 'lotes' => $lotes, 'cantslotes'=>$cantslotes);
        echo $modo.'->-'.$idProd.'->-'.$cantrecibid.'->-'.$lotes_imp.'->-'.$cantslotes;
      }
      if($modo==2){
        $series=$_POST['series'];
        $series_imp=implode(',', $series);
        $_SESSION['v_rePr'][$modo][$idProd][$cadcar]=array('cantrecibid' => $cantrecibid, 'series' => $series);

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




        $_SESSION['v_rePr'][$modo][$idProd][$cadcar]=array('cantrecibid' => $cantrecibid, 'pedimentos' => $pedimentos, 'series' => $series);
        echo $modo.'->-'.$idProd.'->-'.$cantrecibid.'->-'.$pedimentos_imp.'->-'.$series_imp;
      }

      if($modo==4){
        $pedimentos=$_POST['pedimentos'];
        $cantspedimentos=$_POST['cantspedimentos'];
        $pedimentos_imp=implode(',', $pedimentos);
        $_SESSION['v_rePr'][$modo][$idProd][$cadcar]=array('cantrecibid' => $cantrecibid, 'pedimentos' => $pedimentos, 'cantspedimentos' => $cantspedimentos);
        echo $modo.'->-'.$idProd.'->-'.$cantrecibid.'->-'.$pedimentos_imp.'->-'.$cantspedimentos;
      }

      if($modo==5){

        $pedimentoslote=$_POST['pedimentoslote'];
        $cantspedimentos=$_POST['cantspedimentos'];
        $pedimentos_imp=implode(',', $pedimentoslote);
        $_SESSION['v_rePr'][$modo][$idProd][$cadcar]=array('cantrecibid' => $cantrecibid, 'pedimentos' => $pedimentoslote, 'cantspedimentos' => $cantspedimentos);
        echo $modo.'->-'.$idProd.'->-'.$cantrecibid.'->-'.$pedimentos_imp.'->-'.$cantspedimentos;

      }
      
      //var_dump($_SESSION['rePr']);

    }


    function a_listaRequisicionesCompra(){
      $resultReq =  $this->VentasModel->listaRequisicionesCompra();
      $listas=array();
      $listas['data']='';
      if($resultReq->num_rows>0){
        while ($r = $resultReq->fetch_array()) {
          $link='<a  class="btn btn-default btn-xs btn-block">'.$r['id'].'</span></a>';
           $elimin='<a onclick="editReq('.$r['id'].',0);"  class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-search"></span> Ver</a>
            <a onclick="" class="btn btn-default btn-xs disabled"><span class="glyphicon glyphicon-remove"></span> Borrar</a>';
          if($r['urgente']==0){
            $r[6]='<span class="label label-default" style="cursor:pointer;">Normal</span>';
          }
          if($r['urgente']==1){
            $r[6]='<span class="label label-danger" style="cursor:pointer;">Urgente</span>';
          }
          if($r['activo']==0 || $r['activo']==3){
            $elimin='<a onclick="editReq('.$r['id'].',1);"  class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-edit"></span> Editar</a>
            <a onclick="eliminaReq('.$r['id'].')" class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-remove"></span> Borrar</a>';
            $r[7]='<span class="label label-warning" style="cursor:pointer;">Inactiva</span>';
          }
          if($r['activo']==1){

            $r[7]='<span class="label label-success" style="cursor:pointer;">Autorizada</span>';
          }
          if($r['activo']==2){

            $link='<a href="#" class="btn btn-default btn-xs disabled btn-block">'.$r['id'].'</a>';
            $r[7]='<span class="label label-default" style="cursor:pointer;">Cancelada</span>';
          }

          if($r['activo']==4){

            $link='<a href="#" class="btn btn-default btn-xs disabled btn-block">'.$r['id'].'</a>';
            $r[7]='<span class="label label-success" style="cursor:pointer;">Autorizada</span>';
            $elimin='<a onclick="editReq('.$r['id'].',0);"  class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-search"></span> Ver</a>
            <a onclick="" class="btn btn-default btn-xs disabled"><span class="glyphicon glyphicon-remove"></span> Borrar</a>';
          }


          $r[8]=$elimin;
          $r[8].=' <button id="btn_imprimir_'.$r['id'].'_" onclick="imprimir2('.$r['id'].',2);" class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-print"></span> </button>';
          $listas['data'][]=$r;
        }
      }else{
        $listas['data']=array();
      }

      echo json_encode($listas);
  

    }

}


?>
