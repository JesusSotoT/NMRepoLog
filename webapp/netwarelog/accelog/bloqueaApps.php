<?php session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
      <!-- Latest compiled and minified CSS -->
       <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
    <link href="../../netwarelog/design/default/netwarlog.css" type="text/css" rel="stylesheet" />
    <script src="../../modulos/inicio/js/jquery-1.11.1.min.js"></script>
    <script src="../../modulos/inicio/js/jquery.mask.min.js"></script>
    <style>
    .bloquea {
    position: fixed;
    background-color: rgba(0,0,0,.6);
    top:0px;
    bottom: 0px;
    left: 0px;
    right: 0px;
}

.bloquea2 {
    padding: 25px 25px 25px 25px;
    text-shadow: 0px 1px 0px #FFFFFF;
    background-image: url('../../netwarelog/design/default/red_index.png');
    margin: 50px auto 0px auto;
    width: 500px;
    background-color: #F5F5F5;
    border-radius: 10px;
    border: 6px #98ac31 solid;
    box-shadow: 0px 1px 0px #c0d459;
    text-align: center;
}
    </style>
</head>
<body>

    <?php
    include_once("../../netwarelog/catalog/conexionbd.php");
    $bd=$_SESSION['bd'];

     $q=mysql_query("SELECT usuario FROM " . $bd . ".accelog_usuarios WHERE idempleado = 2;");
    while($objUser = mysql_fetch_array($q)){
        $strAdmUser = $objUser[0];
    }

//Hacemos una segunda conexion para conectarse a netwarestore
$nombre_bd_2="netwarstore_prueba";
$host_bd_2="nmdb.cyv2immv1rf9.us-west-2.rds.amazonaws.com";
$user_bd_2="nmdevel";
$pass_bd_2="nmdevel";

 $conexion2=mysql_connect($host_bd_2,$user_bd_2,$pass_bd_2); 
 mysql_select_db($nombre_bd_2 ,$conexion2) or die($connect_error); 
    $menu = $_GET['menu']; 
    $idclient = $_GET['idclient']; 
     //Nombre de la aplicacion
    $qry1 = mysql_query("SELECT appname from appdescrip inner join appmenu on appmenu.idapp=appdescrip.idapp inner join appclient on appclient.idapp=appdescrip.idapp where appclient.idstatus<>1 and appmenu.idmenu=$menu and appclient.idclient=$idclient", $conexion2);
    $nombreapp=mysql_result($qry1, 0);

    //id de la aplicacion
    $qry2 = mysql_query("SELECT appclient.idapp from appmenu inner join appclient on appclient.idapp=appmenu.idapp where appclient.idstatus<>1 and appmenu.idmenu=$menu and appclient.idclient=$idclient", $conexion2);
    $idapp=mysql_result($qry2, 0);
    
    //imagen de la aplicacion
    $qry3 = mysql_query("SELECT photo from appdescrip inner join appmenu on appmenu.idapp=appdescrip.idapp inner join appclient on appclient.idapp=appdescrip.idapp where appclient.idstatus<>1 and appmenu.idmenu=$menu and appclient.idclient=$idclient", $conexion2);
    $imgapp=mysql_result($qry3, 0);

    ?>
    <br>
    <br>
    <br>
     <div class="bloquea">
            <div class="bloquea2">
                  <img src="../accelog/img/<? echo $imgapp ?> " alt="">
                    <h1 style="font-size:50px; color:#a5211c;"><i class="fa fa-times-circle"></i></h1>
<span style="font-size: 16px; font-weight: bold;">Lo sentimos esta opción se encuentra bloqueada ya que pertenece a la aplicación <span style="font-size:23px;"><?php echo utf8_encode($nombreapp) ?></span> y se encuentra vencida, por favor contacte a un distribuidor para adquirir una licencia.<br /><br /></span>

                   <button class="btn btn-success btn-md" onclick="showContract(<?php echo $idapp;?>)"><i class="fa fa-unlock-alt"></i>  ¿Ya cuentas con clave de activación?</button>
    </div>     
    </div>
                 
        <div id="nmcontract" class=" nmcontract ">
            <div class=" nmcontract2 ">
                <div style="none;" id="divContract">
                    <div style=" text-align: center; padding: 10px 10px 10px 10px; color:#000000; position: relative; width: 450px; background-image: none; display: block; margin: 0px auto 15px auto;" >
                       <h3><i class="fa fa-unlock"></i></h3><h3 style="color:#447f28;">Activar <?php echo $nombreapp ?></h3>
                        Ingresa la contraseña de administrador de la instancia<br /><br />
                        <b><?php echo $strAdmUser; ?></b>&nbsp;<input class="nminputtext" type="password" id="strInstPwd" placeholder="Contraseña" autocomplete="off" />
                    </div>
                    <div style=" text-align: center; padding: 10px 10px 10px 10px; color:#000000; position: relative; width: 450px; background-image: none; display: block; margin: 0px auto 15px auto;" >
                        Clave de activación:
                        <input type="text" class="strKey nminputtext " id="strActivation" autocomplete="off" placeholder="XXXX-XXXXX-XX-XXXXX">
                    </div>
                    <div style=" text-align: center; padding: 10px 10px 10px 10px; color:#000000; position: relative; width: 450px; display: block; margin: 0px auto 15px auto;" >
                        <input class=" nmindexbutton " st type="button" onclick="installApp();" value="Aplicar" />
                        <input type="hidden" value="" id="idapp">
                        <input class=" nmindexbutton " type="button" onclick="closeContract();" value="Cancelar" />
                    </div>
                </div>
                <div style="none; text-align: center;" id="divInstalling">
                    <img src="../../netwarelog/design/default/loader-64.gif" />
                    <h3>Validando licencia...</h3>
                </div>
                <div style="none; text-align: center;" id="divInstalled">
                    <h1 style="color:#23aa34;"><i class="fa fa-check-circle"></i></h1>
<span style="font-size: 16px; font-weight: bold;">La licencia fue aplicada correctamente, ahora podra seguir disfrutando la aplicación 1 año mas, le agradecemos su preferencia, Para aplicar los cambios favor reingrese al sistema.<br /><br /></span>
                    <input class=" nmindexbutton " style="font-size: 20px; " type="button" value="Aceptar" onclick="window.parent.location='../../netwarelog/accelog/salir.php';" />
                </div>
            </div>
        </div>


        <script type="text/javascript">
            function installApp(){
                if($('#strInstPwd').val()==''){
                    alert('Por favor ingresa la contraseña de <?php echo $strAdmUser; ?>');
                    $('#strInstPwd').focus();
                }else{
                    $blnGoInstall = true;
                    if($('#strActivation').val()!=''){
                        if($('#strActivation').val().length!=19){
                            $blnGoInstall = false;
                            alert('La clave de activacion debe ser de 16 caracteres en formato AAAA-AAAAA-AA-AAAAA');
                            $('#strActivation').focus();
                        };
                    }
                    if($blnGoInstall){
                        $('#divContract').slideUp('slow', function () {
                            $('#divInstalling').slideDown('slow', function () {
                                $strD = "strPwd=" + $('#strInstPwd').val();
                                $.ajax({
                                    data: $strD,
                                    type: "POST",
                                    dataType: "text",
                                    url: "../../modulos/installapps/valpwd.php",
                                    success:function ($databack){
                                        if($databack!="OK"){
                                            $('#divInstalling').slideUp('slow', function () {
                                                $('#divContract').slideDown('slow', function () {
                                                    alert('La contraseña de <?php echo $strAdmUser; ?> es incorrecta');
                                                    $('#strInstPwd').focus();
                                                });
                                            });
                                        }else{
                                            $strD = "strActKey=" + $('#strActivation').val() + "&strApp="+ $('#idapp').val();
                                            $.ajax({
                                                data: $strD,
                                                type: "POST",
                                                dataType: "text",
                                                url: "../../modulos/installapps/licenciamiento.php",
                                                success: function (datainstall) {
                                                    switch(datainstall){
                                                        case "--NOF1--":
                                                        case "--NOF2--":
                                                            $('#divInstalling').slideUp('slow', function () {
                                                                $('#divContract').slideDown('slow', function () {
                                                                    if(datainstall=="--NOF1--") {
                                                                        alert('La clave de activacion es incorrecta o ya ha sido utilizada');
                                                                        $('#strActivation').focus();
                                                                    }else{
                                                                        alert('Lo sentimos, tu instancia no se encuentra registrada correctamente, por favor comunícate al 01800 APPS 321 (01800 2777 321) y reporta el incidente');
                                                                    }
                                                                });
                                                            });
                                                            break;
                                                        case "OK":
                                                            $('#divInstalling').slideUp('slow', function () {
                                                                $('#divInstalled').slideDown('slow');
                                                            });
                                                            break;
                                                    };
                                                }
                                            });
                                        };
                                    }
                                })
                            });
                        });
                    }
                }
            }

            function showContract(idapp){
                $('#strInstPwd').val('');
                $('#strActivation').val('');
                $('#divInstalling').hide('');
                $('#divInstalled').hide('');
                $('#divContract').show('');
                $('#nmcontract').show();
                $('#idapp').val(idapp);
            }

            function closeContract(){
                $('#nmcontract').hide();
            }

            $(document).ready(function(){
                    $('.strKey').mask('AAAA-AAAAA-AA-AAAAA');
                }
            );
        </script>
</body>
</html>