<style>
    tfoot, thead {
  background-color: #d3d3d3;
  color: #000000;
  font-size: 100%;
  font-weight: bold;
}
col-md-2 {
    margin: 100px;
    margin: 0;
    padding: 0;
}
</style>
<!DOCTYPE html>

<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Ventas Diarias</title>
    <link rel="stylesheet" href="">
</head>
    <link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../libraries/font-awesome/css/font-awesome.min.css">
    <script src="../../libraries/jquery.min.js"></script>
    <script src="../../libraries/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="../../libraries/numeric/jquery.numeric.js"></script>
    <script src="js/reportes.js"></script>
<!--Select 2 -->
    <script src="../../libraries/select2/dist/js/select2.min.js"></script>
    <link rel="stylesheet" type="text/css" href="../../libraries/select2/dist/css/select2.min.css" />
    <!-- Datepicker -->
    <link rel="stylesheet" href="../../libraries/datepicker/css/bootstrap-datepicker.min.css">
    <script src="../../libraries/datepicker/js/bootstrap-datepicker.min.js"></script>
    <script src="js/bootstrap-datepicker.es.js" type="text/javascript"></script>

    <!--Data Tables -->
    <link rel="stylesheet" href="../../libraries/dataTable/css/datatablesboot.min.css">
    <script src="../../libraries/dataTable/js/datatables.min.js"></script>
    <script src="../../libraries/dataTable/js/dataTables.bootstrap.min.js"></script>

    <!-- Modificaciones RC -->
    <script src="../../libraries/export_print/jquery.dataTables.min.js"></script>
    <script src="../../libraries/export_print/dataTables.buttons.min.js"></script>
    <script src="../../libraries/export_print/buttons.html5.min.js"></script>
    <script src="../../libraries/export_print/jszip.min.js"></script>

    <!--Button Print css -->
    <link rel="stylesheet" href="../../libraries/dataTable/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="../../libraries/dataTable/css/buttons.dataTables.min.css">
    <!--Button Print js -->
    <script src="../../libraries/dataTable/js/dataTables.buttons.min.js"></script>
    <script src="../../libraries/dataTable/js/buttons.print.min.js"></script>
   
<body> 
<div class="container" >
    <div class="panel panel-default">
        <div class="panel-heading"><h3>Ventas Diarias</h3></div>
        <div class="panel-body" style="padding-top:0px">
                <input id="hoy" type="hidden" value="<?php echo $fecha; ?>">
                <input id="diaH" type="hidden" value="<?php echo $fecha; ?>">
                <input id="diaD" type="hidden" value="<?php echo $nuevafecha; ?>">
                <br>
            <div style="text-align: right;">
                <i onclick="hoy();" class="fa fa-refresh fa-2x" aria-hidden="true" style="cursor: pointer;"></i>
            </div>

            <div id="divresult" class="col-md-12" style="text-align: center;">
            </div><br>
            
            <div style="text-align: right; margin-top: 350px;">
                <button class="btn btn-primary" onclick="reporte();">Reporte de Venta Global</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        reload();

        var hoy = $("#hoy").val();
        var diaH = $("#diaH").val();
        if(hoy == diaH){
            $('#btnnext').hide('slow');
        }else{
           $('#btnnext').show( "slow" );
        }
    });
    
    function mas(){
        var diaH = $("#diaH").val();
        $.ajax({
                url: 'ajax.php?c=reportes&f=mas',
                type: 'post',
                dataType: 'json',                
                data:{diaH:diaH},
                async:false
        })
        .done(function(data) {
          
            $("#diaD").val(data.diaD);
            $("#diaH").val(data.diaH);
            reload();
        })
    }
    function menos(){
        var diaH = $("#diaH").val();
        $.ajax({
                url: 'ajax.php?c=reportes&f=menos',
                type: 'post',
                dataType: 'json',
                data:{diaH:diaH},
                async:false
        })
        .done(function(data) {
            console.log(data);
            $("#diaD").val(data.diaD);
            $("#diaH").val(data.diaH);
            reload();
        })
    }
    function reload(){

            var diaD = $("#diaD").val();
            var diaH = $("#diaH").val();  
        
        
        $("#divresult").html('');
        $.ajax({
                url: 'ajax.php?c=reportes&f=reloadVD',
                type: 'post',
                data:{diaH:diaH,diaD:diaD},
                async:false
        })
        .done(function(data) {
            $("#divresult").hide();
            $("#divresult").append(data);
            $("#divresult").show();
            var hoy = $("#hoy").val();
            var diaH = $("#diaH").val();
            if(hoy == diaH){
                $('#btnnext').hide();
            }else{
               $('#btnnext').show(); 
            }
            $("#"+hoy).css({'background-color': '#428bca', 'color':'white'});
            $("#"+hoy+"2").css("border-color", "black");
        })

    }
    function reporte(){        
        window.parent.agregatab("../../modulos/pos/index.php?c=caja&f=ventasGrid","Venta Global","",2106);
    }

    function hoy(){
        location.reload();
    }

</script>


   
        
    
    



 

