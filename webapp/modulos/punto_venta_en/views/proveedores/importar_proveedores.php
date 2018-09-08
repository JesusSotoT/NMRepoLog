<LINK href="../../../../netwarelog/catalog/css/estilo.css" title="estilo" rel="stylesheet" type="text/css" />
<LINK href="../../../../netwarelog/catalog/css/view.css"   title="estilo" rel="stylesheet" type="text/css" />
<!--  <link rel="stylesheet" type="text/css" href="../../../../netwarelog/design/default/netwarlog.css" / -->  	
<?php include('../../../../netwarelog/design/css.php');?>
<LINK href="../../../../netwarelog/design/<?php echo $strGNetwarlogCSS;?>/netwarlog.css" title="estilo" rel="stylesheet" type="text/css" /> <!--NETWARLOG CSS-->

<script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<link href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" rel="stylesheet" type="text/css"/>

<script type="text/javascript" src="../../../punto_venta/js/jquery.alphanumeric.js"></script>
<script type="text/javascript" src="../../../punto_venta/js/importar_proveedores.js"></script>

<link href="../../../../libraries/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="../../../../libraries/bootstrap/dist/js/bootstrap.min.js"></script>
<style>
  .row
  {
      margin-top: 0.5em !important;
  }
  h5, h4, h3{
      background-color: #eee;
      padding: 0.4em;
  }
  .modal-title{
    background-color: unset !important;
    padding: unset !important;
  }
  .nmwatitles, [id="title"] {
      padding: 8px 0 3px !important;
    background-color: unset !important;
  }
  @media only screen and (max-width: 520px){
    .smart{
      font-size: 2em !important;
      margin-left: 2em !important;
    }
  }
  .btn2{
    background-color: transparent; 
    border: 1px solid white; 
    color: white; 
    border-radius: 3px; 
    padding: 0.4em 1.5em;
    margin-bottom: 0.5em;
    margin-right: 1em;
  }
  .btn2:hover{
    background-color: white;
    color: rgba(0, 0, 0, 0.6);
  }
  .btn3{
    background-color: transparent; 
    border: 1px solid white; 
    color: white; 
    border-radius: 3px; 
    padding: 0.4em 0.4em;
    margin-bottom: 0.5em;
    margin-right: 1em;
    margin-top: 1em;
  }
  .btn3:hover{
    background-color: white;
  }
</style>

<!-- ///////////////////////////// -->	 

<div class="container">
	<div class="row">
		<div class="col-md-1 col-sm-1">
		</div>
		<div class="col-md-10 col-sm-10">
			<h3 class="nmwatitles text-center">Importar proveedores</h3>
      <section style="min-height: 550px; background: transparent url('../../img/proveedores.png') no-repeat scroll center center / cover ;">
        <div class="row">
          <div class="col-md-9 col-sm-9 col-xs-9">
            <label style="font-weight: 100; font-size: 2.2em; padding-top: 0.75em; padding-left: 0.8em; color: white; letter-spacing: 0.03em;">Bienvenido al portal donde<br>podras descargar y administrar.</label>
          </div>
          <div class="col-md-3 col-sm-3 col-xs-3 text-right">
            <button class="btn3" onclick="$('#modal2').modal('show');" onmouseover="$('#sr_img').attr('src', '../../img/icono_nota_gris.png');" onmouseout="$('#sr_img').attr('src', '../../img/icono_nota_blanco.png');"><img id="sr_img" src="../../img/icono_nota_blanco.png" style="width: 1.5em;"></button>
          </div>
        </div>
        <div class="row" style="margin-top: 5em !important;">
          <div class="col-md-2 col-sm-2 col-xs-2">
            <img src="../../img/icono_proveedores.png" style="width: 6em; margin-left: 1.2em;">
          </div>
          <div class="col-md-10 col-sm-10 col-xs-10">
            <label class="smart" style="color: white; font-weight: 200; font-size: 4em; letter-spacing: 0.03em;">¡Tus <strong>proveedores!</strong></label>
          </div>
        </div>
        <?php
          $url = '../../funcionesBD/importar_proveedores.php';
        ?>
        <form id="myForm" action=<?php echo $url; ?> method="post" enctype="multipart/form-data">
          <div class="row" style="margin-top: 6em !important;">
            <div class="col-md-12 text-center">
              <button type="button" class="btn2 btn_1">Examinar</button>
              <input type='hidden' value='subirArchivo' name='funcion'>
              <input type="file" size="100" name="myfile" class="hidden myfile">
              <button type="button" class="btn2 btn_2">Descargar</button>
              <button class="btn2" type="submit" id="btnarchivo">Previsualizar</button>
            </div>
          </div>
        </form>
        <script type="text/javascript">
          $(".btn_1:first").on("click", function(){ $(".myfile:first").click(); });
          $(".btn_2:first").on("click", function(){ $('#modal').modal('show'); });
        </script>
      </section>
		</div>
	</div>
</div>

<div id='modal' class="modal fade" tabindex="-1" role="dialog" >
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Descargar plantillas</h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <img src='../../img/xls_icon.gif'> <a href='plantilla.xlsx'>Descarga la plantilla para los proveedores</a>
            <hr/>
            <img src='../../img/xls_icon.gif'> <a href='estados_municipios.xlsx'>Descarga catálogo de Estados y municipios</a>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <div class="col-md-6 col-md-offset-6">
          <input class="btn btn-danger btnMenu" type='button' value='Cerrar' onclick="$('#modal').modal('hide');">
        </div>
      </div>
    </div>
  </div>
</div>

<div id='modal2' class="modal fade" tabindex="-1" role="dialog" >
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Información</h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <b>Nota:</b><br>
            ·La lista de proveedores no debe rebasar los 900 elementos por carga.<br>
            ·No se deben insertar comillas (") ni comillas simples (') en ningún campo<br>
            ·En los campos de estado y municipio solo deben insertarse números<br>
            ·El valor en estado y municipio deben tomarse del archivo Catalogo de Estados y Municipio<br>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <div class="col-md-6 col-md-offset-6">
          <input class="btn btn-danger btnMenu" type='button' value='Cerrar' onclick="$('#modal2').modal('hide');">
        </div>
      </div>
    </div>
  </div>
</div>
