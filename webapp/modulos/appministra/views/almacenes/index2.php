<script src='../../libraries/jquery.min.js' type='text/javascript'></script>
    <script>
    <?php 
    require 'js/almacenes.js.php';
    ?>
    </script>
    <style>
        li>img{
                margin-right: 10px;
            }
        ul{
            margin:0; 
            padding-left:15px; 
            text-transform: uppercase;
        }
        [id=cont] li{
            list-style: none;
            }
    </style>
</head>
<body>
        <div id="content">
            <div id='srch' style="margin-top:5px;">
                <label for="search" >Buscar: </label> 
                <input type="text" class='rounded nmcatalogbusquedainputtext' style='width:auto' id='search'>
            </div>  
            <br>        
            <hr class='separator' />
            <div class="left" id='cont' style='width:500px;height:600px;overflow:scroll;'>
                <ul></ul>
            </div>
</div>

<div id='blanca' style='background-color:white;position:absolute;top:130px;width:100%;height:100%;font-size:20px;color:green;z-index:999;'>Cargando Información...</div>