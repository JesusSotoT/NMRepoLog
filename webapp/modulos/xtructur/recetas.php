<?php 
$SQL = "SELECT id,nomfam famat FROM constru_famat ORDER BY nomfam;";
  $result = $mysqli->query($SQL);
  if($result->num_rows>0) {
    while($row = $result->fetch_array() ) {
      $familias[]=$row;
    }
  }else{
    $familias=0;
  }

 $SQL = "SELECT id,codigo FROM constru_bit_pubasico WHERE id_obra='$idses_obra' ORDER BY codigo;";
  $result = $mysqli->query($SQL);
  if($result->num_rows>0) {
    while($row = $result->fetch_array() ) {
      $basicos[]=$row;
    }
  }else{
    $basicos=0;
  }

  $SQL = "SELECT id,nombre FROM constru_agrupador WHERE id_obra='$idses_obra' AND borrado=0;";
	$result = $mysqli->query($SQL);
	  if($result->num_rows>0) {
	    while($row = $result->fetch_array() ) {
	      $agrupadores[]=$row;
	    }
	  }else{
	    $agrupadores=0;
	  }
	

?>

<style>
.p{
	padding:0px;
}
.p10{
	padding:5px;
}
.panel-title{
	background-color: #f4f4f4;
}
</style>
<div>

        <div class="navbar navbar-default"  style="margin-top:10px;">
          <div class="navbar-header">
              <div class="navbar-brand" style="color:#333;">Asignar PU a Conceptos</div>
          </div>
        </div>
<div class="col-md-12 p">
	<div class="col-md-2" style="padding:0px;">
	<select id="selReceta" class="form-control" >
		<option value="0">Seleccione una opcion</option>
		<option value="1">Concepto</option>
		<option value="2">PU Basico</option>
	</select>
	</div>

	<div class="row">&nbsp;</div>

</div>
</div>
<div class="row">&nbsp;</div>
<div class="row" style="padding-top: 15px;">
<div class="col-md-5">	
	<div id="panelInsumos" class="col-md-12 p" style="display:none;">	
		<div class="panel panel-default">
			<div aria-expanded="true" aria-controls="collapse_insumos" href="#tab_insumos" data-parent="#accordion_insumos" data-toggle="collapse" style="cursor: pointer" role="tab" id="heading_insumos" class="panel-heading" hrefer="">
				<h4 class="panel-title">
					<strong>Insumos</strong>
				</h4>
			</div>
			<div aria-labelledby="heading_insumos" role="tabpanel" class="panel-collapse collapse" id="tab_insumos">
			<div id="bodinsumos" class="panel-body">
				<div class="col-md-4">
					Material
				</div>
				<div class="col-md-8">
					<select onchange="selectFam();" id="id_material" style="width:250px;">
						<option value="0" selected="selected">Seleccione</option>
						<option value="t">Todas</option>
						<?php foreach ($familias as $k => $v) { ?>
							<option value="<?php echo $v['id']; ?>"><?php echo utf8_encode($v['famat']); ?></option>
						<?php } ?>
					</select>
				</div>
				<div class="row">&nbsp;</div>
				<div class="col-md-4">
					Clave
				</div>

				<div class="col-md-8">
					<select  onchange="selectCla();"  id="id_clave" style="width:250px;">
						
					</select>
				</div>
				<div id="contclaves">
				</div>
			</div>
			</div>
		</div>
	</div>
	<div id="panelBasicos" class="col-md-12 p"  style="display:none;">	
	<div class="panel panel-default">
		<div aria-expanded="true" aria-controls="collapse_basicos" href="#tab_basicos" data-parent="#accordion_basicos" data-toggle="collapse" style="cursor: pointer" role="tab" id="heading_basicos" class="panel-heading" hrefer="">
			<h4 class="panel-title">
				<strong>PU Basicos</strong>
			</h4>
		</div>
		<div aria-labelledby="heading_basicos" role="tabpanel" class="panel-collapse collapse" id="tab_basicos">
		<div id="bodbasicos" class="panel-body">
			<div class="col-md-4">
				Codigo
			</div>
			<div class="col-md-8">
				<select onchange="selectBas();" id="id_basico" style="width:250px;">
					<option value="0" selected="selected">Seleccione</option>
					<?php foreach ($basicos as $k => $v) { ?>
						<option value="<?php echo $v['id']; ?>"><?php echo utf8_encode($v['codigo']); ?></option>
					<?php } ?>
				</select>
			</div>
			<div class="row">&nbsp;</div>
			<div id="contbasicos">

			</div>
		</div>
		</div>
	</div>
	</div>
</div>


<div class="col-md-7">
<div  id="panelDerecha" class="col-md-12 p" style="display:none;">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h4 class="panel-title">
				<strong>
					PU Basico
				</strong>
			</h4>
		</div>
		<div class="panel-body">
			<div id="listado">
				<div id="agregainsumos">
					<div class="panel panel-default">
					    <div class="panel-body"><b>Agrega insumos o PU Basicos</b></div>
					</div>
				</div>
				<table id="latabla" class="table table-striped" style="font-size:12px;display:none;">
					<tr>
						<th>&nbsp;</th>
						<th width="100%">Descripcion</th>
						<th>Cantidad</th>
						<th>Unidad</th>
						<th>PU</th>
						<th>Total</th>
					</tr>
					<tbody id="listadobody">
					
					</tbody>

					<tbody id="listadofoot">
					</tbody>

				</table>
			</div>

			<div id="combos">
				<div class="col-md-3 p10">
					Agrupador
				</div>
				<div class="col-md-8 p10">
					<select id="cha1" onchange="cha1();" class="form-control">
						<option value="0">Seleccione</option>
						<?php foreach ($agrupadores as $ak => $av) { ?>
							<option value="<?php echo $av['id']; ?>"><?php echo $av['nombre']; ?></option>
						<?php } ?>
					</select>
				</div>

				<div class="col-md-3 p10">
					Area
				</div>
				<div class="col-md-8 p10">
					<select id="cha2" onchange="cha2();" class="form-control">
						<option value="0">Seleccione</option>
					</select>
				</div>
				<div class="col-md-3 p10">
					Especialidad
				</div>
				<div class="col-md-8 p10">
					<select id="cha3" onchange="cha3();" class="form-control">
						<option value="0">Seleccione</option>
					</select>
				</div>

				<div class="col-md-3 p10">
					Partida
				</div>
				<div class="col-md-8 p10">
					<select id="cha4" onchange="cha4();" class="form-control">
						<option value="0">Seleccione</option>
					</select>
				</div>
				<div class="col-md-3 p10">
					Clave
				</div>
				<div class="col-md-8 p10">
					<select id="cha5" onchange="cha52();" class="form-control">
						<option value="0">Seleccione</option>
					</select>
				</div>
	
			</div>
			<div id="cuerpo">
				<div class="col-md-3 p10">
					Nombre
				</div>
				<div class="col-md-8 p10">
					<input id="ig_nombre" class="form-control" type="text" >
				</div>

				<div class="col-md-3 p10">
					Codigo
				</div>
				<div class="col-md-8 p10">
					<input id="ig_codigo" class="form-control" type="text" >
				</div>
				<div class="col-md-3 p10">
					Unidad
				</div>
				<div class="col-md-3 p10">
					<input id="ig_unidad" class="form-control" type="text" >
				</div>
				<div class="col-md-2 p10">
					Precio
				</div>
				<div class="col-md-3 p10">
					<input id="ig_precio" class="form-control" type="text" >
				</div>
				<div class="col-md-3 p10">
					Preparacion
				</div>
				<div class="col-md-8 p10">
					<textarea id="ig_preparacion" class="form-control" rows="3"></textarea>
				</div>
				<div class="col-md-3 p10">
					<button class="btn btn-primary" onclick="guardarBasico();">Guardar</button>
				</div>
			</div>
		</div>
	</div>
</div>
</div>

</div>


<script>
$( document ).ready(function() {
    $('#ig_precio').numeric('-');
    $('#ig_precio').prop('disabled',true);


    $( "#selReceta" ).change(function() {
    	receta=$(this).val();
	  	if(receta==0){
	 		$('#panelBasicos').css('display','none');
	 		$('#panelInsumos').css('display','none');
	 		$('#panelDerecha').css('display','none');
	 		
	 	}
	 	if(receta==2){
	 		$('#panelBasicos').css('display','block');
	 		$('#panelInsumos').css('display','block');
	 		$('#panelDerecha').css('display','block');
	 		$('#combos').css('display','none');

	 		$('#ig_nombre').prop('disabled',false);
	 		$('#ig_codigo').prop('disabled',false);
	 		$('#ig_unidad').prop('disabled',false);

	 		$('#ig_nombre').val('');
	 		$('#ig_codigo').val('');
	 		$('#ig_unidad').val('');
	 		
	 		refrescaTotal();
	 		
	 	}
	 	if(receta==1){
	 		//PU Concepto
	 		$('#panelInsumos').css('display','block');
	 		$('#panelBasicos').css('display','block');
	 		$('#panelDerecha').css('display','block');



			$("#cha1").val($("#cha1 option:first").val());
			$("#cha2").html('<option value="0">Seleccione</option>');
			$("#cha3").html('<option value="0">Seleccione</option>');
			$("#cha4").html('<option value="0">Seleccione</option>');
			$("#cha5").html('<option value="0">Seleccione</option>');


	 		$('#combos').css('display','block');

	 		$('#ig_nombre').prop('disabled',true);
	 		$('#ig_codigo').prop('disabled',true);
	 		$('#ig_unidad').prop('disabled',true);


	 		$('#ig_nombre').val('');
	 		$('#ig_codigo').val('');
	 		refrescaTotal();
	 		$('#ig_precio').val('');
	 	}
	});
});

 function selectFam(){
 	id_material=$('#id_material').val();
	if(id_material>0 || id_material=='t'){

		$('#id_clave').html('<option value="0">Cargando...</option>');
		$.ajax({
		  url:'ajax.php',
		  type: 'POST',
		  dataType: 'JSON',
		  data: {opcion:'desc_insumos_mat',id_material:id_material},
		  success: function(r){
		    if(r.success==1){
		      $('#id_clave').html('<option value="0">Seleccione</option>');
		      $.each(r.datos, function( k, v ) {
		        $('#id_clave').append('<option value="'+v.id+'">'+v.clave+'</option>');
		      });
		    }else{
		      $('#id_clave').html('<option value="0">No hay insumos</option>');
		    }
		  }
		});
	}
 }

 function selectCla(){
 	id_insumo=$('#id_clave').val();
	if(id_insumo>0){
		$.ajax({
		  url:'ajax.php',
		  type: 'POST',
		  dataType: 'JSON',
		  data: {opcion:'desc_insumos',id_insumo:id_insumo},
		  success: function(r){
		    if(r.success==1){
		    	 $('#contclaves').html('');


				  $('#contclaves').append('<div class="row">&nbsp;</div><div class="col-md-4">Precio</div><div class="col-md-8"><input id="i_precio" disabled="disabled" type="text" role="textbox" class="FormElement ui-widget-content ui-corner-all" style="width: 250px;" value="'+r.datos[0].precio+'"></div><div class="row">&nbsp;</div>');

				  $('#contclaves').append('<div class="col-md-4">Descripcion</div><div class="col-md-8"><input id="i_descripcion" disabled="disabled" type="text" role="textbox" class="FormElement ui-widget-content ui-corner-all" style="width: 250px;" value="'+r.datos[0].descripcion+'"></div><div class="row">&nbsp;</div>');

				  $('#contclaves').append('<div class="col-md-4">Unidad</div><div class="col-md-8"><input id="i_unidad" disabled="disabled" type="text" role="textbox" class="FormElement ui-widget-content ui-corner-all" style="width: 250px;" value="'+r.datos[0].unidtext+'"></div><div class="row">&nbsp;</div>');

				  $('#contclaves').append('<div class="col-md-4">Max Cantidad</div><div class="col-md-8"><input id="i_maxcant" disabled="disabled" type="text" role="textbox" class="FormElement ui-widget-content ui-corner-all" style="width: 250px;" value="'+r.datos.totcant.totcant+'"></div><div class="row">&nbsp;</div>');

                  $('#contclaves').append('<div class="col-md-4">Cantidad</div><div class="col-md-8"><input id="i_cantidad" type="text" value="0.00"></div>');
                  $('#contclaves').append('<div class="col-md-12"><button class="btn btn-primary" onclick="addInsumo('+id_insumo+');">Agregar</button></div>');

                  $('#i_cantidad').numeric('-');

		  }
		}
		});
	}
 }

  function selectBas(){
 	id_basico=$('#id_basico').val();
	if(id_basico>0){
		$.ajax({
		  url:'ajax.php',
		  type: 'POST',
		  dataType: 'JSON',
		  data: {opcion:'desc_basicos',id_basico:id_basico},
		  success: function(r){
		    if(r.success==1){
		    	 $('#contbasicos').html('');


				  $('#contbasicos').append('<div class="row">&nbsp;</div><div class="col-md-4">Nombre</div><div class="col-md-8"><input id="b_nombre" disabled="disabled" type="text" role="textbox" class="FormElement ui-widget-content ui-corner-all" style="width: 250px;" value="'+r.datos[0].pub_nombre+'"></div><div class="row">&nbsp;</div>');

				  $('#contbasicos').append('<div class="col-md-4">Codigo</div><div class="col-md-8"><input id="b_codigo" disabled="disabled" type="text" role="textbox" class="FormElement ui-widget-content ui-corner-all" style="width: 250px;" value="'+r.datos[0].codigo+'"></div><div class="row">&nbsp;</div>');

				  $('#contbasicos').append('<div class="col-md-4">Unidad</div><div class="col-md-8"><input id="b_unidad" disabled="disabled" type="text" role="textbox" class="FormElement ui-widget-content ui-corner-all" style="width: 250px;" value="'+r.datos[0].unidtext+'"></div><div class="row">&nbsp;</div>');

				   $('#contbasicos').append('<div class="col-md-4">Precio</div><div class="col-md-8"><input id="b_precio" disabled="disabled" type="text" role="textbox" class="FormElement ui-widget-content ui-corner-all" style="width: 250px;" value="'+r.datos[0].precio+'"></div><div class="row">&nbsp;</div>');

                  $('#contbasicos').append('<div class="col-md-4">Cantidad</div><div class="col-md-8"><input id="b_cantidad" type="text" value="0.00"></div>');
                  $('#contbasicos').append('<div class="col-md-12"><button class="btn btn-primary" onclick="addBasico('+id_basico+');">Agregar</button></div>');

                  $('#b_cantidad').numeric('-');

		  }
		}
		});
	}
 }

 function addInsumo(idinsumo){
	var existe = $('#tr_'+idinsumo+'[basico="0"]').length;

	if(existe>0){
		alert('Este insumo ya esta agregado');
		return false;
	}

 	i_desc=$("#id_clave option[value='"+idinsumo+"']").text();
 	i_precio=$("#i_precio").val();
 	i_unidad=$("#i_unidad").val();
 	i_maxcant=$("#i_maxcant").val();
 	i_cantidad=$("#i_cantidad").val();
 	tox=(i_precio*i_cantidad);
 	tox = Math.round(tox * 100) / 100;

 	if(i_cantidad<=0){
 		alert('La cantidad tiene que ser mayor a 0');
 		return false;
 	}

 	if( (i_cantidad*1)>(i_maxcant*1) ){
 		alert('La cantidad no puede ser mayor a la Maxima cantidad');
 		return false;
 	}

 	$('#agregainsumos').css('display','none');
 	$('#latabla').css('display','block');

 	$('#listadobody').append('<tr id="tr_'+idinsumo+'" basico="0">\
		<td><button class="btn btn-danger btn-xs" onclick="quitarIns('+idinsumo+',0);">Quitar</button></td>\
		<td>'+i_desc+'</td>\
		<td id="ccant">'+i_cantidad+'</td>\
		<td>'+i_unidad+'</td>\
		<td>'+i_precio+'</td>\
		<td id="totp">'+tox+'</td>\
	</tr>');

	$('#listadofoot').html('<tr>\
				<td colspan="4"></td>\
				<td align="right"><b>Total:</b></td>\
				<td id="ttotal"></td>\
			</tr>');

	refrescaTotal();
 }

  function addBasico(idbasico){
	var existe = $('#tr_'+idbasico+'[basico="1"]').length;

	if(existe>0){
		alert('Este PU basico ya esta agregado');
		return false;
	}

 	b_desc=$("#b_codigo").val();
 	b_precio=$("#b_precio").val();
 	b_unidad=$("#b_unidad").val();
 	b_maxcant=$("#b_maxcant").val();
 	b_cantidad=$("#b_cantidad").val();
 	tox=(b_precio*b_cantidad);
 	tox = Math.round(tox * 100) / 100;

 	if(b_cantidad<=0){
 		alert('La cantidad tiene que ser mayor a 0');
 		return false;
 	}

 	$('#agregainsumos').css('display','none');
 	$('#latabla').css('display','block');

 	$('#listadobody').append('<tr id="tr_'+idbasico+'" basico="1">\
		<td><button class="btn btn-danger btn-xs" onclick="quitarIns('+idbasico+',1);">Quitar</button></td>\
		<td>'+b_desc+'</td>\
		<td id="ccant">'+b_cantidad+'</td>\
		<td>'+b_unidad+'</td>\
		<td>'+b_precio+'</td>\
		<td id="totp">'+tox+'</td>\
	</tr>');

	$('#listadofoot').html('<tr>\
				<td colspan="4"></td>\
				<td align="right"><b>Total:</b></td>\
				<td id="ttotal"></td>\
			</tr>');

	refrescaTotal();
 }

 function refrescaTotal(){
 	total=0;

 	var existe = $("#listadobody tr").length;
 	
 	if(existe>0){
	 	$("#listadobody tr").each(function() {
		 	totp =  $(this).find("#totp").text();
		 	totp = totp*1;
		 	total+=totp;
		});

		total = Math.round(total * 100) / 100;
		$('#ttotal').text(total);

		if( $('#selReceta').val()==2 ){ 
			$('#ig_precio').val(total);
		}
	}else{
		$('#latabla').css('display','none');
		$('#agregainsumos').css('display','block');	
	}


 }

 function quitarIns(idinsumo, basico){
 	$('#tr_'+idinsumo+'[basico="'+basico+'"]').remove();
 	refrescaTotal();
 }


 function guardarBasico(){
 	ig_nombre=$('#ig_nombre').val();
	ig_codigo=$('#ig_codigo').val();
	ig_unidad=$('#ig_unidad').val();
	ig_precio=$('#ig_precio').val();
	ig_preparacion=$('#ig_preparacion').val();
	ttotal=$('#ttotal').text();

	if(ig_nombre=='' || ig_codigo=='' || ig_unidad=='' || ig_precio==''){
		alert('Tienes que llenar los campos del formulario');
		return false;
	}

 	entrada = $('#listadobody tr').map(function() {
      id =$(this).attr('id');
      basico =$(this).attr('basico');
      d=id.split('_');
      id=d[1];

      cant =$(this).find('#ccant').text();
      return id+'='+cant+'='+basico; //area=insumo=vol
      
  	}).get().join(',');

 	if(entrada==''){
 		alert('Tienes que seleccionar un insumo');
 		return false;
 	}

 	if(ttotal==''){
 		alert('No hay total');
 		return false;
 	}

 	if( $('#selReceta').val()==2 ){
	 	$.ajax({
		    url:'ajax.php',
		    type: 'POST',
		    data: {opcion:'save_pubasico',entrada:entrada,ig_nombre:ig_nombre,ig_codigo:ig_codigo,ig_unidad:ig_unidad,ig_precio:ig_precio,ig_preparacion:ig_preparacion,ttotal:ttotal},
		    success: function(r){
		        if(r==1){
		        	alert('PU Basico guardado con exito');
		        	window.location.reload();
		        }
		        if(r=='REP'){
		        	alert('El codigo de este Basico ya esta repetido');
		        	
		        }
		       	
		    }
		});
 	}

 	if( $('#selReceta').val()==1 ){
 		cha5=$('#cha5').val();

 		if(cha5>0){
		 	$.ajax({
			    url:'ajax.php',
			    type: 'POST',
			    data: {opcion:'save_puconcepto',entrada:entrada,ig_nombre:ig_nombre,ig_codigo:ig_codigo,ig_unidad:ig_unidad,ig_precio:ig_precio,ig_preparacion:ig_preparacion,ttotal:ttotal,cha5:cha5},
			    success: function(r){
			        if(r==1){
			        	alert('Concepto guardado con exito');
			        	window.location.reload();
			        }
			        if(r=='REP'){
			        	alert('El codigo de este concepto ya esta repetido');
			        	
			        }
			       	
			    }
			});
	 	}else{
	 		alert('Tienes que seleccionar un concepto');
	 	}
 	}


 }

 function cargarReceta(){
 	receta=$('#selReceta').val();
 	if(receta==0){
 		$('#panelBasicos').css('display','none');
 		$('#panelInsumos').css('display','none');
 		$('#panelDerecha').css('display','none');
 		return false;
 	}
 	if(receta==2){
 		$('#panelBasicos').css('display','none');
 		$('#panelInsumos').css('display','block');
 		$('#panelDerecha').css('display','block');
 	}
 	if(receta==1){
 		$('#panelInsumos').css('display','block');
 		$('#panelBasicos').css('display','block');
 		$('#panelDerecha').css('display','block');
 	}
 }

 function cha1(){

 	$("#cha2").html('<option value="0">Seleccione</option>');
	$("#cha3").html('<option value="0">Seleccione</option>');
	$("#cha4").html('<option value="0">Seleccione</option>');
	$("#cha5").html('<option value="0">Seleccione</option>');

 	id_agrupador=$('#cha1').val();
 	if(id_agrupador>0){
	    $.ajax({
	    url:'ajax.php',
	    type: 'POST',
	    data:{opcion:'areas_dinamic_combo',id_agrupador:id_agrupador},
	    success: function(r){

	        $('#cha2').html('<option role="option" value="0">Seleccione</option>'+r);
	      }
	    });
	}
 }
 function cha2(){

	$("#cha3").html('<option value="0">Seleccione</option>');
	$("#cha4").html('<option value="0">Seleccione</option>');
	$("#cha5").html('<option value="0">Seleccione</option>');

 	id_area=$('#cha2').val();
 	if(id_area>0){
	    $.ajax({
	    url:'ajax.php',
	    type: 'POST',
	    data:{opcion:'especialidad_dinamic_combo',id_area:id_area},
	    success: function(r){
	        $('#cha3').html('<option role="option" value="0">Seleccione</option>'+r);
	      }
	    });    
    }          
 }
 function cha3(){

	$("#cha4").html('<option value="0">Seleccione</option>');
	$("#cha5").html('<option value="0">Seleccione</option>');

 	id_especialidad=$('#cha3').val();
 	if(id_especialidad>0){
	    $.ajax({
	    url:'ajax.php',
	    type: 'POST',
	    data:{opcion:'partida_dinamic_combo',id_especialidad:id_especialidad},
	    success: function(r){
	        $('#cha4').html('<option role="option" value="0">Seleccione</option>'+r);
	      }
	    });
    }            
 }
 function cha4(){

 	$("#cha5").html('<option value="0">Seleccione</option>');

 	id_partida=$('#cha4').val();
 	if(id_partida>0){
 		ar=$('#cha2').val();
	    $.ajax({
        url:'ajax.php',
        type: 'POST',
        data:{opcion:'claves_dinamic_combo_dall',pa:id_partida,ar:ar},
        success: function(r){
            $('#cha5').html('<option role="option" value="0">Seleccione</option>'+r);
          }
        });
    }            
 }


 function cha52(){
 	id_insumo=$('#cha5').val();
 	if(id_insumo>0){
 		ar=$('#cha2').val();
	    $.ajax({
        url:'ajax.php',
        type: 'POST',
        dataType: 'JSON',
        data:{opcion:'desc_destaj_est',id_codigo:id_insumo,id_des:'0',ar:ar},
        success: function(r){
          console.log(r.datos);
          if(r.success==1){
          	$('#ig_nombre').val(r.datos[0].descripcion);
          	$('#ig_codigo').val(r.datos[0].codigo);
          	$('#ig_unidad').val(r.datos[0].unidtext);
          	$('#ig_precio').val(r.datos[0].precio);
          }
    	}   
    	}); 
    }        
 }
</script>