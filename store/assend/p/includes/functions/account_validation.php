<?php
 $captcha=$_POST['g-recaptcha-response'];
 if(!empty($captcha))
 {
 $errMsg= '';
 $google_url="https://www.google.com/recaptcha/api/siteverify";
 $secret= '6LcfLg4TAAAAADiuwD12x27DXiVjPgB28BRLH_7W';
 $ip=$_SERVER['REMOTE_ADDR'];
 $captchaurl=$google_url."?secret=".$secret."&response=".$captcha."&remoteip=".$ip;
 
 $curl_init = curl_init();
 curl_setopt($curl_init, CURLOPT_URL, $captchaurl);
 curl_setopt($curl_init, CURLOPT_RETURNTRANSFER, 1);
 curl_setopt($curl_init, CURLOPT_TIMEOUT, 10);
 $results = curl_exec($curl_init);
 curl_close($curl_init);
 
 $results= json_decode($results, true);
 if($results['success']){
 $errMsg="Valid reCAPTCHA code. You are human.";
 }else{
$entry_antirobotreg_error = true;
$text_antirobotreg_error = ERROR_VALIDATION_1;
 }
 }else{
 $errMsg="Please re-enter your reCAPTCHA.";
 }
?>