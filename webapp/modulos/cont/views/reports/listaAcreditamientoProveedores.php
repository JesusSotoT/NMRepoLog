<script src="../cont/js/jquery-1.10.2.min.js"></script>
<script language='javascript'>
function guardaAcreditamiento()
{
	var str = '';
	$(".chbx:checked").each(function(index)
		{
				
				if(index == 0)//Si es el primer o solo es un registro se agrega a la cadena sin coma
					{
						str = $(this).val()	
					}
					else
					{
						str += "," + $(this).val();
					}
		});
		if(str)
		{
			$.post("ajax.php?c=Reports&f=actAcreditamiento",
      		{
        		Ids: str,
        		Periodo: $('#periodo').val(),
        		Ejercicio: $('#ejercicio').val(),
        		Tipo: 'cont_rel_pol_prov'
      		},
      		function(data)
      		{
        		if(parseInt(data))
        		{
        			location.reload();//Actualiza
        			//alert(data)
        		}
        		else
        		{
        			alert('Error, la operacion no se pudo guardar.')
        		}
      		});
			
		}
		else
		{
			alert('Debes seleccionar una poliza para agregar el acreditamiento.')
		}
}
function todos()
{
	if($("#all").is(':checked'))
	{
		$(".chbx").click();
		
	}
	else
	{
		$(".chbx").removeAttr('checked');
	
	}
}
</script>
<LINK href="../../netwarelog/catalog/css/estilo.css" title="estilo" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="css/imprimir_bootstrap.css" type="text/css">
<style>
	.tit_tabla_buscar td
	{
		font-size:12px;
		height:30px;
	}
	.btnMenu{
      	border-radius: 0; 
      	width: 100%;
      	margin-bottom: 0.3em;
      	margin-top: 0.3em;
  	}
  	.row
  	{
      	margin-top: 0.5em !important;
  	}
  	h4, h3{
      	background-color: #eee;
      	padding: 0.4em;
  	}
  	.modal-title{
  		background-color: unset !important;
  		padding: unset !important;
  	}
  	.nmwatitles, [id="title"] {
      	padding: 8px 0 3px !important;
     	background-color: unset !important;
  	}
  	.select2-container{
      	width: 100% !important;
  	}
  	.select2-container .select2-choice{
      	background-image: unset !important;
     	height: 31px !important;
  	}
  	.twitter-typeahead{
  		width: 100% !important;
  	}
  	.tablaResponsiva{
        max-width: 100vw !important; 
        display: inline-block;
    }
</style>

<div class="container">
	<div class="row">
		<div class="col-md-1">
		</div>
		<div class="col-md-10">
			<h3 class="nmwatitles text-center">Lista de Acreditacion Proveedores / <a href='index.php?c=reports&f=listaAcreditamientoDesglose'>Ir a Desglose de IVA</a></h3>
			<div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12 tablaResponsiva">
					<div class="table-responsive">
						<table class="table" cellspacing=0 cellpadding=0 id='resultados'>
							<tr>
								<td colspan=5></td>
								<td>
									<select name='periodo' id='periodo' class="form-control">
										<option value='--'>Periodo</option>
										<option value='0'>Ninguno</option>
										<option value='1'>Enero</option>
										<option value='2'>Febrero</option>
										<option value='3'>Marzo</option>
										<option value='4'>Abril</option>
										<option value='5'>Mayo</option>
										<option value='6'>Junio</option>
										<option value='7'>Julio</option>
										<option value='8'>Agosto</option>
										<option value='9'>Septiembre</option>
										<option value='10'>Octubre</option>
										<option value='11'>Noviembre</option>
										<option value='12'>Diciembre</option>
									</select>
								</td>
								<td>
									<select name='ejercicio' id='ejercicio' class="form-control">
										<option value='--'>Ejercicio</option>
										<option value='0'>Ninguno</option>
										<?php
											while($e = $ejercicios->fetch_assoc())
											{
												echo "<option value='".$e['Id']."'>".$e['NombreEjercicio']."</option>";
											}
										?>
									</select>
								</td>
							</tr>
							<tr class='tit_tabla_buscar'>
								
								<td class="nmcatalogbusquedatit" style=" border-left: 2px solid;text-align: center;" width=50># Poliza</td>
								<td class="nmcatalogbusquedatit" style=" border-left: 2px solid;text-align: center;" width=50>Periodo</td>
								<td class="nmcatalogbusquedatit" style=" border-left: 2px solid;text-align: center;" width=50>Ejercicio</td>
								<td class="nmcatalogbusquedatit" style=" border-left: 2px solid;text-align: center;" width=250>Concepto</td>
								<td class="nmcatalogbusquedatit" style=" border-left: 2px solid;text-align: center;" width=150>Importes</td>
								<td class="nmcatalogbusquedatit" style=" border-left: 2px solid;text-align: center;" width=150>Periodo Acreditamiento</td>
								<td class="nmcatalogbusquedatit" style=" border-left: 2px solid;text-align: center;" width=150>Ejercicio Acreditamiento</td>
								<td class="nmcatalogbusquedatit" style=" border-left: 2px solid;text-align: center;" width=10><input type='checkbox' id='all' onclick='todos()' class='nminputcheck'> Todos</td>
							</tr>
							<?php
							while($p = $polizas->fetch_assoc())
							{
								if ($cont%2==0)//Si el contador es par pinta esto en la fila del grid
									{
							    		$color='nmcatalogbusquedacont_1';
									}
									else//Si es impar pinta esto
									{
							    		$color='nmcatalogbusquedacont_2';
									}
								echo "<tr class='$color'>";
								echo "<td>".$p['numpol']."</td><td>".$p['idperiodo']."</td><td>".$p['Ejercicio']."</td><td>".$p['concepto']."</td><td>".$p['Importes']."</td><td>".$p['periodoAcreditamiento']."</td><td>".$p['ejercicioAcreditamiento']."</td><td><input class='nminputcheck chbx' type='checkbox' value='".$p['idPoliza']."'></td>";
								echo "</tr>";
								$cont++;//Incrementa contador
							}
							?>	
							<tr>
									<td colspan=6></td>
									<td><input type='button' name='guardar' id='guardar' class="btn btn-primary btnMenu" value='Actualizar' onclick='guardaAcreditamiento()'></td>
							</tr>
						</table>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-1">
		</div>
	</div>
</div>
