<?php
//goi thu vien
    include 'PHPMailer/class.smtp.php';
    include "PHPMailer/class.phpmailer.php"; 

function sendMail($title, $subject, $content, $nTo, $mTo){
    if (sendMail_1($title, $subject, $content, $nTo, $mTo) == 1){
        return 1;
    }elseif(sendMail_2($title, $subject, $content, $nTo, $mTo) == 1){
        return 1;
    }elseif(sendMail_3($title, $subject, $content, $nTo, $mTo) == 1){
        return 1;
    }else{return 0;}
    return 0;
}
function sendMail_1($title, $subject, $content, $nTo, $mTo){
    $nFrom = $title;
    $mFrom = 'contact@tungduy.co';  //dia chi email cua ban 
    $mPass = 'Vuduy25062004@@';       //mat khau email cua ban
    $mail             = new PHPMailer(true);
    try {
    $body             = $content;
    $mail->IsSMTP(); 
    $mail->Host       = "mailer-0104.inet.vn";   
    $mail->CharSet   = "utf-8";
    $mail->SMTPDebug  = 0;                     // enables SMTP debug information (for testing)
    $mail->SMTPAuth   = true;                    // enable SMTP authentication
    $mail->SMTPSecure = 'ssl'; 
    $mail->Port = 465;
    $mail->Username   = $mFrom;  // GMAIL username
    $mail->Password   = $mPass;               // GMAIL password
    $mail->SetFrom($mFrom, $nFrom);
    $mail->addAddress($mTo, $nTo);     // Add a recipient
    $mail->addAddress($mTo);               // Name is optional
    $mail->addReplyTo('noti@tungduy.co', $bcc);
    $mail->addCC('noti@tungduy.co');
    $mail->addBCC('noti@tungduy.co');
    //Content
    $mail->isHTML(true);  
    $mail->Subject = $subject;
    $mail->Body    = $body;
    $mail->AltBody = $body;
    $mail->send();
    if(!$mail->Send()) {
        return 0;
    } else {
        return 1;
    }
    } catch (Exception $e) {
    //return 'Message could not be sent. Mailer Error: '.$mail->ErrorInfo;
    return 0;
    }
    
}
function sendMail_2($title, $subject, $content, $nTo, $mTo){
    $nFrom = $title;
    $mFrom = 'info@tungduy.co';  //dia chi email cua ban 
    $mPass = 'Vuduy25062004@@';       //mat khau email cua ban
    $mail             = new PHPMailer(true);
    try {
    $body             = $content;
    $mail->IsSMTP(); 
    $mail->Host       = "mailer-0104.inet.vn";   
    $mail->CharSet   = "utf-8";
    $mail->SMTPDebug  = 0;                     // enables SMTP debug information (for testing)
    $mail->SMTPAuth   = true;                    // enable SMTP authentication
    $mail->SMTPSecure = 'ssl'; 
    $mail->Port = 465;
    $mail->Username   = $mFrom;  // GMAIL username
    $mail->Password   = $mPass;               // GMAIL password
    $mail->SetFrom($mFrom, $nFrom);
    $mail->addAddress($mTo, $nTo);     // Add a recipient
    $mail->addAddress($mTo);               // Name is optional
    $mail->addReplyTo('noti@tungduy.co', $bcc);
    $mail->addCC('noti@tungduy.co');
    $mail->addBCC('noti@tungduy.co');
    //Content
    $mail->isHTML(true);  
    $mail->Subject = $subject;
    $mail->Body    = $body;
    $mail->AltBody = $body;
    $mail->send();
    if(!$mail->Send()) {
        return 0;
    } else {
        return 1;
    }
    } catch (Exception $e) {
    //return 'Message could not be sent. Mailer Error: '.$mail->ErrorInfo;
    return 0;
    }
    
}
function sendMail_3($title, $subject, $content, $nTo, $mTo){
    $nFrom = $title;
    $mFrom = 'noti@tungduy.co';  //dia chi email cua ban 
    $mPass = 'Vuduy25062004@@';       //mat khau email cua ban
    $mail             = new PHPMailer(true);
    try {
    $body             = $content;
    $mail->IsSMTP(); 
    $mail->Host       = "mailer-0104.inet.vn";   
    $mail->CharSet   = "utf-8";
    $mail->SMTPDebug  = 0;                     // enables SMTP debug information (for testing)
    $mail->SMTPAuth   = true;                    // enable SMTP authentication
    $mail->SMTPSecure = 'ssl'; 
    $mail->Port = 465;
    $mail->Username   = $mFrom;  // GMAIL username
    $mail->Password   = $mPass;               // GMAIL password
    $mail->SetFrom($mFrom, $nFrom);
    $mail->addAddress($mTo, $nTo);     // Add a recipient
    $mail->addAddress($mTo);               // Name is optional
    $mail->addReplyTo('noti@tungduy.co', $bcc);
    $mail->addCC('noti@tungduy.co');
    $mail->addBCC('noti@tungduy.co');
    //Content
    $mail->isHTML(true);  
    $mail->Subject = $subject;
    $mail->Body    = $body;
    $mail->AltBody = $body;
    $mail->send();
    if(!$mail->Send()) {
        return 0;
    } else {
        return 1;
    }
    } catch (Exception $e) {
    //return 'Message could not be sent. Mailer Error: '.$mail->ErrorInfo;
    return 0;
    }
    
}



?>