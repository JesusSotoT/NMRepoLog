Cargando....
<?php
ini_set("display_errors",0);
ini_set('memory_limit', '-1');
//error_reporting(E_WARNING);

require_once '../../libraries/Excel/reader.php';

$data = new Spreadsheet_Excel_Reader();
$data->setOutputEncoding('CP1251');
$data->read(dirname(__FILE__).'/clientes_temp.xls');

$dato = array();
$sigue = 1;
for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++) 
{

    $dato[1] = trim($data->sheets[0]["cells"][$i][1]); //No
    $dato[2] = trim($data->sheets[0]["cells"][$i][2]); //Responsable
    $dato[3] = trim($data->sheets[0]["cells"][$i][3]); //Estado
    $dato[4] = trim($data->sheets[0]["cells"][$i][4]); //Poblacion
    $dato[5] = trim($data->sheets[0]["cells"][$i][5]); //Tipo
    $dato[6] = trim($data->sheets[0]["cells"][$i][6]); //ORganismo
    $dato[7] = trim($data->sheets[0]["cells"][$i][7]); //Folio Inadem
    $dato[8] = trim($data->sheets[0]["cells"][$i][8]); //Vale
    $dato[9] = trim($data->sheets[0]["cells"][$i][9]); //Factura
    $dato[10] = trim($data->sheets[0]["cells"][$i][10]); //Archivo
    $dato[11] = trim($data->sheets[0]["cells"][$i][11]); //Concepto
    $dato[12] = trim($data->sheets[0]["cells"][$i][12]); //Estatus
    $dato[13] = trim($data->sheets[0]["cells"][$i][13]); //Vitrina
    $dato[14] = trim($data->sheets[0]["cells"][$i][14]); //Monto
    $dato[15] = trim($data->sheets[0]["cells"][$i][15]); //Nombre Empresario
    $dato[16] = trim($data->sheets[0]["cells"][$i][16]); //Razon Social
    $dato[17] = trim($data->sheets[0]["cells"][$i][17]); //RFC
    $dato[18] = trim($data->sheets[0]["cells"][$i][18]); //LONG RFC
    $dato[19] = trim($data->sheets[0]["cells"][$i][19]); //Domicilio
    $dato[20] = trim($data->sheets[0]["cells"][$i][20]); //No. Exterior
    $dato[21] = trim($data->sheets[0]["cells"][$i][21]); //No. Interior
    ///$dato[22] = $this->ProductoModel->unidad_medida(trim($data->sheets[0]["cells"][$i][22])); //codigo unidad compra
    $dato[22] = trim($data->sheets[0]["cells"][$i][22]); //Colonia
    $dato[23] = trim($data->sheets[0]["cells"][$i][23]); //CP
    $dato[24] = trim($data->sheets[0]["cells"][$i][24]); //Poblacion
    $dato[25] = trim($data->sheets[0]["cells"][$i][25]); //Estado
    $dato[26] = trim($data->sheets[0]["cells"][$i][26]); //Telefono
    $dato[27] = trim($data->sheets[0]["cells"][$i][27]); //Celular
    $dato[28] = trim($data->sheets[0]["cells"][$i][28]); //Email
    //echo 'eididididid';
    
    //$r = $this->clienteModel->guardarLay($dato);
    if($dato[3] != '0' && $dato[4] != '0' && $dato[24] != '0' && $dato[25] != '0'){
        $r = $this->clienteModel->guardarLay($dato);
    }
    else
    {
        echo "<br /><b style='color:red;'>Los estados o municipios , revise su layout.</b>";
        echo "<br /><br /><a style='background-color:gray; color:black;text-decoration:none;width:200px;height:50px;border:1px solid black;' href='index.php?c=producto&f=indexGridProductos'>Regresar</a>";
        //$sigue = 0;
        //break;

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
print_r($dato);
unlink(dirname(__FILE__).'/clientes_temp.xls');
/*if(intval($sigue))
{
    //Validaciones
    $prods = $this->ProductoModel->validarProductos(99);
    $repetidos = '';
    while($p = $prods->fetch_assoc())
        $repetidos .= $p['codigo']." / ".$p['nombre']."<br />";

    $error = 0;
    if($repetidos != '')
    {
        echo "<br /><b style='color:red;'>Los siguientes productos estan repetidos y no se cargaran los datos del layout hasta ser corregido:</b> <br />";
        echo $repetidos;
        $this->ProductoModel->borrar(99);
        echo "<br /><br /><a style='background-color:gray; color:black;text-decoration:none;width:200px;height:50px;border:1px solid black;' href='index.php?c=producto&f=indexGridProductos'>Regresar</a>";
        $error++;
    }

    //Si no hubo errores y no se eliminaron entonces confirmar registro
    if(!intval($error))
    {
        echo "<br /><b style='color:blue;'>Validado</b><br /><br /><b>Seleccione los productos a guardar.</b><table>";
        echo "<tr style='background-color:#286090;color:white;'><td width='100'>Seleccionar</td><td width='200'>Clave</td><td width='200'>Nombre</td><td width='300'>Descripcion</td><td width='100'>Precio</td></tr>";
        $cargados = $this->ProductoModel->traeCargados(99);
        while($car = $cargados->fetch_object())
        {
            echo "<tr style='background-color:#f5f5f5;'><td><input type='checkbox' id='chk-$car->id' onclick='sel_chk($car->id)' checked></td><td>$car->codigo</td><td>$car->nombre</td><td>$car->descripcion_corta</td><td>$ $car->precio</td></tr>";
        }
        echo "</table>";
        //$this->ProductoModel->borrar(99);
        echo "<br /><br /><button onclick='confirmar(99)'>Guardar</button>";
        //$this->ProductoModel->confirmar(99);
        //echo "<script type='text/javascript'>window.location = 'index.php?c=producto&f=indexGridProductos'</script>";
    } */
//} 
    
?>
<script src="../../libraries/jquery.min.js"></script>
<script language='javascript'>
   /* function sel_chk(id)
    {
        var chk = $("#chk-"+id).prop('checked') ? 1 : 0;
        if(chk)
        {
            $.post('ajax.php?c=producto&f=reactivarProdLay', 
                {
                    id  : id,
                    num : 99
                },
                function(data)
                {
                    //console.log(data)
                    console.log("Reactivado")
                });
        }
        else
        {
            $.post('ajax.php?c=producto&f=inactivarProdLay', 
                {
                    id : id,
                    num : 98
                },
                function(data)
                {
                    //console.log(data)
                    console.log("Inactivado")
                });
        }
    }

    function confirmar(num)
    {
         $.post('ajax.php?c=producto&f=confirmarProdLay', 
         {
            num : num
         },
         function()
         {
            window.location = 'index.php?c=producto&f=indexGridProductos'
         });
    } */
</script>
