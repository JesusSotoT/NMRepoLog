INSERT INTO `cont_accounts` (`account_id`, `account_code`, `manual_code`, `description`, `sec_desc`, `account_type`, `status`, `main_account`, `cash_flow`, `reg_date`, `currency_id`, `group_dig`, `id_sucursal`, `seg_neg_mov`, `affectable`, `mod_date`, `father_account_id`, `removable`, `account_nature`, `removed`, `main_father`, `cuentaoficial`, `nif`)
VALUES
	(1, '1', '100.00.000', 'ACTIVO', 'ASSETS', 1, 1, 2, 0, '2016-04-18', 1, 0, 0, 0, 0, '2016-04-18', 0, 0, 2, 0, 0, '', 0),
	(2, '1.1', '100.01.000', 'ACTIVO A CORTO PLAZO', 'CURRENT ASSETS', 1, 1, 2, 0, '2016-04-18', 1, 0, 0, 0, 0, '2016-04-18', 1, 0, 2, 0, 0, '', 0),
	(3, '1.2', '100.02.000', 'ACTIVO A LARGO PLAZO', 'FIXED ASSETS', 1, 1, 2, 0, '2016-04-18', 1, 0, 0, 0, 0, '2016-04-18', 1, 0, 2, 0, 0, '', 0),
	(4, '2', '200.00.000', 'PASIVO', 'LIABILITIES', 2, 1, 2, 0, '2016-04-18', 1, 0, 0, 0, 0, '2016-04-18', 0, 0, 1, 0, 0, '', 0),
	(5, '2.1', '200.01.000', 'PASIVO A CORTO PLAZO', 'CURRENT LIABILITIES', 2, 1, 2, 0, '2016-04-18', 1, 0, 0, 0, 0, '2016-04-18', 4, 0, 1, 0, 0, '', 0),
	(6, '2.2', '200.02.000', 'PASIVO A LARGO PLAZO', 'LONG TERM DEBT', 2, 1, 2, 0, '2016-04-18', 1, 0, 0, 0, 0, '2016-04-18', 4, 0, 1, 0, 0, '', 0),
	(7, '3', '300.00.000', 'CAPITAL CONTABLE', 'CAPITAL', 3, 1, 2, 0, '2016-04-18', 1, 0, 0, 0, 0, '2016-04-18', 0, 0, 1, 0, 0, '', 0),
	(8, '4.1', '400.00.000', 'INGRESOS', 'INCOME', 4, 1, 2, 0, '2016-04-18', 1, 0, 0, 0, 0, '2016-04-18', 0, 0, 1, 0, 0, '', 0),
	(9, '4.2', '500.00.000', 'EGRESOS', 'EXPENSES', 4, 1, 2, 0, '2016-04-18', 1, 0, 0, 0, 0, '2016-04-18', 0, 0, 2, 0, 0, '', 0),
	(10, '5', '800.00.000', 'CUENTAS DE ORDEN', 'ORDER', 5, 1, 2, 0, '2016-04-18', 1, 0, 0, 0, 0, '2016-04-18', 0, 1, 2, 0, 0, '', 0);