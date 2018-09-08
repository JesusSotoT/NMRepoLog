
 function irConfiguracion(){
	//window.parent.quitartab("tb2261",2261,"Configuracion de prenomina");
	window.parent.agregatab('../../modulos/nominas/index.php?c=Catalogos&f=configPrenomina','Configuracion de prenomina','',2261);
	window.parent.preguntar=true;
 }
 function irAutorizacion(){
	window.parent.agregatab('../../modulos/nominas/index.php?c=Prenomina&f=viewAutorizaNomina','Autorizacion de Nomina','',2420);
	window.parent.preguntar=true;
 }
 
 function contenidoPrenomina(idnomina,fechafin,fechainicio,idtipop){


 	$("#contenidop").html('<i class="fa fa-spinner fa-pulse fa-3x fa-fw margin-bottom" ></i>');
     $.ajax({
                  async:false,
                  type: 'POST',
                  url: 'ajax.php?c=Prenomina&f=empleadosNomina',
                  data: {
                     idnomp: idnomina,
                     fecha:fechafin,
                     fechaIni:fechainicio,
                     idtipop:idtipop
                  },
                    success: function(request) {
                      //alert(request);
                      $("#numnomina").val(idnomina);
                      $("#idtipop").val(idtipop);
                      $("#fechafin").val(fechafin);
                      $("#fechainicio").val(fechainicio);
                      $("#contenidop").html(request);
                      $("#periodo").html("PERIODO "+fechainicio+" - "+fechafin);
                      $("#tdp").attr("colspan",parseInt($("#numconf").val())+ parseInt(3));
                      // $('#tablaprenomina').DataTable( {
							// "language": {
								// "url": "js/Spanish.json"
							// }
						// });
                    }
                  });

 }

  
   // $("#prenomina").on('click', function() { 
      // var btnguardar = $(this);
      // btnguardar.button("loading");
    // //alert($("#numnomina").val());
    // $.post("ajax.php?c=Prenomina&f=CalculoNomina",{
      // numnomina:$("#numnomina").val(),
      // fechafin: $("#fechafin").val(),
      // fechainicio:$("#fechainicio").val(),
      // idtipop:$("#idtipop").val()
      // },function(resp){
        // //alert(resp);
        // $("#contenidop").html(resp);
        // btnguardar.button('reset');
      // }
    // );
//     
//    
    // });


 
function adicional(idempleado){    
                
	$("#"+idempleado).bind("contextmenu", function(e){
		$("#menu").css({'display':'block', 'left':e.pageX, 'top':e.pageY});
		$("#menu li").val(idempleado);
		return false;
	});
             
             
                         
 }
 
$(document).ready(function(){
 
$(".p_anterior").click(function(){ 
  console.log("anterior");
            alert('Has seleccionado un periodo que es anterior al periodo actual de trabajo y no puede ser editado.'); 

           });

       // $(".p_actual").click(function(){ 
        // //alert('Ha seleccionado periodo actual.');
       // });


       /*Al click agrega lo que se indica en la clase de css y manda el alert.*/
       $(".p_futuro").click(function(){ 
        alert('A seleccionado un periodo que es futuro al periodo actual.');
       });//cuando hagamos click, el menú desaparecerá


    $(document).click(function(e){
          if(e.button == 0){
                $("#menu").css("display", "none");
          }
    });
     
    //si pulsamos escape, el menú desaparecerá
    $(document).keydown(function(e){
          if(e.keyCode == 27){
                $("#menu").css("display", "none");
                  }
            });
             
  //controlamos los botones del menú
    $("#menu").click(function(e){
          switch(e.target.id){
                case "sobre":
               // alert(idempleado); 
                     window.parent.preguntar=false;
                     window.parent.quitartab("tb2297",2297,"Sobre-recibo");
                     window.parent.agregatab('../../modulos/nominas/index.php?c=Sobrerecibo&f=sobrereciboview&inf='+e.target.value,'Sobre-recibo','',2297);
                     window.parent.preguntar=true;

          
                      break;      
                case "emple":
               			window.parent.preguntar=false;
                    window.parent.quitartab("tb2209",2209,"Empleados");
                    window.parent.agregatab('../../modulos/nominas/index.php?c=Catalogos&f=empleadoview&editar='+e.target.value,'Empleados','',2209);
						        Window.parent.preguntar=true;
                      
                      
                      break;
                case "eliminar":
                      alert("eliminado!");
                      break;
          }
           
    });
    
    $("#prenomina").on('click', function() {

      	var btnguardar = $(this);
		 btnguardar.button("loading");
		//alert($("#numnomina").val());

		
		if(confirm("Calcular todos los empleados (ACEPTAR).\nCalcular empleados pendientes(Cancelar)")){
			if(confirm("Al calcular todos los empleados, \nse reemplazaran los cálculos manuales que haya realizado en el Sobre Recibo!")){
				$.post("ajax.php?c=Prenomina&f=CalculoNomina",{
					numnomina:$("#numnomina").val(),
					fechafin: $("#fechafin").val(),
					fechainicio:$("#fechainicio").val(),
		      		idtipop:$("#idtipop").val(),
		      		todos:1
				},function(resp){
						alert("Calculo Finalizado!");
						$("#contenidop").html(resp);
						btnguardar.button('reset');
				  		if ( $.fn.dataTable.isDataTable( '#tablaprenomina' ) ) {//comprobamos si ya fue definida
						    table = $('#tablaprenomina').DataTable();//si ya solo refrescamos
						}
						else {
						    table =$('#tablaprenomina').DataTable( {//sino esta definida, definimos
									"language": {
										"url": "js/Spanish.json"
									}
								});
						}
						
				});
			}else{
				btnguardar.button('reset');
			}
		}else{
			$.post("ajax.php?c=Prenomina&f=CalculoNomina",{
				numnomina:$("#numnomina").val(),
				fechafin: $("#fechafin").val(),
				fechainicio:$("#fechainicio").val(),
	      		idtipop:$("#idtipop").val(),
	      		todos:0
				},function(resp){
					alert("Calculo Finalizado!");
					$("#contenidop").html(resp);
					btnguardar.button('reset');
		       		if ( $.fn.dataTable.isDataTable( '#tablaprenomina' ) ) {
					    table = $('#tablaprenomina').DataTable();
					}
					else {
					    table =$('#tablaprenomina').DataTable( {
								"language": {
									"url": "js/Spanish.json"
								}
							});
					}
					
				});
		}
		
   
    });
});
 
      