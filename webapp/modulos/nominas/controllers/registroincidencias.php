
               <?php
               require('controllers/catalogos.php');
               require("models/registroincidencias.php");

               class registroincidencias extends Catalogos
               {
                 public $incidenciasModel;
                 public $CatalogosModel;

                 function __construct()
                 {

                   $this->incidenciasModel = new incidenciasModel();
                   $this->CatalogosModel = $this->incidenciasModel;
                   $this->incidenciasModel->connect();

                }

                function __destruct()
                {

                   $this->incidenciasModel->close();
                }

                /* I N C I D E N C I A S   I N T E R F A Z */

                function vistaIncidencias(){
                   $nominasPeriodo     = $this->CatalogosModel->nominasPeriodo();
                   $conceptosConfig        = $this->CatalogosModel->conceptosPrenominaExiste();
                   require("views/registroincidencias.php");
                }

                    //obtiene la fecha inicio y fecha fin 
                function rangofechas(){
                   $rangoFechas    = $this->CatalogosModel->rangoFechas($_REQUEST['fi'],$_REQUEST['ff'],'d-m-Y');
                   echo json_encode($rangoFechas);
                }

                function empleadosNomina(){ 
                       //traemos de nuevo el rango de fechas. Para relacionar los valores que trae el proc.
                       //almacenado 'traerincidencias()' con cada fecha 
                   $rangoFechas  = $this->CatalogosModel->rangoFechas($_REQUEST['fechaIni'],$_REQUEST['fechaFin'], 'Y-m-d');

                           //llama al modelo que ejecutará el sp 'traerIncidencias()'. Recibe el id del periodo
                           //como parámetro
                   $clavesIncidencias = $this->incidenciasModel->claveIncidencias($_REQUEST['idnomp']);

                   $editable = $_REQUEST['editable'];

                   if($clavesIncidencias!=0){
                           while($e = $clavesIncidencias ->fetch_object()){  //recorremos el resultado de 
                                                                   //clavesIncidencias
                             echo "<tr onMouseDown='adicional(".$e->idempleado.");' id='".$e->idempleado."'><td>".$e->codigo."</td>
                             <td>".strtoupper($e->nombreEmpleado . " ". $e->apellidoPaterno)."</td>";  

                               //las columnas que traen los valores de $traerIncidencias, vienen con el 
                               //nombre de cada día. p.ej: 
                               //idempleado, clave, '2017-01-01', '2017-01-02', '2017-01-03', '2017-01-04'
                               //el arreglo $rangoFechas trae las mismas fechas. ej:
                               //{'2017-01-01', '2017-01-02', '2017-01-03', '2017-01-04'} y usaremos ese  
                               //valor para acceder a cada columna de $traerIncidencias.

                               //recorremos el arreglo $rangoFechas para tener acceso a cada valor
                      for ($f=0 ; $f < count($rangoFechas); $f++){

                            echo "<td  style='position:relative; font-size:11px;height:35px;';> ";
                            echo "<br>";
                            echo "<div  class='scrollbar' style='text-align:left;overflow:scroll; height:3.5em;padding-top:1em;width:70px'>";

                                  // echo"<button  style='overflow:scroll; height:20px;''>";

                             //  echo "  <td style='text-align:left;position:relative; height:2.0em'>"; 

                                   //obtenemos el valor de $rangoFechas[] y lo convertimos a un string.
                                   //éste será el nombre del campo que consultaremos en cada fila de //$clavesIncidencias
                               $fieldname =  (string)$rangoFechas[$f]; 
                                   //$fieldname se sustituye por su valor. Ej. si $fieldname = '2017-01-01',
                                   //se sustituiría por $e->'2017-01-01', si $fildname='2017-01-02', se 
                                   //sustituye por $e->'2017-01-02' aqui
                               echo $e->$fieldname; 
                                  // echo"</button>";
                               echo "</div>";
                                   //el elemento a, al hacer click manda llamar tablaincidencias(), le manda
                                   //como parámetro idempleado, idnomp y el valor de la fecha
                                   //como parámetro idempleado, idnomp y el valor de la fecha
                                   //
                               $fieldSobrerecibo = $fieldname."_SR";

                               //echo "[".$e->$fieldSobrerecibo."]";
                               if($editable == 1  && $e->$fieldSobrerecibo ==  0 ){
                                echo "<a style='display:block; right:.8em; position:absolute; top:.2em;' class='agre fa fa-pencil-square-o fa-2x' data-toggle='modal' data-target='#myModal' onclick='tablaincidencias(".$e->idempleado.",".$_REQUEST['idnomp'].",\"".$rangoFechas[$f]."\")'></a>";
                                if($e->$fieldname !=""){
                                   echo "<a style='display:block; left:2.2em; position:absolute; top:.2em' class='dele fa fa-trash-o fa-2x'   onclick='eliminarincidencia(".$e->idempleado.",".$_REQUEST['idnomp'].",\"".$fieldname."\")'></a>";
                                }
                             }
                             echo "</td>";
                          }

                       }

                       echo "</tr>" ;  
                    }
                  //   else{
                  //    echo "<tr><td colspan='".$_REQUEST['cantDias']."'>No tiene ningun empleado dado de alta en este periodo</td></tr>";
                  // }   
               }

               function tablaincidencias(){


                      $clavesinci     = $this->incidenciasModel->valoresincidencias();
                      $listadotabla   = $this->incidenciasModel->tablaincidencias();//consulta de la funcion del model


               //validar las filas que se pueden acumular, en 1 
                      if($listadotabla!=0){ 
                         while($e = $listadotabla->fetch_object()){
                           $combinable ="";
                           if ($e->puedecombinar == 1){
                             $combinable = "combinable";
                          }
                               //Carga la tabla que se muestra en el modal
                          echo "<tr id='".$e->clave."' class='trncidencia ".$combinable."' onclick='togglebackground(\"".$e->clave."\",".$e->idtipoincidencia.")' onMouseDown='incidencia(\"".$e->clave."\",".$e->idtipoincidencia.",".$e->autorizado.")'>
                          <td align='left' >".strtoupper($e->clave)."</td>
                          <td align='left' >".$e->nomi."</td>
                          <td align='left' >".$e->nombcla."</td>
                          <td > <input type='text' class='valor textfield' disabled value='' size='5 id='extra7' name='extra7' onkeypress='return isNumber(event)'' />
                           <input type = 'hidden' class='tipoIncidencia' value='".$e->idtipoincidencia."'/>
                           <input type = 'hidden'  value='".$e->autorizado."'/>
                        </td>    
                     </tr>"; 
                  }
               }
             //   else{
             //    echo "<tr><td colspan='3'>No tiene ninguna incidencia registrada.</td></tr>";
             // }   

          }

          function almacenaincidencia(){
           $arregloDatos = json_encode($_REQUEST['arreglo']);
           $arregloDatos = json_decode($arregloDatos);

           $conf = $this->incidenciasModel->almacenaincidencia($arregloDatos, $_REQUEST['idempleado'], $_REQUEST['idnomp'] );

           if($conf == 1){
             echo 'Información almacenada.';  
          }
          else{
            echo 'Error de almacenamiento.'; 
         }
      }


      function eliminarincidencia(){


         $eliminaInci= $this ->incidenciasModel->eliminarincidencia($_REQUEST['idempleado'],$_REQUEST['idnomp'],$_REQUEST['fecha']);

         if($eliminaInci == 1){
          echo 'Se eliminó correctamente .';  
       }
       else{
         echo 'Error al eliminar.'; 

      }
   }
   }
   ?>