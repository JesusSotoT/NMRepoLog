#México

args=("$@")
appType=${args[0]}

### app types:
###  1004 acontia
###  1001 appministra
###  1002  addon foodware
###  1003  addon agenda
###  1006 netwarlog
###  1005  addon tarjeta de regalo
###  1007 CRM
###  1008 Producción
###  1009  addon factura elecronica
###  1010 Appministra Facturación
###  1011 Xtructur

rm *.txt
strLog=$(date +"%Y%m%d%H%M%S")_sync_log.txt

echo $(date +"%y-%m-%d %k:%M:%S.%N")" -> INICIA"

### BEGIN - SINCRONIZACION DB A INSTANCIAS ###

for arg in *
do
	if [ -d $arg ] && [ $arg != "_base" ] && [ $arg != "coras" ] && [ $arg != "_test" ] && [ $arg != "inadem" ]
	then
		strDatabase=$(php getDBapp.php $appType $arg)
		if [ $strDatabase != "--NOLAENCONTRE--" ]
		then
			echo "Instancia: ["$arg"]"
			echo "DB: "$strDatabase"     TIPO:"$appType
					
			echo $(date +"%y-%m-%d %k:%M:%S.%N")" -----> DB"
			targetDB=$strDatabase
			
			# Aplicando el sql para la app especial.
			mysql --force -h nmdb.cyv2immv1rf9.us-west-2.rds.amazonaws.com -u nmdevel -pnmdevel $targetDB< updDB.sql
	
			echo " "$(date +"%y-%m-%d %k:%M:%S.%N")" -> TERMINA SINCRONIZACION DE '$arg' DESDE _base " >> $strLog
			echo "" >> $strLog	
			echo ""
		fi
	fi
done
### END - SINCRONIZACION A INSTANCIAS ###
echo $(date +"%y-%m-%d %k:%M:%S.%N")" -> TERMINA"
