<?php session_start(); ?>
<html>
  <head>
    <!--Estilo custom font-size del grid y anchos de pagers-->
    <link rel="stylesheet" type="text/css" media="screen" href="jqgrid/css/custom.css" />
    <link rel="stylesheet" type="text/css" media="screen" href="jqgrid/plugins/ui.multiselect.css" />
    <link rel="stylesheet" type="text/css" href="js/multi-select.css" />

    <!--Estilo para el uploadify carga de plantillas xls -->
    <link href="uploadify/uploadify.css" rel="stylesheet">

    <!--Jquery y jquery ui que incluye los datepickers -->
    <script src="jqgrid/js/jquery-1.11.0.min.js" type="text/javascript"></script>
    <script src="jqueryui.js"></script>

    <!-- Uploadify -->
    <script src="uploadify/jquery.uploadify.js"></script>

    <!--JQgrid para todos los gris de xtructur-->
    <script type="text/javascript" src="jqgridmin.js"></script>

    <!--SimpleUpload.js para todos los gris de xtructur-->
    <script type="text/javascript" src="simpleUpload.js"></script>

    <!--Exportaciones a excel por medio del jqgrid-->
    <script type="text/javascript" src="js/jqgridExcelExportClientSide.js" ></script>
    <script type="text/javascript" src="js/jqgridExcelExportClientSide-libs.js" ></script>
    <!-- -->

    <link rel="stylesheet" type="text/css" href="jqgridboot.css">
    
    <!-- Jqgrid en español -->
    <script src="jqgrid/js/i18n/grid.locale-es.js" type="text/javascript"></script>


    <!-- Indicar que se usara Jqgrid con bootstrap -->
    <script type="text/javascript">
        $.jgrid.no_legacy_api = true;
        $.jgrid.useJSON = true;

        $.jgrid.defaults.width = 980;
        $.jgrid.defaults.responsive = true;
        $.jgrid.defaults.styleUI = 'Bootstrap';
    </script>

    <!-- Todas las funciones JS de xtructur importante! -->
    <script src="funcionesIndex.js" type="text/javascript"></script>

    <!-- Todas las exportaciones a xls de los grids de xtructur importante! -->
    <script src="funcionesXls.js" type="text/javascript"></script>

    <!-- Librerias JS utiles-->
    <script src="js/jquery.multi-select.js" type="text/javascript"></script>
    <script src="js/numeric.js" type="text/javascript"></script>
    <script src="moneda.js" type="text/javascript"></script>

    <!-- Fechas datepicker en español -->
    <script src="js/date_esp.js" type="text/javascript"></script>

    <!-- Libreria JS de bootstrap utilizada -->
    <script src="../../libraries/bootstrap/dist/js/bootstrap.min.js" type="text/javascript"></script>


    

    <script>
        var modulo = "<?php echo $modulo; ?>";
    </script>
    <style>
     body{
        padding-bottom: 200px;

     }
     [disabled="disabled"]{
           background-color: #bababa !important; 
        }

    .container .navbar{
       /* border:1px solid #ff7300; */
    }
    .pl{
        padding-left: 5px;
    }
    .p0{
        margin: 0px;
    }
    </style>
  </head>
<?php
    function NumeroSemanasTieneUnAno($ano){
        $date = new DateTime;
        $date->setISODate("$ano", 53);
        if($date->format("W")=="53")
            return 53;
        else
            return 52;
    }

    function getweek($fecha){
        $date = new DateTime($fecha);
        $week = $date->format("W");
        return $week;
    }

    function week_bounds( $date, &$start, &$end ) {
        $date = strtotime( $date );
        // Find the start of the week, working backwards
        $start = $date;
        while( date( 'w', $start ) > 1 ) {
          $start -= 86400; // One day
        }
        // End of the week is simply 6 days from the start
        $end = date( 'Y-m-d', $start + ( 6 * 86400 ) );
        $start = date( 'Y-m-d', $start );
    }

    function getStartAndEndDate($week, $year) {
      $dto = new DateTime();
      $dto->setISODate($year, $week);
      $ret[0] = $dto->format('Y-m-d');
      $dto->modify('+6 days');
      $ret[1] = $dto->format('Y-m-d');
      return $ret;
    }


?>