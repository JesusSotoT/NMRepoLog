## Archivos necesarios para Appministra POS


cp -R _base/webapp/modulos/pos appministrapos2016/webapp/modulos/pos
cp -R _base/webapp/modulos/prontipagos appministrapos2016/webapp/modulos/prontipagos
cp -R _base/webapp/modulos/appministra appministrapos2016/webapp/modulos/appministra
cp -R _base/webapp/modulos/SAT appministrapos2016/webapp/modulos/SAT

chmod -R 777 appministrapos2016/webapp/modulos/pos
chmod -R 777 appministrapos2016/webapp/modulos/prontipagos
chmod -R 777 appministrapos2016/webapp/modulos/appministra
chmod -R 777 appministrapos2016/webapp/modulos/SAT

echo "Se han agregado los archivos de Appministra POS"
