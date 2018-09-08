<?php 
$this->load->helper('url');
$base_url=str_replace("modulos/mrp/","",base_url());

if(!isset($_SESSION)) {
     session_start();
}

unset( $_SESSION["cantidad_array"]);
unset( $_SESSION["unidad_array"]);
unset( $_SESSION["nombre_array"]);
unset( $_SESSION["proveedor_array"]);
unset( $_SESSION["costo_array"]);
unset( $_SESSION["subtotal_array"]);
unset( $_SESSION["unidad_texto"]);
unset( $_SESSION["nombre_texto"]);
unset( $_SESSION["proveedor_texto"]);
unset( $_SESSION['sucursal_solicita_temporal']);
unset( $_SESSION['fecha_pedido_temporal']);
unset( $_SESSION['fecha_entrega_temporal']);
unset( $_SESSION['elaborado_por_temporal']);
unset( $_SESSION['autorizado_por']);
unset( $_SESSION['fecha_entrega_edicion']);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" 
"http://www.w3.org/TR/html4/strict.dtd">
<html lang="sp">
<head>						
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title></title>	
<LINK href="<?php echo $base_url; ?>netwarelog/catalog/css/view.css" title="estilo" rel="stylesheet" type="text/css" />
<LINK href="<?php echo $base_url; ?>netwarelog/catalog/css/estilo.css" title="estilo" rel="stylesheet" type="text/css" />
 <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
 <link rel="stylesheet" type="text/css" href="<?php echo $base_url; ?>netwarelog/design/default/netwarlog.css" />  	
 <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
 <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/grid.js"></script>
<script>
function openEtapa(id,cadti,idord){
	baseUrl='<?php echo base_url(); ?>';
    $.ajax({
      url:baseUrl+'index.php/production_order/etapaProceso/'+id+'/'+cadti,
      type: 'POST',
      data:{idord:idord},
      dataType:'JSON',
      success: function(data){ 
      $('#procesos-cont').html(''); 
        $.each(data, function( key, value ) {
          if(key!='dt'){
            $('#procesos-cont').append('\
            <div style="float:left; width:100%;margin-top:20px;font-size:12px;">\
            	<div style="float:left;width:200px;"><b>Nombre del proceso:</b></div>\
            	<div style="float:left;width:300px;">'+value.proceso+'</div>\
            </div>');
            $('#procesos-cont').append('\
            <div style="float:left; width:100%;margin-top:5px;font-size:12px;">\
            	<div style="float:left;width:200px;">Descripcion:</div>\
            	<div style="float:left;width:400px;">'+value.descripcion+'</div>\
            </div>');
            $('#procesos-cont').append('\
            <div style="float:left; width:100%;margin-top:5px;font-size:12px;">\
            	<div style="float:left;width:200px;">Tiempo estimado:</div>\
            	<div style="float:left;width:300px;">'+value.duracion+'</div>\
            </div>');
            $('#procesos-cont').append('\
            <div style="float:left; width:100%;margin-top:5px;font-size:12px;">\
            	<div style="float:left;width:200px;">Fecha de inicio:</div>\
            	<div style="float:left;width:300px;">'+value.dta+'</div>\
            </div>');
            $('#procesos-cont').append('\
            <div style="float:left; width:100%;margin:5px 0 20px 0;font-size:12px;">\
            	<div style="float:left;width:200px;">Fecha fin estimado:</div>\
            	<div style="float:left;width:300px;">'+value.dt.date+'</div>\
            </div>');
            //$('#procesos-cont').append('<div style="float:left; width:100%;">'+value.descripcion+'</div>');
            //$('#procesos-cont').append('<div style="float:left; width:100%;">Tiempo estimado: '+value.duracion+'</div>');
            //$('#procesos-cont').append('<div style="float:left; width:100%;">Fecha de inicio: '+value.dta+'</div>');
            //$('#procesos-cont').append('<div style="float:left; width:100%;">Fecha fin estimado: '+value.dt.date+'</div>');
          }
        });
        $('#procesos-cont').css('display','block'); 
      } 
    }); 
  }
$(function(){
  $('.ttline').click(function(event) {
  	id=$(this).attr('clave');
  		abaseUrl='<?php echo base_url(); ?>';
		$.ajax({
			url:abaseUrl+'index.php/production_order/timeline/'+id,
			type: 'POST',
			data: {},
			success: function(callback)
			{	
				$('#etapas').dialog({
					modal: true,
					draggable: true,
					resizable: true,
					title:"Timeline",
					position:['top',20],
					width:680,
					height:380,
					dialogClass:"mydialog",
					open: function(){
						$(this).empty().append(callback);
					},
					buttons:{
						"Cancelar": function(){
              $('#etapas').dialog('close');
						}
					}
				}).height('auto');
			}
		});
  		event.preventDefault();
	});  	
});
</script>
</head>
<body>						
<span id="grid"><?php echo $grid?></span>
	<div id="etapas" style="display:none; margin:0px 0px 100px 0px;">
	</div>
</body>
</html> 