<?php
//ini_set('display_errors', 1);
//Carga la funciones comunes top y footer
require('common.php');

//Carga el modelo para este controlador
require("models/reportear.php");

class Reportear extends Common
{
    public $ReportearModel;

    function __construct()
    {
        //Se crea el objeto que instancia al modelo que se va a utilizar

        $this->ReportearModel = new ReportearModel();
        $this->ReportearModel->connect();
    }

    function __destruct()
    {
        //Se destruye el objeto que instancia al modelo que se va a utilizar
        $this->ReportearModel->close();
    }
    function reportear(){
        $moneda = $this->ReportearModel->moneda();
        $formasDePago = $this->ReportearModel->formasDePago();
        $cliente = $this->ReportearModel->ventasIndex();
        
        $clientes = $this->ReportearModel->clientes();
        require('views/reportear/reportear.php');
    }
    
    function generar(){
        $tipoM       = $_POST['tipoM'];
        $tipoA       = $_POST['tipoA'];
        $cliente     = $_POST['cliente'];        

        $desde       = $_POST['desde'];
        $hasta       = $_POST['hasta'];

        if($tipoM == 1){// retiro
            $retiros = $this->ReportearModel->retiros($desde,$hasta);
        }else{ // abono
            $abonos = $this->ReportearModel->abonos($tipoA,$cliente,$desde,$hasta);
        }
                        
        if($tipoM == 1){// retiro
            $tablaphp = '<table id="tableReportear" class="table table-striped table-bordered sizeprint" cellspacing="0" width="100%">'.
                        '<thead>'.
                        '<tr>'.
                            '<th align="center" width="10">ID</th>'.
                            '<th align="center" width="40">Fecha</th>'.
                            '<th align="center" width="40">Usuario</th>'.
                            '<th align="center" width="30">Concepto</th>'.
                            '<th align="center" width="30">Cantidad</th>'.
                            '<th align="center" width="10">Imprimir</th>'.                                                            
                      '</tr>'.
                    '</thead>';
        }else{ // abono
            $tablaphp = '<table id="tableReportear" class="table table-striped table-bordered sizeprint" cellspacing="0" width="100%">'.
                        '<thead>'.
                        '<tr>'.
                            '<th align="center" width="10">ID</th>'.
                            '<th align="center" width="40">Fecha</th>'.
                            '<th align="center">Usuario</th>'.
                            '<th align="center">Cliente</th>'.
                            '<th align="center" width="30">Concepto</th>'.
                            '<th align="center" width="30">Cantidad</th>'.
                            '<th align="center" width="10">Imprimir</th>'.                                                                                    
                      '</tr>'.
                    '</thead>';
        }

       

        if($tipoM == 1){
            foreach ($retiros as $k => $v) {
                $fecha = date("d-m-Y h:m:s",strtotime($v['fecha']));
                $y ='<tr>'.
                        '<td align="center">'.$v['id'].'</td>'.
                        '<td align="center">'.$fecha.'</td>'.
                        '<td align="center">'.$v['empleado'].'</td>'.
                        '<td align="center">'.$v['concepto'].'</td>'.
                        '<td align="center">$'.$v['cantidad'].'</td>'.
                        '<td align="center"><button class="btn btn-default" onclick="reimprimeR('.$v['id'].');"> <i class="fa fa-print" aria-hidden="true"></i> </button></td>'.             
                    '</tr>';    

                    $tablaphp .= $y;                            
            }
        }else{
            foreach ($abonos as $k => $v) {
                $fecha = date("d-m-Y h:m:s",strtotime($v['fecha']));
                $y ='<tr>'.
                        '<td align="center">'.$v['id'].'</td>'.
                        '<td align="center">'.$fecha.'</td>'.                        
                        '<td align="center">'.$v['empleado'].'</td>'.
                        '<td align="center">'.$v['cliente'].'</td>'.
                        '<td align="center">'.$v['concepto'].'</td>'.
                        '<td align="center">$'.$v['cantidad'].'</td>'.
                        '<td align="center"><button class="btn btn-default" onclick="reimprimeA('.$v['id'].');"> <i class="fa fa-print" aria-hidden="true"></i> </button></td>'.                                                          
                    '</tr>'; 

                $tablaphp .= $y;                              
            }                                          
        }
        echo $tablaphp;
    }


}

?>