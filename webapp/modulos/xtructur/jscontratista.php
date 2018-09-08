<script>
$(function() {
      var formEditingOptions = {
        beforeShowForm: function(form){ 
            $('.FormGrid input').css('width','320px');
            $('.FormGrid select').css('width','328px');
            $('.FormGrid textarea').css('width','324px');
            $('.FormGrid textarea').css('height','80px');
            $('#unidad').numeric(); 
            $('#precio_costo').numeric(); 
            $('#precio_venta').numeric(); 
          },

          closeAfterEdit:true,
        width: 500,
        recreateForm: true
      };

      
      jQuery("#rowed22").jqGrid({
        url:'sql_jscontratista.php?q=3',
        datatype: "json",
        colNames:['','Nombre', 'Domicilio', 'Colonia', 'Estado', 'RFC', 'Ciudad', 'Telefono', 'IMSS'],
        colModel:[
          {name:'id',index:'id', width:55, hidden: true, editable:true},
          {name:'nombre',index:'nombre', width:180, sortable:false,editable:true},
          {name:'domicilio',index:'domicilio', width:180, sortable:false,editable:true}, 
          {name:'colonia',index:'colonia', width:100, sortable:false,editable:true}, 
          {name:'estado',index:'estado', width:100, sortable:false,editable:true}, 
          {name:'rfc',index:'rfc', width:90, sortable:false,editable:true}, 
          {name:'ciudad',index:'ciudad', width:60, sortable:false,editable:true}, 
          {name:'telefono',index:'telefono', width:150, sortable:false,editable:true, hidden:true, editrules: 
            {edithidden:true} 
          },  
          {name:'imss',index:'imss', width:150, sortable:false,editable:true, hidden:true, editrules: 
            {edithidden:true} 
          }, 
        ],
        rowNum:1000000,
        rowList: [], 
        pgbuttons: false,
        pgtext: null,  
        pager: '#prowed22',
        sortname: 'id',
        viewrecords: true,
        sortorder: "desc",
        autowidth: true,
 
        multiselect:true,
        height:260,
        gridComplete: function(){
          var ids = jQuery("#rowed22").jqGrid('getDataIDs');
          console.log(ids);
          for(var i=0;i < ids.length;i++){
            var cl = ids[i];
            var nn2 = $('#rowed22').jqGrid('getCell',ids[i],'nombre');
            be = ''; 
            jQuery("#rowed22").jqGrid('setRowData',ids[i],{icon:be});
          } 
        },
        editurl: "sql_jscontratista.php",


        
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
          },
          afterSubmit: function(response, otro){
            if(response.responseText=='NOMAS'){
              return [false,' &nbsp; Solo puedes agregar una constructora'];
            }else{
              return [true];
            }
          },
          closeAfterAdd:true,
          width: 600
        },
        {width: 480},
        {}
      ).jqGrid('navButtonAdd', '#prowed22', {
        caption: "Exportar Excel",
        buttonicon: "ui-icon-export",
          onClickButton: function() {
                $("#rowed22").jqGrid('exportarExcelCliente',{nombre:"HOJATEST",formato:"excel"});
            },
            position: "last"
        });
});
</script>
<link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap.min.css" type="text/css">
<link rel="stylesheet" href="css/dialogo.css" type="text/css">
<style>
  @media print{
    #imprimir,#filtros,#excel, #botones
    {
      display:none;
    }
    #logo_empresa
    {
      display:block;
    }
    .table-responsive{
      overflow-x: unset;
    }
    #imp_cont{
      width: 100% !important;
    }
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
  .titulo, h4, h3{
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
  /*
  .table tr, .table td{
    border: none !important;
  }
  */
  .ms-container{
    width: 100% !important;
  }
  .ms-selectable, .ms-selection{
    margin-top: 1em;
  }
</style>
<body>
  <div class="container" style="width:100%">
    <div class="row">
      <div class="col-sm-10 col-sm-offset-1">
        <div class="navbar navbar-default"  style="margin-top:10px;">
          <div class="navbar-header">
              <div class="navbar-brand" style="color:#333;">Datos generales constructores</div>
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
