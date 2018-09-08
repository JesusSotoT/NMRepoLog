<script type="text/javascript" charset="utf-8">
    $(document).ready(function() 
    {
        inicializa_lista_activos();
        $("#responsable,#factura,.cuentas").select2();
        imprime_facturas();
        $('#fecha_depr,#fecha_ultima_depr,#fecha_depr_2,#fecha_ultima_depr_2').datepicker({
                    format: "yyyy-mm-dd",
                    language: "es"
            });
    });
    </script>
    <style>
    	.pestana a
    	{
    		font-weight: bold;

    	}
    	.titulos_pestanas
    	{
			font-weight: bold;
			font-size:20px;    		
    	}

    	.link
    	{
    		margin-top:5px;
    	}

    	.titulos
    	{
    		font-weight: bold;
    	}

    	.input
    	{
    		margin-bottom:5px;
    	}
    </style>
<div class="container well">
	<div class='col-xs-12 col-md-12'>
		<h3>Lista de Activos Fijos</h3>
	</div>
	<div class="col-xs-12 col-md-12 table-responsive">
        <div id='boton_virtual2'><button class='btn btn-primary btn-sm' data-toggle="modal" data-target=".bs-example-modal-lg" onclick="nuevo()">Nuevo <span class='glyphicon glyphicon-plus'></span></button></div>
            <table id="tabla-activos" class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                <thead>
                    <tr><th>No Activo</th><th>Categoria</th><th>Descripcion</th><th>Modelo</th><th>Marca</th><th>Fecha Adq</th><th>MOI</th><th>Ubicacion</th><th>Responsable</th><th>Concepto Alta</th><th>Modificar</th></tr>
               	</thead>
                <tbody id='trs_activos'>
                </tbody>
            </table>
    </div>
</div>

<script src="../../libraries/export_print/jquery.dataTables.min.js"></script>
<script src="../../libraries/export_print/dataTables.buttons.min.js"></script>
<script src="../../libraries/export_print/buttons.html5.min.js"></script>
<script src="../../libraries/export_print/jszip.min.js"></script>
<!--Button Print css -->
<link rel="stylesheet" href="../../libraries/dataTable/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="../../libraries/dataTable/css/buttons.dataTables.min.css">

<!--<script language='javascript' src='../../libraries/dataTable/js/datatables.min.js'></script>-->
<script language='javascript' src='../../libraries/dataTable/js/dataTables.bootstrap.min.js'></script>
<link rel="stylesheet" href="../../libraries/dataTable/css/datatables.min.css">
<link rel="stylesheet" href="../../libraries/dataTable/css/datatablesboot.min.css">
<script language='javascript' src='js/depreciacion.js'></script>
<script language='javascript' src='http://transtatic.com/js/numericInput.min.js'></script>
<script src="../../libraries/select2/dist/js/select2.min.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="../../libraries/select2/dist/css/select2.min.css" />
<link rel="stylesheet" href="https://raw.githubusercontent.com/t0m/select2-bootstrap-css/bootstrap3/select2-bootstrap.css">
<script language='javascript' src='../../libraries/datepicker/js/bootstrap-datepicker.es.js'></script>
<script language='javascript' src='../../libraries/datepicker/js/bootstrap-datepicker.min.js'></script>
<link rel="stylesheet" type="text/css" href="../../libraries/datepicker/css/bootstrap-datepicker.min.css">
<!--AQUI ESTAN LOS MODALS-->
<div class="modal fade bs-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div id='blanco' style='width:300px;height:300px;background-color:white;z-index:1;position:absolute;color:green;'>&nbsp;&nbsp;Cargando...</div>
      <div class="modal-header panel-heading">
                <h4 id="modal-label" class='tit'></h4>
                <input type='hidden' style='width:150px;' id='id_reg'>
            </div>
      
        <div class='col-xs-4 col-md-4 pestana' style='margin-bottom:30px;'>
        	<a href="javascript:pestana('general');" id='general-link' class='link btn'>General</a>
        </div>
        <div class='col-xs-4 col-md-4 pestana' style='margin-bottom:30px;'>
        	<a href="javascript:pestana('contable');" id='contable-link' class='link btn'>Contable</a>
        </div>
        <div class='col-xs-4 col-md-4 pestana' style='margin-bottom:30px;'>
        	<a href="javascript:pestana('fiscal');" id='fiscal-link' class='link btn'>Fiscal</a>
        </div>
        <div class='col-xs-12 col-md-12 pestanas' id='general'>
        	<div class='col-xs-12 col-md-3 titulos' style='margin-bottom:10px;'>
        			<span id='sel_fac'></span>
        	</div>
        	<div class='col-xs-12 col-md-9' style='margin-bottom:10px;'>
        		<select id='factura' class='form-control' style='width:100%;' onchange='datos_factura()'></select>

        	</div>
            <div class='col-xs-12 col-md-9' style='margin-bottom:10px;'>
                <input type='text' id='factura_hidden' fac='0' class='form-control'>
            </div>
        	<div class='col-xs-12 col-md-6'>
                <div class='col-xs-12 col-md-6 titulos' style='margin-bottom:10px;'>
                    Factura
                    <input type='text' id='num_factura' class='form-control'>
                </div>
                <div class='col-xs-12 col-md-6 titulos' style='margin-bottom:10px;'>
                        Proveedor
                        <input type='hidden' id='id_proveedor'><input type='hidden' id='rfc_proveedor'><input type='text' id='proveedor' class='form-control'>
                </div> 
                <div class='col-xs-12 col-md-6 titulos'>
                    Concepto Alta
                </div>
                <div class='col-xs-12 col-md-6 input'>
                    <select id='concepto_alta' class='form-control'>
                        <?php
                            while($la = $lista_altas->fetch_assoc())
                            {
                                echo "<option value='".$la['id']."'>(".$la['codigo'].") ".$la['nombre']."</option>";
                            }
                        ?>
                    </select>
                </div> 
                <div class='col-xs-12 col-md-6 titulos'>
                    Descripcion
                </div>
                <div class='col-xs-12 col-md-6 input'>
                    <input type='text' id='descripcion' class='form-control'>
                </div>  
                <div class='col-xs-12 col-md-6 titulos'>
                    No. Serie
                </div>
                <div class='col-xs-12 col-md-6 input'>
                    <input type='text' id='n_serie' class='form-control'>
                </div>
                <div class='col-xs-12 col-md-6 titulos'>
                    Modelo
                </div>
                <div class='col-xs-12 col-md-6 input'>
                    <input type='text' id='modelo' class='form-control'>
                </div>
                <div class='col-xs-12 col-md-6 titulos'>
                    Marca
                </div>
                <div class='col-xs-12 col-md-6 input'>
                    <input type='text' id='marca' class='form-control'>
                </div>
                <div class='col-xs-12 col-md-6 titulos'>
                    Color
                </div>
                <div class='col-xs-12 col-md-6 input'>
                    <input type='text' id='color' class='form-control'>
                </div>
                <div class='col-xs-12 col-md-6 titulos'>
                    Ubicacion
                </div>
                <div class='col-xs-12 col-md-6 input'>
                    <input type='text' id='ubicacion' class='form-control'>
                </div>
                <div class='col-xs-12 col-md-6 titulos'>
                    Responsable
                </div>
                <div class='col-xs-12 col-md-6 input'>
                    <select id='responsable' class='form-control' style='width:100%;'>
                        <?php
                            while($res = $responsables->fetch_assoc())
                            {
                                echo "<option value='".$res['idEmpleado']."'>(".$res['codigo'].") ".$res['nombreEmpleado']." ".$res['apellidoPaterno']."</option>";
                            }
                        ?>
                    </select>
                </div>
            </div>
            <div class='col-xs-12 col-md-6'>
                <div class='col-xs-12 col-md-6 titulos' style='margin-bottom:10px;'>
                    Fecha Factura
                    <input type='text' id='fecha' class='form-control'>
                </div>
                <div class='col-xs-12 col-md-6 titulos' style='margin-bottom:10px;'>
                        MOI
                        <input type='text' id='moi' class='form-control'>
                </div>
                <div class='col-xs-12 col-md-6 titulos'>
                    No. Activo
                </div>
                <div class='col-xs-12 col-md-6 input'>
                    <input type='text' id='n_activo' class='form-control'>
                </div>
                <div class='col-xs-12 col-md-6 titulos'>
                    Categoria
                </div>
                <div class='col-xs-12 col-md-6 input'>
                    <select id='categoria' class='form-control'>
                        <?php
                            while($cat = $categorias->fetch_assoc())
                            {
                                echo "<option value='".$cat['id']."'>(".$cat['codigo'].") ".$cat['nombre']."</option>";
                            }
                        ?>
                    </select>
                </div>
                <div class='col-xs-12 col-md-6 titulos'>
                    Seg. Negocio
                </div>
                <div class='col-xs-12 col-md-6 input'>
                    <select id='segmento' class='form-control'>
                        <?php
                            while($seg = $segmentos->fetch_assoc())
                            {
                                echo "<option value='".$seg['idSuc']."'>(".$seg['clave'].") ".$seg['nombre']."</option>";
                            }
                        ?>
                    </select>
                </div>
                <div class='col-xs-12 col-md-6 titulos'>
                    Sucursal
                </div>
                <div class='col-xs-12 col-md-6 input'>
                    <select id='sucursal' class='form-control' style='width:100%;'>
                        <?php
                            while($suc = $sucursales->fetch_assoc())
                            {
                                echo "<option value='".$suc['idSuc']."'>(".$suc['clave'].") ".$suc['nombre']."</option>";
                            }
                        ?>
                    </select>
                </div>
                <div class='col-xs-12 col-md-6 titulos'>
                    No. Codigo de Barras
                </div>
                <div class='col-xs-12 col-md-6 input'>
                    <input type='text' id='barras' class='form-control'>
                </div>
                <div class='col-xs-12 col-md-6 titulos'>
                    Cta Contable Asociada
                </div>
                <div class='col-xs-12 col-md-6 input'>
                    <select id='cuenta_asoc' class='form-control cuentas' style='width:100%;'>
                        <option value='0'>Ninguna</option>
                        <?php
                        while($l = $cuentasGral->fetch_assoc())
                            echo "<option value='".$l['account_id']."'>(".$l['manual_code'].") ".$l['description']."</option>";
                        ?>
                    </select>
                    
                </div>

                <div class='col-xs-12 col-md-6 titulos'>
                    Etiqueta codigo
                </div>
                <div class='col-xs-12 col-md-6 input'>
                    <input type='text' id='etiqueta' class='form-control'>
                </div>
                <div class='col-xs-12 col-md-6 titulos'>
                    Estado del Activo
                </div>
                <div class='col-xs-12 col-md-6 input'>
                    <select id='estatus' class='form-control'>
                        <option value='1'>Activo</option>
                        <option value='0'>Baja</option>
                        <option value='2'>Depreciado</option>
                        <option value='3'>No Depreciado</option>
                    </select>
                </div>
            </div>
        </div>
        <div class='col-xs-12 col-md-12 pestanas' id='contable'>
        	<div class='col-xs-12 col-md-12 titulos_pestanas'>
	        	Datos Contables	
        	</div>
        	<div class='col-xs-12 col-md-6'>
	        	<div class='col-xs-12 col-md-6 titulos'>
	        		Fecha Inicio Uso / Depreciacion
		        </div>
		        <div class='col-xs-12 col-md-6 input'>
		        	<input type='text' id='fecha_depr' class='form-control contable_form'>
		        </div>
		        <div class='col-xs-12 col-md-6 titulos'>
	        		MOI
		        </div>
		        <div class='col-xs-12 col-md-6 input'>
		        	<input type='text' id='moi_cont' class='form-control contable_form'>
		        </div>
		        <div class='col-xs-12 col-md-6 titulos'>
	        		Tasa de depreciacion
		        </div>
		        <div class='col-xs-12 col-md-6 input'>
		        	<select id='tasa_depr' class='form-control'>
        			<?php
        				while($l = $tasa_depr->fetch_assoc())
        				{
        					echo "<option value='".$l['id']."'>".$l['nombre']."</option>";
        				}
        			?>
        		</select>
		        </div>
		        <div class='col-xs-12 col-md-6 titulos'>
	        		% Deducible
		        </div>
		        <div class='col-xs-12 col-md-6 input'>
		        	<input type='text' id='porc_deducible' class='form-control contable_form'>
		        </div>
		        <div class='col-xs-12 col-md-6 titulos'>
	        		Fecha Ultima Depreciacion
		        </div>
		        <div class='col-xs-12 col-md-6 input'>
		        	<input type='text' id='fecha_ultima_depr' class='form-control contable_form'>
		        </div>
		        <div class='col-xs-12 col-md-6 titulos'>
	        		Depreciacion Ejercicio
		        </div>
		        <div class='col-xs-12 col-md-6 input'>
		        	<input type='text' id='depr_ejercicio' class='form-control contable_form'>
		        </div>
		        <div class='col-xs-12 col-md-6 titulos'>
	        		Depreciacion Acumulada
		        </div>
		        <div class='col-xs-12 col-md-6 input'>
		        	<input type='text' id='depr_acumulada' class='form-control contable_form'>
		        </div>
		    </div>
	        <div class='col-xs-12 col-md-6'>
		        <div class='col-xs-12 col-md-12 titulos'>
		        	Cuentas para depreciacion contable
		        </div>
	        	<div class='col-xs-12 col-md-6 titulos'>
	        		Activo
		        </div>
		        <div class='col-xs-12 col-md-6 input'>
                     <select id='cuenta_activo' class='form-control contable_form cuentas' style='width:100%;'>
                        <option value='0'>Ninguna</option>
                        <?php
                        while($l = $cuentasAc->fetch_assoc())
                            echo "<option value='".$l['account_id']."'>(".$l['manual_code'].") ".$l['description']."</option>";
                        ?>
                    </select>
		        </div>	
		        <div class='col-xs-12 col-md-6 titulos'>
	        		Resultados
		        </div>
		        <div class='col-xs-12 col-md-6 input'>
                    <select id='cuenta_resultados' class='form-control contable_form cuentas' style='width:100%;'>
                            <option value='0'>Ninguna</option>
                            <?php
                            while($l = $cuentasRes->fetch_assoc())
                                echo "<option value='".$l['account_id']."'>(".$l['manual_code'].") ".$l['description']."</option>";
                            ?>
                    </select>
		        </div>
		        <div class='col-xs-12 col-md-6 titulos'>
	        		No Deducible
		        </div>
		        <div class='col-xs-12 col-md-6 input'>
                    <select id='no_deducible' class='form-control contable_form cuentas' style='width:100%;'>
                            <option value='0'>Ninguna</option>
                            <?php
                            while($l = $cuentasDed->fetch_assoc())
                                echo "<option value='".$l['account_id']."'>(".$l['manual_code'].") ".$l['description']."</option>";
                            ?>
                    </select>
		        </div>
	        </div>
        </div>
        <div class='col-xs-12 col-md-12 pestanas' id='fiscal'>
        	<div class='col-xs-12 col-md-12 titulos_pestanas'>
	        	Datos Fiscales
        	</div>
        	<div class='col-xs-12 col-md-6'>
	        	<div class='col-xs-12 col-md-6 titulos'>
	        		Fecha Inicio Uso / Depreciacion
		        </div>
		        <div class='col-xs-12 col-md-6 input'>
		        	<input type='text' id='fecha_depr_2' class='form-control fiscal_form'>
		        </div>
		        <div class='col-xs-12 col-md-6 titulos'>
	        		Deduccion inmediata
		        </div>
		        <div class='col-xs-12 col-md-6 input'>
		        	<input type='text' id='deduccion_inmediata' class='form-control fiscal_form'>
		        </div>
		        <div class='col-xs-12 col-md-6 titulos'>
	        		Tasa de depreciacion
		        </div>
		        <div class='col-xs-12 col-md-6 input'>
		        	<select id='tasa_depr_2' class='form-control'>
        			<?php
        				while($l = $tasa_depr2->fetch_assoc())
        				{
        					echo "<option value='".$l['id']."'>".$l['nombre']."</option>";
        				}
        			?>
        		</select>
		        </div>
		        <div class='col-xs-12 col-md-6 titulos'>
	        		% Deducible
		        </div>
		        <div class='col-xs-12 col-md-6 input'>
		        	<input type='text' id='porc_deducible_2' class='form-control fiscal_form'>
		        </div>
		        <div class='col-xs-12 col-md-6 titulos'>
	        		Fecha Ultima Depreciacion
		        </div>
		        <div class='col-xs-12 col-md-6 input'>
		        	<input type='text' id='fecha_ultima_depr_2' class='form-control fiscal_form'>
		        </div>
		        <div class='col-xs-12 col-md-6 titulos'>
	        		Depreciacion Ejercicio
		        </div>
		        <div class='col-xs-12 col-md-6 input'>
		        	<input type='text' id='depr_ejercicio_2' class='form-control fiscal_form'>
		        </div>
		        <div class='col-xs-12 col-md-6 titulos'>
	        		Depreciacion Acumulada
		        </div>
		        <div class='col-xs-12 col-md-6 input'>
		        	<input type='text' id='depr_acumulada_2' class='form-control fiscal_form'>
		        </div>
		    </div>
	        <div class='col-xs-12 col-md-6'>
		        <div class='col-xs-12 col-md-12 titulos'>
		        	Cuentas para depreciacion fiscal
		        </div>
	        	<div class='col-xs-12 col-md-6 titulos'>
	        		Orden Acreedora
		        </div>
		        <div class='col-xs-12 col-md-6 input'>
                    <select id='cuenta_orden_acr' class='form-control contable_form cuentas' style='width:100%;'>
                        <option value='0'>Ninguna</option>
                        <?php
                        while($l = $cuentasOrAc->fetch_assoc())
                            echo "<option value='".$l['account_id']."'>(".$l['manual_code'].") ".$l['description']."</option>";
                        ?>
                    </select>
		        </div>	
		        <div class='col-xs-12 col-md-6 titulos'>
	        		Orden Deudora
		        </div>
		        <div class='col-xs-12 col-md-6 input'>
                    <select id='cuenta_orden_deu' class='form-control contable_form cuentas' style='width:100%;'>
                            <option value='0'>Ninguna</option>
                            <?php
                            while($l = $cuentasOrDe->fetch_assoc())
                                echo "<option value='".$l['account_id']."'>(".$l['manual_code'].") ".$l['description']."</option>";
                            ?>
                    </select>
		        </div>
	        </div>
        </div>
      
            <div class="modal-footer">
                <button class='btn btn-default btn-sm' id='guardar' onclick='guardar()'>Guardar</button><button class='btn btn-default btn-sm' onclick='cancelar()'>Cancelar</button>
            </div>      
    </div>
  </div>
</div>