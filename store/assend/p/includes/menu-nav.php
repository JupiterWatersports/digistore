<style>
    .topheading > a {text-transform: uppercase;}
    div.column{text-align:left;vertical-align:top;display:inline-block;/*width:146px;float:left*/}
.menu .column ul{display:none;position:relative;/*top:100%;*/width:100%;z-index:99999999;margin-right:0}
.column:last-child{border-right:medium none}
.column .hdline{font-weight:600;font-size:17px;text-transform:uppercase;color:#606060;text-align:left; padding: 15px 0px; white-space: nowrap;}
.dropdown_1columns{min-width:140px;}
.column-1, .column-2, .column-3, .column-4, .column-5, .column-6, .column-7, .column-8, .column-9, .column-10, .column-11, .column-12, .col, .column-auto, .column-sm-1, .column-sm-2, .column-sm-3, .column-sm-4, .column-sm-5, .column-sm-6, .column-sm-7, .column-sm-8, .column-sm-9, .column-sm-10, .column-sm-11, .column-sm-12, .column-sm, .column-sm-auto, .column-md-1, .column-md-2, .column-md-3, .column-md-4, .column-md-5, .column-md-6, .column-md-7, .column-md-8, .column-md-9, .column-md-10, .column-md-11, .column-md-12, .column-md, .column-md-auto, .column-lg-1, .column-lg-2, .column-lg-3, .column-lg-4, .column-lg-5, .column-lg-6, .column-lg-7, .column-lg-8, .column-lg-9, .column-lg-10, .column-lg-11, .column-lg-12, .column-lg, .column-lg-auto, .column-xl-1, .column-xl-2, .column-xl-3, .column-xl-4, .column-xl-5, .column-xl-6, .column-xl-7, .column-xl-8, .column-xl-9, .column-xl-10, .column-xl-11, .column-xl-12, .column-xl, .column-xl-auto, .column-2_4, .column-sm-2_4, .column-md-2_4, .column-lg-2_4 {
    position: relative;
    width: 100%;
    min-height: 1px;
    padding-right: 15px;
    padding-left: 15px;
}

.menu .dropdown_1columns>ul li a {
    padding: 10px 15px;
    color: #015B86;
    font-size: 14px;
}

.menu .dropdown_1columns>ul li a:hover { color:#09F;
}


.col {
    -ms-flex-preferred-size: 0;
    flex-basis: 0;
    -ms-flex-positive: 1;
    flex-grow: 1;
    max-width: 100%;
}
    @media (min-width:920px){
        .menu ul li:hover> .m-dropdown{display:flex !important; flex-wrap: wrap;}
        .mobile-lessons{display:none !important;}
        .dropdown_2columns .has-submenu ul{padding-top: 10px;
    padding-left: 40px;}
        
    }
    
    @media (max-width:919px){
        .menu .col{padding:0px;}
        div.column{display:table;}
        .dropdown_1columns ul {padding-bottom: 0px !important;}
        .lessons{display: none !important;}
        .dropdown_2columns .featured{padding-bottom: 30px;}
        
    }
    
</style>

<?php 

$get_menu_items_query = tep_db_query("SELECT * from menu_links  WHERE main_category = '1' ORDER BY sort_order ASC");

echo '<nav id="menu2" class="menu">
    <ul>';

while($get_menu_items = tep_db_fetch_array($get_menu_items_query)){
    if($get_menu_items['mobile_only'] == '1'){
        if($get_menu_items['number_of_columns'] > '0'){
            $main_heading_class = 'only-mobile topheading has-submenu'; 
        } else {
            $main_heading_class = 'only-mobile no-menu';
        }
    } else { 
        if($get_menu_items['number_of_columns'] > '0'){
            $main_heading_class = 'topheading has-submenu'; 
        } else {
            $main_heading_class = 'no-menu';   
        }
    }
    
    echo '<li class="'.$main_heading_class.'">
            <a href="'.$get_menu_items['url'].'">'.$get_menu_items['category_name'].'</a>';
            
       
    // Check for sub links
    $sub_main_links_query = tep_db_query("SELECT * FROM menu_links WHERE category_id = '".$get_menu_items['category_id']."'");
    $sub_main_links = tep_db_fetch_array($sub_main_links_query);
        $check_for_featured_query = tep_db_query("SELECT featured_category as value FROM menu_links WHERE category_id = '".$get_menu_items['category_id']."' ");
        $check_for_featured = tep_db_fetch_array($check_for_featured_query);
    
        if($get_menu_items['number_of_columns'] > '1' || $check_for_featured['value'] == '1'){
            
            if($check_for_featured['value'] == '1'){
                $num_columns = $get_menu_items['number_of_columns'] + 1; 
            } else {
                $num_columns = $get_menu_items['number_of_columns'];
            }
            
            if($num_columns == '5'){
                $columns = 'column-sm-2_4';
            } else {
                $num = 12/$num_columns;
                $columns = 'column-sm-'.$num;
            }
            
            echo '<div class="sub-menu m-dropdown dropdown_'.$num_columns.'columns">';
    	if($get_menu_items['featured_category'] == '1'){
            $category = strtolower($get_menu_items['category_name']);
            
        echo'<div class="featured column col">
                <a class="hdline">FEATURED</a>
                <ul style="display:block; overflow:inherit;">
                    <li><a href="https://www.jupiterkiteboarding.com/store/newproducts.php?cat='.$category.'">New Products</a></li>
                    <li><a href="https://www.jupiterkiteboarding.com/store/sale.php?cat='.$category.'">Sale</a></li>
                </ul>
            </div>';
        } 
            $counts = '0';
            
            if ($get_menu_items['number_of_columns'] == '1'){
                echo'<div class="column has-submenu col">
                <ul style="display:table; text-align:left; width:100%; padding-bottom:10px;">';
                
                $select_all_subcategories_query = tep_db_query("SELECT * from menu_links where parent_id = '".$get_menu_items['category_id']."' ORDER BY sort_order ASC");
                while($select_all_subcategories = tep_db_fetch_array($select_all_subcategories_query)){
                    $complete_url = '';
                
                    preg_match('/(?<=-c-).*?(?=\_)/', $select_all_subcategories['url'], $matches);
                    //$main_category = str_replace("_", "",$matches['0']);
                    $main_category = $matches['0'];

                    preg_match('/.*_(.*).*?(?=\.)/', $select_all_subcategories['url'], $match);
                    $sub_category = $match['1'];

                    // Get Category Name //
                    $sub_category_name = '&category['.$sub_category.']='.$select_all_subcategories['category_name'];
                    $variables = '';

                    $complete_url = 'main.php?catt='.$main_category.$sub_category_name;
                    echo '<li>
                    <a href="'.$complete_url.'">'.$select_all_subcategories['category_name'].'</a>
                    </li>';
                }
            echo '</ul>
            </div>';
            } else {
            
            $select_all_subcategories_query = tep_db_query("SELECT * from menu_links where parent_id = '".$get_menu_items['category_id']."' ORDER BY sort_order ASC");
            while($select_all_subcategories = tep_db_fetch_array($select_all_subcategories_query)){
                echo'<div class="column has-submenu col">';
                    
                $check_for_links_query = tep_db_query("SELECT * FROM menu_links WHERE parent_id = '".$select_all_subcategories['category_id']."'");
                    echo'<a class="hdline">'.$select_all_subcategories['category_name'].'</a> 
                    <ul style="display:block;">';
                    
                    $add_sub_head_links_info_query = tep_db_query("SELECT * FROM menu_links WHERE parent_id = '".$select_all_subcategories['category_id']."' ORDER BY sort_order ASC");    
                
                    while($add_sub_head_links_info = tep_db_fetch_array($add_sub_head_links_info_query)){
                        $gender = '';
                        preg_match('/(?<=-c-).*?(?=\_)/', $add_sub_head_links_info['url'], $matches);
                        
                        //$main_category = str_replace("_", "",$matches['0']);
                        $main_category = $matches['0'];

                        preg_match('/.*_(.*).*?(?=\.)/', $add_sub_head_links_info['url'], $match);
                        $sub_category = $match['1'];
                        
                        //preg_match('/.*?(?=\-c-)/', $add_sub_head_links_info['url'], $SubName);
                        preg_match('/(?<=store\/).+?(?=\-c-)/', $add_sub_head_links_info['url'], $SubName);
                        
                        if($sub_category == '768' || $sub_category == '767' || $sub_category == '45'){
                            $category_name = ucwords(str_replace("-", " ",$SubName['0']));    
                        } else {
                            $category_name = $add_sub_head_links_info['category_name'];
                        }
                        
                         // Get Category Name //
                        $sub_category_name = '&category['.$sub_category.']='.$category_name;
                        
                        if(preg_match("/gender/", $add_sub_head_links_info['url'])){
                        
                            //Get Gender
                            preg_match('/(?<=%5D=).*/', $add_sub_head_links_info['url'], $gender);
                        
                            //Variable Number
                            preg_match('/%5B(.*).?(?=%)/', $add_sub_head_links_info['url'], $var);
                            $gender = '&gender['.$var['1'].']='.$gender['0'];
                            
                            $gender = $gender;
                        } else {
                            $gender = '';
                        }
                        
                        if(preg_match("/brand/", $add_sub_head_links_info['url'])){
                        
                            //Get brand
                            preg_match('/(?<=%5D=).*/', $add_sub_head_links_info['url'], $brand);
                        
                            //Variable Number
                            preg_match('/%5B(.*).?(?=%)/', $add_sub_head_links_info['url'], $var);
                            $brand = '&brand['.$var['1'].']='.$brand['0'];
                            
                            $brand = $brand;
                        } else {
                            $brand = '';
                        }
            
                        
                        $complete_url = 'main.php?catt='.$main_category.$sub_category_name.$brand.$gender;
                        
                        echo '<li>
                        <a href="'.$complete_url.'">'.$add_sub_head_links_info['category_name'].'</a>
                        </li>'; 
                    }
                    if($counts == '0'){
                    $check_for_lessons_query = tep_db_query("SELECT * FROM menu_links WHERE category_name = 'Lessons' and main_category = '".$get_menu_items['category_id']."'");
                    $check_for_lessons = tep_db_fetch_array($check_for_lessons_query);
                    if(tep_db_num_rows($check_for_lessons_query) > '0'){
                        $complete_url = '';
                        
                        preg_match('/(?<=-c-).*?(?=\_)/', $check_for_lessons['url'], $matches);
                        
                        //$main_category = str_replace("_", "",$matches['0']);
                        $main_category = $matches['0'];
                        
                        preg_match('/.*_(.*).*?(?=\.)/', $check_for_lessons['url'], $match);
                        $sub_category = $match['1'];
                        
                        // Get Category Name //
                        $sub_category_name = '&category['.$sub_category.']=Lessons';
                        
                        
                        // Create Complete Url //
                        $complete_url = 'main.php?catt='.$main_category.$sub_category_name;
                        
                      echo '<li class="lessons">
                                <a href="'.$complete_url.'">
                                    <h4>Lessons</h4>
                                </a>
                            </li>';
                    }
                    
                    $check_for_rentals_query = tep_db_query("SELECT * FROM menu_links WHERE category_name = 'Rentals' and main_category = '".$get_menu_items['category_id']."'");
                    $check_for_rentals = tep_db_fetch_array($check_for_rentals_query);
                    if(tep_db_num_rows($check_for_rentals_query) > '0'){
                        $complete_url = '';
                        
                        preg_match('/(?<=-c-).*?(?=\_)/', $check_for_rentals['url'], $matches);
                        
                        //$main_category = str_replace("_", "",$matches['0']);
                        $main_category = $matches['0'];
                        
                        preg_match('/.*_(.*).*?(?=\.)/', $check_for_rentals['url'], $match);
                        $sub_category = $match['1'];
                        
                        // Get Category Name //
                        $sub_category_name = '&category['.$sub_category.']=Rentals';
                        
                        
                        // Create Complete Url //
                        $complete_url = 'main.php?catt='.$main_category.$sub_category_name;
                        
                        echo '<li class="lessons">
                                <a href="'.$complete_url.'">
                                    <h4>Rentals</h4>
                                </a>
                            </li>';
                    }
                
                }
                echo '</ul>
                </div>'; 
                
                $counts++;
            }
            
            $check_for_lessons_query = tep_db_query("SELECT * FROM menu_links WHERE category_name = 'Lessons' and main_category = '".$get_menu_items['category_id']."'");
            $check_for_lessons = tep_db_fetch_array($check_for_lessons_query);
                if(tep_db_num_rows($check_for_lessons_query) > '0'){
                    $complete_url = '';
                        
                    preg_match('/(?<=-c-).*?(?=\_)/', $check_for_lessons['url'], $matches);

                    //$main_category = str_replace("_", "",$matches['0']);
                    $main_category = $matches['0'];

                    preg_match('/.*_(.*).*?(?=\.)/', $check_for_lessons['url'], $match);
                    $sub_category = $match['1'];

                    // Get Category Name //
                    $sub_category_name = '&category['.$sub_category.']=Lessons';


                    // Create Complete Url //
                    $complete_url = 'main.php?catt='.$main_category.$sub_category_name;
                    
                echo '<div class="featured column col mobile-lessons">
                        <a class="hdline" href="'.$complete_url.'">
                            Lessons
                        </a>
                    </div>';
                    }
                    
                    $check_for_rentals_query = tep_db_query("SELECT * FROM menu_links WHERE category_name = 'Rentals' and main_category = '".$get_menu_items['category_id']."'");
                    $check_for_rentals = tep_db_fetch_array($check_for_rentals_query);
                    if(tep_db_num_rows($check_for_rentals_query) > '0'){
                        $complete_url = '';
                        
                        preg_match('/(?<=-c-).*?(?=\_)/', $check_for_rentals['url'], $matches);
                        
                        //$main_category = str_replace("_", "",$matches['0']);
                        $main_category = $matches['0'];
                        
                        preg_match('/.*_(.*).*?(?=\.)/', $check_for_rentals['url'], $match);
                        $sub_category = $match['1'];
                        
                        // Get Category Name //
                        $sub_category_name = '&category['.$sub_category.']=Rentals';
                        
                        
                        // Create Complete Url //
                        $complete_url = 'main.php?catt='.$main_category.$sub_category_name;
                        
                      echo '<div class="featured column col mobile-lessons">
                                <a class="hdline" href="'.$complete_url.'">
                                    Rentals
                                </a>
                            </div>';
                    }
            
        echo'</div>';
            }
        } elseif($get_menu_items['number_of_columns'] == '1'){
            echo '<div class="sub-menu m-dropdown dropdown_1columns">
            <ul style="display:table; text-align:left; width:100%; padding-bottom:10px;">';
            $select_all_subcategories_query = tep_db_query("SELECT * from menu_links where parent_id = '".$get_menu_items['category_id']."' ORDER BY sort_order ASC");
            while($select_all_subcategories = tep_db_fetch_array($select_all_subcategories_query)){
                $complete_url = '';
                
                preg_match('/(?<=-c-).*?(?=\_)/', $select_all_subcategories['url'], $matches);
                //$main_category = str_replace("_", "",$matches['0']);
                $main_category = $matches['0'];

                preg_match('/.*_(.*).*?(?=\.)/', $select_all_subcategories['url'], $match);
                $sub_category = $match['1'];

                // Get Category Name //
                $sub_category_name = '&category['.$sub_category.']='.$select_all_subcategories['category_name'];
                
                $complete_url = 'main.php?catt='.$main_category.$sub_category_name;
            
                echo '<li>
                <a href="'.$complete_url.'">'.$select_all_subcategories['category_name'].'</a>
                </li>';
            }
            echo '</ul>
            </div>';
        }    
}

echo '</nav>';
?>