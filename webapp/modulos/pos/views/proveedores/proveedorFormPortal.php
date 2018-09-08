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

    function check_file()
    {
        var ext = $('#factura').val();
        ext = ext.split('.');
        ext = ext[1];
        if(ext != 'zip' && ext != 'xml')
        {
            alert("Archivo Inválido \nEl archivo debe tener una extensión xml o zip.");
            $("#factura").val('');
        }
    }

	Number.prototype.format = function() {
        return this.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,");
    };
		$(document).ready(function() {


            $('#fac').submit( function( e ) {
    console.log(this);
    //return false;
    //$('#verif').css('display','inline');
    $.ajax( {
      url: 'ajax.php?c=portalproveedores&f=subeFactura',
      type: 'POST',
      data: new FormData( this ),
      processData: false,
      contentType: false
    } ).done(function( data1 ) {
        console.log(data1)
        //$("#Facturas").dialog('refresh')
        //alert(data1)
           // return false;
           // $('#factura').val('')
            data1 = data1.split('-/-*');
            $('#verif').css('display','none');

            if(parseInt(data1[0]))
            {
                if(parseInt(data1[3]))
                {
                    alert('Los siguientes '+data1[3]+' archivos no son validos: \n'+data1[4])
                    //$('#resultasoc').html('<button id="xmlfile" name="" class="btn btn-danger btn-sx active">XML no asociado</button>');
                }

                if(parseInt(data1[1]))
                {


                    //alert(data1[1]+' Archivos Validados: \n'+data1[2])
                    //$('#resultasoc').html('<button id="xmlfile" name="'+data1[6]+'" class="btn btn-success btn-sx active"><span class="glyphicon glyphicon-ok"></span> XML asociado</button>');
                    datosfac = data1[7].split('##');
                    fac_folio=datosfac[0];
                    fac_fecha=datosfac[1];
                    fac_total=datosfac[2];
                    fac_uuid=datosfac[3];
                    fac_desc_concepto=datosfac[4];
                    fac_subtotal=datosfac[5];
                    xmlfile=data1[6];
                    idoc = $('#idocadju').val();//OC

                    $.ajax({
                        url:"ajax.php?c=portalproveedores&f=a_guardaXmlAdju",
                        type: 'POST',
                        data:{fac_folio:fac_folio,fac_fecha:fac_fecha,fac_total:fac_total,fac_uuid:fac_uuid,concepto:fac_desc_concepto,xmlfile:xmlfile,idoc:idoc,fac_subtotal:fac_subtotal},
                        success: function(r){
                            if(r>0){
                                $('#adju_recep').html('Cargando...');
                                $('#adju_xmls').html('Cargando...');
                                $('#modal-adju').modal('hide');
                                alert('XML adjuntado con exito');
                            }
                        }
                    });

                    /*
                    $('#resultasoc').html('<table w style="margin: 15px 0px 8px; width: 100%; border: 1px solid rgb(236, 236, 236); background-color: rgb(250, 250, 250);">\
    <tbody>\
    <tr style="height:25px;">\
      <th width="100">Folio factura</th>\
      <th width="170">Fecha timbrado</th>\
      <th width="150">$ Total factura</th>\
      <th width="100">Ver xml</th>\
      <th width="50" align="center">Eliminar</th>\
    </tr>\
      <tr>\
      <td width="100">'+fac_folio+'</td>\
      <td width="170">'+fac_fecha+'</td>\
      <td id="fftt" width="150">'+fac_total+'</td>\
      <td width="100"><a id="xmlfile" name="'+data1[6]+'" class="btn btn-success btn-xs" onclick="openxml(\''+data1[6]+'\')">Ver xml</a>\
      </td><td width="50" align="center"><a class="btn btn-danger btn-xs" onclick="quitafactasoc(\''+data1[6]+'\');"><span class="glyphicon glyphicon-remove"></span> Quitar</a>\
    </td></tr>\
  </tbody></table>');

                    if(fac_folio==''){
                        fac_folio=fac_uuid;
                    }

                    $('#nofactrec').val(fac_folio);
                    $('#date_recepcion').val(fac_fecha);
                    $('#impfactrec').val(fac_total);
                    $('#desc_concepto').val(fac_desc_concepto);

                    //alert(fac_folio+' . '+fac_fecha+' . '+fac_total);
                    */
                    
                }
                //alert(parseInt(data1[5]))
                if(parseInt(data1[5])){
                    //abrefacturasrepetidas();
                    
                }else{
                  //  location.reload();
                }
            }
            else
            {
                alert("El archivo zip no cumple con el formato correcto\nDebe llamarse igual que la carpeta que contiene los xmls.\nSólo debe contener una carpeta.");

                //$('#resultasoc').html('<button id="xmlfile" name="" class="btn btn-danger btn-sx active">XML no asociado</button>');
            }
        
    
    });
    e.preventDefault();
  });


			$('#modal-adju-uno').on('click',function(){
	            $('#adju_recep').html('Cargando...');
	            $('#adju_xmls').html('Cargando...');
	            $('#modal-adju').modal('hide');
	        });

			
		  $('#numeros').numeric();
		  $('#tipoClas').select2({'width':'100%'});
		  $('#tipoDeCredito').select2({'width':'100%'});
		  $('#moneda').select2({'width':'100%'});
		  $('#banco').select2({'width':'100%'});
		  $('#vendedor').select2({'width':'100%'});
		  $('#cuentaCont').select2({'width':'100%'});
		  $(".numeros").numeric();


		  var table = $('#tableVirbac').DataTable();
                table.destroy();                
                $('#tableVirbac').DataTable( {
            dom: 'Bfrtip',
            buttons: [ 'pageLength', 'excel'],
            language: {
                buttons: {
                    pageLength: "Mostrar %d filas"
                },
                search: "Buscar:",
                lengthMenu:"Mostrar _MENU_ elementos",
                zeroRecords:"No hay datos",
                infoEmpty:"",
                info:"Mostrando del _START_ al _END_ de _TOTAL_ elementos",
                paginate: {
                    first:      "Primero",
                    previous:   "Anterior",
                    next:       "Siguiente",
                    last:       "Último"
                },
             },

                    "aaSorting": [[0,'desc']],
                    ajax: {
                        beforeSend: function() {  }, //Show spinner
                        complete: function() { $('#listareq_load').css('display','none'); }, //Hide spinner
                        url:"ajax.php?c=portalproveedores&f=a_listaOrdenesRecepcion&id=<?php echo $idProveedor; ?>",
                        type: "POST",
                        data: function ( d )    {

                            //d.site = $("#nombredeusuario").val();
                        }  
                    }
                });

/*
		  $('#tableVirbac').DataTable({
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
        }); */


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

		  // buscarPortal();
		  // buscarFacturas();
		  // listaCargosFacturas();
		});

		function adjuntarxml(idoc){
        deten=0;
        /*
        $.ajax({
            async:false,
            url:"ajax.php?c=compras&f=a_verificarPagos",
            type: 'POST',
            dataType: 'json',
            data:{idoc:idoc},
            success: function(r){
                if(r>0){
                    alert('Esta orden de compra ya tiene pagos realizados, no puedes subir facturas.');
                    deten=1;
                }
            }
        });
        */
        if(deten==1){
            return false;
        }
        $('#modal-adju').modal({
            backdrop: 'static',
            keyboard: false, 
            show: true
        });
        $('#idocadju').remove();
        $('body').append('<input id="idocadju" type="hidden" value="'+idoc+'">');
        $.ajax({
            url:"ajax.php?c=portalproveedores&f=a_adjuntarxml",
            type: 'POST',
            dataType: 'json',
            data:{idoc:idoc},
            success: function(r){
                console.log(r);
                tabla='<table>\
                        <tr>\
                        <th width="420">No Recepcion</th>\
                        <th width="160">Fecha recepcion</th>\
                        <th width="100">Monto</th>\
                        </tr>';
                trecep='';
                $.each(r.rows, function(i,v) {
                    tabla+='<tr>\
                        <td style="padding: 2px;">ID Recepcion - '+v.idr+'</td>\
                        <td style="padding: 2px;">'+v.fechar+'</td>\
                        <td style="padding: 2px;">$'+v.total+'</td>\
                        </tr>';
                });
                tabla+='<tr>\
                        <td style="padding: 2px;">&nbsp;</td>\
                        <td style="padding: 2px;">&nbsp;</td>\
                        <td style="padding: 2px;"><b>$'+r.total+'</b></td>\
                        </tr>';
                tabla+='</table>';

                tabla2='<table id="tablaxmladju">\
                        <tr>\
                        <th width="420">Xml archivo</th>\
                        <th width="160">Fecha subida</th>\
                        <th width="100">Monto</th>\
                        </tr>';


                $.each(r.xmls, function(i,v) {
                    tabla2+='<tr>\
                        <td style="padding: 2px;">'+v.xmlfile+'</td>\
                        <td style="padding: 2px;">'+v.fecha_subida+'</td>\
                        <td style="padding: 2px;">$'+v.imp_factura+'</td>\
                        </tr>';
                });
                tabla2+='<tr>\
                        <td style="padding: 2px;">&nbsp;</td>\
                        <td style="padding: 2px;">&nbsp;</td>\
                        <td style="padding: 2px;"><b>$'+r.totalxmls+'</b></td>\
                        </tr>';

                $('#adju_header').html('Orden de compra <b>'+idoc+'</b>');
                $('#adju_recep').html(tabla);
                if(r.xmls==0){
                    $('#adju_xmls').html('No hay facturas adjuntas');
                }else{
                    $('#adju_xmls').html(tabla2);
                }
                
            }
        });
    }


		function verPdf(id){
	window.open("../../modulos/facturas/"+id+".pdf");

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
																		'<td><input type="checkbox" class="checkPro" value="'+x.datosTimbrado.UUID+'"></td>'+
																		'<td>'+val.id+'</td>'+
		                                //'<td>'+( val.tipoComp == "C" ? "NC" : val.tipoComp )+'</td>'+
		                                '<td>'+val.fecha+'</td>'+
		                                //'<td>'+x.Receptor.rfc+'</td>'+
		                                //'<td>'+x.Receptor.nombre+'</td>'+
		                                '<td>'+x.datosTimbrado.UUID+'</td>'+
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
  <div class="panel-heading">
  	<h5>Proveedor<?php
	if(isset($datosCliente)){echo ' ('.$datosCliente['basicos'][0]['razon_social'].')';}?>
	</h5>
   </div>
  <div class="panel-body">
	<div style="heigth:400px;">
	  <div id="tabsCliente">
		<ul class="nav nav-tabs">
		  <li class="active"><a data-toggle="tab" href="#ocv">Ordenes de compra (Virbac)</a></li>
		  <!--<li><a data-toggle="tab" href="#ventasPortal">Ventas</a></li>
		  <li><a data-toggle="tab" href="#saldos">Saldos</a></li>
		  <li><a data-toggle="tab" href="#facturas">Facturas</a></li>
		  <li><a data-toggle="tab" href="#cotizaciones">Cotizaciones</a></li>
		  <li><a data-toggle="tab" href="#accesoPortal">Datos de acceso</a></li>-->
		</ul>
	  </div>
	  <div class="tab-content" style="height:450px;">
		<div id="ocv" class="tab-pane fade in active" style="margin-top: 10px;">
			<table class="table table-bordered table-hover" id="tableVirbac">
                <thead>
                    <tr>
                       	<th>No. OC.</th>
                        <th>Fecha</th>
                        <th>Proveedor</th>
                        <th>Solicitante</th>
                        <th>Fecha entrega</th>
                        <th>Almacen</th>
                        <th>Total</th>
                        <th>Prioridad</th>
                        <th>Estatus</th>
                        <th class="no-sort" style="text-align: center;">Acciones</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>

		</div>
	  </div>
	</div>
   </div>
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

  <div id="modal-adju" class="modal sfade">
	    <div class="modal-dialog">
	        <div class="modal-content panel-default">
	            <div class="modal-header panel-heading">
	                <h4 id="modal-label">Adjuntar XML'S</h4>
	            </div>
	            <div id="bodyespecialxx" class="modal-body">
	                <div id="adju_header" class="col-sm-12" style="padding:10px 0 10px 0;">
	                    &nbsp;
	                </div>
	                <div class="col-sm-12" style="padding:10px 0 10px 0;">
	                    <b>Recepciones</b>
	                </div>
	                <div id="adju_recep" class="col-sm-12" style="padding:10px 0 10px 0;">
	                    Cargando...
	                </div>
	                <div class="col-sm-12" style="padding:10px 0 10px 0;">
	                    <b>Xml's Adjuntos</b>
	                </div>
	                <div id="adju_xmls" class="col-sm-12" style="padding:10px 0 10px 0;">
	                    Cargando...
	                </div>

	                <div class="col-sm-12" style="padding:10px 0 10px 0;">
	                    <b>Subir archivos xml</b>
	                </div>
	                <div id="divxmls" class="col-sm-12" style="padding:0px;">
	                <div class="form-group"  style="padding:0px;">
	                    
	                    <form name='fac' id='fac' action='' method='post' enctype='multipart/form-data'>
	                    <div class="col-sm-10" style="padding:0px;">
	                        
	                        <input type='file' name='factura[]' id='factura' onchange='check_file()'>
	                        <input type='hidden' name='plz' id='plz' value='lala'>
	                        
	                    </div>
	                    <div class="col-sm-10" style="margin-top:10px; padding:0px;">
	                        <input type='submit' id='buttonFactura' value='Asociar Factura'>
	                        <div id="resultasoc" style="margin-top:10px;">
	                            
	                        </div>
	                        
	                        <span id='verif' style='color:green;display:none;'>Verificando...</span>
	                    </div>
	                    </form>
	                </div>
	                </div>

	                <div class="row">
	                </div>
	            </div>
	            <div class="modal-footer">
	                <button id="modal-adju-uno" type="button" class="btn btn-default">Salir</button> 
	            </div>
	        </div>
	    </div> 
	</div> 

</body>
</html>
