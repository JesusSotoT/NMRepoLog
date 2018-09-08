<?php
include_once("../../netwarelog/webconfig.php");
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
              $archiv = quitar_tildes($archivo."");
              $nombreArchiv= quitar_tildes($nombreArchivo);
              
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
        case "3.3":

          $ok = $xml->schemaValidate("../cont/xmls/valida_xmls/xsds/cfdv33.xsd");
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
                $data['uuid']   = getpath("//@UUID");
                //COMIENZA VERSION---------------------------------------
                if(getpath("//@version"))
                          $data['version'] = getpath("//@version");
                        else
                          $data['version'] = getpath("//@Version");

                $version = $data['version'];
                //TERMINA VERSION---------------------------------------

                if($version[0] == '3.3')
                {
                  //$data['uuid']   = $data['uuid'][1];
                  if(is_array($data['uuid'])){
                    $data['uuid']   = $data['uuid'][1];
                  }
                  $data['folio']  = getpath("//@Folio");
                  $data['emisor'] = getpath("//@Nombre");
                  $data['total'] = getpath("//@Total");
                  $data['rfc'] = getpath("//@Rfc");

                  $data['calle'] = getpath("//@Calle");
                  $data['noExt'] = getpath("//@NoExterior");
                  $data['colonia'] = getpath("//@Colonia");
                  $data['municipio'] = getpath("//@Municipio");
                  $data['estado'] = getpath("//@Estado");
                  $data['cp'] = getpath("//@CodigoPostal");

                  $data['FechaPago'] = getpath("//@FechaPago");
                  $data['NumEmpleado'] = getpath("//@NumEmpleado");
                  $subtotal = getpath("//@SubTotal");
                  /*if(!$totalImpuestosTrasladados = $this->getpath("//@TotalImpuestosTrasladados"))
                        $totalImpuestosTrasladados = 0;
                      if(!$totalRetenciones = $this->getpath("//@TotalImpuestosRetenidos"))
                        if(!$totalRetenciones = $this->getpath("//@MontoTotOperacion"))
                          $totalRetenciones = 0;

                      if(!$descuento_f = $this->getpath("//@Descuento"))
                        $descuento_f = 0;*/
                }
                else
                {
                  $data['folio']  = getpath("//@folio");
                  $data['emisor'] = getpath("//@nombre");
                  $data['total'] = getpath("//@total");
                  $data['rfc'] = getpath("//@rfc");

                  $data['calle'] = getpath("//@calle");
                  $data['noExt'] = getpath("//@noExterior");
                  $data['colonia'] = getpath("//@colonia");
                  $data['municipio'] = getpath("//@municipio");
                  $data['estado'] = getpath("//@estado");
                  $data['cp'] = getpath("//@codigoPostal");

                  $data['FechaPago'] = getpath("//@FechaPago");
                  $data['NumEmpleado'] = getpath("//@NumEmpleado");
                  $subtotal = getpath("//@subTotal");
                  /*if(!$totalImpuestosTrasladados = $this->getpath("//@totalImpuestosTrasladados"))
                        $totalImpuestosTrasladados = 0;
                      if(!$totalRetenciones = $this->getpath("//@totalImpuestosRetenidos"))
                        if(!$totalRetenciones = $this->getpath("//@montoTotOperacion"))
                          $totalRetenciones = 0;

                      if(!$descuento_f = $this->getpath("//@descuento"))
                        $descuento_f= 0;*/
                }
/*
                  $data['uuid']   = getpath("//@UUID");
                  $data['folio']  = getpath("//@folio");
                  $data['emisor'] = getpath("//@nombre");
                  $data['version'] = getpath("//@version");
                  $data['fecha'] = getpath("//@FechaTimbrado");
                  $data['descripcion'] = getpath("//@descripcion");


                      $version = $data['version'];

                  
                  $data['total'] = getpath("//@total");
                  $data['subTotal'] = getpath("//@subtotal");
                  $data['rfc'] = getpath("//@rfc");
                  */
                  
                  
                  $tipo = explode('.',$archivo);
                  //Termina obtener UUID---------------------------
          
                  //$this->ComprasModel->rfcOrganizacion();

                  
                  $db = mysql_connect($servidor, $usuariobd, $clavebd)
                  or die("Connection Error: " . mysql_error());
                  mysql_select_db($bd) or die("Error conecting to db.");
                  mysql_query("set names 'utf8'");

                  $SQL = "SELECT RFC from organizaciones ";
                  $result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());


                  if(mysql_num_rows($result)>0){
                    $row = mysql_fetch_assoc($result);

                    $rfcOrganizacion = $row['RFC'];
                  }
                 
                  if($data['rfc'][0] == $rfcOrganizacion)
                  {
                    $nombre = $data['emisor'][1];
                  }
                  elseif($data['rfc'][1] == $rfcOrganizacion)
                  {
                    $nombre = $data['emisor'][0];
                    
                  }

                  if(valida_xsd($version[0],$xml) && strtolower($tipo[1]) == "xml")
                  {

                    if($rfcOrganizacion != $data['rfc'][0] &&  $rfcOrganizacion!= $data['rfc'][1])
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
                      $validaexiste = existeXML($nombreArchivo);
                      $repetidos=0;
                      // if($validaexiste){

                      //   $numeroInvalidos++;
                      //   $noOrganizacion=0;
                      //   $facturasNoValidas .= $archivo." Ya existe en $validaexiste.\n";
                      //    $repetidos=1;
                      //    mkdir ($almacen,0777);
                      //   rename($file, $almacen.quitar_tildes($nombreArchivo));

                      // }else{ 
                        $noOrganizacion = 1; 
                        //}
                    }

                    if($noOrganizacion)
                    {
                      copy($ruta."ziptempo/$foldername/".$archivo,$ruta."/".quitar_tildes($nombreArchivo));
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


        
        $data['descripcion']=addslashes($data['descripcion']);
        $datosfactura=$data['folio'].'##'.$data['fecha'].'##'.$data['total'].'##'.$data['uuid'].'##'.$data['descripcion'].'##'.$data['subTotal'];

        echo $funciono."-/-*".$numeroValidos."-/-*".$facturasValidas."-/-*".$numeroInvalidos."-/-*".$facturasNoValidas."-/-*".$repetidos."-/-*".$nombreArchivo."-/-*".$datosfactura;


    

?>