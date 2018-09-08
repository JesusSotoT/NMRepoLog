<!DOCTYPE html>
<html>
	<script type="text/javascript" src="../js/jquery-1.10.2.min.js"></script>
		<script type="text/javascript" src="../js/tablesort.min.js"></script>

	<LINK href="../../../netwarelog/catalog/css/view.css" title="estilo" rel="stylesheet" type="text/css" />
<LINK href="../../../netwarelog/catalog/css/estilo.css" title="estilo" rel="stylesheet" type="text/css" />
  <link rel="stylesheet" type="text/css" media="all" href="css/styles.css">

<link href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" rel="stylesheet">
<link rel="stylesheet" href="../../../libraries/bootstrap/dist/css/bootstrap.min.css" />
<link rel="stylesheet" href="../css/imprimir_bootstrap.css" type="text/css">
<style type="text/css">
	.tit_tabla_buscar td
    {
        font-size:medium;
    }

    #logo_empresa /*Logo en pdf*/
    {
        display:none;
    }

    @media print
    {
        #imprimir,#filtros,#excel,#email_icon, #botones
        {
            display:none;
        }
        #logo_empresa
        {
            display:block;
        }
        .table-responsive{
            overflow-x: unset;
        }
        .pagination, input[type='button'], input[type='submit'], img{
    		display: none;
    	}
    	body{
    		overflow: unset !important;
    	}
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
    h5, h4, h3{
        background-color: #eee;
        padding: 0.4em;
    }
    .modal-title{
        background-color: unset !important;
        padding: unset !important;
    }
    .nmwatitles, [id="title"] {
    	border-bottom: 2px solid #ffffff;
	    box-shadow: 0 1px 0 #cdcdcd;
	    color: #07aa9e;
	    font-size: 18px;
	    font-weight: bold;
	    margin: 5px 10px 0 0;
	    padding: 8px 100px 3px 5px;
	    text-shadow: 0 1px 0 #ffffff;
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
</style>
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
<script type="text/javascript" src="../../punto_venta/js/ui.datepicker-es-MX.js"></script>
<script type="text/javascript">
	// $(function(){
	// $.datepicker.setDefaults($.datepicker.regional['es-MX']);
	// $("#fin").datepicker({dateFormat: "yy-mm-dd"});
	// $("#inicio").datepicker({dateFormat: "yy-mm-dd",onSelect: function (dateText, inst) {
	  // var parsedDate = $.datepicker.parseDate('yy-mm-dd', dateText);
		// $('#fin').datepicker('setDate', parsedDate);
		// $('#fin').datepicker( "option", "minDate"
			// , parsedDate);
			// }});
// 
			// });
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
                    var sucursal = jQuery('#sucursal').val();
					var idprove = jQuery('#proved').val();
					if (idprove == "elije") {
						alert("Elija un proveedor");
					} else {
						if(sucursal=="elije"){
							alert("Elija una sucursal");
						}else{
						//alert(idprove);
						$('body').css('overflow','hidden');
		               $('#carga').css('display','block');//show();
						switch (val) {
							case 1:
								//radio 1
								var fecha = jQuery('#fecha').val();
								
								 $.post("consultas2.php",{opc:3,fecha:fecha,idprove:idprove,sucursal:sucursal},
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
					$.post("consultas2.php",{opc:4,fechainicio:fechainicio,fin:fin,idprove:idprove,sucursal:sucursal},
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

<div class="container" style="width:100%">
    <div class="row">
        <div class="col-md-12">
            <h3 class="nmwatitles text-center">
            	Reporte de ventas<br>(Productos de Proveedor)<br>
                <a href="javascript:window.print();"><img border="0" src="../../../netwarelog/repolog/img/impresora.png"></a>
            </h3>
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                	<section id="filtros">
	                	<h4>Búsqueda por Fecha</h4>
	                    <div class="row">
	                    	<div class="col-md-6 col-sm-6">
	                    		<h5>
	                    			Por periodo
	                    			<input type="radio" name="eleccion" value="1"/>
	                    		</h5>
	                    		<div class="row">
			                        <div class="col-md-12">
			                            <label>&nbsp;</label>
										<select id="fecha" class="form-control">
											<option value="1"  selected>Hoy</option>
											<option value="2" >Ayer</option>
											<option value="3" >Ultimos 7 dias</option>
											<option value="4" >Este a&ntilde;o</option>
											<option value="5"  >Todas</option>
										</select>
			                        </div>
			                    </div>
		                    </div>
		                    <div class="col-md-6 col-sm-6">
		                    	<h5>
		                    		Por fecha
		                    		<input type="radio" name="eleccion" value="2"/>
		                    	</h5>
		                    	<div class="row">
		                    		<div class="col-md-6 col-sm-6">
										<label>Fecha Inicio</label>
										<input type="text" id="inicio" class="form-control"/>
			                        </div>
			                        <div class="col-md-6 col-sm-6">
			                            <label>Fecha Final</label>
										<input type="text" id="fin" class="form-control"/>
			                        </div>
		                    	</div>
		                    </div>
	                    </div>
	                    <h4>Filtrar por</h4>
	                    <div class="row">
	                        <div class="col-md-3 col-sm-3">
	                            <label>Proveedor:</label>
	                            <select id="proved" class="form-control">
									<?php $prove=$conection->query("select idPrv,razon_social from mrp_proveedor  order by razon_social");
									if($prove->num_rows>0){
												?>
						           <option value="elije" selected>-- Elija un proveedor --</option>
									<?php	while($proveedor=$prove->fetch_array(MYSQLI_ASSOC)){ ?>

									<option value="<?php echo $proveedor['idPrv']; ?>"><?php echo utf8_decode($proveedor['razon_social']); ?></option>
								
									<?php	}
									}else{ ?>
									<option selected>--No existen Proveedores registrados--</option>
						          	<?php   }	?>	
								</select>
	                        </div>
	                        <div class="col-md-3 col-sm-3">
	                    		<label>Sucursal:</label>
	                    		<select id="sucursal"  class="form-control" >
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
	                    	</div>
	                    	<div class="col-md-3 col-sm-3">
	                    		<label>&nbsp;</label>
	                    		<input type="button" id="busca" value="Ver" onclick="reporte();" class="btn btn-primary btnMenu"/>
	                    	</div>
	                    	<div class="col-md-3 col-sm-3" id="carga" style="display: none">
	                    		<label style="color:green;">Espera un momento...</label>
	                    	</div>
	                    </div>
	                </section>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12 tablaResponsiva">
                            <div class="table-responsive" id="movimientosmercancia">
                            	<div  style="width: 410px; height:27px; padding-top:3px; ">
									<div class="div1" id="nota" style="display: none; font-size:12px;font-weight:bold; color:#000000;">
										De click sobre el campo que desea para ordenar los datos
										<div class="div2" id="nota2" ></div>
									</div>
								</div>
								<table class="table"  id="datos" cellpadding="3" cellspacing="0" style="font-size:10px; display:none;">
									<thead>
										<tr class="" title="Segmento de búsqueda" style="font-size: 8pt; background-color:#aeaeae;">
											<th align="center"><span>ID VENTA</span></th>
											<th align="center"><span>Fecha</span></th>
											<th align="center"><span>Producto</span></th>
											<th align="center"><span>Proveedor</span></th>
											<th align="center"><span>Sucursal</span></th>
											<th align="center"><span>Cantidad</span></th>
											<th align="center"><span>Precio Unitario</span></th>
											<th align="center"><span>Descuento</span></th>
											<th align="center"><span>Subtotal</span></th>
											<th align="center"><span>Monto Descuento</span></th>
											<th align="center"><span>IVA</span></th>
											<th align="center"><span>Total</span></th>
										</tr>
								 	</thead>
								  	<tbody>
								  	</tbody>
								  	<tfoot>
								  	</tfoot>	
								</table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

 
<script type="text/javascript">
 new Tablesort(document.getElementById('datos'));
</script>

</body>
</html>