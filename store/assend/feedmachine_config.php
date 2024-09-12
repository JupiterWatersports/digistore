<?php

/*
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

/*
Google Product feed configuration for The Feedmachine Solution
based on google-simple.php by: Lech Madrzyk
----------------------------
This configuration is complient with the Google-Feed specifications of march 2012.
It has to be used together with the modified feedmachine.php file which includes the 'IS_IN_STOCK' Keyword definition.
*/

$feed_config = array('name' => 'Google Product Search',
                     'authors' => 'raiwa',
                     'filename' => 'google-product-search-feed-us-en.txt', //change the name and the filename to a unique name for each language and country
                     'schema_version' => '2.0',
                     'fields' => array('id'               =>   array('output' => 'FM_RS_product_id_us_en', //change the name to the name used for the function
                                                                     'type' => 'FUNCTION'
                                                                    ),
                                       'title'            =>   array('output' => 'products_name',
                                                                     'type' => 'DB',
                                                                     'options' => array('STRIP_HTML', 'STRIP_CRLF')
                                                                    ),
                                       'price'            =>   array('output' => 'FINAL_PRICE_WITH_TAX',
                                                                     'type' => 'KEYWORD',
                                                                    ),
                                       'brand'            =>   array('output' => 'manufacturers_name',
                                                                     'type' => 'DB',
                                                                     'options' => array('STRIP_HTML', 'HTML_ENTITIES', 'STRIP_CRLF')
                                                                    ),
                                       'mpn'              =>   array('output' => 'products_model',
                                                                     'type' => 'DB'
                                                                    ),
                         'google_product_category'        =>   array('output' => 'FM_RS_google_categories_us_en',  //change the name to the name used for the function
                                                                     'type' => 'FUNCTION'
                                                                    ),
                                       'product_type'     =>   array('output' => 'CATEGORY_TREE',
                                                                     'type' => 'KEYWORD',
                                                                     'options' => array('STRIP_HTML', 'STRIP_CRLF')
                                                                    ),
                                       'link'             =>   array('output' => 'PRODUCTS_URL',
                                                                     'type' => 'KEYWORD'
                                                                    ),
                                       'image_link'       =>   array('output' => 'IMAGE_URL',
                                                                     'type' => 'KEYWORD'
                                                                    ),
                                       'condition'        =>   array('output' => 'new', //change to 'used' or 'refurbished' if needed
                                                                     'type' => 'VALUE'
                                                                    ),
                                       'description'      =>   array('output' => 'products_description',
                                                                     'type' => 'DB',
                                                                     'options' => array('STRIP_HTML', 'STRIP_CRLF')
                                                                    ),
                                       'shipping_weight'  =>   array('output' => 'FM_RS_shipping_weight_and_unit',
                                                                     'type' => 'FUNCTION',
                                                                    ),
                                       'availability'        =>   array('output' => 'IS_IN_STOCK',
                                                                     'type' => 'KEYWORD'
                                                                    ),
                                       'gender'        =>   array('output' => 'female', //change to male or unisex if needed
                                                                     'type' => 'VALUE'
                                                                    ),
                                       'age_group'        =>   array('output' => 'adult', //change to kids if needed
                                                                     'type' => 'VALUE'
                                                                    ),
                                       'color'        =>   array('output' => 'FM_RS_google_colors_us_en', //change the name to the name used for the function
                                                                     'type' => 'FUNCTION',
                                                                    ),
                                       'size'        =>   array('output' => 'FM_RS_google_sizes_us_en', //change the name to the name used for the function
                                                                     'type' => 'FUNCTION',
                                                                    ),
                                       'material'        =>   array('output' => 'products_material',
                                                                     'type' => 'DB',
                                                                     'options' => array('STRIP_HTML', 'STRIP_CRLF')
                                                                    ),
                                       'pattern'        =>   array('output' => 'products_pattern',
                                                                     'type' => 'DB',
                                                                     'options' => array('STRIP_HTML', 'STRIP_CRLF')
                                                                    ),
                                       'online_only'        =>   array('output' => 'y', //change to 'n' if your  shop is physical
                                                                     'type' => 'VALUE'
                                                                    ) //here no comma ( , )  if it's the last line
                                      ),
                     'currency_decimal_override' => false,
                     'currency_thousands_override' => '',
                     'add_field_names' => true,
                     'category_tree_seperator' => ' ',
                     'seperator' => "\t",
                     'text_qualifier' => '',
                     'newline' => "\n",
                     'encoding' => 'false',
                     'include_record_function' => ''
                    );

//FEED FUNCTIONS BEGIN

function FM_RS_product_id_us_en($product) { //change the name to a unique name for each language and country
  return 'Your_shops_shortname_' . $product['products_id'] . '_us_en';
}

function FM_RS_google_categories_us_en($product) { //change the name to a unique name for each language and country
	$output_field_category = ($product['parent_id'] > 0) ? $product['parent_id'] : $product['categories_id'];
	return (($output_field_category == 1) ? 'Google > Category > Tree1' :
		(($output_field_category == 2) ? 'Google > Category > Tree2':
		 (($output_field_category == 3) ? 'Google > Category > Tree3':
		  (($output_field_category == 4) ? 'Google > Category > Tree4':
		   (($output_field_category == 5) ? 'Google > Category > Tree5':
		    (($output_field_category == 6) ? 'Google > Category > Tree6':
		     (($output_field_category == 7) ? 'Google > Category > Tree7':
		      (($output_field_category == 8) ? 'Google > Category > Tree8':
		       (($output_field_category == 9) ? 'Google > Category > Tree9':
			(($output_field_category == 10) ? 'Google > Category > Tree10':
			 (($output_field_category == 11) ? 'Google > Category > Tree11':
			  (($output_field_category == 12) ? 'Google > Category > Tree12':
			   (($output_field_category == 13) ? 'Google > Category > Tree13':
			    (($output_field_category == 14) ? 'Google > Category > Tree14':
			     (($output_field_category == 15) ? 'Google > Category > Tree15':
			      (($output_field_category == 10000) ? '':
			       (($output_field_category == 10000) ? '':
				(($output_field_category == 10000) ? '':
				 (($output_field_category == 10000) ? '':
				  (($output_field_category == 10000) ? '':
				  	  ''))))))))))))))))))));
	}

function FM_RS_google_condition_us_en($product) { //change the name to a unique name for each language and country
$condition_query = tep_db_query("select goods from products where products_id = " . $product['products_id']."");
	while($condition = tep_db_fetch_array($condition_query)) {
          return $condition['goods'];
	}
}

function FM_RS_google_gender_us_en($product) { //change the name to a unique name for each language and country
$gender_query = tep_db_query("select gender from products where products_id = " . $product['products_id']."");
	while($gender = tep_db_fetch_array($gender_query)) {
          return $gender['gender'];
	}
}

function FM_RS_google_age_group_us_en($product) { //change the name to a unique name for each language and country
$age_group_query = tep_db_query("select age_group from products where products_id = " . $product['products_id']."");
	while($age_group = tep_db_fetch_array($age_group_query)) {
          return $age_group['age_group'];
	}
}


function FM_RS_google_colors_us_en($product) { //change the name to a unique name for each language and country
	$color_query = tep_db_query("select colour from products where products_id = " . $product['products_id']."");
	while($color = tep_db_fetch_array($color_query)) {
	$colors .= $color["colour"] .", ";
	}
	//$replace_color = array('01-', '02-', '03-', '04-', '05-', '06-', '07-', '08-', '09-','10-', '11-', '12-', '1-', '2-', '3-', '4-', '5-', '6-', 'A-', 'B-', 'C-');
	//$colors = str_replace($replace_color, '', $colors);
	$colors = rtrim($colors, ", ");	//this strips the last comma and white space
	(($colors == '')? $colors = $product['color'] : '');
	return $colors;
}

function FM_RS_google_sizes_us_en($product) { //change the name to a unique name for each language and country
	$size_query = tep_db_query("select size from products where products_id = " . $product['products_id']."");
	while($size = tep_db_fetch_array($size_query)) {
	$sizes .= $size["size"] .", ";
	}
	//$replace_size = array('01-', '02-', '03-', '04-', '05-', '06-', '07-', '08-', '09-','10-', '11-', '12-', '1-', '2-', '3-', '4-', '5-', '6-', 'A-', 'B-', 'C-');
	//$sizes = str_replace($replace_size, '', $sizes);
	$sizes = rtrim($sizes, ", ");	//this strips the last comma and white space
	(($sizes == '')? $sizes = $product['size'] : '');
	return $sizes;
}

//FEED FUNCTIONS END

?>
