<?php

include 'config.php';

try {
    $dbh = new PDO($dsn, $user, $password);

    $sql = "select * from svgplot";
    $sth = $dbh->prepare($sql);
    $sth->execute();
    $result = $sth->fetchAll();
} catch (PDOException $e) {
    echo 'NO';
}

$area = "Khu 1";
$svgPath = "svg/khu_1.svg";

function callback($buffer) {
  global $svgPath;
  file_put_contents($svgPath, $buffer);
}

ob_start("callback");

?>

<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
        viewBox="0 0 3142.47 2276.63" style="enable-background:new 0 0 3142.47 2276.63;" xml:space="preserve">
    <style type="text/css">
        .st0{fill:#5ABA7B;}
        .st1{opacity:0.48;fill:#24B34B;stroke:#224C1C;stroke-width:5;stroke-miterlimit:10;}
        .st2{opacity:0.22;fill:#231F20;stroke:#231F20;stroke-width:0.2833;stroke-miterlimit:10;stroke-dasharray:5.6656;}
        .st3{opacity:0.22;fill:#231F20;stroke:#231F20;stroke-width:0.2749;stroke-miterlimit:10;stroke-dasharray:5.4987;}
        .st4{opacity:0.22;fill:#231F20;stroke:#231F20;stroke-width:0.2551;stroke-miterlimit:10;stroke-dasharray:5.1028;}
        .st5{fill:#E9E5DC;}
        .st6{fill:#6D6E71;}
        .st7{fill:none;stroke:#504A4B;stroke-width:2;stroke-miterlimit:10;}
        .st8{fill:none;stroke:#504A4B;stroke-width:2;stroke-miterlimit:10;stroke-dasharray:4.9971,4.9971;}
        .st9{fill:none;stroke:#231F20;stroke-width:0.25;stroke-miterlimit:10;}
        .st10{fill:none;stroke:#231F20;stroke-width:0.25;stroke-miterlimit:10;stroke-dasharray:4.9971,4.9971;}
        .st11{fill:none;stroke:#504A4B;stroke-width:2;stroke-miterlimit:10;stroke-dasharray:4.9965,4.9965;}
        .st12{fill:none;stroke:#231F20;stroke-width:0.25;stroke-miterlimit:10;stroke-dasharray:4.9965,4.9965;}
        .st13{fill:none;stroke:#504A4B;stroke-width:2;stroke-miterlimit:10;stroke-dasharray:5;}
        .st14{opacity:0.54;}
        .st15{fill:#FFFFFF;}
        .st16{font-family:'MyriadPro-Regular';}
        .st17{font-size:28.5085px;}
        .st18{font-family:'MyriadPro-Black';}
        .st19{opacity:0.6;}
        .st20{opacity:0.39;}
        .st21{opacity:0.44;}
        .st22{opacity:0.22;fill:#231F20;stroke:#231F20;stroke-width:0.25;stroke-miterlimit:10;stroke-dasharray:5;}
        .st23{opacity:0.34;fill:#231F20;stroke:#231F20;stroke-width:0.25;stroke-miterlimit:10;stroke-dasharray:5;}
        .st24{font-size:19.2695px;}
        .st25{fill:#ED1C29;}
        .st26{fill:#949899;stroke:#231F20;stroke-width:0.0627;stroke-miterlimit:10;}
        .st27{fill:#FFFFFF;stroke:#221F1F;stroke-width:0.0209;stroke-miterlimit:10;}
        .st28{fill:#221F1F;}
        .st29{fill:#DBDCDD;stroke:#221F1F;stroke-width:0.0209;stroke-miterlimit:10;}
        .st30{fill:none;stroke:#221F1F;stroke-width:0.0209;stroke-miterlimit:10;}
        .st31{fill:#231F20;}
        .st32{fill:#8A8C8F;stroke:#221F1F;stroke-width:0.0209;stroke-miterlimit:10;}
        .st33{font-family:'MyriadPro-It';}
        .st34{font-size:0.8642px;}
        .st35{opacity:0.22;fill:#231F20;stroke:#231F20;stroke-width:0.2576;stroke-miterlimit:10;stroke-dasharray:5.1518;}
        .st36{opacity:0.22;fill:#231F20;stroke:#231F20;stroke-width:0.232;stroke-miterlimit:10;stroke-dasharray:4.64;}
        .st37{opacity:0.22;fill:#231F20;stroke:#231F20;stroke-width:0.2646;stroke-miterlimit:10;stroke-dasharray:5.2914;}
        .st38{fill:#696D6E;stroke:#231F20;stroke-width:0.0627;stroke-miterlimit:10;}
        .st39{fill:#797C7E;stroke:#221F1F;stroke-width:0.0209;stroke-miterlimit:10;}
        .st40{fill:#949899;stroke:#231F20;stroke-width:0.038;stroke-miterlimit:10;}
        .st41{fill:#FFFFFF;stroke:#221F1F;stroke-width:0.0139;stroke-miterlimit:10;}
        .st42{fill:#DBDCDD;stroke:#221F1F;stroke-width:0.0139;stroke-miterlimit:10;}
        .st43{fill:none;stroke:#221F1F;stroke-width:0.0139;stroke-miterlimit:10;}
        .st44{fill:#8A8C8F;stroke:#221F1F;stroke-width:0.0139;stroke-miterlimit:10;}
        .st45{font-size:0.576px;}
        .st46{fill:#949899;stroke:#231F20;stroke-width:0.0524;stroke-miterlimit:10;}
        .st47{fill:#FFFFFF;stroke:#221F1F;stroke-width:0.0192;stroke-miterlimit:10;}
        .st48{fill:#DBDCDD;stroke:#221F1F;stroke-width:0.0192;stroke-miterlimit:10;}
        .st49{fill:none;stroke:#221F1F;stroke-width:0.0192;stroke-miterlimit:10;}
        .st50{fill:#8A8C8F;stroke:#221F1F;stroke-width:0.0192;stroke-miterlimit:10;}
        .st51{font-size:0.7952px;}
        .st52{fill:#231F20;stroke:#231F20;stroke-width:0.0627;stroke-miterlimit:10;}
    </style>
    <g id="Khu_A">
        <?php
            foreach($result as $value) {
                echo $value['svg'];
            }
        ?>
    </g>
</svg>

<?php

ob_end_flush();

?>

<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bản Đồ Đất Thánh Vinh Đức <?php echo $area ?></title>
  <link href="css/ol.css" rel="stylesheet" />
  <link rel="stylesheet" href="css/style.css">
</head>

<body class="page-home">
  <header>
    <h1 class="text-center">Bản Đồ Đất Thánh Vinh Đức <?php echo $area ?></h1>
  </header>
  <div class="container-fluid">
    <div id="map" class="row"></div>
  </div>

  <script src="js/jquery-3.5.1.min.js"></script>
  <script src="js/popper.min.js"></script>
  <script src="js/ol.js"></script>
  <script type="text/javascript">
    const svgPath = '<?php echo $svgPath ?>';
  </script>
  <script src="js/map.js"></script>
</body>

</html>