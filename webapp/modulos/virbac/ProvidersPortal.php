<?php 

require_once(__DIR__.'/_dependences/PHPMailer/PHPMailerLoad.php');
require_once __DIR__.'/util.php';
require_once __DIR__.'/Provider.php';


$pdo = new PDO(
	        'mysql:host=nmdb.cyv2immv1rf9.us-west-2.rds.amazonaws.com;dbname=_dbmlog0000012660',
	        'nmdevel',
	        'nmdevel'
	    );
$acceperfil= '5';       
$accelogV = 'netappmitranetwarelog1';
$idorg= '1';
$nombre_inst = 'laespecial';

/* --------------------------------------------------------------------------------------------- */


$providers = $pdo->query(
		    	"SELECT idPrv id FROM mrp_proveedor", 
		    	PDO::FETCH_ASSOC
		    );
foreach ($providers as $key => $provider) {
	$p = new Provider( $provider['id'] , $pdo);
	$prov = $p->getInfo();

	$infoAccount = $p->havePortalAccount();
	if( $infoAccount )
		$password = $infoAccount['clave'];
	else {
		$password = $p->generateRandPassword();
		guardarUsuarioPortal($prov['email'], "usuarioProveedor_{$prov['idPrv']}", $password, $prov['razon_social']);
	}
	enviaCorreoPortal($prov['email'], "usuarioProveedor_{$prov['idPrv']}", $password, $prov['razon_social']);
}
