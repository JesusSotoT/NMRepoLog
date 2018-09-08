Cargando archivo...
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


$filasConError = array();
$filasConProveedoresInvalidos = array();
for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++) 
{

    $dato[1] = ( (trim($data->sheets[0]["cells"][$i][1]) != "") ? trim($data->sheets[0]["cells"][$i][1]) : ""); //Codigo
    $dato[2] = ( (utf8_encode(trim($data->sheets[0]["cells"][$i][2])) != "") ? utf8_encode(trim($data->sheets[0]["cells"][$i][2])) : ""); //Nombre
    $dato[3] = ( (trim($data->sheets[0]["cells"][$i][3]) != "") ? trim($data->sheets[0]["cells"][$i][3]) : 0); //Precio
    $dato[4] = ( (utf8_encode(trim($data->sheets[0]["cells"][$i][4])) != "") ? utf8_encode(trim($data->sheets[0]["cells"][$i][4])) : ""); //Desc Corta
    $dato[5] = ( (utf8_encode(trim($data->sheets[0]["cells"][$i][5])) != "") ? utf8_encode(trim($data->sheets[0]["cells"][$i][5])) : ""); //Desc Larga
    $dato[6] = ( (trim($data->sheets[0]["cells"][$i][6]) != "") ? trim($data->sheets[0]["cells"][$i][6]) : ""); //url imagen
    $dato[7] = ( (trim($data->sheets[0]["cells"][$i][7]) != "") ? trim($data->sheets[0]["cells"][$i][7]) : 0); //tipo producto

    $dato[8] = ( (trim($data->sheets[0]["cells"][$i][8]) != "") ? trim($data->sheets[0]["cells"][$i][8]) : 0); //maximos
    $dato[9] = ( (trim($data->sheets[0]["cells"][$i][9]) != "") ? trim($data->sheets[0]["cells"][$i][9]) : 0); //minimos
    $dato[10] = ( (trim($data->sheets[0]["cells"][$i][10]) != "") ? trim($data->sheets[0]["cells"][$i][10]) : 0); //ids impuestos
    $dato[11] = ( (trim($data->sheets[0]["cells"][$i][11]) != "") ? trim($data->sheets[0]["cells"][$i][11]) : 0); //ids proveedores
    $dato[12] = ( (trim($data->sheets[0]["cells"][$i][12]) != "") ? trim($data->sheets[0]["cells"][$i][12]) : 0); //ids caracteristicas padre=>hijo
    $dato[13] = ( (trim($data->sheets[0]["cells"][$i][13]) != "") ? trim($data->sheets[0]["cells"][$i][13]) : 0); //id departamento
    $dato[14] = ( (trim($data->sheets[0]["cells"][$i][14]) != "") ? trim($data->sheets[0]["cells"][$i][14]) : 0); //id familia
    $dato[15] = ( (trim($data->sheets[0]["cells"][$i][15]) != "") ? trim($data->sheets[0]["cells"][$i][15]) : 0); //id linea
    $dato[16] = ( (trim($data->sheets[0]["cells"][$i][7]) != "") ? trim($data->sheets[0]["cells"][$i][16]) : 0); //tipo costeo
    $dato[17] = ( (trim($data->sheets[0]["cells"][$i][16]) != "") ? trim($data->sheets[0]["cells"][$i][17]) : 0); //id moneda
    $dato[18] = $this->ProductoModel->unidad_medida(trim($data->sheets[0]["cells"][$i][18])); //codigo unidad venta
    $dato[19] = ( (trim($data->sheets[0]["cells"][$i][19]) != "") ? trim($data->sheets[0]["cells"][$i][19]) : 0); //bool series
    $dato[20] = ( (trim($data->sheets[0]["cells"][$i][20]) != "") ? trim($data->sheets[0]["cells"][$i][20]) : 0); //bool lotes
    $dato[21] = ( (trim($data->sheets[0]["cells"][$i][21]) != "") ? trim($data->sheets[0]["cells"][$i][21]) : 0); //bool pedimentos
    //$dato[22] = ( (trim($data->sheets[0]["cells"][$i][22]) != "") ? trim($data->sheets[0]["cells"][$i][22]) : ""); //codigo unidad compra
    $dato[22] = $this->ProductoModel->unidad_medida(trim($data->sheets[0]["cells"][$i][22])); //codigo unidad compra
    $dato[23] = ( (trim($data->sheets[0]["cells"][$i][23]) != "") ? trim($data->sheets[0]["cells"][$i][23]) : 0); //costo servicio
//Clave Nombre  Descripcion 

    if($dato[2] != '' && $dato[7] != '' && $dato[11] != '' && $dato[17] != '' && $dato[18] != '' && $dato[22] != ''){
        $this->ProductoModel->guardarLay($dato);

        //Valida si existe proveedor
        if(strpos($dato[11], ',') === false)
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
            //$this->ProductoModel->borrar(99);
            //echo "<br /><b style='color:red;'>Existen registros con proveedores no validos,  revise su layout.</b>";
            //echo "<br /><br /><a style='background-color:gray; color:black;text-decoration:none;width:200px;height:50px;border:1px solid black;' href='index.php?c=producto&f=indexGridProductos'>Regresar</a>";
            //echo "<br /><br /><a style='background-color:gray; color:black;text-decoration:none;width:200px;height:50px;border:1px solid black;' href='index.php?c=producto&f=indexImportarProductos'>Regresar</a>";                                

            array_push($filasConProveedoresInvalidos, ($dato[1]." / ".$dato[2]) );                                                            
            $sigue = 0;
            continue;
        }
    }
    else
    {

/*        $this->ProductoModel->borrar(99);

        echo "<br /><b style='color:red;'>Existen registros con campos obligatorios vacios y no se guardaron los registros, revise su layout.</b>";
        echo "";
        //echo "<br /><br /><a style='background-color:gray; color:black;text-decoration:none;width:200px;height:50px;border:1px solid black;' href='index.php?c=producto&f=indexGridProductos'>Regresar</a>";
        echo "<br /><br /><a style='background-color:gray; color:black;text-decoration:none;width:200px;height:50px;border:1px solid black;' href='index.php?c=producto&f=indexImportarProductos'>Regresar</a>";                                                          */

        array_push($filasConError, ($dato[1]." / ".$dato[2]) );
        $sigue = 0;
        continue;
    }

    
}
unlink(dirname(__FILE__).'/productos_temp.xls');
if(intval($sigue))
{
    //Validaciones
    $prods = $this->ProductoModel->validarProductos(99);
    //print_r($prods);
    $repetidos = '';
    while($p = $prods->fetch_assoc())
        $repetidos .= $p['codigo']." / ".$p['nombre']."<br />";

    $error = 0;
    if($repetidos != '')
    {
        echo "<br /><b style='color:red;'>Los siguientes productos estan repetidos y no se cargaran los datos del layout hasta ser corregido:</b>  <br />";
        echo $pagina; "<br />";
        echo $repetidos;
        $this->ProductoModel->borrar(99);
        //echo "<br /><br /><a style='background-color:gray; color:black;text-decoration:none;width:200px;height:50px;border:1px solid black;' href='index.php?c=producto&f=indexGridProductos;'>Regresar</a>";
        echo "<br /><br /><a style='background-color:gray; color:black;text-decoration:none;width:200px;height:50px;border:1px solid black;' href='javascript:history.back();'>Regresar</a>";
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
        echo "<br /><button onclick='cancelar()'>Cancelar</button>";
        //$this->ProductoModel->confirmar(99);
        //echo "<script type='text/javascript'>window.location = 'index.php?c=producto&f=indexGridProductos'</script>";
    }
}
else {

        $this->ProductoModel->borrar(99);

        
        
        if(count($filasConError) != 0){
            echo "<br /><b style='color:red;'>Los siguientes productos tienen campos obligatorios vacios</b><br />" ;
            foreach ($filasConError as $key => $value) {
                echo $value."<br /> ";
            }            
        }
        if(count($filasConProveedoresInvalidos) != 0) {
            echo "<br /><b style='color:red;'>Los siguientes productos tienen proveedor inválido</b><br />" ;
            foreach ($filasConProveedoresInvalidos as $key => $value) {
                echo $value."<br /> ";
            }            
        }
        
        echo "<br /><b style='color:red;'>No se guardaron los registros, revise y corrija su layout.</b>";
        //echo "<br /><br /><a style='background-color:gray; color:black;text-decoration:none;width:200px;height:50px;border:1px solid black;' href='index.php?c=producto&f=indexGridProductos;'>Regresar</a>";
        echo "<br /><br /><a style='background-color:gray; color:black;text-decoration:none;width:200px;height:50px;border:1px solid black;' href='javascript:history.back();'>Regresar</a>";
}

?>





<script src="../../libraries/jquery.min.js"></script>
<script language='javascript'>
    function sel_chk(id)
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
            //window.location = 'index.php?c=producto&f=indexGridProductos'
            window.location = 'javascript: window.history.back();';
         });
    }
    function cancelar()
    {
        $.post('ajax.php?c=producto&f=cancelar', 
         {},
         function()
         {
            //window.location = 'index.php?c=producto&f=indexGridProductos'
            window.location = 'javascript: window.history.back();';
         });
    }
</script>
