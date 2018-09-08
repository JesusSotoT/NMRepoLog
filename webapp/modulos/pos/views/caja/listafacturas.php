<?php  
echo '<table class="table table-hover table-fixed" style="background-color:#F9F9F9; border:1px solid #c8c8c8;" id="tableGrid">
    <thead>
      <tr>
        <th><div id="allBut"><button id="btnaf90" class="btn btn-primary" onclick="selAll();">Todas</button></div></th>
        <th>ID</th>
        <th>Tipo</th>
        <th>Fecha</th>
        <th>RFC</th>
        <th>Cliente</th>
        <th>UUID</th>
        <th>Folio</th>
        <th>ID Venta</th>

        <th>Total</th>
        <th>Empleado</th>
        <th>Sucursal</th>
        <th>Origen</th>
        <th>Estatus</th>

        <th>Acciones</th>

      </tr>
    </thead>
    <tbody>';
        $status="";
        $acuse = "";
        $proviene = "";
        $nota = '';
        foreach ($facturas as $key => $value) {
            $azurian=base64_decode($value['cadenaOriginal']);
            $azurian = str_replace("\\", "", $azurian);
            if($azurian!=''){
                $azurian=json_decode($azurian);
            }
            $azurian = $this->object_to_array($azurian);
            //print_r($azurian);
            //echo $arreglo;
            if($value['borrado']==0){
                $status = '<span class="label label-success" style="display:block;">Activa</span>';
                $acuse = '<a class="btn btn-default" alt="Cancelar factura" title="Cancelar factura" onclick="cancelar('.$value['id'].');"><i class="fa fa-times" aria-hidden="true"></i></a>';
            }elseif($value['borrado']==2){
                $status = '<span class="label label-warning" style="display:block;">Con Nota</span>';
            }else{
                $status = '<span class="label label-danger" style="display:block;">Cancelada</span>';
                $acuse = '<a class="btn btn-default" alt="Acuse de cancelación" title="Acuse de cancelación" onclick="verAcuse('.$value['id'].');"><i class="fa fa-sticky-note-o" aria-hidden="true"></i></a>';
            }
            if($azurian['Basicos']['version']=='3.2'){

            
                $uuid = "'".$value['folio']."'";
                $totalFactura = $azurian['Basicos']['total'];

                $saldo = '';
                if(intval($conexion_acontia['conectar_acontia']))
                {

                    $importes = $this->sumaImportesFacturas($value['folio']);
                    if(floatval($importes) >= floatval($value['saldocxc']))
                    {
                        if(floatval($importes) >= floatval($azurian['Basicos']['total']))
                            $saldo = '<span class="label label-default" style="display:block; margin-top:2px; cursor:pointer;" onclick="relacion_facturas_pendientes(1, '.$uuid.', '.$importes.');">Saldada '.$importes.' / '.$value['saldocxc'].'</span>';
                        else
                            $saldo = '<span class="label label-warning" onclick="relacion_facturas_pendientes(1, '.$uuid.', '.$importes.');" style="display:block; margin-top:2px; background-color:#E45D05; cursor:pointer;">Pagos Pendientes '.$importes.' / '.$value['saldocxc'].'</span>';
                    }
                    else
                    {
                        if(floatval($value['saldocxc']) >= floatval($azurian['Basicos']['total']))
                            $saldo = '<span class="label label-default" style="display:block; margin-top:2px; cursor:pointer;" onclick="relacion_facturas_pendientes(2, '.$uuid.', '.$value['saldocxc'].');">Saldada '.$importes.' / '.$value['saldocxc'].'</span>';
                        else
                            $saldo = '<span class="label label-warning" onclick="relacion_facturas_pendientes(2, '.$uuid.', '.$value['saldocxc'].');" style="display:block; margin-top:2px; background-color:#E45D05; cursor:pointer;">Pagos Pendientes '.$importes.' / '.$value['saldocxc'].'</span>';
                    }
                }

                if($value['origen']==1){ // comercial
                    $proviene = 'Envios';
                }else{
                    $proviene = 'Caja';
                    /*
                    if($value['proviene']==0){
                        $proviene = "Caja";
                    } elseif($value['proviene']==1) {
                        $proviene = "Kiosko";
                    }else{
                        $proviene = 'Layout';                                 
                    } 
                    */
                }
                

                if($value['tipoComp']=='F'){
                    $nota = '<a class="btn btn-default" alt="Crear nota de crédito" title="Crear nota de crédito" onclick="notaCredito('.$value['id'].');"><i class="fa fa-file-text-o"" aria-hidden="true"></i></a>';
                }else{
                    $nota= '';
                }

                echo '<tr uuid="'.$value['folio'].'">';
                echo '<td><input type="checkbox" class="checkPro" value="'.$value['folio'].'"></td>';
                echo '<td>'.$value['id'].'</td>';
                echo '<td>'.( $value['tipoComp'] == "C" ? "NC" : $value['tipoComp'] ).'</td>';
                echo '<td>'.$value['fecha'].'</td>';
                echo '<td>'.$azurian['Receptor']['rfc'].'</td>';
                echo '<td>'.$azurian['Receptor']['nombre'].'</td>';
                echo '<td>'.$value['folio'].'</td>';
                echo '<td>'.$azurian['Basicos']['folio'].'</td>';
                if($value['idSale'] == 0){
                    $string = '';
                    $ven = $this->CajaModel->facVentas($value['id']);
                    
                    foreach ($ven['ventas'] as $keyVen => $valueVen) {
                       $string.=$valueVen['id_sale'].',';
                    }
                    echo '<td><a href="#" data-toggle="tooltip" data-placement="bottom" title="'.trim($string,',').'">Ventas</a></td>';
                }else{
                     echo '<td>'.$value['idSale'].'</td>';
                }

                echo '<td>$'.$azurian['Basicos']['total'].'</td>';
                 echo '<td>'.$value['empleado'].'</td>';
                  echo '<td>'.$value['sucursal'].'</td>';
                echo '<td>'.$proviene.'</td>';
                echo '<td><center>'.$status.' '. $saldo .'</center></td>';


                echo "<td><a class='btn btn-default' alt='Visualizar PDF' title='Visualizar PDF' onclick='verPdf(\"".$value['folio']."\");'><i class='fa fa-file-pdf-o' aria-hidden='true'></i></a>";

                echo '<a class="btn btn-default" alt="Visualizar XML" title="Visualizar XML" onclick="verXml(\''.$value['folio'].'\');"><i class="fa fa-file-code-o" aria-hidden="true"></i></a>'.$nota.$acuse.'<a class="btn btn-default" alt="Reenviar por correo" title="Reenviar por correo" onclick="enviaFact(\''.$value['folio'].'\',\''.$azurian['Correo']['Correo'].'\');"><i class="fa fa-envelope-o" aria-hidden="true"></i></a></td>';
                echo '</tr>';
        }else{
                $uuid = "'".$value['folio']."'";
                $totalFactura = $azurian['Basicos']['Total'];

                $saldo = '';
                if(intval($conexion_acontia['conectar_acontia']))
                {

                    $importes = $this->sumaImportesFacturas($value['folio']);
                    if(floatval($importes) >= floatval($value['saldocxc']))
                    {
                        if(floatval($importes) >= floatval($azurian['Basicos']['total']))
                            $saldo = '<span class="label label-default" style="display:block; margin-top:2px; cursor:pointer;" onclick="relacion_facturas_pendientes(1, '.$uuid.', '.$importes.');">Saldada '.$importes.' / '.$value['saldocxc'].'</span>';
                        else
                            $saldo = '<span class="label label-warning" onclick="relacion_facturas_pendientes(1, '.$uuid.', '.$importes.');" style="display:block; margin-top:2px; background-color:#E45D05; cursor:pointer;">Pagos Pendientes '.$importes.' / '.$value['saldocxc'].'</span>';
                    }
                    else
                    {
                        if(floatval($value['saldocxc']) >= floatval($azurian['Basicos']['total']))
                            $saldo = '<span class="label label-default" style="display:block; margin-top:2px; cursor:pointer;" onclick="relacion_facturas_pendientes(2, '.$uuid.', '.$value['saldocxc'].');">Saldada '.$importes.' / '.$value['saldocxc'].'</span>';
                        else
                            $saldo = '<span class="label label-warning" onclick="relacion_facturas_pendientes(2, '.$uuid.', '.$value['saldocxc'].');" style="display:block; margin-top:2px; background-color:#E45D05; cursor:pointer;">Pagos Pendientes '.$importes.' / '.$value['saldocxc'].'</span>';
                    }
                }

                if($value['origen']==1){ // comercial
                    $proviene = 'Envios';
                }else{
                    $proviene = 'Caja';
                    /*
                    if($value['proviene']==0){
                        $proviene = "Caja";
                    } elseif($value['proviene']==1) {
                        $proviene = "Kiosko";
                    }else{
                        $proviene = 'Layout';                                 
                    } 
                    */
                }
                

                if($value['tipoComp']=='F'){
                    $nota = '<a class="btn btn-default" alt="Crear nota de crédito" title="Crear nota de crédito" onclick="notaCredito('.$value['id'].');"><i class="fa fa-file-text-o"" aria-hidden="true"></i></a>';
                }else{
                    $nota= '';
                }

                echo '<tr uuid="'.$value['folio'].'">';
                echo '<td><input type="checkbox" class="checkPro" value="'.$value['folio'].'"></td>';
                echo '<td>'.$value['id'].'</td>';
                echo '<td>'.( $value['tipoComp'] == "C" ? "NC" : $value['tipoComp'] ).'</td>';
                echo '<td>'.$value['fecha'].'</td>';
                echo '<td>'.$azurian['Receptor']['Rfc'].'</td>';
                echo '<td>'.$azurian['Receptor']['Nombre'].'</td>';
                echo '<td>'.$value['folio'].'</td>';
                echo '<td>'.$azurian['Basicos']['Folio'].'</td>';
                if($value['idSale'] == 0){
                    $string = '';
                    $ven = $this->CajaModel->facVentas($value['id']);
                    
                    foreach ($ven['ventas'] as $keyVen => $valueVen) {
                       $string.=$valueVen['id_sale'].',';
                    }
                    echo '<td><a href="#" data-toggle="tooltip" data-placement="bottom" title="'.trim($string,',').'">Ventas</a></td>';
                }else{
                     echo '<td>'.$value['idSale'].'</td>';
                }


                echo '<td>$'.$azurian['Basicos']['Total'].'</td>';
                echo '<td>'.$value['empleado'].'</td>';
                echo '<td>'.$value['sucursal'].'</td>';
                echo '<td>'.$proviene.'</td>';
                echo '<td><center>'.$status.' '. $saldo .'</center></td>';


                echo "<td><a class='btn btn-default' alt='Visualizar PDF' title='Visualizar PDF' onclick='verPdf(\"".$value['folio']."\");'><i class='fa fa-file-pdf-o' aria-hidden='true'></i></a>";

                echo '<a class="btn btn-default" alt="Visualizar XML" title="Visualizar XML" onclick="verXml(\''.$value['folio'].'\');"><i class="fa fa-file-code-o" aria-hidden="true"></i></a>'.$nota.$acuse.'<a class="btn btn-default" alt="Reenviar por correo" title="Reenviar por correo" onclick="enviaFact(\''.$value['folio'].'\',\''.$azurian['Correo']['Correo'].'\');"><i class="fa fa-envelope-o" aria-hidden="true"></i></a></td>';
                echo '</tr>';
        }
        }
    
    echo '</tbody>
</table>';