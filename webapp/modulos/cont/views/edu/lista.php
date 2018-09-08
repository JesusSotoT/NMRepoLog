<link rel="stylesheet" href="css/clndr.css">
<script type="text/javascript" src="../../libraries/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="js/moment.js" type="text/javascript"></script>
<script src="js/underscore.js"></script>
<script src="js/clndr.js"></script>
<script src="js/eventos.js"></script>
<script src="js/redirect.js" type="text/javascript" language="javascript" charset="UTF-8"></script>
<link rel="stylesheet" href="css/listaDespacho.css">

<script language='javascript'>
$(document).ready(function(){
	//Removemos el attr de disabled de los botones para inicar el calendario
	$('.clndr').each(function(){
		$(this).removeAttr('disabled');
	});
	iniciar_calendario();

  $('.semaforo').on('click', function(){
    if ($(this).attr('data-estatus') !== undefined || $(this).attr('data-estatus') !== false ) {
      var semaforo = $(this).siblings();
      if (!semaforo.is(':visible')) {
        semaforo.show();
      }
    }
  });

  $('.close_btn').on('click', function(){
    var semaforo = $(this).parent();
    semaforo.hide();
  });

  $('.boton-estatus').on('click', function (){
    //Obtenemos el estatus que contiene el boton del semaforo
    var estatus_nuevo = $(this).attr('data-estatus');
    //Obtenemos el contenedor del estatus actual de la lista
    var boton_gris = $(this).parent().siblings();
    //Obtenemos el estatus actual de la lista
    var estatus_actual = boton_gris.attr('data-estatus');
    //Obtenemos el id actual de la lista
    var id_instancia = boton_gris.attr('data-id');
    //Validamos que el estatus sea diferente de "gris" o no definido.
    $.post('ajax.php?c=edu&f=cambiar_estatus',
    {
      estatus: estatus_nuevo,
      instancia: id_instancia,
      mes: $('#periodo').val(),
      anio: $('#ejercicio').val(),
      estatus_actual: estatus_actual
    },
    function(data){
      console.log(data);
      if (data.resultado) {
        if (estatus_nuevo == 0) {
          boton_gris.removeClass('amarillo');
          boton_gris.removeClass('verde');
          boton_gris.addClass('rojo');
        } else if (estatus_nuevo == 1){
          boton_gris.removeClass('rojo');
          boton_gris.removeClass('verde');
          boton_gris.addClass('amarillo');
        } else if (estatus_nuevo == 2){
          boton_gris.removeClass('rojo');
          boton_gris.removeClass('amarillo');
          boton_gris.addClass('verde');
        }
      } else {
        alert("Hubo un error y no se pudo cambiar el estatus.")
      }
    }, "JSON");
  });

});

$(function() {
	$("#grupo").val("<?php echo $grupo ?>");
	$("#ejercicio").val("<?php echo $ejercicio ?>");
	$("#periodo").val("<?php echo $periodo ?>");
 });

function revisado(id)
{
	$.post('ajax.php?c=edu&f=revisado', 
		{
			idregistro: id
		}, 
		function(data)
		{
			$("#ult-"+id).html(data+" <span class='glyphicon glyphicon-ok' aria-hidden='true'></span>").css("color","green")
		});
}
</script>
<?php
if($tipo == 'u')
{
    $titulo = "Alumnos";
    $url = "http://edu.netwarmonitor.com/htdocs/clientes";
}
if($tipo == 'n')
{
    $url = "http://www.netwarmonitor.mx/clientes/";
    $titulo = "Instancias";
}

?>
<div class="container well">
  <div class="row">
    <div class="col-xs-12 col-md-12">
      <h3 id="titulo_lista" data-inst="<?php echo($instancia); ?>">Avance de <?php echo $titulo;?></h3>
    </div>
  </div>
  <form action='' method='post'>
  <div class="row">
    <div class="col-xs-12 col-md-3">
      <b>Grupo:</b> 
      <select id='grupo' name='grupo' class='select_edu'>
        <option value='0'>Todos</option>
        <?php
        while($gp = $lista_grupos->fetch_assoc())
        {
            echo "<option value='".$gp['idgrupo']."'>".$gp['descripcion']."</option>";
        }
        ?>
      </select>
    </div>
      <div class="col-xs-12 col-md-3">
        <b>Ejercicio:</b> 
        <select id='ejercicio' name='ejercicio' class='select_edu'>
          <?php
          echo $ejercicios;
          ?>
        </select>
      </div>
      <div class="col-xs-12 col-md-3">
        <b>Periodo:</b> 
        <select id='periodo' name='periodo' class='select_edu'>
          <option value='1'>Enero</option>
          <option value='2'>Febrero</option>
          <option value='3'>Marzo</option>
          <option value='4'>Abril</option>
          <option value='5'>Mayo</option>
          <option value='6'>Junio</option>
          <option value='7'>Julio</option>
          <option value='8'>Agosto</option>
          <option value='9'>Septiembre</option>
          <option value='10'>Octubre</option>
          <option value='11'>Noviembre</option>
          <option value='12'>Diciembre</option>
        </select>
      </div>
      <div class="col-xs-12 col-md-3">
        <button type='submit' class='btn btn-primary'>Buscar <span class='glyphicon glyphicon-search' aria-hidden='true'></span></button>
      </div>
  </div>
  </form>
  <div class="row">
    <div class="table-responsive">
      <table class="table table-striped">
        <tr>
        	<th>Link Instancia</th>
        	<th>Nombre</th>
        	<th>Cuentas</th>
        	<th>Polizas</th>
        	<th>Reporte B3</th>
        	<th>Balance Gral</th>
        	<th>Est Resultados</th>
        	<th></th>
        	<th>Ultima revisión</th>
        	<th>Ultimo acceso</th>
        	<th>Evento</th>
          <th>Estatus</th>
        </tr>
        <?php
        while($al = $alumnos->fetch_object())
        {
          $ultima_revision = "<span class='label label-danger'>Nunca revisado</span>";
          if($al->ultima_revision != NULL)
            $ultima_revision = $al->ultima_revision;
      
          $polizasEstudiante = $cuentasEstudiante = $bdestudiante = 0;
          if($al->nombre_db != "")
          {
            $cuentasEstudiante = $this->cuentasEstudiante($al->nombre_db);
            $polizasEstudiante = $this->polizasEstudiante($al->nombre_db,$ejercicio,$periodo);
            $bdestudiante = $al->nombre_db;
          }

          if(!intval($cuentasEstudiante))
              $cuentasEstudiante = "<i style='color:red;'>No tiene cuentas</i>";

          if(!intval($polizasEstudiante))
              $polizasEstudiante = "<i style='color:red;'>No tiene polizas</i>";

          $ru = explode(' ',$ultima_revision);
          $ru = explode('-',$ru[0]);
          $fechaHoy = new DateTime(date("Y-m-d H:i:s"));
          $fechaHoy->modify("-7 hours");
          if($fechaHoy->format("Y-m-d")>date($ru[0]."-".$ru[1]."-".$ru[2]) || $ultima_revision == NULL)
          {
            $label_ultima_revision = $ultima_revision;
            $style='';
          }
          else
          {
            $style="style='color:green;'";
            $label_ultima_revision = $ultima_revision." <span class='glyphicon glyphicon-ok' aria-hidden='true'></span>";
          }

          $estatus = $this->obtener_estatus($periodo,$ejercicio,$al->idalumno);
          $estatus = $estatus['estatus'];
          //$estatus->fetch_asscoc();
          if ($estatus == '0') {
            $color = 'rojo';
          } else if($estatus == '1'){
            $color = 'amarillo';
          } else if($estatus == '2'){
            $color = 'verde';
          }

          echo "
        	<tr>
        		<td>
        			<a href='$url/$al->instancia/webapp/netwarelog/accelog/index.php' target='_blank'>$al->instancia</a>
        		</td>
        		<td>".utf8_encode($al->razon)."</td>
        		<td>$cuentasEstudiante</td>
        		<td>$polizasEstudiante</td>
        		<td>
        			<form name='b3-$al->instancia' action='index.php?c=edu&f=b3&tipo=$tipo' method='post' target='_blank'>
        				<input type='hidden' name='datos' value='$al->nombre_db--*--/*-$ejercicio--*--/*-$periodo--*--/*-$al->razon'>
       		 			<button type='submit' class='btn btn-info'>
       		 				B3 <span class='glyphicon glyphicon-modal-window' aria-hidden='true'></span>
     		 				</button>
        			</form>
        		</td>
          	<td>
          		<form name='bg-$al->instancia' action='index.php?c=edu&f=bg&tipo=$tipo' method='post' target='_blank'>
                <input type='hidden' name='datos_bg' value='$al->nombre_db--*--/*-$ejercicio--*--/*-$periodo--*--/*-$al->razon'>
                <button type='submit' class='btn btn-info'>
                	Balance <span class='glyphicon glyphicon-modal-window' aria-hidden='true'></span>
                </button>
              </form>
          	</td>
          	<td>
          		<form name='er-$al->instancia' action='index.php?c=edu&f=er&tipo=$tipo' method='post' target='_blank'>
          			<input type='hidden' name='datos_er' value='$al->nombre_db--*--/*-$ejercicio--*--/*-$periodo--*--/*-$al->razon'>
          			<button type='submit' class='btn btn-info'>
          				E. Result <span class='glyphicon glyphicon-modal-window' aria-hidden='true'></span>
          			</button>
          		</form>
          	</td>
          	<td>
          		<button class='btn btn-primary' onclick='revisado($al->id)'>
          			Revisar <span class='glyphicon glyphicon-ok' aria-hidden='true'></span>
          		</button>
          	</td>
          	<td id='ult-$al->id' $style title='".$this->listado_revisiones($al->id)."'>
          		$label_ultima_revision
          	</td>
          	<td>".$this->ultima_conexion($al->idalumno)."</td>
          	<td id='ult-$al->id'>
							<a id='$al->idalumno' class='clndr btn btn-default' role='button' disabled>
								<span class='glyphicon glyphicon-calendar' aria-hidden='true'></span>
							</a>
						</td>
            <td class='estatus-container'>
              <div class='semaforo ".$color."' data-estatus='".$estatus."' data-id='".$al->idalumno."'></div>
              <div class='semaforo-cambiar'>
                <div class='boton-estatus semaforo rojo' data-estatus='0'></div>
                <div class='boton-estatus semaforo amarillo' data-estatus='1'></div>
                <div class='boton-estatus semaforo verde' data-estatus='2'></div>
                <div class='glyphicon glyphicon-remove close_btn'></div>
              </div>
            </td>
					</tr>";
        }
        ?>
      </table>      
    </div>
  </div>
</div>

<div class="modal fade" id="clndr-container" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <span class="cldnr-holder">
    	 <div class="cal1"></div>
      </span>
			<section id="eventos" class="container" 
			style="
			top: 0; 
			left: 0; 
			z-index: 99;
			width: 100%; 
			height: 80%;
			display: none; 
			padding-top: 3em;
			background: white; 
			position: absolute; 
			">
				<a class="close-btn" onclick="mostrarEventosCal(0);" style="z-index:100;">
					<i id="cerrar-noticias" class="material-icons black-text">close</i>
				</a>
				<div class="row">
					<div class="col-md-12">
						<div id="evento-loading">
							<h5 class="black-text">Cargando...</h5>
						</div>
						<div id="lista-evento"></div>
					</div>
				</div>
			</section>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">
          Close
        </button>
      </div>
    </div>
  </div>
</div>