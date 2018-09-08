<?php

class documento{
	var $iddocumento=-1;
	var $nombredocumento;
	var $observaciones;
	var $fechacreacion;
	var $fechamodificacion;
	var $utilizaidorganizacion;
	var $linkantes;
	var $linkdespues;
	var $idestructuratitulo;
	var $columnas=0;
	var $detalles=0;
	
	
	//SETTES
	function setiddocumento($d){ $this->iddocumento = $d; }
	function setnombredocumento($d){ $this->nombredocumento = $d; }	
	function setobservaciones($d){ $this->observaciones = $d; }	
	function setfechacreacion($d){ $this->fechacreacion = $d; }	
	function setfechamodificacion($d){ $this->fechamodificacion = $d; }	
	function setutilizaidorganizacion($d){ $this->utilizaidorganizacion = $d; }	
	function setlinkantes($d){ $this->linkantes = $d; }	
	function setlinkdespues($d){ $this->linkdespues = $d; }	
	function setidestructuratitulo($d){ $this->idestructuratitulo = $d; }	
	function setcolumnas($d){ $this->columnas = $d; }	
	function setdetalles($d){ $this->detalles = $d; }
	
		
	//GETTES
	function getiddocumento(){ return $this->iddocumento; }
	function getnombredocumento(){ return $this->nombredocumento; }	
	function getobservaciones(){ return $this->observaciones; }	
	function getfechacreacion(){ return $this->fechacreacion; }	
	function getfechamodificacion(){ return $this->fechamodificacion; }	
	function getutilizaidorganizacion(){ return $this->utilizaidorganizacion; }	
	function getlinkantes(){ return $this->linkantes; }	
	function getlinkdespues(){ return $this->linkdespues; }	
	function getidestructuratitulo(){ return $this->idestructuratitulo; }	
	function getcolumnas(){ return $this->columnas; }
	function getdetalles(){ return $this->detalles; }	
	


	function guardar($conexion){
		
		$ahora = "now()";
		if($conexion->tipobd()=="mssql") $ahora="getdate()";
		
		
		if($this->getiddocumento()!=-1){ 
			//EDITAR
			$sql = "
				select iddocumento 
				from doclog_titulos
				where nombredocumento='".$this->nombredocumento."'
				      and iddocumento<>".$this->iddocumento."
			";
			if($conexion->existe($sql)){
				?>
					<script type='text/javascript'> 
						alert("El nombre del documento ya se encuentra registrado.");
						window.history.go(-1);
					</script>
				<?php
			}else{

				$sql = "
				    update doclog_titulos set
							nombredocumento='".$this->nombredocumento."',
							observaciones='".$this->observaciones."',
							fechamodificacion=".$ahora.",						
             	utilizaidorganizacion=".$this->utilizaidorganizacion.",
             	linkantes='".$this->linkantes."',
							linkdespues='".$this->linkdespues."',
							columnas='".$this->columnas."',
							idestructuratitulo='".$this->idestructuratitulo."'
						where iddocumento=".$this->iddocumento."
					";
                //echo $sql;
				$conexion->consultar($sql);

				//REGISTRO TRANSACCIONES -- 2013-10-04
        $conexion->transaccion("DOCLOG - ADMIN - ACTUALIZACION - ".$this->nombredocumento,$sql);



			}
		} else {
			//NUEVO
			$sql = "
				select iddocumento 
				from doclog_titulos
				where nombredocumento='".$this->nombredocumento."'
			";
			if($conexion->existe($sql)){
				?>
					<script type='text/javascript'> 
						alert("El nombre del documento ya se encuentra registrado.");
						window.history.go(-1);
					</script>
				<?php
			}else{
				$sql = "
					insert into doclog_titulos
					(nombredocumento,observaciones,fechacreacion,fechamodificacion,
					 estatus,utilizaidorganizacion,linkantes,linkdespues,
					 idestructuratitulo,columnas)
					values
					('".$this->nombredocumento."','".$this->observaciones."',".$ahora.",".$ahora.",'G',
					 ".$this->utilizaidorganizacion.",'".$this->linkantes."','".$this->linkdespues."',
					 '".$this->idestructuratitulo."', ".$this->columnas.")
				";
				//echo $sql;
				$conexion->consultar($sql);
				$this->setiddocumento(mysql_insert_id());

				//REGISTRO TRANSACCIONES -- 2013-10-04
        $conexion->transaccion("DOCLOG - ADMIN - INSERT - ".$this->nombredocumento,$sql);

				                                				
			} //if($conexion->existe($sql))
		} //if($this->idestructura!=-1) 
		
				
		if($this->getiddocumento()!=-1){
			if($this->getdetalles()!=0){
				
				$sql = "
						delete from doclog_detalles where iddocumento = ".$this->getiddocumento()."
					";
				$conexion->consultar($sql);
				
				
				foreach ($this->getdetalles() as $detalle){
					$sql = " 
							insert into doclog_detalles 
							(iddocumento, idestructuradetalle)
							values 
							(".$this->getiddocumento().",".$conexion->escapalog($detalle).")
						   ";
					//error_log($sql);
					$conexion->consultar($sql);
				} //foreach ($detalles as $detalle)
												
			} //if($this->getdetalles()!=0)									
		} //if($this->getiddocumento()!=-1)
		
		
		$conexion->cerrar();
		header("Location: index.php");
				
	}
	
	

	function deshabilitar($conexion){
		$ahora = "now()";
		if($conexion->tipobd()=="mssql") $ahora="getdate()";		
		
		$sql = " update doclog_titulos set estatus='D', fechamodificacion=".$ahora." where iddocumento=".$this->iddocumento;
		$conexion->consultar($sql);
		$conexion->cerrar();

		//REGISTRO TRANSACCIONES -- 2013-10-04
    $conexion->transaccion("DOCLOG - ADMIN - DESHABILITAR - ".$this->nombredocumento,$sql);

		header("Location: index.php");
	}

	function habilitar($conexion){
		$ahora = "now()";
		if($conexion->tipobd()=="mssql") $ahora="getdate()";
				
		$sql = " update doclog_titulos set estatus='G', fechamodificacion=".$ahora." where iddocumento=".$this->iddocumento;
		$conexion->consultar($sql);
		$conexion->cerrar();

		//REGISTRO TRANSACCIONES -- 2013-10-04
    $conexion->transaccion("DOCLOG - ADMIN - HABILITAR - ".$this->nombredocumento,$sql);


		header("Location: index.php");
	}
	
	function activar($conexion){
				
        //REGISTRO TRANSACCIONES 
        $conexion->transaccion("DOCLOG - GENERA DOCUMENTO - ".$this->getnombredocumento(),$sql);			


		//ACTUALIZA ESTATUS Y FECHAMODIFICACION EN LA TABLA DOCLOG_TITULOS
		$ahora = "now()";
		if($conexion->tipobd()=="mssql") $ahora="getdate()";			
	
		$sql = " 
				update doclog_titulos 
				set 
					estatus='A',
					fechamodificacion=".$ahora."
				where 
					iddocumento=".$this->getiddocumento()."				
			   ";
		$conexion->consultar($sql);
					
		
		//CONTROL DE ACCESO ---- enlace catalog
	   $url_del_documento="../doclog/abrir.php?iddocumento=".$this->getiddocumento()."&ticket=testing";
           $sql = " select idmenu from accelog_menu where url = '".$url_del_documento."' ";
           $result = $conexion->consultar($sql);
           if(($rs=$conexion->siguiente($result))){
               $sql = "
                   update accelog_menu set
                       nombre='".$this->getobservaciones()."'
                   where url='".$url_del_documento."';
                   ";
	       echo "entre:".$sql;
           } else {
               $sql = "
                   insert into accelog_menu values
                   (null,'".$this->getobservaciones()."',0,'".$url_del_documento."',1,-1,20,0);
                   ";
		echo "nuevo";
           }
           $conexion->consultar($sql);			


		//AÑADIENDO LAS OPCIONES 
		
		//CHECANDO SI YA EXISTEN LOS REGISTROS DE PERMISOS
		$sqlpermisos = " 
				select idopcion 
				from accelog_opciones 
				where idopcion like 'doclog_".$this->getiddocumento()."%' 
						";			
		if(!$conexion->existe($sqlpermisos)){
			//AGREGAR
			$sqlpermisos = " insert into accelog_opciones values ('doclog_".$this->getiddocumento()."xa','".$this->getnombredocumento()." no agregar');";
			$conexion->consultar($sqlpermisos);
			//ELIMINAR
			$sqlpermisos = " insert into accelog_opciones values ('doclog_".$this->getiddocumento()."xe','".$this->getnombredocumento()." no eliminar');";
			$conexion->consultar($sqlpermisos);
			//MODIFICAR
			$sqlpermisos = " insert into accelog_opciones values ('doclog_".$this->getiddocumento()."xm','".$this->getnombredocumento()." no modificar');";
			$conexion->consultar($sqlpermisos);				
		}						
		
		$conexion->cerrar_consulta($result);
		$conexion->cerrar();
		
		
		echo "
			  <script type='text/javascript'>
					window.location = 'index.php';
			  </script>
			";			
								
	} // activar documento
	
	




}

?>
