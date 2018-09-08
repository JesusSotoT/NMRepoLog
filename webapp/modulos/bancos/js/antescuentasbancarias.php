<script src="js/select2/select2.min.js"></script>
<link rel="stylesheet" type="text/css" href="js/select2/select2.css" />
<script>
function cuentaMoneda(){
	var moneda = $('#i1508').val();
	var cuenta = $('#i1510').val();
	$.post("../../modulos/bancos/models/antes.php",{opc:11,idmoneda:moneda},
		function (resp){
			$('#i1510').html(resp);//cuenta contable solo de la moneda
			$('#i1510').val(cuenta);
		});
}
function validaNumero(num){
	
	if($("#i1661").val()==-1){
		if(!$("#i1536").val()){
			alert("Debe seleccionar un Numero final.");
		}
		if(num > parseInt($("#i1536").val())){
			alert("El numero actual debe estar en el rango.")
			$("#i1538").val( $("#i1538").val()-num );
		}
	}
	
}
$(document).ready(function(){
/* para desabilitar la opcion de cambio si tienen numeracion automatica */
	if($("#i1537").val()==-1){
		$("#i1538").attr('readonly',true);
	}else{
		$("#i1538").attr('readonly',false);
	}
	if($("#i1661").val()==-1){
		$("#i1535").attr("readonly",false);
		$("#i1536").attr("readonly",false);
	}else{
		$("#i1535").attr("readonly",true);
		$("#i1536").attr("readonly",true);
	}
/* fin cambio */
	$("#i1506").select2({//banco
     width : "150px"
    });
  var cheques=0;
	$("#send").hide();
	$("#send").after('<input id="send2" class=" nminputbutton " type="button" value="Guardar" >');
	
	$('#i1505').attr('maxlength',16);
	$('#i1508').attr('onchange','return cuentaMoneda(this.value)')
	$('#i1538').attr('onkeyup','return validaNumero(this.value)')

	 $('#i1661').change(function(){
		if($("#i1661").val()==-1){
			$("#i1535").val('').attr("readonly",false);
			$("#i1536").val('').attr("readonly",false);
			//$("#i1538").attr("disabled",true);
			//$("#i1537").val(0);
		}else{
			//$("#i1537").val(-1);
			$("#i1535").val('').attr("readonly",true);
			$("#i1536").val('').attr("readonly",true);
			//$("#i1538").attr("disabled",false);
		}
	});
	//$('#i1534').attr('onchange',deshabilita());
 <?php if($_REQUEST['a'] != 0){?>
	$("#i1537").val(0);
   // $("#i1538").attr("disabled",true);
	$('#i1510').empty();$('#i1508').empty();
	$.post("../../modulos/bancos/models/antes.php",{opc:3,idcuenta:0},
		function (resp){
			$('#i1510').append(resp);
		});
		
	$.post("../../modulos/bancos/models/antes.php",{opc:4,coin_id:0},
		function (resp){
			$('#i1508').append(resp);
		});	
	$("#i1510").select2({//cuenta contable
    	 width : "150px"
    });
    // $("#i1508").select2({//moneda
    	 // width : "150px"
    // });	
<?php }else{ ?>
	$('#i1537').change(function(){
		//por si se limita al admin 
<?php
$idperfil = preg_replace('/\(|\)/','',$_SESSION['accelog_idperfil']);

	if($idperfil == 2){?>
		
		if(confirm("Esta seguro de cambiar la opcion?\nesto podria general inconsistencias en los folios de cheques ")){
			
				if($("#i1537").val()==-1){
					$("#i1538").attr('readonly',true);
				}else{
					$("#i1538").attr('readonly',false);
				}
			
		}else{
			if($('#i1537').val()==-1){ 
				$('#i1537').val(0).select2({width : "150px"});
			}else{ 
				$('#i1537').val(-1).select2({width : "150px"});
			}
		}	
		
<?php }else{ ?>
		alert("Solo el administrador puede cambiar esta opcion");
		if($('#i1537').val()==-1){
			 $('#i1537').val(0).select2({width : "150px"});
		}else{ 
			$('#i1537').val(-1).select2({width : "150px"});
		}
<?php } ?>
});
	
		 cuentaMoneda();
	
		// $.post("../../modulos/bancos/models/antes.php",{opc:6,idbancaria:$('#i1504').val(),numeroactual:$('#i1538').val()},
		// function (resp){
			// if(resp==1){
				// cheques=1
				// //$("#i1535").attr("readonly",true);
				// //$("#i1536").attr("readonly",true);
				// $("#i1538").val('').attr("readonly",true);
				// //$("#i1661").val('').attr("disabled",true);//select
				// //$("#i1537").attr("disabled",true);//select
			// }else{
				// cheques=0;
			// }
		// });
	
		// $.post("../../modulos/bancos/models/antes.php",{opc:3,idcuenta:$('#i1510').val()},
		// function (resp){
			// $('#i1510').empty();
			// $('#i1510').append(resp);
		// });
		$.post("../../modulos/bancos/models/antes.php",{opc:4,coin_id:$('#i1508').val()},
		function (resp){
			$('#i1508').empty();
			$('#i1508').append(resp);
		});	
		$("#i1510").select2({//cuenta contable
     width : "150px"
    });
    $("#i1508").select2({//moneda
     width : "150px"
    });	
		
<?php } ?>       
	
$('#send2').click(function() {
		var idcuenta = $('#i1510').val();
		var id=$('#i1504').val();
		if(id=='(Autonúmerico)'){ id=0;}
		$.post("../../modulos/bancos/models/antes.php",{opc:2,idcuenta:idcuenta,id:id},
		function (resp){
			<?php if($_REQUEST['a'] != 0){?>
				if(resp!=0){
					alert("La cuenta contable solo puede tener una cuenta bancaria");
				}else{
					$.post("../../modulos/bancos/models/antes.php",{opc:5,id:id},
					function (resp){
						if(confirm("El Saldo Inicial no podra ser cambiado seguro que es correcto!")){
							//$("#i1661").attr("disabled",false);
							//$("#i1537").attr("disabled",false);
							$("#i1538,#i1536,#i1535").attr('readonly',false);
							$('#send').click();
						}else{
							return false;
						}
						
					});
					
				}
			<?php }else{ ?>
					if(resp!=0){
						alert("La cuenta contable solo puede tener una cuenta bancaria");
					}
					else{
						
						$.post("../../modulos/bancos/models/antes.php",{opc:5,id:id,cheques:cheques},
						function (resp){
							//$("#i1661").attr("disabled",false);
							//$("#i1537").attr("disabled",false);
							$("#i1538,#i1536,#i1535").attr('readonly',false);
							$('#send').click();
						});
					}
			<?php } ?>
		});
});
/* comprueba si la cuenta tiene finalizada 
 * una conciliacion de ser asi 
 * no podra editar el saldo ni la moneda
 */ 
 <?php if($_REQUEST['a'] == 0){?>
 	$("#i1508").attr('onkeyup','return cambioMoneda()')
	$.post("../../modulos/bancos/models/antes.php",{opc:16,idbancaria:$("#i1504").val()},
		function (resp){
			if(resp==1){
				$("#i1528").attr("readonly",true);
				$("#i1508 option:not(:selected)").attr('disabled', true); 
				$("#i2320 option:not(:selected)").attr('disabled', true); 
				
			}
		});
/* si una cuenta bancaria tiene un documento ya no s epodra cambiar la moneda */
	$.post("../../modulos/bancos/models/antes.php",{opc:17,idbancaria:$("#i1504").val()},
		function (resp){
			if(resp==1){
				$("#i1508 option:not(:selected)").attr('disabled', true); 
				$("#i2320 option:not(:selected)").attr('disabled', true); 
			}
		});

<?php } ?>
});
</script>