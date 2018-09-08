<?php 
error_reporting(E_ALL);
class dbconector{

  private $dbhost;
  private $dbuser;
  private $dbpass;
  private $dbname;
  private $conn;

//En el constructor de la clase establecemos los parámetros de conexión con la base de datos

  function __construct($dbuser = 'nmdevel', $dbpass = 'nmdevel', $dbname = 'nmdev', $dbhost = 'nmdb.cyv2immv1rf9.us-west-2.rds.amazonaws.com'){

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

?>