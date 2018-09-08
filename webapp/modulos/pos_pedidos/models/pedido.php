<?php
//ini_set('display_errors', 1);
require("models/connection_sqli_manual.php"); // funciones mySQLi

class pedidoModel extends Connection {
     
   /*public function __construct() {
        session_start();
      /*  cotizacionModel::simple();
        cotizacionModel::propina();
        cotizacionModel::sessiooon();
        //unset($_SESSION["sucursal"]);
        //unset($_SESSION["caja"]);
        //unset($_SESSION["simple"]);
        //$_SESSION["simple"] = true; */
    
    function getGridP(){
       
        $queryPedido = "SELECT p.id, c.nombre,p.total,u.usuario,p.fecha,p.idCotizacion,p.observaciones,p.status ";
        $queryPedido.=" from cotpe_pedido p,comun_cliente c,accelog_usuarios u ";
        $queryPedido.=" where p.idCliente=c.id and p.idempleado=u.idempleado"; 
        $result = $this->queryArray($queryPedido);

        return array('pedidos' => $result["rows"] , 'perfil' => $_SESSION['accelog_idperfil']);
        //return ;
    }
    function buscaP($idEmpleado,$idCliente,$desde,$hasta){
    $inicio = $desde;
    $fin = $hasta;
    //$filtro=1;
    $filtro = '';

    if($fin!="")
    {
        list($a,$m,$d)=explode("-",$fin);
        $fin=$a."-".$m."-".((int)$d+1);
    }


    if($inicio!="" && $fin=="")
    {
        $filtro.=" and p.fecha >= '".$inicio."' ";
    }
    if($fin!="" && $inicio=="")
    {
        $filtro.=" and p.fecha <= '".$fin."' ";
    }
    if($inicio!="" && $fin!="")
    {
        $filtro.=" and p.fecha <= '".$fin."' and   p.fecha >= '".$inicio."' ";
    }
        //$filtro = '';

        if($idEmpleado!='0'){
            $filtro .= ' and p.idempleado="'.$idEmpleado.'" ';
        }
        if($idCliente!='0'){
            $filtro .= ' and p.idCliente="'.$idCliente.'" ';
        }
        /*if($desde!=''){
            $filtro .= ' and p.fecha BETWEEN "'.$desde.'" AND "'.$hasta.'" '; 
        } */

        $queryPedido = "SELECT p.id, c.nombre,p.total,u.usuario,p.fecha,p.idCotizacion,p.observaciones,p.status ";
        $queryPedido.=" from cotpe_pedido p,comun_cliente c,accelog_usuarios u ";
        $queryPedido.=" where p.idCliente=c.id and p.idempleado=u.idempleado".$filtro; 
  
        $result = $this->queryArray($queryPedido);
        //print_r($result["rows"]);
        return array('pedidos' => $result["rows"] , 'perfil' => $_SESSION['accelog_idperfil']);
        //return $result["rows"];
    }
    function buscaP2($idCliente,$desde,$hasta){
        $filtro = '';
        $inicio = $desde;
        $fin = $hasta;
        //$filtro=1;
        //echo 'inicioi='.$inicio.' hasta='.$fin;
        if($fin!="")
        {
            list($a,$m,$d)=explode("-",$fin);
            $fin=$a."-".$m."-".((int)$d+1);
        }


        if($inicio!="" && $fin=="")
        {
            $filtro.=" and p.fecha >= '".$inicio."' ";   
        }
        if($fin!="" && $inicio=="")
        {
            $filtro.=" and p.fecha <= '".$fin."' ";
        }
        if($inicio!="" && $fin!="")
        {
            $filtro.=" and p.fecha <= '".$fin."' and   p.fecha >= '".$inicio."' "; 
        }

        $queryPedido = "SELECT p.id, p.total,p.fecha,p.idCotizacion,p.observaciones,p.status ";
        $queryPedido.=" from cotpe_pedido p";
        $queryPedido.=" where p.idCliente='".$idCliente."' ".$filtro; 
        //echo $queryPedido;
        $result = $this->queryArray($queryPedido);


        $query2 = "SELECT nombre from comun_cliente where id=".$idCliente;
        $res2 = $this->queryArray($query2);

        return array('pedidos' => $result["rows"] );
        //return $result["rows"];
    }
    function printFiltrosP(){
        $queryClientes = "SELECT  id,nombre from comun_cliente";
        //$queryClientes .= " FROM cotpe_pedido c,comun_cliente u ";
        //$queryClientes .= " WHERE  c.idCliente=u.id ";
        
        $result1 = $this->queryArray($queryClientes);

        $queryEmpleados = "SELECT distinct c.idempleado,u.usuario ";
        $queryEmpleados .= " FROM cotpe_pedido c,accelog_usuarios u ";
        $queryEmpleados .= " where  c.idempleado=u.idempleado ";
       
        $result2 = $this->queryArray($queryEmpleados);

        return array('cliente' => $result1["rows"], 'empleado' => $result2["rows"]);

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
    public function buscaClienteP($empleado){
        $query = "SELECT id from administracion_usuarios where idempleado=".$empleado;
        $res = $this->queryArray($query);

        $query2 = "SELECT nombre from comun_cliente where id=".$res['rows'][0]['id'];
        $res2 = $this->queryArray($query2);
 
        return array('idCliente' => $res['rows'][0]['id'],'nombre' => $res2['rows'][0]['nombre'] );
    }
    public function pedidosGridCliente($cliente){
        $ped = "SELECT * from cotpe_pedido where idCliente=".$cliente;
        $resPed = $this->queryArray($ped);

        return $resPed['rows'];
    }
    function pedidoView($idPedido){
        //echo $idPedido;
        unset($_SESSION['pedido']);
  
        if($idPedido!=''){

            $select = "SELECT idCliente from cotpe_pedido where id=".$idPedido;
            $res = $this->queryArray($select);
 
            $queryClientes = "SELECT * from comun_cliente where id=".$res['rows'][0]['idCliente'];


           /* $queryClientes = "SELECT  id,nombre from comun_cliente";
            $queryClientes .= " FROM cotpe_pedido c,comun_cliente u ";
            $queryClientes .= " WHERE  c.idCliente=u.id and c.id=".$idPedido; */
        }else{
            $queryClientes = "SELECT * from comun_cliente";
        }
        //echo $queryClientes;
        
        $result1 = $this->queryArray($queryClientes);

        $queryProductos = "SELECT  id as idProducto,nombre ";
        $queryProductos .= " FROM app_productos ";
        $queryProductos .= " WHERE status=1 order by nombre ASC ";

        $result2 = $this->queryArray($queryProductos);

        $queryPedido = "SELECT * from cotpe_pedido where id=".$idPedido;
        $result3=$this->queryArray($queryPedido); 

        $queryPedidoProductos ="SELECT * from cotpe_pedido_producto where idPedido=".$idPedido;
        $result4 = $this->queryArray($queryPedidoProductos);

        //print_r($result4);
        foreach ($result4['rows'] as $key => $value) {

                $queryProducto = "SELECT * from app_productos where id=".$value['idProducto'];
                $result5=$this->queryArray($queryProducto); 
                $nombreProd = $result5["rows"][0]["nombre"];

                $queryUni = "SELECT * from app_unidades_medida where id=".$value['idunidad'];
                $result6=$this->queryArray($queryUni); 
                $nomUnidad = $result6["rows"][0]["nombre"];
           
                if($value['caracteristicas']!='0'){
                    $idProducto = $value['idProducto'].'_'.$value['caracteristicas'];
                }else{
                    $idProducto = $value['idProducto'];
                }
                

                $arraySession = new stdClass();

                $arraySession->cantidad = $value['cantidad'];
                $arraySession->idProducto = $idProducto;
                $arraySession->nombre =  $nombreProd;
                $arraySession->idunidad = $value['id'];
                $arraySession->unidad = $nomUnidad;
                $arraySession->precio = $value['precio'];
                $arraySession->importe = $value['importe']; 
                $arraySession->caracteristicas = $value['caracteristicas']; 

            $_SESSION['pedido'][$idProducto]=$arraySession; 

            if($value['caracteristicas']!='0'){
                $caracteristicas2 =  explode("*", $value['caracteristicas']);
                foreach ($caracteristicas2 as $key2 => $value2) {
                    $expv=explode('=>', $value2);
                    $ip=$expv[0];
                    $ih=$expv[1];
                    $my = "SELECT concat('( ',a.nombre,': ',b.nombre,' )') as dcar FROM app_caracteristicas_padre a
                    LEFT JOIN app_caracteristicas_hija b on b.id=".$ih."
                    WHERE a.id=".$ip.";";
                    $producto = $this->queryArray($my);
                    $caras.= $producto['rows'][0]['dcar'];
                }

                $_SESSION['pedido'][$idProducto]->nombre = $_SESSION['pedido'][$idProducto]->nombre.' '.$caras;
                $caras = '';
            } 
        }
        $_SESSION['pedido'] = $this->object_to_array($_SESSION['pedido']);
        $this->calculaImpuestos(123,12);
        //print_r($_SESSION['pedido']);
        //exit();

        return array('cliente' => $result1["rows"], 'productos' => $result2["rows"], 'pedidoInfo' => $result3['rows']);

    }
    function addProductP($idProducto,$cantidad){

       //print_r($_SESSION['cotiza']);
      // unset($_SESSION['cotiza']);
       //exit(); 
        $queryProducto = "SELECT p.idProducto, p.nombre, p.precioventa,p.idunidad,u.compuesto ";
        $queryProducto .=" FROM mrp_producto p,mrp_unidades u WHERE p.idunidad=u.idUni and p.idProducto=".$idProducto;
      
        $result = $this->queryArray($queryProducto);

            /*if (!isset($_SESSION['cotiza'])) {
                session_start();
            } */
        $E=$cantidad;
        $importe=$result["rows"][0]["precioventa"]*$cantidad;
        $_SESSION['pedido'] = $this->object_to_array($_SESSION['pedido']);
        foreach ($_SESSION['pedido'] as $key => $value) {
            if($key==$idProducto){
                $E=$value['cantidad']+$cantidad;
                $importe=$value['precio']*$E;
            }

        }
 
       // echo $E;
        $arraySession = new stdClass();

        $arraySession->cantidad = $E;
        $arraySession->idProducto = $result["rows"][0]["idProducto"];
        $arraySession->nombre = $result["rows"][0]["nombre"];
        $arraySession->idunidad = $result["rows"][0]["idunidad"];
        $arraySession->unidad = $result["rows"][0]["compuesto"];
        $arraySession->precio = $result["rows"][0]["precioventa"];
        $arraySession->importe = $importe;

        $_SESSION['pedido'][$result["rows"][0]["idProducto"]]=$arraySession;

        $this->calculaImpuestos($result["rows"][0]["idProducto"],$cantidad);
       

        return array('status' => true, 'rows' => $_SESSION['pedido'],'charges' => $_SESSION['pedido']['taxes']);



    }

    function calculaImpuestos($idProducto,$cantidad){
        
        
        $_SESSION["pedido"]["charges"]["taxes"]['IVA'] = 0.0;
        $_SESSION["pedido"]["charges"]["taxes"]['IEPS'] = 0.0;
        $_SESSION["pedido"]["charges"]["taxes"]['test'] = 0.0;
        $_SESSION["pedido"]["charges"]["taxes"]['ISH'] = 0.0;
        $_SESSION["pedido"]["charges"]["taxes"]['ISR'] = 0.0;
        $_SESSION['pedido'] = $this->object_to_array($_SESSION['pedido']);
            $ieps=0;
            
            foreach ($_SESSION['pedido'] as $key => $value) {
                if($key!='charges'){
                    $queryImpuestos = "select p.idProducto,p.precioventa, pi.valor, i.nombre";
                    $queryImpuestos .= " from impuesto i, mrp_producto p ";
                    $queryImpuestos .= " left join producto_impuesto pi on p.idProducto=pi.idProducto ";
                    $queryImpuestos .= " where p.idProducto=".$value['idProducto']." and i.id=pi.idImpuesto ";
                    $queryImpuestos .= " Order by pi.idImpuesto asc ";

                    $result = $this->queryArray($queryImpuestos);

                    $subtotal=$value['precio']*$value['cantidad'];

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
                        $_SESSION["pedido"]["charges"]["taxes"][$value2["nombre"]]=$producto_impuesto;
                    }
                }
            }

            foreach ($_SESSION["pedido"] as $key => $value) {
                if($key!='charges'){
                    $subtot+=$value['importe'];
                }   
            }
            foreach ($_SESSION["pedido"]["charges"]["taxes"] as $key => $value) {
                $taxesTot +=$value; 
                    
            }

            $_SESSION["pedido"]["charges"]["sbtot"] = $subtot; 
            $_SESSION["pedido"]["charges"]["taxesTot"] = $taxesTot;
            $_SESSION["pedido"]["charges"]["Tot"] = $subtot + $taxesTot;

    }

    function saveP($idCliente,$observacion,$idPedido){
      //date_default_timezone_set('America/Los_Angeles');
        $_SESSION['cotiza'] = $this->object_to_array($_SESSION['cotiza']);

        $idEmpleado=$_SESSION["accelog_idempleado"];
        
        if($idPedido!=0){

            $fechaactual = date('Y-m-d H:i:s');
            $updatePedido = "UPDATE cotpe_pedido SET idCliente=".$idCliente.", total=".$_SESSION["pedido"]["charges"]["Tot"].", observaciones='".$observacion."' where id=".$idPedido;
            $resultUpdate = $this->queryArray($updatePedido);

            foreach ($_SESSION['pedido'] as $key => $value) {
                if($key!='charges'){

                    $selectPePro ="SELECT * from cotpe_pedido_producto where idPedido=".$idPedido." AND idProducto=".$value['idProducto'];
                    $result = $this->queryArray($selectPePro);
                    
                    if($result['total']>0){
                        $updatePedido = "UPDATE cotpe_pedido_producto SET cantidad=".$value['cantidad'].", precio=".$value['precio'].", importe=".$value['importe']." where idPedido=".$idPedido." and idProducto=".$value['idProducto'];
                        $resultUpdate = $this->queryArray($updatePedido);

                    }else{
                        $insertProductoPed = "INSERT INTO cotpe_pedido_producto (idPedido,idProducto,cantidad,idunidad,precio,importe) values ('".$idPedido."','".$value['idProducto']."','".$value['cantidad']."','".$value['idunidad']."','".$value['precio']."','".$value['importe']."')";
                        $resultInsert = $this->query($insertProductoPed);
                    }
                    $result='';
                }
            } ///fin del foreach

           // exit();
            $insertedPedId = $idPedido;

        }else{

                $fechaactual = date('Y-m-d H:i:s');
                $insertPedido = "INSERT INTO cotpe_pedido (idCliente,total,idempleado,fecha,observaciones) values ('".$idCliente."','".$_SESSION["pedido"]["charges"]["Tot"]."','".$idEmpleado."','".$fechaactual."','".$observacion."')";
                $resultInsert = $this->queryArray($insertPedido);
                $insertedPedId = $resultInsert["insertId"];
         
                foreach ($_SESSION['pedido'] as $key => $value) {
                    if($key!='charges'){
                        $insertProductoCot = "INSERT INTO cotpe_pedido_producto (idPedido,idProducto,cantidad,idunidad,precio,importe) values ('".$insertedPedId."','".$value['idProducto']."','".$value['cantidad']."','".$value['idunidad']."','".$value['precio']."','".$value['importe']."')";
                        $resultInsert = $this->query($insertProductoCot);
                    }
                }
        }
        //echo 'ee'.$insertedPedId;
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
           // print_r($_SESSION["pedido"]["charges"]);
        //echo '('.$Email.')';
       // var_dump($result2);
       unlink('../../modulos/cotizaciones/cotizacionesPdf/pedido_'.$insertedPedId.'.php');
        ////////////////PDF

        include "../../modulos/SAT/PDF/COTIZACIONESPDF.php";
        $obj = new CFDIPDF( );
        $nrec = $result["rows"][0]["num_ext"].' Int.'.$result["rows"][0]["num_int"];
            $obj->datosCFD($insertedPedId, $fechaactual, 'Pedido');
            $obj->lugarE('MEXICO');

            $obj->datosEmisor($result2["rows"][0]["nombreorganizacion"], $result2["rows"][0]["RFC"], $result2["rows"][0]["domicilio"], $result2["rows"][0]["estado"], $result2["rows"][0]["colonia"], $result2["rows"][0]["municipio"], $result2["rows"][0]["estado"], $result2["rows"][0]["cp"], "Mexico", '');
           
            $obj->datosReceptor($result["rows"][0]["nombre"], $result["rows"][0]["rfc"], $result["rows"][0]["direccion"] . $nrec, $result["rows"][0]["municipio"], $result["rows"][0]["colonia"], $result["rows"][0]["municipio"], $result["rows"][0]["estado"], $result["rows"][0]["cp"], 'Mexico');

            $obj->agregarConceptos($_SESSION['pedido']);
            $obj->agregarTotal($_SESSION["pedido"]["charges"]["sbtot"], $_SESSION["pedido"]["charges"]["Tot"], $_SESSION["pedido"]["charges"]);
            $obj->agregarMetodo('eeeeeeeeee', '', '');
            //$obj->agregarSellos($datosTimbrado['csdComplemento'], $datosTimbrado['selloCFD'], $datosTimbrado['selloSAT']);
            $obj->agregarObservaciones($observacion);
            $obj->generar("../../netwarelog/archivos/1/organizaciones/" . $result2["rows"][0]["logoempresa"] . "", 0);
            $obj->borrarConcepto();        
            //exit();

                if ($Email != '') {
                  
                    require_once('../../modulos/phpmailer/sendMail.php');

                    $mail->From = "mailer@netwarmonitor.com";
                    $mail->FromName = "NetwarMonitor";
                    $mail->Subject = "Pedido";
                    $mail->AltBody = "NetwarMonitor";
                    $mail->MsgHTML('Envio de Pedido');
                    $mail->AddAttachment('../../modulos/cotizaciones/cotizacionesPdf/pedido_'.$insertedCotId.".pdf");
                    $mail->AddAddress($Email, $Email);


                 @$mail->Send();

                        unset($_SESSION['pedido']);
                        return array('status' => true);
                }else{
                        unset($_SESSION['pedido']);
                        return array('status' => false);
                } 
        unset($_SESSION['pedido']);
        return array('status' => true);
    } 

    function deleteProP($idProducto,$idPedido){

        unset($_SESSION["pedido"][$idProducto]);
        $deleteProductoPed = "DELETE from cotpe_pedido_producto where idProducto=".$idProducto." and idPedido=".$idPedido;
        $resultInsert = $this->query($deleteProductoPed);
        $this->calculaImpuestos($idProducto,'3');
        return array('status' => true, "rows" => $_SESSION['pedido'],"charges" => $_SESSION["pedido"]['taxes']);
    }
    function sendCajaPedido($idPedido){


        $selVe = "SELECT idVenta from cotpe_pedido where id=".$idPedido;
        $res = $this->queryArray($selVe);
     
        if($res['rows'][0]['idVenta']!=''){
            
            return array('venta' => true);
        }

        $selectProductos = "SELECT idProducto, cantidad from cotpe_pedido_producto where idPedido=".$idPedido;
        $resultProductos= $this->queryArray($selectProductos);
        //var_dump($resultProductos);
        $codigos = array();
        //$cantidad = 
        foreach ($resultProductos['rows'] as $key => $value) {
            //echo 'X'.$value['idProducto'].'-'.$value['cantidad'].'X';
            $selectCodigo = "SELECT codigo from app_productos where id=".$value['idProducto'];
            $resultCodigo= $this->queryArray($selectCodigo);
            $codigos[$key]=array('codigo' => $resultCodigo['rows'][0]['codigo'], 'cantidad' => $value['cantidad']);

        }
        $updatePedido = "UPDATE cotpe_pedido set status=4 where id=".$idPedido;
        $this->query($updatePedido);
        return array('codigo' => $codigos);
        //exit();
    }
    function aProceso($idPedido){
        $updatePedido = "UPDATE cotpe_pedido set status=2 where id=".$idPedido;
        $this->query($updatePedido);

        return array('estatus' => true);
    }
    function aTerminado($idPedido){
            $updatePedido = "UPDATE cotpe_pedido set status=3 where id=".$idPedido;
            $this->query($updatePedido);
            
            return array('estatus' => true);
    }
    function cancelar($idPedido){
        $updatePedido = "UPDATE cotpe_pedido set status=0
         where id=".$idPedido;
            $this->query($updatePedido);
            
            return array('estatus' => true);
    }

}










?>