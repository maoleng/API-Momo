<?php
    include 'momo.php';
    if(GET('act') == "test"){
    	for($i = 1;$i <= 10;$i++){
    		$result = $momo->LoadData( '0877820433' ,'vutungduy')->SendMoney("0899621461",2000,"C");
        	print_r($result);
        }
        die;
    }
    
    if(!is_client()){
        die(json_encode(array("status" => "error","msg" => "Vui lòng đăng nhập trước nhé.")));
    }
    if(GET('act') == 'checkName') {
        $sdt = junoo_boc(POST("momo"));
        $from = junoo_boc(POST("from"));
        if(!$sdt || !$from){
            die(json_encode(array("status" => "error","msg" => "Vui nhập số điện thoại momo,số điện thoải gửi.")));
        }
        $from = $db->fetch_assoc("SELECT * FROM `cron_momo` WHERE `phone` = '".$from."' AND `user_id` = '".$accounts['username']."' LIMIT 1 ",1);
        if(!$from['phone']) {
            die(json_encode(array("status" => "error","msg" => "Không tồn tại Momo hoặc Momo không phải của bạn")));
        }
        $result = $momo->LoadData($from['phone'],$accounts['username'])->CheckName($sdt);
        die(json_encode(array("status" => "success","msg" => $result['message'],"name" => $result['name'])));
    }else if(GET('act') == 'sendhistories') {
        $result = $db->fetch_assoc("SELECT * FROM `send` WHERE `user_id` = '".$accounts['username']."' ORDER BY `id` DESC ",0);
        $i = 1;
        $data = array();
        foreach($result as $key => $row) {
            $info = "Số Dư: ".number_format($row['balance'])."</br>F: ".$row['ownerNumber']."</br>M: ".$row['message'] ;
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
    }else{
        $sdt = $db->real_escape_string(junoo_boc(POST("momo")));
        $from = $db->real_escape_string(junoo_boc(POST("from")));
        $amount = $db->real_escape_string(junoo_boc(POST("amount")));
        $message = POST('message') ? $db->real_escape_string(POST('message')) : "From with love <3";
        if(!$sdt || !$from || $amount < 1000){
            die(json_encode(array("status" => "error","msg" => "Vui nhập số điện thoại momo,số điện thoải gửi,số tiền >= 1.000đ.")));
        }
        $from = $db->fetch_assoc("SELECT * FROM `cron_momo` WHERE `phone` = '".$from."' AND `user_id` = '".$accounts['username']."' LIMIT 1 ",1);
        if(!$from['phone']) {
            die(json_encode(array("status" => "error","msg" => "Không tồn tại Momo hoặc Momo không phải của bạn")));
        }
        $result = $momo->LoadData($from['phone'],$accounts['username'])->SendMoney($sdt,$amount,$message);
        $data_send = $result['full'];
        $db->query("INSERT INTO `send` (`id`, `momo_id`, `tranId`, `partnerId`, `partnerName`, `amount`, `comment`, `time`, `user_id`, `status`, `message`, `data`,`balance`,`ownerNumber`,`ownerName`) VALUES (NULL, '".$result['tranDList']['ID']."', '".$result['tranDList']['tranId']."', '".$result['tranDList']['partnerId']."', '".$result['tranDList']['partnerName']."', '".$result['tranDList']['amount']."', '".$result['tranDList']['comment']."', '".time()."', '".$accounts['username']."', '".$result['status']."', '".$result['message']."', '".$data_send."','".$result['tranDList']['balance']."','".$result['tranDList']['ownerNumber']."','".$result['tranDList']['ownerName']."')");
        die(json_encode(array("status" => $result['status'],"msg" => $result['message'])));
    }
    