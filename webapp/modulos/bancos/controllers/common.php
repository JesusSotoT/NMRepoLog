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

	function path($ruta=null)
        {
            if(is_null($ruta))
                $path = "";
            else
                $path = $ruta;
            
            if(isset($_COOKIE['inst_lig']))
                $path = "../../../../".$_COOKIE['inst_lig']."/webapp/modulos/cont/";
            return $path;
        }


}
?>