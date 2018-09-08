<?php
//ini_set('display_errors', 1);
//Carga la funciones comunes top y footer
require('common.php');

//Carga el modelo para este controlador
require("models/proveedores.php");

class Portalproveedores extends Common
{
    public $ProveedoresModel;

    function __construct()
    {
        //Se crea el objeto que instancia al modelo que se va a utilizar

        $this->ProveedoresModel = new ProveedoresModel();
        $this->ProveedoresModel->connect();
    }

    function __destruct()
    {
        //Se destruye el objeto que instancia al modelo que se va a utilizar
        $this->ProveedoresModel->close();
    } 


    function a_adjuntarxml(){
      $idoc=$_POST['idoc'];
      $resultReq =  $this->ProveedoresModel->listaRecepcionesAdju($idoc);
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
      $resultReq =  $this->ProveedoresModel->listaXmlsCompra($idoc);
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
          
                  $rfcOrganizacion= $this->ProveedoresModel->rfcOrganizacion();
                 
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


    function a_listaOrdenesRecepcion(){
      $idProveedor=$_GET['id'];
      $resultReq =  $this->ProveedoresModel->listaOrdenesCompra($idProveedor);
      $listas=array();
      $listas['data']='';
      if($resultReq->num_rows>0){
        while ($r = $resultReq->fetch_array()) {
          $link='<a  class="btn btn-default btn-xs btn-block">'.$r['idoc'].'</span></a>';
          $elimin='-R-';
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
            $elimin='<a style="margin-top:4px" onclick="adjuntarxml('.$r['idoc'].');"  class="btn btn-default btn-xs"><span class="glyphicon glyphicon-upload"></span> Adjuntar factura\'s</a>';
          }

          if($r['activo']==5){

            $link='<a href="#" class="btn btn-default btn-xs disabled btn-block">'.$r['idoc'].'</a>';
            $r[8]='<span class="label label-success" style="cursor:pointer;">Recepcion Parcial</span>';
            $elimin='<a style="margin-top:4px" onclick="adjuntarxml('.$r['idoc'].');"  class="btn btn-default btn-xs"><span class="glyphicon glyphicon-upload"></span> Adjuntar factura\'s</a>';
          }

          if($r['activo']==6){

            $link='<a href="#" class="btn btn-default btn-xs disabled btn-block">'.$r['idoc'].'</a>';
            $r[8]='<span class="label label-warning" style="cursor:pointer;">Pendiente aclaracion</span>';
            $elimin='-r-';
          }



          $r[9]=$elimin;
          $listas['data'][]=$r;
        }
      }else{
        $listas['data']=array();
      }

      echo json_encode($listas);
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


      $resu = $this->ProveedoresModel->guardaXmlAdju($fac_folio,$fac_fecha,$fac_total,$fac_uuid,$fac_concepto,$xmlfile,$idoc,$fac_subtotal);
      echo $resu;
    }

    function listaCargosFacturas()
    {
        $datos = array();
        $_POST['cobrar_pagar']=0;
        $listaCargos = $this->ClienteModel->listaCargos($_POST['idPrvCli'],$_POST['cobrar_pagar']);
        while($l = $listaCargos->fetch_assoc())
        {
            $vencimiento = new DateTime($l['fecha_pago']);
            if(intval($l['diascredito']))
                $vencimiento->add(new DateInterval('P'.$l['diascredito'].'D'));

            $abonado = (floatval($l['cargo']) * floatval($l['tipo_cambio'])) - floatval($l['saldo']);
            
            $datetime1 = new DateTime(date('Y-m-d'));
                $datetime2 = $vencimiento;
                $interval = $datetime1->diff($datetime2);
                $difer = $interval->format('%R%a');

                if(intval($difer) >= 61)//Al corriente
                    $estatus_m = "<center><span style='display:block !important;width:100% !important;' class='label label-success'>Al Corriente</span></center>";

                if(intval($difer) <= 60 && intval($difer) >= 0)//por vencer
                    $estatus_m = "<center><span style='display:block !important;width:100% !important;' class='label label-warning'>Por vencer</span></center>";

                if(intval($difer) < 0)//vencido
                    $estatus_m = "<center><span style='display:block !important;width:100% !important;' class='label label-danger'>Cuenta Vencida</span></center>";     

                if(number_format($l['saldo'],2) <= 0)//saldada
                    $estatus_m = "<center><span style='display:block !important;width:100% !important;' class='label label-default'>Cuenta Saldada</span></center>";                            

            array_push($datos,array(
                        'fech_cargo' => $l['fecha_pago'],
                        'fecha_venc' => $vencimiento->format('Y-m-d'),
                        'concepto' => $l['concepto'],
                        'monto' => "$ ".number_format($l['cargo'],2)." ".$l['moneda'],
                        'abonado' => "$ ".number_format($abonado,2)." MXN",
                        'actual' => "<span class='actual' cantidad='".$l['saldo']."'>$ ".number_format($l['saldo'],2)." MXN</span>",
                        'estatus' => $estatus_m,
                        'ov' => '-'
                            ));
        }

        $listaFacturas = $this->ClienteModel->listaFacturas($_POST['idPrvCli'],$_POST['cobrar_pagar']);
        while($l = $listaFacturas->fetch_assoc())
        {
            //$foliosFac = $this->CuentasModel->foliosFac($l['id_oc']);
            //$file     = "../cont/xmls/facturas/temporales/".$l['xmlfile'];
            //$texto    = file_get_contents($file);
            //$texto    = preg_replace('{<Addenda.*/Addenda>}is', '<Addenda/>', $texto);
            //$texto    = preg_replace('{<cfdi:Addenda.*/cfdi:Addenda>}is', '<cfdi:Addenda/>', $texto);
            //$xml  = new DOMDocument();
            //$xml->loadXML($texto);
            //$xp = new DOMXpath($xml);
            //$desc = $this->getpath("//@descripcion");
            $vencimiento = new DateTime($l['fecha_factura']);
            if(intval($l['diascredito']))
                $vencimiento->add(new DateInterval('P'.$l['diascredito'].'D'));
            $desc = $l['desc_concepto'];
            $datetime1 = new DateTime(date('Y-m-d'));
                $datetime2 = $vencimiento;
                $interval = $datetime1->diff($datetime2);
                $difer = $interval->format('%R%a');

                if(intval($difer) >= 61)//Al corriente
                    $estatus_m = "<center><span style='display:block !important;width:100% !important;' class='label label-success'>Al Corriente</span></center>";

                if(intval($difer) <= 60 && intval($difer) >= 0)//por vencer
                    $estatus_m = "<center><span style='display:block !important;width:100% !important;' class='label label-warning'>Por vencer</span></center>";

                if(intval($difer) < 0)//vencido
                    $estatus_m = "<center><span style='display:block !important;width:100% !important;' class='label label-danger'>Cuenta Vencida</span></center>";


            $estilo = '';
            if(strtotime($vencimiento->format('Y-m-d')) < strtotime(date()))
                $estilo = "style='color:red;'";


                $nuevoImp = floatval($l['importe_pesos']);


            $saldo = $nuevoImp - floatval($l['pagos']);
            if(number_format($saldo,2) <= 0)//saldada
                    $estatus_m = "<center><span style='display:block !important;width:100% !important;' class='label label-default'>Cuenta Saldada</span></center>";        
                                                            
            

                $abonado = floatval($nuevoImp) - floatval($saldo);
                
                if(intval($_POST['cobrar_pagar']))
                {
                    $url = "index.php?c=compras&f=ordenes&id_oc=".$l['id_oc']."&v=1";
                    $idovc = $l['id_oc'];
                }
                else
                {
                    if(intval($l['origen']) == 1)
                        $url = "index.php?c=ventas&f=ordenes&id_oventa=".$l['id_oventa']."&v=1";
                    if(intval($l['origen']) == 2)
                        $url = "../pos/ticket.php?idventa=".$l['id_oventa']."&print=0";
                    $idovc = $l['id_oventa'];
                }
                    
                        
                array_push($datos,array(
                            'fech_cargo' => $l['fecha_factura'],
                            'fecha_venc' => $vencimiento->format('Y-m-d'),
                            'concepto' => $l['folio']." $desc",
                            'monto' => "$ ".number_format($l['imp_factura'],2)." ".$l['Moneda'],
                            'abonado' => "$ ".number_format($abonado,2)." MXN",
                            'actual' => "<span class='actual' cantidad='$saldo'>$ ".number_format(round($saldo,2),2)." MXN</span>",
                            'estatus' => $estatus_m,
                            'ov' => "<a href='$url' target='_blank'>$idovc</a>"
                                ));
            
        }
        echo json_encode($datos);
    }

    function guardaCliente(){
         $idCliente = $_POST['idCliente'];
         //$codigo = $_POST['codigo'];
         $nombre = $_POST['nombre'];
         $tienda = $_POST['tienda'];
         $numint = $_POST['numint']; 
         $numext = $_POST['numext'];
         $direccion = $_POST['direccion'];
         $colonia = $_POST['colonia']; 
         $cp = $_POST['cp'];
         $pais = $_POST['pais'];
         $estado = $_POST['estado'];  
         $municipio = $_POST['municipio'];
         $email = $_POST['email'];
         $celular = $_POST['celular'];
         $tel1 =  $_POST['tel1'];
         $tel2 = $_POST['tel2'];
         $ciudad = $_POST['ciudad'];

         // $cumpleanos = $_POST['cumpleanos'];
         // $rfc = $_POST['rfc'];
         // $curp = $_POST['curp'];
         // $diasCredito = $_POST['diasCredito'] ;
         // $limiteCredito = $_POST['limiteCredito'];
         // $moneda = $_POST['moneda'];
         // $listaPrecio = $_POST['listaPrecio'];
         // $regimenFact = $_POST['regimenFact'];


         // $idComunFact = $_POST['idComunFact'];
         // $razonSocial = $_POST['razonSocial'];
         // $emailFacturacion = $_POST['emailFacturacion'];
         // $direccionFact = $_POST['direccionFact'];
         // $numextFact = $_POST['numextFact'];
         // $numintFact = $_POST['numintFact'];
         // $coloniaFact = $_POST['coloniaFact'];
         // $cpFact = $_POST['cpFact'];
         // $paisFact = $_POST['paisFact'];
         // $estadoFact = $_POST['estadoFact'];
         // $municipiosFact = $_POST['municipiosFact'];
         // $ciudadFact = $_POST['ciudadFact'];
         // $tipoDeCredito = $_POST['tipoDeCredito'];
         // $descuentoPP = $_POST['descuentoPP'];
         // $interesesMoratorios = $_POST['interesesMoratorios'];
         // $perVenCre = $_POST['perVenCre'];
         // $perExLim = $_POST['perExLim'];
         // $comisionVenta = $_POST['comisionVenta'];
         // $comisionCobranza = $_POST['comisionCobranza'];
         // $empleado = $_POST['empleado'];
         // $enviosDom = $_POST['enviosDom'];
         // $tipoClas = $_POST['tipoClas'];

         // $banco = $_POST['banco'];
         // $numCuenta = $_POST['numCuenta'];
         // $cuentaCont = $_POST['cuentaCont'];

         // $bandera = $_POST['flag'];


          
            $cliente = $this->ClienteModel->updateClientePortal($idCliente,$nombre,$tienda,$numint,$numext,$direccion,$colonia,$cp,$estado,$municipio,$email,$celular,$tel1,$tel2,$ciudad,$pais); 
         

        

        echo json_encode($cliente);
    }
 
    function index()
    {   
     

        session_start();
        $user= $_SESSION["accelog_login"];
        $expuser= explode('_', $user);
        $idProveedor=$expuser[1];
        /*$paises = $this->ClienteModel->paises();
        $estados = $this->ClienteModel->estados();
        $municipiosFc = $this->ClienteModel->munici();
        $listaPre = $this->ClienteModel->listaPrecios();
        $moneda = $this->ClienteModel->moneda();
        $tipoCredito = $this->ClienteModel->creditos();
        $clasificadores = $this->ClienteModel->clasificadoresTipos(0);
        $empleados = $this->ClienteModel->obtenEmple();
        $bancos = $this->ClienteModel->bancos();
        $cuentas = $this->ClienteModel->cuentas();*/

        if($idProveedor!=''){

            $datosCliente = $this->ProveedoresModel->datosProveedor($idProveedor);
            
            //$datosClienteFact = $this->ClienteModel->datosClienteFact($idCliente);
            //$id_claisf = $datosCliente['basicos'][0]['id_clasificacion'];
            //$clasificadores = $this->ClienteModel->clasificadoresTipos($id_claisf);
        } 


        //$almacenes = $this->ClienteModel->almacenes();


        require('views/proveedores/proveedorFormPortal.php');
    }
}
        // $idCliente = $_GET['idCliente'];

        // $paises = $this->ClienteModel->paises();
        // $estados = $this->ClienteModel->estados();
        // $municipiosFc = $this->ClienteModel->munici();
        // $listaPre = $this->ClienteModel->listaPrecios();
        // $moneda = $this->ClienteModel->moneda();
        // $tipoCredito = $this->ClienteModel->creditos();
        // $clasificadores = $this->ClienteModel->clasificadoresTipos(0);
        // $empleados = $this->ClienteModel->obtenEmple();
        // $bancos = $this->ClienteModel->bancos();
        // $cuentas = $this->ClienteModel->cuentas();
        // $almacenes = $this->ClienteModel->almacenes(); /*
        /*foreach ($proveedores as $key => $value) {
         echo ''.$value['razon_social'].'<br>';
        } 
        if($idCliente!=''){
            $datosCliente = $this->ClienteModel->datosCliente($idCliente);
            $datosClienteFact = $this->ClienteModel->datosClienteFact($idCliente);
            $id_claisf = $datosCliente['basicos'][0]['id_clasificacion'];
            $clasificadores = $this->ClienteModel->clasificadoresTipos($id_claisf);
        } 
        //print_r($datosCliente);
        require('views/cliente/clienteForm.php');
    }

    

}


?>
