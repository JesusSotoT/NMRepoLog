<?php 

require_once __DIR__.'/Entity.php';
//namespace virbac;
/**
* 
*/
class EntityProvider extends EntitySyncDB
{

	function __construct(/* array $DBsInfo ,*/ array $desDB , array $srcDB )
	{
		parent::__construct(/*  $DBsInfo ,*/  $desDB ,  $srcDB , 
		[
			
		]);
	}
}