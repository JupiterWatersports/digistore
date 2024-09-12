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

  require('includes/application_top.php');

  

?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title><?php echo TITLE; ?></title>

<link rel="stylesheet" media="screen and (min-width: 0px)" href="mobile-admin.css">
<link rel="stylesheet" media="screen and (min-width: 1000px)" href="tablet-admin.css">
<link rel="stylesheet" media="screen and (min-width: 1400px)" href="admin.css">

<style type="text/css"><!--


.loginimage img{width:100%;}
.admin-login {margin-left:22%;}
.login-error{margin-left:42%;}
.sub { font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 10px; line-height: 1.5; color: #666666; }
.text { font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px; font-weight: bold; color: #000000; }
.textinput { font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; font-weight: color: #666666; }
.smallText { font-family: Verdana, Arial, sans-serif; font-size: 10px; }
.login_heading { font-family: Verdana, Arial, sans-serif; font-size: 12px; color: #ffffff;}
.login { font-family: Verdana, Arial, sans-serif; font-size: 12px; color: #000000;}
.cap{font-family:Verdana;font-weight:bold;font-size:11px;text-decoration:none;}
INPUT {
	BACKGROUND-COLOR: #eeeeee; COLOR: #333333; FONT: 12px Verdana, Geneva, Arial, Helvetica, sans-serif; MARGIN-BOTTOM: 2px; MARGIN-TOP: 2px; width: 200px;
height: 30px;
}
td{font-family:Verdana;font-size:11px;color:#333333;}

@media only screen 
and (max-device-width : 700px) {
/* Styles */
.loginimage img{width:100%;}
}


//--></style>
</head>
<body>
<div id="admin-log-container">
<?php echo tep_draw_form('login', FILENAME_LOGIN, 'action=process'); ?> 

                                     <div class="loginimage"><img src="images/login-logo.jpg"></div>
                                     
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
                            <td width="80" class="login"> <div align="right"></div></td>
                            <td width="303" class="login"><table width="100%" border="0" cellspacing="0" cellpadding="2" class="textinput">
                                <tr>
                                  <td width="24%"><div align="right">Username:</div></td>
                                  <td width="76%"><?php echo tep_draw_input_field('email_address'); ?></td>
                                </tr>
                                <tr>
                                  <td><div align="right">Password:</div></td>
                                  <td><?php echo tep_draw_password_field('password'); ?></td>
                                </tr>
                                <tr>
                                  <td>&nbsp;</td>
                                  <td><input type="submit" name="Submit" value="Continue"></td>
                                </tr>
                              </table></td>
                          </div>
</div>
</body>

</html>
