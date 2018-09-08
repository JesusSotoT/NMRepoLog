<?php
	
	/* RUTAS 
	================================================================== */
	$pathd='../../modulos/SAT/netwar';
	$pathdc='../../modulos/SAT/cliente';
	 
	/* PRODUCCION
  	======================================================================= */
  	/*$azurianUrls=array();
  	$azurianUrls['recepcion']='https://cfdiservices.azurian.com.mx/cfdi-server-recepcion/services/ServicioRecepcionComprobantes?wsdl';
  	$azurianUrls['envio']='https://cfdiservices.azurian.com.mx/cfdi-server-consulta-estado-envio/services/ConsultaEstadoEnvio?wsdl';
  	$azurianUrls['comprobante']='https://cfdiservices.azurian.com.mx/cfdi-server-consulta-comprobante/services/ConsultaComprobante?wsdl';
  	$azurianUrls['cancelacion']='https://cfdiservices.azurian.com.mx/cfdi-server-recepcion/services/CancelarComprobantes?wsdl';
  	$azurianUrls['concultacomp']='https://cfdiservices.azurian.com.mx/cfdi-server-consulta-comprobante/services/ConsultaEstadoComprobante?wsdl';

	$p12_netwar=$pathd.'/netwar.produccion.pem';*/
	
  	/* DESARROLLO
  	======================================================================= */
    $azurianUrls=array();
  	$azurianUrls['recepcion']='https://cfdiservicesdesa.azurian.com.mx/cfdi-server-recepcion/services/ServicioRecepcionComprobantes?wsdl';
  	$azurianUrls['envio']='https://cfdiservicesdesa.azurian.com.mx/cfdi-server-consulta-estado-envio/services/ConsultaEstadoEnvio?wsdl';
  	$azurianUrls['comprobante']='https://cfdiservicesdesa.azurian.com.mx/cfdi-server-consulta-comprobante/services/ConsultaComprobante?wsdl';
  	$azurianUrls['cancelacion']='https://cfdiservicesdesa.azurian.com.mx/cfdi-server-recepcion/services/CancelarComprobantes?wsdl';
  	$azurianUrls['concultacomp']='https://cfdiservicesdesa.azurian.com.mx/cfdi-server-consulta-comprobante/services/ConsultaEstadoComprobante?wsdl';

	$p12_netwar=$pathd.'/netwar.desarrollo.pem';

	/* CERTIFICADOS DEL EMISOR
	================================================================== */
  	
?>