<?PHP
session_start();
$_SESSION['nombreestructura']="mrp_orden_compra";
$_SESSION['idestructura']=83;
$_SESSION['descripcion']='';
$_SESSION['utilizaidorganizacion']=false;

$base_url='../../../../';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="sp">
	<head>
   <!-- <link rel="stylesheet" type="text/css" href="<?php echo $base_url;?>webapp/netwarelog/design/default/netwarlog.css" / --> 
<?php include('../../netwarelog/design/css.php');?>
<LINK href="../../../netwarelog/design/<?php echo $strGNetwarlogCSS;?>/netwarlog.css" title="estilo" rel="stylesheet" type="text/css" /> <!--NETWARLOG CSS-->
    
  <LINK href="<?php echo $base_url; ?>webapp/netwarelog/catalog/css/estilo.css" title="estilo" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="<?php echo $base_url; ?>webapp/modulos/mrp/js/comun.js"></script>
		<script src="../../../../modulos/mrp/busqueda.js" type="text/javascript"></script>

		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>Orden de compra</title>
	</head>

        <body id="seccion" onResize="redimensionar()"  style="width:100%;" >
                                  
            <div height="20">
                <div class="nmwatitles">Orden de compra</div>
                <br>
                <input class='button nminputbutton' type='button' onclick='abrir_orden_compra(1,0,0)' value='Agregar registro' style=' padding-left: 30px; background-image: url(../../../netwarelog/design/default/reg_add.png) '/> 
                <input class='button nminputbutton' id='modificar_button' type='button' onclick='abrir_orden_compra(0,1,0)' value='Modificar registro' style=' padding-left: 30px; background-image: url(../../../netwarelog/design/default/reg_upd.png) '/> 
                <input class='button nminputbutton' type='button' onclick='abrir_orden_compra(0,0,1)' value='Eliminar registro' style=' padding-left: 30px; background-image: url(../../../netwarelog/design/default/reg_del.png) '/>           
           </div>

          <iframe id="opciones" frameborder=0 style="width:100%;border:none; height:500px;"></iframe>
               

	</body>
</html>