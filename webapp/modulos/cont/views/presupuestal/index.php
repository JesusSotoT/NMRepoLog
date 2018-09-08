
<script src="../cont/js/jquery-1.10.2.min.js"></script>
<script src="../../libraries/bootstrap/dist/js/bootstrap.min.js" type="text/javascript"></script>
<script src="js/select2/select2.min.js"></script>
<script src="js/BigEval.js"></script>
<script src="js/redirect.js" type="text/javascript" language="javascript" charset="UTF-8"></script>
<link rel="stylesheet" type="text/css" href="js/select2/select2.css" />
<style>
    /*#lay_obj
    {
        float:left;
        position:absolute;
        background-color:#BDBDBD;
        width:350px;
        height:150px;
        border:1px solid white;
        box-shadow: 2px 2px 5px #000000;
        margin-left:2px;
        z-index: 1;
    }
    #saldos_div
    {
        float:left;
        position:absolute;
        background-color:#BDBDBD;
        width:350px;
        height:150px;
        border:1px solid white;
        box-shadow: 2px 2px 5px #000000;
        margin-left:2px;
        z-index: 1;
    }
    #modificar_div
    {
        float:left;
        position:absolute;
        background-color:#BDBDBD;
        width:3000px;
        height:120px;
        border:1px solid white;
        box-shadow: 2px 2px 5px #000000;
        margin-left:2px;
        z-index: 1;
    }*/
    #captura
    {
        background-color:white;
    }
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
    .twitter-typeahead{
        width: 100% !important;
    }
    .tablaResponsiva{
        max-width: 100vw !important; 
        display: inline-block;
    }
    table td {
        padding: 0.2em !important;
    }
</style>

<div id="lay_obj" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <form action='index.php?c=Presupuesto&f=cargaLay' method='post' name='archivo_xls' enctype="multipart/form-data" id='xls_arch' onsubmit='return validar_xls()'>
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Cargar Presupuestos mediante Layout</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <a href='Formato_presupuestos.xls'>Descargar Layout</a>
                            <hr/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <input type='file' id='presupuesto_xls' name='presupuesto_xls' >
                            <input type='hidden' name='xls_ejercicio' id='xls_ejercicio' value='<?php echo $Ex['EjercicioActual']; ?>' >
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label id='cargando_lay' style='display:none;' class="text-center">Cargando...</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="row">
                        <div class="col-md-6 col-md-offset-6">
                             <input class="btn btn-primary btnMenu" type='submit' id='presupuesto_btn' value='Cargar'>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="saldos_div" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
           <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">SALDO DEL EJERCICIO ANTERIOR</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <label>Agregar cantidad:</label>
                        <input class="form-control" type='text' name='operacion' id='operacion' value='0.00'>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <input type='radio' name='distrib' id='distrib0' value='0' class='distrib' checked> Cantidad Anual
                    </div>
                    <div class="col-md-6">
                        <input type='radio' name='distrib' id='distrib1' value='1' class='distrib'> Cantidad por mes
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="row">
                    <div class="col-md-6 col-md-offset-6">
                        <button id='ejecutar' class="btn btn-primary btnMenu">Ejecutar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="modificar_div" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
           <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">MODIFICAR PRESUPUESTO</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="table-responsive">
                            <table>
                                <tr style='background-color:#BDBDBD;color:white;font-weight:bold;height:30px;'>
                                    <th>Cuenta</th><th>Segmento</th><th>Sucursal</th><th></th><th style='text-align:right;'>Anual</th><th></th><th style='text-align:right;'>Enero</th><th style='text-align:right;'>Febrero</th><th style='text-align:right;'>Marzo</th><th style='text-align:right;'>Abril</th><th style='text-align:right;'>Mayo</th><th style='text-align:right;'>Junio</th><th style='text-align:right;'>Julio</th><th style='text-align:right;'>Agosto</th><th style='text-align:right;'>Septiembre</th><th style='text-align:right;'>Octubre</th><th style='text-align:right;'>Noviembre</th><th style='text-align:right;'>Diciembre</th>
                                </tr>
                                <tr>
                                    
                                    <td>
                                        <select name="cuentas1" id="cuentas1">
                                            <option value="0">Ninguno</option>
                                            <?php
                                            while($cuentas = $listaCuentas1->fetch_object())
                                            {
                                                echo "<option value='".$cuentas->account_id."'>".$cuentas->manual_code." / ".$cuentas->description."</option>";
                                            }
                                            ?>
                                        </select>
                                    </td>
                                    <td>
                                        <select name="segmento1" id="segmento1">
                                            <?php
                                            while($seg = $segmentos1->fetch_object())
                                            {
                                                 echo "<option value='".$seg->idSuc."'>".$seg->nombre."</option>";
                                            }
                                            ?>
                                        </select>
                                    </td>
                                    <td>
                                        <select name="sucursal1" id="sucursal1">
                                            <?php
                                            while($suc = $sucursales1->fetch_object())
                                            {
                                                 echo "<option value='".$suc->idSuc."'>".$suc->nombre."</option>";
                                            }
                                            ?>
                                        </select>
                                    </td>
                                    <td><button id='saldo1' class="btn btn-primary btnMenu">Saldo Anterior</button><input type='hidden' id='idhidden'></td>
                                    <td style='text-align:right;'><input class="form-control" type='text' name='anual1' id='anual1' value='0.00' onChange='aritmetica(this)'></td>
                                    <td><button id='prorratear1' class="btn btn-primary btnMenu">Prorratear</button></td>
                                    <td style='text-align:right;'><input type='text' name='enero1' id='mes0' class='form-control meses1' value='0.00' onChange='aritmetica(this); actualizaTotal(1)'></td>
                                    <td style='text-align:right;'><input type='text' name='febrero1' id='mes1' class='form-control meses1' value='0.00' onChange='aritmetica(this); actualizaTotal(1)'></td>
                                    <td style='text-align:right;'><input type='text' name='marzo1' id='mes2' class='form-control meses1' value='0.00' onChange='aritmetica(this); actualizaTotal(1)'></td>
                                    <td style='text-align:right;'><input type='text' name='abril1' id='mes3' class='form-control meses1' value='0.00' onChange='aritmetica(this); actualizaTotal(1)'></td>
                                    <td style='text-align:right;'><input type='text' name='mayo1' id='mes4' class='form-control meses1' value='0.00' onChange='aritmetica(this); actualizaTotal(1)'></td>
                                    <td style='text-align:right;'><input type='text' name='junio1' id='mes5' class='form-control meses1' value='0.00' onChange='aritmetica(this); actualizaTotal(1)'></td>
                                    <td style='text-align:right;'><input type='text' name='julio1' id='mes6' class='form-control meses1' value='0.00' onChange='aritmetica(this); actualizaTotal(1)'></td>
                                    <td style='text-align:right;'><input type='text' name='agosto1' id='mes7' class='form-control meses1' value='0.00' onChange='aritmetica(this); actualizaTotal(1)'></td>
                                    <td style='text-align:right;'><input type='text' name='septiembre1' id='mes8' class='form-control meses1' value='0.00' onChange='aritmetica(this); actualizaTotal(1)'></td>
                                    <td style='text-align:right;'><input type='text' name='octubre1' id='mes9' class='form-control meses1' value='0.00' onChange='aritmetica(this); actualizaTotal(1)'></td>
                                    <td style='text-align:right;'><input type='text' name='noviembre1' id='mes10' class='form-control meses1' value='0.00' onChange='aritmetica(this); actualizaTotal(1)'></td>
                                    <td style='text-align:right;'><input type='text' name='diciembre1' id='mes11' class='form-control meses1' value='0.00' onChange='aritmetica(this); actualizaTotal(1)'></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="row">
                    <div class="col-md-6 col-md-offset-6">
                        <button onclick='guardar(1)' class="btn btn-primary btnMenu">Ejecutar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div id='loading' style='position:absolute;background-color:white;opacity: 0.8;width:100%;height:100%;padding-left:250px;'>
    <img src='images/loading2.gif'>Cargando...
</div>


<div class="container" id='imprimible'>
    <div class="row">
        <div class="col-md-12">
            <h3 class="nmwatitles text-center">
                Presupuestos<br>
                <a href='javascript:generaexcel()' id='excel'><img src='images/images.jpg' width='35px'></a>
            </h3>
            <section>
                <div class="row">
                    <div class="col-md-3">
                        <label>Ejercicio:</label>
                        <select class="form-control" id="ejercicio" onchange='listaPresupuestos()'>
                            <?php
                            while($ex = $ejercicios->fetch_object())
                            {
                                echo "<option value='$ex->Id'>$ex->NombreEjercicio</option>";
                            }
                            ?>
                        </select>
                        <input type='hidden' id='IdExAct' value='<?php echo $_SESSION['idejercicio_actual']; ?>'>
                    </div>
                    <div class="col-md-5">
                        <label>Buscar:</label>
                        <input type='text' class="form-control" id='busqueda' name='busqueda' placeholder='Buscar'>
                    </div>
                    <div class="col-md-4">
                        <label id='ejerlabel'></label>
                        <label id='cargarMLay'>Cargar presupuestos mediante layout </label>
                        <input type='checkbox' id='check_xls' onclick='check_pol(this)'>
                    </div>
                </div>
            </section>
            <section>
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12 tablaResponsiva">
                        <div class="table-responsive">
                            <table width='3000px' id='tablalist' class='listado'>
                                <tr style='background-color:#BDBDBD;color:white;font-weight:bold;height:30px;' no='1'>
                                    <th style='color:#BDBDBD;'></th><th>Cuenta</th><th>Segmento</th><th>Sucursal</th><th></th><th style='text-align:right; width:5%;'>Anual</th><th></th><th style='text-align:center; width:5%;'>Enero</th><th style='text-align:center; width:5%;'>Febrero</th><th style='text-align:center; width:5%;'>Marzo</th><th style='text-align:center; width:5%;'>Abril</th><th style='text-align:center; width:5%;'>Mayo</th><th style='text-align:center; width:5%;'>Junio</th><th style='text-align:center; width:5%;'>Julio</th><th style='text-align:center; width:5%;'>Agosto</th><th style='text-align:center; width:5%;'>Septiembre</th><th style='text-align:center; width:5%;'>Octubre</th><th style='text-align:center; width:5%;'>Noviembre</th><th style='text-align:center; width:5%;'>Diciembre</th>
                                </tr>
                                <tr id='captura' no='1'>
                                    <td style='color:white;'><a href="javascript:guardar('')"><img src='images/mas.png' style='width:20px;'></a></td>
                                    <td>
                                        <select name="cuentas" id="cuentas">
                                            <option value="0">Ninguno</option>
                                            <?php
                                            while($cuentas = $listaCuentas->fetch_object())
                                            {
                                                echo "<option value='".$cuentas->account_id."'>".$cuentas->manual_code." / ".$cuentas->description."</option>";
                                            }
                                            ?>
                                        </select>
                                    </td>
                                    <td>
                                        <select name="segmento" id="segmento">
                                            <?php
                                            while($seg = $segmentos->fetch_object())
                                            {
                                                 echo "<option value='".$seg->idSuc."'>".$seg->nombre."</option>";
                                            }
                                            ?>
                                        </select>
                                    </td>
                                    <td>
                                        <select name="sucursal" id="sucursal">
                                            <?php
                                            while($suc = $sucursales->fetch_object())
                                            {
                                                 echo "<option value='".$suc->idSuc."'>".$suc->nombre."</option>";
                                            }
                                            ?>
                                        </select>
                                    </td>
                                    <td><button id='saldo' class="btn btn-primary btnMenu">Saldo Anterior</button></td>
                                    <td style='text-align:right;'><input class="form-control" type='text' name='anual' id='anual' value='0.00' onChange='aritmetica(this)'></td>
                                    <td><button id='prorratear' class="btn btn-primary btnMenu">Prorratear</button></td>
                                    <td style='text-align:right;'><input type='text' name='enero' id='enero' class='meses form-control' value='0.00' onChange='aritmetica(this); actualizaTotal(0)'></td>
                                    <td style='text-align:right;'><input type='text' name='febrero' id='febrero' class='meses form-control' value='0.00' onChange='aritmetica(this); actualizaTotal(0)'></td>
                                    <td style='text-align:right;'><input type='text' name='marzo' id='marzo' class='meses form-control' value='0.00' onChange='aritmetica(this); actualizaTotal(0)'></td>
                                    <td style='text-align:right;'><input type='text' name='abril' id='abril' class='meses form-control' value='0.00' onChange='aritmetica(this); actualizaTotal(0)'></td>
                                    <td style='text-align:right;'><input type='text' name='mayo' id='mayo' class='meses form-control' value='0.00' onChange='aritmetica(this); actualizaTotal(0)'></td>
                                    <td style='text-align:right;'><input type='text' name='junio' id='junio' class='meses form-control' value='0.00' onChange='aritmetica(this); actualizaTotal(0)'></td>
                                    <td style='text-align:right;'><input type='text' name='julio' id='julio' class='meses form-control' value='0.00' onChange='aritmetica(this); actualizaTotal(0)'></td>
                                    <td style='text-align:right;'><input type='text' name='agosto' id='agosto' class='meses form-control' value='0.00' onChange='aritmetica(this); actualizaTotal(0)'></td>
                                    <td style='text-align:right;'><input type='text' name='septiembre' id='septiembre' class='meses form-control' value='0.00' onChange='aritmetica(this); actualizaTotal(0)'></td>
                                    <td style='text-align:right;'><input type='text' name='octubre' id='octubre' class='meses form-control' value='0.00' onChange='aritmetica(this); actualizaTotal(0)'></td>
                                    <td style='text-align:right;'><input type='text' name='noviembre' id='noviembre' class='meses form-control' value='0.00' onChange='aritmetica(this); actualizaTotal(0)'></td>
                                    <td style='text-align:right;'><input type='text' name='diciembre' id='diciembre' class='meses form-control' value='0.00' onChange='aritmetica(this); actualizaTotal(0)'></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>


<script language='javascript'>
$(function()
 {
    //EXTENDIENDO LA FUNCION CONTAINS PARA HACERLO CASE-INSENSITIVE
  $.extend($.expr[":"], {
"containsIN": function(elem, i, match, array) {
return (elem.textContent || elem.innerText || "").toLowerCase().indexOf((match[3] || "").toLowerCase()) >= 0;
}
});
  Number.prototype.format = function() {
        return this.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,");
    };
  // INICIA GENERACION DE BUSQUEDA
            $("#busqueda").bind("keyup", function(evt){
                //console.log($(this).val().trim());
                if(evt.type == 'keyup')
                {
                    $(".listado tr:containsIN('"+$(this).val().trim()+"')").css('display','table-row');
                    $(".listado tr:not(:containsIN('"+$(this).val().trim()+"'))").css('display','none');
                    $(".listado tr[no='1']").css('display','table-row');
                    if($(this).val().trim() === '')
                    {
                        $(".listado tr").css('display','table-row');
                    }
                }

            });
    $("#ejerlabel").text($("#ejercicio option:selected").text())
    $("#xls_ejercicio").val($("#ejercicio option:selected").val())
    $("#loading").hide();
    $("#cuentas,#segmento,#sucursal,#cuentas1,#segmento1,#sucursal1").select2({
                 width : "150px"
                });
    $("#prorratear").click(function() {
        var anual = parseFloat($("#anual").val())
        var anual12 = anual/12
        $(".meses").val(anual12.toFixed(2))
        var sumaMeses=0;
        $(".meses").each(function(index, el) {
            sumaMeses += parseFloat($(this).val());
        });
        var residuo = anual - sumaMeses
        var suma = parseFloat($("#diciembre").val())+residuo
        $("#diciembre").val(suma.toFixed(2));
    });
    $("#prorratear1").click(function() {
        var anual = parseFloat($("#anual1").val())
        var anual12 = anual/12
        $(".meses1").val(anual12.toFixed(2))
        var sumaMeses=0;
        $(".meses1").each(function(index, el) {
            sumaMeses += parseFloat($(this).val());
        });
        var residuo = anual - sumaMeses
        var suma = parseFloat($("#mes11").val())+residuo
        $("#mes11").val(suma.toFixed(2));
    });
    $("#saldos_div").hide()
    $("#lay_obj").hide()
    $("#modificar_div").hide()
    $("#saldo").click(function(){
        $("#saldos_div").modal('show');
    });

    $("#ejecutar").click(function() {
        if(!isNaN(parseFloat($("#operacion").val())))
        {
            $("#loading").show();
            saldos();
            //alert("si se pudo")
        }
        else
        {
            alert("Escriba un valor numerico.")
        }
    });

    $("#ejercicio").val($('#IdExAct').val())

   listaPresupuestos();

});
function guardar(o,id)
{
    $("#loading").show();
    var sumaMeses=0;
    $(".meses"+o).each(function(index, el) {
        sumaMeses += parseFloat($(this).val());
    });
    
    if(sumaMeses.toFixed(2) == parseFloat($('#anual'+o).val()).toFixed(2))
    {
        var meses_cont = '';
        var tipo=0;
        var idT = 0
        if(parseInt(o))
        {
            meses_cont = $('#mes0').val()+"|"+$('#mes1').val()+"|"+$('#mes2').val()+"|"+$('#mes3').val()+"|"+$('#mes4').val()+"|"+$('#mes5').val()+"|"+$('#mes6').val()+"|"+$('#mes7').val()+"|"+$('#mes8').val()+"|"+$('#mes9').val()+"|"+$('#mes10').val()+"|"+$('#mes11').val()
            tipo = 1;
            idT = $("#idhidden").val()
        }
        else
        {
            meses_cont = $('#enero').val()+"|"+$('#febrero').val()+"|"+$('#marzo').val()+"|"+$('#abril').val()+"|"+$('#mayo').val()+"|"+$('#junio').val()+"|"+$('#julio').val()+"|"+$('#agosto').val()+"|"+$('#septiembre').val()+"|"+$('#octubre').val()+"|"+$('#noviembre').val()+"|"+$('#diciembre').val()

        }
        
         $.post("ajax.php?c=Presupuesto&f=guardaPresupuesto",
            {
                IdEjercicio: $('#ejercicio').val(),
                IdCuenta: $('#cuentas'+o).val(),
                IdSegmento: $('#segmento'+o).val(),
                IdSucursal: $('#sucursal'+o).val(),
                Anual: $('#anual'+o).val(),
                Meses: meses_cont,
                Act: tipo,
                Id : idT
            },
            function(data2)
            {
                if(parseInt(data2))
                {
                    alert("Se ha guardado correctamente");
                    location.reload();
                   
                }
                else
                {
                    alert("Hubo un problema y no se guardo el registro, es probable que ya exista.");
                    $("#loading").hide();
                }
                
            });
    }
    else
    {
        $("#loading").hide();
        alert("La cantidad total de los meses no coincide con el total anual: "+sumaMeses.toFixed(2))
    }
}

function eliminar(id)
{
    var pregunta = confirm("Esta seguro que desea eliminar este presupuesto?");
    if(pregunta)
    {
         $.post("ajax.php?c=Presupuesto&f=eliminaPresupuesto",
            {
                Id: id
            },
            function()
            {
                listaPresupuestos();
            });
    }
}

function listaPresupuestos()
{
    $("#loading").show();
     $.post("ajax.php?c=Presupuesto&f=listaPresupuestos",
            {
                IdEjercicio: $("#ejercicio").val(),
                TipoCuenta: $("#tipo_cuenta").val()
            },
            function(data2)
            {
                $("#tablalist").find('tr').slice(2).remove()
                $('#tablalist').append(data2)
                $("#loading").hide();
                totales();
                
            });
     $("#ejerlabel").text($("#ejercicio option:selected").text())
     $("#xls_ejercicio").val($("#ejercicio option:selected").val())
}
function saldos()
{
    $.post("ajax.php?c=Presupuesto&f=presupuestoxSaldos",
            {
                idejercicio: $('#ejercicio').val(),
                cuenta: $('#cuentas').val(),
                segmento: $('#segmento').val(),
                sucursal: $('#sucursal').val(),
                sumar: $('#operacion').val(),
                distrib: $(".distrib:checked").val()
            },
            function(data)
            {
               //alert(data)
               var datos = data.split('/')
               $("#anual").val(parseFloat(datos[0]).toFixed(2))
               var meses = datos[1].split('|')
               $("#enero").val(parseFloat(meses[0]).toFixed(2))
               $("#febrero").val(parseFloat(meses[1]).toFixed(2))
               $("#marzo").val(parseFloat(meses[2]).toFixed(2))
               $("#abril").val(parseFloat(meses[3]).toFixed(2))
               $("#mayo").val(parseFloat(meses[4]).toFixed(2))
               $("#junio").val(parseFloat(meses[5]).toFixed(2))
               $("#julio").val(parseFloat(meses[6]).toFixed(2))
               $("#agosto").val(parseFloat(meses[7]).toFixed(2))
               $("#septiembre").val(parseFloat(meses[8]).toFixed(2))
               $("#octubre").val(parseFloat(meses[9]).toFixed(2))
               $("#noviembre").val(parseFloat(meses[10]).toFixed(2))
               $("#diciembre").val(parseFloat(meses[11]).toFixed(2))

               $("#operacion").val('0.00')
               cerrar()
               $("#loading").hide();
                
            });
}
function cerrar(div)
{
    $("#"+div+"_div").hide()
}
function totales()
{
    var total=0;
    $(".anual").each(function(index) {
        total += parseFloat($(this).attr('cantidad'))
    });
    $("#total-anual").html(total.format()).css("text-align","right")

    var mes=0;
    var m=0;
    for(m=0;m<=11;m++)
    {
        $(".mes-"+m).each(function(index) {
        mes += parseFloat($(this).attr('cantidad'))
        });
        $("#total-"+m).html(mes.format()).css("text-align","right")
        mes=0
    }
}
function aritmetica(i)
{
        //Toma el str del value del elemento
        var s = $("#"+i.id).val();

        //Si contiene el signo = entonces es operacion aritmetica
        if (s.indexOf('=') != -1) 
        {
            
            //Quita el signo de = en el string
            s = s.replace('=','')

            //Instancia el objeto de la clase BigEval (Plugin)
            var Obj = new BigEval();

            //Guarda el string en la variable total
            var total = Obj.exec(s)
            total = parseFloat(total)

            //Muestra resultado aritmetico
            $("#"+i.id).val(total.toFixed(2))
        }
}
function actualizaTotal(n)
{
    var total=0;
    if(n)
    {
        $(".meses1").each(function(index) {
        total += parseFloat($(this).val())
        });
        $("#anual1").val(total.toFixed(2))
    }
    else
    {
        $(".meses").each(function(index) {
        total += parseFloat($(this).val())
        });
        $("#anual").val(total.toFixed(2))
    }

}
function cambiar(id)
{
    var con = confirm("Estas seguro que quieres modificar este presupuesto?")
    if(con)
    {
        $.post("ajax.php?c=Presupuesto&f=datosPresup",
            {
                idPresup : id
            },
            function(data)
            {
                var idR = $("#row-"+id).offset();
                var idPos = idR.top;
                //$("#modificar_div").css("margin-top",idPos)

                //datos aqui
                var datos = data.split("**//**");
                $("#cuentas1").select2("val", datos[2]);
                $("#segmento1").select2("val", datos[3]);
                $("#sucursal1").select2("val", datos[4]);
                $("#anual1").val(datos[5])
                var m=0
                var mesdat = datos[6].split('|');
                for(m=0;m<=11;m++)
                {
                    $("#mes"+m).val(mesdat[m])
                }
                $("#idhidden").val(id)


                $("#modificar_div").modal('show');
            });


    }
}
function generaexcel()
{
    var imprim = $('#imprimible').html();
    var captura = $("#captura").html();
    imprim = imprim.replace(captura,'')
    imprim = imprim.replace('Cargar presupuestos mediante layout','')
    $().redirect('views/fiscal/generaexcel.php', {'cont': imprim, 'name': "Presupuestos "+$("#ejercicio option:selected").text()});
} 
function check_pol(c)
 {
    if(c.checked)
    {
        $("#lay_obj").modal('show');
    }
    else
    {
        $("#lay_obj").hide()
    }
 }
 function validar_xls()
    {
        var extension = $("#presupuesto_xls").val()
        extension = extension.split('.')
        if(!$("#presupuesto_xls").val() || extension[1] != 'xls')
        {
            alert('Es necesario agregar el layout (descargar el archivo xls) para generar este proceso')
            return false
        }
        else
        {
            $("#presupuesto_btn").attr('disabled',true)
            $("#cargando_lay").css('display','inline')
        }
    }
</script>
