<?php  

class Provider 
{
	private $id;
	private $pdo;

	public function __construct(string $id, PDO $pdo){
		$this->id = $id;
		$this->pdo = $pdo;
	}

	public function getInfo() : array{
		$res = $this->pdo->query(
	    	"SELECT * FROM mrp_proveedor WHERE idPrv = '{$this->id}'", 
	    	PDO::FETCH_ASSOC
	    );
		return $res->fetch();
	}

	public function havePortalAccount() {
		$res = $this->pdo->query(
	    	"SELECT nombreusuario, clave FROM administracion_usuarios WHERE nombreusuario = 'usuarioProveedor_{$this->id}'", 
	    	PDO::FETCH_ASSOC
	    );
	    if ($res->rowCount()) 
	    	return $res->fetch();
	    return false;
    }


    public function generateRandPassword(): string {
	    $alphabet = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
	    $pass = array(); //remember to declare $pass as an array
	    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
	    for ($i = 0; $i < 8; $i++) {
	        $n = rand(0, $alphaLength);
	        $pass[] = $alphabet[$n];
	    }
	    return implode($pass); //turn the array into a string
	}
}
