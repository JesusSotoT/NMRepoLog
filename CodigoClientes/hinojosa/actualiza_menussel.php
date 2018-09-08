<?php

//Actualiza Menus Selectivos

ini_set("display_errors",0);


  //Coneccion a Base de Datos NetwarStore - Transversal
  $strDBHost="nmdb.cyv2immv1rf9.us-west-2.rds.amazonaws.com";
  $strDBUsr="nmdevel";
  $strDBPwd="nmdevel";
  $strDBName="netwarstore";
  $strSalt="\$2a\$07\$store.netwarmo00000000\$";
  $objCon = mysqli_connect($strDBHost,$strDBUsr,$strDBPwd,$strDBName);
  mysqli_query($objCon, "SET NAMES 'utf8'");

  //Define arreglo de tablas para consulta de datos -->> Agregar Consulta para que genere de manera automatica las tablas de años
  $arrTables = array("netwarelog_transacciones_2016_s1","netwarelog_transacciones_2015_s2","netwarelog_transacciones_2015_s1","netwarelog_transacciones_2014_s2","netwarelog_transacciones_2014_s1","netwarelog_transacciones_2013_s2","netwarelog_transacciones_2013_s1");
  $arrRecords = array("accelog_usuarios","mrp_producto","venta","pvt_respuestaFacturacion","comun_cliente","cont_accounts","cont_polizas","com_comandas","mrp_orden_produccion");
  mysqli_set_charset($objCon,"UTF-8");

  //$strSql = "select * from customer where id in (select idcustomer from appclient where idapp='1011') and status_instancia<>4";

  $strSql="Select * from customer where (instancia='comercializadora'
 Or instancia='claudiohinojosa'
 Or instancia='cofinem'
 Or instancia='bara610401'
 Or instancia='cavh750116'
 Or instancia='cro070509'
 Or instancia='ela060628'
 Or instancia='gaga570501'
 Or instancia='huee430210'
 Or instancia='vamg690114'
 Or instancia='aaaa911223'
 Or instancia='aaam780609'
 Or instancia='aama580309'
 Or instancia='aamm500610'
 Or instancia='aamp620816'
 Or instancia='aifm530228'
 Or instancia='aift541003'
 Or instancia='aps130913'
 Or instancia='bamj680915'
 Or instancia='basl471009'
 Or instancia='beca840918'
 Or instancia='bepg60081'
 Or instancia='cac060318'
 Or instancia='cad080701'
 Or instancia='camv500213'
 Or instancia='cat850617'
 Or instancia='cavk740506'
 Or instancia='ccv930203'
 Or instancia='cla810309'
 Or instancia='cmc810216'
 Or instancia='cogd221107'
 Or instancia='cogh851126'
 Or instancia='copg581021'
 Or instancia='csa940211'
 Or instancia='csm910422'
 Or instancia='cte041124'
 Or instancia='cufs760311'
 Or instancia='cuzs460830'
 Or instancia='dacn710620'
 Or instancia='dco120621'
 Or instancia='dmr060712'
 Or instancia='eaea690307'
 Or instancia='ease731013'
 Or instancia='ease960211'
 Or instancia='eibj840621'
 Or instancia='esc341031'
 Or instancia='euer540815'
 Or instancia='eujo850122'
 Or instancia='eurj680201'
 Or instancia='eurm860604'
 Or instancia='fabl640310'
 Or instancia='fabr730830'
 Or instancia='famr680702'
 Or instancia='gaaa830825'
 Or instancia='gaam851010'
 Or instancia='gac990511'
 Or instancia='gaee880605'
 Or instancia='gagh650801'
 Or instancia='gagl660724'
 Or instancia='gagz510522'
 Or instancia='gav060224'
 Or instancia='gjm020515'
 Or instancia='goma510429'
 Or instancia='gosc730729'
 Or instancia='gosg711025'
 Or instancia='gosj540322'
 Or instancia='gpp150814'
 Or instancia='gugi580927'
 Or instancia='gugm440120'
 Or instancia='gupt710622'
 Or instancia='heu920713'
 Or instancia='hurc560129'
 Or instancia='kmi100903'
 Or instancia='kuij500327'
 Or instancia='kurc810423'
 Or instancia='laf040210'
 Or instancia='locr601024'
 Or instancia='lomj300517'
 Or instancia='macr540921'
 Or instancia='majj500815'
 Or instancia='majl550107'
 Or instancia='male550413'
 Or instancia='mavc331208'
 Or instancia='mejo740620'
 Or instancia='meml510420'
 Or instancia='mesj510125'
 Or instancia='meth440301'
 Or instancia='metr461230'
 Or instancia='mocg640112'
 Or instancia='moct440317'
 Or instancia='momb750429'
 Or instancia='mome520609'
 Or instancia='momj720111'
 Or instancia='more460801'
 Or instancia='mors650928'
 Or instancia='mosj731021'
 Or instancia='movb400715'
 Or instancia='movg381008'
 Or instancia='nayo510827'
 Or instancia='niba831022'
 Or instancia='nimg780625'
 Or instancia='pacf610129'
 Or instancia='pagh390113'
 Or instancia='pebl801026'
 Or instancia='pers741103'
 Or instancia='pmo030207'
 Or instancia='pnr060828'
 Or instancia='rahm620929'
 Or instancia='rame560329'
 Or instancia='raqa670618'
 Or instancia='rarf960822'
 Or instancia='rarl820329'
 Or instancia='ravm521126'
 Or instancia='recg761211'
 Or instancia='reg040826'
 Or instancia='repa480311'
 Or instancia='rica511207'
 Or instancia='roje361026'
 Or instancia='rugr821215'
 Or instancia='rume550725'
 Or instancia='safe820808'
 Or instancia='sahl621106'
 Or instancia='sama660417'
 Or instancia='sasr831101'
 Or instancia='sava851030'
 Or instancia='seg080922'
 Or instancia='smt000710'
 Or instancia='ssa041129'
 Or instancia='ssg990122'
 Or instancia='ssm921207'
 Or instancia='suar850318'
 Or instancia='sucd590402'
 Or instancia='tazm630124'
 Or instancia='tca060719'
 Or instancia='uada751108'
 Or instancia='uadd630403'
 Or instancia='uagc580203'
 Or instancia='uisc500430'
 Or instancia='vacr540202'
 Or instancia='vagc670805'
 Or instancia='vamg630525'
 Or instancia='vare490602'
 Or instancia='veea571030'
 Or instancia='vell851121'
 Or instancia='vemc620927'
 Or instancia='wime361101'
 Or instancia='zacm360525'
 Or instancia='kdi111104')";
  $rstCustomer = mysqli_query($objCon, $strSql);

  //Consigue los Datos para Agregarlos a la Tabla Temporal
  $sql_NS_Values="";
  $intTr = 0;
  while($objCustomer=mysqli_fetch_array($rstCustomer)){
        //$sqlINSERT="INSERT IGNORE INTO ".$objCustomer['nombre_db'] .".accelog_perfiles_me (idperfil, idmenu) VALUES (2, 2085), (2, 2086), (2, 2088), (2,2095), (2, 2098), (2, 2100);";
        //$sqlINSERT="delete from accelog_perfiles_me where idmenu=2361";
        echo "<strong>-----------------".$objCustomer['instancia']."-----------</strong><br>";
        $sqlINSERT="Truncate ".$objCustomer['nombre_db'].".accelog_menu;";
        mysqli_query($objCon, $sqlINSERT);
        echo "Truncate accelog_menu".$objCustomer['nombre_db']."<br>";

        $sqlINSERT="INSERT INTO ".$objCustomer['nombre_db'].".accelog_menu (`idmenu`, `nombre`, `idmenupadre`, `url`, `idcategoria`, `icono`, `orden`, `omision`)
        VALUES
        	(1,'CataLog',0,'../catalog/admin/index.php',1,0,20,0),
        	(2,'Mi Organizaci',0,'../catalog/gestor.php?idestructura=1&ticket=testing',1,0,1,0),
        	(3,'Catálogo de Empleados',0,'../catalog/gestor.php?idestructura=2&ticket=testing',1,0,20,0),
        	(4,'Categorías',0,'../catalog/gestor.php?idestructura=3&ticket=testing',1,0,20,0),
        	(5,'Menús',0,'../catalog/gestor.php?idestructura=4&ticket=testing',1,0,20,0),
        	(6,'Opciones',0,'../catalog/gestor.php?idestructura=7&ticket=testing',1,0,20,0),
        	(7,'Perfiles',0,'../catalog/gestor.php?idestructura=5&ticket=testing',1,0,20,0),
        	(8,'Perfiles - Menús',0,'../catalog/gestor.php?idestructura=6&ticket=testing',1,0,20,0),
        	(9,'Perfiles - Opciones',0,'../catalog/gestor.php?idestructura=8&ticket=testing',1,0,20,0),
        	(11,'Lista de perfiles por usuario',0,'../catalog/gestor.php?idestructura=9&ticket=testing',1,0,20,0),
        	(12,'RepoLog',0,'../repolog/admin/index.php',1,0,20,0),
        	(13,'DocLog',0,'../doclog/admin/index.php',1,0,20,0),
        	(14,'Usuarios Niveles',0,'../catalog/gestor.php?idestructura=11&ticket=testing',1,0,20,0),
        	(40,'Administracion de Usuarios',0,'../catalog/gestor.php?idestructura=47&ticket=testing',1,0,3,0),
        	(112,'Calendario',0,'../../modulos/restaurantes/reservaciones/index.php',1052,0,2,0),
        	(142,'Ejercicios',2372,'../../modulos/cont/index.php?c=Config&f=mainPage',1050,0,0,0),
        	(143,'Consulta/Captura',1664,'../../modulos/cont/index.php?c=CaptPolizas&f=Ver',18,0,0,0),
        	(145,'Catalogo de Cuentas',2372,'../../modulos/cont/index.php?c=arbol&f=index',1050,0,4,0),
        	(1118,'Estado de Resultados',1639,'../../modulos/cont/index.php?c=reports&f=balanceGeneral&tipo=0',1049,0,1,0),
        	(1119,'Impresión',1664,'../../modulos/cont/index.php?c=polizasImpresion&f=Inicial&tipo=1',18,0,1,0),
        	(1120,'Reportes Financieros',0,'',1049,0,6,0),
        	(1122,'Libro de Mayor',1639,'../../modulos/cont/index.php?c=Reports&f=libro_mayor',1049,0,3,0),
        	(1124,'Inactivas',1664,'../../modulos/cont/index.php?c=CaptPolizas&f=ListaPolizasEliminadas',18,0,2,0),
        	(1126,'ParcialLog',0,'../doclog/abrir.php?iddocumento=12',1,0,20,0),
        	(1162,'Reporte de Catalogo de Cuentas',2369,'../../modulos/cont/index.php?c=reports&f=catalogoCuentas',1049,0,0,0),
        	(1182,'Balance General',1639,'../../modulos/cont/index.php?c=reports&f=balanceGeneral&tipo=1',1049,0,0,0),
        	(1588,'Reportes Fiscales',0,'',1049,0,9,0),
        	(1589,'Reportes DIOT',1588,'',1049,0,0,0),
        	(1590,'Egresos sin Control de IVA',1589,'../../modulos/cont/index.php?c=EgresosSinIva&f=Inicial',1049,0,0,0),
        	(1599,'Concentrado de IVA por Proveedor',1589,'../../modulos/cont/index.php?c=ConcentradoIVAProveedor&f=verconcentrado',1049,0,1,0),
        	(1603,'Auxiliar de Impuestos',1606,'../../modulos/cont/index.php?c=Auxiliar_Impuestos&f=filtro',1049,0,0,0),
        	(1604,'Declaración R-21 Impuesto al Valor Agregado',1606,'../../modulos/cont/index.php?c=declaracionR21&f=filtro',1049,0,1,0),
        	(1606,'Declaraciones IVA',1588,'',1049,0,1,0),
        	(1607,'Resumen General R-21',1606,'../../modulos/cont/index.php?c=resumenGeneralR21&f=filtro',1049,0,2,0),
        	(1608,'Anexos IVA Causado y Acreditable',1609,'../../modulos/cont/index.php?c=anexosIVACausadoAcreditable&f=filtro',1049,0,0,0),
        	(1609,'Pago Provisional de IVA',1588,'',1049,0,2,0),
        	(1610,'Conciliación de IVA Contable y Fiscal',1609,'../../modulos/cont/index.php?c=conciliacion_IVA_contable_fiscal&f=filtro',1049,0,1,0),
        	(1614,'Auxiliar de Formato A-29',1589,'../../modulos/cont/index.php?c=auxiliar_a29&f=Inicial',1049,0,2,0),
        	(1617,'Auxiliar de Movimientos de Control de IVA',1589,'../../modulos/cont/index.php?c=auxiliar_controlIva&f=Inicial',1049,0,3,0),
        	(1621,'Conciliación de Flujo de Efectivo e IVA',1589,'../../modulos/cont/index.php?c=flujoEfectivoIva&f=Inicial',1049,0,4,0),
        	(1632,'Movimientos por Cuentas',1641,'../../modulos/cont/index.php?c=Reports&f=movcuentas',1049,0,0,0),
        	(1633,'Balanza de Comprobación',1639,'../../modulos/cont/index.php?c=reports&f=balanzaComprobacion',1049,0,2,0),
        	(1639,'Estados Financieros',1120,'',1049,0,0,0),
        	(1641,'Auxiliares',1120,'',1049,0,1,0),
        	(1642,'Anexos de Catálogo',1641,'../repolog/repolog.php?i=59',1049,0,1,0),
        	(1643,'Contabilidad Electrónica',0,'',18,0,7,0),
        	(1645,'XMLs Catalogo de Cuentas',1643,'../../modulos/cont/index.php?c=Reports&f=catalogoXML',18,0,0,0),
        	(1646,'XMLs Balanza de Comprobación',1643,'../../modulos/cont/index.php?c=Reports&f=balanzaComprobacionXML',18,0,1,0),
        	(1647,'Asignación de Cuentas',2372,'../../modulos/cont/index.php?c=Config&f=configAccounts',1050,0,2,0),
        	(1648,'Cobros a Clientes',1665,'../../modulos/cont/index.php?c=CaptPolizas&f=filtroAutomaticas&t=cobro',18,0,1,0),
        	(1649,'Pagos a Proveedores',1665,'../../modulos/cont/index.php?c=CaptPolizas&f=filtroAutomaticas&t=pago',18,0,2,0),
        	(1653,'Validador de XMLs',2370,'../../modulos/cont/xmls/valida_xmls/validacion.php',18,0,4,0),
        	(1654,'Almacén Digital',2370,'../../modulos/cont/index.php?c=almacen&f=almacenXml',18,0,1,0),
        	(1656,'Segmentos de Negocio',2372,'../catalog/gestor.php?idestructura=251&ticket=testing',1050,0,1,0),
        	(1663,'Asignación Periodo Acreditamiento',1666,'../../modulos/cont/index.php?c=reports&f=listaAcreditamientoProveedores',18,0,0,0),
        	(1664,'Pólizas',0,'',18,0,1,0),
        	(1665,'Pólizas Automáticas',0,'',18,0,2,0),
        	(1666,'Control de IVA',0,'',18,0,8,0),
        	(1667,'A-29 Proveedores TXT',1666,'../../modulos/cont/index.php?c=Reports&f=a29Txt',18,0,0,0),
        	(1668,'Resumen de IVAs retenidos ',1606,'../catalog/gestor.php?idestructura=252&ticket=testing',1049,0,1,0),
        	(1675,'Tipos de Cambio',2372,'../catalog/gestor.php?idestructura=257&ticket=testing',1050,0,7,0),
        	(1677,'Póliza de Ajuste por Diferencia Cambiaria',1665,'../../modulos/cont/index.php?c=Ajustecambiario&f=verfiltro',18,0,5,0),
        	(1694,'Estado de Origen y Aplicación de Recursos',1639,'../../modulos/cont/index.php?c=reports&f=balanceGeneral&tipo=2',1049,0,5,0),
        	(1695,'Clasificación NIF de Cuentas',2372,'../../modulos/cont/index.php?c=AccountsTree&f=cuentasNIF',1050,0,6,0),
        	(1696,'NIF B-6 Estado de Situación Financiera',1702,'../../modulos/cont/index.php?c=reports&f=balanceGeneral&tipo=3',1049,0,5,0),
        	(1702,'Estados Financieros NIF',1120,'',1049,0,1,0),
        	(1704,'NIF B-3 Estado de resultado Integral',1702,'../../modulos/cont/index.php?c=reports&f=balanceGeneral&tipo=4',1049,0,6,0),
        	(1706,'Bancos de Beneficiarios/Proveedores ',0,'../catalog/gestor.php?idestructura=275&ticket=testing',1048,0,2,0),
        	(1714,'Libro de Diario',1639,'../../modulos/cont/index.php?c=polizasImpresion&f=Inicial&tipo=2',1049,0,1,0),
        	(1727,'Datos generales constructores',0,'../../modulos/xtructur/index.php?modulo=datos_generales',1058,0,1,0),
        	(1728,'Alta de obra',0,'../../modulos/xtructur/index.php?modulo=alta_obra',1058,0,2,0),
        	(1730,'Presupuesto contractual',0,'../../modulos/xtructur/index.php?modulo=crear_presu_control',1062,0,1,0),
        	(1732,'Proforma',0,'../../modulos/xtructur/index.php?modulo=proforma',1062,0,2,0),
        	(1733,'Explosion de insumos',0,'../../modulos/xtructur/index.php?modulo=explosion_insumos',1062,0,4,0),
        	(1734,'Desglose de Indirectos',0,'../../modulos/xtructur/index.php?modulo=desgloce_indirectos',1062,0,3,0),
        	(1736,'Definir planeacion',0,'../../modulos/xtructur/index.php?modulo=planeacion',1059,0,3,0),
        	(1737,'Visualizar arbol de planeacion',0,'../../modulos/xtructur/index.php?modulo=arbol',1059,0,8,0),
        	(1738,'Definir familia de materiales',0,'../../modulos/xtructur/index.php?modulo=materiales',1059,0,10,0),
        	(1741,'Asignar planeacion a presupuesto',0,'../../modulos/xtructur/index.php?modulo=asignar_planeacion',1059,0,4,0),
        	(1742,'Asignar PU a destajo',0,'../../modulos/xtructur/index.php?modulo=pu_destajos',1059,0,6,0),
        	(1743,'Asignar PU a subcontrato',0,'../../modulos/xtructur/index.php?modulo=pu_subcontratos',1059,0,7,0),
        	(1745,'Asignar familias a explosion de insumos',0,'../../modulos/xtructur/index.php?modulo=asignar_familias',1059,0,11,0),
        	(1747,'Tabulador tecnico administrativo',0,'../../modulos/xtructur/index.php?modulo=tab_tecnicos',1063,0,3,0),
        	(1748,'Tabulador de obreros',0,'../../modulos/xtructur/index.php?modulo=tab_obreros',1063,0,4,0),
        	(1750,'Tecnicos',0,'../../modulos/xtructur/index.php?modulo=alta_tecnicos',1064,0,1,0),
        	(1751,'Administrativos',0,'../../modulos/xtructur/index.php?modulo=alta_administrativos',1064,0,2,0),
        	(1752,'Subcontratistas',0,'../../modulos/xtructur/index.php?modulo=alta_subcontratistas',1064,0,6,0),
        	(1753,'Proveedores / Subcontratistas',0,'../../modulos/xtructur/index.php?modulo=alta_proveedores',1064,0,9,0),
        	(1755,'Solicitud de Extraordinarios',0,'../../modulos/xtructur/index.php?modulo=extra_control',1070,0,1,0),
        	(1756,'Solicitud de Adicionales',0,'../../modulos/xtructur/index.php?modulo=adic_control',1070,0,2,0),
        	(1757,'Solicitud de No Cobrables',0,'../../modulos/xtructur/index.php?modulo=nocob_control',1070,0,3,0),
        	(1758,'Visualizar Cuentas de Costo',0,NULL,1057,0,3,0),
        	(1759,'Costo acumulado',1758,'../../modulos/xtructur/index.php?modulo=costo_acumulado',1057,0,3,0),
        	(1760,'Costo directo',1758,'../../modulos/xtructur/index.php?modulo=costo_directo',1057,0,1,0),
        	(1761,'Costo indirecto',1758,'../../modulos/xtructur/index.php?modulo=costo_indirecto',1057,0,2,0),
        	(1763,'Elaboracion de Requisiciones',0,'../../modulos/xtructur/index.php?modulo=requisiciones',1065,0,1,0),
        	(1764,'Elaboracion de Ordenes de compra',0,'../../modulos/xtructur/index.php?modulo=pedidos',1065,0,3,0),
        	(1765,'Entradas de almacen',0,'../../modulos/xtructur/index.php?modulo=entradas',1065,0,5,0),
        	(1766,'Salidas de almacen',0,'../../modulos/xtructur/index.php?modulo=salidas',1065,0,6,0),
        	(1768,'Maestros',0,'../../modulos/xtructur/index.php?modulo=alta_destajista',1064,0,3,0),
        	(1769,'Obreros',0,'../../modulos/xtructur/index.php?modulo=alta_obreros',1064,0,4,0),
        	(1770,'Personal de Subcontratistas',0,'../../modulos/xtructur/index.php?modulo=alta_ps',1064,0,7,0),
        	(1771,'Control de Asistencia Obreros',0,'../../modulos/xtructur/index.php?modulo=tomaduria',1068,0,1,0),
        	(1772,'Elaboracion Nomina Obreros',0,'../../modulos/xtructur/index.php?modulo=prenomina',1068,0,2,0),
        	(1774,'Alta familia obreros',0,'../../modulos/xtructur/index.php?modulo=alta_fam_obreros',1063,0,2,0),
        	(1775,'Alta familia Tecnicos-Administradores',0,'../../modulos/xtructur/index.php?modulo=alta_fam_tecnicos',1063,0,1,0),
        	(1776,'Autorizacion de Requisición',0,'../../modulos/xtructur/index.php?modulo=visualizar_requi',1065,0,2,0),
        	(1777,'Autorizacion de Ordenes de compra',0,'../../modulos/xtructur/index.php?modulo=visualizar_pedi',1065,0,4,0),
        	(1778,'Seleccionar concepto a Destajo o Subcontrato',0,'../../modulos/xtructur/index.php?modulo=indicarpu',1059,0,5,0),
        	(1781,'Elaboracion Estimacion Maestros',2335,'../../modulos/xtructur/index.php?modulo=est_destajistas',1069,0,1,0),
        	(1782,'Elaboracion Estimacion Subcontratistas',2336,'../../modulos/xtructur/index.php?modulo=est_subcontratistas',1069,0,3,0),
        	(1783,'Elaboracion Estimacion Proveedores',2337,'../../modulos/xtructur/index.php?modulo=est_proveedores',1069,0,5,0),
        	(1786,'Elaboracion Estimacion Indirectos',2339,'../../modulos/xtructur/index.php?modulo=est_indirectos',1069,0,9,0),
        	(1787,'Elaboracion Estimacion Caja Chica',2340,'../../modulos/xtructur/index.php?modulo=est_cc',1069,0,10,0),
        	(1793,'Cuentas por Pagar',2341,'../../modulos/xtructur/index.php?modulo=remesas',1071,0,1,0),
        	(1794,'Cuentas Pagadas',2341,'../../modulos/xtructur/index.php?modulo=cheques',1071,0,3,0),
        	(1798,'Elaboracion Estimacion Clientes',2338,'../../modulos/xtructur/index.php?modulo=est_cliente',1069,0,7,0),
        	(1800,'Reportes',0,NULL,1057,0,1,0),
        	(1801,'Visualizar entradas de almacen',0,'../../modulos/xtructur/index.php?modulo=visualizar_entradas',1065,0,7,0),
        	(1802,'Visualizar salidas de almacen',0,'../../modulos/xtructur/index.php?modulo=visualizar_salidas',1065,0,8,0),
        	(1803,'Tipo de cuenta',2183,'../catalog/gestor.php?idestructura=281&ticket=testing',1050,0,1,0),
        	(1804,'Cuentas Bancarias',2183,'../catalog/gestor.php?idestructura=280&ticket=testing',1050,0,2,0),
        	(1805,'Cheques',2187,'../../modulos/bancos/index.php?c=Ingresos&f=filtro&fun=vercheque',1033,0,1,0),
        	(1807,'Conciliacion Bancaria',0,NULL,1033,0,5,0),
        	(1808,'Importar estado de cuenta bancario',1807,'../../modulos/bancos/index.php?c=importarEstadoCuenta&f=verImport',1033,0,1,0),
        	(1820,'Flujo de Efectivo',2185,'../../modulos/bancos/index.php?c=Flujo&f=verflujo',1049,0,0,0),
        	(1822,'Ingresos por Depositar',2186,'../../modulos/bancos/index.php?c=Ingresos&f=filtro&fun=verIngresoNodep',1033,0,1,0),
        	(1826,'Sucursal Bancaria',2183,'../catalog/gestor.php?idestructura=283&ticket=testing',1050,0,3,0),
        	(1828,'Documentos',0,NULL,1033,0,4,0),
        	(1829,'Egresos',2187,'../../modulos/bancos/index.php?c=Ingresos&f=filtro&fun=verEgresos',1033,0,0,0),
        	(1830,'Ingresos',2186,'../../modulos/bancos/index.php?c=Ingresos&f=filtro&fun=verIngreso',1033,0,0,0),
        	(1831,'Control de Asistencia Tec-Admon',0,'../../modulos/xtructur/index.php?modulo=nom_tom_oce',1068,0,4,0),
        	(1832,'Elaboracion Nomina Tec-Admon Oficina Central',0,'../../modulos/xtructur/index.php?modulo=nom_ocen',1068,0,5,0),
        	(1834,'Elaboracion Nomina Tec-Admon Oficina Campo',0,'../../modulos/xtructur/index.php?modulo=nom_oce',1068,0,7,0),
        	(1835,'Avance de obra',1800,'../../modulos/xtructur/index.php?modulo=unovsuno',1057,0,3,0),
        	(1836,'XMLs Polizas',1643,'../../modulos/cont/index.php?c=Reports&f=polizasXML',18,0,2,0),
        	(1840,'Ingresos vs Egresos',1800,'../../modulos/xtructur/index.php?modulo=ingresos_egresos',1057,0,4,0),
        	(1844,'Conceptos Documentos  Pago/Cobro',2183,'../catalog/gestor.php?idestructura=288&ticket=testing',1050,0,3,0),
        	(1846,'Control de indirectos',1800,'../../modulos/xtructur/index.php?modulo=control_indirectos',1057,NULL,1,NULL),
        	(1866,'Anticipo de Gastos',1665,'../../modulos/cont/index.php?c=CaptPolizas&f=anticipo',18,0,3,0),
        	(1867,'Comprobacion Anticipo Gastos',1665,'../../modulos/cont/index.php?c=CaptPolizas&f=comprobacion',18,0,4,0),
        	(1874,'Pólizas de Provisión multiple',1665,'../../modulos/cont/index.php?c=CaptPolizas&f=filtroAutomaticas&t=provision',18,0,0,0),
        	(1879,'XMLs Folios',1643,'../../modulos/cont/index.php?c=Reports&f=foliosXML',18,0,2,0),
        	(1881,'Subir Comprobantes',0,'../../modulos/cont/index.php?c=CaptPolizas&f=subecomprobantes',18,0,20,0),
        	(1883,'Costo acumulado a detalle',1800,'../../modulos/xtructur/index.php?modulo=acumulado_detalle',1057,0,5,0),
        	(1884,'XMLs Auxiliar de Cuentas',1643,'../../modulos/cont/index.php?c=Reports&f=auxCuentasXML',18,0,2,0),
        	(1886,'Catalogo partidas',0,'../../modulos/xtructur/index.php?modulo=cat_partidas',1059,0,2,0),
        	(1888,'Provisión Detallada',1665,'../../modulos/cont/index.php?c=CaptPolizas&f=filtroAutomaticas&t=provisiond&detalle=1',18,0,0,0),
        	(1897,'Provision Recibo Nomina',1665,'../../modulos/cont/index.php?c=Nomina&f=viewNomina',18,0,1,0),
        	(1899,'Catalogo especialidades',0,'../../modulos/xtructur/index.php?modulo=cat_especialidades',1059,0,1,0),
        	(1900,'Pago Recibo Nomina',1665,'../../modulos/cont/index.php?c=Nomina&f=viewPagoNomina',18,0,2,0),
        	(1905,'Retenciones y fondos de garantia',1800,'../../modulos/xtructur/index.php?modulo=retenciones',1057,NULL,2,NULL),
        	(1907,'Realizar Conciliacion',1807,'../../modulos/cont/index.php?c=conciliacionAcontia&f=verCaratulaConciliacion',1033,0,1,0),
        	(1911,'Reporte de Estado de Cuenta',1807,'../../modulos/cont/index.php?c=conciliacionAcontia&f=estadocuentafiltro',1033,0,2,0),
        	(1915,'Reporte Conciliacion',1807,'../../modulos/cont/index.php?c=conciliacionAcontia&f=verReporteConciliacion',1033,0,2,0),
        	(1922,'Presupuestos',0,'',18,0,8,0),
        	(1923,'Depositos',2186,'../../modulos/bancos/index.php?c=Ingresos&f=filtro&fun=verDeposito',1033,0,2,0),
        	(1924,'Crear/Ver Presupuesto',1922,'../../modulos/cont/index.php?c=Presupuesto&f=creaPresupuesto',18,0,1,0),
        	(1926,'Tipos de Documento',2183,'../catalog/gestor.php?idestructura=309&ticket=testing',1050,0,2,0),
        	(1927,'Usuarios obras',0,'../../modulos/xtructur/index.php?modulo=usuarios_obras',1058,0,3,0),
        	(1932,'Configuracion',2183,'../../modulos/bancos/index.php?c=Configuracion&f=configuracion',1050,0,0,0),
        	(1960,'Configuración Avanzada',0,'../../modulos/appministra/index.php?c=configuracion&f=general&p=0',1050,0,7,0),
        	(1961,'Clasificadores Generales',0,'../../modulos/appministra/index.php?c=configuracion&f=clasificadores',1050,0,9,0),
        	(1974,'Requisición',0,'../../modulos/appministra/index.php?c=compras&f=requisiciones',1044,0,1,0),
        	(1975,'Recepción',0,'../../modulos/appministra/index.php?c=compras&f=recepcion',1044,0,3,0),
        	(1980,'Orden de Compra',0,'../../modulos/appministra/index.php?c=compras&f=ordenes',1044,0,2,0),
        	(1985,'Categorías de Productos',0,'../../modulos/appministra/index.php?c=configuracion&f=clasificadoresProd',1050,0,10,0),
        	(1986,'Características de Productos',0,'../../modulos/appministra/index.php?c=configuracion&f=caracteristicasProd',1050,0,11,0),
        	(1987,'Tipos de Crédito',0,'../../modulos/appministra/index.php?c=configuracion&f=credito',1050,0,12,0),
        	(1988,'Listas de Precios',0,'../../modulos/appministra/index.php?c=configuracion&f=listas_precio',1050,0,13,0),
        	(1990,'Unidades de Medida',2329,'../../modulos/appministra/index.php?c=configuracion&f=medida',1048,0,5,0),
        	(1993,'Impuestos',2329,'../../modulos/appministra/index.php?c=configuracion&f=impuestos',1048,0,6,0),
        	(2010,'Movimientos de Inventarios',0,'../../modulos/appministra/index.php?c=inventarios&f=entradas',1046,0,1,0),
        	(2034,'Nuevo Producto',2329,'../../modulos/pos/index.php?c=producto&f=indexGridProductos',1048,0,1,0),
        	(2039,'Mermas',0,'../../modulos/pos/index.php?c=inventario&f=indexGridMermas',1046,0,4,0),
        	(2040,'Posicion Bancaria Diaria',2185,'../../modulos/bancos/index.php?c=Flujo&f=verposicion',1049,0,1,0),
        	(2049,'Clientes',0,'../../modulos/pos/index.php?c=cliente&f=indexGrid',1048,0,2,0),
        	(2078,'Calendario Financiero',2185,'../../modulos/bancos/index.php?c=Calendario&f=vercalendario',1049,0,2,0),
        	(2081,'Cuentas por Pagar',0,'../../modulos/appministra/index.php?c=cuentas&f=lista&t=1',1047,0,4,0),
        	(2085,'Cuentas por Pagar',0,NULL,1049,0,5,0),
        	(2086,'Resumen de Saldos por Proveedor',2085,'../../modulos/appministra/index.php?c=Reportes_Cuentas&f=resumen_saldos',1049,0,1,0),
        	(2094,'Abonos y Retiros',2331,'../../modulos/pos/index.php?c=reportear&f=reportear',1049,0,2,0),
        	(2095,'Cuentas por Cobrar',0,NULL,1049,0,5,0),
        	(2097,'Cuentas por Cobrar',0,'../../modulos/appministra/index.php?c=cuentas&f=lista&t=0',1047,0,3,0),
        	(2098,'Resumen de Saldos por Cliente',2095,'../../modulos/appministra/index.php?c=Reportes_Cuentas&f=resumen_saldos_cobrar',1049,0,1,0),
        	(2102,'Inventarios',0,NULL,1049,0,4,0),
        	(2103,'Kardex',2102,'../../modulos/appministra/index.php?c=reportes&f=kardex',1049,0,3,0),
        	(2104,'Existencias',2102,'../../modulos/appministra/index.php?c=reportes&f=existencias',1049,0,2,0),
        	(2105,'Catálogos de Productos y Servicios',2102,'../../modulos/appministra/index.php?c=reportes&f=cataproductos',1049,0,5,0),
        	(2106,'Venta Global',2332,'../../modulos/pos/index.php?c=caja&f=ventasGrid',1049,0,2,0),
        	(2110,'Compras',0,'',1049,0,3,0),
        	(2115,'Corte de Caja',2331,'../../modulos/pos/index.php?c=caja&f=cortesGrid',1049,0,1,0),
        	(2119,'Etiquetas',0,'../../modulos/pos/index.php?c=inventario&f=indexEtiquetado',1046,0,5,0),
        	(2120,'Auxiliar por Beneficiario / Pagador',2185,'../../modulos/bancos/index.php?c=Flujo&f=verauxiliar',1049,0,3,0),
        	(2124,'Inventario Actual',2102,'../../modulos/appministra/index.php?c=reportes&f=inventarioactual',1049,0,1,0),
        	(2125,'Compras por Proveedor y por Producto',2110,'../../modulos/appministra/index.php?c=Reportes_Compras&f=prov_prod',1049,0,1,0),
        	(2136,'Almacenes y Sucursales',0,'../../modulos/appministra/index.php?c=sucursal&f=sucursalesArbol',1050,0,14,0),
        	(2137,'Mi Organización',2147,'../catalog/gestor.php?idestructura=1&ticket=testing',1050,0,2,0),
        	(2138,'Bienvenido',0,'../../modulos/inicio/index.php',1050,0,1,-1),
        	(2139,'Perfiles de Usuario',2147,'../../modulos/perfiles/index.php',1050,0,3,0),
        	(2140,'Administración Usuarios',2147,'../catalog/gestor.php?idestructura=47&ticket=testing',1050,0,4,0),
        	(2141,'Color de interfaz',0,'../../modulos/styleselector/index.php',1050,0,15,0),
        	(2142,'Importar Clientes',2330,'../../modulos/punto_venta/views/clientes/importar_clientes.php',1048,0,3,0),
        	(2143,'Importar Proveedores',2330,'../../modulos/punto_venta/views/proveedores/importar_proveedores.php',1048,0,4,0),
        	(2144,'Beneficiarios/Proveedores ',0,'../../modulos/punto_venta/catalogos/proveedor.php',1048,0,5,0),
        	(2147,'General',0,'',1050,0,2,0),
        	(2148,'Facturacion',0,'',1050,0,3,0),
        	(2149,'Datos de Facturacion',2148,'../catalog/gestor.php?idestructura=234&ticket=testing',1050,0,1,0),
        	(2150,'Serie y Folio',2148,'../catalog/gestor.php?idestructura=221&ticket=testing',1050,0,2,0),
        	(2151,'Clasificador Ingresos-Egresos',2183,'../catalog/gestor.php?idestructura=285&ticket=testing',1050,0,5,0),
        	(2155,'Empleados',0,'../../modulos/nominas/index.php?c=Catalogos&f=listaEmpleados',1048,0,4,0),
        	(2156,'Comandera',0,'../../modulos/restaurantes/ajax.php?c=comandas&f=menuMesas',1051,0,1,0),
        	(2157,'Pedidos',0,'../../modulos/restaurantes/ajax.php?c=pedidosActivos&f=zona',1051,0,2,0),
        	(2158,'Reservaciones',0,'../../modulos/restaurantes/ajax.php?c=reservaciones&f=mapa_reservaciones',1052,0,1,0),
        	(2159,'Recetas',0,'../../modulos/restaurantes/ajax.php?c=recetas&f=vista_recetas',1053,0,1,0),
        	(2160,'Foodware',0,NULL,1049,0,1,0),
        	(2161,'Estatus comanda',2160,'../../modulos/restaurantes/ajax.php?c=comandas&f=vista_estatus_comandas',1049,0,1,0),
        	(2162,'Actividad empleado',2160,'../../modulos/restaurantes/ajax.php?c=comandas&f=vista_actividad',1049,0,2,0),
        	(2163,'Promedio por comensal',2160,'../../modulos/restaurantes/ajax.php?c=comandas&f=vista_promedio_comensal',1049,0,3,0),
        	(2164,'Comensales por mesa',2160,'../../modulos/restaurantes/ajax.php?c=comandas&f=vista_comensales',1049,0,4,0),
        	(2165,'Zonas de mayor afluencia',2160,'../../modulos/restaurantes/ajax.php?c=comandas&f=vista_zonas',1049,0,5,0),
        	(2166,'Ocupacion',2160,'../../modulos/restaurantes/ajax.php?c=comandas&f=vista_ocupacion',1049,0,6,0),
        	(2167,'Reservaciones',2160,'../../modulos/restaurantes/ajax.php?c=reservaciones&f=vista_reservaciones',1049,0,7,0),
        	(2168,'Seguridad',2177,'../../modulos/restaurantes/ajax.php?c=configuracion&f=vista_seguridad',1050,0,1,0),
        	(2169,'Ajustes',2177,'../../modulos/restaurantes/ajax.php?c=pedidosActivos&f=configuraprodpropina',1050,0,2,0),
        	(2170,'Platillos',2177,'../../modulos/restaurantes/ajax.php?c=configuracion&f=vista_productos',1050,0,3,0),
        	(2173,'Asignaciones',2177,'../../modulos/restaurantes/ajax.php?c=comandas&f=vista_asignaciones',1050,0,6,0),
        	(2175,'Pedidos y Cotizaciones',0,'../../modulos/pos_pedidos/index.php?c=pedido&f=imprimeGridP',1073,0,1,0),
        	(2177,'Foodware',0,NULL,1050,0,2,0),
        	(2178,'Retenciones e Informacion de Pagos',2185,'../../modulos/bancos/index.php?c=Cheques&f=verRetencionPendiente',1049,0,4,0),
        	(2180,'Ticket no Facturados',0,'../../modulos/pos/index.php?c=caja&f=pendienteFacturar',1043,0,1,0),
        	(2181,'Lista de Facturas',0,'../../modulos/pos/index.php?c=caja&f=gridFacturas',1043,0,2,0),
        	(2183,'Bancos',0,NULL,1050,0,4,0),
        	(2185,'Bancos',0,NULL,1049,0,7,0),
        	(2186,'Ingresos',1828,'',1033,0,0,0),
        	(2187,'Egresos',1828,'',1033,0,1,0),
        	(2188,'Devoluciones de Compras',2110,'../../modulos/appministra/index.php?c=reportes&f=devolucionespro',1049,0,2,0),
        	(2190,'Utilidad Bruta',2332,'../../modulos/restaurantes/ajax.php?c=comandas&f=vista_utilidad',1049,0,4,0),
        	(2194,'Promociones',0,'../../modulos/restaurantes/ajax.php?c=configuracion&f=vista_promociones',1050,0,1,0),
        	(2195,'Análisis de Ventas',2332,'../../modulos/pos/index.php?c=reporte&f=indexReportes',1049,0,3,0),
        	(2197,'Ingeniería de menú',2160,'../../modulos/restaurantes/ajax.php?c=comandas&f=vista_producto_detalle',1049,0,6,0),
        	(2198,'Menu Digital',2177,'../../modulos/restaurantes/ajax.php?c=configuracion&f=vista_menu',1050,0,7,0),
        	(2200,'Traspaso de Inventarios',0,'../../modulos/appministra/index.php?c=inventarios&f=sol_traspasos',1046,0,2,0),
        	(2201,'Recepción de Traspasos',0,'../../modulos/appministra/index.php?c=inventarios&f=recepciones',1046,0,3,0),
        	(2202,'Preparacion',0,'../../modulos/restaurantes/ajax.php?c=recetas&f=vista_preparacion',1053,0,2,0),
        	(2203,'kits',0,'../../modulos/restaurantes/ajax.php?c=configuracion&f=vista_kits',1050,0,1,0),
        	(2205,'Repartidores',0,'../../modulos/restaurantes/ajax.php?c=repartidores&f=pedidosRep',1051,0,4,0),
        	(2206,'Repartidores',2160,'../../modulos/restaurantes/ajax.php?c=repartidores&f=reporteRep',1049,0,8,0),
        	(2207,'Autorizacion Estimacion Clientes',2338,'../../modulos/xtructur/index.php?modulo=est_cliente_bit',1069,0,8,0),
        	(2214,'Autorizacion Estimacion Maestros',2335,'../../modulos/xtructur/index.php?modulo=est_destajistas_bit',1069,0,2,0),
        	(2215,'Autorizacion Estimacion Subcontratistas',2336,'../../modulos/xtructur/index.php?modulo=est_subcontratistas_bit',1069,0,4,0),
        	(2216,'Autorizacion Estimacion Proveedores',2337,'../../modulos/xtructur/index.php?modulo=est_proveedores_bit',1069,0,6,0),
        	(2222,'Combos',0,'../../modulos/restaurantes/ajax.php?c=configuracion&f=vista_combos',1050,0,1,0),
        	(2229,'TPL Polizas',2366,'../../modulos/appministra/index.php?c=configuracion&f=polizas&p=0',1050,0,1,0),
        	(2232,'Implementación Inicial ',0,'../../modulos/scala/index.php?c=implementa&f=implementa',1050,-1,3,0),
        	(2238,'Inventario',0,'../../modulos/xtructur/index.php?modulo=inventarios',1066,0,1,0),
        	(2240,'Tarjeta de Regalo',0,'../../modulos/pos/index.php?c=caja&f=gridTarjetasRegalo',1042,0,4,0),
        	(2242,'Cargar programa de obra',0,'../../modulos/xtructur/index.php?modulo=programa_obra',1067,0,1,0),
        	(2247,'Polizas Manuales',2366,'../../modulos/appministra/index.php?c=configuracion&f=polizas_manuales',1050,0,1,0),
        	(2263,'Configuracion inicial',0,'../../modulos/xtructur/index.php?modulo=config',1058,0,4,0),
        	(2265,'Gantt',0,'../../modulos/xtructur/index.php?modulo=gantt',1067,0,2,0),
        	(2266,'Tablero Solicitudes Pendientes',0,'../../modulos/xtructur/index.php?modulo=tablero',1058,0,5,0),
        	(2268,'Autorizacion Nomina Obreros',0,'../../modulos/xtructur/index.php?modulo=prenomina_auth',1068,0,3,0),
        	(2269,'Propinas',2160,'../../modulos/restaurantes/ajax.php?c=comandas&f=vista_propinas',1049,0,9,0),
        	(2272,'Autorizar cuentas por pagar',2341,'../../modulos/xtructur/index.php?modulo=aut_cuentaspp',1071,0,2,0),
        	(2276,'Autorizacion prenomina oficina central',0,'../../modulos/xtructur/index.php?modulo=prenom_ocen',1068,0,6,0),
        	(2278,'Autorizacion prenomina campo',0,'../../modulos/xtructur/index.php?modulo=prenom_oce',1068,0,8,0),
        	(2279,'Autorizacion estimacion caja chica',2340,'../../modulos/xtructur/index.php?modulo=viz_cc',1069,0,11,0),
        	(2280,'Punto de Venta',2385,'../../modulos/pos/index.php?c=config_caja&f=index',1050,0,1,0),
        	(2281,'Autorizacion estimacion indirectos',2339,'../../modulos/xtructur/index.php?modulo=viz_ind',1069,0,12,0),
        	(2285,'Editar mapa de mesas',2177,'../../modulos/restaurantes/ajax.php?c=comandas&f=editar_mapa_mesas',1050,0,8,0),
        	(2287,'Historial de movimientos - Nóminas',2293,'../../modulos/xtructur/index.php?modulo=historialnom',1057,0,2,0),
        	(2288,'Historial de movimientos - Compras',2293,'../../modulos/xtructur/index.php?modulo=historialcompras',1057,0,1,0),
        	(2289,'Complementos',0,'../../modulos/restaurantes/ajax.php?c=configuracion&f=vista_complementos',1050,0,2,0),
        	(2290,'Historial de movimientos - Estimaciones',2293,'../../modulos/xtructur/index.php?modulo=historialest',1057,0,3,0),
        	(2291,'Historial de movimientos - Pasivos',2293,'../../modulos/xtructur/index.php?modulo=historialpas',1057,0,5,0),
        	(2292,'Movimiento de Polizas y Facturas',2369,'../../modulos/cont/index.php?c=Reports&f=movpolizas',1049,0,2,0),
        	(2293,'Historial',0,NULL,1057,0,2,0),
        	(2295,'Notificaciones',0,'../../modulos/xtructur/index.php?modulo=notilog',1058,0,6,0),
        	(2299,'Matrices',0,'../../modulos/xtructur/index.php?modulo=recetas',1059,0,12,0),
        	(2300,'Monitorear Pedidos',0,'../../modulos/restaurantes/ajax.php?c=pedidosActivos&f=monitorear_pedidos',1051,0,3,0),
        	(2301,'Proveedores',0,'../../modulos/pos/index.php?c=proveedores&f=indexGrid',1048,0,3,0),
        	(2302,'Autorizacion de Extraordinarios',0,'../../modulos/xtructur/index.php?modulo=extra_aut',1070,0,4,0),
        	(2304,'Autorizacion de No cobrables',0,'../../modulos/xtructur/index.php?modulo=nocob_aut',1070,0,6,0),
        	(2305,'Autorizacion de Adicionales',0,'../../modulos/xtructur/index.php?modulo=adic_aut',1070,0,5,0),
        	(2307,'Bitacora de Usuarios',0,'../../modulos/cont/index.php?c=Report_User&f=reportusers',1050,0,1001,0),
        	(2308,'Importar Productos',2330,'../../modulos/pos/index.php?c=producto&f=indexImportarProductos',1048,0,1,0),
        	(2309,'Historial de movimientos - Ordenes de cambio',2293,'../../modulos/xtructur/index.php?modulo=historialpres',1057,0,4,0),
        	(2319,'Control de insumos',2102,'../../modulos/restaurantes/ajax.php?c=recetas&f=vista_control_insumos',1049,0,2,0),
        	(2320,'Traspaso entre obras',0,'../../modulos/xtructur/index.php?modulo=traspaso',1066,0,2,0),
        	(2321,'Salida de traspasos',0,'../../modulos/xtructur/index.php?modulo=tsalida',1066,0,3,0),
        	(2322,'Entrada de traspasos',0,'../../modulos/xtructur/index.php?modulo=tentrada',1066,0,4,0),
        	(2324,'Cuentas por Cobrar',2342,'../../modulos/xtructur/index.php?modulo=cobros',1071,0,1,0),
        	(2325,'Cuentas Cobradas',2342,'../../modulos/xtructur/index.php?modulo=cobrado',1071,0,2,0),
        	(2326,'Importar Proveedores / Subcontratistas',0,'../../modulos/xtructur/index.php?modulo=imp_proveedores',1064,0,8,0),
        	(2327,'Importar subcontratistas',0,'../../modulos/xtructur/index.php?modulo=imp_subcontratistas',1064,0,5,0),
        	(2328,'Punto de Reorden ',2102,'../../modulos/pos/index.php?c=reorden&f=reorden',1049,0,4,0),
        	(2329,'Productos',0,NULL,1048,0,1,0),
        	(2330,'Importaciones Masivas',0,NULL,1048,0,5,0),
        	(2331,'Punto de Venta',0,NULL,1049,0,1,0),
        	(2332,'Ventas',0,NULL,1049,0,2,0),
        	(2335,'Maestros',0,NULL,1069,0,1,0),
        	(2336,'Subcontratistas',0,NULL,1069,0,2,0),
        	(2337,'Proveedores',0,NULL,1069,0,3,0),
        	(2338,'Clientes',0,NULL,1069,0,4,0),
        	(2339,'Equipo, Maquinaria e Indirectos',0,NULL,1069,0,5,0),
        	(2340,'Caja Chica',0,NULL,1069,0,6,0),
        	(2341,'Cuentas por Pagar',0,NULL,1071,0,1,0),
        	(2342,'Cuentas por Cobrar',0,NULL,1071,0,2,0),
        	(2348,'Configuración de Correo',2177,'../../modulos/restaurantes/ajax.php?c=configuracion&f=configuracion_correo',1050,0,9,0),
        	(2349,'Gestionar Correo',2177,'../../modulos/restaurantes/ajax.php?c=configuracion&f=gestionar_correo',1050,0,10,0),
        	(2355,'Abonos y Retiros',2354,'../../modulos/pos/index.php?c=reportear&f=reportear',1049,0,1,0),
        	(2357,'Caja',0,'../../modulos/pos/index.php?c=caja&f=indexCaja2',1042,0,1,0),
        	(2358,'Facturación',0,'../../modulos/appministra/index.php?c=facturacion&f=facturacion',1050,0,6,0),
        	(2359,'Consumo',2160,'../../modulos/restaurantes/ajax.php?c=comandas&f=vista_reporte_consumo',1049,0,10,0),
        	(2361,'Mapa repartidores',0,'../../modulos/restaurantes/ajax.php?c=configuracion&f=vista_mapa_repartidores',1050,-1,25,0),
        	(2364,'Ventas Diarias',2332,'../../modulos/appministra/index.php?c=reportes&f=ventasdiarias',1049,0,1,0),
        	(2365,'Fondo de Interfaz',2147,'../../modulos/xtructur/index.php?modulo=interfaz',1050,0,25,0),
        	(2366,'Interfaz',0,'',1050,0,8,0),
        	(2369,'Reportes Generales',0,NULL,1049,0,0,0),
        	(2370,'Almacén Digital',0,NULL,18,0,0,0),
        	(2371,'Comisiones',2332,'../../modulos/pos/index.php?c=caja&f=comisionesGrid',1049,0,5,0),
        	(2372,'Acontia',0,'',1050,0,4,0),
        	(2376,'Impresion QR',2177,'../../modulos/restaurantes/ajax.php?c=configuracion&f=vista_impresion_qr',1050,0,12,0),
        	(2377,'Configuracion de Mesa Inteligente',2177,'../../modulos/restaurantes/ajax.php?c=configuracion&f=vista_mesa_inteligente',1050,0,11,0),
        	(2382,'Series, Lotes, Pedimentos y Caducos',2102,'../../modulos/appministra/index.php?c=inventarios&f=reporte_slp&vista=2',1049,NULL,6,NULL),
        	(2384,'Configuración General',0,'../../modulos/appministra/index.php?c=configuraciong&f=configGeneral',1050,0,5,0),
        	(2385,'Configuración Ventas',0,NULL,1050,0,4,0),
        	(2387,'Notificaciones ',2147,'../../modulos/cont/index.php?c=edu&f=ver_notificaciones',1050,0,4,0),
        	(2390,'Productos - Sucursal',2329,'../../modulos/pos/index.php?c=producto&f=indexProdSuc',1048,0,4,0),
        	(2398,'Avance de Obra Volumen',0,'../../modulos/xtructur/index.php?modulo=Avance',1067,0,40,0),
        	(2402,'Visor de Ordenes de Compra',0,'../../modulos/xtructur/index.php?modulo=visor',1065,0,50,0),
        	(2411,'Visor de Aprobadas',2337,'../../modulos/xtructur/index.php?modulo=visorp',1069,0,7,0),
        	(2412,'Visor de Requisiciones',0,'../../modulos/xtructur/index.php?modulo=visorr',1065,0,50,0),
        	(2413,'Comprobacion de Tickets de Anticipo',1665,'../../modulos/cont/index.php?c=CaptPolizas&f=viewComprobanteTicketAnticipo',18,0,3,0),
        	(2414,'Almacen Digital Nuevo',1643,'../../modulos/cont/index.php?c=almacen&f=almacenXml',18,0,2,0),
        	(2416,'Movimiento de Polizas y Facturas',1664,'../../modulos/cont/index.php?c=Reports&f=movpolizas',18,0,3,0),
        	(2417,'Utilidad ',2332,'../../modulos/appministra/index.php?c=reportes&f=vista_utilidad',1049,0,5,0),
        	(2419,'Monedero Producto',2329,'../../modulos/pos/index.php?c=producto&f=indexmonedero',1048,NULL,8,NULL),
        	(2421,'Control de registros para las 7 dimensiones',2372,'../../modulos/cont/index.php?c=captpolizas&f=agregar_registro',1050,0,0,0),
        	(2423,'Ajustes de inventario',0,'../../modulos/pos/index.php?c=ajustesInventario&f=indexGrid',1046,0,10,0),
        	(2424,'Claves del SAT',2329,'../../modulos/pos/index.php?c=producto&f=indexSat',1048,0,7,0),
        	(2432,'Generacion de Polizas',2370,'../../modulos/cont/index.php?c=almacen&f=almacenXmlPolizas',18,0,2,0),
        	(2433,'TPL Generacion de Polizas',2370,'../../modulos/cont/index.php?c=almacen&f=polizas',18,0,3,0);";
          mysqli_query($objCon, $sqlINSERT);
          echo "Insert Into accelog_menus ".$objCustomer['nombre_db']."<br>";


        $sqlINSERT="Truncate ".$objCustomer['nombre_db'].".accelog_perfiles_me;";
        mysqli_query($objCon, $sqlINSERT);
        echo "Truncate accelog_perfiles_me".$objCustomer['nombre_db']."<br>";

        $sqlINSERT="INSERT INTO ".$objCustomer['nombre_db'].".accelog_perfiles_me (`idperfil`, `idmenu`)
        VALUES
        	(1,0),
        	(1,1),
        	(1,2),
        	(1,3),
        	(1,4),
        	(1,5),
        	(1,6),
        	(1,7),
        	(1,8),
        	(1,9),
        	(1,11),
        	(1,12),
        	(1,13),
        	(1,14),
        	(1,40),
        	(2,142),
        	(2,143),
        	(2,145),
        	(2,1118),
        	(2,1119),
        	(2,1120),
        	(2,1122),
        	(2,1124),
        	(1,1126),
        	(2,1162),
        	(2,1182),
        	(2,1588),
        	(2,1589),
        	(2,1590),
        	(2,1599),
        	(2,1603),
        	(2,1604),
        	(2,1606),
        	(2,1607),
        	(2,1608),
        	(2,1609),
        	(2,1610),
        	(2,1614),
        	(2,1617),
        	(2,1621),
        	(2,1632),
        	(2,1633),
        	(2,1639),
        	(2,1641),
        	(2,1642),
        	(2,1643),
        	(2,1645),
        	(2,1646),
        	(2,1647),
        	(2,1648),
        	(2,1649),
        	(2,1653),
        	(2,1654),
        	(2,1656),
        	(2,1663),
        	(2,1664),
        	(2,1665),
        	(2,1666),
        	(2,1667),
        	(2,1668),
        	(2,1675),
        	(2,1677),
        	(2,1694),
        	(2,1695),
        	(2,1696),
        	(2,1702),
        	(2,1704),
        	(2,1714),
        	(2,1803),
        	(2,1804),
        	(2,1805),
        	(2,1807),
        	(2,1808),
        	(2,1820),
        	(2,1822),
        	(2,1826),
        	(2,1828),
        	(2,1829),
        	(2,1830),
        	(2,1836),
        	(2,1844),
        	(2,1866),
        	(2,1867),
        	(2,1874),
        	(2,1879),
        	(2,1881),
        	(3,1881),
        	(2,1884),
        	(2,1888),
        	(2,1897),
        	(2,1900),
        	(2,1907),
        	(2,1911),
        	(2,1915),
        	(2,1922),
        	(2,1923),
        	(2,1924),
        	(2,1926),
        	(2,1932),
        	(2,1960),
        	(2,1961),
        	(2,1974),
        	(2,1975),
        	(2,1980),
        	(2,1985),
        	(2,1986),
        	(2,1987),
        	(2,1988),
        	(2,1990),
        	(2,1993),
        	(2,2010),
        	(2,2034),
        	(2,2039),
        	(2,2040),
        	(2,2049),
        	(2,2078),
        	(2,2081),
        	(2,2085),
        	(2,2086),
        	(2,2094),
        	(2,2095),
        	(2,2097),
        	(2,2098),
        	(2,2102),
        	(2,2103),
        	(2,2104),
        	(2,2105),
        	(2,2106),
        	(2,2110),
        	(2,2115),
        	(2,2119),
        	(2,2120),
        	(2,2124),
        	(2,2125),
        	(2,2136),
        	(2,2138),
        	(2,2141),
        	(2,2142),
        	(2,2143),
        	(2,2147),
        	(2,2148),
        	(2,2149),
        	(2,2150),
        	(2,2151),
        	(2,2155),
        	(2,2175),
        	(2,2178),
        	(2,2180),
        	(2,2181),
        	(2,2183),
        	(2,2185),
        	(2,2186),
        	(2,2187),
        	(2,2188),
        	(3,2188),
        	(2,2190),
        	(2,2195),
        	(2,2200),
        	(2,2201),
        	(3,2207),
        	(3,2214),
        	(3,2215),
        	(3,2216),
        	(2,2229),
        	(3,2229),
        	(2,2232),
        	(3,2238),
        	(2,2240),
        	(3,2242),
        	(2,2247),
        	(5,2247),
        	(3,2278),
        	(2,2280),
        	(2,2292),
        	(2,2301),
        	(5,2301),
        	(2,2307),
        	(3,2307),
        	(2,2308),
        	(3,2308),
        	(5,2308),
        	(2,2328),
        	(2,2329),
        	(2,2330),
        	(2,2331),
        	(2,2332),
        	(2,2357),
        	(2,2358),
        	(2,2364),
        	(2,2366),
        	(2,2369),
        	(2,2370),
        	(2,2371),
        	(3,2371),
        	(5,2371),
        	(2,2372),
        	(2,2374),
        	(2,2382),
        	(2,2384),
        	(2,2385),
        	(2,2390),
        	(3,2390),
        	(4,2390),
        	(5,2390),
        	(2,2416),
        	(3,2416),
        	(5,2416),
        	(2,2419),
        	(2,2423),
        	(3,2423),
        	(5,2423),
        	(35,2423),
        	(2,2424),
        	(2,2432),
        	(3,2432),
        	(2,2433),
        	(3,2433);";
          mysqli_query($objCon, $sqlINSERT);
          echo "Insert Into accelog_perfiles_me".$objCustomer['nombre_db']."<br>";
      }

        echo "Proceso Concluido!!!!!!<br>";
        mysqli_close($objCon);
        unset($objCon);


  //echo "Tabla Temporal:<br>$sql_NST_table<br>";
  //echo "Valores:<br>$sql_NS_Values<br>";
?>
