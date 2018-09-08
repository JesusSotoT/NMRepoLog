<?php session_start();
include('../../netwarelog/webconfig.php');
$objCon = mysqli_connect($servidor,"nmdevel","nmdevel","netwarstore_prueba");
mysqli_set_charset($objCon,"utf8");
$strActKey = $_REQUEST['strActKey'];
$strApp = $_REQUEST['strApp'];
$strResult = "OK";

if($strActKey!='')
{    
    //verificamos que la installkey no este en uso
    $strSql = "SELECT * FROM codigos WHERE codigo = '" . $strActKey . "' AND instalations>=1 AND salesman = '" . $strApp . "';";
    $rstInstance = mysqli_query($objCon,$strSql);
    if(mysqli_num_rows($rstInstance)==0)
    {
        $strResult = "--NOF1--";
    }else
        {
            // actualizamos el estado de la aplicacion: idstatus=0 (licencia sin usar), idstatus=1 (licencia en uso)
            $strSql = "UPDATE codigos SET estatus =1 WHERE codigo = '" . $strActKey . "' AND estatus = 0 AND salesman = '" . $strApp . "';";
            mysqli_query($objCon,$strSql);

            //le restamos una instalación al total de instalaciones
            $strSql2 = "UPDATE codigos set instalations=instalations-1 where codigo='" . $strActKey. "';";
            mysqli_query($objCon,$strSql2);
        }
    mysqli_free_result($rstInstance);
    unset($rstInstance);
}


if($strResult=="OK")
{
    $strDBInst = $_SESSION["bd"];
    $strSql = "SELECT id,idclient FROM customer WHERE nombre_db = '" . $strDBInst . "';";
    $rstInstance = mysqli_query($objCon,$strSql);
    
    if(mysqli_num_rows($rstInstance)==0)
    {
        $strResult = "--NOF2--";
        if($strActKey!='')
        {
            $strSql = "UPDATE codigos SET estatus = 0 WHERE codigo = '" . $strActKey . "' AND estatus = 0 AND salesman = '" . $strApp . "';";
            mysqli_query($objCon,$strSql);
        }
    }else{

            $limitdate=date('Y-m-d', strtotime('+365 days'));
            $initdate = date('Y-m-d');


            $strSql = "UPDATE appclient inner join customer on appclient.idclient=customer.idclient set appclient.installkey = '".$strActKey."', initdate='".$initdate."', limitdate='".$limitdate."', appclient.idstatus =1 where appclient.idapp=".$strApp." and customer.nombre_db='".$strDBInst."'";
            mysqli_query($objCon,$strSql);
        }
    
    mysqli_free_result($rstInstance);
    unset($rstInstance);
}

mysqli_close($objCon);
unset($objCon);
echo $strResult;
?>