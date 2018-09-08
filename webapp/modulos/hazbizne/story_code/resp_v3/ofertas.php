<?php
	include "../../netwarelog/catalog/conexionbd.php";
?>
<html>
<head>
	<script type="text/javascript" src="../../netwarelog/catalog/js/jquery.js"></script>
	<style type="text/css">
		#loader {
		    position: fixed;
		    height: 60px;
		    width: 125px;
		    background:red;
		    top:calc(50% - 125px/2);   /* 50% - height/2 */
		    left:calc(50% - 125px/2);  /* 50% - width/2 */
			background-color: rgba(0,0,0,0.9);
			color:white;
			border-radius: 5px;
			-webkit-border-radius: 5px;
			-moz-border-radius: 5px;
			text-align: center;
			font-size: 10pt;
			padding: 10px;
			display:none;
		}
		body {
			font-family: "Arial";
			font-size: 18px;
			color: gray;
		}
		.btn {
			-moz-box-shadow:inset 0px 1px 0px 0px #ffffff;
			-webkit-box-shadow:inset 0px 1px 0px 0px #ffffff;
			box-shadow:inset 0px 1px 0px 0px #ffffff;
			background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #ededed), color-stop(1, #dfdfdf) );
			background:-moz-linear-gradient( center top, #ededed 5%, #dfdfdf 100% );
			filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#ededed', endColorstr='#dfdfdf');
			background-color:#ededed;
			-webkit-border-top-left-radius:6px;
			-moz-border-radius-topleft:6px;
			border-top-left-radius:6px;
			-webkit-border-top-right-radius:6px;
			-moz-border-radius-topright:6px;
			border-top-right-radius:6px;
			-webkit-border-bottom-right-radius:6px;
			-moz-border-radius-bottomright:6px;
			border-bottom-right-radius:6px;
			-webkit-border-bottom-left-radius:6px;
			-moz-border-radius-bottomleft:6px;
			border-bottom-left-radius:6px;
			text-indent:0;
			border:1px solid #dcdcdc;
			display:inline-block;
			color:#777777;
			font-family:arial;
			font-size:15px;
			font-weight:bold;
			font-style:normal;
			height:40px;
			line-height:40px;
			width:100px;
			text-decoration:none;
			text-align:center;
			text-shadow:1px 1px 0px #ffffff;
		}
		.btn:hover {
			background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #dfdfdf), color-stop(1, #ededed) );
			background:-moz-linear-gradient( center top, #dfdfdf 5%, #ededed 100% );
			filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#dfdfdf', endColorstr='#ededed');
			background-color:#dfdfdf;
		}.btn:active {
			position:relative;
			top:1px;
		}
		.btndisabled {
			-moz-box-shadow:outset 0px 1px 0px 0px #ffffff;
			-webkit-box-shadow:outset 0px 1px 0px 0px #ffffff;
			box-shadow:outset 0px 1px 0px 0px #ffffff;
			background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #cdcdcd), color-stop(1, #bfbfbf) );
			background:-moz-linear-gradient( center top, #cdcdcd 5%, #bfbfbf 100% );
			filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#cdcdcd', endColorstr='#bfbfbf');
			background-color:#ededed;
			-webkit-border-top-left-radius:6px;
			-moz-border-radius-topleft:6px;
			border-top-left-radius:6px;
			-webkit-border-top-right-radius:6px;
			-moz-border-radius-topright:6px;
			border-top-right-radius:6px;
			-webkit-border-bottom-right-radius:6px;
			-moz-border-radius-bottomright:6px;
			border-bottom-right-radius:6px;
			-webkit-border-bottom-left-radius:6px;
			-moz-border-radius-bottomleft:6px;
			border-bottom-left-radius:6px;
			text-indent:0;
			border:1px solid #dcdcdc;
			display:inline-block;
			color:white;
			/*color:#777777;*/
			font-family:arial;
			font-size:15px;
			font-weight:bold;
			font-style:normal;
			height:40px;
			line-height:40px;
			width:100px;
			text-decoration:none;
			text-align:center;
			/*text-shadow:1px 1px 0px #ffffff;*/
		}
		table tr td {
			padding:5px;
		}

	</style>
</head>
<body>
	<img src="../../modulos/hazbizne/background_hazbizne_enc.png" width="100%">

    <?php

        //estatus = 0 sin pedir
        //estatus = 1 pedidos

        $sql = "
            select descripcion,url,estatus
            from sms_oferta_client
            where fin_ofe >= now()
            order by estatus, fecha desc
        ";
        $result_ofertas = $conexion->consultar($sql);
        while($rs = $conexion->siguiente($result_ofertas)){
            ?>
            	<table>
            		<tbody>
            			<tr>
            				<?php
            					if($rs{"estatus"}=="0"){
            						$clase="btn";
            						$label="Pedir";
            						$url="javascript:pedir('".$rs{"url"}."');";
            						//$url="javascript:pedir('http://www.gcommx');";
            					} else {
            						$clase="btndisabled";
            						$label="Pedido";
            						$url="";
            					}
            				?>
            				<td>
            					<a 
            						class="<?php echo $clase; ?>" 
            						href="<?php echo $url; ?>">
            						<?php echo $label; ?>
            					</a>
            				</td>
            				<td><?php echo $rs{"descripcion"}; ?></td>
            			</tr>
            		</tbody>
            	</table>
            <?php
        }

    ?>

 <script>
 	function pedir(remote_url){
 		console.log("entre con loader");
 		$("#loader").fadeIn(10);
 		$.ajax({
 			url: remote_url,
 			success: function(data){
		 		$("#loader").fadeOut(10);
 				//alert("Pedí:"+url+" Recibí:"+data);
 				if(data=="ok"){
 					alert("Pedido realizado.");
 					location.reload();
 				}
 			}
 		});
 	}
 </script>

<div id="loader">
	<img src="../../netwarelog/repolog/img/loading-black.gif" width="40"></img><br>
	Solicitando pedido ...
</div>

</body>
</html>
<?php
	$cbase->close();
?>
