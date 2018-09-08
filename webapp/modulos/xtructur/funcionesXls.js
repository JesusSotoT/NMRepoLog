var exportarExcel = (function() {
	var uri = 'data:application/vnd.ms-excel;base64,',
	template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table border="1">{table}</table></body></html>', 
	base64 = function(s) { return window.btoa(unescape(encodeURIComponent(s))) }, 
	format = function(s, c) { return s.replace(/{(\w+)}/g, function(m, p) { return c[p]; })}

  	return function(opcion, nombreHoja, divGrid) {
	  	var idFilas = new Array();
	  	var nombreColumnas = new Array();
	  	var html='<table border="1">';
	  	var registros = $(divGrid).jqGrid('getGridParam', 'records');


	  	//=== Obtenemos el ID de cada fila ===//
	  	idFilas = $(divGrid).jqGrid('getDataIDs');

	  	if(idFilas.length>0){
		  	//=== Obtenemos el ID de la fila 1 para con el obtener el nombre de las cabeceras ===//
		    var filaUno = $(divGrid).jqGrid('getRowData',idFilas[0]);

		  	if(opcion=='altaProveedores'){
		        //=== Con la Fila uno obtenemos los nombres de las cabceras ===//
		        var x=0;
		        $.each(filaUno, function(cabecera, valor) {
		        	nombreColumnas[x] = cabecera;
		        	if(x==18){
		        		return false;
		        	}
		        	x++;
		        });

		        //=== Pintar en consola las cabeceras para ver si se realiozaran modificaciones ===//
		        console.log('Nombre Columnas: ');
		        console.log(nombreColumnas);

		        //=== Expresiones regulares para sanitizar nombres cabeceras ===//
		        var regexp1 = new RegExp(/value/g);

		        //=== Formar la tabla Html Comienza aqui ===///
		        x=0;
		        html+='<tr>';
		        $.each(nombreColumnas, function(i, th) {
		        	if(x==0){ th='Estatus'; }
		        	if(x==1){ th='Fecha de Captura'; }
		        	if(x==2){ th='Fecha de Ingreso'; }
		        	if(x==3){ th='Dias de Credito'; }
		        	if(x==4){ th='Limite de Credito'; }
		        	if(x==5){ th='Tipo'; }
		        	if(x==6){ th='Razon Social'; }
		        	if(x==7){ th='RFC'; }
		        	if(x==8){ th='Domicilio'; }
		        	if(x==9){ th='Colonia'; }
		        	if(x==10){ th='CP'; }
		        	if(x==11){ th='Municipio'; }
		        	if(x==12){ th='Estado'; }
		        	if(x==13){ th='Telefono Empresa'; }
		        	if(x==14){ th='Apellido Paterno'; }
		        	if(x==15){ th='Apellido Materno'; }
		        	if(x==16){ th='Nombres'; }
		        	if(x==17){ th='Telefono Personal'; }
		        	if(x==18){ th='Correo'; }

		        	html+='<th>'+th+'</th>';
		        	x++;
		        	console.log(th);
		        });
		        html+='</tr>';

		        
		        for (i = 0; i < registros; i++) {
		        	info = $(divGrid).jqGrid('getRowData',idFilas[i]);
		        	f=0;
		        	html+='<tr>';
		        	$.each(info, function(ii, td) {
		        		if(f==1){ f++; return; }
		        		html+='<td>'+td+'</td>';
		        		console.log(td);
		        		f++;
		        	});
		        	html+='</tr>';
		 		}
		        

		        html+='</table>';
		        var ctx = {worksheet: nombreHoja || 'Worksheet', table: html}
	    		window.location.href = uri + base64(format(template, ctx))
		  	}
		  	
	  	}else{
	  		alert('No existen registros a exportar');
	  	}
	    //if (!table.nodeType) table = document.getElementById(table)
	    //var ctx = {worksheet: name || 'Worksheet', table: table.innerHTML}
	    //window.location.href = uri + base64(format(template, ctx))
  	}
})()