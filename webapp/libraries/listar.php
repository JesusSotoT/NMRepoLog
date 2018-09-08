<?php

function ScanDirectory($Directory){

  $MyDirectory = opendir($Directory) or die('Error');
	while($Entry = @readdir($MyDirectory)) {
		if(is_dir($Directory.'/'.$Entry)&& $Entry != '.' && $Entry != '..') {
                         echo '<ul>'.$Directory;
			ScanDirectory($Directory.'/'.$Entry);
                        echo '</ul>';
		}
		else {
			echo '<li>'.$Entry.'</li>';
                }
	}
  closedir($MyDirectory);
}
ScanDirectory('.');

?>


