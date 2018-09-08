	for arg in *
	do
		if [ -d $arg ] && [ $arg != "_base" ]
		then
			echo $arg

			#### Applied 2015-07-16 ####
			#echo "quitando carpeta select2"
			#rm -r $arg/webapp/modulos/select2/
			#rm -r $arg/select2/
			#echo "agregando la carpeta nueva"
			#cp -r _base/webapp/modulos/devoluciones/select2/ $arg/webapp/modulos/devoluciones/
			#cp -r _base/webapp/modulos/punto_venta/js/select2/ $arg/webapp/modulos/punto_venta/js/
		fi
	done
### BEGIN - SINCRONIZACION A INSTANCIAS ###

echo $(date +"%y-%m-%d %k:%M:%S.%N")" -> TERMINA"
