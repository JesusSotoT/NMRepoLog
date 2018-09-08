<?php
    //include('headers.php');
    include('conexiondb.php');
    $id_usuario=$_POST['id_usuario'];
    $opt=$_POST['opt'];
    $values=$_POST['values'];
    $values=$values[0];
    if($opt==1){
       echo  $SQL = "INSERT INTO constru_obrasusuario (iduser, idobra) VALUES ($id_usuario, $values);";
        $result = $mysqli->query($SQL);
    }

    if($opt==2){
       echo  $SQL = "DELETE FROM constru_obrasusuario WHERE iduser='$id_usuario' AND idobra='$values';";
        $result = $mysqli->query($SQL);
    }

   
?>