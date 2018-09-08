<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script src="js/select2/select2.min.js"></script>
<link rel="stylesheet" type="text/css" href="js/select2/select2.css" />

<script>
$(document).ready(function(){
	//$("#frm").attr("onsubmit","return val_inactivo()");
	$("#i1990").before("<input type='hidden' id='activo_h' value='"+$("#i1990").val()+"'>");
	$("#i1990").before("<input type='hidden' id='id_h' name='id_h' value='"+$("#i1539").val()+"'>");
	

	//i1542 moneda
	//i1544 cuenta
	//i1677 dependencia
	$("#lbl1544,#i1544_div").hide();
	$("#i1544").empty();
	$("#lbl1677,#i1677_div").hide();//DEPENDECIA
	
	$("#i1542,#i1677").select2({ width : "150px" });
	
	
	$.post('../../modulos/bancos/models/antes.php',{opc:13},
	function(resp){
		$("#i1542").html(resp);
		$("#i1542").val(1);
	});
 <?php if($_REQUEST['a'] != 0){?>
 	
		
 	$('#i1676').val(1);
 	$('#i1677').empty();
	$.post('../../modulos/bancos/models/antes.php',{opc:7},
	function(resp){
		$("#i1677").html(resp);
	});
	// $("#i1544").empty();
	// $.post('../../modulos/bancos/models/antes.php',{opc:8},
	// function(resp){
		// $("#i1544").html(resp);
	// });
	
 <?php }else{?>
 	var monedadefaul = $("#i1542").val();
 	$.post('../../modulos/bancos/models/antes.php',{opc:13},
	function(resp){
		$("#i1542").html(resp);
 		$("#i1542").val(monedadefaul);
	});
 	
	$.post('../../modulos/bancos/models/antes.php',{opc:9,depen:$("#i1677").val()},
	function(resp){
		$("#i1677").html(resp);
	});
	
	var tipoc = $("#i1543").val();
	$('#i1543').change(function(){
			if($("#i1543").val()!=tipoc){
				if($("#i1676").val()==1){
					$.post('../../modulos/bancos/models/antes.php',{opc:15,id:$("#i1539").val()},
					function(resp){
						if(resp==1){//tiene hijos
							alert("No puede cambiar el tipo, la subcategoria tiene documentos asociados");
							$("#i1543").val(tipoc).select2({ width : "150px" });
							
						}
						
					});
					
				}else{
					$.post('../../modulos/bancos/models/antes.php',{opc:14,id:$("#i1539").val()},
					function(resp){
						if(resp==1){//tiene hijos
							alert("No puede cambiar el tipo, la categoria tiene hijos");
							$("#i1543").val(tipoc).select2({ width : "150px" });
						}
						
					});
					
				}
			}
	});
	// $.post('../../modulos/bancos/models/antes.php',{opc:10,cuenta:$("#i1544").val()},
	// function(resp){
		// $("#i1544").html(resp);
	// });
	
 <?php } ?>
 
 if($("#i1676").val()==1){
		$("#lbl1677,#i1677_div").show();
	}else{
		$("#lbl1677,#i1677_div").hide();
	}
});
//i1676 nivel
$('#i1676').change(function(){
	if($("#i1676").val()==1){
		
		$.post('../../modulos/bancos/models/antes.php',{opc:14,id:$("#i1539").val()},
		function(resp){
			if(resp==1){//tiene hijos
				$("#i1676").val(2).select2({ width : "150px" });
				alert("No puede cambiar a subcategoria, la categoria  tiene hijos");
			}else{
				$("#lbl1677,#i1677_div").show();
			}
			
		});
		
	}else{
		$.post('../../modulos/bancos/models/antes.php',{opc:15,id:$("#i1539").val()},
		function(resp){
			if(resp==1){//tiene hijos
				$("#i1676").val(1).select2({ width : "150px" });
				alert("No puede cambiar a Categoria, la subcategoria  tiene documentos asociados");
			}else{
				$("#lbl1677,#i1677_div").hide();
			}
			
		});
		
	}
});

$('#i1990').change(function(){
	if($('#i1990').val()==0){
		if($("#i1676").val()==2){
	
			$.post('../../modulos/bancos/models/antes.php',{opc:14,id:$("#i1539").val()},
			function(resp){
				if(resp==1){//tiene hijos
					$("#i1990").val(-1).select2({ width : "150px" });
					alert("No puede inactivar la categoria, la categoria tiene hijos");
				}
				
			});
			
		}else{
			$.post('../../modulos/bancos/models/antes.php',{opc:15,id:$("#i1539").val()},
			function(resp){
				if(resp==1){//tiene hijos
					$("#i1990").val(-1).select2({ width : "150px" });
					alert("No puede inactivar subcategorias que tienen documentos asociados");
				}
				
			});
		}
	}	
		

});	


</script>