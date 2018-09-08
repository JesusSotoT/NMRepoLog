<!doctype html>
<html>
<head>
  <title>ComuniLog</title>
  <style>
        .wrap {
              perspective: 800px;
              perspective-origin: 50% 100px;
        }

        .cube {
            position: relative;
            width: 200px;
            transform-style: preserve-3d;
        }

        .cube div {
              position: absolute;
              width: 300px;
              height: 300px;
              background: rgba(255,255,255,0.8);
              box-shadow: inset 0 0 30px rgba(125,125,125,0.8);
              font-size: 20px;
              text-align: center;
              line-height: 200px;
              color: rgba(0,0,0,0.5);
              font-family: sans-serif;
              text-transform: uppercase;
          }

        .back { 
            transform: translateZ(-150px) rotateY(180deg);
        }
        .right {
            transform: rotateY(-270deg) translateX(150px);
            transform-origin: top right;
        }
        .left {
          transform: rotateY(270deg) translateX(-150px);
          transform-origin: center left;
        }
        .top {
          transform: rotateX(-90deg) translateY(-150px);
          transform-origin: top center;
        }
        .bottom {
          transform: rotateX(90deg) translateY(150px);
          transform-origin: bottom center;
        }
        .front {  
          transform: translateZ(150px);
        }

        @keyframes spin {
          from { transform: rotateY(0); }
          to { transform: rotateY(360deg); }
        }

        .cube {
          animation: spin 10s infinite linear;
        }
        



  </style>
  <script type="text/javascript" src="prefixfree.min.js"></script>
</head>

<body>
<br /><br /><br /><br />

<center>
<div class="wrap">
  <div class="cube">
    <div class="front">
     
      <iframe src="//player.vimeo.com/video/72845323?portrait=0" width="300" height="169" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe> 

    </div>
    <div class="back">

          <!-- 
          <iframe frameborder="0" width="300" height="169" src="//www.dailymotion.com/embed/video/x8jrau" allowfullscreen></iframe><br /><a href="http://www.dailymotion.com/video/x8jrau_hector-polo-noticia-090225-sedeco-p_news" target="_blank">Hector Polo, Noticia: 090225 sedeco protege</a> <i>por <a href="http://www.dailymotion.com/hector_polo" target="_blank">hector_polo</a></i> 
          -->
          
    </div>
    <div class="top">
     TOP 

    </div>
    <div class="bottom">bottom</div>
    <div class="left">left
      <button onclick="alert('Hola');">otro</button>
    </div>
    <div class="right">right</div>
  </div>
</div>
</center>

</body>
