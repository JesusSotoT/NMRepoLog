<script src="js/inputmask.js"></script>
<script src="js/inputmask.date.extensions.js"></script>
<script src="js/jquery.inputmask.js"></script>

<script> 
    $(function() {
      $.ajax({
        url:'ajax.php',
        type: 'POST',
        data: {opcion:'constructoras'},
        success: function(cc){
         
      var formEditingOptions = {
        beforeShowForm: function(form){ 
            $('.FormGrid input').css('width','320px');
            $('.FormGrid select').css('width','328px');
            $('.FormGrid textarea').css('width','324px');
            $('.FormGrid textarea').css('height','80px');
            $('#unidad').numeric(); 
            $('#precio_costo').numeric(); 
            $('#precio_venta').numeric(); 
            $('#honorarios').numeric(); 
            $('#iva').numeric(); 
            $('#presupuesto').numeric(); 
            $('#anticipo').numeric(); 
            $('#ade1').numeric(); 
            $('#ade2').numeric(); 
            $('#ade3').numeric(); 
            $('#ade4').numeric(); 
            $("#horai").inputmask("hh:mm:ss");
            $("#horaf").inputmask("hh:mm:ss");
                $('#tr_logoe').hide();
               $('#TblGrid_rowed22').append('<tr><td><b>Logo:<b></td><td><input type="file" id="filel" name="Filedata" style="display: none"><label for="filel" id="flabel" class="btn btn-primary btn-block btn-outlined">Seleccionar imagenes</label></td></tr>');
                        $("#filel").change(function(){
            $("#logoe").val($('#filel').val().substr(12));
               $("#flabel").text($('#filel').val().substr(12)); 
              if($('#logoe').val()!==''){
            $("#filel").simpleUpload('subirArchivoimg.php', {
              start: function(file){
                //upload started
                console.log("upload started");
              },

              progress: function(progress){
                //received progress
                console.log("upload progress: " + Math.round(progress) + "%");
              },

              success: function(data){

                //upload successful
                console.log(data);
                var objresp = $.parseJSON(data);
                console.log(objresp);


                if(objresp.success==true){
             $('#logoe').val(objresp.archivo);
             
                }else{
                   $('#logoe').val('');
                }

              },

              error: function(error){
                alert('Error al subir el archivo');
                console.log("upload error: " + error.name + ": " + error.message);
              }
            });}
                            else{

            }





          });
        

          },
          beforeSubmit: function(postdata, formid){
            inicio=$('#inicio').val();
            termino=$('#termino').val();
            presupuesto=$('#presupuesto').val();
            anticipo=$('#anticipo').val();
              colonia=$('#colonia_sp').val();
            cp=$('#cp_sp').val();
            pais=$('#pais').val();
            municipio=$('#municipio').val();
            calle=$('#localizacion').val();
            numext=$('#numext').val();
            estado=$('#estado').val();

            if(inicio == 0 || termino == 0){
              return [false, '&nbsp; Introduzca la fecha'];
              }else if(Date.parse(inicio) >= Date.parse(termino))
              {
                return [false, '&nbsp; La fecha de Inicio debe ser anterior a la fecha de Termino'];
              }else if(presupuesto <= 0){
                return [false, '&nbsp; Introduzca el Presupuesto'];
              }else if(anticipo < 0){
                return [false, '&nbsp; Introduzca el Anticipo'];
              }else if(pais==0||municipio==0||estado==0||calle==""||numext==""||colonia==""||cp==""){
                return [false, '&nbsp; Introduzca los datos obligatorios'];
              }else{ 
                cerrar_session(modulo);
                return[true,''];
              }
          },
          closeAfterEdit:true,
        width: 500,
        recreateForm: true
      };

    $.ajax({
    url:'ajax.php',
    type: 'POST',
    dataType: 'JSON',
    data: {opcion:'complemento_clientes'},
    success: function(r){

      jQuery("#rowed22").jqGrid({
        url:'sql_jsaltaobra.php?q=3',
        datatype: "json",
        colNames:['Obra', 'Cliente', 'Licitacion No.', 'Construye', 'Clave', 'Contrato','Hora Inicio','Hora Fin','Direccion de obra *','Gerente de Obra','Direccion de Proyectos','Administracion de Obra', 'Inicio', 'Termino', 'IVA', 'Presupuesto', 'Anticipo %', 'Adendum 1', 'Adendum 2', 'Adendum 3', 'Adendum 4', 'Dias de credito','Limite de credito','Colonia *','CP *','Pais *','Estado *','Municipio *','Email','Cuenta','Residente','Director de obra', 'Super intendencia', 'Control de obra', 'Supervision', 'Fecha de contrato','No. de compromiso','Fecha de compromiso','Numero de obra','Telefono','RFC','Razon social','numero exterior *','Logo'],
        colModel:[
          {name:'obra',index:'obra', width:200, sortable:false,editable:true}, 
          {name:'cliente',index:'cliente', width:200, sortable:false,editable:true},
          {name:'licitacion',index:'licitacion', width:140, sortable:false,editable:true},
          {name:'construye',index:'construye',stype: 'select', width:140, sortable:false,editable:true,edittype:"select",editoptions:{value:cc},searchoptions:{sopt:['eq'], value:cc },
            editrules: {edithidden:true},
            hidden:true
          }, 
          {name:'clave',index:'clave', width:70, sortable:false,editable:true}, 
          {name:'contrato',index:'contrato', width:0, sortable:false,editable:true, hidden:true, editrules: 
            {edithidden:true} 
          }, 
 {name:'horai',index:'horai', width:0, sortable:false,editable:true, hidden:true, editrules: 
            {edithidden:true} 
          }, 
           {name:'horaf',index:'horaf', width:0, sortable:false,editable:true, hidden:true, editrules: 
            {edithidden:true} 
          }, 

          {name:'localizacion',index:'localizacion', width:150, sortable:false,editable:true, hidden:true, editrules: 
            {edithidden:true} 
          },
  {name:'gerentedeo',index:'gerentedeo', width:150, sortable:false,editable:true, hidden:true, editrules: 
            {edithidden:true} 
          },
            {name:'dproyectos',index:'dproyectos', width:150, sortable:false,editable:true, hidden:true, editrules: 
            {edithidden:true} 
          },
            {name:'admono',index:'admono', width:150, sortable:false,editable:true, hidden:true, editrules: 
            {edithidden:true} 
          },

         




          {name:"inicio",index:"inicio",width:50,align:"left",formatter:"date",editable:true,sorttype: "date",formatoptions: {srcformat: "Y-m-d H:i:s", newformat: "Y-m-d"},
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
          {name:"termino",index:"termino",width:50,align:"left",formatter:"date",editable:true,sorttype: "date",formatoptions: {srcformat: "Y-m-d H:i:s", newformat: "Y-m-d"},
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
         /* {name:'hon',index:'hon', width:150, sortable:false,editable:true, hidden:true, editrules: 
            {edithidden:true} 
          }, */
          {name:'iva',index:'iva', width:150, sortable:false,editable:true, hidden:true, editrules: 
            {edithidden:true} 
          },
          {name:'presupuesto',index:'presupuesto', width:150, sortable:false,editable:true, hidden:true, editrules: 
            {edithidden:true} 
          },
          {name:'anticipo',index:'anticipo', width:150, sortable:false,editable:true, hidden:true, editrules: 
            {edithidden:true} 
          },
          {name:'ade1',index:'ade1', width:150, sortable:false,editable:true, hidden:true, editrules: 
            {edithidden:true} 
          },
          {name:'ade2',index:'ade2', width:150, sortable:false,editable:true, hidden:true, editrules: 
            {edithidden:true} 
          },
          {name:'ade3',index:'ade3', width:150, sortable:false,editable:true, hidden:true, editrules: 
            {edithidden:true} 
          },
          {name:'ade4',index:'ade4', width:150, sortable:false,editable:true, hidden:true, editrules: 
            {edithidden:true} 
          },

            {name:'dias_credito',index:'dias_credito', width:50, editable:true,
            editrules: {edithidden:true},
            hidden:true
          },

          {name:'limite_credito',index:'limite_credito', width:50, editable:true,
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


           {name:'correo_sp',index:'correo_sp', width:50, editable:true,
            editrules: {edithidden:true},
            hidden:true
          },
          {sorttype: "int", name:'cuenta',index:'cuenta',stype: 'select', width:50, sortable:false,editable:true,edittype:"select",editoptions:{value:'0:Seleccione;'+r.cuentas},searchoptions:{sopt:['eq'], value:'0:Seleccione;'+r.cuentas },
            editrules: {edithidden:true},
            hidden:true
          },
                      {name:'residente',index:'residente', width:150, sortable:false,editable:true, hidden:true, editrules: 
            {edithidden:true} 
          },
          {name:'director',index:'director', width:150, sortable:false,editable:true, hidden:true, editrules: 
            {edithidden:true} 
          },
          {name:'superintendencia',index:'superintendencia', width:150, sortable:false,editable:true,hidden:true, editrules: 
            {edithidden:true} 
          },
          {name:'control',index:'control', width:150, sortable:false,editable:true, hidden:true, editrules: 
            {edithidden:true} 
          }, 
          {name:'supervision',index:'supervision', width:150, sortable:false,editable:true, hidden:true, editrules: 
            {edithidden:true} 
          }, 
          {name:"fecha_contrato",index:"fecha_contrato",width:50,align:"left",formatter:"date",editable:true,sorttype: "date",formatoptions: {srcformat: "Y-m-d H:i:s", newformat: "Y-m-d"},
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
          {name:'no_compromiso',index:'no_compromiso', width:150, sortable:false,editable:true,hidden:true,editrules: 
          {edithidden:true} 
          },
          {name:"fecha_compromiso",index:"fecha_compromiso",width:50,align:"left",formatter:"date",editable:true,sorttype: "date",formatoptions: {srcformat: "Y-m-d H:i:s", newformat: "Y-m-d"},
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
          {name:'no_obra',index:'no_obra', width:150, sortable:false,editable:true,hidden:true,editrules: 
          {edithidden:true} 
          },
          {name:'telefono',index:'telefono', width:150, sortable:false,editable:true,hidden:true,editrules: 
           {edithidden:true}
          },

              {name:'rfc',index:'rfc', width:150, sortable:false,editable:true, hidden:true, editrules: 
            {edithidden:true} 
          },
            {name:'rs',index:'rs', width:150, sortable:false,editable:true, hidden:true, editrules: 
            {edithidden:true} 
          },
              {name:'numext',index:'numext', width:150, sortable:false,editable:true, hidden:true, editrules: 
            {edithidden:true} 
          },

          {name:'logoe',index:'logoe', width:150, sortable:false,editable:true,hidden:true,editrules: 
          {edithidden:true} 
          }


        ],
        loadComplete: function() {
                    $(".jq_arbolghead_0 td").css("background-color", "#c0c0c0");
                    $(".jq_arbolghead_1 td").css("background-color", "#d0d0d0");
                    $(".jq_arbolghead_2 td").css("background-color", "#e0e0e0");
                    $(".jq_arbolghead_3 td").css("background-color", "#f0f0f0");
                    $("tr #null").remove();

        },
        rowNum:1000000,
        rowList: [], 
        pgbuttons: false,
        pgtext: null,  
        pager: '#prowed22',
        sortname: 'id',
        viewrecords: true,
        autowidth: true,  
        sortorder: "desc",
        editurl: "sql_jsaltaobra.php",

        multiselect:true,
        height:260

      });
  
      jQuery("#rowed22").jqGrid('navGrid',"#prowed22",
        {edit:true,add:true,del:true,search:true},
        formEditingOptions,
        {beforeShowForm: function(form){ 
            $('.FormGrid input').css('width','270px');
            $('.FormGrid select').css('width','278px');
            $('.FormGrid textarea').css('width','274px');
            $('.FormGrid textarea').css('height','80px');
            $('#unidad').numeric(); 
            $('#precio_costo').numeric(); 
            $('#precio_venta').numeric();
            $("#horai").inputmask("hh:mm:ss");
            $("#horaf").inputmask("hh:mm:ss");
            $('#iva').numeric(); 
            $('#presupuesto').numeric(); 
            $('#anticipo').numeric(); 
            $('#ade1').numeric(); 
            $('#ade2').numeric(); 
            $('#ade3').numeric(); 
            $('#ade4').numeric();
             $('#tr_logoe').hide();
               $('#TblGrid_rowed22').append('<tr><td><b>Logo:<b></td><td><input type="file" id="filel" name="Filedata" style="display: none"><label for="filel" id="flabel" class="btn btn-primary btn-block btn-outlined">Seleccionar imagenes</label></td></tr>');
                        $("#filel").change(function(){
            $("#logoe").val($('#filel').val().substr(12));
               $("#flabel").text($('#filel').val().substr(12)); 
              if($('#logoe').val()!==''){
            $("#filel").simpleUpload('subirArchivoimg.php', {
              start: function(file){
                //upload started
                console.log("upload started");
              },

              progress: function(progress){
                //received progress
                console.log("upload progress: " + Math.round(progress) + "%");
              },

              success: function(data){

                //upload successful
                console.log(data);
                var objresp = $.parseJSON(data);
                console.log(objresp);


                if(objresp.success==true){
             $('#logoe').val(objresp.archivo);
             
                }else{
                   $('#logoe').val('');
                }

              },

              error: function(error){
                alert('Error al subir el archivo');
                console.log("upload error: " + error.name + ": " + error.message);
              }
            });}
                            else{

            }





          });
           

          },
          beforeSubmit: function(postdata, formid){
            inicio=$('#inicio').val();
            termino=$('#termino').val();
            presupuesto=$('#presupuesto').val();
            anticipo=$('#anticipo').val();
            colonia=$('#colonia_sp').val();
            cp=$('#cp_sp').val();
            pais=$('#pais').val();
            municipio=$('#municipio').val();
            calle=$('#localizacion').val();
            numext=$('#numext').val();
            estado=$('#estado').val();


            if(inicio == 0 || termino == 0){
              return [false, '&nbsp; Introduzca la fecha'];
              }else if(Date.parse(inicio) >= Date.parse(termino))
              {
                return [false, '&nbsp; La fecha de Inicio debe ser anterior a la fecha de Termino'];
              }else if(presupuesto <= 0){
                return [false, '&nbsp; Introduzca el Presupuesto'];
              }else if(anticipo < 0){
                return [false, '&nbsp; Introduzca el Anticipo'];
              }else if(pais==0||municipio==0||estado==0||calle==""||numext==""||colonia==""||cp==""){
                return [false, '&nbsp; Introduzca los datos obligatorios'];
              }
              else{ 
                cerrar_session(modulo);
                return[true,''];
              }
          },


          

          closeAfterAdd:true,
          width: 600
        },
        {width: 480}, // settings for delete
        {} // search options
      ).jqGrid('navButtonAdd', '#prowed22', {
        caption: "Exportar Excel",
        buttonicon: "ui-icon-export",
          onClickButton: function() {
                $("#rowed22").jqGrid('exportarExcelCliente',{nombre:"HOJATEST",formato:"excel"});
            },
            position: "last"
        });
      }
    });
  }
  });

});
</script> 

         

<body>
  <div class="container" style="width:100%">
    <div class="row">
      <div class="col-sm-10 col-sm-offset-1">
        <div class="navbar navbar-default"  style="margin-top:10px;">
          <div class="navbar-header">
              <div class="navbar-brand" style="color:#333;">Alta de obra</div>
          </div>
        </div>
        <div class="row">
          <div class="col-xs-12 tablaResponsiva">
            <div class="table-responsive" id="dtabla">
              <table id="rowed22">
              </table>
              <div id="prowed22">
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
