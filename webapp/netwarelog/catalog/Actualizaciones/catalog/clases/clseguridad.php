<?php

class usuario{
	var $ticket;
	
	//SETTES
	function setticket($ticket){ $this->ticket = $ticket; }

	//GETTES
	function getticket($ticket){ return $this->ticket; }
	
	function getagregar(){
		return -1;
	}
	function getmodificar(){
		return -1;
	}
	function geteliminar(){
		return -1;
	}
	
}	
	
?>