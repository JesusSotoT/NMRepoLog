<?php
class Consult
{
	
  	var $myServer = 'nmdb.cyv2immv1rf9.us-west-2.rds.amazonaws.com';
	var $userServ   = 'nmdevel';
	var $keyServ   = 'nmdevel';
	var $bdc   = 'nmdev';
  

	function conection($servidor,$usuariobd,$clavebd,$bd)
	{

		if(!($conection = new mysqli($servidor, $usuariobd, $clavebd, $bd)))
		{
			die("Error en la conexion");
		}else{
			return $conection;
		}
	}
	
	function crear_articulo($conection,$titulo,$contenido,$bd)
	{
		session_start();
		$idorg=$_SESSION["accelog_idorganizacion"];
		$fecha=date('Y-m-d H:i:s');
		$var = $conection->query("INSERT INTO hb_articulos (titulo,contenido,imagen,fecha,organizacion,rfc,bd) VALUES ('$titulo','$contenido','','$fecha','$idorg','','$bd');");
    return $var;
	}

	function crear_comentario($conection,$coment,$id,$bd)
	{

		$fecha=date('Y-m-d H:i:s');
		$var = $conection->query("INSERT INTO hb_articulos_comentarios (comentario,fecha,nombre,rfc,bd,id_articulo) VALUES ('$coment','$fecha','','','$bd','$id');");
    return $var;
	}

	function desplegar_articulos($conection,$id)
	{
		$var = $conection->query("SELECT * FROM hb_articulos WHERE id_articulo='$id' ORDER BY id_articulo DESC;");
    return $var;
	}

	function desplegar_articulos_comentarios($conection,$id)
	{
		$conection->query('SET NAMES utf8');
		$var = $conection->query("SELECT * FROM hb_articulos_comentarios WHERE id_articulo='$id' order by id_comentario desc;");
    return $var;
	}
	

	function smsOfertaContesto($conection,$idoferta)
	{
		$var = $conection->query("select * from sms_oferta_cliente where idOferta=".$idoferta." and oc.contesto=1 and estatus=0 and idOrdenCompra is not null  and id NOT IN(select DISTINCT(idOfertacliente) from sms_ruta_oferta_cliente)");
    return $var;
	}

	function verificaDisponibilidad($conection,$id_producto,$cantidad,$id_empleado)
	{

		$var = $conection->query("SELECT b.idSuc, a.*, FLOOR((a.cantidad-a.ocupados)/".$cantidad.") total FROM mrp_stock a
	INNER JOIN administracion_usuarios b ON b.idempleado='$id_empleado'
	INNER JOIN mrp_sucursal c ON c.idSuc=b.idSuc
 where FLOOR((a.cantidad-a.ocupados)/".$cantidad.")>0 AND a.idProducto='$id_producto' AND a.idAlmacen=c.idAlmacen;");


    return $var;
	}
	
	function smsOfertaClient($conection,$idClient,$idoferta)
	{
		$var = $conection->query("SELECT distinct(id) FROM sms_oferta_cliente WHERE idCliente=".$idClient." and idOferta=".$idoferta);
    return $var;
	}
	
	function consulruta($conection,$namerut)
	{
		$var = $conection->query("SELECT * FROM sms_ruta_oferta WHERE nombre='".$namerut."'");
    return $var;
	}

	function articulos($conection)
	{
		$conection->query('SET NAMES utf8');
		$var = $conection->query("SELECT * FROM hb_articulos ORDER by id_articulo DESC");
    return $var;
	}
	
	function smsOfertaClientconf($conection,$idofer)
	{
		$var = $conection->query("select * from sms_oferta_cliente where idOferta=".$idofer." and id NOT IN(select DISTINCT(idOfertacliente) from sms_ruta_oferta_cliente)");
    return $var;
	}
	
	function rutaofertaclient($conection,$idruta)
	{
		$var = $conection->query("select * from sms_ruta_oferta_cliente where idRutaOferta=".$idruta);
    return $var;
	}
	
	function smsOferta($conection,$idoferta)
	{
		$var = $conection->query("SELECT * FROM sms_oferta WHERE idOferta=".$idoferta);
    return $var;
	}

	function mrproducto($conection,$idproducto)
	{
		$var = $conection->query("SELECT * FROM mrp_producto WHERE idProducto=".$idproducto);
    return $var;
	}
	
	function mrpunidad($conection,$idunidad)
	{
		$var = $conection->query("SELECT * FROM mrp_unidades WHERE idUni=".$idunidad);
    return $var;
	}
	
	function clientes($conection,$idclient)
	{
		$var = $conection->query("SELECT * FROM comun_cliente WHERE id=".$idclient);
    return $var;
	}

	function clientes2($conection)
	{
		$var = $conection->query("SELECT id, nombre FROM comun_cliente;");
    return $var;
	}

	function invitados($conection)
	{
		$conection->query('SET NAMES utf8');
		$var = $conection->query("SELECT a.id, a.estatus, a.fecha_invitacion, b.nombre, b.nombretienda FROM sms_invitados a INNER JOIN nmdev_common.comun_cliente b on b.id=a.id_cliente_dev  order by a.id desc;");
    return $var;
	}

	function busquedaSMS($conection,$q)
	{
		$conection->query('SET NAMES utf8');
		$var = $conection->query($q);
    return $var;
	}

	function asignar_grupo($conection,$c,$g)
	{	
		$clientes=explode(',', $c);
		$grupos=explode(',', $g);
		$conection->query("DELETE FROM sms_cliente_grupo WHERE id_cliente in(".$c.") AND id>0");
		foreach ($clientes as $keyc => $valuec) {
			foreach ($grupos as $keyg => $valueg) {
				if($valueg!=0 && $valuec!=0){
					$conection->query("INSERT INTO sms_cliente_grupo (id_cliente, id_grupo) VALUES ('".$valuec."','".$valueg."')");
				}
			}
		}
	}

	function invitar_cliente($conection,$c,$bd)
	{	
		$clientes=explode(',', $c);
		$fecha=date('Y-m-d H:i:s');
		$insert='';
		foreach ($clientes as $keyc => $valuec) {
		    $cadena="idi=".$valuec.'&b='.$bd;
            $cadenc=base64_encode($cadena);
            
            $otrophp=preg_replace('/smsAjax./', 'funcionesBD/offer3.', $_SERVER[REQUEST_URI]);
            //$otrophp=$_SERVER[REQUEST_URI];
            $url="http://$_SERVER[HTTP_HOST]".$otrophp.'?id='.$cadenc;

			$insert.="('".$valuec."', '".$fecha."','".$url."'),";
		}
		$insert=trim($insert,',');
		$conection->query("INSERT INTO sms_invitados (id_cliente_dev,fecha_invitacion,url) VALUES ".$insert." ");
	}
	
	function clientessql($conection,$idofer)
	{
		$var = $conection->query("select cc.id,cc.nombre,if(oc.cantidad is null,0,oc.cantidad) cantidad,cc.colonia,cc.direccion,
		cc.cp,cc.idEstado,cc.idMunicipio,cc.idGiro, cc.idRubro  
		from  sms_oferta_cliente oc, comun_cliente cc where oc.idOferta=".$idofer." 
		and cc.id=oc.idCliente  and oc.estatus=0 /* and oc.idOrdenCompra is not null */
		and oc.cantidad>0
	    and oc.id NOT IN(select DISTINCT(idOfertacliente)
	    from sms_ruta_oferta_cliente)order by cc.cp ");
    return $var;
	}
	
	function transporte($conection)
	{
		$var = $conection->query("SELECT * FROM trt_unidades");
    return $var;
	}
	
	
	
	function estado($conection,$idestado)
	{
		$var = $conection->query("SELECT * FROM estados where idestado=".$idestado);
    return $var;
	}

	function estados($conection)
	{
		$conection->query('SET NAMES utf8');
		$var = $conection->query("SELECT idestado, estado FROM estados");		
    return $var;
	}

	function grupos($conection)
	{
		$conection->query('SET NAMES utf8');
		$var = $conection->query("SELECT * FROM sms_grupos");		
    return $var;
	}

	function rubros($conection)
	{
		$conection->query('SET NAMES utf8');
		$var = $conection->query("SELECT * FROM sms_rubro;");
    return $var;
	}

	function ttienda($conection)
	{
		$conection->query('SET NAMES utf8');
		$var = $conection->query("SELECT * FROM sms_tipo_tienda;");
    return $var;
	}

	function codigosp($conection)
	{
		$var = $conection->query("SELECT distinct(cp) from comun_cliente;");		
    return $var;
	}
	
	function municipio($conection,$idmunicipio)
	{
		$var = $conection->query("SELECT * FROM municipios where idmunicipio=".$idmunicipio);
    return $var;
	}
	
	function giro($conection,$idgiro)
	{
		$var = $conection->query("SELECT * FROM sms_giro where idGiro=".$idgiro);
    return $var;
	}
	
	function rubro($conection,$idrubro)
	{
		$var = $conection->query("SELECT * FROM sms_rubro where idRubro=".$idrubro);
    return $var;
	}
	
	function transportes($conection)
	{

		$conection->query('SET NAMES utf8');
        $var = $conection->query("SELECT a.id, c.capacidad, CONCAT(b.tipo,' / ',transporte,' / Placas: ',placas) transporte FROM sms_transporte a left join sms_tipo_unidad b on b.idtipo=a.idtipo left join sms_capacidades c on c.idcapacidad=a.idcapacidad order by b.tipo;");
    return $var;
	}
	
	function transporteseditmas($conection,$idtranselec)
	{
		$conection->query('SET NAMES utf8');
        $var = $conection->query("SELECT a.id, CONCAT(b.tipo,' / ',transporte,' / Placas: ',placas) transporte FROM sms_transporte a left join sms_tipo_unidad b on b.idtipo=a.idtipo left join sms_capacidades c on c.idcapacidad=a.idcapacidad WHERE a.id<>'$idtranselec' order by b.tipo;");
    return $var;
	}
	
	function transportesedit($conection,$idtrans)
	{
		$conection->query('SET NAMES utf8');
        $var = $conection->query("SELECT a.id, CONCAT(b.tipo,' / ',transporte,' / Placas: ',placas) transporte FROM sms_transporte a left join sms_tipo_unidad b on b.idtipo=a.idtipo left join sms_capacidades c on c.idcapacidad=a.idcapacidad WHERE a.id='$idtrans' order by b.tipo;");
    return $var;
	}
	
	
	
	//select u.id, concat( ut.tipo," ",um.marca," ", cu.capacidad," Placas:",u.placas) transporte  from  trt_unidades u ,trt_unidad_tipo ut,trt_unidad_marca um,trt_capacidad_unidad cu
//where ut.id=u.id_tipo and um.id=u.id_marca and cu.id=u.id_capacidad 
}
?>