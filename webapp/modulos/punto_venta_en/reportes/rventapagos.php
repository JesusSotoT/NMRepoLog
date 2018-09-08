<!DOCTYPE html>
<html>
	<script type="text/javascript" src="../js/jquery-1.10.2.min.js"></script>
		<!--<script type="text/javascript" src="../js/jquery.tablesorter.min.js"></script>-->
<script type="text/javascript" src="../js/tablesort.min.js"></script>
	<LINK href="../../../netwarelog/catalog/css/view.css" title="estilo" rel="stylesheet" type="text/css" />
<LINK href="../../../netwarelog/catalog/css/estilo.css" title="estilo" rel="stylesheet" type="text/css" />
  <link rel="stylesheet" type="text/css" media="all" href="css/styles.css">

<link href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" rel="stylesheet">
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
<script type="text/javascript" src="../../punto_venta/js/ui.datepicker-es-MX.js"></script>
<script type="text/javascript">
	
$(document).ready(function() {
	 $.datepicker.setDefaults($.datepicker.regional['es-MX']);
	
	 $("#inicio").datepicker({
	 	maxDate: 0,
	 	dateFormat: 'yy-mm-dd',
        numberOfMonths: 1,
        onSelect: function(selected) {
          $("#fin").datepicker("option","minDate", selected)
        }
    });
    
    $("#fin").datepicker({ 
    	dateFormat: 'yy-mm-dd',
        maxDate:0,
        numberOfMonths: 1,
        onSelect: function(selected) {
           $("#inicio").datepicker("option","maxDate", selected)
        }
    });

  });	
 
			function reporte() {
				 
        $('#datos thead tr th').attr('class','sort-header');

	if (!$("input:radio[name=eleccion]").is(':checked')) {
	alert("Elija una fecha");
	} else {
	var opc = $('input:radio[name=eleccion]:checked').val();
	var val = parseInt(opc, 10);
	var idforma = jQuery('#forma').val();
	var sucursal = jQuery('#sucursal').val();
					if (idforma == "elije") {
	alert("Elija una Forma de pago");
	} else {
	if(sucursal=="elije"){
	alert("Elija una sucursal");
	}else{

	$('body').css('overflow','hidden');
	$('#carga').css('display','block');//show();

	switch (val) {
								
							case 1:
	
								//radio 1
								var fecha = jQuery('#fecha').val();
								
								 $.post("consultas2.php",{opc:5,fecha:fecha,idforma:idforma,sucursal:sucursal},
								 function(respues) {
								 	
	$('body').css('overflow','hidden');
	$('#datos').css('display','block');//show();
	
	
	var res=respues.split('->');
	$('#datos tbody').html(res[0]);
	$('#datos tfoot').html(res[1]);
	$('body').css('overflow','auto');
	$('#carga').hide();
	////////////////////////////////////////////////////////

	if (res[0] != "") {
		$('#nota').css('display', 'block');
		$(document).ready(function() {
			setTimeout(function() {
				$(".div1").fadeOut(1500);
			}, 5000);
		});
	}
///////////////////	/////////////////////////////////

								 });
								
								break;
								case 2:// radio 2
								
				var fechainicio = jQuery('#inicio').val();
				var fin =jQuery('#fin').val();
				if(fechainicio=="" || fin==""){
					alert("Elija las fechas");
					$('body').css('overflow','auto');
		                       $('#carga').hide();
				}else{
					$.post("consultas2.php",{opc:6,fechainicio:fechainicio,fin:fin,idforma:idforma,sucursal:sucursal},
	function(respues) {
	$('body').css('overflow','hidden');
	$('#datos').css('display','block');//show();
	
	var res=respues.split('->');
	$('#datos tbody').html(res[0]);
	$('#datos tfoot').html(res[1]);
	$('body').css('overflow','auto');
	$('#carga').hide();
////////////////////////////////////////////////////////

	if (res[0] != "") {
		$('#nota').css('display', 'block');
		$(document).ready(function() {
			setTimeout(function() {
				$(".div1").fadeOut(1500);
			}, 5000);
		});
	}
///////////////////	/////////////////////////////////
				});	
				
			}
			break;	
		
						}//switch
					}//else de sucu
					}//else de elije provee
				}//else d elije fecha
				
			}
</script>

<body>
	<?php
    include("../../../netwarelog/webconfig.php");
		$conection = new mysqli($servidor,$usuariobd,$clavebd,$bd);	
?>
<a href="javascript:window.print();">
<img border="0" src="../../../netwarelog/repolog/img/impresora.png"></a><b style="font-size:16px;font-weight:bold; color:#6E6E6E;">&nbsp;Reporte de ventas(Por pagos realizados).</b>

<div id="fechas">
<h3>Elija un rango de fechas</h3>

		<fieldset style=" font-size:12px;font-weight:bold; color:#6E6E6E;width: 50%;vertical-align:top; display:inline-block;">

			<legend>
				B&uacute;squeda por Fecha
			</legend>
			<input type="radio" name="eleccion" value="1"/>
			<select id="fecha" style="font-size: 10px;">

				<option value="1"  selected>Hoy</option>
				<option value="2" >Ayer</option>
				<option value="3" >Ultimos 7 dias</option>
				<option value="4" >Este a&ntilde;o</option>
				<option value="5"  >Todas</option>
			</select>
			<br>
			<input type="radio" name="eleccion" value="2"/>
			Fecha Inicio
			<input type="text" id="inicio" />
			Fecha Final
			<input type="text" id="fin" />

		</fieldset>
		<br>
	
	<h3>Forma de Pago:</h3>
		<select id="forma" style="font-size: 10px;">
			<?php $forma=$conection->query("select idFormapago,nombre from forma_pago ");
			if($forma->num_rows>0){
						?>
						
           <option value="elije" selected>-- Elija una forma de pago --</option>
		<?php	while($formapago=$forma->fetch_array(MYSQLI_ASSOC)){ ?>

			<option value="<?php echo $formapago['idFormapago']; ?>"><?php echo utf8_encode($formapago['nombre']); ?></option>
		
		<?php	}
			}else{ ?>
			<option selected>--No existen Formas de pago registradas--</option>
          <?php   }	?>	
		</select>
		<select id="sucursal"  style=" cursor:pointer; margin-top:25px;   font-size:12px; " >
						
			<?php $sucu=$conection->query("select idSuc,nombre from mrp_sucursal");
			if($sucu->num_rows>0){ ?>

			<option value="elije" selected >-- Elija una Sucursal --</option>
		<?php	while($sucursal=$sucu->fetch_array(MYSQLI_ASSOC)){ ?>

		
			<option value="<?php echo $sucursal['idSuc']; ?>"><?php echo $sucursal['nombre']; ?></option>
			
		<?php } ?>
		<option value="todo"  >-- Todas --</option>
		<?php	}else{ ?>
			
            <option selected>--No existen Sucursales--</option>
       <?php     } ?>
</select>
 	
<input type="button" id="busca" value="Ver" onclick="reporte();"/><img src="img/preloader.gif"  id="carga" class="overbox" style="display: none" />

</div>	
<div  style="width: 410px; height:27px; padding-top:3px; ">
<div class="div1" id="nota" style="display: none; font-size:12px;font-weight:bold; color:#000000;">
De click sobre el campo que desea para ordenar los datos
	<div class="div2" id="nota2" ></div>
</div>
</div>
<table  id="datos"  cellpadding="3" cellspacing="0" width="95%" height="95%" style="display: none">
	<thead>
		<tr class="tit_tabla_buscar" title="Segmento de búsqueda" style="font-size: 10pt;">
	 <th align="center" width="10%"><span> ID VENTA</span></th>
	 <th align="center" width="20%"><span>Fecha</span></th>
     <th align="center" width="20%"><span>Sucursal</span></th>
	 <th align="center" width="20%"><span>Forma de Pago</span></th>
	 <th align="center" width="10%"><span>Monto pagado</span></th>
	 <th align="center" width="10%"><span>Total de Venta</span></th>

</tr>
 </thead>
  <tbody>
  	
  </tbody>
  <tfoot>
  	
  </tfoot>	
</table>

<script type="text/javascript">
//function carga(){
// $(function(){
//   
  // $('#datos').tablesorter();
//   
// });
 new Tablesort(document.getElementById('datos'));
//}
</script>
</html>