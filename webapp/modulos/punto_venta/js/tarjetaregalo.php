
<script>
$(document).ready(function() {
	$('#send').hide();
	var lwpButton = $("<input>", { 
		id: "send2", 
		type:"button",
		value:"Guardar",
		css: { "padding": "2px", "cursor": "pointer" }, 
		title: "guardar", 
		alt: "guardar", 
		 click: function (e) {
		 	// document.location = "http://www.lawebdelprogramador.com"; 
		 	}
		 	  });
		 	   $("body").append(lwpButton);
			  
	$('#send2').live('click',function() {
		var nombre=jQuery('#i1048').val();
		
		//alert(nombre);
 $.post("../../modulos/punto_venta/reportes/tarjeta.php",{nombre:nombre},
	function(respues) {
// 		
		if(respues=="si"){
		 alert("El numero de tarjeta ya existe");
		 
		 }else{
		 	$('#send').click();
		 }
   	   });	
 	});  	
});
</script>