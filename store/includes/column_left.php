<Style>
.side-headline {
font-size:1rem;
display: block;
margin-bottom:10px;
}
    .side-headline:hover{color:#09f;}
</Style>
<span id="close-icon" style="float:right;"><i class="fa fa-times"></i></span>
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



$check_if_last_cpath_query = tep_db_query("SELECT * from categories where parent_id = '".$desired_cID."'");
            
if(($desired_cID <> '611') && ($desired_cID <> '612') && ($desired_cID <> '200') && (tep_db_num_rows($check_if_last_cpath_query) > 0) ){ 
    $get_main_heading_name_query = tep_db_query("select cd.categories_name as name from categories c, categories_description cd where c.categories_id = '".$desired_cID."' and c.categories_id = cd.categories_id and c.parent_id = '".$_GET['cPath']."'");
    $get_main_heading_name = tep_db_fetch_array($get_main_heading_name_query);
    
    
    $select_sub_categories_query = tep_db_query("SELECT * from categories c, categories_description cd where c.parent_id = '".$desired_cID."' and c.categories_id = cd.categories_id ORDER BY c.sort_order");
    echo '<h4>'.$get_main_heading_name['name'].'</h4>
    <ul style="margin-left:10px;">';
    while($select_sub_categories = tep_db_fetch_array($select_sub_categories_query)){
        if($select_sub_categories['categories_link_name'] <> ''){
          $menu_link2 = $select_sub_categories['categories_link_name'];  
        } else{
          $menu_link2 = $select_sub_categories['categories_name'];  
        }
        
        echo '<li><a class="side-headline" href="' . tep_href_link(FILENAME_DEFAULT, 'cPath='.$select_sub_categories['categories_id']).'" >'.$menu_link2.'</a></li>'; 
    }
    echo '</ul>';
} else { 


$url="http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

	$which_category = "and p2c.categories_id = '" . $current_category_id . "'";


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
if (tep_not_null($cPath)) {
    $cPath_array = tep_parse_category_path($cPath);
    $cPath = implode('_', $cPath_array);
    $current_category_id = $cPath_array[(sizeof($cPath_array)-1)];
  } else {
    $current_category_id = 0;
  }

	echo'<div class="form-group back-links">
    <h5 style="margin-bottom:5px;">Go Back To</h5>
    <ul>';

for ($i=0, $n=sizeof($cPath_array); $i<$n-1; $i++) {
    $categories2_query = tep_db_query("select categories_name, categories_id from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id = '" . (int)$cPath_array[$i] . "' and language_id = '" . (int)$languages_id . "'");
      if (tep_db_num_rows($categories_query) > 0) {
        $categories2 = tep_db_fetch_array($categories2_query);
    echo '<li style="margin-bottom:6px;"><i class="fa fa-arrow-circle-left"></i> <a href="' . tep_href_link(FILENAME_DEFAULT, 'cPath='.$categories2['categories_id']).'">'.$categories2['categories_name'].'</a></li>';
      }
}
echo '
</ul></div>';
	/*  <h4>Category</h4>';
	  
	  if (isset($_GET['category'])){
		  echo'<div class="current-select-category form-group" style="display:none;">
		  <div style="background: #3a3c41; width:75%; color:#fff; padding:7px; margin-bottom:10px;" class="col-xs-12">Currently Selected</div>';
		  foreach($_GET['category'] as $filter5 => $value5){
		  echo'<span class="col-xs-12" style="margin-bottom:10px;"><i class="fa fa-times" aria-hidden="true" style="margin-right:10px; font-size:16px; color:#D9534F;"></i>'.$value5.'</span>';
		  }
		  echo'</div>';
	  }
	  
	  $categories_count_query = tep_db_query("select c.categories_id from products p left join manufacturers m on p.manufacturers_id = m.manufacturers_id, products_to_categories p2c, categories c, categories_description cd where p.products_status = '1' and p.products_id = p2c.products_id and c.categories_id = p2c.categories_id and c.categories_id = cd.categories_id and c.parent_id = '".$get_children_categories['parent_id']."' ".$filterstring." ".$salestring." GROUP BY c.categories_id ORDER BY c.sort_order ASC");
		$categories_count = tep_db_fetch_array($categories_count_query);
	  
		echo'<div class="category-values-container">
		<ul id="category-values">';
		$i="";
		
		$sale_products_query = tep_db_query("select c.categories_id, count(p.products_id) as total, c.sort_order from products p left join manufacturers m on p.manufacturers_id = m.manufacturers_id, products_to_categories p2c, categories c, categories_description cd where p.products_status = '1' and p.products_id = p2c.products_id and c.categories_id = p2c.categories_id and c.categories_id = cd.categories_id and c.parent_id = '".$get_children_categories['parent_id']."' ".$filterstring."  GROUP BY c.categories_id ORDER BY c.sort_order ASC");
		while($sale_products = tep_db_fetch_array($sale_products_query)){
			
            $get_categories_query = tep_db_query("select cd.categories_name, c.categories_id from categories c, categories_description cd, products_to_categories p2c where cd.categories_id = p2c.categories_id and c.categories_id = p2c.categories_id and c.categories_id = '".$sale_products['categories_id']."' GROUP BY cd.categories_name order by c.sort_order");
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

if(mysql_num_rows($categories_count_query) > 10){
echo'<div style="margin-top:6px; color:#2A9DD4; cursor:pointer;">
<span id="showmore" onClick="showMore();">Show More</span><span id="showless" class="hidden" onClick="showLess();">Show Less</span></div>';
}
echo'</div>
</div></form>';
 */
  
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
		$price_query = tep_db_query ("select MAX(p.products_price) AS maxPrice, MIN(p.products_price) AS minPrice from products p, products_to_categories p2c, manufacturers m where p.products_id = p2c.products_id and p.manufacturers_id = m.manufacturers_id and p.products_status = '1' ".$salestring." ".$which_category." ".$brand_string." ".$gender_string." ".$category_string2."");
		$price = tep_db_fetch_array ($price_query);

		$i="";
		$factor = (($price['maxPrice'] - $price['minPrice']) / 8);
		if ($factor < 100) { $roundto = '-1'; 
							$rounding = 15;
		} else { $roundto = '-2';
				$rounding = 50;
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
		$num_almost = $rounding * ceil($num / $rounding);
		
		$num1 = $num_almost - 0.01;
		}
		
		$maxprice9 = $maxprice - 1;
 
		$price_count_query = tep_db_query ("select count(p.products_id) as count from products p, products_to_categories p2c, manufacturers m where p.products_id = p2c.products_id and p.manufacturers_id = m.manufacturers_id and p.products_price BETWEEN '".$min_price."' AND  '".$num1."' ".$salestring." and p.products_status = 1 ".$which_category." ".$brand_string." ".$category_string2."");
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
				
			$numberr1 = round(($price['minPrice'] + ($factor * ($factor_number - 1))),$roundto);
			$num1_almost = $rounding * ceil($numberr1 / $rounding);	
			$num1 = round($num1_almost, $roundto);	
			
				if(($i > 1) && ($i < 9)){
				
			$number2 = round(($price['minPrice'] + ($factor * $factor_number)),$roundto);
			$num2_almost = $rounding * ceil($number2 / $rounding);
			$num2 = round($num2_almost, $roundto) - 0.01;
			
				} elseif (($i > 7) && ($i < 9)){
				
					if ($price['maxPrice'] > 100) {
					$maxprice = round($price['minPrice'] + ($factor * $factor_number));
					}else {
					$maxprice = $price['maxPrice'];
					}
					$num2 = round($number2, $roundto) - 0.01;
				}
			 
			 $price_count_query = tep_db_query ("select count(p.products_id) as count from products p, products_to_categories p2c, manufacturers m where p.products_id = p2c.products_id and p.products_status = '1' and p.manufacturers_id = m.manufacturers_id ".$salestring." and p.products_price BETWEEN '".$num1."' AND  '".$num2."' ".$which_category." ".$brand_string." ".$gender_string." ".$category_string2."");
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
	</div>';

    include_once 'size-filter-helper.php';

    //if (isset($_GET['develop'])) {
        load_size_options($current_category_id); 
    //}

$gender_query2 = tep_db_query ("select distinct(p.gender) from products p, products_to_categories p2c where p.products_id = p2c.products_id and p.products_status = 1 and p2c.categories_id = '" . $current_category_id . "' and p.gender <> ''");

$gender_query = tep_db_query ("select distinct(p.gender) from products p, products_to_categories p2c where p.products_id = p2c.products_id and p.products_status = 1 and p2c.categories_id = '" . $current_category_id . "' and p.gender <> ''");

$gender_query_check = tep_db_fetch_array($gender_query2);

if ((tep_db_num_rows($gender_query) !== 0)){
	
echo '<div class="form-group">'.
'<h4>Gender</h4>
<ul id="gender-values">';
    $male_count = 0;
    $female_count = 0;
    $unisex_count = 0;
while($gender = tep_db_fetch_array($gender_query)){
	
	$gender_count_query = tep_db_query ("select count(p.gender) as count from products p, products_to_categories p2c, manufacturers m where p.products_id = p2c.products_id and p.manufacturers_id = m.manufacturers_id and p.products_status =1 and p2c.categories_id = '" . $current_category_id . "' and p.gender = '".$gender['gender']."' ".$brand_string." ".$price_string."");
	$gender_count = tep_db_fetch_array($gender_count_query);
	
	if (isset($_GET['gender']) && ($_GET['gender'] !== '')) {
		$checked_attr_gender = '';
            foreach($_GET['gender'] as $gfilterid => $gender_att) {
              if($gender['gender'] == $gender_att){
                $checked_attr_gender = 'checked'; 
              }
            }  
	}
	
		$male = "Men's";
		$female = "Women's";
		$kids = "Kids";
    
        if($gender['gender'] == 'unisex'){
            $unisex_count = $gender_count['count']; 
        }
    
        if($gender['gender'] == 'male'){
            $male_count = $gender_count['count'] + $unisex_count;
        }
    
        if($gender['gender'] == 'female'){
            $female_count = $gender_count['count'] + $unisex_count;
        }
    
		
		if ($gender['gender'] == 'male'){
		echo '<li class="facet" value="male">'.
	'<label role="checkbox" for="gender1">'.
		 tep_draw_checkbox_field ('gender[1]', 'male' , $checked_attr_gender, 'class="filter_id" id="gender1" ').
		 '&nbsp;<span>'.$male.'</span><span class="filter_count"> (' .$male_count . ')</span></label></li>';
		}
		 
	if ($gender['gender'] == 'female'){	 
		echo '<li class="facet" value="female">'.
	'<label role="checkbox" for="gender2">'.
		 tep_draw_checkbox_field ('gender[2]', 'female' , $checked_attr_gender, 'class="filter_id" id="gender2" ').
		 '&nbsp;<span>'.$female.'</span><span class="filter_count"> (' .$female_count . ')</span></label></li>';
		
	}
    if ($gender['gender'] == 'kids'){	 
		echo '<li class="facet" value="kids">'.
	'<label role="checkbox" for="gender3">'.
		 tep_draw_checkbox_field ('gender[3]', 'kids' , $checked_attr_gender, 'class="filter_id" id="gender3" ').
		 '&nbsp;<span>'.$kids.'</span><span class="filter_count"> (' .$gender_count['count'] . ')</span></label></li>';
		
	}
}

echo '</ul></div></form>';
}  else {
	echo '</form>'; }
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
$('#close-icon').click(function(){
    $('#column_left').hide();
})
</script>


<style>
    .back-links li:hover, .back-links a:hover{color:#09f;}
.facet input{margin-right:5px;}
.hidden{display:none;}
#showmore:hover, #showless:hover{text-decoration:underline;}
</style>
<?php  } ?>
