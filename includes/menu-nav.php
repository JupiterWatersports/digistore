  <style>
    .topheading > a {text-transform: uppercase;}
      .menu ul li a {
    display: block;
    text-decoration: none;
}
    .topheading > a{white-space: nowrap;}
    div.column{text-align:left;vertical-align:top;display:inline-block; width:146px; /*float:left*/}
.menu .column ul{display:none;position:relative; top:0px; width:100%;z-index:99999999;margin-right:0}
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

.dropdown-1columns{
    min-width:140px;
}

.dropdown-2columns{
    width:320px;
}

.dropdown-3columns{
    width:460px;
}

.dropdown-4columns{
    width:645px;
}

.dropdown-5columns{
    width:850px;
}

.dropdown-6columns{
    width:100%;
    left:0px;
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

.menu_left{left:40px;}

.menu_end{right:0px;}
    
</style>

<?php 

$get_menu_items_query = tep_db_query("SELECT * from menu_links  WHERE main_category = '1' ORDER BY sort_order ASC");

echo '<nav id="menu2" class="menu">
    <ul>';

	$count_headings = '0';

    $get_number_of_links_query = tep_db_query("SELECT COUNT(*) as count FROM menu_links WHERE main_category = '1' AND mobile_only <> '1'");
    $get_number_of_links = tep_db_fetch_array($get_number_of_links_query);

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
	    $count_headings ++; 
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

	//If Link is towards the end and has the potential to go off the edge of the screen add specific styling

            if($get_number_of_links['count'] == $count_headings){
                $styling = 'menu_end';
            } else {
                $styling = '';
            }

            if(($get_number_of_links['count'] - 1) == $count_headings){
                $styling = 'menu_end';
            } else {
                $styling = '';
            }
/*
            if(($get_number_of_links['count'] - 2) >= $count_headings && $num_columns >= '2'){
                $styling = 'menu_end';
            } else {
                $styling = '';
            }
    */

            if($count_headings == '4'){
                $styling = 'menu_left';

            }

            if($count_headings >= '6'){
                if($num_columns == '5' || $num_columns == '4'){
                    $styling = 'menu_end';
                }


            } else {
                $styling = '';
            }
            
            echo '<div class="sub-menu m-dropdown dropdown-'.$num_columns.'columns '.$styling.'">';
    	if($get_menu_items['featured_category'] == '1'){
            $category = strtolower($get_menu_items['category_name']);
            
        echo'<div class="featured column col">
                <a class="hdline">FEATURED</a>
                <ul style="display:block;">
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
                echo '<li>
                <a href="'.$select_all_subcategories['url'].'">'.$select_all_subcategories['category_name'].'</a>
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
                        echo '<li><a href="'.$add_sub_head_links_info['url'].'">'.$add_sub_head_links_info['category_name'] . '</a>
                        </li>'; 
                    }
                    if($counts == '0'){
                    
                    	$check_for_lessons_query = tep_db_query("SELECT * FROM menu_links WHERE category_id = '".$get_menu_items['category_id']."' AND lessons_category > '0'");	
                    
                    	$lessons_query = tep_db_query("SELECT * FROM menu_links WHERE category_name = 'Lessons' and main_category = '".$get_menu_items['category_id']."'");
                    	$lessons = tep_db_fetch_array($lessons_query);
                    		if(tep_db_num_rows($check_for_lessons_query) > '0'){
                      echo '<li class="lessons">
                                <a href="'.$lessons['url'].'">
                                    <h4>Lessons</h4>
                                </a>
                            </li>';
                    	}
                    	
                    	$check_for_rentals_query = tep_db_query("SELECT * FROM menu_links WHERE category_id = '".$get_menu_items['category_id']."' AND rental_category > '0'");
                    	
                    	$rentals_query = tep_db_query("SELECT * FROM menu_links WHERE category_name = 'Rentals' and main_category = '".$get_menu_items['category_id']."'");
                    	$rentals = tep_db_fetch_array($rentals_query);
                    	if(tep_db_num_rows($check_for_rentals_query) > '0'){
                      	echo '<li class="lessons">
                                <a href="'.$rentals['url'].'">
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
                echo '<div class="featured column col mobile-lessons">
                        <a class="hdline" href="'.$check_for_lessons['url'].'">
                            Lessons
                        </a>
                    </div>';
                    }
                    
                    $check_for_rentals_query = tep_db_query("SELECT * FROM menu_links WHERE category_name = 'Rentals' and main_category = '".$get_menu_items['category_id']."'");
                    $check_for_rentals = tep_db_fetch_array($check_for_rentals_query);
                    if(tep_db_num_rows($check_for_rentals_query) > '0'){
                      echo '<div class="featured column col mobile-lessons">
                                <a class="hdline" href="'.$check_for_rentals['url'].'">
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
                echo '<li>
                <a href="'.$select_all_subcategories['url'].'">'.$select_all_subcategories['category_name'].'</a>
                </li>';
            }
            echo '</ul>
            </div>';
        } 
     
    
    
}
/*<li class="only-mobile no-menu"><a href="http://www.jupiterkiteboarding.com/">Home</a></li>
<li class="only-mobile no-menu"><a href="<?php echo tep_href_link('contact-us.php'); ?>">Contact Us</a></li>
<li class="only-mobile topheading has-submenu"><a>Account</a>
<ul class="sub-menu">
<li><a href="<?php echo tep_href_link(FILENAME_ACCOUNT, '', 'SSL'); ?>">My Account</a></li>
<li><a href="<?php echo tep_href_link(FILENAME_LOGIN, '', 'SSL'); ?>">Login</a></li>
<li><a href="<?php echo tep_href_link(FILENAME_LOGOFF, '', 'SSL'); ?>">Logoff</a></li>
</ul>
</li>

<li class="topheading has-submenu"><a href="kiteboarding-c-611.html">KITE</a>
<div class="sub-menu dropdown_6columns" >
    	<div class="featured column col-sm-2">
        <a class="hdline">FEATURED</a>
        <ul style="display:block; overflow:inherit;">
        <li><a href="<?php echo tep_href_link('newproducts.php?cat=kite'); ?>">New Products</a></li>
        <li><a href="<?php echo tep_href_link('sale.php?cat=kite'); ?>">Sale</a></li>
        </ul>
        </div>
		<div class="column has-submenu col-sm-2">
		<a class="hdline">Kites</a>
		<ul style="display:block;">
        <li><a href="<?php echo tep_href_link('trainer-kites-lessons-trainer-kites-packages-c-611_587_52.html'); ?>">Trainer Kites</a></li>
        <li><a href="<?php echo tep_href_link('kitesurfing-kites-c-611_45.html?filter_id=68&sort=products_sort_order'); ?>">Airush</a></li>
        <li><a href="<?php echo tep_href_link('cabrinha-kites'); ?>">Cabrinha</a></li>
        <li><a href="<?php echo tep_href_link('north-kites'); ?>">North</a></li>
        <li><a href="<?php echo tep_href_link('kitesurfing-kites-c-611_45.html?filter_id=35&sort=products_sort_order'); ?>">Wainman Hawaii</a></li>
        <li><a href="<?php echo tep_href_link('kitesurfing-kites-c-611_45.html?filter_id=135&sort=products_sort_order'); ?>">F-One</a></li>
        <li><a href="<?php echo tep_href_link('kitesurfing-kites-c-611_45.html?filter_id=29&sort=products_sort_order'); ?>">Ozone</a></li>
        <li><a href="<?php echo tep_href_link('kitesurfing-kites-c-611_45.html?filter_id=163&sort=products_sort_order'); ?>">Ocean Rodeo</a></li>
        <li><a href="<?php echo tep_href_link('kiteboarding-packages'); ?>">Packages</a></li>  
        <li class="lessons"><a href="<?php echo tep_href_link('kiteboarding-lessons-c-611_578.html'); ?>"><h3>Lessons</h3></a></li>
        </ul>
		</div>

        <div class="column has-submenu col-sm-2">
        <a class="hdline">Boards</a>
        <ul style="display:block;">
        <li><a href="<?php echo tep_href_link('kiteboards-fins-pads-straps-twin-kiteboards-c-611_305_566.html'); ?>">Twin Tips</a></li>
        <li><a href="<?php echo tep_href_link('kiteboards-fins-pads-straps-kite-surfboards-c-611_305_567.html'); ?>">Kite Surfboards</a></li>
        <li><a href="<?php echo tep_href_link('kiteboards-fins-pads-straps-foil-boards-c-611_305_680.html'); ?>">Foil Boards</a></li>
        <li><a href="<?php echo tep_href_link('kiteboards-fins-pads-straps-pads-straps-components-c-611_305_182.html'); ?>">Pads &amp; Straps</a></li>
        <li><a href="<?php echo tep_href_link('kiteboards-fins-pads-straps-kiteboard-fins-c-611_305_206.html'); ?>">Fins</a></li>
        <li><a href="<?php echo tep_href_link('used-kiteboards'); ?>">Used Boards</a></li>
        </ul>
        </div>

        <div class="column has-submenu col-sm-2">
        <a class="hdline">Harnesses</a>
        <ul style="display:block;">
        <li><a href="<?php echo tep_href_link('harnesses-waist-harness-c-611_312_568.html'); ?>">Waist</a></li>
        <li><a href="<?php echo tep_href_link('harnesses-seat-harness-c-611_312_569.html'); ?>">Seat</a></li>
        <li><a href="<?php echo tep_href_link('harnesses-impact-vests-impact-harnesses-c-611_312_255.html'); ?>">Impact Vests</a></li>
        <li><a href="<?php echo tep_href_link('harnesses-kite-harness-accessories-c-611_312_463.html'); ?>">Accessories</a></li>
        </ul>
        </div>
        
        <div class="column has-submenu col-sm-2 control-bars">
        <a class="hdline">Control Bars</a>
        <ul style="display:block;">
        <li><a href="<?php echo tep_href_link('complete-bars-c-611_62_681.html'); ?>">Complete Bars</a></li>
        <li><a href="<?php echo tep_href_link('chicken-loop-lines-c-611_62_614.html'); ?>">Chicken Loop Lines</a></li>
        <li><a href="<?php echo tep_href_link('control-bars-lines-replacement-lines-c-611_62_48.html'); ?>">Replacement Lines</a></li>
        <li><a href="<?php echo tep_href_link('control-bars-lines-safety-leashes-c-611_62_230.html'); ?>">Safety Leashes</a></li>
        <li><a href="<?php echo tep_href_link('control-bars-lines-replacement-parts-c-611_62_615.html'); ?>">Parts</a></li> 
        </ul>
        </div>
        
        <div class="column has-submenu col-sm-2">
        <a class="hdline">Accessories</a>
        <ul style="display:block;">
        <li><a href="<?php echo tep_href_link('accessories-kite-board-bags-c-611_36_66.html'); ?>">Kite &amp; Board Bags</a></li>
        <li><a href="<?php echo tep_href_link('accessories-helmets-c-611_36_193.html'); ?>">Helmets</a></li>
        <li><a href="<?php echo tep_href_link('accessories-kite-pumps-c-611_36_224.html'); ?>">Pumps</a></li>
        <li><a href="<?php echo tep_href_link('kite-bladder-board-repair-c-611_65.html'); ?>">Repair</a></li>
        <li><a href="<?php echo tep_href_link('accessories-wind-meters-c-611_36_505.html'); ?>">Wind Meter</a></li>   
        </ul>
        </div>
<span class="only-mobile no-menu" style="float:left;"><a style="color:#606060; font-weight:600; text-transform:uppercase; font-size:17px;" href="<?php echo tep_href_link('kiteboarding-lessons-c-611_578.html'); ?>">Lessons</a></span>

</div>
</li>

<li class="topheading has-submenu"><a href="paddleboarding-c-612.html">PADDLE</a>
<div class="sub-menu dropdown_5columns">

<div class="featured column col-sm-2">
        <a class="hdline">FEATURED</a>
        <ul style="display:block; overflow:inherit;">
        <li><a href="<?php echo tep_href_link('newproducts.php?cat=paddle'); ?>">New Products</a></li>
        <li><a href="<?php echo tep_href_link('sale.php?cat=paddle'); ?>">Sale</a></li>
        </ul>
</div>

<div class="column has-submenu col-sm-2">
<a class="hdline">Boards</a>
<ul style="display:block;">
<li><a href="<?php echo tep_href_link('paddleboards-around-paddleboards-c-612_572.html'); ?>">All Around</a></li>
<li><a href="<?php echo tep_href_link('paddleboards-surfing-paddleboards-c-612_573.html'); ?>">Surfing</a></li>
<li><a href="<?php echo tep_href_link('paddleboards-racing-touring-paddleboards-c-612_571.html'); ?>">Racing/Touring</a></li>
<li><a href="<?php echo tep_href_link('paddleboards-fishing-paddleboards-c-612_581_603.html'); ?>">Fishing</a></li>
<li><a href="<?php echo tep_href_link('paddleboards-inflatable-paddleboards-c-612_581_574.html'); ?>">Inflatable</a></li>
<li><a href="<?php echo tep_href_link('paddleboards-used-paddleboards-c-612_581_586.html'); ?>">Used Boards</a></li>
<li class="lessons"><a href="<?php echo tep_href_link('lessons-tours-c-612_588.html'); ?>"><h3>Lessons</h3></a></li>
<li class="lessons"><a href="<?php echo tep_href_link('rentals-c-612_632.html'); ?>"><h3>Rentals</h3></a></li>
</ul>
</div>

<div class="column has-submenu col-sm-2">
<a class="hdline">Paddles</a>
<ul style="display:block;">
<li><a href="<?php echo tep_href_link('paddles-piece-standard-paddles-c-612_394_473.html'); ?>">1 Piece</a></li>
<li><a href="<?php echo tep_href_link('paddles-piece-adjustable-paddles-c-612_394_475.html'); ?>">2 Piece Adjustable</a></li>
<li><a href="<?php echo tep_href_link('paddles-piece-adjustable-paddles-c-612_394_474.html'); ?>">3 Piece Adjustable</a></li>
<li><a href="<?php echo tep_href_link('paddles-racing-paddles-c-612_394_631.html'); ?>">Racing Paddles</a></li>
</ul>
</div>

<div class="column has-submenu col-sm-2 accessories">
<a class="hdline">Accessories</a>
<ul style="display:block;">
<li><a href="<?php echo tep_href_link('paddleboard-accessories-board-accessories-c-612_638.html'); ?>">Board Accessories</a></li>
<li><a href="<?php echo tep_href_link('paddleboard-accessories-board-paddle-bags-c-612_437.html'); ?>">Board/Paddle Bags</a></li>
<li><a href="<?php echo tep_href_link('paddleboard-repair-protection-board-paddle-protection-c-612_623.html'); ?>">Board &amp; Paddle Protection</a></li>
<li><a href="<?php echo tep_href_link('paddleboard-accessories-coolers-c-612_641.html'); ?>">Coolers</a></li>
<li><a href="<?php echo tep_href_link('paddleboard-accessories-fins-c-612_487.html'); ?>">Fins</a></li>
<li><a href="<?php echo tep_href_link('paddleboard-accessories-leashes-c-612_438.html'); ?>">Leashes</a></li>
<li><a href="<?php echo tep_href_link('paddleboard-accessories-paddleboarding-pfds-c-612_563.html'); ?>">Life Jackets</a></li>
<li><a href="<?php echo tep_href_link('paddleboard-repair-protection-repair-products-c-612_624.html'); ?>">Repair Products</a></li>
<li><a href="<?php echo tep_href_link('paddleboard-accessories-traction-pads-c-612_626.html'); ?>">Traction Pads</a></li>
</ul>
</div>

<div class="column has-submenu col-sm-2 racks">
<a class="hdline">Racks</a>
<ul style="display:block;">
<li><a href="<?php echo tep_href_link('racks-accessories-ceiling-rack-c-612_634.html'); ?>">Ceiling</a></li>
<li><a href="<?php echo tep_href_link('racks-accessories-wall-racks-c-612_557.html'); ?>">Wall</a></li>
<li><a href="<?php echo tep_href_link('racks-accessories-roof-racks-c-612_556.html'); ?>">Car Roof Rack</a></li>
<li><a href="<?php echo tep_href_link('racks-accessories-rack-accessories-c-612_606.html'); ?>">Car Roof Rack Accessories</a></li>
</ul>
</div>
<span class="only-mobile no-menu" style="float:left; width:100%;"><a style="color:#606060; font-weight:600; text-transform:uppercase; font-size:17px;" href="<?php echo tep_href_link('lessons-tours-c-612_588.html'); ?>">Lessons</a></span>
<span class="only-mobile no-menu" style="float:left;  width:100%;"><a style="color:#606060; font-weight:600; text-transform:uppercase; font-size:17px;" href="<?php echo tep_href_link('rentals-c-612_632.html'); ?>">Rentals</a></span>
</div>
</li>

<li class="topheading has-submenu"><a href="wakeboarding-c-200.html">WAKE</a>
<div class="sub-menu dropdown_4columns">

<div class="featured column col-sm-2">
        <a class="hdline">FEATURED</a>
        <ul style="display:block; overflow:inherit;">
        <li><a href="<?php echo tep_href_link('newproducts.php?cat=wake'); ?>">New Products</a></li>
        <li><a href="<?php echo tep_href_link('sale.php?cat=wake'); ?>">Sale</a></li>
        </ul>
</div>

<div class="column has-submenu col-sm-2">
<a class="hdline">Boards</a>
<ul style="display:block;">
<li><a href="<?php echo tep_href_link('wakeboards-c-200_762.html?gender%5B1%5D=male'); ?>">Mens</a></li>
<li><a href="<?php echo tep_href_link('wakeboards-c-200_762.html?gender%5B2%5D=female'); ?>">Womens</a></li>
<li><a href="<?php echo tep_href_link('wakeboarding-kids-wakeboards-c-200_643.html'); ?>">Kids</a></li>
<li><a href="<?php echo tep_href_link('wakeboarding-wakeboard-combos-c-200_562.html'); ?>">Combo</a></li>
<li><a href="<?php echo tep_href_link('wakesurfers-c-200_708.html'); ?>">Wakesurfers</a></li>
<li><a href="<?php echo tep_href_link('wakeboarding-wakeskates-c-200_281.html'); ?>">Wakeskates</a></li>
<li class="lessons"><a href="<?php echo tep_href_link('wakeboarding-lessons-c-200_558.html'); ?>"><h3>Lessons</h3></a></li>
</ul>
</div>

<div class="column has-submenu col-sm-2">
<a class="hdline">Bindings</a>
<ul style="display:block;">
<li><a href="<?php echo tep_href_link('wakeboarding-wake-bindings-c-200_466.html'); ?>">Mens</a></li>
<li><a href="<?php echo tep_href_link('wakeboarding-wake-bindings-women-c-200_465.html'); ?>">Womens</a></li>
<li><a href="<?php echo tep_href_link('wakeboarding-wake-bindings-c-200_467.html'); ?>">Kids</a></li>
</ul>
</div>

<div class="column has-submenu col-sm-2 racks">
<a class="hdline">Accessories</a>
<ul style="display:block;">
<li><a href="<?php echo tep_href_link('wakeboarding-life-jackets-impact-vests-c-200_210.html'); ?>">Life Jackets &amp; Impact Vests</a></li>
<li><a href="<?php echo tep_href_link('wakeboarding-wakeboard-rope-c-200_211.html'); ?>">Wakeboard Rope</a></li>
</ul>
</div>
<span class="only-mobile no-menu" style="float:left; width:100%;"><a style="color:#606060; font-weight:600; text-transform:uppercase; font-size:17px;" href="<?php echo tep_href_link('wakeboarding-lessons-c-200_558.html'); ?>">Lessons</a></span>
</div>
</li>
<li class="topheading has-submenu"><a href="">FOIL</a>
    <div class="sub-menu dropdown_1columns">

        <div class="column has-submenu col-sm-2">
            <ul style="display:block;">
                <li><a href="<?php echo tep_href_link('foil-boards-c-611_305_680.html'); ?>">Kite Foil</a></li>
                <li><a href="<?php echo tep_href_link('foil-paddleboards-c-612_581_753.html'); ?>">Paddle Foil</a></li>
                <li><a href="<?php echo tep_href_link('wake-foils-c-200_761.html'); ?>">Wake Foil</a></li>
            </ul>
        </div>
    </div>
</li>
    
<li class="topheading has-submenu"><a href="surfing-c-627.html">SURF</a>
<ul class=" sub-menu dropdown_1columns surfing">
<li><a href="<?php echo tep_href_link('surfing-boards-c-627_646.html'); ?>">Boards</a></li>
<li><a href="<?php echo tep_href_link('surfing-replacement-fins-c-627_645.html'); ?>">Fins</a></li>
<li><a href="<?php echo tep_href_link('surfing-traction-pads-c-627_628.html'); ?>">Traction Pads</a></li>
<li><a href="<?php echo tep_href_link('surfing-board-bags-c-627_629.html'); ?>">Board Bags</a></li>
<li><a href="<?php echo tep_href_link('surfing-leashes-c-627_648.html'); ?>">Leashes</a></li>
<li><a href="<?php echo tep_href_link('surfing-rescue-sleds-c-627_553.html'); ?>">Rescue Sleds</a></li>
</ul>
</li>
    
<li class="topheading has-submenu wwear"><a href="water-wear-c-67.html">WATER WEAR</a>
    <div class="sub-menu dropdown_2columns">
        <div class="column has-submenu col-sm-6">
            <ul style="display:block">
                <li><a href="<?php echo tep_href_link('water-wear-wetsuits-c-67_316.html'); ?>">Wetsuits</a></li>
                <li><a href="<?php echo tep_href_link('water-wear-wetsuit-tops-c-67_318.html'); ?>">Wetsuit Tops</a></li>
                <li><a href="<?php echo tep_href_link('water-wear-rash-guards-c-67_302.html'); ?>">Rash Guards</a></li>
                <li><a href="<?php echo tep_href_link('booties-c-67_651.html'); ?>">Booties</a></li>
            </ul>
        </div>
        <div class="column has-submenu col-sm-6">
            <ul style="display:block;">
                <li><a href="<?php echo tep_href_link('water-wear-swim-shorts-c-67_388.html'); ?>">Swim Shorts</a></li>
                <li><a href="<?php echo tep_href_link('hats-c-67_297.html'); ?>">Hats</a></li>
                <li><a href="<?php echo tep_href_link('water-wear-sunglasses-c-67_304.html'); ?>">Sunglasses</a></li> 
                <li><a href="<?php echo tep_href_link('water-wear-gloves-c-67_461.html'); ?>">Gloves</a></li>
                <li><a href="<?php echo tep_href_link('water-wear-waterproof-packs-c-67_552.html'); ?>">Waterproof Packs</a></li>
                <li><a href="<?php echo tep_href_link('water-wear-sunscreen-c-67_570.html'); ?>">Sunscreen</a></li>  
            </ul>
        </div>
    </div>
</li>

<li class="topheading has-submenu"><a href="gopro-c-551.html">GOPRO</a>
<ul class="sub-menu dropdown_1columns gopro">
<li><a href="<?php echo tep_href_link('gopro-gopro-hero-cameras-c-551_598.html'); ?>">Cameras</a></li>
<li><a href="<?php echo tep_href_link('gopro-gopro-hero-mounts-c-551_599.html'); ?>">Mounts</a></li>
<li><a href="<?php echo tep_href_link('gopro-gopro-hero-accessories-c-551_600.html'); ?>">Accessories</a></li>       
</ul>
</li>

<li class="topheading has-submenu"><a href="skate-balance-boards-c-582.html">SKATE</a>
<ul class="sub-menu dropdown_1columns skate">
<li><a href="<?php echo tep_href_link('electric-skateboards-c-582_650.html'); ?>">Electric Skateboards</a></li>
<li><a href="<?php echo tep_href_link('skate-balance-boards-longboards-skateboards-c-582_575.html'); ?>">Longboards &amp; Skateboards</a></li>
<li><a href="<?php echo tep_href_link('skate-balance-boards-balance-boards-c-582_555.html'); ?>">Balance Boards</a></li>
<li><a href="<?php echo tep_href_link('skate-balance-boards-kiteboard-landboards-c-582_576.html'); ?>">Kite Landboards</a></li>
<li><a href="<?php echo tep_href_link('land-paddles-c-612_394_564.html'); ?>">Land Paddle</a></li>
</ul>
</li>

<li class="topheading has-submenu"><a href="windsurfing-c-549.html">WINDSURF</a>
<ul class="sub-menu dropdown_1columns wndsrf">
<li><a href="<?php echo tep_href_link('windsurfing-windsurfing-complete-c-549_589.html'); ?>">Complete Kit</a></li>
<li><a href="<?php echo tep_href_link('windsurfing-windsurfing-mast-bases-c-549_590.html'); ?>">Mast Bases</a></li>
<li><a href="<?php echo tep_href_link('windsurfing-windsurfing-mast-extensions-c-549_591.html'); ?>">Mase Extensions</a></li>
<li><a href="<?php echo tep_href_link('windsurfing-windsurfing-cleats-lines-c-549_592.html'); ?>">Cleats &amp; Lines</a></li>
</ul>
</li>

<li class="no-menu"><a href="<?php echo tep_href_link('sale'); ?>">SALE</a></li>
<li class="no-menu"><a href="http://www.jupiterkiteboarding.com/store/blog/">Blog</a></li>

<li class="only-mobile no-menu"><a href="<?php echo tep_href_link('gift-certificates-c-559.html') ?>">Gift Certificates</a></li>
</ul>
</nav>
*/

echo '</nav>';
?>
