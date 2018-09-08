	for arg in *
	do
		if [ -d $arg ] && [ $arg != "_base" ]
		then
			echo $arg
			cd $arg/webapp/netwarelog/archivos/1/administracion_usuarios/
			files=./*
			for arg2 in $files
			do
#				if [ $arg2 != './*' ]
#				then
					result=$(identify "$arg2")
					echo $result
#				fi
			done
			cd /srv/www/htdocs/clientes/
		fi
	done
