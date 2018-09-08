<script>     
    $(function() {
      var um = $.ajax({
        type:'POST',
        url: "ajax.php",
        data: {opcion:'presu'},
        async: false,
        datatype: 'json'
      }).responseText;
      console.log(um);

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

      id_proyecto=1;
      nn1=$('#nn1').val();
      jQuery("#grid").jqGrid('navGrid','#pcrud',{});
      jQuery("#rowed22").jqGrid({
        url:'sql_desgloce_indirectos.php?q=3',
        datatype: "json",
        colNames:['Obra', 'Cliente', 'Licitacion No.', 'Construye', 'Clave', 'Contrato'],
        colModel:[
          {name:'obra',index:'obra', width:200, sortable:false,editable:true}, 
          {name:'cliente',index:'cliente', width:200, sortable:false,editable:true},
          {name:'licitacion',index:'licitacion', width:140, sortable:false,editable:true},
          {name:'construye',index:'construye', width:140, sortable:false,editable:true}, 
          {name:'clave',index:'clave', width:70, sortable:false,editable:true}, 
          {name:'contrato',index:'contrato', width:0, sortable:false,editable:true, hidden:true, editrules: 
            {edithidden:true} 
          }
          
        ],
        rowNum:10,
        rowList:[10,20,30],
        pager: '#prowed22',
        sortname: 'id',
        viewrecords: true,
        width : 828,   
        sortorder: "desc",
        editurl: "sql_desgloce_indirectos.php",
        caption:"Alta de obra",
        multiselect:true

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
<body>
  <div id="contenedor_xt" style="width:812px;float:left;margin:10px;">
    <div id="dtabla" style="float:left; width:700px; font-size:10px;">
      <table id="rowed22"></table>
      <div id="prowed22"></div>
    </div>
  </div>
</body>