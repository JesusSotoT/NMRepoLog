	for arg in *
	do
		if [ -d $arg ] && [ $arg != "_base" ] && [ $arg != "coras" ] && [ $arg != "_test" ] && [ $arg != "inadem" ]
		then
#		if [ $arg == "sistema" ]
#		then
			echo $(date +"%y-%m-%d %k:%M:%S.%N")" -> "$arg
			mkdir $arg/webapp/modulos/bancos/
			chmod -R 77 $arg/webapp/modulos/bancos/
			mkdir $arg/webapp/modulos/xtructur/
			chmod -R 77 $arg/webapp/modulos/xtructur/
#		fi
		fi
	done
