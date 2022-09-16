<?php
error_reporting(0);

// Require các thư viện PHP
require_once(realpath($_SERVER["DOCUMENT_ROOT"]) .'/core/connect@data.php');
require_once(realpath($_SERVER["DOCUMENT_ROOT"]) .'/core/Session.php');
require_once(realpath($_SERVER["DOCUMENT_ROOT"]) .'/core/Functions.php');
require_once(realpath($_SERVER["DOCUMENT_ROOT"]) .'/core/phpmailer.php');

// Kết nối database
$db = new DB();
$db->connect();
$db->set_char('utf8');

// Thông tin chung
$_DOMAIN = "http://".$_SERVER["SERVER_NAME"]."/";
$root = $_SERVER["DOCUMENT_ROOT"];
//Thời gian
date_default_timezone_set('Asia/Ho_Chi_Minh'); 
$date_current = '';
$date_current = date("Y-m-d H:i:sa");
$date_now = '';
$date_now = date("Y-m-d");
$time_now = time();   
$day = date('d',time());
$month = date('m',time());
$year = date('Y',time());
// Khởi tạo session
$session = new Session();
$session->start();
if(!$_SESSION['csrf-token']) {
   $_SESSION['csrf-token'] = random('qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM123456789', '44');
   $_SESSION['junoo_session'] = createToken();
   setcookie("Junoo",$_SESSION['csrf-token']);
   setcookie("junoo_session", $_SESSION['junoo_session']);
}

// Nếu đăng nhập
if (is_client())
{
    // Lấy dữ liệu tài khoản
    $sql_accounts = "SELECT * FROM accounts WHERE username = '".$db->real_escape_string($_SESSION['user'])."'";
    if ($db->num_rows($sql_accounts))
    {
        $accounts = $db->fetch_assoc($sql_accounts, 1);
        $session_pass = !empty($_SESSION['pass']) ? $_SESSION['pass'] : '';
        if(!empty($session_pass) && $session_pass != $accounts['password']){$session->destroy();die('Hãy đăng nhập lại !');}
    }else{
        $session->destroy();die('Hãy đăng nhập lại !');
    }
    
}

?>