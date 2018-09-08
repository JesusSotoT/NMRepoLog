<?php
//ini_set('display_errors', 1);
require("models/connection_sqli.php"); // funciones mySQLi

class cotizacionModel extends Connection {
     
    public function __construct() {
        session_start();
      /*  cotizacionModel::simple();
        cotizacionModel::propina();
        cotizacionModel::sessiooon();
        //unset($_SESSION["sucursal"]);
        //unset($_SESSION["caja"]);
        //unset($_SESSION["simple"]);
        //$_SESSION["simple"] = true; */
    }

    function sessiooon(){

         if (!isset($_SESSION["sesionid"])) {
            $_SESSION["sesionid"] = session_id();
         }
    }
    function printFiltros(){
        $queryClientes = "SELECT  id,nombre from comun_cliente";

        $result1 = $this->queryArray($queryClientes);

        $queryEmpleados = "SELECT distinct c.idempleado,u.usuario ";
        $queryEmpleados .= " FROM pvt_cotizacion c,accelog_usuarios u ";
        $queryEmpleados .= " where  c.idempleado=u.idempleado ";

        $result2 = $this->queryArray($queryEmpleados);

        return array('cliente' => $result1["rows"], 'empleado' => $result2["rows"]);


    }
    function buscar($idCliente,$idEmpleado,$desde,$hasta){
        $filtro = '';
        if($idCliente!='0'){
            $filtro .= ' and c.idCliente="'.$idCliente.'" ';
        }
        if($idEmpleado!='0'){
            $filtro .=' and c.idempleado="'.$idEmpleado.'" ';
        }
        if($desde!=''){
            $filtro .=' and fecha BETWEEN "'.$desde.' 00:01:01" AND "'.$hasta.' 23:59:59"';
        }

        $queryGrid = "SELECT c.id,cl.nombre,c.total,u.usuario,c.fecha,c.status ";
        $queryGrid .= " FROM pvt_cotizacion c, comun_cliente cl, accelog_usuarios u ";
        $queryGrid .= " WHERE c.idCliente=cl.id and c.idempleado=u.idempleado".$filtro." order by id desc";
        //echo $queryGrid;
        $result = $this->queryArray($queryGrid);

        return $result["rows"];
    }
    function listas($idProducto){
        $arreglo=array();
        // Agrega el precio de venta
        $queryCombo = "SELECT precioventa as precio, 'Precio venta' as descripcion ";
        $queryCombo .= " FROM mrp_producto ";
        $queryCombo .= " WHERE idProducto=".$idProducto;

        $resultado = $this->queryArray($queryCombo);
        $arreglo['pventa'] =   $resultado["rows"];      

        // Agrega los precios de lista
        $queryProductos = "SELECT  precio, descripcion ";
        $queryProductos .= " FROM mrp_lista_precios ";
        $queryProductos .= " WHERE idProducto=".$idProducto." order by orden ASC ";

        $result = $this->queryArray($queryProductos);
        $arreglo['lventa'] =   $result["rows"];  
        return $arreglo;
    }

    function getProducts(){
        $queryProductos = "SELECT  idProducto,nombre ";
        $queryProductos .= " FROM mrp_producto ";
        $queryProductos .= " WHERE estatus=1 and vendible=1 order by nombre ASC ";

        $result = $this->queryArray($queryProductos);

        return $result["rows"];
    }
    function getClient(){
        $queryClientes = "SELECT  id,nombre ";
        $queryClientes .= " FROM comun_cliente ";
        $queryClientes .= " order by nombre ASC ";

        $result = $this->queryArray($queryClientes);

        return $result["rows"];
    }
    function printGrid(){
        $queryGrid = "SELECT c.id,cl.nombre,c.total,u.usuario,c.fecha,c.status ";
        $queryGrid .= " FROM pvt_cotizacion c, comun_cliente cl, accelog_usuarios u ";
        $queryGrid .= " WHERE c.idCliente=cl.id and c.idempleado=u.idempleado order by id desc";

        $result = $this->queryArray($queryGrid);

        return $result["rows"];
    }
    function precio($idProducto){
        $sql = "SELECT precioventa FROM mrp_producto where idProducto = '$idProducto';";
        $result = $this->queryArray($sql);
        return $result["rows"][0]['precioventa'];
    }
    function addProduct($idProducto,$cantidad,$precio){

        //print_r($_SESSION['cotiza']);
        // unset($_SESSION['cotiza']);
        //exit(); 
        $queryProducto = "SELECT p.idProducto, p.nombre, p.deslarga, p.precioventa,p.idunidad,u.compuesto, p.imagen ";
        $queryProducto .=" FROM mrp_producto p,mrp_unidades u WHERE p.idunidad=u.idUni and p.idProducto=".$idProducto;
      
        $result = $this->queryArray($queryProducto);

        if($result["rows"][0]["deslarga"]!=''){
            $desLarga = ' - '.$result["rows"][0]["deslarga"];
        }
            /*if (!isset($_SESSION['cotiza'])) {
                session_start();
            } */
        $E=$cantidad;
        //$importe=$result["rows"][0]["precioventa"]*$cantidad;
        $importe=$precio*$cantidad;
        $imagen=$result["rows"][0]["imagen"];

        $_SESSION['cotiza'] = $this->object_to_array($_SESSION['cotiza']);
        foreach ($_SESSION['cotiza'] as $key => $value) {
            if($key==$idProducto){
                $E=$value['cantidad']+$cantidad;
                $importe=$value['precio']*$E;
            }
        }
 
        $arraySession = new stdClass();

        $arraySession->cantidad = $E;
        $arraySession->idProducto = $result["rows"][0]["idProducto"];
        $arraySession->nombre = $result["rows"][0]["nombre"].$desLarga;
        $arraySession->idunidad = $result["rows"][0]["idunidad"];
        $arraySession->unidad = $result["rows"][0]["compuesto"];
        //$arraySession->precio = $result["rows"][0]["precioventa"];
        $arraySession->precio = $precio;
        $arraySession->importe = $importe;
        $arraySession->imagen = $result["rows"][0]["imagen"];

        $_SESSION['cotiza'][$result["rows"][0]["idProducto"]]=$arraySession;

        $this->calculaImpuestos($result["rows"][0]["idProducto"],$cantidad);
       
        return array('status' => true, "rows" => $_SESSION['cotiza'],"charges" => $_SESSION["cotiza"]['taxes']);
    }

    function deletePro($idProducto){

        unset($_SESSION["cotiza"][$idProducto]);
        $this->calculaImpuestos($idProducto,'3');
        return array('status' => true, "rows" => $_SESSION['cotiza'],"charges" => $_SESSION["cotiza"]['taxes']);
    }

    function object_to_array($data) {
        if (is_array($data) || is_object($data)) {
            $result = array();
            foreach ($data as $key => $value) {
                $result[$key] = $this->object_to_array($value);
            }
            return $result;
        }
        return $data;
    }

    function save($idCliente,$observacion){
        $_SESSION['cotiza'] = $this->object_to_array($_SESSION['cotiza']);
    
        $idEmpleado=$_SESSION["accelog_idempleado"];
    
       ///////DATOS RECEPTOR
        $queryClient="SELECT c.nombre,c.direccion,c.colonia,c.email,c.cp, e.estado,m.municipio,c.rfc,c.num_ext,c.num_int ";
        $queryClient.=" from comun_cliente c, estados e,municipios m ";
        $queryClient.=" where c.idEstado=e.idestado and c.idMunicipio=m.idmunicipio and c.id=".$idCliente;
        //echo $queryClient;
        $result = $this->queryArray($queryClient);
        $Email = $result["rows"][0]["email"]; 

      ////////DATOS EMISOR
        $queryOganizacion="SELECT o.nombreorganizacion,o.RFC,r.descripcion as regimen,o.domicilio,e.estado,m.municipio,o.cp,o.colonia,o.paginaweb,o.logoempresa ";
        $queryOganizacion.=" from organizaciones o, estados e,municipios m, nomi_regimenfiscal r ";
        $queryOganizacion.=" where o.idestado=e.idestado and o.idmunicipio=m.idmunicipio and o.idregfiscal = r.idregfiscal";
        $result2 = $this->queryArray($queryOganizacion);
            

        $fechaactual = date('Y-m-d H:i:s');
        $insertCotizacion = "INSERT INTO pvt_cotizacion (idCliente,total,idempleado,fecha,observaciones) values ('".$idCliente."','".$_SESSION["cotiza"]["charges"]["Tot"]."','".$idEmpleado."','".$fechaactual."','".$observacion."')";
        $resultInsert = $this->queryArray($insertCotizacion);
        $insertedCotId = $resultInsert["insertId"];
 
        foreach ($_SESSION['cotiza'] as $key => $value) {
            if($key!='charges'){
                $insertProductoCot = "INSERT INTO pvt_cotizacion_producto (idCotizacion,idProducto,cantidad,idunidad,precio,importe) values ('".$insertedCotId."','".$value['idProducto']."','".$value['cantidad']."','".$value['idunidad']."','".$value['precio']."','".$value['importe']."')";
                $resultInsert = $this->query($insertProductoCot);
            }
        }
////////////////PDF

        include "../../modulos/SAT/PDF/COTIZACIONESPDF.php";
        $obj = new CFDIPDF( );
        $nrec = $result["rows"][0]["num_ext"].' Int.'.$result["rows"][0]["num_int"];
            $obj->datosCFD($insertedCotId, $fechaactual, 'Remision','MXP');
            $obj->lugarE('MEXICO');

            $obj->datosEmisor($result2["rows"][0]["nombreorganizacion"], $result2["rows"][0]["RFC"], $result2["rows"][0]["domicilio"], $result2["rows"][0]["municipio"], $result2["rows"][0]["colonia"], $result2["rows"][0]["municipio"], $result2["rows"][0]["estado"], $result2["rows"][0]["cp"], "Mexico", '');
           
            $obj->datosReceptor($result["rows"][0]["nombre"], $result["rows"][0]["rfc"], $result["rows"][0]["direccion"] . $nrec, $result["rows"][0]["municipio"], $result["rows"][0]["colonia"], $result["rows"][0]["municipio"], $result["rows"][0]["estado"], $result["rows"][0]["cp"], 'Mexico');

            $obj->agregarConceptos($_SESSION['cotiza']);
            $obj->agregarTotal($_SESSION["cotiza"]["charges"]["sbtot"], $_SESSION["cotiza"]["charges"]["Tot"], $_SESSION["cotiza"]["charges"]);
            $obj->agregarMetodo('eeeeeeeeee', '', '');
            //$obj->agregarSellos($datosTimbrado['csdComplemento'], $datosTimbrado['selloCFD'], $datosTimbrado['selloSAT']);
            $obj->agregarObservaciones($observacion);
            $obj->generar("../../netwarelog/archivos/1/organizaciones/" . $result2["rows"][0]["logoempresa"] . "", 0);
            $obj->borrarConcepto();        

            

                if ($Email != '') {
                  
                    require_once('../../modulos/phpmailer/sendMail.php');

                    $mail->From = "mailer@netwarmonitor.com";
                    $mail->FromName = "NetwarMonitor";
                    $mail->Subject = "Cotizacion";
                    $mail->AltBody = "NetwarMonitor";
                    $mail->MsgHTML('Estimado Cliente, envio la cotizacion, Saludos.');
                    $mail->AddAttachment('../../modulos/cotizaciones/cotizacionesPdf/cotizacion_'.$insertedCotId.".pdf");
                    $mail->AddAddress($Email, $Email);


                 @$mail->Send();

                        unset($_SESSION['cotiza']);
                        return array('status' => true);
                }else{
                    //echo 'entro al else';
                        unset($_SESSION['cotiza']);
                        return array('status' => false);
                }
    }
    function calculaImpuestos($idProducto,$cantidad){
        
        $_SESSION["cotiza"]["charges"]["taxes"]['IVA'] = 0.0;
        $_SESSION["cotiza"]["charges"]["taxes"]['IEPS'] = 0.0;
        $_SESSION["cotiza"]["charges"]["taxes"]['test'] = 0.0;
        $_SESSION["cotiza"]["charges"]["taxes"]['ISH'] = 0.0;
        $_SESSION["cotiza"]["charges"]["taxes"]['ISR'] = 0.0;
        $_SESSION['cotiza'] = $this->object_to_array($_SESSION['cotiza']);
            $ieps=0;
            
            foreach ($_SESSION['cotiza'] as $key => $value) {
                if($key!='charges'){
                    $queryImpuestos = "select p.idProducto,p.precioventa, pi.valor, i.nombre";
                    $queryImpuestos .= " from impuesto i, mrp_producto p ";
                    $queryImpuestos .= " left join producto_impuesto pi on p.idProducto=pi.idProducto ";
                    $queryImpuestos .= " where p.idProducto=".$value['idProducto']." and i.id=pi.idImpuesto ";
                    $queryImpuestos .= " Order by pi.idImpuesto asc ";

                    $result = $this->queryArray($queryImpuestos);

                    if($precio == 0 ) {
                        $subtotal=$value['precio']*$value['cantidad'];
                    } else {
                        $subtotal=$precio*$value['cantidad'];
                    }
                    

                    foreach ($result['rows'] as $key2 => $value2) {
                        if($value2['nombre']=='IEPS'){
                             $producto_impuesto += $ieps = (($subtotal) * $value2["valor"] / 100);

                        }else{
                            if($ieps != 0){
                                $producto_impuesto += ((($subtotal + $ieps)) * $value2["valor"] / 100);
                            }else{
                                $producto_impuesto += (($subtotal) * $value2["valor"] / 100);
                            }
                        }
                        $_SESSION["cotiza"]["charges"]["taxes"][$value2["nombre"]]=$producto_impuesto;
                    }
                }
            }

            foreach ($_SESSION["cotiza"] as $key => $value) {
                if($key!='charges'){
                    $subtot+=$value['importe'];
                }   
            }
            foreach ($_SESSION["cotiza"]["charges"]["taxes"] as $key => $value) {
                $taxesTot +=$value; 
                    
            }

            $_SESSION["cotiza"]["charges"]["sbtot"] = $subtot; 
            $_SESSION["cotiza"]["charges"]["taxesTot"] = $taxesTot;
            $_SESSION["cotiza"]["charges"]["Tot"] = $subtot + $taxesTot;

    } 

        /*    $queryImpuestos = "select p.idProducto,p.precioventa, pi.valor, i.nombre";
            $queryImpuestos .= " from impuesto i, mrp_producto p ";
            $queryImpuestos .= " left join producto_impuesto pi on p.idProducto=pi.idProducto ";
            $queryImpuestos .= " where p.idProducto=" . $idProducto . " and i.id=pi.idImpuesto ";
            $queryImpuestos .= " Order by pi.idImpuesto DESC ";

            $result = $this->queryArray($queryImpuestos);

            foreach ($result['rows'] as $key => $value) {
                if($value['nombre']=='IEPS'){
                    $producto_impuesto = $ieps = (($value['precioventa']*$cantidad) * $value["valor"] / 100);
                    $_SESSION['cotiza']['impues'][$value['nombre']]=$producto_impuesto;
                }else{
                    $producto_impuesto = (($value['precioventa']*$cantidad) * $value["valor"] / 100);
                    $_SESSION['cotiza']['impues'][$value['nombre']]=$producto_impuesto;
                }
            } */
    function resubmit($id){

        $queryClient="SELECT idCliente from pvt_cotizacion where id=".$id;
        $result = $this->queryArray($queryClient);
        $idCliente = $result["rows"][0]["idCliente"]; 

        $queryEmail="SELECT * from comun_cliente where id=".$idCliente;
        $result = $this->queryArray($queryEmail);
        $Email = $result["rows"][0]["email"]; 

                if ($Email != '' || $Email!='0') {
                  
                    require_once('../../modulos/phpmailer/sendMail.php');

                    $mail->From = "mailer@netwarmonitor.com";
                    $mail->FromName = "NetwareMonitor";
                    $mail->Subject = "Cotizacion";
                    $mail->AltBody = "NetwarMonitor";
                    $mail->MsgHTML('Estimado Cliente, envio la cotizacion pedida, Saludos.');
                    $mail->AddAttachment('../../modulos/cotizaciones/cotizacionesPdf/cotizacion_'.$id.".pdf");
                    $mail->AddAddress($Email, $Email);


                 @$mail->Send();

                        unset($_SESSION['cotiza']);
                        return array('status' => true);
                }else{
                        unset($_SESSION['cotiza']);
                        return array('status' => false);
                }

    }
   function createPedido($idCotizacion){
        $queryCotizacion="SELECT * from pvt_cotizacion where id=".$idCotizacion;
        $result = $this->queryArray($queryCotizacion);
        $idCliente = $result["rows"][0]["idCliente"];
        $idEmpleado=$_SESSION["accelog_idempleado"];

        $fechaactual = date('Y-m-d H:i:s');
        $insertPedido = "INSERT INTO cotpe_pedido (idCliente,total,idempleado,fecha,idCotizacion,observaciones) values ('".$result["rows"][0]["idCliente"]."','".$result["rows"][0]["total"]."','".$idEmpleado."','".$fechaactual."','".$idCotizacion."','-')";
       
        $resultInsert = $this->queryArray($insertPedido);
        $insertePedId = $resultInsert["insertId"];

        $queryProduCoti = "SELECT * from pvt_cotizacion_producto where idCotizacion=".$idCotizacion;
        $resultProductos = $this->queryArray($queryProduCoti);
        
       foreach ($resultProductos['rows'] as $key => $value) {
                $this->addProduct($value['idProducto'],$value['cantidad'],$value['precio']);
                $insertProductoPedido = "INSERT INTO cotpe_pedido_producto (idPedido,idProducto,cantidad,idunidad,precio,importe) values ('".$insertePedId."','".$value['idProducto']."','".$value['cantidad']."','".$value['idunidad']."','".$value['precio']."','".$value['importe']."')";
                $resultInsert = $this->query($insertProductoPedido);          
        } 


       ///////DATOS RECEPTOR
        $queryClient="SELECT c.nombre,c.direccion,c.colonia,c.email,c.cp, e.estado,m.municipio,c.rfc,c.num_ext,c.num_int ";
        $queryClient.=" from comun_cliente c, estados e,municipios m ";
        $queryClient.=" where c.idEstado=e.idestado and c.idMunicipio=m.idmunicipio and c.id=".$idCliente;

        $result = $this->queryArray($queryClient);
        $Email = $result["rows"][0]["email"]; 
      ////////DATOS EMISOR
        $queryOganizacion="SELECT o.nombreorganizacion,o.RFC,r.descripcion as regimen,o.domicilio,e.estado,m.municipio,o.cp,o.colonia,o.paginaweb,o.logoempresa ";
        $queryOganizacion.=" from organizaciones o, estados e,municipios m, nomi_regimenfiscal r ";
        $queryOganizacion.=" where o.idestado=e.idestado and o.idmunicipio=m.idmunicipio and o.idregfiscal = r.idregfiscal";
        $result2 = $this->queryArray($queryOganizacion);
            

        //print_r($_SESSION['cotiza']);


       include "../../modulos/SAT/PDF/COTIZACIONESPDF.php";
        $obj = new CFDIPDF( );
        $nrec = $result["rows"][0]["num_ext"].' Int.'.$result["rows"][0]["num_int"];
            $obj->datosCFD($insertePedId, $fechaactual, 'Pedido');
            $obj->lugarE('MEXICO');

            $obj->datosEmisor($result2["rows"][0]["nombreorganizacion"], $result2["rows"][0]["RFC"], $result2["rows"][0]["domicilio"], $result2["rows"][0]["estado"], $result2["rows"][0]["colonia"], $result2["rows"][0]["municipio"], $result2["rows"][0]["estado"], $result2["rows"][0]["cp"], "Mexico", '');
           
            $obj->datosReceptor($result["rows"][0]["nombre"], $result["rows"][0]["rfc"], $result["rows"][0]["direccion"] . $nrec, $result["rows"][0]["municipio"], $result["rows"][0]["colonia"], $result["rows"][0]["municipio"], $result["rows"][0]["estado"], $result["rows"][0]["cp"], 'Mexico');

            $obj->agregarConceptos($_SESSION['cotiza']);
            $obj->agregarTotal($_SESSION["cotiza"]["charges"]["sbtot"], $_SESSION["cotiza"]["charges"]["Tot"], $_SESSION["cotiza"]["charges"]);
            $obj->agregarMetodo('eeeeeeeeee', '', '1000000');
            //$obj->agregarSellos($datosTimbrado['csdComplemento'], $datosTimbrado['selloCFD'], $datosTimbrado['selloSAT']);
            $obj->agregarObservaciones($observacion);
            $obj->generar("../../netwarelog/archivos/1/organizaciones/" . $result2["rows"][0]["logoempresa"] . "", 0);
            $obj->borrarConcepto();        


           /*     if ($Email != '') {
                  
                    require_once('../../modulos/phpmailer/sendMail.php');

                    $mail->From = "mailer@netwarmonitor.com";
                    $mail->FromName = "NetwareMonitor";
                    $mail->Subject = "Cotizacion";
                    $mail->AltBody = "NetwarMonitor";
                    $mail->MsgHTML('Estimado Cliente, envio la cotizacion pedida, Saludos.');
                    $mail->AddAttachment('../../modulos/cotizaciones/cotizacionesPdf/cotizacion_'.$insertePedId.".pdf");
                    $mail->AddAddress($Email, $Email);


                 @$mail->Send();

                        unset($_SESSION['cotiza']);
                        return array('status' => true);
                }else{
                        unset($_SESSION['cotiza']);
                        return array('status' => false);
                } */

        $queryUpdate = "UPDATE pvt_cotizacion set status=2 where id=".$idCotizacion;
        $this->queryArray($queryUpdate);

        return array('status' => true);



    }        

    function eliminaCoti($idcoti){
        $deleteQuery = "DELETE from pvt_cotizacion_producto where idCotizacion=".$idcoti; 
        $deleteRes = $this->queryArray($deleteQuery);

        $deleteQuery1 = "DELETE from pvt_cotizacion where id=".$idcoti;
        $deleteRes1 = $this->queryArray($deleteQuery1);

        $selQuery = "SELECT * from pvt_cotizacion where id=".$idcoti;
        $selRes = $this->queryArray($selQuery);

        if($selRes['total'] > 0){
            return  array('status' => false );
        }else{
            return  array('status' => true );
        }


    }

    function agregaListas($idProducto){
        $arreglo=array();
        $queryCombo = "SELECT idProducto, 'Precio venta', precioventa ";
        $queryCombo .= " FROM mrp_producto ";
        $queryCombo .= " WHERE idProducto='.$idProducto.' ";

        $resultado = $this->queryArray($queryCombo);

        $arreglo['pventa'] =   $result["rows"];      

        $queryGrid = "SELECT idProducto, descripcion, precio ";
        $queryGrid .= " FROM mrp_lista_precios ";
        $queryGrid .= " WHERE idProducto='.$idProducto.' order by Orden";

        $result = $resultado + $this->queryArray($queryGrid);
        $arreglo['lstaventa'] =   $result["rows"];
        return $arreglo;
    }
}

?>