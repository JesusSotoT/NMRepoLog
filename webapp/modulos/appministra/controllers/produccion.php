<?php
//Carga la funciones comunes top y footer
require('common.php');

//Carga el modelo para este controlador
require("models/produccion.php");


class Produccion extends Common
{
    public $ProduccionModel;

    function __construct()
    {
        //Se crea el objeto que instancia al modelo que se va a utilizar
        $this->ProduccionModel = new ProduccionModel();
        $this->ProduccionModel->connect();
    }

    function __destruct()
    {
        //Se destruye el objeto que instancia al modelo que se va a utilizar
        $this->ProduccionModel->close();
    }

    function a_nuevaorden()
    {
      $resultReq = $this->ProduccionModel->getLastOrden();
      if($resultReq->num_rows>0){
        $row = $resultReq->fetch_array();
        $JSON = array('success' =>1, 'op'=>$row['id']);
      }else{
        $JSON = array('success' =>0);
      }
      
      echo json_encode($JSON);

    }

    function a_addProductoProduccion(){

      $resultReq = $this->ProduccionModel->getEmpleados();
      if($resultReq->num_rows>0){
        while ($r = $resultReq->fetch_assoc()) {
          $empleados[]=$r;
        }
      }else{
        $empleados=0;
      }


      $idProducto=$_POST['idProducto'];

      $resultReq = $this->ProduccionModel->addProductoProduccion($idProducto);
      $cccar=0;
      $html='';
  

      if($resultReq->num_rows>0){
        $row = $resultReq->fetch_array();
        $producto[]=$row;

        $adds='<select id="prelis" onchange="refreshCants('.$producto[0]['id'].',0,0)">
          <option value="'.$producto[0]['costo'].'>0">$'.$producto[0]['costo'].' Precio lista</option>';
        $adds.='<option value="OTRO>x">Otro precio</option>';

        $JSON = array('success' =>1, 'datos'=>$producto, 'adds'=>$adds, 'car'=>$html, 'cccar'=>$cccar);
      }else{
        $JSON = array('success' =>0);
      }

      
      
      echo json_encode($JSON);


    }


    function oproduccion(){
      $resultReq = $this->ProduccionModel->getProductos5();
      if($resultReq->num_rows>0){
        while ($r = $resultReq->fetch_assoc()) {
          $productos[]=$r;
        }
      }else{
        $productos=0;
      }

      $resultReq = $this->ProduccionModel->getUsuario();
      if($resultReq->num_rows>0){
        $set = $resultReq->fetch_assoc();
        $username=$set['username'];
        $iduser=$set['idempleado'];
      }else{
        $username='Favor de salir y loguearse nuevamente';
        $iduser='0';
      }

      $resultReq = $this->ProduccionModel->getSucursales();
      if($resultReq->num_rows>0){
        while ($r = $resultReq->fetch_assoc()) {
          $sucursales[]=$r;
        }
      }else{
        $sucursales=0;
      }

      $resultReq = $this->ProduccionModel->getEmpleados();
      if($resultReq->num_rows>0){
        while ($r = $resultReq->fetch_assoc()) {
          $empleados[]=$r;
        }
      }else{
        $empleados=0;
      }

      require('views/produccion/oproduccion.php');
    }

    function prerequisito(){
      $resultReq = $this->ProduccionModel->getProductos5();
      if($resultReq->num_rows>0){
        while ($r = $resultReq->fetch_assoc()) {
          $productos[]=$r;
        }
      }else{
        $productos=0;
      }

      $resultReq = $this->ProduccionModel->getUsuario();
      if($resultReq->num_rows>0){
        $set = $resultReq->fetch_assoc();
        $username=$set['username'];
        $iduser=$set['idempleado'];
      }else{
        $username='Favor de salir y loguearse nuevamente';
        $iduser='0';
      }

      $resultReq = $this->ProduccionModel->getSucursales();
      if($resultReq->num_rows>0){
        while ($r = $resultReq->fetch_assoc()) {
          $sucursales[]=$r;
        }
      }else{
        $sucursales=0;
      }

      require('views/produccion/prerequisito.php');
    }


    function ordenp(){
      $resultReq = $this->ProduccionModel->getProductos5();
      if($resultReq->num_rows>0){
        while ($r = $resultReq->fetch_assoc()) {
          $productos[]=$r;
        }
      }else{
        $productos=0;
      }

      $resultReq = $this->ProduccionModel->getUsuario();
      if($resultReq->num_rows>0){
        $set = $resultReq->fetch_assoc();
        $username=$set['username'];
        $iduser=$set['idempleado'];
      }else{
        $username='Favor de salir y loguearse nuevamente';
        $iduser='0';
      }

      $resultReq = $this->ProduccionModel->getSucursales();
      if($resultReq->num_rows>0){
        while ($r = $resultReq->fetch_assoc()) {
          $sucursales[]=$r;
        }
      }else{
        $sucursales=0;
      }
         $resultReq = $this->ProduccionModel->getEmpleados();
      if($resultReq->num_rows>0){
        while ($r = $resultReq->fetch_assoc()) {
          $empleados[]=$r;
        }
      }else{
        $empleados=0;
      }

      require('views/produccion/ordenp.php');
    }
    function a_guardarPaso(){
       
      $lote6_nolote=trim($_POST['lote6_nolote']);
      $lote6_fechafab=trim($_POST['lote6_fechafab']);
      $lote6_fechacad=trim($_POST['lote6_fechacad']);

      $costo15_adicional=trim($_POST['costo15_adicional']);
      $costo15_terminado=trim($_POST['costo15_terminado']);

      $idsProductos=trim($_POST['idsProductos']);
      $paso=trim($_POST['paso']);
      $accion=trim($_POST['accion']);
      $idop=trim($_POST['idop']);

      if($accion==1){
        $result = $this->ProduccionModel->savePaso1($idsProductos,$accion,$idop,$paso); 
      }

      if($accion==2){
        $result = $this->ProduccionModel->savePaso2($idsProductos,$accion,$idop,$paso); 
      }

      if($accion==3){
        $result = $this->ProduccionModel->savePaso3($idsProductos,$accion,$idop,$paso); 
      }

      if($accion==14){
        $result = $this->ProduccionModel->savePaso14($idsProductos,$accion,$idop,$paso); 
      }

      if($accion==7){
        $result = $this->ProduccionModel->savePaso7($idsProductos,$accion,$idop,$paso); 
      }

      if($accion==4){
        $result = $this->ProduccionModel->savePaso4($idsProductos,$accion,$idop,$paso); 
      }

      if($accion==5){
        $result = $this->ProduccionModel->savePaso5($idsProductos,$accion,$idop,$paso); 
      }

      if($accion==6){

        $result = $this->ProduccionModel->savePaso6($lote6_nolote,$lote6_fechafab,$lote6_fechacad,$idsProductos,$accion,$idop,$paso); 
      }

      if($accion==15){

        $result = $this->ProduccionModel->savePaso15($costo15_adicional,$costo15_terminado,$idsProductos,$accion,$idop,$paso); 
      }

      if($accion==9){

        $result = $this->ProduccionModel->savePaso9($accion,$idop,$paso); 
      }




    }


    function a_activar(){
       
   
      $id=trim($_POST['id']);
       $result = $this->ProduccionModel->activar($id);
        echo $result;

    }


    function a_guardarUsar(){
      $id_op=trim($_POST['id_op']);
      $iduserlog=trim($_POST['iduserlog']);

      $result = $this->ProduccionModel->saveUsar($id_op,$iduserlog);
      echo $result;
    }


    function a_guardarOrdenP(){
       
   
      $idsProductos=trim($_POST['idsProductos']);
      $fecha_registro=trim($_POST['fecha_registro']);
      $fecha_entrega=trim($_POST['fecha_entrega']);
      $prioridad=trim($_POST['prioridad']);
      $sucursal=trim($_POST['sucursal']);
      $option=trim($_POST['option']);
      $obs=trim($_POST['obs']);
      $iduserlog=trim($_POST['iduserlog']);
      $id_op=trim($_POST['id_op']);
       $ttt=trim($_POST['ttt']);
  $orden=trim($_POST['orden']);

  $sol=trim($_POST['sol']);



      if($option==1){
      $result = $this->ProduccionModel->saveOP($idsProductos,$fecha_registro,$fecha_entrega,$prioridad,$sucursal,$option,$obs,$iduserlog,$id_op,$ttt,$sol);
        echo $result;
      }

      if($option==2){
      $result = $this->ProduccionModel->modifyOP($idsProductos,$fecha_registro,$fecha_entrega,$prioridad,$sucursal,$option,$obs,$iduserlog,$id_op,$ttt,$sol);
        echo $result;
      }

      if($option==3){
       
      $result = $this->ProduccionModel->savePre($idsProductos,$fecha_registro,$fecha_entrega,$prioridad,$sucursal,$option,$obs,$iduserlog,$id_op,$ttt,$orden,$sol);
        echo $result;
      }

    }

    function a_listaOrdenesPre(){
      $resultReq =  $this->ProduccionModel->listaOrdenesPre();
      $listas=array();
      $listas['data']='';
      if($resultReq->num_rows>0){
        $acciones='Sin botones';
        while ($r = $resultReq->fetch_array()) {

          $listas['data'][]=$r;
        }
      }else{
        $listas['data']=array();
      }

      echo json_encode($listas);
      
    }

        function a_listaOrdenes(){
      $resultReq =  $this->ProduccionModel->listaOrdenes();

      $listas=array();
      $listas['data']='';
      if($resultReq->num_rows>0){
        $acciones='Sin botones';
        while ($r = $resultReq->fetch_array()) {

          $listas['data'][]=$r;
        }
      }else{
        $listas['data']=array();
      }

      echo json_encode($listas);
      
    }

        function a_listaOrdenesf(){
             $ffin=$_GET['ffin'];
                $fini=$_GET['fini'];
                    $prod=$_GET['prod'];
                        $suc=$_GET['suc'];
                            $sol=$_GET['sol'];
                             $est=$_GET['est'];
      $resultReq =  $this->ProduccionModel->listaOrdenesf($ffin,$fini,$prod,$suc,$sol,$est);
      $listas=array();
      $listas['data']='';
      if($resultReq->num_rows>0){
        $acciones='Sin botones';
        while ($r = $resultReq->fetch_array()) {

          $listas['data'][]=$r;
        }
      }else{
        $listas['data']=array();
      }

      echo json_encode($listas);
      
    }



        function a_seg(){
            $id=$_GET['id'];
      $resultReq =  $this->ProduccionModel->seg($id);
      $listas=array();
      $listas['data']='';
      if($resultReq->num_rows>0){
        $acciones='Sin botones';
        while ($r = $resultReq->fetch_array()) {

          $listas['data'][]=$r;
        }
      }else{
        $listas['data']=array();
      }

      echo json_encode($listas);
      
    }

       function a_seg2(){
            $id=$_GET['id'];
      $resultReq =  $this->ProduccionModel->seg2($id);
      $listas=array();
      $listas['data']='';
      if($resultReq->num_rows>0){
        $acciones='Sin botones';
        while ($r = $resultReq->fetch_array()) {

          $listas['data'][]=$r;
        }
      }else{
        $listas['data']=array();
      }

      echo json_encode($listas);
      
    }

          function a_segl(){
            $id=$_GET['id'];
      $resultReq =  $this->ProduccionModel->segl($id);
      $listas=array();
      $listas['data']='';
      if($resultReq->num_rows>0){
        $acciones='Sin botones';
        while ($r = $resultReq->fetch_array()) {

          $listas['data'][]=$r;
        }
      }else{
        $listas['data']=array();
      }

      echo json_encode($listas);
      
    }




    function a_listaOrdenesP() {

      $resultReq =  $this->ProduccionModel->listaOrdenesP();



           $row =  $this->ProduccionModel->bandera();
$bandera=$row['aut_ord_prod'];
$orden=$row['genoc_sinreq'];


      $listas=array();
      $listas['data']='';
      if($resultReq->num_rows>0){
        $acciones='Sin botones';
        while ($r = $resultReq->fetch_array()) {

          /*
          $link='<a  class="btn btn-default btn-xs btn-block">'.$r['id'].'</span></a>';
          $elimin='<a style="margin-top:4px;" onclick="editReq('.$r['id'].',0);" class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-search"></span> Ver</a>
            <a style="margin-top:4px;" onclick="" class="btn btn-default btn-xs disabled"><span class="glyphicon glyphicon-remove"></span> Borrar</a>';
          if($r['urgente']==0){
            $r[5]='<span class="label label-default" style="cursor:pointer;">Normal</span>';
          }
          if($r['urgente']==1){
            $r[5]='<span class="label label-danger" style="cursor:pointer;">Urgente</span>';
          }
          if($r['activo']==0){
            $elimin='<a style="margin-top:4px;" onclick="editReq('.$r['id'].');"  class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-edit"></span> Editar</a>
            <a style="margin-top:4px;" onclick="eliminaReq('.$r['id'].')" class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-remove"></span> Inactivar </a>';
            $r[6]='<span class="label label-warning" style="cursor:pointer;">Nueva</span>';
          }
          if($r['activo']==1){

            $r[6]='<span class="label label-success" style="cursor:pointer;">OV Autorizada</span>';
          }
          if($r['activo']==2){

            $link='<a href="#" class="btn btn-default btn-xs disabled btn-block">'.$r['id'].'</a>';
            $r[6]='<span class="label label-default" style="cursor:pointer;">Inactiva</span>';
          }
          if($r['activo']==3){

            $link='<a href="#" class="btn btn-default btn-xs disabled btn-block">'.$r['id'].'</a>';
            $r[6]='<span class="label label-success" style="cursor:pointer;">OV activa</span>';
            $elimin='<a style="margin-top:4px;" onclick="editReq('.$r['id'].',0);" class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-search"></span> Ver</a>
            <a style="margin-top:4px;" onclick="" class="btn btn-default btn-xs disabled"><span class="glyphicon glyphicon-remove"></span> Borrar.</a>';
          }

          if($r['activo']==4){

            $link='<a href="#" class="btn btn-default btn-xs disabled btn-block">'.$r['id'].'</a>';
            $r[6]='<span class="label label-success" style="cursor:pointer;">OK recibida ok</span>';
            $elimin='<a style="margin-top:4px;" onclick="editReq('.$r['id'].',0);" class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-search"></span> Ver</a>
            <a style="margin-top:4px;" onclick="" class="btn btn-default btn-xs disabled"><span class="glyphicon glyphicon-remove"></span> Borrar.</a>';
          }

          if($r['aceptada']==1){
            $r[6].=' <span class="label label-success" style="cursor:pointer;">Aceptada por cliente</span>';
          }

          
          

          $r[7]=$elimin;
          if($r['cadenaCoti']!=null){
            $r[7].=' <button style="margin-top:4px;"  onclick="vercomcli(\''.$r['cadenaCoti'].'\');" class="btn btn-default btn-xs">Comentarios </button>';
          }
          $r[7].=' <button style="margin-top:4px;" id="btn_imprimir_'.$r['id'].'_" onclick="imprimir2('.$r['id'].',2);" class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-print"></span> </button>';
*/



           if($r['autorizado']=='0' && $bandera=='1'){
$boton='<button  style="margin-top:4px;" onclick="autorizar('.$r['id'].');"  class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-edit"></span> Autorizar</button>';
        

            }
elseif($r['estatus']==1){
  $boton='<button  style="margin-top:4px;" onclick="explosionMat('.$r['id'].' , '.$orden.');"  class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-edit"></span> Explosion de materiales</button>';


}
            else{
$boton='<button disabled style="margin-top:4px;" onclick="explosionMat('.$r['id'].','.$orden.');"  class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-edit"></span> Explosion de materiales</button>';


            }



          if($r['estatus']==0){
            $r[6]='<span class="label label-danger" style="cursor:pointer;">Orden eliminada</span>';
            $acciones='<button disabled style="margin-top:4px;" onclick="editReq('.$r['id'].');"  class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-edit"></span> Editar orden</button> '.$boton;
          }


          if($r['estatus']==1){
            $r[6]='<span class="label label-default" style="cursor:pointer;">Registro inicial</span>';
            $acciones='<button style="margin-top:4px;" onclick="editReq('.$r['id'].');"  class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-edit"></span> Editar orden</button> '.$boton.' <button style="margin-top:4px;" onclick="eliminarOP('.$r['id'].');"  class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-remove"></span> Eliminar</button>';
          }

          if($r['estatus']==2){
            $r[6]='<span class="label label-warning" style="cursor:pointer;">En espera de insumos</span>';
            $acciones='<button disabled style="margin-top:4px;" onclick="editReq('.$r['id'].');"  class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-edit"></span> Editar orden</button> '.$boton.'<button onclick="abrirNueva(1);" style="margin-top:4px;" class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-edit"></span> Pre-Requisiciones</button> <!--<button style="margin-top:4px;" onclick="cancelarTodo('.$r['id'].');"  class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-remove"></span> Cancelar</button>-->';
          }

          if($r['estatus']==10){
            $r[6]='<span class="label label-success" style="cursor:pointer;">Produccion finalizada</span>';
            $acciones='';
          }

          if($r['estatus']==3){
            $r[6]='<span class="label label-success" style="cursor:pointer;">Lista para producir</span>';
            $acciones='<button onclick="abrirNueva(1);" style="margin-top:4px;" class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-edit"></span> Pre-Requisiciones</button> <button style="margin-top:4px;" onclick="ciclo('.$r['id'].');"  class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-edit"></span> Ejecutar ciclo</button>';
          }

          if($r['estatus']==4){
            $r[6]='<span class="label label-success" style="cursor:pointer;">Lista para producir</span>';
            $acciones='<button style="margin-top:4px;" onclick="ciclo('.$r['id'].');"  class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-edit"></span> Ejecutar ciclo</button>';
          }

          if($r['estatus']==9){
            $r[6]='<span class="label label-info" style="cursor:pointer;">Produccion iniciada</span>';
            $acciones=' <button style="margin-top:4px;" onclick="ciclo('.$r['id'].');"  class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-edit"></span> Ejecutar ciclo</button>';
          }

          //$acciones.='<button style="margin-top:4px;" onclick="ciclo('.$r['id'].');"  class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-edit"></span> Ejecutar ciclo</button>';

          

          $r[7]=$acciones;
          $listas['data'][]=$r;
        }
      }else{
        $listas['data']=array();
      }

      echo json_encode($listas);
  

    }

    function a_autorizar(){
   $id=$_POST['id'];
    $this->ProduccionModel->autorizar($id);

    }

    function a_editarordenp(){
      $idReq=$_POST['idReq'];
      $m=$_POST['m'];
      $mod=$_POST['mod'];
      $pr=$_POST['pr']; //proviene
      $resultReq = $this->ProduccionModel->editarordenp($idReq);

      if($resultReq->num_rows>0){
      
        $row = $resultReq->fetch_assoc();
        $row['fi']=substr($row['fi'],0,10);
        $row['fe']=substr($row['fe'],0,10);

        $row2['adds']='';
 
        $resultReq2 = $this->ProduccionModel->productosOp($idReq,$m);
        while ($row2 = $resultReq2->fetch_assoc()) {

          $productos[]=$row2;
        }
       // $row2 = $resultReq2->fetch_assoc();
        
        $JSON = array('success' =>1, 'requisicion'=>$row, 'productos'=>$productos, 'adds'=>$adds, 'ss'=>0);
      }else{
        $JSON = array('success' =>0);
      }
      
      echo json_encode($JSON);

    }

    function a_listaPaso5(){
      $idop=$_POST['idop'];
      $tr5='';
        $rsqlpaso4 = $this->ProduccionModel->sqlPaso4($idop);
        if($rsqlpaso4->num_rows>0){

          while ($rowSqlpaso4 = $rsqlpaso4->fetch_assoc()) {
            $tr5.='<tr id="tr_empp_'.$rowSqlpaso4['idEmpleado'].'"><td>'.$rowSqlpaso4['nombre'].'</td>
            <td><input id="maq_'.$rowSqlpaso4['idmaq'].'" type="text" class="form-control" value="'.$rowSqlpaso4['maquinaria'].'"></td>
            </tr>';
          }
        }else{
          $JSON = array('tr5'=>0);
        }

        $JSON = array('tr5'=>$tr5);

      
      echo json_encode($JSON);

    }

    function a_clipaso(){
      $idop=$_POST['idop'];
      $paso=$_POST['paso'];
      $accion=$_POST['accion'];

      if($accion==1){
        $insumos=array();
        $resInsumos = $this->ProduccionModel->productosOpExplosion($idop);
        if($resInsumos['total']>0){
          foreach ($resInsumos['rows'] as $k => $v) {
            $existencias = $this->ProduccionModel->getExistencias($v['idProducto'],'0');
            if($existencias[0]['cantidad']==null){
              $g=0;
            }else{
              $g=$existencias[0]['cantidad'];
            }
            $resInsumos['rows'][$k]['existen']=$g;
          }
        }else{
          $JSON = array('success' =>0);
          echo json_encode($JSON);
          exit();
        }

        $JSON = array('success' =>1, 'data'=>$resInsumos['rows']);
        echo json_encode($JSON);
        exit();
      }

      if($accion==2){
          $insumos=array();
          $resInsumos = $this->ProduccionModel->productosOpExplosion($idop);
          if($resInsumos['total']>0){
            foreach ($resInsumos['rows'] as $k => $v) {
              $rsqlpaso2 = $this->ProduccionModel->sqlPaso2($idop,$v['idProducto']);
              if($rsqlpaso2->num_rows>0){
                $rowSqlpaso2 = $rsqlpaso2->fetch_assoc();
                $resInsumos['rows'][$k]['cantidad2']=$rowSqlpaso2['cantUti'];
              }else{
                $resInsumos['rows'][$k]['cantidad2']=0.00;
              }
            }
          }else{
            $JSON = array('success' =>0);
            echo json_encode($JSON);
            exit();
          }

          $JSON = array('success' =>1, 'data'=>$resInsumos['rows']);
          echo json_encode($JSON);
          exit();
      }

      if($accion==3){
          $insumos=array();
          $resInsumos = $this->ProduccionModel->productosOpExplosion($idop);
          if($resInsumos['total']>0){
            foreach ($resInsumos['rows'] as $k => $v) {
              $rsqlpaso3 = $this->ProduccionModel->sqlPaso3($idop,$v['idProducto']);
              if($rsqlpaso3->num_rows>0){
                $rowSqlpaso3 = $rsqlpaso3->fetch_assoc();
                $resInsumos['rows'][$k]['peso']=$rowSqlpaso3['pesoUti'];
              }else{
                $resInsumos['rows'][$k]['peso']=0.00;
              }
            }
          }else{
            $JSON = array('success' =>0);
            echo json_encode($JSON);
            exit();
          }

          $JSON = array('success' =>1, 'data'=>$resInsumos['rows']);
          echo json_encode($JSON);
          exit();
      }

      if($accion==14){
          $insumos=array();
          $resInsumos = $this->ProduccionModel->productosOpExplosion($idop);
          if($resInsumos['total']>0){
            foreach ($resInsumos['rows'] as $k => $v) {
              $rsqlpaso14 = $this->ProduccionModel->sqlPaso14($idop,$v['idProducto']);
              if($rsqlpaso14->num_rows>0){
                $rowSqlpaso14 = $rsqlpaso3->fetch_assoc();
                $resInsumos['rows'][$k]['merma']=$rowSqlpaso14['merma'];
              }else{
                $resInsumos['rows'][$k]['merma']=0.00;
              }
            }
          }else{
            $JSON = array('success' =>0);
            echo json_encode($JSON);
            exit();
          }

          $JSON = array('success' =>1, 'data'=>$resInsumos['rows']);
          echo json_encode($JSON);
          exit();
      }

      if($accion==4){
          $tr='';
          $tr5='';
          $rsqlpaso4 = $this->ProduccionModel->sqlPaso4($idop);
          if($rsqlpaso4->num_rows>0){
            while ($rowSqlpaso4 = $rsqlpaso4->fetch_assoc()) {
              $tr.='<tr id="tr_empp_'.$rowSqlpaso4['idEmpleado'].'"><td>'.$rowSqlpaso4['nombre'].'</td><td><button id="eliemp4" style=" padding: 0px;  height:33px;" onclick="eliemp4('.$rowSqlpaso4['idEmpleado'].');" class="btn btn-danger btn-sm btn-block">Elimina</button></td></tr>';
            }
            $JSON = array('success' =>1, 'data'=>$tr);
            echo json_encode($JSON);
            exit();
          }else{
            $JSON = array('success' =>1, 'data'=>'');
            echo json_encode($JSON);
            exit();
          }
      }

      if($accion==5){
          $tr5='';
          $rsqlpaso4 = $this->ProduccionModel->sqlPaso4($idop);
          if($rsqlpaso4->num_rows>0){
            while ($rowSqlpaso4 = $rsqlpaso4->fetch_assoc()) {
              $tr5.='<tr id="tr_empp_'.$rowSqlpaso4['idEmpleado'].'"><td>'.$rowSqlpaso4['nombre'].'</td>
              <td><input id="maq_'.$rowSqlpaso4['idmaq'].'" type="text" class="form-control" value="'.$rowSqlpaso4['maquinaria'].'"></td>
              </tr>';
            }
            $JSON = array('success' =>1, 'data'=>$tr5);
            echo json_encode($JSON);
            exit();
          }else{
            $JSON = array('success' =>1, 'data'=>'');
            echo json_encode($JSON);
            exit();
          }
      }

      if($accion==6){
        $lotes=array();
        $rsqlpaso6 = $this->ProduccionModel->sqlPaso6($idop);
        if($rsqlpaso6->num_rows>0){
          $rowSqlpaso6 = $rsqlpaso6->fetch_assoc();
          $lotes[0]=substr($rowSqlpaso6['no_lote'],0,10);
          $lotes[1]=substr($rowSqlpaso6['fecha_fabricacion'],0,10);
          $lotes[2]=substr($rowSqlpaso6['fecha_caducidad'],0,10);
          $JSON = array('success' =>1, 'data'=>$lotes);
            echo json_encode($JSON);
            exit();
        }else{
          $JSON = array('success' =>1, 'data'=>0);
            echo json_encode($JSON);
            exit();
        }
      }

      if($accion==15){
        $costos=array();
        $rsqlpaso15 = $this->ProduccionModel->sqlPaso15($idop);
        if($rsqlpaso15->num_rows>0){
          $rowSqlpaso15 = $rsqlpaso15->fetch_assoc();
          $costos[0]=$rowSqlpaso15['costo_adicional'];
          $costos[1]=$rowSqlpaso15['costo_total'];
          $JSON = array('success' =>1, 'data'=>$costos);
            echo json_encode($JSON);
            exit();
        }else{
          $JSON = array('success' =>1, 'data'=>0);
            echo json_encode($JSON);
            exit();
        }
      }

      if($accion==7){
          $insumos=array();
          $resInsumos = $this->ProduccionModel->productosOpExplosion($idop);
          if($resInsumos['total']>0){
            foreach ($resInsumos['rows'] as $k => $v) {
              $rsqlpaso7 = $this->ProduccionModel->sqlPaso7($idop,$v['idProducto']);
              if($rsqlpaso7->num_rows>0){
                $rowSqlpaso7 = $rsqlpaso7->fetch_assoc();
                $resInsumos['rows'][$k]['cbatch']=$rowSqlpaso7['cbatch'];
              }else{
                $resInsumos['rows'][$k]['cbatch']=0.00;
              }
            }
          }else{
            $JSON = array('success' =>0);
            echo json_encode($JSON);
            exit();
          }

          $JSON = array('success' =>1, 'data'=>$resInsumos['rows']);
          echo json_encode($JSON);
          exit();
      }

      if($accion==9){

            $JSON = array('success' =>1);
            echo json_encode($JSON);
            exit();

      }

    }

    function a_explosionMatCiclo(){
        $idop=$_POST['idop'];
        $resultReq = $this->ProduccionModel->editarordenp($idop);
        $lospasos = $this->ProduccionModel->listar_pasos_op($idop);

        if($lospasos['total']>0){
          $JSON = array('success' =>1, 'data'=>$lospasos['rows']);
        }else{
          $JSON = array('success' =>0);
        }

        echo json_encode($JSON);

        exit();
      if($resultReq->num_rows>0){
      
        $row = $resultReq->fetch_assoc();
        $row['fi']=substr($row['fi'],0,10);
        $row['fe']=substr($row['fe'],0,10);

        $row2['adds']='';

        $tr='';
        $tr5='';
        $rsqlpaso4 = $this->ProduccionModel->sqlPaso4($idop);
        if($rsqlpaso4->num_rows>0){

          while ($rowSqlpaso4 = $rsqlpaso4->fetch_assoc()) {
            $tr.='<tr id="tr_empp_'.$rowSqlpaso4['idEmpleado'].'"><td>'.$rowSqlpaso4['nombre'].'</td><td><button id="eliemp4" style=" padding: 0px;  height:33px;" onclick="eliemp4('.$rowSqlpaso4['idEmpleado'].');" class="btn btn-danger btn-sm btn-block">Elimina</button></td></tr>';
            $tr5.='<tr id="tr_empp_'.$rowSqlpaso4['idEmpleado'].'"><td>'.$rowSqlpaso4['nombre'].'</td>
            <td><input id="maq_'.$rowSqlpaso4['idmaq'].'" type="text" class="form-control" value="'.$rowSqlpaso4['maquinaria'].'"></td>
            </tr>';
          }
        }else{
        
        }

        $lotes=array();
        $rsqlpaso6 = $this->ProduccionModel->sqlPaso6($idop);
        if($rsqlpaso6->num_rows>0){
          $rowSqlpaso6 = $rsqlpaso6->fetch_assoc();
          $lotes[0]=substr($rowSqlpaso6['no_lote'],0,10);
          $lotes[1]=substr($rowSqlpaso6['fecha_fabricacion'],0,10);
          $lotes[2]=substr($rowSqlpaso6['fecha_caducidad'],0,10);
        }else{
          $lotes=0;
        }

        $costos=array();
        $rsqlpaso15 = $this->ProduccionModel->sqlPaso15($idop);
        if($rsqlpaso15->num_rows>0){
          $rowSqlpaso15 = $rsqlpaso15->fetch_assoc();
          $costos[0]=$rowSqlpaso15['costo_adicional'];
          $costos[1]=$rowSqlpaso15['costo_total'];
        }else{
          $costos=0;
        }




 
        $insumos=array();
        $resultReq2 = $this->ProduccionModel->productosOp($idop,1);
        while ($row2 = $resultReq2->fetch_assoc()) {

          $resultReq3 = $this->ProduccionModel->productosOpExplosion($idop,$row2['id_producto']);
          if($resultReq3->num_rows>0){
            while ($row3 = $resultReq3->fetch_assoc()) {
              $rsqlpaso2 = $this->ProduccionModel->sqlPaso2($idop,$row3['idProducto']);
              if($rsqlpaso2->num_rows>0){
                $rowSqlpaso2 = $rsqlpaso2->fetch_assoc();
                $row3['cantidad2']=$rowSqlpaso2['cantUti'];
              }else{
                $row3['cantidad2']='';
              }

              $rsqlpaso3 = $this->ProduccionModel->sqlPaso3($idop,$row3['idProducto']);
              if($rsqlpaso3->num_rows>0){
                $rowSqlpaso3 = $rsqlpaso3->fetch_assoc();
                $row3['peso']=$rowSqlpaso3['pesoUti'];
              }else{
                $row3['peso']=0;
              }

              $rsqlpaso14 = $this->ProduccionModel->sqlPaso14($idop,$row14['idProducto']);
              if($rsqlpaso14->num_rows>0){
                $rowSqlpaso14 = $rsqlpaso14->fetch_assoc();
                $row14['merma']=$rowSqlpaso14['merma'];
              }else{
                $row14['merma']=0;
              }

              $rsqlpaso8 = $this->ProduccionModel->sqlPaso8($idop,$row3['idProducto']);
              if($rsqlpaso8->num_rows>0){
                $rowSqlpaso8 = $rsqlpaso8->fetch_assoc();
                $row3['cbatch']=$rowSqlpaso8['cbatch'];
              }else{
                $row3['cbatch']=0;
              }


        
        $existencias = $this->ProduccionModel->getExistencias($row3['idProducto'],'0');
        if($existencias[0]['cantidad']==null){
          $g=0;
        }else{
          $g=$existencias[0]['cantidad'];
        }
        $row3['existen']=$g;


    


              //consulta pa los proveedores y costos
              if($row3['idcostoprovs']!=''){
                $resultReq4 = $this->ProduccionModel->proveedoresCostoOP($row3['idcostoprovs']);
                if($resultReq4->num_rows>0){
                  $cadprovs="<select id='cmbProv_".$row2['id_producto']."_".$row3['idProducto']."' onchange='refreshCants(".$row3['idProducto'].",".$row2['id_producto'].");' id='insprv'><option value='0-0'>Seleccione</option>";
                  while ($row4 = $resultReq4->fetch_assoc()) {
                    $cadprovs.="<option value='".$row4['id_proveedor']."-".$row4['costo']."'>".$row4['razon_social']."</option>";
                  }
                  $cadprovs.='</select>';
                }else{
                  $cadprovs="<select id='insprv'><option value='0-0'>No hay proveedores para este producto</option></select>";
                }
              }else{
                $cadprovs="<select id='insprv'><option value='0-0'>No hay proveedores</option></select>";

              }

              $row3['listprovs']=$cadprovs;
              $row2['insumos'][]=$row3;

            }

          }else{
            $row2['insumos']=0;
          }

          $productos[]=$row2;
        }



       // $row2 = $resultReq2->fetch_assoc();
        
        $JSON = array('success' =>1, 'requisicion'=>$row, 'productos'=>$productos, 'adds'=>$adds, 'ss'=>0, 'tr'=>$tr, 'tr5'=>$tr5, 'lotes'=>$lotes, 'costos'=>$costos);
      }else{
        $JSON = array('success' =>0);
      }
      
      echo json_encode($JSON);

    }

    function a_explosionMat(){
      $idop=$_POST['idop'];
          $resultReq = $this->ProduccionModel->editarordenp($idop);

      if($resultReq->num_rows>0){
      
        $row = $resultReq->fetch_assoc();
        $row['fi']=substr($row['fi'],0,10);
        $row['fe']=substr($row['fe'],0,10);

        $row2['adds']='';
 
        $insumos=array();
        $resultReq2 = $this->ProduccionModel->productosOp($idop,1);
        while ($row2 = $resultReq2->fetch_assoc()) {


          $resultReq3 = $this->ProduccionModel->productosOpExplosion($idop,$row2['id_producto']);
          if($resultReq3['total']>0){
            foreach ($resultReq3['rows'] as $key => $row3) {

              //consulta pa los proveedores y costos
              if($row3['idcostoprovs']!=''){
                $existencias = $this->ProduccionModel->getExistenciasT($row3['idProducto'],'0');
                $resultReq4 = $this->ProduccionModel->proveedoresCostoOP($row3['idcostoprovs']);
                if($resultReq4->num_rows>0){
                  $cadprovs="<select id='cmbProv_".$row2['id_producto']."_".$row3['idProducto']."' onchange='refreshCants(".$row3['idProducto'].",".$row2['id_producto'].");' id='insprv'><option value='0-0'>Seleccione</option>";
                  while ($row4 = $resultReq4->fetch_assoc()) {
                    $cadprovs.="<option value='".$row4['id_proveedor']."-".$row4['costo']."'>".$row4['razon_social']."</option>";
                  }
                  $cadprovs.='</select>';
                }else{
                  $cadprovs="<select id='insprv'><option value='0-0'>No hay proveedores para este producto</option></select>";
                }
              }else{
                $cadprovs="<select id='insprv'><option value='0-0'>No hay proveedores</option></select>";

              }

              $row3['listprovs']=$cadprovs;
              $row3['existencias']=$existencias;
              $row2['insumos'][]=$row3;




            }

          }else{
            $row2['insumos']=0;
          }

          $productos[]=$row2;
        }



       // $row2 = $resultReq2->fetch_assoc();
        
        $JSON = array('success' =>1, 'requisicion'=>$row, 'productos'=>$productos, 'adds'=>$adds, 'ss'=>0);
      }else{
        $JSON = array('success' =>0);
      }
      
      echo json_encode($JSON);

    }

    function a_eliminaOP()
    {
      $idop=$_POST['idop'];
      $resultReq = $this->ProduccionModel->delOP($idop);
      echo $resultReq;

    }


}


?>
