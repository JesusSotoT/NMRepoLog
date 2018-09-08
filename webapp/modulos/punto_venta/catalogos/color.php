<html>
	<head>
		<LINK href="../../../netwarelog/catalog/css/estilo.css" title="estilo" rel="stylesheet" type="text/css" />
        <?php include('../../../netwarelog/design/css.php');?>
        <LINK href="../../../netwarelog/design/<?php echo $strGNetwarlogCSS;?>/netwarlog.css" title="estilo" rel="stylesheet" type="text/css" /> <!--NETWARLOG CSS-->
       <script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.js"></script>
		<script type="text/javascript" src="js/color.js"></script>	
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	</head>
        <body id="seccion" onresize="redimensionar()"  style="width:100%;" >
                                  
            <div height="20">
                <div class="nmwatitles">Color</div>
                <br>
                <input class=" nminputbutton " style=" padding-left: 30px; background-image: url(../../../netwarelog/design/default/reg_add.png) " type='button' onclick='abrir(1,0,0)' value='Agregar registro' />
                <input class=" nminputbutton " style=" padding-left: 30px; background-image: url(../../../netwarelog/design/default/reg_upd.png) " type='button' onclick='abrir(0,1,0)' value='Modificar registro' />
                <input class=" nminputbutton " style=" padding-left: 30px; background-image: url(../../../netwarelog/design/default/reg_del.png) " type='button' onclick='abrir(0,0,1)' value='Eliminar registro' />
           </div>
          <iframe id="opciones" frameborder=0 style="width:100%;border:none; height:500px;"> </iframe>
	</body>
</html>
