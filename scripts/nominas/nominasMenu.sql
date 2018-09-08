INSERT IGNORE INTO `accelog_menu` (`idmenu`, `nombre`, `idmenupadre`, `url`, `idcategoria`, `icono`, `orden`, `omision`)
VALUES
  (2208, 'Catalogos', 0, NULL, 1055, 0, 1, 0),
  (2209, 'Empleados Nominas', 2208, '../../modulos/nominas/index.php?c=Catalogos&f=listaEmpleados', 1055, 0, 5, 0),
  (2210, 'Registro Patronal', 2208, NULL, 1055, 0, 1, 0),
  (2213, 'Periodo', 2208, NULL, 1055, 0, 4, 0),
  (2218, 'Departamento', 2208, '../catalog/gestor.php?idestructura=412&ticket=testing', 1055, 0, 6, 0),
  (2219, 'Puesto', 2208, '../catalog/gestor.php?idestructura=413&ticket=testing', 1055, 0, 7, 0),
  (2220, 'Tipos de Periodos', 2208, '../../modulos/nominas/index.php?c=Catalogos&f=listaTiposperiodos', 1055, 0, 3, 0),
  (2223, 'Antigüedades', 2234, '../catalog/gestor.php?idestructura=419&ticket=testing', 1055, 0, 1, 0),
  (2224, 'Conceptos', 2208, '../../modulos/nominas/index.php?c=Catalogos&f=listaConceptos', 1055, 0, 5, 0),
  (2226, 'Factor SDI', 2234, '../catalog/gestor.php?idestructura=426&ticket=testing', 1055, 0, 2, 0),
  (2227, 'Registros Patronales', 2208, '../catalog/gestor.php?idestructura=427&ticket=testing', 1055, 0, 1, 0),
  (2228, 'TIMSSRIESGOT', 2234, '../catalog/gestor.php?idestructura=420&ticket=testing', 1055, 0, 6, 0),
  (2230, 'Tipos de acumulados', 2208, '../catalog/gestor.php?idestructura=432&ticket=testing', 1055, 0, 20, 0),
  (2234, 'Tablas de la empresa', 2208, NULL, 1055, 0, 1, 0),
  (2236, 'Finiquito', 2234, '../catalog/gestor.php?idestructura=434&ticket=testing', 1055, 0, 3, 0),
  (2237, 'Salarios Minimos', 2234, '../catalog/gestor.php?idestructura=435&ticket=testing', 1055, 0, 4, 0),
  (2239, 'Infonavit seguimiento vivienda', 2234, '../catalog/gestor.php?idestructura=436&ticket=testing', 1055, 0, 7, 0),
  (2241, 'TVidISRAnual', 2234, '../catalog/gestor.php?idestructura=437&ticket=testing', 1055, 0, 8, 0),
  (2243, 'TVigISRMensual', 2234, '../catalog/gestor.php?idestructura=438&ticket=testing', 1055, 0, 9, 0),
  (2244, 'TVigSubEmpAnual', 2234, '../catalog/gestor.php?idestructura=440&ticket=testing', 1055, 0, 10, 0),
  (2245, 'TVigSubEmpMensual', 2234, '../catalog/gestor.php?idestructura=441&ticket=testing', 1055, 0, 11, 0),
  (2248, 'IMSS Patron', 2251, '../catalog/gestor.php?idestructura=442&ticket=testing', 1055, 0, 1, 0),
  (2249, 'IMSS Trabajador', 2251, '../catalog/gestor.php?idestructura=443&ticket=testing', 1055, 0, 2, 0),
  (2250, 'Topes SGDF', 2251, '../catalog/gestor.php?idestructura=444&ticket=testing', 1055, 0, 3, 0),
  (2251, 'Tablas Globales', 2208, NULL, 1055, 0, 2, 0),
  (2252, 'Tipos de incidencias', 2208, '../catalog/gestor.php?idestructura=445&ticket=testing', 1055, 0, 19, 0),
  (2255, 'Relación Acumulados (Conceptos-Percepciones)', 0, '../catalog/gestor.php?idestructura=448&ticket=testing', 1055, 0, 20, 0),
  (2256, 'Relación Empleados-Conceptos', 0, '../catalog/gestor.php?idestructura=449&ticket=testing', 1055, 0, 20, 0),
  (2257, 'Configuracion', 0, '../../modulos/nominas/index.php?c=Catalogos&f=configuracion', 1055, 0, 0, 0),
  (2260, 'Prenomina', 0, NULL, 1055, 0, 1, 0),
  (2262, 'Periodos', 2208, '../../modulos/nominas/index.php?c=Catalogos&f=periodosview', 1055, 0, 4, 0),
  (2297, 'Sobre - Recibo', 0, '../../modulos/nominas/index.php?c=Sobrerecibo&f=sobrereciboview', 1055, 0, 3, 0),
  (2360, 'Turno', 2208, '../catalog/gestor.php?idestructura=417&ticket=testing', 1055, 0, 3, 0),
  (2394, 'Procesos', 0, NULL, 1055, 0, 8, 0),
  (2420, 'Autorizacion de Nomina', 2394, '../../modulos/nominas/index.php?c=Prenomina&f=viewAutorizaNomina', 1055, 0, 1, 0),
  (2395, 'Calculo del Aguinaldo', 2394, '../../modulos/nominas/index.php?c=Prenomina&f=veraguinaldo', 1055, 0, 2, 0),
  (2397, 'Finiquito', 2394, '../../modulos/nominas/index.php?c=Prenomina&f=verfiniquito', 1055, 0, 3, 0),
  (2273, 'Calculo PTU', 2394, '../../modulos/nominas/index.php?c=Sobrerecibo&f=calculoptuview', 1055, 0, 4, 0),
  (2429, 'Emision de recibos electronicos', 2394, '../../modulos/nominas/index.php?c=Prenomina&f=xmlview', 1055, 0, 5, 0);


  INSERT  IGNORE INTO `accelog_menu` (`idmenu`, `nombre`, `idmenupadre`, `url`, `idcategoria`, `icono`, `orden`, `omision`)
VALUES
  (2261, 'Configuracion de prenomina', 2260, '../../modulos/nominas/index.php?c=Catalogos&f=configPrenomina', 1055, 0, 1, 0),
  (2286, 'Registro Incidencias', 2260, '../../modulos/nominas/index.php?c=registroincidencias&f=vistaIncidencias', 1055, 0, 2, 0),
  (2282, 'Calculo de Prenomina', 2260, '../../modulos/nominas/index.php?c=Prenomina&f=vistaPrenomina', 1055, 0, 3, 0),
  (2420, 'Autorizacion de Nomina', 2260, '../../modulos/nominas/index.php?c=Prenomina&f=vistaPrenomina', 1055, 0, 4, 0),
  (2275, 'Dispersion', 2260, '../../modulos/nominas/index.php?c=Dispersion&f=dispersion', 1055, 0, 5, 0);


INSERT IGNORE INTO `accelog_menu` (`idmenu`, `nombre`, `idmenupadre`, `url`, `idcategoria`, `icono`, `orden`, `omision`)
VALUES
  (2343, 'TVigISRSemanal', 2234, '../catalog/gestor.php?idestructura=450&ticket=testing', 1055, 0, 10, 0),
  (2346, 'TVigSubEmpSemanal', 2234, '../catalog/gestor.php?idestructura=451&ticket=testing', 1055, 0, 12, 0);


 INSERT IGNORE INTO `accelog_menu` (`idmenu`, `nombre`, `idmenupadre`, `url`, `idcategoria`, `icono`, `orden`, `omision`)
VALUES
  (2248, 'IMSS Patron', 2251, '../catalog/gestor.php?idestructura=442&ticket=testing', 1055, 0, 1, 0),
  (2249, 'IMSS Trabajador', 2251, '../catalog/gestor.php?idestructura=443&ticket=testing', 1055, 0, 2, 0),
  (2250, 'Topes SGDF', 2251, '../catalog/gestor.php?idestructura=444&ticket=testing', 1055, 0, 3, 0),
  (2251, 'Tablas Globales', 2208, NULL, 1055, 0, 2, 0);

INSERT IGNORE INTO `accelog_menu` (`idmenu`, `nombre`, `idmenupadre`, `url`, `idcategoria`, `icono`, `orden`, `omision`)
VALUES
  (2394, 'Procesos Especiales', 0, NULL, 1055, 0, 8, 0),
  (2395, 'Calculo del Aguinaldo', 2394, '../../modulos/nominas/index.php?c=Prenomina&f=veraguinaldo', 1055, 0, 0, 0),
  (2397, 'Finiquito', 2394, '../../modulos/nominas/index.php?c=Prenomina&f=verfiniquito', 1055, 0, 1, 0),
  (2378, 'Reportes', 0, '', 1055, 0, 10, 0);

INSERT IGNORE INTO `accelog_menu` (`idmenu`, `nombre`, `idmenupadre`, `url`, `idcategoria`, `icono`, `orden`, `omision`)
VALUES
  (2267, 'Reporte Prenomina', 2378, '../../modulos/nominas/index.php?c=Reportes&f=reporteSobrerecibo', 1055, 0, 4, 0),
  (2274, 'Reporte Acumulado', 2378, '../../modulos/nominas/index.php?c=Reportes&f=reporteAcumulado', 1055, 0, 5, 0),
  (2379, 'Reporte de Nominas', 2378, '../../modulos/nominas/index.php?c=Reportes&f=reporteNominas', 1055, 0, 1, 0),
  (2380, 'Reporte Entradas y Salidas de Empleados', 2378, '../../modulos/nominas/index.php?c=Reportes&f=reporteEntradas', 1055, 0, 4, 0),
  (2384, 'Reporte Incidencias', 2378, '../../modulos/nominas/index.php?c=Reportes&f=reporteIncidencias', 1055, 0, 4, 0);

INSERT IGNORE INTO `accelog_menu` (`idmenu`, `nombre`, `idmenupadre`, `url`, `idcategoria`, `icono`, `orden`, `omision`)
VALUES
  (2273, 'Calculo PTU', 2394, '../../modulos/nominas/index.php?c=Sobrerecibo&f=calculoptuview', 1055, 0, 2, 0);



INSERT  IGNORE INTO `accelog_perfiles_me` (`idperfil`, `idmenu`)
VALUES
  (2, 2248),
  (2, 2249),
  (2, 2250),
  (2, 2251),
  (2,2394),
  (2,2395),
  (2,2397);


INSERT IGNORE INTO `accelog_perfiles_me` (`idperfil`, `idmenu`)
VALUES
    (2, 2208),
    (2, 2209),
    (2, 2218),
    (2, 2219),
    (2, 2220),
    (2, 2223),
    (2, 2224),
    (2, 2226),
    (2, 2227),
    (2, 2228),
    (2, 2230),
    (2, 2234),
    (2, 2236),
    (2, 2237),
    (2, 2239),
    (2, 2241),
    (2, 2243),
    (2, 2244),
    (2, 2245),
    (2, 2248),
    (2, 2249),
    (2, 2250),
    (2, 2251),
    (2, 2252),
    (2,2257),
    (2,2260),
    (2,2261),
    (2,2262),
    (2,2282),
    (2,2286),
    (2,2297),
    (2,2346),
    (2,2343),
    (2,2360),
    (2, 2273),
    (2, 2267),
    (2, 2378),
    (2, 2379),
    (2, 2380),
    (2, 2274),
    (2, 2274),
    (2, 2420),
    (2, 2429),
    (2, 2420),
    (2, 2384),
    (2,2275);






