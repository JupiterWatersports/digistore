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
?> 
    <div class="admin-logo"><a href="index.php"><img src="images/jup-kitepaddlewake.png" border="0" /></a></div>

    <div class="admin-upper-right">
    <ul>
    <li id="create-order" class="admin-upper-link">
  <a href="<?php echo tep_href_link(FILENAME_CREATE_ORDER) ?>"><img src="images/panel_06.gif" width="97" height="45" border="0" /></a>
	<div style=" padding-bottom:0px;"><a style="font-weight:normal; " href="<?php echo tep_href_link(FILENAME_CREATE_ORDER)?>">Create Order</a></div>	
    </li>
        <li id="order" class="admin-upper-link">
    <a href="<?php echo tep_href_link(FILENAME_ORDERS, 'selected_box=customers')?>"><img src="images/panel_01.gif" width="79" height="45" border="0" /></a>
	<div style="padding-bottom:0px;"><a style="font-weight:normal; " href="<?php echo tep_href_link(FILENAME_ORDERS, 'selected_box=customers')?>"><?php echo BOX_CUSTOMERS_ORDERS ?></a></div>
    	</li>
          <li id="customers" class="admin-upper-link">
        <a href="<?php echo tep_href_link(FILENAME_CUSTOMERS, 'selected_box=customers')?>"><img src="images/panel_02.gif" width="67" height="45" border="0" /></a>
	<div style="padding-bottom:0px;"><a style="font-weight:normal;" href="<?php echo tep_href_link(FILENAME_CUSTOMERS, 'selected_box=customers')?>"><?php echo BOX_CUSTOMERS_CUSTOMERS ?></a></div></li>
        <li id="newsletter-manager" class="admin-upper-link">
    	<a href="<?php echo tep_href_link(FILENAME_NEWSLETTERS, 'selected_box=tools')?>"><img src="images/panel_03.gif" width="79" height="45" border="0" /></a>
	<div style="padding-bottom:0px;"><a style="font-weight:normal;" href="<?php echo tep_href_link(FILENAME_NEWSLETTERS, 'selected_box=tools')?>"><?php echo BOX_TOOLS_NEWSLETTER_MANAGER ?></a></div></li>
      <li id="mail" class="admin-upper-link">
    	<a href="<?php echo tep_href_link(FILENAME_MAIL, 'selected_box=tools')?>"><img src="images/panel_04.gif" width="81" height="45" border="0" /></a>
	<div style="padding-bottom:0px;"><a style="font-weight:normal;" href="<?php echo tep_href_link(FILENAME_MAIL, 'selected_box=tools')?>"><?php echo BOX_TOOLS_MAIL ?></a></div></li>
        <li id="online-catalog" class="admin-upper-link">
    	<a href="<?php echo tep_catalog_href_link()?>" target="_blank"><img src="images/panel_05.gif" width="79" height="45" align="absbottom" border="0" /></a>
	<div style="padding-bottom:0px;"><?php echo ' <a style="font-weight:normal;" href="' . tep_catalog_href_link() . '" target="_blank" ><span class="text" >' . HEADER_TITLE_ONLINE_CATALOG . '</span></a> '; ?></div>
    </li>
    <div class="cf"></div>
    </ul>
    <div class="cf"></div>
    </div>                  




<link rel="stylesheet" type="text/css" href="css/jquery.sidr.light.css"  />
<script type="text/javascript" src="../assend/js/jquery.sidr.min.js"></script>


		            
<div id="header-search-boxes">
        <a class="menu-link" href="#menu">Menu</a>
		
<div class="search-inner">

<div id="prod-form">
<input type="text" style="width:140px;" id="searchbox" size="20" name="searchbox" value="Products Search Here" onfocus="if(this.value == 'Products Search Here') { this.value = ''; }" onblur="if(this.value == '') { this.value = 'Products Search Here'; }"  autocomplete="off">
</div>
<div id="ProdresultsContainer" style=""></div>
<script type='javascript'>
$('#oID').focus(function() { 
  $('this').val(''); 
});
</script>




<?php echo tep_draw_form('orders', FILENAME_ORDERS, '', 'get'); ?>
                <div id="orderid-form"><input style="width:80px; height:30px;" type="text" id="oID" name="oID" value="Order id" onfocus="if(this.value == 'Order id') { this.value = ''; }" onblur="if(this.value == '') { this.value = 'Order id'; }"><?php echo tep_draw_hidden_field('action', 'edit'); ?><input type="submit" value="Submit" style="display:none"></div>
              <?php echo tep_hide_session_id(); ?></form>
<div class="support-logout">		   
&nbsp; &nbsp; &nbsp; <a class="supportsite" href="<?PHP echo FILENAME_SUPPORT; ?>" target="_blank"><?PHP echo HEADER_TITLE_SUPPORT; ?></a> 
&nbsp; &nbsp;&nbsp; <a href="<?PHP echo FILENAME_SIGNOFF; ?>"><?PHP echo HEADER_TITLE_SIGNOFF; ?></a>
&nbsp; &nbsp; &nbsp; &nbsp;</div>
              <div class="lang-toggle"><?php echo tep_draw_form('adminlanguage', FILENAME_DEFAULT, '', 'get') . tep_draw_pull_down_menu('language', $languages_array, $languages_selected, 'onChange="this.form.submit();"') . tep_hide_session_id() . '</form>'; ?></div>
 

  
   <div class="cf"></div></div></div>
   <br />	

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
<li><a href="<?php echo tep_href_link('coupons.php'); ?>">Coupons</a></li>
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
<li><a href="<?php echo tep_href_link('quick_stockupdate.php'); ?>">Quick-Stock-Updater</a></li>
<li><a href="<?php echo tep_href_link('reviews.php'); ?>">Reviews</a></li>
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
<li><a href="<?php echo tep_href_link('stats_customers.php'); ?>">Customer Orders Total</a></li>
<li><a href="<?php echo tep_href_link('stats_keywords.php'); ?>">Keyword Searches</a></li>
<li><a href="<?php echo tep_href_link('stats_low_stock.php'); ?>">Low Stock Report</a></li>
<li><a href="<?php echo tep_href_link('stats_sales.php'); ?>">Monthly Sales</a></li>
<li><a href="<?php echo tep_href_link('stats_detailed_monthly_sales.php'); ?>">Monthly Sales/Tax</a></li>
<li><a href="<?php echo tep_href_link('stats_products_purchased.php'); ?>">Products Purchased</a></li>
<li><a href="<?php echo tep_href_link('stats_products_viewed.php'); ?>">Products Viewed</a></li>
<li><a href="<?php echo tep_href_link('stats_recover_cart_sales.php'); ?>">Recovered Sales Results</a></li>
<li><a href="<?php echo tep_href_link('stats_sales_report2.php'); ?>">Sales Reports</a></li>
</ul></li>

<li class="has-submenu"><a href="#">Tools</a>
<ul class="sub-menu">
<li><a href="<?php echo tep_href_link('cache.php'); ?>">Cache Control</a></li>
<li><a href="<?php echo tep_href_link('backup.php'); ?>">Database Backup</a></li>
<li><a href="<?php echo tep_href_link('define_language.php'); ?>">Define Languages</a></li>
<li><a href="<?php echo tep_href_link('configuration.php?gID=16'); ?>">Down for maintenance</a></li>
<li><a href="<?php echo tep_href_link('file_manager.php'); ?>">File Manager</a></li>
<li><a href="<?php echo tep_href_link('googlesitemap.php'); ?>">Google SiteMaps</a></li>
<li><a href="<?php echo tep_href_link('google_feed.php'); ?>">Google Product Feed</a></li>
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
   
   <table width="100%"><tr >
    <td width="100%"  background="images/chromebg_3.gif" height="31" class="headerbar"><?php  include FILENAME_ADVANCED_MENU; ?></td>
  </tr>
</table>

<div id="popupcalendar"></div>




