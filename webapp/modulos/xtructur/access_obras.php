<link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap.min.css" type="text/css">
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
  }/*
  .table tr, .table td{
    border: none !important;
  }*/
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
              <div class="navbar-brand" style="color:#333;">Xtructur Version 2.0</div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-4">
            <select id="selobra" class="form-control">
                <option value="0" selected="selected">Selecciona una obra</option>
                <?php 
                    foreach ($obras as $key => $r) { ?>
                      <option value="<?php echo $r['id']; ?>"><?php echo $r['obra']; ?></option>
                <?php } ?>
            </select>
          </div>
          <div class="col-sm-3">
          <button id="btn_acceder" onclick="obra_acceder('<?php echo $modulo; ?>');" class="btn btn-primary btn-sx"> Iniciar</button>
           
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
