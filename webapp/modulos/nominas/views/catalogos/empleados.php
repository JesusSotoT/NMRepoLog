<!DOCTYPE html>
<head>
  <meta charset="utf-8">
  <script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
  <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
  <script type="text/javascript" src="js/ui.datepicker-es-MX.js"></script> 
  <script type="text/javascript" src="js/empleados.js"></script>
  <link rel="stylesheet" type="text/css" href="css/empleado.css" />
</head>
<body>

  <script>
    $(document).ready(function(){

      if ($("#tipocuenta").length<=1) {
        var longi = $( "#tipocuenta" ).length;
        cuent='0'+longi;
        ($("#tipocuenta").val(cuent));

      }

      <?php 
     	 if($huella == 1){?>
     	 	
      		 $('#conhuella').show();
      		 $('#sinhuella').hide();
      	 
    <?php }else{?>
    			 $('#conhuella').hide();
    			 $('#sinhuella').show();
    <?php }
      
      if ($Nominas == 1 || $NominasManual==1) { ?>
       $('#validaC').hide();
       $( "#valida" ).prop( "checked", true );

       <?php  
     }else {?>
     //$("#nss").prop('disabled', true);
     $("#valida").prop( "checked", true );
     <?php } ?>

     ciudad = <?php if(isset($datos)) { echo $datos->idmunicipio; }else{echo 0;}; ?>;
     ciudadbusca();

     <?php 
     if(isset($datos)){
     	$funcion = "&opc=0"; 
     	?>
     	$("#fechaalta").datepicker("disable").attr("readonly",true);
     	<?php 
     	
     }else{
     	$funcion = "&opc=1"; 
     }
     ?>
   });
 </script>
 <div class="container">
   <?php
   echo "<script>var url_empleados = 'ajax.php?c=Catalogos&f=almacenaEmpleado". $funcion ."';</script>";
   ?>


   <div class="container well">
    <div class="row" style="padding-left: 15px;">
     <div>
      <button class="btn btn-default" onclick="atraslistado();">
       <i class="fa fa-arrow-left" aria-hidden="true"></i>
       Regresar
     </button>
     <button id="load" class="btn btn-primary" data-loading-text="<i class='fa fa-refresh fa-spin '></i>" type="button">
       <span class="glyphicon glyphicon-floppy-disk"></span>
       Guardar
     </button>
   </div>											
 </div>
 <div class="panel panel-default">

   <div class="panel-heading">
    <h3 class="panel-title">Empleados </h3>
  </div>
  <div class="panel-body">

    <form id="formempleados">
     <input type="hidden"  value="<?php if(isset($datos)){ echo $datos->idEmpleado; } ?>" id="idempleado" name="idempleado"/>
     <ul class="nav nav-tabs">
      		<!--
      			<li>
      				<a data-toggle="tab"  href=""  onclick="atraslistado()" title= "Regresar listado">
      					<i class="fa fa-arrow-left" aria-hidden="true"  ></i> Regresar
      				</a>
      			</li>
      		-->
      		<li class="active"><a data-toggle="tab" href="#general"><b>General</b></a></li>
      		<!-- appministra -->
      		<?php if($Appministra){?>
      		<li><a data-toggle="tab" href="#app"><b>Appministra</b></a></li>
      		<?php } ?>
      		<!-- appministra -->
      		<!-- N O M I N A S	-->
      		<?php if($Nominas == 1 || $NominasManual==1 ){?>
      		<li><a data-toggle="tab" href="#nomina"><b>Nominas General</b></a></li>
      		<li><a data-toggle="tab" href="#imss"><b>IMSS-Infonavit</b></a></li>
      		<li><a data-toggle="tab" href="#sueldos"><b>Mas</b></a></li>
      		<!-- <li><a data-toggle="tab" href="#pagos"><b>Pagos de Nomina y Extras</b></a></li> -->	 
      		<?php } ?> 
      		<!--fin N O M I N A S	-->
      	</ul>
                     
 
 <input type="hidden" id="fechahistorial " name="fechahistorial" class="form-control input-md"  value="<?php if(isset($datos)){ echo $datos->fechahistorial; }  ?>"/>

 <input type="hidden" id="SalarioHistorico" name="SalarioHistorico" class="form-control input-md"  value="<?php if(isset($datos)){ echo $datos->SalarioHistorico; }  ?>"/>


      	<input type="hidden" value="<?php echo $Appministra; ?>" name="appministra" id="appministra" />
      	<input type="hidden" value="<?php echo $Nominas; ?>" name="nominas" id="nominas" />
      	<input type="hidden" value="<?php echo $NominasManual; ?>" name="NominasManual" id="NominasManual" />
      	<div class="tab-content">
      		<div id="general" class="tab-pane fade in active">
      			<br>
      			<br>
      			<div>
      				<section>
      					<div class="row">
      						<div class="col-md-12">
      							<?php if($Appministra){?>
      							<div id="validaC" align="right">	Validación:
      								<input id="valida" type="checkbox" name="valida" value="valida">			      									
      							</div>
      							<?php } ?>
      							<div class="col-md-3">
      								<div id="marcoVistaPrevia">
      									<?php 
      									if ($datos->imagen ==null){  
      										echo '<img id="vistaPrevia" src="images/default.jpeg" alt="" />';

      									} else {                   
               //echo '<img src="data:image/jpeg,Jpg;base64,'.$datos->imagen.'"  id=vistaPrevia />'
      										echo '<img src="data:image/jpg;base64,'.$datos->imagen.'"  id=vistaPrevia />';
      									}
      									?>
      								</div>
      								<label for="imagenUsuario" class="marcoVistaPrevia" id="imagen">Fotografía:</label>
      								<input id="seleccionarImagen" name="imagen" type="file" accept=".jpg, .png, .jpeg, .gif, .bmp"/>
      								<!--<img id="vistaPrevia"/><br/>-->

      							</div>
      							<div class="col-md-3" align="left">
      								Codigo:<b style="color:red">*</b>
      								<input type="text" id="codigo" name="codigo" class="form-control input-md"  value="<?php if(isset($datos)){ echo $datos->codigo; }  ?>"/>
      								Estado Civil:<b style="color:red">*</b>
      								<select id="civil" name="civil" class="selectpicker" data-width="100%" data-live-search="true">
      									<?php while ($e = $estadocivil->fetch_object()){ $es = "";
      									if(isset($datos)){ if($e->idEstadoCivil == $datos->idEstadoCivil){ $es="selected"; }  } ?>
      									<option value="<?php echo $e->idEstadoCivil;?>" <?php echo $es; ?>><?php echo $e->estadoCivil;?></option>
      									<?php } ?>
      								</select>
      								Correo electronico:
      								<input type="text" id="correo" name="correo" class="form-control" value="<?php if(isset($datos)){ echo $datos->email; }  ?>" />
      							</div>
      							<div class="col-sm-3">                                                         
      								Fecha de <?php if(isset($datos)){ echo $datos->descripcionestatus; }else{ echo 'Alta';}  ?>:<b style="color:red">*</b>
      								<input type="date" id="fechaalta" readonly="readonly" name="fechaalta" class="form-control" value="<?php if(isset($datos)){ echo $datos->fecha_altabajareingreso; }  ?>"/>
      								Apellido Materno:
      								<input type="text" id="materno" name="materno"  class="form-control" value="<?php if(isset($datos)){ echo $datos->apellidoMaterno; }  ?>"/>
      								Sexo:<b style="color:red">*</b>
      								<select id="sexo" name="sexo" class="selectpicker" data-width="100%" data-live-search="true">
      									<?php $sexo1=$sexo2="";
      									if(isset($datos)){ if($datos->idsexo == 1){ $sexo1="selected";$sexo2=""; }else{$sexo2="selected";$sexo1=""; }  } ?>
      									<option value="1" <?php echo $sexo1; ?> >Femenino</option>
      									<option value="2" <?php echo $sexo2; ?> >Masculino</option>
      								</select>
      							</div>
      							<div class="col-sm-3">
      								Apellido Paterno:
      								<input type="text" id="paterno" name="paterno" class="form-control" value="<?php if(isset($datos)){ echo $datos->apellidoPaterno; }  ?>"/>
      								Nombre:<b style="color:red">*</b>
      								<input type="text" id="nombre" name="nombre" class="form-control"  value="<?php if(isset($datos)){ echo $datos->nombreEmpleado; }  ?>"/>
      								Telefono:
      								<input type="ext" id="tel" name="tel" class="form-control" value="<?php if(isset($datos)){ echo $datos->telefono; }  ?>" />
      							</div>

      						</div>
      					</div> 
      				</section>
      				<br>
      				<section>
      					<div class="row">
      						<div class="col-md-3">
                    Salario Diario:<b style="color:red">*</b>
                    <input type="text" id="salario" name="salario"  onkeypress="return NumDec(event,this)" class="form-control"  value="<?php if(isset($datos)){ echo $datos->salarionuevo; }  ?>"/>
                    Forma de pago:<b style="color:red">*</b>
                    <select id="pago" name="pago" class="selectpicker" data-width="100%" data-live-search="true">
                      <?php while ($e = $formapago->fetch_object()){ $f="";
                      if(isset($datos)){ if ($e->idFormapago == $datos->idFormapago ){  $f="selected";} } ?>
                      <option value="<?php echo $e->idFormapago;?>" <?php echo $f; ?>><?php echo $e->nombre." ".$e->claveSat;?> </option>
                      <?php } ?>
                    </select>
                    N.S.S: <b style="color:red">*</b>
                    <input type="text" id="nss" class="solo-numero form-control" placeholder="Ingrese su NSS" maxlength="11"  name="nss"  disabled="true"  value="<?php if(isset($datos)){ echo $datos->nss; }  ?>" />
                    Codigo Postal:
                    <input type="text" id="cp" name="cp" class="form-control"  value="<?php if(isset($datos)){ echo $datos->cp; }  ?>"/>
                  </div>
                  <div class="col-md-3">
                   Fecha de Nacimiento:<b style="color:red">*</b>
                   <input type="date" id="nacimiento" name="nacimiento" readonly="readonly" class="form-control" value="<?php if(isset($datos)){ echo $datos->fechaNacimiento; }  ?>" />
                   Entidad Federativa:<b style="color:red">*</b>
                   <select id="entidad" name="entidad" class="selectpicker" data-width="100%" data-live-search="true" onchange="ciudadbusca();">
                    <?php while ($e = $entidadfed->fetch_object()){ $en="";
                    if(isset($datos)){ if($e->idestado == $datos->idestado){ $en="selected"; } } ?>
                    <option value="<?php echo $e->idestado;?>" <?php echo $en; ?>><?php echo $e->estado." ".$e->clave;?></option>
                    <?php } ?>
                  </select>
                  Ciudad de Nacimiento:<b style="color:red">*</b>
                  <select id="ciudad" name="ciudad" class="selectpicker" data-width="100%" data-live-search="true">
                  </select>
                  Estado:<b style="color:red">*</b>
                  <select id="estado" name="estado" class="selectpicker" data-width="100%" data-live-search="true">
                    <?php while ($e = $estados->fetch_object()){ $esta = "";
                    if(isset($datos)){ if($e->idestado == $datos->idestadosat){ $esta="selected"; } } ?>
                    <option value="<?php echo $e->idestado;?>" <?php echo $esta; ?>><?php echo $e->estado." ".$e->clave;?></option>
                    <?php } ?>
                  </select>
                </div>
                <div class="col-md-3">
                 RFC:<b style="color:red">*</b>
                 <input type="text" id="rfc" name="rfc" placeholder="Ingrese su RFC" onKeyUp="this.value=this.value.toUpperCase();" maxlength="13" class="form-control" value="<?php if(isset($datos)){ echo $datos->rfc; }  ?>"  />
                 CURP:<b style="color:red">*</b>
                 <input type="ext" id="curp" name="curp" placeholder="Ingrese su CURP" maxlength="18" onKeyUp="this.value=this.value.toUpperCase();" class="form-control"  value="<?php if(isset($datos)){ echo $datos->curp; }  ?>" />
                 Direccion:
                 <input type="text" id="direccion" name="direccion" class="form-control" value="<?php if(isset($datos)){ echo $datos->direccion; }  ?>" />
                 Poblacion:
                 <input type="text" id="poblacion" name="poblacion"  class="form-control" value="<?php if(isset($datos)){ echo $datos->poblacion; }  ?>" />
               </div>
               <div class="col-md-3">
                 Tipo cuenta:
                 <select id="tipocuenta" name="tipocuenta" class="selectpicker" data-width="100%" data-live-search="true">
                  <option value="">Seleccione</option>
                  <option value="01">Cheques</option>
                  <option value="03">Tarjeta de Débito</option>
                  <option value="40">Clabe</option>

                </select>
                Banco para pago electronico:<b style="color:red">*</b>
                <select id="banco" name="banco" class="selectpicker" data-width="100%" data-live-search="true">
                  <?php while ($e = $bancos->fetch_object()){$b = "";
                  if(isset($datos)){ if($e->idestado == $datos->idbanco){ $b="selected"; } } ?>
                  <option value="<?php echo $e->idbanco;?>"  <?php echo $b; ?>><?php echo $e->nombre."(".$e->Clave.")";?></option>
                  <?php } ?>
                </select>
                Numero de cuenta para pago:<b style="color:red">*</b>
                <input type="ext" id="numcuenta" name="numcuenta" maxlength="18" class="solo-numero form-control"  value="<?php if(isset($datos)){ echo $datos->numeroCuenta; }  ?>" />
                Clabe interbancaria:<b style="color:red">*</b>
                <input type="ext" id="interbancaria" maxlength="18" minlength="10"  name="interbancaria" class="solo-numero form-control" value="<?php if(isset($datos)){ echo $datos->claveinterbancaria; }  ?>"  />
              </br>
            </br>
          </br>
        </div>
      </div>
    </section>
  </div>
</div>
<!-- Appministra -->
<?php if($Appministra){?>
<div id="app" class="tab-pane fade">
 <br>
 <!--<div class="alert alert-danger">-->
 <div>
  <div class="row">
   <div class="col-md-12">
    <div class="col-xs-3">
     Tipo comision: 
     <select id="tipocomisionapp" name="tipocomisionapp" class="selectpicker" data-width="100%" data-live-search="true">
      <?php $v1=$v2=$v3="";
      if(isset($datos)){
       if($datos->id_tipo_comision == 0 ){
        $v1="selected";$v2=$v3=""; 
      }elseif($datos->id_tipo_comision == 1){
        $v2="selected";$v1=$v3=""; 
      }elseif($datos->id_tipo_comision == 2){
        $v3="selected";$v2=$v3=""; 
      }
    } ?>
    <option value='0' <?php echo $v1;?>>Ninguna</option>
    <option value='1' <?php echo $v2;?>>Venta</option>
    <option value='2' <?php echo $v3;?>>Cobranza</option>
  </select>
</div>
<div class="col-xs-3">
 Comision:
 <input type="ext" id="comisionapp" name="comisionapp" class="form-control" value="<?php if(isset($datos)){ echo $datos->comision; }  ?>" />
</div>
<div class="col-xs-3">
 Clasificacion: 
 <select id="clasificacionapp" name="clasificacionapp" class="selectpicker" data-width="100%" data-live-search="true">
  <option value="0">-Ninguno-</option>
  <?php while($l = $listaClas->fetch_assoc()){ $cla = "";
  if(isset($datos)){ if($l['id'] == $datos->id_clasificacion){ $cla="selected"; } } ?>
  <option value="<?php echo $l['id'];?>" <?php echo $cla;?>><?php echo "(".$l['clave'].") ".$l['nombre'];?></option>
  <?php } ?>
</select>
</div>
<div class="col-xs-3">
 Area empleado: 
 <select id="areaempleadoapp" name="areaempleadoapp" class="selectpicker" data-width="100%" data-live-search="true">
  <?php while($l = $areaempleadoapp->fetch_object()){ $ar = "";
  if(isset($datos)){ if($l->id == $datos->id_area_empleado){ $ar="selected"; } } ?>
  <option value="<?php echo $l->id;?>"  <?php echo $ar;?> ><?php echo $l->nombre;?></option>
  <?php } ?>
</select>
</div>
</div>

</div>
</div>
</div>
<?php } ?>
<!-- fin appministra -->

<!-- N O M I N A S	-->
<?php if($Nominas == 1 || $NominasManual==1){?>
<div id="nomina" class="tab-pane fade">
 <br>
 <!--<div class="alert alert-success">-->
 <div>
  <section>
   <div class="row">
    <div class="col-md-12">
     <div class="col-xs-3">
      Tipo contrato: <b style="color:red">*</b>
      <select id="contrato" name="contrato" onchange="HabilitaRegisPatro()" class="selectpicker" data-width="100%" data-live-search="true" >
       <?php while ($e = $tipocontrato->fetch_object()){$co = "";
       if(isset($datos)){ if($e->idtipocontrato == $datos->idtipocontrato){ $co="selected"; } } ?>
       <option value="<?php echo $e->idtipocontrato;?>" <?php echo $co; ?> ><?php echo $e->descripcion." ".$e->clave;?></option>
       <?php } ?>
     </select>
   </div>
   <div class="col-xs-3">
    Tipo de periodo:
    <select id="periodo" name="periodo" class="selectpicker" data-width="100%" data-live-search="true">
     <option value="0">-Ninguno-</option>
     <?php while ($e = $tipoperiodo->fetch_object()){$pe = "";
     if(isset($datos)){ if($e->idtipop == $datos->idtipop){ $pe="selected"; } } ?>
     <option value="<?php echo $e->idtipop;?>" <?php echo $pe; ?> > <?php echo $e->nombre; ?></option>
     <?php } ?>
   </select>
 </div>
 <div class="col-xs-3">
  Base de cotizacion:
  <select id="cotizacion" name="cotizacion" class="selectpicker" data-width="100%" data-live-search="true">
   <?php while ($e = $basecotizacion->fetch_object()){$ti = "";
   if(isset($datos)){ if($e->idbase == $datos->idbase){ $ti="selected"; } } ?>
   <option value="<?php echo $e->idbase;?>" <?php echo $ti; ?> ><?php echo $e->nombre; ?></option>
   <?php } ?>
 </select>
</div>
<div class="col-xs-3">
  Ayuda Alimentos:
  <input type="text" id="alimento" name="alimento"  onkeypress="return NumDec(event,this)" class="form-control"  value="<?php if(isset($datos)){ echo $datos->alimento; }  ?>"/>
</div>
</div>
</div>
<div class="row">
  <div class="col-md-12">
   <div class="col-xs-3">
    SBC Parte Fija:<b style="color:red">*</b>
    <input type="text" id="sbcfija" name="sbcfija" onkeypress="return NumDec(event,this)" class="form-control" disabled="true"  value="<?php if(isset($datos)){ echo number_format($datos->sbcfijahisto,2,'.',',');}  ?>"   />
  </div>
  <div class="col-xs-3">
    SBC Parte Variable:
    <input type="text" id="sbcvariable" name="sbcvariable" class="form-control"  value="<?php if(isset($datos)){ echo $datos->sbcvariable; }  ?>"  />
  </div>
  <div class="col-xs-3">
    SBC Topado a 25 SMDF:
    <input type="text" id="sbctopado" name="sbctopado" class="form-control"  value="<?php if(isset($datos)){ echo $datos->sbctopado; }  ?>"  />
  </div>
  <div class="col-xs-3">
    Numero FONACOT:
    <input type="text" id="fonacot" name="fonacot" class="form-control"  value="<?php if(isset($datos)){ echo $datos->fonacot; }?>"/>
  </div>
</div>
</div>
<div class="row">
  <div class="col-md-12">
   <div class="col-xs-3">
    Departamento: 
    <select id="departamento" name="departamento" class="selectpicker" data-width="100%" data-live-search="true">
     <option value="0">-Ninguno-</option>
     <?php while ($e = $departamento->fetch_object()){$de = "";
     if(isset($datos)){ if($e->idDep == $datos->idDep){ $de="selected"; } } ?>
     <option value="<?php echo $e->idDep;?>" <?php echo $de; ?>><?php echo $e->nombre; ?></option>
     <?php } ?>
   </select>
 </div>
 <div class="col-xs-3">
  Puesto:<b style="color:red">*</b>
  <select id="puesto" name="puesto" class="selectpicker" data-width="100%" data-live-search="true">
   <option value="0">-Ninguno-</option>
   <?php while ($e = $puesto->fetch_object()){$pu = "";
   if(isset($datos)){ if($e->idPuesto == $datos->idPuesto){ $pu="selected"; } } ?>
   <option value="<?php echo $e->idPuesto;?>" <?php echo $pu;?>><?php echo $e->nombre; ?></option>
   <?php } ?>
 </select>
</div>
<div class="col-xs-3">
  Tipo de empleado:
  <select id="tipoempleado" name="tipoempleado" class="selectpicker" data-width="100%" data-live-search="true">
   <?php while ($e = $tipoEmpleado->fetch_object()){$em = "";
   if(isset($datos)){ if($e->idtipoempleado == $datos->idtipoempleado){ $em="selected"; } } ?>
   <option value="<?php echo $e->idtipoempleado;?>" <?php echo $em;?>><?php echo $e->tipo; ?></option>
   <?php } ?>
 </select>
</div>
<div class="col-xs-3">
  Afore:
  <input type="text" id="afore" name="afore" class="form-control"  value="<?php if(isset($datos)){ echo $datos->afore; } ?>" />
</div>

</div>
</div>
<div class="row">
  <div class="col-md-12">
   <div class="col-xs-3">
    Base de pago: 
    <select id="basepago" name="basepago" class="selectpicker" data-width="100%" data-live-search="true">
     <?php while ($e = $basePago->fetch_object()){$pag = "";
     if(isset($datos)){ if($e->idbasepago == $datos->idbasepago){ $pag="selected"; } } ?>
     <option value="<?php echo $e->idbasepago;?>" <?php echo $pag;?>><?php echo $e->base; ?></option>
     <?php } ?>
   </select>
 </div>
 <div class="col-xs-3">
  Turno de trabajo:
  <select id="turnotrabajo" name="turnotrabajo" class="selectpicker" data-width="100%" data-live-search="true">
   <option value="0">-Ninguno-</option>
   <?php while ($e = $turno->fetch_object()){$tr = "";
   if(isset($datos)){ if($e->idturno == $datos->idturno){ $tr="selected"; } } ?>
   <option value="<?php echo $e->idturno;?>" <?php echo $tr; ?>><?php echo $e->nombre; ?></option>
   <?php } ?>
 </select>
</div>
<div class="col-xs-3">
  Tipo de Regimen: <b style="color:red">*</b>
  <select id="regimen" name="regimen" class="selectpicker" data-width="100%" data-live-search="true">
   <?php while ($e = $regimenContratacion->fetch_object()){$re = "";
   if(isset($datos)){ if($e->idregimencontrato == $datos->idregimencontrato){ $re="selected"; } } ?>
   <option value="<?php echo $e->idregimencontrato;?>" <?php echo $re; ?>><?php echo $e->descripcion." ".$e->clave; ?></option>
   <?php } ?>
 </select>

</div>
<div class="col-xs-3">
  Zona de salario Minimo:
  <select id="zona" name="zona" class="selectpicker" data-width="100%" data-live-search="true">
    <option value="1" selected="">A</option>
  </select>
  
</div>
</div>
</div>
</section>
</div>
</div>
<div id="imss" class="tab-pane fade"><br>
 <!--<div class="alert alert-success">-->
 <div>
  <div class="row">
   <div class="col-md-12">
    <div class="col-xs-3" >
     Registro Patronal:  <b style="color:red">*</b>
     <select id="registropatronal" name="registropatronal" onchange="DerPatro()" class="selectpicker" data-width="100%" data-live-search="true" >
      <option value="0">-Ninguno-</option>
      <?php while ($e = $registroPatronal->fetch_object()){$re = "";
      if(isset($datos)){ if($e->idregistrop == $datos->idregistrop){ $re="selected"; } } ?>
      <option  value="<?php echo $e->idregistrop;?>" <?php echo $re; ?>><?php echo $e->registro; ?></option>
      <?php } ?>
    </select>
  </div>
  <div class="col-xs-3">
   U.M.F:
   <input type="text" id="umf" name="umf" class="form-control"  value="<?php if(isset($datos)){echo $datos->umf; }?>"/>
 </div>
 <div class="col-xs-3">
   <div class="alert alert-danger">
    <strong>Avisos pendientes ante el IMSS </strong> 
    <br>
    <input type="checkbox" name="avisos[]" value="1"/>Alta
    <input type="checkbox" name="avisos[]" value="2"/>Baja
    <input type="checkbox" name="avisos[]" value="3"/>Modif.salario
  </div>
</div>
</div>
</div>
</div>
</div>
<div id="sueldos" class="tab-pane fade">
 <!--<div class="alert alert-success"><strong>Dias y horas en el periodo</strong><br><hr>-->
 <!-- <div><strong>Dias y horas en el periodo</strong><br><hr>
  <div class="row">
   <div class="col-md-12">
    <div class="col-xs-3">
     Horas extras 1:
     <input type="text" id="h1" onkeypress="return NumDec(event,this)" name="h1" class="form-control" value="<?php if(isset($datos)){echo $datos->horasext1; }?>" />
   </div>
   <div class="col-xs-3">
     Horas extras 2:
     <input type="text" id="h2" name="h2" onkeypress="return NumDec(event,this)" class="form-control"  value="<?php if(isset($datos)){echo $datos->horasext2; }?>"/>
   </div>
   <div class="col-xs-3">
     Horas extras 3:
     <input type="text" id="h3" name="h3" class="form-control"  onkeypress="return NumDec(event,this)" value="<?php if(isset($datos)){echo $datos->horasext3; }?>"/>
   </div>
 </div>
</div> -->
<!-- <div class="row">
 <div class="col-md-12">
  <div class="col-xs-3">
   Dias trabajados:
   <input type="text" id="dtrabajados" name="dtrabajados" onkeypress="return NumDec(event,this)" class="form-control" value="<?php if(isset($datos)){echo $datos->diastrabajados; }?>" />
 </div>
 <div class="col-xs-3">
   Dias pagados:
   <input type="text" id="dpagados" name="dpagados" onkeypress="return NumDec(event,this)" class="form-control"  value="<?php if(isset($datos)){echo $datos->diaspagados; }?>"/>
 </div>
 <div class="col-xs-3">
   Dias cotizados:
   <input type="text" id="dcotizados" name="dcotizados" onkeypress="return NumDec(event,this)" class="form-control" value="<?php if(isset($datos)){echo $datos->diascotizados; }?>" />
 </div>
</div>
<div class="col-md-12">
  <div class="col-xs-3">
   Ausencias:
   <input type="text" id="ausencias" name="ausencias" onkeypress="return NumDec(event,this)" class="form-control"  value="<?php if(isset($datos)){echo $datos->ausencias; }?>"/>
 </div>
 <div class="col-xs-3">
   Incapacidades:
   <input type="text" id="incapacidades" name="incapacidades" onkeypress="return NumDec(event,this)" class="form-control" value="<?php if(isset($datos)){echo $datos->incapacidades; }?>" />
 </div>
 <div class="col-xs-3">
   Vacaciones:
   <input type="text" id="vacaciones" name="vacaciones" onkeypress="return NumDec(event,this)" class="form-control"  value="<?php if(isset($datos)){echo $datos->vacaciones; }?>"/>
 </div>
 <div class="col-xs-3">
   Septimos prop.:
   <input type="text" id="septimos" name="septimos" onkeypress="return NumDec(event,this)" class="form-control"  value="<?php if(isset($datos)){echo $datos->septimosprop; }?>"/>
 </div>
</div>
</div> -->
<!-- <br><br>
<strong>Fechas vigentes de salarios</strong><br><hr>
<div class="row">
 <div class="col-md-12">
  <div class="col-xs-3">
   Salario variable:
   <input type="text" id="svariable" name="svariable" onkeypress="return NumDec(event,this)" class="form-control"  value="<?php if(isset($datos)){echo $datos->salariovariable; }?>"/>
 </div>
 <div class="col-xs-3">
   Fecha de salario variable:
   <input type="date" id="fechavariable" name="fechavariable" readonly="readonly" class="form-control"  value="<?php if(isset($datos)){echo $datos->fechavariable; }?>"/>
 </div>
 <div class="col-xs-3">
   Fecha de salario diario:
   <input type="date" id="fechadiario" name="fechadiario" readonly="readonly" class="form-control"  value="<?php if(isset($datos)){echo $datos->fechadiario; }?>"/>
 </div>
</div>
</div>
<div class="row">
 <div class="col-md-12">
  <div class="col-xs-3">
   Salario promedio:
   <input type="text" id="salariopromedio" name="salariopromedio" onkeypress="return NumDec(event,this)" class="form-control"  value="<?php if(isset($datos)){echo $datos->salariopromedio; }?>"/>
 </div>
 <div class="col-xs-3">
   Fecha de salario promedio:
   <input type="date" id="fechapromedio" name="fechapromedio" readonly="readonly" class="form-control"  value="<?php if(isset($datos)){echo $datos->fechapromedio; }?>"/>
 </div>
 <div class="col-xs-3">
   Fecha de salario integrado:
   <input type="date" id="fechaintegrado" name="fechaintegrado" readonly="readonly" class="form-control"  value="<?php if(isset($datos)){echo $datos->fechaintegrado; }?>"/>
 </div>
</div>
</div>
<div class="row">
 <div class="col-md-12">
  <div class="col-xs-3">
   Salario base liquidacion:
   <input type="text" id="salarioliquidacion" name="salarioliquidacion" onkeypress="return NumDec(event,this)" class="form-control"   value="<?php if(isset($datos)){echo $datos->salarioliquidacion; }?>"/>
 </div>
 <div class="col-xs-3">
   Salario del ajuste al neto:
   <input type="text" id="salarioajusteneto" name="salarioajusteneto" onkeypress="return NumDec(event,this)" class="form-control"   value="<?php if(isset($datos)){echo $datos->salarioajusteneto; }?>"/>
 </div> -->
 
 <div class="col-xs-3">
   Salario Neto de empleado por periodo:
   <input type="text" id="sueldoneto" name="sueldoneto" onkeypress="return NumDec(event,this)" class="form-control"   value="<?php if(isset($datos)){echo $datos->sueldoneto; }?>"/>
 </div> 
 <div class="col-xs-3">
   <br>          
   <input type="button" id="sdi" name="sdi" class="form-control" onclick="calculosdi()" value="Calcular SDI" />
 </div>
 <div class="col-xs-3"><br>
 		<label id="sinhuella"><b> El empleado no tiene ninguna huella registrada.</b></label>        
   		<input type="button" id="conhuella" name="conHuella" class="form-control" onclick="borraHuella()" value="Borrar huella de empleado" />
 </div>
</div><!-- 
</div>
</div>
</div> -->



<!-- <div id="pagos" class="tab-pane fade">
<div class="alert alert-success"><strong>Pagos bancarios de nomina</strong><br><hr>
<div class="row">
<div class="col-md-12">
<div class="col-xs-3">
Banco para pago electronico: 
<select id="registropatronal" name="registropatronal" class="selectpicker" data-width="100%" data-live-search="true">

</select>
</div>
<div class="col-xs-3">
Horas extras 2:
<input type="text" id="h2" name="h2" class="form-control"  />
</div>
<div class="col-xs-3">
Horas extras 3:
<input type="text" id="h3" name="h3" class="form-control"  />
</div>
</div>
</div>
</div>
</div> -->

<?php } ?>  
<!--F I N   N O M I N A S	-->
</div>
</div><!--FIN DIV panel-body-->
</div><!--FIN DIV container well-->
<hr>
<!--
<div class="alert alert-info">
<section>
<div class="row">
<div class="col-md-5">
	<br>
	<div class="col-xs-3">
		<button type="button" class="btn btn-primary" id="load"    data-loading-text="<i class='fa fa-refresh fa-spin '></i>"><span class="glyphicon glyphicon-floppy-disk"></span> Guardar</button>
	</div>
</div>
</div>
</section>
</div>
-->
</form>
</div>
</body>
</html>