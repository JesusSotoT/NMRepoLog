INSERT INTO `cont_accounts` (`account_id`, `account_code`, `manual_code`, `description`, `sec_desc`, `account_type`, `status`, `main_account`, `cash_flow`, `reg_date`, `currency_id`, `group_dig`, `id_sucursal`, `seg_neg_mov`, `affectable`, `mod_date`, `father_account_id`, `removable`, `account_nature`, `removed`, `main_father`, `cuentaoficial`, `nif`)
VALUES
	(1,'1','1','ACTIVO','ASSETS',1,1,2,0,'2016-02-04',1,0,0,0,0,'2015-02-04',0,0,2,0,0,'',0),
    (2,'1.1','1.1','CIRCULANTE','ASSETS',1,1,2,0,'2016-02-04',1,0,0,0,0,'2015-02-04',1,0,2,0,0,'',0),
    (3,'1.2','1.2','NO CIRCULANTE','ASSETS',1,1,2,0,'2016-02-04',1,0,0,0,0,'2015-02-04',1,0,2,0,0,'',0),
	(4,'2','2','PASIVO','LIABILITIES',2,1,2,0,'2016-10-02',1,0,0,0,0,NULL,0,0,1,0,0,'0',0),
    (5,'2.1','2.1','CORTO PLAZO','LIABILITIES',2,1,2,0,'2016-10-02',1,0,0,0,0,NULL,4,0,1,0,0,'0',0),
    (6,'2.2','2.2','LARGO PLAZO','LIABILITIES',2,1,2,0,'2016-10-02',1,0,0,0,0,NULL,4,0,1,0,0,'0',0),
	(7,'3','3','CAPITAL CONTABLE','CAPITAL',3,1,2,0,'2016-10-02',1,0,0,0,0,NULL,0,0,1,0,0,'0',0),
    (8,'3.1','3.1','CONTRIBUIDO','CAPITAL',3,1,2,0,'2016-10-02',1,0,0,0,0,NULL,7,0,1,0,0,'0',0),
    (9,'3.2','3.2','GANADO','CAPITAL',3,1,2,0,'2016-10-02',1,0,0,0,0,NULL,7,0,1,0,0,'0',0),
	(10,'4','4','RESULTADOS','RESULTS',4,1,2,0,'2016-02-04',1,0,0,0,0,'2015-02-04',0,0,1,0,0,'',0),
	(11,'4.1','4.1','INGRESOS','INCOME',4,1,2,0,'2016-10-02',1,0,0,0,0,NULL,10,0,1,0,0,'0',0),
	(12,'4.2','4.2','EGRESOS','EXPENSES',4,1,2,0,'2016-02-04',1,0,0,0,0,'2015-02-04',10,0,2,0,0,'',0),
	(13,'5','5','ORDEN','ORDER',5,1,2,0,'2016-02-04',1,0,0,0,0,'2015-02-04',0,1,1,0,0,'',0);

