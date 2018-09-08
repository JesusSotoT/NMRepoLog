<?php

//echo json_encode($datosCliente);
function randpass() {
    $alphabet = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < 8; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
}
 ?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Formulario de Cliente</title>
	<link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap-theme.min.css">
	<link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="../../libraries/font-awesome/css/font-awesome.min.css">
	<script src="../../libraries/jquery.min.js"></script>
	<script src="../../libraries/bootstrap/dist/js/bootstrap.min.js"></script>
	<script src="../../libraries/numeric.js"></script>
	<script src="js/cliente.js"></script>
<!--Select 2 -->
	<script src="../../libraries/select2/dist/js/select2.min.js"></script>
	<link rel="stylesheet" type="text/css" href="../../libraries/select2/dist/css/select2.min.css" />
<!-- datetimepicker -->
<link rel="stylesheet" href="../../libraries/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" />

<script src="../../libraries/bootstrap-datetimepicker/js/moment.js"></script>

<script src="../../libraries/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
<link rel="stylesheet" href="../../libraries/dataTable/css/datatablesboot.min.css">
<!--    <script src="../../libraries/dataTable/js/datatables.min.js"></script> -->
    <script src="../../libraries/export_print/jquery.dataTables.min.js"></script>
    <script src="../../libraries/dataTable/js/dataTables.bootstrap.min.js"></script>

    <!-- Modificaciones RCA -->
    <link rel="stylesheet" type="text/css" href="../../libraries/dataTable/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="../../libraries/dataTable/css/buttons.dataTables.min.css">
    <script src="../../libraries/export_print/jquery.dataTables.min.js"></script>
    <script src="../../libraries/export_print/dataTables.buttons.min.js"></script>

	<script>

	Number.prototype.format = function() {
        return this.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,");
    };
		$(document).ready(function() {
		  $('#numeros').numeric();
		  $('#tipoClas').select2({'width':'100%'});
		  $('#tipoDeCredito').select2({'width':'100%'});
		  $('#moneda').select2({'width':'100%'});
		  $('#banco').select2({'width':'100%'});
		  $('#vendedor').select2({'width':'100%'});
		  $('#cuentaCont').select2({'width':'100%'});
		  $(".numeros").numeric();

		  $('#tableGrid').DataTable({
            autowidth: 'false',
            dom: 'Bfrtip',
            buttons: [ 'excel' ],
                            language: {
                            search: "Buscar:",
                            lengthMenu:"",
                            zeroRecords: "No hay datos.",
                            infoEmpty: "No hay datos que mostrar.",
                            info:"Mostrando del _START_ al _END_ de _TOTAL_ facturas",
                            paginate: {
                                first:      "Primero",
                                previous:   "Anterior",
                                next:       "Siguiente",
                                last:       "Último"
                            },
                         },
                          aaSorting : [[0,'desc' ]]
        });


		  $('#tableSales').DataTable({
                            dom: 'Bfrtip',
                            buttons: [ 'excel' ],
                            language: {
                                search: "Buscar",
                                lengthMenu:"",
                                zeroRecords: "No hay datos.",
                                infoEmpty: "No hay datos que mostrar.",
                                info:"Mostrando del _START_ al _END_ de _TOTAL_ elementos",
                                paginate: {
                                    first:      "Primero",
                                    previous:   "Anterior",
                                    next:       "Siguiente",
                                    last:       "Último"
                                },
                            },
                            aaSorting : [[0,'desc' ]]
        });

		  $('#tableCotis').DataTable({
                            dom: 'Bfrtip',
                            buttons: [ 'excel' ],
                            language: {
                                search: "Buscar",
                                lengthMenu:"",
                                zeroRecords: "No hay datos.",
                                infoEmpty: "No hay datos que mostrar.",
                                info:"Mostrando del _START_ al _END_ de _TOTAL_ elementos",
                                paginate: {
                                    first:      "Primero",
                                    previous:   "Anterior",
                                    next:       "Siguiente",
                                    last:       "Último"
                                },
                            },
                            aaSorting : [[0,'desc' ]]
        });

		  

		  buscarPortal();
		  buscarFacturas();
		  listaCargosFacturas();
		});


		function verPdf(id){
	window.open("../../modulos/facturas/"+id+".pdf");

}

function vercomcli(op){
        if(op==0){
        
            c=$('#cadenaCoti').val();
        }else{
        
            c=op;
        }
        p='c';
        window.open("../../../coti/index.php?c="+c+'&p='+p);
    }

function modificarPassPortal(){
	var cliente = $('#idCliente').val();
	$('#btnenviarCorreo').prop('disabled',true); 
	$('#btnenviarCorreo').text( $('#btnenviarCorreo').attr('txt-click') );


	passportal2=$('#passportal2').val();
	passportal3=$('#passportal3').val();


	if(passportal2=='' || passportal3==''){
		alert('Los campos no pueden estar vacios.');
		$('#btnenviarCorreo').prop('disabled',false); 
		$('#btnenviarCorreo').text( $('#btnenviarCorreo').attr('txt-original') );
		return false
	}

	if(passportal2!=passportal3){
		alert('Las contraseñas no coinciden.');
		$('#btnenviarCorreo').prop('disabled',false); 
		$('#btnenviarCorreo').text( $('#btnenviarCorreo').attr('txt-original') );
		return false
	}

	$.ajax({
    url:"ajax.php?c=cliente&f=modificarPortal",
    type: 'POST',
    data:{passportal2:passportal2,cliente:cliente,passportal3:passportal3},
    success: function(data){

    	alert('Contraseña modificada correctamente');
    	
    	$('#btnenviarCorreo').prop('disabled',false); 
		$('#btnenviarCorreo').text( $('#btnenviarCorreo').attr('txt-original') );

		location.reload(); 

    }
  });

}

function verXml(id){
	$.ajax({
		url: 'ajax.php?c=caja&f=origenPac',
		type: 'POST',
		dataType: 'json',
		data: {id: id},
	})
	.done(function(resp) {
		if(resp.pac=='formas'){
			window.open("../../modulos/cont/xmls/facturas/temporales/"+id+".xml");
		}else{
			window.open("../../modulos/facturas/"+id+".xml");
		}
	})
	.fail(function() {
		console.log("error");
	})
	.always(function() {
		console.log("complete");
	});


}



		function buscarFacturas(){
			var cliente = $('#idCliente').val();
			var desde = '2000-01-01';
        	var hasta = '3000-01-01';
			var tipo = 1;
			$.ajax({
				url: 'ajax.php?c=caja&f=buscarFacturasCliente',
				type: 'POST',
				dataType: 'json',
				data: {cliente: cliente,
						desde : desde,
						hasta : hasta,
						tipo : tipo
					},
			})
			.done(function(result) {
				console.log(result);
				var table = $('#tableGrid').DataTable();

		            //$('.rows').remove();

		            table.clear().draw();

		            var x ='';
		            var estatus = '';
		            var monto = 0;
		            var iva = 0;
		            var total = 0;
		            var y = '';
		            var acuse = '';
		    		var proviene = '';
		    		var tipo = '';
		            $.each(result, function(index, val) {
		            	console.log(result);
		            	//alert(val.cadenaOriginal);
		            	x = JSON.parse(val.cadenaOriginal)

		            	console.log(x);
		            	
		            	/*alert(x.datosTimbrado.UUID);
		            	alert(val.borrado);
		            	alert(val.idSale);
		            	alert(x.datosTimbrado.UUID);
		            	alert(x.Basicos.total);
		            	alert(x.Receptor.nombre);
		            	alert(val.tipoComp); */



		                if(val.borrado=='0'){
		                    estatus = '<span class="label label-success">Activa</span>';
		                    //acuse = '<a class="btn btn-default" alt="Cancelar factura" title="Cancelar factura" onclick="cancelar('+val.id+');"><i class="fa fa-times" aria-hidden="true"></i></a>';
		                }else if(val.borrado=='2'){
		                	estatus = '<span class="label label-warning">Con Nota</span>';
		                }else{
		                    estatus = '<span class="label label-danger">Cancelada</span>';
		                    //acuse = '<a class="btn btn-default" alt="Acuse de cancelación" title="Acuse de cancelación" onclick="verAcuse('+val.id+');"><i class="fa fa-sticky-note-o" aria-hidden="true"></i></a>';
		                }

		                if(val.origen == 1){ // comercial
		                	proviene = 'Envios';
		                }else{
		                	proviene = 'Caja';
		                }
		                /*
		                if(val.proviene==0){
		                	proviene = 'Caja';
		                }else if (val.proviene==1){
		                	proviene = 'Kiosko';
		                }else{
		                	proviene = 'Layout';
		                }
		                */
		                if(val.tipoComp=='F'){
		                	//tipo = '<a class="btn btn-default" alt="Crear nota de crédito" title="Crear nota de crédito" onclick="notaCredito('+val.id+');"><i class="fa fa-file-text-o"" aria-hidden="true"></i></a>';
		                }else{
		                	//tipo = '';
		                }



		                y ='<tr class="filas">'+
																		'<td>'+val.id+'</td>'+
																		'<td>'+val.fecha+'</td>'+
		                                //'<td>'+( val.tipoComp == "C" ? "NC" : val.tipoComp )+'</td>'+
		                                '<td>'+x.datosTimbrado.UUID+'</td>'+
		                                //'<td>'+x.Receptor.rfc+'</td>'+
		                                //'<td>'+x.Receptor.nombre+'</td>'+
		                                '<td>'+x.Basicos.folio+'</td>'+
		                                //'<td>'+x.Basicos.folio+'</td>'+
		                                '<td>'+val.idSale+'</td>'+

		                                '<td>$'+x.Basicos.total+'</td>'+



		                                //'<td>'+proviene+'</td>'+

		                                '<td>'+estatus+'</td>'+
		                                '<td><a class="btn btn-default" alt="Visualizar PDF" title="Visualizar PDF" onclick="verPdf(\''+x.datosTimbrado.UUID+'\');"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>'+
		                                '<a class="btn btn-default" alt="Visualizar XML" title="Visualizar XML" onclick="verXml(\''+x.datosTimbrado.UUID+'\');"><i class="fa fa-file-code-o" aria-hidden="true"></i></a>'+
		                                //'<a class="btn btn-default" onclick="cancelar('+val.id+');"><i class="fa fa-times" aria-hidden="true"></i></a>'+
		                                tipo+acuse+
		                                //'<a class="btn btn-default" alt="Reenviar por correo" title="Reenviar por correo" onclick="enviaFact('+val.id+');"><i class="fa fa-envelope-o" aria-hidden="true"></i></a>'+
		                                '</td></tr>';

		                    table.row.add($(y)).draw();

		            });
		            //alert(total);



			})
			.fail(function() {
				console.log("error");
			})
			.always(function() {
				console.log("complete");
			});

		}


		function listaCargosFacturas()
{
    var idPrvCli = $('#idCliente').val();

    $.post('ajax.php?c=portalclientes&f=listaCargosFacturas',
        {
            idPrvCli: idPrvCli,
            cobrar_pagar: $("#cobrar_pagar").val()
        },
        function(data)
        {


           var datos = jQuery.parseJSON(data);

                $('#tabla-carfac').DataTable( {
                    dom: 'Bfrtip',
                    buttons: ['excel'],
                    language: {
                        search: "Buscar:",
                        lengthMenu:"Mostrar _MENU_ elementos",
                        zeroRecords: "No hay coincidencias.",
                        infoEmpty: "No hay coincidencias que mostrar.",
                        infoFiltered: "",
                        info:"Mostrando del _START_ al _END_ de _TOTAL_ cuentas",
                        paginate: {
                            first:      "Primero",
                            previous:   "Anterior",
                            next:       "Siguiente",
                            last:       "Último"
                        }
                     },
                     "order": [[ 0, "asc" ]],
                     data:datos,
                     columns: [
                        { data: 'fech_cargo' },
                        { data: 'fecha_venc' },
                        { data: 'concepto' },
                        { data: 'monto' },
                        { data: 'abonado' },
                        { data: 'actual' },
                        { data: 'estatus' },
                        { data: 'ov' }
                    ]
                });
                var saldo = 0;
                $('.actual').each(function()
                {
                    saldo+=parseFloat($(this).attr('cantidad'))
                })
                $("#total_saldos").val("$ "+saldo.format())
                $("#tabla-carfac").before($("#saldos_div2"));
        });
}
		
		function enviarCorreoPortal(){
			correoportal=$('#correoportal').val();
			userportal=$('#userportal').val();
			passportal=$('#passportal').val();
			nombre=$('#nombre').val();

			if(correoportal=='' || userportal=='' || passportal==''){
				alert('Los campos no pueden estar vacios.');
				return false
			}

			$.ajax({
		    url:"ajax.php?c=cliente&f=correoPortal",
		    type: 'POST',
		    data:{correoportal:correoportal,userportal:userportal,passportal:passportal,nombre:nombre},
		    success: function(data){
		    	if(data==1){
		    		alert('Correo enviado al cliente');
		    	}else{
		    		alert('Error en el proceso de envio');
		    	}

		    }
		  });
		}

		function quitm(){
			$('#modalSuccess').modal('hide');
		}


		function isValidRfc(rfc)
{
		if(rfc.match(/[A-Z,Ñ,&]{3,4}[0-9]{2}[0-1][0-9][0-3][0-9][A-Z,0-9]?[A-Z,0-9]?[0-9,A-Z]?$/i)){//Moral y Fisica
			return true;
		}else{
			return false;
		}
}


function guardaClientePortal(){

	//Datos Obligatorios



	// var codigo =  $('#codigo').val();
	var nombre =  $('#nombre').val();
	var pais =  $('#selectPais').val();
	if( nombre == "" || pais == ""){
		alert("Verifica haber llenado todos los comapos oblicatorios (*)");
		return;
	}
	//Datos Basicos
	var idCliente =  $('#idCliente').val();
	var tienda =  $('#tienda').val();
	var mumint =  $('#numint').val();
	var numext =  $('#numext').val();
	var direccion =  $('#direccion').val();
	var colonia =  $('#colonia').val();
	var cp =  $('#cp').val();
	var estado =  $('#selectEstado').val();
	var municipio =  $('#selectMunicipio').val();
	var email =  $('#email').val();
	var celular =  $('#celular').val();
	var tel1 =  $('#tel1').val();
	var tel2 =  $('#tel2').val();
	var ciudad = $('#ciudad').val();
	// var cumpleanos = $('#cumpleanos').val();
	/// Datos de Facturacion
	// var idComunFact = $('#idComunFact').val();
	// var rfc =  $('#rfc').val();
	// var curp =  $('#curp').val();
	// var razonSocial = $('#razonSocial').val();
	// var emailFacturacion = $('#emailFacturacion').val();
	// var direccionFact = $('#direccionFact').val();
	// var numextFact = $('#numextFact').val();
	// var numintFact = $('#numintFact').val();
	// var coloniaFact = $('#coloniaFact').val();
	// var cpFact = $('#cpFact').val();
	// var paisFact2 = $('#paisFact2').val();
	// var estadoFact = $('#estadoFact').val();
	// var municipiosFact = $('#municipiosFact').val();
	// var ciudadFact = $('#ciudadFact').val();
	// var paisFact = $('#paisFact').val();
	// var regimenFact = $('#regimenFact').val();


	// var vacios = false;
	// var llenos = false;

	// Expresion regular para validar el correo
	var regex = /[\w-\.]{2,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/;
	// VALIDACIONES
	// rfc
	// if(rfc != ''){
	// 	if(isValidRfc(rfc) == false){ alert('RFC no valido!!'); $("#rfc").focus(); return 0; }
	// }
	// email
	if(email != ''){
		if (!regex.test(email.trim())) {
			alert('Email de facturación no valido!!'); $("#email").focus(); return 0;
		}
		//if(isValidEmail(email) == false){ alert('Email Basico no valido!!'); $("#emailemail").focus(); return 0; }
	}
	// if(emailFacturacion != ''){
	// 	if (!regex.test(emailFacturacion.trim())) {
	// 		alert('Email de facturación no valido!!'); $("#emailFacturacion").focus(); return 0;
	// 	}
	// 	//if(isValidEmail(emailFacturacion) == false){ alert('Email de facturación no valido!!'); $("#emailFacturacion").focus(); return 0; }
	// }

	// TODOS O NINGUNO
	// if(razonSocial == '' && rfc == '' && emailFacturacion == '' && direccionFact == '' && numextFact == '' && coloniaFact == '' && cpFact == '' && estadoFact == '0' && municipiosFact == '0' && ciudadFact == '' && paisFact2 == '0'){
	// 	//TODOS VACIOS
	// 	vacios = true;
	// }

	// if(razonSocial != '' && rfc != '' && emailFacturacion != '' && direccionFact != '' && numextFact != '' && coloniaFact != '' && cpFact != '' && estadoFact != '0' && municipiosFact != '0' && ciudadFact != '' && paisFact2 != '0' && vacios == false) {
	// 	llenos = true;
	// }

	// if (vacios == false && llenos == false ) {
	// 	alert('Todos los datos de Facturación son requeridos');
	// 	return 0;
	// }

	// //Datos Credito
	// var tipoDeCredito = $('#tipoDeCredito').val();
	// var diasCredito =  $('#diasCredito').val();
	// var limiteCredito =  $('#limiteCredito').val();
	// var moneda =  $('#moneda').val();
	// var listaPrecio =  $('#listaPrecio').val();
	// var descuentoPP = $('#descuentoPP').val();
	// var interesesMoratorios = $('#interesesMoratorios').val();
	//    if($('#checkVc').is(':checked')){
	// 		perVenCre = 1
	//    }else{
	// 		perVenCre = 0;
	//    }
	//    if($('#checkLc').is(':checked')){
	// 		perExLim = 1
	//    }else{
	// 		perExLim = 0;
	//    }
	// var banco = $('#banco').val();
	// var numCuenta = $('#cuentaBanc').val();
	// //Datos Comision
	// var comisionVenta = $('#comisionVenta').val();
	// var comisionCobranza =  $('#comisionCobranza').val();
	// var empleado = $('#vendedor').val();
	// //Datos de Envio
	// var enviosDom = $('#enviosDom').val();

	// var tipoClas = $('#tipoClas').val();
	// var cuentaCont = $('#cuentaCont').val();

	// if(codigo==''){
	// 	alert('No puedes dejar el codigo vacio.');
	// 	return false;
	// }
	// if(nombre==''){
	// 	alert('No puedes dejar el Nombre vacio.');
	// 	return false;
	// }

	//alert('guardado');
	//return 0;

	$.ajax({
		url: 'ajax.php?c=portalclientes&f=guardaCliente',
		type: 'POST',
		dataType: 'json',
		data: {idCliente: idCliente,
				// codigo : codigo,
				nombre : nombre,
				tienda : tienda,
				numint : mumint,
				numext : numext,
				direccion: direccion,
				colonia : colonia,
				cp : cp,
				pais : pais,
				estado : estado,
				municipio: municipio,
				email : email,
				celular : celular,
				tel1 : tel1,
				tel2 : tel2,
				// rfc : rfc,
				// curp : curp,
				// diasCredito : diasCredito,
				// limiteCredito: limiteCredito,
				// moneda : moneda,
				// listaPrecio : listaPrecio,
				// razonSocial : razonSocial,
				// emailFacturacion : emailFacturacion,
				// direccionFact : direccionFact,
				// numextFact : numextFact,
				// numintFact : numintFact,
				// coloniaFact : coloniaFact,
				// cpFact : cpFact,
				// paisFact : paisFact2,
				// estadoFact : estadoFact,
				// municipiosFact : municipiosFact,
				// ciudadFact : ciudadFact,
				// tipoDeCredito : tipoDeCredito,
				// descuentoPP : descuentoPP,
				// interesesMoratorios : interesesMoratorios,
				// perVenCre : perVenCre,
				// perExLim : perExLim,
				// comisionVenta : comisionVenta,
				// comisionCobranza : comisionCobranza,
				// empleado : empleado,
				// enviosDom : enviosDom,
				// tipoClas : tipoClas,
				// idComunFact : idComunFact,
				// regimenFact : regimenFact,
				// banco : banco,
				// numCuenta : numCuenta,
				// cuentaCont : cuentaCont,
				 ciudad : ciudad
				// cumpleanos : cumpleanos
			},
	})
	.done(function(data) {
		console.log(data);
		if(data.idClienteInser!=''){
			$('#modalSuccess').modal({
				show:true,
			});
		}else{
			alert('Algo Paso');
		}
	})
	.fail(function() {
		console.log("error");
	})
	.always(function() {
		console.log("complete");
	});

}

function buscarPortal(){
        var cliente = $('#idCliente').val();
        var empleado = 0;
        var desde = '2000-01-01';
        var hasta = '3000-01-01';
        var sucursal = 0
        var via_contacto = "";

        $.ajax({
            url: 'ajax.php?c=caja&f=buscarVentas',
            type: 'POST',
            dataType: 'json',
            data: {cliente: cliente,
                    empleado : empleado,
                    desde: desde,
                    hasta: hasta,
                    sucursal: sucursal,
                    via_contacto: via_contacto
                },
        })
        .done(function(data) {
            console.log(data);
            var table = $('#tableSales').DataTable();
    
            //$('.rows').remove();
            
            table.clear().draw();
         
            var x ='';
            var estatus = '';
            var monto = 0;
            var iva = 0;
            var total = 0;
            var docu = '';
            var xlink = '';
            var cad = '';
            $.each(data.ventas, function(index, val) {
                monto = parseFloat(val.monto);
                if(val.estatus=='Activa'){
                    estatus = '<span class="label label-success">Activa</span>';
                    total += parseFloat(monto.toFixed(2));  
                }else{
                    estatus = '<span class="label label-danger">Cancelada</span>';
                }

                if(val.documento==1){
                    if(val.cadenaOriginal!=null){
                        cad = atob(val.cadenaOriginal);
                        cad  =  JSON.parse(cad);

                        xlink = '<a href="../../modulos/facturas/'+cad.datosTimbrado.UUID+'.pdf" target="_blank">'+cad.Basicos.folio+'</a>';
                        docu = 'Ticket Facturado('+xlink+')';
                    }else{
                        docu = 'Ticket';
                    }
                    
                }else if(val.documento==2){
                    if(val.cadenaOriginal!=null){
                        cad = atob(val.cadenaOriginal);
                        cad  =  JSON.parse(cad);

                        xlink = '<a href="../../modulos/facturas/'+cad.datosTimbrado.UUID+'.pdf" target="_blank">'+cad.Basicos.folio+'</a>';
                    }else{
                        xlink = 'Pendiente';
                    }
                    docu = 'Factura('+xlink+')';
                }else if(val.documento==4){
                    docu = 'Recibo de pago';
                }else if(val.documento==5){
                    if(val.cadenaOriginal!=null){
                        cad = atob(val.cadenaOriginal);
                        cad  =  JSON.parse(cad);

                        xlink = '<a href="../../modulos/facturas/'+cad.datosTimbrado.UUID+'.pdf" target="_blank">'+cad.Basicos.folio+'</a>';
                    }else{
                        xlink = 'Pendiente';
                    }
                    docu = 'Recibo de Honorarios('+xlink+')';
                } 

                if(val.devoluciones != 0)
                    estatus += '<br> <span class="label label-warning" > Con devoluciones </span>';
                iva = parseFloat(val.iva);
                x ='<tr class="filas">'+
                                '<td>'+val.folio+'</td>'+
                                //'<td>'+docu+'</td>'+
                                '<td>'+val.fecha+'</td>'+
                                //'<td>'+val.cliente+'</td>'+
                                //'<td>'+val.empleado+'</td>'+
                                '<td>'+val.sucursal+'</td>'+
                                '<td>'+estatus+'</td>'+
                                '<td>$'+iva.toFixed(2)+'</td>'+
                                '<td>$'+monto.toFixed(2)+'</td>'+
                                //'<td><button class="btn btn-primary btn-block" onclick="ventaDetalle('+val.folio+');" type="button"><i class="fa fa-list-ul"></i> Detalle</button></td>';
                                '</tr>';  
                    table.row.add($(x)).draw();

                                         
            });    
            //alert(total);    
            total = parseFloat(total).toFixed(2); 
            $('#montoTotalLabel').text('$'+total);
            var prom = parseFloat(total).toFixed(2) / parseFloat(data.numTrans).toFixed(2); 
            if(isNaN(prom)){
                prom = 0.00;
            }
            if(data.numTrans==0){
                $('#gDonut').html('<h3 align="center">No hay datos</h3>')
                $('#gLine').html('<h3 align="center">No hay datos</h3>')
                $('#gDonutMenos').html('<h3 align="center">No hay datos</h3>')
            }
            $('#ticketPromedio').text('$'+parseFloat(prom).toFixed(2));
            $('#transacciones').text(data.numTrans);
        
        })
        .fail(function() {
            console.log("error");
        })
        .always(function() {
            console.log("complete");
        });
    
    }
	</script>
  <style>
    .select2-selection{
      height: 34px !important;
    }
  </style>

</head>
<body>
<div class="container-fluid well">
	  <div class="row">
		<!--<div class="col-sm-1">
			<button class="btn btn-default" onclick="back();"><i class="fa fa-long-arrow-left" aria-hidden="true"></i> Regresar</button>
		</div>-->
		<div class="col-sm-1" style="margin: 0px 0px 18px 2px;">
		  <button type="button" class="btn btn-primary" onclick="guardaClientePortal();"><span class="glyphicon glyphicon-floppy-disk"></span> Guardar</button>
		</div>
		<div class="col-sm-1">
		<?php
		  if($idCliente!=''){
			echo '<span class="label label-warning">Editando</span>';
		  }else{
			echo '<span class="label label-success">Nuevo</span>';
		  }

		?>
		</div>
	</div>
  <div class="panel panel-default">
  <div class="panel-heading"><h5>Cliente<?php
						if(isset($datosCliente)){echo ' ('.$datosCliente['basicos'][0]['nombre'].')';}?></h5></div>
  <div class="panel-body">
	<div style="heigth:400px;">
	  <div id="tabsCliente">
		<ul class="nav nav-tabs">
		  <li class="active"><a data-toggle="tab" href="#basicos">Datos Generales</a></li>
		  <li><a data-toggle="tab" href="#ventasPortal">Ventas</a></li>
		  <li><a data-toggle="tab" href="#saldos">Saldos</a></li>
		  <li><a data-toggle="tab" href="#facturas">Facturas</a></li>
		  <li><a data-toggle="tab" href="#cotizaciones">Cotizaciones</a></li>
		  <li><a data-toggle="tab" href="#accesoPortal">Datos de acceso</a></li>
		</ul>
	  </div>
	  <div class="tab-content" style="height:450px;">
		<div id="basicos" class="tab-pane fade in active" style="margin-top: 10px;">
		  <div class="row">
			<div class="col-sm-2">
			  <label class="control-label" for="email">ID</label>
			  <input id="idCliente" class="form-control" type="text" value="<?php
						if(isset($datosCliente)){echo $datosCliente['basicos'][0]['id'];}?>" readonly placeholder="(Autonumérico)">
			</div>
			<!--
			<div class="col-sm-3">
				<label class="control-label"><span style="color:red;">* </span>Código</label>
				<input id="codigo" class="form-control" type="text" value="<?php
						if(isset($datosCliente)){echo $datosCliente['basicos'][0]['codigo'];}?>">
			</div>
			<div class="col-sm-3">
			  <label>Clasificador del Cliente</label>
			  <select id="tipoClas" class="form-control">
				<?php
				  foreach ($clasificadores as $keyClas => $valueClas) {
							   if(isset($datosCliente)){
								  if($datosCliente['basicos'][0]['id_clasificacion']==$valueClas['id']){
									echo '<option value="'.$valueClas['id'].'" selected>'.$valueClas['nombre'].'/'.$valueClas['clave'].'</option>';
								  }
								}
					echo '<option value="'.$valueClas['id'].'">'.$valueClas['nombre'].'/'.$valueClas['clave'].'</option>';
				  }
				?>
			  </select>
			</div>
			-->

		  </div>

		  <div class="row">
			<div class="col-sm-6">
			  <label class="control-label"><span style="color:red;">*</span> Nombre</label>
			  <input id="nombre" class="form-control" type="text" value="<?php
						if(isset($datosCliente)){echo $datosCliente['basicos'][0]['nombre'];}?>">
			</div>
			<div class="col-sm-6">
				<label class="control-label">Nombre Comercial</label>
				<input id="tienda" class="form-control" type="text" value="<?php
						if(isset($datosCliente)){echo $datosCliente['basicos'][0]['nombretienda'];}?>">
			</div>
		  </div>

		  <div class="row">
			<div class="col-sm-6">
			  <label class="control-label">Dirección</label>
			  <input id="direccion" class="form-control" type="text" value="<?php
						if(isset($datosCliente)){echo $datosCliente['basicos'][0]['direccion'];}?>">
			</div>
			<div class="col-sm-3">
			  <label class="control-label">Exterior</label>
			  <input id="numext" class="form-control" type="text" value="<?php
						if(isset($datosCliente)){echo $datosCliente['basicos'][0]['num_ext'];}?>">
			</div>
			<div class="col-sm-3">
			  <label class="control-label">Interior</label>
			  <input id="numint" class="form-control" type="text" value="<?php
						if(isset($datosCliente)){echo $datosCliente['basicos'][0]['num_int'];}?>">
			</div>
		  </div>

		  <div class="row">
			<div class="col-sm-2">
			  <label class="control-label">Colonia</label>
			  <input id="colonia" class="form-control" type="text" value="<?php
						if(isset($datosCliente)){echo $datosCliente['basicos'][0]['colonia'];}?>">
			</div>
			<div class="col-sm-2">
				<label class="control-label">Código Postal</label>
				<input id="cp" class="form-control numeros" type="text" value="<?php
						if(isset($datosCliente)){echo $datosCliente['basicos'][0]['cp'];}?>">
			</div>
			<div class="col-sm-2">
				<div class="row">
					<div class="col-sm-8">
						<label class="control-label"><span style="color:red;">*</span> País</label>
						<select id="selectPais" class="form-control" >
							<option value="<?php if(isset($datosCliente)){echo $datosCliente['basicos'][0]['idPais'];} ?>">
								<?php if(isset($datosCliente)){echo $datosCliente['basicos'][0]['descPais'];} ?>
							</option>
                        </select>
					</div>
					<div class="col-sm-1">
						<label class="control-label"></label>
						<button type="button" data-toggle="modal" data-target="#nuevoPais" class="btn btn-success">
							<i class="fa fa-plus" aria-hidden="true"></i>
						</button>
					</div>
				</div>
			</div>
<!-- Modal -->
<div class="modal fade" id="nuevoPais"  role="dialog" aria-labelledby="nuevoPais" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" >Agregar nuevo País</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <input type="text" id="inputNuevoPais" class="form-control" placeholder="Nombre de país">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" data-dismiss="modal" id="btnNuevoPais">Aceptar</button>
      </div>
    </div>
  </div>
</div>
			<div class="col-sm-2">
				<div class="row">
					<div class="col-sm-8">
						<label class="control-label">Estado</label>
						<select id="selectEstado" class="form-control" >
							<option value="<?php if(isset($datosCliente)){echo $datosCliente['basicos'][0]['idEstado'];} ?>">
								<?php if(isset($datosCliente)){echo $datosCliente['basicos'][0]['descEstado'];} ?>
							</option>
                        </select>
					</div>
					<div class="col-sm-1">
						<label class="control-label"></label>
						<button type="button" data-toggle="modal" data-target="#nuevoEstado" class="btn btn-success">
							<i class="fa fa-plus" aria-hidden="true"></i>
						</button>
					</div>
				</div>
			</div>
<!-- Modal -->
<div class="modal fade" id="nuevoEstado"  role="dialog" aria-labelledby="nuevoEstado" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" >Agregar nuevo Estado</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
     	<select id="selectPais2" class="form-control" ></select>
        <input type="text" id="inputNuevoEstado" class="form-control" placeholder="Nombre de estado">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" data-dismiss="modal" id="btnNuevoEstado">Aceptar</button>
      </div>
    </div>
  </div>
</div>
			<div class="col-sm-2">
				<div class="row">
					<div class="col-sm-8">
						<label class="control-label">Municipio</label>
						<select id="selectMunicipio" class="form-control" >
							<option value="<?php if(isset($datosCliente)){echo $datosCliente['basicos'][0]['idMunicipio'];} ?>">
								<?php if(isset($datosCliente)){echo $datosCliente['basicos'][0]['descMunicipio'];} ?>
							</option>
                        </select>
					</div>
					<div class="col-sm-1">
						<label class="control-label"></label>
						<button type="button" data-toggle="modal" data-target="#nuevoMunicipio" class="btn btn-success">
							<i class="fa fa-plus" aria-hidden="true"></i>
						</button>
					</div>
				</div>
			</div>
<!-- Modal -->
<div class="modal fade" id="nuevoMunicipio"  role="dialog" aria-labelledby="nuevoMunicipio" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" >Agregar nuevo Municipio</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <select id="selectPais3" class="form-control" ></select>
    	<select id="selectEstado3" class="form-control" ></select>
        <input type="text" id="inputNuevoMunicipio" class="form-control" placeholder="Nombre de municipio">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" data-dismiss="modal" id="btnNuevoMunicipio">Aceptar</button>
      </div>
    </div>
  </div>
</div>
			<div class="col-sm-2">
			  <label class="control-label">Ciudad</label>
			  <input id="ciudad" class="form-control" type="text" value="<?php
						if(isset($datosCliente)){echo $datosCliente['basicos'][0]['ciudad'];}?>">
			</div>
		  </div>

		  <div class="row">
			<div class="col-sm-3">
			  <label class="control-label">Email</label>
			  <input id="email" class="form-control" type="text" value="<?php
						if(isset($datosCliente)){echo $datosCliente['basicos'][0]['email'];}?>">
			</div>
			<div class="col-sm-3">
			  <label class="control-label">Celular</label>
			  <input id="celular" class="form-control numeros" type="text" value="<?php
						if(isset($datosCliente)){echo $datosCliente['basicos'][0]['celular'];}?>">
			</div>
			<div class="col-sm-3">
			  <label class="control-label">Teléfono 1</label>
			  <input id="tel1" class="form-control numeros" type="text" value="<?php
						if(isset($datosCliente)){echo $datosCliente['basicos'][0]['telefono1'];}?>">
			</div>
			<div class="col-sm-3">
			  <label class="control-label">Teléfono 2</label>
			  <input id="tel2" class="form-control numeros" type="text" value="<?php
						if(isset($datosCliente)){echo $datosCliente['basicos'][0]['telefono2'];}?>">
			</div>
		  </div>
<!--
		  <div class="row">
			    <div class="col-sm-3" style="height:130px;">
			        <div class="form-group">
			        	<label class="control-label">Fecha de cumpleaños
						</label>
			            <div class='input-group date' id='datetimepicker10'>
			                <input id='cumpleanos' type='text' class="form-control" placeholder="dd / mm" value="<?php
						if(isset($datosCliente)){echo $datosCliente['basicos'][0]['cumpleanos'];}
					?>"/>
			                <span class="input-group-addon">
			                    <span class="glyphicon glyphicon-calendar">
			                    </span>
			                </span>
			            </div>
			            <script type="text/javascript">
					        $(function () {
					            $('#datetimepicker10').datetimepicker({
					                viewMode: 'months',
					                format: 'DD/MM'
					            });
					        });
					    </script>
			        </div>
			    </div>
		  </div>-->

		<!--  <div class="row">
			<div class="col-sm-3">
			  <label class="control-label">RFC</label>
			  <input id="rfc" class="form-control" type="text" value="<?php
						if(isset($datosCliente)){echo $datosCliente['basicos'][0]['rfc'];}?>">
			</div>
			<div class="col-sm-3">
			  <label class="control-label">CURP</label>
			  <input id="curp" class="form-control" type="text" value="<?php
						if(isset($datosCliente)){echo $datosCliente['basicos'][0]['curp'];}?>">
			</div>
		  </div> -->

		<!--  <div class="row">
			<div class="col-sm-3">
			  <label class="control-label">Dias de Credito</label>
			  <input id="diasCredito" class="form-control" type="text" value="<?php
						if(isset($datosCliente)){echo $datosCliente['basicos'][0]['dias_credito'];}?>">
			</div>
			<div class="col-sm-3">
			  <label class="control-label">Limite de Credito</label>
			  <input id="limiteCredito" class="form-control" type="text" value="<?php
						if(isset($datosCliente)){echo $datosCliente['basicos'][0]['limite_credito'];}?>">
			</div>
			<div class="col-sm-3">
			  <label class="control-label">Moneda</label>
			  <!--<input id="moneda" class="form-control" type="text" value=""> -->
			 <!-- <select id="moneda" class="form-control">
				<?php

				  foreach ($moneda as $keyMon => $valueMon) {
					echo '<option value="'.$valueMon['coin_id'].'">'.$valueMon['description'].'/'.$valueMon['codigo'].'</option>';
				  }

				?>
			  </select>
			</div>
			<div class="col-sm-3"></div>
		  </div> -->



		  <div class="row"><br>
			<div class="col-sm-10"></div>
			<!--<div class="col-sm-1"><button type="button" class="btn btn-primary" onclick="guardaCliente();"><span class="glyphicon glyphicon-floppy-disk"></span> Guardar</button></div> -->
		  </div>
		</div><!-- Fin del div basicos -->
		<div id="ventasPortal" class="tab-pane fade" style="margin-top: 10px;">
		  
			<table class="table table-bordered table-hover" id="tableSales">
                <thead>
                    <tr>
                        <th>Folio</th>
                        <!--<th>Tipo Doc.</th>-->
                        <th>Fecha</th>
                        <!--<th>Cliente</th>-->
                        <!--<th>Empleado</th>-->
                        <th>Sucursal</th>
                        <th>Estatus</th>
                        <th>Impuestos</th>
                        <th>Monto</th>
                        <!--<th>Acciones</th>-->
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
	
			
		</div><!-- fin del Tab de facturacion -->
		<div id="datosCredito" class="tab-pane fade">
		  <div class="row">
		  <div class="col-sm-3">
			<label>Tipo de Crédito</label>
			<select name="tipoDeCredito" id="tipoDeCredito" class="form-control">
			  <option value="0">-Selecciona un Crédito-</option>
			  <?php
				foreach ($tipoCredito as $keyCre => $valueCre) {
					if(isset($datosCliente)){
					  if($datosCliente['basicos'][0]['id_tipo_credito']==$valueCre['id']){
						  echo '<option value="'.$valueCre['id'].'" selected>'.$valueCre['nombre'].'/'.$valueCre['clave'].'</option>';
					  }
					}
					echo '<option value="'.$valueCre['id'].'">'.$valueCre['nombre'].'/'.$valueCre['clave'].'</option>';
				}

			  ?>
			</select>
		  </div>
		  </div>
		  <div class="row">
			<div class="col-sm-3">
			  <label class="control-label">Días de Crédito</label>
			  <input id="diasCredito" class="form-control numeros" type="text" value="<?php
						if(isset($datosCliente)){echo $datosCliente['basicos'][0]['dias_credito'];}?>">
			</div>

			<div class="col-sm-3">
			  <label>Saldo</label>
			  <input id="saldo" type="text" class="form-control numeros" readonly>
			</div>
		  </div>
		  <div class="row">
			<div class="col-sm-3">
			  <label class="control-label">Límite de Crédito</label>
			  <input id="limiteCredito" class="form-control numeros" type="text" value="<?php
						if(isset($datosCliente)){echo $datosCliente['basicos'][0]['limite_credito'];}?>">
			</div>
		  </div>
		  <?php
				$x = '';
				$y = '';
				  if(isset($datosCliente)){
					  if($datosCliente['basicos'][0]['permitir_vtas_credito']==1){
						$x = 'checked';
					  }else{
						$x = '';
					  }
					  if($datosCliente['basicos'][0]['permitir_exceder_limite']==1){
						$y = 'checked';
					  }else{
						$y= '';
					  }
				  }


		  ?>
		  <div class="row">
			<div class="col-sm-3">
			  <div class="checkbox">
				<label>
				  <input id="checkVc" type="checkbox" value="" <?php echo $x; ?>>
				  Permitir ventas a crédito
				</label>
			  </div>
			  <div class="checkbox disabled">
				<label>
				  <input id="checkLc" type="checkbox" value="" <?php echo $y; ?>>
				  Permitir exceder límite de crédito
				</label>
			  </div>
			</div>
		  </div>
		  <div class="row">
			<div class="col-sm-3">
			  <label>Descuento por pronto pago (%)</label>
			  <input id="descuentoPP" type="text" class="form-control numeros" value="<?php
						if(isset($datosCliente)){echo $datosCliente['basicos'][0]['dcto_pronto_pago'];}?>">
			</div>
			<div class="col-sm-3">
			  <label>Intereses Moratorios (%)</label>
			  <input id="interesesMoratorios" type="text" class="form-control numeros" value="<?php
						if(isset($datosCliente)){echo $datosCliente['basicos'][0]['intereses_moratorios'];}?>">
			</div>
			<div class="col-sm-3"></div>
			<div class="col-sm-3"></div>
		  </div>
		  <div class="row">
			<div class="col-sm-3">
			  <label class="control-label">Moneda</label>
			  <!--<input id="moneda" class="form-control" type="text" value=""> -->
			  <select id="moneda" class="form-control">
				<?php

				  foreach ($moneda as $keyMon => $valueMon) {
					if(isset($datosCliente)){
					  if($datosCliente['basicos'][0]['id_lista_precios']==$valueMon['coin_id']){
						  echo '<option value="'.$valueMon['coin_id'].'" selected>'.$valueMon['description'].'/'.$valueMon['codigo'].'</option>';
					  }
					}
					echo '<option value="'.$valueMon['coin_id'].'">'.$valueMon['description'].'/'.$valueMon['codigo'].'</option>';
				  }

				?>
			  </select>
			</div>
			<div class="col-sm-3">
			  <label class="control-label">Lista de Precio</label>
			  <select id="listaPrecio" class="form-control">
				<option value="0" selected>-Ninguna-</option>
				<?php
				foreach ($listaPre as $key1 => $value1) {
				  if(isset($datosCliente)){
					  if($datosCliente['basicos'][0]['id_lista_precios']==$value1['id']){
						  echo '<option value="'.$value1['id'].'" selected>'.$value1['nombre'].'</option>';
					  }else{
					  	 echo '<option value="'.$value1['id'].'">'.$value1['nombre'].'</option>';
					  }
					  
				  }				  
				}
				?>
			  </select>
			</div>
		  </div>
		  <div class="row">
			<div class="col-sm-3">
			  <label>Banco</label>
			  <select id="banco" class="form-control">
				<?php
				foreach ($bancos as $keyBan => $valueBan) {
				  if(isset($datosCliente)){
					  if($datosCliente['basicos'][0]['idBanco']==$valueBan['idbanco']){
						  echo '<option value="'.$valueBan['idbanco'].'" selected>'.$valueBan['nombre'].'/'.$valueBan['Clave'].'</option>';
					  }
				  }

				  echo '<option value="'.$valueBan['idbanco'].'">'.$valueBan['nombre'].'/'.$valueBan['Clave'].'</option>';
				}
				?>
			  </select>
			</div>
			<div class="col-sm-3">
			  <label>Número de Cuenta</label>
			  <input type="text" id="cuentaBanc" class="form-control" value="<?php
						if(isset($datosCliente)){echo $datosCliente['basicos'][0]['numero_cuenta_banco'];}?>">
			</div>
			<div class="col-sm-3"></div>
		  </div>
		</div><!-- Fin de tab credito -->
		<div id="datosComisiones" class="tab-pane fade">
		  <div class="row">
			<div class="col-sm-3">
			  <label>Comisión de Venta (%)</label>
			  <input id="comisionVenta" type="text" class="form-control" value="<?php
						if(isset($datosCliente)){echo $datosCliente['basicos'][0]['comision_vta'];}?>">
			</div>
			<div class="col-sm-3">
			  <label>Comisión de Cobranza (%)</label>
			  <input id="comisionCobranza" type="text" class="form-control" value="<?php
						if(isset($datosCliente)){echo $datosCliente['basicos'][0]['comision_cobranza'];}?>">
			</div>
		  </div>
		  <div class="row">
			<div class="col-sm-3">
			  <label>Vendedor</label>
			  <select id="vendedor" class="form-control">
				<option value="0">-Selecciona Vendedor-</option>
				<?php
				$empleados['empleados'];
				  foreach ($empleados['empleados'] as $key8 => $value8) {
					if(isset($datosCliente)){
						if($datosCliente['basicos'][0]['idVendedor']==$value8['idEmpleado']){
							echo '<option value="'.$value8['idEmpleado'].'" selected>'.$value8['nombreEmpleado'].' '.$value8['apellidoMaterno'].'</option>';
						}
					}

					echo '<option value="'.$value8['idEmpleado'].'">'.$value8['nombreEmpleado'].' '.$value8['apellidoMaterno'].'</option>';
				  }
				?>


				?>
			  </select>
			</div>
		  </div>
		</div><!-- Fin del tab de comisiones  -->
		<div id="saldos" class="tab-pane fade" style="margin-top:10px;">
		  <div class='row' id='listaCargosFac'>
		      <div class="col-xs-12 col-md-12 table-responsive">
		      <div id='saldos_div2'>&nbsp;&nbsp;&nbsp;<span style='font-size:14px;'>Saldo total del cliente</span> <input type='text' id='total_saldos' readonly="readonly" style='text-align:center;font-weight:bold;font-size:16px;'></div>
		        <table id="tabla-carfac" class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
		          <thead>
		            <tr>
		              <th>Fecha de Cargo / Factura</th>
		              <th>Fecha de Vencimiento</th>
		              <th>Concepto</th>
		              <th>Monto</th>
		              <th>Saldo Abonado</th>
		              <th>Saldo Actual</th>
		              <th>Estatus</th>
		              <th>OV</th>
		            </tr>
		          </thead>
		          <tbody id='trs_carfac'>
		          </tbody>
		        </table>
		      </div>

		    </div>
		</div><!-- Fin del tab de saldos-->
		<div id="datosContables" class="tab-pane fade">
		  <div class="row">
		   <div class="col-sm-4">
			  <label>Cuenta Contable</label>
			  <select id="cuentaCont" class="form-control">
				<option value="0">-Selecciona Cuenta-</option>
			  <?php

				  foreach ($cuentas as $keyCont => $valueCont) {
					if(isset($datosCliente)){
					  if($datosCliente['basicos'][0]['cuenta']==$valueCont['account_id']){
						  echo '<option value="'.$valueCont['account_id'].'" selected>'.$valueCont['description'].' ('.$valueCont['manual_code'].')'.'</option>';
					  }
					}
					echo '<option value="'.$valueCont['account_id'].'">'.$valueCont['description'].' ('.$valueCont['manual_code'].')'.'</option>';
				  }

				?>
			  </select>
		   </div>
		  </div>
		</div><!-- Fin del tab Datos Contable-->
		<div id="cotizaciones" class="tab-pane fade">
		  <table class="table table-bordered table-hover" id="tableCotis">
                <thead>
                    <tr>
                        <th>No Cotizacion</th>
                        <!--<th>Tipo Doc.</th>-->
                        <th>Fecha</th>
                        <!--<th>Cliente</th>-->
                        <!--<th>Empleado</th>-->
                        <th>Total</th>
                        <th>Estatus</th>
                        <th>Acciones</th>
                        <!--<th>Acciones</th>-->
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($cotizaciones['rows'] as $k => $v) { ?>
					<tr>
					<td><?php echo $v['id']; ?></td>
					<td><?php echo $v['fff']; ?></td>
					<?php 
					if($v['activo']==0){
						$es='<span class="label label-warning" style="cursor:pointer;">Nueva</span>';
					}
					if($v['activo']==1){
						$es='<span class="label label-success" style="cursor:pointer;">OV Autorizada</span>';
					}
					if($v['activo']==2){
						$es='<span class="label label-default" style="cursor:pointer;">Inactiva</span>';
					}
					if($v['activo']==3){
						$es='<span class="label label-success" style="cursor:pointer;">OV activa</span>';
					}
					if($v['activo']==4){
						$es='<span class="label label-success" style="cursor:pointer;">OK recibida ok</span>';
					}
					if($v['aceptada']==1){
						$es.=' <span class="label label-success" style="cursor:pointer;">Aceptada por cliente</span>';
					} 
					if($v['activo']==6){
						if($v['status']==1){
							$es='<span class="label label-info" style="cursor:pointer;">Venta en Caja (PMP'.$v['idcotpe'].')</span>';
						}else if($v['status']==5){
							$es='<span class="label label-success" style="cursor:pointer;">Venta realizada en caja</span>';
						}
					}

					if($v['cadenaCoti']!=null){
			            $nuevos='';
			            if($v['cnuevos']>0){
			              $nuevos='('.$r['cnuevos'].')';
			            }
			            $ccc=' <button style="margin-top:4px;"  onclick="vercomcli(\''.$v['cadenaCoti'].'\');" class="btn btn-default btn-xs">Comentarios '.$nuevos.'</button>';
			        }else{
			        	$ccc='';
			        }

					?>
					<td><?php echo $v['importe']; ?></td>
					<td><?php echo $es ?></td>
					<td><?php echo $ccc ?></td>
					</tr>
                <?php } ?>

                </tbody>
            </table>
		</div>
		<div id="accesoPortal" class="tab-pane fade">
		  <div class="row">
		  <div class="col-sm-12" style="margin-top: 20px;">
			   <div class="col-sm-2">
			   		<b>Correo:</b>
			   </div>  
			   <div class="col-sm-10">
			   		<input style="width:300px;" id="correoportal" class="form-control" type="text" value="<?php
						if(isset($datosCliente)){echo $datosCliente['basicos'][0]['email'];}?>" readonly>
			   </div>

		   </div>
		   <div class="col-sm-12" style="margin-top: 10px;">
			   <div class="col-sm-2">
			   		<b>Usuario:</b>
			   </div>  
			   <div class="col-sm-10">
			   		<input style="width:300px;" id="userportal" class="form-control" type="text" value="usuarioCliente_<?php
						if(isset($datosCliente)){echo $datosCliente['basicos'][0]['id'];}?>" readonly>
			   </div>

		   </div>
		   <div class="col-sm-12" style="margin-top: 10px;">
			   <div class="col-sm-2">
			   		<b>Contraseña:</b>
			   </div>  
			   <div class="col-sm-10">
			   		<input style="width:300px;" id="passportal" class="form-control" type="password" value="<?php echo randpass(); ?>" readonly>
			   </div>
			   
		   </div>

		   <div class="col-sm-12" style="margin-top: 10px;">
			   &nbsp;
		   </div>

		   <div class="col-sm-12" style="margin-top: 10px;">
			   <div class="col-sm-2">
			   		<b>Nueva contraseña:</b>
			   </div>  
			   <div class="col-sm-10">
			   		<input style="width:300px;" id="passportal2" class="form-control" type="password" value="">
			   </div>
			   
		   </div>
		   <div class="col-sm-12" style="margin-top: 10px;">
			   <div class="col-sm-2">
			   		<b>Escribir de nuevo:</b>
			   </div>  
			   <div class="col-sm-10">
			   		<input style="width:300px;" id="passportal3" class="form-control" type="password" value="">
			   </div>
			   
		   </div>

		   <div class="col-sm-12" style="margin-top: 10px;">
		   		<div class="col-sm-2">
			   	&nbsp;
			   </div> 
			   <div class="col-sm-10">
			   		<button id="btnenviarCorreo" txt-original='Modificar contraseña' txt-click='Procesando...' type="button" class="btn btn-default" onclick="modificarPassPortal();">Modificar contraseña</button>
			   </div>  
			   
		   </div>
		
		  </div>
		</div><!-- Fin del tab accesoPortal-->

			<div id="facturas" class="tab-pane fade" style="margin-top: 20px;">
	
			<table class="table table-hover table-fixed" style="background-color:#F9F9F9; border:1px solid #c8c8c8;" id="tableGrid">
                    <thead>
                      <tr>
                        <th>ID</th>
                        <!--<th>Tipo</th>-->
                        <th>Fecha</th>
                        <!--<th>RFC</th>-->
                        <!--<th>Cliente</th>-->
                        <th>UUID</th>
                        <th>Folio</th>
                        <th>ID Venta</th>

                        <th>Total</th>

                        <!--<th>Origen</th>-->
                        <th>Estatus</th>

                        <th>Acciones</th>

                      <!--  <th>Autorizo</th>
                        <th>Estatus</th>
                        <th>Modificar</th> -->
                      </tr>
                    </thead>
                    <tbody>
                       
                    </tbody>
                </table>
                

                </div>




	  </div>  <!-- Fin del div de los tabs -->
  </div><!-- fin de contenedor overflow -->
  </div> <!-- Fin del Panel Body -->
  </div>
</div>
  <!--          Molda Success           -->
  <div id="modalSuccess" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content panel-success">
			<div class="modal-header panel-heading">
				<h4 id="modal-label">Exito!</h4>
			</div>
			<div class="modal-body">
				<p>Tu Cliente se guardo existosamente</p>
			</div>
			<div class="modal-footer">
				<button id="modal-btnconf2-uno" type="button" class="btn btn-default" onclick="quitm();">Continuar</button>
			</div>
		</div>
	</div>
  </div>
</body>
</html>
