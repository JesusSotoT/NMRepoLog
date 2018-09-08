<!DOCTYPE html>
<html>
<?php 
$this->load->helper('url');
$base_url=str_replace("modulos/mrp/","",base_url());
?>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<LINK href="<?php echo $base_url; ?>netwarelog/catalog/css/view.css" title="estilo" rel="stylesheet" type="text/css" />
<LINK href="<?php echo $base_url; ?>netwarelog/catalog/css/estilo.css" title="estilo" rel="stylesheet" type="text/css" />	
<!--<LINK href="<?php echo $base_url; ?>netwarelog/design/default/netwarlog.css" title="estilo" rel="stylesheet" type="text/css" / -->   
<?php include('../../netwarelog/design/css.php');?>
<LINK href="<?php echo $base_url; ?>netwarelog/design/<?php echo $strGNetwarlogCSS;?>/netwarlog.css" title="estilo" rel="stylesheet" type="text/css" /> <!--NETWARLOG CSS-->

<LINK href="<?php echo base_url(); ?>css/mrp.css" title="estilo" rel="stylesheet" type="text/css" />

<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">

<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>

<script type="text/javascript">var baseUrl='<?php echo base_url(); ?>';</script>	
<script type="text/javascript" src="<?php echo base_url(); ?>js/inventary.js"></script>
<script  type="text/javascript" src="<?php echo base_url(); ?>js/jTPS.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>js/csstest.css">
<link rel="stylesheet" href="../../../../libraries/bootstrap/dist/css/bootstrap.min.css" />
<script>
	// $(function(){
// 		
	  // $("#search-producto").autocomplete({delay: 0,source:"<?php echo $base_url; ?>modulos/punto_venta/autocompleteProductos.php",
	  // search: function( event, ui ){                      },
	  // select: function( event, ui ){  $("#producto").val(ui.item.id);                     }
	  // });
// 	
	// });
</script>

<link rel="stylesheet" href="../../css/imprimir_bootstrap.css" />
  <style>
      .ui-autocomplete {
        max-height: 250px;
        overflow-y: auto;
        /* prevent horizontal scrollbar */
        overflow-x: hidden;
      }
      /* IE 6 doesn't support max-height
       * we use height instead, but this forces the menu to always be this tall
       */
      * html .ui-autocomplete {
        height: 250px;
      }
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
            .pagination2, input[type='button'], input[type='submit'], img{
                display: none;
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
                Existencias<br>
                <a href="javascript:window.print();"><img class="nmwaicons" src="../../../../netwarelog/design/default/impresora.png" border="0"></a>
            </h3>
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <div class="row">
                        <div class="col-md-3 col-sm-3">
                            <label>Seleccione la sucursal:</label>
                            <?php echo $sucursales; ?>
                        </div>
                        <div class="col-md-3 col-sm-3">
                            <label>Seleccione el almacen:</label>
                            <section id="almacenes">
                                <select class="form-control">
                                    <option value=0 selected>-Seleccione un almacen-</option>
                                </select>
                            </section>
                        </div>
                        <div class="col-md-3 col-sm-3">
                            <label>Seleccione el producto:</label>
                            <input type="text" id="search-producto" placeholder="Ingrese código o descripción......" class="form-control" />
                            <input type="hidden" id="producto"  />
                        </div>
                        <div class="col-md-3 col-sm-3">
                            <label>Solo productos con existencia:</label>
                            <input type="radio" name="conexistencia" value="1" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 col-sm-3">
                            <label>Solo productos sin existencia:</label>
                            <input type="radio" name="conexistencia" value="2" />
                        </div>
                        <div class="col-md-3 col-sm-3">
                            <label>TODOS:</label>
                            <input type="radio" name="conexistencia" value="0" />
                        </div>
                        <div class="col-md-3 col-sm-3">
                            <input type="button" value="Ver Existencias" onClick="VerExistencias();" class="btn btn-primary btnMenu">
                        </div>
                        <div class="col-md-3 col-sm-3" id="preloader">
                            <label style="color:green;">Espera un momento...</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12 tablaResponsiva">
                            <div class="table-responsive" style="border: 1px solid black; margin-bottom: 5em;">
                                <table style="table-layout: unset !important; width: 100% !important;" class="busqueda" cellpadding="3"  align="center" cellspacing="0" height="0"  id="orden">
                                    <thead>
                                    <tr class="tit_tabla_buscar">
                                    <td class="nmcatalogbusquedatit" align="center" sort="codigo">Código</td>
                                    <td class="nmcatalogbusquedatit" align="center" sort="descript">Descripción</td>
                                    <td class="nmcatalogbusquedatit" align="center" sort="almacen">Almacen</td>
                                    <td class="nmcatalogbusquedatit" align="center" sort="min">Minimo</td>
                                    <td class="nmcatalogbusquedatit" align="center" sort="max">Máximo</td>
                                    <td class="nmcatalogbusquedatit" align="center" sort="uni">Unidad</td>
                                    <td class="nmcatalogbusquedatit" align="center" sort="exist">Existencia</td></tr>
                                    </thead>
                                    <tbody>
                                        <?php echo $inventario; ?> 
                                    </tbody>
                                    <tfoot class="nav pagination2">
                                        <tr align="right">
                                            <td colspan=7>
                                                <div class="pagination"></div>
                                                <div class="paginationTitle">Pagina</div>
                                                <div class="selectPerPage"></div>
                                            </td>
                                        </tr>
                                    </tfoot> 
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<span id="stock"></span>
<div class="dialog"></div>

<script>
function carga(){
                $(document).ready(function () {
               
                        $('#orden').jTPS( {perPages:[5,12,15,50,'TODO'],scrollStep:1,scrollDelay:30,
                                clickCallback:function () {    
                                        // target table selector
                                        var table = '#orden';
                                        // store pagination + sort in cookie
                                        document.cookie = 'jTPS=sortasc:' + $(table + ' .sortableHeader').index($(table + ' .sortAsc')) + ',' +
                                                'sortdesc:' + $(table + ' .sortableHeader').index($(table + ' .sortDesc')) + ',' +
                                                'page:' + $(table + ' .pageSelector').index($(table + ' .hilightPageSelector')) + ';';
                                }
                        });

                        // reinstate sort and pagination if cookie exists
                        var cookies = document.cookie.split(';');
                        for (var ci = 0, cie = cookies.length; ci < cie; ci++) {
                                var cookie = cookies[ci].split('=');
                                if (cookie[0] == 'jTPS') {
                                        var commands = cookie[1].split(',');
                                        for (var cm = 0, cme = commands.length; cm < cme; cm++) {
                                                var command = commands[cm].split(':');
                                                if (command[0] == 'sortasc' && parseInt(command[1]) >= 0) {
                                                        $('#orden .sortableHeader:eq(' + parseInt(command[1]) + ')').click();
                                                } else if (command[0] == 'sortdesc' && parseInt(command[1]) >= 0) {
                                                        $('#orden .sortableHeader:eq(' + parseInt(command[1]) + ')').click().click();
                                                } else if (command[0] == 'page' && parseInt(command[1]) >= 0) {
                                                        $('#orden .pageSelector:eq(' + parseInt(command[1]) + ')').click();
                                                }
                                        }
                                }
                        }

                        // bind mouseover for each tbody row and change cell (td) hover style
                        $('#orden tbody tr:not(.stubCell)').bind('mouseover mouseout',
                                function (e) {
                                        // hilight the row
                                        e.type == 'mouseover' ? $(this).children('td').addClass('hilightRow') : $(this).children('td').removeClass('hilightRow');
                                }
                        );

                });

}
 $(document).ready(function () {
               
                        $('#orden').jTPS( {perPages:[5,12,15,50,'TODO'],scrollStep:1,scrollDelay:30,
                                clickCallback:function () {    
                                        // target table selector
                                        var table = '#orden';
                                        // store pagination + sort in cookie
                                        document.cookie = 'jTPS=sortasc:' + $(table + ' .sortableHeader').index($(table + ' .sortAsc')) + ',' +
                                                'sortdesc:' + $(table + ' .sortableHeader').index($(table + ' .sortDesc')) + ',' +
                                                'page:' + $(table + ' .pageSelector').index($(table + ' .hilightPageSelector')) + ';';
                                }
                        });

                        // reinstate sort and pagination if cookie exists
                        var cookies = document.cookie.split(';');
                        for (var ci = 0, cie = cookies.length; ci < cie; ci++) {
                                var cookie = cookies[ci].split('=');
                                if (cookie[0] == 'jTPS') {
                                        var commands = cookie[1].split(',');
                                        for (var cm = 0, cme = commands.length; cm < cme; cm++) {
                                                var command = commands[cm].split(':');
                                                if (command[0] == 'sortasc' && parseInt(command[1]) >= 0) {
                                                        $('#orden .sortableHeader:eq(' + parseInt(command[1]) + ')').click();
                                                } else if (command[0] == 'sortdesc' && parseInt(command[1]) >= 0) {
                                                        $('#orden .sortableHeader:eq(' + parseInt(command[1]) + ')').click().click();
                                                } else if (command[0] == 'page' && parseInt(command[1]) >= 0) {
                                                        $('#orden .pageSelector:eq(' + parseInt(command[1]) + ')').click();
                                                }
                                        }
                                }
                        }

                        // bind mouseover for each tbody row and change cell (td) hover style
                        $('#orden tbody tr:not(.stubCell)').bind('mouseover mouseout',
                                function (e) {
                                        // hilight the row
                                        e.type == 'mouseover' ? $(this).children('td').addClass('hilightRow') : $(this).children('td').removeClass('hilightRow');
                                }
                        );

                });

        </script>

</body>
</html>