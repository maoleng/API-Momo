<?php

session_start();
require_once(realpath($_SERVER["DOCUMENT_ROOT"]) .'/core/init.php');
if(!$_POST){load_url('/');}
if (is_client()) {
    echo json_encode(array('status' => "error", 'msg' => "Bạn đã đăng nhập rồi !"));
}else{
    if (!$_POST['username'] || !$_POST['password']) {
    echo json_encode(array('status' => "error", 'msg' => "Thông tin đăng nhập không được để trống !"));exit();}
    $username = $db->real_escape_string(junoo_boc(POST('username')));
    $password = md5(POST('password'));
    
    if(!preg_match('/^[A-Za-z0-9]{5,24}$/', $username)){
        echo json_encode(array('status' => "error", 'msg' =>"Tài khoản phải từ 6-24 kí tự gồm chữ và số !"));exit;}
    //kiếm tra username
    $check_username = $db->fetch_row("SELECT COUNT(*) FROM `accounts` WHERE `username` = '{$username}' AND `password` = '{$password}'");
    // kiểm tra tài khoản
    if(($check_username) == 0){
      echo json_encode(array('status' => "error", 'msg' =>"Thông tin đăng nhập không chính xác !"));exit;
        
    }    
    
    //Get dữ liệu tài khoản    
    $accounts = $db->fetch_assoc("SELECT * FROM `accounts` WHERE `username` = '{$username}' ", 1);
    //đăng nhập
    $_SESSION['user'] = $accounts['username'];
    $_SESSION['pass'] = $password;
        echo json_encode(array('status' => "success", 'msg' => "Đăng nhập thành công !"));
    exit();
}