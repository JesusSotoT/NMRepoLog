<?php 
$this->load->helper('url');
$base_url=str_replace("modulos/mrp/","",base_url());
?>
<?php include('../../netwarelog/design/css.php');?>
<LINK href="<?php echo $base_url; ?>netwarelog/design/<?php echo $strGNetwarlogCSS;?>/netwarlog.css" title="estilo" rel="stylesheet" type="text/css" /> <!--NETWARLOG CSS-->


<LINK href="<?php echo $base_url; ?>netwarelog/catalog/css/estilo.css" title="estilo" rel="stylesheet" type="text/css" />
<LINK href="<?php echo $base_url; ?>netwarelog/catalog/css/view.css" title="estilo" rel="stylesheet" type="text/css" />
<LINK href="<?php echo base_url(); ?>css/mrp.css" title="estilo" rel="stylesheet" type="text/css" />
 <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script type="text/javascript">var baseUrl='<?php echo base_url(); ?>';</script>	
<script type="text/javascript" src="<?php echo base_url(); ?>js/buy.js"></script>

<table><tr class="listadofila"><td class="campo">
<h2>Selecciona la orden de compra para poder hacer la recepción de mercancia.</h2>

<?php 
echo "<br><br>";
foreach($ordenes_compra as $orden)
{
	echo $orden->elaborado_por."<br>";
}
?>

</td></tr></table>