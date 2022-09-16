<?php
    include 'momo.php';
    if(!is_client()){
        die(json_encode(array("status" => "error","msg" => "Vui lòng đăng nhập trước nhé.")));
    }
    if(GET('act') == 'topuphistories') {
        $result = $db->fetch_assoc("SELECT * FROM `topup` WHERE `user_id` = '".$accounts['username']."' ORDER BY `id` DESC ",0);
        $i = 1;
        $data = array();
        foreach($result as $key => $row) {
            $info = "Số Dư: ".number_format($row['balance'])."</br>F: ".$row['ownerNumber']."</br>M: ".$row['message'] ;
            $data[] = [
                'RecordID' => $row['id'],
                'momo_id' => $row['momo_id'],
                'Amount' => number_format($row['amount']),
                'tranId' => $row['tranId'],
                'Status' => $row['status'],
                'Info' => $info,
                'Date' => date("H:m:i d-m-Y",$row['time']),
            ];
            $i++;
        }
        echo json_encode(($data));
    }else{
        $from = $db->real_escape_string(junoo_boc(POST("from")));
        $amount = $db->real_escape_string(junoo_boc(POST("amount")));
        $bankName = $db->real_escape_string(strtoupper(junoo_boc(POST("bankname"))));
        if(!$bankName || !$from || $amount < 10000){
            die(json_encode(array("status" => "error","msg" => "Vui nhập chọn ngân hàng,số điện thoại nạp,số tiền >= 10.000đ.")));
        }
        $from = $db->fetch_assoc("SELECT * FROM `cron_momo` WHERE `phone` = '".$from."' AND `user_id` = '".$accounts['username']."' LIMIT 1 ",1);
        if(!$from['phone']) {
            die(json_encode(array("status" => "error","msg" => "Không tồn tại Momo hoặc Momo không phải của bạn")));
        }
        $result = $momo->LoadData($from['phone'],$accounts['username'])->ToUpMoney($amount,$bankName);
        $data_send = $result['full'];
        $db->query("INSERT INTO `topup` (`id`, `momo_id`, `tranId`, `amount`, `time`, `user_id`, `status`, `message`, `data`,`balance`,`ownerNumber`,`ownerName`) VALUES (NULL, '".$result['tranDList']['ID']."', '".$result['tranDList']['tranId']."', '".$result['tranDList']['amount']."', '".time()."', '".$accounts['username']."', '".$result['status']."', '".$result['message']."', '".$data_send."','".$result['tranDList']['balance']."','".$result['tranDList']['ownerNumber']."','".$result['tranDList']['ownerName']."')");
        die(json_encode(array("status" => $result['status'],"msg" => $result['message'])));
    }
    