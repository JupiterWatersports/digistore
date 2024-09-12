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

 

 $fileDeleted = false;

 $foundErrors = 0;

 $refFile     = "sitemonitor_reference.php";

 $showErrors  = 0;

 

 $actionDelete = (isset($_POST['action_delete']) ? $_POST['action_delete'] : '');

 $actionExecute = (isset($_POST['action_execute']) ? $_POST['action_execute'] : '');

 

 if (tep_not_null($actionDelete) || tep_not_null($actionExecute))

 {

   require('sitemonitor_configure.php');

   require('includes/functions/sitemonitor_functions.php');

   

   if (is_file($refFile))

   {

      runSitemonitor(0);                 //run before deleting

      if (tep_not_null($actionDelete))   //delete the reference file before running

       if (unlink($refFile))

        $fileDeleted = true;

   } 



  

   $foundErrors = runSitemonitor(0);        //create the reference files

   $showErrors = 1;  

   switch ($foundErrors)                    //report result

   {

     case -1: $errmsg = 'Reference file creation failed.'; break;

     case -2: $errmsg = 'First time ran. Reference file was created and saved.'; break;

     case  0: $errmsg = 'No mismatches found'; break;

     default: $errmsg = sprintf("%d mismatches were found. Run the script manually or see the email for the actual mismatches.", $foundErrors); break;

   }

 }

 else if (isset($_POST['action_manual']))

 {

   tep_redirect(tep_href_link('sitemonitor.php'));

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

      <td class="HTC_Head"><?php echo HEADING_SITEMONITOR_ADMIN; ?></td>

     </tr>

     <tr>

      <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>

      </tr>

     <tr>

      <td class="HTC_subHead"><?php echo TEXT_SITEMONITOR_ADMIN; ?></td>

     </tr>

     <tr>

      <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>

     </tr>

     <tr>

      <td><?php echo tep_black_line(); ?></td>

     </tr>

     

     <!-- BEGIN DELETE AND GENERATE FILE -->

     <tr>

      <td><table width="100%">

       <tr>

        <td align="right"><?php echo tep_draw_form('header_tags_auto', FILENAME_SITEMONITOR_ADMIN, '', 'post') . tep_draw_hidden_field('action_delete', 'process'); ?></td>

         <tr>

          <td><table border="0" width="40%" cellspacing="0" cellpadding="2">

           <tr>

            <td class="main" width="70%"><?php echo TEXT_SITEMONITOR_DELETE_REFERENCE; ?></td>

           </tr>

           <tr>

            <td class="smallText"><?php echo TEXT_SITEMONITOR_DELETE_EXPLAIN; ?></td>            

            <td align="center"><?php echo (tep_image_submit('button_update.gif', IMAGE_UPDATE) ) . ' <a href="' . tep_href_link(FILENAME_SITEMONITOR_ADMIN, '') .'">' . '</a>'; ?></td>

           </tr>     

           <?php if ($actionDelete && $fileDeleted) { ?>

            <tr><td class="smallText"><?php echo $refFile . ' has been deleted!'; ?></td></tr>

           <?php } ?>     

           <?php if ($actionDelete && $showErrors) { ?>

            <tr><td class="smallText"><?php echo $errmsg; ?></td></tr>

           <?php } ?>  

          <table></td>

         </tr>        

        </form>

        </td>

       </tr>   

      </table></td>

     </tr>  

     <!-- END DELETE AND GENERATE FILE -->   



     <tr>

      <td><?php echo tep_black_line(); ?></td>

     </tr>

     

     <!-- BEGIN EXECUTE FILE -->

     <tr>

      <td><table width="100%">

        <tr>

        <td align="right"><?php echo tep_draw_form('header_tags_auto', FILENAME_SITEMONITOR_ADMIN, '', 'post') . tep_draw_hidden_field('action_execute', 'process'); ?></td>

         <tr>

          <td><table border="0" width="40%" cellspacing="0" cellpadding="2">

           <tr>

            <td class="main" width="70%"><?php echo TEXT_SITEMONITOR_EXECUTE; ?></td>

           </tr>

           <tr>

            <td class="smallText"><?php echo TEXT_SITEMONITOR_EXECUTE_EXPLAIN; ?></td> 

            <td align="center"><?php echo (tep_image_submit('button_update.gif', IMAGE_UPDATE) ) . ' <a href="' . tep_href_link(FILENAME_SITEMONITOR_ADMIN, '') .'">' . '</a>'; ?></td>

           </tr>     

           <?php if ($actionExecute && $showErrors) { ?>

            <tr><td class="smallText"><?php echo $errmsg; ?></td></tr>

           <?php } ?>  

          <table></td>

         </tr>        

        </form>

        </td>

       </tr>     

      </table></td>

     </tr>  

     <!-- END EXECUTE FILE -->      

     

     <tr>

      <td><?php echo tep_black_line(); ?></td>

     </tr>

          

     <!-- BEGIN MANUALLY EXECUTE FILE -->

     <tr>

      <td><table width="100%">

        <tr>

        <td align="right"><?php echo tep_draw_form('header_tags_auto', FILENAME_SITEMONITOR_ADMIN, '', 'post') . tep_draw_hidden_field('action_manual', 'process'); ?></td>

         <tr>

          <td><table border="0" width="40%" cellspacing="0" cellpadding="2">

           <tr>

            <td class="main" width="70%"><?php echo TEXT_SITEMONITOR_MANUAL; ?></td>

           </tr>

           <tr>

            <td class="smallText"><?php echo TEXT_SITEMONITOR_MANUAL_EXPLAIN; ?></td> 

            <td align="center"><?php echo (tep_image_submit('button_update.gif', IMAGE_UPDATE) );?></td>

           </tr>     

          <table></td>

         </tr>        

        </form>

        </td>

       </tr>     

      </table></td>

     </tr>  

     <!-- END MANUALLY EXECUTE FILE -->        

	 

    </table></td>

<!-- body_text_eof //-->

  </tr>

</table>

<!-- body_eof //-->



<!-- footer //-->

<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>

<!-- footer_eof //-->

</body>

</html>

<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>

