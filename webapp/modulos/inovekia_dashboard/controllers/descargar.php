<?php
//ini_set('display_errors', 1);
//Carga la funciones comunes top y footer
require('common.php');

//Carga el modelo para este controlador
require("models/descargar.php");

class Descargar extends Common
{
    public $DescargarModel;

    function __construct()
    {
        //Se crea el objeto que instancia al modelo que se va a utilizar

        $this->DescargarModel = new DescargarModel();
        $this->DescargarModel->connect();
    }

    function __destruct()
    {
        //Se destruye el objeto que instancia al modelo que se va a utilizar
        $this->DescargarModel->close();
    } 

    function index(){
        require('views/descargar/index.php');
    }

    function relacion(){
        require_once '../../libraries/PHPExcel/PHPExcel.php';
        require_once '../../libraries/PHPExcel/PHPExcel/IOFactory.php';
        $datos = $this->DescargarModel->relacion();
        if($datos["status"]){
            $datos = $datos["registros"];
            $phpExcel = new PHPExcel();
            $phpExcel->setActiveSheetIndex(0);

            $phpExcel->getActiveSheet()->SetCellValue('A1', 'Organismo');
            $phpExcel->getActiveSheet()->SetCellValue('B1', 'Consultor');
            $phpExcel->getActiveSheet()->SetCellValue('C1', 'Consultor Usuario');
            $phpExcel->getActiveSheet()->SetCellValue('D1', 'Empresario');
            $phpExcel->getActiveSheet()->SetCellValue('E1', 'Empresario Usuario');
            $phpExcel->getActiveSheet()->SetCellValue('F1', 'Folio');
            $phpExcel->getActiveSheet()->SetCellValue('G1', 'Creado');

            $contador = 2;
            foreach ($datos as $relacion) {
                $phpExcel->getActiveSheet()->SetCellValue('A' . $contador, $relacion["Organismo"]);
                $phpExcel->getActiveSheet()->SetCellValue('B' . $contador, $relacion["Consultor"]);
                $phpExcel->getActiveSheet()->SetCellValue('C' . $contador, $relacion["ConsultorUsuario"]);
                $phpExcel->getActiveSheet()->SetCellValue('D' . $contador, $relacion["Empresario"]);
                $phpExcel->getActiveSheet()->SetCellValue('E' . $contador, $relacion["EmpresarioUsuario"]);
                $phpExcel->getActiveSheet()->SetCellValue('F' . $contador, $relacion["Folio"]);
                $phpExcel->getActiveSheet()->SetCellValue('G' . $contador, $relacion["Creado"]);
                $contador++;
            }

            $phpExcel->getActiveSheet()->setTitle('Registros');
    
            //$phpExcel = PHPExcel_IOFactory::createWriter($phpExcel, 'Excel2007');
            
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="relaciones.xls"');
            header('Cache-Control: max-age=0');
            header ('Expires: Mon, 26 Jul 2017 05:00:00 GMT');
            header ('Last-Modified: ' . gmdate('D, d M Y H:i:s').' GMT');
            header ('Cache-Control: cache, must-revalidate');
            header ('Pragma: public');

            $phpExcel = PHPExcel_IOFactory::createWriter($phpExcel, 'Excel5');
            $phpExcel->save('php://output');

            /*header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="relaciones.xls"');
            header('Cache-Control: max-age=0');*/
            //$phpExcel->save('php://output');
            exit();
        } else {
            echo json_encode($datos);
        }
    }

    function formularioUno(){
        require_once '../../libraries/PHPExcel/PHPExcel.php';
        require_once '../../libraries/PHPExcel/PHPExcel/IOFactory.php';
        $datos = $this->DescargarModel->formularioUno();
        if($datos["status"]){
            $datos = $datos["registros"];
            $phpExcel = new PHPExcel();
            $phpExcel->setActiveSheetIndex(0);

            $phpExcel->getActiveSheet()->SetCellValue('A1', 'ORGANISMO');
            $phpExcel->getActiveSheet()->SetCellValue('B1', 'FOLIO');
            $phpExcel->getActiveSheet()->SetCellValue('C1', 'ID EMPRESARIO');
            $phpExcel->getActiveSheet()->SetCellValue('D1', 'NOMBRE');
            $phpExcel->getActiveSheet()->SetCellValue('E1', 'RFC');
            $phpExcel->getActiveSheet()->SetCellValue('F1', 'CONSULTOR');
            $phpExcel->getActiveSheet()->SetCellValue('G1', 'FECHA ULTIMO ACCESO');
            $phpExcel->getActiveSheet()->SetCellValue('H1', 'FOLIO DE PROYECTO');
            $phpExcel->getActiveSheet()->SetCellValue('I1', 'RAZON SOCIAL');
            $phpExcel->getActiveSheet()->SetCellValue('J1', 'NOMBRE DEL REP LEGAL');
            $phpExcel->getActiveSheet()->SetCellValue('K1', 'RFC DE LA EMPRESA');
            $phpExcel->getActiveSheet()->SetCellValue('L1', 'FOTO DE LA CONSTANCIA DEL RFC');
            $phpExcel->getActiveSheet()->SetCellValue('M1', 'TELEFONO');
            $phpExcel->getActiveSheet()->SetCellValue('N1', 'CORREO ELECTRONICO');
            $phpExcel->getActiveSheet()->SetCellValue('O1', 'TAMAÑO DE LA EMPRESA');
            $phpExcel->getActiveSheet()->SetCellValue('P1', 'GIRO DE LA EMPRESA');
            $phpExcel->getActiveSheet()->SetCellValue('Q1', 'RUBRO DE LA EMPRESA');
            $phpExcel->getActiveSheet()->SetCellValue('R1', 'TIPO DE VIALIDAD');
            $phpExcel->getActiveSheet()->SetCellValue('S1', 'NOMBRE DE VIALIDAD');
            $phpExcel->getActiveSheet()->SetCellValue('T1', 'NUMERO EXTERIOR');
            $phpExcel->getActiveSheet()->SetCellValue('U1', 'TIPO DE ASENTAMIENTO');
            $phpExcel->getActiveSheet()->SetCellValue('V1', 'NOMBRE DEL ASENTAMIENTO');
            $phpExcel->getActiveSheet()->SetCellValue('W1', 'CODIGO POSTAL');
            $phpExcel->getActiveSheet()->SetCellValue('X1', 'LOCALIDAD');
            $phpExcel->getActiveSheet()->SetCellValue('Y1', 'MUNICIPIO');
            $phpExcel->getActiveSheet()->SetCellValue('Z1', 'ENTIDAD FEDERATIVA');
            $phpExcel->getActiveSheet()->SetCellValue('AA1', 'ENTRE VIALIDADES');
            $phpExcel->getActiveSheet()->SetCellValue('AB1', 'VIALIDAD POSTERIOR');
            $phpExcel->getActiveSheet()->SetCellValue('AC1', 'DESCRIPCION DE LA UBICACIÓN');
            $phpExcel->getActiveSheet()->SetCellValue('AD1', 'CARTA DE AUTO EMPLEO');
            $phpExcel->getActiveSheet()->SetCellValue('AE1', 'IFE DE EMPRESARIO');
            $phpExcel->getActiveSheet()->SetCellValue('AF1', 'LISTA DE RAYAS HOMBRES');
            $phpExcel->getActiveSheet()->SetCellValue('AG1', 'IFE DE HOMBRES');
            $phpExcel->getActiveSheet()->SetCellValue('AH1', 'LISTA DE RAYAS MUJERES');
            $phpExcel->getActiveSheet()->SetCellValue('AI1', 'IFE DE MUJERES');
            $phpExcel->getActiveSheet()->SetCellValue('AJ1', 'RECIBO DE LUZ');
            $phpExcel->getActiveSheet()->SetCellValue('AK1', 'EMPLEADOS HOMBRE');
            $phpExcel->getActiveSheet()->SetCellValue('AL1', 'EMPLEADOS MUJER');
            $phpExcel->getActiveSheet()->SetCellValue('AM1', 'EMPLEADOS INDIGENA');
            $phpExcel->getActiveSheet()->SetCellValue('AN1', 'EMPLEADOS DISCAPACITADO');
            $phpExcel->getActiveSheet()->SetCellValue('AO1', 'VENTAS TOTALES DEL PERIODO ANTERIOR');
            $phpExcel->getActiveSheet()->SetCellValue('AP1', 'VENTAS TOTALES DEL PERIODO ACTUAL');
            $phpExcel->getActiveSheet()->SetCellValue('AQ1', 'COSTO DE NOMINA');
            $phpExcel->getActiveSheet()->SetCellValue('AR1', 'VALOR DE LOS ACTIVOS FIJOS');
            $phpExcel->getActiveSheet()->SetCellValue('AS1', 'CORREO ELECTRONICO');
            $phpExcel->getActiveSheet()->SetCellValue('AT1', 'CONTRASEÑA');

            $contador = 2;
            foreach ($datos as $empresario) {
                $phpExcel->getActiveSheet()->SetCellValue('A' . $contador, $empresario["organismo"]);
                $phpExcel->getActiveSheet()->SetCellValue('B' . $contador, $empresario["folio"]);
                $phpExcel->getActiveSheet()->SetCellValue('C' . $contador, $empresario["id_empresario"]);
                $phpExcel->getActiveSheet()->SetCellValue('D' . $contador, $empresario["nombre"]);
                $phpExcel->getActiveSheet()->SetCellValue('E' . $contador, $empresario["rfc"]);
                $phpExcel->getActiveSheet()->SetCellValue('F' . $contador, $empresario["consultor"]);
                $phpExcel->getActiveSheet()->SetCellValue('G' . $contador, "");
                $phpExcel->getActiveSheet()->SetCellValue('H' . $contador, $empresario["folio"]);
                $phpExcel->getActiveSheet()->SetCellValue('I' . $contador, $empresario["razon"]);
                $phpExcel->getActiveSheet()->SetCellValue('J' . $contador, $empresario["representante"]);
                $phpExcel->getActiveSheet()->SetCellValue('K' . $contador, $empresario["rfc"]);
                if($empresario["rfcH1"] == "N.A." || is_null($empresario["rfcH1"])){
                    $phpExcel->getActiveSheet()->SetCellValue('L' . $contador, "N.A.");
                } else {
                    $phpExcel->getActiveSheet()->SetCellValue('L' . $contador, "Hoja 1:\n". $empresario["rfcH1"] ."\n\nHoja 2:\n". $empresario["rfcH2"] ."\n\nHoja 3:\n". $empresario["rfcH3"]);
                }
                $phpExcel->getActiveSheet()->SetCellValue('M' . $contador, $empresario["telefono"]);
                $phpExcel->getActiveSheet()->SetCellValue('N' . $contador, $empresario["correo"]);
                $phpExcel->getActiveSheet()->SetCellValue('O' . $contador, $empresario["tamano"]);
                $phpExcel->getActiveSheet()->SetCellValue('P' . $contador, $empresario["giro"]);
                $phpExcel->getActiveSheet()->SetCellValue('Q' . $contador, $empresario["rubro"]);
                $phpExcel->getActiveSheet()->SetCellValue('R' . $contador, $empresario["tipo_vialidad"]);
                $phpExcel->getActiveSheet()->SetCellValue('S' . $contador, $empresario["vialidad"]);
                $phpExcel->getActiveSheet()->SetCellValue('T' . $contador, $empresario["num_ext"]);
                $phpExcel->getActiveSheet()->SetCellValue('U' . $contador, $empresario["tipo_asentamiento"]);
                $phpExcel->getActiveSheet()->SetCellValue('V' . $contador, $empresario["asentamiento"]);
                $phpExcel->getActiveSheet()->SetCellValue('W' . $contador, $empresario["cp"]);
                $phpExcel->getActiveSheet()->SetCellValue('X' . $contador, $empresario["localidad"]);
                $phpExcel->getActiveSheet()->SetCellValue('Y' . $contador, $empresario["municipio"]);
                $phpExcel->getActiveSheet()->SetCellValue('Z' . $contador, $empresario["estado"]);
                $phpExcel->getActiveSheet()->SetCellValue('AA' . $contador, $empresario["entre"]);
                $phpExcel->getActiveSheet()->SetCellValue('AB' . $contador, $empresario["posterior"]);
                $phpExcel->getActiveSheet()->SetCellValue('AC' . $contador, $empresario["descripcion"]);
                $phpExcel->getActiveSheet()->SetCellValue('AD' . $contador, $empresario["caeH1"]);
                if($empresario["ifeH1"] == "N.A." || is_null($empresario["ifeH1"])){
                    $phpExcel->getActiveSheet()->SetCellValue('AE' . $contador, "N.A.");
                } else {
                    $phpExcel->getActiveSheet()->SetCellValue('AE' . $contador, "Hoja 1:\n". $empresario["ifeH1"] ."\n\nHoja 2:\n". $empresario["ifeH2"]);
                }
                $phpExcel->getActiveSheet()->SetCellValue('AF' . $contador, $empresario["lrhH1"]);
                if($empresario["ifehH1"] == "N.A." || is_null($empresario["ifehH1"])){
                    $phpExcel->getActiveSheet()->SetCellValue('AG' . $contador, "N.A.");
                } else {
                    $phpExcel->getActiveSheet()->SetCellValue('AG' . $contador, "Hoja 1:\n". $empresario["ifehH1"] ."\n\nHoja 2:\n". $empresario["ifehH2"]);
                }
                $phpExcel->getActiveSheet()->SetCellValue('AH' . $contador, $empresario["lrmH1"]);
                if($empresario["ifemH1"] == "N.A." || is_null($empresario["ifemH1"])){
                    $phpExcel->getActiveSheet()->SetCellValue('AI' . $contador, "N.A.");
                } else {
                    $phpExcel->getActiveSheet()->SetCellValue('AI' . $contador, "Hoja 1:\n". $empresario["ifemH1"] ."\n\nHoja 2:\n". $empresario["ifemH2"]);
                }
                if($empresario["reciboH1"] == "N.A." || is_null($empresario["reciboH1"])){
                    $phpExcel->getActiveSheet()->SetCellValue('AJ' . $contador, "N.A.");
                } else {
                    $phpExcel->getActiveSheet()->SetCellValue('AJ' . $contador, "Hoja 1:\n". $empresario["reciboH1"] ."\n\nHoja 2:\n". $empresario["reciboH2"]);
                }
                $phpExcel->getActiveSheet()->SetCellValue('AK' . $contador, $empresario["empleados_hombre"]);
                $phpExcel->getActiveSheet()->SetCellValue('AL' . $contador, $empresario["empleados_mujer"]);
                $phpExcel->getActiveSheet()->SetCellValue('AM' . $contador, $empresario["empleados_indigena"]);
                $phpExcel->getActiveSheet()->SetCellValue('AN' . $contador, $empresario["empleados_discapacitado"]);
                $phpExcel->getActiveSheet()->SetCellValue('AO' . $contador, $empresario["ventas_anterior"]);
                $phpExcel->getActiveSheet()->SetCellValue('AP' . $contador, $empresario["ventas_actual"]);
                $phpExcel->getActiveSheet()->SetCellValue('AQ' . $contador, $empresario["nomina"]);
                $phpExcel->getActiveSheet()->SetCellValue('AR' . $contador, $empresario["activos"]);
                $phpExcel->getActiveSheet()->SetCellValue('AS' . $contador, $empresario["email"]);
                $phpExcel->getActiveSheet()->SetCellValue('AT' . $contador, $empresario["contrasena"]);
                $contador++;
            }

            $phpExcel->getActiveSheet()->setTitle('Registros');
    
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="formulario_uno.xls"');
            header('Cache-Control: max-age=0');
            header ('Expires: Mon, 26 Jul 2022 05:00:00 GMT');
            header ('Last-Modified: ' . gmdate('D, d M Y H:i:s').' GMT');
            header ('Cache-Control: cache, must-revalidate');
            header ('Pragma: public');

            $phpExcel = PHPExcel_IOFactory::createWriter($phpExcel, 'Excel5');
            $phpExcel->save('php://output');

            exit();
        } else {
            echo json_encode($datos);
        }
    }

    function seguimiento(){
        require_once '../../libraries/PHPExcel/PHPExcel.php';
        require_once '../../libraries/PHPExcel/PHPExcel/IOFactory.php';
        $datos = $this->DescargarModel->seguimiento();
        if($datos["status"]){
            $datos = $datos["registros"];
            $phpExcel = new PHPExcel();
            $phpExcel->setActiveSheetIndex(0);

            $phpExcel->getActiveSheet()->SetCellValue('A1', 'ORGANISMO');
            $phpExcel->getActiveSheet()->SetCellValue('B1', 'FOLIO');
            $phpExcel->getActiveSheet()->SetCellValue('C1', 'ID EMPRESARIO');
            $phpExcel->getActiveSheet()->SetCellValue('D1', 'NOMBRE');
            $phpExcel->getActiveSheet()->SetCellValue('E1', 'RFC');
            $phpExcel->getActiveSheet()->SetCellValue('F1', 'CONSULTOR');
            $phpExcel->getActiveSheet()->SetCellValue('G1', 'FECHA ULTIMO ACCESO');
            $phpExcel->getActiveSheet()->SetCellValue('H1', 'CURSO VENTAS');
            $phpExcel->getActiveSheet()->SetCellValue('I1', 'CURSO INVENTARIOS');
            $phpExcel->getActiveSheet()->SetCellValue('J1', 'CURSO OPERACIONES CONTABLES');
            $phpExcel->getActiveSheet()->SetCellValue('K1', 'CURSO CAPACIDAD ENDEUDAMIENTO');
            $phpExcel->getActiveSheet()->SetCellValue('L1', 'CURSO USO DE HARDWARE Y SOFTWARE');
            $phpExcel->getActiveSheet()->SetCellValue('M1', 'FORMULARIO SESIÓN INICIAL');
            $phpExcel->getActiveSheet()->SetCellValue('N1', 'FORMULARIO FACTIBILIDAD COMERCIAL');
            $phpExcel->getActiveSheet()->SetCellValue('O1', 'FORMULARIO DIAGNOSTICO DE MADUREZ');
            $phpExcel->getActiveSheet()->SetCellValue('P1', 'FORMULARIO PLAN FINANCIERO');
            $phpExcel->getActiveSheet()->SetCellValue('Q1', 'FORMULARIO MICRO MERCADO');

            $contador = 2;
            foreach ($datos as $empresario) {
                $phpExcel->getActiveSheet()->SetCellValue('A' . $contador, $empresario["empresario"]["organismo"]);
                $phpExcel->getActiveSheet()->SetCellValue('B' . $contador, $empresario["empresario"]["folio"]);
                $phpExcel->getActiveSheet()->SetCellValue('C' . $contador, $empresario["empresario"]["id_empresario"]);
                $phpExcel->getActiveSheet()->SetCellValue('D' . $contador, $empresario["empresario"]["nombre"]);
                $phpExcel->getActiveSheet()->SetCellValue('E' . $contador, $empresario["empresario"]["rfc"]);
                $phpExcel->getActiveSheet()->SetCellValue('F' . $contador, $empresario["empresario"]["consultor"]);
                $phpExcel->getActiveSheet()->SetCellValue('G' . $contador, "");
                $phpExcel->getActiveSheet()->SetCellValue('H' . $contador, $empresario["seguimientos"]["atencion_clientes"]);
                $phpExcel->getActiveSheet()->SetCellValue('I' . $contador, $empresario["seguimientos"]["inventarios"]);
                $phpExcel->getActiveSheet()->SetCellValue('J' . $contador, $empresario["seguimientos"]["operaciones_contables"]);
                $phpExcel->getActiveSheet()->SetCellValue('K' . $contador, $empresario["seguimientos"]["capacidad_endeudamiento"]);
                $phpExcel->getActiveSheet()->SetCellValue('L' . $contador, $empresario["seguimientos"]["hardware_software"]);
                $phpExcel->getActiveSheet()->SetCellValue('M' . $contador, $empresario["formularios"]["sesion_inicial"]);
                $phpExcel->getActiveSheet()->SetCellValue('N' . $contador, $empresario["formularios"]["factibilidad_comercial"]);
                $phpExcel->getActiveSheet()->SetCellValue('O' . $contador, $empresario["formularios"]["diagnostico_madurez"]);
                $phpExcel->getActiveSheet()->SetCellValue('P' . $contador, $empresario["formularios"]["plan_financiero"]);
                $phpExcel->getActiveSheet()->SetCellValue('Q' . $contador, $empresario["formularios"]["micro_mercado"]);
                $contador++;  
            }

            $phpExcel->getActiveSheet()->setTitle('Reporte');

            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="seguimiento.xls"');
            header('Cache-Control: max-age=0');
            header ('Expires: Mon, 26 Jul 2022 05:00:00 GMT');
            header ('Last-Modified: ' . gmdate('D, d M Y H:i:s').' GMT');
            header ('Cache-Control: cache, must-revalidate');
            header ('Pragma: public');

            $phpExcel = PHPExcel_IOFactory::createWriter($phpExcel, 'Excel5');
            $phpExcel->save('php://output');
            
            exit();
        } else {
            echo json_encode($datos);
        }
    }

}

?>