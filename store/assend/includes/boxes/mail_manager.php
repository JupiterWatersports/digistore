<?php
/*
  $Id: modules.php 1739 2007-12-20 00:52:16Z hpdl $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- mail manager //-->
        
<?php
  $heading = array();
  $contents = array();

  $heading[] = array('text'  => BOX_HEADING_MAIL_MANAGER,
                     'link'  => tep_href_link(FILENAME_MM_RESPONSEMAIL, 'selected_box=mailmanager'));
  /*This is for your subcategories. If you are installing a contribution and want the menu item to show up as a submenu in your navigationbar, just add your submenu link here. for example, if you install a contribution like header tags SEO that requires a new link in the header bar or your old column left, then you can add it in a existing box like "tools" and instead of adding links to the main links ($contents[]) below, just add them in $catalogSub1 or $catalogSub2 etc...

Then you need to add the variable to the main links ($contents[]) and its done like this:
Choose what link you want your submeny to appare after and add for example $catalogSub1. (note the dot after the variable) after any '</a>' . like this:

 '<a href="' . tep_href_link(FILENAME_PRODUCTS_EXPECTED, '', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_CATALOG_PRODUCTS_EXPECTED . '</a>' .$subcategory1. 
*/
  
  	/*subcategories start 
 	you can create as many subs you need, just copy the code below and change $catalogSub1 to $catalogSub2 etc...*/
  $modulesSub1= '</li><li><a class="menuBoxContentLink" href="#">'.BOX_HEADING_HEADER_TAGS_SEO.'</a>
		<ul>
    		<li><a href="' . tep_href_link(FILENAME_HEADER_TAGS_SEO, '', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_HEADER_TAGS_ADD_A_PAGE . '</a>				</li>
    		<li><a href="' . tep_href_link(FILENAME_HEADER_TAGS_FILL_TAGS, '', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_HEADER_TAGS_FILL_TAGS . '</a></li>
    		<li><a href="' . tep_href_link(FILENAME_HEADER_TAGS_TEST, '', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_HEADER_TAGS_TEST . '</a></li>
    	
		</ul>
    </li><li>';
	/*	if you want to create deeper submenus just add the code below after any </li> above and you will have a sub menu in your submenu
	
	<li><a class="menuBoxContentLink" href="#">deeper category</a>
		<ul>
    		<li><a href="#" class="menuBoxContentLink">deep sub1</a>				</li>
		</ul>
    </li>
	 subcategories end*/
	 
 // if ($selected_box == 'modules') {
	 

   $mmlinks = '</li><li><a class="menuBoxContentLink" href="#">'.BOX_HEADING_MAIL_MANAGER.'</a><ul>'; 
	  // Add your old links here start
    $contents[] = array('text'  =>  '<a href="' . tep_href_link(FILENAME_MM_RESPONSEMAIL) . '" class="menuBoxContentLink">' . BOX_MM_RESPONSEMAIL . '</a>' .
                                   '<a href="' . tep_href_link(FILENAME_MM_BULKMAIL) . '" class="menuBoxContentLink">' . BOX_MM_BULKMAIL . '</a>' .
                                   '<a href="' . tep_href_link(FILENAME_MM_TEMPLATES) . '" class="menuBoxContentLink">' . BOX_MM_TEMPLATES . '</a>' .
                                   '<a href="' . tep_href_link(FILENAME_MM_EMAIL) . '" class="menuBoxContentLink">' . BOX_MM_EMAIL . '</a>');
	
		  
	  // Add your old links here end
   // }

  //$box = new box;
 // echo $box->menuBox($heading, $contents);
   foreach($contents as $value) {	  
   $mmlinks .='<li>'.$value['text'].'</li>';
}
  $mmlinks .= '</ul></li><li>';
?>
<!-- mail manage eof //-->
