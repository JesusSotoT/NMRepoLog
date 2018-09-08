	$(function(){
		
	$("#preloader").hide();
		
	});
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	function Elimina(id)
	{
		var r = confirm("Estas seguro que deseas eliminar este registro?");
		if (r == true)
		  {
		  		$.ajax({
				type: 'POST',
				url:'gridalmacen.php',
				data:{funcion:'elimina',id:id,elimina:1},
				success: function(resp){  
				$("#grid").html(resp);
				 }});//end ajax	
		  }
	}

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function paginacionGrid($pagina,$filtro,$elimina)
	{
		if($pagina==0)
		{
			$pagina=$("#irpagina").val();
		}
		$("#preloader").show();
		$.ajax({
		type: 'POST',
		url:'gridalmacen.php',
		data:{filtro:$filtro,page:$pagina,paginacion:$("#irpaginacion").val(),elimina:$elimina},
		success: function(resp){  
		$("#grid").html(resp);
		$("#preloader").hide();
		 }});//end ajax		
	}

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function busquedas(evt,val,campo)
	{
				var key = evt.keyCode;
				if(key==13)
				{
					var filtro=1;
						if(val!="")
						{
								switch(campo)
								{
									case 'id': filtro+=" and a.idAlmacen='"+val+"'"; break;		
									case 'nombre': filtro+=" and a.nombre like '%"+val+"%'"; break;
									case 'dirección': filtro+=" and a.direccion like '%"+val+"%'"; break;
									case 'estado': filtro+=" and e.estado like '%"+val+"%'"; break;
									case 'municipio': filtro+=" and m.municipio like '%"+val+"%'"; break;
									case 'código postal': filtro+=" and a.cp like '%"+val+"%'"; break;
									case 'teléfono contacto': filtro+=" and a.tel_contacto like '%"+val+"%'"; break;
									case 'contacto': filtro+=" and a.contacto like '%"+val+"%'"; break;
								}	
						}
					if($("#elimina").val()==0)
						{	
							paginacionGrid(1,filtro);
						}
						else
						{
							paginacionGrid(1,filtro,1);
						}
					}
			}
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function abrir(nuevo,modificar,eliminar)
    {
     var url = "";
     var frop = document.getElementById("opciones");                        
        $.ajax({type: 'GET',url:'../../../netwarelog/catalog/gestor.php?idestructura=194',data:{},success:function(resp){ 
        	
        	 if(nuevo==1)
        	 {    
        	 			url="../../../netwarelog/catalog/f.php?a=1";
        	 }
        	else 
        	{
                 if(modificar==1)
                  {
                    	url="../../../modulos/punto_venta/catalogos/gridalmacen.php";                            
                  } 
                  else 
                  {
                   		url="../../../modulos/punto_venta/catalogos/gridalmacen.php?elimina=1";                          
                  }
             }      
        	frop.src = url;
        	}});//end ajax		   
                        }               
   	///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function redimensionar(){
                            var frop=document.getElementById("opciones");
                            var altura = parent.innerHeight;
                            if(altura==null){ //IE
                                altura = document.documentElement.clientHeight;
                                altura = altura-80;
                            } else { //otros browser
                                altura = altura-205;
                            }                                                    
                            frop.setAttribute("height", altura);                            
                        }                 
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	