<?php
ini_set("display_errors",0);
include('../../netwarelog/webconfig.php');
$objCon = mysqli_connect($servidor, $usuariobd, $clavebd, $bd);
mysqli_query($objCon, "SET NAMES 'utf8'");
$intAdm = 2;
$acceperfil= $_SESSION['accelog_idperfil'];

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <?php include('../../netwarelog/design/css.php');?>
    <LINK href="../../netwarelog/design/<?php echo $strGNetwarlogCSS;?>/netwarlog.css" title="estilo" rel="stylesheet" type="text/css" /> <!--NETWARLOG CSS-->

    <script src="js/jquery-1.11.1.min.js"></script>
    <script src="js/jquery.mask.min.js"></script>
    <style >
        .btn_on_off { padding: 0px 0px 0px 0px; width: 40px; height: 20px; border-radius: 10px; border: 1px #505050 solid; box-shadow: 0px 1px 0px #c0d459; background-repeat: no-repeat; background-size: 14px 14px; background-color: #DCDCDC; background-position-y: 2px; cursor: pointer;  }
    </style>
</head>
<body>
    <div class=" nmwatitles ">Perfiles de Usuario</div><br />
    <div id="divWorking" style="display: none; text-align: center;">
        <img src="img/spinner.gif" />
    </div>
    <div id="divProfiles">
        <input type="button" value="Crear Nuevo" onclick="loadProfile(-1);" style=" vertical-align: middle; text-align: center; font-size: 10pt; font-weight: bold; color:#101010; border-radius: 5px; padding: 3px 10px 3px 10px; background-color: #98ac31; cursor: pointer; " />
        <br /><br />
        <table class=" nmcatalogbusqueda ">
            <tbody>
                <tr>
                    <td class=" nmcatalogbusquedatit " align="center">Perfil</td>
                    <td class=" nmcatalogbusquedatit " align="center">Usuarios</td>
                    <td class=" nmcatalogbusquedatit " align="center"></td>
                </tr>
<?php

$strSql = "SELECT accelog_perfiles.idperfil, accelog_perfiles.nombre, (SELECT COUNT(a.idempleado) FROM accelog_usuarios_per a inner join administracion_usuarios b on b.idempleado=a.idempleado WHERE a.idperfil = accelog_perfiles.idperfil) AS 'count' FROM accelog_perfiles WHERE accelog_perfiles.visible = -1 AND accelog_perfiles.idperfil <> 2 ORDER BY accelog_perfiles.nombre;";
$rstPerfiles = mysqli_query($objCon, $strSql);
$intClass=0;
while($objPerfiles=mysqli_fetch_assoc($rstPerfiles)){
    if(($intClass % 2) == 0){
        $Class = 1;
    }else{
        $Class = 2;
    }
    ?>
                <tr id="tr<?php echo $objPerfiles['idperfil'];?>">
                    <td class=" nmcatalogbusquedacont_<?php echo $Class;?> " style="cursor: pointer;" onclick="loadProfile(<?php echo $objPerfiles['idperfil'];?>);"><?php echo $objPerfiles['nombre'];?></td>
                    <td class=" nmcatalogbusquedacont_<?php echo $Class;?> " style="cursor: pointer;" onclick="loadProfile(<?php echo $objPerfiles['idperfil'];?>);"><?php echo $objPerfiles['count'];?></td>
                    <td class=" nmcatalogbusquedacont_<?php echo $Class;?> " style="vertical-align: middle; text-align: center;">
                        <?php
                        if($objPerfiles['count']==0) {
                            ?>
                            <input type="button" value="Eliminar" onclick="delProfile(<?php echo $objPerfiles['idperfil'];?>,'<?php echo $objPerfiles['nombre'];?>');" style=" vertical-align: middle; text-align: center; font-size: 10pt; font-weight: bold; color:#FFFFFF; border-radius: 5px; padding: 3px 10px 3px 10px; background-color: #8B0000; cursor: pointer; "/>
                        <?php
                        }
                        ?>
                    </td>
                </tr>
    <?php
    $intClass++;
}
unset($objPerfiles);
mysqli_free_result($rstPerfiles);
unset($rstPerfiles);
?>
            </tbody>
        </table>
    </div>
    <div id="divMenus" style="display: none; vertical-align: top;">
        <table>
            <tr>
                <td style="vertical-align: top">
                    <table>
                        <tr>
                            <td colspan="2">
                                Perfil <input id="txtProfile" type="text" class=" nminputtext " style="width:200px;" act="">
                            </td>
                        </tr>
                        <tr style="height: 80px;">
                            <td style="vertical-align: bottom; text-align: center">
                                <input type="button" value="Aceptar" onclick="procProfile();" style=" vertical-align: middle; text-align: center; font-size: 14pt; font-weight: bold; color:#101010; border-radius: 5px; padding: 3px 15px 3px 15px; background-color: #98ac31; cursor: pointer; " />
                            </td>
                            <td style="vertical-align: bottom; text-align: center">
                                <input type="button" value="Cancelar" onclick="cancelProfile();" style=" vertical-align: middle; text-align: center; font-size: 14pt; font-weight: bold; color:#FFFFFF; border-radius: 5px; padding: 3px 15px 3px 15px; background-color: #8B0000; cursor: pointer; "/>
                            </td>
                        </tr>
                    </table>
                    <div id="divError" style=" display: none; background-color: #FFF0C9; border: 1px #8B0000 solid; border-radius: 5px; color:#8B0000; padding: 10px 10px 10px 10px; text-align: center; margin-top: 10px;"></div>
                </td>
                <td>
                    <table class=" nmcatalogbusqueda ">
                        <tbody>
                        <tr>
                            <td class=" nmcatalogbusquedatit " align="center">Menu</td>
                            <td class=" nmcatalogbusquedatit " align="center">Acceso?</td>
                        </tr>
                        <?php
                        $strProfiles = "";
                        $strSql = "SELECT DISTINCT(accelog_categorias.idcategoria) AS 'idcategoria', accelog_categorias.nombre AS 'categoria' FROM accelog_menu LEFT JOIN accelog_categorias ON accelog_menu.idcategoria = accelog_categorias.idcategoria WHERE accelog_menu.idmenu IN (SELECT idmenu FROM accelog_perfiles_me WHERE idperfil = " . $intAdm . " OR idperfil = " . $acceperfil . "  ) AND NOT accelog_categorias.idcategoria IS NULL ORDER BY accelog_categorias.orden,accelog_menu.orden;";
                        $rstCategorias = mysqli_query($objCon, $strSql);
                        while ($objCategorias = mysqli_fetch_assoc($rstCategorias)) {
                            echo "<tr><td colspan='2' class=' nmcatalogbusquedacont_2 '>" . $objCategorias['categoria'] . "</td></tr>";
                            getMenus($objCategorias['idcategoria'],0,1,$acceperfil);
                        }
                        unset($objCategorias);
                        mysqli_free_result($rstCategorias);
                        unset($rstCategorias);
                        ?>
                        </tbody>
                    </table>
                </td>
            </tr>
        </table>
    </div>
    <script>

        $strProfiles = [<?php echo substr($strProfiles,0,strlen($strProfiles) - 1); ?>];

        function loadProfile($intProfile){
            $('#divProfiles').slideUp('slow',function(){
                $('#divWorking').slideDown('slow',function(){
                    for($intIx=0; $intIx<$strProfiles.length; $intIx++) {
                        posbtn = document.getElementById('btn' + $strProfiles[$intIx]); //Correccion para firefox
                        if(posbtn!=null){
                            posbtn.style.backgroundPosition = 2+'px 0';
                            //$('#btn' + $strProfiles[$intIx]).css('background-position-x','2px');
                            $('#btn' + $strProfiles[$intIx]).css('background-image','url(img/btn_off.png)');
                        }
                    };
                    if($intProfile==-1){
                        $('#divError').hide();
                        $('#divError').html('');
                        $('#txtProfile').val('');
                        $('#txtProfile').attr('act','0');
                        $('#divWorking').slideUp('slow',function(){
                            $('#divMenus').slideDown('slow');
                        })
                    }else{
                        $strD = "intProfile=" + $intProfile;
                        $.ajax({
                            data: $strD,
                            type: "POST",
                            dataType: "json",
                            url: "getprofile.php",
                            success: function (databack) {
                                console.log(databack);
                                for($intIx=0; $intIx<databack.arrMenus.length; $intIx++){
                                    posbtn = document.getElementById('btn' + databack.arrMenus[$intIx].intMenu);
                                    //Correccion para firefox
                                    if(posbtn!=null){
                                        posbtn.style.backgroundPosition = 21+'px 0';
                                        // $('#btn' + databack.arrMenus[$intIx].intMenu).css('background-position-x','21px');
                                        $('#btn' + databack.arrMenus[$intIx].intMenu).css('background-image','url(img/btn_on.png)');
                                    }
                                };
                                $('#divError').hide();
                                $('#divError').html('');
                                $('#txtProfile').val(databack.strProfile);
                                $('#txtProfile').attr('act',$intProfile);
                                $('#divWorking').slideUp('slow',function(){
                                    $('#divMenus').slideDown('slow');
                                })
                            }
                        });
                    };
                });
            });
        }

        function procProfile(){
            $('#divMenus').slideUp('slow',function(){
                $('#divWorking').slideDown('slow',function(){
                    if($('#txtProfile').val().trim()==''){
                        $('#divError').html('Indique el nombre del perfil,<br/>solo se admiten números y letras');
                        $('#divError').show();
                        $('#txtProfile').focus();
                        $('#divWorking').slideUp('slow',function(){
                            $('#divMenus').slideDown('slow');
                        })
                    }else{
                        $strProfileMenus = "";
                        for($intIx=0; $intIx<$strProfiles.length; $intIx++) {
                            posbtn = document.getElementById('btn' + $strProfiles[$intIx]); //Correccion para firefox
                            nposbtn = posbtn.style.backgroundPosition;
                            if(nposbtn!='2px 0px'){

                                //if($('#btn' + $strProfiles[$intIx]).css('background-position-x').trim()!='2px'){
                                    $strProfileMenus += $strProfiles[$intIx] + "|";
                                //}
                            }
                        };

                        //console.log($strProfileMenus);
                        //return false;
                        if($('#txtProfile').attr('act')==0){
                            $strAction = 0;
                        }else{
                            $strAction = $('#txtProfile').attr('act');
                        };
                        $strProfile = $('#txtProfile').val().trim().toUpperCase();
                        $strD = "strProfileMenus=" + $strProfileMenus + "&strAction="+ $strAction + "&strProfile=" + $strProfile;
                        $.ajax({
                            data: $strD,
                            type: "POST",
                            dataType: "text",
                            url: "procprofile.php",
                            success: function (databack) {
                                $('#divError').hide();
                                $('#divError').html('');
                                $('#txtProfile').val('');
                                $('#txtProfile').attr('act','');
                                $('#divWorking').slideUp('slow',function(){
                                    $('#divProfiles').slideDown('slow',function(){
                                        location.reload();
                                    });
                                })
                            }
                        });
                    };
                })
            })
        }

        function delProfile($intProfile, $strProfile){
            if(confirm('¿Desea eliminar el perfil -' + $strProfile + '-?')){
                $('#divProfiles').slideUp('slow',function(){
                    $('#divWorking').slideDown('slow',function(){
                        $('#divError').hide();
                        $('#divError').html('');
                        $('#txtProfile').val($intProfile);
                        $('#txtProfile').attr('act',-1);
                        procProfile();
                    })
                })
            };
        }

        function cancelProfile(){
            $('#divMenus').slideUp('slow',function(){
                $('#divWorking').slideDown('slow',function(){
                    for($intIx=0; $intIx<$strProfiles.length; $intIx++) {
                        $('#btn' + $strProfiles[$intIx]).css('background-position-x','2px');
                        $('#btn' + $strProfiles[$intIx]).css('background-image','url(img/btn_off.png)');
                    };
                    $('#divError').hide();
                    $('#divError').html('');
                    $('#txtProfile').val('');
                    $('#txtProfile').attr('act','');
                    $('#divWorking').slideUp('slow',function(){
                        $('#divProfiles').slideDown('slow');
                    })
                })
            })
        }

        function toggleButton($intBtn){
            btn = document.getElementById('btn' + $intBtn); //Correccion para los colores
            nbtn = btn.style.backgroundPosition;//Correccion para los colores

            if(nbtn=='2px 0px'){
                btn.style.backgroundPosition = 21+'px 0';
                //$('#btn' + $intBtn).css('background-position-x','21px');
                $('#btn' + $intBtn).css('background-image','url(img/btn_on.png)');
            }else{
                btn.style.backgroundPosition = 2+'px 0';
                //$('#btn' + $intBtn).css('background-position-x','2px');
                $('#btn' + $intBtn).css('background-image','url(img/btn_off.png)');
            }
        }
    </script>
</body>
</html>
<?php
mysqli_close($objCon);
unset($objCon);

function getMenus($intMenu,$intPadre,$intMult,$acceperfil){
    global $intAdm;
    global $objCon;
    global $strProfiles;
    $strSql = "SELECT accelog_menu.idmenu AS 'idmenu', accelog_menu.nombre AS 'menu', accelog_menu.idmenupadre AS 'padre', accelog_menu.orden AS 'orden' FROM accelog_menu WHERE accelog_menu.idcategoria = " . $intMenu . " AND accelog_menu.idmenupadre = " . $intPadre . " AND accelog_menu.idmenu IN (SELECT idmenu FROM accelog_perfiles_me WHERE idperfil = " . $intAdm . " OR idperfil = " . $acceperfil . ") ORDER BY accelog_menu.orden;";
    $rstMenu = mysqli_query($objCon, $strSql);
    while ($objMenu = mysqli_fetch_assoc($rstMenu)) {
        $strProfiles.= $objMenu['idmenu'] . ",";
        echo "<tr><td class=' nmcatalogbusquedacont_1 '>";
        echo genSpacer($intMult);
        echo $objMenu['menu'];
        echo "</td>";
        echo "<td class=' nmcatalogbusquedacont_1 '>";
        echo "<input type='button' id='btn" . $objMenu['idmenu'] . "' onclick='toggleButton(" . $objMenu['idmenu'] . ")' class=' btn_on_off ' style=' background-position-x: 2px; background-image: url(img/btn_off.png); '>";
        echo "</td>";
        echo "</tr>";
        //echo genSpacer($intMult) . $objMenu['idmenu'] . " - " . $objMenu['menu'] . "<br />";
        $intMult++;
        getMenus($intMenu,$objMenu['idmenu'],$intMult,$acceperfil);
        $intMult--;
    }
    unset($objMenu);
    mysqli_free_result($rstMenu);
    unset($rstMenu);
}

function genSpacer($intMult){
    $strSpacer = "";
    $intMult = $intMult * 4;
    for($intIx=0; $intIx<$intMult; $intIx++){
        $strSpacer.="&nbsp;";
    }
    return $strSpacer;
}
?>
