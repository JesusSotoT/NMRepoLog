<?php  @$_SESSION['ivas']; $cadena="";?>
<script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script type="text/javascript" src="http://malsup.github.com/jquery.form.js"></script>
<link href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" rel="stylesheet" type="text/css"/>

<script>

		//$('#lbl1275').hide(); //text
	//$('#i1275').hide();//tercero
	
	// $('#lbl1276').hide(); //text
	// $('#i1276').hide();//operacion
// 	
	// $('#lbl1277').hide(); //text
	// $('#i1277').hide();//curp
// 	
	// $('#lbl1278').hide(); //text
	// $('#i1278').hide();//cuenta
// 	
	// $('#lbl1279').hide(); //text
	// $('#i1279').hide();//cuenta
// 	
	// $('#lbl1280').hide(); //text
	// $('#i1280').hide();//extranjero
// 	
	// $('#lbl1281').hide(); //text
	// $('#i1281').hide();//nacionalidad
// 	
	// $('#lbl1282').hide(); //text
	// $('#i1282').hide();//retenido
// 	
	// $('#lbl1283').hide(); //text
	// $('#i1283').hide();//retenido
// 	
	// $('#lbl1284').hide(); //text
	 $('#i1284').val('1234');//asumir
	 $("tr[title='asumir']").hide();
	 $("tr[title='curp']").hide();
	 $("tr[title='Tipo Tercero']").hide();
	 $("tr[title='Tipo Operacion']").hide();
	 $("tr[title='cuenta']").hide();
	 $("tr[title='Numero ID Fiscal']").hide();
	 $("tr[title='Nombre del extranjero']").hide();
	 $("tr[title='Nacionalidad']").hide();
	 $("tr[title='IVA Retenido']").hide();
	 $("tr[title='ISR Retenido']").hide();
	 
$(function(){
	$(document).ready(function() {
	var label = $("<label>", { 
		id: "aki", 
		 click: function (e) {
		 	}
		 	  });
		 	  $("tr[title='Verifiación de garantia de disponibilidad y precio']").html('<br>');
		 	  $("tr[title='Verifiación de garantia de disponibilidad y precio']").append(label);
		 	  $('#aki').text('Datos fiscales');
	});
});
/////////////////////////////////////////////////////////////////	 
$(function(){
	$(document).ready(function() {
	var lwpButton = $("<select>", { 
		id: "elije", 
		onchange:'cambio();',
		html: '<option selected>No</option>',
		prepend: '<option>Si</option>',
		 click: function (e) {
		 	}
		 	  });
				$("#aki").append(lwpButton);
	});
		 	
 });
	///////////////////////////////////////////////////////////////////////////////	 	
	var div = $("<div>", { 
		id: 'ivas', 
		style:'background-color:#6E6E6E; top:30px; left:10px; width:81px; ',
		click: function (e) {
		 	}
		 	  });
		 	  $("tr[title='asumir']").html('<br>');
				$("tr[title='asumir']").append(div);
	
	for(var i=0;i<8;i++){  
		if(i==0){
			
				$("#ivas").append();
				var tasa = $("<label>", { 
		id: 'tasa', 
		text:'Asumir*  Tasa%',
		click: function (e) {
		 	}
		 	  });
				$("#ivas").append(tasa);
			//	$('#tasa').text('tasa(%)');
	////////////////////////////////////////
	var radi = $("<input>", { 
		id:'ivasumir',
		name: 'ivasumir', 
		type:'radio',
		value:i,
		click: function (e) {
		 	}
		 	  });
				$("#ivas").append(radi);
////////////////////////////////////
var va = $("<input>", { 
		id: 'tasas', 
		type:'checkbox',
		value:i,
		 click: function (e) {
		 	}
		 	  });
				$("#ivas").append(va);
var checo2 = $("<label>", { 
		 id: i, 
		 click: function (e) {
		 	 }
		 	   });
				 $("#ivas").append(checo2);	
	
}//if de i=1
else{
		var radi = $("<input>", { 
		id:'ivasumirr',
		name: 'ivasumir', 
		type:'radio',
		value:i,
		click: function (e) {
		 	}
		 	  });
				$("#ivas").append(radi);
	
//////////////////

	var va = $("<input>", { 
		id: 'tasas', 
		type:'checkbox',
		value:i,
		 click: function (e) {
		 	}
		 	  });
				$("#ivas").append(va);
				var checo2 = $("<label>", { 
		 id: i, 
		 click: function (e) {
		 	 }
		 	   });
				 $("#ivas").append(checo2);	
     }
}
		 	$('#0').text(' 16%');
		 	$('#1').text(' 11%');
		 	$('#2').text(' 0%');
		 	$('#3').text('Exenta');
		 	$('#4').text(' 15%');
		 	$('#5').text(' 10%');
		 	$('#6').text('Otra 1');
		 	$('#7').text('Otra 2');
		 	
		 	$('input:radio[value="0"]').prop('checked',true);
		 	$('input:checkbox[value="0"]').prop('checked',true);
		 	
		 	$('input:radio[value="1"]').prop('disabled',true);
		 	$('input:radio[value="2"]').prop('disabled',true);
		 	$('input:radio[value="3"]').prop('disabled',true);
		 	$('input:radio[value="4"]').prop('disabled',true);
		 	$('input:radio[value="5"]').prop('disabled',true);
		 	$('input:radio[value="6"]').prop('disabled',true);
		 	$('input:radio[value="7"]').prop('disabled',true);
		 	$('input:radio[value="8"]').prop('disabled',true);
	/////////////////////////AGREGAR OTRO IVA///////////////////////////////	 
		 	var otra1 = $("<input>", { 
		id: 'otra1', 
		type:'text',
		value:'0.00%',
		style: 'width:60px;',
		 click: function (e) {
		 	}
		 	  }); $("#6").append(otra1); 
	$('#otra1').hide();
	var otra1 = $("<input>", { 
		id: 'otra2', 
		type:'text',
		value:'0.00%',
		style: 'width:60px;',
		 click: function (e) {
		 	}
		 	  }); $("#7").append(otra1); 
	$('#otra2').hide();
	
var cadena="0,";	
var radio="0";
		 	  //////////////////////////
		 	   $(document).ready(function(){
		 	   	
		 $('input:checkbox').click(function(){	
		 	
		//alert( ($(this).val()));
		 	//for(var i=1;i<8;i++){  
		//$('input:checkbox[value="'+($(this).val())+'"]').click(function(){	
		$('input:radio[value="'+($(this).val())+'"]').prop('disabled',true);//abilita radio
////////////GUARDAR VALORES PARA EXPORTACION AL DESPUES/////////		
//if($('input:radio').is(':checked')){


		if(cadena!=''){
cadena=cadena.replace(($(this).val())+',','');
}


//$('#i1281').val(cadena);
////////////////////////////////////////////////////////////////////////
if($(this).val()==6){
				
	$('#otra1').hide();
				
}if($(this).val()==7 ){
	
		$('#otra2').hide();

}
		
		
$('input:checkbox[value="'+($(this).val())+'"]:checked').each(function(){//desabilita radio
	
	cadena=cadena+($(this).val())+',';
	//$('#i1281').val(cadena);
  	$('input:radio[value="'+($(this).val())+'"]').prop('disabled',false);
  //////////////////////OTRO IVA///////////////////////////////////////////////////////////
  	$('input:checkbox[value="6"]:checked').each(function(){//text otra1
  	$('#otra1').val('0.00%');	
	$('#otra1').show();
});

$('input:checkbox[value="7"]:checked').each(function(){//text otra1
		$('#otra2').val('0.00%');
	$('#otra2').show();
});
//////////////////////////////////////////////////////
});
if($('input:radio[value="'+($(this).val())+'"]').is(':checked')){
  	///	$('input:radio[value="'+($(this).val())+'"]').prop('checked',false);
  	$('input:radio[value="0"]').prop('checked',true);
  
  	}
  	
  
});//del click en check
});//document
		 	  
		function cambio(){
			var fiscal=$('#elije').val();
			if(fiscal=="Si"){
				$("input[onclick='btn_i1275_click();']").hide();
				$("input[onclick='btn_i1276_click();']").hide();
				$("input[onclick='btn_i1278_click();']").hide();
				
	 $("tr[title='asumir']").show();
	 $("tr[title='curp']").show();
	 $("tr[title='Tipo Tercero']").show();
	 $("tr[title='Tipo Operacion']").show();
	 $("tr[title='cuenta']").show();			
	 $("tr[title='Numero ID Fiscal']").show();
	 $("tr[title='Nombre del extranjero']").show();
	 $("tr[title='Nacionalidad']").show();
	 $("tr[title='IVA Retenido']").show();
	 $("tr[title='ISR Retenido']").show();
			}else{
				$("tr[title='curp']").hide();
				$("tr[title='asumir']").hide();
	 $("tr[title='Tipo Tercero']").hide();
	 $("tr[title='Tipo Operacion']").hide();
	 $("tr[title='cuenta']").hide();
	 $("tr[title='Numero ID Fiscal']").hide();
	 $("tr[title='Nombre del extranjero']").hide();
	 $("tr[title='Nacionalidad']").hide();
	 $("tr[title='IVA Retenido']").hide();
	 $("tr[title='ISR Retenido']").hide();
			}
		} 	  
		 
	
	var inseriva1=$("#otra1").val();
	var inseriva2=$("#otra2").val();
		
$('input:radio[name="ivasumir"]').click(function(){	
	alert($('input:radio[name="ivasumir"]:checked').val());
});
		 
		// alert(cadena);
		//nacionalidad
		 
		 	  
		//$('#elije').html('<option selected>si</option>');	  
///////////////////////////////esto ya estaba////////////////////////////////////////////////////

	$(function(){			

		if(!isNaN($('input[type="text"]:first').first().val()))
		{
			//$("#i618").after("<br><input type='button' onclick='opendialog(1);' value='Adjuntar archivo'>");
			//$("#i619").after("<br><input type='button' onclick='opendialog(2);'value='Adjuntar archivo'>");
			//$("#i620").after("<br><input type='button' onclick='opendialog(3);' value='Adjuntar archivo'><div id='dialog'></div>");
		}
		else
		{
			$("#i618").attr("disabled","disabled");
			$("#i619").attr("disabled","disabled");
			$("#i620").attr("disabled","disabled");
			
			$("#i618 option[value='0']").attr("selected",true);
			$("#i619 option[value='0']").attr("selected",true);
			$("#i620 option[value='0']").attr("selected",true);
		}
	$("#i618").hide();
	$("#lbl618").hide();
	$("#i619").hide();
	$("#lbl619").hide();
	$("#i620").hide();
	$("#lbl620").hide();
	});
	function opendialog(validacion)
	{
		switch(validacion)
		{
			case 1: var opcion='datos legales';break;
			case 2: var opcion='precio y calidad';break;
			case 3: var opcion='dispobilidad y precio';break;
		}
		
		$('#dialog').dialog({
			modal: true,
			minWidth: 450,
			draggable: true,
			resizable: false,
			title:"Adjuntar archivos de "+opcion,
			open: function()
			{	
				var idProveedor=$('input[type="text"]:first').first().val();
				$.ajax({
					type: 'POST',
					url:'../../../webapp/modulos/mrp/dialogautorizaciones.php',
					data:{proveedor:idProveedor,tipo:validacion},
					success: function(contenido)
					{	   
						$('#dialog').empty().append(contenido);
						$("#opcion").val(validacion);
		
						/*upload ajax*/
						var options = 
						{ 
	   						beforeSend: function() {   },
	    					uploadProgress: function(event, position, total, percentComplete) {},
	    					success: function(){},
							complete: function(response) 
							{
								$("#archivos").val(response.responseText);
								var idProveedor=$('input[type="text"]:first').first().val();
								$.ajax({
									url:'../../../webapp/modulos/mrp/guardautorizaciones.php',
									type: 'POST',
									data: {proveedor:idProveedor,archivos:$("#archivos").val(),opcion:$("#opcion").val()},
									success: function(resp)
									{
										alert("Has adjuntado los archivos con éxito");
										$('#dialog').dialog('close');
									}
								});
							},
							error: function()
							{
								alert("Ocurrio un error");
							}
						}; 
						$("#myForm").ajaxForm(options);
						/*end upload ajax*/
					}
				});
			},//open
			buttons:[{text:'Aceptar',click: function(){ $("#myForm").submit();		
			}},{text: 'Salir',click: function(){$('#dialog').dialog('close');}}]
		}).height('auto');			
	}
			
</script>

<script>
	document.getElementById("frm").onsubmit = function(){
		//event.preventDefault();
	    var cadena = $("#i384").val();
	    var alerta = "";
		if (cadena.length == 12)
			var valid = '^(([A-Z]|[a-z]){3})([0-9]{6})((([A-Z]|[a-z]|[0-9]){3}))';
		else
			var valid = '^(([A-Z]|[a-z]|\s){1})(([A-Z]|[a-z]){3})([0-9]{6})((([A-Z]|[a-z]|[0-9]){3}))';
		
		var validRfc=new RegExp(valid);
		var matchArray=cadena.match(validRfc);
		
		if ($("#i390").val() == "" || $("#i389").val() == "")
			alerta += "- Seleccione primero estado y municipio.\n";
		if ($("#i384").val() != "" && matchArray==null) 
			alerta += " El RFC no es valido.";
		
		if(alerta != "")
		{
			alert (alerta);
			return false;
		}
	
		return valida();
  	}
</script>
<!-- <div style='background-color:#6E6E6E;position:absolute; top:10px; left:10px; width:20px;'></div> -->