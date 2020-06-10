<?php

include 'config.php';

try {
    $dbh = new PDO($dsn, $user, $password);

    $sql = "select id_nguoimat, tennguoimat, khuvuc, hang_khuvuc, thutu_nguoimat from nguoimat where id_nguoimat=:id";

    $id = isset($_POST['id']) ? $_POST['id'] : 0;
    $sth = $dbh->prepare($sql);
    $sth->execute(array(':id' => $id));
    $result = $sth->fetch();

} catch (PDOException $e) {
    echo 'NO';
}

if (empty($result)) {
  echo 'NO';
  exit();
}

?>

<p>Tên: <?php echo $result['tennguoimat'] ?></p>
<p>Khu vực: <?php echo $result['khuvuc'] ?></p>
<p>Hàng khu vực: <?php echo $result['hang_khuvuc'] ?></p>
<p>Thứ tự: <?php echo $result['thutu_nguoimat'] ?></p>