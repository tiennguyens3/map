<?php

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
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
  <link rel="stylesheet" href="style.css">
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
          <input type="text" class="form-control" id="idInput" placeholder="Enter ID">
        </div>
        <div class="form-group">
          <input type="text" class="form-control" id="nameInput" placeholder="Enter Name">
        </div>
        
          <div class="custom-control custom-radio custom-control-inline">
            <input type="radio" class="custom-control-input" name="typeInput" id="maleInput" value="male">
            <label class="custom-control-label" for="maleInput">Male</label>
          </div>

          
          <div class="custom-control custom-radio custom-control-inline">
            <input type="radio" class="custom-control-input" name="typeInput" id="feMaleInput" value="female">
            <label class="custom-control-label" for="feMaleInput">Female</label>
          </div>

          
          <div class="custom-control custom-radio custom-control-inline">
            <input type="radio" class="custom-control-input" name="typeInput" id="kidInput" value="kid">
            <label class="custom-control-label" for="kidInput">Kid</label>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" id="saveChanges" class="btn btn-primary">Save changes</button>
        </div>
      </div>
    </div>
  </div>

  <script src="https://ariutta.github.io/svg-pan-zoom/dist/svg-pan-zoom.js"></script>
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  <script>
    $(function () {
      svgPanZoom('#svg-container svg', {
        zoomEnabled: true,
        controlIconsEnabled: true,
        fit: true,
        center: true,
        minZoom: 0.1
      });
    })

    var polyline = null;
    $('polyline').click(function() {
      polyline = $(this);
      $('#editorModal').modal('show');
    });

    $('#saveChanges').click(function() {
      polyline.attr('id', $('#idInput').val());
      polyline.attr('name', $('#nameInput').val());
      polyline.attr('type', $('input[name="typeInput"]:checked').val());

      $('#idInput').val("");
      $('#nameInput').val("");

      $('#editorModal').modal('hide');
    });

    $('#saveBtn').click(function(event) {
      event.preventDefault();
      $('#content').val($('#svg-container').html());

      $('form').submit();
    });

  </script>
</body>

</html>