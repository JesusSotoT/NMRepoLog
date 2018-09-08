
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title>Calculadora</title> 
<script type="text/javascript" src="../../../js/calculadora.js"></script>
<style>
/*aspectos generales: bordes y color de fondo de calculadora*/ 
.calculadora { border: 3px blue ridge; width: 250px;text-align: center;
                         background-color: #f6f8d8; }

/*pantalla de visualización: bordes, posición, color de fondo, estilo letra.*/                   
#textoPantalla { width: 185px; border: 2px black solid; text-align: right; 
                position: relative; left: 23px;  padding: 0px 5px;
                                background-color: white; font-family: "courier new"; 
                                overflow: hidden;}

/*botones normales: anchura y margen*/
.calculadora [type=button] { width: 35px; padding: 0;  }
/*botones especiales*/
.calculadora input.largo { color: red; width: 60px; }
</style>
</head>
<body>
<div class="calculadora"
<form action="#" name="calculadora" id="calculadora">
<p id="textoPantalla">0</p>
<p>
<input type="button" class="largo" value="Retr" onclick="retro()"   />
<input type="button" class="largo" value="CE" onclick="borradoParcial()"  />
<input type="button" class="largo" value="C" onclick="borradoTotal()"  />
</p>
<p>
<input type="button" value="7" onclick="numero(7)" />
<input type="button" value="8" onclick="numero('8')" />
<input type="button" value="9" onclick="numero('9')" />
<input type="button" value="/" onclick="operar('/')"  />
<input type="button" value="Raiz" onclick="raizc()" />
</p>
<p>
<input type="button" value="4" onclick="numero('4')" />
<input type="button" value="5" onclick="numero('5')" />
<input type="button" value="6" onclick="numero('6')" />
<input type="button" value="*" onclick="operar('*')" />
<input type="button" value="%" onclick="porcent()" />
</p>
<p>
<input type="button" value="1" onclick="numero('1')" />
<input type="button" value="2" onclick="numero('2')" />
<input type="button" value="3" onclick="numero('3')" />
<input type="button" value="-" onclick="operar('-')" />
<input type="button" value="1/x" onclick="inve()" />
</p>
<p>
<input type="button" value="0" onclick="numero('0')" />
<input type="button" value="+/-" onclick="opuest()" />
<input type="button" value="." onclick="numero('.')" />
<input type="button" value="+" onclick="operar('+')" />
<input type="button" value="=" onclick="igualar()" />
</p>
</form>
</div>
</body>
</html>
