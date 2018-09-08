
<html lang="es">
<head>
    <meta http-equiv="Expires" content="0">
    <title>Cliente INADEM</title>
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/typeahead.css" />
    <link rel="stylesheet" href="css/caja/caja.css" />

    <?php include('../../netwarelog/design/css.php');?>
    <LINK href="../../netwarelog/design/<?php echo $strGNetwarlogCSS;?>/netwarlog.css" title="estilo" rel="stylesheet" type="text/css" /> <!--NETWARLOG CSS-->


    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/cliente/cliente.js" ></script>
    <script type="text/javascript" src="js/typeahead.js" ></script>
    <script src="js/select2/select2.min.js"></script>
    <link rel="stylesheet" type="text/css" href="js/select2/select2.css" />

    <script>
        $(document).ready(function() {
            //pintaGrid();
            $("#folio").select2({
             width : "100px"
            });
            $("#convocatoria").select2({
                 width : "150px"
            }); 
            $("#organismo").select2({
                 width : "150px"
            }); 
        });
    </script>
<style type="text/css">
a:link {text-decoration:none;color:#000000;}
a:visited {text-decoration:none;color:#000000;}
a:active {text-decoration:none;color:#000000;}
a:hover {text-decoration:underline;color:#000000;}
</style>


</head>

<body>
<div id="contenido" class="col-xs-12 container-fluid">
   <div class="nmwatitles">Clientes INADEM</div>
  <div class="col-xs-12" style="margin-top:2%;">
    <div class="col-xs-3">
      <label>Folio:</label>
      <select id="folio">
        <option value="0">--Selecciona --</option>
        <?php 
        foreach ($filtros['folio'] as $key => $value) {
          echo '<option value="'.$value['folio_inadem'].'">'.$value['folio_inadem'].'</option>';
        }

        ?>
      </select>
    </div>
    <div class="col-xs-3">
        <label>Convocatoria</label>
      <select id="convocatoria">
        <option value="0">--Selecciona --</option>
        <?php 
        foreach ($filtros['convocatoria'] as $key => $value) {
          echo '<option value="'.$value['convocatoria'].'">'.$value['convocatoria'].'</option>';
        }

        ?>
      </select>
    </div>
    <div class="col-xs-3">
        <label>Organismo</label>
      <select id="organismo">
        <option value="0">--Selecciona --</option>
        <?php 
        foreach ($filtros['organismo'] as $key => $value) {
          echo '<option value="'.$value['organismo_inter'].'">'.$value['organismo_inter'].'</option>';
        }

        ?>
      </select>
    </div>
    <div class="col-xs-3">
    <!--  <div style="float:left;margin-top:4%">
        <label>Fecha:</label>
          <input type="text" id="desde" class="nminputtext">
          <input type="text" id="hasta" class="nminputtext">
          
      </div> -->
      <input type="button" value="Buscar" onclick="busca();" class="nminputbutton">
    </div>
  </div>
   <div class="col-xs-12" style="margin-top:1% ;margin-bottom:1%">
  <!--  <div style="padding-left:90%;">
      <input type="button" onclick="createnew();" value="Crea Cotizacion" class="nminputbutton_color2">
    </div> -->
  </div>
   <div class="col-xs-12" id="gridCoti">

       <table class="table display" id="Gridinadem">
           <thead>
               <tr>
                   <th class="nmcatalogbusquedatit">ID</th>
                   <th class="nmcatalogbusquedatit">Cliente</th>
                   <th class="nmcatalogbusquedatit">Folio</th>
                   <th class="nmcatalogbusquedatit">Convocatoria</th>
                   <th class="nmcatalogbusquedatit">Vitrina</th>
                   <th class="nmcatalogbusquedatit">Cupon</th>
                   <th class="nmcatalogbusquedatit">Promotor</th>
                   <th class="nmcatalogbusquedatit">Organismo</th>
                   <th class="nmcatalogbusquedatit">Responsable</th>
               </tr>
           </thead>
           <?php 
            foreach ($clientes['grid'] as $key => $value) {
                $rowsGrid .="<tr class='rowsTable'>";
                $rowsGrid .="<td><a href='ajax.php?c=cliente&f=clienteForm&pe=".$value['id']."'>".$value['id']."</a></td>";
                $rowsGrid .="<td><a href='ajax.php?c=cliente&f=clienteForm&pe=".$value['id']."'>".$value['nombre']."</a></td>";
                $rowsGrid .="<td><a href='ajax.php?c=cliente&f=clienteForm&pe=".$value['id']."'>".$value['folio_inadem']."</a></td>";
                $rowsGrid .="<td><a href='ajax.php?c=cliente&f=clienteForm&pe=".$value['id']."'>".$value['convocatoria']."</a></td>";
                $rowsGrid .="<td><a href='ajax.php?c=cliente&f=clienteForm&pe=".$value['id']."'>".$value['vitrina']."</a></td>";
                $rowsGrid .="<td><a href='ajax.php?c=cliente&f=clienteForm&pe=".$value['id']."'>".$value['cupon']."</a></td>";
                $rowsGrid .="<td><a href='ajax.php?c=cliente&f=clienteForm&pe=".$value['id']."'>".$value['promotor']."</a></td>";
                $rowsGrid .="<td><a href='ajax.php?c=cliente&f=clienteForm&pe=".$value['id']."'>".$value['organismo_inter']."</a></td>";
                $rowsGrid .="<td><a href='ajax.php?c=cliente&f=clienteForm&pe=".$value['id']."'>".$value['resp_nwm']."</a></td>";
                $rowsGrid .="</tr>";
            }
            echo $rowsGrid;

           ?>
       </table>
   </div>

</div>



</body> 
</html>