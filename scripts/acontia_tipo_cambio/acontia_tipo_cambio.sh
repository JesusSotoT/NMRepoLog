#!/bin/bash
strLog=$(php /srv/www/cronscripts/acontia_tipo_cambio/tipo_de_cambio_oficial.php)
echo -e $(date +"%y-%m-%d %k:%M:%S.%N")"\r\n$strLog"
