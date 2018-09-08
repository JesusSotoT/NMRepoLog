<?php
ini_set('memory_limit','16M');
//ini_set('display_errors', 1);
set_time_limit(300);
	$modulo = (isset($_GET['modulo'])) ? $_GET['modulo'] : 0;

  	include('headers2.php');
	include('conexiondb.php');


 	$idusr = $_SESSION['accelog_idempleado'];

	$SQL = "SELECT interfaz FROM constru_user_interfaz WHERE id_usuario='$idusr' LIMIT 1;";
	$result = $mysqli->query($SQL);
	if($result->num_rows>0) {
	    $row = $result->fetch_array();
	    if($row['interfaz']=='default'){
	    	$interfaz='default';
	    }else{
	    	$interfaz='fondo_xtructur0'.$row['interfaz'].'.png';
		}
	}else{	
	  	$interfaz='default';
	}

?>
<link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap.min.css" type="text/css">
	<link rel="stylesheet" href="css/dialogo.css" type="text/css">
	<style>
	  @media print{
	    #imprimir,#filtros,#excel, #botones
	    {
	      display:none;
	    }
	    #logo_empresa
	    {
	      display:block;
	    }
	    .table-responsive{
	      overflow-x: unset;
	    }
	    #imp_cont{
	      width: 100% !important;
	    }
	  }
	  .btnMenu{
	    border-radius: 0; 
	    width: 100%;
	    margin-bottom: 0.3em;
	    margin-top: 0.3em;
	  }
	  .row
	  {
	      margin-top: 0.5em !important;
	  }
	  .titulo, h5, h4, h3{
	      background-color: #eee;
	      padding: 0.4em;
	  }
	  .modal-title{
	    background-color: unset !important;
	    padding: unset !important;
	  }
	  .nmwatitles, [id="title"] {
	    padding: 8px 0 3px !important;
	    background-color: unset !important;
	  }
	  .select2-container{
	    width: 100% !important;
	  }
	  .select2-container .select2-choice{
	    background-image: unset !important;
	    height: 31px !important;
	  }
	  .twitter-typeahead{
	    width: 100% !important;
	  }
	  .tablaResponsiva{
	      max-width: 100vw !important; 
	      display: inline-block;
	  }
	  
	  .cfinal{
	  	margin-bottom: 5em;
	  }
	  .ui-widget-content{
		border: 1px solid #c0c0c0;
	  }


	</style>
	
	
	<style>
	/*
iframe, body{
	background-image: url(edificio2.jpg);
	background-size: 1480px 720px;
}
*/
/*<!-- Clouds -->
iframe, body{
background: #ECE9E6;  
background: -webkit-linear-gradient(to right, #FFFFFF, #ECE9E6);  
background: linear-gradient(to right, #FFFFFF, #ECE9E6); 

}

/*<!-- Verde -->
iframe, body{
background: #abbaab;
background: -webkit-linear-gradient(to right, #ffffff, #abbaab); 
background: linear-gradient(to right, #ffffff, #abbaab); 
}

/* Azul 
iframe, body{
	color:#000;
	background: #D7DDE8;   
	background: -webkit-linear-gradient(to right, #D7DDE8, #757F9A);  
	background: linear-gradient(to right, #D7DDE8, #757F9A);
}
*/
/*
iframe, body{
background: #ECE9E6;  
background: -webkit-linear-gradient(to right, #FFFFFF, #ECE9E6);  
background: linear-gradient(to right, #FFFFFF, #ECE9E6); 

}

iframe, body{
	background-image: url(fondo_xtructur05.png);
	background-size: 100%;
}
*/
</style>

<?php if($interfaz=='default'){ ?>
<style>
	iframe, body{
	background: #F5F5F5;  
	background: -webkit-linear-gradient(to right, #FFFFFF, #F5F5F5);  
	background: linear-gradient(to right, #FFFFFF, #F5F5F5); 
	
	}
	.ui-jqgrid tr.ui-row-ltr td { 
			/*border:none; */
			background-color: rgba(255, 255, 255, 0.98);
		}
</style>
<?php }else{ ?>
<style>
	iframe, body{
	background-image: url('<?php echo $interfaz; ?>');
	background-size: 100%;
	background-attachment: fixed;

	}

	.ui-jqgrid tr.ui-row-ltr td { 
			/*border:none; */
			
			background-color: rgba(252, 252, 252, 0.96);
			/*background-color: #ffffff;*/
		}
</style>
<?php } ?>

<style>
		.navbar, .navbar-default{
			background-color: inherit;

			background: rgba(255, 255, 255, 0.98);

			/*border: none;*/
		}

		.ui-jqgrid-hbox thead{
			background: rgba(0, 0, 0, 0.2);
			background: rgba(255, 255, 255, 0.98);

		}

		#jq_arbol, tbody{
			/*background: rgba(255, 255, 255, 0.3);*/
		}

		.panel, .panel-default{
			background:inherit; background: rgba(0, 0, 0, 0.1);
			background: rgba(255, 255, 255, 0.95);
		}
		.panel-heading{
			background:none; 
			background: rgba(255, 255, 255, 0.98);
			
		}

		.ui-jqgrid-pager{
			background: rgba(0, 0, 0, 0.2);
			background: rgba(255, 255, 255, 0.98);
		}
		



		.footrow{
	  	background: rgba(255, 255, 255, 0.98);
	  }


/*
		.container > div:nth-child(2){
		    background: red;
		}
		*/
		/*.ui-jqgrid tr.ui-row-ltr td {
text-align: left;
border-right-width: 0px;
border-right-color: inherit;
border-right-style: solid;
background: rgba(0, 0, 0, 0.1);
}*/
/*
		.ui-jqgrid{
			border:none;
		}

		.ui-jqgrid-bdiv{
			border: none;
		}
/*
		.table-bordered > tbody > tr > td, .table-bordered > tbody > tr > th, .table-bordered > tfoot > tr > td, .table-bordered > tfoot > tr > th, .table-bordered > thead > tr > td, .table-bordered > thead > tr > th {
    border: 0 solid #ddd;
}
.ui-jqgrid tr.ui-row-ltr td { border-right-color: transparent; }
.ui-jqgrid tr.ui-row-ltr td { border-bottom-color: transparent; }
th.ui-th-column { border-right-color: transparent !important }
.ui-jqgrid-labels .ui-th-column { border-right-color: transparent }
.footrow footrow-ltr {
border-right-color: transparent !important 
border-left-color: transparent !important 
border-top-color: transparent !important 
}
*/

.FormError{
	margin: 5px 15px 5px 0px; padding: 5px; border: 1px solid rgb(191, 0, 0);
}



	</style>





<?php
    $idusr = $_SESSION['accelog_idempleado'];
    $SQL = "SELECT concat(nombre,' ',apellido1) as username, idempleado from empleados where idempleado='$idusr';";
    $result = $mysqli->query($SQL);
	$row = $result->fetch_array();
	$username_global=$row['username'];
	$id_username_global=$row['idempleado'];




	if($modulo=="datos_generales"){ include('jscontratista.php'); exit(); }
	if($modulo=="alta_obra"){ include('jsaltaobra.php'); exit(); }
	if($modulo=="usuarios_obras"){ include('jsusuarios_obra.php'); exit(); }
    if($modulo=="notilog"){ include('notilog.php'); exit(); }
    if($modulo=="traspaso"){ include('traspaso.php'); exit(); }
    if($modulo=="interfaz"){ include('interfaz.php'); exit(); }

	if(!isset($_COOKIE['xtructur'])){
		$idperfil = preg_replace('/\(|\)/','',$_SESSION['accelog_idperfil']);

		if($idperfil==2){
	    	$SQL = "SELECT id,obra FROM constru_generales WHERE borrado=0 ORDER BY obra;";
		}else{
			$SQL = "SELECT a.id,a.obra FROM constru_generales a 
	    left join constru_obrasusuario b on  a.id=b.idobra  
	    WHERE a.borrado=0 AND b.iduser='$idperfil' group by a.id ORDER BY obra;";

		}
	    


		$result = $mysqli->query($SQL);

		while($row = $result->fetch_array() ) {
       		$obras[]=$row;
    	}

    	include('access_obras.php');
    	exit();
 
	}else{
		$cookie_xtructur = unserialize($_COOKIE['xtructur']);
		$idses_obra = $cookie_xtructur['id_obra'];
		$nombre_obra = $cookie_xtructur['obra'];
		$obra_ini = $cookie_xtructur['obra_ini'];
		$obra_fin = $cookie_xtructur['obra_fin'];

		$SQL = "SELECT presupuesto FROM constru_config WHERE id_obra='$idses_obra' LIMIT 1;";
        $result = $mysqli->query($SQL);
        if($result->num_rows>0) {
        	$row = $result->fetch_array();
        	$presX=$row['presupuesto'];
        }else{
        	$presX=0;
        }
	?>
	
	
		<body>
			<div class="container" style="width:100%">
    			<div class="row">
      				<div class="col-sm-10 col-sm-offset-1">
						<div class="row" style="background-color:inherit; margin-left: 0; margin-right: 0;">
							<div class="col-sm-10" style="padding: 5px;">
								<input id="nombre_obra_x" type="hidden" value="<?php echo $nombre_obra; ?>">
								<b>Obra seleccionada:</b> <?php echo $nombre_obra; ?>
							</div>

							<div class="col-sm-2" style="padding:0px;">
							<button id="btnGenReq" class="btn btn-primary btn-sm pull-right" onclick="cerrar_session('<?php echo $modulo; ?>');" id="btnGenReq"> Cambiar de obra</button>

			
							</div>
						</div>
						<div class="row">
							<div class="col-sm-12">
								<section id="swi_cont">
									<?php	
									switch ($modulo) {
										case 'proforma':
											include('jsproforma2.php');
										break;

										case 'explosion_insumos':
											include('jsexplosion.php');
										break;

										case 'asignar_familias':
											include('jsasignarfam.php');
										break;

										case 'crear_presu_control':
											if($presX>0){
												include('jspresupuesto_all.php');
											}else{
												include('jspresupuesto.php');
											}
										break;

										case 'extra_control':
											include('jsextra.php');
										break;

										case 'extra_aut':
											include('jsextraaut.php');
										break;

										case 'adic_control':
											include('jsadic.php');
										break;

										case 'adic_aut':
											include('jsadicaut.php');
										break;

										case 'nocob_control':
											include('jsnocob.php');
										break;

											case 'nocob_aut':
											include('jsnocobaut.php');
										break;

										case 'planeacion':
											include('jsagrupador.php');
										break;

										case 'arbol':
											include('jsarbol.php');
										break;

										case 'asignar_planeacion':
											include('jsasignacion.php');
										break;
										
										case 'visualizar_pcontrol':
											include('jsvisualizarpcontrol.php');
										break;

										case 'requisiciones':
											include('jsrequisiciones.php');
										break;

										case 'pedidos':
											include('jspedidos2.php');
										break;

										case 'alta_tecnicos':
											include('jstecnicos.php');
										break;

										case 'alta_administrativos':
											include('jsadministrativo.php');
										break;

										case 'alta_subcontratistas':
											include('jssubcontratistas.php');
										break;

										case 'alta_proveedores':
											include('jsproveedores.php');
										break;

										case 'alta_destajista':
											include('jsdestajista.php');
										break;

										case 'alta_obreros':
											include('jsobreros.php');
										break;

										case 'pu_destajos':
											include('jspudestajos.php');
										break;

										case 'pu_subcontratos':
											include('jspusubcontratos.php');
										break;

										case 'desgloce_indirectos':
											include('desgloce_indirectos.php');
										break;

										case 'materiales':
											include('jsmateriales.php');
										break;

										case 'alta_fam_obreros':
											include('jsfamobreros.php');
										break;

										case 'alta_fam_tecnicos':
											include('jsfamtecnicos.php');
										break;

										case 'tab_obreros':
											include('jstabobreros.php');
										break;

										case 'tab_tecnicos':
											include('jstabtecnicos.php');
										break;

										case 'visualizar_requi':
											include('jsvisualizar_requi.php');
										break;

										case 'visualizar_pedi':
											include('jsvisualizar_pedi.php');
										break;

										case 'arbols':
											include('jsarbols.php');
										break;

										case 'indicarpu':
											include('jsindicarpu.php');
										break;

										case 'entradas':
											include('jsentradas.php');
										break;

										case 'visualizar_entradas':
											include('jsvisualizar_entradas.php');
										break;

										case 'visualizar_salidas':
											include('jsvisualizar_salidas.php');
										break;

										case 'salidas':
											include('jssalidas.php');
										break;

										case 'costo_acumulado':
											include('jscacumulado.php');
										break;

										case 'costo_directo':
											include('jscdirecto.php');
										break;

										case 'costo_indirecto':
											include('jscindirecto.php');
										break;

										case 'alta_ps':
											include('jsps.php');
										break;

										case 'construccion':
											include('construccion.php');
										break;

										case 'tomaduria':
											include('jstomaduria.php');
										break;

										case 'prenomina':
											include('jsprenomina.php');
										break;

										case 'prenomina_auth':
											include('jsprenomina_aut.php');
										break;

										case 'est_destajistas':
											include('jsest_destajistas.php');
										break;

										case 'est_subcontratistas':
											include('jsest_subcontratistas.php');
										break;

										case 'est_indirectos':
											include('jsest_indirectos.php');
										break;

										case 'est_cc':
											include('jsest_chica.php');
										break;

										case 'est_cliente':
											include('jsest_cliente.php');
										break;

										case 'est_proveedores':
											include('jsest_prov.php');
										break;

										case 'nom_tom_oce':
											include('jstomaduria_tec.php');
										break;

										case 'nom_oce':
											include('jsprenomina_tec.php');
										break;

										case 'nom_ocen':
											include('jsprenominacen_tec.php');
										break;

										case 'prenom_oce':
											include('jsprenominaver_tec.php');
										break;

										case 'prenom_ocen':
											include('jsprenominavercen_tec.php');
										break;


										case 'unovsuno':
											//include('jsunovsuno.php');
											include('jsunovsuno3.php');
										break;

										case 'ingresos_egresos':
											//include('jsunovsuno.php');
											include('ingresos_egresos.php');
										break;
										case 'ingresos_egresosM':
											//include('jsunovsuno.php');
											include('ingresos_egresosM.php');
										break;

										case 'remesas':
											include('jsremesas3.php');
										break;

										case 'cheques':
											include('jsremesas2.php');
										break;

										case 'estado_resultados':
											include('jsestado.php');
										break;

										case 'control_indirectos':
											include('jscontrol_indirectos.php');
										break;

										case 'acumulado_detalle':
											include('jsacumulado_detalle.php');
										break;

										case 'cat_partidas':
											include('jscat_partidas.php');
										break;

										case 'cat_especialidades':
											include('jscat_especialidades.php');
										break;

										case 'retenciones':
											include('jsretenciones.php');
										break;

										case 'est_cliente_bit':
											include('jsest_cliente_bit_all.php');
										break;

										case 'est_destajistas_bit':
											include('jsest_destajistas_bit_all.php');
										break;

										case 'est_subcontratistas_bit':
											include('jsest_subcontratistas_bit_all.php');
										break;

										case 'est_proveedores_bit':
											include('jsest_prov_bit_all.php');
										break;

										case 'inventarios':
											include('jsinventarios.php');
										break;

										case 'programa_obra':
											include('programaobra.php');
										break;

										case 'config':
											include('config.php');
										break;

										case 'gantt':
											include('pogantt.php');
										break;

										case 'tablero':
											include('tablero.php');
										break;

										case 'aut_cuentaspp':
											include('aut_cuentaspp.php');
										break;

                                        case 'viz_cc':
											include('jsviz_chica.php');
										break;
										
										case 'viz_ind':
											include('jsviz_ind.php');
										break;

                                         case 'historialnom':
											include('historialnom.php');
										break;
										case 'historialest':
											include('historialest.php');
										break;
										case 'historialpas':
											include('historialpas.php');
										break;

										case 'historialpres':
											include('historialpres.php');
										break;

										case 'historialcompras':
											include('historialcompras.php');
										break;

										case 'recetas':
											include('recetas.php');
										break;

										case 'tsalida':
											include('jstsalida.php');
										break;

										case 'tentrada':
											include('jstentrada.php');
										break;

                                        case 'cobros':
											include('jscobrar.php');
										break;

										case 'cobrado':
											include('jscobrados.php');
										break;

										case 'imp_proveedores':
											include('impprov.php');
										break;

										case 'imp_subcontratistas':
											include('impsubc.php');
										break;

									case 'Avance':
											include('avance.php');
										break;

										case 'visor':
											include('visor.php');
										break;

											case 'visorp':
											include('prov_aprob.php');
										break;

											case 'visorr':
											include('visor_requi.php');
										break;
										
										default:
											# code...
										break;
										
									}
									?>
								</section>
							</div>
						</div>
					</div>
				</div>
			</div>
		</body>
		<?php
	}

?>