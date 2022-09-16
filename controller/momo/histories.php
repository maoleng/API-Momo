<?php
    include 'momo.php';
    if(!is_client()){
        die(json_encode(array("status" => "error","msg" => "Vui lòng đăng nhập trước nhé.")));
    }

        $result = $db->fetch_assoc("SELECT * FROM `momo_history` WHERE `user_id` = '".$accounts['username']."' ORDER BY `id` DESC ",0);
        $i = 1;
        $data = array();
        foreach($result as $key => $row) {
            $data[] = [
                'RecordID' => $row['id'],
                'momo_id' => $row['id_momo'],
                'Phone' => $row['partnerId'],
                'Name' => $row['partnerName'],
                'Amount' => number_format($row['amount']),
                'Comment' => $row['comment'],
                'tranId' => $row['id_tran'],
                'Status' => $row['status_momo'],
                'Info' => "Nhận: " .$row['phone'],
                'Date' => $row['time_tran_date'],
            ];
            $i++;
        }
        echo json_encode(($data));
    