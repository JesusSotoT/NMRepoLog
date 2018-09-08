<?php

$idcampo = $_POST['ic'];
$campovalor = $_POST['cv'];
$campodesc = $_POST['cd'];
$dependenciatabla = $_POST['dt'];
$sqlw = $_POST['sw'];
//echo "recibí:".$_SERVER["REQUEST_URI"]."   sqlw:".$sqlw;
$sqlw = str_replace("\\","",$sqlw);
//echo " -- id:".$idcampo." sw:".$sqlw;

$seleccionado_m = $_POST['sm'];
$deshabilitado = $_POST['de'];
$formato = $_POST['fo'];

//echo "cargando... ".$idcampo." ".$campovalor." ".$campodesc." ".$dependenciatabla." ".$sqlw;

include("conexionbd.php");

$objeto="

		<table><tr><td>
		<div id='i".$idcampo."_div'>

		<select id='i".$idcampo."' name='i".$idcampo."' class=' nminputselect ' onchange='campo_onchange(this,true)' ".$deshabilitado." >";


$campodesc=str_replace(",",",' ',",$campodesc);
$campodesc=" concat(".$campodesc.") as catalog_campodesc ";
$sql="select ".$campovalor.", ".$campodesc." from ".$dependenciatabla." where ".$sqlw." order by catalog_campodesc";
$campodesc="catalog_campodesc";
//echo $sql;
$rsdependenciasimple = $conexion->consultar($sql);
while($regsimple=$conexion->siguiente($rsdependenciasimple)){

    $selecciona_m="";
    if($seleccionado_m!=""){
        if($regsimple{$campovalor}==$seleccionado_m){
            $selecciona_m="selected";
        } else {
            $selecciona_m="";
        }
    }

    $datoenelcombo = $regsimple{$campodesc};
    if(($formato=="$")||($formato=="$.00")){
        $datoenelcombo = "$ ".number_format($datoenelcombo);
    }

    if($formato=="0.00"){
        $datoenelcombo = number_format($datoenelcombo);
    }


    $objeto.="<option value='".$regsimple{$campovalor}."' ".$selecciona_m." >".$datoenelcombo."</option>";
}
$conexion->cerrar_consulta($rsdependenciasimple);

$objeto.="</select>";

if($formato!="-1"){
    //SecundarioLog
    $objeto.="
		</div></td><td style='padding-left:10px;'><input type='button' class='btn btn-default btn-sm' value='...' onclick='btn_i".$idcampo."_click();' /></td></tr></table>";
    $sql_encriptado = str_rot13(base64_encode($sql));
    //$sql_desencriptado = base64_decode(str_rot13($sql_encriptado));
    //$objeto.=$sql_encriptado." <br>".$sql_desencriptado;
    $objeto.="<script>";
    $objeto.="  function btn_i".$idcampo."_click(){";

    $sql_dt = "select idestructura, descripcion from catalog_estructuras where nombreestructura='".$dependenciatabla."' ";
    $rs_dt = $conexion->consultar($sql_dt);
    if($reg_dt=$conexion->siguiente($rs_dt)){
        $idestructura_dt = $reg_dt["idestructura"];
    }
    $conexion->cerrar_consulta($rs_dt);

    $objeto.="
					div_secundariolog = 'i".$idcampo."_div';
					div_secundariolog_q = '".$sql_encriptado."';
					div_secundariolog_o = 'i".$idcampo."';
					div_secundariolog_d = '".$deshabilitado."';
					div_secundariolog_c = '".$campodesc."';
					div_secundariolog_v = '".$campovalor."';
					$('#frsecundariolog').hide();
					$('#div_wait_secundariolog').show();
					$('#frsecundariolog').attr('src','../secundariolog/gestor.php?idestructura=".$idestructura_dt."&ticket=testing');
					$('#secundariolog_lbl_title').html('Espere un momento ...');
					$('#secundariolog').modal('show');
				";

    //$objeto.="     window.open('../secundariolog/gestor.php?idestructura=".$idestructura_dt."&ticket=testing');";
    $objeto.="  }";
    $objeto.="</script>";
} else {
    $objeto.="</tr></table>";
}


echo $objeto;

$conexion->cerrar();



?>
