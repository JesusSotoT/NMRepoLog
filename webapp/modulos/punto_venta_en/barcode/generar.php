<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<style>
body
{
	font-family: Tahoma,"Trebuchet MS", Arial;
}

@media print
{
	.noprint
	{
		display:none;
	}
}
</style>
<center><h2  class='noprint'>Generar C&oacute;digo de Barras</h2>
	<input type='button' value='Imprimir' class='noprint' onClick='javascript:window.print();'>
<?php
//ini_set('display_errors', 1);
		//Abre Conexion a la base de datos
		include("../../../netwarelog/webconfig.php");
		$connection = new mysqli($servidor,$usuariobd,$clavebd,$bd);

		//Toma los valores de la cadena
		$id_prods = explode(',', $_GET['id_prods']);

		//Crea la tabla que contendra los codigos de barras
		echo "<table width='50%' align='center' cellpadding='20' border=0><tr>";
		$contador = 0;//Contador

		foreach ($id_prods as $id_prod)//Por cada valor de la cadena hace una consulta
		{
			//Hace la consulta a la base de datos para tomar el codigo del articulo
			$Consulta = "SELECT codigo,nombre,precioventa FROM mrp_producto WHERE idProducto = ".$id_prod;
			$articulo_info = $connection->query($Consulta);

			 	$queryImpuestos = "select p.idProducto,p.precioventa, pi.valor, i.nombre, i.retenido";
                $queryImpuestos .= " from impuesto i, mrp_producto p ";
                $queryImpuestos .= " left join producto_impuesto pi on p.idProducto=pi.idProducto ";
                $queryImpuestos .= " where p.idProducto=" . $id_prod . " and i.id=pi.idImpuesto ";
                $queryImpuestos .= " Order by pi.idImpuesto DESC ";
                $impuestosinfo = $connection->query($queryImpuestos);
               	$ieps = 0;
                while($impuestos = $impuestosinfo->fetch_object()){
                	    if ($impuestos->nombre == 'IEPS') {
                            $producto_impuesto = $ieps = (($impuestos->precioventa) * $impuestos->valor / 100);
                        } else {
                            if ($ieps != 0) {
                            	//echo 'X'.$ieps;
                                $producto_impuesto += ((($impuestos->precioventa + $ieps)) * $impuestos->valor / 100);
                                 /*if($impuestos->retenido==1){
                                    $nombreret=$impuestos->nombre;
                                    $producto_impuesto_ret =  (($impuestos->precioventa) * $impuestos->retenido / 100);//sacco el retenido
                                }  */                              
                            } else {
                                /*if($impuestos->retenido==1){
                                    $nombreret=$impuestos->nombre;
                                    $producto_impuesto_ret =  (($impuestos->precioventa) * $impuestos->valor / 100);//sacco el retenido 
                                }else{ */
                                    $producto_impuesto += (($impuestos->precioventa) * $impuestos->valor / 100);
                                //}                                 
                            }
                        }
                }
                //echo 'precio='.$producto_impuesto + $articulo->precioventa;
				
			while($articulo = $articulo_info->fetch_object())//Por cada elemento de la cadena toma el codigo y genera las barras
			{
				//Si el valor del contador es par cierra la columna
				if ($contador % 2 ==0 and $contador!=0)
				{
					echo '</tr><tr>';
				}

				//Pinta el codigo de barras, basandose en el codigo del producto
				echo "<td style='text-align:center;'>";
				echo "<div>".$articulo->nombre."</div>";
				echo "<div>$".number_format(($producto_impuesto + $articulo->precioventa),2)."</div>";
				echo "<div><img src='barras.php?c=barcode&barcode=".$articulo->codigo."&text=".$articulo->codigo."&width=430' /></div>";
				echo "</td>";
				$contador++;
				$producto_impuesto = 0;
			}
			
		}

		//Cierra tabla
		echo "</tr></table>";
		
		//Cierra conexion
		$connection->close();
		function object_to_array($data) {
		    if (is_array($data) || is_object($data)) {
		        $result = array();
		        foreach ($data as $key => $value) {
		            $result[$key] = object_to_array($value);
		        }
		        return $result;
		    }
		    return $data;
		}
		?>
		<br >
		<label  class='noprint'>NetwarMonitor </label></center>
		