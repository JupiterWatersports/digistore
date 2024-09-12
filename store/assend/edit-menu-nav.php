<?php
  require('includes/application_top.php');

if(isset($_GET['action'])){
    $action = $_GET['action'];
}

if(isset($_POST['action'])){
    $action = $_POST['action'];
}

  if (tep_not_null($action)) {
    switch($action){
        case 'edit_main_link':
            $update_main_cat = tep_db_query("UPDATE menu_links SET category_name = '".$_GET['category_name']."',  number_of_columns = '".$_GET['number_of_columns']."', sort_order = '".$_GET['sort_order']."', featured_category = '".$_GET['featured_section']."', lessons_category = '".$_GET['lessons_section']."',
          rental_category = '".$_GET['rentals_section']."'  where category_id = '".$_GET['category_id']."'");
            
            if($_GET['lessons_section'] == '1'){
                $check_for_lessons = tep_db_query("SELECT * FROM menu_links WHERE category_name = 'Lessons' and main_category = '".$_GET['category_id']."'");
                
                if(tep_db_num_rows($check_for_lessons) > 0){
                    // Do Nothing
                } else {
                  $data = array(
                      'category_name' => 'Lessons',
                      'main_category' => $_GET['category_id']  
                  );
                  tep_db_perform("menu_links", $data);
                }
            } else {
            	tep_db_query("UPDATE menu_links SET lessons_category = '0' WHERE main_category = '".$_GET['category_id']."'");
            }
            
            if($_GET['rentals_section'] == '1'){
                $check_for_rentals = tep_db_query("SELECT * FROM menu_links WHERE category_name = 'Rentals' and main_category = '".$_GET['category_id']."'");
                
                if(tep_db_num_rows($check_for_rentals) > 0){
                    // Do Nothing
                } else {
                  $data = array(
                      'category_name' => 'Rentals',
                      'main_category' => $_GET['category_id']  
                  );
                  tep_db_perform("menu_links", $data);
                }
            } else {
            	tep_db_query("UPDATE menu_links SET rental_category = '0' WHERE main_category = '".$_GET['category_id']."'");
            }
            
        tep_redirect(tep_href_link('edit-menu-nav.php'));
        break;
            
        case 'add_main_link':
            
            $array = array(
            'category_id'=> '',
            'category_name' => $_GET['category_name'],
            'parent_id' => 0,
            'main_category' => '1',
            'number_of_columns' => $_GET['number_of_columns'],    
            'sort_order' => $_GET['sort_order'],
            'master_category_id' => 0,
            'mobile_only' => $_GET['mobile_only'],
            'featured_category' => 0,
            'lessons_category' => 0,
            'rental_category' => 0,    
            'url' => ''    
            );
                
            tep_db_perform("menu_links", $array);
            
            if($_GET['mobile_only'] == '1'){
            
            $new_cat_id = tep_db_insert_id(); 
            $value = $new_cat_id + 1000;    
            $query = tep_db_query("UPDATE menu_links SET category_id= '".$value."' WHERE id = '".$new_cat_id."'");
            }
            
         tep_redirect(tep_href_link('edit-menu-nav.php'));
        break;
            
        case 'deleteHeadline':
            $update = tep_db_query("DELETE FROM menu_links where category_id = '".$_GET['headlineID']."'");
        
        tep_redirect(tep_href_link('edit-menu-nav.php'));
        break;
            
        case 'saveHeadline':
            $update = tep_db_query("UPDATE menu_links SET category_name = '".$_GET['inputHeadline']."', sort_order = '".$_GET['headlineSort']."' WHERE category_id = '".$_GET['headlineID']."'");
        tep_redirect(tep_href_link('edit-menu-nav.php'));
        break;
            
        case 'saveHeadlineNew':
            
            $array = array(
            'category_id'=> '',
            'category_name' => $_GET['inputHeadline'],
            'parent_id' => $_GET['masterCategory'],
            'main_category' => 0,
            'number_of_columns' => '',     
            'sort_order' => 0,
            'master_category_id' => $_GET['masterCategory'],
            'mobile_only' => 0,
            'featured_category' => 0,
            'lessons_category' => 0,
            'rental_category' => 0, 
            'url' => ''    
            );
            tep_db_perform("menu_links", $array);
        
        $new_cat_id = tep_db_insert_id(); 
        $value = $new_cat_id + 1000;    
        $query = tep_db_query("UPDATE menu_links SET category_id= '".$value."' WHERE id = '".$new_cat_id."'");
            
        tep_redirect(tep_href_link('edit-menu-nav.php'));
        break;
            
        case 'saveSingleLink':
            $query = tep_db_query("UPDATE menu_links SET category_name = '".$_GET['editLinksName']."', sort_order = '".$_GET['sort_order']."', url = '".$_GET['editUrl']."' WHERE category_id = '".$_GET['category_id']."'");
      tep_redirect(tep_href_link('edit-menu-nav.php'));    
            
        break;
            
        case 'deleteSingleLink':
            $query = tep_db_query("DELETE FROM menu_links WHERE category_id = '".$_GET['category_id']."'");
        tep_redirect(tep_href_link('edit-menu-nav.php'));    
            
        break;    
            
        case 'autoPop':
        $add_links_query = tep_db_query("SELECT * FROM categories c, categories_description cd WHERE c.categories_id = cd.categories_id and c.parent_id = '".$_GET['category_id']."'");
            
        while($add_links = tep_db_fetch_array($add_links_query)){
            $values = array(
            'category_id' => $add_links['categories_id'],
            'category_name' => $add_links['categories_name'],
            'parent_id'=> $_GET['category_id'],
            'main_category' => 0,   
            'number_of_columns' => '0',
            'sort_order' => '0',
            'master_category_id' => '0',
            'mobile_only' => '0',
            'featured_category' => '0',
            'lessons_category' => 0,
            'rental_category' => 0, 
            'url' => '',   
            );
            
            tep_db_perform("menu_links", $values);
        }
        tep_redirect(tep_href_link('edit-menu-nav.php'));
        break; 
            
        case 'saveNewSingleLinks':
            if(isset($_POST['newSingleUrl'])){
                foreach($_POST['newSingleUrl'] as $number=> $new_url){
                    $new_urls[] = array('url' => $new_url);
                }
            }
            
            if(isset($_POST['newSingleSortOrder'])){
                foreach($_POST['newSingleSortOrder'] as $number=> $new_SingleSortorder){
                    $new_single_sort_order[] = array('url' => $new_SingleSortorder);
                }
            }
            
            
            if(isset($_POST['newSingleLinksName'])){
                foreach($_POST['newSingleLinksName'] as $number=> $newSingleName){
                    if($newSingleName <> ''){
                    $values = array(
                    'category_id' => '',
                    'category_name' => $newSingleName,
                    'parent_id'=> $_POST['catID'],
                    'main_category' => 0,  
                    'number_of_columns' => '0',
                    'sort_order' => $_POST['newSingleSortOrder'][$number],
                    'master_category_id' => '0',
                    'mobile_only' => '0',
                    'featured_category' => '0',
                    'lessons_category' => 0,
                    'rental_category' => 0, 
                    'url' => $_POST['newSingleUrl'][$number],   
                    );
                    tep_db_perform('menu_links', $values);
                    
                    $new_cat_id = tep_db_insert_id(); 
                    $value = $new_cat_id + 1000;    
                    $query = tep_db_query("UPDATE menu_links SET category_id= '".$value."' WHERE id = '".$new_cat_id."'");
                    }
                }
            }
            tep_redirect(tep_href_link('edit-menu-nav.php'));
        break;
            
        case 'saveLesson':
            tep_db_query("UPDATE menu_links SET url = '".$_POST['lessons_input']."' WHERE category_name = 'Lessons' and main_category = '".$_POST['category_id']."'");
        break;
            
        case 'saveRental':
            tep_db_query("UPDATE menu_links SET url = '".$_POST['rentals_input']."' WHERE category_name = 'Rentals' and main_category = '".$_POST['category_id']."'");
        break;    
  }
}
    
// check if the catalog image directory exists

?>
<style>
    .menu-item .active {background-color:#d7d7d7; color:#000;}
    .auto{width:auto !important;}
    .hundo{width:100% !important;}
    .show-overlay{height:85%; overflow: hidden}
    #wrapper{width:100% !important;}    
.example-menu{width:100%; display:table; clear: both;}
.example-menu ul{padding: 0; margin: 0 auto; list-style: none; position: relative; display:flex; justify-content:center;} 
.example-menu >ul>li {display: inline-block; flex: 0; flex-grow: 1; flex-shrink: 0; white-space: nowrap;} 
.example-menu ul li > a {padding: 15px 9px; display: block; text-decoration: none;color: #FFF; font-size: 13px; font-weight: normal;}
.example-menu ul ul>li a { padding: 7px 0px; height: auto; color: #015B86; font-size: 14px; text-align: left;
} 
.example-menu .sub-menu .has-submenu > a:after{content:''}
.example-menu ul li a {display: block; text-decoration: none; text-align: center;
}    
.sub-menu{display: none; position: absolute; background-color: #d7d7d7; z-index: 99999999;}
div.column{text-align:left;vertical-align:top;display:inline-block;white-space:normal;}
.example-menu .column ul{display:none;position:relative;width:100%;z-index:99999999;margin-right:0}
.column .hdline { font-weight: 600; font-size: 17px; text-transform: uppercase; color: #606060; text-align: left; padding: 15px 0px; }
.dropdown_1columns{width:315px;z-index:4;}
.dropdown_3columns{width:460px;z-index:4}
.dropdown_4columns{width:645px;z-index:4;}
.dropdown_5columns{width:850px;z-index:4; left:0px;}
.dropdown_6columns{width:910px;z-index:4; left:0px;}
    
.dropdown_1columns ul li{padding:5px 10px;}
.active + .m-dropdown{display: flex; flex-wrap: wrap;} 
.edit-stuff{display:none; position:absolute; top:30px; width: 300px; height: auto; z-index: 1000000; background: #fff;}
.edit-stuff-active {display:block;} 
#wrapper .example-menu .dropdown_1columns > ul li a { padding:10px 5px; color: #015B86; font-size: 14px; }
    
    .col-xs-12{padding: 0px 15px;}
    
    @media (min-width:920px){
        #lower{display:none;}        
    }
    
    @media (min-width:1200px){
        
        .water-wear{min-width:145px;}
    }
    
    @media (max-width:1024px){
        .menu-item{text-align: center;}
        .menu-item > span{margin-top:10px;}
        #controller{width:90%;}
    }
    
/* Tablet and Media Queries */
    
    @media (max-width: 919px){  
        #lower {
            height: 55px;
            width: 100%;
            background: rgba(58, 60, 65, 1);
            position: relative;
            display:table;
        }
        
        #lower .menu-icon {
            font-size: 32px;
            display: block;
            position: relative;
            width: 40px;
            height: 36px;
            text-align: center;
            cursor: pointer;
            -moz-user-select: none;
            -webkit-user-select: none;
            border-radius: 3px;
            margin-bottom: 6px;
            margin-left: 10px;
            margin-right: auto;
        }
        
        a.menu-link2 {
            display: block;
            float: left;
            text-decoration: none;
            margin-top: 9px;
        }
        
        .mobile-menu-search, #log {
            float: left;
            width: 25%;
        }
        .mobile-logo, .mobile-menu-search {
            display: block;
        }
        
        .menu-icon .fa-bars {
            color: #fff;
            padding: 5px;
        }
        
        .example-menu{background-color: #333 !important;}
        
        
        
        .example-menu, .example-menu>ul div.active, .example-menu>ul ul.active {
            max-height: 6000px !important;
            height: auto;
            z-index: 999999990;
            position: relative;
        }
        
         .example-menu>ul div.sub-menu {
            overflow: hidden;
            max-height: 0;
            background-color: #f4f4f4;
        }
        
        .example-menu{
            clear: both;
            min-width: inherit;
            float: none;
        }
        
        .example-menu ul{display: inline;}
        .example-menu li, .example-menu>ul>li {display: block; float: left; width: 100%;}
        .example-menu li a {
            display: block;
            padding: .8em !important;
            border-bottom: 1px solid gray;
            position: relative;
            text-align: left !important;
            text-transform: uppercase;
        }
        
        .no-menu a, .menu-item a {
            font-size: 16px !important;
            color: #FFF;
            text-align: center;
            position: relative;
        }
        
        .menu-item a, .no-menu a {
            padding: 16px 13px;
        }
        
        .example-menu li.menu-item div.column>a:after,
        .example-menu li.menu-item>a:after,
        .example-menu li.topheading>a:after {
            content: '+';
            position: absolute;
            top: 0;
            right: 0;
            display: block;
            font-size: 1.5em;
            padding: .55em .5em;
        }
        
        .example-menu li.menu-item div.column>a.active:after,
        .example-menu li.menu-item >a.active:after,
        .example-menu li.topheading>a.active:after {
            content: "-";
            padding: .55em 15px;
        }
        
        .example-menu ul div.sub-menu, .example-menu ul ul, .example-menu ul ul ul {
            display: inherit;
            position: relative;
            left: auto;
            top: auto;
            border: none;
            width: 100%;
        }
        
        div.column {
            text-align: left;
            vertical-align: top;
            display: block;
            white-space: normal;
            width: 100%;
            border: none;
        }
        
        .stuff{padding:0.5rem; display:inline-block;}
        
        .form-horizontal .btn{width:125px; font-size: 0.8rem !important;}
        .column ul >li a {font-size: 0.9rem !important; text-transform: none;}
        
        .hdline-container{border-bottom:1px solid gray; width:100% !important;}
        .hdline-container .hdline{border-bottom: none;}
        
        .example-menu ul ul>li a {background-color: #e4e4e4;}
    }
    
.fa-plus-circle {color: #0C0;}
    .wider{width:280px;}
    #wrapper{min-height:900px;}
    
#wrapper .btn-sm {
    padding: 0.25rem 0.5rem !important;
    font-size: 0.875rem !important;
    line-height: 1.5 !important;
    border-radius: 0.2rem !important;
}    
</style>
<!doctype html>
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"> 
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<link rel="stylesheet" type="text/css" href="css/bootstrap-grid.css">
<script language="javascript" src="includes/general.js"></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onLoad="SetFocus();" >
<!-- header //-->
<?php
  require(DIR_WS_INCLUDES . 'template-top.php');
    
/*
        $insert_something = tep_db_query("INSERT INTO menu_links (category_id,category_name,parent_id,number_of_columns,sort_order,master_category_id) VALUES ('".$get_all_main_categories['categories_id']."','".$get_all_main_categories['name']."', '', '', '".$get_all_main_categories['sort_order']."','' )");   */  
    
?>
<!-- header_eof //-->


<!--- page 1 --->
     <div id="heading-block">
             <h1 class="pageHeading"><?php echo 'Edit Store Menu Navigation'; ?></h1>
    </div>
    <div class="form-group col-xs-12">
    Current Setup    
    </div>
    <div id="lower">
        <div class="mobile-menu-search">
            <a class="menu-link2 menu-icon">
                <i class="fa fa-bars" aria-hidden="true"></i>
            </a>
        </div>
    </div>
    
    <nav id="menu2" class="example-menu" style="background-color:rgba(58, 60, 65, 0.85);">
        <ul class="sub-ul">
    <?php $get_all_main_categories_query = tep_db_query("SELECT * FROM menu_links where main_category = '1' ORDER BY sort_order");
    while($get_all_main_categories = tep_db_fetch_array($get_all_main_categories_query)){
        $get_all_info_query = tep_db_query("select * from menu_links where category_id = '".$get_all_main_categories['category_id']."'" );
        $get_all_info = tep_db_fetch_array($get_all_info_query);
        if($get_all_info['featured_category'] == '1'){
            $yes_checked = true;
            $no_checked = false;
        } else {
            $yes_checked = false;
            $no_checked = true;
        }
        
        if($get_all_info['lessons_category'] == '1'){
            $les_yes_checked = true;
            $les_no_checked = false;
        } else {
            $les_yes_checked = false;
            $les_no_checked = true;
        }
        
        if($get_all_info['rental_category'] == '1'){
            $ren_yes_checked = true;
            $ren_no_checked = false;
        } else {
            $ren_yes_checked = false;
            $ren_no_checked = true;
        }
        
        if($get_all_main_categories['category_name'] == 'Water Wear'){
            $water_wear = ' water-wear';
        } else {
            $water_wear = '';
        }
        
        if($get_all_main_categories['main_category'] == '0'){
            $link = 'no-submenu';    
        } else {
            $link = 'menu-item';
        }
        
        echo '<li class="'.$link.$water_wear.'" >
        <span class="btn btn-primary btn-sm editPrimary'.$get_all_main_categories['category_id'].'" onclick="editMenuLink(\''.$get_all_main_categories['category_id'].'\')" style="padding:5px;">
            <i class="fa fa-pencil" style="margin:0px 2px;" id="edit_category"></i>
        </span>
        <span class="btn btn-primary btn-sm cancelPrimary'.$get_all_main_categories['category_id'].'" style="padding:5px 7px; display:none; background-color:#D9534F;" onclick="Close(\''.$get_all_main_categories['category_id'].'\')">
            <i class="fa fa-times"></i>
        </span>
        <a id="controller" style="display:inline-block;">'.$get_all_main_categories['category_name'].'</a>';
        
        if($get_all_info['number_of_columns'] == '1'){
            echo '<div class="m-dropdown sub-menu dropdown_1columns">
                <ul style="display:block; text-align:left; width:100%;">';
            $select_all_subcategories_query = tep_db_query("SELECT * from menu_links where parent_id = '".$get_all_main_categories['category_id']."' ORDER BY sort_order ASC");
            while($select_all_subcategories = tep_db_fetch_array($select_all_subcategories_query)){
                echo '<form id="former'.$select_all_subcategories['category_id'].'">
                <li>
                <span class="btn btn-primary btn-sm editSingleLink" style="padding:7px;">
                <i class="fa fa-pencil"></i>
                <i class="fa fa-times" style="display:none;"></i>
                </span>
                <a style="display:inline-block;">'.$select_all_subcategories['category_name'].'</a>
                <div style="display:none;" class="inputer">
                <div class="form-group" style="margin-top:10px; display:inline-block; width:100%;">
                    <input class="form-control" name="editLinksName" value="'.$select_all_subcategories['category_name'].'">
                </div>
                <span>
                    <label>Url:</label>
                    <input class="form-control" name="editUrl" value="'.$select_all_subcategories['url'].'"/>
                </span>
                <span class="form-group" style="margin-top:10px; display:inline-block;">
                <label>Sort Order</label>
                <input class="form-control" style="width:90px;" name="sort_order" value="'.$select_all_subcategories['sort_order'].'" />
                </span>
                <span class="btn btn-primary btn-sm" onclick="saveSingleLinks(\''.$select_all_subcategories['category_id'].'\');" style="padding:6px;">
                    <i class="fa fa-save"></i>
                </span>
                </div>
                <span class="btn btn-primary btn-sm deleteSingleLink" style="padding:7px; float:right; background-color:#D9534F; margin-top:6px;" onclick="deleteSingleLink(\''.$select_all_subcategories['category_id'].'\')">
                <i class="fa fa-trash"></i>
                </span>
                </li>
                </form>';
            }
            echo '</ul>
            <form class="single-links-form" style="display:table;" method="post">
                <input type="hidden" name="action" value="saveNewSingleLinks" />
                <input type="hidden" name="catID" value="'.$get_all_main_categories['category_id'].'" />
            </form>
            
            <a class="submit btn btn-primary btn-sm" style="margin-top:20px; width:80px; display:none;">Submit</a>
            <div class="column has-submenu col-sm-2 add-column">
                    <span class="form-horizontal form-group col-xs-12 addNewColumnBtn" style="width:140px;"><i class="fa fa-plus-circle"></i> Add Link</span>
                </div>
            </div>';
        }  else {
            
            $check_for_featured_query = tep_db_query("SELECT featured_category as value FROM menu_links WHERE category_id = '".$get_all_main_categories['category_id']."' ");
            $check_for_featured = tep_db_fetch_array($check_for_featured_query);
            
            if($check_for_featured['value'] == '1'){
                $num_columns = $get_all_info['number_of_columns'] + 1; 
            } else {
                $num_columns = $get_all_info['number_of_columns'];
            }
            
            if($num_columns == '5'){
                $columns = 'column-sm-2_4';
            } else {
                $num = 12/$num_columns;
                $columns = 'column-sm-'.$num;
            }
            
            echo '<div class="m-dropdown sub-menu dropdown_'.$num_columns.'columns">';
    	if($get_all_info['featured_category']== '1'){
        echo'<div class="featured column col">
                <a class="hdline">FEATURED</a>
                <ul style="display:block; overflow:inherit;">
                    <li><a href="http://localhost/store-exact/newproducts.php?cat=kite">New Products</a></li>
                    <li><a href="http://localhost/store-exact/sale.php?cat=kite">Sale</a></li>
                </ul>
            </div>';
        } 
            $select_all_subcategories_query = tep_db_query("SELECT * from categories c, categories_description cd where c.categories_id = cd.categories_id and c.parent_id = '".$get_all_main_categories['category_id']."' ORDER BY c.sort_order ASC");
            while($select_all_subcategories = tep_db_fetch_array($select_all_subcategories_query)){
                /* $add_subs = tep_db_query('INSERT INTO menu_links (category_id,category_name,parent_id,number_of_columns,sort_order,master_category_id) values ("'.$select_all_subcategories['categories_id'].'", "'.$select_all_subcategories['categories_name'].'", "'.$select_all_subcategories['parent_id'].'","","'.$select_all_subcategories['sort_order'].'","'.$select_all_subcategories['parent_id'].'") '); */
            }
            $select_all_subcategories_query = tep_db_query("SELECT * from menu_links where parent_id = '".$get_all_main_categories['category_id']."' ORDER BY sort_order ASC ");
            
                $counts = 0;
            while($select_all_subcategories = tep_db_fetch_array($select_all_subcategories_query)){
                echo'<div class="column has-submenu col">
                <form id="headlineForm'.$select_all_subcategories['category_id'].'">
                
                <div class="hdline-container" style="display:table; width: auto; position:relative">
                
                    <a class="hdline hdline'.$select_all_subcategories['category_id'].'" style="display:inline-block;" >'.$select_all_subcategories['category_name'].'</a>
                    <div class="extraStoof" id="inputHeadline'.$select_all_subcategories['category_id'].'" style="z-index:100; display:none; width:300px; padding:15px 0px;">
                    <input class="form-control form-group" name="inputHeadline" value="'.$select_all_subcategories['category_name'].'"/>
                    <label>Sort Order</label>
                    <input style="width:90px;" class="form-control" name="headlineSort" value="'.$select_all_subcategories['sort_order'].'"/>
                    </div>
                
                    <div class="stuff" style="vertical-align:top;">
                        <span style="padding:6px;" class="btn btn-primary btn-sm editHeadline">
                            <i class="fa fa-pencil"></i>
                            <i class="fa fa-times" style="display:none;"></i>    
                        </span>

                        <span style="padding:6px; display:none; margin-left:10px;" onclick="saveHeadline(\''.$select_all_subcategories['category_id'].'\')" class="btn btn-primary btn-sm save">
                            <i class="fa fa-save"></i>
                        </span>

                        <span class="btn btn-primary btn-sm" style="padding:6px; margin-left:10px;" onclick="deleteHeadline(\''.$select_all_subcategories['category_id'].'\')"><i class="fa fa-trash-o"></i></span>    
                    </div>
                
                </div>
                
                    <input type="hidden" name="headlineID" value="'.$select_all_subcategories['category_id'].'"/>
                    
                </form>
                
                <div class="form-horizontal form-group">
                    <a class="btn btn-primary btn-sm" style="text-align:left; background-color:#FFB848;" onclick="editLinks(\''.$select_all_subcategories['category_id'].'\')">Edit Links <i style="margin-left:5px;" class="fa fa-arrow-down"></i></a>
                </div>';
            $check_for_links_query = tep_db_query("SELECT * FROM menu_links WHERE parent_id = '".$select_all_subcategories['category_id']."'");
                
            if (tep_db_num_rows($check_for_links_query) > 0 ){
                
            } else {   
            echo'<form id="auto'.$select_all_subcategories['category_id'].'">    
                <div class="form-horizontal" style="margin-bottom:30px;">
                    <a class="btn btn-primary btn-sm" style="display:none" onclick="autoPopulate(\''.$select_all_subcategories['category_id'].'\')">Auto Populate</a>
                    <input type="hidden" name="action" value="autoPop"/>
                </div>
            </form>';
            }
                
                echo'<ul style="display:block;" class="list'.$select_all_subcategories['category_id'].'">';
                    /* $get_sub_headline_links_query = tep_db_query("SELECT * from menu_links where parent_id = '".$select_all_subcategories['id']."' ORDER BY  sort_order ASC"); */
                    
                    $add_sub_head_links_info_query1 = tep_db_query("SELECT * FROM menu_links WHERE parent_id = '".$select_all_subcategories['category_id']."' ORDER BY sort_order ASC");    
                
                    $add_sub_head_links_info_query = tep_db_query("SELECT * FROM categories c, categories_description cd WHERE c.categories_id = cd.categories_id and c.parent_id = '".$select_all_subcategories['category_id']."' ");
                if( tep_db_num_rows($add_sub_head_links_info_query1) > 0){
                    $query_this = $add_sub_head_links_info_query1;
                    while($add_sub_head_links_info = tep_db_fetch_array($query_this)){
                        echo '<li><a>'.$add_sub_head_links_info['category_name'] . '</a>
                            <input class="form-control form-group" style="display:none;" name="link'.$add_sub_head_links_info['categories_id'].'" value="'.$add_sub_head_links_info['category_name'].'"/> </li>'; 
                    }
                } else {
                    $query_this = $add_sub_head_links_info_query;
                   
                    while($add_sub_head_links_info = tep_db_fetch_array($query_this)){
                        echo '<li><a>'.$add_sub_head_links_info['categories_name'] . '</a>
                            <input class="form-control form-group" style="display:none;" name="link'.$add_sub_head_links_info['categories_id'].'" value="'.$add_sub_head_links_info['categories_name'].'"/> </li>'; 
                    }
                }
                
                if($counts == '0'){
                    $check_for_lessons_query = tep_db_query("SELECT * FROM menu_links WHERE category_id = '".$get_all_main_categories['category_id']."' AND lessons_category > '0'");

                    $lessons_query = tep_db_query("SELECT * FROM menu_links WHERE category_name = 'Lessons' and main_category = '".$get_all_main_categories['category_id']."'");
                    $lessons = tep_db_fetch_array($check_for_lessons_query);
                    if(tep_db_num_rows($check_for_lessons_query) > '0'){
                      echo '<li class="form-group">
                        <form method="post">
                        <h3 style="margin-bottom:5px;">Lessons
                          <span class="btn btn-primary btn-sm editLesson" data-id="'.$get_all_main_categories['category_id'].'">
                              <i class="fa fa-pencil" style="margin:0px 2px;"></i>
                              <i class="fa fa-times" style="display:none;"></i>
                          </span></h3>
                          <span style="display:none;" class="inputer">
                            <label style="display:block; margin-top:15px;">Url:</label>
                            <input style="width:200%;" class="form-control" name="lessons_input" value="'.$essons['url'].'"/>

                            <span class="btn btn-primary btn-sm saveLesson" data-id="'.$get_all_main_categories['category_id'].'" style="padding:6px; margin-top:10px;">
                                <i class="fa fa-save"></i>
                            </span>

                          </span>
                          </form>
                      </li>';
                    }

                    $check_for_rentals_query = tep_db_query("SELECT * FROM menu_links WHERE category_id = '".$get_all_main_categories['category_id']."' AND rental_category > '0'");

                    $rentals_query = tep_db_query("SELECT * FROM menu_links WHERE category_name = 'Rentals' and main_category = '".$get_all_main_categories['category_id']."'");
                    $rentals = tep_db_fetch_array($rentals_query);
                    if(tep_db_num_rows($check_for_rentals_query) > '0'){
                      echo '<li class="form-group">
                      <form method="post">
                        <h3 style="margin-bottom:5px;">Rentals
                          <span class="btn btn-primary btn-sm editRental" data-id="'.$get_all_main_categories['category_id'].'">
                              <i class="fa fa-pencil" style="margin:0px 2px;"></i>
                              <i class="fa fa-times" style="display:none;"></i>
                          </span></h3>
                          <span style="display:none;" class="inputer">
                            <label style="display:block; margin-top:15px;">Url:</label>
                            <input style="width:200%;" class="form-control" name="rentals_input" value="'.$rentals['url'].'"/>

                            <span class="btn btn-primary btn-sm saveRental" data-id="'.$get_all_main_categories['category_id'].'" style="padding:6px; margin-top:10px;">
                                <i class="fa fa-save"></i>
                            </span>
                          </span>
                          </form>
                      </li>';
                    }
                }
                
                echo '</ul>
                </div>';
                
                
                $counts++;
            }
            
            if($get_all_main_categories['number_of_columns'] > tep_db_num_rows($select_all_subcategories_query)){
                echo'<input type="hidden" class="input-class" value="'.$get_all_main_categories['number_of_columns'].'">
                <input type="hidden" class="mainCat-class" value="'.$get_all_main_categories['category_id'].'">
                <div class="column col add-column">
                    <span class="form-horizontal col-xs-12 addNewColumnBtn" style="margin-left:5px;"><i class="fa fa-plus-circle"></i> Add Column</span>
                </div>';
            }
        
        echo'</div>'; }
        echo '<form id="form'.$get_all_main_categories['category_id'].'" style="position:relative;">
            <div id="edit-stuff_'.$get_all_main_categories['category_id'].'" class="edit-stuff" style="padding:15px; text-align:left;">
            <div class="col-xs-12 form-group">
            <label>Menu Link Name</label>
            <input class="form-control" name="category_name" value="'.$get_all_main_categories['category_name'].'">
            </div>';
            
            if($get_all_main_categories['number_of_columns'] < 1){
                echo'<div class="col-xs-12 form-group">
            <label>Url</label>
            <input name="number_of_columns" class="form-control" value="'.$get_all_info['url'].'">
            </div>';
            }
            
            echo'<div class="col-xs-12 form-group">
            <label>Number of Columns</label>
            <input name="number_of_columns" class="form-control" style="width:60px;" value="'.$get_all_info['number_of_columns'].'">
            </div>';
            
            if($get_all_main_categories['number_of_columns'] > 0){
                echo'<div class="col-xs-12 form-group">
                <label>Featured Section</label>
                '.tep_draw_radio_field('featured_section', '1', $yes_checked).'Yes
                '.tep_draw_radio_field('featured_section', '0', $no_checked, 'style="margin-left:10px;"').'No
                </div>';
                
                echo'<hr>
                <div class="col-xs-12 form-group">
                <label><u>Lessons</u> Section</label>
                '.tep_draw_radio_field('lessons_section', '1', $les_yes_checked).'Yes
                '.tep_draw_radio_field('lessons_section', '0', $les_no_checked, 'style="margin-left:10px;"').'No
                </div>';
                
                echo'<hr>
                <div class="col-xs-12 form-group">
                <label><u>Rentals</u> Section</label>
                '.tep_draw_radio_field('rentals_section', '1', $ren_yes_checked).'Yes
                '.tep_draw_radio_field('rentals_section', '0', $ren_no_checked, 'style="margin-left:10px;"').'No
                </div>
                <hr>';
                
                
                
            }
            echo '<div class="col-xs-12 form-group">
            <label>Sort Order</label>
            <input class="form-control" name="sort_order" style="width:60px;" value="'.$get_all_info['sort_order'].'">
            </div>
            <input class="form-control" type="hidden" name="category_id" value="'.$get_all_main_categories['category_id'].'">
            <a class="btn btn-primary btn-sm form-group updateMainCategory" style="display:inline-block; margin-right:60%; margin-bottom:15px; float:left;"><i class="fa fa-save" style="margin-right:10px;"></i>Update</a>
            <a class="btn btn-primary btn-sm form-group deleteMainCategory" style="display:inline-block; float:left;" ><i class="fa fa-trash-o" style="margin-right:10px;"></i>Delete</a>
        
        </form>
    </li>';
    } ?>
            
            <li class="menu-item">
                <span class="btn btn-primary btn-sm"  onclick="addNewHeadline();" style="padding:5px;">
                    <i class="fa fa-plus" style="margin:0px 7px;" id="edit_category"></i>
                </span>
                <a id="controller" style="display: inline-block; padding:15px 4px;">Add</a>
                
                <?php  echo '<form id="form">
            <div id="edit-stuff" class="edit-stuff" style="padding:15px; right:0px; top:40px; text-align:left;">
            <div class="col-xs-12 form-group">
            <label>Menu Link Name</label><input name="category_name" value="'.$get_all_main_categories['category_name'].'">
            </div>
            
            <div class="col-xs-12 form-group">
            <label>Has Dropdown?</label>
            <input type="radio" name="main_category" value="0" onclick="noDropdown();"/>No
            <input type="radio" name="main_category" value="0" onclick="YesDropdown();" style="margin-left:10px;" />Yes
            </div>
            
            <div class="col-xs-12 form-group num_of_columns">
            <label>Number of Columns</label><input name="number_of_columns" value="'.$get_all_info['number_of_columns'].'" style="width:50px; margin-left:10px;">
            </div>
            
            <div class="col-xs-12 form-group featured_section">
            <label>Featured Section</label>
            '.tep_draw_radio_field('featured_section', '0', $no_checked).'No
            '.tep_draw_radio_field('featured_section', '1', $yes_checked, 'style="margin-left:10px;"').'Yes
            </div>
            
            <div class="col-xs-12 form-group">
            <label>Mobile Only</label>
            '.tep_draw_radio_field('mobile_only', '0', $no_checked).'No
            '.tep_draw_radio_field('mobile_only', '1', $yes_checked, 'style="margin-left:10px;"').'Yes
            </div>
            
            <div class="col-xs-12 form-group">
            <label>Sort Order</label><input name="sort_order"  value="'.$get_all_info['sort_order'].'" style="width:50px;">
            </div>
            <input type="hidden" name="action" value="add_main_link">
            <a class="btn btn-primary btn-sm form-group" style="display:inline-block; margin-right:60%; margin-bottom:15px; float:left;" onclick="SaveMainCategory()" >Update</a>
            <a style="margin-top:15px; float:left;" onclick="CloseThis()">Close</a>
        </form>'; ?>
            </li>
        </ul>
    </nav>
    
    <div id="edit-links-container" style="position:fixed; z-index: 3000000000; top: 10%; width:95%; background-color: #fff; left:1%; display: none; height:90%; overflow:scroll;"
        
    </div>
<?php
  
?>
<script>
    $("#controller").click(function(e) {
        $(this).toggleClass("active");
        e.preventDefault();
    })
function editMenuLink(cID){
    $("#edit-stuff_"+cID).toggleClass("edit-stuff-active");
    $(".editPrimary"+cID).hide();
    $(".cancelPrimary"+cID).show();
    
    }
    
function Close(cID){
    $("#edit-stuff_"+cID).removeClass("edit-stuff-active");
    $(".editPrimary"+cID).show();
    $(".cancelPrimary"+cID).hide();
}    

$(".editHeadline").on("click", function(){
    $(this).find('i').toggle();
    $(this).closest('form').find(".hdline").toggle();
    $(this).closest('form').find(".extraStoof").toggle();
    $(this).closest(".stuff").find(".save").toggle();   $(this).parent().parent().parent().parent(".column").toggleClass("auto");
    $(this).parent().parent().parent().parent().parent(".m-dropdown").toggleClass("hundo");
})

function editAddHeadline(element){
    $(element).find('i').toggle();
    $(element).closest('form').find(".hdline").toggle();
    $(element).closest('form').find(".extraStoof").toggle();
    $(element).closest(".stuff").find(".save").toggle();
    $(element).parent().parent().parent().parent(".column").toggleClass("auto");
    $(element).parent().parent().parent().parent().parent(".m-dropdown").toggleClass("hundo");
}
        
function closeHeadline(cID){
    $('#inputHeadline'+cID).hide();
    $(".edit"+cID).show();
    $(".cancel"+cID).hide();
    $(".save"+cID).hide();
}
    
function noDropdown(){
    $('.num_of_columns').hide();
    $('.featured_section').hide();  
}
    
function YesDropdown(){
    $('.num_of_columns').show();
    $('.featured_section').show();
}    
    
$(".updateMainCategory").on("click",function(){
    var cID = $(this).parent().find('[name="category_id"]').val();
    var $frm = $('#form'+cID);
    var input = $("<input>")
               .attr("type", "hidden")
               .attr("name", "action").val("edit_main_link");
    $frm.append(input);
    $frm.submit(); 
})
    
$(".deleteMainCategory").on("click",function(){
     var cID = $(this).parent().find('[name="category_id"]').val();
    
    var r = confirm("Are you sure you want to delete this link?");
    if (r == true) {
        var $frm = $('#form'+cID);
        var input = $("<input>")
               .attr("type", "hidden")
               .attr("name", "action").val("deleteSingleLink");
        $frm.append(input);
        $frm.submit();
    } else {
        
    }
})   
    
function SaveMainCategory(){
    var $frm = $('#form');
    $frm.submit();
    
}  
    
function saveHeadline(cID){
    var form = $('#headlineForm'+cID);
    var input = $("<input>")
               .attr("type", "hidden")
               .attr("name", "action").val("saveHeadline");
    form.append(input);
    form.submit();
}
    
function saveNewHeadline(cID){
    var form = $('#headlineForm'+cID);
    var input = $("<input>")
               .attr("type", "hidden")
               .attr("name", "action").val("saveHeadlineNew");
    form.append(input);
    form.submit();
}    
    
function deleteHeadline(cID){
    var r = confirm("Are you sure you want to delete this link?");
    if (r == true) {
        var form = $('#headlineForm'+cID);
        var input = $("<input>")
            .attr("type", "hidden")
            .attr("name", "action").val("deleteHeadline");
        form.append(input);
        form.submit();
    } else {
        
    }
}
    
function deleteAddHeadline(element){
    
    var value = $(element).parent().parent().find(".input-class").val();
    
    var r = confirm("Are you sure you want to delete this link?");
    if (r == true) {
        $(element).parent().parent().parent().parent(".column").remove();
        var total = $(element).parent().parent(".m-dropdown").find(".has-submenu").length;
                    
        if(value <= total){
            $(".add-column").hide();
        } else {
            $(".add-column").show();   
        }          
    } else {
        
    }
}
    
    
function editLinks(cID){
    var data = $("#headlineForm"+cID).serialize();
        $.ajax({
        type : 'POST',
        url  : 'edit-menu-links.php?subHeadlineID='+cID,
        data : data,
        success :  function(data) {
            $("#edit-links-container").html(data);
            $("#edit-links-container").show();
            var overlay = document.querySelector("body");
            overlay.classList.toggle('show-overlay');
	       }  
        });
}
    
$(".editSingleLink").on('click', function(){
    $(this).find('i').toggle();
    $(this).parent().find(".deleteSingleLink").toggle();
    var input = $(".inputer");
    $(this).closest('li').find(input).toggle();
   // $(".dropdown_1columns").toggleClass("wider");
}); 
    
$(".editLesson").on("click", function(){
    $(this).find('i').toggle();
    var input = $(".inputer");
    $(this).closest('li').find(input).toggle();
})
    
$(".editRental").on("click", function(){
    $(this).find('i').toggle();
    var input = $(".inputer");
    $(this).closest('li').find(input).toggle();
})
    
$(".saveLesson").on("click", function(){
    var cID = $(this).data("id");
    var form = $(this).closest('li').find('form');
    var input = '';
        input += '<input type="hidden" name="action" value="saveLesson"/>';
        input += "<input type='hidden' name='category_id' value='"+cID+"' />";
    form.append(input);
    form.submit();
})
    
$(".saveRental").on("click", function(){
    var cID = $(this).data("id");
    var form = $(this).closest('li').find('form');
    var input = '';
        input += '<input type="hidden" name="action" value="saveRental"/>';
        input += "<input type='hidden' name='category_id' value='"+cID+"' />";
    form.append(input);
    form.submit();
})    


    
function saveSingleLinks(cID){
    var $frm = $('#former'+cID);
    var input = '';
        input += '<input type="hidden" name="action" value="saveSingleLink"/>';
        input += "<input type='hidden' name='category_id' value='"+cID+"' />";
    $frm.append(input);
    $frm.submit();
}
    
function deleteSingleLink(cID){
    var r = confirm("Are you sure you want to delete this link?");
    if (r == true) { 
        var $frm = $('#former'+cID);
        var input = '';
            input += '<input type="hidden" name="action" value="deleteSingleLink"/>';
            input += "<input type='hidden' name='category_id' value='"+cID+"' />";
        $frm.append(input);
        $frm.submit();
    } else {
        
    }
       
}    
    
function autoPopulate(cID){
    var $frm = $('#auto'+cID);
    var input = $("<input>")
               .attr("type", "hidden")
               .attr("name", "category_id").val(cID);
    $frm.append(input);
    $frm.submit();
}   

    var counter = 0;
    
$('.addnewColumnBtn').on('click', function(){
    var parent_id = $(this).parent();
    var total = $(this).parent().parent(".m-dropdown").find(".has-submenu").length;
    var value = $(this).parent().parent().find(".input-class").val();
     var mainCiD = $(this).parent().parent().find(".mainCat-class").val();
    
    if(value > total){
    
 var stuff = '';
    stuff += '<div class="column has-submenu col">';
    stuff += "<form id='headlineForm"+counter+"'>";
    stuff += "<input type='hidden' name='masterCategory' value='"+mainCiD+"'>";
    stuff += '<div class="hdline-container" style="display:table; width: auto; position:relative">';    
    stuff += '<a class="hdline">New Headline</a>';
    stuff += "<div class='extraStoof' id='inputHeadline"+counter+"' style='z-index:100; display:none; width:300px; padding:15px 0px;'><input class='form-control form-group' name='inputHeadline' value='New Headline'><label>Sort Order</label><input style='width:90px;' class='form-control' name='headlineSort' value='0'></div>";
    stuff += "<div class='stuff' style='vertical-align:top;'>";
    stuff += "<span style='padding:6px;' class='btn btn-primary btn-sm' onclick='editAddHeadline(this)'><i class='fa fa-pencil'></i><i class='fa fa-times' style='display:none;'></i></span><span style='padding:6px; margin-left:15px; display:none;' onclick='saveNewHeadline("+counter+")' class='btn btn-primary btn-sm save'><i class='fa fa-save'></i></span><span class='btn btn-primary btn-sm' style='padding:6px; margin-left:15px;' onclick='deleteAddHeadline(this)''><i class='fa fa-trash-o'></i></span></div></div>";
    stuff += '</form>';
    stuff += '</div>';
 parent_id.before(stuff);
    
    counter++;
    }
    
    var total2 = $(this).parent().parent(".m-dropdown").find(".has-submenu").length;
    var value = $(this).parent().parent().find(".input-class").val();
    
    if (value <= total2) {
        $(".add-column").hide();
    }

})

 var counterer = '';   
$(".addNewColumnBtn").on("click", function(){
 //   $(this).parent(".column").hide();
    $(this).parent().siblings(".submit").show();
    var $this = $(this).parent().siblings(".single-links-form");
    
    var $html = '';
    $html += '<div class="col-xs-12" style="border-bottom:1px dashed; text-align:left;">';
    $html += '<div class="form-group" style="margin-top:10px; display:inline-block; width:100%;">';
    $html += '<input class="form-control" name="newSingleLinksName['+counterer+']" value="" placeholder="Link Title"></div>';
    $html += '<span><label>Url:</label><input class="form-control" name="newSingleUrl['+counterer+']" value=""/></span>';
    $html += '<span class="form-group" style="margin-top:10px; display:inline-block;">';
    $html += '<label>Sort Order</label><input class="form-control" style="width:90px;" name="newSingleSortOrder['+counterer+']" value="" /></span>';
    $html += '</div>';
    
    $this.prepend($html);
    
    counterer++;
    
}) 
   
    function addNewHeadline(){
        $('#edit-stuff').toggleClass("edit-stuff-active");
    }
    
    function CloseThis(){
        $('#edit-stuff').removeClass("edit-stuff-active"); 
    }
    
    $(".submit").on("click", function(){
        var $frm = $(this).siblings(".single-links-form");
        $frm.submit();
        
    })
    
    var $menuTrigger2 = $(".menu-item > a");

	$menuTrigger2.on("click", function() {
		var $this = $(this);
		$this.toggleClass('active').next('ul, div.sub-menu').toggleClass('active');
	});
        
    </script>
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</div>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
