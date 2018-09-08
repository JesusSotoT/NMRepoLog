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
	private $nombre;
	private $busquedas;
	private $filtro;
	private $columnas;
	private $eliminar;
	
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	public function __construct($pagination,$filtro,$nombre,$eliminar=0)
	{
		include_once("../../netwarelog/webconfig.php");
		$this->mysql= new mysqli($servidor,$usuariobd,$clavebd,$bd);		
		if(is_numeric($pagination))
		{
			$this->pagination=$pagination;	
		}
		else{ $this->pagination=10; }
		$this->header='';
		$this->body='';	
		$this->page=1;
		$this->nombre=$nombre;
		$this->eliminar=$eliminar;
		$this->filtro=$filtro;
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
				while ($obj = $result->fetch_object())
				{
						if($i%2==0)
						{	
							$this->body.='<tr  class="busqueda_fila">';	
						}
						else
						{
							$this->body.='<tr  class="busqueda_fila2">';
						}
						$e=0;
						foreach($obj as $campo)
						{
							if($e==0){$id=$campo;}	
							$this->body.='<td align="center">';	
							if(!$this->eliminar)
							{
								$this->body.='<a class="a_registro" style="cursor:pointer;" >'.$campo.'</a>';
							}
							else
							{
								$this->body.='<a class="a_registro" style="cursor:pointer;" onclick="Elimina'.$this->nombre.'('.$id.');">'.$campo.'</a>';
							}
							
							$this->body.='</td>';	
						$e++;
						}
						$this->body.='</tr>';
						
				$i++;			
    			}
				if($i<$this->pagination)
				{
					for($e=$i;$e<=$this->pagination;$e++)
					{
							if($e%2==0)
							{	
								$this->body.='<tr  class="busqueda_fila">';	
							}
							else
							{
								$this->body.='<tr  class="busqueda_fila2">';
							}
							for($j=0;$j<=$this->columnas;$j++)
							{
								$this->body.='<td align="center"></td>';	
							}
							$this->body.='</tr>';
					}
				}	
			
				
			}	
	}
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
	
	public function setHeader($header)
	{
		$this->columnas=count($header);	
		$this->header='<tr  class="tit_tabla_buscar">';	
		foreach($header as $columna)
		{
			$this->header.='<th>'.$columna.'</th>';
			$this->busquedas.='<td align="center"><input style="width:200px;" class="input_filtro"';
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
			
		return $this->titulo.
		'<table  class="busqueda" border="0" cellpadding="3" cellspacing="1" width="100%">'.
		$this->header.
		$this->busquedas.
		$this->body.
		'</table>'.
		$this->opciones();
	}
	
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	public function paginacion()
	{
		if($this->page==1){$pag_anterior=1;}else{$pag_anterior=$this->page-1;}
		if(($this->page+1)>=$this->pages){$pag_siguiente=$this->page;}else{$pag_siguiente=$this->page+1;}		
		
		$this->titulo='<div class="tipo">
		<table><tbody><tr>
		<td><input type="button" value="<" onclick="paginacionGrid'.$this->nombre.'('.($pag_anterior).',\''.addslashes($this->filtro).'\','.$this->eliminar.');"></td>
		<td><input type="button" value=">" onclick="paginacionGrid'.$this->nombre.'('.($pag_siguiente).',\''.addslashes($this->filtro).'\','.$this->eliminar.');" ></td>
		<td><a href="javascript:window.print();">
		<img src="../../netwarelog/repolog/img/impresora.png" border="0"></a></td>
		<td><b>'.$this->nombre.'</b></td>
		<td><img src="img/preloader.gif" id="preloader"></td>
		</tr></tbody></table></div><br>';				
	}
	
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	public function opciones()
	{
		$options='<table width="100%">
		<tr  class="tit_tabla_buscar">
			<td align="center"  width="25%" >Página:'.($this->page).'</td>
			
			<td align="center" width="25%">
			Ir a página:<input id="irpagina" type="text" maxlength="8" style="width:50px;" value="'.$this->page.'" >
			<input type="button" value="Ir"  onclick="paginacionGrid'.$this->nombre.'(0,\''.addslashes($this->filtro).'\','.$this->eliminar.');">
			</td>
			
			<td align="center" width="25%">
			Paginación:<select id="irpaginacion" onchange="paginacionGrid'.$this->nombre.'('.$this->page.',\''.addslashes($this->filtro).'\','.$this->eliminar.');">';
			
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
			<td width="25%" align="center">Número de páginas:'.(int)($this->pages).'</td>
			</tr>';
		$options.='</table>';
		return ($options);	
	}
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
}

