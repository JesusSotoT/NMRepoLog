<?php 
//ini_set('display_errors', 1);
//echo json_encode($datos['unidades']['rows']);
?>
<!DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Producto</title>
    <link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../libraries/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="../../libraries/quaggajs/css/colors.css">
    <link rel="stylesheet" href="../../libraries/quaggajs/css/fonts.css">
    <link rel="stylesheet" href="../../libraries/quaggajs/css/styles.css">
    <link rel="stylesheet" href="../../libraries/bootstrap-select-1.9.3/dist/css/bootstrap-select.min.css">
    <script src="../../libraries/jquery.min.js"></script>
    <script src="../../libraries/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="../../libraries/bootstrap-select-1.9.3/dist/js/bootstrap-select.min.js"></script>
    <script src="../../libraries/numeric.js"></script>
    <script src="js/producto.js"></script>
    <script src="../../libraries/numeric/jquery.numeric.js"></script>
    <script src="https://rawgit.com/serratus/quaggaJS/441534cd8ba2ff15e4231ed41e77b10d5b3cc9ee/dist/quagga.min.js"></script>
<!--Select 2 -->
    <script src="../../libraries/select2/dist/js/select2.min.js"></script>
    <link rel="stylesheet" type="text/css" href="../../libraries/select2/dist/css/select2.min.css" />

    <!--JOB master -->
   <script src="JOB.js"></script> 
  <!--  <script src="BarcodeReader.js"></script>
    <script src="barcode-reader.jquery.js"></script>-->
    <script src="DecoderWorker.js"></script>
    <script src="exif.js"></script> 

    <!-- DataTables  -->
      <link rel="stylesheet" href="../../libraries/dataTable/css/datatablesboot.min.css">
      <link rel="stylesheet" href="../../libraries/dataTable/css/jquery.dataTables.min.css">
      <link rel="stylesheet" href="../../libraries/dataTable/css/buttons.dataTables.min.css">


  <!-- DataTables  -->
    <script src="../../libraries/dataTable/js/datatables.min.js"></script>
    <script src="../../libraries/dataTable/js/dataTables.bootstrap.min.js"></script>
      <script src="../../libraries/dataTable/js/dataTables.buttons.min.js"></script>
      <script src="../../libraries/dataTable/js/buttons.print.min.js"></script>
      <script src="../../libraries/export_print/buttons.html5.min.js"></script>
      <script src="../../libraries/export_print/jszip.min.js"></script>
    
    <script>
      $(document).ready(function() {uniVenta
        $('#selectProveedor').select2({ width: '250px' }); 
        $('#uniVenta').select2({ width: '250px' }); 
        $('#moneda').select2({ width: '200px' }); 
        $('#selectCaract').select2({ width: '250px' });
        $('#departamento').select2({ width: '350px' });
        $('#familia').select2({ width: '350px' });
        $('#linea').select2({ width: '350px' }); 
        $('.number').numeric(); 
        $('#tipoCom').select2();
        $('#uniCompra').select2({ width: '250px' });
        $('#costeoSelect').select2({ width: '350px' }); 
        $(".numeros").numeric();

        $('#tabla_insumos').DataTable({
        language : {
          search : "<i class=\"fa fa-search\"></i>",
          lengthMenu : "_MENU_ por pagina",
          zeroRecords : "No hay datos.",
          infoEmpty : "No hay datos que mostrar.",
          info : " ",
          infoFiltered : " -> <strong> _TOTAL_ </strong> resultados encontrados",
          paginate : {
            first : "Primero",
            previous : "<<",
            next : ">>",
            last : "Último"
          }
        },
        order: [[0, 'asc']]
      });


            $.ajax({
                url: "ajax.php?c=producto&f=modformu",
                type: "post",
                cache: false,
                contentType: false,
                processData: false
            }).done(function(res){

                    
            });


        var serv = $('#tipoProd').val();
        
        if(serv==2){
          $('#servicio').show();
            $('[name="seriesCheck"]').attr('disabled', 'disabled');
            $('[name="pedimentosCheck"]').attr('disabled', 'disabled');
            $('[name="lotesCheck"]').attr('disabled', 'disabled');
            $('[name="unidadesCheck"]').attr('disabled', 'disabled');
            $('[name="caracCheck"]').attr('disabled', 'disabled');

            //$('.1').attr('disabled', 'disabled');
            $('.2').attr('disabled', 'disabled');
            $('#costeoSelect').attr('disabled', 'disabled'); 

        }




        $("#myForm").on("submit", function(e){
            e.preventDefault();
            var f = $(this);
            var formData = new FormData(document.getElementById("myForm"));
            formData.append("dato", "valor");
            //formData.append(f.attr("name"), $(this)[0].files[0]);
            $.ajax({
                url: "ajax.php?c=producto&f=uploadfile",
                type: "post",
                dataType: "json",
                data: formData,
                cache: false,
                contentType: false,
       processData: false
            })
                .done(function(res){
                  console.log(res);
                  $('#imagenProducto').attr("src",res.direccion);
                  $('#imagenDir').val(res.direccion);
                    
                });
        }); 


        /*if (!/^([0-9])*$/.test(numero))
      alert("El valor " + numero + " no es un número"); */
     /* var takePicture = document.querySelector("#Take-Picture"),
      showPicture = document.createElement("img");
      Result = document.querySelector("#textbit");
      var canvas =document.getElementById("picture");
      var ctx = canvas.getContext("2d");
      JOB.Init();
      JOB.SetImageCallback(function(result) {
        if(result.length > 0){
          var tempArray = [];
          for(var i = 0; i < result.length; i++) {
            tempArray.push(result[i].Format+" : "+result[i].Value);
          }
          Result.innerHTML=tempArray.join("<br />");
        }else{
          if(result.length === 0) {
            Result.innerHTML="Decoding failed.";
          }
        }
      });
      JOB.PostOrientation = true;
      JOB.OrientationCallback = function(result) {
        canvas.width = result.width;
        canvas.height = result.height;
        var data = ctx.getImageData(0,0,canvas.width,canvas.height);
        for(var i = 0; i < data.data.length; i++) {
          data.data[i] = result.data[i];
        }
        ctx.putImageData(data,0,0);
      };
      JOB.SwitchLocalizationFeedback(true);
      JOB.SetLocalizationCallback(function(result) {
        ctx.beginPath();
        ctx.lineWIdth = "2";
        ctx.strokeStyle="red";
        for(var i = 0; i < result.length; i++) {
          ctx.rect(result[i].x,result[i].y,result[i].width,result[i].height); 
        }
        ctx.stroke();
      });
      if(takePicture && showPicture) {
        takePicture.onchange = function (event) {
          var files = event.target.files;
          if (files && files.length > 0) {
            file = files[0];
            try {
              var URL = window.URL || window.webkitURL;
              alert(URL);
              showPicture.onload = function(event) {
                Result.innerHTML="";
                alert(showPicture);
                JOB.DecodeImage(showPicture);
                URL.revokeObjectURL(showPicture.src);
              };
              showPicture.src = URL.createObjectURL(file);
            }
            catch (e) {
              try {
                var fileReader = new FileReader();
                fileReader.onload = function (event) {
                  showPicture.onload = function(event) {
                    Result.innerHTML="";
                    console.log("filereader");
                    JOB.DecodeImage(showPicture);
                  };
                  showPicture.src = event.target.result;
                };
                fileReader.readAsDataURL(file);
              }
              catch (e) {
                Result.innerHTML = "Neither createObjectURL or FileReader are supported";
              }
            }
          }
        };
      } */
        //$('#jobmaster').barcodeReader();
       /* var UPC_SET = {
        "3211": '0',
        "2221": '1',
        "2122": '2',
        "1411": '3',
        "1132": '4',
        "1231": '5',
        "1114": '6',
        "1312": '7',
        "1213": '8',
        "3112": '9'
    };
    
    getBarcodeFromImage = function(imgOrId){
        var doc = document,
            img = "object" == typeof imgOrId ? imgOrId : doc.getElementById(imgOrId),
            canvas = doc.createElement("canvas"),
            width = img.width,
            height = img.height,
            ctx = canvas.getContext("2d"),
            spoints = [1, 9, 2, 8, 3, 7, 4, 6, 5],
            numLines = spoints.length,
            slineStep = height / (numLines + 1),
            round = Math.round;
        canvas.width = width;
        canvas.height = height;
        ctx.drawImage(img, 0, 0);
        while(numLines--){
            console.log(spoints[numLines]);
            var pxLine = ctx.getImageData(0, slineStep * spoints[numLines], width, 2).data,
                sum = [],
                min = 0,
                max = 0;
            for(var row = 0; row < 2; row++){
                for(var col = 0; col < width; col++){
                    var i = ((row * width) + col) * 4,
                        g = ((pxLine[i] * 3) + (pxLine[i + 1] * 4) + (pxLine[i + 2] * 2)) / 9,
                        s = sum[col];
                    pxLine[i] = pxLine[i + 1] = pxLine[i + 2] = g;
                    sum[col] = g + (undefined == s ? 0 : s);
                }
            }
            for(var i = 0; i < width; i++){
                var s = sum[i] = sum[i] / 2;
                if(s < min){ min = s; }
                if(s > max){ max = s; }
            }
            var pivot = min + ((max - min) / 2),
                bmp = [];
            for(var col = 0; col < width; col++){
                var matches = 0;
                for(var row = 0; row < 2; row++){
                    if(pxLine[((row * width) + col) * 4] > pivot){ matches++; }
                }
                bmp.push(matches > 1);
            }
            var curr = bmp[0],
                count = 1,
                lines = [];
            for(var col = 0; col < width; col++){
                if(bmp[col] == curr){ count++; }
                else{
                    lines.push(count);
                    count = 1;
                    curr = bmp[col];
                }
            }
            var code = '',
                bar = ~~((lines[1] + lines[2] + lines[3]) / 3),
                u = UPC_SET;
            for(var i = 1, l = lines.length; i < l; i++){
                if(code.length < 6){ var group = lines.slice(i * 4, (i * 4) + 4); }
                else{ var group = lines.slice((i * 4 ) + 5, (i * 4) + 9); }
                var digits = [
                    round(group[0] / bar),
                    round(group[1] / bar),
                    round(group[2] / bar),
                    round(group[3] / bar)
                ];
                code += u[digits.join('')] || u[digits.reverse().join('')] || 'X';
                if(12 == code.length){ return code; break; }
            }
            if(-1 == code.indexOf('X')){ return code || false; }
        }
        return false;
    }
        Quagga.init({
            inputStream: {
                name: "Live",
                type: "LiveStream",
                constraints: {
                    width: 640,
                    height: 480,
                    facing: "environment"
                }
            },
            locator: {
                patchSize: "medium",
                halfSample: true
            },
            numOfWorkers: 4,
            locate: true,
            decoder : {
                readers: ["code_128_reader"]
            }
        }, function() {
            Quagga.start();
        });

        Quagga.onDetected(function(result) {
            var code = result.codeResult.code;
            //alert(code);
            document.querySelector(".found").innerHTML = code;
        });/*
         /*$('[name="seriesCheck"]').click(function() {
              if($(this).is(':checked')) {
                $(".characteristics").attr('disabled', 'disabled');
                $(".unity").attr('disabled', 'disabled');
              } else {
                $(".characteristics").removeAttr('disabled');
                $(".unity").removeAttr('disabled');
              }
          }); */
         $('.propiedades').click(function(){

              if($('[name="unidadesCheck"]').is(':checked')){
                //$('.2').attr('disabled', 'disabled');
                //$('.3').attr('disabled', 'disabled');
              
              } else if($('[name="seriesCheck"]').is(':checked')){
                $('.1').attr('disabled', 'disabled');
                //$('.2').attr('disabled', 'disabled');
                $('.5').attr('disabled', 'disabled');
                $('#costeoSelect > option[value="1"]').prop('selected', false);
                $('#costeoSelect > option[value="6"]').prop('selected', true);
                $('#costeoSelect').select2({ width: '350px' }); 
                $('#costeoSelect').attr('disabled', 'disabled');
              }else if($('[name="caracCheck"]').is(':checked')){
                $('.1').attr('disabled', 'disabled');
                //$('.3').attr('disabled', 'disabled');
              }else if($('[name="lotesCheck"]').is(':checked')){
                //$('.3').attr('disabled', 'disabled');
              }else{
                $('.propiedades').prop('disabled', false);
                $('.1').prop('disabled', false);
                //$('.2').prop('disabled', false);
                $('#costeoSelect > option[value="6"]').prop('selected', false);
                $('#costeoSelect > option[value="1"]').prop('selected', true);
                $('#costeoSelect').prop('disabled', false);
                
                $('#costeoSelect').select2({ width: '350px' }); 

              }

          });

          $('.propiedades').click(function(){
            if($('[name="pesoCheck"]').is(':checked')){
              $("#boxes").show();
            }else{
              $("#boxes").hide();
            }
          });

          $('.propiedades').click(function(){
            if($('[name="caracCheck"]').is(':checked')){
              $("#divcart").show();
            }else{
              $("#divcart").hide();
            }
          });

          $('.propiedades').click(function(){
            if($('[name="unidadesCheck"]').is(':checked')){
              $("#divunid").show();
            }else{
              $("#divunid").hide();
            }
          });

            $('input[type=checkbox]').each(function () {
                checar = $(this).attr('checar');
                if(checar==1){
                  $(this).trigger('click');
                }

            }); 

            editaBloqueo();
          
      });

    function editaBloqueo(){
      
      if($('#editable').val()==0 && $('#editable').val()!==''){
        $('.propiedades').prop('disabled', true);
        $('#moneda').prop('disabled', true);

      }else{

      }


      /*$(".precioBaseComision").select2({
        placeholder: "Proveedores",
        delay: 250,
        width:'100%',
        ajax: {
            url: 'ajax.php?c=producto&f=buscarPrecioProveedor',
            type: 'GET',
            dataType: 'json',

            data: function(params) {
                return { producto : $('#id').val(),
                    patronProveedor: params.term };
            },

            processResults: function (data) {
                $("#precioBase").empty();
                return { results: data.rows };
            },
            cache: true
        },
        templateResult: function format(state) {
            return  state.text;
        },
        templateSelection: function format(state) {
            return  state.text;
        }
    });*/
    }
    </script>
    </head>
<body>
  <br>
<div class="container well">
  <div class="row">
    <div class="col-sm-1">
        <button class="btn btn-default" onclick="back();"><i class="fa fa-arrow-left" aria-hidden="true"></i> Regresar</button>
    </div>
    
    <div class="col-sm-1">
        <div id="btnSave">
          <button type="submit" class="btn btn-primary" onclick="guardar();"><span class="glyphicon glyphicon-floppy-disk"></span> Guardar</button>
        </div>
        <div class="col-sm-1" style="display:none;" id="loadingPro">
          <i class="fa fa-refresh fa-spin fa-3x"></i>
        </div>
    </div>
  </div>
  <!--<div class="row">
    <div class="col-sm-12">
      <h3>Producto Insumos</h3>
    </div>
  </div> -->
  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">Productos - Insumos - Servicios <?php 
                  if(isset($datosProducto)){echo '('.$datosProducto['basicos'][0]['nombre'].' - $'.number_format($datosProducto['basicos'][0]['precio'],2).')';}?></h3>
    </div>
    <input type="hidden" id="idProducto" value="<?php echo $idProducto; ?>">
    <div class="panel-body">

  
  <!-- div de los Tabs -->
    <div id="tabsProduct">  
      <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#basicos">Datos Básicos</a></li>
        <!--<li><a data-toggle="tab" href="#listaPrecios">Lista de Precios</a></li>-->
        <li><a data-toggle="tab" href="#menu2">Proveedores</a></li>
        <li><a data-toggle="tab" href="#atributos">Atributos</a></li>
        <li><a data-toggle="tab" href="#categorias">Categorías</a></li>
        <!--<li><a data-toggle="tab" href="#caracteristicas">Caracteristicas</a></li>-->
        <!--<li><a data-toggle="tab" href="#unidades">Unidades de Medida</a></li>-->
        <li><a data-toggle="tab" href="#costeo">Métodos de Costeo</a></li>
        <li><a data-toggle="tab" href="#descripcion">Descripción</a></li>
        <!--<li><a data-toggle="tab" href="#impuestos">Impuestos</a></li>-->
        <li><a data-toggle="tab" href="#comisiones">Comisiones</a></li>
        <li><a data-toggle="tab" href="#satClaves">Claves SAT</a></li>
        <li style="display: none;"><a data-toggle="tab" href="#formuProds">Formulacion productos</a></li>
      </ul>
    </div>
  <!-- Termina div de los Tabs -->  
  <!-- Div contendro de los Contenidos -->
  <div class="tab-content" style="height:400px;">
    <div id="basicos" class="tab-pane fade in active">
      <div class="form-horizontal col-sm-12">
        <div class="form-group">

            
              <div class="col-sm-6">
                  <div class="col-sm-11" style="padding: 0px">
                    <div class="col-sm-5">
                    <label class="control-label" for="email" >ID</label>
                    <input type="text" class="form-control" id="id" readonly placeholder="(Autonumérico)" value="<?php 
                         if(isset($datosProducto)){echo $datosProducto['basicos'][0]['id'];}?>" >
                    </div> 
                    <div class="col-sm-3"><br><br>
                    <span class="label label-success">Activo</span>
                    </div> 
                  </div>

                  <div class="col-sm-11">
                    <input type="hidden" id="imagenDir" name="imagen" value="<?php 
                       if(isset($datosProducto)){echo $datosProducto['basicos'][0]['ruta_imagen'];}?>">                     
                        
                    <input type="hidden" id="editable" value="<?php 
                       if(isset($datosProducto)){echo $editable['editable']; }else{echo '1';}?>">

                    <label class="control-label" for="email">Nombre</label>
                      <input type="text" class="form-control" id="nombre"  value="<?php 
                        if(isset($datosProducto)){echo $datosProducto['basicos'][0]['nombre'];}?>">
                      <label class="control-label" for="pwd">Código</label>
                      <div class="input-group" id="clienteSelect">
                        <span class="input-group-btn">
                          <button class="btn btn-default" type="button" onclick="escanea();">Código</button>
                        </span>
                        <input type="text" class="form-control" id="codigo" value="<?php 
                          if(isset($datosProducto)){echo $datosProducto['basicos'][0]['codigo'];}?>">
                      </div>
                  </div>

                  <div class="col-sm-11">
                    <label>Tipo de Producto</label>
                      <select id="tipoProd" class="form-control" onchange="(function(){servicio();satGrupo();satClase();satClave();cambiaTab();})()">
                         <option value="1" <?php if(isset($datosProducto)){ if($datosProducto['basicos'][0]['tipo_producto']==2){ echo 'selected'; } } else {echo 'selected';}?>>Producto</option>
                         <option value="2" <?php if(isset($datosProducto)){ if($datosProducto['basicos'][0]['tipo_producto']==2){ echo 'selected'; } }?>>Servicio</option>
                         <option value="3" <?php if(isset($datosProducto)){ if($datosProducto['basicos'][0]['tipo_producto']==3){ echo 'selected'; } }?>>Insumo</option>
                         <option value="4" <?php if(isset($datosProducto)){ if($datosProducto['basicos'][0]['tipo_producto']==4){ echo 'selected'; } }?>>Insumo preparado</option>
                         <option value="5" <?php if(isset($datosProducto)){ if($datosProducto['basicos'][0]['tipo_producto']==5){ echo 'selected'; } }?>>Receta</option>
                         <option value="6" <?php if(isset($datosProducto)){ if($datosProducto['basicos'][0]['tipo_producto']==6){ echo 'selected'; } }?>>Kit</option>
                         <option value="7" <?php if(isset($datosProducto)){ if($datosProducto['basicos'][0]['tipo_producto']==7){ echo 'selected'; } }?>>Combo</option>
                         <option value="8" <?php if(isset($datosProducto)){ if($datosProducto['basicos'][0]['tipo_producto']==8){ echo 'selected'; } }?>>Material transformado</option>
                         <option value="9" <?php if(isset($datosProducto)){ if($datosProducto['basicos'][0]['tipo_producto']==9){ echo 'selected'; } }?>>Producto fabricado</option>
                      </select>
                   </div>

                   <div class="col-sm-11">
                    <div id="servicio" style="display:none;">
                      <label>Costo de Servicio</label>
                      <input type="text" id="costoServicio" class="form-control numeros" value="<?php 
                        if(isset($datosProducto)){echo $datosProducto['basicos'][0]['costo_servicio'];}?>">
                    </div>
                  </div>  

                  <h4>Configuración de precio base</h4>

                  <div class="col-sm-3">                  
                         <label class="control-label">Precio General</label>
                         <input type="text" class="form-control number numeros" id="precio" value="<?php 
                         if(isset($datosProducto)){echo $datosProducto['basicos'][0]['precio'];}?>" >                                                    
                  </div>
                  <div class="col-sm-1">
                      <label class="control-label" style="color:white">.</label>
                      <button class="btn btn-default" onclick="calcula();"><i class="fa fa-calculator" aria-hidden="true"></i></button> 
                  </div>






















            <?php 

              if(count($sucursal) > 1){

             ?>

                  <div class="col-sm-3">                  
                         <label class="control-label">Sucursal</label><br>                     
                         <select class="form-control precios" id="selectsucursal">
                             <?php 
                              foreach ($sucursal as $key => $value) {
                                 echo '<option value="'.$value['idSuc'].'">'.$value['nombre'].'</option>';
                              }
                             ?>
                         </select>  

                  </div>
            <?php 
               }else{
                echo '<input type="hidden" id="idsucursal" value="'.$sucursal[0]['idSuc'].'">';
                echo '<input type="hidden" id="nomsucursal" value="'.$sucursal[0]['nombre'].'">';
               }
             ?>
                  <div class="col-sm-4">                  
                         <label class="control-label">Precio Sucursal</label>
                         <!-- <select class="form-control precios" id="listaPrecio">
                              <option value="0">-Selecciona Lista-</option>
                             <?php 
                              foreach ($departamento['listPre'] as $key => $value) {
                                 echo '<option value="'.$value['id'].'">'.$value['nombre'].'</option>';
                              }
                             ?>
                         </select> -->

                        <input type="text" class="form-control number numeros" id="preciosucursal" value="">
                          <!--<button class="btn btn-default precios" onclick="agregaLista();">Agregar</button>-->
                  </div>

                  <div class="col-sm-1">
                      <label class="control-label" style="color:white"><br></label>
                      <button class="btn btn-default" onclick="agregaPrecioSucursal();"><i class="fa fa-plus" aria-hidden="true"></i></button> 
                  </div>

                  <div class="row">
                      <div class="col-sm-12">
                         <table id="preciosSucursal" class="table">
                            <thead>
                               <tr>
                                  <th></th>
                                  <th>Sucursal</th>
                                  <th>Precio</th>
                               </tr>
                            </thead>
                            
                            <tbody>
                              <?php 
                                    if(isset($datosProducto)){
                                      //echo json_encode($datosProducto['precios']);
                                      foreach ($datosProducto['preciosBase'] as $key => $value) {

                                          /*echo '<tr id="idLp_'.$valueP['id_lista'].'" idsuc="'.$valueP['idSuc'].'" idListPre="'.$valueP['id_lista'].'" >';
                                          echo '<td><span onclick="removePrecio('.$valueP['id_lista'].');" class="glyphicon glyphicon-remove"></span></td>';
                                          echo '<td>'.$valueP['sucursal'].'</td>';
                                          echo '<td><input type="number" min="0"  id="id_'.$valueP['id_lista'].'_'.$valueP['idSuc'].'" value='.$valueP['precio'].'></input></td>';
                                          echo '</tr>';*/

                                          echo '<tr idsuc="'.$value['sucursal'].'" id="idSp_'.$value['sucursal'].'">';
                                          echo '<td><span class="glyphicon glyphicon-remove" onclick="removePrecioSucursal('.$value['sucursal'].');"></span></td>';
                                          echo ' <td>'.$value['nombre'].'</td> ';
                                          echo '<td><input type="number" min="0" id="id_'.$value['sucursal'].'" value='.$value['precio'].'></input></td>';
                                          echo '</tr>';
                                        
                                      }
                                    } 
                              ?>  
                              </tbody>
                         </table>
                      </div> 
                  </div> 
















            <h4>Configuración de listas de precio</h4>

                  
            <?php 

              if(count($sucursal) > 1){

             ?>

                  <div class="col-sm-3">                  
                         <label class="control-label">Sucursal</label><br>                     
                         <select class="form-control precios" id="selectsuc">
                             <?php 
                              foreach ($sucursal as $key => $value) {
                                 echo '<option value="'.$value['idSuc'].'">'.$value['nombre'].'</option>';
                              }
                             ?>
                         </select>   
                  </div>
            <?php 
               }else{
                echo '<input type="hidden" id="idsuc" value="'.$sucursal[0]['idSuc'].'">';
                echo '<input type="hidden" id="nomsuc" value="'.$sucursal[0]['nombre'].'">';
               }
             ?>
                  <div class="col-sm-4">                  
                         <label class="control-label">Lista de Precios</label>
                         <select class="form-control precios" id="listaPrecio">
                              <option value="0">-Selecciona Lista-</option>
                             <?php 
                              foreach ($departamento['listPre'] as $key => $value) {
                                 echo '<option value="'.$value['id'].'">'.$value['nombre'].'</option>';
                              }
                             ?>
                         </select>
                          <!--<button class="btn btn-default precios" onclick="agregaLista();">Agregar</button>-->
                  </div>

                  <div class="col-sm-1">
                      <label class="control-label" style="color:white">.</label>
                      <button class="btn btn-default" onclick="agregaLista();"><i class="fa fa-plus" aria-hidden="true"></i></button> 
                  </div>

                  <div class="row">
                      <div class="col-sm-12">
                         <table id="preciosList" class="table">
                            <thead>
                               <tr>
                                  <th></th>
                                  <th>Sucursal</th>
                                  <th>Lista de precio</th>
                                  <th>Precio</th>
                               </tr>
                            </thead>
                            
                            <tbody>
                              <?php 
                                    if(isset($datosProducto)){
                                      //echo json_encode($datosProducto['precios']);
                                      foreach ($datosProducto['precios'] as $keyP => $valueP) {

                                        if( $valueP['tipo'] == "2" ) {
                                          echo '<tr id="idLp_'.$valueP['id_lista'].'_'.$valueP['idSuc'].'" idsuc="'.$valueP['idSuc'].'" idListPre="'.$valueP['id_lista'].'" >';
                                          echo '<td><span onclick="removePrecio('.$valueP['id_lista'].','.$valueP['idSuc'].');" class="glyphicon glyphicon-remove"></span></td>';
                                          echo '<td>'.$valueP['sucursal'].'</td>';
                                          echo '<td>'.$valueP['nombre'].'</td>';
                                          echo '<td><input type="number" min="0"  id="id_'.$valueP['id_lista'].'_'.$valueP['idSuc'].'" value='.$valueP['precio'].'></input></td>';
                                          echo '</tr>';
                                        }
                                        else {

                                          
if ( in_array($valueP['idSuc'],   array_column($datosProducto['preciosBase'], 'sucursal')) ){
  $keyTmp = array_search($valueP['idSuc'],   array_column($datosProducto['preciosBase'], 'sucursal'));

  $precioBase = floatval( $datosProducto['preciosBase'][$keyTmp]['precio'] );
}
else {
  $precioBase = floatval( $datosProducto['basicos'][0]['precio'] );
}

                                          $descuento = $precioBase * $valueP['porcentaje'] / 100;
                                          if($valueP['descuento'] == 1){
                                              $precioFinal = $precioBase - $descuento;
                                          }else{
                                              $precioFinal = $precioBase + $descuento;
                                          }

                                          echo '<tr id="idLp_'.$valueP['id_lista'].'_'.$valueP['idSuc'].'" idsuc="'.$valueP['idSuc'].'" idListPre="'.$valueP['id_lista'].'">';
                                          echo '<td><span onclick="removePrecio('.$valueP['id_lista'].','.$valueP['idSuc'].');" class="glyphicon glyphicon-remove"></span></td>';                                          
                                          echo '<td>'.$valueP['sucursal'].'</td>';
                                          echo '<td>'.$valueP['nombre'].'</td>';
                                          echo '<td>$'.$precioFinal.'</td>';
                                          echo '</tr>';
                                        }
                                        
                                        
                                      }
                                    } 
                              ?>  
                              </tbody>
                         </table>
                      </div> 
                  </div>













                  
                  <div class="row">
                    <!--
                    <div class="col-sm-3">
                          <label class="control-label">Comision %</label>
                          <input type="text" class="form-control comision" id="comision" value="<?php 
                          if(isset($datosProducto)){echo $datosProducto['basicos'][0]['comision'];}?>" >
                    </div>
                    <div class="col-sm-4">
                        <label class="control_label">Tipo de Comision</label>
                        <select id="tipoCom" class="form-control">
                          <option value="0" <?php if(isset($datosProducto)){ if($datosProducto['basicos'][0]['tipo_comision']==0){ echo 'selected'; } }?> >-Selecciona tipo-</option>
                          <option value="1" <?php if(isset($datosProducto)){ if($datosProducto['basicos'][0]['tipo_comision']==1){ echo 'selected'; } }?> >Cobranza</option>
                          <option value="2" <?php if(isset($datosProducto)){ if($datosProducto['basicos'][0]['tipo_comision']==2){ echo 'selected'; } }?> >Venta</option>
                        </select>
                    </div>
                    -->
                    <div class="col-sm-6">
                         <label class="control-label">Moneda</label>
                         <select id="moneda" class="form-control">
                           <?php 
                              foreach ($moneda as $keyCoin => $valueCoin) {
                              if(isset($datosProducto)){
                                if($datosProducto['basicos'][0]['id_moneda']==$valueCoin['coin_id']){
                                  echo '<option value="'.$valueCoin['coin_id'].'" selected>'.$valueCoin['description'].'</option>';
                                }
                              }
                                echo '<option value="'.$valueCoin['coin_id'].'">'.$valueCoin['description'].'</option>';
                              }
                           ?>
                         </select>
                    </div>
                    <div class="col-sm-6">
                      <div class="form-group">
                        <div class="form-check">
                          <label class="form-check-label">
                          <input id="consigna" name="point" value="1" type="checkbox" <?php 
                        if(isset($datosProducto)){
                            if($datosProducto['basicos'][0]['consigna']==1){
                              echo 'checked';
                            } 
                          }
                          ?>
                          >Producto Consigna
                          </label>
                        </div>
                      </div>
                    </div>

                  </div>
              
              </div> <!--FIN DATOS LEFT-->
              

             
              <div class="col-sm-6" style="text-align: left; padding-left: 50px;">
                
                  <div class="row">
                          <div class="col-sm-12" id="imagen-producto" style="text-align: center;">
                            <!--<img width="250" height="250" src="noimage.jpeg" id='imagenProducto'> -->
                            <?php   if(isset($datosProducto))
                                { 
                                  if($datosProducto['basicos'][0]['ruta_imagen']==''){
                                    echo '<img id="imagenProducto" width="250px" height="250px" src="noimage.jpeg">';
                                  }else{
                                    echo '<img id="imagenProducto" width="250px" height="250px" src="'.$datosProducto['basicos'][0]['ruta_imagen'].'">';
                                  }
                                }
                                else
                                {
                            
                                 echo '<img id="imagenProducto" width="250px" height="250px" src="noimage.jpeg">';
                                } 
                            ?> 
                          </div>              
                    </div>
                    <div class="row"">
                        <form id="myForm"  method="post" enctype="multipart/form-data">
                            <div class="row">
                            <div class="col-sm-6">
                             <!-- <input type="hidden" id="imagen" name="imagen" value=""> -->
                                <div style="padding-left:10%">
                                  <input type="file" size="40" name="myfile">
                                </div>
                            </div>
                            </div>
                            <div class="row">
                            <div class="col-sm-6">
                              <div style="padding-left:10%">
                                <button type="submit" class="btn btn-primary btnMenu" id="btnimagen">Agregar imagen</button>
                              </div>
                            </div>
                            </div>
                        </form>             
                    </div>
                    <div class="row">
                      <div class="col-sm-12" style="padding-left:0%">
                          <blockquote>
                            <p>Para mejor visualización, se recomienda utilizar imágenes cuadradas.</p>
                          </blockquote>
                      </div>
                    </div>

              </div> <!--FIN DATOS RIGHT-->
              

              
            
            

            
        
       <!-- <div class="form-group">
          <label for="ejemplo_archivo_1">Adjuntar un archivo</label>
          <input type="file" id="ejemplo_archivo_1">
          <p class="help-block">Ejemplo de texto de ayuda.</p>
        </div> -->
            <!--<div class="row">
                  <div class="col-md-12 text-center" id="imagen-producto">
                    <img width="250" height="250" src="noimage.jpeg" id='imagenProducto'>
                    <?php /*  if(isset($datos_producto))
                        {
                          echo '<img width="250" height="250" src="'.base_url().$datos_producto[0]->imagen.'">';
                        }
                        else
                        {
                    ?>
                          <img width="250" height="250" src="<?php echo base_url();?>images/noimage.jpeg">
                    <?php   } */
                    ?> 
                  </div>
                </div>
                <div class="row">
                <div class="col-sm-12">
                <form id="myForm"  method="post" enctype="multipart/form-data">
                    <div class="row">
                    <div class="col-md-6">
                     <!-- <input type="hidden" id="imagen" name="imagen" value=""> -->
                      <!--  <input type="file" size="40" name="myfile">
                    </div>
                    </div>
                    <div class="row">
                    <div class="col-md-6">
                        <button type="submit" class="btn btn-primary btnMenu" id="btnimagen">Agregar imagen</button>
                    </div>
                    </div>
                </form>
                </div>
              </div> -->
        </div>
      </div>
    </div>

  
  <!--
    <div id="listaPrecios" class="tab-pane fade">
      <h4>Lista de precios</h4>
      <div class="row">
        <div class="col-sm-6">
          <div class="form-inline">
            <select class="form-control precios" id="listaPrecio">
              <option value="0">-Selecciona Lista-</option>
             <?php 
              foreach ($departamento['listPre'] as $key => $value) {
                 echo '<option value="'.$value['id'].'">'.$value['nombre'].'</option>';
              }
             ?>
            </select>            
          </div>
        </div>
        <div class="col-sm-6">
          <button class="btn btn-default precios" onclick="agregaLista();">Agregar</button>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-6">
          <table id="preciosList" class="table">
            <thead>
              <tr>
                <th></th>
                <th>Lista de precio</th>
                <th>Precio</th>
              </tr>
            </thead>
            <tbody>
            <?php 
                  if(isset($datosProducto)){
                    //print_r($datosProducto['precios']);
                    foreach ($datosProducto['precios'] as $keyP => $valueP) {

                      if( $valueP['tipo'] == "2" ) {
                        echo '<tr idListPre="'.$valueP['id_lista'].'" id="idLp_'.$valueP['id_lista'].'">';
                        echo '<td><span class="glyphicon glyphicon-remove" onclick="removePrecio('.$valueP['id_lista'].');"></span></td>';
                        echo '<td>'.$valueP['nombre'].'</td>';
                        echo '<td><input type="number" min="0"  id="id_'.$valueP['id_lista'].'" value='.$valueP['precio'].'></input></td>';
                        echo '</tr>';
                      }
                      else {
                        $descuento = $datosProducto['basicos'][0]['precio'] * $valueP['porcentaje'] / 100;
                        if($valueP['descuento'] == 1){
                            $precioFinal = $datosProducto['basicos'][0]['precio'] - $descuento;
                        }else{
                            $precioFinal = $datosProducto['basicos'][0]['precio'] + $descuento;
                        }

                        echo '<tr idListPre="'.$valueP['id_lista'].'" id="idLp_'.$valueP['id_lista'].'">';
                        echo '<td><span class="glyphicon glyphicon-remove" onclick="removePrecio('.$valueP['id_lista'].');"></span></td>';
                        echo '<td>'.$valueP['nombre'].'</td>';
                        echo '<td>$'.$precioFinal.'</td>';
                        echo '</tr>';
                      }
                      
                      
                    }
                  } 
            ?>  
            </tbody>
          </table>
        </div> 
      </div>
    </div>
  -->
    <div id="menu2" class="tab-pane fade">
      <h4>Selecciona proveedores</h4>
      <div class="row">
        <div class="col-sm-3">
            <select id="selectProveedor" class="prove">
              <option value="0">-- Selecciona Proveedor --</option>
              <?php
                foreach ($proveedores as $key => $value) {
                  echo '<option value="'.$value['idPrv'].'">'.$value['codigo'].'/'.$value['razon_social'].'</option>';
                }
              ?>
            </select>
        </div>
        <div class="col-sm-3">
          <input type="text" class="form-control number numeros prove" id="provePrecio">
        </div>
        <div class="col-sm-3">
           <button class="btn btn-default prove" onclick="agregaProve();">Agregar</button>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-6">
          <table id="provesList" class="table">
            <thead>
              <tr>
                <th></th>
                <th>Proveedor</th>
                <th>Precio</th>
              </tr>
            </thead>
            <tbody>
              <?php 
              foreach ($datosProducto['proves'] as $keyx => $valuex) {
                  echo '<tr idProved="'.$valuex['idPrv'].'" id="idPr_'.$valuex['idPrv'].'">';
                  echo '<td><span class="glyphicon glyphicon-remove" onclick="removeProve('.$valuex['idPrv'].');"></span></td>';
                  echo '<td>'.$valuex['razon_social'].'</td>';
                  echo '<td><input type="hidden" id="pro_'.$valuex['idPrv'].'" value="'.$valuex['costo'].'">'.$valuex['costo'].'</td>';
                  echo '</tr>';
              }
              ?>
            </tbody>
          </table>
        </div>  
      </div>
    </div>

    <!-- ATRIBUTOS -->
      <div id="atributos" class="tab-pane fade">
         <div class="col-md-3">
            <!--
            <label>Tipo de Atributo</label><br>
            <select id="atributo">
               <option value="0">-- Seleciona tipo --</option>
               </select>
            -->
            <div class="col-sm-11">
            <div class="checkbox">
               <span class="glyphicon glyphicon-baby-formula"></span>
               <label><input type="checkbox" name="unidadesCheck" class="propiedades 1" id="unis" <?php echo $unidad; if($datosProducto['basicos'][0]['maximos'] >  0 || $datosProducto['basicos'][0]['minimos'] > 0 ){ echo 'checked';} ?>>Unidades de Medida</label>
            </div>
            <div class="checkbox">
               <span class="glyphicon glyphicon-tags"></span>
               <label><input type="checkbox" name="caracCheck" class="propiedades 2" id="checkcar" <?php echo $caracter; if($caractE == 1){echo 'checked';}else{echo '';}?>>Características</label>
            </div>
            <div class="checkbox">
               <span class="glyphicon glyphicon-list"></span>
               <label><input type="checkbox" name="seriesCheck" class="propiedades 3" id="checkSeries" <?php echo $series; if($datosProducto['basicos'][0]['series'] == 1){ echo 'checked';}?> >Series</label>
            </div>
            <div class="checkbox">
               <span class="glyphicon glyphicon-list-alt"></span>
               <label><input type="checkbox" name="pedimentosCheck" class="propiedades 4" id="checkPedimentos" <?php echo $pedimentos; if($datosProducto['basicos'][0]['pedimentos'] == 1){ echo 'checked';}?> >Pedimentos</label>
            </div>
            <div class="checkbox">  
               <span class="glyphicon glyphicon-briefcase"></span>
               <label><input type="checkbox" name="lotesCheck" class="propiedades 5" id="checkLotes" <?php echo $lotes; if($datosProducto['basicos'][0]['lotes'] == 1){ echo 'checked';}?> >Lotes</label>
            </div>
            <div class="checkbox">  
               <span class="glyphicon glyphicon-scale"></span>
               <label><input type="checkbox" name="pesoCheck" class="propiedades 6" id="checkPeso" <?php echo $peso; if($datosProducto['basicos'][0]['peso_dimension'] == 1){ echo 'checked';}?> >Peso y Dimensiones</label>
            </div>
         </div>
         </div>
         <div class="col-md-9">
            <div id="divcart">
              <input type="hidden" id="caractE" value="<?php echo $caractE; ?>">
               <h4>Característica Padre</h4>
                  <div class="row">
                     <div class="col-sm-4">
                        <div class="form-inline">                  
                           <select id="selectCaract" class="form-control characteristics 2">
                              <option value="0">-Selecciona Característica-</option>
                              <?php 
                                foreach ($caracteristicas as $key => $value) {
                                   echo '<option value="'.$value['id'].'">'.$value['nombre'].'</option>';
                                }
                              ?>
                           </select>                     
                        </div>
                     </div>
                      <div class="col-sm-1">
                         <button class="btn btn-default characteristics 2" onclick="agregaCarac();">Agregar</button>
                     </div>
                  </div>
           
                  <div class="row">
                     <div class="col-sm-6">
                        <table id="caracList" class="table">
                           <thead>
                              <tr>
                                 <th></th>
                                 <th>Características</th>
                              </tr>
                           </thead>
                           <tbody>
                              <?php 
                              //print_r($datosProducto['caracteristicas']);
                                    if(isset($datosProducto)){
                                      //echo $datosProducto['precios'];
                                      foreach ($datosProducto['caracteristicas'] as $keyP => $valueP) {
                                        echo '<tr idcart="'.$valueP['id'].'" id="carac_'.$valueP['id'].'">';
                                        echo '<td><span class="glyphicon glyphicon-remove-circle" onclick="removeCarac('.$valueP['id'].')"></span></td>';
                                        echo '<td>'.$valueP['nombre'].'</td>';
                                        echo '</tr>';
                                      }
                                    } 
                              ?> 
                           </tbody>
                        </table>
                     </div>  
                  </div>
            </div>
               <br> 
                
               <div id="divunid">            
                  <div class="row">
                     <div class="col-sm-4">
                        <label>Unidad de Compra</label><br>    
                        <select  id="uniCompra" <?php if(isset($editable)){ if($editable['editable']==0){ echo 'disabled'; } }?> class="1">
                           <?php 
                              foreach ($unidades as $keyuni => $valueUni) {
                                 if(isset($datosProducto)){
                                    if($datosProducto['basicos'][0]['id_unidad_compra']==$valueUni['id']){
                                       echo '<option value="'.$valueUni['id'].'" selected >'.$valueUni['nombre'].'</option>';
                                    }
                                 }
                              echo '<option value="'.$valueUni['id'].'">'.$valueUni['nombre'].'</option>';
                              }
                           ?> 
                        </select>
                     </div>

                     <div class="col-sm-6">
                        <label>Unidad de Venta</label><br>
                        <select  id="uniVenta" <?php if(isset($editable)){ if($editable['editable']==0){ echo 'disabled'; } }?> class="1" onchange="cambiauventa();"  >
                        <?php 
                           foreach ($unidades as $keyuni => $valueUni) {
                              if(isset($datosProducto)){
                                 if($datosProducto['basicos'][0]['id_unidad_venta']==$valueUni['id']){
                                    echo '<option value="'.$valueUni['id'].'" selected >'.$valueUni['nombre'].'</option>';
                                 }
                              }
                              echo '<option value="'.$valueUni['id'].'">'.$valueUni['nombre'].'</option>';
                           }
                        ?> 
                        </select>
                     </div>
                  </div>

                  <div class="row">
                     <div class="col-sm-12">
                        <div class="form-inline">
                        <br>
                           <label>Mínimo</label>
                           <input type="text" id="minimo" class="form-control number numeros 1" value="<?php 
                              if(isset($datosProducto)){echo $datosProducto['basicos'][0]['minimos'];}?>">
                           <label>Máximo</label>
                           <input type="text" id="maximo" class="form-control number numeros 1" value="<?php 
                              if(isset($datosProducto)){echo $datosProducto['basicos'][0]['maximos'];}?>">
                        </div>
                     </div>
                  </div>
               </div> 

               <div id="boxes" style="display: none;"> 
                  <div class="row">
                  <div class="col-sm-12">
                        &nbsp;
                     </div>
                     <div class="col-sm-3">
                        <input id="boxPeso" type="text" class="form-control number numeros 1" placeholder="Peso (Kg)" style="margin-top:10px; width: 120px;" value="<?php 
                              if(isset($datosProducto)){echo $datosProducto['basicos'][0]['pesokg'];}?>" >
                     </div>
                     <div class="col-sm-9" style="margin-top: 15px;">
                        Utilizar medidas en Kilogramos y Centimetros
                     </div>
                     <div class="col-sm-12">
                        &nbsp;
                     </div>
                     <div class="col-sm-2">
                        <input id="boxAlto" type="text" class="form-control number numeros 1" placeholder="Alto" style="margin-top:75px; width: 120px;" value="<?php 
                              if(isset($datosProducto)){echo $datosProducto['basicos'][0]['altocm'];}?>" >
                     </div>
                     <div class="col-sm-10">
                        <img src="boxes.png" width="250" height="170">
                     </div>
                     <div class="col-sm-3">
                        <input id="boxLargo" type="text" class="form-control number numeros 1" placeholder="Largo" style="margin-left:80px; width: 120px;" value="<?php 
                              if(isset($datosProducto)){echo $datosProducto['basicos'][0]['largocm'];}?>" >
                     </div>
                     <div class="col-sm-9">
                        <input id="boxAncho" type="text" class="form-control number numeros 1" placeholder="Ancho" style="margin-left:100px; width: 120px;" value="<?php 
                              if(isset($datosProducto)){echo $datosProducto['basicos'][0]['anchocm'];}?>" >
                     </div>
                  </div>
               </div>
         
         </div>
      </div>

    <!-- ATRIBUTOS FIN -->


    <div id="categorias" class="tab-pane fade">
      <div class="row">
        <div class="col-sm-6">
          <h3>Categorías</h3>
          <div class="row">
              <div class="col-sm-4">
                <label>Departamento</label>
              </div>
              <div class="col-sm-5">
                <select class="form-control catego" id="departamento" onchange="buscaFam();">
                  <option value="0">-Selecciona Departamento-</option>
                <?php 
                  foreach ($departamento['dep'] as $key => $value) {
                    if(isset($datosProducto)){
                      if($datosProducto['basicos'][0]['departamento']==$value['id']){
                        echo '<option value="'.$value['id'].'" selected>'.$value['nombre'].'</option>';
                      }
                    }
                      echo '<option value="'.$value['id'].'">'.$value['nombre'].'</option>';
                  }
                ?>
                </select>
              </div>
          </div><br>
          <div class="row">
              <div class="col-sm-4">
                <label>Familia</label>
              </div>
              <div class="col-sm-5">
                  <select class="form-control catego" id="familia" onchange="buscaLinea();">
                    <option value="0">-Selecciona Familia-</option>
                  <?php 
                    foreach ($departamento['fam'] as $key => $value) {
                        if(isset($datosProducto)){
                        if($datosProducto['basicos'][0]['familia']==$value['id']){
                          echo '<option value="'.$value['id'].'" selected>'.$value['nombre'].'</option>';
                        }
                      }
                      echo '<option value="'.$value['id'].'">'.$value['nombre'].'</option>';
                    }
                  ?>
                  </select>
              </div>
          </div><br>
          <div class="row">
              <div class="col-sm-4">
                <label>Línea</label>
              </div>
              <div class="col-sm-5">
                <select class="form-control catego" id="linea">
                  <option value="0">-Selecciona Línea-</option>
                <?php 
                  foreach ($departamento['lin'] as $key => $value) {
                      if(isset($datosProducto)){
                      if($datosProducto['basicos'][0]['linea']==$value['id']){
                        echo '<option value="'.$value['id'].'" selected>'.$value['nombre'].'</option>';
                      }
                    }
                    echo '<option value="'.$value['id'].'">'.$value['nombre'].'</option>';
                  }
                ?>
                </select>  
              </div>          
          </div>
        </div>
       <!-- <div class="col-sm-6">
          <h3>Caracteristicas</h3>
        </div> -->
      </div>
    </div>
    <!-- Claves SAT -->
    <div id="satClaves" class="tab-pane fade">
      <div class="row">
        <div class="col-sm-6">
          <h3>Claves Productos y Servicios SAT</h3>
          <!-- <div class="row">
              <div class="col-sm-4">
                <label>Tipo</label>
              </div>
              <div class="col-sm-5">
                  <select id="tipoProdSat" class="form-control" onchange="satTipo();">
                    <option value="0">-Selecciona-</option>
                    <option value="1">Producto</option>
                    <option value="2">Servicio</option>
                  </select>
              </div>
          </div><br> -->
          <div class="row">
              <div class="col-sm-4">
                <label>Divisiones</label>
              </div>
              <div class="col-sm-8">

                  <select class="form-control catego" id="divisionSat" onchange="(function(){satGrupo();satClase();satClave();})()">
                    <option value="0">-Selecciona-</option>
                    <?php 
                     foreach ($datosProducto['division_sat'] as $key => $value) { ?>
                      <option value="<?php echo $value['id'] ?>" <?php echo ($value['id'] == $datosProducto['basicos'][0]['division_sat']) ? "selected" : ""; ?>><?php echo $value['nombre'] ?></option>
                    <?php } ?>
                  </select>
              </div>
          </div><br>
          <div class="row">
              <div class="col-sm-4">
                <label>Grupo</label>
              </div>
              <div class="col-sm-8">
                <select class="form-control catego" id="grupoSat" onchange="(function(){satClase();satClave();})()">
                  <option value="0">-Selecciona-</option>
                  <?php foreach ($datosProducto['grupo_sat'] as $key => $value) { ?>
                      <option value="<?php echo $value['id'] ?>" <?php echo ($value['id'] == $datosProducto['basicos'][0]['grupo_sat']) ? "selected" : ""; ?>><?php echo $value['nombre'] ?></option>
                  <?php } ?>
                </select>  
              </div>          
          </div>
          <br>
          <div class="row">
              <div class="col-sm-4">
                <label>Clase</label>
              </div>
              <div class="col-sm-8">
                <select class="form-control catego" id="claseSat" onchange="(function(){satClave();})()">
                  <option value="0">-Selecciona-</option>
                  <?php foreach ($datosProducto['clase_sat'] as $key => $value) { ?>
                      <option value="<?php echo $value['id'] ?>" <?php echo ($value['id'] == $datosProducto['basicos'][0]['clase_sat']) ? "selected" : ""; ?>><?php echo $value['nombre'] ?></option>
                  <?php } ?>
                </select>  
              </div>          
          </div>
          <br>
          <div class="row">
              <div class="col-sm-4">
                <label>Clave</label>
              </div>
              <div class="col-sm-8">
                <select class="form-control catego" id="claveSat" >
                  <option value="0">-Selecciona-</option>
                  <?php //var_dump($datosProducto['clave_sat']);die;
                   foreach ($datosProducto['clave_sat'] as $key => $value) { ?>
                      <option value="<?php echo $value['c_claveprodserv'] ?>" <?php echo ($value['c_claveprodserv'] == $datosProducto['basicos'][0]['clave_sat']) ? "selected" : ""; ?>><?php echo $value['descripcion'] ?></option>
                  <?php } ?>
                </select>  
              </div>          
          </div>
          <br>
          <!-- <div class="row">
              <div class="col-sm-12">
                <div class="form-group has-success">
                  <label>Clave</label>
                  <input type="text" disabled class="form-control input-success" id="satCl67">
                </div>  
              </div>
          </div>
          <br> -->
          <!-- <div class="form-group">
            <div class="form-check">
              <label class="form-check-label">
                <input type="checkbox" id="generica" name="point" value="1" >Usar Clave Genérica
              </label>
            </div>
          </div>
        </div> -->
       <!-- <div class="col-sm-6">
          <h3>Caracteristicas</h3>
        </div> -->
      </div>
    </div>
    </div>
    <!-- Fin Claves del SAT -->

    <!-- Formulacion Productos -->
    <div id="formuProds" class="tab-pane fade">
<?php //echo json_encode($datos['insumos']); ?>

<!-- -->
<div class="row">&nbsp;</div>

<div class="col-md-12">
<div class="row">
  <div class="col-md-4">
    <h3><small>Factor minimo de producción:</small></h3>
        <div class="input-group input-group-lg">
      <input value="<?php if($datosProducto['basicos'][0]['factor']==''){ echo 0; }else{ echo $datosProducto['basicos'][0]['factor']; } ?>" id="factor" type="text" class="form-control" value="1" onchange="checamultiplof();" />
      <span class="input-group-addon"><i class="fa fa-slack"></i></span>
    </div>
  </div>
  <div class="col-md-4">
    <h3><small>Cantidad mínima de producción:</small></h3>
        <div class="input-group input-group-lg">
      <input value="<?php echo $datosProducto['basicos'][0]['minimoprod']; ?>" id="cant_minima" type="text" class="form-control" onchange="checamultiplo();" />
      <span class="input-group-addon"><i class="fa fa-slack"></i></span>
    </div>
  </div>
  <div class="col-md-4">
    <h3><small>Unidad:</small></h3>
        <div class="input-group input-group-lg" id="notificaciones">
          <span class="input-group-addon"><i class="fa fa-cubes"></i></span>
          <select id="unidad_compra_venta" class="selectpicker" data-width="80%" style="height: 46px;" onchange="cambiauventa2();"><?php
            foreach ($datos['unidades']['rows'] as $key => $value) {
              ?>
              <option value="<?php echo $value['id'] ?>"><?php echo $value['nombre'] ?></option><?php
            } ?>
          </select>
        </div>
  </div>
</div>
</div>
<div class="row">&nbsp;</div>
<div class="row">&nbsp;</div>

<div class="col-md-6">
<div class="row">
      <div class="col-md-12 col-sm-12">
        <div class="panel-group" id="accordion_insumos" role="tablist" aria-multiselectable="true">
          <div class="panel panel-default">
            <div hrefer class="panel-heading" id="heading_insumos" role="tab" role="button" style="cursor: pointer" data-toggle="collapse" data-parent="#accordion_insumos" href="#tab_insumos" aria-controls="collapse_insumos" aria-expanded="true">
              <h4 class="panel-title">
                <strong>Insumos / Formulación de Productos</strong>
              </h4>
            </div>
            <div id="tab_insumos" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading_insumos">
              <div class="panel-body">
                <table id="tabla_insumos" class="table table-striped table-bordered" cellspacing="0" width="100%">
                  <thead>
                    <tr>
                      <th><strong>Código</stong></th>
                      <th align="center"><strong>Insumo</strong></th>
                      <th align="center"><strong><i class="fa fa-check fa-lg"></i></strong></th>
                    </tr>
                  </thead>
                  <tbody><?php
                    foreach ($datos['insumos']['rows'] as $k => $v) { ?>
                      <tr
                        id="tr_<?php echo $v['idProducto'] ?>"
                        onclick="agregar_insumos_producto({
                          id:<?php echo $v['idProducto'] ?>,
                          codigo:'<?php echo $v['codigo'] ?>',
                          nombre:'<?php echo $v['nombre']?>',
                          unidad_nombre:'<?php echo $v['unidad']?>',
                          idunidad:'<?php echo $v['unidad_codigo']?>',
                          unidad_clave:'<?php echo $v['unidad_clave']?>',
                          div:'div_insumos_producto_agregados',
                          check:$('#check_<?php echo $v['idProducto'] ?>').prop('checked')
                        })"
                        style="cursor: pointer">
                        <td>
                          <?php echo $v['codigo']?>
                        </td>
                        <td align="center">
                          <?php echo $v['nombre'] ?>
                        </td>
                        <td align="center">
                          <input
                            style="cursor: pointer"
                            disabled="1"
                            type="checkbox"
                            id="check_<?php echo $v['idProducto'] ?>" />
                        </td>
                      </tr><?php
                    } ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    </div>
    <!-- -->
    <div class="col-md-6">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h4 class="panel-title">
            <strong>
              Insumos
            </strong>
          </h4>
        </div>
        <div class="panel-body">
          <div class="row">
            <div class="col-md-12 col-sm-12" id="div_insumos_producto_agregados">
            <!-- En esta div se cargan los insumos de la receta -->
              <br /><br />
              <blockquote style="font-size: 16px">
                  <p>
                      Selecciona <strong>"Insumos"</strong> para agregarlos.
                  </p>
                </blockquote>
            </div>
          </div>
        </div>
      </div>
    </div>


     <!-- <div class="row">
        <div class="col-sm-12" style="margin-top: 10px;">
          
          <iframe style="border:block;" height="800" width="100%" src="../../modulos/prd/ajax.php?c=recetas&f=vista_recetas&v=frm_prd"></iframe> 
        </div>
      </div>
      -->
    </div>
    <!-- Fin Formulacion Productos -->

  <!-- 
    <div id="caracteristicas" class="tab-pane fade">
      <div class="col-sm-12">
      <div class="row">
        <h4>Caracteristica Padre</h4>
      </div>
        <div class="row">
          <div class="col-sm-4">
            <div class="form-inline">
                
                <select id="selectCaract" class="form-control characteristics 2">
                  <option value="0">-Selecciona Caracteristica-</option>
                  <?php 
                    foreach ($caracteristicas as $key => $value) {
                       echo '<option value="'.$value['id'].'">'.$value['nombre'].'</option>';
                    }
                  ?>
                </select>
               
            </div>
          </div>
          <div class="col-sm-1">
             <button class="btn btn-default characteristics 2" onclick="agregaCarac();">Agregar</button>
          </div>
        </div>
        
      <div class="row">
        <div class="col-sm-6">
          <table id="caracList" class="table">
            <thead>
              <tr>
                <th></th>
                <th>Caracteristicas</th>
              </tr>
            </thead>
            <tbody>
            <?php 
            //print_r($datosProducto['caracteristicas']);
                  if(isset($datosProducto)){
                    //echo $datosProducto['precios'];
                    foreach ($datosProducto['caracteristicas'] as $keyP => $valueP) {
                      echo '<tr idcart="'.$valueP['id'].'" id="carac_'.$valueP['id'].'">';
                      echo '<td><span class="glyphicon glyphicon-remove-circle" onclick="removeCarac('.$valueP['id'].')"></span></td>';
                      echo '<td>'.$valueP['nombre'].'</td>';
                      echo '</tr>';
                    }
                  } 
            ?> 
            </tbody>
          </table>
        </div>  
      </div>          
      </div>
    </div>
  -->
  <!--
    <div id="unidades" class="tab-pane fade">
      <br />
      <div class="row">
        <div class="col-sm-6">
          <div class="form-inline">
            <label>Minimo</label>
            <input type="text" id="minimo" class="form-control number numeros 1" value="<?php 
                  if(isset($datosProducto)){echo $datosProducto['basicos'][0]['minimos'];}?>">
            <label>Maximo</label>
            <input type="text" id="maximo" class="form-control number numeros 1" value="<?php 
                  if(isset($datosProducto)){echo $datosProducto['basicos'][0]['maximos'];}?>">
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-3">
          <label>Unidad de Compra</label>
        
          <select  id="uniCompra" <?php if(isset($editable)){ if($editable['editable']==0){ echo 'disabled'; } }?> class="1">
            <?php 
              foreach ($unidades as $keyuni => $valueUni) {
                if(isset($datosProducto)){
                  if($datosProducto['basicos'][0]['id_unidad_compra']==$valueUni['id']){
                    echo '<option value="'.$valueUni['id'].'" selected >'.$valueUni['nombre'].'</option>';
                  }
                }
                echo '<option value="'.$valueUni['id'].'">'.$valueUni['nombre'].'</option>';
              }
            ?> 
          </select>
        </div>
        <div class="col-sm-3">
          <label>Unidad de Venta</label>
          <select  id="uniVenta" <?php if(isset($editable)){ if($editable['editable']==0){ echo 'disabled'; } }?> class="1">
            <?php 
              foreach ($unidades as $keyuni => $valueUni) {
                if(isset($datosProducto)){
                  if($datosProducto['basicos'][0]['id_unidad_venta']==$valueUni['id']){
                    echo '<option value="'.$valueUni['id'].'" selected >'.$valueUni['nombre'].'</option>';
                  }
                }
                echo '<option value="'.$valueUni['id'].'">'.$valueUni['nombre'].'</option>';
              }
            ?> 
          </select>
        </div>
      </div>
    </div>
  -->
    
    <div id="costeo" class="tab-pane fade">
      <div class="col-sm-4">
        <label>Selecciona la metodología de costeo del producto</label>
        <select id="costeoSelect" class="form-control costeo" <?php if(isset($editable)){ if($editable['editable']==0){ echo 'disabled'; } }?>>
          <?php 
              $mc = 0;
              if(isset($datosProducto)){
                $mc = $datosProducto['basicos'][0]['id_tipo_costeo'];
              }
            foreach ($costeo as $key => $value) {
              if($value['id']==$mc){
                echo '<option value="'.$value['id'].'" selected >'.$value['nombre'].'</option>';
              }else{
                echo '<option value="'.$value['id'].'"  >'.$value['nombre'].'</option>';
              }
              
            
            }
          ?>
        </select>        
      </div>
    </div>

    <div id="descripcion" class="tab-pane fade">
      <div class="row">
          <div class="form-group col-xs-3">
              <label>Modificar concepto para facturación</label>
              <?php 
              
              if(isset($datosProducto)){
                if($datosProducto['basicos'][0]['edicion']==0){
                  $si = "";
                  $no = 'selected';
                }else{  
                  $si = 'selected';
                  $no = '';
                }
              }


              ?>
              <select id="editDescrio" class="form-control">
                <option value="0" <?php echo $no; ?>>No</option>
                <option value="1" <?php echo $si; ?>>Si</option>
              </select>
            </div>
      </div>
      <div class="row">
      <div class="col-sm-12">
            <div class="form-horizontal col-xs-12">

            <div class="form-group ">
                <label>Descripción Corta</label>
                <textarea name="" maxlength="14" id="descorta" cols="6" rows="2" class="form-control"><?php 
                  if(isset($datosProducto)){echo $datosProducto['basicos'][0]['descripcion_corta'];}?></textarea>
            </div>  

       
            <div class="form-group">
                <label>Descripción Larga</label>
                <textarea name="" id="deslarga" cols="4" rows="6" class="form-control"><?php 
                  if(isset($datosProducto)){echo $datosProducto['basicos'][0]['descripcion_larga'];}?></textarea>

            </div>

            <div class="form-group">
                <label>Reseña</label>
                <textarea name="" id="resena" cols="4" rows="6" class="form-control"><?php 
                  if(isset($datosProducto)){echo $datosProducto['basicos'][0]['resena'];}?></textarea>

            </div>  
            
            <div class="form-group">
              <label>Link Interactivo</label>
                           <input type="text" id="link" class="form-control" value="<?php 
                              if(isset($datosProducto)){echo $datosProducto['basicos'][0]['link'];}?>">  
            </div>                               
 
      </div>
      </div>
      </div>
    </div>
  <!--
    <div id="impuestos" class="tab-pane fade">
      <div class="form-horizontal">
          <h4>Impuestos</h4>
          <input type="hidden" id="formulaIeps" value="<?php 
                  if(isset($datosProducto)){echo $datosProducto['basicos'][0]['formulaIeps'];}?>">
      <div class="row">
        <div class="col-sm-3">
            <select class="form-control" id="selectImpuestos" onchange="muestraIeps();">
              <option value="0">-Selecciona Impuesto-</option>
             <?php 
             //print_r($impuestosDefault['impuestos']);
              foreach ($impuestosDefault['impuestos'] as $key => $value) {
                 echo '<option value="'.$value['id'].'">'.$value['nombre'].'</option>';
              } 
             ?>
            </select>
        </div>
        <div class="col-sm-3">
            <div id="selectfomula" style="display: none;">
              <select id="formula" class="form-control">
                <option value="0">-Selecciona la Formula-</option>
                <option value="1">Suma al importe </option>
                <option value="2">Suma al Subtotal</option>
              </select>
            </div>         
        </div>
        <div class="col-sm-6">
          <button class="btn btn-default" onclick="agregaListaImpues();">Agregar</button>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-6">
          <table id="impuestosList" class="table">
            <thead>
              <tr>
                <th></th>
                <th>Impuesto</th>
                <th>Valor</th>
                <th>Formula</th>
              </tr>
            </thead>
            <tbody>
            <?php 
            $formula='';
                  if(isset($datosProducto)){
                    //echo $datosProducto['basicos'][0]['descripcion_larga'];
                    foreach ($datosProducto['taxes'] as $keyIm => $valueIm) {
                      
                       if($valueIm['formula']==1){
                          $formula = 'Suma al importe';
                        }elseif($valueIm['formula']==2){
                          $formula = 'Suma al Subtotal';
                        }else{
                          $formula = '';
                        } 

                        echo '<tr idListImpues="'.$valueIm['id'].'" id="imp_x_'.$valueIm['id'].'">';
                        echo '<td><span class="glyphicon glyphicon-remove-circle" onclick="removeImpues('.$valueIm['id'].');"></span></td>';
                        echo '<td>'.$valueIm['nombre'].'</td>';
                        echo '<td>%'.$valueIm['valor'].'</td>';
                        echo '<td>%'.$valueIm['valor'].'</td>';
                        echo '<td>'.$formula.'<input type="hidden" id="form_'.$valueIm['id'].'" value="'.$valueIm['formula'].'"></td>';
                        echo '</tr>';
                    }

                  }else{

                    if(isset($impuestosDefault)){
                      
                      foreach ($impuestosDefault['IVA'] as $keyPx => $valuePx) {

                        echo '<tr idListImpues="'.$valuePx['id'].'" id="imp_x_'.$valuePx['id'].'">';
                        echo '<td><span class="glyphicon glyphicon-remove-circle" onclick="removeImpues('.$valuePx['id'].');"></span></td>';
                        echo '<td>'.$valuePx['nombre'].'</td>';
                        echo '<td>%'.$valuePx['valor'].'</td>';
                        echo '<td>%'.$valuePx['valor'].'</td>';
                        echo '</tr>';
                      }
                    }                    
                  }

 
            ?>  
            </tbody>
          </table>
        </div> 
      </div>

 
      </div>
    </div>
  -->

    <div id="comisiones" class="tab-pane fade">
        <div class="col-sm-12">
            <div class="form-horizontal">
            <div class="container">
                <br>
                <div class="row">
                    <div class="col-sm-3">
                        <label for=""> <input type="radio" class="configComision" name="configComision" value="1" onchange="changeConfigComision();" <?php echo ( $comision['config_comision'] == "1" ) ? "checked" : "" ?>> Comisión sobre subtotal</label>
                    </div>
                    <div class="col-sm-3">
                        <label for=""> <input type="radio" class="configComision" name="configComision" value="2" onchange="changeConfigComision();" <?php echo ( $comision['config_comision'] == "2" ) ? "checked" : "" ?>> Comisión sobre utilidad bruta</label>
                    </div>
                    <div class="col-sm-3">
                        <label for=""> <input type="radio" class="configComision" name="configComision" value="3" onchange="changeConfigComision();" <?php echo ( $comision['config_comision'] == "3" OR  ($comision['config_comision'] != "1" AND $comision['config_comision'] != "2") ) ? "checked" : "" ?>> Ninguna</label>
                    </div>
                </div>
                
                <div class="row" id="comisionSubtotal" <?php echo ( $comision['config_comision'] != "1" ) ? "hidden" : "" ?> >
                    <div class="col-sm-6">
                        <hr>
                    </div>
                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-sm-2">Porcentaje de comisión</div>
                            <div class="col-sm-2"><input type="number" class="porcentajeBaseComision" min="0" max="100" style="width: 100%;" value="<?php echo $comision['porcentaje_comision'] ?>"></div>
                        </div>
                    </div>
                    <div class="col-sm-12" style="height: 5px;"></div>
                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-sm-2">Tipo de comisión</div>
                            <div class="col-sm-2">
                                <select name="comision" class="tipoComision" style="width: 100%;" value="<?php echo $comision['id_tipo_comision'] ?>">>
                                    <option value="1" selected >Por venta</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" id="comisionUtilidadBruta" <?php echo ( $comision['config_comision'] != "2" ) ? "hidden" : "" ?>>
                    <div class="col-sm-6">
                        <hr>
                    </div>
                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-sm-2">Precio base</div>
                            <div class="col-sm-2">
                                <select name="precioBase" class="precioBaseComision" style="width: 100% !important;" >
                                    <?php 
                                      foreach ($datosProducto['proves'] as $keyx => $valuex) {
                                          echo "<option value='{$valuex['idPrv']}' ".( ($valuex['idPrv'] == $comision['id_costo_proveedor_comision']) ? 'selected' : '' ).">{$valuex['razon_social']} \${$valuex['costo']}</option>";
                                      }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12" style="height: 5px;"></div>
                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-sm-2">Porcentaje de comisión</div>
                            <div class="col-sm-2"><input type="number" class="porcentajeBaseComision" min="0" max="100" style="width: 100%;" value="<?php echo $comision['porcentaje_comision'] ?>" ></div>
                        </div>
                    </div>
                    <div class="col-sm-12" style="height: 5px;"></div>
                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-sm-2">Tipo de comisión</div>
                            <div class="col-sm-2">
                                <select name="comision" class="tipoComision" style="width: 100%;" value="<?php echo $comision['id_tipo_comision'] ?>">
                                    <option value="1" selected >Por venta</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
         <!--   <div class="form-group">
          <div class="col-sm-offset-2 col-sm-10">
            <div id="btnSave">
              <button type="submit" class="btn btn-default" onclick="guardar();">Guardar</button>
            </div>
              <div class="col-sm-1" style="display:none;" id="loadingPro">
                <i class="fa fa-refresh fa-spin fa-3x"></i>
              </div>
          </div>
        </div>  -->
        <div class="row">
          <div class="col-sm-10"></div>
          <div class="col-sm-2">
           <!--   <div id="btnSave">
                <button type="submit" class="btn btn-success" onclick="guardar();"><span class="glyphicon glyphicon-floppy-disk"></span> Guardar</button>
              </div>
              <div class="col-sm-1" style="display:none;" id="loadingPro">
                <i class="fa fa-refresh fa-spin fa-3x"></i>
              </div> -->
          </div>
        </div>
  </div>
    </div>
  </div>
  <!-- Fin del Div Contenedor de los Tabss -->

  <!--          Molda Success           -->
  <div id="modalSuccess" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content panel-success">
            <div class="modal-header panel-heading">
                <h4 id="modal-label">Exito!</h4>
            </div>
            <div class="modal-body">
                <p>Tu producto se guardo existosamente</p>
            </div>
            <div class="modal-footer">
                <button id="modal-btnconf2-uno" type="button" class="btn btn-default" onclick="redireccion();">Continuar</button> 
            </div>
        </div>
    </div> 
  </div>
    <!--          Molda Quaggajs           -->
  <div id="modalScaner" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content panel-success">
            <div class="modal-header panel-heading">
                <h4 id="modal-label">Exito!</h4>
            </div>
            <div class="modal-body">
                <p>Barcode: <span class="found"></span></p>
                <div id="interactive" class="viewport"></div>
            </div>
            <div class="modal-footer">
                <button id="modal-btnconf2-uno" type="button" class="btn btn-default" onclick="redireccion();">Continuar</button> 
            </div>
        </div>
    </div> 
  </div>

  <!--CALCULADORA-->
      <div id="modalCal" class="modal fade">
         <div class="modal-dialog" role="document">
           <div class="modal-content">
            <div class="modal-header">
               <h5 class="modal-title">Calculadora</h5>
            </div>
               
               
               <div class="modal-body">
                  <label> Calcular Precio Neto</label>
                  <div class="row">
                     <div class="col-md-6">
                        <label>Precio</label>                       
                        <input class="form-control" type="text" id="precioM" style="width: 100;">
                        <br><br><br><br>
                        <label>Precio Sin Impuestos</label>                       
                        <input type="text" id="precioMS">
                     </div>
                     <div class="col-md-6">
                        <label>Impuestos</label> 
                        <div class="col-sm-10" style="padding: 0px;">
                           <input type="hidden" id="formulaIeps" value="<?php 
                           if(isset($datosProducto)){echo $datosProducto['basicos'][0]['formulaIeps'];}?>"> 
                              <select class="form-control" id="selectImpuestos" onchange="muestraIeps();">
                                 <option value="0">-Selecciona Impuesto-</option>
                                  <?php 
                                  //print_r($impuestosDefault['impuestos']);
                                    foreach ($impuestosDefault['impuestos'] as $key => $value) {
                                      echo '<option value="'.$value['id'].'">'.$value['nombre'].'</option>';
                                    } 
                                 ?>
                              </select>
                        </div>  
                        <div class="col-sm-2" style="padding: 0px;">
                           <button class="btn btn-default" onclick="agregaListaImpues();"><span class="fa fa-plus"></button>
                        </div>                         
                        

                        <div class="col-sm-12">
                           <br>
                           <div id="selectfomula" style="display: none;">
                             <select id="formula" class="form-control">
                               <option value="0">-Selecciona la Fórmula-</option>
                               <option value="1">Suma al importe </option>
                               <option value="2">Suma al Subtotal</option>
                             </select>
                           </div>         
                        </div>

                        <table id="impuestosList" class="table">
                           <thead>
                              <tr>
                                 <th></th>
                                 <th>Impuesto</th>
                                 <th>Valor</th>
                                 <th>Fórmula</th>
                              </tr>
                           </thead>
                           <tbody>
                              <?php 
                              $formula='';
                                    if(isset($datosProducto)){
                                      //echo $datosProducto['basicos'][0]['descripcion_larga'];
                                      foreach ($datosProducto['taxes'] as $keyIm => $valueIm) {
                                        
                                         if($valueIm['formula']==1){
                                            $formula = 'Suma al Importe';
                                          }elseif($valueIm['formula']==2){
                                            $formula = 'Suma al Subtotal';
                                          }else{
                                            $formula = '';
                                          } 

                                          echo '<tr idListImpues="'.$valueIm['id'].'" valListImpues="'.$valueIm['valor'].'" id="imp_x_'.$valueIm['id'].'">';
                                          echo '<td><span class="glyphicon glyphicon-remove-circle" onclick="removeImpues('.$valueIm['id'].');"></span></td>';
                                          echo '<td>'.$valueIm['nombre'].'</td>';
                                          echo '<td>%'.$valueIm['valor'].'</td>';
                                          echo '<td>%'.$valueIm['valor'].'</td>';
                                          echo '<td>'.$formula.'<input type="hidden" id="form_'.$valueIm['id'].'" value="'.$valueIm['formula'].'"></td>';
                                          echo '</tr>';
                                      }

                                    }else{

                                      if(isset($impuestosDefault)){
                                        
                                        foreach ($impuestosDefault['IVA'] as $keyPx => $valuePx) {

                                          echo '<tr idListImpues="'.$valuePx['id'].'" valListImpues="'.$valuePx['valor'].'" id="imp_x_'.$valuePx['id'].'">';
                                          echo '<td><span class="glyphicon glyphicon-remove-circle" onclick="removeImpues('.$valuePx['id'].');"></span></td>';
                                          echo '<td>'.$valuePx['nombre'].'</td>';
                                          echo '<td>%'.$valuePx['valor'].'</td>';
                                          echo '<td>%'.$valuePx['valor'].'</td>';
                                          echo '</tr>';
                                        }
                                      }                    
                                    }
                              ?>  
                           </tbody>
                        </table>
                     </div>
                  </div>
               </div>

               <div class="modal-footer">
                  <div class="col-sm-12">
                     <div class="col-sm-6">   
                        <button id="modal-btnconf2-uno" type="button" class="btn btn-success btn-block" onclick="calcular();">Calcular Precio</button>                   
                     </div>
                     <div class="col-sm-6"> 
                        <button id="modal-btnconf2-uno" type="button" class="btn btn-primary btn-block" onclick="guardarP();">Guardar</button>                  
                     </div>
                  </div>                   
               </div>
              

           </div>
         </div> 
      </div>


</div>

</body>
</html>  
<script>
  $(document).ready(function() {
    cambiaTab();
    var insel='<?php echo $insel; ?>';

    

    if(insel!=0){
      var objinsel = jQuery.parseJSON(insel);
      $.each( objinsel, function( k, v ) {
        $('#tabla_insumos tr[id=tr_'+v.id+']').trigger('click');
      });

      $.each( objinsel, function( k, v ) {
        $('#cant_req_'+v.id+'').val(v.cantidad);
      });
    }

    formu_unidad = "<?php echo $datosProducto['basicos'][0]['id_unidad_venta']; ?>";

    $('#unidad_compra_venta').val(formu_unidad);
    $('#unidad_compra_venta').selectpicker('refresh');
    //$("#unidad_compra_venta option[id="+formu_unidad+"]").attr("selected", "selected");
    //


    ver_boxes = "<?php echo $datosProducto['basicos'][0]['peso_dimension']; ?>";
    if(ver_boxes==1){
      $("#boxes").show();
    }else{
      $("#boxes").hide();
    }


    $("#divcart, #divunid").hide();
    
    var caractE = $("#caractE").val();
    var idProducto = $("#idProducto").val();
    
    if(caractE == 1){

      $("#divcart").show();
      $("#unis").attr('disabled', 'disabled');
      $("#divunid").hide();      
      
    }else{      
      $("#divcart").hide();
      if(idProducto != ''){        
        
        $("#divunid").show();
        //$("#unis").attr('checked', 'checked');        
      }
    }

  });

function cambiauventa(){
  idventa =  $("#uniVenta").val();
  $("#unidad_compra_venta").val(idventa);
  $('#unidad_compra_venta').selectpicker('refresh');
  //$("#uniVenta option[id="+idventa+"]").attr("selected", "selected");
}

function cambiauventa2(){
  idventa =  $("#unidad_compra_venta").val();
  $("#uniVenta").val(idventa).trigger('change');
  //$("#uniVenta").val(idventa);
  //$('#uniVenta').selectpicker('refresh');
  //$("#uniVenta option[id="+idventa+"]").attr("selected", "selected");
}


       function calcula(){
          $("#modalCal").modal('show');
      }

function agregar_insumos_producto($objeto){
$("#"+$objeto['div']).html('<div align="center"><i class="fa fa-refresh fa-5x fa-spin"></i></div>');

  $.ajax({
    data : $objeto,
    url : 'ajax.php?c=producto&f=agregar_insumos_producto',
    type : 'POST',
    dataType : 'html',
    async:false
  }).done(function(resp) {
    //console.log('----> Done agregar insumo '+$objeto['id']);
   // console.log(resp);

  // Carga la vista a la div
    $('#' + $objeto['div']).html(resp);

    //$('.selectpicker').selectpicker('refresh');

    //console.log('----> check');
    //console.log($objeto['check']);


    var tabla = $('#tabla_insumos').dataTable();
      var tabla = tabla.fnGetNodes();

    $(tabla).each(function (index){
      id = $(this,tabla).attr('id');
      if(id == 'tr_'+$objeto['id']){
        checkbox = $(this,tabla).find('input');
        if($objeto['check'] === false){
          checkbox.prop("checked", true);
          $(this,tabla).addClass('success');
        }else{
          checkbox.prop("checked", false);
          $(this,tabla).addClass('success');
          $(this,tabla).removeClass('success');
        }
      }
      });
  }).fail(function(resp) {
    console.log('----> Fail agregar insumos por producto');
    console.log(resp);

    $mensaje = 'Error. No se pueden cargar los insumos por producto.';
    $.notify($mensaje, {
      position : "top center",
      autoHide : true,
      autoHideDelay : 5000,
      className : 'error',
      arrowSize : 15
    });
  });

}

function cambiaTab(){
  tp=$('#tipoProd').val();
  if(tp==8 || tp==9){
    $('[href="#formuProds"]').closest('li').show();
  }else{
    $('[href="#formuProds"]').closest('li').hide();
  }
}

function checamultiplo(){
  factor=$('#factor').val();
  cant=$('#cant_minima').val();
  if(factor==0 || factor==''){
    return false;
  }
  
  if (cant % factor == 0){

  }else{
    alert('La cantidad minima solo pueden ser multiples del factor minimo');
    $('#cant_minima').val(factor);
  }
}

function checamultiplof(){
factor=$('#factor').val();
    $('#cant_minima').val(factor);
  
}


$('#divisionSat').select2({width:'100%'});
$('#grupoSat').select2({width:'100%'});
$('#claseSat').select2({width:'100%'});
$('#claveSat').select2({width:'100%'});

$('#generica').change(function() {
        if($(this).is(":checked")) {
            $('#satCl67').val('52839');
        
(function(){servicio();satGrupo();satClase();satClave();})();
        $('#divisionSat').select2({width:'100%'});
        $('#grupoSat').select2({width:'100%'});
        $('#claseSat').select2({width:'100%'});
        $('#claveSat').select2({width:'100%'});
        }else{
            $('#satCl67').val('');
        }
    });


</script>   