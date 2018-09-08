<style>
	#lista td
	{
		width:146px;
		text-align: center;
		border:1px solid #BDBDBD;
	}

	#buscar
	{
	-webkit-border-radius: 4px;
	-moz-border-radius: 4px;
	-o-border-radius: 4px;
	border-radius: 4px;
	}
	#loading
	{
		background-color:#BDBDBD;
		color:white;
		text-align:center;
		font-weight:bold;
	}
	#Proveedores, #ProveedoresLista {
	    background-color: unset;
	    border: unset;
	    height: unset;
	    width: unset;
	}
</style>

<div id="Proveedores" class="modal fade" tabindex="-1" role="dialog" style="z-index: 1200 !important;">
  	<div class="modal-dialog modal-sm">
    	<div class="modal-content">
      		<div class="modal-header">
        		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        		<h4 class="modal-title">Relacionar Proveedor</h4>
      		</div>
      		<div class="modal-body">
      			<div class="row">
    					<div class="col-md-12">
    						<input type='button' style='border:0px;color:white;width:100%;' value='Cargando datos...' id='loading'>
                <input type='hidden' id='idr'>
                <input type='hidden' id='idx'>
    					</div>
    				</div>
            <div class="row">
              <div class="col-md-12">
                <label>Proveedor:</label> <img src="images/cuentas.png" onclick="iraprovs()" title="Abrir Ventana de Proveedores" style="vertical-align:middle;"> <img style="vertical-align:middle;" title="ActualizarProveedores" onclick="actualizaProveedores()" src="images/reload.png">
                <select name='ProveedoresSelect' id='ProveedoresSelect' onclick='modificaInfoProv(1)'>
                  <option value='0'>---</option>
                  <?php
                  while($Proveedores = $Providers->fetch_assoc())
                  {
                    echo "<option value='".$Proveedores['idPrv']."'>".$Proveedores['razon_social']."</option>";
                  }
                  ?>  
                </select>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <label>Referencia:</label>
                <input type='text' id='referencia' name='referencia' class="form-control">
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <label>Importe:</label>
                <input type='text' id='importe' name='importe' onchange='aritmetica(this);modificaImpuestos()' class="form-control">
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <label>IVA:</label>
                <label id='ivas'></label>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <label>Importe base:</label>
                <input type='text' id='importeBase' name='importeBase' onchange='aritmetica(this);importeIVA(1)' class="form-control">
              </div>
            </div>
            <div class="row">
              <div class="col-md-12" id="acreditaietu" style="display:none">
                <label>Importe Base/Acreditamiento para IETU:</label>
                <input type='text' id='acreietu' name='acreietu' class="form-control">
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <label>Importe IVA:</label>
                <input class="form-control" type='text' id='importeIVA' name='importeIVA' readonly >
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <label>Otras erogaciones:</label>
                <input class="form-control" type='text' id='otrasErogaciones' name='otrasErogaciones' onchange='aritmetica(this);importeAntesRetenciones()'>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <label>Importe antes de retenciones:</label>
                <input class="form-control" type='text' id='importeAntesRetenciones' name='importeAntesRetenciones' readonly>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <label>Retenci&oacute;n IVA:</label>
                (<label id='retivaNum'></label>%)
                <br />
                <input class="form-control" type='text' id='retiva' name='retiva' onchange='aritmetica(this);totalErogacion()'>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <label>Retenci&oacute;n ISR:</label>
                (<label id='retisrNum'></label>%)
                <br />
                <input class="form-control" type='text' id='retisr' name='retisr' onchange='aritmetica(this);totalErogacion()'>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <label>Total erogación:</label>
                <input class="form-control" type='text' id='totalErogacion' name='totalErogacion' readonly >
              </div>
            </div>
    				<div class="row">
              <div class="col-md-12">
                <label>IVA Pagado no acreditable:</label>
                <input class="form-control" type='text' id='IVANoAcreditable' name='IVANoAcreditable' value='0' onchange='aritmetica(this);'>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12" style="display: none" id="ietumuestra">
                <label>Tipo de IETU:</label>
                <select name='ietu' id='ietu' onchange="compruebaid()" class="form-control">
                 <option value='0' >---</option>
                </select>
              </div>
            </div>
    				<div class="row">
              <div class="col-md-12">
                <label>El movimiento aplica para control de IVA:</label>
                <input type='checkbox' id='aplica' value='1' checked >
              </div>
            </div>
        		</div>
        		<div class="modal-footer">
        			<div class="row">
        				<div class="col-md-7">
        				</div>
    					<div class="col-md-5">
    						<button class="btn btn-primary btnMenu" onclick="javascript:gAbreProveedores();">Guardar</button>
    					</div>
    				</div>
      		</div>
      	</div>
    </div>
</div>

<div id="ProveedoresLista" principal-scroll='1' class="modal fade" tabindex="-1" role="dialog" >
  	<div class="modal-dialog">
    	<div class="modal-content">
      		<div class="modal-header">
        		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        		<h4 class="modal-title">Lista de proveedores relacionados</h4>
      		</div>
      		<div class="modal-body">
      			<div class="row">
  					<div class="col-md-12">
  						<div class="table-responsive" id="ProveedoresListaCuerpo">
  						</div>
  					</div>
  				</div>
      		</div>
      		<div class="modal-footer">
      			<div class="row">
      				<div class="col-md-6">
      				</div>
      				<div class="col-md-3">
      					<button class="btn btn-primary btnMenu" onclick="javascript:abreProveedores(0,0);">Nuevo</button>
  					</div>
  					<div class="col-md-3">
  						<button class="btn btn-danger btnMenu" onclick="javascript:$('#ProveedoresLista').modal('hide');">Cerrar</button>
  					</div>
  				</div>
      		</div>
      	</div>
    </div>
</div>

<script>
	function compruebaid(){
		if($('#ietu').val() == 24){
			alert("Solo debe considerar las cuotas tanto de IMSS, Infonavit y SAR que son a cargo del empleador");
		}
	}

  function iraprovs(){
    //window.parent.agregatab('../../modulos/pos/index.php?c=proveedores&f=indexGrid','Proveedores','',2301)
    window.parent.agregatab('../../modulos/punto_venta/catalogos/proveedor.php','Proveedores','',123)
    //window.location='../../modulos/cont/index.php?c=AccountsTree';
}
	
</script>