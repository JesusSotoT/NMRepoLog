<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="js/redirect.js" type="text/javascript" language="javascript" charset="UTF-8"></script>
<script language='javascript' src='../../libraries/bootstrap/dist/js/bootstrap.min.js'></script>
<script language='javascript'>
$(document).ready(function()
{
    $('#nmloader_div',window.parent.document).hide();
    Number.prototype.format = function() {
        return this.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,");
    };
    var ingresos = $("#sumG-INGRESOS").attr('cantidad')
    if(isNaN(ingresos)) ingresos = 0
    var egresos = $("#sumG-EGRESOS").attr('cantidad')
    if(isNaN(egresos)) egresos = 0
    var total = parseFloat(ingresos) - parseFloat(egresos)
    $("#sum-Resultados").html('$ '+total.format())
    $("#sum-Resultados").attr('cantidad',total)
    if(total < 0) $("#sum-Resultados").css('color','red');

    ingresos = $("#sumGM-INGRESOS").attr('cantidad')
    if(isNaN(ingresos)) ingresos = 0
    egresos = $("#sumGM-EGRESOS").attr('cantidad')
    if(isNaN(egresos)) egresos = 0
    total = parseFloat(ingresos) - parseFloat(egresos)
    $("#sumM-Resultados").html('$ '+total.format())
    $("#sumM-Resultados").attr('cantidad',total)
    if(total < 0) $("#sumM-Resultados").css('color','red');

    
    //PRESUPUESTO INICIA
    ingresos = $("#sumGMP-INGRESOS").attr('cantidad')
    if(isNaN(ingresos)) ingresos = 0
    egresos = $("#sumGMP-EGRESOS").attr('cantidad')
    if(isNaN(egresos)) egresos = 0    
    total = parseFloat(ingresos) - parseFloat(egresos)
    $("#sumMP-Resultados").html('$ '+total.format())
    $("#sumMP-Resultados").attr('cantidad',total)
    if(total < 0) $("#sumMP-Resultados").css('color','red');

    ingresos = $("#sumDif-INGRESOS").attr('cantidad')
    if(isNaN(ingresos)) ingresos = 0
    egresos = $("#sumDif-EGRESOS").attr('cantidad')
    if(isNaN(egresos)) egresos = 0    
    total = parseFloat(ingresos) - parseFloat(egresos)
    $("#sumMDif-Resultados").html('$ '+total.format())
    $("#sumMDif-Resultados").attr('cantidad',total)
    if(total < 0) $("#sumMDif-Resultados").css('color','red');    

    ingresos = $("#sumDifAcum-INGRESOS").attr('cantidad')
    if(isNaN(ingresos)) ingresos = 0
    egresos = $("#sumDifAcum-EGRESOS").attr('cantidad')
    if(isNaN(egresos)) egresos = 0    
    total = parseFloat(ingresos) - parseFloat(egresos)
    $("#sumDif-Resultados").html('$ '+total.format())
    $("#sumDif-Resultados").attr('cantidad',total)
    if(total < 0) $("#sumDif-Resultados").css('color','red');    

    ingresos = $("#sumGP-INGRESOS").attr('cantidad')
    if(isNaN(ingresos)) ingresos = 0
    egresos = $("#sumGMP-EGRESOS").attr('cantidad')
    if(isNaN(egresos)) egresos = 0    
    total = parseFloat(ingresos) - parseFloat(egresos)
    $("#sumP-Resultados").html('$ '+total.format())
    $("#sumP-Resultados").attr('cantidad',total)
    if(total < 0) $("#sumP-Resultados").css('color','red');  
    //PRESUPUESTO TERMINA  

    
    
    //PORCENTAJES EN COMPARACION CON EL INGRESO POR MES
    var cantidad=0;
    ingresos = $("#sumGM-INGRESOS").attr('cantidad');
    egresos = $("#sumGM-EGRESOS").attr('cantidad');
    $(".mes-INGRESOS").each(function(index)
    {
        cantidad = parseFloat($(this).attr('cantidad'))

        total = cantidad / ingresos * 100
        if(isNaN(total))
        {
            total = 0
        }
        $(this).after("<td style='text-align:right;'>"+total.format()+"%</td>")

    });

    cantidad=0;
    $(".mes-EGRESOS").each(function(index)
    {
        cantidad = parseFloat($(this).attr('cantidad'))
        total = cantidad / ingresos * 100
        if(isNaN(total))
        {
            total = 0
        }
        if(ingresos == 0) total= 0
        $(this).after("<td style='text-align:right;'>"+total.format()+"%</td>")

    });

    cantidad = 0
    cantidad = $("#sumGM-EGRESOS").attr('cantidad')
    total = cantidad / ingresos * 100
    if(isNaN(total))
        {
            total = 0
        }
        if(ingresos == 0) total= 0
    $("#sumGM-EGRESOS").after("<td style='text-align:right;'>"+total.format()+"%</td>")

    cantidad = 0
    cantidad = $("#sumM-Resultados").attr('cantidad')
    total = cantidad / ingresos * 100
    if(isNaN(total))
        {
            total = 0
        }
        if(ingresos == 0) total= 0
    $("#sumM-Resultados").after("<td style='text-align:right;'>"+total.format()+"%</td>")

    cantidad=0;

    /// PRESUPUESTO INGRESOS

    total = 0;
    total = $("#sumDif-INGRESOS").attr('cantidad')/$("#sumGM-INGRESOS").attr('cantidad')*100;
    $("#sumDif-INGRESOS").after("<td style='text-align:right;'>"+total.format()+"%</td>")
    
    total = 0;
    total = $("#sumDifAcum-INGRESOS").attr('cantidad')/$("#sumG-INGRESOS").attr('cantidad')*100;
    $("#sumDifAcum-INGRESOS").after("<td style='text-align:right;'>"+total.format()+"%</td>")
    ///

    /// PRESUPUESTO EGRESOS
   total = 0;
    total = $("#sumDif-EGRESOS").attr('cantidad')/$("#sumGM-EGRESOS").attr('cantidad')*100;
    $("#sumDif-EGRESOS").after("<td style='text-align:right;'>"+total.format()+"%</td>")
    
    total = 0;
    total = $("#sumDifAcum-EGRESOS").attr('cantidad')/$("#sumG-EGRESOS").attr('cantidad')*100;
    $("#sumDifAcum-EGRESOS").after("<td style='text-align:right;'>"+total.format()+"%</td>")
    ///


    //PORCENTAJES EN COMPARACION CON EL INGRESO POR ACUMULADO
    cantidad=0;
    ingresos = $("#sumG-INGRESOS").attr('cantidad');
    egresos = $("#sumG-EGRESOS").attr('cantidad');
    $(".acum-INGRESOS").each(function(index)
    {
        cantidad = $(this).attr('cantidad')
        total = cantidad / ingresos * 100
        if(isNaN(total))
        {
            total = 0
        }
        if(ingresos == 0) total= 0
        $(this).after("<td style='text-align:right;'>"+total.format()+"%</td>")

    });

    cantidad=0;
    $(".acum-EGRESOS").each(function(index)
    {
        cantidad = $(this).attr('cantidad')
        total = cantidad / ingresos * 100
        if(isNaN(total))
        {
            total = 0
        }
        if(ingresos == 0) total= 0
        $(this).after("<td style='text-align:right;'>"+total.format()+"%</td>")

    });

    cantidad = 0
    cantidad = $("#sumG-EGRESOS").attr('cantidad')
    total = cantidad / ingresos * 100
    if(isNaN(total))
        {
            total = 0
        }
        if(ingresos == 0) total= 0
    $("#sumG-EGRESOS").after("<td style='text-align:right;'>"+total.format()+"%</td>")

    cantidad = 0
    cantidad = $("#sum-Resultados").attr('cantidad')
    total = cantidad / ingresos * 100
    if(isNaN(total))
        {
            total = 0
        }
        if(ingresos == 0) total= 0
    $("#sum-Resultados").after("<td style='text-align:right;'>"+total.format()+"%</td>")

    ingresos = $("#sumDif-INGRESOS").next().html();
    ingresos = ingresos.replace('%','');
    egresos = $("#sumDif-EGRESOS").next().html();
    egresos = egresos.replace('%','');
    total = parseFloat(ingresos) - parseFloat(egresos)
    $("#sumMDif-Resultados").after("<td style='text-align:right;'>"+total.format()+"%</td>")    

    ingresos = $("#sumDifAcum-INGRESOS").next().html();
    ingresos = ingresos.replace('%','').replace(',','');
    egresos = $("#sumDifAcum-EGRESOS").next().html();
    egresos = egresos.replace('%','').replace(',','');
    total = parseFloat(ingresos) - parseFloat(egresos)
    $("#sumDif-Resultados").after("<td style='text-align:right;'>"+total.format()+"%</td>")    

    $('.clasif-Clasificacion').remove()
    $(".clasif-Activo:contains('TOTAL GRUPO')").remove()
    $("tr[numero='0']").remove()

    var prc,prc2,prcpre1,prcpre2;
    var prcAntes;
    for(numerillo=0;numerillo<=200;numerillo++)
    {
        
        prcAntes = '';
        prc = prc2 = prcpre1 = prcpre2 = 0;

        $("tr[nmtr='"+numerillo+"']").each(function(index)
        {
            prcAntes = $("td:nth-child(4)",this).html();
            prcAntes = prcAntes.replace("%","")
            prc += parseFloat(prcAntes)

            prcAntes = $("td:nth-child(7)",this).html();
            prcAntes = prcAntes.replace("%","")
            prcpre1 += parseFloat(prcAntes)

            prcAntes = $("td:nth-child(9)",this).html();
            prcAntes = prcAntes.replace("%","")
            prc2 += parseFloat(prcAntes)

            prcAntes = $("td:nth-child(12)",this).html();
            prcAntes = prcAntes.replace("%","")
            prcpre2 += parseFloat(prcAntes)
        });
        $("tr[numero='"+numerillo+"'] td:nth-child(4)").html(prc.format()+"%")
        $("tr[numero='"+numerillo+"'] td:nth-child(9)").html(prc2.format()+"%")
        $("tr[numero='"+numerillo+"'] td:nth-child(7)").html(prcpre1.format()+"%")
        $("tr[numero='"+numerillo+"'] td:nth-child(12)").html(prcpre2.format()+"%")
        //alert(prc)
    }

});


function generaexcel()
            {
                $().redirect('views/fiscal/generaexcel.php', {'cont': $('#imprimible').html(), 'name': $('#titulo').val()});
            }   

</script>
<script language='javascript' src='js/pdfmail.js'></script>
<!--LINK href="../../netwarelog/catalog/css/estilo.css" title="estilo" rel="stylesheet" type="text/css" /-->
<link href="css/style.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="css/imprimir_bootstrap.css" type="text/css">
<style>
    .tit_tabla_buscar td
    {
        font-size:medium;
    }

    #logo_empresa /*Logo en pdf*/
    {
        display:none;
    }

    @media print
    {
        #imprimir,#filtros,#excel,#email_icon, #botones
        {
            display:none;
        }

        #logo_empresa
        {
            display:block;
        }

        .table-responsive{
            overflow-x: unset;
        }

        table{
            zoom: 0.6 !important;
        }

        #imp_cont{
            width: 100% !important;
        }
    }

    .btnMenu{
        border-radius: 0; 
        width: 100%;
        margin-bottom: 0.3em;
        margin-top: 0.3em;
    }
    .row
    {
        margin-top: 0.5em !important;
    }
    h4, h3{
        background-color: #eee;
        padding: 0.4em;
    }
    .modal-title{
        background-color: unset !important;
        padding: unset !important;
    }
    .nmwatitles, [id="title"] {
        padding: 8px 0 3px !important;
        background-color: unset !important;
    }
    .select2-container{
        width: 100% !important;
    }
    .select2-container .select2-choice{
        background-image: unset !important;
        height: 31px !important;
    }
    .twitter-typeahead{
        width: 100% !important;
    }
    .tablaResponsiva{
        max-width: 100vw !important; 
        display: inline-block;
    }
    .table thead, .table tbody tr {
        display:table;
        width:100%;
        table-layout: fixed;/* even columns width , fix width of table too*/
    }
</style>

<?php 
$moneda=$_POST['moneda'];
if($_POST['valMon']){$valMon=$_POST['valMon'];}else{$valMon=1;}
//$valMon=13.5;
$titulo1="font-size:10px;background-color:#f6f7f8;font-weight:bold;height:30px;";
$subtitulo="font-size:9px;font-weight:bold;height:30px;background-color:#fafafa;text-align:left;margin-left:10px;"

?>

<div class="container" style="width:100%">
    <div class="row">
        <div class="col-md-12">
            <h3 class="nmwatitles text-center">
                Estado de Resultados<br>
                <section id="botones">
                    <a href="javascript:window.print();" id='imprimir'><img class="nmwaicons" src="../../netwarelog/design/default/impresora.png" border="0" title='Imprimir'></a>
                    <a href='javascript:generaexcel()' id='excel'><img src='images/images.jpg' width='35px'></a>   
                    <a href="javascript:pdf();"><img src="../../../webapp/netwarelog/repolog/img/pdf.gif" title ="Generar reporte en PDF" border="0"></a>   
                    <a href="javascript:mail();"><img src="../../../webapp/netwarelog/repolog/img/email.png" title ="Enviar reporte por correo electrónico" border="0"></a>
                    <a href='index.php?c=reports&f=balanceGeneral&tipo=0' onclick="javascript:$('#nmloader_div',window.parent.document).show();" id='filtros' onclick="javascript:$('#nmloader_div',window.parent.document).hide();"><img src="../../netwarelog/repolog/img/filtros.png"  border="0" title='Haga click aqu&iacute; para cambiar los filtros...'></a>               
                </section>
            </h3>
            <input type='hidden' value='Estado de Resultados.' id='titulo'>
            <div class="row">
                <div class="col-md-1">
                </div>
                <div class="col-md-10" id="imp_cont">
                    <section id='imprimible'>
                        <div class="row">
                            <?php
                                $url = explode('/modulos',$_SERVER['REQUEST_URI']);
                                if($logo == 'logo.png') $logo = 'x.png';
                                $logo = str_replace(' ', '%20', $logo);
                            ?>
                            <div class="col-md-6 col-sm-6 col-md-offset-6 col-sm-offset-6" style="font-size:7px;text-align:right;color:gray;">
                                <b>Fecha de Impresión<br><?php echo date("d/m/Y H:i:s"); ?></b>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-center" style="font-size:18px;">
                                <label><?php echo $empresa;?></label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12" style="text-align:center;color:#576370;font-size:12px;">
                                <b style='font-size:15px;'>Estado de Resultados </b> <br> 
                                Ejercicio <b><?php echo $ej; ?></b>  Periodo<b><?php echo $periodo; ?></b><br>
                                Sucursal <b><?php echo $nomSucursal; ?></b> Segmento <b><?php echo $nomSegmento; ?></b> 
                                <?php if($valMon>1){echo "<br>Moneda <b>$moneda</b> Tipo de Cambio $ <b>$valMon</b>";}?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12 tablaResponsiva">
                                <div class="table-responsive">
                                    <table align="center" valing="center" cellpadding="3" style='font-size:9px;max-width:900px;min-width:500px' >
                                        <thead>
                                        <tr style='font-weight:bold;background-color:#edeff1;height:30px;' valign='center'>
                                            <td style='width:8%;min-width:90px;text-align:center;'>CLAVE</td>
                                            <td style='width:24%;min-width:230px;text-align:center;'>CUENTA <?php if(!intval($_POST['detalle'])) echo "DE MAYOR"; ?></td>
                                            <td style='width:18%;min-width:170px;text-align:center;'>CANTIDAD DEL MES</td>
                                            <td style='width:16%;min-width:90px;text-align:center;'>% DEL MES</td>
                                            <?php
                                            if(intval($_POST['presup']))
                                            {
                                            ?>
                                                <td style='width:18%;min-width:170px;text-align:center;'>PRESUPUESTO MES</td>
                                                <td style='width:18%;min-width:170px;text-align:center;'>DIFERENCIA MES</td>
                                            <?php }
                                            if(intval($_POST['presup']) == 1)
                                            {
                                            
                                                echo "<td style='width:16%;min-width:90px;text-align:center;'>% DE DESVIACION MES</td>";
                                            }
                                            if(intval($_POST['presup']) == 2)
                                            {
                                            
                                                echo "<td style='width:16%;min-width:90px;text-align:center;'>% DE ALCANZADO MES</td>";
                                            }?>
                                            <td style='width:18%;min-width:170px;text-align:center;'>CANTIDAD ACUMULADA</td>
                                            <td style='width:16%;min-width:90px;text-align:center;'>% ACUMULADO</td>
                                            <?php
                                            if(intval($_POST['presup']))
                                            {
                                            ?>
                                                <td style='width:18%;min-width:170px;text-align:center;'>PRESUPUESTO ACUMULADO</td>
                                                <td style='width:18%;min-width:170px;text-align:center;'>DIFERENCIA ACUMULADA</td>
                                            <?php }
                                            if(intval($_POST['presup']) == 1)
                                            {
                                            
                                                echo "<td style='width:16%;min-width:90px;text-align:center;'>% DE DESVIACION ACUMULADA</td>";
                                            }
                                            if(intval($_POST['presup']) == 2)
                                            {
                                            
                                                echo "<td style='width:16%;min-width:90px;text-align:center;'>% DE ALCANZADO ACUMULADA</td>";
                                            }?>
                                        </tr>
                                        </thead>
                                                    
                                        <?php
                                        function startsWith($haystack, $needle) 
                                        {
                                            // search backwards starting from haystack length characters from the end
                                            return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== FALSE;
                                        }
                                        $nn=0;
                                        $clasifAnterior='Clasificacion';//Almacena la clasificacion anterior
                                        $grupoAnterior='Grupo';
                                        $sumaCantidad = $sumaCantidadMes = 0;//Almacena la sumatoria de las cantidades de la cuenta de mayor
                                        $sumaCantidadGrupo = $sumaCantidadGrupoMes = 0;//Almacena la sumatoria de las cantidades de la cuenta de mayor
                                        while($d = $datos->fetch_object())
                                        {
                                            if($d->h2 == '1')
                                                $d->Grupo = "INGRESOS";
                                            if($d->h2 == '2')
                                                $d->Grupo = "EGRESOS";
                                            
                                            $CM = explode(' / ',$d->Cuenta_de_Mayor,2);
                                            

                                            if(startsWith($d->Grupo,'RESULTADO ACRE') || startsWith($d->Grupo,'RESULTADOS ACRE'))
                                            {
                                                $d->Grupo = "INGRESOS"; 
                                            }

                                            if(startsWith($d->Grupo,'RESULTADO DEUDOR') || startsWith($d->Grupo,'RESULTADOS DEUDOR'))
                                            {
                                                $d->Grupo = "EGRESOS";  
                                            }

                                            if(intval($_POST['idioma']))
                                            {
                                                $grupoNombre = $d->Grupo_Alt;
                                                $grupoAnteriorNombre = $grupoAnterior_Alt;
                                                $clasNombre = $d->Clasificacion_Alt;
                                                $clasAnteriorNombre = $clasifAnterior_Alt;
                                                $y = 'and';
                                            }
                                            else
                                            {
                                                $grupoNombre = $d->Grupo;
                                                $grupoAnteriorNombre = $grupoAnterior;
                                                $clasNombre = $d->Clasificacion;
                                                $clasAnteriorNombre = $clasifAnterior;
                                                $y = "y";
                                            }

                                            $title = $grupoNombre;

                                            if($clasifAnterior != $d->Clasificacion)
                                            {
                                                if($grupoAnterior != $d->Grupo)
                                                {

                                                    if(floatval($sumaCantidadGrupo) < 0) $red = "style='color:red;'";
                                                    if(floatval($sumaCantidadGrupoMes) < 0) $redMes = "style='color:red;'";
                                                    echo "<tr style='$subtitulo' class='clasif-$clasifAnterior'><td></td><td>TOTAL ".strtoupper($grupoAnteriorNombre)."</td><td id='sumGM-$grupoAnterior' $redMes cantidad='".number_format($sumaCantidadGrupoMes,2,'.','')."'>$ ".number_format($sumaCantidadGrupoMes,2)."</td><td>100%</td><td id='sumGMP-$grupoAnterior' style='text-align:right;$redMes' cantidad='".number_format($presupMesGrupo,2,'.','')."'>$ ".number_format($presupMesGrupo,2)."</td><td id='sumDif-$grupoAnterior' style='text-align:right;$redMes' cantidad='".number_format($DiferMesGrupo,2,'.','')."'>$ ".number_format($DiferMesGrupo,2)."</td><td id='sumG-$grupoAnterior' $red cantidad='".number_format($sumaCantidadGrupo,2,'.','')."'>$ ".number_format($sumaCantidadGrupo,2)."</td><td>100%</td><td id='sumGP-$grupoAnterior' style='text-align:right;$redMes' cantidad='".number_format($presupAcumGrupo,2,'.','')."'>$ ".number_format($presupAcumGrupo,2)."</td><td id='sumDifAcum-$grupoAnterior' style='text-align:right;$redMes' cantidad='".number_format($DiferAcumGrupo,2,'.','')."'>$ ".number_format($DiferAcumGrupo,2)."</td></tr>";
                                                    $sumaCantidadGrupo = $sumaCantidadGrupoMes = $presupMesGrupo = $DiferMesGrupo = $presupAcumGrupo = $DiferAcumGrupo = 0;
                                                }


                                                //comienza cuenta de clasificacion
                                                $red = $redMes = '';
                                                if(floatval($sumaCantidad) < 0) $red = "style='color:red;'";
                                                if(floatval($sumaCantidadMes) < 0) $redMes = "style='color:red;'";
                                                echo "<tr style='font-weight:bold;height:30px;' class='clasif-$clasifAnterior'><td></td><td>TOTAL ".strtoupper($clasAnteriorNombre)."</td><td id='sumM-$clasifAnterior' $redMes>$ ".number_format($sumaCantidadMes,2)."</td><td>100%</td><td id='sum-$clasifAnterior' $red>$ ".number_format($sumaCantidad,2)."</td><td>100%</td></tr>";
                                                $sumaCantidad = $sumaCantidadMes = 0;
                                                echo "<!--tr class='clasif-$clasifAnterior' style='font-weight:bold;height:3px;'><td colspan='12'></td></tr-->"; 
                                                echo "<tr style='$titulo1' class='clasif-$d->Clasificacion'><td></td><td style='text-align:left;'>".strtoupper($clasNombre)."</td><td style='text-align:right;'></td><td></td><td></td><td></td></tr>"; 
                                                //termina cuenta de clasificacion
                                                
                                                if($grupoAnterior != $d->Grupo)
                                                {
                                                    echo "<!--tr style='height:3px;'><td colspan='12' class='clasif-$d->Clasificacion'></td></tr-->";    
                                                    echo "<tr style='$subtitulo' class='clasif-$d->Clasificacion'><td></td><td>".strtoupper($title)."</td><td></td><td></td><td></td><td></td></tr>";   
                                                }
                                                if($d->Cuenta_de_Mayor != $mayorAnterior AND intval($_POST['detalle']))
                                                {   
                                                    echo "<tr numero='$nn' style='border-top:1px solid black;color:gray;font-weight:bold;text-align:right;' class='anterior'><td></td><td style='text-align:left;'>Total $mayorAnterior</td><td>$".number_format($sumaCantidadMayorMes,2)."</td><td></td><td>$".number_format($presupMesMayor,2)."</td><td>$".number_format($DiferMesMayor,2)."</td><td></td><td>$".number_format($sumaCantidadMayor,2)."</td><td></td><td>$".number_format($presupAcumMayor,2)."</td><td>$".number_format($DiferAcumMayor,2)."</td><td></td></tr>";
                                                    echo "<tr style='color:gray;font-weight:bold;height:50px;text-align:left;'><td></td><td>$d->Codigo / ".$CM[1]."</td></tr>";
                                                    $sumaCantidadMayor=0;
                                                    $sumaCantidadMayorMes=0;
                                                    $presupMesMayor=0;
                                                    $DiferMesMayor=0;
                                                    $presupAcumMayor=0;
                                                    $DiferAcumMayor=0;
                                                    $nn++;
                                                }

                                            }
                                            else
                                            {
                                                
                                                if($d->Cuenta_de_Mayor != $mayorAnterior AND intval($_POST['detalle']))
                                                {
                                                    echo "<tr numero='$nn' style='border-top:1px solid black;color:gray;font-weight:bold;text-align:right;' class='anterior'><td></td><td style='text-align:left;'>Total $mayorAnterior</td><td>$".number_format($sumaCantidadMayorMes,2)."</td><td></td><td>$".number_format($presupMesMayor,2)."</td><td>$".number_format($DiferMesMayor,2)."</td><td></td><td>$".number_format($sumaCantidadMayor,2)."</td><td></td><td>$".number_format($presupAcumMayor,2)."</td><td>$".number_format($DiferAcumMayor,2)."</td><td></td></tr>";
                                                }
                                                if($grupoAnterior != $d->Grupo)
                                                {
                                                    $red = $redMes ='';
                                                    if(floatval($sumaCantidadGrupo) < 0) $red = "color:red";
                                                    if(floatval($sumaCantidadGrupoMes) < 0) $redMes = "color:red";
                                                    echo "<tr style='$subtitulo' class='clasif-$d->Clasificacion'><td></td><td>TOTAL ".strtoupper($titleAnterior)."</td><td id='sumGM-$grupoAnterior' style='text-align:right;$redMes' cantidad='".number_format($sumaCantidadGrupoMes,2,'.','')."'>$ ".number_format($sumaCantidadGrupoMes,2)."</td><td style='text-align:right;'>100%</td><td id='sumGMP-$grupoAnterior' style='text-align:right;$redMes' cantidad='".number_format($presupMesGrupo,2,'.','')."'>$ ".number_format($presupMesGrupo,2)."</td><td id='sumDif-$grupoAnterior' style='text-align:right;$redMes' cantidad='".number_format($DiferMesGrupo,2,'.','')."'>$ ".number_format($DiferMesGrupo,2)."</td><td id='sumG-$grupoAnterior' style='text-align:right;$red' cantidad='".number_format($sumaCantidadGrupo,2,'.','')."'>$ ".number_format($sumaCantidadGrupo,2)."</td><td style='text-align:right;'>100%</td><td id='sumGP-$grupoAnterior' style='text-align:right;$redMes' cantidad='".number_format($presupAcumGrupo,2,'.','')."'>$ ".number_format($presupAcumGrupo,2)."</td><td id='sumDifAcum-$grupoAnterior' style='text-align:right;$redMes' cantidad='".number_format($DiferAcumGrupo,2,'.','')."'>$ ".number_format($DiferAcumGrupo,2)."</td></tr>";
                                                    $sumaCantidadGrupo = $sumaCantidadGrupoMes = $presupMesGrupo = $DiferMesGrupo = $presupAcumGrupo = $DiferAcumGrupo = 0;
                                                    echo "<!--tr><td colspan='12' class='clasif-$d->Clasificacion' style='height:15px;'></td></tr-->";   
                                                    echo "<tr style='$subtitulo' class='clasif-$d->Clasificacion'><td></td><td>".strtoupper($title)."</td><td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td></tr>";    

                                                }
                                                if($d->Cuenta_de_Mayor != $mayorAnterior AND intval($_POST['detalle']))
                                                {
                                                    echo "<tr style='color:gray;font-weight:bold;height:50px;text-align:left;'><td></td><td>$d->Codigo / ".$CM[1]."</td></tr>";
                                                    $sumaCantidadMayor=0;
                                                    $sumaCantidadMayorMes=0;
                                                    $presupMesMayor=0;
                                                    $DiferMesMayor=0;
                                                    $presupAcumMayor=0;
                                                    $DiferAcumMayor=0;
                                                    $nn++;
                                                }
                                            }
                                            $red = $redMes = '';
                                            $ResultadosMes = $d->CargosAbonosMes;
                                            $ResultadosMes = $ResultadosMes/$valMon;
                                            $Resultados = $d->CargosAbonos;
                                            $Resultados = $Resultados/$valMon;
                                            if($d->Grupo == "INGRESOS")
                                            {
                                                $Resultados = $Resultados *-1;
                                                $ResultadosMes = $ResultadosMes *-1;
                                            }
                                            if(floatval($Resultados) < 0) $red = 'color:red;';
                                            if(floatval($ResultadosMes) < 0) $redMes = 'color:red;';
                                            if(!intval($_POST['detalle']))
                                            {
                                                $nc = $d->Codigo;
                                                $tc = $CM[1];
                                            } 
                                            else
                                            {
                                                 $nc = $d->CuentaAfectable;
                                                 $tc = $d->Cuenta;
                                            }
                                        if($_POST['detalle']){ $t=0;}else{$t=1;}
                                        
                                        if(intval($_POST['presup']))
                                        {
                                            $DiferenciaMes = $ResultadosMes - $d->PresupuestoMes;
                                            $DiferenciaAcum = $Resultados - $d->PresupuestoAcum;
                                        }
                                
                                        if(intval($_POST['presup']) == 1)
                                        {
                                            $PorcMes = ($DiferenciaMes / $ResultadosMes) * 100;
                                            $PorcAcum = ($DiferenciaAcum / $Resultados) * 100;
                                        }

                                        if(intval($_POST['presup']) == 2)
                                        {
                                            $PorcMes = ($ResultadosMes / $d->PresupuestoMes) * 100;
                                            $PorcAcum = ($Resultados / $d->PresupuestoAcum) * 100;
                                        }
                                        if($_POST['segmento']==0){ $_POST['segmento']="todos";}
                                            echo "<tr class='clasif-$d->Clasificacion' nmtr='$nn'><td style='mso-number-format:\"@\";'><a href='index.php?c=Reports&f=movcuentas_despues&f3_2=01&f4_2=31&f3_1=".$_POST['periodo']."&f4_1=".$_POST['periodo']."&f3_3=".$_POST['ejercicio']."&f4_3=".$_POST['ejercicio']."&tipo=".$t."&cuentas[]=".$d->account_id."&segmento=".$_POST['segmento']."' target='_blank'>".$nc."</a></td><td style='text-align:left;'>".$tc."</td><td style='text-align:right;$redMes' class='mes-$d->Grupo' cantidad='".number_format($ResultadosMes,2,'.','')."'>$ ".number_format($ResultadosMes,2)."</td><td style='text-align:right;' class='mespres-$d->Grupo' cantidad='".number_format($d->PresupuestoMes,2,'.','')."'>$ ".number_format($d->PresupuestoMes,2)."</td><td style='text-align:right;' class='difmes-$d->Grupo' cantidad='".number_format($DiferenciaMes,2,'.','')."'>$ ".number_format($DiferenciaMes,2)."</td><td style='text-align:right;' class='porcmes-$d->Grupo' cantidad='".number_format($PorcMes,2,'.','')."'>".number_format($PorcMes,2)."%</td><td style='text-align:right;$red' class='acum-$d->Grupo' cantidad='".number_format($Resultados,2,'.','')."'>$ ".number_format($Resultados,2)."</td><td style='text-align:right;' class='acumpres-$d->Grupo' cantidad='".number_format($d->PresupuestoAcum,2,'.','')."'>$ ".number_format($d->PresupuestoAcum,2)."</td><td style='text-align:right;' class='dif-$d->Grupo' cantidad='".number_format($DiferenciaAcum,2,'.','')."'>$ ".number_format($DiferenciaAcum,2)."</td><td style='text-align:right;' class='porcacum-$d->Grupo' cantidad='".number_format($PorcAcum,2,'.','')."'>".number_format($PorcAcum,2)."%</td></tr>";
                                            $sumaCantidadGrupo      += $Resultados;
                                            $sumaCantidad           += $Resultados;
                                            $sumaCantidadMayor      += $Resultados;
                                            $sumaCantidadGrupoMes   += $ResultadosMes;
                                            $sumaCantidadMes        += $ResultadosMes;
                                            $sumaCantidadMayorMes   += $ResultadosMes;
                                            $clasifAnterior         = $d->Clasificacion;
                                            $grupoAnterior          = $d->Grupo;
                                            $mayorAnterior          = $d->Cuenta_de_Mayor;
                                            $red = $redMes = '';
                                            $titleAnterior          = $title;

                                            $presupMesGrupo         += $d->PresupuestoMes;
                                            $presupMesMayor         += $d->PresupuestoMes;
                                            $presupMes              += $d->PresupuestoMes;
                                            $DiferMesGrupo          += $DiferenciaMes;
                                            $DiferMesMayor          += $DiferenciaMes;
                                            $DiferMes               += $DiferenciaMes;
                                            $presupAcumGrupo        += $d->PresupuestoAcum;
                                            $presupAcumMayor        += $d->PresupuestoAcum;
                                            $presupAcum             += $d->PresupuestoAcum;
                                            $DiferAcumGrupo         += $DiferenciaAcum;
                                            $DiferAcumMayor         += $DiferenciaAcum;
                                            $DiferAcum              += $DiferenciaAcum;
                                        }
                                        
                                        if(floatval($sumaCantidadGrupo) < 0) $red = "color:red";
                                        if(floatval($sumaCantidadGrupoMes) < 0) $redMes = "color:red";
                                        echo "<tr numero='$nn' style='border-top:1px solid black;color:gray;font-weight:bold;text-align:right;' class='anterior'><td></td><td style='text-align:left;'>Total $mayorAnterior</td><td>$".number_format($sumaCantidadMayorMes,2)."</td><td></td><td>$".number_format($presupMesMayor,2)."</td><td>$".number_format($DiferMesMayor,2)."</td><td></td><td>$".number_format($sumaCantidadMayor,2)."</td><td></td><td>$".number_format($presupAcumMayor,2)."</td><td>$".number_format($DiferAcumMayor,2)."</td><td></td></tr>";
                                        echo "<tr style='$subtitulo' class='clasif-$d->Clasificacion'>
                                                <td></td>
                                                <td>TOTAL ".strtoupper($titleAnterior)."</td>
                                                <td id='sumGM-$grupoAnterior' style='text-align:right;$redMes' cantidad='".number_format($sumaCantidadGrupoMes,2,'.','')."'>$ ".number_format($sumaCantidadGrupoMes,2)."</td>
                                                <td id='sumGMP-$grupoAnterior' style='text-align:right;$redMes' cantidad='".number_format($presupMesGrupo,2,'.','')."'>$ ".number_format($presupMesGrupo,2)."</td>
                                                <td id='sumDif-$grupoAnterior' style='text-align:right;$redMes' cantidad='".number_format($DiferMesGrupo,2,'.','')."'>$ ".number_format($DiferMesGrupo,2)."</td>
                                                <td id='sumG-$grupoAnterior' style='text-align:right;$red' cantidad='".number_format($sumaCantidadGrupo,2,'.','')."'>$ ".number_format($sumaCantidadGrupo,2)."</td>
                                                <td id='sumGP-$grupoAnterior' style='text-align:right;$redMes' cantidad='".number_format($presupAcumGrupo,2,'.','')."'>$ ".number_format($presupAcumGrupo,2)."</td>
                                                <td id='sumDifAcum-$grupoAnterior' style='text-align:right;$redMes' cantidad='".number_format($DiferAcumGrupo,2,'.','')."'>$ ".number_format($DiferAcumGrupo,2)."</td>
                                              </tr>";

                                        if(floatval($sumaCantidad) < 0) $red = "color:red;";
                                        if(floatval($sumaCantidadMes) < 0) $redMes = "color:red;";
                                        echo "<tr style='$titulo1'><td>
                                                </td><td style='text-align:left;'>TOTAL ".strtoupper($clasNombre)."</td>
                                                <td id='sumM-$clasifAnterior' style='text-align:right;$redMes'></td>
                                                <td id='sumMP-$clasifAnterior' style='text-align:right;$redMes'></td>
                                                <td id='sumMDif-$clasifAnterior' style='text-align:right;$redMes'></td>
                                                <td id='sum-$clasifAnterior' style='text-align:right;$red'></td>
                                                <td id='sumP-$clasifAnterior' style='text-align:right;$redMes'></td>
                                                <td id='sumDif-$clasifAnterior' style='text-align:right;$redMes'></td>
                                              </tr>";
                                        ?>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <input type='hidden' id='totalMayores' value='<?php echo $sumaCont; ?>'>
                    </section>
                </div>
            </div>
        </div>
    </div>
</div>

<!--GENERA PDF*************************************************-->
<div id="divpanelpdf" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Generar PDF</h4>
            </div>
            <form id="formpdf" action="libraries/pdf/examples/generaPDF.php" method="post" target="_blank" onsubmit="generar_pdf()">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label>Escala (%):</label>
                            <select id="cmbescala" name="cmbescala" class="form-control">
                                <?php
                                    for($i=100; $i > 0; $i--){
                                        echo '<option value='. $i .'>' . $i . '</option>';
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label>Orientación:</label>
                            <select id="cmborientacion" name="cmborientacion" class="form-control">
                                <option value='P'>Vertical</option>
                                <option value='L'>Horizontal</option>
                            </select>
                        </div>
                    </div>
                    <textarea id="contenido" name="contenido" style="display:none"></textarea>
                    <input type='hidden' name='tipoDocu' value='hg'>
                    <input type='hidden' value='<?php echo "http://".$_SERVER['SERVER_NAME'].$url[0]."/netwarelog/archivos/1/organizaciones/$logo"; ?>' name='logo' />
                    <input type='hidden' name='nombreDocu' value='Estado de Resultados'>
                </div>
                <div class="modal-footer">
                    <div class="row">
                        <div class="col-md-6">
                            <input type="submit" value="Crear PDF" autofocus class="btn btn-primary btnMenu">
                        </div>
                        <div class="col-md-6">
                            <input type="button" value="Cancelar" onclick="cancelar_pdf()" class="btn btn-danger btnMenu">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!--GENERA PDF*************************************************-->

<!-- MAIL -->
            <div id="loading" style="position: absolute; top:30%; left: 50%;display:none;z-index:2;">
            <div 
                id="divmsg"
                style="
                    opacity:0.8;
                    position:relative;
                    background-color:#000;
                    color:white;
                    padding: 20px;
                    -webkit-border-radius: 20px;
                    border-radius: 10px;
                    left:-50%;
                    top:-30%
                ">
                <center><img src='../../../webapp/netwarelog/repolog/img/loading-black.gif' width='50'><br>Cargando...
                </center>
            </div>
            </div>
            <script>
                function cerrarloading(){
                    $("#loading").fadeOut(0);
                    var divloading="<center><img src='../../../webapp/netwarelog/repolog/img/loading-black.gif' width='50'><br>Cargando...</center>";
                    $("#divmsg").html(divloading);
                }
            </script>