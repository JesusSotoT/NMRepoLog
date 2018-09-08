<?php  
//ini_set('display_errors', 1);
include "../SAT/PDF/phpqrcode/qrlib.php";

session_start();
error_reporting(0);
$idventa=$_REQUEST["idventa"];
$print = $_REQUEST["print"];

include("controllers/caja.php"); 

$cajaController = new Caja;
$organizacion = $cajaController->datosorganizacion();
$venta = $cajaController->datosventa($idventa);
$datosSucursal = $cajaController->datosSucursal($idventa);
//echo $venta[0]['jsonImpuestos'];
$productos=$cajaController->productosventa($idventa);
$impuestos_venta = json_decode($venta[0]['jsonImpuestos']);
$impuestos_venta = $cajaController->object_to_array($impuestos_venta);
$pagos = $cajaController->pagos($idventa);
$configTikcet = $cajaController->configTikcet();
$leyenda = $cajaController->obtenerLeyenda();

$configVenta = $cajaController->obtenerConfigVenta();
$leyenda = $configVenta['leyenda_ticket'];
$precio_unit_ticket = $configVenta['precio_unit_ticket'];

unlink('images/qrventas/qrticket.png');
$texto="netwarmonitor.mx/clientes/".$_SESSION['accelog_nombre_instancia']."/kiosko";
$err = creaQR($texto,$idventa);


		function creaQR($texto,$idventa) {
			//include dirname(__FILE__) . "phpqrcode/qrlib.php";
			$ruta = 'images/qrventas/qrticket.png';
			QRcode::png($texto, $ruta);
			return $ruta;	
		}
$codigoQrVenta = '<tr><td align="center"><img src="'.$err.'" alt=""></td></tr>';

//print_r($pagos);
/*echo '<table><tr><th>Cantidad</th><th>Prodcuto</th><th>Total</th></tr>';
foreach ($productos as $key => $value) {
	echo '<tr>';
	echo '<td>'.$value['cantidad'].'</td>';
	echo '<td>'.$value['nombre'].'</td>';
	echo '<td>'.$value['precio'].'</td>';
	echo '</tr>';
}
echo '</table>';
echo '<table><tr><th>Cantidad</th></tr>';
foreach ($impuestos_venta as $key => $value) {
	echo '<tr>';
	echo '<td>'.$key.'='.$value.'</td>';
	echo '</tr>';
}
echo '</table>'; 
/*$organizacion=datosorganizacion();
$venta=datosventa($idventa);
$productos=productosventa($idventa);
$pagos=pagos($idventa);
$impuestos_venta=array();
$mesa = 0; */
if(isset($_SESSION['mesa'])){
	$mesa = $_SESSION['mesa'];
}else{
	$mesa = 0;
}

/*if($_SESSION['mesa']==null || $_SESSION['mesa']==0 || $_SESSION['mesa']==''){
	$mesa = 0;
	echo 'entro al if';
}else{
	echo 'entro al else';
	$mesa = $_SESSION['mesa'];
	$_SESSION['mesa'] = 0;
} */
//unset($_SESSION['mesa']);

?>
<meta charset="UTF-8">
<link rel="stylesheet" rev="stylesheet" href="css/netpos.css" />
<link rel="stylesheet" rev="stylesheet" href="css/netpos_print.css"  media="print"/>
<link rel="stylesheet" rev="stylesheet" href="../../libraries/font-awesome-4.7.0/css/font-awesome-4.7.min.css" />
<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
<script id="scriptAccion" type="text/javascript"> 
///////////////// ******** ---- 	formatearTicket		------ ************ //////////////////
//////// Imprime los pedidos de la comanda en una nueva ventana
// Como parametros puede recibir:
//	vararray -> array ticket
//	varstring -> string a convertir
//  bold -> 0: nomarl, 1: negrita
//  underlined -> 0: no, 1: si
//  tipo -> 1: left, 2: center, 3: right
function formatearTicket(vararray, varstring, bold, underlined, tipo){
			varstring = String(varstring).trim();
			var varstring_copia = varstring.split(" ");
			var string = '';
			var ant_string = '';
			console.log(varstring+': '+varstring.length);
			console.log('copia: '+varstring_copia.length);
			if(varstring.length > 32){
				for (var x = 0; x < varstring_copia.length; x++) {
					ant_string = string.trim();
					string += ' '+varstring_copia[x];
					if(string.length > 32){
						//centrado
						var string_spaces = '';
						if(tipo == 2){
							var spaces = Math.floor((32 - ant_string.length) / 2);
							for (var i = 0; i < spaces; i++) {
								string_spaces+= ' ';
							}
						} else if(tipo == 3){
							var spaces = 32 - varstring.length;
							for (var i = 0; i < spaces; i++) {
								string_spaces+= ' ';
							}
						}
						vararray.push({'texto' : string_spaces+ant_string, 'bold' : bold, 'underlined' : underlined});
						string = '';
						x--;
					} else if(x == (varstring_copia.length - 1)){
						string = string.trim();
						var string_spaces = '';
						if(tipo == 2){
							var spaces = Math.floor((32 - string.length) / 2);
							for (var i = 0; i < spaces; i++) {
								string_spaces+= ' ';
							}
						} else if(tipo == 3){
							var spaces = 32 - varstring.length;
							for (var i = 0; i < spaces; i++) {
								string_spaces+= ' ';
							}
						}

						vararray.push({'texto' : string_spaces+string, 'bold' : bold, 'underlined' : underlined});
					}
					
				}
			} else {
				//centrado
				var string_spaces = '';
				if(tipo == 2){
					var spaces = Math.floor((32 - varstring.length) / 2);
					for (var i = 0; i < spaces; i++) {
						string_spaces+= ' ';
					}
				} else if(tipo == 3){
					var spaces = 32 - varstring.length;
					for (var i = 0; i < spaces; i++) {
						string_spaces+= ' ';
					}
				}
				vararray.push({'texto' : string_spaces+varstring, 'bold' : bold, 'underlined' : underlined});
			}
			return vararray;
}
///////////////// ******** ---- 	FIN formatearTicket		------ ************ //////////////////

///////////////// ******** ---- 	formatearTicketProducts		------ ************ //////////////////
//////// Imprime los pedidos de la comanda en una nueva ventana
// Como parametros puede recibir:
//	vararray -> array ticket
//  cant -> cantidad producto
//  producto -> descripcion producto
//  total -> total
//	product -> string a convertir
//  bold -> 0: nomarl, 1: negrita
//  underlined -> 0: no, 1: si
//  tipo -> 1: left, 2: center, 3: right, 4: products
 function formatearTicketProducts (vararray, cant, varproduct, total, bold, underlined){
			cant = String(cant).trim();
			varproduct = String(varproduct).trim();
			total = String(total).trim();
			var product_copia = varproduct.split(" ");
			var product = '';
			var ant_product = '';
			var ya = 0;
			if(varproduct.length > 18){
				for (var x = 0; x < product_copia.length; x++) {
					ant_product = product.trim();
					product += ' '+product_copia[x];
					if(product.length > 18){
						//centrado
						var string_spaces = '';
						var spaces = 0;
						spaces = (18 - ant_product.length);
						for (var i = 0; i < spaces; i++) {
							string_spaces+= ' ';
						}
						ant_product += string_spaces;
						if(ya == 0){
							string_spaces = '';
							spaces = (7 - cant.length);
							for (var i = 0; i < spaces; i++) {
								string_spaces+= ' ';
							}
							cant += string_spaces;
							string_spaces = '';
							spaces = (7 - total.length);
							for (var i = 0; i < spaces; i++) {
								string_spaces+= ' ';
							}
							total = string_spaces+total;
							vararray.push({'texto' : cant+ant_product+total, 'bold' : bold, 'underlined' : underlined});
							ya = 1;
						}
						else{
							vararray.push({'texto' : '       '+ant_product, 'bold' : bold, 'underlined' : underlined});
						}
						product = '';
						x--;
					} else if(x == (product_copia.length - 1)){
						product = product.trim();
						var string_spaces = '';
						var spaces = 0;
						spaces = (18 - product.length);
						for (var i = 0; i < spaces; i++) {
							string_spaces+= ' ';
						}
						product += string_spaces;
						if(ya == 0){
							string_spaces = '';
							spaces = (7 - cant.length);
							for (var i = 0; i < spaces; i++) {
								string_spaces+= ' ';
							}
							cant += string_spaces;
							string_spaces = '';
							spaces = (7 - total.length);
							for (var i = 0; i < spaces; i++) {
								string_spaces+= ' ';
							}
							total = string_spaces+total;
							vararray.push({'texto' : cant+product+total, 'bold' : bold, 'underlined' : underlined});
							ya = 1;
						}
						vararray.push({'texto' : '       '+product, 'bold' : bold, 'underlined' : underlined});
					}
					
				}
			} else {
				//centrado
				var string_spaces = '';
				var spaces = 7 - cant.length;
				for (var i = 0; i < spaces; i++) {
					string_spaces+= ' ';
				}
				cant += string_spaces;
				string_spaces = '';
				spaces = 18 - varproduct.length;
				for (var i = 0; i < spaces; i++) {
					string_spaces+= ' ';
				}
				varproduct += string_spaces;
				string_spaces = '';
				spaces = 7 - total.length;
				for (var i = 0; i < spaces; i++) {
					
					string_spaces+= ' ';
				}
				total = string_spaces+total;
				console.log("total:"+total);
				vararray.push({'texto' : cant+varproduct+total, 'bold' : bold, 'underlined' : underlined});
			}
			return vararray;
}

function isEmptyF(obj){ 

	    var isEmpty = false;
	 
	    if (typeof obj == 'undefined' || obj === null || obj === ''){
	      isEmpty = true;
	    }      
	       
	    if (typeof obj == 'number' && isNaN(obj)){
	      isEmpty = true;
	    }
	       
	    if (obj instanceof Date && isNaN(Number(obj))){
	      isEmpty = true;
	    }
	       
	    return isEmpty;
}

<?php 
header('Content-Type: text/html; charset=utf-8');
if(!isset($print) || $print == 'true')
{
	?>
	$(function(){
		var isMobile = {
			mobilecheck : function() {
			return (/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino|android|ipad|playbook|silk/i.test(navigator.userAgent||navigator.vendor||window.opera)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test((navigator.userAgent||navigator.vendor||window.opera).substr(0,4)));
			}
		};
		if(isMobile.mobilecheck()){
			var jsonCaja = [];
			jsonCaja.push({'codigo' : '', 'qr' : '<?php echo $texto?>', 'tipo' : 3, 'type' : '', 'logo' : ''});
			<?php
				$src = '../../netwarelog/archivos/1/organizaciones/' . $organizacion[0]['logoempresa'];
				if(file_exists($src)){
					$type = pathinfo($src, PATHINFO_EXTENSION);
					//$data_2 = file_get_contents($src);

					// Loading the image and getting the original dimensions
					if($type == 'jpeg' || $type == 'jpg')
					$image = imagecreatefromjpeg($src);
					else if($type == 'png')
					$image = imagecreatefrompng($src);	
					$orig_width = imagesx($image);
					$orig_height = imagesy($image);
					$width = 390;
					// Calc the new height
					$height = (($orig_height * $width) / $orig_width);

					// Create new image to display
					$new_image = imagecreatetruecolor($width, $height);

					// Create new image with change dimensions
					imagecopyresized($new_image, $image,
						0, 0, 0, 0,
						$width, $height,
						$orig_width, $orig_height);

					// Print image
					ob_start();
					imagejpeg($new_image);
					$data = ob_get_contents();
					ob_end_clean(); ?>
					jsonCaja[0]['logo']= '<?php echo base64_encode($data); ?>';
	       			jsonCaja[0]['type']= '<?php echo $type; ?>';
				<?php } else { ?>
					jsonCaja[0]['logo']= '';
	       			jsonCaja[0]['type']= '';
				<?php }
			?>

			jsonCaja = formatearTicket(jsonCaja, "<?php echo utf8_decode($organizacion[0]['nombreorganizacion']);?>", 0, 0, 2);
			jsonCaja = formatearTicket(jsonCaja, "RFC: <?php echo utf8_decode($organizacion[0]['RFC']);?>", 0, 0, 2);
			jsonCaja = formatearTicket(jsonCaja, "<?php echo utf8_decode($datosSucursal[0]['direccion']." ".$datosSucursal[0]['municipio'].",".$datosSucursal[0]['estado']);?>", 0, 0, 2);
			
			<?php if($organizacion[0]['paginaweb']!='-'){?>
					jsonCaja = formatearTicket(jsonCaja, "<?php echo $organizacion[0]['paginaweb']?>", 0, 0, 2);
			<?php } ?>
			<?php if(strcmp($venta[0]['estatus'],"Cancelada")==0){?>
				jsonCaja = formatearTicket(jsonCaja, "<?php echo "Venta ".$venta[0]['estatus'];?>", 0, 0, 2);
			<?php }  ?>

			jsonCaja = formatearTicket(jsonCaja, "Sucursal: <?php echo $datosSucursal[0]['nombre']; ?>", 0, 0, 2);
			jsonCaja = formatearTicket(jsonCaja, "Ticket de compra", 0, 0, 2);
			jsonCaja = formatearTicket(jsonCaja, "Cliente: <?php echo $venta[0]['cliente']; ?>", 0, 0, 2);
			jsonCaja = formatearTicket(jsonCaja, "Cajero: <?php  echo $venta[0]['empleado']; ?>", 0, 0, 2);
			jsonCaja = formatearTicket(jsonCaja, "<?php echo $cajaController->formatofecha($venta[0]['fecha']);?>", 0, 0, 2);
			jsonCaja = formatearTicket(jsonCaja, "Id venta:<?php  echo $venta[0]['folio']; ?>", 0, 0, 2);

			<?php
			// Valida si la instancia tiene Foodware, para mostrar los dolares
			    session_start();
				if (in_array(2156, $_SESSION['accelog_menus'])) {
				// Consulta los ajustes de Foodware
					$ajustes_foodware = $cajaController->listar_ajustes_foodware($objeto);

					// Valida si se debe de mostrar la informacion de la comanda
					if ($ajustes_foodware['mostrar_info_comanda'] == 1) {
						if (empty($_SESSION['detalles_mesa'])) {
							$objeto['id_venta'] = $_REQUEST["idventa"];
							$_SESSION['detalles_mesa'] = $cajaController->listar_detalles_comanda($objeto);
						}
						
					// Imprime los datos de la comanda
						if (!empty($_SESSION['detalles_mesa'])) { ?>
							jsonCaja = formatearTicket(jsonCaja, '________________________________', 1, 0, 1);
							<?php if (!empty($_SESSION['detalles_mesa']['nombre_mesero'])) { ?>
								jsonCaja = formatearTicket(jsonCaja, "Mesero: <?php echo  $_SESSION['detalles_mesa']['nombre_mesero'] ?>", 0, 0, 2);
							<?php } ?>
							<?php if (!empty($_SESSION['detalles_mesa']['persona'])) { ?>
								jsonCaja = formatearTicket(jsonCaja, "Personas: <?php echo  $_SESSION['detalles_mesa']['persona'] ?>", 0, 0, 2);
							<?php } ?>
							jsonCaja = formatearTicket(jsonCaja, "Mesa: <?php echo $_SESSION['detalles_mesa']['nombre_mesa']; ?>", 0, 0, 2);
					 		jsonCaja = formatearTicket(jsonCaja, "<?php echo $_SESSION['detalles_mesa']['codigo']; ?>", 0, 0, 2);

							<?php
							
							//unset($_SESSION['detalles_mesa']);
						}
					} 
				} ?>
			jsonCaja = formatearTicket(jsonCaja, '________________________________', 1, 0, 1);
			jsonCaja = formatearTicketProducts(jsonCaja, 'Cant.', 'Producto', 'Total', 1, 0);
			<?php 
				$sub = 0;
				$descDesc = '';
				foreach ($productos as $key => $value) { ?>
					<?php if($value['tipodescuento']=='C'){
				 		$descDesc  = '[Cortesia]';
				 	}
					if($value['montodescuento'] > 0){
						//$descDesc  = '[Precio:$'.number_format($value['precio'],2).',Descuento:$'.number_format($value['montodescuento'],2).'/'.$value['tipodescuento'].$value['descuento'].']';
						$descDesc  = '[Descuento:$'.number_format($value['montodescuento'],2).'/'.$value['tipodescuento'].$value['descuento'].']';
					} ?>
					toti = '<?php echo number_format(($value["cantidad"] * $value["preciounitario"]),2) ?>';
					jsonCaja = formatearTicketProducts(jsonCaja, '<?php echo $value["cantidad"] ?>', '<?php echo $value["nombre"]." ".$descDesc; ?>', '$ '+toti, 0, 0);
					
					<?php $sub +=($value['cantidad'] * $value['preciounitario']);
					$descDesc = '';
				}
			?>
			jsonCaja = formatearTicket(jsonCaja, '________________________________', 1, 0, 1);

			jsonCaja = formatearTicket(jsonCaja, 'Subtotal: $<?php echo number_format($sub,2,".",","); ?>', 1, 0, 3);
			<?php 
				if($venta[0]['descuento'] > 0){?>
					jsonCaja = formatearTicket(jsonCaja, 'Descuento: $<?php echo number_format($venta[0]["descuento"],2); ?>', 1, 0, 3);
			<?php } ?>
			<?php 
				$totalimpuestos = 0;
				foreach ($impuestos_venta as $key2 => $value2) {?>
					jsonCaja = formatearTicket(jsonCaja, '<?php echo $key2; ?>: $<?php echo number_format($value2,2); ?>', 1, 0, 3);
			<?php
				$totalimpuestos+=$value2;
			 } ?>
			jsonCaja = formatearTicket(jsonCaja, 'Total: $<?php echo number_format((($sub+$totalimpuestos) - $venta[0]["descuento"]),2,".",",").' '.$venta[0]["codigo"]; ?>', 1, 0, 3);

			<?php 
				foreach ($pagos as $key => $value) {?>
					jsonCaja = formatearTicket(jsonCaja, '<?php echo $value["nombre"]; ?>: $<?php echo number_format($value["monto"],2).$venta[0]["codigo"]; ?>', 1, 0, 3);
			<?php } ?>

			jsonCaja = formatearTicket(jsonCaja, 'Cambio: $<?php echo number_format($venta[0]["cambio"],2,".",",").$venta[0]["codigo"]; ?>', 1, 0, 3);
			<?php
			
		// Valida si la instancia tiene Foodware, para mostrar los dolares
		    session_start();
			if (in_array(2156, $_SESSION['accelog_menus'])) {
				
			// Valida si se debe de mostrar la informacion de los dolares
				if ($ajustes_foodware['mostrar_dolares'] == 1) {
				// Consulta el tipo de cambio
					$objeto['moneda'] = 2; //Dolar
					$tipo_cambio = $cajaController->tipo_cambio($objeto);
					
				// Convierte a dolares
					$monto = number_format((($sub+$totalimpuestos) - $venta[0]['descuento']),2,".",",");
					
					$conversion = number_format(($monto / $tipo_cambio), 2, ".", ","); ?>
					jsonCaja = formatearTicket(jsonCaja, 'Dolar americano: $<?php echo $conversion ?>', 1, 0, 3);
					<?php
				}
			} ?>
			jsonCaja = formatearTicket(jsonCaja, '________________________________', 1, 0, 1);
			<?php
			if($configTikcet > 0){
				$url="netwarmonitor.mx/clientes/";
				$url2=$_SESSION['accelog_nombre_instancia']."/kiosko";

			?>
			jsonCaja = formatearTicket(jsonCaja, 'Para obtener su factura ingrese a la dirección:', 1, 0, 2);

			jsonCaja = formatearTicket(jsonCaja, '<?php echo $url?>', 0, 0, 2);
			jsonCaja = formatearTicket(jsonCaja, '<?php echo $url2?>', 0, 0, 2);

			jsonCaja = formatearTicket(jsonCaja, 'Ingresando el Siguiente codigo:', 1, 0, 2);
 
			<?php
				$longuitud=strlen($_SESSION['accelog_nombre_instancia']);
				$codinstancia=$_SESSION['accelog_nombre_instancia'][0].$_SESSION['accelog_nombre_instancia'][$longuitud-1];

				$fecha=str_replace('-', '', $venta[0]['fecha'] );
				$fecha=str_replace(':', '', $fecha);
				$fecha=str_replace(' ', '', $fecha);
				$codigoHex = $codinstancia.dechex($fecha.$venta[0]['folio']);
				$codigoFactura=$codigoHex;
			?> 
			jsonCaja = formatearTicket(jsonCaja, '<?php echo $codigoFactura?>', 0, 0, 2);
			//<?php echo $codigoQrVenta; ?>

			<?php } ?>
			jsonCaja = formatearTicket(jsonCaja, '<?php echo $leyenda?>', 0, 0, 2);
	
			var jsV = JSON.stringify(jsonCaja);
			jsV = jsV.replace(/#/g, '');
			jsV = jsV.replace(/%/g, '');
			jsV = jsV.replace(/á/g, 'a');
			jsV = jsV.replace(/é/g, 'e');
			jsV = jsV.replace(/í/g, 'i');
			jsV = jsV.replace(/ó/g, 'o');
			jsV = jsV.replace(/ú/g, 'u');
			jsV = jsV.replace(/Á/g, 'A');
			jsV = jsV.replace(/É/g, 'E');
			jsV = jsV.replace(/Í/g, 'I');
			jsV = jsV.replace(/Ó/g, 'O');
			jsV = jsV.replace(/Ú/g, 'U');
			console.log("json: ")
			console.log(jsV);
			//window.open('intent://intentar/#Intent;scheme=http;package=com.netwarmonitor.utilidades;S.extra1=' + jsV + ';end');
			var navegador = (navigator.userAgent.indexOf('Firefox') != -1) ? 1 : ((navigator.userAgent.indexOf("Chrome") != -1) ? 2 : 0);
			$("#btn_imprimir").attr("href", 'intent://intentar/#Intent;scheme=http;package=com.netwarmonitor.utilidades;S.extra1=' + jsV + ';S.navegador='+ navegador +';end');
			$("#btn_imprimir").css("display", "block");
		} else {
			//// MODULO PRINTVALIDACION
			var moduloPrint = 0;
			$.ajax({
				url : '../restaurantes/ajax.php?c=pedidosActivos&f=moduloPrint',
				type : 'POST',
				dataType : 'html',
				async:false,
			}).done(function(resp) {							
				moduloPrint = resp;
			});
			//web
	
			if(moduloPrint == 0){
				window.print();
			}else{
				var jsonCaja = [];
				jsonCaja.push({'codigo' : '', 'qr' : '<?php echo $texto?>', 'tipo' : 3, 'type' : '', 'logo' : ''});
				<?php
					$src = '../../netwarelog/archivos/1/organizaciones/' . $organizacion[0]['logoempresa'];
					if(file_exists($src)){
						$type = pathinfo($src, PATHINFO_EXTENSION);
						//$data_2 = file_get_contents($src);

						// Loading the image and getting the original dimensions
						if($type == 'jpeg' || $type == 'jpg')
						$image = imagecreatefromjpeg($src);
						else if($type == 'png')
						$image = imagecreatefrompng($src);	
						$orig_width = imagesx($image);
						$orig_height = imagesy($image);
						$width = 390;
						// Calc the new height
						$height = (($orig_height * $width) / $orig_width);

						// Create new image to display
						$new_image = imagecreatetruecolor($width, $height);

						// Create new image with change dimensions
						imagecopyresized($new_image, $image,
							0, 0, 0, 0,
							$width, $height,
							$orig_width, $orig_height);

						// Print image
						ob_start();
						imagejpeg($new_image);
						$data = ob_get_contents();
						ob_end_clean(); ?>
						jsonCaja[0]['logo']= '<?php echo base64_encode($data); ?>';
		       			jsonCaja[0]['type']= '<?php echo $type; ?>';
					<?php } else { ?>
						jsonCaja[0]['logo']= '';
		       			jsonCaja[0]['type']= '';
					<?php }
				?>

				jsonCaja = formatearTicket(jsonCaja, "<?php echo utf8_decode($organizacion[0]['nombreorganizacion']);?>", 0, 0, 2);
				jsonCaja = formatearTicket(jsonCaja, "RFC: <?php echo utf8_decode($organizacion[0]['RFC']);?>", 0, 0, 2);
				jsonCaja = formatearTicket(jsonCaja, "<?php echo utf8_decode($datosSucursal[0]['direccion']." ".$datosSucursal[0]['municipio'].",".$datosSucursal[0]['estado']);?>", 0, 0, 2);
				
				<?php if($organizacion[0]['paginaweb']!='-'){?>
						jsonCaja = formatearTicket(jsonCaja, "<?php echo $organizacion[0]['paginaweb']?>", 0, 0, 2);
				<?php } ?>
				<?php if(strcmp($venta[0]['estatus'],"Cancelada")==0){?>
					jsonCaja = formatearTicket(jsonCaja, "<?php echo "Venta ".$venta[0]['estatus'];?>", 0, 0, 2);
				<?php }  ?>

				jsonCaja = formatearTicket(jsonCaja, "Sucursal: <?php echo $datosSucursal[0]['nombre']; ?>", 0, 0, 2);
				jsonCaja = formatearTicket(jsonCaja, "Ticket de compra", 0, 0, 2);
				jsonCaja = formatearTicket(jsonCaja, "Cliente: <?php echo $venta[0]['cliente']; ?>", 0, 0, 2);
				jsonCaja = formatearTicket(jsonCaja, "Cajero: <?php  echo $venta[0]['empleado']; ?>", 0, 0, 2);
				jsonCaja = formatearTicket(jsonCaja, "<?php echo $cajaController->formatofecha($venta[0]['fecha']);?>", 0, 0, 2);
				jsonCaja = formatearTicket(jsonCaja, "Id venta:<?php  echo $venta[0]['folio']; ?>", 0, 0, 2);

				<?php
				// Valida si la instancia tiene Foodware, para mostrar los dolares
				    session_start();
					if (in_array(2156, $_SESSION['accelog_menus'])) {
					// Consulta los ajustes de Foodware
						$ajustes_foodware = $cajaController->listar_ajustes_foodware($objeto);

						// Valida si se debe de mostrar la informacion de la comanda
						if ($ajustes_foodware['mostrar_info_comanda'] == 1) {
							if (empty($_SESSION['detalles_mesa'])) {
								$objeto['id_venta'] = $_REQUEST["idventa"];
								$_SESSION['detalles_mesa'] = $cajaController->listar_detalles_comanda($objeto);
							}
							
						// Imprime los datos de la comanda
							if (!empty($_SESSION['detalles_mesa'])) { ?>
								jsonCaja = formatearTicket(jsonCaja, '________________________________', 1, 0, 1);
								<?php if (!empty($_SESSION['detalles_mesa']['nombre_mesero'])) { ?>
									jsonCaja = formatearTicket(jsonCaja, "Mesero: <?php echo  $_SESSION['detalles_mesa']['nombre_mesero'] ?>", 0, 0, 2);
								<?php } ?>
								<?php if (!empty($_SESSION['detalles_mesa']['persona'])) { ?>
									jsonCaja = formatearTicket(jsonCaja, "Personas: <?php echo  $_SESSION['detalles_mesa']['persona'] ?>", 0, 0, 2);
								<?php } ?>
								jsonCaja = formatearTicket(jsonCaja, "Mesa: <?php echo $_SESSION['detalles_mesa']['nombre_mesa']; ?>", 0, 0, 2);
						 		jsonCaja = formatearTicket(jsonCaja, "<?php echo $_SESSION['detalles_mesa']['codigo']; ?>", 0, 0, 2);

								<?php
								
								//unset($_SESSION['detalles_mesa']);
							}
						} 
					} ?>
				jsonCaja = formatearTicket(jsonCaja, '________________________________', 1, 0, 1);
				jsonCaja = formatearTicketProducts(jsonCaja, 'Cant.', 'Producto', 'Total', 1, 0);
				<?php 
					$sub = 0;
					$descDesc = '';
					foreach ($productos as $key => $value) { ?>
						<?php if($value['tipodescuento']=='C'){
					 		$descDesc  = '[Cortesia]';
					 	}
						if($value['montodescuento'] > 0){
							//$descDesc  = '[Precio:$'.number_format($value['precio'],2).',Descuento:$'.number_format($value['montodescuento'],2).'/'.$value['tipodescuento'].$value['descuento'].']';
							$descDesc  = '[Descuento:$'.number_format($value['montodescuento'],2).'/'.$value['tipodescuento'].$value['descuento'].']';
						} ?>
						toti = '<?php echo number_format(($value["cantidad"] * $value["preciounitario"]),2) ?>';
						jsonCaja = formatearTicketProducts(jsonCaja, '<?php echo $value["cantidad"] ?>', '<?php echo $value["nombre"]." ".$descDesc; ?>', '$ '+toti, 0, 0);
						
						<?php $sub +=($value['cantidad'] * $value['preciounitario']);
						$descDesc = '';
					}
				?>
				jsonCaja = formatearTicket(jsonCaja, '________________________________', 1, 0, 1);

				jsonCaja = formatearTicket(jsonCaja, 'Subtotal: $<?php echo number_format($sub,2,".",","); ?>', 1, 0, 3);
				<?php 
					if($venta[0]['descuento'] > 0){?>
						jsonCaja = formatearTicket(jsonCaja, 'Descuento: $<?php echo number_format($venta[0]["descuento"],2); ?>', 1, 0, 3);
				<?php } ?>
				<?php 
					$totalimpuestos = 0;
					foreach ($impuestos_venta as $key2 => $value2) {?>
						jsonCaja = formatearTicket(jsonCaja, '<?php echo $key2; ?>: $<?php echo number_format($value2,2); ?>', 1, 0, 3);
				<?php
					$totalimpuestos+=$value2;
				 } ?>
				jsonCaja = formatearTicket(jsonCaja, 'Total: $<?php echo number_format((($sub+$totalimpuestos) - $venta[0]["descuento"]),2,".",",").' '.$venta[0]["codigo"]; ?>', 1, 0, 3);

				<?php 
					foreach ($pagos as $key => $value) {?>
						jsonCaja = formatearTicket(jsonCaja, '<?php echo $value["nombre"]; ?>: $<?php echo number_format($value["monto"],2).$venta[0]["codigo"]; ?>', 1, 0, 3);
				<?php } ?>

				jsonCaja = formatearTicket(jsonCaja, 'Cambio: $<?php echo number_format($venta[0]["cambio"],2,".",",").$venta[0]["codigo"]; ?>', 1, 0, 3);
				<?php
				
				// Valida si la instancia tiene Foodware, para mostrar los dolares
			    session_start();
				if (in_array(2156, $_SESSION['accelog_menus'])) {
					
				// Valida si se debe de mostrar la informacion de los dolares
					if ($ajustes_foodware['mostrar_dolares'] == 1) {
					// Consulta el tipo de cambio
						$objeto['moneda'] = 2; //Dolar
						$tipo_cambio = $cajaController->tipo_cambio($objeto);
						
					// Convierte a dolares
						$monto = number_format((($sub+$totalimpuestos) - $venta[0]['descuento']),2,".",",");
						
						$conversion = number_format(($monto / $tipo_cambio), 2, ".", ","); ?>
						jsonCaja = formatearTicket(jsonCaja, 'Dolar americano: $<?php echo $conversion ?>', 1, 0, 3);
						<?php
					}
				} ?>
				jsonCaja = formatearTicket(jsonCaja, '________________________________', 1, 0, 1);
				<?php
				if($configTikcet > 0){
					$url="netwarmonitor.mx/clientes/";
					$url2=$_SESSION['accelog_nombre_instancia']."/kiosko";

				?>
				jsonCaja = formatearTicket(jsonCaja, 'Para obtener su factura ingrese a la dirección:', 1, 0, 2);

				jsonCaja = formatearTicket(jsonCaja, '<?php echo $url?>', 0, 0, 2);
				jsonCaja = formatearTicket(jsonCaja, '<?php echo $url2?>', 0, 0, 2);

				jsonCaja = formatearTicket(jsonCaja, 'Ingresando el Siguiente codigo:', 1, 0, 2);
	 
				<?php
					$longuitud=strlen($_SESSION['accelog_nombre_instancia']);
					$codinstancia=$_SESSION['accelog_nombre_instancia'][0].$_SESSION['accelog_nombre_instancia'][$longuitud-1];

					$fecha=str_replace('-', '', $venta[0]['fecha'] );
					$fecha=str_replace(':', '', $fecha);
					$fecha=str_replace(' ', '', $fecha);
					$codigoHex = $codinstancia.dechex($fecha.$venta[0]['folio']);
					$codigoFactura=$codigoHex;
				?> 
				jsonCaja = formatearTicket(jsonCaja, '<?php echo $codigoFactura?>', 0, 0, 2);
				//<?php echo $codigoQrVenta; ?>

				<?php } ?>
				jsonCaja = formatearTicket(jsonCaja, '<?php echo $leyenda?>', 0, 0, 2);

				var segundo = 0;
	            var impresionTexto = ""; 
	            console.log("textomadafaka")
	            console.log(JSON.stringify(jsonCaja[1]));

	            $.each(jsonCaja, function(index, element){
	                if(segundo == 0){
	                    segundo = 1;
	                }else{
	                    impresionTexto = impresionTexto + element.texto + "\n";
	                }
	            });

	            $.ajax({
	                url : '../restaurantes/ajax.php?c=impresion&f=insertar',
	                type: 'POST',
	                dataType: 'json',
	                data: { area : "Caja", ticket : impresionTexto, codigo : ""},
	            })
	            .done(function(resp) {

	            })
	            .fail(function() {
	                console.log("error");
	            })
	            .always(function() {
	                console.log("complete");
	            });
				
			}	
		}

		
	});
	<?php 
}
?>
</script>
<style>

body{
	font-family: Tahoma,'Trebuchet MS',Arial;
}
#letraschicas{
	font-size: 13px;

}
.small_button a{
	color:white;
	text-decoration:none;
	font-family:Arial, Helvetica, sans-serif;
}
.textWrap {
    text-align: justify;
    word-wrap: break-word;
    font-size: 10px;
}

@media print
{
	.item_number{display:none;}
}
</style>


<div id="receipt_wrapper">
		<div id="logo">
		<?php 
			$imagen='../../netwarelog/archivos/1/organizaciones/'.$organizacion[0]['logoempresa'];
			$imagesize=getimagesize($imagen);
			$porcentaje=0;
			if($imagesize[0]>200 && $imagesize[1]>90){
				if($imagesize[0]>$imagesize[1]){
					$porcentaje=intval(($imagesize[1]*100)/$imagesize[0]);
					$imagesize[0]=200;
					$imagesize[1]=(($porcentaje*200)/100);
				}else{
					$porcentaje=intval(($imagesize[0]*100)/$imagesize[1]);
					$imagesize[0]=200;
					$imagesize[1]=(($porcentaje*200)/100);	
				}
			}
			//"../../netwarelog/archivos/1/organizaciones/'.$cliente[0]->logoempresa.'"
			$src="";
			if($imagen!="" && file_exists($imagen))
				$src='<img src="'.$imagen.'" style="width:'.$imagesize[0].'px;height:'.$imagesize[1].'px;display:block;margin:0 auto 0 auto;"/>';
			echo $src;
		?>
	
	</div>
	<table align="center" style="width: 100%;">
	<tbody style="width: 100%;">
	<tr style="width: 100%;">
	<td style="width: 100%;">
	<div id="receipt_header" style="text-align:center;">
	<a id="btn_imprimir" style="display: none;" ><li class="fa fa-print fa-2x">Imprimir ticket</li></a>
		<div id="company_name" style="text-align: center; font-size:15px;font-family: Tahoma,'Trebuchet MS',Arial;"><?php echo $organizacion[0]['nombreorganizacion'];?></div>
	<!--	<div id="company_address"><?php echo utf8_decode($organizacion[0]['domicilio']." ".$organizacion[0]['municipio'].",".$organizacion[0]['estado']);?></div> -->

	<?php if(!empty($organizacion[0]['RFC'])) {?>
	<div id="rfc" style="text-align: center; font-size:15px;font-family: Tahoma,'Trebuchet MS',Arial;">RFC: <?php echo $organizacion[0]['RFC'];?></div>	
	<?php } ?>
	<div id="company_address" style="text-align: center; font-size:15px;font-family: Tahoma,'Trebuchet MS',Arial;"><?php echo $datosSucursal[0]['direccion']." ".$datosSucursal[0]['municipio'].",".$datosSucursal[0]['estado'];?>	
	</div>
	<?php 
		if($organizacion[0]['paginaweb']!='-'){
			echo '<div id="paginaWeb" style="text-align: center; font-size:13px;font-family: Tahoma,'."'Trebuchet MS'".',Arial;">'.$organizacion[0]['paginaweb'].'</div>';	
		}
	?>
		<?php if(strcmp($venta[0]['estatus'],"Cancelada")==0){?>
		<div id="company_phone">		
			<?php echo "Venta ".$venta[0]['estatus'];?>
		</div>
		<?php
	}  ?>

	<!--<div id="sale_receipt"><?php echo  $organizacion[0]['logoempresa'];?></div>	-->
	<div id="sucursal" style="text-align: center; font-size:13px;font-family: Tahoma,'Trebuchet MS',Arial;">Sucursal:<?php echo $datosSucursal[0]['nombre']; ?></div>
		<div id="sucursal" style="text-align: center; font-size:13px;font-family: Tahoma,'Trebuchet MS',Arial;">Telefono:<?php echo $datosSucursal[0]['tel_contacto']; ?></div>
	<div id="sale_receipt" style="text-align: center; font-size:13px;font-family: Tahoma,'Trebuchet MS',Arial;">Ticket de compra</div>
	<div id="customer" style="text-align: center; font-size:13px;font-family: Tahoma,'Trebuchet MS',Arial;">Cliente:<?php echo $venta[0]['cliente']; ?></div>
	<div id="receipt_general_info" style="text-align:center;">
		<div style="width: 5%; float: left;">&nbsp;</div>
		<div id="employee" style="width: 40%; float: left; text-align: left; font-size:13px;font-family: Tahoma,'Trebuchet MS',Arial;">Cajero: <?php  echo $venta[0]['empleado']; ?></div>
		<div id="sale_time" style="width: 50%; float: left; text-align: right; font-size:13px;font-family: Tahoma,'Trebuchet MS',Arial;"><!--Fecha y hora--><?php echo $cajaController->formatofecha($venta[0]['fecha']);?></div>
	</div><br>
	<div id="sale_id" style="text-align: center; font-size:13px;font-family: Tahoma,'Trebuchet MS',Arial;">Id venta:<?php  echo $venta[0]['folio']; ?></div>
	<?php
	
// Valida si la instancia tiene Foodware, para mostrar los dolares
    session_start();
	if (in_array(2156, $_SESSION['accelog_menus'])) {
	// Consulta los ajustes de Foodware
		$ajustes_foodware = $cajaController->listar_ajustes_foodware($objeto);

	// Valida si se debe de mostrar la informacion de la comanda
	if ($ajustes_foodware['mostrar_info_comanda'] == 1) {
		if (empty($_SESSION['detalles_mesa'])) {
			$objeto['id_venta'] = $_REQUEST["idventa"];
			$_SESSION['detalles_mesa'] = $cajaController->listar_detalles_comanda($objeto);
		}
		
// Imprime los datos de la comanda
		if (!empty($_SESSION['detalles_mesa'])) { ?>
			<div id="receipt_general_info" style="text-align:center; border-top:2px solid;">
				<div style="width: 5%; float: left;">&nbsp;</div>
				<?php if (!empty($_SESSION['detalles_mesa']['nombre_mesero'])) { ?>
					<div id="employee" style="width: 55%; float: left; text-align: left; font-size:13px;font-family: Tahoma,'Trebuchet MS',Arial;">Mesero: <?php echo  $_SESSION['detalles_mesa']['nombre_mesero'] ?></div>
				<?php } ?>
				<?php if (!empty($_SESSION['detalles_mesa']['persona'])) { ?>
					<div id="persons" style="width: 35%; float: left; text-align: right;font-size:13px;font-family: Tahoma,'Trebuchet MS',Arial;">Personas: <?php echo  $_SESSION['detalles_mesa']['persona'] ?></div>
				<?php } ?>
			</div><br>
			<div id="receipt_general_info" style="text-align:center;">
				<div style="width: 5%; float: left;">&nbsp;</div>
				<?php if (is_numeric($_SESSION['detalles_mesa']['nombre_mesa'])) { ?>
		 			<div id="mesa" style="width: 45%; float: left; text-align: left;font-size:13px;font-family: Tahoma,'Trebuchet MS',Arial;">Mesa: #<?php echo $_SESSION['detalles_mesa']['nombre_mesa']; ?></div>
				<?php } else { ?>
					<div id="mesa" style="width: 45%; float: left; text-align: left;font-size:13px;font-family: Tahoma,'Trebuchet MS',Arial;">Mesa: <?php echo $_SESSION['detalles_mesa']['nombre_mesa']; ?></div>
				<?php } ?>
				<div id="comand" style="width: 45%; float: left; text-align: right; font-size:13px;font-family: Tahoma,'Trebuchet MS',Arial;"><?php echo $_SESSION['detalles_mesa']['codigo']; ?></div>

			</div>
		
			<?php
			
			unset($_SESSION['detalles_mesa']);
		}
	} } ?>
</div>
</td>
</tr>
</tbody>
</table>

<table border='0' style="width: 100%; border-top:2px solid;" align="center">
	<tr style="font-weight: bold; font-size:15px; font-family: Tahoma,'Trebuchet MS',Arial;">
		<!--<th style="width:25%;" class='item_number'>#</th>-->
		<th style="width:20%; text-align: center;">Cant</th>
		<th style="width:40%; text-align: left;">Producto</th>
		<!--<th style="width:17%;">Precio</th>-->

		<!--<th style="width:16%;text-align:center;">Descuento</th>-->
		<?php echo ($precio_unit_ticket == "1") ? '<th style="width:20%;text-align:center;">P. U.</th>' : ''; ?>
		<th style="width:20%;text-align:center;">Total</th>
	</tr>
	<?php 
		$sub = 0;
		$descDesc = '';
		foreach ($productos as $key => $value) {
		 echo '<tr style="font-size:13px; font-family: Tahoma,'."'".'Trebuchet MS'."'".',Arial; text-align: center">';
		 echo "<td style='width:25%; text-align:center;'>".$value['cantidad']."</td>";
		  	if($value['tipodescuento']=='C'){
		
		 		$descDesc  = '[Cortesia]';

		 	}
		 if($value['montodescuento'] > 0){
		 	//$descDesc  = '[Precio:$'.number_format($value['precio'],2).',Descuento:$'.number_format($value['montodescuento'],2).'/'.$value['tipodescuento'].$value['descuento'].']';
		 	$descDesc  = '[Descuento:$'.number_format($value['montodescuento'],2).'/'.$value['tipodescuento'].$value['descuento'].']';
		 }
		 if ($value['id'] == 0) {
		 	$nm = $value['comentario'];
		 } else{
		 	if($value['descripcion_corta']!=''){
		 		$nm = $value['descripcion_corta'];
		 	}else{
		 		$nm = $value['nombre'];
		 	}
		 	
		 }

		 echo "<td style='width:34%; text-align: left;' class='textWrap'><span class='short_name'>".$nm.' '.$descDesc."</td>";
		 echo ($precio_unit_ticket == "1") ? ("<td style='width:23%; text-align: center; text-align:center;'>$".number_format($value['preciounitario'],2)."</td>") : '';
		 echo "<td style='width:23%; text-align: center; text-align:center;'>$".number_format(($value['cantidad'] * $value['preciounitario']),2)."</td>";
		 echo "</tr>";
		 $sub +=($value['cantidad'] * $value['preciounitario']);
		 $descDesc = '';
		}
	?>
		<?php
		/*if($producto->montodescuento>0){
			?>
			<tr>
				<td style='text-align:center;'>Desc:</td><td style='text-align:center;'>$<?php echo number_format( $producto->montodescuento,2,".",","); ?></td>
			</tr>
			<?php
		} */
	?>
	<tr style="width: 100%; ">
		<td colspan="4" style="width:100%;border-top:2px solid; ">
			<div style="text-align: right;font-size:13px;font-family: Tahoma,'Trebuchet MS',Arial;">
				<strong>Subtotal: </strong>$<?php echo number_format($sub,2,".",","); ?>
			</div>
		</td>
	</tr>
		<?php 
			if($venta[0]['descuento'] > 0){?>
				<tr style="width: 100%">
					<td colspan="4" style="width:100%;">
						<div style="text-align: right;font-size:13px;font-family: Tahoma,'Trebuchet MS',Arial;">
							<strong>Descuento: </strong>$<?php echo number_format($venta[0]['descuento'],2); ?>
						</div>
					</td>
				</tr>
			<?php }
		?>
	<?php 
		$totalimpuestos = 0;
		//print_r($impuestos_venta);
		foreach ($impuestos_venta as $key2 => $value2) {
			//echo 'CCCC'.$key;
			echo '<tr style="width: 100%"><td colspan="4" style="width:100%;">';?>
			<div style="text-align: right;font-size:13px;font-family: Tahoma,'Trebuchet MS',Arial;">
				<strong><?php echo $key2; ?>: </strong>$<?php echo number_format($value2,2); ?>
			</div>
			<?php echo '</td></tr>';
			$totalimpuestos+=$value2;
		}
	?>
	
	<?php
	//$totalimpuestos=0;
	/*if($impuestos_venta2==''){
		$impuestos_venta2['IVA']=0.00;
	}
	$impuestos_venta=$impuestos_venta2;
	foreach($impuestos_venta as $impuesto=>$valorimpuesto)
	{
		$totalimpuestos+=$valorimpuesto;
		?>		
		<tr>
			<td colspan="2" style='text-align:right;'><b><?php echo $impuesto;?></b></td>
			<td colspan="1" style='text-align:right;'>$<?php echo number_format( $valorimpuesto,2,".",","); ?></td>
		</tr>
		<?php	
	} */
	?>
<!--
		<tr>
			<td colspan="4" style='text-align:right;'>Impuestos</td>
			<td colspan="2" style='text-align:right;'>$<?php echo number_format( $venta->impuestos,2,".",","); ?></td>
		</tr>
	-->	
	<tr style="width:100%;">
		<td colspan="4" style="width:100%;">
			<div style="text-align: right;font-size:13px;font-family: Tahoma,'Trebuchet MS',Arial;">
				<!--<strong>Total: </strong>$<?php echo number_format((($sub+$totalimpuestos) - $venta[0]['descuento']),2,".",",").' '.$venta[0]['codigo']; ?> -->
				<strong>Total: </strong>$<?php 
				if(($venta[0]['tipo_cambio'] * 1) > 1){
					echo number_format((($sub+$totalimpuestos) - $venta[0]['descuento']),2,".",","); 
				}else{
					echo number_format((($sub+$totalimpuestos) - $venta[0]['descuento']),2,".",",").' '.$venta[0]['codigo'];
				}
				
				?>
			</div>
		</td>
	</tr>
	<?php 
		foreach ($pagos as $key => $value) {?>
			<tr style="width:100%;">
				<td colspan="4" style="width:100%;">
					<div style="text-align: right;font-size:13px;font-family: Tahoma,'Trebuchet MS',Arial;">
					<!--	<strong><?php echo $value['nombre']; ?>: </strong>$<?php echo number_format($value['monto'],2).$venta[0]['codigo']; ?> -->
						<strong><?php echo $value['nombre']; ?>: </strong>$<?php 


						if(($venta[0]['tipo_cambio'] * 1) > 1){
							echo number_format($value['monto'],2); 
						}else{
							echo number_format($value['monto'],2).$venta[0]['codigo'];
						}
						


						?>
					</div>
				</td>
			</tr>
		<?php }
	?>
	<?php while($pago=mysql_fetch_object($pagos)){ ?>
	<!--<tr>
		
		<td colspan="2" style="text-align:right;"><b><?php echo utf8_decode($pago->nombre); ?></b></td>
		<td colspan="1" style="text-align:left" >$<?php echo number_format($pago->monto,2,".",",").$venta[0]['codigo']; ?>  </td>
	</tr> -->
	<?php } ?>


	<tr style="width:100%;">
		<td colspan="4" style="width:100%;">
			<div style="text-align: right;font-size:13px;font-family: Tahoma,'Trebuchet MS',Arial;">
			<!--	<strong>Cambio: </strong>$<?php echo number_format($venta[0]['cambio'],2,".",",").$venta[0]['codigo']; ?> -->
				<strong>Cambio: </strong>$<?php 
				if(($venta[0]['tipo_cambio'] * 1) > 1){
					echo number_format($venta[0]['cambio'],2,".",","); 
				}else{
					echo number_format($venta[0]['cambio'],2,".",",").$venta[0]['codigo'];
				}
				
				?>
			</div>
		</td>
	</tr>
	<?php 
		if(($venta[0]['tipo_cambio'] * 1) > 1){

		
	?>
	<tr style="width:100%;">
		<td colspan="4" style="width:100%;">
			<div style="text-align: right;font-size:13px;font-family: Tahoma,'Trebuchet MS',Arial;">
			<!--	<strong>Cambio: </strong>$<?php echo number_format($venta[0]['cambio'],2,".",",").$venta[0]['codigo']; ?> -->
				<strong>Tipo Cambio: </strong>$<?php echo number_format($venta[0]['tipo_cambio'],4,".",","); ?>
			</div>
		</td>
	</tr>
	<?php 
		}
	?>

	<?php
	
// Valida si la instancia tiene Foodware, para mostrar los dolares
    session_start();
	if (in_array(2156, $_SESSION['accelog_menus'])) {
		
	// Valida si se debe de mostrar la informacion de los dolares
		if ($ajustes_foodware['mostrar_dolares'] == 1) {
		// Consulta el tipo de cambio
			$objeto['moneda'] = 2; //Dolar
			$tipo_cambio = $cajaController->tipo_cambio($objeto);
			
		// Convierte a dolares
			$monto = number_format((($sub+$totalimpuestos) - $venta[0]['descuento']),2,".",",");
			
			$conversion = number_format(($monto / $tipo_cambio), 2, ".", ","); ?>
			
			<tr style="width:100%;">
				<td colspan="4" style="width:100%;">
					<div style="text-align: right;font-size:13px;font-family: Tahoma,'Trebuchet MS',Arial;">
						<strong>Dolar americano: </strong>$<?php echo $conversion ?>
					</div>
				</td>
			</tr><?php
		}
	} ?>
</table>

<div> <strong>Puntos x venta actual:</strong> <?php echo $venta[0]['puntosTarjataRegalo'];?> </div>
<?php 

 ?>
<div> <strong>Total de puntos:</strong>  <?php echo $venta[0]['puntos'];?> </div>
<?php


	if($configTikcet > 0){
		$url="netwarmonitor.mx/clientes/</br>".$_SESSION['accelog_nombre_instancia']."/kiosko";

		 ?>
		 <hr size='2' color="black">
		 <table id='codigofact' width='100%' style="text-align: left;" border="0">
 
	<tr>
		<td>
			<div style="margin-top: 0px;" float="left">
				<h6 align="center">&nbsp;Para obtener su factura ingrese a la dirección:</h6>
			</div>
		<div style="margin-top: -15px;" float="left" class='textWrap'>
		<p align="center" style="font-size: 12px;">	
		<?php 
			//$url="netwarmonitor.mx/clientes/".$_SESSION['accelog_nombre_instancia']."/kiosko";
			//$rutaQR = $this->creaQR($url,$idventa);

			if(strlen($url) >50)
			{	
				echo $url;
				/*$url1 = substr($url, 0,50);
				$url2 = substr($url, 51);

				echo $url1."</br>";
				echo $url2; */
			}else
			{
				echo $url;
			}
		?>	
		</p>
		</div>	
		</td>
	</tr>

	<?php echo $codigoQrVenta; ?>

	<tr>
		<td>
			<div style="margin-top: 0px;" float="left">
				<h6 align="center">&nbsp;Ingresando el Siguiente codigo:</h6>
			</div>	
			<div style="margin-top: -15px;" float="left">
				<p align="center" style="font-size: 19px;">
		<?php
				$longuitud=strlen($_SESSION['accelog_nombre_instancia']);
				$codinstancia=$_SESSION['accelog_nombre_instancia'][0].$_SESSION['accelog_nombre_instancia'][$longuitud-1];

				$fecha=str_replace('-', '', $venta[0]['fecha'] );
				$fecha=str_replace(':', '', $fecha);
				$fecha=str_replace(' ', '', $fecha);
		//echo "Codigo sin convertir:".$codinstancia.$fecha.$venta->folio.";";	
				//$codigoHex=base64_encode($codinstancia.$fecha.$venta->folio);
				$codigoHex = $codinstancia.dechex($fecha.$venta[0]['folio']);
				$codigoFactura=$codigoHex;
				echo $codigoFactura;
		?> 
	           </p>
			</div>
	</td>
	</tr>
	<?php }
		
		?>
	<tr >
		<td style=" text-align:center; max-width: 400px"><p><?php echo nl2br($leyenda); ?></p></td>
	</tr>
</table> 
	
		
</div>



