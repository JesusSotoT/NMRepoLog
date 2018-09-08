<?php
switch($_REQUEST['fun']){
	case 'verIngreso':
		$menu = "Ingresos";
		$agregar = "index.php?c=Ingresos&f=verIngreso";
		$editar = "ajax.php?c=Ingresos&f=listadoIngresos&opc=editar";
		$eliminar = "ajax.php?c=Ingresos&f=listadoIngresos&opc=eliminar";
	break;
	case 'verIngresoNodep':
		$menu = "Ingresos por Depositar";
		$agregar = "index.php?c=Ingresos&f=verIngresoNodep";
		$editar = "ajax.php?c=Ingresos&f=listadoIngresosP&opc=editar";
		$eliminar = "ajax.php?c=Ingresos&f=listadoIngresosP&opc=eliminar";
	break;
	case 'verDeposito':
		$menu = "Depositos";
		$agregar = "index.php?c=Ingresos&f=verDeposito";
		$editar = "ajax.php?c=Ingresos&f=listadoDeposito&opc=editar";
		$eliminar = "ajax.php?c=Ingresos&f=listadoDeposito&opc=eliminar";
	break;
	case 'verEgresos':
		$menu = "Egresos";
		$agregar = "index.php?c=Cheques&f=verEgresos";
		$editar = "ajax.php?c=Cheques&f=listadoEgreso&opc=editar";
		$eliminar = "ajax.php?c=Cheques&f=listadoEgreso&opc=eliminar";
	break;
	case 'vercheque':
		$menu = "Cheques";
		$agregar = "index.php?c=Cheques&f=vercheque";
		$editar = "ajax.php?c=Cheques&f=listadoCheques&opc=editar";
		$eliminar = "ajax.php?c=Cheques&f=listadoCheques&opc=eliminar";
	break;
}
?>
<script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
<link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap-theme.min.css">
<link rel="stylesheet" href="../../libraries/font-awesome/css/font-awesome.min.css">

<nav class="navbar navbar-default" id="nmtoolbar_catalog">
    	
    	<div class="container-fluid">
    
			<ul class="nav navbar-nav">
				<li id="linew">
        				<a title="Agregar Nuevo Documento" style="cursor: pointer"><i class="fa fa-plus fa-lg" onclick="window.location='<?php echo $agregar;?>'"></i></a>
				</li>
			    <li id="liedit">
      				<a title="Editar Documento Existente" style="cursor: pointer"><i  class="fa fa-pencil-square-o fa-lg" onclick="javascript:carga('<?php echo $editar;?>')" ></i></a>
			    	</li>
			    <li id="lidelete">
					<a title="Eliminar Documento" style="cursor: pointer"><i class="fa fa-trash-o fa-lg"   onclick="javascript:carga('<?php echo $eliminar;?>')"></i> </a>      
			    	</li>    			
		        
                <li><span class="label label-default" id="lblstatus" style="display: none;">Espere un momento ...</span></li>
		  		<li id="espere" style="display: none">
			  		<i class="fa fa-spinner fa-pulse fa-3x fa-fw" id=""></i>
			  		Espere...
		  		</li>
		   </ul>
		
		</div>
	</nav>

<script>
function carga(link){
	$("#espere").show();
	//$("#lista").load(link);
	

	$( "#lista" ).load( link, function( response, status, xhr ) {$("#espere").hide();
	  if ( status == "error" ) {
	  	
	    alert("Ocurrió un problema con su conexión intente de nuevo");
	  }else if(status == 'success') { 
	  	$("#espere").hide();
	  }
});
}
</script>
<div id="lista"></div>