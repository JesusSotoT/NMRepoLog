

function abreProveedoresLista(id)
{
  $('#ProveedoresLista').modal('show');
  $.post("ajax.php?c=CaptPolizas&f=getProviderList",
    {
      IdPoliza: id 
    },
       function(data)
       {
          $('#ProveedoresListaCuerpo').html(data).find('table').addClass('table');
          if(parseFloat($("#suma-importes").val()) > parseFloat($("#suma-abonos-bancos").val()))
          {
            alert("[ALERTA] La suma de los importes es mayor al de la poliza.");
          }
          if(!parseFloat($('#tienePeriodoAcreditamiento').val()))
          {
            //alert('No tiene')
            $('#periodo_acreditamiento').val('0');
          }else
          {
            //alert('Si tiene')
            $('#periodo_acreditamiento').val($('#tienePeriodoAcreditamiento').val());
          }

          if(!parseFloat($('#tieneEjercicioAcreditamiento').val()))
          {
            //alert('No tiene')
            $('#ejercicio_acreditamiento').val('0')
          }else
          {
            //alert('Si tiene')
            $('#ejercicio_acreditamiento').val($('#tieneEjercicioAcreditamiento').val());
          }

       });

}

function gAbreProveedores(){
  if($('#importe').val() > 0 && $('#ProveedoresSelect').val() != '0' && $('.iva:checked').val() && parseFloat($('#IVANoAcreditable').val()) <= parseFloat($('#importeIVA').val()))
  {
    guardarProveedores($('#idx').val(),$('#idr').val());
    abreProveedoresLista($('#idpoliza').val());
    $("#Proveedores").modal('hide');
  }
  else
  {
    alert('Hay un error en la captura, \n\nCausas:\n\n- Agregue un provedor. \n\n- Agregue un importe.\n\n- Seleccione un IVA. \n\n- La retencion del IVA no puede ser mayor al importe del IVA\n\n- El IVA no acreditable no puede ser mayor al importe del IVA.');
  }
}

function abreProveedores(idPrv,id)
{ 
  //se abre div de contenido mientras carga la informacion
  $('#loading').show();
  $('#conte').hide();
  //window.setTimeout(function(){$('#loading').hide();$('#conte').show();},3000);
  
  /*var importe = $('#Abonos b').html();
  importe = importe.replace('$','');
  importe = importe.replace(',','');*/
  $('#referencia').val('');
   $('#importeBase').val('0.00');
   $('#importeIVA').val('0.00');
   $('#IVANoAcreditable').val('0.00');
   $('#otrasErogaciones').val('0.00');


   
//  $('#Proveedores').dialog('open');
if(id)
{
  $.post("ajax.php?c=CaptPolizas&f=getProviderAllInfo",
      {
        Idr: id
      },
      function(data)
      {
        var datos = data.split('/');
        if(parseInt(datos[7]))
        {
          $('#aplica').prop('checked','true');
        }
        else
        {
          $('#aplica').removeAttr('checked');
        }
        $('#referencia').val(datos[0]);
        $('#importe').val(datos[1]);
        $('#importeBase').val(datos[2]);
        $('#otrasErogaciones').val(datos[3]);
        $('#retiva').val(datos[4]);
        $('#retisr').val(datos[5]);
        $('#IVANoAcreditable').val(datos[6]);


      });

var idR = $("#idR-"+id).offset();
var idPos = idR.top;

//$('#Proveedores').dialog({position:['center',idPos-200]});

}
else
{
  $.post("ajax.php?c=CaptPolizas&f=getAbonosBancos",
      {
    
         IdPoliza: $('#idpoliza').val()
         
      },
      function callback(data)
      {
        var resto = parseFloat(data)-parseFloat($("#suma-importes").val());
        $('#importe').val(resto.toFixed(2));
        //$('#Proveedores').dialog({position:['center',150]});
      });
}

  
  $('#idr').val(id);

  $("#ProveedoresSelect").select2({
         width : "150px"
        }).select2("val", idPrv);
        

  
  modificaInfoProv(0);


  $('#Proveedores').modal('show');
  

}

function modificaInfoProv(esopcion)//Se ejecuta cuando dan click al combo de proveedores
{
  $('#idx').val($('#ProveedoresSelect').val());
     var idpoli=$('#idpoliza').val();
  $.post("ajax.php?c=CaptPolizas&f=getProviderTax",
      {
        IdPrv: $('#idx').val(),
        Idr: $('#idr').val()
      },
      function callback(data)
      {
        $('#ivas').html(data);
         $.post("ajax.php?c=CaptPolizas&f=getProviderInfo",
      {
        IdPrv: $('#idx').val()
      },
      function callback(data)
      {
        var datos = data.split("/");
        $('#retivaNum').html(datos[0]);
        $('#retisrNum').html(datos[1]);
  //$('#idpoliza').val()
  
  		 $.post("ajax.php?c=CaptPolizas&f=ejercicioietu",
	      {
	        IdPrv: $('#idx').val(),
	        idpoli:$('#idpoliza').val(),
	        ejer:$('#ejercicio_acreditamiento').val()
	      },
	      function(resp)
	      {		      		
	      	if(resp!=0){
	      		var separa = resp.split('//');
	      		$('#ietumuestra').show();
	      		$('#acreditaietu').show();
	      		$('#ietu').empty();$('#acreietu').empty();
	      		$('#ietu').html(separa[1]);
	      		$('#acreietu').val(separa[0]);
	      	}else{
	      		$('#ietumuestra').hide(); $('#acreditaietu').hide();
	      		$('#acreietu').val(0);
	      	}
	      });
  
        importeIVA();

        if(esopcion)
        {
          modificaImpuestos();
         idpoli= 0;
        }
        
         $.post("ajax.php?c=CaptPolizas&f=ejercicioietu",
	      {
	        IdPrv: $('#idx').val(),
	        idpoli:idpoli,
	        ejer:$('#ejercicio_acreditamiento').val()
	      },
	      function(resp)
	      {		  
	      	    		
	      	if(resp!=0){
	      		var separa = resp.split('//');
	      		$('#ietumuestra').show();
	      		$('#acreditaietu').show();
	      		$('#ietu').empty();$('#acreietu').empty();
	      		$('#ietu').html(separa[1]);
	      		$('#acreietu').val(separa[0]);
	      		if(separa[0] == 0){
	      		 $("#acreietu").val($('#importeBase').val());
	      		}
	      	}else{
	      		$('#ietumuestra').hide();
	      		$('#acreditaietu').hide();
	      		$('#acreietu').val(0);
	      		
	      	}
	      });
        importeAntesRetenciones(1);
        
        //Cuando carga todos los datos se cierra el loading y se abre la tabla de contenido
        $('#loading').hide();
        $('#conte').show();
        
       
      });
      });

}

function guardarProveedores(IdProv,Idr)
{
// if(!$('#acreietu').val()){
	// $('#acreietu').val(0);
// }
  $.post("ajax.php?c=CaptPolizas&f=GuardarProveedores",
      {
        Idr: Idr,
        Poliza: $('#idpoliza').val(), 
        IdPrv: IdProv,
        Referencia: $('#referencia').val(),
        Tasa: $('.iva:checked').attr('idbd'),
        Importe: $('#importe').val(),
        ImporteBase: $('#importeBase').val(),
        OtrasErogaciones: $('#otrasErogaciones').val(),
        IVARetenido: $('#retiva').val(),
        IsrRetenido: $('#retisr').val(),
        IvaPagadoNoAcreditable: $('#IVANoAcreditable').val(),
        Aplica: $('#aplica').prop('checked'),
        Ejercicio: $('#IdExercise').val(),
        ietu:$('#ietu').val(),
        acreditaietu:$('#acreietu').val(),
        periodo_acreditamiento:$("#periodo_acreditamiento").val()
      },
      function(data)
      {
        alert(data);
      });
}
function modificaImpuestos()
{
  var porcientoImpuesto;
  var importeBase;
//alert($('.iva:checked').val());
  if(parseFloat($('.iva:checked').val()))
  {
      porcientoImpuesto = parseFloat($('.iva:checked').val()) - parseFloat($('#retivaNum').html())  - parseFloat($('#retisrNum').html());
  }
  else
  {
     porcientoImpuesto = parseFloat($('.iva:checked').val()) - parseFloat($('#retisrNum').html());
  }

  importeBase = parseFloat($('#importe').val()) / ((porcientoImpuesto / 100) + 1);
   $('#importeBase').val(importeBase.toFixed(2));
  importeIVA(0);
  importeAntesRetenciones(0);
}

function importeIVA(cambiaImporte)
{
  var importeIVA = parseFloat($('#importeBase').val()) * (parseFloat($('.iva:checked').val())/100);
  $('#importeIVA').val(importeIVA.toFixed(2));
  
  if(cambiaImporte)
  {
    importeAntesRetenciones(0);
    var nuevoImporte = parseFloat($('#importeBase').val()) + importeIVA - parseFloat($('#retisr').val()) - parseFloat($('#retiva').val());
    $('#importe').val(nuevoImporte.toFixed(2));
  }
}

function importeAntesRetenciones(primeravez)
{
  var importeAntesRetenciones = parseFloat($('#importeBase').val()) + parseFloat($('#importeIVA').val()) + parseFloat($('#otrasErogaciones').val());
   $('#importeAntesRetenciones').val(importeAntesRetenciones.toFixed(2));
  var importe = parseFloat($('#importeBase').val());
  
        if(!primeravez)
        {
          var retiva = importe * (parseFloat($('#retivaNum').html())/100);
      
          $('#retiva').val(retiva.toFixed(2));

          var retisr = importe * (parseFloat($('#retisrNum').html())/100);
          $('#retisr').val(retisr.toFixed(2));
        }
   totalErogacion();
}

function totalErogacion()
{
  if(!parseFloat($('#importeIVA').val()))
  {
    //$('#retiva').val('0.00');
    //$('#retisr').val('0.00');
    $('#IVANoAcreditable').val('0.00');
  }
  var totalErogacion = parseFloat($('#importeAntesRetenciones').val() - $('#retiva').val() - $('#retisr').val());
  $('#totalErogacion').val(totalErogacion.toFixed(2));
}

function eliminaProv(id)
{
  var cerrar = confirm("Esta seguro de eliminar este registro?");
  if(cerrar)
  {
    $.post("ajax.php?c=CaptPolizas&f=eliminaProv",
      {
        Id: id
      },
      function(data)
      {
        abreProveedoresLista($('#idpoliza').val());
      });
  }
}

function actualizaPeriodoAcreditamiento(idPoliza)
{
  var nuevoPeriodo = $('#periodo_acreditamiento').val();
  var nuevoEjercicio = $('#ejercicio_acreditamiento').val();
  //alert("Periodo: "+idPoliza+" Nuevo Periodo"+nuevoPeriodo);
   $.post("ajax.php?c=CaptPolizas&f=actualizaPeriodoAcreditamiento",
      {
        IdPoliza: idPoliza,
        NuevoPeriodo: nuevoPeriodo,
        NuevoEjercicio: nuevoEjercicio
      },
       function()
      {
        alert('Se ha actualizado el acreditamiento.');
      });

}

function actualizaProveedores()
{
  $("#ProveedoresSelect").html('');
   $.post("ajax.php?c=CaptPolizas&f=actualizaProveedores",
       function(data)
      {
        $("#ProveedoresSelect").html(data).trigger('change')
      });
}

