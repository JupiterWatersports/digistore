<?php

/*

  $Id: basket.php,v 1.1.2 2008/08/26 23:28:24 hpdl Exp $



  Digistore v4.0,  Open Source E-Commerce Solutions

  http://www.digistore.co.nz



  Copyright (c) 2003 osCommerce, http://www.oscommerce.com



  Released under the GNU General Public License



  Author : Antonio Ibarrola

  email  : antonio_ibarrola_cerda@hotmail.com

  Online Store : www.topgun.com.mx

*/





// parameter is used to screen wide (W)-Best view 1600x1200 or thiny (T)-Best view 1024x768

  if (!isset($screen)) {$screen='W';} else { if ($screen=='t') {$screen='T';} } 



  require('includes/application_top.php');



// redirect the customer to a friendly cookie-must-be-enabled page if cookies are disabled (or the session has not started)

  if ($session_started == false) { 

    tep_redirect(tep_href_link(FILENAME_COOKIE_USAGE));

  } 


$psw = $_GET['psw'];
// check if basket password is correct

  if (empty($psw) or $psw <> BASKET_PASSWORD) {

    $messageStack->add('login', TEXT_BASKET_PASSWORD_ERROR);

    tep_redirect(tep_href_link(FILENAME_DEFAULT));

  }



  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_BASKET);



  tep_session_unregister('customer_id');

  tep_session_unregister('customer_default_address_id');

  tep_session_unregister('customer_first_name');

  tep_session_unregister('customer_country_id');

  tep_session_unregister('customer_zone_id');

  tep_session_unregister('comments');



  $cart->reset();



  $basket_customers_id  = array() ;

  $basket_date_added    = array() ;

  $basket_last_login    = array() ;

  $basket_name          = array() ;

  $basket_email_address = array() ;





  if (isset($action) && $action=='delete') {

     tep_db_query("DELETE from " . TABLE_CUSTOMERS_BASKET . " where customers_id=" . $customer);

     tep_db_query("DELETE from " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " where customers_id=" . $customer);

     unset($customer) ;

     unset($action) ;

  }



  $error = false;

  if (isset($action) && $action=='login') {

    unset($action) ;

    $email_address = $email ;

    // Check if email exists

    $check_customer_query = tep_db_query("select customers_id, customers_firstname, customers_password, customers_email_address, customers_default_address_id from " . TABLE_CUSTOMERS . " where customers_email_address = '" . tep_db_input($email_address) . "'");

    if (!tep_db_num_rows($check_customer_query)) {

        $error = true;

    } else {

        $check_customer = tep_db_fetch_array($check_customer_query);

        if (SESSION_RECREATE == 'True') {

          tep_session_recreate();

        }



        $check_country_query = tep_db_query("select entry_country_id, entry_zone_id from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$check_customer['customers_id'] . "' and address_book_id = '" . (int)$check_customer['customers_default_address_id'] . "'");

        $check_country = tep_db_fetch_array($check_country_query);



        $customer_id = $check_customer['customers_id'];

        $customer_default_address_id = $check_customer['customers_default_address_id'];

        $customer_first_name = $check_customer['customers_firstname'];

        $customer_country_id = $check_country['entry_country_id'];

        $customer_zone_id = $check_country['entry_zone_id'];

        tep_session_register('customer_id');

        tep_session_register('customer_default_address_id');

        tep_session_register('customer_first_name');

        tep_session_register('customer_country_id');

        tep_session_register('customer_zone_id');

    }

    // restore cart contents

    $cart->restore_contents();

    tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));

  }



  if ($error == true) {

    $messageStack->add('login', TEXT_BASKET_LOGIN_ERROR);

  }





  $i = -1 ;

  $previus_customer_id = 0 ;

  $sql_basket = tep_db_query("SELECT * from " . TABLE_CUSTOMERS_BASKET . " where 1 order by customers_id desc , customers_basket_date_added");

  while ($BASKET=tep_db_fetch_array($sql_basket)) 

   {

      if ($previus_customer_id <> $BASKET['customers_id']) {

          $previus_customer_id =  $BASKET['customers_id'] ;

          $i++ ; 

          $sql_customers = tep_db_query("select * from " . TABLE_CUSTOMERS . " where customers_id =" . $BASKET['customers_id']);

          if ($CUSTOMERS = tep_db_fetch_array($sql_customers)) {

              $basket_name[$i]          = $CUSTOMERS['customers_firstname'] . " " . $CUSTOMERS['customers_lastname'];

              $basket_email_address[$i] = $CUSTOMERS['customers_email_address'] ;

              $basket_name[$i]          = $CUSTOMERS['customers_firstname'] . " " . $CUSTOMERS['customers_lastname'];

              $sql_customers_info = tep_db_query("select * from " . TABLE_CUSTOMERS_INFO . " where customers_info_id =" . $BASKET['customers_id']);

              if ($CUSTOMERS_INFO = tep_db_fetch_array($sql_customers_info)) {

                  if ($CUSTOMERS_INFO['customers_info_date_of_last_logon'] == NULL) {

                      $basket_last_login[$i] = $CUSTOMERS_INFO['customers_info_date_account_created'] ;

                  } else {

                      $basket_last_login[$i] = $CUSTOMERS_INFO['customers_info_date_of_last_logon'] ;

                  }

              } else {

                  $basket_last_login[$i]    = " " ;

              }

          } else {

              $basket_customers_id[$i]  = 0 ;

              $basket_last_login[$i]    = " "                 ;

              $basket_name[$i]          = TEXT_BASKET_CUSTOMER_NO_EXIST ;

              $basket_email_address[$i] = TEXT_BASKET_CUSTOMER_NO_EXIST ;

          }

      }

      $basket_customers_id[$i] = $BASKET['customers_id'] ;

      $raw_date = $BASKET['customers_basket_date_added'] ;

      $year  = substr($raw_date, 0, 4);

      $month = substr($raw_date, 4, 2);

      $day   = substr($raw_date, 6, 2);

      $basket_date_added[$i]   = $year . '-' . $month . '-' . $day . ' 00:00:00' ;

   }



   // display the cart in shoping box on colum left or header

   if (isset($customer) && $customer <> 0) {

       $email_address = $email ;

       // Check if email exists

       $check_customer_query = tep_db_query("select customers_id, customers_firstname, customers_password, customers_email_address, customers_default_address_id from " . TABLE_CUSTOMERS . " where customers_email_address = '" . tep_db_input($email_address) . "'");

       if (tep_db_num_rows($check_customer_query)) {

           $check_customer = tep_db_fetch_array($check_customer_query);

           $check_country_query = tep_db_query("select entry_country_id, entry_zone_id from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$check_customer['customers_id'] . "' and address_book_id = '" . (int)$check_customer['customers_default_address_id'] . "'");

           $check_country = tep_db_fetch_array($check_country_query); 

           $customer_id = $check_customer['customers_id'];

           $customer_default_address_id = $check_customer['customers_default_address_id'];

           $customer_first_name = $check_customer['customers_firstname'];

           $customer_country_id = $check_country['entry_country_id'];

           $customer_zone_id = $check_country['entry_zone_id'];

           tep_session_register('customer_id');

           tep_session_register('customer_default_address_id');

           tep_session_register('customer_first_name');

           tep_session_register('customer_country_id');

           tep_session_register('customer_zone_id');

           // restore cart contents

           $cart->restore_contents();

       }

    }



?>

<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">

<html <?php echo HTML_PARAMS; ?>>

<head>

<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">

<title><?php echo TITLE; ?></title>

<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">

<link rel="stylesheet" type="text/css" href="stylesheet.css">

<script language="javascript"><!--

function session_win() {

  window.open("<?php echo tep_href_link(FILENAME_INFO_SHOPPING_CART); ?>","info_shopping_cart","height=460,width=430,toolbar=no,statusbar=no,scrollbars=yes").focus();

}

//--></script>

</head>

<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">

<!-- header //-->

<?php require(DIR_WS_INCLUDES . 'header.php'); ?>

<!-- header_eof //-->



<!-- body //-->

<table border="0" width="100%" cellspacing="3" cellpadding="3">

  <tr>

<!-- body_text //-->

    <td width="100%" valign="top" cellpadding="1">

      <table border="0" width="100%" height="100%" cellspacing="0" cellpadding="0">     
        <tr>

           <td class="pageHeading"><?php echo HEADING_BASKET_TITLE; ?></td>

           <td class="pageHeading" align="right"><?php echo tep_image(DIR_WS_IMAGES . 'table_background_login.gif', HEADING_BASKET_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>

        </tr>

        <tr>

          <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>

        </tr>

      </table>



      <table border="0" width="100%" height="100%" cellspacing="0" cellpadding="2" class="infoBoxHeading">

        <tr class="infoBox" border="0">

          <td align="center" class="infoBoxHeading"><b><?php echo '&nbsp;' . HEADING_BASKET_CUSTOMER_ID;      ?></b></td>

          <td align="Left"   class="infoBoxHeading"><b><?php echo HEADING_BASKET_CUSTOMER_NAME;              ?></b></td>

          <?php if ($screen!=='T') {echo '<td align="Left"   class="infoBoxHeading"><b>' . HEADING_BASKET_CUSTOMER_EMAIL . '</b></td>' ; } ?>



          <td align="Center" class="infoBoxHeading"><b><?php echo HEADING_BASKET_CUSTOMER_LAST_LOGIN_DATE;   ?></b></td>

          <td align="Center"   class="infoBoxHeading"><b><?php echo HEADING_BASKET_CUSTOMER_BASKET_DATE;     ?></b></td>

          <td align="Right"  class="infoBoxHeading"><b><?php echo HEADING_BASKET_CUSTOMER_BASKET_TOTAL_CART; ?></b></td>

          <td align="center" class="infoBoxHeading"><b>     &nbsp                                       </b></td>

          <td align="center" class="infoBoxHeading"><b>     &nbsp                                       </b></td>

          <td align="center" class="infoBoxHeading"><b>     &nbsp                                       </b></td>

        </tr>

            <?php

               $end=$i+1 ;

               $i=0 ;

               while ($i < $end)

                 {

                   $total_cart = 0 ;

                   if ($basket_customers_id[$i] <> 0) {

                       $email_address = $basket_email_address[$i] ;

                      // Check if email exists

                      $check_customer_query = tep_db_query("select customers_id, customers_firstname, customers_password, customers_email_address, customers_default_address_id from " . TABLE_CUSTOMERS . " where customers_email_address = '" . tep_db_input($email_address) . "'");

                      if (tep_db_num_rows($check_customer_query)) {

                         $check_customer = tep_db_fetch_array($check_customer_query);

                         $check_country_query = tep_db_query("select entry_country_id, entry_zone_id from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$check_customer['customers_id'] . "' and address_book_id = '" . (int)$check_customer['customers_default_address_id'] . "'");

                         $check_country = tep_db_fetch_array($check_country_query); 

                         $customer_id = $check_customer['customers_id'];

                         $customer_default_address_id = $check_customer['customers_default_address_id'];

                         $customer_first_name = $check_customer['customers_firstname'];

                         $customer_country_id = $check_country['entry_country_id'];

                         $customer_zone_id = $check_country['entry_zone_id'];

                         tep_session_register('customer_id');

                         tep_session_register('customer_default_address_id');

                         tep_session_register('customer_first_name');

                         tep_session_register('customer_country_id');

                         tep_session_register('customer_zone_id');

                      }

                      // restore cart contents

                      $cart->restore_contents();

                      $total_cart = $cart->show_total(); ;

                      $cart->reset();

                      tep_session_unregister('customer_id');

                      tep_session_unregister('customer_default_address_id');

                      tep_session_unregister('customer_first_name');

                      tep_session_unregister('customer_country_id');

                      tep_session_unregister('customer_zone_id');

                      tep_session_unregister('comments');

                   }

                 ?>

                   <tr class="infoBoxContents" border="0">

                     <?php 

                     if (isset($customer) && $customer == $basket_customers_id[$i]) { ?>

                        <td align="center"><b><?php echo '&nbsp#' . $basket_customers_id[$i] ; ?> </b></td>

                        <td>               <b><?php echo $basket_name[$i]                       ; ?></b> 

                        <?php if ($screen =='T') {

                                  echo '<br />' ; 

                              } else {

                                  echo '</td><td>' ; 

                              } 

                        ?>

                                           <b><?php echo $basket_email_address[$i]              ; ?></b></td>

                        <td align="center"><b><?php echo tep_date_short($basket_last_login[$i]) ; ?></b></td>

                        <td align="center"><b><?php echo tep_date_short($basket_date_added[$i]) ; ?></b></td>

                        <td align="right"> <b><?php echo $currencies->format($total_cart)       ; ?></b></td>

                        <td align="right"> <b><?php echo '<a href="' . tep_href_link(FILENAME_BASKET, 'psw=' . $psw . '&customer='. $basket_customers_id[$i] , 'SSL') . '">' . TEXT_BASKET_SEE_BASKET . '</a>' ; ?></b></td>

                        <td align="center"><b><?php echo '<a href="' . tep_href_link(FILENAME_BASKET, 'psw=' . $psw . '&customer='. $basket_customers_id[$i] . '&action=delete' , 'SSL') . '">' . TEXT_BASKET_DELETE . '</a>' ; ?></b></td>

                        <td align="center"><b><?php echo '<a href="' . tep_href_link(FILENAME_BASKET, 'psw=' . $psw . '&email='. $basket_email_address[$i] . '&action=login' , 'SSL') . '">' . TEXT_BASKET_LOGIN  . '</a>' ; ?></b></td>

                        <?php $email = $basket_email_address[$i] ;

                     } else { ?>

                        <td align="center"><?php echo '&nbsp#' . $basket_customers_id[$i] ; ?> </td>

                        <td>               <?php echo $basket_name[$i]                       ; ?>

                        <?php if ($screen =='T') {

                                  echo '<br />' ; 

                              } else {

                                  echo '</td><td>' ; 

                              } 

                        ?>

                                           <?php echo $basket_email_address[$i]              ; ?></td>

                        <td align="center"><?php echo tep_date_short($basket_last_login[$i]) ; ?></td>

                        <td align="center"><?php echo tep_date_short($basket_date_added[$i]) ; ?></td>

                        <td align="right"> <?php echo $currencies->format($total_cart)       ; ?></td>

                        <td align="right"> <?php echo '<a href="' . tep_href_link(FILENAME_BASKET, 'psw=' . $psw . '&customer='. $basket_customers_id[$i] , 'SSL') . '">' . TEXT_BASKET_SEE_BASKET . '</a>' ; ?></td>

                        <td align="center"><?php echo '<a href="' . tep_href_link(FILENAME_BASKET, 'psw=' . $psw . '&customer='. $basket_customers_id[$i] . '&action=delete' , 'SSL') . '">' . TEXT_BASKET_DELETE . '</a>' ; ?></td>

                        <td align="center"><?php echo '<a href="' . tep_href_link(FILENAME_BASKET, 'psw=' . $psw . '&email='. $basket_email_address[$i] . '&action=login' , 'SSL') . '">' . TEXT_BASKET_LOGIN  . '</a>' ; ?></td>

                      <?php

                     }

                      ?>

                   </tr>

                 <?php

                   $i++ ;

                 }



                 ?>

      </table>

    </td>



<?php

    // display the cart in shoping box on colum rigth or footer

    if (isset($customer) && $customer <> 0) {

        $email_address = $email ;

        // Check if email exists

        $check_customer_query = tep_db_query("select customers_id, customers_firstname, customers_password, customers_email_address, customers_default_address_id from " . TABLE_CUSTOMERS . " where customers_email_address = '" . tep_db_input($email_address) . "'");

        if (tep_db_num_rows($check_customer_query)) {

            $check_customer = tep_db_fetch_array($check_customer_query);

            $check_country_query = tep_db_query("select entry_country_id, entry_zone_id from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$check_customer['customers_id'] . "' and address_book_id = '" . (int)$check_customer['customers_default_address_id'] . "'");

            $check_country = tep_db_fetch_array($check_country_query); 

            $customer_id = $check_customer['customers_id'];

            $customer_default_address_id = $check_customer['customers_default_address_id'];

            $customer_first_name = $check_customer['customers_firstname'];

            $customer_country_id = $check_country['entry_country_id'];

            $customer_zone_id = $check_country['entry_zone_id'];

            tep_session_register('customer_id');

            tep_session_register('customer_default_address_id');

            tep_session_register('customer_first_name');

            tep_session_register('customer_country_id');

            tep_session_register('customer_zone_id');

            // restore cart contents

            $cart->restore_contents();

        }

    }

?>







<!-- body_text_eof //-->

    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">

<!-- right_navigation //-->

<?php require(DIR_WS_INCLUDES . 'column_right.php'); ?>

<!-- right_navigation_eof //-->

    </table></td>

  </tr>

</table>

<!-- body_eof //-->

<?php

  tep_session_unregister('customer_id');

  tep_session_unregister('customer_default_address_id');

  tep_session_unregister('customer_first_name');

  tep_session_unregister('customer_country_id');

  tep_session_unregister('customer_zone_id');

  tep_session_unregister('comments');

  $cart->reset();

?>

<!-- footer //-->

<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>

<!-- footer_eof //-->

</body>

</html>

<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); 



?>

