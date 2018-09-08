<?php
//Carga la clase de coneccion con sus metodos para consultas o transacciones
//require("models/connection.php"); // funciones mySQL 
require("models/connection_sqli_manual.php"); // funciones mySQLi

class ordenservicioModel extends Connection
{
	public function lista()
	{
		$myQuery = "SELECT* FROM cont_ejercicios";
		$resultados = $this->query($myQuery);
		return $resultados;
	}

    public function cuenta($nc)
    {
        $myQuery = "SELECT description FROM cont_accounts WHERE account_id = $nc";
        $cuenta = $this->query($myQuery);
        $cuenta = $cuenta->fetch_assoc();
        return $cuenta['description'];
    }


    function listar2()
        {
            $myQuery = "SELECT * FROM cont_ejercicios";
            $producto = $this->queryArray($myQuery);
            return $producto["rows"];
        }
/////////////////////////////////////CONVENIOS//////////////////////////////////////////////////////////////////////////
    function listarConvenios()
        {
            $myQuery = "SELECT a.idconvenio, a.clave, a.id as idcliente, b.nombre, a.idestado, c.estado, a.idmunicipio, d.municipio, a.idcapacidadunidad, e.capacidad, a.temperatura, a.descripcion, a.iddesccorta, f.concepto, a.precio_cliente, a.precio_proveedor, a.retencion, a.comision, a.comision_porcentual, a.coordinacion, a.incluir, a.idsolicitud, a.cantidad, a.incluir
                        from tran_convenio a
                        left join comun_cliente b on b.id = a.id
                        left join estados c on c.idestado = a.idestado
                        left join municipios d on d.idmunicipio = a.idmunicipio
                        left join tran_capacidadunidad e on e.idcapacidadunidad = a.idcapacidadunidad
                        left join tran_desccorta f on f.iddesccorta = a.iddesccorta;";
            $producto = $this->queryArray($myQuery);
            return $producto["rows"];
        }
    function listarConveniosSol($idsolicitud)
        {
            $myQuery = "SELECT a.idconvenio, a.clave, a.id as idcliente, b.nombre, a.idestado, c.estado, a.idmunicipio, d.municipio, a.idcapacidadunidad, e.capacidad, a.temperatura, a.descripcion, a.iddesccorta, f.concepto, a.precio_cliente, a.precio_proveedor, a.retencion, a.comision, a.comision_porcentual, a.coordinacion, a.incluir, a.idsolicitud, a.cantidad
                        from tran_convenio a
                        left join comun_cliente b on b.id = a.id
                        left join estados c on c.idestado = a.idestado
                        left join municipios d on d.idmunicipio = a.idmunicipio
                        left join tran_capacidadunidad e on e.idcapacidadunidad = a.idcapacidadunidad
                        left join tran_desccorta f on f.iddesccorta = a.iddesccorta
                        where a.idsolicitud = '$idsolicitud';";
            $producto = $this->queryArray($myQuery);
            return $producto["rows"];
        }


        function listarevireq(){
       $myQuery = "SELECT a.idevireq  , a.idcliente , b.nombretienda , c.estado, d.municipio ,a.requerimientos ,a.evidencia
        FROM tran_evi_req a
        left join comun_cliente b on b.id = a.idcliente
        left join estados c on c.idestado = b.idEstado
        left join municipios d on d.idmunicipio = b.idMunicipio;";
            $evireq = $this->queryArray($myQuery);
                    return $evireq["rows"];
        }
        function edited_evireq($idevireq,$idcliente)
    {      
        $myQuery = "SELECT a.idevireq , b.id ,b.nombretienda , a.evidencia , a.requerimientos,a.idcliente from tran_evi_req a 
                    LEFT JOIN  comun_cliente b  on b.id = a.idcliente
                    WHERE idevireq = '$idevireq' and b.id = '$idcliente';";
        $evireq = $this->queryArray($myQuery);
            return $evireq["rows"];
    }

        function send_edited_evireq($idevireqE,$evidencias,$requerimientos)
        {
         $registro = 0;
         $myQuery =  "UPDATE tran_evi_req SET
         tran_evi_req.requerimientos = '$requerimientos',
         tran_evi_req.evidencia = '$evidencias'
         WHERE tran_evi_req.idevireq = $idevireqE ;";
    
           if($this->query($myQuery)){
            $registro = 1;
           }
            if ($registro == 1)
            {
                echo 1;
            }else{
                echo "Registro Falido!!";
            }   
        }


        function listarRelSolCon($folio,$id,$incluir,$idsolicitud)
        {
           
            $myQuery = "SELECT a.idconvenio, a.cantidad, a.descripcion, c.iddesccorta, c.concepto, a.retencion, a.precio_cliente , a.incluir , d.id , d.nombre , b.incluir , b.idsolicitud , b.id_cliente
                        from tran_convenio a
                        left join tran_relacion_sol_con b on b.idconvenio = a.idconvenio
                        left join tran_desccorta c on c.iddesccorta = a.iddesccorta
                        left join comun_cliente d on d.id = b.id_cliente
                       where d.id = '$id';";
            $rel = $this->queryArray($myQuery);
            return $rel["rows"];
        }
    function listarConveniosEdit($idconvenio)
        { 
            $myQuery = "SELECT a.idconvenio, a.clave, a.id as idcliente, b.nombre, a.idestado, c.estado, a.idmunicipio, d.municipio, a.idcapacidadunidad, e.capacidad, a.temperatura, a.descripcion, a.iddesccorta, f.concepto, a.precio_cliente, a.precio_proveedor, a.retencion, a.comision, a.comision_porcentual, a.coordinacion, a.incluir
                        from tran_convenio a
                        left join comun_cliente b on b.id = a.id
                        left join estados c on c.idestado = a.idestado
                        left join municipios d on d.idmunicipio = a.idmunicipio
                        left join tran_capacidadunidad e on e.idcapacidadunidad = a.idcapacidadunidad
                        left join tran_desccorta f on f.iddesccorta = a.iddesccorta
                        where a.idconvenio = '$idconvenio';";
            $producto = $this->queryArray($myQuery);
            return $producto["rows"];
        }
    
    function listarClientes()
        {
            $myQuery = "SELECT id , nombre , nombretienda,celular, direccion , colonia , idtipotienda ,cp , idEstado  , idMunicipio  FROM comun_cliente  
            ORDER BY id  ASC   ";
            $clientes = $this->queryArray($myQuery);
            return $clientes["rows"];
        }
    function listarClientesconv($lastid){
        $myQuery = "SELECT id , nombre , nombretienda,celular, direccion , colonia , idtipotienda ,cp , idEstado  , idMunicipio  FROM comun_cliente  WHERE id = '$lastid';";
            $clientes = $this->queryArray($myQuery);
            return $clientes["rows"];

    }
        function listarDestinatarios()
        {
            $myQuery = "SELECT id , nombre , nombretienda,celular, direccion , colonia , idtipotienda ,cp , idEstado  , idMunicipio  FROM comun_destinatario  ORDER BY id ASC  ";
            $clientes = $this->queryArray($myQuery);
            return $clientes["rows"];
        }
    function listarOperadores1($idOS)
        {
            $myQuery = "SELECT a.idEmpleado,concat(a.nombreEmpleado,' ',a.apellidoPaterno,' ',a.apellidoMaterno) nombreEmpleado 
                        FROM nomi_empleados a 
                        left join tran_ordenservicio b on b.idEmpleado = a.idEmpleado
                        where  b.idordenservicio = '$idOS';";
            $operadores1 = $this->queryArray($myQuery);
            return $operadores1["rows"];
        }
    function listarClientes1($idcliente)
        {
            $myQuery = "SELECT a.id , a.nombre , a.nombretienda,a.celular, a.direccion , a.colonia , a.idtipotienda ,a.cp , a.idEstado  , a.idMunicipio ,b.idcliente,b.evidencia,b.requerimientos,b.idevireq
        FROM comun_cliente a
        LEFT JOIN tran_evi_req b on b.idcliente = a.id
        WHERE a.id = '$idcliente' ;";
            $clientes1 = $this->queryArray($myQuery);
            return $clientes1["rows"];
        }
        function listarDestinatarios1($iddestinatario)
        {
            $myQuery = "SELECT *
                        FROM comun_destinatario a 
                        where id = '$iddestinatario';";
            $clientes1 = $this->queryArray($myQuery);
            return $clientes1["rows"];
        }
    function listarCapacidad()
        {
            $myQuery = "SELECT * FROM tran_capacidadunidad";
            $capacidad = $this->queryArray($myQuery);
            return $capacidad["rows"];
        }
    function listarTipounidad()
        {
            $myQuery = "SELECT * FROM tran_tipounidad";
            $tipounidad = $this->queryArray($myQuery);
            return $tipounidad["rows"];
        }
    function listarTipogas()
        {
            $myQuery = "SELECT * FROM tran_tipocombustible";
            $tipounidad = $this->queryArray($myQuery);
            return $tipounidad["rows"];
        }

    function listarTipocaja()
    {
        $myQuery = "SELECT * FROM tran_tipo_caja";
        $tipocaja = $this->queryArray($myQuery);
        return $tipocaja["rows"];
    }

    function listarEmbalaje()
    {
        $myQuery = "SELECT * FROM tran_embalaje";
        $tipoembalaje = $this->queryArray($myQuery);
        return $tipoembalaje["rows"];
    }
    function listarOperadores()
        {  
           $estatus = "DISPONIBLE";
           
            $myQuery = "SELECT idEmpleado,concat( nombreEmpleado,' ', apellidoPaterno) operador
                        FROM nomi_empleados a
                        WHERE estatus_tran = '$estatus' and nomi_empleado_clasif = '1';";
            $operadores = $this->queryArray($myQuery);
            return $operadores["rows"];
        }
    function listarUnidades($idordenservicio,$espesifico,$idunidad)
        {

            $estatus = "1";
          
            $myQuery = "SELECT a.idunidad, concat(a.no_economico,' / ',a.placas,' / ',a.color,' / ',b.marca,' / ',c.tipo,' / ',d.capacidad) unidad from tran_unidades a
                            left join tran_marca b on b.idmarca = a.idmarca
                            left join tran_tipounidad c on c.idtipounidad = a.idtipounidad
                            left join tran_capacidadunidad d on d.idcapacidadunidad = a.idcapacidadunidad
                           where a.estatus = '$estatus';";
            $unidades = $this->queryArray($myQuery);
            return $unidades["rows"];
        }
    function listarCajas($idordenservicio,$espesifico,$idcajatractor)
        {   
            $estatus = "1";
            if($espesifico == "SI"){
                $where = 'WHERE a.estatus = "'.$estatus.'" or c.idcajatractor = '.$idcajatractor.''; // muestra en servicio espesifica de la OS
            }
            else{
                $where = 'WHERE a.estatus = "'.$estatus.'"'; /// muestra solo disponibles
            }
            $myQuery = "SELECT a.idcajatractor, concat(a.no_economico,' / ',a.placas,' / ',b.tipocaja,' / ',a.color) unidad from tran_cajatractor a
                            left join tran_tipo_caja b on b.id = a.tipocaja
                         WHERE a.estatus = '$estatus';";
            $cajas = $this->queryArray($myQuery);
            return $cajas["rows"];
        }
    function listarEstados()
        {
            $myQuery = "SELECT * FROM estados";
            $estados = $this->queryArray($myQuery);
            return $estados["rows"];
        }

    function listarFabricante()
        {
            $myQuery = "SELECT * FROM tran_marca";
            $fabricante = $this->queryArray($myQuery);
            return $fabricante["rows"];
        }

    function listarCiudades($idest)
        {
            $myQuery = "SELECT * FROM municipios where idestado = '$idest'";
            $estados = $this->queryArray($myQuery);
            return $estados["rows"];
        }
    function listarDesccorta()
        {
            $myQuery = "SELECT * FROM tran_desccorta";
            $estados = $this->queryArray($myQuery);
            return $estados["rows"];
        }
    function listaDesccortaGastos()
        {
            $myQuery = "SELECT iddesccorta_gastos , concepto FROM tran_desccorta_gastos";
            $descgastos = $this->queryArray($myQuery);
            return $descgastos["rows"];
        }
    function listarTipocarga()
        {
            $myQuery = "SELECT * FROM tran_tipocarga";
            $tipocarga = $this->queryArray($myQuery);
            return $tipocarga["rows"];
        }
    function savedConvenio($lastid,$idsolicitud,$desc,$desccorta,$precioclie,$retencion,$comisionporc)
        {
            $idconvenio = $lastid ;
            $registro = 0;
            
           echo  $myQuery ="INSERT INTO tran_convenio (
                            id,descripcion,iddesccorta,precio_cliente,retencion,comision_porcentual,incluir,idsolicitud,cantidad) 
                        VALUES 
                        ('$lastid','$desc','$desccorta','$precioclie','$retencion','$comisionporc',1,'$idsolicitud',1);";
            
          /*  $myQuery .= "INSERT INTO tran_relacion_sol_con (idsolicitud,idconvenio,cantidad,incluir,id_cliente) 
                        VALUES ('$idsolicitud','$idconvenio',1,0,$cli);"; */


            if($this->multi_query($myQuery)){
            $registro = 1;
           }
            if ($registro == 1)
            {
                echo 1;
            }else{
                echo "Registro Falido!!";
            }
                
        }
        
    function savedEvireq($cliente,$requerimientos,$evidencias)
    {
        $registro = 0;

        $myQuery = "INSERT INTO tran_evi_req (idcliente,requerimientos,evidencia)
                    VALUES              ('$cliente','$requerimientos','$evidencias');";



            if($this->query($myQuery)){
            $registro = 1;
           }
            if ($registro == 1)
            {
                echo 1;
            }else{
                echo "Registro Falido!!";
            }

    }

    function editedConvenio($idconvenioE,$cli,$est,$ciu,$cap,$temp,$desc,$desccorta,$precioclie,$preciopro,$retencion,$comisionfija,$comisionporc,$coor)
        {
            $registro = 0;
            $myQuery = "UPDATE tran_convenio SET
                        tran_convenio.id='$cli',
                        tran_convenio.idestado='$est',
                        tran_convenio.idmunicipio='$ciu',
                        tran_convenio.idcapacidadunidad='$cap',
                        tran_convenio.temperatura='$temp',
                        tran_convenio.descripcion='$desc',
                        tran_convenio.iddesccorta='$desccorta',
                        tran_convenio.precio_cliente='$precioclie',
                        tran_convenio.precio_proveedor='$preciopro',
                        tran_convenio.retencion='$retencion',
                        tran_convenio.comision='$comisionfija',
                        tran_convenio.comision_porcentual='$comisionporc',
                        tran_convenio.coordinacion='$coor'
                        where tran_convenio.idconvenio = $idconvenioE;";
           
           if($this->query($myQuery)){
            $registro = 1;
           }
            if ($registro == 1)
            {
                echo 1;
            }else{
                echo "Registro Falido!!";
            }
        }
    function deletedConvenio($idconvenioD){
        $registro = 0;
        $myQuery = "DELETE FROM tran_convenio WHERE tran_convenio.idconvenio = '$idconvenioD';";
        if($this->query($myQuery)){
            $registro = 1;
           }
            if ($registro == 1)
            {
                echo 1;
            }else{
                echo "Eliminacion Fallida!!";
            }
    }

    function deletedEvireq($idevireq){
        $registro = 0;
        $myQuery = "DELETE FROM tran_evi_req WHERE tran_evi_req.idevireq = '$idevireq';";
        if($this->query($myQuery)){
            $registro = 1;
           }
            if ($registro == 1)
            {
                echo 1;
            }else{
                echo "Eliminacion Fallida!!";
            }
    }

    function deletedgastoX($idgastoextra){
        $registro = 0;
         $myQuery = "UPDATE tran_gastoextra SET
                        tran_gastoextra.estatus = 0
                        where tran_gastoextra.idgastoextra = '$idgastoextra';";
        if($this->query($myQuery)){
            $registro = 1;
           }
            if ($registro == 1)
            {
                echo 1;
            }else{
                echo "Eliminacion Fallida!!";
            }

            }

     function deletedanticipo($idanticipo){
       $registro = 0;
            $myQuery = "UPDATE tran_anticipo SET
                        tran_anticipo.estatus = 0
                        where tran_anticipo.idanticipo = '$idanticipo';";
           
           if($this->query($myQuery)){
            $registro = 1;
           }
            if ($registro == 1)
            {
                echo 1;
            }else{
                echo "Registro Falido!!";
            }
    }

    /////////////////////////////////////SOLICITUDES//////////////////////////////////////////////////////////////////////////
        function listarSolicitudes()
        {
            $estatus = "1";
            $myQuery = "SELECT DISTINCT a.idsolicitud folio, a.fecha, a.hora, d.nombre cliente, f.municipio poblacion, e.estado, g.capacidad, a.temperatura, a.fechac, a.horac, a.fechae, a.horae, h.incluir , a.id id , k.nombre destinatario , k.id id_destinatario , j.estatus_solicitud,d.nombretienda , k.nombretienda nombre_tien_des
            from tran_solicitud a
            left join tran_datoscarga b on a.iddatoscarga = b.iddatoscarga
            left join tran_datosentrega c on a.iddatoscarga = c.iddatosentrega
            left join comun_cliente d on a.id = d.id
            left join comun_destinatario k on k.id = a.id_destinatario
            left join estados e on b.idestado =e.idestado 
            left join municipios f on b.idmunicipio = f.idmunicipio
            left join tran_capacidadunidad g on g.idcapacidadunidad = a.idcapacidadunidad
            left join tran_convenio h on h.idsolicitud = a.idsolicitud
            left join tran_est_sol j on j.id = a.estatus
            where a.estatus= '$estatus';";
            $solicitudes = $this->queryArray($myQuery);
            return $solicitudes["rows"];
        }
        function listarSolicitudEdit($idsolicitud)
        { 
            $myQuery = "SELECT a.idsolicitud, a.fecha, a.hora, a.id,
                        b.nombre, b.nombretienda, b.celular, 
                        a.idcapacidadunidad, e.capacidad, a.idtipocarga, g.tipocarga, 
                        l.tipoembalaje, a.peso, a.temperatura, a.grados, a.tipo_servicio, a.viaje, 
                        h.iddatoscarga, h.carga_en, h.calle as callec, h.referencia as referenciac, h.colonia as coloniac, c.estado as estadoc, d.municipio municipioc, h.preguntar_por as preguntar_porc, h.telefono as telefonoc, a.fechac, a.horac,
                        i.id_destinatario, p.nombre nombre_des , p.celular celular_des , p.nombretienda tienda_des,
                        i.iddatosentrega, i.entrega_en, i.calle as callee, i.referencia as referenciae, i.colonia as coloniae, j.estado as estadoe, k.municipio as municipioe, i.preguntar_por as preguntar_pore, i.telefono as telefonoe, a.fechae, a.horae,
                        a.atencion, a.evidencia, a.req_cliente, a.recomendacion
                        from tran_solicitud a
                        left join comun_cliente b on b.id = a.id
                        left join tran_capacidadunidad e on e.idcapacidadunidad = a.idcapacidadunidad
                        left join tran_tipocarga g on g.idtipocarga = a.idtipocarga
                        left join tran_datoscarga h on h.iddatoscarga = a.iddatoscarga
                        left join tran_datosentrega i on i.iddatosentrega = a.iddatosentrega
                        left join comun_destinatario p on i.id_destinatario = p.id
                        left join estados c on c.idestado = h.idestado
                        left join municipios d on d.idmunicipio = h.idmunicipio
                        left join estados j on j.idestado = i.idestado
                        left join municipios k on k.idmunicipio = i.idmunicipio
                        left join tran_embalaje l on l.idembalaje = a.embalaje 
                        where a.idsolicitud = '$idsolicitud';";
            $solicitud = $this->queryArray($myQuery);
            return $solicitud["rows"];
        }
        function listarDatoscarga($idcliente)
        {
            $myQuery = "SELECT a.iddatoscarga, a.carga_en, a.calle, a.referencia, a.colonia, e.estado, f.municipio, a.preguntar_por, a.telefono , a.id_cliente from tran_datoscarga a
                         left join estados e on a.idestado =e.idestado
                         left join comun_cliente c on c.id = a.id_cliente
                         left join municipios f on a.idmunicipio = f.idmunicipio
                        where a.id_cliente = '$idcliente';";
            $datoscarga = $this->queryArray($myQuery);
            return $datoscarga["rows"];
        }
        function listarDatoscarga1($iddatoscarga)
        {
            $myQuery = "SELECT a.iddatoscarga, a.carga_en, a.calle, a.referencia, a.colonia, e.estado, f.municipio, a.preguntar_por, a.telefono from tran_datoscarga a
                         left join estados e on a.idestado =e.idestado
                         left join municipios f on a.idmunicipio = f.idmunicipio
                        where a.iddatoscarga = '$iddatoscarga';";
            $datoscarga1 = $this->queryArray($myQuery);
            return $datoscarga1["rows"];
        }
        function listarDatosentrega($iddestinatario)
        { 
            $myQuery = "SELECT a.iddatosentrega, a.entrega_en, a.calle, a.referencia, a.colonia, e.estado, f.municipio, a.preguntar_por, a.telefono ,a.id_destinatario , d.id,d.direccion from tran_datosentrega a
                         left join estados e on a.idestado =e.idestado
                         left join comun_destinatario d on d.id = a.id_destinatario
                         left join municipios f on a.idmunicipio = f.idmunicipio
                         where a.id_destinatario = '$iddestinatario';";
            $datosentrega = $this->queryArray($myQuery);
            return $datosentrega["rows"];
        }
        function listarDatosentrega1($iddatosentrega)
        {
            $myQuery = "SELECT a.iddatosentrega, a.entrega_en, a.calle, a.referencia, a.colonia, e.estado, f.municipio, a.preguntar_por, a.telefono from tran_datosentrega a
                         left join estados e on a.idestado =e.idestado
                         left join municipios f on a.idmunicipio = f.idmunicipio
                         where a.iddatosentrega = '$iddatosentrega';";
            $datoscarga1 = $this->queryArray($myQuery);
            return $datoscarga1["rows"];
        }
        function savedSolicitud($idcliente,$fechaD,$horaD,$embalaje,$peso,
            $grados,$fechaC,$horaC,$fechaE,$horaE,$atencion,$recomendaciones,$requerimientos,$evidencias,$idcapacidad,$idtipocarga,
            $iddatoscarga,$iddatosentrega,$temp,$servi,$viaje,$estatus,$iddestinatario)
        {
            $registro = 0; 
            $myQuery = "INSERT INTO tran_solicitud (fecha,hora,id,idcapacidadunidad,embalaje,peso,idtipocarga,temperatura,grados,tipo_servicio,viaje,iddatoscarga,fechac,horac,iddatosentrega,fechae,horae,atencion,evidencia,req_cliente,recomendacion,estatus,id_destinatario) VALUES 
            ('$fechaD','$horaD','$idcliente','$idcapacidad','$embalaje','$peso','$idtipocarga','$temp','$grados','$servi','$viaje','$iddatoscarga','$fechaC','$horaC','$iddatosentrega','$fechaE','$horaE','$atencion','$evidencias','$requerimientos','$recomendaciones','$estatus','$iddestinatario');";
            if($this->query($myQuery)){
                $registro = 1;
            }
            if ($registro == 1)
            {
                echo 1;
            }else{
                echo "Registro Falido!!";
            }
                
        }
        function editedSolicitud($idsolicitud,$idcliente,$fechaD,$horaD,$embalaje,$peso,
            $grados,$fechaC,$horaC,$fechaE,$horaE,$atencion,$recomendaciones,$requerimientos,$evidencias,$idcapacidad,$idtipocarga,
            $iddatoscarga,$iddatosentrega,$temp,$servi,$viaje)
        {
            $registro = 0;
            $myQuery = "UPDATE tran_solicitud SET
                        tran_solicitud.fecha = '$fechaD',
                        tran_solicitud.hora = '$horaD',
                        tran_solicitud.id = '$idcliente',
                        tran_solicitud.idcapacidadunidad = '$idcapacidad',
                        tran_solicitud.embalaje = '$embalaje',
                        tran_solicitud.peso = '$peso',
                        tran_solicitud.idtipocarga = '$idtipocarga',
                        tran_solicitud.temperatura = '$temp',
                        tran_solicitud.grados = '$grados',
                        tran_solicitud.tipo_servicio = '$servi',
                        tran_solicitud.viaje = '$viaje',
                        tran_solicitud.iddatoscarga = '$iddatoscarga',
                        tran_solicitud.fechac = '$fechaC',
                        tran_solicitud.horac = '$horaC',
                        tran_solicitud.iddatosentrega = '$iddatosentrega',
                        tran_solicitud.fechae = '$fechaE',
                        tran_solicitud.horae = '$horaE',
                        tran_solicitud.atencion = '$atencion',
                        tran_solicitud.evidencia = '$evidencias',
                        tran_solicitud.req_cliente = '$requerimientos',
                        tran_solicitud.recomendacion = '$recomendaciones'


                        where tran_solicitud.idsolicitud = '$idsolicitud';";
           
           if($this->query($myQuery)){
            $registro = 1;
           }
            if ($registro == 1)
            {
                echo 1;
            }else{
                echo "Registro Falido!!";
            }
        }
    function savedLugarcarga($carga_en,$calleC1,$estadoC1,$ciudadC1,$referenciaC1,$coloniaC1,$preguntarC1,$telefonoC1,$idcliente)
        {
            $registro = 0;
            $myQuery = "INSERT INTO tran_datoscarga (carga_en,calle,referencia,colonia,idestado,idmunicipio,preguntar_por,telefono,id_cliente) VALUES 
                                                ('$carga_en','$calleC1','$referenciaC1','$coloniaC1','$estadoC1','$ciudadC1','$preguntarC1','$telefonoC1','$idcliente');";
            if($this->query($myQuery)){
                $registro = 1;
            }
            if ($registro == 1)
            {
                echo 1;
            }else{
                echo "Registro Falido!!";
            }
                
        }
    function savedLugarentrega($entrega_en,$calleE1,$estadoE1,$ciudadE1,$referenciaE1,$coloniaE1,$preguntarE1,$telefonoE1,$iddestinatario)
        {
            $registro = 0;
            $myQuery = "INSERT INTO tran_datosentrega (entrega_en,calle,referencia,colonia,idestado,idmunicipio,preguntar_por,telefono,id_destinatario) VALUES 
                                              ('$entrega_en','$calleE1','$referenciaE1','$coloniaE1','$estadoE1','$ciudadE1','$preguntarE1','$telefonoE1','$iddestinatario');";
            if($this->query($myQuery)){
                $registro = 1;
            }
            if ($registro == 1)
            {
                echo 1;
            }else{
                echo "Registro Falido!!";
            }
                
        }


        function deletedSolicitud($folio){
        $registro = 0;
        $myQuery = "DELETE FROM tran_solicitud WHERE tran_solicitud.idsolicitud = '$folio';";
        if($this->query($myQuery)){
            $registro = 1;
           }
            if ($registro == 1)
            {
                echo 1;
            }else{
                echo "Eliminacion Fallida!!";
            }
        }


    function borrarOSM($idordenservicio,$idcajatractor,$idEmpleado,$idunidad){
        $registro = 0;
$myQuery = "UPDATE nomi_empleados SET 
                nomi_empleados.estatus_tran = DISPONIBLE where nomi_empleados.idEmpleado = '$idEmpleado';";

$myQuery = "UPDATE tran_unidades SET
                tran_unidades.estatus = 1 WHERE tran_unidades.idunidad = '$idunidad';";

$myQuery = "UPDATE tran_cajatractor SET
                tran_cajatractor.estatus = 1 WHERE tran_cajatractor.idcajatractor = '$idcajatractor';";

$myQuery = "DELETE FROM tran_ordenservicio WHERE tran_ordenservicio.idordenservicio = '$idordenservicio';";

        if($this->multi_query($myQuery)){
            $registro = 1;
           }
            if ($registro == 1)
            {
                echo $myQuery;
            }else{
                echo "Eliminacion Falida!!";
            }
        }

        function SavedNewope($nombre,$apellido,$telefono,$rfc,$edad,$estado,$ciudad,$domicilio,$numero_ext,$numero_int,$colonia,$codigopostal,$licencia,$fecha,$tel1,$tel2,$nom_emer)
         {

                $registro = 0;

                $myQuery = "INSERT INTO tran_operadores (nombreEmpleado,apellidoPaterno,contacto,tipo_persona,rfc,edad,estado,municipio,calle,num_ext,num_int,colonia,cp,tel1,tel2,operador,estatus,transport,num_lic,fecha_v,nom_emergencia)
                            VALUES
                        ('$nombre','$apellido','$telefono','5','$rfc','$edad','$estado','$ciudad','$domicilio','$numero_ext','$numero_int','$colonia','$codigopostal','$tel1','$tel2','1','1','12','$licencia','$fecha','$nom_emer');"; 

                $myQuery = "INSERT INTO nomi_empleados (fechaAlta,apellidoPaterno,nombreEmpleado,idestado,idmunicipio,rfc,direccion,cp,telefono,activo,estatus_tran)
                            VALUES
                            ('$fecha','$apellido','$nombre','$estado','$ciudad','$rfc','$domicilio','$codigopostal','$telefono',1,6,'DISPONIBLE');";


            if($this->multi_query($myQuery)){
                $registro = 1;
               }
            if ($registro == 1)
            {
                echo 1;
            }else{
                echo "Registro Falido!!";
            }

         } 

        function SavedNewunidad ($numero_ec,$placas,$modelo,$anio,$color,$tanque,
    $observaciones,$fecha,$tipo,$capacidad,$marca,$tipogas)
        {
            $registro = 0;
            $myQuery = "INSERT INTO tran_unidades (no_economico,placas,idmarca,modelo,anio,color,idtipounidad,idtipocombustible,tanque_tamano,idcapacidadunidad,fecha_adquisicion,observaciones,estatus) 
                          VALUES
                            ('$numero_ec','$placas','$marca','$modelo','$anio','$color','$tipo','$tipogas','$tanque','$capacidad','$fecha','$observaciones',1);";

            if($this->query($myQuery)){
            $registro = 1;
           }
            if ($registro == 1)
            {
              echo 1;   
            }else{
            // echo "Registro fallido";   

                            }
        }

        function SavedNewcaja($numero,$placas,$ejes,$color,$obs,$fecha,$tipo)
        {
            $registro = 0;
            $myQuery = "INSERT INTO tran_cajatractor (no_economico,placas,tipocaja,numero_ejes,color,observaciones,estatus,fecha)
                        VALUES                       ('$numero','$placas','$tipo','$ejes','$color','$obs',1,'$fecha');";


            if($this->query($myQuery)){
            $registro = 1;
           }
            if ($registro == 1)
            {
              echo 1;   
            }else{
            // echo "Registro fallido";   

                            }
        }


        //////////////ORDEN DE SERVICIO/////////////
        function listarOS(){

            $myQuery = "SELECT b.idsolicitud, a.idordenservicio, a.fecha, concat(c.nombreEmpleado, ' ', c.apellidoPaterno) operador, d.no_economico ne, e.nombre cliente, f.entrega_en entrega, b.viaje, g.municipio, h.estado, i.capacidad, b.temperatura  , j.id , j.nombretienda tienda_des,d.estatus estatusUni,c.estatus_tran estatusEmpleado,k.estatus estatusCaja,c.idEmpleado,k.idcajatractor,d.idunidad from tran_ordenservicio a
                left join tran_solicitud b on b.idsolicitud = a.idsolicitud
                left join nomi_empleados c on c.idEmpleado = a.idEmpleado
                left join tran_unidades d on d.idunidad = a.idunidad
                left join comun_cliente e on e.id = b.id
                left join tran_datosentrega f on f.iddatosentrega = b.iddatosentrega
                left join municipios g on g.idmunicipio = f.idmunicipio
                left join estados h on h.idestado = f.idestado
                left join tran_capacidadunidad i on i.idcapacidadunidad = d.idcapacidadunidad
                left join comun_destinatario j on j.id = b.id_destinatario
                left join tran_cajatractor k on k.idcajatractor = a.idcajatractor;";
            $os = $this->queryArray($myQuery);
            return $os["rows"];
        }
        function listarOS1($idordenservicio){
            $myQuery = "SELECT a.carta_porte FROM tran_ordenservicio a where a.idordenservicio = '$idordenservicio';";
            $os1 = $this->queryArray($myQuery);
            return $os1["rows"];
        }
        function listarFormaspago($idordenservicio){

            $myQuery = "SELECT  a.idFormapago, a.nombre FROM forma_pago a;";
            $listFormapago = $this->queryArray($myQuery);
            return $listFormapago["rows"];

        }
        function listarGastoX($idordenservicio){

            $myQuery = "SELECT idgastoextra , clave , monto , observaciones , cobro_cliente , idordenservicio, iddesccorta
                        FROM tran_gastoextra  where idordenservicio = '$idordenservicio' and estatus = 1;";
            $listgastox = $this->queryArray($myQuery);
            return $listgastox["rows"];

        }
        function listarAnticipos($idordenservicio){

            $myQuery = "SELECT a.idanticipo, a.fecha_captura, CONCAT(b.apellidoPaterno, ' ', b.nombreEmpleado) operador, c.nombre, a.importe FROM tran_anticipo a 
                        left join nomi_empleados b on b.idEmpleado = a.idEmpleado
                        left join forma_pago c on c.idFormapago = a.idFormapago
                        where a.idordenservicio = '$idordenservicio' and a.estatus = 1;";
            $listgastox = $this->queryArray($myQuery);
            return $listgastox["rows"];

        }
        function listarBitacora($idordenservicio){

            $myQuery = "SELECT a.fecha , a.idordenservicio , concat(c.marca ,' - ', b.modelo , ' - ', d.capacidad ) marca , b.anio , b.placas , b.no_economico , concat(e.nombreEmpleado,' ',e.apellidoMaterno,' ',e.apellidoPaterno) nombreEmpleadoB , concat(h.municipio,' , ',i.estado) carga , concat(hm.municipio,' , ', he.estado) entrega,k.numerolicencia,k.tipolicencia,k.vigencia
                        from tran_ordenservicio a
                        left join tran_unidades b on b.idunidad = a.idunidad
                        left join tran_marca c on c.idmarca = b.idmarca
                        left join tran_capacidadunidad d on d.idcapacidadunidad = b.idcapacidadunidad
                        left join nomi_empleados e on e.idEmpleado = a.idEmpleado
                        left join tran_solicitud f on f.idsolicitud = a.idsolicitud
                        left join tran_datoscarga g on g.iddatoscarga = f.iddatoscarga
                        left join municipios h on h.idmunicipio = g.idmunicipio
                        left join estados i on i.idestado = g.idestado
                        left join tran_datosentrega j on j.iddatosentrega = f.iddatosentrega
                        left join municipios hm on hm.idmunicipio = j.idmunicipio
                        left join estados he on he.idestado = j.idestado
                        LEFT JOIN tran_licencias k on k.idEmpleado = e.idEmpleado
                        where a.idordenservicio = '$idordenservicio';";
            $listbitacora = $this->queryArray($myQuery);
            return $listbitacora["rows"];

        }
        function listarCuenta(){

            $myQuery = "SELECT a.idbancaria, CONCAT(a.cuenta,' - ', b.nombre) cuenta  FROM bco_cuentas_bancarias a
                        left join cont_bancos b on b.idbanco = a.idbanco;";
            $listcuenta = $this->queryArray($myQuery);
            return $listcuenta["rows"];

        }
        function listarAsignacion($idordenservicio){
            $myQuery = "SELECT a.fecha, a.idEmpleado, a.idunidad, a.idcajatractor, a.no_logistica , b.idsolicitud , b.id , b.id_destinatario from tran_ordenservicio a 
                        left join tran_solicitud b on b.idsolicitud = a.idsolicitud
                        where a.idordenservicio = '$idordenservicio';";
            $listasignacion = $this->queryArray($myQuery);
            return $listasignacion["rows"];
        }

        function listarCartaPorte($idordenservicio){

            $myQuery = "SELECT c.iddatoscarga, e.nombretienda remitente, f.rfc, f.idEmpleado, 
                CONCAT(e.direccion, ' - ', e.colonia, ' - ', e.celular) domicilio, 
                CONCAT(h.municipio, ', ', i.estado) origen, 
                CONCAT(c.calle, ' - ', c.referencia, ' - ', c.colonia, ' - ', c.telefono) recoger, 
                CONCAT(j.municipio, ' ', k.estado) destino, d.entrega_en, 
                d.preguntar_por destinatario, 
                CONCAT(d.calle, ' - ', d.referencia, ' - ', d.colonia, ' - ', d.telefono) domicilioe, d.entrega_en,
                CONCAT(f.nombreEmpleado, ' ', f.apellidoPaterno, ' ', f.apellidoMaterno) operador, 
                l.no_economico, l.placas, a.idsolicitud, n.nombre condicionpago, o.valor_unitario, o.valor_declarado, o.idcartaporte
                FROM tran_ordenservicio a
                left join tran_solicitud b on b.idsolicitud = a.idsolicitud
                left join tran_datoscarga c on c.iddatoscarga = b.iddatoscarga
                left join tran_datosentrega d on d.iddatosentrega = b.iddatosentrega
                left join comun_cliente e on e.id = b.id
                left join nomi_empleados f on f.idEmpleado = a.idEmpleado
                left join municipios g on g.idmunicipio = e.idMunicipio
                left join municipios h on h.idmunicipio = c.idMunicipio
                left join estados i on i.idestado = c.idestado
                left join municipios j on j.idmunicipio = d.idMunicipio
                left join estados k on k.idestado = d.idestado
                left join tran_unidades l on l.idunidad = a.idunidad
                left join tran_convenio m on m.idsolicitud = b.idsolicitud
                left join app_tipo_credito n on n.id = e.id_tipo_credito
                left join tran_cartaporte o on o.idordenservicio = a.idordenservicio
                where a.idordenservicio = '$idordenservicio';";
           
            $listcarta = $this->queryArray($myQuery);
            return $listcarta["rows"];

        }
        function savedGastoExtra($idOS,$desc,$desccorta,$clave,$monto,$obser,$cobro){
            
            $registro = 0;
            $myQuery = "INSERT INTO tran_gastoextra (idordenservicio,descripcion,iddesccorta,clave,monto,observaciones,cobro_cliente) 
            VALUES ('$idOS','$desc','$desccorta','$clave','$monto','$obser','$cobro');";
            if($this->query($myQuery)){
                $registro = 1;
            }
            if ($registro == 1)
            {
                echo 1;
            }else{
                echo "Registro Falido!!";
            }
        }
        function savedAnticipo($idOS,$fecha,$operador,$formapago,$cuenta,$referencia,$importe,$estatus){
            
            $registro = 0;
            $myQuery = "INSERT INTO tran_anticipo (idordenservicio,fecha_captura,idEmpleado,idFormapago,idbancaria,referencia,importe,estatus) 
            VALUES ('$idOS','$fecha','$operador','$formapago','$cuenta','$referencia','$importe',$estatus);";
            if($this->query($myQuery)){
                $registro = 1;
            }
            if ($registro == 1)
            {
                echo 1;
            }else{
                echo "Registro Falido!!";
            }
        }
        function savedAyudante($idOS,$operador,$concepto,$monto,$observ){
            $registro = 0;
            $myQuery = "INSERT INTO tran_ayudante (idordenservicio,idEmpleado,iddesccorta,monto,observaciones) 
            VALUES ('$idOS','$operador','$concepto','$monto','$observ');";
            if($this->query($myQuery)){
                $registro = 1;
            }
            if ($registro == 1)
            {
                echo 1;
            }else{
                echo "Registro Falido!!";
            }
        }
        
/*
        function listarDescCorta(){

            $myQuery = "SELECT a.iddesccorta, a.concepto from tran_desccorta a;";
            $desccorta = $this->queryArray($myQuery);
            return $desccorta["rows"];

        }
*/
        //// AVANZADAS
        function listarConInc($idsolicitud){

           $myQuery = "SELECT a.idsolicitud folio, b.incluir 
                            from tran_solicitud a 
                            LEFT join tran_relacion_sol_con b on b.idsolicitud = a.idsolicitud 
                            where b.incluir = 1 and b.idsolicitud = '$idsolicitud'; ";
            $list = $this->queryArray($myQuery);
            return $list["rows"];

        }
        function lastIdConM($id,$folio,$idsolicitud,$incluir){
            if($incluir == 1){
           $myQuery = "SELECT DISTINCT a.idconvenio , a.id,b.nombretienda , a.descripcion , a.precio_cliente , a.precio_proveedor , c.incluir , a.idsolicitud, a.cantidad ,d.concepto
           FROM tran_convenio a
           left join comun_cliente b on b.id = a.id
           left join tran_relacion_sol_con c on c.idconvenio = a.idconvenio
           left join tran_desccorta d on d.iddesccorta = a.iddesccorta
           where a.id = '$id';";
            }else{
           $myQuery = "SELECT DISTINCT a.idconvenio , a.id,b.nombretienda , a.descripcion , a.precio_cliente , a.precio_proveedor , c.incluir , a.idsolicitud, a.cantidad ,d.concepto
           FROM tran_convenio a
           left join comun_cliente b on b.id = a.id
           left join tran_relacion_sol_con c on c.idconvenio = a.idconvenio
           left join tran_desccorta d on d.iddesccorta = a.iddesccorta
           where a.id = '$id';";
       }
            $id = $this->queryArray($myQuery);
            return $id["rows"];
        

        }

        function convagreeM($id,$folio,$idsolicitud,$incluir){
        $myQuery = "SELECT DISTINCT a.idconvenio , a.idcliente , a.idsolicitud, b.nombretienda , c.descripcion , c.cantidad , a.incluir,c.precio_cliente,d.concepto
                    from tran_relacion_sol_con a
                            left join tran_convenio c on c.idconvenio = a.idconvenio
                            left join comun_cliente b on b.id = a.idcliente
                            left join tran_desccorta d on d.iddesccorta = c.iddesccorta
                    WHERE a.idsolicitud = '$folio' and a.incluir = '1';";

           $id = $this->queryArray($myQuery);
            return $id["rows"];

        }
        
        function ligarConSolM($idconvenio,$idsolicitud)
        {
            $registro = 0;
            /*
            $myQuery = "UPDATE tran_convenio SET
                        tran_convenio.idsolicitud = '$idsolicitud'
                        where tran_convenio.idconvenio = '$idconvenio';";
           */
        $myQuery = "INSERT INTO tran_relacion_sol_con (idsolicitud,idconvenio,cantidad,incluir) 
                    VALUES ('$idsolicitud','$idconvenio',1,1);";
           
           if($this->query($myQuery)){
            $registro = 1;
           }
            if ($registro == 1)
            {
                echo 1;
            }else{
                echo "Registro Falido!!";
            }
        }
        function editedConvenioCant($idsolicitud,$idconvenio,$cantidad)
        {
            $registro = 0;
            $myQuery = "UPDATE tran_convenio SET
                        tran_convenio.cantidad = '$cantidad'
                        where tran_convenio.idconvenio = '$idconvenio' and tran_convenio.idsolicitud = '$idsolicitud';";
           
           if($this->query($myQuery)){
            $registro = 1;
           }
            if ($registro == 1)
            {
                echo 1;
            }else{
                echo "Registro Falido!!";
            }
        }


function editedConvenioInc($idsolicitud,$idconvenio,$idcliente,$cantidad)
    {
        $registro = 0;
        echo $myQuery = "INSERT INTO tran_relacion_sol_con (idsolicitud,idconvenio,incluir,idcliente,cantidad)
                                              VALUES  ('$idsolicitud','$idconvenio','1','$idcliente','$cantidad');";

            if($this->query($myQuery))
            {
              $registro = 1;
            }
            if($registro == 1)
            {
              print_r($myQuery);   
            }
            else
            {
              print_r($myQuery);
            }
    }

function editedConvenioNO($idsolicitud,$idconvenio,$incluir,$idcliente)
        {
        $registro = 0;
         echo  $myQuery = "UPDATE tran_relacion_sol_con SET tran_relacion_sol_con.incluir = '0' 
                            WHERE tran_relacion_sol_con.idconvenio = '$idconvenio' and tran_relacion_sol_con.idsolicitud = '$idsolicitud';";
         
           
           if($this->query($myQuery)){
            $registro = 1;
           }
            if ($registro == 1)
            {
              print_r($incluir);   
            }else{
               print_r("False");
            }
        }


        function savedAsignacion($fechaA,$idope,$iduni,$idcaja,$idsolicitud,$estaus_operador)
        {

            $registro = 1;
            $myQuery = "INSERT INTO tran_ordenservicio (fecha, idEmpleado, idunidad,idcajatractor,idsolicitud,estatus) VALUES 
                                                             ('$fechaA','$idope','$iduni','$idcaja','$idsolicitud','2');";

            $myQuery .= "UPDATE tran_unidades SET
                        tran_unidades.estatus = 'ASIGNADO'
                        where tran_unidades.idunidad = '$iduni';";

            $myQuery .="UPDATE tran_cajatractor SET
                        tran_cajatractor.estatus = 'ASIGNADO'
                        where tran_cajatractor.idcajatractor = '$idcaja';";

            $myQuery .="UPDATE tran_solicitud SET
                        tran_solicitud.estatus = '2'
                        where tran_solicitud.idsolicitud = '$idsolicitud';";

            $myQuery .="UPDATE nomi_empleados SET
                        nomi_empleados.estatus_tran = 'ASIGNADO'
                        where nomi_empleados.idEmpleado = '$idope';";
        
        
           if($this->multi_query($myQuery)){
            $registro = 1;
           }
            if ($registro == 1)
            {
                echo $myQuery;
            }else{
                echo "Registro Falido!!";
            }
        
        }
        function editedRelSolCon($query){
            
            $registro = 0;
            if($this->multi_query($query)){
                $registro = 1;
            }
            if ($registro == 1)
            {
                echo 1;
            }else{
               // echo "Registro Falido!!";
            }


        }

        function cargaConvenioNewM($idsolicitud,$lastid){
                        $myQuery = "SELECT a.idsolicitud , a.id , b.idestado , c.estado, a.idcapacidadunidad ,a.temperatura , 
                                    from tran_solicitud a
                                    left join tran_datoscarga b on b.iddatoscarga = a.iddatoscarga
                                    left join estados c on c.idestado = b.idestado
                                    where a.idsolicitud = '$idsolicitud';";
                        $carga = $this->queryArray($myQuery);
                        return $carga["rows"];
        }


        function savedCartaPorte($idordenservicio,$fecha,$valoruni,$valordec,$condiciones)
        {
            $registro = 0;
            $myQuery = "INSERT INTO tran_cartaporte (idordenservicio,fecha,valor_unitario,valor_declarado,condiciones_pago) VALUES 
                                                        ('$idordenservicio','$fecha','$valoruni','$valordec','$condiciones');";
            
            $myQuery .= "UPDATE  tran_ordenservicio SET
                        tran_ordenservicio.carta_porte = 'SI'
                        where tran_ordenservicio.idordenservicio = '$idordenservicio';";

            //echo $myQuery;

            if($this->multi_query($myQuery)){
                $registro = 1;
               }
            if ($registro == 1)
            {
                echo 1;
            }else{
                echo "Registro Falido!!";
            }
            
                
        }


        function table_licM()
        {
            $myQuery = "SELECT a.idEmpleado, concat(b.nombreEmpleado,' ',b.apellidoPaterno,' ',b.apellidoMaterno) nombre , a.tipolicencia , a.numerolicencia , a.vigencia , b.estatus_tran,b.codigo
            FROM tran_licencias a
            left join nomi_empleados b on b.idEmpleado = a.idEmpleado;";

            $id = $this->queryArray($myQuery);
            return $id["rows"];

        }

        function listconduct()
        {
            $myQuery="SELECT idEmpleado,concat(nombreEmpleado,' ',apellidoPaterno,' ',apellidoMaterno)nombre , estatus_tran , codigo
            from nomi_empleados
            where id_clasificacion = 6;";

            $id = $this->queryArray($myQuery);
            return $id["rows"];

        }


        function list2conduct($idchofer){
            $myQuery="SELECT idEmpleado,concat(nombreEmpleado,' ',apellidoPaterno,' ',apellidoMaterno)nombre , estatus_tran , codigo
            from nomi_empleados
            where idEmpleado = '$idchofer';";

            $id = $this->queryArray($myQuery);
            return $id["rows"];
        }

        function savedLic($chofer,$licencia,$tipoLic,$vigencia){
            $registro = 0;
            $myQuery="INSERT INTO tran_licencias (idEmpleado,tipolicencia,numerolicencia,vigencia)
                                      VALUES     ('$chofer','$tipoLic','$licencia','$vigencia');";
         
           
           if($this->query($myQuery)){
            $registro = 1;
           }
            if ($registro == 1)
            {
            echo ("Registro Exitoso!!");   
            }else{
               print_r("Fallo Registro");
            }
        }

        function listarant(){
                    $myQuery="SELECT a.idanticipo , a.idordenservicio , a.fecha_captura , a.idEmpleado,concat(d.nombreEmpleado ,' ',d.apellidoPaterno,' ',d.apellidoMaterno) nombre , a.idFormapago, c.nombre nombrepago , a.idbancaria , a.referencia , a.importe , a.estatus estatus1 , b.estatus estatus2  
from tran_anticipo a
left join tran_estatusanticipo b on b.idestatusanticipo = a.estatus
left join forma_pago c on c.idFormapago = a.idFormaPago
left join nomi_empleados d on d.idEmpleado = a.idEmpleado
WHERE a.estatus = 1;";
                    $id = $this->queryArray($myQuery);
                    return $id["rows"];
        }

        function aprobado_ant($idanticipo,$idordenservicio){
             $registro = 0;
            $myQuery = "UPDATE tran_anticipo SET
                        tran_anticipo.estatus = 2
                        where tran_anticipo.idanticipo = '$idanticipo' and tran_anticipo.idordenservicio = '$idordenservicio';";
           
           if($this->query($myQuery)){
            $registro = 1;
           }
            if ($registro == 1)
            {
                echo 1;
            }else{
                echo "Registro Falido!!";
            }

        }


        function rechazado_ant($idanticipo,$idordenservicio){
             $registro = 0;
            $myQuery = "UPDATE tran_anticipo SET
                        tran_anticipo.estatus = 3
                  where tran_anticipo.idanticipo = '$idanticipo' and tran_anticipo.idordenservicio = '$idordenservicio';";
           
           if($this->query($myQuery)){
            $registro = 1;
           }
            if ($registro == 1)
            {
                echo 1;
            }else{
                echo "Registro Falido!!";
            }

        }




////////////////////////////////////INICIO PORCENTAJES OPERADORES///////////////////////////////////////////






////////////////////////////////////FIN PORCENTAJES OPERADORES///////////////////////////////////////////


/////////////////////////////////// INICIO ALTA DE UNIDADES ////////////////////////////////////////////////////////////////////////////////

function listarunidadesalta(){
    $myQuery = "SELECT a.idunidad , a.no_economico , a.placas , b.marca , a.modelo , a.anio , a.color , c.combustible FROM tran_unidades a 
                left join tran_marca b on b.idmarca = a.idmarca
                left join tran_tipocombustible c on c.idtipocombustible = a.idtipocombustible;";

    $id = $this->queryArray($myQuery);
            return $id["rows"];
}

function infoUniList($idunidad){
        $myQuery = "SELECT a.idunidad , a.no_economico , a.placas , b.marca,a.idmarca , a.modelo , a.anio , a.color , c.combustible , a.idtipocombustible , a.tanque_tamano , a.tanque_rendimiento_foraneo , a.tanque_rendimiento_local , a.tanque_tamano_thermo , a.tanque_rendimiento_foraneo_thermo , a.tanque_rendimiento_local_thermo , d.capacidad,a.idcapacidadunidad , e.tipo, a.tipo_unidad , a.fecha_adquisicion,  a.observaciones , a.estatus , a.kmadquisicion , a.kmtotal,a.refrigerado  FROM tran_unidades a 
                left join tran_marca b on b.idmarca = a.idmarca
                left join tran_tipocombustible c on c.idtipocombustible = a.idtipocombustible
                left join tran_capacidadunidad d on d.idcapacidadunidad = a.idcapacidadunidad
                left join tran_tipounidad e on e.idtipounidad = a.tipo_unidad
                where a.idunidad = '$idunidad' ;";
    $idunidad2 = $this->queryArray($myQuery);
                    return $idunidad2["rows"];
                    
}

function saved_addUni($refrigerado,$no_economico,$marca,$anio,$placas,$color,$tipo,$capacidad,$tipocomb,$tamanotanUni,$rendforUni,$rendlocUni,$tamtanqthem,$rendthermfor,$rendthermloc,$fechaaddUni,$kmadquisicion,$kmtotal,$observaciones,$modelo){
         $registro = 0;

     echo $myQuery = "INSERT INTO tran_unidades (no_economico,placas,idmarca,modelo,anio,color,idtipounidad,idtipocombustible,tanque_tamano,tanque_rendimiento_foraneo,tanque_rendimiento_local,tanque_tamano_thermo,tanque_rendimiento_foraneo_thermo,tanque_rendimiento_local_thermo,idcapacidadunidad,tipo_unidad,fecha_adquisicion,observaciones,estatus,kmadquisicion,kmtotal,refrigerado)
            VALUES('$no_economico','$placas','$marca','$modelo','$anio','$color','$tipo','$tipocomb','$tamanotanUni','$rendforUni','$rendlocUni','$tamtanqthem','$rendthermfor','$rendthermloc','$capacidad','$tipo','$fechaaddUni','$observaciones','1','$kmadquisicion','$kmtotal','$refrigerado');";

           if($this->query($myQuery)){
            $registro = 1;
           }
            if ($registro == 1)
            {
            echo $myQuery;
            echo ("Registro Exitoso!!");   

            }else{
               print_r("Fallo Registro");
               echo $myQuery;
            }


}


function listado_select_marca(){
        $myQuery = "SELECT idmarca , marca FROM tran_marca ;";
            $selects = $this->queryArray($myQuery);
                    return $selects["rows"];


}

function listado_select_tipouni(){
        $myQuery = "SELECT idtipounidad , tipo FROM tran_tipounidad ;";
            $selects = $this->queryArray($myQuery);
                    return $selects["rows"];


}

function listado_select_capuni(){
        $myQuery = "SELECT idcapacidadunidad , capacidad FROM tran_capacidadunidad ;";
            $selects = $this->queryArray($myQuery);
                    return $selects["rows"];


}

function listado_select_tipocomb(){
        $myQuery = "SELECT idtipocombustible , combustible FROM tran_tipocombustible ;";
            $selects = $this->queryArray($myQuery);
                    return $selects["rows"];


}







}


?>


