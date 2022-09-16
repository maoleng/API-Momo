<?php
include 'momo.php';
$result = $db->fetch_assoc("SELECT * FROM `cron_momo` WHERE  `phone` = '0975043452' ORDER BY RAND() LIMIT 1", 1);
$result1 = $momo->LoadData($result['phone'], $result['user_id'])->CheckHis(1);
//$result1 = $momo->LoadData($result['phone'], $result['user_id'])->CheckHis(30);
echo json_encode($result1);
?>