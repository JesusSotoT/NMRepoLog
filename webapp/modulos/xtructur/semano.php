<?php
$ano = $_POST['ano'];
function NumeroSemanasTieneUnAno($ano){
        $date = new DateTime;
        $date->setISODate("$ano", 53);
        if($date->format("W")=="53")
            return 53;
        else
            return 52;
    }
echo NumeroSemanasTieneUnAno($ano);
?>