<?php

include 'config.php';

const AREA_DEFAULT = 7;
const NAME_DEFAULT = "Khu A";

$area = isset($_GET['area']) ? $_GET['area'] : AREA_DEFAULT;
$name = isset($_GET['name']) ? $_GET['name'] : NAME_DEFAULT;
$svgPath = "svg/khu_". $area .".svg";

?>

<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bản Đồ Đất Thánh Vinh Đức <?php echo $name ?></title>
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link href="css/ol.css" rel="stylesheet" />
  <link rel="stylesheet" href="css/style.css">
</head>

<body class="page-home">
  <header>
    <h1 class="text-center">Bản Đồ Đất Thánh Vinh Đức <?php echo $name ?></h1>
  </header>
  <div class="container-fluid">
    <div id="map" class="row"></div>
  </div>

  <script src="js/jquery-3.5.1.min.js"></script>
  <script src="js/popper.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/ol.js"></script>
  <script type="text/javascript">
    const svgPath = '<?php echo $svgPath ?>';
  </script>
  <script src="js/map.js"></script>
</body>

</html>