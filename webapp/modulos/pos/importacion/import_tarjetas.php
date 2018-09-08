Cargando....
<?php
ini_set("display_errors",0);
ini_set('memory_limit', '-1');
//error_reporting(E_WARNING);

require_once '../../libraries/Excel/reader.php';

$data = new Spreadsheet_Excel_Reader();
$data->setOutputEncoding('CP1251');
$data->read(dirname(__FILE__).'/tarjetas_temp.xls');

$dato = array();
$sigue = 1;
for ($i = 3; $i <= $data->sheets[0]['numRows']; $i++) 
{

    $dato[1] = trim($data->sheets[0]["cells"][$i][1]); //Estatus
    $dato[2] = utf8_encode(trim($data->sheets[0]["cells"][$i][2])); //Clave
    $dato[3] = utf8_encode(trim($data->sheets[0]["cells"][$i][3])); //Banco

    if($dato[2] != '' && $dato[3] != '' )
        $this->TarjetaModel->guardarLay($dato);
    else
    {

        echo "<br /><b style='color:red;'>Existen registros con campos obligatorios vacios y no se guardaron los registros, revise su layout.</b>";

        echo "<br /><br /><a style='background-color:gray; color:black;text-decoration:none;width:200px;height:50px;border:1px solid black;' href='index.php?c=tarjeta&f=indexGridTarjetas'>Regresar</a>";                                                                                                                                                   
        $sigue = 0;
        break;
    }

 
}
unlink(dirname(__FILE__).'/tarjetas_temp.xls');
if(intval($sigue))
{
    //Validaciones
    $prods = $this->TarjetaModel->validarProductos(99);
    //print_r($prods);
    $repetidos = '';
    while($p = $prods->fetch_assoc())
        $repetidos .= $p['clave']." / ".$p['nombre']."<br />";

    $error = 0;
    if($repetidos != '')
    {
        echo "<br /><b style='color:red;'>Los siguientes productos estan repetidos y no se cargaran los datos del layout hasta ser corregido:</b> <br />";
        echo $repetidos;
        //$this->TarjetaModel->borrar(99);
        //echo "<br /><br /><a style='background-color:gray; color:black;text-decoration:none;width:200px;height:50px;border:1px solid black;' href='index.php?c=producto&f=indexGridProductos'>Regresar</a>";
        echo "<br /><br /><a style='background-color:gray; color:black;text-decoration:none;width:200px;height:50px;border:1px solid black;' href='index.php?c=destino&f=indexGridDestinos'>Regresar</a>";                                                                                                                                                   
        $error++;
    }

    //Si no hubo errores y no se eliminaron entonces confirmar registro
    if(!intval($error))
    {
        echo "<br /><b style='color:blue;'>Validado</b><br /><br /><b>Seleccione las tarjetas a guardar.</b><table>";
        echo "<tr style='background-color:#286090;color:white;'><td width='100'>Seleccionar</td><td width='200'>Clave</td><td width='200'>Banco</td><td width='300'>Estatus</td></tr>";
        $cargados = $this->TarjetaModel->traeCargados(99);
        while($car = $cargados->fetch_object())
        {
            echo "<tr style='background-color:#f5f5f5;'>
                    <td><input type='checkbox' id='chk-$car->id' onclick='sel_chk($car->id)' checked></td>
                    <td>$car->clave</td>
                    <td>$car->banco</td>
                    <td>$car->estatus</td>
                </tr>";
        }
        echo "</table>";
        //$this->TarjetaModel->borrar(99);
        echo "<br /><br /><button onclick='confirmar(99)'>Guardar</button>";
        //$this->TarjetaModel->confirmar(99);
        //echo "<script type='text/javascript'>window.location = 'index.php?c=producto&f=indexGridProductos'</script>";
    }
}

?>





<script src="../../libraries/jquery.min.js"></script>
<script language='javascript'>












    function sel_chk(id)
    {
        var chk = $("#chk-"+id).prop('checked') ? 1 : 0;
        if(chk)
        {
            $.post('ajax.php?c=tarjeta&f=reactivarLay', 
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
            $.post('ajax.php?c=tarjeta&f=inactivarLay', 
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
         $.post('ajax.php?c=tarjeta&f=confirmarLay', 
         {
            num : num
         },
         function()
         {
            window.location = 'index.php?c=tarjeta&f=indexGridTarjetas'
         });
    }
</script>
