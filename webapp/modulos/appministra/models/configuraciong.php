<?php
//Carga la clase de coneccion con sus metodos para consultas o transacciones
//require("models/connection.php"); // funciones mySQL 
require("models/connection_sqli_manual.php"); // funciones mySQLi

class ConfiguraciongModel extends Connection
{
	
    //============================== ch@
    public function miOrganizacion(){
        $sql = "SELECT * FROM organizaciones";
        $datos = $this->queryArray($sql);
        return $datos['rows'];
    }
    public function regimen(){
        $sql = "SELECT * FROM nomi_regimenfiscal";
        $datos = $this->queryArray($sql);
        return $datos['rows'];
    }
    public function pais(){
        $sql = "SELECT * FROM paises";
        $datos = $this->queryArray($sql);
        return $datos['rows'];
    }
    public function estados(){
        $sql = "SELECT * FROM estados where idpais = 1";
        $datos = $this->queryArray($sql);
        return $datos['rows'];
    }
    public function sucursal(){
        $sql = "SELECT * FROM mrp_sucursal";
        $datos = $this->queryArray($sql);
        return $datos['rows'];
    }
    public function puestos(){
        $sql = "SELECT * FROM puestos";
        $datos = $this->queryArray($sql);
        return $datos['rows'];
    }
    public function perfil(){
        $sql = "SELECT * FROM accelog_perfiles where visible = -1";
        $datos = $this->queryArray($sql);
        return $datos['rows'];
    }
    public function clientes(){
        $sql = "SELECT id, nombre FROM comun_cliente";
        $datos = $this->queryArray($sql);
        return $datos['rows'];
    }
    
    public function municipios($idestado){
        if($idestado == 0){
            $filtro = '';
        }else{
            $filtro = ' where idestado = '.$idestado; 
        }
        $sql = "SELECT * FROM municipios $filtro";
        $datos = $this->queryArray($sql);
        return $datos['rows'];
    }

    public function relaodPU(){
        $sql = "SELECT p.idperfil, p.nombre, count(idempleado) usuarios FROM accelog_perfiles p
                left join accelog_usuarios_per up on up.idperfil = p.idperfil
                where p.visible = -1
                group by p.idperfil;";
        $datos = $this->queryArray($sql);
        return $datos['rows'];
    }

    public function relaodPU2($acceperfil){
        $intAdm = 2;
        $sql="SELECT DISTINCT(accelog_categorias.idcategoria) AS 'idcategoria', accelog_categorias.nombre AS 'categoria' 
        FROM accelog_menu 
        LEFT JOIN accelog_categorias ON accelog_menu.idcategoria = accelog_categorias.idcategoria WHERE accelog_menu.idmenu IN (SELECT idmenu FROM accelog_perfiles_me WHERE idperfil = " . $intAdm . " OR idperfil = " . $acceperfil . "  ) AND NOT accelog_categorias.idcategoria IS NULL ORDER BY accelog_categorias.orden,accelog_menu.orden;";
        $datos = $this->queryArray($sql);
        return $datos['rows'];
    }
    public function getMenus($intMenu,$intPadre,$intMult,$acceperfil){
        $intAdm = 2;
        $sql = "SELECT accelog_menu.idmenu AS 'idmenu', accelog_menu.nombre AS 'menu', accelog_menu.idmenupadre AS 'padre', accelog_menu.orden AS 'orden' 
        FROM accelog_menu 
        WHERE accelog_menu.idcategoria = " . $intMenu . " AND accelog_menu.idmenupadre = " . $intPadre . " AND accelog_menu.idmenu IN (SELECT idmenu FROM accelog_perfiles_me WHERE idperfil = " . $intAdm . " OR idperfil = " . $acceperfil . ") ORDER BY accelog_menu.orden;";
        $datos = $this->queryArray($sql);
        return $datos['rows'];
    }
    
    public function relaodAU(){
        $sql = "SELECT au.*, p.nombre perfil, pu.puesto, su.nombre sucursal, cc.nombre cliente FROM administracion_usuarios au left join accelog_perfiles p on p.idperfil = au.idperfil left join puestos = pu on pu.idpuesto = au.idpuesto left join mrp_sucursal su on su.idSuc = au.idSuc left join comun_cliente cc on cc.id = au.id;";
        $datos = $this->queryArray($sql);
        return $datos['rows'];
    } 
    public function saveMiOrg($POST){  
        
        $logo = $_POST['logoempresa'];
        $logo =str_replace("C:\\fakepath\\", "", $logo);

        $idorg = trim($_POST['idOr']);

        $sql = "UPDATE organizaciones SET                     
                    nombreorganizacion = '".trim($_POST['razon'])."', 
                    RFC = '".trim($_POST['rfc'])."',
                    idregfiscal = ".trim($_POST['regimen']).", 
                    domicilio = '".trim($_POST['domicilio'])."', 
                    idestado = ".trim($_POST['estado']).", 
                    idmunicipio = ".trim($_POST['municipio']).", 
                    cp = ".trim($_POST['cp']).", 
                    colonia = '".trim($_POST['colonia'])."', 
                    paginaweb = '".trim($_POST['web'])."', 
                    logoempresa = '".$logo."'                
                WHERE idorganizacion=".$idorg.";";
        $datos = $this->queryArray($sql);
        return $datos['rows'];
    }
    public function deletePU($idperfil){
        $sql = "UPDATE accelog_perfiles SET                     
                    visible = 0 WHERE idPerfil = '$idperfil'";
        $datos = $this->queryArray($sql);
        return $datos['rows'];
    }
    
    function fencripta($pwd, $salt) {
        $resultado = crypt($pwd, $salt);

        return $resultado;
    }
    public function user(){
        $sql = "SELECT nombreusuario from administracion_usuarios";
        $datos = $this->queryArray($sql);
        return $datos['rows'];
    }
    public function editAU($id){
        $sql = "SELECT * from administracion_usuarios where idadmin = $id;";
        $datos = $this->queryArray($sql);
        return $datos['rows'];
    }

    public function editedAU($POST,$idadmin){
  
        session_start();
        $acceperfil= $_SESSION['accelog_idperfil'];// 5        
        $accelogV = $_SESSION['accelog_variable'];
        $idorganizacion = 1;
        $auxpass = $_POST['auxpass'];
        
        $accelog_salt = "$2a$07$".$accelogV."aaaaaaa$";
        $pass = $POST['pass'];
        
        $foto = str_replace('C:fakepath', '', $_POST['fotoPerfil']);

        $sql = "UPDATE administracion_usuarios SET 
        nombre='".$_POST['nombre']."', 
        apellidos='".$_POST['apellidos']."', 
        nombreusuario='".$_POST['nombreUser']."',
        correoelectronico='".$_POST['email']."',
        foto='".$foto."',
        idperfil='".$_POST['perfil']."',
        idempleado='".$_POST['idempleado']."',
        idorganizacion='".$idorganizacion."',
        idpuesto='".$_POST['puesto']."',
        idsuc='".$_POST['suc']."',
        id='".$_POST['cliente']."' 
        WHERE idadmin = $idadmin;";

        $result = $this->queryArray($sql);

        if($auxpass == 1){ // para actualizar el pass
            $sqlp = "UPDATE administracion_usuarios SET 
            clave='".$_POST['pass']."',
            confirmaclave='".$_POST['pass']."',
            correoelectronico='".$_POST['email']."'
            WHERE idadmin = $idadmin";
            $resultp = $this->queryArray($sqlp);

            $sql2 = "UPDATE accelog_usuarios SET 
            usuario = '".$_POST['nombreUser']."', 
            clave = '".$this->fencripta($pass, $accelog_salt)."' 
            WHERE idempleado = '".$_POST['idempleado']."'";
            $result2 = $this->queryArray($sql2);
        }

        $sql3="UPDATE accelog_usuarios_per SET
            idperfil =".$_POST['perfil']." WHERE idempleado = ".$_POST['idempleado'].";";
        $result3 = $this->queryArray($sql3);

        $sql4="UPDATE empleados SET
                    nombre ='".$_POST['nombre']."', 
                    apellido1 ='".$_POST['apellidos']."'
            WHERE idempleado = ".$_POST['idempleado'].";";
        $result4 = $this->queryArray($sql4);

        return $result2;
    }

    public function saveAU($POST){

        session_start();
        $acceperfil= $_SESSION['accelog_idperfil'];// 5        
        $accelogV = $_SESSION['accelog_variable'];
        $idorganizacion = 1;
        
        $accelog_salt = "$2a$07$".$accelogV."aaaaaaa$";
        $pass = $POST['pass'];

        $foto = str_replace('C:fakepath', '', $_POST['fotoPerfil']);

        $sql = "INSERT INTO administracion_usuarios(nombre,apellidos,nombreusuario,clave,confirmaclave,correoelectronico,foto,idperfil,idempleado,idorganizacion,idpuesto,idsuc,id) 
        VALUES('".$_POST['nombre']."','".$_POST['apellidos']."','".$_POST['nombreUser']."','".$_POST['pass']."','".$_POST['pass']."','".$_POST['email']."','".$foto."','".$_POST['perfil']."','".$idempleado."','".$idorganizacion."','".$_POST['puesto']."','".$_POST['suc']."','".$_POST['cliente']."');";
        $result = $this->queryArray($sql);
        $idadmin = $result["insertId"];

        $sql2 = "INSERT INTO empleados (nombre,apellido1,visible,administrador,idorganizacion) 
                    VALUES ('".$_POST['nombre']."','".$_POST['apellidos']."',-1,0,1) ";
        $result2 = $this->queryArray($sql2);
        $id2 = $result2["insertId"];
        //$result = $this->queryArray($sql2);

        $sql3 ="INSERT INTO accelog_usuarios (idempleado,usuario,clave) 
                    VALUES ('".$id2."','".$_POST['nombreUser']."','".$this->fencripta($pass, $accelog_salt)."') ";
         $result3 = $this->queryArray($sql3);

        //Asigna Perfiles
        //if($acceperfil==2){
        //$perfil=$acceperfil;    
        $perfil=$_POST['perfil'];    
        $sql4="INSERT INTO accelog_usuarios_per (idperfil,idempleado) 
                VALUES ('".$perfil."','".$id2."') ";
        $result4 = $this->queryArray($sql4);
        //}
        /*
            else
            {
              foreach($_POST['perfiles'] as $valor) 
            { 
                 $sqlin="INSERT INTO accelog_usuarios_per (idperfil,idempleado) 
                    VALUES ('".$valor."','".$idnewemp."') ";
                    $conexion->consultar($sqlin);
            }
            }
        */
        $usuario = $_POST['nombreUser'];
        $sql5 = "UPDATE administracion_usuarios SET idempleado='".$id2."' where nombreusuario='".$usuario."'";
        $result5 = $this->queryArray($sql5);
        return $result5;

    }
    
    public function saveAF($idempleado,$apps){

        $apps = trim($apps, ',');
        $apps2=explode(",",$apps);

        $sql = "DELETE from dashboard_contenido where idempleado=$idempleado"; 
        $this->queryArray($sql);    
        $sql2 = "INSERT INTO dashboard_contenido (idtipo,idmenu,idempleado) VALUES ";
        for($i=0;$i<count($apps2);$i++) {
            $sql2.="(1,".$apps2[$i].",$idempleado),";
          }
          $sql2=substr($sql2, 0, -1);

        $result = $this->queryArray($sql2);   
    }

    public function savePU($perfil,$strmenus){        

        $strmenus = trim($strmenus, ',');
        $strmenus2 = explode(",",$strmenus);

        $sql = "INSERT INTO accelog_perfiles (nombre,visible) VALUES ('$perfil',-1);";
        $result = $this->queryArray($sql);
        $idperfil = $result["insertId"];

        $sql2 = "INSERT INTO accelog_perfiles_me (idperfil,idmenu) VALUES";
        for($i=0;$i<count($strmenus2);$i++) {
            $sql2.="($idperfil,".$strmenus2[$i]."),";
          }
        $sql2=substr($sql2, 0, -1);
        $result = $this->queryArray($sql2);
        return $result;

    }

    public function editedPU($idperfil,$perfil,$strmenus){
        
        $strmenus = trim($strmenus, ',');
        $strmenus2 = explode(",",$strmenus);

        $sql = '';
        $sql .= "DELETE FROM accelog_perfiles_me WHERE idperfil = $idperfil;";
        //$result = $this->queryArray($sql);

        $sql2 = "INSERT INTO accelog_perfiles_me (idperfil,idmenu) VALUES";
        for($i=0;$i<count($strmenus2);$i++) {
            $sql2.="($idperfil,".$strmenus2[$i]."),";
          }
        $sql2=substr($sql2, 0, -1);

        $sql3 = "UPDATE accelog_perfiles SET nombre = '$perfil' where idperfil = $idperfil;";        
        $result3 = $this->queryArray($sql3);
        
        $sql .= $sql2.';';

        if($this->dataTransact($sql)){            
            return intval($idperfil);
        }else{
            //Deshacer todo
            return false;
        }
        //$result = $this->queryArray($sql2);
        //return $result;

    }

    public function perfiles($idPU){
        $sql = "SELECT * FROM accelog_perfiles_me WHERE idperfil = $idPU";
        $datos = $this->queryArray($sql);
        return $datos['rows'];
    }

    public function reloadB($idempleado){
        $sql = "SELECT c.*, m.idmenu, m.nombre, url from dashboard_contenido c
                left join accelog_menu m on m.idmenu = c.idmenu
                where idempleado = $idempleado;";
        $datos = $this->queryArray($sql);
        return $datos['rows'];

    }
    
    public function apps($idempleado){
       $sql = "SELECT a.idmenu, a.nombre,a.idmenupadre,a.url, ifnull((select idmenu from dashboard_contenido where idempleado='$idempleado' and idtipo=1 and idmenu=a.idmenu),-1) sel
                    FROM  accelog_menu a
                    WHERE  a.url<>'' and idmenu in (select idmenu from accelog_perfiles_me where idperfil in (select idperfil from accelog_usuarios_per where idempleado='$idempleado'));";
        $apps = $this->queryArray($sql);
        return $apps['rows'];
    }

    public function usuarios($perfil){
        $sql = "SELECT au.nombre usuario, s.nombre sucursal from accelog_usuarios_per up
                left join administracion_usuarios au on au.idempleado = up.idempleado
                left join mrp_sucursal s on s.idSuc = au.idSuc
                where up.idperfil = '$perfil';";
        $apps = $this->queryArray($sql);
        return $apps['rows'];
    }

    public function deleteAU($idadmin,$idempleado){

        // elimina de acc
        $sql = "DELETE from administracion_usuarios WHERE idadmin = '$idadmin';";
        $datos = $this->queryArray($sql);
        //return $datos['rows'];

        $sql2 = " DELETE from accelog_usuarios_per WHERE idempleado = '$idempleado';";
        $datos2 = $this->queryArray($sql2);

        $sql .= $sql2;

        $sql = "UPDATE empleados SET visible = 0 WHERE idempleado = '$idempleado';";
        $datos = $this->queryArray($sql);
        return $datos['rows'];

        //echo $sql;
        exit();

        if($this->dataTransact($sql)){            
            return intval($idperfil);
        }else{
            //Deshacer todo
            return false;
        }
        /*
            $sql = "SELECT idempleado FROM administracion_usuarios WHERE idadmin = '$idadmin';";
            $idempleado = $this->queryArray($sql);
            $idempleado = $idempleado['rows'][0]['idempleado'];

            // desactiva empleados
            $sql = "UPDATE empleados SET visible = 0 WHERE idempleado = '$idempleado';";
            $datos = $this->queryArray($sql);
            return $datos['rows'];

            // elimina de acc
            $sql = "DELETE accelog_usuarios WHERE idempleado = '$idempleado';";
            $datos = $this->queryArray($sql);
            return $datos['rows'];
        */
    }

    public function nuevoPais( $nombre ){
        $sql = "INSERT INTO paises (pais)
                VALUES  ('$nombre')";
        $result = $this->queryArray($sql);
        $idpais = $result["insertId"];
        return $idpais;
    }

    public function nuevoEstado($idpais,$estado){
        $sql = "INSERT INTO estados (estado, idpais)
                VALUES  ('$estado', '$idpais')";
        $result = $this->queryArray($sql);
        $idestado = $result["insertId"];
        return $idestado;
    }
    public function reloadEstado($idpais){
        $sql = "SELECT * from estados where idpais = $idpais";
        $datos = $this->queryArray($sql);
        return $datos['rows'];
    }
    public function nuevoMunicipio($idestado,$municipio){
        $sql = "INSERT INTO municipios (municipio, idestado)
                VALUES  ('$municipio', '$idestado')";
        $result = $this->queryArray($sql);
        $idmunicipio = $result["insertId"];
        return $idmunicipio;
    }

    public function nuevoPuesto($puesto){
        $sql = "INSERT INTO puestos (puesto)
                VALUES ('$puesto');";
        $result = $this->queryArray($sql);
        $idpuesto = $result["insertId"];
        return $idpuesto;
    }




    
    
    
    //============================== ch@

}
?>
