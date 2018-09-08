#México



strLog=$(date +"%Y%m%d%H%M%S")_sync_log.txt

echo $(date +"%y-%m-%d %k:%M:%S.%N")" -> INICIA"

### BEGIN - SINCRONIZACION DESDE GITHUB ###

		echo " "$(date +"%y-%m-%d %k:%M:%S.%N")" -> INICIA SINCRONIZACION DE '_base' DESDE GIT "i >> $strLog
		
		cd _base/
		
		git clean -df & git checkout .
		git pull origin stage
		
	

