<?php
//Carga la funciones comunes top y footer
require('common.php');

//Carga el modelo para este controlador
require("models/reportes_ventas.php");

class Reportes_Ventas extends Common
{
    public $Reportes_VentasModel;

    function __construct()
    {
        //Se crea el objeto que instancia al modelo que se va a utilizar

        $this->Reportes_VentasModel = new Reportes_VentasModel();
        $this->Reportes_VentasModel->connect();
    }

    function __destruct()
    {
        //Se destruye el objeto que instancia al modelo que se va a utilizar
        $this->Reportes_VentasModel->close();
    }

    function grafi_ventas()
    {
        require('views/ventas/v_grafi_ventas.php'); 
    }

    function graficar2(){
        $desde = $_POST['desde'];
        $hasta = $_POST['hasta'];
        $orderby = $_POST['orderby'];
        $idSucursal = $_POST['sucursal'];

        $producto=$_POST['producto'];
        $proveedor=$_POST['proveedor'];

        $respG = $this->Reportes_VentasModel->graficar2($desde,$hasta,$orderby,$idSucursal,$producto,$proveedor);

        echo json_encode($respG);
    }

    function a_reporte()
    {
        $desde=$_POST['desde'];
        $hasta=$_POST['hasta'];
        $ordenar=$_POST['ordenar'];
        $radio=$_POST['radio'];
        $resultReq = $this->Reportes_VentasModel->reforteGrafico($desde,$hasta,$ordenar,$radio);

        if($resultReq->num_rows>0){
        while ($r = $resultReq->fetch_assoc()) {
            $productos[]=$r;
            $dproductos[]=(object)array('label'=>$r->nombre, 'value'=>$r->total);
        }
        $JSON = array('success' =>1, 'datos'=>$productos);
        }else{
        $JSON = array('success' =>0);
        }
          
        echo json_encode($JSON);

    }

    function cobranza_vendedor()
    {
        $listaVendedores = $this->Reportes_VentasModel->listaUsuarios();
        $listaClientes = $this->Reportes_VentasModel->listaClientes();
        require('views/ventas/cobranza_vendedor.php');
    }

    function cobranza_vendedor_reporte()
    {
        $datos = $this->Reportes_VentasModel->cobranza_vendedor_reporte($_POST);
        $reporte = '';
        $cobro = $cont = 0;
        $clienteAnterior = '';
        while($d = $datos->fetch_object())
        {
            $muestra = 1;
            if(intval($_POST['status_doc']) == 1)
            {
                if(floatval($d->pagos) < floatval($d->imp_factura))
                    $muestra = 0;
            }

            if(intval($_POST['status_doc']) == 2)
            {
                if(floatval($d->pagos) >= floatval($d->imp_factura))
                    $muestra = 0;
            }
            if($muestra)
            {
                if($d->id_usrcompra != $vendedorAnterior)
                {
                    if($cont != 0)
                    {
                        $reporte .= "<tr style='font-weight:bold;'><td></td><td></td><td colspan='2'>Total de Cobros</td><td colspan='4'>$ ".number_format($cobro,2)."</td></tr>";
                        $cobro = 0;
                    }
            
                    $reporte .= "<tr class='linea_prov'><td>Agente: </td><td colspan='6'>$d->id_usrcompra $d->Vendedor</td></tr>";
                    $clienteAnterior = '';
                }

                if($d->id_cliente != $clienteAnterior)
                {
                    $reporte .= "<tr class='linea_fac'><td>Cliente: </td><td colspan='6'>$d->id_cliente $d->Cliente</td></tr>";
                }

                
                    $folio = $d->folio;
                

                
                $saldo = floatval($d->imp_factura) - floatval($d->pagos);
                $reporte .= "<tr><td>$d->fecha</td><td>$d->tipoComp</td><td>$folio</td><td>$ ".number_format($d->imp_factura,2)."</td><td>$ ".number_format($d->pagos,2)."</td><td>$d->fecha_abono</td><td>$ ".number_format($saldo,2)."</td></tr>";
                $cobro += $d->pagos;
                $vendedorAnterior = $d->id_usrcompra;
                $clienteAnterior = $d->id_cliente;
                $cont++;
            }
            
        }
        $reporte .= "<tr style='font-weight:bold;'><td></td><td></td><td colspan='2'>Total de Cobros</td><td colspan='4'>$ ".number_format($cobro,2)."</td></tr>";
        echo $reporte;
        
    }
}

?>
