<?php
require "controllers/common_father.php";

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
		require('views/principal/principal.php');
	}

	function mainPageFunction()
	{
		//echo "<b style='color:red;'>La función no existe.</b>";
	}
}
?>