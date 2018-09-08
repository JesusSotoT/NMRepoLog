<html>
	<head>
		<LINK href="../../../netwarelog/catalog/css/estilo.css" title="estilo" rel="stylesheet" type="text/css" />
        <?php include('../../../netwarelog/design/css.php');?>
        <LINK href="../../../netwarelog/design/<?php echo $strGNetwarlogCSS;?>/netwarlog.css" title="estilo" rel="stylesheet" type="text/css" /> <!--NETWARLOG CSS-->
       <script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.js"></script>
		<script type="text/javascript" src="js/familia.js"></script>	
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link href="../../../libraries/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <script src="../../libraries/bootstrap/dist/js/bootstrap.min.js"></script>
    <style type="text/css">
          .btnMenu{
              border-radius: 0; 
              width: 100%;
              margin-bottom: 1em;
              margin-top: 1em;
          }
          .row
          {
              margin-top: 1em !important;
          }
          .select2-container{
              width: 100% !important;
          }
          h4, h3{
              background-color: #eee;
              padding: 0.4em;
          }
          .nmwatitles, [id="title"] {
              padding: 8px 0 3px !important;
              background-color: unset !important;
          }
      </style>
	</head>
        <body id="seccion" onresize="redimensionar()"  style="width:100%;" >
            <div class="container">
              <div class="row">
                  <div class="col-md-12">
                      <h3 class="nmwatitles text-center">
                        Familia
                      </h3>
                  </div>
              </div>
              <h4>Seleccione una opción</h4>
              <section>
                <div class="row">
                  <div class="col-md-4">
                    <input class="btn btn-primary btnMenu"  type='button' onclick='abrir(1,0,0)' value='Agregar registro' />
                  </div>
                  <div class="col-md-4">
                    <input class="btn btn-success btnMenu" type='button' onclick='abrir(0,1,0)' value='Modificar registro' />
                  </div>
                  <div class="col-md-4">
                    <input class="btn btn-danger btnMenu" type='button' onclick='abrir(0,0,1)' value='Eliminar registro' />
                  </div>
                </div>
              </section>
              <section>
                <div class="row">
                  <div class="col-md-12">
                    <iframe id="opciones" frameborder=0 style="width:100%;border:none; height:500px;"> </iframe>
                  </div>
                </div>
              </section>
          </div>  
	</body>
</html>
