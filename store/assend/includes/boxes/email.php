<?php
/*
  $Id: taxes.php 1739 2007-12-20 00:52:16Z hpdl $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- taxes //-->
        
<?php
  $heading = array();
  $contents = array();

  $heading[] = array('text'  => BOX_HEADING_LOCATION_AND_TAXES,
                     'link'  => tep_href_link(FILENAME_COUNTRIES, 'selected_box=taxes'));
  /*This is for your subcategories. If you are installing a contribution and want the menu item to show up as a submenu in your navigationbar, just add your submenu link here. for example, if you install a contribution like header tags SEO that requires a new link in the header bar or your old column left, then you can add it in a existing box like "tools" and instead of adding links to the main links ($contents[]) below, just add them in $catalogSub1 or $catalogSub2 etc...

Then you need to add the variable to the main links ($contents[]) and its done like this:
Choose what link you want your submeny to appare after and add for example $catalogSub1. (note the dot after the variable) after any '</a>' . like this:

 '<a href="' . tep_href_link(FILENAME_PRODUCTS_EXPECTED, '', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_CATALOG_PRODUCTS_EXPECTED . '</a>' .$subcategory1. 
*/
  
  	/*subcategories start 
 	you can create as many subs you need, just copy the code below and change $catalogSub1 to $catalogSub2 etc...
	*/
	
  $taxesSub1= '</li><li><a class="menuBoxContentLink" href="#">'.BOX_HEADING_HEADER_TAGS_SEO.'</a>
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
	 
	 
  //if ($selected_box == 'taxes') {
	  
	  	  
	  // Add your old links here start
    $contents[] = array('text'  => '<a href="' . tep_href_link(FILENAME_MAIL, '', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_TOOLS_MAIL . '</a>' .
                                   '<a href="' . tep_href_link(FILENAME_NEWSLETTERS, '', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_TOOLS_NEWSLETTER_MANAGER . '</a>' .
                                   '<a href="' . tep_href_link(FILENAME_CONFIGURATION, 'gID=12', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_CONFIGURATION_EMAIL_OPTIONS . '</a>'.
                                   '<a href="' . tep_href_link('mailbeez.php', '', 'NONSSL') . '" class="menuBoxContentLink">Mail Beez</a>');
	
		  
	  // Add your old links here end
//  }

 // $box = new box;
 // echo $box->menuBox($heading, $contents);
  

 foreach($contents as $value) {	  
   echo '<li>'.$value['text'].'</li>';
}

//echo $contents[text];
?>
        
<!-- taxes_eof //-->
