<?php 

  class Encriptacion
  {
      private $vectorInicial = "trmpcp2136032510";
      private $llaveSecreta = "VNM3tw0@#7qP3Dev";

      function __construct()
      {

      }

      function encriptar($texto) {
        $vectorInicial = $this->vectorInicial;

        $encriptador = mcrypt_module_open('rijndael-128', '', 'cbc', $vectorInicial);
        mcrypt_generic_init($encriptador, $this->llaveSecreta, $vectorInicial);
        $encriptacion = mcrypt_generic($encriptador, $texto);
        mcrypt_generic_deinit($encriptador);
        mcrypt_module_close($encriptador);

        return bin2hex($encriptacion);
      }

      function desencriptar($texto) {
        $texto = $this->hex2bin($texto);
        $vectorInicial = $this->vectorInicial;

        $desencriptador = mcrypt_module_open('rijndael-128', '', 'cbc', $vectorInicial);
        mcrypt_generic_init($desencriptador, $this->llaveSecreta, $vectorInicial);
        $desencriptacion = mdecrypt_generic($desencriptador, $texto);
        mcrypt_generic_deinit($desencriptador);
        mcrypt_module_close($desencriptador);

        return utf8_encode(trim($desencriptacion));
      }

      protected function hex2bin($hexadecimal) {
        $binario = '';

        for ($i = 0; $i < strlen($hexadecimal); $i += 2) {
          $binario .= chr(hexdec(substr($hexadecimal, $i, 2)));
        }

        return $binario;
      }

  }

?>