<?php

/* $Id$

Digistore v4.0,  Open Source E-Commerce Solutions

http://www.digistore.co.nz

Copyright (c) 2002 osCommerce



Released under the GNU General Public License

xsell.php

Original Idea From Isaac Mualem im@imwebdesigning.com <mailto:im@imwebdesigning.com>

Complete Recoding From Stephen Walker admin@snjcomputers.com

*/

  require('includes/application_top.php');

  require(DIR_WS_CLASSES . 'currencies.php');

   $currencies = new currencies();

  switch($_GET['action']){

          case 'update_cross' :

                if ($_POST['product']){

            foreach ($_POST['product'] as $temp_prod){

          tep_db_query('delete from ' . TABLE_PRODUCTS_XSELL . ' where xsell_id = "'.$temp_prod.'" and products_id = "'.$_GET['add_related_product_ID'].'"');

            }

          }



                $sort_start_query = tep_db_query('select sort_order from ' . TABLE_PRODUCTS_XSELL . ' where products_id = "'.$_GET['add_related_product_ID'].'" order by sort_order desc limit 1');

        $sort_start = tep_db_fetch_array($sort_start_query);



            $sort = (($sort_start['sort_order'] > 0) ? $sort_start['sort_order'] : '0');

                if ($_POST['cross']){

        foreach ($_POST['cross'] as $temp){

                        $sort++;

                        $insert_array = array();

                        $insert_array = array('products_id' => $_GET['add_related_product_ID'],

                                                  'xsell_id' => $temp,

                                                  'sort_order' => $sort);

              tep_db_perform(TABLE_PRODUCTS_XSELL, $insert_array);

                }

                }

        $messageStack->add(CROSS_SELL_SUCCESS, 'success');

        //Cache

        $cachedir = DIR_FS_CACHE_XSELL . $_GET['add_related_product_ID'];

        if(is_dir($cachedir))

		 {

		  rdel($cachedir);

		 }

        //Fin Cache

           break;

          case 'update_sort' :

        foreach ($_POST as $key_a => $value_a){

         tep_db_query('update ' . TABLE_PRODUCTS_XSELL . ' set sort_order = "' . $value_a . '" where xsell_id = "' . $key_a . '"');

            }

        $messageStack->add(SORT_CROSS_SELL_SUCCESS, 'success');

           break;

  }

?>

<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">

<html <?php echo HTML_PARAMS; ?>>

<head>

<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">

<title><?php echo TITLE; ?></title>

<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">

<style>

.productmenutitle{

cursor:pointer;

margin-bottom: 0px;

background-color:orange;

color:#FFFFFF;

font-weight:bold;

font-family:ms sans serif;

width:100%;

padding:3px;

font-size:12px;

text-align:center;

/*/*/border:1px solid #000000;/* */

}

.productmenutitle1{

cursor:pointer;

margin-bottom: 0px;

background-color: red;

color:#FFFFFF;

font-weight:bold;

font-family:ms sans serif;

width:100%;

padding:3px;

font-size:12px;

text-align:center;

/*/*/border:1px solid #000000;/* */

}

</style>

<script language="JavaScript1.2">



function cOn(td)

{

if(document.getElementById||(document.all && !(document.getElementById)))

{

td.style.backgroundColor="#CCCCCC";

}

}



function cOnA(td)

{

if(document.getElementById||(document.all && !(document.getElementById)))

{

td.style.backgroundColor="#CCFFFF";

}

}



function cOut(td)

{

if(document.getElementById||(document.all && !(document.getElementById)))

{

td.style.backgroundColor="DFE4F4";

}

}

</script>

<script language="javascript" src="includes/general.js"></script>

</head>

<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">

<!-- body //-->

<table width="1000px" border="0" align="center" cellpadding="10" cellspacing="0" bgcolor="#FFFFFF" style="border:solid 1px; border-color:#999999;">

  <tr>

    

<!-- body_text //-->

    <td width="100%" valign="top">

	<!-- header //-->

	<?php

  require(DIR_WS_INCLUDES . 'header.php');

?>

<!-- header_eof //-->

  <table border="0" width="100%" cellspacing="0" cellpadding="0">

   <tr>

    <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10');?></td>

   </tr>

   <tr>

    <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>

   </tr>

   <tr>

    <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '15');?></td>

    <td class="smallText" align="right">

<?php

    echo tep_draw_form('search', FILENAME_XSELL_PRODUCTS, '', 'get'). tep_draw_hidden_field('add_related_product_ID', $add_related_product_ID);

    echo HEADING_TITLE_SEARCH . ' ' . tep_draw_input_field('search');

    echo '</form>';

?>

                </td>

   </tr>

   

  </table>



<?php

  if ($_GET['add_related_product_ID'] == ''){

?>

  <table border="0" cellspacing="1" cellpadding="2" bgcolor="#999999" align="center">

   <tr class="dataTableHeadingRow">

    <td class="dataTableHeadingContent" width="75"><?php echo TABLE_HEADING_PRODUCT_ID;?></td>

    <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_PRODUCT_MODEL;?></td>

    <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_PRODUCT_NAME;?></td>

    <td class="dataTableHeadingContent" nowrap><?php echo TABLE_HEADING_CURRENT_SELLS;?></td>

    <td class="dataTableHeadingContent" colspan="2" nowrap align="center"><?php echo TABLE_HEADING_UPDATE_SELLS;?></td>

   </tr>

<?php

if (isset($HTTP_GET_VARS['search'])) {

      $search = tep_db_prepare_input($HTTP_GET_VARS['search']);

    $products_query_raw = 'select p.products_id, p.products_model, p.products_price, pd.products_name, p.products_id from '.TABLE_PRODUCTS.' p, '.TABLE_PRODUCTS_DESCRIPTION.' pd where p.products_id = pd.products_id and pd.language_id = "'.(int)$languages_id.'" and pd.products_name like "%' . tep_db_input($search) . '%" order by pd.products_name asc';}else{$products_query_raw = 'select p.products_id, p.products_model, pd.products_name, p.products_price, p.products_id from '.TABLE_PRODUCTS.' p, '.TABLE_PRODUCTS_DESCRIPTION.' pd where p.products_id = pd.products_id and pd.language_id = "'.(int)$languages_id.'" order by pd.products_name asc';}

    $products_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $products_query_raw, $products_query_numrows);

    $products_query = tep_db_query($products_query_raw);

    while ($products = tep_db_fetch_array($products_query)) {

?>

   <tr onMouseOver="cOn(this); this.style.cursor='pointer'; this.style.cursor='hand';" onMouseOut="cOut(this);" bgcolor='#DFE4F4' onClick=document.location.href="<?php echo tep_href_link(FILENAME_XSELL_PRODUCTS, 'add_related_product_ID=' . $products['products_id'], 'NONSSL');?>">

    <td class="dataTableContent" valign="top">&nbsp;<?php echo $products['products_id'];?>&nbsp;</td>

    <td class="dataTableContent" valign="top">&nbsp;<?php echo $products['products_model'];?>&nbsp;</td>

    <td class="dataTableContent" valign="top">&nbsp;<?php echo $products['products_name'];?>&nbsp;</td>

    <td class="dataTableContent" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">

<?php

    $products_cross_query = tep_db_query('select p.products_id, p.products_model, pd.products_name, p.products_id, x.products_id, x.xsell_id, x.sort_order, x.ID from '.TABLE_PRODUCTS.' p, '.TABLE_PRODUCTS_DESCRIPTION.' pd, '.TABLE_PRODUCTS_XSELL.' x where x.xsell_id = p.products_id and x.products_id = "'.$products['products_id'].'" and p.products_id = pd.products_id and pd.language_id = "'.(int)$languages_id.'" order by x.sort_order asc');

        $i=0;

    while ($products_cross = tep_db_fetch_array($products_cross_query)){

                $i++;

?>

         <tr>

          <td class="dataTableContent">&nbsp;<?php echo $i . '.&nbsp;&nbsp;<b>' . $products_cross['products_model'] . '</b>&nbsp;' . $products_cross['products_name'];?>&nbsp;</td>

         </tr>

<?php

        }

    if ($i <= 0){

?>

         <tr>

          <td class="dataTableContent">&nbsp;--&nbsp;</td>

         </tr>

<?php

        }else{

?>

         <tr>

          <td class="dataTableContent"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10');?></td>

         </tr>

<?php

}

?>

    </table></td>

    <td class="dataTableContent" valign="top">&nbsp;<a href="<?php echo tep_href_link(FILENAME_XSELL_PRODUCTS, tep_get_all_get_params(array('action')) . 'add_related_product_ID=' . $products['products_id'], 'NONSSL');?>"><?php echo TEXT_EDIT_SELLS;?></a>&nbsp;</td>

    <td class="dataTableContent" valign="top" align="center">&nbsp;<?php echo (($i > 0) ? '<a href="' . tep_href_link(FILENAME_XSELL_PRODUCTS, tep_get_all_get_params(array('action')) . 'sort=1&add_related_product_ID=' . $products['products_id'], 'NONSSL') .'">'.TEXT_SORT.'</a>&nbsp;' : '--')?></td>

   </tr>

<?php

        }

?>

   <tr>

    <td colspan="6"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="infoBoxContent">

     <tr>

      <td class="smallText" valign="top"><?php echo $products_split->display_count($products_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_PRODUCTS); ?></td>

      <td class="smallText" align="right"><?php echo $products_split->display_links($products_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page'], tep_get_all_get_params(array('page', 'info', 'x', 'y', 'cID', 'action'))); ?></td>

     </tr>

    </table></td>

   </tr>

  </table>

<?php

}elseif($HTTP_GET_VARS['add_related_product_ID'] != '' && $HTTP_GET_VARS['sort'] == ''){

        $products_name_query = tep_db_query('select pd.products_name, p.products_model, p.products_image, p.products_price from '.TABLE_PRODUCTS.' p, '.TABLE_PRODUCTS_DESCRIPTION.' pd where p.products_id = "'.$HTTP_GET_VARS['add_related_product_ID'].'" and p.products_id = pd.products_id and pd.language_id ="'.(int)$languages_id.'"');

        $products_name = tep_db_fetch_array($products_name_query);

?>

  <table border="0" cellspacing="0" cellpadding="0" bgcolor="#999999" align="center">

   <tr>

    <td><?php echo tep_draw_form('update_cross', FILENAME_XSELL_PRODUCTS, tep_get_all_get_params(array('action')) . 'action=update_cross', 'post');?><table cellpadding="1" cellspacing="1" border="0">

         <tr>

          <td colspan="6"><table cellpadding="3" cellspacing="0" border="0" width="100%">

           <tr class="dataTableHeadingRow">

            <td valign="top" align="center" colspan="2"><span class="pageHeading"><?php echo TEXT_SETTING_SELLS.$products_name['products_name'].' ('.TEXT_MODEL.': '.$products_name['products_model'].') ('.TEXT_PRODUCT_ID.': '.$_GET['add_related_product_ID'].')';?></span></td>

           </tr>

           <tr class="dataTableHeadingRow">

            <td align="right"><?php echo tep_image(DIR_WS_CATALOG_IMAGES . '/'.$products_name['products_image'], "", SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT);?></td>

            <td align="right" valign="bottom"><?php echo tep_image_submit('button_update.gif') . '<br><br><a href="'.tep_href_link(FILENAME_XSELL_PRODUCTS, 'men_id=catalog').'">' . tep_image_button('button_cancel.gif') . '</a>';?></td>

           </tr>

          </table></td>

         </tr>

     <tr class="dataTableHeadingRow">

      <td class="dataTableHeadingContent" width="75">&nbsp;<?php echo TABLE_HEADING_PRODUCT_ID;?>&nbsp;</td>

      <td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_PRODUCT_MODEL;?>&nbsp;</td>

          <td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_PRODUCT_IMAGE;?>&nbsp;</td>

          <td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_CROSS_SELL_THIS;?>&nbsp;</td>

      <td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_PRODUCT_NAME;?>&nbsp;</td>

          <td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_PRODUCT_PRICE;?>&nbsp;</td>

         </tr>

<?php

    if (isset($HTTP_GET_VARS['search'])) {

      $search = tep_db_prepare_input($HTTP_GET_VARS['search']);

    $products_query_raw = 'select p.products_id, p.products_model, p.products_price, pd.products_name, p.products_id from '.TABLE_PRODUCTS.' p, '.TABLE_PRODUCTS_DESCRIPTION.' pd where p.products_id = pd.products_id and pd.language_id = "'.(int)$languages_id.'" and pd.products_name like "%' . tep_db_input($search) . '%" order by pd.products_name asc';}else{$products_query_raw = 'select p.products_id, p.products_model, pd.products_name, p.products_price, p.products_id from '.TABLE_PRODUCTS.' p, '.TABLE_PRODUCTS_DESCRIPTION.' pd where p.products_id = pd.products_id and pd.language_id = "'.(int)$languages_id.'" order by pd.products_name asc';}

    $products_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $products_query_raw, $products_query_numrows);

    $products_query = tep_db_query($products_query_raw);

    while ($products = tep_db_fetch_array($products_query)) {

                $xsold_query = tep_db_query('select * from '.TABLE_PRODUCTS_XSELL.' where products_id = "'.$_GET['add_related_product_ID'].'" and xsell_id = "'.$products['products_id'].'"');

?>

         <tr bgcolor='#DFE4F4'>

          <td class="dataTableContent" align="center">&nbsp;<?php echo $products['products_id'];?>&nbsp;</td>

          <td class="dataTableContent" align="center">&nbsp;<?php echo $products['products_model'];?>&nbsp;</td>

          <td class="dataTableContent" align="center">&nbsp;<?php echo ((is_file(DIR_WS_CATALOG_IMAGES . '/'.$products['products_image'])) ?  tep_image(DIR_WS_CATALOG_IMAGES . '/'.$products['products_image'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) : '<br>No Image<br>');?>&nbsp;</td>

          <td class="dataTableContent">&nbsp;<?php echo tep_draw_hidden_field('product[]', $products['products_id']) . tep_draw_checkbox_field('cross[]', $products['products_id'], ((tep_db_num_rows($xsold_query) > 0) ? true : false), '', ' onMouseOver="this.style.cursor=\'hand\'"');?>&nbsp;<label onMouseOver="this.style.cursor='hand'"><?php echo TEXT_CROSS_SELL;?></label>&nbsp;</td>

          <td class="dataTableContent">&nbsp;<?php echo $products['products_name'];?>&nbsp;</td>

          <td class="dataTableContent">&nbsp;<?php echo $currencies->format($products['products_price']);?>&nbsp;</td>

         </tr>

<?php

    }

?>

        </table></form></td>

   </tr>

   <tr>

    <td colspan="6"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="infoBoxContent">

     <tr>

      <td class="smallText" valign="top"><?php echo $products_split->display_count($products_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_CUSTOMERS); ?></td>

      <td class="smallText" align="right"><?php echo $products_split->display_links($products_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page'], tep_get_all_get_params(array('page', 'info', 'x', 'y', 'cID', 'action'))); ?></td>

     </tr>

    </table></td>

   </tr>

  </table>

<?php

}elseif($HTTP_GET_VARS['add_related_product_ID'] != '' && $HTTP_GET_VARS['sort'] != ''){

        $products_name_query = tep_db_query('select pd.products_name, p.products_model, p.products_image, p.products_price from '.TABLE_PRODUCTS.' p, '.TABLE_PRODUCTS_DESCRIPTION.' pd where p.products_id = "'.$HTTP_GET_VARS['add_related_product_ID'].'" and p.products_id = pd.products_id and pd.language_id ="'.(int)$languages_id.'"');

        $products_name = tep_db_fetch_array($products_name_query);

?>

  <table border="0" cellspacing="0" cellpadding="0" bgcolor="#999999" align="center">

   <tr>

    <td><?php echo tep_draw_form('update_sort', FILENAME_XSELL_PRODUCTS, tep_get_all_get_params(array('action')) . 'action=update_sort', 'post');?><table cellpadding="1" cellspacing="1" border="0">

         <tr>

          <td colspan="6"><table cellpadding="3" cellspacing="0" border="0" width="100%">

           <tr class="dataTableHeadingRow">

            <td valign="top" align="center" colspan="2"><span class="pageHeading"><?php echo TEXT_SETTING_SELLS.': '.$products_name['products_name'].' ('.TEXT_MODEL.': '.$products_name['products_model'].') ('.TEXT_PRODUCT_ID.': '.$_GET['add_related_product_ID'].')';?></span></td>

           </tr>

           <tr class="dataTableHeadingRow">

            <td align="right"><?php echo tep_image(DIR_WS_CATALOG_IMAGES . '/'.$products_name['products_image'], "", SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT);?></td>

            <td align="right" valign="bottom"><?php echo tep_image_submit('button_update.gif') . '<br><br><a href="'.tep_href_link(FILENAME_XSELL_PRODUCTS, 'men_id=catalog').'">' . tep_image_button('button_cancel.gif') . '</a>';?></td>

           </tr>

          </table></td>

         </tr>

     <tr class="dataTableHeadingRow">

          <td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_PRODUCT_ID;?>&nbsp;</td>

          <td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_PRODUCT_MODEL;?>&nbsp;</td>

          <td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_PRODUCT_IMAGE;?>&nbsp;</td>

          <td class="dataTableHeadingContent" align="center">&nbsp;<?php echo TABLE_HEADING_PRODUCT_NAME;?>&nbsp;</td>

          <td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_PRODUCT_PRICE;?>&nbsp;</td>

          <td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_PRODUCT_SORT;?>&nbsp;</td>

         </tr>

<?php

    $products_query_raw = 'select p.products_id as products_id, p.products_price, p.products_image, p.products_model, pd.products_name, p.products_id, x.products_id as xproducts_id, x.xsell_id, x.sort_order, x.ID from '.TABLE_PRODUCTS.' p, '.TABLE_PRODUCTS_DESCRIPTION.' pd, '.TABLE_PRODUCTS_XSELL.' x where x.xsell_id = p.products_id and x.products_id = "'.$HTTP_GET_VARS['add_related_product_ID'].'" and p.products_id = pd.products_id and pd.language_id = "'.(int)$languages_id.'" order by x.sort_order asc';

    $products_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $products_query_raw, $products_query_numrows);

        $sort_order_drop_array = array();

        for($i=1;$i<=$products_query_numrows;$i++){

        $sort_order_drop_array[] = array('id' => $i, 'text' => $i);

        }

    $products_query = tep_db_query($products_query_raw);

 while ($products = tep_db_fetch_array($products_query)){

?>

         <tr bgcolor='#DFE4F4'>

          <td class="dataTableContent" align="center">&nbsp;<?php echo $products['products_id'];?>&nbsp;</td>

          <td class="dataTableContent" align="center">&nbsp;<?php echo $products['products_model'];?>&nbsp;</td>

          <td class="dataTableContent" align="center">&nbsp;<?php echo ((is_file(DIR_WS_CATALOG_IMAGES . '/'.$products['products_image'])) ?  tep_image(DIR_WS_CATALOG_IMAGES . '/'.$products['products_image'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) : '<br>'.TEXT_NO_IMAGE.'<br>');?>&nbsp;</td>

          <td class="dataTableContent" align="center">&nbsp;<?php echo $products['products_name'];?>&nbsp;</td>

          <td class="dataTableContent" align="center">&nbsp;<?php echo $currencies->format($products['products_price']);?>&nbsp;</td>

          <td class="dataTableContent" align="center">&nbsp;<?php echo tep_draw_pull_down_menu($products['products_id'], $sort_order_drop_array, $products['sort_order']);?>&nbsp;</td>

     </tr>

<?php

}

?>

    </table></form></td>

   </tr>

   <tr>

    <td colspan="6"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="infoBoxContent">

     <tr>

      <td class="smallText" valign="top"><?php echo $products_split->display_count($products_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_PRODUCTS); ?></td>

      <td class="smallText" align="right"><?php echo $products_split->display_links($products_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page'], tep_get_all_get_params(array('page', 'info', 'x', 'y', 'cID', 'action'))); ?></td>

     </tr>

    </table></td>

   </tr>

  </table>

<?php

}

?>

<!-- body_text_eof //-->

  </td>

 </tr>

</table>

<!-- body_eof //-->

<!-- footer //-->

<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>

<!-- footer_eof //-->

</body>

</html>

<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>