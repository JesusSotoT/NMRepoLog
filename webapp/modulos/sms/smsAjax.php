<?php
    include("../../netwarelog/webconfig.php");
    include_once('funcionesBD/conexion.php');

    if(isset($_POST['con']) && $_POST['con']==1){
        $consult = new Consult;
        $conection = $consult -> conection($servidor,'nmdevel','nmdevel','nmdev_common');
    }else{
        $consult = new Consult;
        $conection = $consult -> conection($servidor,$usuariobd,$clavebd,$bd);
    }

    $funcion=$_POST['funcion'];

    switch ($funcion) {
        
        case 'desplegar_articulos':
            $id=$_POST['id'];
            $result=$consult->desplegar_articulos($conection,$id);
            $row=$result->fetch_array(MYSQLI_ASSOC);
            $JSON=array();
            if($row==null){
                $JSON = array('success' =>0,
                    'error'=>'772', 
                    'mensaje'=>'No existe el articulo'
                );
            }else{
                $result=$consult->desplegar_articulos_comentarios($conection,$id);
                if($result->num_rows>=1){  
                    while($row2=$result->fetch_array(MYSQLI_ASSOC)){ 
                        $coms[]=$row2;
                    }
                }else{
                    $coms=0;
                }
                $JSON = array('success' =>1,
                    'foto'=>$row['imagen'], 
                    'descripcion'=>$row['contenido'],
                    'coms'=>$coms
                );
            }
            echo json_encode($JSON);
            exit();
        break;

        case 'crear_articulo':
            $titulo=$_POST['titulo'];
            $contenido=$_POST['contenido'];

            $result=$consult->crear_articulo($conection,$titulo,$contenido,$bd);
        break;

        case 'crear_comentario':
            $coment=$_POST['coment'];
            $id=$_POST['id'];

            $result=$consult->crear_comentario($conection,$coment,$id,$bd);
        break;

        case 'invitar_cliente':
            $c=$_POST['c'];
            $c=trim($c,',');

             

            $result=$consult->invitar_cliente($conection,$c,$bd);
        break;

    	case 'sms_busqueda':
    		$tipo=$_POST['tipo'];
    		if($tipo==1){
    			$a1=$_POST['cmbEstados'];
    			$a2=$_POST['cmbGrupos'];
    			$a3=$_POST['cmbRubros'];
    			$a4=$_POST['cmbTtienda'];
    			$qc='';
                $ij='';
    			if($a1==0){
    				$qc.=' ';
    			}else{
    				$qc.=' AND a.idEstado='.$a1.' ';
    			}

    			if($a2==0){
    				$qc.=' ';
    			}else{
                    $ij=' INNER JOIN sms_cliente_grupo b ON b.id_cliente=a.id ';
    				$qc.=' AND b.id_grupo='.$a2.' ';
    			}

    			if($a3==0){
    				$qc.=' ';
    			}else{
    				$qc.=' AND a.idRubro='.$a3.' ';
    			}

    			if($a4==0){
    				$qc.=' ';
    			}else{
    				$qc.=' AND a.idTipotienda='.$a4.' ';
    			}

              /*  if(isset($_POST['con']) && $_POST['con']==1){
                    $plus=' AND a.id not in (SELECT id )'
                }
*/
    			$query="SELECT a.id,a.nombre FROM comun_cliente a ".$ij." WHERE 1 ".$qc." ";

    		}
    		if($tipo==0){
    			$b1=$_POST['cmbClientes'];
    			$qc='';
    			if($b1==0){
    				$qc.=' ';
    			}else{
    				$qc.=' AND id='.$b1.' ';
    			}

    			$query="SELECT id,nombre FROM comun_cliente WHERE 1 ".$qc." ";
    			
    		}

    		$result=$consult->busquedaSMS($conection,$query);
    		if($result->num_rows>=1){  
		      while($row=$result->fetch_array(MYSQLI_ASSOC)){ 
		        $res[]=$row;
		      }
		    }else{
		        $res=0;
		    }
		    $JSON = json_encode($res);
		    echo $JSON;

		break;

		case 'asignar_grupo':
			$c=$_POST['c'];
			$g=$_POST['g'];

			$c=trim($c,',');
			$g=trim($g,',');

			$result=$consult->asignar_grupo($conection,$c,$g);
		break;

        case 'verificaDisponibilidad':
            session_start();

            $id_empleado=$_SESSION['accelog_idempleado'];
            $id_producto=$_POST['id_producto'];
            $cantidad=$_POST['cantidad'];

            $result=$consult->verificaDisponibilidad($conection,$id_producto,$cantidad,$id_empleado);
            $row=$result->fetch_array(MYSQLI_ASSOC);
            if($row==''){
                echo 0;
            }else{
                echo $row['total'];
            }

           // var_dump($_SESSION);
        break;
    	
    }
?>