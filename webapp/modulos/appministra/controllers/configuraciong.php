<?php
//Carga la funciones comunes top y footer
require('common.php');

//Carga el modelo para este controlador
require("models/configuraciong.php");

class Configuraciong extends Common
{
	public $ConfiguraciongModel;

	function __construct()
	{
		//Se crea el objeto que instancia al modelo que se va a utilizar

		$this->ConfiguraciongModel = new ConfiguraciongModel();
		$this->ConfiguraciongModel->connect();
	}

	function __destruct()
	{
		//Se destruye el objeto que instancia al modelo que se va a utilizar
		$this->ConfiguraciongModel->close();
	}

	// === CH@
    

    
    
    
    function configGeneral(){
    	$pais = 1;
    	$miOrganizacion = $this->ConfiguraciongModel->miOrganizacion();
    	$regimen 		= $this->ConfiguraciongModel->regimen();
    	$pais 			= $this->ConfiguraciongModel->pais();
    	$estados 		= $this->ConfiguraciongModel->estados($pais);
    	$municipios     = $this->ConfiguraciongModel->municipios(0);
        $sucursal       = $this->ConfiguraciongModel->sucursal();
        $puestos        = $this->ConfiguraciongModel->puestos();
        $perfil         = $this->ConfiguraciongModel->perfil();
        $clientes       = $this->ConfiguraciongModel->clientes();        
    	require('views/configuraciong/configGeneral.php');
    }


    function reloadB(){

        session_start();
        $idempleado = $_SESSION["accelog_idempleado"];
        $apps = $this->ConfiguraciongModel->reloadB($idempleado);



        $html =  '<div class="panel panel-default">
                      <div class="panel-heading">Aplicaciones Favoritas</div>
                      <div class="panel-body">
                        <button class="btn btn-default" onclick="moreApps();">+ Apps</button>';

        foreach ($apps as $k => $v) {
            $url = $v['url'];
            $nombre = $v['nombre'];
            $idmenu = $v['idmenu'];            
            $html .=   '<button class="btn btn-default" onclick="openMenu(\''.$url.'\',\''.$nombre.'\',\''.$idmenu.'\');">'.$v['nombre'].'</button>';
        }

        $html .=       '</div> 
                </div>';

        $html .= '<div class="panel panel-default">
                        <div class="panel-heading">Información Relevante</div>
                        <div class="panel-body">
                          
                      </div>
                </div>';

        echo $html;
    }

    
    function municipios(){
    	$idestado = $_POST['idestado'];
    	$municipios 	= $this->ConfiguraciongModel->municipios($idestado);
    	echo json_encode($municipios);
    }
    function relaodPU(){
    	$relaodPU 	= $this->ConfiguraciongModel->relaodPU();

        //$html = '<button class="btn btn-primary" onclick="newPU();">Nuevo <i class="fa fa-plus" aria-hidden="true"></i></button>';

    	$html = '<table id="tablePU" class="table table-striped table-bordered" cellspacing="0">'.
                    '<thead>'.
                        '<tr>'.
                            '<th>ID</th>'.
                            '<th>Perfil</th>'.
                            '<th>Usuarios</th>'.
                            '<th></th>'.
                        '</tr>'.
                    '</thead>';

        foreach ($relaodPU as $key => $va) {
        	if($va['usuarios'] == 0){
        		$btn = '<button class="btn btn-danger" onclick="deletePU('.$va['idperfil'].');"><i class="fa fa-times" aria-hidden="true"></i></button>';
        	}else{
        		$btn = '';
        	}

        	$html .= '<tr>
                        <td>'.$va['idperfil'].'</td>
        				<td style="cursor:pointer" onclick="editPU('.$va['idperfil'].',\''.$va['nombre'].'\')"><a>'.$va['nombre'].'</a></td>
        				<td style="cursor:pointer" onclick="usuarios('.$va['idperfil'].')">'.$va['usuarios'].'</td>
        				<td>'.$btn.'</td>
        			</tr>';
        }

        echo $html;
        

    }

    function relaodPU2(){
               
        $acceperfil= $_SESSION['accelog_idperfil'];
        $pu   = $this->ConfiguraciongModel->relaodPU2($acceperfil);


        $html = ' <br><div class="col-sm-4">
                    <label class="control-label">Perfil</label>
                    <input id="perfil" class="form-control" type="text">
                    <br>
                    <div class="pull-right">
                        <button class="btn btn-default" onclick="savePU();">Aceptar <i class="fa fa-check" aria-hidden="true"></i></button>
                        <button class="btn btn-default" onclick="backP();">Cancelar <i class="fa fa-arrow-left" aria-hidden="true"></i></button>  
                    </div>
                                      
                </div>';

        $html .= '<br><div class="col-sm-6">';
        $html .= '<div class="col-sm-6" style="text-align: center;"><b>Menú</b></div><div class="col-sm-6" style="text-align: center;"><b>¿Permiso?</b></div><br>';
        $html .= '<div class="panel-group" id="accordion">';

        foreach ($pu as $key => $value) {
            $pu2 = $this->ConfiguraciongModel->getMenus($value['idcategoria'],0,1,$acceperfil); // categoria, menu padre, nivel, perfil
                $html .= '<div class="panel panel-default" id="panel'.$value['idcategoria'].'">
                            <div class="panel-heading">
                                 <h4 class="panel-title">
                                    <a data-toggle="collapse" data-target="#collapse'.$value['idcategoria'].'" href="#collapse'.$value['idcategoria'].' class="collapsed"">
                                      '.$value['categoria'].'
                                    </a>
                                </h4>
                            </div>
                        </div>'; 

                $html .= '<div id="collapse'.$value['idcategoria'].'" class="panel-collapse collapse">
                <div class="panel-body">';
                foreach ($pu2 as $k => $v) {
                    $html .= '<div style="padding-top:8px;" class="col-sm-10">&nbsp;&nbsp;&nbsp;&nbsp;'.$v['menu'].'</div>
                              <div style="padding-top:8px;" class="col-sm-2"><input type="checkbox" value="\''.$v['idmenu'].'\'"></div>';
                    
                    $submenu = $this->ConfiguraciongModel->getMenus($value['idcategoria'],$v['idmenu'],$intMult,$acceperfil);
                    foreach ($submenu as $ke => $va) {
                            $html .= '<div style="padding-top:8px;" class="col-sm-10">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$va['menu'].'</div>
                                      <div style="padding-top:8px;" class="col-sm-2"><input type="checkbox" value="\''.$va['idmenu'].'\'"></div>';

                        $ssubmenu = $this->ConfiguraciongModel->getMenus($value['idcategoria'],$va['idmenu'],$intMult,$acceperfil);
                        foreach ($ssubmenu as $kei => $val) {
                                $html .= '<div style="padding-top:8px;" class="col-sm-10">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$val['menu'].'</div>
                                          <div style="padding-top:8px;" class="col-sm-2"><input type="checkbox" value="\''.$val['idmenu'].'\'"></div>';
                        }
                    }
                }

                $html .= '</div>
                </div>';
        }

        $html .= '</div></div>';

        echo $html;


        /*
            echo    ' <div class="col-sm-4">
                        <label class="control-label">Perfil</label>
                        <input id="perfil" class="form-control" type="text">
                        <br>
                        <div class="pull-right">
                            <button class="btn btn-success" onclick="savePU();">Aceptar</button>
                            <button class="btn btn-danger" onclick="backP();">Cancelar</button>  
                        </div>
                                          
                    </div>';

            echo '<table class=" nmcatalogbusqueda ">
                            <tbody>
                            <tr>
                                <td class=" nmcatalogbusquedatit " align="center">Menu</td>
                                <td class=" nmcatalogbusquedatit " align="center">Acceso?</td>
                            </tr>';

            foreach ($pu as $key => $value) {
                echo "<tr><td colspan='2' class=' nmcatalogbusquedacont_2 '>" . $value['categoria'] . "</td></tr>";
                $pu2 = $this->ConfiguraciongModel->getMenus($value['idcategoria'],0,1,$acceperfil); // categoria, menu padre, nivel, perfil
                
                foreach ($pu2 as $k => $v) {
                    
                    $strProfiles.= $v['idmenu'] . ",";
                    echo "<tr><td class=' nmcatalogbusquedacont_1 '>";
                    echo '&nbsp;&nbsp;&nbsp;&nbsp;'.$v['menu'];
                    echo "</td>";
                    echo "<td class=' nmcatalogbusquedacont_1 '>";
                    //echo "<input type='button' id='btn" . $v['idmenu'] . "' onclick='toggleButton(" . $v['idmenu'] . ")' class=' btn_on_off ' style=' background-position-x: 2px; background-image: url(img/btn_off.png); '>";
                    echo '<input data-toggle="toggle" data-size="mini" type="checkbox" value="'.$v['idmenu'].'">';
                    echo "</td>";
                    echo "</tr>";
                    $submenu = $this->ConfiguraciongModel->getMenus($value['idcategoria'],$v['idmenu'],$intMult,$acceperfil);
                    foreach ($submenu as $ke => $va) {
                        echo "<tr><td class=' nmcatalogbusquedacont_1 '>";
                        echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$va['menu'].'-'.$intMult;
                        echo "</td>";
                        echo "<td class=' nmcatalogbusquedacont_1 '>";
                        //echo "<input type='button' id='btn" . $va['idmenu'] . "' onclick='toggleButton(" . $va['idmenu'] . ")' class=' btn_on_off ' style=' background-position-x: 2px; background-image: url(img/btn_off.png); '>";
                        echo '<input data-toggle="toggle" data-size="mini" type="checkbox">';
                        echo "</td>";
                        echo "</tr>";
                    }                            
                }                        
            }
            echo '</tbody>
                </table>'; 

        */             

    }

    function reloadEditPU(){
        $idPU = $_POST['idPU'];
        $nombre = $_POST['nombre'];
        $acceperfil= $_SESSION['accelog_idperfil'];
        $pu   = $this->ConfiguraciongModel->relaodPU2($acceperfil); // categorias
        $perf = $this->ConfiguraciongModel->perfiles($idPU);        

        $html = ' <br><div class="col-sm-4">
                    <label class="control-label">Perfil</label>
                    <input id="perfil" class="form-control" type="text" value="'.$nombre.'">
                    <br>
                    <div class="pull-right">
                        <button class="btn btn-default" onclick="editedPU('.$idPU.');">Guardar <i class="fa fa-check" aria-hidden="true"></i></button>
                        <button class="btn btn-default" onclick="backP();">Cancelar <i class="fa fa-arrow-left" aria-hidden="true"></i></button>  
                    </div>                                      
                </div>';

        $html .= '<br><div class="col-sm-6">';
        $html .= '<div class="col-sm-6" style="text-align: center;"><b>Menú</b></div><div class="col-sm-6" style="text-align: center;"><b>¿Permiso?</b></div><br>';
        $html .= '<div class="panel-group" id="accordion">';

        $html .= '<div class="panel-group" id="accordion">';
        foreach ($pu as $key => $value) {
            $pu2 = $this->ConfiguraciongModel->getMenus($value['idcategoria'],0,1,$acceperfil); // categoria, menu padre, nivel, perfil
                $html .= '<div class="panel panel-default" id="panel'.$value['idcategoria'].'">
                            <div class="panel-heading">
                                 <h4 class="panel-title">
                                    <a data-toggle="collapse" data-target="#collapse'.$value['idcategoria'].'" href="#collapse'.$value['idcategoria'].' class="collapsed"">
                                      '.$value['categoria'].'
                                    </a>
                                </h4>
                            </div>
                        </div>'; 

                $html .= '<div id="collapse'.$value['idcategoria'].'" class="panel-collapse collapse">
                <div class="panel-body">';
                foreach ($pu2 as $k => $v) {

                    foreach ($perf as $ke => $va) {
                        if($v['idmenu'] == $va['idmenu']){
                           $check = 'checked'; 
                           break;
                       }else{
                            $check = '';
                       }
                    }

                    $html .= '<div style="padding-top:8px;" class="col-sm-10">&nbsp;&nbsp;'.$v['menu'].'</div>
                              <div style="padding-top:8px;" class="col-sm-2"><input type="checkbox" value="\''.$v['idmenu'].'\'" '.$check.'></div>';

                    
                    $submenu = $this->ConfiguraciongModel->getMenus($value['idcategoria'],$v['idmenu'],$intMult,$acceperfil);
                    foreach ($submenu as $ke => $va) {

                        foreach ($perf as $kee => $vaa) {
                            if($va['idmenu'] == $vaa['idmenu']){
                               $check = 'checked'; 
                               break;
                           }else{
                                $check = '';
                           }
                        }
                            $html .= '<div style="padding-top:8px;" class="col-sm-10">&nbsp;&nbsp;&nbsp;&nbsp;'.$va['menu'].'</div>
                                      <div style="padding-top:8px;" class="col-sm-2"><input type="checkbox" value="\''.$va['idmenu'].'\'" '.$check.'></div>';
                    }
                }

                 $html .= '</div>
                </div>';
        }

        $html .= '</div></div>';

        echo $html;
        /////
        
        /*
            echo    ' <div class="col-sm-4">
                        <label class="control-label">Perfil</label>
                        <input id="perfil" class="form-control" type="text" value="'.$nombre.'">
                        <br>
                        <div class="pull-right">
                            <button class="btn btn-success" onclick="editedPU('.$idPU.');">Guardar</button>
                            <button class="btn btn-danger" onclick="backP();">Cancelar</button>  
                        </div>
                                          
                    </div>';

            echo '<table class=" nmcatalogbusqueda ">
                            <tbody>
                            <tr>
                                <td class=" nmcatalogbusquedatit " align="center">Menu</td>
                                <td class=" nmcatalogbusquedatit " align="center">Acceso?</td>
                            </tr>';

                
            foreach ($pu as $key => $value) {

                echo "<tr><td colspan='2' class=' nmcatalogbusquedacont_2 '>" . $value['categoria'] . "</td></tr>";
                $pu2 = $this->ConfiguraciongModel->getMenus($value['idcategoria'],0,1,$acceperfil); // categoria, menu padre, nivel, perfil
                foreach ($pu2 as $k => $v) {
                    foreach ($perf as $ke => $va) {
                        if($v['idmenu'] == $va['idmenu']){
                           $check = 'checked'; 
                           break;
                       }else{
                            $check = '';
                       }
                    }
                    
                    
                    $strProfiles.= $v['idmenu'] . ",";
                    echo "<tr><td class=' nmcatalogbusquedacont_1 '>";
                    echo '&nbsp;&nbsp;&nbsp;&nbsp;'.$v['menu'];
                    echo "</td>";
                    echo "<td class=' nmcatalogbusquedacont_1 '>";
                    //echo "<input type='button' id='btn" . $v['idmenu'] . "' onclick='toggleButton(" . $v['idmenu'] . ")' class=' btn_on_off ' style=' background-position-x: 2px; background-image: url(img/btn_off.png); '>";
                    echo '<input data-toggle="toggle" data-size="mini" type="checkbox" value="'.$v['idmenu'].'" '.$check.'>';
                    echo "</td>";
                    echo "</tr>";
                    $submenu = $this->ConfiguraciongModel->getMenus($value['idcategoria'],$v['idmenu'],$intMult,$acceperfil);
                    foreach ($submenu as $ke => $va) {
                        
                        foreach ($perf as $kee => $vaa) {
                            if($v['idmenu'] == $vaa['idmenu']){
                               $check = 'checked'; 
                               break;
                           }else{
                                $check = '';
                           }
                        }

                        echo "<tr><td class=' nmcatalogbusquedacont_1 '>";
                        echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$va['menu'];
                        echo "</td>";
                        echo "<td class=' nmcatalogbusquedacont_1 '>";
                        //echo "<input type='button' id='btn" . $va['idmenu'] . "' onclick='toggleButton(" . $va['idmenu'] . ")' class=' btn_on_off ' style=' background-position-x: 2px; background-image: url(img/btn_off.png); '>";
                        echo '<input data-toggle="toggle" data-size="mini" type="checkbox" value="'.$v['idmenu'].'" '.$check.'>';
                        echo "</td>";
                        echo "</tr>";
                    }                            
                }                        
            }
            echo '</tbody>
                </table>';   

        */
    }

    function newuser2(){
        $idadmin = $_POST['idadmin'];
        if(isset($idadmin)){
            $datos = $this->ConfiguraciongModel->editAU($idadmin);
            $id = $datos[0]['idadmin'];
            $nom = $datos[0]['nombre'];
            $ape = $datos[0]['apellidos'];
            $user = $datos[0]['nombreusuario'];
            $email = $datos[0]['correoelectronico'];
            $idempleado = $datos[0]['idempleado'];
            //$pass1 = $datos[0]['clave'];
            //$pass2 = $datos[0]['confirmaclave'];
            $idperfil = $datos[0]['idperfil'];
            $idpuesto = $datos[0]['idpuesto'];
            $idSuc = $datos[0]['idSuc'];
            $idcli = $datos[0]['id'];            
            $foto = $datos[0]['foto'];
            $auxpass = 0;
            $pass='************';
            $onclick = 'onfocus="editpass()";';
            $read = 'readonly';

            $auxE = 1;
        }else{
            $id = '(Autonúmerico)';
            $nom = $ape = $user = $pass1 = $pass2 = $foto ='';
            $email = '@';
            $auxE = 0;
            $auxpass = 1;
            $pass = '';
            $onclick = '';
            $read = '';
        }
        $perfil         = $this->ConfiguraciongModel->perfil();
        $puestos        = $this->ConfiguraciongModel->puestos();
        $sucursal       = $this->ConfiguraciongModel->sucursal();
        $clientes       = $this->ConfiguraciongModel->clientes();

        $html = '<div class="col-sm-12">
                    <div class="col-sm-3">
                        <label> ID:<font style="color:#FF0000; font-weight:bold;"></font></label>
                        <input id="idadmin" class=" form-control" type="text" value="'.$id.'" style="box-shadow:none;border:none;text-align:center;color:silver;" size="15" disabled="" last_class=" nminputtext nminputtextdisabled ">                                                  
                    </div>
                    <div class="col-sm-3">
                        <label><font>*</font>Nombre:</label>
                        <input id="nombre" class=" form-control " type="text" placeholder="Nombre" maxlength="100" size="100" value="'.$nom.'">
                    </div> 
                    <div class="col-sm-3">
                        <label><font style="font-weight:bold;">*</font>Apellidos:</label>
                        <input id="apellidos" class=" form-control " type="text" placeholder="Apellidos" maxlength="100" size="100" value="'.$ape.'">
                    </div> 
                    <div class="col-sm-3">
                        <label><font style="font-weight:bold;">*</font>Nombre de Usuario:</label>
                        <input id="auxE" type="hidden" value="'.$auxE.'">
                        <input id="idempleado" type="hidden" value="'.$idempleado.'">
                        <input id="nombreUser" class=" form-control " type="text" placeholder="Nombre de Usuario" maxlength="100" size="100" value="'.$user.'">                            
                    </div>

                    <div class="col-md-3 nmfieldcell">
                        <label>Correo Electronico:</label>
                        <input id="email" class=" form-control " type="text" placeholder="email" maxlength="100" size="100" value="'.$email.'">
                    </div>
                    <div class="col-md-3">
                        <label><font style="font-weight:bold;">*</font>Contraseña:</label>
                        <input id="pass" class=" form-control" '.$onclick.' type="password" placeholder="Contraseña" maxlength="100" size="100" value="'.$pass.'" '.$read.'>    
                    </div> 
                    <div class="col-md-3">
                        <label><font style="font-weight:bold;">*</font>Confirma tu Contraseña:</label>
                        <input id="pass2" class=" form-control " type="password" placeholder="Confirma tu Contraseña" maxlength="100" size="100" value="'.$pass.'" '.$read.'>    
                        <input type="hidden" id="auxpass" value="'.$auxpass.'">
                    </div> 
                    <div class="col-md-3">
                        <label>Perfil Aplicaciones:</label>
                        <select id="perfil" class="form-control">
                            <!--<option value="0">Selecciona el Perfil</option>-->';
                                    foreach ($perfil as $k => $v) {
                                        if($v['idperfil'] == $idperfil){
                                            $html .= '<option value="'.$v['idperfil'].'" selected>'.$v['nombre'].'</option>';                                                                                             
                                        }else{
                                            $html .= '<option value="'.$v['idperfil'].'">'.$v['nombre'].'</option>';                                                                                             
                                        }                                        
                                    }
                                                                                    
        $html .= '     </select>
                    </div>

                    <div class="col-md-2">
                        <label>Puesto:</label>
                        <select id="puesto" class="form-control">
                            <!--<option value="0">Selecciona el Puesto</option>-->';                                 
                                foreach ($puestos as $k => $v) {
                                    if($v['idpuesto'] == $idpuesto){
                                        $html .= '<option value="'.$v['idpuesto'].'" selected>'.$v['puesto'].'</option>';                                                                                              
                                    }else{
                                        $html .= '<option value="'.$v['idpuesto'].'">'.$v['puesto'].'</option>';                                                                                             
                                    }
                                    
                                }                                                       
        $html .=  '     </select>
                    </div>
                    <div class="col-md-1">
                    <label style="color:white;">......</label>
                       <button class="btn btn-default" onclick="puestos();">...</button> 
                    </div>
                    <div class="col-md-3">
                        <label>Sucursal:</label>
                        <select id="suc" class="form-control">
                            <!--<option value="0">Selecciona el Puesto</option>-->';                                 
                                foreach ($sucursal as $k => $v) {
                                    if($v['idSuc'] == $idSuc){
                                        $html .= '<option value="'.$v['idSuc'].'" selected>'.$v['nombre'].'</option>';                                                                                             
                                    }else{
                                       $html .= '<option value="'.$v['idSuc'].'">'.$v['nombre'].'</option>';                                                                                              
                                   }                                    
                                }                                                      
        $html .=  '     </select>
                    </div> 
                    <div class="col-md-3">
                        <label>Cliente:</label>
                        <select id="cliente" class="form-control">
                            <!--<option value="0">Selecciona el Puesto</option>-->';                                 
                                foreach ($clientes as $k => $v) {
                                    if($v['id'] == $idcli){
                                        $html .= '<option value="'.$v['id'].'" selected>'.$v['nombre'].'</option>';                                                                                             
                                    }else{
                                        $html .= '<option value="'.$v['id'].'">'.$v['nombre'].'</option>';                                                                                             
                                    } 
                                }                                                     
        $html .=  '     </select>
                    </div>
                    <div class=" col-md-3">  
                        <label id="lbFoto" class="control-label">Fotografia Perfil: '.$foto.'</label> <br>                                                                                                      
                        <a id="btndescAU" class="btn btn-default btn-xs" target="blank" title="Descargar archivo">
                            <i class="fa fa-arrow-circle-down"></i>
                        </a>                                            
                        <a class="btn btn-default btn-xs" target="blank" title="Ver archivo">
                            <i class="fa fa-file-o"></i>
                        </a>                                        
                        <input type="file" id="fotoPerfil" size="100" name="Filedata" style="display: block;">
                        <input type="hidden" id="fotoPerfil2" value="'.$foto.'">     
                    </div>   
        </div>';

        $html .= '   <div style="text-align:center;">
                        <button class="btn btn-default" onclick="saveAU();">Guardar <i class="fa fa-check" aria-hidden="true"></i></button>
                        <button class="btn btn-default" onclick="relaodAU();">Cancelar <i class="fa fa-arrow-left" aria-hidden="true"></i></button>  
                    </div>
                                      
                </div>';

        echo $html;
    }
    function relaodAU(){
    	$relaodAU 	= $this->ConfiguraciongModel->relaodAU();

    	$html = '  <table  id="tableAU" class="table table-striped table-bordered">'.
                            '<thead>'.
                            '<tr>'.
	                            '<th>ID</th>'.
	                            '<th>Nombre</th>'.	                            
	                            '<th>Nombre de Usuario</th>'.
	                            '<th>Correo Electronico</th>'.
	                            //'<th>Fotografia Perfil</th>'.
	                            '<th>Perfil Aplicaciones</th>'.
	                            //'<th>Id organizacion</th>'.
	                            //'<th>Puesto</th>'.
	                            '<th>Sucursal</th>'.
	                            //'<th>Cliente</th>'.
                                '<th>Acciones</th>'.
                          '</tr>'.
                        '</thead>';

        
       
        foreach ($relaodAU as $key => $va) {
        	

        	$html .= '<tr>        				
                        <td>'.$va['idadmin'].'</a></td>
        				<td><a onclick="newUser2('.$va['idadmin'].')">'.$va['nombre'].' '.$va['apellidos'].'</a> </td>        				
        				<td>'.$va['nombreusuario'].'</td>
        				<td>'.$va['correoelectronico'].'</td>
        				<td>'.$va['perfil'].'</td>        				        				        				
        				<td>'.$va['sucursal'].'</td>        				
                        <td><button class="btn btn-danger" onclick="deleteAU('.$va['idadmin'].','.$va['idempleado'].');"><i class="fa fa-times" aria-hidden="true"></i></button></td>        				        				
        			</tr>';
        }
        
        

        echo $html;
    }

    /*
    function reloadBien(){
    	
    	//require('views/configuracion/configGeneral.php');
    	require("views/configuraciong/informacionrelevante.php");
    }
    */

    function savePU(){
        $perfil = $_POST['perfil'];
        $strmenus = $_POST['strmenus'];

        $result = $this->ConfiguraciongModel->savePU($perfil,$strmenus);
        echo json_encode($result);

    }
    function editedPU(){
        $idperfil = $_POST['idperfil'];
        $perfil = $_POST['perfil'];
        $strmenus = $_POST['strmenus'];

        $result = $this->ConfiguraciongModel->editedPU($idperfil,$perfil,$strmenus);
        echo json_encode($result);
    }
    function saveMiOrg(){

    	$result = $this->ConfiguraciongModel->saveMiOrg($_POST);
    	echo json_encode($result);
    }

    function deletePU(){
    	$idperfil  = $_POST['idperfil'];
    	$result = $this->ConfiguraciongModel->deletePU($idperfil);
    }
 
    function saveAU(){
        $auxE = $_POST['auxE'];
        $idadmin = $_POST['idadmin'];
        if($auxE == 1){
            $result = $this->ConfiguraciongModel->editedAU($_POST,$idadmin);
        }
        if($auxE == 0){
            $result = $this->ConfiguraciongModel->saveAU($_POST);
        }

        echo json_encode($result); 
    }
    function editAU(){
        $id = $_POST['id'];
        $result = $this->ConfiguraciongModel->editAU($id);
        echo json_encode($result); 
    }

    function saveAF(){
        session_start();
        $idempleado = $_SESSION["accelog_idempleado"];
        $apps = $_POST['apps'];

       $result = $this->ConfiguraciongModel->saveAF($idempleado,$apps);
       echo json_encode($result);
    }

    function moreApps(){
        session_start();
        $idempleado = $_SESSION["accelog_idempleado"]; //$catalog_id_utilizado
        $apps = $this->ConfiguraciongModel->apps($idempleado);
        $html = '<form role="form">
                      <div class="form-group">';
        $html =         "<div class='list-group'>
                                        <li class='list-group-item active'>
                                          Agregar / Eliminar
                                        </li>
                                        <li class='list-group-item'>";                          
                           foreach ($apps as $key => $val) {
                               if($val['sel'] == -1){
                                    $checked="";
                               }else{
                                    $checked=" checked='checked' ";
                                }
                                $html.="<spam class='list-group-item'><input type='checkbox' name=chk[] value=".$val["idmenu"]." $checked>  ".$val["nombre"]."</spam>";
                           }

                          $html.="</li></div>";
                       
                     
                 $html .= "</div> </form>";    

        echo $html; 
                   
        
    }

    function user(){
        $user = $_POST['nombreUser'];
        $result = $this->ConfiguraciongModel->user();
        $userV = 0; 
        foreach ($result as $k => $v) {
            if($user == $v['nombreusuario']){
               $userV = 1;  
               break;
               
            }
            
        }
        echo $userV;
    }

    function usuarios(){
        $perfil = $_POST['perfil'];
        $usuarios = $this->ConfiguraciongModel->usuarios($perfil);

        $html = '<div class="col-sm-12">
                        <div class="col-sm-6"><b>Nombre</b></div>
                        <div class="col-sm-6"><b>Sucursal</b></div>';

        foreach ($usuarios as $k => $v) {
            $html .= '<div class="col-sm-6">'.$v['usuario'].'</div>
                      <div class="col-sm-6">'.$v['sucursal'].'</div>';
        }
        $html .= '</div>';
        echo $html;
    }

    function deleteAU(){
        $idadmin = $_POST['idadmin'];
        $idempleado = $_POST['idempleado'];
        $deleteAU = $this->ConfiguraciongModel->deleteAU($idadmin,$idempleado);
        echo json_encode($deleteAU);
    }
    
	// === CH@ FIN

    function nuevoPais(){
        $nombre = (filter_var($_POST['nombre'], FILTER_SANITIZE_STRING));
        echo $this->ConfiguraciongModel->nuevoPais($nombre);
    }
    function nuevoEstado(){
        $idpais = (filter_var($_POST['idpais'], FILTER_SANITIZE_STRING));
        $estado = (filter_var($_POST['estado'], FILTER_SANITIZE_STRING));
        echo $this->ConfiguraciongModel->nuevoEstado($idpais,$estado);
    }
    function reloadEstado(){        
        $idpais = $_POST['idpais'];
        $result = $this->ConfiguraciongModel->reloadEstado($idpais);
        echo json_encode($result);
    }
    function nuevoMunicipio(){
        $idestado = (filter_var($_POST['idestado'], FILTER_SANITIZE_STRING));
        $municipio = (filter_var($_POST['municipio'], FILTER_SANITIZE_STRING));
        echo $this->ConfiguraciongModel->nuevoMunicipio($idestado,$municipio);

    }
    function nuevoPuesto(){
        $puesto = (filter_var($_POST['puesto'], FILTER_SANITIZE_STRING));
        echo $this->ConfiguraciongModel->nuevoPuesto($puesto);

    }

}


?>
