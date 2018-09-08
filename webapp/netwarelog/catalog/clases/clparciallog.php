<?php



class clparciallog {
    
    var $parciallog_info = array();
    var $parciallog_listacampos = array();
    var $ncampos = 0;
    
    function __construct($estructura,$perfil,$conexion) {   
        $ncampos=0;
        /*PERMISOS -- PARCIALLOG
	*   Obtiene los permisos registrados en el ParcialLog y los almacena en el
	*   arreglo parciallog_info ...
	*/
        $sql_parciallog_titulo = " 
            select idparciallog, idperfil 
            from parciallog_titulo 
            where estructura = '".$estructura."' 
                  and idperfil = '".$perfil."'
	";
        //echo $sql_parciallog_titulo;
        
	$resultplt = $conexion->consultar($sql_parciallog_titulo);

        if($regplt = $conexion->siguiente($resultplt)){
            $sql_parciallog_detalle = " 
                select campo,caracteristica 
		from parciallog_detalle 
		where idparciallog = ".$regplt{'idparciallog'}."
            ";
            //echo $sql_parciallog_detalle;
	    
            $resultpld = $conexion->consultar($sql_parciallog_detalle);
            while($regpld = $conexion->siguiente($resultpld)){		
                $this->parciallog_info[$regpld{'campo'}] = strtoupper(substr($regpld{'caracteristica'},0,1));
                $ncampos++;
                $this->parciallog_listacampos[$ncampos-1]=$regpld{'campo'};
                //echo $estructura." ".$regpld{'campo'}." ".$this->parciallog_info[$regpld{'campo'}]."<br>";
            }
            $conexion->cerrar_consulta($resultpld);

	} else {
            
            //echo "<br><b>no aplica para este perfil</b><br>";
	}
	$conexion->cerrar_consulta($resultplt);
        //echo "Error";
	///////
    }
    
    public function get_permiso($nombrecampo){ 
        //echo "<br> entre ".$nombrecampo.": "; // - ".$this->parciallog_info[$nombrecampo]."- ";
        if(isset($this->parciallog_info[$nombrecampo])){
            return $this->parciallog_info[$nombrecampo];
        } else {
            return "M";
        }
    }
    
    public function get_where_excluircampos(){   
      $sqlw = "";
      foreach($this->parciallog_listacampos as $nombrecampo){
          if($this->get_permiso($nombrecampo)=="O"){
              $sqlw.= " and nombrecampo <> '".$nombrecampo."' ";
          }          
      }         
      return $sqlw;
    }
    
}

?>
