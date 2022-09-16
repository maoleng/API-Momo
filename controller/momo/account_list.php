<?php
    include 'momo.php';
    if(!is_client()){
        die(json_encode(array("status" => "error","msg" => "Vui lòng đăng nhập trước nhé.")));
    }
        $result = $db->fetch_assoc("SELECT * FROM `cron_momo` WHERE `user_id` = '".$accounts['username']."' ORDER BY `id` DESC ",0);
        $i = 1;
        $data = array();
        foreach($result as $key => $row) {
            //$info = "Số Dư: ".number_format($row['balance'])."</br> Người Gửi: ".$row['ownerNumber']." - ".$row['ownerName'];
            $data[] = [
                'RecordID' => $row['id'],
                'ID' => $row['id'],
                'phone' => $row['phone'],
                'password' => $row['password'],
                'Name' => $row['Name'],
                'BALANCE' => number_format($row['BALANCE']).'<sup>đ</sup>',
                'today_gd' => number_format($row['today_gd']),
                'today' => number_format($row['today']),
                'Status' => $row['status'],
                'errorDesc' => $row['errorDesc'],
                'month' => number_format($row['month']),
                'Actions' => '',
                'Date' => date("H:i:s d-m-Y",$row['TimeLogin']),
            ];
            $i++;
        }
        echo json_encode(($data));
    
    