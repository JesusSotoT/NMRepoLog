<script src="../cont/js/jquery-1.10.2.min.js"></script>
<script src="../cont/js/jquery-ui.js"></script>

<?php include('../../netwarelog/design/css.php');?>
<LINK href="../../netwarelog/design/<?php echo $strGNetwarlogCSS;?>/netwarlog.css" title="estilo" rel="stylesheet" type="text/css" /> <!--NETWARLOG CSS-->

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
			$.post("ajax.php?c=CaptPolizas&f=MovimientosPolizasEliminadas",
 		 {
    		IdPoliza: this.id
  		 },
  		 function(data)
  		 {
  		 	$('#contenido').html(data);
  		 	
  		 });
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
$("#todos").click(function(event) {
        $(".chk").click();
    });
});

function accion(a)
{
    var preg;
    var str = '0'
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
    if(str != '0')
    {
        if(a)
        {
            preg = confirm('Esta seguro que quiere restaurar esta(s) poliza(s)?.');
            if(preg)
            {
                $.post("ajax.php?c=CaptPolizas&f=RestaurarPoliza",
                 {
                    IdPoliza: str,
                    PDV:0  
                 },function(data)
                 {
                    location.reload(); 
                 });
            }
        }
        else
        {
            preg = confirm('Si elimina la(s) poliza(s) definitivamente ya no podra recuperarla despues.');
            if(preg)
            {
                $.post("ajax.php?c=CaptPolizas&f=EliminarPoliza",
                 {
                    IdPoliza: str
                 },function()
                 {
                    location.reload(); 
                 });
            }
        }
    }
    else
    {
        alert('Seleccione una poliza.')
    }
   
}


</script>
<style>
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
</style>

<div class="container">
  <?php
  if($PDV)
  {
      $cpdv = "/ <a href='index.php?c=CaptPolizas&f=ListaPolizasPDV'>Generadas PDV</a>";
  }
  ?>
  <h3 class="nmwatitles text-center">Lista de Polizas Inactivas <?php echo $cpdv; ?></h3>
  <div class="row nmsubtitle" id="nmsubtitle" style="width: unset !important;margin-top: .25em;">
    <div class="col-md-6">
      Polizas recuperables 
    </div>
    <div class="col-md-3 col-md-offset-3">
      <input type="text" class="form-control" id="buscar" name="buscar" placeholder="Buscar">
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="table-responsive">          
        <table class="table table-bordered" id='lista'>
          <tr style='background-color:#BDBDBD;color:white;font-weight:bold;'>
            <td>
              <b style='visibility:hidden;'>*1*</b># de Poliza
            </td>
            <td>
              Concepto
            </td>
            <td>
              Fecha
            </td>
            <td>
              Ver Movimientos
            </td>
            <td>
              <label>Seleccionar</label>
              <button id='todos' class="btn btn-default btn-sm">Todos</button>
            </td>
          </tr>
          <?php
            while($LPE = $ListaPolizasEliminadas->fetch_assoc())
            {
              echo "<tr onMouseOver=\"this.className='over'\" onMouseOut=\"this.className='out'\">
                      <td>
                        ".$LPE['idperiodo']."/".$LPE['numpol']."
                      </td>
                      <td>
                        ".$LPE['concepto']."
                      </td>
                      <td>
                        ".$LPE['fecha']."
                      </td>
                      <td>
                        <a href='#' class='movs' id='".$LPE['id']."'>Movimientos</a>
                      </td>
                      <td>
                        <input class='chk' type='checkbox' value='".$LPE['id']."'>
                      </td>
                    </tr>";
            }
          ?>
          <tr>
            <td colspan='3'>
              <td>
                <button id='restaurar' onclick='accion(1)' class="btn btn-primary btn-sm">Restaurar</button>
              </td>
              <td>
                <button id='eliminar' onclick='accion(0)' class="btn btn-warning btn-sm">Eliminar</button>
              </td>
            </td>
          </tr>
        </table>
      </div>
    </div>
  </div>
  <div class="row" id='verMovs' title='Ver Movimientos'>
    <div class="col-md-12" id='contenido'>
    </div>
  </div>
</div>