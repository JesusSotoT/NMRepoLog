<?php 

class db{

  private $dbhost;
  private $dbuser;
  private $dbpass;
  private $dbname;
  private $conn;

//En el constructor de la clase establecemos los parámetros de conexión con la base de datos

  function __construct($dbuser = 'nmdevel', $dbpass = 'nmdevel', $dbname = '_dbmlog0000000987', $dbhost = 'nmdb.cyv2immv1rf9.us-west-2.rds.amazonaws.com'){

    $this->dbhost = $dbhost;
    $this->dbuser = $dbuser;
    $this->dbpass = $dbpass;
    $this->dbname = $dbname;
    $this->abrir($dbhost,$dbuser,$dbpass,$dbname);

  }

//El método abrir establece una conexión con la base de datos

  public function abrir(){

    $this->conn = mysqli_connect($this->dbhost, $this->dbuser, $this->dbpass,$this->dbname);
    if (mysqli_connect_errno()) {
      die('Error al conectar con mysql');
    }

  }

//El método "consulta" ejecuta la sentencia select que recibe por parámetro "$query" a la base de datos y devuelve un array asociativo con los datos que obtuvo de la base de datos para facilitar su posteiror manejo.

  public function consulta($query){
    $valores = array();

    $result = mysqli_query($this->conn,$query);
    if (!$result) {
      die('Error query BD:' . mysqli_error());
    }else{
      $num_rows= mysqli_num_rows($result);
      for($i=0;$i<$num_rows;$i++){
        $row = mysqli_fetch_assoc($result);
        array_push($valores, $row);
      }
    }

    return $valores;
  }

//La función sql nos permite ejecutar una senetencia sql en la base de datos, se suele utilizar para senetencias insert y update.

  public function sql($sql){
    $resultado=mysqli_query($this->conn,$sql);
    return $resultado;
  }

//La función id nos devuelve el identificador del último registro insertado en la base de datos

  public function id(){
    return mysqli_insert_id($this->conn);
  }

  public function cerrar(){
    mysqli_close($this->conn);
  }

  public function escape($value){
    return mysqli_real_escape_string($this->conn,$value);
  }

}
$conexion = new db;
//$link = mysqli_connect("nmdb.cyv2immv1rf9.us-west-2.rds.amazonaws.com","nmdevel","nmdevel","nmdev") or die("Error " . mysqli_error($link)); 
  $cont = 0;
  $ventas = $conexion->consulta("SELECT * from venta where documento=2 and fecha between '2015-01-01' and '2015-12-30'");
  //var_dump($ventas);
  echo '<h1>Ventas Facuradas</h1>';
  echo '<table border=1><tr><td>idVenta</td><td>idFactura</td><td>Folio(UUID)</td><td>Status</td><td>Nombre</td><td>RFC</td><td>Monto</td><td>Fecha</td>';
      foreach ($ventas as $key => $value) {
         // echo $value['idVenta'].'<br />';
        echo '<tr>';
        echo '<td>'.$value['idVenta'].'</td>';
        $facturas = $conexion->consulta("SELECT id,folio,idSale,fecha,borrado from pvt_respuestaFacturacion where idSale=".$value['idVenta']);
        if($facturas[0]['folio']=='' && $facturas[0]['id']==''){
          echo "<td bgcolor='#F6FC7C'>Se mando a facturar sin respuesta</td><td></td><td></td>";
          $cont ++;
        }else{
          echo "<td>".$facturas[0]['id']."</td>";
          echo "<td>".$facturas[0]['folio']."</td>";
          if($facturas[0]['borrado']==3){
            echo "<td><font color='red'>Cancelada</font></td>";
          }else{
            echo "<td><font color='green'>Vigente</font></td>";
          }
        }
        if($value['idCliente']!=''){
          $nombrecliente = $conexion->consulta("SELECT nombre from comun_cliente where id=".$value['idCliente']);
           echo "<td>".$nombrecliente[0]['nombre']."</td>";
          $rfc = $conexion->consulta("select rfc from comun_facturacion where nombre=".$value['idCliente']);
          if($rfc[0]['rfc']!=''){
            echo "<td>".$rfc[0]['rfc']."</td>";
          }else{
            echo "<td></td>";
          }
        }else{
          echo "<td></td>";
          echo "<td></td>";
        }
        
        
        echo "<td>$".$value['monto']."</td>";
        echo "<td>".$value['fecha']."</td>";
        //var_dump($facturas);
      }
  echo "</table>";
  echo 'Existen  '.$cont.' ventas que se facturaron y no hay respuesta';
?>
<table>
    <tr></tr>
</table>