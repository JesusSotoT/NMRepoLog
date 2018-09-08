### Omar Vazquez | Appministra | 2016-03-29 11:34
cp _base/webapp/modulos/punto_venta_nuevo/models/caja/caja.php hpfoodware/webapp/modulos/punto_venta_nuevo/models/caja/caja.php

#### Omar Mendoza | Netwarelog | 2016-03-15 13:31
#cp -r _base_netwarelog/webapp/libraries gmorales/webapp/
#cp -r _base_netwarelog/webapp/netwarelog/accelog gmorales/webapp/netwarelog/
#cp -r _base_netwarelog/webapp/netwarelog/catalog gmorales/webapp/netwarelog/
#cp -r _base_netwarelog/webapp/netwarelog/design gmorales/webapp/netwarelog/
#cp -r _base_netwarelog/landing gmorales/
#cp -r _base_netwarelog/webapp/modulos/styleselector gmorales/webapp/modulos/
#
#chown -R www-data gmorales/webapp/libraries
#chgrp -R www-data gmorales/webapp/libraries
#chmod -R 777 gmorales/webapp/libraries
#
#chown -R www-data gmorales/webapp/netwarelog/accelog
#chgrp -R www-data gmorales/webapp/netwarelog/accelog
#chmod -R 777 gmorales/webapp/netwarelog/accelog
#
#chown -R www-data gmorales/webapp/netwarelog/catalog
#chgrp -R www-data gmorales/webapp/netwarelog/catalog
#chmod -R 777 gmorales/webapp/netwarelog/catalog
#
#chown -R www-data gmorales/webapp/netwarelog/design
#chgrp -R www-data gmorales/webapp/netwarelog/design
#chmod -R 777 gmorales/webapp/netwarelog/design
#
#chown -R www-data gmorales/landing
#chgrp -R www-data gmorales/landing
#chmod -R 777 gmorales/landing
#
#chown -R www-data gmorales/webapp/modulos/styleselector
#chgrp -R www-data gmorales/webapp/modulos/styleselector
#chmod -R 777 gmorales/webapp/modulos/styleselector
#
#cp _base_netwarelog/webapp/netwarelog/accelog.php gmorales/webapp/netwarelog/accelog.php
#cp _base_netwarelog/webapp/netwarelog/accelog_claccess.php gmorales/webapp/netwarelog/accelog_claccess.php
#cp _base_netwarelog/webapp/netwarelog/descarga_archivo.php gmorales/webapp/netwarelog/descarga_archivo.php
#cp _base_netwarelog/webapp/netwarelog/descarga_archivo_fisico.php gmorales/webapp/netwarelog/descarga_archivo_fisico.php


### Omar Vazquez | Appministra | 2016-03-02
#echo '[nmadmin]'
#cp _base/webapp/modulos/facturacion/descargaNotas.php nmadmin/webapp/modulos/facturacion/descargaNotas.php
#cp _base/webapp/modulos/facturacion/getInfoFacturas.php nmadmin/webapp/modulos/facturacion/getInfoFacturas.php

### Omar Vazquez | Appministra | 2016-03-01
#echo '[nmadmin]'
#cp _base/webapp/modulos/SAT/funcionesSAT.php nmadmin/webapp/modulos/SAT/funcionesSAT.php

### Omar Vazquez | Appministra | 2016-03-01
#echo '[nmadmin]'
#cp _base/webapp/modulos/punto_venta_nuevo/js/caja/caja.js nmadmin/webapp/modulos/punto_venta_nuevo/js/caja/caja.js
#cp _base/webapp/modulos/punto_venta_nuevo/models/caja/caja.php nmadmin/webapp/modulos/punto_venta_nuevo/models/caja/caja.php
#cp _base/webapp/modulos/SAT/funcionesSAT.php nmadmin/webapp/modulos/SAT/funcionesSAT.php
#cp _base/webapp/modulos/SAT/PDF/CFDIPDF.php nmadmin/webapp/modulos/SAT/PDF/CFDIPDF.php
#
#echo '[ebustamante]'
#cp _base/webapp/modulos/punto_venta_nuevo/js/caja/caja.js ebustamante/webapp/modulos/punto_venta_nuevo/js/caja/caja.js
#cp _base/webapp/modulos/punto_venta_nuevo/models/caja/caja.php ebustamante/webapp/modulos/punto_venta_nuevo/models/caja/caja.php
#cp _base/webapp/modulos/SAT/funcionesSAT.php ebustamante/webapp/modulos/SAT/funcionesSAT.php
#cp _base/webapp/modulos/SAT/PDF/CFDIPDF.php ebustamante/webapp/modulos/SAT/PDF/CFDIPDF.php

### Omar Vazquez | Appministra | 2016-02-04 19:00
#
#echo '[ebustamante]'
#cp _base/webapp/modulos/SAT/funcionesSAT.php ebustamante/webapp/modulos/SAT/funcionesSAT.php
#
#echo '[sistema]'
#cp _base/webapp/modulos/SAT/funcionesSAT.php sistema/webapp/modulos/SAT/funcionesSAT.php
#
#echo '[nmadmin]'
#cp _base/webapp/modulos/SAT/funcionesSAT.php nmadmin/webapp/modulos/SAT/funcionesSAT.php


### Bancos | Camen Gutierrez | 2016-01-29

#echo '[demo2]'
#cp -R _base/webapp/modulos/bancos/views/configuracion demo2/webapp/modulos/bancos/views
#chown -R www-data demo2/webapp/modulos/bancos/views/configuracion
#chgrp -R www-data demo2/webapp/modulos/bancos/views/configuracion
#chmod -R 777 demo2/webapp/modulos/bancos/views/configuracion
#cp _base/webapp/modulos/bancos/controllers/cheques.php demo2/webapp/modulos/bancos/controllers/cheques.php
#cp _base/webapp/modulos/bancos/controllers/ingresos.php demo2/webapp/modulos/bancos/controllers/ingresos.php
#cp _base/webapp/modulos/bancos/models/cheques.php demo2/webapp/modulos/bancos/models/cheques.php
#cp _base/webapp/modulos/bancos/models/ingresos.php demo2/webapp/modulos/bancos/models/ingresos.php
#cp _base/webapp/modulos/bancos/js/depositos.js demo2/webapp/modulos/bancos/js/depositos.js
#cp _base/webapp/modulos/bancos/js/egresos.js demo2/webapp/modulos/bancos/js/egresos.js
#cp _base/webapp/modulos/bancos/js/ingresos.js demo2/webapp/modulos/bancos/js/ingresos.js
#cp _base/webapp/modulos/bancos/views/documentos/depositos.php demo2/webapp/modulos/bancos/views/documentos/depositos.php
#cp _base/webapp/modulos/bancos/views/documentos/depositosListado.php demo2/webapp/modulos/bancos/views/documentos/depositosListado.php
#cp _base/webapp/modulos/bancos/views/documentos/egresoListado.php demo2/webapp/modulos/bancos/views/documentos/egresoListado.php
#cp _base/webapp/modulos/bancos/views/documentos/egresos.php demo2/webapp/modulos/bancos/views/documentos/egresos.php
#cp _base/webapp/modulos/bancos/views/documentos/ingresos.php demo2/webapp/modulos/bancos/views/documentos/ingresos.php
#cp _base/webapp/modulos/bancos/views/documentos/ingresosNoDepositado.php demo2/webapp/modulos/bancos/views/documentos/ingresosNoDepositado.php
#cp _base/webapp/modulos/bancos/views/documentos/proyectadoListado.php demo2/webapp/modulos/bancos/views/documentos/proyectadoListado.php
#cp _base/webapp/modulos/bancos/views/documentos/ingresosListado.php demo2/webapp/modulos/bancos/views/documentos/ingresosListado.php
#cp _base/webapp/modulos/bancos/images/re3.png demo2/webapp/modulos/bancos/images/re3.png
#cp _base/webapp/modulos/bancos/views/configuracion/configuracion.php demo2/webapp/modulos/bancos/views/configuracion/configuracion.php
#cp _base/webapp/modulos/bancos/views/retenciones/retenciones.php demo2/webapp/modulos/bancos/views/retenciones/retenciones.php
##cp _base/webapp/modulos/bancos/js/config.js demo2/webapp/modulos/bancos/js/config.js
#cp _base/webapp/modulos/bancos/models/configuracion.php demo2/webapp/modulos/bancos/models/configuracion.php
#cp _base/webapp/modulos/bancos/controllers/configuracion.php demo2/webapp/modulos/bancos/controllers/configuracion.php
#
#
#




### Acontia | Ivan Cuenca | 2016-01-29 10:41
#
#echo '[demo24]'
#cp _base/webapp/modulos/cont/models/presupuesto.php demo24/webapp/modulos/cont/models/presupuesto.php
#cp _base/webapp/modulos/cont/views/partial/top.php demo24/webapp/modulos/cont/views/partial/top.php
#cp _base/webapp/modulos/cont/views/presupuestal/index.php demo24/webapp/modulos/cont/views/presupuestal/index.php
#
#echo '[demo41]'
#cp _base/webapp/modulos/cont/models/presupuesto.php demo41/webapp/modulos/cont/models/presupuesto.php
#cp _base/webapp/modulos/cont/views/partial/top.php demo41/webapp/modulos/cont/views/partial/top.php
#cp _base/webapp/modulos/cont/views/presupuestal/index.php demo41/webapp/modulos/cont/views/presupuestal/index.php
#


### Appministra | Omar Vazquez | 2016-01-29 10:20

#echo '[nmadmin]'
##cp _base/webapp/modulos/SAT/PDF/CFDIPDF.php nmadmin/webapp/modulos/SAT/PDF/CFDIPDF.php
#cp _base/webapp/modulos/SAT/funcionesSAT.php nmadmin/webapp/modulos/SAT/funcionesSAT.php
#cp _base/webapp/modulos/punto_venta_nuevo/js/caja/caja.js nmadmin/webapp/modulos/punto_venta_nuevo/js/caja/caja.js
#cp _base/webapp/modulos/punto_venta_nuevo/models/caja/caja.php nmadmin/webapp/modulos/punto_venta_nuevo/models/caja/caja.php
#
#echo '[ebustamante]'
#cp _base/webapp/modulos/SAT/PDF/CFDIPDF.php ebustamante/webapp/modulos/SAT/PDF/CFDIPDF.php
#cp _base/webapp/modulos/SAT/funcionesSAT.php ebustamante/webapp/modulos/SAT/funcionesSAT.php
#cp _base/webapp/modulos/punto_venta_nuevo/js/caja/caja.js ebustamante/webapp/modulos/punto_venta_nuevo/js/caja/caja.js
#cp _base/webapp/modulos/punto_venta_nuevo/models/caja/caja.php ebustamante/webapp/modulos/punto_venta_nuevo/models/caja/caja.php
#

### Acontia | Ivan Cuenca | 2016-01-28 17:32

#echo '[demo24]'
#cp _base/webapp/modulos/cont/controllers/presupuesto.php demo24/webapp/modulos/cont/controllers/presupuesto.php
#cp _base/webapp/modulos/cont/importar/import_presupuesto.php demo24/webapp/modulos/cont/importar/import_presupuesto.php
#cp _base/webapp/modulos/cont/importar/validaciones_presupuesto.php demo24/webapp/modulos/cont/importar/validaciones_presupuesto.php
#cp _base/webapp/modulos/cont/models/presupuesto.php demo24/webapp/modulos/cont/models/presupuesto.php
#
#echo '[demo41]'
#cp _base/webapp/modulos/cont/controllers/presupuesto.php demo41/webapp/modulos/cont/controllers/presupuesto.php
#cp _base/webapp/modulos/cont/importar/import_presupuesto.php demo41/webapp/modulos/cont/importar/import_presupuesto.php
#cp _base/webapp/modulos/cont/importar/validaciones_presupuesto.php demo41/webapp/modulos/cont/importar/validaciones_presupuesto.php
#cp _base/webapp/modulos/cont/models/presupuesto.php demo41/webapp/modulos/cont/models/presupuesto.php

### Foodware | Fer de la Cruz | 2016-01-27 10:25
#echo '[ebustamante]'
#cp _base/webapp/modulos/restaurantes/controllers/comandas.php ebustamante/webapp/modulos/restaurantes/controllers/comandas.php
#cp _base/webapp/modulos/restaurantes/models/comandas.php ebustamante/webapp/modulos/restaurantes/models/comandas.php
#cp _base/webapp/modulos/restaurantes/views/comandas/Gmesas.php ebustamante/webapp/modulos/restaurantes/views/comandas/Gmesas.php

### Acontia | Ivan Cuenca | 2016-01-26 18:48
#
#echo '[demo24]'
#cp _base/webapp/modulos/cont/models/captpolizas.php demo24/webapp/modulos/cont/models/captpolizas.php
#cp _base/webapp/modulos/cont_repolog/eliminar_segmentos.php demo24/webapp/modulos/cont_repolog/eliminar_segmentos.php
#cp _base/webapp/modulos/cont_repolog/eliminar_segmentos_despues.php demo24/webapp/modulos/cont_repolog/eliminar_segmentos_despues.php
#cp _base/webapp/modulos/cont/controllers/config.php demo24/webapp/modulos/cont/controllers/config.php
#cp _base/webapp/modulos/cont/models/config.php demo24/webapp/modulos/cont/models/config.php
#
#echo '[demo41]'
#cp _base/webapp/modulos/cont/models/captpolizas.php demo41/webapp/modulos/cont/models/captpolizas.php
#cp _base/webapp/modulos/cont_repolog/eliminar_segmentos.php demo41/webapp/modulos/cont_repolog/eliminar_segmentos.php
#cp _base/webapp/modulos/cont_repolog/eliminar_segmentos_despues.php demo41/webapp/modulos/cont_repolog/eliminar_segmentos_despues.php
#cp _base/webapp/modulos/cont/controllers/config.php demo41/webapp/modulos/cont/controllers/config.php
#cp _base/webapp/modulos/cont/models/config.php demo41/webapp/modulos/cont/models/config.php


### Appministra | Omar Vazquez | 2016-01-26 11:08
#cp _base/facturar/js/facturar.js ebustamante/facturar/js/facturar.js
#cp _base/facturar/model/facturar.php ebustamante/facturar/model/facturar.php


### Appministra | Omar Vazquez | 2016-01-26 10:46
#cp _base/webapp/modulos/punto_venta_nuevo/models/caja/caja.php ebustamante/webapp/modulos/punto_venta_nuevo/models/caja/caja.php
#cp -R _base/facturar ebustamante/

### Acontia | Carmen Gtz | 2016-01-25 16:52
#echo '[demo32]'
#cp _base/webapp/modulos/cont/models/conciliacionacontia.php demo32/webapp/modulos/cont/models/conciliacionacontia.php
#cp _base/webapp/modulos/cont/js/conciliacionacontia.js demo32/webapp/modulos/cont/js/conciliacionacontia.js
#cp _base/webapp/modulos/cont/controllers/conciliacionacontia.php demo32/webapp/modulos/cont/controllers/conciliacionacontia.php
#
#echo '[demo33]'
#cp _base/webapp/modulos/cont/models/conciliacionacontia.php demo33/webapp/modulos/cont/models/conciliacionacontia.php
#cp _base/webapp/modulos/cont/js/conciliacionacontia.js demo33/webapp/modulos/cont/js/conciliacionacontia.js
#cp _base/webapp/modulos/cont/controllers/conciliacionacontia.php demo33/webapp/modulos/cont/controllers/conciliacionacontia.php
#
#echo '[demo24]'
#cp _base/webapp/modulos/cont/models/conciliacionacontia.php demo24/webapp/modulos/cont/models/conciliacionacontia.php
#cp _base/webapp/modulos/cont/js/conciliacionacontia.js demo24/webapp/modulos/cont/js/conciliacionacontia.js
#cp _base/webapp/modulos/cont/controllers/conciliacionacontia.php demo24/webapp/modulos/cont/controllers/conciliacionacontia.php
#
#echo '[demo41]'
#cp _base/webapp/modulos/cont/models/conciliacionacontia.php demo41/webapp/modulos/cont/models/conciliacionacontia.php
#cp _base/webapp/modulos/cont/js/conciliacionacontia.js demo41/webapp/modulos/cont/js/conciliacionacontia.js
#cp _base/webapp/modulos/cont/controllers/conciliacionacontia.php demo41/webapp/modulos/cont/controllers/conciliacionacontia.php


### Acontia | Ivan Cuenca | 2016-01-25 16:48
#echo '[demo24]'
#cp _base/webapp/modulos/cont/controllers/captpolizas.php demo24/webapp/modulos/cont/controllers/captpolizas.php
#cp _base/webapp/modulos/cont/js/agregar.js demo24/webapp/modulos/cont/js/agregar.js
#cp _base/webapp/modulos/cont/models/captpolizas.php demo24/webapp/modulos/cont/models/captpolizas.php
#cp _base/webapp/modulos/cont/views/reports/balanceGeneralReporte.php demo24/webapp/modulos/cont/views/reports/balanceGeneralReporte.php
#cp _base/webapp/netwarelog/mvc/models/connection_sqli.php demo24/webapp/netwarelog/mvc/models/connection_sqli.php
#
#echo '[demo41]'
#cp _base/webapp/modulos/cont/controllers/captpolizas.php demo41/webapp/modulos/cont/controllers/captpolizas.php
#cp _base/webapp/modulos/cont/js/agregar.js demo41/webapp/modulos/cont/js/agregar.js
#cp _base/webapp/modulos/cont/models/captpolizas.php demo41/webapp/modulos/cont/models/captpolizas.php
#cp _base/webapp/modulos/cont/views/reports/balanceGeneralReporte.php demo41/webapp/modulos/cont/views/reports/balanceGeneralReporte.php
#cp _base/webapp/netwarelog/mvc/models/connection_sqli.php demo41/webapp/netwarelog/mvc/models/connection_sqli.php
#

### Foodware | Fer de la Cruz | 2016-01-25 16:44
#echo '[ebustamante]'
#cp _base/webapp/modulos/restaurantes/controllers/comandas.php ebustamante/webapp/modulos/restaurantes/controllers/comandas.php
#cp _base/webapp/modulos/restaurantes/models/comandas.php ebustamante/webapp/modulos/restaurantes/models/comandas.php
#cp _base/webapp/modulos/restaurantes/views/comandas/comanda.php ebustamante/webapp/modulos/restaurantes/views/comandas/comanda.php
#
#echo '[gourmet]'
#cp _base/webapp/modulos/restaurantes/controllers/comandas.php gourmet/webapp/modulos/restaurantes/controllers/comandas.php
#cp _base/webapp/modulos/restaurantes/models/comandas.php gourmet/webapp/modulos/restaurantes/models/comandas.php
#cp _base/webapp/modulos/restaurantes/views/comandas/comanda.php gourmet/webapp/modulos/restaurantes/views/comandas/comanda.php
#
#echo '[patriciag2]'
#cp _base/webapp/modulos/restaurantes/controllers/comandas.php patriciag2/webapp/modulos/restaurantes/controllers/comandas.php
#cp _base/webapp/modulos/restaurantes/models/comandas.php patriciag2/webapp/modulos/restaurantes/models/comandas.php
#cp _base/webapp/modulos/restaurantes/views/comandas/comanda.php patriciag2/webapp/modulos/restaurantes/views/comandas/comanda.php
#



### Acontia | Carmen Gutierrez | 2016-01-25 10:40
#cp _base/webapp/modulos/cont/views/captpolizas/actpolizas.php demo33/webapp/modulos/cont/views/captpolizas/actpolizas.php

### Acontia | Ivan Cuenca | 2016-01-25 10:37
#echo '[demo24]'
#cp _base/webapp/modulos/cont/index.php demo24/webapp/modulos/cont/index.php
#cp _base/webapp/modulos/cont/views/partial/footer.php demo24/webapp/modulos/cont/views/partial/footer.php
#cp _base/webapp/netwarelog/mvc/controllers/common_father.php demo24/webapp/netwarelog/mvc/controllers/common_father.php
#cp _base/webapp/netwarelog/mvc/libraries/access.php demo24/webapp/netwarelog/mvc/libraries/access.php
#
#echo '[demo41]'
#cp _base/webapp/modulos/cont/index.php demo41/webapp/modulos/cont/index.php
#cp _base/webapp/modulos/cont/views/partial/footer.php demo41/webapp/modulos/cont/views/partial/footer.php
#cp _base/webapp/netwarelog/mvc/controllers/common_father.php demo41/webapp/netwarelog/mvc/controllers/common_father.php
#cp _base/webapp/netwarelog/mvc/libraries/access.php demo41/webapp/netwarelog/mvc/libraries/access.php
#

### Foodware | Fer de la Cruz | 2016-01-25 10:24
#echo '[ebustamante]'
#cp _base/webapp/modulos/restaurantes/controllers/comandas.php ebustamante/webapp/modulos/restaurantes/controllers/comandas.php
#cp _base/webapp/modulos/restaurantes/js/comandas/comandas.js ebustamante/webapp/modulos/restaurantes/js/comandas/comandas.js
#cp _base/webapp/modulos/restaurantes/models/comandas.php ebustamante/webapp/modulos/restaurantes/models/comandas.php
#cp _base/webapp/modulos/restaurantes/views/comandas/Gmesas.php ebustamante/webapp/modulos/restaurantes/views/comandas/Gmesas.php
#cp _base/webapp/modulos/restaurantes/views/comandas/listar_comandas.php ebustamante/webapp/modulos/restaurantes/views/comandas/listar_comandas.php
#cp _base/webapp/modulos/restaurantes/views/comandas/listar_mesas.php ebustamante/webapp/modulos/restaurantes/views/comandas/listar_mesas.php
#cp _base/webapp/modulos/restaurantes/views/comandas/vista_empleados.php ebustamante/webapp/modulos/restaurantes/views/comandas/vista_empleados.php
#
#echo '[gourmet]'
#cp _base/webapp/modulos/restaurantes/controllers/comandas.php gourmet/webapp/modulos/restaurantes/controllers/comandas.php
#cp _base/webapp/modulos/restaurantes/js/comandas/comandas.js gourmet/webapp/modulos/restaurantes/js/comandas/comandas.js
#cp _base/webapp/modulos/restaurantes/models/comandas.php gourmet/webapp/modulos/restaurantes/models/comandas.php
#cp _base/webapp/modulos/restaurantes/views/comandas/Gmesas.php gourmet/webapp/modulos/restaurantes/views/comandas/Gmesas.php
#cp _base/webapp/modulos/restaurantes/views/comandas/listar_comandas.php gourmet/webapp/modulos/restaurantes/views/comandas/listar_comandas.php
#cp _base/webapp/modulos/restaurantes/views/comandas/listar_mesas.php gourmet/webapp/modulos/restaurantes/views/comandas/listar_mesas.php
#cp _base/webapp/modulos/restaurantes/views/comandas/vista_empleados.php gourmet/webapp/modulos/restaurantes/views/comandas/vista_empleados.php
#
#
#cp -R _base/webapp/modulos/restaurantes/js/bootstrap-select-1.9.3/ gourmet/webapp/modulos/restaurantes/js/bootstrap-select-1.9.3/
#cp -R _base/webapp/modulos/restaurantes/js/bootstrap-select-1.9.3/ ebustamante/webapp/modulos/restaurantes/js/bootstrap-select-1.9.3/
#
#chown -R www-data gourmet/webapp/modulos/restaurantes/js/bootstrap-select-1.9.3/
#chgrp -R www-data gourmet/webapp/modulos/restaurantes/js/bootstrap-select-1.9.3/
#chmod -R 777 gourmet/webapp/modulos/restaurantes/js/bootstrap-select-1.9.3/
#
#chown -R www-data ebustamante/webapp/modulos/restaurantes/js/bootstrap-select-1.9.3/
#chgrp -R www-data ebustamante/webapp/modulos/restaurantes/js/bootstrap-select-1.9.3/
#chmod -R 777 ebustamante/webapp/modulos/restaurantes/js/bootstrap-select-1.9.3/



### Acontia | Ivan Cuenca | 2016-01-25 10:19

#echo '[demo24]'
#cp _base/webapp/modulos/cont/controllers/accountstree.php demo24/webapp/modulos/cont/controllers/accountstree.php
#cp _base/webapp/modulos/cont/index.php demo24/webapp/modulos/cont/index.php
#cp _base/webapp/modulos/cont/js/accountsTree.js.php demo24/webapp/modulos/cont/js/accountsTree.js.php
#cp _base/webapp/modulos/cont/models/accountsTree.php demo24/webapp/modulos/cont/models/accountsTree.php
#cp _base/webapp/modulos/cont/views/accountsTree/index.php demo24/webapp/modulos/cont/views/accountsTree/index.php
#cp _base/webapp/modulos/cont/views/reports/balanceGeneralReporte.php demo24/webapp/modulos/cont/views/reports/balanceGeneralReporte.php
#cp _base/webapp/modulos/cont/views/reports/balanceGeneralReportePeriodos.php demo24/webapp/modulos/cont/views/reports/balanceGeneralReportePeriodos.php
#cp _base/webapp/modulos/cont/views/reports/estadoResultadosReporte.php demo24/webapp/modulos/cont/views/reports/estadoResultadosReporte.php
#cp _base/webapp/modulos/cont/views/reports/estadoResultadosReportePresup.php demo24/webapp/modulos/cont/views/reports/estadoResultadosReportePresup.php
#cp _base/webapp/modulos/cont/views/reports/estadoResultadosxSegmento2.php demo24/webapp/modulos/cont/views/reports/estadoResultadosxSegmento2.php
#
#echo '[demo41]'
#cp _base/webapp/modulos/cont/controllers/accountstree.php demo41/webapp/modulos/cont/controllers/accountstree.php
#cp _base/webapp/modulos/cont/index.php demo41/webapp/modulos/cont/index.php
#cp _base/webapp/modulos/cont/js/accountsTree.js.php demo41/webapp/modulos/cont/js/accountsTree.js.php
#cp _base/webapp/modulos/cont/models/accountsTree.php demo41/webapp/modulos/cont/models/accountsTree.php
#cp _base/webapp/modulos/cont/views/accountsTree/index.php demo41/webapp/modulos/cont/views/accountsTree/index.php
#cp _base/webapp/modulos/cont/views/reports/balanceGeneralReporte.php demo41/webapp/modulos/cont/views/reports/balanceGeneralReporte.php
#cp _base/webapp/modulos/cont/views/reports/balanceGeneralReportePeriodos.php demo41/webapp/modulos/cont/views/reports/balanceGeneralReportePeriodos.php
#cp _base/webapp/modulos/cont/views/reports/estadoResultadosReporte.php demo41/webapp/modulos/cont/views/reports/estadoResultadosReporte.php
#cp _base/webapp/modulos/cont/views/reports/estadoResultadosReportePresup.php demo41/webapp/modulos/cont/views/reports/estadoResultadosReportePresup.php
#cp _base/webapp/modulos/cont/views/reports/estadoResultadosxSegmento2.php demo41/webapp/modulos/cont/views/reports/estadoResultadosxSegmento2.php
#


### Appministra | Omar Vazquez | 2016-01-19 18:55

#echo '[gcmarisol]'
#cp _base/webapp/modulos/SAT/PDF/CFDIPDF.php gcmarisol/webapp/modulos/SAT/PDF/CFDIPDF.php
#cp _base/webapp/modulos/SAT/funcionesSAT.php gcmarisol/webapp/modulos/SAT/funcionesSAT.php
#cp _base/webapp/modulos/punto_venta_nuevo/models/caja/caja.php gcmarisol/webapp/modulos/punto_venta_nuevo/models/caja/caja.php




### Appministra | Omar Vazquez | 2016-01-12 10:26

#echo '[ebustamante]'
#cp _base/webapp/modulos/punto_venta_nuevo/controllers/caja.php ebustamante/webapp/modulos/punto_venta_nuevo/controllers/caja.php
#cp _base/webapp/modulos/punto_venta_nuevo/js/caja/caja.js ebustamante/webapp/modulos/punto_venta_nuevo/js/caja/caja.js
#cp _base/webapp/modulos/punto_venta_nuevo/models/caja/caja.php ebustamante/webapp/modulos/punto_venta_nuevo/models/caja/caja.php
#cp _base/webapp/modulos/punto_venta_nuevo/views/caja/caja.php ebustamante/webapp/modulos/punto_venta_nuevo/views/caja/caja.php
#cp _base/webapp/modulos/punto_venta/funcionesBD/importar_productos.php ebustamante/webapp/modulos/punto_venta/funcionesBD/importar_productos.php


### Acontia | Ivan Cuenca | 2016-01-12 10:18
#echo '[demo8]'
#cp _base/webapp/modulos/cont/models/reports.php demo8/webapp/modulos/cont/models/reports.php
#
#echo '[demo24]'
#cp _base/webapp/modulos/cont/models/reports.php demo24/webapp/modulos/cont/models/reports.php
#
#echo '[demo32]'
#cp _base/webapp/modulos/cont/models/reports.php demo32/webapp/modulos/cont/models/reports.php
#
#echo '[ccpg]'
#cp _base/webapp/modulos/cont/models/reports.php ccpg/webapp/modulos/cont/models/reports.php



### Foodware | Fer | 2016-01-08 18:33

#echo '[ebustamante]'
#cp _base/webapp/modulos/restaurantes/controllers/comandas.php ebustamante/webapp/modulos/restaurantes/controllers/comandas.php
#cp _base/webapp/modulos/restaurantes/models/comandas.php ebustamante/webapp/modulos/restaurantes/models/comandas.php
#cp _base/webapp/modulos/restaurantes/views/comandas/vista_estatus_comandas.php ebustamante/webapp/modulos/restaurantes/views/comandas/vista_estatus_comandas.php
#
#echo '[gourmet]'
#cp _base/webapp/modulos/restaurantes/controllers/comandas.php gourmet/webapp/modulos/restaurantes/controllers/comandas.php
#cp _base/webapp/modulos/restaurantes/models/comandas.php gourmet/webapp/modulos/restaurantes/models/comandas.php
#cp _base/webapp/modulos/restaurantes/views/comandas/vista_estatus_comandas.php gourmet/webapp/modulos/restaurantes/views/comandas/vista_estatus_comandas.php


### [Acontia] | Ivan Cuenca | 2016-01-08 18:16

#echo '[demo8]'
#cp _base/webapp/netwarelog/mvc/mvc.zip demo8/webapp/netwarelog/mvc/mvc.zip
#
#echo '[demo24]'
#cp _base/webapp/netwarelog/mvc/mvc.zip demo24/webapp/netwarelog/mvc/mvc.zip
#
#echo '[demo32]'
#cp _base/webapp/netwarelog/mvc/mvc.zip demo32/webapp/netwarelog/mvc/mvc.zip
#

### [Foodware] | Fer | 2016-01-08 18:11

#echo '[ebustamante]'
#cp _base/webapp/modulos/cont/controllers/polizasimpresion.php ebustamante/webapp/modulos/cont/controllers/polizasimpresion.php
#cp _base/webapp/modulos/cont/models/polizasImpresion.php ebustamante/webapp/modulos/cont/models/polizasImpresion.php
#cp _base/webapp/modulos/cont/views/captpolizas/polizasImpresion.php ebustamante/webapp/modulos/cont/views/captpolizas/polizasImpresion.php
#cp _base/webapp/modulos/cont/views/reports/navegadorXMLs_facturas.php ebustamante/webapp/modulos/cont/views/reports/navegadorXMLs_facturas.php
#
#echo '[gourmet]'
#cp _base/webapp/modulos/cont/controllers/polizasimpresion.php gourmet/webapp/modulos/cont/controllers/polizasimpresion.php
#cp _base/webapp/modulos/cont/models/polizasImpresion.php gourmet/webapp/modulos/cont/models/polizasImpresion.php
#cp _base/webapp/modulos/cont/views/captpolizas/polizasImpresion.php gourmet/webapp/modulos/cont/views/captpolizas/polizasImpresion.php
#cp _base/webapp/modulos/cont/views/reports/navegadorXMLs_facturas.php gourmet/webapp/modulos/cont/views/reports/navegadorXMLs_facturas.php
#

### Acontia - NetwarLog | Ivan Cuenca | 2016-01-08 17:58
#
#echo '[demo8]'
#cp _base/webapp/modulos/cont/Formato_presupuestos.xls demo8/webapp/modulos/cont/Formato_presupuestos.xls
#cp _base/webapp/modulos/cont/ajax.php demo8/webapp/modulos/cont/ajax.php
#cp _base/webapp/modulos/cont/controllers/common.php demo8/webapp/modulos/cont/controllers/common.php
#cp _base/webapp/modulos/cont/controllers/presupuesto.php demo8/webapp/modulos/cont/controllers/presupuesto.php
#cp _base/webapp/modulos/cont/index.php demo8/webapp/modulos/cont/index.php
#cp _base/webapp/modulos/cont/js/agregar.js demo8/webapp/modulos/cont/js/agregar.js
#cp _base/webapp/modulos/cont/models/connection.php demo8/webapp/modulos/cont/models/connection.php
#cp _base/webapp/modulos/cont/models/connection_sqli.php demo8/webapp/modulos/cont/models/connection_sqli.php
#cp _base/webapp/modulos/cont/models/connection_sqli_manual.php demo8/webapp/modulos/cont/models/connection_sqli_manual.php
#cp _base/webapp/modulos/cont/models/presupuesto.php demo8/webapp/modulos/cont/models/presupuesto.php
#cp _base/webapp/modulos/cont/models/reports.php demo8/webapp/modulos/cont/models/reports.php
#cp _base/webapp/modulos/cont/views/presupuestal/index.php demo8/webapp/modulos/cont/views/presupuestal/index.php
#cp -r _base/webapp/netwarelog/mvc demo8/webapp/netwarelog/
#
#chown -R www-data demo8/webapp/netwarelog/mvc
#chgrp -R www-data demo8/webapp/netwarelog/mvc
#chmod -R 777 demo8/webapp/netwarelog/mvc
#
#echo '[demo24]'
#cp _base/webapp/modulos/cont/Formato_presupuestos.xls demo24/webapp/modulos/cont/Formato_presupuestos.xls
#cp _base/webapp/modulos/cont/ajax.php demo24/webapp/modulos/cont/ajax.php
#cp _base/webapp/modulos/cont/controllers/common.php demo24/webapp/modulos/cont/controllers/common.php
#cp _base/webapp/modulos/cont/controllers/presupuesto.php demo24/webapp/modulos/cont/controllers/presupuesto.php
#cp _base/webapp/modulos/cont/index.php demo24/webapp/modulos/cont/index.php
#cp _base/webapp/modulos/cont/js/agregar.js demo24/webapp/modulos/cont/js/agregar.js
#cp _base/webapp/modulos/cont/models/connection.php demo24/webapp/modulos/cont/models/connection.php
#cp _base/webapp/modulos/cont/models/connection_sqli.php demo24/webapp/modulos/cont/models/connection_sqli.php
#cp _base/webapp/modulos/cont/models/connection_sqli_manual.php demo24/webapp/modulos/cont/models/connection_sqli_manual.php
#cp _base/webapp/modulos/cont/models/presupuesto.php demo24/webapp/modulos/cont/models/presupuesto.php
#cp _base/webapp/modulos/cont/models/reports.php demo24/webapp/modulos/cont/models/reports.php
#cp _base/webapp/modulos/cont/views/presupuestal/index.php demo24/webapp/modulos/cont/views/presupuestal/index.php
#cp -r _base/webapp/netwarelog/mvc demo24/webapp/netwarelog/
#
##chown -R www-data demo24/webapp/netwarelog/mvc
#chgrp -R www-data demo24/webapp/netwarelog/mvc
#chmod -R 777 demo24/webapp/netwarelog/mvc
#
#echo '[demo32]'
#cp _base/webapp/modulos/cont/Formato_presupuestos.xls demo32/webapp/modulos/cont/Formato_presupuestos.xls
#cp _base/webapp/modulos/cont/ajax.php demo32/webapp/modulos/cont/ajax.php
#cp _base/webapp/modulos/cont/controllers/common.php demo32/webapp/modulos/cont/controllers/common.php
#cp _base/webapp/modulos/cont/controllers/presupuesto.php demo32/webapp/modulos/cont/controllers/presupuesto.php
#cp _base/webapp/modulos/cont/index.php demo32/webapp/modulos/cont/index.php
#cp _base/webapp/modulos/cont/js/agregar.js demo32/webapp/modulos/cont/js/agregar.js
#cp _base/webapp/modulos/cont/models/connection.php demo32/webapp/modulos/cont/models/connection.php
#cp _base/webapp/modulos/cont/models/connection_sqli.php demo32/webapp/modulos/cont/models/connection_sqli.php
#cp _base/webapp/modulos/cont/models/connection_sqli_manual.php demo32/webapp/modulos/cont/models/connection_sqli_manual.php
#cp _base/webapp/modulos/cont/models/presupuesto.php demo32/webapp/modulos/cont/models/presupuesto.php
#cp _base/webapp/modulos/cont/models/reports.php demo32/webapp/modulos/cont/models/reports.php
#cp _base/webapp/modulos/cont/views/presupuestal/index.php demo32/webapp/modulos/cont/views/presupuestal/index.php
#cp -r _base/webapp/netwarelog/mvc demo32/webapp/netwarelog/
#
#chown -R www-data demo32/webapp/netwarelog/mvc
#chgrp -R www-data demo32/webapp/netwarelog/mvc
#chmod -R 777 demo32/webapp/netwarelog/mvc
#

### Appministra | Omar Vazquez | 2016-01-08 17:09
#
#echo '[ebustamante]'
#cp _base/webapp/modulos/punto_venta_nuevo/models/caja/caja.php ebustamante/webapp/modulos/punto_venta_nuevo/models/caja/caja.php
#
#echo '[gourmet]'
#cp _base/webapp/modulos/punto_venta_nuevo/models/caja/caja.php gourmet/webapp/modulos/punto_venta_nuevo/models/caja/caja.php
#
#

### Acontia | instancia: sistema | permisos especiales para polizas

#chmod 777 /srv/www/htdocs/clientes/sistema/webapp/modulos/cont/xmls/facturas/temporales/*
#chown -R www-data /srv/www/htdocs/clientes/sistema/webapp/modulos/cont/xmls/facturas/temporales/*
#chgrp -R www-data /srv/www/htdocs/clientes/sistema/webapp/modulos/cont/xmls/facturas/temporales/*

### Acontia | Ivan Cuenca | 2016-01-05 18:27
#
#echo '[demo8]'
#cp _base/webapp/modulos/cont/controllers/reports.php demo8/webapp/modulos/cont/controllers/reports.php
#cp _base/webapp/modulos/cont/models/reports.php demo8/webapp/modulos/cont/models/reports.php
#cp _base/webapp/modulos/cont/models/presupuesto.php demo8/webapp/modulos/cont/models/presupuesto.php
#cp _base/webapp/modulos/cont/controllers/presupuesto.php demo8/webapp/modulos/cont/controllers/presupuesto.php
#cp _base/webapp/modulos/cont/js/agregar.js demo8/webapp/modulos/cont/js/agregar.js
#cp _base/webapp/modulos/cont/views/presupuestal/index.php demo8/webapp/modulos/cont/views/presupuestal/index.php
#cp _base/webapp/modulos/cont/views/reports/estadoResultadosReportePresup.php demo8/webapp/modulos/cont/views/reports/estadoResultadosReportePresup.php
#cp _base/webapp/modulos/cont/importar/import_presupuesto.php demo8/webapp/modulos/cont/importar/import_presupuesto.php
#cp _base/webapp/modulos/cont/importar/validaciones_presupuesto.php demo8/webapp/modulos/cont/importar/validaciones_presupuesto.php
#cp _base/webapp/modulos/cont/Formato_presupuestos.xls demo8/webapp/modulos/cont/Formato_presupuestos.xls
#
#chmod -R 777 demo8/webapp/modulos/cont/importar/import_presupuesto.php
#chown -R www-data demo8/webapp/modulos/cont/importar/import_presupuesto.php
#chgrp -R www-data demo8/webapp/modulos/cont/importar/import_presupuesto.php
#
#chmod -R 777 demo8/webapp/modulos/cont/importar/validaciones_presupuesto.php
#chown -R www-data demo8/webapp/modulos/cont/importar/validaciones_presupuesto.php
#chgrp -R www-data demo8/webapp/modulos/cont/importar/validaciones_presupuesto.php
#
#chmod -R 777 demo8/webapp/modulos/cont/Formato_presupuestos.xls
#chown -R www-data demo8/webapp/modulos/cont/Formato_presupuestos.xls
#chgrp -R www-data demo8/webapp/modulos/cont/Formato_presupuestos.xls
#
#
#echo '[demo24]'
#cp _base/webapp/modulos/cont/controllers/reports.php demo24/webapp/modulos/cont/controllers/reports.php
#cp _base/webapp/modulos/cont/models/reports.php demo24/webapp/modulos/cont/models/reports.php
#cp _base/webapp/modulos/cont/models/presupuesto.php demo24/webapp/modulos/cont/models/presupuesto.php
#cp _base/webapp/modulos/cont/controllers/presupuesto.php demo24/webapp/modulos/cont/controllers/presupuesto.php
#cp _base/webapp/modulos/cont/js/agregar.js demo24/webapp/modulos/cont/js/agregar.js
#cp _base/webapp/modulos/cont/views/presupuestal/index.php demo24/webapp/modulos/cont/views/presupuestal/index.php
#cp _base/webapp/modulos/cont/views/reports/estadoResultadosReportePresup.php demo24/webapp/modulos/cont/views/reports/estadoResultadosReportePresup.php
#cp _base/webapp/modulos/cont/importar/import_presupuesto.php demo24/webapp/modulos/cont/importar/import_presupuesto.php
#cp _base/webapp/modulos/cont/importar/validaciones_presupuesto.php demo24/webapp/modulos/cont/importar/validaciones_presupuesto.php
#cp _base/webapp/modulos/cont/Formato_presupuestos.xls demo24/webapp/modulos/cont/Formato_presupuestos.xls
#
#chmod -R 777 demo24/webapp/modulos/cont/importar/import_presupuesto.php
#chown -R www-data demo24/webapp/modulos/cont/importar/import_presupuesto.php
#chgrp -R www-data demo24/webapp/modulos/cont/importar/import_presupuesto.php
#
#chmod -R 777 demo24/webapp/modulos/cont/importar/validaciones_presupuesto.php
#chown -R www-data demo24/webapp/modulos/cont/importar/validaciones_presupuesto.php
#chgrp -R www-data demo24/webapp/modulos/cont/importar/validaciones_presupuesto.php
#
#chmod -R 777 demo24/webapp/modulos/cont/Formato_presupuestos.xls
#chown -R www-data demo24/webapp/modulos/cont/Formato_presupuestos.xls
#chgrp -R www-data demo24/webapp/modulos/cont/Formato_presupuestos.xls
#
#
#echo '[demo32]'
#cp _base/webapp/modulos/cont/controllers/reports.php demo32/webapp/modulos/cont/controllers/reports.php
#cp _base/webapp/modulos/cont/models/reports.php demo32/webapp/modulos/cont/models/reports.php
#cp _base/webapp/modulos/cont/models/presupuesto.php demo32/webapp/modulos/cont/models/presupuesto.php
#cp _base/webapp/modulos/cont/controllers/presupuesto.php demo32/webapp/modulos/cont/controllers/presupuesto.php
#cp _base/webapp/modulos/cont/js/agregar.js demo32/webapp/modulos/cont/js/agregar.js
#cp _base/webapp/modulos/cont/views/presupuestal/index.php demo32/webapp/modulos/cont/views/presupuestal/index.php
#cp _base/webapp/modulos/cont/views/reports/estadoResultadosReportePresup.php demo32/webapp/modulos/cont/views/reports/estadoResultadosReportePresup.php
#cp _base/webapp/modulos/cont/importar/import_presupuesto.php demo32/webapp/modulos/cont/importar/import_presupuesto.php
#cp _base/webapp/modulos/cont/importar/validaciones_presupuesto.php demo32/webapp/modulos/cont/importar/validaciones_presupuesto.php
#cp _base/webapp/modulos/cont/Formato_presupuestos.xls demo32/webapp/modulos/cont/Formato_presupuestos.xls
#
#chmod -R 777 demo32/webapp/modulos/cont/importar/import_presupuesto.php
#chown -R www-data demo32/webapp/modulos/cont/importar/import_presupuesto.php
#chgrp -R www-data demo32/webapp/modulos/cont/importar/import_presupuesto.php
#
#chmod -R 777 demo32/webapp/modulos/cont/importar/validaciones_presupuesto.php
#chown -R www-data demo32/webapp/modulos/cont/importar/validaciones_presupuesto.php
#chgrp -R www-data demo32/webapp/modulos/cont/importar/validaciones_presupuesto.php
#
#chmod -R 777 demo32/webapp/modulos/cont/Formato_presupuestos.xls
#chown -R www-data demo32/webapp/modulos/cont/Formato_presupuestos.xls
#chgrp -R www-data demo32/webapp/modulos/cont/Formato_presupuestos.xls
#
#
#
### Facturación | Omar Vazquez | Notas 2016-01-05 17:01
#
#cp _base/webapp/modulos/facturacion/descargaNotas.php sistema/webapp/modulos/facturacion/descargaNotas.php
#
#mkdir sistema/webapp/modulos/facturas/notas/
#chmod -R 777 sistema/webapp/modulos/facturas/notas/
#chown -R www-data sistema/webapp/modulos/facturas/notas/
#chgrp -R www-data sistema/webapp/modulos/facturas/notas/


### Acontia | Carmen | Permisos
#chmod -R 777 /srv/www/htdocs/clientes/sistema/webapp/modulos/cont/xmls/facturas/temporales
#chown -R www-data /srv/www/htdocs/clientes/sistema/webapp/modulos/cont/xmls/facturas/temporales
#chgrp -R www-data /srv/www/htdocs/clientes/sistema/webapp/modulos/cont/xmls/facturas/temporales

### Foodware | Fer | 2016-01-05 11:35
#echo '[ebustamante]'
#cp _base/webapp/modulos/restaurantes/views/comandas/Gmesas.php ebustamante/webapp/modulos/restaurantes/views/comandas/Gmesas.php
#echo '[lantigua]'
#cp _base/webapp/modulos/restaurantes/views/comandas/Gmesas.php lantigua/webapp/modulos/restaurantes/views/comandas/Gmesas.php
#echo '[gourmet]'
#cp _base/webapp/modulos/restaurantes/views/comandas/Gmesas.php gourmet/webapp/modulos/restaurantes/views/comandas/Gmesas.php


### Foodware | Fer | 2016-01-05 11:02
#
#echo '[ebustamante]'
#cp _base/webapp/modulos/restaurantes/libraries/getcontrollers.php ebustamante/webapp/modulos/restaurantes/libraries/getcontrollers.php
#cp _base/webapp/modulos/restaurantes/models/comandas.php ebustamante/webapp/modulos/restaurantes/models/comandas.php
#cp _base/webapp/modulos/restaurantes/views/comandas/Gmesas.php ebustamante/webapp/modulos/restaurantes/views/comandas/Gmesas.php
#cp _base/webapp/modulos/restaurantes/views/comandas/comanda.php ebustamante/webapp/modulos/restaurantes/views/comandas/comanda.php
#cp _base/webapp/modulos/restaurantes/views/comandas/imprimecomandas.php ebustamante/webapp/modulos/restaurantes/views/comandas/imprimecomandas.php
#
#echo '[gourmet]'
#cp _base/webapp/modulos/restaurantes/libraries/getcontrollers.php gourmet/webapp/modulos/restaurantes/libraries/getcontrollers.php
#cp _base/webapp/modulos/restaurantes/models/comandas.php gourmet/webapp/modulos/restaurantes/models/comandas.php
#cp _base/webapp/modulos/restaurantes/views/comandas/Gmesas.php gourmet/webapp/modulos/restaurantes/views/comandas/Gmesas.php
#cp _base/webapp/modulos/restaurantes/views/comandas/comanda.php gourmet/webapp/modulos/restaurantes/views/comandas/comanda.php
#cp _base/webapp/modulos/restaurantes/views/comandas/imprimecomandas.php gourmet/webapp/modulos/restaurantes/views/comandas/imprimecomandas.php


### Appministra | Omar Vazquez | 2016-01-04 11:14
#
#echo '[ebustamante]'
#cp _base/webapp/modulos/punto_venta_nuevo/controllers/caja.php ebustamante/webapp/modulos/punto_venta_nuevo/controllers/caja.php
#cp _base/webapp/modulos/punto_venta_nuevo/js/caja/caja.js ebustamante/webapp/modulos/punto_venta_nuevo/js/caja/caja.js
#cp _base/webapp/modulos/punto_venta_nuevo/models/caja/caja.php ebustamante/webapp/modulos/punto_venta_nuevo/models/caja/caja.php
#cp _base/webapp/modulos/punto_venta_nuevo/views/caja/caja.php ebustamante/webapp/modulos/punto_venta_nuevo/views/caja/caja.php


### Appministra | Omar Vazquez | 2015-12-29 14:06
#
#echo "[sistema]"
#cp _base/webapp/modulos/facturacion/reporteFacturas.php sistema/webapp/modulos/facturacion/reporteFacturas.php

### Appministra | Omar Vazquez | 2015-12-29 12:41
#
#echo "[sistema]"
#cp _base/webapp/modulos/facturacion/reporteFacturas.php sistema/webapp/modulos/facturacion/reporteFacturas.php

### Foodware | Fer de la Cruz | 2015-12-28 17:50

#cp -r _base/webapp/modulos/restaurantes buensazon/webapp/modulos/

#chmod -R 777 buensazon/webapp/modulos/restaurantes
#chown -R www-data buensazon/webapp/modulos/restaurantes
#chgrp -R www-data buensazon/webapp/modulos/restaurantes


### Acontia | Ivan Cuenca | Revisión de permisos | 2015-12-28 17:50

#chmod -R 777 demo8/webapp/modulos/cont/controllers/*
#chmod -R 777 demo8/webapp/modulos/cont/models/*
#chmod -R 777 demo8/webapp/modulos/cont/views/presupuestal/*
#
#chown -R www-data demo8/webapp/modulos/cont/controllers/*
#chown -R www-data demo8/webapp/modulos/cont/models/*
#chown -R www-data demo8/webapp/modulos/cont/views/presupuestal/*
#
#chgrp -R www-data demo8/webapp/modulos/cont/controllers/*
#chgrp -R www-data demo8/webapp/modulos/cont/models/*
#chgrp -R www-data demo8/webapp/modulos/cont/views/presupuestal/*
#
#
#chmod -R 777 demo24/webapp/modulos/cont/controllers/*
#chmod -R 777 demo24/webapp/modulos/cont/models/*
#chmod -R 777 demo24/webapp/modulos/cont/views/presupuestal/*
#
#chown -R www-data demo24/webapp/modulos/cont/controllers/*
#chown -R www-data demo24/webapp/modulos/cont/models/*
#chown -R www-data demo24/webapp/modulos/cont/views/presupuestal/*
#
#chgrp -R www-data demo24/webapp/modulos/cont/controllers/*
#chgrp -R www-data demo24/webapp/modulos/cont/models/*
#chgrp -R www-data demo24/webapp/modulos/cont/views/presupuestal/*
#
#
#chmod -R 777 demo32/webapp/modulos/cont/controllers/*
#chmod -R 777 demo32/webapp/modulos/cont/models/*
#chmod -R 777 demo32/webapp/modulos/cont/views/presupuestal/*
#
#chown -R www-data demo32/webapp/modulos/cont/controllers/*
#chown -R www-data demo32/webapp/modulos/cont/models/*
#chown -R www-data demo32/webapp/modulos/cont/views/presupuestal/*
#
#chgrp -R www-data demo32/webapp/modulos/cont/controllers/*
#chgrp -R www-data demo32/webapp/modulos/cont/models/*
#chgrp -R www-data demo32/webapp/modulos/cont/views/presupuestal/*
#

### Acontia| Ivan Cuenca | 2015-12-28 14:41
##echo '[demo8]'
#cp _base/webapp/modulos/cont/controllers/presupuesto.php demo8/webapp/modulos/cont/controllers/presupuesto.php
#cp _base/webapp/modulos/cont/controllers/reports.php demo8/webapp/modulos/cont/controllers/reports.php
#cp _base/webapp/modulos/cont/models/Presupuesto.php demo8/webapp/modulos/cont/models/Presupuesto.php
#cp _base/webapp/modulos/cont/models/reports.php demo8/webapp/modulos/cont/models/reports.php
#cp _base/webapp/modulos/cont/views/presupuestal/index.php demo8/webapp/modulos/cont/views/presupuestal/index.php
#cp _base/webapp/modulos/cont/views/reports/movcuentas_despues.php demo8/webapp/modulos/cont/views/reports/movcuentas_despues.php
#
#echo '[demo24]'
#cp _base/webapp/modulos/cont/controllers/presupuesto.php demo24/webapp/modulos/cont/controllers/presupuesto.php
#cp _base/webapp/modulos/cont/controllers/reports.php demo24/webapp/modulos/cont/controllers/reports.php
##cp _base/webapp/modulos/cont/models/Presupuesto.php demo24/webapp/modulos/cont/models/Presupuesto.php
#cp _base/webapp/modulos/cont/models/reports.php demo24/webapp/modulos/cont/models/reports.php
#cp _base/webapp/modulos/cont/views/presupuestal/index.php demo24/webapp/modulos/cont/views/presupuestal/index.php
#cp _base/webapp/modulos/cont/views/reports/movcuentas_despues.php demo24/webapp/modulos/cont/views/reports/movcuentas_despues.php
#
#echo '[demo32]'
#cp _base/webapp/modulos/cont/controllers/presupuesto.php demo32/webapp/modulos/cont/controllers/presupuesto.php
#cp _base/webapp/modulos/cont/controllers/reports.php demo32/webapp/modulos/cont/controllers/reports.php
#cp _base/webapp/modulos/cont/models/Presupuesto.php demo32/webapp/modulos/cont/models/Presupuesto.php
#cp _base/webapp/modulos/cont/models/reports.php demo32/webapp/modulos/cont/models/reports.php
#cp _base/webapp/modulos/cont/views/presupuestal/index.php demo32/webapp/modulos/cont/views/presupuestal/index.php
#cp _base/webapp/modulos/cont/views/reports/movcuentas_despues.php demo32/webapp/modulos/cont/views/reports/movcuentas_despues.php

### Bancos | Carmen | 2015-12-23 18:30
#echo '[demo2]'
#cp -r _base/webapp/modulos/bancos demo2/webapp/modulos/
#
#chmod -R 777 demo2/webapp/modulos/bancos
#chown -R www-data demo2/webapp/modulos/bancos
#chgrp -R www-data demo2/webapp/modulos/bancos
#

### Acontia| Ivan Cuenca | 2015-12-17 18:17
#echo '[demo8]'
#cp _base/webapp/modulos/cont/models/reports.php demo8/webapp/modulos/cont/models/reports.php
#cp _base/webapp/modulos/cont/controllers/reports.php demo8/webapp/modulos/cont/controllers/reports.php
#cp _base/webapp/modulos/cont/views/reports/balanceGeneral.php demo8/webapp/modulos/cont/views/reports/balanceGeneral.php
#cp _base/webapp/modulos/cont/views/reports/estadoResultadosReportePresup.php demo8/webapp/modulos/cont/views/reports/estadoResultadosReportePresup.php
#cp _base/webapp/modulos/cont/views/presupuestal/index.php demo8/webapp/modulos/cont/views/presupuestal/index.php
#cp _base/webapp/modulos/cont/models/Presupuesto.php demo8/webapp/modulos/cont/models/Presupuesto.php
#cp _base/webapp/modulos/cont/images/plus.png demo8/webapp/modulos/cont/images/plus.png
#cp _base/webapp/modulos/cont/controllers/presupuesto.php demo8/webapp/modulos/cont/controllers/presupuesto.php
#cp _base/webapp/modulos/cont/models/captpolizas.php demo8/webapp/modulos/cont/models/captpolizas.php
#
#echo '[demo24]'
#cp _base/webapp/modulos/cont/models/reports.php demo24/webapp/modulos/cont/models/reports.php
#cp _base/webapp/modulos/cont/controllers/reports.php demo24/webapp/modulos/cont/controllers/reports.php
#cp _base/webapp/modulos/cont/views/reports/balanceGeneral.php demo24/webapp/modulos/cont/views/reports/balanceGeneral.php
#cp _base/webapp/modulos/cont/views/reports/estadoResultadosReportePresup.php demo24/webapp/modulos/cont/views/reports/estadoResultadosReportePresup.php
#cp _base/webapp/modulos/cont/views/presupuestal/index.php demo24/webapp/modulos/cont/views/presupuestal/index.php
#cp _base/webapp/modulos/cont/models/Presupuesto.php demo24/webapp/modulos/cont/models/Presupuesto.php
#cp _base/webapp/modulos/cont/images/plus.png demo24/webapp/modulos/cont/images/plus.png
#cp _base/webapp/modulos/cont/controllers/presupuesto.php demo24/webapp/modulos/cont/controllers/presupuesto.php
#cp _base/webapp/modulos/cont/models/captpolizas.php demo24/webapp/modulos/cont/models/captpolizas.php
#
#echo '[demo32]'
#cp _base/webapp/modulos/cont/models/reports.php demo32/webapp/modulos/cont/models/reports.php
#cp _base/webapp/modulos/cont/controllers/reports.php demo32/webapp/modulos/cont/controllers/reports.php
#cp _base/webapp/modulos/cont/views/reports/balanceGeneral.php demo32/webapp/modulos/cont/views/reports/balanceGeneral.php
#cp _base/webapp/modulos/cont/views/reports/estadoResultadosReportePresup.php demo32/webapp/modulos/cont/views/reports/estadoResultadosReportePresup.php
#cp _base/webapp/modulos/cont/views/presupuestal/index.php demo32/webapp/modulos/cont/views/presupuestal/index.php
#cp _base/webapp/modulos/cont/models/Presupuesto.php demo32/webapp/modulos/cont/models/Presupuesto.php
#cp _base/webapp/modulos/cont/images/plus.png demo32/webapp/modulos/cont/images/plus.png
#cp _base/webapp/modulos/cont/controllers/presupuesto.php demo32/webapp/modulos/cont/controllers/presupuesto.php
#cp _base/webapp/modulos/cont/models/captpolizas.php demo32/webapp/modulos/cont/models/captpolizas.php



### Foodware | Fer de la Cruz | 2015-12-17 18:04
#echo '[gourmet]'
#cp _base/webapp/modulos/restaurantes/models/comandas.php gourmet/webapp/modulos/restaurantes/models/comandas.php
#cp _base/webapp/modulos/restaurantes/views/comandas/imprimecomandas.php gourmet/webapp/modulos/restaurantes/views/comandas/imprimecomandas.php
#
#echo '[ebustamante]'
#cp _base/webapp/modulos/restaurantes/models/comandas.php ebustamante/webapp/modulos/restaurantes/models/comandas.php
#cp _base/webapp/modulos/restaurantes/views/comandas/imprimecomandas.php ebustamante/webapp/modulos/restaurantes/views/comandas/imprimecomandas.php


### Foodware | Fer de la Cruz | 2015-12-14 17:37
#
#echo '[ebustamante]'
#cp _base/webapp/modulos/restaurantes/controllers/comandas.php ebustamante/webapp/modulos/restaurantes/controllers/comandas.php
#cp _base/webapp/modulos/restaurantes/models/comandas.php ebustamante/webapp/modulos/restaurantes/models/comandas.php
#cp _base/webapp/modulos/restaurantes/views/comandas/comanda.php ebustamante/webapp/modulos/restaurantes/views/comandas/comanda.php
#
#echo '[gourmet]'
#cp _base/webapp/modulos/restaurantes/controllers/comandas.php gourmet/webapp/modulos/restaurantes/controllers/comandas.php
#cp _base/webapp/modulos/restaurantes/models/comandas.php gourmet/webapp/modulos/restaurantes/models/comandas.php
#cp _base/webapp/modulos/restaurantes/views/comandas/comanda.php gourmet/webapp/modulos/restaurantes/views/comandas/comanda.php
#

### Foodware | Fer de la Cruz | 2015-12-14 11:52
#echo '[ebustamante]'
#cp _base/webapp/modulos/restaurantes/views/comandas/comanda.php ebustamante/webapp/modulos/restaurantes/views/comandas/comanda.php


### Appministra | Omar Vazquez | 2015-12-14 11:42
#echo '[ebustamante]'
#cp _base/webapp/modulos/punto_venta_nuevo/controllers/caja.php ebustamante/webapp/modulos/punto_venta_nuevo/controllers/caja.php
#cp _base/webapp/modulos/punto_venta_nuevo/models/caja/caja.php ebustamante/webapp/modulos/punto_venta_nuevo/models/caja/caja.php
#cp _base/webapp/modulos/punto_venta_nuevo/views/caja/caja.php ebustamante/webapp/modulos/punto_venta_nuevo/views/caja/caja.php
#cp _base/webapp/modulos/punto_venta_nuevo/js/caja/caja.js ebustamante/webapp/modulos/punto_venta_nuevo/js/caja/caja.js

### Foodware | Fer de la Cruz | 2015-12-11 14:15
#cp _base/webapp/modulos/restaurantes/views/comandas/comanda.php ebustamante/webapp/modulos/restaurantes/views/comandas/comanda.php


### Foodware | Fer de la Cruz | 2015-12-11 12:57

#echo '[gourmet]'
#cp _base/webapp/modulos/mrp/application/views/product/form.php gourmet/webapp/modulos/mrp/application/views/product/form.php
#cp _base/webapp/modulos/restaurantes/controllers/comandas.php gourmet/webapp/modulos/restaurantes/controllers/comandas.php
#cp _base/webapp/modulos/restaurantes/models/comandas.php gourmet/webapp/modulos/restaurantes/models/comandas.php
#cp _base/webapp/modulos/restaurantes/views/comandas/comanda.php gourmet/webapp/modulos/restaurantes/views/comandas/comanda.php
#cp _base/webapp/modulos/restaurantes/views/comandas/vista_productos.php gourmet/webapp/modulos/restaurantes/views/comandas/vista_productos.php
#
#echo '[ebustamante]'
#cp _base/webapp/modulos/mrp/application/views/product/form.php ebustamante/webapp/modulos/mrp/application/views/product/form.php
#cp _base/webapp/modulos/restaurantes/controllers/comandas.php ebustamante/webapp/modulos/restaurantes/controllers/comandas.php
#cp _base/webapp/modulos/restaurantes/models/comandas.php ebustamante/webapp/modulos/restaurantes/models/comandas.php
#cp _base/webapp/modulos/restaurantes/views/comandas/comanda.php ebustamante/webapp/modulos/restaurantes/views/comandas/comanda.php
#cp _base/webapp/modulos/restaurantes/views/comandas/vista_productos.php ebustamante/webapp/modulos/restaurantes/views/comandas/vista_productos.php
#


### Acontia | Ivan Cuenca | 2015-12-07 18:07
#
#echo '[demo8]'
#cp _base/webapp/modulos/cont/views/reports/navegadorXMLs_facturas.php demo8/webapp/modulos/cont/views/reports/navegadorXMLs_facturas.php
#cp _base/webapp/modulos/cont/models/accountsTree.php demo8/webapp/modulos/cont/models/accountsTree.php
#cp _base/webapp/modulos/cont/models/reports.php demo8/webapp/modulos/cont/models/reports.php
#
#echo '[demo24]'
#cp _base/webapp/modulos/cont/views/reports/navegadorXMLs_facturas.php demo24/webapp/modulos/cont/views/reports/navegadorXMLs_facturas.php
#cp _base/webapp/modulos/cont/models/accountsTree.php demo24/webapp/modulos/cont/models/accountsTree.php
#cp _base/webapp/modulos/cont/models/reports.php demo24/webapp/modulos/cont/models/reports.php
#
#echo '[demo32]'
#cp _base/webapp/modulos/cont/views/reports/navegadorXMLs_facturas.php demo32/webapp/modulos/cont/views/reports/navegadorXMLs_facturas.php
#cp _base/webapp/modulos/cont/models/accountsTree.php demo32/webapp/modulos/cont/models/accountsTree.php
#cp _base/webapp/modulos/cont/models/reports.php demo32/webapp/modulos/cont/models/reports.php
#
#echo '[ccpg]'
#cp _base/webapp/modulos/cont/views/reports/navegadorXMLs_facturas.php ccpg/webapp/modulos/cont/views/reports/navegadorXMLs_facturas.php
#cp _base/webapp/modulos/cont/models/accountsTree.php ccpg/webapp/modulos/cont/models/accountsTree.php
#cp _base/webapp/modulos/cont/models/reports.php ccpg/webapp/modulos/cont/models/reports.php



### Prontipagos | Mario | 2015-11-30 13:59

#echo '[ebustamante]'
#cp _base/webapp/modulos/prontipagos/setsettings.php ebustamante/webapp/modulos/prontipagos/setsettings.php
#cp _base/webapp/modulos/prontipagos/procsell.php ebustamante/webapp/modulos/prontipagos/procsell.php
#cp _base/webapp/modulos/prontipagos/inc/curl.php ebustamante/webapp/modulos/prontipagos/inc/curl.php
#cp _base/webapp/modulos/prontipagos/getproducts.php ebustamante/webapp/modulos/prontipagos/getproducts.php
#cp _base/webapp/modulos/prontipagos/getproduct.php ebustamante/webapp/modulos/prontipagos/getproduct.php
#cp _base/webapp/modulos/prontipagos/getbalance.php ebustamante/webapp/modulos/prontipagos/getbalance.php
#cp _base/webapp/modulos/prontipagos/sell.php ebustamante/webapp/modulos/prontipagos/sell.php


### Prontipagos | Mario | 2015-11-30 12:17
#echo '[ebustamante]'
#cp _base/webapp/modulos/prontipagos/setsettings.php ebustamante/webapp/modulos/prontipagos/setsettings.php
#cp _base/webapp/modulos/prontipagos/procsell.php ebustamante/webapp/modulos/prontipagos/procsell.php
#cp _base/webapp/modulos/prontipagos/inc/curl.php ebustamante/webapp/modulos/prontipagos/inc/curl.php
#cp _base/webapp/modulos/prontipagos/getproducts.php ebustamante/webapp/modulos/prontipagos/getproducts.php
#cp _base/webapp/modulos/prontipagos/getproduct.php ebustamante/webapp/modulos/prontipagos/getproduct.php
#cp _base/webapp/modulos/prontipagos/getbalance.php ebustamante/webapp/modulos/prontipagos/getbalance.php
#cp _base/webapp/modulos/prontipagos/sell.php ebustamante/webapp/modulos/prontipagos/sell.php


### Prontipagos | Mario | 2015-11-30 11:25
#
#echo '[ebustamante]'
#cp _base/webapp/modulos/prontipagos/setsettings.php ebustamante/webapp/modulos/prontipagos/setsettings.php
#cp _base/webapp/modulos/prontipagos/procsell.php ebustamante/webapp/modulos/prontipagos/procsell.php
#cp _base/webapp/modulos/prontipagos/inc/curl.php ebustamante/webapp/modulos/prontipagos/inc/curl.php
#cp _base/webapp/modulos/prontipagos/getproducts.php ebustamante/webapp/modulos/prontipagos/getproducts.php
#cp _base/webapp/modulos/prontipagos/getproduct.php ebustamante/webapp/modulos/prontipagos/getproduct.php
#cp _base/webapp/modulos/prontipagos/getbalance.php ebustamante/webapp/modulos/prontipagos/getbalance.php
#
### Prontipagos | Mario | 2015-11-26 14:12
#
#echo '[ebustamante]'
#cp _base/webapp/modulos/prontipagos/procsell.php ebustamante/webapp/modulos/prontipagos/procsell.php
#cp _base/webapp/modulos/prontipagos/setsettings.php ebustamante/webapp/modulos/prontipagos/setsettings.php
#cp _base/webapp/modulos/prontipagos/getproducts.php ebustamante/webapp/modulos/prontipagos/getproducts.php

### Acontia | Carmen | 2015-11-25 13:33
#
#echo '[demo24]'
#cp _base/webapp/modulos/cont/models/automaticasmonedaext.php demo24/webapp/modulos/cont/models/automaticasmonedaext.php
#cp _base/webapp/modulos/cont/views/captpolizas/monedaext/pagoext.php demo24/webapp/modulos/cont/views/captpolizas/monedaext/pagoext.php

### Acontia | Carmen | 2015-11-25 12:23

#echo '[demo24]'
#cp _base/webapp/modulos/cont/controllers/ajustecambiario.php demo24/webapp/modulos/cont/controllers/ajustecambiario.php
#cp _base/webapp/modulos/cont/controllers/automaticasmonedaext.php demo24/webapp/modulos/cont/controllers/automaticasmonedaext.php
#cp _base/webapp/modulos/cont/controllers/captpolizas.php demo24/webapp/modulos/cont/controllers/captpolizas.php
#cp _base/webapp/modulos/cont/models/automaticasmonedaext.php demo24/webapp/modulos/cont/models/automaticasmonedaext.php
#cp _base/webapp/modulos/cont/views/partial/top.php demo24/webapp/modulos/cont/views/partial/top.php
#cp _base/webapp/modulos/cont_repolog/catalog_clientes.php demo24/webapp/modulos/cont_repolog/catalog_clientes.php
#cp _base/webapp/modulos/mrp/guardavariable.php demo24/webapp/modulos/mrp/guardavariable.php


### Acontia | Carmen | 2015-11-24 13:50

#echo '[demo24]'
#cp _base/webapp/modulos/cont/images/dine.jpeg demo24/webapp/modulos/cont/images/dine.jpeg
#cp _base/webapp/modulos/cont/images/intro.png demo24/webapp/modulos/cont/images/intro.png
#cp _base/webapp/modulos/cont/images/mas.jpeg demo24/webapp/modulos/cont/images/mas.jpeg
#cp _base/webapp/modulos/cont/controllers/automaticasmonedaext.php demo24/webapp/modulos/cont/controllers/automaticasmonedaext.php
#cp _base/webapp/modulos/cont/js/cobroext.js demo24/webapp/modulos/cont/js/cobroext.js
#cp _base/webapp/modulos/cont/js/pagoext.js demo24/webapp/modulos/cont/js/pagoext.js
#cp _base/webapp/modulos/cont/views/captpolizas/monedaext/cobroext.php demo24/webapp/modulos/cont/views/captpolizas/monedaext/cobroext.php
#cp _base/webapp/modulos/cont/views/captpolizas/monedaext/pagoext.php demo24/webapp/modulos/cont/views/captpolizas/monedaext/pagoext.php
#cp _base/webapp/modulos/cont/views/captpolizas/polizacliente.php demo24/webapp/modulos/cont/views/captpolizas/polizacliente.php


### Acontia | Carmen | 2015-11-24 12:53
#
#echo '[ccpg]'
#cp _base/webapp/modulos/cont/controllers/captpolizas.php ccpg/webapp/modulos/cont/controllers/captpolizas.php
#
#echo '[demo8]'
#cp _base/webapp/modulos/cont/controllers/captpolizas.php demo8/webapp/modulos/cont/controllers/captpolizas.php
#
#echo '[demo24]'
#cp _base/webapp/modulos/cont/controllers/captpolizas.php demo24/webapp/modulos/cont/controllers/captpolizas.php
#
#echo '[demo32]'
#cp _base/webapp/modulos/cont/controllers/captpolizas.php demo32/webapp/modulos/cont/controllers/captpolizas.php



### Acontia | Ivan Cuenca | 2015-11-24

#echo '[ccpg]'
#cp _base/webapp/modulos/cont/Formato_polizas2.xls ccpg/webapp/modulos/cont/Formato_polizas2.xls
#cp _base/webapp/modulos/cont/controllers/captpolizas.php ccpg/webapp/modulos/cont/controllers/captpolizas.php
#cp _base/webapp/modulos/cont/importar/import_polizas.php ccpg/webapp/modulos/cont/importar/import_polizas.php
#cp _base/webapp/modulos/cont/views/captpolizas/verpolizas.php ccpg/webapp/modulos/cont/views/captpolizas/verpolizas.php


### Appministra | Omar Vazquez | 2015-11-23
#echo '[ebustamante]'
#cp _base/webapp/modulos/punto_venta_nuevo/js/caja/caja.js ebustamante/webapp/modulos/punto_venta_nuevo/js/caja/caja.js
#cp _base/webapp/modulos/punto_venta_nuevo/models/caja/caja.php ebustamante/webapp/modulos/punto_venta_nuevo/models/caja/caja.php
#cp _base/webapp/modulos/punto_venta_nuevo/ticket.php ebustamante/webapp/modulos/punto_venta_nuevo/ticket.php
#cp _base/webapp/modulos/punto_venta_nuevo/controllers/caja.php ebustamante/webapp/modulos/punto_venta_nuevo/controllers/caja.php
#cp _base/webapp/modulos/punto_venta_nuevo/views/caja/caja.php ebustamante/webapp/modulos/punto_venta_nuevo/views/caja/caja.php


# Acontia | Carmen | 2015-11-23 15:57
#
#echo '[demo24]'
#cp _base/webapp/modulos/cont/controllers/ajustecambiario.php demo24/webapp/modulos/cont/controllers/ajustecambiario.php
#cp _base/webapp/modulos/cont/js/funcionesPolizas.js demo24/webapp/modulos/cont/js/funcionesPolizas.js
#cp _base/webapp/modulos/cont/views/captpolizas/actpolizas.php demo24/webapp/modulos/cont/views/captpolizas/actpolizas.php

### Acontia | Ivan Cuenca | 2015-11-23 15:54
#echo '[demo8]'
#cp _base/webapp/modulos/cont/importar/import_polizas.php demo8/webapp/modulos/cont/importar/import_polizas.php
#
#echo '[demo24]'
#cp _base/webapp/modulos/cont/importar/import_polizas.php demo24/webapp/modulos/cont/importar/import_polizas.php
#
#echo '[demo32]'
#cp _base/webapp/modulos/cont/importar/import_polizas.php demo32/webapp/modulos/cont/importar/import_polizas.php


### Foodware | Fer | 2015-11-23 10:38

#echo '[lantigua]'
#cp _base/webapp/modulos/restaurantes/controllers/pedidosactivos.php lantigua/webapp/modulos/restaurantes/controllers/pedidosactivos.php
#cp _base/webapp/modulos/restaurantes/js/pedidos/pedidos.js lantigua/webapp/modulos/restaurantes/js/pedidos/pedidos.js
#cp _base/webapp/modulos/restaurantes/models/comandas.php lantigua/webapp/modulos/restaurantes/models/comandas.php
#cp _base/webapp/modulos/restaurantes/models/pedidosactivos.php lantigua/webapp/modulos/restaurantes/models/pedidosactivos.php
#cp _base/webapp/modulos/restaurantes/views/comandas/comanda.php lantigua/webapp/modulos/restaurantes/views/comandas/comanda.php
#
#echo '[ebustamante]'
#cp _base/webapp/modulos/restaurantes/controllers/pedidosactivos.php ebustamante/webapp/modulos/restaurantes/controllers/pedidosactivos.php
#cp _base/webapp/modulos/restaurantes/js/pedidos/pedidos.js ebustamante/webapp/modulos/restaurantes/js/pedidos/pedidos.js
#cp _base/webapp/modulos/restaurantes/models/comandas.php ebustamante/webapp/modulos/restaurantes/models/comandas.php
#cp _base/webapp/modulos/restaurantes/models/pedidosactivos.php ebustamante/webapp/modulos/restaurantes/models/pedidosactivos.php
#cp _base/webapp/modulos/restaurantes/views/comandas/comanda.php ebustamante/webapp/modulos/restaurantes/views/comandas/comanda.php
#
#echo '[gourmet]'
#cp _base/webapp/modulos/restaurantes/controllers/pedidosactivos.php gourmet/webapp/modulos/restaurantes/controllers/pedidosactivos.php
#cp _base/webapp/modulos/restaurantes/js/pedidos/pedidos.js gourmet/webapp/modulos/restaurantes/js/pedidos/pedidos.js
#cp _base/webapp/modulos/restaurantes/models/comandas.php gourmet/webapp/modulos/restaurantes/models/comandas.php
#cp _base/webapp/modulos/restaurantes/models/pedidosactivos.php gourmet/webapp/modulos/restaurantes/models/pedidosactivos.php
#cp _base/webapp/modulos/restaurantes/views/comandas/comanda.php gourmet/webapp/modulos/restaurantes/views/comandas/comanda.php



### Appministra | Omar Vazquez | 2015-11-20 18:08
#
#echo '[ebustamante]'
#
#mkdir ebustamante/webapp/modulos/inadem/views/lote/
#chmod -R 777 ebustamante/webapp/modulos/inadem/views/lote/
#chown -R www-data ebustamante/webapp/modulos/inadem/views/lote/
#chgrp -R www-data ebustamante/webapp/modulos/inadem/views/lote/
#
#cp _base/webapp/modulos/cotizaciones/js/pedido/pedido.js ebustamante/webapp/modulos/cotizaciones/js/pedido/pedido.js
#cp _base/webapp/modulos/cotizaciones/models/pedido/pedido.php ebustamante/webapp/modulos/cotizaciones/models/pedido/pedido.php
#cp _base/webapp/modulos/inadem/views/lote/lote.php ebustamante/webapp/modulos/inadem/views/lote/lote.php
#cp _base/webapp/modulos/inadem/views/lote/lote.php ebustamante/webapp/modulos/inadem/views/lote/lote.php
#cp _base/webapp/modulos/inadem/views/lote/loteForm.php ebustamante/webapp/modulos/inadem/views/lote/loteForm.php
#
#
### Appministra | Omar Vazquez | 2015-11-20 14:33
#echo '[ebustamante]'
#cp -r _base/webapp/modulos/cotizaciones ebustamante/webapp/modulos/
#cp _base/webapp/modulos/SAT/PDF/COTIZACIONESPDF.php ebustamante/webapp/modulos/SAT/PDF/COTIZACIONESPDF.php
#
#chmod -R 777 ebustamante/webapp/modulos/cotizaciones
#chown -R www-data ebustamante/webapp/modulos/cotizaciones
#chgrp -R www-data ebustamante/webapp/modulos/cotizaciones


### Foodware | Fer | 2015-11-20 10:26
#echo '[lantigua]'
#cp _base/webapp/modulos/restaurantes/views/comandas/Gmesas.php lantigua/webapp/modulos/restaurantes/views/comandas/Gmesas.php
#cp _base/webapp/modulos/restaurantes/controllers/comandas.php lantigua/webapp/modulos/restaurantes/controllers/comandas.php
#cp _base/webapp/modulos/restaurantes/models/comandas.php lantigua/webapp/modulos/restaurantes/models/comandas.php


### Foodware | Fer | 2015-11-13 18:54
#
#echo '[ebustamante]'
#cp _base/webapp/modulos/restaurantes/controllers/comandas.php ebustamante/webapp/modulos/restaurantes/controllers/comandas.php
#cp _base/webapp/modulos/restaurantes/controllers/pedidosactivos.php ebustamante/webapp/modulos/restaurantes/controllers/pedidosactivos.php
#cp _base/webapp/modulos/restaurantes/js/comandas/comandas.js ebustamante/webapp/modulos/restaurantes/js/comandas/comandas.js
#cp _base/webapp/modulos/restaurantes/js/pedidos/pedidos.js ebustamante/webapp/modulos/restaurantes/js/pedidos/pedidos.js
#cp _base/webapp/modulos/restaurantes/models/comandas.php ebustamante/webapp/modulos/restaurantes/models/comandas.php
#cp _base/webapp/modulos/restaurantes/models/pedidosactivos.php ebustamante/webapp/modulos/restaurantes/models/pedidosactivos.php
#cp _base/webapp/modulos/restaurantes/views/comandas/comanda.php ebustamante/webapp/modulos/restaurantes/views/comandas/comanda.php
#cp _base/webapp/modulos/restaurantes/views/comandas/vista_productos.php ebustamante/webapp/modulos/restaurantes/views/comandas/vista_productos.php
#cp _base/webapp/modulos/restaurantes/views/pedidos/confpropina.php ebustamante/webapp/modulos/restaurantes/views/pedidos/confpropina.php
#
#echo '[gourmet]'
#cp _base/webapp/modulos/restaurantes/controllers/comandas.php gourmet/webapp/modulos/restaurantes/controllers/comandas.php
#cp _base/webapp/modulos/restaurantes/controllers/pedidosactivos.php gourmet/webapp/modulos/restaurantes/controllers/pedidosactivos.php
#cp _base/webapp/modulos/restaurantes/js/comandas/comandas.js gourmet/webapp/modulos/restaurantes/js/comandas/comandas.js
#cp _base/webapp/modulos/restaurantes/js/pedidos/pedidos.js gourmet/webapp/modulos/restaurantes/js/pedidos/pedidos.js
#cp _base/webapp/modulos/restaurantes/models/comandas.php gourmet/webapp/modulos/restaurantes/models/comandas.php
#cp _base/webapp/modulos/restaurantes/models/pedidosactivos.php gourmet/webapp/modulos/restaurantes/models/pedidosactivos.php
#cp _base/webapp/modulos/restaurantes/views/comandas/comanda.php gourmet/webapp/modulos/restaurantes/views/comandas/comanda.php
#cp _base/webapp/modulos/restaurantes/views/comandas/vista_productos.php gourmet/webapp/modulos/restaurantes/views/comandas/vista_productos.php
#cp _base/webapp/modulos/restaurantes/views/pedidos/confpropina.php gourmet/webapp/modulos/restaurantes/views/pedidos/confpropina.php
#
#echo '[lantigua]'
#cp _base/webapp/modulos/restaurantes/controllers/comandas.php lantigua/webapp/modulos/restaurantes/controllers/comandas.php
#cp _base/webapp/modulos/restaurantes/controllers/pedidosactivos.php lantigua/webapp/modulos/restaurantes/controllers/pedidosactivos.php
#cp _base/webapp/modulos/restaurantes/js/comandas/comandas.js lantigua/webapp/modulos/restaurantes/js/comandas/comandas.js
#cp _base/webapp/modulos/restaurantes/js/pedidos/pedidos.js lantigua/webapp/modulos/restaurantes/js/pedidos/pedidos.js
#cp _base/webapp/modulos/restaurantes/models/comandas.php lantigua/webapp/modulos/restaurantes/models/comandas.php
#cp _base/webapp/modulos/restaurantes/models/pedidosactivos.php lantigua/webapp/modulos/restaurantes/models/pedidosactivos.php
#cp _base/webapp/modulos/restaurantes/views/comandas/comanda.php lantigua/webapp/modulos/restaurantes/views/comandas/comanda.php
#cp _base/webapp/modulos/restaurantes/views/comandas/vista_productos.php lantigua/webapp/modulos/restaurantes/views/comandas/vista_productos.php
#cp _base/webapp/modulos/restaurantes/views/pedidos/confpropina.php lantigua/webapp/modulos/restaurantes/views/pedidos/confpropina.php
#
#


### Acontia | Carmen | 2015-11-13 18:32
#
#echo '[demo24]'
#cp _base/webapp/modulos/cont/controllers/conciliacionacontia.php demo24/webapp/modulos/cont/controllers/conciliacionacontia.php
#cp _base/webapp/modulos/cont/js/conciliacionacontia.js demo24/webapp/modulos/cont/js/conciliacionacontia.js
#cp _base/webapp/modulos/cont/js/funcionesPolizas.js demo24/webapp/modulos/cont/js/funcionesPolizas.js
#cp _base/webapp/modulos/cont/views/conciliacionacontia/conciliacionacontia.php demo24/webapp/modulos/cont/views/conciliacionacontia/conciliacionacontia.php
#cp _base/webapp/modulos/cont/views/conciliacionacontia/sumamovbancarios.php demo24/webapp/modulos/cont/views/conciliacionacontia/sumamovbancarios.php
#cp _base/webapp/modulos/cont/views/conciliacionacontia/volverdescartar.php demo24/webapp/modulos/cont/views/conciliacionacontia/volverdescartar.php
#cp _base/webapp/modulos/cont/controllers/automaticasmonedaext.php demo24/webapp/modulos/cont/controllers/automaticasmonedaext.php
#cp _base/webapp/modulos/cont/js/cobroext.js demo24/webapp/modulos/cont/js/cobroext.js
#cp _base/webapp/modulos/cont/js/pagoext.js demo24/webapp/modulos/cont/js/pagoext.js
#cp _base/webapp/modulos/cont/views/captpolizas/monedaext/cobroext.php demo24/webapp/modulos/cont/views/captpolizas/monedaext/cobroext.php
#cp _base/webapp/modulos/cont/views/captpolizas/monedaext/pagoext.php demo24/webapp/modulos/cont/views/captpolizas/monedaext/pagoext.php



### Appministra | Omar Vazquez | 2015-11-12 18:23

#echo '[ebustamante]'
#cp _base/webapp/modulos/punto_venta_nuevo/controllers/caja.php ebustamante/webapp/modulos/punto_venta_nuevo/controllers/caja.php
#cp _base/webapp/modulos/punto_venta_nuevo/models/caja/caja.php ebustamante/webapp/modulos/punto_venta_nuevo/models/caja/caja.php
#cp _base/webapp/modulos/punto_venta_nuevo/js/caja/caja.js ebustamante/webapp/modulos/punto_venta_nuevo/js/caja/caja.js
#cp _base/webapp/modulos/mrp/js/product.js ebustamante/webapp/modulos/mrp/js/product.js


### Acontia | Ivan Cuenca | 2015-11-12 17:46
#
#echo '[demo8]'
#cp _base/webapp/modulos/cont/models/reports.php demo8/webapp/modulos/cont/models/reports.php
#
#echo '[demo24]'
#cp _base/webapp/modulos/cont/models/reports.php demo24/webapp/modulos/cont/models/reports.php
#
#echo '[demo32]'
#cp _base/webapp/modulos/cont/models/reports.php demo32/webapp/modulos/cont/models/reports.php
#
#echo '[ccpg]'
#cp _base/webapp/modulos/cont/models/reports.php ccpg/webapp/modulos/cont/models/reports.php
#



### Acontia | Ivan Cuenca | 2015-11-12 17:20
#echo '[demo8]'
#cp _base/webapp/modulos/cont/controllers/reports.php demo8/webapp/modulos/cont/controllers/reports.php
#cp _base/webapp/modulos/cont/models/reports.php demo8/webapp/modulos/cont/models/reports.php
#
#echo '[demo24]'
#cp _base/webapp/modulos/cont/controllers/reports.php demo24/webapp/modulos/cont/controllers/reports.php
#cp _base/webapp/modulos/cont/models/reports.php demo24/webapp/modulos/cont/models/reports.php
#
#echo '[demo32]'
#cp _base/webapp/modulos/cont/controllers/reports.php demo32/webapp/modulos/cont/controllers/reports.php
#cp _base/webapp/modulos/cont/models/reports.php demo32/webapp/modulos/cont/models/reports.php
#
#echo '[ccpg]'
#cp _base/webapp/modulos/cont/controllers/reports.php ccpg/webapp/modulos/cont/controllers/reports.php
#cp _base/webapp/modulos/cont/models/reports.php ccpg/webapp/modulos/cont/models/reports.php

### Acontia | Carmen | 2015-11-12 15:42
#echo '[demo32]'
#
#cp -r _base/webapp/modulos/cont/views/conciliacionacontia/ demo32/webapp/modulos/cont/views/
#chmod -R 777 demo32/webapp/modulos/cont/views/conciliacionacontia/
#chown -R www-data demo32/webapp/modulos/cont/views/conciliacionacontia/
#chgrp -R www-data demo32/webapp/modulos/cont/views/conciliacionacontia/
#
#cp _base/webapp/modulos/cont/views/reports/reporteestadocuenta.php demo32/webapp/modulos/cont/views/reports/reporteestadocuenta.php
#cp _base/webapp/modulos/cont/controllers/conciliacionacontia.php demo32/webapp/modulos/cont/controllers/conciliacionacontia.php
#cp _base/webapp/modulos/cont/models/conciliacionacontia.php demo32/webapp/modulos/cont/models/conciliacionacontia.php
#cp _base/webapp/modulos/cont/views/conciliacionacontia/conciliacionacontia.php demo32/webapp/modulos/cont/views/conciliacionacontia/conciliacionacontia.php
#cp _base/webapp/modulos/cont/views/reports/estadocuenta.php demo32/webapp/modulos/cont/views/reports/estadocuenta.php
#cp _base/webapp/modulos/cont/views/reports/reporteestadocuenta.php demo32/webapp/modulos/cont/views/reports/reporteestadocuenta.php
#cp _base/webapp/modulos/cont/js/conciliacionacontia.js demo32/webapp/modulos/cont/js/conciliacionacontia.js
#
#cp -r _base/webapp/modulos/bancos demo32/webapp/modulos/
#chmod -R 777 demo32/webapp/modulos/bancos/
#chown -R www-data demo32/webapp/modulos/bancos/
#chgrp -R www-data demo32/webapp/modulos/bancos/


### Acontia | Carmen | 2015-11-12 15:40
#echo '[demo24]'
#cp _base/webapp/modulos/cont/controllers/conciliacionacontia.php demo24/webapp/modulos/cont/controllers/conciliacionacontia.php
#cp _base/webapp/modulos/cont/js/conciliacionacontia.js demo24/webapp/modulos/cont/js/conciliacionacontia.js
#cp _base/webapp/modulos/cont/js/poliprovisional.js demo24/webapp/modulos/cont/js/poliprovisional.js
#cp _base/webapp/modulos/cont/js/provisionext.js demo24/webapp/modulos/cont/js/provisionext.js
#cp _base/webapp/modulos/cont/views/captpolizas/monedaext/provisionextranjera.php demo24/webapp/modulos/cont/views/captpolizas/monedaext/provisionextranjera.php
#cp _base/webapp/modulos/cont/views/captpolizas/provisionmultiple.php demo24/webapp/modulos/cont/views/captpolizas/provisionmultiple.php
#cp _base/webapp/modulos/cont/views/conciliacionacontia/conciliacionacontia.php demo24/webapp/modulos/cont/views/conciliacionacontia/conciliacionacontia.php

### Acontia | Carmen | 2015-11-12 11:29

#echo '[demo24]'
#cp _base/webapp/modulos/cont/controllers/automaticasmonedaext.php demo24/webapp/modulos/cont/controllers/automaticasmonedaext.php
#cp _base/webapp/modulos/cont/js/conciliacionacontia.js demo24/webapp/modulos/cont/js/conciliacionacontia.js
#cp _base/webapp/modulos/cont/js/jquery.number.js demo24/webapp/modulos/cont/js/jquery.number.js
#cp _base/webapp/modulos/cont/views/captpolizas/monedaext/cobroext.php demo24/webapp/modulos/cont/views/captpolizas/monedaext/cobroext.php
#cp _base/webapp/modulos/cont/views/captpolizas/monedaext/pagoext.php demo24/webapp/modulos/cont/views/captpolizas/monedaext/pagoext.php
#cp _base/webapp/modulos/cont/views/captpolizas/monedaext/provisiondetalladoextranjera.php demo24/webapp/modulos/cont/views/captpolizas/monedaext/provisiondetalladoextranjera.php
#cp _base/webapp/modulos/cont/views/captpolizas/monedaext/provisionextranjera.php demo24/webapp/modulos/cont/views/captpolizas/monedaext/provisionextranjera.php
#cp _base/webapp/modulos/cont/views/captpolizas/polizacliente.php demo24/webapp/modulos/cont/views/captpolizas/polizacliente.php
#cp _base/webapp/modulos/cont/views/captpolizas/polizaproveedor.php demo24/webapp/modulos/cont/views/captpolizas/polizaproveedor.php
#cp _base/webapp/modulos/cont/views/captpolizas/provisionmultiple.php demo24/webapp/modulos/cont/views/captpolizas/provisionmultiple.php
#cp _base/webapp/modulos/cont/views/captpolizas/provisionmultipledetallado.php demo24/webapp/modulos/cont/views/captpolizas/provisionmultipledetallado.php
#cp _base/webapp/modulos/cont/views/conciliacionacontia/conciliacionacontia.php demo24/webapp/modulos/cont/views/conciliacionacontia/conciliacionacontia.php


### Acontia | Ivan Cuenca | 2015-11-12 11:27
#echo '[demo8]'
#cp _base/webapp/modulos/cont/controllers/reports.php demo8/webapp/modulos/cont/controllers/reports.php
#
#echo '[demo24]'
#cp _base/webapp/modulos/cont/controllers/reports.php demo24/webapp/modulos/cont/controllers/reports.php
#
#echo '[demo32]'
#cp _base/webapp/modulos/cont/controllers/reports.php demo32/webapp/modulos/cont/controllers/reports.php
#
#echo '[ccpg]'
#cp _base/webapp/modulos/cont/controllers/reports.php ccpg/webapp/modulos/cont/controllers/reports.php

### Acontia | Ivan Cuenca | 2015-11-11 18:53
#echo '[demo8]'
#cp _base/webapp/modulos/cont/js/funcionesPolizas.js demo8/webapp/modulos/cont/js/funcionesPolizas.js
#cp _base/webapp/modulos/cont/models/captpolizas.php demo8/webapp/modulos/cont/models/captpolizas.php
#
#echo '[demo24]'
#cp _base/webapp/modulos/cont/js/funcionesPolizas.js demo24/webapp/modulos/cont/js/funcionesPolizas.js
#cp _base/webapp/modulos/cont/models/captpolizas.php demo24/webapp/modulos/cont/models/captpolizas.php
#
#echo '[demo32]'
#cp _base/webapp/modulos/cont/js/funcionesPolizas.js demo32/webapp/modulos/cont/js/funcionesPolizas.js
#cp _base/webapp/modulos/cont/models/captpolizas.php demo32/webapp/modulos/cont/models/captpolizas.php
#
#echo '[ccpg]'
#cp _base/webapp/modulos/cont/js/funcionesPolizas.js ccpg/webapp/modulos/cont/js/funcionesPolizas.js
#cp _base/webapp/modulos/cont/models/captpolizas.php ccpg/webapp/modulos/cont/models/captpolizas.php


### Acontia | Carmen Gutiérrez | 2015-11-11 18:26
#echo '[demo24]'
#cp _base/webapp/modulos/cont/controllers/conciliacionacontia.php demo24/webapp/modulos/cont/controllers/conciliacionacontia.php
#cp _base/webapp/modulos/cont/views/conciliacionacontia/conciliacionacontia.php demo24/webapp/modulos/cont/views/conciliacionacontia/conciliacionacontia.php


### Acontia | Carmen Gutiérrez | 2015-11-11 17:54
#echo '[demo24]'
#cp _base/webapp/modulos/cont/views/reports/estadocuenta.php demo24/webapp/modulos/cont/views/reports/estadocuenta.php
#cp _base/webapp/modulos/cont/views/reports/reporteestadocuenta.php demo24/webapp/modulos/cont/views/reports/reporteestadocuenta.php


### Acontia | Carmen Gutierrez | 2015-11-11 1:50 pm
#cp -r  _base/webapp/modulos/bancos demo24/webapp/modulos/
#chmod -R 777 demo24/webapp/modulos/bancos/
#chown -R www-data demo24/webapp/modulos/bancos/
#chgrp -R www-data demo24/webapp/modulos/bancos/


### Foodware | Fer de la Cruz | 2015-11-11 1:12 pm 
#
#echo '[lantigua]'
#cp _base/webapp/modulos/restaurantes/views/comandas/Gmesas.php lantigua/webapp/modulos/restaurantes/views/comandas/Gmesas.php
#cp _base/webapp/modulos/restaurantes/views/comandas/comanda.php lantigua/webapp/modulos/restaurantes/views/comandas/comanda.php
#
#echo '[ebustamante]'
#cp _base/webapp/modulos/restaurantes/views/comandas/Gmesas.php ebustamante/webapp/modulos/restaurantes/views/comandas/Gmesas.php
#cp _base/webapp/modulos/restaurantes/views/comandas/comanda.php ebustamante/webapp/modulos/restaurantes/views/comandas/comanda.php
#
#echo '[gourmet]'
#cp _base/webapp/modulos/restaurantes/views/comandas/Gmesas.php gourmet/webapp/modulos/restaurantes/views/comandas/Gmesas.php
#cp _base/webapp/modulos/restaurantes/views/comandas/comanda.php gourmet/webapp/modulos/restaurantes/views/comandas/comanda.php



### Acontia | Ivan Cuenca | 2015-11-11 12:35

#echo '[demo8]'
#cp _base/webapp/modulos/cont/controllers/reports.php demo8/webapp/modulos/cont/controllers/reports.php
#cp _base/webapp/modulos/cont/models/reports.php demo8/webapp/modulos/cont/models/reports.php
#cp _base/webapp/modulos/cont/js/accountsTree.js.php demo8/webapp/modulos/cont/js/accountsTree.js.php

#echo '[demo24]'
#cp _base/webapp/modulos/cont/controllers/reports.php demo24/webapp/modulos/cont/controllers/reports.php
#cp _base/webapp/modulos/cont/models/reports.php demo24/webapp/modulos/cont/models/reports.php
#cp _base/webapp/modulos/cont/js/accountsTree.js.php demo24/webapp/modulos/cont/js/accountsTree.js.php

#echo '[demo32]'
#cp _base/webapp/modulos/cont/controllers/reports.php demo32/webapp/modulos/cont/controllers/reports.php
#cp _base/webapp/modulos/cont/models/reports.php demo32/webapp/modulos/cont/models/reports.php
#cp _base/webapp/modulos/cont/js/accountsTree.js.php demo32/webapp/modulos/cont/js/accountsTree.js.php

#echo '[ccpg]'
#cp _base/webapp/modulos/cont/controllers/reports.php ccpg/webapp/modulos/cont/controllers/reports.php
#cp _base/webapp/modulos/cont/models/reports.php ccpg/webapp/modulos/cont/models/reports.php
#cp _base/webapp/modulos/cont/js/accountsTree.js.php ccpg/webapp/modulos/cont/js/accountsTree.js.php



### Acontia | Carmen Gutierrez | 2015-11-11 12:28
#echo '[demo24]'
#cp _base/webapp/modulos/cont/controllers/automaticasmonedaext.php demo24/webapp/modulos/cont/controllers/automaticasmonedaext.php
#cp _base/webapp/modulos/cont/js/provisionext.js demo24/webapp/modulos/cont/js/provisionext.js
#cp _base/webapp/modulos/cont/models/automaticasmonedaext.php demo24/webapp/modulos/cont/models/automaticasmonedaext.php
#cp _base/webapp/modulos/cont/views/captpolizas/monedaext/cobroext.php demo24/webapp/modulos/cont/views/captpolizas/monedaext/cobroext.php
#cp _base/webapp/modulos/cont/views/captpolizas/monedaext/pagoext.php demo24/webapp/modulos/cont/views/captpolizas/monedaext/pagoext.php
#cp _base/webapp/modulos/cont/views/captpolizas/monedaext/provisiondetalladoextranjera.php demo24/webapp/modulos/cont/views/captpolizas/monedaext/provisiondetalladoextranjera.php
#cp _base/webapp/modulos/cont/views/captpolizas/monedaext/provisionextranjera.php demo24/webapp/modulos/cont/views/captpolizas/monedaext/provisionextranjera.php

### Acontia | Carmen Gutierrez | 2015-11-11 12:03
#echo '[brendaalicia]'
#cp _base/webapp/modulos/cont/controllers/captpolizas.php brendaalicia/webapp/modulos/cont/controllers/captpolizas.php
#cp _base/webapp/modulos/cont/views/captpolizas/comprobacion.php brendaalicia/webapp/modulos/cont/views/captpolizas/comprobacion.php
#cp _base/webapp/modulos/cont/controllers/config.php brendaalicia/webapp/modulos/cont/controllers/config.php
#cp _base/webapp/modulos/cont/models/config.php brendaalicia/webapp/modulos/cont/models/config.php
#cp _base/webapp/modulos/cont/views/config/configAccounts.php brendaalicia/webapp/modulos/cont/views/config/configAccounts.php
#cp _base/webapp/modulos/cont/views/captpolizas/polizacliente.php brendaalicia/webapp/modulos/cont/views/captpolizas/polizacliente.php
#cp _base/webapp/modulos/cont/views/captpolizas/polizaproveedor.php brendaalicia/webapp/modulos/cont/views/captpolizas/polizaproveedor.php
#cp _base/webapp/modulos/cont/views/captpolizas/provisionmultiple.php brendaalicia/webapp/modulos/cont/views/captpolizas/provisionmultiple.php
#cp _base/webapp/modulos/cont/views/captpolizas/provisionmultipledetallado.php brendaalicia/webapp/modulos/cont/views/captpolizas/provisionmultipledetallado.php

### Acontia | Carmen Gutierrez | 2015-11-11 11:59
#echo '[demo24]'
#cp _base/webapp/modulos/bancos/controllers/importarestadocuenta.php demo24/webapp/modulos/bancos/controllers/importarestadocuenta.php
#cp _base/webapp/modulos/bancos/js/importar.js demo24/webapp/modulos/bancos/js/importar.js
#cp _base/webapp/modulos/bancos/models/importarestadocuenta.php demo24/webapp/modulos/bancos/models/importarestadocuenta.php
#cp _base/webapp/modulos/bancos/views/importador/importaestadocuenta.php demo24/webapp/modulos/bancos/views/importador/importaestadocuenta.php

### Appministra | Omar Vazquez | 2015-11-11 11:11
#echo '[sistema]'
#cp _base/webapp/modulos/punto_venta_nuevo/controllers/caja.php sistema/webapp/modulos/punto_venta_nuevo/controllers/caja.php
#cp _base/webapp/modulos/punto_venta_nuevo/js/caja/caja.js sistema/webapp/modulos/punto_venta_nuevo/js/caja/caja.js
#cp _base/webapp/modulos/punto_venta_nuevo/models/caja/caja.php sistema/webapp/modulos/punto_venta_nuevo/models/caja/caja.php

### Foodware | Fer de la Cruz | 2015-11-11 11:03
#echo '[ebustamante]'
#cp _base/webapp/modulos/restaurantes/controllers/pedidosactivos.php ebustamante/webapp/modulos/restaurantes/controllers/pedidosactivos.php
#cp _base/webapp/modulos/restaurantes/controllers/comandas.php ebustamante/webapp/modulos/restaurantes/controllers/comandas.php
#cp _base/webapp/modulos/restaurantes/controllers/reservaciones.php ebustamante/webapp/modulos/restaurantes/controllers/reservaciones.php
#cp _base/webapp/modulos/restaurantes/js/notify.js ebustamante/webapp/modulos/restaurantes/js/notify.js
#cp _base/webapp/modulos/restaurantes/js/pedidos/pedidos.js ebustamante/webapp/modulos/restaurantes/js/pedidos/pedidos.js
#cp _base/webapp/modulos/restaurantes/js/comandas/comandas.js ebustamante/webapp/modulos/restaurantes/js/comandas/comandas.js
#cp _base/webapp/modulos/restaurantes/models/comandas.php ebustamante/webapp/modulos/restaurantes/models/comandas.php
#cp _base/webapp/modulos/restaurantes/models/connection_sqli.php ebustamante/webapp/modulos/restaurantes/models/connection_sqli.php
#cp _base/webapp/modulos/restaurantes/models/pedidosactivos.php ebustamante/webapp/modulos/restaurantes/models/pedidosactivos.php
#cp _base/webapp/modulos/restaurantes/models/reservaciones.php ebustamante/webapp/modulos/restaurantes/models/reservaciones.php
#cp _base/webapp/modulos/restaurantes/views/comandas/Gmesas.php ebustamante/webapp/modulos/restaurantes/views/comandas/Gmesas.php
#cp _base/webapp/modulos/restaurantes/views/comandas/comanda.php ebustamante/webapp/modulos/restaurantes/views/comandas/comanda.php
#cp _base/webapp/modulos/restaurantes/views/comandas/vista_productos.php ebustamante/webapp/modulos/restaurantes/views/comandas/vista_productos.php
#cp _base/webapp/modulos/restaurantes/views/pedidos/confpropina.php ebustamante/webapp/modulos/restaurantes/views/pedidos/confpropina.php
#cp _base/webapp/modulos/restaurantes/reservaciones/index.php ebustamante/webapp/modulos/restaurantes/reservaciones/index.php
#
#echo '[lantigua]'
#cp _base/webapp/modulos/restaurantes/controllers/pedidosactivos.php lantigua/webapp/modulos/restaurantes/controllers/pedidosactivos.php
#cp _base/webapp/modulos/restaurantes/controllers/comandas.php lantigua/webapp/modulos/restaurantes/controllers/comandas.php
#cp _base/webapp/modulos/restaurantes/controllers/reservaciones.php lantigua/webapp/modulos/restaurantes/controllers/reservaciones.php
#cp _base/webapp/modulos/restaurantes/js/notify.js lantigua/webapp/modulos/restaurantes/js/notify.js
#cp _base/webapp/modulos/restaurantes/js/pedidos/pedidos.js lantigua/webapp/modulos/restaurantes/js/pedidos/pedidos.js
#cp _base/webapp/modulos/restaurantes/js/comandas/comandas.js lantigua/webapp/modulos/restaurantes/js/comandas/comandas.js
#cp _base/webapp/modulos/restaurantes/models/comandas.php lantigua/webapp/modulos/restaurantes/models/comandas.php
#cp _base/webapp/modulos/restaurantes/models/connection_sqli.php lantigua/webapp/modulos/restaurantes/models/connection_sqli.php
#cp _base/webapp/modulos/restaurantes/models/pedidosactivos.php lantigua/webapp/modulos/restaurantes/models/pedidosactivos.php
#cp _base/webapp/modulos/restaurantes/models/reservaciones.php lantigua/webapp/modulos/restaurantes/models/reservaciones.php
#cp _base/webapp/modulos/restaurantes/views/comandas/Gmesas.php lantigua/webapp/modulos/restaurantes/views/comandas/Gmesas.php
#cp _base/webapp/modulos/restaurantes/views/comandas/comanda.php lantigua/webapp/modulos/restaurantes/views/comandas/comanda.php
#cp _base/webapp/modulos/restaurantes/views/comandas/vista_productos.php lantigua/webapp/modulos/restaurantes/views/comandas/vista_productos.php
#cp _base/webapp/modulos/restaurantes/views/pedidos/confpropina.php lantigua/webapp/modulos/restaurantes/views/pedidos/confpropina.php
#cp _base/webapp/modulos/restaurantes/reservaciones/index.php lantigua/webapp/modulos/restaurantes/reservaciones/index.php
#
#echo '[gourmet]'
#cp _base/webapp/modulos/restaurantes/controllers/pedidosactivos.php gourmet/webapp/modulos/restaurantes/controllers/pedidosactivos.php
#cp _base/webapp/modulos/restaurantes/controllers/comandas.php gourmet/webapp/modulos/restaurantes/controllers/comandas.php
#cp _base/webapp/modulos/restaurantes/controllers/reservaciones.php gourmet/webapp/modulos/restaurantes/controllers/reservaciones.php
#cp _base/webapp/modulos/restaurantes/js/notify.js gourmet/webapp/modulos/restaurantes/js/notify.js
#cp _base/webapp/modulos/restaurantes/js/pedidos/pedidos.js gourmet/webapp/modulos/restaurantes/js/pedidos/pedidos.js
#cp _base/webapp/modulos/restaurantes/js/comandas/comandas.js gourmet/webapp/modulos/restaurantes/js/comandas/comandas.js
#cp _base/webapp/modulos/restaurantes/models/comandas.php gourmet/webapp/modulos/restaurantes/models/comandas.php
#cp _base/webapp/modulos/restaurantes/models/connection_sqli.php gourmet/webapp/modulos/restaurantes/models/connection_sqli.php
#cp _base/webapp/modulos/restaurantes/models/pedidosactivos.php gourmet/webapp/modulos/restaurantes/models/pedidosactivos.php
#cp _base/webapp/modulos/restaurantes/models/reservaciones.php gourmet/webapp/modulos/restaurantes/models/reservaciones.php
#cp _base/webapp/modulos/restaurantes/views/comandas/Gmesas.php gourmet/webapp/modulos/restaurantes/views/comandas/Gmesas.php
#cp _base/webapp/modulos/restaurantes/views/comandas/comanda.php gourmet/webapp/modulos/restaurantes/views/comandas/comanda.php
#cp _base/webapp/modulos/restaurantes/views/comandas/vista_productos.php gourmet/webapp/modulos/restaurantes/views/comandas/vista_productos.php
#cp _base/webapp/modulos/restaurantes/views/pedidos/confpropina.php gourmet/webapp/modulos/restaurantes/views/pedidos/confpropina.php
#cp _base/webapp/modulos/restaurantes/reservaciones/index.php gourmet/webapp/modulos/restaurantes/reservaciones/index.php
#


### Acontia | Ivan Cuenca | 2015-11-06 18:26
#echo '[demo8]'
#cp _base/webapp/modulos/cont/controllers/repperiodoacreditamiento.php demo8/webapp/modulos/cont/controllers/repperiodoacreditamiento.php
#cp _base/webapp/modulos/cont/models/RepPeriodoAcreditamiento.php demo8/webapp/modulos/cont/models/RepPeriodoAcreditamiento.php
#cp _base/webapp/modulos/cont/models/captpolizas.php demo8/webapp/modulos/cont/models/captpolizas.php
#
#echo '[demo24]'
#cp _base/webapp/modulos/cont/controllers/repperiodoacreditamiento.php demo24/webapp/modulos/cont/controllers/repperiodoacreditamiento.php
#cp _base/webapp/modulos/cont/models/RepPeriodoAcreditamiento.php demo24/webapp/modulos/cont/models/RepPeriodoAcreditamiento.php
#cp _base/webapp/modulos/cont/models/captpolizas.php demo24/webapp/modulos/cont/models/captpolizas.php
#
#echo '[demo32]'
#cp _base/webapp/modulos/cont/controllers/repperiodoacreditamiento.php demo32/webapp/modulos/cont/controllers/repperiodoacreditamiento.php
#cp _base/webapp/modulos/cont/models/RepPeriodoAcreditamiento.php demo32/webapp/modulos/cont/models/RepPeriodoAcreditamiento.php
#cp _base/webapp/modulos/cont/models/captpolizas.php demo32/webapp/modulos/cont/models/captpolizas.php
#
#echo '[ccpg]'
#cp _base/webapp/modulos/cont/controllers/repperiodoacreditamiento.php ccpg/webapp/modulos/cont/controllers/repperiodoacreditamiento.php
#cp _base/webapp/modulos/cont/models/RepPeriodoAcreditamiento.php ccpg/webapp/modulos/cont/models/RepPeriodoAcreditamiento.php
#cp _base/webapp/modulos/cont/models/captpolizas.php ccpg/webapp/modulos/cont/models/captpolizas.php


### Acontia | Carmen | 2015-11-06 18:19

#echo '[demo24]'

#mkdir demo24/webapp/modulos/cont/views/captpolizas/monedaext/
#
#chmod -R 777 demo24/webapp/modulos/cont/views/captpolizas/monedaext/
#chown -R www-data demo24/webapp/modulos/cont/views/captpolizas/monedaext/
#chgrp -R www-data demo24/webapp/modulos/cont/views/captpolizas/monedaext/
#
#cp _base/webapp/modulos/cont/controllers/automaticasmonedaext.php demo24/webapp/modulos/cont/controllers/automaticasmonedaext.php
#cp _base/webapp/modulos/cont/js/cobroext.js demo24/webapp/modulos/cont/js/cobroext.js
#cp _base/webapp/modulos/cont/js/pagoext.js demo24/webapp/modulos/cont/js/pagoext.js
#cp _base/webapp/modulos/cont/views/captpolizas/monedaext/cobroext.php demo24/webapp/modulos/cont/views/captpolizas/monedaext/cobroext.php
#cp _base/webapp/modulos/cont/models/automaticasmonedaext.php demo24/webapp/modulos/cont/models/automaticasmonedaext.php
#cp _base/webapp/modulos/cont/views/captpolizas/filtropolizasautomaticas.php demo24/webapp/modulos/cont/views/captpolizas/filtropolizasautomaticas.php
#cp _base/webapp/modulos/cont/views/captpolizas/monedaext/pagoext.php demo24/webapp/modulos/cont/views/captpolizas/monedaext/pagoext.php
#cp _base/webapp/modulos/cont/controllers/conciliacionacontia.php demo24/webapp/modulos/cont/controllers/conciliacionacontia.php
#


### Foodware | Fer De La Cruz | 2015-11-06 18:10

#echo '[gourmet]'
#cp _base/webapp/modulos/restaurantes/controllers/comandas.php gourmet/webapp/modulos/restaurantes/controllers/comandas.php
#cp _base/webapp/modulos/restaurantes/controllers/pedidosactivos.php gourmet/webapp/modulos/restaurantes/controllers/pedidosactivos.php
#cp _base/webapp/modulos/restaurantes/models/comandas.php gourmet/webapp/modulos/restaurantes/models/comandas.php
#cp _base/webapp/modulos/restaurantes/models/connection_sqli.php gourmet/webapp/modulos/restaurantes/models/connection_sqli.php
#cp _base/webapp/modulos/restaurantes/models/pedidosactivos.php gourmet/webapp/modulos/restaurantes/models/pedidosactivos.php
#cp _base/webapp/modulos/restaurantes/reservaciones/form.php gourmet/webapp/modulos/restaurantes/reservaciones/form.php
#cp _base/webapp/modulos/restaurantes/reservaciones/index.php gourmet/webapp/modulos/restaurantes/reservaciones/index.php
#cp _base/webapp/modulos/restaurantes/views/comandas/Gmesas.php gourmet/webapp/modulos/restaurantes/views/comandas/Gmesas.php
#cp _base/webapp/modulos/restaurantes/views/comandas/comanda.php gourmet/webapp/modulos/restaurantes/views/comandas/comanda.php
#cp _base/webapp/modulos/restaurantes/js/pedidos/pedidos.js gourmet/webapp/modulos/restaurantes/js/pedidos/pedidos.js
#
#echo '[ebustamante]'
#cp _base/webapp/modulos/restaurantes/controllers/comandas.php ebustamante/webapp/modulos/restaurantes/controllers/comandas.php
#cp _base/webapp/modulos/restaurantes/controllers/pedidosactivos.php ebustamante/webapp/modulos/restaurantes/controllers/pedidosactivos.php
#cp _base/webapp/modulos/restaurantes/models/comandas.php ebustamante/webapp/modulos/restaurantes/models/comandas.php
#cp _base/webapp/modulos/restaurantes/models/connection_sqli.php ebustamante/webapp/modulos/restaurantes/models/connection_sqli.php
#cp _base/webapp/modulos/restaurantes/models/pedidosactivos.php ebustamante/webapp/modulos/restaurantes/models/pedidosactivos.php
#cp _base/webapp/modulos/restaurantes/reservaciones/form.php ebustamante/webapp/modulos/restaurantes/reservaciones/form.php
#cp _base/webapp/modulos/restaurantes/reservaciones/index.php ebustamante/webapp/modulos/restaurantes/reservaciones/index.php
#cp _base/webapp/modulos/restaurantes/views/comandas/Gmesas.php ebustamante/webapp/modulos/restaurantes/views/comandas/Gmesas.php
#cp _base/webapp/modulos/restaurantes/views/comandas/comanda.php ebustamante/webapp/modulos/restaurantes/views/comandas/comanda.php
#cp _base/webapp/modulos/restaurantes/js/pedidos/pedidos.js ebustamante/webapp/modulos/restaurantes/js/pedidos/pedidos.js
#
#echo '[laantigua]'
#cp _base/webapp/modulos/restaurantes/controllers/comandas.php laantigua/webapp/modulos/restaurantes/controllers/comandas.php
#cp _base/webapp/modulos/restaurantes/controllers/pedidosactivos.php laantigua/webapp/modulos/restaurantes/controllers/pedidosactivos.php
#cp _base/webapp/modulos/restaurantes/models/comandas.php laantigua/webapp/modulos/restaurantes/models/comandas.php
#cp _base/webapp/modulos/restaurantes/models/connection_sqli.php laantigua/webapp/modulos/restaurantes/models/connection_sqli.php
#cp _base/webapp/modulos/restaurantes/models/pedidosactivos.php laantigua/webapp/modulos/restaurantes/models/pedidosactivos.php
#cp _base/webapp/modulos/restaurantes/reservaciones/form.php laantigua/webapp/modulos/restaurantes/reservaciones/form.php
#cp _base/webapp/modulos/restaurantes/reservaciones/index.php laantigua/webapp/modulos/restaurantes/reservaciones/index.php
#cp _base/webapp/modulos/restaurantes/views/comandas/Gmesas.php laantigua/webapp/modulos/restaurantes/views/comandas/Gmesas.php
#cp _base/webapp/modulos/restaurantes/views/comandas/comanda.php laantigua/webapp/modulos/restaurantes/views/comandas/comanda.php
#cp _base/webapp/modulos/restaurantes/js/pedidos/pedidos.js laantigua/webapp/modulos/restaurantes/js/pedidos/pedidos.js
#



### Appministra | Omar Vazquez | 2015-11-05 18:24
#cp _base/webapp/modulos/facturacion/reporteFacturas.php sistema/webapp/modulos/facturacion/reporteFacturas.php
#cp _base/webapp/modulos/punto_venta_nuevo/models/caja/caja.php sistema/webapp/modulos/punto_venta_nuevo/models/caja/caja.php


### Appministra | Omar Vazquez | 2015-11-05 17:20
#cp _base/webapp/modulos/punto_venta_nuevo/models/caja/caja.php sistema/webapp/modulos/punto_venta_nuevo/models/caja/caja.php

### Appministra | Omar Vazquez | 2015-11-05 14:40
#cp _base/webapp/modulos/punto_venta_nuevo/models/caja/caja.php sistema/webapp/modulos/punto_venta_nuevo/models/caja/caja.php

### Appministra | Omar Vazquez | 2015-11-05 14:26
#cp _base/webapp/modulos/punto_venta_nuevo/models/caja/caja.php sistema/webapp/modulos/punto_venta_nuevo/models/caja/caja.php

### Aconita | Carmen | 2015-11-05 11:51
#echo '[demo24]'
#cp _base/webapp/modulos/cont/views/conciliacionacontia/conciliacionacontia.php demo24/webapp/modulos/cont/views/conciliacionacontia/conciliacionacontia.php
#cp _base/webapp/modulos/cont/js/conciliacionacontia.js demo24/webapp/modulos/cont/js/conciliacionacontia.js


### Appministra | Omar Vazquez | 2015-11-05 11:10
#echo '[sistema]'
#cp _base/webapp/modulos/SAT/funcionesSAT.php sistema/webapp/modulos/SAT/funcionesSAT.php
#cp _base/webapp/modulos/punto_venta_nuevo/models/caja/caja.php sistema/webapp/modulos/punto_venta_nuevo/models/caja/caja.php

### Acontia | Carmen | 2015-11-03 18:48
#cp _base/webapp/modulos/cont/models/conciliacionacontia.php demo24/webapp/modulos/cont/models/conciliacionacontia.php

### Foodware | Fer de la Cruz | 2015-11-03 18:46
#cp _base/webapp/modulos/restaurantes/views/comandas/Gmesas.php lantigua/webapp/modulos/restaurantes/views/comandas/Gmesas.php

### Appministra | Omar Vazquez | 2015-11-03 18:41
#echo '[restesting]'
#cp _base/webapp/modulos/mrp/js/product.js restesting/webapp/modulos/mrp/js/product.js
#cp _base/webapp/modulos/punto_venta_nuevo/js/caja/caja.js restesting/webapp/modulos/punto_venta_nuevo/js/caja/caja.js
#cp _base/webapp/modulos/punto_venta_nuevo/models/caja/caja.php restesting/webapp/modulos/punto_venta_nuevo/models/caja/caja.php
#cp _base/webapp/modulos/punto_venta_nuevo/views/caja/caja.php restesting/webapp/modulos/punto_venta_nuevo/views/caja/caja.php
#cp _base/webapp/modulos/punto_venta_nuevo/controllers/caja.php restesting/webapp/modulos/punto_venta_nuevo/controllers/caja.php

### Acontia | Ivan Cuenca | 2015-11-03 12:25
#echo '[ccpg]'
#cp _base/webapp/modulos/cont/controllers/reports.php ccpg/webapp/modulos/cont/controllers/reports.php
#cp _base/webapp/modulos/cont/controllers/auxiliar_a29.php ccpg/webapp/modulos/cont/controllers/auxiliar_a29.php
#cp _base/webapp/modulos/cont/controllers/auxiliar_impuestos.php ccpg/webapp/modulos/cont/controllers/auxiliar_impuestos.php
#cp _base/webapp/modulos/cont/controllers/anexosivacausadoacreditable.php ccpg/webapp/modulos/cont/controllers/anexosivacausadoacreditable.php
#cp _base/webapp/modulos/cont/controllers/captpolizas.php ccpg/webapp/modulos/cont/controllers/captpolizas.php
#cp _base/webapp/modulos/cont/models/reports.php ccpg/webapp/modulos/cont/models/reports.php
#cp _base/webapp/modulos/cont/models/basicTree.sql ccpg/webapp/modulos/cont/models/basicTree.sql
#cp _base/webapp/modulos/cont/models/fullTree.sql ccpg/webapp/modulos/cont/models/fullTree.sql
#cp _base/webapp/modulos/cont/models/Auxiliar_Impuestos.php ccpg/webapp/modulos/cont/models/Auxiliar_Impuestos.php
#cp _base/webapp/modulos/cont/models/flujoEfectivoIva.php ccpg/webapp/modulos/cont/models/flujoEfectivoIva.php
#cp _base/webapp/modulos/cont/models/RepPeriodoAcreditamiento.php ccpg/webapp/modulos/cont/models/RepPeriodoAcreditamiento.php
#cp _base/webapp/modulos/cont/models/auxiliar_a29.php ccpg/webapp/modulos/cont/models/auxiliar_a29.php
#cp _base/webapp/modulos/cont/models/conciliacion_IVA_contable_fiscal.php ccpg/webapp/modulos/cont/models/conciliacion_IVA_contable_fiscal.php
#cp _base/webapp/modulos/cont/models/captpolizas.php ccpg/webapp/modulos/cont/models/captpolizas.php
#cp _base/webapp/modulos/cont/views/reports/balanceGeneral.php ccpg/webapp/modulos/cont/views/reports/balanceGeneral.php
#cp _base/webapp/modulos/cont/views/reports/balanceGeneralReporte.php ccpg/webapp/modulos/cont/views/reports/balanceGeneralReporte.php
#cp _base/webapp/modulos/cont/views/reports/balanceGeneralReportePeriodos.php ccpg/webapp/modulos/cont/views/reports/balanceGeneralReportePeriodos.php
#cp _base/webapp/modulos/cont/views/reports/estadoOrigenReporte.php ccpg/webapp/modulos/cont/views/reports/estadoOrigenReporte.php
#cp _base/webapp/modulos/cont/views/reports/estadoResultadosReporte.php ccpg/webapp/modulos/cont/views/reports/estadoResultadosReporte.php
#cp _base/webapp/modulos/cont/views/reports/estadoResultadosxSegmento2.php ccpg/webapp/modulos/cont/views/reports/estadoResultadosxSegmento2.php
#cp _base/webapp/modulos/cont/views/reports/balanzaComprobacion.php ccpg/webapp/modulos/cont/views/reports/balanzaComprobacion.php
#cp _base/webapp/modulos/cont/views/reports/balanzaComprobacionReporte2.php ccpg/webapp/modulos/cont/views/reports/balanzaComprobacionReporte2.php
#cp _base/webapp/modulos/cont/views/reports/movcuentas_despues.php ccpg/webapp/modulos/cont/views/reports/movcuentas_despues.php
#cp _base/webapp/modulos/cont/views/fiscal/declaraciones/aux_imp_egresos.php ccpg/webapp/modulos/cont/views/fiscal/declaraciones/aux_imp_egresos.php
#cp _base/webapp/modulos/cont/views/fiscal/declaraciones/aux_imp_ingresos.php ccpg/webapp/modulos/cont/views/fiscal/declaraciones/aux_imp_ingresos.php
#cp _base/webapp/modulos/cont/views/fiscal/diot/AuxiliarControlIvaPorProveedorReporte.php ccpg/webapp/modulos/cont/views/fiscal/diot/AuxiliarControlIvaPorProveedorReporte.php
#cp _base/webapp/modulos/cont/views/fiscal/diot/AuxiliarControlIvaReporte.php ccpg/webapp/modulos/cont/views/fiscal/diot/AuxiliarControlIvaReporte.php
#cp _base/webapp/modulos/cont/views/fiscal/diot/AuxiliarFormatoA29Reporte.php ccpg/webapp/modulos/cont/views/fiscal/diot/AuxiliarFormatoA29Reporte.php
#cp _base/webapp/modulos/cont/views/fiscal/diot/EgresosSinControlDeIVAReporte.php ccpg/webapp/modulos/cont/views/fiscal/diot/EgresosSinControlDeIVAReporte.php
#cp _base/webapp/modulos/cont/views/fiscal/diot/flujoEfectivoIvaReporte.php ccpg/webapp/modulos/cont/views/fiscal/diot/flujoEfectivoIvaReporte.php
#cp _base/webapp/modulos/cont/views/fiscal/pago_provicional/Conciliacion_IVA_contable_fiscal.php ccpg/webapp/modulos/cont/views/fiscal/pago_provicional/Conciliacion_IVA_contable_fiscal.php
#cp _base/webapp/modulos/cont/views/fiscal/diot/RepConcentradoIvaProveedor.php ccpg/webapp/modulos/cont/views/fiscal/diot/RepConcentradoIvaProveedor.php
#cp _base/webapp/modulos/cont/views/fiscal/declaraciones/declaracion_r21.php ccpg/webapp/modulos/cont/views/fiscal/declaraciones/declaracion_r21.php
#cp _base/webapp/modulos/cont/views/fiscal/declaraciones/resumen_mov_r21.php ccpg/webapp/modulos/cont/views/fiscal/declaraciones/resumen_mov_r21.php
#cp _base/webapp/modulos/cont/views/fiscal/pago_provicional/Anexos_IVA_Causado_Acreditable.php ccpg/webapp/modulos/cont/views/fiscal/pago_provicional/Anexos_IVA_Causado_Acreditable.php
#cp _base/webapp/modulos/cont/views/captpolizas/agregar.php ccpg/webapp/modulos/cont/views/captpolizas/agregar.php
#cp _base/webapp/modulos/cont/views/reports/movcuentas.php ccpg/webapp/modulos/cont/views/reports/movcuentas.php
#cp _base/webapp/modulos/cont/views/captpolizas/actpolizas.php ccpg/webapp/modulos/cont/views/captpolizas/actpolizas.php
#cp _base/webapp/modulos/cont/views/captpolizas/capturapolizas.php ccpg/webapp/modulos/cont/views/captpolizas/capturapolizas.php
#cp _base/webapp/modulos/cont/js/agregar.js ccpg/webapp/modulos/cont/js/agregar.js
#cp _base/webapp/modulos/cont/js/funcionesPolizas.js ccpg/webapp/modulos/cont/js/funcionesPolizas.js


### Appministra | Omar Vazquez | 2015-11-02 17:48

#echo '[sistema]'
#cp _base/webapp/modulos/mrp/js/product.js sistema/webapp/modulos/mrp/js/product.js
#cp _base/webapp/modulos/punto_venta_nuevo/js/caja/caja.js sistema/webapp/modulos/punto_venta_nuevo/js/caja/caja.js
#cp _base/webapp/modulos/punto_venta_nuevo/models/caja/caja.php sistema/webapp/modulos/punto_venta_nuevo/models/caja/caja.php
#cp _base/webapp/modulos/punto_venta_nuevo/views/caja/caja.php sistema/webapp/modulos/punto_venta_nuevo/views/caja/caja.php
#cp _base/webapp/modulos/punto_venta_nuevo/controllers/caja.php sistema/webapp/modulos/punto_venta_nuevo/controllers/caja.php


### Acontia | Ivan Cuenca | 2015-11-02 17:47
#
#echo '[demo8]'
#cp _base/webapp/modulos/cont/controllers/reports.php demo8/webapp/modulos/cont/controllers/reports.php
#cp _base/webapp/modulos/cont/models/reports.php demo8/webapp/modulos/cont/models/reports.php
#cp _base/webapp/modulos/cont/views/reports/movcuentas.php demo8/webapp/modulos/cont/views/reports/movcuentas.php
#cp _base/webapp/modulos/cont/views/reports/movcuentas_despues.php demo8/webapp/modulos/cont/views/reports/movcuentas_despues.php
#
#echo '[demo24]'
#cp _base/webapp/modulos/cont/controllers/reports.php demo24/webapp/modulos/cont/controllers/reports.php
#cp _base/webapp/modulos/cont/models/reports.php demo24/webapp/modulos/cont/models/reports.php
#cp _base/webapp/modulos/cont/views/reports/movcuentas.php demo24/webapp/modulos/cont/views/reports/movcuentas.php
#cp _base/webapp/modulos/cont/views/reports/movcuentas_despues.php demo24/webapp/modulos/cont/views/reports/movcuentas_despues.php
#
#echo '[demo32]'
#cp _base/webapp/modulos/cont/controllers/reports.php demo32/webapp/modulos/cont/controllers/reports.php
#cp _base/webapp/modulos/cont/models/reports.php demo32/webapp/modulos/cont/models/reports.php
#cp _base/webapp/modulos/cont/views/reports/movcuentas.php demo32/webapp/modulos/cont/views/reports/movcuentas.php
#cp _base/webapp/modulos/cont/views/reports/movcuentas_despues.php demo32/webapp/modulos/cont/views/reports/movcuentas_despues.php
#


### Acontia | Carmen | 2015-11-02 17:45

#echo '[demo24]'
#cp _base/webapp/modulos/cont/views/reports/estadoResultadosReporte.php demo24/webapp/modulos/cont/views/reports/estadoResultadosReporte.php
#cp _base/webapp/modulos/cont/views/reports/balanceGeneralReportePeriodos.php demo24/webapp/modulos/cont/views/reports/balanceGeneralReportePeriodos.php
#cp _base/webapp/modulos/cont/views/reports/balanceGeneralReporte.php demo24/webapp/modulos/cont/views/reports/balanceGeneralReporte.php

### Acontia | Carmen | 2015-11-02 17:43

#echo '[demo24]'
#cp _base/webapp/modulos/cont/controllers/conciliacionacontia.php demo24/webapp/modulos/cont/controllers/conciliacionacontia.php
#cp _base/webapp/modulos/cont/js/conciliacionacontia.js demo24/webapp/modulos/cont/js/conciliacionacontia.js
#cp _base/webapp/modulos/cont/models/conciliacionacontia.php demo24/webapp/modulos/cont/models/conciliacionacontia.php
#cp _base/webapp/modulos/cont/views/conciliacionacontia/conciliacionacontia.php demo24/webapp/modulos/cont/views/conciliacionacontia/conciliacionacontia.php


### Acontia | Ivan Cuenca | 2015-11-02 11:26
#echo '[demo8]'
#cp _base/webapp/modulos/cont/controllers/captpolizas.php demo8/webapp/modulos/cont/controllers/captpolizas.php
#cp _base/webapp/modulos/cont/controllers/reports.php demo8/webapp/modulos/cont/controllers/reports.php
#cp _base/webapp/modulos/cont/js/agregar.js demo8/webapp/modulos/cont/js/agregar.js
#cp _base/webapp/modulos/cont/js/funcionesPolizas.js demo8/webapp/modulos/cont/js/funcionesPolizas.js
#cp _base/webapp/modulos/cont/models/captpolizas.php demo8/webapp/modulos/cont/models/captpolizas.php
#cp _base/webapp/modulos/cont/models/reports.php demo8/webapp/modulos/cont/models/reports.php
#cp _base/webapp/modulos/cont/views/captpolizas/agregar.php demo8/webapp/modulos/cont/views/captpolizas/agregar.php
#
#echo '[demo24]'
#cp _base/webapp/modulos/cont/controllers/captpolizas.php demo24/webapp/modulos/cont/controllers/captpolizas.php
#cp _base/webapp/modulos/cont/controllers/reports.php demo24/webapp/modulos/cont/controllers/reports.php
#cp _base/webapp/modulos/cont/js/agregar.js demo24/webapp/modulos/cont/js/agregar.js
#cp _base/webapp/modulos/cont/js/funcionesPolizas.js demo24/webapp/modulos/cont/js/funcionesPolizas.js
#cp _base/webapp/modulos/cont/models/captpolizas.php demo24/webapp/modulos/cont/models/captpolizas.php
#cp _base/webapp/modulos/cont/models/reports.php demo24/webapp/modulos/cont/models/reports.php
#cp _base/webapp/modulos/cont/views/captpolizas/agregar.php demo24/webapp/modulos/cont/views/captpolizas/agregar.php
#
#echo '[demo32]'
#cp _base/webapp/modulos/cont/controllers/captpolizas.php demo32/webapp/modulos/cont/controllers/captpolizas.php
#cp _base/webapp/modulos/cont/controllers/reports.php demo32/webapp/modulos/cont/controllers/reports.php
#cp _base/webapp/modulos/cont/js/agregar.js demo32/webapp/modulos/cont/js/agregar.js
#cp _base/webapp/modulos/cont/js/funcionesPolizas.js demo32/webapp/modulos/cont/js/funcionesPolizas.js
#cp _base/webapp/modulos/cont/models/captpolizas.php demo32/webapp/modulos/cont/models/captpolizas.php
#cp _base/webapp/modulos/cont/models/reports.php demo32/webapp/modulos/cont/models/reports.php
#cp _base/webapp/modulos/cont/views/captpolizas/agregar.php demo32/webapp/modulos/cont/views/captpolizas/agregar.php
#

### Acontia | Carmen | 2015-10-30 16:14
#echo '[demo24]'
#cp _base/webapp/modulos/cont/controllers/conciliacionacontia.php demo24/webapp/modulos/cont/controllers/conciliacionacontia.php
#cp _base/webapp/modulos/cont/js/conciliacionacontia.js demo24/webapp/modulos/cont/js/conciliacionacontia.js
#cp _base/webapp/modulos/cont/models/conciliacionacontia.php demo24/webapp/modulos/cont/models/conciliacionacontia.php
#cp _base/webapp/modulos/cont/views/conciliacionacontia/conciliacionacontia.php demo24/webapp/modulos/cont/views/conciliacionacontia/conciliacionacontia.php



### Appministra | Omar Vazquez | 2015-10-28 17:46
#cp _base/webapp/modulos/punto_venta_nuevo/models/caja/caja.php sistema/webapp/modulos/punto_venta_nuevo/models/caja/caja.php
#cp _base/webapp/modulos/punto_venta_nuevo/js/caja/caja.js sistema/webapp/modulos/punto_venta_nuevo/js/caja/caja.js


### Appministra | Omar Vazquez | 2015-10-28 17:17
#echo '[sistema]'
#cp _base/webapp/modulos/SAT/PDF/CFDIPDF.php sistema/webapp/modulos/SAT/PDF/CFDIPDF.php
#cp _base/webapp/modulos/SAT/funcionesSAT.php sistema/webapp/modulos/SAT/funcionesSAT.php
#cp _base/webapp/modulos/punto_venta_nuevo/js/caja/caja.js sistema/webapp/modulos/punto_venta_nuevo/js/caja/caja.js




### Appministra | Omar Vazquez | 2015-10-28 16:57
#echo '[sistema]'
#cp _base/webapp/modulos/punto_venta_nuevo/models/caja/caja.php sistema/webapp/modulos/punto_venta_nuevo/models/caja/caja.php
#cp _base/webapp/modulos/SAT/PDF/CFDIPDF.php sistema/webapp/modulos/SAT/PDF/CFDIPDF.php
#cp _base/webapp/modulos/SAT/funcionesSAT.php sistema/webapp/modulos/SAT/funcionesSAT.php
#cp _base/webapp/modulos/punto_venta_nuevo/js/caja/caja.js sistema/webapp/modulos/punto_venta_nuevo/js/caja/caja.js




### Acontia | Ivan Cuenca | 2015-10-28 13:37

# [demo8]
#cp _base/webapp/modulos/cont/controllers/anexosivacausadoacreditable.php demo8/webapp/modulos/cont/controllers/anexosivacausadoacreditable.php
#cp _base/webapp/modulos/cont/controllers/auxiliar_impuestos.php demo8/webapp/modulos/cont/controllers/auxiliar_impuestos.php
#cp _base/webapp/modulos/cont/views/fiscal/declaraciones/declaracion_r21.php demo8/webapp/modulos/cont/views/fiscal/declaraciones/declaracion_r21.php
#cp _base/webapp/modulos/cont/views/fiscal/declaraciones/resumen_mov_r21.php demo8/webapp/modulos/cont/views/fiscal/declaraciones/resumen_mov_r21.php
#cp _base/webapp/modulos/cont/views/fiscal/pago_provicional/Anexos_IVA_Causado_Acreditable.php demo8/webapp/modulos/cont/views/fiscal/pago_provicional/Anexos_IVA_Causado_Acreditable.php

# [demo24]
#cp _base/webapp/modulos/cont/controllers/anexosivacausadoacreditable.php demo24/webapp/modulos/cont/controllers/anexosivacausadoacreditable.php
#cp _base/webapp/modulos/cont/controllers/auxiliar_impuestos.php demo24/webapp/modulos/cont/controllers/auxiliar_impuestos.php
#cp _base/webapp/modulos/cont/views/fiscal/declaraciones/declaracion_r21.php demo24/webapp/modulos/cont/views/fiscal/declaraciones/declaracion_r21.php
#cp _base/webapp/modulos/cont/views/fiscal/declaraciones/resumen_mov_r21.php demo24/webapp/modulos/cont/views/fiscal/declaraciones/resumen_mov_r21.php
#cp _base/webapp/modulos/cont/views/fiscal/pago_provicional/Anexos_IVA_Causado_Acreditable.php demo24/webapp/modulos/cont/views/fiscal/pago_provicional/Anexos_IVA_Causado_Acreditable.php

# [demo32]
#cp _base/webapp/modulos/cont/controllers/anexosivacausadoacreditable.php demo32/webapp/modulos/cont/controllers/anexosivacausadoacreditable.php
#cp _base/webapp/modulos/cont/controllers/auxiliar_impuestos.php demo32/webapp/modulos/cont/controllers/auxiliar_impuestos.php
#cp _base/webapp/modulos/cont/views/fiscal/declaraciones/declaracion_r21.php demo32/webapp/modulos/cont/views/fiscal/declaraciones/declaracion_r21.php
#cp _base/webapp/modulos/cont/views/fiscal/declaraciones/resumen_mov_r21.php demo32/webapp/modulos/cont/views/fiscal/declaraciones/resumen_mov_r21.php
#cp _base/webapp/modulos/cont/views/fiscal/pago_provicional/Anexos_IVA_Causado_Acreditable.php demo32/webapp/modulos/cont/views/fiscal/pago_provicional/Anexos_IVA_Causado_Acreditable.php






### Foodware | Fer De La Cruz | 2015-10-27 18:47

## [ebustamante]
#cp _base/webapp/modulos/restaurantes/controllers/comandas.php ebustamante/webapp/modulos/restaurantes/controllers/comandas.php
#cp _base/webapp/modulos/restaurantes/imgcomandas/boton_amarillo.png ebustamante/webapp/modulos/restaurantes/imgcomandas/boton_amarillo.png
#cp _base/webapp/modulos/restaurantes/models/comandas.php ebustamante/webapp/modulos/restaurantes/models/comandas.php
#cp _base/webapp/modulos/restaurantes/reservaciones/eventos.php ebustamante/webapp/modulos/restaurantes/reservaciones/eventos.php
#cp _base/webapp/modulos/restaurantes/reservaciones/form.php ebustamante/webapp/modulos/restaurantes/reservaciones/form.php
#cp _base/webapp/modulos/restaurantes/reservaciones/index.php ebustamante/webapp/modulos/restaurantes/reservaciones/index.php
#cp _base/webapp/modulos/restaurantes/reservaciones/select2/modal.php ebustamante/webapp/modulos/restaurantes/reservaciones/select2/modal.php
#cp _base/webapp/modulos/restaurantes/views/comandas/Gmesas.php ebustamante/webapp/modulos/restaurantes/views/comandas/Gmesas.php
#cp _base/webapp/modulos/restaurantes/views/comandas/comanda.php ebustamante/webapp/modulos/restaurantes/views/comandas/comanda.php
#cp _base/webapp/netwarelog/catalog/js/notify.js ebustamante/webapp/netwarelog/catalog/js/notify.js
#
## [gourmet]
#cp _base/webapp/modulos/restaurantes/controllers/comandas.php gourmet/webapp/modulos/restaurantes/controllers/comandas.php
#cp _base/webapp/modulos/restaurantes/imgcomandas/boton_amarillo.png gourmet/webapp/modulos/restaurantes/imgcomandas/boton_amarillo.png
#cp _base/webapp/modulos/restaurantes/models/comandas.php gourmet/webapp/modulos/restaurantes/models/comandas.php
#cp _base/webapp/modulos/restaurantes/reservaciones/eventos.php gourmet/webapp/modulos/restaurantes/reservaciones/eventos.php
#cp _base/webapp/modulos/restaurantes/reservaciones/form.php gourmet/webapp/modulos/restaurantes/reservaciones/form.php
#cp _base/webapp/modulos/restaurantes/reservaciones/index.php gourmet/webapp/modulos/restaurantes/reservaciones/index.php
#cp _base/webapp/modulos/restaurantes/reservaciones/select2/modal.php gourmet/webapp/modulos/restaurantes/reservaciones/select2/modal.php
#cp _base/webapp/modulos/restaurantes/views/comandas/Gmesas.php gourmet/webapp/modulos/restaurantes/views/comandas/Gmesas.php
#cp _base/webapp/modulos/restaurantes/views/comandas/comanda.php gourmet/webapp/modulos/restaurantes/views/comandas/comanda.php
#cp _base/webapp/netwarelog/catalog/js/notify.js gourmet/webapp/netwarelog/catalog/js/notify.js
#
#
### Acontia | Ivan Cuenca | 2015-10-27 12:38

# [demo8]
#cp _base/webapp/modulos/cont/models/RepPeriodoAcreditamiento.php demo8/webapp/modulos/cont/models/RepPeriodoAcreditamiento.php
#cp _base/webapp/modulos/cont/models/auxiliar_a29.php demo8/webapp/modulos/cont/models/auxiliar_a29.php
#cp _base/webapp/modulos/cont/models/conciliacion_IVA_contable_fiscal.php demo8/webapp/modulos/cont/models/conciliacion_IVA_contable_fiscal.php
#cp _base/webapp/modulos/cont/models/flujoEfectivoIva.php demo8/webapp/modulos/cont/models/flujoEfectivoIva.php
#cp _base/webapp/modulos/cont/views/fiscal/declaraciones/aux_imp_egresos.php demo8/webapp/modulos/cont/views/fiscal/declaraciones/aux_imp_egresos.php
#cp _base/webapp/modulos/cont/views/fiscal/declaraciones/aux_imp_ingresos.php demo8/webapp/modulos/cont/views/fiscal/declaraciones/aux_imp_ingresos.php
#cp _base/webapp/modulos/cont/views/fiscal/diot/AuxiliarControlIvaReporte.php demo8/webapp/modulos/cont/views/fiscal/diot/AuxiliarControlIvaReporte.php
#cp _base/webapp/modulos/cont/views/fiscal/diot/AuxiliarFormatoA29Reporte.php demo8/webapp/modulos/cont/views/fiscal/diot/AuxiliarFormatoA29Reporte.php
#cp _base/webapp/modulos/cont/views/fiscal/diot/EgresosSinControlDeIVAReporte.php demo8/webapp/modulos/cont/views/fiscal/diot/EgresosSinControlDeIVAReporte.php
#cp _base/webapp/modulos/cont/views/fiscal/diot/RepConcentradoIvaProveedor.php demo8/webapp/modulos/cont/views/fiscal/diot/RepConcentradoIvaProveedor.php
#cp _base/webapp/modulos/cont/views/fiscal/diot/flujoEfectivoIvaReporte.php demo8/webapp/modulos/cont/views/fiscal/diot/flujoEfectivoIvaReporte.php
#cp _base/webapp/modulos/cont/views/fiscal/pago_provicional/Conciliacion_IVA_contable_fiscal.php demo8/webapp/modulos/cont/views/fiscal/pago_provicional/Conciliacion_IVA_contable_fiscal.php
#cp _base/webapp/modulos/cont/views/reports/movcuentas_despues.php demo8/webapp/modulos/cont/views/reports/movcuentas_despues.php

# [demo24]
#cp _base/webapp/modulos/cont/models/RepPeriodoAcreditamiento.php demo24/webapp/modulos/cont/models/RepPeriodoAcreditamiento.php
##cp _base/webapp/modulos/cont/models/auxiliar_a29.php demo24/webapp/modulos/cont/models/auxiliar_a29.php
#cp _base/webapp/modulos/cont/models/conciliacion_IVA_contable_fiscal.php demo24/webapp/modulos/cont/models/conciliacion_IVA_contable_fiscal.php
#cp _base/webapp/modulos/cont/models/flujoEfectivoIva.php demo24/webapp/modulos/cont/models/flujoEfectivoIva.php
#cp _base/webapp/modulos/cont/views/fiscal/declaraciones/aux_imp_egresos.php demo24/webapp/modulos/cont/views/fiscal/declaraciones/aux_imp_egresos.php
#cp _base/webapp/modulos/cont/views/fiscal/declaraciones/aux_imp_ingresos.php demo24/webapp/modulos/cont/views/fiscal/declaraciones/aux_imp_ingresos.php
#cp _base/webapp/modulos/cont/views/fiscal/diot/AuxiliarControlIvaReporte.php demo24/webapp/modulos/cont/views/fiscal/diot/AuxiliarControlIvaReporte.php
#cp _base/webapp/modulos/cont/views/fiscal/diot/AuxiliarFormatoA29Reporte.php demo24/webapp/modulos/cont/views/fiscal/diot/AuxiliarFormatoA29Reporte.php
#cp _base/webapp/modulos/cont/views/fiscal/diot/EgresosSinControlDeIVAReporte.php demo24/webapp/modulos/cont/views/fiscal/diot/EgresosSinControlDeIVAReporte.php
#cp _base/webapp/modulos/cont/views/fiscal/diot/RepConcentradoIvaProveedor.php demo24/webapp/modulos/cont/views/fiscal/diot/RepConcentradoIvaProveedor.php
#cp _base/webapp/modulos/cont/views/fiscal/diot/flujoEfectivoIvaReporte.php demo24/webapp/modulos/cont/views/fiscal/diot/flujoEfectivoIvaReporte.php
#cp _base/webapp/modulos/cont/views/fiscal/pago_provicional/Conciliacion_IVA_contable_fiscal.php demo24/webapp/modulos/cont/views/fiscal/pago_provicional/Conciliacion_IVA_contable_fiscal.php
#cp _base/webapp/modulos/cont/views/reports/movcuentas_despues.php demo24/webapp/modulos/cont/views/reports/movcuentas_despues.php

# [demo32]
#cp _base/webapp/modulos/cont/models/RepPeriodoAcreditamiento.php demo32/webapp/modulos/cont/models/RepPeriodoAcreditamiento.php
#cp _base/webapp/modulos/cont/models/auxiliar_a29.php demo32/webapp/modulos/cont/models/auxiliar_a29.php
#cp _base/webapp/modulos/cont/models/conciliacion_IVA_contable_fiscal.php demo32/webapp/modulos/cont/models/conciliacion_IVA_contable_fiscal.php
#cp _base/webapp/modulos/cont/models/flujoEfectivoIva.php demo32/webapp/modulos/cont/models/flujoEfectivoIva.php
#cp _base/webapp/modulos/cont/views/fiscal/declaraciones/aux_imp_egresos.php demo32/webapp/modulos/cont/views/fiscal/declaraciones/aux_imp_egresos.php
#cp _base/webapp/modulos/cont/views/fiscal/declaraciones/aux_imp_ingresos.php demo32/webapp/modulos/cont/views/fiscal/declaraciones/aux_imp_ingresos.php
#cp _base/webapp/modulos/cont/views/fiscal/diot/AuxiliarControlIvaReporte.php demo32/webapp/modulos/cont/views/fiscal/diot/AuxiliarControlIvaReporte.php
#cp _base/webapp/modulos/cont/views/fiscal/diot/AuxiliarFormatoA29Reporte.php demo32/webapp/modulos/cont/views/fiscal/diot/AuxiliarFormatoA29Reporte.php
#cp _base/webapp/modulos/cont/views/fiscal/diot/EgresosSinControlDeIVAReporte.php demo32/webapp/modulos/cont/views/fiscal/diot/EgresosSinControlDeIVAReporte.php
#cp _base/webapp/modulos/cont/views/fiscal/diot/RepConcentradoIvaProveedor.php demo32/webapp/modulos/cont/views/fiscal/diot/RepConcentradoIvaProveedor.php
#cp _base/webapp/modulos/cont/views/fiscal/diot/flujoEfectivoIvaReporte.php demo32/webapp/modulos/cont/views/fiscal/diot/flujoEfectivoIvaReporte.php
#cp _base/webapp/modulos/cont/views/fiscal/pago_provicional/Conciliacion_IVA_contable_fiscal.php demo32/webapp/modulos/cont/views/fiscal/pago_provicional/Conciliacion_IVA_contable_fiscal.php
#cp _base/webapp/modulos/cont/views/reports/movcuentas_despues.php demo32/webapp/modulos/cont/views/reports/movcuentas_despues.php



### Appministra | Omar Vazquez | 2015-10-22 16:26

# [sistema]
#cp _base/webapp/modulos/SAT/PDF/CFDIPDF.php sistema/webapp/modulos/SAT/PDF/CFDIPDF.php
#cp _base/webapp/modulos/SAT/funcionesSAT.php sistema/webapp/modulos/SAT/funcionesSAT.php
#cp _base/webapp/modulos/facturacion/getInfoFacturas.php sistema/webapp/modulos/facturacion/getInfoFacturas.php
#cp _base/webapp/modulos/facturacion/reporteFacturas.php sistema/webapp/modulos/facturacion/reporteFacturas.php
#cp _base/webapp/modulos/punto_venta_nuevo/js/caja/caja.js sistema/webapp/modulos/punto_venta_nuevo/js/caja/caja.js
#cp _base/webapp/modulos/punto_venta_nuevo/models/caja/caja.php sistema/webapp/modulos/punto_venta_nuevo/models/caja/caja.php



### Appministra | Omar Vazquez | 2015-10-22 16:25
#cp _base/webapp/modulos/punto_venta_nuevo/models/caja/caja.php sistema/webapp/modulos/punto_venta_nuevo/models/caja/caja.php

### Foodware | Fer de la Cruz | 2015-10-21 16:19

# [gourmet]
#mkdir gourmet/webapp/modulos/agenda/models
#cp _base/webapp/modulos/agenda/models/connection_sqli.php gourmet/webapp/modulos/agenda/models/connection_sqli.php
#cp _base/webapp/modulos/restaurantes/ajax.php gourmet/webapp/modulos/restaurantes/ajax.php
#cp _base/webapp/modulos/restaurantes/controllers/comandas.php gourmet/webapp/modulos/restaurantes/controllers/comandas.php
#cp _base/webapp/modulos/restaurantes/js/comandas/comandas.js gourmet/webapp/modulos/restaurantes/js/comandas/comandas.js
#cp _base/webapp/modulos/restaurantes/js/jquery.tooltipster.js gourmet/webapp/modulos/restaurantes/js/jquery.tooltipster.js
#cp _base/webapp/modulos/restaurantes/js/jquery.tooltipster.min.js gourmet/webapp/modulos/restaurantes/js/jquery.tooltipster.min.js
#cp _base/webapp/modulos/restaurantes/js/pedidos/pedidos.js gourmet/webapp/modulos/restaurantes/js/pedidos/pedidos.js
#cp _base/webapp/modulos/restaurantes/models/comandas.php gourmet/webapp/modulos/restaurantes/models/comandas.php
#cp _base/webapp/modulos/restaurantes/models/pedidosactivos.php gourmet/webapp/modulos/restaurantes/models/pedidosactivos.php
#cp _base/webapp/modulos/restaurantes/views/comandas/Gmesas.php gourmet/webapp/modulos/restaurantes/views/comandas/Gmesas.php
#cp _base/webapp/modulos/restaurantes/views/comandas/comanda.php gourmet/webapp/modulos/restaurantes/views/comandas/comanda.php
#
#cp -r _base/webapp/modulos/restaurantes/imgcomandas/ gourmet/webapp/modulos/restaurantes/
#cp -r _base/webapp/modulos/restaurantes/reservaciones/ gourmet/webapp/modulos/restaurantes/
#
#chmod -R 777 gourmet/webapp/modulos/restaurantes/imgcomandas/
#chown -R www-data gourmet/webapp/modulos/restaurantes/imgcomandas/
#chgrp -R www-data gourmet/webapp/modulos/restaurantes/imgcomandas/
#
#chmod -R 777 gourmet/webapp/modulos/restaurantes/reservaciones/
#chown -R www-data gourmet/webapp/modulos/restaurantes/reservaciones/
#chgrp -R www-data gourmet/webapp/modulos/restaurantes/reservaciones/
#
#
## [ebustamante]
#mkdir ebustamante/webapp/modulos/agenda/models
#cp _base/webapp/modulos/agenda/models/connection_sqli.php ebustamante/webapp/modulos/agenda/models/connection_sqli.php
#cp _base/webapp/modulos/restaurantes/ajax.php ebustamante/webapp/modulos/restaurantes/ajax.php
#cp _base/webapp/modulos/restaurantes/controllers/comandas.php ebustamante/webapp/modulos/restaurantes/controllers/comandas.php
#cp _base/webapp/modulos/restaurantes/js/comandas/comandas.js ebustamante/webapp/modulos/restaurantes/js/comandas/comandas.js
#cp _base/webapp/modulos/restaurantes/js/jquery.tooltipster.js ebustamante/webapp/modulos/restaurantes/js/jquery.tooltipster.js
#cp _base/webapp/modulos/restaurantes/js/jquery.tooltipster.min.js ebustamante/webapp/modulos/restaurantes/js/jquery.tooltipster.min.js
#cp _base/webapp/modulos/restaurantes/js/pedidos/pedidos.js ebustamante/webapp/modulos/restaurantes/js/pedidos/pedidos.js
#cp _base/webapp/modulos/restaurantes/models/comandas.php ebustamante/webapp/modulos/restaurantes/models/comandas.php
#cp _base/webapp/modulos/restaurantes/models/pedidosactivos.php ebustamante/webapp/modulos/restaurantes/models/pedidosactivos.php
#cp _base/webapp/modulos/restaurantes/views/comandas/Gmesas.php ebustamante/webapp/modulos/restaurantes/views/comandas/Gmesas.php
#cp _base/webapp/modulos/restaurantes/views/comandas/comanda.php ebustamante/webapp/modulos/restaurantes/views/comandas/comanda.php
#
#cp -r _base/webapp/modulos/restaurantes/imgcomandas/ ebustamante/webapp/modulos/restaurantes/
#cp -r _base/webapp/modulos/restaurantes/reservaciones/ ebustamante/webapp/modulos/restaurantes/
#
#chmod -R 777 ebustamante/webapp/modulos/restaurantes/imgcomandas/
#chown -R www-data ebustamante/webapp/modulos/restaurantes/imgcomandas/
#chgrp -R www-data ebustamante/webapp/modulos/restaurantes/imgcomandas/
#
#chmod -R 777 ebustamante/webapp/modulos/restaurantes/reservaciones/
#chown -R www-data ebustamante/webapp/modulos/restaurantes/reservaciones/
#chgrp -R www-data ebustamante/webapp/modulos/restaurantes/reservaciones/
#

### Appministra | Omar Vazquez | 2015-10-20 17:16
#cp _base/webapp/modulos/mrp/js/product.js restesting/webapp/modulos/mrp/js/product.js

###Xtructur | Christian Díaz | 2015-10-20 16:00
#cp /srv/www/htdocs/clientes/_base/webapp/modulos/xtructur/index.php /srv/www/construccion/mlog/webapp/modulos/xtructur/index.php
#cp /srv/www/htdocs/clientes/_base/webapp/modulos/xtructur/ajax.php /srv/www/construccion/mlog/webapp/modulos/xtructur/ajax.php
#cp /srv/www/htdocs/clientes/_base/webapp/modulos/xtructur/funcionesIndex.js /srv/www/construccion/mlog/webapp/modulos/xtructur/funcionesIndex.js
#cp /srv/www/htdocs/clientes/_base/webapp/modulos/xtructur/jsest_cheques_view2.php /srv/www/construccion/mlog/webapp/modulos/xtructur/jsest_cheques_view2.php
#cp /srv/www/htdocs/clientes/_base/webapp/modulos/xtructur/jstomaduria.php /srv/www/construccion/mlog/webapp/modulos/xtructur/jstomaduria.php
#cp /srv/www/htdocs/clientes/_base/webapp/modulos/xtructur/sql_visualizar_pedi.php /srv/www/construccion/mlog/webapp/modulos/xtructur/sql_visualizar_pedi.php
#cp /srv/www/htdocs/clientes/_base/webapp/modulos/xtructur/sql_visualizar_remesa_rep2.php /srv/www/construccion/mlog/webapp/modulos/xtructur/sql_visualizar_remesa_rep2.php
#cp /srv/www/htdocs/clientes/_base/webapp/modulos/xtructur/sql_visualizar_requit.php /srv/www/construccion/mlog/webapp/modulos/xtructur/sql_visualizar_requit.php
#cp /srv/www/htdocs/clientes/_base/webapp/modulos/xtructur/jsest_indirectos_view.php /srv/www/construccion/mlog/webapp/modulos/xtructur/jsest_indirectos_view.php
#cp /srv/www/htdocs/clientes/_base/webapp/modulos/xtructur/jsest_remesas_view_rep.php /srv/www/construccion/mlog/webapp/modulos/xtructur/jsest_remesas_view_rep.php
#cp /srv/www/htdocs/clientes/_base/webapp/modulos/xtructur/sql_visualizar_remesa_rep.php /srv/www/construccion/mlog/webapp/modulos/xtructur/sql_visualizar_remesa_rep.php
#cp /srv/www/htdocs/clientes/_base/webapp/modulos/xtructur/jsretenciones.php /srv/www/construccion/mlog/webapp/modulos/xtructur/jsretenciones.php
#cp /srv/www/htdocs/clientes/_base/webapp/modulos/xtructur/sql_jsretenciones.php /srv/www/construccion/mlog/webapp/modulos/xtructur/sql_jsretenciones.php
##cp /srv/www/htdocs/clientes/_base/webapp/modulos/xtructur/jscontrol_indirectos_rep.php /srv/www/construccion/mlog/webapp/modulos/xtructur/jscontrol_indirectos_rep.php
#cp /srv/www/htdocs/clientes/_base/webapp/modulos/xtructur/sql_jscat_especialidades.php /srv/www/construccion/mlog/webapp/modulos/xtructur/sql_jscat_especialidades.php
#
#


###Appministra | Omar Vazquez | 2015-10-19 18:29
#cp -r  _base/webapp/modulos/cotizaciones/ ebustamante/webapp/modulos/
#cp -r  _base/webapp/modulos/cotizaciones/ acomee/webapp/modulos/

#chmod -R 777 ebustamante/webapp/modulos/cotizaciones
#chown -R www-data ebustamante/webapp/modulos/cotizaciones
#chgrp -R www-data ebustamante/webapp/modulos/cotizaciones
#
#chmod -R 777 acomee/webapp/modulos/cotizaciones
#chown -R www-data acomee/webapp/modulos/cotizaciones
#chgrp -R www-data acomee/webapp/modulos/cotizaciones





###Acontia | Carmen Gutíerrez | 2015-10-19 17:48
#echo "\n >>>	demo24	"
#cp	_base/webapp/modulos/cont/controllers/reports.php	demo24/webapp/modulos/cont/controllers/reports.php
#cp	_base/webapp/modulos/cont/models/reports.php	demo24/webapp/modulos/cont/models/reports.php
#cp	_base/webapp/modulos/cont/views/reports/balanceGeneralReportePeriodos.php	demo24/webapp/modulos/cont/views/reports/balanceGeneralReportePeriodos.php
#cp	_base/webapp/modulos/cont/views/reports/balanzaComprobacionReporte2.php	demo24/webapp/modulos/cont/views/reports/balanzaComprobacionReporte2.php
#cp	_base/webapp/modulos/cont/views/reports/estadoResultadosReporte.php	demo24/webapp/modulos/cont/views/reports/estadoResultadosReporte.php


###Acontia | Carmen Gutíerrez | 2015-10-19 13:22
#cp	_base/webapp/modulos/cont/models/reports.php	demo24/webapp/modulos/cont/models/reports.php
#cp	_base/webapp/modulos/cont/views/reports/balanceGeneralReporte.php	demo24/webapp/modulos/cont/views/reports/balanceGeneralReporte.php
#cp	_base/webapp/modulos/cont/views/reports/balanzaComprobacionReporte2.php	demo24/webapp/modulos/cont/views/reports/balanzaComprobacionReporte2.php


###Acontia | Carmen Gutíerrez | 2015-10-19 11:16
#cp	_base/webapp/modulos/bancos/controllers/importarestadocuenta.php	demo24/webapp/modulos/bancos/controllers/importarestadocuenta.php
#cp	_base/webapp/modulos/bancos/images/xls_icon.gif	demo24/webapp/modulos/bancos/images/xls_icon.gif
#
#mkdir demo24/webapp/modulos/bancos/importar/
#chmod -R 777 demo24/webapp/modulos/bancos/importar/
#chown -R www-data demo24/webapp/modulos/bancos/importar
##chgrp -R www-data demo24/webapp/modulos/bancos/importar
#
#mkdir demo24/webapp/modulos/bancos/importar/Excel/
#chmod -R 777 demo24/webapp/modulos/bancos/importar/Excel/
#chown -R www-data demo24/webapp/modulos/bancos/importar/Excel
#chgrp -R www-data demo24/webapp/modulos/bancos/importar/Excel
#
#mkdir demo24/webapp/modulos/bancos/views/importador/
#chmod -R 777 demo24/webapp/modulos/bancos/views/importador/
#chown -R www-data demo24/webapp/modulos/bancos/views/importador
#chgrp -R www-data demo24/webapp/modulos/bancos/views/importador
#
#cp	_base/webapp/modulos/bancos/importar/Excel/oleread.inc	demo24/webapp/modulos/bancos/importar/Excel/oleread.inc
#cp	_base/webapp/modulos/bancos/importar/Excel/reader.php	demo24/webapp/modulos/bancos/importar/Excel/reader.php
#cp	_base/webapp/modulos/bancos/js/importar.js	demo24/webapp/modulos/bancos/js/importar.js
#cp	_base/webapp/modulos/bancos/models/importarestadocuenta.php	demo24/webapp/modulos/bancos/models/importarestadocuenta.php
#cp	_base/webapp/modulos/bancos/plantillabancos.xls	demo24/webapp/modulos/bancos/plantillabancos.xls
#cp	_base/webapp/modulos/bancos/views/importador/importaestadocuenta.php	demo24/webapp/modulos/bancos/views/importador/importaestadocuenta.php
#cp	_base/webapp/modulos/cont/views/captpolizas/provisionmultiple.php	demo24/webapp/modulos/cont/views/captpolizas/provisionmultiple.php
#


###Acontia | Carmen Gutíerrez | 2015-10-19 11:11
#cp	_base/webapp/modulos/cont/models/reports.php	demo24/webapp/modulos/cont/models/reports.php
#cp	_base/webapp/modulos/cont/views/reports/balanceGeneralReporte.php	demo24/webapp/modulos/cont/views/reports/balanceGeneralReporte.php
#cp	_base/webapp/modulos/cont/views/reports/balanceGeneralReportePeriodos.php	demo24/webapp/modulos/cont/views/reports/balanceGeneralReportePeriodos.php
#cp	_base/webapp/modulos/cont/views/reports/estadoResultadosReporte.php	demo24/webapp/modulos/cont/views/reports/estadoResultadosReporte.php
#cp	_base/webapp/modulos/cont/views/reports/estadoResultadosxSegmento.php	demo24/webapp/modulos/cont/views/reports/estadoResultadosxSegmento.php


###Acontia | Carmen Gutíerrez | 2015-10-19 09:45
#cp	_base/webapp/modulos/cont/views/reports/balanceGeneralReporte.php	demo8/webapp/modulos/cont/views/reports/balanceGeneralReporte.php
#cp	_base/webapp/modulos/cont/views/reports/balanceGeneralReportePeriodos.php	demo8/webapp/modulos/cont/views/reports/balanceGeneralReportePeriodos.php
#cp	_base/webapp/modulos/cont/views/reports/estadoResultadosReporte.php	demo8/webapp/modulos/cont/views/reports/estadoResultadosReporte.php
#cp	_base/webapp/modulos/cont/views/reports/estadoResultadosxSegmento.php	demo8/webapp/modulos/cont/views/reports/estadoResultadosxSegmento.php
#
#cp	_base/webapp/modulos/cont/views/reports/balanceGeneralReporte.php	demo24/webapp/modulos/cont/views/reports/balanceGeneralReporte.php
#cp	_base/webapp/modulos/cont/views/reports/balanceGeneralReportePeriodos.php	demo24/webapp/modulos/cont/views/reports/balanceGeneralReportePeriodos.php
#cp	_base/webapp/modulos/cont/views/reports/estadoResultadosReporte.php	demo24/webapp/modulos/cont/views/reports/estadoResultadosReporte.php
#cp	_base/webapp/modulos/cont/views/reports/estadoResultadosxSegmento.php	demo24/webapp/modulos/cont/views/reports/estadoResultadosxSegmento.php
#
#cp	_base/webapp/modulos/cont/views/reports/balanceGeneralReporte.php	demo32/webapp/modulos/cont/views/reports/balanceGeneralReporte.php
#cp	_base/webapp/modulos/cont/views/reports/balanceGeneralReportePeriodos.php	demo32/webapp/modulos/cont/views/reports/balanceGeneralReportePeriodos.php
#cp	_base/webapp/modulos/cont/views/reports/estadoResultadosReporte.php	demo32/webapp/modulos/cont/views/reports/estadoResultadosReporte.php
#cp	_base/webapp/modulos/cont/views/reports/estadoResultadosxSegmento.php	demo32/webapp/modulos/cont/views/reports/estadoResultadosxSegmento.php
#
#
#
#
###Acontia | Ivan Cuenca | 2015-10-09 13:20 | Poder subir cuentas desde un layout  en excel en una catalogo existente calculadora aritmetica
#[demo1]
#cp _base/webapp/modulos/cont/Formato_cuentas2.xls demo1/webapp/modulos/cont/Formato_cuentas2.xls
#cp _base/webapp/modulos/cont/controllers/config.php demo1/webapp/modulos/cont/controllers/config.php
#cp _base/webapp/modulos/cont/importar/import_cuentas.php demo1/webapp/modulos/cont/importar/import_cuentas.php
#cp _base/webapp/modulos/cont/js/accountsTree.js.php demo1/webapp/modulos/cont/js/accountsTree.js.php
#cp _base/webapp/modulos/cont/views/accountsTree/index.php demo1/webapp/modulos/cont/views/accountsTree/index.php
#cp _base/webapp/modulos/cont/views/captpolizas/actpolizas.php demo1/webapp/modulos/cont/views/captpolizas/actpolizas.php
#cp _base/webapp/modulos/cont/views/captpolizas/capturapolizas.php demo1/webapp/modulos/cont/views/captpolizas/capturapolizas.php
#cp _base/webapp/modulos/cont/js/BigEval.js demo1/webapp/modulos/cont/js/BigEval.js
#cp _base/webapp/modulos/cont/js/agregar.js demo1/webapp/modulos/cont/js/agregar.js
#cp _base/webapp/modulos/cont/views/captpolizas/agregar.php demo1/webapp/modulos/cont/views/captpolizas/agregar.php
##[demo1]
#cp _base/webapp/modulos/cont/Formato_cuentas2.xls demo8/webapp/modulos/cont/Formato_cuentas2.xls
#cp _base/webapp/modulos/cont/controllers/config.php demo8/webapp/modulos/cont/controllers/config.php
#cp _base/webapp/modulos/cont/importar/import_cuentas.php demo8/webapp/modulos/cont/importar/import_cuentas.php
#cp _base/webapp/modulos/cont/js/accountsTree.js.php demo8/webapp/modulos/cont/js/accountsTree.js.php
#cp _base/webapp/modulos/cont/views/accountsTree/index.php demo8/webapp/modulos/cont/views/accountsTree/index.php
#cp _base/webapp/modulos/cont/views/captpolizas/actpolizas.php demo8/webapp/modulos/cont/views/captpolizas/actpolizas.php
#cp _base/webapp/modulos/cont/views/captpolizas/capturapolizas.php demo8/webapp/modulos/cont/views/captpolizas/capturapolizas.php
#cp _base/webapp/modulos/cont/js/BigEval.js demo8/webapp/modulos/cont/js/BigEval.js
#cp _base/webapp/modulos/cont/js/agregar.js demo8/webapp/modulos/cont/js/agregar.js
#cp _base/webapp/modulos/cont/views/captpolizas/agregar.php demo8/webapp/modulos/cont/views/captpolizas/agregar.php
##[demo24]
#cp _base/webapp/modulos/cont/Formato_cuentas2.xls demo24/webapp/modulos/cont/Formato_cuentas2.xls
#cp _base/webapp/modulos/cont/controllers/config.php demo24/webapp/modulos/cont/controllers/config.php
#cp _base/webapp/modulos/cont/importar/import_cuentas.php demo24/webapp/modulos/cont/importar/import_cuentas.php
#cp _base/webapp/modulos/cont/js/accountsTree.js.php demo24/webapp/modulos/cont/js/accountsTree.js.php
#cp _base/webapp/modulos/cont/views/accountsTree/index.php demo24/webapp/modulos/cont/views/accountsTree/index.php
#cp _base/webapp/modulos/cont/views/captpolizas/actpolizas.php demo24/webapp/modulos/cont/views/captpolizas/actpolizas.php
#cp _base/webapp/modulos/cont/views/captpolizas/capturapolizas.php demo24/webapp/modulos/cont/views/captpolizas/capturapolizas.php
#cp _base/webapp/modulos/cont/js/BigEval.js demo24/webapp/modulos/cont/js/BigEval.js
#cp _base/webapp/modulos/cont/js/agregar.js demo24/webapp/modulos/cont/js/agregar.js
#cp _base/webapp/modulos/cont/views/captpolizas/agregar.php demo24/webapp/modulos/cont/views/captpolizas/agregar.php
##[demo32]
#cp _base/webapp/modulos/cont/Formato_cuentas2.xls demo32/webapp/modulos/cont/Formato_cuentas2.xls
#cp _base/webapp/modulos/cont/controllers/config.php demo32/webapp/modulos/cont/controllers/config.php
#cp _base/webapp/modulos/cont/importar/import_cuentas.php demo32/webapp/modulos/cont/importar/import_cuentas.php
#cp _base/webapp/modulos/cont/js/accountsTree.js.php demo32/webapp/modulos/cont/js/accountsTree.js.php
#cp _base/webapp/modulos/cont/views/accountsTree/index.php demo32/webapp/modulos/cont/views/accountsTree/index.php
#cp _base/webapp/modulos/cont/views/captpolizas/actpolizas.php demo32/webapp/modulos/cont/views/captpolizas/actpolizas.php
#cp _base/webapp/modulos/cont/views/captpolizas/capturapolizas.php demo32/webapp/modulos/cont/views/captpolizas/capturapolizas.php
#cp _base/webapp/modulos/cont/js/BigEval.js demo32/webapp/modulos/cont/js/BigEval.js
#cp _base/webapp/modulos/cont/js/agregar.js demo32/webapp/modulos/cont/js/agregar.js
#cp _base/webapp/modulos/cont/views/captpolizas/agregar.php demo32/webapp/modulos/cont/views/captpolizas/agregar.php

###Acontia | Carmen Gitierrez | 2015-10-07 12:16 | 

##Acontia | Carmen Gutierrez | 2015-10-06 18:36 | Asigna cuenta en automatico para Deducciones y Percepcionesy SQL para correcion en polizas de nomi_empleados
#cp _base/webapp/modulos/cont/controllers/config.php demo24/webapp/modulos/cont/controllers/config.php
#cp _base/webapp/modulos/cont/js/provisionomina.js demo24/webapp/modulos/cont/js/provisionomina.js
#cp _base/webapp/modulos/cont/models/config.php demo24/webapp/modulos/cont/models/config.php
#cp _base/webapp/modulos/cont/views/config/configAccounts.php demo24/webapp/modulos/cont/views/config/configAccounts.php
#cp _base/webapp/modulos/cont/views/nominas/nominas.php demo24/webapp/modulos/cont/views/nominas/nominas.php


##Appministra | Omar Vazquez | 2015-10-06 18:13 | Numero de interior y exterior en las direcciones de los datos de facturacion 
#cp _base/webapp/modulos/punto_venta/funcionesBD/importar_clientes_inadem.php ebustamante/webapp/modulos/punto_venta/funcionesBD/importar_clientes_inadem.php
#cp _base/webapp/modulos/punto_venta/funcionesBD/importar_clientes_inadem.php acomee/webapp/modulos/punto_venta/funcionesBD/importar_clientes_inadem.php

##Foodware | Fernando de la Cruz | 2015-10-06 17:46 | Mejoras a la interfaz y las funciones en restaurantes.
#cp _base/webapp/modulos/restaurantes/controllers/comandas.php ebustamante/webapp/modulos/restaurantes/controllers/comandas.php
#cp _base/webapp/modulos/restaurantes/images/impresora.jpeg ebustamante/webapp/modulos/restaurantes/images/impresora.jpeg
#cp _base/webapp/modulos/restaurantes/images/impresora2.jpeg ebustamante/webapp/modulos/restaurantes/images/impresora2.jpeg
#cp _base/webapp/modulos/restaurantes/imgcomandas/agenda.png ebustamante/webapp/modulos/restaurantes/imgcomandas/agenda.png
#cp _base/webapp/modulos/restaurantes/imprimecomandas.php ebustamante/webapp/modulos/restaurantes/imprimecomandas.php
#cp _base/webapp/modulos/restaurantes/js/comandas/comandas.js ebustamante/webapp/modulos/restaurantes/js/comandas/comandas.js
#cp _base/webapp/modulos/restaurantes/models/comandas.php ebustamante/webapp/modulos/restaurantes/models/comandas.php
#cp _base/webapp/modulos/restaurantes/views/comandas/comanda.php ebustamante/webapp/modulos/restaurantes/views/comandas/comanda.php
#cp _base/webapp/modulos/restaurantes/views/comandas/Gmesas.php ebustamante/webapp/modulos/restaurantes/views/comandas/Gmesas.php
#cp _base/webapp/modulos/restaurantes/views/comandas/imprimecomandas.php ebustamante/webapp/modulos/restaurantes/views/comandas/imprimecomandas.php
#cp _base/webapp/modulos/restaurantes/views/comandas/promedio_comensal.php ebustamante/webapp/modulos/restaurantes/views/comandas/promedio_comensal.php
#cp _base/webapp/modulos/restaurantes/views/comandas/promedio_comensal.php ebustamante/webapp/modulos/restaurantes/views/comandas/promedio_comensal.php


##Acontia | Carmen Gutierrez | 2015-10-06 14:01 | Se agrego excepción de Otros en deducibles y percepciones de nomina a nivel seleccion afectable de cuentas y se omitio de la asignacion
#[demo24]
#cp _base/webapp/modulos/cont/controllers/nomina.php demo24/webapp/modulos/cont/controllers/nomina.php
#cp _base/webapp/modulos/cont/js/provisionomina.js demo24/webapp/modulos/cont/js/provisionomina.js
#cp _base/webapp/modulos/cont/models/config.php demo24/webapp/modulos/cont/models/config.php
#cp _base/webapp/modulos/cont/views/captpolizas/provisionmultipledetallado.php demo24/webapp/modulos/cont/views/captpolizas/provisionmultipledetallado.php
#cp _base/webapp/modulos/cont/views/nominas/nominas.php demo24/webapp/modulos/cont/views/nominas/nominas.php

##Appministra | Omar Vazquez | 2015-10-06 13:56 | Importacion de clientes con sus datos de INADEM
#cp _base/webapp/modulos/punto_venta/funcionesBD/importar_clientes_inadem.php acomee/webapp/modulos/punto_venta/funcionesBD/importar_clientes_inadem.php
#cp _base/webapp/modulos/cont/importar/import_cuentas.php demo32/webapp/modulos/cont/importar/import_cuentas.php
#cp _base/webapp/modulos/cont/js/accountsTree.js.php demo32/webapp/modulos/cont/js/accountsTree.js.php
#cp _base/webapp/modulos/cont/views/accountsTree/index.php demo32/webapp/modulos/cont/views/accountsTree/index.php
#cp _base/webapp/modulos/cont/views/captpolizas/actpolizas.php demo32/webapp/modulos/cont/views/captpolizas/actpolizas.php
#cp _base/webapp/modulos/cont/views/captpolizas/capturapolizas.php demo32/webapp/modulos/cont/views/captpolizas/capturapolizas.php
#cp _base/webapp/modulos/cont/js/BigEval.js demo32/webapp/modulos/cont/js/BigEval.js
#cp _base/webapp/modulos/cont/js/agregar.js demo32/webapp/modulos/cont/js/agregar.js
#cp _base/webapp/modulos/cont/views/captpolizas/agregar.php demo32/webapp/modulos/cont/views/captpolizas/agregar.php


##Acontia | Ivan Cuenca | 2015-10-09 13:20 | Poder subir cuentas desde un layout  en excel en una catalogo existente calculadora aritmetica
#[demo1]
#cp _base/webapp/modulos/cont/Formato_cuentas2.xls demo1/webapp/modulos/cont/Formato_cuentas2.xls
#cp _base/webapp/modulos/cont/controllers/config.php demo1/webapp/modulos/cont/controllers/config.php
#cp _base/webapp/modulos/cont/importar/import_cuentas.php demo1/webapp/modulos/cont/importar/import_cuentas.php
#cp _base/webapp/modulos/cont/js/accountsTree.js.php demo1/webapp/modulos/cont/js/accountsTree.js.php
#cp _base/webapp/modulos/cont/views/accountsTree/index.php demo1/webapp/modulos/cont/views/accountsTree/index.php
#cp _base/webapp/modulos/cont/views/captpolizas/actpolizas.php demo1/webapp/modulos/cont/views/captpolizas/actpolizas.php
#cp _base/webapp/modulos/cont/views/captpolizas/capturapolizas.php demo1/webapp/modulos/cont/views/captpolizas/capturapolizas.php
#cp _base/webapp/modulos/cont/js/BigEval.js demo1/webapp/modulos/cont/js/BigEval.js
#cp _base/webapp/modulos/cont/js/agregar.js demo1/webapp/modulos/cont/js/agregar.js
#cp _base/webapp/modulos/cont/views/captpolizas/agregar.php demo1/webapp/modulos/cont/views/captpolizas/agregar.php
##[demo1]
#cp _base/webapp/modulos/cont/Formato_cuentas2.xls demo8/webapp/modulos/cont/Formato_cuentas2.xls
#cp _base/webapp/modulos/cont/controllers/config.php demo8/webapp/modulos/cont/controllers/config.php
#cp _base/webapp/modulos/cont/importar/import_cuentas.php demo8/webapp/modulos/cont/importar/import_cuentas.php
#cp _base/webapp/modulos/cont/js/accountsTree.js.php demo8/webapp/modulos/cont/js/accountsTree.js.php
#cp _base/webapp/modulos/cont/views/accountsTree/index.php demo8/webapp/modulos/cont/views/accountsTree/index.php
#cp _base/webapp/modulos/cont/views/captpolizas/actpolizas.php demo8/webapp/modulos/cont/views/captpolizas/actpolizas.php
#cp _base/webapp/modulos/cont/views/captpolizas/capturapolizas.php demo8/webapp/modulos/cont/views/captpolizas/capturapolizas.php
#cp _base/webapp/modulos/cont/js/BigEval.js demo8/webapp/modulos/cont/js/BigEval.js
#cp _base/webapp/modulos/cont/js/agregar.js demo8/webapp/modulos/cont/js/agregar.js
#cp _base/webapp/modulos/cont/views/captpolizas/agregar.php demo8/webapp/modulos/cont/views/captpolizas/agregar.php
##[demo24]
#cp _base/webapp/modulos/cont/Formato_cuentas2.xls demo24/webapp/modulos/cont/Formato_cuentas2.xls
#cp _base/webapp/modulos/cont/controllers/config.php demo24/webapp/modulos/cont/controllers/config.php
#cp _base/webapp/modulos/cont/importar/import_cuentas.php demo24/webapp/modulos/cont/importar/import_cuentas.php
#cp _base/webapp/modulos/cont/js/accountsTree.js.php demo24/webapp/modulos/cont/js/accountsTree.js.php
#cp _base/webapp/modulos/cont/views/accountsTree/index.php demo24/webapp/modulos/cont/views/accountsTree/index.php
#cp _base/webapp/modulos/cont/views/captpolizas/actpolizas.php demo24/webapp/modulos/cont/views/captpolizas/actpolizas.php
#cp _base/webapp/modulos/cont/views/captpolizas/capturapolizas.php demo24/webapp/modulos/cont/views/captpolizas/capturapolizas.php
#cp _base/webapp/modulos/cont/js/BigEval.js demo24/webapp/modulos/cont/js/BigEval.js
#cp _base/webapp/modulos/cont/js/agregar.js demo24/webapp/modulos/cont/js/agregar.js
#cp _base/webapp/modulos/cont/views/captpolizas/agregar.php demo24/webapp/modulos/cont/views/captpolizas/agregar.php
##[demo32]
#cp _base/webapp/modulos/cont/Formato_cuentas2.xls demo32/webapp/modulos/cont/Formato_cuentas2.xls
#cp _base/webapp/modulos/cont/controllers/config.php demo32/webapp/modulos/cont/controllers/config.php
#cp _base/webapp/modulos/cont/importar/import_cuentas.php demo32/webapp/modulos/cont/importar/import_cuentas.php
#cp _base/webapp/modulos/cont/js/accountsTree.js.php demo32/webapp/modulos/cont/js/accountsTree.js.php
#cp _base/webapp/modulos/cont/views/accountsTree/index.php demo32/webapp/modulos/cont/views/accountsTree/index.php
#cp _base/webapp/modulos/cont/views/captpolizas/actpolizas.php demo32/webapp/modulos/cont/views/captpolizas/actpolizas.php
#cp _base/webapp/modulos/cont/views/captpolizas/capturapolizas.php demo32/webapp/modulos/cont/views/captpolizas/capturapolizas.php
#cp _base/webapp/modulos/cont/js/BigEval.js demo32/webapp/modulos/cont/js/BigEval.js
#cp _base/webapp/modulos/cont/js/agregar.js demo32/webapp/modulos/cont/js/agregar.js
#cp _base/webapp/modulos/cont/views/captpolizas/agregar.php demo32/webapp/modulos/cont/views/captpolizas/agregar.php
#
#
###


echo $(date +"%y-%m-%d %k:%M:%S.%N")" -> Termina special.sh"
