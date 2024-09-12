<?php
$login_request = true;

 require('../includes/application_top.php');
  require('../includes/functions/password_funcs.php');

  $action = (isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : '');

  if (tep_not_null($action)) {
    switch ($action) {
      case 'process':
        $username = tep_db_prepare_input($HTTP_POST_VARS['username']);
        $password = tep_db_prepare_input($HTTP_POST_VARS['password']);

        $check_query = tep_db_query("select id, user_name, user_password from " . TABLE_ADMINISTRATORS . " where user_name = '" . tep_db_input($username) . "'");

        if (tep_db_num_rows($check_query) == 1) {
          $check = tep_db_fetch_array($check_query);

          if (tep_validate_password($password, $check['user_password'])) {
            tep_session_register('admin');

            $admin = array('id' => $check['id'],
                           'username' => $check['user_name']);

            if (tep_session_is_registered('redirect_origin')) {
              $page = $redirect_origin['page'];
              $get_string = '';

              if (function_exists('http_build_query')) {
                $get_string = http_build_query($redirect_origin['get']);
              }

              tep_session_unregister('redirect_origin');

              tep_redirect(tep_href_link($page, $get_string));
            } else {
              tep_redirect(tep_href_link(FILENAME_DEFAULT));
            }
          }
        }

        $messageStack->add(ERROR_INVALID_ADMINISTRATOR, 'error');

        break;

      case 'logoff':
        tep_session_unregister('selected_box');
        tep_session_unregister('admin');
        tep_redirect(tep_href_link(FILENAME_DEFAULT));

        break;

      case 'create':
        $check_query = tep_db_query("select id from " . TABLE_ADMINISTRATORS . " limit 1");

        if (tep_db_num_rows($check_query) == 0) {
          $username = tep_db_prepare_input($HTTP_POST_VARS['username']);
          $password = tep_db_prepare_input($HTTP_POST_VARS['password']);

          tep_db_query('insert into ' . TABLE_ADMINISTRATORS . ' (user_name, user_password) values ("' . $username . '", "' . tep_encrypt_password($password) . '")');
        }

        tep_redirect(tep_href_link(FILENAME_LOGIN));

        break;
    }
  }

  $languages = tep_get_languages();
  $languages_array = array();
  $languages_selected = DEFAULT_LANGUAGE;
  for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
    $languages_array[] = array('id' => $languages[$i]['code'],
                               'text' => $languages[$i]['name']);
    if ($languages[$i]['directory'] == $language) {
      $languages_selected = $languages[$i]['code'];
    }
  }

  $admins_check_query = tep_db_query("select id from " . TABLE_ADMINISTRATORS . " limit 1");
  if (tep_db_num_rows($admins_check_query) < 1) {
    $messageStack->add(TEXT_CREATE_FIRST_ADMINISTRATOR, 'warning');
  }


  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_LOGIN);

<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-language" content="en" />
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<meta name="apple-mobile-web-app-status-bar-style" content="black" />
	
	<link rel="apple-touch-icon" href="images/icon.png" />
	<link rel="apple-touch-startup-image" href="images/splash.png">
	
	<link rel="stylesheet" media="screen" type="text/css" href="css/reset.css" />	
	<link rel="stylesheet" media="screen" type="text/css" href="css/main.css" />
	
	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/main.js"></script>
	
	<title>Admin</title>
</head>

<body onorientationchange="Orientation();">

<div id="wrapper">

	<!-- HEADER -->
	<div id="header">
	
		<h1 id="logo">Log In</h1>
		
	</div> <!-- /header -->
	
	<!-- CONTENT -->
	<div id="content">
	
		<!-- LOGIN -->
		<div class="content-box nomb">		
		
			<form action="#" method="post">

				<dl>
					<dt>Username:</dt>
					<dd><input type="text" size="30" name="" class="input-text" /></dd>
					
					<dt>Password:</dt>
					<dd><input type="password" size="30" name="" class="input-text" /></dd>
				</dl>				
				
				<p class="nomb t-center"><input type="submit" value="Login" class="input-submit" /> or <a href="#" onclick="return link(this)">Send forgotten password</a></p>

			</form>
		
		</div> <!-- /content-box -->		
		
	</div> <!-- /content -->
		
	<!-- FOOTER -->
	<div id="footer">
	
		<p id="footer-button"><a onclick="jQuery('html,body').animate({scrollTop:0},'slow');" href="javascript:void(0);">Back on top</a></p>
	
		<p>&copy; 2011 <a href="http://www.motemplates.com/">MoTemplates.com</a></p>
		
	</div> <!-- /footer -->

</div> <!-- /wrapper -->

</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
