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
}
</style>

<?php $variable = ['var']; ?>
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
  <script src="js/funciones_gen.js" type="text/javascript"></script>
  <script src="js/ordenservicio.js" type="text/javascript"></script>
    <script src="js/html2canvas.min.js" type="text/javascript"></script>
 

          <div class="container" id="">
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
                      <td colspan="4" align="center"><input class="form-control" id="catB" type="text"></td>
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
                        <td> <?php echo $variable; ?></td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
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