<?php
/* 
 * Utiliza la información de los otros webconfig para operar en repolog/admin/
 */

        //include("../../webconfig.php"); //Este archivo ya carga la base de datos
        $link_catalog_local = "../../catalog/";
        include($link_catalog_local."conexionbd.php");

        if($instalarbase_repolog=="1"){
            include("instalacion.php");
        }      

			  // Remove Fatal Instrucctions	
				function repolog_RFI($sql){

					$debug="entre RFI >> Recibí:::".$sql;

					$msg = "-- FUNCION NO VALIDA--";	
					$sql = str_ireplace("alter ", $msg, $sql);
					$sql = str_ireplace("analize ", $msg, $sql);
					$sql = str_ireplace("backup ", $msg, $sql);
					$sql = str_ireplace("create ", $msg, $sql);
					$sql = str_ireplace("cache ", $msg, $sql);
					$sql = str_ireplace("change master to ", $msg, $sql);
					$sql = str_ireplace("check table ", $msg, $sql);
					$sql = str_ireplace("checksum table ", $msg, $sql);
					$sql = str_ireplace("commit ", $msg, $sql);
					$sql = str_ireplace("delete ", $msg, $sql);
					$sql = str_ireplace("describe ", $msg, $sql);
					$sql = str_ireplace("desc ", $msg, $sql);
					$sql = str_ireplace("drop ", $msg, $sql);
					$sql = str_ireplace("explain ", $msg, $sql);
					$sql = str_ireplace("flush ", $msg, $sql);
					$sql = str_ireplace("grant ", $msg, $sql);
					$sql = str_ireplace("handler ", $msg, $sql);
					$sql = str_ireplace("insert ", $msg, $sql);
					$sql = str_ireplace("kill ", $msg, $sql);
					$sql = str_ireplace("load ", $msg, $sql);
					$sql = str_ireplace("mysqldump ", $msg, $sql);
					$sql = str_ireplace("mysql ", $msg, $sql);
					$sql = str_ireplace("purge ", $msg, $sql);
					$sql = str_ireplace("rename ", $msg, $sql);
					$sql = str_ireplace("repair ", $msg, $sql);
					$sql = str_ireplace("replace ", $msg, $sql);
					$sql = str_ireplace("reset ", $msg, $sql);
					$sql = str_ireplace("revoke ", $msg, $sql);
					$sql = str_ireplace("rollback ", $msg, $sql);
					$sql = str_ireplace("savepoint ", $msg, $sql);
					$sql = str_ireplace("set ", $msg, $sql);
					$sql = str_ireplace("show ", $msg, $sql);
					$sql = str_ireplace("start ", $msg, $sql);
					$sql = str_ireplace("stop ", $msg, $sql);
					$sql = str_ireplace("unlock ", $msg, $sql);
					$sql = str_ireplace("use ", $msg, $sql);
					$sql = str_ireplace("truncate ", $msg, $sql);
					$sql = str_ireplace("update ", $msg, $sql);

					$debug.=" Regrese >>> ".$sql."\n";
					$accelog_access = new claccess();
					$accelog_access->nmerror_log($debug);
					//echo $debug;

					return $sql;
						
				}
?>
