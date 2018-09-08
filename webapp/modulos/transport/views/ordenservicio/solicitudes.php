<style>
#modal_tamano{
  width: 90% !important;
  height: 200% !important;

}
#modal_tamano2{
  width: 70% !important;
}
#modal_tamano3{
  width: 60% !important;
}
.nopadding_borrar {
   padding: 0 !important;
   margin: 0 !important;
}
#overflow {
  border: 1px solid blue;
  width:198px;
  overflow-y: auto;
  height: 100%;
}
.botones_carga_entrega {
  padding: 50px 0px 15px 10px;
}

.modal_agree_ope {
width: 90% !important;
max-height: 100%;
overflow-y: auto;
border: 1px solid blue;
  width:198px;
  overflow: auto;
  height: 100%;
}

.tabla-solicitudes th {
padding: 10px;
font-size: 11.1px;
background-color: #0088ff;
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


.table-convagree th {
padding: 10px;
font-size: 9px;
background-color: #e06231;
color: #fff;
}
.table-convlist th {
padding: 10px;
font-size: 9px;
background-color: #e0301e;
color: #fff;
}

</style>


<head>
    <link rel="stylesheet" type="text/css" href="../../libraries/select2/dist/css/select2.min.css">
    <link rel="stylesheet" type="text/css" href="../../libraries/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/datatablesboot.min.css">
    <link rel="stylesheet" type="text/css" href="css/bootstrap-datepicker.min.css">

</head>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
   <!-- ch@isystem - Librerias genericas -->
  <script src="../../libraries/jquery.min.js" type="text/javascript"></script>
  <script src="../../libraries/bootstrap/dist/js/bootstrap.min.js" type="text/javascript"></script>
  <script src="../../libraries/select2/dist/js/select2.min.js" type="text/javascript"></script>
  <!-- ch@isystem- Librerias raiz transport -->
  <script src="js/datatables.min.js" type="text/javascript"></script>
  <script src="js/bootstrap-datepicker.min.js" type="text/javascript"></script>
  <script src="js/funciones_gen.js" type="text/javascript"></script>
  <script src="js/bootstrap-datetimepicker.js" type="text/javascript"></script>
  <script type="text/javascript" src="js/bootstrap-datetimepicker.min.js"></script>

  <div class="container">
    <h1>Solicitudes de Servicio</h1>
  <hr style=" 
   
    border-width: 3px;
    border-color: #0088ff;
    width: 100%;
           ""   >
  <br />
    <button class="btn btn-success" onclick="add_solicitud()"><i class="glyphicon glyphicon-plus"></i> Nueva Solicitud</button>
    <br>


           <br>

        <table id="table_listado" class="table table-bordered tabla-solicitudes" cellspacing="0" width="">
        <thead>
          <tr>
            <th>Folio</th>
            <th>Solicitud <br> Dia- Hora</th>
            <th>Cliente</th>
            <th>Municipio</th>
            <th>Estado</th>
            <th>Carga <br> Dia - Hora</th>
            <th>Capacidad</th>
            <th>Temperatura</th>
            <th>Destinatario</th>
            <th>Entrega <br> Dia - Hora</th>
            <th>Estatus <br> de Solicitud</th>
            <th colspan = "4">Acciones</th>
        </thead>
        <tbody>
        </tbody>

      </table>
  </div>


<!-- ///////////////////////////////////////// ASIGNACION OPERADOR UNIDAD & MODALES ////////////////////////////////////////////// -->

<!-- ASIGNACION OPERDAOR UNIDAD-->
<div class="modal fade" id="modal_form_sol2" data-keyboard="false">
    <div class="modal-dialog" id="modal_tamano3">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h3 class="modal-title_sol">Asignar Unidad y Operador</h3>
        </div>
        <input type="hidden" value="" id="idsol_inc" name="id"/>
              <div class="container" id="modal_tamano3">
                  <label class="control-label nopadding">Fecha:</label>
                  <div class="nopadding">
                    <input id="fechaA" class="form-control" type="date" value="<?php echo date("Y-m-d");?>">
                  </div>
                  <label class="control-label">Operador</label>
                  <div class="">
                    <select id="idope" class="form-control" size="5" style="width:350px"></select>
                  </div>
                  <label class="control-label">Unidad</label>
                  <div class="">
                    <select id="iduni" class="form-control" size="5" style="width:350px"></select>
            <button class="btn btn-success" onclick="add_newUnidad()"><i class="glyphicon glyphicon-pencil" ></i></button>
                    <p style="font-style: #C8C8C8" >*Para agregar un nueva unidad haga clic sobre el boton.</p>
                  </div>
                  <label class="control-label">Caja</label>
                  <div class="">
                    <select id="idcaja" class="form-control" size="5" style="width:350px"></select>
                     <button class="btn btn-success" onclick="add_newCaja()"><i class="glyphicon glyphicon-pencil"></i></button>
                    <p style="font-style: #C8C8C8" >*Para agregar un nueva caja haga clic sobre el boton.</p>
                  </div>
                 
            </div>
            <div class="modal-footer">
              <button type="button" id="btnSave1" onclick="saveIncluir()" class="btn btn-primary">Guardar</button>
              <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
            </div>
          </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
      </div><!-- /.modal -->

<!--- MODAL AGREGAR NUEVO OPERADOR -->
<div class="modal fade" id="modal_agree_ope">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Agregar Nuevo Operador</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body">

              <div class="panel panel-success">
              <h5 class="panel-heading">Datos de Nuevo Operador</h5>
              <div class="panel-body">
              <div>
              <div class="form-group">
              <label class="control-label col-md-2">Nombre(s):</label>
              <div class="col-md-4">
              <input id="nombre_opeNew" class="form-control" type="text" placeholder="ej: Juan Manuel">
              </div>
              <div class="form-group">
              <label class="control-label col-md-2">Apellidos:</label>
              <div class="col-md-4">
              <input id="ape_opeNew" class="form-control" type="text" placeholder="ej: Yañez Ibarra">
              </div>
              </div>
              </div>
              </div>
              
              <br>
              <br>
              <div class="panel panel-info">
              <div class="panel-heading"></div>
              <div class="panel-body">
              <div>
              <div class="form-group">
              <label class="control-label col-md-1">Telefono:</label>
              <div class="col-md-3">
              <input id="tel_opeNew" class="form-control" type="text" placeholder="123456...">
              </div>
              <label class="control-label col-xs-1">RFC : </label>
              <div class="col-md-4">
              <input id="rfc_opeNew" class="form-control" type="text" placeholder="...">
              </div>
              <label class="control-label col-xs-1">Edad: </label>
              <div class="col-xs-2">
              <input id="age_opeNew" class="form-control" type="number" min="18" max="80" placeholder="0">
              </div>
              </div>
              </div>
              <br>
            
              <div>
              <div class="form-group">
              <label class="control-label col-md-1 nopadding">Estado:</label>
              <div class="col-xs-3 nopadding">
              <select id="est_opeNew" class="form-control" style="width:180px"></select>
              </div>
              <label class="control-label col-md-1 nopadding" >Ciudad:</label>
              <div class="col-xs-3 nopadding">
              <select id="ciu_opeNew" class="form-control" style="width:180px"></select>
              </div>
              <label class="control-label col-md-1 nopadding">Calle:</label>
              <div class="col-xs-3 nopadding">
              <input id="calle_opeNew" class="form-control" type="text" placeholder="calle">
              </div>
              <br>
                <br>
                <label class="control-label col-xs-1 nopadding">Nº Ext:</label>
                <div class="col-xs-1 nopadding">
                <input id="noext_opeNew" class="form-control" type="text" style="width: 60px" placeholder="Numero exterior">
                </div>
                <label class="control-label col-xs-1 nopadding" >Nº Int:</label>
                <div class="col-xs-1 nopadding">
                <input id="noint_opeNew" class="form-control" type="text" style="width: 60px" placeholder="Numero interior">
                </div>
                <label class="control-label col-md-1 nopadding">Colonia:</label>
                <div class="col-xs-3 nopadding">
                <input id="col_opeNew" class="form-control" type="text" placeholder="Colonia">
                </div>
                <label class="control-label col-xs-1 nopadding">C.P:</label>
                <div class="col-xs-2 nopadding">
                <input id="cp_opeNew" class="form-control" type="text" placeholder="Codigo postal">
                </div>
                </div>  
                </div>
                </div>
                </div> 
                <label class="control-label col-md-1">Numero Licencia:</label>
                <div class="col-md-4">
                <input id="nolic_opeNew" class="form-control" type="text" placeholder="10 a 24 digitos">
                </div>
                <label class="control-label col-xs-2">Fecha de registro:</label>
                <div class="col-md-4">
                <input id="fechaAgree" class="form-control" type="date" value="<?php echo date("y-m-d"); ?>">
                <br>
                </div>
                </div>
                </div>
                <div class="panel panel-danger">
                <h4 class="panel-heading">Contacto en caso de emergencia.</h4>
                <div class="panel-body">
                <label class="control-label col-md-1">Telefono 1: </label>
                <div class="col-md-4">
                <input id="tel1_opeNew" class="form-control" type="text" placeholder="ej:38268542">
                </div>
                <label class="control-label col-xs-2">Telefono 2:</label>
                <div class="col-md-4">
                <input id="tel2_opeNew" class="form-control" type="text" placeholder="ej:38268542">
                </div>
                <br>
                <br>
                <label class="control-label col-md-4">Nombre contacto de emergencia:</label>
                <div class="col-md-4">
                <input id="nomeme_opeNew" class="form-control" type="text" placeholder="ej: Martha Jimenez">
                </div>
                </div>
                </div>
                </div>
                      
                </div>
                <div class="modal-footer">
 <button type="button" class="btn btn-success btn-lg" onclick="saveNewope()">Guardar    </button>
                <button type="button" class="btn btn-danger btn-lg" data-dismiss="modal" onclick="resetagree()">Cancelar</button>
          </div>
       </div>
   </div>
</div>
<!--FIN AGREGAR NUEVO OPERADOR -->

<!--- MODAL AGREGAR NUEVA UNIDAD -->
<div class="modal fade" id="modal_form_addUni" data-keyboard="false">
    <div class="modal-dialog modal-lg" id="modal_tamano">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title_sol">Agregar Nueva Unidad </h4>
         </div>
        <div class="modal-body form" id"overflow">
          <form id="form_carta" class="form-horizontal">
            <input type="hidden" value="" id="idsolicitud"/>
            <input type="hidden" value="" id="idorden"/> 
            <div class="form-body"  overflow: scroll;">
              
          
         <table class="table table-condensed nopadding">
                              <tbody>
                                  <tr>
                                      <td>No.Economico:</td>
                                      <td width="10%"><input class="form-control" id="noEconomico" type="text" placeholder="XXX-123-134 , EC-123 , etc..."></td>
                                  </tr>
                                   <tr>
                                    <td>Marca:</td>
                                      <td colspan=""><select id="marcaUni" class="form-control" style="width: 350px;"></select></td>
                                      <td align="right">Modelo:</td>
                                      <td colspan="1"><input class="form-control" id="modeloUni" type="text" placeholder="Acros slt , Transit Custom , etc..."></td> 
                                      <td align="right">Año:</td>
                                      <td colspan="1"><input class="form-control" id="anioUni" type="text" placeholder="2010,2012,etc.."></td>      
                                  </tr>
                                  <tr>
                                      <td>Placas:</td>
                                      <td colspan="1"><input class="form-control" id="placasUni" type="text" placeholder="AX-241, FDE-234-2, etc..."></td>
                                      <td></td>
                                       <td>Color:</td>
                                      <td colspan="1"><input class="form-control" id="colorUni" type="text" placeholder="Azul , Negro , etc..."></td>
                                      <td></td>
                                      <td></td>
                                  </tr>
                                 
                                  <tr>
                                      <td>Tipo de Unidad:</td>
                                      <td colspan="2">  <select id="tipoUni" class="form-control" style="width: 350px;"></select></td>
                                      <td>Capacidad de la unidad:</td>
                                      <td><select id="capUni" class="form-control" style="width: 200px;"></select></td>
                                      <td></td>
                                      <td></td>
                                  </tr>
                                  <tr>
                                      <td>Tipo de Combustible:</td>
                                      <td colspan="3">  <select id="tipocombustibleUni" class="form-control" style="width: 350px;"></select></td>
                                    
                                  </tr>
                                  <tr>
                                      <td>Tamaño del Tanque (lts.):</td>
                                      <td colspan="1"><input class="form-control" id="tamanotanqUni" type="number"></td>
                                      <td>Rendimiento Foraneo (km/l):</td>
                                      <td colspan="1"><input class="form-control" id="rendforUni" type="number"></td>
                                      <td>Rendimiento Local (km/l):</td>
                                      <td colspan="1"><input class="form-control" id="rendlocUni" type="number"></td>
                                      <td></td>
                                      <td></td>
                                  </tr>
                                  <tr>
                                      <td>Refrigerado:</td>
                                      <td><input type="radio" name="refrigeradoR" id="refriUni" value="1" checked ><label>SI</label></td>
                                      <td><input type="radio" name="refrigeradoR" value="0" id="refriUniNo"><label>NO</label></td>
                                  </tr>
                                  <tr id="thermotable1">
                                    <td>Tamaño del Tanque del Thermo (lts):</td>
                                    <td><input type="number" id="tamtanqthermo"></td>
                                    <td>Rendmiento Foraneo (km/l):</td>
                                    <td><input type="number" id="rendthermfor"></td>
                                    <td>Rendimiento Local (km/l):</td>
                                    <td><input type="number" id="rendthermloc"></td>  
                                  </tr>
                                  <tr>
                                    <td>Fecha de Adquisicion:</td>
                                    <td><input id="fechaaddUni" class="form-control" type="date" value=""></td>
                                    <td>Kilometros a la Adquisicion:</td>
                                    <td><input type="number" id="kmadquisicion"></td>
                                    <td>Kilometros Totales:</td>
                                    <td><input type="number" id="kmtotal"></td>
                                  </tr>
                                  <tr>

                                  </tr>

                                </tbody>
                              </table>
                              <div>
                                 <div><h5>Observaciones de la Unidad:</h5></div>
                                    <textarea id="observaciones" rows="6" cols="100"></textarea>
                              </div>



          </form>
       </div>
          <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"> Cancelar</button>
                <button type="button" id="btnSave" onclick="save_addUni()" class="btn btn-success"><i class="glyphicon glyphicon-floppy-saved"></i> Añadir Unidad</button>
                
          </div>
       </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
</div><!-- /.modal -->
<!--FIN AGREGAR NUEVA UNIDAD -->


<!--- MODAL AGREGAR NUEVA CAJA -->
<div class="modal fade" id="modal_agree_caja">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Agregar Nueva Caja</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body">

              <div class="panel panel-warning">
              <h5 class="panel-heading">Datos de la Nueva caja</h5>
              <div class="panel-body">
              <div>
              <div class="form-group">
              <label class="control-label col-md-2">Numero EC:</label>
              <div class="col-md-4">
              <input id="numeco_new_caja" class="form-control" type="text" placeholder="ej: EC-0942">
              </div>
              <div class="form-group">
              <label class="control-label col-md-2">Placas:</label>
              <div class="col-md-4">
              <input id="placas_new_caja" class="form-control" type="text" placeholder="ej: JMD-94-48">
              </div>
              </div>
              </div>
              </div>
              
              <br>
              <br>
            
              <div>
              <div class="form-group">
              <label class="control-label col-md-2 ">Tipo de caja:</label>
              <div class="col-md-4 nopadding">
              <select id="tipocaja_new" class="form-control" style="width:180px"></select>
              </div>
              <div class="col-xs-2"></div>
              <label class="control-label col-md-1">Ejes: </label>
              <div class="col-xs-2">
              <input id="ejes_new" class="form-control" type="number" placeholder="ej: 2 ">
              </div>
              </div>
              </div>
              <br>
              <br>
              <br>
              <div>
              <div class="form-group">
              <label class="control-label col-md-2 nopadding">Color:</label>
              <div class="col-md-3 nopadding">
              <input id="color_new_caja" class="form-control" type="Text" placeholder="ej: Negro , Blanco">
              </div>
              <div class="col-md-3"></div>
              <label class="control-label col-md-1">Fecha de registro:</label>
                <div class="col-md-3">
                <input id="fecha_new_caja" class="form-control" type="date" value="<?php echo date("y-m-d"); ?>">
                </div> 
                </div>
                </div>
              </div>
                
                </div>
                <div class="panel panel-danger">
                <h4 class="panel-heading">Observaciones de la Caja</h4>
                <div class="panel-body">
                <textarea name="observaciones_caja" rows="5" cols="125" placeholder="Escriba aqui las observaciones de la unidad..."></textarea>
                <label>Maximo 250 caracteres.</label>
                </div>
                </div>
                   
                </div>
                <div class="modal-footer">
 <button type="button" class="btn btn-success btn-lg" onclick="saveNewcaja()">Guardar </button>
                <button type="button" class="btn btn-danger btn-lg" data-dismiss="modal" onclick="resetagree()">Cancelar</button>
          </div>
       </div>
   </div>
</div>
<!--FIN AGREGAR NUEVA CAJA -->








<!-- /////////////////////////////////////// FIN ASIGNACION OPERADOR UNIDAD & MODALES //////////////////////////////////////////// -->


<!-- RELACIONAR CONVENIO - SOLICITUD-->
<div class="modal fade" id="modal_form_con2sol" data-keyboard="false" overflow>
    <div class="modal-dialog" id="modal_tamano">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h3 class="modal-title_sol">Relacionar convenios</h3>
        </div>
<br>
<br>
          <div class="container" id="modal_tamano">
            <input type="hidden" value="" id="lastid" name="id"/>
            <div class="row">

                <div class="col-md-9">
                <button class="btn btn-success " id="btnnewCon" onclick=""><i class="glyphicon glyphicon-plus"></i>Nuevo Convenio</button>
                </div>
              <!--   <div class="col-xs-2">
                <button class="btn btn-warning " id="btnaddCon" onclick=""><i class="glyphicon glyphicon-asterisk"></i>Agregar Convenio</button>
                </div>      -->
            </div>
            <table>
              
                <br>
                <h5>Lista de convenios</h5>
                <table id="table_listado_con" class="table table-bordered table-convlist table-condensed" cellspacing="0">
                <thead>
                  <tr>
                    <th width="5%">ID Convenio</th>
                    <th width="20%">ID/Cliente</th>
                    <th width="">Cantidad</th>
                    <th>Descripcion</th>
                    <th width="15%">Tipo</th>
                    <th width="5%">Precio</th>
                    <th width="5%">Importe</th>
                    <th width="5%">Incluir</th>
                    <!--<th>Editar</th>-->
                  </tr>
                </thead>
                <tbody>
                </tbody>

              </table>
              <br>
              <h5>Convenios agregados</h5>
              <table id="table_listado_conAgree" class="table table-bordered table-convagree table-condensed" cellspacing="0">
                <thead>
                  <tr>
                    <th width="5%">ID Convenio</th>
                    <th width="20%">ID/Cliente</th>
                    <th width="5%">Cantidad</th>
                    <th >Descripcion</th>
                    <th>Tipo</th>
                    <th width="5%">Precio</th>
                    <th width="5%">Importe</th>
                    <th width="5%">Incluir</th>
                    <!--<th>Editar</th>-->
                  </tr>
                </thead>
                <tbody>
                </tbody>

              </table>
            </table>
              <div class="modal-footer" >
                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Cerrar </button>
              </div>
          </div>
          </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
      </div><!-- /.modal -->




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
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" onclick="resetcampos()">&times;</span></button>
          <h3 class="modal-title_sol">Nuevo Convenio</h3>
            </div>
              <div class="modal-body">
                <form action="#" id="form_convenio" class="form-horizontal">
                  <input type="hidden" value="" id="folio" name="id"/>
                    <input type="hidden" value="" id="lastid"/>
                        <table class="table" id="tablaAnticipos">
                          <tr>
                              <td><label class="control-label col-md-3">Cliente</label></td>
                              <td><input id="clienteconv" class="form-control"></input></td>
                          </tr>
                          <tr>
                              <td><label class="control-label col-md-3">Desc. Corta</label></td>
                              <td><select id="desccorta" class="form-control" style="width: 300px;"></select></td>
                              <td><label class="control-label col-md-3">Descripcion</label></td>
                              <td><textarea id="desc" class="form-control" rows="3" cols="100"></textarea></td>
                            </tr>
                              <tr>
                              <td><label class="control-label col-md-3">Precio Cliente</label></td> 
                              <td><input id="precioclie" class="form-control" type="text"></td>
                          </tr>
                          <tr>
                              <td><label class="control-label col-md-3">Retencion</label></td>   
                              <td><input id="retencion" class="form-control" type="text"></td>
                              <td><label class="control-label col-md-3">Comision %</label></td>
                              <td><input id="comisionporc" class="form-control" type="text"></td>
                          </tr>
                           </table>
                        </form>
                     </div>
                  <div class="modal-footer">
               <button type="button" onclick="save_convenio()" class="btn btn-success"><i class="glyphicon glyphicon-edit"></i> Crear Convenio</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Cancelar</button>
         </div>
      </div><!-- /.modal-content -->
   </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<!-- Modal Formulario para nueva solicitud INICIO-->
              <div class="modal fade" id="modal_form_sol" data-keyboard="false" style="overflow-y: scroll; max-height:95%;  margin-top: 30px; margin-bottom: 20px; ">
              <div class="modal-dialog modal-lg" id="modal_tamano" >
              <div class="modal-content">
              <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <div><h3>Nueva Solicitud</h3></div>
              </div>
        
              <div class="modal-body form" id"overflow">
              <form id="form_solicitud" class="form-horizontal">
              <input type="hidden" value="" id="idsolicitud"/>
              
              <div class="panel panel-info">
              <div class="panel-heading">DATOS DEL CLIENTE</div>
              <div class="panel-body">
              <div>
                <label aling="center"><big>Solicitud de Servicio : </big></label>
              </div>
              <div class="form-group">
                <label class="control-label col-md-1">Cliente : </label>
                <div class="col-md-5">
                  <select id="cliente" class="form-group" style="width:350px"></select>
                </div>
                <div class="form-group">
                <label class="control-label col-md-1 nopadding">Fecha:</label>
                <div class="col-md-2 nopadding">
                  <input id="fechaD" class="form-control col-md-2" type="date" value="<?php echo date("Y-m-d"); ?>">
                </div>
              
                <label class="control-label col-md-1 nopadding">Hora:</label>
                <div class="col-md-2 nopadding">
                  <input id="horaD" class="form-control" style="width:80px;"type="text" onkeyup="mascara(this,':',patron,true)" maxlength="10" disabled="" />
                </div>
              </div>
              </div>
              <div>

                <div class="form-group">
                  <label class="control-label col-md-1">Contacto</label>
                  <div class="col-md-5">
                    <input id="contacto" class="form-control" type="text" disabled>
                  </div>
                  <label class="control-label col-md-1 nopadding">Celular</label>
                  <div class="col-md-4">
                    <input id="celular" class="form-control" type="text" disabled>
                  </div>
                </div>
              </div>
      </div>
</div>


               <hr style="
    margin-left: auto;
    margin-right: auto;
    border-width:1px;
    border-color: #00a4e3;
           "   >
           
              <!--Datos del Servicio-->
              <div class="panel panel-primary">
              <div class="panel-heading">DATOS DE SERVICIO</div>
              <div class="panel-body">
              <div>     
              </div>
              <div class="form-group">
                <label class="control-label col-xs-2 nopadding">Capacidad</label>
                <div class="col-md-2 nopadding">
                  <select id="capacidad" class="form-control" style="width:190px;"></select>
                </div>
                <label class="control-label col-xs-1 nopadding" >Tipo de Carga</label>
                  <div class="col-md-2 nopadding">
                    <select id="tipocarga" class="form-control" style="width:190px;"></select>
                  </div>
                <label class="control-label col-xs-1 nopadding">Embalaje</label>
                <div class="col-md-2">
                  <select id="embalaje" class="form-control col-md-3" type="text"></select>
                </div>
               
              </div>


              <!--Radios button-->
              <div class="form-group">
                <label class="control-label col-md-2">Temperatura</label>
                <div class=" col-xs-1" >
                    <label>
                      <input type="radio" name="temperaturaR" Value="Seco" id="Rseco" />Seco <br>
                      <input type="radio" name="temperaturaR" Value="Frio" id="Rfrio" checked />Frio <br>
                    </label>
                </div>
                  <div class="col-xs-4">
                    <label class=" col-xs-2" id="gradosl">Grados:</label>
                    <label class=" col-xs-2" id="temp1" >Cº :</label>
                      <input id="grados" class=" col-xs-1" type="number" style="width:80px" placeholder="Min"></input>
                    <label class="col-xs-3" id="gradosl2">Min - Max</label>
                    <input id="grados2" class=" col-xs-1" type="number" style="width:80px" placeholder="Max"></input>
                  </div>
                <label class="control-label col-md-1">Tipo Servicio</label>
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
</div>
</div>


               <hr style="
    margin-left: auto;
    margin-right: auto;
    border-width:1px;
    border-color: #00a4e3;
           "   >

              <!--Datos Carga -->
               <div class="panel panel-warning">
              <div class="panel-heading">DATOS DE CARGA/DESCARGA</div>
              <div class="panel-body">
              <div class="form-group ">
                 <input type="hidden" value="" id="idcarga"/>
                     <div class="row">
                        <div  class="control-label col-xs-2"><label><big> Datos de Origen</big></label></div>
                        <div class="col-md-2 col-xs-4">
                        <div class=" btn btn-success" href="javascript:void()" style="margin-top: 15px; margin-left: 9px"  onclick="datos_carga()"><i class="glyphicon glyphicon-plus"></i></div>
                        </div>
                      </div>
                      <br>
                      <label class="control-label col-md-2">Carga en:</label>
                      <div class="col-md-5">
                        <input id="cargaC" class="form-control" type="text">
                      </div>
                      <label class="control-label col-md-1">Calle:</label>
                      <div class="col-md-4">
                        <input id="calleC" class="form-control" type="text">
                      </div>
                      <label class="control-label col-md-2">Referencia</label>
                      <div class="col-md-5">
                        <input id="referenciaC" class="form-control" type="text">
                      </div>
                      <label class="control-label col-md-1">Colonia</label>
                      <div class="col-md-4">
                        <input id="coloniaC" class="form-control" type="text">
                      </div>
                      <label class="control-label col-md-2">Estado</label>
                      <div class="col-md-5">
                        <input id="estadoC" class="form-control" type="text">
                      </div>
                      <label class="control-label col-md-1">Ciudad</label>
                      <div class="col-md-4">
                        <input id="ciudadC" class="form-control" type="text">
                      </div>
                      <label class="control-label col-md-2">Preguntar por:</label>
                      <div class="col-md-5">
                        <input id="preguntarC" class="form-control" type="text">
                      </div>
                      <label class="control-label col-md-1">Telofono</label>
                      <div class="col-md-4">
                        <input id="telefonoC" class="form-control" type="text">
                      </div>
                      <label class="control-label col-md-2">Fecha de Carga:</label>
                      <div class="col-md-5">
                        <input id="fechaC" class="form-control"  type="date" value="<?php echo date("Y-m-d"); ?>">
                      </div>
                      <label class="control-label col-md-1">Hora</label>
                      <div class="col-md-2">
                        <input id="horaC" class="form-control" style="width:80px;"type="text" onkeyup="mascara(this,':',patron,true)" maxlength="10"  />
                      </div>
                </div>
                <br>
                 <hr style="
    margin-left: auto;
    margin-right: auto;
    border-width:1px;
    border-color: #ffe060;
           "   >
              <br>
              <!--Datos Entrega-->
              <div>
                <div  class="control-label col-xs-2"><label><big>Datos del Destino</big></label></div>
              </div>
              <div class="form-group">
                <div class="col-md-5">
                  <select id="destinatario" class="form-control" value="Selecciona un destinatario:" style="width:350px;"></select>
                </div>
              </div>
                <div class="form-group">
                  <label class="control-label col-md-2">Contacto</label>
                  <div class="col-md-5">
                    <input id="contacto_des" class="form-control" type="text">
                  </div>
                  <label class="control-label col-md-1 nopadding">Celular</label>
                  <div class="col-md-4">
                    <input id="celular_des" class="form-control" type="text">
                  </div>
                </div>
              <div class="form-group ">
                <input type="hidden" value="" id="identrega"/>
                <div class="row">
                        <div  class="control-label col-xs-2"><label><big>Datos de Entrega</big></label></div>
                        <div class="col-md-2 col-xs-4">
                        <div class=" btn btn-success" href="javascript:void()" style="margin-top: 15px; margin-left: 9px"  onclick="datos_entrega()"><i class="glyphicon glyphicon-plus"></i></div>
                        </div>
                      </div>
                      <br>
                <label class="control-label col-md-2">Entrega en:</label>
                <div class="col-md-5">
                  <input id="cargaE" class="form-control" type="text">
                </div>
                <label class="control-label col-md-1">Calle:</label>
                <div class="col-md-4">
                  <input id="calleE" class="form-control" type="text">
                </div>
                <label class="control-label col-md-2">Referencia:</label>
                <div class="col-md-5">
                  <input id="referenciaE" class="form-control" type="text">
                </div>
                <label class="control-label col-md-1">Colonia:</label>
                <div class="col-md-4">
                  <input id="coloniaE" class="form-control" type="text">
                </div>
                <label class="control-label col-md-2">Estado:</label>
                <div class="col-md-5">
                  <input id="estadoE" class="form-control" type="text">
                </div>
                <label class="control-label col-md-1">Ciudad:</label>
                <div class="col-md-4">
                  <input id="ciudadE" class="form-control" type="text">
                </div>
                <label class="control-label col-md-2">Preguntar por:</label>
                <div class="col-md-5">
                  <input id="preguntarE" class="form-control" type="text">
                </div>
                <label class="control-label col-md-1">Telofono:</label>
                <div class="col-md-4">
                  <input id="telefonoE" class="form-control" type="text">
                </div>
                <label class="control-label col-md-2">Fecha de Entrega:</label>
                  <div class="col-md-5">
                    <input id="fechaE" class="form-control" type="date" value="<?php echo date("Y-m-d"); ?>">
                  </div>
                <label class="control-label col-md-1">Hora</label>
                  <div class="col-md-2">
                    <input id="horaE" class="form-control" style="width:80px;"type="text" onkeyup="mascara(this,':',patron,true)" maxlength="10" />
                  </div>
              </div>
              <br>

                </div>
</div>
            <div class="modal-footer">
            </form>
             <br>   <!--Text areas-->
             <div class="form-group ">
                <div>
                  <label class="control-label col-md-2"><big>Requerimientos</big></label>
                  <div class="col-md-4">
                   <textarea id="requerimientos" class="form-control" rows="2" disabled></textarea>
                  </div>
                </div>
                <div>
                  <label class="control-label col-md-2"><big>Evidencia</big></label>
                  <div class="col-md-4">
                    <textarea id="evidencias" class="form-control" rows="2" disabled></textarea>
                  </div>
                </div>
                <div>
                  <label class="control-label col-md-2"><big>Atencion</big></label>
                  <div class="col-md-4">
                    <textarea id="atencion" class="form-control" rows="2"></textarea>
                  </div>
                </div>
                <div>
                  <label class="control-label col-md-2"><big>Recomendaciones</big></label>
                  <div class="col-md-4">
                   <textarea id="recomendaciones" class="form-control" rows="2"></textarea>
                  </div>
                </div>
              </div>

            </div>
            <br>
            <br>
              <button type="button" id="btnSave" onclick="save_solicitud()" class="btn btn-primary">Guardar</button>
              <button type="button" id="btnUpdate" onclick="send_edit_solicitud()" class="btn btn-default">Actualizar</button>
              <div class="col-md-10"></div>
              <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="restcampos()">Cancelar</button>
            </div>
          </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
      </div><!-- /.modal -->
<!-- Modal Formulario para nueva solicitud FIN-->


<!-- Modal Para DATOS -->
  <div class="modal fade" id="modal_form_datoscarga" data-keyboard="false">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" onclick="hide_datoscarga2()" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h3 class="modal-title">Agregar Datos</h3>
        </div>
  <!-- div Para DATOS CARGA INICIO-->
            <div id="divcarga">
              <div class="form-group">
                <label id="iddatoscargaL" class="control-label col-md-3">Datos Carga</label>
                <div class="col-md-9">
                  <select id="iddatoscarga" class="form-control"></select>
                </div>
              </div>
              <div id="botonescarga" class="botones_carga_entrega">
                <button type="button"  onclick="add_datoscarga()" class="btn btn-success">Agregar</button>
                <button type="button"  onclick="add_lugarcarga()" class="btn btn-primary">Nuevo</button>
                <button type="button"  onclick="hide_datoscarga2()" class="btn btn-danger">Cerrar</button>
            </div>
          </div>
  <!-- div Para DATOS CARGA FIIN-->
  <!-- div Para DATOS ENCARGA INICIO-->
            <div id="diventrega">
              <div class="form-group">
                <label id="iddatosentregaL" class="control-label col-md-3">Datos Entrega</label>
                <div class="col-md-9">
                  <select id="iddatosentrega" class="form-control"></select>
                </div>
              </div>
              <div id="botonesentrega" class="botones_carga_entrega">
                <button type="button"  onclick="add_datosentrega()" class="btn btn-success">Agregar</button>
                <button type="button"  onclick="add_lugarentrega()" class="btn btn-primary">Nuevo</button>
                <button type="button"  onclick="hide_datoscarga2()" class="btn btn-danger">Cerrar</button>
              </div>
            </div>
  <!-- div Para DATOS ENCARGA FIIN-->
          </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
      </div><!-- /.modal -->
<!-- Modal Para LUGAR DE CARGA INICIO-->
<div class="modal" id="myModal" data-keyboard="false">
  <div class="modal-dialog" id="modal_tamano2">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" onclick="hide_datoscarga3()" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Modal title</h4>
        </div><div class="container"></div>
        <div class="modal-body">

                    <label class="control-label col-md-2">Carga en:</label>
                    <div class="col-md-5">
                      <input id="cargaC1" class="form-control" type="text">
                    </div>
                    <label class="control-label col-md-1">Calle:</label>
                    <div class="col-md-4">
                      <input id="calleC1" class="form-control" type="text">
                    </div>
                    <label class="control-label col-md-2">Estado</label>
                    <div class="col-md-5">
                      <select id="estadoC1" class="form-control"></select>
                    </div>
                    <label class="control-label col-md-1">Ciudad</label>
                    <div class="col-md-4">
                      <select id="ciudadC1" class="form-control"></select>
                    </div>
                    <label class="control-label col-md-2">Referencia</label>
                    <div class="col-md-5">
                      <input id="referenciaC1" class="form-control" type="text">
                    </div>
                    <label class="control-label col-md-1">Colonia</label>
                    <div class="col-md-4">
                      <input id="coloniaC1" class="form-control" type="text">
                    </div>
                    <label class="control-label col-md-2">Preguntar por:</label>
                    <div class="col-md-5">
                      <input id="preguntarC1" class="form-control" type="text">
                    </div>
                    <label class="control-label col-md-1">Telofono</label>
                    <div class="col-md-4">
                      <input id="telefonoC1" class="form-control" type="text">
                    </div>
                    <br>
        </div>
        <div class="modal-footer">

          <a href="#" class="btn btn-primary" onclick="guardar_lugarcarga()">Guardar</a>
          <button type="button"  onclick="hide_datoscarga3()" class="btn btn-danger">Cerrar</button>
        </div>
      </div>
    </div>
</div>
<!-- Modal Para LUGAR DE CARGA FIN-->

<!-- Modal Para LUGAR DE ENTREGA INICIO-->
<div class="modal" id="myModal1" data-keyboard="false">
  <div class="modal-dialog" id="modal_tamano2">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" onclick="hide_datoscarga4()" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Modal title</h4>
        </div><div class="container"></div>
        <div class="modal-body">

                    <label class="control-label col-md-2">Entrega en</label>
                    <div class="col-md-5">
                      <input id="cargaE1" class="form-control" type="text">
                    </div>
                    <label class="control-label col-md-1">Calle:</label>
                    <div class="col-md-4">
                      <input id="calleE1" class="form-control" type="text">
                    </div>
                    <label class="control-label col-md-2">Estado</label>
                    <div class="col-md-5">
                      <select id="estadoE1" class="form-control"></select>
                    </div>
                    <label class="control-label col-md-1">Ciudad</label>
                    <div class="col-md-4">
                       <select id="ciudadE1" class="form-control"></select>
                    </div>
                    <label class="control-label col-md-2">Referencia</label>
                    <div class="col-md-5">
                    <input id="referenciaE1" class="form-control" type="text">
                    </div>
                    <label class="control-label col-md-1">Colonia</label>
                    <div class="col-md-4">
                      <input id="coloniaE1" class="form-control" type="text">
                    </div>
                    <label class="control-label col-md-2">Preguntar por:</label>
                    <div class="col-md-5">
                      <input id="preguntarE1" class="form-control" type="text">
                    </div>
                    <label class="control-label col-md-1">Telofono</label>
                    <div class="col-md-4">
                      <input id="telefonoE1" class="form-control" type="text">
                    </div>



        <div class="modal-footer">

          <button href="#" class="btn btn-primary" onclick="guardar_lugarentrega()">Guardar</button>
          <button type="button"  onclick="hide_datoscarga4()" class="btn btn-danger">Cerrar</button>
        </div>
      </div>
    </div>
</div>
</div>
<!-- Modal Para LUGAR DE ENTREGA FIN-->


<!-- Modal Formulario para nueva solicitud INICIO-->
      <div class="modal" id="modal_form_sol_edit" >
        <div class="modal-dialog">
          <div class="modal-content">
              <h1>jnsd</h1>
              <input id="nada" class="form-control" type="text">
          </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
      </div><!-- /.modal -->
<!-- Modal Formulario para nueva solicitud FIN-->


  <script>

    //inicializacion select 2 todos los input select 0003367
    //
    
    $('#cliente').select2();
    $('#destinatario').select2();
    $('#estadoE1').select2();
    $('#ciudadE1').select2();
    $('#estadoC1').select2();
    $('#ciudadC1').select2();
    $('#tipocarga').select2();
    $('#capacidad').select2();
    $('#desccorta').select2();
    $('#cap').select2();
    $('#ciu').select2();
    $('#est').select2();
    $('#idope').select2();
    $('#iduni').select2();
    $('#idcaja').select2();
    $('#est_opeNew').select2();
    $('#ciu_opeNew').select2();
    $('#tipounidad').select2();
    $('#tipogas').select2();
    $('#marca_new').select2();
    $('#capacidad_new').select2();
    $('#ciu_opeNew').select2();
    $('#tipocaja_new').select2();  
    $('#fechaAgree').datepicker({ format:'yyyy-mm-dd', "autoclose": true }).attr('readonly','readonly');
    $('#fecha_newuni').datepicker({ format:'yyyy-mm-dd', "autoclose": true }).attr('readonly','readonly');
    $('#marcaUni').select2();
      $('#tipoUni').select2();
      $('#capUni').select2();
      $('#tipocombustibleUni').select2();

    //inicializacion select 2 catalogo embalaje 0003500
    $('#embalaje').select2();
    

  

 </script>




