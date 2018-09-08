<script type="text/javascript">
jQuery(function () {
	/*$( "#frm" ).find("div[class='row']").eq(-2).after('<div class="row">\
	<div class="col-md-4 nmfieldcell">\
	<label>Selecciona un PAC:\
    <select class="form-control" id="pac" onchange>\
      <option value="1" selected="selected">Azurian</option>\
      <option value="2">Formas continuas</option>\
    </select>\
  </label>\
  </div>\
</div>');*/
$('#i2332').hide();
$('#frm').find('div').find('#lbl2332').append('<select class="form-control" id="pac" onchange="pacF();"><option value="1">Azurian</option><option value="2">Formas continuas</option><option value="2">Konesh</option</select>');

	var pacid = $('#i2332').val();
	if(pacid==1){
		$('#pac > option[value="'+pacid+'"]').attr('selected', 'selected');
		$('#lbl2334').hide();
		$('#lbl2335').hide();
		$('#i2334').hide();
		$('#i2335').hide();
	}else{
		$('#pac > option[value="'+pacid+'"]').attr('selected', 'selected');
		$('#lbl2334').show();
		$('#lbl2335').show();
		$('#i2334').show();
		$('#i2335').show();
	}

	$('#send').prop('type','button');
	$('#send').attr('onclick','elupload();');
	$('#i2265').hide();
	$('#frm').find('div').find('#lbl2265').append('<select class="form-control" id="ticket" onchange="ticketF();"><option value="1">Si</option><option value="0">No</option></select>');
	var id = $('#i1312').val();
	if(id > 0){
		var x = $('#i2265').val();
		$('#ticket > option[value="'+x+'"]').attr('selected', 'selected');
	}

});
function elupload(){
	var id = $('#i1312').val();
	mal=0;
	$( '.archivo' ).each(function() {
		cad = $(this).val();
		if(cad.match(/(.cer)$|(.key)$/)){

		}else{
			mal=mal+1;
		}
	});

	if(mal==0 || id > 0){
		$('form').submit();
	}else{
		alert('Solo puedes subir archivos con extension .cer o .key');
	}
}
function ticketF(){

	var ticket = $('#ticket').val();
	$('#i2265').val(ticket);
}
function pacF(){
	var pac = $('#pac').val();
	$('#i2332').val(pac);
	if(pac==1){
		$('#lbl2334').hide();
		$('#lbl2335').hide();
		$('#i2334').hide();
		$('#i2335').hide();
	}else{
		$('#lbl2334').show();
		$('#lbl2335').show();
		$('#i2334').show();
		$('#i2335').show();
	}
}
</script>
