<head>
    <link rel="stylesheet" type="text/css" href="../../libraries/select2/dist/css/select2.min.css">
    <link rel="stylesheet" type="text/css" href="../../libraries/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/datatablesboot.min.css">
    <link rel="stylesheet" type="text/css" href="css/bootstrap-datepicker.min.css"> 

    <link rel="stylesheet" href="../../libraries/morris.js-0.5.1/morris.css">


</head>

   <!-- ch@isystem - Librerias genericas -->
  <script src="../../libraries/jquery.min.js" type="text/javascript"></script>
  <script src="../../libraries/bootstrap/dist/js/bootstrap.min.js" type="text/javascript"></script>
  <script src="../../libraries/select2/dist/js/select2.min.js" type="text/javascript"></script>

  <script src="../../libraries/morris.js-0.5.1/raphael.min.js"></script>
  <script src="../../libraries/morris.js-0.5.1/morris.min.js"></script>
  
  <!-- ch@isystem- Librerias raiz transport -->
  <!-- <script src="js/dataTables.bootstrap.min.js" type="text/javascript"></script>-->
  <script src="js/datatables.min.js" type="text/javascript"></script>
  <script src="js/bootstrap-datepicker.min.js" type="text/javascript"></script>
  <script src="js/funciones_gen.js" type="text/javascript"></script>

  <div class="container divCenter text-center panel panel-default">
    <h2>CANCELACIONES</h2><br/>
  </div>
  


<h1>Bar charts</h1>
<div id="graph"></div>
<pre id="code" class="prettyprint linenums">
// Use Morris.Bar
Morris.Bar({
  element: 'graph',
  data: [
    {x: '2011 Q1', y: 0},
    {x: '2011 Q2', y: 1},
    {x: '2011 Q3', y: 2},
    {x: '2011 Q4', y: 3},
    {x: '2012 Q1', y: 4},
    {x: '2012 Q2', y: 5},
    {x: '2012 Q3', y: 6},
    {x: '2012 Q4', y: 7},
    {x: '2013 Q1', y: 8}
  ],
  xkey: 'x',
  ykeys: ['y'],
  labels: ['Y'],
  barColors: function (row, series, type) {
    if (type === 'bar') {
      var red = Math.ceil(255 * row.y / this.ymax);
      return 'rgb(' + red + ',0,0)';
    }
    else {
      return '#000';
    }
  }
});
</pre>
</body>
