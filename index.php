<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SVG</title>
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="style.css">
</head>

<body class="page-home">
  <header>
    <h1 class="text-center">SVG</h1>
    <form>
      <input id="txtName" type="text" placeholder="Enter name" />
      <button id="btnSearch" class="btn btn-primary">Search</button>
    </form>
  </header>
  <div class="container-fluid">
    <div id="svg-container" class="row">
      <?php 
        $fileName = 'original.svg';
        if (file_exists('destination.svg')) {
          $fileName = 'destination.svg';
        }
        echo(file_get_contents($fileName));
      ?>
    </div>
  </div>

  <script src="js/svg-pan-zoom.js"></script>
  <script src="js/jquery-3.5.1.min.js"></script>
  <script src="load.js"></script>
</body>

</html>