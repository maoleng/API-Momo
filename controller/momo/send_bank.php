<?php
    include 'momo.php';

    
    if(GET('act') == 'listBank'){
        die(file_get_contents("list_bank.json"));
    }
    if(!is_client()){
        die(json_encode(array("status" => "error","msg" => "Vui lòng đăng nhập trước nhé.")));
    }
    if(GET('act') == 'checkName') {
        $bankcode = junoo_boc(POST("bankcode"));
        $account_number = junoo_boc(POST("account_number"));
        if(!$bankcode || !$account_number){
            die(json_encode(array("status" => "error","msg" => "Vui nhập số điện thoại momo,số điện thoải gửi.")));
        }
        $from = $db->fetch_assoc("SELECT * FROM `cron_momo` WHERE `status` = 'success' AND `user_id` = '".$accounts['username']."' LIMIT 1 ",1);
        if(!$from['phone']) {
            die(json_encode(array("status" => "error","msg" => "Không tồn tại Momo hoặc Momo không phải của bạn")));
        }
        $result = $momo->LoadData($from['phone'],$accounts['username'])->checkNameBank($bankcode,$account_number);
        die(json_encode(array("status" => "success","msg" => $result['message'],"name" => $result['name'])));
    }if(GET('act') == 'send') {
        $bankcode = $db->real_escape_string(junoo_boc(POST("bankcode")));
        $account_number = $db->real_escape_string(junoo_boc(POST("account_number")));
        $from = $db->real_escape_string(junoo_boc(POST("from")));
        $amount = $db->real_escape_string(junoo_boc(POST("amount")));
        $message = POST('message') ? $db->real_escape_string(POST('message')) : "From fgocheat.net with love";
        if(!$bankcode || !$account_number || !$from){
            die(json_encode(array("status" => "error","msg" => "Vui nhập đầy đủ dữ liệu")));
        }
        $from = $db->fetch_assoc("SELECT * FROM `cron_momo` WHERE `phone` = '".$from."' AND `user_id` = '".$accounts['username']."' LIMIT 1 ",1);
        if(!$from['phone']) {
            die(json_encode(array("status" => "error","msg" => "Không tồn tại Momo hoặc Momo không phải của bạn")));
        }
        $result = $momo->LoadData($from["phone"], $from["user_id"])->SendMoneyBank($bankcode,$account_number,$amount,$message);
        $data_send = $result['full'];
        $db->query("INSERT INTO `send_bank` (`id`, `momo_id`, `tranId`, `partnerId`, `partnerName`, `amount`, `comment`, `time`, `user_id`, `status`, `message`, `data`,`balance`,`ownerNumber`,`ownerName`,`type`) VALUES (NULL, '".$result['tranDList']['ID']."', '".$result['tranDList']['tranId']."', '".$account_number."', '".$result['tranDList']['partnerName']."', '".$result['tranDList']['amount']."', '".$result['tranDList']['comment']."', '".time()."', '".$accounts['username']."', '".$result['status']."', '".$result['message']."', '".$data_send."','".$result['tranDList']['balance']."','".$result['tranDList']['ownerNumber']."','".$result['tranDList']['ownerName']."','0')");
        $logne = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']."\nIP: ".$_SERVER['REMOTE_ADDR']."\nBrowser: ".$_SERVER['HTTP_USER_AGENT'];
        $logne .= "\n SEND:";
        $logne .= "\n".json_encode($_REQUEST);
        if (!is_dir(realpath($_SERVER["DOCUMENT_ROOT"]) .'/assets/vendor/log/'.$accounts['username'])) {
            @mkdir(realpath($_SERVER["DOCUMENT_ROOT"]) .'/assets/vendor/log/'.$accounts['username'], 0777, true);
        }
        $myFile = realpath($_SERVER["DOCUMENT_ROOT"]) .'/assets/vendor/log/'.$accounts['username'].'/'.$day.'-'.$month.'-'.$year.'.bat';
        writeLog($logne,$myFile);
        die(json_encode(array("status" => $result['status'],"msg" => $result['message'])));
    }if(GET('act') == 'sendhistories') {
        $ngaydaucuathang = strtotime(date('01-m-Y'));
        $ngaycuoicuathang = strtotime('23:59:59 '.date('t-m-Y'));
        $result = $db->fetch_assoc("SELECT * FROM `send_bank` WHERE `user_id` = '".$accounts['username']."' AND `time` >= '".$ngaydaucuathang."'  AND `time` <= '".$ngaycuoicuathang."' ORDER BY `id` DESC ",0);
        $i = 1;
        $data = array();
        foreach($result as $key => $row) {
            
            $type = "Gửi tiền";
            $info = "Số Dư: ".number_format($row['balance'])."</br>F: ".$row['ownerNumber']."</br>M: ".$row['message']."</br>T: ".$type  ;
            $data[] = [
                'RecordID' => $row['id'],
                'momo_id' => $row['momo_id'],
                'Phone' => $row['partnerId'],
                'Name' => $row['partnerName'],
                'Amount' => number_format($row['amount']),
                'Comment' => $row['comment'],
                'tranId' => $row['tranId'],
                'Status' => $row['status'],
                'Info' => $info,
                'Date' => date("H:m:i d-m-Y",$row['time']),
            ];
            $i++;
        }
        echo json_encode(($data));
    }