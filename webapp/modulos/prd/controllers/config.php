<?php
//Carga la funciones comunes top y footer
require('common.php');

//Carga el modelo para este controlador
require("models/config.php");

class Config extends Common{

    //public $ConfigModel;

    function __construct()
    {
      

        //Se crea el objeto que instancia al modelo que se va a utilizar
        $this->ConfigModel = new ConfigModel();

    }

    function __destruct()
    {

        //Se destruye el objeto que instancia al modelo que se va a utilizar
        //$this->ConfigModel->close();
    }

    function a_nuevaorden()
    {
      // $resultReq = $this->ConfigModel->getLastOrden();
      // if($resultReq->num_rows>0){
      //   $row = $resultReq->fetch_array();
      //   $JSON = array('success' =>1, 'op'=>$row['id']);
      // }else{
      //   $JSON = array('success' =>0);
      // }
      
      // echo json_encode($JSON);

    }

    function saveConfig(){
      $opcion=$_POST['opcion'];
      $gap=$_POST['gap'];
      $apop=$_POST['apop'];
      $notc=$_POST['notc'];
      $hereda=$_POST['hereda'];
      $insdir=$_POST['insdir'];
      $ocop=$_POST['ocop'];
      $ocsinr=$_POST['ocsinr'];
      $deaalm=$_POST['deaalm'];
      $salins=$_POST['salins'];
      $capaso=$_POST['capaso'];
      $resultModel= $this->ConfigModel->saveConfig($opcion,$gap,$apop,$notc,$hereda,$insdir,$ocop,$ocsinr,$deaalm,$salins,$capaso);
    
    }


    function configurar(){

      $resultModel = $this->ConfigModel->loadConfig();

      if($resultModel->num_rows>0){
        while ($row = $resultModel->fetch_assoc()) {
          $gap=$row['gen_aut_ped'];
          $apop=$row['aut_ord_prod'];
          $notc=$row['not_correo'];
          $hereda=$row['heredar_op'];
          $insdir=$row['req_insumos'];
          $ocop=$row['oc_seareq'];
          $ocsinr=$row['genoc_sinreq'];
          $deaalm=$row['designar_almacen'];
          $salins=$row['salida_autinsumos'];
          $capaso=$row['capaso'];
        }
      }else{
        $gap=0;
        $apop=0;
        $notc=0;
        $hereda=0;
        $insdir=0;
        $ocop=0;
        $ocsinr=0;
        $deaalm=0;
        $salins=0;
        $capaso=1;
      }

      // $resultReq = $this->ConfiguracionModel->getUsuario();
      // if($resultReq->num_rows>0){
      //   $set = $resultReq->fetch_assoc();
      //   $username=$set['username'];
      //   $iduser=$set['idempleado'];
      // }else{
      //   $username='Favor de salir y loguearse nuevamente';
      //   $iduser='0';
      // }

      // $resultReq = $this->ConfiguracionModel->getSucursales();
      // if($resultReq->num_rows>0){
      //   while ($r = $resultReq->fetch_assoc()) {
      //     $sucursales[]=$r;
      //   }
      // }else{
      //   $sucursales=0;
      // }

      require('views/config.php');
    }

  

}


?>
