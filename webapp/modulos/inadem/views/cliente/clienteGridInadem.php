<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Clientes Inadem</title>
    <link rel="stylesheet" href="">
</head>
    <link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../libraries/font-awesome/css/font-awesome.min.css">
    <script src="../../libraries/jquery.min.js"></script>
    <script src="../../libraries/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="js/inadem.js"></script>
<!--Select 2 -->
    <script src="../../libraries/select2/dist/js/select2.min.js"></script>
    <link rel="stylesheet" type="text/css" href="../../libraries/select2/dist/css/select2.min.css" />

    <link rel="stylesheet" href="../../libraries/dataTable/css/datatablesboot.min.css">
    <!-- Modificaciones RC -->
    <script src="../../libraries/dataTable/js/datatables.min.js"></script>
    <script src="../../libraries/export_print/jquery.dataTables.min.js"></script>
    <script src="../../libraries/export_print/dataTables.buttons.min.js"></script>
    <script src="../../libraries/export_print/buttons.html5.min.js"></script>
    <script src="../../libraries/export_print/jszip.min.js"></script>
    <script src="../../libraries/dataTable/js/dataTables.bootstrap.min.js"></script>
    <link rel="stylesheet" href="../../libraries/dataTable/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="../../libraries/dataTable/css/buttons.dataTables.min.css">

   <script>
   $(document).ready(function() {
            $('#tableGrid').DataTable({
                            dom: 'Bfrtip',
                            buttons: ['excel'],
                            language: {
                            search: "Buscar:",
                            lengthMenu:"",
                            zeroRecords: "No hay datos.",
                            infoEmpty: "No hay datos que mostrar.",
                            info:"Mostrando del _START_ al _END_ de _TOTAL_ elementos",
                            paginate: {
                                first:      "Primero",
                                previous:   "Anterior",
                                next:       "Siguiente",
                                last:       "Último"
                            },
                         },
                          aaSorting : [[0,'desc' ]]
            });
            $("#layout_row").attr("abierto","0").hide();
            $("#layout_precios").attr("abierto","0").hide();

   $('#filtros').change(function() {
        var x = $(this).val();
        gridFacturado(x);

        /*if ($(this).is(':checked')) {
            gridFacturado(1);
        }else{
            gridFacturado(2);
        } */
        
    });



   });
   function mostrar_layout()
   {
    if(!parseInt($("#layout_row").attr("abierto")))
        $("#layout_row").attr("abierto","1").show("slow");
    else
        $("#layout_row").attr("abierto","0").hide("slow");
   }
    function mostrar_layout2()
   {
    if(!parseInt($("#layout_precios").attr("abierto")))
        $("#layout_precios").attr("abierto","1").show("slow");
    else
        $("#layout_precios").attr("abierto","0").hide("slow");
   }


   function validar(t)
   {
    if(t.layout.value == '')
    {
        alert("Agregue un archivo xls.");
        return false;
    }
   }
   </script>
<body>  
    <br>
<div class="container well col-sm-12">
   
    <h3>Clientes Inadem</h3>
    <div class="row">
        <div class='col-sm-12 col-md-2'>
        <!--    <button class="btn btn-primary" onclick="newProduct();"><i class="fa fa-plus" aria-hidden="true"></i> Nuevo Producto</button> -->
        </div>
        <div class='col-sm-12 col-md-2'>   
           <button class="btn btn-default" title='Subir productos mediante layout' onclick='mostrar_layout()'><span class='glyphicon glyphicon-upload'></span></button>
        </div>
      <!--   <div class='col-sm-12 col-md-2'>   
           <button class="btn btn-default" title='Subir productos mediante layout' onclick='mostrar_layout2()'><span class='glyphicon glyphicon-tags'></span></button>
        </div> -->
        <div class='col-sm-12 col-md-2'>   
           <button class="btn btn-default" onclick='allfs();'>Facturar</button>
           <input type="hidden" value="100" id="rango"> 
        </div>
        <div class='col-sm-12 col-md-2'>   
            <div class="checkbox">
                <label><select id="filtros" class="form-control">
                        <option value="1">Pendientes</option>
                        <option value="2">Facturados</option>
                        <option value="3">Con Error</option>
                </select></label>
            </div>
        </div>

    </div>
    <div class='row' id='layout_row'>
        <div class='col-sm-12 col-md-offset-2 col-md-5'>
            <b>Subir productos mediante layout</b> / <a href='importacion/productos.xls'>Descargar</a><br />
            <form action='index.php?c=cliente&f=subeLayout' method='post' name='archivo' enctype="multipart/form-data" id='arch' onsubmit='return validar(this)'>
                <input type='file' id='layout' name='layout'><br />
                <button type='submit' onclick='cargar_productos()'>Cargar</button>
            </form>
        </div>
    </div>
    <!-- Actualzia Precios -->
    <div class='row' id='layout_precios'>
        <div class='col-sm-12 col-md-offset-2 col-md-5'>
            <b>Actualizar precios mediante layout</b><br />
            <form action='index.php?c=producto&f=subeLayoutPrecios' method='post' name='archivo' enctype="multipart/form-data" id='arch' onsubmit='return validar(this)'>
                <input type='file' id='layout' name='layout'><br />
                <button type='submit' onclick='cargar_productos()'>Cargar</button>
            </form>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
         <!--   <label id="totlaProdsLabel">Total: <?php echo $productosGrid['total']; ?></label>
            <input type="hidden" id="totlaProds" value="<?php echo $productosGrid['total']; ?>"> -->
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12" style="overflow:auto;">
                     <table class="table table-hover table-fixed" style="background-color:#F9F9F9; border:1px solid #c8c8c8;" id="tableGrid">
                    <thead>
                      <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Folio</th>
                        <th>Cupon</th>
                        <th>Vitrina</th>
                        <th>Responsable</th>
                        <th>Factura</th>
                        <th>Archivo</th>
                        <th><div id="allBut"><button id="btnaf90" class="btn btn-primary" onclick="selAll();">Selecciona 10</button></div></th>
                      </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $status = '';
                        $total = 0;
                        foreach ($clientes['grid'] as $key => $value) {
                            if($value['factura']==''){
                                echo '<tr>';
                                echo '<td>'.$value['id'].'</td>';
                                echo '<td>'.$value['nombre'].'</td>';
                                echo '<td>'.$value['folio_inadem'].'</td>';

                                echo '<td>'.$value['cupon'].'</td>';
                                echo '<td>'.$value['vitrina'].'</td>';
                                echo '<td>'.$value['resp_nwm'].'</td>';
                                echo '<td>'.$value['factura'].'</td>';
                                echo '<td>'.$value['archivo'].'</td>';
                                echo '<td><input type="checkbox" value="'.$value['id'].'" class="checkPro"></td>';
                                echo '</tr>';
                            }
                        }
                        ?>
                    </tbody>
                </table>
        </div>
    </div>
</div>
        <!--          Molda Warning           -->
  <div id="modalElimina" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content panel-warning">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <div class="modal-header panel-heading">
                <h4 id="modal-label">Eliminar!</h4>
            </div>
            <div class="modal-body">
                <p>Deseas desactivar este producto?</p>
                <input type="hidden" id="eliminaProd">
            </div>
            <div class="modal-footer">
                <button id="modal-btnconf2-uno" type="button" class="btn btn-danger" onclick="borraProducto2();">Eliminar</button> 
            </div>
        </div>
    </div> 
  </div>

  <div class="modal fade" id="modalLoading" role="dialog" style="z-index:1051;" data-backdrop="static">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Espere un momento...</h4>
        </div>
        <div class="modal-body">
            <div class="alert alert-default">
                <div class="row">
                    <div class="col-sm-12" id="contResp"></div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <div style="display: none;" id="btnCerrar78">
                <button type="button" class="btn btn-primary"    data-dismiss="modal" onclick="javascripr: window.location.reload();">Cerrar</button>
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



    <div id="modalActiva" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content panel-warning">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <div class="modal-header panel-heading">
                <h4 id="modal-label">Activar!</h4>
            </div>
            <div class="modal-body">
                <p>Deseas activar este producto?</p>
                <input type="hidden" id="activaProd">
            </div>
            <div class="modal-footer">
                <button id="modal-btnconf2-uno" type="button" class="btn btn-danger" onclick="activar2();">Activar</button> 
            </div>
        </div>
    </div> 
  </div>
</body>
</html>