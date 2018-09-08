<?php
header('Content-Type: text/html; charset=UTF-8'); 
class Grid
{
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
	private $pagination;
	private $mysql;
	private $page;
	private $header;
	private $body;
	private $footer;
	private $title;
	private $pages;
	private $query;

	private $busquedas;
	private $filtro;
	private $columnas;
	private $eliminar;
	private $key; 
	
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	public function __construct($key,$pagination,$filtro,$eliminar=0)
	{
		include_once("../../../netwarelog/webconfig.php");
		$this->mysql= new mysqli($servidor,$usuariobd,$clavebd,$bd);		
		if(is_numeric($pagination))
		{
			$this->pagination=$pagination;	
		}
		else{ $this->pagination=20; }
		$this->header='';
		$this->body='';	
		$this->page=1;
		$this->eliminar=$eliminar;
		$this->filtro=$filtro;
		$this->key=$key;
		$this->busquedas='<tr class="titulo_filtros" title="Segmento de búsqueda">';
	}
	
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	public function query($q)
	{
		$this->query=$q;
		if ($result = $this->mysql->query($this->query))
		{
				$this->pages=($result->num_rows/$this->pagination);
				if($result->num_rows%$this->pagination!=0){$this->pages++;}	
		}				
	}
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
	public function execute()
	{
			if($this->page==1){$begin=0;}else{$begin=($this->pagination*$this->page)-$this->pagination;}
									
			if ($result = $this->mysql->query($this->query.' LIMIT '.$begin.','.$this->pagination))
			{
				$i=0;
				if($result->num_rows>0)
				{
					while ($obj = $result->fetch_object())
					{
                            $this->body.='<tr>';
							$e=0;
							foreach($obj as $campo)
							{
								if($e==0){$id=$campo;}
                                if($i%2==0){
                                    $this->body.='<td class=" nmcatalogbusquedacont_1 ">';
                                }else{
                                    $this->body.='<td class=" nmcatalogbusquedacont_2 ">';
                                }
								if(!$this->eliminar)
								{
									if(strcmp($this->key,"idProducto")==0)
									{
										$this->body.='<a class="nmcatalogbusquedacontregistro" style="cursor:pointer;" href="../../../modulos/mrp/index.php/product/formNuevo/'.$id.'" >'.utf8_encode($campo).'</a>';
									}	
									else
									{	
										$this->body.='<a class="nmcatalogbusquedacontregistro" style="cursor:pointer;" href="../../../netwarelog/catalog/f.php?a=0&sw='.$this->key.'=\''.$id.'\'" >'.utf8_encode($campo).'</a>';
									}
								}
								else
								{
									$this->body.='<a class="nmcatalogbusquedacontregistro" style="cursor:pointer;" onclick="Elimina'.'('.$id.');">'.utf8_encode($campo).'</a>';
								}
								
								$this->body.='</td>';	
							$e++;
							}
							$this->body.='</tr>';
							
					$i++;			
    				}
				}
			}
	}
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
	//krmn1
	public function setHeader($header)
	{
		$this->columnas=count($header);
//        $this->header='<tr  class="tit_tabla_buscar" style="font-size:12px;">';
        $this->header='<tr>';
        foreach($header as $columna)
		{
		$this->header.='<th class=" nmcatalogbusquedatit " align="center" sort="'.$columna.'" >'.$columna.'</th>';
			$this->busquedas.='<td align="center"><input  class=" nmcatalogbusquedainputtext "';
			$this->busquedas.='onkeydown="busquedas(event,this.value,\''.strtolower($columna).'\')"></td>';
							
		}
		$this->header.='</tr>';	
		$this->busquedas.='</tr>';	
	}
	
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	public function setStyle($style)
	{
		$this->header=str_replace('<table>','<table '.$style.'>',$this->header);
	}
	
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	public function render($page)
	{
		$this->body='';
		if(is_numeric($page))
		{	
			$this->page=$page;
		}else {$this->page=1;}
		
		$this->execute();
		$this->paginacion();
		
		
		if($this->eliminar)
		{
			$hidden="<input type='hidden' id='elimina' value='1'>";
		}
		else
		{
			$hidden="<input type='hidden' id='elimina' value='0'>";
		}
		
			
		return $this->titulo.
		'<table id="datos"  border="0" cellpadding="3" cellspacing="1" width="100%">'.
		'<thead>'.$this->header.'</thead><tbody>'.
		$this->busquedas.'</tbody><tfoot>'.
		$this->body.'</tfoot>'.
		'
		
		</table>'.
		$this->opciones().$hidden;
	}
	
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	public function paginacion()
	{
		if($this->page==1){$pag_anterior=1;}else{$pag_anterior=$this->page-1;}
		if(($this->page+1)>=$this->pages){$pag_siguiente=$this->page;}else{$pag_siguiente=$this->page+1;}		
		
		$this->titulo='<div class="nmcatalogtitle">
		<table><tr>
        <td><img src="../../../netwarelog/design/default/pag_ant.png" class=" nmwaicons " onclick="paginacionGrid('.($pag_anterior).',\''.addslashes($this->filtro).'\','.$this->eliminar.');"  /></td>
        <!--td><input type="button" value=">" onclick="paginacionGrid('.($pag_siguiente).',\''.addslashes($this->filtro).'\','.$this->eliminar.');" ></td-->
        <td><img src="../../../netwarelog/design/default/pag_sig.png" class=" nmwaicons " onclick="paginacionGrid('.($pag_siguiente).',\''.addslashes($this->filtro).'\','.$this->eliminar.');"  /></td>
		<td><a href="javascript:window.print();">
		<img src="../../../netwarelog/design/default/impresora.png" border="0" class=" nmwaicons " ></a></td>
		<td><img src="../img/preloader.gif" id="preloader"></td>
		</tr></table></div><br>';				
	}
	
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	public function opciones()
	{
		
		$options='<table width="100%" id="gridOpc">
		<tr>
			<td class=" nmcatalogbusquedafoot " align="center"  width="25%" >Página:'.($this->page).'</td>
			
			<td class=" nmcatalogbusquedafoot " align="center" width="25%">
			Ir a página:<input id="irpagina" type="text" class=" nminputtext " maxlength="8" style="width:50px;" onkeyup="valor(event,$(this).val(),'.(int)$this->pages.');"  value="'.$this->page.'">
			<input type="button" value="Ir" class=" nminputbutton " onclick="paginacionGrid(0,\''.addslashes($this->filtro).'\','.$this->eliminar.');">
			</td>
			
			<td class=" nmcatalogbusquedafoot " align="center" width="25%">
			Paginación:<select class=" nminputselect " id="irpaginacion" onchange="paginacionGrid(1,\''.addslashes($this->filtro).'\','.$this->eliminar.');">';
			
			for($i=10;$i<=100;$i=$i+10)
			{
				if($this->pagination==$i)
				{
					$options.='<option selected="selected" value="'.$i.'">'.$i.'</option>';
				}
				else
				{		
					$options.='<option value="'.$i.'">'.$i.'</option>';
				}			
			}
			
			$options.='</select>
			</td >
			<td class=" nmcatalogbusquedafoot " width="25%" align="center">Número de páginas:'.(int)($this->pages).'</td>
			</tr>';
		$options.='</table>
		<script>
			function valor(e,v1,v2){
					var tecla=e.charCode? e.charCode : e.keyCode;
						if(tecla!=13){
							if(v1>v2){
				alert("No existe esa pagina");
						}	
					}	
			}
		</script>';
		return ($options);	
	}
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
		public function elimina($id,$tabla,$campo,$relaciones)
		{
				$elimina=true;
				$mensaje_error="";
						if($tabla=="mrp_producto"){
							$resultado=$this->mysql->query("UPDATE mrp_producto set estatus=0, nombre=concat(nombre,' Borrado ','".$id."'), codigo=concat(codigo,'b','') where idProducto=".$id);
							if($resultado->num_rows > 0){
								return 'Surgio un Error al eliminar un registro.';
							}else{
								return 'El registro ha sido eliminado';
							}
						}else{
				foreach($relaciones as $relacion=>$fk)
				{
						list($tablaFk,$idFk)=explode("-",$fk);	
						$consultafk="Select ".$idFk." from ".$tablaFk." where ".$idFk."=".$id;

						if ($resultfk = $this->mysql->query($consultafk))
						{
							if($resultfk->num_rows>0)
							{	
								$mensaje_error.="No se pudo eliminar el registro existen ".$relacion." relacionados con él, debe eliminar las relaciones para poder eliminar el registro. \\n";
							}
						}
						else {
							echo $consultafk;
							 return "Surgio un error inesperado, intentelo más tarde."; }	
				}
				if(strlen($mensaje_error)>5) 
				{
						$elimina=false;		
						return $mensaje_error;
				}			
				if($elimina)
				{
					if(strcmp($tabla,"mrp_producto")==0)
					{
						if(!$result =$this->mysql->query("DELETE from producto_impuesto where idProducto=".$id))
						{
								return "No se pudo eliminar el registro, intentelo más tarde.";
						}
					}
						
					$consulta="delete from ".$tabla." where ".$campo."=".$id;
					//echo $consulta;
					if ($result = $this->mysql->query($consulta))
					{
									return "Registro eliminado con éxito.";				
					}
					else
					{
									return "No se pudo eliminar el registro, intentelo más tarde.";
					}
				}	
		}
	}
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	
}

