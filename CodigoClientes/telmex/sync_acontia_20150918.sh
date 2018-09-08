args=("$@")
blnUpdGit=${args[0]}
blnUpdDB=${args[1]}

rm *.txt

strLog=$(date +"%Y%m%d%H%M%S")_sync_log.txt

echo $(date +"%y-%m-%d %k:%M:%S.%N")" -> INICIA"

### BEGIN - SINCRONIZACION DESDE GITHUB ###

	if [ $blnUpdGit == 1 ]
	then
		echo " "$(date +"%y-%m-%d %k:%M:%S.%N")" -> INICIA SINCRONIZACION DE '_base' DESDE GIT "i >> $strLog
		
		cd _base/
		
		git clean -df & git checkout .
		git pull origin stage
		
		chmod -R 777 *
		#chown -R wwwrun *
		chown -R www-data *
		#chgrp -R www *
		chgrp -R www-data *
	
		rm webapp/netwarelog/archivos/1/administracion_usuarios/*
		touch webapp/netwarelog/archivos/1/administracion_usuarios/.file
		touch webapp/netwarelog/archivos/1/organizaciones/.file
		rm webapp/netwarelog/utilerias/img_org/*
		touch webapp/netwarelog/utilerias/img_org/.file

		rm webapp/modulos/facturas/*
		touch webapp/modulos/facturas/.file
		rm webapp/modulos/mrp/images/productos/*
		touch webapp/modulos/mrp/images/productos/.file
		rm webapp/modulos/SAT/cliente/*
		touch webapp/modulos/SAT/cliente/.file
		rm webapp/modulos/xtructur/uploads/*
		touch webapp/modulos/xtructur/uploads/.file
	
		rm webapp/modulos/SAT/config.php

		cd ..
	
		echo " "$(date +"%y-%m-%d %k:%M:%S.%N")" -> TERMINA SINCRONIZACION DE '_base' DESDE GIT " >> $strLog
		echo "" >> $strLog
	fi

### END - SINCRONIZACION DESDE GITHUB ###
arrFiles=()
#webapp/modulos/mrp/antesprove.php
#webapp/modulos/mrp/guardavariable.php
#webapp/modulos/punto_venta/catalogos/gridproveedor.php
#webapp/modulos/punto_venta/catalogos/js/proveedor.js
arrDirsR=()
arrDirsRNew=()
#webapp/modulos/cont/js
#webapp/modulos/cont/controllers
#webapp/modulos/cont/css
#webapp/modulos/cont/images
#webapp/modulos/cont/importar
#webapp/modulos/cont/libraries
#webapp/modulos/cont/models
#webapp/modulos/cont/views
#webapp/modulos/cont/xmls/funciones
#webapp/modulos/cont/xmls/valida_xmls
#webapp/modulos/cont_repolog
#webapp/modulos/cont/xmls/folios
#webapp/modulos/cont/xmls/zips
#webapp/modulos/cont/xmls/polizas
#webapp/modulos/cont/xmls/polizas
arrDirsM=()
arrDirsN=()
arrDirsREmpty=(
webapp/modulos/cont/xmls/auxcuentas
)
#webapp/modulos/cont/xmls/folios/
#webapp/modulos/cont/xmls/zips/
#webapp/modulos/cont/xmls/polizas/
#webapp/modulos/cont/xmls/facturas/
#webapp/modulos/cont/xmls/facturas/temporales/
### BEGIN - SINCRONIZACION A INSTANCIAS ###

	for arg in *
	do
		if [ -d $arg ] && [ $arg != "_base" ] && [ $arg != "coras" ] && [ $arg != "_test" ] && [ $arg != "inadem" ]
		then
			echo $(date +"%y-%m-%d %k:%M:%S.%N")" -> "$arg
			echo " "$(date +"%y-%m-%d %k:%M:%S.%N")" -> INICIA SINCRONIZACION DE '$arg' DESDE _base " >> $strLog

                        for strDirsM in ${arrDirsM[@]}; do
                                printf $arg"/webapp/modulos/"$strDirsM" -----> "
                                printf " cp ... "
                                cp -R _base/webapp/modulos/$strDirsM/ $arg/webapp/modulos/
                                printf " chmod ... \n"
                                chmod -R 777 $arg/webapp/modulos/$strDirsM/
                        done

			for strFile in ${arrFiles[@]}; do
				printf $strFile" -----> "
				printf " cp ... "
				cp _base/$strFile $arg/$strFile
				printf " touch ... "
				touch $arg/$strFile
				printf " chown ... "
				#chown wwwrun $arg/$strFile
				chown www-data $arg/$strFile
				printf " chgrp ... "
				#chgrp www $arg/$strFile
				chgrp www-data $arg/$strFile
				printf " chmod ... \n"
				chmod 777 $arg/$strFile
			done
			
			for strDirsR in ${arrDirsR[@]}; do
                                printf $strDirsR" -----> "
                                printf " cp ... "
                                cp -R _base/$strDirsR/ $arg/
                                printf " chmod ... \n"
                                chmod -R 777 $arg/$strDirsR/
                        done

			# Create empty directories with permissions
			# and make .file.rtf
			for strDirsR in ${arrDirsREmpty[@]}; do
                                printf $arg/$strDirsR" -----> "
                                printf " making ... "
                                mkdir -p $arg/$strDirsR/
                                printf " chmod ... \n"
                                chmod -R 777 $arg/$strDirsR/
				chown -R www-data $arg/$strDirsR
				chgrp -R www-data $arg/$strDirsR
				touch $arg/$strDirsR/.file.rtf
                                chmod 777 $arg/$strDirsR/.file.rtf
				chown www-data $arg/$strDirsR/.file.rtf
				chgrp www-data $arg/$strDirsR/.file.rtf
			done


			for strDirsR in ${arrDirsRNew[@]}; do
                                printf $strDirsR" -----> "
                                printf " making directory ... "
				mkdir -p $arg/$strDirsR/
                                printf " applying permissions ... "
                                chmod -R 777 $arg/$strDirsR/
                                printf " cp ... "
				POS=0
				FIN=0
				for (( i=0; i<${#strDirsR}; i++ )); do
					if [ ${strDirsR:$i:1} == "/" ]
					then 
						FIN=$i
					fi 
				done 
				destiny=${strDirsR:$POS:$FIN}
				printf " DESTINY: "
				printf $destiny/
				printf " ..... "
				
                                cp -R _base/$strDirsR/ $arg/$destiny/
                                printf " chmod ... \n"
                                chmod -R 777 $arg/$strDirsR/
				#chown -R www-data $arg/$strDirsR
				#chgrp -R www-data $arg/$strDirsR
				#chown -R www-data $arg/$strDirsR/*
				#chgrp -R www-data $arg/$strDirsR/*
                        done

                        for strDirsN in ${arrDirsN[@]}; do
                                printf $strDirsN" -----> "
                                printf " cp ... "
                                cp -R _base/webapp/netwarelog/$strDirsN/ $arg/webapp/netwarelog/
                                printf " chmod ... \n"
                                chmod -R 777 $arg/webapp/netwarelog/$strDirsN/
                        done
			
			if [ $blnUpdDB == 1 ]
			then
				
				echo $(date +"%y-%m-%d %k:%M:%S.%N")" -----> DB"
				targetDB=$(php getDB.php $arg)
				if [ $targetDB != "--NOLAENCONTRE--" ]
				then
					mysql --force -h nmdb.cyv2immv1rf9.us-west-2.rds.amazonaws.com -u nmdevel -pnmdevel $targetDB< updDB.sql
				else
					if [ $arg == "_clonbase" ]
					then
						mysql --force -h nmdb.cyv2immv1rf9.us-west-2.rds.amazonaws.com -u nmdevel -pnmdevel _clonbase< updDB.sql
					fi
				fi
				
	
			fi

			echo " "$(date +"%y-%m-%d %k:%M:%S.%N")" -> TERMINA SINCRONIZACION DE '$arg' DESDE _base " >> $strLog
			echo "" >> $strLog

			echo ""
		fi
	done
### BEGIN - SINCRONIZACION A INSTANCIAS ###

echo $(date +"%y-%m-%d %k:%M:%S.%N")" -> TERMINA"
