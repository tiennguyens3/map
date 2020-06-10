<?php

include 'config.php';

try {
    $dbh = new PDO($dsn, $user, $password);

    $sql = "update nguoimat set gioitinh=:gioitinh where id_nguoimat=:id";

    $id = isset($_POST['id']) ? $_POST['id'] : 0;
    $isMale = isset($_POST['male']) && 'male' == $_POST['male'] ? 1 : 0;
    $sth = $dbh->prepare($sql);
    $sth->execute(array(':id' => $id, ':gioitinh' => $isMale));

    echo $sth->rowCount();

} catch (PDOException $e) {
    echo 'NO';
}