
$("#guardar_iva_btn").on("click", function(){
  guardarIVACausacion();
});

function abreCausacion(idPoliza)
{
  $('#idp').val(idPoliza);
  $('input[tipo=numero]').val('0.00');
  /*$('#Causacion').dialog({position:['center',150]});
  $('#Causacion').dialog('open');*/
  $("#Causacion").modal("show");
   $.post("ajax.php?c=CaptPolizas&f=getCausacionData",
      {
         IdPoliza: idPoliza
      },
      function(data)
      {
		
          //alert(miJson[1].ImporteTotal) 
          if(data != '0')
          {
              llenaTablaDatos(data);
          }
          else
          {
              buscaCuentaBancos(idPoliza);
          }
      });
   recalculaTotales();
}
function buscaCuentaBancos(idPoliza)
{

  $.post("ajax.php?c=CaptPolizas&f=getCargosBancos&Tipo=Cargo",
      {
    
         IdPoliza: idPoliza
         
      },
      function(importe2)
      {
        //alert(importe2)
        if(importe2 != 'No')
        {
          $('#ImporteTotal16').val(importe2);
          var importeBase2 = parseFloat(importe2) / ((16 / 100) + 1);
          $('#ImporteBase16').val(importeBase2.toFixed(2));
          var importeIVA2 = importeBase2 * (16/100);
          $('#IVA16').val(importeIVA2.toFixed(2));
          $('#existe').val('0'); 
          $('#ietucli').val(importeBase2.toFixed(2));       
          recalculaTotales(); 
        }
        else
        {
          alert("Configure una cuenta de Bancos en (Asignacion de Cuentas)")
          /*$("#Causacion").dialog('close');*/
          $("#Causacion").modal("hide");
          window.parent.agregatab('../../modulos/cont/index.php?c=Config&amp;f=configAccounts','Asignación de Cuentas','',1647)
        }
      });
}
function llenaTablaDatos(data)
{
              var miJson = $.parseJSON(data);
              
              //Bloque IVA 16%
              $('#ImporteTotal16').val(parseFloat(miJson[2].ImporteTotal).toFixed(2));
              $('#ImporteBase16').val(parseFloat(miJson[2].ImporteBase).toFixed(2));
              $('#IVA16').val(parseFloat(miJson[2].IVA).toFixed(2));
              $('#IVANoAc16').val(parseFloat(miJson[2].IvaPagadoNoAcreditable).toFixed(2));

              //Bloque IVA 11%
              $('#ImporteTotal11').val(parseFloat(miJson[3].ImporteTotal).toFixed(2));
              $('#ImporteBase11').val(parseFloat(miJson[3].ImporteBase).toFixed(2));
              $('#IVA11').val(parseFloat(miJson[3].IVA).toFixed(2));
              $('#IVANoAc11').val(parseFloat(miJson[3].IvaPagadoNoAcreditable).toFixed(2));

             //Bloque IVA 0%
              $('#ImporteTotal0').val(parseFloat(miJson[4].ImporteTotal).toFixed(2));
              $('#ImporteBase0').val(parseFloat(miJson[4].ImporteBase).toFixed(2));
        

              //Bloque Exento%
              $('#ImporteTotalExenta').val(parseFloat(miJson[5].ImporteTotal).toFixed(2));
              $('#ImporteBaseExenta').val(parseFloat(miJson[5].ImporteBase).toFixed(2));
        
              //Bloque IVA 15%
              $('#ImporteTotal15').val(parseFloat(miJson[6].ImporteTotal).toFixed(2));
              $('#ImporteBase15').val(parseFloat(miJson[6].ImporteBase).toFixed(2));
              $('#IVA15').val(parseFloat(miJson[6].IVA).toFixed(2));
              $('#IVANoAc15').val(parseFloat(miJson[6].IvaPagadoNoAcreditable).toFixed(2));

              //Bloque IVA 10%
              $('#ImporteTotal10').val(parseFloat(miJson[7].ImporteTotal).toFixed(2));
              $('#ImporteBase10').val(parseFloat(miJson[7].ImporteBase).toFixed(2));
              $('#IVA10').val(parseFloat(miJson[7].IVA).toFixed(2));
              $('#IVANoAc10').val(parseFloat(miJson[7].IvaPagadoNoAcreditable).toFixed(2));

              //Bloque Otras Tasas
              $('#ImporteTotalOtras').val(parseFloat(miJson[8].ImporteTotal).toFixed(2));
              $('#ImporteBaseOtras').val(parseFloat(miJson[8].ImporteBase).toFixed(2));
              $('#IVAOtras').val(parseFloat(miJson[8].IVA).toFixed(2));
              $('#IVANoAcOtras').val(parseFloat(miJson[8].IvaPagadoNoAcreditable).toFixed(2));

              //Bloque IVA Retenido
              $('#ImporteTotalIvaRetenido').val(parseFloat(miJson[9].ImporteTotal).toFixed(2));

              //Bloque ISR Retenido
              $('#ImporteTotalIsrRetenido').val(parseFloat(miJson[10].ImporteTotal).toFixed(2));

              //Bloque Otros
              $('#ImporteTotalOtros').val(parseFloat(miJson[11].ImporteTotal).toFixed(2));

              //Aplica para IVA
              if(parseInt(miJson[12].ImporteTotal))
              {
                  $('#aplicaIVA').prop('checked','true');
              }
              else
              {
                  $('#aplicaIVA').removeAttr('checked');
              }

              //Periodo de Acreditamiento
              if(!parseInt(miJson[13].ImporteTotal))
              {
                  //alert('No tiene')
                  $('#periodo_acreditamientoIVA').val(0)
              }
              else
              {
                  //alert('Si tiene')
                  $('#periodo_acreditamientoIVA').val(miJson[13].ImporteTotal);
              }

              if(!parseInt(miJson[14].ImporteTotal))
              {
                  //alert('No tiene')
                  $('#EjerciciosIVA').val(0);
              }
              else
              {
                  //alert(miJson[14].ImporteTotal)
                  $('#EjerciciosIVA').val(miJson[14].ImporteTotal);
                 
              }
              
              if(parseInt(miJson[15].ImporteTotal)){
              	$("#ietucli").val(parseInt(miJson[15].ImporteTotal));
              }else{
              	$("#ietucli").val(0);
              }
              if(parseInt(miJson[16].ImporteTotal)){
              	$("#ietulista").val(miJson[16].ImporteTotal);
              	$("tfoot").show();
              }else{
              	$("#ietu").val(0);
              	$("tfoot").show();
              }
              
 paraietu();
              modificaIvaRetenido();
              $('#existe').val('1');

}

function modificaImporteTotal(tasa)
{

  var nuevoImporteBase;
  var nuevoIVA;
  var n;

  switch(tasa)
  {
    case 'Exenta': 
                  n=0;
                  break;
    case 'Otras': 
                  n = prompt('Ingresa el porcentaje a calcular en Otras Tasas');
                  $('#ImporteTotalOtras').attr('title','Tasa '+n+'%');
                  break;
    default: 
                  n = tasa;
  }
  if($('#ImporteTotal'+tasa).val() == '') $('#ImporteTotal'+tasa).val('0.00')// si el importe esta vacio agrega 0.00

  nuevoImporteBase = parseFloat($('#ImporteTotal'+tasa).val()) / ((n / 100) + 1);
  nuevoIVA = nuevoImporteBase * (n/100);
  $('#ImporteBase'+tasa).val(nuevoImporteBase.toFixed(2));
  $('#IVA'+tasa).val(nuevoIVA.toFixed(2));
 
  recalculaTotales();

}
var total;
function modificaIvaRetenido()
{
  $('#IvaIvaRetenido').val($('#ImporteTotalIvaRetenido').val())
  recalculaTotales();
}

function recalculaTotales()
{
  var totalesImporteTotal = 0;
  var totalesImporteBase = 0;
  var totalesIVA = 0;
  var totalesIVANoAc = 0;

  $("#contex tr[tipo|='trtasa']").each(function(index)
  {
    //if(isNaN(totalesImporteTotal)){ totalesImporteTotal = 0; }

    if($('td:nth-child(1)',this).text() == 'IVA Retenido' || $('td:nth-child(1)',this).text() == 'ISR Retenido')
    {
      totalesImporteTotal -= parseFloat($('td:nth-child(2) input',this).val());  
    }
    else
    {
      totalesImporteTotal += parseFloat($('td:nth-child(2) input',this).val());
    }
    
    totalesImporteBase += parseFloat($('td:nth-child(3) input',this).val());

    if($('td:nth-child(1)',this).text() == 'IVA Retenido' || $('td:nth-child(1)',this).text() == 'ISR Retenido')
    {
      totalesIVA -= parseFloat($('td:nth-child(4) input',this).val());
    }
    else
    {
      totalesIVA += parseFloat($('td:nth-child(4) input',this).val());
    }
    totalesIVANoAc += parseFloat($('td:nth-child(5) input',this).val());
  });
  $("#totalesImporteTotal").html('$'+totalesImporteTotal.toFixed(2));
  $("#totalesImporteTotalHidden").val(totalesImporteTotal.toFixed(2));
  $("#totalesImporteBase").html('$'+totalesImporteBase.toFixed(2));
  $("#totalesIVA").html('$'+totalesIVA.toFixed(2));
  $("#totalesIVANoAc").html('$'+totalesIVANoAc.toFixed(2));
   $("#ietucli").val(totalesImporteBase.toFixed(2));
   total=totalesImporteBase.toFixed(2);

}

function guardaCausacion(idPoliza)
{
  var tasas = new Array();
  var tasa;
  $('button').attr('disabled','disabled')

$("#contex tr[tipo|='trtasa']").each(function(index)
{
  tasa = $('td:nth-child(1)',this).text();
  tasa = tasa.split('%').join('');
  tasa = tasa.split('Tasa ').join('');

  tasas[tasa] = $('td:nth-child(2) input',this).val() +"-"+ $('td:nth-child(3) input',this).val()+"-"+ $('td:nth-child(4) input',this).val()+"-"+ $('td:nth-child(5) input',this).val()
  
});
// alert($("#ietucli").val());
// alert($("#ietulista").val());
 $.post("ajax.php?c=CaptPolizas&f=guardaCausacion",
      {  
         tasa16: tasas[16],
         tasa11: tasas[11],
         tasa0: tasas[0],
         tasaExenta: tasas['exenta'],
         tasa15: tasas[15],
         tasa10: tasas[10],
         tasaOtras: tasas['Otras Tasas'],
         ivaRetenido: tasas['IVA Retenido'],
         isrRetenido: tasas['ISR Retenido'],
         otros: tasas['Otros'],
         aplica: $('#aplicaIVA').prop('checked'),
         periodoAc: $('#periodo_acreditamientoIVA').val(),
         ejercicioAc: $('#EjerciciosIVA').val(),
         IdPoliza: idPoliza,
         Existe: $('#existe').val(),
         acreditaietu : $("#ietucli").val(),
         tipoietu : $("#ietulista").val(),
         nombreejer: $("#nombreejer").val()
      },
      function(callback)
      {
        if(callback)
        {
         //alert(callback)
         //alert('La Causacion se ha guardado con exito.');
         /*$("#Causacion").dialog('close');*/
         $("#Causacion").modal("hide");
         $('button').removeAttr('disabled')
        }
          
      });
 
}

function comparaIVA(tasa)
{
  if($('#IVANoAc'+tasa).val() > $('#IVA'+tasa).val())
  {
    alert('El IVA pagado no acreditable no puede ser mayor al IVA.');
    $('#IVANoAc'+tasa).focus();
    return false;
  }

}

function paraietu(){
	$.post("ajax.php?c=CaptPolizas&f=buscaejercicio",{
		idejer:$("#EjerciciosIVA").val()
		},function (resp){
			if(parseInt(resp)<2014 && resp!=0){
				$("tfoot").show();
				$("#nombreejer").val(resp);
				$('#ietucli').val(total);
			}else{
				$("tfoot").hide();
				$("#nombreejer").val(0);
				$("#ietulista").val(0);
				$('#ietucli').val(0);
			}
		});
	//if($("#EjerciciosIVA".html()))
}

function guardarIVACausacion(){
  var guardar;
  var Cargos = $('#Cargos b').html();
      Cargos = Cargos.replace('$','').replace(/,/g, '');
  if(parseFloat($('#totalesImporteTotalHidden').val()) <= parseFloat(Cargos))
  {
      guardar = 1;
  }
  else
  {
      var acepta = confirm("El Total de 'Importe Total' es mayor a los Cargos.\nTotales: "+$('#totalesImporteTotalHidden').val()+"\nCargos: "+Cargos+"\nAun asi desea continuar?");
      if(acepta)
      {
          guardar=1;
      }
      else
      {
          guardar = 0;
      }
  }

  if(guardar)
  {
    guardaCausacion($('#idp').val());
  }
}
