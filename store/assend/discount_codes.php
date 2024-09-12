<?php
/*
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

  Discount Code 3.1
  http://high-quality-php-coding.com/
*/

  require('includes/application_top.php');

  $action = (isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : '');

  if (tep_not_null($action)) {
    switch ($action) {
      case 'setflag':
        tep_db_query("update " . TABLE_DISCOUNT_CODES . " set status = '" . (int)$HTTP_GET_VARS['flag'] . "' where discount_codes_id = '" . (int)$HTTP_GET_VARS['dID'] . "' limit 1");

        tep_redirect(tep_href_link(FILENAME_DISCOUNT_CODES, 'page=' . $HTTP_GET_VARS['page']));
        break;
      case 'insert':
        if (!empty($HTTP_POST_VARS['discount_codes']) && !empty($HTTP_POST_VARS['discount_values'])) {
          $sql_data_array = array('products_id' => '',
                                  'categories_id' => '',
                                  'manufacturers_id' => '',
                                  'excluded_products_id' => '',
                                  'customers_id' => '',
                                  'orders_total' => '0',
                                  'order_info' => (int)$HTTP_POST_VARS['order_info'],
                                  'exclude_specials' => (int)$HTTP_POST_VARS['exclude_specials'],
                                  'discount_codes' => tep_db_prepare_input($HTTP_POST_VARS['discount_codes']),
								  'coupons_notice' => tep_db_prepare_input($HTTP_POST_VARS['coupons_notice']),
                                  'discount_values' => tep_db_prepare_input($HTTP_POST_VARS['discount_values']),
                                  'minimum_order_amount' => tep_db_prepare_input($HTTP_POST_VARS['minimum_order_amount']),
                                  'expires_date' => empty($HTTP_POST_VARS['day']) && empty($HTTP_POST_VARS['month']) && empty($HTTP_POST_VARS['year']) ? '0000-00-00' : tep_db_prepare_input($HTTP_POST_VARS['year']) . '-' . tep_db_prepare_input($HTTP_POST_VARS['month']) . '-' . tep_db_prepare_input($HTTP_POST_VARS['day']),
                                  'number_of_use' => (int)$HTTP_POST_VARS['number_of_use'],
                                  'number_of_products' => 0);

          $error = true;
          if ((int)$HTTP_POST_VARS['applies_to'] == 1) {
            if (is_array($HTTP_POST_VARS['products_id']) && sizeof($HTTP_POST_VARS['products_id']) > 0) {
              $sql_data_array['products_id'] = implode(',', $HTTP_POST_VARS['products_id']);
              $error = false;
            }
          } elseif ((int)$HTTP_POST_VARS['applies_to'] == 2) {
            if (is_array($HTTP_POST_VARS['categories_id']) && sizeof($HTTP_POST_VARS['categories_id']) > 0) {
              $sql_data_array['categories_id'] = implode(',', $HTTP_POST_VARS['categories_id']);
              $error = false;
            }
          } elseif ((int)$HTTP_POST_VARS['applies_to'] == 3) {
            $sql_data_array['orders_total'] = 1; // total
            $error = false;
          } elseif ((int)$HTTP_POST_VARS['applies_to'] == 4) {
            if (is_array($HTTP_POST_VARS['manufacturers_id']) && sizeof($HTTP_POST_VARS['manufacturers_id']) > 0) {
              $sql_data_array['manufacturers_id'] = implode(',', $HTTP_POST_VARS['manufacturers_id']);
              $error = false;
            }
          } elseif ((int)$HTTP_POST_VARS['applies_to'] == 5) {
            $sql_data_array['orders_total'] = 2; // subtotal
            $error = false;
          }

          if ((int)$HTTP_POST_VARS['applies_to'] == 2 || (int)$HTTP_POST_VARS['applies_to'] == 4) {
            if (is_array($HTTP_POST_VARS['excluded_products_id']) && sizeof($HTTP_POST_VARS['excluded_products_id']) > 0) {
              $sql_data_array['excluded_products_id'] = implode(',', $HTTP_POST_VARS['excluded_products_id']);
            }
          }

          if ((int)$HTTP_POST_VARS['applies_to'] != 3 && !empty($HTTP_POST_VARS['number_of_products'])) {
            $sql_data_array['number_of_products'] = (int)$HTTP_POST_VARS['number_of_products'];
          }

          if (!empty($HTTP_POST_VARS['customers']) && $HTTP_POST_VARS['customers'] == 1) {
            if (is_array($HTTP_POST_VARS['customers_id']) && sizeof($HTTP_POST_VARS['customers_id']) > 0) {
              $sql_data_array['customers_id'] = implode(',', $HTTP_POST_VARS['customers_id']);
            }
          }

          if ($error == false) {
            if (empty($HTTP_GET_VARS['dID'])) {
              tep_db_perform(TABLE_DISCOUNT_CODES, $sql_data_array);
              $messageStack->add_session(SUCCESS_DISCOUNT_CODE_INSERTED, 'success');
            } else {
              tep_db_perform(TABLE_DISCOUNT_CODES, $sql_data_array, 'update', "discount_codes_id = '" . (int)$HTTP_GET_VARS['dID'] . "'");
              $messageStack->add_session(SUCCESS_DISCOUNT_CODE_UPDATED, 'success');
            }
            tep_redirect(tep_href_link(FILENAME_DISCOUNT_CODES));
          }
        }
        $action = 'new';
        break;
      case 'delete':
        tep_db_query("delete from " . TABLE_CUSTOMERS_TO_DISCOUNT_CODES . " where discount_codes_id = '" . (int)$HTTP_GET_VARS['dID'] . "'");
        tep_db_query("delete from " . TABLE_DISCOUNT_CODES . " where discount_codes_id = '" . (int)$HTTP_GET_VARS['dID'] . "' limit 1");

        $messageStack->add_session(SUCCESS_DISCOUNT_CODE_REMOVED, 'success');

        tep_redirect(tep_href_link(FILENAME_DISCOUNT_CODES, 'page=' . $HTTP_GET_VARS['page']));
        break;
    }
  }

  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();

  require(DIR_WS_INCLUDES . 'template-top.php');
?>
<title>Coupon Codes</title>
<link rel="stylesheet" type="text/css" href="includes/javascript/calendar.css">
<script language="JavaScript" src="includes/javascript/calendarcode.js"></script>
<script language="javascript">
function confirm_delete(a, b) { if (confirm(a)) window.location = b }
</script>

<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onLoad="<?php echo isset($action) && $action == 'new' ? 'onload();' : 'SetFocus();'; ?>">
<div id="popupcalendar" class="text"></div>

<div id="heading-block">
            <!-- PWA BOF -->
             <h1 class="pageHeading">Coupons</h1>
             </div>
    
<?php
  if ($action == 'new') {
    $dInfo = new objectInfo(array('products_id' => '',
                                  'categories_id' => '',
                                  'manufacturers_id' => '',
                                  'excluded_products_id' => '',
                                  'customers_id' => '',
                                  'orders_total' => '2',
                                  'order_info' => '',
                                  'exclude_specials' => '',
                                  'discount_codes' => substr(md5(uniqid(rand(), true)), 0, 8),
                                  'discount_values' => '',
								  'coupons_notice' => '',
                                  'minimum_order_amount' => '',
                                  'expires_date' => '',
                                  'number_of_orders' => '',
                                  'number_of_use' => '1',
                                  'number_of_products' => '1',
                                  'status' => ''));

    if (isset($HTTP_GET_VARS['dID'])) {
      $discount_code_query = tep_db_query("select dc.discount_codes_id, dc.products_id, dc.categories_id, dc.manufacturers_id, dc.excluded_products_id, dc.customers_id, dc.orders_total, dc.order_info, dc.discount_codes, dc.discount_values, dc.coupons_notice, dc.minimum_order_amount, dc.expires_date, dc.number_of_orders, dc.number_of_use, dc.number_of_products, dc.status from discount_codes dc where dc.discount_codes_id = '" . (int)$HTTP_GET_VARS['dID'] . "'");
      $discount_code = tep_db_fetch_array($discount_code_query);

      $dInfo->objectInfo($discount_code);

      if (!empty($discount_code['products_id'])) $dInfo->products_id = explode(',', $discount_code['products_id']);
      if (!empty($discount_code['categories_id'])) $dInfo->categories_id = explode(',', $discount_code['categories_id']);
      if (!empty($discount_code['manufacturers_id'])) $dInfo->manufacturers_id = explode(',', $discount_code['manufacturers_id']);
      if (!empty($discount_code['excluded_products_id'])) $dInfo->excluded_products_id = explode(',', $discount_code['excluded_products_id']);
      if (!empty($discount_code['customers_id'])) $dInfo->customers_id = explode(',', $discount_code['customers_id']);
      if ($discount_code['minimum_order_amount'] == '0.0000') $dInfo->minimum_order_amount = '';
      if ($discount_code['expires_date'] == '0000-00-00') {
        $dInfo->expires_date = array(0 => '', 1 => '', 2 => '');
      } else {
        $dInfo->expires_date = explode('-', $discount_code['expires_date']);
      }
      if ($discount_code['number_of_use'] == 0) $dInfo->number_of_use = '';
      if ($discount_code['number_of_products'] == 0) $dInfo->number_of_products = '';
    //} elseif (tep_not_null($HTTP_POST_VARS)) {
      //$dInfo->objectInfo($HTTP_POST_VARS);
    }

    $manufacturers_array = array();
    $manufacturers_query = tep_db_query("select manufacturers_id, manufacturers_name from " . TABLE_MANUFACTURERS . " order by manufacturers_name");
    while ($manufacturers = tep_db_fetch_array($manufacturers_query)) {
      $manufacturers_array[] = array('id' => $manufacturers['manufacturers_id'],
                                     'text' => $manufacturers['manufacturers_name']);
    }
?>
<script language="javascript">
function applies_to_onclick() {
  var a = document.new_discount_code.applies_to, b = document.getElementById("excluded_products_id"), c = document.getElementById("number_of_products"), d = document.getElementById("exclude_specials");
  for (var i = 0, n = a.length; i < n; i++) if (a[i].checked) { b.disabled = (a[i].value == 2 || a[i].value == 4 ? false : true); c.disabled = (a[i].value == 3 || a[i].value == 5 ? true : false); d.disabled = (a[i].value == 3 || a[i].value == 5 ? true : false) }
}
function customers_onclick() {
  var d = document.getElementById("customers"), e = document.getElementById("customers_id"); e.disabled = !d.checked;
}
function onload() {
  SetFocus();
  applies_to_onclick();
  customers_onclick();
}
</script>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <?php echo tep_draw_form('new_discount_code', FILENAME_DISCOUNT_CODES, (isset($HTTP_GET_VARS['page']) ? 'page=' . $HTTP_GET_VARS['page'] . '&' : '') . 'action=insert' . (isset($HTTP_GET_VARS['dID']) ? '&dID=' . (int)$HTTP_GET_VARS['dID'] : '')); ?><tr>
        <td><table border="0" cellspacing="0" cellpadding="2">
          <tr>
          <div class="col-sm-6">
          <div class="form-group">
            <label class="col-xs-3"><?php echo 'Coupon Name:'; ?></label>
            <div class="col-xs-9"><?php echo tep_draw_input_field('discount_codes', $dInfo->discount_codes, 'size="20"', true); ?></div>
            </div>
            
              <div class="form-group">
          <label class="col-xs-3">Comment:</label>
              <div class="col-xs-9"><?php echo tep_draw_input_field('coupons_notice', $dInfo->coupons_notice, 'style="width:100%;"'); ?></div>
           </div>
           
            <div class="form-group">
           <label class="col-xs-3"><?php echo TEXT_DISCOUNT; ?></label>
             <div class="col-xs-9"><?php echo tep_draw_input_field('discount_values', $dInfo->discount_values, 'size="8"', true); ?></div>
           </div>
           
           <div class="form-group">
           <label class="col-xs-3"><?php echo TEXT_MINIMUM_ORDER_AMOUNT; ?></label>
             <div class="col-xs-9"><?php echo tep_draw_input_field('minimum_order_amount', $dInfo->minimum_order_amount, 'size="8"'); ?></div>
             </div>
             <div class="form-group">
            <label class="col-xs-3"><?php echo TEXT_EXPIRY; ?></label>
            <div class="col-xs-9"><?php echo tep_draw_input_field('day', $dInfo->expires_date[2], 'size="2" maxlength="2" class="cal-TextBox"') . tep_draw_input_field('month', $dInfo->expires_date[1], 'size="2" maxlength="2" class="cal-TextBox"') . tep_draw_input_field('year', $dInfo->expires_date[0], 'size="4" maxlength="4" class="cal-TextBox"'); ?><a class="so-BtnLink" href="javascript:calClick();return false;" onMouseOver="calSwapImg('BTN_date', 'img_Date_OVER',true);" onMouseOut="calSwapImg('BTN_date', 'img_Date_UP',true);" onClick="calSwapImg('BTN_date', 'img_Date_DOWN');showCalendar('new_discount_code','dteWhen','BTN_date');return false;"><?php echo tep_image(DIR_WS_IMAGES . 'cal_date_up.gif', 'Calendar', '22', '17', 'align="absmiddle" name="BTN_date"'); ?></a></div>
            </div>
            </div>
             
             <div class="col-sm-6">
           <div class="form-group">
            <label class="col-xs-6"><?php echo TEXT_NUMBER_OF_USE; ?></label>
            <div class="col-xs-6"><?php echo tep_draw_input_field('number_of_use', $dInfo->number_of_use, 'size="4"'); ?></div>
          </div>
         
         <div class="form-group">
            <label class="col-xs-6"><?php echo TEXT_NUMBER_OF_PRODUCTS; ?></label>
             <div class="col-xs-6"><?php echo tep_draw_input_field('number_of_products', $dInfo->number_of_products, 'size="4" id="number_of_products"'); ?></div>
        </div>
               <div class="form-group"><?php echo '<label>' . tep_draw_checkbox_field('order_info', '1', $dInfo->order_info == 1) . '&nbsp;' . TEXT_ORDER_INFO . '</label>'; ?></div>
          
<div class="form-group"><?php echo '<label>' . str_replace('name="exclude_specials"', 'name="exclude_specials" id="exclude_specials"', tep_draw_checkbox_field('exclude_specials', '1', $dInfo->exclude_specials == 1)) . '&nbsp;Exclude Specials</label>'; ?></div>
</div>

      
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main"><?php echo TEXT_APPLIES_TO; ?></td>
            <td width="5"></td>
            <td class="main"><?php echo '<label>' . str_replace('<input type="radio"', '<input onclick="applies_to_onclick();" type="radio"', tep_draw_radio_field('applies_to', '5', $dInfo->orders_total == 2)) . '&nbsp;' . TEXT_ORDER_SUBTOTAL . '</label>'; ?></td>
            <td width="5"></td>
            <td class="main"><?php echo '<label>' . str_replace('<input type="radio"', '<input onclick="applies_to_onclick();" type="radio"', tep_draw_radio_field('applies_to', '3', $dInfo->orders_total == 1)) . '&nbsp;' . TEXT_ORDER_TOTAL . '</label>'; ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo '<label>' . str_replace('<input type="radio"', '<input onclick="applies_to_onclick();" type="radio"', tep_draw_radio_field('applies_to', '1', is_array($dInfo->products_id))) . '&nbsp;' . TEXT_PRODUCTS . '</label>'; ?></td>
            <td></td>
            <td class="main"><?php echo '<label>' . str_replace('<input type="radio"', '<input onclick="applies_to_onclick();" type="radio"', tep_draw_radio_field('applies_to', '2', is_array($dInfo->categories_id))) . '&nbsp;' . TEXT_CATEGORIES . '</label>'; ?></td>
            <td></td>
            <td class="main"><?php echo '<label>' . str_replace('<input type="radio"', '<input onclick="applies_to_onclick();" type="radio"', tep_draw_radio_field('applies_to', '4', is_array($dInfo->manufacturers_id))) . '&nbsp;' . TEXT_MANUFACTURERS . '</label>'; ?></td>
          </tr>
<?php
    $products_id = '';
    $products_query = tep_db_query("select p.products_id, pd.products_name, p.products_price, s.specials_new_products_price from (" . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd) left join " . TABLE_SPECIALS . " s on (p.products_id = s.products_id and s.status = '1' and ifnull(s.expires_date, now()) >= now()) where p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' order by products_name");
    while ($products = tep_db_fetch_array($products_query)) {
      $products_id .= '<option value="' . $products['products_id'] . '">' . $products['products_name'] . ' (' . (empty($products['specials_new_products_price']) ? '' : $currencies->format($products['specials_new_products_price']) . '/') . $currencies->format($products['products_price']) . ')</option>';
    }
    $products_id .= '</select>';

    $excluded_products_id = '<select name="excluded_products_id[]" size="10" multiple style="width: 280px;" id="excluded_products_id">' . $products_id;
    $products_id = '<select name="products_id[]" size="10" multiple style="width: 280px;">' . $products_id;

    if (is_array($dInfo->products_id)) {
      foreach ($dInfo->products_id as $v) {
        $products_id = str_replace('<option value="' . $v . '">', '<option value="' . $v . '" selected>', $products_id);
      }
    }

    if (is_array($dInfo->excluded_products_id)) {
      foreach ($dInfo->excluded_products_id as $v) {
        $excluded_products_id = str_replace('<option value="' . $v . '">', '<option value="' . $v . '" selected>', $excluded_products_id);
      }
    }

    $categories_id = str_replace('<select name="categories_id">', '<select name="categories_id[]" size="10" multiple style="width: 280px;">', tep_draw_pull_down_menu('categories_id', tep_get_category_tree('0', '', '0')));
    if (is_array($dInfo->categories_id)) {
      foreach ($dInfo->categories_id as $v) {
        $categories_id = str_replace('<option value="' . $v . '">', '<option value="' . $v . '" selected>', $categories_id);
      }
    }

    $manufacturers_id = str_replace('<select name="manufacturers_id">', '<select name="manufacturers_id[]" size="10" multiple style="width: 280px;">', tep_draw_pull_down_menu('manufacturers_id', $manufacturers_array));
    if (is_array($dInfo->manufacturers_id)) {
      foreach ($dInfo->manufacturers_id as $v) {
        $manufacturers_id = str_replace('<option value="' . $v . '">', '<option value="' . $v . '" selected>', $manufacturers_id);
      }
    }
?>
          <tr>
            <td class="main"><?php echo $products_id; ?></td>
            <td></td>
            <td class="main"><?php echo $categories_id; ?></td>
            <td></td>
            <td class="main"><?php echo $manufacturers_id; ?></td>
          </tr>
          <tr>
            <td></td>
            <td></td>
            <td class="main"><?php echo TEXT_EXCLUDED_PRODUCTS; ?></td>
            <td></td>
            <td class="main"><?php echo '<label>' . str_replace('<input type="checkbox"', '<input type="checkbox" id="customers" onclick="customers_onclick();"', tep_draw_checkbox_field('customers', '1', is_array($dInfo->customers_id))) . '&nbsp;' . TEXT_CUSTOMERS . '</label>'; ?></td>
          </tr>
          <tr>
            <td></td>
            <td></td>
            <td class="main"><?php echo $excluded_products_id; ?></td>
            <td></td>
<?php
    $customers_id = '<select name="customers_id[]" size="10" multiple style="width: 280px;" id="customers_id">';
    $customers_query = tep_db_query("select customers_id, concat(customers_lastname, ', ', customers_firstname, ' (', customers_email_address, ')') as customers_info from " . TABLE_CUSTOMERS . " order by customers_lastname, customers_firstname");
    while ($customers = tep_db_fetch_array($customers_query)) {
      $customers_id .= '<option value="' . $customers['customers_id'] . '">' . $customers['customers_info'] . '</option>';
    }
    $customers_id .= '</select>';

    if (is_array($dInfo->customers_id)) {
      foreach ($dInfo->customers_id as $v) {
        $customers_id = str_replace('<option value="' . $v . '">', '<option value="' . $v . '" selected>', $customers_id);
      }
    }
?>
            <td class="main"><?php echo $customers_id; ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main" align="right" valign="top" nowrap><?php echo (isset($HTTP_GET_VARS['dID']) ? tep_image_submit('button_update.gif', IMAGE_UPDATE) : tep_image_submit('button_insert.gif', IMAGE_INSERT)) . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_DISCOUNT_CODES, (isset($HTTP_GET_VARS['page']) ? 'page=' . $HTTP_GET_VARS['page'] . '&' : '') . (isset($HTTP_GET_VARS['dID']) ? 'dID=' . (int)$HTTP_GET_VARS['dID'] : '')) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>'; ?></td>
          </tr>
        </table></td>
      </tr></form>
<?php
  } else {
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top">
        <table class="table-orders table-orders-bordered table-hover">
<thead>
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo 'Coupon Code'; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_DISCOUNT; ?></td>
                <td class="dataTableHeadingContent"><?php echo 'Comments'; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_APPLIES_TO; ?></td>
                <td class="dataTableHeadingContent" align="center" alt="<?php echo TABLE_HEADING_MINIMUM_ORDER_AMOUNT_FULL; ?>" title=" <?php echo TABLE_HEADING_MINIMUM_ORDER_AMOUNT_FULL; ?> "><?php echo TABLE_HEADING_MINIMUM_ORDER_AMOUNT; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_EXPIRY; ?></td>
               
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_STATUS; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
              </thead>
              
<?php
    $discount_codes_query_raw = "select dc.discount_codes_id, dc.products_id, dc.categories_id, dc.manufacturers_id, dc.excluded_products_id, dc.customers_id, dc.orders_total, dc.order_info, dc.discount_codes, dc.discount_values, dc.minimum_order_amount, dc.expires_date, dc.number_of_orders, dc.number_of_use, dc.number_of_products, dc.status, dc.coupons_notice from discount_codes dc order by dc.discount_codes_id desc";
    $discount_codes_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $discount_codes_query_raw, $discount_codes_query_numrows);
    $discount_codes_query = tep_db_query($discount_codes_query_raw);
    while ($discount_codes = tep_db_fetch_array($discount_codes_query)) {
      $applies_to = '';
      if (!empty($discount_codes['orders_total'])) {
        if ($discount_codes['orders_total'] == 1) {
          $applies_to = TEXT_ORDER_TOTAL;
        } elseif ($discount_codes['orders_total'] == 2) {
          $applies_to = TEXT_ORDER_SUBTOTAL;
        }
      } elseif (!empty($discount_codes['products_id'])) {
        $applies_to = TEXT_PRODUCTS;
        $product_query = tep_db_query("select products_name from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id in (" . $discount_codes['products_id'] . ") and language_id = '" . (int)$languages_id . "' order by products_name");
        while ($product = tep_db_fetch_array($product_query)) {
          $applies_to .= (empty($applies_to) ? '' : '<br>') . $product['products_name'];
        }
      } elseif (!empty($discount_codes['categories_id'])) {
        $applies_to = TEXT_CATEGORIES;
        $category_query = tep_db_query("select c.categories_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' and c.categories_id in (" . $discount_codes['categories_id'] . ") order by c.parent_id, cd.categories_name");
        while ($category = tep_db_fetch_array($category_query)) {
          $applies_to .= (empty($applies_to) ? '' : '<br>') . tep_output_generated_category_path($category['categories_id']);
        }
      } else {
        $applies_to = TEXT_MANUFACTURERS;
        $manufacturer_query = tep_db_query("select manufacturers_name from " . TABLE_MANUFACTURERS . " where manufacturers_id in (" . $discount_codes['manufacturers_id'] . ") order by manufacturers_name");
        while ($manufacturer = tep_db_fetch_array($manufacturer_query)) {
          $applies_to .= (empty($applies_to) ? '' : '<br>') . $manufacturer['manufacturers_name'];
        }
      }
      if (!empty($discount_codes['excluded_products_id'])) {
        $applies_to .= '<br>' . TEXT_EXCLUDED_PRODUCTS;
        $product_query = tep_db_query("select products_name from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id in (" . $discount_codes['excluded_products_id'] . ") and language_id = '" . (int)$languages_id . "' order by products_name");
        while ($product = tep_db_fetch_array($product_query)) {
          $applies_to .= (empty($applies_to) ? '' : '<br>') . $product['products_name'];
        }
      }

      if ((!isset($HTTP_GET_VARS['dID']) || (isset($HTTP_GET_VARS['dID']) && ($HTTP_GET_VARS['dID'] == $discount_codes['discount_codes_id']))) && !isset($dInfo) && (substr($action, 0, 3) != 'new')) {
        $dInfo = new objectInfo($discount_codes);
      }

      if (isset($dInfo) && is_object($dInfo) && ($discount_codes['discount_codes_id'] == $dInfo->discount_codes_id)) {
        echo '              <tr id="defaultSelected">' . "\n";
      } else {
        echo '              <tr>' . "\n";
      }
?>
                <td class="dataTableContent"><?php echo $discount_codes['discount_codes']; ?></td>
                <td class="dataTableContent" align="center"><?php echo strpos($discount_codes['discount_values'], '%') === false ? $currencies->format($discount_codes['discount_values']) : $discount_codes['discount_values']; ?>&nbsp;</td>
                <td class="dataTableContent"><?php echo $discount_codes['coupons_notice']; ?></td>
                <td class="dataTableContent"><?php echo $applies_to; ?></td>
                <td class="dataTableContent" align="center"><?php echo $discount_codes['minimum_order_amount'] == '0.0000' ? '-' : $currencies->format($discount_codes['minimum_order_amount']); ?></td>
                <td class="dataTableContent" align="center"><?php echo $discount_codes['expires_date'] == '0000-00-00' ? '-' : tep_date_short($discount_codes['expires_date']); ?>&nbsp;</td>
                <td class="dataTableContent" align="right">
<?php
      if ($discount_codes['status'] == '1') {
        echo '<span style="font-size:17px;"><i class="fa fa-circle" title="Active" style="color:#0C0;">'.'</i></span>&nbsp;&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_DISCOUNT_CODES, 'page=' . $HTTP_GET_VARS['page'] . '&dID=' . $discount_codes['discount_codes_id'] . '&action=setflag&flag=0') . '"><span style="font-size:17px;"><i class="fa fa-circle" title="Set Inactive" style="color:#E2B2B1;">'.'</i></span></a>';
      } else {
        echo '<a href="' . tep_href_link(FILENAME_DISCOUNT_CODES, 'page=' . $HTTP_GET_VARS['page'] . '&dID=' . $discount_codes['discount_codes_id'] . '&action=setflag&flag=1') . '"><span style="font-size:17px;"><i class="fa fa-circle" title="Set Active" style="color:#AED2AE;">'.'</i></span></a>&nbsp;&nbsp;&nbsp;<span style="font-size:17px;"><i class="fa fa-circle" title="Inactive" style="color:#D95351">'.'</i></span>';
      }
?></td>
      
                <td class="dataTableContent" align="right"><?php echo '<a href="' . tep_href_link(FILENAME_DISCOUNT_CODES, 'page=' . $HTTP_GET_VARS['page'] . '&dID=' . $discount_codes['discount_codes_id'] . '&action=new') . '" data-toggle="tooltip" data-placement="top" title="Edit" class="btn btn-primary" style="margin:0px 4px;">'.'<i class="fa fa-pencil">'.'</i>'.'</a>';
				

             echo  '<a href="#" onclick="confirm_delete(\'Are you sure you want to delete this coupon?\', \'' . tep_href_link(FILENAME_DISCOUNT_CODES, 'page=' . $HTTP_GET_VARS['page'] . '&dID=' . $discount_codes['discount_codes_id'] . '&action=delete') . '\'); return false;" data-toggle="tooltip" data-placement="top" title="Delete" class="btn btn-danger" style="margin:0px 4px;">'.'<i class="fa fa-trash-o">'.'</i>'.'</a>'; ?>
				</td>
              </tr>
<?php
    }
?>
              <tr>
                <td colspan="8"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $discount_codes_split->display_count($discount_codes_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_DISCOUNT_CODES); ?></td>
                    <td class="smallText" align="right"><?php echo $discount_codes_split->display_links($discount_codes_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page']); ?></td>
                  </tr>
                  <tr>
                    <td align="right" colspan="2"><?php echo '<a href="' . tep_href_link(FILENAME_DISCOUNT_CODES, 'action=new') . '">' . tep_image_button('button_new_discount_code.gif', IMAGE_NEW_DISCOUNT_CODE) . '</a>'; ?></td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
<?php
  $heading = array();
  $contents = array();
  switch ($action) {
   
    default:
      if (is_object($dInfo)) {
        $heading[] = array('text' => '<strong>' . $dInfo->discount_codes . '</strong>');

        $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_DISCOUNT_CODES, 'page=' . $HTTP_GET_VARS['page'] . '&dID=' . $dInfo->discount_codes_id . '&action=new') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_DISCOUNT_CODES, 'page=' . $HTTP_GET_VARS['page'] . '&dID=' . $dInfo->discount_codes_id . '&action=delete') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a>');
        $contents[] = array('text' => '<br />');
        if ($dInfo->order_info == 1) $contents[] = array('text' => '' . tep_image(DIR_WS_ICONS . 'tick.gif', TABLE_HEADING_ORDER_INFO_FULL) . '&nbsp;' . TABLE_HEADING_ORDER_INFO_FULL);
        if ($dInfo->exclude_specials == 1) $contents[] = array('text' => tep_image(DIR_WS_ICONS . 'tick.gif', TEXT_EXCLUDE_SPECIALS) . '&nbsp;' . TEXT_EXCLUDE_SPECIALS);
        if ($dInfo->number_of_use != 0) $contents[] = array('text' => '' . TABLE_HEADING_NUMBER_OF_USE . ' ' . $dInfo->number_of_use);
        if ($dInfo->number_of_products != 0) $contents[] = array('text' => '' . TABLE_HEADING_NUMBER_OF_PRODUCTS . ' ' . $dInfo->number_of_products);
        if (!empty($dInfo->customers_id)) {
          $select_string = '';
          $customers_query = tep_db_query("select concat(customers_lastname, ', ', customers_firstname, ' (', customers_email_address, ')') as customers_info from " . TABLE_CUSTOMERS . " where customers_id in (" . $dInfo->customers_id . ") order by customers_lastname, customers_firstname");
          while ($customers = tep_db_fetch_array($customers_query)) {
            $select_string .= (empty($select_string) ? '' : '<br>') . $customers['customers_info'];
          }
          if (!empty($select_string)) {
            $contents[] = array('text' => '<br />' . TEXT_INFO_CUSTOMERS . '<br />' . $select_string);
          }
        }
      }
      break;
  }

 
?>
          </tr>
        </table></td>
      </tr>
<?php
  }
?>
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
