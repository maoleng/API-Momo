<?php
include 'momo.php';
if (!is_client()) {
    die(json_encode(array(
        "status" => "error",
        "msg" => "Vui lòng đăng nhập trước nhé."
    )));
}
if (GET("act") == 'settings') {
    
    $edit_id          = $db->real_escape_string(junoo_boc(POST("edit_id")));
    $edit_password    = $db->real_escape_string(junoo_boc(POST("edit_password")));
    $edit_limit_day   = $db->real_escape_string(junoo_boc(POST("edit_limit_day"))) ?: 30000000;
    $edit_limit_month = $db->real_escape_string(junoo_boc(POST("edit_limit_month"))) ?: 100000000;
    $edit_noidungtra  = $db->real_escape_string(POST("edit_noidungtra"));
    $edit_status      = $db->real_escape_string(junoo_boc(POST("edit_status")));
    $callback_url      = $db->real_escape_string(POST("callback_url"));
    if (!$edit_id || !$edit_password  || !$edit_status) {
        die(json_encode(array(
            "status" => "error",
            "msg" => "Dữ liệu vào không hợp lệ."
        )));
    }
    $data = $db->fetch_assoc("SELECT * FROM `cron_momo` WHERE `id` = '" . $edit_id . "' AND `user_id` = '" . $accounts['username'] . "' LIMIT 1 ", 1);
    if (!$data['phone']) {
        die(json_encode(array(
            "status" => "error",
            "msg" => "Không tồn tại Momo hoặc Momo không phải của bạn."
        )));
    }
    $db->query("UPDATE  `cron_momo` SET `callback_url` = '".$callback_url."', `password` = '" . $edit_password . "',`status` = '" . $edit_status . "',`limit_day` = '" . $edit_limit_day . "',`limit_month` = '" . $edit_limit_month . "',`noidungtra` = '" . $edit_noidungtra . "' WHERE `id` = '" . $edit_id . "' AND `user_id` = '" . $accounts['username'] . "' ");
    die(json_encode(array(
        "status" => "success",
        "msg" => "Thành Công"
    )));
    
    
} else {
    $id = junoo_boc(POST("id"));
    if (!$id) {
        die(json_encode(array(
            "status" => "error",
            "msg" => "Dữ liệu vào không hợp lệ."
        )));
    }
    $data = $db->fetch_assoc("SELECT * FROM `cron_momo` WHERE `id` = '" . $id . "' AND `user_id` = '" . $accounts['username'] . "' LIMIT 1 ", 1);
    if (!$data['phone']) {
        die(json_encode(array(
            "status" => "error",
            "msg" => "Không tồn tại Momo hoặc Momo không phải của bạn."
        )));
    }
    $db->query("DELETE FROM  `cron_momo` WHERE `id` = '" . $id . "' AND `user_id` = '" . $accounts['username'] . "' ");
    die(json_encode(array(
        "status" => "success",
        "msg" => "Thành Công"
    )));
    
}