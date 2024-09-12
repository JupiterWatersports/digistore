<head><meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"></head>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="css/bootstrap-grid.css">

<?php
/*
  $Id: header.php,v 1.19 2002/04/13 16:11:52 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
  
  
*/

  //BOF Down for Maintenance Mod
if (DOWN_FOR_MAINTENANCE == 'true') {
    $messageStack->add(TEXT_ADMIN_DOWN_FOR_MAINTENANCE, 'warning');
  }
 //EOF Down for Maintenance Mod

  if ($messageStack->size > 0) {
    echo $messageStack->output();
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

  if ($messageStack->size > 0) {
    echo $messageStack->output();
  }

  $check_admin_query = tep_db_query("SELECT * from admin where admin_id = '".$login_id."'"); 
  $check_admin = tep_db_fetch_array($check_admin_query);
  $readonly = true;
?> 
<div class="col-xs-12">
    <div class="row">
    <div class="admin-logo column-md-4 column-lg-5">
        <a href="index.php"><img src="images/jup-kitepaddlewake-black.png" border="0" /></a>
    </div>

<div class="admin-upper-right column-md-8 column-lg-7" id="admin-upper-right">		
    <ul>
       <li id="create-order-kiteshop" class="admin-upper-link">
    <form name="create_order" action="https://jupiterkiteboarding.com/store/assend/create_order_process.php" method="post" onsubmit="return check_form(this);" id="create_order">
  <div class="header_content">
 		
		 <input type="hidden" name="delivery_location" value="1">
  		 <input type="hidden" name="customers_id" value="6732">
 		 <input type="hidden" name="customers_firstname" value="kite">
         <input type="hidden" name="customers_lastname" value="shop">
         <input type="hidden" name="customers_email_address" value="kite@shop.com">
         <input type="hidden" name="customers_password" >
         <input type="hidden" name="customers_newsletter" value="1" id="customers_newsletter">
         <input type="hidden" name="entry_company" class="form-control">
         <input type="hidden" name="entry_street_address" value="1500 N US HWY 1">
         <input type="hidden" name="entry_suburb">
         <input type="hidden" name="entry_city" value="jupiter">
         <input type="hidden" name="entry_state" value="Florida (Palm Beach County)">
         <input type="hidden" name="entry_postcode" value="33469">
	     <input type="hidden" name="entry_country" value="223">
         <input type="hidden" name="customers_telephone" value="561-427-0240">	
  
  
	  <button style="border: none; background-color: transparent; padding: 0px;"><i class="fa fa-flag-checkered"></i></button></div>
	<div style=" padding-bottom:0px;"><a style="font-size:11px; font-weight:normal">Kite Shop Order</a></div>
   </form>
    </li>
    <li id="create-order" class="admin-upper-link">
  <div class="header_content"><a href="<?php echo tep_href_link(FILENAME_CREATE_ORDER) ?>"><i class="fa fa-credit-card"></i></a></div>
	<div style=" padding-bottom:0px;"><a style="font-weight:normal; " href="<?php echo tep_href_link(FILENAME_CREATE_ORDER)?>">Create Order</a></div>	
    </li>
       
        <li id="order" class="admin-upper-link">
      <div class="header_content"><a href="<?php echo tep_href_link(FILENAME_ORDERS)?>"><i class="fa fa-shopping-cart"></i></a></div>
	<div style="padding-bottom:0px;"><a style="font-weight:normal; " href="<?php echo tep_href_link(FILENAME_ORDERS)?>"><?php echo BOX_CUSTOMERS_ORDERS ?></a></div>
    	</li>
       
          <li id="customers" class="admin-upper-link">
        <div class="header_content"><a href="<?php echo tep_href_link(FILENAME_CATEGORIES)?>"><i class="fa fa-building-o"></i></a></div>
	<div style="padding-bottom:0px;"><a style="font-weight:normal;" href="<?php echo tep_href_link(FILENAME_CATEGORIES)?>"><?php echo 'Products' ?></a></div></li>
      
      <li id="customers" class="admin-upper-link">
        <div class="header_content"><a href="<?php echo tep_href_link('stockcheck.php?action=go')?>"><i class="fa fa-question"></i></a></div>
	<div style="padding-bottom:0px;"><a style="font-weight:normal;" href="<?php echo tep_href_link('stockcheck.php')?>"><?php echo 'Stock Check' ?></a></div></li>
      
             <li id="mail" class="admin-upper-link">
    	 <div class="header_content"><a href="<?php echo tep_href_link(FILENAME_GOOGLE_FEED)?>"><i class="fa fa-rss"></i></a></div>
	<div style="padding-bottom:0px;"><a style="font-weight:normal;" href="<?php echo tep_href_link(FILENAME_GOOGLE_FEED)?>"><?php echo 'Google Product<br>Feed' ?></a></div></li> 
    
    <li id="newsletter-manager" class="admin-upper-link">
    	<div class="header_content"><a href="http://mailchimp.com/" target="_blank"><i class="fa fa-mail-chimp"><img src="images/Freddie_OG.png"/></i></a></div>
	<div style="padding-bottom:0px;"><a style="font-weight:normal;" href="http://mailchimp.com/" target="_blank"><?php echo 'Mail Chimp' ?></a></div></li>
       
        <li id="online-catalog" class="admin-upper-link">
<div class="header_content"><a href="http://www.jupiterkiteboarding.com/store/" target="_blank"><i class="fa fa-globe"></i></a></div>
	<div style="padding-bottom:0px;"><?php echo ' <a style="font-weight:normal;" href="http://www.jupiterkiteboarding.com/store/" target="_blank" ><span class="text" >' . 'View Store' . '</span></a> '; ?></div>
    </li>
    <div class="cf"></div>
    </ul>
    <div class="cf"></div>
    </div>                  


       
<?php
if ((!preg_match("/customers/", $PHP_SELF)) && (!preg_match("/categories.php/", $PHP_SELF))) { ?>
<script src="ext/jquery/jquery.js"></script>
<?php } ?>


		            
<div id="header-search-boxes">
        <a class="menu-link" href="#menu">Menu</a>
		
<div class="search-inner form-group">
<?php if (!preg_match("/customers.php/", $PHP_SELF)) {  ?>
<div id="cust-form"><input type="text" name="search" id="search" class="form-control" value="Search Customers" onfocus="if(this.value == 'Search Customers') { this.value = ''; }" onblur="if(this.value == '') { this.value = 'Search Customers'; }" autocomplete="off"><div id="HeadresultsContainer"></div>
</div> 
<?php } ?>
 


<div id="prod-form">
<input type="text"  size="20" name="searchbox" id="searchbox2" class="form-control" value="Search Products" onfocus="if(this.value == 'Search Products') { this.value = ''; }" onblur="if(this.value == '') { this.value = 'Search Products'; }"  autocomplete="off">

<div id="ProdresultsContainer" style=""></div></div>





<?php echo tep_draw_form('orders', FILENAME_ORDERS, '', 'get'); ?>
                <div id="orderid-form"><input type="text" id="oID" name="oID" value="Order id" class="form-control" onfocus="if(this.value == 'Order id') { this.value = ''; }" onblur="if(this.value == '') { this.value = 'Order id'; }"><?php echo tep_draw_hidden_field('action', 'edit'); ?><input type="submit" value="Submit" style="display:none"></div>
              <?php echo tep_hide_session_id(); ?></form>

 

  
  </div> </div> 	

 </div>   
   
   <table width="100%"><tr >
    <td width="100%"  background="images/chromebg_3.gif" height="31" class="headerbar"><?php  include FILENAME_ADVANCED_MENU; ?></td>
  </tr>
</table>

<div id="popupcalendar"></div>




