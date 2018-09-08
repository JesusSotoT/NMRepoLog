<?php
require "../../netwarelog/mvc/controllers/common_father.php";

class Common extends CommonFather
{

	function top()
	{
		//carga la vista que contiene el top
		require('views/partial/top.php');
	}

	function footer()
	{
		//carga la vista que contiene el footer
		require('views/partial/footer.php');
	}

	function mainPageIndex()
	{
		echo "<b style='color:red;'>.</b>";
	}

	function mainPageFunction()
	{
		echo "<b style='color:red;'>La función no existe.</b>";
	}

	function nombre_mes($mes)
	{
		switch($mes)
		{
			case 1 : $nombre = "Enero";break;
			case 2 : $nombre = "Febrero";break;
			case 3 : $nombre = "Marzo";break;
			case 4 : $nombre = "Abril";break;
			case 5 : $nombre = "Mayo";break;
			case 6 : $nombre = "Junio";break;
			case 7 : $nombre = "Julio";break;
			case 8 : $nombre = "Agosto";break;
			case 9 : $nombre = "Septiembre";break;
			case 10 : $nombre = "Octubre";break;
			case 11 : $nombre = "Noviembre";break;
			case 12 : $nombre = "Diciembre";break;
		}
		return $nombre;
	}
}
?>