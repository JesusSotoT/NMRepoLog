<?php
require('common.php');
require("models/externo.php");

class externo extends Common{
	public $externoModel;

	function __construct(){
		$this->externoModel = new externoModel();
	}

///////////////// ******** ---- 		imprime_comanda		------ ************ //////////////////
	//////// Imprime la comanda de la mesa
		// Como parametros recibe:
			// mesa -> ID de la mesa
		
		function imprime_comanda($objeto){
		// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
		// Si no conserva su valor normal
			$objeto=(empty($objeto))?$_REQUEST:$objeto;
		
		// Lista los pedidos de la comanda
			$objeto['pedidos']= $this->externoModel->listar_pedidos($objeto);
			print_r($objeto);
			return 0;
			
			
			$objeto['pedidos']=$objeto['pedidos']['rows'];
			$objeto['comanda']=$objeto['pedidos']['rows'][0]['comanda'];
		
		// Valida que la comanda tenga pedidos
			if (!empty($objeto['pedidos'])) {
			// Optenemos el logo
				$logo=$this->externoModel->logo($objet);
				$objeto['logo']=$logo['rows'][0]['logo'];
				
			// Optenemos el codigo de la comanda
				$objeto['codigo']=$objeto['pedidos'][0]['codigo'];
					
			// Obtiene los extra y calcula el total
				foreach ($objeto['pedidos'] as $key => $value) {
					if(!empty($value['adicionales'])){
						$objeto['pedidos'][$key]['extras']= $this->externoModel->listar_extras($value);
						$objeto['pedidos'][$key]['extras']=$objeto['pedidos'][$key]['extras']['rows'];
						
						foreach ($objeto['pedidos'][$key]['extras'] as $e => $ee) {
							$objeto['total']+=$ee['costo'];
						}
					}
					$objeto['total']+=$value['precioventa']*$value['cantidad'];
				}
				
				$objeto['mostrar']= $this->externoModel->mostrar_propina($value);
				$objeto['mostrar']=$objeto['mostrar']['rows'][0]['propina'];
			
			// Calcula la propina si se debe de mostrar
				if ($objeto['mostrar']==1) {
					$objeto['propina']=$objeto['total']*.10;
				}
				
				require('views/externo/imprime_comanda.php');
			} else {
				echo "<h1>* No hay pedidos en esta mesa *</h1>";
			}
		}

///////////////// ******** ---- 		FIN imprime_comanda		------ ************ //////////////////

} ?>