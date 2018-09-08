<?php
ini_set("display_errors",0);
include('../../netwarelog/webconfig.php');

$objCon = mysqli_connect($servidor,"nmdevel","nmdevel","netwarstore");
mysqli_set_charset($objCon,"utf8");

$strSql = "SELECT DISTINCT(customer.id), customer.instancia, customer.nombre_db FROM appclient, customer WHERE appclient.idapp IN (" . $_REQUEST['strApps'] . ") AND appclient.idcustomer = customer.id ORDER BY instancia;";

echo $strSql . "<br /><br />";

$rstInstances = mysqli_query($objCon,$strSql);
$arrApps = explode(",",$_REQUEST['strApps']);
if(mysqli_num_rows($rstInstances)>0){
    echo "<table>";
    echo "<tr>";
    echo "<td>Instancia</td><td>Base de Datos</td>";
    echo "</tr>";
    while($objInstances=mysqli_fetch_assoc($rstInstances)){
        echo "<tr>";
        echo "<td style='vertical-align:top; width: 150px;'>" . $objInstances['instancia'] . "</td><td style='vertical-align:top;width: 150px; '>" . $objInstances['nombre_db'] . "</td>";
        echo "<td style='vertical-align:top; width: 300px;'>";
        echo "<table>";
        for($intIx=0;$intIx<count($arrApps);$intIx++){
            $strSql = "SELECT appclient.initdate, appdescrip.appname FROM appclient, appdescrip WHERE appclient.idapp = appdescrip.idapp AND appclient.idcustomer = " . $objInstances['id'] . " AND appclient.idapp = " . $arrApps[$intIx] . ";";
            $rstApps = mysqli_query($objCon,$strSql);
            while($objApps=mysqli_fetch_assoc($rstApps)){
                echo "<tr>";
                echo "<td style='vertical-align:top;width: 150px;'>" . $objApps['appname'] . "</td><td style='vertical-align:top;width: 150px;'>" . $objApps['initdate'] . "</td>";
                echo "</tr>";
            }
            unset($objApps);
            mysqli_free_result($rstApps);
            unset($rstApps);
        }
        echo "</table>";
        echo "</tr>";
    }
    unset($objInstances);
    echo "</table>";
}else{
    echo "No se encontraron resultados";
}
mysqli_free_result($rstInstances);
unset($rstInstances);
mysqli_close($objCon);
unset($objCon);
?>