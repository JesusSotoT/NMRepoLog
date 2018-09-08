<!-- <input type="text" id="barcode"> -->
	<div class="input-group col-sm-4">
	    <div class="input-group-addon"> 
			<i class="fa fa-barcode" aria-hidden="true"></i> 
		</div>
	    <input type="text" class="form-control" id="barcode" placeholder="Código">
	</div>
	<table id="tablaInventario" class="table table-striped table-bordered sizeprint" style="width: 100%">
			<thead>
				<tr>
					<th style="width: 40%;"> Producto </th>
					<th> Cantidad Actual </th>
					<th> Cantidad Contada </th>
					<th> Diferencia </th>
			  	</tr>
			</thead>
			<tbody>
				<?php 
				foreach ($valorInventario as $key => $value) { 
				?>
					<tr id="<?php echo $value['id_producto'] ?>"
						class="p_<?php echo $value['codigo'] ?>" 
						caracteristicas="<?php echo  ( $this->caract2id($value['id_producto_caracteristica']) )  ?>" 
						lote="<?php echo $value['lote'] ?>" 
						no_lote="<?php echo ($value['lote']) ? $value['no_lote'] : ""; ?>"
						series='<?php
								echo  ($value['series'] != "0")  
								? $this->obtenerSeries( $value['id_producto'] , $value['id_producto_caracteristica'] )
								: "[]" ;
							?>'
						ajusteseries="0"
						costo="<?php echo $value['costo_promedio'] ?>">
						<td > 
							 <?php 
							 	echo ($value['lote']) ? "{ ".$value['no_lote']." } " : "";
							 	echo $value['producto']; 
							 	echo ($value['id_producto_caracteristica'] != '\'0\'') ? "[".( $this->caract2nombre($caracteristicas,$value['id_producto_caracteristica']) )."]" : "";
							 ?> 
						</td>

						<td style="text-align: right;">
							<?php echo $value['c'] ?>	
						</td>

						<td style="text-align: right;">
							<input type="text" value="0" class="form-control" style="text-align: right;">
						</td>

						<td style="text-align: right;">
							<input type="text" value="<?php echo -$value['c'] ?>" class="form-control" disabled style="text-align: right;">
						</td>
					</tr>
				<?php
				}
				?>
			</tbody>
			<tfoot>
				<tr>
					<td colspan="3"></td>
					<td colspan="1"><button id="realizarAjusteExistencias" class="btn btn-default" style="width: 100%">Realizar ajuste</button></td>
				</tr>
			</tfoot>
		</table>
