
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Ordenes de Compra</title>
    <link rel="stylesheet" href="">
</head>
    <link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../libraries/font-awesome/css/font-awesome.min.css">
    <script src="../../libraries/jquery.min.js"></script>
    <script src="../../libraries/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="js/inventarios.js"></script>
<!--Select 2 -->
    <script src="../../libraries/select2/dist/js/select2.min.js"></script>
    <link rel="stylesheet" type="text/css" href="../../libraries/select2/dist/css/select2.min.css" />
    <!-- Datepicker -->
    <link rel="stylesheet" href="../../libraries/datepicker/css/bootstrap-datepicker.min.css">
    <script src="../../libraries/datepicker/js/bootstrap-datepicker.min.js"></script>

 
    <link rel="stylesheet" href="../../libraries/dataTable/css/datatablesboot.min.css">
    <script src="../../libraries/dataTable/js/datatables.min.js"></script>
    <script src="../../libraries/dataTable/js/dataTables.bootstrap.min.js"></script>

<body> 
<br> 
<div class="container well" id="divfiltro">
    <div class="row">
        <div class="col-xs-12 col-md-12">
           <h3>Filtro</h3>
        </div>
    </div>
    <div class="row col-md-12">                     
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-sm-4">
                        
                        <label>Productos</label><br>
                        <select id="producto" class="form-control">
                            <option value="0">-Todos-</option>                         
                        </select><br>

                        <div class="col-sm-6">
                            <label>Lotes</label><br>
                            <input type="radio" name="Rlotes" id="Rlotes3" value="3" checked="checked">TODAS<br>
                            <input type="radio" name="Rlotes" id="Rlotes1" value="1">SI<br>
                            <input type="radio" name="Rlotes" id="Rlotes0" value="0">NO
                        </div>
                        <div class="col-sm-6">
                            <label>Series</label><br>
                            <input type="radio" name="Rseries" id="Rseries3" value="3" checked="checked">TODAS<br>
                            <input type="radio" name="Rseries" id="Rseries1" value="1">SI<br>
                            <input type="radio" name="Rseries" id="Rseries0" value="0">NO
                        </div>

                    </div>
                    <div class="col-sm-4">

                        <label>Unidad</label><br>
                        <select id="unidad" class="form-control">
                            <option value="0">-Todos-</option>                        
                        </select><br>

                        <div class="col-sm-6">
                            <label>Pedimentos</label><br>
                            <input type="radio" name="Rpedi" id="Rpedi3" value="3" checked="checked">TODAS<br>
                            <input type="radio" name="Rpedi" id="Rpedi1" value="1">SI<br>
                            <input type="radio" name="Rpedi" id="Rpedi0" value="0">NO
                        </div>
                        <div class="col-sm-6">
                            <label>Caracteristicas</label><br>
                            <input type="radio" name="Rcarac" id="Rcarac3" value="3" checked="checked">TODAS<br>
                            <input type="radio" name="Rcarac" id="Rcarac1" value="1">SI<br>
                            <input type="radio" name="Rcarac" id="Rcarac0" value="0">NO
                        </div>
                    </div>
                    <div class="col-sm-4">

                     <label>Moneda</label><br>
                        <select id="moneda" class="form-control">
                            <option value="0">-Todos-</option>                        
                        </select>   
                    </div>
                    <div>
                        <button class="btn btn-default" onclick="reloadtable();">Procesar</button>
                    </div>
                        
                </div><br>
            </div>    
        </div>
    </div>
</div>
<div class="container">
  <div class="container" >
    <h3>Catalogo de Productos</h3>
  <br />
        <table id="table_listado" class="table table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
          <tr>
            <th>Codigo</th>
            <th>Producto</th>
            <th>Unidad</th>
            <th>Caracteristicas</th>
            <th>Lotes</th>
            <th>Series</th>
            <th>Pedimientos</th>
            <th>Moneda</th>
          </tr>
        </thead>
        <tbody>
        </tbody>

      </table>
  </div>

    <div class="container">
    <h3>Catalogo de Servicios</h3>
  <br />
        <table id="table_listado2" class="table table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
          <tr>
            <th>Codigo</th>
            <th>Producto</th>
            <th>Unidad</th>
            <th>Caracteristicas</th>
            <th>Lotes</th>
            <th>Series</th>
            <th>Pedimientos</th>
            <th>Moneda</th>
          </tr>
        </thead>
        <tbody>
        </tbody>

      </table>
  </div>
</div>
     
</body>
</html>
<script>
   $(document).ready(function() {
        reloadselect();
        reloadtable();
        $('#producto, #unidad, #moneda').select2();

   });
   </script>


