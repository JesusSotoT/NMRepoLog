
 $(function()
 {
  $.ui.dialog.prototype._allowInteraction = function(e) {
    return !!$(e.target).closest('.ui-dialog, .ui-datepicker, .select2-drop').length;
};
 	actualizaListaMov();
 	dias_periodo();
   $("#cuenta").select2({
         width : "150px"
        });

	

 	function agregarMov()
 	{
 		if(parseFloat($('#abono').val())>0)
 		{
 			var TipoMov = "Abono";
 			var imp = $('#abono').val();
 		}
 		if(parseFloat($('#cargo').val())>0)
 		{
 			var TipoMov = "Cargo";
 			var imp = $('#cargo').val();
 		}
 		$.post("ajax.php?c=CaptPolizas&f=InsertMov",
 		 {
    		IdPoliza: $('#idpoliza').val(), 
    		Movto: $('#movto').val(), 
    		Cuenta: $('#cuenta').val(), 
    		TipoMovto: TipoMov, 
    		Importe: imp, 
    		Referencia: $('#referencia_mov').val(), 
    		Concepto: $('#concepto_mov').val(),
    		Sucursal: $('#sucursal').val(),
    		Nuevo: $('#nuevo').val()
  		 });
 	}

	

 	 $("#capturaMovimiento").dialog({
      autoOpen: false,
      width: 600,
      height: 300,
      modal: true,
	  buttons: 
	  {
	 	  "Agregar Movimiento": function () 
		 {
			var todos = 0; 
      if($('#cuenta').val() == "" || $('#cuenta').val() == "0.0.0.0")
      {
        todos += 1;
      }

      if($('#concepto_mov').val() == "")
      {
        todos += 1;
      }

      if(($('#abono').val() == "" && $('#cargo').val() == "") || ($('#abono').val() == "0.00" && $('#cargo').val() == "0.00"))
      {
        todos += 1;
      }


			 if(todos==0)
       {
         agregarMov();
			   setInterval(function(){actualizaListaMov()},1000);
         //actualizaListaMov();
			   if($('#nuevo').val() == '0')
			   {
			 	   $("#capturaMovimiento").dialog('close')
			   }
			   $('#cuenta').val('');
			   $('#referencia_mov').val('');
			   $('#concepto_mov').val($('#concepto').val());
			   $('#abono').val('0.00');
			   $('#cargo').val('0.00');
			   $('#movto').val(parseInt($('#movto').val())+1);
         $("#abono").removeAttr("readonly");
         $("#cargo").removeAttr("readonly");
      }else
      {
        alert("No se puede guardar el registro, revise si la informacion es correcta. ")
      }
			 
		 }
	  }
    });
 	$('#agregar').click(function(){
 		$('#capturaMovimiento').dialog('open');
 		
 		$.post("ajax.php?c=CaptPolizas&f=UltimoMov",
 		 {
    		IdPoliza: $('#idpoliza').val(),
  		 },
  		 function(data)
  		 {
  		 	if(data)
  		 	{
  		 		$("#movto").val(parseInt(data)+1);
  		 	}else
  		 	{
  		 		$("#movto").val('1');
  		 	}
  		 	
  		 });
			 $('#referencia_mov').val('');
			 $('#concepto_mov').val($('#concepto').val());
			 $('#abono').val('0.00');
			 $('#cargo').val('0.00');
			 $('#movto').val(parseInt($('#movto').val())+1);	
			 $("#nuevo").val('1');
       $("#abono").removeAttr("readonly");
       $("#cargo").removeAttr("readonly");
 	});
 	$('body').bind("keyup", function(evt){
    if (event.ctrlKey==1)
    {
      if (evt.keyCode == 13)
      {
        $('#guardarpolizaboton').click();
        $('#actualizarboton').click();
      };
    };
    if (evt.keyCode == 27)
    {
      $('#cancelarpolizaboton').click();
    };
  });

//EXTENDIENDO LA FUNCION CONTAINS PARA HACERLO CASE-INSENSITIVE
  $.extend($.expr[":"], {
"containsIN": function(elem, i, match, array) {
return (elem.textContent || elem.innerText || "").toLowerCase().indexOf((match[3] || "").toLowerCase()) >= 0;
}
});
//-------------------------------------------------------------

  // INICIA GENERACION DE BUSQUEDA
      $("#buscar").bind("keyup", function(evt){
        //console.log($(this).val().trim());
        if(evt.type == 'keyup')
        {
          $("#lista tr:containsIN('"+$(this).val().trim()+"')").css('display','table-row');
          $("#lista tr:not(:containsIN('"+$(this).val().trim()+"'))").css('display','none');
          $("#lista tr:containsIN('*1*')").css('display','table-row');
          if($(this).val().trim() === '')
          {
            $("#lista tr").css('display','table-row');
          }
        }

      });
    // TERMINA GENERACION DE BUSQUEDA
 
});

function modifica(id)
{
   $('#cuenta').val('');
         $('#referencia_mov').val('');
         $('#concepto_mov').val('');
         $('#abono').val('0.00');
         $('#cargo').val('0.00');
         $('#movto').val('');
	$('#capturaMovimiento').dialog('open');
 		
 		$.post("ajax.php?c=CaptPolizas&f=DatosMov",
 		 {
    		Id: id,
  		 },
  		 function(data)
  		 {
  		 	var datos = data.split("-");
  		 	$("#movto").val(datos[0]);
        $("#cuenta").select2({
         width : "150px"
        }).select2("val", datos[1]);
  		 	
  		 	$("#referencia_mov").val(datos[3]);
  		 	$("#concepto_mov").val(datos[4]);

  		 	if(datos[2] == 'Abono')
  		 	{
  		 	$("#abono").val(datos[5]);
  		 	$("#cargo").val('0.00');
  		 	}

  		 	if(datos[2] == 'Cargo')
  		 	{
  		 	$("#abono").val('0.00');
  		 	$("#cargo").val(datos[5]);
  		 	}
  		 	$("#nuevo").val('0');
  		  $("#sucursal option[value="+datos[6]+"]").attr("selected","selected");  
  		 });
}
function actualizaListaMov()
	{
		$.post("ajax.php?c=CaptPolizas&f=NumMovs",
 		 {
    		IdPoliza: $('#idpoliza').val()
  		 },
  		 function(data)
  		 {
  		 	$('#lista').html(data);
  		 });

		$.post("ajax.php?c=CaptPolizas&f=SumAbonosCargos",
 		 {
    		IdPoliza: $('#idpoliza').val(),
        IdEjercicio:$('#IdExercise').val()
  		 },
  		 function(data)
  		 {
  		 	var AbonosCargos = data.split("-");
        var formatter = new Intl.NumberFormat('en-US', {
          style: 'currency',
         currency: 'USD',
         minimumFractionDigits: 2,
          });
  		 	$('#Abonos').html("Abonos: <b>"+formatter.format(AbonosCargos[0])+"</b>");
  		 	$('#Cargos').html("Cargos: <b>"+formatter.format(AbonosCargos[1])+"</b>");
  		 	$('#Cuadre').html("Diferencia: <b style='color:red;'>"+formatter.format(AbonosCargos[1]-AbonosCargos[0])+"</b>");
  		 });
	}
function deleteMov(id,con)
	{
		
		var confirmar = confirm("¿Esta seguro de eliminar este movimiento "+con+"?");

		if(confirmar)
		{
			$.get( "ajax.php?c=CaptPolizas&f=ActMovActivo&id="+id, function() {
 			$("#tbl"+id).css("display","none");
 			actualizaListaMov()
 			//location.reload()
			});
		}
	}

function CancelPoliza()
{
	var confirmar = confirm("¿Esta seguro de cancelar esta poliza?");
	if(confirmar)
		{
			window.location = "index.php?c=CaptPolizas&f=Ver";
		}
}
function dias_periodo()
{
  $.post("ajax.php?c=CaptPolizas&f=InicioEjercicio",
     {
        NombreEjercicio: $('#NameExercise').val()
       },
       function(data)
       {
        var cad = data.split("-");
    var fin;
    if($('#periodos').val() == 13)
      {
      $('#inicio_mes').html('31-12-'+cad[0]);
      $('#fin_mes').html('31-12-'+cad[0]);
    }
    else
    {
    $('#inicio_mes').html(moment(cad[0]+'-'+cad[1]+'-'+cad[2]).add('months', $('#periodos').val()-1).format('DD-MM-YYYY'));
    fin = moment(cad[0]+'-'+cad[1]+'-'+cad[2]).add('months', $('#periodos').val()).format('YYYY-MM-DD');
    fin = moment(fin).subtract('days',1).format('DD-MM-YYYY');
    $('#fin_mes').html(fin);
    }
        
       });
  
} 

function cal()
{
		var fi = $("#fin_mes").html();
  		 	var fecha_inicial = fi.split('-');	
        if($("#periodos").val() == 13)
        {
          $("#datepicker").val($("#fin_mes").html()).attr('readonly','readonly');
           $("#concepto").val('Poliza de Ajuste, Ejercicio '+fecha_inicial[2]).attr('readonly','readonly');
        }
        else
        {
			$( "#datepicker" ).datepicker(
			{ 
				dateFormat: "dd-mm-yy",
				monthNames: [ "Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"],
				dayNamesMin: [ "Do","Lu","Ma","Mi","Ju","Vi","Sa"],
				minDate: new Date(fecha_inicial[2], $("#periodos").val() - 1, 1),
				maxDate: new Date(fecha_inicial[2], $("#periodos").val() - 1, fecha_inicial[0])
			});
      }
$("#aux").css("display","none")
}	

function validaciones(f)
{
  if(f.concepto.value == "")
  {
    alert("El Campo Concepto esta vacio.")
    f.concepto.focus();
    return false;
  }

   if(f.fecha.value == "")
  {
    alert("El Campo Fecha esta vacio.")
    f.fecha.focus();
    return false;
  }
  var fch = f.fecha.value
  fch = fch.split('-');
  if(f.periodos.value != 13)
  {
    if(parseInt(fch[1]) != parseInt(f.periodos.value) || parseInt(fch[2]) != parseInt(f.NameExercise.value))
    {
      alert("Escoje una fecha acorde al periodo y ejercicio actual.")
      f.fecha.focus();
      return false;
    }
  }
    else
    {
      if(parseInt(fch[1])+1 != parseInt(f.periodos.value) || parseInt(fch[2]) != parseInt(f.NameExercise.value))
      {
        alert("Escoje una fecha acorde al periodo y ejercicio actual.")
        f.fecha.focus();
        return false;
      }
    }
  }


function abonoscargos()
{
  if($("#abono").val() == "" || $("#abono").val() == "0.00" || $("#abono").val() == "0" )
  {
    $("#cargo").removeAttr("readonly");
  }
  else
  {
  $("#cargo").val("0.00");
  $("#cargo").attr("readonly","readonly");
  }

  if($("#cargo").val() == "" || $("#cargo").val() == "0.00" || $("#cargo").val() == "0" )
  {
    $("#abono").removeAttr("readonly");
  }
  else
  {
    $("#abono").val("0.00");
  $("#abono").attr("readonly","readonly");
  }
}
