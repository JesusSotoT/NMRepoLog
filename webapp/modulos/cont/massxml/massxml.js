function valDatesFrom(){
    $intDateFrom = 0;
    if($('#dteFrom').val().trim()!=''){
        $intDateFrom = parseInt($('#dteFrom').val().split('-').join(''));
    }
    $intDateTo = 0;
    if($('#dteTo').val().trim()!=''){
        $intDateTo = parseInt($('#dteTo').val().split('-').join(''));
    }
    if($intDateTo<$intDateFrom){
        $('#dteTo').val($('#dteFrom').val());
    }
}

function valDatesTo(){
    $intDateFrom = 0;
    if($('#dteFrom').val().trim()!=''){
        $intDateFrom = parseInt($('#dteFrom').val().split('-').join(''));
    }
    $intDateTo = 0;
    if($('#dteTo').val().trim()!=''){
        $intDateTo = parseInt($('#dteTo').val().split('-').join(''));
    }

    if($intDateFrom>$intDateTo){
        $('#dteFrom').val($('#dteTo').val());
    }
}

function getXMLs(){
    var strNuevos = $('#strNuevosPc').prop('checked') ? 1 : 0;
    //alert(strNuevos)
    enableButtons(false);
    $('#divGrid').hide();
    $('#tblGrid tbody tr').remove();
    if($('#strRFC').val().trim()==''){
        enableButtons(true);
        $('#strResult').css('border-color','#FF0000');
        $('#strResult').css('color','#FF0000');
        $('#strResult').html('Por favor ingresa el RFC');
    }else{
        if($('#strCIEC').val().trim()==''){
            enableButtons(true);
            $('#strResult').css('border-color','#FF0000');
            $('#strResult').css('color','#FF0000');
            $('#strResult').html('Por favor ingresa la contraseña CIEC');
        }else{
            if($('#jcaptcha').val().trim()==''){
                enableButtons(true);
                $('#strResult').css('border-color','#FF0000');
                $('#strResult').css('color','#FF0000');
                $('#strResult').html('Por favor ingresa el captcha');
            }else{
            if($('#dteFrom').val().trim()=='' || $('#dteTo').val().trim()==''){
                enableButtons(true);
                $('#strResult').css('border-color','#FF0000');
                $('#strResult').css('color','#FF0000');
                $('#strResult').html('Por favor ingresa el periodo a descargar');
            }else{
                $('#strRFC').attr('disabled','disabled');
                $('#strCIEC').attr('disabled','disabled');
                $('#jcaptcha').attr('disabled','disabled');
                $('#strResult').css('border-color','#337ab7');
                $('#strResult').css('color','#337ab7');
                $('#strResult').html('Descargando ...');
                $strQueryString = "strRFC=" + $('#strRFC').val().trim();
                $strQueryString += "&strCIEC=" + $('#strCIEC').val().trim();
                $strQueryString += "&jcaptcha=" + $('#jcaptcha').val().trim();
                $strQueryString += "&strDocument=" + $('#strDocument').val();
                $strQueryString += "&strNuevosPc=" + strNuevos;
                $strQueryString += "&strYearFrom=" + $('#dteFrom').val().substring(0,4);
                $strQueryString += "&strMonthFrom=" + $('#dteFrom').val().substring(5,7);
                $strQueryString += "&strDayFrom=" + $('#dteFrom').val().substring(8,10);
                $strQueryString += "&strYearTo=" + $('#dteTo').val().substring(0,4);
                $strQueryString += "&strMonthTo=" + $('#dteTo').val().substring(5,7);
                $strQueryString += "&strDayTo=" + $('#dteTo').val().substring(8,10);
                $.ajax({url: "process.php", data: $strQueryString, type: "POST", dataType: "json",
                    success: function ($jsnPhpScriptResponse) {
                        enableButtons(true);
                        if($jsnPhpScriptResponse.strError!=''){
                            $('#strRFC').removeAttr('disabled');
                            $('#strCIEC').removeAttr('disabled');
                            $('#jcaptcha').removeAttr('disabled');
                            $('#strResult').css('border-color','#FF0000');
                            $('#strResult').css('color','#FF0000');
                            $('#strResult').html($jsnPhpScriptResponse);
                        }else{
                            $strResult = '';
                            $('#strResult').css('border-color','#07AA9E');
                            $('#strResult').css('color','#07AA9E');
                            if($jsnPhpScriptResponse.intXMLTotal>0){
                                $strResult += "<b>" + $jsnPhpScriptResponse.intXMLTotal + "</b> XML encontrado(s)<br /><br />";
                                $strResult += "<b>" + $jsnPhpScriptResponse.intXMLToDownload + "</b> XML fue(ron) descargado(s)<br /><br />";
                                if($jsnPhpScriptResponse.intXMLNewSupplier>0){
                                    $strResult += "<b>" + $jsnPhpScriptResponse.intXMLNewSupplier + "</b> proveedor(es) nuevo(s)<br /><br />";
                                }
                                if($jsnPhpScriptResponse.intXMLNewClient>0){
                                    $strResult += "<b>" + $jsnPhpScriptResponse.intXMLNewClient + "</b> cliente(s) nuevo(s)<br /><br />";
                                }
                                $strResult += "<b>" + $jsnPhpScriptResponse.intXMLDownloaded + "</b> XML ya se encuentra(n) en almacen temporal<br />";
                                $strResult += "<b>" + $jsnPhpScriptResponse.intXMLAssociated + "</b> XML ya se encuentra(n) asociado(s)";
                                if($jsnPhpScriptResponse.arrXMLs.length>0){
                                    $strGrid = '';
                                    for($intIx=0;$intIx<$jsnPhpScriptResponse.arrXMLs.length;$intIx++){
                                        $strGrid += '<tr>';
                                        $strGrid += '<td>' + $jsnPhpScriptResponse.arrXMLs[$intIx]['efectoComprobante'] + '</td>';
                                        $strGrid += '<td>' + $jsnPhpScriptResponse.arrXMLs[$intIx]['estadoComprobante'] + '</td>';
                                        $strGrid += '<td>' + $jsnPhpScriptResponse.arrXMLs[$intIx]['uuid'] + '</td>';
                                        $strGrid += '<td>' + $jsnPhpScriptResponse.arrXMLs[$intIx]['fechaEmision'] + '</td>';
                                        $strGrid += '<td>' + $jsnPhpScriptResponse.arrXMLs[$intIx]['nombreEmisor'] + '</td>';
                                        $strGrid += '<td>' + $jsnPhpScriptResponse.arrXMLs[$intIx]['rfcEmisor'] + '</td>';
                                        $strGrid += '<td>' + $jsnPhpScriptResponse.arrXMLs[$intIx]['nombreReceptor'] + '</td>';
                                        $strGrid += '<td>' + $jsnPhpScriptResponse.arrXMLs[$intIx]['rfcReceptor'] + '</td>';
                                        $strGrid += '<td>' + $jsnPhpScriptResponse.arrXMLs[$intIx]['total'] + '</td>';
                                        $strGrid += '</tr>';
                                    }
                                    $('#tblGrid tbody').append($strGrid);
                                    $('#divGrid').show();
                                }
                            }else{
                                $strResult += "<b>No se encontraron registros</b>";
                            }
                            $('#strResult').html($strResult);
                        }
                    },
                    error: function ($jqXHR, $textStatus, $errorThrown) {
                        enableButtons(true);
                        $('#strResult').css('border-color','#FF0000');
                        $('#strResult').css('color','#FF0000');
                        $('#strResult').html('Lo sentimos, los servidores de información del SAT no se encuentran disponibles, por favor reintenta más tarde: '+$errorThrown);
                    }
                });
            }
          }
        }
    }
}

function loadXMLDownloaderPage(){
    window.location.href = '../cont/massxml/index.php';
}

function backToDigitalWHouse(){
    window.history.back();
}

function enableButtons($blnEnable){
    if(!$blnEnable){
        $('#btnGET').attr('disabled','disabled');
        $('#btnBack').attr('disabled','disabled');
    }else{
        $('#btnGET').removeAttr('disabled');
        $('#btnBack').removeAttr('disabled');
    }
}