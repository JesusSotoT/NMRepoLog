<style>


  .glyphicon.glyphicon-oil {
    font-size: 65px;
}
  .glyphicon.glyphicon-scale {
    font-size: 55px;
}
      .glyphicon-refresh-animate {
    -animation: spin .7s infinite linear;
    -ms-animation: spin .7s infinite linear;
    -webkit-animation: spinw .7s infinite linear;
    -moz-animation: spinm .7s infinite linear;
}
@keyframes spin {
    from { transform: scale(1) rotate(0deg);}
    to { transform: scale(1) rotate(360deg);}
}
  
@-webkit-keyframes spinw {
    from { -webkit-transform: rotate(0deg);}
    to { -webkit-transform: rotate(360deg);}
}

@-moz-keyframes spinm {
    from { -moz-transform: rotate(0deg);}
    to { -moz-transform: rotate(360deg);}
}

.tabla-liquidaciones th {
padding: 10px;
font-size: 11.1px;
background-color: #8b0000;
color: #fff;
border-right-width: 1px;
border-bottom-width: 1px;
border-right-style: solid;
border-bottom-style: solid;
border-right-color: #000000;
border-bottom-color: #000000;
font-family: “Trebuchet MS”, Arial;
text-transform: uppercase;
}

.button-princ:hover {
 background: rgba(0,0,0,0);
 color: #3a7999;
 box-shadow: inset 0 0 0 3px #3a7999;
}

.tabla-anticipos th {
padding: 10px;
font-size: 11.1px;
background-color: #c1cdcd;
color: #fff;
border-right-width: 1px;
border-bottom-width: 1px;
border-right-style: solid;
border-bottom-style: solid;
border-right-color: #000000;
border-bottom-color: #000000;
font-family: “Trebuchet MS”, Arial;
text-transform: uppercase;
}

.tabla-listas th {
padding: 8px;
font-size: 11px;
background-color: #cccccc;
color: #fff;
border-right-style:  hidden;
border-bottom-style: hidden;
border-top-style: hidden;
border-left-style: hidden;
border-right-color: #cccccc;
border-bottom-color: #cccccc;
font-family: “Trebuchet MS”, Arial;
text-transform: uppercase;
}

.tabla-gastos th {
padding: 8px;
font-size: 11px;
color: #fff;
border-right-style:  hidden;
border-bottom-style: hidden;
border-top-style: hidden;
border-left-style: hidden;
border-right-color: #cccccc;
border-bottom-color: #cccccc;
font-family: “Trebuchet MS”, Arial;
text-transform: uppercase;
}

.tabla-kms th {
border-right-style:  hidden;
border-bottom-style: hidden;
border-top-style: hidden;
border-left-style: hidden;

}


.tabla-listas2 th {
font-size: 10px;
background-color: ;
color: black;
border-right-style: solid;
border-bottom-style: solid;
border-top-style: solid;
border-left-style: solid;

font-family: “Trebuchet MS”, Arial;
text-transform: uppercase;
}

.tabla-listas2 tr {
font-size: 10px;
color: black;
border-right-width: 0.5px;
border-left-width: 0.5px;
border-top-width: 0.5px;
border-bottom-width: 0.5px;
border-right-style: solid;
border-bottom-style: solid;
border-top-style: solid;
border-left-style: solid;

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
 <script src="js/dataTables.bootstrap.min.js" type="text/javascript"></script>
  <script src="js/datatables.min.js" type="text/javascript"></script>
  <script src="js/bootstrap-datepicker.min.js" type="text/javascript"></script>
  <script src="js/liquidaciones.js" type="text/javascript"></script>
  


  <div class="container divCenter text-center panel panel-default">
    <h2>LIQUIDACION DE ORDENES DE SERVICIO</h2><br/>
 <hr style=" 
   
    border-width: 3px;
    border-color: #8b0000;
    width: 100%;
           ""   >
      <div id = "divadd" class=" divCenter text-center panel panel-default">
        <h4>Lista Orden de Servicio</h4>
        <div class="">
                <table id="table_listado" class="table tabla-liquidaciones table-bordered" cellspacing="0" >
                <thead>
                  <tr>
                    <th>Fecha</th>
                    <th>OS</th>
                    <th width="3%">Unidad</th>
                    <th>Ruta</th>
                    <th width="30%">Cliente</th>
                    <th width="10%">Operador</th>
                    <th width="30%">Ciudad Origen</th>
                    <th width="30%">Ciudad Destino</th>
                    <th>Liquidar</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
          </div>
      </div>
      <div id = "divliquidar" class=" divCenter text-center panel panel-default">
        <h4>Liquidacion de Viaje</h4>
        <form class="form-horizontal">
                <div class="form-body">
                  <div class="form-group">
                    <label class="control-label col-md-2">Operador</label>
                    <div class="col-md-2">
                      <input id="operador" class="form-control col-md-2" type="text" style="text-transform: uppercase; width: 300px;">
                    </div>
                    <label class="control-label col-md-2">Unidad</label>
                    <div class="col-md-2">
                      <input id="unidad" class="form-control col-md-2" type="text" style="text-transform: uppercase;">
                    </div>
                    <label class="control-label col-md-2">Orden de Servicio</label>
                    <div class="col-md-2">
                      <input id="idsolicitud" class="form-control" type="" value="0" style="text-transform: uppercase;">
                      <input id="idordenservicio" class="form-control col-md-2" type="" style="text-transform: uppercase;">
                    </div>
                  </div>
                  
                  <div class="form-group">
                    <label class="control-label col-md-2">Cliente</label>
                    <div class="col-md-2">
                      <input id="cliente" class="form-control col-md-4" type="text" style="text-transform: uppercase; width: 300px;">
                      <input  class="form-control col-md-4" type="" id="idcliente" style="text-transform: uppercase; width: 300px;">
                    </div>
                    <label class="control-label col-md-2">Fecha</label>
                    <div class="col-md-2">
                      <input id="fecha" class="form-control col-md-2" type="text" style="text-transform: uppercase;">
                    </div>
                    <div class="col-md-4">
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="control-label col-md-2">Remite</label>
                    <div class="col-md-2">
                      <input id="remite" class="form-control col-md-2" type="text" style="text-transform: uppercase;">
                    </div>
                    <label class="control-label col-md-2">Origen</label>
                    <div class="col-md-2">
                      <input id="origen" class="form-control col-md-2" type="text" style="text-transform: uppercase; width: 300px;">
                    </div>
                    <div class="col-md-4">
                    </div>
                  </div>

                 <div class="form-group">
                    <label class="control-label col-md-2">Destinatario</label>
                    <div class="col-md-2">
                      <input id="destintario" class="form-control col-md-2" type="text" style="text-transform: uppercase;">
                    </div>
                    <label class="control-label col-md-2">Destino</label>
                    <div class="col-md-2">
                      <input id="destino" class="form-control col-md-2" type="text" style="text-transform: uppercase; width: 300px;">
                    </div>
                    <div class="col-md-4">
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="control-label col-md-2">Temperatura</label>
                    <div class="col-md-2">
                      <input id="temperatura" class="form-control col-md-2" type="text" style="text-transform: uppercase;">
                    </div>
                    <label class="control-label col-md-2">Capacidad</label>
                    <div class="col-md-2">
                      <input id="capacidad" class="form-control col-md-2" type="text" style="text-transform: uppercase;">
                    </div>
                    <div class="col-md-4">
                    </div>
                  </div>
              
                    

                  <div id = "divgas" class="divCenter text-center panel panel-default col-md-12" >
                    
                      <div class="col-md-1"></div>
                      <div class="col-md-1"><label class="control-label col-md-12">KM. Inicial</label></div>
                      <div class="col-md-1"><input id="kminicial" class="form-control" type="text" onblur="calculos()"></div>
                      <div class="col-md-1"><label class="control-label col-md-2">KM. Descarga</label></div>
                      <div class="col-md-1"><input id="kmdescarga" class="form-control" type="text" onblur="calculos()"></div>
                      <div class="col-md-1"><label class="control-label col-md-2">KM. Final</label></div>
                      <div class="col-md-1"><input id="kmfinal" class="form-control" type="text" onblur="calculos()"></div>
                      <div class="col-md-1"><label class="control-label col-md-2">HRS. Inicial</label></div>
                      <div class="col-md-1"><input id="hrinicial" class="form-control" type="text" onblur="calculos()" disabled></div>
                      <div class="col-md-1"><label class="control-label col-md-2">HRS. Final</label></div>
                      <div class="col-md-2"><input id="hrfinal" class="form-control" type="text" onblur="calculos()" disabled></div>
                      <div class="col-md-1"></div>

                      <!--<div class="col-md-12"> nueva linea</div>-->

                      <table class="tabla-kms table" width ="100%" border ="0" style="background-color: #810e0e; color: white;">
                   <!-- 
                        <td width ="15%" colspan = "1"><label class="control-label"></label></td>
                        <td width ="15%" colspan = "1"><label class="control-label"></label></td>
                        <td width ="15%" colspan = "1"><label class="control-label"></label></td>
                        <td width ="20%" colspan = "1"><label class="control-label"></label></td>
                        <td width ="20%" colspan = "1"><label class="control-label"></label></td>
                      
                     <tr>
                        <td><i class="glyphicon glyphicon-oil"></i></td>
                        <td><select id="cmbinicial" class="" hidden="true">
                          <option value="1/8">1/8</option>
                          <option value="1/8">2/8</option>
                          <option value="1/8">3/8</option>
                          <option value="1/8">4/8</option>
                          <option value="1/8">5/8</option>
                          <option value="1/8">6/8</option>
                          <option value="1/8">7/8</option>
                          <option value="1/8">8/8</option>
                        </select></td>
                        <td><i class="glyphicon glyphicon-oil" hidden=""></i></td>
                        <td><select id="cmbdescarga" class="">
                          <option value="1/8">1/8</option>
                          <option value="1/8">2/8</option>
                          <option value="1/8">3/8</option>
                          <option value="1/8">4/8</option>
                          <option value="1/8">5/8</option>
                          <option value="1/8">6/8</option>
                          <option value="1/8">7/8</option>
                          <option value="1/8">8/8</option>
                        </select></td>
                        <td><i class="glyphicon glyphicon-oil" hidden="true"></i></td>
                        <td><select id="cmbfinal" class="">
                          <option value="1/8">1/8</option>
                          <option value="1/8">2/8</option>
                          <option value="1/8">3/8</option>
                          <option value="1/8">4/8</option>
                          <option value="1/8">5/8</option>
                          <option value="1/8">6/8</option>
                          <option value="1/8">7/8</option>
                          <option value="1/8">8/8</option>
                        </select></td>
                        <td><i class="glyphicon glyphicon-scale" hidden="true"></i></td>
                        <td><select id="cmbThermoinicial" class="">
                          <option value="1/8">1/8</option>
                          <option value="1/8">2/8</option>
                          <option value="1/8">3/8</option>
                          <option value="1/8">4/8</option>
                          <option value="1/8">5/8</option>
                          <option value="1/8">6/8</option>
                          <option value="1/8">7/8</option>
                          <option value="1/8">8/8</option>
                        </select></td>
                        <td><i class="glyphicon glyphicon-scale" hidden="true"></i></td>
                        <td><select id="cmbThermofinal" class="">
                          <option value="1/8">1/8</option>
                          <option value="1/8">2/8</option>
                          <option value="1/8">3/8</option>
                          <option value="1/8">4/8</option>
                          <option value="1/8">5/8</option>
                          <option value="1/8">6/8</option>
                          <option value="1/8">7/8</option>
                          <option value="1/8">8/8</option>
                        </select></td>
                      </tr>    -->
                      
                            <tr >
                        <td><label class="control-label col-md-12">KM. Recorridos</label></td>
                        <td><input class ="read" id="kmrecorridos" class="form-control" type="text" style="color: black;"></td>
                        <td><label class="control-label col-md-2">KM. Cargado</label></td>
                        <td><input class ="read" id="kmcargado" class="form-control" type="text" style="color: black;"></td>
                        <td><label class="control-label col-md-2">KM. Vacio</label></td>
                        <td><input class ="read" id="kmvacio" class="form-control" type="text" style="color: black;"></td>
                        
                        <td><label class="control-label col-md-2">HRS. Total</label></td>
                        <td><input id="hrtotal" class="form-control" type="text" disabled></td>
                    </tr>
                    </table>
                    </div>
        </form> 
      </div> 
         <table class="table" width = "50%">
                      <tr>

                          <td width = "50%">
                                <table class="table tabla-anticipos" id="tablaAnticipos" width = "20%" border="1">
                                  <thead>
                                    <tr><h3>Anticipos Gastos Viaje</h3>  <button class="btn btn-info" style="width: 500px; background-color: #33c5ff;" onclick="addAnticipos()" id="btnAnt">Agregar Nuevo Anticipo</button>
                                      <th>Referencia</th> 
                                      <th>Fecha</th>
                                      <th>Importe</th>
                                    </tr>
                                  </thead>
                                  <tfoot>
                                    <th style="text-align: center;">TOTAL</th>
                                    <th style="text-align: center;">$</th>
                                    <th style="text-align: center; font-style: inherit; background-color: green; color: white;" id="sumaAnticipos"></th>
                                  </tfoot>
                                </table>
                          </td>
                        </tr>
                      </table>
                    <table class="table" width = "100%">
                      <tr>
                          <td width = "90%">
                                <table class="table" width = "100%">
                                    <tr colspan= "4"><h3> Comprobación de Gastos</h3>
                                     <table class="table" border="2" width = "100%">
                                       <tr>   
                                                   <td>
                                                    <table class="tddel tabla-listas" width="100%">
                                                     <tr>
                                                       <th colspan= "2" width = "100%"> COMBUSTIBLE TARJETA <input id="countCMBTarg" class="form-control" type="hidden" value="0"></th>
                                                     </tr>
                                                     <tr>
                                                       <th> No. Vale  : </th>
                                                       <th><input id="inpNoValeCMBTarg" class="form-control" type="text"></th>
                                                     </tr>
                                                     <tr>
                                                       <th> Cantidad : </th>
                                                       <th><div class="col-md-1"></div><input id="inpCantCMBTarg" class="form-control" type="text"></th>
                                                     </tr>
                                                     <tr>
                                                       <th> Costo x Litro : </th>
                                                       <th><div class="col-md-1"></div><input id="inpCostlitTarg" class="form-control" type="text"></th>
                                                     </tr>
                                                     <tr>
                                                       <th colspan= "2" width = "100%"><button class="btn btn-warning" onclick="addCMBTarg()">Grabar</button></th>
                                                     </tr>
                                                   </table>
                                                  </td>

                                                  <td width="20%"> 
                                                      <table class="table tabla-listas2" id ="tablaCMBTarg" border="1" width = "40%">
                                                        <thead>
                                                          <tr>
                                                           <th class="thnext tabla-listas" colspan= "3" width = "40%">COMBUSTIBLE TARJETA </th>
                                                         </tr>
                                                          <tr> 
                                                            <th>No. Vale</th>
                                                            <th>Cantidad</th>
                                                            <th>Costo x Lt</th>
                                                            <th>Litros</th>
                                                          </tr>
                                                        </thead>
                                                        <tbody><tr></tr></tbody>
                                                        <tfoot>
                                                         <tr>
                                                            <th>Suma/prom.</th>
                                                            <th  style="background-color: green; color: white; text-align: center; font-style: inherit; " id="sumaCMBTarg">$ 0</th>
                                                            <th id="promedioCMBTarj">Promedio</th>
                                                            <th id="sumalit">0 lts.</th>
                                                          </tr>
                                                        </tfoot>
                                                    </table>
                                                  </td>

                                                  <td width="30%">
                                                    <table class="tddel tabla-listas">
                                                     <tr>
                                                       <th colspan= "2" width = "100%">CMB THERMO POR TARJETA <input id="countCMBThermoTarg" class="form-control" type="hidden" value="0"></th>
                                                     </tr>
                                                     <tr>
                                                       <th>No. Vale : </th>
                                                       <th><input id="inpNoValeCMBThermoTarg" class="form-control" type="text"></th>
                                                     </tr>
                                                     <tr>
                                                       <th>Cantidad : </th>
                                                       <th><input id="inpCantCMBThermoTarg" class="form-control" type="text"></th>
                                                     </tr>
                                                     <tr>
                                                       <th> Costo x Litro : </th>
                                                       <th><div class="col-md-1"></div><input id="inpCostlitTargTher" class="form-control" type="text"></th>
                                                     </tr>
                                                     <tr>
                                                       <th colspan= "2" width = "100%"><button class="btn btn-warning" onclick="addCMBThermoTarg()">Grabar</button></th>
                                                     </tr>
                                                   </table>
                                                  </td>

                                                  <td width="20%">
                                                    <table class="tabla-listas2 table" id="tablaCMBThermoTarg" border="1" width = "80%">
                                                        <thead>
                                                          <tr>
                                                            <th class="thnext" colspan= "3" width = "80%">CMB THERMO POR TARJETA</th>
                                                          </tr>
                                                          <tr> 
                                                            <th>No. Vale </th>
                                                            <th>Cantidad </th>
                                                            <th>Costo x Lt</th>
                                                            <th>Litros</th>
                                                          </tr>
                                                        </thead>
                                                        <tbody><tr></tr></tbody>
                                                        <tfoot>
                                                         <tr>
                                                            <th>Suma/prom.</th>
                                                            <th style="background-color: green; color: white; text-align: center; font-style: inherit; " id="sumaCMBThermoTarg">$ 0</th>
                                                            <th id="promedioCMBThermoTarj">Promedio</th>
                                                            <th id="sumalitherm">0 lts.</th>
                                                          </tr>
                                                        </tfoot>
                                                    </table>
                                                  </td>
                                       </tr>

                                      <tr>  
                                                    <td>
                                                    <table class="tddel tabla-listas" width="100%">
                                                     <tr>
                                                       <th colspan= "2" width = "100%">COMBUSTIBLE EN EFECTIVO <input id="countCMBEfectivo" class="form-control" type="hidden" value="0"></th>
                                                     </tr>
                                                     <tr>
                                                       <th>Cantidad : </th>
                                                       <th><input id="inpCantCMBEfectivo" class="form-control" type="text"></th>
                                                     </tr>
                                                    <tr>
                                                       <th> Costo x Litro : </th>
                                                       <th><div class="col-md-1"></div><input id="inpCostlitEfectivo" class="form-control" type="text"></th>
                                                     </tr>
                                                     <tr>
                                                       <th colspan= "2" width = "100%"><button class="btn btn-warning" onclick="addCMBEfectivo()">Grabar</button></th>
                                                     </tr>
                                                   </table>
                                                  </td>

                                                  <td>
                                                    <table id="tablaCMBEfectivo" class="tabla-listas2 table" border="1" width = "80%">
                                                        <thead>
                                                          <tr>
                                                            <th class="thnext" colspan= "3" width = "100%">CMB EFECTIVO</th>
                                                          </tr>
                                                          <tr>
                                                            <th></th>
                                                            <th>Cantidad </th>
                                                            <th>Costo x Lt</th>
                                                            <th>Litros</th>
                                                          </tr>
                                                        </thead>
                                                        <tbody><tr></tr></tbody>
                                                        <tfoot>
                                                         <tr>
                                                            <th>Suma/prom.</th>
                                                            <th style="background-color: green; color: white; text-align: center; font-style: inherit; " id="sumaCMBEfectivo">$ 0</th>
                                                            <th id="promedioEfect">Promedio</th>
                                                            <th id="sumalitEfectivo">0</th>
                                                            <th>LTS.</th>
                                                          </tr>
                                                        </tfoot>
                                                    </table>
                                                  </td>
  
                                                  <td>
                                                    <table class="tddel tabla-listas table">
                                                     <tr>
                                                       <th colspan= "4" width = "100%">COMBUSTIBLE THERMO EN EFECTIVO <input id="countCMBThermoEfect" class="form-control" type="hidden" value="0"></th>
                                                     </tr>
                                                     <tr>
                                                       <th>Cantidad : </th>
                                                       <th><input id="inpCantCMBThermoEfect" class="form-control" type="text"></th>
                                                     </tr>
                                                    <tr>
                                                       <th> Costo x Litro : </th>
                                                       <th><div class="col-md-1"></div><input id="inpCostlitThermoEfect" class="form-control" type="text"></th>
                                                     </tr>
                                                     <tr>
                                                       <th colspan= "2" width = "100%"><button class="btn btn-warning" onclick="addCMBThermoEfectivo()">Grabar</button></th>
                                                     </tr>
                                                   </table>
                                                  </td>

                                                  <td>
                                                    <table id="tablaCMBThermoEfect" class="tabla-listas2 table" border="1" width = "100%">
                                                        <thead>
                                                          <tr>
                                                            <th class="thnext" colspan= "3" width = "100%">CMB THERMO EFECTIVO</th>
                                                          </tr>
                                                          <tr>
                                                            <th></th> 
                                                            <th>Cantidad </th>
                                                            <th>Costo x Lt</th>
                                                            <th>Litros</th>
                                                          </tr>
                                                        </thead>
                                                        <tbody><tr></tr></tbody>
                                                        <tfoot>
                                                         <tr>
                                                            <th>Suma/prom.</th>
                                                            <th style="background-color: green; color: white; text-align: center; font-style: inherit; " id="sumaCMBThermoEfect">$ 0</th>
                                                            <th id="promedioThermoEfect">Promedio</th>
                                                            <th id="sumalitThermoEfect">0</th>
                                                            <th>LTS.</th>
                                                          </tr>
                                                        </tfoot>
                                                    </table>
                                                  </td>
                                       </tr>

                                      <tr>  
                                                   <td>
                                                    <table class="tddel  tabla-listas table">
                                                     <tr>
                                                       <th colspan= "2" width = "100%">CASETAS <input id="countCaseta" class="form-control" type="hidden" value="0"></th>
                                                     </tr>
                                                     <tr>
                                                       <th>Cantidad :</th>
                                                       <th><input id="inpCantCaseta" class="form-control" type="text"></th>
                                                     </tr>
                                                     <tr>
                                                       <th colspan= "2" width = "100%"><button class="btn btn-warning" onclick="addCaseta()">Grabar</button></th>
                                                     </tr>
                                                   </table>
                                                  </td>

                                                  <td>
                                                      <table id="tablaCaseta" class="tabla-listas2 table" border="1" width = "100%">
                                                        <thead>
                                                          <tr>
                                                            <th class="thnext" colspan= "3" width = "100%">CASETAS</th>
                                                          </tr>
                                                          <tr> 
                                                            <th>Cantidad</th>
                                                          </tr>
                                                        </thead>
                                                        <tbody><tr></tr></tbody>
                                                        <tfoot>
                                                         <tr>
                                                            <th>Suma</th>
                                                            <th style="background-color: green; color: white; text-align: center; font-style: inherit; " id="sumaCaseta">$ 0</th>
                                                          </tr>
                                                        </tfoot>
                                                    </table>
                                                  </td>

                                                  <td>
                                                    <table class="tddel tabla-listas table">
                                                     <tr>
                                                       <th colspan= "2" width = "100%">CASETAS CLIENTE <input id="countCasetaCli" class="form-control" type="hidden" value="0"></th>
                                                     </tr>
                                                     <tr>
                                                       <th>Cantidad :</th>
                                                       <th><input id="inpCantCasetaCli" class="form-control" type="text"></th>
                                                     </tr>
                                                     <tr>
                                                       <th width = "10%"  colspan= "2" width = "100%"><button class="btn btn-warning" onclick="addCasestaCli()">Grabar</button></th>
                                                     </tr>
                                                   </table>
                                                  </td>

                                                  <td>
                                                    <table id="tablaCasetaCli" class="tabla-listas2 table" border="1" width = "100%">
                                                        <thead>
                                                          <tr>
                                                            <th class="thnext" colspan= "3" width = "100%">CASETAS CLIENTE</th>
                                                          </tr>
                                                          <tr> 
                                                            <th>Cantidad</th>
                                                          </tr>
                                                        </thead>
                                                        <tbody><tr></tr></tbody>
                                                        <tfoot>
                                                         <tr>
                                                            <th>Suma</th>
                                                            <th style="background-color: green; color: white; text-align: center; font-style: inherit; "  id="sumaCasetaCli">$ 0</th>
                                                          </tr>
                                                        </tfoot>
                                                    </table>
                                                  </td>
                                       </tr>

                                      <tr>  
                                                   <td width = "50%" colspan= "2">
                                                    <table class="tddel">
                                                     <tr>
                                                       <th colspan= "2" width = "100%">GASTOS <input id="countGasto" class="form-control" type="hidden" value="0"></th>
                                                     </tr>
                                                     <tr>
                                                       <th>Concepto : </th>
                                                       <th>Cantidad : </th>
                                                     </tr>
                                                     <tr>
                                                       <td width = "70%"><select id="idGasto" class="form-control"></select>
                                                        </td>
                                                       <td width = "20%" ><input id="inpCantidadGasto" class="form-control" type="text"></td>
                                                       <td width = "10%" colspan= "2" width = "100%"><button class="btn btn-warning" onclick="addgasto()">Grabar</button></td>
                                                     </tr>
                                                   </table>
                                                  </td>

                                                  <td colspan= "2">
                                                      <table id="tablagastos" class="tabla-listas2 table" border="1" width = "100%">
                                                        <thead>
                                                          <tr>
                                                            <th class="thnext" colspan= "3" width = "100%">GASTOS AUTORIZADOS</th>
                                                          </tr>
                                                          <tr> 
                                                            <th>Concepto</th>
                                                            <th>Clave</th>
                                                            <th>Monto</th>
                                                            <th></th>
                                                          </tr>
                                                        </thead>
                                                        <tbody><tr></tr></tbody>
                                                        <tfoot>
                                                         <tr>
                                                            <th>Total</th>
                                                            <th></th>
                                                            <th style="background-color: green; color: white; text-align: center; font-style: inherit; " id="sumaGasto">$ 0</th>
                                                            <th></th>
                                                          </tr>
                                                        </tfoot>
                                                    </table>
                                                  </td>


                                       </tr>

                                     </table>
                                    </tr>
                                </table>
                          </td>
                              
                      </tr>
                      


                      <tr>
                        <table id="pag2" class="table" width = "100%">
                          <tr>
                            <td>
                              <table class="table" border="" width = "100%">
                                <tr>
                                  <td colspan="2">TOTALES VIAJE</td>
                                </tr>
                                <tr>
                                  <td>ANTICIPOS</td>
                                  <td><input id="totant" class="form-control" type="text"></td>
                                </tr>
                                <tr>
                                  <td>GASTOS</td>
                                  <td><input id="gastot" class="form-control" type="text"></td>
                                </tr>
                                <tr>
                                  <td>DIFERENCIA</td>
                                  <td><input id="diftot" class="form-control" type="text"></td>
                                </tr>
                                <tr>
                                  <td>DEVOLUCION</td>
                                  <td><input id="devtot" class="form-control" type="text"></td>
                                </tr>
                                <tr>
                                  <td>RESTA</td>
                                  <td><input id="restot" class="form-control" type="text"></td>
                                </tr>
                                <tr>
                                  <td colspan="2">DEVOLUCION ANTICIPOS</td>
                                </tr>
                                <tr>
                                  <td>PAGO</td>
                                  <td><select id="" class="form-control"></select></td>
                                </tr>
                                <tr>
                                  <td>CUENTA</td>
                                  <td><select id="" class="form-control"></select></td>
                                </tr>
                              </table>
                            </td>
                            <td>
                              <table id="tabla_convenios" class="table" border="" width = "100%">
                                <thead>
                                  <tr>
                                    <th colspan="3">CONVENIOS</th>
                                  </tr>
                                  <tr>
                                    <th>...</th>
                                    <th>FLETE</th>
                                    <th>SUELDO</th>
                                  </tr>
                                </thead>                           

                              </table>
                            </td>
                            <td>
                              <table class="table table-bordered" border="1" width = "100%">
                                <tr>
                                  <td colspan="4">RESUMEN DE OPERACION</td>
                                </tr>
                                <tr>
                                  <td>KM. REC.</td>
                                  <td><input id="kmrecorridos2" class="form-control" type="text"></td>
                                  <td></td>
                                  <td></td>
                                </tr>
                                <tr>
                                  <td>LTS. CONS. CAMION</td>
                                  <td><input id="litcons" class="form-control" type="text"></td>
                                  <td>LTS. CONS. THERMO</td>
                                  <td><input id="litconstherm" class="form-control" type="text"></td>
                                </tr>
                                <tr>
                                  <td>REND CAMION</td>
                                  <td><input id="rendcam" class="form-control" type="text"></td>
                                  <td>REND THERMO</td>
                                  <td><input id="rendtherm" class="form-control" type="text"></td>
                                </tr>
                                <tr>
                                  <td>% ALCANZADO CAMION</td>
                                  <td><input id="porcencam" class="form-control" type="text"></td>
                                  <td>% ALCANZADO THERMO</td>
                                  <td><input id="porcenttherm" class="form-control" type="text"></td>
                                </tr>
                                <tr>
                                  <td>AJUSTE X LITROS CAMION$</td>
                                  <td><input id="ajusxltspc" class="form-control" type="text"></td>
                                  <td>AJUSTE X LITROS THERMO$</td>
                                  <td><input id="ajuscltsth" class="form-control" type="text"></td>
                                </tr>
                              </table>
                            </td>
                          </tr>
                        </table>
                      </tr>

                    </table>



            <div class="text-right">
                  <button id = "btnclose" class="btn btn-danger" onclick="cerrar()"><i class="glyphicon glyphicon-menu-down"></i> Cerrar</button> 
                  <button id = "btnback" class="btn btn-warning" onclick="regresar()"><i class="glyphicon glyphicon-chevron-left"></i> Regresar</button>    
                  <button id = "btnnext" class="btn btn-success" onclick="siguiente()"><i class="glyphicon glyphicon-chevron-right"></i> Siguiente</button> 
                  <button id = "btnnext2" class="btn btn-info" onclick="siguiente2()"><i class="glyphicon glyphicon-ok-sign"></i> Finzalizar</button>     
            </div>
        </body>
      </div>
   </div>


   <!-- ADD-ANTICIPO -->
  <div class="modal fade" id="modal_add_anticipo" data-keyboard="false">
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
                <input id="Anfecha" class="form-control" type="text">
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
               <input id="Animporte" class="form-control" type="text">
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

<script>
$(function(){
  //$("#divliquidar").hide();
  $("#divadd").show();
  listaGastosConcepto();
  //listaAnticipos(7);
  //datosLiquidacion(7);
  $('#modal_add_anticipo').modal('hide');
  $('#btnback').hide();
  $('.thnext').hide();
  $("#divliquidar").hide();
  $('#btnAnt').show();
});

reloadtable();
  
</script>
