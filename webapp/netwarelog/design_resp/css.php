<?php
if(session_id()==''){
    session_start();
};
$servidorcss = "nmdb.cyv2immv1rf9.us-west-2.rds.amazonaws.com";
$arrInstanciaCSS = explode("/",$_SERVER['REQUEST_URI']);
if(array_search('facturar',$arrInstanciaCSS)!=0){
    $strInstanciaCSS = $arrInstanciaCSS[array_search('facturar',$arrInstanciaCSS) - 1];
}else{
    $strInstanciaCSS = $arrInstanciaCSS[array_search('webapp',$arrInstanciaCSS) - 1];
}
if($strInstanciaCSS=="mlog"){
    $usuariocss = "nmdevel";
    $clavecss = "nmdevel";
    $bdcss = "nmdev";
}else {
    $objCssCon = mysqli_connect($servidorcss, "nmdevel", "nmdevel", "netwarstore");
    $strCssSql = "SELECT * FROM customer WHERE instancia = '" . $strInstanciaCSS . "';";
    $rstCss = mysqli_query($objCssCon, $strCssSql);
    while ($objCss = mysqli_fetch_assoc($rstCss)) {
        $usuariocss = $objCss['usuario_db'];
        $clavecss = $objCss['pwd_db'];
        $bdcss = $objCss['nombre_db'];
    }
    unset($objCss);
    mysqli_free_result($rstCss);
    unset($rstCss);
    mysqli_close($objCssCon);
    unset($strCssSql);
}

$objCssCon = mysqli_connect($servidorcss, $usuariocss, $clavecss, $bdcss);
mysqli_query($objCssCon, "SET NAMES 'utf8'");

$strGNetwarlogCSS = "default";

$strCssSql = "SELECT css FROM accelog_usuarios WHERE idempleado = " . $_SESSION['accelog_idempleado'] . ";";
$rstCss = mysqli_query($objCssCon, $strCssSql);
while($objCss=mysqli_fetch_assoc($rstCss)){
    $strGNetwarlogCSS=$objCss['css'];
}
unset($objCss);
mysqli_free_result($rstCss);
unset($rstCss);

mysqli_close($objCssCon);
unset($objCssCon);
?>