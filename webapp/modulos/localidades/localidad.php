<?php
class Cp
{
	public function __construct()
	{
		require("../../netwarelog/webconfig.php");	
		$link=mysql_connect($servidor,$usuariobd,$clavebd);
		mysql_select_db($bd,$link);	
	}//end construct
	
	public function localidades()
	{
		$localidades='<label class="loc_label">Estado:</label>'.$this->estados().'
					  <label class="loc_label">Municipio:</label><span id="municipios"><select class="loc_select"><option>-Seleccione-</option></select></span>
					  <label class="loc_label">Ciudad:</label><span id="ciudades"><select class="loc_select"><option>-Seleccione-</option></select></span>
					  <label class="loc_label">C&oacute;digo postal:</label><span id="codigospostales"><select class="loc_select"><option>-Seleccione-</option></select></span>';
	return $localidades;
	}
		
	public function estados()
	{
		$q=mysql_query("Select DISTINCT(TRIM(estado)) estado,idestado from codigospostales where idestado<33 and idestado>0 order by estado");
		$estados="<select id='localidad_estado' class='loc_select'>";
		$estados.="<option value=''>-Seleccione-</option>"; 
		while($obj=mysql_fetch_object($q))
		{
			$estados.="<option value='".$obj->idestado."'>".$obj->estado."</option>"; 
		}
		$estados.="</select>";
		return $estados; 
	}//end estados
	
	public function municipios($idestado)
	{
		$q=mysql_query("Select DISTINCT(TRIM(municipio)) municipio,idmunicipio  from codigospostales  where idestado=".$idestado." and idestado<33 and idestado>0  and idmunicipio>0 and idciudad>0 order by municipio");
		$municipios="<select id='localidad_municipio' class='loc_select'>";
		$municipios.="<option value=''>-Seleccione-</option>"; 
		$muni=array();
		while($obj=mysql_fetch_object($q))
		{
			if(!in_array($obj->municipio,$muni))
			{
				$muni[]=$obj->municipio;	
				$municipios.="<option value='".$obj->idmunicipio."'>".utf8_encode($obj->municipio)."</option>"; 
			}
		}
		$municipios.="</select>";
		return $municipios;
	}//end municipios
	
	public function ciudades($idmunicipio)
	{
		$q=mysql_query("Select DISTINCT(TRIM(ciudad)) ciudad,idciudad  from codigospostales  where idmunicipio=".$idmunicipio." and idestado<33 and idestado>0  and idmunicipio>0 and idciudad>0 order by ciudad");
		$ciudades="<select id='localidad_ciudad' class='loc_select'>";
		$ciudades.="<option value=''>-Seleccione-</option>"; 
		$ciud=array();
		while($obj=mysql_fetch_object($q))
		{
			if(!in_array($obj->ciudad,$ciud))
			{
				$ciud[]=$obj->ciudad;			
				$ciudades.="<option value='".$obj->idciudad."'>".utf8_encode($obj->ciudad)."</option>"; 
			}
		}
		$ciudades.="</select>";
		return $ciudades;
	}//end ciudades
	
	public function codigos($idciudad)
	{
		$q=mysql_query("Select DISTINCT(cp)  from codigospostales where idciudad=".$idciudad." order by cp");
		$codigos.="<select id='localidad_codigo' class='loc_select'>";
		$codigos.="<option value=''>-Seleccione-</option>"; 
		while($obj=mysql_fetch_object($q))
		{
			$codigos.="<option value=''>".$obj->cp."</option>"; 
		}
		$codigos.="</select>";
		return $codigos;
	}//end codigos
	
}//end class


$cp=new Cp();
if(count($_POST)>0)
{
	switch ($_POST["operacion"])
	{
		case 'municipios':echo $cp->municipios($_POST["estado"]);break;
		case 'ciudades':  echo $cp->ciudades($_POST["municipio"]);break;
		case 'codigos':  echo $cp->codigos($_POST["ciudad"]);break;
	}
}

?>