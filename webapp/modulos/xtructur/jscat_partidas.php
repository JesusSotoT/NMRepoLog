  <script>     
    $(function() {
      var formEditingOptions = {
        beforeShowForm: function(form){ 
            /*$('.FormGrid input').css('width','320px');
            $('.FormGrid select').css('width','328px');*/
            $('#unidad').numeric(); 
          },
          closeAfterEdit:true,
        width: 500,
        recreateForm: false
      };

      jQuery("#row_proforma").jqGrid({
        url:'sql_jscat_partidas.php?q=3',
        datatype: "json",
        colNames:['clave','Partida'],
        colModel:[
          
          {name:'clave',index:'clave', width:80, editable:false,
            editrules: {edithidden:false},
            hidden:false
          },
          {name:'partida', index:'partida', width:120, sortable:false,editable:true},
          
        ],
        loadComplete: function() {

        },

        rowNum:1000000,
        rowList: [], 
        pgbuttons: false,
        pgtext: null,  
        pager: '#prow_proforma',
        sortname: 'id',
        viewrecords: true,
        sortorder: "asc",
        autowidth: true,
        footerrow: true,
        multiselect:true,
        editurl: "sql_jscat_partidas.php",

               
      });

      jQuery("#row_proforma").jqGrid('navGrid',"#prow_proforma",
        {edit:true,add:true,del:true,search:true},
        formEditingOptions,
        {closeAfterAdd:true},
        {},
        {closeAfterSearch:true}
      ).jqGrid('navButtonAdd', '#prow_proforma', {
                caption: "Exportar a Excel",
                buttonicon: "ui-icon-export",
                  onClickButton: function() {
                        $("#row_proforma").jqGrid('exportarExcelCliente',{nombre:"HOJATEST",formato:"excel"});
                        //console.log($("#tb_ejemplo").jqGrid('exportarTextoCliente'));
                    },
                    position: "last"
                });

    });
  </script>
  <body>

    <div id="divcuandohayobra" class="row">
      <div class="col-sm-12">
        <div class="navbar navbar-default"  style="margin-top:10px;">
          <div class="navbar-header">
              <div class="navbar-brand" style="color:#333;">Catalogo partidas</div>
          </div>
        </div>
        <div class="row">
          <div class="col-xs-12 tablaResponsiva">
            <div class="table-responsive" id="dtabla">
              <table id="row_proforma"></table>
              <div id="prow_proforma"></div>
            </div>
          </div>
        </div>
      </div>
    </div>

</body>

  