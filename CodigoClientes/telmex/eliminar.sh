	for arg in *
	do
		if [ -d $arg ] && [ $arg != "_base" ]
		then
			echo $arg

			#### Applied 2016-05-01 ####
			#echo "Eliminando HazBizne"
			#rm -R $arg/webapp/modulos/hazbizne/
			#echo "Eliminando inadem"
			#rm -R $arg/webapp/modulos/inadem/
			echo "Eliminando Archivos"
                        rm -R $arg/webapp/modulos/agenda/
			rm -R $arg/webapp/modulos/manuales/
			rm -R $arg/webapp/modulos/sms/
			rm -R $arg/webapp/netwarelog/design_resp/
		fi
	done
### BEGIN - SINCRONIZACION A INSTANCIAS ###

echo $c "instancias"
echo $(date +"%y-%m-%d %k:%M:%S.%N")" -> TERMINA"
