<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SVG</title>
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="css/bootstrap-fs-modal.min.css">
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
        $fileName = 'khuA_alinh-01.svg';
        if (file_exists('destination.svg')) {
          $fileName = 'destination.svg';
        }
        echo(file_get_contents($fileName));
      ?>
    </div>
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

  <script src="js/svg-pan-zoom.js"></script>
  <script src="js/jquery-3.5.1.min.js"></script>
  <script src="js/popper.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/fs-modal.min.js"></script>
  <script src="js/hammer.js"></script>
  <script src="load.js"></script>
</body>

</html>