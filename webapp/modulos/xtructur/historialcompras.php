<?php

$SQL = "SELECT a.id, concat('Requisicion-',a.id,' (',substr(a.fecha_captura,1,10),')') as fechaca from constru_requis a
 where a.id_obra='$idses_obra' order by a.id desc";
  $result = $mysqli->query($SQL);
  if($result->num_rows>0) {
    while($row = $result->fetch_array() ) {
      $requis[]=$row;
    }
  }else{
    $requis=0;
  }

$wp=200;
 ?>
 <style>
td {
	width:200px;
}

 </style>

<div class="row">&nbsp;</div>
 <div class="panel panel-default" >
			<!-- Panel Heading -->
			<div class="panel-heading">
			<div class="panel-title">Historial de compras</div>
			</div><!-- End panel heading -->

			<!-- Panel body -->
			<div class="panel-body" >
			 	<div class="col-xs-4 col-md-4">
					<select class="form-control" id="selreq">
					    <option selected="selected" value="0">Selecciona una requisicion</option>
					    <?php if($requis!=0){ 
					    	foreach ($requis as $k => $v) { ?>
							<option value="<?php echo $v['id']; ?>"><?php echo $v['fechaca']; ?></option>
					    <?php  } } ?>
					</select>
				</div>
				<div class="col-xs-2 col-md-2">
					<button class="btn btn-primary" onclick="historialCompras();">Ver requisicion</button>
				</div>
			    
			</div><!-- ENd panel body -->
		</div>


 <div class="container well">
    <div class="row">
        

    <div>
    <div>&nbsp;</div>
    
    </div>
</div>

<div>&nbsp;</div>

<div id="lodemas">


</div>

<script>
	function historialCompras(){
		idReq=$('#selreq').val();
		if(idReq==0){
			$('#lodemas').html('');
			alert('Seleccione una requisicion');
			return false;
		}
		$.ajax({
	        url:'historialajax2.php',
	        type: 'POST',
	      
	        data: {idReq:idReq},
	        success: function(r){
	        		$('#lodemas').empty();
	        		$('#lodemas').html(r);
	        	}
	        });
	}
</script>