<Style>
	.btn {
  display: inline-block;
  margin-bottom: 0;
  font-weight: normal;
  text-align: center;
  vertical-align: middle;
  touch-action: manipulation;
  cursor: pointer;
  background-image: none;
  border: 1px solid transparent;
  white-space: nowrap;
  padding: 8px 13px;
  font-size: 12px;
  line-height: 1.42857143;
  border-radius: 3px;
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
}
.btn-primary {
  color: #ffffff !important;
  background-color: #1e91cf;
  border-color: #1978ab;
}

.btn-primary:hover {
  color: #fff;
  background-color: #025aa5;
  border-color: #01549b;
}
</Style>

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

// START STS 4.1
if ($sts->display_template_output) {
include DIR_WS_MODULES.'sts_inc/sts_column_left.php';
} else {
//END STS 4.1
  $column_query = tep_db_query('select configuration_column as cfgcol, configuration_title as cfgtitle, configuration_value as cfgvalue, configuration_key as cfgkey, box_heading from ' . TABLE_THEME_CONFIGURATION . ' order by location');
while ($column = tep_db_fetch_array($column_query)) {

$column['cfgtitle'] = str_replace(' ', '_', $column['cfgtitle']);
$column['cfgtitle'] = str_replace("'", '', $column['cfgtitle']);

if ( ($column['cfgvalue'] == 'yes') && ($column['cfgcol'] == 'left')) {

define($column['cfgkey'],$column['box_heading']);


}
}
// START STS 4.1
}
// END STS 4.1

$url="http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

	$get_round1_category_query = tep_db_query("SELECT c.parent_id FROM products_to_categories p2c, categories c where c.categories_id = '".$current_category_id."' and p2c.categories_id = c.categories_id");
	$get_round1_category =  tep_db_fetch_array($get_round1_category_query);

	$get_round2_category_query = tep_db_query("SELECT c.parent_id FROM categories c where c.categories_id = '". $get_round1_category['parent_id']."'");
	$get_round2_category =  tep_db_fetch_array($get_round2_category_query);

	if(tep_db_num_rows($get_round2_category_query) > 0 && ($get_round2_category['parent_id'] > 0)){


	$get_round3_category_query = tep_db_query("SELECT c.parent_id FROM categories c where c.categories_id = '". $get_round2_category['parent_id']."'");
	$get_round3_category =  tep_db_fetch_array($get_round3_category_query);

	  if(tep_db_num_rows($get_round3_category_query) > 0 && ($get_round3_category['parent_id'] > 0)){

		$master_categories_id = $get_round3_category['parent_id'];  
	  } else {
		$master_categories_id = $get_round2_category['parent_id']; 
	  } 
		  
	} else {
	  $master_categories_id = $get_round1_category['parent_id']; 
	}

	if(isset($_GET['catt'])){
		$filterstring = "and p2c.master_category = '".$_GET['catt']."'";
		$master_categories_id = $_GET['catt'];
	}else {
		$filterstring = "and p2c.master_category = '".$master_categories_id."'";
	}

if (!isset($_GET['category'])){
	$which_category = "and p2c.master_category = '".$_GET['catt']."'";
} else {
	$which_category = "";
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
	if (isset($_GET['maxprice']) && tep_not_null($_GET['maxprice'])) {
			  $price_string = " and p.products_price <= '".$_GET['maxprice']."'";
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
			
echo '<form id="category-form">';
if (isset($_GET['catt'])){
echo '<input type="hidden" name="catt" value="'.$_GET['catt'].'">';
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
if(isset($_GET['maxprice']) && ($_GET['maxprice'] !=='')){
	echo '<input type="hidden" name="maxprice" value="'.$_GET['maxprice'].'">';
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
	  
	

	  $categories_count_query = tep_db_query("select c.categories_id from products p left join manufacturers m on p.manufacturers_id = m.manufacturers_id, products_to_categories p2c, categories c, categories_description cd where p.products_status = '1' and p.products_id = p2c.products_id and c.categories_id = p2c.categories_id and c.categories_id = cd.categories_id ".$filterstring." ".$salestring." GROUP BY c.categories_id ORDER BY c.sort_order ASC");
		$categories_count = tep_db_fetch_array($categories_count_query);
	  
		echo'<div class="category-values-container">
		<ul id="category-values">';
		$i="";

	$get_main_categories_query = tep_db_query("SELECT c.categories_id, cd.categories_name, c.sort_order FROM categories c, categories_description cd where c.parent_id = '".$master_categories_id."' and c.categories_id = cd.categories_id ORDER BY c.sort_order ASC");
	
$numbers = '';
	while($get_main_categories = tep_db_fetch_array($get_main_categories_query)){

		$all_products_query = tep_db_query("select c.categories_id, count(p.products_id) as total, cd.categories_name from products p left join manufacturers m on p.manufacturers_id = m.manufacturers_id, products_to_categories p2c, categories c, categories_description cd where p.products_status = '1' and p.products_id = p2c.products_id and c.parent_id = '".$get_main_categories['categories_id']."' and c.categories_id = p2c.categories_id and c.categories_id = cd.categories_id and p2c.master_category = '".$master_categories_id."' GROUP BY c.categories_id ORDER BY c.sort_order ASC ");
		
		if(tep_db_num_rows($all_products_query) > 0) {
			while($all_products = tep_db_fetch_array($all_products_query)){
			
			$get_categories_query = tep_db_query("select cd.categories_name, c.categories_id from categories c, categories_description cd, products_to_categories p2c where cd.categories_id = p2c.categories_id and c.categories_id = p2c.categories_id ".$filterstring." and c.categories_id = '".$all_products['categories_id']."' GROUP BY cd.categories_name order by c.sort_order");
			$get_categories = tep_db_fetch_array($get_categories_query);
		
			if(isset($_GET['category'])){
			$checked_category = '';
            	foreach($_GET['category'] as $filterid5 => $category_name) {
              		if($get_categories['categories_id'] == $filterid5){
                	$checked_category = 'checked'; 
              		}
            	}  
			} 
			elseif ($current_category_id == $get_categories['categories_id']){
				$checked_category = 'checked'; 
			} else {
				$checked_category = '';
			}
		
		$i++;
		$category_stuff = '';	
		$category_stuff .= '<li class="facet" id="category-facet'.$i.'">'.
		'<label role="checkbox" for="category'.$i.'">'.
		 tep_draw_checkbox_field ('category['.$get_categories['categories_id'].']', $get_categories['categories_name'], $checked_category, 'class="filter_id" id="category'.$i.'"').
		 '&nbsp;<span>'.$get_categories['categories_name'].'</span><span class="filter_count"> (' . $all_products['total'] . ')</span></label></li>';
	 
			$numbers .=	$get_categories['categories_name'].'<br>';
		echo $category_stuff;	
		
			}
			
		} else {
			$all_products2_query = tep_db_query("select c.categories_id, count(p.products_id) as total, cd.categories_name from products p left join manufacturers m on p.manufacturers_id = m.manufacturers_id, products_to_categories p2c, categories c, categories_description cd where p.products_status = '1' and p.products_id = p2c.products_id and c.categories_id = '".$get_main_categories['categories_id']."' and c.categories_id = p2c.categories_id and c.categories_id = cd.categories_id and p2c.master_category = '".$master_categories_id."' GROUP BY c.categories_id ORDER BY c.sort_order ASC");
			$all_products2 = tep_db_fetch_array($all_products2_query);
			
			if(isset($_GET['category'])){
			$checked_category = '';
            	foreach($_GET['category'] as $filterid5 => $category_name) {
              		if($get_main_categories['categories_id'] == $filterid5){
                	$checked_category = 'checked'; 
              		}
            	}  
			} 
			
			 else {
				$checked_category = '';
			}
			
			echo '<li class="facet" id="category-facet'.$i.'">'.
		'<label role="checkbox" for="category'.$i.'">'.
		 tep_draw_checkbox_field ('category['.$get_main_categories['categories_id'].']', $get_main_categories['categories_name'], $checked_category, 'class="filter_id" id="category'.$i.'"').
		 '&nbsp;<span>'.$get_main_categories['categories_name'].'</span><span class="filter_count"> (' . $all_products2['total'] . ')</span></label></li>';
	 
			
		}$i++;
		
	}
		
		

echo '</ul>';

if($i > 10){
echo'<div style="margin-top:6px; color:#2A9DD4; cursor:pointer;">
<span id="showmore" onClick="showMore();">Show More</span><span id="showless" class="hidden" onClick="showLess();">Show Less</span></div>';
}
echo'</div>
</div></form>';

  
echo '<form id="brand-form">';
if (isset($_GET['catt'])){
echo '<input type="hidden" name="catt" value="'.$_GET['catt'].'">';
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


$manufacturers_query = tep_db_query ("select distinct(m.manufacturers_id), m.manufacturers_name from manufacturers m, products p, products_to_categories p2c where p.manufacturers_id = m.manufacturers_id and p.products_id = p2c.products_id and p.products_status = 1 ".$which_category." ".$salestring." ".$category_string2." ".$price_string." ".$gender_string." order by m.manufacturers_name ASC ");
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
		'<h4>Price Range</h4>
		<div class="col-xs-12 form-horizontal">
			<div class="row">
			<span style="position: absolute; left:10px; top: 8px;">$</span>
			<input name="maxprice" class="form-control" placeholder="Max Price" style="max-width:110px; padding:0px 25px; display:inline-block; height:35px;" value="'.$_GET['maxprice'].'">
			
			<button class="btn btn-primary" style="margin-left:10px;">Go</button>
			</div>
		</div>'.
	'</div>';

$gender_query2 = tep_db_query ("select distinct(p.gender) from products p, products_to_categories p2c where p.products_id = p2c.products_id and p.products_status = 1 ".$category_string2." ".$which_category." and p.gender <> '' ");

$gender_query = tep_db_query ("select distinct(p.gender) from products p, products_to_categories p2c where p.products_id = p2c.products_id and p.products_status = 1 ".$category_string2." ".$which_category." and p.gender <> '' ORDER BY p.gender DESC");

$gender_query_check = tep_db_fetch_array($gender_query2);

if ((tep_db_num_rows($gender_query2) !== 0)){
	
echo '<div class="form-group">'.
'<h4>Gender</h4>
<ul id="gender-values">';
    $male_count = 0;
    $female_count = 0;
    $unisex_count = 0;
    $kids_count = 0;
    
    $male_checked_attr_gender = '';
    $female_checked_attr_gender = '';
while($gender = tep_db_fetch_array($gender_query)){
	
	$gender_count_query = tep_db_query ("select count(p.gender) as count from products p, products_to_categories p2c, manufacturers m where p.products_id = p2c.products_id and p.manufacturers_id = m.manufacturers_id and p.products_status =1 ".$category_string2." and p.gender = '".$gender['gender']."' ".$brand_string." ".$price_string." ".$which_category."");

	$gender_count = tep_db_fetch_array($gender_count_query);
	
	if (isset($_GET['gender']) && ($_GET['gender'] !== '')) {
        foreach($_GET['gender'] as $gfilterid => $gender_att) {
            if($gender['gender'] == $gender_att && $gender_att == 'male'){
                $male_checked_attr_gender = 'checked'; 
            } 
            if($gender_att == 'female' && $gender['gender'] == 'unisex'){
                $female_checked_attr_gender = 'checked';
            } elseif($gender['gender'] == $gender_att && $gender_att == 'female'){
                $female_checked_attr_gender = 'checked';
            }
            if($gender['gender'] == $gender_att && $gender_att == 'kids'){
                $kids_checked_att_gender = 'checked';
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
        
        if($gender['gender'] == 'kids'){
            $kids_count = $gender_count['count']; 
        }
}
    
		
		if ($male_count > 0){
		echo '<li class="facet" value="male">'.
	'<label role="checkbox" for="gender1">'.
		 tep_draw_checkbox_field ('gender[1]', 'male' , $male_checked_attr_gender, 'class="filter_id" id="gender1" ').
		 '&nbsp;<span>'.$male.'</span><span class="filter_count"> (' .$male_count . ')</span></label></li>';
		}
	if($female_count == 0 && $unisex_count > 0){ 
        echo '<li class="facet" value="female">'.
	'<label role="checkbox" for="gender2">'.
		 tep_draw_checkbox_field ('gender[2]', 'female' , $female_checked_attr_gender, 'class="filter_id" id="gender2" ').
		 '&nbsp;<span>'.$female.'</span><span class="filter_count"> (' .$unisex_count . ')</span></label></li>';
    } elseif ($female_count > 0){	 
		echo '<li class="facet" value="female">'.
	'<label role="checkbox" for="gender2">'.
		 tep_draw_checkbox_field ('gender[2]', 'female' , $female_checked_attr_gender, 'class="filter_id" id="gender2" ').
		 '&nbsp;<span>'.$female.'</span><span class="filter_count"> (' .$female_count . ')</span></label></li>';	
	}
    
    if ($kids_count > 0){	 
		echo '<li class="facet" value="kids">'.
	'<label role="checkbox" for="gender3">'.
		 tep_draw_checkbox_field ('gender[3]', 'kids' , $kids_checked_att_gender, 'class="filter_id" id="gender3" ').
		 '&nbsp;<span>'.$kids.'</span><span class="filter_count"> (' .$kids_count . ')</span></label></li>';
		
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
		$('#category-facet11').nextAll().addClass("hidden");
	}	
}

var elems = $('#brand-values .facet').length
for (var i=0, n=elems; i<n; ++i) {  
	if(i > 10){
		$('#brand-facet10').nextAll().addClass("hidden");
	}	
}

function showMore(){
	$('#category-facet11').nextAll().removeClass("hidden");
	$('#showmore').addClass("hidden");
	$('#showless').removeClass("hidden");
}
function showLess(){
	$('#category-facet11').nextAll().addClass("hidden");
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

</script>


<style>
.facet input{margin-right:5px;}
.hidden{display:none;}
#showmore:hover, #showless:hover{text-decoration:underline;}
</style>
