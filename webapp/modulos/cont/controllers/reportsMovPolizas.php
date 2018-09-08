<?php
require('common.php');//Carga la funciones comunes top y footer
require("models/reportsMovPolizasModel.php");

class ReportsMovPolizas extends Common
{
	public $ReportsMovPolizasModel;

	function __construct(){
	$this->ReportsMovPolizasModel = new ReportsMovPolizasModel();
	$this->ReportsMovPolizasModel->connect();
	}

	function __destruct()
	{
		//Se destruye el objeto que instancia al modelo que se va a utilizar
		$this->ReportsMovPolizasModel->close();
	}

  function modal_facturas() {
    $contador = 1;
    $datos_modal = $this->ReportsMovPolizasModel->getData_modal_facturas($_POST['id']);
    while($row = $datos_modal->fetch_object()){
      if ($contador == 1) {
        echo('
          <div class="modal-header" style="background:#ddd; border-radius: 5px 5px 0 0">
        ');
        echo('
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
          <h4 class="modal-title"> <b>'.$row->concepto.' | Movimiento '.$row->numero_movimiento.'</b></h4>
        ');
        echo('
          </div>
          <div class="modal-body" style="max-height: 350px; overflow-x:scroll; background: #fdfdfd;">
        ');
      }
			$dir = $this->path()."xmls/facturas/".$row->pol_id."/";
			//Validamos que la ruta exista
			if (file_exists($dir.$row->factura)) {
				//Se carga el xml y es igualado a la variable xml
				$xml = simplexml_load_file($dir.$row->factura);
				//Validamos que la factura tenga folio
				if(isset($xml['folio'])){
					$uuidFolio = $xml['folio'];
				//Si no, le asignamos la UUID de la factura
				} else {
					$uuidFolio = $xml['uuid'];
				}
			}

      echo("<h5><b>Factura ".$contador.":</b></h5>");
      echo("<p>".$row->factura."</p>");

			//Si tiene folio:
			if (isset($xml['folio'])) {
				echo("<p><b>Folio:</b> ".$xml['folio']."</p>");
			//Si no tiene folio, pero tiene UUID:
			} else if(isset($row->factura)){
				$row->factura = explode("_", $row->factura);
				$name = pathinfo($row->factura[2], PATHINFO_FILENAME);
				$xml['uuid'] = $name;
				echo("<p><b>UUID:</b> ".$xml['uuid']."</p>");
			// Si de plano no tiene ni folio ni UUID:
			} else {
				$xml['folio'] = "No se encontro folio en la factura";
				echo("<p><b>Folio:</b> ".$xml['folio']."</p>");
			}

			//Si tiene serie:
			if (isset($xml['serie'])) {
				echo("<p><b>Serie:</b> ".$xml['serie']."</p>");
			//Si no tiene serie:
			} else {
				$xml['serie'] = "Esta factura no tiene serie.";
				echo("<p><b>Serie:</b> ".$xml['serie']."</p>");
			}
			echo("<p><b>Total: </b>".$xml['total']."</p>");
      echo("<hr>");
      $contador++;
			$total = $total + $xml['total'];
    }
    echo('
			<input type="hidden" id="totalMF" value="$ '.number_format($total, 2).'">
    </div>
		<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		</div>
    ');
  }
}
?>
