<?php
 require('common.php');

require("models/configuracion.php");

class Configuracion extends Common
{
	public $ConfiguracionModel;
	
	function __construct()
	{
		$this->ConfiguracionModel = new ConfiguracionModel();
		$this->ConfiguracionModel->connect();
	}

	function __destruct()
	{
		$this->ConfiguracionModel->close();
	}
	function configuracion(){
		$acontia = $this->ConfiguracionModel->validaAcontia();
		$configuracionBancos = $this->ConfiguracionModel->configuracionBancos();
		if($acontia){
			$configuracionAcontia = $this->ConfiguracionModel->configuracionAcontia();
			$ejercicioAcontia = $this->ConfiguracionModel->ejercicioAcontia();
		}else{
			$ejercicioBancos = $this->ConfiguracionModel->ejercicioBancos();
		}
		require('views/configuracion/configuracion.php');
	}
	function guardaConfig(){
		if(!$_REQUEST['polizaAu']){ $_REQUEST['polizaAu']=0;}
		if(!$_REQUEST['periodosabiertos']){ $_REQUEST['periodosabiertos']=0;}
		$conf=0;
		if($_REQUEST['acontiaconf']){
			$conf = $this->ConfiguracionModel->insertConf('', 0, 0, 0, $_REQUEST['polizaAu'], 1);
			
		}else{
			$ejer = $this->ConfiguracionModel->insertaEjercicio($_REQUEST['ejercicio']);
			if($ejer){
				$conf = $this->ConfiguracionModel->insertConf($_REQUEST['rfc'], $_REQUEST['periodosabiertos'], $_REQUEST['vigente'], $_REQUEST['ejercicio'], $_REQUEST['polizaAu'], 0);
			}
			
		}
		if($conf==1){
			echo "<script> alert('Informacion Almacenada'); window.location='../../modulos/bancos/index.php?c=Configuracion&f=configuracion';</script>";
		}else{
			echo "<script> alert('Error de almacenamiento intente de nuevo.'); window.location='../../modulos/bancos/index.php?c=Configuracion&f=configuracion';</script>";
		}
	}
	function passAdmin()
	{
		echo $this->ConfiguracionModel->passAdmin($_POST['Pass']);
	}
	function reiniciar(){
		echo $this->ConfiguracionModel->reiniciar();
	}
	function updateConfiguracion(){
		echo $this->ConfiguracionModel->updateConfiguracion($_REQUEST['rfc'], $_REQUEST['vigente'],$_REQUEST['polizaAu'], $_REQUEST['periodosabiertos']);
	}
	function updatepolizaAuto(){
		echo $this->ConfiguracionModel->updatepolizaAuto($_REQUEST['polizaAu']);
	}
}
?>