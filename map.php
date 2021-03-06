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
    <form class="col-4">
      <input id="txtName" type="text" placeholder="Enter name" />
      <button id="btnSearch" class="btn btn-primary">Search</button>
    </form>
  </header>
  <div class="container-fluid">
    <div id="map" class="row"></div>
  </div>

  <div class="modal modal-fullscreen fade" id="infoModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">Thông tin</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group" id="groupData">
            
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal modal-fullscreen fade" id="biaModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Thông tin</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group" id="detailBody">
            
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
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