<?php
header("Content-Type: application/vnd.ms-excel");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=concentradodeivaporproveedor.xls");?>
<!DOCTYPE html>
	<head>
		
	</head>
	
	<body>
		<div id="tabla">
				<b style="font-size:16px; color:#6E6E6E; text-align: center;">&nbsp;Concentrado de IVA por Proveedor.</b><br>
		</br>
		<h2 align="center"  style="font-size:16px; color:#6E6E6E; text-align: center;"><div id="empresa"><?php echo $empresa; ?></div></h2>
		<h3 align="center" style="font-size:16px; color:#6E6E6E; text-align: center;">Concentrado de IVA por Proveedor</h3>
		<h4 id="periodo" align="center" style="font-size:16px; color:#6E6E6E; text-align: center;"><?php echo $periodo; ?></h4>
		<p align="right" style="font-size:16px; color:#6E6E6E; text-align: right;margin-right: 55px;">Fecha : <label id="fech"><?php echo $fecha; ?></label></p>
<?php //echo 'aki'.$body; ?>
	
	<table align='center' class="busqueda" id="datos" width='90%'cellpadding="1" cellspacing="2" >
			<thead>
				<tr class="tit_tabla_buscar">
				<th>Importe Base</th>
				<th>Otros</th>
				<th>Tasa</th>
				<th>IVA Acreditable</th>
				<th>Importe Antes Retenciones</th>
				<th>IVA Retenido</th>
				<th>ISR Retenido</th>
				<th>Total Erogacion</th>
				<th>IVA Pagado no Acreditable</th>
				</tr>
			</thead>
			<tbody><?php 
			$cont=0;
			foreach ($dato as $key => $d) {
				//var_dump($taenviar);
				// print_r($taenviar[ $d['razon_social']]);
				// $ca='index.php?c=auxiliar_controlIva&f=VerReporte&fecha_ini='.$inicio.'&fecha_fin='.$fin.'&periodoAcreditamiento=1&periodo_inicio='.$p1.'&periodo_fin='.$p2.'&pinicial='.$d['idProveedor'].'&pfinal=';
				// $ca.=$d['idProveedor'].'&ejercicio='.$e.'&prov=1&noAplica=0&tasas[]='.$taenviar[ $d['razon_social']];
							// //print_r($taenviar[$d['razon_social']]);  
				
				
				 ?>
			
				
				<tr class="busqueda_fila"><td colspan="9"></td><tr>
				<tr class="prove" >
					<td colspan="3" style="text-align:center;"><?php echo $d['razon_social']; ?></td>
					<td colspan="2" style="text-align:center;">RFC: <?php echo$d['rfc']; ?></td>
					
					<td colspan="2" style="text-align:center;">CURP: <?php echo $d['curp']; ?></td>
					<td colspan="2" style="text-align:center;"><?php echo $d['tipotercero']; ?></td>
				</tr>
			
			<?php	foreach ($d['tasas'] as $key => $value) {
				
						if($value==0 ){  ?>
							<tr class="busqueda_fila">
							<td>0</td>
							<td>0</td>
							<td><?php echo $key; ?></td>
							<td>0</td>
							<td>0</td>
							<td>0</td>
							<td>0</td>
							<td>0</td>
							<td>0</td>
							</tr>
		    <?php 	    }else{
						if($value['tasa']=='Otra Tasa 1' || $value['tasa']=='Otra Tasa 2'){
							$tasa=$value['tasa'].'('.$value['valor'].'%)';
						}else{
							$tasa=$value['tasa'];
						} ?>
               
	
				<tr class="busqueda_fila">
					 <td><?php echo round($value["importeBase"],2);?></td>
					 <td><?php echo round($value["otraserogaciones"],2);?></td>
					 <td><?php echo $tasa;?></td>
					 <td><?php echo round($value['acredita'],2);?></td>
					 <td><?php echo round($value['importeBase']+$value['otraserogaciones']+$value['acredita'],2);?></td>
					 <td><?php echo round($value["ivaRetenido"],2);?></td>
					 <td><?php echo round($value["isrRetenido"],2);?></td>
					 <td><?php echo round($value["totalerogacion"],2);?></td>
					 <td><?php echo round($value["ivaPagadoNoAcreditable"],2);?></td>
					 </tr>
		<?php		}//else
					if($value['tasa']=='Otra Tasa 1'){
						$o1='';
					}else{
						$o1= '
						<tr class="busqueda_fila">
							<td>0</td>
							<td>0</td>
							<td>Otra Tasa 1</td>
							<td>0</td>
							<td>0</td>
							<td>0</td>
							<td>0</td>
							<td>0</td>
							<td>0</td>
						</tr>';
					}
					if($value['tasa']=='Otra Tasa 2'){
						$o2='';
					}else{
						$o2='
						<tr class="busqueda_fila">
							<td>0</td>
							<td>0</td>
							<td>Otra Tasa 2</td>
							<td>0</td>
							<td>0</td>
							<td>0</td>
							<td>0</td>
							<td>0</td>
							<td>0</td>
						</tr>';
					}
				}		
					echo $o1.$o2;	
// 					
					
// 										
					foreach ($d['suma'] as $key => $d3) {
					if($d3!=''){ 
						?>
					<tr>
						 <td colspan="9">
						 <hr>
						 </td>
						 </tr>
						 <tr class="busqueda_fila">
						<?php 
						// $ca='index.php?c=auxiliar_controlIva&f=VerReporte&fecha_ini='.$inicio.'&fecha_fin='.$fin.'&periodoAcreditamiento=1&periodo_inicio='.$p1.'&periodo_fin='.$p2.'&pinicial='.$d['idProveedor'].'&pfinal=';
				// $ca.=$d['idProveedor'].'&ejercicio='.$e.'&prov=1&noAplica=0&tasas[]='.$taenviar[ $d['razon_social']];
				if(!isset($inicio)){
					$inicio=0;
					$fin=0;
					
				}if(!isset($p1)){
					$p1=0;
					$p2=0;
				}
				$t=($taenviar[ $d['razon_social'] ]);
				
						 echo "<td class='tdlink' onclick='javascript:manda(".$inicio.",".$fin.",".$p1.",".$p2.",".$d['idProveedor'].",".$e.",".$periodoAcreditamiento.",".json_encode($t).")'>".round($d3["importeBase"],2)."</td>";
						 ?>
						 <td><?php echo round($d3["otraserogaciones"],2);?></td>
						 <td></td>
						 <td><?php echo round($d3['acredita'],2);?></td>
						 <td><?php echo round($d3['importeBase']+$d3['otraserogaciones']+$d3['acredita'],2);?></td>
						 <td><?php echo round($d3["ivaRetenido"],2);?></td>
						 <td><?php echo round($d3["isrRetenido"],2);?></td>
						 <td><?php echo round($d3["totalerogacion"],2);?></td>
						 <td><?php echo round($d3["ivaPagadoNoAcreditable"],2);?></td>
					 </tr>
	<?php				}
				}
 					 
				
				$cont++;	
					
		}
?>
			</tbody>
		</table>
		</div>
		<div id="r"></div>
		
		
	</body>
	<script>
		function manda(fecha_ini,fecha_fin,periodo_inicio,periodo_fin,pinicial,ejercicio,periodoAcreditamiento,tasas){
			$.post('ajax.php?c=auxiliar_controlIva&f=VerReporte',
			{fecha_ini:fecha_ini,
			 fecha_fin:fecha_fin,
			 periodo_inicio:periodo_inicio,
			 periodo_fin:periodo_fin,
			 pinicial:pinicial,
			 pfinal:pinicial,
			 ejercicio:ejercicio,
			 porProv:'1',
			 noAplica:'0',
			 tasas:tasas,
			 prov:'algunos',
			 periodoAcreditamiento:periodoAcreditamiento
			},function(respues){
			  $("#tabla").hide();
              $("#r").html(respues);
            // $("#r").after('<br><input type="button" onclick="javascript:regre();" value="Regresar">');
			});
		}
		function regre(){
			$("#tabla").show();
			 $("#r").hide();
		}
	</script>
	</html>