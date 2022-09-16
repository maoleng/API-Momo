<?php
require_once(realpath($_SERVER["DOCUMENT_ROOT"]) .'/core/init.php');
if(!$_POST){load_url('/');}
if (!is_client()) {
    echo json_encode(array('status' => "error", 'msg' => "Vui lòng đăng nhập trước."));
}else{
    if (!$_POST['password'] || !$_POST['newpassword'] || !$_POST['renewpassword']) {
        echo json_encode(array('status' => "error", 'msg' => "Vui lòng nhập đầy đủ thông tin"));exit();
    }
    
    $password = md5(POST('password'));
    $newpassword = md5(POST('newpassword'));
    $renewpassword = md5(POST('renewpassword'));
    
    $check_username = $db->fetch_row("SELECT COUNT(*) FROM `accounts` WHERE `username` = '".$accounts['username']."' AND `password` = '{$password}'");
    // kiểm tra tài khoản
    if(($check_username) == 0){
        echo json_encode(array('status' => "error", 'msg' =>"Mật khẩu cũ không chính xác !"));exit;
    }    
    if($newpassword != $renewpassword) {
                echo json_encode(array('status' => "error", 'msg' =>"Nhập lại mật khẩu không chính xác !"));exit;
    }
    if($newpassword == $password) {
                echo json_encode(array('status' => "error", 'msg' =>"Mật khẩu mới phải khác mật khẩu cũ!"));exit;
    }
    $db->query("UPDATE `accounts` SET `password` = '".$newpassword."' WHERE `username` = '".$accounts['username']."' AND `password` = '{$password}'");
    echo json_encode(array('status' => "success", 'msg' => "Đổi mật khẩu thành công !"));
    exit();
}