<?php
error_reporting(0);
include("../../netwarelog/webconfig.php");
$mysqli = new mysqli($servidor,$usuariobd,$clavebd,$bd);
?>
<html>
<head>
	<title>Devoluciones</title>
<?php include('../../netwarelog/design/css.php');?>
<LINK href="../../netwarelog/design/<?php echo $strGNetwarlogCSS;?>/netwarlog.css" title="estilo" rel="stylesheet" type="text/css" /> <!--NETWARLOG CSS-->
<script type="text/javascript" src="jquery-1.10.2.min.js"></script>

<!-- Slect con buscador -->
<script src="select2/select2.min.js"></script>
<link rel="stylesheet" type="text/css" href="select2/select2.css" />
<link rel="stylesheet" type="text/css" href="../../libraries/bootstrap/dist/css/bootstrap.min.css" />
<style type="text/css">
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
</style>

	<script>
	 function justNumbers(e)
	 {
	 	var keynum = window.event ? window.event.keyCode : e.which;
		if ((keynum == 8) || (keynum == 46))
			return true;
															     
		return /\d/.test(String.fromCharCode(keynum));
	 }

		$(document).ready(function(){
			$(".btnDevolver").click(function(event){
					var arrayProducto=new Array();
					var arrayAlmacen=new Array();
					var arrayValues=new Array();
					var contador=0;
					$('.textDev').each(function(){
						if($(this).val()!=0){
							arrayProducto.push($(this).attr('producto'));
							arrayAlmacen.push($(this).attr('almacen'));
							arrayValues.push($(this).val()); 
							contador++;
						}///es para formar un array
					});
					if(contador>0){
						if(confirm("Estas seguro de mandar a buzon de devoluciones los productos?")){
							$.ajax({
								data:{accion:'devolver', idProducto:arrayProducto.join(','), idAlmacen:arrayAlmacen.join(','), values:arrayValues.join(','), proveedor:$("#selec_provee").val() },
					       		url:'devoluciones_ajax.php',
					       		type: 'POST',
					       		success: function(callback){ 
									alert(callback)
									reloadTabla();
					   			}
							});
						}
					}else
						alert("No hay ningun producto para devolver!");
			});
			
			$(".cmbProveedor").change(function(event){
				$(".cmbProveedor").attr("disabled","disabled");
				$(".cmbAlmacen").attr("disabled","disabled");
				reloadTabla();
			});
			
			$(".cmbAlmacen").change(function(event){
				$(".cmbProveedor").attr("disabled","disabled");
				$(".cmbAlmacen").attr("disabled","disabled");
				reloadTabla();
			});
			reloadTabla();
		
		// Cambia los selects a selects con buscador
			selects();
		});
		
	// Cambia los selects a selects con buscador
		function selects(){
		// Crea los Select con buscador
			$("#selec_provee").select2({
				width : "250px"
			});
			$("#selec_almacen").select2({
				width : "250px"
			});
		}
		
		function reloadTabla(){
			$(".busqueda_fila").remove();
			$.ajax({
				data:{accion:'listar', idProveedor:$("#selec_provee").val(), idAlmacen:$("#selec_almacen").val()},
	       		url:'devoluciones_ajax.php',
	       		type: 'POST',
	       		success: function(callback){ 
					var tabla=document.getElementById("tblDevoluciones");
					tabla.innerHTML=tabla.innerHTML+callback;
					$(".cmbProveedor").removeAttr("disabled");
					$(".cmbAlmacen").removeAttr("disabled");
					loadEvents();
	   			}
			});
		}
		
		function loadEvents(){
			$(".textDev").focusout(function(event){
				if($(this).val()=="")
					$(this).val(0);
				if($(this).val()==".")
					$(this).val(0);
				$(this).val(parseFloat($(this).val()).toFixed(2));
				if($(this).val()<0){
					alert("Debe ingresar numeros positivos")
					$(this).val(0);
					$(this).val(parseFloat($(this).val()).toFixed(2));
				}
			});
			
			$(".textDev").focus(function(event){
				if($(this).val()==0)
					$(this).val("");
			});
			
			
			
			$(".textDev").keypress(function(event){
				var cadena=$(this).val()+String.fromCharCode(event.keyCode);
				//var validRfc=new RegExp('(^[0-9]{1,}\.{1}[0-9]{1,2}$)|(^[0-9]{1,}$)|(^[0-9]{1,}\.{1}$)|(^\.{1}[0-9]{0,2}$)');
				var validRfc= new RegExp ('(/^[0-9]*(\.[0-9][0-9]$)');
				var matchArray=cadena.match(validRfc);
				
				if(matchArray==null)
					event.preventDefault();
			});
			
			$(".textDev").keyup(function(event){
				var campo=$(this).val();
				if(campo==".")
					campo=0;
				var impuestoProducto=$(this).attr('impuestos')*campo;
				var costoCantidad=campo*$(this).attr('costo');
				$("#"+$(this).attr('idpu')).text(costoCantidad.toFixed(2));
				
				var total=0;
				var impuestos=0;
				$('.textDev').each(function(){
					total+=parseInt($("#"+$(this).attr('idpu')).text());	
					var campo=$(this).val();
					if(campo==".")
						campo=0;
					impuestos+=parseFloat($(this).attr('impuestos')*campo);
				});
				$("#neto").text(total.toFixed(2));
				$("#iva").text(impuestos.toFixed(2));
				$("#totalIva").text((impuestos+total).toFixed(2));
			});
		}
	</script>
</head>
<body>

	 <div class="container">
        <h3 class="nmwatitles text-center">Hacer una devolucion</h3>
        <div class="row">
        	<div class="col-md-10 col-md-offset-1">
        		<div class="row">
        			<div class="col-md-6">
        				<label>Selecciona un almacen:</label>
        				<select class="cmbAlmacen" id="selec_almacen"><?php
							$result = $mysqli->query("select idAlmacen, nombre from almacen");
							
							echo '<option value="0">Todos</option>';
							if($result->num_rows>0){
								while($row=$result->fetch_assoc()){
									echo '<option value="'.$row['idAlmacen'].'">'.$row['nombre'].'</option>';
								}
							} ?>	
						</select>
        			</div>
        			<div class="col-md-6">
        				<label>Selecciona un proveedor:</label>
        				<select class="cmbProveedor" id="selec_provee">
							<?php
								$result = $mysqli->query("select idPrv, razon_social from mrp_proveedor"); 
								if($result->num_rows>0){
									while($row=$result->fetch_assoc()){
										echo '<option value="'.$row['idPrv'].'">'.$row['razon_social'].'</option>';
									}
								}	
							?>	
						</select>
        			</div>
        		</div>
        		<h4>Productos en stock</h4>
        		<div class="row">
        			<div class="col-md-12">
        				<div class="table-responsive">
        					<table width="100%" align="center" style="display:table;border-collapse:separate;border-spacing:2px;border-color:gray" class="tblDevoluciones table" id="tblDevoluciones">
								<tbody>
									<tr class="tit_tabla_buscar" style="border:solid silver;font-weight: normal;font-size:medium;font-variant:normal;font-style:normal;color:-webkit-text;text-align:start">
										<td class="nmcatalogbusquedatit" align="center">Cantidad</td>
										<td class="nmcatalogbusquedatit" align="center">Unidad</td>
										<td class="nmcatalogbusquedatit" align="center">Devolucion</td>
										<td class="nmcatalogbusquedatit" align="center">Producto</td>
										<td class="nmcatalogbusquedatit" align="center">Costo unitario</td>
										<td class="nmcatalogbusquedatit" align="center">Subtotal</td>
									</tr>
									
									<!--ciclo de carga-->
									
									<!--fin ciclo carga-->
								</tbody>
							</table>
        				</div>
        			</div>
        		</div>
        		<div class="row">
        			<div class="col-md-2 col-md-offset-10">
        				<button type="button" id="btngcompra" class="btnDevolver btn btn-primary btnMenu">Devolver</button>
        			</div>
        		</div>
        	</div>
        </div>
    </div>
<?php
	mysqli_close($connection);
?>
</body>
</html>