<?php
/*
  $Id: order_total.php 1739 2007-12-20 00:52:16Z hpdl $

  Digistore v4.0,  Open Source E-Commerce Solutions
  http://www.digistore.co.nz

  Copyright (c) 2003 osCommerce, http://www.oscommerce.com

  Released under the GNU General Public License
*/

  class order_total2 {
    var $modules;

// class constructor
    function order_total2() {
      global $language;

      
        $this->modules = explode(';', MODULE_ORDER_TOTAL_INSTALLED);

        reset($this->modules);
        while (list(, $value) = each($this->modules)) {
          include(DIR_WS_LANGUAGES . $language . '/modules/order_total2/' . $value);
          include(DIR_WS_MODULES . 'order_total2/' . $value);

          $class = substr($value, 0, strrpos($value, '.'));
          $GLOBALS[$class] = new $class;
        }
      }
    }

    function process() {
      $order_total_array = array();
      if (is_array($this->modules)) {
        reset($this->modules);
        while (list(, $value) = each($this->modules)) {
          $class = substr($value, 0, strrpos($value, '.'));
          if ($GLOBALS[$class]->enabled) {
            $GLOBALS[$class]->output = array();
            $GLOBALS[$class]->process();

            for ($i=0, $n=sizeof($GLOBALS[$class]->output); $i<$n; $i++) {
              if (tep_not_null($GLOBALS[$class]->output[$i]['title']) && tep_not_null($GLOBALS[$class]->output[$i]['text'])) {
                $order_total_array[] = array('code' => $GLOBALS[$class]->code,
                                             'title' => $GLOBALS[$class]->output[$i]['title'],
                                             'text' => $GLOBALS[$class]->output[$i]['text'],
                                             'value' => $GLOBALS[$class]->output[$i]['value'],
                                             'sort_order' => $GLOBALS[$class]->sort_order);
              }
            }
          }
        }
      }

      return $order_total_array;
    }

    function output() {
      $output_string = '';
      if (is_array($this->modules)) {
        reset($this->modules);
        while (list(, $value) = each($this->modules)) {
          $class = substr($value, 0, strrpos($value, '.'));
          if ($GLOBALS[$class]->enabled) {
            $size = sizeof($GLOBALS[$class]->output);
            for ($i=0; $i<$size; $i++) {
              $output_string .= '              <tr>' . "\n" .
                                '                <td align="right" class="main">' . $GLOBALS[$class]->output[$i]['title'] . '</td>' . "\n" .
                                '                <td align="right" class="main">' . $GLOBALS[$class]->output[$i]['text'] . '</td>' . "\n" .
                                '              </tr>';
            }
          }
        }
      }

      return $output_string;
    }
  
?>