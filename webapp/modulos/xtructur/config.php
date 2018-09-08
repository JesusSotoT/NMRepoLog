<?php
 $SQL = "SELECT autorizaciones, tiempo, puaut, correo, correo_can,nominadomingo,presupuesto,limitar,ocorreo,rcorreo,matriz,correo_aut FROM constru_config WHERE id_obra='$idses_obra' LIMIT 1;";
  $result = $mysqli->query($SQL);
  if($result->num_rows>0) {
    $row = $result->fetch_array();
    $autsel=$row['autorizaciones'];
    $auttime=$row['tiempo'];
    $puaut=$row['puaut'];
    $correo=$row['correo'];
    $correo_can=$row['correo_can'];
    $nominad=$row['nominadomingo'];
    $pres=$row['presupuesto'];
        $matriz=$row['matriz'];
      $lim=$row['limitar'];
         $ocorreo=$row['ocorreo'];
                $rcorreo=$row['rcorreo'];
                $correo_aut=$row['correo_aut'];
  }else{	
  	$autsel=1;
  	$auttime=0;
  	$puaut=1;
  	$correo=0;
  	$correo_can=0;
  	$nominad=0;
  	$pres=0;
  	$lim=1;
  	$ocorreo='';
  	$rcorreo='';
  	 $correo_aut=0;
  		$matriz=0;
  }

 ?>




 <body>

    <div id="divcuandohayobra" class="row">
      <div class="col-sm-12">
        <!--<div class="navbar navbar-default"  style="margin-top:10px;">
          <div class="navbar-header">
              <div class="navbar-brand" style="color:#333;">Configuracion inicial</div>
          </div>
        </div>-->


		<div class="panel panel-default" >
			<!-- Panel Heading -->
			<div class="panel-heading">
			<div class="panel-title">Configuracion inicial</div>
			</div><!-- End panel heading -->

			<!-- Panel body -->
			<div class="panel-body" >
			  
				<div class="row" style="padding: 10px;">
	<div class="col-md-2">
	 	¿Requiere autorizacion para requisiciones estimaciones y nominas?
	 </div>
	 <div class="col-md-2">
	 	<input type="radio" name="ra" value="1" <?php if($autsel==1){ echo 'checked="checked"'; } ?>   style="cursor:pointer;"> Si &nbsp;&nbsp;&nbsp;
	 	<input type="radio" name="ra" value="0" <?php if($autsel==0){ echo 'checked="checked"'; } ?>  style="cursor:pointer;"> No
	 </div>
	 <div class="col-md-2">
	 	Seleccione el intervalo de tiempo para la emision de alertas
	 </div>
	 <div class="col-md-6">
	 	<input type="radio" name="ti" value="1" <?php if($auttime==1){ echo 'checked="checked"'; } ?>   style="cursor:pointer;"> 10 Min &nbsp;&nbsp;&nbsp;
	 	<input type="radio" name="ti" value="2" <?php if($auttime==2){ echo 'checked="checked"'; } ?>  style="cursor:pointer;"> 15 Min
	 	&nbsp;&nbsp;&nbsp;
	 	<input type="radio" name="ti" value="3" <?php if($auttime==3){ echo 'checked="checked"'; } ?>  style="cursor:pointer;"> 30 Min
	 	&nbsp;&nbsp;&nbsp;
		<input type="radio" name="ti" value="4" <?php if($auttime==4){ echo 'checked="checked"'; } ?>  style="cursor:pointer;"> 60 Min

	 </div>
	 <div class="row">&nbsp;</div>
	 <div class="row">&nbsp;</div>
	 <div class="col-md-2">
	 	¿Desea asignar PU de destajo y subcontrato automaticamente?
	 </div>
	 <div class="col-md-2">
	 	<input type="radio" name="pu" value="1" <?php if($puaut==1){ echo 'checked="checked"'; } ?>   style="cursor:pointer;"> Si &nbsp;&nbsp;&nbsp;
	 	<input type="radio" name="pu" value="0" <?php if($puaut==0){ echo 'checked="checked"'; } ?>  style="cursor:pointer;"> No
	 </div>
	 <div class="col-md-2">
	 	Enviar correo automatico a sus proveedores al autorizar una orden de compra
	 </div>
	 <div class="col-md-2">
	 	<input type="radio" name="ec" value="1" <?php if($correo==1){ echo 'checked="checked"'; } ?>   style="cursor:pointer;"> Si &nbsp;&nbsp;&nbsp;
	 	<input type="radio" name="ec" value="0" <?php if($correo==0){ echo 'checked="checked"'; } ?>  style="cursor:pointer;"> No &nbsp;&nbsp;&nbsp;
	 </div>
	 <div class="col-md-2">
	 	Enviar correo automatico al usuario cuando se cancela alguna estimacion por autorizar.
	 </div>
	 <div class="col-md-2">
	 	<input type="radio" name="ecc" value="1" <?php if($correo_can==1){ echo 'checked="checked"'; } ?>   style="cursor:pointer;"> Si &nbsp;&nbsp;&nbsp;
	 	<input type="radio" name="ecc" value="0" <?php if($correo_can==0){ echo 'checked="checked"'; } ?>  style="cursor:pointer;"> No &nbsp;&nbsp;&nbsp;
	 </div>
	  <div class="row">&nbsp;</div>
	   <div class="row">&nbsp;</div>
	  <div class="col-md-2">
	 	¿Desea sumar el dia domingo automaticamente a las nominas?
	 </div>
	 <div class="col-md-2">
	 	<input type="radio" name="nd" value="1" <?php if($nominad==1){ echo 'checked="checked"'; } ?>   style="cursor:pointer;"> Si &nbsp;&nbsp;&nbsp;
	 	<input type="radio" name="nd" value="0" <?php if($nominad==0){ echo 'checked="checked"'; } ?>  style="cursor:pointer;"> No &nbsp;&nbsp;&nbsp;
	 </div>
  <div class="col-md-2">
	 	¿Desea definir la planeacion automaticamente al cargar el presupuesto contractual?
	 </div>
	 <div class="col-md-2">
	 	<input type="radio" name="pres" value="1" <?php if($pres==1){ echo 'checked="checked"'; } ?>   style="cursor:pointer;"> Si &nbsp;&nbsp;&nbsp;
	 	<input type="radio" name="pres" value="0" <?php if($pres==0){ echo 'checked="checked"'; } ?>  style="cursor:pointer;"> No &nbsp;&nbsp;&nbsp;
	 </div>


	 <div class="col-md-2">
	 	¿Desea limitar el volumen tope de acuerdo al avance de obra en las estimaciónes a subcontratistas, maestros y clientes?
	 </div>
	 <div class="col-md-2">
	 	<input type="radio" name="lim" value="1" <?php if($lim==1){ echo 'checked="checked"'; } ?>   style="cursor:pointer;"> Si &nbsp;&nbsp;&nbsp;
	 	<input type="radio" name="lim" value="0" <?php if($lim==0){ echo 'checked="checked"'; } ?>  style="cursor:pointer;"> No &nbsp;&nbsp;&nbsp;
	 </div>
  <div class="row">&nbsp;</div>
	   <div class="row">&nbsp;</div>
	  <div class="col-md-2">

	 Enviar correo para notificar una orden de compra pendiente <?php if($ocorreo=='') { ?><input style="margin-top: 5px;" type='text' id='ocorreo' size='45' placeholder="Escriba un correo o mas, separados por coma." value=''><?php }else { ?><input style="margin-top: 5px;" placeholder="Escriba un correo o mas, separados por coma" type='text' id='ocorreo' size='45' value='<?php echo $ocorreo; ?>' > <?php } ?>
	 </div>
	   <div class="col-md-2">&nbsp;</div>
	   <div class="col-md-2">
Enviar correo para notificar una orden de requisicion pendiente <?php if($rcorreo=='') { ?><input style="margin-top: 5px;" type='text' id='rcorreo' size='45' placeholder="Escriba un correo o mas, separados por coma." value=''><?php }else { ?><input style="margin-top: 5px;" placeholder="Escriba un correo o mas, separados por coma" type='text' id='rcorreo' size='45' value='<?php echo $rcorreo; ?>' > <?php } ?>
	 </div>
<div class="col-md-2">&nbsp;</div>
	 <div class="col-md-2">
	 	¿Desea trabajar con matrices?</div>
	 <div class="col-md-2">
	 	<input type="radio" name="matriz" value="1" <?php if($matriz==1){ echo 'checked="checked"'; } ?>   style="cursor:pointer;"> Si &nbsp;&nbsp;&nbsp;
	 	<input type="radio" name="matriz" value="0" <?php if($matriz==0){ echo 'checked="checked"'; } ?>  style="cursor:pointer;"> No &nbsp;&nbsp;&nbsp;
	 </div>




</div>
  <div class="row">&nbsp;</div>


	 <div class="col-md-2">
	 	¿Enviar correo para notificar una autorizacion de requisicion/orden de compra?</div>
	 <div class="col-md-2">
	 	<input type="radio" id="correoaut" value="1" <?php if($correo_aut==1){ echo 'checked="checked"'; } ?>   style="cursor:pointer;"> Si &nbsp;&nbsp;&nbsp;
	 	<input type="radio" id="correoaut" value="0" <?php if($correo_aut==0){ echo 'checked="checked"'; } ?>  style="cursor:pointer;"> No &nbsp;&nbsp;&nbsp;
	 </div>
  <div class="row">&nbsp;</div>

<div class="row" style="padding: 10px;">
	 <div class="col-md-12">
	 	<button class="btn btn-primary btn-sm" onclick="saveConfig()"> Guardar cambios</button>
	 </div>
</div>




			    
			</div><!-- ENd panel body -->
		</div>

        
      </div>
    </div>

</body>


