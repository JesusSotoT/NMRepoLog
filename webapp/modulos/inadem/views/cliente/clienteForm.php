<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Formulario de Cliente</title>
    <link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../libraries/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="../../libraries/quaggajs/css/colors.css">
    <link rel="stylesheet" href="../../libraries/quaggajs/css/fonts.css">
    <link rel="stylesheet" href="../../libraries/quaggajs/css/styles.css">
    <script src="../../libraries/jquery.min.js"></script>
    <script src="../../libraries/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="../../libraries/numeric.js"></script>
    <script src="js/producto.js"></script>
    <script src="../../libraries/numeric/jquery.numeric.js"></script>
    <script src="https://rawgit.com/serratus/quaggaJS/441534cd8ba2ff15e4231ed41e77b10d5b3cc9ee/dist/quagga.min.js"></script>
<!--Select 2 -->
    <script src="../../libraries/select2/dist/js/select2.min.js"></script>
    <link rel="stylesheet" type="text/css" href="../../libraries/select2/dist/css/select2.min.css" />
    <link rel="stylesheet" type="text/css" href="js/select2/select2.css" />

    <script>
        $(document).ready(function() {
  
        });
    </script>
</head>
<body>
<div id="contenido" class="col-xs-12 container-fluid">
    <div class="nmwatitles">Clientes INADEM</div>
    <div class="panel panel-default">
        <div class="panel-heading">Cliente</div>
        <div class="panel-body">
            <input type="hidden" id="clienteIdInHidden" value="<?php echo $idCliente; ?>">
            <div class="row">
                <div class="col-sm-6">
                    <label>Factura</label>
                    <input type="text" class="form-control" id="factura">
                </div>
                <div class="col-sm-6">
                       <label>Archivos</label>
                    <input type="text" class="form-control" id="archivos">
                </div>
            </div>
            <div>Datos Basicos</div>
            <div class="col-xs-12" style="margin-bottom:1%;"> <!-- primera Fila  -->
              <div class="form-group">
                <label class="col-lg-2 control-label" style="padding-left:2.6%">Nombre:</label>
                <div class="col-lg-9" style="margin-left:-3.5%">
                  <input type="text" class="form-control" id="nombreCliente" value="<?php echo $clienteBasicos['basicos'][0]['razon_social']; ?>" readonly>
                </div>
              </div>
            </div> <!-- fin primera fila -->
            <div class="col-xs-12" style="margin-bottom:1%;"> <!-- segunda fila -->
                <div class="col-xs-6">
                    <div class="form-group">
                        <label class="col-lg-3 control-label">Direccion:</label>
                        <div class="col-lg-8">
                          <input type="text" class="form-control" id="direccion" value="<?php echo $clienteBasicos['basicos'][0]['domicilio'].' '.$clienteBasicos['basicos'][0]['num_ext']; ?>" readonly>
                        </div>
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label class="col-lg-2 control-label">Colonia:</label>
                        <div class="col-lg-7">
                          <input type="text" class="form-control" id="colonia" value="<?php echo $clienteBasicos['basicos'][0]['razon_social']; ?>" readonly>
                        </div>
                    </div>                    
                </div>
            </div> <!-- fin segunda fila -->
            <div class="col-xs-12" style="margin-bottom:1%;"><!-- tercera Fila -->
                <div class="col-xs-6">
                   <div class="form-group">
                        <label class="col-lg-3 control-label">Estado:</label>
                        <div class="col-lg-8">
                          <input type="text" class="form-control" id="estado" value="<?php echo $clienteBasicos['basicos'][0]['estadoname']; ?>" readonly>
                        </div>
                    </div>                    
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label class="col-lg-2 control-label">Municipio:</label>
                        <div class="col-lg-7">
                          <input type="text" class="form-control" id="municipio" value="<?php echo $clienteBasicos['basicos'][0]['municipio']; ?>" readonly>
                        </div>
                    </div> 
                </div>
            </div><!-- fin tercera fila -->
            <div class="col-xs-12" style="margin-bottom:1%;"><!-- cuarta fila -->
                <div class="col-xs-6">
                    <div class="form-group">
                        <label class="col-lg-3 control-label">Email:</label>
                        <div class="col-lg-8">
                          <input type="text" class="form-control" id="email" value="<?php echo $clienteBasicos['basicos'][0]['correo']; ?>" readonly>
                        </div>
                    </div>                   
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label class="col-lg-2 control-label">Telefono:</label>
                        <div class="col-lg-7">
                          <input type="text" class="form-control" id="telefono" value="<?php echo $clienteBasicos['basicos'][0]['celular']; ?>" readonly>
                        </div>
                    </div>
                </div>                
            </div><!-- fin cuarta fila  -->
            <div class="col-xs-12" style="margin-bottom:1%;"><!-- quinta fila -->
                <div class="col-xs-6">
                    <div class="form-group">
                        <label class="col-lg-3 control-label">Razon Social:</label>
                        <div class="col-lg-5">
                          <input type="text" class="form-control" id="razon_social" value="<?php echo $clienteBasicos['basicos'][0]['razon_social']; ?>" readonly>
                        </div>
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label class="col-lg-2 control-label">RFC:</label>
                        <div class="col-lg-5">
                          <input type="text" class="form-control" id="rfc" value="<?php echo $clienteBasicos['basicos'][0]['rfc']; ?>" readonly>
                        </div>
                    </div>                    
                </div>                
            </div><!-- fin quinta Fila -->
             <div>Datos Inadem</div>
            <div class="col-xs-12" style="margin-bottom:1%;"><!-- sexta fila -->
                <div class="col-xs-6">
                   <div class="form-group">
                        <label class="col-lg-3 control-label">Folio:</label>
                        <div class="col-lg-5">
                          <input type="text" class="form-control" id="folio" value="<?php echo $cliente['inadem'][0]['folio_inadem'];?>">
                        </div>
                    </div>                    
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label class="col-lg-2 control-label" style="padding-left:1%;">Convocatoria:</label>
                        <div class="col-lg-5">
                          <input type="text" class="form-control" id="convocatoria" value="<?php echo $cliente['inadem'][0]['convocatoria']; ?>">
                        </div>
                    </div> 
                </div>              
            </div><!-- fin sexta fila -->
            <div class="col-xs-12" style="margin-bottom:1%;"><!-- septima Fila -->
                <div class="col-xs-6">
                   <div class="form-group">
                        <label class="col-lg-3 control-label">Cupon:</label>
                        <div class="col-lg-5">
                          <input type="text" class="form-control" id="cupon" value="<?php echo $cliente['inadem'][0]['cupon']; ?>">
                        </div>
                    </div>                    
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label class="col-lg-2 control-label">Vitrina:</label>
                        <div class="col-lg-5">
                          <input type="text" class="form-control" id="vitrina" value="<?php echo $cliente['inadem'][0]['vitrina']; ?>">
                        </div>
                    </div>
                </div>                                                 
            </div><!-- fin septima fila -->
            <div class="col-xs-12">
                <div class="col-xs-6">
                    <div class="form-group">
                        <label class="col-lg-3 control-label">Mon. Beneficio:</label>
                        <div class="col-lg-5">
                          <input type="text" class="form-control" id="beneficio" value="<?php echo $cliente['inadem'][0]['monto_beneficio'];?>">
                        </div>
                    </div> 
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label class="col-lg-2 control-label">Mon. Aportacion:</label>
                        <div class="col-lg-5">
                          <input type="text" class="form-control" id="aportacion" value="<?php echo $cliente['inadem'][0]['monto_aportacion'];?>" >
                        </div>
                    </div>
                </div>                 
            </div>
            <div class="col-xs-12"><!-- octava fila -->
                <div class="col-xs-6">
                    <div class="form-group">
                        <label class="col-lg-3 control-label">Organismo Intermedio:</label>
                        <div class="col-lg-5">
                          <input type="text" class="form-control" id="org_int" value="<?php echo $cliente['inadem'][0]['organismo_inter'];?>">
                        </div>
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label class="col-lg-2 control-label">Promotor:</label>
                        <div class="col-lg-7">
                          <input type="text" class="form-control" id="promotor" value="<?php echo $cliente['inadem'][0]['promotor'];?>">
                        </div>
                    </div>                    
                </div>                  
            </div><!-- fin octava  fila -->
            <div class="col-xs-12"><!-- novena fila -->
                <div class="col-xs-6">
                    <div class="form-group">
                        <label class="col-lg-3 control-label">Responsable NWM:</label>
                        <div class="col-lg-5">
                          <input type="text" class="form-control" id="resp_nwm" value="<?php echo $cliente['inadem'][0]['resp_nwm'];?>">
                        </div>
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label class="col-lg-2 control-label">Fecha:</label>
                        <div class="col-lg-7">
                          <input type="text" class="form-control" id="fecha" value="<?php echo $cliente['inadem'][0]['fecha_entrega'];?>">
                        </div>
                    </div>                    
                </div>                  
            </div><!-- fin novena  fila -->            
            <div class="col-xs-12"><!-- decima fila -->
                <div class="col-xs-6">
                    <div class="form-group">
                        <label class="col-lg-3 control-label">Instancia:</label>
                        <div class="col-lg-5">
                          <input type="text" class="form-control" id="instancia" value="<?php echo $cliente['inadem'][0]['instancia'];?>">
                        </div>
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label class="col-lg-2 control-label">Responsable Legal:</label>
                        <div class="col-lg-7">
                          <input type="text" class="form-control" id="resp_legal" value="<?php echo $cliente['inadem'][0]['resp_legal'];?>">
                        </div>
                    </div>                    
                </div>                  
            </div><!-- fin decima  fila -->
            <div class="col-xs-12"><!-- undecima fila -->
                <div class="col-xs-6"><input type="button" onclick="backToGrid();" value="Regresar" class="btn btn-default"></div>
                <div class="col-xs-6"><div style="padding-left:86%;"><input type="button" onclick="saveClient();" value="Guardar" class="btn btn-primary"></div></div>
            </div><!-- fin undecima fila -->
        </div>
    </div>
</div>    
    
</body>
</html>