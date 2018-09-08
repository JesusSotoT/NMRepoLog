<script type="text/JavaScript" src="../js/jquery.js"></script> 	
<script type="java/JavaScript">
	$(document).ready(function(){ 
   		$("#selectdependenciatabla").change(function(){ 
			alert("prueba");
    		//$("#divcmbdescripcion").load("dependencia_form_campos.php?idestructura="+$("cmbdependenciatabla option:selected").value); 
    	}); 
		
 });
</script>


CREAR PROCEDIMIENTO ALMACENADO
------------------------------

DELIMITER $$

DROP PROCEDURE IF EXISTS `netwaremonitor`.`g_app`$$
CREATE PROCEDURE `netwaremonitor`.`g_app` (IN N VARCHAR(50), IN D VARCHAR(50), IN L VARCHAR(50))
BEGIN
	delete from g_app_tabla where nombre=N;
	insert into g_app_tabla values (N, D, L);
END$$

DELIMITER ;


LLAMAR A UN PROCEDIMIENTO ALMACENADO
------------------------------------
CALL g_app('Esta','es','prueba');