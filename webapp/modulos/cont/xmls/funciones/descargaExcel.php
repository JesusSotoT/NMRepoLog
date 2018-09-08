<?php
$nombre = str_replace('.xml', '', $_GET['nombre']);
header( "Content-Type: application/vnd.ms-excel" );
header( "Content-disposition: attachment; filename=$nombre.xls" );
?>
<style>
.titulo
{
	 color:white;
	background-color:black;
}
</style>
<?php
$file = "../".$_GET['ruta']."/".$_GET['nombre'];
if (file_exists($file)) 
{
         
        echo "<table border=1>";

        if($_GET['tipo'] == 'balanzaComprobacionXML')
        {
                $xmlDoc = new DOMDocument(); 
                $xmlDoc->load($file);
                $titulo = $xmlDoc->getElementsByTagName("Balanza"); 
                $busca = $xmlDoc->getElementsByTagName("Ctas"); 
                echo "<tr><td style='font-weight:bold;font-size:14px;' colspan=5>Balanza estado de resultados</td></tr>";

                foreach( $titulo as $titulo ) 
                {
                        echo "<tr><td>Version: ".$titulo->getAttribute('Version')."</td><td> RFC: ".$titulo->getAttribute('RFC')."</td><td> TotalCtas: ".$titulo->getAttribute('TotalCtas')."</td><td> Mes: ".$titulo->getAttribute('Mes')."</td><td> Ano: ".$titulo->getAttribute('Ano')."</td></tr>";
                }
  
                echo "<tr><td class='titulo'>NumCta</td><td class='titulo'>SaldoIni</td><td class='titulo'>Debe</td><td class='titulo'>Haber</td><td class='titulo'>Saldo</td></tr>";

                foreach( $busca as $busca ) 
                {
                        echo "<tr><td style='mso-number-format:\"@\";'>".$busca->getAttribute('NumCta')."</td><td>" . $busca->getAttribute('SaldoIni')."</td><td>" . $busca->getAttribute('Debe')."</td><td>" . $busca->getAttribute('Haber')."</td><td>" . $busca->getAttribute('SaldoFin')."</td></tr>";
                }

        }

        if($_GET['tipo'] == 'catalogoXML')
        {
                $xmlDoc = new DOMDocument(); 
                $xmlDoc->load($file);
                $titulo = $xmlDoc->getElementsByTagName("Catalogo"); 
                $busca = $xmlDoc->getElementsByTagName("Ctas"); 
                echo "<tr><td style='font-weight:bold;font-size:14px;' colspan=5>Catalogo de Cuentas</td></tr>";

                foreach( $titulo as $titulo ) 
                {
                        echo "<tr><td>Version: ".$titulo->getAttribute('Version')."</td><td> RFC: ".$titulo->getAttribute('RFC')."</td><td> TotalCtas: ".$titulo->getAttribute('TotalCtas')."</td><td> Mes: ".$titulo->getAttribute('Mes')."</td><td> Ano: ".$titulo->getAttribute('Ano')."</td></tr>";
                }
  
                echo "<tr><td class='titulo'>CodAgrup</td><td class='titulo'>NumCta</td><td class='titulo'>Desc</td><td class='titulo'>SubCtaDe</td><td class='titulo'>Nivel</td><td class='titulo'>Natur</td></tr>";

                foreach( $busca as $busca ) 
                {
                        echo "<tr><td style='mso-number-format:\"@\";'>".$busca->getAttribute('CodAgrup')."</td><td style='mso-number-format:\"@\";'>".$busca->getAttribute('NumCta')."</td><td>" . $busca->getAttribute('Desc')."</td><td style='mso-number-format:\"@\";'>" . $busca->getAttribute('SubCtaDe')."</td><td>" . $busca->getAttribute('Nivel')."</td><td>" . $busca->getAttribute('Natur')."</td></tr>";
                }
                
        }

        if($_GET['tipo'] == 'polizasXML')
        {
                $xmlDoc = new DOMDocument(); 
                $xmlDoc->load($file);
                $titulo = $xmlDoc->getElementsByTagName("Polizas"); 
                $poliza = $xmlDoc->getElementsByTagName("Poliza"); 

                echo "<tr><td style='font-weight:bold;font-size:14px;' colspan=5>Polizas del periodo</td></tr>";

                foreach( $titulo as $titulo ) 
                {
                        echo "<tr><td>Version: ".$titulo->getAttribute('Version')."</td><td> RFC: ".$titulo->getAttribute('RFC')."</td><td> Mes: ".$titulo->getAttribute('Mes')."</td><td> Anio: ".$titulo->getAttribute('Anio')."</td><td>Tipo Solicitud: ".$titulo->getAttribute('TipoSolicitud')."</td>";
                        if($titulo->getAttribute('NumOrden'))
                        {
                            echo "<td>NumOrden</td><td>".$titulo->getAttribute('NumOrden')."</td>";
                        }
                        echo "</tr></table>";
                }
                echo "<table>";
                foreach( $poliza as $poliza ) 
                {
                    echo "<tr style='border-top:1px solid black;'><td><b>Poliza</b></td><td><b>NumUniIdenPol:</b></td><td>".$poliza->getAttribute('NumUnIdenPol')."</td><td><b>Fecha:</b></td><td>".$poliza->getAttribute('Fecha')."</td><td><b>Concepto:</b></td><td>".$poliza->getAttribute('Concepto')."</td><td></td><td></td><td></td><td></td><td></td></tr>";
                    $movimientos = $poliza->getElementsByTagName("Transaccion");
                    foreach( $movimientos as $movimientos )
                    {
                        echo "<tr><td>-</td><td><b>- Transaccion</b></td><td><b>NumCta:</b></td><td>".$movimientos->getAttribute('NumCta')."</td><td><b>DesCta:</b></td><td>".$movimientos->getAttribute('DesCta')."</td><td><b>Concepto:</b></td><td>".$movimientos->getAttribute('Concepto')."</td><td><b>Debe:</b></td><td>".$movimientos->getAttribute('Debe')."</td><td><b>Haber:</b></td><td>".$movimientos->getAttribute('Haber')."</td></tr>";
                        if($CompNal = $movimientos->getElementsByTagName("CompNal"))
                        {
                            foreach ($CompNal as $CompNal) 
                            {
                                echo "<tr><td>-</td><td>-</td><td><b>- CompNal</b></td><td><b>UUID_CFDI:</b></td><td>".$CompNal->getAttribute('UUID_CFDI')."</td><td><b>RFC:</b></td><td>".$CompNal->getAttribute('RFC')."</td><td><b>MontoTotal:</b></td><td>".$CompNal->getAttribute('MontoTotal')."</td></tr>";
                            }
                        }
                        if($Cheque = $movimientos->getElementsByTagName("Cheque"))
                        {
                            foreach ($Cheque as $Cheque) 
                            {
                                echo "<tr><td>-</td><td>-</td><td><b>- Cheque</b></td><td><b>Num:</b></td><td>".$Cheque->getAttribute('Num')."</td><td><b>BanEmisNal:</b></td><td>".$Cheque->getAttribute('BanEmisNal')."</td><td><b>CtaOri:</b></td><td>".$Cheque->getAttribute('CtaOri')."</td><td><b>Fecha:</b></td><td>".$Cheque->getAttribute('Fecha')."</td><td><b>Benef:</b></td><td>".$Cheque->getAttribute('Benef')."</td><td><b>RFC:</b></td><td>".$Cheque->getAttribute('RFC')."</td><td><b>Monto:</b></td><td>".$Cheque->getAttribute('Monto')."</td></tr>";
                            }
                        }
                        if($Transferencia = $movimientos->getElementsByTagName("Transferencia"))
                        {
                            foreach ($Transferencia as $Transferencia) 
                            {
                                echo "<tr><td>-</td><td>-</td><td><b>- Transferencia</b></td><td><b>CtaOri:</b></td><td>".$Transferencia->getAttribute('CtaOri')."</td><td><b>BanOriNal:</b></td><td>".$Transferencia->getAttribute('BanOriNal')."</td><td><b>CtaDest:</b></td><td>".$Transferencia->getAttribute('CtaDest')."</td><td><b>BancoDestNal:</b></td><td>".$Transferencia->getAttribute('BancoDestNal')."</td><td><b>Fecha:</b></td><td>".$Transferencia->getAttribute('Fecha')."</td><td><b>Benef:</b></td><td>".$Transferencia->getAttribute('Benef')."</td><td><b>RFC:</b></td><td>".$Transferencia->getAttribute('RFC')."</td><td><b>Monto:</b></td><td>".$Transferencia->getAttribute('Monto')."</td></tr>";
                            }
                        }
                        if($OtrMetodoPago = $movimientos->getElementsByTagName("OtrMetodoPago"))
                        {
                            foreach ($OtrMetodoPago as $OtrMetodoPago) 
                            {
                                echo "<tr><td>-</td><td>-</td><td><b>- OtrMetodoPago</b></td><td><b>MetPagoPol:</b></td><td>".$OtrMetodoPago->getAttribute('MetPagoPol')."</td><td><b>Fecha:</b></td><td>".$OtrMetodoPago->getAttribute('Fecha')."</td><td><b>Benef:</b></td><td>".$OtrMetodoPago->getAttribute('Benef')."</td><td><b>RFC:</b></td><td>".$OtrMetodoPago->getAttribute('RFC')."</td><td><b>Monto:</b></td><td>".$OtrMetodoPago->getAttribute('Monto')."</td></tr>";
                            }
                        }
                    }
                }

        }

        if($_GET['tipo'] == 'a29Txt')
         {
               $txt = fopen($file, "r") or exit("Unable to open file!");
               echo "<tr><td style='font-weight:bold;font-size:14px;' colspan=5>A29 Proveedores</td></tr>";
                 echo "<tr style='background-color:black;color:white;'><td>Tipo Tercero</td><td>Tipo Operacion</td><td>RFC</td><td># ID Fiscal</td><td>Nombre Extranjero</td><td>Pais de Residencia</td><td>Nacionalidad</td><td>IVA 16% o 15%</td><td>IVA 15%</td><td>IVA 16% o 15% No Acreditable</td><td>IVA 11% o 10%</td><td>IVA 10%</td><td>IVA 11% o 10% No Acreditable</td><td>IVA 16% o 15% Importaci&oacute;n</td><td>IVA 16% o 15% No Acreditable Importaci&oacute;</td><td>IVA 11% o 10% Importaci&oacute;</td><td>IVA 11% o 10% No Acreditable Importaci&oacute;n</td><td>IVA Excento Importaci&oacute;n</td><td>IVA 0%</td><td>IVA Excento</td><td>IVA Retenido</td><td>IVA Devoluciones, Descuentos y Bonificaciones</td></tr>";
                while(!feof($txt))
                {
                        $linea = fgets($txt);
                        $linea = explode('|',$linea);
                        echo "<tr><td>".$linea[0]."</td><td>".$linea[1]."</td><td>".$linea[2]."</td><td>".$linea[3]."</td><td>".$linea[4]."</td><td>".$linea[5]."</td><td>".$linea[6]."</td><td>".$linea[7]."</td><td>".$linea[8]."</td><td>".$linea[9]."</td><td>".$linea[10]."</td><td>".$linea[11]."</td><td>".$linea[12]."</td><td>".$linea[13]."</td><td>".$linea[14]."</td><td>".$linea[15]."</td><td>".$linea[16]."</td><td>".$linea[17]."</td><td>".$linea[18]."</td><td>".$linea[19]."</td><td>".$linea[20]."</td><td>".$linea[21]."</td><td>".$linea[22]."</td></tr>";
                }
                fclose($txt);
                
        }

        echo "</table>";
}
?>
