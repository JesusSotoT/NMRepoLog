<?php
  $target_dir = "../comprobantes/";
  $target_file = $target_dir . basename($_FILES["file"]["name"]);
  $uploadOk = 1;

  //Validación para verificar que se haya seleccionado algun archivo.
  if ( 0 < $_FILES['file']['error'] ) {
    echo 'Error: ' . $_FILES['file']['error'];
    $uploadOk = 0;
  }

  //Validación para ver si el archivo ya existe.
  if (file_exists($target_file)) {
    echo "Un archivo con ese nombre ya existe.";
    $uploadOk = 0;
  }

  //Validación del tamaño del archivo.
  if ($_FILES["file"]["size"] > 500000) {
    echo "El archivo es demasiado grande. Sube un archivo menor a 500KB.";
    $uploadOk = 0;
  }

  //Se valida que no haya errores
  if($uploadOk == 0) {
    echo ' El archivo no se pudo subir.';
  } else {
    move_uploaded_file($_FILES['file']['tmp_name'], '../comprobantes/' . $_FILES['file']['name']);
    echo ' El archivo '. $_FILES['file']['name']. ' ah sido almacenado.';
  }
?>
