#10702- 10711, 10713-10730, 10731-10770, 10771-10800
intIni=10801
intEnd=10840
strPref="mlog"
#strBasedb=_db$strPref$(printf %.10d $(($intIni-1)))
#strBasedir=$strPref$(printf %.10d $(($intIni-1)))
strBasedb="_clonbase"
strBasedir="_clonbase"
strDbusr="nmdevel"
strDbpwd="nmdevel"
strDbhost="nmdb.cyv2immv1rf9.us-west-2.rds.amazonaws.com"
strDumpfile="dump.sql"
strStoredb="netwarstore"
strWebconfig="webapp/netwarelog/webconfig.php"

echo $(date +"%y-%m-%d %r")" -> START"

echo $(date +"%y-%m-%d %r")" -> DUMP DB"
if [ -f $strDumpfile ]
then
	rm $strDumpfile
fi

mysqldump -h $strDbhost -u $strDbusr -p$strDbpwd $strBasedb > $strDumpfile

for ((num=$intIni;num<=$intEnd;num++))
do

        fileN=$(printf %.10d $num)
	dirN=$strPref$fileN

	echo $(date +"%y-%m-%d %r")" -> Clone "$dirN
	dbN=_db$dirN
	usrDB=_u$dirN
	pwdDB=_p$dirN$RANDOM
	salT=$fileN$strPref

	printf "CP -> "	
	cp -R $strBasedir/ ./$dirN

#	printf "WEBCONFIG -> "
#	rm $dirN/$strWebconfig
#	echo '<?php' > $dirN/$strWebconfig
#	echo '$tipobd= "mysql";' >> $dirN/$strWebconfig
#	echo '$servidor ="'$strDbhost'";' >> $dirN/$strWebconfig
#	echo '$usuariobd = "'$usrDB'";' >> $dirN/$strWebconfig
#	echo '$clavebd = "'$pwdDB'";' >> $dirN/$strWebconfig
#	echo '$bd= "'$dbN'";' >> $dirN/$strWebconfig
#	echo '$instalarbase= "0";' >> $dirN/$strWebconfig
#	echo '$link_regreso= "../accelog/";' >> $dirN/$strWebconfig
#	echo '$link_gestor = "catalog/gestor.php";' >> $dirN/$strWebconfig
#	echo '$crear_tablas_organizacion_empleados = 0;' >> $dirN/$strWebconfig
#	echo '$crear_tablas = 0;' >> $dirN/$strWebconfig
#	echo '$accelog_variable = "'$salT'";' >> $dirN/$strWebconfig
#	echo '$accelog_salt = "$2a$07$".$accelog_variable."$";' >> $dirN/$strWebconfig
#	echo '$tabla_organizacion = "organizaciones";' >> $dirN/$strWebconfig
#	echo '$campo_idorganizacion = "idorganizacion";' >> $dirN/$strWebconfig
#	echo '$campo_nombre_org = "nombreorganizacion";' >> $dirN/$strWebconfig
#	echo '$idestructura_organizacion = "1";' >> $dirN/$strWebconfig
#	echo '$tabla_empleados = "empleados";' >> $dirN/$strWebconfig
#	echo '$campo_idempleado = "idempleado";' >> $dirN/$strWebconfig
#	echo '$campo_nombre_emp = "nombre";' >> $dirN/$strWebconfig
#	echo '$campo_paterno_emp = "apellido1";' >> $dirN/$strWebconfig
#	echo '$campo_materno_emp = "apellido2";' >> $dirN/$strWebconfig
#	echo '$idestructura_empleados = "2";' >> $dirN/$strWebconfig
#	echo '$super_usu = "yoda";' >> $dirN/$strWebconfig
#	echo '$super_pwd = "vader";' >> $dirN/$strWebconfig
#	echo '$super_perfil = "NMPerfil";' >> $dirN/$strWebconfig
#	echo '$super_nombre_org = "eNFoto";' >> $dirN/$strWebconfig
#	echo '$super_idorganizacion = "1";' >> $dirN/$strWebconfig
#	echo '$super_idempleado = "1";' >> $dirN/$strWebconfig
#	echo '$super_nombre = "Yoda";' >> $dirN/$strWebconfig
#	echo '$super_paterno = "De";' >> $dirN/$strWebconfig
#	echo '$super_materno = "Kana";' >> $dirN/$strWebconfig
#	echo '$permiso_otras_organizaciones_desc = "Permitir el acceso a otras organizaciones.";' >> $dirN/$strWebconfig
#	echo '$permiso_otras_organizaciones_id = "NMOTRAS_ORG";' >> $dirN/$strWebconfig
#	echo '$url_repolog_para_accelog = "../repolog/";' >> $dirN/$strWebconfig
#	echo '$instalarbase_repolog = "0";' >> $dirN/$strWebconfig
#	echo '$fase_desarrollo = 1;' >> $dirN/$strWebconfig
#	echo '$tamano_buffer = "120M";' >> $dirN/$strWebconfig
#	echo '$url_repolog="../repolog/";' >> $dirN/$strWebconfig
#	echo '$url_catalog = "../catalog/";' >> $dirN/$strWebconfig
#	echo '$url_doclog_para_accelog = "../doclog/";' >> $dirN/$strWebconfig
#	echo '$link_gestor_doclog = "doclog/abrir.php";' >> $dirN/$strWebconfig
#	echo '$filas_pagina = 11;' >> $dirN/$strWebconfig
#	echo '$netwarelog_correo_usu = "soporte@netwaremonitor.com";' >> $dirN/$strWebconfig
#	echo '$netwarelog_correo_pwd = "&=98+69netware";' >> $dirN/$strWebconfig
#	echo '$tiempo_timeout = 10000;' >> $dirN/$strWebconfig
#	echo 'include "accelog.php";' >> $dirN/$strWebconfig
#	echo '?>' >> $dirN/$strWebconfig

	printf "CHMOD -> "
	chmod -R 777 $dirN/
	printf "TOUCH -> "
	find ./$dirN/ -exec touch {} \+
	printf "CHOWN -> "
	chown -R www-data $dirN/
	printf "CHGRP -> "
	chgrp -R www-data $dirN/

	printf "DB -> "
	mysql -h $strDbhost -u $strDbusr -p$strDbpwd -e "CREATE DATABASE $dbN"
	mysql -h $strDbhost -u $strDbusr -p$strDbpwd $dbN< $strDumpfile

	printf "USR -> "
	mysql -h $strDbhost -u $strDbusr -p$strDbpwd -e "CREATE USER '$usrDB'@'%' IDENTIFIED BY '$pwdDB';"
	mysql -h $strDbhost -u $strDbusr -p$strDbpwd -e "GRANT SELECT, INSERT, UPDATE, REFERENCES, DELETE, CREATE, DROP, ALTER, INDEX, TRIGGER, CREATE VIEW, SHOW VIEW, EXECUTE, ALTER ROUTINE, CREATE ROUTINE, CREATE TEMPORARY TABLES, LOCK TABLES, EVENT ON $dbN.* TO $usrDB@'%';"
	mysql -h $strDbhost -u $strDbusr -p$strDbpwd -e "GRANT GRANT OPTION ON $dbN.* TO $usrDB@'%';"

	printf "YODA -> "
	pwdVader=$(php encpwd.php vader $salT)
	mysql -h $strDbhost -u $strDbusr -p$strDbpwd $dbN -e "UPDATE accelog_usuarios SET clave = '$pwdVader' WHERE idempleado = 1;"

	printf "INSTANCE"
	mysql -h $strDbhost -u $strDbusr -p$strDbpwd $strStoredb -e "INSERT INTO customer (instancia,nombre_db,usuario_db,pwd_db,usuario_master,pwd_master) VALUES ('$dirN','$dbN','$usrDB','$pwdDB','yoda','vader');"
	mysql -h $strDbhost -u $strDbusr -p$strDbpwd $strStoredb -e "INSERT INTO instancias (base) VALUES ('$dirN');"

	echo ""

done

echo $(date +"%y-%m-%d %r")" -> FINISH"
