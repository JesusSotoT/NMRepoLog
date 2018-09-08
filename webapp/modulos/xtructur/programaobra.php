<link rel="stylesheet" type="text/css" href="dhtmlxgantt.css"   title="estilo"  />
<script src="dhtmlxgantt.js" type="text/javascript"></script>
<script src="gant.espanol.js" type="text/javascript"></script>
<script src="dhtmlxgantt_tooltip.js"></script>

<style type="text/css">
	.grid_h0{ background-color: #c3c3c3 !important; border: 1px solid #c3c3c3;}
	.grid_h1{ background-color: #d3d3d3 !important;}
	.grid_h2{ background-color: #e3e3e3 !important;}
	.grid_h3{ background-color: #f3f3f3 !important;}

	.colores{ background-color: #f3f3f3 !important; color:#ff0000;}


#lc_chat_layout{
	display:none;
}

</style>


<?php
 $SQL = "SELECT * FROM constru_uploadPO WHERE id_obra='$idses_obra' order by id desc limit 1;";
  $result = $mysqli->query($SQL);
  if($result->num_rows>0) {
    $row = $result->fetch_array();
    if($row['opcion']=='d'){
    	$por='Por duracion';
    }
    if($row['opcion']=='r'){
    	$por='Por rendimiento';
    }
    $existeo=1;
  }else{	
  	$existeo=0;
  }
?>
<body>

    <div id="divcuandohayobra" class="row">
      <div class="col-sm-12">
<div class="panel panel-default" >
			<!-- Panel Heading -->
			<div class="panel-heading">
			<div class="panel-title">Cargar programa de obra</div>
			</div><!-- End panel heading -->

			<!-- Panel body -->
			<div class="panel-body" >
			  <div class="col-md-12" style="padding:0px;">
 	<b>Descarga de programa de obra</b>
 	<div class="row">
	 <div class="col-md-2">
	 	<button class="btn btn-primary btn-sm" onclick="programaObra(1)"><span class="glyphicon glyphicon-download"></span> Por rendimiento</button>
	 </div>
	 <div class="col-md-10">
	 	<button class="btn btn-primary btn-sm" onclick="programaObra(2)"><span class="glyphicon glyphicon-download"></span> Por duración</button>
	 </div>
	</div>
 </div>
<div class="row">
&nbsp;
</div>
<div class="row">
&nbsp;
</div>

<div class="col-md-12" style="padding:0px;">
 	<b>Cargar programa de obra</b>
 	<div class="row">
	 <div class="col-md-2">
	 	<select id="selpobra">
	 		<option value="r">Por rendimeinto</option>
	 		<option value="d">Por duracion</option>
	 	</select>
	 </div>
	 <div class="col-sm-10">
		<input type="file" name="file_upload" id="file_upload_2" />
	</div>
	</div>
 </div>

<div class="row">
&nbsp;
</div>
<div class="row">
&nbsp;
</div>

<div class="col-md-12" style="padding:0px;">
 	<b>Ultimos archivo cargado</b>
 	<div class="row">
	 <div class="col-md-12">
	 <?php if($existeo==0){ ?>
	 	No hay archivos cargados.
	 <?php }else{ ?>
	 	<table style="font-size:12px">
	 		<tr>
	 		<th width=150>Fecha de carga</th>
	 		<th width=220>Archivo</th>
	 		<th width=150>Tipo</th>
	 		<th width=130>Gantt</th>
	 		</tr>
	 		<tr>
	 		<td><?php echo $row['fecha']; ?></td>
	 		<td><?php echo $row['presupuestoxls']; ?></td>
	 		<td><?php echo $por; ?></td>
	 		<td>
	 		<button class="btn btn-primary btn-xs" onclick="verGantt(<?php echo $idses_obra; ?>,'<?php echo $row['opcion']; ?>')"><span class="glyphicon glyphicon-download"></span> Visualizar</button>
	 		</td>
	 		</tr>
	 	</table>
	 <?php } ?>
	 </div>	
	</div>
 </div>

<div class="row">
&nbsp;
</div>
<div class="row">
&nbsp;
</div>
			    
			</div><!-- ENd panel body -->
		</div>



<div class="col-md-12" style="padding:0px; width: 100%; margin-left:0%;">
 <div id="gantt_here" style="width: 100%; height: 100%;"></div>
</div>
</div>
</div>
</body>
<!--
<div class="row">
<button class="btn btn-primary btn-xs" onclick="guardarGantt();"> Guardar</button>
<div class="col-md-12" style="padding:0px;height:200px;">
</div>
</div>
-->


 <script>
$(function() {

$("#file_upload_2").uploadify({
	height        : 28,
	swf           : 'uploadify/uploadify.swf',
	uploader      : 'uploadify/uploadify.php',
	width         : 105,
	'onUploadSuccess' : function(file, data, response) {
	  selpobra = $('#selpobra').val();

	    $.ajax({
	        url:"importacion_pobra.php",
	        type: 'POST',
	        async: false,
	        data:{file:file.name,selpobra:selpobra},
	        success: function(r){
	          if(r==1){
	            alert('Importacion creada exitosamente');
	            window.location='index.php?modulo='+modulo;
	          }else{
	            alert('Error durante el proceso de importacion');
	          }
	            
	        }
	    });

	} 
});
});

	 function programaObra(option){
	 	window.open('xls_progObra.php?id='+option);
	 }

function verGantt(idobra,por){

	var demo_tasks='';
	$.ajax({
        url:"ajax.php",
        type: 'POST',
        async: false,
        data:{opcion:'data_gantt',por:por},
        success: function(r){
          	demo_tasks=r;            
        }
    });

	/*
	"links":[
				{"id":"10","source":"11","target":"12","type":"1"},
				*/
		
	gantt.config.lightbox.sections = [
		{name: "description", height: 70, map_to: "text", type: "textarea", focus: true},
		{name: "time", height: 72, type: "duration", map_to: "auto"}
	];

	gantt.config.scale_unit = "month";
	gantt.config.date_scale = "%F, %Y";

	gantt.config.task_height = 15;
	gantt.config.row_height = 25;

	gantt.config.min_column_width = 50;
	gantt.config.scale_height = 60;

	gantt.config.subscales = [
		{unit:"day", step:3, date:"%j, %D" }
	];

	gantt.config.columns = [
	    {name:"text",       label:"Concepto",  width:"200", tree:true, template:myFunc },
	    {name:"start_date", label:"Inicio", align: "center" },
	    {name:"duration",   label:"Duracion",   align: "center" }
	];

	gantt.init("gantt_here");
	gantt.clearAll();

	function myFunc(task){

	    return "<div class='important' style='font-size:12px;'>"+task.text+"</div>";
	  
	};

	gantt.templates.grid_row_class = function( start, end, task ){
	    if ( task.$level == 0 ){ return "grid_h0"; }
	    if ( task.$level == 1 ){ return "grid_h1"; }
	    if ( task.$level == 2 ){ return "grid_h2"; }
	    if ( task.$level == 3 ){ return "grid_h3"; }

	};

	gantt.templates.tooltip_text = function(start,end,task){
		/*
		desc='';
		$.ajax({
	        url:"ajax.php",
	        type: 'POST',
	        async: false,
	        data:{opcion:'desc_gantt',codigo:task.text},
	        success: function(r){
	          	desc=r;
	        }
	    });
	    */
	    return "<b>Descripcion:</b> "+task.text+"<br/><b>Duracion:</b> " + task.duration;
	};

	gantt.templates.task_text=function(start,end,task){
		corte = task.text.split(' |  | ');
		return corte[0];
	    //return "<b>Text:</b> "+task.text+",<b> Holders:</b> "+task.users;
	};

	gantt.config.drag_resize = false;
	gantt.config.drag_move = false;
	gantt.config.drag_progress = false;

/*
	gantt.templates.task_row_class  = function(start, end, task){
	    if(task.$level<4){
	          return 'meeting_task';
	    }
	};
*/
		
	gantt.parse(demo_tasks);
}

function guardarGantt(){

	var json = gantt.serialize();
	console.log(json);

	$.ajax({
        url:"ajax.php",
        type: 'POST',
        async: false,
        data:{opcion:'save_gantt',links:json.links},
        success: function(r){
          	//demo_tasks=r;            
        }
    });
}
</script>

