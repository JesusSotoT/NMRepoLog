<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Tarjetas de Regalo / Monedero Electrónico</title>
    <link rel="stylesheet" href="">
</head>
    <link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../libraries/font-awesome/css/font-awesome.min.css">
    <script src="../../libraries/jquery.min.js"></script>
    <script src="../../libraries/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="js/ventas.js"></script>
    <script src="../../libraries/numeric.js"></script>
    <script src="../../libraries/numeric/jquery.numeric.js"></script>
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

        $('.numeros').numeric();
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
   });
   function mostrar_layout()
   {
    if(!parseInt($("#layout_row").attr("abierto")))
        $("#layout_row").attr("abierto","1").show("slow");
    else
        $("#layout_row").attr("abierto","0").hide("slow");
   }
   /* function mostrar_layout2()
   {
    if(!parseInt($("#layout_precios").attr("abierto")))
        $("#layout_precios").attr("abierto","1").show("slow");
    else
        $("#layout_precios").attr("abierto","0").hide("slow");
   } */


   function validar(t)
   {
    if(t.layout.value == '')
    {
        alert("Agregue un archivo xls.");
        return false;
    }

   }
           function xyx(){
          
            var lil = $('#tipoCard').val();

            if(lil==1){
                $('.porcen').show();
                $('.equival').hide();
            }else{
                $('.porcen').hide();
                $('.equival').show();
            }
        }
   </script>
<body>  
    <br>
<div class="container well">
   
    <h3>Tarjetas de Regalo</h3>
    <div class="row">
        <div class='col-sm-2 col-md-2'>
            <button class="btn btn-primary" onclick="newCard();"><i class="fa fa-plus" aria-hidden="true"></i> Tarjeta Nueva</button>
        </div>
        <div class='col-sm-2 col-md-2'>
            <button class="btn btn-primary" onclick="newPolitic();"><i class="fa fa-plus" aria-hidden="true"></i> Politica Nueva</button>
        </div>
    <!--    <div class='col-sm-12 col-md-2'>   
           <button class="btn btn-default" title='Subir productos mediante layout' onclick='mostrar_layout()'><span class='glyphicon glyphicon-upload'></span></button>
        </div> -->
    <!--     <div class='col-sm-12 col-md-2'>   
           <button class="btn btn-default" title='Subir productos mediante layout' onclick='mostrar_layout2()'><span class='glyphicon glyphicon-tags'></span></button>
        </div> -->
    </div>
    <div class='row' id='layout_row'>
        <div class='col-sm-12 col-md-offset-2 col-md-5'>
            <b>Subir productos mediante layout</b> / <a href='importacion/productos.xls'>Descargar</a><br />
            <form action='index.php?c=producto&f=subeLayout' method='post' name='archivo' enctype="multipart/form-data" id='arch' onsubmit='return validar(this)'>
                <input type='file' id='layout' name='layout'><br />
                <button type='submit' onclick='cargar_productos()'>Cargar</button>
            </form>
        </div>
    </div>
    <!-- Actualzia Precios -->
 <!--   <div class='row' id='layout_precios'>
        <div class='col-sm-12 col-md-offset-2 col-md-5'>
            <b>Actualizar precios mediante layout</b><br />
            <form action='index.php?c=producto&f=subeLayoutPrecios' method='post' name='archivo' enctype="multipart/form-data" id='arch' onsubmit='return validar(this)'>
                <input type='file' id='layout' name='layout'><br />
                <button type='submit' onclick='cargar_productos()'>Cargar</button>
            </form>
        </div>
    </div> -->
    <div class="row">
        <div class="col-sm-12">
            <label>Total: <?php echo $tarjetas['total']; ?></label>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12" style="overflow:auto;">
                     <table class="table table-hover table-fixed" style="background-color:#F9F9F9; border:1px solid #c8c8c8;" id="tableGrid">
                    <thead>
                      <tr>
                        <th>ID</th>
                        <th>Numero</th>
                        <th>Valor</th>
                        <th>Estatus</th>
                        <th>Monto Usado</th>
                        <th>Disponible</th>
                        <th>Puntos</th>
                        <th>Cliente</th>
                        <th>Modificar</th>
                     <!--   <th>Estatus</th>
                        <td>Modificar</td> -->
                      </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $status = '';
                        $total = 0;
                        foreach ($tarjetas['tarjetas'] as $key => $value) {
                            $disponible = $value['valor'] - $value['montousado'];
                            if($value['usada']!=1){

                                $status = '<span class="label label-success">Activo</span>';
                                //$botones = '<a href="#" class="btn btn-primary btn-xs active" onclick="modificaGiftCard('.$value['id'].');"><span class="glyphicon glyphicon-edit"></span> Editar</a><a href="#" class="btn btn-danger btn-xs active" onclick="desactivaGift('.$value['id'].');"><span class="glyphicon glyphicon-remove"></span> Desactivar</a>';
                                $botones = '<a href="#" class="btn btn-danger btn-xs active" onclick="desactivaGift('.$value['id'].');"><span class="glyphicon glyphicon-remove"></span> Desactivar</a>';
                            }else{
                                $status = '<span class="label label-danger">Inactivo</span>';
                                if($disponible > 0){
                                    $botones = '<a href="#" class="btn btn-info btn-xs active" onclick="activaGift('.$value['id'].');"><span class="glyphicon glyphicon-check"></span> Activar</a>';
                                }else{
                                    $botones = '<a href="#" class="btn btn-warning btn-xs active" ></span> Sin saldo</a>';
                                }
                                
                                
                            }
                            echo '<tr>';
                            echo '<td>'.$value['id'].'</td>';
                            echo '<td>'.$value['numero'].'</td>';
                            echo '<td>$'.number_format($value['valor'],2).'</td>';
                            echo '<td>'.$status.'</td>';
                            echo '<td>$'.number_format($value['montousado'],2).'</td>';
                            
                            echo '<td>$'.$disponible.'</td>';

                            echo '<td>'.$value['puntos'].'</td>';
                            echo '<td>'.$value['cliente'].'</td>';
                            echo '<td>';
                           // echo '<a href="index.php?c=producto&f=index&idProducto='.$value['id'].'" class="btn btn-primary btn-xs active"><span class="glyphicon glyphicon-edit"></span> Editar</a> ';
                            //echo '<a href="#" class="btn btn-danger btn-xs active" onclick="borraProducto('.$value['id'].');"><span class="glyphicon glyphicon-remove"></span> Borrar</a>';
                            echo $botones;
                            echo '</td>';

                            echo '</tr>';
                            
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

<div id="modal-giftCard" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content panel-default">
            <div class="modal-header panel-heading">
            <button class="close" type="button" data-dismiss="modal">×</button>
                <h4 id="modal-label">Nueva Tarjeta de Regalo</h4>
                <input type="hidden" id="idGiftCard">
            </div>
            <div class="modal-body">
            <div class="row">
                <div class="col-sm-6">
                    <label for="">Numero de Tarjeta</label>
                    <input type="text" class="form-control" id="numeroTarjeta" >
                </div>
                <div class="col-sm-6">
                    <label for="">Monto</label>
                    <input type="text" class="form-control numeros" id="montoTarjeta">
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <label for="">Puntos</label><input type="text" class="form-control numeros" id="puntos">
                </div>
                <div class="col-sm-1">
                    <label for=""> </label>
                    <button class="btn btn-default" onclick="clienteAddButton();"><i class="fa fa-user-plus" aria-hidden="true"></i></button>
                </div>
                <div class="col-sm-5">
                    <label for="">Cliente</label>
                    <select  class="form-control" id="cliente">
                        <?php foreach ($clientes as $key => $value) { ?>
                            <option value="<?php echo $value['id'] ?>"><?php echo $value['nombre'] ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>


            <div class="row" style="display:none;" id="modDiv">
                <div class="col-sm-6">
                    <label for="">Monto Usado</label>
                    <input type="text" class="form-control numeros" id="usado" >
                </div>
                <div class="col-sm-6">
                    <label for="">Monto Disponible</label>
                    <input type="text" class="form-control numeros" id="disponible" >
                </div>
            </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" onclick="saveGiftCard();">Guardar</button>
            </div>
        </div>
    </div> 
</div>


<div id="giftCardPolitics" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content panel-default">
            <div class="modal-header panel-heading">
            <button class="close" type="button" data-dismiss="modal">×</button>
                <h4 id="modal-label">Nueva Politica</h4>
                <input type="hidden" id="idGiftCard">
            </div>
            <div class="modal-body">
            <div class="row">
                <div class="col-xs-12">
                    <label>Nombre</label>
                    <input type="text" id="namePolitic" class="form-control">
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-xs-4">
                    <label>Tipo</label>
                    <select id="tipoCard" class="form-control" onchange="xyx();">
                        <option value="1">Procentaje</option>
                        <option value="2">Equivalencia</option>
                    </select>
                </div>
                <div class="col-xs-6 porcen">
                    <label>Porcentaje</label>
                    <input type="text" id="percent" class="form-control">
                </div> 
                <div class="col-xs-4 equival" style="display: none;">
                    <label>Dinero $</label>
                    <input type="text" id="money" class="form-control">
                </div>
                <div class="col-xs-4 equival" style="display: none;">
                    <label>Puntos</label>
                    <input type="text" id="points" class="form-control">
                </div>
            </div>
            <div class="row">
                
            </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" onclick="savePolitic();">Guardar</button>
            </div>
        </div>
    </div> 
</div>
    <div id='modalCliente' class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header modal-header-info">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><i class="fa fa-user-plus fa-lg" aria-hidden="true"></i> Agregar Cliente </h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                      <div class="col-sm-2">
                        <label class="control-label" for="email">ID</label>
                        <input id="idCliente" class="form-control" type="text" value="<?php 
                                  if(isset($datosCliente)){echo $datosCliente['basicos'][0]['id'];}?>" readonly placeholder="(Autonumerico)">
                      </div>
                      <div class="col-sm-3">
                          <label class="control-label">Codigo</label>
                          <input id="codigo" class="form-control" type="text" value="<?php 
                                  if(isset($datosCliente)){echo $datosCliente['basicos'][0]['codigo'];}?>">
                      </div>
                      <div class="col-sm-7">
                          <div class="alert alert-warning"><strong>NOTA:</strong>Si el cliente se registra con RFC y Razon Social, automaticamente se ingresaran a sus datos de facturacion.</div>
                      </div>

                    </div>

                    <div class="row">
                      <div class="col-sm-6">
                        <label class="control-label">Nombre</label>
                        <input id="nombre" class="form-control" type="text" value="<?php 
                                  if(isset($datosCliente)){echo $datosCliente['basicos'][0]['nombre'];}?>">
                      </div>
                      <div class="col-sm-6">
                          <label class="control-label">Razon Social</label>
                          <input id="razonSocial" class="form-control" type="text" value="<?php 
                                  if(isset($datosCliente)){echo $datosCliente['basicos'][0]['nombre'];}?>">
                      </div>

                    </div>
                    <div class="row">
                      <div class="col-sm-3">
                        <label class="control-label">RFC</label>
                        <input id="rfc2" class="form-control" type="text" value="<?php 
                                  if(isset($datosCliente)){echo $datosCliente['basicos'][0]['rfc'];}?>"> 
                      </div>
                      <div class="col-sm-3">
                        <label class="control-label">CURP</label>
                        <input id="curp" class="form-control" type="text" value="<?php 
                                  if(isset($datosCliente)){echo $datosCliente['basicos'][0]['curp'];}?>"> 
                      </div>
                      <div class="col-sm-3">
                          <label class="control-label">Régimen Fiscal</label>
                          <input id="regimen" type="text" class="form-control">
                      </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                          <label class="control-label">Tienda</label>
                          <input id="tienda" class="form-control" type="text" value="<?php 
                                  if(isset($datosCliente)){echo $datosCliente['basicos'][0]['nombretienda'];}?>">
                        </div>
                    </div>

                    <div class="row">
                      <div class="col-sm-6">
                        <label class="control-label">Direccion</label>
                        <input id="direccion" class="form-control" type="text" value="<?php 
                                  if(isset($datosCliente)){echo $datosCliente['basicos'][0]['direccion'];}?>">
                      </div>
                      <div class="col-sm-3">
                        <label class="control-label">Exterior</label>
                        <input id="numext" class="form-control" type="text" value="<?php 
                                  if(isset($datosCliente)){echo $datosCliente['basicos'][0]['num_ext'];}?>">
                      </div>
                      <div class="col-sm-3">
                        <label class="control-label">Interior</label>
                        <input id="numint" class="form-control" type="text" value="<?php 
                                  if(isset($datosCliente)){echo $datosCliente['basicos'][0]['num_int'];}?>">        
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-sm-3">
                        <label class="control-label">Colonia</label>
                        <input id="colonia" class="form-control" type="text" value="<?php 
                                  if(isset($datosCliente)){echo $datosCliente['basicos'][0]['colonia'];}?>"> 
                      </div>
                      <div class="col-sm-3">
                          <label class="control-label">Codio Postal</label>
                          <input id="cp" class="form-control numeros" type="text" value="<?php 
                                  if(isset($datosCliente)){echo $datosCliente['basicos'][0]['cp'];}?>"> 
                      </div>
                      <div class="col-sm-3">
                          <label class="control-label">Estado</label>
                          <select id="estado" class="form-control" onchange="municipiosF();">
                            <option value="0">-Selecciona un estado</option>
                            <?php 
                              foreach ($estados as $key => $value) {
                                echo '<option value="'.$value['idestado'].'">'.$value['estado'].'</option>';
                              }
                            ?>
                          </select>
                      </div>
                      <div class="col-sm-3">
                          <label class="control-label">Municipio</label>
                          <select  id="municipios" class="form-control">
                            <option value='0'>-Selecciona un municipio--</option>

                          </select>
                      </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <label class="control-label">Ciudad</label>
                            <input id="cdF" type="text" class="form-control">
                        </div>
                    </div>

                    <div class="row">
                      <div class="col-sm-3">
                        <label class="control-label">Email</label>
                        <input id="email" class="form-control" type="text" value="<?php 
                                  if(isset($datosCliente)){echo $datosCliente['basicos'][0]['email'];}?>"> 
                      </div>
                      <div class="col-sm-3">
                        <label class="control-label">Celular</label>
                        <input id="celular" class="form-control numeros" type="text" value="<?php 
                                  if(isset($datosCliente)){echo $datosCliente['basicos'][0]['celular'];}?>"> 
                      </div>
                      <div class="col-sm-3">
                        <label class="control-label">Telefono 1</label>
                        <input id="tel1" class="form-control numeros" type="text" value="<?php 
                                  if(isset($datosCliente)){echo $datosCliente['basicos'][0]['telefono1'];}?>"> 
                      </div>
                      <div class="col-sm-3">
                        <label class="control-label">Telefono 2</label>
                        <input id="tel2" class="form-control numeros" type="text" value="<?php 
                                  if(isset($datosCliente)){echo $datosCliente['basicos'][0]['telefono2'];}?>"> 
                      </div>      
                    </div>
                    


                    <div class="row">
                      <div class="col-sm-3">
                        <label class="control-label">Dias de Credito</label>
                        <input id="diasCredito" class="form-control numeros" type="text" value="<?php 
                                  if(isset($datosCliente)){echo $datosCliente['basicos'][0]['dias_credito'];}?>"> 
                      </div>
                      <div class="col-sm-3">
                        <label class="control-label">Limite de Credito</label>
                        <input id="limiteCredito" class="form-control numeros" type="text" value="<?php 
                                  if(isset($datosCliente)){echo $datosCliente['basicos'][0]['limite_credito'];}?>"> 
                      </div>
                      <div class="col-sm-3">
                        <label class="control-label">Moneda</label>
                        <!--<input id="moneda" class="form-control" type="text" value=""> -->
                        <select id="moneda" class="form-control">
                          <?php 
                    
                            foreach ($moneda as $keyMon => $valueMon) {
                              echo '<option value="'.$valueMon['coin_id'].'">'.$valueMon['description'].'/'.$valueMon['codigo'].'</option>';
                            }

                          ?>
                        </select>
                      </div>
                      <div class="col-sm-3"></div>
                    </div>

                    <div class="row">
                      <div class="col-sm-3">
                        <label class="control-label">Lista de Precio</label>
                        <select id="listaPrecio" class="form-control">
                          <?php 
                          foreach ($listaPre as $key1 => $value1) {
                            echo '<option value="'.$value1['id'].'">'.$value1['nombre'].'</option>';
                          }
                          ?>
                        </select>
                      </div>
                      <div class="col-sm-3"></div>
                      <div class="col-sm-3"></div>
                      <div class="col-sm-3"></div>
                    </div>

                    <div class="row"><br>
                      <div class="col-sm-10"></div>
                      <div class="col-sm-1"></div>
                    </div>    
                </div>
                <div class="modal-footer">
                    <div class="row">
                        <div class="col-md-6 col-md-offset-6">
                            <button type="button" class="btn btn-primary" onclick="guardaCliente();"><span class="glyphicon glyphicon-floppy-disk"></span> Guardar</button>
                            <!-- <button class="btn btn-success btnMenu" onclick="javascript:caja.observacionesEnviar();">Enviar</button> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

      <div id="modalSuccess" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content panel-success">
            <div class="modal-header panel-heading">
                <h4 id="modal-label">Exito!</h4>
            </div>
            <div class="modal-body">
                <p>Tu Cliente se guardo existosamente</p>
            </div>
            <div class="modal-footer">
                <button id="modal-btnconf2-uno" type="button" class="btn btn-default" onclick="cierramodales();">Continuar</button> 
            </div>
        </div>
    </div> 
  </div>

</body>
</html>