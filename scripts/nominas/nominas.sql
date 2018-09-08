
ALTER TABLE `nomi_deducciones`
MODIFY COLUMN descripcion VARCHAR(200);

ALTER TABLE `nomi_percepciones`
MODIFY COLUMN descripcion VARCHAR(300);

ALTER TABLE  nomi_otros_pagos
add COLUMN activo tinyint(1);

ALTER TABLE  nomi_percepciones
add COLUMN activo tinyint(1);

ALTER TABLE  nomi_deducciones
add COLUMN activo tinyint(1);


update nomi_deducciones set activo=1;
update nomi_otros_pagos set activo=1;
update nomi_percepciones set activo=1;
update nomi_percepciones set activo=0 where idAgrupador in(14,15);



CREATE TABLE `nomi_acumulados` (
  `idacumulado` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) CHARACTER SET latin1 NOT NULL,
  `idtipoacumulado` int(11) DEFAULT NULL,
  PRIMARY KEY (`idacumulado`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `nomi_base_cotizacion` (
  `idbase` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`idbase`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `nomi_base_cotizacion` (`idbase`, `nombre`)
VALUES
    (1,'Fijo'),
    (2,'Variable'),
    (3,'Mixto');

INSERT INTO `nomi_acumulados` (`idacumulado`, `nombre`, `idtipoacumulado`)
VALUES
    (1,'Acumulado especial 1',2),
    (2,'Acumulado especial 2',2),
    (3,'Acumulado especial 3',2),
    (4,'Acumulado especial 4',2),
    (5,'Dias IMSS Ausencias',1),
    (6,'Dias IMSS Incapacidad',1),
    (7,'Dias PTU no participan',1),
    (8,'IMSS 1 EG (Art. 25)',2),
    (9,'IMSS 2 EG (Art.106-I,108)',2),
    (10,'IMSS 3 EG (Art.106-II)',2),
    (11,'IMSS 4 EG (Art.107)',2),
    (12,'IMSS 5 Invalidez y Vida',2),
    (13,'IMSS 6 Cesantia y Vejez',2),
    (14,'IMSS 7 Guarderias',2),
    (15,'IMSS 8 Retiro',2),
    (16,'IMSS 9 Riesgo de Trabajo',2),
    (17,'IMSS Percepcion Variable',2),
    (18,'IMSS Total empleado',2),
    (19,'IMSS Total Empresa',2),
    (20,'ISPT antes de Subs al Empleo',2),
    (21,'ISR  a compensar o retener',2),
    (22,'ISR Base Exenta',2),
    (23,'ISR Base Gravada',2),
    (24,'ISR Base Gravada  Art142',2),
    (25,'ISR DEL',2),
    (26,'ISR Gratificación exenta',2),
    (27,'ISR Liquidacion exento',2),
    (28,'ISR Liquidacion gravado',2),
    (29,'ISR Perc.especiales exentas',2),
    (30,'ISR Perc.especiales grav.',2),
    (31,'ISR Prima vac. exenta',2),
    (32,'ISR SUBS AL EMPLEO DEL',2),
    (33,'ISR Subsidio acreditable',2),
    (34,'ISR Subsidio no acreditable',2),
    (35,'ISR Total de percepciones',2),
    (36,'Otras deducciones',2),
    (37,'PTU Ingresos acumulados para',2),
    (38,'PTU ISR exento',2),
    (39,'Subs al Empleo  Acreditado',2);




CREATE TABLE `nomi_ajustedias` (
  `idajuste` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`idajuste`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `nomi_ajustedias` (`idajuste`, `nombre`)
VALUES
    (1,'No Aplica'),
    (2,'Dias pagados, contando dias laborados'),
    (3,'Dias pagados, contando dias no laborados');


CREATE TABLE `nomi_antiguedades` (
  `idantiguedad` int(11) NOT NULL AUTO_INCREMENT,
  `dias_vac_conf` double DEFAULT NULL,
  `dias_vac_sind` double DEFAULT NULL,
  `porc_prima_conf` double DEFAULT NULL,
  `porc_prima_sind` double DEFAULT NULL,
  `dias_antig_conf` double DEFAULT NULL,
  `dias_antig_sind` double DEFAULT NULL,
  `dias_aguinaldo_c` double DEFAULT NULL,
  `dias_aguinaldo_si` double DEFAULT NULL,
  PRIMARY KEY (`idantiguedad`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `nomi_antiguedades` (`idantiguedad`, `dias_vac_conf`, `dias_vac_sind`, `porc_prima_conf`, `porc_prima_sind`, `dias_antig_conf`, `dias_antig_sind`, `dias_aguinaldo_c`, `dias_aguinaldo_si`)
VALUES
    (1,6,6,25,25,0,0,15,15),
    (2,8,8,25,25,0,0,15,15),
    (3,10,10,25,25,0,0,15,15),
    (4,12,12,25,25,0,0,15,15),
    (5,14,14,25,25,0,0,15,15),
    (6,14,14,25,25,0,0,15,15),
    (7,14,14,25,25,0,0,15,15),
    (8,14,14,25,25,0,0,15,15),
    (9,14,14,25,25,0,0,15,15),
    (10,16,16,25,25,0,0,15,15),
    (11,16,16,25,25,0,0,15,15),
    (12,16,16,25,25,0,0,15,15),
    (13,16,16,25,25,0,0,15,15),
    (14,16,16,25,25,0,0,15,15),
    (15,18,18,25,25,0,0,15,15),
    (16,18,18,25,25,0,0,15,15),
    (17,18,18,25,25,0,0,15,15),
    (18,18,18,25,25,0,0,15,15),
    (19,18,18,25,25,0,0,15,15),
    (20,20,20,25,25,0,0,15,15);


CREATE TABLE `nomi_base_pago` (
  `idbasepago` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `base` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`idbasepago`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `nomi_base_pago` (`idbasepago`, `base`)
VALUES
    (1,'Sueldo'),
    (2,'Comision'),
    (3,'Destajo'),
    (4,'Sueldo-Comision'),
    (5,'Sueldo-Destajo');


CREATE TABLE `nomi_conceptos` (
  `idconcepto` int(11) NOT NULL AUTO_INCREMENT,
  `concepto` varchar(10) DEFAULT NULL,
  `descripcion` varchar(100) CHARACTER SET latin1 NOT NULL,
  `global` tinyint(1) DEFAULT NULL,
  `liquidacion` tinyint(1) DEFAULT NULL,
  `especie` tinyint(1) DEFAULT NULL,
  `idAgrupador` int(11) NOT NULL DEFAULT '0',
  `idtipo` int(11) NOT NULL DEFAULT '0',
  `idhora` varchar(11) NOT NULL DEFAULT '0',
  `idFormapago` varchar(11) NOT NULL DEFAULT '0',
  `activo` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`idconcepto`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

INSERT INTO `nomi_conceptos` (`idconcepto`, `concepto`, `descripcion`, `global`, `liquidacion`, `especie`, `idAgrupador`, `idtipo`, `idhora`, `idFormapago`, `activo`)
VALUES
  (1, '1', 'Sueldo', 1, 1, 0, 1, 1, '0', '0', 1),
  (2, '3', 'Septimo dia', 1, 1, 0, 1, 1, '0', '0', 1),
  (3, '4', 'Horas extras', 1, 1, 0, 16, 1, '1,2,3', '', 1),
  (4, '5', 'Destajos', 1, 0, 0, 1, 1, '0', '0', 1),
  (5, '6', 'Comisiones', 0, 1, 0, 25, 1, '0', '0', 1),
  (6, '7', 'Incentivo de productividad', 0, 0, 0, 35, 1, '0', '0', 1),
  (7, '8', 'Incentivos demoras', 0, 0, 0, 35, 1, '0', '0', 1),
  (8, '9', 'Incapacidad pagada empresa', 0, 0, 0, 12, 1, '0', '0', 1),
  (9, '10', 'Prima dominical', 0, 0, 0, 17, 1, '0', '0', 1),
  (10, '11', 'Día festivo/descanso', 0, 0, 0, 35, 1, '0', '0', 1),
  (11, '12', 'Gratificación', 0, 0, 0, 35, 1, '0', '0', 1),
  (12, '13', 'Compensación', 0, 0, 0, 35, 1, '0', '0', 1),
  (13, '14', 'Premios eficiencia', 1, 0, 0, 35, 1, '0', '0', 1),
  (14, '15', 'Bono puntualidad', 0, 0, 0, 8, 1, '0', '0', 1),
  (15, '16', 'Retroactivo', 0, 0, 0, 1, 1, '0', '0', 1),
  (16, '17', 'Ajuste en sueldos', 0, 0, 0, 1, 1, '0', '0', 1),
  (17, '18', 'Anticipo de sueldos', 0, 0, 0, 1, 1, '0', '0', 1),
  (18, '19', 'Vacaciones a tiempo', 0, 1, 0, 1, 1, '0', '0', 1),
  (19, '20', 'Prima de vacaciones a tiempo', 0, 1, 0, 18, 1, '', '', 1),
  (20, '21', 'Vacaciones reportadas ', 0, 1, 0, 1, 1, '0', '0', 1),
  (21, '22', 'Prima de vacaciones reportada ', 0, 1, 0, 18, 1, '0', '0', 1),
  (22, '23', 'Días de vacaciones', 0, 0, 1, 1, 1, '0', '0', 1),
  (23, '24', 'Aguinaldo', 0, 1, 0, 2, 1, '0', '0', 1),
  (24, '25', 'Reparto de utilidades', 0, 0, 0, 3, 1, '0', '0', 1),
  (25, '26', 'Indemnización', 0, 0, 0, 22, 1, '0', '0', 1),
  (26, '27', 'Separación Unica', 0, 0, 0, 20, 1, '0', '0', 1),
  (27, '29', 'Prima de antiguedad', 0, 1, 0, 19, 1, '', '', 1),
  (28, '31', 'Fondo ahorro empresa', 0, 0, 1, 5, 1, '0', '0', 1),
  (29, '32', 'Despensa', 0, 0, 1, 26, 1, '0', '0', 1),
  (30, '33', 'Deporte y cultura', 0, 0, 1, 35, 1, '0', '0', 1),
  (31, '35', 'Anticipo vacaciones Percepción', 0, 0, 0, 1, 1, '0', '0', 1),
  (32, '36', 'Destajo - sueldo', 0, 0, 0, 1, 1, '0', '0', 1),
  (33, '37', 'Comisión sueldo', 0, 0, 0, 25, 1, '0', '0', 1),
  (34, '131', 'Fondo de ahorro Empresa', 0, 0, 0, 5, 1, '0', '0', 1),
  (35, '5', 'Ret. Inv. Y Vida', 1, 1, 1, 0, 2, '0', '0', 1),
  (36, '6', 'Ret. Cesantia', 1, 1, 1, 0, 2, '0', '0', 1),
  (37, '11', 'Ret. Enf. y Mat. obrero', 1, 1, 1, 0, 2, '0', '0', 1),
  (38, '14', 'Seguro de vivienda Infonavit', 0, 1, 0, 9, 2, '0', '0', 1),
  (39, '15', 'Prestamo Infonavit (vsm)', 0, 0, 0, 9, 2, '0', '0', 1),
  (40, '16', 'Prestamo Infonavit (cf)', 0, 0, 0, 9, 2, '0', '0', 1),
  (41, '32', 'Subs al Empleo acreditado', 1, 1, 1, 0, 2, '0', '0', 1),
  (42, '33', 'I.S.R. a retener (cálc. anual)', 0, 0, 0, 4, 2, '0', '0', 1),
  (43, '44', 'I.S.R. antes de Subs al Empleo', 1, 1, 1, 0, 2, '0', '0', 1),
  (44, '43', 'I.S.R. Art142', 0, 1, 0, 2, 2, '0', '0', 1),
  (45, '44', 'I.S.R. (anual)', 0, 0, 0, 2, 2, '0', '0', 1),
  (46, '45', 'I.S.R. (mes)', 0, 0, 0, 2, 2, '0', '0', 1),
  (47, '49', 'I.S.R. (sp)', 1, 1, 0, 2, 2, '0', '0', 1),
  (48, '52', 'I.M.S.S.', 1, 1, 0, 1, 2, '0', '0', 1),
  (49, '53', 'I.E.', 0, 0, 0, 4, 2, '0', '0', 1),
  (50, '54', 'Cuota sindical', 0, 0, 0, 19, 2, '0', '0', 1),
  (51, '56', 'Caja de ahorro', 0, 0, 0, 4, 2, '0', '0', 1),
  (52, '57', 'Préstamo caja de ahorro', 0, 0, 0, 4, 2, '0', '0', 1),
  (53, '58', 'Intereses ptmo. ahorro', 0, 0, 0, 4, 2, '0', '0', 1),
  (54, '59', 'Préstamo Infonavit', 0, 0, 0, 9, 2, '', '', 1),
  (55, '60', 'Intereses ptmo. Infonavit', 0, 0, 0, 9, 2, '', '', 1),
  (56, '61', 'Prestamo FONACOT', 0, 0, 0, 11, 2, '0', '0', 1),
  (57, '62', 'Fonacot revolvente', 0, 0, 0, 11, 2, '0', '0', 1),
  (58, '63', 'Intereses ptmo. fonacot', 0, 0, 0, 11, 2, '0', '0', 1),
  (59, '64', 'Préstamo empresa', 0, 0, 0, 4, 2, '0', '0', 1),
  (60, '65', 'Intereses ptmo. empresa', 0, 0, 0, 4, 2, '0', '0', 1),
  (61, '66', 'Anticipo sueldo', 0, 0, 0, 12, 2, '0', '0', 1),
  (62, '67', 'Fondo de ahorro', 0, 0, 0, 4, 2, '0', '0', 1),
  (63, '69', 'Reintegración', 0, 0, 0, 4, 2, '0', '0', 1),
  (64, '70', 'Deduccion general', 0, 0, 0, 4, 2, '0', '0', 1),
  (65, '72', 'Préstamo fondo de ahorro', 0, 0, 0, 4, 2, '0', '0', 1),
  (66, '73', 'Intereses ptmo. fondo de ahorro', 0, 0, 0, 4, 2, '0', '0', 1),
  (67, '74', 'Anticipo vacaciones', 0, 0, 0, 12, 2, '0', '0', 1),
  (68, '87', 'Aportación voluntaria Infonavit', 0, 0, 0, 5, 2, '0', '0', 1),
  (69, '88', 'Aportación voluntaria SAR', 0, 0, 0, 3, 2, '0', '0', 1),
  (70, '91', 'Subsidio acreditable', 0, 0, 1, 0, 2, '0', '0', 1),
  (71, '92', 'Subsidio no acreditable', 0, 0, 1, 0, 2, '0', '0', 1),
  (72, '94', 'Sobregiro', 0, 0, 1, 0, 2, '0', '0', 1),
  (73, '99', 'Ajuste al Neto', 0, 0, 0, 4, 2, '0', '0', 1),
  (74, '101', 'I.S.R. finiquito', 0, 1, 0, 2, 2, '0', '0', 1),
  (75, '157', 'Ptmo. caja de ahorro2', 0, 0, 0, 4, 2, '0', '0', 1),
  (76, '158', 'Ptmo. caja de ahorro3', 0, 0, 0, 4, 2, '0', '0', 1),
  (77, '159', 'Ptmo. caja de ahorro4', 0, 0, 0, 4, 2, '0', '0', 1),
  (78, '164', 'Ptmo. empresa2', 0, 0, 0, 4, 2, '0', '0', 1),
  (79, '165', 'Ptmo. empresa3', 0, 0, 0, 4, 2, '0', '0', 1),
  (80, '166', 'Ptmo. empresa4', 0, 0, 0, 4, 2, '0', '0', 1),
  (81, '172', 'Ptmo. fondo de ahorro2', 0, 0, 0, 4, 2, '0', '0', 1),
  (82, '173', 'Ptmo. fondo de ahorro3', 0, 0, 0, 4, 2, '0', '0', 1),
  (83, '174', 'Ptmo. fondo de ahorro4', 0, 0, 0, 4, 2, '0', '0', 1),
  (84, '5', 'Invalidez y Vida', 1, 1, 1, 0, 3, '0', '0', 1),
  (85, '6', 'Cesantia y Vejez', 1, 1, 1, 0, 3, '0', '0', 1),
  (86, '7', 'Enf. y Mat. Patron', 1, 1, 1, 0, 3, '0', '0', 1),
  (87, '89', '2% Fondo retiro SAR (8)', 1, 1, 0, 0, 3, '0', '0', 1),
  (88, '90', '2% Impuesto estatal', 1, 1, 0, 0, 3, '0', '0', 1),
  (89, '93', 'Riesgo de trabajo (9)', 1, 1, 0, 0, 3, '0', '0', 1),
  (90, '95', '1% Educación empresa', 0, 0, 0, 0, 3, '0', '0', 1),
  (91, '96', 'I.M.S.S. empresa', 1, 1, 0, 0, 3, '0', '0', 1),
  (92, '97', 'Infonavit empresa', 1, 1, 0, 0, 3, '0', '0', 1),
  (93, '98', 'Guarderia I.M.S.S. (7)', 1, 1, 0, 0, 3, '0', '0', 1),
  (94, '34', 'Subsidio al Empleo (anual)', 0, 0, 0, 103, 2, '0', '0', 1),
  (95, '35', 'Subsidio al Empleo (mes)', 0, 0, 0, 103, 2, '0', '0', 1),
  (96, '39', 'Subsidio al Empleo (sp)', 1, 1, 0, 103, 2, '', '', 1),
  (97, '50', 'Reintegrado de ISR  pagado en exceso', 0, 0, 0, 101, 2, '0', '0', 1),
  (98, '55', 'I.S.R. a compensar', 0, 0, 0, 102, 2, '0', '0', 1),
  (99, '0', 'Neto', 1, 1, 0, 0, 1, '', '', 1),
  (100, '123', 'Reintegrado de ISR  pagado en excesoReintegrado de ISR  pagado en excesoReintegrado de ISR  pagado e', 1, 1, 1, 5, 1, '0', '0', 1),
  (101, 'uno borram', 'borrame', 0, 0, 0, 0, 1, '0', '0', 1);




INSERT INTO `nomi_deducciones` (`idAgrupador`, `clave`, `descripcion`, `account_id`)
VALUES
    (22,'022','Impuestos Locales',-1),
    (23,'023','Aportaciones voluntarias',-1),
    (24,'024','Ajuste en Gratificación Anual (Aguinaldo) Exento',-1),
    (25,'025','Ajuste en Gratificación Anual (Aguinaldo) Gravado',-1),
    (26,'026','Ajuste en Participación de los Trabajadores en las Utilidades PTU Exento',-1),
    (27,'027','Ajuste en Participación de los Trabajadores en las Utilidades PTU Gravado',-1),
    (28,'028','Ajuste en Reembolso de Gastos Médicos Dentales y Hospitalarios Exento',-1),
    (29,'029','Ajuste en Fondo de ahorro Exento',-1),
    (30,'030','Ajuste en Caja de ahorro Exento',-1),
    (31,'031','Ajuste en Contribuciones a Cargo del Trabajador Pagadas por el Patrón Exento',-1),
    (32,'032','Ajuste en Premios por puntualidad Gravado',-1),
    (33,'033','Ajuste en Prima de Seguro de vida Exento',-1),
    (34,'034','Ajuste en Seguro de Gastos Médicos Mayores Exento',-1),
    (35,'035','Ajuste en Cuotas Sindicales Pagadas por el Patrón Exento',-1),
    (36,'036','Ajuste en Subsidios por incapacidad Exento',-1),
    (37,'037','Ajuste en Becas para trabajadores y/o hijos Exento',-1),
    (38,'038','Ajuste en Horas extra Exento',-1),
    (39,'039','Ajuste en Horas extra Gravado',-1),
    (40,'040','Ajuste en Prima dominical Exento',-1),
    (41,'041','Ajuste en Prima dominical Gravado',-1),
    (42,'042','Ajuste en Prima vacacional Exento',-1),
    (43,'043','Ajuste en Prima vacacional Gravado',-1),
    (44,'044','Ajuste en Prima por antigüedad Exento',-1),
    (45,'045','Ajuste en Prima por antigüedad Gravado',-1),
    (46,'046','Ajuste en Pagos por separación Exento',-1),
    (47,'047','Ajuste en Pagos por separación Gravado',-1),
    (48,'048','Ajuste en Seguro de retiro Exento',-1),
    (49,'049','Ajuste en Indemnizaciones Exento',-1),
    (50,'050','Ajuste en Indemnizaciones Gravado',-1),
    (51,'051','Ajuste en Reembolso por funeral Exento',-1),
    (52,'052','Ajuste en Cuotas de seguridad social pagadas por el patrón Exento',-1),
    (53,'053','Ajuste en Comisiones Gravado',-1),
    (54,'054','Ajuste en Vales de despensa Exento',-1),
    (55,'055','Ajuste en Vales de restaurante Exento',-1),
    (56,'056','Ajuste en Vales de gasolina Exento',-1),
    (57,'057','Ajuste en Vales de ropa Exento',-1),
    (58,'058','Ajuste en Ayuda para renta Exento',-1),
    (59,'059','Ajuste en Ayuda para artículos escolares Exento',-1),
    (60,'060','Ajuste en Ayuda para anteojos Exento',-1),
    (61,'061','Ajuste en Ayuda para transporte Exento',-1),
    (62,'062','Ajuste en Ayuda para gastos de funeral Exento',-1),
    (63,'063','Ajuste en Otros ingresos por salarios Exento',-1),
    (64,'064','Ajuste en Otros ingresos por salarios Gravado',-1),
    (65,'065','Ajuste en Jubilaciones, pensiones o haberes de retiro Exento',-1),
    (66,'066','Ajuste en Jubilaciones, pensiones o haberes de retiro Gravado',-1),
    (67,'067','Ajuste en Pagos por separación Acumulable',-1),
    (68,'068','Ajuste en Pagos por separación No acumulable',-1),
    (69,'069','Ajuste en Jubilaciones, pensiones o haberes de retiro Acumulable',-1),
    (70,'070','Ajuste en Jubilaciones, pensiones o haberes de retiro No acumulable',-1),
    (71,'071','Ajuste en Subsidio para el empleo (efectivamente entregado al trabajador)',-1),
    (72,'072','Ajuste en Ingresos en acciones o títulos valor que representan bienes Exento',-1),
    (73,'073','Ajuste en Ingresos en acciones o títulos valor que representan bienes Gravado',-1),
    (74,'074','Ajuste en Alimentación Exento',-1),
    (75,'075','Ajuste en Alimentación Gravado',-1),
    (76,'076','Ajuste en Habitación Exento',-1),
    (77,'077','Ajuste en Habitación Gravado',-1),
    (78,'078','Ajuste en Premios por asistencia',-1),
    (79,'079','Ajuste en Pagos distintos a los listados y que no deben considerarse como ingreso por sueldos, salarios o ingresos asimilados',-1),
    (80,'080','Ajuste en Viáticos no comprobados',-1),
    (81,'081','Ajuste en Viáticos anticipados',-1),
    (82,'082','Ajuste en Fondo de ahorro Gravado',-1),
    (83,'083','Ajuste en Caja de ahorro Gravado',-1),
    (84,'084','Ajuste en Prima de Seguro de vida Gravado',-1),
    (85,'085','Ajuste en Seguro de Gastos Médicos Mayores Gravado',-1),
    (86,'086','Ajuste en Subsidios por incapacidad Gravado',-1),
    (87,'087','Ajuste en Becas para trabajadores y/o hijos Gravado',-1),
    (88,'088','Ajuste en Seguro de retiro Gravado',-1),
    (89,'089','Ajuste en Vales de despensa Gravado',-1),
    (90,'090','Ajuste en Vales de restaurante Gravado',-1),
    (91,'091','Ajuste en Vales de gasolina Gravado',-1),
    (92,'092','Ajuste en Vales de ropa Gravado',-1),
    (93,'093','Ajuste en Ayuda para renta Gravado',-1),
    (94,'094','Ajuste en Ayuda para artículos escolares Gravado',-1),
    (95,'095','Ajuste en Ayuda para anteojos Gravado',-1),
    (96,'096','Ajuste en Ayuda para transporte Gravado',-1),
    (97,'097','Ajuste en Ayuda para gastos de funeral Gravado',-1),
    (98,'098','Ajuste a ingresos asimilados a salarios gravados',-1),
    (99,'099','Ajuste a ingresos por sueldos y salarios gravados',-1),
    (100,'100','Ajuste en Viáticos exentos',-1);



CREATE TABLE `nomi_departamento` (
  `idDep` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) CHARACTER SET latin1 NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '-1',
  PRIMARY KEY (`idDep`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE `nomi_empleados` 
 ADD `idtipocontrato` int(11) DEFAULT NULL COMMENT 'nomi_tipocontrato' AFTER activo,
 ADD `idtipop` int(11) DEFAULT NULL COMMENT 'nomi_tiposperiodos' AFTER idtipocontrato,
 ADD `idbase` int(11) DEFAULT NULL COMMENT 'nomi_base_cotizacion' AFTER idtipop,
 ADD `sbcfija` double(100,2) DEFAULT NULL AFTER idbase,
 ADD `sbcvariable` double(100,2) DEFAULT NULL AFTER sbcfija,
 ADD `sbctopado` double(100,2) DEFAULT NULL AFTER sbcvariable,
 ADD `idDep` int(11) DEFAULT NULL COMMENT 'nomi_departamento' AFTER sbctopado,
 ADD `idPuesto` int(11) DEFAULT NULL COMMENT 'nomi_puesto' AFTER idDep,
 ADD `idtipoempleado` int(11) DEFAULT NULL COMMENT 'nomi_tipo_empleado' AFTER idPuesto,
 ADD `idbasepago` int(11) DEFAULT NULL COMMENT 'nomi_base_pago' AFTER idtipoempleado,
 ADD `idturno` int(11) DEFAULT NULL COMMENT 'nomi_turno' AFTER idbasepago,
 ADD `idregimencontrato` int(11) DEFAULT NULL COMMENT 'nomi_regimencontratacion' AFTER idturno,
 ADD `fonacot` int(11) DEFAULT NULL AFTER idregimencontrato,
 ADD `afore` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL AFTER fonacot,
 ADD `idregistrop` int(11) DEFAULT NULL COMMENT 'nomi_registropatronal' AFTER afore,
 ADD `umf` int(11) DEFAULT NULL AFTER idregistrop,
 ADD `avisosimss` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '1-alta, 2-baja, 3-modif salario' AFTER umf,
 ADD `horasext1` double(100,2) DEFAULT NULL AFTER avisosimss,
 ADD `horasext2` double(100,2) DEFAULT NULL AFTER horasext1,
 ADD `horasext3` double(100,2) DEFAULT NULL AFTER horasext2,
 ADD `diastrabajados` double(100,2) DEFAULT NULL AFTER horasext3,
 ADD `diaspagados` double(100,2) DEFAULT NULL AFTER diastrabajados,
 ADD `diascotizados` double(100,2) DEFAULT NULL AFTER diaspagados,
 ADD `ausencias` double(100,2) DEFAULT NULL AFTER diascotizados,
 ADD `incapacidades` double(100,2) DEFAULT NULL AFTER ausencias,
 ADD `vacaciones` double(100,2) DEFAULT NULL AFTER incapacidades,
 ADD `septimosprop` double(100,2) DEFAULT NULL AFTER vacaciones,
 ADD `salariovariable` double(100,2) DEFAULT NULL AFTER septimosprop,
 ADD `fechavariable` date DEFAULT NULL AFTER salariovariable,
 ADD `fechadiario` date DEFAULT NULL AFTER fechavariable,
 ADD `salariopromedio` double(100,2) DEFAULT NULL AFTER fechadiario,
 ADD `fechapromedio` date DEFAULT NULL AFTER salariopromedio,
 ADD `fechaintegrado` date DEFAULT NULL AFTER fechapromedio,
 ADD `salarioliquidacion` double(100,2) DEFAULT NULL AFTER fechaintegrado,
 ADD `salarioajusteneto` double(100,2) DEFAULT NULL AFTER salarioliquidacion;
 

CREATE TABLE `nomi_factorSDI` (
  `antiguedad` int(11) NOT NULL AUTO_INCREMENT,
  `factor_conf` double DEFAULT NULL,
  `factor_sind` double DEFAULT NULL,
  PRIMARY KEY (`antiguedad`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `nomi_factorSDI` (`antiguedad`, `factor_conf`, `factor_sind`)
VALUES
    (1,0,0),
    (2,0,0),
    (3,0,0),
    (4,0,0),
    (5,0,0),
    (6,0,0),
    (7,0,0),
    (8,0,0),
    (9,0,0),
    (10,0,0),
    (11,0,0),
    (12,0,0),
    (13,0,0),
    (14,0,0),
    (15,0,0),
    (16,0,0),
    (17,0,0),
    (18,0,0),
    (19,0,0),
    (20,0,0);

CREATE TABLE `nomi_finiquito` (
  `idfiniquito` int(11) NOT NULL AUTO_INCREMENT,
  `numero` double(100,2) DEFAULT NULL,
  `casusmo` double(100,2) DEFAULT NULL,
  `casisr86` double(100,2) DEFAULT NULL,
  `caldirecperc` double(100,2) DEFAULT NULL,
  `indem90` double(100,2) DEFAULT NULL,
  PRIMARY KEY (`idfiniquito`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE TABLE `nomi_fraccionriesgocatalogo` (
  `idfraccion` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(500) DEFAULT NULL,
  `fraccion` int(11) DEFAULT NULL,
  `idclaveriesgopuesto` int(11) DEFAULT NULL,
  PRIMARY KEY (`idfraccion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


INSERT INTO `nomi_fraccionriesgocatalogo` (`idfraccion`, `descripcion`, `fraccion`, `idclaveriesgopuesto`)
VALUES
    (1,'Beneficio y/o fabricación de productos de tabaco.',220,3),
    (2,'Fabricación, preparación, hilado, tejido y acabado de textiles de fibras blandas.',231,4),
    (3,'Trabajos de blanqueo, teñido, estampado, impermeabilizado y acabado de hilados y tejidos de fibras blandas.',232,4),
    (4,'Fabricación de tejidos y artículos de punto.',233,3),
    (5,'Fabricación, preparación, hilado, tejido y acabado de textiles de fibras duras.',234,5),
    (6,'Fabricación de tejidos de fibras blandas con telares automáticos sin lanzadera.',236,4),
    (7,'Fabricación de hilados con máquinas de turbina.',237,4),
    (8,'Confección de prendas de vestir a la medida.',241,1),
    (9,'Confección de prendas de vestir.',242,2),
    (10,'Otros artículos confeccionados con textiles y materiales diversos.',243,2),
    (11,'Fabricación de calzado, con maquinaria y/o equipo motorizado.',251,3),
    (12,'Fabricación de calzado, sin maquinaria ni equipo motorizado.',252,2),
    (13,'Curtido y acabado de cuero y piel.',253,5),
    (14,'Manufactura de artículos de cuero, piel y sucedáneos, en forma artesanal.',254,2),
    (15,'Fabricación de artículos de cuero, piel y sucedáneos.',255,3),
    (16,'Curtido y acabado de cuero y piel, con uso exclusivo de maquinaria y/o equipo motorizado.',256,5),
    (17,'Fabricación de productos de aserradero.',261,5),
    (18,'Fabricación de artículos y accesorios de madera.',262,5),
    (19,'Manufactura de artículos de corcho, palma, vara, carrizo y mimbre.',263,2),
    (20,'Fabricación de artículos de corcho, palma, vara, carrizo y mimbre.',264,3),
    (21,'Fabricación y/o reparación de muebles de madera y sus partes.',271,5),
    (22,'Fabricación de papel y/o cartón y sus derivados.',281,4),
    (23,'Fabricación de artículos a base de papel y/o cartón.',282,4),
    (24,'Industrias editorial, de impresión, encuadernación y actividades conexas.',291,3),
    (25,'Fabricación de sustancias químicas e industriales; excepto abonos.',301,3),
    (26,'Fabricación de abonos, fertilizantes y plaguicidas.',302,4),
    (27,'Fabricación de resinas sintéticas y plastificantes.',303,3),
    (28,'Industria de las pinturas.',304,3),
    (29,'Industrias químico-farmacéuticas y de medicamentos.',305,2),
    (30,'Fabricación de productos químicos para limpieza y aromatizantes ambientales.',307,3),
    (31,'Fabricación de perfumes y cosméticos.',308,2),
    (32,'Fabricación de aceites y grasas vegetales y animales no comestibles, para usos industriales.',309,4),
    (33,'Fabricación de velas, veladoras y similares.',3010,3),
    (34,'Fabricación de cerillos.',3012,4),
    (35,'Fabricación de explosivos y fuegos artificiales.',3013,4),
    (36,'Otros productos de las industrias químicas conexas.',3014,3),
    (37,'Fabricación de fibras artificiales y sintéticas.',3016,2),
    (38,'Refinación del petróleo crudo y petroquímica básica.',311,4),
    (39,'Fabricación de lubricantes y aditivos.',312,3),
    (40,'Fabricación de productos a base de asfalto y sus mezclas.',313,4),
    (41,'Fabricación de productos de hule.',321,5),
    (42,'Fabricación de productos de plástico.',322,4),
    (43,'Fabricación de productos de látex.',323,5),
    (44,'Manufactura de artículos de alfarería y cerámica.',331,3),
    (45,'Fabricación de muebles sanitarios, loza, porcelana y artículos refractarios.',332,5),
    (46,'Fabricación de vidrio y/o productos de vidrio.',333,4),
    (47,'Fabricación de productos de arcilla para la construcción.',335,5),
    (48,'Fabricación de cal y yeso.',336,5),
    (49,'Fabricación de productos a base de asbesto.',337,5),
    (50,'Fabricación de productos abrasivos.',338,3),
    (51,'Fabricación de granito artificial, productos de mármol y otras piedras.',339,5),
    (52,'Fabricación de productos y partes preconstruidas de concreto.',3310,5),
    (53,'Fabricación de azulejos, con procesos continuos automatizados.',3312,3),
    (54,'Fabricación de vidrio y/o productos de vidrio, con procesos continuos automatizados.',3313,2),
    (55,'Fabricación de productos de asbesto-cemento.',3315,5),
    (56,'Fabricación de cemento.',3316,5),
    (57,'Fabricación de concreto premezclado.',3317,4),
    (58,'Industrias básicas del hierro, acero y metales no ferrosos.',341,5),
    (59,'Industrias básicas del hierro, acero y metales no ferrosos, con procesos automatizados.',342,5),
    (60,'Fabricación de utensilios agrícolas, herramientas y artículos de ferretería y cerrajería.',351,5),
    (61,'Fabricación y/o reparación de puertas, ventanas, cortinas metálicas y otros trabajos de herrería.',352,4),
    (62,'Fabricación, ensamble y/o reparación de muebles metálicos y sus partes.',353,4),
    (63,'Fabricación y/o reparación de estructuras metálicas, tanques, calderas y similares.',354,5),
    (64,'Fabricación de envases metálicos, corcholatas y tapas.',355,5),
    (65,'Fabricación de alambres y otros productos de alambre.',356,5),
    (66,'Trabajos de tratamientos térmicos y galvanoplastia.',357,4),
    (67,'Fabricación de agujas, alfileres, cierres, botones y navajas para rasurar.',358,3),
    (68,'Fabricación de baterías de cocina, cucharas, cuchillos y tenedores.',359,5),
    (69,'Fabricación de otros productos metálicos maquinados.',3510,5),
    (70,'Tratamientos térmicos y galvanoplastia, con procesos continuos automatizados.',3511,3),
    (71,'Fabricación y/o ensamble de maquinaria, equipos e implementos para labores agropecuarias.',361,4),
    (72,'Fabricación y/o ensamble de maquinaria, equipo e implementos para las industrias de alimentos, bebidas, tabacalera, textil, calzado, madera, cuero, impresión, hule, plástico, productos de minerales no metálicos (excepto cemento), metal mecánica y maquinaria y equipo de uso común a varias industrias.',362,4),
    (73,'Fabricación y/o ensamble de maquinaria, equipo e implementos para las industrias de la construcción, extractivas, papel, cemento, petroquímica básica, química; metálicas básicas del hierro, del acero y de metales no ferrosos.',363,5),
    (74,'Fabricación y/o ensamble de máquinas de coser, oficina, cómputo y sus partes.',364,2),
    (75,'Reparación y ensamble de máquinas de coser y de oficina.',365,1),
    (76,'Fabricación de partes y piezas sueltas, para maquinaria y equipo en general.',366,5),
    (77,'Reparación y/o mantenimiento de maquinaria y equipo en general.',367,3),
    (78,'Fabricación y/o ensamble de maquinaria y equipo para generación y transformación de energía eléctrica.',371,4),
    (79,'Fabricación y/o ensamble de equipo y aparatos de radio, televisión y comunicaciones.',372,2),
    (80,'Fabricación y/o grabado de discos y cintas magnéticas para sonidos, imágenes y datos.',373,3),
    (81,'Fabricación y/o ensamble de aparatos eléctricos y sus partes para uso doméstico.',374,3),
    (82,'Fabricación, reconstrucción y/o ensamble de acumuladores eléctricos.',375,4),
    (83,'Fabricación y/o ensamble de pilas (secas), componentes eléctricos y electrónicos diversos.',376,3),
    (84,'Fabricación y/o ensamble de lámparas (focos) y tubos al vacío para alumbrado eléctrico.',377,3),
    (85,'Fabricación de conductores eléctricos.',378,3),
    (86,'Fabricación y/o ensamble de aparatos, accesorios eléctricos o electrónicos, para empalme, corte, protección y conexión.',379,2),
    (87,'Fabricación de luminarias y anuncios luminosos.',3710,5),
    (88,'Fabricación en serie o con procesos continuos de acumuladores eléctricos.',3711,3),
    (89,'Fabricación y/o ensamble de refrigeradores, estufas, lavadoras, secadoras y otros aparatos de línea blanca.',3712,4),
    (90,'Fabricación y/o ensamble de aeronaves.',381,3),
    (91,'Fabricación y/o ensamble de carrocerías para vehículos de transporte.',382,4),
    (92,'Fabricación y/o ensamble de partes y accesorios para automóviles, autobuses, camiones, motocicletas y bicicletas.',383,4),
    (93,'Fabricación y/o ensamble de partes para el sistema eléctrico de vehículos automóviles.',384,2),
    (94,'Fabricación y/o ensamble de bicicletas y otros vehículos de pedal.',385,4),
    (95,'Fabricación, ensamble y/o reparación de carros de ferrocarril, equipo ferroviario y sus partes.',386,5),
    (96,'Fabricación, ensamble y/o reparación de embarcaciones.',387,5),
    (97,'Fabricación y/o ensamble de automóviles, autobuses, camiones y motocicletas.',388,3),
    (98,'Fabricación y/o ensamble de motores para automóviles, autobuses y camiones.',389,3),
    (99,'Fabricación de conjuntos mecánicos y sus partes para automóviles, autobuses, camiones y motocicletas.',3810,4),
    (100,'Fabricación, ensamble y/o reparación de equipos, aparatos científicos y profesionales e instrumentos de medida y control.',390,3),
    (101,'Fabricación, ensamble y/o reparación de aparatos, instrumentos y accesorios de óptica y fotografía.',391,2),
    (102,'Fabricación, montaje y/o ensamble de relojes, joyas, artículos de orfebrería y fantasía.',392,2),
    (103,'Fabricación y/o ensamble de instrumentos musicales, paraguas, juguetes y artículos deportivos, con maquinaria y/o equipo motorizado.',394,3),
    (104,'Fabricación y/o ensamble de instrumentos musicales, paraguas, juguetes y artículos deportivos, sin maquinaria ni equipo motorizado.',395,2),
    (105,'Fabricación de lápices, gomas, plumas y bolígrafos.',396,3),
    (106,'Talleres de mecánica dental.',397,2),
    (107,'Fabricación y/o ensamble de armas de fuego portátiles, cartuchos, municiones y accesorios.',398,3),
    (108,'Fabricación, ensamble y/o reparación de otros artículos manufacturados no clasificados anteriormente, sin maquinaria ni equipo motorizado.',399,3),
    (109,'Fabricación, ensamble y/o reparación de otros artículos manufacturados no clasificados anteriormente, con maquinaria y/o equipo motorizado.',3910,4),
    (110,'Construcción de edificaciones; excepto obra pública.',411,5),
    (111,'Construcciones de obras de infraestructura y edificaciones en obra pública.',412,5),
    (112,'Instalaciones sanitarias, eléctricas, de gas y de aire acondicionado.',421,4),
    (113,'Instalación y reparación de ascensores, escaleras electromecánicas y otros equipos para transportación',422,4),
    (114,'Instalación de ventanería, herrería, cancelería, vidrios y cristales.',423,5),
    (115,'Otros servicios de instalación vinculados al acabado o remodelación de obras de construcción.',424,5),
    (116,'Generación, transmisión y distribución de energía eléctrica.',500,4),
    (117,'Captación y suministro de agua potable y tratada.',510,3),
    (118,'Expendios de ventas al menudeo de alimentos, bebidas y/o productos del tabaco.',611,2),
    (119,'Compraventa de alimentos, bebidas y/o productos del tabaco, sin transporte.',612,3),
    (120,'Compraventa de alimentos, bebidas y/o productos del tabaco, con transporte.',613,3),
    (121,'Compraventa e introducción de animales vivos.',614,3),
    (122,'Expendios de ventas al menudeo de prendas y accesorios de vestir y artículos para su confección.',621,1),
    (123,'Compraventa de prendas y accesorios de vestir y artículos para su confección, sin transporte.',622,2),
    (124,'Compraventa de prendas y accesorios de vestir y artículos para su confección, con transporte.',623,1),
    (125,'Expendios de ventas al menudeo de artículos de uso personal.',624,1),
    (126,'Compraventa de artículos de uso personal, sin transporte.',625,2),
    (127,'Compraventa de artículos de uso personal, con transporte.',626,1),
    (128,'Expendios de ventas al menudeo de medicamentos, productos farmacéuticos, químico-farmacéuticos y de perfumería.',627,1),
    (129,'Compraventa de medicamentos, productos farmacéuticos, químico-farmacéuticos y de perfumería, sin transporte.',628,1),
    (130,'Compraventa de medicamentos, productos farmacéuticos, químico-farmacéuticos y de perfumería, con transporte.',629,2),
    (131,'Expendios de ventas al menudeo de papelería, útiles escolares y de oficina; libros, periódicos y revistas.',6210,1),
    (132,'Compraventa de papelería, útiles escolares y de oficina; libros, periódicos y revistas, sin transporte.',6211,3),
    (133,'Compraventa de papelería, útiles escolares y de oficina; libros, periódicos y revistas, con transporte.',6212,2),
    (134,'Expendios de ventas al menudeo de máquinas, muebles, aparatos e instrumentos para el hogar, sus refacciones y accesorios.',631,1),
    (135,'Compraventa de máquinas, muebles, aparatos e instrumentos para el hogar, sus refacciones y accesorios, sin transporte.',632,1),
    (136,'Compraventa de máquinas, muebles, aparatos e instrumentos para el hogar, sus refacciones y accesorios, con transporte y/o servicios de instalación.',633,3),
    (137,'Expendios de ventas al menudeo de otros artículos para el hogar.',634,1),
    (138,'Compraventa de otros artículos para el hogar, sin transporte.',635,1),
    (139,'Compraventa de otros artículos para el hogar, con transporte y/o servicios de instalación.',636,2),
    (140,'Supermercados, tiendas de autoservicio y de departamentos especializados por línea de mercancías.',641,2),
    (141,'Compraventa, envasado y/o distribución de gases para uso doméstico, industrial y medicinal.',651,5),
    (142,'Compraventa de lubricantes y aditivos, sin transporte.',652,2),
    (143,'Estaciones de venta de gasolina, diesel y compraventa de lubricantes y aditivos, con transporte.',653,3),
    (144,'Compraventa de leña, carbón vegetal y mineral.',654,3),
    (145,'Expendios de ventas al menudeo de materias primas agropecuarias.',661,2),
    (146,'Compraventa de materias primas agropecuarias, sin transporte.',662,3),
    (147,'Compraventa de materias primas agropecuarias, con transporte.',663,3),
    (148,'Compraventa de materiales para construcción, tales como madera, aceros y productos de ferretería, sin transporte, ni preparación de mercancías.',664,2),
    (149,'Compraventa de materiales para construcción tales como: madera, aceros y productos de ferretería, con transporte y/o preparación de mercancías.',665,4),
    (150,'Compraventa de material eléctrico, pinturas y productos de tlapalería, sin transporte.',666,2),
    (151,'Compraventa de material eléctrico, pinturas y productos de tlapalería, con transporte.',667,2),
    (152,'Compraventa de vidrio plano, cristales, espejos y lunas, sin transporte ni servicios de instalación.',668,5),
    (153,'Compraventa de vidrio plano, cristales, espejos y lunas, con transporte y/o servicios de instalación.',669,5),
    (154,'Compraventa de fertilizantes, plaguicidas y productos químicos (no explosivos) en envases cerrados, sin transporte.',6610,2),
    (155,'Compraventa de fertilizantes, plaguicidas y productos químicos (no explosivos) en envases cerrados o a granel, con transporte.',6611,3),
    (156,'Compraventa de pieles, cueros curtidos y otros artículos de peletería, sin transporte.',6612,2),
    (157,'Compraventa de pieles, cueros curtidos y otros artículos de peletería, con transporte',6613,1),
    (158,'Compraventa de papel y cartón nuevos, sin transporte.',6614,2),
    (159,'Compraventa de papel y cartón nuevos, con transporte.',6615,3),
    (160,'Compraventa de chatarra, fierro viejo, partes o mecanismos usados y desperdicios en general.',6616,5),
    (161,'Compraventa de explosivos y productos de pirotecnia.',6617,3),
    (162,'Expendio de ventas al menudeo de refacciones y accesorios para maquinaria y/o equipo para la producción de bienes.',671,2),
    (163,'Compraventa de maquinaria, equipo y sus refacciones y/o accesorios para la producción de bienes, sin transporte.',672,2),
    (164,'Compraventa de maquinaria, equipo y sus refacciones y/o accesorios para la producción de bienes, con transporte y/o servicios de reparación o mantenimiento.',673,3),
    (165,'Compraventa de maquinaria, equipo y sus refacciones y/o accesorios para la producción de bienes, con servicios de instalación.',674,4),
    (166,'Expendios de ventas al menudeo de equipo, mobiliario, sus partes y/o accesorios para la prestación de servicios y el comercio.',675,1),
    (167,'Compraventa de equipo, mobiliario, sus partes y/o accesorios para la prestación de servicios y el comercio, sin transporte.',676,1),
    (168,'Compraventa de equipo, mobiliario, sus partes y/o accesorios para la prestación de servicios y el comercio, con transporte y/o servicios de instalación, reparación y mantenimiento.',677,2),
    (169,'Expendios de ventas al menudeo de aparatos e instrumentos de medición, precisión, cirugía, laboratorio y otros usos científicos.',678,1),
    (170,'Compraventa de aparatos e instrumentos de medición, precisión, cirugía, laboratorio y otros usos científicos, sin transporte.',679,1),
    (171,'Compraventa de aparatos e instrumentos de medición, precisión, cirugía, laboratorio y otros usos científicos, con transporte y/o servicios de instalación, reparación o mantenimiento.',6710,2),
    (172,'Compraventa de equipo de cómputo o de procesamiento electrónico de datos y sus periféricos, con servicios de instalación, reparación y/o mantenimiento.',6711,2),
    (173,'Expendios de ventas al menudeo de refacciones, accesorios y/o partes para equipo de transporte.',681,2),
    (174,'Compraventa de equipo de transporte, sus refacciones, accesorios y/o partes, sin transporte.',682,2),
    (175,'Compraventa de equipo de transporte, sus refacciones, accesorios y/o partes, con transporte y/o servicios de instalación, reparación o mantenimiento.',683,3),
    (176,'Compraventa de bienes inmuebles.',691,1),
    (177,'Expendios de ventas al menudeo de artículos diversos no clasificados.',692,1),
    (178,'Compraventa de artículos diversos no clasificados, sin transporte.',693,2),
    (179,'Compraventa de artículos diversos no clasificados, con transporte y/o servicios de instalación, reparación o mantenimiento.',694,2),
    (180,'Transporte de pasajeros.',711,4),
    (181,'Transporte de carga.',712,5),
    (182,'Transporte ferroviario y eléctrico.',713,5),
    (183,'Transporte marítimo y de navegación interior y servicios diversos a bordo y/o en plataformas marinas.',721,4),
    (184,'Servicios directamente vinculados con el transporte por agua y/o servicios de supervisión y mantenimiento en plataformas marinas.',722,5),
    (185,'Transporte aéreo.',730,2),
    (186,'Administración de vías de comunicación, terminales y servicios auxiliares.',740,2),
    (187,'Servicios de almacenamiento y/o refrigeración.',751,4),
    (188,'Servicios sin transporte de agencias de gestión aduanal, de equipajes, viajes y turísticas.',752,1),
    (189,'Servicios de grúa y emergencia para vehículos.',753,4),
    (190,'Servicios de alquiler de aeronaves, carros de ferrocarril y transportes acuáticos.',754,3),
    (191,'Servicios con transporte de agencias de gestión aduanal, de mensajería y paquetería, de equipajes, viajes, turísticas y otras actividades relacionadas con los transportes en general.',755,4),
    (192,'Comunicaciones.',760,2),
    (193,'Instituciones de crédito, seguros y fianzas.',810,1),
    (194,'Servicios colaterales a las instituciones financieras y de seguros.',820,1),
    (195,'Servicios relacionados con inmuebles.',830,1),
    (196,'Servicios profesionales y técnicos.',841,1),
    (197,'Servicios de instalación de maquinaria y equipo en general.',843,5),
    (198,'Servicios de protección y custodia.',844,3),
    (199,'Servicios de laboratorio para la industria en general.',845,2),
    (200,'Servicios de alquiler de maquinaria y equipo agrícola.',851,3),
    (201,'Servicios de alquiler de maquinaria y equipo para la construcción con operadores.',852,5),
    (202,'Servicios de alquiler de maquinaria y equipo para la construcción sin operadores.',853,3),
    (203,'Servicios de alquiler de equipo y mobiliario a empresas.',854,2),
    (204,'Servicios de alquiler para el público en general.',855,1),
    (205,'Servicios de alquiler o renta de automóviles, bicicletas y motocicletas.',856,2),
    (206,'Servicios de alojamiento temporal.',860,2),
    (207,'Preparación y servicio de alimentos.',871,2),
    (208,'Preparación y servicio de bebidas alcohólicas.',872,3),
    (209,'Servicios recreativos.',881,2),
    (210,'Servicios de esparcimiento.',882,1),
    (211,'Hipódromos, galgódromos, lienzos charros, palenques y promoción y presentación de espectáculos taurinos.',883,3),
    (212,'Servicios de centros nocturnos, salones de baile y casinos.',884,2),
    (213,'Promoción y montaje de exposiciones de pintura y escultura.',885,1),
    (214,'Circos y juegos electromecánicos.',886,3),
    (215,'Servicios de reparación, lavado, engrasado, verificación de emisión de contaminantes y estacionamiento de vehículos con servicios mecánicos y/o de hojalatería.',891,3),
    (216,'Servicios de reparación de artículos de uso doméstico y personal, sin maquinaria ni equipo motorizado.',892,2),
    (217,'Servicios de reparación de artículos de uso doméstico y personal, con maquinaria y/o equipo motorizado.',893,4),
    (218,'Servicios para el aseo personal y sanitarios.',894,2),
    (219,'Servicios de peluquería y salones de belleza.',895,1),
    (220,'Servicios de aseo y limpieza, sin maquinaria ni equipo motorizado.',896,2),
    (221,'Servicios de aseo y limpieza, con maquinaria y/o equipo motorizado.',897,3),
    (222,'Servicios de limpieza de ventanas y fachadas.',894,4),
    (223,'Servicios de fumigación, desinfección y control de plagas.',899,3),
    (224,'Aerotecnia agrícola.',8910,5),
    (225,'Servicios de revelado fotográfico.',8911,1),
    (226,'Inhumaciones y servicios conexos.',8912,2),
    (227,'Servicios domésticos.',8913,1),
    (228,'Servicios de estacionamiento y/o pensión para vehículos.',8914,3),
    (229,'Servicios de enseñanza académica, capacitación, investigación científica y difusión cultural.',911,1),
    (230,'Servicios médicos.',921,1),
    (231,'Servicios médicos, paramédicos y auxiliares.',922,2),
    (232,'Servicios de asistencia social.',923,1),
    (233,'Servicios veterinarios y auxiliares.',924,1),
    (234,'Asociaciones y organizaciones comerciales, profesionales, cívicas, laborales y políticas.',931,1),
    (235,'Organizaciones religiosas.',933,1),
    (236,'Servicios generales de la administración pública.',941,2),
    (237,'Seguridad pública.',942,3),
    (238,'Seguridad social.',943,2),
    (239,'Servicios de organizaciones internacionales y otros organismos extraterritoriales.',990,1);



CREATE TABLE `nomi_imssriesgot` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fecha` date DEFAULT NULL,
  `factor` double DEFAULT NULL,
  `idregistrop` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE TABLE `nomi_incapacidad` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `clave` varchar(11) NOT NULL DEFAULT '' COMMENT 'tabla definida por el sat',
  `descripcion` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

INSERT INTO `nomi_incapacidad` (`id`, `clave`, `descripcion`)
VALUES
  (1, '01', 'Riesgo de Trabajo'),
  (2, '02', 'Enfermedad en General'),
  (3, '03', 'Maternidad');


CREATE TABLE `nomi_jornada` (
  `idjornada` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(50) DEFAULT NULL,
  `clave` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`idjornada`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


INSERT INTO `nomi_jornada` (`idjornada`, `descripcion`, `clave`)
VALUES
    (0,'No aplica',''),
    (1,'Diurna','01'),
    (2,'Nocturna','02'),
    (3,'Mixta','03'),
    (4,'Por Hora','04'),
    (5,'Reducida','05'),
    (6,'Continuada','06'),
    (7,'Partida','07'),
    (8,'Por turnos','08'),
    (9,'Por Jornada','99');

CREATE TABLE `nomi_origenrecurso` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(30) DEFAULT NULL,
  `clave` varchar(2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `nomi_origenrecurso` (`id`, `descripcion`, `clave`)
VALUES
    (1,'Ingresos propios','IP'),
    (2,'Ingreso federales','IF'),
    (3,'Ingresos mixtos','IM');

CREATE TABLE `nomi_otros_pagos` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(300) DEFAULT NULL,
  `clave` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT ignore INTO `nomi_otros_pagos` (`id`, `descripcion`, `clave`)
VALUES
    (1,'Reintegro de ISR pagado en exceso (siempre que no haya sido enterado al SAT)','001'),
    (2,'Subsidio para el empleo (efectivamente entregado al trabajador)','002'),
    (3,'Viaticos (entregados al trabajador)','003'),
    (4,'Aplicacion de saldo a favor por compensacion anual','004'),
    (5,'Pagos distintos a los listados y que no deben considerarse como ingreso por sueldos, salarios o ingresos asimilados.','999');


INSERT ignore INTO `nomi_percepciones` (`idAgrupador`, `clave`, `descripcion`, `account_id`)
VALUES
    (37,'044','Jubilaciones, pensiones o haberes de retiro en parcialidades',-1),
    (38,'045','Ingresos en acciones o títulos valor que representan bienes',-1),
    (39,'046','Ingresos asimilados a salarios',-1),
    (40,'047','Alimentación',-1),
    (41,'048','Habitación',-1),
    (42,'049','Premios por asistencia',-1),
    (43,'050','Viáticos',-1);


CREATE TABLE `nomi_periodicidad` (
  `idperiodicidad` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(50) DEFAULT NULL,
  `clave` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`idperiodicidad`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


INSERT INTO `nomi_periodicidad` (`idperiodicidad`, `descripcion`, `clave`)
VALUES
    (1,'Diario','01'),
    (2,'Semanal','02'),
    (3,'Catorcenal','03'),
    (4,'Quincenal','04'),
    (5,'Mensual','05'),
    (6,'Bimestral','06'),
    (7,'Unidad Obra','07'),
    (8,'Comisión','08'),
    (9,'Precio Alzado','09'),
    (10,'Decenal','10'),
    (11,'Otra Periodicidad','99');


CREATE TABLE `nomi_puesto` (
  `idPuesto` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) CHARACTER SET latin1 NOT NULL,
  `descripcion` varchar(500) CHARACTER SET latin1 DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '-1',
  PRIMARY KEY (`idPuesto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `nomi_regimencontratacion` (
  `idregimencontrato` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `clave` char(2) DEFAULT NULL,
  `descripcion` varchar(300) DEFAULT NULL,
  PRIMARY KEY (`idregimencontrato`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


INSERT INTO `nomi_regimencontratacion` (`idregimencontrato`, `clave`, `descripcion`)
VALUES
    (1,'02','Sueldos'),
    (2,'03','Jubilados'),
    (3,'04','Pensionados'),
    (4,'05','Asimilados Miembros Sociedades Cooperativas Produccion'),
    (5,'06','Asimilados Integrantes Sociedades Asociaciones Civiles'),
    (6,'07','Asimilados Miembros consejos'),
    (7,'08','Asimilados comisionistas'),
    (8,'09','Asimilados Honorarios'),
    (9,'10','Asimilados acciones'),
    (10,'11','Asimilados otros'),
    (11,'99','Otro Regimen');


CREATE TABLE `nomi_regimenfiscal` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(100) DEFAULT NULL,
  `clave` varchar(3) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `nomi_regimenfiscal` (`id`, `descripcion`, `clave`)
VALUES
    (1,'General de Ley Personas Morales','601'),
    (2,'Personas Morales con Fines no Lucrativos','603'),
    (3,'Sueldos y Salarios e Ingresos Asimilados a Salarios','605'),
    (4,'Arrendamiento','606'),
    (5,'Demás ingresos','608'),
    (6,'Consolidación','609'),
    (7,'Residentes en el Extranjero sin Establecimiento Permanente en México','610'),
    (8,'Ingresos por Dividendos (socios y accionistas)','611'),
    (9,'Personas Físicas con Actividades Empresariales y Profesionales','612'),
    (10,'Ingresos por intereses','614'),
    (11,'Sin obligaciones fiscales','616'),
    (12,'Sociedades Cooperativas de Producción que optan por diferir sus ingresos','620'),
    (13,'Incorporación Fiscal','621'),
    (14,'Actividades Agrícolas, Ganaderas, Silvícolas y Pesqueras','622'),
    (15,'Opcional para Grupos de Sociedades','623'),
    (16,'Coordinados','624'),
    (17,'Hidrocarburos','628'),
    (18,'Régimen de Enajenación o Adquisición de Bienes','607'),
    (19,'De los Regímenes Fiscales Preferentes y de las Empresas Multinacionales','629'),
    (20,'Enajenación de acciones en bolsa de valores','630'),
    (21,'Régimen de los ingresos por obtención de premios','615');


CREATE TABLE `nomi_registropatronal` (
  `idregistrop` int(11) NOT NULL AUTO_INCREMENT,
  `registro` varchar(13) CHARACTER SET latin1 NOT NULL,
  `localidad` varchar(50) CHARACTER SET latin1 DEFAULT NULL,
  `idestado` int(11) NOT NULL,
  `cp` varchar(6) CHARACTER SET latin1 NOT NULL,
  `idclaveriesgotrabajo` int(11) DEFAULT NULL,
  `idfraccion` int(11) DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '-1',
  PRIMARY KEY (`idregistrop`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `nomi_riesgopuesto` (
  `idclaveriesgopuesto` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`idclaveriesgopuesto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `nomi_riesgopuesto` (`idclaveriesgopuesto`, `descripcion`)
VALUES
    (1,'Clase I'),
    (2,'Clase II'),
    (3,'Clase III'),
    (4,'Clase IV'),
    (5,'Clase V');

CREATE TABLE `nomi_salariosminimos` (
  `idsalariominimo` int(11) NOT NULL AUTO_INCREMENT,
  `vigencia` date NOT NULL,
  `zona_a` double(100,2) NOT NULL,
  `zona_b` double(100,2) NOT NULL,
  `zona_c` double(100,2) NOT NULL,
  PRIMARY KEY (`idsalariominimo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


INSERT INTO `nomi_salariosminimos` (`idsalariominimo`, `vigencia`, `zona_a`, `zona_b`, `zona_c`)
VALUES
    (1,'2016-01-01',73.04,73.04,73.04),
    (2,'2017-01-01',80.04,80.04,80.04);


INSERT INTO `nomi_sexo` (`idsexo`, `sexo`)
VALUES
    (1,'Femenino'),
    (2,'Masculino');

CREATE TABLE `nomi_tinfonavit_segvivienda` (
  `idsegvivienda` int(11) NOT NULL AUTO_INCREMENT,
  `vigencia` date DEFAULT NULL,
  `cuota` double NOT NULL,
  PRIMARY KEY (`idsegvivienda`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE TABLE `nomi_tipo_acumulado` (
  `idtipoacumulado` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(40) DEFAULT NULL,
  PRIMARY KEY (`idtipoacumulado`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `nomi_tipo_acumulado` (`idtipoacumulado`, `nombre`)
VALUES
    (1,'Dias y horas'),
    (2,'Percepcion deduccion y obligacion');


CREATE TABLE `nomi_tipo_empleado` (
  `idtipoempleado` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tipo` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`idtipoempleado`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `nomi_tipo_empleado` (`idtipoempleado`, `tipo`)
VALUES
    (1,'Sindicalizado'),
    (2,'Confianza');



CREATE TABLE `nomi_tipoconcepto` (
  `idtipo` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tipo` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`idtipo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `nomi_tipoconcepto` (`idtipo`, `tipo`)
VALUES
    (1,'Percepcion'),
    (2,'Deduccion'),
    (3,'Obligacion');


CREATE TABLE `nomi_tipocontrato` (
  `idtipocontrato` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(100) DEFAULT NULL,
  `clave` varchar(2) DEFAULT NULL,
  PRIMARY KEY (`idtipocontrato`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


INSERT INTO `nomi_tipocontrato` (`idtipocontrato`, `descripcion`, `clave`)
VALUES
    (1,'Contrato de trabajo por tiempo indeterminado','01'),
    (2,'Contrato de trabajo para obra determinada','02'),
    (3,'Contrato de trabajo por tiempo determinado','03'),
    (4,'Contrato de trabajo por temporada','04'),
    (5,'Contrato de trabajo sujeto a prueba','05'),
    (6,'Contrato de trabajo con capacidad inicial','06'),
    (7,'Modalidad de contratacion por pago de hora laborada','07'),
    (8,'Modalidad de trabajo por comision laboral','08'),
    (9,'Modalidades de contratacion donde no existe relacion de trabajo','09'),
    (10,'Jubilación, pensión, retiro.','10'),
    (11,'Otro trabajo','99');

CREATE TABLE `nomi_tipohoras` (
  `idhora` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(10) DEFAULT NULL,
  `clave` varchar(2) DEFAULT NULL,
  PRIMARY KEY (`idhora`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


INSERT INTO `nomi_tipohoras` (`idhora`, `descripcion`, `clave`)
VALUES
    (1,'Dobles','01'),
    (2,'Triples','02'),
    (3,'Simples','03');

CREATE TABLE `nomi_tiponomina` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(50) DEFAULT NULL,
  `clave` varchar(2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `nomi_tiponomina` (`id`, `descripcion`, `clave`)
VALUES
    (1,'Nómina ordinaria','O'),
    (2,'Nómina extraordinaria','E');


CREATE TABLE `nomi_tiposdeperiodos` (
  `idtipop` int(11) NOT NULL AUTO_INCREMENT,
  `fechainicio` datetime DEFAULT NULL,
  `nombre` varchar(100) CHARACTER SET latin1 NOT NULL,
  `diasperiodo` int(11) NOT NULL,
  `diaspago` double(100,2) NOT NULL,
  `periodotrabajo` int(11) NOT NULL,
  `ajustemes` tinyint(1) DEFAULT NULL,
  `septimodia` int(11) DEFAULT NULL,
  `idajuste` int(11) DEFAULT NULL,
  `diapago` int(11) DEFAULT NULL,
  `idperiodicidad` int(11) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '-1',
  PRIMARY KEY (`idtipop`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `nomi_turno` (
  `idturno` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) CHARACTER SET latin1 NOT NULL,
  `horas` double NOT NULL,
  `idjornada` int(11) DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '-1',
  PRIMARY KEY (`idturno`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE TABLE `nomi_tvig_isr_anual` (
  `idisranual` int(11) NOT NULL AUTO_INCREMENT,
  `limite_inferior` double(100,2) NOT NULL,
  `cuotafija` double(100,2) DEFAULT NULL,
  `porcentaje` double(100,2) NOT NULL,
  PRIMARY KEY (`idisranual`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


INSERT INTO `nomi_tvig_isr_anual` (`idisranual`, `limite_inferior`, `cuotafija`, `porcentaje`)
VALUES
    (1,0.01,0.00,1.92),
    (2,5952.85,114.29,6.40),
    (3,50524.93,2966.91,10.88),
    (4,88793.05,7130.48,16.00),
    (5,103218.01,9438.47,17.92),
    (6,123580.21,13087.37,21.36),
    (7,249243.49,39929.05,23.52),
    (8,392841.97,73703.41,30.00),
    (9,750000.01,180850.82,32.00),
    (10,1000000.01,260850.81,34.00),
    (11,3000000.01,940850.81,35.00);


CREATE TABLE `nomi_tvig_isr_mensual` (
  `idisrmensual` int(11) NOT NULL AUTO_INCREMENT,
  `limiteinferior` double(100,2) NOT NULL,
  `cuotafija` double(100,2) NOT NULL,
  `porcentaje` double(100,2) NOT NULL,
  PRIMARY KEY (`idisrmensual`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


INSERT INTO `nomi_tvig_isr_mensual` (`idisrmensual`, `limiteinferior`, `cuotafija`, `porcentaje`)
VALUES
    (1,0.01,0.00,1.92),
    (2,496.08,9.52,6.40),
    (3,4210.42,247.24,10.88),
    (4,7399.43,7399.43,16.00),
    (5,8601.51,786.54,17.92),
    (6,10298.36,1090.61,21.36),
    (7,20770.30,3327.42,23.52),
    (8,32736.84,6141.95,30.00),
    (9,62500.01,15070.90,32.00),
    (10,83333.34,21737.57,34.00),
    (11,250000.01,78404.23,35.00);


CREATE TABLE `nomi_tvig_subemp_anual` (
  `idempanual` int(11) NOT NULL AUTO_INCREMENT,
  `limiteinferior` double(100,2) NOT NULL,
  `subsempleo` double(100,2) NOT NULL,
  PRIMARY KEY (`idempanual`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


INSERT INTO `nomi_tvig_subemp_anual` (`idempanual`, `limiteinferior`, `subsempleo`)
VALUES
    (1,0.01,4884.24),
    (2,21227.64,4881.96),
    (3,31840.68,4879.44),
    (4,41674.20,4713.24),
    (5,42454.56,4589.52),
    (6,53353.92,4250.76),
    (7,56606.28,3898.44),
    (8,64025.16,3535.56),
    (9,74696.16,3042.48),
    (10,85366.92,2611.32),
    (11,88588.08,0.00);


CREATE TABLE `nomi_tvig_subemp_mensual` (
  `idempmensual` int(11) NOT NULL AUTO_INCREMENT,
  `limiteinferior` double(100,2) NOT NULL,
  `subsalempleo` double(100,2) NOT NULL,
  PRIMARY KEY (`idempmensual`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `nomi_tvig_subemp_mensual` (`idempmensual`, `limiteinferior`, `subsalempleo`)
VALUES
    (1,0.01,407.02),
    (2,1768.97,406.83),
    (3,2653.39,406.62),
    (4,3472.85,392.77),
    (5,3537.88,382.46),
    (6,4446.16,354.23),
    (7,4717.19,324.87),
    (8,5335.43,294.63),
    (9,294.63,253.54),
    (10,7113.91,217.61),
    (11,7382.34,0.00);

INSERT INTO `accelog_categorias` (`idcategoria`, `nombre`, `icono`, `orden`)
VALUES
    (1055, 'Nominas', 0, 12);




INSERT INTO `catalog_estructuras` (`idestructura`, `nombreestructura`, `descripcion`, `fechacreacion`, `fechamodificacion`, `estatus`, `utilizaidorganizacion`, `linkproceso`, `columnas`, `linkprocesoantes`)
VALUES
    (441, 'nomi_tvig_subemp_mensual', 'TVigSubEmpMensual', '2017-02-02 16:43:45', '2017-02-02 16:45:54', 'A', 0, '', 0, ''),
    (440, 'nomi_tvig_subemp_anual', 'TVigSubEmpAnual', '2017-02-02 16:36:07', '2017-02-02 16:38:13', 'A', 0, '', 0, ''),
    (438, 'nomi_tvig_isr_mensual', 'TVigISRMensual', '2017-02-02 12:06:42', '2017-02-02 12:59:44', 'A', 0, '', 0, ''),
    (437, 'nomi_tvig_isr_anual', 'TVigISRAnual', '2017-02-02 11:46:36', '2017-02-02 17:00:37', 'A', 0, '', 0, ''),
    (417, 'nomi_turno', 'Turno', '2017-01-13 16:50:30', '2017-01-13 17:00:29', 'A', 0, '', 0, ''),
    (431, 'nomi_tipo_acumulado', 'Tipo acumulado', '2017-01-18 16:43:09', '2017-01-18 16:43:09', 'G', 0, '', 0, ''),
    (414, 'nomi_tiposdeperiodos', 'Tipos de Periodos', '2017-01-13 10:32:59', '2017-01-13 12:19:10', 'A', 0, '', 0, ''),
    (439, 'nomi_tipohoras', 'Tipo horas', '2017-01-17 16:50:18', '2017-01-17 16:50:18', 'G', 0, '', 0, ''),
    (424, 'nomi_tipoconcepto', 'Tipo concepto', '2017-01-16 17:30:00', '2017-01-16 17:30:00', 'G', 0, '', 0, ''),
    (436, 'nomi_tinfonavit_segvivienda', 'Infonavit seguimiento vivienda', '2017-02-01 11:29:52', '2017-02-01 11:55:30', 'A', 0, '', 0, ''),
    (435, 'nomi_salariosminimos', 'Salarios Minimos', '2017-01-31 12:33:54', '2017-01-31 12:46:57', 'A', 0, '', 0, ''),
    (434, 'nomi_finiquito', 'Finiquito', '2017-01-31 12:21:36', '2017-01-31 12:31:41', 'A', 0, '', 0, ''),
    (429, 'nomi_fraccionriesgocatalogo', 'Fraccion de riesgo', '2017-01-17 10:39:54', '2017-01-17 10:39:54', 'G', 0, '', 0, ''),
    (428, 'nomi_riesgopuesto', 'Riesgo puesto', '2017-01-17 10:38:24', '2017-01-17 10:38:24', 'G', 0, '', 0, ''),
    (427, 'nomi_registropatronal', 'Registros Patronales', '2017-01-17 09:51:41', '2017-01-17 10:46:13', 'A', 0, '', 0, '../../modulos/nominas/js/antesregistropatronal.php'),
  (452, 'nomi_riesgotrabajo', 'Riesgo trabajo', '2017-06-01 21:37:25', '2017-06-01 21:37:25', 'G', 0, '', 0, ''),
   
    (412, 'nomi_departamento', 'Departamento', '2017-01-13 10:02:49', '2017-01-13 10:04:14', 'A', 0, '', 0, ''),
    (413, 'nomi_puesto', 'Puesto', '2017-01-13 10:12:34', '2017-01-13 10:14:19', 'A', 0, '', 0, ''),
    
    (415, 'nomi_ajustedias', 'Ajuste de dias pagados en quincenas de 16 dias o febrero', '2017-01-13 11:55:18', '2017-01-13 11:55:18', 'G', 0, '', 0, ''),
    (416, 'nomi_periodicidad', 'Periodicidad de Pago', '2017-01-13 12:06:00', '2017-01-13 12:09:35', 'G', 0, '', 0, ''),
    
    (418, 'nomi_jornada', 'Tipo de Jornada', '2017-01-13 16:55:10', '2017-01-13 16:55:10', 'G', 0, '', 0, ''),
    (419, 'nomi_antiguedades', 'Antigüedades ', '2017-01-13 17:09:55', '2017-01-16 11:16:24', 'A', 0, '', 0, ''),
    (420, 'nomi_imssriesgot', 'TIMSSRIESGOT', '2017-01-16 12:00:57', '2017-01-17 11:16:36', 'A', 0, '', 0, ''),
    
    (426, 'nomi_factorSDI', 'Factor SDI', '2017-01-17 09:32:50', '2017-01-17 09:36:39', 'A', 0, '', 0, ''),
    (423, 'nomi_conceptos', 'Catalogo de conceptos', '2017-01-16 17:05:07', '2017-01-16 17:35:26', 'A', 0, '', 0, '../../modulos/nominas/js/antesconceptos.php'),
    (432, 'nomi_acumulados', 'Tipos de acumulados', '2017-01-18 16:45:00', '2017-01-18 16:48:51', 'A', 0, '', 0, '');

INSERT ignore INTO `catalog_campos` (`idcampo`, `idestructura`, `nombrecampo`, `nombrecampousuario`, `descripcion`, `longitud`, `tipo`, `valor`, `formula`, `requerido`, `formato`, `orden`, `llaveprimaria`)
VALUES
    (2509, 441, 'idempmensual', 'ID', 'ID', 11, 'auto_increment', 'NA', '', -1, '', 0, -1),
    (2510, 441, 'limite_inferior', 'Limite inferior', 'Limite inferior', 100, 'double', 'NA', '', -1, '', 1, 0),
    (2511, 441, 'subsalempleo', 'Subsidio al empleo', 'Subsidio al empleo', 0, 'double', 'NA', '', -1, '', 3, 0),
    (2570, 441, 'limite_superior', 'Limite Superior', 'Limite Superior', 100, 'double', 'NA', '', -1, '', 2, 0),
    (2506, 440, 'idempanual', 'ID', 'ID', 11, 'auto_increment', 'NA', '', -1, '', 0, -1),
    (2507, 440, 'limite_inferior', 'Limite inferior', 'Limite inferior', 0, 'double', 'NA', '', -1, '', 1, 0),
    (2508, 440, 'subsempleo', 'Subsidio al empleo', 'Subsidio al empleo', 0, 'double', 'NA', '', -1, '', 2, 0),
    (2499, 438, 'idisrmensual', 'ID', 'ID', 11, 'auto_increment', 'NA', '', 0, '', 0, -1),
    (2503, 438, 'limite_inferior', 'Limite inferior', 'Limite inferior', 0, 'double', 'NA', '', -1, '', 1, 0),
    (2504, 438, 'cuotafija', 'Cuota fija', 'Cuota fija', 0, 'double', 'NA', '', -1, '', 2, 0),
    (2505, 438, 'porcentaje', 'Porcenjate', 'Porcenjate', 0, 'double', 'NA', '', -1, '', 3, 0),
    (2569, 438, 'limite_superior', 'Limite superior', 'Limite superior', 100, 'double', 'NA', '', -1, '', 2, 0),
    (2495, 437, 'idisranual', 'ID', 'ID', 11, 'auto_increment', 'NA', '', 0, '', 0, -1),
    (2496, 437, 'limite_inferior', 'Limite Inferior', 'Limite Inferior', 0, 'double', 'NA', '', -1, '', 1, 0),
    (2497, 437, 'cuotafija', 'Cuota Fija', 'Cuota Fija', 0, 'double', 'NA', '', 0, '', 2, 0),
    (2498, 437, 'porcentaje', 'Porcentaje', 'Porcentaje', 0, 'double', 'NA', '', -1, '', 3, 0),
    (2571, 437, 'limite_superior', 'Limite superior', 'Limite superior', 100, 'double', 'NA', '', -1, '', 2, 0),
    (2399, 417, 'idturno', 'ID', 'ID', 11, 'auto_increment', 'NA', '', 0, '', 0, -1),
    (2400, 417, 'nombre', 'Nombre', 'Nombre', 50, 'varchar', 'NA', '', -1, '', 1, 0),
    (2401, 417, 'horas', 'Horas por turno', 'Horas por turno', 0, 'double', 'NA', '', -1, '', 2, 0),
    (2402, 417, 'idjornada', 'Tipo de jornada', 'Tipo de jornada', 11, 'int', 'NA', '', 0, '-1', 3, 0),
    (2479, 417, 'activo', 'Activo', 'Activo', 0, 'boolean', 'NA', '', 0, '', 4, 0),
    (2466, 431, 'idtipoacumulado', 'ID', 'ID', 11, 'auto_increment', 'NA', '', -1, '', 0, -1),
    (2467, 431, 'nombre', 'Nombre', 'Nombre', 40, 'varchar', 'NA', '', 0, '', 0, 0),
    (2383, 414, 'idtipop', 'ID', 'ID', 11, 'auto_increment', 'NA', '', -1, '', 0, -1),
    (2384, 414, 'fechainicio', 'Fecha inicio del ejercicio', 'Fecha inicio del ejercicio', 0, 'date', 'NA', '', -1, '', 1, 0),
    (2385, 414, 'nombre', 'Nombre', 'Nombre', 100, 'varchar', 'NA', '', -1, '', 2, 0),
    (2386, 414, 'diasperiodo', 'Dias del periodo', 'Dias del periodo', 11, 'int', 'NA', '', -1, '', 3, 0),
    (2387, 414, 'diaspago', 'Dias de pago', 'Dias de pago', 0, 'double', 'NA', '', -1, '', 4, 0),
    (2388, 414, 'periodotrabajo', 'Periodo de trabajo', 'Periodo de trabajo', 11, 'int', 'NA', '', -1, '', 5, 0),
    (2389, 414, 'ajustemes', 'Ajuste al mes calendario', 'Ajuste al mes calendario', 0, 'boolean', 'NA', '', 0, '', 6, 0),
    (2390, 414, 'septimodia', 'Posición de los séptimos dias', 'Posición de los séptimos dias', 0, 'int', 'NA', '', 0, '', 8, 0),
    (2393, 414, 'idajuste', 'Ajuste de dias pagados en quincenas de 16 dias o F', 'Ajuste de dias pagados en quincenas de 16 dias o Febrero', 11, 'int', 'NA', '', 0, '-1', 7, 0),
    (2394, 414, 'diapago', 'Posición del dia de pago', 'Posición del dia de pago', 11, 'int', 'NA', '', 0, '', 9, 0),
    (2398, 414, 'idperiodicidad', 'Periodicidad de pago', 'Periodicidad de pago', 11, 'int', 'NA', '', -1, '-1', 10, 0),
    (2476, 414, 'activo', 'Activo', 'Activo', 0, 'boolean', 'NA', '', 0, '', 11, 0),
    (2500, 439, 'idhora', 'ID', 'ID', 11, 'auto_increment', 'NA', '', 0, '', 0, -1),
    (2501, 439, 'descripcion', 'Descripcion', 'Descripcion', 10, 'varchar', 'NA', '', 0, '', 1, 0),
    (2502, 439, 'clave', 'Clave', 'Clave', 2, 'varchar', 'NA', '', 0, '', 2, 0),
    (2434, 424, 'idtipo', 'ID', 'ID', 11, 'auto_increment', 'NA', '', 0, '', 0, -1),
    (2435, 424, 'tipo', 'Tipo', 'Tipo', 50, 'varchar', 'NA', '', 0, '', 1, 0),
    (2480, 434, 'idfiniquito', 'ID', 'ID', 11, 'auto_increment', 'NA', '', 0, '', 0, -1),
    (2481, 434, 'numero', 'Numero', 'Numero', 0, 'double', 'NA', '', 0, '', 1, 0),
    (2482, 434, 'casusmo', 'CASUSMO', 'CASUSMO', 0, 'double', 'NA', '', 0, '', 2, 0),
    (2483, 434, 'casisr86', 'CASISR86', 'CASISR86', 100, 'double', 'NA', '', 0, '', 3, 0),
    (2484, 434, 'caldirecperc', 'CalDirecPerc', 'CalDirecPerc', 100, 'double', 'NA', '', 0, '', 4, 0),
    (2485, 434, 'indem90', 'Indem90', 'Indem90', 100, 'double', 'NA', '', 0, '', 5, 0),
    (2486, 435, 'idsalariominimo', 'ID', 'ID', 0, 'auto_increment', 'NA', '', 0, '', 0, -1),
    (2487, 435, 'zona_a', 'Zona A', 'Zona A', 0, 'double', 'NA', '', -1, '', 2, 0),
    (2488, 435, 'zona_b', 'Zona B', 'Zona B', 0, 'double', 'NA', '', -1, '', 3, 0),
    (2489, 435, 'vigencia', 'Vigencia', 'Vigencia', 0, 'date', 'NA', '', -1, '', 1, 0),
    (2490, 435, 'zona_c', 'Zona C', 'Zona C', 0, 'double', 'NA', '', -1, '', 4, 0),
    (2491, 436, 'idsegvivienda', 'ID', 'ID', 11, 'auto_increment', 'NA', '', 0, '', 0, -1),
    (2492, 436, 'vigencia', 'Vigencia', 'Vigencia', 0, 'date', 'NA', '', -1, '', 1, 0),
    (2493, 436, 'cuota', 'Cuota', 'Cuota', 0, 'double', 'NA', '', -1, '', 2, 0),
    (2446, 427, 'idregistrop', 'ID', 'ID', 11, 'auto_increment', 'NA', '', -1, '', 0, -1),
    (2447, 427, 'registro', 'Registro patronal del IMSS', 'Registro patronal del IMSS', 13, 'varchar', 'NA', '', -1, '', 1, 0),
    (2448, 427, 'localidad', 'Localidad', 'Localidad', 50, 'varchar', 'NA', '', 0, '', 2, 0),
    (2449, 427, 'idestado', 'Entidad Federativa', 'Entidad Federativa', 11, 'int', 'NA', '', -1, '-1', 3, 0),
    (2450, 427, 'cp', 'Código postal', 'Código postal', 6, 'varchar', 'NA', '', -1, '', 4, 0),
    (2451, 427, 'idclaveriesgotrabajo', 'Clase de riesgo de trabajo', 'Clase de riesgo de trabajo', 11, 'int', 'NA', '', 0, '-1', 5, 0),
    (2452, 427, 'idfraccion', 'Fracción de riesgo de trabajo', 'Fracción de riesgo de trabajo', 11, 'int', 'NA', '', 0, '-1', 6, 0),
    (2475, 427, 'activo', 'Activo', 'Activo', 0, 'boolean', '1', '', 0, '', 7, 0),
    (2453, 428, 'idclaveriesgopuesto', 'ID', 'ID', 11, 'auto_increment', 'NA', '', -1, '', 0, -1),
    (2454, 428, 'descripcion', 'Descripcion', 'Descripcion', 50, 'varchar', 'NA', '', -1, '', 1, 0),
    (2455, 429, 'idfraccion', 'idfraccion', 'idfraccion', 11, 'int', 'NA', '', 0, '', 0, 0),
    (2456, 429, 'descripcion', 'Descripcion', 'Descripcion', 500, 'varchar', 'NA', '', 0, '', 1, 0),
    (2457, 429, 'fraccion', 'fraccion', 'fraccion', 11, 'int', 'NA', '', 0, '', 2, 0),
    (2458, 429, 'idclaveriesgopuesto', 'Riesgo clave', 'Riesgo clave', 11, 'int', 'NA', '', 0, '', 3, 0),
    (2378, 412, 'idDep', 'ID', 'ID', 11, 'auto_increment', 'NA', '', -1, '', 0, -1),
    (2379, 412, 'nombre', 'Nombre', 'Nombre', 100, 'varchar', 'NA', '', -1, '', 1, 0),
    (2477, 412, 'activo', 'Activo', 'Activo', 0, 'boolean', 'NA', '', 0, '', 2, 0),
    (2380, 413, 'idPuesto', 'ID', 'ID', 11, 'auto_increment', 'NA', '', -1, '', 0, -1),
    (2381, 413, 'nombre', 'Nombre', 'Nombre Puesto', 100, 'varchar', 'NA', '', -1, '', 1, 0),
    (2382, 413, 'descripcion', 'Descripción del puesto', 'Descripción del puesto', 500, 'varchar', 'NA', '', 0, '', 2, 0),
    (2478, 413, 'activo', 'Activo', 'Activo', 0, 'boolean', 'NA', '', 0, '', 3, 0),
    (2391, 415, 'idajuste', 'ID', 'ID', 11, 'auto_increment', 'NA', '', 0, '', 0, 0),
    (2392, 415, 'nombre', 'Nombre', 'Nombre', 50, 'varchar', 'NA', '', 0, '', 1, 0),
    (2395, 416, 'idperiodicidad', 'Periodicidad de pago', 'Periodicidad de pago', 11, 'int', 'NA', '', 0, '', 0, -1),
    (2396, 416, 'descripcion', 'Nombre', 'Nombre', 50, 'varchar', 'NA', '', 0, '', 1, 0),
    (2397, 416, 'clave', 'Clave', 'Clave', 5, 'varchar', 'NA', '', 0, '', 2, 0),
    (2403, 418, 'idjornada', 'ID', 'ID', 11, 'auto_increment', 'NA', '', 0, '', 0, -1),
    (2404, 418, 'descripcion', 'Descripcion', 'Descripcion', 50, 'varchar', 'NA', '', 0, '', 1, 0),
    (2405, 418, 'clave', 'Clave', 'Clave', 5, 'varchar', 'NA', '', 0, '', 2, 0),
    (2406, 419, 'idantiguedad', 'Antigüedad ', 'Antigüedad', 11, 'auto_increment', 'NA', '', -1, '', 0, -1),
    (2407, 419, 'dias_vac_conf', 'Dias vacaciones conf', 'Dias vacaciones conf', 0, 'double', 'NA', '', 0, '', 1, 0),
    (2408, 419, 'dias_vac_sind', 'Dias vacaciones sindicalizado', 'Dias vacaciones sindicalizado', 0, 'double', 'NA', '', 0, '', 2, 0),
    (2409, 419, 'porc_prima_conf', 'Porc prima conf', 'Porc prima conf', 0, 'double', 'NA', '', 0, '', 3, 0),
    (2410, 419, 'porc_prima_sind', 'Porc prima sindicalizado', 'Porc prima sindicalizado', 0, 'double', 'NA', '', 0, '', 4, 0),
    (2411, 419, 'dias_antig_conf', 'Dias antigüedad conf', 'Dias antigüedad conf', 0, 'double', 'NA', '', 0, '', 5, 0),
    (2412, 419, 'dias_antig_sind', 'Dias antigüedad sindicalizado', 'Dias antigüedad sindicalizado', 0, 'double', 'NA', '', 0, '', 6, 0),
    (2413, 419, 'dias_aguinaldo_c', 'Dias aguinaldo c', 'Dias aguinaldo c', 0, 'double', 'NA', '', 0, '', 8, 0),
    (2414, 419, 'dias_aguinaldo_si', 'Dias aguinaldo sindicalizado ', 'Dias aguinaldo sindicalizado ', 0, 'double', 'NA', '', 0, '', 9, 0),
    (2415, 420, 'id', 'ID', 'ID', 11, 'auto_increment', 'NA', '', -1, '', 0, -1),
    (2418, 420, 'idregistrop', 'Registro patronal', 'Registro patronal', 11, 'int', 'NA', '', 0, '-1', 2, 0),
    (2473, 420, 'fecha', 'Fecha de vigencia', 'Fecha de vigencia', 0, 'date', 'NA', '', 0, '', 1, 0),
    (2474, 420, 'factor', 'Factor riesgo', 'Factor riesgo', 0, 'double', 'NA', '', -1, '', 3, 0),
    (2443, 426, 'antiguedad', 'Antigüedad ', 'Antigüedad', 11, 'auto_increment', 'NA', '', -1, '', 0, -1),
    (2444, 426, 'factor_conf', 'Factor conf', 'Factor conf', 0, 'double', 'NA', '', 0, '', 1, 0),
    (2445, 426, 'factor_sind', 'Factor sind', 'Factor sind', 0, 'double', 'NA', '', 0, '', 2, 0),
    (2427, 423, 'idconcepto', 'ID', 'ID', 11, 'auto_increment', 'NA', '', 0, '', 0, -1),
    (2428, 423, 'descripcion', 'Descripcion', 'Descripcion', 100, 'varchar', 'NA', '', -1, '', 1, 0),
    (2429, 423, 'global', 'Automatico global', 'Automatico global', 0, 'boolean', 'NA', '', 0, '', 5, 0),
    (2430, 423, 'liquidacion', 'Automatico liquidación ', 'Automatico liquidación ', 0, 'boolean', 'NA', '', 0, '', 3, 0),
    (2431, 423, 'especie', 'Especie ', 'Especie', 0, 'boolean', 'NA', '', 0, '', 4, 0),
    (2432, 423, 'idAgrupador', 'Clave agrupadora SAT', 'Clave agrupadora SAT', 11, 'int', '0', '', 0, '-1', 6, 0),
    (2433, 423, 'idtipo', 'Tipo', 'Tipo', 11, 'int', 'NA', '', 0, '-1', 2, 0),
    (2463, 423, 'idhora', 'Tipo de hora extra', 'Tipo de hora extra', 11, 'int', '0', '', 0, '-1', 7, 0),
    (2464, 423, 'idFormapago', 'Metodo de pago', 'Metodo de pago', 11, 'int', '0', '', 0, '-1', 8, 0),
    (2468, 432, 'idacumulado', 'ID', 'ID', 11, 'auto_increment', 'NA', '', -1, '', 0, -1),
    (2469, 432, 'nombre', 'Nombre', 'Nombre', 100, 'varchar', 'NA', '', -1, '', 1, 0),
    (2470, 432, 'idtipoacumulado', 'Tipo de acumulado', 'Tipo de acumulado', 11, 'int', 'NA', '', 0, '-1', 2, 0),
  (2576, 452, 'idclaveriesgotrabajo', 'ID', 'ID', 11, 'auto_increment', 'NA', '', 0, '', 0, -1),
  (2577, 452, 'descripcion', 'Descripcion', 'Descripcion', 20, 'varchar', 'NA', '', 0, '', 1, 0);


INSERT INTO `catalog_dependencias` (`idcampo`, `tipodependencia`, `dependenciatabla`, `dependenciacampovalor`, `dependenciacampodescripcion`)
VALUES
    (2393, 'S', 'nomi_ajustedias', 'idajuste', ' nombre '),
    (2398, 'S', 'nomi_periodicidad', 'idperiodicidad', ' descripcion , clave '),
    (2402, 'S', 'nomi_jornada', 'idjornada', ' descripcion , clave '),
    (2418, 'S', 'nomi_registropatronal', 'idregistrop', ' registro '),
    (2422, 'S', 'cont_accounts', 'account_id', ' manual_code , descripcion '),
    (2432, 'S', 'nomi_deducciones', 'idAgrupador', ' clave , descripcion '),
    (2433, 'S', 'nomi_tipoconcepto', 'idtipo', ' tipo '),
    (2449, 'S', 'estados', 'idestado', ' estado '),
    (2451, 'S', 'nomi_riesgotrabajo', 'idclaveriesgotrabajo', ' idclaveriesgotrabajo , descripcion '),
    (2452, 'S', 'nomi_fraccionriesgocatalogo', 'idfraccion', ' descripcion , fraccion '),
    (2463, 'S', 'nomi_tipohoras', 'idhora', ' descripcion , clave '),
    (2464, 'S', 'forma_pago', 'idFormapago', ' nombre , claveSat '),
    (2470, 'S', 'nomi_tipo_acumulado', 'idtipoacumulado', ' nombre ');




INSERT INTO `catalog_estructuras` (`idestructura`, `nombreestructura`, `descripcion`, `fechacreacion`, `fechamodificacion`, `estatus`, `utilizaidorganizacion`, `linkproceso`, `columnas`, `linkprocesoantes`)
VALUES
  (442, 'nomi_imss_patron', 'IMSS Patron', '2017-02-08 10:39:44', '2017-02-08 10:51:09', 'A', 0, '', 0, ''),
  (443, 'nomi_imss_trabajador', 'IMSS Trabajador', '2017-02-08 10:52:40', '2017-02-08 10:56:34', 'A', 0, '', 0, ''),
  (444, 'nomi_topes_sgdf', 'Topes SGDF', '2017-02-08 10:57:53', '2017-02-08 10:59:52', 'A', 0, '', 0, '');
INSERT INTO `catalog_campos` (`idcampo`, `idestructura`, `nombrecampo`, `nombrecampousuario`, `descripcion`, `longitud`, `tipo`, `valor`, `formula`, `requerido`, `formato`, `orden`, `llaveprimaria`)
VALUES
  (2513, 442, 'idimsspatron', 'ID', 'ID', 11, 'auto_increment', 'NA', '', 0, '', 0, -1),
  (2514, 442, 'vigencia', 'Fecha vigencia', 'Fecha vigencia', 0, 'date', 'NA', '', -1, '', 1, 0),
  (2515, 442, 'eg_especie_gast', 'EG Especie Gastos Medicos', 'EG Especie Gastos Medicos', 0, 'double', 'NA', '', -1, '', 2, 0),
  (2516, 442, 'eg_especie_fija', 'EG Especie Fija', 'EG Especie Fija', 0, 'double', 'NA', '', -1, '', 3, 0),
  (2517, 442, 'eg_especie_mas', 'EG Especie mas 3SMDF', 'EG Especie mas 3SMDF', 0, 'double', 'NA', '', -1, '', 4, 0),
  (2518, 442, 'eg_prestaciones_dinero', 'EG Prestaciones en dinero', 'EG Prestaciones en dinero', 0, 'double', 'NA', '', -1, '', 5, 0),
  (2519, 442, 'invalidez_vida', 'Invalidez y Vida', 'Invalidez y Vida', 0, 'double', 'NA', '', -1, '', 6, 0),
  (2520, 442, 'cesantia_vejez', 'Cesantía y Vejez', 'Cesantía y Vejez', 0, 'double', 'NA', '', -1, '', 7, 0),
  (2521, 442, 'guarderias', 'Guarderias', 'Guarderias', 0, 'double', 'NA', '', -1, '', 8, 0),
  (2522, 442, 'retiro', 'Retiro', 'Retiro', 0, 'double', 'NA', '', -1, '', 9, 0),
  (2523, 443, 'idimsstrabajador', 'ID', 'ID', 11, 'auto_increment', 'NA', '', 0, '', 0, -1),
  (2524, 443, 'vigencia', 'Fecha vigencia', 'Fecha vigencia', 0, 'date', 'NA', '', -1, '', 1, 0),
  (2525, 443, 'eg_especie_gast', 'EG Especie Gastos Medicos', 'EG Especie Gastos Medicos', 0, 'double', 'NA', '', -1, '', 2, 0),
  (2526, 443, 'eg_especie_fija', 'EG Especie Fija', 'EG Especie Fija', 0, 'double', 'NA', '', -1, '', 3, 0),
  (2527, 443, 'eg_especie_mas', 'EG Especie mas 3SMDF', 'EG Especie mas 3SMDF', 0, 'double', 'NA', '', -1, '', 4, 0),
  (2528, 443, 'eg_prestaciones_dinero', 'EG Prestaciones en dinero', 'EG Prestaciones en dinero', 0, 'double', 'NA', '', -1, '', 5, 0),
  (2529, 443, 'invalidez_vida', 'Invalidez y Vida', 'Invalidez y Vida', 0, 'double', 'NA', '', -1, '', 6, 0),
  (2530, 443, 'cesantia_vejez', 'Cesantía y Vejez', 'Cesantía y Vejez', 0, 'double', 'NA', '', -1, '', 7, 0),
  (2531, 443, 'guarderias', 'Guarderias', 'Guarderias', 0, 'double', 'NA', '', -1, '', 8, 0),
  (2532, 443, 'retiro', 'Retiro', 'Retiro', 0, 'double', 'NA', '', -1, '', 9, 0),
  (2533, 444, 'idtopes', 'ID', 'ID', 11, 'auto_increment', 'NA', '', 0, '', 0, -1),
  (2534, 444, 'vigencia', 'Fecha vigencia', 'Fecha vigencia', 0, 'date', 'NA', '', -1, '', 1, 0),
  (2535, 444, 'eg_especie_gast', 'EG Especie Gastos Medicos', 'EG Especie Gastos Medicos', 0, 'double', 'NA', '', -1, '', 2, 0),
  (2536, 444, 'eg_especie_fija', 'EG Especie Fija', 'EG Especie Fija', 0, 'double', 'NA', '', -1, '', 3, 0),
  (2537, 444, 'eg_especie_mas', 'EG Especie mas 3SMDF', 'EG Especie mas 3SMDF', 0, 'double', 'NA', '', -1, '', 4, 0),
  (2538, 444, 'eg_prestaciones_dinero', 'EG Prestaciones en dinero', 'EG Prestaciones en dinero', 0, 'double', 'NA', '', -1, '', 5, 0),
  (2539, 444, 'invalidez_vida', 'Invalidez y Vida', 'Invalidez y Vida', 0, 'double', 'NA', '', -1, '', 6, 0),
  (2540, 444, 'cesantia_vejez', 'Cesantía y Vejez', 'Cesantía y Vejez', 0, 'double', 'NA', '', -1, '', 7, 0),
  (2541, 444, 'guarderias', 'Guarderias', 'Guarderias', 0, 'double', 'NA', '', -1, '', 8, 0),
  (2542, 444, 'retiro', 'Retiro', 'Retiro', 0, 'double', 'NA', '', -1, '', 9, 0);

INSERT INTO `catalog_estructuras` (`idestructura`, `nombreestructura`, `descripcion`, `fechacreacion`, `fechamodificacion`, `estatus`, `utilizaidorganizacion`, `linkproceso`, `columnas`, `linkprocesoantes`)
VALUES
  (445, 'nomi_tipoincidencias', 'Tipos de incidencias', '2017-02-14 12:34:31', '2017-02-14 15:15:59', 'A', 0, '', 0, ''),
  (446, 'nomi_clasificacion_incidencias', 'Tipo', '2017-02-14 14:57:47', '2017-02-14 14:57:47', 'G', 0, '', 0, ''),
  (447, 'nomi_considera_incidencia', 'Considera', '2017-02-14 15:02:26', '2017-02-14 15:02:26', 'G', 0, '', 0, '');

INSERT INTO `catalog_campos` (`idcampo`, `idestructura`, `nombrecampo`, `nombrecampousuario`, `descripcion`, `longitud`, `tipo`, `valor`, `formula`, `requerido`, `formato`, `orden`, `llaveprimaria`)
VALUES
  (2543, 445, 'idtipoincidencia', 'ID', 'ID', 11, 'auto_increment', 'NA', '', 0, '', 0, -1),
  (2544, 445, 'clave', 'Clave', 'Clave', 0, 'varchar', 'NA', '', -1, '', 1, 0),
  (2545, 445, 'nombre', 'Nombre', 'Nombre', 100, 'varchar', 'NA', '', -1, '', 2, 0),
  (2546, 445, 'idclasificadorincidencia', 'Tipo', 'Tipo', 11, 'int', 'NA', '', 0, '-1', 3, 0),
  (2547, 445, 'valorunidad', 'Valor unidad', 'Valor unidad', 0, 'double', 'NA', '', 0, '', 4, 0),
  (2548, 445, 'derechosueldo', 'Derecho a sueldo', 'Derecho a sueldo', 0, 'boolean', 'NA', '', 0, '', 5, 0),
  (2549, 445, 'pocentaje', 'Porcentaje', 'Porcentaje', 0, 'double', 'NA', '', 0, '', 6, 0),
  (2550, 445, 'disminuyeseptimo', 'Disminuye septimo dia', 'Disminuye septimo dia', 0, 'boolean', 'NA', '', 0, '', 7, 0),
  (2551, 445, 'idconsiderado', 'Se considera', 'Se considera', 11, 'int', 'NA', '', 0, '-1', 8, 0),
  (2552, 446, 'idclasificadorincidencia', 'ID', 'id', 11, 'auto_increment', 'NA', '', 0, '', 0, -1),
  (2553, 446, 'nombre', 'Tipo', 'Tipo', 20, 'varchar', 'NA', '', 0, '', 1, 0),
  (2554, 447, 'idconsiderado', 'ID', 'ID', 11, 'auto_increment', 'NA', '', 0, '', 0, -1),
  (2555, 447, 'tipo', 'Tipo', 'Tipo', 20, 'varchar', 'NA', '', 0, '', 1, 0);

INSERT INTO `catalog_dependencias` (`idcampo`, `tipodependencia`, `dependenciatabla`, `dependenciacampovalor`, `dependenciacampodescripcion`)
VALUES
  (2546, 'S', 'nomi_clasificacion_incidencias', 'idclasificadorincidencia', ' nombre '),
  (2551, 'S', 'nomi_considera_incidencia', 'idconsiderado', ' tipo ');

INSERT INTO `catalog_dependencias` (`idcampo`, `tipodependencia`, `dependenciatabla`, `dependenciacampovalor`, `dependenciacampodescripcion`)
VALUES
  (2557, 'S', 'nomi_conceptos', 'idconcepto', ' descripcion '),
  (2558, 'S', 'nomi_tipoincidencias', 'idtipoincidencia', ' clave , nombre '),
  (2559, 'S', 'nomi_acumulados', 'idacumulado', ' nombre '),
  (2561, 'S', 'nomi_empleados', 'idEmpleado', ' apellidoPaterno , apellidoMaterno , nombreEmpleado '),
  (2562, 'S', 'nomi_acumulados', 'idacumulado', ' nombre ');

INSERT INTO `catalog_estructuras` (`idestructura`, `nombreestructura`, `descripcion`, `fechacreacion`, `fechamodificacion`, `estatus`, `utilizaidorganizacion`, `linkproceso`, `columnas`, `linkprocesoantes`)
VALUES
  (448, 'nomi_relacion_acumulados', 'Relación Acumulados (Conceptos-Percepciones)', '2017-02-15 18:53:15', '2017-02-15 19:03:32', 'A', 0, '', 0, ''),
  (449, 'nomi_relacion_empleados_conceptos', 'Relación Empleados-Conceptos', '2017-02-15 19:17:13', '2017-02-15 19:19:08', 'A', 0, '', 0, '');
INSERT INTO `catalog_campos` (`idcampo`, `idestructura`, `nombrecampo`, `nombrecampousuario`, `descripcion`, `longitud`, `tipo`, `valor`, `formula`, `requerido`, `formato`, `orden`, `llaveprimaria`)
VALUES
  (2556, 448, 'idrelacionacumulado', 'ID', 'ID', 11, 'auto_increment', 'NA', '', 0, '', 0, -1),
  (2557, 448, 'idconcepto', 'Concepto', 'Percepcion-Deduccion-Obligacion', 11, 'int', 'NA', '', 0, '-1', 1, 0),
  (2558, 448, 'idtipoincidencia', 'Incidencia', 'Incidencia', 11, 'int', 'NA', '', 0, '-1', 2, 0),
  (2559, 448, 'idacumulado', 'Acumulado', 'Acumulado', 11, 'int', 'NA', '', -1, '', 3, 0),
  (2560, 449, 'idrelacionemcon', 'ID', 'ID', 11, 'auto_increment', 'NA', '', 0, '', 0, -1),
  (2561, 449, 'idEmpleado', 'Empleado', 'Empleado', 11, 'int', 'NA', '', -1, '-1', 1, 0),
  (2562, 449, 'idacumulado', 'Acumulado', 'tipo acumulado', 11, 'int', 'NA', '', 0, '-1', 2, 0);
 
 CREATE TABLE `nomi_riesgotrabajo` (
  `idclaveriesgotrabajo` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`idclaveriesgotrabajo`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8; 

 INSERT INTO `nomi_riesgotrabajo` (`idclaveriesgotrabajo`, `descripcion`)
VALUES
  (1, 'I Ordinario'),
  (2, 'II Bajo'),
  (3, 'III Medio'),
  (4, 'IV Alto'),
  (5, 'V Maximo');


CREATE TABLE `nomi_tipoincidencias` (
  `idtipoincidencia` INT(11) NOT NULL AUTO_INCREMENT,
  `clave` VARCHAR(50) DEFAULT NULL,
  `nombre` VARCHAR(100) DEFAULT NULL,
  `idclasificadorincidencia` INT(11) DEFAULT NULL,
  `valorunidad` DOUBLE DEFAULT NULL,
  `derechosueldo` TINYINT(1) DEFAULT NULL,
  `pocentaje` DOUBLE DEFAULT NULL,
  `disminuyeseptimo` TINYINT(1) DEFAULT NULL,
  `idconsiderado` INT(11) DEFAULT NULL,
  PRIMARY KEY (`idtipoincidencia`)
) ENGINE=INNODB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
CREATE TABLE `nomi_relacion_empleados_conceptos` (
  `idrelacionemcon` int(11) NOT NULL AUTO_INCREMENT,
  `idEmpleado` int(11) NOT NULL,
  `idacumulado` int(11) DEFAULT NULL,
  PRIMARY KEY (`idrelacionemcon`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
CREATE TABLE `nomi_clasificacion_incidencias` (
  `idclasificadorincidencia` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`idclasificadorincidencia`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

INSERT INTO `nomi_clasificacion_incidencias` (`idclasificadorincidencia`, `nombre`)
VALUES
  (1, 'Destajos'),
  (2, 'Dias'),
  (3, 'Horas');


CREATE TABLE `nomi_considera_incidencia` (
  `idconsiderado` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tipo` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`idconsiderado`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

INSERT INTO `nomi_considera_incidencia` (`idconsiderado`, `tipo`)
VALUES
  (1, 'Ausencia'),
  (2, 'Incapacidad'),
  (3, 'Vacaciones'),
  (4, 'Ninguno'),
  (5, 'Festivo');

CREATE TABLE `nomi_relacion_acumulados` (
  `idrelacionacumulado` int(11) NOT NULL AUTO_INCREMENT,
  `idconcepto` int(11) DEFAULT NULL,
  `idtipoincidencia` int(11) DEFAULT NULL,
  `idacumulado` int(11) NOT NULL,
  PRIMARY KEY (`idrelacionacumulado`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

 CREATE TABLE `nomi_nominasperiodo` (
  `idnomp` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `idtipop` int(11) DEFAULT NULL COMMENT 'nomi_tiposdeperiodos',
  `numnomina` int(11) DEFAULT NULL,
  `fechainicio` date DEFAULT NULL,
  `fechafin` date DEFAULT NULL,
  `ejercicio` int(11) DEFAULT NULL,
  `mes` int(11) DEFAULT NULL,
  `diaspago` double(100,2) DEFAULT NULL,
  `iniciomes` tinyint(1) NOT NULL DEFAULT '0',
  `iniciobimentreimss` tinyint(1) NOT NULL DEFAULT '0',
  `inicioejercicio` tinyint(1) NOT NULL DEFAULT '0',
  `finmes` tinyint(1) NOT NULL DEFAULT '0',
  `finbimentreimss` tinyint(1) NOT NULL DEFAULT '0',
  `finejercicio` tinyint(1) NOT NULL DEFAULT '0',
  `autorizado` tinyint(1) NOT NULL DEFAULT '0',
  `timbrado` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`idnomp`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

  CREATE TABLE `nomi_incapacidad_secuela_consecuencia` (
  `idsecuela` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`idsecuela`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

INSERT IGNORE INTO `nomi_incapacidad_secuela_consecuencia` (`idsecuela`, `descripcion`)
VALUES
  (1, 'Incapacidad temporal'),
  (2, 'Valuacion inicial provisional'),
  (3, 'Valuacion inicial definitiva'),
  (4, 'Defuncion'),
  (5, 'Recaida'),
  (6, ' Valuacion post. a la fecha de alta'),
  (7, 'Revaluacion provisional'),
  (8, 'Recaida sin alta medica'),
  (9, 'Revaluacion definitiva'),
  (10, 'Ninguno');


CREATE TABLE `nomi_claveincidencias` (
  `idempleado` int(11) DEFAULT NULL,
  `idtipoincidencia` int(11) DEFAULT NULL,
  `idnomp` int(11) DEFAULT NULL,
  `fechaseleccion` date NOT NULL,
  `clave` varchar(11) COLLATE utf8_unicode_ci DEFAULT NULL,
  `valor` tinyint(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


-- Create syntax for TABLE 'nomi_fonacot_sobrerecibo'
CREATE TABLE `nomi_fonacot_sobrerecibo` (
  `idfonacotsobre` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `numcredito` int(11) DEFAULT NULL,
  `descripcion` varchar(100) DEFAULT NULL,
  `mes` int(11) DEFAULT NULL,
  `ejercicio` int(11) DEFAULT NULL,
  `calculoretencion` tinyint(1) DEFAULT NULL COMMENT '1-importefijo 2-proporciondias',
  `importecredito` double(100,2) DEFAULT NULL,
  `retencionmensual` double(100,2) DEFAULT NULL,
  `pagohechosotros` double(100,2) DEFAULT NULL,
  `montoacumuladoretenido` double(100,2) DEFAULT NULL,
  `saldo` double(100,2) DEFAULT NULL,
  `estatus` tinyint(1) DEFAULT NULL,
  `idEmpleado` int(11) DEFAULT NULL,
  PRIMARY KEY (`idfonacotsobre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Create syntax for TABLE 'nomi_incapacidades_sobrerecibo'
CREATE TABLE `nomi_incapacidades_sobrerecibo` (
  `idincapacidadsobre` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `folio` varchar(10) DEFAULT NULL,
  `idtipoincidencia` int(11) DEFAULT NULL,
  `diasautorizados` int(11) DEFAULT NULL,
  `fechainicio` date DEFAULT NULL,
  `ramoseguro` int(11) DEFAULT NULL COMMENT 'nomi_incapacidad',
  `tiporiesgo` int(11) DEFAULT NULL COMMENT '1-accidente, 2-enfermedad',
  `porcentajeincapacidad` double(100,2) DEFAULT NULL,
  `idsecuela` int(11) DEFAULT NULL COMMENT 'nomi_incapacidad_secuela_consecuencia',
  `idcontrol` int(11) DEFAULT NULL COMMENT 'nomi_control_incapacidad',
  `descripcion` varchar(1000) DEFAULT NULL,
  `idEmpleado` int(11) DEFAULT NULL,
  PRIMARY KEY (`idincapacidadsobre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Create syntax for TABLE 'nomi_infonavit_sobrerecibo'
CREATE TABLE `nomi_infonavit_sobrerecibo` (
  `idinfonavit` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `numinfonavit` varchar(50) DEFAULT NULL,
  `descripcion` varchar(50) DEFAULT NULL,
  `tipocredito` int(11) DEFAULT NULL,
  `importecredito` double(100,2) DEFAULT NULL COMMENT 'de acuerdo al tipo sera el importe',
  `incluirpagoseguro` tinyint(1) DEFAULT NULL,
  `fechaaplicacion` date DEFAULT NULL,
  `montoacumulado` double(100,2) DEFAULT NULL,
  `vecesaplicado` int(11) DEFAULT NULL,
  `fecharegistro` date DEFAULT NULL,
  `estatus` tinyint(1) DEFAULT NULL,
  `idEmpleado` int(11) DEFAULT NULL,
  PRIMARY KEY (`idinfonavit`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Create syntax for TABLE 'nomi_movpermanentes_sobrerecibo'
CREATE TABLE `nomi_movpermanentes_sobrerecibo` (
  `idmovper` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(100) DEFAULT NULL,
  `idtipo` int(11) DEFAULT NULL,
  `idconcepto` int(11) DEFAULT NULL,
  `fechainicio` date DEFAULT NULL,
  `importe` double(100,2) DEFAULT NULL,
  `vecesaplica` int(11) DEFAULT NULL,
  `montolimite` double(100,2) DEFAULT NULL,
  `montoacumulado` double(100,2) DEFAULT NULL,
  `fecharegistro` date DEFAULT NULL,
  `numerocontrol` int(11) DEFAULT NULL,
  `estatus` tinyint(1) DEFAULT NULL,
  `idEmpleado` int(11) DEFAULT NULL,
  PRIMARY KEY (`idmovper`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Create syntax for TABLE 'nomi_vacaciones_sobrerecibo'
CREATE TABLE `nomi_vacaciones_sobrerecibo` (
  `idvacasobrerecibo` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tipocaptura` int(1) DEFAULT NULL COMMENT '1-soloprima 2-vacaciones y prima',
  `fechainicial` date DEFAULT NULL,
  `fechafinal` date DEFAULT NULL,
  `fechapago` date DEFAULT NULL,
  `diasdescansoseptimo` int(11) DEFAULT NULL,
  `diasvacaciones` int(11) DEFAULT NULL,
  `diasvacprimavac` int(11) DEFAULT NULL COMMENT 'Prima vac. de Dias de vac.',
  `idEmpleado` int(11) DEFAULT NULL,
  PRIMARY KEY (`idvacasobrerecibo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `nomi_control_incapacidad` (
  `idcontrol` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`idcontrol`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

INSERT INTO `nomi_control_incapacidad` (`idcontrol`, `descripcion`)
VALUES
  (1, 'Unica'),
  (2, 'Inicial'),
  (3, 'Subsecuente'),
  (4, 'Alta medica o ST-2'),
  (5, 'Valuacion o ST-3'),
  (6, 'Defuncion o ST-3'),
  (7, 'Prenatal'),
  (8, 'Enlace'),
  (9, 'Postnatal'),
  (10, 'Ninguno');
CREATE TABLE `nomi_configuracion` (
  `idconfnomi` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `fechainicio` date DEFAULT NULL,
  `idregfiscal` int(11) DEFAULT NULL,
  `factordeduexent` double(100,2) DEFAULT NULL COMMENT 'factor no deducible por ingresos exentos',
  `idregistrop` int(11) DEFAULT NULL,
  `reginfonavit` varchar(50) DEFAULT NULL,
  `centrotrabajofonacot` int(11) DEFAULT NULL,
  `regss` varchar(50) DEFAULT NULL,
  `regcomisionmixta` varchar(50) DEFAULT NULL,
  `periodoanteriores` int(11) DEFAULT NULL COMMENT '1-si,0-no modificar',
  `periodosfuturos` int(11) DEFAULT NULL COMMENT '1-si,0-no modificar',
  `ptu` int(11) DEFAULT NULL,
  `aguinaldo` int(11) DEFAULT NULL,
  `primavac` int(11) DEFAULT NULL,
  `vactiempo` int(11) DEFAULT NULL,
  `calculoinvertido` int(11) DEFAULT NULL,
  `emitirsellos` int(11) DEFAULT NULL COMMENT '1-si,0-no',
  `idzona` int(11) DEFAULT NULL,
  `curp` varchar(18) DEFAULT NULL,
  `idtipop` int(11) NOT NULL,
  PRIMARY KEY (`idconfnomi`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


CREATE TABLE `nomi_conf_prenomina_default` (
  `idefault` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `idtipo` int(11) DEFAULT NULL,
  `idconcepto` int(11) DEFAULT NULL,
  `valor` tinyint(1) DEFAULT NULL,
  `importe` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`idefault`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE `nomi_conf_prenomina` (
  `idconfpre` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `idtipo` int(11) DEFAULT NULL,
  `idconcepto` int(11) DEFAULT NULL,
  `valor` tinyint(1) DEFAULT NULL,
  `importe` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`idconfpre`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


INSERT IGNORE INTO `nomi_tipoincidencias` (`idtipoincidencia`, `clave`, `nombre`, `idclasificadorincidencia`, `valorunidad`, `derechosueldo`, `pocentaje`, `disminuyeseptimo`, `idconsiderado`)
VALUES
  (1, 'ATRB', 'Accidente de trabajo', 3, 0, 0, 0, 0, 2),
  (2, 'ATRY', 'Accidente de trayecto', 2, 0, 0, 0, 0, 2),
  (3, 'CAST', 'Días de castigo', 2, 0, 0, 0, -1, 1),
  (4, 'TRAB', 'Días trabajados', 2, 0, -1, 100, 0, 4),
  (5, 'ENFG', 'Enf. Gral.Acc. Fuera trab.', 2, 0, 0, 0, -1, 2),
  (6, 'FINJ', 'Faltas injustificadas', 2, 0, 0, 0, -1, 1),
  (7, 'HE1', 'Horas extras 1', 3, 0, 0, 0, 0, 4),
  (8, 'HE2', 'Horas extras 2', 3, 0, 0, 0, 0, 1),
  (9, 'HE3', 'Horas extras 3', 3, 0, 0, 0, 0, 1),
  (10, 'HE4', 'Horas extras 4', 3, 0, 0, 0, 0, 1),
  (11, 'HE5', 'Horas extras 5', 3, 0, 0, 0, 0, 1),
  (12, 'INC', 'Incapacidad pagada por la empresa', 2, 0, 0, 0, 0, 2),
  (13, 'MAT', 'Incapacidad por maternidad', 2, 0, 0, 0, 0, 2),
  (14, 'PCS', 'Permisos con goce de sueldo', 2, 0, -1, 100, 0, 4),
  (15, 'PSS', 'Permisos sin goce de sueldo', 2, 0, 0, 0, -1, 1),
  (16, 'RET', 'Retardos', 3, 0, 0, 0, 0, 1),
  (17, 'VAC', 'Vacaciones a pagar', 2, 0, 0, 0, 0, 3);

INSERT INTO `nomi_configuracion` (`idconfnomi`, `fechainicio`, `idregfiscal`, `factordeduexent`, `idregistrop`, `reginfonavit`, `centrotrabajofonacot`, `regss`, `regcomisionmixta`, `periodoanteriores`, `periodosfuturos`, `ptu`, `aguinaldo`, `primavac`, `vactiempo`, `calculoinvertido`, `emitirsellos`, `idzona`, `curp`, `idtipop`)
VALUES
  (1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0);


DELIMITER ;;
CREATE  PROCEDURE `traerIncidencias`(idNominaPeriodo int)
begin 
  
  declare sqlv varchar (10000) default '';
  declare outerSelectClause varchar(10000) default '';
  declare innerSelectClause varchar(10000) default '';

  select  `fechainicio`,`fechafin`
    from nomi_nominasperiodo 
  where idnomp = idNominaPeriodo
  into @fechaini, @fechafin;
  
  
  set @sql='';
  
  while @fechaini <= @fechafin do
    set outerSelectClause = CONCAT( outerSelectClause,
      'GROUP_CONCAT(`', @fechaini, '` SEPARATOR \'\'  ) as \'',@fechaini, '\' ,');
    set innerSelectClause = CONCAT(innerSelectClause,
      'case when fechas like \'%',@fechaini  ,'%\'  Then clave else \'\' END as \'', @fechaini , '\'  ,');
    
    set @fechaini = date_add(@fechaini, INTERVAL 1 DAY); 
  end while;
  
  
  set outerSelectClause = SUBSTRING(outerSelectClause, 1, CHAR_LENGTH(outerSelectClause)-1);
  set innerSelectClause = SUBSTRING(innerSelectClause, 1, CHAR_LENGTH(innerSelectClause)-1);
  
  set sqlv = CONCAT('select idempleado, codigo, nombreEmpleado, apellidoPaterno, ', outerSelectClause , 
    'from (select idempleado, codigo, nombreEmpleado, apellidoPaterno, ', innerSelectClause);
  set sqlv = CONCAT(sqlv, 'from
      (
        select  ne.idempleado, IFNULL(GROUP_CONCAT(fechaseleccion SEPARATOR \',\' ), \'\') as fechas, 
          IFNULL(clave, \'\') as clave,
          codigo, nombreEmpleado, apellidoPaterno     
        from nomi_claveincidencias ci
           right join nomi_empleados ne on ci.idempleado = ne.idempleado 
        where ne.fechaAlta<=\'' , @fechafin, '\' 
          
        group by ne.idEmpleado, clave ,idnomp
      ) as tbl1
    )  
    as tbl2       group by idempleado
    ');
  
  SET @sql = CONCAT( sqlv,'') ; 
  PREPARE STMT FROM @sql;
  EXECUTE STMT;
 
  
end;;
DELIMITER ;
-- create procedure traerIncidencias(idNominaPeriodo int)  
-- begin 
--   /*Procedimiento que lee los datos de la tabla nomi_claveincidencias, los acomoda de manera que el rango de 
--   fechas del periodo los muestra como columnas, y si hay alguna clave capturada, la muestra en la columna 
--   correspondiente.
--   En base a este SP se formarà la tabla del catálogo de incidencias en el sistema.
--   parámetros: 
--     idNominaPeriodo INT: el id de la nominaperiodo a mostrar. Usado para consultar la fechainicio y fechafin 
--   */
  
--   /*Se crearàn dos clàusulas select, éstas se formarán a partir de cada fecha dentro del rango*/
--   declare sqlv varchar (10000) default '';
--   declare outerSelectClause varchar(10000) default ''; 
--   declare innerSelectClause varchar(10000) default '';

--   /*traemos la fecha inicio y fecha fin correspondientes a la nominaperiodo y se guardan en las variables
--   locales @fechaini y @fechafin*/
--   select  `fechainicio`,`fechafin`
--     from nomi_nominasperiodo 
--   where idnomp = idNominaPeriodo
--   into @fechaini, @fechafin;
  
  
--   set @sql='';
--   /*hacemos un ciclo desde fechainicio a fechafin, aumentando un día a cada iteración*/
--   while @fechaini <= @fechafin do
--     /*La idea es crear una consulta que cada columna tenga el siguiente formato
--      GROUP_CONCAT(`2017-01-01` SEPARATOR ''  ) as '2017-01-01' ,
--      GROUP_CONCAT(`2017-01-02` SEPARATOR ''  ) as '2017-01-02' ,
--     */
--     set outerSelectClause = CONCAT( outerSelectClause,
--       'GROUP_CONCAT(`', @fechaini, '` SEPARATOR \'\'  ) as \'',@fechaini, '\' ,');
      
--     /*En este caso, crear una consulta interna de esta manera
--      case when fechas like '%2017-01-01%'  Then clave else '' END as '2017-01-01'  ,
--      case when fechas like '%2017-01-02%'  Then clave else '' END as '2017-01-02'  ,
--       */  
--     set innerSelectClause = CONCAT(innerSelectClause,
--       'case when fechas like \'%',@fechaini  ,'%\'  Then clave else \'\' END as \'', @fechaini , '\'  ,');
    
--     set @fechaini = date_add(@fechaini, INTERVAL 1 DAY); 
--   end while;
  
--   /*retiramos la ultima ',' de ambas cláusulas select*/
--   set outerSelectClause = SUBSTRING(outerSelectClause, 1, CHAR_LENGTH(outerSelectClause)-1);
--   set innerSelectClause = SUBSTRING(innerSelectClause, 1, CHAR_LENGTH(innerSelectClause)-1);
  
--   /*agregamos los campos que neceistemos mas los campos creados anteriormente en el while*/
--   set sqlv = CONCAT('select idempleado, codigo, nombreEmpleado, apellidoPaterno, ', outerSelectClause , 
--     'from (select idempleado, codigo, nombreEmpleado, apellidoPaterno, ', innerSelectClause);
    
--   /*GROUP_CONCAT se encarga de unificar los valores de multiples registros de una columna, eligiendo un caracter como separador.
--   ej. si en la fila 1,  en el campo fecha = '2017-01-02' y en la fila 2 = '2017-01-03'  si usamos  GROUP_CONCAT sobre ese campo,
--   tendríamos  '2017-01-02,2017-02-03'. y agruparemos para concatenar solo las  que tengan el mismo  idempleado , clave e idnomp.
--   Esto con el fin de que en una sola fila tenga todas las fechas que se capturó una incidencia. Luego en la innerselectclause,
--   se preguntarà si determinada fecha està dentro de estos valores. Si està, mostramos la incidencia capturada.
--   ej. fecha = '2017-01-02,2017-02-03'. usamos un case when fecha like '%2017-01-01%' then clave as '2017-01-01', 
--   case when fecha like '%2017-01-02%' then clave as '2017-01-02'
--   */  
--   set sqlv = CONCAT(sqlv, 'from
--       (
--         select  ne.idempleado, IFNULL(GROUP_CONCAT(fechaseleccion SEPARATOR \',\' ), \'\') as fechas, 
--           IFNULL(clave, \'\') as clave,
--           codigo, nombreEmpleado, apellidoPaterno     
--         from nomi_claveincidencias ci
--            right join nomi_empleados ne on ci.idempleado = ne.idempleado 
--         where ne.fechaAlta<=\'' , @fechafin, '\' 
          
--         group by ne.idEmpleado, clave ,idnomp
--       ) as tbl1
--     )  
--     as tbl2       group by idempleado
--     ');
  
--   SET @sql = CONCAT( sqlv,'') ; 
--   PREPARE STMT FROM @sql;
--   EXECUTE STMT;
 
  
-- end;


INSERT INTO `catalog_estructuras` (`idestructura`, `nombreestructura`, `descripcion`, `fechacreacion`, `fechamodificacion`, `estatus`, `utilizaidorganizacion`, `linkproceso`, `columnas`, `linkprocesoantes`)
VALUES
  (450, 'nomi_tvig_isr_semanal', 'TVigISRSemanal', '2017-05-15 21:59:54', '2017-05-15 22:19:19', 'A', 0, '', 0, ''),
  (451, 'nomi_tvig_subemp_semanal', 'TVigSubEmpSemanal', '2017-05-15 23:08:16', '2017-05-15 23:10:48', 'A', 0, '', 0, '');

  INSERT INTO `catalog_campos` (`idcampo`, `idestructura`, `nombrecampo`, `nombrecampousuario`, `descripcion`, `longitud`, `tipo`, `valor`, `formula`, `requerido`, `formato`, `orden`, `llaveprimaria`)
VALUES
  (2564, 450, 'idisrsemanal', 'ID', 'ID', 11, 'auto_increment', 'NA', '', -1, '', 0, -1),
  (2565, 450, 'limite_inferior', 'Limite inferior', 'Limite inferior', 100, 'double', 'NA', '', -1, '', 1, 0),
  (2566, 450, 'limite_superior', 'Limite superior', 'Limite superior', 100, 'double', 'NA', '', -1, '', 2, 0),
  (2567, 450, 'cuotafija', 'Cuota Fija', 'Cuota Fija', 100, 'double', 'NA', '', -1, '', 3, 0),
  (2568, 450, 'porcentaje', 'Porcentaje', 'Porcentaje', 100, 'double', 'NA', '', -1, '', 4, 0);
INSERT INTO `catalog_campos` (`idcampo`, `idestructura`, `nombrecampo`, `nombrecampousuario`, `descripcion`, `longitud`, `tipo`, `valor`, `formula`, `requerido`, `formato`, `orden`, `llaveprimaria`)
VALUES
  (2572, 451, 'idempsemanal', 'ID', 'ID', 11, 'auto_increment', 'NA', '', -1, '', 0, -1),
  (2573, 451, 'limite_inferior', 'Limite inferior', 'Limite inferior', 1000, 'double', 'NA', '', -1, '', 1, 0),
  (2574, 451, 'limite_superior', 'Limite superior', 'Limite superior', 100, 'double', 'NA', '', -1, '', 2, 0),
  (2575, 451, 'subsalempleo', 'Subsidio al empleado', 'Subsidio al empleado', 100, 'double', 'NA', '', -1, '', 3, 0);


CREATE TABLE `nomi_tvig_isr_semanal` (
  `idisrsemanal` int(11) NOT NULL AUTO_INCREMENT,
  `limite_inferior` double(100,2) NOT NULL,
  `limite_superior` double(100,2) NOT NULL,
  `cuotafija` double(100,2) NOT NULL,
  `porcentaje` double(100,2) NOT NULL,
  PRIMARY KEY (`idisrsemanal`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `nomi_historial_empleado` (
  `idhistorial` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `idEmpleado` int(11) DEFAULT NULL,
  `tipo` int(11) DEFAULT NULL COMMENT '1-alta,2,baja,3-reingreso',
  `fecha` date DEFAULT NULL,
  PRIMARY KEY (`idhistorial`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8;

INSERT INTO `nomi_tvig_isr_semanal` (`idisrsemanal`, `limite_inferior`, `limite_superior`, `cuotafija`, `porcentaje`)
VALUES
  (1, 0.01, 114.24, 0.00, 1.92),
  (2, 114.25, 969.50, 2.17, 6.40),
  (3, 969.51, 1703.80, 56.91, 10.88),
  (4, 1703.81, 1980.58, 136.85, 16.00),
  (5, 1980.59, 2371.32, 181.09, 17.92),
  (6, 2371.33, 4782.61, 251.16, 21.36),
  (7, 4782.62, 7538.09, 766.15, 23.52),
  (8, 7538.10, 14391.44, 1414.28, 30.00),
  (9, 14391.45, 19188.61, 3470.25, 32.00),
  (10, 19188.62, 57565.76, 5005.35, 34.00),
  (11, 57565.77, 0.00, 18053.63, 35.00);
CREATE TABLE `nomi_tvig_subemp_semanal` (
  `idempsemanal` int(11) NOT NULL AUTO_INCREMENT,
  `limite_inferior` double(100,2) NOT NULL,
  `limite_superior` double(100,2) NOT NULL,
  `subsalempleo` double(100,2) NOT NULL,
  PRIMARY KEY (`idempsemanal`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
INSERT INTO `nomi_tvig_subemp_semanal` (`idempsemanal`, `limite_inferior`, `limite_superior`, `subsalempleo`)
VALUES
  (1, 0.01, 407.33, 93.73),
  (2, 407.34, 610.96, 93.66),
  (3, 610.97, 799.68, 93.66),
  (4, 799.69, 814.66, 90.44),
  (5, 814.67, 1023.75, 88.06),
  (6, 1023.76, 1086.19, 81.55),
  (7, 1086.20, 1228.57, 74.83),
  (8, 1228.58, 1433.32, 67.83),
  (9, 1433.33, 1638.07, 58.38),
  (10, 1638.08, 1699.88, 50.12),
  (11, 1699.89, 0.00, 0.00);




CREATE TABLE `nomi_registro_entradas` (
  `idregistro` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `horaentrada` time DEFAULT NULL,
  `iniciocomida` time DEFAULT NULL,
  `fincomida` time DEFAULT NULL,
  `horasalida` time DEFAULT NULL,
  `idEmpleado` int(11) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `dia` char(3) DEFAULT NULL,
  `idnomp` int(11) DEFAULT NULL COMMENT 'id de nomi_nominasperiodo',
  PRIMARY KEY (`idregistro`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


/*EMPLEADOS HUELLA*/
CREATE TABLE `nomi_empleadoReHuella` (
  `idregistrohuella` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `idEmpleado` int(11) DEFAULT NULL,
  `huella` blob,
  PRIMARY KEY (`idregistrohuella`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DELIMITER ;;
CREATE  PROCEDURE `usp_InsertarRegistroHuella`(
   _idEmpleado  int 
  ,_Fecha       date
  ,_Dia         varchar(5)
  ,_Hora        time 
  ,OUT _Mensaje varchar(100)
)
Begin
  DECLARE _horaFinComida time default null;
  DECLARE _horaIniComida time default null;
  DECLARE _idRegistro int default null;
  DECLARE _idNomP int default null;
  DECLARE _horaEntrada time default null;
  DECLARE _rangoTolerancia time default '00:10:00';
  
  
  
  SELECT idnomp  
     INTO _idNomP
   FROM nomi_nominasperiodo
     WHERE autorizado='1' limit 1;

  SELECT idregistro, iniciocomida, fincomida, horaentrada
    INTO _idRegistro, _horaIniComida, _horaFinComida, _horaEntrada   
  FROM nomi_registro_entradas
  WHERE idEmpleado = _idEmpleado
    AND fecha      = _Fecha
  ORDER BY idRegistro DESC LIMIT 1;
  
  IF CAST(_Hora - _horaEntrada as time) < _rangoTolerancia THEN
    SET _Mensaje = CONCAT('YA MARCO ->' ,_Hora,'<->',_horaEntrada,'<->',CAST(_Hora - _horaEntrada as time))   ;
  ELSE  
      IF _idRegistro IS NULL THEN
        INSERT INTO 
          nomi_registro_entradas ( horaentrada,  iniciocomida, fincomida,horasalida,idEmpleado, fecha, dia, idnomp)
        VALUES(_Hora, null, null, null, _idEmpleado, _Fecha, _Dia, _idNomP);
      ELSEIF _Dia ='Vie' THEN
        UPDATE nomi_registro_entradas
        SET horasalida   = _Hora
        WHERE idEmpleado = _idEmpleado
          AND fecha      = _Fecha;
      ELSEIF _Hora BETWEEN CAST('14:00:00' as time) AND CAST('17:00:00' as time) THEN
        IF _horaIniComida IS NULL THEN
          UPDATE nomi_registro_entradas
          SET iniciocomida = _Hora
          WHERE idEmpleado = _idEmpleado
            AND fecha      = _Fecha;
        ELSE
          UPDATE nomi_registro_entradas
          SET   fincomida  = _Hora
          WHERE idEmpleado = _idEmpleado
            AND fecha      = _Fecha;
        END IF;
      ELSE 
          UPDATE nomi_registro_entradas
          SET   horasalida  = _Hora
          WHERE idEmpleado = _idEmpleado
            AND fecha      = _Fecha;
      END IF;
  END IF; 
End;;
DELIMITER ;




ALTER TABLE nomi_empleados
ADD imagen blob DEFAULT NULL,
ADD alimento double(100,2) DEFAULT NULL; 

CREATE TABLE `nomi_uma` (
  `iduma` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `vigencia` date DEFAULT NULL,
  `valor` double(100,2) DEFAULT NULL,
  `periodo` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`iduma`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

INSERT INTO `nomi_uma` (`iduma`, `vigencia`, `valor`, `periodo`)
VALUES
  (1, '2017-02-01', 75.49, 'diario');

CREATE TABLE `nomi_politicas` (
  `idpoliticas` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `jornada` double DEFAULT NULL,
  `tolerancia` int(11) DEFAULT NULL,
  `minhoraentrada` time DEFAULT NULL,
  `maxhoraentrada` time DEFAULT NULL,
  `diastrabajados` int(11) DEFAULT NULL COMMENT 'ejemp.si es semanal (7) y solo van lun - vier (5) deberan registrar el 5',
  `idperiodicidad` int(11) DEFAULT NULL,
  PRIMARY KEY (`idpoliticas`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
INSERT INTO `catalog_estructuras` (`idestructura`, `nombreestructura`, `descripcion`, `fechacreacion`, `fechamodificacion`, `estatus`, `utilizaidorganizacion`, `linkproceso`, `columnas`, `linkprocesoantes`)
VALUES
  (452, 'nomi_riesgotrabajo', 'Riesgo trabajo', '2017-06-01 21:37:25', '2017-06-01 21:37:25', 'G', 0, '', 0, '');
  
  INSERT INTO `catalog_campos` (`idcampo`, `idestructura`, `nombrecampo`, `nombrecampousuario`, `descripcion`, `longitud`, `tipo`, `valor`, `formula`, `requerido`, `formato`, `orden`, `llaveprimaria`)
VALUES
  (2576, 452, 'idclaveriesgotrabajo', 'ID', 'ID', 11, 'auto_increment', 'NA', '', 0, '', 0, -1),
  (2577, 452, 'descripcion', 'Descripcion', 'Descripcion', 20, 'varchar', 'NA', '', 0, '', 1, 0);


INSERT INTO `catalog_estructuras` (`idestructura`, `nombreestructura`, `descripcion`, `fechacreacion`, `fechamodificacion`, `estatus`, `utilizaidorganizacion`, `linkproceso`, `columnas`, `linkprocesoantes`)
VALUES
  (453, 'nomi_politicas', 'Politicas de la Empresa', '2017-07-07 15:19:04', '2017-07-07 15:24:59', 'A', 0, '', 0, '');
INSERT INTO `catalog_campos` (`idcampo`, `idestructura`, `nombrecampo`, `nombrecampousuario`, `descripcion`, `longitud`, `tipo`, `valor`, `formula`, `requerido`, `formato`, `orden`, `llaveprimaria`)
VALUES
  (2578, 413, 'idclaveriesgopuesto', 'Riesgo puesto', 'Riesgo puesto', 11, 'int', 'NA', '', 0, '-1', 3, 0),
  (2579, 453, 'idpoliticas', 'ID', 'ID', 11, 'auto_increment', 'NA', '', 0, '', 0, -1),
  (2580, 453, 'jornada', 'Jornada', 'Jornada', 0, 'double', 'NA', '', 0, '', 1, 0),
  (2581, 453, 'tolerancia', 'Tolerancia', 'Tolerancia de minutos para llegar tarde', 11, 'int', 'NA', '', 0, '', 2, 0),
  (2582, 453, 'minhoraentrada', 'Hora minima de entrada', 'Hora desde que el empleado puede ingresar', 0, 'time', 'NA', '', 0, '', 3, 0),
  (2583, 453, 'maxhoraentrada', 'Hora maxima de entrada(incluyendo tiempo extra)', 'Maxima hora de llegada del empleado incluyendo tiempo extra', 0, 'time', 'NA', '', 0, '', 4, 0),
  (2584, 453, 'diastrabajados', 'Dias trabajados', 'Dias que el empleado debe presentarse a trabajar en un periodo', 11, 'int', 'NA', '', 0, '', 5, 0),
  (2585, 453, 'idperiodicidad', 'Periodicidad en la que aplican las politicas', 'Periodicidad en la que aplican las politicas', 11, 'int', 'NA', '', 0, '', 6, 0);
;

INSERT INTO `catalog_dependencias` (`idcampo`, `tipodependencia`, `dependenciatabla`, `dependenciacampovalor`, `dependenciacampodescripcion`)
VALUES
  (2585, 'S', 'nomi_periodicidad', 'idperiodicidad', ' descripcion ');

CREATE TABLE `nomi_calculo_prenomina` (
  `idcal` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `idEmpleado` int(11) DEFAULT NULL,
  `idnomp` int(11) DEFAULT NULL,
  `idconfpre` int(11) DEFAULT NULL COMMENT 'id en nomi|_conf_prenomina',
  `importe` double(100,2) DEFAULT NULL,
  `gravado` double(100,2) DEFAULT NULL,
  `exento` double(100,2) DEFAULT NULL,
  `idNominatimbre` int(11) NOT NULL DEFAULT '0' COMMENT 'ref a tabla nomi_prenomina',
  `diaspagados` double(100,2) DEFAULT NULL,
  `diaslaborados` int(11) DEFAULT NULL,
  `valordias` int(11) DEFAULT NULL COMMENT 'se refiere a los dias corespondientes ejem vac valor 2 dos dias tomados',
  `aplicarecibo` int(11) NOT NULL DEFAULT '1' COMMENT '1 -si 0-no',
  `origen` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0-prenomina,1-aguinaldo,2-finiquito,3-ptu',
  `fechabaja` date DEFAULT NULL,
  `autorizado` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`idcal`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE `nomi_rel_concep_causa_finiquito` (
  `idrelconcau` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `idcausaf` int(11) DEFAULT NULL,
  `idconf` int(11) DEFAULT NULL,
  PRIMARY KEY (`idrelconcau`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

INSERT INTO `nomi_rel_concep_causa_finiquito` (`idrelconcau`, `idcausaf`, `idconf`)
VALUES
  (1, 1, 1),
  (2, 1, 2),
  (3, 1, 3),
  (4, 1, 4),
  (5, 1, 5),
  (6, 1, 8),
  (7, 2, 1),
  (8, 2, 2),
  (9, 2, 3),
  (10, 2, 4),
  (11, 2, 5),
  (12, 2, 6),
  (13, 2, 7),
  (14, 2, 8),
  (15, 3, 1),
  (16, 4, 1),
  (17, 4, 2),
  (18, 4, 3),
  (19, 4, 4),
  (20, 4, 5),
  (21, 4, 6),
  (22, 4, 7),
  (23, 4, 8),
  (24, 5, 1),
  (25, 5, 2),
  (26, 5, 3),
  (27, 5, 4),
  (28, 5, 5),
  (29, 5, 6),
  (30, 5, 7),
  (31, 5, 8),
  (32, 6, 1),
  (33, 6, 2),
  (34, 6, 3),
  (35, 6, 4),
  (36, 6, 5),
  (37, 6, 6),
  (38, 6, 7),
  (39, 6, 8),
  (40, 7, 1),
  (41, 8, 1),
  (42, 8, 2),
  (43, 8, 3),
  (44, 8, 4),
  (45, 8, 5),
  (46, 8, 6),
  (47, 8, 7),
  (48, 8, 8),
  (49, 9, 1),
  (50, 9, 2),
  (51, 9, 3),
  (52, 9, 4),
  (53, 9, 5),
  (54, 9, 6),
  (55, 9, 7),
  (56, 9, 8),
  (57, 10, 1),
  (58, 10, 2),
  (59, 10, 3),
  (60, 10, 4),
  (61, 10, 5),
  (62, 10, 6),
  (63, 10, 7),
  (64, 10, 8),
  (65, 11, 1),
  (66, 11, 2),
  (67, 11, 3),
  (68, 11, 4),
  (69, 11, 5),
  (70, 11, 6),
  (71, 11, 7),
  (72, 11, 8);

CREATE TABLE `nomi_causa_finiquito` (
  `idcausaf` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`idcausaf`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
INSERT INTO `nomi_causa_finiquito` (`idcausaf`, `nombre`)
VALUES
  (1, 'Separacion Voluntaria'),
  (2, 'Termino de contrato'),
  (3, 'Abandono de empleo'),
  (4, 'Defuncion'),
  (5, 'Clausura'),
  (6, 'Otra'),
  (7, 'Ausentismo'),
  (8, 'Rescision de contrato'),
  (9, 'Jubilacion'),
  (10, 'Pension'),
  (11, 'Incapacidad');

-- C A L C U L O   P T U
DELIMITER ;;
CREATE DEFINER=`nmdevel`@`%` PROCEDURE `calculoptu`(montoARepartir  decimal, descontarIncidencias bit, guardarPTU bit,ejercicio bit,ptuselect int)
begin 
  /*declare sqlv varchar (10000) default '';*/
  CREATE TEMPORARY TABLE IF NOT EXISTS tableDatos AS (
    Select 
    fechaInicioAnioAnterior,
    fechaFinAnioAnterior,
    fechaAlta,
    descripcionstatus, 
    activo,
    (year(curdate())-1) as ejecalculado,
    (year(curdate())) as ejepago,
    Case 
      When descontarIncidencias = 0 Then diasTrabajados
       Else diasTrabajados - CantidadIncidencias
       End as diasTrabajados,
    Salario, nuevoSalario,salario2,
    Case
    When descontarIncidencias =0 Then diasTrabajados * Salario 
      Else (diasTrabajados - CantidadIncidencias) * Salario
    End as totalPercibido, 
    idEmpleado,
    idtipop,
    Codigo,
    idnomp,
    numnomina,
    autorizado,
    nombreEmpleado,
    apellidoPaterno,
    apellidoMaterno,
    iduma, vigencia, valor, periodo, parteexenta,mensual,ptuselect,
    cast(0 as decimal(28,10)) as totalDiasTrabajados,
    cast(0 as decimal(28,10)) as totalSalarioPercibido,
    cast(0 as decimal(28,10)) as factorDiasTrabajados,
    cast(0 as decimal(28,10)) as factorSalarioPercibido,
    cast(0 as decimal(28,10)) as ptuDiasTrabajados,
    cast(0 as decimal(28,10)) as ptuPorSalarios,
    cast(0 as decimal(28,10)) as gravado,
    cast(0 as decimal(28,10)) as subtotalPTU,
    cast(0 as decimal(28,10)) as totalGravado,
    cast(0 as decimal(28,10)) as isr_gravado, 
    cast(0 as decimal(28,10)) as isr_mensual,
    cast(0 as decimal(28,10)) as totalPTU,
    cast(0 as decimal(28,10)) as isrmensualsub,
    cast(0 as decimal(28,10)) as isrgravadosub,
    cast(0 as decimal(28,10)) as totalPTUresta
    
    From (
      Select 
      Case 
       When descripcionEstatus in ('Alta', 'Reingreso' ) Then
      Case 
       When fecha_altabajareingreso < fechaInicioAnioAnterior Then DATEDIFF (fechaFinAnioAnterior,fechaInicioAnioAnterior) +1
      Else DATEDIFF (fechaFinAnioAnterior, fecha_altabajareingreso) +1 
        End 
       When descripcionEstatus = 'Baja' Then
      Case 
        When fecha_altabajareingreso > fechaFinAnioAnterior Then DATEDIFF ( fechaFinAnioAnterior, fechaInicioAnioAnterior)
      Else DATEDIFF (fecha_altabajareingreso, fechaInicioAnioAnterior)
        End 
        End as diasTrabajados,
      datosEmpleados.*
      From ( 
        Select    histSal.idEmpleado as empleadoEnHistorico ,  ne.idEmpleado as empeadoEnEmpleados   ,
        makedate(year(curdate())-1 , 01) as fechaInicioAnioAnterior,
        str_to_date(concat(year(curdate())-1,'-12-31') , '%Y-%m-%d') as fechaFinAnioAnterior,  
        fechaAlta,
        ne.activo,
        Case ne.activo  
        When -1 then ne.fechaAlta 
        Else he.`fecha` 
        End as fecha_altabajareingreso,
        Case ne.activo
        When -1 then 'Alta'
        When 2  then 'Baja'
        When 3  then 'Reingreso'
        End as descripcionEstatus, 
        Case ne.activo  
        When -1 then ne.fechaAlta 
        Else he.`fecha` 
        End as fecha_altabajareingresodos,
        Case ne.activo
        When -1 then 'Activo'  
        When  2 then he.`fecha`
        /*  When 3  then 'Reingreso' */
        End as descripcionstatus, 
        ne.idEmpleado,ne.idtipop,p.idnomp,p.autorizado,p.numnomina,codigo,ne.nombreEmpleado,apellidoPaterno,apellidoMaterno,
    IFNULL(histSal.nuevoSalario, ne.Salario) as Salario,
    histSal.nuevoSalario, ne.Salario as salario2,
        IFNULL(histSal.nuevoSalario, ne.Salario)  * 30.4 as mensual,
        ifnull(Incidencias.CantidadIncidencias,0) as CantidadIncidencias,
        iduma, vigencia, valor, periodo, parteexenta
        From nomi_empleados ne
        Left join nomi_historial_empleado he
        On  he.idEmpleado=ne.idEmpleado 
         And he.tipo =ne.activo  
         inner join nomi_nominasperiodo p
         on ne.idtipop=p.idtipop
        /*left join nomi_historico_salarios s
    on  s.idEmpleado =  ne.idEmpleado */  /*--Agreee con lo del aumento de salario--*/
    left join (
      Select s.idEmpleado,nuevoSalario,s.fechaAplicacion 
      From
      nomi_historico_salarios s 
      Inner join (
        Select idEmpleado, max(fechaAplicacion) as maxFechaAplicacion 
        From nomi_historico_salarios 
        Where Year(fechaAplicacion ) <=  year(curdate())-1   group by idEmpleado) s2
      on s.idEmpleado = s2.idEmpleado and s.fechaAplicacion = s2.maxFechaAplicacion
      ) as histSal  
    on ne.idEmpleado = histSal.idEmpleado               
        Left Join (
          Select Count(1) as CantidadIncidencias,
          idempleado
          From nomi_claveincidencias  ci
          Inner Join nomi_tipoincidencias ti
          on ci.idtipoincidencia = ti.idtipoincidencia
          Where ci.autorizado =1 
          And ti.idconsiderado not in (2,3)
          And year(ci.fechaseleccion) = (year(curdate()) -1)
        ) as Incidencias
        On Incidencias.idEmpleado = ne.idempleado
        Left Join (
          Select iduma, vigencia, valor, periodo, valor * 15 as parteexenta
          From nomi_uma 
          Where year(vigencia) = year(CURDATE())
        ) as uma 
        On (1=1) 
      ) as datosEmpleados
    ) as tabladias
  
    where diasTrabajados > 60 and autorizado=0  group by nombreEmpleado);
    
    select  sum(diasTrabajados),
    sum(totalPercibido) 
    from tableDatos   
      into @sumDiasTrabajados, @sumTotalPercibido;
      Update tableDatos 
        Set factorDiasTrabajados   = (montoARepartir / 2) / @sumDiasTrabajados,
    factorSalarioPercibido = (montoARepartir / 2) / @sumTotalPercibido,
    totalSalarioPercibido  = @sumTotalPercibido,
    totalDiasTrabajados    = cast(@sumDiasTrabajados as decimal(18,2));
      Update tableDatos 
         Set ptuDiasTrabajados      = (diasTrabajados * factorDiasTrabajados  ),
    ptuPorSalarios    = (totalPercibido * factorSalarioPercibido);
       Update tableDatos 
         Set gravado    = Case 
      When (ptuDiasTrabajados + ptuPorSalarios) < parteexenta Then (ptuDiasTrabajados + ptuPorSalarios) 
        Else (ptuDiasTrabajados + ptuPorSalarios) - parteexenta 
     End,
    subtotalPTU   = ptuDiasTrabajados + ptuPorSalarios;
    
    Update tableDatos
    Set totalGravado   = gravado + mensual;
    update tableDatos td, nomi_tvig_isr_mensual im
      set td.isr_gravado = ((totalgravado - limite_inferior) * (porcentaje /100) + cuotafija)
      WHERE
        (td.totalGravado between im.limite_inferior and im.limite_superior ) OR 
        (td.totalGravado >= im.limite_inferior and im.`limite_superior` is null);
      
  
      Update tableDatos
      Set mensual   =   Salario * 30.4;
       update tableDatos td, nomi_tvig_isr_mensual im
      set td.isr_mensual = ((mensual - limite_inferior) * (porcentaje /100) + cuotafija)
    WHERE
      (td.mensual between im.limite_inferior and im.limite_superior) OR 
        (td.mensual >= im.limite_inferior and im.`limite_superior` is null);

      
   UPDATE  tableDatos td, nomi_tvig_subemp_mensual sm SET td.isrmensualsub = CASE
      WHEN (td.isr_mensual> sm.subsalempleo) THEN (td.isr_mensual-sm.subsalempleo)
      WHEN (sm.subsalempleo > td.isr_mensual) THEN (sm.subsalempleo-td.isr_mensual)
     END
 WHERE td.mensual between sm.limite_inferior and sm.limite_superior OR
 (td.mensual >= sm.limite_inferior and sm.`limite_superior` is null);
 

  UPDATE  tableDatos td, nomi_tvig_subemp_mensual sm SET td.isrgravadosub = CASE
      WHEN (td.isr_gravado> sm.subsalempleo) THEN (td.isr_gravado-sm.subsalempleo)
        WHEN (sm.subsalempleo > td.isr_gravado) THEN (sm.subsalempleo-td.isr_gravado)
       END  
      WHERE (td.totalGravado between sm.limite_inferior and sm.limite_superior) OR 
        (td.totalGravado >= sm.limite_inferior and sm.`limite_superior` is null);
    
      update tableDatos
        set totalPTUresta = isr_gravado - isr_mensual;
        
        update tableDatos
          set totalPTU = subtotalPTU - totalPTUresta;
     
        if guardarPTU = 0 then
    
            Select * from tableDatos;
            
            DROP TEMPORARY TABLE IF EXISTS tableDatos;  
            
                   else 
                  if ejercicio=1 then 
                update nomi_almacena_ptu  set status=2 where status=3  and ejercicio_pago= (year(curdate())-1);
            end if; 

      
          delete 
          from nomi_calculo_prenomina 
          where idptu in (
              Select idptu from nomi_almacena_ptu
              where ejercicio_calculado = year(curdate())-1
            )
            and origen=3;
          delete 
          from nomi_almacena_ptu
          where ejercicio_calculado = year(curdate())-1;
     

 IF EXISTS(SELECT 1  FROM nomi_tiposdeperiodos WHERE  YEAR (fechainicio) = YEAR (CURDATE()) AND extraordinario=1) THEN

            INSERT INTO   
              nomi_almacena_ptu(cantidad_a_repartir, idEmpleado, dias_trabajados, salario, factor_por_dias, factorpor_salario, subtotal, isr, total_importe, status, fechabaja,                   ejercicio_pago, ejercicio_calculado)
            select  montoARepartir,idEmpleado,diasTrabajados,salario,factorDiasTrabajados,factorSalarioPercibido,subtotalPTU,totalPTUresta,totalPTU,
              case descripcionstatus when 'Activo' then 1 else 3 end, descripcionstatus,ejepago,ejecalculado
              from tableDatos;
              
              
         insert nomi_calculo_prenomina(idEmpleado,idnomp,idconfpre,importe,gravado,exento,idNominatimbre,diaspagados,diaslaborados,valordias,aplicarecibo,origen,autorizado,idptu)
            select distinct td.idEmpleado,(select idnomp from nomi_nominasperiodo where idtipop=3),(select ptu from nomi_configuracion),td.totalPTU, td.gravado, td.parteexenta, 0, td.diasTrabajados,0,0,1,3,0,ptu.idptu /*ID DE ALMACENAPTU*/
              from tableDatos td, nomi_almacena_ptu ptu 
              where td.descripcionstatus='Activo'
                and td.idempleado   = ptu.idEmpleado
                and td.diasTrabajados = ptu.dias_trabajados
                and td.salario      = ptu.salario
                and td.factorDiasTrabajados   = ptu.factor_por_dias
                and td.factorSalarioPercibido = ptu.factorpor_salario
                and td.subtotalPTU    = ptu.subtotal
                and td.totalPTUresta  = ptu.isr
                and td.totalPTU     = ptu.total_importe
                and td.ejepago      = ptu.ejercicio_pago
                and td.ejecalculado   = ptu.ejercicio_calculado;  

        end if;
        END IF;
                DROP TEMPORARY TABLE IF EXISTS tableDatos;  
    end;;
DELIMITER ;


/**/
drop PROCEDURE calculoptu;
DELIMITER ;;
CREATE PROCEDURE `almacenaIncidencia`(prmtipoIncidencia int, prmclave varchar(10), fecha date,valor int, prmidnomp int,prmidempleado int)
begin
  declare fechaini date ; 
  declare i int default 0;
  declare sqlv varchar (1000) default '';
  declare existenregistros int;
  
  set fechaini = fecha;
  
  select  `idclasificadorIncidencia`,`idconsiderado`
    from nomi_tipoincidencias
  where idtipoincidencia = prmtipoIncidencia
  into @clasificadorIncidencia, @idconsiderado; 
  
  select `fechafin` 
  from nomi_nominasperiodo
  where idnomp=prmidnomp
  into @fechafin;
  
  if prmtipoincidencia =16 then       /*si la incidencia es retardo borra aquellas del mismo dia que no sean horas extra*/
      delete from nomi_claveincidencias 
      where `idempleado`= prmidempleado   
        and `fechaseleccion` = fecha
        and `idnomp`= prmidnomp 
        and idtipoincidencia not in (7,8,9,10,11);
        
  elseif prmtipoincidencia in (7,8,9,10,11) then    /*si la incidencia a guardar es hora extra, borramos aquellas ya guardads que no seah horas extra,*/
      delete from nomi_claveincidencias       /*retardos, o que sea la misma incidencia que la capturada*/
      where `idempleado`=prmidempleado 
        and `fechaseleccion` = fecha
        and `idnomp`= prmidnomp 
        and (idtipoincidencia not in (7,8,9,10,11,16) 
           or idtipoincidencia =prmtipoincidencia)  ;     
  else
      delete from nomi_claveincidencias   /*si la incidencia no es retardo ni hora extra borra aquellas ya guardadas del mismo dia*/
      where `idempleado`= prmidempleado 
        and `fechaseleccion` = fecha
        and `idnomp`= prmidnomp ;
  end if;
    
  set sqlv = 'insert into nomi_claveincidencias (idempleado, idtipoincidencia, idnomp, fechaseleccion, clave, valor) values ';
  
  if @clasificadorIncidencia = 2 then     /*si clasificadorincidencia es por dias, lo repartimos entre dias*/
    set i=0;
    while i < valor do  /*hacemos un ciclo para insertar tantos registros como se indiquen en la variable valor*/
      set existenregistros=(
        SELECT count(1)
          FROM nomi_claveincidencias 
        where `idempleado`=prmidempleado and `fechaseleccion` = fechaini
          and `idnomp`=prmidnomp
      );
             
      if existenregistros = 0 or i=0 then /*solo guardamos en caso de que no haya algo previamente capturado en ese dia*/
        set sqlv = concat(sqlv , '(', prmidempleado ,',',prmtipoIncidencia,',',prmidnomp,',\'',fechaini,'\',\'',prmclave,'\',1),');
      end if;
      
      set i = i+1;
      set fechaini = date_add(fechaini, INTERVAL 1 DAY);    /*incrementamos la fecha cada vez que guardamos un dia*/
      
      if @idconsiderado=2  and fechaini > @fechafin then /*si idconsiderado es 2 y la fecha es mayor a la fecha final del periodo,*/
        set prmidnomp = prmidnomp + 1;           /*incrementamos*/
        select `fechafin` 
        from nomi_nominasperiodo
        where idnomp=prmidnomp
        into @fechafin;
      elseif  @idconsiderado!=2 and fechaini >@fechafin then /*solo los idconsiderado=2 pueden brincar al sig. periodo*/
        set i=valor;    /*hacemos i=valor para que ya se salga del ciclo*/
      end if;
       
    end while;
    set sqlv = SUBSTRING(sqlv, 1, CHAR_LENGTH(sqlv)-1);
      
  elseif @clasificadorIncidencia = 3 then
        set sqlv = concat(sqlv , '(', prmidempleado ,',',prmtipoIncidencia,',',prmidnomp,',\'',fecha,'\',\'',prmclave,'\',',valor,')');
  end if  ;
  
  SET @sql = CONCAT( sqlv,'') ; 
  PREPARE STMT FROM @sql;
  EXECUTE STMT;
end;;
DELIMITER ;


/**/
drop PROCEDURE traerDatosCalculoPrenomina;
DELIMITER ;;
CREATE  PROCEDURE `traerDatosCalculoPrenomina`(
      filtroPeriodo varchar(100)
      ,filtroNomina varchar(100)
      ,filtroEmpleado varchar(100)
  )
BEGIN
    /*declare sqlv varchar(10000) default '';*/
    declare sqlFiltroPeriodo varchar(100) default '';
    declare sqlFiltroNomina varchar(100) default '';
    declare sqlFiltroEmpleado varchar(100) default '';
    
    if filtroPeriodo != '*' then
      set sqlFiltroPeriodo = concat(' and np.idtipop = ', filtroPeriodo);
    end if;
    
    if filtroNomina !='*' then
      set sqlFiltroNomina = concat(' and cap.idnomp = ', filtroNomina);
    end if;
    
    if filtroEmpleado !='*' then
      set sqlFiltroEmpleado = concat(' and em.idEmpleado = ', filtroEmpleado);
    end if;
    
    
    set @sql = '';
    set @sql :=
    (select   
      concat('select idEmpleado, codigo, empleado, ',  
        group_concat(outerselect SEPARATOR ',' ), ' From  (',
        'select cap.idEmpleado, em.codigo, CONCAT(em.apellidoPaterno, \' \', em.apellidoMaterno, \' \', em.nombreEmpleado) as empleado, ', 
          group_concat(innerselect separator ','), 
        ' From nomi_conf_prenomina cp Inner Join nomi_calculo_prenomina cap on cap.idConfpre = cp.idConcepto
          Inner Join nomi_conceptos c on cp.idConcepto =c.idConcepto 
          Inner join nomi_empleados em
          on em.idEmpleado=cap.idEmpleado
          Inner Join nomi_nominasperiodo np
            on np.idnomp = cap.idnomp
          Where (1=1) ', sqlFiltroPeriodo, sqlFiltroNomina, sqlFiltroEmpleado  ,' 
          ) as datos group by idEmpleado' )
    from (
      select concat('case c.idconcepto when ' , cp.idConcepto , ' then cap.importe else null end as  CONCEPTO_' , cp.idConcepto ) as innerselect
          ,concat('ifnull(group_concat(CONCEPTO_'  , cp.idconcepto , ' SEPARATOR \'\'),0) as CONCEPTO_',cp.idConcepto) as outerselect
          From nomi_conf_prenomina cp 
          Inner Join nomi_calculo_prenomina cap on cap.idConfpre = cp.idConcepto
          Inner Join nomi_conceptos c on cp.idConcepto =c.idConcepto
        Group by cp.idconcepto, c.Descripcion
        Order by cp.idConcepto
    ) as b
    );
    /*select  @sql as sqltmt;*/
    /*set @sql = concat(sqlv,'');*/
    PREPARE STMT FROM @sql;
    EXECUTE STMT;
  END;;
DELIMITER ;


/**/
drop PROCEDURE almacenaIncidenciasobrerecibo;

DELIMITER ;;
CREATE PROCEDURE `almacenaIncidenciasobrerecibo`(prmtipoIncidencia int, prmclave varchar(10), fecha date,valor int, prmidnomp int,prmidempleado int)
begin
  declare fechaini date ; 
  declare i int default 0;
  declare sqlv varchar (1000) default '';
  declare existenregistros int;
  
  set fechaini = fecha;
  
  select  `idclasificadorIncidencia`,`idconsiderado`
    from nomi_tipoincidencias
  where idtipoincidencia = prmtipoIncidencia
  into @clasificadorIncidencia, @idconsiderado; 
  
  select `fechafin` 
  from nomi_nominasperiodo
  where idnomp=prmidnomp
  into @fechafin;
  
  if prmtipoincidencia =16 then       /*si la incidencia es retardo borra aquellas del mismo dia que no sean horas extra*/
      delete from nomi_incapacidades_sobrerecibo 
      where `idempleado`= prmidempleado   
        and `fechaseleccion` = fecha
        and `idnomp`= prmidnomp 
        and idtipoincidencia not in (7,8,9,10,11);
        
  elseif prmtipoincidencia in (7,8,9,10,11) then    /*si la incidencia a guardar es hora extra, borramos aquellas ya guardads que no seah horas extra,*/
      delete from nomi_incapacidades_sobrerecibo      /*retardos, o que sea la misma incidencia que la capturada*/
      where `idempleado`=prmidempleado 
        and `fechaseleccion` = fecha
        and `idnomp`= prmidnomp 
        and (idtipoincidencia not in (7,8,9,10,11,16) 
           or idtipoincidencia =prmtipoincidencia)  ;     
  else
      delete from nomi_incapacidades_sobrerecibo  /*si la incidencia no es retardo ni hora extra borra aquellas ya guardadas del mismo dia*/
      where `idempleado`= prmidempleado 
        and `fechaseleccion` = fecha
        and `idnomp`= prmidnomp ;
  end if;
    
  set sqlv = 'INSERT INTO nomi_incapacidades_sobrerecibo
    //      (folio, idtipoincidencia, diasautorizados, fechainicio, ramoseguro, tiporiesgo, porcentajeincapacidad, idsecuela, idcontrol, descripcion, idEmpleado,idnomp) values ';
  
  if @clasificadorIncidencia = 2 then     /*si clasificadorincidencia es por dias, lo repartimos entre dias*/
    set i=0;
    while i < valor do  /*hacemos un ciclo para insertar tantos registros como se indiquen en la variable valor*/
      set existenregistros=(
        SELECT count(1)
          FROM nomi_incapacidades_sobrerecibo 
        where `idempleado`=prmidempleado and `fechaseleccion` = fechaini
          and `idnomp`=prmidnomp
      );
          
       /*set sqlv='INSERT INTO nomi_incapacidades_sobrerecibo
    //      (folio, idtipoincidencia, diasautorizados, fechainicio, ramoseguro, tiporiesgo, porcentajeincapacidad, idsecuela, idcontrol, descripcion, idEmpleado,idnomp)
    //    VALUES
    //      ('$folio', $idtipoincidencia, $diasautorizados, '$fechainicio', $ramoseguro, $tiporiesgo, $porcentajeincapacidad, $idsecuela, $idcontrol, '$descripcion', $idEmpleado,$nominaactiva);
    //    ";  
          
  set sqlv='INSERT INTO nomi_claveincidencias(idtipoincidencia,idnomp,idempleado,fechaseleccion,clave)
    select nc.idtipoincidencia,nc.idnomp,nc.idEmpleado,nc.fechainicio,ti.clave  from nomi_incapacidades_sobrerecibo as nc inner join 
       nomi_tipoincidencias as ti on ti.idtipoincidencia=nc.idtipoincidencia';
       
       
             /*solo guardamos en caso de que no haya algo previamente capturado en ese dia*/

      if existenregistros = 0 or i=0 then         set sqlv = concat(sqlv , '(', prmidempleado ,',',prmtipoIncidencia,',',prmidnomp,',\'',fechaini,'\',\'',prmclave,'\',1),');
      end if;
      
      set i = i+1;
      set fechaini = date_add(fechaini, INTERVAL 1 DAY);    /*incrementamos la fecha cada vez que guardamos un dia*/
      
      if @idconsiderado=2  and fechaini > @fechafin then /*si idconsiderado es 2 y la fecha es mayor a la fecha final del periodo,*/
        set prmidnomp = prmidnomp + 1;           /*incrementamos*/
        select `fechafin` 
        from nomi_nominasperiodo
        where idnomp=prmidnomp
        into @fechafin;
      elseif  @idconsiderado!=2 and fechaini >@fechafin then /*solo los idconsiderado=2 pueden brincar al sig. periodo*/
        set i=valor;    /*hacemos i=valor para que ya se salga del ciclo*/
      end if;
       
    end while;
    set sqlv = SUBSTRING(sqlv, 1, CHAR_LENGTH(sqlv)-1);
      
  elseif @clasificadorIncidencia = 3 then
        set sqlv = concat(sqlv , '(', prmidempleado ,',',prmtipoIncidencia,',',prmidnomp,',\'',fecha,'\',\'',prmclave,'\',',valor,')');
  end if  ;
  
  SET @sql = CONCAT( sqlv,'') ; 
  PREPARE STMT FROM @sql;
  EXECUTE STMT;
end;;
DELIMITER ;


/**/

CREATE TABLE `nomi_conceptos_finiquito` (
  `idconf` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) DEFAULT NULL,
  `idconcepto` int(11) DEFAULT NULL,
  `diastotal` double(100,3) DEFAULT NULL,
  PRIMARY KEY (`idconf`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


INSERT IGNORE INTO `nomi_conceptos_finiquito` (`idconf`, `nombre`, `idconcepto`, `diastotal`)
VALUES
  (1, 'Sueldo', NULL, NULL),
  (2, 'Vac. a tiempo', NULL, NULL),
  (3, 'Vac. pendientes', NULL, NULL),
  (4, 'Aguinaldo', NULL, NULL),
  (5, 'Prima vacacional', NULL, NULL),
  (6, 'Indemnizacion 90 dias', NULL, 90.000),
  (7, '20 dias por año', NULL, NULL),
  (8, 'Prima de antigüedad', NULL, NULL),
  (9, 'Gratificación ', NULL, NULL);


INSERT ignore INTO `catalog_campos` (`idcampo`, `idestructura`, `nombrecampo`, `nombrecampousuario`, `descripcion`, `longitud`, `tipo`, `valor`, `formula`, `requerido`, `formato`, `orden`, `llaveprimaria`)
VALUES
  (2578, 413, 'idclaveriesgopuesto', 'Riesgo puesto', 'Riesgo puesto', 11, 'int', 'NA', '', 0, '-1', 3, 0);








