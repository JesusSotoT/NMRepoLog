
$('[title="<?php echo $strHide; ?>"]').css('display','none');

$strForm  = '<tr class=" nmcataloglistadofila ">';
$strForm += '   <td>';
$strForm += '       <label id="pais">Pais</label><label style="color:#FF0000; font-weight:bold;"> *</label><br /><select id="txtCountries" onchange="searchAddress(1)" class=" nminputselect ">';
$strForm += '           <option selected>- Pais -</option>';
<?php
$objCon = mysqli_connect($servidor,"nmdevel","nmdevel","nmdev_common");
mysqli_set_charset($objCon,"utf8");
$strSql = "SELECT DISTINCT(Pais) FROM cat_direcciones;";
$rstCountries = mysqli_query($objCon, $strSql);
while($objCountries = mysqli_fetch_assoc($rstCountries)){
?>
    $strForm += '           <option id="<?php echo $objCountries['Pais']; ?>"><?php echo $objCountries['Pais']; ?></option>';
<?php
}
unset($objCountries);
mysqli_free_result($rstCountries);
unset($rstCountries);
mysqli_close($objCon);
unset($objCon);
?>
$strForm += '       </select>';
$strForm += '   </td>';
$strForm += '</tr>';
$strForm += '<tr class=" nmcataloglistadofila " id="rowStates" style="display:block">';
$strForm += '   <td>';
$strForm += '       <label id="estado">Estado</label><label style="color:#FF0000; font-weight:bold;"> *</label><br />';
$strForm += '       <select id="txtStates" onchange="searchAddress(2)" class=" nminputselect ">';
$strForm += '           <option selected>- Estado -</option>';
$strForm += '       </select>';
$strForm += '   </td>';
$strForm += '</tr>';
$strForm += '<tr class=" nmcataloglistadofila " id="rowTowns" style="display:block">';
$strForm += '   <td>';
$strForm += '       <label id="municipio">Municipio</label><label style="color:#FF0000; font-weight:bold;"> *</label><br />';
$strForm += '       <select id="txtTowns" onchange="searchAddress(3)" class=" nminputselect ">';
$strForm += '           <option selected>- Municipio -</option>';
$strForm += '       </select>';
$strForm += '   </td>';
$strForm += '</tr>';
$strForm += '<tr class=" nmcataloglistadofila " id="rowSettlements" style="display:block">';
$strForm += '   <td>';
$strForm += '       <label id="colonia">Colonia</label><label style="color:#FF0000; font-weight:bold;"> *</label><br />';
$strForm += '       <select id="txtSettlements" onchange="searchAddress(4)" class=" nminputselect ">';
$strForm += '           <option selected>- Colonia -</option>';
$strForm += '       </select>';
$strForm += '   </td>';
$strForm += '</tr>';
$strForm += '<tr class=" nmcataloglistadofila " id="rowZipcodes" style="display:block">';
$strForm += '   <td>';
$strForm += '       <label id="cp">Codigo Postal</label><label style="color:#FF0000; font-weight:bold;"> *</label><br />';
$strForm += '       <select id="txtZipcodes" onchange="searchAddress(5)" class=" nminputselect ">';
$strForm += '           <option selected>- Codigo Postal -</option>';
$strForm += '       </select>';
$strForm += '   </td>';
$strForm += '</tr>';
$($strForm).insertAfter('[title="<?php echo $strInsertAfter; ?>"]');

function searchAddress($intVal){
    switch ($intVal){
        case 1:
            $strCountry =$('#txtCountries').val().replace("- Pais -","");
            $('#txtStates').empty();
            $('#txtStates').append('<option selected>- Estado -</option>');
            $('#txtTowns').empty();
            $('#txtTowns').append('<option selected>- Municipio -</option>');
            $('#txtSettlements').empty();
            $('#txtSettlements').append('<option selected>- Colonia -</option>');
            $('#txtZipcodes').empty();
            $('#txtZipcodes').append('<option selected>- Codigo Postal -</option>');
            $('#<?php echo $strInput; ?>').val('');
            if($strCountry!=''){
                $.post('../../modulos/netwarmonitor/getaddress.php',{strCountry:$strCountry,intOption:1},function($databack) {
                    $('#txtStates').append($databack);
                    $('#rowStates').slideDown('slow');
                });
            };
            break;
        case 2:
            $strCountry =$('#txtCountries').val().replace("- Pais -","");
            $strState =$('#txtStates').val().replace("- Estado -","");
            $('#txtTowns').empty();
            $('#txtTowns').append('<option selected>- Municipio -</option>');
            $('#txtSettlements').empty();
            $('#txtSettlements').append('<option selected>- Colonia -</option>');
            $('#txtZipcodes').empty();
            $('#txtZipcodes').append('<option selected>- Codigo Postal -</option>');
            $('#<?php echo $strInput; ?>').val('');
            if($strState!=''){
                $.post('../../modulos/netwarmonitor/getaddress.php',{strCountry:$strCountry,strState:$strState,intOption:2},function($databack) {
                    $('#txtTowns').append($databack);
                    $('#rowTowns').slideDown('slow');
                });
            };
            break;
        case 3:
            $strCountry =$('#txtCountries').val().replace("- Pais -","");
            $strState =$('#txtStates').val().replace("- Estado -","");
            $strTown =$('#txtTowns').val().replace("- Municipio -","");
            $('#txtSettlements').empty();
            $('#txtSettlements').append('<option selected>- Colonia -</option>');
            $('#txtZipcodes').empty();
            $('#txtZipcodes').append('<option selected>- Codigo Postal -</option>');
            $('#<?php echo $strInput; ?>').val('');
            if($strTown!=''){
                $.post('../../modulos/netwarmonitor/getaddress.php',{strCountry:$strCountry,strState:$strState,strTown:$strTown,intOption:3},function($databack) {
                    $('#txtSettlements').append($databack);
                    $('#rowSettlements').slideDown('slow');
                });
            };
            break;
        case 4:
            $strCountry =$('#txtCountries').val().replace("- Pais -","");
            $strState =$('#txtStates').val().replace("- Estado -","");
            $strTown =$('#txtTowns').val().replace("- Municipio -","");
            $strSettlement =$('#txtSettlements').val().replace("- Colonia -","");
            $('#txtZipcodes').empty();
            $('#txtZipcodes').append('<option selected>- Codigo Postal -</option>');
            $('#<?php echo $strInput; ?>').val('');
            if($strSettlement!=''){
                $.post('../../modulos/netwarmonitor/getaddress.php',{strCountry:$strCountry,strState:$strState,strTown:$strTown,strSettlement:$strSettlement,intOption:4},function($databack) {
                    $('#txtZipcodes').append($databack);
                    $('#rowZipcodes').slideDown('slow');
                });
            };
            break;
        case 5:
            $strCountry =$('#txtCountries').val().replace("- Pais -","");
            $strState =$('#txtStates').val().replace("- Estado -","");
            $strTown =$('#txtTowns').val().replace("- Municipio -","");
            $strSettlement =$('#txtSettlements').val().replace("- Colonia -","");
            $strZipcode =$('#txtZipcodes').val().replace("- Codigo Postal -","");
            $('#<?php echo $strInput; ?>').val('');
            if($strZipcode!=''){
                $.post('../../modulos/netwarmonitor/getaddress.php',{strCountry:$strCountry,strState:$strState,strTown:$strTown,strSettlement:$strSettlement,strZipcode:$strZipcode,intOption:5},function($databack) {
                    $('#<?php echo $strInput; ?>').val($databack);
                });
            };
            break;
    };
}