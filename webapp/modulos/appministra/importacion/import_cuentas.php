Cargando....
<?php
ini_set("display_errors",0);
ini_set('memory_limit', '-1');
//error_reporting(E_WARNING);

require_once '../../libraries/Excel/reader.php';

$data = new Spreadsheet_Excel_Reader();
$data->setOutputEncoding('CP1251');
$data->read(dirname(__FILE__).'/cuentas'.$_GET['t'].'_temp.xls');

$dato = array();

//Cuentas
$tipo = "cuentasxcobrar";
if(intval($_GET['t']))
    $tipo = "cuentasxpagar"; 
$sigue = 1;
for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++) 
{

    $dato[1] = trim($data->sheets[0]["cells"][$i][1]); //id cliente/proveedor
    $dato[2] = trim($data->sheets[0]["cells"][$i][2]); //saldo
    $dato[3] = trim($data->sheets[0]["cells"][$i][3]); //fecha factura
    $dato[4] = trim($data->sheets[0]["cells"][$i][4]); //uuid
    $dato[5] = trim($data->sheets[0]["cells"][$i][5]); //concepto
    $dato[6] = trim($data->sheets[0]["cells"][$i][6]); //id moneda
    
    if($dato[1] != '' && $dato[2] != '' && $dato[3] != '' && $dato[4] != '' && $dato[6] != '')
        $this->CuentasModel->guardarLay($dato,$_GET['t']);
    else
    {
        $this->CuentasModel->borrar($_GET['t']);
        echo "<br /><b style='color:red;'>Existen registros con campos obligatorios vacios y no se guardaron los registros, revise su layout.</b>";
        echo "<br /><br /><a style='background-color:gray; color:black;text-decoration:none;width:200px;height:50px;border:1px solid black;' href='index.php?c=cuentas&f=$tipo'>Regresar</a>";
        $sigue = 0;
        break;
    }
}
unlink(dirname(__FILE__).'/cuentas'.$_GET['t'].'_temp.xls');

if(intval($sigue))
    echo "<script type='text/javascript'>window.location = 'index.php?c=cuentas&f=$tipo'</script>";
?>
