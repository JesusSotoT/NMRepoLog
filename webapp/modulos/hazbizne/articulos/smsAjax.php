<?php
    include('../../../netwarelog/catalog/conexionbd.php');  //Se conecta a la base de datos del cliente
    include_once('funcionesBD/conexionbda.php');  //Se instancia a la base de datos del usuario
    
    /*
    if(isset($_POST['con']) && $_POST['con']==1){
        $consult = new nConsult;
        $conection = $consult -> conection($servidor,'nmdevel','nmdevel','nmdev_common');
    }else{
        $consult = new nConsult;
        $conection = $consult -> conection($servidor,$usuariobd,$clavebd,$bd);
    }*/
    
    $consult = new Consult;
    $conection = $consult -> conection($servidor,'nmdevel','nmdevel','nmdev_common');
    
    
    
    $coment=$_POST['coment'];
    $id=$_POST['id'];

    $result=$consult->crear_comentario($conection,$coment,$id,$bd);
    
?>
