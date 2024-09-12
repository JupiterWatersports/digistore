<?php
/*
  $Id: boxes.php 1739 2007-12-20 00:52:16Z hpdl $
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Copyright (c) 2003 osCommerce
  Released under the GNU General Public License
*/

///// BOF CSS TABLEBOXES
// csstableBox builds the infoboxes
class csstableBox {
 
// class constructor
    function csstableBox($contents, $direct_output = false) {
      $tableBox_string = '<div ';
      if (tep_not_null($this->table_parameters)) $tableBox_string .= ' ' . $this->table_parameters;
      $tableBox_string .= '>' . "\n";
     
      for ($i=0, $n=sizeof($contents); $i<$n; $i++) {
        if (isset($contents[$i]['form']) && tep_not_null($contents[$i]['form'])) $tableBox_string .= $contents[$i]['form'] . "\n";
     
        if (isset($contents[$i][0]) && is_array($contents[$i][0])) {
          for ($x=0, $n2=sizeof($contents[$i]); $x<$n2; $x++) {
            if (isset($contents[$i][$x]['text']) && tep_not_null($contents[$i][$x]['text'])) {
           
              if (isset($contents[$i][$x]['id']) && tep_not_null($contents[$i][$x]['id'])) $tableBox_string .= ' id="' . tep_output_string($contents[$i][$x]['id']) . '"';
              if (isset($contents[$i][$x]['params']) && tep_not_null($contents[$i][$x]['params'])) {
                $tableBox_string .= ' ' . $contents[$i][$x]['params'];
              } elseif (tep_not_null($this->table_data_parameters)) {
                $tableBox_string .= ' ' . $this->table_data_parameters; 
              }
          
              if (isset($contents[$i][$x]['form']) && tep_not_null($contents[$i][$x]['form'])) $tableBox_string .= $contents[$i][$x]['form'];
              $tableBox_string .= $contents[$i][$x]['text'];
              if (isset($contents[$i][$x]['form']) && tep_not_null($contents[$i][$x]['form'])) $tableBox_string .= '</form>';
          //    $tableBox_string .= '</div>' . "\n"; 
            }
          }
        }
          $tableBox_string .= '' . $contents[$i]['text'] . "\n";  
       	if (isset($contents[$i]['form']) && tep_not_null($contents[$i]['form'])) $tableBox_string .= '</form>' . "\n";
      	}
		

      	if ($direct_output == true) echo $tableBox_string;

      	return $tableBox_string;
   	 }
  	}
  	
 
  class cssinfoBox extends csstableBox {

    function cssinfoBox($contents) {
      $info_box_contents = array();
      $info_box_contents[] = array('text' => $this->cssinfoBoxContents($contents).'</div></div></div>');
      $this->table_parameters = 'class="infobox"';
      $this->csstableBox($info_box_contents, true);
    }

    function cssinfoBoxContents($contents) {     
     $this->table_parameters = 'class="infoboxcontents"';
      $info_box_contents = array();
    
      for ($i=0, $n=sizeof($contents); $i<$n; $i++) {
        $info_box_contents[] = array(array('form' => (isset($contents[$i]['form']) ? $contents[$i]['form'] : ''),
                                       
                                           'text' => (isset($contents[$i]['text']) ? $contents[$i]['text']: '')));
      		
      }
      return $this->csstableBox($info_box_contents);
    }
  }

  class cssinfoBoxHeading extends csstableBox {
    function cssinfoBoxHeading($contents,  $left_corner = true, $right_corner = true,  $right_arrow = false) {
      $this->table_parameters = 'class="grid_2 alpha" '; 
      if ($right_arrow == true) {

        $right_arrow = '';
      } else {
        $right_arrow = '';
      }
     
     $info_box_contents = array();
     
     $info_box_contents[] = array(array('text' => '<div class="infobox_heading">'.$contents[0]['text'].$right_arrow.'</div>'));

      $this->csstableBox($info_box_contents, true);
    }
  }
 
///// EOF CSS TABLEBOXES

///// BOF PLIST TABLEBOXES

// plisttableBox builds listings in includes/modules/product_listing.php and table in shopping_cart.php.

class plisttableBox {
    
    var $table_parameters = '';
    var $table_row_parameters = '';
    var $table_data_parameters = '';
    
// class constructor
    function plisttableBox($contents, $direct_output = false) {
  
      for ($i=0, $n=sizeof($contents); $i<$n; $i++) {
        if (isset($contents[$i]['form']) && tep_not_null($contents[$i]['form'])) $tableBox_string .= $contents[$i]['form'] . "\n";
        $tableBox_string .= '<div ';   
        if (tep_not_null($this->table_row_parameters)) $tableBox_string .= ' ' . $this->table_row_parameters;
        if (isset($contents[$i]['params']) && tep_not_null($contents[$i]['params'])) $tableBox_string .= ' ' . $contents[$i]['params'];
        $tableBox_string .= '>' . "\n";

        if (isset($contents[$i][0]) && is_array($contents[$i][0])) {
          for ($x=0, $n2=sizeof($contents[$i]); $x<$n2; $x++) {
            if (isset($contents[$i][$x]['text']) && tep_not_null($contents[$i][$x]['text'])) {
              $tableBox_string .= '    <div';  
              if (isset($contents[$i][$x]['class']) && tep_not_null($contents[$i][$x]['class'])) $tableBox_string .= ' class="' . tep_output_string($contents[$i][$x]['class']) . '"';
              if (isset($contents[$i][$x]['params']) && tep_not_null($contents[$i][$x]['params'])) {
                $tableBox_string .= ' ' . $contents[$i][$x]['params'];
              } elseif (tep_not_null($this->table_data_parameters)) {
                $tableBox_string .= ' ' . $this->table_data_parameters;
              }
              $tableBox_string .= '>';
              if (isset($contents[$i][$x]['form']) && tep_not_null($contents[$i][$x]['form'])) $tableBox_string .= $contents[$i][$x]['form'];
              $tableBox_string .= $contents[$i][$x]['text'];
              if (isset($contents[$i][$x]['form']) && tep_not_null($contents[$i][$x]['form'])) $tableBox_string .= '</form>';
              $tableBox_string .= '</div>' . "\n"; 
            }
          }
        } else {
      
          if (isset($contents[$i]['class']) && tep_not_null($contents[$i]['class'])) $tableBox_string .= ' class="' . tep_output_string($contents[$i]['class']) . '"';
          if (isset($contents[$i]['params']) && tep_not_null($contents[$i]['params'])) {
            $tableBox_string .= ' ' . $contents[$i]['params'];
          } elseif (tep_not_null($this->table_data_parameters)) {
            $tableBox_string .= ' ' . $this->table_data_parameters;
          }
          $tableBox_string .= '' . $contents[$i]['text'] . "\n";  
        }

      $tableBox_string .= '<div class="divider"></div> </div>' . "\n";  
        if (isset($contents[$i]['form']) && tep_not_null($contents[$i]['form'])) $tableBox_string .= '</form>' . "\n";
      }

      if ($direct_output == true) echo $tableBox_string;
      return $tableBox_string;
    }
  }

class plistBox extends plisttableBox {
    function plistBox($contents) {
      $this->plisttableBox($contents, true);
    }
  }
  
class plistcontentBox extends plisttableBox {
    function plistcontentBox($contents) {
      $info_box_contents = array();
      $info_box_contents[] = array('text' => $this->plistcontentBoxContents($contents));
      $this->plisttableBox($info_box_contents, true);
    }

    function plistcontentBoxContents($contents) {
      $this->table_parameters = 'class="productlisting-new-contents"';
      return $this->plisttableBox($contents);
    }
  }

  class plistcontentBoxHeading extends plisttableBox {
    function plistcontentBoxHeading($contents) {
      $info_box_contents = array();
      $info_box_contents[] = array(array('params' => ' class="pl-heading"',
                                         'text' => $contents[0]['text']));

      $this->plisttableBox($info_box_contents, true);
    }
  }
  
  class plistListingBox extends plisttableBox {
    function plistListingBox($contents) {
      $this->plisttableBox($contents, true);
    }
  }
////// EOF PLIST TABLEBOXES


///// BOF PL TABLEBOXES

/* pltableBox builds the product listing in:  
       includes/modules/xsell_products.php 
       includes/modules/new_products.php
       includes/modules/also_purchased_products.php
       
*/
class pltableBox {
    
    var $table_parameters = '';
    var $table_row_parameters = '';
    var $table_data_parameters = '';
    
// class constructor
    function pltableBox($contents, $direct_output = false) {
  
      for ($i=0, $n=sizeof($contents); $i<$n; $i++) {
        if (isset($contents[$i]['form']) && tep_not_null($contents[$i]['form'])) $tableBox_string .= $contents[$i]['form'] . "\n";
    
        if (tep_not_null($this->table_row_parameters)) $tableBox_string .= ' ' . $this->table_row_parameters;
        if (isset($contents[$i]['params']) && tep_not_null($contents[$i]['params'])) $tableBox_string .= ' ' . $contents[$i]['params'];
  
        if (isset($contents[$i][0]) && is_array($contents[$i][0])) {
          for ($x=0, $n2=sizeof($contents[$i]); $x<$n2; $x++) {
            if (isset($contents[$i][$x]['text']) && tep_not_null($contents[$i][$x]['text'])) {
              $tableBox_string .= '    <div'; 
              if (isset($contents[$i][$x]['id']) && tep_not_null($contents[$i][$x]['id'])) $tableBox_string .= ' id="' . tep_output_string($contents[$i][$x]['id']) . '"';
              if (isset($contents[$i][$x]['params']) && tep_not_null($contents[$i][$x]['params'])) {
                $tableBox_string .= ' ' . $contents[$i][$x]['params'];
              } elseif (tep_not_null($this->table_data_parameters)) {
                $tableBox_string .= ' ' . $this->table_data_parameters;
              }
              $tableBox_string .= '>';
              if (isset($contents[$i][$x]['form']) && tep_not_null($contents[$i][$x]['form'])) $tableBox_string .= $contents[$i][$x]['form'];
              $tableBox_string .= $contents[$i][$x]['text'];
              if (isset($contents[$i][$x]['form']) && tep_not_null($contents[$i][$x]['form'])) $tableBox_string .= '</form>';
              $tableBox_string .= '</div>' . "\n";  
            }
          }
        } else {
      
          if (isset($contents[$i]['id']) && tep_not_null($contents[$i]['id'])) $tableBox_string .= ' id="' . tep_output_string($contents[$i]['id']) . '"';
          if (isset($contents[$i]['params']) && tep_not_null($contents[$i]['params'])) {
            $tableBox_string .= ' ' . $contents[$i]['params'];
          } elseif (tep_not_null($this->table_data_parameters)) {
            $tableBox_string .= ' ' . $this->table_data_parameters;
          }
          $tableBox_string .= '' . $contents[$i]['text'] . "\n";  
        }

  
        if (isset($contents[$i]['form']) && tep_not_null($contents[$i]['form'])) $tableBox_string .= '</form>' . "\n";
      }


      if ($direct_output == true) echo $tableBox_string;

      return $tableBox_string;
    }
  }

class plBox extends pltableBox {
    function plBox($contents) {
      $this->pltableBox($contents, true);
    }
  }
  
class plcontentBox extends pltableBox {
    function plcontentBox($contents) {
      $info_box_contents = array();
      $info_box_contents[] = array('text' => $this->plcontentBoxContents($contents));
      $this->pltableBox($info_box_contents, true);
    }

    function plcontentBoxContents($contents) {
      $this->table_parameters = 'class="productlisting-new-contents"';
      return $this->pltableBox($contents);
    }
  }

  class plcontentBoxHeading extends pltableBox {
    function plcontentBoxHeading($contents) {
      $info_box_contents = array();
      $info_box_contents[] = array(array('params' => ' class="pl-heading"',
                                         'text' => $contents[0]['text']));

      $this->pltableBox($info_box_contents, true);
    }
  }
  
  class plListingBox extends pltableBox {
    function plListingBox($contents) {
      $this->pltableBox($contents, true);
    }
  }
////// EOF PL TABLEBOXES



///// BOF ORIGINAL TABLEBOXES
 // builds includes/modules/articles_xsell, includes/modules/articles_pxsell  
 
 class tableBox {
    var $table_border = '0';
    var $table_width = '100%';
    var $table_cellspacing = '0';
    var $table_cellpadding = '2';
    var $table_parameters = '';
    var $table_row_parameters = '';
    var $table_data_parameters = '';

// class constructor
    function tableBox($contents, $direct_output = false) {
     // $tableBox_string = '<table border="' . tep_output_string($this->table_border) . '" width="' . tep_output_string($this->table_width) . '" cellspacing="' . tep_output_string($this->table_cellspacing) . '" cellpadding="' . tep_output_string($this->table_cellpadding) . '"';
      $tableBox_string = '<table';    
      if (tep_not_null($this->table_parameters)) $tableBox_string .= ' ' . $this->table_parameters;
      $tableBox_string .= '>' . "\n";

      for ($i=0, $n=sizeof($contents); $i<$n; $i++) {
        if (isset($contents[$i]['form']) && tep_not_null($contents[$i]['form'])) $tableBox_string .= $contents[$i]['form'] . "\n";
        $tableBox_string .= '  <tr';
        if (tep_not_null($this->table_row_parameters)) $tableBox_string .= ' ' . $this->table_row_parameters;
        if (isset($contents[$i]['params']) && tep_not_null($contents[$i]['params'])) $tableBox_string .= ' ' . $contents[$i]['params'];
        $tableBox_string .= '>' . "\n";

        if (isset($contents[$i][0]) && is_array($contents[$i][0])) {
          for ($x=0, $n2=sizeof($contents[$i]); $x<$n2; $x++) {
            if (isset($contents[$i][$x]['text']) && tep_not_null($contents[$i][$x]['text'])) {
              $tableBox_string .= '    <td ';
              if (isset($contents[$i][$x]['align']) && tep_not_null($contents[$i][$x]['align'])) $tableBox_string .= ' align="' . tep_output_string($contents[$i][$x]['align']) . '"';
              if (isset($contents[$i][$x]['params']) && tep_not_null($contents[$i][$x]['params'])) {
                $tableBox_string .= ' ' . $contents[$i][$x]['params'];
              } elseif (tep_not_null($this->table_data_parameters)) {
                $tableBox_string .= ' ' . $this->table_data_parameters;
              }
              $tableBox_string .= '>';
              if (isset($contents[$i][$x]['form']) && tep_not_null($contents[$i][$x]['form'])) $tableBox_string .= $contents[$i][$x]['form'];
              $tableBox_string .= $contents[$i][$x]['text'];
              if (isset($contents[$i][$x]['form']) && tep_not_null($contents[$i][$x]['form'])) $tableBox_string .= '</form>';
              $tableBox_string .= '</td>' . "\n";
            }
          }
        } else {
          $tableBox_string .= '    <td';
          if (isset($contents[$i]['align']) && tep_not_null($contents[$i]['align'])) $tableBox_string .= ' align="' . tep_output_string($contents[$i]['align']) . '"';
          if (isset($contents[$i]['params']) && tep_not_null($contents[$i]['params'])) {
            $tableBox_string .= ' ' . $contents[$i]['params'];
          } elseif (tep_not_null($this->table_data_parameters)) {
            $tableBox_string .= ' ' . $this->table_data_parameters;
          }
          $tableBox_string .= '>' . $contents[$i]['text'] . '</td>' . "\n";
        }

        $tableBox_string .= '  </tr>' . "\n";
        if (isset($contents[$i]['form']) && tep_not_null($contents[$i]['form'])) $tableBox_string .= '</form>' . "\n";
      }

      $tableBox_string .= '</table>' . "\n";

      if ($direct_output == true) echo $tableBox_string;

      return $tableBox_string;
    }
  }



  class infoBox extends tableBox {
    function infoBox($contents) {
      $info_box_contents = array();
      $info_box_contents[] = array('text' => $this->infoBoxContents($contents));
      $this->table_cellpadding = '1';
      $this->table_parameters = 'class="infoBox"';
      $this->tableBox($info_box_contents, true);
    }

    function infoBoxContents($contents) {
      $this->table_cellpadding = '3';
      $this->table_parameters = 'class="infoBoxContents"';
      $info_box_contents = array();
      $info_box_contents[] = array(array('text' => tep_draw_separator('pixel_trans.gif', '100%', '1')));

      for ($i=0, $n=sizeof($contents); $i<$n; $i++) {
        $info_box_contents[] = array(array('align' => (isset($contents[$i]['align']) ? $contents[$i]['align'] : ''),
                                           'form' => (isset($contents[$i]['form']) ? $contents[$i]['form'] : ''),
                                           'params' => 'class="boxText"',
                                           'text' => (isset($contents[$i]['text']) ? $contents[$i]['text'] : '')));
      }
      $info_box_contents[] = array(array('text' => tep_draw_separator('pixel_trans.gif', '100%', '1')));
      return $this->tableBox($info_box_contents);
    }
  }

  class infoBoxHeading extends tableBox {
    function infoBoxHeading($contents, $left_corner = true, $right_corner = true, $right_arrow = false) {
      $this->table_cellpadding = '0';

      if ($left_corner == true) {
        $left_corner = tep_image(DIR_WS_IMAGES . 'infobox/corner_left.gif');
      } else {
        $left_corner = tep_image(DIR_WS_IMAGES . 'infobox/corner_right_left.gif');
      }
      if ($right_arrow == true) {
        $right_arrow = '<a href="' . $right_arrow . '">' . tep_image(DIR_WS_IMAGES . 'infobox/arrow_right.gif', ICON_ARROW_RIGHT) . '</a>';
      } else {
        $right_arrow = '';
      }
      if ($right_corner == true) {
        $right_corner = $right_arrow . tep_image(DIR_WS_IMAGES . 'infobox/corner_right.gif');
      } else {
        $right_corner = $right_arrow . tep_draw_separator('pixel_trans.gif', '11', '14');
      }

      $info_box_contents = array();
      $info_box_contents[] = array(array('params' => 'height="14" class="infoBoxHeading"',
                                         'text' => $left_corner),
                                   array('params' => 'width="100%" height="14" class="infoBoxHeading"',
                                         'text' => $contents[0]['text']),
                                   array('params' => 'height="14" class="infoBoxHeading" nowrap',
                                         'text' => $right_corner));

      $this->tableBox($info_box_contents, true);
    }
  }

  class contentBox extends tableBox {
    function contentBox($contents) {
      $info_box_contents = array();
      $info_box_contents[] = array('text' => $this->contentBoxContents($contents));
      $this->table_cellpadding = '1';
      $this->table_parameters = 'class="infoBox"';
      $this->tableBox($info_box_contents, true);
    }

    function contentBoxContents($contents) {
      $this->table_cellpadding = '4';
      $this->table_parameters = 'class="infoBoxContents"';
      return $this->tableBox($contents);
    }
  }

  class contentBoxHeading extends tableBox {
    function contentBoxHeading($contents) {
      $this->table_width = '100%';
      $this->table_cellpadding = '0';

      $info_box_contents = array();
      $info_box_contents[] = array(array('params' => 'height="14" class="infoBoxHeading"',
                                         'text' => tep_image(DIR_WS_IMAGES . 'infobox/corner_left.gif')),
                                   array('params' => 'height="14" class="infoBoxHeading" width="100%"',
                                         'text' => $contents[0]['text']),
                                   array('params' => 'height="14" class="infoBoxHeading"',
                                         'text' => tep_image(DIR_WS_IMAGES . 'infobox/corner_right_left.gif')));

      $this->tableBox($info_box_contents, true);
    }
  }

  class errorBox extends tableBox {
    function errorBox($contents) {
      $this->table_data_parameters = 'class="errorBox"';
      $this->tableBox($contents, true);
    }
  }

  class productListingBox extends tableBox {
    function productListingBox($contents) {
      $this->table_parameters = 'class="productListing"';
      $this->tableBox($contents, true);
    }
  }
  
  // plisttableBox builds table in shopping_cart.php.

class carttableBox {
    
    var $table_parameters = '';
    var $table_row_parameters = '';
    var $table_data_parameters = '';
    
// class constructor
    function carttableBox($contents, $direct_output = false) {
  
      for ($i=0, $n=sizeof($contents); $i<$n; $i++) {
        if (isset($contents[$i]['form']) && tep_not_null($contents[$i]['form'])) $tableBox_string .= $contents[$i]['form'] . "\n";
        $tableBox_string .= '';   
        if (tep_not_null($this->table_row_parameters)) $tableBox_string .= ' ' . $this->table_row_parameters;
        if (isset($contents[$i]['params']) && tep_not_null($contents[$i]['params'])) $tableBox_string .= ' ' . $contents[$i]['params'];
        $tableBox_string .= '' . "\n";

        if (isset($contents[$i][0]) && is_array($contents[$i][0])) {
          for ($x=0, $n2=sizeof($contents[$i]); $x<$n2; $x++) {
            if (isset($contents[$i][$x]['text']) && tep_not_null($contents[$i][$x]['text'])) {
              $tableBox_string .= '    <div';  
              if (isset($contents[$i][$x]['class']) && tep_not_null($contents[$i][$x]['class'])) $tableBox_string .= ' class="' . tep_output_string($contents[$i][$x]['class']) . '"';
              if (isset($contents[$i][$x]['params']) && tep_not_null($contents[$i][$x]['params'])) {
                $tableBox_string .= ' ' . $contents[$i][$x]['params'];
              } elseif (tep_not_null($this->table_data_parameters)) {
                $tableBox_string .= ' ' . $this->table_data_parameters;
              }
              $tableBox_string .= '>';
              if (isset($contents[$i][$x]['form']) && tep_not_null($contents[$i][$x]['form'])) $tableBox_string .= $contents[$i][$x]['form'];
              $tableBox_string .= $contents[$i][$x]['text'];
              if (isset($contents[$i][$x]['form']) && tep_not_null($contents[$i][$x]['form'])) $tableBox_string .= '</form>';
              $tableBox_string .= '</div>' . "\n"; 
            }
          }
        } else {
      
          if (isset($contents[$i]['class']) && tep_not_null($contents[$i]['class'])) $tableBox_string .= ' class="' . tep_output_string($contents[$i]['class']) . '"';
          if (isset($contents[$i]['params']) && tep_not_null($contents[$i]['params'])) {
            $tableBox_string .= ' ' . $contents[$i]['params'];
          } elseif (tep_not_null($this->table_data_parameters)) {
            $tableBox_string .= ' ' . $this->table_data_parameters;
          }
         
        }

      $tableBox_string .= '' . "\n";  
        if (isset($contents[$i]['form']) && tep_not_null($contents[$i]['form'])) $tableBox_string .= '</form>' . "\n";
      }

      if ($direct_output == true) echo $tableBox_string;
      return $tableBox_string;
    }
  }


////// EOF Cart TABLEBOXES

// added new class for shopping cart  
  class shoppingcartTable extends  carttableBox {
    function shoppingcartTable($contents) {
      $this->table_parameters = 'class="shoppingcart"';
      $this-> carttableBox($contents, true);
    }
  }
  
  // added new class for shopping cart  
  class minicartTable extends  carttableBox {
    function minicartTable($contents) {
      $this->table_parameters = 'class="shoppingcart"';
      $this-> carttableBox($contents, true);
    }
  }
 ///// EOF ORIGINAL TABLEBOXES
 
 // Start Products Specifications
  class borderlessBox extends tableBox {
    function borderlessBox ($contents) {
      $this->table_parameters = 'class="main"';
      $this->tableBox ($contents, true);
    }
  }
// End Products Specifications
?>