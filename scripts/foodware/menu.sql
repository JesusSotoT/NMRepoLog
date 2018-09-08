/*Limpia la tabla de perfiles y solo deja los del master*/
DELETE FROM
	accelog_perfiles_me
WHERE
	idperfil > 1;

INSERT IGNORE INTO 
	accelog_perfiles_me (idperfil,idmenu) 
VALUES 
/*--  Scala --*/
	(2, 2347),/*Mudar instancia*/
/*-- Servicios --*/
	(2, 2156),/*Comandera*/
	(2, 2157),/*Pedidos*/
	(2, 2300),/*Monitorear Pedidos*/
	(2, 2205),/*Repartidores*/
	
/*-- Reservaciones --*/
	(2, 2158),/*Reservaciones*/
	
/*-- Recetas --*/
	(2, 2159),/*Recetas*/
	(2, 2202),/*Preparacion*/
	
/*-- Reportes --*/
		/*Inventario*/
	(2, 2319),/*-- Control de insumos*/
	(2, 2160),/*Foodware*/
	(2, 2161),/*-- Estatus comanda*/
	(2, 2162),/*-- Actividad empleado*/
	(2, 2163),/*-- Promedio por comensal*/
	(2, 2164),/*-- Comensales por mesa*/
	(2, 2165),/*-- Zonas de mayor afluencia*/
	(2, 2166),/*-- Ocupacion*/
	(2, 2190),/*-- Utilidad*/
	(2, 2167),/*-- Reservaciones*/
	(2, 2197),/*-- Producto detalle*/
	(2, 2206),/*-- Repartidores*/
	(2, 2269),/*-- Propinas*/
	
/*-- Configuracion --*/
	(2, 2194),/*Promociones*/
	(2, 2203),/*Kits*/
	(2, 2222),/*Combos*/
	(2, 2289),/*Complementos*/
	(2, 2177),/*Foodware*/
	(2, 2168),/*-- Seguridad*/
	(2, 2169),/*-- Ajustes*/
	(2, 2170),/*-- Platillos*/
	(2, 2171),/*-- Mesas*/
	(2, 2172),/*-- Empleados*/
	(2, 2173),/*-- Asignaciones*/
	(2, 2198),/*-- Menu digital*/
	(2, 2285),/*-- Editar mapa de mesas*/
	(2, 2361);/*-- Mapa repartidores*/
	

/* Appministra
=============================================================================*/

INSERT IGNORE INTO accelog_perfiles_me (idperfil,idmenu) values (2,2051),
(2,2094),
(2,2115),
(2,2153),
(2,2154),
(2,2032),
(2,2033),
(2,2106),
(2,2010),
(2,1993),
(2,2034),
(2,2049),
(2,2142),
(2,2143),
(2,2144),
(2,2102),
(2,2108),
(2,2036),
(2,1960),
(2,1961),
(2,1985),
(2,1986),
(2,1990),
(2,2135),
(2,2136),
(2,2137),
(2,2138),
(2,2139),
(2,2140),
(2,2141),
(2,2147),
(2,2148),
(2,2149),
(2,2150),
(2,2152),
(2,2119),
(2,2039),
(2,2174),
(2,2285),
(2,2175);