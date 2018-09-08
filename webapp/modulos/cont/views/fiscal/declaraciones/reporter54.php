<?php
if($toexcel==1){
header("Content-Type: application/vnd.ms-excel");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=declaracionR54.xls");
}
?>
<!DOCTYPE html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	
	<script type="text/javascript" src="js/jquery.js"></script>
	<script language='javascript' src='js/pdfmail.js'></script>
	<script type="text/javascript" src="js/declaracionr54.js"></script>
<?php if($toexcel==0){ ?>
	<!--LINK href="../../netwarelog/catalog/css/view.css" title="estilo" rel="stylesheet" type="text/css" />	
	<LINK href="../../netwarelog/catalog/css/estilo.css" title="estilo" rel="stylesheet" type="text/css" /-->	
	<link rel="stylesheet" href="css/style.css" type="text/css">
	<style type="text/css">
		
		
		.concepto_r21{width: 350px; font: 13px arial; vertical-align: top;}
		.valor_r21{width: 75px; text-align: right; font: 13px arial; vertical-align: top;}
		.esp_medio{width: 30px;}
		
	</style>
	<?php 
	$titulo_v="background-color:#edeff1;font-weight:bold;text-align:center;height:30px;";
	?>

	<div class="iconos">
	<table>
		<tr>
			<td width="16" align="right">
				<a href="javascript:window.print();">
					<img class="nmwaicons" border="0" src="../../netwarelog/design/default/impresora.png" style="width: 20px;height: 20px">
				</a>
			</td>
			<td width="16" align="right">
				 <a href="javascript:pdf();"><img src="../../../webapp/netwarelog/repolog/img/pdf.gif" title ="Generar reporte en PDF" border="0"></a> 
			</td>
			<td width="16" align="right">
				<a href="javascript:mail();">
				<img border="0" title="Enviar reporte por correo electrónico" src="../../../webapp/netwarelog/repolog/img/email.png">
				</a>
			</td>
			<td width="16" align="right">
				<a id="filtros" onclick="" href="index.php?c=Declaracionr54&f=viewdeclaracion">
					<img border="0" title="Haga click aquí para cambiar los filtros..." src="../../netwarelog/repolog/img/filtros.png">
				</a>
			</td>
			<td width="16" align="right">
				<img src="images/images.jpg" title="Exportar a Excel" onclick="excelreport();" width="25px" height="25px"> 
			</td>
		</tr>
	</table>
</div>

<?php } ?>

</head>
	
	
<body>	

<div class="repTitulo">Declaración R54 Impuesto Empresarial a Tasa Única</div>
<div id='imprimible'>	
	<table width="100%">
		<tr>
			<td width="50%">
				<?php
				$logo=$organizacion->logoempresa;
			$url = explode('/modulos',$_SERVER['REQUEST_URI']);
			if($logo == 'logo.png') $logo = 'x.png';
			$logo = str_replace(' ', '%20', $logo);
			?>
			<!--img id='logo_empresa' src='<?php // echo "http://".$_SERVER['SERVER_NAME'].$url[0]."/netwarelog/archivos/1/organizaciones/$logo"; ?>' height='55'-->
		</td>
			<td valign="top" width='50%' style="font-size:7px;text-align:right;color:gray;">
			<b>Fecha de Impresión<br><?php echo date("d/m/Y H:i:s"); ?></b><br>
			</td>
		</tr>
		<tr style="text-align:center;color:#576370;">
			<td colspan=2>
				<b style="font-size:18px;color:black;"><?php echo $organizacion->nombreorganizacion; ?></b><br>
				<b style="font-size:15px;">Declaracion Provisional o Definitiva de Impuestos Federales</b><br>
				<b style="font-size:15px;"> R54 Impuesto Empresarial a Tasa Única</b><br>
				RFC: <b><?php echo $organizacion->RFC; ?></b><br>
				Ejercicio <b><?php echo $suma->EjercicioActual; ?></b> Período <b><?php echo $meses[$inicio]; ?></b><br><br>
			</td>
		</tr>
	</table>

	<table border="0" align="center" cellpadding="3" style="width:100%;max-width:900px;font-size:10px;" valign="center">
		<tbody>
		<tr style="<?php echo $titulo_v; ?>"><td colspan="5" >IMPUESTO EMPRESARIAL A TASA UNICA         </td></tr>
		<tr>
			<td colspan=2 style="text-align:left;">Indique si es Contribuyente que lleva a cabo Operaciones de Maquila de Conformidad con
				el Decreto para el Fomento de la Industria Manufacturera, Maquiladora y de Servicios de Exportación
			</td>
			<td ></td>
			<td colspan=2>NO</td>
		</tr>
		<tr style="<?php echo $titulo_v; ?>"><td colspan="5" >Determinación del Impuesto</td></tr>
		<tr style="text-align:left;">
			<td width="35%">Suma de ingresos percibidos en meses anteriores del ejercicio</td>
			<td align="right" width="12%"><?php echo number_format($mesesanteriores,2,'.',','); ?></td>
			<td width="6%"></td>
			<td width="35%">Crédito fiscal de inventarios del periodo que declara</td>
			<td align="right" width="12%"><?php echo number_format($inventarios,2,'.',','); ?></td>
		</tr>
		<tr style="text-align:left;">
			<td>Ingresos percibidos en el periodo</td>
			<td align="right"><?php echo number_format($mesactual,2,'.',','); ?></td>
			<td width="6%"></td>
			<td>Crédito fiscal de deducción inmediata / perdidas fiscales del periodo que declara</td>
			<td align="right"><?php echo number_format($inmediataperdida,2,'.',',') ;?></td>
		</tr>
		<tr style="text-align:left;">
			<td>Total de Ingresos percibidos</td>
			<td align="right"><?php echo number_format($totaldeingresospercibidos,2,'.',','); ?></td>
			<td width="6%"></td>
			<td>Crédito fiscal sobre perdidas fiscales (Régimen simplificado) del periodo que declara</td>
			<td align="right"><?php echo number_format($perdidas,2,'.',','); ?></td>
		</tr>
		<tr style="text-align:left;">
			<td>Ingresos por los que no se pagará el impuesto (Exentos)</td>
			<td align="right"><?php echo number_format($exentos,2,'.',','); ?></td>
			<td width="6%"></td>
			<td>Crédito fiscal por enajenación a plazos del periodo que declara</td>
			<td align="right"><?php echo number_format($enajenacion,2,'.',','); ?></td>
		</tr>
		<tr style="text-align:left;">
			<td>Suma de Deducciones autorizadas de meses anteriores</td>
			<td align="right"><?php echo number_format($deduccionesautorizadasanteriores,2,'.',','); ?></td>
			<td width="6%"></td>
			<td>Acreditamiento de pagos provisionales del ISR enterados ante las oficinas autorizadas del periodo que declara</td>
			<td align="right"><?php echo number_format($ISRautorizadas,2,'.',','); ?></td>
		</tr>
		<tr style="text-align:left;">
			<td>Deducciones Autorizadas</td>
			<td align="right"><?php echo number_format($deduccionesautorizadaactual,2,'.',','); ?></td>
			<td width="6%"></td>
			<td>Acreditamiento de pagos provisionales del ISR entregados a la controladora del periodo que declara</td>
			<td align="right"><?php echo number_format($ISRentregados,2,'.',','); ?></td>
		</tr>
		<tr style="text-align:left;">
			<td>Total de Deducciones del periodo</td>
			<td align="right"><?php echo number_format($totaldeduccionesautorizadas,2,'.',','); ?></td>
			<td width="6%"></td>
			<td>Acreditamiento del ISR retenido del periodo que declara</td>
			<td align="right"><?php echo number_format($ISRretenido,2,'.',','); ?></td>
		</tr>
		<tr style="text-align:left;">
			<td>Base Gravable del Pago Provisional</td>
			<td align="right"><?php echo number_format($basegravabledelpagoprovisional,2,'.',','); ?></td>
			<td width="6%"></td>
			<td>Impuesto a Cargo (1a Diferencia)</td>
			<td align="right"><?php echo number_format($impuestocargo1diferencia,2,'.',','); ?></td>
		</tr>
		<tr style="text-align:left;">
			<td>Impuesto causado del periodo que declara</td>
			<td align="right"><?php echo number_format($impuestocausadodelperiodo,2,'.',','); ?></td>
			<td width="6%"></td>
			<td>Acreditamiento para empresas maquiladoras del periodo que declara</td>
			<td align="right"><?php echo number_format($acreditamientomaquiladoras,2,'.',','); ?></td>
		</tr>
		<tr style="text-align:left;">
			<td>Crédito Fiscal por deducciones mayores a los ingresos</td>
			<td align="right"><?php echo number_format($creditodeducmayoringresos,2,'.',','); ?></td>
			<td width="6%"></td>
			<td>Pagos provisionales de IETU efectuados en el periodo anterior</td>
			<td align="right"><?php echo number_format($proviIETU,2,'.',','); ?></td>
		</tr>
		<tr style="text-align:left;">
			<td>Acreditamiento por sueldos y salarios gravados del periodo que declara</td>
			<td align="right"><?php echo number_format($acreditasueldosid23,2,'.',','); ?></td>
			<td width="6%"></td>
			<td>Otras cantidades a cargo del contribuyente del periodo que declara</td>
			<td align="right"><?php echo number_format($cargo,2,'.',','); ?></td>
		</tr>
		<tr style="text-align:left;">
			<td>Acreditamiento por aportaciones de seguridad social patronales del periodo que declara</td>
			<td align="right"><?php echo number_format($acreditamientoaportaciones,2,'.',','); ?></td>
			<td width="6%"></td>
			<td>Otras cantidades a favor del contribuyente del  periodo que declara</td>
			<td align="right"><?php echo number_format($favor,2,'.',','); ?></td>
		</tr>
		<tr style="text-align:left;">
			<td>Crédito fiscal por inversiones (1998 a 2007) del periodo que declara</td>
			<td align="right"><?php echo number_format($inversiones,2,'.',','); ?></td>
			<td width="6%"></td>
			<td>Impuesto a cargo</td>
			<td align="right"><?php echo number_format($impuestocargo,2,'.',','); ?></td>
		</tr>
		<?php 
		if($toexcel == 1){
		?>
		<tr align="center" style="<?php echo $titulo_v; ?>"><td colspan="5" class="subtitulo_r21">Datos Informativos</td></tr>
		<tr style="text-align:left;">
			<td>Parte proporcional del IETU por las actividades de maquila</td>
			<td>0</td>
			<td width="6%"></td>
			<td>Monto total de pagos correspondientes a sueldos y salarios gravados del periodo que declara</td>
			<td>0</td>
		</tr>
		<tr style="text-align:left;">
			<td>Parte Proporcional del ISR Propio por las Actividades de Maquila</td>
			<td>0</td>
			<td width="6%"></td>
			<td>Monto Total de Aportaciones de Seguridad Social Patronales Pagadas del Periodo que Declara</td>
			<td>0</td>
		</tr>
		<tr style="text-align:left;">
			<td>Contraprestaciones que efectivamente se cobren en el Periodo que Declara por las Enajenaciones a Plazo</td>
			<td>0</td>
			<td width="6%"></td>
			<td>Monto total de los pagos provisionales del ISR enterados ante las oficinas autorizadas del periodo que declara</td>
			<td>0</td>
		</tr>
		<tr style="text-align:left;">
			<td>Parte Proporcional del ISR Acreditable Contra IETU del Periodo que Declara</td>
			<td>0</td>
			<td width="6%"></td>
			<td>Monto total de los pagos provisionales del ISR enterados ante las oficinas autorizadas del periodo que declara</td>
			<td>0</td>
		</tr>
		<tr style="text-align:left;">
			<td>Utilidad fiscal para pagos provisionales en las actividades de maquila</td>
			<td>0</td>
			<td width="6%"></td>
			<td>Monto total del ISR retenido del periodo que declara</td>
			<td>0</td>
		</tr>
		<tr align="center" style="<?php echo $titulo_v; ?>"><td colspan="5" class="subtitulo_r21">Determinacion del Pago</td></tr>
		<tr style="text-align:left;">
			<td>A cargo</td>
			<td><?php echo $impuestocargo; ?></td>
			<td width="6%"></td>
			<td>¿Usted realizó en las últimas 48 horas un pago para este concepto?</td>
			<td>NO</td>
		</tr>
		<tr style="text-align:left;">
			<td>Parte actualizada</td>
			<td>0</td>
			<td width="6%"></td>
			<td>Importe pagado en las últimas 48 horas</td>
			<td>0</td>
		</tr>
		<tr style="text-align:left;">
			<td>Recargos</td>
			<td>0</td>
			<td width="6%"></td>
			<td>Cantidad a cargo</td>
			<td><?php echo $impuestocargo; ?></td>
		</tr>
		<tr style="text-align:left;">
			<td>Multa por corrección</td>
			<td>0</td>
			<td width="6%"></td>
			<td>¿Aplica primera parcialidad?</td>
			<td>NO</td>
		</tr>
		<tr style="text-align:left;">
			<td>Total de contribuciones</td>
			<td><?php echo $impuestocargo; ?></td>
			<td width="6%"></td>
			<td>Importe de la 1ra. Parcialidad</td>
			<td>0</td>
		</tr>
		<tr style="text-align:left;">
			<td>Compensaciones</td>
			<td>0</td>
			<td width="6%"></td>
			<td>Importe sin la 1ra. Parcialidad</td>
			<td>0</td>
		</tr>
		<tr style="text-align:left;">
			<td>Total de aplicaciones</td>
			<td>0</td>
			<td width="6%"></td>
			<td>Cantidad a favor</td>
			<td>0</td>
		</tr>
		<tr style="text-align:left;">
			<td>Fecha del pago realizado con anterioridad</td>
			<td>0</td>
			<td width="6%"></td>
			<td>Cantidad a pagar</td>
		 	<td><?php echo $impuestocargo; ?></td>
		</tr>
		<tr style="text-align:left;">
			<td width="6%"></td>
			<td>Monto pagado con anterioridad</td>
			<td>0</td>
			
		</tr>
	<?php } ?>
	</tbody>
	</table>
	</div>
	<input type="hidden" id="ejercicio" value="<?php echo $ejercicio ; ?>">
	<input type="hidden" id="delperiodo" value="<?php echo $inicio ; ?>">
	<input type="hidden" id="maquilaselect" value="<?php echo $maquilaselect ; ?>">
	<input type="hidden" id="inversiones" value="<?php echo $inversiones ; ?>">
	<input type="hidden" id="inventarios" value="<?php echo $inventarios ; ?>">
	<input type="hidden" id="inmediataperdida" value="<?php echo $inmediataperdida ; ?>">
	<input type="hidden" id="perdidas" value="<?php echo $perdidas ; ?>">
	<input type="hidden" id="enajenacion" value="<?php echo $enajenacion ; ?>">
	<input type="hidden" id="ISRautorizadas" value="<?php echo $ISRautorizadas ; ?>">
	<input type="hidden" id="ISRentregados" value="<?php echo $ISRentregados ; ?>">
	<input type="hidden" id="ISRretenido" value="<?php echo $ISRretenido ; ?>">
	<input type="hidden" id="maquiladoras" value="<?php echo $acreditamientomaquiladoras ; ?>">
	<input type="hidden" id="proviIETU" value="<?php echo $proviIETU ; ?>">
	<input type="hidden" id="cargo" value="<?php echo $cargo ; ?>">
	<input type="hidden" id="favor" value="<?php echo $favor ; ?>">
<?php if($toexcel==0){ ?>

<div id="divpanelpdf"
				style="
					position: absolute; top:30%; left: 40%;
					opacity:0.9;
					padding: 20px;
					-webkit-border-radius: 20px;
    			border-radius: 10px;
					background-color:#000;
					color:white;
				  display:none;	
				">
				<form id="formpdf" action="libraries/pdf/examples/generaPDF.php" method="post" target="_blank" onsubmit="generar_pdf()">
					<center>
					<b> Generar PDF </b>
					<br><br>

					<table style="border:none;">
						<tbody>
							<tr>
								<td style="color:white;font-size:13px;">Escala:</td>
								<td style="color:white;font-size:13px;">
									<select id="cmbescala" name="cmbescala">
									<option value=100>100</option>
<option value=99>99</option>
<option value=98>98</option>
<option value=97>97</option>
<option value=96>96</option>
<option value=95>95</option>
<option value=94>94</option>
<option value=93>93</option>
<option value=92>92</option>
<option value=91>91</option>
<option value=90>90</option>
<option value=89>89</option>
<option value=88>88</option>
<option value=87>87</option>
<option value=86>86</option>
<option value=85>85</option>
<option value=84>84</option>
<option value=83>83</option>
<option value=82>82</option>
<option value=81>81</option>
<option value=80>80</option>
<option value=79>79</option>
<option value=78>78</option>
<option value=77>77</option>
<option value=76>76</option>
<option value=75>75</option>
<option value=74>74</option>
<option value=73>73</option>
<option value=72>72</option>
<option value=71>71</option>
<option value=70>70</option>
<option value=69>69</option>
<option value=68>68</option>
<option value=67>67</option>
<option value=66>66</option>
<option value=65>65</option>
<option value=64>64</option>
<option value=63>63</option>
<option value=62>62</option>
<option value=61>61</option>
<option value=60>60</option>
<option value=59>59</option>
<option value=58>58</option>
<option value=57>57</option>
<option value=56>56</option>
<option value=55>55</option>
<option value=54>54</option>
<option value=53>53</option>
<option value=52>52</option>
<option value=51>51</option>
<option value=50>50</option>
<option value=49>49</option>
<option value=48>48</option>
<option value=47>47</option>
<option value=46>46</option>
<option value=45>45</option>
<option value=44>44</option>
<option value=43>43</option>
<option value=42>42</option>
<option value=41>41</option>
<option value=40>40</option>
<option value=39>39</option>
<option value=38>38</option>
<option value=37>37</option>
<option value=36>36</option>
<option value=35>35</option>
<option value=34>34</option>
<option value=33>33</option>
<option value=32>32</option>
<option value=31>31</option>
<option value=30>30</option>
<option value=29>29</option>
<option value=28>28</option>
<option value=27>27</option>
<option value=26>26</option>
<option value=25>25</option>
<option value=24>24</option>
<option value=23>23</option>
<option value=22>22</option>
<option value=21>21</option>
<option value=20>20</option>
<option value=19>19</option>
<option value=18>18</option>
<option value=17>17</option>
<option value=16>16</option>
<option value=15>15</option>
<option value=14>14</option>
<option value=13>13</option>
<option value=12>12</option>
<option value=11>11</option>
<option value=10>10</option>
<option value=9>9</option>
<option value=8>8</option>
<option value=7>7</option>
<option value=6>6</option>
<option value=5>5</option>
<option value=4>4</option>
<option value=3>3</option>
<option value=2>2</option>
<option value=1>1</option>
									</select> %
								</td>
							</tr>
							<tr>
								<td style="color:white;font-size:13px;">Orientación:</td>
								<td style="color:white;">
									<select id="cmborientacion" name="cmborientacion">
										<option value='P'>Vertical</option>
										<option value='L'>Horizontal</option>
									</select>
								</td>
							</tr>
					</tbody>
				</table>
				<br>
					
				<textarea id="contenido" name="contenido" style="display:none"></textarea>
				<input type='hidden' name='tipoDocu' value='hg'>
				<input type='hidden' value='<?php echo "http://".$_SERVER['SERVER_NAME'].$url[0]."/netwarelog/archivos/1/organizaciones/$logo"; ?>' name='logo' />
				<input type='hidden' name='nombreDocu' value='ReporteR54'>
				<input type="submit" value="Crear PDF" autofocus >
				<input type="button" value="Cancelar" onclick="cancelar_pdf()">
				
				</center>
				</form>
			</div>
			<div id="loading" style="position: absolute; top:30%; left: 50%;display:none;">
			<div 
				id="divmsg"
				style="
					opacity:0.8;
					position:relative;
					background-color:#000;
					color:white;
					padding: 20px;
					-webkit-border-radius: 20px;
    				border-radius: 10px;
					left:-50%;
					top:-30%
				">
				<center><img src='../../../webapp/netwarelog/repolog/img/loading-black.gif' width='50'><br>Cargando...
				</center>
			</div>
			</div>
			<script>
				function cerrarloading(){
					$("#loading").fadeOut(0);
					var divloading="<center><img src='../../../webapp/netwarelog/repolog/img/loading-black.gif' width='50'><br>Cargando...</center>";
					$("#divmsg").html(divloading);
				}
			</script>
	<?php } ?>

</body>
</html>