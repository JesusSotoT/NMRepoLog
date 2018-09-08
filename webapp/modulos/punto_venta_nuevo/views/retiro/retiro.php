<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Retiro de Caja</title>
        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/typeahead.css" />
    <link rel="stylesheet" href="css/caja/caja.css" />
    <?php include('../../netwarelog/design/css.php');?>
    <LINK href="../../netwarelog/design/<?php echo $strGNetwarlogCSS;?>/netwarlog.css" title="estilo" rel="stylesheet" type="text/css" /> <!--NETWARLOG CSS-->
 <!--   <link rel="stylesheet" href="http://cdn.datatables.net/1.10.8/css/jquery.dataTables.min.css"> -->
    <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.1/themes/base/jquery-ui.css" />
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/caja/caja.js" ></script>
    <script type="text/javascript" src="js/retiro/retiro.js" ></script>
    <script type="text/javascript" src="js/typeahead.js" ></script>
    <script type="text/javascript" src="js/caja/punto_venta.js" ></script>
    <script type="text/javascript" src="../punto_venta/js/jquery.alphanumeric.js" ></script>
<!--  <script type="text/javascript" src="http://cdn.datatables.net/1.10.8/js/jquery.dataTables.min.js"></script>
   <script type="text/javascript" src="js/jquery.dataTables.min.js" ></script>
    <script type="text/javascript" src="js/table.js" ></script>
    <script type="text/javascript" src="http://cdn.datatables.net/1.10.2/js/jquery.dataTables.min.js"></script> -->
    <script>
    $(document).ready(function() {
        pintatabla();
        usuarios();
       $.datepicker.regional['es'] = {
             closeText: 'Cerrar',
             prevText: '<Ant',
             nextText: 'Sig>',
             currentText: 'Hoy',
             monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
             monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
             dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
             dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
             dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sa'],
             weekHeader: 'Sm',
             dateFormat: 'dd/mm/yy',
             firstDay: 1,
             isRTL: false,
             showMonthAfterYear: false,
             yearSuffix: ''
        };
        $.datepicker.setDefaults($.datepicker.regional['es']);
        $("#desde").datepicker({
            firstDay: 1,
            dateFormat: 'yy-mm-dd' 
        });
        $("#hasta").datepicker({
            firstDay: 1,
            dateFormat: 'yy-mm-dd' 
        });

    }); 

    </script>
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
        h4, h3{
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
        .select2-container{
            width: 100% !important;
        }
        .select2-container .select2-choice{
            background-image: unset !important;
            height: 31px !important;
        }
        .tablaResponsiva{
            max-width: 100vw !important; 
            display: inline-block;
        }
    </style>
<body>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h3 class="nmwatitles text-center">
                    Retiro de Efectivo
                </h3>
            </div>
        </div>
        <h4>Cantidad y concepto</h4>
        <section>
            <div class="row">
                <div class="col-md-6">
                    <label>Cantidad:</label>
                    <input type="number" class="form-control" id="cantidad">
                </div>
                <div class="col-md-6">
                    <label>Concepto:</label>
                    <input type="text" class="form-control" id="concepto">
                </div>
            </div>
            <div class="row">
                <div class="col-md-2">
                    <button type="button" id="btnGuarda" class="btn btn-success btnMenu" onclick="this.disabled='disabled'; retira()">Guardar</button>
                </div>
            </div>
        </section>
        <h4>Filtrado:</h4>
        <section>
            <div class="row">
                <div class="col-md-3">
                    <label>Desde:</label>
                    <input type="text" id="desde" class="form-control">
                </div>
                <div class="col-md-3">
                    <label>Hasta:</label>
                    <input type="text" id="hasta" class="form-control">
                </div>
                <div class="col-md-3">
                    <label>Usuario:</label>
                    <select id="usuario" class="form-control">
                    </select>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary btnMenu" onclick="filtra();">Buscar</button>
                </div>
            </div>
        </section>
        <section>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12 tablaResponsiva" style="margin-bottom:5em;">
                    <div class="table-responsive">
                        <table class="table display" id="tablita"  cellspacing="0">
                            <thead>
                              <tr>
                                <th class="nmcatalogbusquedatit">ID</th>
                                <th class="nmcatalogbusquedatit">Cantidad</th>
                                <th class="nmcatalogbusquedatit">Concepto</th>
                                <th class="nmcatalogbusquedatit">Usuario</th>
                                <th class="nmcatalogbusquedatit">Fecha</th>
                                <th class="nmcatalogbusquedatit">Imprimir</th>
                              </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>

</body>
</html>