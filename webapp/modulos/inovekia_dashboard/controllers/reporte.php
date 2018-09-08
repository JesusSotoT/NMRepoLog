<?php
//ini_set('display_errors', 1);
//Carga la funciones comunes top y footer
require('common.php');

//Carga el modelo para este controlador
require("models/reporte.php");

class Reporte extends Common
{
    public $ReporteModel;

    function __construct()
    {
        //Se crea el objeto que instancia al modelo que se va a utilizar

        $this->ReporteModel = new ReporteModel();
        $this->ReporteModel->connect();
    }

    function __destruct()
    {
        //Se destruye el objeto que instancia al modelo que se va a utilizar
        $this->ReporteModel->close();
    } 

    function cursosOrganismoIndex(){
        require('views/reporte/cursos/organismo.php');
    }

    function cursosOrganismo(){
        echo json_encode($this->ReporteModel->cursosOrganismo());
    }

    function cursosFolioIndex(){
        require('views/reporte/cursos/folio.php');
    }

    function cursosFolio(){
        echo json_encode($this->ReporteModel->cursosFolio());
    }

    function cursosConsultorIndex(){
        require('views/reporte/cursos/consultor.php');
    }

    function cursosConsultor(){
        echo json_encode($this->ReporteModel->cursosConsultor());
    }

    function cursosEmpresarioIndex(){
        require('views/reporte/cursos/empresario.php');
    }

    function cursosEmpresario(){
        echo json_encode($this->ReporteModel->cursosEmpresario());
    }

    function cursosCursoIndex(){
        require('views/reporte/cursos/curso.php');
    }

    function cursosCurso(){
        echo json_encode($this->ReporteModel->cursosCurso());
    }

}

?>