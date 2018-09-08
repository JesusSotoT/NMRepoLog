$(function(){
	
	$(".numeric").numeric();
	$(".float").numeric({allow:'.'});
	$("#preloader").hide();
	
		var options = { 
    beforeSend: function() 
    {
    	/*
		$("#progress").show();
    	//clear everything
    	$("#bar").width('0%');
    	$("#message").html("");
		$("#percent").html("0%");
		*/
    },
    uploadProgress: function(event, position, total, percentComplete) 
    {
    	//$("#bar").width(percentComplete+'%');$("#percent").html(percentComplete+'%');
    },
    success: function() 
    {
        //$("#bar").width('100%');	$("#percent").html('100%');
    },
	complete: function(response) 
	{
		$("#fact").val(response.responseText);
},
	error: function()
	{
		alert("Ocurrio un error al agregar la factura");
		//$("#message").html("<font color='red'> ERROR: No se pudo adjuntar el archivo</font>");
	}
     
}; 
///////////////////
		var options2 = { 
    beforeSend: function() 
    {
    	/*
		$("#progress").show();
    	//clear everything
    	$("#bar").width('0%');
    	$("#message").html("");
		$("#percent").html("0%");
		*/
    },
    uploadProgress: function(event, position, total, percentComplete) 
    {
    	//$("#bar").width(percentComplete+'%');$("#percent").html(percentComplete+'%');
    },
    success: function() 
    {
        //$("#bar").width('100%');	$("#percent").html('100%');
    },
	complete: function(response) 
	{
		$("#xml").val(response.responseText);
	},
	error: function()
	{
		alert("Ocurrio un error al agregar el xml");
		//$("#message").html("<font color='red'> ERROR: No se pudo adjuntar el archivo</font>");
	}
     
}; 
//////////////////

     $("#myForm_factura").ajaxForm(options);
     $("#myForm_xml").ajaxForm(options2);
	
});

function Guardarcompra()
{
		//if($("#factura").val()==''){alert("Debes ingresar el folio de la factura");return false;}
		//if($("#fact").val()==''){alert("Debes adjuntar la factura");return false;}
		//if($("#xml").val()==''){alert("Debes adjuntar el xml de la factura");return false;}

		cxp();

		var comentario=$('#comentario').val();
		//alert(comentario);

		var productos=Array();
		var ids=$("#ids").val().split("*");
		for(var i=0;i<ids.length-1;i++)
		{
			productos[i]=ids[i]+"_"+$("#cantidad_"+ids[i]).val()+"_"+$("#costo_"+ids[i]).val();
		}
		$("#btngcompra").attr("disabled","disabled");
		$("#preloader").show();
		$.ajax({
			url:baseUrl+'index.php/buy/guardar',
			type: 'POST',
			data: {datos:productos,fact:$("#fact").val(),xml:$("#xml").val(),factura:$("#factura").val(),orden:$("#orden").val(),sucursal:$("#sucursal").val(),comentario:comentario},
			success: function(callback)
			{	 
				alert("Has almacenado la mercancia con exito");
				$("#btngcompra").removeAttr("disabled");
				$("#preloader").hide();
				window.location=baseUrl+'index.php/buy';
			}
		});
}


function ChangeOrder(id)
{
	
	$("#sub_"+id).html($.number($("#cantidad_"+id).val()*quitapuntos($("#costo_"+id).val()) ,2));
	
	var ids=$("#ids").val().split("*");
	var total=0;
	for(var i=0;i<ids.length-1;i++)
	{
		total+=parseFloat($("#sub_"+ids[i]).html().replace(",",""));	
	}
	
	$("#neto").html(total.toFixed(2));
	$("#iva").html(parseFloat(total*0.16).toFixed(2));
	$("#total").html(parseFloat(total*1.16).toFixed(2));

}

function Actualizar(id)
{
		
				
				
			$("#preloader").show();		
		$.ajax({
			url:baseUrl+'index.php/buy/actulizar',
			type: 'POST',
			data: {fact:$("#fact").val(),xml:$("#xml").val(),factura:$("#factura").val(),id:id},
			success: function(callback)
			{	 
				alert("Has almacenado los cambios correctamente");
				//$("#btngcompra").removeAttr("disabled");
				$("#preloader").hide();
				window.location=baseUrl+'index.php/buy';
			}
		});	
}


function quitapuntos(numero)
{
	var e=0;
	 while (numero.toString().indexOf(".") != -1)
	{
		if(e==0)
		{
		  numero= numero.toString().replace(".","*");
		}
		else
		{
		  numero= numero.toString().replace(".","");
		}
		e++;
	}
	numero=numero.replace("*",".");
	
	numero=numero.replace(",","");
	return numero;
}

function cxp(){
	var id = $('#orden').val();
	var ctotal = $('#total').attr('value');

	//alert(id+'X'+'X'+ctotal);

		$.ajax({
			url:baseUrl+'index.php/buy/cuentasporpagar',
			type: 'POST',
			data: {id:id,
				   ctotal:ctotal,
				},
			success: function(callback)
			{	 
				alert("El monto se ha traladado a cuentas por pagar.");
			
			}
		});	



} 







