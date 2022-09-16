<?php
include 'momo.php';
if (!is_client())
{
    die(json_encode(array(
        "status" => "error",
        "msg" => "Vui lòng đăng nhập trước nhé."
    )));
}
if (GET('act') == 'send')
{
    $sdt = $db->real_escape_string(junoo_boc(POST("momo")));
    if (!$sdt)
    {
        die(json_encode(array(
            "status" => "error",
            "msg" => "Vui nhập số điện thoại momo."
        )));
    }
    $result = $momo->LoadData($sdt, $accounts['username'])->SendOTP();
    if ($result['status'] == "success")
    {
        $json = array(
            "msg" => "OTP Đã Được Gửi Đến Số Điện Thoại Của Bạn!!",
            "status" => "success"
        );
    }
    else
    {
        $json = array(
            "msg" => $result['message'],
            "status" => "error"
        );
    }
    echo json_encode($json);
    exit;

}
else if (GET('act') == 'confirm')
{
    $sdt = $db->real_escape_string(junoo_boc(POST("momo")));
    $password = $db->real_escape_string(junoo_boc(POST("password")));
    $otp = $db->real_escape_string(junoo_boc(POST("otp")));
    if (!$sdt || !$otp || !$password)
    {
        die(json_encode(array(
            "status" => "error",
            "msg" => "Vui nhập đầy đủ dữ liệu."
        )));
    }
    $result = $db->fetch_assoc("SELECT * FROM `cron_momo` WHERE `phone` = '" . $sdt . "' AND `user_id` = '" . $accounts['username'] . "' ORDER BY `id` ASC LIMIT 1", 1);
        if (!$result['phone']) {
            die(json_encode(array(
                "status" => "error",
                "code" => -99,
                "msg" => "Không tồn tại tài khoản momo"
            )));
        }
    $result = $momo->LoadData($sdt, $accounts['username'])->ImportOTP($otp);
    if ($result['status'] == "success")
    {
        $result = $momo->LoadData($sdt, $accounts['username'])->LoginUser($password);
        if ($result['status'] == "success")
        {
            $json = array(
                "msg" => "Thành Công",
                "status" => "success"
            );
        }
        else
        {
            $json = array(
                "msg" => $result['message'],
                "status" => "error"
            );
        }
        echo json_encode($json);
        die;
    }
    else
    {
        $json = array(
            "msg" => $result['message'],
            "status" => "error"
        );
        echo json_encode($json);
        die;
    }

}

