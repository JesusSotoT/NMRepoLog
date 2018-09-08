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
	
	var dato = valor;
	dato = dato.replace(/,/g,"");
	
	return parseFloat(dato);
}

function redondeanumero(num, dec) {
	var result = Math.round(num*Math.pow(10,dec))/Math.pow(10,dec);
	return result;
}

function dosdecimales(num){
	var i=0;
	var result="";
	var caracter="";
	var decimales="";
	//alert(num);
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
		alert("Time is not in a valid format.");
		return false;
	}
	
	hour = matchArray[1];
	minute = matchArray[2];
	second = matchArray[4];
	ampm = matchArray[6];

	if (second=="") { second = null; }
	if (ampm=="") { ampm = null }

	if(hour < 0  || hour > 23){
		alert("La hora debe estar entre 1 y 12.");
		return false;
	}
	
	if(hour <= 12 && ampm == null){
		//if (confirm("Please indicate which time format you are using.  OK = Standard Time, CANCEL = Military Time")) {
			alert("Se debe especifcar el AM ó PM.");
			return false;
	    //}
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