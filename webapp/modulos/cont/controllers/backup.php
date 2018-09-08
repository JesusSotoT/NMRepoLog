<?php
require('common.php');//Carga la funciones comunes top y footer
require("models/backup.php");

class Backup extends Common
{
	public $BackupModel;

	function __construct(){
		$this->BackupModel = new BackupModel();
		$this->BackupModel->connect();
	}

	function __destruct()
	{
		//Se destruye el objeto que instancia al modelo que se va a utilizar
		$this->BackupModel->close();
	}

  function backup_tables() {
    // obtenemos el DateTime actual
    date_default_timezone_set('America/Mexico_city');
    // damos formato a la fecha
    $date = date('m-d-Y H:i:s');
    // generamos el nombre para el archivo zip
    $zipname = $date.' respaldo';
    // le asignamos el directorio
    $zipPath = "importar/".$zipname.".zip";
    //guardamos el link del archivo zip
    $zipDownload = "<input type='hidden' id='zipPath' value='".$zipPath."'>";
    // creamos un nuevo archivo zip
    $zip = new ZipArchive;
    // abrimos el achivo zip
    $zip->open($zipPath, ZipArchive::CREATE);

    $tables = [
    "cont_account_status",
    "cont_accounts",
    "cont_bancos",
    "cont_bancosPrv",
    "cont_clasificacion_nif",
    "cont_classification",
    "cont_coin",
    "cont_config",
    "cont_diarioficial",
    "cont_ejercicios",
    "cont_grupo_facturas",
    "cont_IETU",
    "cont_ivasretenidos",
    "cont_main_type",
    "cont_movimientos",
    "cont_nature",
    "cont_nodeducible",
    "cont_polizas",
    "cont_presupuestos",
    "cont_rel_desglose_iva",
    "cont_rel_pol_prov",
    "cont_relacion_ter_oper",
    "cont_resumen_ivas_retenidos",
    "cont_segmentos",
    "cont_sucursales",
    "cont_tasaPrv",
    "cont_tasas",
    "cont_tipo_cambio",
    "cont_tipo_iva",
    "cont_tipo_operacion",
    "cont_tipo_tercero",
    "cont_tipos_poliza",
    "bco_clasificador",
    "bco_complementos",
    "bco_conceptos",
    "bco_configuracion",
    "bco_controlNumeroCheque",
    "bco_cuentas_bancarias",
    "bco_devoluciones",
    "bco_documentos",
    "bco_documentoSubcategorias",
    "bco_impuestos_retencion",
    "bco_ingresos_depositos",
    "bco_nivelClasificador",
    "bco_paises",
    "bco_pendiente_timbrar",
    "bco_saldo_bancario",
    "bco_saldos_conciliacion",
    "bco_saldos_conciliacionBancos",
    "bco_status",
    "bco_sucursalBancaria",
    "bco_tipo_contribuyente",
    "bco_tipo_cuenta",
    "bco_tipo_dividendo",
    "bco_tipo_documentos",
    "bco_tipoClasificador",
    "bco_tiposDocumentoConcepto",
    "comun_cliente",
    "mrp_proveedor",
    "mrp_sucursal",
    ];

    // Recorre el array que almacena las tablas y genera un CSV por cada una
    foreach ($tables as $table) {
      //Validamos que la tabla exista
      $checkTable = $this->BackupModel->checkTable($table);
      $checkTableVal = $checkTable->fetch_row();
      if ($table == $checkTableVal[0]) {
        // Reiniciamos la variable que almacena los headers
        $headers = '';
        // asignamos el nombre del archivo
        $filename = $date.' '.$table.'.csv';
        // asignamos el directorio al archivo
        $filepath = 'importar/'.$filename;
        // abrimos el csv archivo para escribir en el
        $file = fopen($filepath, 'w');
        // obtenemos las cabeceras de la tabla
        $tableHeaders = $this->BackupModel->getTableHeaders($table);
        while ($row = $tableHeaders->fetch_row()) {
          $headers[] = $row[0];
        }
        // guardamos las cabeceras de la tabla
        fputcsv($file, $headers);

        // obtenemos los registros de la tabla
        $data = $this->BackupModel->getAllTable($table);
        // guardamos cada registro en el archivo
        foreach ($data as $row) {
          fputcsv($file, $row);
        }
        // Cerramos el archivo
        fclose($file);
        // Guardamos las tablas que si se generaron como csv para indicarle al usuario.
        $tablas .= $table.", ";
      } else {
        $noexiste .= $table.", ";
      }
      $zip->addFile($filepath, $filename);
      // notificamos que se ha creado el archivo
      $unlinked_files[] = $filepath;
    }
    $tablas .= "además de adjuntar su carpeta de facturas. ";

    // **** - **** - *** - Añadir facturas al zip - *** - **** - ****
    //Designamos el directorio que se que va añadir al zip
    
    $roothpath = realpath($this->path().'xmls/facturas/');
    //Obtenemos los archivos
    $files = new RecursiveIteratorIterator( 
      new RecursiveDirectoryIterator($roothpath),
      RecursiveIteratorIterator::LEAVES_ONLY);

    foreach ($files as $file) {
      //Omitir directorios (se añadiran automaticamente)
      if (!$file->isDir()) {
        //Obtenemos el path real del archivo actual
        $filepath = $file->getRealPath();
        $relativepath = substr($filepath, strlen($roothpath)+1);
      }
      //Añadimos el archivo al zip
      $zip->addFile($filepath, $relativepath);
    }

    // Cerramos el archivo zip
    $zip->close();
    header("Content-Description: Descargar ZIP");
    header("Content-Disposition: attachment; filename=".$zipPath.".zip");
    header("Content-Type: application/force-download");
    header("Content-Transfer-Encoding: binary");
    foreach ($unlinked_files as $csv) {
      unlink($csv);
    }
    echo("Se ha creado un respaldo de las tablas: ".$tablas);
    if (isset($noexiste)) {
      echo(" La(s) tabla(s) no se encontraron: ".$noexiste);
    }
    echo($zipDownload);
  }

  function descargarXMLS() {
    // obtenemos el DateTime actual
    date_default_timezone_set('America/Mexico_city');
    // damos formato a la fecha
    $date = date('m-d-Y_H-i-s');
    // generamos el nombre para el archivo zip
    $zipname = 'facturas'.$date;
    // le asignamos el directorio
    $zipPath = "importar/".$zipname.".zip";
    //guardamos el link del archivo zip
    $data['download'] = "<input type='hidden' id='zipPathXML' value='".$zipPath."'>";
    // creamos un nuevo archivo zip
    $zip = new ZipArchive;
    // abrimos el achivo zip
    $zip->open($zipPath, ZipArchive::CREATE);
    $xmls = $_POST['xmls'];

    foreach ($xmls as $xmlFile) {
      //Reiniciamos la ruta
      $filepath = '';
      $XML = explode("/", $xmlFile);
      for ($i=0; $i <= 2; $i++) { 
        if ($i == 0) {
          $filepath .= $XML[$i]; 
        } else {
          $filepath .= "/".$XML[$i];
        }
      }
      //Añadimos un ultimo separador
      $basenameXML = $XML[3];
      $filepath.= "/".$basenameXML;
      // añadimos archivo al rar
      $data[] = "Path: ".$filepath." || basename: ".$basenameXML;
      $zip->addFile($filepath, $basenameXML);
    }
    // Cerramos el archivo zip
    $zip->close();
    echo json_encode($data);
  }

  function borrarZip(){
    $link = $_POST['link'];
    unlink($link);
  }

  function Eliminarxmls() {
    $lista_uuid = array();
    //Recorremos el arreglo que contiene los xml
    foreach ($_POST['xmls'] as $xml => $valor) {
      //Obtenemos los fragmentos de la ruta
      $ruta = explode("/",$valor);
      //Si se encuentra dentro de la carpeta de temporales...
      if ($ruta[2] == "temporales") {
        //Obtenemos el uuid
        $nombre_xml = explode("_",$ruta[3]);
        //Removemos la extension del archivo
        $uuid = preg_replace('/\\.[^.\\s]{3,4}$/', '', $nombre_xml[2]);
        array_push($lista_uuid, $uuid);
        unlink($valor);
      }
    }
    $result = $this->BackupModel->esconder_facturas($lista_uuid);
    echo($result);
  }

}