INSERT INTO `accelog_categorias` (`idcategoria`, `nombre`, `icono`, `orden`) VALUES
	(1072, 'Produccion', 0, 10005);

INSERT INTO `accelog_menu` (`idmenu`, `nombre`, `idmenupadre`, `url`, `idcategoria`, `icono`, `orden`, `omision`) VALUES
  (2350, 'Procesos de Producción', 0, '../../modulos/prd/ajax.php?c=recetas&f=vista_recetas&v=prc_prd', 1072, 0, 1, 0),
  (2351, 'Conceptos Laboratorio', 0, '../../modulos/prd/ajax.php?c=recetas&f=vista_recetas&v=lab_cpts', 1072, 0, 2, 0),
  (2352, 'Registro de Laboratorio', 0, '../../modulos/prd/ajax.php?c=recetas&f=vista_recetas&v=lab_rgtr', 1072, 0, 4, 0),
  (2353, 'Lab Conceptos-Producto', 0, '../../modulos/prd/ajax.php?c=recetas&f=vista_recetas&v=lab_cs_prd	', 1072, 0, 3, 0);

INSERT INTO `accelog_perfiles_me` (`idperfil`, `idmenu`) VALUES
  (2, 2350),
  (2, 2351),
  (2, 2352),
  (2, 2353);
