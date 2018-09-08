<?php
ini_set("display_errors",0);
include('../../netwarelog/webconfig.php');
$objCon = mysqli_connect($servidor, $usuariobd, $clavebd, $bd);
mysqli_query($objCon, "SET NAMES 'utf8'");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <?php include('../../netwarelog/design/css.php');?>
    <LINK href="../../netwarelog/design/<?php echo $strGNetwarlogCSS;?>/netwarlog.css" title="estilo" rel="stylesheet" type="text/css" /> <!--NETWARLOG CSS-->

	<!--  ##### BOOTSTRAP & FONT ###### -->
    <link href="../../libraries/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../libraries/font-awesome/css/font-awesome.min.css">
    <link href="../../libraries/bootstrap-switch-master/dist/css/bootstrap3/bootstrap-switch.min.css" rel="stylesheet">


 	<!--  ##### BEGIN: BOOTSTRAP & JQUERY ###### -->
	<script src="../../libraries/jquery.min.js"></script>
	<script src="../../libraries/jquery.mobile.touch_events.min.js"></script>
	<script src="../../libraries/select2/dist/js/select2.min.js"></script>
	<script src="../../libraries/select2/dist/js/i18n/es.js"></script>
	<script src="../../libraries/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="../../libraries/bootstrap-switch-master/dist/js/bootstrap-switch.min.js"></script>
	<!--  ##### END: BOOTSTRAP & JQUERY ###### -->

    <style >
    	body { padding: 20px }
        .btn_on_off { padding: 0px 0px 0px 0px; width: 40px; height: 20px; border-radius: 10px; border: 1px #505050 solid; box-shadow: 0px 1px 0px #c0d459; background-repeat: no-repeat; background-size: 14px 14px; background-color: #DCDCDC; background-position-y: 2px; cursor: pointer;  }
        /*.table { width:400px; }*/
        .table td, th{ text-align: center; }
        .btnMenu{
            border-radius: 0;
            width: 100%;
            margin-bottom: 1em;
            margin-top: 1em;
        }
        .row
        {
            margin-top: 1em !important;
        }
        .select2-container{
            width: 100% !important;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h3 class="nmwatitles text-center">
                    Color de interfaz
                </h3>
            </div>
        </div>
        <div class="row" id="divWorking" style="display: none; text-align: center;">
            <div class="col-md-12">
                <i class="fa fa-refresh fa-spin fa-4x"></i>
            </div>
        </div>
        <section id="divSkins">
            <div class="row">
                <div class="col-md-4 col-md-offset-4 col-sm-offset-2">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <thead>
                            <tr>
                                <th style="width:100px;">Color</th>
                                <th>Estilo</th>
                                <th>Activado</th>
                            </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $colors = array(); // "default","blanco","azul","verde","rojo","rosa"

                                    /*
                                    $colors[0]["background"] = "#15263A"; $colors[0]["name"] = "default"; $colors[0]["description"] = "Omisión";
                                    $colors[1]["background"] = "#101010"; $colors[1]["name"] = "dark";    $colors[1]["description"] = "Negro";
                                    $colors[2]["background"] = "#005A8F"; $colors[2]["name"] = "azul";    $colors[2]["description"] = "Azul";
                                    $colors[3]["background"] = "#00A5D0"; $colors[3]["name"] = "cielo";   $colors[3]["description"] = "Cielo";
                                    $colors[4]["background"] = "#98ac31"; $colors[4]["name"] = "green";   $colors[4]["description"] = "Verde";
                                    $colors[5]["background"] = "#cf0404"; $colors[5]["name"] = "rojo";    $colors[5]["description"] = "Rojo";
                                    $colors[6]["background"] = "#EBC1CB"; $colors[6]["name"] = "rosa";    $colors[6]["description"] = "Rosa";
                                    $colors[7]["background"] = "#ffffff"; $colors[7]["name"] = "blanco";  $colors[7]["description"] = "Blanco";
                                    */
                                    $colors[0]["background"] = "#800000"; $colors[0]["name"] = "maroon"; $colors[0]["description"] = "Marron";
                                    $colors[1]["background"] = "#ff0000"; $colors[1]["name"] = "red"; $colors[1]["description"] = "Rojo";
                                    $colors[2]["background"] = "#ffa500"; $colors[2]["name"] = "orange"; $colors[2]["description"] = "Naranja";
                                    // $colors[3]["background"] = "#ffff00"; $colors[3]["name"] = "yellow"; $colors[3]["description"] = "Amarillo";
                                    $colors[3]["background"] = "#808000"; $colors[3]["name"] = "olive"; $colors[3]["description"] = "Olivo";
                                    // $colors[5]["background"] = "#00ff00"; $colors[5]["name"] = "lime"; $colors[5]["description"] = "Lime";
                                    $colors[4]["background"] = "#008000"; $colors[4]["name"] = "green"; $colors[4]["description"] = "Verde";
                                    $colors[5]["background"] = "#800080"; $colors[5]["name"] = "purple"; $colors[5]["description"] = "Morado";
                                    // $colors[8]["background"] = "#ff00ff"; $colors[8]["name"] = "fuchsia"; $colors[8]["description"] = "Fucsia";
                                    $colors[6]["background"] = "#000080"; $colors[6]["name"] = "navy"; $colors[6]["description"] = "Navy";
                                    // $colors[10]["background"] = "#0000ff"; $colors[10]["name"] = "blue"; $colors[10]["description"] = "Azul";
                                    // $colors[11]["background"] = "#00ffff"; $colors[11]["name"] = "aqua"; $colors[11]["description"] = "Aqua";
                                    $colors[7]["background"] = "#008080"; $colors[7]["name"] = "default"; $colors[7]["description"] = "Teal";
                                    $colors[8]["background"] = "#808080"; $colors[8]["name"] = "gray"; $colors[8]["description"] = "Gris";
                                    // $colors[14]["background"] = "#c0c0c0"; $colors[14]["name"] = "silver"; $colors[14]["description"] = "Plata";
                                    $colors[9]["background"] = "#000000"; $colors[9]["name"] = "black"; $colors[9]["description"] = "Negro";


                                    for($i=0;$i<count($colors);$i++){
                                    ?>

                                        <tr>
                                            <td style="
                                                background-color: <?php echo $colors[$i]["background"]?>;
                                                border: 2px;
                                                ">&nbsp;</td>
                                            <td><?php echo $colors[$i]["description"]; ?></td>
                                            <td align="center">
                                                <input class="checkboxSwitch bootstrap-switch-small" type="radio" id='btn<?php echo $i; ?>'
                                                    value="<?php echo $colors[$i]["name"]; ?>" name="btn">
                                            </td>
                                        </tr>

                                    <?php
                                    }
                                ?>

                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td align="center">
                                        <input id="send" type="button" onclick="changeSkin();" value="Guardar" class=" btn btn-primary btnMenu ">
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>


    <script>


		var valueColor = "<?php echo $strGNetwarlogCSS; ?>";

        $(document).ready(function(){

            <?php
            	echo "$(\"input[name='btn'][value='".$strGNetwarlogCSS."']\").attr('checked', 'checked');";
            ?>

        	$.fn.bootstrapSwitch.defaults.size = 'mini';
        	$(".checkboxSwitch").bootstrapSwitch({
        	    onSwitchChange: function(event, state){
         	       valueColor = $(this).val();
        	    }
        	});

        });

        function changeSkin(){
            $('#divSkins').slideUp('slow',function(){
                $('#divWorking').slideDown('slow',function(){
                    $strD = "css="+valueColor;
                    $strD += "&idempleado=<?php echo $_SESSION['accelog_idempleado'];?>";

                    $.ajax({
                       data: $strD,
                       type: "POST",
                       dataType: "text",
                       url: "actcss.php",
                       success: function (databack) {
                           $('#divWorking').slideUp('slow',function(){
                               $('#divSkins').slideDown('slow',function(){
                                   if(confirm("Cambios guardados correctamente\n\nSi desea aplicar inmediatamente el nuevo estilo las pestañas activas de cerraran, de lo contrario se vera reflejado la proxima vez que ingrese al sistema,\n\n¿Aplicar en este momento?")){
                                           //$('#tdhome',window.parent.document).trigger("click");
                                   		window.parent.location.reload();
                                   }
                               });
                           })
                       }
                   });

                });
            });
        }




            /*

                    for($intIx=1;$intIx<7;$intIx++){
                        posbtn = document.getElementById('btn' + $intIx); //Correccion para los colores
                        nposbtn = posbtn.style.backgroundPosition;//Correccion para los colores
                        if(nposbtn!='2px 0px'){ //Correccion para los colores
						//if($('#btn' + $intIx).css('background-position-x')!='2px'){ //codigo antiguo para los colores
                            switch($intIx){
                                case 1:
                                    $strD = "css=default";
                                    break;
                                case 2:
                                    $strD = "css=azul";
                                    break;
                                case 3:
                                    $strD = "css=cielo";
                                    break;
                                case 4:
                                    $strD = "css=green";
                                    break;
                                case 5:
                                    $strD = "css=rojo";
                                    break;
                                case 6:
                                    $strD = "css=rosa";
                                    break;
                            }
                            $strD += "&idempleado=<?php echo $_SESSION['accelog_idempleado'];?>";
                            $.ajax({
                                data: $strD,
                                type: "POST",
                                dataType: "text",
                                url: "actcss.php",
                                success: function (databack) {
                                    $('#divWorking').slideUp('slow',function(){
                                        $('#divSkins').slideDown('slow',function(){
                                            if(confirm("Cambios guardados correctamente\n\nSi desea aplicar inmediatamente el nuevo estilo las pestañas activas de cerraran, de lo contrario se vera reflejado la proxima vez que ingrese al sistema,\n\n¿Aplicar en este momento?")){
                                                    $('#tdhome',window.parent.document).trigger("click");
                                            }
                                        });
                                    })
                                }
                            });
                        };
                    }
                });
            });
        }*/

        function toggleButton($intBtn){
            //console.log($intBtn);
            for($intIx=1;$intIx<7;$intIx++){
                btn = document.getElementById('btn' + $intIx); //Correccion para firefox
                btn.style.backgroundPosition = 2+'px 0';
                //$('#btn' + $intIx).css('background-position-x','2px'); //regresa el boton
                $('#btn' + $intIx).css('background-image','url(img/btn_off.png)'); //cambia color el color a gris
            }
            btnOri = document.getElementById('btn' + $intBtn);  //Correccion para firefox
            btnOri.style.backgroundPosition = 21+'px 0';
            //$('#btn' + $intBtn).css('background-position-x','21px'); //mueve el boton a la derecha
            $('#btn' + $intBtn).css('background-image','url(img/btn_on.png)');//cambia el color a verde
        }
    </script>
</body>
</html>
<?php
mysqli_close($objCon);
unset($objCon);
?>
