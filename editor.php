<?php

include 'config.php';

try {
    $dbh = new PDO($dsn, $user, $password);

    $sql = "select id_nguoimat, tennguoimat from nguoimat";
    $result = [];
    foreach ($dbh->query($sql) as $value) {
      $result[$value['id_nguoimat']] = [$value['id_nguoimat'], $value['tennguoimat']];
    }
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}

if (isset($_POST['content'])) {
  $content = $_POST['content'];

  $fileName = 'destination.svg';
  if ($content) {
    file_put_contents($fileName, $content);
  } else {
    unlink($fileName);
  }
}

?>

<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Editor</title>
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="style.css">
  <link href="css/bootstrap-select.min.css" rel="stylesheet" />
</head>

<body class="page-home">
  <header>
    <h1 class="text-center">Editor</h1>
    <form method="post">
      <input type="hidden" id="content" name="content" />
      <button id="saveBtn" class="btn btn-primary">Save</button>
      <button id="reset" name="reset" class="btn btn-primary">Reset</button>
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

  <div class="modal fade" id="editorModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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
              foreach ($result as $value) {
                echo "<option data-id=".$value[0]." data-name='".$value[1]."'>" . implode(", ", $value) . "</option>";
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

  <script src="js/svg-pan-zoom.js"></script>
  <script src="js/jquery-3.5.1.min.js"></script>
  <script src="js/popper.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/bootstrap-select.min.js"></script>
  
  <script>
    $(function () {
      svgPanZoom('#svg-container svg', {
        zoomEnabled: true,
        controlIconsEnabled: true,
        fit: true,
        center: true,
        minZoom: 0.1
      });

      var polyline = null;
      $('polyline').click(function() {
        polyline = $(this);

        if (polyline.attr('id')) {
          $('#idInput').val(polyline.attr('id'));
          $('option[data-id="'+ polyline.attr('id') +'"]').attr('selected', true);
        }

        if (polyline.attr('type')) {
          $('input[name="typeInput"][value="'+polyline.attr('type')+'"]').attr('checked', true);
        }

        $('.selectpicker').selectpicker('refresh');
        $('#editorModal').modal('show');
      });

      $('#saveChanges').click(function() {
        var id = $('.selectpicker :selected').data('id');
        var name = $('.selectpicker :selected').data('name');

        polyline.attr('id', id);
        polyline.attr('name', name);
        polyline.attr('type', $('input[name="typeInput"]:checked').val());

        polyline.append("<title>" + name + "</title>");

        $('#idInput').val("");
        $('#nameInput').val("");

        $('.selectpicker :selected').attr('disabled', true);
        $('.selectpicker').selectpicker('refresh');

        $('#editorModal').modal('hide');
      });

      $('#saveBtn').click(function(event) {
        event.preventDefault();

        var svg = $('#svg-container').clone();
        svg.find('#svg-pan-zoom-controls').remove();
        $('#content').val(svg.html());

        $('form').submit();
      });

      $('g[fill="white"] polyline[id]').each(function() {
        $('option[data-id="'+ $(this).attr('id') +'"]').attr('disabled', true);
      });

      $('.selectpicker').selectpicker();
    });

  </script>
</body>

</html>