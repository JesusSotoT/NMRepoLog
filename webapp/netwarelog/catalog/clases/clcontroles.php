<?php
	class controles{
	
		var $idcampo_array = array();
		var $linea_array = array();
		var $grabar_array = array();
		var $llave_array = array();		
		//var $tipo_array = array();
		//var $requerido_array = array();		
		
	
		//function agregar($idcampo,$nombrecampo,$linea,$tipo,$requerido){			
		function agregar($idcampo,$nombrecampo,$linea,$grabar,$llave){			
			$this->idcampo_array[$nombrecampo] = $idcampo;
			$this->linea_array[$nombrecampo] = $linea;
			$this->grabar_array[$nombrecampo] = $grabar;
			$this->llave_array[$nombrecampo] = $llave;
			//$this->tipo_array[$nombrecampo] = $tipo;
			//$this->requerido_array[$nombrecampo] = $requerido;
		}
	
		function getlinea($nombrecampo){
			return $this->linea_array[$nombrecampo];
		}
		
		function getgrabar($nombrecampo){
			return $this->grabar_array[$nombrecampo];
		}

		function getllave($nombrecampo){
			return $this->llave_array[$nombrecampo];
		}


		function getidcampo($nombrecampo){
			return $this->idcampo_array[$nombrecampo];
		}
		
		function regresarequerido($nombrecampo,$nombrecampousuario,$control,$valor){
			$validacion= " if(".$control."==".$valor."){
								validado=false;
								alert('Capture el campo ".$nombrecampousuario.".');
								return false;
							}
							";	
			return $validacion;
		}
		
		
		function validafecha($anual,$mes,$dia,$nombrecampousuario){
			$validacion= " 
						  if((".$anual."!='')||(".$mes."!='')||(".$dia."!='')){
							if(!esFecha(".$anual.",".$mes.",".$dia.")){
								validado=false;
								alert('La fecha del campo ".$nombrecampousuario." es inválida.');
								return false;
							}
							}
							";	
			return $validacion;						
		}
		

		function validahora($horas,$ampm,$nombrecampousuario){
			$validacion= " 
							if((".$horas."!='')){
								if(!esHora(".$horas."+' '+".$ampm.")){
									validado=false;
									alert('La hora del campo ".$nombrecampousuario." es inválida.');
									return false;
								}
							}
							";	
			return $validacion;						
		}


		function validahora_hr($horas,$nombrecampousuario){
			$validacion= " 
							if((".$horas."!='')){
								if(!esHora(".$horas.")){
									validado=false;
									alert('La hora del campo ".$nombrecampousuario." es inválida.');
									return false;
								}
							}
							";	
			return $validacion;						
		}

		
		

		function cuantos(){
			return count($this->idcampo_array);
		}
		
		function getcampos(){
			return $this->idcampo_array;
		}

		
		/*
		function gettipo($nombrecampo){
			return $this->tipo_array[$nombrecampo];
		}
		
		function getrequerido($nombrecampo){
			return $this->requerido_array[$nombrecampo];
		}
		*/

	}


/*
	class control{
		
		var $idcampo=0;
		var $nombrecampo="";
		var $linea="";  //Línea script para obtener su valor
		
		function setcontrol($idcampo,$nombrecampo,$linea){
			$this->$idcampo = $idcampo;
			$this->$nombrecampo = $nombrecampo;
			$this->$linea = $linea;
		}
		
		function getidcampo(){ 
			return $this->idcampo;
		}

		function getnombrecampo(){ 
			return $this->nombrecampo;
		}

		//Linea script para obtener su valor
		function getlinea(){ 
			return $this->linea;
		}
	}
	
	class controles{
		
		var $controles_array = array();
		
		function agregar($idcampo,$nombrecampo,$linea){
			//$control = new control();
			//$control->setcontrol($idcampo,$nombrecampo,$linea);
			//$this->controles_array[$nombrecampo] = $control;			
			$this->controles_array[$nombrecampo] = new control();
			$this->controles_array[$nombrecampo]->setcontrol($idcampo,$nombrecampo,$linea);
		}
		
		function getlinea($nombrecampo){
			$control = $this->controles_array[$nombrecampo];
			return $control->getlinea();
		}

		function getidcampo($nombrecampo){
			//$control = $this->controles_array[$nombrecampo];
			//return $control->getlinea();
			return $this->controles_array[$nombrecampo]->getlinea();
		}
	
	}	
*/
	
?>
