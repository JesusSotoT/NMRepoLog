<?php

	class Input
	{

		public static function tieneArchivo($archivo)
		{
			$estatus = false;
			if(isset($_FILES[$archivo]) && $_FILES[$archivo]['error'] != 4){
				$estatus = true;
			}
			return $estatus;
		}

		public static function esImagen($archivo)
		{
			if($_FILES[$archivo]['error'] == 4) return true;
			$extenciones = array("png", "jpg", "jpeg", "pdf");
			return in_array(self::extencion($archivo), $extenciones);
		}

		public static function esVideo($archivo)
		{
			$extenciones = array("mov", "mp4");
			return in_array(self::extencion($archivo), $extenciones);
		}

		public static function extencion($archivo)
		{
			return pathinfo($_FILES[$archivo]['name'], PATHINFO_EXTENSION);
		}

	}

?>