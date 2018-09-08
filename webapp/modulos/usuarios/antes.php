<style>
    .checkbox
    {
        display: none;
        font-size: 12px;
        margin-left: 14%;
    }
    .radio
    {
      font-size: 12px;  
    }
</style>
<?php
    //Recupera Variables
    $idempleado = $_SESSION["accelog_idempleado"];
    //$catalog_id_utilizado
    
    //Verifica si este usuario puede seleccionar perfiles
     $sQuery = "SELECT administrador FROM empleados WHERE idempleado=$idempleado";
                $result = $conexion->consultar($sQuery);
                while($rs = $conexion->siguiente($result)){
                    $cperfil=$rs{"administrador"};
		}
		$conexion->cerrar_consulta($result);

//*************** P e r f i l e s   d e  u s u a r i o *******************************//
$perfiles=array();
$qry = mysql_query("SELECT * from accelog_perfiles where visible<>0 and idperfil<>2");
while ($fila = mysql_fetch_array($qry, MYSQL_NUM)) 
 {
    $nombre=$fila[1];
    $idperfil=$fila[0];
    $checkbox="<div class='checkbox'><input type='checkbox' name='perfiles[]' value='$idperfil'>$nombre</input></br></div>";
    $perfiles[]=$checkbox;
}

?>
        <script>

						var datoespecial="nolacambies_mlog_1-oct-2013";


             $(document).ready(function(){
								//$("#i195").removeAttr("disabled");
								//$("#lbl195").hide();
								//$("#i195").hide();
								
								$("#i199").val(datoespecial); //Contraseña
								$("#i200").val(datoespecial); //Confirma tu contraseña
							
								$("#i198").css("font-weight","bold");	
								$("#i198").css("color","red");	
								$("#i201").css("font-weight","bold");	
								$("#i201").css("color","red");	
								$("#i201").attr("title","Registre el correo electrónico de su usuario, este dato es muy importante posteriormente para recuperar el acceso al sistema.");	
                                $("#i203_div").hide();

                //recupera arreglo de los perfiles
                var arrayJS=<?php echo json_encode($perfiles);?>;
                
                $admin="<div class='radio'><input type='radio' name='perfil' value='2' onclick='ocultarPerfiles()'>Administrador<br><input type='radio' name='perfil' value='1' onclick='mostrarPerfiles()' >Otros:<br></div>";
                $($admin).insertAfter('[title="Perfil Aplicaciones"]');

                for(var i=0;i<arrayJS.length;i++) 
                { 
                    $varJS = arrayJS[i];
                    $($varJS).insertBefore('[title="Puesto"]');
               
                }

                if('<?php echo "$cperfil";?>'==0){
                    //$("#i203").attr("disabled","disabled");
                }
                $("#i200").change(function(){
                    if($("#i200").val()!==$("#i199").val()){
                        alert('Las contraseñas no coinciden. ¿Quieres volver a intentarlo?');
                        $("#i200").focus();
                    };
                });
                $("#send").click(function(){
                    if('<?php echo "$cperfil";?>'==0){
                        //$("#i203").removeAttr('disabled');
                    }
                });


								document.getElementById("frm").onsubmit = function(){


										$("#i195").removeAttr("disabled");


										if($("#i199").val()==datoespecial){
										
											return valida();
	
										} else{
 
											if(caracter($("#i199").val())){
												if($("#199").val()!=""){
													return valida();
												} else {
													alert("La contraseña no debe estar en blanco.");
												}
											}
											if('<?php echo "$cperfil";?>'==0){
												//$("#i203").attr('disabled','disabled');
											}


										}
										

										event.preventDefault();
										return false;
								}

								
								$("#i199").focus(borrapwd);
								$("#i200").focus(borrapwd);
								function borrapwd(){
										if($("#i199").val()==datoespecial){
											if(confirm("¿Desea modificar la contraseña?")){
												$("#i199").val("");
												$("#i200").val("");
												$("#i199").attr("placeholder","La contraseña no puede estar en blanco.");
												$("#i200").attr("placeholder","La contraseña no puede estar en blanco.");
											} else {
												$("#i199").val(datoespecial);
												$("#i200").val(datoespecial);
												$("#i201").focus();
											}
										}
								}



             });

  function caracter(q){                                                           
  	var mayus=/[A-Z]/;
  	var minus=/[a-z]/;
  	var num=/[0-9]/;
  	if((mayus.test(q))&&(minus.test(q))&&(num.test(q))) {
      return true;
    }else{
      error = 'El Password debe contener mayúsculas, minúsculas y números.';
			alert(error);
     	return false;
  	}
	}

function mostrarPerfiles() {
 $('.checkbox').toggle()
}
function ocultarPerfiles() {
 $('.checkbox').hide()
}
</script>
