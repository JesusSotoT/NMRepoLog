<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Product extends CI_Controller {

	public function elimina($id) {
		$this -> load -> model('Producto');
		$this -> Producto -> elimina($id);
		echo $this -> grid(2, 1, 1, true);
	}

	/////////////////////////////////////////////
	public function index() {
		$this -> load -> view('product/index');
	}

	/////////////////////////////////////////////////INICIO FUNCION ELIMINAR INSUMO //////////////////////////
	public function eliminar_insumos() {
		$idProducto = $_POST["idProducto"];
		$this -> load -> model('Producto');
		session_start();
		$_SESSION["materiales_agregados"] = '';
		unset($_SESSION["materiales_agregados"]);
		$this -> Producto -> eliminar_insumos($idProducto);
		//echo "Se eliminaron";
		//session_start();
		//$_SESSION["materiales_selecionados"]='';
		//session_destroy();

	}

	//////////////////////////////////////////////////INICIO
	/////////////////////////////////////////////
	public function formNuevo($id = '') {
		$flag = 0;
		session_start();
		unset($_SESSION["materiales_selecionados"]);

		unset($_SESSION["materiales_agregados"]);
		$_SESSION["materiales_agregados"] = Array();

		$this -> load -> model('Producto');

		// Si contiene el id(modificar producto) lo filtra por el tipo de producto
		// Producir producto, kit de material, producto, etc.
		if (is_numeric($id)) {
			$datos_producto = $this -> Producto -> giveProducto($id);

			$tipo = $datos_producto[0] -> tipo_producto;
			$objeto['tipo'] = $tipo;
			$data['margen_ganancia'] = $datos_producto[0] -> margen_ganancia;

			$data['lista_materiales'] = $this -> Producto -> listar_materiales($objeto);

			// Si no lo filtra normalmente
		} else {
			$data['lista_materiales'] = $this -> Producto -> listar_materiales($objeto);
		}

		$data['lista_unidades'] = $this -> Producto -> listaUnidades();

		////////   - - - - - --  			Materiales agregados			- - - - - - - - - - - ///////////
		//** Si viene el id(modificar producto) consulta los amteriales agregados y los agrega a un array de sesion
		if (is_numeric($id)) {
			$objeto['id'] = $id;
			// Lista los materiales agregados
			$materiales_agregados = $this -> Producto -> listar_materiales_agregados($objeto);

			// Consulta el costo y la informacion faltante de los materiales
			// Y los agrega a una array de session
			foreach ($materiales_agregados as $key => $v) {
				$objeto['id'] = $v -> idMaterial;

				// Consulta la informacion faltante de los materiales
				$producto = $this -> Producto -> listar_materiales($objeto);

				// ** Compara si la unidad de venta es la misma que la de compra para calcular el costo

				// Si la unidad de venta es la misma que la de compra basta con multiplicar cantidad por costo para sacar el costo
				if ($producto[0] -> idunidad == $producto[0] -> idunidadCompra) {
					$costo = ($producto[0] -> costo * $v -> cantidad);
					// Si la unidad de venta es diferente que la de compra realiza una conversion
					// para sacar el equivalente
				} else {
					// Calcula la conversion
					$conversion = $this -> Producto -> unidadConversion($producto[0] -> idunidad);

					// $producto[0]->idunidadCompra==21 significa que La unidad es litro
					if ($producto[0] -> idunidadCompra == 21) {
						// Calcula la conversion en base a litros
						$conversion = $this -> Producto -> unidadConversion($producto[0] -> idunidadCompra);
						$costo = ($producto[0] -> costo * $v -> cantidad) / $conversion;
						// La unidad puede ser kilo, gramo, unidad, etc.
					} else {
						$costo = ($producto[0] -> costo * $v -> cantidad) / $conversion;
					}
				}

				// Armamos el array
				$material = '';
				$material -> idProducto = $producto[0] -> idProducto;
				$material -> idunidad = $producto[0] -> idunidad;
				$material -> material = $producto[0] -> nombre;
				$material -> unidad = $producto[0] -> unidad;
				$material -> costo = $costo;
				$material -> cantidad = $v -> cantidad;
				$material -> tipo = $v -> opcional;

				// Agregamos el material a una posicion del array
				$_SESSION["materiales_agregados"][$key] = $material;
			}
		}
		////////   - - - - - --  		FIN	Materiales agregados			- - - - - - - - - - - ///////////

		if ($id != '') {
			$data['etapas'] = $this -> Producto -> consultaetapa($id);
			$data['provesmasivos'] = $this -> Producto -> consultaProbesmasivos($id);
			$data['precios'] = $this -> Producto -> consultaPrecios($id);
		}
		if (is_numeric($id)) {
			$data["datos_producto"] = $this -> Producto -> giveProducto($id);
			$data["datos_stock"] = $this -> Producto -> consultaStock($id);
			$data["datos_orden"] = $this -> Producto -> ordenesCompra($id);
		}

		$data['query_color'] = $this -> Producto -> buscaColor();
		$data['query_talla'] = $this -> Producto -> buscaTalla();

		//	$select_prv = "<select id='proveedor' >";
		//	$select_prv .= '<option value="">-Seleccione-</option>';
		if (is_numeric($id)) {
			$z = $this -> Producto -> buscaProveedorEdit($id);
			//print_r($z);
			//echo $z[0]->idPrv."dddd";
			$select_prv = "<select id='proveedor' class='form-control proveedor_added' name='idProveedor' >";
			$select_prv .= '<option value="">-Seleccione-</option>';
			foreach ($this->Producto->buscaProveedor() as $prov) :
				//echo $prov->idPrv."XX";
				//echo $data["datos_producto"][0]->idProveedor."yy";
				if ($z[0] -> idPrv == $prov -> idPrv) {
					$select_prv .= '<option selected value="' . $prov -> idPrv . '">' . utf8_decode($prov -> razon_social) . '</option>';
				} else {
					$select_prv .= '<option value="' . $prov -> idPrv . '">' . utf8_decode($prov -> razon_social) . '</option>';
				}
			endforeach;
		} else {
			$select_prv = "<select id='proveedor' class='form-control prueba_testeo' name='idProveedor' >";
			$select_prv .= '<option value="">-Seleccione-</option>';
			foreach ($this->Producto->buscaProveedor() as $prov) :
				$select_prv .= '<option value="' . $prov -> idPrv . '">' . utf8_decode($prov -> razon_social) . '</option>';
			endforeach;
		}
		$flag++;
		$select_prv .= "</select>";
		$data['prv'] = $select_prv;
		////////////////////////////////// INICIA PROVEEDOR ADICIONAL HIDDEN ////////////////////////////////////////////
		if (is_numeric($id)) {
			$z = $this -> Producto -> buscaProveedorEdit($id);

			$select_prv_hidden = "<select id='proveedor_adicional' class='form-control proveedor_added' name='idProveedor' >";
			$select_prv_hidden .= '<option value="">-Seleccione-</option>';
			foreach ($this->Producto->buscaProveedor() as $prov) :
				//echo $prov->idPrv."XX";
				//echo $data["datos_producto"][0]->idProveedor."yy";
				if ($z[0] -> idPrv == $prov -> idPrv) {
					$select_prv_hidden .= '<option selected value="' . $prov -> idPrv . '">' . utf8_decode($prov -> razon_social) . '</option>';
				} else {
					$select_prv_hidden .= '<option value="' . $prov -> idPrv . '">' . utf8_decode($prov -> razon_social) . '</option>';
				}
			endforeach;
		} else {
			$select_prv_hidden = "<select id='proveedor' class='form-control prueba_testeo' name='idProveedor' >";
			$select_prv_hidden .= '<option value="">-Seleccione-</option>';
			foreach ($this->Producto->buscaProveedor() as $prov) :
				$select_prv_hidden .= '<option value="' . $prov -> idPrv . '">' . utf8_decode($prov -> razon_social) . '</option>';
			endforeach;
		}
		$flag++;
		$select_prv_hidden .= "</select>";
		$data['prv_hidden'] = $select_prv_hidden;

		////////////////////////////////// TERMINA PROVEEDOR ADICIONAL HIDDEN ////////////////////////////////////////////
		$contador_impuestos = 0;
		$impuestos = "";
		foreach ($this->Producto->consultaImpuestos() as $imp) :
			if (is_numeric($id)) {
				$consultaProductoImpuesto = $this -> Producto -> consultaProductoImpuesto($id, $imp -> id);
				if ($consultaProductoImpuesto['si'] != 0 ) {
					$impuestos .= "<div style='display: table; width: 100%;'>
						<div style='display: table-cell; width: 30%;' >
						<input type='hidden' id='hideImp_" . $contador_impuestos . "' value='" . $imp -> nombre . "'>
						" . $imp -> nombre . ":
						</div> 
						<div style='display: table-cell; width: 50%;' >
						<input value='" . number_format($consultaProductoImpuesto['valImpues'], 2) . "' type='text' id='impuesto_" . $contador_impuestos . "' class='numeric form-control' readonly maxlength='5' style='width: 60%; margin-bottom: 1em; float: left;'>&nbsp;% 
						</div>
						<div style='display: table-cell; width: 20%;' >
						<input type='checkbox' name='check_imp' class='" . $imp -> nombre . "' id='chk_" . $contador_impuestos . "' value=" . $imp -> id . " onclick='impuestoOnOff(" . $contador_impuestos . ");' checked>
						</div>
						</div>";
				} else {
					$impuestos .= "<div style='display: table; width: 100%;'>
						<div style='display: table-cell; width: 30%;' >
						<input type='hidden' id='hideImp_" . $contador_impuestos . "' value='" . $imp -> nombre . "'>
						" . $imp -> nombre . ":
						</div> 
						<div style='display: table-cell; width: 50%;' >
						<input value='" . $imp -> valor . "' type='text' id='impuesto_" . $contador_impuestos . "' class='numeric form-control' maxlength='5' readonly style='width: 60%; margin-bottom: 1em; float: left;' >&nbsp;% 
						</div>
						<div style='display: table-cell; width: 20%;' >
						<input type='checkbox' name='check_imp' class='" . $imp -> nombre . "' id='chk_" . $contador_impuestos . "' value=" . $imp -> id . " onclick='impuestoOnOff(" . $contador_impuestos . ");'>
						</div>
						</div>";
				}
				$contador_impuestos++;
			} else {
				$impuestos .= "<div style='display: table; width: 100%;'>
					<div style='display: table-cell; width: 30%;' >
					<input type='hidden' id='hideImp_" . $contador_impuestos . "' value='" . $imp -> nombre . "'>
					" . $imp -> nombre . ":
					</div> 
					<div style='display: table-cell; width: 50%;' >
					<input value='" . $imp -> valor . "' type='text' id='impuesto_" . $contador_impuestos . "' class='numeric form-control' maxlength='5' readonly style='width: 60%; margin-bottom: 1em; float: left;' >&nbsp;% 
					</div>
					<div style='display: table-cell; width: 20%;' >
					<input type='checkbox' name='check_imp' class=" . $imp -> nombre . " id='chk_" . $contador_impuestos . "' value=" . $imp -> id . " onclick='impuestoOnOff(" . $contador_impuestos . ");'>
					</div>
					</div>";
				$contador_impuestos++;
			}
		endforeach;
		$impuestos .= "<input type='hidden' value=" . $contador_impuestos . " id='contador_impuestos'>";
		$data['imp'] = $impuestos;

		$select_departamento = "<div><select id='dep' onchange='buscaFamilia(this.value);' class='form-control'>";
		$select_departamento .= "<option value=''>----------</option>";
		foreach ($this->Producto->buscaDepartamento() as $dep) :
			if (is_numeric($id)) {
				if ($data["datos_producto"][0] -> idDep == $dep -> idDep) {
					$select_departamento .= '<option selected value="' . $dep -> idDep . '">' . $dep -> nombre . '</option>';
				} else {
					$select_departamento .= '<option value="' . $dep -> idDep . '">' . $dep -> nombre . '</option>';
				}
			} else {
				if ($dep -> idDep == 1) {
					$select_departamento .= '<option selected value="' . $dep -> idDep . '">' . $dep -> nombre . '</option>';
				} else {
					$select_departamento .= '<option value="' . $dep -> idDep . '">' . $dep -> nombre . '</option>';
				}
			}
		endforeach;
		$select_departamento .= "</select></div>";
		$data['dep'] = $select_departamento;
		//-----------------------------------------------------

		$select_linea = "<select id='lin' class='form-control'>";

		foreach ($this->Producto->buscaLinea() as $lin) :
			if (is_numeric($id)) {
				if ($data["datos_producto"][0] -> idLinea == $lin -> idLin) {
					$select_linea .= '<option selected value="' . $lin -> idLin . '">' . $lin -> nombre . '</option>';
				} else {
					$select_linea .= '<option value="' . $lin -> idLin . '">' . $lin -> nombre . '</option>';
				}
			} else {
				if ($lin -> idLin == 1) {
					$select_linea .= '<option selected value="' . $lin -> idLin . '">' . $lin -> nombre . '</option>';
				} else {
					$select_linea .= '<option value="' . $lin -> idLin . '">' . $lin -> nombre . '</option>';
				}
			}
		endforeach;

		$select_linea .= "</select>";
		$data['lin'] = $select_linea;
		//-----------------------------------------------------

		//--------------------------------------------------

		$select_fam = "<select id='fam' onchange='buscaLinea(this.value);' class='form-control'>";

		foreach ($this->Producto->buscaFamilia() as $fam) :
			if (is_numeric($id)) {
				if ($data["datos_producto"][0] -> idFam == $fam -> idFam) {
					$select_fam .= '<option selected value="' . $fam -> idFam . '">' . $fam -> nombre . '</option>';
				} else {
					$select_fam .= '<option value="' . $fam -> idFam . '">' . $fam -> nombre . '</option>';
				}
			} else {
				if ($fam -> idFam == 1) {
					$select_fam .= '<option selected value="' . $fam -> idFam . '">' . $fam -> nombre . '</option>';
				} else {
					$select_fam .= '<option value="' . $fam -> idFam . '">' . $fam -> nombre . '</option>';
				}
			}
		endforeach;

		$select_fam .= "</select>";
		$data['fam'] = $select_fam;

		$data['query_unidad'] = $this -> Producto -> listaUnidades();
		$data['query_unidad_conversion'] = $this -> Producto -> listaUnidadesConversion();
		if (is_numeric($id)) {
			$data["datos_producto"] = $this -> Producto -> giveProducto($id);
		}
		//var_dump($data);
		if ($data["datos_producto"][0] != '') {
			$data["type"] = 2;
		} else {
			$data["type"] = 1;
		}
		$this -> load -> view('product/tab1', $data);
	}

	public function form($id = '') {
		session_start();
		unset($_SESSION["materiales_selecionados"]);

		$this -> load -> model('Producto');
		$data['query_color'] = $this -> Producto -> buscaColor();
		$data['query_talla'] = $this -> Producto -> buscaTalla();
		$data['etapas'] = $this -> Producto -> consultaetapa($id);
		//krmn
		$data['query_unidad'] = $this -> Producto -> listaUnidades();
		if (is_numeric($id)) {
			$data["datos_producto"] = $this -> Producto -> giveProducto($id);
		}

		$select_departamento = "<div><select id='dep' onchange='buscaFamilia(this.value);' class='form-control'>";
		$select_departamento .= "<option value=''>----------</option>";
		foreach ($this->Producto->buscaDepartamento() as $dep) :
			if (is_numeric($id)) {
				if ($data["datos_producto"][0] -> idDep == $dep -> idDep) {
					$select_departamento .= '<option selected value="' . $dep -> idDep . '">' . $dep -> nombre . '</option>';
				} else {
					$select_departamento .= '<option value="' . $dep -> idDep . '">' . $dep -> nombre . '</option>';
				}
			} else {
				if ($dep -> idDep == 1) {
					$select_departamento .= '<option selected value="' . $dep -> idDep . '">' . $dep -> nombre . '</option>';
				} else {
					$select_departamento .= '<option value="' . $dep -> idDep . '">' . $dep -> nombre . '</option>';
				}
			}
		endforeach;
		$select_departamento .= "</select></div>";
		$data['dep'] = $select_departamento;
		//-----------------------------------------------------

		$select_linea = "<select id='lin' class='form-control'>";

		foreach ($this->Producto->buscaLinea() as $lin) :
			if (is_numeric($id)) {
				if ($data["datos_producto"][0] -> idLinea == $lin -> idLin) {
					$select_linea .= '<option selected value="' . $lin -> idLin . '">' . $lin -> nombre . '</option>';
				} else {
					$select_linea .= '<option value="' . $lin -> idLin . '">' . $lin -> nombre . '</option>';
				}
			} else {
				if ($lin -> idLin == 1) {
					$select_linea .= '<option selected value="' . $lin -> idLin . '">' . $lin -> nombre . '</option>';
				} else {
					$select_linea .= '<option value="' . $lin -> idLin . '">' . $lin -> nombre . '</option>';
				}
			}
		endforeach;

		$select_linea .= "</select>";
		$data['lin'] = $select_linea;
		//-----------------------------------------------------

		//--------------------------------------------------

		$select_fam = "<select id='fam' onchange='buscaLinea(this.value);' class='form-control'>";

		foreach ($this->Producto->buscaFamilia() as $fam) :
			if (is_numeric($id)) {
				if ($data["datos_producto"][0] -> idFam == $fam -> idFam) {
					$select_fam .= '<option selected value="' . $fam -> idFam . '">' . $fam -> nombre . '</option>';
				} else {
					$select_fam .= '<option value="' . $fam -> idFam . '">' . $fam -> nombre . '</option>';
				}
			} else {
				if ($fam -> idFam == 1) {
					$select_fam .= '<option selected value="' . $fam -> idFam . '">' . $fam -> nombre . '</option>';
				} else {
					$select_fam .= '<option value="' . $fam -> idFam . '">' . $fam -> nombre . '</option>';
				}
			}
		endforeach;

		$select_fam .= "</select>";
		$data['fam'] = $select_fam;
		//-----------------------------------------------------
		//-----------------------------------------------------

		$select_prv = "<select id='proveedor' class='nminputselect' style='width:305px;'>";
		$select_prv .= '<option value="0">-Seleccione-</option>';

		foreach ($this->Producto->buscaProveedor() as $prov) :
			if (is_numeric($id)) {
				if ($data["datos_producto"][0] -> idProveedor == $prov -> idPrv) {
					$select_prv .= '<option selected value="' . $prov -> idPrv . '">' . utf8_decode($prov -> razon_social) . '</option>';
				} else {
					$select_prv .= '<option value="' . $prov -> idPrv . '">' . utf8_decode($prov -> razon_social) . '</option>';
				}
			} else {
				$select_prv .= '<option value="' . $prov -> idPrv . '">' . utf8_decode($prov -> razon_social) . '</option>';
			}

		endforeach;

		$select_prv .= "</select>";
		$data['prv'] = $select_prv;
		//-----------------------------------------------------

		$contador_impuestos = 0;
		$impuestos = "";
		foreach ($this->Producto->consultaImpuestos() as $imp) :
			if (is_numeric($id)) {
				$consultaProductoImpuesto = $this -> Producto -> consultaProductoImpuesto($id, $imp -> id);
				if ($consultaProductoImpuesto != 0) {
					$impuestos .= "<div style='display: table; width: 100%;'>
														<div style='display: table-cell; width: 30%;' >
														<input type='hidden' id='hideImp_" . $contador_impuestos . "' value='" . $imp -> nombre . "'>
														" . $imp -> nombre . ":
														</div> 
														<div style='display: table-cell; width: 50%;' >
														<input value='" . number_format($consultaProductoImpuesto, 2) . "' type='text' id='impuesto_" . $contador_impuestos . "' class='numeric' readonly maxlength='5' style='width: 60%;'> % 
														</div>
														<div style='display: table-cell; width: 20%;' >
														<input type='checkbox' name='check_imp' class='" . $imp -> nombre . "' id='chk_" . $contador_impuestos . "' value=" . $imp -> id . " onclick='impuestoOnOff(" . $contador_impuestos . ");' checked>
														</div>
														</div>";
				} else {
					$impuestos .= "<div style='display: table; width: 100%;'>
														<div style='display: table-cell; width: 30%;' >
														<input type='hidden' id='hideImp_" . $contador_impuestos . "' value='" . $imp -> nombre . "'>
														" . $imp -> nombre . ":
														</div> 
														<div style='display: table-cell; width: 50%;' >
														<input value='" . $imp -> valor . "' type='text' id='impuesto_" . $contador_impuestos . "' class='numeric' maxlength='5' readonly style='width: 60%;' > % 
														</div>
														<div style='display: table-cell; width: 20%;' >
														<input type='checkbox' name='check_imp' class='" . $imp -> nombre . "' id='chk_" . $contador_impuestos . "' value=" . $imp -> id . " onclick='impuestoOnOff(" . $contador_impuestos . ");'>
														</div>
														</div>";
				}
				$contador_impuestos++;
			} else {
				$impuestos .= "<div style='display: table; width: 100%;'>
													<div style='display: table-cell; width: 30%;' >
													<input type='hidden' id='hideImp_" . $contador_impuestos . "' value='" . $imp -> nombre . "'>
													" . $imp -> nombre . ":
													</div> 
													<div style='display: table-cell; width: 50%;' >
													<input value='" . $imp -> valor . "' type='text' id='impuesto_" . $contador_impuestos . "' class='numeric' maxlength='5' readonly style='width: 60%;' > % 
													</div>
													<div style='display: table-cell; width: 20%;' >
													<input type='checkbox' name='check_imp' class=" . $imp -> nombre . " id='chk_" . $contador_impuestos . "' value=" . $imp -> id . " onclick='impuestoOnOff(" . $contador_impuestos . ");'>
													</div>
													</div>";
				$contador_impuestos++;
			}
		endforeach;
		$impuestos .= "<input type='hidden' value=" . $contador_impuestos . " id='contador_impuestos'>";
		$data['imp'] = $impuestos;

		$this -> load -> view('product/form', $data);
	}

	/////////////////////////////////////////////
	public function familia() {
		$idDep = $_POST['id'];
		$this -> load -> model('Producto');
		$data['query_familia'] = $this -> Producto -> buscaFamilia($idDep);

		echo "<div><select id='fam' onchange='buscaLinea(this.value);' class='form-control'>";
		echo '<option value="">Seleccione una opcion</option>';
		foreach ($data['query_familia'] as $fam) :
			echo '<option value="' . $fam -> idFam . '">' . utf8_decode($fam -> nombre) . '</option>';
		endforeach;
		echo "</select></div>";

	}

	/////////////////////////////////////////////
	public function linea() {
		$idFam = $_POST['id'];
		$this -> load -> model('Producto');
		$data['query_linea'] = $this -> Producto -> buscaLinea($idFam);

		echo "<div><select id='lin' class='form-control'>";
		echo '<option value="">Seleccione una opcion</option>';
		foreach ($data['query_linea'] as $lin) :
			echo '<option value="' . $lin -> nombre . '">' . $lin -> nombre . '</option>';
		endforeach;
		echo "</select></div>";
	}

	/////////////////////////////////////////////
	public function registraProducto() {
		$descx = $_POST['descx'];
		$listaprecios = $_POST['z'];
		$costo_produccion = $_POST['costo_produccion'];
		$provesmasiv = $_POST['y'];
		$etapas = $_POST["x"];
		$tipop = $_POST['tipo_prod'];
		$id = $_POST['id'];
		$linea = $_POST['linea'];
		$nombre = $_POST['nombre'];
		$des_cor = $_POST['des_cor'];
		$des_lar = $_POST['des_lar'];
		$des_cen = $_POST['des_cen'];
		$color = $_POST['color'];
		$talla = $_POST['talla'];
		$materiales = $_POST['materiales'];
		$maximo = $_POST['maximo'];
		$minimo = $_POST['minimo'];
		//	$inicial=$_POST['inicial'];
		$imagen = $_POST['imagen'];
		$codigo = $_POST['codigo'];
		$consumo = $_POST['consumo'];
		$vendible = $_POST['vendible'];
		$esreceta = $_POST['esreceta'];
		$eskit = $_POST['eskit'];
		$unidad = $_POST['unidad'];
		$unidadCompra = $_POST['unidadCompra'];
		$impuestos_valores = $_POST['impuestos_valores'];
		$impuestos_ids = $_POST['impuestos_ids'];

		$preciov = $_POST['preciov'];
		$preciom = $_POST['preciom'];
		$preciol = $_POST['preciol'];

		$proveedor = $_POST['proveedor'];
		$costo = $_POST['costo'];
		$margen = $_POST['margen'];

		if ($unidad == 1 || $unidadCompra == 1) {
			$inicial = round($_POST['inicial']);
		} else {
			$inicial = $_POST['inicial'];
		}

		$this -> load -> model('Producto');

		$data['result_insert_product'] = $this -> Producto -> registraProducto($proveedor, $costo, $preciov, $preciom, $preciol, $id, $nombre, $des_lar, $des_cor, $des_cen, $color, $talla, $linea, $materiales, $maximo, $minimo, $imagen, $codigo, $consumo, $vendible, $esreceta, $impuestos_ids, $impuestos_valores, $inicial, $eskit, $unidad, $tipop, $etapas, $unidadCompra, $provesmasiv, $costo_produccion, $margen, $listaprecios, $descx);
		print_r($data['result_insert_product']);

	}

	/////////////////////////////////////////////
	public function grid($modo = "1", $filtro = 1, $pagina = 1, $elimina = false) {
		$this -> load -> helper('url');
		$base_url = str_replace("modulos/mrp/", "", base_url());

		if (strlen($_POST["filtro"]) > 5) {
			$filtro = $_POST["filtro"];
		}

		$this -> load -> model('Producto');
		$grid = $this -> Producto -> grid($pagina, $filtro);
		$encabezado = '';
		$busquedas = '';
		$filas = '';
		$i = 0;
		foreach ($grid["data"] as $fila) {
			$encabezado = '';
			$busquedas = '';
			if ($i % 2 == 0) {$filas .= '<tr class="busqueda_fila">';
			} else {$filas .= '<tr class="busqueda_fila2">';
			}
			$e = 0;
			foreach ($fila as $campo => $valor) {
				if ($e == 0) {$id = $valor;
				}
				$encabezado .= '<td align="center">' . ($campo) . '</td>';
				$busquedas .= '<td><input class="input_filtro" onkeydown="input_keydown(event,this.value,\'' . strtolower($campo) . '\',\'' . $base_url . '\')"></td>';
				if ($modo == 1) {
					$filas .= '<td><a class="a_registro" href="' . base_url() . 'index.php/product/form/' . $id . '">' . utf8_decode($valor) . '</a></td>';
				}
				if ($modo == 2) {
					$filas .= '<td><a class="a_registro" onclick="Elimina(' . $id . ',\'mrp_producto\');">' . ($valor) . '</a></td>';
				}

				$e++;
			}
			$filas .= '</tr>';
			$i++;
		}
		if ($pagina == 1) {$pag_anterior = 1;
		} else {$pag_anterior = $pagina - 1;
		}
		if (($pagina + 1) > $grid["paginas"]) {$pag_siguiente = $pagina;
		} else {$pag_siguiente = $pagina + 1;
		}

		$link_anterior = base_url() . 'index.php/product/grid/' . $modo . '/' . $filtro . '/' . $pag_anterior;
		$link_siguiente = base_url() . 'index.php/product/grid/' . $modo . '/' . $filtro . '/' . $pag_siguiente;

		if ($encabezado == "") {
			$encabezado = '
													<td align="center">ID</td>
													<td align="center">Código</td>
													<td align="center">Nombre</td>
													<td align="center">Departamento</td>
													<td align="center">Familia</td>
													<td align="center">Linea</td>
													<td align="center">Precio</td>
													<td align="center">Color</td>
													<td align="center">Talla</td>
													<td align="center">Maximo</td>
													<td align="center">Minimo</td>';
		}

		if ($i < 10) {
			for ($j = $i; $j < 10; $j++) {
				if ($j % 2 == 0) {$filas .= '<tr class="busqueda_fila">';
				} else {$filas .= '<tr class="busqueda_fila2">';
				}
				$filas .= "<td></td><td></td><td></td><td></td><td></td><td></td><td></td>";
				//$filas.="<td></td><td></td><td></td><td></td>";
				//$filas.="<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
			}
		}

		$catalogo = '<div class="tipo">
												<table><tbody><tr>
												<td><input type="button" value="<" onclick="paginacionGrid(\'' . $link_anterior . '\');"></td>
												<td><input type="button" value=">" onclick="paginacionGrid(\'' . $link_siguiente . '\');" ></td>
												<td><a href="javascript:window.print();">
												<img src="../../../../netwarelog/repolog/img/impresora.png" border="0"></a></td>
												<td><b>' . $grid['nombre'] . '</b></td></tr></tbody></table></div><br>';

		$catalogo .= '<table class="busqueda" border="1" cellpadding="3" cellspacing="1" width="100%">
												<tr class="tit_tabla_buscar">' . $encabezado . '</tr>			
												<tr class="titulo_filtros" title="Segmento de búsqueda">' . $busquedas . '</tr>
												' . $filas . '</table>';

		$data = array('grid' => $catalogo, 'pagina_anterior' => $link_anterior, 'pagina_siguiente' => $link_siguiente);

		if (!isset($_POST["ajax"]) && !$elimina) {
			$this -> load -> view('grid', $data);
		} else {
			echo $catalogo;
		}
	}

	//////////////////////////////////////////// aqui se cambio
	/*	public function listaMateriales()
	 {
	 $this->load->model('Producto');
	 $data["producto"]=$_POST["producto"];
	 $this->Producto->materiales($_POST["producto"]);
	 $data["lista"]=$this->imprimemateriales($_POST["baseurl"],true,$_POST["producto"],true);
	 $this->load->view('product/listamateriales',$data);
	 }	*/
	public function listaMateriales($tipo)//Aquí cuenta con el valor del option selected
	{
		session_start();
		if (isset($_SESSION["materiales_seleccionados_tipo"])) {
			if ($_SESSION["materiales_seleccionados_tipo"] != $tipo) {
				session_destroy();
				echo "dif";
				exit();
			}
		} else {

		}
		$this -> load -> model('Producto');
		$data["producto"] = $_POST["producto"];
		//Recibe el id del producto por post ajax
		//print_r($_POST["producto"]);
		$this -> Producto -> materiales($_POST["producto"]);
		//Obtengo nombre, idMaterial, compuesto, cantidad, idUnida de la función materiales del modelo Producto
		$data["lista"] = $this -> imprimemateriales($_POST["baseurl"], true, $_POST["producto"], true, $tipo);
		$this -> load -> view('product/listamateriales', $data);
		//Envía array $data a la vista listamateriales
	}

	public function listaMaterialesjson($tipo) {
		$this -> load -> model('Producto');
		$data["producto"] = $_POST["producto"];
		$this -> Producto -> materiales($_POST["producto"]);
	}

	///////////////////////////////////Inicio de función para tabla de costos de materiales /////////////////////////////////

	public function costoMateriales() {
		$tipo = $_POST['tipo'];
		$baseUrl = $_POST['baseUrl'];
		session_start();

		if (isset($_SESSION["materiales_seleccionados_tipo"])) {
			if ($_SESSION["materiales_seleccionados_tipo"] != $tipo) {

				session_destroy();
				echo 'dif';
				exit();
			}
		}

		if ($_POST["actual"] == 1) {
			$materialesCostoJson = $this -> imprimeMaterialesTable();

			echo $materialesCostoJson;
		} else {
			$this -> load -> model('Producto');
			$data["idProducto"] = $_POST['idProducto'];
			$this -> Producto -> materiales($_POST['idProducto']);
			$materialesCostoJson = $this -> imprimeMaterialesTable();
			//$data['listacosto']=$this->imprimeMaterialesTable();
			//	$this->load->view('product/costoproductotable',$data);
			echo $materialesCostoJson;
			//echo $materiales;
			//$data["lista"]=$this->imprimemateriales($_POST["baseUrl"],true,$_POST["idProducto"],true,$tipo);
			//echo json_encode($materiales);
		}
	}

	public function imprimeMaterialesTable() {
		$this -> load -> model('Producto');

		if (isset($_SESSION["materiales_selecionados"])) {
			$arrayMateriales = array();
			$costoUnitario = 0;
			$costoTotal = 0;
			$costoTotalProducto = 0;

			foreach ($_SESSION["materiales_selecionados"] as $nombre_material => $datos) {

				list($idMaterial, $compuesto, $idUnidad, $costo, $opcional, $cantidad, $opcional2, $cantidad2, $opcional3, $cantidad3) = explode("_", $datos);

				// list($idMaterial,$compuesto,$cantidad,$idUnidad,$opcional,$costo) = explode("_",$datos);// Array que contiene los datos guardados en la SESSION en el moodelo

				$unicon = $this -> Producto -> unidadVentaConver($idMaterial);
				$conversion = $this -> Producto -> unidadConversion($idUnidad);

				if ($unicon == $idUnidad) {
					// El producto es normal y existe el menos alguno
					if ($cantidad > 0) {
						$costo_normal = $costo * $cantidad;
					}

					// El producto es opcional y existe el menos alguno
					if ($cantidad2 > 0) {
						$costo_opcional = $costo * $cantidad2;
					}

					// El producto es extra y existe el menos alguno
					if ($cantidad3 > 0) {
						$costo_extra = $costo * $cantidad3;
					}
				} else {
					if ($unicon == 21) {
						$conversion = $this -> Producto -> unidadConversion($unicon);

						// El producto es normal y existe el menos alguno
						if ($cantidad > 0) {
							$costo_normal = ($costo * $cantidad) / $conversion;
						}

						// El producto es opcional y existe el menos alguno
						if ($cantidad2 > 0) {
							$costo_opcional = ($costo * $cantidad2) / $conversion;
						}

						// El producto es extra y existe el menos alguno
						if ($cantidad3 > 0) {
							$costo_extra = ($costo * $cantidad3) / $conversion;
						}

					} else {
						// El producto es normal y existe el menos alguno
						if ($cantidad > 0) {
							$costo_normal = ($costo * $cantidad) / $conversion;
						}

						// El producto es opcional y existe el menos alguno
						if ($cantidad2 > 0) {
							$costo_opcional = ($costo * $cantidad2) / $conversion;
						}

						// El producto es extra y existe el menos alguno
						if ($cantidad3 > 0) {
							$costo_extra = ($costo * $cantidad3) / $conversion;
						}

					}

				}
				//echo $idUnidad.'X';
				//$conversion = 0;
				//$subtotal="$".number_format(($datos->costo*($cantidad / $conversion->conversion)),2,".",",")
				//$costoTotalProducto = $costo * ($cantidad / $conversion);
				//echo $costo.'%'.$datos->costo.'*'.$cantidad.'/'.$conversion;
				//conversion debe ser la unidad del proveedores
				$opc_normal = '';
				$opc_opcional = '';
				$opc_extra = '';

				// El producto es normal y existe el menos alguno
				if ($cantidad > 0) {
					$opc_normal = 'Normal';
				}

				// El producto es opcional y existe el menos alguno
				if ($cantidad2 > 0) {
					$opc_opcional = 'Opcional';
				}

				// El producto es extra y existe el menos alguno
				if ($cantidad3 > 0) {
					$opc_extra = 'Extra';
				}

				$materialFila = array('idMaterial' => $idMaterial, 'nombre_material' => $nombre_material, 'compuesto' => $compuesto, 'opcional_normal' => $opc_normal, 'cantidad' => $cantidad, 'costo_normal' => $costo_normal, 'opcional_opcional' => $opc_opcional, 'cantidad2' => $cantidad2, 'costo_opcional' => $costo_opcional, 'opcional_extra' => $opc_extra, 'cantidad3' => $cantidad3, 'costo_extra' => $costo_extra);

				$arrayMateriales[] = $materialFila;
				//Array vacíose incrementa solo
				$costoTotal = $costoTotal + ($costo_normal + $costo_opcional + $costo_extra);
			}

			$materialesCosto['arrayMateriales'] = $arrayMateriales;
			//Guardo el array de materiales y lavariable decostoTotal dentro de otro array para enviarlo como json
			$materialesCosto['costoTotal'] = $costoTotal;
			//posiciones texto para ser json
			$contadorMateriales = count($_SESSION["materiales_selecionados"]);
			$materialesCosto['contadorMateriales'] = $contadorMateriales;
		}
		return json_encode($materialesCosto);
		//return array ('callback'=>$callback,'costoTotal'=> $costoTotal);//Envío de regreso el array con posición 'callback' & 'costoTotal'
	}

	public function listaMaterialesUnid($tipo) {
		session_start();
		if (isset($_SESSION["materiales_selecionados_tipo"])) {
			if ($_SESSION["materiales_selecionados_tipo"] != $tipo) {
				//session_destroy();
				echo 'dif';
				exit();
			}
		} else {

		}
		$this -> load -> model('Producto');
		//$data["producto"]=$_POST["producto"];
		$data['unidades'] = $this -> Producto -> selunidades();
		//$data["lista"]=$this->imprimemateriales($_POST["baseurl"],true,$_POST["producto"],true,$tipo);
		$this -> load -> view('product/listamaterialesunid', $data);
	}

	////////////////////////////////////////////
	public function uploadfile() {
		$output_dir = "images/productos/";
		if (isset($_FILES["myfile"])) {
			//Filter the file types , if you want.
			if ($_FILES["myfile"]["error"] > 0) {
				echo "Error: " . $_FILES["file"]["error"] . "<br>";
			} else {
				//move the uploaded file to uploads folder;
				move_uploaded_file($_FILES["myfile"]["tmp_name"], $output_dir . $_FILES["myfile"]["name"]);
				echo $output_dir . $_FILES["myfile"]["name"];
			}
		}
	}

	/////////////////////  ***************       	deletematerial	     ***************  /////////////////////////////

	// Elimina un material de la tabla de materiales seleccionados
	// Como parametros recibe:
	// nombre-> nombre del material.
	// id-> id del material

	public function deletematerial() {
		$mensaje['mensaje'] = '';

		session_start();

		$normal = strpos($_POST["id"], '_normal');
		$opcional = strpos($_POST["id"], '_opcional');
		$extra = strpos($_POST["id"], '_extra');

		// Desglosamos el material en variables
		list($idMaterial, $compuesto, $idUnidad, $costo, $opcional, $cantidad, $opcional2, $cantidad2, $opcional3, $cantidad3) = explode("_", $_SESSION["materiales_selecionados"][$_POST["nombre"]]);

		// Si el material es "Normal" regresa su estatus y su cantidad a 0
		if ($normal !== false) {
			// Armamos de nuevo el material restaurando los datos de "Noemal" a su estatus inicial
			$_SESSION["materiales_selecionados"][$_POST["material_nombre"]] = $material . "_" . $unidad . "_" . $idUnidad . "_" . $costo . "_" . "0" . "_" . "0" . "_" . $opcional2 . "_" . $cantidad2 . "_" . $opcional3 . "_" . $cantidad3;

			$mensaje['mensaje'] = 'Se elimino un registro Normal';
		}

		// Si el material es "Opcional" regresa su estatus y su cantidad a 0
		if ($opcional !== false) {
			// Armamos de nuevo el material restaurando los datos de "Opcional" a su estatus inicial
			$_SESSION["materiales_selecionados"][$_POST["material_nombre"]] = $material . "_" . $unidad . "_" . $idUnidad . "_" . $costo . "_" . $opcional . "_" . $cantidad . "_" . "0" . "_" . "0" . "_" . $opcional3 . "_" . $cantidad3;

			$mensaje['material'] .= ' Se elimino un registro Opcional';
			$mensaje['mensaje'] .= ' Se elimino un registro Opcional';
		}

		// Si el material es "Extra" regresa su estatus y su cantidad a 0
		if ($extra !== false) {
			// Armamos de nuevo el material restaurando los datos de "Extra" a su estatus inicial
			$_SESSION["materiales_selecionados"][$_POST["material_nombre"]] = $material . "_" . $unidad . "_" . $idUnidad . "_" . $costo . "_" . $opcional . "_" . $cantidad . "_" . $opcional2 . "_" . $cantidad2 . "_" . "0" . "_" . "0";
			$mensaje['mensaje'] .= ' Se elimino un registro Extra';
		}

		// Lo Desglosamos nuevamente para comprobar si aun existee al menos un "Normal" un "Opcional" o un "Extra"
		list($idMaterial, $compuesto, $idUnidad, $costo, $opcional, $cantidad, $opcional2, $cantidad2, $opcional3, $cantidad3) = explode("_", $_SESSION["materiales_selecionados"][$_POST["nombre"]]);

		// Si no existe al menos un material "Normal" un "Opcional" o un "Extra" elimina el material del arreglo
		if ($cantidad <= 0 && $cantidad2 <= 0 && $cantidad3 <= 0) {
			unset($_SESSION["materiales_selecionados"][$_POST["nombre"]]);

			$mensaje['mensaje'] .= ' Se elimino por completo el material :(';
		}

		// Si no existe ningun material en el arreglo elimina el tipo seleccionado
		if (count($_SESSION["materiales_selecionados"]) == 0) {
			unset($_SESSION["materiales_selecionados_tipo"]);

			$mensaje['mensaje'] .= ' Se elimino materiales_selecionados_tipo';
		}

		echo json_encode($mensaje);
	}

	/////////////////////  ***************       	FIN deletematerial	     ***************  /////////////////////////////

	/////////////////////  ***************       	agregarmaterial	     ***************  /////////////////////////////
	public function agregarmaterial($tipo) {
		$idMaterial = $_POST['material'];
		$mensaje['mensaje'] = 'Si esta';
		$esta = 0;

		// Busca el costo del producto
		$this -> load -> model('Producto');
		$costo = $this -> Producto -> encuentraCosto($idMaterial);

		session_start();
		$_SESSION["materiales_selecionados_tipo"] = $tipo;

		// Si hay materiales seleccionados
		if (isset($_SESSION["materiales_selecionados"])) {
			//Verifica que el valor izq se encuentre en array derecha//Verifica el material seleccionado concuerde con uno guardado
			foreach ($_SESSION["materiales_selecionados"] as $key => $value) {
				// Si existe el nombre del material en el arreglo
				if ($_POST["material_nombre"] == $key) {
					// Existe el material
					$esta = 1;

					list($material, $unidad, $idUnidad, $costo, $opcional, $cantidad, $opcional2, $cantidad2, $opcional3, $cantidad3) = explode("_", $_SESSION["materiales_selecionados"][$_POST["material_nombre"]]);

					// Material Normal
					if ($_POST["opcional"] == 0) {
						$_SESSION["materiales_selecionados"][$_POST["material_nombre"]] = $material . "_" . $unidad . "_" . $idUnidad . "_" . $costo . "_" . $_POST["opcional"] . "_" . ($_POST["cantidad"] + $cantidad) . "_" . $opcional2 . "_" . $cantidad2 . "_" . $opcional3 . "_" . $cantidad3;
					}

					// Material Opcional
					if ($_POST["opcional"] == 1) {
						$_SESSION["materiales_selecionados"][$_POST["material_nombre"]] = $material . "_" . $unidad . "_" . $idUnidad . "_" . $costo . "_" . $opcional . "_" . $cantidad . "_" . $_POST["opcional"] . "_" . ($_POST["cantidad"] + $cantidad2) . "_" . $opcional3 . "_" . $cantidad3;
					}

					// Material Extra
					if ($_POST["opcional"] == 2) {
						$_SESSION["materiales_selecionados"][$_POST["material_nombre"]] = $material . "_" . $unidad . "_" . $idUnidad . "_" . $costo . "_" . $opcional . "_" . $cantidad . "_" . $opcional2 . "_" . $cantidad2 . "_" . $_POST["opcional"] . "_" . ($_POST["cantidad"] + $cantidad3);
					}
				}
			}

			// ////// * * *- -- - - - -   __ _  _ _ _           NOTA           _ _ _ _  _ _  - - - - - -      - - * * * +* * * ///// ////////

			// Se agrega una cadena al final de cada registro simulando posiciones de un objeto

			// La cadena es por defecto "0_0_0_0_0_0"
			// Donde los primeros 2 "0" corresponden al tipo de material normal y su cantidad
			// Donde los segundos 2 "0" corresponden al tipo de material opcional y su cantidad
			// Donde los ulitmos 2 "0" corresponden al tipo de material extra y su cantidad

			// Ejemplo:
			// De tal manera que si queremos "un producto normal", "2 opcionales" y "3 extra".
			// La cadena quedaria de esta manera: "0_1_1_2_2_3".

			// Si queremos "dos productos normales" y "1 extra".
			// La cadena quedaria de esta manera: "0_2_0_0_2_1"

			// Ya que el coodigo es: // Normal ---- 0
			// opcional -- 1
			// extra ----- 2

			// ////// * * *- -- - - - -   __ _  _ _ _      FIN NOTA           _ _ _ _  _ _  - - - - - -      - - * * * +* * * ///// ////////

			// Si el producto no existe
			if ($esta == 0) {
				// Material Normal
				if ($_POST["opcional"] == 0) {
					$_SESSION["materiales_selecionados"][$_POST["material_nombre"]] = $_POST["material"] . "_" . $_POST["unidad"] . "_" . $_POST["idUnidad"] . "_" . $costo . "_" . $_POST["opcional"] . "_" . $_POST["cantidad"] . "_0_0_0_0";
				}

				// Material Opcional
				if ($_POST["opcional"] == 1) {
					$_SESSION["materiales_selecionados"][$_POST["material_nombre"]] = $_POST["material"] . "_" . $_POST["unidad"] . "_" . $_POST["idUnidad"] . "_" . $costo . "_0_0_" . $_POST["opcional"] . "_" . $_POST["cantidad"] . "_0_0";
				}

				// Material Extra
				if ($_POST["opcional"] == 2) {
					$_SESSION["materiales_selecionados"][$_POST["material_nombre"]] = $_POST["material"] . "_" . $_POST["unidad"] . "_" . $_POST["idUnidad"] . "_" . $costo . "_0_0_0_0_" . $_POST["opcional"] . "_" . $_POST["cantidad"];
				}
			}
		}

		// Si esta vacio el arreglo de materiales seleccionados
		else {
			// Material Normal
			if ($_POST["opcional"] == 0) {
				$_SESSION["materiales_selecionados"][$_POST["material_nombre"]] = $_POST["material"] . "_" . $_POST["unidad"] . "_" . $_POST["idUnidad"] . "_" . $costo . "_" . $_POST["opcional"] . "_" . $_POST["cantidad"] . "_0_0_0_0";
			}

			// Material Opcional
			if ($_POST["opcional"] == 1) {
				$_SESSION["materiales_selecionados"][$_POST["material_nombre"]] = $_POST["material"] . "_" . $_POST["unidad"] . "_" . $_POST["idUnidad"] . "_" . $costo . "_0_0_" . $_POST["opcional"] . "_" . $_POST["cantidad"] . "_0_0";
			}

			// Material Extra
			if ($_POST["opcional"] == 2) {
				$_SESSION["materiales_selecionados"][$_POST["material_nombre"]] = $_POST["material"] . "_" . $_POST["unidad"] . "_" . $_POST["idUnidad"] . "_" . $costo . "_0_0_0_0_" . $_POST["opcional"] . "_" . $_POST["cantidad"];
			}
		}

		// Vuelve a cargar los combos de prodcutos para agregar
		echo $this -> imprimemateriales($_POST["baseurl"], FALSE, $_POST["producto"], false, $tipo);
	}

	/////////////////////  ***************       FIN agregarmaterial	     ***************  /////////////////////////////

	public function proceso() {
		$callback = "";
		$callback .= $_POST["proceso"];
		$callback .= $_POST["duracion"];
		$callback .= $_POST["descripcion"];
		$callback .= $_POST["orden"];
		echo $callback;
		return $callback;
	}

	public function agregarproceso() {
		//session_start();
		// $_SESSION["materiales_selecionados_tipo"]=$tipo;
		$count = $_POST["orden"];
		$netapa = $_POST["netapa"];

		$callback .= '<br><div style="float: left;margin:0 0 5px 0;" id="pro_' . $netapa . '_' . $_POST["proceso"] . '_pro">';
		$callback .= '<div style="float: left;"><input name="pronombre" id="p_' . $netapa . '_' . $_POST["proceso"] . '_p" type="hidden" readonly value="' . $_POST["proceso"] . '" class="pro"><input name="pronombreusu" id="p_' . $netapa . '_p" type="text" readonly value="' . $_POST["formatoPro"] . '" class="pro nminputtext"></div>';
		//	$callback.= '<input id="d_'.$netapa.'_d" type="text" readonly value="'.$_POST["descripcion"].'">';
		$callback .= '<div style="float: left;"><textarea name="prodesc" id="d_' . $netapa . '_' . $_POST["proceso"] . '_d" readonly style="width: 250px;" class="nminputtextarea">' . $_POST["descripcion"] . '</textarea></div>';
		$callback .= '<div style="float: left;"><input name="produra" id="t_' . $netapa . '_' . $_POST["proceso"] . '_t" type="hidden" readonly value="' . $_POST["duracion"] . '"></div>';
		$callback .= '<div style="float: left;"><input name="produrausu" id="t1_' . $netapa . '_' . $_POST["proceso"] . '_t1" type="text" readonly value="' . $_POST["formato"] . '" class="nminputtext"></div>';
		$callback .= '<div style="float: left;"><input name="proorden" id="o_' . $netapa . '_' . $_POST["proceso"] . '_o" type="text" readonly value="' . $_POST["orden"] . '" size="5" class="nminputtext"></div>';
		$callback .= '<div style="float: left;"><input type="button" style="cursor:pointer;"  value="Eliminar" onclick="quitaPro(\'pro_' . $netapa . '_' . $_POST["proceso"] . '_pro\',\'' . $netapa . '\',\'' . $_POST["proceso"] . '\');" class="nminputbutton" ></div>';
		$callback .= '</div>';
		/*	$callback="";
		 $callback.='<div style="width: 100%;" title="etapasProduc">
		 <label id="labeletapa">Etapas de Proceso: </label>
		 <br>
		 <input type="text" id="etapanombre" size="30" placeholder="Nombre Etapa">
		 <input type="button" id="button" value="+" onclick="agregaetapa();">
		 <br>
		 <div id="xform">
		 <div>
		 <label id="labeletapa"></label><input type="button" value="-"><input type="button" value="-" onclick="agrupa();">
		 </div>
		 <div style="float: left;">
		 <input type="text" placeholder="nombre de la etapa" id="etaName">
		 </div>
		 <div style="float: left;">
		 <textarea id="etaDesc" style="width: 100%;"" placeholder="Descripcion" ></textarea>
		 </div>
		 <div style="float: left;">
		 <input type="text" id="etaDuracion" value="" placeholder="Duracion">
		 </div>
		 <div style="float: left;">
		 <input type="text" size="5" placeholder="Orden" id="etaOrden">
		 </div>
		 <div style="float: left;">
		 <input type="button" value="+" onclick="AgregaProceso();"><br>
		 </div>
		 </div>
		 </div>'; */
		echo $callback;
		return $callback;

	}

	public function material() {
		$id = $_POST['valor'];

		$this -> load -> model('Producto');
		$xy = $this -> Producto -> listaUnidadesCombo($id);
		//print_r($xy);
		echo json_encode($xy);

	}

	///////////////////////////////////////////// aqui le cambie
	public function imprimemateriales($baseurl, $sesion, $producto, $editar, $tipo) {

		//	if(!$editar){session_start();}
		//echo $tipo."lsdjflodjflfhjSHFJKHSfjksdeeeeeeeeee";
		$callback = '';
		$this -> load -> model('Producto');
		if ($tipo == 2) {
			$lista_productos = $this -> Producto -> listaProductos($producto);
		}
		//modificacion de else if a if ¬¬
		if ($tipo == 4) {
			$lista_productos = $this -> Producto -> listaProductosNN($producto);
		}

		if (isset($_SESSION["materiales_selecionados"])) {
			foreach ($_SESSION["materiales_selecionados"] as $nombre_material => $datos)//Usa la posición en la que está, que es el nombre y lo recorre con $datos
			{

				list($material, $unidad, $idUnidad, $costo, $opcional, $cantidad, $opcional2, $cantidad2, $opcional3, $cantidad3) = explode("_", $datos);
				// list($material,$unidad,$cantidad,$idUnidad,$opcional,$costo)=explode("_",$datos);

				if ($cantidad > 0) {
					$callback .= '<input id="c_' . $material . '" type="hidden" style="width:60px;" readonly value="' . $cantidad . '" class="nminputtext">';
					$callback .= '<input id="p_' . $material . '" type="hidden" style="width:240px;" readonly value="' . $nombre_material . '" class="nminputtext">';
					$callback .= '<input id="u_' . $material . '" type="hidden" style="width:100px;" readonly value="' . $unidad . '" class="nminputtext">';
					$callback .= '<input id="x_' . $material . '" type="hidden" style="width:100px;" readonly value="' . $opcional . '" class="nminputtext">';
					$callback .= '<input id="b_' . $material . '"  type="hidden" value="-" class="pb nminputbutton" onclick="Removematerial(' . $material . ',\'' . $nombre_material . '\',\'' . $baseurl . '\');">';
					$callback .= '<input type="hidden" id="costo" value="' . $costo . '">';
				}

				if ($cantidad2 > 0) {
					$callback .= '<input id="c_' . $material . '" type="hidden" style="width:60px;" readonly value="' . $cantidad2 . '" class="nminputtext">';
					$callback .= '<input id="p_' . $material . '" type="hidden" style="width:240px;" readonly value="' . $nombre_material . '" class="nminputtext">';
					$callback .= '<input id="u_' . $material . '" type="hidden" style="width:100px;" readonly value="' . $unidad . '" class="nminputtext">';
					$callback .= '<input id="x_' . $material . '" type="hidden" style="width:100px;" readonly value="' . $opcional2 . '" class="nminputtext">';
					$callback .= '<input id="b_' . $material . '"  type="hidden" value="-" class="pb nminputbutton" onclick="Removematerial(' . $material . ',\'' . $nombre_material . '\',\'' . $baseurl . '\');">';
					$callback .= '<input type="hidden" id="costo" value="' . $costo . '">';
				}

				if ($cantidad3 > 0) {
					$callback .= '<input id="c_' . $material . '" type="hidden" style="width:60px;" readonly value="' . $cantidad3 . '" class="nminputtext">';
					$callback .= '<input id="p_' . $material . '" type="hidden" style="width:240px;" readonly value="' . $nombre_material . '" class="nminputtext">';
					$callback .= '<input id="u_' . $material . '" type="hidden" style="width:100px;" readonly value="' . $unidad . '" class="nminputtext">';
					$callback .= '<input id="x_' . $material . '" type="hidden" style="width:100px;" readonly value="' . $opcional3 . '" class="nminputtext">';
					$callback .= '<input id="b_' . $material . '"  type="hidden" value="-" class="pb nminputbutton" onclick="Removematerial(' . $material . ',\'' . $nombre_material . '\',\'' . $baseurl . '\');">';
					$callback .= '<input type="hidden" id="costo" value="' . $costo . '">';
				}
			}
			$callback .= '<input type="hidden" id="num_materiales" value="' . count($_SESSION["materiales_selecionados"]) . '">';
			$callback .= '</div class="tableCosto">';

		}
		return $callback;
	}

	public function unidadesCompra() {

		$this -> load -> model('Producto');
		$ids = $_POST["ids"];

		$result = $this -> Producto -> unidadesCompra($ids);

		echo json_encode($result);
	}

	public function proveedores() {
		$idProducto = $_POST['id'];
		$select_prv = '';
		//echo $idProducto.'jjjjjjjjjjj';
		$select_prv .= '<select id="proveedor" class="nminputselect">';
		$select_prv .= '<option value="">-Seleccione-</option>';
		foreach ($this->Producto->buscaProveedor() as $prov) :
			//echo $prov->idPrv."XX";
			//echo $data["datos_producto"][0]->idProveedor."yy";
			//	if($z[0]->idPrv==$prov->idPrv)
			//	{
			$select_prv .= '<option selected value="' . $prov -> idPrv . '">' . utf8_decode($prov -> razon_social) . '</option>';
			//		}
			//		else
			//		{
			//			$select_prv .= '<option value="'.$prov->idPrv.'">'.utf8_decode($prov->razon_social).'</option>';
			//}
		endforeach;

		echo $select_prv;

		return;
	}

	public function eliminaProve2() {
		$idProducto = $_POST['idproducto'];
		$idPrv = $_POST['idPrv'];
		$this -> load -> model('Producto');
		//echo 'producto='.$idProducto.'/prove='.$idPrv.'X';

		$this -> Producto -> eliminaProve2($idProducto, $idPrv);

		return;
	}

	public function eliminaPrecio2() {
		$id = $_POST['id'];
		echo $id;
		$this -> load -> model('Producto');
		//echo 'producto='.$idProducto.'/prove='.$idPrv.'X';
		$this -> Producto -> eliminaPrecio2($id);

		return;
	}

	public function cambiaprecio() {
		$idprecio = $_POST['id'];
		$precio = $_POST['precio'];
		$descripcion = $_POST['descripcion'];
		$descuento = $_POST['descuento'];
		echo $precio . 'X' . $descripcion . 'X' . $descuento . 'X';
		$this -> load -> model('Producto');
		//echo 'producto='.$idProducto.'/prove='.$idPrv.'X';
		$this -> Producto -> cambiaprecio($idprecio, $precio, $descripcion, $descuento);

		return;
	}

////////////////////// ************* --		agregar_material	--***************** ////////////////////////////////
//////// Agrega un material a un array de sesion.
	// Como parametros puede recibir:
		// id-> id del material
		// cantidad-> cantidad de materiales necesarios
		// unidad-> gramo, kilo, tonelada, unidada, area, etc.
		// tipo-> normal, opcional o extra

	public function agregar_material($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
		$costo = 0;
		session_start();

	// Si no hay materiales agregados crea un array para agregarlos
		$_SESSION["materiales_agregados"] = (!empty($_SESSION["materiales_agregados"])) ? $_SESSION["materiales_agregados"] : Array();

	// Consultamos para traer el costo, nombre, unidad
		$this -> load -> model('Producto');
		$producto = $this -> Producto -> listar_materiales($objeto);

	// ** Compara si la unidad de venta es la misma que la de compra para calcular el costo
	// Si la unidad de venta es la misma que la de compra basta con multiplicar cantidad por costo para sacar el costo
		if ($producto[0] -> idunidad == $producto[0] -> idunidadCompra) {
			$costo = ($producto[0] -> costo);

	// Si la unidad de venta es diferente que la de compra realiza una conversion
	// para sacar el equivalente
		} else {
		// - -- - -- - - --	-	-		**		 NOTA		**			- - - - - -- - - -- - 	//

			//** Dividimos el valor de compra entre el de venta para sacar la conversion
			// Ejem.
			// Kilo -> 1'000,000   // El valor de un kilo son 1'000,000 miligramos
			// Gramo -> 1,000   // El valor de un kilo son 1,000 miligramos

			// Para calcular la diferencia en miligramos dividimos  el valor de compra entre el de venta

			// 1000000/1000=1000	(kilo/gramo es igual a 1000 miligramos)

		// - -- - -- - - --	-	-		**		FIN NOTA		**			- - - - - -- - - -- - 	//

		// Calcula los valores de compra y de venta(kilo, gramo, miligramos, etc.)
			$valor_compra = $this -> Producto -> unidadConversion($producto[0] -> idunidadCompra);
			$valor_venta = $this -> Producto -> unidadConversion($producto[0] -> idunidad);

		// Obtenemos el equivalente de la conversion
			$conversion = $valor_compra / $valor_venta;

		// $producto[0]->idunidadCompra==21 significa que La unidad es litro
			if ($producto[0] -> idunidadCompra == 21) {
			// Calcula la conversion en base a litros
				$conversion = $this -> Producto -> unidadConversion($producto[0] -> idunidadCompra);
				$costo = ($producto[0] -> costo) / $conversion;

		// La unidad puede ser kilo, gramo, unidad, etc.
			} else {
				$costo = ($producto[0] -> costo) / $conversion;
			}
		}

	// Armamos el array
		$material -> idProducto = $producto[0] -> idProducto;
		$material -> idunidad = $producto[0] -> idunidad;
		$material -> material = $producto[0] -> nombre;
		$material -> unidad = $producto[0] -> unidad;
		$material -> costo = $costo;
		$material -> cantidad = $objeto['cantidad'];
		$material -> tipo = $objeto['tipo'];

	// Buscamos el material en el array
		foreach ($_SESSION["materiales_agregados"] as $key => $value) {
		// Si se encuentra el objeto en el array le suma la cantidad y el costo
			if (($material -> idProducto == $value -> idProducto) && ($material -> tipo == $value -> tipo)) {
				$encontrado = 1;
			// Sumamos la cantidad existente con la nueva cantidad
				$_SESSION["materiales_agregados"][$key] -> cantidad = ($_SESSION["materiales_agregados"][$key] -> cantidad + $material -> cantidad);

			// Calculamos el costo
				$_SESSION["materiales_agregados"][$key] -> costo = ($_SESSION["materiales_agregados"][$key] -> cantidad * $costo);
			}
		}

	// Si el material no se encuentra en el array lo agregamos
		if ($encontrado == 0) {
			$material -> costo = $costo * $objeto['cantidad'];
			
		// Agregamos el material al arreglo de sesion
			array_push($_SESSION["materiales_agregados"], $material);
		}

	// Regresa al ajax el resultado
		echo json_encode($_SESSION["materiales_agregados"]);
	}

////////////////////// ************* --		FIN agregar_material	--***************** ////////////////////////////////

////////////////////// ************* --		buscar_unidad	--***************** ////////////////////////////////
//////// Realiza una consulta que devuelve la unidad del material
	// Como parametros puede recibir:
		// id-> id del material

	public function buscar_unidad($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

	// Carga el modelo de Producto
		$this -> load -> model('Producto');

	// Consulta el id de unidad y el nombre del compuesto del material
		$material = $this -> Producto -> buscar_unidad($objeto);

	// Regresa al ajax el resultado
		echo json_encode($material);
	}

////////////////////// ************* --		FIN buscar_unidad	-- ***************** ////////////////////////////////

	////////////////////// ************* --		listar_materiales	-- ***************** ////////////////////////////////
	//////// Realiza una consulta que devuelve un array con los materiales
	// Como parametros puede recibir:
	// id-> id del material
	// tipo-> tipo de producto seleccionado:
	// producto(1), producir producto(2), material de produccion(3), kit de productos(4)
	// producto de consumo(5), servicios(6)

	public function listar_materiales($objeto) {
		// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
		// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

		// Carga el modelo de Producto
		$this -> load -> model('Producto');

		// Realizamos una consulta para traer el listado de los materiales
		$materiales = $this -> Producto -> listar_materiales($objeto);

		// Regresa al ajax el resultado
		echo json_encode($materiales);
	}

	////////////////////// ************* --		FIN listar_materiales	--***************** ////////////////////////////////

	////////////////////// ************* --		eliminar_material	--***************** ////////////////////////////////
	//////// Realiza una consulta que devuelve la unidad del material
	// Como parametros puede recibir:
	// id-> id del material

	public function eliminar_material($objeto) {
		// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
		// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

		session_start();

		$indice = $objeto['id'];

		// Buscamos el material en el array
		foreach ($_SESSION["materiales_agregados"] as $key => $value) {
			// Si encuentra el material lo elimina del array
			if ($key == $objeto['id']) {
				// Captura el costo para actualizar el total
				$result -> restar = $value -> costo;
				unset($_SESSION["materiales_agregados"][$key]);
			}
		}

		// Si el indice existe en el array significa que no se elimino
		if (array_key_exists($objeto['id'], $_SESSION["materiales_agregados"])) {
			// Si existe manda un mensaje de error
			$result -> mensaje = 'Algo salio mal';
		} else {
			$result -> mensaje = 1;
			//Todo bien :D
		}

		// Regresa al ajax el resultado
		echo json_encode($result);
	}

	////////////////////// ************* --		FIN eliminar_material	-- ***************** ////////////////////////////////
}
