<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<script src='../../libraries/jquery.min.js' type='text/javascript'></script>
<script language='javascript' src='../../libraries/bootstrap/dist/js/bootstrap.min.js'></script>
<script language='javascript'>
$(function()
{
	$("#cambiar_instancia").hide()
	tipo_inst();
    var sel_v;
    if($("#sel_inst").val() == '')
      sel_v = 0;
    else
      sel_v = $("#sel_inst").val()
    $("#inst_lig").val(sel_v)
});
	function cambia_session()
    {
      $.post("ajax.php?c=Edu&f=cambia_session",
      {
        inst_lig:$("#inst_lig").val()
      },
         function(data)
         {
          console.log("respuesta de cambia_session: "+data)
            if(data)
              location.reload();
            else
              alert($("#inst_lig").val()+" Esta instancia no esta relacionada con su despacho")
         });
    }
	function tipo_inst()
	{
	  //Si no se ha seleccionado ninguno antes, valida el tipo instancia del actual
	  if($("#sel_inst").val()=='')
	  {
	   $.post("ajax.php?c=Edu&f=tipo_inst",
	         function(data)
	         {
	          if(data == 2)
	            $("#inst_lig").show()
	         });
	  }
	  else//Si ya se habia seleccionado uno antes entonces es una instancia principal
	    $("#inst_lig").show()
	}
</script>
<div class="col-xs-12 col-md-4 col-md-offset-4 well" style='font-weight:bold;font-size:16px;'>
	Conectar datos de instancias asociadas.
</div>
<div class="col-xs-12 col-md-4 col-md-offset-4 well">
	Seleccionar instancia: 
	<select id='inst_lig' onchange='cambia_session()' style='display:none;' class='form-control'>
	  <option value='0'>principal</option>
	  <?php
	  while($l = $lista->fetch_assoc())
	  	echo "<option value='".$l['instancia']."'>".$l['instancia']."</option>";
	  ?>
	</select>
	<input type='hidden' id='sel_inst' value='<?php echo $_COOKIE['inst_lig'] ?>'>
</div>
	<?php
		if(intval($_SESSION["accelog_idempleado"]) <=2)
		{
			echo "<div class='col-xs-12 col-md-4 col-md-offset-4 well'>";
			echo "<center><a href='index.php?c=edu&f=rel_user_inst'>Relacionar Usuarios / Instancias</a></center>";
			echo "</div>";
		}
	?>