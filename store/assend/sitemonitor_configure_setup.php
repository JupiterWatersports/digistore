<?php

/*

  $Id: sitemonitor_admin.php,v 1.2 2006/09/24 

  sitemonitor Originally Created by: Jack mcs

  Digistore v4.0,  Open Source E-Commerce Solutions

  http://www.digistore.co.nz



  Copyright (c) 2003 osCommerce, http://www.oscommerce.com



  Released under the GNU General Public License

*/

 

  require('includes/application_top.php');

  require('includes/functions/sitemonitor_functions.php');



  $filenameConfigure = DIR_FS_ADMIN . FILENAME_SITEMONITOR_CONFIGURE;

  $fp = @file($filenameConfigure);

     

  /*********** LOAD THE CONFIGURE SETTINGS **********/

  $switch = array(); 

  for ($i = 0; $i < count($fp); ++$i)

  {

    if (strpos($fp[$i], "\$always_email") !== FALSE)

    {

      if (($pos = strpos($fp[$i], ";")) !== FALSE)

       $switch['always_email'] = ((int)substr($fp[$i], $pos -1) == 1) ? 'Checked' : '';

    }  

    else if (strpos($fp[$i], "\$verbose") !== FALSE)

    {

      if (($pos = strpos($fp[$i], ";")) !== FALSE) 

       $switch['verbose'] = ((int)substr($fp[$i], $pos -1) == 1) ? 'Checked' : '';

    }

    else if (strpos($fp[$i], "\$logfile") !== FALSE && strpos($fp[$i], "\$logfile_") === FALSE)

    {

      if (($pos = strpos($fp[$i], ";")) !== FALSE) 

       $switch['logfile'] = ((int)substr($fp[$i], $pos -1) == 1) ? 'Checked' : '';

    } 

    else if (strpos($fp[$i], "\$logfile_size") !== FALSE)

    {

      if (($pos = strpos($fp[$i], ";")) !== FALSE)

       $switch['logfile_size'] = (((int)substr($fp[$i], $pos - 1) > 0) ? (int)substr($fp[$i], $pos - 1) : '100000');

    }   

    else if (strpos($fp[$i], "\$reference_reset") !== FALSE)

    {

      if (($pos = strpos($fp[$i], ";")) !== FALSE) 

       $switch['reference_reset'] = (((int)substr($fp[$i], $pos - 1) > 0) ? (int)substr($fp[$i], $pos - 1) : '');

    }      

    else if (strpos($fp[$i], "\$quarantine") !== FALSE)

    {

      if (($pos = strpos($fp[$i], ";")) !== FALSE) 

       $switch['quarantine'] = ((int)substr($fp[$i], $pos -1) == 1) ? 'Checked' : '';

    }          

    else if (strpos($fp[$i], "\$to") !== FALSE) 

    { 

      $switch['to_address'] = GetConfigureSetting($fp[$i], "'", "'");

      $switch['to_address'] = ($switch['to_address'] === 'some_address@your_domain.com') ? STORE_OWNER_EMAIL_ADDRESS : $switch['to_address'];

    }   

    else if (strpos($fp[$i], "\$from") !== FALSE)

    {  

      $switch['from_address'] = GetConfigureSetting($fp[$i], "'", "'");

      $switch['from_address'] = ($switch['from_address'] === 'From: some_address@your_domain.com') ? "From: " . STORE_OWNER_EMAIL_ADDRESS : $switch['from_address'];

    }   

    else if (strpos($fp[$i], "\$start_dir") !== FALSE)

    {  

      $switch['start_dir'] = GetConfigureSetting($fp[$i], "'", "'");    

      $start_dir = ($switch['start_dir'] === '/home/username/public_html') ? rtrim(DIR_FS_DOCUMENT_ROOT, "/") : $switch['start_dir'];  

    }   

    else if (strpos($fp[$i], "\$admin_dir") !== FALSE)

    {  

      $switch['admin_dir'] = GetConfigureSetting($fp[$i], "'", "'");    

      $admin_dir = ($switch['admin_dir'] === 'https://www.yourdomain.com/admin') ? rtrim(HTTP_SERVER.DIR_WS_ADMIN, "/") : $switch['admin_dir'];

    }    

    else if (strpos($fp[$i], "\$admin_username") !== FALSE)

    {  

      $switch['admin_username'] = GetConfigureSetting($fp[$i], "'", "'");    

      $admin_username = $switch['admin_username'];

    }    

    else if (strpos($fp[$i], "\$admin_password") !== FALSE)

    {  

      $switch['admin_password'] = GetConfigureSetting($fp[$i], "'", "'");    

      $admin_password = $switch['admin_password'];

    }    

    else if (strpos($fp[$i], "\$excludeList") !== FALSE)  

    {

      $quarantine = substr(DIR_WS_ADMIN, 1) . "quarantine";

      $list = stripslashes(GetConfigureSetting($fp[$i], "(", ")"));

      $switch['exclude_list'] = (strpos($list, $quarantine) === FALSE) ? "\"" .  $quarantine . "\", " . $list : $list;

    }   

  }                   

 

  /****************** BUILD THE DIRECTORY LIST ***********************/  

  $exclude_array = array();

  $exclude_array = GetList(DIR_FS_CATALOG, 1, 1, $exclude_array);

  $exclude_selector = array();  





  $exclude_selector[] = array('id' => 0, 

                              'text' => 'Make Selection');

  for ($i = 0; $i < count($exclude_array); ++$i)

  {

    $exclude_selector[] = array('id' => $i+1, 

                                'text' => GetDirectoryName($start_dir, $exclude_array[$i]));

  }

 

  /************************ CHECK THE INPUT ***************************/     

  if (isset($_GET['action_reset']))

  {

    $switch['exclude_list'] = '';

  } 

     

  else if (isset($_POST['action']))

  {

    if (isset($_POST['update_x']))

    {

      $switch['always_email'] = (isset($_POST['always_email'])) ? 'Checked' : '';

      $switch['verbose'] = (isset($_POST['verbose'])) ? 'Checked' : '';

      $switch['logfile'] = (isset($_POST['logfile'])) ? 'Checked' : '';

      $switch['logfile_size'] = $_POST['logfile_size'];

      $switch['reference_reset'] = $_POST['reference_reset'];

      $switch['quarantine'] = (isset($_POST['quarantine'])) ? 'Checked' : '';

      $switch['to_address'] = $_POST['to_address'];

      $switch['from_address'] = $_POST['from_address'];

      $switch['start_dir'] = $_POST['start_dir'];

      $switch['admin_dir'] = $_POST['admin_dir'];

      $switch['admin_username'] = $_POST['admin_username'];

      $switch['admin_password'] = $_POST['admin_password'];

      $switch['exclude_list'] = stripslashes($_POST['exclude_list']); 

 

      $error = false;

      $errmsg = '';

        

      if (empty($switch['to_address']))

      {

        $errmsg = "To address is required.";

        $error = true;

      }      

      else if (empty($switch['from_address']))

      {

        $errmsg = "From address is required.";

        $error = true;

      }

      else if (empty($switch['start_dir']))

      {

        $errmsg = "Start directory is required.";

        $error = true;

      }         

    

      if (! $error)

      {   

        $options = array();

        

        $opt = ($switch['always_email']) == 'Checked' ? 1 : 0; 

        $options['always_email'] = sprintf("\$always_email = %d; //set to 1 to always email the results", $opt);

        

        $opt = ($switch['verbose']) == 'Checked' ? 1 : 0; 

        $options['verbose'] = sprintf("\$verbose = %d; //set to 1 to see the results displayed on the page (for when running manually)", $opt);

  

        $opt = ($switch['logfile']) == 'Checked' ? 1 : 0; 

        $options['logfile'] = sprintf("\$logfile = %d; //set to 1 to see to track results in a log file", $opt);

  

        $opt = (empty($switch['logfile_size'])) ? '100000' : $switch['logfile_size']; 

        $options['logfile_size'] = sprintf("\$logfile_size = %d; //set the maximum size of the logfile", $opt);



        $opt = (empty($switch['reference_reset'])) ? '' : $switch['reference_reset']; 

        $options['reference_reset'] = sprintf("\$reference_reset = %d; //delete the reference file this many days apart", $opt);

  

        $opt = ($switch['quarantine']) == 'Checked' ? 1 : 0; 

        $options['quarantine'] = sprintf("\$quarantine = %d; //set to 1 to move new files found to the quarantine directory", $opt);

           

        $opt = $switch['to_address']; 

        $options['to_address'] = sprintf("\$to = '%s'; //where email is sent to", $opt);

        

        $opt = $switch['from_address']; 

        $options['from_address'] = sprintf("\$from = '%s'; //where email is sent from", $opt);    



        $opt = $switch['start_dir'] ; 

        $options['start_dir'] = sprintf("\$start_dir = '%s'; //your shops root", $opt); 

  

        $opt = $switch['admin_dir'] ; 

        $options['admin_dir'] = sprintf("\$admin_dir = '%s'; //your shops admin", $opt); 



        $opt = $switch['admin_username'] ; 

        $options['admin_username'] = sprintf("\$admin_username = '%s'; //your admin username", $opt); 



        $opt = $switch['admin_password'] ; 

        $options['admin_password'] = sprintf("\$admin_password = '%s'; //your admin password", $opt); 

                        

        $opt = CheckExcludeList($switch['exclude_list']);  //special case - must be last

  

        if (strpos($opt, "FAILED") === FALSE)

        {        

          $options['exclude_list'] = stripslashes(sprintf("\$excludeList = array(%s); //don't check these directories - change to your liking - must be set prior to first run", $opt));

      

          $fp = file($filenameConfigure);

          $fp_out = array();

          for ($i = 0; $i < count($fp); ++$i)

          {

            if (strpos($fp[$i], "\$always_email") !== FALSE)

             $fp_out[] = $options['always_email'] . "\n";

            else if (strpos($fp[$i], "\$verbose") !== FALSE)  

             $fp_out[] = $options['verbose'] . "\n";

            else if (strpos($fp[$i], "\$logfile") !== FALSE && (strpos($fp[$i], "\$logfile_") === FALSE) )  

             $fp_out[] = $options['logfile'] . "\n";

            else if (strpos($fp[$i], "\$logfile_size") !== FALSE)  

             $fp_out[] = $options['logfile_size'] . "\n";

            else if (strpos($fp[$i], "\$reference_reset") !== FALSE)  

             $fp_out[] = $options['reference_reset'] . "\n";

            else if (strpos($fp[$i], "\$quarantine") !== FALSE)  

             $fp_out[] = $options['quarantine'] . "\n";

            else if (strpos($fp[$i], "\$to") !== FALSE)  

             $fp_out[] = $options['to_address'] . "\n";

            else if (strpos($fp[$i], "\$from") !== FALSE)  

             $fp_out[] = $options['from_address'] . "\n";

            else if (strpos($fp[$i], "\$start_dir") !== FALSE)  

             $fp_out[] = $options['start_dir'] . "\n";      

            else if (strpos($fp[$i], "\$admin_dir") !== FALSE)  

             $fp_out[] = $options['admin_dir'] . "\n";      

            else if (strpos($fp[$i], "\$admin_username") !== FALSE)  

             $fp_out[] = $options['admin_username'] . "\n";      

            else if (strpos($fp[$i], "\$admin_password") !== FALSE)  

             $fp_out[] = $options['admin_password'] . "\n";      

            else if (strpos($fp[$i], "\$excludeList") !== FALSE)  

             $fp_out[] = $options['exclude_list'] . "\n";            

            else

             $fp_out[] = $fp[$i];

          }

          

          WriteConfigureFile($filenameConfigure, $fp_out);

        }

        else

          $messageStack->add($opt, 'error');

      }

      else if ($error)

       $messageStack->add($errmsg, 'error');  

    }

    

    /************************** EXCLUDE SELECTOR WAS USED ******************************/

    else if ($_POST['exclude_selector'] > 0)    

    { 

      $removeThis1 = sprintf("\"%s\", ", $exclude_selector[$_POST['exclude_selector']]['text']);

      $removeThis2 = sprintf("\"%s\"", $exclude_selector[$_POST['exclude_selector']]['text']);

      if (strpos($_POST['exclude_list'], $removeThis1) !== FALSE)  //already exists in list with comma so remove it

      {

         $switch['exclude_list'] = str_replace($removeThis1, "", $_POST['exclude_list']);

      }

      else if (strpos($_POST['exclude_list'], $removeThis2) !== FALSE)  //already exists in list so remove it

      {

         $switch['exclude_list'] = str_replace($removeThis2, "", $_POST['exclude_list']);

         if (substr($switch['exclude_list'], -1) == ",")

           $switch['exclude_list'] = substr($switch['exclude_list'], 0, -1); //remove extra comma at end if needed

         else if (strpos($switch['exclude_list'], ",,") !== FALSE)

           $switch['exclude_list'] = str_replace(",,", ",", $switch['exclude_list']);  //remove extra comma in string if needed

         else 

           $switch['exclude_list'] = ltrim($switch['exclude_list'], ", ");  //remove extra comma at the beginning if needed

      }

      else

      {

        $curList = stripslashes($_POST['exclude_list']);



        if (tep_not_null($errmsg = AddToExcludeList($curList, GetDirectoryName($start_dir, $exclude_array[$_POST['exclude_selector']-1]), DIR_WS_ADMIN)))

          $messageStack->add($errmsg, 'error');

        

        $switch['exclude_list'] = $curList; 

      }      

    }

  } 

?>

<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">

<html <?php echo HTML_PARAMS; ?>>

<head>

<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">

<title><?php echo TITLE; ?></title>

<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">

<style type="text/css">

td.HTC_Head {color: sienna; font-size: 24px; font-weight: bold; } 

td.HTC_subHead {color: sienna; font-size: 14px; } 

</style>

</head>

<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">

<!-- body //-->

<table width="1000px" border="0" align="center" cellpadding="10" cellspacing="0" bgcolor="#FFFFFF" style="border:solid 1px; border-color:#999999;">

  <tr>

    

<!-- body_text //-->

    <td width="100%" valign="top">

	<!-- header //-->

	<?php

  require(DIR_WS_INCLUDES . 'header.php');

?>

<!-- header_eof //-->

<table border="0" width="100%" cellspacing="0" cellpadding="2">

     <tr>

      <td class="HTC_Head"><?php echo HEADING_SITEMONITOR_CONFIGURE_SETUP; ?></td>

     </tr>

     <tr>

      <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>

      </tr>

     <tr>

      <td class="HTC_subHead"><?php echo TEXT_SITEMONITOR_CONFGIURE_SETUP; ?></td>

     </tr>

     <tr>

      <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>

     </tr>

     <tr>

      <td><?php echo tep_black_line(); ?></td>

     </tr>

     

     <!-- BEGIN SITEMONITOR CONFIGURE SETTINGS -->      

     <tr>

      <td><table width="100%">      

       <tr>

        <td align="right"><?php echo tep_draw_form('sitemonitor', FILENAME_SITEMONITOR_CONFIG_SETUP, '', 'post') . tep_draw_hidden_field('action', 'process'); ?></td>

         <tr>

          <td><table border="0" width="100%" cellspacing="0" cellpadding="2"> 

           <tr>

            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">

             <tr class="main">

              <td width="18%"><?php echo TEXT_OPTION_ALWAYS_EMAIL; ?></td>

              <td width="8%"><?php echo tep_draw_checkbox_field('always_email', '', $switch['always_email'], ''); ?> </td>

              <td class="smallText" valign="top"><?php echo TEXT_OPTION_ALWAYS_EMAIL_EXPLAIN; ?></td>

             </tr>

            </table></td>  

           </tr>

           <tr>

            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">

             <tr class="main">           

              <td width="18%"><?php echo TEXT_OPTION_QUARANTINE; ?></td>

              <td width="8%"><?php echo tep_draw_checkbox_field('quarantine', '', $switch['quarantine'], ''); ?> </td>

              <td class="smallText" valign="top" align="left"><?php echo TEXT_OPTION_QUARANTINE_EXPLAIN; ?></td>

             </tr>

            </table></td>              

           </tr>             

           <tr>

            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">

             <tr class="main">           

              <td width="18%"><?php echo TEXT_OPTION_VERBOSE; ?></td>

              <td width="8%"><?php echo tep_draw_checkbox_field('verbose', '', $switch['verbose'], ''); ?> </td>

              <td class="smallText" valign="top" align="left"><?php echo TEXT_OPTION_VERBOSE_EXPLAIN; ?></td>

             </tr>

            </table></td>              

           </tr>

           <tr>

            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">

             <tr class="main">           

              <td width="18%"><?php echo TEXT_OPTION_LOGFILE; ?></td>

              <td width="8%"><?php echo tep_draw_checkbox_field('logfile', '', $switch['logfile'], ''); ?> </td>

              <td class="smallText" valign="top" align="left"><?php echo TEXT_OPTION_LOGFILE_EXPLAIN; ?></td>

             </tr>

            </table></td>              

           </tr>         

           <tr>

            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">

             <tr class="main">           

              <td width="18%"><?php echo TEXT_OPTION_LOGFILE_SIZE; ?></td>

              <td width="8%"><?php echo tep_draw_input_field('logfile_size',$switch['logfile_size'], 'maxlength="10", size="6"', false, 300); ?> </td>

              <td class="smallText" valign="top" align="left"><?php echo TEXT_OPTION_LOGFILE_SIZE_EXPLAIN; ?></td>

             </tr>

            </table></td>              

           </tr>  

           <tr>

            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">

             <tr class="main">

              <td width="18%"><?php echo TEXT_OPTION_REFERENCE_RESET; ?></td>

              <td width="8%"><?php echo tep_draw_input_field('reference_reset',$switch['reference_reset'], 'maxlength="10", size="6"', false, 300); ?> </td>

              <td class="smallText" valign="top"><?php echo TEXT_OPTION_REFERENCE_RESET_EXPLAN; ?></td>

             </tr>

            </table></td>  

           </tr>                                                   

           <tr>

            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">

             <tr class="main">             

              <td width="18%"><?php echo TEXT_OPTION_TO_EMAIL; ?></td>

              <td width="32%"><?php echo tep_draw_input_field('to_address', $switch['to_address'], 'maxlength="255", size="40"', false, 300); ?> </td>

              <td class="smallText" valign="top"><?php echo TEXT_OPTION_TO_ADDRESS_EXPLAIN; ?></td>

             </tr>

            </table></td>                

           </tr>  

           <tr>

            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">

             <tr class="main">             

              <td width="18%"><?php echo TEXT_OPTION_FROM_EMAIL; ?></td>

              <td width="32%"><?php echo tep_draw_input_field('from_address', $switch['from_address'], 'maxlength="255", size="40"', false, 300); ?> </td>

              <td class="smallText" valign="top"><?php echo TEXT_OPTION_FROM_ADDRESS_EXPLAIN; ?></td>

             </tr>

            </table></td>                

           </tr>

           <tr>

            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">

             <tr class="main">             

              <td width="18%"><?php echo TEXT_OPTION_START_DIR; ?></td>

              <td width="32%"><?php echo tep_draw_input_field('start_dir', (empty($switch['start_dir']) ? DIR_FS_DOCUMENT_ROOT : $switch['start_dir']), 'maxlength="255", size="40"', false, 300); ?> </td>

              <td class="smallText" valign="top"><?php echo TEXT_OPTION_START_DIR_EXPLAIN; ?></td>

             </tr>

            </table></td>                

           </tr>   

           <tr>

            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">

             <tr class="main">             

              <td width="18%"><?php echo TEXT_OPTION_ADMIN_DIR; ?></td>

              <td width="32%"><?php echo tep_draw_input_field('admin_dir', (empty($switch['admin_dir']) ? HTTP_SERVER . DIR_WS_ADMIN : $switch['admin_dir']), 'maxlength="255", size="40"', false, 300); ?> </td>

              <td class="smallText" valign="top"><?php echo TEXT_OPTION_ADMIN_DIR_EXPLAIN; ?></td>

             </tr>

            </table></td>                

           </tr>    

           <tr>

            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">

             <tr class="main">             

              <td width="18%"><?php echo TEXT_OPTION_ADMIN_USERNAME; ?></td>

              <td width="32%"><?php echo tep_draw_input_field('admin_username', (empty($switch['admin_username']) ? DB_SERVER_USERNAME : $switch['admin_username']), 'maxlength="255", size="40"', false, 300); ?> </td>

              <td class="smallText" valign="top"><?php echo TEXT_OPTION_ADMIN_USERNAME_EXPLAIN; ?></td>

             </tr>

            </table></td>                

           </tr>  

           <tr>

            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">

             <tr class="main">             

              <td width="18%"><?php echo TEXT_OPTION_ADMIN_PASSWORD; ?></td>

              <td width="32%"><?php echo tep_draw_input_field('admin_password', (empty($switch['admin_password']) ? DB_SERVER_PASSWORD : $switch['admin_password']), 'maxlength="255", size="40"', false, 300); ?> </td>

              <td class="smallText" valign="top"><?php echo TEXT_OPTION_ADMIN_PASSWORD_EXPLAIN; ?></td>

             </tr>

            </table></td>                

           </tr>                                 

           <tr>

            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">

             <tr class="main">

              <td width="18%"><?php echo TEXT_OPTION_EXCLUDE_SELECTOR; ?></td>

              <td><?php echo tep_draw_pull_down_menu('exclude_selector', $exclude_selector, '', 'onChange="this.form.submit();"');?></td>

             </tr>              

            </table></td>                

           </tr>                               

           <tr>

            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">

             <tr class="main">             

              <td width="18%"><?php echo TEXT_OPTION_EXCLUDE_LIST; ?></td>

              <td><?php echo tep_draw_textarea_field('exclude_list', 'soft', 60, 7, $switch['exclude_list'], '', false); ?></td>

              <td class="smallText" valign="top"><?php echo TEXT_OPTION_EXCLUDE_LIST_EXPLAIN; ?></td>

             </tr>

            </table></td>                

           </tr>                               

           <tr>

            <td><table border="0" width="80%" cellspacing="0" cellpadding="2">

             <tr>

              <td align="center">

               <INPUT type="image" src="<?php echo DIR_WS_LANGUAGES . $language . '/images/buttons/button_update.gif'; ?>" NAME="update"> 

               <?php echo '<a href="' . tep_href_link(FILENAME_SITEMONITOR_CONFIG_SETUP, 'action_reset=reset') . '">' . tep_image_button('button_reset.gif', IMAGE_RESET) . '</a>'; ?>

              </td>

             </tr>

            </table></td>  

           </tr>       

          <table></td>

         </tr>        

        </form>

        </td>

       </tr>   

      </table></td>

     </tr>  

     <!-- END SITEMONITOR CONFIGRE SETTINGS -->   



     <tr>

      <td><?php echo tep_black_line(); ?></td>

     </tr>

 

    </table></td>

<!-- body_text_eof //-->

  </tr>

</table>

<!-- body_eof //-->

</td>

</tr>

</table>

<!-- footer //-->

<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>

<!-- footer_eof //-->

</body>

</html>

<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>