
<?php
include 'momo.php';


$act  = GET('act');
$type = GET('type') ?: 0;

if($act == 'sendMoney' || $act == 'sendMoneyBank') {
    $logne = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']."\nIP: ".$_SERVER['REMOTE_ADDR']."\nBrowser: ".$_SERVER['HTTP_USER_AGENT'];
    $logne .= "\n".json_encode($_REQUEST);
    if (!is_dir(realpath($_SERVER["DOCUMENT_ROOT"]) .'/assets/vendor/log/'.POST('user_id'))) {
        @mkdir(realpath($_SERVER["DOCUMENT_ROOT"]) .'/assets/vendor/log/'.POST('user_id'), 0777, true);
    }
    $myFile = realpath($_SERVER["DOCUMENT_ROOT"]) .'/assets/vendor/log/'.POST('user_id').'/'.$day.'-'.$month.'-'.$year.'.bat';
    writeLog($logne,$myFile);
}
if ($act == 'getToken') {
    $phone   = $db->real_escape_string(junoo_boc(POST('phone')));
    $user_id = $db->real_escape_string(junoo_boc(POST('user_id')));
    $api_key = $db->real_escape_string(POST('api_key'));
    
    if ($phone) {
        $result = $db->fetch_assoc("SELECT * FROM `cron_momo` WHERE `phone` = '" . $phone . "' AND `user_id` = '" . $user_id . "' ORDER BY `id` ASC LIMIT 1", 1);
        if (!$result['phone']) {
            die(json_encode(array(
                "status" => "error",
                "code" => -99,
                "message" => "Không tồn tại tài khoản momo"
            )));
        }
        $result_user = $db->fetch_assoc("SELECT * FROM `accounts` WHERE `username` = '" . $user_id . "' ORDER BY `id` ASC LIMIT 1", 1);
        if ( $result_user['api_key'] != $api_key) {
            die(json_encode(array(
                "status" => "error",
                "code" => -100,
                "message" => "API KEY không hợp lệ hoặc chưa được kích hoạt"
            )));
        }
        $result1 = $momo->LoadData($result['phone'], $result['user_id'])->LoginUser();
        echo json_encode($result1);
    } else if ($type == 1) {
        /*$result = $db->fetch_assoc("SELECT * FROM `cron_momo` WHERE `status` = 'success' ORDER BY `id` ASC ", 0);
        foreach ($result as $key => $row) {
            if(!$row['Name'] ||$row['Name'] == '' ) {
                $result12 = $momo->LoadData($row['phone'], $row['user_id'])->CheckName($row['phone']);
                $db->query("UPDATE `cron_momo` SET `Name` = '".$result12['name']."' WHERE `phone` = '".$row['phone']."' ");
            }
            $result1 = $momo->LoadData($row['phone'], $row['user_id'])->LoginTimeSetup();
            echo "<pre>";
            print_r($result1);
            echo "<pre>";
        }*/
        $result1 = $db->fetch_assoc("SELECT * FROM `cron_momo` WHERE `status` = 'error' AND `try` <= '3' ORDER BY `id` ASC ", 0);
        foreach ($result1 as $key => $row) {
            if (!$row["Name"] || $row["Name"] == "") {
                $result12 = $momo->LoadData($row["phone"], $row["user_id"])->CheckName($row["phone"]);
                $db->query("UPDATE `cron_momo` SET `Name` = '" . $result12["name"] . "' WHERE `phone` = '" . $row["phone"] . "' ");
            }
            $result12 = $momo->LoadData($row["phone"], $row["user_id"])->LoginTimeSetup();
            
            echo "<pre>";
            print_r($result12);
            echo "<pre>";
        }
    }else{
        die(json_encode(array(
                "status" => "error",
                "code" => -99,
                "message" => "Không tồn tại tài khoản momo"
            )));   
    }
}else if ($act == 'getHistories') {
    $hours     = POST('hours') ?: 30;
    $phone   = $db->real_escape_string(junoo_boc(POST('phone')));
    $user_id = $db->real_escape_string(junoo_boc(POST('user_id')));
    $api_key = $db->real_escape_string(POST('api_key'));
    
    if ($phone) {
        $result = $db->fetch_assoc("SELECT * FROM `cron_momo` WHERE `phone` = '" . $phone . "' AND `user_id` = '" . $user_id . "' ORDER BY `id` ASC LIMIT 1", 1);
        if (!$result['phone']) {
            die(json_encode(array(
                "status" => "error",
                "code" => -99,
                "message" => "Không tồn tại tài khoản momo"
            )));
        }
        $result_user = $db->fetch_assoc("SELECT * FROM `accounts` WHERE `username` = '" . $user_id . "' ORDER BY `id` ASC LIMIT 1", 1);
        if (!$result_user['api_key'] || $result_user['api_key'] != $api_key) {
            die(json_encode(array(
                "status" => "error",
                "code" => -100,
                "message" => "API KEY không hợp lệ hoặc chưa được kích hoạt"
            )));
        }
        $history = $momo->LoadData($result['phone'], $result['user_id'])->CheckHisNew($hours);
        
        foreach ($history['TranList'] as $contents) {
            $transaction = ["time_tran" => $contents['millisecond'],"io" => 1, "tranId" => $contents['tranId'], "comment" => $contents['comment'], "amount" => $contents['amount'], "partnerId" => $contents['patnerID'], "partnerName" => $contents['partnerName'], "ID" => $contents['ID']];
            $exists  = $db->fetch_assoc("SELECT * FROM `momo_history` WHERE `id_momo` = '" . $transaction['ID'] . "' LIMIT 1 ", 1);
            $exists1 = $db->fetch_assoc("SELECT * FROM `momo_history` WHERE `id_tran` = '" . $transaction['tranId'] . "' LIMIT 1 ", 1);
            
            if (!$exists['id'] && !$exists1['id']) {
                $insert = $db->query("INSERT INTO `momo_history` ( `user_id`, `phone`, `id_momo`, `id_tran`, `type`, `partnerId`, `partnerName`, `amount`, `comment`, `full_data`, `time_tran`, `time_tran_date`, `status_momo`, `created_at`, `updated_at`) VALUES ( '" . $result['user_id'] . "', '" . $result['phone'] . "', '" . $transaction['ID'] . "', '" . $transaction['tranId'] . "', '" . $transaction['io'] . "', '" . $transaction['partnerId'] . "', '" . $transaction['partnerName'] . "', '" . $transaction['amount'] . "', '" . $transaction['comment'] . "', '" . json_encode($transaction) . "', '" . floor($contents['millisecond'] / 1000) . "', '" . date("d/m/Y H:i:s", floor($contents['millisecond'] / 1000)) . "', '999', current_timestamp(), current_timestamp())");
            	
            }
        }
        echo json_encode($history);
    } else if ($type == 1) {
        $result = $db->fetch_assoc("SELECT * FROM `cron_momo` WHERE `status` = 'success' ORDER BY `id` ASC ", 0);
        foreach ($result as $key => $row) {
            $history = $momo->LoadData($row['phone'], $row['user_id'])->CheckHisNew(1);
          //  echo "<pre>";
            echo json_encode($history);
          //  echo "<pre>";
            foreach ($history['TranList'] as $contents) {
                $transaction = ["phone" => $row['phone'],"time_tran" => $contents['millisecond'],"io" => 1, "tranId" => $contents['tranId'], "comment" => $contents['comment'], "amount" => $contents['amount'], "partnerId" => $contents['patnerID'], "partnerName" => $contents['partnerName'], "ID" => $contents['ID']];
                $exists  = $db->fetch_assoc("SELECT * FROM `momo_history` WHERE `id_momo` = '" . $transaction['ID'] . "' LIMIT 1 ", 1);
                $exists1 = $db->fetch_assoc("SELECT * FROM `momo_history` WHERE `id_tran` = '" . $transaction['tranId'] . "' LIMIT 1 ", 1);
                
                if (!$exists['id'] && !$exists1['id']) {
                    $insert = $db->query("INSERT INTO `momo_history` ( `user_id`, `phone`, `id_momo`, `id_tran`, `type`, `partnerId`, `partnerName`, `amount`, `comment`, `full_data`, `time_tran`, `time_tran_date`, `status_momo`, `created_at`, `updated_at`) VALUES ( '" . $row['user_id'] . "', '" . $row['phone'] . "', '" . $transaction['ID'] . "', '" . $transaction['tranId'] . "', '" . $transaction['io'] . "', '" . $transaction['partnerId'] . "', '" . $transaction['partnerName'] . "', '" . $transaction['amount'] . "', '" . $transaction['comment'] . "', '" . json_encode($transaction) . "', '" . floor($contents['millisecond'] / 1000) . "', '" . date("d/m/Y H:i:s", floor($contents['millisecond'] / 1000)) . "', '999', current_timestamp(), current_timestamp())");
                    if($row['callback_url'] != ""){
                		$log = "----momo callback----\n";
                		$log .= curl_post($row['callback_url'],$transaction)."/n";
                        $log .= json_encode($transaction);
                        if (!is_dir(realpath($_SERVER["DOCUMENT_ROOT"]) .'/assets/vendor/log/'.$row['user_id'])) {
        					@mkdir(realpath($_SERVER["DOCUMENT_ROOT"]) .'/assets/vendor/log/'.$row['user_id'], 0777, true);
    					}
    					$myFile = realpath($_SERVER["DOCUMENT_ROOT"]) .'/assets/vendor/log/'.$row['user_id'].'/'.$day.'-'.$month.'-'.$year.'.bat';
    					writeLog($log,$myFile);
                	}
                    
                }
            }
        }
    }else{
        die(json_encode(array(
                "status" => "error",
                "code" => -99,
                "message" => "Không tồn tại tài khoản momo"
            )));   
    }
}else if ($act == 'getHistoriesFull') {
    $hours     = POST('hours') ?: 30;
    $phone   = $db->real_escape_string(junoo_boc(POST('phone')));
    $user_id = $db->real_escape_string(junoo_boc(POST('user_id')));
    $api_key = $db->real_escape_string(POST('api_key'));
    
    if ($phone) {
        $result = $db->fetch_assoc("SELECT * FROM `cron_momo` WHERE `phone` = '" . $phone . "' AND `user_id` = '" . $user_id . "' ORDER BY `id` ASC LIMIT 1", 1);
        if (!$result['phone']) {
            die(json_encode(array(
                "status" => "error",
                "code" => -99,
                "message" => "Không tồn tại tài khoản momo"
            )));
        }
        $result_user = $db->fetch_assoc("SELECT * FROM `accounts` WHERE `username` = '" . $user_id . "' ORDER BY `id` ASC LIMIT 1", 1);
        if (!$result_user['api_key'] || $result_user['api_key'] != $api_key) {
            die(json_encode(array(
                "status" => "error",
                "code" => -100,
                "message" => "API KEY không hợp lệ hoặc chưa được kích hoạt"
            )));
        }
        $history = $momo->LoadData($result['phone'], $result['user_id'])->CheckHis($hours);
        
        foreach ($history['TranList'] as $contents) {
            $transaction = ["time_tran" => $contents['millisecond'],"io" => $contents['io'], "tranId" => $contents['tranId'], "comment" => $contents['comment'], "amount" => $contents['amount'], "partnerId" => $contents['patnerID'], "partnerName" => $contents['partnerName'], "ID" => $contents['ID']];
            $exists  = $db->fetch_assoc("SELECT * FROM `momo_history` WHERE `id_momo` = '" . $transaction['ID'] . "' LIMIT 1 ", 1);
            $exists1 = $db->fetch_assoc("SELECT * FROM `momo_history` WHERE `id_tran` = '" . $transaction['tranId'] . "' LIMIT 1 ", 1);
            
            if (!$exists['id'] && !$exists1['id'] && $transaction['io'] == 1) {
                $insert = $db->query("INSERT INTO `momo_history` ( `user_id`, `phone`, `id_momo`, `id_tran`, `type`, `partnerId`, `partnerName`, `amount`, `comment`, `full_data`, `time_tran`, `time_tran_date`, `status_momo`, `created_at`, `updated_at`) VALUES ( '" . $result['user_id'] . "', '" . $result['phone'] . "', '" . $transaction['ID'] . "', '" . $transaction['tranId'] . "', '" . $transaction['io'] . "', '" . $transaction['partnerId'] . "', '" . $transaction['partnerName'] . "', '" . $transaction['amount'] . "', '" . $transaction['comment'] . "', '" . json_encode($transaction) . "', '" . floor($contents['millisecond'] / 1000) . "', '" . date("d/m/Y H:i:s", floor($contents['millisecond'] / 1000)) . "', '999', current_timestamp(), current_timestamp())");
            }
        }
        echo json_encode($history);
    } else if ($type == 1) {
        $result = $db->fetch_assoc("SELECT * FROM `cron_momo` WHERE `status` = 'success' ORDER BY `id` ASC ", 0);
        foreach ($result as $key => $row) {
            $history = $momo->LoadData($row['phone'], $row['user_id'])->CheckHis(1);
            echo "<pre>";
            print_r($history);
            echo "<pre>";
            foreach ($history['TranList'] as $contents) {
                $transaction = ["phone" => $row['phone'],"time_tran" => $contents['millisecond'],"io" => $contents['io'], "tranId" => $contents['tranId'], "comment" => $contents['comment'], "amount" => $contents['amount'], "partnerId" => $contents['patnerID'], "partnerName" => $contents['partnerName'], "ID" => $contents['ID']];
                $exists  = $db->fetch_assoc("SELECT * FROM `momo_history` WHERE `id_momo` = '" . $transaction['ID'] . "' LIMIT 1 ", 1);
                $exists1 = $db->fetch_assoc("SELECT * FROM `momo_history` WHERE `id_tran` = '" . $transaction['tranId'] . "' LIMIT 1 ", 1);
                
                if (!$exists['id'] && !$exists1['id'] && $transaction['io'] == 1) {
                    $insert = $db->query("INSERT INTO `momo_history` ( `user_id`, `phone`, `id_momo`, `id_tran`, `type`, `partnerId`, `partnerName`, `amount`, `comment`, `full_data`, `time_tran`, `time_tran_date`, `status_momo`, `created_at`, `updated_at`) VALUES ( '" . $row['user_id'] . "', '" . $row['phone'] . "', '" . $transaction['ID'] . "', '" . $transaction['tranId'] . "', '" . $transaction['io'] . "', '" . $transaction['partnerId'] . "', '" . $transaction['partnerName'] . "', '" . $transaction['amount'] . "', '" . $transaction['comment'] . "', '" . json_encode($transaction) . "', '" . floor($contents['millisecond'] / 1000) . "', '" . date("d/m/Y H:i:s", floor($contents['millisecond'] / 1000)) . "', '999', current_timestamp(), current_timestamp())");
                    if($row['callback_url'] != ""){
                    	$log = "----momo callback----\n";
                		$log .= curl_post($row['callback_url'],$transaction)."/n";
                        $log .= json_encode($transaction);
                        if (!is_dir(realpath($_SERVER["DOCUMENT_ROOT"]) .'/assets/vendor/log/'.$row['user_id'])) {
        					@mkdir(realpath($_SERVER["DOCUMENT_ROOT"]) .'/assets/vendor/log/'.$row['user_id'], 0777, true);
    					}
    					$myFile = realpath($_SERVER["DOCUMENT_ROOT"]) .'/assets/vendor/log/'.$row['user_id'].'/'.$day.'-'.$month.'-'.$year.'.bat';
    					writeLog($log,$myFile);
                	}
                    
                }
            }
        }
    }else{
        die(json_encode(array(
                "status" => "error",
                "code" => -99,
                "message" => "Không tồn tại tài khoản momo"
            )));   
    }
}else if(GET('act') == 'listBank'){
	$user_id = $db->real_escape_string(junoo_boc(POST('user_id')));
    $api_key = $db->real_escape_string(POST('api_key'));
    $result_user = $db->fetch_assoc("SELECT * FROM `accounts` WHERE `username` = '" . $user_id . "' ORDER BY `id` ASC LIMIT 1", 1);
        if (!$result_user['api_key'] || $result_user['api_key'] != $api_key) {
            die(json_encode(array(
                "status" => "error",
                "code" => -100,
                "message" => "API KEY không hợp lệ hoặc chưa được kích hoạt"
            )));
        }
	die(file_get_contents("list_bank.json"));
}else if(GET('act') == 'checkName') {
        $bankcode = junoo_boc(POST("bankcode"));
        $account_number = junoo_boc(POST("account_number"));
        $user_id = $db->real_escape_string(junoo_boc(POST('user_id')));
    	$api_key = $db->real_escape_string(POST('api_key'));
        if(!$bankcode || !$account_number || !$user_id || !$api_key){
            die(json_encode(array("status" => "error","code" => -99,"msg" => "Vui nhập đầy đủ dữ liệu.")));
        }
        $result_user = $db->fetch_assoc("SELECT * FROM `accounts` WHERE `username` = '" . $user_id . "' ORDER BY `id` ASC LIMIT 1", 1);
        if (!$result_user['api_key'] || $result_user['api_key'] != $api_key) {
            die(json_encode(array(
                "status" => "error",
                "code" => -100,
                "message" => "API KEY không hợp lệ hoặc chưa được kích hoạt"
            )));
        }
        $from = $db->fetch_assoc("SELECT * FROM `cron_momo` WHERE `status` = 'success' AND `user_id` = '".$user_id."' ORDER BY RAND()LIMIT 1 ",1);
        if(!$from['phone']) {
            die(json_encode(array("status" => "error","msg" => "Không có check để thực hiện")));
        }
        $result = $momo->LoadData($from['phone'],$user_id)->checkNameBank($bankcode,$account_number);
        die(json_encode($result));
} else if ($act == 'sendMoneyBank') {
    $account_number = $db->real_escape_string(POST('account_number'));
    $amount = $db->real_escape_string(POST('amount'));
    $comments = $db->real_escape_string(POST('comments')) ?: "";
    $bankcode = $db->real_escape_string(junoo_boc(POST("bankcode")));
    $phone   = $db->real_escape_string(junoo_boc(POST('phone')));
    $user_id = $db->real_escape_string(junoo_boc(POST('user_id')));
    $api_key = $db->real_escape_string(POST('api_key'));
    if(!$bankcode || !$account_number || !$amount || !$user_id || !$api_key){
            die(json_encode(array("status" => "error","code" => -99,"msg" => "Vui nhập đầy đủ dữ liệu.")));
    }
    if ($phone) {
        $from = $db->fetch_assoc("SELECT * FROM `cron_momo` WHERE `phone` = '" . $phone . "' AND `user_id` = '" . $user_id . "' AND `BALANCE` >= '".$amount."' ORDER BY `id` ASC LIMIT 1", 1);
        if (!$from['phone']) {
            die(json_encode(array(
                "status" => "error",
                "code" => -99,
                "message" => "Không tồn tại tài khoản momo"
            )));
        }
        $result_user = $db->fetch_assoc("SELECT * FROM `accounts` WHERE `username` = '" . $user_id . "' ORDER BY `id` ASC LIMIT 1", 1);
        if (!$result_user['api_key'] || $result_user['api_key'] != $api_key) {
            die(json_encode(array(
                "status" => "error",
                "code" => -100,
                "message" => "API KEY không hợp lệ hoặc chưa được kích hoạt"
            )));
        }
        
        $comments = $comments ?: $from['noidungtra'];
        $result = $momo->LoadData($from['phone'], $from['user_id'])->SendMoneyBank($bankcode,$account_number,$amount,$comments);
        $data_send = $result['full'];
        $db->query("INSERT INTO `send_bank` (`id`, `momo_id`, `tranId`, `partnerId`, `partnerName`, `amount`, `comment`, `time`, `user_id`, `status`, `message`, `data`,`balance`,`ownerNumber`,`ownerName`,`type`) VALUES (NULL, '".$result['tranDList']['ID']."', '".$result['tranDList']['tranId']."', '".$account_number."', '".$result['tranDList']['partnerName']."', '".$result['tranDList']['amount']."', '".$result['tranDList']['comment']."', '".time()."', '".$from['user_id']."', '".$result['status']."', '".$result['message']."', '".$data_send."','".$result['tranDList']['balance']."','".$result['tranDList']['ownerNumber']."','".$result['tranDList']['ownerName']."','0')");
        echo json_encode($result);
    } else {
        $from = $db->fetch_assoc("SELECT * FROM `cron_momo` WHERE  `user_id` = '" . $user_id . "' AND `status` = 'success' AND `BALANCE` >= '".$amount."' ORDER BY RAND() LIMIT 1", 1);
        if (!$from['phone']) {
            die(json_encode(array(
                "status" => "error",
                "code" => -99,
                "message" => "Không tồn tại tài khoản momo"
            )));
        }
        $result_user = $db->fetch_assoc("SELECT * FROM `accounts` WHERE `username` = '" . $user_id . "' ORDER BY `id` ASC LIMIT 1", 1);
        if (!$result_user['api_key'] || $result_user['api_key'] != $api_key) {
            die(json_encode(array(
                "status" => "error",
                "code" => -100,
                "message" => "API KEY không hợp lệ hoặc chưa được kích hoạt"
            )));
        }
        $comments = $comments ?: $from['noidungtra'];
        $result = $momo->LoadData($from['phone'], $from['user_id'])->SendMoneyBank($bankcode,$account_number,$amount,$comments);
        $data_send = $result['full'];
        $db->query("INSERT INTO `send_bank` (`id`, `momo_id`, `tranId`, `partnerId`, `partnerName`, `amount`, `comment`, `time`, `user_id`, `status`, `message`, `data`,`balance`,`ownerNumber`,`ownerName`,`type`) VALUES (NULL, '".$result['tranDList']['ID']."', '".$result['tranDList']['tranId']."', '".$account_number."', '".$result['tranDList']['partnerName']."', '".$result['tranDList']['amount']."', '".$result['tranDList']['comment']."', '".time()."', '".$from['user_id']."', '".$result['status']."', '".$result['message']."', '".$data_send."','".$result['tranDList']['balance']."','".$result['tranDList']['ownerNumber']."','".$result['tranDList']['ownerName']."','0')");
        echo json_encode($result);
    }
} else if ($act == 'sendMoney') {
    $tophone = $db->real_escape_string(POST('tophone'));
    $amount = $db->real_escape_string(POST('amount'));
    $comments = $db->real_escape_string(POST('comments')) ?: "";
    $phone   = $db->real_escape_string(junoo_boc(POST('phone')));
    $user_id = $db->real_escape_string(junoo_boc(POST('user_id')));
    $api_key = $db->real_escape_string(POST('api_key'));
    if(!$bankcode || !$tophone || !$amount || !$user_id || !$api_key){
            die(json_encode(array("status" => "error","code" => -99,"msg" => "Vui nhập đầy đủ dữ liệu.")));
    }
    if ($phone) {
        $result = $db->fetch_assoc("SELECT * FROM `cron_momo` WHERE `phone` = '" . $phone . "' AND `user_id` = '" . $user_id . "' AND `BALANCE` >= '".$amount."' ORDER BY `id` ASC LIMIT 1", 1);
        if (!$result['phone']) {
            die(json_encode(array(
                "status" => "error",
                "code" => -99,
                "message" => "Không tồn tại tài khoản momo"
            )));
        }
        $result_user = $db->fetch_assoc("SELECT * FROM `accounts` WHERE `username` = '" . $user_id . "' ORDER BY `id` ASC LIMIT 1", 1);
        if (!$result_user['api_key'] || $result_user['api_key'] != $api_key) {
            die(json_encode(array(
                "status" => "error",
                "code" => -100,
                "message" => "API KEY không hợp lệ hoặc chưa được kích hoạt"
            )));
        }
        $comments = $comments ?: $result['noidungtra'];
        $result = $momo->LoadData($result['phone'], $result['user_id'])->SendMoney($tophone,$amount,$comments);
        $data_send = $result['full'];
        $db->query("INSERT INTO `send` (`id`, `momo_id`, `tranId`, `partnerId`, `partnerName`, `amount`, `comment`, `time`, `user_id`, `status`, `message`, `data`,`balance`,`ownerNumber`,`ownerName`) VALUES (NULL, '".$result['tranDList']['ID']."', '".$result['tranDList']['tranId']."', '".$result['tranDList']['partnerId']."', '".$result['tranDList']['partnerName']."', '".$result['tranDList']['amount']."', '".$result['tranDList']['comment']."', '".time()."', '".$user_id."', '".$result['status']."', '".$result['message']."', '".$data_send."','".$result['tranDList']['balance']."','".$result['tranDList']['ownerNumber']."','".$result['tranDList']['ownerName']."')");
        echo json_encode($result);
    } else {
        $result = $db->fetch_assoc("SELECT * FROM `cron_momo` WHERE  `user_id` = '" . $user_id . "' AND `status` = 'success' AND `BALANCE` >= '".$amount."' ORDER BY RAND() LIMIT 1", 1);
        if (!$result['phone']) {
            die(json_encode(array(
                "status" => "error",
                "code" => -99,
                "message" => "Không tồn tại tài khoản momo"
            )));
        }
        $result_user = $db->fetch_assoc("SELECT * FROM `accounts` WHERE `username` = '" . $user_id . "' ORDER BY `id` ASC LIMIT 1", 1);
        if (!$result_user['api_key'] || $result_user['api_key'] != $api_key) {
            die(json_encode(array(
                "status" => "error",
                "code" => -100,
                "message" => "API KEY không hợp lệ hoặc chưa được kích hoạt"
            )));
        }
        $comments = $comments ?: $result['noidungtra'];
        $result = $momo->LoadData($result['phone'],$result['user_id'])->SendMoney($tophone,$amount,$comments);
        $data_send = $result['full'];
        $db->query("INSERT INTO `send` (`id`, `momo_id`, `tranId`, `partnerId`, `partnerName`, `amount`, `comment`, `time`, `user_id`, `status`, `message`, `data`,`balance`,`ownerNumber`,`ownerName`) VALUES (NULL, '".$result['tranDList']['ID']."', '".$result['tranDList']['tranId']."', '".$result['tranDList']['partnerId']."', '".$result['tranDList']['partnerName']."', '".$result['tranDList']['amount']."', '".$result['tranDList']['comment']."', '".time()."', '".$user_id."', '".$result['status']."', '".$result['message']."', '".$data_send."','".$result['tranDList']['balance']."','".$result['tranDList']['ownerNumber']."','".$result['tranDList']['ownerName']."')");
        echo json_encode($result);
    }
}else if ($act == 'sayThanks') {
    $comments = $db->real_escape_string(POST('comments')) ?: "";
    $phone   = $db->real_escape_string(junoo_boc(POST('phone')));
    $user_id = $db->real_escape_string(junoo_boc(POST('user_id')));
    $api_key = $db->real_escape_string(POST('api_key'));
    $tranid = $db->real_escape_string(junoo_boc(POST('tranid')));
    if(!$tranid || !$user_id || !$api_key){
            die(json_encode(array("status" => "error","code" => -99,"msg" => "Vui nhập đầy đủ dữ liệu.")));
    }
        $result = $db->fetch_assoc("SELECT * FROM `cron_momo` WHERE `phone` = '" . $phone . "' AND `user_id` = '" . $user_id . "' ORDER BY `id` ASC LIMIT 1", 1);
        if (!$result['phone']) {
            die(json_encode(array(
                "status" => "error",
                "code" => -99,
                "message" => "Không tồn tại tài khoản momo"
            )));
        }
        $result_user = $db->fetch_assoc("SELECT * FROM `accounts` WHERE `username` = '" . $user_id . "' ORDER BY `id` ASC LIMIT 1", 1);
        if (!$result_user['api_key'] || $result_user['api_key'] != $api_key) {
            die(json_encode(array(
                "status" => "error",
                "code" => -100,
                "message" => "API KEY không hợp lệ hoặc chưa được kích hoạt"
            )));
        }
        $result_tran = $db->fetch_assoc("SELECT * FROM `momo_history` WHERE  `user_id` = '" . $user_id . "' AND `phone` = '".$phone."'  AND `id_tran` = '".$tranid."' LIMIT 1", 1);
        if (!$result_tran['id']) {
            die(json_encode(array(
                "status" => "error",
                "code" => -101,
                "message" => "Không tồn tại giao dịch trên hệ thống."
            )));
        }
        $result = $momo->LoadData($result['phone'],$result['user_id'])->SAY_THANKS($tranid,$comments);
        echo json_encode($result);
}else if ($act == 'resetDay') {
    $db->query("UPDATE `cron_momo` SET `today` = '0', `today_gd` = '0'");
}else if ($act == 'resetMonth') {
    $db->query("UPDATE `cron_momo` SET `month` = '0'");
}

?>