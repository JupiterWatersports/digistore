<head><meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"></head>

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

    if( $check_admin['admin_groups_id'] == '6' || $check_admin['admin_groups_id'] == '1' ){ ?>
      <script>var admin_override = false;</script>
    <?php }else{ ?>
      <script>var admin_override = true;</script>
    <?php }
 ?>
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<div class="col-xs-12">
    <div class="row">
    <div class="admin-logo column-md-4 column-lg-5">
        <a href="index.php"><img src="images/jup-kitepaddlewake-black.png" border="0" /></a>
    </div>

   <div class="admin-upper-right column-md-8 column-lg-7" id="admin-upper-right">
    <ul>
       <li id="create-order-kiteshop" class="admin-upper-link">
    <form name="create_order" action="https://www.jupiterkiteboarding.com/store/assend/create_order_process.php" method="post" onsubmit="return check_form(this);" id="create_order">
  <div class="header_content">
 		<input type="hidden" name="customers_create_type" value="existing">
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
    	<div class="header_content"><a href="http://mailchimp.com/" target="_blank" rel="noreferrer"><i class="fa fa-mail-chimp"><img src="images/Freddie_OG.png"/></i></a></div>
	<div style="padding-bottom:0px;"><a style="font-weight:normal;" href="http://mailchimp.com/" target="_blank" rel="noreferrer"><?php echo 'Mail Chimp' ?></a></div></li>
       
        <li id="online-catalog" class="admin-upper-link">
<div class="header_content"><a href="https://www.jupiterkiteboarding.com/store/" target="_blank"><i class="fa fa-globe"></i></a></div>
	<div style="padding-bottom:0px;"><?php echo ' <a style="font-weight:normal;" href="https://www.jupiterkiteboarding.com/store/" target="_blank" ><span class="text" >' . 'View Store' . '</span></a> '; ?></div>
    </li>
    <div class="cf"></div>
    </ul>
    <div class="cf"></div>
    </div>                  

        </div>
    </div>

       
<?php
if ((!preg_match("/customers/", $PHP_SELF)) && (!preg_match("/categories.php/", $PHP_SELF))) { ?>
<script src="ext/jquery/jquery.js"></script>
<?php } ?>


		            
<div id="header-search-boxes">
        <a class="menu-link" href="#menu">Menu</a>
		
<div class="search-inner form-group">
<?php if (!preg_match("/customers.php/", $PHP_SELF)) {  ?>
<div id="cust-form"><input type="text" name="search" id="head-cust-search" class="form-control" value="Search Customers" onfocus="if(this.value == 'Search Customers') { this.value = ''; }" onblur="if(this.value == '') { this.value = 'Search Customers'; }" autocomplete="off"><div id="HeadresultsContainer"></div>
</div> 
<?php } ?>
 


<div id="prod-form">
<input type="text"  size="20" name="searchbox" id="head-prod-search" class="form-control" value="Search Products" onfocus="if(this.value == 'Search Products') { this.value = ''; }" onblur="if(this.value == '') { this.value = 'Search Products'; }"  autocomplete="off">

<div id="ProdresultsContainer" style=""></div></div>





<?php echo tep_draw_form('orders', FILENAME_ORDERS, '', 'get'); ?>
                <div id="orderid-form"><input type="text" id="oID" name="oID" value="Order id" class="form-control" onfocus="if(this.value == 'Order id') { this.value = ''; }" onblur="if(this.value == '') { this.value = 'Order id'; }"><?php echo tep_draw_hidden_field('action', 'edit'); ?><input type="submit" value="Submit" style="display:none"></div>
              <?php echo tep_hide_session_id(); ?></form>

 

  
  </div> </div> 	

             <nav id="menu" class="menu">        
			<ul>
	                    
<li class="has-submenu"><a href="#">Setup</a>
<ul class="sub-menu">                         
<li><a href="<?php echo tep_href_link('configuration.php?gID=73'); ?>">Accept Terms and Conditions</a></li>
<li><a href="<?php echo tep_href_link('configuration.php?gID=736'); ?>">Anti-Robot</a></li>
<li><a href="<?php echo tep_href_link('banner_manager.php'); ?>">Banner Manager</a></li>
<li><a href="<?php echo tep_href_link('configuration.php?gID=11'); ?>">Cache</a></li>
<li><a href="<?php echo tep_href_link('configuration.php?gID=5'); ?>">Customer Details</a></li>
<li class="has-submenu"><a href="">Digiadmin Users</a>
<ul>
<li><a href="<?php echo FILENAME_ADMIN_ACCOUNT ?>">Admin Account</a></li>
<li><a href="<?php echo FILENAME_ADMIN_MEMBERS ?>">Admin Users</a></li>
<li><a href="<?php echo FILENAME_ADMIN_FILES ?>">File Access</a></li>
</ul>
</li>
<li><a href="<?php echo tep_href_link('configuration.php?gID=13'); ?>">Download</a></li>
<li><a href="<?php echo tep_href_link('configuration.php?gID=14'); ?>">GZip Compression</a></li>
<li><a href="<?php echo tep_href_link('configuration.php?gID=543'); ?>">Header Tags Seo</a></li>
<li><a href="<?php echo tep_href_link('homepagead.php'); ?>">Homepage Advert</a></li>
<li><a href="<?php echo tep_href_link('configuration.php?gID=4'); ?>">Images Setup</a></li>
<li><a href="<?php echo tep_href_link('infobox_configuration.php?gID=1'); ?>">Infobox Admin</a></li>
<li><a href="<?php echo tep_href_link('configuration.php?gID=10'); ?>">Logging</a></li>
<li><a href="<?php echo tep_href_link('configuration.php?gID=206'); ?>">Mailchimp Newsletter</a></li>
<li><a href="<?php echo tep_href_link('configuration.php?gID=2'); ?>">Minimum Values</a></li>
<li><a href="<?php echo tep_href_link('configuration.php?gID=3'); ?>">Maximum Values</a></li>
<li class="has-submenu"><a href="">Modules</a>
<ul>
<li><a href="<?php echo tep_href_link('modules.php?set=ordertotal'); ?>">Order Total</a></li>
<li><a href="<?php echo tep_href_link('modules.php?set=payment'); ?>">Payment</a></li>
<li><a href="<?php echo tep_href_link('modules.php?set=shipping'); ?>">Shipping</a></li>
<li><a href="<?php echo tep_href_link('modules.php?set=shipping_labels'); ?>">Shipping Labels</a></li>
</ul>
</li>
<li><a href="<?php echo tep_href_link('configuration.php?gID=1'); ?>">My Store</a></li>
<li><a href="<?php echo tep_href_link('configuration.php?gID=7878'); ?>">OSC viphone</a></li>
<li><a href="<?php echo tep_href_link('configuration.php?gID=26229'); ?>">Page Cache</a></li>
<li class="has-submenu"><a href="">Paypal</a>
	<ul>
        <li><a href="<?php echo tep_href_link('paypal.php?action=balance'); ?>">Balance</a></li>
        <li><a href="<?php echo tep_href_link('paypal.php?action=configure'); ?>">Configure</a></li>
        <li><a href="<?php echo tep_href_link('paypal.php?action=credentials'); ?>">Credentials</a></li>
        <li><a href="<?php echo tep_href_link('paypal.php?action=log'); ?>">Log</a></li>
    </ul>
</li>	
<li><a href="<?php echo tep_href_link('configuration.php?gID=8'); ?>">Product Listing</a></li>
<li><a href="<?php echo tep_href_link('configuration.php?gID=111'); ?>">Recently Viewed Products</a></li>
<li><a href="<?php echo tep_href_link('configuration.php?gID=6501'); ?>">Recover Cart Sales</a></li>
<li><a href="<?php echo tep_href_link('configuration.php?gID=15'); ?>">Sessions</a></li>
<li><a href="<?php echo tep_href_link('configuration.php?gID=50'); ?>">Shipping Labels</a></li>
<li><a href="<?php echo tep_href_link('configuration.php?gID=7'); ?>">Shipping/Packaging</a></li>
<li><a href="<?php echo tep_href_link('configuration.php?gID=9'); ?>">Stock Control</a></li>
</ul>
</li>

<li class="has-submenu"><a href="#">Catalog</a>
<ul class="sub-menu">   
<li><a href="<?php echo FILENAME_CATEGORIES ?>">Categories Products</a></li>
<li><a href="<?php echo tep_href_link('xsell.php'); ?>">Cross Sell Products</a></li>
<li><a href="<?php echo tep_href_link('discount_codes.php'); ?>">Discount Codes</a></li>
<li><a href="<?php echo tep_href_link('extra_values.php'); ?>">Extra Field Values</a></li>
<li><a href="<?php echo tep_href_link('extra_fields.php'); ?>">Extra Product Fields</a></li>
<li><a href="<?php echo tep_href_link('get_1_free.php'); ?>">Get 1 Free</a></li>
<li><a href="<?php echo tep_href_link('manufacturers.php'); ?>">Manufacturers</a></li>
<li><a href="<?php echo tep_href_link('products_multi.php'); ?>">Multiple Products Manager</a></li>
<li><a href="<?php echo tep_href_link('configuration.php?gID=17'); ?>">Per Product Discount Setup</a></li>
<li><a href="<?php echo tep_href_link('products_attributes.php'); ?>">Products Attributes</a></li>
<li><a href="<?php echo tep_href_link('products_expected.php'); ?>">Products Expected</a></li>
<li><a href="<?php echo tep_href_link('product_types.php'); ?>">Product Types</a></li>

<li><a href="<?php echo tep_href_link('reviews.php'); ?>">Reviews</a></li>
<li><a href="<?php echo FILENAME_RELATED_PRODUCTS ?>">Related Products</a></li>
<li><a href="<?php echo tep_href_link('specials.php'); ?>">Specials</a></li>
<li><a href="<?php echo tep_href_link('products_sorter.php'); ?>">Sort Order</a></li>
</ul>
</li>

<li class="has-submenu"><a href="#">Customers</a>
<ul class="sub-menu">
<li><a href="<?php echo tep_href_link('batch_print.php'); ?>">Batch Print Center</a></li>
<li><a href="<?php echo tep_href_link('stats_inactive_user.php'); ?>">Inactive User</a></li>
<li><a href="<?php echo tep_href_link('customers.php'); ?>">View Customers</a></li>
</ul>
</li>

<li class="has-submenu"><a href="#">Orders</a>
<ul class="sub-menu">
<li><a href="<?php echo tep_href_link('create_order.php'); ?>">Create Order</a></li>
<li><a href="<?php echo tep_href_link('configuration.php?gID=72'); ?>">Order Editor Setup</a></li>
<li><a href="<?php echo tep_href_link('orders_status.php'); ?>">Order Status</a></li>
<li><a href="<?php echo tep_href_link('client_search.php'); ?>">Orders by Product</a></li>
<li><a href="<?php echo tep_href_link('orders.php?status=1'); ?>">View Pending</a></li>
<li><a href="<?php echo tep_href_link('orders.php?status=2'); ?>">View Processing</a></li>
<li><a href="<?php echo tep_href_link('orders.php?status=3'); ?>">View Delivered</a></li>
<li><a href="<?php echo tep_href_link('board_checkout.php'); ?>">View Board Checkouts</a></li>
</ul>
</li>

<li class="has-submenu"><a href="#">Email</a>
<ul class="sub-menu">
<li><a href="<?php echo tep_href_link('configuration.php?gID=12'); ?>">E-Mail Options</a></li>
<li><a href="<?php echo tep_href_link('newsletters.php'); ?>">Newsletter Manager</a></li>
<li><a href="<?php echo tep_href_link('mail.php'); ?>">Send Email</a></li>
</ul></li>

<li class="has-submenu"><a href="#">Localization</a>
<ul class="sub-menu">
<li><a href="<?php echo tep_href_link('countries.php'); ?>">Countries</a></li>
<li><a href="<?php echo tep_href_link('currencies.php'); ?>">Currencies</a></li>
<li><a href="<?php echo tep_href_link('languages.php'); ?>">Languages</a></li>
<li><a href="<?php echo tep_href_link('tax_classes.php'); ?>">Tax Classes</a></li>
<li><a href="<?php echo tep_href_link('tax_rates.php'); ?>">Tax Rates</a></li>
<li><a href="<?php echo tep_href_link('geo_zones.php'); ?>">Tax Zones</a></li>
<li><a href="<?php echo tep_href_link('zones.php'); ?>">Zones</a></li>
</ul></li>

<li class="has-submenu"><a href="#">Reports</a>
<ul class="sub-menu">
<li><a href="<?php echo tep_href_link('stats_low_stock_attributes.php'); ?>">Attributes Low Stock Report</a></li>
<li><a href="<?php echo tep_href_link('stats_customers.php'); ?>">Customer Orders Total</a></li>
<li><a href="<?php echo tep_href_link('daily_sales_report.php'); ?>">Daily Sales Report</a></li>
<li><a href="<?php echo tep_href_link('daily_sales_report-accounting.php'); ?>">Daily Sales Report (Accounting)</a></li>		
	
<li><a href="<?php echo tep_href_link('stats_employee_sales_report.php'); ?>">Employee Sales Report</a></li>
<li><a href="<?php echo tep_href_link('inventory_cost.php'); ?>">Inventory Cost Report</a></li>
<li><a href="<?php echo tep_href_link('inventory_to_csv.php'); ?>">Inventory CSV</a></li>
<li><a href="<?php echo tep_href_link('inventory_price_margin.php'); ?>">Inventory Profit Margin CSV</a></li>
<li><a href="<?php echo tep_href_link('inventory_upc.php'); ?>">Inventory UPC CSV</a></li>
<li><a href="<?php echo tep_href_link('stats_keywords.php'); ?>">Keyword Searches</a></li>
<li><a href="<?php echo tep_href_link('stats_low_stock.php'); ?>">Low Stock Report</a></li>
<li><a href="<?php echo tep_href_link('stats_sales.php'); ?>">Monthly Sales</a></li>
<li><a href="<?php echo tep_href_link('stats_detailed_monthly_sales.php'); ?>">Monthly Sales/Tax</a></li>
<li><a href="<?php echo tep_href_link('stats_out_of_stock.php'); ?>">Out Of Stock Report</a></li>
<li><a href="<?php echo tep_href_link('completePrices.php'); ?>">Product Prices Total</a></li>
<li><a href="<?php echo tep_href_link('stats_products_purchased.php'); ?>">Products Purchased</a></li>
<li><a href="<?php echo tep_href_link('stats_products_viewed.php'); ?>">Products Viewed</a></li>
<li><a href="<?php echo tep_href_link('stats_recover_cart_sales.php'); ?>">Recovered Sales Results</a></li>
<li><a href="<?php echo tep_href_link('stats_sales_report2.php'); ?>">Sales Reports</a></li>
<li><a href="<?php echo tep_href_link('salesPerCategory.php'); ?>">Sales Reports (Category)</a></li>
<li><a href="<?php echo tep_href_link('taxes_page.php'); ?>">Taxes</a></li>
<li><a href="<?php echo tep_href_link('unpaid-orders.php'); ?>">Unpaid Orders</a></li>
    
    <?php if($check_admin['admin_groups_id'] == '6' || $check_admin['admin_groups_id'] == '7'){ ?>
<li class="has-submenu"><a>Transactions</a>
    <ul class="sub-menu">
    <li><a href="<?php echo tep_href_link('add_expense.php'); ?>">Add Expense</a></li>
    <li><a href="<?php echo tep_href_link('balance-sheet.php'); ?>">Balance Sheet</a></li>
    <li><a href="<?php echo tep_href_link('profit-loss-statement.php'); ?>">Profit Loss Statement</a></li>
   </ul>
</li>
    <?php } ?>

</ul></li>

<li class="has-submenu"><a href="#">Tools</a>
<ul class="sub-menu">
<li><a href="<?php echo tep_href_link('admin_notes.php'); ?>">Admin Notes</a></li>
<li><a href="<?php echo tep_href_link('cache.php'); ?>">Cache Control</a></li>
<li><a href="<?php echo tep_href_link('backup.php'); ?>">Database Backup</a></li>
<li><a href="<?php echo tep_href_link('define_language.php'); ?>">Define Languages</a></li>
<li><a href="<?php echo tep_href_link('configuration.php?gID=16'); ?>">Down for maintenance</a></li>
    
<li><a href="<?php echo tep_href_link('edit-menu-nav.php'); ?>">Edit Store Menu</a></li>  
<li><a href="<?php echo tep_href_link('file_manager.php'); ?>">File Manager</a></li>
<li><a href="<?php echo tep_href_link('googlesitemap.php'); ?>">Google SiteMaps</a></li>
<li><a href="<?php echo tep_href_link('google_feed.php'); ?>">Google Product Feed</a></li>
<li><a href="<?php echo tep_href_link('pages.php'); ?>">Page Editor</a></li>
<li><a href="<?php echo tep_href_link('basket.php?psw=5789&screen=T'); ?>">Paid but no order</a></li>
<li><a href="<?php echo tep_href_link('recover_cart_sales.php'); ?>">Recover Cart Sales</a></li>
<li><a href="<?php echo tep_href_link('barcode_config.php'); ?>">RMI Barcode</a></li>
<li class="has-submenu"><a href="">Security</a>
<ul>
<li><a href="<?php echo tep_href_link('configuration.php?gID=18'); ?>">Change Basket Password</a></li>
<li><a href="<?php echo tep_href_link('configuration.php?gID=6503'); ?>">FWR Security Pro</a></li>
<li><a href="<?php echo tep_href_link('sitemonitor_admin.php'); ?>">Site Monitor Admin</a></li>
<li><a href="<?php echo tep_href_link('sitemonitor_configure_setup.php'); ?>">Site Monitor Configure</a></li>
</ul>
</li>
<li><a href="<?php echo tep_href_link('server_info.php'); ?>">Server Info</a></li>
<li><a href="<?php echo tep_href_link('whos_online.php'); ?>">Who's Online</a></li>
</ul></li>

<li class="has-submenu"><a href="#">CMS Info Pages</a>
<ul class="sub-menu">
<li><a href="<?php echo tep_href_link('shopinfo.php?gID=1&selected_box=shopinfo'); ?>">Add New Page</a></li>

<?php
   $count=2;     
	if(!$languages_id) $languages_id=1;
   $infomenuquery = tep_db_query('SELECT si_id, si_heading FROM information WHERE language_id = ' . (int)$languages_id  . ' ORDER BY si_sort');
   $numrows = tep_db_num_rows($infomenuquery);
   $contents = array();
	while ($infomenu = tep_db_fetch_array($infomenuquery)) {
          echo $info_string = '<li><a href="' .tep_href_link('shopinfo.php', '&siid=' . $infomenu['si_id'] . '&selected_box=shopinfo').'">' .$infomenu['si_heading'].'</a></li>';
    $count=$count+1; 
      }  // while 
?>

</ul></li>

<li class="has-submenu"><a href="#">SEO</a>
<ul class="sub-menu">
<li><a href="<?php echo tep_href_link('header_tags_fill_tags.php'); ?>">Fill Tags</a></li>
<li><a href="<?php echo tep_href_link('configuration.php?gID=40'); ?>">Meta Tags</a></li>
<li><a href="<?php echo tep_href_link('header_tags_seo.php'); ?>">Page Control</a></li>
<li><a href="<?php echo tep_href_link('seo_assistant.php'); ?>">SEO Assistant</a></li>
<li><a href="<?php echo tep_href_link('configuration.php?gID=6506'); ?>">SEO URLs</a></li>
<li><a href="<?php echo tep_href_link('header_tags_seo_silo.php'); ?>">Silo Control</a></li>
<li><a href="<?php echo tep_href_link('header_tags_test.php'); ?>">Test</a></li>
<li><a href="<?php echo tep_href_link('configuration.php?gID=42'); ?>">URL Validation</a></li>
</ul></li>

<li class="has-submenu"><a href="#">Templates</a>
<ul class="sub-menu">
<li><a href="<?php echo tep_href_link('configuration.php?gID=31'); ?>">Default Template</a></li>
</ul></li>

<li class="has-submenu"><a href="#">Help</a>
<ul class="sub-menu">
<li><a target="_blank" href="<?php echo tep_href_link('help/MATC.htm'); ?>">Accept Terms & Conditions</a></li>
<li><a target="_blank" href="<?php echo tep_href_link('help/batch-Print-Center.htm'); ?>">Batch Print Center</a></li>
<li><a target="_blank" href="<?php echo tep_href_link('help/documentation/documentation.html'); ?>">Documentation</a></li>
<li><a target="_blank" href="<?php echo tep_href_link('help/fck_Instructions.htm'); ?>">FCK Editor</a></li>
<li><a target="_blank" href="<?php echo tep_href_link('help/faq.htm'); ?>">Frequently Asked Questions</a></li>
<li><a target="_blank" href="<?php echo tep_href_link('help/Security-Pro.htm'); ?>">FWR Secruity Pro</a></li>
<li><a target="_blank" href="<?php echo tep_href_link('help/Paid-but-no-order.htm'); ?>">Paid But No Order</a></li>
<li><a target="_blank" href="<?php echo tep_href_link('help/ship-pay-mods/ship-pay-mods.html'); ?>">Payment & Shipping Mods</a></li>
<li><a target="_blank" href="<?php echo tep_href_link('help/product_bundle.html'); ?>">Product Bundle</a></li>
<li><a target="_blank" href="<?php echo tep_href_link('help/get-1-free.htm'); ?>">Promotions get 1 free</a></li>
<li><a target="_blank" href="<?php echo tep_href_link('help/Recently-viewed.html'); ?>">Recently Viewed Products</a></li>
<li><a target="_blank" href="<?php echo tep_href_link('help/recover cart.htm'); ?>">Recover Cart Sales</a></li>
<li><a target="_blank" href="<?php echo tep_href_link('help/SEO Instructions.html'); ?>">SEO Urls</a></li>
<li><a target="_blank" href="<?php echo tep_href_link('help/downloads.htm'); ?>">Setting up Downloads</a></li>
<li><a target="_blank" href="<?php echo tep_href_link('help/Site-Monitor-Instructions.htm'); ?>">Site Monitor</a></li>
<li><a target="_blank" href="<?php echo tep_href_link('help/site-safety.htm'); ?>">Site Security</a></li>
<li class="has-submenu"><a href="">STS Template System</a>
<ul>
<li><a target="_blank" href="<?php echo tep_href_link('help/sts doc/module_default.html'); ?>">Default Module</a></li>
<li><a target="_blank" href="<?php echo tep_href_link('help/sts doc/module_index.html'); ?>">Index Module</a></li>
<li><a target="_blank" href="<?php echo tep_href_link('help/sts doc/module_product_info.html'); ?>">Product Module</a></li> 
<li><a target="_blank" href="<?php echo tep_href_link('help/sts-user-doc.htm'); ?>">User Guide</a></li>
</ul>
</li>
</ul></li>

<li class="has-submenu"><a href="#">Mail Manager</a>
<ul class="sub-menu">
<li><a target="_blank" href="<?php echo FILENAME_MM_BULKMAIL; ?>">BulkMail Manager</a></li>
<li><a target="_blank" href="<?php echo FILENAME_MM_RESPONSEMAIL; ?>">Response Mail</a></li>
<li><a target="_blank" href="<?php echo FILENAME_MM_EMAIL; ?>">Send Email</a></li>
<li><a target="_blank" href="<?php echo FILENAME_MM_TEMPLATES; ?>">Template Manager</a></li>
</ul>
</li>

</ul>
</nav>
   


<div id="popupcalendar"></div>




