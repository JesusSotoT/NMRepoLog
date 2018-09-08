<?php

$SQL='SELECT * from cont_bancos';
$result = $mysqli->query($SQL);
      if($result->num_rows>0) {
        while($row = $result->fetch_array() ) {
          $vercompras[]=$row;
        }
      }else{
        $vercompras=0;
      }   
     
?>
<script>     
$(function() {
      var formEditingOptions = {
        beforeShowForm: function(form){ 
            $('.FormGrid input').css('width','278px');
            $('.FormGrid select').css('width','278px');
            $('.FormGrid textarea').css('width','274px');
            $('.FormGrid textarea').css('height','80px');
            $('#unidad').numeric(); 
            $('#precio_costo').numeric(); 
            $('#precio_venta').numeric(); 
          },
          afterSubmit: function(response, otro){
            if(response.statusText=='OK'){
              return [true];
            }else{
              return [false,' Error al editar el registro '];
            }
          },
          closeAfterEdit:true,
          width: 480
      };
  $.ajax({
    url:'ajax.php',
    type: 'POST',
    dataType: 'JSON',
    data: {opcion:'complemento_proveedores'},
    success: function(r){

      
      jQuery("#jq_alta_destajista").jqGrid({
        url:'sql_jsproveedores.php?q=3',
        datatype: "json",
        colNames:['ID','Tipo de Alta','Estatus','Fecha de captura','Fecha de ingreso','Dias de credito','Limite de credito','Razon social','RFC','Domicilio','Colonia','CP','Pais','Estado','Municipio','Telefono empresa','Apellido paterno','Apellido materno','Nombres','Telefono personal','Correo','Cuenta','Datos Fiscales','Tipo de Proveedor','Beneficiario/Pagador','Cuenta Cliente *','Tipo Tercero *','Tipo Operacion *','IVA retenido % *','ISR retenido % *','Tipo IVA','Asumir tasa % ','Nacionalidad','Número ID Fiscal','Importe Contrato','Adendum 1','Adendum 2','Adendum 3','% De Anticipo','% Fondo de Garantia','% Retencion'],
        colModel:[
        {name:'id', width:40,sortable:true,search:true},
     {name:'alta',index:'alta',stype: 'select', width:50,editable:true,edittype:"select",editoptions:{value:'5:Proveedor;4:Subcontratista',
            },
                 

        



            searchoptions:{sopt:['eq'], value:'5:Proveedor;4:Subcontratista' },
          },

          {sorttype: "int", name:'estatus',index:'estatus',stype: 'select', width:50, sortable:false,editable:true,edittype:"select",editoptions:{value:'Alta:Alta;Baja:Baja;Incapacitado:Incapacitado;Boletinado:Boletinado'},searchoptions:{sopt:['eq'], value:'Alta:Alta;Baja:Baja;Incapacitado:Incapacitado;Boletinado:Boletinado' },
            editrules: {edithidden:true},
            hidden:true
          },

        
          {sortable:true, name:"f_captura",index:"f_captura",width:35,align:"left",formatter:"date",editable:true,sorttype: "date",formatoptions: {srcformat: "Y-m-d H:i:s", newformat: "Y-m-d"},
            editoptions:{ 
              dataInit: function(el){ 
                setTimeout(function(){ 
                  $(el).datepicker({ dateFormat: "yy-mm-dd" });
                }, 200); 
              }
            }
          },
          {name:"f_ingreso",index:"f_ingreso",width:50,align:"left",formatter:"date",editable:true,sorttype: "date",formatoptions: {srcformat: "Y-m-d H:i:s", newformat: "Y-m-d"},
            editoptions:{ 
              dataInit: function(el){ 
                setTimeout(function(){ 
                  $(el).datepicker({ dateFormat: "yy-mm-dd" }); 
                }, 200); 
              }
            },
            editrules: {edithidden:true},
            hidden:true
          },

          {name:'dias_credito',index:'dias_credito', width:50, editable:true,
            editrules: {edithidden:true},
            hidden:true
          },

          {name:'limite_credito',index:'limite_credito', width:50, editable:true,
            editrules: {edithidden:true},
            hidden:true
          },

        
       
         
          {name:'razon_social_sp',index:'razon_social_sp', width:50, editable:true},
          {name:'rfc_sp',index:'rfc_sp', width:50, editable:true},
          {name:'calle_sp',index:'calle_sp', width:50, editable:true,
            editrules: {edithidden:true},
            hidden:true
          },
          {name:'colonia_sp',index:'colonia_sp', width:50, editable:true,
            editrules: {edithidden:true},
            hidden:true
          },
          {name:'cp_sp',index:'cp_sp', width:50, editable:true,
            editrules: {edithidden:true},
            hidden:true
          },

          {name:'pais',index:'pais',stype: 'select', width:50, sortable:false,editable:true,edittype:"select",editoptions:{value:'0:Seleccione;'+r.paises,
              dataEvents:[{ 
                type: 'change', fn: function(e) {
                  data='';
                  id_pais = $(this).val();
                  $('#estado').html('<option value="">Cargando...</option>');
                  $.ajax({
                    url:'ajax.php',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {opcion:'cmb_paises',id_pais:id_pais},
                    success: function(r){
                      if(r.success==1){
                        $.each( r.datos, function(i,d) {
                          data+='<option value="'+d.idestado+'">'+d.estado+'</option>';
                        });
                        $('#estado').html(data);
                      }else{
                        $('#estado').html('<option value="">No hay estados relacionados a este pais</option>');
                      }
                    }
                  });
                } 
              }]
            },
            searchoptions:{sopt:['eq'], value:'0:Seleccione;'+r.paises },
          },

          {name:'estado',index:'estado',stype: 'select', width:70, sortable:false,editable:true,edittype:"select",editoptions:{value:'0:Seleccione;'+r.estados,
            dataEvents:[{ 
                  type: 'change', fn: function(e) {
                    data='';
                    id_estado = $(this).val();
                    $('#municipio').html('<option value="">Cargando...</option>');
                    $.ajax({
                      url:'ajax.php',
                      type: 'POST',
                      dataType: 'JSON',
                      data: {opcion:'cmb_estados',id_estado:id_estado},
                      success: function(r){
                        if(r.success==1){
                          $.each( r.datos, function(i,d) {
                            data+='<option value="'+d.idmunicipio+'">'+d.municipio+'</option>';
                          });
                          $('#municipio').html(data);
                        }else{
                          $('#municipio').html('<option value="">No hay municipios relacionados a este estado</option>');
                        }
                      }
                    });
                  } 
                }]
              },
            searchoptions:{sopt:['eq'], value:'0:Seleccione;'+r.estados }
          },

          {name:'municipio',index:'municipio',stype: 'select', width:70, sortable:false,editable:true,edittype:"select",editoptions:{value:'0:Seleccione;'+r.municipios},searchoptions:{sopt:['eq'], value:'0:Seleccione;'+r.municipios }
          },

          // {name:'municipio_sp',index:'municipio_sp', width:50, editable:true,
          //   editrules: {edithidden:true},
          //   hidden:true
          // },
          // {name:'estado_sp',index:'estado_sp', width:50, editable:true,
          //   editrules: {edithidden:true},
          //   hidden:true
          // },
          {name:'tel_emp_sp',index:'tel_emp_sp', width:50, editable:true,
            editrules: {edithidden:true},
            hidden:true
          },
          {name:'paterno_sp',index:'paterno_sp', width:50, editable:true,
            editrules: {edithidden:true},
            hidden:true
          },
          {name:'materno_sp',index:'materno_sp', width:50, editable:true,
            editrules: {edithidden:true},
            hidden:true
          },
          {name:'nombres_sp',index:'nombres_sp', width:50, editable:true,
            editrules: {edithidden:true},
            hidden:true
          },
          {name:'tel_personal_sp',index:'tel_personal_sp', width:50, editable:true,
            editrules: {edithidden:true},
            hidden:true
          },
          {name:'correo_sp',index:'correo_sp', width:50, editable:true,
            editrules: {edithidden:true},
            hidden:true
          },
          {sorttype: "int", name:'cuenta',index:'cuenta',stype: 'select', width:50, sortable:false,editable:true,edittype:"select",editoptions:{value:'0:Seleccione;'+r.cuentas},searchoptions:{sopt:['eq'], value:'0:Seleccione;'+r.cuentas },
            editrules: {edithidden:true},
            hidden:true
          },
       

                 {name:'fis',index:'fis',stype: 'select', width:50, sortable:false,editable:true,edittype:"select",editoptions:{value:'1:Si;2:No',
            
            },

            searchoptions:{sopt:['eq'], value:'1:Si;2:No' },
                        editrules: {edithidden:true},
            hidden:true
          },

             {name:'tprov',index:'tprov',stype: 'select', width:50, sortable:false,editable:true,edittype:"select",editoptions:{value:r.tprov},
            searchoptions:{sopt:['eq'], value:r.tprov },
                        editrules: {edithidden:true},
            hidden:true
          },

                   {name:'ben',index:'ben',stype: 'select', width:50, sortable:false,editable:true,edittype:"select",editoptions:{value:'1:Si;2:No',
            },

            searchoptions:{sopt:['eq'], value:'1:Si;2:No' },
                        editrules: {edithidden:true},
            hidden:true
          },

              {name:'ccliente',index:'ccliente',stype: 'select', width:50, sortable:false,editable:true,edittype:"select",editoptions:{value:'0:Seleccione;'+r.ccliente},
            searchoptions:{sopt:['eq'], value:'0:Seleccione;'+r.ccliente },
                        editrules: {edithidden:true},
            hidden:true
          },

           {name:'ttercero',index:'ttercero',stype: 'select', width:50, sortable:false,editable:true,edittype:"select",editoptions:{value:'0:Seleccione;'+r.ttercero,
              dataEvents:[{ 
                type: 'change', fn: function(e) {
                  data='';
                  id_ter = $(this).val();
                  $('#toperacion').html('<option value="">Cargando...</option>');
                  $.ajax({
                    url:'ajax.php',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {opcion:'cmb_operacion',id_ter:id_ter},
                    success: function(r){
                      if(r.success==1){
                        $.each( r.datos, function(i,d) {
                          data+='<option value="'+d.id+'">'+d.tipoOperacion+'</option>';
                        });
                        $('#toperacion').html(data);
                      }else{
                        $('#toperacion').html('<option value="">No hay tipos relacionados a este tipo tercero</option>');
                      }
                    }
                  });
                } 
              }]
            },
            searchoptions:{sopt:['eq'], value:'0:Seleccione;'+r.ttercero },
                        editrules: {edithidden:true},
            hidden:true
          },

             {name:'toperacion',index:'toperacion',stype: 'select', width:70, sortable:false,editable:true,edittype:"select",editoptions:{value:'0:Seleccione;'+r.toperacion},searchoptions:{sopt:['eq'], value:'0:Seleccione;'+r.toperacion },
                            editrules: {edithidden:true},
            hidden:true
          },

             {name:'ivar',index:'ivar', width:50, editable:true,
                         editrules: {edithidden:true},
            hidden:true
            
          },

             {name:'isrr',index:'isrr', width:50, editable:true,
                         editrules: {edithidden:true},
            hidden:true
            
          },

                 {name:'tiva',index:'tiva',stype: 'select', width:50, sortable:false,editable:true,edittype:"select",editoptions:{value:'0:Seleccione;'+r.tiva},
            searchoptions:{sopt:['eq'], value:'0:Seleccione;'+r.tiva },
                              editrules: {edithidden:true},
            hidden:true
          },

                    {name:'tasa',index:'tasa',stype: 'select', width:50, sortable:false,editable:true,edittype:"select",editoptions:{value:'1:16%;2:11%;3:0%;4:Exenta;5:15%;6:10%',
            },
                 

                     editrules: {edithidden:true},
            hidden:true,



            searchoptions:{sopt:['eq'], value:'1:16%;2:11%;3:0%;4:Exenta;5:15%;6:10%' },
          },

                  {name:'na',index:'na', width:50, editable:true,
            editrules: {edithidden:true},
            hidden:true
          },
               {name:'idf',index:'idf', width:50, editable:true,
            editrules: {edithidden:true},
            hidden:true
          },

                   {name:'imp',index:'imp', width:50, editable:true,
            editrules: {edithidden:true},
            hidden:true
          },
               {name:'ad1',index:'ad1', width:50, editable:true,
            editrules: {edithidden:true},
            hidden:true
          },
                 {name:'ad2',index:'ad2', width:50, editable:true,
            editrules: {edithidden:true},
            hidden:true
          },
                 {name:'ad3',index:'ad3', width:50, editable:true,
            editrules: {edithidden:true},
            hidden:true
          },
                 {name:'ant',index:'ant', width:50, editable:true,
            editrules: {edithidden:true},
            hidden:true
          },
                 {name:'gar',index:'gar', width:50, editable:true,
            editrules: {edithidden:true},
            hidden:true
          },
                 {name:'ret',index:'ret', width:50, editable:true,
            editrules: {edithidden:true},
            hidden:true
          },


        ],

        rowNum:1000000,
        rowList: [], 
        pgbuttons: false,
        pgtext: null, 
        pager: '#jqp_alta_destajista',
        sortname: 'id',
        viewrecords: true,
        sortorder: "desc",
        autowidth: true,
        height: "300",
        multiselect: true,
       /* groupingView : {
          groupSummary : [true],
          groupColumnShow : [true],
          groupText : ['<b>{0}</b>'],
          groupCollapse : false,
          groupOrder: ['asc'] 
        }, */
        gridComplete: function(){
          var ids = jQuery("#jq_alta_destajista").jqGrid('getDataIDs');
          console.log(ids);
          for(var i=0;i < ids.length;i++){
            var cl = ids[i];
            var nn7 = $('#jq_alta_destajista').jqGrid('getCell',ids[i],'nombre');
            be = ''; 
            jQuery("#jq_alta_destajista").jqGrid('setRowData',ids[i],{icon:be});
          }
        },
        editurl: "sql_jsproveedores.php",

        onCellSelect: function(rowid, iRow, iCol, e) {
          //$(this).jqGrid('editGridRow', rowid, formEditingOptions);
          /*
          var rowData = $("#jq_alta_tecnico").jqGrid('getRowData',id); 
          var desc = rowData['descripcion'];
          $("#rdesc").html(desc);
          $("#descripcion").css('display','block');
          */
        }


      });

      //$("div.ui-jqgrid-sdiv").after($("div.ui-jqgrid-bdiv"));
      jQuery("#jq_alta_destajista").jqGrid('navGrid',"#jqp_alta_destajista",
        {edit:true,add:true,del:true,search:true,
        },
        {beforeShowForm: function(form){ 
            $('.FormGrid input').css('width','278px');
            $('.FormGrid select').css('width','278px');
            $('.FormGrid textarea').css('width','274px');
            $('.FormGrid textarea').css('height','80px');
            $('#unidad').numeric(); 
            $('#isrr').numeric(); 
            $('#ivar').numeric();

        $('<tr><td><input type="button" value="Cuentas Bancos" data-toggle="modal" data-target="#bancosmodal2" /></td>').insertAfter( '#tr_idf');
               $('#precio_venta').numeric();
                   $('#precio_venta').numeric();
            var deptoSalario = $('#tr_id_partida', form).show();
            $('<tr class="FormData" id="tr_AddInfo"><td class="CaptionTD ui-widget-content"><b>Depto:</b></td></tr>').insertAfter(deptoSalario);

            var planeacion = $('#tr_limite_credito', form).show();
            $('<tr class="FormData" id="tr_AddInfo"><td class="CaptionTD ui-widget-content"><b>Planeacion:</b></td></tr>').insertAfter(planeacion);

            var datosPersonales = $('#tr_limite_credito', form).show();
            $('<tr class="FormData" id="tr_AddInfo"><td class="CaptionTD ui-widget-content"><b>Datos fiscales:</b></td></tr>').insertAfter(datosPersonales);




          },
          afterSubmit: function(response, otro){
              if(response.responseText=='ob'){
              return [false,' Rellene todos los datos obligatorios '];
            }
            else{
            if(response.statusText=='OK'){
              return [true];
            }
    
            else{
              return [false,' Error al editar el registro '];
            }}
          },
          closeAfterEdit:true,
          width: 480
        },
        
        {beforeShowForm: function(form){ 
            $('.FormGrid input').css('width','278px');
            $('.FormGrid select').css('width','278px');
            $('.FormGrid textarea').css('width','274px');
            $('.FormGrid textarea').css('height','80px');
            $('#unidad').numeric(); 
            $('#precio_costo').numeric(); 
            $('#precio_venta').numeric();
                  $('#isrr').numeric(); 
            $('#ivar').numeric();

           $('<tr><td><input type="button" value="Cuentas Bancos" data-toggle="modal" data-target="#bancosmodal" /></td>').insertAfter( '#tr_idf');

            $('#estado').html('<option value="0">Seleccione</option>');
                $('#toperacion').html('<option value="0">Seleccione</option>');
                    $('#municipio').html('<option value="0">Seleccione</option>');

            var deptoSalario = $('#tr_id_partida', form).show();
            $('<tr class="FormData" id="tr_AddInfo"><td class="CaptionTD ui-widget-content"><b>Depto:</b></td></tr>').insertAfter(deptoSalario);

            var planeacion = $('#tr_limite_credito', form).show();
            $('<tr class="FormData" id="tr_AddInfo"><td class="CaptionTD ui-widget-content"><b>Planeacion:</b></td></tr>').insertAfter(planeacion);

            var datosPersonales = $('#tr_oc_inst', form).show();
            $('<tr class="FormData" id="tr_AddInfo"><td class="CaptionTD ui-widget-content"><b>Datos fiscales:</b></td></tr>').insertAfter(datosPersonales);
            
          },
          afterSubmit: function(response, otro){
              if(response.responseText=='ob'){
              return [false,' Rellene todos los datos obligatorios '];
            }
            else{
            if(response.statusText=='OK'){
              return [true];
            }

            else{
              return [false,' Error al guardar el registro '];
            }}
          },
          closeAfterAdd:true,
          width: 480
        },
        {width: 480}, // settings for delete
        {onSearch: function(data){
          $("#jq_alta_destajista").jqGrid('setColProp', 'precio_costo', { formatter:"number", });
          $("#jq_alta_destajista").jqGrid('setColProp', 'precio_venta', { formatter:"number", });
         }
        } // search options
      ).jqGrid('navButtonAdd', '#jqp_alta_destajista', {
        caption: "Exportar Excel",
        buttonicon: "ui-icon-export",
          onClickButton: function() {
                window.open('xls_expPro.php');
                //$("#jq_alta_destajista").jqGrid('exportarExcelCliente',{nombre:"HOJATEST",formato:"excel"});

                //exportarExcel('altaProveedores', 'W3C Example Table','#jq_alta_destajista');
            },

            
            position: "last"
        });

  
    }
  });
});

function agregarBanco(){
  var idBanco = $('#selectBanco').val();
  var nombre = $("#selectBanco option:selected").text(); 
  var noCuentaBan = $("#noCuentaBan").val();
  var aux = 1;
  $("#bancoList tr").each(function (index) {   
    //idbanc = $(this).attr('idbanco');
    nctaBanc = $(this).attr('numct');
    if (noCuentaBan == nctaBanc) {
//    if(idBanco == idbanc){
      alert('El No. de cuenta y/o tarjeta ya esta en la lista');
      aux = 0;
    }
  });
  if(aux==0){
    return false;
  }
  $('#bancoList tr:last').after('<tr id="idBan_'+idBanco+'" idbanco="'+idBanco+'" idRel="0" numct="'+noCuentaBan+'"><td><span class="glyphicon glyphicon-remove" onclick="removeBanco('+idBanco+');"></span></td><td>'+nombre+'</td><td>'+noCuentaBan+'</td></tr>');
}




function removeBanco(id){
  $('#idBan_'+id).remove();
}


function agregarBanco2(){
  var idBanco = $('#selectBanco2').val();
  var nombre = $("#selectBanco2 option:selected").text(); 
  var noCuentaBan = $("#noCuentaBan2").val();
  var aux = 1;
  $("#bancoList2 tr").each(function (index) {   
    //idbanc = $(this).attr('idbanco');
    nctaBanc = $(this).attr('numct');
    if (noCuentaBan == nctaBanc) {
//    if(idBanco == idbanc){
      alert('El No. de cuenta y/o tarjeta ya esta en la lista');
      aux = 0;
    }
  });
  if(aux==0){
    return false;
  }
  $('#bancoList2 tr:last').after('<tr id="idBan_'+idBanco+'" idbanco="'+idBanco+'" idRel="0" numct="'+noCuentaBan+'"><td><span class="glyphicon glyphicon-remove" onclick="removeBanco('+idBanco+');"></span></td><td>'+nombre+'</td><td>'+noCuentaBan+'</td></tr>');
}






  </script> 

  <body>

    <div id="divcuandohayobra" class="row">
      <div class="col-sm-12">
        <div class="navbar navbar-default"  style="margin-top:10px;">
          <div class="navbar-header">
              <div class="navbar-brand" style="color:#333;">Alta de proveedores / subcontratistas</div>
          </div>
        </div>
        <div class="row">
          <div class="col-xs-12 tablaResponsiva">
            <div class="table-responsive" id="dtabla">
                <table id="jq_alta_destajista"></table>
                <div id="jqp_alta_destajista"></div>
            </div>
          </div>
        </div>
      </div>
    </div>

<div class="modal fade" id="bancosmodal" role="dialog"  >

  <script>
$('#bancosmodal').on('hidden.bs.modal', function () {

  var stringBanco = '';
      $("#bancoList tr").each(function (index) 
      {   
        idrel     = $(this).attr('idrel');
        idbanco   = $(this).attr('idbanco');
        numct     = $(this).attr('numct');

        if(idrel == 0){ // solo guarda los nuevos en la lista
          stringBanco +='-'+idbanco+'-'+numct+'#';
        }
      });

      $.ajax({
      url:"ajax.php",
      type: 'POST',
      data:{opcion:'bancos',stringbanco:stringBanco},
      success: function(r){
       
      }
    });
   
});

  </script>
     <div class="modal-dialog" >
      
      
         <div class="modal-content">

      <div id="bancoProvedores" > <br>
              
    
                <div class="row" id="divBP" >
                  <div class="col-sm-12">
                    <div class="col-sm-4">
                      <label> <font color="red">*</font> Banco</label>
                      <select id="selectBanco" class="form-control">
                        <?php 
                          foreach ($vercompras as $keyClas => $valueClas) {
                         
                           
                           
                            echo '<option value="'.$valueClas['idbanco'].'">'.$valueClas['nombre'].'/'.$valueClas['Clave'].'</option>';
                          }
                        ?>
                      </select>
                    </div>
                  </div>

                  <div class="col-sm-12">
                    <div class="col-sm-4"> <br>
                      <label> <font color="red">*</font> No. Tarjeta / Cuenta bancaria</label>
                      <input id="noCuentaBan" class="form-control numeros" type="text" value=""> <br>

                      <button type="button" class="btn btn-success" onclick="agregarBanco();">Agregar
                        <i class="fa fa-plus cursor" aria-hidden="true"></i>
                      </button> 
                              <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                      <!--<button onclick="savebancos();">Guardar Lista</button> -->
                    </div>
                  </div> <br>

                  <div class="col-sm-6">
                    <table id="bancoList" class="table">
                      <thead>
                        <tr>
                          <th></th>
                          <th>Banco</th>
                          <th>No. Tarjeta o Cuenta bancaria</th>
                        </tr>
                      </thead>
                      <tbody>
                    
                      </tbody>
                    </table>
                  </div> 
                </div>
   </div></div>
    </div>
  </div>

  <div class="modal fade" id="bancosmodal2" role="dialog"  >
  <div class="modal-dialog" >
                <script>
   

                $('#bancosmodal2').on('show.bs.modal', function () {
               
                
                 
                   id=$('#id_g').val();
                       id2=$('#prov').val();
                      
      

                $.ajax({
      url:"ajax.php",
      type: 'POST',
       dataType: "json", 
      data:{opcion:'bancosa',id:id},
      success: function(r){

 if(id!=id2){   $("#bancoList2 tr").html("");
$.each(r, function(i, item) {



var idBanco = r[i].idbanco;
  var nombre = r[i].nombre;
  var noCuentaBan = r[i].numCT;



  $('#bancoList2 tr:last').after('<tr id="idBan_'+idBanco+'" idbanco="'+idBanco+'" idRel="0" numct="'+noCuentaBan+'"><td><span class="glyphicon glyphicon-remove" onclick="removeBanco('+idBanco+');"></span></td><td>'+nombre+'</td><td>'+noCuentaBan+'</td></tr>');});
}
        
        $('#prov').val(id);
       
      }
   
});

                  });

$('#bancosmodal2').on('hidden.bs.modal', function () {
      id=$('#id_g').val();

  var stringBanco = '';
      $("#bancoList2 tr").each(function (index) 
      {   
        idrel     = $(this).attr('idrel');
        idbanco   = $(this).attr('idbanco');
        numct     = $(this).attr('numct');

        if(idrel == 0){ // solo guarda los nuevos en la lista
          stringBanco +='-'+idbanco+'-'+numct+'#';
        }
      });

      $.ajax({
      url:"ajax.php",
      type: 'POST',
      data:{opcion:'bancos',stringbanco:stringBanco,id:id},
      success: function(r){
       
      }
    });
   
});
                </script>



      
      
         <div class="modal-content">

      <div id="bancoProvedores" > <br>
              

                <div class="row" id="divBP" >
                  <div class="col-sm-12">
                    <div class="col-sm-4">
                      <label> <font color="red">*</font> Banco</label>
                      <select id="selectBanco2" class="form-control">
                        <?php 
                          foreach ($vercompras as $keyClas => $valueClas) {
                         
                           
                           
                            echo '<option value="'.$valueClas['idbanco'].'">'.$valueClas['nombre'].'/'.$valueClas['Clave'].'</option>';
                          }
                        ?>
                      </select>
                    </div>
                  </div>

                  <div class="col-sm-12">
                    <div class="col-sm-4"> <br>
                      <label> <font color="red">*</font> No. Tarjeta / Cuenta bancaria</label>
                      <input id="noCuentaBan2" class="form-control numeros" type="text" value=""> <br>

                      <button type="button" class="btn btn-success" onclick="agregarBanco2();">Agregar
                        <i class="fa fa-plus cursor" aria-hidden="true"></i>
                      </button> 
                      <input type="hidden" id="prov" value="";?> >
                              <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                      <!--<button onclick="savebancos();">Guardar Lista</button> -->
                    </div>
                  </div> <br>

                  <div class="col-sm-6">
                    <table id="bancoList2" class="table">
                      <thead>
                        <tr>
                          <th></th>
                          <th>Banco</th>
                          <th>No. Tarjeta o Cuenta bancaria</th>
                        </tr>
                      </thead>
                      <tbody>
                    
                      </tbody>
                    </table>
                  </div> 
                </div>
   </div></div>
    </div>
  </div>

</body>
  