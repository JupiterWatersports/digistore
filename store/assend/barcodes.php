<?php
/*
  $Id: configuration.php,v 1.43 2003/06/29 22:50:51 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  $action = (isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : 'manage');

  if (isset($HTTP_GET_VARS['pID']))
    $pID = $HTTP_GET_VARS['pID'];


  /////////////////
  // If a barcode was scanned
  if ($action == "select")
  {
    // Get the products_barcodes_id
    $query = tep_db_query("select products_id, products_barcodes_id from " . TABLE_PRODUCTS_BARCODES . " where barcode = '" . $barcode . "'");
    
    if($result = tep_db_fetch_array($query))
    {
      $pID = $result['products_id'];
      $pbID = $result['products_barcodes_id'];
    }
    else
      $pID = 0;
    
    $action = 'manage';
  }
  // If a barcode was scanned
  /////////////////


  /////////////////
  // If adding new barcode
  if ($action == "add")
  {
    // Check if the barcode alredy exists
    $query_barcode = tep_db_query("select products_barcodes_id id from " . TABLE_PRODUCTS_BARCODES . " where barcode = '" . $barcode ."'");
	  $result_barcode = tep_db_fetch_array($query_product);
	  
	  // If the barcode doesnt exist
	  if (tep_db_num_rows($query_barcode) == 0)
	  {
	    $Query_insert = "insert into " . TABLE_PRODUCTS_BARCODES . " (products_id, barcode) values (" . $pID . ", '" . $barcode . "')";
		  tep_db_query($Query_insert);
	    tep_redirect(tep_href_link(FILENAME_BARCODES, 'action=select&barcode=' . $barcode));
	  }
  }
  // If adding new barcode
  /////////////////


  /////////////////
  // If deleting a barcode
  if ($action == "del_barcode")
  {
    $query_del = tep_db_query("delete from " . TABLE_PRODUCTS_BARCODES . " where products_barcodes_id = " . $pbID);
    tep_redirect(tep_href_link(FILENAME_BARCODES, 'pID=' . $pID));
  }
  // If deleting a barcode
  /////////////////
  
  
  /////////////////
  // If adding new attribute
  if ($action == "add_attribute")
  {
//    $barcode = $HTTP_GET_VARS['barcode'];
//    $pID = $HTTP_GET_VARS['pID'];
  	
    $query = tep_db_query("insert into " . TABLE_PRODUCTS_BARCODES_TO_OPTIONS . " (products_barcodes_id, products_attributes_id) values (" . $pbID . ", " . $paID . ")");
    tep_redirect(tep_href_link(FILENAME_BARCODES, 'pbID=' . $pbID));
  }
  // If adding new attribute
  /////////////////




  if (isset($pID) == false)
  { 
    if (isset($pbID))
    {
      // Get the product id
      $query_product_id = tep_db_query("select products_id, products_barcodes_id from " . TABLE_PRODUCTS_BARCODES . " where products_barcodes_id = " . $pbID);
      if($result_product_id = tep_db_fetch_array($query_product_id))
      {
        $pID = $result_product_id['products_id'];
      }
      else
        $pID = 0;
    }
    else
      $pID = 0;
  }

  $query_product_name = tep_db_query("select pd.products_name from " . TABLE_PRODUCTS_DESCRIPTION . " pd where pd.products_id = " . $pID . " and pd.language_id = '" . (int)$languages_id . "'");
	$result_product_name = tep_db_fetch_array($query_product_name);
	$product_name = $result_product_name['products_name'];




?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/general.js"></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onload="SetFocus();">
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
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>

<!-- Barcode Applet //-->

<?php 
switch ($action) 
{

  case 'new':
    tep_barcode_applet(tep_href_link(FILENAME_BARCODES, 'pID=' . $pID . '&action=add'));
  break;

  case 'manage':
    tep_barcode_applet(tep_href_link(FILENAME_BARCODES, 'action=select'));
  break;

}
?>
<!-- Barcode Applet eof //-->


    </td>
<!-- body_text //-->

<?php
switch ($action) 
{
  
// Managing barcodes for the product
   case 'manage':
?>
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading">Barcodes for <?php echo $product_name ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td>
        
          <!-- Table that contains all the barcode tables : Begin -->
          <table border="0" width="100%" cellspacing="15" cellpadding="0">

            <?php
            // Get all the barcodes for this product
              $query_barcodes = tep_db_query("select products_barcodes_id id, barcode from " . TABLE_PRODUCTS_BARCODES . " where products_id = " .$pID);
              
              while ($product_barcode = tep_db_fetch_array($query_barcodes)) 
              {
                $active_barcode = false;
                if ($pbID == $product_barcode['id'])
                  $active_barcode = true;
            ?>
          
            <tr>
              <td valign="top">
              
                <!-- A Table that contains a barcode : Begin -->
                <table border="0" width="50%" cellspacing="0" cellpadding="2">
                  <tr class=<?php 
                              if ($active_barcode)
                                echo '"dataTableHeadingRow"';
                              else
                                echo '"dataTableRow"';
                            ?> >
                    <td class="dataTableContent" valign="top" width="15%">
                      <?php echo $product_barcode['barcode']; ?>
                    </td>
                    <td>
                      <table border="0" width="100%" cellspacing="0" cellpadding="2">
                      <colgroup>
                        <col width="30%">
                        <col width="30%">
                        <col width="20%">
                        <col width="20%">
                      </colgroup>
                        <tr>
                          <td class="dataTableHeadingContent" valign="top">Attribute</td>
                          <td class="dataTableHeadingContent" valign="top">Value</td>
                          <td></td>
                          <td>
                            <?php echo ' <a href="' . tep_href_link(FILENAME_BARCODES, 'pbID=' . $product_barcode['id'] . '&pID=' . $pID . '&action=del_barcode') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a>'; ?>
                          </td>
                        </tr>
                        
                        <?php
                        // List all the attributes and their values
                        $query_attributes_values = tep_db_query("select products_options_id options_id, products_options_name options_name, products_options_values_id values_id, products_options_values_name values_name from " . TABLE_PRODUCTS_OPTIONS . " po, " . TABLE_PRODUCTS_ATTRIBUTES . " pa, " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov, " . TABLE_PRODUCTS_BARCODES_TO_OPTIONS . " pbo where pov.products_options_values_id = options_values_id and pbo.products_attributes_id = pa.products_attributes_id and pa.products_id = " . $pID . " and po.products_options_id = pa.options_id and po.language_id = '" . $languages_id . "' and pov.language_id = po.language_id and pbo.products_barcodes_id = " . $product_barcode['id'] . " order by products_options_name, products_options_values_name");
                       
                        while ($attribute_value = tep_db_fetch_array($query_attributes_values))
                        {
                        ?>
                        <tr>
                          <td class="dataTableContent"><?php echo $attribute_value['options_name']; ?></td>
                          <td class="dataTableContent"><?php echo $attribute_value['values_name']; ?></td>
                          <td></td>
                          <td></td>
                        </tr>
                        <?php
                        }
                        ?>
                        
                        
                        <?php
                        // If active, we can add an option
                        if ($active_barcode == true)
                        {
                        ?>
                        <tr>
                          <td class="dataTableRow"></td>
                          <td class="dataTableRow"></td>
                          <td class="dataTableRow"></td>
                          <td class="dataTableRow"></td>
                        </tr>
                        
                        <tr>
                          <td>
                            <form action=<?php echo tep_href_link(FILENAME_BARCODES, tep_get_all_get_params()); ?> method="post">
                              <select name="aID" onchange='submit()'>
  
                                <?php
                                $query_attributes = tep_db_query("select distinct products_options_id id, products_options_name name from " . TABLE_PRODUCTS_OPTIONS . " po, " . TABLE_PRODUCTS_ATTRIBUTES . " pa where pa.products_id = " . $pID . " and po.products_options_id = pa.options_id and po.language_id = '" . $languages_id . "' and pa.options_id not in (select options_id from " . TABLE_PRODUCTS_ATTRIBUTES . " pa2, " . TABLE_PRODUCTS_BARCODES_TO_OPTIONS . " pbo2 where pa2.products_attributes_id = pbo2.products_attributes_id and pbo2.products_barcodes_id = " . $product_barcode['id'] . ") order by products_options_name");
  
                                if (isset($aID) == false)
                                  $aID = 0;
                                  
                                while ($attribute = tep_db_fetch_array($query_attributes)) {
                                  echo '<option ';
                                  if ($aID == 0)
                                  	$aID = $attribute['id'];
                                  if ($aID == $attribute['id'])
                                    echo 'SELECTED ';
                                  echo 'name="' . $attribute['name'] . '" value="' . $attribute['id'] . '">' . $attribute['name'] . '</option>';
                                } 
                                ?>
                              </select>
                            </form>
                          </td>

                          <form action=<?php echo tep_href_link(FILENAME_BARCODES, 'action=add_attribute&pbID=' . $pbID); ?> method="post">                          
                            <td>
                               <select name="paID">
  
                                <?php
                                $query_values = tep_db_query("select pa.products_attributes_id id, products_options_values_name name from " . TABLE_PRODUCTS_OPTIONS_VALUES . " v, " . TABLE_PRODUCTS_ATTRIBUTES . " pa where pa.options_id = " . $aID . " and v.products_options_values_id = pa.options_values_id and pa.products_id = " . $pID . " and v.language_id = '" . $languages_id . "' order by products_options_values_name");
  
                                if (isset($HTTP_GET_VARS['valueID'])) {
                                  $valueID = $HTTP_GET_VARS['valueID']; }
                                  
                                while ($value = tep_db_fetch_array($query_values)) {
                                  echo '<option ';
                                  if ($valueID == $value['id'])
                                    echo 'SELECTED ';
                                  echo 'name="' . $value['name'] . '" value="' . $value['id'] . '">' . $value['name'] . '</option>';
                                } 
                                ?>
                              </select>
                            </td>
                          
                            <td>
                              <?php echo tep_image_submit('button_insert.gif', IMAGE_INSERT); ?>
                            </td>
                          </form>
                          
                          <td>
                          </td>

                        </tr>
                        <?php
                        }
                        ?>
                      </table>
                    </td>

                  </tr>
                </table>
                <!-- A Table that contains a barcode : End -->
                
              </td>
            </tr>

            <?php
              }
            ?>            

          </table>
          <!-- Table that contains all the barcode tables : End -->
          
          <?php 
          echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;'; 
          echo ' <a href="' . tep_href_link(FILENAME_BARCODES, 'pID=' . $pID . '&action=new') . '">' . tep_image_button('button_new_barcode.gif') . '</a>';
          ?>
          
        </td>
      </tr>
    </table></td>
<?php
   break;
   
// New barcode
   case 'new':
?>

    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading">New Barcode for <?php echo $product_name ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td>
          Scan the barcode to be added
        </td>
      </tr>
    </table></td>

<?php
   break;

// Adding barcode : If we are here, it's because the code to be added alredy exists.
   case 'add':
?>

    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading">New Barcode for <?php echo $product_name ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td>
          <B>The code that was scanned alredy exists</B>
        </td>
      </tr>
      <tr>
        <td>
          <B><?php echo ' <a href="' . tep_href_link(FILENAME_BARCODES, 'pID=' . $pID) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>'; ?></B>
        </td>
      </tr>
    </table></td>

<?php
   break;
}
?>
    
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
