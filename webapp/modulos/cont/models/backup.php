<?php

require("models/connection_sqli_manual.php"); // funciones mySQLi
class BackupModel extends Connection {

  function getTableHeaders($table) {
    $myQuery = "".
      "SELECT COLUMN_NAME
      FROM INFORMATION_SCHEMA.COLUMNS
      WHERE TABLE_SCHEMA = 'nmdev'
      AND TABLE_NAME = '$table';
    ";
    $Resultados = $this->query($myQuery);
    return $Resultados;
  }

  function getAllTable($table){
    $myQuery = "".
      "SELECT *
      FROM $table;
    ";
    $Resultados = $this->query($myQuery);
    return $Resultados;
  }

  function checkTable($table){
    $myQuery = "".
      "SHOW TABLES LIKE '$table'
    ";
    $Resultados = $this->query($myQuery);
    return $Resultados;
  }

  function esconder_facturas($uuids){
    $where = '';
    foreach ($uuids as $uuid => $valor) {
      if ($uuid == 0) {
        $where .= $valor.'" ';
      } else {
        $where .= 'OR uuid = "'.$valor.'" ';
      }
    }
    $myQuery = '
      UPDATE cont_facturas 
      SET temporal = 0
      WHERE uuid = "'.$where.';';
    
    $Result = $this->query($myQuery);
    return $Result;
  }
}
