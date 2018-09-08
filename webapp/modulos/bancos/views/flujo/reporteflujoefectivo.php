<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8" />
		<script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
		<script src="../../libraries/bootstrap/dist/js/bootstrap.min.js" type="text/javascript"></script>
		<script src="../../libraries/dataTable/js/datatables.min.js" type="text/javascript"></script>
		<script src="../../libraries/dataTable/js/dataTables.bootstrap.min.js" type="text/javascript"></script>
		<link rel="stylesheet" type="text/css" href="../../libraries/bootstrap/dist/css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="../../libraries/dataTable/css/datatablesboot.min.css">
		<script language='javascript' src='../cont/js/pdfmail.js'></script>
		<script src="../cont/js/redirect.js" type="text/javascript" language="javascript" charset="UTF-8"></script>

    
		
	</head>
	<script>
		$(document).ready(function() {
//				$('#global,#global2').DataTable();
			} );
			function generaexcel()
			{
				$("#padre").css("background","#D8D8D8");
				$("#ico").hide();
				$().redirect('../cont/views/fiscal/generaexcel.php', {'cont': $('#imprimible').html(), 'name': $('#titulo').val()});
				$("#ico").show();
				$("#padre").css("background","#848484");
			}
			function antesmail(){
				$("#ico").hide();
				$("#padre").css("background","#D8D8D8");
				mail();
				$("#ico").show();
				$("#padre").css("background","#848484");
			}
			function antespdf(){
				$("#padre").css("background","#D8D8D8");
				$("#ico").hide();
				pdf();
				$("#ico").show();
				$("#padre").css("background","#848484");
			}
	</script>
	<style>
	@media print
{
	#global,#global2
	{
		
	}
	#ico,#filtros,#excel
	{
		display:none;
	}
	#padre{
		background:#D8D8D8;
	}
	
}

</style>
	<body>
	
		<?php
			$url = explode('/modulos',$_SERVER['REQUEST_URI']);
			if($logo == 'logo.png') $logo = 'x.png';
			$logo = str_replace(' ', '%20', $logo);
	?>
		
		<div class="iconos" id="ico">
                <table class="bh" align="right" border="0" >
                    <tr>            
                        <td width=16 align=right>
                            <a href="javascript:window.print();"><img src="../../../webapp/netwarelog/repolog/img/impresora.png" border="0"></a>
                        </td>
                        <td width=16  align=right>
							<a href="index.php?c=Flujo&f=verflujo"> <img src="../../../webapp/netwarelog/repolog/img/filtros.png" 
								title ="Haga clic aqui para cambiar filtros..." border="0"> </a>
						</td>                        
						<td width=16 align=right>
							<a href="javascript:antesmail();"> <img src="../../../webapp/netwarelog/repolog/img/email.png"  
							   title ="Enviar reporte por correo electrónico" border="0"> 
							</a>
						</td>
						<td width=16 align=right>
							<a href="javascript:antespdf();"> <img src="../../../webapp/netwarelog/repolog/img/pdf.gif"  
							   title ="Generar reporte en PDF" border="0"> 
							</a>
						</td>
						<td width=16 align=right>
							<a href='javascript:generaexcel()' id='excel'><img src='images/images.jpg' width='35px'></a>
						</td>																				
                    </tr>
                </table>
            </div><br><br>
 <div id='imprimible'>
<div style="width:90%;background: #848484;" align="center" class="container well" id="padre">	
	<div class="panel panel-default" >
		<div class="panel-heading"  style="height: 170px">
			<h3>Reporte Flujo de Efectivo</h3>
			<?php
				echo "<b>".$banco."</b> Num.Cuenta(".$cuenta.")";
			?><br>
			<b style="font-family: Courier">Del <?php
			echo $_REQUEST['fechainicio'];
			?>
			Al <?php
			echo $_REQUEST['fechafin'];
			?></b><br>
			<?php
				if($detalle==0){
					$nivel="Global";
				}else{
					$nivel="Detalle";
				}
			?>
			<b style="font-size: 16px">Nivel: <?php echo $nivel; ?></b><br>
		<?php
				if($_REQUEST['proyectados']==1){?>
					/<b style="font-family: cursive;font-size: 11px;">Incluidos No Depositados del Periodo</b> /
		<?php	}if($_REQUEST['cobrados']==0){?>
					/<b style="font-family: cursive;font-size: 11px;">Incluidos Cheques No Cobrados</b> /
		<?php	}
			
			?>
		</div> 
			<div class="panel-body" >
				
		<table  width="100%" >
			
			<tbody>
				<tr class="" style="width:120px !important;color: red">
					<td align="right"><b style="font-size: 16px">SALDO INICIAL</b></td>
					<td align="right"><b style="font-size: 16px"><?php echo number_format($saldoInicial,2,'.',','); ?></b></td>
				</tr>
				
			</tbody>
		</table>
		<?php 
			if($detalle==1){ ?>
		<!-- <table class="" width="60%" cellspacing="0" cellpadding="3" border="0">
			<thead>
				<tr>
					<th>Fecha</th>
					<th>Clasificador</th>
					<th>Concepto</th>
					<th>Importe</th>
				</tr>
			</thead>
			<tbody>
				
				<tr>
					<td colspan="4"><b>Documentos Ingresos</b></td>
				</tr>
				<?php 
				foreach($ingresosArray as $index){ 
					foreach($index as $val){?>
				<tr>
					<td><?php echo $val['fecha']; ?></td>
					<td><?php echo $val['clasificador']; ?></td>
					<td><?php echo $val['concepto']; ?></td>
					<td><?php echo $val['importe']; ?></td>
				</tr>
					
				<?php }
					 
				} ?>
				<tr>
					<td colspan="4"><b>Documentos Egresos</b></td>
				</tr>
				<?php 
				foreach($egresosArray as $index){ 
					foreach($index as $val){?>
				<tr>
					<td><?php echo $val['fecha']; ?></td>
					<td><?php echo $val['clasificador']; ?></td>
					<td><?php echo $val['concepto']; ?></td>
					<td><?php echo $val['importe']; ?></td>
				</tr>
					
				<?php }
					 
				} ?>
				
			</tbody>
		</table> -->
	<?php } else{?>
		
		<table class="table table-striped table-bordered"  id="global"  width="100%">
			<thead>
				<tr>
					<td colspan="1"><b>SubClaficador Ingresos</b></td>
					<td></td>
				</tr>

			</thead>
			<tbody>	
				<?php 
				$finingresos = 0;
				foreach($ingresosArray as $val){ $finingresos+=$val['importe']; ?>
				<tr>
					<td><?php echo $val['clasificador']; ?></td>
					<td align="right"><?php echo number_format($val['importe'],2,'.',','); ?></td>
				</tr>
					
				<?php }
					 
				 ?>
			</tbody>
			<tfoot>
				<tr style="">
					
					<td align="left"><b style="">Total de Ingresos</b></td>				
					<td align="right"><b><?php echo number_format($finingresos,2,'.',','); ?></b></td>
				</tr>
			</tfoot>
		</table>
		<table class="table table-striped table-bordered"  id="global2"  width="100%">
			<thead>
				<tr>
					<td colspan="1"><b>SubClasificador Egresos</b></td>
					<td></td>
				</tr>

			</thead>
			<tbody>
				<?php 
				$finegresos = 0;
				foreach($egresosArray as $val){$finegresos+=$val['importe']; ?>
				<tr>
					<td><?php echo $val['clasificador']; ?></td>
					<td align="right"><?php echo number_format($val['importe'],2,'.',','); ?></td>
				</tr>
					
				<?php }
					 
				 ?>
			</tbody>
			<tfoot>
				<tr style="">
					
					<td align="left"><b style="">Total de Egresos</b></td>				
					<td align="right"><b><?php echo number_format($finegresos,2,'.',','); ?></b></td>
				</tr>
			</tfoot>
		</table>
		
	<?php } ?>
	<table width="100%" >
				<tr class="" style="width:120px !important;color: red">
					
					<td align="right"><b style="font-size: 16px">SALDO FINAL</b></td>
					<td align="right"><b style="font-size: 16px"><?php echo number_format($saldoFinal,2,'.',','); ?></b></td>
				</tr>
		</table>	
	</div>
</div>
</div>
</div><!-- imprimible -->

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
				  z-index:1;
				">
				<!--form id="formpdf" action="../../../webapp/netwarelog/repolog/pdf.php" method="post" target="_blank" onsubmit="generar_pdf()"-->
				<form id="formpdf" action="../cont/libraries/pdf/examples/generaPDF.php" method="post" target="_blank" onsubmit="generar_pdf()">
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
				<input type='hidden' name='nombreDocu' value='Flujo de Efectivo'>
				<input type='hidden' value='<?php echo "http://".$_SERVER['SERVER_NAME'].$url[0]."/netwarelog/archivos/1/organizaciones/$logo"; ?>' name='logo' />
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
	</body>
</html>