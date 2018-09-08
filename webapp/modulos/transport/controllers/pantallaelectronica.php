<?php
//Carga la funciones comunes top y footer
require('common.php');


//Carga el modelo para este controlador
require("models/pantallaelectronica.php");

class PantallaElectronica extends Common
{
	public $PantallaElectronicaModel;

	function __construct()
	{
		//Se crea el objeto que instancia al modelo que se va a utilizar

		$this->PantallaElectronicaModel = new PantallaElectronicaModel();
		$this->PantallaElectronicaModel->connect();
	}

	function __destruct()
	{
		//Se destruye el objeto que instancia al modelo que se va a utilizar
		$this->PantallaElectronicaModel->close();
	}

	function pantalla()
	{
		require('views/pantallaelectronica/pantalla.php');
	}
  

  	function listaPantalla(){
      $estatus = $_POST['estatus'];
     
      if($estatus==3){
        $where = "WHERE a.estatus = '1' group by a.idunidad";
      }
      if($estatus==2){
        $where = "WHERE a.estatus = '3'";
      }
      if($estatus==4){
        $where = "WHERE a.estatus = '4'";
      }
      if($estatus==5){
        $where = "WHERE c.estatus = '5'";
      }
      $listaPantalla = $this->PantallaElectronicaModel->listarPantalla($where);
      echo json_encode ($listaPantalla);
    }

  function listaPantallaCH(){
      
      $fechainicio = $_POST['fechainicio'];
      $fechafinal = $_POST['fechafinal'];
      $idcliente = $_POST['idcliente'];
      $idoperador = $_POST['idoperador'];
      $iddestino = $_POST['iddestino'];
      $idunidad = $_POST['idunidad'];
      $idcapacidad = $_POST['idcapacidad'];
       
      $where = "WHERE (c.fecha >= '$fechainicio' and c.fecha <= '$fechafinal')";
  
      if($idcliente == 0){
        $where .= "";
      }else{
        $where .= " AND f.id = ".$idcliente;
      }
    
      if($idoperador == 0){
        $where .= "";
      }else{
        $where .= " AND d.idEmpleado = ".$idoperador;
      }

      if($iddestino == 0){
        $where .= "";
      }else{
        $where .= " AND g.iddatosentrega = ".$iddestino;
      }

      if($idunidad == 0){
        $where .= "";
      }else{
        $where .= " AND a.idunidad = ".$idunidad;
      }

      if($idcapacidad == 0){
        $where .= "";
      }else{
        $where .= " AND b.idcapacidadunidad = ".$idcapacidad;
      }

      $where .= " AND c.estatus = 'FINALALIZADO'";
      
      $listaPantallaCH = $this->PantallaElectronicaModel->listarPantallaCH($where);
      echo json_encode ($listaPantallaCH);
      
    }


  	function listaReportesMax(){
  	$idordenservicio = $_POST['idordenservicio'];
      $listaReportesMax = $this->PantallaElectronicaModel->listarReportesMax($idordenservicio);
      echo json_encode ($listaReportesMax);
    }
    function listaOperadores(){
      $listaOperadores = $this->PantallaElectronicaModel->listarOperadores();
      echo json_encode ($listaOperadores);
    }
    function listaUnidades(){
      $listaUnidades = $this->PantallaElectronicaModel->listarUnidades();
      echo json_encode ($listaUnidades);
    }
    function listaDestinos(){
      $listaDestinos = $this->PantallaElectronicaModel->listarDestinos();
      echo json_encode ($listaDestinos);
    }
    function listaReportesEsp(){
  		  $idordenservicio = $_POST['idordenservicio'];
      	$listaReportesEsp = $this->PantallaElectronicaModel->listarReportesEsp($idordenservicio);
      	echo json_encode ($listaReportesEsp);
    }
    

    function saveReporte(){
      $num_reporte = $_POST['num_reporte'];
      $idordenservicio = $_POST['idordenservicio'];
      $fecha = $_POST['fecha'];
      $hora = $_POST['hora'];
      $km = $_POST['km'];
      $ubicacion = $_POST['ubicacion'];
      $observ = $_POST['observ'];
      $estatus = $_POST['estatus'];
      $idEmpleado = $_POST['idEmpleado'];
      $idunidad = $_POST['idunidad'];
      $idcajatractor = $_POST['idcajatractor'];

      if($estatus == "VIAJE"){
          $saveReporte = $this->PantallaElectronicaModel->savedReporte($num_reporte,$idordenservicio,$fecha,$hora,$km,$ubicacion,$observ,$estatus);
          echo ($saveReporte);
      }else if($estatus == "FINALIZADO"){
          $saveReporte = $this->PantallaElectronicaModel->savedReporteF($num_reporte,$idordenservicio,$fecha,$hora,$km,$ubicacion,$observ,$estatus,$idEmpleado,$idunidad,$idcajatractor);
          echo ($saveReporte);
      }

    }

    

}/// fin class

?>