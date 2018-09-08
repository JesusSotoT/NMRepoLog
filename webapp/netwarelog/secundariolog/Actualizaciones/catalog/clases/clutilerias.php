<?php

 class utilerias{
	
	function getnumero($dato){
		
		$info = str_replace(",","",$dato); 
		
		if($info=="") $info="0";
		
		return $info;
		
	}
				
 }

?>