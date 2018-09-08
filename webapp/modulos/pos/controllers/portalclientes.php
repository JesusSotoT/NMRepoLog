<?php
//ini_set('display_errors', 1);
//Carga la funciones comunes top y footer
require('common.php');

//Carga el modelo para este controlador
require("models/cliente.php");

class Portalclientes extends Common
{
    public $ClienteModel;

    function __construct()
    {
        //Se crea el objeto que instancia al modelo que se va a utilizar

        $this->ClienteModel = new ClienteModel();
        $this->ClienteModel->connect();
    }

    function __destruct()
    {
        //Se destruye el objeto que instancia al modelo que se va a utilizar
        $this->ClienteModel->close();
    } 

    function listaCargosFacturas()
    {
        $datos = array();
        $_POST['cobrar_pagar']=0;
        $listaCargos = $this->ClienteModel->listaCargos($_POST['idPrvCli'],$_POST['cobrar_pagar']);
        while($l = $listaCargos->fetch_assoc())
        {
            $vencimiento = new DateTime($l['fecha_pago']);
            if(intval($l['diascredito']))
                $vencimiento->add(new DateInterval('P'.$l['diascredito'].'D'));

            $abonado = (floatval($l['cargo']) * floatval($l['tipo_cambio'])) - floatval($l['saldo']);
            
            $datetime1 = new DateTime(date('Y-m-d'));
                $datetime2 = $vencimiento;
                $interval = $datetime1->diff($datetime2);
                $difer = $interval->format('%R%a');

                if(intval($difer) >= 61)//Al corriente
                    $estatus_m = "<center><span style='display:block !important;width:100% !important;' class='label label-success'>Al Corriente</span></center>";

                if(intval($difer) <= 60 && intval($difer) >= 0)//por vencer
                    $estatus_m = "<center><span style='display:block !important;width:100% !important;' class='label label-warning'>Por vencer</span></center>";

                if(intval($difer) < 0)//vencido
                    $estatus_m = "<center><span style='display:block !important;width:100% !important;' class='label label-danger'>Cuenta Vencida</span></center>";     

                if(number_format($l['saldo'],2) <= 0)//saldada
                    $estatus_m = "<center><span style='display:block !important;width:100% !important;' class='label label-default'>Cuenta Saldada</span></center>";                            

            array_push($datos,array(
                        'fech_cargo' => $l['fecha_pago'],
                        'fecha_venc' => $vencimiento->format('Y-m-d'),
                        'concepto' => $l['concepto'],
                        'monto' => "$ ".number_format($l['cargo'],2)." ".$l['moneda'],
                        'abonado' => "$ ".number_format($abonado,2)." MXN",
                        'actual' => "<span class='actual' cantidad='".$l['saldo']."'>$ ".number_format($l['saldo'],2)." MXN</span>",
                        'estatus' => $estatus_m,
                        'ov' => '-'
                            ));
        }

        $listaFacturas = $this->ClienteModel->listaFacturas($_POST['idPrvCli'],$_POST['cobrar_pagar']);
        while($l = $listaFacturas->fetch_assoc())
        {
            //$foliosFac = $this->CuentasModel->foliosFac($l['id_oc']);
            //$file     = "../cont/xmls/facturas/temporales/".$l['xmlfile'];
            //$texto    = file_get_contents($file);
            //$texto    = preg_replace('{<Addenda.*/Addenda>}is', '<Addenda/>', $texto);
            //$texto    = preg_replace('{<cfdi:Addenda.*/cfdi:Addenda>}is', '<cfdi:Addenda/>', $texto);
            //$xml  = new DOMDocument();
            //$xml->loadXML($texto);
            //$xp = new DOMXpath($xml);
            //$desc = $this->getpath("//@descripcion");
            $vencimiento = new DateTime($l['fecha_factura']);
            if(intval($l['diascredito']))
                $vencimiento->add(new DateInterval('P'.$l['diascredito'].'D'));
            $desc = $l['desc_concepto'];
            $datetime1 = new DateTime(date('Y-m-d'));
                $datetime2 = $vencimiento;
                $interval = $datetime1->diff($datetime2);
                $difer = $interval->format('%R%a');

                if(intval($difer) >= 61)//Al corriente
                    $estatus_m = "<center><span style='display:block !important;width:100% !important;' class='label label-success'>Al Corriente</span></center>";

                if(intval($difer) <= 60 && intval($difer) >= 0)//por vencer
                    $estatus_m = "<center><span style='display:block !important;width:100% !important;' class='label label-warning'>Por vencer</span></center>";

                if(intval($difer) < 0)//vencido
                    $estatus_m = "<center><span style='display:block !important;width:100% !important;' class='label label-danger'>Cuenta Vencida</span></center>";


            $estilo = '';
            if(strtotime($vencimiento->format('Y-m-d')) < strtotime(date()))
                $estilo = "style='color:red;'";


                $nuevoImp = floatval($l['importe_pesos']);


            $saldo = $nuevoImp - floatval($l['pagos']);
            if(number_format($saldo,2) <= 0)//saldada
                    $estatus_m = "<center><span style='display:block !important;width:100% !important;' class='label label-default'>Cuenta Saldada</span></center>";        
                                                            
            

                $abonado = floatval($nuevoImp) - floatval($saldo);
                
                if(intval($_POST['cobrar_pagar']))
                {
                    $url = "index.php?c=compras&f=ordenes&id_oc=".$l['id_oc']."&v=1";
                    $idovc = $l['id_oc'];
                }
                else
                {
                    if(intval($l['origen']) == 1)
                        $url = "index.php?c=ventas&f=ordenes&id_oventa=".$l['id_oventa']."&v=1";
                    if(intval($l['origen']) == 2)
                        $url = "../pos/ticket.php?idventa=".$l['id_oventa']."&print=0";
                    $idovc = $l['id_oventa'];
                }
                    
                        
                array_push($datos,array(
                            'fech_cargo' => $l['fecha_factura'],
                            'fecha_venc' => $vencimiento->format('Y-m-d'),
                            'concepto' => $l['folio']." $desc",
                            'monto' => "$ ".number_format($l['imp_factura'],2)." ".$l['Moneda'],
                            'abonado' => "$ ".number_format($abonado,2)." MXN",
                            'actual' => "<span class='actual' cantidad='$saldo'>$ ".number_format(round($saldo,2),2)." MXN</span>",
                            'estatus' => $estatus_m,
                            'ov' => "<a href='$url' target='_blank'>$idovc</a>"
                                ));
            
        }
        echo json_encode($datos);
    }

    function guardaCliente(){
         $idCliente = $_POST['idCliente'];
         //$codigo = $_POST['codigo'];
         $nombre = $_POST['nombre'];
         $tienda = $_POST['tienda'];
         $numint = $_POST['numint']; 
         $numext = $_POST['numext'];
         $direccion = $_POST['direccion'];
         $colonia = $_POST['colonia']; 
         $cp = $_POST['cp'];
         $pais = $_POST['pais'];
         $estado = $_POST['estado'];  
         $municipio = $_POST['municipio'];
         $email = $_POST['email'];
         $celular = $_POST['celular'];
         $tel1 =  $_POST['tel1'];
         $tel2 = $_POST['tel2'];
         $ciudad = $_POST['ciudad'];

         // $cumpleanos = $_POST['cumpleanos'];
         // $rfc = $_POST['rfc'];
         // $curp = $_POST['curp'];
         // $diasCredito = $_POST['diasCredito'] ;
         // $limiteCredito = $_POST['limiteCredito'];
         // $moneda = $_POST['moneda'];
         // $listaPrecio = $_POST['listaPrecio'];
         // $regimenFact = $_POST['regimenFact'];


         // $idComunFact = $_POST['idComunFact'];
         // $razonSocial = $_POST['razonSocial'];
         // $emailFacturacion = $_POST['emailFacturacion'];
         // $direccionFact = $_POST['direccionFact'];
         // $numextFact = $_POST['numextFact'];
         // $numintFact = $_POST['numintFact'];
         // $coloniaFact = $_POST['coloniaFact'];
         // $cpFact = $_POST['cpFact'];
         // $paisFact = $_POST['paisFact'];
         // $estadoFact = $_POST['estadoFact'];
         // $municipiosFact = $_POST['municipiosFact'];
         // $ciudadFact = $_POST['ciudadFact'];
         // $tipoDeCredito = $_POST['tipoDeCredito'];
         // $descuentoPP = $_POST['descuentoPP'];
         // $interesesMoratorios = $_POST['interesesMoratorios'];
         // $perVenCre = $_POST['perVenCre'];
         // $perExLim = $_POST['perExLim'];
         // $comisionVenta = $_POST['comisionVenta'];
         // $comisionCobranza = $_POST['comisionCobranza'];
         // $empleado = $_POST['empleado'];
         // $enviosDom = $_POST['enviosDom'];
         // $tipoClas = $_POST['tipoClas'];

         // $banco = $_POST['banco'];
         // $numCuenta = $_POST['numCuenta'];
         // $cuentaCont = $_POST['cuentaCont'];

         // $bandera = $_POST['flag'];


          
            $cliente = $this->ClienteModel->updateClientePortal($idCliente,$nombre,$tienda,$numint,$numext,$direccion,$colonia,$cp,$estado,$municipio,$email,$celular,$tel1,$tel2,$ciudad,$pais); 
         

        

        echo json_encode($cliente);
    }
 
    function index()
    {   
     

        session_start();
        $user= $_SESSION["accelog_login"];
        $expuser= explode('_', $user);
        $idCliente=$expuser[1];
        $paises = $this->ClienteModel->paises();
        $estados = $this->ClienteModel->estados();
        $municipiosFc = $this->ClienteModel->munici();
        $listaPre = $this->ClienteModel->listaPrecios();
        $moneda = $this->ClienteModel->moneda();
        $tipoCredito = $this->ClienteModel->creditos();
        $clasificadores = $this->ClienteModel->clasificadoresTipos(0);
        $empleados = $this->ClienteModel->obtenEmple();
        $bancos = $this->ClienteModel->bancos();
        $cuentas = $this->ClienteModel->cuentas();

        if($idCliente!=''){
            $datosCliente = $this->ClienteModel->datosCliente($idCliente);
            $datosClienteFact = $this->ClienteModel->datosClienteFact($idCliente);
            $id_claisf = $datosCliente['basicos'][0]['id_clasificacion'];
            $clasificadores = $this->ClienteModel->clasificadoresTipos($id_claisf);
            $cotizaciones = $this->ClienteModel->listaCotis($idCliente);
        } 

        if($datosCliente==null){
            echo 'No hay cliente, favor de loguearte de nuevo.';
            exit();
        }
        //$almacenes = $this->ClienteModel->almacenes();


        require('views/cliente/clienteFormPortal.php');
    }
}
        // $idCliente = $_GET['idCliente'];

        // $paises = $this->ClienteModel->paises();
        // $estados = $this->ClienteModel->estados();
        // $municipiosFc = $this->ClienteModel->munici();
        // $listaPre = $this->ClienteModel->listaPrecios();
        // $moneda = $this->ClienteModel->moneda();
        // $tipoCredito = $this->ClienteModel->creditos();
        // $clasificadores = $this->ClienteModel->clasificadoresTipos(0);
        // $empleados = $this->ClienteModel->obtenEmple();
        // $bancos = $this->ClienteModel->bancos();
        // $cuentas = $this->ClienteModel->cuentas();
        // $almacenes = $this->ClienteModel->almacenes(); /*
        /*foreach ($proveedores as $key => $value) {
         echo ''.$value['razon_social'].'<br>';
        } 
        if($idCliente!=''){
            $datosCliente = $this->ClienteModel->datosCliente($idCliente);
            $datosClienteFact = $this->ClienteModel->datosClienteFact($idCliente);
            $id_claisf = $datosCliente['basicos'][0]['id_clasificacion'];
            $clasificadores = $this->ClienteModel->clasificadoresTipos($id_claisf);
        } 
        //print_r($datosCliente);
        require('views/cliente/clienteForm.php');
    }

    

}


?>
