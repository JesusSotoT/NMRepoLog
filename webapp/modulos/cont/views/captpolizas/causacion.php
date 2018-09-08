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
</style>

<div id='Causacion' class="modal fade" tabindex="-1" role="dialog">
  	<div class="modal-dialog">
    	<div class="modal-content">
      		<div class="modal-header">
        		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        		<h4 class="modal-title">Desglose de IVA Causado</h4>
      		</div>
      		<div class="modal-body">
      			<input type='hidden' id='idp'>
      			<input type='hidden' id='existe'>
        		<div class="row">
					<div class="col-md-12">
						<div class="table-responsive">          
			  				<table class="table" id='contex'>
			  					<tr  style='background-color:#BDBDBD;color:white;font-weight:bold;'>
									<th width='200' class="nmcatalogbusquedatit">Base Gravable</th>
									<th width='200' class="nmcatalogbusquedatit">Importe Total</th>
									<th width='200' class="nmcatalogbusquedatit">Importe Base</th>
									<th width='200' class="nmcatalogbusquedatit">IVA</th>
									<th width='200' class="nmcatalogbusquedatit">IVA Pagado No Acreditable</th>
								</tr>
								<tr tipo='trtasa' onMouseOver="this.className='over'" onMouseOut="this.className='out'">
									<td>Tasa 16%</td>
									<td>
										<input tabindex='1' type='text' class="nminputtext" id='ImporteTotal16' tipo='numero' onchange="aritmetica(this);modificaImporteTotal('16')">
									</td>
									<td>
										<input tabindex='2' type='text' class="nminputtext" id='ImporteBase16' tipo='numero' onchange='aritmetica(this);recalculaTotales()'>
									</td>
									<td>
										<input tabindex='3' type='text' class="nminputtext" id='IVA16' tipo='numero' onchange='aritmetica(this);recalculaTotales()'>
									</td>
									<td>
										<input type='text' class="nminputtext" id='IVANoAc16' tipo='numero' onchange='aritmetica(this);comparaIVA(16);recalculaTotales()' tabindex='4'>
									</td>
								</tr>
								<tr tipo='trtasa' onMouseOver="this.className='over'" onMouseOut="this.className='out'">
									<td>Tasa 11%</td>
									<td>
										<input tabindex='1' type='text' class="nminputtext" id='ImporteTotal11' tipo='numero' onchange="aritmetica(this);modificaImporteTotal('11')">
									</td>
									<td>
										<input tabindex='2' type='text' class="nminputtext" id='ImporteBase11' tipo='numero' onchange='aritmetica(this);recalculaTotales()'>
									</td>
									<td>
										<input tabindex='3' type='text' class="nminputtext" id='IVA11' tipo='numero' onchange='aritmetica(this);recalculaTotales()'>
									</td>
									<td>
										<input type='text' class="nminputtext" id='IVANoAc11' tipo='numero' onchange='aritmetica(this);comparaIVA(11);recalculaTotales()' tabindex='4'>
									</td>
								</tr>
								<tr tipo='trtasa' onMouseOver="this.className='over'" onMouseOut="this.className='out'">
									<td>Tasa 0%</td><td><input tabindex='1' type='text' class="nminputtext" id='ImporteTotal0' tipo='numero' onchange="aritmetica(this);modificaImporteTotal('0')"></td><td><input tabindex='2' type='text' class="nminputtext" id='ImporteBase0' tipo='numero' onchange='aritmetica(this);recalculaTotales()'></td><td><input type='text' class="nminputtext" tipo='numero' disabled onchange='recalculaTotales()'></td><td><input type='text' class="nminputtext" tipo='numero' disabled onchange='recalculaTotales()'></td>
								</tr>
								<tr tipo='trtasa' onMouseOver="this.className='over'" onMouseOut="this.className='out'">
									<td>Tasa exenta</td><td><input tabindex='1' type='text' class="nminputtext" id='ImporteTotalExenta' tipo='numero' onchange="aritmetica(this);modificaImporteTotal('Exenta')"></td><td><input tabindex='2' type='text' class="nminputtext" id='ImporteBaseExenta' tipo='numero'onchange='aritmetica(this);recalculaTotales()'></td><td><input type='text' class="nminputtext" tipo='numero' disabled onchange='recalculaTotales()'></td><td><input type='text' class="nminputtext" class="nminputtext" tipo='numero' disabled onchange='recalculaTotales()'></td>
								</tr>
								<tr tipo='trtasa' onMouseOver="this.className='over'" onMouseOut="this.className='out'">
									<td>Tasa 15%</td><td><input tabindex='1' type='text' class="nminputtext" id='ImporteTotal15' tipo='numero' onchange="aritmetica(this);modificaImporteTotal('15')"></td><td><input tabindex='2' type='text' class="nminputtext" id='ImporteBase15' tipo='numero' onchange='aritmetica(this);recalculaTotales()'></td><td><input tabindex='3' type='text' class="nminputtext" id='IVA15' tipo='numero' onchange='aritmetica(this);recalculaTotales()'></td><td><input type='text' class="nminputtext" id='IVANoAc15' tipo='numero' onchange='aritmetica(this);comparaIVA(15);recalculaTotales()' tabindex='4'></td>
								</tr>
								<tr tipo='trtasa' onMouseOver="this.className='over'" onMouseOut="this.className='out'">
									<td>Tasa 10%</td><td><input tabindex='1' type='text' class="nminputtext" id='ImporteTotal10' tipo='numero' onchange="aritmetica(this);modificaImporteTotal('10')"></td><td><input tabindex='2' type='text' class="nminputtext" id='ImporteBase10' tipo='numero' onchange='aritmetica(this);recalculaTotales()'></td><td><input tabindex='3' type='text' class="nminputtext" id='IVA10' tipo='numero' onchange='aritmetica(this);recalculaTotales()'></td><td><input type='text' class="nminputtext" id='IVANoAc10' tipo='numero' onchange='aritmetica(this);comparaIVA(10);recalculaTotales()' tabindex='4'></td>
								</tr>
								<tr tipo='trtasa' onMouseOver="this.className='over'" onMouseOut="this.className='out'">
									<td>Otras Tasas</td><td><input tabindex='1' type='text' class="nminputtext" id='ImporteTotalOtras' tipo='numero' onchange="aritmetica(this);modificaImporteTotal('Otras')"></td><td><input tabindex='2' type='text' class="nminputtext" id='ImporteBaseOtras' tipo='numero' onchange='aritmetica(this);recalculaTotales()'></td><td><input tabindex='3' type='text' class="nminputtext" id='IVAOtras' tipo='numero' onchange='aritmetica(this);recalculaTotales()'></td><td><input type='text' class="nminputtext" id='IVANoAcOtras' tipo='numero' onchange="aritmetica(this);comparaIVA('Otras');recalculaTotales()" tabindex='4'></td>
								</tr>
								<tr tipo='trtasa' onMouseOver="this.className='over'" onMouseOut="this.className='out'">
									<td><i style='color:red;'>IVA Retenido</i></td><td><input tabindex='1' type='text' class="nminputtext" class="nminputtext" id='ImporteTotalIvaRetenido' tipo='numero' onchange='aritmetica(this);modificaIvaRetenido()'></td><td><input type='text' class="nminputtext" tipo='numero' onchange='recalculaTotales()' disabled></td><td><input type='text' class="nminputtext" id='IvaIvaRetenido' tipo='numero' onchange='recalculaTotales()' disabled></td><td><input type='text' class="nminputtext" tipo='numero' disabled onchange='recalculaTotales()'></td>
								</tr>
								<tr tipo='trtasa' onMouseOver="this.className='over'" onMouseOut="this.className='out'">
									<td><i style='color:red;'>ISR Retenido</i></td><td><input tabindex='1' type='text' class="nminputtext" class="nminputtext" id='ImporteTotalIsrRetenido' tipo='numero' onchange='aritmetica(this);recalculaTotales()'></td><td><input type='text' class="nminputtext" tipo='numero' onchange='recalculaTotales()' disabled></td><td><input type='text' class="nminputtext" tipo='numero' onchange='recalculaTotales()' disabled></td><td><input type='text' class="nminputtext" class="nminputtext" tipo='numero' disabled onchange='recalculaTotales()'></td>
								</tr>
								<tr tipo='trtasa' onMouseOver="this.className='over'" onMouseOut="this.className='out'">
									<td>Otros</td><td><input tabindex='1' type='text' class="nminputtext" id='ImporteTotalOtros' tipo='numero' onchange='aritmetica(this);recalculaTotales()'></td><td><input type='text' class="nminputtext" tipo='numero' onchange='recalculaTotales()' disabled></td><td><input type='text' class="nminputtext" tipo='numero' onchange='recalculaTotales()' disabled></td><td><input type='text' class="nminputtext" tipo='numero' disabled onchange='recalculaTotales()'></td>
								</tr>
								<tr style='font-weight:bold;'><td>Totales:<input type='hidden' name='totalesImporteTotalHidden' id='totalesImporteTotalHidden'></td><td id='totalesImporteTotal'></td><td id='totalesImporteBase'></td><td id='totalesIVA'></td><td id='totalesIVANoAc'></td></tr>
								<tr><td colspan='2'>Periodo de Causaci&oacute;n:</td><td><select class="nminputselect" tabindex='4' name='periodo_acreditamientoIVA' id='periodo_acreditamientoIVA' ><option value='0'>No tiene</option><option value='1'>Enero</option><option value='2'>Febrero</option><option value='3'>Marzo</option><option value='4'>Abril</option><option value='5'>Mayo</option><option value='6'>Junio</option><option value='7'>Julio</option><option value='8'>Agosto</option><option value='9'>Septiembre</option><option value='10'>Octubre</option><option value='11'>Noviembre</option><option value='12'>Diciembre</option></select></td><td>Ejercicio de Causacion:</td><td>
									<select id='EjerciciosIVA' name='EjerciciosIVA' class="nminputselect" onchange="paraietu();">
									<option value='0'>No tiene</option>
									<?php
									while($Exer = $ExercisesList->fetch_assoc())
									{
										echo "<option value='".$Exer['Id']."'>".$Exer['NombreEjercicio']."</option>";
									}
									?>
									</select></td></tr>
									<TR><TD colspan=2>Relacionar IVA</TD><TD><input class="nminputcheck" tabindex='4' type='checkbox' id='aplicaIVA' value='1' checked ></TD></TR>
								<tfoot style="display: none">
									<tr class="nmcatalogbusquedatit" style="background-color:#6E6E6E">
								
										<td>Acumulable para IETU:</td>
										<td ></td>
										<td><input tabindex='1' type="text" id="ietucli"  class="nminputtext" readonly/></td>
										<td><b>Tipo de IETU:</b></td>
										<td>
											<select id="ietulista" class="nminputselect" style="width:150px" >
												<option value=0 selected></option>
											<?php
											while($ietu = $ietucli->fetch_array())
											{
											echo "<option value='".$ietu['id']."'>".$ietu['nombre']."</option>";
											}
											?>
											</select>
										</td>
										<input type="hidden" id="nombreejer"  />
									</tr>
								</tfoot>
			  				</table>
			  			</div>
					</div>
				</div>
			</div>
      		<div class="modal-footer">
        		<button id="guardar_iva_btn" onclick="javascript:guardarIVACausacion();" type="button" class="btn btn-primary">Agregar movimiento</button>
      		</div>
    	</div>
  	</div>
</div>
