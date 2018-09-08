<!-- <script type="text/JavaScript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
<script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script type="text/javascript" src="http://malsup.github.com/jquery.form.js"></script>
<link href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" rel="stylesheet" type="text/css"/> -->
<!-- <script src="js/select2/select2.min.js"></script>
<link rel="stylesheet" type="text/css" href="js/select2/select2.css" /> -->
<script>
///////////////////////////////////////////////////////////////
			 var fiscalok=false;
 var ctrlPressed = false;
 var teclaCtrl = 17, teclaC = 71;
 var bancos=0;
var ejer=0;
$(document).keydown(function(e){
       
       if (e.keyCode == teclaCtrl) 
          ctrlPressed = true;
       
       if (ctrlPressed && (e.keyCode == teclaC)) 
			$('#send2').click();	
    });
    <?php if($_REQUEST['a'] != 0){?> 	$("#lbl1280,#i1280_div").hide();	$('#i1280').val(1);

   $('#i1278').html('<option selected>-Seleccione-</option>');
   $('#i384').val('XAXX010101000');
   $("#i1625").val(1);
   <?php }?>
 $(document).keyup(function(e)
    {
        if (e.keyCode == teclaCtrl) 
          ctrlPressed = false;
    });
$(document).ready(function(){
	$('#i2563,#lbl2563').hide();
	$('#i2563').val(1);
	
	$('#frm div:eq(20)').after('<div class="row"> <div class="col-md-4 nmfieldcell"> <label for="">Pais:</label> <br> <select id="idpais"><option value="1" selected="selected">MEXICO (ESTADOS UNIDOS MEXICANOS)</option><option value="47">COLOMBIA (REPUBLICA DE)</option><option value="54">COSTA RICA (REPUBLICA DE)</option><option value="85">GUATEMALA (REPUBLICA DE)</option></select> </div> </div>');
	$('#i1031').attr('onkeypress','return validar_num(event)')
	$('#i1281').attr('onkeypress','return validar_num(event)')
	$('#i1036').attr('onkeypress','return validar_let(event)')
	$('#i386').attr('onkeypress','return validar_let(event)')
	$('#i1275').attr('onchange','return tipo()')
	$("#i1625").attr('onchange','return tipoprv()')
	$("#i1690").attr('onchange','return cuentaCli()')//beneficiario pagador

	$("#idpais").select2();
	
	$('#idpais').change(function(){  
		$('#i389').html('');		
		var idpais = $('#idpais').val();
		$('#i2563').val(idpais);
		//var pa = $('#i2563').val();
		//alert(pa);
		$.ajax({
	            url: '../../modulos/appministra/ajax.php?c=reportes&f=estados',
	            type: 'post',
	            dataType: 'json',
	            data:{idpais:idpais}
	        })
	        .done(function(data) {
	            //$('#i389').append('<option value="0">Selecciona un estado</option>'); 
	            $.each(data, function(index, val) {
	                  $('#i389').append('<option value="'+val.idestado+'">'+val.estado+'</option>');  
	            });
	            $('#i389').select2();
	        })
	});
	
	 //para bancos
		 //termina bancos
		 
	// $("#i1278").select2({
         // width : "150px"
        // });
        // $("#i1280").select2({
         // width : "150px"
        // });
	var cuentacliente = $("#i1689").val();
	$.post("../../modulos/mrp/guardavariable.php",{opc:21},//select yena tipo operacion
	 function(respues) {
	 	$("#i1689").empty().append(respues);
	 	$("#i1689").val(cuentacliente);
	 	// $("#i1689").select2({
        	 // width : "550px"
        // });
	});	
	
	$.post("../../modulos/mrp/guardavariable.php",{opc:22},//select yena tipo operacion
	 function(re) {
	 	bancos=re;
	 	if(bancos==0){
	 		$("#lbl1689,#i1689_div").hide();
	 		$("span[aria-labelledby='select2-i1690-container']").show();$("#lbl1690,#i1690").hide();
	 	
	 	}
	 });
	$("#i2292_div").remove()
	$("input[onclick='btn_i2292_click();']").remove()
	$("#lbl2292").after().html("<label id='lbl2292' for='i2292'>moneda:</label><select id='i2292' class='form-control' name='i2292'><option value='1'>MXN Peso Mexicano</option><option value='2'>USD Dólar estadounidense</option></select>")

	valoresclasmod();
	 
});


function valoresclasmod()
{
	if(parseInt($("#i2292").val()) == 4)
		$("#i2292").val('1').trigger('change');

	if($("#i2293").val() == '')
		$("#i2293").val('0');
	

	$.post("../../modulos/appministra/ajax.php?c=configuracion&f=listaClasificacionesProv",//select llena tipo operacion
	 function(data) 
	 {
	 	$("#i2293").after("<select id='clasif' class='form-control' onchange='nuevoclasif()'></select>").attr('type','hidden');
	 	$("#clasif").html(data);

		$("#clasif").val(parseInt($("#i2293").val()));	 	
	 });
}

function nuevoclasif()
{
	$("#i2293").val($("#clasif").val());
}

function validar_num(e) { // 1
		tecla = (document.all) ? e.keyCode : e.which; // 2
		if (tecla==8) return true; // 3
		patron =/[A-Za-zñÑ\s]/; // 4
		te = String.fromCharCode(tecla); // 5
		return patron.test(te); // 6
	}

function validar_let(e) { // 1
		tecla = (document.all) ? e.keyCode : e.which; // 2
		if (tecla==8) return true; // 3
	patron = /\d/; // Solo acepta números 4
		te = String.fromCharCode(tecla); // 5
		return patron.test(te); // 6
	}
	/////////////////////////////////////////////////
	

	$(document).ready(function() {

	var label = $("<label>", { 
		id: "aki", 
		click: function (e) {
		 	}
	 	  });
	 	  $("#lbl1625").before(label);
	 	  $("#aki").after('<br>');
	 	  $('#aki').text('Datos fiscales');
	 	  $("#aki").append("<select id='elije' onchange='cambio()'><option selected>No</option><option>Si</option>");
		
$.post("../../modulos/mrp/guardavariable.php",{idtercero:$('#i1275').val(),opc:4},//select yena tipo operacion
	 function(respues) {
	 	$('#i1276').html(respues);
	});	
	
	$.post("../../modulos/mrp/guardavariable.php",{opc:17},//para saber el ejercicio
	function(respues) {
	 ejer=respues;
 	});	 	
//---------------------------------
//var cadenacheck="0,";

	$('#send').hide();
	var lwpButton = $("<input>", { 
		id: "send2", 
		type:"button",
		value:"Guardar",
		css: { "padding": "2px", "cursor": "pointer" }, 
		title: "guardar", 
		alt: "guardar", 
		click: function (e) {
	 	}
	});
	$("body").append(lwpButton);
});
		 	
		 	
////////////para divicion

 $("#lbl1275").before("<div id='divtipote' style='background-color:#DF013A; top:40px; left:10px;  height:5%; width:100%;text-align: center;color:#F8ECE0;'>Tipo de Tercero y Operación</div>");
//---------------------------------------------------------
 $("#i1281").after("<div id='divcontrol' style='background-color:#DF013A; top:40px; left:10px;  height:5%; width:100%;text-align: center;color:#F8ECE0 '>Control de IVA</div>");
//--------------------------------------------------------
 $("#i1276_div").after("<hr width='100%' id='h1' align:'left' style='color:red'>");	
//------------------------------------------------------
 $("#i1281").after("<hr width='100%' id='h2' align:'left' style='color:red'>");			 	  		
//-------------------------------------------
////////////para el select cuentas

<?php if($_REQUEST['a'] != 0){?>
	 	var cadenacheck="1,";
	 	$.post("../../modulos/mrp/guardavariable.php",{opc:2},
	 		function(respues) {
	 			$('#i1278').html('<option value=0 selected>-Seleccione-</option>');
	 			$('#i1278').append(respues);
				//$('#i1278').prepend(respues);
	
 			});
	 	//Interface de nuevo registro  	

////////////////////lo nuevo d tipo iva
 $('#i1336').prepend('<option selected>--------------------</option>');
 $('#i1400').prepend('<option selected>--------------------</option>');//ietu
	 
 $('#i1280').prepend('<option selected value=0>Ninguno</option>');
 
<?php }else{?>


// cuentas
$.post("../../modulos/mrp/guardavariable.php",{opc:2},
	function(respues) {
		$('#i1278').html("<option value=0 selected>-Seleccione-<option>");
		$('#i1278').append(respues);	
 	});	
///
//para sacar cuentas
$.post("../../modulos/mrp/guardavariable.php",{idproveedor:$('#i382').val(),opc:6},
	function(respues) {
		if(respues){
	 		var r=respues.split('//');
	 		$('#i1278 option[value='+r[0]+']').text(r[1]);
	 		$('#i1278 option[value='+r[0]+']').attr('selected','');
	 	}
	});
//tipo de iva
	$.post("../../modulos/mrp/guardavariable.php",{idproveedor:$('#i382').val(),opc:7},
	 function(respues) {
	 	if(respues!=0){
	 	var r=respues.split('//');
	 	$('#i1336 option[value='+r[0]+']').text(r[1]);
	 	$('#i1336 option[value='+r[0]+']').attr('selected','');
	 	}else{
	 		$('#i1336').prepend('<option selected>--------------------</option>');
	 	}
	 });
	 //TIPO IETU
	 $.post("../../modulos/mrp/guardavariable.php",{idproveedor:$('#i382').val(),opc:16},
	 function(respues) {
	 	if(respues==0){
	 		$('#i1400').prepend('<option selected>--------------------</option>');
	 	}
	 });
	
	 
	 
<?php } ?>
////////////////////
// tipo prv //
function tipoprv(){
	if($("#i1625").val()==3 || $("#i1625").val()==2){
		$('#i1275').val(7);
		tipo();
	}else{
		$('#i1275').val(1);
		tipo();
	}
	if($("#i1625").val()==2){
		$.post("../../modulos/mrp/guardavariable.php",{opc:20},
	 	function(respues) {
	 		$('#i1278').html("<option value=0 selected>-Seleccione-</option>");
	 		$('#i1278').append(respues);
		});
	}else{
		$.post("../../modulos/mrp/guardavariable.php",{opc:2},
	 		function(respues) {
	 			$('#i1278').html("<option value=0 selected>-Seleccione-</option>");
				$('#i1278').append(respues);
		 	});
	}
	if($("#i1625").val()==4 ){
		$("#i1689_div,#lbl1278").hide();
		$("#lbl1689,#i1278_div").hide();
	}else{
		$("#i1689_div,#lbl1278").show();
		$("#lbl1689,#i1278_div").show();
	}
}

///////////

var Evarios=false;
///--------------filtro de operacion tercero--------//
function tipo(){
	$('#i1276').after('<img id="ima" src="../../modulos/mrp/images/preloader.gif">');
	var idtercero=$('#i1275').val();
	if(idtercero==7){
		$('#i1280').val(1);
		$("#lbl1280,#i1280_div").hide();
	    //$('#i1280 option:not(:selected)').attr('disabled',true);//pais
 		$('#i1352').prop('disabled',true);//nombre extran
 		$('#i1279').prop('disabled',true);//idfiscal
 		$('#i1281').prop('disabled',true);//nacionalidad
 		$('#i1282').prop('disabled',true);//ivaretenido
 		$('#i1283').prop('disabled',true);//isrretenido
 		//krmnaki
 		$('#i1284').val('1234');
 		Evarios=true;
		$('#ivas').hide();
	} if(idtercero!=2 && idtercero!=7){
		//alert('entre1');
		$('#no').hide();
 		$('#i1279').prop('disabled',false);//idfiscal
 		$('#i1281').prop('disabled',false);//nacionalidad
 		$('#i1282').prop('disabled',false);//ivaretenido
 		$('#i1283').prop('disabled',false);//isrretenido
		$('#ivas').show();
		$('#i1352').prop('disabled',true);//nombre extran
		$('#i1280').val(1);
		$("#lbl1280,#i1280_div").hide();
	    //$('#i1280 option:not(:selected)').attr('disabled',true);//pais
	    Evarios=false;
	} if(idtercero==2){
		$('#no').hide();
		$("#lbl1280,#i1280_div").show();
		//$('#i1280 option:not(:selected)').attr('disabled',false);//pais
 		$('#i1352').prop('disabled',false);//nombre extran
 		$('#i1279').prop('disabled',false);//idfiscal
 		$('#i1281').prop('disabled',false);//nacionalidad
 		$('#i1282').prop('disabled',false);//ivaretenido
 		$('#i1283').attr('disabled',false);//isrretenido
		$('#ivas').show();
		 Evarios=false;
	}
	$.post("../../modulos/mrp/guardavariable.php",{idtercero:idtercero,opc:4},
		 function(respues) {
		 	$('#i1276').html(respues);
		 	$('#ima').hide();
	});
	
	//krmnaki
}
//-----------termina filtro-----------------//
	 //krmn
   		
   	
   	 <?php if($_REQUEST['a'] != 0){?>
   	 	var tasas = new Array() 
		 $.post("../../modulos/mrp/guardavariable.php",{opc:3},
		 function(respues) {
			res=respues.split('//');
			  for (x=1;x<res.length;x++){
			   tasas.push(res[x]);
			   //alert(puntos[x]);
			  }
				 	var ids;
				 	var idsigiente;
			 		for(var i=0;i<=tasas.length;i++){ 
			 		if(i==0){
			 			ids=tasas[i].split('->');
			 			//idsigiente=ids[1];
						$("#ivas").append("<label id='tasa'>Asumir* Tasa%</label>");
						$("#ivas").append('<br>');
						$("#ivas").append("<input type='radio' id='ivasumir' name='ivasumir' value="+ids[1]+" checked>");
						$("#ivas").append("<input id='tasas' type='checkbox' value='"+ids[1]+"' checked>");
						$("#ivas").append("<label id='"+i+"'>"+ids[0]+"</label>");	
				
					}//if de i=1
					if(i!=tasas.length && i!=0){
						ids=tasas[i].split('->');
						//idsigiente=ids[1];
						$("#ivas").append('<br>');
						$("#ivas").append("<input type='radio' id='ivasumir' name='ivasumir' value="+ids[1]+">");
						$("#ivas").append("<input id='tasas' type='checkbox' value='"+ids[1]+"'>");
						$("#ivas").append("<label id='"+i+"'>"+ids[0]+"</label>");
						$('input:radio[value="'+ids[1]+'"]').prop('disabled',true);
			     	}
			     	if(i==tasas.length){
			     	 o1=i;
			     	// idsigiente=parseInt(idsigiente)+1;
			     		$("#ivas").append('<br>');
			     	    $("#ivas").append("<input type='radio' id='ivasumir' name='ivasumir' value='1234'>");
						$("#ivas").append("<input id='tasas' type='checkbox' value='1234'>");
						$("#ivas").append("<label id='1234'>Otra 1</label>");
			     		$('input:radio[value="1234"]').prop('disabled',true);
			     		$("#ivas").append('<br>');
			     	 o2=i+1;
			     	// idsigiente2=parseInt(idsigiente)+1;
			     		$("#ivas").append("<input type='radio' id='ivasumir' name='ivasumir' value='12345'>");
						$("#ivas").append("<input id='tasas' type='checkbox' value='12345'>");
						$("#ivas").append("<label id='12345'>Otra 2</label>");
			     		$('input:radio[value="12345"]').prop('disabled',true);
			     		$("#1234").append("<input id='otra1' type='text'  style= 'width:60px;' placeholder='0.00%'>"); 
						$('#otra1').hide();
				   		$("#12345").append("<input id='otra2' type='text' placeholder='0.00%' style= 'width:60px;'>"); 
						$('#otra2').hide();
				  
			     	}
	}//for
//});
		 	
		 	$('#ivas').hide();
		 	//$('#ivas input:radio').prop('checked',true);
		 	$('input:radio[value="1"]').prop('checked',true);
		 	$('input:checkbox[value="1"]').prop('checked',true);
			(function(a){
	  			a.fn.validCampo=function(b){a(this).on({keypress:function(a){var c=a.which,d=a.keyCode,e=String.fromCharCode(c).toLowerCase(),f=b;(-1!=f.indexOf(e)||9==d||37!=c&&37==d||39==d&&39!=c||8==d||46==d&&46!=c)&&161!=c||a.preventDefault()}})}})(jQuery);
	  			$(function(){   
	     		 $('#otra1').validCampo('0123456789.'); 
	     		 $('#otra2').validCampo('0123456789.');    
	   		});

		 	/////////////////////////AGREGAR OTRO IVA///////////////////////////////
		 
   $(document).ready(function(){  $("span[aria-labelledby='select2-i1690-container']").hide();
    	$('input:checkbox').click(function(){	
 			$('input:radio[value="'+($(this).val())+'"]').prop('disabled',true);//abilita radio
////////////GUARDAR VALORES PARA EXPORTACION AL DESPUES/////////		
			if(cadenacheck!=''){
				cadenacheck=cadenacheck.replace(($(this).val())+',','');
			}

////////////////////////////////////////////////////////////////////////
			if($(this).val()==1234){
				$('#otra1').val('0.00');		
				$('#otra1').hide();
							
			}if($(this).val()==12345 ){
				$('#otra2').val('0.00');
				$('#otra2').hide();
			
			}

			$('input:checkbox[value="'+($(this).val())+'"]:checked').each(function(){//desabilita radio
				cadenacheck=cadenacheck+($(this).val())+',';
				$('input:radio[value="'+($(this).val())+'"]').prop('disabled',false);
	  //////////////////////OTRO IVA///////////////////////////////////////////////////////////
	        	if(($(this).val())==1234){//text otra1
	  				$('#otra1').show();
				}
				if(($(this).val())==12345){
					$('#otra2').show();
				}
//////////////////////////////////////////////////////
			});
			if($('input:radio[value="'+($(this).val())+'"]').is(':checked')){
	  			$('input:radio[value="1"]').prop('checked',true);
	  
		  		if($(this).val()==1){
					if($('input:radio[value="1"]').is(':checked')){
						$('input:radio[value="1"]').prop('checked',false);
					}
				}
		
	  		}
  	
  
		});//del click en check
	});//document
});	
<?php }else{?>//para cuando es edicion en las tasas
	var tasas2 = new Array();
	var tasas3 = new Array();
	var tasa1=false;
	var tasa2=false;
	var cadenacheck=''; 
	$.post("../../modulos/mrp/guardavariable.php",{idproveedor:$('#i382').val(),opc:9},
		 function(respues) {
		 	//if(respues!=0){
			res=respues.split('//');
			 for (x=1;x<res.length;x++){
			   tasas2.push(res[x]);
			   
			   //alert(puntos[x]);
			  }
			 							//$("#ivas").append("<label id='tasa'>Asumir* Tasa%</label>");

					var ids;
			 		for(var i=0;i<=tasas2.length;i++){ 
			 	 
			 		if(i==0){
			 			ids=tasas2[i].split('->');
			 				$("#ivas").append("<label id='tasa'>Asumir* Tasa%</label>");

			 			var edicion=ids[0];
							
			 			if(ids[0]=="Otra Tasa 1"){
			 				tasa1=true;
			 				id(ids[1],"1234",ids[0],ids[2]+'->o1');
							
			 			}else if(ids[0]=="Otra Tasa 2"){
			 				
			 				tasa2=true;
							 id(ids[1],"12345",ids[0],ids[2]+'->o2');
						}else if(ids[0]=="No Calcula") {
							//$("#ivas").hide();
							$("#ivas").append("<br><label id='no' style='color:#B40404'>No Calcula</label>");
						
						}else 
							if(ids[0]!="Otra Tasa 1" && ids[0]!="Otra Tasa 2" && ids[0]!="No Calcula"){
								id(ids[1],i,ids[0],''); 
								

						}
					
					}//if de i=1
					if(i!=tasas2.length && i!=0){
						ids=tasas2[i].split('->');
						//idsigiente=ids[1];
						if(ids[0]=="Otra Tasa 1"){
							tasa1=true;
							id(ids[1],"1234",ids[0],ids[2]+'->o1');
							
			 			}else if(ids[0]=="Otra Tasa 2"){
			 				tasa2=true;
							id(ids[1],"12345",ids[0],ids[2]+'->o2');
			 				
			 			}else if(ids[0]=="No Calcula"){
			 				$("#ivas").text('<label id="no">No Calcula</label>');
			 			}else
			 				
			 				if(ids[0]!="Otra Tasa 1" && ids[0]!="Otra Tasa 2" && ids[0]!="No Calcula"){
			 					//alert(ids[1]);
			 					id(ids[1],i,ids[0],'');
		                     
								
						}
			     	}
			    }
	///////////////-----------TASAS QUE NO ESTAN EN LA RELACION CON CONT_TASAPRV----------------
	
	
	$.post("../../modulos/mrp/guardavariable.php",{idproveedor:$('#i382').val(),opc:5},
					 function(respues) {
					 	
					 var res2=respues.split('//');
						 for (x=1;x<res2.length;x++){
						 		tasas3.push(res2[x]);
			  				}
			  				var ids;
				 	
			 		for(var i=0;i<tasas3.length;i++){ 
			 			
						//if(i!=tasas3.length ){
							ids=tasas3[i].split('->');
							//idsigiente=ids[1];
							// alert(ids[0]);
							
								$("#ivas").append('<br>');
								$("#ivas").append("<input type='radio' id='ivasumir' name='ivasumir' value="+ids[1]+">");
								$("#ivas").append("<input id='tasas' type='checkbox' value='"+ids[1]+"'>");
								$("#ivas").append("<label id='"+i+"'>"+ids[0]+"</label>");
								$('input:radio[value="'+ids[1]+'"]').prop('disabled',true);
								//id(ids[1],"1234",ids[0]);

				     	}
				    
				   	if(tasa1==false){
				   		//id(ids[1],"1234",ids[0]);
						 $("#ivas").append('<br>');
			     	     $("#ivas").append("<input type='radio' id='ivasumir' name='ivasumir' value='1234'>");
						 $("#ivas").append("<input id='tasas' type='checkbox' value='1234'>");
						 $("#ivas").append("<label id='1234'>Otra Tasa 1</label>");
			     		 $('input:radio[value="1234"]').prop('disabled',true);
			     		$("#1234").append("<input id='otra1' type='text'  style= 'width:60px;' placeholder='0.00%'>"); 
						$('#otra1').hide();			
					}
					if(tasa2==false){
						//id(ids[1],"12345",ids[0]);
						 $("#ivas").append('<br>');
			     	     $("#ivas").append("<input type='radio' id='ivasumir' name='ivasumir' value='12345'>");
						 $("#ivas").append("<input id='tasas' type='checkbox' value='12345'>");
						 $("#ivas").append("<label id='12345'>Otra Tasa 2</label>");
			     		 $('input:radio[value="12345"]').prop('disabled',true);	
			     		$("#12345").append("<input id='otra2' type='text' placeholder='0.00%' style= 'width:60px;'>"); 
						$('#otra2').hide();		
					}
					
//////////--------------------------//////////////

		 	(function(a){
	  			a.fn.validCampo=function(b){a(this).on({keypress:function(a){var c=a.which,d=a.keyCode,e=String.fromCharCode(c).toLowerCase(),f=b;(-1!=f.indexOf(e)||9==d||37!=c&&37==d||39==d&&39!=c||8==d||46==d&&46!=c)&&161!=c||a.preventDefault()}})}})(jQuery);
	  			$(function(){   
	     		 $('#otra1').validCampo('0123456789.'); 
	     		 $('#otra2').validCampo('0123456789.');    
	   		});

		 	/////////////////////////AGREGAR OTRO IVA///////////////////////////////
		 
		   $(document).ready(function(){
		    	$('input:checkbox').click(function(){	
		 			$('input:radio[value="'+($(this).val())+'"]').prop('disabled',true);//abilita radio
////////////GUARDAR VALORES PARA EXPORTACION AL DESPUES/////////		
					if(cadenacheck!=''){
						cadenacheck=cadenacheck.replace(($(this).val())+',','');
					}

////////////////////////////////////////////////////////////////////////
					if($(this).val()==1234){
						$('#otra1').val('0.00');		
						$('#otra1').hide();
									
					}if($(this).val()==12345 ){
						$('#otra2').val('0.00');
						$('#otra2').hide();
					
					}
		
					$('input:checkbox[value="'+($(this).val())+'"]:checked').each(function(){//desabilita radio
						cadenacheck=cadenacheck+($(this).val())+',';
						$('input:radio[value="'+($(this).val())+'"]').prop('disabled',false);
			  //////////////////////OTRO IVA///////////////////////////////////////////////////////////
			        	if(($(this).val())==1234){//text otra1
			  				$('#otra1').show();
						}
						if(($(this).val())==12345){
							$('#otra2').show();
						}
//////////////////////////////////////////////////////
					});
					if($('input:radio[value="'+($(this).val())+'"]').is(':checked')){
			  			$('input:radio[value="1"]').prop('checked',true);
			  
				  		if($(this).val()==1){
							if($('input:radio[value="1"]').is(':checked')){
								$('input:radio[value="1"]').prop('checked',false);
							}
						}
				
			  		}
  	
  if(edicion==0){//pato
							//$("input:checkbox[value='1']").prop('checked',true);
							$("input:radio[value='1']").prop('checked',true);
			 			}
		});//del click en check
	});//cierre de confg de select radio etc
///////////-------------------------/////////////					
		$.post("../../modulos/mrp/guardavariable.php",{idproveedor:$('#i382').val(),opc:10},
			 function(respues) {
				$('input:radio[value="'+respues+'"]').prop('checked',true); 
		});	
			   		
	});
	
	$.post("../../modulos/mrp/guardavariable.php",{idproveedor:$('#i382').val(),opc:8},
	 function(respues) {
	 	var r=respues.split('//');
	 	var r2=r[0].split('->');
	 	//alert(r2[0]+'ter'+r2[1]);
	 	//tipo tercero
	 	$('#i1275 option[value='+r2[0]+']').text(r2[1]);
	 	$('#i1275 option[value='+r2[0]+']').attr('selected','');
	 	if(r2[0]==7){
		 	$('#ivas').hide();
		 	$("#lbl1280,#i1280").hide();
		    //$('#i1280 option:not(:selected)').attr('disabled',true);//pais
	 		$('#i1352').prop('disabled',true);//nombre extran
	 		$('#i1279').prop('disabled',true);//idfiscal
	 		$('#i1281').prop('disabled',true);//nacionalidad
	 		$('#i1282').prop('disabled',true);//ivaretenido
	 		$('#i1283').prop('disabled',true);//isrretenido
		Evarios=true;
		}else if(r2[0]!=2 && r2[0]!=7 ){
			//alert('entre3');
	 		$('#i1279').prop('disabled',false);//idfiscal
	 		$('#i1281').prop('disabled',false);//nacionalidad
	 		$('#i1282').prop('disabled',false);//ivaretenido
	 		$('#i1283').prop('disabled',false);//isrretenido
			$('#ivas').show();
			$('#i1352').prop('disabled',true);//nombre extran
			//$('#i1280 option:not(:selected)').attr('disabled',true);//pais
			$("#lbl1280,#i1280").hide();
		}else if(r2[0]==2){
			//alert('entre4');
			$("#lbl1280,#i1280").show();
			//$('#i1280 option:selected').attr('disabled',false);//pais
	 		$('#i1352').prop('disabled',false);//nombre extran
	 		$('#i1279').prop('disabled',false);//idfiscal
	 		$('#i1281').prop('disabled',false);//nacionalidad
	 		$('#i1282').prop('disabled',false);//ivaretenido
	 		$('#i1283').prop('disabled',false);//isrretenido
			$('#ivas').show();
		}
			 	$('#ivas').hide();

	 	//tipo operacion
	 var  r3=r[1].split('<-');
	 	$('#i1276 option[value='+r3[0]+']').text(r3[1]);
	 	$('#i1276 option[value='+r3[0]+']').attr('selected','');
	 //	alert(r3[0]+'ope'+r3[1]);
	 });

/////////////////////////////////////////////////////////
	//fiscal true
	$.post("../../modulos/mrp/guardavariable.php",{opc:15,id:$('#i382').val()},
	function(respues) {
		if(respues!=0){
			fiscalok=true;	
			$('#elije').val('Si');
			cambio()
			cuentaCli();///ejecuta para identificar la visualizacion de la cuenta de clientes

			//$('#i1284').val(respues);
		
		}
 	});	
});

<?php } ?>	
		 	
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
	// $('#i1284').show();//asumir
	var o2=0;
	 	var o1=0;
	 // fiscalok=false;
     $('#divtipote').hide();
     $('#h1').hide();
     $('#h2').hide();
   	 $("#lbl1625,#i1625_div").hide();
     $('#divcontrol').hide();
	 $('#i1284').val('0');
	 $("#lbl1284,#i1284").hide();//asumir
	 //$("#lbl1277,#i1277").hide();
	 $("#lbl1275,#i1275_div").hide();
	 $("#lbl1276,#i1276_div").hide();
	 $("#lbl1278,#i1278_div").hide();
	 $("#lbl1279,#i1279").hide();
	 $("#lbl1352,#i1352").hide();
	 $("#lbl1281,#i1281").hide();
	 $("#lbl1282,#i1282").hide();
	 $("#lbl1283,#i1283").hide();
	 $("#lbl1336,#i1336_div").hide();
	 $("#lbl1400,#i1400_div").hide();
	 $("#lbl1352,#i1352").hide();
     $("#lbl1280,#i1280_div").hide();
     $("#lbl1689,#i1689_div").hide();
	 $("span[aria-labelledby='select2-i1690-container']").hide();$("#lbl1690,#i1690").hide(); 
	 //$("#lbl1284,#i1284").append('<br>');//asumir
	
	//tr[title='asumir']
	 $("form").append("<div id='ivas' style='background-color:#6E6E6E; top:30px; left:10px; width:10%;text-align:left'></div>");
function cambio(){
			var fiscal=$('#elije').val();

			if(fiscal=="Si" ){
				if(ejer<2014){
					 $("#lbl1400,#i1400_div").show();
				}				
				fiscalok=true;
				$('#divtipote').show();
				$('#i1284').val('1234');
				// $("input[onclick='btn_i1275_click();']").hide();
				// $("input[onclick='btn_i1276_click();']").hide();
				// $("input[onclick='btn_i1278_click();']").hide();
				// $("input[onclick='btn_i1336_click();']").hide();//tipo iva button
				// $("input[onclick='btn_i1400_click();']").hide();//ietu
				// $("input[onclick='btn_i1689_click();']").hide();
				<?php if($_REQUEST['a'] == 0){?>
				if(Evarios==false){
					
					 $('#ivas').show();	
				 }else{
				 	
					$('#ivas').hide();	
				}
			 	<?php } else{ ?>
			 		
			 		$('#ivas').show();	
			 	<?php }?>
	// $("tr[title='asumir']").show();
				// $("#lbl1277,#i1277").show();
				 $("#lbl1275,#i1275_div").show();
				 $("#lbl1276,#i1276_div").show();
				 $("#lbl1278,#i1278_div").show();			
				 $("#lbl1279,#i1279").show();
				 $("#lbl1352,#i1352").show();
				 $("#lbl1281,#i1281").show();
				 $("#lbl1282,#i1282").show();
				 $("#lbl1283,#i1283").show();
				 $("#lbl1336,#i1336_div").show();
				 $("#lbl1625,#i1625_div").show();
				 $("#lbl1352,#i1352").show();
			     $("#lbl1280,#i1280_div").show();
			      if(bancos==1){
				 	$("#lbl1689,#i1689_div").show();
				 	$("span[aria-labelledby='select2-i1690-container']").show();$("#lbl1690,#i1690,#select2-i1690-container").show();
				 }
			    
				 $('#divcontrol').show();
				 $('#h1').show();
				 $('#h2').show();
			}else{	
				
				fiscalok=false;	 
				if(ejer<2014){
					 $("#lbl1400,#i1400_div").hide();//Tipo IETU
				}
				 $('#i1284').val('0');
				 $('#ivas').hide();
				// $("#lbl1277,#i1277").hide();//curp
				//$("tr[title='asumir']").hide();
				 $("#lbl1275,#i1275_div").hide();//tipo tercero
				 $("#lbl1276,#i1276_div").hide();//Tipo Operacion
				 $("#lbl1278,#i1278_div").hide();//cuenta
				 $("#lbl1279,#i1279").hide();//Numero ID Fiscal
				 $("#lbl1352,#i1352").hide();//Nombre del extranjero
				 $("#lbl1281,#i1281").hide();//Nacionalidad
				 $("#lbl1282,#i1282").hide();//IVA Retenido
				 $("#lbl1283,#i1283").hide();//ISR Retenido
				 $("#lbl1336,#i1336_div").hide();//Tipo IVA
				 $('#divtipote').hide();
				 $('#h1').hide();
				 $('#divcontrol').hide();
			     $('#h2').hide();
			     $("#lbl1280,#i1280_div").hide();//Pais de Residencia
				 $("#lbl1625,#i1625_div").hide();//Tipo
				 $("#lbl1689,#i1689_div").hide();//Cuenta Cliente
			     $("span[aria-labelledby='select2-i1690-container']").hide();$("#lbl1690,#i1690").hide();//Beneficiario/Pagador
			}
		} 	  
		 
	

	
		
$('input:radio[name="ivasumir"]').click(function(){	
	var radio=($('input:radio[name="ivasumir"]:checked').val());
});

 $(document).ready(function() {
		 $('#send2').click(function() {
var cadena = $("#i383").val().replace(/\s*[\r\n][\r\n \t]*/g, "");
$("#i383").val(cadena);
		 var cadena = $("#i384").val();
	     var alerta = "";
		 if (cadena.length == 12)
			var valid = '^(([A-Z]|[a-z]){3})([0-9]{6})((([A-Z]|[a-z]|[0-9]){3}))';
		 else if(cadena.length == 13)
			var valid = '^(([A-Z]|[a-z]|\s){1})(([A-Z]|[a-z]){3})([0-9]{6})((([A-Z]|[a-z]|[0-9]){3}))';
		 else if(cadena.length <12){
		 	alerta += "El RFC no es valido.";
		 }
		// else if (cadena.length == 10)
			// var valid = '^(([A-Z]|[a-z]){3})([0-9]{6})';
// 			
		
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
		}else{
			
////////////////////////////////////////////////////////////
var inseriva1=$("#otra1").val();
var inseriva2=$("#otra2").val();

var radio=($('input:radio[name="ivasumir"]:checked').val());
////aki
 	<?php if($_REQUEST['a'] != 0){?>
				if(fiscalok==true){
					if(radio!=null){
 						if($('#i383').val()!=""){
 						$.post("../../modulos/mrp/guardavariable.php",{opc:14,nombre:$('#i383').val(),id:0},
 							function(respues) {
 								if(respues==1){
 									alert('El nombre ya esta registrado');
 								}else if($('#i384').val()!=""){
 									$.post("../../modulos/mrp/guardavariable.php",{opc:13,rfc:$('#i384').val(),id:0},
 										function(respues) {
 											if($('#i384').val()=="XAXX010101000"){
 												respues=0
 											}
 											if(respues==1){
 								 				alert('El rfc ya se encuentra registrado');

 											}else{////todo bien no hya repe
							 					if(Evarios==false){
								 					$.post("../../modulos/mrp/guardavariable.php",{opc:1,inseriva1:inseriva1,inseriva2:inseriva2,radio:radio,cadenacheck:cadenacheck},
														function(respues) {
															var razon=$("#i383").val().replace("\n"," ");
															$("#i383").val(razon);
														 	$('#send').click();	
														 	//mandabancos();
														});
											 		
									 			}else{
											    	$.post("../../modulos/mrp/guardavariable.php",{opc:1,inseriva1:inseriva1,inseriva2:inseriva2,radio:123456,cadenacheck:'123456,'},
												 		function(respues) {
															var razon=$("#i383").val().replace("\n"," ");
															$("#i383").val(razon);
															$('#send').click();	
															//mandabancos();
													    });
									   			}
										   }//else de no ay repe
 										});	
 								}else{
 									if(Evarios==false){
					 					$.post("../../modulos/mrp/guardavariable.php",{opc:1,inseriva1:inseriva1,inseriva2:inseriva2,radio:radio,cadenacheck:cadenacheck},
											function(respues) {
												var razon=$("#i383").val().replace("\n"," ");
												$("#i383").val(razon);
											 	$('#send').click();
											 	//mandabancos();	
											});
								 		
						 			}else{
								    	$.post("../../modulos/mrp/guardavariable.php",{opc:1,inseriva1:inseriva1,inseriva2:inseriva2,radio:123456,cadenacheck:'123456,'},
									 		function(respues) {
									 			var razon=$("#i383").val().replace("\n"," ");
												$("#i383").val(razon);
												$('#send').click();	
												//mandabancos();	
										    });
						   			}
 								}
 							});
 						}else{
 							alert('Introduzca la Razon Social');
 						}
 					}else{
 						alert('Indica la tasa a Asumir');
 					}
 				}else{//si no hay fiscal
 					$('#i1275').html('<option  selected></option>');
 					$('#i1276').html('<option  selected></option>');
 					if($('#i383').val()!=""){
 						$.post("../../modulos/mrp/guardavariable.php",{opc:14,nombre:$('#i383').val(),id:0},
 							function(respues) {
 								if(respues==1){
 									alert('El nombre ya esta registrado');
 								}else if($('#i384').val()!=""){
 									$.post("../../modulos/mrp/guardavariable.php",{opc:13,rfc:$('#i384').val(),id:0},
 										function(respues) {
 											if($('#i384').val()=="XAXX010101000"){
 												respues=0
 											}
 											if(respues==1){
 								 				alert('El rfc ya se encuentra registrado');

 											}else{
 												//alert($('#i1275').val())
 												var razon=$("#i383").val().replace("\n"," ");
												$("#i383").val(razon);
 												$('#send').click();	
 											}
 										});
	 								}else{
	 									var razon=$("#i383").val().replace("\n"," ");
										$("#i383").val(razon);
										$('#send').click();	
							
							   		}
 								});
 							}else{
 								alert('Introduzca la Razon Social');
 							}
 				}
 				
 	<?php }else{ ?>//krmn
 			 	if(fiscalok==true){
 			 		if(radio!=null || $('#i1275').val()==7){

 					if($('#i383').val()!=""){
 						$.post("../../modulos/mrp/guardavariable.php",{opc:14,nombre:$('#i383').val(),id:$('#i382').val()},
 							function(respues) {
 								if(respues==1){//si no coincide el nombre con el idproveedor
 									alert('El nombre ya esta registrado');
 								}else{
						 			if($('#i384').val()!=""){
	 									$.post("../../modulos/mrp/guardavariable.php",{opc:13,rfc:$('#i384').val(),id:$('#i382').val()},
	 										function(respues) {
	 											if($('#i384').val()=="XAXX010101000"){
 													respues=0
 												}
	 											if(respues==1){//sino coincide rfc y id
	 								 				alert('El rfc ya se encuentra registrado');
													
	 											}else{////todo bien no hya repe
	 												
								 					if(Evarios==true){
															$.post("../../modulos/mrp/guardavariable.php",{opc:1,inseriva1:inseriva1,inseriva2:inseriva2,radio:123456,cadenacheck:'123456,',idproveedor:$('#i382').val()},
														 		  function(respues) {
														 		  	var razon=$("#i383").val().replace("\n"," ");
																	$("#i383").val(razon);
																	 $('#send').click();
																	 //mandabancos();	
															});
														}else{
															 $.post("../../modulos/mrp/guardavariable.php",{opc:1,inseriva1:inseriva1,inseriva2:inseriva2,radio:radio,cadenacheck:cadenacheck,idproveedor:$('#i382').val()},
														 		  function(respues) {
														 		  	var razon=$("#i383").val().replace("\n"," ");
																	$("#i383").val(razon);
																	 $('#send').click();
																	 //mandabancos();	
															});
														}
											   }//else de no ay repe
								  
											});	
									}
									else{////
				 						if(Evarios==true){
											$.post("../../modulos/mrp/guardavariable.php",{opc:1,inseriva1:inseriva1,inseriva2:inseriva2,radio:123456,cadenacheck:'123456,',idproveedor:$('#i382').val()},
										 		  function(respues) {
										 		  	var razon=$("#i383").val().replace("\n"," ");
													$("#i383").val(razon);
													 $('#send').click();
													 //mandabancos();	
											});
										}else{
											 $.post("../../modulos/mrp/guardavariable.php",{opc:1,inseriva1:inseriva1,inseriva2:inseriva2,radio:radio,cadenacheck:cadenacheck,idproveedor:$('#i382').val()},
										 		  function(respues) {
										 		  	var razon=$("#i383").val().replace("\n"," ");
													$("#i383").val(razon);
													 $('#send').click();	
					//								 mandabancos();
											});
										}
					  				 }
			
								}//else primero
						
				
							});
						}else{
							alert('Introduzca la Razon Social');
						}
			
				//respues 0
				}else{
 						alert('Indica la tasa a Asumir');
 					}
		}else{//si no hay fiscal
			$('#i1275').html('<option  selected></option>');
 			$('#i1276').html('<option  selected></option>');
			if($('#i383').val()!=""){
				$.post("../../modulos/mrp/guardavariable.php",{opc:14,nombre:$('#i383').val(),id:$('#i382').val()},
					function(respues) {
						if(respues==1){
							alert('El nombre ya esta registrado');
						}else{
								//////////
							if($('#i384').val()!=""){
								$.post("../../modulos/mrp/guardavariable.php",{opc:13,rfc:$('#i384').val(),id:$('#i382').val()},
									function(respues) {
										if($('#i384').val()=="XAXX010101000"){
 											respues=0
 										}
										if(respues==1){
							 				alert('El rfc ya se encuentra registrado');

										}else{
										var razon=$("#i383").val().replace("\n"," ");
										$("#i383").val(razon);
											$('#send').click();	
										}
									});
							}else{/////////////////
								var razon=$("#i383").val().replace("\n"," ");
								$("#i383").val(razon);
								$('#send').click();	
							}
											//////////
					  }
				    });
			}else{
				alert('Introduzca la Razon Social');
			}
					
		}//else
			///////////////////		   
			

 	<?php } ?>
 	
  }//el else q puse
		 });
});  
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
			//$("#i620").attr("disabled","disabled");
			
			$("#i618 option[value='0']").attr("selected",true);
			$("#i619 option[value='0']").attr("selected",true);
			//$("#i620 option[value='0']").attr("selected",true);
		}
	$("#i618").hide();
	$("#lbl618").hide();
	$("#i619").hide();
	$("#lbl619").hide();
	// $("#i620").hide();
	//$("#lbl620").hide();
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
function id(id,label,nombre,val){
	cadenacheck='';
	if(id!=0){
	$.post("../../modulos/mrp/guardavariable.php",{id:id,opc:11},
		function(respues) {
			if(cadenacheck==''){
				cadenacheck=respues+',';
			}else{
				cadenacheck=cadenacheck+respues+',';
			}
		$("#ivas").append('<br>');
		$("#ivas").append("<input type='radio' id='ivasumir' name='ivasumir' value="+respues+">");
		$("#ivas").append("<input id='tasas' type='checkbox' value='"+respues+"'>");
		$("#ivas").append("<label id='"+label+"'>"+nombre+"</label>");
		$("input:checkbox[value='"+respues+"']").prop('checked',true);
		 var v=val.split('->');
		 if(v[1]=='o1'){
		 	$("#1234").append("<input id='otra1' type='text'  style= 'width:60px;' placeholder='0.00%' value='"+v[0]+"'>"); 
	
		 }else if (v[1]=='o2'){
		 	 $("#12345").append("<input id='otra2' type='text'  style= 'width:60px;' placeholder='0.00%' value='"+v[0]+"'>"); 

		 }
		
	});
  }
}

<?php
 $accelog_access->add_url("/webapp/netwarelog/catalog/gestor.php?idestructura=275&ticket=testing");
 ?>		
function mandabancos(){
	window.parent.parent.agregatab("../../netwarelog/catalog/gestor.php?idestructura=275&ticket=testing","Bancos de Proveedor","",1706);
}	
//$("#i1278_div").after("<br></br><input type='button' onclick='mandabancos()' value='Agregar Bancos al Proveedor'/>");
// ("#i1278").after("<br></br><input type='button' onclick='mandabancos()' value='Agregar Bancos al Proveedor'/>");
function cuentaCli(){
	if(bancos==1){
		if($("#i1690").val()==-1){
			$("#lbl1689,#i1689_div").show();
		}else{
			$("#lbl1689,#i1689_div").hide();
			$('#i1689').find('option').removeAttr("selected");
			$('#i1689 > option[value="0"]').attr('selected', true);
			//$("#i1689").val(0);// prv
		}
		if($("#i1625").val()==4 ){
			$("#lbl1689,#i1689_div").hide();
		}
		
	}
}
</script>


