<?php session_start(); ?>
<html>
  <head>
    <link href="uploadify/uploadify.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" media="screen" href="jqgrid/css/ui.jqgrid.css" />
    <link rel="stylesheet" type="text/css" media="screen" href="jqgrid/css/custom.css" />
    <link rel="stylesheet" type="text/css" media="screen" href="jqgrid/plugins/ui.multiselect.css" />

    <?php 

    include('../../netwarelog/design/css.php');

    ?>
    <LINK href="../../netwarelog/design/<?php echo $strGNetwarlogCSS;?>/netwarlog.css" title="estilo" rel="stylesheet" type="text/css" /> <!--NETWARLOG CSS-->

    <link rel="stylesheet" type="text/css" href="../../netwarelog/catalog/css/estilo.css" title="estilo"   />
    <link rel="stylesheet" type="text/css" href="../../netwarelog/catalog/css/view.css"   title="estilo"  />
    <link rel="stylesheet" type="text/css" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
    <link rel="stylesheet" type="text/css" href="js/multi-select.css" />


    <script src="jqgrid/js/jquery-1.11.0.min.js" type="text/javascript"></script>
    <script src="uploadify/jquery.uploadify.js"></script>

    <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
    <script type="text/javascript" src="../../modulos/xtructur/js/jquery.numeric.js"></script>
    <script type="text/javascript" src="../../modulos/xtructur/js/json2.js"></script>

    <script src="jqgrid/js/i18n/grid.locale-es.js" type="text/javascript"></script>
    <script src="js/numeric.js" type="text/javascript"></script>
    <script src="js/jquery.multi-select.js" type="text/javascript"></script>

    
    <script type="text/javascript">
      $.jgrid.no_legacy_api = true;
      $.jgrid.useJSON = true;
    </script>

    <script src="jqgrid/js/jquery.jqGrid.min.js" type="text/javascript"></script>
    <script type="text/javascript" src="js/jqgridExcelExportClientSide.js" ></script>
    <script type="text/javascript" src="js/jqgridExcelExportClientSide-libs.js" ></script>
    <link rel="stylesheet" type="text/css" media="screen" href="jqgrid/css/custom2.css" />
    <script src="funcionesIndex.js" type="text/javascript"></script>
    <script src="moneda.js" type="text/javascript"></script>
    <script src="js/date_esp.js" type="text/javascript"></script>

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