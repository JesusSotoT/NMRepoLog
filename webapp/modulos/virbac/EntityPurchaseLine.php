<?php 

require_once __DIR__.'/Entity.php';
//namespace virbac;
/**
* 
*/
class EntityPurchaseLine extends EntitySyncDB
{
	function __construct(/* array $DBsInfo ,*/ array $desDB , array $srcDB )
	{
		parent::__construct(/*  $DBsInfo ,*/  $desDB ,  $srcDB , 
			/*[
				"id_ocompra",
				"id_producto",
			"ses_tmp",
			"estatus",
			"activo",
				"almacen",
				"cantidad",
				"costo",
				"impuestos",
			"caracteristica",
			] ,*/
			[
				'src' => ["ibpuno"], // src primary key 
				'dest' => ["id"] // dest primary key
			]
		);
	}
}