<?php
/*
  $Id: split_page_results.php 1739 2007-12-20 00:52:16Z hpdl $

  Digistore v4.0,  Open Source E-Commerce Solutions
  http://www.digistore.co.nz

  Copyright (c) 2003 osCommerce, http://www.oscommerce.com

  Released under the GNU General Public License
*/

  class splitPageResultsPagin {
    var $sql_query, $number_of_rows, $current_page_number, $number_of_pages, $number_of_rows_per_page, $page_name;

/* class constructor */
    public function __construct($query, $max_rows, $count_key = '*', $page_holder = 'page') {
      global $_GET, $_POST;

      $this->sql_query = $query;
      $this->page_name = 'page';	

      $page = $_GET[$this->page_name] ?? $_POST[$this->page_name] ?? '';

      if (empty($page) || !is_numeric($page)) $page = 1;
      $this->current_page_number = $page;

      $this->number_of_rows_per_page = $max_rows;

      $pos_to = strlen($this->sql_query);
      $pos_from = strpos($this->sql_query, ' from', 0);

      $pos_group_by = strpos($this->sql_query, ' group by', $pos_from);
      if (($pos_group_by < $pos_to) && ($pos_group_by != false)) $pos_to = $pos_group_by;

      $pos_having = strpos($this->sql_query, ' having', $pos_from);
      if (($pos_having < $pos_to) && ($pos_having != false)) $pos_to = $pos_having;

      $pos_order_by = strpos($this->sql_query, ' order by', $pos_from);
      if (($pos_order_by < $pos_to) && ($pos_order_by != false)) $pos_to = $pos_order_by;

      if (strpos($this->sql_query, 'distinct') || strpos($this->sql_query, 'group by')) {
        $count_string = 'distinct ' . tep_db_input($count_key);
      } else {
        $count_string = tep_db_input($count_key);
      }

      $count_query = tep_db_query("select count(".$count_string.") as total " . substr($this->sql_query, $pos_from, ($pos_to - $pos_from)));
      $count = tep_db_fetch_array($count_query);

      $this->number_of_rows = $count['total'];

      $this->number_of_pages = ceil($this->number_of_rows / $this->number_of_rows_per_page);

      if ($this->current_page_number > $this->number_of_pages) {
        $this->current_page_number = $this->number_of_pages;
      }

      $offset = ($this->number_of_rows_per_page * ($this->current_page_number - 1));

      $this->sql_query .= " LIMIT " . max($offset, 0) . ", " . $this->number_of_rows_per_page;
    }

/* class functions */

// display split-page-number-links
    function display_links($max_page_links, $parameters = '') {
      global $PHP_SELF, $request_type;
	
      $display_links_string = '<ul class="pagination justify-content-end">';

      //$class = 'class="currentpage"';

      if (tep_not_null($parameters) && (substr($parameters, -1) != '&')) $parameters .= '&';

// previous button - not disabled on first page
      if ($this->current_page_number > 1) {
        $display_links_string .= '<li class="page-item">';
          $display_links_string .= '<a class="page-link" href="' . tep_href_link($PHP_SELF, $parameters . $this->page_name . '=' . ($this->current_page_number - 1), $request_type) . '" title=" PREVNEXT_TITLE_PREVIOUS_PAGE"><i class="fa fa-angle-left"></i></a>';
        $display_links_string .= '</li>';
      } else {
        $display_links_string .= '<li class="page-item disabled">';
          $display_links_string .= '<a class="page-link" href="#" tabindex="-1"><i class="fa fa-angle-left"></i></a>';
        $display_links_string .= '</li>';
      }
// first page
	if($this->current_page_number > '10'){	
		$display_links_string .= '<li class="page-item">';
            $display_links_string .= '<a class="page-link" href="' . tep_href_link($PHP_SELF, $parameters . $this->page_name . '=' . (1), $request_type) . '" title=" ' . sprintf('PREVNEXT_TITLE_NEXT_SET_OF_NO_PAGE', $max_page_links) . ' ">1</a>';
          $display_links_string .= '</li>';
	}
		
// check if number_of_pages > $max_page_links
      $cur_window_num = intval($this->current_page_number / $max_page_links);
      if ($this->current_page_number % $max_page_links) $cur_window_num++;

      $max_window_num = intval($this->number_of_pages / $max_page_links);
      if ($this->number_of_pages % $max_page_links) $max_window_num++;

// previous window of pages
      if ($cur_window_num > 1) $display_links_string .= '<li ><a class="page-link" href="' . tep_href_link($PHP_SELF, $parameters . $this->page_name . '=' . (($cur_window_num - 1) * $max_page_links), $request_type) . '" title=" ' . sprintf('PREVNEXT_TITLE_PREV_SET_OF_NO_PAGE', $max_page_links) . ' ">...</a></li>';

// page nn button
      for ($jump_to_page = 1 + (($cur_window_num - 1) * $max_page_links); ($jump_to_page  <= ($cur_window_num * $max_page_links)) && ($jump_to_page <= $this->number_of_pages); $jump_to_page++) {
          if ($jump_to_page == $this->current_page_number) {
            $display_links_string .= '<li class="page-item active">';
              $display_links_string .= '<a class="page-link" href="' . tep_href_link($PHP_SELF, $parameters . $this->page_name . '=' . $jump_to_page, $request_type) . '" title=" ' . sprintf('PREVNEXT_TITLE_PAGE_NO', $jump_to_page) . ' ">' . $jump_to_page . '</a>';
            $display_links_string .= '</li>';
          } else {
            $display_links_string .= '<li class="page-item">';
              $display_links_string .= '<a class="page-link" href="' . tep_href_link($PHP_SELF, $parameters . $this->page_name . '=' . $jump_to_page, $request_type) . '" title=" ' . sprintf('PREVNEXT_TITLE_PAGE_NO', $jump_to_page) . ' ">' . $jump_to_page . '</a>';
            $display_links_string .= '</li>';
          }
        }

// next window of pages
      if ($cur_window_num < $max_window_num) {
          $display_links_string .= '<li class="page-item">';
            $display_links_string .= '<a class="page-link" href="' . tep_href_link($PHP_SELF, $parameters . $this->page_name . '=' . (($cur_window_num) * $max_page_links + 1), $request_type) . '" title=" ' . sprintf('PREVNEXT_TITLE_NEXT_SET_OF_NO_PAGE', $max_page_links) . ' ">...</a>';
          $display_links_string .= '</li>';
        }
// last page
	if ($this->current_page_number < $this->number_of_pages){	
		$display_links_string .= '<li class="page-item">';
            $display_links_string .= '<a class="page-link" href="' . tep_href_link($PHP_SELF, $parameters . $this->page_name . '=' . ($this->number_of_pages), $request_type) . '" title=" ' . sprintf('PREVNEXT_TITLE_NEXT_SET_OF_NO_PAGE', $max_page_links) . ' ">'.$this->number_of_pages.'</a>';
          $display_links_string .= '</li>';
	}
		
// next button
		if (($this->current_page_number < $this->number_of_pages) && ($this->number_of_pages != 1)) {
          $display_links_string .= '<li class="page-item">';
            $display_links_string .= '<a class="page-link" href="' . tep_href_link($PHP_SELF, $parameters . 'page=' . ($this->current_page_number + 1)) . '" aria-label=" Next"><span aria-hidden="true"><i class="fa fa-angle-right"></i></span></a>';
          $display_links_string .= '</li>';
        } else {
          $display_links_string .= '<li class="page-item disabled">';
            $display_links_string .= '<a class="page-link" href="#" tabindex="-1"><i class="fa fa-angle-right"></i></a>';
          $display_links_string .= '</li>';
        }

        $display_links_string .= '</ul>';
      $display_links_string .= '</nav>';

      return $display_links_string;
    }

// display number of total products found
    function display_count($text_output) {
      $to_num = ($this->number_of_rows_per_page * $this->current_page_number);
      if ($to_num > $this->number_of_rows){
		  $to_num = $this->number_of_rows;
	  }

      if ($to_num == 0) {
        $from_num = 0;
      } else {
        $from_num = ($this->number_of_rows_per_page * ($this->current_page_number - 1));

        $from_num++;
      }

      return sprintf($text_output, $from_num, $to_num, $this->number_of_rows);
    }
  }
  
  
?>