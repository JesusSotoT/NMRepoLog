<?php 
$this->load->helper('url');
$base_url=str_replace("modulos/mrp/","",base_url());
?>

<html>
<head>
	<link href="<?php echo $base_url; ?>netwarelog/catalog/css/estilo.css" title="estilo" rel="stylesheet" type="text/css" />
	<link href="<?php echo $base_url; ?>netwarelog/catalog/css/view.css"   title="estilo" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.alphanumeric.js"></script> 
	<script>
	$(function(){
		$("#material").change(function() {
			
			alert($(this).val());
			alert('lkajdlkfjasd');
			valor=$(this).val();

			xx=window.location.href;
			if( xx.match(/formNuevo\/[0-9]{1,}/) ){
				baseUrl='../../../';
			}else{
				baseUrl='../../';
			}

			$.ajax({
				type: 'POST',
				url:baseUrl+'index.php/product/material',
				dataType: 'json',
				data: { 
					valor:valor
				},
				success : function(vale){
					if(vale!=''){
						// $("#unidad").empty();
						//alert(vale);
						// $.each(vale, function(index, x) {
							// $(document.createElement('option')).attr({'value':x['idUni']}).html(x['compuesto']).appendTo($("#unidad"));
						// });
					}

				}

			}); 

		})
		$(".float").numeric({allow:'.'});

	});

	</script> 
</head>
<body>
	<table><tr class="listadofila"><td class="campo">
		<div id="listaproductos" style="width:460px;"><?php echo $lista;?></div>
	</td></tr></table>
</body>
</html>

