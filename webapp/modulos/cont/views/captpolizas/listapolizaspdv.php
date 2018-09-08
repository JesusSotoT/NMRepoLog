<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js" type="text/javascript"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
<script language='javascript'>
$(function()
{
	$("#verMovs").dialog({
      autoOpen: false,
      width: 600,
      height: 500,
      modal: true,
	  buttons: 
	  {
	 	  "Cerrar": function () 
		 {
			$("#verMovs").dialog('close')
		 
		 }
	  }
    });
	$('.movs').click(function()
		{
			event.preventDefault()
			$('#contenido').html('');
			$('#verMovs').dialog('open');
			$.post("ajax.php?c=CaptPolizas&f=MovimientosPolizasPDV",
 		 {
    		IdPoliza: this.id
  		 },
  		 function(data)
  		 {
  		 	$('#contenido').html(data);
  		 	
  		 });
		});
    $("#todos").click(function(event) {
        $(".chk").click();
    });
});
function accion(a)
{
    var preg;
    var str = ''
        //Busca entre todos los checkboxes seleccionados los valores
        //$("input:checkbox:checked").each(function(index)
        $(".chk:checked").each(function(index)
        {
                
                if(index == 0)//Si es el primer o solo es un registro se agrega a la cadena sin coma
                    {
                        str = $(this).val() 
                    }
                    else
                        {
                            //Se hace una validacion, si el id del producto esta repetido no se agrega a la cadena
                            //////////////////////////////////////////////////////////////////////////////////////

                            var cad = str.split(",");//Se divide la cadena por sus comas
                            var cont=0; //Contador inicializa en Cero
                            
                            //Se analiza palabra por palabra de la cadena
                            for(var i=0;i<=cad.length;i++)
                            {
                                //Si el id esta repetido se suma el contador
                                if(cad[i] == $(this).val()){ cont++; }
                            }

                            //Si el contador no devuelve nada entonces no hay palabra repetida por lo que se agrega a la cadena (con coma)
                            if(!cont){ str += "," + $(this).val(); }
                        }
        });
        //TERMINA
    if(a)
    {
        preg = confirm('Esta seguro que quiere restaurar estas polizas?.');
        if(preg)
        {
            $.post("ajax.php?c=CaptPolizas&f=RestaurarPoliza",
             {
                IdPoliza: str,
                PDV:1  
             },function(data)
             {
                alert('Se ha restaurado la Poliza')
                location.reload(); 
             });
        }
    }
    else
    {
        preg = confirm('Si elimina la poliza definitivamente ya no podra recuperarla despues.');
        if(preg)
        {
            $.post("ajax.php?c=CaptPolizas&f=EliminarPoliza",
             {
                IdPoliza: str
             },function()
             {
                alert('Se ha eliminado la Poliza.')
                location.reload(); 
             });
        }
    }
   
}

function checking(elem)
{
	$('#'+elem).click()
}
function actualiza(elem)
{
	var es_activo;
	if($('#cb_'+elem).is(':checked'))
	{
		es_activo = 1
	}
	else
	{
		es_activo = 0
	}
	$.post("ajax.php?c=CaptPolizas&f=ActivaMovPDV",
 		 {
    		Id: elem ,
    		Activo: es_activo
  		 });
}

</script>
<style>
.over{
background-color:#525154;
color:#FFF;
}
.out{
background-color:;
color:;
}
td
{
	width:158px;
	height:30px;
	text-align: center;
	border:1px solid #BDBDBD;
}

a.movs
{
	text-decoration: none;
	font-weight: bold;
	color:black;
}
a.movs:hover
{
	text-decoration: underline;
	color:white;
}

#nmsubtitle
{
    color:black;
}

footer{
  display: none;
}
</style>

<div class="container">
  <h3 id='title' class="text-center">Lista de Polizas sin autorizar del Punto de Venta / <a href='index.php?c=CaptPolizas&f=ListaPolizasEliminadas'>Inactivas(Acontia)</a></h3>
  <div class="row nmsubtitle" id="nmsubtitle" style="width: unset !important;">
    <div class="col-md-12">
      Autorizaci&oacute;n para generar movimientos contables
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="table-responsive">   
        <table class="table table-bordered">
          <tr style='background-color:#BDBDBD;color:white;font-weight:bold;'>
            <td>
              Referencia
            </td>
            <td>
              Fecha
            </td>
            <td>
              Detalles
            </td>
            <td>
              <label>Seleccionar</label>
              <button class="btn btn-default btn-sm" id='todos'>Todos</button>
            </td>
          </tr>
          <?php
            while($LPP = $ListaPolizasPDV->fetch_assoc())
            {
              echo "<tr onMouseOver=\"this.className='over'\" onMouseOut=\"this.className='out'\">
                      <td>
                        ".$LPP['referencia']."
                      </td>
                      <td>
                        ".$LPP['fecha']."
                      </td>
                      <td>
                        <a href='#' class='movs' id='".$LPP['id']."'>Movimientos</a>
                      </td>
                      <td>
                        <input class='chk' type='checkbox' value='".$LPP['id']."'>
                      </td>
                    </tr>";
            }
          ?>
          <tr>
            <td colspan='2'>
              <td>
                <button id='restaurar' onclick='accion(1)' class="btn btn-primary btn-sm">Autorizar</button>
              </td>
              <td>
                <button id='eliminar' onclick='accion(0)' class="btn btn-warning btn-sm">Eliminar</button>
              </td>
            </td>
          </tr>
      </div>
    </div>
  </div>
  <div class="row" id='verMovs' title='Ver Movimientos'>
    <div class="col-md-12" id='contenido'>
    </div>
  </div>
</div>