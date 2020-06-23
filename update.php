<?php

include 'config.php';

try {
    $dbh = new PDO($dsn, $user, $password);

    $id = isset($_POST['id']) ? $_POST['id'] : 0;
    $isMale = isset($_POST['male']) && 'male' == $_POST['male'] ? 1 : 0;

    if (!$id) {
        throw new PDOException();
    }

    $sql = "select * from nguoimat where id_nguoimat=:id";
    $sth = $dbh->prepare($sql);
    $sth->execute(array(':id' => $id));
    $result = $sth->fetch();

    $sql = "update nguoimat set gioitinh=:gioitinh where id_nguoimat=:id";
    $sth = $dbh->prepare($sql);
    $sth->execute(array(':id' => $id, ':gioitinh' => $isMale));

} catch (PDOException $e) {
    echo 'NO';
}

if (isset($result)) {
?>

<tspan x="0" dy=".6em"><?php echo $result['tenthanh']; ?></tspan>
<tspan x="0" dy="1.2em"><?php echo $result['tennguoimat']; ?></tspan>
<tspan x="0" dy="1.2em"><?php echo 'Mỹ Dụ'; ?></tspan>
<tspan x="0" dy="1.2em"><?php echo $result['namsinh']; ?></tspan>
<tspan x="0" dy="1.2em"><?php echo $result['quequan']; ?></tspan>

<?php

}

?>