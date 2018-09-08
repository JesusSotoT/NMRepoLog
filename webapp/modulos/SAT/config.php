<?php

	/* RUTAS 
	================================================================== */
  if(strpos($_SERVER["HTTP_REFERER"], "appministra_api") === FALSE && strpos($_SERVER["HTTP_REFERER"], "facturar") === FALSE && strpos($_SERVER["HTTP_REFERER"], "kiosko") === FALSE)
  {
    $pathd='../../modulos/SAT/netwar';
    $pathdc='../../modulos/SAT/cliente';
    $positionPath="../../modulos";
  }else
  {
    $pathd='../webapp/modulos/SAT/netwar';
    $pathdc='../webapp/modulos/SAT/cliente';
    $positionPath="../webapp/modulos";
  }


  include "config_devprod.php";

  ?>