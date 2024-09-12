<?php
/*
  $Id: login.php,v 1.17 2005/11/01 12:57:29 dgw_ Exp $   
   ============================================  
   DIGISTORE FREE ECOMMERCE OPEN SOURCE VER 3.2  
   ============================================
      
   (c)2005-2006
   The Digistore Developing Team NZ   
   http://www.digistore.co.nz                       
                                                                                           
   SUPPORT & PROJECT UPDATES:                                  
   http://www.digistore.co.nz/support/
   
   Portions Copyright (c) 2003 osCommerce
   http://www.oscommerce.com   
   
   This software is released under the
   GNU General Public License. A copy of
   the license is bundled with this
   package.   
   
   No warranty is provided on the open
   source version of this software.
   
   ========================================
*/
//print_r($_SESSION);
 $login_request = true;
  require('includes/application_top.php');
require(DIR_WS_FUNCTIONS . 'password_funcs.php');

  if (isset($HTTP_GET_VARS['action']) && ($HTTP_GET_VARS['action'] == 'process')) {
    $email_address = tep_db_prepare_input($HTTP_POST_VARS['email_address']);
    $password = tep_db_prepare_input($HTTP_POST_VARS['password']);

// Check if email exists
    $check_admin_query = tep_db_query("select admin_id as login_id, admin_groups_id as login_groups_id, admin_firstname as login_firstname, admin_email_address as login_email_address, admin_password as login_password, admin_modified as login_modified, admin_logdate as login_logdate, admin_lognum as login_lognum from " . TABLE_ADMIN . " where admin_email_address = '" . tep_db_input($email_address) . "'");
    if (!tep_db_num_rows($check_admin_query)) {
      $HTTP_GET_VARS['login'] = 'fail';
    } else {
      $check_admin = tep_db_fetch_array($check_admin_query);
      // Check that password is good
      if (!tep_validate_password($password, $check_admin['login_password'])) {
        $HTTP_GET_VARS['login'] = 'fail';
      } else {
        if (tep_session_is_registered('password_forgotten')) {
          tep_session_unregister('password_forgotten');
        }

        $login_id = $check_admin['login_id'];
        $login_groups_id = $check_admin['login_groups_id'];
        $login_firstname = $check_admin['login_firstname'];
        $login_email_address = $check_admin['login_email_address'];
        $login_logdate = $check_admin['login_logdate'];
        $login_lognum = $check_admin['login_lognum'];
        $login_modified = $check_admin['login_modified'];

        tep_session_register('login_id');
        tep_session_register('login_groups_id');
        tep_session_register('login_first_name');
        
        //$date_now = date('Ymd');
        tep_db_query("update " . TABLE_ADMIN . " set admin_logdate = now(), admin_lognum = admin_lognum+1 where admin_id = '" . $login_id . "'");
          
        // update session with username
        $value_query = tep_db_query("select * from sessions where sesskey = '" . $_COOKIE['osCAdminID']."'");
        $value = tep_db_fetch_array($value_query);
         if(tep_db_num_rows($value_query) > 0){
            tep_db_query("update sessions SET user = '".$login_id."' where sesskey = '".$_COOKIE['osCAdminID']."'"); 
         } 
  
        if($value['user'] <> ''){
            $admin_query =  tep_db_query("select admin_firstname from admin where admin_id = '".$value['user']."' ");
            $admin = tep_db_fetch_array ($admin_query);
            $username = $admin['admin_firstname'];
            
            $style1 = '';
            $style2 = 'style="display:none;"';
        } else {
            $style1 = 'style="display:none;"';
            $style2 = '';
            $username = '';
        }

 //if (($login_lognum == 0) || !($login_logdate) || ($login_email_address == 'admin@localhost') || ($login_modified == '0000-00-00 00:00:00')) {
          //tep_redirect(tep_href_link("login.php"));
        //} else {
          tep_redirect(tep_href_link(FILENAME_DEFAULT));
        //} 

      }
    }
  }

$values_query = tep_db_query("select * from sessions where sesskey = '" . $_COOKIE['osCAdminID']."'");
$values = tep_db_fetch_array($values_query);



if($values['user'] <> ''){
    $admin_query =  tep_db_query("select admin_firstname, admin_email_address as email from admin where admin_id = '".$values['user']."'");
    $admin = tep_db_fetch_array ($admin_query);
    $username = $admin['admin_firstname'];
    $user_email = $admin['email'];
            
    $style1 = 'style="text-align:center;"';
    $style2 = 'style="display:none;"';
} else {
    $style1 = 'style="display:none;"';
    $style2 = 'style="margin-bottom:30px;"';
    $username = '';
}

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_LOGIN);
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<link rel="stylesheet" type="text/css" href="includes/bootstrap-header.css">
    <script language="javascript" src="includes/general.js"></script>
    <script src="ext/jquery/jquery.js"></script>
<style type="text/css">

body {background-size: 100%; font-family: cursive; font-size:1rem;}
h1{font-size: 2.5rem;}     
td{font-family:Verdana;font-size:11px;color:#333333;}
    
.admin-login{padding:30px; min-height: 330px;}
.admin-logo input{background-color: rgba(255, 255, 255, 0.38);}
.btn {display: inline-block; font-weight: 400; color: #fff; text-align: center; vertical-align: middle; -webkit-user-select: none; -moz-user-select: none; -ms-user-select: none; user-select: none; background-color: rgb(4, 136, 171); border: 1px solid transparent; padding: .375rem .75rem; font-size: 1rem; line-height: 1.5; border-radius: .25rem; transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;
}
.cap{font-family:Verdana;font-weight:bold;font-size:11px;text-decoration:none;}    
.form-control { font-weight: 400; transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
}   
.input-group{display: -ms-flexbox; display: flex; -ms-flex-wrap: wrap; flex-wrap: wrap; -ms-flex-align: stretch; align-items: stretch; width: 100%;}
.input-group>.input-group-append>.btn{ border-top-left-radius: 0; border-bottom-left-radius: 0;}
.input-group-append .btn, .input-group-prepend .btn {position: relative; z-index: 2;}    
.input-group-append {margin-left: -1px;}
.input-group-append { display: -ms-flexbox; display: flex;}   
.input-group>.form-control{ position: relative; -ms-flex: 1 1 auto; flex: 1 1 auto; width: 1%; margin-bottom: 0;}    
.input-group .form-control{border-top-left-radius: 0.25rem !important; border-bottom-left-radius:0.25rem !important;}    
        
.login { font-family: Verdana, Arial, sans-serif; font-size: 12px; color: #000000;}
.login_heading { font-family: Verdana, Arial, sans-serif; font-size: 12px; color: #ffffff;}        
.login-error{width:200px; margin:10px auto 0px; color:#F00;}
.smallText { font-family: Verdana, Arial, sans-serif; font-size: 10px; }    
.sub { font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 10px; line-height: 1.5; color: #666666; }
.text { font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px; font-weight: bold; color: #000000; }
.textinput { font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; font-weight: color: #666666; }
    
#admin-log-container {margin: 0px auto; width: 320px; margin-top:15%; border: 5px dashed #fff; border-radius:10px; color:#fff; background-color: rgba(2, 55, 101, 0.78);
}    
 
#buttonup{margin:0;}
#switch{color:#fff;}
#switch:hover{color:#09f; text-decoration: underline; cursor: pointer;}
#users-select{display:none;}

</style>
</head>
<body style="background-image:url(css/pirateflag.jpg);">
<?php echo tep_draw_form('login', FILENAME_LOGIN, 'action=process'); ?> 
<div id="admin-log-container">

<div class="loginimage" style="display:none;"><img src="images/login-logo.jpg"></div>

<div class="login-error">                                             <?php
if ($HTTP_GET_VARS['login'] == 'fail') {
$info_message = TEXT_LOGIN_ERROR;
}
if ($HTTP_GET_VARS['action'] == 'logoff'){
$info_message = TEXT_LOGOFF;
}
if (isset($info_message)) {
?>

<?php echo $info_message; ?>

<?php
} else {
?>      
<?php  } ?> <p></p>  </div>
 <div class="admin-login">

<div id="user-heading" <?php echo $style1; ?>>
    <h1><?php echo $username; ?></h2>
</div>
         
<div class="form-group" <?php echo $style2; ?>>
    <label>Username</label>
    <input id="email_field" class="form-control" type="text" name="email_address" autocomplete="new-password" value="<?php echo $user_email; ?>">
</div>
     
<div class="form-group input-group">
    <label style="display: none;">Password</label>
    <input id="passfld" class="form-control" readonly type="text" name="password" maxlength="40" placeholder="Enter Password" autocomplete="new-password"><div class="input-group-append">
    <input class="btn btn-outline-secondary" id="buttonup" type="submit" value=">"></div>
     </div>
<div style="text-align:center; margin-top:45px; display:none;">
    <input id="buttonup" class="form-control"  type="submit" name="Submit" value="Login">
</div>
     
<div class="form-group" style="text-align:center; font-family:auto; margin-top:30px;"><a id="switch" style="color:#fff;">Switch User</a></div>
     
     <div class="form-group" id="users-select">
         <select class="form-control" name="users-switch" id="switcheroo">
            <option>Select User</option>
            <?php $get_users_query = tep_db_query("select admin_id, admin_firstname, admin_email_address from admin where admin_id NOT IN (4,15,43,46,47,50,51) and deleted_at is null ORDER BY admin_firstname ASC");
            while($get_users = tep_db_fetch_array($get_users_query)){
              if($get_users['admin_firstname'] == 'David'){
                echo '<option value="'.$get_users['admin_email_address'].'">Accounting</option>';
              }else{
                echo '<option value="'.$get_users['admin_email_address'].'">'.$get_users['admin_firstname'].'</option>';
              } 
            }
            ?>
          </select>
     </div>      
     
     
          
<input type="hidden" id="email_check" value="0">  
<input type="hidden" id="pw_check" value="0">
<input type="hidden" id="key-counter" value="0">     
<input type="hidden" id="counter" value="0">

</div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>    
<script>
$('#passfld').val('');    
    
$('#passfld').focus();
    
$('#passfld').on("touchstart click",function(){
  $('#passfld').removeAttr('readOnly');
  $('#passfld').removeAttr('type');
  $('#passfld').attr('type','password');
})

$('#switch').on("click",function(){
   $('#users-select').toggle(); 
})
    
$('#switcheroo').on('change', function(){
    var texts = $('#switcheroo option:selected').text();
    $('#email_field').val(this.value);
    $('#users-select').hide();
    $('#email_field').hide();
    $('#user-heading h1').html(texts);
    $('#user-heading').show();
    $('#user-heading').css("text-align", 'center');
    
})    
    
var placeholder = $("#passfld").val();
$("#passfld").keydown(function() {
    if (this.value == placeholder) {
        this.value = '';
    }
}).blur(function() {
    if (this.value == '') {
        this.value = placeholder;
    }
});

var counter = 0;   
$("#passfld").keypress(function() {
    if($(this).val().length > 4) {
        $('#email_check').val('1');
        $('#pw_check').val('1');
        $("#key-counter").val();
    }
    counter++;
    $("#key-counter").val(counter);
})
    
var input = $('#passfld');
  input.on('keydown', function() {
    var key = event.keyCode || event.charCode;

    if( key == 8 || key == 46 )
        $('#passfld').val('');
  });    
  
    
function validateMyForm(){    
    var email = $('#email_check').val();
    var pswd = $('#pw_check').val();
    var num = +$("#counter").val() + 1;
    var value = $('#counter').val();
    var keys = $('#key-counter').val();
    if(email < 1){
      $('#email_field').val('');
      $('#passfld').val('');
      $('#email_check').val('0');
      $('#pw_check').val('0');
      $("#counter").val(num);
        if(value > 1){
        alert('Try typing in the password manually');
    }
        return false;
    }
    if(pswd < 1){
        $('#email_field').val('');
        $('#passfld').val('');
        $('#email_check').val('0');
        $('#pw_check').val('0');
        $("#counter").val(num);
        if(value > 1){
        alert('Try typing in the password manually');
        return false;    
        }
    }
    if(keys < 4){
       $('#email_field').val('');
        $('#passfld').val('');
        $('#email_check').val('0');
        $('#pw_check').val('0');
        $("#counter").val(num);    
        return false;
    
    } else {
        submitForm();
    }  
}
</script>
    

</body>

</html>
