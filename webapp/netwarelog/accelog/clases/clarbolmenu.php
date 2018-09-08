<?php 
/*     
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
if($bd!="nmdev")
{
//Hacemos una segunda conexion para conectarse a netwarestore
//busca los menus que se pueden bloquear
    //extraemos el id del cliente
    $queryCliente=mysql_query("SELECT distinct customer.idclient from appclient inner join customer on appclient.idclient=customer.idclient where customer.nombre_db='$bd'", $conexion2);
    $idclient=mysql_result($queryCliente, 0);


    $totalaplicaciones="";
    $vencidas="";
    //total de aplicaciones del cliente
    $Contar =mysql_query("SELECT COUNT(*) FROM appclient where appclient.idclient=$idclient",$conexion2);
    $totalaplicaciones=mysql_result($Contar, 0);

    //Aplicaciones que tiene el cliente vencidas
    $qry1 = mysql_query("SELECT COUNT(*) FROM appclient where idstatus<>1 and idclient=$idclient", $conexion2);
    $vencidas=mysql_result($qry1, 0);

    //#####QUITAR PARA ACTIVAR LICENCIAMIENTO!!!#####
    $vencidas=0;
    //#####QUITAR PARA ACTIVAR LICENCIAMIENTO!!!#####

    //licenciamiento
    if ($vencidas!=0)
    {
        if($vencidas >= 1)
        {  
            if($vencidas!=$totalaplicaciones)
            {
                 $query="select distinct(idmenu) from appmenu where idapp in (";
                    $qry4=mysql_query("SELECT appclient.idapp from appclient  where appclient.idstatus<>1 and appclient.idclient=$idclient", $conexion2);
                    while ($row = mysql_fetch_array($qry4, MYSQL_NUM)) 
                    {
                        $idapp=$row[0];
                       $Array2[]= $idapp;

                    }

                    $apps2=implode(",", $Array2);

                    $query.="$apps2";
                   
                    $query.=")and idmenu not in (select idmenu from appmenu where idapp in (";
                    $qry5=mysql_query("SELECT appclient.idapp from appclient  where appclient.idstatus=1 and appclient.idclient=$idclient",$conexion2);
                    while ($row = mysql_fetch_array($qry5, MYSQL_NUM)) 
                    {
                        $idapp=$row[0];
                        $Array[]= $idapp;


                    }

                    $apps=implode(",", $Array);

                     $query.="$apps))";


            }else
                {
                     $query ="select idmenu from appmenu where idapp in (";

                $buscaapps=mysql_query("SELECT appclient.idapp from appclient  where appclient.idstatus<>1 and appclient.idclient=$idclient", $conexion2);
                while ($row = mysql_fetch_array($buscaapps, MYSQL_NUM)) 
                {
                    $idapp=$row[0];
                    $Array[]= $idapp;

                }
                $app=implode(",", $Array);
                $query.="$app)";

                   
                }  

        }

    else
    {
        $query="SELECT idmenu from appmenu inner join appclient on appmenu.idapp=appclient.idapp where idstatus=-1";
    }
    $ejecutaquery=mysql_query($query, $conexion2);

    $arrMenusBloq = array();

    while ($row2 = mysql_fetch_array($ejecutaquery, MYSQL_NUM))
    {
        array_push($arrMenusBloq,$row2[0]);
    }
    unset($row2);
    mysql_free_result($ejecutaquery);
    unset($ejecutaquery);

}
}

class arbolmenu{

    var $idcategoria;
    var $conexion;
    var $menus = array();
    var $aMenusOmision = array();
    
    var $all_menus = array();
   
    
   	var $menustofind = ""; 
   	var $menustofind_item = "";
   	var $menustofind_cat = "";

    function arbolmenu($idcategoria_enviada, $conexion_enviada,$menus){
        $this->idcategoria=$idcategoria_enviada;
        $this->conexion=$conexion_enviada;
        $this->menus= $menus;
        
       
        // Begin: loading all menus
        $sql = " select * from accelog_menu order by orden ";
        $result = $this->conexion->consultar($sql);       
        $m = 0;
        while($rs = $this->conexion->siguiente($result)){
        	if($this->buscar_menu_permiso($rs{"idmenu"})){        
        		
        		$m+=1;
        		$this->all_menus[$m]["idmenu"] = $rs{"idmenu"};
        		$this->all_menus[$m]["nombre"] = $rs{"nombre"};
        		$this->all_menus[$m]["url"] = $rs{"url"};
        		$this->all_menus[$m]["idmenupadre"] = $rs{"idmenupadre"};
        		$this->all_menus[$m]["idcategoria"] = $rs{"idcategoria"};
        		$this->all_menus[$m]["omision"] = $rs{"omision"};
        		
        		$filename="";
        		$icono="";
                if($rs{"icono"}){
                    $filename="../utilerias/img_mnu/".$rs{"idmenu"}.".png";
                    if(!file_exists($filename)){
                        $filename="../utilerias/img_mnu/x.png";
                    }
                    $icono = "<img src=\"".$filename."\">";
                }        		
        		$this->all_menus[$m]["icono"] = $icono;
        	}
        }
        $this->conexion->cerrar_consulta($result);
        // End: loading all menus
        
        
    }

    function buscar_menu_permiso($idmenu){
        $encontrado = false;
        foreach ($this->menus as $key => $val) {
            if($idmenu==$val){
                $encontrado = true;
                return $encontrado;
            }
        }
        return  $encontrado;
    }

    function agregaomision($idmenu){
        $this->aMenusOmision[]=$idmenu;
        //echo "entre ".$idmenu;
    }

    function setMenuOmision($aMenusOmision){
        $this->aMenusOmision = $aMenusOmision;
    }

    function regresamenusomision(){
        return $this->aMenusOmision;
    }



    //Esta función regresa verdadero si hay hijos...
    function construyemenus($idmenupadre, $sangria){

        global $arrMenusBloq;
        global $idclient;

        $sql = " select * from accelog_menu where idmenupadre=".$idmenupadre." and idcategoria=".$this->idcategoria;
        $sql .= " order by orden ";
        $result = $this->conexion->consultar($sql);

        if($sangria==-5){
            $sangria=0;
        }
        $sangria = $sangria+8;

        $arboldemenus="";
        $arboldemenushijo="";
        while($rs = $this->conexion->siguiente($result)){


            if($this->buscar_menu_permiso($rs{"idmenu"})){

                //Menu por omisión
                if($rs{"omision"}==-1){
                    $this->agregaomision($rs{"idmenu"});
                }

                $arboldemenushijo=$this->construyemenus($rs{"idmenu"}, $sangria);


                $nombreimagen = "imgmnu".$rs{"idmenu"};
                //$icono = "&nbsp;";
                $icono = "";
                $filename="";
                if($rs{"icono"}){
                    $filename="../utilerias/img_mnu/".$rs{"idmenu"}.".png";
                    if(!file_exists($filename)){
                        $filename="../utilerias/img_mnu/x.png";
                    }
                    $icono = "<img src='".$filename."'>";
                }

                $imagen = "mas_espacio.png";
                $cursor="";
                $alclic = "";
                if($arboldemenushijo!=""){
                    $imagen = "menos.png";
                    $cursor = " style='cursor:pointer;' ";
                    $nombrehijo = "divmnu".$rs{"idmenu"};
                    $arboldemenushijo="<div id='".$nombrehijo."'   >".$arboldemenushijo."</div>";
                    $alclic = "hijos('#".$nombrehijo."','#".$nombreimagen."')";
                    $estilomenu = "nmmenuconhijos";
                } else {
                    /*
                    if($_SESSION['bd']!="nmdev")
                    {
                        $menu=$rs{"idmenu"};
                        if($rs{"idcategoria"}!=1023){
                            if(array_search($rs{"idmenu"}, $arrMenusBloq)===false){
                            $url=$rs{"url"};
                            }else{
                                $url="../../netwarelog/accelog/bloqueaApps.php?menu=$menu&idclient=$idclient";
                            }
                            }else{
                            $url=$rs{"url"};
                        }
                    }
                    else
                    {
                        $url=$rs{"url"};
                    }
                    */
                    $url=$rs{"url"};
                    $alclic = "agregatab('".$url."','".$rs{"nombre"}."','".$filename."',".$rs{"idmenu"}.")";
                    $estilomenu = "nmmenusinhijos";
                }



                $arboldemenus.="<table class=' nmmenu " . $estilomenu . "' width='100%'  height='0%'  onclick=\"".$alclic."\"   >";
                $arboldemenus.="<tr>";
                $arboldemenus.="<td style='width:".$sangria."px; ;'></td>";

                //IMAGEN FLECHA EN CASO DE HIJOS
                $arboldemenus.="<td style='width:14px;'><img style='width:18px;' id='".$nombreimagen."' src=\"../design/default/".$imagen."\"  ".$cursor." ></td>";

                //IMAGEN ELEGIDA PARA EL MENU
                //$arboldemenus.="<td width='16' style='background-color:transparent'>".$icono."</td>"; //Seccion de Imagen
                $arboldemenus.="<td style='width:5px;'>".$icono."</td>"; //Seccion de Imagen

                //NOMBRE DEL MENU
                $arboldemenus.="<td>".$rs{"nombre"}."</td>";

                $arboldemenus.="</tr>";
                $arboldemenus.="</table>";
                $arboldemenus.=$arboldemenushijo;

            } //if(buscar_menu_permiso($rs{"idmenu"}))


        }

        $this->conexion->cerrar_consulta($result);

        return $arboldemenus;
    }
    
    
    
    //Esta función regresa verdadero si hay hijos...
    function construyemenus_bootstrap($idmenupadre, $sangria){

        $arboldemenus="";
        $arboldemenushijo="";
        
        foreach ($this->all_menus as $menu) {

			if($menu["idmenupadre"]==$idmenupadre and $menu["idcategoria"]==$this->idcategoria){

                //Menu por omisión
                if($menu["omision"]==-1){
					$this->agregaomision($menu["idmenu"]);
                }

                $arboldemenushijo=$this->construyemenus_bootstrap($menu["idmenu"], $sangria." > ".$menu["nombre"]);
                $nombreimagen = "imgmnu".$menu["idmenu"];
                $icono = $menu["icono"];

                $imagen = "mas_espacio.png";
                $cursor="";
                $alclic = "";
                if($arboldemenushijo!=""){
                    $imagen = "menos.png";
                    $cursor = " style='cursor:pointer;' ";
                    $nombrehijo = "divmnu".$menu["idmenu"];
                    //$arboldemenushijo="<div id='".$nombrehijo."'   >".$arboldemenushijo."</div>";
                    $alclic = "hijos('#".$nombrehijo."','#".$nombreimagen."')";
                    $estilomenu = "nmmenuconhijos";
                    $arboldemenus.="<li class='dropdown dropdown-submenu nmcatsubmenu'>
                                        <a  class='dropdown-toggle href_nmcatsubmenu' 
                                            data-toggle='dropdown' 
                                            tabindex='-1' href='#'>".$menu["nombre"]."</a>";
                    $arboldemenus.="<ul class='dropdown-menu'>";
                    $arboldemenus.=$arboldemenushijo;
                    $arboldemenus.="</ul>";
                    $arboldemenus.="</li>";
                    
                    //$this->menustofind_item.=$menu["nombre"]." > ";
                    //$sangria.=" > ".$menu["nombre"];
                    //error_log($sangria);
                	
                } else {
                	
                	$this->menustofind.="<option value='mnu_".$menu["idmenu"]."'>";
                	//$this->menustofind.=$this->menustofind_cat." > ";
                	$this->menustofind.=$sangria;
                	$this->menustofind.=" > ".$this->menustofind_item;
                	$this->menustofind.=$menu["nombre"]."</option>";
                	
                	
                	//$this->menustofind_item="";
                	
                    $url=$menu["url"];
                    //$alclic = "agregatab(\"".$url."\",\"".$menu["nombre"]."\",\"".$menu["icono"]."\",".$menu["idmenu"].")";
                    $alclic = "agregatab(\"".$url."\",\"".$menu["nombre"]."\",\"\",".$menu["idmenu"].")";
                    $estilomenu = "nmmenusinhijos";
                    $arboldemenus.="<li><a id='mnu_".$menu["idmenu"]."' href='javascript:".$alclic."'>".$menu["nombre"]."</a></li>";
                }

            } //if($menu["idmenupadre"]==$idmenupadre and $menu["idcategoria"]==$idcategoria)

        }

        return $arboldemenus;
    }
    
}



?>
