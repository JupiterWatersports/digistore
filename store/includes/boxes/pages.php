<!-- pages //-->
       
<?php
  include_once('includes/application_top.php');

   $page_query = tep_db_query("select pd.pages_title, pd.pages_body, p.pages_id, p.pages_name, p.pages_image, p.pages_status, p.sort_order from " . TABLE_PAGES . " p, " . TABLE_PAGES_DESCRIPTION . " pd where p.pages_id = pd.pages_id and p.pages_status = '1' and pd.language_id = '" . (int)$languages_id . "' order by p.sort_order");

  $page_menu_text = '';
  while($page = tep_db_fetch_array($page_query)){
    if($page["pages_id"]!=1 && $page["pages_id"]!=2)
      $page_menu_text .= '<div class="info-links"><a href="' . tep_href_link(FILENAME_PAGES, 'page='.$page["pages_name"]) . '">' . $page["pages_title"] . '</a></div>';
  }


  $info_contents = array(); {
 echo  '<div id="sidelinks" class="col-xs-12 col-sm-3">'
 .'<div class="account-heading">Info Pages</div>'
 . $page_menu_text
 .'</div>';                          
  }
  
?>
       
<!-- pages_eof //-->