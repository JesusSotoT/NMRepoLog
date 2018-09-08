<!DOCTYPE html>
<html>
	<script type="text/javascript" src="../js/jquery-1.10.2.min.js"></script>
	<LINK href="../../../netwarelog/catalog/css/view.css" title="estilo" rel="stylesheet" type="text/css" />
<LINK href="../../../netwarelog/catalog/css/estilo.css" title="estilo" rel="stylesheet" type="text/css" />
<LINK href="../../../netwarelog/design/default/netwarlog.css" title="estilo" rel="stylesheet" type="text/css" />

<script  type="text/javascript" src="css/jTPS.js"></script>
    <link rel="stylesheet" type="text/css" href="css/csstest.css">
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
        maxDate:30,
        numberOfMonths: 1,
        onSelect: function(selected) {
           $("#inicio").datepicker("option","maxDate", selected)
        }
    });

  });	
		function buscaclient(){
			$('body').css('overflow','hidden');
	$('#carga').css('display','block');//show();
		var concepto=jQuery('#busca').val();
		if(concepto!="--Elija un Concepto--"){
		if(concepto!="todos"){
		$.post("consulta.php",{opc:3,concepto:concepto},
	function(respues) {
		$('#datos').html(respues); 
		$('body').css('overflow','auto');
	$('#carga').hide();
		
   	});	
   	
   	
   }else{
   window.location.reload();
   }
	}else{
	alert("Elija un Concepto");
	}
}
	function buscafecha(){
		$('body').css('overflow','hidden');
	$('#carga').css('display','block');//show();
		var inicia=jQuery('#inicio').val();
		var fin=jQuery('#fin').val();
		
		$.post("consulta.php",{opc:4,inicio:inicia,fin:fin},
	function(respues) {
		$('#datos').html(respues); 
		$('body').css('overflow','auto');
	$('#carga').hide();
		
   	});	
   }
   ////////////////////////////////////////////////////
   function buscaestado(){
	$('body').css('overflow','hidden');
	$('#carga').css('display','block');//show();
		var estado=jQuery('#estado').val();
		if(estado!="todo"){		
		$.post("consulta.php",{opc:6,estado:estado},
	function(respues) {
		$('#datos').html(respues); 
		$('body').css('overflow','auto');
	$('#carga').hide();
		
   	});	
  }else{
  	window.location.reload();
  } 	
}
/////////////////////////////////////  
</script>

<link rel="stylesheet" href="../../../libraries/bootstrap/dist/css/bootstrap.min.css" type="text/css">
<style>

	@media print
	{
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
	.btnMenu{
      	border-radius: 0; 
      	width: 100%;
      	margin-bottom: 0.3em;
  	}
  	.row
  	{
      	margin-top: 0.5em !important;
  	}
  	h4, h3{
      	background-color: #eee;
      	padding: 0.4em;
  	}
  	.modal-title{
  		background-color: unset !important;
  		padding: unset !important;
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

<body>
<?php
    include("../../../netwarelog/webconfig.php");
		$conection = new mysqli($servidor,$usuariobd,$clavebd,$bd);	
?>

<div class="container" style="width:100%">
	<div class="row">
		<div class="col-sm-10 col-sm-offset-1">
			<h3 class="nmwatitles text-center">
				Cuentas por Pagar
			</h3>
			<div class="row">
				<div class="col-sm-6">
					<h4>B&uacute;squeda por Concepto</h4>
					<div class="col-sm-6">
						<label>Concepto:</label>
						<select id="busca" class="form-control">
							<?php
							$busca=$conection->query("select  c.idCxp ID,c.concepto
							from cxp c group by c.concepto ");
							if($busca->num_rows>0){ ?>
							<option selected>--Elija un Concepto--</option>
							
								
							<?php	while($concepto=$busca->fetch_array(MYSQLI_ASSOC)){ ?>
									<option ><?php echo utf8_encode($concepto['concepto']); ?></option>
							<?php } ?>
							
							<option value="todos">Todos</option>
							<?php }else{?>	
							
							<option selected>--No hay Adeudos--</option>
							<?php } ?>
						</select>
					</div>
					<div class="col-sm-6">
						<label>&nbsp;</label>
						<input type="button" id="busca" value="Buscar" onclick="buscaclient();" class="btn btn-primary btnMenu"/>
					</div>
				</div>
				<div class="col-sm-6">
					<h4>Busqueda por estado</h4>
					<div class="col-sm-6">
						<label>Estado:</label>
						<select id="estado" class="form-control">
							<option value="todo" selected>-- Todos --</option>
							<option value="0">Activas</option>
							<option value="1">Saldadas</option>
							<option value="<?php echo date('Y-m-d '); ?>">Vencidas</option>
						</select>
					</div>
					<div class="col-sm-6">
						<label>&nbsp;</label>
						<input type="button" id="busca" value="Buscar" onclick="buscaestado();" class="btn btn-primary btnMenu"/>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-6">
					<h4>Busqueda por Fecha</h4>
					<div class="col-sm-4">
						<label>Fecha Inicio:</label>
						<input type="text" id="inicio" class="form-control"/>
					</div>
					<div class="col-sm-4">
						<label>Fecha Final:</label>
						<input type="text" id="fin" class="form-control"/>
					</div>
					<div class="col-sm-4">
						<label>&nbsp;</label>
						<input type="button" id="busca" value="Buscar" onclick="buscafecha();" class="btn btn-primary btnMenu"/>
					</div>
				</div>
				<div class="col-sm-6" id="carga" style="display: none;">
					<div class="col-sm-12">
						<label style="color:green;">Espera un momento...</label>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12 col-xs-12 tablaResponsiva">
					<div class="table-responsive">
						<table class="busqueda" id="datos" cellpadding="3" cellspacing="0" width="100%" height="95%" >
										<thead><tr class="tit_tabla_buscar" title="Segmento de búsqueda" style="font-size: 9pt;">
										<th class="nmcatalogbusquedatit" align="center" sort="id">ID</th>
										<th class="nmcatalogbusquedatit" align="center" sort="cargo">Fecha Cargo</th>
										<th class="nmcatalogbusquedatit" align="center" sort="vence">Fecha Vencimiento</th>
										<th class="nmcatalogbusquedatit" align="center" sort="concep">Concepto</th>
										<th class="nmcatalogbusquedatit" align="center" sort="prove">Proveedor</th>
										<th class="nmcatalogbusquedatit" align="center" sort="monto">Monto</th>
										<th class="nmcatalogbusquedatit" align="center" sort="abonado">Saldo Abonado</th>
										<th class="nmcatalogbusquedatit" align="center" sort="actual">Saldo Actual</th>
										<th class="nmcatalogbusquedatit" align="center" sort="estado">Estado</th>
										</tr></thead><tbody>
							<?php $consul=$conection->query("select c.idCxp ID,c.concepto,
							c.monto,c.saldoabonado,c.saldoactual, SaldoActual,
							c.fechacargo,c.fechavencimiento,c.estatus,
							(select  case mp.razon_social when mp.razon_social THEN mp.razon_social ELSE null end from mrp_proveedor mp
							WHERE   mp.idPrv=c.idProveedor) prove
							from cxp c"); 
								//$paginas=($consul->num_rows/$paginacion);if($consul->num_rows%$paginacion!=0){$paginas++;}
										$Saldoabonado=0;
											$SaldoActual=0;
											$monto=0;
											$cont=0;
							                while($lista=$consul->fetch_array(MYSQLI_ASSOC)){ 
							                		 if ($cont%2==0)//Si el contador es par pinta esto en la fila del grid
																{
														    		$color='nmcatalogbusquedacont_1';
																}
																else//Si es impar pinta esto
																{
														    		$color='nmcatalogbusquedacont_2';
																}
														$cont++;
							                	 if($lista['estatus']==1){//saldada ?>
							                	<tr class="<?php echo $color;?>" style="color:#0101DF; font-size: 10pt;">
								<td align="center"><?php echo $lista['ID']; ?></td >
								<td align="center"> <?php echo $lista['fechacargo']; ?> </td>
								<td align="center"> <?php echo $lista['fechavencimiento']; ?> </td>
								<td align="center"> <?php echo utf8_encode($lista['concepto']); ?> </td>
								<td align="center"><?php echo utf8_encode($lista['prove']); ?> </td>
								<td align="center" > <?php echo $lista['monto']; ?> </td>
								<td align="center" ><?php echo $lista['saldoabonado']; ?> </td>
								<td align="center" > <?php echo $lista['SaldoActual']; ?> </td>
							    	<td align="center"> SALDADA </td>
							</tr>
							<?php }else { //vencida
									if($lista['fechavencimiento']<=date('Y-m-d ')){  ?>
									<tr class="<?php echo $color; ?>" style="color:#FF0040; font-size: 10pt;">
								<td align="center"><?php echo $lista['ID']; ?></td >
								<td align="center"> <?php echo $lista['fechacargo']; ?> </td>
								<td align="center"> <?php echo $lista['fechavencimiento']; ?> </td>
								<td align="center"> <?php echo utf8_encode($lista['concepto']); ?> </td>
								<td align="center"><?php echo utf8_encode($lista['prove']); ?> </td>
								<td align="center" > <?php echo $lista['monto']; ?> </td>
								<td align="center" ><?php echo $lista['saldoabonado']; ?> </td>
								<td align="center" > <?php echo $lista['SaldoActual']; ?> </td>
							    	<td align="center"> VENCIDA </td>
							</tr>	
										<?php	}else{ ?>	  	
							          <tr class="busqueda_fila" style=" color:#6E6E6E; font-size: 10pt;">
							                <td align="center"> 
										<?php echo $lista['ID']; ?>
											</td >
											<td align="center">
										<?php echo $lista['fechacargo']; ?>
											</td>
											<td align="center">
										<?php echo $lista['fechavencimiento']; ?>
											<td align="center">
										<?php echo utf8_encode($lista['concepto']); ?>
											</td>
											<td align="center">
										<?php echo utf8_encode($lista['prove']); ?>
											</td>
											<td align="center">
										<?php echo $lista['monto']; ?>
										</td>
											<td align="center">
										<?php echo $lista['saldoabonado']; ?>
											</td>
											<td align="center">
										<?php echo $lista['SaldoActual']; ?>
											</td>
											<td align="center">
										ACTIVA
											</td>
									
											</td>
										</tr>
										<?php
										}
										 } 
										 $monto=$monto+$lista['monto'];
										 $Saldoabonado=$Saldoabonado+$lista['saldoabonado'];
										 $SaldoActual=$SaldoActual+$lista['SaldoActual']; 
										 
										 }//while?>
							 </tbody>
										<tfoot class="nav"><tr class="nmsubtitle"><td></td><td></td><td></td>
								
								<td></td><td style="font-size: 14px;font-weight:bold;font: color:">Totales</td>
								<td style="font-size: 14px;font-weight:bold">$<?php echo number_format($monto, 2, '.', ','); ?></td>
								<td style="font-size: 14px;font-weight:bold">$<?php echo number_format($Saldoabonado, 2, '.', ','); ?></td>
								<td style="font-size: 14px;font-weight:bold">$<?php echo number_format($SaldoActual, 2, '.', ','); ?></td>
								<td style="font-size: 14px;font-weight:bold"></td></tr>
								<!-- <?php	for($j=0;$j<5;$j++)
									{	?>
									<tr class="busqueda_fila"><tr class="busqueda_fila2">
								<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
									</tr>
									<?php }?> -->
										
										<tr align="right">
							                        <td colspan=7>
							                                <div class="pagination"></div>
							                                <div class="paginationTitle">Pagina</div>
							                                <div class="selectPerPage"></div>
							                                
							                        </td>
							                </tr>
							       
										 </tfoot>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>



		  <script>

                $(document).ready(function () {
               
                        $('#datos').jTPS( {perPages:[5,12,15,50,'TODO'],scrollStep:1,scrollDelay:30,
                                clickCallback:function () {    
                                        // target table selector
                                        var table = '#datos';
                                        // store pagination + sort in cookie
                                        document.cookie = 'jTPS=sortasc:' + $(table + ' .sortableHeader').index($(table + ' .sortAsc')) + ',' +
                                                'sortdesc:' + $(table + ' .sortableHeader').index($(table + ' .sortDesc')) + ',' +
                                                'page:' + $(table + ' .pageSelector').index($(table + ' .hilightPageSelector')) + ';';
                                }
                        });

                        // reinstate sort and pagination if cookie exists
                        var cookies = document.cookie.split(';');
                        for (var ci = 0, cie = cookies.length; ci < cie; ci++) {
                                var cookie = cookies[ci].split('=');
                                if (cookie[0] == 'jTPS') {
                                        var commands = cookie[1].split(',');
                                        for (var cm = 0, cme = commands.length; cm < cme; cm++) {
                                                var command = commands[cm].split(':');
                                                if (command[0] == 'sortasc' && parseInt(command[1]) >= 0) {
                                                        $('#datos .sortableHeader:eq(' + parseInt(command[1]) + ')').click();
                                                } else if (command[0] == 'sortdesc' && parseInt(command[1]) >= 0) {
                                                        $('#datos .sortableHeader:eq(' + parseInt(command[1]) + ')').click().click();
                                                } else if (command[0] == 'page' && parseInt(command[1]) >= 0) {
                                                        $('#datos .pageSelector:eq(' + parseInt(command[1]) + ')').click();
                                                }
                                        }
                                }
                        }

                        // bind mouseover for each tbody row and change cell (td) hover style
                        $('#datos tbody tr:not(.stubCell)').bind('mouseover mouseout',
                                function (e) {
                                        // hilight the row
                                        e.type == 'mouseover' ? $(this).children('td').addClass('hilightRow') : $(this).children('td').removeClass('hilightRow');
                                }
                        );

                });


        </script>
</body>
</html>