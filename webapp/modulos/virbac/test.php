<?php 

require_once __DIR__.'/EntityProvider.php';
require_once __DIR__.'/EntityPurchase.php';
require_once __DIR__.'/EntityPurchaseLine.php';
//namespace virbac;

$pdoVirbac = new PDO(
        'sqlsrv:Server=dbsqlserver.cyv2immv1rf9.us-west-2.rds.amazonaws.com;Database=virbac',
        'sa',
        'sql2017_sepa'
    );
/*$pdoVirbac = new PDO(
        'sqlsrv:Server=10.234.64.22;Database=dbo',
        'pportal',
        'VBANPortal2017'
    );*/

$pdoNetwar = new PDO(
        'mysql:host=nmdb.cyv2immv1rf9.us-west-2.rds.amazonaws.com;dbname=_dbmlog0000009942',
        'nmdevel',
        'nmdevel'
    );


try {



	$lineaDeCompra = new EntityPurchaseLine( 
		
			[ 	$pdoNetwar ,
							'table' => [
								'app_ocompra_datos' => ['id_ocompra', 'id_producto', 'ses_tmp', 'estatus', 'activo', 'almacen', 'cantidad', 'costo', 'impuestos', 'caracteristica'] 
							]
			] ,

			[ 	$pdoVirbac ,
							'tables' => [
								'mpline' => [ 'ibpuno', 'ibitno', '\'sestmp\'' , '\'1\'', '\'1\'', 'ibwhlo', 'iborqa' , '\'0.0\'' , '\'0.0\'', '\'\'\'0\'\'\'' ] 
							],
						'join' => [ 
							
						] ,
						'where' => [
							
						]
			] 
			
	 );
	$lineaDeCompra->sync();
	die;
	//$pdoVirbac->beginTransaction();
	//$pdoNetwar->beginTransaction();

	$proveedor = new EntityProvider( 
	
			[ 	$pdoNetwar ,
							'tables' => [
								'mrp_proveedor' => ['idPrv', 'codigo', 'razon_social', 'rfc', 'domicilio', 'telefono', 'email', 'web', 'diascredito', 'idpais', 'idestado', 'idmunicipio', 'legal', 'precioycalidad', 'disponibilidad', 'idtipotercero', 'idtipoperacion', 'curp', 'cuenta', 'numidfiscal', 'nombrextranjero', 'PaisdeResidencia', 'nacionalidad', 'ivaretenido', 'isretenido', 'idTasaPrvasumir', 'idtipoiva', 'idIETU', 'ImOtSis', 'idtipo', 'beneficiario_pagador', 'cuentacliente', 'nombre', 'nombre_comercial', 'moneda', 'clasificacion', 'limite_credito', 'status', 'calle', 'no_ext', 'no_int', 'id_colonia', 'cp', 'saldo', 'colonia', 'ciudad'] ,
							]
			] ,

			[ 	$pdoVirbac ,
							'tables' => [
								'mphead' => ['iasuno', 
								'codigo', 'razon_social', 'rfc', 'domicilio', 'telefono', 'email', 'web', 'diascredito', 'idpais', 'idestado', 'idmunicipio', 'legal', 'precioycalidad', 'disponibilidad', 'idtipotercero', 'idtipoperacion', 'curp', 'cuenta', 'numidfiscal', 'nombrextranjero', 'PaisdeResidencia', 'nacionalidad', 'ivaretenido', 'isretenido', 'idTasaPrvasumir', 'idtipoiva', 'idIETU', 'ImOtSis', 'idtipo', 'beneficiario_pagador', 'cuentacliente', 'nombre', 'nombre_comercial', 'moneda', 'clasificacion', 'limite_credito', 'status', 'calle', 'no_ext', 'no_int', 'id_colonia', 'cp', 'saldo', 'colonia', 'ciudad' ] ,
							],
						'join' => [ 
						] ,
						'where' => [
						]
			] 
			
	 );
	//$proveedor->sync();

	$compra = new EntityPurchase( 
		
			[ 	$pdoNetwar ,
							'tables' => [
								'app_ocompra' => ['id_proveedor',  'id_usrcompra', 'observaciones', 'fecha', 'fecha_entrega', 'activo', 'id_requisicion', 'subtotal', 'total', 'id_almacen', 'id_usuario', 'fecha_creacion', 'tipo', 'num_factura'] ,
							]
			] ,

			[ 	$pdoVirbac ,
							'tables' => [
								'mphead' => ['iasuno',  ] ,
							],
						'join' => [ 
						] ,
						'where' => [
						]
			] 
			
	 );
	//$compra->sync();

	$lineaDeCompra = new EntityPurchaseLine( 
		
			[ 	$pdoNetwar ,
							'tables' => [
								'app_ocompra_datos' => ['id_ocompra', 'id_producto', 'ses_tmp', 'estatus', 'activo', 'almacen', 'cantidad', 'costo', 'impuestos', 'caracteristica' ] ,
							]
			] ,

			[ 	$pdoVirbac ,
							'tables' => [
								'mpline' => [ 'ibpuno', 'ibitno', '\'sestmp\'' , '\'1\'', '\'1\'', 'ibwhlo', 'iborqa' , 'ibpupr' , '\'0.0\'', '\'\'\'0\'\'\'' ] 
							],
						'join' => [ 
							
						] ,
						'where' => [
							
						]
			] 
			
	 );
	$lineaDeCompra->sync();
	//echo $lineaDeCompra->constructQuerySelect();



	//$pdoVirbac->commit();
	//$pdoNetwar->commit();

} catch (Exception $e) {
	//$pdoVirbac->rollback();
	//$pdoNetwar->rollback();

} finally {
	$pdoVirbac = null;
	$pdoNetwar = null;

}





/*$proveedor = new EntityProvider( 
	
		[ 	$pdoNetwar ,
						'tables' => [
							'mrp_proveedor' => ['idPrv', 'codigo', 'razon_social', 'rfc', 'domicilio', 'telefono', 'email', 'web', 'diascredito', 'idpais', 'idestado', 'idmunicipio', 'legal', 'precioycalidad', 'disponibilidad', 'idtipotercero', 'idtipoperacion', 'curp', 'cuenta', 'numidfiscal', 'nombrextranjero', 'PaisdeResidencia', 'nacionalidad', 'ivaretenido', 'isretenido', 'idTasaPrvasumir', 'idtipoiva', 'idIETU', 'ImOtSis', 'idtipo', 'beneficiario_pagador', 'cuentacliente', 'nombre', 'nombre_comercial', 'moneda', 'clasificacion', 'limite_credito', 'status', 'calle', 'no_ext', 'no_int', 'id_colonia', 'cp', 'saldo', 'colonia', 'ciudad'] ,
						]
		] ,

		[ 	$pdoVirbac ,
						'tables' => [
							'mphead' => ['iasuno', 
							'codigo', 'razon_social', 'rfc', 'domicilio', 'telefono', 'email', 'web', 'diascredito', 'idpais', 'idestado', 'idmunicipio', 'legal', 'precioycalidad', 'disponibilidad', 'idtipotercero', 'idtipoperacion', 'curp', 'cuenta', 'numidfiscal', 'nombrextranjero', 'PaisdeResidencia', 'nacionalidad', 'ivaretenido', 'isretenido', 'idTasaPrvasumir', 'idtipoiva', 'idIETU', 'ImOtSis', 'idtipo', 'beneficiario_pagador', 'cuentacliente', 'nombre', 'nombre_comercial', 'moneda', 'clasificacion', 'limite_credito', 'status', 'calle', 'no_ext', 'no_int', 'id_colonia', 'cp', 'saldo', 'colonia', 'ciudad' ] ,
						],
					'join' => [ 
					] ,
					'where' => [
					]
		] 
		
 );
$proveedor->sync();

$compra = new EntityPurchase( 
	
		[ 	$pdoNetwar ,
						'tables' => [
							'app_ocompra' => ['id_proveedor',  'id_usrcompra', 'observaciones', 'fecha', 'fecha_entrega', 'activo', 'id_requisicion', 'subtotal', 'total', 'id_almacen', 'id_usuario', 'fecha_creacion', 'tipo', 'num_factura'] ,
						]
		] ,

		[ 	$pdoVirbac ,
						'tables' => [
							'mphead' => ['iasuno',  ] ,
						],
					'join' => [ 
					] ,
					'where' => [
					]
		] 
		
 );
$compra->sync();

$lineaDeCompra = new EntityPurchaseLine( 
	
		[ 	$pdoNetwar ,
						'tables' => [
							'app_ocompra_datos' => ['id_ocompra', 'id_producto', 'ses_tmp', 'estatus', 'activo', 'almacen', 'cantidad', 'costo', 'impuestos', 'caracteristica' ] ,
						]
		] ,

		[ 	$pdoVirbac ,
						'tables' => [
							'mpline' => [ 'ibpuno', 'ibitno', '\'sestmp\'' , '\'1\'', '\'1\'', 'ibwhlo', 'iborqa' , '\'0.0\'' , '\'0.0\'', '\'\'\'0\'\'\'' ] ,
						],
					'join' => [ 
					] ,
					'where' => [
					]
		] 
		
 );
$lineaDeCompra->sync();*/


//$lineaDeCompra->constructSentenceInsert();
