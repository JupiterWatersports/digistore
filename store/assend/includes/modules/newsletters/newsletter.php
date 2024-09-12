<?php
/*
  $Id: newsletter.php 1739 2007-12-20 00:52:16Z hpdl $

  Digistore v4.0,  Open Source E-Commerce Solutions
  http://www.digistore.co.nz

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  class newsletter {
    var $show_choose_audience, $title, $content;

    function newsletter($title, $content) {
      $this->show_choose_audience = false;
      $this->title = $title;
      $this->content = $content;
    }

    function choose_audience() {
      return false;
    }

    function confirm() {
      global $HTTP_GET_VARS;

// copy e-mails to a temporary table if that table is empty
      
      $check_rows = tep_db_query("select * from " . TABLE_CUSTOMERS_TEMP);
      if (mysql_num_rows($check_rows) == 0) {

    $copy_query = tep_db_query("select customers_firstname, customers_lastname, customers_email_address from " . TABLE_CUSTOMERS . " where customers_newsletter = '1'");
	
	while($row = tep_db_fetch_array($copy_query)) {
		if (eregi('^[-a-z0-9._]+@([-a-z0-9_]+\.)+[a-z]{2,6}$',$row['customers_email_address'])) {
		tep_db_query("insert into " . TABLE_CUSTOMERS_TEMP . " (customers_firstname, customers_lastname, customers_email_address) VALUES ('" . addslashes($row['customers_firstname']) . "', '" . addslashes($row['customers_lastname']) . "', '" . $row['customers_email_address'] . "')");
		}
	}
      //echo "copy done.<br>";
      } else {
        //echo "no copy done.<br>";
	}
      
  // count e-mails in temp table	

       $mail_query = tep_db_query("select count(*) as count from " . TABLE_CUSTOMERS_TEMP);

      $mail = tep_db_fetch_array($mail_query);

      $confirm_string = '<table border="0" cellspacing="0" cellpadding="2">' . "\n" .
                        '  <tr>' . "\n" .
                        '    <td class="main"><font color="#ff0000"><b>' . sprintf(TEXT_COUNT_CUSTOMERS, $mail['count']) . '</b></font></td>' . "\n" .
                        '  </tr>' . "\n" .
                        '  <tr>' . "\n" .
                        '    <td>' . tep_draw_separator('pixel_trans.gif', '1', '10') . '</td>' . "\n" .
                        '  </tr>' . "\n" .
                        '  <tr>' . "\n" .
                        '    <td class="main"><b>' . $this->title . '</b></td>' . "\n" .
                        '  </tr>' . "\n" .
                        '  <tr>' . "\n" .
                        '    <td>' . tep_draw_separator('pixel_trans.gif', '1', '10') . '</td>' . "\n" .
                        '  </tr>' . "\n" .
                        '  <tr>' . "\n" .
                        '    <td class="main"><tt>' . nl2br($this->content) . '</tt></td>' . "\n" .
                        '  </tr>' . "\n" .
                        '  <tr>' . "\n" .
                        '    <td>' . tep_draw_separator('pixel_trans.gif', '1', '10') . '</td>' . "\n" .
                        '  </tr>' . "\n" .
                        '  <tr>' . "\n" .
                        '    <td align="right"><a href="' . tep_href_link(FILENAME_NEWSLETTERS, 'page=' . $HTTP_GET_VARS['page'] . '&nID=' . $HTTP_GET_VARS['nID'] . '&action=confirm_send') . '">' . tep_image_button('button_send.gif', IMAGE_SEND) . '</a> <a href="' . tep_href_link(FILENAME_NEWSLETTERS, 'page=' . $HTTP_GET_VARS['page'] . '&nID=' . $HTTP_GET_VARS['nID']) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a></td>' . "\n" .
                        '  </tr>' . "\n" .
                        '</table>';

      return $confirm_string;
    }

    function send($newsletter_id) {
       $mail_query = tep_db_query("select customers_firstname, customers_lastname, customers_email_address from " . TABLE_CUSTOMERS_TEMP . " limit " . BULKMAILER_LIMIT);

      $mimemessage = new email(array('X-Mailer: osCommerce bulk mailer'));
      //      $mimemessage->add_text($this->content);
      $mimemessage->add_html($this->content);
      $mimemessage->build_message();
      while ($mail = tep_db_fetch_array($mail_query)) {
        $mimemessage->send(stripslashes($mail['customers_firstname']) . ' ' . stripslashes($mail['customers_lastname']), $mail['customers_email_address'], '', EMAIL_FROM, $this->title);

      }

      $newsletter_id = tep_db_prepare_input($newsletter_id);
      tep_db_query("update " . TABLE_NEWSLETTERS . " set date_sent = now(), status = '1' where newsletters_id = '" . tep_db_input($newsletter_id) . "'");
    }
  }
?>
