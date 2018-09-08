<style>

#modal_tamano{
  width: 90% !important;
}

  .tabla-anticipos th {
padding: 10px;
font-size: 13px;
background-color: #332a24;
color: #fff;
border-right-width: 1px;
border-bottom-width: 1px;
border-right-style: solid;
border-bottom-style: solid;
border-right-color: #332a24;
border-bottom-color: #332a24;
font-family: “Trebuchet MS”, Arial;
text-transform: uppercase;
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
  <script src="js/unidades.js"></script>
<div class="container">
    <h1>Alta de Unidades</h1>
    <hr style=" margin-right:1px; border-width:3px; border-color: #332a24;">
    <button onclick="addUni()" class="btn btn-lg btn-success"><i class="glyphicon glyphicon-plus-sign"></i> Agregar Nueva Unidad</button>
    <hr>
    <table id="table_listado_unidades" class="table table-striped table-bordered tabla-anticipos" cellspacing="0" width="100%">
      <thead>
        <tr>
            <th>ID</th>
            <th>Marca/Modelo</th>
            <th>No.Economico</th>
            <th>Placas</th>
            <th>Año</th>
            <th>Color</th>
            <th>Combustible</th>
            <th>Acciones</th>
        </tr>
      </thead>
    <tbody>
    </tbody>

      </table>
  </div>




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



 <div class="modal fade" id="modal_form_infoUni" data-keyboard="false">
    <div class="modal-dialog modal-lg" id="modal_tamano">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title_sol">Informacion de  Unidad </h4>
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
                                      <td width="10%"><input class="form-control" id="noEconomicoInfo" type="text" placeholder="XXX-123-134 , EC-123 , etc..."></td>
                                  </tr>
                                   <tr>
                                    <td>Marca:</td>
                                      <td colspan=""><select id="marcaUniInfo" class="form-control" style="width: 350px;"></select></td>
                                      <td align="right">Modelo:</td>
                                      <td colspan="1"><input class="form-control" id="modeloUniInfo" type="text" placeholder="Acros slt , Transit Custom , etc..."></td> 
                                      <td align="right">Año:</td>
                                      <td colspan="1"><input class="form-control" id="anioUniInfo" type="text" placeholder="2010,2012,etc.."></td>      
                                  </tr>
                                  <tr>
                                      <td>Placas:</td>
                                      <td colspan="1"><input class="form-control" id="placasUniInfo" type="text" placeholder="AX-241, FDE-234-2, etc..."></td>
                                      <td></td>
                                       <td>Color:</td>
                                      <td colspan="1"><input class="form-control" id="colorUniInfo" type="text" placeholder="Azul , Negro , etc..."></td>
                                      <td></td>
                                      <td></td>
                                  </tr>
                                 
                                  <tr>
                                      <td>Tipo de Unidad:</td>
                                      <td colspan="2">  <select id="tipoUniInfo" class="form-control" style="width: 350px;"></select></td>
                                      <td>Capacidad de la unidad:</td>
                                      <td><select id="capUniInfo" class="form-control" style="width: 200px;"></select></td>
                                      <td></td>
                                      <td></td>
                                  </tr>
                                  <tr>
                                      <td>Tipo de Combustible:</td>
                                      <td colspan="3">  <select id="tipocombustibleUniInfo" class="form-control" style="width: 350px;"></select></td>
                                    
                                  </tr>
                                  <tr>
                                      <td>Tamaño del Tanque (lts.):</td>
                                      <td colspan="1"><input class="form-control" id="tamanotanqUniInfo" type="number"></td>
                                      <td>Rendimiento Foraneo (km/l):</td>
                                      <td colspan="1"><input class="form-control" id="rendforUniInfo" type="number"></td>
                                      <td>Rendimiento Local (km/l):</td>
                                      <td colspan="1"><input class="form-control" id="rendlocUniInfo" type="number"></td>
                                      <td></td>
                                      <td></td>
                                  </tr>
                                  <tr>
                                      <td>Refrigerado:</td>
                                      <td><input type="radio"  id="refriUniInfo" value="1" ><label>SI</label></td>
                                      <td><input type="radio" value="0" id="refriUniNoInfo"><label>NO</label></td>
                                  </tr>
                                  <tr id="thermotable">
                                    <td>Tamaño del Tanque del Thermo (lts):</td>
                                    <td><input type="number" id="tamtanqthermoInfo"></td>
                                    <td>Rendmiento Foraneo (km/l):</td>
                                    <td><input type="number" id="rendthermforInfo"></td>
                                    <td>Rendimiento Local (km/l):</td>
                                    <td><input type="number" id="rendthermlocInfo"></td>  
                                  </tr>
                                  <tr>
                                    <td>Fecha de Adquisicion:</td>
                                    <td><input id="fechaaddUniInfo" class="form-control" type="" value=""></td>
                                    <td>Kilometros a la Adquisicion:</td>
                                    <td><input type="number" id="kmadquisicionInfo"></td>
                                    <td>Kilometros Totales:</td>
                                    <td><input type="number" id="kmtotalInfo"></td>
                                  </tr>
                                  <tr>

                                  </tr>

                                </tbody>
                              </table>
                              <div>
                                 <div><h5>Observaciones de la Unidad:</h5></div>
                                    <textarea id="observacionesInfo" rows="6" cols="100"></textarea>
                              </div>



          </form>
       </div>
          <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"> Cerrar</button>
               
                
          </div>

       </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
</div><!-- /.modal -->









    <script> 
      $('#marcaUni').select2();
      $('#tipoUni').select2();
      $('#capUni').select2();
      $('#tipocombustibleUni').select2();



       $(document).ready(function(){
        reloadtable(); })
    </script>