<?php
    //include('headers.php');
    include('conexiondb.php');
    $id_usuario=$_POST['id_usuario'];
    $SQL = "SELECT a.id,a.obra FROM constru_generales a WHERE a.borrado=0
    AND a.id not in (SELECT idobra FROM constru_obrasusuario WHERE iduser='$id_usuario')
    ORDER BY obra;";
    $result = $mysqli->query($SQL);
    if($result->num_rows>0) {
        while($row = $result->fetch_array() ) {
        $ono[]=$row;
        $datono=1;
        }
    }else{
        $datono=0;
    }
    
    $SQL = "SELECT a.id,a.obra FROM constru_generales a WHERE a.borrado=0
    AND a.id in (SELECT idobra FROM constru_obrasusuario WHERE iduser='$id_usuario')
    ORDER BY obra;";
    $result = $mysqli->query($SQL);
    if($result->num_rows>0) {
        while($row = $result->fetch_array() ) {
            $osi[]=$row;
            $datosi=1;
        }
    }else{
        $datosi=0;
    }

    $JSON = array('success' =>1,
                'datono'=>$datono,
                'datosi'=>$datosi, 
                'ono'=>$ono,
                'osi'=>$osi);
    //}else{
        //$JSON = array('success' =>0);
    //}
    echo json_encode($JSON);
?>