<?php
  //require("models/connection_sqli_manual.php"); // funciones mySQLi
  require("models/reports.php"); // funciones mySQLi

  class Report_UserModel extends ReportsModel {

    /**
     * Metodo que cambia las collations de las tablas a las de transacciones para poder realizar
     * los UNION al momento de llamar por los registros de las tablas.
     */
    function set_collation() {
      $query = '';
      $queryNomTablas = "SELECT TABLE_NAME AS nombre
                FROM information_schema.TABLES
                WHERE TABLE_SCHEMA = SCHEMA()
                AND TABLE_NAME LIKE '%netwarelog_transacciones%';";
      $result = $this->query($queryNomTablas);
      while($row = mysqli_fetch_assoc($result)){
        $query .= "ALTER TABLE ".$row['nombre']." CONVERT to character set utf8 COLLATE utf8_general_ci; ";
      }
      $myQuery = $query;
      //echo($myQuery);
      $var = $this->multi_query($myQuery);
      return $var;
    }

    /**
     * Metodo que obtiene los nombres de las tablas donde se guardan las transacciones realizadas
     * por los usuarios, una vez obtenidas regresa los registros realizados por los usuarios segun el
     * rango de fecha que se ingreso en el filtro.
     * @param  [date]  $fecha_antes   [Del]
     * @param  [date]  $fecha_despues [Al]
     * @return [Registros]                [Los registros obtenidos]
     */
    function get_reporte_usuarios($fecha_antes, $fecha_despues) {
      $contador = 1;
      $queryNomTablas = "SELECT TABLE_NAME AS nombre
                FROM information_schema.TABLES
                WHERE TABLE_SCHEMA = SCHEMA()
                AND TABLE_NAME LIKE '%netwarelog_transacciones%';";
      $result = $this->query($queryNomTablas);

      $num_rows = $result->num_rows;
      while($row = mysqli_fetch_assoc($result)){
        if ($contador == $num_rows) {
          $queryReporte.= "SELECT fecha, usuario, nombreproceso, ip
                    FROM ".$row['nombre']."
                    WHERE `fecha` BETWEEN '$fecha_antes' AND '$fecha_despues'";
        } else {
          $queryReporte.= "SELECT fecha, usuario, nombreproceso, ip
                    FROM ".$row['nombre']."
                    WHERE `fecha` BETWEEN '$fecha_antes' AND '$fecha_despues'
                    UNION ALL ";
        }
        $contador++;
      }
      $queryReporte.= " ORDER BY `fecha` ASC;";
      $myQuery = $queryReporte;
      //echo($myQuery);
      $Resultados = $this->query($myQuery);
      return $Resultados;
    }

  }
?>
