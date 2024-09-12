<?php
/*
  $Id: optional_related_products.php, ver 1.0 02/05/2007 Exp $

  Copyright (c) 2007 Anita Cross (http://www.callofthewildphoto.com/)

  Based on: products_options.php, ver 2.0 05/01/2005
  Copyright (c) 2004-2005 Daniel Bahna (daniel.bahna@gmail.com)

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  $languages = tep_get_languages();

  $version = tep_db_fetch_array(tep_db_query("select configuration_value as version, configuration_group_id as gID from " . TABLE_CONFIGURATION . " where configuration_key = 'RELATED_PRODUCTS_VERSION_INSTALLED'"));
  if ($version['version'] != TEXT_VERSION_CONTROL){
    tep_redirect(tep_href_link('sql_setup_related_products.php'));
  }
  $gID = $version['gID'];

  $action = (isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : '');

  $products_id_view = $HTTP_GET_VARS['products_id_view'];
  $products_id_master = $HTTP_GET_VARS['products_id_master'];
  if ($products_id_master) { $products_id_view = $products_id_master; }

  if (tep_not_null($action)) {
    $page_info = '';
    if (isset($HTTP_GET_VARS['attribute_page'])) $page_info .= 'attribute_page=' . $HTTP_GET_VARS['attribute_page'] . '&';
    if (tep_not_null($page_info)) {
      $page_info = substr($page_info, 0, -1);
    }

    switch ($action) {
      case 'Insert':
        $products_id_master = tep_db_prepare_input($_REQUEST['products_id_master']);
        $products_id_slave = tep_db_prepare_input($_REQUEST['products_id_slave']);
        $pop_order_id = tep_db_prepare_input($_REQUEST['pop_order_id']);

        if ($products_id_master != $products_id_slave) {
          $check = tep_db_query("select p.pop_id from " . TABLE_PRODUCTS_RELATED_PRODUCTS . " p where p.pop_products_id_master=" . $products_id_master ." and p.pop_products_id_slave=" . $products_id_slave);
          if (!tep_db_fetch_array($check)) {
            tep_db_query("insert into " . TABLE_PRODUCTS_RELATED_PRODUCTS . " values ('', '" . (int)$products_id_master . "', '" . (int)$products_id_slave . "', '". (int)$pop_order_id."')");
          }
        }
        tep_redirect(tep_href_link(FILENAME_RELATED_PRODUCTS, $page_info.'&products_id_master='.$products_id_master.'&products_id_slave='.$products_id_slave.'&products_id_view='.$products_id_view));
        break;

      case 'Reciprocate':
        $products_id_master = tep_db_prepare_input($_REQUEST['products_id_master']);
        $products_id_slave = tep_db_prepare_input($_REQUEST['products_id_slave']);
        $pop_order_id = tep_db_prepare_input($_REQUEST['pop_order_id']);
        if ($products_id_master != $products_id_slave) {
          $check = tep_db_query("select p.pop_id from " . TABLE_PRODUCTS_RELATED_PRODUCTS . " p where p.pop_products_id_master=" . $products_id_master ." and p.pop_products_id_slave=" . $products_id_slave);
          if (!tep_db_fetch_array($check)) {
            tep_db_query("insert into " . TABLE_PRODUCTS_RELATED_PRODUCTS . " values ('', '" . (int)$products_id_master . "', '" . (int)$products_id_slave . "', '". (int)$pop_order_id."')");
          }
          $check = tep_db_query("select p.pop_id from " . TABLE_PRODUCTS_RELATED_PRODUCTS . " p where p.pop_products_id_master=" . $products_id_slave ." and p.pop_products_id_slave=" . $products_id_master );
          if (!tep_db_fetch_array($check)) {
            tep_db_query("insert into " . TABLE_PRODUCTS_RELATED_PRODUCTS . " values ('', '" . (int)$products_id_slave . "', '" . (int)$products_id_master . "', '". (int)$pop_order_id."')");
          }
        }
        tep_redirect(tep_href_link(FILENAME_RELATED_PRODUCTS, $page_info.'&products_id_master='.$products_id_master.'&products_id_slave='.$products_id_slave.'&products_id_view='.$products_id_view));
        break;

      case 'Inherit':
        $products_id_master = tep_db_prepare_input($_REQUEST['products_id_master']);
        $products_id_slave = tep_db_prepare_input($_REQUEST['products_id_slave']);
        $pop_order_id = tep_db_prepare_input($_REQUEST['pop_order_id']);

        if ($products_id_master != $products_id_slave) {
          if (INSERT_AND_INHERIT == 'True') {
            $check = tep_db_query("select p.pop_id  from " . TABLE_PRODUCTS_RELATED_PRODUCTS . " p where p.pop_products_id_master=" . $products_id_master." and p.pop_products_id_slave=" . $products_id_slave);
            if (!tep_db_fetch_array($check)) {
               tep_db_query("insert into " . TABLE_PRODUCTS_RELATED_PRODUCTS . " values ('', '" . (int)$products_id_master . "', '" . (int)$products_id_slave . "', '". (int)$pop_order_id."')");
            }
          }
          $products = tep_db_query("select p.pop_products_id_slave, pop_order_id from " . TABLE_PRODUCTS_RELATED_PRODUCTS . " p where p.pop_products_id_master=" . $products_id_slave . " order by p.pop_id");
          while ($products_values = tep_db_fetch_array($products)) {
            $products_id_slave2 = $products_values['pop_products_id_slave'];
            if ($products_id_master != $products_id_slave2) {

              $check = tep_db_query("select p.pop_id from " . TABLE_PRODUCTS_RELATED_PRODUCTS . " p where p.pop_products_id_master=" . $products_id_master." and p.pop_products_id_slave=" . $products_id_slave2);
              if (!tep_db_fetch_array($check)) {
                tep_db_query(" insert into " . TABLE_PRODUCTS_RELATED_PRODUCTS . " values ('', '" . (int)$products_id_master . "', '" . (int)$products_id_slave2 . "', '". $products_values['pop_order_id']."')");
              }
            }
          }
        }
        tep_redirect(tep_href_link(FILENAME_RELATED_PRODUCTS, $page_info.'&products_id_master='.$products_id_master.'&products_id_slave='.$products_id_slave.'&products_id_view='.$products_id_view));
        break;

      case 'update_product_attribute':
        $products_id_master = tep_db_prepare_input($_REQUEST['products_id_master']);
        $products_id_slave = tep_db_prepare_input($_REQUEST['products_id_slave']);
        $pop_order_id = tep_db_prepare_input($_REQUEST['pop_order_id']);
        $pop_id = tep_db_prepare_input($_REQUEST['pop_id']);

        tep_db_query("update " . TABLE_PRODUCTS_RELATED_PRODUCTS . " set pop_products_id_master = '" . (int)$products_id_master . "', pop_products_id_slave = '" . (int)$products_id_slave . "', pop_order_id = '".(int)$pop_order_id."' where pop_id = '" . (int)$pop_id . "'");
        tep_redirect(tep_href_link(FILENAME_RELATED_PRODUCTS, $page_info.'&products_id_view='.$products_id_master));
        break;
      case 'delete_attribute':
        $pop_id = tep_db_prepare_input($HTTP_GET_VARS['pop_id']);

        tep_db_query("delete from " . TABLE_PRODUCTS_RELATED_PRODUCTS . " where pop_id = '" . (int)$pop_id . "'");

        tep_redirect(tep_href_link(FILENAME_RELATED_PRODUCTS, $page_info.'&products_id_view='.$products_id_view));
        break;
    }
  }
?>
<!doctype html>
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title>Optional Related Products</title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<link rel="stylesheet" type="text/css" href="css/bootstrap-grid.css">
	<style>
		#from-products{background: #fff;
			padding: 15px;
    		padding-top: 0px;
    		margin-top: -15px;
		}
		#from-products li{line-height:45px;}
		#from-products li:hover{cursor: pointer; background-color:#bdd0f9;}
	</style>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<div id="wrapper2">
	<!-- header //-->
	<?php
  require(DIR_WS_INCLUDES . 'header.php');
?>
<!-- header_eof //-->
<div style="clear:both;"></div>
	<h1 class="pageHeading"><?php echo HEADING_TITLE_ATRIB; ?></h1>

	<div class="column-12 form-group">
		<div class="row">
			<div class="column-sm-6 form-group">
				<form name="formview"><select class="form-control" style="max-width: 80%;" name="products_id_view" onChange="return formview.submit();">
<?php

    echo '<option name="Show All Products" value="">Show All Products</option>';
    $products = tep_db_query("select p.products_id, p.products_model, pd.products_name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where pd.products_id = p.products_id and pd.language_id = '" . $languages_id . "' order by pd.products_name");
    while ($products_values = tep_db_fetch_array($products)) {
        $model = (RELATED_PRODUCTS_ADMIN_USE_MODEL == 'True')?$products_values['products_model'] . RELATED_PRODUCTS_ADMIN_MODEL_SEPARATOR:'';
        $name = (RELATED_PRODUCTS_ADMIN_USE_NAME == 'True')?$products_values['products_name']:'';
        if ($products_id_view == $products_values['products_id']) {
              echo '<option name="' . $name . '" value="' . $products_values['products_id'] . '" SELECTED>' . $model . $name . '</option>';
        } else {
              echo '<option name="' . $name . '" value="' . $products_values['products_id'] . '">' . $model . $name . '</option>';
        }
    }
?>
            </select></form>
			</div>
			<div class="column-sm-6 form-group">
            	<a href="<?php echo tep_href_link(FILENAME_CONFIGURATION, 'selected_box=configuration&gID=' . $gID); ?>">Configuration Options</a><br>
            	<a href="<?php echo tep_href_link('sql_setup_related_products.php'); ?>">SQL Setup Utility</a>
			</div>
		</div>
	</div>

<?php
/*
foreach ($_REQUEST as $key => $value) {
   echo "Key: $key; Value: $value<br>\n";
}
*/
  if ($action == 'update_attribute') {
    $form_action = 'update_product_attribute';
    $method = 'POST';
  } else {
    $form_action = 'add_product_attributes';
    $method = 'GET';
  }

$attribute_page=$_GET['attribute_page'];
  if (!isset($attribute_page)) {
    $attribute_page = 1;
  }

  $prev_attribute_page = $attribute_page - 1;
  $next_attribute_page = $attribute_page + 1;
  $form_params = 'action=' . $form_action . '&option_page=' . $option_page . '&value_page=' . $value_page . '&attribute_page=' . $attribute_page;
?>
        <td><form name="attributes" action="<?php echo tep_href_link(FILENAME_RELATED_PRODUCTS, $form_params); ?>" method="<?php echo $method; ?>"><table class="table table-striped">

<?php
  $per_page = RELATED_PRODUCTS_MAX_ROW_LISTS_OPTIONS;

  $attributes = "
         SELECT
                pa.*
           FROM " .
                TABLE_PRODUCTS_RELATED_PRODUCTS . " pa
           LEFT JOIN " .
                TABLE_PRODUCTS_DESCRIPTION . " pd
             ON pa.pop_products_id_master = pd.products_id
            AND pd.language_id = '" . (int)$languages_id . "'";

  if ($products_id_view) { $attributes .= "
          WHERE pd.products_id = '$products_id_view'"; }
  $attributes .= "
       ORDER BY pd.products_name, pa.pop_order_id, pa.pop_id";

  $attribute_query = tep_db_query($attributes);

  $attribute_page_start = ($per_page * $attribute_page) - $per_page;
  $num_rows = tep_db_num_rows($attribute_query);

  if ($num_rows <= $per_page) {
     $num_pages = 1;
  } else if (($num_rows % $per_page) == 0) {
     $num_pages = ($num_rows / $per_page);
  } else {
     $num_pages = ($num_rows / $per_page) + 1;
  }
  $num_pages = (int) $num_pages;

  $attributes = $attributes . " LIMIT $attribute_page_start, $per_page";

  $view_id = '';
  if ($products_id_view) {
    $products_id_view = $products_id_master?$products_id_master:$products_id_view;
    $view_id = '&products_id_view=' . $products_id_view;
  }
?>

         <thead>
          <tr class="dataTableHeadingRow">
            <th class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_ID; ?>&nbsp;</th>
            <th class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_PRODUCT; ?>(To)&nbsp;</th>
            <th class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_PRODUCT; ?>(From)&nbsp;</th>
            <th class="dataTableHeadingContent" align="center">&nbsp;<?php echo TABLE_HEADING_ORDER; ?>&nbsp;</th>
            <th width="17%" class="dataTableHeadingContent" align="center">&nbsp;<?php echo TABLE_HEADING_ACTION; ?>&nbsp;</th>
          </tr>
		</thead>

<?php
  $next_id = 1;
  $attributes = tep_db_query($attributes);
  while ($attributes_values = tep_db_fetch_array($attributes)) {
    $products_name_master = tep_get_products_name($attributes_values['pop_products_id_master']);
    $products_name_slave = tep_get_products_name($attributes_values['pop_products_id_slave']);
    if (RELATED_PRODUCTS_ADMIN_USE_MODEL == 'True') {
      $mModel = tep_get_products_model($attributes_values['pop_products_id_master']) . RELATED_PRODUCTS_ADMIN_MODEL_SEPARATOR . ' ';
      $sModel = tep_get_products_model($attributes_values['pop_products_id_slave']) . RELATED_PRODUCTS_ADMIN_MODEL_SEPARATOR . ' ';
    } else {
      $mModel = $sModel = '';
    }
    $pop_order_id = $attributes_values['pop_order_id'];
    $rows++;
?>
          <tr>
<?php
    if (($action == 'update_attribute') && ($HTTP_GET_VARS['pop_id'] == $attributes_values['pop_id'])) {
?>
            <td class="align-middle"><?php echo $attributes_values['pop_id']; ?><input type="hidden" name="pop_id" value="<?php echo $attributes_values['pop_id']; ?>"></td>
            <td class="align-middle"><select class="form-control" name="products_id_master">
<?php
      $products = tep_db_query("select p.products_id, p.products_model, pd.products_name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where pd.products_id = p.products_id and pd.language_id = '" . $languages_id . "' order by pd.products_name");
      while($products_values = tep_db_fetch_array($products)) {
        $model = (RELATED_PRODUCTS_ADMIN_USE_MODEL == 'True')?$products_values['products_model'] . RELATED_PRODUCTS_ADMIN_MODEL_SEPARATOR:'';
        $name = (RELATED_PRODUCTS_ADMIN_USE_NAME == 'True')?$products_values['products_name']:'';
        $product_name = (RELATED_PRODUCTS_MAX_NAME_LENGTH == '0')?$name:substr($name, 0, RELATED_PRODUCTS_MAX_NAME_LENGTH);
        if ($attributes_values['pop_products_id_master'] == $products_values['products_id']) {
          echo "\n" . '<option name="' . $products_values['products_name'] . '" value="' . $products_values['products_id'] . '" SELECTED>' . $model . $product_name . '</option>';
        } else {
          echo "\n" . '<option name="' . $products_values['products_name'] . '" value="' . $products_values['products_id'] . '">' . $model . $product_name . '</option>';
        }
      }
	$get_products_name_query = tep_db_query("select p.products_id, p.products_model, pd.products_name AS name FROM products p, products_description pd where pd.products_id = p.products_id AND p.products_id = '".$attributes_values['pop_products_id_slave']."'");
	$get_products_name = tep_db_fetch_array($get_products_name_query);
?>
            </select></td>
            <td class="align-middle"><input class="form-control pID-slave" value="<?php echo $get_products_name['name'];?>">
				<input type="hidden" name="products_id_slave" class="hidden-id-input" value="<?php echo $attributes_values['pop_products_id_slave']; ?>">
				<div id="from-products" style="display: none;">
					<ul style="list-style: none;">
<?php
      $products = tep_db_query("select p.products_id, p.products_model, pd.products_name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where pd.products_id = p.products_id and pd.language_id = '" . $languages_id . "' order by pd.products_name");
      while($products_values = tep_db_fetch_array($products)) {
        $model = (RELATED_PRODUCTS_ADMIN_USE_MODEL == 'True')?$products_values['products_model'] . RELATED_PRODUCTS_ADMIN_MODEL_SEPARATOR:'';
        $name = (RELATED_PRODUCTS_ADMIN_USE_NAME == 'True')?$products_values['products_name']:'';
        $product_name = (RELATED_PRODUCTS_MAX_NAME_LENGTH == '0')?$name:substr($name, 0, RELATED_PRODUCTS_MAX_NAME_LENGTH);

          echo '<li name="' . $products_values['products_name'] . '" value="' . $products_values['products_id'] . '">' . $model . $name . '</li>';

      }
?>
            </ul>&nbsp;</td>
            <td class="align-middle"align="center" ><input class="form-control" style="width:60px;" type="text" name="pop_order_id" value="<?php echo $attributes_values['pop_order_id']; ?>" size="6"></td>
            <td class="align-middle"align="center" class="smallText">
				<button class="btn btn-sm btn-primary" type="submit" name="action" value="update_product_attribute">Update</button>
				<?php echo '<a type="button" class="btn btn-sm btn-danger" href="' . tep_href_link(FILENAME_RELATED_PRODUCTS, '&attribute_page=' . $attribute_page . '&products_id_view='.$products_id_view, 'NONSSL') . '" style="margin-left:20px;">'; ?>Cancel</a></td>
<?php
    } else {
//  basic browse table list
?>
            <td class="align-middle"><?php echo $attributes_values["pop_id"]; ?>&nbsp;</td>
            <td class="align-middle"><?php echo $mModel ?><?php echo (RELATED_PRODUCTS_MAX_DISPLAY_LENGTH== '0')?$products_name_master:substr($products_name_master, 0, RELATED_PRODUCTS_MAX_DISPLAY_LENGTH); ?>&nbsp;</td>
            <td class="align-middle"><?php echo $sModel ?><?php echo (RELATED_PRODUCTS_MAX_DISPLAY_LENGTH== '0')?$products_name_slave:substr($products_name_slave, 0, RELATED_PRODUCTS_MAX_DISPLAY_LENGTH); ?>&nbsp;</td>
            <td class="align-middle" align="center">&nbsp;<?php echo $pop_order_id; ?>&nbsp;</td>
            <td class="align-middle" align="center">

               <?php
                 $params = 'action=update_attribute&pop_id='
                          . $attributes_values['pop_id']
                          . '&attribute_page=' . $attribute_page
                          . '&products_id_view=' . $products_id_view;
                     echo '<a class="btn btn-sm btn-outline-primary" style="display:inline-block" href="' . tep_href_link(FILENAME_RELATED_PRODUCTS, $params, 'NONSSL') . '">'; ?>Edit</a>
               <?php
                 $params = 'action=delete_attribute&pop_id='
                          . $attributes_values['pop_id']
                          . '&attribute_page=' . $attribute_page
                          . '&products_id_view=' . $products_id_view;
                     if (RELATED_PRODUCTS_CONFIRM_DELETE == 'False') { ?>
               <a class="btn btn-sm btn-outline-danger"style="display:inline-block; margin-left:20px;" href="<?php echo tep_href_link(FILENAME_RELATED_PRODUCTS, $params, 'NONSSL')?>">Delete</a>
               <?php }else { ?>
               <a class="btn btn-sm btn-outline-danger" style="display:inline-block; margin-left:20px;" href="<?php echo tep_href_link(FILENAME_RELATED_PRODUCTS, $params, 'NONSSL')?>" onClick="return confirm('<?php echo sprintf(TEXT_CONFIRM_DELETE_ATTRIBUTE, addslashes($products_name_slave), addslashes($products_name_master)); ?>');">Delete</a>
               <?php } ?></td>
<?php
    }
    $max_attributes_id_query = tep_db_query("select max(pop_id) + 1 as next_id from " . TABLE_PRODUCTS_RELATED_PRODUCTS);
    $max_attributes_id_values = tep_db_fetch_array($max_attributes_id_query);
    $next_id = $max_attributes_id_values['next_id'];
?>
          </tr>
<?php
  }
  if ($action != 'update_attribute') {
?>
          <tr>
            <td colspan="5"><?php echo tep_black_line(); ?></td>
          </tr>
          <tr>
            <td class="align-middle"><?php echo $next_id; ?></td>
      	    <td class="align-middle"><b>A:</b><select name="products_id_master" class="form-control">
<?php
    $products = tep_db_query("select p.products_id, p.products_model, pd.products_name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where pd.products_id = p.products_id and pd.language_id = '" . $languages_id . "' order by pd.products_name");
    $products_id_master = $HTTP_GET_VARS['products_id_master'];
    if (!$products_id_master) { $products_id_master = $products_id_view; }
    while ($products_values = tep_db_fetch_array($products)) {
      $model = (RELATED_PRODUCTS_ADMIN_USE_MODEL == 'True')?$products_values['products_model'] . RELATED_PRODUCTS_ADMIN_MODEL_SEPARATOR:'';
      $name = (RELATED_PRODUCTS_ADMIN_USE_NAME == 'True')?$products_values['products_name']:'';
      $product_name = (RELATED_PRODUCTS_MAX_NAME_LENGTH == '0')?$name:substr($name, 0, RELATED_PRODUCTS_MAX_NAME_LENGTH);
      if ($products_id_master == $products_values['products_id']) {
        echo '<option name="' . $products_values['products_name'] . '" value="' . $products_values['products_id'] . '" SELECTED>' . $model . $product_name . '</option>';
      } else {
        echo '<option name="' . $products_values['products_name'] . '" value="' . $products_values['products_id'] . '">' . $model . $product_name . '</option>';
      }
    }
?>
            </select></td>
            <td ><b>B:</b><input class="form-control pID-slave" >
				<input type="hidden" name="products_id_slave" class="hidden-id-input">
				<div id="from-products" style="display: none;">
					<ul style="list-style: none;">
<?php
    $products = tep_db_query("select p.products_id, p.products_model, pd.products_name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where pd.products_id = p.products_id and pd.language_id = '" . $languages_id . "' order by pd.products_name");
    while ($products_values = tep_db_fetch_array($products)) {
      $model = (RELATED_PRODUCTS_ADMIN_USE_MODEL == 'True')?$products_values['products_model'] . RELATED_PRODUCTS_ADMIN_MODEL_SEPARATOR:'';
      $name = (RELATED_PRODUCTS_ADMIN_USE_NAME == 'True')?$products_values['products_name']:'';
      $product_name = $name;
      if ($HTTP_GET_VARS['products_id_slave'] == $products_values['products_id']) {
        echo '<li name="' . $products_values['products_name'] . '" value="' . $products_values['products_id'] . '" SELECTED>' . $model . $product_name . '</li>';
      } else {
        echo '<li name="' . $products_values['products_name'] . '" value="' . $products_values['products_id'] . '">' . $model . $product_name . '</li>';
      }
    }
?>			</ul>
            </div></td>
            <td class="align-middle" align="center"><input type="text" name="pop_order_id" class="form-control" style="width:60px;" size="3"></td>
          </tr>
          <tr><td colspan="5" align="center" class="smallText">
            <input type="submit" name="action" value="Insert" class="btn btn-sm btn-secondary">
            <input type="submit" name="action" value="Reciprocate" class="btn btn-sm btn-secondary" style="margin-left:15px;">
            <input type="submit" name="action" value="Inherit" class="btn btn-sm btn-secondary" style="margin-left:15px;">
          </td></tr>
<?php
  }
?>
          <tr>
            <td colspan="5"><?php echo tep_black_line(); ?></td>
          </tr>
<tr><td><?php
  if ($products_id_view) {
    $products_id_view = $products_id_master?$products_id_master:$products_id_view;
    $view_id = '&products_id_view=' . $products_id_view;
  }

  // Previous
  if ($prev_attribute_page) {
    echo '<a href="' . tep_href_link(FILENAME_RELATED_PRODUCTS, 'attribute_page=' . $prev_attribute_page . $view_id) . '"> &lt;&lt; </a> | ';
  }

  for ($i = 1; $i <= $num_pages; $i++) {
    if ($i != $attribute_page) {
      echo '<a href="' . tep_href_link(FILENAME_RELATED_PRODUCTS, 'attribute_page=' . $i) . $view_id . '">' . $i . '</a> | ';
    } else {
      echo '<b><font color="red">' . $i . '</font></b> | ';
    }
  }

  // Next
  if ($attribute_page != $num_pages) {
    echo '<a href="' . tep_href_link(FILENAME_RELATED_PRODUCTS, 'attribute_page=' . $next_attribute_page . $view_id) . '"> &gt;&gt; </a>';
  }
?></td></tr>
        </table>
        <input type="hidden" name="products_id_view" value="<?php echo $products_id_view; ?>">
        </form></td>
      </tr>
    </table></td>
  </tr>
</table>

<script>

$(".pID-slave").on("keyup", function(e){
	var input, filter, ul, li, a, i;
	input = $(this);
  	filter = input.val().toUpperCase();
  	div = $("#from-products");
  	a = $("#from-products li");

	if(input.val().length > 0){
		div.show();
	} else {
		div.hide();
	}

	for (i = 0; i < a.length; i++) {
    	txtValue = a[i].textContent || a[i].innerText;
    	if (txtValue.toUpperCase().indexOf(filter) > -1) {
      		a[i].style.display = "";
    	} else {
      		a[i].style.display = "none";
    	}
  	}
})

$(".pID-slave").on("click", function(e){
	var input = $(this);
	input.val('');
})

$("#from-products li").on("click", function(){
	var value, name;
	value = $(this).val();
	name = $(this).attr('name');
	$("#from-products").hide();
	$('.pID-slave').val(name);
	$('.hidden-id-input').val(value);


})
</script>
<?php require(DIR_WS_INCLUDES . 'footer.php'); /* footer */?>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
