<?php
/*
  $Id: column_left.php,v 1.13 2002/06/16 22:08:05 lango Exp $

  Digistore v4.0,  Open Source E-Commerce Solutions
  http://www.digistore.co.nz

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License

  Do not any add includes boxs here add in admin-infobox admin
*/

// Start recently viewed



$url="http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
if(preg_match("/sale/",$url,$matches)){
  	$salestring = "and p.products_msrp > p.products_price";
	$which_category = '';
} else {
	$which_category = "and p2c.categories_id = '" . $current_category_id . "'";
}


if (isset($_GET['cat'])){

	if($_GET['cat'] == 'kite'){
	$whatineed = '611';
	}
	elseif($_GET['cat'] == 'paddle'){
	$whatineed = '612';
	}
	elseif($_GET['cat'] == 'wake'){
	$whatineed = '200';
	}
	$which_category = "and p2c.master_category = '".$whatineed."'";
} 

$brand_string ='';

  if(isset($_GET['brand']) && tep_not_null($_GET['brand'])){
	$brands = $_GET['brand'];
	$str = '';
		foreach($brands as $filter => $value){
	    $str .= $value."','";
		}
		$result = rtrim($str,"','");
	
		if (count($brands) > 1){
		$brand = "m.manufacturers_name IN ('".$result."')"; 
		} else {
		$brand = "m.manufacturers_name = '".$result."' "; 
		}

		$brand_string .= ' and ' .$brand;

   }
   

$gender_string ='';
if(isset($_GET['gender']) && tep_not_null($_GET['gender'])){
	$str = '';
	foreach($_GET['gender'] as $filter => $value){
		$str .= $value."','"; 
	
		$genders= rtrim($str,"','");
	
	}
	if(count($_GET['gender']) > 1 ){
	$gender = "p.gender IN ('".$genders."')"; 
	}
	else {$gender = "p.gender = '".$value."'";
	}
	
	$gender_string .= ' and ' .$gender;
	
}

$price_string ='';
$result2 = '';
$price_deux = '';
$i = 0;
	if (isset($HTTP_GET_VARS['range']) && tep_not_null($HTTP_GET_VARS['range'])) {
		$get_range2 = $_GET['range'];
		
		  foreach($get_range2 as $filter => $value){
			
			$result2 = str_replace(array('-', ' '),"'AND'", $value);
				$str= str_replace(array('-', ' '), ',', $value);
				$result23 .= $str.',';	  	  
			$i++;
				  
			 $arr1 = preg_split("/''/", $value);
			 if ($i > 1){
			 $price_deux .= " OR p.products_price BETWEEN '" .$result2."'";
			  
			 }
		 
		  }
		    
			 $result_almost = rtrim($result23,"','");
		     $result_maybe =  explode(',', $result_almost);
			  
				  if(count($get_range2) > 1){
					  
			  $price = "(p.products_price BETWEEN '" .$result_maybe[0]. "' AND '".$result_maybe[1]."'".$price_deux.")";
				  } else {
			  $price = "p.products_price BETWEEN '".$result2."'"; 
				  }
			  
			  
			  $price_string .= ' and '.$price;
		} 
		
//////////////////////////////////////////////////////////////////
		
$category_string2 ='';

  if(isset($_GET['category']) && tep_not_null($_GET['category'])){
	$categoryy = $_GET['category'];
	$str5 = '';
		foreach($categoryy as $filter5 => $value5){
	    $str5 .= $filter5."','";
		}
		$result5 = rtrim($str5,"','");
	
		if (count($categoryy) > 1){
		$category = "p2c.categories_id IN ('".$result5."')"; 
		} else {
		$category = "p2c.categories_id = '".$result5."' "; 
		}

		$category_string2 .= ' and ' .$category;
   } 
		
if(preg_match("/sale/",$url,$matches)){		
echo '<form id="category-form">';
if (isset($_GET['cat'])){
echo '<input type="hidden" name="cat" value="'.$_GET['cat'].'">';
}

if(isset($_GET['filter_id'])){
echo '<input type="hidden" name="filter_id" value="'.$_GET['filter_id'].'">';
}

if(isset($_GET['brand']) && tep_not_null($_GET['brand'])){
	$brands = $_GET['brand'];
		foreach($brands as $filter => $value){
	    echo '<input type="hidden" name="brand['.$filter.']" value="'.$value.'">';
		}

}

	echo'<div class="form-group">
	  <h4>Category</h4>';
	  
	  if (isset($_GET['category'])){
		  echo'<div class="current-select-category form-group" style="display:none;">
		  <div style="background: #3a3c41; width:75%; color:#fff; padding:7px; margin-bottom:10px;" class="col-xs-12">Currently Selected</div>';
		  foreach($_GET['category'] as $filter5 => $value5){
		  echo'<span class="col-xs-12" style="margin-bottom:10px;"><i class="fa fa-times" aria-hidden="true" style="margin-right:10px; font-size:16px; color:#D9534F;"></i>'.$value5.'</span>';
		  }
		  echo'</div>';
	  }
	  
	  $categories_count_query = tep_db_query("select c.categories_id from products p left join manufacturers m on p.manufacturers_id = m.manufacturers_id, products_to_categories p2c, categories c, categories_description cd where p.products_status = '1' and p.products_msrp > p.products_price and p.products_id = p2c.products_id and c.categories_id = p2c.categories_id and c.categories_id = cd.categories_id ".$filterstring." ".$salestring." GROUP BY c.categories_id ORDER BY c.sort_order ASC");
		$categories_count = tep_db_fetch_array($categories_count_query);
	  
		echo'<div class="category-values-container">
		<ul id="category-values">';
		$i="";
		
		$sale_products_query = tep_db_query("select c.categories_id, count(p.products_id) as total from products p left join manufacturers m on p.manufacturers_id = m.manufacturers_id, products_to_categories p2c, categories c, categories_description cd where p.products_status = '1' and p.products_msrp > p.products_price and p.products_id = p2c.products_id and c.categories_id = p2c.categories_id and c.categories_id = cd.categories_id ".$filterstring."  GROUP BY c.categories_id ORDER BY c.sort_order ASC");
		while($sale_products = tep_db_fetch_array($sale_products_query)){
			
		$get_categories_query = tep_db_query("select cd.categories_name, c.categories_id from categories c, categories_description cd, products_to_categories p2c where cd.categories_id = p2c.categories_id and c.categories_id = p2c.categories_id ".$which_category." and c.categories_id = '".$sale_products['categories_id']."' GROUP BY cd.categories_name order by c.sort_order");
		$get_categories = tep_db_fetch_array($get_categories_query);
		
		if(isset($_GET['category'])){
		$checked_category = '';
            foreach($_GET['category'] as $filterid5 => $category_name) {
              if($get_categories['categories_id'] == $filterid5){
                $checked_category = 'checked'; 
              }
            }  
		}
		
		$i++;
		$category_stuff = '';	
		$category_stuff .= '<li class="facet" id="category-facet'.$i.'">'.
	'<label role="checkbox" for="category'.$i.'">'.
		 tep_draw_checkbox_field ('category['.$get_categories['categories_id'].']', $get_categories['categories_name'], $checked_category, 'class="filter_id" id="category'.$i.'"').
		 '&nbsp;<span>'.$get_categories['categories_name'].'</span><span class="filter_count"> (' . $sale_products['total'] . ')</span></label></li>';
	 
		echo $category_stuff;	
		
			
		}
		
		

echo '</ul>';

if(tep_db_num_rows($categories_count_query) > 10){
echo'<div style="margin-top:6px; color:#2A9DD4; cursor:pointer;">
<span id="showmore" onClick="showMore();">Show More</span><span id="showless" class="hidden" onClick="showLess();">Show Less</span></div>';
}
echo'</div>
</div></form>';
}
  
echo '<form id="brand-form">';
if (isset($_GET['cat'])){
echo '<input type="hidden" name="cat" value="'.$_GET['cat'].'">';
}

if(isset($_GET['filter_id'])){
echo '<input type="hidden" name="filter_id" value="'.$_GET['filter_id'].'">';
}

if(isset($_GET['category']) && tep_not_null($_GET['category'])){
	$categories = $_GET['category'];
		foreach($categories as $filter3 => $value3){
	    echo '<input type="hidden" name="category['.$filter3.']" value="'.$value3.'">';
		}

}

echo '<div class="form-group">';
echo'<h4>Brand</h4>';
echo '<ul id="brand-values">';

$str = $_SERVER['REQUEST_URI'];
$str2 = substr($str, 0, strrpos($str, '?'));

$manufacturers_count2_query = tep_db_query ("select count(m.manufacturers_name) as total from manufacturers m, products p, products_to_categories p2c where p.manufacturers_id = m.manufacturers_id and p.products_id = p2c.products_id and p.products_status = 1 ".$which_category." ".$salestring." ".$category_string2." ".$price_string." GROUP BY m.manufacturers_name order by m.manufacturers_name ASC");


$manufacturers_query = tep_db_query ("select distinct(m.manufacturers_id), m.manufacturers_name from manufacturers m, products p, products_to_categories p2c where p.manufacturers_id = m.manufacturers_id and p.products_id = p2c.products_id and p.products_status = 1 ".$which_category." ".$salestring." ".$category_string2." ".$price_string." order by m.manufacturers_name ASC");
$i="";
while ($manufacturers = tep_db_fetch_array($manufacturers_query)){
	$manufacturers_count_query = tep_db_query ("select count(m.manufacturers_id) as count from manufacturers m, products p, products_to_categories p2c where p.manufacturers_id = m.manufacturers_id and m.manufacturers_id = '".$manufacturers['manufacturers_id']."' and p.products_id = p2c.products_id and p.products_status = 1 ".$which_category." ".$salestring." ".$gender_string." ".$price_string." ".$category_string2."");
	$manufacturers_count = tep_db_fetch_array($manufacturers_count_query);
	
$filter_id = $_GET['brand'];

		$brand_stuff = "";
		if(isset($_GET['brand'])){
		$checked_attr_brand = '';
            foreach($_GET['brand'] as $filterid2 => $man_name) {
              if($manufacturers['manufacturers_id']==$filterid2){
                $checked_attr_brand = 'checked'; 
              }
            }  
		}
	$i++;
	 
		 $brand_stuff .= '<li id="brand-facet'.$i.'" class="facet">'.
	'<label role="checkbox" for="brand'.$i.'">'.
		 tep_draw_checkbox_field ('brand['.$manufacturers['manufacturers_id'].']', $manufacturers['manufacturers_name'], $checked_attr_brand, 'class="filter_id" id="brand'.$i.'"').
		 '&nbsp;<span>'.$manufacturers['manufacturers_name'].'</span><span class="filter_count"> (' . $manufacturers_count['count'] . ')</span></label></li>';
	 
		 

		
echo $brand_stuff;	
} 
echo '</ul>';
if(tep_db_num_rows($manufacturers_count2_query) > 10){
echo'<div style="margin-top:6px; color:#2A9DD4; cursor:pointer;">
<span id="showmoreB" onClick="showMoreBrand();">Show More</span><span id="showlessB" class="hidden" onClick="showLessBrand();">Show Less</span></div>';
}
echo'</div>';


echo'<div class="form-group">'.
		'<h4>Price</h4>'.
		'<ul id="price-values">';
		$price_query = tep_db_query ("select MAX(p.products_price) AS maxPrice, MIN(p.products_price) AS minPrice from products p, products_to_categories p2c, manufacturers m where p.products_id = p2c.products_id and p.manufacturers_id = m.manufacturers_id and p.products_status = '1' and p.products_msrp > p.products_price ".$which_category." ".$brand_string." ".$gender_string." ".$category_string2."");
		$price = tep_db_fetch_array ($price_query);

		$i="";
		$factor = (($price['maxPrice'] - $price['minPrice']) / 8);
		if ($factor < 100) { $roundto = '-1'; 
		} else { $roundto = '-2';
		}
		
		if($price['minPrice'] < 100){ 
		$min_price = '0.00';
		} else {
		$min_price = floor($price['minPrice']);
		}
		
		if(($price['minPrice'] == $price['maxPrice']) && ($min_price = '0.00')){
			$num1 = number_format($price['maxPrice'], 2, '.', '');
			}else{
		$num = round(($price['minPrice'] + $factor), $roundto);
		$num_almost = 50 * ceil($num / 50);
		
		$num1 = $num_almost - 0.01;
		}
		
		$maxprice9 = $maxprice - 1;
 
		$price_count_query = tep_db_query ("select count(p.products_id) as count from products p, products_to_categories p2c, manufacturers m where p.products_id = p2c.products_id and p.manufacturers_id = m.manufacturers_id and p.products_price BETWEEN '".$min_price."' AND  '".$num1."' and p.products_msrp > p.products_price and p.products_status = 1 ".$which_category." ".$brand_string." ".$category_string2."");
		$price_count = tep_db_fetch_array ($price_count_query); 
			
		 $range_input =''; 
		 for($i=1; $i<9; $i++)  {
			 
			if($i== 1){
			$range_input .= '<li class="facet" value="'.$manufacturers['manufacturers_name'].'">'.
			'<label role="checkbox" for="price1">
			 <input type="checkbox" name="range[1]" '.$checked_price.' value="'.$min_price.'-'.$num1.'" id="price1">&nbsp;$'.$min_price.'&nbsp;-&nbsp;$'.$num1.'<span class="filter_count"> (' . $price_count['count'] . ')</span></label></li>';
			}
			$factor_number = $i;
			
			if(($i > 1) && ($i < 9)){
				
			$num1_almost = round(($price['minPrice'] + ($factor * ($factor_number - 1))),$roundto);
			$num1 = 50 * ceil($num1_almost / 50);	
			
				if(($i > 1) && ($i < 9)){
				
			$number2 = round(($price['minPrice'] + ($factor * $factor_number)),$roundto);
			$num2_almost = 50 * ceil($number2 / 50);
			$num2 = round($num2_almost, $roundto) - 0.01;
			
				} elseif (($i > 7) && ($i < 9)){
				
					if ($price['maxPrice'] > 100) {
					$maxprice = round($price['minPrice'] + ($factor * $factor_number));
					}else {
					$maxprice = $price['maxPrice'];
					}
					$num2 = round($number2, $roundto) - 0.01;
				}
			 
			 $price_count_query = tep_db_query ("select count(p.products_id) as count from products p, products_to_categories p2c, manufacturers m where p.products_id = p2c.products_id and p.products_status = '1' and p.manufacturers_id = m.manufacturers_id and p.products_msrp > p.products_price and p.products_price BETWEEN '".$num1."' AND  '".$num2."' ".$which_category." ".$brand_string." ".$gender_string." ".$category_string2."");
			 $price_count = tep_db_fetch_array ($price_count_query);
	
				if($price_count['count'] > 0){
				$range_input .= '<li class="facet">'.
				'<label role="checkbox" for="price'.$i.'">'. tep_draw_checkbox_field ('range['.$i.']', $num1.'-'.$num2, '', 'class="filter_id" id="price'.$i.'"'. $disabled).'
				$'.$num1.'&nbsp;-&nbsp;$'.$num2.'<span class="filter_count"> (' . $price_count['count'] . ')</span></label></li>';
				}
			} 
	 	}

		echo $range_input;

		

		if(isset($_GET['range'])){
		$get_range = $_GET['range'];
			
		  foreach($get_range as $filter => $value){
			  ?>
              <script>
			  var value = $("#price<?php echo $filter; ?>").val();
			  var prices = "<?php echo $value; ?>";
			  
              if(value == prices){
			
              $('#price<?php echo $filter; ?>').attr('checked', true);
              }
			  </script>
			<?php  
            } 
		}

echo '</ul>
	</div></form>';
?>

<script>
if (navigator.userAgent.search("Firefox") >= 0) {
var labelID;
$('label').click(function() {
       labelID = $(this).attr('for');
       $('#'+labelID).trigger('click');
});
}

var elems = $('#category-values .facet').length
for (var i=0, n=elems; i<n; ++i) {  
	if(i > 10){
		$('#category-facet10').nextAll().addClass("hidden");
	}	
}

var elems = $('#brand-values .facet').length
for (var i=0, n=elems; i<n; ++i) {  
	if(i > 10){
		$('#brand-facet10').nextAll().addClass("hidden");
	}	
}

function showMore(){
	$('#category-facet10').nextAll().removeClass("hidden");
	$('#showmore').addClass("hidden");
	$('#showless').removeClass("hidden");
}
function showLess(){
	$('#category-facet10').nextAll().addClass("hidden");
	$('#showmore').removeClass("hidden");
	$('#showless').addClass("hidden");
}

function showMoreBrand(){
	$('#brand-facet10').nextAll().removeClass("hidden");
	$('#showmoreB').addClass("hidden");
	$('#showlessB').removeClass("hidden");
}
function showLessBrand(){
	$('#brand-facet10').nextAll().addClass("hidden");
	$('#showmoreB').removeClass("hidden");
	$('#showlessB').addClass("hidden");
}

$('#dropdown').change(function() {
	 $('#filter-form').submit();
	 });

$('#brand-values li').click(function () {
    var $frm = $('#brand-form');
    //set the value of the hidden element
    
    $frm.submit();
});

$('#price-values li').click(function () {
    var $frm = $('#brand-form');
    //set the value of the hidden element
    
    $frm.submit();
});

$('#category-values li').click(function () {
    var $frm = $('#category-form');
    //set the value of the hidden element
    
    $frm.submit();
});

$('#gender-values li').click(function () {
    var $frm = $('#brand-form');
    //set the value of the hidden element
    
    $frm.submit();
});

$('#showfilter').on('click', function(e){

    $("#column_left").toggle();
    
});
</script>


<style>
.facet input{margin-right:5px;}
.hidden{display:none;}
#showmore:hover, #showless:hover{text-decoration:underline;}
</style>
