<html>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<?php 
$this->load->helper('url');
$base_url=str_replace("modulos/mrp/","",base_url());
?>

<LINK href="<?php echo $base_url; ?>netwarelog/catalog/css/view.css" title="estilo" rel="stylesheet" type="text/css" />
<LINK href="<?php echo $base_url; ?>netwarelog/catalog/css/estilo.css" title="estilo" rel="stylesheet" type="text/css" />	
<!--<LINK href="<?php echo $base_url; ?>netwarelog/design/default/netwarlog.css" title="estilo" rel="stylesheet" type="text/css" / -->	
	    <?php include('../../netwarelog/design/css.php');?>
	    <LINK href="<?php echo $base_url; ?>netwarelog/design/<?php echo $strGNetwarlogCSS;?>/netwarlog.css" title="estilo" rel="stylesheet" type="text/css" /> <!--NETWARLOG CSS-->

<LINK href="<?php echo base_url(); ?>css/mrp.css" title="estilo" rel="stylesheet" type="text/css" />
 <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
 <link rel="stylesheet" href="../../../../libraries/bootstrap/dist/css/bootstrap.min.css" />
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>

<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script src="<?php echo base_url(); ?>js/ui.datepicker-es-MX.js"></script>
<script type="text/javascript">var baseUrl='<?php echo base_url(); ?>';</script>	
<script type="text/javascript" src="<?php echo base_url(); ?>js/inventary.js"></script>
<script>
$(function(){
	$.datepicker.setDefaults($.datepicker.regional['es-MX']);
	$("#ffin").datepicker({dateFormat: "yy-mm-dd"});
	$("#finicio").datepicker({dateFormat: "yy-mm-dd",onSelect: function (dateText, inst) {
	  var parsedDate = $.datepicker.parseDate('yy-mm-dd', dateText);
		$('#ffin').datepicker('setDate', parsedDate);
		$('#ffin').datepicker( "option", "minDate", parsedDate);
	}});
	
	
	

	
});
</script>
<style type="text/css">
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
</style>
<body>

<div class="container" style="width:100%">
    <div class="row">
        <div class="col-md-12">
            <h3 class="nmwatitles text-center">
                Historico existencias<br>
            </h3>
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <div class="row">
                        <div class="col-md-3 col-sm-3">
                            <label>Producto:</label>
                            <?php echo $productos; ?>
                        </div>
                        <div class="col-md-3 col-sm-3">
                            <label>Sucursal:</label>
                            <?php echo $sucursales; ?>
                        </div>
                        <div class="col-md-3 col-sm-3">
                            <label>Almacen:</label>
                            <section id="almacenes">
                            	<select class="form-control">
                            		<option value="">-Seleccione-</option>
                            	</select>
                            </section>
                        </div>
                        <div class="col-md-3 col-sm-3">
                            <label>Inicio:</label>
                            <input type="text" readonly id="finicio" class="form-control">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 col-sm-3">
                            <label>Fin:</label>
                            <input type="text" readonly id="ffin"  class="form-control">
                        </div>
                        <div class="col-md-3 col-sm-3">
                        	<label>&nbsp;</label>
                        	<input type="button" value="Ver existencias" onClick="Verexistencias();" class="btn btn-primary btnMenu">
                        </div>
                        <div class="col-md-3 col-sm-3">
                        </div>
                        <div class="col-md-3 col-sm-3" id="preloader">
                            <label style="color:green;">Espera un momento...</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12 tablaResponsiva" style="margin-bottom: 5em;">
                            <div class="table-responsive" id="existencias" style="border: 1px solid black; margin-bottom: 5em;">
                                <?php echo $existencias; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>