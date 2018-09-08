<?php
//Carga la clase de coneccion con sus metodos para consultas o transacciones
//require("models/connection.php"); // funciones mySQL
require("models/connection_sqli_manual.php"); // funciones mySQLi

class ClienteModel extends Connection
{
    public function indexGrid()
    {
        $myQuery = "SELECT * from comun_cliente";
        $resultados = $this->queryArray($myQuery);
        return array('clientes' =>$resultados['rows'] ,'total' => $resultados['total'] );
    }
    public function razonSocialCliente($idcliente)
    {
        $query = 'select razon_social from comun_facturacion where nombre = ' . $idcliente;
        $result = $this->queryArray($query);
        return $result['rows'][0];
    }
    public function paises(){
        $query = 'SELECT * from paises where idpais IN (1,47,54,85);';
        $result = $this->queryArray($query);
        return $result['rows'];
    }
    public function estados2($idPais){
        $query = 'Select * from estados where idpais = '.$idPais;
        $result = $this->queryArray($query);
        return $result['rows'];
    }
    public function estados(){
        $query = 'Select * from estados';
        $result = $this->queryArray($query);
        return $result['rows'];
    }
    public function municipios($idEstado){
        $queryM = "SELECT * from municipios where idestado=".$idEstado;
        $result = $this->queryArray($queryM);
        return $result['rows'];
    }
    public function munici(){
        $queryM = "SELECT * from municipios";
        $result = $this->queryArray($queryM);
        return $result['rows'];
    }
    public function listaPrecios(){
        $query = 'SELECT * from app_lista_precio where activo=1';
        $result = $this->queryArray($query);

        return $result['rows'];
    }
    public function cuentas(){
        $query = ' SELECT account_id, manual_code, description FROM cont_accounts where main_account = 3 AND removed=0 AND  currency_id = 1 AND main_father = (SELECT CuentaClientes FROM cont_config)';
        $resCu = $this->queryArray($query);

        return $resCu['rows'];
    }
    public function datosCliente($idCliente){
        $query = 'SELECT * from comun_cliente where id='.$idCliente;
        $result = $this->queryArray($query);

        $idTmp = $result['rows'][0]['idPais'];
        $sql = "SELECT  pais
                FROM    paises
                WHERE   idpais = $idTmp";
        $res = $this->queryArray($sql);
        $result['rows'][0]['descPais'] = $res['rows'][0]['pais'];

        $idTmp = $result['rows'][0]['idEstado'];
        $sql = "SELECT  estado
                FROM    estados
                WHERE   idestado = $idTmp";
        $res = $this->queryArray($sql);
        $result['rows'][0]['descEstado'] = $res['rows'][0]['estado'];

        $idTmp = $result['rows'][0]['idMunicipio'];
        $sql = "SELECT  municipio
                FROM    municipios
                WHERE   idmunicipio = $idTmp";
        $res = $this->queryArray($sql);
        $result['rows'][0]['descMunicipio'] = $res['rows'][0]['municipio'];

        return array("basicos" => $result['rows']);
    }
    public function obtenEmple(){
        $query = "SELECT  * from nomi_empleados";
        $result = $this->queryArray($query);

        return array("empleados" => $result['rows']);
    }
    public function datosClienteFact($idCliente){
        $query = 'SELECT f.*, m.idmunicipio as idMunicipio from comun_facturacion f, municipios m where f.municipio=m.municipio and f.nombre='.$idCliente;
        $result = $this->queryArray($query);


        return array("fact" => $result['rows']);
    }
    public function creditos(){
        $queryCre = "SELECT * from app_tipo_credito where activo=1";
        $rescredi = $this->queryArray($queryCre);

        return $rescredi['rows'];
    }
    public function moneda(){
        $query = "SELECT * from cont_coin";
        $result = $this->queryArray($query);

        return $result['rows'];
    }
    public function clasificadoresTipos(){
        $queryClas = "SELECT * from app_clasificadores where tipo=1 and activo=1";
        $resClas= $this->queryArray($queryClas);

        return $resClas['rows'];
    }
    public function bancos(){
        $query = "SELECT * from cont_bancos";
        $resBan = $this->queryArray($query);

        return $resBan['rows'];
    }

    public function guardaCliente($idCliente,$codigo,$nombre,$tienda,$mumint,$numext,$direccion,$colonia,$cp,$estado,$municipio,$email,$celular,$tel1,$tel2,$ciudad,$cumpleanos,$rfc,$curp,$diasCredito,$limiteCredito,$moneda,$listaPrecio,$razonSocial,$emailFacturacion,$direccionFact,$numextFact,$numintFact,$coloniaFact,$cpFact,$estadoFact,$municipiosFact,$ciudadFact,$tipoDeCredito,$descuentoPP,$interesesMoratorios,$perVenCre,$perExLim,$comisionVenta,$comisionCobranza,$empleado,$enviosDom,$tipoClas,$idComunFact,$regimenFact,$banco,$numCuenta,$cuentaCont,$pais,$paisFact){

        $queryCliente = "INSERT INTO comun_cliente (codigo,nombre,nombretienda,direccion,colonia,email,celular,cp,idPais,idEstado,idMunicipio,rfc,curp,telefono1,telefono2,ciudad,cumpleanos,limite_credito,dias_credito,num_ext,num_int,id_clasificacion,permitir_vtas_credito,id_tipo_credito,permitir_exceder_limite,dcto_pronto_pago,intereses_moratorios,id_lista_precios,envios,comision_vta,comision_cobranza,idBanco,numero_cuenta_banco,idVendedor,cuenta) values ";
        $queryCliente .="('".$codigo."','".$nombre."','".$tienda."','".$direccion."','".$colonia."','".$email."','".$celular."','".$cp."','".$pais."','".$estado."','".$municipio."','".$rfc."','".$curp."','".$tel1."','".$tel2."','".$ciudad."','".$cumpleanos."','".$limiteCredito."','".$diasCredito."','".$numext."','".$mumint."','".$tipoClas."','".$perVenCre."','".$tipoDeCredito."','".$perExLim."','".$descuentoPP."','".$interesesMoratorios."','".$listaPrecio."','".$enviosDom."','".$comisionVenta."','".$comisionCobranza."','".$banco."','".$numCuenta."','".$empleado."','".$cuentaCont."')";
        $insertClienteRes = $this->queryArray($queryCliente);
        $idClienteInsert = $insertClienteRes['insertId'];

        if($rfc!='' && $razonSocial!='' ){
            $comunFact = $this->guardaComunFact($idClienteInsert,$rfc,$razonSocial,$direccionFact,$numextFact,$numintFact,$coloniaFact,$cpFact,$estadoFact,$municipiosFact,$ciudadFact,$emailFacturacion,$regimenFact,$paisFact);
        }else{
            $comunFact = 0;
        }


        return array('status' => true , 'idClienteInser' => $idClienteInsert, 'comun_fac' => $comunFact);

    }
    public function guardaComunFact($idClienteInsert,$rfc,$razonSocial,$direccionFact,$numextFact,$numintFact,$coloniaFact,$cpFact,$estadoFact,$municipiosFact,$ciudadFact,$emailFacturacion,$regimenFact,$paisFact){

        $extInt = '';
        if($numintFact!=''){
            $extInt = $numextFact.' Int. '.$numintFact;
        }else{
            $extInt = $numextFact;
        }
        $selcMuni = "SELECT * from municipios where idmunicipio=".$municipiosFact;
        $resmunici = $this->queryArray($selcMuni);
        $municipioNombre = $resmunici['rows'][0]['municipio'];

        $insertCo = "INSERT into comun_facturacion(nombre,rfc,razon_social,correo,pais,regimen_fiscal,domicilio,num_ext,cp,colonia,idPais,estado,ciudad,municipio) values('".$idClienteInsert."','".$rfc."','".$razonSocial."','".$emailFacturacion."','Mexico','".$regimenFact."','".$direccionFact."','".$extInt."','".$cpFact."','".$coloniaFact."','".$paisFact."','".$estadoFact."','".$ciudadFact."','".$municipioNombre."')";
        //echo $insertCo;
        $resInsert = $this->queryArray($insertCo);

        return $resInsert['insertId'];

    }
    public function updateCliente($idCliente,$codigo,$nombre,$tienda,$mumint,$numext,$direccion,$colonia,$cp,$estado,$municipio,$email,$celular,$tel1,$tel2,$ciudad,$cumpleanos,$rfc,$curp,$diasCredito,$limiteCredito,$moneda,$listaPrecio,$razonSocial,$emailFacturacion,$direccionFact,$numextFact,$numintFact,$coloniaFact,$cpFact,$estadoFact,$municipiosFact,$ciudadFact,$tipoDeCredito,$descuentoPP,$interesesMoratorios,$perVenCre,$perExLim,$comisionVenta,$comisionCobranza,$empleado,$enviosDom,$tipoClas,$idComunFact,$regimenFact,$banco,$numCuenta,$cuentaCont,$pais,$paisFact){

        $update = "UPDATE comun_cliente set codigo='".$codigo."', nombre='".$nombre."', nombretienda='".$tienda."', direccion='".$direccion."', colonia='".$colonia."', email='".$email."', celular='".$celular."', cp='".$cp."', idPais='".$pais."', idEstado='".$estado."', idMunicipio='".$municipio."', rfc='".$rfc."', curp='".$curp."', telefono1='".$tel1."', telefono2='".$tel2."', ciudad='".$ciudad."', cumpleanos='".$cumpleanos."', limite_credito='".$limiteCredito."', dias_credito='".$diasCredito."', num_ext='".$numext."', num_int='".$numint."', id_moneda='".$moneda."', id_clasificacion='".$tipoClas."', permitir_vtas_credito='".$perVenCre."', id_tipo_credito='".$tipoDeCredito."', permitir_exceder_limite='".$perExLim."', dcto_pronto_pago='".$descuentoPP."', intereses_moratorios='".$interesesMoratorios."', id_lista_precios='".$listaPrecio."', envios='".$enviosDom."', comision_vta='".$comisionVenta."', comision_cobranza='".$comisionCobranza."', idBanco='".$banco."', numero_cuenta_banco='".$numCuenta."', idVendedor='".$empleado."', cuenta='".$cuentaCont." 'where id=".$idCliente;
        $resUpdate = $this->queryArray($update);

        if($idComunFact!=''){
            $comunFact = $this->updateComunFact($idCliente,$rfc,$razonSocial,$direccionFact,$numextFact,$numintFact,$coloniaFact,$cpFact,$estadoFact,$municipiosFact,$ciudadFact,$emailFacturacion,$idComunFact,$regimenFact,$paisFact);

        }elseif($rfc!='' && $razonSocial!='' && $idComunFact==''){
            $comunFact = $this->guardaComunFact($idCliente,$rfc,$razonSocial,$direccionFact,$numextFact,$numintFact,$coloniaFact,$cpFact,$estadoFact,$municipiosFact,$ciudadFact,$emailFacturacion,$regimenFact,$paisFact);
        }

        return array('status' => true , 'idClienteInser' => $idCliente, 'comun_fac' => $comunFact);

    }
    public function updateComunFact($idCliente,$rfc,$razonSocial,$direccionFact,$numextFact,$numintFact,$coloniaFact,$cpFact,$estadoFact,$municipiosFact,$ciudadFact,$emailFacturacion,$idComunFact,$regimenFact,$paisFact){

        $extInt = '';
        //echo 'rr'.$numextFact;
        //echo 'int'.$numintFact;
        if($numintFact!=''){
            $extInt = $numextFact.' Int. '.$numintFact;
        }else{
            $extInt = $numextFact;
        }
    /*    echo 'XX '. $extInt;
        exit(); */
        $selcMuni = "SELECT * from municipios where idmunicipio=".$municipiosFact;
        $resmunici = $this->queryArray($selcMuni);
        $municipioNombre = $resmunici['rows'][0]['municipio'];

        $update = "UPDATE comun_facturacion set rfc='".$rfc."', razon_social='".$razonSocial."', correo='".$emailFacturacion."', pais='Mexico', regimen_fiscal='', domicilio='".$direccionFact."', num_ext='".$extInt."', cp='".$cpFact."', colonia='".$coloniaFact."', idPais='".$paisFact."', estado='".$estadoFact."', ciudad='".$ciudadFact."', regimen_fiscal='".$regimenFact."', municipio='".$municipioNombre."' where nombre=".$idCliente." and id=".$idComunFact;
        //echo $update;
        //exit();
        $resUpdate = $this->queryArray($update);

        return $idComunFact;

    }
    public function borraCliente($idCliente){
        $sel = 'UPDATE comun_cliente set borrado=1 where id='.$idCliente;
        $res = $this->queryArray($sel);

        return  array('estatus' => true );

    }
    public function activaCliente($idCliente){
        $sel = 'UPDATE comun_cliente set borrado=0 where id='.$idCliente;
        $res = $this->queryArray($sel);

        return  array('estatus' => true );

    }

    public function buscarLocalizacion( $idLoc, $patron, $parentLoc) {

        switch ($idLoc) {
            case '1':
                $id = 'idpais';
                $nombre = 'pais';
                $tabla = 'paises';
                $filtro = "";
                break;
            case '2':
                $id = 'idestado';
                $nombre = 'estado';
                $tabla = 'estados';
                $filtro = "AND idpais='$parentLoc'";
                break;
            case '3':
                $id = 'idmunicipio';
                $nombre = 'municipio';
                $tabla = 'municipios';
                $filtro = "AND idestado='$parentLoc'";
                break;
            default:
                # code...
                break;
        }
        if($parentLoc == "")
            $filtro = "AND 1 = 2";

        $sql = "SELECT  $id AS id, $nombre as text
                FROM    $tabla
                WHERE   $nombre LIKE '%$patron%' $filtro";

        $res = $this->queryArray($sql);
        foreach ($res as $key => $value) {
            $value = htmlspecialchars($value['text']);
        }

        return json_encode( $res );
    }

    function nuevoPais( $nombre ){
        $sql = "INSERT INTO paises (pais)
                VALUES  ('$nombre')";
        $res = $this->queryArray($sql);

        return json_encode( $res );
    }

    function nuevoEstado( $nombre , $idPais){
        $sql = "INSERT INTO estados (estado, idpais)
                VALUES  ('$nombre', '$idPais')";
        $res = $this->queryArray($sql);

        return json_encode( $res );
    }

    function nuevoMunicipio( $nombre , $idEstado){
        $sql = "INSERT INTO municipios (municipio, idestado)
                VALUES  ('$nombre', '$idEstado')";
        $res = $this->queryArray($sql);

        return json_encode( $res );
    }



}
?>
