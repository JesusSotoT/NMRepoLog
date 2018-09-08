
<html lang="es">
<head>
    <meta http-equiv="Expires" content="0">
    <title>Lotes</title>
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/typeahead.css" />
    <link rel="stylesheet" href="css/caja/caja.css" />

    <?php include('../../netwarelog/design/css.php');?>
    <LINK href="../../netwarelog/design/<?php echo $strGNetwarlogCSS;?>/netwarlog.css" title="estilo" rel="stylesheet" type="text/css" /> <!--NETWARLOG CSS-->

  <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.1/themes/base/jquery-ui.css" />
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/lote/lote.js" ></script>
    <script type="text/javascript" src="js/typeahead.js" ></script>
    <script src="js/select2/select2.min.js"></script>
    <link rel="stylesheet" type="text/css" href="js/select2/select2.css" />

    <script>
        $(document).ready(function() {
               $("#cliente").select2({
                    width : "70px"
                });
         /* $.ajax({
            url: 'ajax.php?c=casio&f=cargaCliente',
            type: 'post',
            dataType: 'json',
            
          })
          .done(function(data) {
            console.log(data);
            $.each(data.cliente, function(index, val) {
                $('#cliente').append('<option value="'+val.id+'">'+val.nombre+'</option>');
            });
          })
          .fail(function() {
            console.log("error");
          })
          .always(function() {
            console.log("complete");
          });
          
        }); */

        $('#casioForm').submit(function(event) {
          alert('ieiieie');
        });
    </script>
<style type="text/css">
a:link {text-decoration:none;color:#000000;}
a:visited {text-decoration:none;color:#000000;}
a:active {text-decoration:none;color:#000000;}
a:hover {text-decoration:underline;color:#000000;}
</style>


</head>

<body>
<div id="contenido" class="col-xs-12 container-fluid">
   <form id="casio" action='ajax.php?c=casio&f=readFile' method="post" enctype="multipart/form-data">
  
  <div class="form-group">
    <label for="exampleInputFile">File input</label>
    <input type="file" id="exampleInputFile">
    <p class="help-block">Example block-level help text here.</p>
  </div>
  <div class="checkbox">

  <button type="submit" class="btn btn-default">Submit</button>
</form>
<form action="ajax.php?c=casio&f=readFile" method="post" enctype="multipart/form-data" id="casioForm">
    Select image to upload:
    <select name="" id="cliente">
      
    </select>
    <input type="file" name="fileToUpload" id="fileToUpload">
    <input type="submit" value="Upload Image" name="submit">
</form>

</div>
</body> 
</html>