<!-- CSS -->
<link rel="stylesheet" href="../../libraries/dataTable/css/datatablesboot.min.css">
<link rel="stylesheet" href="../../libraries/dataTable/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="../../libraries/dataTable/css/buttons.dataTables.min.css">
<link rel="stylesheet" href="css/sucursal.css">
<!-- JS -->
<script src="../../libraries/dataTable/js/datatables.min.js"></script>
<script src="../../libraries/export_print/jquery.dataTables.min.js"></script>
<script src="../../libraries/export_print/dataTables.buttons.min.js"></script>
<script src="../../libraries/export_print/buttons.html5.min.js"></script>
<script src="../../libraries/export_print/jszip.min.js"></script>
<script src="../../libraries/dataTable/js/dataTables.bootstrap.min.js"></script>
<script src="js/sucursal.js"></script>


<div class="col s12" id="title-container" style="margin-bottom: .5em !important;">
  <h5>Información de las Sucursales</h5>
</div>

<!-- Main Wrapper / Content -->
<main class="main-wrapper" style="padding: 0 .5em;">
  <!-- Container -->
  <div class="">
    <!-- Tabla ver sucursales -->
    <div class="table-responsive">
      <table class="table table-striped table-bordered" id="ver_sucursales">
        <thead>
          <tr>
            <th>ID          </th>
            <th>Nombre      </th>
            <th>Dirección   </th>
            <th>Contacto    </th>
            <th>Teléfono    </th>
            <th>Almacén     </th>
            <th>Activo      </th> 
          </tr>
        </thead>
        <tbody>
          <?php $sucursalesTable = '';
          foreach ($sucursales as $sucursal) {
            $sucursalesTable .= "<tr>";
            foreach ($sucursal as $registro => $value) {
              //Si value no se encuentra vacio:
              if (isset($value)) {
                //Si el registro es igual a nombre_suc imprimira el nombre con la etiqueta <a>
                if ($registro == "nombre_suc") {
                  $sucursalesTable .= "<td>
                  <a data-toggle='tooltip' title='Modificar Sucursal' href='index.php?c=sucursal&f=nuevaSucursal&id=".$sucursal['id_suc']."'>".$value."</a>
                  </td>";
                  //Si el registro es igual a activo_suc
                  /* NOTA: PARA AGREGAR MÁS EXCEPCIONES EN LOS REGISTROS AÑADIR ELSE IF ABAJO DE ESTE
                  VVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVV*/
                } else if($registro == "activo_suc"){
                  if ($value == -1){
                    //Pintara Si en caso de que sea igual a "-1"
                    $sucursalesTable .= "<td>Si</td>";
                  } else {
                    //Pintara No en caso de que sea diferente a "-1"
                    $sucursalesTable .= "<td>No</td>";
                  }
                /*AÑADIR ELSE IF AQUI*/
                //Si no, imprimira normalmente
                } else {
                  $sucursalesTable .= "<td>".$value."</td>";  
                }
              //Si se encuentra vacio imprimira un guion.               
              } else {
                $sucursalesTable .= "<td>-</td>";
              }
            }
            $sucursalesTable .= "</tr>";
          }
          echo($sucursalesTable);?>
        </tbody>
      </table>
    </div>
  </div> <!-- // Container -->
</main> <!-- // Main Wrapper / Content -->