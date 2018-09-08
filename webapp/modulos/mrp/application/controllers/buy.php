<?php	

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//error_reporting(E_ALL);
class Buy extends CI_Controller {

	/*
	public function index()
	{
		$this->load->model('Compra');		
      	$data["ordenes_compra"]=$this->Compra->obtenOrdenes();
		$this->load->view('buy/index',$data);
	}
	*/
	///////////////////////////////////////////
	public function uploadfile()
	{
		$ordenid=$_POST['ordenid'];
		if(!file_exists("facturas/".$ordenid)){
			mkdir("facturas/".$ordenid, 0777, true);
		}
		
		/*if(!mkdir("facturas/".$ordenid, 0777)) {
   			 die('Fallo al crear las carpetas...');
		} */
		
		$output_dir = "facturas/".$ordenid."/";
			if(isset($_FILES["myfile"]))
			{
				//Filter the file types , if you want.
				if ($_FILES["myfile"]["error"] > 0)
				{
				  echo "Error: " . $_FILES["file"]["error"] . "<br>";
				}
				else
				{
					//move the uploaded file to uploads folder;
					move_uploaded_file($_FILES["myfile"]["tmp_name"],$output_dir. $_FILES["myfile"]["name"]);
					//echo 'dir='.$output_dir.' name='.$_FILES["myfile"]["name"];
				echo $output_dir. $_FILES["myfile"]["name"];
				}
			}
	}
////////////////////////////////////////////
	
	public function actulizar()
	{
		$this->load->model('Compra');
		$this->Compra->actualizar($_POST["id"],$_POST["fact"],$_POST["xml"],$_POST["factura"]);
	}
	public function cuentasporpagar(){
		//$this->load->model('Compra');
		$idorden = $_POST["id"];
		$total = $_POST["ctotal"];
		echo 'orden='.$idorden.'total='.$total;
		$this->load->model('Compra');
		$this->Compra->cxp($idorden,$total);
	}
	////////////////////////////////////////////
	
	public function guardar()
	{
		$this->load->model('Compra');
		$this->Compra->guardar($_POST["datos"],$_POST["fact"],$_POST["xml"],$_POST["factura"],$_POST["orden"],$_POST["sucursal"],$_POST["comentario"]);
	}
	///////////////////////////////////////////////////
	public function orden($id)
	{
		$this->load->model('Compra');
		$orden=$this->Compra->get($id);
		$detalle_orden=$this->Compra->detalle($id);
		$data["orden"]=$orden;
		$data["factura"]=$this->Compra->factura($id);
		/**/
		if(count($orden)>0)
		{
			if(strcmp($orden[0]->Estatus,"Registrada")==0){$propiedad="";}else{$propiedad="readonly";} 
		}else{ $propiedad=""; }
		
		$str_orden='<table border="1" width="100%" align="center">
<tr class="tit_tabla_buscar">
<td class="nmcatalogbusquedatit" align="center">Cantidad</td>
<td class="nmcatalogbusquedatit" align="center">Unidad</td>
<td class="nmcatalogbusquedatit" align="center">Producto</td>
<td class="nmcatalogbusquedatit" align="center">Costo unitario</td>
<td class="nmcatalogbusquedatit" align="center">Subtotal</td>
</tr>';	
$cont=0;
$i=0;$total=0;$ids='';
foreach($detalle_orden as $producto)
{
	if(strcmp($orden[0]->Estatus,"Registrada")==0){$funciono="onkeyup='ChangeOrder(".$producto->id.");'";}else{$funciono="";} 
	
	$componentes=$this->Compra->componentes($producto->id);
	$ids.=$producto->id."*";
		if ($cont%2==0)//Si el contador es par pinta esto en la fila del grid
		{
    		$color='nmcatalogbusquedacont_1';
		}
		else//Si es impar pinta esto
		{
    		$color='nmcatalogbusquedacont_2';
		}
			$cont++;
	if($i%2==0){$str_orden.='<tr class="'.$color.'">';} else{ $str_orden.='<tr class="'.$color.'">';}
		$str_orden.="<td align='center'><input id='cantidad_".$producto->id."' ".$propiedad." value='".$producto->cantidad."' class='float nminputtext' maxlength='8' ".$funciono.">";
		$str_orden.= "<td align='center'>".$producto->compuesto."</td>";
		$str_orden.= "<td align='center'>".$producto->nombre."</td>";
		$str_orden.= "<td align='center'>$<input maxlength='10' value='".number_format($producto->ultCosto,2,".",",")."' class='float nminputtext' id='costo_".$producto->id."' ".$propiedad."  ".$funciono."></td>";
		$str_orden.="<td align='center'>$<span id='sub_".$producto->id."'>".number_format(($producto->cantidad*$producto->ultCosto),2,".",",")."</span></td>";
	$str_orden.="</tr>";
	$total+=$producto->cantidad*$producto->ultCosto;
}

if($i%2==0){$str_orden.='<tr class="nmcatalogbusquedacont_1">';} else{ $str_orden.='<tr class="nmcatalogbusquedacont_2">';}
$str_orden.="<td></td><td></td><td></td><td align='right'><strong>Neto:</strong></td><td align='center'>$<span id='neto'>".number_format($total,2,".",",")."</span></td></tr>";
$i++;
if($i%2==0){$str_orden.='<tr class="nmcatalogbusquedacont_1">';} else{ $str_orden.='<tr class="nmcatalogbusquedacont_2">';}
$str_orden.="<td></td><td></td><td></td><td align='right'><strong>Iva:</strong></td><td align='center'>$<span id='iva'>".number_format(($total*0.16),2,".",",")."</span></td></tr>";
$i++;
if($i%2==0){$str_orden.='<tr class="nmcatalogbusquedacont_1">';} else{ $str_orden.='<tr class="nmcatalogbusquedacont_2">';}
$str_orden.="<td></td><td></td><td><input type='hidden' value='".$ids."' id='ids'></td><td align='right'><strong>Total:</strong></td><td align='center'>$<span id='total' value='".number_format(($total*1.16),2,".",",")."'>".number_format(($total*1.16),2,".",",")."</span></td></tr>";
$str_orden.='</table>';

		$data["detalle_orden"]=$str_orden;
		$this->load->view('buy/orden',$data);
	}
	/////////////////////////////////////////////	
	public function index($modo="1",$filtro=1,$pagina=1,$elimina=false)
	{
		  $this->load->model('Compra');
		  $this->load->helper('url');	
		  $grid=$this->Compra->grid($pagina,$filtro);
		  $encabezado='';$busquedas='';  
		  $filas='';
			$i=0;
			foreach($grid["data"] as $fila)
			{
				$encabezado='';$busquedas='';
				if($i%2==0){$filas.='<tr class="nmcatalogbusquedacont_1">';}
				else{$filas.='<tr class="nmcatalogbusquedacont_2">';}
				$e=0;
				foreach($fila as $campo=>$valor)
				{
				if($e==0){$id=$valor;}
				if($e==2)
				{
					$cadena_hora_fecha = $valor;
					list($fecha, $hora) = explode(' ', $cadena_hora_fecha);
					list($ano, $mes, $dia) = explode('-', $fecha);
					list($hour, $minuto, $segundo) = explode(':', $hora);
					
					if($hour < 12)
						$indicador = "am";
					else
					{
						$indicador = "pm";
						$hour = $hour-12;
					}
						
					$valor = $dia . "/" . $mes . "/" . $ano . " " . $hour . ":" . $minuto . " " . $indicador; 
				}
				if($e==3)
				{
					$fecha = $valor;
					list($ano, $mes, $dia) = explode('-', $fecha);
					
					if ($ano == 0000 || $mes == 00 || $dia == 00)
						$valor = "Falta fecha de entrega";
					else 
						$valor = $dia . "/" . $mes . "/" . $ano;
				}
	$encabezado.='<td  class="nmcatalogbusquedatit" align="center">'.str_replace("_"," ",$campo).'</td>';
	$busquedas.='<td><input class="nmcatalogbusquedainputtext input_filtro" onkeydown="input_keydown(event,this.value,\''.$campo.'\')"></td>';
if($modo==1){					
$filas.='<td><a class="a_registro" href="'.base_url().'index.php/buy/orden/'.$id.'">'.utf8_decode($valor).'</a></td>';
}
if($modo==2){					
$filas.='<td><a class="a_registro" onclick="Elimina('.$id.',\'mrp_producto\');">'.utf8_decode($valor).'</a></td>';
}

				$e++;
				}
				$filas.='</tr>';
			$i++;
			}
if($i<10)
{
	//if($i%2==0){$filas.='<tr class="busqueda_fila">';}else{$filas.='<tr class="busqueda_fila2">';}
	
	$encabezado='<td class="nmcatalogbusquedatit" align="center">Id</td>';
	$encabezado.='<td class="nmcatalogbusquedatit" align="center">Proveedor</td>';
	$encabezado.='<td class="nmcatalogbusquedatit" align="center">Fecha pedido</td>';
	$encabezado.='<td class="nmcatalogbusquedatit" align="center">Fecha de entrega</td>';
	$encabezado.='<td class="nmcatalogbusquedatit" align="center">Elaboro</td>';
	$encabezado.='<td class="nmcatalogbusquedatit" align="center">Almacen</td>';
	$encabezado.='<td class="nmcatalogbusquedatit" align="center">Autorizado por</td>';
	$encabezado.='<td class="nmcatalogbusquedatit" align="center">Estatus</td>';
	$encabezado.='<td class="nmcatalogbusquedatit" align="center">Orden de producción</td>';

	$e=8;
	//$i++;
		for($k=0;$k<=10;$k++)
		{
				if($i%2==0){$filas.='<tr class="busqueda_fila">';}else{$filas.='<tr class="busqueda_fila2">';}
				for($j=0;$j<=$e;$j++){$filas.='<td></td>';}
				$filas.='</tr>';
		$i++;
		}	
}			
			
if($pagina==1){$pag_anterior=1;}else{$pag_anterior=$pagina-1;}
if(($pagina+1)>$grid["paginas"]){$pag_siguiente=$pagina;}else{$pag_siguiente=$pagina+1;}			

$link_anterior=base_url().'index.php/buy/index/'.$modo.'/'.$filtro.'/'.$pag_anterior;
$link_siguiente=base_url().'index.php/buy/index/'.$modo.'/'.$filtro.'/'.$pag_siguiente;
$base_url=str_replace("modulos/mrp/","",base_url());

$catalogo='
<link rel="stylesheet" href="../../../libraries/bootstrap/dist/css/bootstrap.min.css" type="text/css">
<style>
	@media print
	{
		a[href]:after {
			content: none;
		}
		#imprimir,#filtros,#excel, #botones
		{
			display:none;
		}
		.table-responsive{
			overflow-x: unset;
		}
		#imp_cont{
			width: 100% !important;
		}
	}
  	.row
  	{
      	margin-top: 0.5em !important;
  	}
  	h4, h3{
      	background-color: #eee;
      	padding: 0.4em;
  	}
  	.nmwatitles, [id="title"] {
      	padding: 8px 0 3px !important;
     	background-color: unset !important;
  	}
  	.select2-container{
      	width: 100% !important;
  	}
  	.select2-container .select2-choice{
      	background-image: unset !important;
     	height: 31px !important;
  	}
  	.twitter-typeahead{
  		width: 100% !important;
  	}
  	.tablaResponsiva{
        max-width: 100vw !important; 
        display: inline-block;
    }
	.table tr, .table td{
		border: none !important;
	}
</style>
<div class="container" style="width:100%">
	<div class="row">
		<div class="col-md-12">
			<h3 class="nmwatitles text-center">
				'.$grid['nombre'].'<br>
				<section id="botones">
					<img type="button" onclick="paginacionGrid(\''.$link_anterior.'\');"  class="nmwaicons" src="'.$base_url.'netwarelog/design/default/pag_ant.png">
                    <a href="javascript:window.print();"><img class="nmwaicons" src="'.$base_url.'netwarelog/design/default/impresora.png" border="0"></a>	
					<img type="button" onclick="paginacionGrid(\''.$link_siguiente.'\');" class="nmwaicons" src="'.$base_url.'netwarelog/design/default/pag_sig.png">
				</section>
			</h3>
		</div>
	</div>';
					
$catalogo.='
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12 tablaResponsiva">
			<div class="table-responsive">
				<table class="busqueda" border="1" cellpadding="3" cellspacing="1" width="100%">
				<tr class="tit_tabla_buscar">'.$encabezado.'</tr>			
				<tr class="titulo_filtros" title="Segmento de búsqueda">'.$busquedas.'</tr>
				'.$filas.'</table>
			</div>
		</div>
	</div>
</div>';	
			
$data=array('grid'=>$catalogo,'pagina_anterior'=>$link_anterior,'pagina_siguiente'=>$link_siguiente);

if(!isset($_POST["ajax"]) && !$elimina)
{		  
	$this->load->view('grid',$data); 	
}
else
{
	echo $catalogo;
}
	}
	////////////////////////////////////////////
	
	

}