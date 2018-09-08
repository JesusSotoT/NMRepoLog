Cargando....
<?php
ini_set("display_errors",0);
ini_set('memory_limit', '-1');
//error_reporting(E_WARNING);

require_once '../../libraries/Excel/reader.php';

$data = new Spreadsheet_Excel_Reader();
$data->setOutputEncoding('CP1251');
$data->read(dirname(__FILE__).'/cxc.xls');

$dato = array();
$sigue = 1;
for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++) 
{

    $dato[1] = trim($data->sheets[0]["cells"][$i][1]); //Cliente
    $dato[2] = trim($data->sheets[0]["cells"][$i][2]); //Saldo

    
    if($dato[1] != '' && $dato[2] != '')
        $this->ProductoModel->cargaSaldosCxc($dato);
    else
    {
        $this->ProductoModel->borrar(99);
        echo "<br /><b style='color:red;'>Existen registros con campos obligatorios vacios y no se guardaron los registros, revise su layout.</b>";
        //echo "<br /><br /><a style='background-color:gray; color:black;text-decoration:none;width:200px;height:50px;border:1px solid black;'>href='index.php?c=producto&f=indexGridProductos'>Regresar</a>";
        $sigue = 0;
        break;
    }

    //Valida si existe proveedor
  /*  if(strpos($dato[11], ',') === false)
        $validado = $this->ProductoModel->validaProveedor($dato[11]);

    if(strpos($dato[11], ',') !== false)
    {
        $provs = explode(',',$dato[11]);
        for($j=0;$j<=count($provs)-1;$j++)
        {
            $validado = $this->ProductoModel->validaProveedor($provs[$j]);
            if(!intval($validado))
                break;
        }
    }

    if(!intval($validado))
    {
        $this->ProductoModel->borrar(99);
        echo "<br /><b style='color:red;'>Existen registros con proveedores no validos,  revise su layout.</b>";
        echo "<br /><br /><a style='background-color:gray; color:black;text-decoration:none;width:200px;height:50px;border:1px solid black;' href='index.php?c=producto&f=indexGridProductos'>Regresar</a>";
        $sigue = 0;
        break;
    } */
}
exit();
unlink(dirname(__FILE__).'/productos_tempPrices.xls');
echo "<script type='text/javascript'>window.location = 'index.php?c=producto&f=indexGridProductos'</script>";
/*if(intval($sigue))
{
    //Validaciones
    /*$prods = $this->ProductoModel->validarProductos(99);
    $repetidos = '';
    while($p = $prods->fetch_assoc())
        $repetidos .= $p['codigo']." / ".$p['nombre']."<br />";

    $error = 0;
    if($repetidos != '')
    {
        echo "<br /><b style='color:red;'>Las siguientes cuentas estan repetidas y no se cargaran los datos del layout hasta ser corregido:</b> <br />";
        echo $repetidos;
        $this->ProductoModel->borrar(99);
        echo "<br /><br /><a style='background-color:gray; color:black;text-decoration:none;width:200px;height:50px;border:1px solid black;' href='index.php?c=producto&f=indexGridProductos'>Regresar</a>";
        $error++;
    } */

    //Si no hubo errores y no se eliminaron entonces confirmar registro
    /*if(!intval($error))
    {
        $this->ProductoModel->confirmar(99);
        echo "<script type='text/javascript'>window.location = 'index.php?c=producto&f=indexGridProductos'</script>";
    }
} */





?>
