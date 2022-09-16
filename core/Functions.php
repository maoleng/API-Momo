<?php
function writeLog($message, $file_name) {
	$body = "\n";
	$body .= date("d/m/Y - H:i:s"). "\n";
	$body .= $message . "\n";
	$body .= '-----------------';

	$day = date("d_m_Y");
	$file = $file_name;
	$fp = fopen($file, 'a+');
	fwrite($fp, $body);
	fclose($fp);
}
function getFinishNgay($subday) {
    
    $ngay  = getStartNgay($subday)+86399;
    
    return $ngay;
}
function getStartNgay($subday) {
    
    $ngay  = strtotime(date('Y-m-d', strtotime('-'.$subday.' days', time())));
    return $ngay;
}


function getNgayDauThang($thang) {
    if($thang) {
        $ngaydaucuathang = strtotime(date('01-'.$thang.'-Y'));
    }else{
        $ngaydaucuathang = strtotime(date('01-m-Y'));
    }
    return $ngaydaucuathang;
}
function getNgayCuoiThang($thang) {
    if($thang) {
        $ngaycuoicuathang = strtotime('23:59:59 '.date(''.$day.'-'.$thang.'-Y'));
    }else{
        $ngaycuoicuathang = strtotime('23:59:59 '.date('t-m-Y'));
    }
    return $ngaycuoicuathang;
}
function base64UrlEncode($text)
{
    return str_replace(
        ['+', '/', '='],
        ['-', '_', ''],
        base64_encode($text)
    );
}

function createToken(){
$secret = "SWdw46oeBRnvrkFhX2AaPIuicm35Mq19gKEQzbJCVLYf";
$header = json_encode([
    'typ' => 'JWT',
    'alg' => 'HS256'
]);

$payload = json_encode([
    'user_id' => 1,
    'role' => 'admin',
    'exp' => time()
]);

$base64UrlHeader = base64UrlEncode($header);

$base64UrlPayload = base64UrlEncode($payload);

$signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $secret, true);

$base64UrlSignature = base64UrlEncode($signature);

$jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
return $jwt;
}
function random($string, $int)
{
    $chars = $string;  
    $data = substr(str_shuffle($chars), 0, $int);
    return $data;
}
function curl_post($url,$input){
        $curl = curl_init();
        curl_setopt($curl,CURLOPT_URL, $url);
        $headers = [
        'Content-Type: application/json',
        ];
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl,CURLOPT_USERAGENT, "DichVuApi.Xyz");
        curl_setopt($curl,CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl,CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl,CURLOPT_CONNECTTIMEOUT, 120);
        curl_setopt($curl,CURLOPT_TIMEOUT, 120);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($input));
        $exec = curl_exec($curl);
        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
        }
        curl_close($curl);
        if (!empty($error_msg)) {
            return false;
        }
        //$result = json_decode($exec, true);
        return $exec;
    }
function curl($url = null, $param = array(), $header = [], $userA = NULL)
{
    if (!empty($url)) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, $userA);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_DIGEST);
        curl_setopt($ch, CURLOPT_TIMEOUT, 120);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        if (count($param) > 0) {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($param));
        }
        if (curl_errno($ch)) {
            $res = "Lỗi: " . curl_error($ch);
        } else {
            $res = curl_exec($ch);
        }

        curl_close($ch);
        return $res;
    }
}        

function text($string, $separator = ', '){
    $vals = explode($separator, $string);
    foreach($vals as $key => $val) {
        $vals[$key] = strtolower(trim($val));
    }
    return array_diff($vals, array(""));
}
//anti_xss
function anti_xss($text) {
  return htmlspecialchars(htmlentities(strip_tags($text), ENT_QUOTES, 'UTF-8'));
}
// $_GET 
function GET($key) {
    return isset($_GET[$key]) ? ($_GET[$key]) : false; 
}
// $_POST
function POST($key) {
    return isset($_POST[$key]) ? ($_POST[$key]) : false; 
}

// gọn hơn :v
function load_url($url = "") {
    header('Location: '.$url.'');
    exit();
}
// xác nhận người dùng
function is_client() {
    $username = isset($_SESSION["user"]) ? $_SESSION["user"] : false;
    if ($username) {
        return true;
    }
    return false;
}

//bb code
function bbcode($text) {
   
    $find = array(
       
        '~\[b\](.*?)\[/b\]~s',

        '~\[p\](.*?)\[/p\]~s',
        
        '~\[i\](.*?)\[/i\]~s',
        
        '~\[u\](.*?)\[/u\]~s',
        
        '~\[quote\](.*?)\[/quote\]~s',
        
        '~\[size=(.*?)\](.*?)\[/size\]~s',
        
        '~\[color=(.*?)\](.*?)\[/color\]~s',
        
        '~\[url=(.*?)\](.*?)\[/url\]~s',
        
        '~\[img=(.*?)\](.*?)\[/img\]~s'
        
    );
    
    $replace = array(
       
        '<b>$1</b>',

        '<p>$1</p>',
        
        '<i>$1</i>',
        
        '<span style="text-decoration:underline;">$1</span>',
        
        '<pre>$1</'.'pre>',
        
        '<span style="font-size:$1px;">$2</span>',
        
        '<span style="color:$1;">$2</span>',
        
        '<a href="$1">$2</a>',
        
        '<img src="$1" alt="$2" />'
        
    );
    return preg_replace($find,$replace,$text);
}

function junoo_boc($data){
    $text = html_entity_decode(trim(strip_tags($data)), ENT_QUOTES, 'UTF-8');
    $text=str_replace(" ","", $text);
    $text=str_replace("/","",$text);
    $text=str_replace("{","",$text);
    $text=str_replace("}","",$text);
    $text=str_replace("\\","",$text);
    $text=str_replace(":","",$text);
    $text=str_replace("\"","",$text);
    $text=str_replace("'","",$text);
    $text=str_replace("<","",$text);
    $text=str_replace(">","",$text);
    $text=str_replace("?","",$text);
    $text=str_replace(";","",$text);
    $text=str_replace(",","",$text);
    $text=str_replace("[","",$text);
    $text=str_replace("]","",$text);
    $text=str_replace("(","",$text);
    $text=str_replace(")","",$text);
    $text=str_replace("*","",$text);
    $text=str_replace("!","",$text);
    $text=str_replace("$","",$text);
    $text=str_replace("&","",$text);
    $text=str_replace("%","",$text);
    $text=str_replace("#","",$text);
    $text=str_replace("^","",$text);
    $text=str_replace("=","",$text);
    $text=str_replace("+","",$text);
    $text=str_replace("~","",$text);
    $text=str_replace("`","",$text);
    $text=str_replace("-","",$text);
    $text=str_replace("|","",$text);
    $text = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", 'a', $text);
    $text = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", 'a', $text);

    $text = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", 'e', $text);

    $text = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", 'e', $text);

    $text = preg_replace("/(ì|í|ị|ỉ|ĩ)/", 'i', $text);
    $text = preg_replace("/(ì|í|ị|ỉ|ĩ)/", 'i', $text);

    $text = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", 'o', $text);
    $text = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", 'o', $text);

    $text = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", 'u', $text);
    $text = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", 'u', $text);

    $text = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", 'y', $text);
    $text = preg_replace("/(đ)/", 'd', $text);
    $text = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", 'y', $text);
    $text = preg_replace("/(đ)/", 'd', $text);

    $text = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", 'A', $text);
    $text = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", 'A', $text);

    $text = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", 'E', $text);
    $text = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", 'E', $text);

    $text = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", 'I', $text);
    $text = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", 'I', $text);

    $text = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", 'O', $text);
    $text = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", 'O', $text);

    $text = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", 'U', $text);
    $text = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", 'U', $text);

    $text = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", 'Y', $text);
    $text = preg_replace("/(Đ)/", 'D', $text);

    $text = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", 'Y', $text);
    $text = preg_replace("/(Đ)/", 'D', $text);

    $text=strtolower($text);
    return $text;
}

// bỏ dấu
function bodau2($strTitle)
{
   $strTitle=strtolower($strTitle);
   $strTitle=trim($strTitle);
   $strTitle=str_replace(' ','-',$strTitle);
   $strTitle=preg_replace("/(!|@|\$|%|\^|\*|\(|\)|\+|\=|\<|\>|\?|\/|,|\.|\:|\'| |\"|\&|\#|\[|\]|~)/",'-',$strTitle);
   $strTitle=preg_replace("/(ò|ó|ọ|ỏ|õ|ơ|ờ|ớ|ợ|ở|ỡ|ô|ồ|ố|ộ|ổ|ỗ)/",'o',$strTitle);
   $strTitle=preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ|Ô|Ố|Ổ|Ộ|Ồ|Ỗ)/",'o',$strTitle);
   $strTitle=preg_replace("/(à|á|ạ|ả|ã|ă|ằ|ắ|ặ|ẳ|ẵ|â|ầ|ấ|ậ|ẩ|ẫ)/",'a',$strTitle);
   $strTitle=preg_replace("/(À|Á|Ạ|Ả|Ã|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ|Â|Ấ|Ầ|Ậ|Ẩ|Ẫ)/",'a',$strTitle);
   $strTitle=preg_replace("/(ề|ế|ệ|ể|ê|ễ|é|è|ẻ|ẽ|ẹ)/",'e',$strTitle);
   $strTitle=preg_replace("/(Ể|Ế|Ệ|Ể|Ê|Ễ|É|È|Ẻ|Ẽ|Ẹ)/",'e',$strTitle);
   $strTitle=preg_replace("/(ừ|ứ|ự|ử|ư|ữ|ù|ú|ụ|ủ|ũ)/",'u',$strTitle);
   $strTitle=preg_replace("/(Ừ|Ứ|Ự|Ử|Ư|Ữ|Ù|Ú|Ụ|Ủ|Ũ)/",'u',$strTitle);
   $strTitle=preg_replace("/(ì|í|ị|ỉ|ĩ)/",'i',$strTitle);
   $strTitle=preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/",'i',$strTitle);
   $strTitle=preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/",'y',$strTitle);
   $strTitle=preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/",'y',$strTitle);
   $strTitle=preg_replace('/(đ|Đ)/','d',$strTitle);
   $strTitle=preg_replace("/(^\-+|\-+$)/",'',$strTitle);
   return $strTitle;
}

function bodau($str){
    $str = preg_replace('/[\s]+/mu', ' ', $str);
    $unicode = array(
       
        'a'=>'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',
        
        'd'=>'đ',
        
        'e'=>'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
        
        'i'=>'í|ì|ỉ|ĩ|ị',
        
        'o'=>'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
        
        'u'=>'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
        
        'y'=>'ý|ỳ|ỷ|ỹ|ỵ',
        
        'A'=>'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',
        
        'D'=>'Đ',
        
        'E'=>'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',
        
        'I'=>'Í|Ì|Ỉ|Ĩ|Ị',
        
        'O'=>'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',
        
        'U'=>'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',
        
        'Y'=>'Ý|Ỳ|Ỷ|Ỹ|Ỵ',
        
    );
    
    foreach($unicode as $nonUnicode=>$uni){
        $str = preg_replace("/($uni)/i", $nonUnicode, $str);
    }
    $str = str_replace(' ','-',$str);
    return $str;
    
}


function time_ago($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'năm',
        'm' => 'tháng',
        'w' => 'tuần',
        'd' => 'ngày',
        'h' => 'giờ',
        'i' => 'phút',
        's' => 'giây',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? '' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' trước' : 'Bây giờ';
}


function time_stamp($time){
    $periods = array("giây", "phút", "giờ", "ngày", "tuần", "tháng", "năm", "thập kỉ");
    $lengths = array("60","60","24","7","4.35","12","10");
    $now = time();
    $difference = $now - $time;
    $tense = "trước";
    for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
     $difference /= $lengths[$j];
 }
 $difference = round($difference);
 return "Cách đây $difference $periods[$j]";
}


// func time ago
function time_elapsed_string($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'năm',
        'm' => 'tháng',
        'w' => 'tuần',
        'd' => 'ngày',
        'h' => 'giờ',
        'i' => 'phút',
        's' => 'giây',
    );
    
    
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? '' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' trước' : 'vừa xong';
}
// time left

function time_left($from, $to = '') {
    if (empty($to))
        $to = time();
    $diff = (int) abs($to - $from);
    if ($diff <= 60) {
        $since = sprintf('Còn vài giây');
    } elseif ($diff <= 3600) {
        $mins = round($diff / 60);
        if ($mins <= 1) {
            $mins = 1;
        }
        /* translators: min=minute */
        $since = sprintf('Còn %s phút', $mins);
    } else if (($diff <= 86400) && ($diff > 3600)) {
        $hours = round($diff / 3600);
        if ($hours <= 1) {
            $hours = 1;
        }
        $since = sprintf('Còn %s giờ', $hours);
    } elseif ($diff >= 86400) {
        $days = round($diff / 86400);
        if ($days <= 1) {
            $days = 1;
        }
        $since = sprintf('Còn %s ngày', $days);
    }
    return $since;
}


function in_array_r($needle, $haystack, $strict = false) {
    foreach ($haystack as $item) {
        if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && in_array_r($needle, $item, $strict))) {
            return true;
        }
    }

    return false;
}

?>