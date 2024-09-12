<?php
/*
  $Id: product_info_process.php v1.0 20101215 kymation $
  $From: product_info.php 1739 2007-12-20 00:52:16Z hpdl $
  $Loc: catalog/includes/modules/ $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/


  // Handle the output of the Ask a Question, Review, and Tell a Friend forms
  $from_name = '';
  $from_email_address = '';
  $message = '';
  $rating = '';
  $review = '';
  $to_name = '';
  $to_email_address = '';
  $customers_full_name = '';
  $customers_firstname = '';
  $customers_lastname = '';
  $email_intro = '';
  $email_link = '';
  $email_sig = '';

  // Used to block spambots
  $address = '';
  $city = '';
  $reviewer_email = '';
  $website = '';

  if (isset($_GET['action']) && ($_GET['action'] == 'process') && ($_GET['products_id'] > 0) ) {
    $products_query_raw = "
      select
        pd.products_name,
        p.products_model
      from
        " . TABLE_PRODUCTS . " p,
        " . TABLE_PRODUCTS_DESCRIPTION . " pd
      where
            p.products_id = '" . (int) $_GET['products_id'] . "'
        and pd.products_id = '" . (int) $_GET['products_id'] . "'
        and pd.language_id = '" . (int) $languages_id . "'
    ";
    $products_query = tep_db_query ($products_query_raw);
    $products_data = tep_db_fetch_array ($products_query);

    $error = false;
    if (isset($_GET['tab']) && ($_GET['tab'] != '') ) {
      switch ($_GET['tab']) {
        case '9': // review tab
          $form_type = 'review';
          $rating = tep_db_prepare_input ($_POST['rating']);
          $review = tep_db_prepare_input ($_POST['review']);
          $reviewer_email = $_POST['reviewer_email'];
          $website = $_POST['website'];
          $customers_firstname = $customers_full_name = tep_db_prepare_input ($_POST['customers_firstname']);
          if ($_POST['customers_lastname'] != '') {
            $customers_lastname = ' ' . tep_db_prepare_input ($_POST['customers_lastname']);
            $customers_full_name = $customers_firstname . ' ' . $customers_lastname;
          }

          $error = false;
          if (strlen ($review) < REVIEW_TEXT_MIN_LENGTH) {
            $error = true;
            $messageStack->add_session ('review', JS_REVIEW_TEXT);
          }

          if (($rating < 1) || ($rating > 5)) {
            $error = true;
            $messageStack->add_session ('review', JS_REVIEW_RATING);
          }

          if ($error == false) {
            if( isset( $reviewer_email ) && $reviewer_email == '' && isset( $website ) && $website == '' && $customers_firstname != '' ) {
              $ipaddress = tep_get_ip_address();

              if (tep_session_is_registered ('customer_id') ) {
                tep_db_query ("
                  insert into
                    " . TABLE_REVIEWS . " (
                      products_id,
                      customers_id,
                      customers_name,
                      reviews_rating,
                      date_added,
                      approved,
                      ipaddress
                    )
                    values (
                      '" . (int)$_GET['products_id'] . "',
                      '" . (int) $customer_id . "',
                      '" . $customers_full_name . "',
                      '" . tep_db_input ($rating) . "',
                      now(),
                      '0',
                      '" . $ipaddress . "'
                   )
                ");
              } else {
                tep_db_query ("
                  insert into
                    " . TABLE_REVIEWS . " (
                      products_id,
                      customers_name,
                      reviews_rating,
                      date_added,
                      approved,
                      ipaddress
                    ) values (
                      '" . (int)$_GET['products_id'] . "',
                      '" . $customers_full_name . "',
                      '" . tep_db_input ($rating) . "',
                      now(),
                      '0',
                      '" . $ipaddress . "'
                   )
                ");
              }
              $insert_id = tep_db_insert_id();

              tep_db_query ("insert into " . TABLE_REVIEWS_DESCRIPTION . " (reviews_id, languages_id, reviews_text) values ('" . (int)$insert_id . "', '" . (int)$languages_id . "', '" . tep_db_input($review) . "')");

              // Send an email to the owner when a review is pending
              $email_content = EMAIL_TEXT_REVIEW_APPROVAL . 'http://www307.pair.com/adssinc/samples/adss/reviews.php' . "\n\n" .
                               EMAIL_SEPARATOR . "\n" .
                               EMAIL_TEXT_PRODUCT . $product_info['products_name'] . "\n" .
                               EMAIL_TEXT_CUSTOMERS_NAME . $customers_full_name . "\n" .
                               EMAIL_TEXT_CUSTOMERS_REVIEW . "\n"  . $review . "\n" .
                               EMAIL_TEXT_CUSTOMERS_RATING . $rating . "\n" .
                               EMAIL_SEPARATOR . "\n";
              tep_mail (STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, EMAIL_TEXT_SUBJECT, $email_content, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);

            }
            tep_redirect (tep_href_link (FILENAME_PRODUCT_INFO, tep_get_all_get_params (array ('action', 'tab') ) . 'tab=' . $form_type . '&action=success') );
          }
          break;

        case '11': // friend tab
          $form_type = 'friend';
          $from_name = tep_db_prepare_input ($_POST['from_name']);
          $from_email_address = tep_db_prepare_input ($_POST['from_email_address']);
          $to_name = tep_db_prepare_input ($_POST['to_name']);
          $to_email_address = tep_db_prepare_input ($_POST['to_email_address']);
          $email_subject = sprintf (TEXT_FRIEND_EMAIL_SUBJECT, $from_name, $products_data['products_name']);
          $email_link = sprintf (TEXT_ASK_EMAIL_LINK, tep_href_link (FILENAME_PRODUCT_INFO, 'products_id=' . $_GETS['products_id']) ) . "\n\n";
          $email_sig = sprintf (TEXT_FRIEND_EMAIL_SIGNATURE, $from_name) . "\n";
          $message_success = sprintf (TEXT_FRIEND_EMAIL_SUCCESSFUL_SENT, $to_name);

          if (empty ($from_name) ) {
            $error = true;
            $messageStack->add_session ($form_type, ERROR_FROM_NAME);
          }

          if (!tep_validate_email ($from_email_address)) {
            $error = true;
            $messageStack->add_session ($form_type, ERROR_FROM_ADDRESS);
          }

          if (empty ($to_name) ) {
            $error = true;
            $messageStack->add_session ($form_type, ERROR_TO_NAME);
          }

          if (!tep_validate_email ($to_email_address)) {
            $error = true;
            $messageStack->add_session ($form_type, ERROR_TO_ADDRESS);
          }

          if ($error == false) {
            $message = tep_db_prepare_input ($_POST['message']) . "\n\n";
            $email_body = $email_intro . $message . $email_link . $email_sig . "\n\n";

            tep_mail ($to_name, $to_email_address, $email_subject, $email_body, $from_name, $from_email_address);

            $messageStack->add_session ($form_type, $message_success, 'success');

            tep_redirect (tep_href_link (FILENAME_PRODUCT_INFO, tep_get_all_get_params (array ('action', 'tab') ) . 'tab=' . $form_type) );
          } // if ($error == false)
          break;

        case '10':  // ask tab
        default:
          $form_type = 'ask';  // Default Ask a Question form
          $to_name = STORE_OWNER;
          $to_email_address = STORE_OWNER_EMAIL_ADDRESS;
          $from_name = tep_db_prepare_input ($_POST['from_name']);
          $from_email_address = tep_db_prepare_input ($_POST['from_email_address']);
          $from_state = tep_db_prepare_input ($_POST['from_state']);
          $from_company = tep_db_prepare_input ($_POST['from_company']);
          $from_phone = tep_db_prepare_input ($_POST['from_phone']);
          $from_extension = tep_db_prepare_input ($_POST['from_extension']);

          $email_subject = sprintf (TEXT_ASK_EMAIL_SUBJECT, $from_name);
          $email_intro = sprintf (TEXT_ASK_EMAIL_INTRO, $products_data['products_name'], $products_data['products_model']) . "\n\n";
          $email_customer = TEXT_ASK_EMAIL_FROM . $from_name . "\n" . $from_company . "\n" . $from_state . "\n" . $from_email_address . "\n" . $from_phone . "\n\n";
          $email_link = "\n\n" . sprintf (TEXT_ASK_EMAIL_LINK, tep_href_link (FILENAME_PRODUCT_INFO, 'products_id=' . (int) $_GET['products_id']) ) . "\n\n\n";
          $email_sig = sprintf (TEXT_ASK_EMAIL_SIGNATURE, STORE_NAME . "\n" . HTTP_SERVER . DIR_WS_CATALOG . "\n");
          $message_success = sprintf (TEXT_ASK_EMAIL_SUCCESSFUL_SENT, $products_data['products_name']);

          if (empty ($from_name) ) {
            $error = true;
            $messageStack->add_session ($form_type, ERROR_FROM_NAME);
          }

          if (!tep_validate_email ($from_email_address)) {
            $error = true;
            $messageStack->add_session ($form_type, ERROR_FROM_ADDRESS);
          }

          if (empty ($from_state) ) {
            $error = true;
            $messageStack->add_session ($form_type, ERROR_FROM_STATE);
          }

          if (empty ($to_name) ) {
            $error = true;
            $messageStack->add_session ($form_type, ERROR_TO_NAME);
          }

          if (!tep_validate_email ($to_email_address)) {
            $error = true;
            $messageStack->add_session ($form_type, ERROR_TO_ADDRESS);
          }

          if ($error == false) {
            $message = tep_db_prepare_input ($_POST['message']) . "\n\n";
            $email_body = $email_intro . $email_customer . $message . $email_link . $email_sig . "\n\n";


            // Spambot trap: If the hidden fields exist and are null, send normally, otherwise fail silently.
            if (isset($_POST['city']) &&
                $_POST['city'] == '' &&
                isset($_POST['address']) &&
                $_POST['address'] == '') {

              tep_mail ($to_name, $to_email_address, $email_subject, $email_body, $from_name, $from_email_address);
            } //End spambot trap

            $messageStack->add_session ($form_type, $message_success, 'success');

            tep_redirect (tep_href_link (FILENAME_PRODUCT_INFO, tep_get_all_get_params (array ('action', 'tab') ) . 'tab=' . $form_type) );
          } // if ($error == false)
          break;
      } // switch ($_GET['tab']
    } // if (isset($_GET['tab']

  } elseif (tep_session_is_registered ('customer_id') ) {
    $account_query = tep_db_query ("select customers_firstname,
                                           customers_lastname,
                                           customers_email_address
                                   from " . TABLE_CUSTOMERS . "
                                   where customers_id = '" . (int) $customer_id . "'
                                 ");
    $account = tep_db_fetch_array($account_query);

    $from_name = tep_db_input ($customer['customers_firstname']) . ' ' . tep_db_input ($customer['customers_lastname']);
    $from_email_address = $account['customers_email_address'];
  }

?>
