<?php if ($_GET['cPath'] == ''){
?><style>.bread1{display: none;}</style>
<?php } ?>

<div id="breadcrumb" class="col-xs-12 bread1">
<?php 
    
$stringyy = $_GET['cPath'];

$desired_cID = preg_replace('/^.*_\s*/', '', $stringyy);
// $desired_cID = trim(substr($stringyy, strpos($stringyy, '_') + 1));

$get_children_categories_query = tep_db_query("SELECT parent_id FROM `categories` where categories_id = '".$desired_cID."'");
$get_children_categories = tep_db_fetch_array($get_children_categories_query);

echo'<div class="form-group upper-links row"><ul>';
    
$get_sub_categories_query = tep_db_query("SELECT c.categories_id, cd.categories_name, cd.categories_link_name from categories c, categories_description cd, products_to_categories p2c, products p where c.categories_id = cd.categories_id and c.categories_id = p2c.categories_id and c.parent_id = '".$get_children_categories['parent_id']."' and p.products_status = '1' and p.products_id = p2c.products_id GROUP BY c.categories_id HAVING count(p.products_id) > 3 ORDER BY c.sort_order ASC");    

if (tep_db_num_rows($get_sub_categories_query) > 6) {
$count = 7;
$get_sub_categories_query_raw = $get_sub_categories_query;
} else {
$count = 6;
$get_sub_categories_query = tep_db_query("SELECT c.categories_id, cd.categories_name, cd.categories_link_name from categories c, categories_description cd, products_to_categories p2c, products p where c.categories_id = cd.categories_id and c.categories_id = p2c.categories_id and c.parent_id = '".$get_children_categories['parent_id']."' and p.products_status = '1' and p.products_id = p2c.products_id GROUP BY c.categories_id ORDER BY c.sort_order ASC");    
$get_sub_categories_query_raw = $get_sub_categories_query;      
} 
        
while($get_sub_categories = tep_db_fetch_array($get_sub_categories_query_raw)){
    if($get_sub_categories['categories_id'] === $desired_cID){
        $selected_link = 'class="hilight"';    
    } else {
      $selected_link = '';  
    }
    
    if ($count > 6) {
        
        $filtered_cIDS_query = tep_db_query("select c.categories_id, count(p.products_id) as total, cd.categories_name, cd.categories_link_name, c.sort_order from products p left join manufacturers m on p.manufacturers_id = m.manufacturers_id, products_to_categories p2c, categories c, categories_description cd where p.products_status = '1' and p.products_id = p2c.products_id and c.categories_id = p2c.categories_id and c.categories_id = cd.categories_id and c.categories_id = '".$get_sub_categories['categories_id']."' GROUP BY c.categories_id HAVING count(p.products_id) > 3 ORDER BY c.sort_order ASC");
        $filtered_cIDS = tep_db_fetch_array($filtered_cIDS_query);
    
        if($filtered_cIDS['categories_link_name'] <> ''){
           $link_name =  $filtered_cIDS['cateogries_link_name'];
        } else {
            $link_name = $filtered_cIDS['categories_name'];
        }
        
    echo '<li '.$selected_link.' ><a href="' . tep_href_link(FILENAME_DEFAULT,'cPath='.$filtered_cIDS['categories_id'], 'SSL').'">' .$link_name.'</a></li>';
    } else {
        
        if($get_sub_categories['categories_link_name'] <> ''){
           $link_name =  $get_sub_categories['categories_link_name'];
        } else {
            $link_name = $get_sub_categories['categories_name'];
        }
        
    echo '<li '.$selected_link.' ><a href="' . tep_href_link(FILENAME_DEFAULT,'cPath='.$get_sub_categories['categories_id'], 'SSL').'">' .$link_name.'</a></li>';
    }
}

echo '</ul></div>';   ?>  
</div>