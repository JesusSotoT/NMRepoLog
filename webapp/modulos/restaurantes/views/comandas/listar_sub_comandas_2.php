<div class="row">
	<div class="col-xs-6">
		<h3 class="text-success">$ <?php echo $_SESSION['cerrar_personalizado']['total_comanda'] ?></h3>
	</div>
	<div class="col-xs-6">
		<h3 class="text-danger" id="sub_to">$ <?php echo $_SESSION['cerrar_personalizado']['total_comanda'] ?></h3>
	</div>
</div>
<script type="text/javascript">
	comandas.total_comanda = <?php echo $_SESSION['cerrar_personalizado']['total_comanda'] ?>;
	
</script>