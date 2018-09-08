<?php
//ini_set('display_errors', 1);
//Carga la funciones comunes top y footer
require('common.php');

//Carga el modelo para este controlador
require("models/producto.php");

class Producto extends Common
{
    public $ProductoModel;

    function __construct()
    {
        //Se crea el objeto que instancia al modelo que se va a utilizar

        $this->ProductoModel = new ProductoModel();
        $this->ProductoModel->connect();
    }

    function __destruct()
    {
        //Se destruye el objeto que instancia al modelo que se va a utilizar
        $this->ProductoModel->close();
    } 
    function indexImportarProductos(){
        $vista_importar = "indexImportarProductos";
        require('views/producto/importar_products.php');
    }
   function indexSat(){
        //$vista_importar = "indexImportarProductos";
        $departamento = $this->ProductoModel->depFamLin();
        $productos = $this->ProductoModel->productosSat(0);
        require('views/producto/productosat.php');
    }
    function indexMonedero(){
        //$vista_importar = "indexImportarProductos";
        $departamento = $this->ProductoModel->depFamLin();
        $productos = $this->ProductoModel->productosMonedero(0);
        $politicas = $this->ProductoModel->politicas();
        require('views/producto/productomonedero.php');
    }
    function indexGridProductos(){
        $limit = 'limit 0,100';
        $productosGrid = $this->ProductoModel->indexGridProductos($limit);
        
        require('views/producto/gridProductos.php');
    }
    function mostrarMas(){
        $ran = floatval($_POST['rango']);

        $limit = 'limit '.$ran.' , 100';
        //echo $limit;
        $productosGrid = $this->ProductoModel->indexGridProductos($limit);

        echo json_encode($productosGrid);
    }
    function mostrarTodos() {
        $ran = floatval($_POST['rango']);

        $limit = 'limit '.$ran.' , '.PHP_INT_MAX;
        //echo $limit;
        $productosGrid = $this->ProductoModel->indexGridProductos($limit);

        echo json_encode($productosGrid);
    }
    function indexProdSuc(){
        $sucursal = $this->ProductoModel->sucursal();
        $productos = $this->ProductoModel->productosSuc(0);
        $politicas = $this->ProductoModel->politicas();
        require('views/producto/prodsuc.php');
    }
    function getSucuPro(){
        $id = $_POST['idProducto'];
        $info = $this->ProductoModel->getSucuPro($id);
        echo json_encode($info);

    }
    function agregaAsucursal(){
        $idProducto = $_POST['idProducto'];
        $sucursal = $_POST['sucursal'];

        $res = $this->ProductoModel->agregaAsucursal($idProducto,$sucursal);
        echo json_encode($res);

    }
    function vinculacionMasiva(){
        $cadena = $_POST['cadena'];
        $idSucursal = $_POST['sucursal'];
        $monedero = $_POST['monedero'];

        $res = $this->ProductoModel->vinculacionMasiva($cadena,$idSucursal,$monedero);

        echo json_encode($res);
    }
    function vinculacionMasivaMonedero(){
        $cadena = $_POST['cadena'];
        $monedero = $_POST['monedero'];

        $res = $this->ProductoModel->vinculacionMasivaMonedero($cadena,$monedero);

        echo json_encode($res);
    }
    function eliminaVinculacion(){
        $idProducto = $_POST['idProducto'];
        $idSuc = $_POST['idSuc'];

        $res = $this->ProductoModel->eliminaVinculacion($idProducto,$idSuc);
        echo json_encode($res);
    }
    function filtraProds(){
        $sucursal = $_POST['sucursal'];
        $res = $this->ProductoModel->productosSuc($sucursal);
        echo $res;
    }
    function index()
    {   
        //Imnsusmos formulacion
        session_start();
        unset($_SESSION['insumos_producto']);
        $objeto=null;
        
        $datos['unidades'] = $this ->ProductoModel->listar_unidades($objeto);



        $idProducto = $_GET['idProducto'];
        $datos['insumos'] = $this->ProductoModel->listar_insumos($idProducto);
        $proveedores = $this->ProductoModel->ListaProveedor();
        $departamento = $this->ProductoModel->depFamLin();
        $costeo = $this->ProductoModel->costeoList();
        $caracteristicas = $this->ProductoModel->caracteristicas();
        $impuestos = $this->ProductoModel->getImpuestos();
        $contador_impuestos = $impuestos['total'];
        $impuestosDefault = $this->ProductoModel->impuestosConfig();
        $unidades = $this->ProductoModel->unidades();
        $moneda = $this->ProductoModel->moneda();
        $comision = $this->ProductoModel->comision(  );

        $datos['insel'] = $this->ProductoModel->explosion($idProducto);
        if($datos['insel']['total']>0){
            $insel = json_encode($datos['insel']['rows']);
            //var_dump($insel);
        }else{
            $insel=0;
        }

        $sucursal = $this->ProductoModel->sucursal();

        //print_r($impuestosDefault);
        
        if($idProducto!=''){
            $datosProducto = $this->ProductoModel->datosProducto($idProducto);
            $editable = $this->ProductoModel->tieneRegistros($idProducto);
            $caractE = $this->ProductoModel->tieneCaract($idProducto);            
        } else {
            $divTmp = $this->ProductoModel->divisionesSat("1");
            $datosProducto  = [ 'division_sat' => $divTmp['divisiones'] ];
        }

        require('views/producto/formProducto.php');
    }
    function nombreProvedor(){
        $idPrv = $_POST['idPrv'];
        $nombre  = $this->ProductoModel->nombreProvedor($idPrv);
      // var_dump($nombre);
        echo json_encode($nombre);
    }
    function desactiva(){
        $idProducto = $_POST['idProducto'];

        $desactiva = $this->ProductoModel->desactiva($idProducto);
        //print_r($descativa);
        echo json_encode($desactiva);
    }
    function activa(){
        $idProducto = $_POST['idProducto'];

        $activa = $this->ProductoModel->activa($idProducto);
        //print_r($descativa);
        echo json_encode($activa);
    }
    function nombreCaracteristica(){
        $idC = $_POST['idCara'];
        $nombreCa  = $this->ProductoModel->nombreCaracteristica($idC);
        //var_dump($nombreCa);
        echo json_encode($nombreCa);
    }
    function guardaProducto(){
        $objeto = $_POST['formulacion'];
        $box = $_POST['box'];
        $boxPeso = $_POST['boxPeso'];
        $boxAlto = $_POST['boxAlto'];
        $boxLargo = $_POST['boxLargo']; 
        $boxAncho = $_POST['boxAncho']; 


        $idProducto = $_POST['idProducto'];
        $nombre = $_POST['nombre'];
        $codigo = $_POST['codigo'];
        $precio = $_POST['precio'];
        $deslarga = $_POST['deslarga'];
        $descorta = $_POST['descorta'];
        $uniCompra = $_POST['uniCompra'];
        $uniVenta = $_POST['uniVenta'];
        $proveedores = $_POST['proveedores'];
        $departamento = $_POST['departamento'];
        $familia = $_POST['familia'];
        $linea = $_POST['linea'];
        $maximo = $_POST['maximo'];
        $minimo = $_POST['minimo'];
        $tipoProd = $_POST['tipoProd'];
        $costeo = $_POST['costeo'];
        $cartrt = $_POST['cartrt'];
        $listPreciosStr = $_POST['listPreciosStr'];
        $preciosSucursal = $_POST['preciosSucursal'];
        $listaImpuestos = $_POST['listaImpuestos'];
        $comision = $_POST['comision'];
        $moneda = $_POST['moneda'];
        $lotes = $_POST['lotes'];
        $series = $_POST['series'];
        $pedimentos = $_POST['pedimentos'];
        $tipoCom = $_POST['tipoCom'];
        $costoServicio = $_POST['costoServicio'];
        $imagen = $_POST['imagen'];
        $iepsForm = $_POST['iepsForm'];
        $configComision = $_POST['configComision'];
        $precioBaseComision = $_POST['precioBaseComision'];
        $porcentajeBaseComision = $_POST['porcentajeBaseComision'];
        $tipoComision = $_POST['tipoComision'];
        $resena = $_POST['resena'];
        $link = $_POST['link'];
        $edicion = $_POST['edicion'];

        $divisionSat = $_POST['divisionSat'];
        $grupoSat = $_POST['grupoSat'];
        $claseSat = $_POST['claseSat'];
        $claveSat = $_POST['claveSat']; 
        $consigna = $_POST['consigna'];        /*echo '('.$lotes.')';
         echo '('.$series.')';
          echo '('.$pedimentos.')';*/
        /*echo $listaImpuestos .'se para proceso'; 
        exit(); */
        if($idProducto!=''){
            $producto = $this->ProductoModel->updateProducto($idProducto,$nombre,$codigo,$precio,$deslarga,$descorta,$departamento,$familia,$linea,$maximo,$minimo,$tipoProd,$costeo,$proveedores,$uniCompra,$uniVenta,$cartrt,$listPreciosStr,$preciosSucursal,$listaImpuestos,$comision,$moneda,$lotes,$series,$pedimentos,$tipoCom,$costoServicio,$imagen,$iepsForm,$configComision,$precioBaseComision,$porcentajeBaseComision,$tipoComision,$resena,$link,$edicion,$divisionSat,$grupoSat,$claseSat,$claveSat,$consigna,$box,$boxPeso,$boxAlto,$boxLargo,$boxAncho,$objeto);
                
        }else{
            $producto = $this->ProductoModel->guardaProducto($idProducto,$nombre,$codigo,$precio,$deslarga,$descorta,$departamento,$familia,$linea,$maximo,$minimo,$tipoProd,$costeo,$proveedores,$uniCompra,$uniVenta,$cartrt,$listPreciosStr,$preciosSucursal,$listaImpuestos,$comision,$moneda,$lotes,$series,$pedimentos,$tipoCom,$costoServicio,$imagen,$iepsForm,$configComision,$precioBaseComision,$porcentajeBaseComision,$tipoComision,$resena,$link,$edicion,$divisionSat,$grupoSat,$claseSat,$claveSat,$consigna,$box,$boxPeso,$boxAlto,$boxLargo,$boxAncho,$objeto);
        }
        

        echo json_encode($producto);
    }
    function listaParametros(){
        $idLista = $_POST['idLista'];
        $param = $this->ProductoModel->listaParametros($idLista);

        echo json_encode($param);
    }
    function getNewImpuesto(){
        $idImp = $_POST['idImp'];
        $imp = $this->ProductoModel->getNewImpuesto($idImp);

        echo json_encode($imp);

    }
    function buscaFam(){
        $dep = $_POST['dep'];
        $familias = $this->ProductoModel->buscaFam($dep);
        echo json_encode($familias);
    }
    function buscaLinea(){
        $fam = $_POST['fam'];
        $lineas = $this->ProductoModel->buscaLinea($fam);
        echo json_encode($lineas);
    }
    /*function productoUpdate(){

        $idProducto = $_GET['idProducto'];
       
        $datosProducto = $this->ProductoModel->datosProducto($idProducto);
        var_dump($datosProducto);
        exit();
        require('views/producto/formProducto.php');
    } */
    function uploadfile() {
        $output_dir = "images/productos/";

        if (isset($_FILES["myfile"])) {
            //Filter the file types , if you want.
            if ($_FILES["myfile"]["error"] > 0) {
                echo "Error: " . $_FILES["file"]["error"] . "<br>";
            } else {
                //move the uploaded file to uploads folder;
                move_uploaded_file($_FILES["myfile"]["tmp_name"], $output_dir . $_FILES["myfile"]["name"]);
                $output_dir . $_FILES["myfile"]["name"];
            }
            echo json_encode( array('direccion' => $output_dir . $_FILES["myfile"]["name"]));
        }
    }

    function subeLayout()
    {
        $directorio = "importacion/";
        if ($v == 1) {
            $vista_import = "indexImportarProductos";
        } else {
            $vista_import = "indexGridProductos";
        }


        if (isset($_FILES["layout"])) 
        {
                if($_FILES['layout']['name'])
                {
                    if (move_uploaded_file($_FILES['layout']['tmp_name'], $directorio.basename("productos_temp.xls" ) )) 
                    {
                        echo "Validando archivo...<br/>";
                        include($directorio."import_productos.php");
                    } 
                    else 
                    {
                        echo "No se subio el archivo de Productos <br/>";
                    }
                }
        }
    }
    function subeLayoutPrecios()
    {   
        $directorio = "importacion/";
        if (isset($_FILES["layout"])) 
        {
                if($_FILES['layout']['name'])
                {
                    if (move_uploaded_file($_FILES['layout']['tmp_name'], $directorio.basename("productos_tempPrices.xls" ) )) 
                    {
                        echo "Validando archivo...<br/>";
                        include($directorio."actualiza_precios.php");
                    } 
                    else 
                    {
                        echo "No se subio el archivo de Productos <br/>";
                    }
                }
        }
    }
    function subeLayoutCxc(){
        $directorio = "importacion/";
        if (isset($_FILES["layout"])) 
        {
                if($_FILES['layout']['name'])
                {
                    if (move_uploaded_file($_FILES['layout']['tmp_name'], $directorio.basename("cxc.xls" ) )) 
                    {
                        echo "Validando archivo...<br/>";
                        include($directorio."actualiza_cxc.php");
                    } 
                    else 
                    {
                        echo "No se subio el archivo de Productos <br/>";
                    }
                }
        }
    }

    function inactivarProdLay()
    {
        echo $this->ProductoModel->inactivarProdLay($_POST['id'],$_POST['num']);
    }

    function reactivarProdLay()
    {
        echo $this->ProductoModel->reactivarProdLay($_POST['id'],$_POST['num']);
    }

    function confirmarProdLay()
    {
        $this->ProductoModel->confirmar($_POST['num']);
        $this->ProductoModel->borrar(98);
    }

    function buscarPrecioProveedor(){
        $datos = [
            'producto'         => filter_input(INPUT_GET, 'producto', FILTER_SANITIZE_NUMBER_INT, FILTER_VALIDATE_INT),
            'patronProveedor'   => filter_input(INPUT_GET, 'patronProveedor', FILTER_SANITIZE_STRING)
        ];

        $res = $this->ProductoModel->buscarPrecioProveedor($datos);
        echo json_encode($res);
    }

    function cancelar() {
        $this->ProductoModel->borrar(99);
        $this->ProductoModel->borrar(98);
    }
    function divisionesSat(){
        $tipo = $_POST['tipo'];
        $res = $this->ProductoModel->divisionesSat($tipo);
        echo json_encode($res);
    }
    function gruposSat(){
        $div = $_POST['division'];
        $res = $this->ProductoModel->gruposSat($div);
        echo json_encode($res);
    }
    function claseSat(){
        $div = $_POST['grupo'];
        $res = $this->ProductoModel->claseSat($div);
        echo json_encode($res);
    }
    function claveSat(){
        $div = $_POST['clase'];
        $res = $this->ProductoModel->claveSat($div);
        echo json_encode($res);
    }
    function vinculacionMasivaSat(){
        $cadena = $_POST['cadena'];
        $clave = $_POST['clave'];
        $division = $_POST['division'];
        $grupo = $_POST['grupo'];
        $clase = $_POST['clase'];
        $res = $this->ProductoModel->vinculacionMasivaSat($cadena,$clave,$division,$grupo,$clase);
        echo json_encode($res);
    }
    function prodDepa(){
        $depa = $_POST['depa'];
        $familia = $_POST['familia'];
        $linea = $_POST['linea'];
        $res = $this->ProductoModel->prodDepa($depa,$familia,$linea);
        echo json_encode($res);

    }

    function agregar_insumos_producto($objeto) {
    // Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
    // Si no conserva su valor normal
        $objeto=(empty($objeto))?$_REQUEST:$objeto;

        session_start();

        if (!empty($_SESSION['insumos_producto'][$objeto['id']]))
                unset($_SESSION['insumos_producto'][$objeto['id']]);
        else
            $_SESSION['insumos_producto'][$objeto['id']]=$objeto;


        //echo json_encode($_SESSION['insumos_producto']);

    // carga la vista para listar las reservaciones
        //require('views/recetas/listar_parametros_agregados.php');
        require('views/producto/listar_insumos_producto.php');
    }

    function asignar_cant_req($objeto) {
    // Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
    // Si no conserva su valor normal
        $objeto = (empty($objeto)) ? $_REQUEST : $objeto;
        session_start();

        $_SESSION['insumos_producto'][$objeto['id']]['cantidad'] = $objeto['cantidad'];

        echo json_encode($_SESSION['insumos_producto']);
    }




}
?>
