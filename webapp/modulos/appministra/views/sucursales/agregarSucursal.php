<!-- CSS -->
<link rel="stylesheet" type="text/css" href="../../libraries/select2/dist/css/select2.min.css"/>
<link rel="stylesheet" href="css/sucursal.css">
<!-- JS -->
<script src="../../libraries/select2/dist/js/select2.min.js" type="text/javascript"></script>
<script src="js/sucursal.js"></script>
 
<div class="col s12" id="title-container">
<?php if (isset($_GET['id'])){
  $titulo = "<h5 class='title' id='main-title' idSuc='".$_GET['id']."'>Modificar Sucursal</h5>";
} else {
  $titulo = "<h5 class='title'>Agregar Nueva Sucursal</h5>";
}?>
<?php echo($titulo);?>
</div>

  <!-- Alinear formulario -->
  <div class="col-md-12 col-sm-12" id="form-container">
  <!-- Formulario -->
  <?php if (isset($_GET['id'])) {
    $function = "modificarSucursal()";
  } else {
    $function = "agregarSucursal()";
    $_GET['id'] = "Autoincremental";
  }?>

    <!-- Primera fila -->
    <div class="row">
      <!-- ID -->
      <div class="col-md-4 col-sm-12">
        <div class="form-group">
          <label for="">ID:</label>
          <input class="form-control" name="id" id="id" placeholder="<?php echo($_GET['id']);?>" disabled>
        </div> 
      </div> <!-- // ID -->      

      <!-- Clave -->
      <div class="col-md-4 col-sm-12">
        <div class="form-group">
          <label for="">Clave:</label><span class="required"> *</span>
          <input class="form-control" name="clave" id="clave">
        </div> 
      </div> <!-- // Clave -->

      <!-- Nombre -->
      <div class="col-md-4 col-sm-12">
        <div class="form-group">
          <label for="">Nombre:</label><span class="required"> *</span><br>
          <input class="form-control" name="nombre" id="nombre">
        </div> 
      </div> <!-- // Nombre -->
    </div>

    <!-- Segunda fila -->
    <div class="row">
      <!-- Contacto -->
      <div class="col-md-4 col-sm-12">
        <div class="form-group">
          <label for="clave">Contacto:</label>
          <input class="form-control" name="contacto" id="contacto">
        </div> 
      </div> <!-- //contacto -->

      <!-- Telefono Contacto -->
      <div class="col-md-4 col-sm-12">
        <div class="form-group">
          <label for="telefono">Teléfono de Contacto:</label>
          <input type="tel" class="form-control" name="telefono" id="telefono">
        </div>
      </div> <!-- // Telefono Contacto -->

      <!-- Direccion -->
      <div class="col-md-4 col-sm-12">
        <div class="form-group">
          <label for="clave">Dirección</label><span class="required"> *</span>
          <input class="form-control" name="direccion" id="direccion">
        </div>
      </div> <!-- //Direccion -->
    </div>

    <div class="row">
      <!-- Estado -->
      <div class="col-md-4 col-sm-12">
        <div class="form-group">
          <label for="">Estado:</label><span class="required"> *</span><br>
          <select class="form-control" name="estado" id="estado" required> 
          <?php echo($estadosSelect)?>
          </select>
        </div> 
      </div> <!-- // Estado -->

      <!-- Municipio -->
      <div class="col-md-4 col-sm-12">
        <div class="form-group">
          <label for="">Municipio:</label><span class="required"> *</span><br>
          <select class="form-control" name="municipio" id="municipio" disabled>
            <option value="0">Seleccione un estado primero</option>
          </select>
        </div> 
      </div> <!-- // Municipio -->

      <!-- Codigo Postal -->  
      <div class="col-md-4 col-sm-12">
        <div class="form-group">
          <label for="codigoPostal">Codigo Postal:</label>
          <input type="text" class="form-control" name="codigoPostal" id="codigoPostal" maxlength="6">
        </div>
      </div> <!-- // Codigo postal -->
    </div>

    <!-- Organizacion y Activo -->
    <div class="row">
      <!-- Organización -->
      <div class="col-lg-2 col-md-4 col-sm-12">
        <div class="form-group">
          <label for="">Organización:</label><br>
          <select class="form-control" name="organizacion" id="organizacion">
            <?php echo($orgaSelect); ?>
          </select>
        </div> 
      </div> <!-- // Organización -->

      <!-- Almacen -->
      <div class="col-lg-2 col-md-4 col-sm-12" hidden="">
        <div class="form-group">
          <label for="">Almacén primario:</label><br>
          <select class="form-control" name="almacen" id="almacen">
            <?php echo($almaSelect); ?>
          </select>
        </div> 
      </div> <!-- // Almacen -->

      <!-- Activo -->
      <div class="col-lg-2 col-md-4 col-sm-12">
        <div class="form-group">
          <label for="">Activo:</label><span class="required"> *</span><br>
          <select class="form-control" name="activo" id="activo" required>
            <option value="-1">Si</option>
            <option value="0">No</option>
          </select>
        </div> 
      </div> <!-- // Activo -->
    </div> <!-- // Organizacion, Almacenes y Activo -->
    <div class="row">
      <div class="col-md-2 col-md-offset-8">
        <input type="submit" onclick="<?php echo($function); ?>" class="btn btn-primary btn-success btn-block" name="sent" value="Guardar">
      </div>
      <div class="col-md-2">
        <a  href="index.php?c=sucursal&f=verSucursales" 
            class="btn btn-primary btn-info btn-block" 
            id="volver">
          Regresar
        </a>
      </div>
    </div>
  </div> <!-- // Alinear formulario -->
</main> <!-- // Main Wrapper / Content -->