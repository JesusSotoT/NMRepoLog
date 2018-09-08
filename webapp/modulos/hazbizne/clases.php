<?php


// Model: Connect to server to access to cross databases
abstract class clcn {
    var $cn;
    var $_serv2="nmdb.cyv2immv1rf9.us-west-2.rds.amazonaws.com";
    var $_usu2="nmdevel";
    var $_pwd2="nmdevel";
    var $_bd2;
    var $error;

    function connect() {
        $this->cn = new mysqli($this->_serv2,$this->_usu2,$this->_pwd2,$this->_bd2);
        if($this->cn->connect_errno){
            $this->error =  $this->cn->connect_error;
            if($this->error!=""){
                error_log($this->error);
            }
        }
    }

    function disconnect(){
        mysqli_close($this->cn);
    }    
}



// Control netware database
class clnetwarstore extends clcn {
    function __construct(){
        //error_log("ENTRE_CONSTRUCTOR");
        $this->_bd2 = "netwarstore";        
        $this->connect();
    }

    function get_giro() {
        $giro = "";
		$sql = "select giro from customer where nombre_db='".$this->_bd2."' ";
	    
		$rs_giro = $this->cn->query($sql);
		while($rs_fila=$rs_giro->fetch_assoc()){
	    	$this->giro="[ ".$rs_fila["giro"]." ]";
		}
		$rs_giro->free();
    	return $giro;
    }

    function get_correo($user) {
        $correo = "";
        $sql = "select correo from customer where nombre_db='".$user."' ";
        
        $rs_correo = $this->cn->query($sql);
        while($rs_fila=$rs_correo->fetch_assoc()){
            $correo=$rs_correo["correo"];
        }
        $rs_correo->free();
        return $correo;
    }

    function get_rfc($bd) {
        $rfc = "";
        $sql = "select rfc from customer where nombre_db='".$bd."' ";
        
        
        $rs_rfc = $this->cn->query($sql);
        while($rs_fila=$rs_rfc->fetch_assoc()){
            $rfc=$rs_fila["rfc"];
        }
        $rs_rfc->free();
    
        
        return $rfc;
    }

    function get_contactos($nombre, $pagina, $invitaciones){
        
        $contactos = array();
        if($nombre=="") return $contactos;

        $record_count = 10;
        $sql
         = " 
            select id,nombre,razon,rfc,correo,nombre_db,instancia
            from customer 
            where 
                    (nombre like '%".$nombre."%' or 
                    razon like '%".$nombre."%' or
                    rfc like '%".$nombre."%' or
                    correo like '%".$nombre."%' or 
                    instancia like '%".$nombre."%')             
        ";


        if($invitaciones!=""){
            $dbs = explode("|",$invitaciones);
            $sql_invitaciones="";
            foreach($dbs as $db){
                if($db!=""){
                    if(strlen($sql_invitaciones)>0) $sql_invitaciones.=" or ";
                    $sql_invitaciones = $sql_invitaciones."nombre_db='_dbmlog".str_pad($db,10,"0",STR_PAD_LEFT)."'";
                }
            }

            $sql.=" and (".$sql_invitaciones.")";
        }


        //PAGINADO
        $sql.=" limit ".($pagina*$record_count).",".$record_count;

        //error_log($sql);
        
        $rs_contactos = $this->cn->query($sql);
        while($rs_fila = $rs_contactos->fetch_assoc()){
            $contactos[]["nombre"] = utf8_encode($rs_fila["nombre"]);
            $contactos[count($contactos)-1]["razon"] = utf8_encode($rs_fila["razon"]);
            $contactos[count($contactos)-1]["rfc"] = utf8_encode($rs_fila["rfc"]);
            $contactos[count($contactos)-1]["correo"] = utf8_encode($rs_fila["correo"]);
            $contactos[count($contactos)-1]["id"] = utf8_encode($rs_fila["id"]);
            $contactos[count($contactos)-1]["nombre_db"] = utf8_encode($rs_fila["nombre_db"]);
            $contactos[count($contactos)-1]["instancia"] = utf8_encode($rs_fila["instancia"]);
        }
        $rs_contactos->free();
        return $contactos;
    }

    function get_contactos_chat_info($contactos){
        $index = 0;
        $enlaces = array();
        foreach ($contactos as $contacto_conversacion) {
            $sql = " select correo, nombre_db, rfc from customer where nombre_db = '".$contacto_conversacion["resultado"]."' ";
            $rs_contactos = $this->cn->query($sql);
            while ($rs_fila = $rs_contactos->fetch_assoc()){
                //$enlaces[]["email"] = $rs_fila["correo"];
                $enlaces[$index]["email"] = $rs_fila["correo"];
                $enlaces[$index]["bd"] = $rs_fila["nombre_db"];
                $enlaces[$index]["rfc"] = $rs_fila["rfc"];
            }
            $rs_contactos->free();
            $index = $index + 1;
        }
        return $enlaces;
    }
}

// Control nmdev_common database
class clnmdev_common extends clcn {

    function __construct(){
        //error_log("ENTRE_CONSTRUCTOR");
        $this->_bd2 = "nmdev_common";        
        $this->connect();
        //Probando error de caracteres con acento  
        mysqli_set_charset($this->cn,"utf8");

    }

    function get_connection(){
        return $this->cn;
    }

    function get_articulos($articulos_acceso) {
        $sql_where = "";
        foreach ($articulos_acceso as $articulo){
            if($sql_where!="") $sql_where.=" or ";
            $sql_where.=" id_articulo = ".$articulo;
        }
        if($sql_where!="") $sql_where=" where ".$sql_where;

        $articulos = array();

        $sql = "select titulo, contenido, organizacion from hb_articulos ".$sql_where." order by id_articulo desc limit 3 ";
        //error_log($sql);
        $rs_articulos = $this->cn->query($sql);
        while($rs_row=$rs_articulos->fetch_assoc()){
            //error_log("ENTRE");
            $articulos[]["titulo"] = $rs_row["titulo"];
            $articulos[]["contenido"] = substr($rs_row["contenido"],0,50)."...";
            $articulos[]["organizacion"] = $rs_row["organizacion"];
        }
		$rs_articulos->free();
        return $articulos;
    }

    function contact_hazbizne($bd1,$bd2){
				//Revisa si no hay una invitación previa
				$sql = "select vinculados from hb_contacto where (bd_1='".$bd1."' and bd_2='".$bd2."') or (bd_1='".$bd2."' and bd_2='".$bd1."')";
				$rs_vinculados = $this->cn->query($sql);

				if($rs_row=$rs_vinculados->fetch_assoc()){
					if($rs_row["vinculados"]){
                        $rs_vinculados->free();
						//1 - Linked
						return 1;
					} else {
						if($rs_row["bd_1"]==$bd1){                            
                            $rs_vinculados->free();
							//2 - The user get invitation but she or he hasn't been approved yet. 
							return 2;
						} else {
                            $rs_vinculados->free();
							//4 - The user was waiting for your approbation
							$sql = "update hb_contacto set vinculados = true where (bd_1='".$bd1."' and bd_2='".$bd2."') or (bd_1='".$bd2."' and bd_2='".$bd1."')";
                            //error_log($sql);
							$this->cn->query($sql);
							return 4;	
						}
					}
				} else {
                        $rs_vinculados->free();
						//3 - Send invitation to do: hazbizne
						$sql = "insert into hb_contacto (bd_1,bd_2,vinculados) values ('".$bd1."','".$bd2."',false)";
						$this->cn->query($sql);
						return 3;
				}
    }

	function check_contactos($contactos, $bd){
        //php_log("entre a checkcontactos");
		$linea = -1;
		foreach($contactos as $contacto){
			$vinculados = 2; //Need invitation;
			$sql = "
						select vinculados, bd_1
						from hb_contacto 
						where (bd_1='".$bd."' and bd_2='".$contacto["nombre_db"]."') 
						   or (bd_1='".$contacto["nombre_db"]."' and bd_2='".$bd."') ";
			$rs_vinculados = $this->cn->query($sql);
			if($rs_row=$rs_vinculados->fetch_assoc()){
				if($rs_row["vinculados"]){
					$vinculados = 1; //Linked
				} else {
                    //error_log($rs_row["bd_1"]." --- ".$bd);
					if($rs_row["bd_1"] == $bd){
						$vinculados = 3; //Waiting for approbation
					} else {
						$vinculados = 4; //Waiting for your approbation
					}	
				}
			}
            $rs_vinculados->free();
			$linea++;
			$contactos[$linea]["vinculados"] = $vinculados; 
			$contactos[$linea]["sql"] = $sql; 
		}
		return $contactos;
	}


    function get_invitations($bd){

        $invitations = array();

        $sql = "
           select bd_1
           from hb_contacto 
           where bd_2='".$bd."' and vinculados <> true 
        ";
        //error_log($sql);

        $result = $this->cn->query($sql);
        while($row = $result->fetch_assoc()){
            $invitations[] = intval(substr($row["bd_1"],7));
        }
        $result->free();

        return $invitations;
    }

    function get_contactos_chat($bd){
        $contactos_chat = array();

        $sql = "select distinct bd_remitente as resultado from hb_conversaciones where (bd_emisor='".$bd."' or bd_remitente='".$bd."') order by bd_remitente";
        $result = $this->cn->query($sql);
        while($row = $result->fetch_assoc()){
            if ($row["resultado"] != $bd) 
                $contactos_chat[] = $row;
            
        }

        $sql = "select distinct bd_emisor as resultado from hb_conversaciones where (bd_emisor='".$bd."' or bd_remitente='".$bd."') order by bd_emisor";
        $result = $this->cn->query($sql);
        while($row = $result->fetch_assoc()){
            if (($row["resultado"] != $bd) and (!in_array($row, $contactos_chat)))
                $contactos_chat[] = $row;
        } 
        

        sort($contactos_chat);
        return $contactos_chat;

    }

    function get_conversacion($bd, $contacto){
        $conversacion = array();
        $sql = "select fecha, mensaje, bd_emisor from hb_conversaciones where (bd_emisor='".$bd."' and bd_remitente='".$contacto."') or (bd_emisor='".$contacto."' and bd_remitente='".$bd."')  order by fecha";
                
        $result = $this->cn->query($sql);
        while($row = $result->fetch_assoc()){
            $conversacion[] = $row;
        }
        
        return $conversacion;
    }

    function marcar_conversacion($bd, $contacto){
        $sql = "update hb_conversaciones set mensaje_leido = 1 WHERE bd_remitente = '".$bd."' and bd_emisor = '".$contacto."' and mensaje_leido = 0";
        $this->cn->query($sql);
    }

    function graba_conversacion($bd, $contacto, $conversacion){
        $sql = "insert into hb_conversaciones (bd_emisor, bd_remitente, mensaje) values ('".$bd."','".$contacto."','".$conversacion."')";
        $this->cn->query($sql);
        return $sql;
    }

    function graba_conversacion_busqueda($bd, $mensaje, $registro, $based){
        $sql = "insert into hb_conversaciones (rfc_remitente, mensaje, bd_emisor, bd_remitente) values ('".$registro."','".$mensaje."','".$bd."', '".$based."')";
        $this->cn->query($sql);
        return $sql;
    }

    function graba_conversacion_completa($rfc_emisor, $rfc_remitente, $mensaje, $bd_emisor, $bd_remitente){
        $sql = "insert into hb_conversaciones (rfc_emisor, rfc_remitente, mensaje, bd_emisor, bd_remitente, mensaje_leido) values ('".$rfc_emisor."','".$rfc_remitente."','".$mensaje."','".$bd_emisor."','".$bd_remitente."',0)";
        $this->cn->query($sql);
        return $sql;
    }

    function get_mensajes_no_leidos($bd, $bd_emisor){
        $sql = "select count(mensaje_leido) as total from hb_conversaciones where bd_remitente = '".$bd."' and bd_emisor = '".$bd_emisor."' and mensaje_leido = 0";
        $result = $this->cn->query($sql);
        while($row = $result->fetch_assoc()){
            $count = $row['total'];
        }
        return $count;
    }

    function revisar_mensajes($bd){
        $sql = "select count(mensaje_leido) as total from hb_conversaciones where bd_remitente = '".$bd."' and mensaje_leido = 0";
        $result = $this->cn->query($sql);
        while($row = $result->fetch_assoc()){
            $count = $row['total'];
        }
        return $count;
    }

    function get_direccion_distribuidor($param){
        $sql = "select Pais, Estado, Municipio, Colonia, CP from nmdev_common.cat_direcciones where id = '".$param."'";
        $array_direccion = array();
        $result = $this->cn->query($sql);
        while($row = $result->fetch_assoc()){
            $array_direccion[] = $row;
        }
        return $array_direccion;   
    }

}

// Control nmdev_common database
class clnmdev extends clcn {

    function __construct(){
        //error_log("ENTRE_CONSTRUCTOR");
        $this->_bd2 = "nmdev";        
        $this->connect();
    }

    function get_connection(){
        return $this->cn;
    }

    function get_zonas(){

        $zonas = array();

        $sql = "select intId, strZona from nm_zones";
        //error_log($sql);

        $result = $this->cn->query($sql);
        while($row = $result->fetch_assoc()){
            //echo $row["strZona"];
            $zonas[$row["intId"]] = $row["strZona"];
            //echo $zonas[]
        }
        $result->free();

        return $zonas;
    }
}

class clnetwarstore_p extends clcn {
    function __construct(){
        //error_log("ENTRE_CONSTRUCTOR");
        $this->_bd2 = "netwarstore_prueba";        
        $this->connect();
    }

    function get_licencias(){
        $index = 0;
        $licencias = array();
        $sql = "select count(*) as numero, salesman, appname from netwarstore_prueba.codigos inner join netwarstore_prueba.appdescrip where netwarstore_prueba.codigos.salesman = netwarstore_prueba.appdescrip.idapp and estatus = 0 group by salesman order by salesman";
    
        $result = $this->cn->query($sql);
        while($row = $result->fetch_assoc()){
            $licencias[$index]["numero"] = $row["numero"];
            $licencias[$index]["salesman"] = $row["salesman"];
            $licencias[$index]["appname"] = $row["appname"];
            $index = $index + 1;
        }
        $result->free();
        return $licencias;
    }

    function get_licencias_distintas(){
        $index = 0;
        $licencias = array();
        $sql = "select distinct salesman as licenciaId, appname from netwarstore_prueba.codigos inner join netwarstore_prueba.appdescrip where netwarstore_prueba.codigos.salesman = netwarstore_prueba.appdescrip.idapp order by salesman";
    
        $result = $this->cn->query($sql);
        while($row = $result->fetch_assoc()){
            $licencias[$index]["licenciaId"] = $row["licenciaId"];
            $licencias[$index]["appname"] = $row["appname"];
            $index = $index + 1; 
        }
        $result->free();
        return $licencias;
    }    

    function get_distribuidores($zona){
        $index = 0;
        $distros = array();
        
        $sql = "select intId, strName FROM nmdev.nm_distribuidores_licencias where intZoneId = '" . $zona . "'";

        $result = $this->cn->query($sql);
        while($row = $result->fetch_assoc()){
            $distros[$index]["intId"] = $row["intId"];
            $distros[$index]["strName"] = $row["strName"];
            $index = $index + 1;
        }
        $result->free();
        return $distros;
    }

    function get_nombre_distribuidor($id){
        $nombre = "";
        $sql = "select strName from nmdev.nm_distribuidores_licencias where intId = ". $id;

        $result = $this->cn->query($sql);
        while($row = $result->fetch_assoc()){
            $nombre = $row["strName"];
        }
     
        $result->free();
        return $nombre;   
    }

    function get_zonas(){
        $index = 0;
        $zonas = array();
        
        $sql = "select intId, strZona FROM nmdev.nm_zones";

        $result = $this->cn->query($sql);
        while($row = $result->fetch_assoc()){
            $zonas[$index]["intId"] = $row["intId"];
            $zonas[$index]["strZona"] = $row["strZona"];
            $index = $index + 1;
        }
        $result->free();
        return $zonas;
    }    

    function asignar_licencias($app, $cantidad, $distribuidor){
        $sql = "update codigos set estatus = 1, id_distribuidor = '" . $distribuidor . "', fecha_asignacion = now() where (salesman = '" . $app ."' and estatus = 0) limit " . $cantidad;
        $this->cn->query($sql);

        return $sql;
    }

    function reporte_licencias(){
        $index = 0;
        $reporte_licencias = array();

        $sql = "select count(*) as total, strName, id_distribuidor, appname, salesman from netwarstore_prueba.codigos inner join nmdev.nm_distribuidores_licencias inner join netwarstore_prueba.appdescrip where codigos.id_distribuidor = nm_distribuidores_licencias.intId and codigos.salesman = appdescrip.idapp and estatus <> 0 group by id_distribuidor, salesman";
        $result = $this->cn->query($sql);
        while($row = $result->fetch_assoc()){
            $reporte_licencias[$index]["total"] = $row["total"];
            $reporte_licencias[$index]["distribuidor"] = $row["strName"];
            $reporte_licencias[$index]["id_distribuidor"] = $row["id_distribuidor"];
            $reporte_licencias[$index]["aplicacion"] = $row["appname"];
            $reporte_licencias[$index]["salesman"] = $row["salesman"];
            $index = $index + 1;
        }
        $result->free();
        return $reporte_licencias;   
    }

    function detalle_reporte_licencias($distribuidor, $aplicacion){
        $index = 0;
        $detalle_reporte_licencias = array();

        $sql = "select codigo, fecha_asignacion from netwarstore_prueba.codigos where estatus <> 0 and id_distribuidor = '". $distribuidor . "' and salesman = '" . $aplicacion . "'";
        $result = $this->cn->query($sql);
        while($row = $result->fetch_assoc()){
            $detalle_reporte_licencias[$index]["codigo"] = $row["codigo"];
            $detalle_reporte_licencias[$index]["fecha"] = $row["fecha_asignacion"];
            $index = $index + 1;
        }
        $result->free();
        return $detalle_reporte_licencias;    
    }
}