<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="js/redirect.js" type="text/javascript" language="javascript" charset="UTF-8"></script>
<script language='javascript'>
$(document).ready(function()
{

	$('#nmloader_div',window.parent.document).hide();	
	var totalMayores = $("#totalMayores").val();
	for(i=1;i<=totalMayores;i++)
	{
		$("#m"+i).html($("#mt"+i).html());
	}

	for(i=1;i<=4;i++)
	{
		$("#FT-"+i).html($("#F-"+i).html());
		$("#F-"+i).remove()
	}
	$(".mayorTotal").remove();
	if($("#tipoVista").val() == '1')
	{
		$(".mayor").remove();
		$("#nivel").text('Afectables');
	}

	if($("#tipoVista").val() == '2')
	{
		$(".afectables").remove();
		$("#cuentaCuentas").text(totalMayores);
		$("#nivel").text('Mayor');
	}
});
function generaexcel()
			{
				$().redirect('views/fiscal/generaexcel.php', {'cont': $('#imprimible').html(), 'name': $('#titulo').val()});
			}
</script>
<script language='javascript' src='js/pdfmail.js'></script>
<link rel="stylesheet" href="css/style.css" type="text/css">
<!--LINK href="../../netwarelog/catalog/css/estilo.css" title="estilo" rel="stylesheet" type="text/css" /-->
<style>
.tit_tabla_buscar td
{
	font-size:medium;
}

#logo_empresa /*Logo en pdf*/
	{
		display:none;
	}

.clasemayor
{
	color:white;
	background-color:gray;
}
#titulo_impresion
{
	visibility: hidden;
}
#sc
{
	overflow:scroll;
}
@media print
{
	#imprimir,#filtros,#excel,#titulo,#email_icon
	{
		display:none;
	}
	#titulo_impresion
	{
		visibility:visible;
	}
	#sc
	{
		overflow: visible;
	}

	#logo_empresa
	{
		display:block;
	}
}
</style>
<?php
$moneda=$_POST['moneda'];
if($_POST['valMon']){$valMon=$_POST['valMon'];}else{$valMon=1;}
//$valMon=13.5;
ini_set('memory_limit', '-1');
?>	

<div class='iconos'  style='margin-left:10px;margin-bottom:10px;'><a href="javascript:window.print();" id='imprimir'><img class="nmwaicons" src="../../netwarelog/design/default/impresora.png" border="0" title='Imprimir'></a><a href='javascript:generaexcel()' id='excel'><img src='images/images.jpg' width='35px'></a>   <a href="javascript:pdf();"><img src="../../../webapp/netwarelog/repolog/img/pdf.gif" title ="Generar reporte en PDF" border="0"></a>   <a href="javascript:mail();"><img src="../../../webapp/netwarelog/repolog/img/email.png" title ="Enviar reporte por correo electrónico" border="0"></a>
	<a href='index.php?c=reports&f=balanzaComprobacion' onclick="javascript:$('#nmloader_div',window.parent.document).show();" id='filtros' onclick="javascript:$('#nmloader_div',window.parent.document).hide();"><img src="../../netwarelog/repolog/img/filtros.png"  border="0" title='Haga click aqu&iacute; para cambiar los filtros...'></a></div>
<div class="repTitulo">Balanza de Comprobación*</div>

<input type='hidden' value='Balanza de Comprobacion.' id='titulo'>
<div id='imprimible'>
	<table width='100%'>
		<tr>
			<td width="50%">
				<?php
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
		<tr style="color:#576370;text-align:center;">
			<td colspan=2>
				<b style="font-size:18px;color:black;"><?php echo $empresa; ?></b><br>
				<b style="font-size:15px;">Balanza de Comprobación </b><br>   
				Ejercicio <b><?php echo $ej; ?></b> Periodo De <b><?php echo $fecIni; ?></b> A <b><?php echo $fecFin; ?></b><br>
				A nivel de <span id='nivel' style='font-weight:bold;'>Todos</span> No. de Cuentas: <b><span id='cuentaCuentas'><?php echo $n_cuentas;?></span></b>
				<?php if($valMon>1){echo "<br>Moneda <b>$moneda</b> Tipo de Cambio <b>$ $valMon </b> ";}?>
				<br><br>
			</td>
		</tr>
	</table>
<!--table border='0' style='width:100%;'>
	
	<tr class='tit_tabla_buscar' id='titulo' style='background-color:#edeff1;font-size:10px;'>
		<td style='width:9%'>Codigo</td>
		<td style='width:13%'>Nombre</td>
		<td style='width:13%'>Saldo Inicial Deudor</td>
		<td style='width:13%'>Saldo Inicial Acreedor</td>
		<td style='width:13%'>Cargos</td>
		<td style='width:13%'>Abonos</td>
		<td style='width:13%'>Saldo Final Deudor</td>
		<td style='width:13%'>Saldo Final Acreedor</td>
	</tr>
</table-->
<table border='0' align='center' cellpadding=3 style='width:100%;max-width:950px;font-size:9px;'>
	<thead>
	<tr style='background-color:#e4e7ea;font-size:10px;font-weight:bold;height:30px;text-align:center;'>
		<td style='width:8%'>Código</td>
		<td style='width:20%'>Nombre</td>
		<td style='width:12%'>Saldo Inicial Deudor</td>
		<td style='width:12%'>Saldo Inicial Acreedor</td>
		<td style='width:12%'>Cargos</td>
		<td style='width:12%'>Abonos</td>
		<td style='width:12%'>Saldo Final Deudor</td>
		<td style='width:12%'>Saldo Final Acreedor</td>
	</tr>
	</head>
	<?php
	$Familia = 0;
	$Mayor = 'xx';

	$SaldoDeudorInicialTotal = 0;
	$SaldoAcreedorInicialTotal = 0;
	$CargosTotal = 0;
	$AbonosTotal = 0;
	$SaldoDeudorFinalTotal = 0;
	$SaldoAcreedorFinalTotal = 0;
	$contMayor = 0;
	
	while($d = $datos->fetch_object())
	{

		
		if($Mayor != $d->Cuenta_de_Mayor)
		{
			$Mayor = str_replace(')', '', $Mayor);
			$Mayor = explode("(",$Mayor);
			if($contMayor != 0) echo "<tr style='color:white;background-color:gray;' class='mayorTotal' id='mt$contMayor' ><td>".$Mayor[1]."</td><td>".$Mayor[0]."</td><td style='text-align:right;'>$".number_format($SaldoDeudorInicialMayor,2)."</td><td style='text-align:right;'>$".number_format($SaldoAcreedorInicialMayor,2)."</td><td style='text-align:right;'>$".number_format($CargosMayor,2)."</td><td style='text-align:right;'>$".number_format($AbonosMayor,2)."</td><td style='text-align:right;'>$".number_format($SaldoDeudorFinalMayor,2)."</td><td style='text-align:right;'>$".number_format($SaldoAcreedorFinalMayor,2)."</td></tr>";
			$contMayor++;
			if($d->Familia != $Familia)
			{
				switch($Familia)
				{
					case 1 : $f = 'ACTIVO';break;
					case 2 : $f = 'PASIVO';break;
					case 3 : $f = 'CAPITAL';break;
					case 4 : $f = 'RESULTADOS';break;
				}
				if(intval($Familia))
				{
					echo "<tr id='F-$Familia'><td></td><td>$f</td><td style='text-align:right;'>$".number_format($SaldoDeudorInicialFam,2)."</td><td style='text-align:right;'>$".number_format($SaldoAcreedorInicialFam,2)."</td><td style='text-align:right;'>$".number_format($CargosFam,2)."</td><td style='text-align:right;'>$".number_format($AbonosFam,2)."</td><td style='text-align:right;'>$".number_format($SaldoDeudorFinalFam,2)."</td><td style='text-align:right;'>$".number_format($SaldoAcreedorFinalFam,2)."</td></tr>";
				}
				
				$SaldoDeudorInicialFam = 0;
				$SaldoAcreedorInicialFam = 0;
				$CargosFam = 0;
				$AbonosFam = 0;
				$SaldoDeudorFinalFam = 0;
				$SaldoAcreedorFinalFam = 0;
				switch($d->Familia)
				{
					case 1 : $f = 'ACTIVO';break;
					case 2 : $f = 'PASIVO';break;
					case 3 : $f = 'CAPITAL';break;
					case 4 : $f = 'RESULTADOS';break;
				}
				echo "<tr style='color:black;background-color:#edeff1;height:30px;font-weight:bold;text-align:left;' id='FT-$d->Familia'></tr>";


			}
			if($tipoVista==2)
			{
				if ($contMayor%2==0)//Si el contador es par pinta esto en la fila del grid
				{
    				$color2='#ffffff';
				}
				else//Si es impar pinta esto
				{
    				$color2='#fafafa';
				}
			}
			else
			{
				$color2="#f6f7f8";
				$bold="font-weight: bold;";
			}
			echo "<tr class=' mayor' style='$bold height:30px;text-align:left;background-color:$color2;' id='m$contMayor'><td></td><td>".$d->Cuenta_de_Mayor."</td><td></td>
			<td></td><td></td><td></td><td></td><td></td></tr>";
			$SaldoDeudorInicialMayor = 0;
			$SaldoAcreedorInicialMayor = 0;
			$CargosMayor = 0;
			$AbonosMayor = 0;
			$SaldoDeudorFinalMayor = 0;
			$SaldoAcreedorFinalMayor = 0;
		}

		if($d->Naturaleza == 'DEUDORA')
		{
			$SaldoAcreedorInicial = 0;
			$SaldoDeudorInicial = floatval($d->CargosAntes) - floatval($d->AbonosAntes);
			$SaldoDeudorInicial = $SaldoDeudorInicial/$valMon;
			$SaldoAcreedorFinal = 0;
			$SaldoDeudorFinal = $SaldoDeudorInicial + floatval($d->Cargos) - floatval($d->Abonos);
			$SaldoDeudorFinal = $SaldoDeudorFinal/$valMon;
		}
		if($d->Naturaleza == 'ACREEDORA')
		{
			$SaldoAcreedorInicial = floatval($d->AbonosAntes) - floatval($d->CargosAntes);
			$SaldoAcreedorInicial = $SaldoAcreedorInicial/$valMon;
			$SaldoDeudorInicial = 0;
			$SaldoAcreedorFinal = $SaldoAcreedorInicial + floatval($d->Abonos) - floatval($d->Cargos);
			$SaldoAcreedorFinal = $SaldoAcreedorFinal/$valMon;
			$SaldoDeudorFinal = 0;
		}

		if ($cont%2==0)//Si el contador es par pinta esto en la fila del grid
		{
    		$color='#ffffff';
		}
		else//Si es impar pinta esto
		{
    		$color='#fafafa';
		}
		$Account = str_replace(')', '', $d->Cuenta);
		$Account = explode('(',$Account);
		echo "<tr class='afectables' style='height: 30px !important;text-align:left;background-color:$color;'>
				<td>$Account[1]</td>
				<td>$Account[0]</td>
				<td style='text-align:right;' title='Saldo Deudor Inicial'>".number_format($SaldoDeudorInicial,2)."</td>
				<td style='text-align:right;' title='Saldo Acreedor Inicial'>".number_format($SaldoAcreedorInicial,2)."</td>
				<td style='text-align:right;' title='Cargos'>".number_format(($d->Cargos/$valMon),2)."</td>
				<td style='text-align:right;' title='Abonos'>".number_format(($d->Abonos/$valMon),2)."</td>
				<td style='text-align:right;' title='Saldo Deudor Final'>".number_format($SaldoDeudorFinal,2)."</td>
				<td style='text-align:right;' title='Saldo Acreedor Final'>".number_format($SaldoAcreedorFinal,2)."</td></tr>";


		$cont++;//Incrementa contador
		$Familia = $d->Familia;//Guarda Familia Actual
		$Mayor = $d->Cuenta_de_Mayor;//Guarda Cuenta de Mayor Actual

		//Guarda Sumatorias Mayor
		$SaldoDeudorInicialMayor += $SaldoDeudorInicial;
		$SaldoAcreedorInicialMayor += $SaldoAcreedorInicial;
		$CargosMayor += $d->Cargos/$valMon;
		$AbonosMayor += $d->Abonos/$valMon;
		$SaldoDeudorFinalMayor += $SaldoDeudorFinal;
		$SaldoAcreedorFinalMayor += $SaldoAcreedorFinal;

		//Guarda Sumatorias Familia
		$SaldoDeudorInicialFam += $SaldoDeudorInicial;
		$SaldoAcreedorInicialFam += $SaldoAcreedorInicial;
		$CargosFam += $d->Cargos/$valMon;
		$AbonosFam += $d->Abonos/$valMon;
		$SaldoDeudorFinalFam += $SaldoDeudorFinal;
		$SaldoAcreedorFinalFam += $SaldoAcreedorFinal;

		//Guarda Sumatorias Totales
		$SaldoDeudorInicialTotal += $SaldoDeudorInicial;
		$SaldoAcreedorInicialTotal += $SaldoAcreedorInicial;
		$CargosTotal += $d->Cargos/$valMon;
		$AbonosTotal += $d->Abonos/$valMon;
		$SaldoDeudorFinalTotal += $SaldoDeudorFinal;
		$SaldoAcreedorFinalTotal += $SaldoAcreedorFinal;

	}
	$Mayor = str_replace(')', '', $Mayor);
	$Mayor = explode("(",$Mayor);
	echo "<tr style='text-align:left;background-color:#f6f7f8;' class='mayorTotal' id='mt$contMayor'><td>".$Mayor[1]."</td><td>".$Mayor[0]."</td><td style='text-align:right;'>$".number_format($SaldoDeudorInicialMayor,2)."</td><td style='text-align:right;'>$".number_format($SaldoAcreedorInicialMayor,2)."</td><td style='text-align:right;'>$".number_format($CargosMayor,2)."</td><td style='text-align:right;'>$".number_format($AbonosMayor,2)."</td><td style='text-align:right;'>$".number_format($SaldoDeudorFinalMayor,2)."</td><td style='text-align:right;'>$".number_format($SaldoAcreedorFinalMayor,2)."</td></tr>";
	switch($Familia)
				{
					case 1 : $f = 'ACTIVO';break;
					case 2 : $f = 'PASIVO';break;
					case 3 : $f = 'CAPITAL';break;
					case 4 : $f = 'RESULTADOS';break;
				}
	echo "<tr id='F-$Familia'><td></td><td>$f</td><td style='text-align:right;'>$".number_format($SaldoDeudorInicialFam,2)."</td><td style='text-align:right;'>$".number_format($SaldoAcreedorInicialFam,2)."</td><td style='text-align:right;'>$".number_format($CargosFam,2)."</td><td style='text-align:right;'>$".number_format($AbonosFam,2)."</td><td style='text-align:right;'>$".number_format($SaldoDeudorFinalFam,2)."</td><td style='text-align:right;'>$".number_format($SaldoAcreedorFinalFam,2)."</td></tr>";
	echo "<tr style='font-size:10px;font-weight:bold;text-align:right;color:black;background-color:#e4e7ea;height:30px;' id='totales'><td></td><td></td><td>$".number_format($SaldoDeudorInicialTotal,2)."</td><td>$".number_format($SaldoAcreedorInicialTotal,2)."</td><td>$".number_format($CargosTotal,2)."</td><td>$".number_format($AbonosTotal,2)."</td><td>$".number_format($SaldoDeudorFinalTotal,2)."</td><td>$".number_format($SaldoAcreedorFinalTotal,2)."</td></tr>";
	?>
</table>

<input type='hidden' id='totalMayores' value='<?php echo $contMayor; ?>'>
<input type='hidden' id='tipoVista' value='<?php echo $tipoVista; ?>'>
</div>
<!--GENERA PDF*************************************************-->
<div id="divpanelpdf"
				style="
					position: absolute; top:200px; left: 40%;
					opacity:0.9;
					padding: 20px;
					-webkit-border-radius: 20px;
    			border-radius: 10px;
					background-color:#000;
					color:white;
				  display:none;	
				  z-index:1;
				">
					<form id="formpdf" action="libraries/pdf/examples/generaPDF.php" method="post" target="_blank" onsubmit="generar_pdf()">
				<!--form id="formpdf" action="../../../webapp/netwarelog/repolog/pdf.php" method="post" target="_blank" onsubmit="generar_pdf()">-->
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
				<input type='hidden' name='nombreDocu' value='Balanza de Comprobacion'>
				<input type="submit" value="Crear PDF" autofocus >
				<input type="button" value="Cancelar" onclick="cancelar_pdf()">
				
				</center>
				</form>
			</div>
<!--GENERA PDF*************************************************-->
<!-- MAIL -->
			<div id="loading" style="position: absolute; top:30%; left: 50%;display:none;z-index:2;">
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
					top:-200px
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