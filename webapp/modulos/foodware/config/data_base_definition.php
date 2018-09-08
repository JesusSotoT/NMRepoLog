<?php

	/*
		Definición de la estructura de la base de datos que usara el proyecto;
		Por estándar del framework cada tabla siempre tendrá las columnas: ID, ACTIVO, CREADO, MODIFICADO.

		El array principal << $db >> es asociativo y cada elemento de el es una tabla existente en la base de datos, cada tabla estará definida por:
		- Llave (Asociación al array principal): 
			Será el nombre de la tabla en minúsculas.
		- Contenido: 
			Será un array asociativo el cual contendrá la definición de cada uno de las columnas de la tabla, esta definición será la siguiente:
			- Llave (Asociación al array de la tabla): 
				Será el nombre de la columna en minúsculas.
			- Contenido:
				Será un array asociativo el cual contendrá la definición de cada una de las características, las cuales deben ser:
				- Tipo:
					Indica el tipo de dato de la columna, el framework soporta string, datetime, int.
				- Nulo:
					Indica si el campo puede ser nulo, esto se indica mediante un true o false dependiendo el caso.
				- Tamano:
					Indica la longitud del campo, se puede dejar en null para no especificar la longitud.
				- Default (Opcional):
					Indica el valor default que contendrá el campo.
	*/

	global $db;

	$db = array(
		"almacen" => array(
			"idAlmacen" => array(
				"tipo" => "int",
				"nulo" => true,
				"tamano" => 11,
				"id" => true
			),
			"nombre" => array(
				"tipo" => "string",
				"nulo" => true,
				"tamano" => 50
			),
			"direccion" => array(
				"tipo" => "string",
				"nulo" => true,
				"tamano" => 255
			),
			"idEstado" => array(
				"tipo" => "int",
				"nulo" => false,
				"tamano" => 11
			),
			"idmunicipio" => array(
				"tipo" => "int",
				"nulo" => false,
				"tamano" => 11
			),
			"cp" => array(
				"tipo" => "string",
				"nulo" => true,
				"tamano" => 50
			),
			"tel_contacto" => array(
				"tipo" => "string",
				"nulo" => true,
				"tamano" => 15
			),
			"contacto" => array(
				"tipo" => "string",
				"nulo" => true,
				"tamano" => 128
			)
		),
		"com_actividades" => array(
			"id" => array(
				"tipo" => "int",
				"nulo" => true,
				"tamano" => 11,
				"id" => true
			),
			"empleado" => array(
				"tipo" => "int",
				"nulo" => true,
				"tamano" => 11
			),
			"accion" => array(
				"tipo" => "string",
				"nulo" => true,
				"tamano" => 50
			),
			"descripcion" => array(
				"tipo" => "string",
				"nulo" => true,
				"tamano" => null
			),
			"id_sucursal" => array(
				"tipo" => "int",
				"nulo" => false,
				"tamano" => 11
			),
			"fecha" => array(
				"tipo" => "datetime",
				"nulo" => true,
				"tamano" => null
			)
		),
		"com_configuracion" => array(
			"id" => array(
				"tipo" => "int",
				"nulo" => true,
				"tamano" => 11,
				"id" => true
			),
			"propina" => array(
				"tipo" => "int",
				"nulo" => true,
				"tamano" => 11,
				"default" => 1
			),
			"consumo" => array(
				"tipo" => "int",
				"nulo" => true,
				"tamano" => 11,
				"default" => 0
			),
			"reservaciones" => array(
				"tipo" => "int",
				"nulo" => true,
				"tamano" => 11,
				"default" => 1
			),
			"seguridad" => array(
				"tipo" => "string",
				"nulo" => true,
				"tamano" => 20
			),
			"tipo_operacion" => array(
				"tipo" => "int",
				"nulo" => true,
				"tamano" => 1,
				"default" => 1
			),
			"pedir_pass" => array(
				"tipo" => "int",
				"nulo" => true,
				"tamano" => 11,
				"default" => 1
			),
			"mostrar_dolares" => array(
				"tipo" => "int",
				"nulo" => true,
				"tamano" => 11,
				"default" => 1
			),
			"mostrar_info_comanda" => array(
				"tipo" => "int",
				"nulo" => true,
				"tamano" => 11, 
				"default" => 1
			),
			"calculo_automatico" => array(
				"tipo" => "int",
				"nulo" => true,
				"tamano" => 11,
				"default" => 10
			),
			"mostrar_sd" => array(
				"tipo" => "int",
				"nulo" => true,
				"tamano" => 11,
				"default" => 1
			),
			"switch_propina" => array(
				"tipo" => "int",
				"nulo" => true,
				"tamano" => 11,
				"default" => 1
			),
			"facturar_propina" => array(
				"tipo" => "int",
				"nulo" => true,
				"tamano" => 11,
				"default" => 1
			),
			"idioma" => array(
				"tipo" => "int",
				"nulo" => true,
				"tamano" => 11,
				"default" => 1
			),
			"mostrar_nombre" => array(
				"tipo" => "int",
				"nulo" => true,
				"tamano" => 11,
				"default" => 1
			),
			"mostrar_domicilio" => array(
				"tipo" => "int",
				"nulo" => true,
				"tamano" => 11,
				"default" => 1
			),
			"mostrar_tel" => array(
				"tipo" => "int",
				"nulo" => true,
				"tamano" => 11,
				"default" => 1
			),
			"switch_info_ticket" => array(
				"tipo" => "int",
				"nulo" => true,
				"tamano" => 11,
				"default" => 1
			),
			"mostrar_info_empresa" => array(
				"tipo" => "int",
				"nulo" => true,
				"tamano" => 11,
				"default" => 1
			),
			"imprimir_pedido_general" => array(
				"tipo" => "int",
				"nulo" => true,
				"tamano" => 11,
				"default" => 2
			),
			"mostrar_iva" => array(
				"tipo" => "int",
				"nulo" => true,
				"tamano" => 11,
				"default" => 1
			),
			"mostrar_info_correo" => array(
				"tipo" => "int",
				"nulo" => true,
				"tamano" => 11,
				"default" => 1
			),
			"mostrar_logo_correo" => array(
				"tipo" => "int",
				"nulo" => true,
				"tamano" => 11,
				"default" => 1
			),
			"imagen_promo" => array(
				"tipo" => "string",
				"nulo" => true,
				"tamano" => 255
			),
			"imagen_felicitaciones" => array(
				"tipo" => "string",
				"nulo" => true,
				"tamano" => 255
			),
			"informacion_adicional" => array(
				"tipo" => "string",
				"nulo" => true,
				"tamano" => null
			),
			"enviar_promociones" => array(
				"tipo" => "int",
				"nulo" => true,
				"tamano" => 11,
				"default" => 1
			),
			"enviar_menu" => array(
				"tipo" => "int",
				"nulo" => true,
				"tamano" => 11,
				"default" => 1
			),
			"enviar_felicitaciones" => array(
				"tipo" => "int",
				"nulo" => true,
				"tamano" => 11,
				"default" => 1
			),
			"menu_digital" => array(
				"tipo" => "string",
				"nulo" => true,
				"tamano" => 255
			),
			"mostrar_logo_qr" => array(
				"tipo" => "int",
				"nulo" => true,
				"tamano" => 11,
				"default" => 1
			),
			"mostrar_info_qr" => array(
				"tipo" => "int",
				"nulo" => true,
				"tamano" => 11,
				"default" => 1
			),
			"tipo_vista_qr" => array(
				"tipo" => "int",
				"nulo" => true,
				"tamano" => 11,
				"default" => 1
			),
			"mostrar_opciones_menu" => array(
				"tipo" => "string",
				"nulo" => true,
				"tamano" => 50
			),
			"imagen_fondo" => array(
				"tipo" => "string",
				"nulo" => true,
				"tamano" => 250
			)
		),
		"administracion_usuarios" => array(
			"idadmin" => array(
				"tipo" => "int",
				"nulo" => true,
				"tamano" => 11,
				"id" => true
			),
			"nombre" => array(
				"tipo" => "string",
				"nulo" => true,
				"tamano" => 100
			),
			"apellidos" => array(
				"tipo" => "string",
				"nulo" => true,
				"tamano" => 100
			),
			"nombreusuario" => array(
				"tipo" => "string",
				"nulo" => true,
				"tamano" => 100
			),
			"clave" => array(
				"tipo" => "string",
				"nulo" => true,
				"tamano" => 50
			),
			"confirmaclave" => array(
				"tipo" => "string",
				"nulo" => true,
				"tamano" => 50
			),
			"correoelectronico" => array(
				"tipo" => "string",
				"nulo" => true,
				"tamano" => 100
			),
			"foto" => array(
				"tipo" => "string",
				"nulo" => true,
				"tamano" => 255
			),
			"idperfil" => array(
				"tipo" => "int",
				"nulo" => true,
				"tamano" => 11
			),
			"idempleado" => array(
				"tipo" => "int",
				"nulo" => true,
				"tamano" => 11
			),
			"idorganizacion" => array(
				"tipo" => "int",
				"nulo" => true,
				"tamano" => 11
			),
			"idpuesto" => array(
				"tipo" => "int",
				"nulo" => true,
				"tamano" => 11
			),
			"tipo" => array(
				"tipo" => "string",
				"nulo" => true,
				"tamano" => 2
			),
			"idSuc" => array(
				"tipo" => "int",
				"nulo" => true,
				"tamano" => 11
			),
			"id" => array(
				"tipo" => "int",
				"nulo" => true,
				"tamano" => 11
			),
			"mostrar_comanda" => array(
				"tipo" => "int",
				"nulo" => false,
				"tamano" => 11,
				"default" => 1
			)
		),
		"empleado" => array(
			"idempleado" => array(
				"tipo" => "int",
				"nulo" => true,
				"tamano" => 11,
				"id" => true
			),
			"codigo" => array(
				"tipo" => "string",
				"nulo" => true,
				"tamano" => 25
			),
			"nombre" => array(
				"tipo" => "string",
				"nulo" => false,
				"tamano" => 45
			),
			"apellido1" => array(
				"tipo" => "string",
				"nulo" => false,
				"tamano" => 45
			),
			"apellido2" => array(
				"tipo" => "string",
				"nulo" => false,
				"tamano" => 45
			),
			"idorganizacion" => array(
				"tipo" => "string",
				"nulo" => false,
				"tamano" => 45
			),
			"visible" => array(
				"tipo" => "int",
				"nulo" => false,
				"tamano" => 1,
				"default" -1
			),
			"administrador" => array(
				"tipo" => "int",
				"nulo" => true,
				"tamano" => 1
			),
			"turno" => array(
				"tipo" => "int",
				"nulo" => true,
				"tamano" => 11
			)
		),
		"com_mesas" => array(
			"id_mesa" => array(
				"tipo" => "int",
				"nulo" => true,
				"tamano" => 10,
				"id" => true
			),
			"idDep" => array(
				"tipo" => "int",
				"nulo" => true,
				"tamano" => 11
			),
			"personas" => array(
				"tipo" => "entero",
				"nulo" => true,
				"tamano" => 11
			),
			"tipo" => array(
				"tipo" => "entero",
				"nulo" => true,
				"tamano" => 11
			),
			"nombre" => array(
				"tipo" => "string",
				"nulo" => false,
				"tamano" => 60
			),
			"domicilio" => array(
				"tipo" => "string",
				"nulo" => true,
				"tamano" => 150
			),
			"idempleado" => array(
				"tipo" => "entero",
				"nulo" => true,
				"tamano" => 11
			),
			"x" => array(
				"tipo" => "entero",
				"nulo" => true,
				"tamano" => 5
			),
			"y" => array(
				"tipo" => "entero",
				"nulo" => true,
				"tamano" => 5
			),
			"status" => array(
				"tipo" => "entero",
				"nulo" => true,
				"tamano" => 11
			),
			"idSuc" => array(
				"tipo" => "entero",
				"nulo" => true,
				"tamano" => 11
			),
			"id_via_contacto" => array(
				"tipo" => "entero",
				"nulo" => true,
				"tamano" => 11
			),
			"notificacion" => array(
				"tipo" => "entero",
				"nulo" => true,
				"tamano" => 11
			),
			"id_zona_reparto" => array(
				"tipo" => "entero",
				"nulo" => true,
				"tamano" => 11
			),
			"tipo_mesa" => array(
				"tipo" => "entero",
				"nulo" => true,
				"tamano" => 11
			),
			"width" => array(
				"tipo" => "entero",
				"nulo" => true,
				"tamano" => 11
			),
			"heigth" => array(
				"tipo" => "entero",
				"nulo" => true,
				"tamano" => 11
			),
			"id_area" => array(
				"tipo" => "entero",
				"nulo" => true,
				"tamano" => 11
			),
			"id_dependencia" => array(
				"tipo" => "entero",
				"nulo" => true,
				"tamano" => 11
			),
			"password" => array(
				"tipo" => "string",
				"nulo" => true,
				"tamano" => 50
			)
		),
		"app_departamento" => array(
			"id" => array(
				"tipo" => "int",
				"nulo" => true,
				"tamano" => 11,
				"id" => true
			),
			"nombre" => array(
				"tipo" => "string",
				"nulo" => true,
				"tamano" => 50
			)
		),
		"app_familia" => array(
			"id" => array(
				"tipo" => "int",
				"nulo" => true,
				"tamano" => 11,
				"id" => true
			),
			"nombre" => array(
				"tipo" => "string",
				"nulo" => true,
				"tamano" => 50
			)
		),
		"app_linea" => array(
			"id" => array(
				"tipo" => "int",
				"nulo" => true,
				"tamano" => 11,
				"id" => true
			),
			"nombre" => array(
				"tipo" => "string",
				"nulo" => true,
				"tamano" => 50
			),
			"id_familia" => array(
				"tipo" => "entero",
				"nulo" => true,
				"tamano" => 3
			),
			"activo" => array(
				"tipo" => "entero",
				"nulo" => true,
				"tamano" => 1
			)
		),
		"app_productos" => array(
			"id" => array(
				"tipo" => "int",
				"nulo" => true,
				"tamano" => 11,
				"id" => true
			),
			"codigo" => array(
				"tipo" => "string",
				"nulo" => true,
				"tamano" => 45
			),
			"nombre" => array(
				"tipo" => "string",
				"nulo" => true,
				"tamano" => 45
			),
			"precio" => array(
				"tipo" => "decimal",
				"nulo" => true,
				"tamano" => null
			),
			"descripcion_corta" => array(
				"tipo" => "string",
				"nulo" => true,
				"tamano" => 45
			),
			"descripcion_larga" => array(
				"tipo" => "string",
				"nulo" => true,
				"tamano" => 200
			),
			"ruta_imagen" => array(
				"tipo" => "string",
				"nulo" => true,
				"tamano" => 500
			),
			"tipo_producto" => array(
				"tipo" => "entero",
				"nulo" => true,
				"tamano" => 11
			),
			"maximos" => array(
				"tipo" => "entero",
				"nulo" => true,
				"tamano" => 11
			),
			"minimos" => array(
				"tipo" => "entero",
				"nulo" => true,
				"tamano" => 11
			),
			"departamento" => array(
				"tipo" => "entero",
				"nulo" => true,
				"tamano" => 11
			),
			"familia" => array(
				"tipo" => "entero",
				"nulo" => true,
				"tamano" => 11
			),
			"linea" => array(
				"tipo" => "entero",
				"nulo" => true,
				"tamano" => 11
			),
			"id_tipo_costeo" => array(
				"tipo" => "entero",
				"nulo" => true,
				"tamano" => 11
			),
			"id_moneda" => array(
				"tipo" => "entero",
				"nulo" => true,
				"tamano" => 11
			),
			"id_clasificacion" => array(
				"tipo" => "entero",
				"nulo" => true,
				"tamano" => 11
			),
			"inventaiable" => array(
				"tipo" => "entero",
				"nulo" => true,
				"tamano" => 11
			),
			"precio" => array(
				"tipo" => "decimal",
				"nulo" => true,
				"tamano" => null
			),
			"comision" => array(
				"tipo" => "decimal",
				"nulo" => true,
				"tamano" => null
			),
			"precio" => array(
				"tipo" => "decimal",
				"nulo" => true,
				"tamano" => null
			),
			"tipo_comision" => array(
				"tipo" => "entero",
				"nulo" => true,
				"tamano" => 11
			),
			"id_unidad_venta" => array(
				"tipo" => "entero",
				"nulo" => true,
				"tamano" => 11
			),
			"series" => array(
				"tipo" => "entero",
				"nulo" => true,
				"tamano" => 11
			),
			"lotes" => array(
				"tipo" => "entero",
				"nulo" => true,
				"tamano" => 11
			),
			"pedimentos" => array(
				"tipo" => "entero",
				"nulo" => true,
				"tamano" => 11
			),
			"status" => array(
				"tipo" => "entero",
				"nulo" => true,
				"tamano" => 11
			),
			"id_unidad_compra" => array(
				"tipo" => "entero",
				"nulo" => true,
				"tamano" => 11
			),
			"costo_servicio" => array(
				"tipo" => "decimal",
				"nulo" => true,
				"tamano" => null
			),
			"formulaleps" => array(
				"tipo" => "entero",
				"nulo" => true,
				"tamano" => 11
			),
			"fecha_mod" => array(
				"tipo" => "datetime",
				"nulo" => true,
				"tamano" => null
			),
			"idempleado" => array(
				"tipo" => "entero",
				"nulo" => true,
				"tamano" => 11
			),
			"resena" => array(
				"tipo" => "entero",
				"nulo" => true,
				"tamano" => 11
			),
			"link" => array(
				"tipo" => "string",
				"nulo" => true,
				"tamano" => 100
			)
		)
	);

	//Verificar si esta activo el modulo API, de ser así, incorporar la tabla que procesara la tokenizacion de los usuarios
	/*global $activar_api;
	if($activar_api){
		$db["api_token_foodware_nativo"] = array(
				"id_usuario" => array(
					"tipo" => "int",
					"nulo" => false,
					"tamano" => null
				)
				"dispositivo" => array(
					"tipo" => "string",
					"nulo" => false,
					"tamano" => null
				),
				"token" => array(
					"tipo" => "string",
					"nulo" => false,
					"tamano" => 100
				),
				"inicio" => array(
					"tipo" => "datetime",
					"nulo" => false,
					"tamano" => null
				),
				"fin" => array(
					"tipo" => "datetime",
					"nulo" => false,
					"tamano" => null
				)
			);
	}*/

	/*foreach ($db as $columna => &$parametros) {
		if(!array_key_exists("activo", $parametros)){
			$parametros["activo"] = array(
					"tipo" => "int",
					"nulo" => false,
					"tamano" => 1,
					"default" => 1
				);
		}
		$parametros["modificado"] = $parametros["creado"] = array(
				"tipo" => "datetime",
				"nulo" => false,
				"tamano" => null
			);
	}*/

?>