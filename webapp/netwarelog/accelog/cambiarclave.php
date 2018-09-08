<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

session_start();

	//CSRF
	$reset_vars = true;
	include "../catalog/clases/clcsrf.php";	



$usuario = $_SESSION["accelog_login"];

$msg = "";

if(isset($_GET['p'])==1){
    $msg = "<font size=2 color=red>La contraseña nueva y la confirmación no coinciden.</font>";
}

if(isset($_GET['e'])==1){
    $msg = "<font size=2 color=red>La contraseña actual no es correcta.</font>";
}



?>
<html>
<head>
    <LINK href="css/estilo_accelog.css" title="estilo" rel="stylesheet" type="text/css" /> <!--RECURSO LOCAL CSS-->
    <LINK href="../catalog/css/estilo.css" title="estilo" rel="stylesheet" type="text/css" /> <!--RECURSOS EXTERNOS COMPATIBILIDAD CATALOG CSS-->
    <style type="text/css">
        body{
            /*background: url('img/fondocambiarclave.png') transparent;*/
            font-family:Tahoma, Arial;
            font-size:11px;
            color:gray;
        }
        td{
            font-size: 11px;
        }
    </style>

</head>
<body>
    <br>
    
        <?php echo $msg; ?>
    <table cellpadding="2" cellspacing="2">
        <form id="frm" action="cambiarclave_guardar.php" method="post">
			<?php 
				//CSRF - FORM
				echo $csrf->input_token($token_id,$token_value);	 
			?>		


        <tr>
            <th rowspan="4" align="center" width="100" valign="top" >
                <img src="img/key2.png" title="http://www.pixel-mixer.com/">
            </th>
            <td>CONTRASEÑA ACTUAL:</td>
            <td><input type="password" name="txtpwdactual" id="txtpwdactual" size="30" maxlength="30"></td>
        </tr>
        <tr>
            <td>CONTRASEÑA NUEVA:</td>
            <td><input type="password" name="txtpwdnueva" id="txtpwdnueva" size="30" maxlength="30"></td>
        </tr>
        <tr>
            <td>CONFIRMAR:</td>
            <td><input type="password" name="txtpwdconfirmar" id="txtpwdconfirmar" size="30" maxlength="30"></td>
        </tr>
        <tr>
            <td></td>
            <td><input type="submit" value="Guardar"></td>
        </tr>
        </form>
    </table>
        <script type="text/javascript">
            function ocultaventana(){
                parent.ocultamodificarclave();
            }
        </script>
    
</body>
</html>
