<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Lotes</title>
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/typeahead.css" />
    <link rel="stylesheet" href="css/caja/caja.css" />

    <?php include('../../netwarelog/design/css.php');?>
    <LINK href="../../netwarelog/design/<?php echo $strGNetwarlogCSS;?>/netwarlog.css" title="estilo" rel="stylesheet" type="text/css" /> <!--NETWARLOG CSS-->


    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/lote/lote.js" ></script>
    <script type="text/javascript" src="js/typeahead.js" ></script>
    <script src="js/select2/select2.min.js"></script>
    <link rel="stylesheet" type="text/css" href="js/select2/select2.css" />

    <script>
        $(document).ready(function() {
            //pintaGrid();
            $("#folio").select2({
             width : "100px"
            });
            $("#convocatoria").select2({
                 width : "150px"
            }); 
            $("#organismo").select2({
                 width : "150px"
            }); 
        });
    </script>
</head>
<body>
<div id="contenido" class="col-xs-12 container-fluid">
   <div class="nmwatitles">Series del Lote <?php echo $idLote ?></div>
    <div class="panel panel-default">
        <div class="panel-heading">Numeros de serie</div>
            <div class="panel-body">
                <div class="col-xs-12">
                    <div class="col-xs-3">
                        <label>LOTE:</label>
                        <input type="hidden" id="idloteinput" value="<?php echo $idLote ?>">
                        <?php echo $idLote ?>
                    </div>
                    <div class="col-xs-3">
                        <label>Producto:</label>
                        <input type="hidden" id="idProductoInput" value="<?php echo $datosLote['datos'][0]['idProducto']; ?>">
                       <?php echo $datosLote['datos'][0]['nombre']; ?>
                    </div>
                    <div class="col-xs-3">
                        <label>Cantidad:</label>
                        <input type="hidden" id="cantidadInput" value="<?php echo $datosLote['datos'][0]['cantidad']; ?>">
                        <?php echo $datosLote['datos'][0]['cantidad']; ?>
                    </div>
                    <div class="col-xs-3">
                        <label>Orden de Compra:</label>
                        <?php echo $datosLote['datos'][0]['idOrdeCom']; ?>
                    </div>
                </div>
                <div class="col-xs-12">
                    <div class="col-xs-3">
                        <label>Fecha recepcion:</label>
                        <?php echo $datosLote['datos'][0]['fecha_recibido']; ?>
                    </div>   
                    <div class="col-xs-3">
                        <label>Fecha recepcion:</label>
                        <?php echo $datosLote['datos'][0]['fecha_caducidad']; ?>
                    </div>                        
                </div>
                <div class="col-xs-12">
                    <label>No. de Serie :</label>
                    <input type="text" id="serie" class="nminputtext">
                    <!--<input type="button" value="Agregar numero de serie" onclick="addSerie();" calss="btn btn-success"> -->
                    <button type="button" class="btn btn-success" onclick="addSerie();">Agregar numero de serie</button>
                </div>    
                <div class="col-xs-12">
                    <table class="table table-striped" id="serialTable">
                        <thead>
                            <tr>
                                <th>No. Serie</th>
                            </tr>
                        </thead> 
                        <?php 
                        $rowSeries='';
                        $contador = 0;
                        $reg = count($series['series']);
                        $div=4;
                        $cols=$reg/$div;
                        $pag=1;

                        $show=0;

                        $filas  = ceil($cols);
                        $tds= $filas*$div;

                        for ($i=0; $i <$cols ; $i++) { 
                            $rowSeries .="<tr class='rowsSeries'>";
                            foreach ($series['series'] as $key => $value) {

                                if( $key<=($div*$pag)-1 && $key>=($div*($pag-1)) ){
                                    $rowSeries .="<td>".$value['serie']."</td>"; 
                                    $show++;
                                } 

                                if($show>=$div*$pag) break;
                                
                            }
                            if($pag>=$cols && $show!=$tds){
                                for ($d=$show; $d <=$tds; $d++) { 
                                    $rowSeries .="<td>&nbsp;</td>"; 
                                    $d++;
                                }
                            }
                            $pag++;
                            $rowSeries .="</tr>";
                        }
                            echo $rowSeries;

                        ?>   
                    </table>
                </div>
            </div>  
    </div>
   <input type="button" value="Regresar" onclick="goBack();" class="nminputbutton_color2">
</div>   
</body>
</html>