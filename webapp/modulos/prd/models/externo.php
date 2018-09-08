<?php 

	require("models/connection_sqli.php"); // funciones mySQLi
	session_start();
	class externoModel extends Connection{
///////////////// ******** ---- 	listar_pedidos		------ ************ //////////////////
	//////// Optiene los pedidos de la comanda y los regresa en un array
			// Como parametros puede recibir:
				// 	mesa -> ID de la mesa
				
		function listar_pedidos($objeto) {
		// Anti hack
			foreach ($objeto as $key => $value) {
				$datos[$key]=$this->escapalog($value);
			}
			
		// orden
			$orden = (!empty($datos['orden'])) ? ' ORDER BY '.$datos['orden']: ' ORDER BY a.npersona ASC, a.id ASC';
			
			$sql="	SELECT 
						d.id AS comanda, a.npersona, SUM(a.cantidad) AS cantidad, b.nombre, 
						(	SELECT ROUND(if(SUM(e.valor) is NULL,b.precioventa,
								((SUM(e.valor)/100)*b.precioventa)+b.precioventa),2) precioventa 
							FROM 
								producto_impuesto e 
							WHERE 
								e.idProducto=b.idProducto
						) precioventa, 
						a.opcionales, a.adicionales, a.normales, c.tipo, c.nombre nombreu, c.domicilio, d.codigo 
					FROM 
						com_pedidos a 
					INNER JOIN 
							mrp_producto b 
						ON 
							b.idProducto=a.idproducto 
					LEFT JOIN 
							com_comandas d 
						ON 
							d.id=(	SELECT
										c.id
									FROM
										com_comandas c
									INNER JOIN
											com_mesas m
										ON
											m.id_mesa=c.idmesa
									WHERE
										c.status=0
									AND
										m.id_mesa=".$datos['mesa']."
									LIMIT
										1
								)
					LEFT JOIN 
							com_mesas c 
						ON 
							c.id_mesa=d.idmesa 
					WHERE 
						idcomanda=(	SELECT
										c.id
									FROM
										com_comandas c
									INNER JOIN
											com_mesas m
										ON
											m.id_mesa=c.idmesa
									WHERE
										c.status=0
									AND
										m.id_mesa=".$datos['mesa']."
									LIMIT
										1
								)
					AND
						a.status!=3
					GROUP BY 
						a.npersona, a.idProducto, a.opcionales, a.adicionales ".
					$orden;
			return $sql;
			$result = $this->queryArray($sql);
			
			return $result;
		}
		
///////////////// ******** ---- 	FIN listar_pedidos		------ ************ //////////////////

///////////////// ******** ---- 	logo		------ ************ //////////////////
//** Consulta el logo de la empresa
	// Como parametro recibe:
		// id-> id de la empresa
		
		function logo($objet){
			$condicion.=(!empty($objet['id']))?' AND idorganizacion=\''.$objet['id'].'\'':'';
			
			$sql="
				SELECT 
					logoempresa as logo
				FROM 
					organizaciones
				WHERE 1=1".
					$condicion;
			$result = $this->queryArray($sql);
			
			// return $sql;
			return $result;
		}
		
///////////////// ******** ---- 	FIN logo		------ ************ //////////////////

///////////////// ******** ---- 	listar_extras		------ ************ //////////////////
//** Optoiene el nombre y el costo de los productos extra
	// Como parametro recibe:
		// adicionales-> ids de los productos extra
		
		function listar_extras($objeto){
			$sql="	SELECT 
						nombre, (	SELECT 
										ROUND(if(SUM(e.valor) is NULL,p.precioventa,
											((SUM(e.valor)/100)*p.precioventa)+p.precioventa),2) precioventa 
									FROM 
										producto_impuesto e 
									WHERE 
										e.idProducto=p.idProducto
								) costo
					FROM 
						mrp_producto p
					WHERE 
						idProducto IN(".$objeto['adicionales'].")";
			// return $sql;
			$result= $this->queryArray($sql);
			
			return $result;
		}
		
///////////////// ******** ---- 	FIN listar_extras		------ ************ //////////////////

///////////////// ******** ---- 	mostrar_propina		------ ************ //////////////////
//** Consulta si se debe de mostrar la propina o no
	// Como parametro recibe:
		
		function mostrar_propina($objeto){
			$sql="	SELECT
						propina
					FROM
						com_configuracion";
			// return $sql
	        $result = $this->queryArray($sql);
			
			return $result;
		}
		
///////////////// ******** ---- 	FIN mostrar_propina		------ ************ //////////////////
	}
?>