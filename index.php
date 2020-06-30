<?php

include 'config.php';

$svgPath = $defaultPath;
if (file_exists($destinationPath)) {
  $svgPath = $destinationPath;
}

try {
  $dbh = new PDO($dsn, $user, $password);

  $sql = "select 
      id_khu,
      tenkhu 
    from khuvuc";

  $sth = $dbh->prepare($sql);
  $sth->execute();
  $result = $sth->fetchAll();

} catch (PDOException $e) {
  echo 'NO';
}

?>

<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bản Đồ Đất Thánh Vinh Đức</title>
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="css/bootstrap-fs-modal.min.css">
  <link href="css/ol.css" rel="stylesheet" />
  <link rel="stylesheet" href="css/style.css">
</head>

<body class="page-home">
  <header>
    <h1 class="text-center">Bản Đồ Đất Thánh Vinh Đức</h1>
    <ul>
      <?php foreach($result as $value): ?>
      <li><a href="map.php?area=<?php echo $value['id_khu'] ?>&name=<?php echo urlencode($value['tenkhu']); ?>"><?php echo $value['tenkhu']; ?></a></li>
      <?php endforeach; ?>
    </ul>
    <form class="col-4">
      <input id="txtName" type="text" placeholder="Enter name" />
      <button id="btnSearch" class="btn btn-primary">Search</button>
    </form>
  </header>
  <div class="container-fluid">
    <div id="svg-container" class="row"></div>
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
  <script src="js/fs-modal.min.js"></script>
  <script src="js/ol.js"></script>
  <script type="text/javascript">
    const svgPath = '<?php echo $svgPath ?>';
  </script>
  <script src="js/index.js"></script>
</body>

</html>