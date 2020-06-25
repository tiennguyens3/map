<?php

include 'config.php';

const REPLACE_ME = "[REPLACE_ME]";

try {
    $dbh = new PDO($dsn, $user, $password);

    $sql = "select 
        id_nguoimat, 
        kv.tenkhu as khuvuc,
        kv.id_khu as idkhuvuc,
        hang_khuvuc, 
        thutu_nguoimat, 
        tennguoimat 
      from nguoimat nm
      inner join khuvuc kv on kv.id_khu=nm.khuvuc";

    $result = [];
    foreach ($dbh->query($sql) as $value) {
      $result[$value['idkhuvuc']][$value['id_nguoimat']] = [
        $value['id_nguoimat'], 
        $value['tennguoimat'],
        $value['khuvuc'],
        $value['hang_khuvuc'],
        $value['thutu_nguoimat'],
      ];
    }

    $sql = "select * from khuvuc";
    $sth = $dbh->prepare($sql);
    $sth->execute();
    $areas = $sth->fetchAll();

    $data = [];
    if (isset($areas[0])) {
      $areaId  = $areas[0]['id_khu'];
      $data = isset($result[$areaId]) ? $result[$areaId] : [];
    }

    function handlePlot($id, $svg) {
      global $dbh;
      $sql = "select * from svgplot where userId=:id";
      $sth = $dbh->prepare($sql);
      $sth->execute(array(':id' => $id));
      $row = $sth->fetch();
  
      if ($row) {
          $sql = "update svgplot set svg=:svg where userId=:userId";
      } else {
          $sql = "insert into svgplot(userId, svg) values (:userId, :svg)";
      }
      $sth = $dbh->prepare($sql);
      $sth->execute(array(':userId' => $id, ':svg' => $svg));
    }

    function generateSVG($area) {
      global $dbh;
      $fileName = "svg/template_" . $area . ".svg";
      $content = file_get_contents($fileName);

      $sql = "select 
        p.id, 
        svg 
        from svgplot p 
        inner join nguoimat nm on nm.id_nguoimat=p.userId
        where nm.khuvuc=:area";

      $sth = $dbh->prepare($sql);
      $sth->execute(array(':area' => $area));
      $result = $sth->fetchAll();

      $fileTemp = "svg/temp.svg";
      foreach($result as $value) {
        file_put_contents($fileTemp, $value['svg'], FILE_APPEND);
      }

      $temp = file_get_contents($fileTemp);
      $data = str_replace(REPLACE_ME, $temp, $content);

      $fileName = "svg/khu_" . $area . ".svg";
      file_put_contents($fileName, $data);

      // Reset temp file
      file_put_contents($fileTemp, "");
    }

} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}

if (isset($_POST['content'])) {
  $content = $_POST['content'];

  $fileName = 'svg/destination.svg';
  if ($content) {
    file_put_contents($fileName, $content);

    $plots = isset($_POST['plots']) ? $_POST['plots'] : '';
    if ($plots) {
      foreach($plots as $id => $svg) {
        handlePlot($id, $svg);
      }
    }

    $area = isset($_POST['area']) ? $_POST['area'] : 0;
    if ($area) {
      generateSVG($area);
    }
  } else {
    unlink($fileName);
  }
}

$svgPath = $defaultPath;
if (file_exists($destinationPath)) {
  $svgPath = $destinationPath;
}

?>

<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Thiết Lập Bản Đồ Đất Thánh Vinh Đức</title>
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link href="css/bootstrap-select.min.css" rel="stylesheet" />
  <link href="css/ol.css" rel="stylesheet" />
  <link href="css/style.css" rel="stylesheet" >
</head>

<body class="page-home">
  <header>
    <h1 class="text-center">Thiết Lập Bản Đồ Đất Thánh Vinh Đức</h1>
    <form id="submitForm" method="post" class="col-2">
      <div class="form-group">
        <label for="areaSelect">Chọn khu</label>
        <select class="form-control" id="areaSelect" name="area">
          <?php foreach ($areas as $area): ?>
          <option value="<?php echo $area['id_khu'] ?>"><?php echo $area['tenkhu'] ?></option>
          <?php endforeach ?>
        </select>
      </div>
      <input type="hidden" id="content" name="content" />
      <button id="saveBtn" class="btn btn-primary">Save</button>
      <button id="reset" name="reset" class="btn btn-warning">Reset</button>
    </form>
  </header>
  <div class="container-fluid">
    <div id="svg-container" class="row">
    </div>
  </div>

  <div class="modal modal-fullscreen fade" id="editorModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">Informations</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">

        <div class="form-group">
          <select id="selectpicker" class="form-control selectpicker" data-live-search="true">
            <?php 
              foreach ($data as $value) {
                $text = "Khu vực: " . $value[2] . "- Hàng: " . $value[3] . "- STT: " . $value[4] . ", " . $value[1];
                echo "<option data-area=".$value[2]." data-row=".$value[3]." data-id=".$value[0]." data-name='".$value[1]."'>" . $text . "</option>";
              }
            ?>
          </select>
        </div>

        <div class="form-group">
          <input type="hidden" class="form-control" id="idInput" placeholder="Enter ID">
        </div>
        <div class="form-group">
          <input type="hidden" class="form-control" id="nameInput" placeholder="Enter Name">
        </div>
        
          <div class="custom-control custom-radio custom-control-inline">
            <input type="radio" class="custom-control-input" name="typeInput" id="maleInput" value="male" checked="checked">
            <label class="custom-control-label" for="maleInput">Male</label>
          </div>

          <div class="custom-control custom-radio custom-control-inline">
            <input type="radio" class="custom-control-input" name="typeInput" id="feMaleInput" value="female">
            <label class="custom-control-label" for="feMaleInput">Female</label>
          </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" id="saveChanges" class="btn btn-primary">Save changes</button>
        </div>
      </div>
    </div>
  </div>

  <script src="js/jquery-3.5.1.min.js"></script>
  <script src="js/popper.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/bootstrap-select.min.js"></script>
  <script src="js/ol.js"></script>
  <script type="text/javascript">
    const svgPath = '<?php echo $svgPath ?>';
    const data = <?php echo json_encode($result); ?>
  </script>
  <script src="js/editor.js"></script>
</body>

</html>