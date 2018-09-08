	for arg in *
	do
		if [ -d $arg ] && [ $arg != "_base" ]
		then
			echo $arg
			#rm -R $arg/webapp/modulos/imprimeplan/dompdf-0.5.1/
			#rm -R $arg/webapp/modulos/punto_venta/Classes/PHPExcel/Writer/PDF/
			#rm -R $arg/webapp/modulos/punto_venta/punto_venta/Classes/PHPExcel/Writer/PDF/
			#rm -R $arg/webapp/modulos/xtructur/Classes/
			#rm $arg/webapp/modulos/punto_venta/Classes/PHPExcel/Writer/PDF.php
			#rm $arg/webapp/modulos/punto_venta/punto_venta/Classes/PHPExcel/Writer/PDF.php
		fi
	done
### BEGIN - SINCRONIZACION A INSTANCIAS ###

echo $(date +"%y-%m-%d %k:%M:%S.%N")" -> TERMINA"
