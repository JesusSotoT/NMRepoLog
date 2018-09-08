<?php
    session_start();
    $data=$_SESSION['ticketventaenv'];


$nimps=explode('<div class="row">', $data['imps']);
$table='<table>';
foreach ($nimps as $kk => $vv) {
    if($vv!=''){
        $table.='<tr>';
        $nimps2=explode('<div class="col-sm-6"><label>', $vv);

        foreach ($nimps2 as $kkk => $vvv) {
            $vvv=preg_replace('/(<\/label><\/div>)/', '', $vvv);
            $vvv=preg_replace('/(<\/div>)/', '', $vvv);
            $table.='<td style="text-align:right;">'.$vvv.'</td>';
        }
        $table.='</tr>';
    }
}
$table.='</table>';


 $html='<style>
@font-face {
  font-family: SourceSansPro;
  src: url(SourceSansPro-Regular.ttf);
}

.clearfix:after {
  content: "";
  display: table;
  clear: both;
}

a {
  color: #0087C3;
  text-decoration: none;
}

body {
  position: relative;
  width: 19cm;  
  height: 29.7cm; 
  margin: 0 auto; 
  color: #555555;
  background: #FFFFFF; 
  font-family: Arial, sans-serif; 
  font-size: 14px; 
  font-family: SourceSansPro;
}

header {
  padding: 10px 0;
  margin-bottom: 20px;
  border-bottom: 1px solid #AAAAAA;
}

#logo {
  float: left;
  margin-top: 8px;
}

#logo img {
  height: 70px;
}

#company {
  float: right;
  text-align: right;
}


#details {
  margin-bottom: 50px;
}

#client {
  padding-left: 6px;
  border-left: 6px solid #0087C3;
  float: left;

}

#client .to {
  color: #777777;
}

h2.name {
  font-size: 1.4em;
  font-weight: normal;
  margin: 0;
}

#invoice {
  float: right;
  text-align: right;

}

label{
    width:150px;
}

#invoice h1 {
  color: #0087C3;
  font-size: 2.4em;
  line-height: 1em;
  font-weight: normal;
  margin: 0  0 10px 0;
}

#invoice .date {
  font-size: 1.1em;
  color: #777777;
}

table {
  width: 100%;
  border-collapse: collapse;
  border-spacing: 0;
  margin-bottom: 20px;
  font-size:12px;
}

table th,
table td {
  padding: 8px;
  background: #EEEEEE;
  text-align: center;
  border-bottom: 1px solid #FFFFFF;
}

table th {
  white-space: nowrap;        
  font-weight: bold;
}

table td {
  text-align: right;
}

table td h3{
  color: #57B223;
  font-size: 1.2em;
  font-weight: normal;
  margin: 0 0 0.2em 0;
}

table .no {
  color: #000;
  background: #DDDDDD;
}

table .desc {
  text-align: left;
}

table .unit {
  background: #DDDDDD;
}

table .qty {
}

table .total {
  background: #333;
  color: #FFFFFF;
}

table td {word-wrap:break-word;}

table td.unit,
table td.qty,
table td.total {
  font-size: 1.2em;
}

table tbody tr:last-child td {
  border: none;
}

table tfoot td {
  padding: 10px 20px;
  background: #FFFFFF;
  border-bottom: none;
  font-size: 1.2em;
  white-space: nowrap; 
  border-top: 1px solid #AAAAAA; 
}

table tfoot tr:first-child td {
  border-top: none; 
}

table tfoot tr:last-child td {
  color: #57B223;
  font-size: 1.4em;
  border-top: 1px solid #57B223; 

}

table tfoot tr td:first-child {
  border: none;
}

#thanks{
  font-size: 2em;
  margin-bottom: 50px;
}

#notices{
  padding-left: 6px;
  border-left: 6px solid #0087C3;  
}

#notices .notice {
  font-size: 1.2em;
}

footer {
  color: #777777;
  width: 100%;
  height: 30px;
  position: absolute;
  bottom: 0;
  border-top: 1px solid #AAAAAA;
  padding: 8px 0;
  text-align: center;
}
</style><html><body>
    <div class="clearfix" style="border-bottom:1px solid #ddd; margin:10px 0 20px 0;">
      <div id="company">
        <h2 class="name">'.$data['reqdata']['empresa'].'</h2>
        '.$data['reqdata']['direccionempresa'].'<br>
        Appministra Software<br><br>
        <div><a href="mailto:netwaremonitor@netwarmonitor.com">netwaremonitor@netwaremonitor.com</a></div>
      </div>
    </div>
      <div class="clearfix" id="details">
        <div id="client">
          <div class="to">Cliente:</div>
          <h2 class="name">'.$data['reqdata']['nombre'].'</h2>
          <div class="address">'.$data['reqdata']['direccion'].'</div>
          <div class="email"><a href="mailto:'.$data['reqdata']['email'].'">'.$data['reqdata']['email'].'</a></div>
          <div class="address">&nbsp;</div>
          <div class="address">Ticket de venta: '.$data['reqdata']['cotizacion'].'</div>
          <div class="address">Fecha del ticket: '.$data['reqdata']['fecha'].'</div>
        </div>
      </div>
      <table width="100%" cellspacing="0" cellpadding="0" border="0">
        <thead>
          <tr>
            <th style="width:10px;" class="no">ID</th>
            <th style="width:50px;" class="desc">Codigo</th>
            <th style="width:180px;" class="desc">Producto</th>
            <th style="width:10px;" class="unit">Precio</th>
            <th style="width:10px;" class="qty">Cantidad</th>
            <th style="width:10px;" class="total">Sub Total</th>
          </tr>
        </thead>
        <tbody>';
        foreach ($data['prodata'] as $k => $v) {


           // $v['codigo'] = str_repeat($v['codigo'],50);
            //$v['codigo'] =  wordwrap($v['codigo'], 20, PHP_EOL, true);

          $html.='<tr>
            <td style="width:2px; text-align:center" class="no">'.$v['id'].'</td>
            <td style="width:60px;" class="desc">'.$v['codigo'].'</td>
            <td style="width:162px;" class="desc">'.$v['nomprod'].'</td>
            <td style="width:8px;" class="unit">$'.$v['costo'].'</td>
            <td style="width:8px;" class="qty">'.$v['cantidadr'].'</td>
            <td style="width:10px;" class="total">$'.($v['costo']*$v['cantidadr']).'</td>
          </tr>';
      }

          $html.='</tbody>
      </table>
      <div class="clearfix" id="details" style="margin-left:549px; margin-top:10px;">
        '.$table.'
      </div>
      </body></html>';
      $_SESSION['ticketventaenv']='';
unset($_SESSION['ticketventaenv']);
echo $html;