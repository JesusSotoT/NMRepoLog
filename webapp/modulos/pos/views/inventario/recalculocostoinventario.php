
	<table id="tablaInventario" class="table table-striped table-bordered sizeprint" style="width: 100%">
			<thead>
				<tr>
					<th style="width: 40%;"> Producto </th>
					<th> Costo Actual </th>
					<th> Costo Esperado </th>
					<th> Diferencia </th>
			  	</tr>
			</thead>
			<tbody>
				<?php 
				foreach ($valorInventario as $key => $value) { 

				?>
					<!-- <tr id="<?php echo $value['id_producto'] ?>" caracteristicas="<?php echo $value['id_producto_caracteristica'] ?>" lote="<?php echo $value['lote'] ?>" series="" cantidad="<?php echo $value['c'] ?>">
						<td > 
							 <?php 
							 	echo ($value['lote']) ? "{".$value['lote']."}" : "";
							 	echo $value['producto']; 
							 	echo ($value['id_producto_caracteristica'] != '\'0\'') ? "[".( $this->caract2nombre($caracteristicas,$value['id_producto_caracteristica']) )."]" : "";
							 ?> 
						</td>

						<td style="text-align: right;">
							<?php echo $value['costo_promedio'] ?>	
						</td>

						<td style="text-align: right;">
							<input type="text" value="<?php echo $value['costo_promedio'] ?>" class="form-control" style="text-align: right;">
						</td>

						<td style="text-align: right;">
							<input type="text" value="0" class="form-control" disabled style="text-align: right;">
						</td>
					</tr> -->




					<tr id="<?php echo $value['id_producto'] ?>"
						class="p_<?php echo $value['codigo'] ?>" 
						caracteristicas="<?php echo  ( $value['id_producto_caracteristica'] )  ?>" 
						lote="<?php echo "0" ?>" 
						series='<?php
								echo "[]" ;
							?>'
						ajusteseries="0"
						cantidad="<?php echo $value['c'] ?>">
						<td > 
							 <?php 
							 	echo ($value['lote']) ? "{ ".$value['no_lote']." } " : "";
							 	echo $value['producto']; 
							 	echo ($value['id_producto_caracteristica'] != '\'0\'') ? "[".( $this->caract2nombre($caracteristicas,$value['id_producto_caracteristica']) )."]" : "";
							 ?> 
						</td>

						<td style="text-align: right;">
							<?php echo $value['costo_promedio'] ?>	
						</td>

						<td style="text-align: right;">
							<input type="text" value="<?php echo $value['costo_promedio'] ?>" class="form-control" style="text-align: right;">
						</td>

						<td style="text-align: right;">
							<input type="text" value="0" class="form-control" disabled style="text-align: right;">
						</td>
					</tr>
				<?php

				}
				?>
			</tbody>
			<tfoot>
				<tr>
					<td colspan="3" class="danger">
						<p> Nota: Tenga cuidado de no tener diferencias menores a 0.0001 ya que podria ocacionar algún error en el cálculo. </p>
					</td>
					<td colspan="1"><button id="realizarAjusteCostos" class="btn btn-default" style="width: 100%">Realizar ajuste</button></td>
				</tr>
			</tfoot>
		</table>
