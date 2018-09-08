<?php
 $idusr = $_SESSION['accelog_idempleado'];
 $SQL = "SELECT interfaz FROM constru_user_interfaz WHERE id_usuario='$idusr' LIMIT 1;";
  $result = $mysqli->query($SQL);
  if($result->num_rows>0) {
    $row = $result->fetch_array();
    $interfaz=$row['interfaz'];

  }else{	
  	$interfaz='default';

  }

 ?>



<body>
  <div class="container" style="width:100%">
  <div class="row">
&nbsp;
</div>
    <div class="row">
      <div class="col-sm-10 col-sm-offset-1">
        <div class="panel panel-default" >
			<!-- Panel Heading -->
			<div class="panel-heading">
			<div class="panel-title">Fondo Interfaz</div>
			</div><!-- End panel heading -->

			<!-- Panel body -->
			<div class="panel-body" >
				<div class="col-md-12"  style="margin-bottom:0px;">
				 	Seleccione una imagen de fondo que se aplicara a la session de este usuario
				</div>
				<div class="col-md-3" style="margin-top:20px;">
					<img onclick="saveFondo(1);" src="fondo_xtructur01.png" width="200" height="160" style="cursor:pointer; border: 1px solid #ccc; padding: 5px;" />
				</div>
				<div class="col-md-3" style="margin-top:20px;">
					<img onclick="saveFondo(2);" src="fondo_xtructur02.png" width="200" height="160" style="cursor:pointer; border: 1px solid #ccc; padding: 5px;" />
				</div>
				<div class="col-md-3" style="margin-top:20px;">
					<img onclick="saveFondo(3);" src="fondo_xtructur03.png" width="200" height="160" style="cursor:pointer; border: 1px solid #ccc; padding: 5px;" />
				</div>
				<div class="col-md-3" style="margin-top:20px;">
					<img onclick="saveFondo(4);" src="fondo_xtructur04.png" width="200" height="160" style="cursor:pointer; border: 1px solid #ccc; padding: 5px;" />
				</div>
				<div class="col-md-3" style="margin-top:20px;">
					<img onclick="saveFondo(5);" src="fondo_xtructur05.png" width="200" height="160" style="cursor:pointer; border: 1px solid #ccc; padding: 5px;" />
				</div>
				<div class="col-md-3" style="margin-top:20px;">
					<img onclick="saveFondo(6);" src="fondo_xtructur06.png" width="200" height="160" style="cursor:pointer; border: 1px solid #ccc; padding: 5px;" />
				</div>
				<div class="col-md-3" style="margin-top:20px;">
				<div onclick="saveFondo('default');" style="cursor:pointer; background-color: #fefefe; border: 1px solid #ccc; padding: 5px; width: 200px; height: 160px;">
					
				</div>


			    
			</div><!-- ENd panel body -->
		</div>
      </div>
    </div>
  </div>
</body>

<script>
function saveFondo(op){
	$.ajax({
      url:"ajax.php",
      type: 'POST',
      data:{opcion:'cambiaFondo',op:op},
      success: function(r){
        window.location='index.php?modulo='+modulo;
      }
    });
}
</script>
