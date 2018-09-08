<?php

	class Input
	{

		public static function tieneArchivo($archivo)
		{
			$estatus = false;
			if(isset($_FILES[$archivo])){
				@$validacion = getimagesize($_FILES[$archivo]['tmp_name']);
				if($validacion !== false) $estatus = true;
			}
			return $estatus;
		}

		public static function esImagen($archivo)
		{
			$extenciones = array("png", "jpg", "jpeg");
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