<?php
	if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class Production_order extends CI_Controller {
		///////////la primera opcion del orden de produccion
		public function ensamblaUno() {
			session_start();
			$pproduccion=false;
			$this->load->model('Orden_compra');
			$this->load->model('Orden_produccion');

			if(isset($_SESSION["productos"])) {
				//si no tienes el producto(materiales) suficiente para producir
				foreach($_SESSION["productos"] as $idProducto=>$datos) {
					list($cantidad,$unidad,$textounidad)=explode("_",$datos);
					$componentes=$this->componentes($idProducto);

					foreach($componentes as $key=>$r) {
						list($idp,$nombrep,$unidadp,$cantidadp)=explode("_",$key);
						$es=$this->Orden_produccion->esMaterial($idp);
						
						//echo 'r'.$es[0]->tipo_producto;
						//if($es->tipo_producto == 2){
							$cantAlmacenResp = $this->Orden_produccion->cantAlma($idp);
							$cantAlam = $cantAlmacenResp[0]->cantidad - $cantAlmacenResp[0]->ocupados;
						
							if(($cantidadp * $cantidad) > $cantAlam){
								echo  "No tienes suficientes ".$nombrep." para producir el producto, utiliza la opción de generar ordenes de compra.";	
								return false;
							} 
						/*}else{
								foreach($_SESSION["materiales"] as $idProductomaterial=>$material) {
								//	print_r($_SESSION["productos"]);
								//	print_r($_SESSION["materiales"]);
									list($cantidad,$unidad,$pedido)=explode("_",$material);
									$producto=$this->Orden_produccion->datosproducto($idProductomaterial);
									$es=$this->Orden_produccion->esMaterial($idp);
								//	echo $cantidad;
								//	print_r($producto);
								//	echo 'material'.$material;
								//	echo 'pedido'.$pedido;
									if($pedido>0) {
										echo  "No tienes suficientes ".$producto->nombre." para producir el producto, utiliza la opción de generar ordenes de compra".$pedido;	
										return false;
									}
								}
						} */
					}
			}
			///validacion viejita
			/*foreach($_SESSION["materiales"] as $idProductomaterial=>$material)
			{
				list($cantidad,$unidad,$pedido)=explode("_",$material);
				$producto=$this->Orden_produccion->datosproducto($idProductomaterial);
				if($pedido>0)
				{
					echo  "No tienes suficientes ".$producto->nombre." para producir el producto, utiliza la opción de generar ordenes de compra";	
					return false;
				}
			} */

			mysql_query("SET AUTOCOMMIT=0");
			mysql_query("BEGIN");
			foreach($_SESSION["productos"] as $idProducto=>$datos) {			 
				list($cantidad,$unidad,$textounidad)=explode("_",$datos);
				$componentes=$this->componentes($idProducto);

				foreach($componentes as $key=>$r) {
					list($idp,$nombrep,$unidadp,$cantidadp)=explode("_",$key);
					//actualizar stock materiales
					$q2=mysql_query("select s.cantidad from mrp_stock s where  s.idProducto=".$idp." and  s.idAlmacen=".$_SESSION["almacen"]);
					$row2 = mysql_fetch_object($q2);
					$cantidadx=$row2->cantidad;
					$q3=mysql_query("select s.ocupados+".$cantidad." ocupados from mrp_stock s where  s.idProducto=".$idp." and  s.idAlmacen=".$_SESSION["almacen"]);
					$row3 = mysql_fetch_object($q3);
					$ocupadosx=$row3->ocupados;

					//if($cantidadx>=$ocupadosx){
						//	if(mysql_num_rows($q2)>0){
							$updatestock="Update mrp_stock set ocupados=ocupados+".($cantidad*$cantidadp)." where  idAlmacen=".$_SESSION["almacen"]." and idProducto=".$idp;
						//	}else{
							//		$updatestock="insert into mrp_stock(idProducto,cantidad,idAlmacen,idUnidad,ocupados) values(".$idProducto.",0,".$_SESSION["almacen"].",1,".($cantidad*$cantidadp).")";
						//	}
							mysql_query($updatestock);	
						/*}else{
							echo  "-No tienes suficientes ".$producto->nombre." para producir el producto.-";
							$c = "ROLLBACK";
							mysql_query($c);
							return false;
						} */
					}
						$pproduccion=true;
					//end actualizar stock
			}	 
                 mysql_query("COMMIT");

				if($pproduccion)
				{
				echo 1;
				return true;
				}else {  echo  "No tienes productos para producir agregados"; return false;}
		}
		else
		{
				echo  "No has agregado productos para producir";
				return false;
		}
		if(!$pproduccion){ echo  "No tienes productos para producir agregados"; return false; }

	}

//////////////la segunda opcion de la orden de produccion
		public function ensambla()
	{
		session_start();
		$pproduccion=false;
		$this->load->model('Orden_compra');
		$this->load->model('Orden_produccion');	
		if(isset($_SESSION["productos"]))
		{
		//si no tienes el producto(materiales) suficiente para producir		
	
			
			foreach($_SESSION["productos"] as $idProducto=>$datos)
			{	
				list($cantidad,$unidad,$textounidad)=explode("_",$datos);			
				$componentes=$this->componentes($idProducto);
			
				foreach($componentes as $key=>$r)
					{
						list($idp,$nombrep,$unidadp,$cantidadp)=explode("_",$key);
						$es=$this->Orden_produccion->esMaterial($idp);
						
						//echo 'r'.$es[0]->tipo_producto;
						//if($es->tipo_producto == 2){
							$cantAlmacenResp = $this->Orden_produccion->cantAlma($idp);
							$cantAlam = $cantAlmacenResp[0]->cantidad - $cantAlmacenResp[0]->ocupados;
						
							if(($cantidadp * $cantidad) > $cantAlam){
								echo  "No tienes suficientes ".$nombrep." para producir el producto, utiliza la opción de generar ordenes de compra.";	
								return false;
							} 
						/*}else{
								foreach($_SESSION["materiales"] as $idProductomaterial=>$material)
								{
								//	print_r($_SESSION["productos"]);
								//	print_r($_SESSION["materiales"]);
									list($cantidad,$unidad,$pedido)=explode("_",$material);
									$producto=$this->Orden_produccion->datosproducto($idProductomaterial);
									$es=$this->Orden_produccion->esMaterial($idp);
								//	echo $cantidad;
								//	print_r($producto);
								//	echo 'material'.$material;
								//	echo 'pedido'.$pedido;
									if($pedido>0)
									{
										echo  "No tienes suficientes ".$producto->nombre." para producir el producto, utiliza la opción de generar ordenes de compra".$pedido;	
										return false;
									}
									
								} 						

						} */

					}

			}



			//exit(); Valdiacion viejita
			/*foreach($_SESSION["materiales"] as $idProductomaterial=>$material)
			{
			//	print_r($_SESSION["productos"]);
			//	print_r($_SESSION["materiales"]);
				list($cantidad,$unidad,$pedido)=explode("_",$material);
				$producto=$this->Orden_produccion->datosproducto($idProductomaterial);
			//	echo $cantidad;
			//	print_r($producto);
			//	echo 'material'.$material;
			//	echo 'pedido'.$pedido;
				if($pedido>0)
				{
					echo  "No tienes suficientes ".$producto->nombre." para producir el producto, utiliza la opción de generar ordenes de compra".$pedido;	
					return false;
				}
				
			} 	*/

	mysql_query("SET AUTOCOMMIT=0");
 	mysql_query("BEGIN");

			foreach($_SESSION["productos"] as $idProducto=>$datos)
			{
				list($cantidad,$unidad,$textounidad)=explode("_",$datos);			
				$componentes=$this->componentes($idProducto);
				foreach($componentes as $key=>$r)
					{
						list($idp,$nombrep,$unidadp,$cantidadp)=explode("_",$key);
						//actualizar stock materiales
						$q2=mysql_query("select s.cantidad-ocupados cantidad from mrp_stock s where  s.idProducto=".$idp." and  s.idAlmacen=".$_SESSION["almacen"]);
						$row2 = mysql_fetch_object($q2);
						$x=$row2->cantidad;
						/*if($x<($cantidad*$cantidadp)){
							echo "No tienes suficientes productos para producir, revise su stock.";
							mysql_query("ROLLBACK");
							return false;
						}else{ */
							$query=mysql_query("select s.cantidad cantidad from mrp_stock s where  s.idProducto=".$idp." and  s.idAlmacen=".$_SESSION["almacen"]);
							$rows = mysql_fetch_object($query);
							$y=$rows->cantidad;
							$updatestock="Update mrp_stock set cantidad=".($y-($cantidad*$cantidadp))." where  idAlmacen=".$_SESSION["almacen"]." and idProducto=".$idp;
							mysql_query($updatestock);
						//}
	
						//var_dump($updatestock);
						//mysql_query($updatestock);	
						//end actualizar stock materiales
					}
					//actualizar stock
						$q2=mysql_query("select s.cantidad cantidad from mrp_stock s where  s.idProducto=".$idProducto." and  s.idAlmacen=".$_SESSION["almacen"]);
						$row2 = mysql_fetch_object($q2);
						if(mysql_num_rows($q2)>0)
						{
							$updatestock="Update mrp_stock set cantidad=".($row2->cantidad+$cantidad)." where  idAlmacen=".$_SESSION["almacen"]." and idProducto=".$idProducto;
						}
						else 
						{	
							$updatestock="insert into mrp_stock(idProducto,cantidad,idAlmacen,idUnidad) values(".$idProducto.",".$cantidad.",".$_SESSION["almacen"].",1)";	
						}
						//var_dump($updatestock);;
						mysql_query($updatestock);	
						$pproduccion=true;
					//end actualizar stock
			} 
			mysql_query("COMMIT");
				if($pproduccion)
				{
				echo 1;
				return true;
				}else {  echo  "No tienes productos para producir agregados"; return false;}
		}
		else
		{
				echo  "No has agregado productos para producir";
				return false;
		}
		if(!$pproduccion){ echo  "No tienes productos para producir agregados"; return false; }
		
	}
////////////////////////////funcion de cambio de status y cambios en el inventario
public function terminacancelado(){

 $status=$_POST["estatus"];
		session_start();
		$pproduccion=false;
		$this->load->model('Orden_compra');
		$this->load->model('Orden_produccion');	
		if(isset($_SESSION["productos"]))
		{
		 	switch($status){
		 		case 2: 		
		 			foreach($_SESSION["productos"] as $idProducto=>$datos)
					{
						
						list($cantidad,$unidad,$textounidad)=explode("_",$datos);			
						$componentes=$this->componentes($idProducto);
						foreach($componentes as $key=>$r)
							{
								list($idp,$nombrep,$unidadp,$cantidadp)=explode("_",$key);
									$x=1;
									$almacen = $_SESSION["almacen"];
									$this->Orden_produccion->termina($idp,$cantidad,$cantidadp,$almacen,$idProducto,$x);
							}
								$x=2;
								$almacen = $_SESSION["almacen"];
								$this->Orden_produccion->termina($idp,$cantidad,$cantidadp,$almacen,$idProducto,$x);

								$pproduccion=true;
							//end actualizar stock
					} 
		 				break;
		 		case 3: 
		 			foreach($_SESSION["productos"] as $idProducto=>$datos)
					{
					 
						list($cantidad,$unidad,$textounidad)=explode("_",$datos);			
						$componentes=$this->componentes($idProducto);
						foreach($componentes as $key=>$r)
							{
								
								list($idp,$nombrep,$unidadp,$cantidadp)=explode("_",$key);
								$almacen = $_SESSION["almacen"];
								$this->Orden_produccion->cancelado($idp,$cantidad,$cantidadp,$almacen);		

							}
								$pproduccion=true;
							//end actualizar stock
					}	 
		 				break;
		 	}


				if($pproduccion)
				{
				echo 1;
				$this->Orden_produccion->cambiaestatus($_POST["id"],$_POST["estatus"]);
				return true;
				}else {  echo  "No tienes productos para producir agregados"; return false;}
		}
		else
		{
				echo  "No has agregado productos para producir-----";
				return false;
		}
		if(!$pproduccion){ echo  "No tienes productos para producir agregados******"; return false; }

}
///////////////////////////////

	public function cargaalmacenes()
	{
		$this->load->model('Orden_produccion');	
		$select_suc='<select class="long-input form-control" id="almacen">';
		$select_suc.='<option value="">-Seleccione un almacen-</option>';
		foreach($this->Orden_produccion->almacenes($_POST["id"]) as $almacen)
		{
			$select_suc.='<option value="'.$almacen->idAlmacen.'">'.utf8_decode($almacen->nombre).'</option>';
		}
		$select_suc.='</select>';	
		
		echo $select_suc;	
	}
/////////////////////
	public function quitaelementos()
	{
		session_start();
		//$productos=explode(",",$_POST["productos"]);
		foreach($_POST["productos"] as $producto)
		{
			if(array_key_exists($producto,$_SESSION["productos"])){unset( $_SESSION["productos"][$producto]);}
			if(array_key_exists($producto,$_SESSION["materiales"])){unset($_SESSION["materiales"][$producto]);}	
			
			
			
		}
		echo $this->orden();
		
	}
	
	public function createorder()
	{
		session_start();	
		$this->load->model('Orden_produccion');
		$this->Orden_produccion->createorder($_SESSION["productos"],$_POST["ordenes"],$_POST["elaboro"],$_SESSION["sucursal"],$_SESSION["almacen"],$_POST["fecha_inicio"],$_POST["x"],$_POST["fecha_fin"]);	
	}

	public function createordercompra()
	{
		session_start();	
		$this->load->model('Orden_produccion');
		$this->Orden_produccion->createordercompra($_SESSION["productos"],$_POST["ordenes"],$_POST["elaboro"],$_SESSION["sucursal"],$_SESSION["almacen"],$_POST["fecha_inicio"],$_POST["x"],$_POST["fecha_fin"]);	
	}
	
	public function cambiaestatus()
	{
		$this->load->model('Orden_produccion');
		echo $this->Orden_produccion->cambiaestatus($_POST["id"],$_POST["estatus"]);
	}
	public function etapaProceso($id,$cadti){
		$idord=$_POST['idord'];
		$this->load->model('Orden_produccion');
		$data["procesos"]=$this->Orden_produccion->procesos($id,$cadti,$idord);

		echo json_encode($data['procesos']);
	}

	public function detalle($id)
	{
		session_start();
		$_SESSION["productos"]=array();
		$this->load->model('Orden_produccion');
		
		$orden=$this->Orden_produccion->giveOrdenProduccion($id);

		$_SESSION["sucursal"]=$orden->idSuc;
		$_SESSION["almacen"]=$orden->idAlmacen;
		
		$productos=$this->Orden_produccion->giveproductosOrden($id);
		foreach($productos as $p)
		{
			$producto=$p->idProducto;
			$cantidad=$p->cantidad;
			$unidad=$p->idUnidad;
			$textounidad=$p->compuesto;
			$_SESSION["productos"][$producto]=$cantidad."_".$unidad."_".$textounidad;
		}				

		$data["sucursal"]=$orden->sucursal;
		$data["almacen"]=$orden->almacen;
		$data["detalle"]=$this->orden();
		$data["id"]=$id;
		$data["estatus"]=$orden->estatus;
		$this->load->view('production_order/detalle',$data);
	}
	
	public function index($modo="1",$filtro=1,$pagina=1,$elimina=false)
	{
	
		$filtro=$_REQUEST['filtro'];
		if($filtro==''){
			$filtro=1;}
		
		session_start();
		$_SESSION["productos"]=array();
			
		$this->load->helper('url');	
		$this->load->model('Orden_produccion');
		  $grid=$this->Orden_produccion->grid($pagina,$filtro);
		  $encabezado='';$busquedas='';  
		  $filas='';
			$i=0;
		if($filtro != 1 && !isset($grid["data"][0]))
		{
			exit('false');
		}
			foreach($grid["data"] as $fila)
			{
				$encabezado='';$busquedas='';
				if($i%2==0){$filas.='<tr class="busqueda_fila">';}
				else{$filas.='<tr class="busqueda_fila2">';}
				$e=0;
				foreach($fila as $campo=>$valor)
				{
				if($e==0){$id=$valor;}
				if($e==1)
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
				if($e==2 || $e==3){
					$cadena_hora_fecha = $valor;
					list($fecha, $hora) = explode(' ', $cadena_hora_fecha);
					list($ano, $mes, $dia) = explode('-', $fecha);
					list($hour, $minuto, $segundo) = explode(':', $hora);
					$valor = $dia . "/" . $mes . "/" . $ano; 
				}
	$encabezado.='<td align="center" class="nmcatalogbusquedatit">'.str_replace("_"," ",$campo).'</td>';
	$busquedas.='<td><input class="input_filtro nmcatalogbusquedainputtext" onkeydown="busquedaProduccion(event,this.value,\''.$campo.'\')"></td>';
if($modo==1){					
$filas.='<td class="nmcatalogbusquedacont_1"><a class="a_registro" href="'.base_url().'index.php/production_order/detalle/'.$id.'">'.($valor).'</a></td>';
}
if($modo==2){					
$filas.='<td class="nmcatalogbusquedacont_2"><a class="a_registro" onclick="Elimina('.$id.',\'mrp_producto\');">'.utf8_decode($valor).'</a></td>';
}

				$e++;
				}
				$filas.='</tr>';
			$i++;
			}
if($i<10)
{
	$encabezado='<td class="nmcatalogbusquedatit" align="center">ID</td>';
	$encabezado.='<td class="nmcatalogbusquedatit" align="center">Fecha</td>';
	$encabezado.='<td class="nmcatalogbusquedatit" align="center">Fecha Produccion</td>';
	$encabezado.='<td class="nmcatalogbusquedatit" align="center">Feha Finalizacion</td>';
	$encabezado.='<td class="nmcatalogbusquedatit" align="center">Orden generada por</td>';
	$encabezado.='<td class="nmcatalogbusquedatit" align="center">Almacen</td>';
	$encabezado.='<td class="nmcatalogbusquedatit" align="center">Estatus</td>';


	$e=6;
	//$i++;
		for($k=0;$k<=10;$k++)
		{
				if($i%2==0){$filas.='<tr class="nmcatalogbusquedacont_1">';}else{$filas.='<tr class="nmcatalogbusquedacont_2">';}
				for($j=0;$j<=$e;$j++){$filas.='<td></td>';}
				$filas.='</tr>';
		$i++;
		}	
}			
			
if($pagina==1){$pag_anterior=1;}else{$pag_anterior=$pagina-1;}
if(($pagina+1)>$grid["paginas"]){$pag_siguiente=$pagina;}else{$pag_siguiente=$pagina+1;}			

$link_anterior='../../../../webapp/modulos/mrp/index.php/production_order/index/'.$modo.'/'.$filtro.'/'.$pag_anterior;
$link_siguiente='../../../../webapp/modulos/mrp/index.php/production_order/index/'.$modo.'/'.$filtro.'/'.$pag_siguiente;

$base_url=str_replace("modulos/mrp/","",base_url());

$catalogo='
<link rel="stylesheet" type="text/css" href="../../../libraries/bootstrap/dist/css/bootstrap.min.css" />
<link rel="stylesheet" type="text/css" href="../css/imprimir_bootstrap.css" />
<link rel="stylesheet" type="text/css" href="../../../../libraries/bootstrap/dist/css/bootstrap.min.css" />
<link rel="stylesheet" type="text/css" href="../../css/imprimir_bootstrap.css" />
<style type="text/css">
	a[href]:after {
	   content: none !important;
	}
    .btnMenu{
        border-radius: 0; 
        width: 100%;
        margin-bottom: 0.3em;
        margin-top: 0.3em;
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
    @media print{
		#imprimir,#filtros,#excel, .botones, input[type="button"], button, button[type="button"], .btnMenu{
			display:none;
		}
		.table-responsive{
			overflow-x: unset;
		}
		#imp_cont{
			width: 100% !important;
		}
	}
</style>
<div class="container" style="width:100%">
			<div class="row">
				<div class="col-sm-1">
				</div>
				<div class="col-sm-10" id="imp_cont">
					<h3 class="nmwatitles text-center">
						'.$grid['nombre'].'<br>
						<img class="nmwaicons" src="'.$base_url.'/netwarelog/design/default/pag_ant.png" onclick="paginacionGrid(\''.$link_anterior.'\');">
						<a href="javascript:window.print();"><img class="nmwaicons" src="'.$base_url.'/netwarelog/design/default/impresora.png" border="0"></a>
						<img class="nmwaicons" src="'.$base_url.'/netwarelog/design/default/pag_sig.png" onclick="paginacionGrid(\''.$link_siguiente.'\');" >
					</h3>';
					

$catalogo.='
<div class="row">
	<div class="col-sm-4 col-sm-offset-8">
		<input type="button" class="btn btn-primary btnMenu" value="Generar nueva orden de produccion" onclick="window.location=\''.base_url().'index.php/production_order/index2\'">
	</div>
</div>';						
				
$catalogo.='
<div class="row">
	<div class="col-xs-12 tablaResponsiva">
		<div class="table-responsive">
			<table class="busqueda" border="1" cellpadding="3" cellspacing="1" width="50%">
				<tr class="tit_tabla_buscar" >'.$encabezado.'</tr>			
				<tr class="titulo_filtros" title="Segmento de búsqueda">'.$busquedas.'</tr>
				'.$filas.'
			</table>
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
///////////////////////////////////////////////////////	
public function unidades()
{
		$this->load->model('Orden_produccion');
		$unidades=$this->Orden_produccion->unidades($_POST["producto"]);

		$selec_unidad='<select id="unidad" class="long-input form-control">';
		$selec_unidad.='<option value="">-Seleccione la unidad-</option>';
		foreach($unidades as $unidad)
		{
			list($id,$texto)=explode("_",$unidad);
			$selec_unidad.='<option   value="'.$id.'">'.($texto).'</option>';
		}
		$selec_unidad.='</select>';
	echo $selec_unidad;
}	
	///////////////////////////////////////////////////////	
public function index2()
	{
		$this->load->model('Orden_produccion');
		$this->load->model('Producto');			
		$this->load->model('Inventario');			
		
		$selec_productos='<select id="producto" class="long-input form-control" onchange="Cargaunidades(this.value);">';
		$selec_productos.='<option value="">-Seleccione el producto-</option>';
		foreach($this->Producto->listaProductosProduccion(0) as $producto)
		{
			$selec_productos.='<option value="'.$producto->idProducto.'">'.($producto->nombre).'</option>';
		}
		$selec_productos.='</select>';
		$data["productos"]=$selec_productos;
		
		$selec_unidad='<select id="unidad" class="long-input form-control">';
		$selec_unidad.='<option value="">-Seleccione la unidad-</option>';
		foreach($this->Producto->listaUnidades() as $unidad)
		{
			if($unidad->idUni==1){$op='selected';}else{$op='';};
			$selec_unidad.='<option '.$op.'  value="'.$unidad->idUni.'">'.utf8_decode($unidad->compuesto).'</option>';
		}
		$selec_unidad.='</select>';
		$data["unidades"]=$selec_unidad;
		$data["orden_produccion"]=$this->orden();

		$select_suc='<select id="sucursal" class="long-input form-control" onchange="cargaalmacenes(this.value);">';
		$select_suc.='<option value="">-Seleccione una sucursal-</option>';
		foreach($this->Inventario->sucursales() as $suc)
		{
			$select_suc.='<option value="'.$suc->idSuc.'">'.utf8_decode($suc->nombre).'</option>';
		}
		$select_suc.='</select>';

		$data["sucursales"]=$select_suc;
		
		$this->load->view('production_order/index',$data);
	}
//////////////////////////////////////////////
public function creaorden()
	{
		session_start();
		if(!isset($_SESSION["productos"]))
		{
			$_SESSION["productos"]=array();
			$_SESSION["productos"][$_POST["producto"]]=$_POST["cantidad"]."_".$_POST["unidad"]."_".$_POST["textounidad"];
			$_SESSION["sucursal"]=$_POST["sucursal"];
			$_SESSION["almacen"]=$_POST["almacen"];
		}
		else
		{
			$_SESSION["sucursal"]=$_POST["sucursal"];	
			$_SESSION["almacen"]=$_POST["almacen"];	
			$_SESSION["productos"][$_POST["producto"]]=$_POST["cantidad"]."_".$_POST["unidad"]."_".$_POST["textounidad"];
		}
		echo $this->orden();
	}
//////////////////////////////////////////////
public function orden()
	{
		
	$this->load->model('Orden_produccion');		
	$string_order='
	<table border="1" width="100%" align="center">
    <tr class="tit_tabla_buscar">
    	<td align="center" class="nmcatalogbusquedatit"></td>
    	<td align="center" width="15%" class="nmcatalogbusquedatit">Cantidad</td>
    	<td align="center" width="15%" class="nmcatalogbusquedatit">Unidad</td>
		<td align="center" width="16%" class="nmcatalogbusquedatit">Código</td>
		<td align="center" width="19%" class="nmcatalogbusquedatit">Producto</td>
     	<td align="center" width="12%" class="nmcatalogbusquedatit">Minimo</td>
     	<td align="center" width="13%" class="nmcatalogbusquedatit">Máximo</td>
     	<td align="center" width="13%" class="nmcatalogbusquedatit">Existencia</td>
	<!--	<td align="center" width="10%">Pedido</td>
		<td align="center" width="15%">Pedido + ajuste stock</td> -->
    </tr>';
    $i=0;
	//session_start();
	$_SESSION["materiales"]=array();
	if(isset($_SESSION["productos"]))
	{
		foreach($_SESSION["productos"] as $idProducto=>$datos)
		{
				list($cantidad,$unidad,$textounidad)=explode("_",$datos);
				if($i%2==0){$string_order.='<tr class="busqueda_fila">';} else{ $string_order.='<tr class="busqueda_fila2">';}
				$string_order.=$this->reccomponentes($idProducto,$cantidad,$textounidad);
				$string_order.='</tr>'; 
		$i++;
		}
	}
	if($i<10)
	{
	for($f=$i;$f<=10;$f++)
		{
	if($f%2==0){$string_order.='<tr class="busqueda_fila">';} else{ $string_order.='<tr class="busqueda_fila2">';}
		$string_order.='
			<td align="center" class="nmcatalogbusquedacont_1"></td>
			<td align="center" class="nmcatalogbusquedacont_1"></td>
			<td align="center" class="nmcatalogbusquedacont_1"></td>
			<td align="center" class="nmcatalogbusquedacont_1"></td>
			<td align="center" class="nmcatalogbusquedacont_1"></td>
			<td align="center" class="nmcatalogbusquedacont_1"></td>
			<td align="center" class="nmcatalogbusquedacont_1"></td>
			<td align="center" class="nmcatalogbusquedacont_1"></td>
		<!--	<td align="center" class="nmcatalogbusquedacont_1"></td>
			<td align="center" class="nmcatalogbusquedacont_1"></td> -->     
		</tr>';
		}
	}
    
$string_order.='</table>';
return $string_order;
	} 
	///////////////////////////////// 

	function timeline($id){
		$this->load->model('Orden_produccion');
		
		$data["desgloce"]=$this->Orden_produccion->desgloce($id);
		$data["idord"]=$id;
		//var_dump($data['desgloce'][0]['ides']);
		$data["etapas"]=$this->Orden_produccion->etapas($data['desgloce'][0]['ides']);
		$this->load->view('production_order/timeline',$data);

	}
function componentes($id)
	{
		$arreglo=array();
		$this->load->model('Orden_compra');
		$resultado=$this->Orden_compra->compruebaProductoCompuesto($id);
		
		if(is_array($resultado))
		{
		
		foreach($resultado as $comp): 
			
		
			$arreglo[$comp->idMaterial."_".$comp->Nom."_".$comp->compuesto."_".$comp->cantidad] = $this->componentes($comp->idMaterial);
		
		
		endforeach;	
		
		}
		
		return $arreglo;
	}
	/////////////////////////////////
function reccomponentes($idProducto,$cantidad,$textounidad,$inicio=true)
{

	$string_order='';
	$producto=$this->Orden_produccion->datosproducto($idProducto);
	$existencia=$this->Orden_produccion->existencia($idProducto,$_SESSION["sucursal"],$_SESSION["almacen"]);

if($cantidad>$existencia)
{
//	echo $producto->nombre;
//	$this->quitaelementos();
$pedido=$cantidad-$existencia;
$ajuste=$producto->minimo;
}
else{$pedido=0;$ajuste=0;}
  
  
$re=$this->componentes($idProducto);	

	
if(count($re)>0)
{		
		$string_order.='<td align="center" ><input type="checkbox" id="ck_'.$idProducto.'" class="ck" value="'.$idProducto.'"></td>
		<td colspan="9">';
		$string_order.='<div class="accordion">
  <h3>'; 
 /* if($cantidad>$existencia){
	$string_order.='<input class="verde" type="hidden" val="1" >';
	$st=' style="color:#ff0000;" '; 
}else{
	$string_order.='<input class="azul" type="hidden" val="1" >';
	$st=''; 
} */
  
  $string_order.='
  <table border="0" width="100%" align="center">
		<tr>
				
				<td align="center" width="15%">'.$cantidad.'</td>
				<td align="center" width="15%">'.$textounidad.'</td>
				<td align="center" width="16%">'.$producto->codigo.'</td>
				<td align="center" width="19%">'.$producto->nombre.'</td>
				<td align="center" width="12%">'.$producto->minimo.'</td>
				<td align="center" width="13%">'.$producto->maximo.'</td>
				<td align="center" width="13%">'.$existencia.'</td>

	 </tr>
 </table>';
 				//<td align="center" width="10%">'.$pedido.'</td> lines contiguas de la 622
				//<td align="center" width="15%">'.($ajuste+$pedido).'</td>
   $string_order.='</h3>
	  <div>
				  <table border="0" width="100%" align="center">';	
					
		 $string_order.='<tr class="tit_tabla_buscar">
    	<td align="center" ></td>
    	<td align="center" width="15%" class="nmcatalogbusquedatit">Cantidad</td>
    	<td align="center" width="15%" class="nmcatalogbusquedatit">Unidad</td>
		<td align="center" width="16%" class="nmcatalogbusquedatit">Código</td>
		<td align="center" width="19%" class="nmcatalogbusquedatit">Producto</td>
     	<td align="center" width="12%" class="nmcatalogbusquedatit">Minimo</td>
     	<td align="center" width="13%" class="nmcatalogbusquedatit">Máximo</td>
     	<td align="center" width="13%" class="nmcatalogbusquedatit">Existencia</td>
<!--		<td align="center" width="10%">Pedido</td>
		<td align="center" width="15%">Pedido + ajuste stock</td> -->
    </tr>';
					
					
					foreach($re as $key=>$r)
					{
						list($idp,$nombrep,$unidadp,$cantidadp)=explode("_",$key);
						//$productop=$this->Orden_produccion->datosproducto($idp);
						//$existenciap=$this->Orden_produccion->existencia($idp,$_SESSION["sucursal"]);
						$string_order.='<tr>';

					
						$string_order.=$this->reccomponentes($idp,($cantidad*$cantidadp),$unidadp,false);
						
						$string_order.='</tr>';
					}	
				 $string_order.='</table>
	  </div>
  </div>';
  $string_order.='</td>';
 
 
}
	else{  
		if(array_key_exists($producto->idProducto, $_SESSION["materiales"])){

			list($cant,$text,$x)=explode("_",$_SESSION["materiales"][$producto->idProducto]);
				$_SESSION["materiales"][$producto->idProducto]=($cantidad+$cant)."_".$textounidad."_".($pedido+$ajuste);

	}else{
			$_SESSION["materiales"][$producto->idProducto]=$cantidad."_".$textounidad."_".($pedido+$ajuste);
	}		

			if($inicio){
			$string_order.='<td align="center" ><input type="checkbox" id="ck_'.$producto->idProducto.'" class="ck" value="'.$producto->idProducto.'"></td>';
			}
else{$string_order.='<td align="center"></td>';}
if($cantidad>$existencia){
	$string_order.='<input class="errexist" id="error" type="hidden" val="1" >';
	$st=' style="color:#ff0000;" '; 
}else{
	$st=''; 
}				
				$string_order.='<td '.$st.' align="center" width="15%" class="nmcatalogbusquedacont_1">'.$cantidad.'</td>
				<td '.$st.'  align="center" width="15%" class="nmcatalogbusquedacont_1">'.$textounidad.'</td>
				<td '.$st.'  align="center" width="16%" class="nmcatalogbusquedacont_1">'.$producto->codigo.'</td>
				<td '.$st.'  align="center" width="19%" class="nmcatalogbusquedacont_1">'.$producto->nombre.'</td>
				<td '.$st.'  align="center" width="12%" class="nmcatalogbusquedacont_1">'.$producto->minimo.'</td>
				<td '.$st.'  align="center" width="13%" class="nmcatalogbusquedacont_1">'.$producto->maximo.'</td>
				<td '.$st.'  align="center" width="13%" class="nmcatalogbusquedacont_1">'.$existencia.'</td>
				';
				//<td align="center" width="10%">'.$pedido.'</td> lines continuas de la 676
				//<td align="center" width="15%">'.($pedido+$ajuste).'</td>
	}

		return $string_order;
	
	
}
///////////////////////////////////
public function puede()
{
	session_start();
	if(isset($_SESSION["materiales"]))
	{
		echo count($_SESSION["materiales"]);
	}else{echo 0;}
}		 
/////////////////	 
public function explosion($x)
{
$this->load->model('Orden_produccion');
$this->load->helper('url');		
session_start();
 $i=0;	
 $total=0;
//$data["materiales"]=$_SESSION["materiales"];
//<input type="checkbox" id="all" onclick="Todos();">
$ex='<table border="0" width="100%" align="center">';					  	
$ex.='<tr class="tit_tabla_buscar">
    	<td align="center" class="nmcatalogbusquedatit"></td>
    	<td align="center" width="8%" class="nmcatalogbusquedatit">Cantidad</td>
    	<td align="center" width="8%" class="nmcatalogbusquedatit">Unidad</td>
		<td align="center" width="8%" class="nmcatalogbusquedatit">Código</td>
		<td align="center" width="26%" class="nmcatalogbusquedatit">Producto</td>
     	<td align="center" width="26%" class="nmcatalogbusquedatit">Proveedor</td>
     	<td align="center" width="8%" class="nmcatalogbusquedatit">Cantidad Proveedor</td>
     	<td align="center" width="8%" class="nmcatalogbusquedatit">Unidad proveedor</td>
     	<td align="center" width="8%" class="nmcatalogbusquedatit">Costo unitario</td>
     	<td align="center" width="8%" class="nmcatalogbusquedatit">Subtotal</td>
    </tr>';
	

	
foreach($_SESSION["materiales"] as $idProducto=>$material)
{ 
list($cantidad,$unidad,$pedido)=explode("_",$material);
$producto=$this->Orden_produccion->datosproducto($idProducto);
$conversion = $this->Orden_produccion->conversion($unidad);
			//echo $conversion->conversion.'orfjoejfr';			
if($i%2==0){$ex.='<tr class="busqueda_fila">';} else{ $ex.='<tr class="busqueda_fila2">';}
		
		$select='<select id="proveedor_'.$idProducto.'" onchange="CotizaProveedor(this.value,'.$idProducto.','.$pedido.');" class="nminputselect">';
		$check='<input type="checkbox" class="ck" value="'.$idProducto.'" id="check_'.$idProducto.'">';
		$costo='';
		$subtotal='';
		$unidadProveedor='';
		$i=0;
		foreach ($this->Orden_produccion->listaproveedores($idProducto) as $row)
		{
			$datos=$this->Orden_produccion->mejorproveedor($idProducto);			
			$unidadProveedor=$datos->compuesto;
			$costo="$".number_format($datos->costo,2,".",",");
			$subtotal="$".number_format(($datos->costo*($cantidad / $conversion->conversion)),2,".",",");	
			$propiedad='';

			if($datos->idProveedor==$row->idPrv){$propiedad='selected';}
			$select.='<option value="'.$row->idPrv.'" '.$propiedad.'>'.utf8_decode($row->razon_social).'</option>';	
		}
		if($select!='<select id="proveedor_'.$idProducto.'" onchange="CotizaProveedor(this.value,'.$idProducto.','.$pedido.');" class="nminputselect">'){
				 $hiddenvalue=$datos->idUnidad;
			$select.='</select>';}
		else{$select='Aún no existe proveedor para este producto';$check=''; $hiddenvalue=''; }
		$total+=str_replace(",","",str_replace("$","",$subtotal));
		$ex.='	
			<td align="center" class="nmcatalogbusquedacont_1">'.$check.'</td>
			<td align="center" class="nmcatalogbusquedacont_1"><span>'.$cantidad.'</span></td>
			<td align="center" class="nmcatalogbusquedacont_1">'.$unidad.'</td>
			<td align="center" class="nmcatalogbusquedacont_1">'.$producto->codigo.'</td>
			<td align="center" class="nmcatalogbusquedacont_1">'.$producto->nombre.'</td>
			<td align="center" class="nmcatalogbusquedacont_1">'.$select.'<img class="preload" id="preloader_'.$idProducto.'" src="'.base_url().'/images/preloader.gif"></td>
			<td align="center" class="nmcatalogbusquedacont_1"><span id="cantidad_'.$idProducto.'">'.($cantidad / $conversion->conversion).'</span></td>
			<td align="center" class="nmcatalogbusquedacont_1"><input type="hidden" id="idunidad_'.$idProducto.'" value="'.$hiddenvalue.'"><span id="unidad_'.$idProducto.'">'.$unidadProveedor.'</span></td>
			<td align="center" class="nmcatalogbusquedacont_1"><span id="costo_'.$idProducto.'">'.$costo.'</span></td>
			<td align="center" class="nmcatalogbusquedacont_1"><span id="subtotal_'.$idProducto.'">'.$subtotal.'</span></td>     
		</tr>';
		
$i++;
}	
if($i<10)
	{
	for($f=$i;$f<10;$f++)
		{
	if($f%2==0){$ex.='<tr class="busqueda_fila">';} else{ $ex.='<tr class="busqueda_fila2">';}
		$ex.='
			<td align="center" class="nmcatalogbusquedacont_1"></td><td align="center" class="nmcatalogbusquedacont_1"></td><td align="center" class="nmcatalogbusquedacont_1"></td><td align="center" class="nmcatalogbusquedacont_1"></td>
			<td align="center" class="nmcatalogbusquedacont_1"></td><td align="center" class="nmcatalogbusquedacont_1"></td><td align="center" class="nmcatalogbusquedacont_1"></td><td align="center" class="nmcatalogbusquedacont_1"></td>
			<td align="center" class="nmcatalogbusquedacont_1"></td>  
			<td align="center" class="nmcatalogbusquedacont_1"></td>       
		</tr>';
		}
	}
	
$ex.='<tr class="tit_tabla_buscar">
    	<td align="center" class="nmcatalogbusquedacont_1"></td>
    	<td align="center" width="8%" class="nmcatalogbusquedacont_1"></td>
    	<td align="center" width="8%" class="nmcatalogbusquedacont_1"></td>
		<td align="center" width="8%" class="nmcatalogbusquedacont_1"></td>
		<td align="center" width="26%" class="nmcatalogbusquedacont_1"></td>
     	<td align="center" width="26%" class="nmcatalogbusquedacont_1"></td>
     	<td align="center" width="8%" class="nmcatalogbusquedacont_1"></td>
     	<td align="center" width="8%" class="nmcatalogbusquedacont_1"></td>
     	<td align="center" width="8%" class="nmcatalogbusquedacont_1"><h3>Total:</h3></td>
     	<td align="center" width="8%" class="nmcatalogbusquedacont_1"><h3><span id="total">$'.number_format($total,2,".",",").'</span></h3></td>
    </tr>';	
	
	
	
$ex.='<tr>
<td colspan="7"><label>Orden de producción generada por:</label><input readonly value="'.$_SESSION['accelog_login'].'" type="text" id="elaboro" style="width:350px;" class="nminputtext"></td>
<td colspan="2" align="right"><br>
<img class="preload" id="preloader" src="'.base_url().'/images/preloader.gif">
<input type="button" id="btongoc" value="Generar ordenes de produccion" onclick="produccion();" class="nminputbutton_color2">
</td></tr>';		
$ex.='</table><div id="contenido-opc" style="display:none;">
				<table>
        <tr>
          <td>
            <label><strong>Primera</strong></label>
            <p>Este proceso, apartara los insumos para producir el productos y  agregará al inventario los productos terminados una vez que se complete la orden de produccion</p>
          </td>
          <td>
            <input type="radio" value="1" name="opciOrd" id="opcionOrd1">
          </td>
        </tr>
        <tr>
          <td>
            <label><strong>Segunda</strong></label>
            <p>Este proceso, descontará los insumos para producir el productos y  agregará al inventario los productos terminado</p>
          </td>
          <td>
            <input type="radio" value="2" name="opciOrd" id="opcionOrd2">
          </td>
        </tr>
    </table>
				</div>';
$data["materiales"]=$ex;
$this->load->view('production_order/explosion',$data);
}
public function explosionCompra($x)
{
$this->load->model('Orden_produccion');
$this->load->helper('url');		
session_start();
 $i=0;	
 $total=0;
//$data["materiales"]=$_SESSION["materiales"];
//<input type="checkbox" id="all" onclick="Todos();">
$ex='<table border="0" width="100%" align="center">';					  	
$ex.='<tr class="tit_tabla_buscar">
    	<td align="center" class="nmcatalogbusquedatit"></td>
    	<td align="center" width="8%" class="nmcatalogbusquedatit">Cantidad</td>
    	<td align="center" width="8%" class="nmcatalogbusquedatit">Unidad</td>
		<td align="center" width="8%" class="nmcatalogbusquedatit">Código</td>
		<td align="center" width="26%" class="nmcatalogbusquedatit">Producto</td>
     	<td align="center" width="26%" class="nmcatalogbusquedatit">Proveedor</td>
     	<td align="center" width="8%" class="nmcatalogbusquedatit">Cantidad Proveedor</td>
     	<td align="center" width="8%" class="nmcatalogbusquedatit">Unidad proveedor</td>
     	<td align="center" width="8%" class="nmcatalogbusquedatit">Costo unitario</td>
     	<td align="center" width="8%" class="nmcatalogbusquedatit">Subtotal</td>
    </tr>';
	

	
foreach($_SESSION["materiales"] as $idProducto=>$material)
{ 
list($cantidad,$unidad,$pedido)=explode("_",$material);
$producto=$this->Orden_produccion->datosproducto($idProducto);
$conversion = $this->Orden_produccion->conversion($unidad);
			//echo $conversion->conversion.'orfjoejfr';			
if($i%2==0){$ex.='<tr class="busqueda_fila">';} else{ $ex.='<tr class="busqueda_fila2">';}
		
		$select='<select id="proveedor_'.$idProducto.'" onchange="CotizaProveedor(this.value,'.$idProducto.','.$pedido.');" class="form-control">';
		$check='<input type="checkbox" checked="checked" class="ck" value="'.$idProducto.'" id="check_'.$idProducto.'">';
		$costo='';
		$subtotal='';
		$unidadProveedor='';
		$i=0;
		foreach ($this->Orden_produccion->listaproveedores($idProducto) as $row)
		{
			$datos=$this->Orden_produccion->mejorproveedor($idProducto);			
			$unidadProveedor=$datos->compuesto;
			$costo="$".number_format($datos->costo,2,".",",");
			$subtotal="$".number_format(($datos->costo*($cantidad / $conversion->conversion)),2,".",",");	
			$propiedad='';

			if($datos->idProveedor==$row->idPrv){$propiedad='selected';}
			$select.='<option value="'.$row->idPrv.'" '.$propiedad.'>'.utf8_decode($row->razon_social).'</option>';	
		}
		if($select!='<select id="proveedor_'.$idProducto.'" onchange="CotizaProveedor(this.value,'.$idProducto.','.$pedido.');" class="nminputselect">'){
				 $hiddenvalue=$datos->idUnidad;
			$select.='</select>';}
		else{$select='Aún no existe proveedor para este producto';$check=''; $hiddenvalue=''; }
		$total+=str_replace(",","",str_replace("$","",$subtotal));
		$ex.='	
			<td align="center" class="nmcatalogbusquedacont_1">'.$check.'</td>
			<td align="center" class="nmcatalogbusquedacont_1"><span>'.$cantidad.'</span></td>
			<td align="center" class="nmcatalogbusquedacont_1">'.$unidad.'</td>
			<td align="center" class="nmcatalogbusquedacont_1">'.$producto->codigo.'</td>
			<td align="center" class="nmcatalogbusquedacont_1">'.$producto->nombre.'</td>
			<td align="center" class="nmcatalogbusquedacont_1">'.$select.'<img class="preload" id="preloader_'.$idProducto.'" src="'.base_url().'/images/preloader.gif"></td>
			<td align="center" class="nmcatalogbusquedacont_1"><span id="cantidad_'.$idProducto.'">'.($cantidad / $conversion->conversion).'</span></td>
			<td align="center" class="nmcatalogbusquedacont_1"><input type="hidden" id="idunidad_'.$idProducto.'" value="'.$hiddenvalue.'"><span id="unidad_'.$idProducto.'">'.$unidadProveedor.'</span></td>
			<td align="center" class="nmcatalogbusquedacont_1"><span id="costo_'.$idProducto.'">'.$costo.'</span></td>
			<td align="center" class="nmcatalogbusquedacont_1"><span id="subtotal_'.$idProducto.'">'.$subtotal.'</span></td>     
		</tr>';
		
$i++;
}	
if($i<10)
	{
	for($f=$i;$f<10;$f++)
		{
	if($f%2==0){$ex.='<tr class="busqueda_fila">';} else{ $ex.='<tr class="busqueda_fila2">';}
		$ex.='
			<td align="center" class="nmcatalogbusquedacont_1"></td><td align="center" class="nmcatalogbusquedacont_1"></td><td align="center" class="nmcatalogbusquedacont_1"></td><td align="center" class="nmcatalogbusquedacont_1"></td>
			<td align="center" class="nmcatalogbusquedacont_1"></td><td align="center" class="nmcatalogbusquedacont_1"></td><td align="center" class="nmcatalogbusquedacont_1"></td><td align="center" class="nmcatalogbusquedacont_1"></td>
			<td align="center" class="nmcatalogbusquedacont_1"></td>  
			<td align="center" class="nmcatalogbusquedacont_1"></td>       
		</tr>';
		}
	}
	
$ex.='<tr class="tit_tabla_buscar">
    	<td align="center" class="nmcatalogbusquedacont_1"></td>
    	<td align="center" width="8%" class="nmcatalogbusquedacont_1"></td>
    	<td align="center" width="8%" class="nmcatalogbusquedacont_1"></td>
		<td align="center" width="8%" class="nmcatalogbusquedacont_1"></td>
		<td align="center" width="26%" class="nmcatalogbusquedacont_1"></td>
     	<td align="center" width="26%" class="nmcatalogbusquedacont_1"></td>
     	<td align="center" width="8%" class="nmcatalogbusquedacont_1"></td>
     	<td align="center" width="8%" class="nmcatalogbusquedacont_1"></td>
     	<td align="center" width="8%" class="nmcatalogbusquedacont_1"><h3>Total:</h3></td>
     	<td align="center" width="8%" class="nmcatalogbusquedacont_1"><h3><span id="total">$'.number_format($total,2,".",",").'</span></h3></td>
    </tr>';	
	
	
	
$ex.='<tr>
<td colspan="7"><label>Orden de producción generada por:</label><input readonly value="'.$_SESSION['accelog_login'].'" type="text" id="elaboro" style="width:350px;" class="form-control"></td>
<td colspan="2" align="right"><br>
<img class="preload" id="preloader" src="'.base_url().'/images/preloader.gif">
<input type="button" id="btongoc" value="Generar ordenes de Compra" onclick="GeneraOrdenesCompra2('.$x.');" class="btn btn-primary btnMenu">
</td></tr>';		

$data["materiales"]=$ex;
$this->load->view('production_order/explosion',$data);
}
///////
public function cotiza()
{
	$this->load->model('Orden_produccion');		
	$cotizacion=$this->Orden_produccion->cotizacion($_POST["producto"],$_POST["proveedor"]);
	$resultado=array($cotizacion->compuesto,"$".number_format($cotizacion->costo,2,".",","),"$".number_format(($_POST["cantidad"]*$cotizacion->costo),2,".",","),$cotizacion->idunidad);
	echo json_encode($resultado);

}


}	