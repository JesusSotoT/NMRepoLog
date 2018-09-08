
  INSERT IGNORE INTO `accelog_menu` (`idmenu`, `nombre`, `idmenupadre`, `url`, `idcategoria`, `icono`, `orden`, `omision`)
VALUES
  (2183, 'Bancos', 0, NULL, 1050, 0, 4, 0),
  (1803, 'Tipo de cuenta', 2183, '../catalog/gestor.php?idestructura=281&ticket=testing', 1050, 0, 1, 0),
  (1844, 'Conceptos Documentos  Pago/Cobro', 2183, '../catalog/gestor.php?idestructura=288&ticket=testing', 1050, 0, 3, 0),
  (1926, 'Tipos de Documento', 2183, '../catalog/gestor.php?idestructura=309&ticket=testing', 1050, 0, 2, 0),
  (1932, 'Configuracion', 2183, '../../modulos/bancos/index.php?c=Configuracion&f=configuracion', 1050, 0, 0, 0),
  (2151, 'Clasificador Ingresos-Egresos', 0, '../catalog/gestor.php?idestructura=285&ticket=testing', 1050, 0, 5, 0),
  (2185, 'Bancos', 0, NULL, 1049, 0, 7, 0),
  (1820, 'Flujo de Efectivo', 2185, '../../modulos/bancos/index.php?c=Flujo&f=verflujo', 1049, 0, 0, 0),
  (2040, 'Posicion Bancaria Diaria', 2185, '../../modulos/bancos/index.php?c=Flujo&f=verposicion', 1049, 0, 1, 0),
  (2078, 'Calendario Financiero', 2185, '../../modulos/bancos/index.php?c=Calendario&f=vercalendario', 1049, 0, 2, 0),
  (2120, 'Auxiliar por Beneficiario / Pagador', 2185, '../../modulos/bancos/index.php?c=Flujo&f=verauxiliar', 1049, 0, 3, 0),
  (2178, 'Retenciones e Informacion de Pagos', 2185, '../../modulos/bancos/index.php?c=Cheques&f=verRetencionPendiente', 1049, 0, 4, 0),
  (2187, 'Egresos', 1828, '', 1033, 0, 1, 0),
  (2186, 'Ingresos', 1828, '', 1033, 0, 0, 0),
  (1923, 'Depositos', 2186, '../../modulos/bancos/index.php?c=Ingresos&f=filtro&fun=verDeposito', 1033, 0, 2, 0),
  (1829, 'Egresos', 2187, '../../modulos/bancos/index.php?c=Ingresos&f=filtro&fun=verEgresos', 1033, 0, 0, 0),
  (1805, 'Cheques', 2187, '../../modulos/bancos/index.php?c=Ingresos&f=filtro&fun=vercheque', 1033, 0, 1, 0),
  (1822, 'Ingresos por Depositar', 2186, '../../modulos/bancos/index.php?c=Ingresos&f=filtro&fun=verIngresoNodep', 1033, 0, 1, 0),
  (1830, 'Ingresos', 2186, '../../modulos/bancos/index.php?c=Ingresos&f=filtro&fun=verIngreso', 1033, 0, 0, 0),
  (1808, 'Importar estado de cuenta bancario', 0, '../../modulos/bancos/index.php?c=importarEstadoCuenta&f=verImport', 1054, 0, 0, 0),
  (1911, 'Reporte de Estado de Cuenta', 0, '../../modulos/cont/index.php?c=conciliacionAcontia&f=estadocuentafiltro', 1054, 0, 1, 0),
  (1907, 'Realizar Conciliacion', 0, '../../modulos/cont/index.php?c=conciliacionAcontia&f=verCaratulaConciliacion', 1054, 0, 2, 0),
  (1915, 'Reporte Conciliacion', 0, '../../modulos/cont/index.php?c=conciliacionAcontia&f=verReporteConciliacion', 1054, 0, 3, 0),
  (1826, 'Sucursal Bancaria', 0, '../catalog/gestor.php?idestructura=283&ticket=testing', 1033, 0, 3, 0),
  (1804, 'Cuentas Bancarias', 0, '../catalog/gestor.php?idestructura=280&ticket=testing', 1033, 0, 2, 0),
  (1828, 'Documentos', 0, NULL, 1033, 0, 4, 0),
  (2147, 'General', 0, '', 1050, 0, 2, 0),
  (2138, 'Bienvenido', 2147, '../../modulos/inicio/index.php', 1050, 0, 1, 0),
  (2137, 'Mi Organización', 2147, '../catalog/gestor.php?idestructura=1&ticket=testing', 1050, 0, 2, 0),
  (2139, 'Perfiles de Usuario', 2147, '../../modulos/perfiles/index.php', 1050, 0, 3, 0),
  (2140, 'Administración Usuarios', 2147, '../catalog/gestor.php?idestructura=47&ticket=testing', 1050, 0, 4, 0),
  (2141, 'Color de Interfaz', 2147, '../../modulos/styleselector/index.php', 1050, 0, 5, 0),
  (1706, 'Bancos de Beneficiarios/Proveedores ', 0, '../catalog/gestor.php?idestructura=275&ticket=testing', 1023, 0, 2, 0);
  
INSERT IGNORE INTO `accelog_perfiles_me` (`idperfil`, `idmenu`)
VALUES
  (2,1706),(2, 1932),(2, 1803),(2, 1841),(2, 1804),(2,1844),(3,1844),(2,1926),(2,1826),(2,1828),(2,1830),(2,1822),(2,1923),(2,1829),(2,1824),(2,1805),(2,1805),(2,1808),(2,1820),(2,2040),(2,2078),(2,2185),(2,2186),(2,2187),
  (2, 2183),(2, 2178),(3, 2178),(2, 2120),(3, 2120),(2,1911),(3,1911),(2,1907),(3,1907),(2,1915),(3,1915),(2,2151),(3,2151);
