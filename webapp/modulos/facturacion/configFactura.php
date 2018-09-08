<?php 

include "../../netwarelog/webconfig.php";
include "../../netwarelog/catalog/conexionbd.php";

class configFactura{
	function configFactura($conexion_enviada){
        $this->conexion=$conexion_enviada;
    }
	
	function loadData(){	
		$result = $this->conexion->consultar("select rfc, razonsocial from configFactura");
		if($rs = $this->conexion->siguiente($result)){
			$content="<div>Datos Actuales:</div>";
			$content.="<div style='color:#ff0000;font-size:14px' id='divrfc'>".$rs{'rfc'}."</div>";
			$content.="<div style='color:#ff0000;font-size:14px' id='divrazon'>".$rs{'razonsocial'}."</div>";
			return $content;
		}else
			return "";
	}			
}

?>
<script type="text/javascript" src="../posclasico/js/jquery-1.7.2.min.js"></script>
<script>

	function validaRfc(rfcStr) {
		var strCorrecta;
		strCorrecta = rfcStr;	
		if (rfcStr.length == 12){
			var valid = '^(([A-Z]|[a-z]){3})([0-9]{6})((([A-Z]|[a-z]|[0-9]){3}))';
		}else{
			var valid = '^(([A-Z]|[a-z]|\s){1})(([A-Z]|[a-z]){3})([0-9]{6})((([A-Z]|[a-z]|[0-9]){3}))';
		}
		var validRfc=new RegExp(valid);
		var matchArray=strCorrecta.match(validRfc);
		if (matchArray==null) {
			return 0;
		}
		else
		{
			return 1;
		}	
	}
	
	function configSaveRFC(){

		if($('#rfc').val()!="" && $('#razonsocial').val()!=""){
			if(validaRfc($('#rfc').val())==0){
				alert("El RFC es incorrecto!!")
				return false;
			}
		    $.ajax({
	            data:  {id:0, accion:"configSaveRfc", rfc:$('#rfc').val().toUpperCase(), razon:$('#razonsocial').val().toUpperCase()},
	            url:   'getInfoFacturas.php',
	            type:  'post',
	            success:  function (response) {
	            	$('#divrfc').html($('#rfc').val().toUpperCase())
	            	$('#divrazon').html($('#razonsocial').val().toUpperCase())
	            	$('#rfc').val("")
	            	$('#razonsocial').val("")
					alert("Datos cambiados correctamente!!");
	            }
	        });
		}
		else
			alert("Porfavor llene todos los campos!!")
	}
</script>

<div>
	<?php
		$menus = new configFactura($conexion); 
		echo $menus->loadData();
	?>
</div>

<div>
	<table>
		<tr>
			<td style="font-family:Verdana, Geneva, Arial, Helvetica, sans-serif;font-size:13px;display: inline-block;margin-top:10px;text-align: left;line-height: 2.3;">RFC:</td>
			<td><input type="text" style="width:100px;padding: 3px;background-color:#f2f2f2;" id="rfc"/></td>
		</tr>
		<tr>
			<td style="font-family:Verdana, Geneva, Arial, Helvetica, sans-serif;font-size:13px;display: inline-block;margin-top:10px;text-align: left;line-height: 2.3;">RazonSocial:</td>
			<td><input type="text" style="width:100px;padding: 3px;background-color:#f2f2f2;" id="razonsocial"/></td>
		</tr>
		<tr>
			<td></td>
			<td><input type="button" name="submit" value="Enviar" id="btnff" class="submit_button float_right" onclick="configSaveRFC()" style="padding: 5px;color: #fff;background-color: #91C313;border: 2px solid #ddd;padding: 5px;color: #fff;background-color: #91C313;border: 2px solid #ddd;"></td>
		</tr>
	</table>
</div>