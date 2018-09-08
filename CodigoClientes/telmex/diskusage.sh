for arg in *
do
	if [ -d $arg ] && [ $arg != _base ]
	then
		du -sh $arg/
	fi
done
