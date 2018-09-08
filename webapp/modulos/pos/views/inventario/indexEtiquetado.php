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
   <script>
   $(document).ready(function() {
    JsBarcode("#barcode", "0123456789012340",{width: 2});


    $('#productos').select2();
    var table = $('#tableGrid').DataTable({
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

           
        @media print{
           div.saltopagina{
              display:block !important;
              page-break-after:always !important;
              background: red !important;
           }
          
        }
  #colorEtique {
                border: 2px solid;
                border-radius: 25px;
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
                    <div class="panel-heading">
                    <!--    <h3 class="panel-title">Panel title</h3> -->
                        <div class="row">
                            <div class="col-sm-4">
                                <label>Producto</label>
                                <select id="productos" class="form-control">
                                    <option value="0">-Selecciona Producto-</option>
                                    <?php 
                                        foreach ($productosGrid['productos'] as $key1 => $value1) {
                                            echo '<option value="'.$value1['id'].'">'.$value1['nombre'].'/'.$value1['codigo'].'</option>';
                                        }
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
                    </div>
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
                                                    <div class="col-sm-12">
                                                        <div class="checkbox">
                                                          <i class="fa fa-list-ul fa-2x" aria-hidden="true"></i>
                                                          <label><input type="checkbox" name="caracteriricas" class="propiedades 2" id="checkCaracteriricas" checked>Caraterísticas</label>
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
                                            <th>ID</th>
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

                                        foreach ($productosGrid['productos'] as $key => $value) {

                                                //$productosGrid['caracteristicasProductos']
                                                if ( in_array( array( 'id' => $value['id'] ), $productosGrid['caracteristicas']) ) {

                                                    echo '<tr>';
                                                        echo '<td class="text-right" style="text-align:center;" > <i class="btn btn-xs fa fa-arrow-down" data-toggle = "collapse" data-target = ".collapsed'.$value['id'].'" ></i></td>';
                                                        echo '<td>'.$value['id'].'</td>';
                                                        echo '<td><input id="cod_'.$value['id'].'" value="'.$value['codigo'].'" type="hidden">'.$value['codigo'].'</td>';
                                                        echo '<td><input id="nom_'.$value['id'].'" value="'.$value['nombre'].'" type="hidden">'.$value['nombre'].'</td>';
                                                        echo '<td><input id="price_'.$value['id'].'" value="'.number_format($value['precio'],2).'" type="hidden">$'.number_format($value['precio'],2).'</td>';
                                                        echo '<td><input id="des_'.$value['id'].'" value="'.$value['descripcion_corta'].'" type="hidden">'.$value['descripcion_corta'].'</td>';
                                                        echo '<td class="text-right"> </td>';
                                                    echo '</tr>';


                                                        $productosGrid2 = $this->InventarioModel->obtenCaracteristicas($value['id']);

                                                        $caracteristicas = [""];
                                                        $caracteristicas2 = [""];
                                                        foreach ( $productosGrid2['cararc'] as $key => $valu) {
                                                            $caracteristicarTmp = [];
                                                            $caracteristicarTmp2 = [];
                                                            foreach ($valu as $ke => $va) {
                                                                foreach ($caracteristicas as $k => $v) {
                                                                    $strTmp = $v . $key ."-". $va['nombre'] . "_";
                                                                    array_push($caracteristicarTmp, $strTmp );
                                                                }
                                                                foreach ($caracteristicas2 as $k => $v) {
                                                                    $strTmp2 = $v . $va['id_caracteristica_padre']."H".$va['id']. "P";
                                                                    array_push($caracteristicarTmp2, $strTmp2 );
                                                                }
                                                            }
                                                            $caracteristicas = $caracteristicarTmp;
                                                            $caracteristicas2 = $caracteristicarTmp2;
                                                            //$caracteristicarTmp = [];
                                                        }
                                                        //echo json_encode($caracteristicas2);
                                                        foreach ($caracteristicas as $k => $val) {
                                                            $val = str_replace(" ", "¿", $val) ;
                                                            echo '<tr class="collapse  collapsed'.$value['id'].'" style="background-color: #EEE;">';
                                                                echo '<td align="center"><input class="checkPro" type="checkbox" name="prods" value="'.$value['id']."_".$val.'" id="check_'.$value['id'].'"></td>';
                                                                echo '<td>'.$value['id'].'</td>';
                                                                echo '<td><input id="cod_'.$value['id']."_".$val.'" value="'.$value['codigo'].' '.$caracteristicas2[$k].'" type="hidden">'.$value['codigo'].'</td>';
                                                                echo '<td><input id="nom_'.$value['id']."_".$val.'" value="'.$value['nombre'].'" type="hidden">'.$value['nombre'].'</td>';
                                                                echo '<td><input id="price_'.$value['id']."_".$val.'" value="'.number_format($value['precio'],2).'" type="hidden">$'.number_format($value['precio'],2).'</td>';
                                                                echo '<td><input id="des_'.$value['id']."_".$val.'" value="'.$value['descripcion_corta'].'" type="hidden">'.$value['descripcion_corta'].'</td>';
                                                                $valTmp = substr($val, 0, -1);
                                                                $valTmp = str_replace("-", ":", $valTmp) ;
                                                                $valTmp = str_replace( "_", "," , $valTmp) ;
                                                                $valTmp = str_replace( "¿", " " , $valTmp) ;
                                                                echo '<td> <input id="carac_'.$value['id']."_".$val.'" value="'.$valTmp.'" type="hidden">' . $valTmp . '</td>';
                                                            echo '</tr>';
                                                        }
                                                }
                                                else {
                                                    echo '<tr>';
                                                        echo '<td align="center"><input class="checkPro" type="checkbox" name="prods" value="'.$value['id'].'" id="check_'.$value['id'].'"></td>';
                                                        echo '<td>'.$value['id'].'</td>';
                                                        echo '<td><input id="cod_'.$value['id'].'" value="'.$value['codigo'].'" type="hidden">'.$value['codigo'].'</td>';
                                                        echo '<td><input id="nom_'.$value['id'].'" value="'.$value['nombre'].'" type="hidden">'.$value['nombre'].'</td>';
                                                        echo '<td><input id="price_'.$value['id'].'" value="'.number_format($value['precio'],2).'" type="hidden">$'.number_format($value['precio'],2).'</td>';
                                                        echo '<td><input id="des_'.$value['id'].'" value="'.$value['descripcion_corta'].'" type="hidden">'.$value['descripcion_corta'].'</td>';
                                                        echo '<td></td>';
                                                    echo '</tr>';
                                                }


                                                
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