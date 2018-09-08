<?php
	$str_total = "716,719.99965128";
        $str_total = str_replace(',', '',$str_total);
        //$str_total = $str_total - 0.01;
        $str_total = number_format($str_total,0).'.00';
	echo $str_total."\n";

?>
