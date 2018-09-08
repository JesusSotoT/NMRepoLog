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
				url:'gridcolor.php',
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
		url:'gridcolor.php',
		data:{filtro:$filtro,page:$pagina,paginacion:$("#irpaginacion").val(),elimina:$elimina},
		success: function(resp){  
		$("#grid").html(resp);
		$("#preloader").hide();
	////////////////////////////////////////////////////////////	
			$(document).ready(function() {

				$('#datos').jTPS({
					perPages : ['TODO'],
					scrollStep : 1,
					scrollDelay : 30,
					clickCallback : function() {
					}
				});
			}); 
///////////////////////////////////////////////////////////////////////
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
								{//select idCol,color from mrp_color
									case 'id': filtro+=" and c.idCol='"+val+"'"; break;		
									case 'color': filtro+=" and c.color like '%"+val+"%'"; break;
									
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
        $.ajax({type: 'GET',url:'../../../netwarelog/catalog/gestor.php?idestructura=81',data:{},success:function(resp){ 
        	
        	 if(nuevo==1)
        	 {    
        	 			url="../../../netwarelog/catalog/f.php?a=1";
        	 }
        	else 
        	{
                 if(modificar==1)
                  {
                    	url="../../../modulos/punto_venta/catalogos/gridcolor.php";                            
                  } 
                  else 
                  {
                   		url="../../../modulos/punto_venta/catalogos/gridcolor.php?elimina=1";                          
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
	
	