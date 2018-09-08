<?php 
//ini_set('display_errors', 1);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Etiquetado</title>
    <link rel="stylesheet" href="">
</head>
    <link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../libraries/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="imprimir_bootstrap.css">
    <script src="../../libraries/jquery.min.js"></script>
    <script src="../../libraries/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="js/inventario.js"></script>
<!--Select 2 -->
    <script src="../../libraries/select2/dist/js/select2.min.js"></script>
    <link rel="stylesheet" type="text/css" href="../../libraries/select2/dist/css/select2.min.css" />

    <link rel="stylesheet" href="../../libraries/dataTable/css/datatablesboot.min.css">
    <script src="../../libraries/dataTable/js/datatables.min.js"></script>
    <script src="../../libraries/dataTable/js/dataTables.bootstrap.min.js"></script>
    <!-- Codigo de barras -->
    <script src="https://cdn.jsdelivr.net/jsbarcode/3.3.7/JsBarcode.all.min.js"></script>



    <script src="https://cdn.datatables.net/buttons/1.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.4.2/js/buttons.flash.min.js"></script>
    <script src="https:////cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.4.2/js/buttons.print.min.js"></script>
   <script>
   $(document).ready(function() {
    JsBarcode("#barcode", "0123456789012340",{width: 2});


    $('#productos').select2();
    var table = $('#tableGrid').DataTable({
         dom:"Bfrtip",
         buttons: [
            {
                extend: 'excel',
                text: 'Exportar',
                filename: 'Etiquetas',
                title: null,
                exportOptions: {
                    columns: [2,3,6,4,5],
                    
                }
            }
        ],

                        bPaginate: false,
                        language: {
                        search: "Buscar:",
                        zeroRecords: "No hay datos.",
                        infoEmpty: "No hay datos que mostrar.",
                        info:"Mostrando del _START_ Inicio al _END_ de _TOTAL_ elementos",
                        paginate: {
                            first:      "Primero",
                            previous:   "Anterior",
                            next:       "Siguiente",
                            last:       "Último"
                        }
                     }
    });
    $('#tableGrid').on('click', '.fa-list-ul', function () {
            var tr = $(this).closest('tr');

            var row = table.row(tr);

            if (row.child.isShown()) {
                // This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
            } else {
                // Open this row
                row.child.show();
                tr.addClass('shown');
            }
        });

    $('#colorLabel').on('input', function() { 
        $('#colorEtique').css('background-color', $(this).val());
    });
     $('#colorLetter').on('input', function() { 
        $('.letra').css('color', $(this).val());
    });

        $('.propiedades').click(function(){

            /*if( !$('[name="etiqueta"]').prop('checked') ) {
                //alert('No Seleccionado');
                $('[name="nombre"]').prop('checked', false)
                $('[name="precio"]').prop('checked', false)

                $('[name="nombre"]').prop('disabled', true);
                $('[name="precio"]').prop('disabled', true);

                $('#colorEtique').hide();

            }else if(!$('[name="nombre"]').prop('checked') ){
                
                $('#nombreProd').show();
            }else if(!$('[name="precio"]').prop('checked') ){
                $('#precioProd').show();
            }else if(!$('[name="codigo"]').prop('checked')){
                $('#codProd').hide();
            }else if($('[name="codigo"]').prop('checked')){
                $('#codProd').show();
            }else if($('[name="precio"]').prop('checked')){
                $('#precioProd').show();
            }else if($('[name="nombre"]').prop('checked')){
                $('#nombreProd').show();
            }else{
                                //alert('No Seleccionado');
                $('[name="nombre"]').prop('checked', true)
                $('[name="precio"]').prop('checked', true)

                $('[name="nombre"]').prop('disabled', false);
                $('[name="precio"]').prop('disabled', false);

                $('#colorEtique').show();
                $('#nombreProd').show();
                $('#codProd').show();
                $('#precioProd').show();
            } */

            if(!$('[name="etiqueta"]').prop('checked')){
                $('#colorEtique').hide('slow');
            }else{
                $('#colorEtique').show('slow');
            }
            
            if(!$('[name="nombre"]').prop('checked')){
                $('#nombreProd').hide('slow');
            }else{
                $('#nombreProd').show('slow');
            }

            if(!$('[name="precio"]').prop('checked')){
                $('#precioProd').hide('slow');
            }else{
                $('#precioProd').show('slow');
            }

            if(!$('[name="caracteriricas"]').prop('checked')){
                $('#caractProd').hide('slow');
            }else{
                $('#caractProd').show('slow');
            }

            if(!$('[name="codigo"]').prop('checked')){
                $('#codProd').hide('slow');
            }else{
                $('#codProd').show('slow');
            }





              /*if($('[name="unidadesCheck"]').is(':checked')){
                $('.2').attr('disabled', 'disabled');
                $('.3').attr('disabled', 'disabled');
              
              } else if($('[name="seriesCheck"]').is(':checked')){
                $('.1').attr('disabled', 'disabled');
                $('.2').attr('disabled', 'disabled');
                $('.5').attr('disabled', 'disabled');
                $('#costeoSelect > option[value="1"]').prop('selected', false);
                $('#costeoSelect > option[value="6"]').prop('selected', true);
                $('#costeoSelect').select2({ width: '350px' }); 
                $('#costeoSelect').attr('disabled', 'disabled');
              }else if($('[name="caracCheck"]').is(':checked')){
                $('.1').attr('disabled', 'disabled');
                $('.3').attr('disabled', 'disabled');
              }else if($('[name="lotesCheck"]').is(':checked')){
                $('.3').attr('disabled', 'disabled');
              }else{
                $('.propiedades').prop('disabled', false);
                $('.1').prop('disabled', false);
                $('.2').prop('disabled', false);
                $('#costeoSelect > option[value="6"]').prop('selected', false);
                $('#costeoSelect > option[value="1"]').prop('selected', true);
                $('#costeoSelect').prop('disabled', false);
                
                $('#costeoSelect').select2({ width: '350px' }); 

              } */

        });

   });
   </script>
   <style>
        a.dt-button {
            position: relative;
            display: inline-block;
            box-sizing: border-box;
            margin-right: 0.333em;
            padding: 0.5em 1em;
            border: 1px solid #999;
            border-radius: 2px;
            cursor: pointer;
            font-size: 0.88em;
            color: black;
            white-space: nowrap;
            overflow: hidden;
            background-color: #e9e9e9;
            background-image: -webkit-linear-gradient(top, #fff 0%, #e9e9e9 100%);
            background-image: -moz-linear-gradient(top, #fff 0%, #e9e9e9 100%);
            background-image: -ms-linear-gradient(top, #fff 0%, #e9e9e9 100%);
            background-image: -o-linear-gradient(top, #fff 0%, #e9e9e9 100%);
            background-image: linear-gradient(to bottom, #fff 0%, #e9e9e9 100%);
            filter: progid:DXImageTransform.Microsoft.gradient(GradientType=0,StartColorStr='white', EndColorStr='#e9e9e9');
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            text-decoration: none;
            outline: none;
        }
        #colorEtique {
            border: 2px solid;
            border-radius: 25px;
        }

        @media print{
            .saltopagina{
                  display:block !important;
                  page-break-after: always !important;
                  background: red !important;
            } 
        }

   </style>
<body>  
<div class="container well">
        <div class="row">
            <h3>Etiquetado</h3>
        </div>
 <!--   <div class="row">
            <div class="col-sm-2">
                <button class="btn btn-default btn-block" onclick="cambia2();">Listado</button>
            </div>
            <div class="col-sm-2">
                <button class="btn btn-default btn-block" onclick="cambia1();">Nueva Merma</button>
            </div>
        </div> -->
        <div class="row">
            <div class="col-sm-12" style="overflow:auto;">
                <div class="panel panel-default">
                    <!-- <div class="panel-heading">
                       <h3 class="panel-title">Panel title</h3>
                        <div class="row">
                            <div class="col-sm-4">
                                <label>Producto</label>
                                <select id="productos" class="form-control">
                                    <option value="0">-Selecciona Producto-</option>
                                    <?php 
                                        // foreach ($productosGrid['productos'] as $key1 => $value1) {
                                        //     echo '<option value="'.$value1['id'].'">'.$value1['nombre'].'/'.$value1['codigo'].'</option>';
                                        // }
                                    ?>
                                </select>
                            </div>
                            <div class="col-sm-4">
                                <div style="padding-top:6%">
                                    <button class="btn btn-primary" type="button">Buscar</button>
                                </div>
                            </div>
                            <div class="col-sm-4"></div>
                        </div>
                    </div> -->

                    <!-- <div class="panel-heading">
                        <div class="row">
                            <div class="col-sm-4">
                                        <select class="erre form-control " id="selectProducto" >

                                        </select>
                                    </div>
                            <div class="col-sm-2">
                                    <i class="btn fa fa-search fa-2x" aria-hidden="true" onclick="resetFilters();" style="background-color: rgba(0, 0, 0, 0.1); border-radius: 100%;"></i>
                            </div>
                        </div>
                    </div> -->
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <h4>Configura Etiqueta</h4>
                            </div>   
                        </div>
                        <div class="row"><!-- Inicio del row de configuracion de Etiqueta -->
                            <div class="col-sm-12">
                                <div class="panel panel-default">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div class="checkbox">
                                                          <i class="fa fa-tag fa-2x" aria-hidden="true"></i>
                                                          <label><input type="checkbox" name="etiqueta" class="propiedades 2" id="checkLabel" checked>Etiqueta</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div class="checkbox">
                                                          <i class="fa fa-font fa-2x" aria-hidden="true"></i>
                                                          <label><input type="checkbox" name="nombre" class="propiedades 2" id="checkNombre" checked>Nombre</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div class="checkbox">
                                                          <i class="fa fa-list-ul fa-2x" aria-hidden="true"></i>
                                                          <label><input type="checkbox" name="caracteriricas" class="propiedades 2" id="checkCaracteriricas" checked>Caraterísticas</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div class="checkbox">
                                                          <i class="fa fa-usd fa-2x" aria-hidden="true"></i>
                                                          <label><input type="checkbox" name="precio" class="propiedades 2" id="checkPrecio" checked>Precio</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div class="checkbox">
                                                          <i class="fa fa-barcode fa-2x" aria-hidden="true"></i>
                                                          <label><input type="checkbox" name="codigo" class="propiedades 2" id="checkCodigo" checked>Código</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <label>Color de Etiqueta:</label>
                                                        <input id="colorLabel" type="color" name="favcolor" value="#FFFE70" class="form-control">
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <label>Color de Letra:</label>
                                                        <input id="colorLetter" type="color" name="favcolor" value="#333333" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-3">
                                                        <label>Cantidad</label>
                                                        <input id="cantidad" class="form-control" type="text" value="1">
                                                    </div>
                                                </div>
                                    <!--            <div class="row">
                                                    <div class="col-sm-12">
                                                        <label>Tipo de Codigo</label>
                                                        <select id="" class="form-control">
                                                            <option value=""></option>
                                                            <option value=""></option>
                                                        </select>
                                                    </div>
                                                </div> -->
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div id="colorEtique" style="background-color:#FFFE70;">
                                                            <div class="row" id="nombreProd">
                                                                <div class="col-sm-12" >
                                                                    <h4 class="letra" align="center" style="color:#333333;">Descripción del Producto</h4>
                                                                </div>
                                                            </div>
                                                            <div class="row" id="caractProd">
                                                                <div class="col-sm-12" >
                                                                    <h6 class="letra" align="center" style="color:#333333;">Característica:Propiedad,Característica:Propiedad</h6>
                                                                </div>
                                                            </div>
                                                            <div class="row" id="precioProd">
                                                                <div class="col-sm-12" >
                                                                   <h3 class="letra" align="center" style="color:#333333;">$100.00</h3> 
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row" id="codProd">
                                                            <div class="col-sm-12">
                                                                <div align="center"><svg id="barcode" aling="center"></svg></div>
                                                            </div>
                                                        </div>
                                                    </div>                                                   
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- fin del row de configuracion de Etiqueta -->
                        
                        <div class="row">
                            <div class="col-sm-11">
                                <div class="row">
                                    <div class="col-sm-3">
                                        <select class="erre form-control " id="selectSucursal" >

                                        </select>
                                    </div>
                                    <div class="col-sm-3">
                                        <select class="erre form-control " id="selectDepartamento" >

                                        </select>
                                    </div>
                                    <div class="col-sm-3">
                                        <select class="erre form-control" id="selectFamilia" >

                                        </select>
                                    </div>
                                    <div class="col-sm-3">
                                        <select class="erre form-control" id="selectLinea" >

                                        </select>
                                    </div>
                                
                                </div>
                            </div>  
                            <div class="col-sm-1">
                                <div class="row">
                                    <div class="col-sm-2">
                                    <i class="btn fa fa-search fa-2x" aria-hidden="true" onclick="resetFilters();" style="background-color: rgba(0, 0, 0, 0.1); border-radius: 100%;"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <script LANGUAGE="JavaScript">
// pintarProductos() {
//     $('#rango').val(0);
//     $('#containerTouch').empty();
//     caja.cargarMas();

// }

function resetFilters(){
    var pathname = window.location.pathname;
    window.location = window.location.protocol + '//'+document.location.host+pathname+'?c=inventario&f=indexEtiquetado'+ '&filtrado=1'+
    '&sucursal='+ ($("#selectSucursal").val() ? $("#selectSucursal").val() : "") + 
    '&departamento=' + ( $("#selectDepartamento").val() ? $("#selectDepartamento").val() : "") + 
    '&familia=' + ( $("#selectFamilia").val() ? $("#selectFamilia").val() : "" ) + 
    '&linea=' + ( $("#selectLinea").val() ? $("#selectLinea").val() : "" ) ;

}

    $("#selectSucursal").select2({
        placeholder: "Sucursal",
        delay: 250,
        width:'100%',
        ajax: {
            url: 'ajax.php?c=inventario&f=buscarSucursales',
            type: 'GET',
            dataType: 'json',

            data: function(params) {
                return { 
                    patron: params.term 
                };
            },

            processResults: function (data) {
                return { results: data.rows };
            },
            cache: true
        },
        templateResult: function format(state) {
            return  state.text;
        },
        templateSelection: function format(state) {
            return  state.text;
        }
    });

    $("#selectDepartamento").select2({
        placeholder: "Departamento",
        delay: 250,
        width:'100%',
        ajax: {
            url: 'ajax.php?c=caja&f=buscarClasificadores',
            type: 'GET',
            dataType: 'json',

            data: function(params) {
                return { clasificador : 1,
                    patron: params.term };
            },

            processResults: function (data) {
                $("#selectDepartamento").empty();
                return { results: data.rows };
            },
            cache: true
        },
        templateResult: function format(state) {
            return  state.text;
        },
        templateSelection: function format(state) {
            return  state.text;
        }
    })
    .on("change", function(e) {
        $("#selectFamilia").empty().trigger('change');
    });
    $("#selectFamilia").select2({
        placeholder: "Familia",
        delay: 250,
        width:'100%',
        ajax: {
            url: 'ajax.php?c=caja&f=buscarClasificadores',
            type: 'GET',
            dataType: 'json',

            data: function(params) {
                return { clasificador : 2,
                    departamento : $('#selectDepartamento').val(),
                    patron: params.term };
            },

            processResults: function (data) {
                $("#selectFamilia").empty();
                return { results: data.rows };
            },
            cache: true
        },
        templateResult: function format(state) {
            return  state.text;
        },
        templateSelection: function format(state) {
            return  state.text;
        }
    })
    .on("change", function(e) {
        $("#selectLinea").empty().trigger('change');
    });
    $("#selectLinea").select2({
        placeholder: "Linea",
        delay: 250,
        width:'100%',
        ajax: {
            url: 'ajax.php?c=caja&f=buscarClasificadores',
            type: 'GET',
            dataType: 'json',

            data: function(params) {
                return { clasificador : 3,
                    familia : $('#selectFamilia').val(),
                    patron: params.term };
            },

            processResults: function (data) {
                $("#selectLinea").empty();
                return { results: data.rows };
            },
            cache: true
        },
        templateResult: function format(state) {
            return  state.text;
        },
        templateSelection: function format(state) {
            return  state.text;
        }
    });
    // .on("change", function(e) {
    //     caja.pintarProductos();
    // });
                        </script>

                        <div class="row"><!-- Inicio del Div del Boton -->
                            <div class="col-sm-12">
                                <div align="left" ">
                                    <button type="button" class="btn btn-primary" onclick="etiquetar();"> Etiquetar </button>
                                </div>
                            </div>
                        </div> <!-- fin del div de boton -->
                        <div class="row"><!-- inicio del div de la tabla -->
                            <div class="col-sm-12" style="overflow:auto;">
                           
                                <table id="tableGrid" class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th><div><input type="checkbox" id="todos" onclick="allsi();"></div></th>
                                            <th>Cantidad</th>
                                            <th>Código</th>
                                            <th>Nombre</th>
                                            <th>Precio</th>
                                            <th>Descripción corta</th>
                                            <th>Características</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php 

                                        /*foreach ($productosGrid['productos'] as $key => $value) {
                                                echo '<tr>';
                                                echo '<td align="center"><input class="checkPro" type="checkbox" name="prods" value="'.$value['id'].'" id="check_'.$value['id'].'"></td>';
                                                echo '<td>'.$value['id'].'</td>';
                                                echo '<td><input id="cod_'.$value['id'].'" value="'.$value['codigo'].'" type="hidden">'.$value['codigo'].'</td>';
                                                echo '<td><input id="nom_'.$value['id'].'" value="'.$value['nombre'].'" type="hidden">'.$value['nombre'].'</td>';
                                                echo '<td><input id="price_'.$value['id'].'" value="'.number_format($value['precio'],2).'" type="hidden">$'.number_format($value['precio'],2).'</td>';
                                                echo '<td><input id="des_'.$value['id'].'" value="'.$value['descripcion_corta'].'" type="hidden">'.$value['descripcion_corta'].'</td>';
                                                echo '</tr>';
                                        }*/

                                        $sucursarTmp = 0;
                                        $productoTmp = 0;
                                        foreach ($productosGrid['productos'] as $key => $value) {
                                            if($value['ID_SUCURSAL'] != $sucursarTmp){
                                                $sucursarTmp = $value['ID_SUCURSAL'];
                                                echo '<tr>';
                                                    echo '<td class="text-right" style="text-align:center;" > <i class="btn btn-xs fa fa-bars" data-toggle = "collapse" data-target = ".collapsed'.$value['ID_SUCURSAL'].'" ></i></td>';
                                                    echo '<td></td>';
                                                    echo '<td></td>';
                                                    echo '<td align="center">'.$value['SUCURSAL'].'</td>';
                                                    
                                                    echo '<td></td>';
                                                    echo '<td></td>';
                                                    echo '<td></td>';
                                                echo '</tr>';
                                            }
                                            $caracteristicasProductoTmp = explode( ',' , $value['CARACTERISTICAS'] );

                                            $caracteristicasEtiqueta = '';
                                            foreach ($caracteristicasProductoTmp as $key => $val) {
                                                $cararcterisiticaHija = explode( '=>' , $val);
                                                  $keyTmp = array_search( trim($cararcterisiticaHija[1], "\'"),   array_column($productosGrid['caracteristicas'], 'ID_H'));

                                                  $caracteristicasEtiqueta .=  $productosGrid['caracteristicas'][$keyTmp]['CRARACTERISTICA_PADRE'] .'-';
                                                  $caracteristicasEtiqueta .=  $productosGrid['caracteristicas'][$keyTmp]['CARACTERISTICA_HIJA'] ;
                                                  $caracteristicasEtiqueta .= '_';
                                            }

                                            $caracteristicasEtiqueta = substr($caracteristicasEtiqueta, 0, -1);
                                                $caracteristicasEtiquetaTmp = str_replace("-", ":", $caracteristicasEtiqueta) ;
                                                $caracteristicasEtiquetaTmp = str_replace( "_", "," , $caracteristicasEtiquetaTmp) ;
                                                //$caracteristicasEtiquetaTmp = str_replace(" ", "", $caracteristicasEtiquetaTmp) ;

                                            //$caracteristicasEtiqueta = str_replace(" ", "¿", $caracteristicasEtiqueta) ;
                                            $caracteristicasEtiqueta = preg_replace('([^A-Za-z0-9])', '', $caracteristicasEtiqueta);
                                            //if($value['ID'] != $productoTmp){
                                                //$productoTmp = $value['ID'];
                                                
//var_dump($caracteristicasProductoTmp); die;
                                                if( $caracteristicasProductoTmp[0] != '\'0\'' && $productoTmp != $value['ID'] ) {
                                                    $productoTmp = $value['ID'];
                                                    echo '<tr class="collapse  collapsed'.$value['ID_SUCURSAL'].'" style="background-color: #EEE;">';
                                                        echo '<td class="text-right" style="text-align:center;" > <i class="btn btn-xs fa fa-arrow-down" data-toggle = "collapse" data-target = ".collapsed'.$value['ID_SUCURSAL'].'_'.$value['ID'].'" ></i></td>';
                                                        echo '<td></td>';
                                                        echo '<td>'.$value['CODIGO'].'</td>';
                                                        echo '<td align="center">'.$value['NOMBRE'].'</td>';
                                                        
                                                        echo '<td></td>';
                                                        echo '<td></td>';
                                                        echo '<td></td>';
                                                    echo '</tr>';
                                                }







                                                if($caracteristicasProductoTmp[0] != '\'0\'' ) {
                                                    echo '<tr class="collapse  collapsed'.$value['ID_SUCURSAL'].'_'. $value['ID'].'" style="background-color: #FFF;">';
                                                        //echo '<td align="center"><input class="checkPro" type="checkbox" name="prods" value="'.$value['ID'].'" id="check_'.$value['ID'].'"></td>';
                                                        echo '<td align="center"><input class="checkPro" type="checkbox" name="prods" value="'.$value['ID_SUCURSAL'].'_'.$value['ID']."_".$caracteristicasEtiqueta.'" id="check_'.$value['ID_SUCURSAL'].'_'.$value['ID']."_".$caracteristicasEtiqueta.'"></td>';
                                                        echo '<td><input id="cant_'.$value['ID_SUCURSAL'].'_'.$value['ID']."_".$caracteristicasEtiqueta.'"" type="number"/></td>';
                                                        //echo '<td><input id="cod_'.$value['ID'].'" value="'.$value['CODIGO'].'" type="hidden">'.$value['CODIGO'].'</td>';

                                                        $search    = array('\'', '=>', ',');
                                                        $replace   = array('', 'H' , 'P');
                                                        $codigoCaracteristicas = str_replace( $search , $replace, $value['CARACTERISTICAS']);

                                                        echo '<td><input id="cod_'.$value['ID_SUCURSAL'].'_'.$value['ID']."_".$caracteristicasEtiqueta.'" value="'.$value['CODIGO'].' '.$codigoCaracteristicas.'" type="hidden">'.$value['CODIGO'].' '.$codigoCaracteristicas.'</td>';

                                                        //echo '<td><input id="nom_'.$value['ID'].'" value="'.$value['NOMBRE'].'" type="hidden">'.$value['NOMBRE'].'</td>';
                                                        echo '<td><input id="nom_'.$value['ID_SUCURSAL'].'_'.$value['ID']."_".$caracteristicasEtiqueta.'" value="'.$value['NOMBRE'].'" type="hidden">'.$value['NOMBRE'].'</td>';

                                                        //echo '<td><input id="price_'.$value['ID'].'" value="'.number_format($value['PRECIO_GENERAL'],2).'" type="hidden">$'.number_format($value['PRECIO_GENERAL'],2).'</td>';
                                                        echo '<td><input id="price_'.$value['ID_SUCURSAL'].'_'.$value['ID']."_".$caracteristicasEtiqueta.'" value="'.number_format( ( $value['precio'] ) ,2).'" type="hidden">$'.number_format( ( $value['precio']  ) ,2).'</td>';

                                                        echo '<td><input id="des_'.$value['ID_SUCURSAL'].'_'.$value['ID']."_".$caracteristicasEtiqueta.'" value="'.$value['DESC_CORTA'].'" type="hidden">'.$value['DESC_CORTA'].'</td>';

                                                        
                                                        echo '<td><input id="carac_'.$value['ID_SUCURSAL'].'_'.$value['ID']."_".$caracteristicasEtiqueta.'" value="'.$caracteristicasEtiquetaTmp.'" type="hidden">'.$caracteristicasEtiquetaTmp.'</td>';
                                                    echo '</tr>';
                                                }
                                                else {
                                                    echo '<tr class="collapse  collapsed'.$value['ID_SUCURSAL'].'" style="background-color: #EEE;">';
                                                        



                                                        echo '<td align="center"><input class="checkPro" type="checkbox" name="prods" value="'.$value['ID_SUCURSAL'].'_'.$value['ID'].'" id="check_'.$value['ID_SUCURSAL'].'_'.$value['ID'].'"></td>';
                                                        echo '<td><input id="cant_'.$value['ID_SUCURSAL'].'_'.$value['ID'].'"" type="number"/></td>';


                                                        echo '<td><input id="cod_'.$value['ID_SUCURSAL'].'_'.$value['ID'].'" value="'.$value['CODIGO'].'" type="hidden">'.$value['CODIGO'].'</td>';

                                                        echo '<td><input id="nom_'.$value['ID_SUCURSAL'].'_'.$value['ID'].'" value="'.$value['NOMBRE'].'" type="hidden">'.$value['NOMBRE'].'</td>';

                                                        echo '<td><input id="price_'.$value['ID_SUCURSAL'].'_'.$value['ID'].'" value="'.number_format( ( $value['precio'] ) ,2).'" type="hidden">$'.number_format( ( $value['precio']  ) ,2).'</td>';

                                                        echo '<td><input id="des_'.$value['ID_SUCURSAL'].'_'.$value['ID'].'" value="'.$value['DESC_CORTA'].'" type="hidden">'.$value['DESC_CORTA'].'</td>';

                                                        
                                                        echo '<td><input id="carac_'.$value['ID_SUCURSAL'].'_'.$value['ID'].'" value="" type="hidden"></td>';
                                                    echo '</tr>';
                                                    continue;
                                                }
                                            //}
                                            
                                                
                                        }


                                        /*foreach ($productosGrid['productos'] as $key => $value) {
                                                $row = "<tr>
                                                        <td align='center'><input class='checkPro' type='checkbox' name='prods' value='{ value['id'] }' id='check_{ value['id'] }'></td>
                                                        <td>{ value['id'] }</td>
                                                        <td><input id='cod_{ value['id'] }' value='{ value['codigo'] }' type='hidden'>{ value['codigo'] }</td>
                                                        <td><input id='nom_{ value['id'] }' value='{ value['nombre'] }' type='hidden'>{ value['nombre'] }</td>
                                                        <td><input id='price_{ value['id'] }' value='{ number_format( value['precio'],2) }' type='hidden'>$ { number_format( value['precio'],2) }</td>
                                                        <td><input id='des_{ value['id'] }' value='{ value['descripcion_corta'] }' type='hidden'>{ value['descripcion_corta'] }</td>
                                                    </tr>";
                                                    echo $row;
                                        }*/

                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div><!-- fin del div de la tabla -->
                        
                    </div>
                </div>
            </div>
        </div>
    <div id='modalCodigos' class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" style="width:98%;">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="row">
                        <div class="col-sm-6"><h4 class="modal-title">Etiquetas</h4></div>
                        <div class="col-sm-6">
                        <div align="right">
                        <button class="btn btn-primary btnMenu" onclick="imprimeLabels()" > <i class="fa fa-print" aria-hidden="true"></i> Imprimir</button>
                        </div>
                        </div>
                    </div>
                </div>
                <div class="modal-body">
                    <div id="divLabels" class="col-sm-12" style="overflow:auto;"></div>
                    <div id="divLabels2" class="col-sm-12" style="overflow:auto;display:none;"></div>
                </div>
                <div class="modal-footer">
                    <div class="row">
                        <div class="col-md-6 col-md-offset-6">
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>






    <div class="modal fade" id="modalMensajes" role="dialog" style="z-index:1051;" data-backdrop="static">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Espere un momento...</h4>
        </div>
        <div class="modal-body">
          <div class="alert alert-default">
            <div align="center"><label id="lblMensajeEstado"></label></div>
            <div align="center"><i class="fa fa-refresh fa-spin fa-5x fa-fw margin-bottom"></i>
                 <span class="sr-only">Loading...</span>
             </div>
        </div>
        </div>
      </div>
    </div>
  </div>

    <!--- 
                                            <div class="col-sm-12">
                                                <div style="background-color:#FFFE70;">
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <h4 align="center">Agua ciel De un Litro</h4>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                           <h3 align="center">$223.19</h3> 
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div align="center"><svg id="barcode" aling="center"></svg></div>
                                                    </div>
                                                </div>
                                            </div>

                                            -->

</div>                                            
</body>
</html>