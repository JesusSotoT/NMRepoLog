<style>
#modal_tamano{
  width: 90% !important;
}  
#modal_tamano2{
  width: 70% !important;
} 
#divreporteador{
  width: 80% !important;
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
     border-top: none !important; 
 }
.row tr {
width: 15px; /*Aquí va el ancho de la Celda*/
height: 10px; /*Aquí el Alto*/
}
table.table.table-condensed {
    border: 1px solid black;
}
.center {
    margin: auto;
    width: 60%;
}
.hidden {
  visibility: hidden;
}

.tabla-pantalla th {
padding: 10px;
font-size: 13px;
background-color: #EEA82A;
color: #fff;
border-right-width: 1px;
border-bottom-width: 1px;
border-right-style: solid;
border-bottom-style: solid;
border-right-color: #558FA6;
border-bottom-color: #558FA6;
font-family: “Trebuchet MS”, Arial;
text-transform: uppercase;
width: 120%;
}



.tabla-reportes th {
padding: 10px;
font-size: 13px;
background-color: #c3bfbb;
color: #fff;
border-right-width: 1px;
border-bottom-width: 1px;
border-right-style: solid;
border-bottom-style: solid;
border-right-color: #558FA6;
border-bottom-color: #558FA6;
font-family: “Trebuchet MS”, Arial;
text-transform: uppercase;
width: 120%;
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
  <!-- <script src="js/dataTables.bootstrap.min.js" type="text/javascript"></script>-->
  <script src="js/datatables.min.js" type="text/javascript"></script>
  <script src="js/bootstrap-datepicker.min.js" type="text/javascript"></script>
  <script src="js/pantallaelectronica.js" type="text/javascript"></script>

  <div class="container">
    <h1>Pantalla Electronica</h1>
      <hr style=" 
    margin-left: auto;
    margin-right: auto;
    border-width:3px;
    border-color: #EEA82A;
           ""   >
<div class="form-group">
    <div class="row">
        <div class="panel panel-warning">
            <div class="panel-body">
              
                <div class="col-xs-2">
                    <button class="btn btn-lg btn-primary" onclick="pantallaElectronica()"><i class="glyphicon glyphicon-tower"></i> POR UNIDAD </button>
                    </div>
                    <div class="col-xs-2">
                    <button class="btn btn-lg btn-info" onclick="pantallaElectronica(3)"><i class="glyphicon glyphicon-certificate"></i> DISPONIBLES </button>
                    </div>

                    <div class="col-xs-3">
                    <button class="btn btn-lg btn-warning" onclick="pantallaElectronica(2)"><i class="glyphicon glyphicon-send"></i> EN VIAJE </button>
                    </div>  
                     <div class="col-xs-3">
                    <button class="btn btn-lg btn-danger" onclick="pantallaElectronica(4)"><i class="glyphicon glyphicon-wrench"></i> MANTENIMIENTO </button>
                    </div>
                      <div class="col-xs-2">
                    <button class="btn btn-lg btn-success" onclick="pantallaHistorico(5)"><i class="glyphicon glyphicon-list"></i> HISTORICO </button>
                    </div>
               </div>
         </div>
    </div> 
</div>
  

    <div class="form-group center panel-default" id="divreporteador">
            <label class="control-label col-md-2">Fecha Inicio</label>
            <div class="col-md-4">
              <input id="fechainicio" class="form-control" type="text">
            </div>
            <label class="control-label col-md-2">Fecha Final</label>
            <div class="col-md-4">
              <input id="fechafinal" class="form-control" type="text">
            </div><br><br><br><br>
            <label class="control-label col-md-1">Cliente</label>
            <div class="col-md-5">
              <select id="idclie" class="form-control">
              </select>
            </div>
            <label class="control-label col-md-1">Operdador</label>
            <div class="col-md-5">
              <select id="idope" class="form-control">
              </select>
            </div><br><br><br><br>
            <label class="control-label col-md-1">Destino</label>
            <div class="col-md-5">
              <select id="iddes" class="form-control">
              </select>
            </div>
            <label class="control-label col-md-1">Unidad</label>
            <div class="col-md-5">
              <select id="iduni" class="form-control">
              </select>
            </div><br><br><br><br>
            <label class="control-label col-md-1">Capacidad</label>
            <div class="col-md-5">
              <select id="idcap" class="form-control">
              </select>
            </div><br><br><br><br>
            <div class="col-md-22 center">
              <button class="btn btn-info col-md-10 " onclick ="buscar()">Buscar</button>
            </div><br>        
    </div>

    <div id="tablaPantalla">
      <table id="table_listaPantalla" class="table-responsive  table-bordered table-condensed tabla-pantalla" cellspacing="5" width="100%">
        <thead>
          <tr id="trpantalla">
            <th>Unidad</th>
            <th>Capacidad</th>
            <th>Estatus Unidad</th>
            <th>OS</th>
            <th>Operador</th>
            <th>Destino / Destinatario</th>
            <th>Cliente</th>
            <th>Solicitud</th>
            <th>Asignacion</th>
            <th>Reportes</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
    </div>

    <div id="tablaHistoria">
      <table id="table_listaHistoria" class="table table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
          <tr id="trpantalla">
            <th>Fecha</th>
            <th>OS</th>
            <th>Cliente</th>
            <th>Operador</th>
            <th>Ciudad Origen</th>
            <th>Ciudad Destino</th>
            <th>Reportes</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
    </div>
  </div>
</body>


<div class="modal fade" id="modal_form_reportes" data-keyboard="false">
    <div class="modal-dialog" id="modal_tamano">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h3 class="modal-title_sol">Pantalla Electronica - Reportes</h3>

          <div class="container" id="modal_tamano">

          <div class="form-group">
              <label class="control-label col-md-2">Orden de Servicio</label>
              <div class="col-md-1">
                <input id="idordenservicio" class="form-control" type="text">
              </div>
              <label class="control-label col-md-1">Operador</label>
              <div class="col-md-4">
                <input id="operador" class="form-control" type="text">
              </div>
              <label class="control-label col-md-1">Destino</label>
              <div class="col-md-3">
                <input id="destino" class="form-control" type="text">
              </div>
          </div>

                <table id="table_listaReportes" class="table table-striped tabla-reportes table-bordered" cellspacing="0" width="100%">
                <thead>
                  <tr id="trpantalla">
                    <th>Num. Reporte</th>
                    <th>OS</th>
                    <th>Operador</th>                    
                    <th>Destino / Destinatario</th>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Kilometros</th>
                    <th>Ubicacion</th>
                    <th>Observaciones</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
          </div>

        </div>
     </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="modal_form_reporte" data-keyboard="false">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h3 class="modal-title_sol">Pantalla Electronica - Reporte</h3>
        </div>

          <div class="modal-body form">
            <form action="#" id="form_reporte" class="form-horizontal">
              <div class="form-body">
                <div class="form-group">
                  <label class="control-label col-md-3">Orden de Servicio</label>
                  <div class="col-md-9">
                   <input id="idordenservicio1" class="form-control" type="text">
                  </div>
                </div>
                <div class="form-group">
                  <label class="control-label col-md-3">Operador</label>
                  <div class="col-md-9">
                    <input id="operador1" class="form-control" type="text">
                  </div>
                </div>
                <div class="form-group">
                  <label class="control-label col-md-3">Cliente</label>
                  <div class="col-md-9">
                    <input id="cliente1" class="form-control" type="text">
                  </div>
                </div>
                <div class="form-group">
                  <label class="control-label col-md-3">Destino</label>
                  <div class="col-md-9">
                    <input id="destino1" class="form-control" type="text">
                  </div>
                </div>
                
                <div class="form-group">
                  <label class="control-label col-md-3">Fecha</label>
                  <div class="col-md-9">
                    <input id="fecha" class="form-control" type="text">
                  </div>
                </div>

                <div class="form-group">
                  <label class="control-label col-md-3">Hora</label>
                  <div class="col-md-9">
                    <input id="hora" class="form-control" type="text" onkeyup="mascara(this,':',patron,true)">
                  </div>
                </div>
                <div class="form-group">
                  <label class="control-label col-md-3">Kilometros</label>
                  <div class="col-md-9">
                    <input id="km" class="form-control" type="text">
                  </div>
                </div>
                <div class="form-group">
                  <label class="control-label col-md-3">Lugar de ubicacion</label>
                  <div class="col-md-9">
                    <input id="ubicacion" class="form-control" type="text">
                  </div>
                </div>
                <div class="form-group">
                  <label class="control-label col-md-3">Observaciones</label>
                  <div class="col-md-9">
                    <textarea id="observ" class="form-control" rows="2"></textarea>
                  </div>
                </div>
                <div class="form-group">
                  <label class="control-label col-md-3">Servicio Finalizado</label>
                  <div class="col-md-1">
                    <input type="checkbox" id="estatus"  value="FINALIZADO">
                  </div>
                </div>
              </div>
            </form>
          </div>

          <div class="modal-footer">
            <button type="button" onclick="add_reporte()" class="btn btn-primary">Agregar</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
          </div>

          </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script>

    $( document ).ready(function() {
    $("#divreporteador").hide();
    $("#tablaPantalla").hide();
    $("#tablaHistoria").hide();
    });
    $("#idclie").select2({placeholder: 'Selecciona un cliente', allowClear: true});
     $("#idope").select2({placeholder: 'Selecciona un operador', allowClear: true});
      $("#iddes").select2({placeholder: 'Selecciona un destino', allowClear: true});
       $("#iduni").select2({placeholder: 'Selecciona una unidad', allowClear: true});
        $("#idcap").select2({placeholder: 'Selecciona una capacidad', allowClear: true});
        

</script>

