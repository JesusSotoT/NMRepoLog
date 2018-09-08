<?php

      $SQL = "SELECT id,obra FROM constru_generales where borrado=0 ORDER BY obra;";
      $result = $mysqli->query($SQL);
      if($result->num_rows>0) {
        while($row = $result->fetch_array() ) {
          $obrasa[]=$row;
        }
      }else{
        $obrasa=0;
      } 


      $SQL = "SELECT id,obra FROM constru_generales where borrado=0 ORDER BY obra;";
      $result = $mysqli->query($SQL);
      if($result->num_rows>0) {
        while($row = $result->fetch_array() ) {
          $obraen[]=$row;
        }
      }else{
        $obraen=0;
      }    
?>
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
  }/*
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
              <div class="navbar-brand" style="color:#333;">Traspaso de almacenes</div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-3">
            <select id="obra_sal" class="form-control">
              <option selected="selected" value="0">Seleccione obra salida</option>
              <?php 
              if($obrasa!=0){
                foreach ($obrasa as $k => $v) { ?>
                  <option value="<?php echo $v['id']; ?>"><?php echo $v['obra']; ?></option>
                <?php } ?>
              <?php }else{ ?>
                <option value="0">No hay obras dadas de alta</option>
              <?php } ?>
            </select>
          </div>

          <div class="col-sm-3">
            <select id="obra_ent" class="form-control">
              <option selected="selected" value="0">Seleccione obra entrada</option>
              <?php 
              if($obraen!=0){
                foreach ($obraen as $k => $v) { ?>
                  <option value="<?php echo $v['id']; ?>"><?php echo $v['obra']; ?></option>
                <?php } ?>
              <?php }else{ ?>
                <option value="0">No hay obras dadas de alta</option>
              <?php } ?>
            </select> 
          </div>

          <div class="col-sm-3">
            <div id="viejaseleccion" class="col-sm-3">
              <button id="btnGenReq" onclick="funTraspaso();" class="btn btn-primary btn-sx"> Seleccionar obras</button>
            </div>
            <div id="nuevaseleccion" class="col-sm-3" style="display:none;">
              <button id="btnGenReq" onclick="cambiarObras();" class="btn btn-primary btn-sx"> Cambiar de obras</button> 
            </div>
          </div>

          
        </div>
        <div class="row" style="padding-top:14px;">
          <div class="col-sm-12" id="cargagrid">
            
          </div>
        </div>
    </div>
  </div>
</body>


