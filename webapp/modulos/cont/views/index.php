Este es el contenido
<br />
<?php
	while($a = $empleados->fetch_array())
	{
		echo $a['username']."<br />";	
	}
?>