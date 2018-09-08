/**
 * @author karmen
 */
function borra(valor,valor2){
		
	 $.post("borrasual.php",{a:valor ,s:valor2},
                 function(respuesta) {	
                 	
                if(respuesta=="ok"){
                 		$("#"+valor).remove();
                 		agregado(2);
                 }
                 if(respuesta=="data"){
                 	alert("No puedes borrar el almacen primario");
                 }
  
   		// $('#datos').load('consultalmacen.php?a='+almacen+"&s="+sucursal);
//    	
});
}