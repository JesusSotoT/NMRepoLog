clear

while read strInstLine
do
	strInst=$strInstLine
	echo "CLEAN: "$strInst" - "$(date +"%y-%m-%d %k:%M:%S.%N")

	printf "GETTING DATA ... "
	intIdCustomer=$(mysql -h nmdb.cyv2immv1rf9.us-west-2.rds.amazonaws.com -u nmdevel -pnmdevel netwarstore -se "SELECT id FROM customer WHERE nombre_db = '_db$strInst';")
	intIdClient=$(mysql -h nmdb.cyv2immv1rf9.us-west-2.rds.amazonaws.com -u nmdevel -pnmdevel netwarstore -se "SELECT idclient FROM customer WHERE nombre_db = '_db$strInst';")
	strInstance=$(mysql -h nmdb.cyv2immv1rf9.us-west-2.rds.amazonaws.com -u nmdevel -pnmdevel netwarstore -se "SELECT instancia FROM customer WHERE nombre_db = '_db$strInst';")
	echo "[OK]"
	printf "REVOKE PRIVILEGES ... "
	mysql --force -h nmdb.cyv2immv1rf9.us-west-2.rds.amazonaws.com -u nmdevel -pnmdevel netwarstore -e "REVOKE ALL PRIVILEGES, GRANT OPTION FROM _u$strInst;"
	echo "[OK]"

	printf "DROP user ... "
	mysql --force -h nmdb.cyv2immv1rf9.us-west-2.rds.amazonaws.com -u nmdevel -pnmdevel netwarstore -e "DROP user _u$strInst;"
	echo "[OK]"

	printf "DROP database ... "
	mysql --force -h nmdb.cyv2immv1rf9.us-west-2.rds.amazonaws.com -u nmdevel -pnmdevel netwarstore -e "DROP database _db$strInst;"
	echo "[OK]"

	printf "DELETE customer ... "
	mysql --force -h nmdb.cyv2immv1rf9.us-west-2.rds.amazonaws.com -u nmdevel -pnmdevel netwarstore -e "DELETE FROM netwarstore.customer WHERE id = $intIdCustomer;"
	echo "[OK]"

	printf "DELETE instancias ... "
	mysql --force -h nmdb.cyv2immv1rf9.us-west-2.rds.amazonaws.com -u nmdevel -pnmdevel netwarstore -e "DELETE FROM netwarstore.instancias WHERE base = '$strInst';"
	echo "[OK]"

	printf "DELETE appclient ... "
	mysql --force -h nmdb.cyv2immv1rf9.us-west-2.rds.amazonaws.com -u nmdevel -pnmdevel netwarstore -e "DELETE FROM netwarstore.appclient WHERE idclient = $intIdClient AND idcustomer = $intIdCustomer;"
	echo "[OK]"

	printf "RM directory ... "
	rm -R $strInstance/
	echo "[OK]"

	echo ""

done < clean.txt

echo "DONE"
