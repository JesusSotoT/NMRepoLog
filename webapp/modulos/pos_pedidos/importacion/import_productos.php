Cargando....
<?php
ini_set("display_errors",0);
ini_set('memory_limit', '-1');
//error_reporting(E_WARNING);

require_once '../../libraries/Excel/reader.php';

$data = new Spreadsheet_Excel_Reader();
$data->setOutputEncoding('CP1251');
$data->read(dirname(__FILE__).'/productos_temp.xls');

$dato = array();
$sigue = 1;
for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++) 
{

    $dato[1] = trim($data->sheets[0]["cells"][$i][1]); //Codigo
    $dato[2] = trim($data->sheets[0]["cells"][$i][2]); //Nombre
    $dato[3] = trim($data->sheets[0]["cells"][$i][3]); //Precio
    $dato[4] = trim($data->sheets[0]["cells"][$i][4]); //Desc Corta
    $dato[5] = trim($data->sheets[0]["cells"][$i][5]); //Desc Larga
    $dato[6] = trim($data->sheets[0]["cells"][$i][6]); //url imagen
    $dato[7] = trim($data->sheets[0]["cells"][$i][7]); //tipo producto
    $dato[8] = trim($data->sheets[0]["cells"][$i][8]); //maximos
    $dato[9] = trim($data->sheets[0]["cells"][$i][9]); //minimos
    $dato[10] = trim($data->sheets[0]["cells"][$i][10]); //ids impuestos
    $dato[11] = trim($data->sheets[0]["cells"][$i][11]); //ids proveedores
    $dato[12] = trim($data->sheets[0]["cells"][$i][12]); //ids caracteristicas padre=>hijo
    $dato[13] = trim($data->sheets[0]["cells"][$i][13]); //id departamento
    $dato[14] = trim($data->sheets[0]["cells"][$i][14]); //id familia
    $dato[15] = trim($data->sheets[0]["cells"][$i][15]); //id linea
    $dato[16] = trim($data->sheets[0]["cells"][$i][16]); //tipo costeo
    $dato[17] = trim($data->sheets[0]["cells"][$i][17]); //id moneda
    $dato[18] = $this->ProductoModel->unidad_medida(trim($data->sheets[0]["cells"][$i][18])); //codigo unidad venta
    $dato[19] = trim($data->sheets[0]["cells"][$i][19]); //bool series
    $dato[20] = trim($data->sheets[0]["cells"][$i][20]); //bool lotes
    $dato[21] = trim($data->sheets[0]["cells"][$i][21]); //bool pedimentos
    $dato[22] = $this->ProductoModel->unidad_medida(trim($data->sheets[0]["cells"][$i][22])); //codigo unidad compra
    $dato[23] = trim($data->sheets[0]["cells"][$i][23]); //costo servicio
    
    if($dato[2] != '' && $dato[7] != '' && $dato[11] != '' && $dato[17] != '' && $dato[18] != '' && $dato[22] != '')
        $this->ProductoModel->guardarLay($dato);
    else
    {
        $this->ProductoModel->borrar(99);
        echo "<br /><b style='color:red;'>Existen registros con campos obligatorios vacios y no se guardaron los registros, revise su layout.</b>";
        echo "<br /><a href='index.php?c=producto&f=indexGridProductos'>Regresar</a>";
        $sigue = 0;
        break;
    }

    //Valida si existe proveedor
    if(strpos($dato[11], ',') === false)
        $validado = $this->ProductoModel->validaProveedor($dato[11]);

    if(strpos($dato[11], ',') !== false)
    {
        $provs = explode(',',$dato[11]);
        for($i=0;$i<=count($provs)-1;$i++)
        {
            $validado = $this->ProductoModel->validaProveedor($provs[$i]);
            if(!intval($validado))
                break;
        }
    }

    if(!intval($validado))
    {
        $this->ProductoModel->borrar(99);
        echo "<br /><b style='color:red;'>Existen registros con proveedores no validos,  revise su layout.</b>";
        echo "<br /><a href='index.php?c=producto&f=indexGridProductos'>Regresar</a>";
        $sigue = 0;
        break;
    }
}
unlink(dirname(__FILE__).'/productos_temp.xls');
if(intval($sigue))
{
    //Validaciones
    $prods = $this->ProductoModel->validarProductos(99);
    $repetidos = '';
    while($p = $prods->fetch_assoc())
        $repetidos .= $p['codigo']." / ".$p['nombre']."<br />";

    $error = 0;
    if($repetidos != '')
    {
        echo "<br /><b style='color:red;'>Las siguientes cuentas estan repetidas y no se cargaran los datos del layout hasta ser corregido:</b> <br />";
        echo $repetidos;
        $this->ProductoModel->borrar(99);
        echo "<a href='index.php?c=producto&f=indexGridProductos'>Regresar</a>";
        $error++;
    }

    //Si no hubo errores y no se eliminaron entonces confirmar registro
    if(!intval($error))
    {
        $this->ProductoModel->confirmar(99);
        echo "<script type='text/javascript'>window.location = 'index.php?c=producto&f=indexGridProductos'</script>";
    }
}





?>
