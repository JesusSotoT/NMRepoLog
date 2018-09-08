/*!
 * misutils.js
 *
 * Copyright 2010, Omar Mendoza LGPL licenses.
 *
 * Date: Jue May 13 23:07 2010 Mex
 */

function solofecha(evt){
     var keyPressed = (evt.which) ? evt.which : event.keyCode
     return !(keyPressed > 31 && (keyPressed < 48 || keyPressed > 57))
}

function soloint(evt){
     var keyPressed = (evt.which) ? evt.which : event.keyCode
     return !(keyPressed > 31 && (keyPressed < 48 || keyPressed > 57))
}
function solonum(evt,obj){
     var keyPressed = (evt.which) ? evt.which : event.keyCode
     var a = !((keyPressed > 31 && (keyPressed < 48 || keyPressed > 57)) && keyPressed!=46)
	var str=obj.value.toString();
	var result=str.indexOf(".");

	 //Checa si no había punto antes      alert("entre   "+str+"   hay punto:"+result);	 
	 if(keyPressed==46){
		 if(result==-1){
			return true;
		 } else {
			return false
		 }
	 } else {
		return a;
	 }
		
	 
}

function agregacomas(nStr)
{
	nStr += '';
	x = nStr.split('.');
	x1 = x[0];
	x2 = x.length > 1 ? '.' + x[1] : '';
	var rgx = /(\d+)(\d{3})/;
	while (rgx.test(x1)) {
		x1 = x1.replace(rgx, '$1' + ',' + '$2');
	}
	return x1 + x2;
}

function regresanumero(valor){	
	var dato = valor+",";
	//alert(valor);
	dato = dato.replace(/,/g,"");
	//alert(dato);
	return parseFloat(dato);
}

function redondeanumero(num, dec) {
	if(num==null) return 0;
	var result = Math.round(num*Math.pow(10,dec))/Math.pow(10,dec);
	return result;
}

function dodecimales(num){
	//return num;
	//alert(arguments.callee.caller.toString()+'  valor:'+num);

	if(num.indexOf("E")!=-1){
		return "0.00";
	}
	
	if(num.indexOf("e")!=-1){
		return "0.00";
	}
	
	var i=0;
	var result="";
	var caracter="";
	var decimales="";
	//alert(num);
	//num = regresanumero(num);
	for(i=0; i<=num.length; i++){
		caracter=num.substring(i,i+1);
		//alert(caracter);
		if(caracter==="."){
			decimales+=num.substring(i+1);
			break;
		}
		result+=caracter;		
	}
	//alert(decimales+" "+(decimales.toString().length.toString()));
	if(decimales.toString().length>=2){
		decimales=Left(decimales.toString(),2);
	} else {
		if(decimales.toString().length==1){
			decimales=decimales.concat("0");
		}else{
			decimales="00";
		}
	}
	result=result.concat("."+decimales);
	
	//alert(result);
	return result;
}

function sindecimales(num){
	var i=0;
	var result="";
	var caracter="";
	for(i=0; i<=num.length; i++){
		caracter=num.substring(i,i+1);
		if(caracter=="."){
			break;
		}
		result+=caracter;
	}

	//alert(result);
	return result;
}


String.prototype.repeat = function(num){
	return new Array(num+1).join(this);
}
function cuantos_decimales(num, cuantosdec){
	
	//return num;
	var result = "";
	var ceros_decimales = "0".repeat(cuantosdec);

	if(num.indexOf("E")!=-1){
		result = "0." + ceros_decimales; 
		return result;
	}
	
	if(num.indexOf("e")!=-1){	
		result = "0." + ceros_decimales;
		return result;
	}
	
	var i=0;
	var caracter="";
	var decimales="";
	//alert(num);
	//num = regresanumero(num);
	for(i=0; i<=num.length; i++){
		caracter=num.substring(i,i+1);
		//alert(caracter);
		if(caracter==="."){
			decimales+=num.substring(i+1);
			break;
		}
		result+=caracter;		
	}
	//alert(decimales+" "+(decimales.toString().length.toString()));
	if(decimales.toString().length>=cuantosdec){
		decimales=Left(decimales.toString(),cuantosdec);
	} else {
		while(decimales.toString().length<cuantosdec){
			decimales=decimales.concat("0");
		}
		/*
		if(decimales.toString().length==1){
			decimales=decimales.concat(ceros_decimales);
		}else{
			decimales=ceros_decimales;
		}*/
	}
	result=result.concat("."+decimales);
	
	//alert(result);
	return result;
}




function Left(str, n){
	if (n <= 0)
	    return "";
	else if (n > String(str).length)
	    return str;
	else
	    return String(str).substring(0,n);
}


function esFecha(year,month,day){
	// El argumento del mes debe estar 1-12
	month = month - 1; // esto es por que javascript el rango de mes es : 0- 11
	var tempDate = new Date(year,month,day);
	if ( (year == tempDate.getFullYear()) && (month == tempDate.getMonth()) && (day == tempDate.getDate()) ){
		return true;
	} else {
		return false;
	}
}


function esHora(timeStr){
	// Checks if time is in HH:MM:SS AM/PM format.
	// The seconds and AM/PM are optional.

	var timePat = /^(\d{1,2}):(\d{2})(:(\d{2}))?(\s?(AM|am|PM|pm))?$/;
	
	var matchArray = timeStr.match(timePat);
	
	if(matchArray == null){
		alert("La hora no tiene un formato válido.");
		return false;
	}
		
	
	hour = matchArray[1];
	minute = matchArray[2];
	second = matchArray[4];
	ampm = matchArray[6];

	if (second=="") { second = null; }
	if (ampm=="") { ampm = null }

	
	
	/*if(hour <= 12 && ampm == null){
		//if (confirm("Please indicate which time format you are using.  OK = Standard Time, CANCEL = Military Time")) {
			alert("Se debe especifcar el AM ó PM.");
			return false;
	    //}
	}*/
	if(ampm == null){
		if(hour < 0  || hour > 23){
			alert("La hora debe estar entre 0 y 23.");
			return false;
		}
	} else {
		if(hour < 1  || hour > 12){		
			alert("La hora debe estar entre 1 y 12.");
			return false;
		}
	}	
	
	
	
	if  (hour > 12 && ampm != null) {
		alert("El formato de hora es de 12 con AM y PM");
		return false;
	}
	
	if (minute<0 || minute > 59) {
		alert ("Los minutos deben estar entre 0 y 59");
		return false;
	}
	
	if (second != null && (second < 0 || second > 59)) {
		alert ("Los segundos deben estar entre 0 y 59");
		return false;
	}
	
	return true;
}


function maximaLongitud(texto,maxlong){
	var tecla, int_value, out_value;				
	if (texto.value.length > maxlong)
	{
    	/*con estas 3 sentencias se consigue que el texto se reduzca
    	al tamaño maximo permitido, sustituyendo lo que se haya
    	introducido, por los primeros caracteres hasta dicho limite*/
    	in_value = texto.value;
    	out_value = in_value.substring(0,maxlong);
    	texto.value = out_value;
    	alert("La longitud máxima es de " + maxlong + " caractéres");
    	return false;
	}
    return true;
}
