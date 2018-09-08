<?php 
	/*=====================================================================================
	=            controllers/accountsTree.php - Miguel Angel Velazco Martinez - 18/09/2013            =
	=====================================================================================*/
	
	/**
	
		TODO:
		- changeFather
			- Accede al metodo del modelo para cambiar la cuenta padre de los datos recibidos.
		- deleteAccount
			- Elimina la (o las) cuenta(s) asignadas al id de cuenta recibido.
		-  getAccounts
			- Solicita las cuentas y las envia como un JSON.
		- insertAccount
			- Inserta una nueva cuenta contable en base a los datos recibidos.
		- mainPage
			- "Pinta"  la vista principal, y solicita los datos necesarios para la misma.
		- updateAccount
			- Envia los datos al modelo para actualizar los datos de una cuenta.
		- updateAffectables
			- Actualiza las cuentas afectables.
	
	**/
	
	/*----  End of accountsTree.php - Miguel Angel Velazco Martinez - 18/09/2013  ------*/
	
	require_once 'models/accountsTree.php';
	require_once 'common.php';
	class AccountsTree extends Common
	{
		public $Model;

		function __construct()
		{
			$this->Model = new Account();
		}
		
		public function changeFather()
		{
			$data = $_REQUEST['data'];
			$res = $this->Model->changeFather($data);
			$this->Model->updateAffectables();// Actualizacion de cuentas afectables
			echo $res;
		}

		public function deleteAccount()
		{
			$data = $_REQUEST['data'];
			$res = $this->Model->deleteAccount($data);
			$this->Model->updateAffectables();// Actualizacion de cuentas afectables
			echo $res;
		}

		function getAccounts()
		{
			$json = json_encode($this->Model->getAccounts());
			echo $json;
		}

		public function insertAccount()
		{
			$data = $_REQUEST['data'];
			$res = $this->Model->insertAccount($data);
			$this->Model->updateAffectables();// Actualizacion de cuentas afectables
			echo $res;
		}


		function mainPage()
		{
			
			$inputMask = $this->Model->getMask();
			$accountMode = $this->Model->getAccountMode();
			$accounts = $this->Model->getAccounts();
			$coins = $this->Model->fillSelect("coin_id","description","cont_coin");
			$nature = $this->Model->fillSelect("nature_id","description","cont_nature");
			$status = $this->Model->fillSelect('status_id','description','cont_account_status');
			$classification = $this->Model->fillSelect("classification_id","description","cont_classification");
			$type = $this->Model->fillSelect("type_id","description",'cont_main_type');
			$oficial = $this->Model->oficial();
			require('views/accountsTree/index.php');
		}

		public function updateAccount()
		{
			$data = $_REQUEST['data'];
			$res = $this->Model->updateAccount($data);
			echo $res;
		}
		public function cvs(){
			$result=$this->Model->cuentas();
			$fp = fopen('php://output', 'w');//
 			//php://output envia  directamente en el navegador, sin necesidad de escribir en un archivo externo
 			if ($fp && $result) {
		   	 	  header('Content-Type: text/csv');
		     	  header('Content-Disposition: attachment; filename="cuentas.csv"');
				  //$i=1;
		   		while ($row = $result->fetch_assoc()) {
			    	// if($i==1){
			    	 // fputcsv($fp,array_keys($row));
					// }
			        fputcsv($fp, array_values($row));
					//$i++;
				}
		    	die;
		 	}
		}

		function cuentasNIF()
		{
			if($_GET['da'])
			{
				$ofi = $this->Model->clas_oficial();
				$cuentas = $this->Model->cuentas_mayor(0);
				require('views/accountsTree/cuentas_oficial.php');
			}
			else
			{
				$nif = $this->Model->clas_nif();
				$cuentas_mayor = $this->Model->cuentas_mayor(1);
				require('views/accountsTree/cuentas_nif.php');
			}
		}

		function UpdateNif()
		{
			$this->Model->UpdateNif($_POST['IdCuenta'],$_POST['Valor'],$_POST['Tipo']);
		}

		function getManualCode()
		{
			echo $this->Model->getManualCode($_REQUEST['data']);
		}
		
		
		
	}
?>