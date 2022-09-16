<?php
require_once(realpath($_SERVER["DOCUMENT_ROOT"]) .'/core/init.php');

//header("content-type: application/json;charset=utf-8");
$momo = new MOMO($db->connect());

class MOMO
{
    //Đưa dữ liệu vào
    private $config = array();
    //Kết nối Database
    private $connect;

    private $BankId = array(
        'BIDV' => array(
            'partnerCode' => '110',
        ),
        'VTB'  => array(
            'partnerCode' => '102',
        ),
        'MB'   => array(
            'partnerCode' => '301',
        ),
        'ACB'  => array(
            'partnerCode' => '115',
        ),
        'VCB'  => array(
            'partnerCode' => '12345',
        ),
        'VPB'  => array(
            'partnerCode' => '103',
        ),
        'VIB'  => array(
            'partnerCode' => '113',
        ),
        'EXB'  => array(
            'partnerCode' => '107',
        ),
        'OCB'  => array(
            'partnerCode' => '104',
        ),
        'SCB'  => array(
            'partnerCode' => '111',
        ),
    );

    private $ohash;

    private $TimeSetUp = 900; // seconds

    private $amount = 100000;

    private $hours = 200;

    private $keys;

    private $send = array();

    private $rsa;

    private $URLAction = array(
        "CHECK_USER_BE_MSG" => "https://api.momo.vn/backend/auth-app/public/CHECK_USER_BE_MSG",//Check người dùng
        "SEND_OTP_MSG"      => "https://api.momo.vn/backend/otp-app/public/SEND_OTP_MSG",//Gửi OTP
        "REG_DEVICE_MSG"    => "https://api.momo.vn/backend/otp-app/public/REG_DEVICE_MSG",// Xác minh OTP
        "QUERY_TRAN_HIS_MSG" => "https://owa.momo.vn/api/QUERY_TRAN_HIS_MSG", // Check ls giao dịch
        "USER_LOGIN_MSG"     => "https://owa.momo.vn/public/login",// Đăng Nhập
        "GENERATE_TOKEN_AUTH_MSG"     => "https://api.momo.vn/backend/auth-app/public/GENERATE_TOKEN_AUTH_MSG",// Get Token
        "QUERY_TRAN_HIS_MSG_NEW" => "https://m.mservice.io/hydra/v2/user/noti",// check ls giao dịch 
        "M2MU_INIT"         => "https://owa.momo.vn/api/M2MU_INIT",// Chuyển tiền
        "M2MU_CONFIRM"      => "https://owa.momo.vn/api/M2MU_CONFIRM",// Chuyển tiền
        "LOAN_MSG"          => "https://owa.momo.vn/api/LOAN_MSG",// yêu cầu chuyển tiền
        'M2M_VALIDATE_MSG'  => 'https://owa.momo.vn/api/M2M_VALIDATE_MSG',// Ko rõ chức năng 
        'CHECK_USER_PRIVATE'=> 'https://owa.momo.vn/api/CHECK_USER_PRIVATE', // Check người dùng ẩn
        'TRAN_HIS_INIT_MSG' => 'https://owa.momo.vn/api/TRAN_HIS_INIT_MSG', // Rút tiền, chuyển tiền
        'TRAN_HIS_CONFIRM_MSG' => 'https://owa.momo.vn/api/TRAN_HIS_CONFIRM_MSG',// rút tiền chuyển tiền
        'GET_CORE_PREPAID_CARD' => 'https://owa.momo.vn/api/sync/GET_CORE_PREPAID_CARD',
        'ins_qoala_phone'   => 'https://owa.momo.vn/proxy/ins_qoala_phone',
        'GET_DETAIL_LOAN'   => 'https://owa.momo.vn/api/GET_DETAIL_LOAN',// Get danh sách yêu cầu chuyển
        'LOAN_UPDATE_STATUS'=> 'https://owa.momo.vn/api/LOAN_UPDATE_STATUS',// Từ chỗi chuyển tiền
        'CANCEL_LOAN_REQUEST'=> 'https://owa.momo.vn/api/CANCEL_LOAN_REQUEST',// Huỷe chuyển tiền
        'LOAN_SUGGEST'      => 'https://owa.momo.vn/api/LOAN_SUGGEST',
        'STANDARD_LOAN_REQUEST'  => 'https://owa.momo.vn/api/STANDARD_LOAN_REQUEST',
        'SAY_THANKS'        => 'https://owa.momo.vn/api/SAY_THANKS',// Gửi lời nhắn khi nhận tiền
        'HEARTED_TRANSACTIONS'=> 'https://owa.momo.vn/api/HEARTED_TRANSACTIONS',
        'VERIFY_MAP'        => 'https://owa.momo.vn/api/VERIFY_MAP',// Liên kết ngân hàng
        'service'           => "https://owa.momo.vn/service",   // Check ngân hàng qua stk
        'NEXT_PAGE_MSG'     => 'https://owa.momo.vn/api/NEXT_PAGE_MSG', // mua thẻ điện thoại
        'dev_backend_gift-recommend' => 'https://owa.momo.vn/proxy/dev_backend_gift-recommend', // check gift
        'ekyc_init'         => 'https://owa.momo.vn/proxy/ekyc_init',  // Xác minh cmnd
        'ekyc_ocr'          => 'https://owa.momo.vn/proxy/ekyc_ocr', // xác minh cmnd
        'GetDataStoreMsg'   => 'https://owa.momo.vn/api/GetDataStoreMsg', // Get danh sách ngân hàng đã chuyển
        'VOUCHER_GET'       => 'https://owa.momo.vn/api/sync/VOUVHER_GET',// get voucher 
        'END_USER_QUICK_REGISTER' => 'https://api.momo.vn/backend/auth-app/public/END_USER_QUICK_REGISTER',// đăng kí
        'AGENT_MODIFY'      => 'https://api.momo.vn/backend/auth-app/api/AGENT_MODIFY',// Cập nhật tên email
        'ekyc_ocr_result'   => 'https://owa.momo.vn/proxy/ekyc_ocr_result',// xác minh cmnd
        'CHECK_INFO'        => 'https://owa.momo.vn/api/CHECK_INFO',// Check hóa đơn
        'BANK_OTP'          => 'https://owa.momo.vn/api/BANK_OTP',// Rút tiền
        'SERVICE_UNAVAILABLE'=> 'https://owa.momo.vn/api/SERVICE_UNAVAILABLE',// Bên bảo mật
        'ekyc_ocr_confirm'  => 'https://owa.momo.vn/proxy/ekyc_ocr_confirm',//Xác minh cmnd
        'sync'              => 'https://owa.momo.vn/api/sync',// Lấy biến động số dư
        'MANAGE_CREDIT_CARD'=> 'https://owa.momo.vn/api/MANAGE_CREDIT_CARD',//Thêm visa marter card
        'UN_MAP'            => 'https://owa.momo.vn/api/UN_MAP',// Hủy liên kết thẻ
        'WALLET_MAPPING'    => 'https://owa.momo.vn/api/WALLET_MAPPING',// Liên kết thẻ
        'NAPAS_CASHIN_INIT_MSG' => 'https://owa.momo.vn/api/NAPAS_CASHIN_INIT_MSG',
        "CARD_GET" => "https://owa.momo.vn/api/sync/CARD_GET",
        'NAPAS_CASHIN_DELETE_TOKEN_MSG' => 'https://owa.momo.vn/api/NAPAS_CASHIN_DELETE_TOKEN_MSG',
        'API_DEFAULT_SOURCE'=> 'https://owa.momo.vn/api/API_DEFAULT_SOURCE'

    );
    private $momo_data_config = array(
        "appVer" => 31062,
        "appCode" => "3.1.6"
        
        );

    
    public function LoadData($phone,$username)
    {
        $select = $this->connect->query("SELECT * FROM `cron_momo` WHERE `phone` = '".$phone."' LIMIT 1 ");
        if($select->num_rows == 0){
            $this->CheckUser($phone,$username);
            return $this;
        }
        $this->config = $select->fetch_assoc();
        if(($this->config['TimeLogin'] < time() - $this->TimeSetUp) && $this->config['authorization'] && $this->config['status'] == 'success'){
            $this->getTokenTimeSetup();
            $select = $this->connect->query("SELECT * FROM `cron_momo` WHERE `phone` = '".$phone."' LIMIT 1 ");
            $this->config = $select->fetch_assoc();
        }
        
        return $this;
    }

    public function __construct($conn)
    {
        $this->connect = $conn;
        return $this;
    }

    public function CheckUser($phone,$username)
    {
        $select = $this->connect->query("SELECT * FROM `cron_momo` WHERE `phone` = '".$phone."' ");
        if($select->num_rows >= 1){
            $this->connect->query("UPDATE `cron_momo` SET `agent_id` = 'underfined',
                                                          `sessionkey` = '',
                                                          `authorization` = 'underfined' WHERE `phone` = '$phone' ");
        }else if($select->num_rows == 0){
            $device = $this->connect->query("SELECT * FROM `device` ORDER BY RAND() LIMIT 1 ")->fetch_assoc();
            $this->connect->query("INSERT INTO `cron_momo` SET `phone` = '".$phone."',
                                                               `user_id` = '".$username."',
                                                               `imei` = '".$this->generateImei()."',
                                                               `SECUREID` = '".$this->get_SECUREID()."',
                                                               `rkey` = '".$this->generateRandom(20)."',
                                                               `AAID` = '".$this->generateImei()."',
                                                               `TOKEN` = '".$this->get_TOKEN()."',
                                                               `device` = '".$device["device"]."',
                                                               `hardware` = '".$device["hardware"]."',
                                                               `facture` = '".$device["facture"]."',
                                                               `status` = 'pending',
                                                               `MODELID` = '".$device["MODELID"]."' 
                                                               
                                                               ");
                                                               
        }
        $this->config = $this->connect->query("SELECT * FROM `cron_momo` WHERE `phone` = '".$phone."' LIMIT 1 ")->fetch_assoc();
        return $this->config;
    }

    public function CheckBeUser()
    {
        $result = $this->CHECK_USER_BE_MSG();
        if(!empty($result["errorCode"])){
            return array(
                "status" => "error",
                "code"   => $result["errorCode"],
                "message"=> $result["errorDesc"]
            );
        }
        $this->connect->query("UPDATE `cron_momo` SET `Name` = '".$result['extra']['NAME']."' WHERE `phone` = '".$this->config['phone']."' ");
        return $result;

    }

    public function SendOTP()
    {
        $result = $this->SEND_OTP_MSG();
        if(!empty($result["errorCode"])){
            return array(
                "status" => "error",
                "code"   => $result["errorCode"],
                "message"=> $result["errorDesc"]
            );
        }
        else if(is_null($result)){
            return array(
                "status" => "error",
                "code"   => -5,
                "message"=> "Hết thời gian truy cập vui lòng đăng nhập lại"
            );
        }
        return array(
            "status"  => "success",
            "message" => "Thành công"
        );

    }

    public function Register($pass, $Name, $email = '')
    {
        if(empty($pass)){
            return array(
                'status' => 'error',
                'message'=> 'Vui lòng điền mật khẩu'
            );
        }
        else if(strlen($pass) != 6){
            return array(
                'status' => 'error',
                'message'=> 'Mật khẩu chứa 6 kí tự là số'
            );
        }
        if(empty($email)){
            $email = $this->generateRandomString(12).'@gmail.com';
        }
        $this->connect->query("UPDATE `cron_momo` SET `Name` = '".$Name."',
                                                      `password` = '".$pass."',
                                                      `email` = '".$email."' WHERE `phone` = '".$this->config['phone']."' ");
        $this->config['password'] = $pass;
        $result = $this->END_USER_QUICK_REGISTER();
        if(!empty($result["errorCode"])){
            return array(
                "status" => "error",
                "code"   => $result["errorCode"],
                "message"=> $result["errorDesc"]
            );
        }
        else if(is_null($result)){
            return array(
                "status" => "error",
                "code"   => -5,
                "message"=> "Hết thời gian truy cập vui lòng đăng nhập lại"
            );
        }
        $extra = $result["extra"];
        $this->connect->query("UPDATE `cron_momo` SET `password` = '".$this->config["password"]."',
                                                      `authorization` = '".$extra["AUTH_TOKEN"]."',
                                                      `agent_id` = '".$result["momoMsg"]["agentId"]."',
                                                      `RSA_PUBLIC_KEY` = '".$extra["REQUEST_ENCRYPT_KEY"]."',
                                                      `sessionkey` = '".$extra["SESSION_KEY"]."' WHERE `phone` = '".$this->config["phone"]."' ");
        return array(
            "status"  => "success",
            "message" => "Thành công"
        );
    }

    public function UpDateProFile()
    {

        $result = $this->AGENT_MODIFY();
        if(!empty($result["errorCode"])){
            return array(
                "status" => "error",
                "code"   => $result["errorCode"],
                "message"=> $result["errorDesc"]
            );
        }
        else if(is_null($result)){
            return array(
                "status" => "error",
                "code"   => -5,
                "message"=> "Hết thời gian truy cập vui lòng đăng nhập lại"
            );
        }
        return array(
            "status"  => "success",
            "message" => "Thành công"
        );

    }

    public function ImportOTP($code)
    {
        $this->config['ohash'] = hash('sha256',$this->config["phone"].$this->config["rkey"].$code);
        $this->connect->query("UPDATE `cron_momo` SET `ohash` = '".$this->config['ohash']."' WHERE `phone` = '".$this->config["phone"]."' ");
        $result = $this->REG_DEVICE_MSG();
        if(!empty($result["errorCode"])){
            $this->connect->query("UPDATE `cron_momo` SET `status` = 'error',`errorDesc` = '".$result["errorDesc"]."' WHERE `phone` = '".$this->config["phone"]."' ");
            return array(
                "status" => "error",
                "code"   => $result["errorCode"],
                "message"=> $result["errorDesc"]
            );
        }else if(is_null($result)){
            $this->connect->query("UPDATE `cron_momo` SET `status` = 'error',`errorDesc` = 'Hết thời gian truy cập vui lòng đăng nhập lại' WHERE `phone` = '".$this->config["phone"]."' ");
            return array(
                "status" => "error",
                "code"   => -5,
                "message"=> "Hết thời gian truy cập vui lòng đăng nhập lại"
            );
        }
        $setupKeyDecrypt = $this->get_setupKey($result["extra"]["setupKey"]);
        $this->connect->query("UPDATE `cron_momo` SET `setupKey` = '".$result["extra"]["setupKey"]."',
                                                      `status` = 'success',
                                                      `setupKeyDecrypt` = '".$setupKeyDecrypt."' WHERE `phone` =  '".$this->config["phone"]."' ");
        $select = $this->connect->query("SELECT * FROM `cron_momo` WHERE `phone` = '".$this->config["phone"]."' LIMIT 1 ");
        $this->config = $select->fetch_assoc();
        return array(
            "status" => "success",
            "message"=> "Thành công"
        );
    }

    public function LoginUser($password = "")
    {
        if($password == ""){
            $result = $this->USER_LOGIN_MSG();
        }else{
            $this->config["password"] = $password;
            $result = $this->USER_LOGIN_MSG();
        }
        if(!empty($result["errorCode"])){
            $this->connect->query("UPDATE `cron_momo` SET `try` = `try` + '1',`status` = 'error',`errorDesc` = '".$result["errorDesc"]."' WHERE `phone` = '".$this->config["phone"]."' ");
            return array(
                "status" => "error",
                "code"   => $result["errorCode"],
                "message"=> $result["errorDesc"]
            );
        }else if(is_null($result)){
            $this->connect->query("UPDATE `cron_momo` SET `try` = `try` + '1',`status` = 'error',`errorDesc` = 'Lỗi Hệ Thống' WHERE `phone` = '".$this->config["phone"]."' ");
            return array(
                "status"  => "error",
                "code"    => -5,
                "message" => "Hết thời gian truy cập vui lòng đăng nhập lại"
            );
        }
        $extra = $result["extra"];
        $BankVerify = ($result['momoMsg']['bankVerifyPersonalid'] == 'null') ? '1' : '2';
        $this->connect->query("UPDATE `cron_momo` SET `password` = '".$this->config["password"]."',
                                                      `authorization` = '".$extra["AUTH_TOKEN"]."',
                                                      `try` = '0',
                                                      `BankVerify`    = '".$BankVerify."',
                                                      `agent_id` = '".$result["momoMsg"]["agentId"]."',
                                                      `RSA_PUBLIC_KEY` = '".$extra["REQUEST_ENCRYPT_KEY"]."',
                                                      `BALANCE` = '".$extra["BALANCE"]."',
                                                      `refreshToken` = '".$extra["REFRESH_TOKEN"]."',
                                                      `sessionkey` = '".$extra["SESSION_KEY"]."',
                                                      `errorDesc` = '".$result["errorCode"]."',
                                                      `status` = 'success',
                                                      `errorDesc` = 'Thành Công',
                                                      `TimeLogin`  = '".time()."' WHERE `phone` = '".$this->config["phone"]."' ");
        return array(
                "status"  => "success",
                "message" => "Thành công",
                "data" => json_encode($result)
            );;
    }
    
    public function getTokenTimeSetup()
    {
        if($this->config['TimeLogin'] > time() - $this->TimeSetUp ){
            return array(
                "momo" => $this->config["phone"],
                'status' => 'success',
                'message'=> 'Đăng nhập thành công'
            );
        }
        $result = $this->GENERATE_TOKEN_AUTH_MSG();
        if(!empty($result["errorCode"])){
            $this->connect->query("UPDATE `cron_momo` SET `try` = `try` + '1',`status` = 'error',`errorDesc` = '".$result["errorDesc"]."' WHERE `phone` = '".$this->config["phone"]."' ");
            return array(
                "momo" => $this->config["phone"],
                "status" => "error",
                "code"   => $result["errorCode"],
                "message"=> $result["errorDesc"],
                "full" => json_encode($result)
            );
        }else if(is_null($result)){
            $this->connect->query("UPDATE `cron_momo` SET `try` = `try` + '1',`status` = 'error',`errorDesc` = 'Lỗi Hệ Thống' WHERE `phone` = '".$this->config["phone"]."' ");
            return array(
                "momo" => $this->config["phone"],
                "status"  => "error",
                "code"    => -5,
                "message" => "Hết thời gian truy cập vui lòng đăng nhập lại",
                "full" => json_encode($result)
            );
        }
        $extra = $result["extra"];
        $this->connect->query("UPDATE `cron_momo` SET 
                                                      `authorization` = '".$extra["AUTH_TOKEN"]."',
                                                      `try` = '0',
                                                      `RSA_PUBLIC_KEY` = '".$extra["REQUEST_ENCRYPT_KEY"]."',
                                                      `sessionkey` = '".$extra["SESSION_KEY"]."',
                                                      `errorDesc` = '".$result["errorCode"]."',
                                                      `errorDesc` = 'Thành Công',
                                                      `TimeLogin`  = '".time()."' WHERE `phone` = '".$this->config["phone"]."' ");
        return array(
            "momo" => $this->config["phone"],
            "status" => "success",
            "balance"  => (int)$extra["BALANCE"],
            "message"=> "Thành công",
            "full" => json_encode($result)
        );
    }
    
    public function LoginTimeSetup()
    {
        if($this->config['TimeLogin'] > time() - $this->TimeSetUp ){
            return array(
                "momo" => $this->config["phone"],
                'status' => 'success',
                'message'=> 'Đăng nhập thành công'
            );
        }
        $result = $this->USER_LOGIN_MSG();
        if(!empty($result["errorCode"])){
            $this->connect->query("UPDATE `cron_momo` SET `try` = `try` + '1',`status` = 'error',`errorDesc` = '".$result["errorDesc"]."' WHERE `phone` = '".$this->config["phone"]."' ");
            return array(
                "momo" => $this->config["phone"],
                "status" => "error",
                "code"   => $result["errorCode"],
                "message"=> $result["errorDesc"]
            );
        }else if(is_null($result)){
            $this->connect->query("UPDATE `cron_momo` SET `try` = `try` + '1',`status` = 'error',`errorDesc` = 'Lỗi Hệ Thống' WHERE `phone` = '".$this->config["phone"]."' ");
            return array(
                "momo" => $this->config["phone"],
                "status"  => "error",
                "code"    => -5,
                "message" => "Hết thời gian truy cập vui lòng đăng nhập lại"
            );
        }
        $extra = $result["extra"];
        $BankVerify = ($result['momoMsg']['bankVerifyPersonalid'] == 'null') ? '1' : '2';
        $this->connect->query("UPDATE `cron_momo` SET `password` = '".$this->config["password"]."',
                                                      `authorization` = '".$extra["AUTH_TOKEN"]."',
                                                      `try` = '0',
                                                      `BankVerify`    = '".$BankVerify."',
                                                      `agent_id` = '".$result["momoMsg"]["agentId"]."',
                                                      `RSA_PUBLIC_KEY` = '".$extra["REQUEST_ENCRYPT_KEY"]."',
                                                      `refreshToken` = '".$extra["REFRESH_TOKEN"]."',
                                                      `BALANCE` = '".$extra["BALANCE"]."',
                                                      `sessionkey` = '".$extra["SESSION_KEY"]."',
                                                      `errorDesc` = '".$result["errorCode"]."',
                                                      `status` = 'success',
                                                      `errorDesc` = 'Thành Công',
                                                      `TimeLogin`  = '".time()."' WHERE `phone` = '".$this->config["phone"]."' ");
        return array(
            "momo" => $this->config["phone"],
            "status" => "success",
            "balance"  => (int)$extra["BALANCE"],
            "message"=> "Thành công",
            "full" => json_encode($result)
        );
    }

    public function SendImage($phone, $image)
    {
          if($phone == ''){
                return array(
                      'status' => 'error',
                      'message'=> 'Vui lòng không để trỗng số điện thoại'
                );
          }

          $results = $this->CHECK_USER_PRIVATE($phone);
          if(!empty($results["errorCode"])){
                return array(
                    "status" => "error",
                    "code"   => $results["errorCode"],
                    "message" => $results["errorDesc"]
                );
          }
          else if(is_null($results)){
                return array(
                    "status" => "error",
                    "code"   => -5,
                    "message"=> "Hết thời gian truy cập vui lòng đăng nhập lại"
                );
          }
          $this->image = $image;
          $result = $this->CreatLink();
          if($result['status'] == 'error'){
                return array(
                      'status' => 'error',
                      'message'=> 'Đã xảy ra lỗi khi tạo đường dẫn'
                );
          }
          $image = $result['image'];
          $this->send = array(
                'image' => $image,
                'phone'   => $phone,
                'name'    => $results["extra"]["NAME"]
          );
          $result = $this->GetGroupId();
          if(!empty($result['success'])){
              $this->send['roomId'] = $result['json']['room']['id'];
              $this->Mess();

              $result = $this->CheckMessage();
              if(!empty($result['success'])){
                  $dataMess = $result['json']['data'];
                  foreach($dataMess as $item){
                      $url = $item['parts']['payload']['url'];
                      if($url == $this->send['image']){

                          return array(
                            'status' => 'success',
                            'message'=> 'Đã gửi tin nhắn thành công'
                          );
                      }

                  }

              }
              return array(
                    'status' => 'error',
                    'message'=> 'Đã xảy ra lỗi vui lòng thử lại'
                 );

        }
        else{
              return array(
                    'status' => 'error',
                    'message'=> 'Đã xảy ra lỗi vui lòng thử lại'
              );
        }
    }

    public function SendMess($phone, $message = '')
    {
          if($phone == ''){
                return array(
                      'status' => 'error',
                      'message'=> 'Vui lòng không để trỗng số điện thoại'
                );
          }

          $result = $this->CHECK_USER_PRIVATE($phone);
          if(!empty($result["errorCode"])){
                return array(
                    "status" => "error",
                    "code"   => $result["errorCode"],
                    "message" => $result["errorDesc"]
                );
          }
          else if(is_null($result)){
                return array(
                    "status" => "error",
                    "code"   => -5,
                    "message"=> "Hết thời gian truy cập vui lòng đăng nhập lại"
                );
          }
          $this->send = array(
                'message' => $message,
                'phone'   => $phone,
                'name'    => $result["extra"]["NAME"]
          );

          $result = $this->GetGroupId();
          if(!empty($result['success'])){
                $this->send['roomId'] = $result['json']['room']['id'];
                $this->Mess();
                $result = $this->CheckMessage();
                return $result;
                if(!empty($result['success'])){
                    $dataMess = $result['json']['data'];
                    foreach($dataMess as $item){
                        $mess = $item['parts']['payload']['content'];

                        if($mess == $this->send['message']){

                            return array(
                              'status' => 'success',
                              'message'=> 'Đã gửi tin nhắn thành công'
                            );
                        }

                    }

                }
                return array(
                      'status' => 'error',
                      'message'=> 'Đã xảy ra lỗi vui lòng thử lại'
                   );

          }
          else{
                return array(
                      'status' => 'error',
                      'message'=> 'Đã xảy ra lỗi vui lòng thử lại'
                );
          }

    }
    public function CheckName($receiver)
    {
        $result = $this->CHECK_USER_PRIVATE($receiver);
        if(!empty($result["errorCode"])){
            return array(
                "status" => "error",
                "code"   => $result["errorCode"],
                "message" => $result["errorDesc"]
            );
        }else if(is_null($result)){
            return array(
                "status" => "error",
                "code"   => -5,
                "message"=> "Hết thời gian truy cập vui lòng đăng nhập lại"
            );
        }
        if(!empty($result['extra']['NAME'])){
            $phoneName = $result['extra']['NAME'];
        }
        else{
            $phoneName = 'Không tìm thấy';
        }
        return array(
            "status"  => "success",
            'name'    => $phoneName,
            "message" => "Thành công"
        );

    }
    public function checkNameBank($bankCode, $BankId){
        
        if(empty($bankCode)){
            return array(
                'status'  => 'error',
                'code'    => -5,
                'message' => 'Thất bại thiếu Bank Code'
            );
        }
        if(empty($BankId)){
            return array(
                'status'  => 'error',
                'code'    => -5,
                'message' => 'Thất bại thiếu số tài khoản cần chuyển'
            );
        }
        $result = $this->service();
        if(empty($result)){
            return array(
                'status' => 'error',
                'code'   => -5,
                'message'=> 'Vui lòng chờ thêm ít thời gian rồi mua lại'
            );
        }
        if(!empty($result['errorCode'])){
            return array(
                "status" => "error",
                "code"   => $result["errorCode"],
                "message"=> $result["errorDesc"]
            );
        }
        $napasBanks = $result['napasBanks'];
        foreach ($napasBanks as $item){
            if($item['bankCode'] == $bankCode){
                $this->send = array(
                    'bankCode' => $bankCode,
                    'bankName' => $item['shortBankName'],
                    'accId'    => $BankId,
                    'shortBankName' => $item['bankName'],
                    
                );
                break;
            }
        }
        if(empty($this->send['shortBankName'])){
            return array(
                'status' => 'error',
                'code'   => -10,
                'message'=> 'Thất bại Bank Code không tồn tại ngân hàng nào'
            );
        }
        $result = $this->CheckBank();
        if(empty($result)){
            return array(
                'status' => 'error',
                'code'   => -5,
                'message'=> 'Vui lòng chờ thêm ít thời gian nhé.'
            );
        }
        if(!empty($result['errorCode'])){
            return array(
                "status" => "error",
                "code"   => $result["errorCode"],
                "message"=> 'Thất bại trong khi Check Bank'
            );
        }
        return array(
                "status" => "success",
                "message"=> 'Thành công.',
                "name" => $result['benfAccount']['accName']
            );
        
    }
    
    public function SendMoney($receiver,$amount = 100,$comment = "")
    {
        $result = $this->CHECK_USER_PRIVATE($receiver);
        if(!empty($result["errorCode"]) ){
            return array(
                "status" => "error",
                "code"   => $result["errorCode"],
                "message" => $result["errorDesc"],
                "full" => json_encode($result)
            );
        }else if(is_null($result)){
            return array(
                "status" => "error",
                "code"   => -5,
                "message"=> "Hết thời gian truy cập vui lòng đăng nhập lại"
            );
        }
        $results = $this->M2M_VALIDATE_MSG($receiver, $comment);
        if(!empty($result["errorCode"]) && $result["errorDesc"] != "Lỗi cơ sở dữ liệu. Quý khách vui lòng thử lại sau"){
            return array(
                "status" => "error",
                "code"   => $result["errorCode"],
                "message"=> $result["errorDesc"],
                "full" => json_encode($result)
            );
        }else if(is_null($result)){
            return array(
                "status" => "error",
                "code"   => -5,
                "message"=> "Đã xảy ra lỗi ở momo hoặc bạn đã hết hạn truy cập vui lòng đăng nhập lại"
            );
        }
        $message = $results['momoMsg']['message'];
        $this->send = array(
            "amount" => (int)$amount,
            "comment"=> $message,
            "receiver"=> $receiver,
            "partnerName"=> $result["extra"]["NAME"]
        );
        $result = $this->M2MU_INIT();
        if(!empty($result["errorCode"]) && $result["errorDesc"] != "Lỗi cơ sở dữ liệu. Quý khách vui lòng thử lại sau"){
            return array(
                "status" => "error",
                "code"   => $result["errorCode"],
                "message"=> $result["errorDesc"]
            );
        }else if(is_null($result)){
            return array(
                "status" => "error",
                "code"   => -5,
                "message"=> "Đã xảy ra lỗi ở momo hoặc bạn đã hết hạn truy cập vui lòng đăng nhập lại"
            );
        }else{
            $ID = $result["momoMsg"]["replyMsgs"]["0"]["ID"];
            $result = $this->M2MU_CONFIRM($ID);
            $balance = $result["extra"]["BALANCE"];
            $tranHisMsg = $result["momoMsg"]["replyMsgs"]["0"]["tranHisMsg"];
            if($result["errorDesc"] == "Lỗi cơ sở dữ liệu. Quý khách vui lòng thử lại sau") {
                $tranHisMsg["desc"] = $tranHisMsg["desc"] ?: "Lỗi cơ sở dữ liệu. Quý khách vui lòng thử lại sau";
            }
            if($tranHisMsg["status"] != 999 && $result["errorDesc"] != "Lỗi cơ sở dữ liệu. Quý khách vui lòng thử lại sau"){
                return array(
                    "status"   => "error",
                    "message"  => $tranHisMsg["desc"],
                    "tranDList"=> array(
                        "balance" => $balance,
                        "ID"   => $tranHisMsg["ID"],
                        "tranId"=> $tranHisMsg["tranId"],
                        "partnerId"=> $tranHisMsg["partnerId"],
                        "partnerName"=> $tranHisMsg["partnerName"],
                        "amount"   => $tranHisMsg["amount"],
                        "comment"  => (empty($tranHisMsg["comment"])) ? "" : $tranHisMsg["comment"],
                        "status"   => $tranHisMsg["status"],
                        "desc"     => $tranHisMsg["desc"],
                        "ownerNumber" => $tranHisMsg["ownerNumber"],
                        "ownerName"=> $tranHisMsg["ownerName"],
                        "millisecond" => $tranHisMsg["finishTime"]
                    ),
                    "full" => json_encode($result)
                );
            }else{
                $this->connect->query("UPDATE `cron_momo` SET `BALANCE` = '".$balance."',`today` = `today` + '".$amount."',`month` = `month` + '".$amount."',`today_gd` = `today_gd` + 1 WHERE `phone` = '".$this->config["phone"]."' ");
                return array(
                    "status" => "success",
                    "message"=> $tranHisMsg["desc"],
                    "tranDList" => array(
                        "balance" => $balance,
                        "ID"    => $tranHisMsg["ID"],
                        "tranId"=> $tranHisMsg["tranId"],
                        "partnerId"=> $tranHisMsg["partnerId"],
                        "partnerName"=> $tranHisMsg["partnerName"],
                        "amount"     => $tranHisMsg["amount"],
                        "comment"    => (empty($tranHisMsg["comment"])) ? "" : $tranHisMsg["comment"],
                        "status"     => $tranHisMsg["status"],
                        "desc"       => $tranHisMsg["desc"],
                        "ownerNumber"=> $tranHisMsg["ownerNumber"],
                        "ownerName"  => $tranHisMsg["ownerName"],
                        "millisecond"=> $tranHisMsg["finishTime"]
                    ),
                    "full" => json_encode($result)
                );
            }

        }
    }
    
    public function SendMoneyBank($bankCode, $BankId, $amount = 20000, $comment = '')
    {}
    public function CheckCard()
    {
        $result = $this->CARD_GET();
        if(empty($result)){
            return array(
                "status" => "error",
                "code"   => -5,
                "message"=> 'Hết thời gian đăng nhập vui lòng đăng nhập lại'
            );
        }
        if(!empty($result["errorCode"])){
            return array(
                "status" => "error",
                "code"   => $result["errorCode"],
                "message"=> $result["errorDesc"]
            );
        }
        if(empty($result['momoMsg']['cards'])){
            return array(
                'status' => 'error',
                'message'=> 'Tài khoản chưa được xác minh thẻ nào'
            );
        }
        $InfoCard = $result['momoMsg']['cards']['0'];
        return array(
            'status' => 'success',
            'message'=> 'Thành công',
            'cards' => $InfoCard
        );

    }

    public function DirectDeposit($phone = '',$amount = 20000, $type = 'VIETTEL' )
    {
        $this->amount = $amount;
        if(empty($phone)){
            return array(
                'status' => 'error',
                'message' => 'Vui lòng điền số điện thoại cần nạp'
            );
        }
        $phoneName = $this->CHECK_USER_PRIVATE($phone);
        if(empty($phoneName['extra']['NAME'])){
            $phoneName = $phoneName['extra']['NAME'];
        }
        else{
            $phoneName = '';
        }
        $io = (int) $amount / 1000;
        switch ($type){
            case 'MOBIPHONE': 
                $this->send['ownerName'] = 'TUMBF'.$io;
                $this->send['serviceId'] = 'topup_mobiphone';
                break;
            case 'VINAPHONE':
                $this->send['ownerName'] = 'TUVNP'.$io;
                $this->send['serviceId'] = 'topup_Vinaphone';
                break;
            case 'VIETNAMOBLIE':
                $this->send['ownerName'] = 'TUVNM'.$io;
                $this->send['serviceId'] = 'toup_Vietnamobile';
                break;
            default :
                $this->send['ownerName'] = 'TUVTT'.$io;
                $this->send['serviceId'] = 'topup_Viettel';
                break;
        }
        $tranHisMsg = array (
            'user' => $this->config['phone'],
            'quantity' => 1,
            'pageNumber' => 1,
            'extras' => '{"vpc_CardType":"SML","agent_id":63827211,"vpc_TicketNo":"27.73.19.190","vpc_PaymentGateway":""}',
            '_class' => 'mservice.backend.entity.msg.TranHisMsg',
            'serviceId' => $this->send['serviceId'],
            'ownerName' => $this->send['ownerName'],
            'originalAmount' => (int) $amount,
            'amount' => (int) $amount,
            'partnerId' => $phone,
            'partnerExtra1' => $phoneName,
            'phoneName' => $phoneName,
            'phoneNumber' => $phone,
            'serviceName' => 'Trả trước',
            'clientTime' => $this->get_microtime() - 5000,
            'category' => 11,
            'tranType' => 3,
            'moneySource' => 1,
            'partnerCode' => 'momo',
            'rowCardId' => '',
            'giftId' => '',
            'useVoucher' => 0,
            'prepaidIds' => '',
            'usePrepaid' => 0,
        );
        $result = $this->TRAN_HIS_INIT_MSG($tranHisMsg);
        if(empty($result)){
            return array(
                "status" => "error",
                "code"   => -5,
                "message"=> 'Hết thời gian đăng nhập vui lòng đăng nhập lại'
            );
        }
        if(!empty($result["errorCode"])){
            return array(
                "status" => "error",
                "code"   => $result["errorCode"],
                "message"=> $result["errorDesc"]
            );
        }
        $tranHisMsg = $result['momoMsg']['tranHisMsg'];
        $result = $this-> TRAN_HIS_CONFIRM_MSG($tranHisMsg);
        return $result;


    }

    public function ToUpMoney($amount = 10000, $BankName)
    {
        $this->amount = (int) $amount;
        if(empty($this->config['rowCardId'])){
            $history = $this->QUERY_TRAN_HIS_MSG(720);
            if(!empty($history)){
                $List = $history["momoMsg"]["tranList"];
                foreach ($List as $item){
                    if(!empty($item['rowCardId'])){

                        //Lưu ID Ngân Hàng
                        $this->connect->query("UPDATE `cron_momo` SET `rowcardId` = '".$item['rowCardId']."' WHERE `phone` = '".$this->config['phone']."' ");
                        $this->config['rowCardId'] = $item['rowCardId']; break;
                    }
                }
            }
        }
        if(empty($this->config['rowCardId'])){
            return array(
                'status' => 'error',
                'code'   => -5,
                'message'=> 'Vui lòng hãy vào App và thực hiện nạp hoặc rút tiền về tài khoản ngân hàng lần đầu để lấy ID Ngân Hàng'
            );
        }
        $this->send = array(
            'Name' => $this->config['Name'],
            'category' => 11,
            'tranType' => 1,
            'moneySource' => 2,
            'rowCardId'   => $this->config['rowCardId']
        );
        $tranHisMsg =   array (
            'clientTime' => $this->get_microtime() - 300,
            'tranType' => 1,
            'comment' => '',
            'amount' => (int) $amount,
            'partnerCode' => $this->BankId[$BankName]['partnerCode'],
            '_class' => 'mservice.backend.entity.msg.TranHisMsg',
            'moneySource' => 2,
            'rowCardId' => $this->config['rowCardId'],
            'giftId' => '',
            'useVoucher' => 0,
            'prepaidIds' => '',
            'usePrepaid' => 0,
            'extras' => '{"vpc_CardType":"SML","vpc_TicketNo":"171.238.63.60","vpc_PaymentGateway":""}',
          );
        $result = $this->TRAN_HIS_INIT_MSG($tranHisMsg);
        if(empty($result)){
            return array(
                'status' => 'error',
                'code'   => -5,
                'message'=> 'Vui lòng chờ thêm ít thời gian rồi rút lại TRAN_HIS_INIT_MSG'
            );
        }
        if(!empty($result['errorCode'])){
            return array(
                "status" => "error",
                "code"   => $result["errorCode"],
                "message"=> $result["errorDesc"]
            );
        }
        else if(empty($result['momoMsg']['ID'])){
            return array(
                'status' => 'error',
                'code'   => -5,
                'message'=> 'Đã xảy ra lỗi trong khi tạo đơn rút tiền'
            );
        }
        $result = $this->BANK_OTP($result['momoMsg']['tranHisMsg']);
        if(empty($result)){
            return array(
                'status' => 'error',
                'code'   => -5,
                'message'=> 'Vui lòng chờ thêm ít thời gian rồi rút lại BANK_OTP'
            );
        }
        if(!empty($result['errorCode'])){
            return array(
                "status" => "error",
                "code"   => $result["errorCode"],
                "message"=> $result["errorDesc"]
            );
        }
        else if(empty($result['momoMsg']['ID'])){
            return array(
                'status' => 'error',
                'code'   => -5,
                'message'=> 'Đã xảy ra lỗi trong khi tạo đơn rút tiền'
            );
        }
        $result = $this->TRAN_HIS_CONFIRM_MSG($result['momoMsg']);
        if(empty($result)){
            return array(
                'status' => 'error',
                'code'   => -5,
                'message'=> 'Vui lòng chờ thêm ít thời gian rồi rút lại TRAN_HIS_CONFIRM_MSG'
            );
        }
        if(!empty($result['errorCode'])){
            return array(
                "status" => "error",
                "code"   => $result["errorCode"],
                "message"=> $result["errorDesc"]
            );
        }
        else if(empty($result['momoMsg']['ID'])){
            return array(
                'status' => 'error',
                'code'   => -5,
                'message'=> 'Đã xảy ra lỗi trong khi tạo đơn rút tiền'
            );
        }
        $balance = $result["extra"]["BALANCE"];
        $tranHisMsg = $result["momoMsg"]["tranHisMsg"];
        if($tranHisMsg["status"] == 999){
        $this->connect->query("UPDATE `cron_momo` SET `BALANCE` = '".$balance."' WHERE `phone` = '".$this->config["phone"]."' ");
                return array(
                    "status" => "success",
                    "message"=> $tranHisMsg["desc"],
                    "tranDList" => array(
                        "balance" => $balance,
                        "ID"    => $tranHisMsg["ID"],
                        "tranId"=> $tranHisMsg["tranId"],
                        "status"=> $tranHisMsg["status"],
                        "amount"     => $tranHisMsg["originalAmount"],
                        "ownerNumber"=> $tranHisMsg["ownerNumber"],
                        "millisecond"=> $tranHisMsg["finishTime"]
                    ),
                    "full" => json_encode($result)
                );
        }else{
            return array(
                    "status" => "error",
                    "message"=> $tranHisMsg["desc"],
                    "tranDList" => array(
                        "balance" => $balance,
                        "ID"    => $tranHisMsg["ID"],
                        "tranId"=> $tranHisMsg["tranId"],
                        "status"=> $tranHisMsg["status"],
                        "amount"     => $tranHisMsg["originalAmount"],
                        "ownerNumber"=> $tranHisMsg["ownerNumber"],
                        "ownerName"=> $this->config["Name"],
                        "millisecond"=> $tranHisMsg["finishTime"]
                    ),
                    "full" => json_encode($result)
                );
        }
    }

    public function WithDraw($amount = 50000)
    {
        $this->amount = $amount;
        if(empty($this->config['rowCardId'])){
            $history = $this->QUERY_TRAN_HIS_MSG(720);
            if(!empty($history)){
                $List = $history["momoMsg"]["tranList"];
                foreach ($List as $item){
                    if(!empty($item['rowCardId'])){

                        //Lưu ID Ngân Hàng
                        $this->connect->query("UPDATE `cron_momo` SET `rowcardId` = '".$item['rowCardId']."' WHERE `phone` = '".$this->config['phone']."' ");
                        $this->config['rowCardId'] = $item['rowCardId']; break;
                    }
                }
            }
        }
        if(empty($this->config['rowCardId'])){
            return array(
                'status' => 'error',
                'code'   => -5,
                'message'=> 'Vui lòng hãy vào App và thực hiện nạp hoặc rút tiền về tài khoản ngân hàng lần đầu để lấy ID Ngân Hàng'
            );
        }
        $this->send = array(
                'Name' => $this->config['Name'],
                'category' => 11,
                'tranType' => 1,
                'moneySource' => 2,
                'rowCardId'   => $this->config['rowCardId']
        );
        $tranHisMsg = array(
            'user' => $this->config['phone'],
            "_class"    => "mservice.backend.entity.msg.TranHisMsg",
            "originalAmount" => $this->amount,
            "amount"    => $this-> amount,
            "partnerExtra1" => $this->config['Name'],
            "partnerName"=> $this->config['Name'],
            "clientTime" => ($this->get_microtime() - 1029),
            "category"   => 11,
            "tranType"   => 2,
            "moneySource"=> 2,
            "partnerCode"=> "110",
            "rowCardId"  => $this->config['rowCardId']
        );
        $result = $this-> TRAN_HIS_INIT_MSG($tranHisMsg);
        if(empty($result)){
            return array(
                'status' => 'error',
                'code'   => -5,
                'message'=> 'Vui lòng chờ thêm ít thời gian rồi rút lại 2'
            );
        }
        if(!empty($result['errorCode'])){
            return array(
                "status" => "error",
                "code"   => $result["errorCode"],
                "message"=> $result["errorDesc"]
            );
        }
        else if(empty($result['momoMsg']['ID'])){
            return array(
                'status' => 'error',
                'code'   => -5,
                'message'=> 'Đã xảy ra lỗi trong khi tạo đơn rút tiền'
            );
        }
        $tranHisMsg = $result['momoMsg']['tranHisMsg'];
        $result = $this-> TRAN_HIS_CONFIRM_MSG($tranHisMsg);
        return $result;
    }

    public function RequestMoney($receiver,$amount = 100,$comment = "")
    {
        $this->send = array(
            "amount"  => (int)$amount,
            "comment" => $this->get_string($comment),
            "receiver"=> $receiver,
        );
        $result = $this->LOAN_MSG();
        if(empty($result)){
            return array(
                "status" => "error",
                "code"   => -5,
                "message"=> 'Hết thời gian đăng nhập vui lòng đăng nhập lại'
            );
        }
        if(!empty($result["errorCode"])){
            return array(
                "status" => "error",
                "code"   => $result["errorCode"],
                "message"=> $result["errorDesc"]
            );
        }
        $tranHisMsg = $result["momoMsg"]["replyMsgs"]["1"]["tranHisMsg"];
        return array(
            "status"  => "success",
            "message" => "Yêu cầu chuyển tiền thành công",
            "tranDList" =>array(
                "ID"       => $tranHisMsg["ID"],
                "partnerId"=> $tranHisMsg["partnerId"],
                "partnerName" => $tranHisMsg["partnerName"],
                "amount"   => $tranHisMsg["amount"],
                "comment"  => (empty($tranHisMsg["comment"])) ? "" : $tranHisMsg["comment"],
                "millisecond" => $tranHisMsg["clientTime"]
            )
        );
    }

    private function adapter_kyb()
    {
        $microtime = $this->get_microtime();
        $requestkeyRaw = $this->generateRandom(32);
        $requestkey = $this->RSA_Encrypt($this->config["RSA_PUBLIC_KEY"],$requestkeyRaw);
        $header = array(
            "agent_id: ".$this->config["agent_id"],
            "user_phone: ".$this->config["phone"],
            "sessionkey: ".$this->config["sessionkey"],
            "authorization: Bearer ".$this->config["authorization"],
            "userid: ".$this->config["phone"],
            "requestkey: ".$requestkey,
            "Host: owa.momo.vn"
            );
        $Data = array (
            'requestId' => 'bd606a22-e9b3-79eb-2753-6d7127d46b7d',
            'debitor' => '0334506791',
            'type' => 1,
            'source' => 2,
            'checkinfoType' => 3,
            'serviceCode' => 'adapter_kyb',
            'reference1' => '',
            'reference2' => '',
            'user' => '0334506791',
            'agent' => '0334506791',
            'appCode' => $this->momo_data_config("appCode"),
            'appVer' => $this->momo_data_config("appVer"),
            'lang' => 'vi',
            'deviceOS' => 'ANDROID',
            'channel' => 'APP',
            'buildNumber' => 491,
            'appId' => 'vn.momo.dynamicform',
        );
        $Data = array (
            'requestId' => 'bd607684-eead-400b-96d5-8599a5e9ff2e',
            'debitor' => '0334506791',
            'type' => 1,
            'source' => 2,
            'checkinfoType' => 4,
            'serviceCode' => 'adapter_kyb',
            'reference1' => '',
            'reference2' => '',
            'user' => '0334506791',
            'agent' => '0334506791',
            'appCode' => $this->momo_data_config("appCode"),
            'appVer' => $this->momo_data_config("appVer"),
            'lang' => 'vi',
            'deviceOS' => 'ANDROID',
            'channel' => 'APP',
            'buildNumber' => 491,
            'appId' => 'vn.momo.dynamicform',
        );
        $Data = array (
            'requestId' => 'bd608165-3564-9966-bf87-5c50af034091',
            'parentPlaceId' => 349,
            'parentPlaceName' => 'An Giang',
            'type' => 1,
            'source' => 2,
            'checkinfoType' => 1,
            'serviceCode' => 'adapter_kyb',
            'reference1' => '',
            'reference2' => '',
            'user' => '0334506791',
            'agent' => '0334506791',
            'debitor' => '0334506791',
            'appCode' => $this->momo_data_config("appCode"),
            'appVer' => $this->momo_data_config("appVer"),
            'lang' => 'vi',
            'deviceOS' => 'ANDROID',
            'channel' => 'APP',
            'buildNumber' => 491,
            'appId' => 'vn.momo.dynamicform',
        );
        $Data = array (
            'requestId' => 'bd60825e-dc02-f828-dd35-433b12e1625f',
            'parentPlaceId' => 105201,
            'parentPlaceName' => 'An Phú',
            'type' => 1,
            'source' => 2,
            'checkinfoType' => 1,
            'serviceCode' => 'adapter_kyb',
            'reference1' => '',
            'reference2' => '',
            'user' => '0334506791',
            'agent' => '0334506791',
            'debitor' => '0334506791',
            'appCode' => $this->momo_data_config("appCode"),
            'appVer' => $this->momo_data_config("appVer"),
            'lang' => 'vi',
            'deviceOS' => 'ANDROID',
            'channel' => 'APP',
            'buildNumber' => 491,
            'appId' => 'vn.momo.dynamicform',
        );
        $Data = array (
            'requestId' => 'bd608ebd-f504-b761-8a4c-75dcc3885761',
            'debitor' => '0334506791',
            'data' => 
            array (
              'email' => 'vannampro01@gmail.com',
              'kyb_status' => -1,
              'buLicenseImg1' => 'data:image/png;base64,
          ',
              'buLicenseImg2' => 'data:image/png;base64,
          ',
              'otherDocument1' => 'data:image/png;base64,
          ',
              'otherDocument2' => 'data:image/png;base64,
          ',
              'otherDocument3' => 'data:image/png;base64,',
              'enterpriseName' => 'NO NAME',
              'taxCode' => '1880525',
              'businessCode' => 'Fjjgvbb',
              'street' => '1dvvbv',
              'representName' => 'NGUYEN VAN NAM',
              'representTitle' => 'Gggg',
              'representEmail' => 'vannampro01@gmail.com',
              'representPhone' => '0334506791',
              'businessArea' => 
              array (
                'value' => 27,
                'text' => 'Appstore',
                'text_en' => 'Appstore',
              ),
              'businessType' => 
              array (
                'value' => 74,
                'text' => 'Báo chí',
                'text_en' => 'Press',
              ),
              'city' => 
              array (
                'value' => 349,
                'text' => 'An Giang',
              ),
              'district' => 
              array (
                'value' => 105201,
                'text' => 'An Phú',
              ),
              'ward' => 
              array (
                'value' => 105209,
                'text' => 'Phú Hội',
              ),
              'walletId' => '0334506791',
            ),
            'type' => 1,
            'source' => 2,
            'checkinfoType' => 2,
            'token' => '6A19E53231ACA232330DDF9B6101192C2BD0DE632DFC560A2AC39614D5D4A96A',
            'appCode' => $this->momo_data_config("appCode"),
            'appVer' => $this->momo_data_config("appVer"),
            'serviceCode' => 'adapter_kyb',
            'reference1' => '',
            'reference2' => '',
            'user' => '0334506791',
            'agent' => '0334506791',
            'lang' => 'vi',
            'deviceOS' => 'ANDROID',
            'channel' => 'APP',
            'buildNumber' => 491,
            'appId' => 'vn.momo.dynamicform',
        );
        return $this->CURL("https://owa.momo.vn/proxy/adapter_kyb",$header,$this->Encrypt_data($Data,$requestkeyRaw));

    }

    public function CheckHis($hours = 5)
    {
        $result = $this->QUERY_TRAN_HIS_MSG($hours);
        if(empty($result)){
            return array(
                "status" => "error",
                "code"   => -5,
                "message"=> 'Hết thời gian đăng nhập vui lòng đăng nhập lại'
            );
        }
        if(!empty($result["errorCode"])){
            return array(
                "status" => "error",
                "code"   => $result["errorCode"],
                "message"=> $result["errorDesc"]
            );
        }
        $List = $result["momoMsg"]["tranList"];
        $tranList = array();
        foreach ($List as $values){
            if(empty($values['partnerId']) or empty($values['io'])) continue;
            $tranList[] = array(
                'ID'    => $values['ID'],
                "tranId"=> $values["tranId"],
                "io"    => $values["io"],
                "patnerID" => $values["partnerId"],
                "status"=> $values["status"],
                "partnerName" => empty($values["partnerName"]) ? "" : $values['partnerName'] ,
                "amount" => empty($values["amount"]) ? 0 : $values["amount"],
                "comment" => (!empty($values["comment"])) ? $values["comment"] : "",
                "desc"  => empty($values["desc"]) ? "" : $values["desc"],
                "millisecond" => empty($values["finishTime"]) ? 0 : $values['finishTime'] 
            );
        }
        return array(
            "status"  => "success",
            "message" => "Thành công",
            "TranList"=> $tranList
        );
    }

    public function CheckHisNew($hours = 5)
    {
        $begin =  (time() - (3600 * $hours)) * 1000;
        //$begin = strtotime("-1 minutes") * 1000;
        $header = array(
            "authorization: Bearer ".$this->config["authorization"],
            "user_phone: ".$this->config["phone"],
            "sessionkey: ".$this->config["sessionkey"],
            "agent_id: ".$this->config["agent_id"],
            'app_version: '.$this->momo_data_config["appVer"],
            'app_code: '.$this->momo_data_config["appCode"],
            "Host: m.mservice.io"
        );
        
        
        $Data = '{
            "userId": "'.$this->config['phone'].'",
            "fromTime": '.$begin.',
            "toTime": '.$this->get_microtime().',
            "limit": 5000,
            "cursor": "",
            "cusPhoneNumber": ""
        }';
        $result =  $this->CURL("QUERY_TRAN_HIS_MSG_NEW",$header,$Data);
        if(!is_array($result)){
            return array(
                "status"=>"error",
                "code"=>-5,
                "message"=>"Hết thời gian truy cập vui lòng đăng nhập lại"
            );
        }
        $tranHisMsg =  $result["message"]["data"]["notifications"];
        $return = array();
        foreach ($tranHisMsg as $value){
            //if($value["type"] != 77) continue;
            //$extra = json_decode($value["extra"],true);
            $amount = $value['caption'];
            $name = explode("từ", $amount) [1] ? : "";
            if (strpos($amount, "Nhận") !== false && $name)
            {
                preg_match('#Nhận (.+?)đ#is', $amount, $amount);
                $amount = str_replace(".", "", $amount[1]) > 0 ? str_replace(".", "", $amount[1]) : '0';
                //Cover body to comment
                $comment = $value['body'];
                $comment = ltrim($comment, '"');
                $comment = explode('"', $comment);
                $comment = $comment[0];
                if($comment == "Nhấn để xem chi tiết.") {
                    $comment = "";
                }
                $return[] = array(
                    "tranId"  => $value["tranId"],
                    "ID"  => $value["ID"],
                    "patnerID"=> $value["sender"],
                    "partnerName" => $name,
                    "comment" => $comment,
                    "amount" => (int)$amount,
                    "millisecond" => $value["time"]
                );
            }
        }
        return array(
            "status"  => "success",
            "message" => "Thành công",
            "TranList"=> $return
        );


    }
    public function SAY_THANKS($trandId , $message = 'Cám ơn')
    {
        $microtime = $this->get_microtime();
        $requestkeyRaw = $this->generateRandom(32);
        $requestkey = $this->RSA_Encrypt($this->config["RSA_PUBLIC_KEY"],$requestkeyRaw);
        $header = array(
            "agent_id: ".$this->config["agent_id"],
            "user_phone: ".$this->config["phone"],
            "sessionkey: ".$this->config["sessionkey"],
            "authorization: Bearer ".$this->config["authorization"],
            "msgtype: LOAN_UPDATE_STATUS",
            "userid: ".$this->config["phone"],
            "requestkey: ".$requestkey,
            "Host: owa.momo.vn"
            );
        $Data = array (
            'user' => $this->config['phone'],
            'msgType' => 'SAY_THANKS',
            'cmdId' => (string) $microtime. '000000',
            'lang' => 'vi',
            'time' => (int) $microtime,
            'channel' => 'APP',
            'appVer' => $this->momo_data_config["appVer"],
            'appCode' => $this->momo_data_config["appCode"],
            'deviceOS' => 'ANDROID',
            'buildNumber' => 1987,
            'appId' => 'vn.momo.transfer',
            'result' => true,
            'errorCode' => 0,
            'errorDesc' => '',
            'momoMsg' => 
            array (
              '_class' => 'mservice.backend.entity.msg.TranHisMsg',
              'partnerAction' => $message,
              'tranId' => $trandId,
              'tranType' => 2018,
            ),
            'extra' => 
            array (
              'checkSum' => $this->generateCheckSum('SAY_THANKS', $microtime),
            ),
        );
        $result = $this->CURL("SAY_THANKS",$header,$this->Encrypt_data($Data,$requestkeyRaw));
        
        if(empty($result)){
            return array(
                "status" => "error",
                "code"   => -5,
                "message"=> 'Hết thời gian đăng nhập vui lòng đăng nhập lại'
            );
        }
        if(!empty($result["errorCode"])){
            return array(
                "status" => "error",
                "code"   => $result["errorCode"],
                "message"=> $result["errorDesc"]
            );
        }
        
        return array(
            "status"  => "success",
            "message" => "Thành Công",
            "full" => $result
        );
    }

    public function BuyCard($amount = 10000, $type = "VIETTEL")
    {
        $this->send['amount'] = (int) $amount;
        $io = $amount / 1000;
        switch ($type){
            case 'MOBIPHONE': 
                $this->send['ownerName'] = 'BCMBF'.$io;
                $this->send['serviceId'] = 'EPAY_MOBIPHONE';
                break;
            case 'VINAPHONE':
                $this->send['ownerName'] = 'BCVNP'.$io;
                $this->send['serviceId'] = 'EPAY_VINAPHONE';
                break;
            case 'VIETNAMOBLIE':
                $this->send['ownerName'] = 'BCVNM'.$io;
                $this->send['serviceId'] = 'EPAY_VIETNAMOBLIE';
                break;
            default :
                $this->send['ownerName'] = 'BCVTT'.$io;
                $this->send['serviceId'] = 'EPAY_VIETTEL';
                break;
        }
        $result = $this->NEXT_PAGE_MSG();
        if(empty($result)){
            return array(
                'status' => 'error',
                'code'   => -5,
                'message'=> 'Vui lòng chờ thêm ít thời gian rồi mua lại'
            );
        }
        if(!empty($result['errorCode'])){
            return array(
                "status" => "error",
                "code"   => $result["errorCode"],
                "message"=> $result["errorDesc"]
            );
        }
        else if(empty($result['momoMsg']['ID'])){
            return array(
                'status' => 'error',
                'code'   => -5,
                'message'=> 'Đã xảy ra lỗi trong khi tạo đơn rút tiền'
            );
        }
        $tranHisMsg = $result['momoMsg']['tranHisMsg'];
        $result = $this->TRAN_HIS_CONFIRM_MSG($tranHisMsg);
        if(empty($result)){
            return array(
                'status' => 'error',
                'code'   => -5,
                'message'=> 'Vui lòng chờ thêm ít thời gian rồi rút lại 2'
            );
        }
        if(!empty($result['errorCode'])){
            return array(
                "status" => "error",
                "code"   => $result["errorCode"],
                "message"=> $result["errorDesc"]
            );
        }
        else if(empty($result['momoMsg']['ID'])){
            return array(
                'status' => 'error',
                'code'   => -5,
                'message'=> 'Đã xảy ra lỗi trong khi tạo đơn rút tiền'
            );
        }
        return $result;
    }

    public function GetallGift()
    {
        $header = array(
            "agent_id: ".$this->config["agent_id"],
            "user_phone: ".$this->config["phone"],
            "sessionkey: ".$this->config["sessionkey"],
            "authorization: Bearer ".$this->config["authorization"],
            "userid: ".$this->config["phone"],
            "Host: api.momo.vn",
            'user-agent: momotransfer/3.0.21.30211 Mozilla/5.0 (Linux; Android 6.0.1; SM-G532G Build/MMB29T; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/55.0.2883.91 Mobile Safari/537.36',
            'm-timestamp: 1630235657912',
            'm-lang: vi',
            'm-timezone: Asia/Ho_Chi_Minh',
            'm-requestid: b57c8c2b-7e77-40fb-9003-0607bcc38d88.1630235657912',
            'm-signature: hEmGoSUCTvW5UqP2W5aMtRa+v+cQwkbp4+CTYRCp+Wc=',
            'm-isencrypt: false',
            'Connection: Keep-Alive',
            'cache-control: max-age=3600'
        );
        return $this->CURL('GetallGift', $header, '');
    }

    public function kyc($path = [])
    {
        $result = $this->ekyc_init();
        if(empty($result)){
            return array(
                'status' => 'error',
                'code'   => -5,
                'message'=> 'Vui lòng chờ thêm ít thời gian rồi thử lại'
            );
        }
        if(empty($result['proxyResponse']['kycReference'])){
            return array(
                "status" => "error",
                "code"   => -10,
                "message"=> 'Tạo ID KYC Thất Bại Vui Lòng Thử Lại'
            );
        }
        $this->send['kycReference'] = $result['proxyResponse']['kycReference'];
        $this->send['image'] = base64_encode(file_get_contents($path['FRONT']));
        $this->send['imageSide']  = 'FRONT';
        $result = $this->ekyc_ocr();
        $a = array();
        $a["f"] = $result;
        $this->send['frontReference'] = $result['proxyResponse']['frontReference'];
        $this->send['idCardTypeInApp']  = $result['proxyResponse']['documentType'];
        $this->send['image'] = base64_encode(file_get_contents($path['BACK']));
        $this->send['imageSide']  = 'BACK';
        $result = $this->ekyc_ocr();
        $a["b"] = $result;
        $this->send['backReference'] = $result['proxyResponse']['backReference'];
        $result = $this->ekyc_ocr_result();
        $a["r"] = $result;
        return $a;
        return $this->ekyc_ocr_confirm($result['proxyResponse']['data']);
    }
    
    public function VeriFyMap($BankId, $BankName ,$name, $PersionalId, $Date = '')
    {
        if(empty($BankId)){
            return array(
                'status'  => 'error',
                'code'    => -5,
                'message' => 'Thất bại thiếu Bank ID'
            );
        }
        else if(empty($BankName) or empty($this->BankId[$BankName])){
            return array(
                'status'  => 'error',
                'code'    => -5,
                'message' => 'Thất bại thiếu Bank Name'
            );
        }
        else if(empty($name)){
            return array(
                'status'  => 'error',
                'code'    => -5,
                'message' => 'Thất bại thiếu họ tên trên thẻ ngân hàng'
            );
        }
        else if(empty($PersionalId)){
            return array(
                'status'  => 'error',
                'code'    => -5,
                'message' => 'Thất bại thiếu số giấy tờ tùy thân'
            );
        }   

        switch ($BankName) {
            case 'MB':
                if(!strstr($BankId, '9704')){
                    $extra = json_encode(array(
                        'cardIssueDate' => $Date,
                        'isRouteNapasScreen' => false
                    ));
                }
                else {
                    $extra = json_encode(array(
                        'isRouteNapasScreen' => false
                    ));
                }
                break;
            case 'VTB':

                break;
            case 'BIDV': 
                $extra = json_encode(array(
                    'linkType' => 'IN_APP'
                ));
                break;

        }

        $this->send = array(
            'rowCardNum' => $BankId,
            'BankName'   => $BankName,
            'customerNumber' => $name,
            'partnerId'  => $PersionalId,  
            'extras'     => $extra
        );

        $result = $this->VERIFY_MAP();
        if(empty($result)){
            return array(
                'status' => 'error',
                'code'   => -5,
                'message'=> 'Vui lòng chờ thêm ít thời gian rồi thêm lại'
            );
        }
        if(!empty($result['errorCode'])){
            return array(
                "status" => "error",
                "code"   => $result["errorCode"],
                "message"=> $result["errorDesc"]
            );
        }
        $momoMsg = base64_encode(json_encode($result['momoMsg']));
        $this->connect->query("UPDATE `cron_momo` SET `DataJson` = '".$momoMsg."' WHERE `phone` = '".$this->config['phone']."' ");
        return $result;
        return array(
            'status' => 'success',
            'message'=> $result["errorDesc"],
            'momoMsg'=> $result['momoMsg']
        );
    }

    public function ImportOTPMap($code = '')
    {
        if(empty($code)){
            return array(
                'status'  => 'error',
                'code'    => -5,
                'message' => 'Vui lòng nhập mã OTP gửi về số điện thoại ngân hàng '
            );
        }
        if(empty($this->config['DataJson'])){
            return array(
                'status'  => 'error',
                'code'    => -6,
                'message' => 'Bạn chưa thêm ngân hàng nào vào trước vui lòng chọn function VeriFyMap '
            );
        }
        $momoMsg = json_decode(base64_decode($this->config['DataJson']), true);
        $momoMsg['otpBanknet'] = trim($code);
        $result = $this->WALLET_MAPPING($momoMsg);
        if(empty($result)){
            return array(
                'status' => 'error',
                'code'   => -5,
                'message'=> 'Vui lòng chờ thêm ít thời gian rồi thực hiện lại'
            );
        }
        if(!empty($result['errorCode'])){
            return array(
                "status" => "error",
                "code"   => $result["errorCode"],
                "message"=> $result["errorDesc"]
            );
        }
        return array(
            'status'  => 'success',
            'message' => $result["errorDesc"]
        );


    }

    public function UnConfirmNapas()
    {
        $result = $this->CARD_GET();
        if(empty($result)){
            return array(
                "status" => "error",
                "code"   => -5,
                "message"=> 'Hết thời gian đăng nhập vui lòng đăng nhập lại'
            );
        }
        if(!empty($result["errorCode"])){
            return array(
                "status" => "error",
                "code"   => $result["errorCode"],
                "message"=> $result["errorDesc"]
            );
        }
        if(empty($result['momoMsg']['cards'])){
            return array(
                'status' => 'error',
                'message'=> 'Tài khoản chưa được xác minh thẻ nào'
            );
        }
        $InfoCard = $result['momoMsg']['cards']['0'];
        $result = $this->NAPAS_CASHIN_DELETE_TOKEN_MSG($InfoCard['ID']);
        if(empty($result)){
            return array(
                "status" => "error",
                "code"   => -5,
                "message"=> 'Hết thời gian đăng nhập vui lòng đăng nhập lại'
            );
        }
        if(!empty($result["errorCode"])){
            return array(
                "status" => "error",
                "code"   => $result["errorCode"],
                "message"=> $result["errorDesc"]
            );
        }
        return array(
            'status' => 'success',
            'message'=> 'Hủy liên kết thẻ thành công'
        );
    }

    public function UnVerifyMap($BankName)
    {
        $this->send['BankName'] = trim($BankName);
        if(empty($this->BankId[$BankName])){
            return array(
                'status' => 'error',
                'message'=> 'Tên ngân hàng không tồn tại trong hệ thông'
            );
        }
        return $this->UN_MAP();
    }

    public function VeriFyNapas($BankId, $name, $Date)
    {
        if(empty($BankId)){
            return array(
                'status'  => 'error',
                'code'    => -5,
                'message' => 'Thất bại thiếu Bank ID'
            );
        }
        else if(empty($name)){
            return array(
                'status'  => 'error',
                'code'    => -5,
                'message' => 'Thất bại thiếu họ tên trên thẻ ngân hàng'
            );
        }
        $this->send = array(
            'cardNumber' => $BankId,
            'cardFullName' => $name,
            'cardIssueDate'=> $Date,
            'personalId'   => rand(100000000000,1999999999999)
        );
        $result = $this->NAPAS_CASHIN_INIT_MSG();
        if(empty($result)){
            return array(
                'status' => 'error',
                'code'   => -5,
                'message'=> 'Vui lòng chờ thêm ít thời gian rồi thực hiện lại'
            );
        }
        if(!empty($result['errorCode'])){
            return array(
                "status" => "error",
                "code"   => $result["errorCode"],
                "message"=> $result["errorDesc"]
            );
        }
        $momoMsg = base64_encode(json_encode($result['momoMsg']));
        $this->connect->query("UPDATE `cron_momo` SET `DataJson` = '".$momoMsg."' WHERE `phone` = '".$this->config['phone']."' ");
        return array(
            'status' => 'success',
            'message'=> $result["errorDesc"],
            'momoMsg'=> $result['momoMsg']
        );

    }

    public function ConfirmNaPas($code = '')
    {
        if(empty($code)){
            return array(
                'status' => 'error',
                'message'=> 'Vui lòng nhập mã xác nhận OTP'
            );
        }
        $momoMsg = json_decode(base64_decode($this->config['DataJson']), true);
        $UrlConfirm  = json_decode($momoMsg['extras'], true)['form_submit_url'];
        header('Content-Type: text/html; charset=UTF-8');
        $header = array(
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'Accept-Language: vi-VN,vi;q=0.9,fr-FR;q=0.8,fr;q=0.7,en-US;q=0.6,en;q=0.5',
            'Cache-Control: max-age=0',
            'Connection: keep-alive',
            'DNT: 1',
            'Host: payment.momo.vn',
            'pragma: akamai-x-cache-on, akamai-x-cache-remote-on, akamai-x-check-cacheable, akamai-x-get-cache-key, akamai-x-get-extracted-values, akamai-x-get-ssl-client-session-id, akamai-x-get-true-cache-key, akamai-x-serial-no, akamai-x-get-request-id,akamai-x-get-nonces,akamai-x-get-client-ip,akamai-x-feo-trace',
            'sec-ch-ua: "Google Chrome";v="93", " Not;A Brand";v="99", "Chromium";v="93"',
            'sec-ch-ua-mobile: ?1',
            'sec-ch-ua-platform: "Android"',
            'Sec-Fetch-Dest: document',
            'Sec-Fetch-Mode: navigate',
            'content-type: application/json; charset=UTF-8',
            'Sec-Fetch-Site: none',
            'Sec-Fetch-User: ?1',
            'Upgrade-Insecure-Requests: 1',
            'User-Agent: '.$_SERVER['HTTP_USER_AGENT']
        );

        $result = $this->CURL_NAPAS($UrlConfirm, $header);
        $script = "<script>
        var OTP = '';
        setTimeout(CheckTag, 2000);
      
        function CheckTag(){
            var Button = document.getElementById('napasProcessBtn1');
            var InputOTP = document.getElementById('napasOtpCode');
      
            if(Button == null || InputOTP == null){
                setTimeout(CheckTag, 2000);
                return false;
            }
            InputOTP.value = '".$code."';
            setTimeout(Button.click(), 2000);
        }
      </script>".'</body>';
        $result = str_replace('</body>', $script, $result);
        return $result;
    }

    private function SendDataNapas()
    {
        $header = array(
            'accept: */*',
            'accept-language: vi-VN,vi;q=0.9,fr-FR;q=0.8,fr;q=0.7,en-US;q=0.6,en;q=0.5',
            'content-type: application/json; charset=UTF-8',
            'dnt: 1',
            'origin: https://payment.momo.vn',
            'referer: https://payment.momo.vn/',
            'sec-ch-ua: "Google Chrome";v="93", " Not;A Brand";v="99", "Chromium";v="93"',
            'sec-ch-ua-mobile: ?1',
            'sec-ch-ua-platform: "Android"',
            'sec-fetch-dest: empty',
            'sec-fetch-mode: cors',
            'sec-fetch-site: cross-site',
            'User-Agent: '.$_SERVER['HTTP_USER_AGENT']
        );

        $Data = array (
            'apiOperation' => $this->send['apiOperation'],
            'inputParameters' => array (
              'clientIP' => $this->send['clientIP'],
              'deviceId' => $this->send['deviceId'],
              'environment' => $this->send['environment'],
              'cardScheme' => $this->send['cardScheme'],
              'enable3DSecure' => $this->send['enable3DSecure'],
            ),
            'order' =>  array (
              'amount' => $this->send['orderAmount'],
              'currency' => $this->send['orderCurrency'],
              'reference' => $this->send['orderReference'],
            ),
            'sourceOfFunds' => array (
              'type' => $this->send['sourceOfFundsType'],
            ),
            'transaction' => array (
              'otp' => $this->send['otp'],
              'pin' => '',
            ),
            'channel' => $this->send['channel'],
            'orderId' => $this->send['orderId'],
            'transactionId' => 'HFPURCHASEOTP_'.rand(100000,999999),
            'callApi' => $this->send['apiOperation'],
            'dataKey' => $this->send['dataKeyV2'],
            'submerchant' => 
            array (
              'code' => '',
            ),
        );
        $DataPost = base64_encode(json_encode($Data,JSON_UNESCAPED_SLASHES));
        return $this->CURL_NAPAS('https://dps.napas.com.vn/api/restjs/version/1/merchant/MOMOCE/token', $header, $DataPost);
    }

    private function GetDataPostNapas($url)
    {
        // echo $url;die;
        header('Content-Type: text/html; charset=UTF-8');
        $header = array(
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'Accept-Language: vi-VN,vi;q=0.9,fr-FR;q=0.8,fr;q=0.7,en-US;q=0.6,en;q=0.5',
            'Cache-Control: max-age=0',
            'Connection: keep-alive',
            'DNT: 1',
            'Host: payment.momo.vn',
            'pragma: akamai-x-cache-on, akamai-x-cache-remote-on, akamai-x-check-cacheable, akamai-x-get-cache-key, akamai-x-get-extracted-values, akamai-x-get-ssl-client-session-id, akamai-x-get-true-cache-key, akamai-x-serial-no, akamai-x-get-request-id,akamai-x-get-nonces,akamai-x-get-client-ip,akamai-x-feo-trace',
            'sec-ch-ua: "Google Chrome";v="93", " Not;A Brand";v="99", "Chromium";v="93"',
            'sec-ch-ua-mobile: ?1',
            'sec-ch-ua-platform: "Android"',
            'Sec-Fetch-Dest: document',
            'Sec-Fetch-Mode: navigate',
            'content-type: application/json; charset=UTF-8',
            'Sec-Fetch-Site: none',
            'Sec-Fetch-User: ?1',
            'Upgrade-Insecure-Requests: 1',
            'User-Agent: '.$_SERVER['HTTP_USER_AGENT']
        );

        $result = $this->CURL_NAPAS($url, $header);
        $script = "<script>
        var OTP = '';
        setTimeout(CheckTag, 2000);
      
        function CheckTag(){
            var Button = document.getElementById('napasProcessBtn1');
            var InputOTP = document.getElementById('napasOtpCode');
      
            if(Button == null || InputOTP == null){
                setTimeout(CheckTag, 2000);
                return false;
            }
            InputOTP.value = '".$this->send['otp']."';
            setTimeout(Button.click(), 2000);
        }
      </script>".'</body>';
        $result = str_replace('</body>', $script, $result);
        echo $result;die;
        $Data = array();
        if(preg_match_all('/([a-zA-Z0-9]*)="(.*)"/', $result, $match)){
            $keysvalue = $match['1'];
            $values    = $match['2'];
            if(count($keysvalue) == count($values)){
                for($i = 0; $i < count($values); $i++ ){
                    $Data[$keysvalue[$i]] = $values[$i];
                }
            }
        }
        return $Data;

    }

    private function HotedForm()
    {
        $Data = array (
            'apiOperation' => 'HOSTED_FORM_PURSAVE',
            'inputParameters' => array (
              'clientIP' => $this->send['clientIP'],
              'deviceId' => $this->send['deviceId'],
              'environment' => $this->send['environment'],
              'cardScheme' => $this->send['cardScheme'],
              'enable3DSecure' => $this->send['enable3DSecure'],
            ),
            'orderId' => $this->send['orderId'],
            'formAction' => $this->send['action'],
            'napasLang' => 'vi',
            'dataKey' => $this->send['dataKey'],
            'order' =>  array (
              'amount' => $this->send['orderAmount'],
              'currency' => $this->send['orderCurrency'],
              'reference' => $this->send['orderReference'],
            ),
            'sourceOfFunds' =>  array (
              'type' => $this->send['sourceOfFundsType'],
            ),
            'callApi' => 'PURCHASE_WITH_RETURNED_TOKEN',
            'channel' => $this->send['channel'],
            'submerchant' =>  array (
              'code' => '',
            ),
          );
          
          $DataPost = base64_encode(json_encode($Data,JSON_UNESCAPED_SLASHES));
          $header = array(
            'Host: dps.napas.com.vn',
            'Connection: keep-alive',
            // 'Content-Length: '.strlen($DataPost),
            'sec-ch-ua: "Google Chrome";v="93", " Not;A Brand";v="99", "Chromium";v="93"',
            'Accept: */*',
            'DNT: 1',
            'Content-Type: application/json; charset=UTF-8',
            'sec-ch-ua-mobile: ?0',
            'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/93.0.4577.63 Safari/537.36',
            'sec-ch-ua-platform: "Windows"',
            'Origin: https://payment.momo.vn',
            'Sec-Fetch-Site: cross-site',
            'Sec-Fetch-Mode: cors',
            'Sec-Fetch-Dest: empty',
            'Referer: https://payment.momo.vn/',
            'Accept-Encoding: gzip, deflate, br',
            'Accept-Language: vi-VN,vi;q=0.9,fr-FR;q=0.8,fr;q=0.7,en-US;q=0.6,en;q=0.5'  
          );
          return $this->CURL_NAPAS('https://dps.napas.com.vn/api/restjs/version/1/merchant/MOMOCE/hostedform', $header, $DataPost);
    }

    private function CURL_NAPAS($Action, $header , $Data = '')
    {
        $curl = curl_init();
        curl_setopt_array($curl,array(
            CURLOPT_URL => $Action,
            CURLOPT_HTTPHEADER => $header,
            CURLOPT_POST       => (empty($Data)) ? FALSE : TRUE,
            CURLOPT_CUSTOMREQUEST => (empty($Data)) ? 'GET' : 'POST',
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_FOLLOWLOCATION => TRUE,
            CURLOPT_ENCODING => "",
            CURLOPT_SSLVERSION => 6
        ));

        return curl_exec($curl);
    }

    public function UN_MAP()
    {
        $microtime = $this->get_microtime();
        $requestkeyRaw = $this->generateRandom(32);
        $requestkey = $this->RSA_Encrypt($this->config["RSA_PUBLIC_KEY"],$requestkeyRaw);
        $header = array(
            "agent_id: ".$this->config["agent_id"],
            "user_phone: ".$this->config["phone"],
            "sessionkey: ".$this->config["sessionkey"],
            "authorization: Bearer ".$this->config["authorization"],
            "msgtype: UN_MAP",
            "userid: ".$this->config["phone"],
            "requestkey: ".$requestkey,
            "Host: owa.momo.vn"
            );
        $Data = array(
            'user' => $this->config['phone'],
            'msgType' => 'UN_MAP',
            'cmdId' => (string) $microtime. '000000',
            'lang' => 'vi',
            'time' => (int) $microtime,
            'channel' => 'APP',
            'appVer' => $this->momo_data_config["appVer"],
            'appCode' => $this->momo_data_config["appCode"],
            'deviceOS' => 'ANDROID',
            'buildNumber' => 0,
            'appId' => 'vn.momo.platform',
            'result' => true,
            'errorCode' => 0,
            'errorDesc' => '',
            'momoMsg'   => array(
                'partnerCode' => $this->BankId[$this->send['BankName']]['partnerCode'],
                '_class'      => 'mservice.backend.entity.msg.TranHisMsg'
            ),
            'extra' => array (
              'checkSum' => $this->generateCheckSum('UN_MAP',$microtime),
            )
        );
        return $this->CURL("UN_MAP",$header,$this->Encrypt_data($Data,$requestkeyRaw));

    }

    private function NAPAS_CASHIN_INIT_MSG()
    {
        $microtime = $this->get_microtime();
        $requestkeyRaw = $this->generateRandom(32);
        $requestkey = $this->RSA_Encrypt($this->config["RSA_PUBLIC_KEY"],$requestkeyRaw);
        $header = array(
            "agent_id: ".$this->config["agent_id"],
            "user_phone: ".$this->config["phone"],
            "sessionkey: ".$this->config["sessionkey"],
            "authorization: Bearer ".$this->config["authorization"],
            "msgtype: NAPAS_CASHIN_INIT_MSG",
            "userid: ".$this->config["phone"],
            "requestkey: ".$requestkey,
            "Host: owa.momo.vn"
            );
        $Data = array (
            'user' => $this->config['phone'],
            'msgType' => 'NAPAS_CASHIN_INIT_MSG',
            'cmdId' =>  (string) $microtime. '000000',
            'lang' => 'vi',
            'time' => $microtime,
            'channel' => 'APP',
            'appVer' => $this->momo_data_config["appVer"],
            'appCode' => $this->momo_data_config["appCode"],
            'deviceOS' => 'ANDROID',
            'buildNumber' => 0,
            'appId' => 'vn.momo.platform',
            'result' => true,
            'errorCode' => 0,
            'errorDesc' => '',
            'momoMsg' => 
            array (
              'amount' => 0,
              'cardNumber' => $this->send['cardNumber'],
              'cardFullName' => $this->send['cardFullName'],
              'cardIssueDate' => $this->send['cardIssueDate'],
              'partnerCode' => 'napas_cashin_tokenization',
              'personalId' => $this->send['personalId'],
              'isSaveToken' => true,
              'isLinkCard' => true,
              'clientIP' => '171.238.63.60',//$_SERVER['REMOTE_ADDR'],
              'tranType' => 1,
              'moneySource' => 5,
              '_class' => 'mservice.backend.entity.msg.NapasCashInInitMsg',
              'extras' => '{"paymentTranType":"","ID":""}',
              'serviceId' => '',
            ),
            'extra' => 
            array (
              'checkSum' => $this->generateCheckSum('NAPAS_CASHIN_INIT_MSG', $microtime),
            ),
        );
        return $this->CURL("NAPAS_CASHIN_INIT_MSG",$header,$this->Encrypt_data($Data,$requestkeyRaw));

    }

    public function WALLET_MAPPING($momoMsg)
    {
        $microtime = $this->get_microtime();
        $requestkeyRaw = $this->generateRandom(32);
        $requestkey = $this->RSA_Encrypt($this->config["RSA_PUBLIC_KEY"],$requestkeyRaw);
        $header = array(
            "agent_id: ".$this->config["agent_id"],
            "user_phone: ".$this->config["phone"],
            "sessionkey: ".$this->config["sessionkey"],
            "authorization: Bearer ".$this->config["authorization"],
            "msgtype: WALLET_MAPPING",
            "userid: ".$this->config["phone"],
            "requestkey: ".$requestkey,
            "Host: owa.momo.vn"
            );
        $Data = array(
            'user' => $this->config['phone'],
            'msgType' => 'WALLET_MAPPING',
            'cmdId' => (string) $microtime. '000000',
            'lang' => 'vi',
            'time' => (int) $microtime,
            'channel' => 'APP',
            'appVer' => $this->momo_data_config["appVer"],
            'appCode' => $this->momo_data_config["appCode"],
            'deviceOS' => 'ANDROID',
            'buildNumber' => 0,
            'appId' => 'vn.momo.platform',
            'result' => true,
            'errorCode' => 0,
            'errorDesc' => '',
            'momoMsg'   => $momoMsg,
            'extra' => array (
              'checkSum' => $this->generateCheckSum('WALLET_MAPPING',$microtime),
            )
        );
        return $this->CURL("WALLET_MAPPING",$header,$this->Encrypt_data($Data,$requestkeyRaw));
    }

    public function GetDataStoreMsg()
    {
        $microtime = $this->get_microtime();
        $requestkeyRaw = $this->generateRandom(32);
        $requestkey = $this->RSA_Encrypt($this->config["RSA_PUBLIC_KEY"],$requestkeyRaw);
        $header = array(
            "agent_id: ".$this->config["agent_id"],
            "user_phone: ".$this->config["phone"],
            "sessionkey: ".$this->config["sessionkey"],
            "authorization: Bearer ".$this->config["authorization"],
            "msgtype: GetDataStoreMsg",
            "userid: ".$this->config["phone"],
            "requestkey: ".$requestkey,
            "Host: owa.momo.vn"
            );
        $Data = array (
            'user' => $this->config['phone'],
            'msgType' => 'GetDataStoreMsg',
            'cmdId' => (string) $microtime. '000000',
            'lang' => 'vi',
            'time' => (int) $microtime,
            'channel' => 'APP',
            'appVer' => $this->momo_data_config["appVer"],
            'appCode' => $this->momo_data_config["appCode"],
            'deviceOS' => 'ANDROID',
            'buildNumber' => 1987,
            'appId' => 'vn.momo.transfer',
            'result' => true,
            'errorCode' => 0,
            'errorDesc' => '',
            'momoMsg' => 
            array (
              '_class' => 'mservice.backend.entity.msg.ForwardMsg',
              'key' => 'TransferRecentHistory',
            ),
            'extra' => 
            array (
              'checkSum' =>  $this->generateCheckSum('GetDataStoreMsg', $microtime),
            ),
        );
        return $this->CURL("GetDataStoreMsg",$header,$this->Encrypt_data($Data,$requestkeyRaw));
    }

    public function AGENT_MODIFY()
    {
        $microtime = $this->get_microtime();
        $requestkeyRaw = $this->generateRandom(32);
        $requestkey = $this->RSA_Encrypt($this->config["RSA_PUBLIC_KEY"],$requestkeyRaw);
        $header = array(
            "agent_id: ".$this->config["agent_id"],
            "user_phone: ".$this->config["phone"],
            "sessionkey: ".$this->config["sessionkey"],
            "authorization: Bearer ".$this->config["authorization"],
            "msgtype: AGENT_MODIFY",
            "userid: ".$this->config["phone"],
            "requestkey: ".$requestkey,
            "Host: api.momo.vn"
        );
        $Data = array (
            'user' => $this->config['phone'],
            'msgType' => 'AGENT_MODIFY',
            'cmdId' => (string) $microtime. '000000',
            'lang' => 'vi',
            'time' => (int) $microtime,
            'channel' => 'APP',
            'appVer' => $this->momo_data_config["appVer"],
            'appCode' => $this->momo_data_config["appCode"],
            'deviceOS' => 'ANDROID',
            'buildNumber' => 1987,
            'appId' => 'vn.momo.platform',
            'result' => true,
            'errorCode' => 0,
            'errorDesc' => '',
            'momoMsg' => 
            array (
              'name'  => $this->config['Name'],
              'email' => $this->config['email'],
              'gender'=> 1,
              'sex'   => 1,
              'nationality' => 'VN',
              'identify' => 'CONFIRM',
              '_class' => 'mservice.backend.entity.msg.MomoUserDataMsg',
            ),
            'extra' => 
            array (
              'checkSum' => $this->generateCheckSum('AGENT_MODIFY',$microtime),
            ),
        );
        return $this->CURL("AGENT_MODIFY",$header,$this->Encrypt_data($Data,$requestkeyRaw));
    }

    public function END_USER_QUICK_REGISTER()
    {
        $microtime = $this->get_microtime();
        $header = array(
            "user_phone: ".$this->config["phone"],
            "authorization: Bearer ".$this->config["authorization"],
            "msgtype: END_USER_QUICK_REGISTER",
            "userid: ".$this->config["phone"],
            "Host: api.momo.vn"
            );
        $Data = array (
            'user' => $this->config['phone'],
            'msgType' => 'END_USER_QUICK_REGISTER',
            'pass' => $this->config['password'],
            'cmdId' => (string) $microtime. '000000',
            'lang' => 'vi',
            'time' => $microtime,
            'channel' => 'APP',
            'appVer' => $this->momo_data_config["appVer"],
            'appCode' => $this->momo_data_config["appCode"],
            'deviceOS' => 'ANDROID',
            'buildNumber' => 0,
            'appId' => 'vn.momo.platform',
            'result' => true,
            'errorCode' => 0,
            'errorDesc' => '',
            'momoMsg' => 
            array (
              '_class' => 'mservice.backend.entity.msg.MomoUserDataMsg',
              'isSetup' => true,
            ),
            'extra' => 
            array (
              'pHash' => $this->get_pHash(),
              'AAID' => $this->config['AAID'],
              'IDFA' => '',
              'TOKEN' => $this->config['TOKEN'],
              'SIMULATOR' => 'false',
              'SECUREID' => $this->config['SECUREID'],
              'checkSum' => $this->generateCheckSum('END_USER_QUICK_REGISTER', $microtime),
            ),
        );
        return $this->CURL("END_USER_QUICK_REGISTER",$header,$Data);
    }

    private function TRAN_HIS_INIT_MSG_2()
    {}

    public function service()
    {
        $microtime = $this->get_microtime();
        $requestkeyRaw = $this->generateRandom(32);
        $requestkey = $this->RSA_Encrypt($this->config["RSA_PUBLIC_KEY"],$requestkeyRaw);
        $header = array(
            "agent_id: ".$this->config["agent_id"],
            "user_phone: ".$this->config["phone"],
            "sessionkey: ".$this->config["sessionkey"],
            "authorization: Bearer ".$this->config["authorization"],
            "userid: ".$this->config["phone"],
            "requestkey: ".$requestkey,
            'Connection: Keep-Alive',
            "Host: owa.momo.vn"
            );
        $Data = array(
			"requestId" => (string) $this->config['phone']. $microtime,
			"agent" => $this->config['phone'],
			"msgType" => "NapasBankCodeRequestMsg",
			"serviceId" => "2001",
			"source" => 2
        );
        return $this->CURL("service",$header,$this->Encrypt_data($Data,$requestkeyRaw));
    }

    public function CreatImage()
    {
        $this->number = rand(100000000000,999999999999);
        $this->string = $this->generateRandomString(16);
        $header = array(
            'X-Firebase-Storage-Version: Android/21.30.16 (040304-391784508)',
            'X-Goog-Upload-Offset: 0',
            'X-Goog-Upload-Protocol: resumable',
            'x-firebase-gmpid: 1:'.$this->number.':android:'.$this->string,
            'X-Goog-Upload-Command: start',
            'Content-Type: application/x-www-form-urlencoded',
            'User-Agent: Dalvik/2.1.0 (Linux; U; Android 10; PIXEL Build/MMB29T)',
            'Host: firebasestorage.googleapis.com',
            'Connection: Keep-Alive',
            'Accept-Encoding: gzip'
        );

        $Action = 'https://firebasestorage.googleapis.com/v0/b/momovn-mini-app/o?name=chat_images%2F'.$this->generateRandom(20).'.png&uploadType=resumable';
        return $this->CURL_V1($Action,$header , '');
    }

    public function GetGroupId()
    {

          $header = array(
                'Host: m.mservice.io',
                'accept: application/json',
                'app_version: '.$this->momo_data_config["appVer"],
                'app_code: '.$this->momo_data_config["appCode"],
                'device_os: ANDROID',
                'agent_id: '.$this->config['agent_id'],
                'sessionkey: '.$this->config['sessionkey'],
                'user_phone: '.$this->config['phone'],
                'lang: vi',
                'authorization: Bearer '.$this->config['authorization'],
                'content-type: application/json',
                'accept-encoding: gzip',
                'user-agent: okhttp/3.14.7'
          );
          $Data = array (
                'userId' => $this->config['phone'],
                'name' => '',
                'addUserIds' => 
                array (
                  0 => $this->send['phone'],
                ),
                'customData' => 
                array (
                  'users' => 
                  array (
                    0 => 
                    array (
                      'phone' => $this->config['phone'],
                      'name' => $this->config['Name'],
                      'avatar' => 'https://s3-ap-southeast-1.amazonaws.com/avatars.mservice.io/'.$this->config['phone'].'.png',
                    ),
                    1 => 
                    array (
                      'phone' => $this->send['phone'],
                      'name' => $this->send['name'],
                      'avatar' => 'https://s3-ap-southeast-1.amazonaws.com/avatars.mservice.io/'.$this->send['phone'].'.png',
                      'isStranger' => false,
                    ),
                  ),
                ),
          );

          return $this->CURL_V2('https://m.mservice.io/helios/chat-api/v1/room/', $header, json_encode($Data));
    }

    public function UpLoadImage()
    {
          
        $result = $this->CreatImage();
        if(empty($result)){
            return array(
                'status' => 'error',
                'message'=> 'Tạo đường dẫn ảnh thất bại vui lòng thử lại'
            );
        }
        $header = array(
            'X-Firebase-Storage-Version: Android/21.30.16 (040304-391784508)',
            'X-Goog-Upload-Protocol: resumable',
            'X-Goog-Upload-Offset: 0',
            'x-firebase-gmpid: 1:'.$this->number.':android:'.$this->string,
            'X-Goog-Upload-Command: upload, finalize',
            'Content-Length: '.strlen($this->image),
            'Content-Type: application/x-www-form-urlencoded',
            'User-Agent: Dalvik/2.1.0 (Linux; U; Android 10; PIXEL Build/MMB29T)',
            'Host: firebasestorage.googleapis.com',
            'Connection: Keep-Alive',
            'Accept-Encoding: gzip'
        );
        if(preg_match('/X-Goog-Upload-URL: (.*)\b/', $result, $match)){
                $Action =  trim($match['1']);
                $result = $this->CURL_V1($Action, $header, $this->image, FALSE);
                return json_decode($result, true);
        }
        else if(preg_match('/x-goog-upload-url: (.*)\b/', $result,$match)){
                $Action =  trim($match['1']);
                $result = $this->CURL_V1($Action, $header, $this->image, FALSE);
                return json_decode($result, true);
        }
        else
        {
            return array(
                'status' => 'error',
                'message'=> 'Tạo đường dẫn ảnh thất bại vui lòng thử lại'
            );
        } 
    }

    public function CreatLink()
    {
        $result = $this->UpLoadImage();
        if(isset($result['status'])){
            return array(
                'status' => 'error',
                'message'=> 'Tạo đường dẫn ảnh thất bại vui lòng thử lại'
            );
        }
        return array(
            'status' => 'success',
            'image'  => 'https://firebasestorage.googleapis.com/v0/b/momovn-mini-app/o/'.urlencode($result['name']).'?alt=media&token='.$result['downloadTokens']
        );
        
    }

    public function AddFriend($phone = '')
    {
          if(empty($phone)){
                return array(
                      'status' => 'error',
                      'message'=> 'Vui lòng không để trỗng số điện thoại'
                );
          }

          $header = array(
                'Host: m.mservice.io',
                'accept: application/json',
                'app_version: $this->momo_data_config["appVer"]',
                'app_code: 3.0.23',
                'device_os: ANDROID',
                'agent_id: '.$this->config['agent_id'],
                'sessionkey: '.$this->config['sessionkey'],
                'user_phone: '.$this->config['phone'],
                'lang: vi',
                'authorization: Bearer '.$this->config['authorization'],
                'content-type: application/json',
                'accept-encoding: gzip',
                'user-agent: okhttp/3.14.7'
          );
          $Data = '{"friendId":"'.$phone.'"}';

          return $this->CURL_V2('https://m.mservice.io/helios/chat-api/v1/user/send-friend-request', $header, $Data);
    }

    public function CheckMessage()
    {
          $header = array(
              'Host: m.mservice.io',
              'accept: application/json',
              'app_version: '.$this->momo_data_config["appVer"],
              'app_code: 3.0.23',
              'device_os: ANDROID',
              'agent_id: '.$this->config['agent_id'],
              'sessionkey: '.$this->config['sessionkey'],
              'user_phone: '.$this->config['phone'],
              'lang: vi',
              'authorization: Bearer '.$this->config['authorization'],
              'content-type: application/json',
              'accept-encoding: gzip',
              'user-agent: okhttp/3.14.7'
          );
          $Data = array (
              'roomId' => $this->send['roomId'],
              'beforeId' => '',
              'limit' => 10,
              'action' => 1,
          );
          return $this->CURL_V2('https://m.mservice.io/helios/chat-api/v1/room/fetch-messages',$header, json_encode($Data));
    }

    public function CheckPending()
    {
      $header = array(
          'Host: m.mservice.io',
          'accept: application/json',
          'app_version: '.$this->momo_data_config["appVer"],
          'app_code: 3.0.23',
          'device_os: ANDROID',
          'agent_id: '.$this->config['agent_id'],
          'sessionkey: '.$this->config['sessionkey'],
          'user_phone: '.$this->config['phone'],
          'lang: vi',
          'authorization: Bearer '.$this->config['authorization'],
          'content-type: application/json',
          'accept-encoding: gzip',
          'user-agent: okhttp/3.14.7'
        );

       return $this->CURL_V2('https://m.mservice.io/helios/chat-api/v1/room/load/pending?page=1', $header,'{}');
    }

    public function Mess()
    {
          $header = array(
                "Host: helios.mservice.io",
                "user-agent: xxx grpc-java-okhttp/1.32.2",
                "content-type: application/grpc",
                "te: trailers",
                "authorization: Bearer ".$this->config['authorization'],
                "grpc-accept-encoding: gzip"
            );

          if(!empty($this->send['message'])){
                $message = json_encode(array (
                      'roomId' => $this->send['roomId'],
                      'requestId' => $this->generateImei(),
                      'createAt' => $this->get_microtime(),
                      'parts' => 
                      array (
                        'partType' => 'INLINE',
                        'payload' => 
                        array (
                          'content' => $this->send['message'],
                          'customData' => 
                          array (
                            'name' => $this->send['name'],
                            'userName' => $this->send['name'],
                            '_id' => $this->config['phone'],
                            'avatar' => 'https://s3-ap-southeast-1.amazonaws.com/avatars.mservice.io/'.$this->config['phone'].'.png',
                          ),
                        ),
                      ),
                      'userId' => $this->config['phone'],
                      'userName' => '',
                ));
          }
          else {
                $message = json_encode(array (
                      'roomId' => $this->send['roomId'],
                      'parts' => 
                      array (
                        'partType' => 'ATTACHMENT',
                        'payload' => 
                        array (
                          'customData' => 
                          array (
                            'userName' => $this->config['Name'],
                          ),
                          'content' => '',
                          'type' => 'IMAGE',
                          'url' => $this->send['image'],
                        ),
                      ),
                      'requestId' => $this->generateImei(),
                      'userId' => $this->config['phone'],
                      'userName' => '',
                ));
          }
          $curl = curl_init();
          $opt = array(
                CURLOPT_URL => "https://helios.mservice.io/helioschat.ChatService/connect",
                CURLOPT_RETURNTRANSFER => false,
                CURLOPT_POST => TRUE,
                CURLOPT_POSTFIELDS => $this->HexDataMess($message),
                CURLOPT_HEADER => 1,
                CURLOPT_FOLLOWLOCATION => TRUE,
                CURLOPT_CONNECTTIMEOUT_MS => 300,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_HTTPHEADER => $header,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_2,
                CURLOPT_TIMEOUT => 1,
          );
          curl_setopt_array($curl,$opt);
          echo curl_exec($curl); 
    }

    private function HexDataMess($message)
    {
          if(is_array($message)) $message = json_encode($message);
          $hexbin = '000000';

          $tinhtong = 128 * (intdiv(strlen($message),128) - 1);

          $hexbin .= '0'.dechex(strlen($message) + 5).'080F12';

          $hexbin .= dechex(strlen($message) - $tinhtong).'0'.dechex(strlen($message) / 128);

          return hex2bin($hexbin).$message;
    }

    public function CURL_V2($Action, $header, $data)
    {
          $curl = curl_init();
          $opt = array(
              CURLOPT_URL =>$Action,
              CURLOPT_RETURNTRANSFER => TRUE,
              CURLOPT_POST => TRUE,
              CURLOPT_POSTFIELDS => $data,
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_HTTPHEADER => $header,
              CURLOPT_ENCODING => "",
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_TIMEOUT => 20,
          );
          curl_setopt_array($curl,$opt);
          $result = curl_exec($curl);
          if(is_object(json_decode($result))){
                return json_decode($result, true);
          }
          return $result;
    }

    private function CURL_V1($Action, $header, $data, $Hidden = TRUE)
    {   
        $curl = curl_init();
        $opt = array(
            CURLOPT_URL =>$Action,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_POST => TRUE,
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_HTTPHEADER => $header,
            CURLOPT_ENCODING => "",
            CURLOPT_HEADER => $Hidden,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_2,
            CURLOPT_TIMEOUT => 20,
        );
        curl_setopt_array($curl,$opt);
        return curl_exec($curl);
    }

    private function CheckBank()
    {
        $microtime = $this->get_microtime();
        $requestkeyRaw = $this->generateRandom(32);
        $requestkey = $this->RSA_Encrypt($this->config["RSA_PUBLIC_KEY"],$requestkeyRaw);
        $header = array(
            "agent_id: ".$this->config["agent_id"],
            "user_phone: ".$this->config["phone"],
            "sessionkey: ".$this->config["sessionkey"],
            "authorization: Bearer ".$this->config["authorization"],
            "userid: ".$this->config["phone"],
            "requestkey: ".$requestkey,
            'Connection: Keep-Alive',
            "Host: owa.momo.vn"
            );
        $Data = array (
            'requestId' => $microtime,
            'agent' => $this->config['phone'],
            'channel' => 'APP',
            'coreBankCode' => '2001',
            'serviceId' => '2001',
            'benfAccount' => 
            array (
              'accId' => $this->send['accId'],
              'napasBank' => 
              array (
                'bankCode' => $this->send['bankCode'],
                'bankName' => $this->send['bankName'],
              ),
            ),
            'msgType' => 'CheckAccountRequestMsg',
            'appCode' => $this->momo_data_config["appCode"],
            'appVer' => $this->momo_data_config["appVer"],
            'lang' => 'vi',
            'deviceOS' => 'ANDROID',
            'buildNumber' => 1987,
            'appId' => 'vn.momo.transfer',
        );
        return $this->CURL("service",$header,$this->Encrypt_data($Data,$requestkeyRaw));

    }

    public function link_service_tokenization()
    {
        $microtime = $this->get_microtime();
        $requestkeyRaw = $this->generateRandom(32);
        $requestkey = $this->RSA_Encrypt($this->config["RSA_PUBLIC_KEY"],$requestkeyRaw);
        $header = array(
            "agent_id: ".$this->config["agent_id"],
            "user_phone: ".$this->config["phone"],
            "sessionkey: ".$this->config["sessionkey"],
            "authorization: Bearer ".$this->config["authorization"],
            "userid: ".$this->config["phone"],
            "requestkey: ".$requestkey,
            "Host: owa.momo.vn"
            );
        $Data = array (
            'requestId' => (string) $microtime,
            'type' => 'getList',
            'walletId' => $this->config['phone'],
            'sessionKey' => $this->config['sessionkey'],
            'appCode' => $this->momo_data_config["appCode"],
            'appVer' => 30323,
            'lang' => 'vi',
            'deviceOS' => 'ANDROID',
            'channel' => 'APP',
            'buildNumber' => 74,
            'appId' => 'vn.momo.authenlinkingservices',
        );
        return $this->CURL_proxy("https://owa.momo.vn/proxy/link_service_tokenization",$header,$this->Encrypt_data($Data,$requestkeyRaw));
    }

    public function dev_backend_gift_recommend()
    {
        $microtime = $this->get_microtime();
        $requestkeyRaw = $this->generateRandom(32);
        $requestkey = $this->RSA_Encrypt($this->config["RSA_PUBLIC_KEY"],$requestkeyRaw);
        $header = array(
            "agent_id: ".$this->config["agent_id"],
            "user_phone: ".$this->config["phone"],
            "sessionkey: ".$this->config["sessionkey"],
            "authorization: Bearer ".$this->config["authorization"],
            "userid: ".$this->config["phone"],
            "requestkey: ".$requestkey,
            'Connection: Keep-Alive',
            "Host: owa.momo.vn"
            );
        $Data = array (
            "agent" => $this->config['phone'],
            'serviceId' => 'transfer_p2p',
            'momoMsg' => 
            array (
              'serviceId' => 'transfer_p2p',
              'originalAmount' => '100000',
              'page' => 1,
              'limit' => 20,
              '_class' => 'mservice.backend.entity.msg.GiftRecommendMsg',
              'user' => (string) $this->config['phone'],
            ),
            'requestId' => (string) $this->config['phone']. $microtime,
            'msgType' => 'GIFT_RECOMMEND',
            'user' => (string) $this->config['phone'],
            'appCode' => $this->momo_data_config["appCode"],
            'appVer' => 30323,
            'lang' => 'vi',
            'deviceOS' => 'ANDROID',
            'channel' => 'APP',
            'buildNumber' => 0,
            'appId' => 'vn.momo.platform',
        );
        return $this->CURL_proxy("https://owa.momo.vn/proxy/dev_backend_gift_recommend",$header,$this->Encrypt_data($Data,$requestkeyRaw));
    }

    public function ekyc_ocr()
    {
        $microtime = $this->get_microtime();
        $requestkeyRaw = $this->generateRandom(32);
        $requestkey = $this->RSA_Encrypt($this->config["RSA_PUBLIC_KEY"],$requestkeyRaw);
        $header = array(
            "agent_id: ".$this->config["agent_id"],
            "user_phone: ".$this->config["phone"],
            "sessionkey: ".$this->config["sessionkey"],
            "authorization: Bearer ".$this->config["authorization"],
            "userid: ".$this->config["phone"],
            "requestkey: ".$requestkey,
            "Host: owa.momo.vn"
            );
        $Data = array (
            'kycReference' => $this->send['kycReference'],
            'agent' => $this->config['phone'],
            'imageSide' => $this->send['imageSide'],
            'image' => $this->send['image'], //base64
            'action' => 'OCR',
            'serviceId' => 'ekyc_service',
            'appCode' => $this->momo_data_config["appCode"],
            'appVer' => $this->momo_data_config["appVer"],
            'lang' => 'vi',
            'deviceOS' => 'ANDROID',
            'channel' => 'APP',
            'buildNumber' => 1062,
            'appId' => 'vn.momo.bank',
        );
        return $this->CURL("ekyc_ocr",$header,$this->Encrypt_data($Data,$requestkeyRaw));
    }

    public function NAPAS_CASHIN_DELETE_TOKEN_MSG($requestTokenHash)
    {
        $microtime = $this->get_microtime();
        $requestkeyRaw = $this->generateRandom(32);
        $requestkey = $this->RSA_Encrypt($this->config["RSA_PUBLIC_KEY"],$requestkeyRaw);
        $header = array(
            "agent_id: ".$this->config["agent_id"],
            "user_phone: ".$this->config["phone"],
            "sessionkey: ".$this->config["sessionkey"],
            "authorization: Bearer ".$this->config["authorization"],
            "msgtype: NAPAS_CASHIN_DELETE_TOKEN_MSG",
            "userid: ".$this->config["phone"],
            "requestkey: ".$requestkey,
            "Host: owa.momo.vn"
            );
        $Data = array (
            'user' => $this->config['phone'],
            'msgType' => 'NAPAS_CASHIN_DELETE_TOKEN_MSG',
            'cmdId' => (string) $microtime.'000000',
            'lang' => 'vi',
            'time' => (int) $microtime,
            'channel' => 'APP',
            'appVer' => $this->momo_data_config["appVer"],
            'appCode' => $this->momo_data_config["appCode"],
            'deviceOS' => 'ANDROID',
            'buildNumber' => 0,
            'appId' => 'vn.momo.platform',
            'result' => true,
            'errorCode' => 0,
            'errorDesc' => '',
            'momoMsg' => 
            array (
              'partnerCode' => 'napas_cashin_tokenization',
              'requestTokenHash' => $requestTokenHash,
              'moneySource' => 5,
              '_class' => 'mservice.backend.entity.msg.NapasCashinDeleteTokenMsg',
            ),
            'extra' => 
            array (
              'checkSum' => $this->generateCheckSum('NAPAS_CASHIN_DELETE_TOKEN_MSG', $microtime),
            ),
        );
        return $this->CURL('NAPAS_CASHIN_DELETE_TOKEN_MSG', $header, $this->Encrypt_data($Data, $requestkeyRaw));
    }

    public function ekyc_init()
    {
        $microtime = $this->get_microtime();
        $requestkeyRaw = $this->generateRandom(32);
        $requestkey = $this->RSA_Encrypt($this->config["RSA_PUBLIC_KEY"],$requestkeyRaw);
        $header = array(
            "agent_id: ".$this->config["agent_id"],
            "user_phone: ".$this->config["phone"],
            "sessionkey: ".$this->config["sessionkey"],
            "authorization: Bearer ".$this->config["authorization"],
            "userid: ".$this->config["phone"],
            "requestkey: ".$requestkey,
            "Host: owa.momo.vn"
            );
        $Data = array (
            'agent' => $this->config['phone'],
            'purpose' => 'EKYC_IDENTIFY',
            'action' => 'OCR_INIT',
            'serviceId' => 'ekyc_service',
            'appCode' => $this->momo_data_config["appCode"],
            'appVer' => $this->momo_data_config["appVer"],
            'lang' => 'vi',
            'deviceOS' => 'ANDROID',
            'channel' => 'APP',
            'buildNumber' => 1062,
            'appId' => 'vn.momo.bank',
        );
        return $this->CURL("ekyc_init",$header,$this->Encrypt_data($Data,$requestkeyRaw));
    }

    public function ekyc_ocr_result()
    {
        $requestkeyRaw = $this->generateRandom(32);
        $requestkey = $this->RSA_Encrypt($this->config["RSA_PUBLIC_KEY"],$requestkeyRaw);
        $header = array(
            "agent_id: ".$this->config["agent_id"],
            "user_phone: ".$this->config["phone"],
            "sessionkey: ".$this->config["sessionkey"],
            "authorization: Bearer ".$this->config["authorization"],
            "userid: ".$this->config["phone"],
            "requestkey: ".$requestkey,
            "Host: owa.momo.vn"
            );
        $Data =  array(
            'kycReference' => $this->send['kycReference'], 
            'frontReference' => $this->send['frontReference'],
            'idCardTypeInApp' => $this->send['idCardTypeInApp'],
            'backReference' => $this->send['backReference'],
            'agent' => $this->config['phone'],
            'action' => 'GET_OCR_RESULT',
            'serviceId' => 'ekyc_service',
            'appCode' => $this->momo_data_config["appCode"],
            'appVer' => $this->momo_data_config["appVer"],
            'lang' => 'vi',
            'deviceOS' => 'ANDROID',
            'channel' => 'APP',
            'buildNumber' => 1062,
            'appId' => 'vn.momo.bank',
        );
        return $this->CURL("ekyc_ocr_result",$header,$this->Encrypt_data($Data,$requestkeyRaw));
    }

    public function ekyc_ocr_confirm($result)
    {
        $requestkeyRaw = $this->generateRandom(32);
        $requestkey = $this->RSA_Encrypt($this->config["RSA_PUBLIC_KEY"],$requestkeyRaw);
        $header = array(
            "agent_id: ".$this->config["agent_id"],
            "user_phone: ".$this->config["phone"],
            "sessionkey: ".$this->config["sessionkey"],
            "authorization: Bearer ".$this->config["authorization"],
            "userid: ".$this->config["phone"],
            "requestkey: ".$requestkey,
            "Host: owa.momo.vn"
            );
        $Data =  array(
            'kycReference' => $this->send['kycReference'],
            'agent' => $this->config['phone'],
            'data' => $result,
            'serviceId' => 'ekyc_service',
            'action' => 'OCR_CONFIRM',
            'resultReference' => $this->send['resultReference'],
            'appCode' => $this->momo_data_config["appCode"],
            'appVer' => $this->momo_data_config["appVer"],
            'lang' => 'vi',
            'deviceOS' => 'ANDROID',
            'channel' => 'APP',
            'buildNumber' => 1062,
            'appId' => 'vn.momo.bank',
        );
        return $this->CURL("ekyc_ocr_confirm",$header,$this->Encrypt_data($Data,$requestkeyRaw));
    }

    public function VOUCHER_GET()
    {
        $microtime = $this->get_microtime();
        $requestkeyRaw = $this->generateRandom(32);
        $requestkey = $this->RSA_Encrypt($this->config["RSA_PUBLIC_KEY"],$requestkeyRaw);
        $header = array(
            "agent_id: ".$this->config["agent_id"],
            "user_phone: ".$this->config["phone"],
            "sessionkey: ".$this->config["sessionkey"],
            "authorization: Bearer ".$this->config["authorization"],
            "msgtype: VOUCHER_GET",
            "userid: ".$this->config["phone"],
            "requestkey: ".$requestkey,
            "Host: owa.momo.vn"
            );
        $Data = array (
            'user' => $this->config['phone'],
            'msgType' => 'VOUCHER_GET',
            'cmdId' => (string) $microtime. '000000',
            'lang' => 'vi',
            'time' => (int) $microtime,
            'channel' => 'APP',
            'appVer' => $this->momo_data_config["appVer"],
            'appCode' => $this->momo_data_config["appCode"],
            'deviceOS' => 'ANDROID',
            'buildNumber' => 1987,
            'appId' => 'vn.momo.platform',
            'result' => true,
            'errorCode' => 0,
            'errorDesc' => '',
            'momoMsg' => 
            array (
              '_class' => 'mservice.backend.entity.msg.SyncMsg',
              'limit'  => 100,
              'page'   => 1,
            ),
            'extra' => 
            array (
              'checkSum' => $this->generateCheckSum('VOUCHER_GET',$microtime),
            ),
        );
        return $this->CURL("VOUCHER_GET",$header,$this->Encrypt_data($Data,$requestkeyRaw));

    }

    public function BANK_OTP($TranHisInit)
    {
        $microtime = $this->get_microtime();
        $requestkeyRaw = $this->generateRandom(32);
        $requestkey = $this->RSA_Encrypt($this->config["RSA_PUBLIC_KEY"],$requestkeyRaw);
        $header = array(
            "agent_id: ".$this->config["agent_id"],
            "user_phone: ".$this->config["phone"],
            "sessionkey: ".$this->config["sessionkey"],
            "authorization: Bearer ".$this->config["authorization"],
            "msgtype: BANK_OTP",
            "userid: ".$this->config["phone"],
            "requestkey: ".$requestkey,
            "Host: owa.momo.vn"
            );
        $Data = array (
            'user' => $this->config['phone'],
            'msgType' => 'BANK_OTP',
            'cmdId' => (string) $microtime. '000000',
            'lang' => 'vi',
            'time' => (int) $microtime,
            'channel' => 'APP',
            'appVer' => $this->momo_data_config["appVer"],
            'appCode' => $this->momo_data_config["appCode"],
            'deviceOS' => 'ANDROID',
            'buildNumber' => 1987,
            'appId' => 'vn.momo.platform',
            'result' => true,
            'errorCode' => 0,
            'errorDesc' => '',
            'momoMsg' => $TranHisInit,
            'extra' => 
            array (
              'checkSum' => $this->generateCheckSum('BANK_OTP',$microtime),
            ),
        );
        return $this->CURL("BANK_OTP",$header,$this->Encrypt_data($Data,$requestkeyRaw));
    }

    public function CHECK_INFO()
    {
        $microtime = $this->get_microtime();
        $requestkeyRaw = $this->generateRandom(32);
        $requestkey = $this->RSA_Encrypt($this->config["RSA_PUBLIC_KEY"],$requestkeyRaw);
        $header = array(
            "agent_id: ".$this->config["agent_id"],
            "user_phone: ".$this->config["phone"],
            "sessionkey: ".$this->config["sessionkey"],
            "authorization: Bearer ".$this->config["authorization"],
            "msgtype: CHECK_INFO",
            "userid: ".$this->config["phone"],
            "requestkey: ".$requestkey,
            "Host: owa.momo.vn"
            );
        $Data =  array(
            'user' => $this->config['phone'],
            'msgType' => 'CHECK_INFO',
            'cmdId' => (string) $microtime. '000000',
            'lang' => 'vi',
            'time' => $microtime,
            'channel' => 'APP',
            'appVer' => $this->momo_data_config["appVer"],
            'appCode' => $this->momo_data_config["appCode"],
            'deviceOS' => 'ANDROID',
            'buildNumber' => 1537,
            'appId' => 'vn.momo.billpay',
            'result' => true,
            'errorCode' => 0,
            'errorDesc' => '',
            'momoMsg' => 
            array(
              'clientTime' => $microtime - 500,
              'tranType' => 7,
              'pageNumber' => 1,
              'parentTranType' => 3,
              'quantity' => 1,
              'billId' => $this->send['billId'],
              'serviceId' => $this->send['serviceId'],
              'serviceName' => $this->send['serviceName'],
              'category' => 21,
              'extras' => '{}',
              '_class' => 'mservice.backend.entity.msg.TranHisMsg',
           ),
            'extra' => 
            array(
              'currentFormDataModel' => '{"formData":"{}"}',
              'checkSum' => $this->generateCheckSum('CHECK_INFO', $microtime),
           ),
        );
        return $this->CURL("CHECK_INFO",$header,$this->Encrypt_data($Data,$requestkeyRaw));
    }

    private function NEXT_PAGE_MSG()
    {
        $microtime = $this->get_microtime();
        $requestkeyRaw = $this->generateRandom(32);
        $requestkey = $this->RSA_Encrypt($this->config["RSA_PUBLIC_KEY"],$requestkeyRaw);
        $header = array(
            "agent_id: ".$this->config["agent_id"],
            "user_phone: ".$this->config["phone"],
            "sessionkey: ".$this->config["sessionkey"],
            "authorization: Bearer ".$this->config["authorization"],
            "msgtype: NEXT_PAGE_MSG",
            "userid: ".$this->config["phone"],
            "requestkey: ".$requestkey,
            "Host: owa.momo.vn"
            );
        $Data = array (
            'user' => $this->config['phone'],
            'msgType' => 'NEXT_PAGE_MSG',
            'cmdId' => (string) $microtime. '000000',
            'lang' => 'vi',
            'time' => $microtime,
            'channel' => 'APP',
            'appVer' => $this->momo_data_config["appVer"],
            'appCode' => $this->momo_data_config["appCode"],
            'deviceOS' => 'ANDROID',
            'buildNumber' => 0,
            'appId' => 'vn.momo.platform',
            'result' => true,
            'errorCode' => 0,
            'errorDesc' => '',
            'momoMsg' => 
            array (
              'user' => $this->config['phone'],
              'quantity' => 1,
              'pageNumber' => 1,
              'extras' => '{"vpc_CardType":"SML","vpc_TicketNo":"116.104.162.112","vpc_PaymentGateway":""}',
              '_class' => 'mservice.backend.entity.msg.TranHisMsg',
              'serviceId' => $this->send['serviceId'],
              'ownerName' => $this->send['ownerName'],
              'originalAmount' => $this->send['amount'],
              'amount' => $this->send['amount'],
              'partnerId' => $this->config['phone'],
              'partnerExtra1' => $this->config['Name'],
              'discount' => 0,
              'serviceName' => 'Mua mã thẻ',
              'clientTime' => $microtime - 222,
              'category' => 11,
              'tranType' => 7,
              'moneySource' => 1,
              'partnerCode' => 'momo',
              'rowCardId' => '',
              'giftId' => '',
              'useVoucher' => 0,
              'prepaidIds' => '',
              'usePrepaid' => 0,
            ),
            'extra' => 
            array (
              'checkSum' => $this->generateCheckSum('NEXT_PAGE_MSG',$microtime),
            ),
        );
        return $this->CURL("NEXT_PAGE_MSG",$header,$this->Encrypt_data($Data,$requestkeyRaw));
    }

    public function SERVICE_UNAVAILABLE()
    {
        $microtime = $this->get_microtime();
        $requestkeyRaw = $this->generateRandom(32);
        $requestkey = $this->RSA_Encrypt($this->config["RSA_PUBLIC_KEY"],$requestkeyRaw);
        $header = array(
            "agent_id: ".$this->config["agent_id"],
            "user_phone: ".$this->config["phone"],
            "sessionkey: ".$this->config["sessionkey"],
            "authorization: Bearer ".$this->config["authorization"],
            "msgtype: SERVICE_UNAVAILABLE",
            "userid: ".$this->config["phone"],
            "requestkey: ".$requestkey,
            "Host: owa.momo.vn"
            );
        $Data = array (
            'user' => $this->config['phone'],
            'msgType' => 'SERVICE_UNAVAILABLE',
            'cmdId' => (string) $microtime. '000000',
            'lang' => 'vi',
            'time' => $microtime,
            'channel' => 'APP',
            'appVer' => $this->momo_data_config["appVer"],
            'appCode' => $this->momo_data_config["appCode"],
            'deviceOS' => 'ANDROID',
            'buildNumber' => 1087,
            'appId' => 'vn.momo.mobilecenter',
            'result' => true,
            'errorCode' => 0,
            'errorDesc' => '',
            'momoMsg' => 
            array (
              'code' => 'topup_Viettel',
              'name' => 'Viettel',
              '_class' => 'mservice.backend.entity.msg.ServiceModel',
            ),
            'extra' => 
            array (
              'checkSum' => $this->generateCheckSum('SERVICE_UNAVAILABLE', $microtime),
            ),
        );
        return $this->CURL("SERVICE_UNAVAILABLE",$header,$this->Encrypt_data($Data,$requestkeyRaw));
    }

    public function VERIFY_MAP()
    {
        $microtime = $this->get_microtime();
        $requestkeyRaw = $this->generateRandom(32);
        $requestkey = $this->RSA_Encrypt($this->config["RSA_PUBLIC_KEY"],$requestkeyRaw);
        $header = array(
            "agent_id: ".$this->config["agent_id"],
            "user_phone: ".$this->config["phone"],
            "sessionkey: ".$this->config["sessionkey"],
            "authorization: Bearer ".$this->config["authorization"],
            "msgtype: VERIFY_MAP",
            "userid: ".$this->config["phone"],
            "requestkey: ".$requestkey,
            "Host: owa.momo.vn"
            );
        $Data = array (
            'user' => $this->config['phone'],
            'msgType' => 'VERIFY_MAP',
            'cmdId' => (string) $microtime. '000000',
            'lang' => 'vi',
            'time' => $microtime,
            'channel' => 'APP',
            'appVer' => $this->momo_data_config["appVer"],
            'appCode' => $this->momo_data_config["appCode"],
            'deviceOS' => 'ANDROID',
            'buildNumber' => 1062,
            'appId' => 'vn.momo.bank',
            'result' => true,
            'errorCode' => 0,
            'errorDesc' => '',
            'momoMsg' => 
            array (
              'rowCardNum' => $this->send['rowCardNum'],
              'extras' => $this->send['extras'],
              'customerNumber' => $this->send['customerNumber'],
              'partnerId' => $this->send['partnerId'],
              'partnerCode' => $this->BankId[$this->send['BankName']]['partnerCode'],
              '_class' => 'mservice.backend.entity.msg.TranHisMsg',
            ),
            'extra' => 
            array (
              'checkSum' => $this->generateCheckSum('VERIFY_MAP',$microtime),
            ),
        );
        return $this->CURL("VERIFY_MAP",$header,$this->Encrypt_data($Data,$requestkeyRaw));
    }

    public function GET_DETAIL_LOAN()
    {
        $microtime = $this->get_microtime();
        $requestkeyRaw = $this->generateRandom(32);
        $requestkey = $this->RSA_Encrypt($this->config["RSA_PUBLIC_KEY"],$requestkeyRaw);
        $header = array(
            "agent_id: ".$this->config["agent_id"],
            "user_phone: ".$this->config["phone"],
            "sessionkey: ".$this->config["sessionkey"],
            "authorization: Bearer ".$this->config["authorization"],
            "msgtype: GET_DETAIL_LOAN",
            "userid: ".$this->config["phone"],
            "requestkey: ".$requestkey,
            "Host: owa.momo.vn"
            );
        $Data = array (
            'user' => $this->config['phone'],
            'msgType' => 'GET_DETAIL_LOAN',
            'cmdId' => (string) $microtime. '000000',
            'lang' => 'vi',
            'time' => (int) $microtime,
            'channel' => 'APP',
            'appVer' => $this->momo_data_config["appVer"],
            'appCode' => $this->momo_data_config["appCode"],
            'deviceOS' => 'ANDROID',
            'buildNumber' => 1987,
            'appId' => 'vn.momo.transfer',
            'result' => true,
            'errorCode' => 0,
            'errorDesc' => '',
            'momoMsg' => 
            array (
              'appSendChat' => false,
              'loanGroupId' => '1629731094629',
              'loanHisId' => '54d761ac-3795-4dfc-af2b-51585455315a',
              '_class' => 'mservice.backend.entity.msg.LoanDetailMsg',
            ),
            'extra' => 
            array (
              'checkSum' => $this->generateCheckSum('GET_DETAIL_LOAN',$microtime),
            ),
        );
        return $this->CURL("GET_DETAIL_LOAN",$header,$this->Encrypt_data($Data,$requestkeyRaw));
    }

    public function GET_CORE_PREPAID_CARD()
    {
        $microtime = $this->get_microtime();
        $requestkeyRaw = $this->generateImei();
        $requestkey = $this->RSA_Encrypt($this->config["RSA_PUBLIC_KEY"],$requestkeyRaw);
        echo $requestkey; die;
        $header = array(
            "agent_id: ".$this->config["agent_id"],
            "user_phone: ".$this->config["phone"],
            "sessionkey: ".$this->config["sessionkey"],
            "authorization: Bearer ".$this->config["authorization"],
            "msgtype: GET_DETAIL_LOAN",
            "userid: ".$this->config["phone"],
            "requestkey: ".$requestkey,
            "Host: owa.momo.vn"
        );
        $Data = array (
            'user' => $this->config['phone'],
            'msgType' => 'GET_CORE_PREPAID_CARD',
            'cmdId' => (string) $microtime. '000000',
            'lang' => 'vi',
            'time' => (int) $microtime,
            'channel' => 'APP',
            'appVer' => $this->momo_data_config["appVer"],
            'appCode' => $this->momo_data_config["appCode"],
            'deviceOS' => 'ANDROID',
            'buildNumber' => 1987,
            'appId' => 'vn.momo.platform',
            'result' => true,
            'errorCode' => 0,
            'errorDesc' => '',
            'momoMsg' => 
            array (
              "partnerCode" =>"napas_cashin_tokenization",
              "moneySource" => 5,
              '_class' => 'mservice.backend.entity.msg.SyncMsg'
            ),
            'extra' => 
            array (
              'checkSum' => $this->generateCheckSum('GET_CORE_PREPAID_CARD',$microtime),
            ),
        );
        return $this->CURL("GET_CORE_PREPAID_CARD",$header,$this->Encrypt_data($Data,$requestkeyRaw));

    }

    public function CARD_GET()
    {
        $microtime = $this->get_microtime();
        $requestkeyRaw = $this->generateRandom(32);
        $requestkey = $this->RSA_Encrypt($this->config["RSA_PUBLIC_KEY"],$requestkeyRaw);
        $header = array(
            "agent_id: ".$this->config["agent_id"],
            "user_phone: ".$this->config["phone"],
            "sessionkey: ".$this->config["sessionkey"],
            "authorization: Bearer ".$this->config["authorization"],
            "msgtype: CARD_GET",
            "userid: ".$this->config["phone"],
            "requestkey: ".$requestkey,
            "Host: owa.momo.vn"
        );
        $Data = array (
            'user' => $this->config['phone'],
            'msgType' => 'CARD_GET',
            'cmdId' => (string) $microtime. '000000',
            'lang' => 'vi',
            'time' => (int) $microtime,
            'channel' => 'APP',
            'appVer' => $this->momo_data_config["appVer"],
            'appCode' => $this->momo_data_config["appCode"],
            'deviceOS' => 'ANDROID',
            'buildNumber' => 1987,
            'appId' => 'vn.momo.platform',
            'result' => true,
            'errorCode' => 0,
            'errorDesc' => '',
            'momoMsg' => 
            array (
              '_class' => 'mservice.backend.entity.msg.SyncMsg'
            ),
            'extra' => 
            array (
              'checkSum' => $this->generateCheckSum('CARD_GET',$microtime),
            ),
        );
        return $this->CURL("CARD_GET",$header,$this->Encrypt_data($Data,$requestkeyRaw));
    }

    
    public function HEARTED_TRANSACTIONS()
    {
        $microtime = $this->get_microtime();
        $requestkeyRaw = $this->generateRandom(32);
        $requestkey = $this->RSA_Encrypt($this->config["RSA_PUBLIC_KEY"],$requestkeyRaw);
        $header = array(
            "agent_id: ".$this->config["agent_id"],
            "user_phone: ".$this->config["phone"],
            "sessionkey: ".$this->config["sessionkey"],
            "authorization: Bearer ".$this->config["authorization"],
            "msgtype: HEARTED_TRANSACTIONS",
            "userid: ".$this->config["phone"],
            "requestkey: ".$requestkey,
            "Host: owa.momo.vn"
            );
        $Data = array (
            'user' => $this->config['phone'],
            'msgType' => 'HEARTED_TRANSACTIONS',
            'cmdId' => (string) $microtime. '000000',
            'lang' => 'vi',
            'time' => $microtime,
            'channel' => 'APP',
            'appVer' => $this->momo_data_config("appVer"),
            'appCode' => $this->momo_data_config("appCode"),
            'deviceOS' => 'ANDROID',
            'buildNumber' => 1987,
            'appId' => 'vn.momo.transfer',
            'result' => true,
            'errorCode' => 0,
            'errorDesc' => '',
            'momoMsg' => 
            array (
              '_class' => 'mservice.backend.entity.msg.ForwardMsg',
            ),
            'extra' => 
            array (
              'checkSum' => $this->generateCheckSum('HEARTED_TRANSACTIONS',$microtime),
            ),
        );
        return $this->CURL("HEARTED_TRANSACTIONS",$header,$this->Encrypt_data($Data,$requestkeyRaw));

    }

    public function LOAN_UPDATE_STATUS()
    {
        $microtime = $this->get_microtime();
        $requestkeyRaw = $this->generateRandom(32);
        $requestkey = $this->RSA_Encrypt($this->config["RSA_PUBLIC_KEY"],$requestkeyRaw);
        $header = array(
            "agent_id: ".$this->config["agent_id"],
            "user_phone: ".$this->config["phone"],
            "sessionkey: ".$this->config["sessionkey"],
            "authorization: Bearer ".$this->config["authorization"],
            "msgtype: LOAN_UPDATE_STATUS",
            "userid: ".$this->config["phone"],
            "requestkey: ".$requestkey,
            "Host: owa.momo.vn"
            );
        $Data = array (
            'user' => $this->config['phone'],
            'msgType' => 'LOAN_UPDATE_STATUS',
            'cmdId' => (string) $microtime. '000000',
            'lang' => 'vi',
            'time' => $microtime,
            'channel' => 'APP',
            'appVer' => $this->momo_data_config["appVer"],
            'appCode' => $this->momo_data_config["appCode"],
            'deviceOS' => 'ANDROID',
            'buildNumber' => 1874,
            'appId' => 'vn.momo.chat',
            'result' => true,
            'errorCode' => 0,
            'errorDesc' => '',
            'momoMsg' => 
            array (
              'appSendChat' => false,
              'replyTo' => '0977371507',
              'senderName' => 'NGUYỄN VĂN ĐẠT',
              'loanId' => '56058822',
              'accept' => false,
              '_class' => 'mservice.backend.entity.msg.LoanResponseMsg',
            ),
            'extra' => 
            array (
              'checkSum' => $this->generateCheckSum('LOAN_UPDATE_STATUS',$microtime),
            ),
        );
        return $this->CURL("LOAN_UPDATE_STATUS",$header,$this->Encrypt_data($Data,$requestkeyRaw));
    }

    public function CANCEL_LOAN_REQUEST($info)
    {
        $microtime = $this->get_microtime();
        $requestkeyRaw = $this->generateRandom(32);
        $requestkey = $this->RSA_Encrypt($this->config["RSA_PUBLIC_KEY"],$requestkeyRaw);
        $header = array(
            "agent_id: ".$this->config["agent_id"],
            "user_phone: ".$this->config["phone"],
            "sessionkey: ".$this->config["sessionkey"],
            "authorization: Bearer ".$this->config["authorization"],
            "msgtype: CANCEL_LOAN_REQUEST",
            "userid: ".$this->config["phone"],
            "requestkey: ".$requestkey,
            "Host: owa.momo.vn"
            );
        $Data = array (
            'user' => $this->config['phone'],
            'msgType' => 'CANCEL_LOAN_REQUEST',
            'cmdId' => (string) $microtime. '000000',
            'lang' => 'vi',
            'time' => (int) $microtime,
            'channel' => 'APP',
            'appVer' => $this->momo_data_config["appVer"],
            'appCode' => $this->momo_data_config["appCode"],
            'deviceOS' => 'ANDROID',
            'appId' => 'vn.momo.transfer',
            'result' => true,
            'errorCode' => 0,
            'errorDesc' => '',
            'momoMsg' => 
            array (
                'senderName' => $this->config['Name'],
                'senderId' => $this->config['phone'],
                'partnerId' => $info['partnerId'],
                'partnerName' => $info['partnerName'],
                'loanGroup' => $info['loanGroup'],
                'loanGroupId' => $info['loanGroupId'],
                '_class' => 'mservice.backend.entity.msg.LoanCancelMsg',
            ),
            'extra' => 
            array (
              'checkSum' => $this->generateCheckSum('CANCEL_LOAN_REQUEST',$microtime),
            ),
        );
        return $this->CURL("CANCEL_LOAN_REQUEST",$header,$this->Encrypt_data($Data,$requestkeyRaw));
    }

    private function LOAN_MSG()
    {
        $microtime = $this->get_microtime();
        $requestkeyRaw = $this->generateRandom(32);
        $requestkey = $this->RSA_Encrypt($this->config["RSA_PUBLIC_KEY"],$requestkeyRaw);
        $header = array(
            "agent_id: ".$this->config["agent_id"],
            "user_phone: ".$this->config["phone"],
            "sessionkey: ".$this->config["sessionkey"],
            "authorization: Bearer ".$this->config["authorization"],
            "msgtype: M2MU_INIT",
            "userid: ".$this->config["phone"],
            "requestkey: ".$requestkey,
            "Host: owa.momo.vn"
            );
        $Data = array(
            "user" => $this->config["phone"],
            "msgType" => "LOAN_MSG",
            "cmdId" => (string) $microtime."000000",
            "lang"  => "vi",
            "time"  => $microtime,
            "channel" => "APP",
            "appVer"  => $this->momo_data_config["appVer"],
            "appCode" => $this->momo_data_config["appCode"],
            "deviceOS"=> "ANDROID",
            "buildNumber" => 1874,
            "appId"   => "vn.momo.platform",
            "result"  => true,
            "errorCode" => 0,
            "errorDesc" =>"",
            "momoMsg" => array(
                "_class" => "mservice.backend.entity.msg.M2MUInitMsg",
                "tranList" => [
                    array(
                        "_class" => "mservice.backend.entity.msg.TranHisMsg",
                        "user" => $this->config["phone"],
                        "clientTime" => ($microtime - 251),
                        "tranType"   => 36,
                        "amount" => $this->send["amount"],
                        "receiverType" => 1
                    ),
                    array(
                        "_class" => "mservice.backend.entity.msg.TranHisMsg",
                        "user"   => $this->config["phone"],
                        "clientTime" => ($microtime - 251),
                        "tranType"   => 36,
                        "partnerId"  => $this->send["receiver"],
                        "amount"     => $this->send["amount"],
                        "comment"    => $this->send["comment"],
                        "ownerName"  => "",
                        "receiverType" => 0,
                        "partnerExtra1" => '{\"totalAmount\":'.$this->send["amount"].'}',
                        "partnerInvNo"  => "borrow"
                    )
                    ]
                ),
                "extra" => array(
                    "checkSum" => $this->generateCheckSum("LOAN_MSG",$microtime)
                )
        );
            return $this->CURL("LOAN_MSG",$header,$this->Encrypt_data($Data,$requestkeyRaw));
    }

    public function STANDARD_LOAN_REQUEST()
    {
        $microtime = $this->get_microtime();
        $requestkeyRaw = $this->generateRandom(32);
        $requestkey = $this->RSA_Encrypt($this->config["RSA_PUBLIC_KEY"],$requestkeyRaw);
        $header = array(
            "agent_id: ".$this->config["agent_id"],
            "user_phone: ".$this->config["phone"],
            "sessionkey: ".$this->config["sessionkey"],
            "authorization: Bearer ".$this->config["authorization"],
            "msgtype: STANDARD_LOAN_REQUEST",
            "userid: ".$this->config["phone"],
            "requestkey: ".$requestkey,
            "Host: owa.momo.vn"
            );
        $Data = array (
            'user' => $this->config['phone'],
            'msgType' => 'STANDARD_LOAN_REQUEST',
            'cmdId' => (string) $microtime. '000000',
            'lang' => 'vi',
            'time' => (int) $microtime,
            'channel' => 'APP',
            'appVer' => $this->momo_data_config["appVer"],
            'appCode' => $this->momo_data_config["appCode"],
            'deviceOS' => 'ANDROID',
            'buildNumber' => 1987,
            'appId' => 'vn.momo.transfer',
            'result' => true,
            'errorCode' => 0,
            'errorDesc' => '',
            'momoMsg' => 
            array (
              '_class' => 'mservice.backend.entity.msg.LoanDetailMsg',
            ),
            'extra' => 
            array (
              'checkSum' => $this->generateCheckSum('STANDARD_LOAN_REQUEST',$microtime),
            ),
        );
        return $this->CURL("STANDARD_LOAN_REQUEST",$header,$this->Encrypt_data($Data,$requestkeyRaw));
    }

    public function LOAN_SUGGEST()
    {
        $microtime = $this->get_microtime();
        $requestkeyRaw = $this->generateRandom(32);
        $requestkey = $this->RSA_Encrypt($this->config["RSA_PUBLIC_KEY"],$requestkeyRaw);
        $header = array(
            "agent_id: ".$this->config["agent_id"],
            "user_phone: ".$this->config["phone"],
            "sessionkey: ".$this->config["sessionkey"],
            "authorization: Bearer ".$this->config["authorization"],
            "msgtype: LOAN_SUGGEST",
            "userid: ".$this->config["phone"],
            "requestkey: ".$requestkey,
            "Host: owa.momo.vn"
            );
        $Data = array (
            'user' => $this->config['phone'],
            'msgType' => 'LOAN_SUGGEST',
            'cmdId' => (string) $microtime. '000000',
            'lang' => 'vi',
            'time' => (int) $microtime,
            'channel' => 'APP',
            'appVer' => $this->momo_data_config["appVer"],
            'appCode' => $this->momo_data_config["appCode"],
            'deviceOS' => 'ANDROID',
            'buildNumber' => 1987,
            'appId' => 'vn.momo.transfer',
            'result' => true,
            'errorCode' => 0,
            'errorDesc' => '',
            'momoMsg' => 
            array (
              '_class' => 'mservice.backend.entity.msg.LoanDetailMsg',
            ),
            'extra' => 
            array (
              'checkSum' => $this->generateCheckSum('LOAN_SUGGEST',$microtime),
            ),
        );
        return $this->CURL("LOAN_SUGGEST",$header,$this->Encrypt_data($Data,$requestkeyRaw));
    }

    public function MANAGE_CREDIT_CARD()
    {
        $microtime = $this->get_microtime();
        $requestkeyRaw = $this->generateRandom(32);
        $requestkey = $this->RSA_Encrypt($this->config["RSA_PUBLIC_KEY"],$requestkeyRaw);
        $header = array(
            "agent_id: ".$this->config["agent_id"],
            "user_phone: ".$this->config["phone"],
            "sessionkey: ".$this->config["sessionkey"],
            "authorization: Bearer ".$this->config["authorization"],
            "msgtype: MANAGE_CREDIT_CARD",
            "userid: ".$this->config["phone"],
            "requestkey: ".$requestkey,
            "Host: owa.momo.vn"
        );
        $Data = array (
            'user' => $this->config['phone'],
            'msgType' => 'MANAGE_CREDIT_CARD',
            'cmdId' => (string) $microtime. '000000',
            'lang' => 'vi',
            'time' => $microtime,
            'channel' => 'APP',
            'appVer' => $this->momo_data_config["appVer"],
            'appCode' => $this->momo_data_config["appCode"],
            'deviceOS' => 'ANDROID',
            'buildNumber' => 1062,
            'appId' => 'vn.momo.bank',
            'result' => true,
            'errorCode' => 0,
            'errorDesc' => '',
            'momoMsg' => 
            array (
              'action' => 2,
              '_class' => 'mservice.backend.entity.msg.ManageCreditCardMsg',
              'clientIp' => '172.16.14.223',
              'cardList' => 
              array (
                0 => 
                array (
                  '_class' => 'mservice.backend.entity.msg.CardInfoMsg',
                  'cardType' => '001',
                  'cardNumber' => '',
                  'cardExpired' => '10/2026',
                  'cardHolder' => 'VU TUNG DUY',
                  'email' => $this->config["email"]."@gmail.com",
                  'address' => 'HẢI DƯƠNG',
                  'cvn' => '144',
                  'personal_id_verify' => '040203013718',
                ),
              ),
            ),
            'extra' => 
            array (
              'checkSum' => $this->generateCheckSum('MANAGE_CREDIT_CARD', $microtime),
            ),
        );
        return $this->CURL("MANAGE_CREDIT_CARD",$header,$this->Encrypt_data($Data,$requestkeyRaw));

    }

    public function ins_qoala_phone()
    {
        $microtime = $this->get_microtime();
        $requestkeyRaw = $this->generateRandom(32);
        $requestkey = $this->RSA_Encrypt($this->config["RSA_PUBLIC_KEY"],$requestkeyRaw);
        $header = array(
            "authorization: Bearer ".$this->config["authorization"],
            "Host: owa.momo.vn",
            'User-Agent: okhttp/3.14.7',
            'Connection: Keep-Alive',
            );
        $Data =  array(
            'requestId' => (string) $this->config['phone'] . $microtime,
            'type' => 50,
            'source' => 2,
            'serviceCode' => 'MobileTopup',
            'debitorEmail' => 'nguyenthihaudk41@gmail.com',
            'debitorName' => $this->config['Name'],
            'debitor' => $this->config['phone'],
            'reference1' => '',
            'reference2' => '',
            'phoneDetail' =>  array(
                    'brand' => $this->config['facture'],
                    'model' => $this->config['device'],
                ),
                    'appCode' => $this->momo_data_config["appCode"],
                    'appVer' => $this->momo_data_config["appVer"],
                    'lang' => 'vi',
                    'deviceOS' => 'ANDROID',
                    'channel' => 'APP',
                    'buildNumber' => 1087,
                    'appId' => 'vn.momo.mobilecenter',
        );
        return $this->CURL('ins_qoala_phone',$header,$Data);
    }

    public function M2M_VALIDATE_MSG($phone, $message = '')
    {
        $microtime = $this->get_microtime();
        $requestkeyRaw = $this->generateRandom(32);
        $requestkey = $this->RSA_Encrypt($this->config["RSA_PUBLIC_KEY"],$requestkeyRaw);
        $header = array(
            "agent_id: ".$this->config["agent_id"],
            "user_phone: ".$this->config["phone"],
            "sessionkey: ".$this->config["sessionkey"],
            "authorization: Bearer ".$this->config["authorization"],
            "msgtype: M2M_VALIDATE_MSG",
            "userid: ".$this->config["phone"],
            "requestkey: ".$requestkey,
            "Host: owa.momo.vn"
            );
        $Data = '{
            "user":"'.$this->config['phone'].'",
            "msgType":"M2M_VALIDATE_MSG",
            "cmdId":"'.$microtime.'000000",
            "lang":"vi",
            "time":'.(int) $microtime.',
            "channel":"APP",
            "appVer": '.$this->momo_data_config["appVer"].',
            "appCode": "'.$this->momo_data_config["appCode"].'",
            "deviceOS":"ANDROID",
            "buildNumber":1916,
            "appId":"vn.momo.transfer",
            "result":true,
            "errorCode":0,
            "errorDesc":"",
            "momoMsg":
            {
                "partnerId":"'.$phone.'",
                "_class":"mservice.backend.entity.msg.ForwardMsg",
                "message":"'.$this->get_string($message).'"
            },
            "extra":
            {
                "checkSum":"'.$this->generateCheckSum('M2M_VALIDATE_MSG',$microtime).'"
            }
        }';
        return $this->CURL("M2M_VALIDATE_MSG",$header,$this->Encrypt_data($Data,$requestkeyRaw));
    }

    public function TRAN_HIS_INIT_MSG($tranHisMsg)
    {
        $microtime = $this->get_microtime();
        $requestkeyRaw = $this->generateRandom(32);
        $requestkey = $this->RSA_Encrypt($this->config["RSA_PUBLIC_KEY"],$requestkeyRaw);
        $header = array(
            "agent_id: ".$this->config["agent_id"],
            "user_phone: ".$this->config["phone"],
            "sessionkey: ".$this->config["sessionkey"],
            "authorization: Bearer ".$this->config["authorization"],
            "msgtype: TRAN_HIS_INIT_MSG",
            "userid: ".$this->config["phone"],
            "requestkey: ".$requestkey,
            "Host: owa.momo.vn"
            );
        $Data = array(
            "user" =>  $this->config['phone'],
            "msgType" => "TRAN_HIS_INIT_MSG",
            "cmdId"   => (string) $microtime.'000000',
            "lang"    => "vi",
            "time"    =>  (int) $microtime,
            "channel" => "APP",
            "appVer"  =>  $this->momo_data_config["appVer"],
            "appCode" => $this->momo_data_config["appCode"],
            "deviceOS"=> "ANDROID",
            "buildNumber"=> 0,
            "appId"   => "vn.momo.platform",
            "result"  => true,
            "errorCode"=> 0,
            "errorDesc"=> "",
            "momoMsg" => $tranHisMsg,
            "extra" => array(
                "checkSum" => $this->generateCheckSum('TRAN_HIS_INIT_MSG',$microtime)
            )
        );
        return $this->CURL("TRAN_HIS_INIT_MSG",$header,$this->Encrypt_data($Data,$requestkeyRaw));
    }

    public function TRAN_HIS_CONFIRM_MSG($tranHisMsg = [])
    {
        $microtime = $this->get_microtime();
        $requestkeyRaw = $this->generateRandom(32);
        $requestkey = $this->RSA_Encrypt($this->config["RSA_PUBLIC_KEY"],$requestkeyRaw);
        $header = array(
            "agent_id: ".$this->config["agent_id"],
            "user_phone: ".$this->config["phone"],
            "sessionkey: ".$this->config["sessionkey"],
            "authorization: Bearer ".$this->config["authorization"],
            "msgtype: TRAN_HIS_CONFIRM_MSG",
            "userid: ".$this->config["phone"],
            "requestkey: ".$requestkey,
            "Host: owa.momo.vn"
            );
        $Data =  array(
            'user'    => $this->config['phone'],
            'pass'    => $this->config['password'],
            'msgType' => 'TRAN_HIS_CONFIRM_MSG',
            'cmdId'   => (string) $microtime.'000000',
            'lang'    => 'vi',
            'time'    => $microtime,
            'channel' => 'APP',
            'appVer'  => $this->momo_data_config["appVer"],
            'appCode' => $this->momo_data_config["appCode"],
            'deviceOS' => 'ANDROID',
            'buildNumber' => 0,
            'appId'   => 'vn.momo.platform',
            'result'  => true,
            'errorCode' => 0,
            'errorDesc' => '',
            'momoMsg' => $tranHisMsg,
            'extra' => 
            array(
              'checkSum' => $this->generateCheckSum('TRAN_HIS_CONFIRM_MSG',$microtime),
           ),
         );
        return $this->CURL("TRAN_HIS_CONFIRM_MSG",$header,$this->Encrypt_data($Data,$requestkeyRaw));
    }

    private function M2MU_CONFIRM($ID)
    {
        $microtime = $this->get_microtime();
        $requestkeyRaw = $this->generateRandom(32);
        $requestkey = $this->RSA_Encrypt($this->config["RSA_PUBLIC_KEY"],$requestkeyRaw);
        $header = array(
            "agent_id: ".$this->config["agent_id"],
            "user_phone: ".$this->config["phone"],
            "sessionkey: ".$this->config["sessionkey"],
            "authorization: Bearer ".$this->config["authorization"],
            "msgtype: M2MU_INIT",
            "userid: ".$this->config["phone"],
            "requestkey: ".$requestkey,
            "Host: owa.momo.vn"
            );
        $ipaddress = $this->get_ip_address();
        $Data =  array(
            'user' => $this->config['phone'],
            'pass' => $this->config['password'],
            'msgType' => 'M2MU_CONFIRM',
            'cmdId' => (string) $microtime.'000000',
            'lang' => 'vi',
            'time' =>(int) $microtime,
            'channel' => 'APP',
            'appVer' => $this->momo_data_config["appVer"],
            'appCode' => $this->momo_data_config["appCode"],
            'deviceOS' => 'ANDROID',
            'buildNumber' => 0,
            'appId' => 'vn.momo.platform',
            'result' => true,
            'errorCode' => 0,
            'errorDesc' => '',
            'momoMsg' => 
            array(
              'ids' => 
             array (
               0 => $ID,
             ),
              'totalAmount' => $this->send['amount'],
              'originalAmount' => $this->send['amount'],
              'originalClass' => 'mservice.backend.entity.msg.M2MUConfirmMsg',
              'originalPhone' => $this->config['phone'],
              'totalFee' => '0.0',
              'id' => $ID,
              'GetUserInfoTaskRequest' => $this->send['receiver'],
              'tranList' => 
             array (
               0 => 
                array(
                  '_class' => 'mservice.backend.entity.msg.TranHisMsg',
                  'user' => $this->config['phone'],
                  'clientTime' => (int) ($microtime - 211),
                  'tranType' => 36,
                  'amount' => (int) $this->send['amount'],
                  'receiverType' => 1,
               ),
               1 => 
                array(
                  '_class' => 'mservice.backend.entity.msg.TranHisMsg',
                  'user' => $this->config['phone'],
                  'clientTime' => (int) ($microtime - 211),
                  'tranType' => 36,
                  'partnerId' => $this->send['receiver'],
                  'amount' => 100,
                  'comment' => '',
                  'ownerName' => $this->config['Name'],
                  'receiverType' => 0,
                  'partnerExtra1' => '{"totalAmount":'.$this->send['amount'].'}',
                  'partnerInvNo' => 'borrow',
               ),
             ),
              'serviceId' => 'transfer_p2p',
              'serviceCode' => 'transfer_p2p',
              'clientTime' => (int) ($microtime - 211),
              'tranType' => 2018,
              'comment' => '',
              'ref' => '',
              'amount' => $this->send['amount'],
              'partnerId' => $this->send['receiver'],
              'bankInId' => '',
              'otp' => '',
              'otpBanknet' => '',
              '_class' => 'mservice.backend.entity.msg.M2MUConfirmMsg',
              'extras' => '{"appSendChat":false,"vpc_CardType":"SML","vpc_TicketNo":"'.$ipaddress.'"","vpc_PaymentGateway":""}',
           ),
            'extra' => 
            array(
              'checkSum' => $this-> generateCheckSum('M2MU_CONFIRM',$microtime),
           ),
         );
          return $this->CURL("M2MU_CONFIRM",$header,$this->Encrypt_data($Data,$requestkeyRaw));

    }

    public function M2MU_INIT()
    {
        $microtime = $this->get_microtime();
        $requestkeyRaw = $this->generateRandom(32);
        $requestkey = $this->RSA_Encrypt($this->config["RSA_PUBLIC_KEY"],$requestkeyRaw);
        $header = array(
            "agent_id: ".$this->config["agent_id"],
            "user_phone: ".$this->config["phone"],
            "sessionkey: ".$this->config["sessionkey"],
            "authorization: Bearer ".$this->config["authorization"],
            "msgtype: M2MU_INIT",
            "userid: ".$this->config["phone"],
            "requestkey: ".$requestkey,
            "Host: owa.momo.vn"
            );
        $ipaddress = $this->get_ip_address();
        $Data = array (
            'user' => $this->config['phone'],
            'msgType' => 'M2MU_INIT',
            'cmdId' => (string) $microtime.'000000',
            'lang' => 'vi',
            'time' => (int) $microtime,
            'channel' => 'APP',
            'appVer' => $this->momo_data_config["appVer"],
            'appCode' => $this->momo_data_config["appCode"],
            'deviceOS' => 'ANDROID',
            'buildNumber' => 0,
            'appId' => 'vn.momo.platform',
            'result' => true,
            'errorCode' => 0,
            'errorDesc' => '',
            'momoMsg' => 
            array (
              'clientTime' => (int) $microtime - 221,
              'tranType' => 2018,
              'comment' => $this->send['comment'],
              'amount' => $this->send['amount'],
              'partnerId' => $this->send['receiver'],
              'partnerName' => $this->send['partnerName'],
              'ref' => '',
              'serviceCode' => 'transfer_p2p',
              'serviceId' => 'transfer_p2p',
              '_class' => 'mservice.backend.entity.msg.M2MUInitMsg',
              'tranList' => 
              array (
                0 => 
                array (
                  'partnerName' => $this->send['partnerName'],
                  'partnerId' => $this->send['receiver'],
                  'originalAmount' => $this->send['amount'],
                  'serviceCode' => 'transfer_p2p',
                  'stickers' => '',
                  'themeBackground' => '#f5fff6',
                  'themeUrl' => 'https://cdn.mservice.com.vn/app/img/transfer/theme/Corona_750x260.png',
                  'transferSource' => '',
                  'socialUserId' => '',
                  '_class' => 'mservice.backend.entity.msg.M2MUInitMsg',
                  'tranType' => 2018,
                  'comment' => $this->send['comment'],
                  'moneySource' => 1,
                  'partnerCode' => 'momo',
                  'serviceMode' => 'transfer_p2p',
                  'serviceId' => 'transfer_p2p',
                  'extras' => '{"loanId":0,"appSendChat":false,"loanIds":[],"stickers":"","themeUrl":"https://cdn.mservice.com.vn/app/img/transfer/theme/Corona_750x260.png","hidePhone":false,"vpc_CardType":"SML","vpc_TicketNo":"'.$ipaddress.'","vpc_PaymentGateway":""}',
                ),
              ),
              'extras' => '{"loanId":0,"appSendChat":false,"loanIds":[],"stickers":"","themeUrl":"https://cdn.mservice.com.vn/app/img/transfer/theme/Corona_750x260.png","hidePhone":false,"vpc_CardType":"SML","vpc_TicketNo":"'.$ipaddress.'","vpc_PaymentGateway":""}',
              'moneySource' => 1,
              'partnerCode' => 'momo',
              'rowCardId' => '',
              'giftId' => '',
              'useVoucher' => 0,
              'prepaidIds' => '',
              'usePrepaid' => 0,
            ),
            'extra' => 
            array (
              'checkSum' => $this->generateCheckSum('M2MU_INIT', $microtime),
            ),
        );
        return $this->CURL("M2MU_INIT",$header,$this->Encrypt_data($Data,$requestkeyRaw));
        
    }

    public function API_DEFAULT_SOURCE()
    {
        $microtime = $this->get_microtime();
        $requestkeyRaw = $this->generateRandom(32);
        $requestkey = $this->RSA_Encrypt($this->config["RSA_PUBLIC_KEY"],$requestkeyRaw);
        $header = array(
            "agent_id: ".$this->config["agent_id"],
            "user_phone: ".$this->config["phone"],
            "sessionkey: ".$this->config["sessionkey"],
            "authorization: Bearer ".$this->config['authorization'],
            "msgtype: QUERY_TRANSACTION_EXPENSE_MANAGEMENT_V2_MSG",
            "userid: ".$this->config["phone"],
            "requestkey: ".$requestkey,
            "Host: owa.momo.vn"
        );
        $Data = array (
            'user' => $this->config['phone'],
            'msgType' => 'API_DEFAULT_SOURCE',
            'cmdId' => (string) $microtime . '000000',
            'lang' => 'vi',
            'time' =>  (int) $microtime,
            'channel' => 'APP',
            'appVer' => 30323,
            'appCode' => $this->momo_data_config["appCode"],
            'deviceOS' => 'ANDROID',
            'buildNumber' => 74,
            'appId' => 'vn.momo.authenlinkingservices',
            'result' => true,
            'errorCode' => 0,
            'errorDesc' => '',
            'momoMsg' => 
            array (
              '_class' => 'mservice.backend.entity.msg.DefaultSourceRequestMsg',
              'action' => 'GET',
            ),
            'extra' => 
            array (
              'checkSum' => $this->generateCheckSum('API_DEFAULT_SOURCE', $microtime),
            ),
        );
        return $this->CURL("API_DEFAULT_SOURCE",$header,$this->Encrypt_data($Data,$requestkeyRaw));
    }

    public function sync()
    {
        $microtime = $this->get_microtime();
        $requestkeyRaw = $this->generateRandom(32);
        $requestkey = $this->RSA_Encrypt($this->config["RSA_PUBLIC_KEY"],$requestkeyRaw);
        $header = array(
            "agent_id: ".$this->config["agent_id"],
            "user_phone: ".$this->config["phone"],
            "sessionkey: ".$this->config["sessionkey"],
            "authorization: Bearer ".$this->config['authorization'],
            "msgtype: QUERY_TRANSACTION_EXPENSE_MANAGEMENT_V2_MSG",
            "userid: ".$this->config["phone"],
            "requestkey: ".$requestkey,
            "Host: owa.momo.vn"
            );
        $Data = array (
            'user' => $this->config['phone'],
            'msgType' => 'QUERY_TRANSACTION_EXPENSE_MANAGEMENT_V2_MSG',
            'cmdId' => (string) $microtime.'000000',
            'lang' => 'vi',
            'time' => $microtime,
            'channel' => 'APP',
            'appVer' => $this->momo_data_config["appVer"],
            'appCode' => $this->momo_data_config["appCode"],
            'deviceOS' => 'ANDROID',
            'buildNumber' => 1720,
            'appId' => 'vn.momo.transactionhistory',
            'result' => true,
            'errorCode' => 0,
            'errorDesc' => '',
            'momoMsg' => 
            array (
              '_class' => 'mservice.backend.entity.msg.ExpenseManagementDataMsg',
              'begin'  => 0,
              'end'    => $microtime
            ),
            'extra' => 
            array (
              'checkSum' => $this->generateCheckSum('QUERY_TRANSACTION_EXPENSE_MANAGEMENT_V2_MSG', $microtime),
            ),
        );
        return $this->CURL("sync",$header,$this->Encrypt_data($Data,$requestkeyRaw));
    }

    public function QUERY_TRAN_HIS_MSG($hours)
    {

        $requestkeyRaw = $this->generateRandom(32);
        $requestkey = $this->RSA_Encrypt($this->config["RSA_PUBLIC_KEY"],$requestkeyRaw);
        $header = array(
            "agent_id: ".$this->config["agent_id"],
            "user_phone: ".$this->config["phone"],
            "sessionkey: ".$this->config["sessionkey"],
            "authorization: Bearer ".$this->config["authorization"],
            "msgtype: QUERY_TRAN_HIS_MSG",
            "userid: ".$this->config["phone"],
            "requestkey: ".$requestkey,
            "Host: owa.momo.vn"
        );

        $begin =  (time() - (3600 * $hours)) * 1000;
        $microtime = $this->get_microtime();
        $Data = array(
            'user' => $this->config['phone'],
            'msgType' => 'QUERY_TRAN_HIS_MSG',
            'cmdId' => (string) $microtime.'000000',
            'time' => $microtime,
            'lang' => 'vi',
            'channel' => 'APP',
            'appVer' => $this->momo_data_config["appVer"],
            'appCode' => $this->momo_data_config["appCode"],
            'deviceOS' => 'ANDROID',
            'appId' => 'vn.momo.platform',
            'result' => true,
            'buildNumber' => 0,
            'errorCode' => 0,
            'errorDesc' => '',
            'extra' => 
            array(
              'checkSum' => $this->generateCheckSum('QUERY_TRAN_HIS_MSG',$microtime),
           ),
            'momoMsg' => 
            array(
              '_class' => 'mservice.backend.entity.msg.QueryTranhisMsg',
              'begin' => $begin,
              'end' => $microtime,
           ),
         );    
        return $this->CURL("QUERY_TRAN_HIS_MSG",$header,$this->Encrypt_data($Data,$requestkeyRaw));

    }
    public function CHECK_USER_PRIVATE($receiver)
    {
        $microtime = $this->get_microtime();
        $requestkeyRaw = $this->generateRandom(32);
        $requestkey = $this->RSA_Encrypt($this->config["RSA_PUBLIC_KEY"],$requestkeyRaw);
        $header = array(
            "agent_id: ".$this->config["agent_id"],
            "user_phone: ".$this->config["phone"],
            "sessionkey: ".$this->config["sessionkey"],
            "authorization: Bearer ".$this->config["authorization"],
            "msgtype: CHECK_USER_PRIVATE",
            "userid: ".$this->config["phone"],
            "requestkey: ".$requestkey,
            "Host: owa.momo.vn"
        );
        $Data = '{
            "user":"'.$this->config['phone'].'",
            "msgType":"CHECK_USER_PRIVATE",
            "cmdId":"'.$microtime.'000000",
            "lang":"vi",
            "time":'.(int) $microtime.',
            "channel":"APP",
            "appVer": '.$this->momo_data_config["appVer"].',
            "appCode": "'.$this->momo_data_config["appCode"].'",
            "deviceOS":"ANDROID",
            "buildNumber":1916,
            "appId":"vn.momo.transfer",
            "result":true,
            "errorCode":0,
            "errorDesc":"",
            "momoMsg":
            {
                "_class":"mservice.backend.entity.msg.LoginMsg",
                "getMutualFriend":false
            },
            "extra":
            {
                "CHECK_INFO_NUMBER":"'.$receiver.'",
                "checkSum":"'.$this->generateCheckSum('CHECK_USER_PRIVATE',$microtime).'"
            }
        }';
          return $this->CURL("CHECK_USER_PRIVATE",$header,$this->Encrypt_data($Data,$requestkeyRaw));
    }

    public function USER_LOGIN_MSG()
    {
        $microtime = $this->get_microtime();
        $header = array(
            "agent_id: ".$this->config["agent_id"],
            "user_phone: ".$this->config["phone"],
            "sessionkey: ".(!empty($this->config["sessionkey"])) ? $this->config["sessionkey"] : "",
            "authorization: Bearer ".$this->config["authorization"],
            "msgtype: USER_LOGIN_MSG",
            "Host: owa.momo.vn",
            "user_id: ".$this->config["phone"],
            "User-Agent: okhttp/3.14.17",
            "app_version: ".$this->momo_data_config["appVer"],
            "app_code: ".$this->momo_data_config["appCode"],
            "device_os: ANDROID"
        );
        $Data = array (
            'user' => $this->config['phone'],
            'msgType' => 'USER_LOGIN_MSG',
            'pass' => $this->config['password'],
            'cmdId' => (string) $microtime.'000000',
            'lang' => 'vi',
            'time' => $microtime,
            'channel' => 'APP',
            'appVer' => $this->momo_data_config["appVer"],
            'appCode' => $this->momo_data_config["appCode"],
            'deviceOS' => 'ANDROID',
            'buildNumber' => 0,
            'appId' => 'vn.momo.platform',
            'result' => true,
            'errorCode' => 0,
            'errorDesc' => '',
            'momoMsg' => 
            array (
              '_class' => 'mservice.backend.entity.msg.LoginMsg',
              'isSetup' => false,
            ),
            'extra' => 
            array (
              'pHash' => $this->get_pHash(),
              'AAID' => $this->config['AAID'],
              'IDFA' => '',
              'TOKEN' => $this->config['TOKEN'],
              'SIMULATOR' => '',
              'SECUREID' => $this->config['SECUREID'],
              'MODELID' => $this->config['MODELID'],
              'checkSum' => $this->generateCheckSum('USER_LOGIN_MSG', $microtime),
            ),
          );
          return $this->CURL("USER_LOGIN_MSG",$header,$Data);
    }
    public function GENERATE_TOKEN_AUTH_MSG()
    {
        $microtime = $this->get_microtime();
        $header = array(
            "agent_id: ".$this->config["agent_id"],
            "user_phone: ".$this->config["phone"],
            "sessionkey: ".(!empty($this->config["sessionkey"])) ? $this->config["sessionkey"] : "",
            "authorization: Bearer ".$this->config["authorization"],
            "msgtype: GENERATE_TOKEN_AUTH_MSG",
            "Host: api.momo.vn",
            "user_id: ".$this->config["phone"],
            "User-Agent: MoMoPlatform-Release/31062 CFNetwork/1325.0.1 Darwin/21.1.0",
            "app_version: ".$this->momo_data_config["appVer"],
            "app_code: ".$this->momo_data_config["appCode"],
            "device_os: ANDROID"
        );
        $Data = array (
            'user' => $this->config['phone'],
            'msgType' => 'GENERATE_TOKEN_AUTH_MSG',
            'cmdId' => (string) $microtime.'000000',
            'lang' => 'vi',
            'time' => $microtime,
            'channel' => 'APP',
            'appVer' => $this->momo_data_config["appVer"],
            'appCode' => $this->momo_data_config["appCode"],
            'deviceOS' => 'ANDROID',
            'buildNumber' => 0,
            'appId' => 'vn.momo.platform',
            'result' => true,
            'errorCode' => 0,
            'errorDesc' => '',
            'momoMsg' => 
            array (
              '_class' => 'mservice.backend.entity.msg.RefreshTokenMsg',
              'refreshToken' => $this->config["refreshToken"],
            ),
            'extra' => 
            array (
              'pHash' => $this->get_pHash(),
              'AAID' => $this->config['AAID'],
              'IDFA' => '',
              'TOKEN' => $this->config['TOKEN'],
              'SIMULATOR' => '',
              'SECUREID' => $this->config['SECUREID'],
              'MODELID' => $this->config['MODELID'],
              'checkSum' => $this->generateCheckSum('GENERATE_TOKEN_AUTH_MSG', $microtime),
            ),
          );
          return $this->CURL("GENERATE_TOKEN_AUTH_MSG",$header,$Data);
    }
    public function CHECK_USER_BE_MSG()
    {
        $microtime = $this->get_microtime();
        $header = array(
            "agent_id: undefined",
            "sessionkey:",
            "user_phone: undefined",
            "authorization: Bearer undefined",
            "msgtype: CHECK_USER_BE_MSG",
            "Host: api.momo.vn",
            "User-Agent: okhttp/3.14.17",
            "app_version: ".$this->momo_data_config["appVer"],
            "app_code: ",
            "device_os: ANDROID"
        );

        $Data = array (
            'user' => $this->config['phone'],
            'msgType' => 'CHECK_USER_BE_MSG',
            'cmdId' => (string) $microtime. '000000',
            'lang' => 'vi',
            'time' => $microtime,
            'channel' => 'APP',
            'appVer' => $this->momo_data_config["appVer"],
            'appCode' => $this->momo_data_config["appCode"],
            'deviceOS' => 'ANDROID',
            'buildNumber' => 0,
            'appId' => 'vn.momo.platform',
            'result' => true,
            'errorCode' => 0,
            'errorDesc' => '',
            'momoMsg' => 
            array (
              '_class' => 'mservice.backend.entity.msg.RegDeviceMsg',
              'number' => $this->config['phone'],
              'imei' => $this->config["imei"],
              'cname' => 'Vietnam',
              'ccode' => '084',
              'device' => $this->config["device"],
              'firmware' => '23',
              'hardware' => $this->config["hardware"],
              'manufacture' => $this->config["facture"],
              'csp' => 'Viettel',
              'icc' => '',
              'mcc' => '452',
              'device_os' => 'Android',
              'secure_id' => $this->config["SECUREID"],
            ),
            'extra' => 
            array (
              'checkSum' => '',
            ),
        );
        return $this->CURL("CHECK_USER_BE_MSG",$header,$Data);

    }

    public function REG_DEVICE_MSG()
    {
        $microtime = $this->get_microtime();
        $header = array(
            "agent_id: undefined",
            "sessionkey:",
            "user_phone: undefined",
            "authorization: Bearer undefined",
            "msgtype: REG_DEVICE_MSG",
            "Host: api.momo.vn",
            "User-Agent: okhttp/3.14.17",
            "app_version: ".$this->momo_data_config["appVer"],
            "app_code: ".$this->momo_data_config["appCode"],
            "device_os: ANDROID"
        );
        $Data = '{
            "user": "'.$this->config["phone"].'",
            "msgType": "REG_DEVICE_MSG",
            "cmdId": "'.$microtime.'000000",
            "lang": "vi",
            "time": '.$microtime.',
            "channel": "APP",
            "appVer": '.$this->momo_data_config["appVer"].',
            "appCode": "'.$this->momo_data_config["appCode"].'",
            "deviceOS": "ANDROID",
            "buildNumber": 0,
            "appId": "vn.momo.platform",
            "result": true,
            "errorCode": 0,
            "errorDesc": "",
            "momoMsg": {
              "_class": "mservice.backend.entity.msg.RegDeviceMsg",
              "number": "'.$this->config["phone"].'",
              "imei": "'.$this->config["imei"].'",
              "cname": "Vietnam",
              "ccode": "084",
              "device": "'.$this->config["device"].'",
              "firmware": "23",
              "hardware": "'.$this->config["hardware"].'",
              "manufacture": "'.$this->config["facture"].'",
              "csp": "",
              "icc": "",
              "mcc": "",
              "device_os": "Android",
              "secure_id": "'.$this->config["SECUREID"].'"
            },
            "extra": {
              "ohash": "'.$this->config['ohash'].'",
              "AAID": "'.$this->config["AAID"].'",
              "IDFA": "",
              "TOKEN": "'.$this->config["TOKEN"].'",
              "SIMULATOR": "",
              "SECUREID": "'.$this->config["SECUREID"].'",
              "MODELID": "'.$this->config["MODELID"].'",
              "checkSum": ""
            }
          }';
          return $this->CURL("REG_DEVICE_MSG",$header,$Data);

    }

    public function SEND_OTP_MSG()
    {
        $header = array(
            "agent_id: undefined",
            "sessionkey:",
            "user_phone: undefined",
            "authorization: Bearer undefined",
            "msgtype: SEND_OTP_MSG",
            "Host: api.momo.vn",
            "User-Agent: okhttp/3.14.17",
            "app_version: ".$this->momo_data_config["appVer"],
            "app_code: ".$this->momo_data_config["appCode"],
            "device_os: ANDROID"
        );
        $microtime = $this->get_microtime();
        $Data = array (
            'user' => $this->config['phone'],
            'msgType' => 'SEND_OTP_MSG',
            'cmdId' => (string) $microtime. '000000',
            'lang' => 'vi',
            'time' => $microtime,
            'channel' => 'APP',
            'appVer' => $this->momo_data_config["appVer"],
            'appCode' => $this->momo_data_config["appCode"],
            'deviceOS' => 'ANDROID',
            'buildNumber' => 0,
            'appId' => 'vn.momo.platform',
            'result' => true,
            'errorCode' => 0,
            'errorDesc' => '',
            'momoMsg' => 
            array (
              '_class' => 'mservice.backend.entity.msg.RegDeviceMsg',
              'number' => $this->config['phone'],
              'imei' => $this->config["imei"],
              'cname' => 'Vietnam',
              'ccode' => '084',
              'device' => $this->config["device"],
              'firmware' => '23',
              'hardware' => $this->config["hardware"],
              'manufacture' => $this->config["facture"],
              'csp' => '',
              'icc' => '',
              'mcc' => '452',
              'device_os' => 'Android',
              'secure_id' => $this->config['SECUREID'],
            ),
            'extra' => 
            array (
              'action' => 'SEND',
              'rkey' => $this->config["rkey"],
              'AAID' => $this->config["AAID"],
              'IDFA' => '',
              'TOKEN' => $this->config["TOKEN"],
              'SIMULATOR' => '',
              'SECUREID' => $this->config['SECUREID'],
              'MODELID' => $this->config["MODELID"],
              'isVoice' => false,
              'REQUIRE_HASH_STRING_OTP' => true,
              'checkSum' => '',
            ),
        );
        return $this->CURL("SEND_OTP_MSG",$header,$Data);

    }

    private function get_ip_address()
    {
        $isValid = filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP,FILTER_FLAG_IPV4);
        if(!empty($isValid)){
            return $_SERVER['REMOTE_ADDR'];
        }
        try {
            $isIpv4 = json_decode(file_get_contents('https://api.ipify.org?format=json'), true);
            return $isIpv4['ip'];
        } catch (\Throwable $e){
            return '116.107.187.109';
        }
    }

    private function get_TOKEN()
    {
        return  $this->generateRandom(22).':'.$this->generateRandom(9).'-'.$this->generateRandom(20).'-'.$this->generateRandom(12).'-'.$this->generateRandom(7).'-'.$this->generateRandom(7).'-'.$this->generateRandom(53).'-'.$this->generateRandom(9).'_'.$this->generateRandom(11).'-'.$this->generateRandom(4);
    }

    private function CURL_proxy($Action, $header, $data){
        $Data = is_array($data) ? json_encode($data) : $data;
        $DataPost = http_build_query(array(
            'url' => $Action,
            'data_post' => $Data,
            'header'    => json_encode($header)
        ));
        $curl = curl_init();
        curl_setopt_array($curl,array(
            CURLOPT_URL => 'https://curl.dowu.app',
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_POST => TRUE,
            CURLOPT_POSTFIELDS => $DataPost
        ));

        return json_decode($this->Decrypt_data(curl_exec($curl)),true);
    }

    private function CURL($Action,$header,$data)
    {
        $Data = is_array($data) ? json_encode($data) : $data;
        $curl = curl_init();
        // echo strlen($Data); die;
        $header[] = 'Content-Type: application/json';
        $header[] = 'accept: application/json';
        $header[] = 'Content-Length: '.strlen($Data);
        $opt = array(
            CURLOPT_URL =>$this->URLAction[$Action],
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_POST => empty($data) ? FALSE : TRUE,
            CURLOPT_POSTFIELDS => $Data,
            CURLOPT_CUSTOMREQUEST => empty($data) ? 'GET' : 'POST',
            CURLOPT_HTTPHEADER => $header,
            CURLOPT_ENCODING => "",
            CURLOPT_HEADER => FALSE,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_TIMEOUT => 20,
        );
        curl_setopt_array($curl,$opt);
        $body = curl_exec($curl);
        // echo strlen($body); die;
        if(is_object(json_decode($body))){
            return json_decode($body,true);
        }
        return json_decode($this->Decrypt_data($body),true);
    }

    public function RSA_Encrypt($key,$content)
    {
        if(empty($this->rsa)){
            $this->INCLUDE_RSA($key);
        }
        return base64_encode($this->rsa->encrypt($content));
    }

    private function INCLUDE_RSA($key)
    {
        require_once (realpath($_SERVER["DOCUMENT_ROOT"]) .'/controller/momo/lib/RSA/Crypt/RSA.php');
        $this->rsa = new Crypt_RSA();
        $this->rsa->loadKey($key);
        $this->rsa->setEncryptionMode(CRYPT_RSA_ENCRYPTION_PKCS1);
        return $this;
    }

    public function Encrypt_data($data,$key)
    {

     $iv = pack('C*', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
     $this->keys = $key;
     return base64_encode(openssl_encrypt(is_array($data) ? json_encode($data) : $data, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv));

    }

    public function Decrypt_data($data)
    {

      $iv = pack('C*', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
      return openssl_decrypt(base64_decode($data), 'AES-256-CBC', $this->keys, OPENSSL_RAW_DATA, $iv);

    }

    public function generateCheckSum($type,$microtime)
    {
     $Encrypt =   $this->config["phone"].$microtime.'000000'.$type. ($microtime / 1000000000000.0) . 'E12';
     $iv = pack('C*', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
     return base64_encode(openssl_encrypt($Encrypt, 'AES-256-CBC',$this->config["setupKeyDecrypt"], OPENSSL_RAW_DATA, $iv));
    }

    private function get_pHash()
    {
        $data = $this->config["imei"]."|".$this->config["password"];
        $iv = pack('C*', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
        return base64_encode(openssl_encrypt($data, 'AES-256-CBC',$this->config["setupKeyDecrypt"], OPENSSL_RAW_DATA, $iv));
    }

    public function get_setupKey($setUpKey)
    {
      $iv = pack('C*', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
      return openssl_decrypt(base64_decode($setUpKey), 'AES-256-CBC',$this->config["ohash"], OPENSSL_RAW_DATA, $iv);
    }

    private function generateRandom($length = 20)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    private function get_SECUREID($length = 17)
    {
            $characters = '0123456789abcdef';
            $charactersLength = strlen($characters);
            $randomString = '';
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
            return $randomString;
    }

    public function generateImei()
    {
          return $this->generateRandomString(8) . '-' . $this->generateRandomString(4) . '-' . $this->generateRandomString(4) . '-' . $this->generateRandomString(4) . '-' . $this->generateRandomString(12);
    }

    private function generateRandomString($length = 20)
    {
       $characters = '0123456789abcdef';
       $charactersLength = strlen($characters);
       $randomString = '';
       for ($i = 0; $i < $length; $i++) {
          $randomString .= $characters[rand(0, $charactersLength - 1)];
       }
      return $randomString;
    }

    private function get_string($data){
        return str_replace(array('<',"'",'>','?','/',"\\",'--','eval(','<php','-'),array('','','','','','','','','',''),htmlspecialchars(addslashes(strip_tags($data))));
    }

    public function get_microtime()
    {
     return round(microtime(true) * 1000);
    }
}

?>
