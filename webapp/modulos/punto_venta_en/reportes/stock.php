<?php   
//header('Content-Type: text/html; charset=utf-8');
header('Content-Type: text/html; charset=ISO-8859-1'); 
?>
<script type="text/javascript" src="../js/jquery-1.10.2.min.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
  <!--<link rel="stylesheet" type="text/css" href="../../../netwarelog/design/default/netwarlog.css" / -->

<?php
//ini_set('display_errors', 1);
include('../../../netwarelog/design/css.php');?>
<LINK href="../../../netwarelog/design/<?php echo $strGNetwarlogCSS;?>/netwarlog.css" title="estilo" rel="stylesheet" type="text/css" /> <!--NETWARLOG CSS-->


<LINK href="../../../netwarelog/catalog/css/estilo.css" title="estilo" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="../../../libraries/bootstrap/dist/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="../../../libraries/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../../libraries/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="../../../libraries/typeahead/typeahead.css">
<!--<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" /> -->
		<script type="text/javascript" src="../../punto_venta/js/ui.datepicker-es-MX.js"></script>
        <script type="text/javascript" src="../../../libraries/typeahead/typeahead.js"></script>

        <LINK href="../../../libraries/typeahead/typeahead.css" title="estilo" rel="stylesheet" type="text/css" />

    <link rel="stylesheet" href="../../../libraries/dataTable/css/datatablesboot.min.css">
    <script src="../../../libraries/dataTable/js/datatables.min.js"></script>
    <script src="../../../libraries/dataTable/js/dataTables.bootstrap.min.js"></script>
    <script src="../../../libraries/bootstrap/dist/js/bootstrap.min.js"></script>

<meta charset="UTF-8">
<style>

a
{
	color:black;
	font-weight:bold;
}

@media print
{
	#busca,#crea,.chbx,.detalles,#containerProm
	{
		display:none;
	}
}
td{ word-wrap: break-word }

</style>
<link rel="stylesheet" href="../css/imprimir_bootstrap.css">
<style type="text/css">
  	.btnMenu{
    	border-radius: 0; 
    	width: 100%;
    	margin-bottom: 0.3em;
    	margin-top: 0.3em;
  	}
  	.row{
    	margin-top: 0.5em !important;
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
    @media print{
    	.pagination, input[type='button'], input[type='submit'], img{
    		display: none;
    	}
    	.table-responsive{
			overflow-x: unset;
		}
		#imp_cont{
			width: 100% !important;
		}
    }
</style>
<script language='javascript'>
/*$(document).ready(function() {

}); */
$(document).ready(function(){
/* Error EGA-OV
	$('#tableGrid').DataTable({
                        language: {
                        search: "Buscar:",
                        lengthMenu:"",
                        zeroRecords: "No hay datos.",
                        infoEmpty: "No hay datos que mostrar.",
                        info:"Mostrando del START al END de TOTAL elementos",
                        paginate: {
                            first:      "Primero",
                            previous:   "Anterior",
                            next:       "Siguiente",
                            last:       "Último"
                        }
                     }
    });
*/
	/*$('#busqueda').typeahead({
          source: function (query, process) {
          	//alert('eeee');
            $.ajax({
              url: '../funcionesBD/inv.php',
              type: 'POST',
              dataType: 'JSON',
            data: {
                    q: query,
                    Operacion : '8',
                  },
              success: function(data) {
                console.log(data);
                process(data);
              }
            });
          }
        }); */

    function log( message ) {
      $( "<div>" ).text( message ).prependTo( "#log" );
      $( "#log" ).scrollTop( 0 );
    }


   /* $( "#busqueda" ).autocomplete({

      source: function( request, response ) {
        $.ajax({
          url: "../funcionesBD/inv.php",
          type: 'POST',
          dataType: "json",
          data: {
            q: request.term,
            Operacion : '8',
          },
          success: function( data ) {
          	console.log(data);
            response( data );
          }
        });
      }

    }); */
        var productos = $('#busqueda').typeahead({
            hint: true,
            highlight: true,
            minLength: 1
        },
        {
            name: 'nombre',
            displayKey: 'nombre',
            source: function(query, process) {

                if ($('#busqueda').val() != '')
                {
                    $.ajax({
                        url: '../funcionesBD/inv.php',
                        type: 'POST',
                        dataType: 'json',
                        data: {q: query,Operacion : '8'},
                        beforeSend: function() {

                        },
                        success: function(data) {
                            console.log(data);


                            //$('#search-producto').removeClass('loader');

                                return process(data);

                        },
                        error: function(data)
                        {
                           // $('#search-producto').removeClass('loader');
                        }
                    })
}
}
}).on('typeahead:selected', function(event, data) {


});

	$.ajax({
		url: '../funcionesBD/inv.php',
		type: 'POST',
		dataType: 'json',
		data: {Operacion: 4},
	})
	.done(function(data) {
		console.log('perroo');
	console.log(data);
		$('#depDiv').html(data.dep);
		$('#tallaDiv').html(data.talla);
		$('#colorDiv').html(data.col);

	})
	.fail(function() {
		console.log("error");
	})
	.always(function() {
		console.log("complete");
	});




	
	$('.inv').click(function(event)
		{
			event.preventDefault()
			$('#datos').html("")
			$('#invent').modal('show');
			var ia = this.id.split('/');
			$.post("../funcionesBD/inv.php",
 			 {
    			Id: ia[0],
    			Almacen: ia[1],
    			Operacion:1
  			 },
  			 function(data)
  			 {
  			 	$('#id').val(ia[0])
				$('#almacen').val(ia[1])
				$('#datos').html(data)

  			 });


		});

	$('.det').click(function(event)
		{
			event.preventDefault()
			$('#contenido2').html("")
			$('#detalles').modal('show');
			var ia = this.id.split('/');
			$.post("../funcionesBD/inv.php",
 			 {
    			Id: ia[0],
    			Almacen: ia[1],
    			Operacion:3
  			 },
  			 function(data)
  			 {
  			 	$('#contenido2').html(data)
  			 });


		});



});
function codigobarras()
	{
		var str = ''
		//Busca entre todos los checkboxes seleccionados los valores
		//$("input:checkbox:checked").each(function(index)
		$(".chbx:checked").each(function(index)
		{

				if(index == 0)//Si es el primer o solo es un registro se agrega a la cadena sin coma
					{
						str = $(this).val()
					}
					else
						{
							//Se hace una validacion, si el id del producto esta repetido no se agrega a la cadena
							//////////////////////////////////////////////////////////////////////////////////////

							var cad = str.split(",");//Se divide la cadena por sus comas
							var cont=0; //Contador inicializa en Cero

							//Se analiza palabra por palabra de la cadena
							for(var i=0;i<=cad.length;i++)
							{
								//Si el id esta repetido se suma el contador
								if(cad[i] == $(this).val()){ cont++; }
							}

							//Si el contador no devuelve nada entonces no hay palabra repetida por lo que se agrega a la cadena (con coma)
							if(!cont){ str += "," + $(this).val(); }
						}

		});
		//alert(str)
		//Si la cadena devuelve un valor se abre el generador de codigos de barras, si no , genera un alert
		if(str)
		{
			//window.open('../../posclasico/index.php/items/generate_barcodes/' + str)
			window.open('../barcode/generar.php?id_prods=' + str)
		}
		else
		{
			alert('Debes seleccionar un artículo para generar el código de barras.')
		}
	}

function todos()
{
	if($("#all").is(':checked'))
	{
		$(".chbx").click();

	}
	else
	{
		$(".chbx").removeAttr('checked');

	}
}
function excel()
	{
		window.location = '../../cont_repolog/excel/generaexcel.php?nombreseccion=reporte inventario actual'
	}

//Validacion que solo permite numeros en un input text
function validar_let(e) { // 1
    tecla = (document.all) ? e.keyCode : e.which; // 2
    if (tecla==8) return true; // 3
patron = /^\d*(\.\d*)?$/; // Solo acepta nÃºmeros y puntos 4
    te = String.fromCharCode(tecla); // 5
    return patron.test(te); // 6
}
function buscaFam(){
	var idDep = $('#departamento').val();
	$.ajax({
		url: '../funcionesBD/inv.php',
		type: 'POST',
		dataType: 'json',
		data: {Operacion: 5,idDep:idDep},
	})
	.done(function(data) {
		console.log(data);
		$('#famDiv').empty();
		$('#famDiv').html(data.fam);

		$('#linDiv').empty();
		$('#linDiv').append('<select name="linea" class="form-control" id="linea"><option value="0" selected >-Linea-</option></select>');
		console.log("success");
	})
	.fail(function() {
		console.log("error");
	})
	.always(function() {
		console.log("complete");
	});

}
function buscaLin(){
	var idFam = $('#familia').val();
	$.ajax({
		url: '../funcionesBD/inv.php',
		type: 'POST',
		dataType: 'json',
		data: {Operacion: 6,idFam:idFam},
	})
	.done(function(data) {
		console.log(data.lin);
		$('#linDiv').empty();
		$('#linDiv').append(data.lin);

		console.log("success");
	})
	.fail(function() {
		console.log("error");
	})
	.always(function() {
		console.log("complete");
	});

}
function costoPromedio(idProducto,idAlmacen){

	 $.ajax({
	 	url: '../funcionesBD/inv.php',
	 	type: 'POST',
	 	dataType: 'html',
	 	data: {Operacion: 7,idProducto:idProducto,idAlmacen:idAlmacen},
	 })
	 .done(function(data) {
	 	console.log(data);

	 		$.datepicker.setDefaults($.datepicker.regional['es-MX']);
			$("#hasta").datepicker({dateFormat: "yy-mm-dd"});
			$("#inicio").datepicker({dateFormat: "yy-mm-dd",onSelect: function (dateText, inst) {
			  var parsedDate = $.datepicker.parseDate('yy-mm-dd', dateText);
				$('#hasta').datepicker('setDate', parsedDate);
				$('#hasta').datepicker( "option", "minDate", parsedDate);
			}});
	 	$('#containerProm').html(data);
				$("#promCost").dialog({
			      autoOpen: true,
			      width: 1000,
			      height: 550,
			      modal: true,
			    });

	 		$.datepicker.setDefaults($.datepicker.regional['es-MX']);
			$("#hasta").datepicker({dateFormat: "yy-mm-dd"});
			$("#desde").datepicker({dateFormat: "yy-mm-dd",onSelect: function (dateText, inst) {
			  var parsedDate = $.datepicker.parseDate('yy-mm-dd', dateText);
				$('#hasta').datepicker('setDate', parsedDate);
				$('#hasta').datepicker( "option", "minDate", parsedDate);
			}});

	 })
	 .fail(function() {
	 	console.log("error");
	 })
	 .always(function() {
	 	console.log("complete");
	 });

}
function promedioFechas(){
	//alert('perrooo');
	var idProducto = $('#producto').val();
	var idAlmacen = $('#almacenProm').val();
	var desde = $('#desde').val();
	var hasta = $('#hasta').val();
	alert(idProducto);
	alert(idAlmacen);
	alert(desde);
	alert(hasta);
	$.ajax({
	 	url: '../funcionesBD/inv.php',
	 	type: 'POST',
	 	dataType: 'html',
	 	data: {Operacion: 7,idProducto:idProducto,idAlmacen:idAlmacen,desde:desde,hasta:hasta},
	 })
	 .done(function(data) {
	 	console.log(data);

	 		$.datepicker.setDefaults($.datepicker.regional['es-MX']);
			$("#hasta").datepicker({dateFormat: "yy-mm-dd"});
			$("#desde").datepicker({dateFormat: "yy-mm-dd",onSelect: function (dateText, inst) {
			  var parsedDate = $.datepicker.parseDate('yy-mm-dd', dateText);
				$('#hasta').datepicker('setDate', parsedDate);
				$('#hasta').datepicker( "option", "minDate", parsedDate);
			}});
	 	$('#containerProm').empty();
	 	$('#containerProm').html(data);

	 		$.datepicker.setDefaults($.datepicker.regional['es-MX']);
			$("#hasta").datepicker({dateFormat: "yy-mm-dd"});
			$("#desde").datepicker({dateFormat: "yy-mm-dd",onSelect: function (dateText, inst) {
			  var parsedDate = $.datepicker.parseDate('yy-mm-dd', dateText);
				$('#hasta').datepicker('setDate', parsedDate);
				$('#hasta').datepicker( "option", "minDate", parsedDate);
			}});


	 })
	 .fail(function() {
	 	console.log("error");
	 })
	 .always(function() {
	 	console.log("complete");
	 });

}
function inventGuardar(){
        var Cant = 0;
        var Susagr = '';
        var ok = 0;
        
        if($('#susagr').val() == 'Sustraccion de')
        {
            Cant = $('#enstock').val() - $('#cant').val()
            Susagr = "Sustraccion";
            if($('#cant').val() > 0 && $('#coment').val().length > 0 && parseFloat($('#cant').val()) <= parseFloat($('#enstock').val()))
            {
                ok = 1
            }
        }
        else
        {
            Cant = parseFloat($('#enstock').val()) + parseFloat($('#cant').val())
            Susagr = "Se Agrego";
            if($('#cant').val() > 0 && $('#coment').val().length > 0)
            {
                ok = 1
            }
        }

        if(ok)
        {
            /*$.post("../funcionesBD/inv.php",
             {
            Id: $('#id').val(),
            Almacen: $('#almacen').val(),
            CantidadTotal: Cant,
            Cantidad: $('#cant').val(),
            Tipo: Susagr,
            Comentario: $('#coment').val(),
            Operacion:3
             },
             function()
             {
             // alert(data)
                //$("#invent").modal('hide')
                //location.reload();
               // window.parent.frurl.location.reload();

               alert('iieieiei');
                var iframe = document.getElementById('frurl');
                iframe.src = iframe.src;


             }); */
            $.ajax({
                url: '../funcionesBD/inv.php',
                type: 'post',
                dataType: 'json',
                data: {Id: $('#id').val(),
                        Almacen: $('#almacen').val(),
                        CantidadTotal: Cant,
                        Cantidad: $('#cant').val(),
                        Tipo: Susagr,
                        Comentario: $('#coment').val(),
                        Operacion:2
                    },
            })
            .done(function(data) {
                console.log(data);
                $("#invent").modal('hide')
                location.reload();
            })
            .fail(function() {
                console.log("error");
            })
            .always(function() {
                console.log("complete");
            });
            
        }
        else
        {
            alert('Falta agregar una cantidad correcta o comentario')
        }
    }

</script>

<?php
//session_start();
include("../../../netwarelog/webconfig.php");
$connection = new mysqli($servidor,$usuariobd,$clavebd,$bd);

?>
<!--Formulario de busqueda-->
<div class="container" id="imp_cont">
	<section id="registro_nuevo">
		<div class="row">
			<div class="col-md-12">
				<h3 class="nmwatitles text-center">
					Inventario Actual<br>
					<a href="javascript:window.print();"><img class="nmwaicons" src="../../../netwarelog/design/default/impresora.png" border="0" title='Imprimir'></a>
    			</h3>
			</div>
		</div>
	</section>
	<section>
		<form name='stock' method='get' action=''>
			<div class="row">
				<div class="col-md-3 col-sm-3">
					<input type='text' id="busqueda" name='nombre' placeholder='Codigo o nombre del Articulo' class="form-control" >
				</div>
				<div class="col-md-3 col-sm-3">
					<select name='almacenes' id='almacenes' class="form-control">
	                    <option value='0'>Todos los Almacenes</option>
	                    <?php

	                    $almacenes = $connection->query("SELECT idAlmacen, nombre FROM almacen");
	                    while($obj = $almacenes->fetch_object())
	                    {
	                        echo "<option value='".$obj->idAlmacen."'>".$obj->nombre."</option>";
	                    }
	                    ?>
	                </select>
				</div>
				<div class="col-md-3 col-sm-3">
					<select name="tipo_producto" class="form-control">
	                    <option value="0" selected >-Tipo producto-</option>
	                    <option value="1">Producto</option>
	                    <option value="2">Producir Producto</option>
	                    <option value="3">Material de produccion</option>
	                    <option value="4">kit</option>
	                    <option value="5">cosnumo</option>
	                    <option value="6">servicio</option>
	                </select>
				</div>
				<div class="col-md-3 col-sm-3">
					<div id="depDiv"></div>
				</div>
			</div>
			<div class="row">
	            <div class="col-sm-3">
	                <div id="famDiv">
	                    <select name="familia" class="form-control">
	                        <option value="0" selected >-Familia-</option>
	                    </select>
	                </div>
	            </div>
	            <div class="col-sm-3">
	                <div id="linDiv">
	                    <select name="linea" class="form-control" id='linea'>
	                        <option value="0" selected >-Linea-</option>
	                    </select>
	                </div>
	            </div>
	            <div class="col-sm-3">
	                <div id="colorDiv"></div>
	            </div>
	            <div class="col-sm-3">
	                <div id="tallaDiv"></div>
	            </div>
	        </div>
	        <div class="row">
		        <div class="col-sm-3 col-sm-offset-9">
	                <input type='submit' value='Enviar' name='enviar' class="btn btn-primary btnMenu">
	            </div>
	        </div>
		</form>
	</section>
	<section>
		<?php
		//Al dar click hace busqueda
		if(isset($_GET['enviar']))
		{
			$filtro = '';
			if($_GET['nombre'] != '' AND $_GET['almacenes'] != 0 AND $_GET['tipo_producto'] !='')
			{
				$filtro = " (p.nombre LIKE '%".$_GET['nombre']."%' or p.codigo LIKE '%".$_GET['nombre']."%') AND a.idAlmacen = ".$_GET['almacenes']." and p.tipo_producto=".$_GET['tipo_producto']." and";
			}

			if($_GET['nombre'] != '' AND $_GET['almacenes'] == 0 AND $_GET['tipo_producto']==0)
			{
				$filtro = " (p.nombre LIKE '%".$_GET['nombre']."%' or p.codigo LIKE '%".$_GET['nombre']."%') and";
			}

			if($_GET['nombre'] == '' AND $_GET['almacenes'] != 0 AND $_GET['tipo_producto']==0)
			{
				$filtro = " a.idAlmacen = ".$_GET['almacenes']." and";
			}

			if($_GET['nombre'] == '' AND $_GET['almacenes'] != 0 AND $_GET['tipo_producto'] !=0)
			{
				$filtro = " a.idAlmacen = ".$_GET['almacenes']." and p.tipo_producto=".$_GET['tipo_producto']." and";
			}

			if($_GET['nombre'] == '' AND $_GET['almacenes'] == 0 AND $_GET['tipo_producto'] !=0)
			{
				$filtro = "p.tipo_producto=".$_GET['tipo_producto']." and";
			}

			if($_GET['nombre'] != '' AND $_GET['almacenes'] == 0 AND $_GET['tipo_producto'] !=0)
			{
				$filtro = " (p.nombre LIKE '%".$_GET['nombre']."%' or p.codigo LIKE '%".$_GET['nombre']."%') and p.tipo_producto=".$_GET['tipo_producto']." and";
			}
			if($_GET['linea']!=0){
				$filtro .=' p.idLinea='.$_GET['linea'].' and';
			}
			if($_GET['color']!=0){
				$filtro .=' p.color='.$_GET['color'].' and';
			}
			if($_GET['talla']!=0){
				$filtro .=' p.talla='.$_GET['talla'].' and';
			}
			//echo '('.$filtro.')';
		$Query = "SELECT p.idProducto AS Clave, p.nombre AS Nombre, p.descorta AS Descripcion, s.cantidad AS Cantidad, s.ocupados AS Ocupados, p.minimo AS Minimo, p.maximo AS Maximo, u.compuesto AS Unidad, a.nombre AS Almacen, a.idAlmacen
		FROM mrp_stock s
		INNER JOIN mrp_producto p ON p.idProducto = s.idProducto
		INNER JOIN almacen a ON a.idAlmacen = s.idAlmacen
		LEFT JOIN mrp_unidades u ON u.idUni = p.idunidad
		WHERE ".$filtro." p.estatus=1 ORDER BY p.nombre";
		//echo $Query;
		///////////////////////////////////////////////////////////////////////// * Inicia Paginacion * ///////////////////////////////////
		if(isset($_GET['p']))
		{
			$limite = $_GET['p'];//Si la variable get esta seteada toma su valor
		}
		else
		{
			$limite=0; //si no esta seteada es igual a 0
		}

		//--------------------------------------------------------------------
		$valorLimit = 50;//Valor de la paginacion busca de 30 en 30 limit 0,30
		//--------------------------------------------------------------------

		$inicial = 0;//Valor inicial de la paginacion limit 0,30
		$num=$connection->query($Query);

		$numero = $num->num_rows/$valorLimit; //Consultamos cuantas paginas seran necesarias para la paginacion
		if(is_float($numero))//Si el resultado no es un entero se crea una pagina mas para tomar los registros sobrantes
		{
			$numero+=1;
		}

		//Se crean los botones de la paginacion
		for($nn = 1;$nn<=$numero;$nn++)
		{
			if($_GET['p'] == $inicial)//Si es el actual no se crea el boton solo se queda el numero de pagina
			{
				$paginacion.="| <b>$nn</b> | ";
			}
			else//Si no es el actual se crea el boton de paginacion
			{
				$paginacion.="| <a href='stock.php?nombre=".$_GET['nombre']."&almacenes=".$_GET['almacenes']."&enviar=".$_GET['enviar']."&tipo_producto=".$_GET['tipo_producto']."&linea=".$_GET['linea']."&color=".$_GET['color']."&talla=".$_GET['talla']."&p=$inicial'>$nn</a> | ";
			}
			$inicial+=$valorLimit;//Incrementa la el valor de busqueda para el limit
		}
		///////////////////////////////////////////////////////////////////////// * Termina Paginacion * ///////////////////////////////////

		$_SESSION['consulta'] = $Query." LIMIT ".$limite.",$valorLimit"; //La consulta que se despliega paginada
		//$_SESSION['consulta'] = $Query.' ';
		$newQuery = str_replace(", a.idAlmacen","",$Query);//elimina el idalmacen para que no aparezca en el excel
		$_SESSION['excel'] = $newQuery;//La consulta con todos los registros para excel,
		$consulta=$connection->query($_SESSION['consulta']);
		//echo $_SESSION['consulta'];
		//Muestra resultados
		?>
		<div class="row">
		    <div class="col-sm-12 col-md-12 col-xs-12 tablaResponsiva">
		    	<div class="table-responsive">
					<table class="table table-striped"  style='margin-bottom:10px; font-size:10px;' id="tableGrid">
					    <thead style="background-color:#C2C2C2; ">
						<tr class=''>
							<td class=''><input type='checkbox' id='all' onclick='todos()' class=''> Todos</td>
							<td class=''>Id Producto</td>
							<td class='unique_width'>Nombre</td>
							<td class=''>Descripci&oacute;n</td>
							<td class=''>Cantidad</td>
							<td class=''>En Produccion</td>
							<td class=''>Disponibles</td>
							<td class=''>M&iacute;nimo</td>
							<td class=''>M&aacute;ximo</td>
							<td class=''>Unidades</td>
							<td class=''>Almacen</td>
							<td class=''>Costo Unidad</td>
							<td class=''>Costo inventario</td>
							<td class='detalles '></td>
						</tr>
					    </thead>
					    <tbody>
					<?php
					$cont=1;//Contador
					while($lista = $consulta->fetch_object())
					{
						if ($cont%2==0)//Si el contador es par pinta esto en la fila del grid
						{
					    	$color='';
						}
						else//Si es impar pinta esto
						{
					    	$color='';
						}

							/*$queryVentas = "SELECT sum(cantidad) from venta_producto where idProducto=".$lista->Clave;
							$conVentas=$connection->query($queryVentas); */
							$queryNombre = "SELECT idProducto,nombre,costo,stock_inicial from mrp_producto where idProducto=".$lista->Clave;
							$datosPro = $connection->query($queryNombre);
							while($datpro = $datosPro->fetch_object()){
							$costInicial = $datpro->costo;
							$cantidadInicial = $datpro->stock_inicial;
							$costoTotInicial = $costInicial * $cantidadInicial;
							 $tableP.='<h1>'.utf8_encode($datpro->nombre).'<h1>';
							 $tableP.='<input type="hidden" value="'.$datpro->idProducto.'" id="producto">';
							}

							$queryCompras = "(SELECT 'compra' as movimiento, o.fecha_pedido,p.cantidad,p.ultCosto, (p.cantidad * p.ultCosto) as costo_total from mrp_producto_orden_compra p, mrp_orden_compra o where o.idOrd=p.idOrden and p.idProducto=".$lista->Clave." and o.estatus='Cerrada' and o.idAlmacen=".$lista->idAlmacen.") UNION (select 'Venta' as movimiento ,v.fecha as fecha_pedido,p.cantidad,'' as ultCosto,'' as costo_total from venta_producto p, venta v where v.idVenta=p.idVenta and   p.idProducto=".$lista->Clave.") order by fecha_pedido";
							$conCompras=$connection->query($queryCompras);
							/////Estos son las buuenas
							/*$cantidad=0;
							$costoTotal = 0;
							$costoUnidad = 0; */
							$cantidad=$cantidadInicial;
							$costoTotal = $costoTotInicial;
							$costoUnidad = $costInicial;

							$tableP .= '<table border="1"><tr><td>cantidad</td><td>costounidad</td><td>costototal</td><td>VCantidad</td><td>VCostoUnidad</td><td>VCostoTotal</td><td>Saldocantidad</td><td>Saldocostounidad</td><td>Saldocostototal</td></tr>';

							while($compras = $conCompras->fetch_object()){

								$tableP .= '<tr>';
								if($compras->movimiento =='compra'){
									$tableP.= '<td>'.$compras->cantidad.'</td>'.'<td>'.$compras->ultCosto.'</td>'.'<td>'.$compras->costo_total.'</td>';
									$tableP.='<td></td><td></td><td></td>';
										$cantidad +=$compras->cantidad;
										$costoTotal += $compras->cantidad * $compras->ultCosto;
										$costoUnidad = $costoTotal / $cantidad;
										$tableP.='<td>'.number_format($cantidad,2).'</td><td>'.number_format($costoUnidad,2).'</td><td>'.number_format($costoTotal,2).'</td>';
								}else{
									$tableP.='<td></td><td></td><td></td>';
									$tableP.= '<td>'.number_format($compras->cantidad,2).'</td>'.'<td>'.number_format($costoUnidad,2).'</td>'.'<td>'.number_format(($compras->cantidad*$costoUnidad),2).'</td>';
										$cantidad -=$compras->cantidad;
									$tableP.='<td>'.number_format($cantidad,2).'</td><td>'.number_format($costoUnidad,2).'</td><td>'.number_format(($costoTotal-($compras->cantidad*$costoUnidad)),2).'</td>';
									$costoTotal=($costoTotal-($compras->cantidad*$costoUnidad));

								}
								/////esto es lo bueno
								/*$tableP.= '<td>'.$compras->cantidad.'</td>'.'<td>'.$compras->ultCosto.'</td>'.'<td>'.$compras->costo_total.'</td>';
								$cantidad +=$compras->cantidad;
								$costoTotal += $compras->cantidad * $compras->ultCosto;
								$costoUnidad = $costoTotal / $cantidad;
								$tableP.='<td>'.$cantidad.'</td><td>'.$costoUnidad.'</td><td>'.$costoTotal.'</td>'; */
								//////
								$tableP .= '</tr>';
							}


							//echo $tableP;
							$tableP='';
						echo "<tr class=''>
							<td class=''><input type='checkbox' class='chbx nminputcheck' value='".$lista->Clave."' ></td>
							<td class=''>".$lista->Clave."</td>
							<td title='".$lista->Nombre."' class='unique_width'>".substr($lista->Nombre,0,50)."</td>
							<td title='".$lista->Descripcion."' class=''>".substr($lista->Descripcion,0,15)."</td>
							<td class=''>".number_format($lista->Cantidad,2)."</td>
							<td class=''>".number_format($lista->Ocupados,2,'.','')."</td>
							<td class=''>".number_format(($lista->Cantidad-$lista->Ocupados),2,'.','')."</td>
							<td class=''>".number_format($lista->Minimo,2)."</td>
							<td class=''>".number_format($lista->Maximo,2)."</td>
							<td class=''>".$lista->Unidad."</td>
							<td class=''>".$lista->Almacen."</td>
							<td class=''><div onClick='costoPromedio(".$lista->Clave.",".$lista->idAlmacen.");'>$".number_format($costoUnidad,2)."</div></td>
							<td class=''><div onClick='costoPromedio(".$lista->Clave.",".$lista->idAlmacen.");'>$".number_format($costoTotal,2)."</div></td>
							<td class='detalles '><a href='#' class='inv' id='$lista->Clave/$lista->idAlmacen'>Invent.</a> <br> <a href='#' class='det' id='$lista->Clave/$lista->idAlmacen'>Detalles</a></td>
						</tr>";
						$cont++;//Incrementa contador
					}
					//-------------------------Aqui comienza generador de excel---------------------
					echo "<div class='pagination' style='margin-left:10px;margin-bottom:10px'>Resultados: ".$paginacion."</div>";//Pinta la paginacion
					?>
					</tbody>
					</table>
				</div>
		    </div>
		</div>
	</section>
	<section>
		<div style='margin-left:10px;' id='crea'>
			<div class="row">
				<div class="col-sm-3 col-sm-offset-6">
					<input type="button" name="barcode" value="Generar C&oacute;digo de Barras" onClick="javascript:codigobarras()" class="btn btn-primary btnMenu">
				</div>
				<div class="col-sm-3">
					<input type="button" name="crea" value="Enviar la consulta a Excel" onClick="javascript:excel()" class="btn btn-primary btnMenu">
				</div>
			</div>
		</div>
	</section>
</div>


<?php

//-------------------------Aqui termina generador de excel----------------------


//Termina if isset
}
//Cierra conexion
$connection->close();
?>

<div id='invent' class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Sustraer / Agregar Inventario</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                    	<label>ID:</label>
                    	<input class="form-control" type='text' id='id' value='' readonly >
                    </div>
                    <div class="col-md-6">
                    	<label>Almacen:</label>
                    	<input class="form-control" type='text' id='almacen' value='' readonly >
                    </div>
                </div>
                <div class="row">
                	<div class="col-md-12 col-sm-12 col-xs-12 tablaResponsiva">
                		<div class="table-responsive" id='datos'>
                		</div>
                	</div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                    	<label>Operacion:</label>
                    	<select name='susagr' id='susagr' class="form-control">
                    		<option value='Sustraccion de'>Sustraer</option>
                    		<option value='Se Agrego'>Agregar</option>
                    	</select>
                    </div>
                    <div class="col-md-6">
                    	<label>Cantidad:</label>
                    	<input class="form-control" type='cant' id='cant' placeholder='Cantidad' onkeypress="return validar_let(event)">
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <label>Comentario:</label>
                        <textarea class="form-control" name="coment" id="coment" cols="30" rows="5"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
            	<div class="row">
                    <div class="col-md-3 col-md-offset-9">
                        <button type="button" class="btn btn-primary btnMenu" onclick="javascript:inventGuardar();">Guardar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div id="promCost" style="display:none;" title="Costo Promedio">
	<div id="containerProm" style="overflow:auto;">

	</div>
</div>

<div id='detalles' class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Detalles de inventarios (&Uacute;ltimos 10 movimientos de agregar o sustraer)</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                	<div class="col-md-12 col-sm-12 col-xs-12 tablaResponsiva">
                		<div class="table-responsive" id='contenido2'>
                		</div>
                	</div>
                </div>
            </div>
            <div class="modal-footer">
            	<div class="row">
                    <div class="col-md-3 col-md-offset-9">
                        <button type="button" class="btn btn-danger btnMenu" onclick="javascript:$('#detalles').modal('hide');">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
