<?php
// get rid of the individual calls for files and replace it with the only one we need, application_top.php
// from here all other files necessary are also included.
header( 'Content-type: text/html; charset=utf-8' );
require('includes/application_top.php');
require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_SEARCHES);
$oID= $_GET['oID'];

// Output a form checkbox menu for product info page
  function tep_draw_checkbox_menu($name, $values, $default = '', $parameters = '', $required = false, $oID='', $url_id='',$url_title='',$products_options_name='',$options_id='',$productsattribute='' ) { ?>
  
<?php	$field ='<div class="url-holder">
<div style="display:inline;float:left"><form method="POST" id="'.$url_id.'"><input type="hidden" name="step" value="3">';
	$field .= '<input type="hidden" name="add_product_products_id" id="add_product_products_id" value="'. $url_id.'"><input type="hidden" name="product_search2" id="product_search" value="'.$url_title.'">';
	$field .= '<input type="hidden" name="add_product_option" value="'.$values.'">';
	$field ='</div>';   
	if (empty($default) && isset($GLOBALS[$name])) $default = stripslashes($GLOBALS[$name]);

    for ($i=0, $n=sizeof($values); $i<$n; $i++) {
	  $value = tep_output_string($values[$i]['id']);
	  $productsattributes = tep_output_string($productsattribute[$i]['id']);
	   $field .='<div class="url-holder">';
	  $field .="<script>
$(document).ready(function(){
        submit_form();
});	  
	  
function submit_form () {
var data = $('#".$productsattributes."').serialize();
  $.ajax({
  type : 'POST',
  url  : 'add_product.php?oID=".$oID."',
  data : data,
  success :  function(data) {
	 $('#add-products-block').html(data);
	 var data = $('#stepthree').serialize();
  $.ajax({
  type : 'POST',
 url  : 'add_product.php?oID=".$oID."',
  data : data,
  success :  function(data) {
	  $('#add-products-block').html(data);
	  var data = $('#qtyform').serialize();
  $.ajax({
  type : 'POST',
  url  : 'add_product.php?oID=".$oID."&action=add_product',
  data : data,
  success :  function(data) {
	 $('#add-products-block').load('add_product.php?oID=".$oID."&step=1&submitForm=yes&act=scanner');

	  }  
  });
	  
	  }  
  });
	  }  
  });
}

  </script>"; 

	
	 $field .='
<div style="display:inline;float:left">
<form method="POST" id="'.$productsattributes.'">
<input type="hidden" name="add_product_quantity" size="3" value="1">
<input type="hidden" name="step" value="3">';
$field .= '<input type="hidden" name="add_product_products_id" id="add_product_products_id" value="'. $url_id.'">';
$field .= '<input type="hidden" name="add_product_options['.$options_id.']" id="products_options_id" value="' . $value . '">';
$field .= '<input type="hidden" name="add_product_options_value" id="products_options_id" value="' . $value . '">';
$field .= '<input type="hidden" name="add_product_attributes_id" id="products_attribute_id" value="'.$value.':'. $productsattributes . '">';
$field .= '<input type="hidden" name="search" value="1">';
$field .= '<input type="hidden" name="product_search2" id="product_search" value="' .$products_options_name .' '. tep_output_string($values[$i]['text'], array('"' => '&quot;', '\'' => '&#039;', '<' => '&lt;', '>' => '&gt;')) . '"';
        $field .= 'readonly style="width:400px;  background:#fff; border:none; align:left;">
		<div style="font-size:12px !important; padding-left:20px; width:100%; cursor:pointer;" id="submitform'. $productsattributes . '">'.$products_options_name .' '. tep_output_string($values[$i]['text']).'</div></form></div></div>';
        
    }
	$field .= '';

    if ($required == true) $field .= TEXT_FIELD_REQUIRED;

    return $field;
  }
if(isset($_GET['query'])) { $query = $_GET['query']; } else { $query = ""; }
if(isset($_GET['type'])) { $type = $_GET['type']; } else { $query = "count"; }


$r = 0; //Set R

//here we can replace certain phrases that people may search for that are wrong, i have left my examples below.
//for example i have people add food or foods onto the end of search phrases, but food is rarely used in product names.
//or for if people add spaces where there shouldnt be or remove spaces when there should be


$query = str_replace(' food','',$query);
$query = str_replace(' food','',$query);
$query = str_replace('jameswellbeloved','james wellbeloved',$query);
$query = str_replace('jameswell beloved','james wellbeloved',$query);
$query = str_replace('ferrets','ferret',$query);
$query = str_replace('sawdust','wood shavings',$query);
$query = str_replace('saw dust','wood shavings',$query);
$query = str_replace('naturesdiet','naturediet',$query);
$query = str_replace('natures diet','naturediet',$query);
$query = str_replace('drjohns','dr johns',$query);

//Explode this query
$query_exploded = array();
$query_exploded = explode(' ',$query);


//We Have All Suggested Categories.
//Find Suggested Products
foreach($query_exploded as $g)
{
  //Prevent SQL Injection Attempts
  $g = str_replace("'",'',$g); //get rid of ' marks
  $g = str_replace(";",'',$g); //get rid of ;'s 
  $g = str_replace("*",'',$g); //get rid of *'s 
  $g = str_replace("(",'',$g); //get rid of ('s 
  $g = str_replace(")",'',$g); //get rid of )'s 
  
   $like_statement .= " (pd.products_name LIKE '%" . $g . "%' or products_upc  LIKE '%" . $g . "%') AND ";
}
//Remove the last and
$like_statement = substr($like_statement, 0, -4);

$sqlquery = "SELECT distinct(p.products_id),
                    pd.products_name,
                    p.products_price,
		    p.products_upc,
                    p.products_tax_class_id
					
             FROM " . TABLE_PRODUCTS_DESCRIPTION . " pd,
                  " . TABLE_PRODUCTS ." p 
             WHERE" . $like_statement . " AND
                    pd.products_id = p.products_id GROUP BY  pd.products_name";

$sqlresult = tep_db_query($sqlquery);

echo '';

while ($row = mysqli_fetch_assoc($sqlresult))
{
  $url_title = htmlspecialchars($row['products_name']);
  $url_id = $row['products_id'];
  foreach($query_exploded as $g)
  {
    $r++;
    //$url_title = str_ireplace($g,'<b>' . $g . '</b>',$url_title); //highlight what was typed
    $url_title = ucwords(strtolower($url_title));
  }
  $url_desc = ''; 
  $url_url = "";
  if($r > 10)
  {
    if($r > 50)
    {
      // http://www.linuxuk.co.uk FIMBLE. Altered the wording here, and made it bolder so customers can see it clearly
      $end =  MORE_RESULTS_1;

    }
    else
    {
      // http://www.linuxuk.co.uk FIMBLE. Altered the wording here, and made it bolder so customers can see it clearly
      $end =  MORE_RESULTS_2;

    }
  }
  else
  {
    // http://www.linuxuk.co.uk FIMBLE. Include a query to get not only the price but the Prefix, Tax, and Specials
    // and display it to the container.
    /*if($new_price = tep_get_products_special_price($row['products_id']))
    {
      $price = '<s>' . $currencies->display_price($row['products_price'], tep_get_tax_rate($row['products_tax_class_id'])) . '</s> <span class="productSpecialPrice">' . $currencies->display_price($new_price, tep_get_tax_rate($row['products_tax_class_id'])) . '</span>';
    }
    else
    {
      $price = $currencies->display_price($row['products_price'], tep_get_tax_rate($row['products_tax_class_id']));
    } */
    //$addlink = '<a href="' . $url_url . '&action=buy_now&sort=2a">' . BUY_NOW . '</a>'; //Add To Cart Button
    
 ?>


<div class="url-holder">
<div style="display:inline;float:left; width:100%;">
 <form method="POST" id="<?php echo $url_id; ?>"><input type="hidden" name="step" value="3"><input type="hidden" name="add_product_products_id" id="add_product_products_id" value="<?php echo $url_id; ?>"><input type="hidden" name="search" value="1">
<input type="hidden" name="product_search" id="product_search" value="<?php echo $url_title; ?>" readonly style="width:100%;  background:#fff; border:none; align:left; cursor:pointer;" onClick="this.form.submit();">
<div style="font-size:12px !important; width:100%; cursor:pointer;" id="submitform<?php echo $url_id; ?>"><?php echo $url_title; ?></div>
<input type="submit" value="add" style="display:none;">
</form></div>
<script>

$(document).ready(function(){
        submit_form2();
});	  
	  
function submit_form2 () {
 {				  var data = $('#<?php echo $url_id; ?>').serialize();
  $.ajax({
  type : 'POST',
  url  : 'add_product.php?oID=<?php echo $oID; ?>',
  data : data,
  success :  function(data) {
	 $("#add-products-block").html(data);
	 var data = $('#qtyform').serialize();
  $.ajax({
  type : 'POST',
  url  : 'add_product.php?oID=<?php echo $oID; ?>&action=add_product',
  data : data,
  success :  function(data) {
	 $("#add-products-block").load('add_product.php?oID=<?php echo $oID; ?>&step=1&submitForm=yes&act=scanner');

	  }  
  });
	  }  
  });
 
 };
}
  </script>
</div>

<?php
  } 
}
$like_statement = '';
echo '<hr>';

//Find Suggested Products
foreach($query_exploded as $g)
{
  //Prevent SQL Injection Attempts
  $g = str_replace("'",'',$g); //get rid of ' marks
  $g = str_replace(";",'',$g); //get rid of ;'s 
  $g = str_replace("*",'',$g); //get rid of *'s 
  $g = str_replace("(",'',$g); //get rid of ('s 
  $g = str_replace(")",'',$g); //get rid of )'s 
  
   $like_statement .= " (pa.options_upc LIKE '%" . $g . "%' or pa.options_serial_no LIKE '%" . $g . "%') AND ";
}
//Remove the last and
$like_statement = substr($like_statement, 0, -4);

$sqlquery = "SELECT distinct(pa.options_values_id),
                    pd.products_name,
					p.products_id,
                    p.products_price,
                    p.products_tax_class_id,
					pa.options_serial_no
             FROM " . TABLE_PRODUCTS_DESCRIPTION . " pd,
                  " . TABLE_PRODUCTS ." p left join " . TABLE_PRODUCTS_ATTRIBUTES . " pa on pa.products_id = p.products_id 
             WHERE" . $like_statement . " AND
                    pd.products_id = p.products_id GROUP BY  pd.products_name";

$sqlresult = tep_db_query($sqlquery);

echo '';

while ($row = mysqli_fetch_assoc($sqlresult))
{
  $url_title = htmlspecialchars($row['products_name']);
  $url_id = $row['products_id'];
  $products_attributes_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" . (int)$url_id . "' and patrib.options_id = popt.products_options_id and popt.language_id = '" . (int)$languages_id . "'");

    $products_attributes = tep_db_fetch_array($products_attributes_query);

    if ($products_attributes['total'] > 0) {

      $products_options_name_query = tep_db_query("select distinct popt.products_options_id, patrib.options_id, popt.products_options_name from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" . (int)$url_id . "' and patrib.options_id = popt.products_options_id and popt.language_id = '" . (int)$languages_id . "' order by popt.products_options_name");

      while ($products_options_name = tep_db_fetch_array($products_options_name_query)) {
	$options_id = $products_options_name['options_id'];
        $products_options_array = array();
		$productsattribute = array();

        $products_options_query = tep_db_query("select pov.products_options_values_id, pov.products_options_values_name, pa.options_values_price, pa.price_prefix, pa.options_serial_no, pa.options_upc, pa.options_id, pa.products_attributes_id from " . TABLE_PRODUCTS_ATTRIBUTES . " pa, " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov where pa.products_id = '" . (int)$url_id . "' and pa.options_id = '" . (int)$products_options_name['products_options_id'] . "' and pa.options_values_id = pov.products_options_values_id and " . $like_statement . "and pov.language_id = '" . (int)$languages_id . "' order by pa.products_options_sort_order");

        while ($products_options = tep_db_fetch_array($products_options_query)) {

          $products_options_array[] = array('id' => $products_options['products_options_values_id'], 'text' => $products_options['products_options_values_name']. ' ('.$products_options['options_serial_no'].') ');
		$productsattribute[] = array('id' => $products_options['products_attributes_id']);  
        }

 	  if(isset($_POST['add_product_options'])) {
          $selected_attribute = $_POST['add_product_options'][$products_options_name['products_options_id']];
        } else {
          $selected_attribute = false;
        } 
    $attrib = tep_draw_checkbox_menu('id[' . $products_options_name['products_options_id'] . ']', $products_options_array, $selected_attribute,'','',$oID,$url_id,$url_title,$products_options_name['products_options_name'],$options_id, $productsattribute); 
	
}
}
  foreach($query_exploded as $g)
  {
    $r++;
    //$url_title = str_ireplace($g,'<b>' . $g . '</b>',$url_title); //highlight what was typed
    $url_title = ucwords(strtolower($url_title));
  }
  $url_desc = ''; 
  $url_url = "";
  if($r > 10)
  {
    if($r > 50)
    {
      // http://www.linuxuk.co.uk FIMBLE. Altered the wording here, and made it bolder so customers can see it clearly
      $end =  MORE_RESULTS_1;

    }
    else
    {
      // http://www.linuxuk.co.uk FIMBLE. Altered the wording here, and made it bolder so customers can see it clearly
      $end =  MORE_RESULTS_2;

    }
  }
  else
  {
    // http://www.linuxuk.co.uk FIMBLE. Include a query to get not only the price but the Prefix, Tax, and Specials
    // and display it to the container.
    /*if($new_price = tep_get_products_special_price($row['products_id']))
    {
      $price = '<s>' . $currencies->display_price($row['products_price'], tep_get_tax_rate($row['products_tax_class_id'])) . '</s> <span class="productSpecialPrice">' . $currencies->display_price($new_price, tep_get_tax_rate($row['products_tax_class_id'])) . '</span>';
    }
    else
    {
      $price = $currencies->display_price($row['products_price'], tep_get_tax_rate($row['products_tax_class_id']));
    } */
    //$addlink = '<a href="' . $url_url . '&action=buy_now&sort=2a">' . BUY_NOW . '</a>'; //Add To Cart Button
    
?>
<div class="url-holder">
<div style="display:inline;float:left; width:100%;">
<input type="hidden" name="add_product_products_id" id="add_product_products_id" value="<?php echo $url_id; ?>">
<input type="text"  value="<?php echo $url_title; ?>" readonly style="width:100%; border:none; align:left; font-weight:bold">
<input type="submit" value="add" style="display:none;">

</div>
</div>
<?php 
echo $attrib ?>
  <?php
echo $end;

if($r == 0){
  // http://www.linuxuk.co.uk FIMBLE. Nothing found? Lets add a link to the advanced search then
  echo '<div style="border-top:1px" class="url-holder-more"><a href="' . tep_href_link(FILENAME_ADVANCED_SEARCH_RESULT, 'keywords=' . str_replace(' ','+',$query)) . '">' . $query.'</a></div>';
}
  } 
}
?>
