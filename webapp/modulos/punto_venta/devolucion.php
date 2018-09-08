<?php include("funcionesPv.php");?>
<!DOCTYPE HTML>
<html lang="es">
<head>
    <title>Punto de venta</title>
    <meta charset="utf-8" />
    <LINK href="../../netwarelog/catalog/css/view.css" title="estilo" rel="stylesheet" type="text/css" />
        <LINK href="../../netwarelog/catalog/css/estilo.css" title="estilo" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">  
    <link rel="stylesheet" href="punto_venta.css" />
    <script type="text/javascript" src="punto_venta.js" ></script>
    <script type="text/javascript">
    function valida(id){
       var cantNew =  $('#cant_'+id).val();
       var cant = $('#hideCa_'+id).val();

       if(cantNew > cant){
        alert('No puedes regresar mas de lo comprado');
        $('#cant_'+id).val(cant);
        $('#hideCa_').val(cant)
        return;
       }else{

        $('#cant_'+id).val(cantNew);
       }
        $('#cant_'+id).val(cantNew);
    }

    </script>
</head>
<body>

    
    <?php echo cargaDevolucion($_POST['id']); ?>
    
</body>
</html> 