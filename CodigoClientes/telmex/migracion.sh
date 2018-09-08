for arg in *
do
	if [ -d $arg ]
	then
		echo $(date +"%y-%m-%d %k:%M:%S.%N")" -> "$arg
		cp -R /mnt/disco/srv/www/htdocs/clientes/$arg ./
		chmod -R 777 $arg/
	fi
done
