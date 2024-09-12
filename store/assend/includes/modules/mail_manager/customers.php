<?php
/*
  $Id: customers.php 1739 2007-12-20 00:52:16Z hpdl $
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Copyright (c) 2002 osCommerce
  Released under the GNU General Public License

NOTE: This is simply a copy of 'newsletters.php. Use this a model to write your own query that selects
your choice of customers or subscribers. If you change the name of any the variables on this page, you
will need to make cooresponding changes in mm_bulkmail.php. 

Simply rename the file and upload it to admin/includes/modules/mail_manager. It will automatically be listed in the 'target'
dropdown in the edit screen of each bulkmail email in the admin of the bulkmail manager.

*/



 switch ($action){
	case 'send';
 		//count the target group
 		$count_query = tep_db_query("select count(*) as count from " . TABLE_CUSTOMERS . " where customers_newsletter = '1' and mmstatus = '0' ");
 		$count = tep_db_fetch_array($count_query);
	break;
	
	case 'confirm_send';
	    //get the target group
	    $queue_query = tep_db_query("select count(*) as count from " . TABLE_CUSTOMERS . " where customers_newsletter = '1' and mmstatus = '0' ");		
 		$queue = tep_db_fetch_array($queue_query);
 		
 		//count remaining email addresses in  target group (number to be mailed).
 		$mailed_query = tep_db_query("select count(*) as count from " . TABLE_CUSTOMERS . " where customers_newsletter = '1' and mmstatus = '9' ");
 		$mailed = tep_db_fetch_array($mailed_query);
 		
		//count how many email addresses have been mailed.
		$mail_query = tep_db_query("select customers_firstname, customers_lastname, customers_email_address, customers_newsletter, mmstatus from " . TABLE_CUSTOMERS . " where customers_newsletter = '1' and mmstatus = '0' ");
 		$mail = tep_db_fetch_array($mail_query);

 	break;
 	}

?>
