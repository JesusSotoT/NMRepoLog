<?php 
//Ejemplo: 1 clave del usuario 2 = key3: 0000009090mlog 2
echo crypt($argv[1],"$2a$07$".$argv[2]."aaaaaaa$");
?>
