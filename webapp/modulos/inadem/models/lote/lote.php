<?php
//ini_set('display_errors', 1);
require("models/connection_sqli.php"); // funciones mySQLi

class loteModel extends Connection {
     
    public function __construct() {
        session_start();
      /*  cotizacionModel::simple();
        cotizacionModel::propina();
        cotizacionModel::sessiooon();
        //unset($_SESSION["sucursal"]);
        //unset($_SESSION["caja"]);
        //unset($_SESSION["simple"]);
        //$_SESSION["simple"] = true; */
    }   
        function imprimeGrid($idCliente){
            $grid = "SELECT l.idLote, l.idOrdeCom, p.nombre, l.cantidad, l.fecha_recibido, l.fecha_caducidad from mrp_lote l, mrp_producto p where l.idProducto=p.idProducto";
            $result1 = $this->queryArray($grid);

            return array('grid' => $result1["rows"]);
            
        } 
        function datosLote($idLote){
            $selectDatos = "SELECT l.idLote, l.idOrdeCom, p.nombre, l.cantidad, l.fecha_recibido, l.fecha_caducidad, l.idProducto from mrp_lote l, mrp_producto p where l.idProducto=p.idProducto and l.idLote=".$idLote;
            $result1 = $this->queryArray($selectDatos);

            return array('datos' => $result1["rows"]);
        } 
        function series($idLote){
            $selectSeries = "SELECT serie, idCliente from mrp_lote_series where idLote=".$idLote;
            $result = $this->queryArray($selectSeries);

            return array('series' => $result["rows"]);

        }
        function addSerie($idLote,$idProducto,$serie,$cantidad){
            $numReg = "SELECT count(serie) as total from mrp_lote_series where idLote=".$idLote;
            $result = $this->queryArray($numReg);

            $totalReg = $result['rows'][0]['total']*1;
            $cantidad = $cantidad*1;
            if($totalReg==$cantidad){
                return array('status' => false, 'serie' => $serie);
            }else{
                $insertSerie = "INSERT INTO mrp_lote_series (idLote,idProducto,serie) VALUES ('".$idLote."','".$idProducto."','".$serie."')";
                $result1 = $this->query($insertSerie);

                return array('status' => true, 'serie' => $serie);
            }

        }
        function cargaSelects(){
            $selectLote = "SELECT idLote from mrp_lote";
            $result1 = $this->queryArray($selectLote);

            $selctProducto = "SELECT distinct p.nombre,l.idProducto from mrp_producto p, mrp_lote l where p.idProducto=l.idProducto";
            $result2 = $this->queryArray($selctProducto);

            return array('lote' => $result1['rows'], 'producto' => $result2['rows']);
        }
        function busca($idLote,$idProducto,$desde,$hasta){

            $filtro='';
            if($idLote!=0){
                $filtro .=' and l.idLote='.$idLote; 
            }
            if($idProducto!=0){
                $filtro .=' and l.idProducto='.$idProducto;
            }
            if($desde!='' && $hasta!=''){
                $filtro .=" and fecha_recibido between '".$desde."' and '".$hasta."' ";
            }

            $grid = "SELECT l.idLote, l.idOrdeCom, p.nombre, l.cantidad, l.fecha_recibido, l.fecha_caducidad from mrp_lote l, mrp_producto p"; 
            $grid.=" where l.idProducto=p.idProducto".$filtro;

            $result1 = $this->queryArray($grid);

            return array('grid' => $result1["rows"]);
        }










}