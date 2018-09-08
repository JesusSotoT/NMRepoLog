args=("$@")

rm *.txt

strLog=$(date +"%Y%m%d%H%M%S")_sync_log.txt

echo $(date +"%y-%m-%d %k:%M:%S.%N")" -> INICIA"

arrFiles=()
arrDirsRNew=()
arrDirsR=(
webapp/modulos/appministra/importacion/
webapp/modulos/pos/importacion/
webapp/modulos/pos/images/productos/
)
arrDirsM=()
arrDirsN=()
### BEGIN - SINCRONIZACION A INSTANCIAS ###

	for arg in *
	do
		if [ -d $arg ] && [ $arg != "_base" ] && [ $arg != "coras" ] && [ $arg != "_test" ] && [ $arg != "inadem" ]
		then
			echo ""
			echo ""
			echo ""
			echo $(date +"%y-%m-%d %k:%M:%S.%N")" ->        "$arg
			echo ""
			echo ""
			echo ""
			echo " "$(date +"%y-%m-%d %k:%M:%S.%N")" -> INICIA SINCRONIZACION DE '$arg' DESDE _base " >> $strLog

                        for strDirsM in ${arrDirsM[@]}; do
                                printf $arg"/webapp/modulos/"$strDirsM" -----> "
                                #printf " cp ... "
                                #cp -R _base/webapp/modulos/$strDirsM/ $arg/webapp/modulos/
                                printf " chmod ... \n"
                                chmod -R 777 $arg/webapp/modulos/$strDirsM/
                        done

			for strFile in ${arrFiles[@]}; do
				printf $strFile" -----> "
				#printf " cp ... "
				#cp _base/$strFile $arg/$strFile
				#printf " touch ... "
				#touch $arg/$strFile
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
                                #printf " cp ... "
                                #cp -R _base/$strDirsR/ $arg/
                                printf " chmod ... \n"
                                chmod -R 777 $arg/$strDirsR/
                                chmod -R 777 $arg/$strDirsR
				chown -R www-data $arg/$strDirsR
				chgrp -R www-data $arg/$strDirsR
                        done

			for strDirsR in ${arrDirsRNew[@]}; do
                                printf $strDirsR" -----> "
                                printf " making directory ... "
				#mkdir -p $arg/$strDirsR/
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
				
                                #cp -R _base/$strDirsR/ $arg/$destiny/
                                printf " chmod ... \n"
                                chmod -R 777 $arg/$strDirsR/
				chown -R www-data $arg/$strDirsR
				chgrp -R www-data $arg/$strDirsR
				chown -R www-data $arg/$strDirsR/*
				chgrp -R www-data $arg/$strDirsR/*
                        done

                        for strDirsN in ${arrDirsN[@]}; do
                                printf $strDirsN" -----> "
                                printf " cp ... "
                                #cp -R _base/webapp/netwarelog/$strDirsN/ $arg/webapp/netwarelog/
                                printf " chmod ... \n"
                                chmod -R 777 $arg/webapp/netwarelog/$strDirsN/
                        done
			
			echo " "$(date +"%y-%m-%d %k:%M:%S.%N")" -> TERMINA SINCRONIZACION DE '$arg' DESDE _base " >> $strLog
			echo "" >> $strLog

			echo ""
		fi
	done
### BEGIN - SINCRONIZACION A INSTANCIAS ###

echo $(date +"%y-%m-%d %k:%M:%S.%N")" -> TERMINA"
