<?php
class Common 
{

	function top()
	{
		
		require('views/partial/top.php');
	}

	function footer()
	{
		
		require('views/partial/footer.php');
	}

	
	function content($f)
	{	
		if(isset($f))
		{
			$this->$f();
		}
		else
		{
			$this->mainPage();
		}		
	}


}
?>