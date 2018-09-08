<?php
  require("models/connection_sqli_manual.php"); // funciones mySQLi
  class ReportsMovPolizasModel extends Connection {
    /**
     * @param $id [El id del registro que se va a buscar]
     * @return [Rows] Regresa los registros encontrados del id que se consulto.
     */
    function getData_modal_facturas($id)
    {
      $myquery = "SELECT
      mov.`NumMovto` as numero_movimiento,
      gf.`factura`,
      mov.`concepto` as concepto,
      pol.`id` as pol_id

      FROM `cont_movimientos` AS mov

      INNER JOIN `cont_polizas` AS pol
      ON mov.`IdPoliza` = pol.`id`

      INNER JOIN `cont_grupo_facturas` AS gf
      ON gf.`IdPoliza` = pol.`id` AND gf.`NumMovimiento` = mov.`NumMovto`

      WHERE mov.`id` = $id
      ";
      $Resultados = $this->query($myquery);
      return $Resultados;
    }
  }
?>
