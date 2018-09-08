<!DOCTYPE html>
<html>
<script type="text/javascript" src="../js/jquery-1.10.2.min.js"></script>
<LINK href="../../../netwarelog/catalog/css/view.css" title="estilo" rel="stylesheet" type="text/css" />
<LINK href="../../../netwarelog/catalog/css/estilo.css" title="estilo" rel="stylesheet" type="text/css" />
<!--<LINK href="../../../netwarelog/design/default/netwarlog.css" title="estilo" rel="stylesheet" type="text/css" / -->
<?php include('../../../netwarelog/design/css.php');?>
<LINK href="../../../netwarelog/design/<?php echo $strGNetwarlogCSS;?>/netwarlog.css" title="estilo" rel="stylesheet" type="text/css" /> <!--NETWARLOG CSS-->
        <!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

<link href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" rel="stylesheet">
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
<script type="text/javascript" src="../../punto_venta/js/ui.datepicker-es-MX.js"></script>
<!-- Slect con buscador -->
<script src="select2/select2.min.js"></script>
<link rel="stylesheet" type="text/css" href="select2/select2.css" />

<style type="text/css">
    .btnMenu{
        border-radius: 0; 
        width: 100%;
        margin-bottom: 0.3em;
        margin-top: 0.3em;
    }
    .row
    {
        margin-top: 0.5em !important;
    }
    h4, h3{
        background-color: #eee;
        padding: 0.4em;
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
    @media print{
        .pagination, input[type='button'], input[type='submit'], img{
            display: none;
        }
        .table-responsive{
            overflow-x: unset;
        }
        #imp_cont{
            width: 100% !important;
        }
    }
</style>

<script type="text/javascript">
$(document).ready(function() {
        $('#busca').select2({
            width : "250px"
        });
        $('#ususel').select2({
            width : "250px"
        });
});
function almacenes(){
    window.location="almacenes.php";
}

$(function(){
    $.datepicker.setDefaults($.datepicker.regional['es-MX']);
    $("#fin").datepicker({dateFormat: "yy-mm-dd"});
    $("#inicio").datepicker({dateFormat: "yy-mm-dd"});


    
});

function buscalmacen(){
    var alma=jQuery('#busca').val();
    if(alma!="todos"){

        var inicia=jQuery('#inicio').val();
        var fin=jQuery('#fin').val();

   

        $.post("consultas.php",{opc:9,a:alma,inicio:inicia,fin:fin},
            function(respues) {
                $('#datos').html(respues); 
            }); 


    }else{
        window.location.reload();
    }
}
function buscafecha(){
    var inicia=jQuery('#inicio').val();
    var fin=jQuery('#fin').val();

    var alma=jQuery('#busca').val();
    var user=$('#ususel').val();
    $.post("consultas.php",{opc:15,inicio:inicia,fin:fin,a:alma,user:user},
        function(respues) {
            $('#datos').html(respues); 
        }); 
}


 function aprueba(id){
        $('#loader_'+id).show();
        $('#boton_'+id).hide();
        $.ajax({
            url: 'consultas.php',
            type: 'POST',
            data: {opc: '13',id:id},
        })
        .done(function(resp) {
            if(resp=='ok'){
                ///$('#loader_'+id).hide();
                alert('Se ha aprobado tu movimiento.');
                 window.location.reload();
            }
            console.log("success");
        })
        .fail(function() {
            console.log("error");
        })
        .always(function() {
            console.log("complete");
        });
        

 }

 function comprobante(id){

    window.open("../../../modulos/punto_venta/movimientos/comprobante.php?idmov=" +id+"&tipo=2");
    
}
</script>
<body>
    <?php 
    include("../../../netwarelog/webconfig.php");
    $conection = new mysqli($servidor,$usuariobd,$clavebd,$bd);
    $i=0;
    $pagina=1;
        // if($pagina==1){$pag_anterior=1;}else{$pag_anterior=$pagina-1;}
    // if(($pagina+1)>$paginas){$pag_siguiente=$pagina;}else{$pag_siguiente=$pagina+1;} 
    ?>

    <div class="container" style="width:100%;">
        <h3 class="nmwatitles text-center">Recepcion de Movimientos</h3>
        <section>
            <div class="row">
                <div class="col-md-12 col-sm-12 ">
                    <img  class="nmwaicons" type="button"  onclick="paginacionGridCxc(<?php //echo $pag_anterior;?>,1);" src="../../../netwarelog/design/default/pag_ant.png">
                    <img  class="nmwaicons" type="button"  onclick="paginacionGridCxc(<?php //echo $pag_siguiente;?>,1);" src="../../../netwarelog/design/default/pag_sig.png" >
                    <a href="javascript:window.print();"><img class="nmwaicons" src="../../../netwarelog/design/default/impresora.png" border="0"></a>
                </div>
            </div>
        </section>
        <section>
            <h4>B&uacute;squeda por Fecha</h4>
            <div class="row">
                <div class="col-md-3 col-sm-3 ">
                    <label>Almac&eacute;n:</label>
                    <select id="busca" style="font-size: 10px;" >
                        <?php
                        $busca=$conection->query("select idAlmacen,nombre from almacen");
                        if($busca->num_rows>0){ ?>
                        <option selected>--Elija un almac&eacute;n--</option>


                        <?php   while($almacen=$busca->fetch_array(MYSQLI_ASSOC)){ ?>
                        <option value="<?php echo $almacen['idAlmacen']; ?>"><?php echo $almacen['nombre']; ?></option>
                        <?php } ?>

                       <!-- <option value="todos">Todos</option> -->
                        <?php }else{?>  

                        <option selected>--No hay almacenes registrados--</option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-md-3 col-sm-3 ">
                    <label>Usuario:</label>
                    <select id="ususel" style="font-size: 10px;" >
                        <?php
                        $busca2=$conection->query("SELECT * from accelog_usuarios");
                    
                        if($busca2->num_rows>0){ ?>
                        <option value="0" selected>--Elija un usuario--</option>


                        <?php   while($usuario=$busca2->fetch_array(MYSQLI_ASSOC)){ ?>
                        <option value="<?php echo $usuario['idempleado']; ?>"><?php echo $usuario['usuario']; ?></option>
                        <?php } ?>

                        
                        <?php }else{?>  

                        <option selected>--No hay usuarios Registrados--</option>
                        <?php } ?>
                    </select>  
                </div>
                <div class="col-md-2 col-sm-2 ">
                    <label>Fecha de inicio:</label>
                    <input type="text" id="inicio" class="form-control"/>
                </div>
                <div class="col-md-2 col-sm-2 ">
                    <label>Fecha final:</label>
                    <input type="text" id="fin" class="form-control"/>
                </div>
                <div class="col-md-2 col-sm-2 ">
                    <label>&nbsp;</label>
                    <button type="button" id="busca" onclick="buscafecha();" class="btn btnMenu btn-primary">Buscar</button>
                </div>
            </div>
        </section>
        <section>
            <div class="row">
                <div class="col-md-12 col-sm-12 ">
                    <div class="table-responsive">
                        <table class="busqueda table" id="datos" cellpadding="3" cellspacing="1"  >
                            <tr class="tit_tabla_buscar" title="Segmento de búsqueda" style="font-size: 9pt;">
                                <th class="nmcatalogbusquedatit" align="center">ID</th>
                                <th class="nmcatalogbusquedatit" align="center">Almac&eacute;n Origen</th>
                                <th class="nmcatalogbusquedatit" align="center">Cantidad Total Origen</th>
                                <th class="nmcatalogbusquedatit" align="center">Movimiento</th>
                                <th class="nmcatalogbusquedatit" align="center">Almac&eacute;n Destino</th>
                                <th class="nmcatalogbusquedatit" align="center">Cantidad Total Destino</th>
                                <th class="nmcatalogbusquedatit" align="center">Fecha</th>
                                <th class="nmcatalogbusquedatit" align="center">Salida</th>
                                <th class="nmcatalogbusquedatit" align="center">Entrada</th>
                                <th class="nmcatalogbusquedatit" align="center">Comprobante</th>
                            </tr>
                            <?php 
                                    $strSql = " SELECT au.idSuc,mp.nombre ";
                                    $strSql .= " FROM administracion_usuarios au,mrp_sucursal mp ";
                                    $strSql .= " WHERE mp.idSuc=au.idSuc AND au.idempleado=" . $_SESSION['accelog_idempleado'];
                  
                                    $suc=$conection->query($strSql);
                                    if($suc->num_rows>0){
                                        if($sucursal=$suc->fetch_array(MYSQLI_BOTH)){
                                            $idsucursal = $sucursal[0];
                                        }
                                    }  
                                    
                                    $strSql2 = "SELECT s.idSuc, s.nombre sucursal,a.idAlmacen ,a.nombre almacen ";
                                    $strSql2 .= " FROM mrp_sucursal s, almacen a ";
                                    $strSql2 .= " WHERE s.idAlmacen=a.idAlmacen AND s.idSuc=" . $idsucursal;     
                                  
                                    $alm=$conection->query($strSql2);
                                    if($alm->num_rows>0){
                                        if($almacen=$alm->fetch_array(MYSQLI_BOTH)){
                                            $idAlmacen = $almacen[2];
                                        }
                                    }   
                            

                              /*  $consul=$conection->query("SELECT mm.id,
                                (select nombre from almacen where idAlmacen=mm.idAlmacenOrigen) almacenorigen,
                                mm.cantidadtotalOrigen,
                                concat(mm.cantidadmovimiento,' ',u.compuesto,' de ',p.nombre) movimiento,
                                (select nombre from almacen where idAlmacen=mm.idAlmacenDestino) almacendestino,
                                mm.cantidadtotalDestino,mm.fechamovimiento, usu.usuario, mm.status
                                from movimientos_mercancia mm,mrp_producto p,mrp_unidades u,almacen a, accelog_usuarios usu
                                where   mm.idAlmacenDestino=".$idAlmacen." and mm.idProducto=p.idProducto
                                and mm.idUnidad=u.idUni  and mm.idEmpleado=usu.idempleado  GROUP BY mm.id;"); */
        //$paginas=($consul->num_rows/$paginacion);if($consul->num_rows%$paginacion!=0){$paginas++;}
                                $consul=$conection->query("select mm.id,
                                (select nombre from almacen where idAlmacen=mm.idAlmacenOrigen) almacenorigen,
                                mm.cantidadtotalOrigen,
                                concat(mm.cantidadmovimiento,' ',u.compuesto,' de ',p.nombre) movimiento,
                                (select nombre from almacen where idAlmacen=mm.idAlmacenDestino) almacendestino,
                                mm.cantidadtotalDestino,mm.fechamovimiento, usu.usuario, mm.status,
                                (select IF(mm.idEmpleadoRec = 0, 'Nada', usu.usuario) as usuarioentrada from accelog_usuarios usu where mm.idEmpleadoRec=usu.idempleado) usuarioentrada
                                from movimientos_mercancia mm,mrp_producto p,mrp_unidades u,almacen a, accelog_usuarios usu
                                where   mm.idAlmacenDestino=a.idAlmacen and mm.idProducto=p.idProducto
                                and mm.idUnidad=u.idUni  and mm.idEmpleado=usu.idempleado  GROUP BY mm.id ORDER BY mm.id desc;"); 


                                $cont=0;
                                while($lista=$consul->fetch_array(MYSQLI_ASSOC)){ 
                                            if ($cont%2==0)//Si el contador es par pinta esto en la fila del grid
                                                {
                                                    $color='nmcatalogbusquedacont_1';
                                                }
                                                else//Si es impar pinta esto
                                                {
                                                    $color='nmcatalogbusquedacont_2';
                                                }
                                                    $cont++;

                                ?>
                                <tr class="<?php echo $color; ?>" style=" color:#6E6E6E; font-size: 10pt;">
                                    <td align="center"> 
                                        <?php echo $lista['id']; ?>
                                    </td >
                                    <td align="center">
                                        <?php echo utf8_encode($lista['almacenorigen']); ?>
                                    </td>
                                    <td align="center">
                                        <?php echo $lista['cantidadtotalOrigen']; ?>
                                    </td>
                                    <td align="center">
                                        <?php echo utf8_encode($lista['movimiento']); ?>
                                    </td>
                                    <td align="center">
                                        <?php echo utf8_encode($lista['almacendestino']); ?>
                                    </td>
                                    <td align="center">
                                        <?php echo $lista['cantidadtotalDestino']; ?>
                                    </td>
                                    <td align="center">
                                        <?php echo $lista['fechamovimiento']; ?>
                                    </td>
                                    <td align="center">
                                        <?php echo $lista['usuario']; ?>
                                    </td>
                                    <td align="center">
                                        <?php 
                                            if($lista['usuarioentrada']==''){ 
                                               ?>
                                        <button class="btn btn-warning btn-xs" type="button" onclick="aprueba('<?php echo $lista['id']; ?>')" id="boton_<?php echo $lista['id']; ?>">Recibir</button>
                                        <img src="../img/loader.gif" alt="" style="display:none;" id="loader_<?php echo $lista['id']; ?>">
                                          <?php  }else{
                                            echo '<p style="color: #027B06;">'.$lista['usuarioentrada'].'</p>';
                                          }
                                        ?>
                                        
                                    </td>
                                    <td align="center">
                                        <div onclick="comprobante('<?php echo $lista['id']; ?>');">
                                            <img src="../../punto_venta_nuevo/images/imprime.png" style="width:20px; height:20px; align:center;" alt=""  >
                                        </div>
                                    </td>

                                </tr>
                                <?php }

                                /*$cont=0;
                                for($j=$i;$j<5;$j++)
                                    {           
                                    if ($cont%2==0)//Si el contador es par pinta esto en la fila del grid
                                                {
                                                    $color='nmcatalogbusquedacont_1';
                                                }
                                                else//Si es impar pinta esto
                                                {
                                                    $color='nmcatalogbusquedacont_2';
                                                }
                                                    $cont++;
                                        ?>
                                <tr class="<?php echo $color; ?>" style="height:20px"><tr class="<?php echo $color; ?>" style="height:20px">
                                    <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                                </tr>
                                <?php }*/

                                ?>
                        </table>
                        <?php $conection->close(); ?>
                    </div>
                </div>
            </div>
        </section>
    </div>
</body>
</html>