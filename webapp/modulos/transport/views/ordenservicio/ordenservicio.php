<style>
#modal_tamano{
  width: 90% !important;
}
#modal_tamano2{
  width: 70% !important;
}
#modal_tamano3{
  width: 60% !important;
}
.nopadding {
   padding: 0 !important;
   margin: 0 !important;
}
#overflow {
  border: 1px solid blue;
  width:198px;
  overflow-y: auto;
  height: 100%;
}
.table th, .table td {
     

 }
 .table2 td{
  font-size: 8px;
 }
.row tr {
width: 20px; /*Aquí va el ancho de la Celda*/
height: 20px; /*Aquí el Alto*/
background-color: gray;
}
table.table.table-condensed {
    border: 1px solid gray;
}


.tabla th {
padding: 10px;
font-size: 13px;
background-color: #a4c3d1;
color: #fff;
border-right-width: 1px;
border-bottom-width: 1px;
border-right-style: solid;
border-bottom-style: solid;
border-right-color: #558FA6;
border-bottom-color: #558FA6;
font-family: “Trebuchet MS”, Arial;
text-transform: uppercase;
}
.tabla-gastos th {
padding: 10px;
font-size: 13px;
background-color: #80000d;
color: #fff;
border-right-width: 1px;
border-bottom-width: 1px;
border-right-style: solid;
border-bottom-style: solid;
border-right-color: #558FA6;
border-bottom-color: #558FA6;
font-family: “Trebuchet MS”, Arial;
text-transform: uppercase;
}
.tabla-anticipos th {
padding: 10px;
font-size: 13px;
background-color: #eecc3d;
color: #fff;
border-right-width: 1px;
border-bottom-width: 1px;
border-right-style: solid;
border-bottom-style: solid;
border-right-color: #558FA6;
border-bottom-color: #558FA6;
font-family: “Trebuchet MS”, Arial;
text-transform: uppercase;
}
.tabla2 th {
padding: 10px;
font-size: 8px;
background-color: #a4c3d1;
color: #fff;
border-right-width: 1px;
border-bottom-width: 1px;
border-top-width: 1px;
border-right-style: solid;
border-bottom-style: solid;
border-top-style: solid;
border-right-color: #558FA6;
border-bottom-color: #558FA6;
border-top-color: #558FA6;
font-family: “Trebuchet MS”, Arial;
text-transform: uppercase;
}
.tabla3 td {
padding: 10px;
font-size: 8px;
color: black;
border-right-width: 1px;
border-bottom-width: 1px;
border-top-width:solid;
border-right-style: solid;
border-bottom-style: solid;
border-top-style: solid;
border-right-color: #060606;
border-bottom-color: #060606;
border-top-color: #060606;
font-family: “Trebuchet MS”, Arial;
text-transform: uppercase;
}
.texto-vertical-1 {
    writing-mode: vertical-lr;
     -ms-transform:rotate(180deg); /* IE 9 */
  -moz-transform:rotate(180deg); /* Firefox */
  -webkit-transform:rotate(180deg); /* Safari and Chrome */
  -o-transform:rotate(180deg); /* Opera */
   
}
</style>
<head>
    <link rel="stylesheet" type="text/css" href="../../libraries/select2/dist/css/select2.min.css">
    <link rel="stylesheet" type="text/css" href="../../libraries/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/datatablesboot.min.css">
    <link rel="stylesheet" type="text/css" href="css/bootstrap-datepicker.min.css">
</head>

   <!-- ch@isystem - Librerias genericas -->
  <script src="../../libraries/jquery.min.js" type="text/javascript"></script>
  <script src="../../libraries/bootstrap/dist/js/bootstrap.min.js" type="text/javascript"></script>
  <script src="../../libraries/select2/dist/js/select2.min.js" type="text/javascript"></script>
  <!-- ch@isystem- Librerias raiz transport -->
  <script src="js/datatables.min.js" type="text/javascript"></script>
  <script src="js/bootstrap-datepicker.min.js" type="text/javascript"></script>
  <script src="js/ordenservicio.js" type="text/javascript"></script>
    <script src="js/html2canvas.min.js" type="text/javascript"></script>
 
<div id ="listOS" class="container">
  <h1>Ordenes de Servicio</h1>
           
    <hr style=" 
    margin-left: auto;
    margin-right: auto;
    border-width: 5px;
    border-color: #a4c3d1;
           ""   >


        <table id="table_list_OS" class="table-responsive table table-bordered tabla table-condensed" cellspacing="0" width="100%">
        <thead>
          <tr>
            <th>Solicitud</th>
            <th>Fecha</th>
            <th>OS</th>
            <th>Operdor</th>
            <th>N.E.</th>
            <th>Cliente</th>
            <th>Destinatario</th>
            <th>Ruta</th>
            <th>Poblacion</th>
            <th>Estado</th>
            <th>Capacidad</th>
            <th>Temp.</th>
            <th align="center"> Acciones </th>
        </thead>
        <tbody>
        </tbody>
      </table>
  </div>


<div class="modal fade" id="modal_form_sol" data-keyboard="false">
    <div class="modal-dialog" id="modal_tamano">
      <div class="modal-content">
          <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h3>Solicitud</h3>
        </div>
      <!-- FORMULARIO PRINCIPAL PARA LA EDICION DE OS INICIO -->
        <div id="formedit" class="container">
                <form id="form_solicitud" class="form-horizontal">
                  <input type="hidden" value="" id="idsolicitud"/> 
                  <div class="form-body" style="height:450px; overflow: scroll;">
                    <div>
                      <label aling="center"><big>Solicitud de Servicio</big></label>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-1">Cliente</label>
                      <div class="col-md-5">
                        <select id="cliente" class="form-control"></select>
                      </div>
                      <label class="control-label col-md-1 nopadding">Fecha:</label>
                      <div class="col-md-2 nopadding">
                        <input id="fechaD" class="form-control col-md-2" type="text">
                      </div>
                      <label class="control-label col-md-1 nopadding">Hora:</label>
                      <div class="col-md-2 nopadding">
                        <input id="horaD" class="form-control" style="width:80px;"type="text" onkeyup="mascara(this,':',patron,true)" maxlength="10" />
                      </div>
                    </div>
                    <div>
                      <div>
                        <label aling="center"><big>Cliente</big></label>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-1">Contacto</label>
                        <div class="col-md-5">
                          <input id="contacto" class="form-control" type="text">
                        </div>
                        <label class="control-label col-md-1 nopadding">Celular</label>
                        <div class="col-md-4">
                          <input id="celular" class="form-control" type="text">
                        </div>
                      </div>
                    </div>
                    <!--Datos del Servicio-->
                    <div>
                      <label aling="center"><big>Datos del Servicio</big></label>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-1 nopadding">Capacidad</label>
                      <div class="col-md-2 nopadding">
                        <select id="capacidad" class="form-control"></select>
                      </div>
                      <label class="control-label col-md-2 nopadding" >Tipo de Carga</label>
                        <div class="col-md-2 nopadding">
                          <select id="tipocarga" class="form-control"></select>
                        </div>
                      <label class="control-label col-md-1 nopadding">Embalaje</label>
                      <div class="col-md-2 nopadding">
                        <input id="embalaje" class="form-control" type="text">
                      </div>
                      <label class="control-label col-md-1 nopadding" style="width:55px;" >Peso</label>
                      <div class="col-md-1 nopadding">
                        <input id="peso" class="form-control" style="width:80px;" type="text">
                      </div>
                    </div>
                    <!--Radios button-->
                    <div class="form-group"> 
                      <label class="control-label col-md-2">Temperatura</label>
                      <div class="radio col-md-1" >
                          <label>
                            <input type="radio" name="temperaturaR" Value="Seco" id="Rseco" checked=="true"/>Seco <br> 
                            <input type="radio" name="temperaturaR" Value="Frio" id="Rfrio"/>Frio <br>
                          </label>
                      </div>
                      <label class="control-label col-md-1" id="gradosl">Grados</label>
                        <div class="col-md-1">
                          <input id="grados" class="form-control" type="text" style="width:50px;">
                        </div>
                      <label class="control-label col-md-2">Tipo Servicio</label>
                        <div class="radio col-md-2">
                          <label><input type="radio" name="tiposervicioR" Value="Flete" id="Rflete" checked>Servicio Flete
                              <br>
                            <input type="radio" name="tiposervicioR" Value="Renta" id="Rrenta">Renta Unidad</label>
                        </div>
                        <label class="control-label col-md-1">Viaje</label>
                        <div class="radio col-md-1">
                            <label><input type="radio" class ="viaje" name="viajeR" Value="Local" id="viajeRLocal" checked>Local
                              <br>
                            <input type="radio" class ="viaje" name="viajeR" Value="Foraneo" id="viajeRForaneo" >Foraneo</label>
                        </div>
                    </div>
                    <!--Datos Carga -->
                    <div class="form-group">
                            <input type="hidden" value="" id="idcarga"/>
                            <div>
                              <label aling="center" class="col-md-2"><big>Datos Carga</big></label><a class="btn btn-sm btn-primary" href="javascript:void()" onclick="datos_carga()"><i class="glyphicon glyphicon-road"></i></a>
                            </div>
                            <label class="control-label col-md-2">Carga en:</label>
                            <div class="col-md-4">
                              <input id="cargaC" class="form-control" type="text">
                            </div>
                            <label class="control-label col-md-1">Calle:</label>
                            <div class="col-md-4">
                              <input id="calleC" class="form-control" type="text">
                            </div>
                            <label class="control-label col-md-2">Referencia</label>
                            <div class="col-md-4">
                              <input id="referenciaC" class="form-control" type="text">
                            </div>
                            <label class="control-label col-md-1">Colonia</label>
                            <div class="col-md-4">
                              <input id="coloniaC" class="form-control" type="text">
                            </div>
                            <label class="control-label col-md-2">Estado</label>
                            <div class="col-md-4">
                              <input id="estadoC" class="form-control" type="text">
                            </div>
                            <label class="control-label col-md-1">Ciudad</label>
                            <div class="col-md-4">
                              <input id="ciudadC" class="form-control" type="text">
                            </div>
                            <label class="control-label col-md-2">Preguntar por:</label>
                            <div class="col-md-4">
                              <input id="preguntarC" class="form-control" type="text">
                            </div>
                            <label class="control-label col-md-1">Telofono</label>
                            <div class="col-md-4">
                              <input id="telefonoC" class="form-control" type="text">
                            </div>
                            <label class="control-label col-md-2">Fecha de Carga</label>
                            <div class="col-md-4">
                              <input id="fechaC" class="form-control" type="text">
                            </div>
                            <label class="control-label col-md-1">Hora</label>
                            <div class="col-md-2">
                              <input id="horaC" class="form-control" style="width:80px;"type="text" onkeyup="mascara(this,':',patron,true)" maxlength="10" />
                            </div>
                      </div>
                    <!--Datos Entrega-->
                    <div class="form-group ">
                      <input type="hidden" value="" id="identrega"/>
                      <div>
                        <label class="col-md-2" aling="center"><big>Datos Entrega</big></label><a class="btn btn-sm btn-primary" href="javascript:void()" onclick="datos_entrega()"><i class="glyphicon glyphicon-road"></i></a>
                      </div>
                      <label class="control-label col-md-2">Entrega en:</label>
                      <div class="col-md-4">
                        <input id="cargaE" class="form-control" type="text">
                      </div>
                      <label class="control-label col-md-1">Calle:</label>
                      <div class="col-md-4">
                        <input id="calleE" class="form-control" type="text">
                      </div>
                      <label class="control-label col-md-2">Referencia</label>
                      <div class="col-md-4">
                        <input id="referenciaE" class="form-control" type="text">
                      </div>
                      <label class="control-label col-md-1">Colonia</label>
                      <div class="col-md-4">
                        <input id="coloniaE" class="form-control" type="text">
                      </div>
                      <label class="control-label col-md-2">Estado</label>
                      <div class="col-md-4">
                        <input id="estadoE" class="form-control" type="text">
                      </div>
                      <label class="control-label col-md-1">Ciudad</label>
                      <div class="col-md-4">
                        <input id="ciudadE" class="form-control" type="text">
                      </div>
                      <label class="control-label col-md-2">Preguntar por:</label>
                      <div class="col-md-4">
                        <input id="preguntarE" class="form-control" type="text">
                      </div>
                      <label class="control-label col-md-1">Telofono</label>
                      <div class="col-md-4">
                        <input id="telefonoE" class="form-control" type="text">
                      </div>
                      <label class="control-label col-md-2">Fecha de Entrega</label>
                        <div class="col-md-4">
                          <input id="fechaE" class="form-control" type="text">
                        </div>
                      <label class="control-label col-md-1">Hora</label>
                        <div class="col-md-2">
                          <input id="horaE" class="form-control" style="width:80px;"type="text" onkeyup="mascara(this,':',patron,true)" maxlength="10" />
                        </div>
                    </div>
                    <!--Text areas-->
                      <div>
                        <label class="control-label col-md-2"><big>Atencion</big></label>
                        <div class="col-md-4">
                         <textarea id="atencion" class="form-control" rows="2"></textarea>
                        </div>
                      </div>
                      <div>
                        <label class="control-label col-md-2"><big>Evidencia</big></label>
                        <div class="col-md-4">
                          <textarea id="evidencias" class="form-control" rows="2"></textarea>
                        </div>
                      </div>
                      <div>
                        <label class="control-label col-md-2"><big>Requerimientos del Cliente</big></label>
                        <div class="col-md-4">
                          <textarea id="requerimientos" class="form-control" rows="2"></textarea>
                        </div>
                      </div>
                      <div>
                        <label class="control-label col-md-2"><big>Recomendaciones</big></label>
                        <div class="col-md-4">
                         <textarea id="recomendaciones" class="form-control" rows="2"></textarea>
                        </div>
                      </div>
                </form>
        <!--SECCION PARA AÑADIR CONVENIOS INICIO -->
              
                <div id="modal_tamano">
                  
                  <input type="hidden" value="" id="lastid" name="id"/> 
                  <h4>Convenios</h4>
                      <button type="button" class="btn btn-default" id="btnaddCon" onclick="addConvenio()"><i class="glyphicon glyphicon-chevron-down"></i>Agregar Convenio</button>
                      <button type="button" class="btn btn-default" id="btnnewCon" onclick="newConvenio()"><i class="glyphicon glyphicon-plus"></i>Nuevo Convenio</button>
                      <table id="table_listado_con" class="table table-striped table-bordered" cellspacing="0" width="90%">
                      <thead>
                        <tr>
                          <th>Clave</th>
                          <th>Cantidad</th>
                          <th>Descripcion</th>
                          <th>Precio</th>
                          <th>Importe</th>                   
                          <th>Incluir</th>
                          <!--<th>Editar</th>-->
                        </tr>
                      </thead>
                      <tbody>
                      </tbody>
                    </table>
                </div>
        <!--SECCION PARA AÑADIR CONVENIOS FIN -->
        <!--SECCION ASIGNACION INICIO -->
              
                    <div id="modal_tamano3">
                      <h4>Asignacion</h4>
                      <input type="hidden" value="" id="idsol_inc" name="id"/> 
                        <label class="control-label nopadding">Fecha:</label>
                        <div class="nopadding">
                          <input id="fechaA" class="form-control" type="text">
                        </div>
                        <label class="control-label">Operador</label>
                        <div class="">
                          <select id="idope" class="form-control"></select>
                        </div>
                        <label class="control-label">Unidad</label>
                        <div class="">
                          <select id="iduni" class="form-control"></select>
                        </div>
                        <label class="control-label col-md-2">Caja</label>
                        <div class="">
                          <select id="idcaja" class="form-control"></select>
                        </div>
                        <label class="control-label">No. Logistica</label>
                        <div class="">
                          <input id="logistica" class="form-control col-md-2" type="text">
                        </div>
                  </div>
        <!--SECCION ASIGNACION FIN -->
      </div>
      <!-- FORMULARIO PRINCIPAL PARA LA EDICION DE OS FIN -->
      </div><!-- /.modal-content -->
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
        </div>
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->
</div> <!-- div auxiliar para cerrar-->


<div class="modal fade" id="modal_form_convList" data-keyboard="false">
    <div class="modal-dialog" id="modal_tamano">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h3 class="modal-title_sol">Agregar convenios</h3>
        </div>

          <div class="container" id="modal_tamano">
                <table id="table_listadoConv" class="table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                  <tr>
                    <th>Clave</th>
                    <th>Cliente</th>
                    <th>Estado</th>
                    <th>Municipio</th>
                    <th>Capcidad</th>
                    <th>Temperatura</th>
                    <th>Descripcion</th>
                    <th>Desc. Corta</th>
                    <th>Precio Cliente</th>
                    <th>Agregar</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>

              </table>
          </div>
          </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


    <!-- NUEVO CONVENIO -->
    <div class="modal fade" id="modal_form_conv" data-keyboard="false">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h3 class="modal-title_sol">Nuevo Convenio</h3>
        </div>

        <div class="modal-body form">
        <form action="#" id="form_convenio" class="form-horizontal">
          <input type="hidden" value="" id="folio" name="id"/>
          <input type="hidden" value="" id="lastid"/>  
          <div class="form-body">
            <div class="form-group">
              <label class="control-label col-md-3">Cliente</label>
              <div class="col-md-9">
                <select id="cli" class="form-control"></select>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3">Estado</label>
              <div class="col-md-9">
                <select id="est" class="form-control"></select>
              </div>
            </div>
            <div id = "ciudades" class="form-group">
              <label class="control-label col-md-3">Ciudad</label>
              <div class="col-md-9">
                <select id="ciu" class="form-control"></select>
              </div>
            </div>
            
            <div class="form-group">
              <label class="control-label col-md-3">Capacidad</label>
              <div class="col-md-9">
                <select id="cap" class="form-control"></select>
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-3">Temperatura</label>
              <div class="col-md-9">
                <select id="temp" class="form-control">
                    <option value="Seco">Seco</option>
                    <option value="Frio">Frio</option>
                </select>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3">Descripcion</label>
              <div class="col-md-9">
                <input id="desc" class="form-control" type="text">
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3">Desc. Corta</label>
              <div class="col-md-9">
                <select id="desccorta" class="form-control"></select>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3">Precio Cliente</label>
              <div class="col-md-9">
                <input id="precioclie" class="form-control" type="text">
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3">Precio Proveedor</label>
              <div class="col-md-9">
                <input id="preciopro" class="form-control" type="text">
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3">Retencion</label>
              <div class="col-md-9">
                <input id="retencion" class="form-control" type="text">
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3">Comision Fija</label>
              <div class="col-md-9">
                <input id="comisionfija" class="form-control" type="text">
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3">Comision %</label>
              <div class="col-md-9">
                <input id="comisionporc" class="form-control" type="text">
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3">Coordinacion</label>
              <div class="col-md-9">
                <select  id="coor" class="form-control">
                    <option value="-1">SI</option>
                    <option value="0">NO</option>
                </select>
              </div>
            </div>
          </div>
        </form>
          </div>

          <div class="modal-footer">
            <button type="button" onclick="save_convenio()" class="btn btn-primary">Guardar</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
          </div>

          </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
      </div><!-- /.modal -->

<!-- GASTO -->
  <div class="modal fade" id="modal_form_gasto" data-keyboard="false">
    <div class="modal-dialog" id="modal_tamano">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h3 class="modal-title">Gasto Extra</h3>
        </div>

          <div class="container" id="modal_tamano">
            <hr style="

    margin-right:1px;
    border-width:3px;
    border-color: #960017;
           "   >
            <input type="hidden" value="" id="idOSgasto"/>
            <button id="add_gasto" class="btn btn-success" onclick="add_gasto()"><i class="glyphicon glyphicon-plus"></i>Agregar Nuevo Gasto</button>
            <br>
            <br>
                <table id="table_listado_gasto" class="table tabla-gastos  table-bordered" cellspacing="0" width="100%">
                <thead>
                  <tr>
                    <th>Folio</th>
                    <th>Clave</th>
                    <th>Monto</th>
                    <th>Observaciones</th>
                    <th>Cobro al Cliente</th>
                    <th>Accion</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
          </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal"> Cerrar </button>
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->

<!-- ANTICIPO -->
 <div class="modal fade" id="modal_form_anticipo" data-keyboard="false">
    <div class="modal-dialog" id="modal_tamano">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h3 class="modal-title">Anticipo</h3>
        </div>

          <div class="container" id="modal_tamano">
            <hr style="

            margin-right:1px;
            border-width:3px;
            border-color: #eecc3d;
           "   >
            <input type="hidden" value="" id="idOSanticipo"/> 
            <button id="add_gasto" class="btn btn-success" onclick="add_anticipo()"><i class="glyphicon glyphicon-plus"></i>Agregar Nuevo Anticipo</button>
                <table id="table_listado_anticipo" class="table  tabla-anticipos table-bordered" cellspacing="0" width="100%">
                <thead>
                  <tr>
                    <th>Folio</th>
                    <th>Fecha</th>
                    <th>Operador</th>
                    <th>Forma de Pago</th>
                    <th>Importe</th> 
                    <th>Accion</th>                   
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
          </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->
  <!-- ANTICIPO FIN-->

 <!-- AYUDANTE -->
 <div class="modal fade" id="modal_form_ayudante" data-keyboard="false">
    <div class="modal-dialog" id="modal_tamano">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h3 class="modal-title">Ayudante</h3>
        </div>

            <div class="container" id="modal_tamano">
            <input type="hidden" value="" id="idOSayudante"/>
            <div id="bitacora_div">
                <div>  <img class="col-md-3" src="http://www.trtrefrigerados.com/wp-content/uploads/2017/02/logo.png" style="width: 10%"><div class="col-md-4"></div>
                <div class="col-md-3" style="font-weight: bold;">TRT  PLUS  S.A  de  C.V</div>
                <br>
                <div class="col-md-2"></div>
                <div>MATAMOROS NO.547 COL.ALAMO ORIENTE C.P. 45560 TLAQUEPAQUE,JALISCO,MEXICO</div>
                <div class="col-xs-4"></div>
                <div>TEL: (0133) 3659 3222  /  RFC:TPL090216JZ5</div>

                 </div>
                 <div>
                      <table class="table">
                      <tbody>
                      <tr class="noCarta">
                      <td colspan="2" style="background-color:#a4c3d1;">FECHA:</td>
                      <td colspan="3"><input class="form-control" id="Bfecha" type="text"></td>
                      <td style="background-color:#a4c3d1;">HORA:</td>
                      <td><input class="form-control" id="Bhora" type="text"></td>
                      <td style="background-color:#a4c3d1;">ORDEN DE SERVICIO:</td>
                      <td><input class="form-control" id="Bno_viaje" type="text"></td>
                      </tr>
                      
                      <tr style="background-color:#a4c3d1;">
                      <td style="background-color:#a4c3d1;"></td>
                      <td colspan="5" align="center" style="background-color:#a4c3d1; font-style: bold;">DATOS DE LA UNIDAD Y REMOLQUE(S)</td>
                      <td style="background-color:#a4c3d1;"></td>
                      <td style="background-color:#a4c3d1;" ></td>
                      <td colspan="5" style="background-color:#a4c3d1;" ></td>
                      </tr>
                      <tr>
                      <td>CATEGORIA Y MARCA DEL VEHICULO:</td>
                      <td colspan="6" align="center"><input class="form-control" id="catB" type="text"></td>
                      <td>MODELO:</td>
                      <td><input class="form-control" id="modeloB" type="text"></td>
                      <td>PLACAS:</td>
                      <td><input class="form-control" id="placasB" type="text"></td>
                      <td>NUMERO ECONOMICO:</td>
                      <td><input class="form-control" id="necB" type="text"></td>
                      </tr>                      
                      <tr>
                      <tr style="background-color:#a4c3d1;">
                      <td style="background-color:#a4c3d1;"></td>
                      <td colspan="5" align="right" style="background-color:#a4c3d1; font-style: bold;">DATOS DEL CONDUCTOR</td>
                      <td style="background-color:#a4c3d1;"></td>
                      <td style="background-color:#a4c3d1;" ></td>
                      <td colspan="8" style="background-color:#a4c3d1;" ></td>
                      </tr>
                      <td>NOMBRE:</td>
                      <td colspan="8"><input class="form-control" id="nombCB" type="text"></td>
                      <td></td>
                      <td></td>
                      </tr>
                      <tr>
                      <td>NUMERO DE LICENCIA :</td>
                      <td colspan="3"><input class="form-control" id="numLCB" type="text"></td>
                      <td>TIPO DE LICENCIA :</td>
                      <td colspan="1"><input class="form-control" id="tipoLB" type="text"></td>
                      <td>VIGENCIA :</td>
                      <td colspan="2"><input class="form-control" id="vigB" type="text"></td>
                      <td></td>
                      <td></td>
                      </tr>
                      <tr>
                      <tr style="background-color:#a4c3d1;">
                      <td style="background-color:#a4c3d1;"></td>
                      <td colspan="5" align="right" style="background-color:#a4c3d1; font-style: bold;">ORIGEN Y DESTINO</td>
                      <td style="background-color:#a4c3d1;"></td>
                      <td style="background-color:#a4c3d1;" ></td>
                      <td colspan="4" style="background-color:#a4c3d1;" ></td>
                      </tr>
                      <tr>
                      <td>CIUDAD ORIGEN:</td>
                      <td colspan="4"><input class="form-control" id="oriB" type="text"></td>
                      <td>CIUDAD DESTINO:</td>
                      <td colspan="4"><input class="form-control" id="destB" type="text"></td>
                    
                      </tr>
                      </tr>
                    </tbody>
                  </table>
                    </div>
                    <div>
                      <table id="table_list_conv" class="table tabla2 tabla3 table-striped table-condensed table-bordered" cellspacing="0" width="50%">
                      <tr>   
                      <th>FECHA</th>
                      <th>HORA/ACTIVIDAD</th>
                      <th class="texto-vertical-1">00:00 A 01:00</th>
                      <th class="texto-vertical-1">01:01 A 02:00</th>
                      <th class="texto-vertical-1">02:01 A 03:00</th>
                      <th class="texto-vertical-1">03:01 A 04:00</th>
                      <th class="texto-vertical-1">04:01 A 05:00</th>
                      <th class="texto-vertical-1">05:01 A 06:00</th>
                      <th class="texto-vertical-1">06:01 A 07:00</th>
                      <th class="texto-vertical-1">07:01 A 08:00</th>
                      <th class="texto-vertical-1">08:01 A 09:00</th>
                      <th class="texto-vertical-1">09:01 A 10:00</th>
                      <th class="texto-vertical-1">10:01 A 11:00</th>
                      <th class="texto-vertical-1">11:01 A 12:00</th>
                      <th class="texto-vertical-1">12:01 A 13:00</th>
                      <th class="texto-vertical-1">13:01 A 14:00</th>
                      <th class="texto-vertical-1">14:01 A 15:00</th>
                      <th class="texto-vertical-1">15:01 A 16:00</th>
                      <th class="texto-vertical-1">16:01 A 17:00</th>
                      <th class="texto-vertical-1">17:01 A 18:00</th>
                      <th class="texto-vertical-1">18:01 A 19:00</th>
                      <th class="texto-vertical-1">19:01 A 20:00</th>
                      <th class="texto-vertical-1">20:01 A 21:00</th>
                      <th class="texto-vertical-1">21:01 A 22:00</th>
                      <th class="texto-vertical-1">22:01 A 23:00</th>
                      <th class="texto-vertical-1">23:01 A 00:00</th>
                      <th>TOTAL</th>
                      </tr>
                      <tr>
                       <td> </td>
                       <td>Hrs.CONDUCIENDO</td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        
                      </tr>
                      <tr>
                       <td> </td>
                       <td>Hrs.S/conducir PNP</td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        
                      </tr>
                      <tr>
                       <td> </td>
                       <td>Hrs.Fuera de servicio</td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        
                      </tr>
                      <tr>
                       <td> </td>
                       <td>Hrs.de Desc.</td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        
                      </tr>
                        <tr>
                       <td> </td>
                       <td>Hrs.CONDUCIENDO</td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        
                      </tr>
                      <tr>
                       <td> </td>
                       <td>Hrs.S/conducir PNP</td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                       
                      </tr>
                      <tr>
                       <td> </td>
                       <td>Hrs.Fuera de servicio</td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                       
                      </tr>
                      <tr>
                       <td> </td>
                       <td>Hrs.de Desc.</td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        
                      </tr>
                        <tr>
                       <td> </td>
                       <td>Hrs.CONDUCIENDO</td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        
                      </tr>
                      <tr>
                       <td> </td>
                       <td>Hrs.S/conducir PNP</td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        
                      </tr>
                      <tr>
                       <td> </td>
                       <td>Hrs.Fuera de servicio</td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        
                      </tr>
                      <tr>
                       <td> </td>
                       <td>Hrs.de Desc.</td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        
                      </tr>
                        <tr>
                       <td> </td>
                       <td>Hrs.CONDUCIENDO</td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        
                      </tr>
                      <tr>
                       <td> </td>
                       <td>Hrs.S/conducir PNP</td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        
                      </tr>
                      <tr>
                       <td> </td>
                       <td>Hrs.Fuera de servicio</td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        
                      </tr>
                      <tr>
                       <td> </td>
                       <td>Hrs.de Desc.</td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        
                      </tr>
                        <tr>
                       <td> </td>
                       <td>Hrs.CONDUCIENDO</td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        
                      </tr>
                      <tr>
                       <td> </td>
                       <td>Hrs.S/conducir PNP</td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        
                      </tr>
                      <tr>
                       <td> </td>
                       <td>Hrs.Fuera de servicio</td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                       
                      </tr>
                      <tr>
                       <td> </td>
                       <td>Hrs.de Desc.</td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        
                      </tr>
                        <tr>
                       <td> </td>
                       <td>Hrs.CONDUCIENDO</td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        
                      </tr>
                      <tr>
                       <td> </td>
                       <td>Hrs.S/conducir PNP</td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        
                      </tr>
                      <tr>
                       <td> </td>
                       <td>Hrs.Fuera de servicio</td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        
                      </tr>
                      <tr>
                       <td> </td>
                       <td>Hrs.de Desc.</td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        
                      </tr>
                      

                      </table>  
                      </div>
                      <div>
                      <table class="table table-responsive">
                      <tr>
            <td colspan="1" style="background-color: #a4c3d1;">ELABORO NOMBRE Y FIRMA:</td> <br>
            <td colspan="4"><input class="form-control" id="firmEL" type="text"></td>
           
            <td colspan="1" style="background-color: #a4c3d1;">FIRMA DEL CONDUCTOR:</td> <br> 
            <td colspan="4" ><input class="form-control" id="FIRMCOND" type="text"></td>
                      </tr>
                      </table>
                    </div>
                  </div>
          </div>

        <div class="modal-footer">
          <a onclick="makePDF()" title="Impresion" class="btn btn-primary"><i class="glyphicon glyphicon-print"> Imprimir</i></a>
          <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->
  <!-- AYUDANTE FIN-->

<!-- ADD-GASTO -->
  <div class="modal fade" id="modal_form_gasto_add" data-keyboard="false">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h3 class="modal-title">Agregar Gasto Extra</h3>
        </div>

        <div class="modal-body form">
        <form action="#" id="form_add_gasto" class="form-horizontal">
        <input type="hidden" value="" id="AGidSO"/> 
          <div class="form-body">
            <div class="form-group">
              <label class="control-label col-md-3">Descripción</label>
              <div class="col-md-9">
                <input id="AGdesc" class="form-control" type="text">
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3">Descripcion Corta</label>
              <div class="col-md-9">
                <select id="AGdesccorta" class="form-control"></select>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3">Clave</label>
              <div class="col-md-9">
                <input id="AGclave" class="form-control" type="text">
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3">Monto</label>
              <div class="col-md-9">
                <input id="AGmonto" class="form-control" type="text">
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3"><big>Observaciones</big></label>
              <div class="col-md-9">
               <textarea id="AGobs" class="form-control" rows="2"></textarea>
              </div>
            </div>
              <div class="form-group"> 
                <label class="control-label col-md-3">Cobro al Cliente</label>
                <div class="radio col-md-1" >
                    <label>
                      <input type="radio" name="RCobro" Value="SI" id="RCobrosi" checked=="true"/>SI <br> 
                      <input type="radio" name="RCobro" Value="NO" id="RCobrono"/>NO <br>
                    </label>
                </div>
          </div>
        </form>
        </div>

        <div class="modal-footer">
          <button type="button" onclick="save_gasto()" class="btn btn-primary">Guardar</button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
        </div>
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->

  <!-- ADD-ANTICIPO -->
 <div class="modal fade" id="modal_form_anticipo_add" data-keyboard="false">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h3 class="modal-title">Agregar Anticipo</h3>
        </div>

        <div class="modal-body form">
        <form action="#" id="form_add_anticipo" class="form-horizontal">

          <div class="form-body">
            <div class="form-group">
              <label class="control-label col-md-3">Fecha Captura</label>
              <div class="col-md-9">
                <input id="Anfecha" class="form-control" type="date" value="<?php echo date("Y-m-d"); ?>">
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3">Operador</label>
              <div class="col-md-9">
                <select id="Anoperador" class="form-control"></select>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3">Orden de Servicio</label>
              <div class="col-md-9">
                <input id="AnOS" class="form-control" type="text">
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3">Forma de Pago</label>
              <div class="col-md-9">
                <select id="Anformapago" class="form-control"></select>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3">Cuenta</label>
              <div class="col-md-9">
                <select id="Ancuenta" class="form-control"></select>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3">Referencia</label>
              <div class="col-md-9">
               <textarea id="Anreferencia" class="form-control" rows="2"></textarea>
              </div>
            </div>
            <div class="form-group"> 
                <label class="control-label col-md-3">Importe</label>
              <div class="col-md-9">
               <input id="Animporte" class="form-control" type="number">
              </div>
          </div>
        </form>
        </div>

        <div class="modal-footer">
          <button type="button" onclick="save_anticipo()" class="btn btn-primary">Guardar</button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
        </div>
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->
  <!-- ADD-ANTICIPO FIN-->

   <!-- ADD-AYUDANTE -->
 <div class="modal fade" id="modal_form_ayudante_add" data-keyboard="false">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h3 class="modal-title">Agregar Ayudante</h3>
        </div>

        <div class="modal-body form">
          <form action="#" id="form_add_ayudante" class="form-horizontal">
           <input type="hidden" value="" id="AYidOS"/>  
            
            <div class="form-body">
            <div class="form-group">
              <label class="control-label col-md-3">Operador</label>
              <div class="col-md-9">
                <select id="AYoper" class="form-control"></select>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3">Concepto</label>
              <div class="col-md-9">
                <select id="AYconcep" class="form-control"></select>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3">Monto</label>
              <div class="col-md-9">
                <input id="AYmonto" class="form-control" type="text">
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3">Observaciones</label>
              <div class="col-md-9">
                <input id="AYobserv" class="form-control" type="text">
              </div>
            </div>
          </div>
          </form>
        </div>


        <div class="modal-footer">
          <button type="button" onclick="save_ayudante()" class="btn btn-primary">Guardar</button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->

<!-- Modal Formulario para CARTA PORTE INICIO-->
  <div class="modal fade" id="modal_form_carta" data-keyboard="false">
    <div class="modal-dialog modal-lg" id="modal_tamano">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h3 class="modal-title_sol">Carta Porte</h3>
         </div>
        <div class="modal-body form" id"overflow">
          <form id="form_carta" class="form-horizontal">
            <input type="hidden" value="" id="idsolicitud"/>
            <input type="hidden" value="" id="idorden"/> 
            <div class="form-body"  overflow: scroll;">
              <div class="table-responsive"> 
                          <table class="table table-condensed nopadding">
                              <tbody>
                                  <tr class="noCarta">
                                      <td></td>
                                      <td colspan="3"></td>
                                      <td>CARTA PORTE</td>
                                      <td><input class="form-control" id="idcarta" type="text"></td>
                                  </tr>
                                  <tr>
                                      <td>LUGAR DE EXPEDICION</td>
                                      <td colspan="3"><input class="form-control" id="lugarexp" type="text"></td>
                                      <td>FECHA</td>
                                      <td><input class="form-control" id="Cfecha" type="text"></td>
                                  </tr>
                                   <tr>
                                      <td>ORIGEN:</td>
                                      <td colspan="3"><input class="form-control" id="Corigen" type="text"></td>
                                      <td></td>
                                      <td></td>
                                  </tr>
                                  <tr>
                                      <td>REMITENTE:</td>
                                      <td colspan="3"><input class="form-control" id="COremitente" type="text"></td>

                                      <td>RFC:</td>
                                      <td><input class="form-control" id="COrfc" type="text"></td>
                                  </tr>
                                  <tr>
                                      <td>DOMICILIO:</td>
                                      <td colspan="3"><input class="form-control" id="COdomicilio" type="text"></td>

                                      <td></td>
                                      <td></td>
                                  </tr>
                                  <tr>
                                      <td>RECOGER EN:</td>
                                      <td colspan="3"><input class="form-control" id="COrecojer" type="text"></td>
                                      <td></td>
                                      <td></td>
                                  </tr>
                                  <tr>
                                      <td>DESTINO:</td>
                                      <td colspan="3"><input class="form-control" id="Cdestino" type="text"></td>
                                      <td></td>
                                      <td></td>
                                  </tr>
                                  <tr>
                                      <td>DESTINATARIO:</td>
                                      <td colspan="3"><input class="form-control"id="CDdestinatario" type="text"></td>
                                      <td>RFC:</td>
                                      <td><input class="form-control" id="CDrfc" type="text"></td>
                                  </tr>
                                  <tr>
                                      <td>DOMICILIO:</td>
                                      <td colspan="3"><input class="form-control" id="CDdomicilio" type="text"></td>
                                      <td></td>
                                      <td></td>
                                  </tr>
                                  <tr>
                                      <td>ENTREGAR EN:</td>
                                      <td colspan="3"><input class="form-control" id="CDentregar" type="text"></td>
                                      <td></td>
                                      <td></td>
                                  </tr>
                                  <tr>
                                      <td>Valor Unitario Cuota Convenio TM Carga Fraccionaria:</td>
                                      <td><input class="form-control" id="valoruni" type="text"></td>
                                      <td>Valor Declarado:</td>
                                      <td><input class="form-control" id="valordec" type="text"></td>
                                      <td>Condiciones de Pago:</td>
                                      <td><input class="form-control" id="condiciones" type="text"></td>
                                  </tr>
                                  <tr>
                                   
                                    <table class="table table-condensed">
                                      <tr>
                                        <td>
                                          <table id="table_list_conv" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                            <thead>
                                              <tr>
                                                <th style="display:none;">ID</th>
                                                <th>CANT</th>
                                                <th>QUE EL REMITENTE DICE QUE CONTIENE</th>
                                                <th>P.UNITARIO</th>
                                                <th>OBSERVACIONES</th>
                                            </thead>
                                            <tbody id="tbodyobs">
                                            </tbody>
                                          </table>
                                        </td>
                                        
                                        <td>
                                          <table class="table table-bordered">
                                            <tr>
                                              <td>CONCEPTO</td>
                                              <td>IMPORTE</td>
                                            </tr>
                                            <tr>
                                              <td>FLETE</td>
                                              <td><input class="form-control" id="flete" type="text"></td>
                                            </tr>
                                            <tr>
                                              <td>SEGURO</td>
                                              <td><input class="form-control" id="seguro" type="text"></td>
                                            </tr>
                                            <tr>
                                              <td>MANIOBRAS</td>
                                              <td><input class="form-control" id="maniobras" type="text"></td>
                                            </tr>
                                            <tr>
                                              <td>AUTOPISTAS</td>
                                              <td><input class="form-control" id="autopistas" type="text"></td>
                                            </tr>
                                            <tr>
                                              <td>OTROS</td>
                                              <td><input class="form-control" id="otros" type="text"></td>
                                            </tr>
                                            <tr>
                                              <td>SUB-TOTAL</td>
                                              <td><input class="form-control" id="subtotal" type="text"></td>
                                            </tr>
                                            <tr>
                                              <td>16 % I.V.A.</td>
                                              <td><input class="form-control" id="iva" type="text"></td>
                                            </tr>
                                            <tr>
                                              <td>SUMA</td>
                                              <td><input class="form-control" id="suma" type="text"></td>
                                            </tr>
                                            <tr>
                                              <td>RETENCION</td>
                                              <td><input class="form-control" id="retencion1" type="text"></td>
                                            </tr>
                                            <tr>
                                              <td>TOTAL</td>
                                              <td><input class="form-control" id="total" type="text"></td>
                                            </tr>
                                          </table>
                                        </td>
                                      </tr>
                                      <tr>
                                        <td colspan="2">
                                          <table>
                                            </tr>
                                              <td>OPERADOR:</td>
                                              <td><input class="form-control" id="operador" type="text"></td>
                                              <td>UNIDAD:</td>
                                              <td><input class="form-control" id="unidad" type="text"></td>
                                              <td>PLACAS:</td>
                                              <td><input class="form-control" id="placas" type="text"></td>
                                            </tr>
                                          </table>
                                        </td>
                                      </tr>
                                      <tr>
                                        <td>IMPORTE TOTAL CON LETRA: <br> <input class="form-control" id="numletra" type="text"></td></td>
                                        <td>OBSERVACIONES <br> <input class="form-control" id="observ" type="text"></td></td>
                                      </tr>
                                    </table>
                                  </tr>
                              </tbody>
                          </table>
                        </div>
                      </form>
                    </div>
                    <div class="modal-footer">
                      <button type="button" id="btnPrint" onclick="print_carta()" class="btn btn-primary">Imprimir</button>
                      <button type="button" id="btnSave" onclick="save_carta()" class="btn btn-primary">Guardar</button>
                      <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                    </div>
                  </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
              </div><!-- /.modal -->
<!-- Modal Formulario para CARTA PORTE FIN-->


<script>  
  reloadtable();
 $('#cliente').select2();
 $('#destinatario').select2();


 html2canvas($("bitacora_div"), {
                onrendered: function (canvas) {
                    var win=window.open();
                    win.document.write("<br><img src='"+canvas.toDataURL()+"'/>");
                    win.print();
                }
            });
        
</script>
