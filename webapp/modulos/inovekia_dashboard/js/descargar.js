$(document).ready(function() {
  	$("#btn_relaciones").click(function(){
	    if(cliente == "mlog"){
	     	window.open("index.php?c=descargar&f=relacion", "_blank");
	    } else {
	     	window.open("http://www.netwarmonitor.mx/clientes/inovekia/webapp/modulos/inovekia_dashboard/index.php?c=descargar&f=relacion", "_blank");
	    }
  	});
  	$("#btn_formulario_uno").click(function(){
	    if(cliente == "mlog"){
	     	window.open("index.php?c=descargar&f=formularioUno", "_blank");
	    } else {
	     	window.open("http://www.netwarmonitor.mx/clientes/inovekia/webapp/modulos/inovekia_dashboard/index.php?c=descargar&f=formularioUno", "_blank");
	    }
  	});
  	$("#btn_seguimiento").click(function(){
	    if(cliente == "mlog"){
	     	window.open("index.php?c=descargar&f=seguimiento", "_blank");
	    } else {
	     	window.open("http://www.netwarmonitor.mx/clientes/inovekia/webapp/modulos/inovekia_dashboard/index.php?c=descargar&f=seguimiento", "_blank");
	    }
  	});
});