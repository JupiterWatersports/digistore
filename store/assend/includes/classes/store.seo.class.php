<?php
define('USE_SEO_REDIRECT_DEBUG', 'false');
/**
 * Ultimate SEO URLs Contribution - osCommerce MS-2.2
 *
 * Ultimate SEO URLs offers search engine optimized URLS for osCommerce
 * based applications. Other features include optimized performance and 
 * automatic redirect script.
 * @package Ultimate-SEO-URLs
 * @link http://www.oscommerce-freelancers.com/ osCommerce-Freelancers
 * @copyright Copyright 2005, Bobby Easland 
 * @author Bobby Easland 
 * @filesource
 */
/**
 * SEO_DataBase Class
 *
 * The SEO_DataBase class provides abstraction so the databaes can be accessed
 * without having to use tep API functions. This class has minimal error handling
 * so make sure your code is tight!
 * @package Ultimate-SEO-URLs
 * @link http://www.oscommerce-freelancers.com/ osCommerce-Freelancers
 * @copyright Copyright 2005, Bobby Easland 
 * @author Bobby Easland 
 */
/**
* Modified for MySQL5 in STRICT mode
* by FWR Media
* www.fwrmedia.co.uk
*/
class SEO_DataBase{
        /**
         * Database host (localhost, IP based, etc)
        * @var string
         */
        var $host;
        /**
         * Database user
        * @var string
         */
        var $user;
        /**
         * Database name
        * @var string
         */
        var $db;
        /**
         * Database password
        * @var string
         */
        var $pass;
        /**
         * Database link
        * @var resource
         */
        var $link_id;
/**
 * MySQL_DataBase class constructor 
 * @author Bobby Easland 
 * @version 1.0
 * @param string $host
 * @param string $user
 * @param string $db
 * @param string $pass  
 */        
        function SEO_DataBase($host, $user, $db, $pass){
                $this->host = $host;
                $this->user = $user;
                $this->db = $db;
                $this->pass = $pass;                
                $this->ConnectDB();
                $this->SelectDB();
        } # end function
/**
 * Function to connect to MySQL 
 * @author Bobby Easland 
 * @version 1.1
 */        
        function ConnectDB(){
                $this->link_id = tep_db_connect($this->host, $this->user, $this->pass, $this->db);
        } # end function
        
/**
 * Function to select the database
 * @author Bobby Easland 
 * @version 1.0
 * @return resoource 
 */        
        function SelectDB(){
                return mysqli_select_db($this->link_id, $this->db);
        } # end function
        
/**
 * Function to perform queries
 * @author Bobby Easland 
 * @version 1.0
 * @param string $query SQL statement
 * @return resource 
 */        
        function Query($query){
                return mysqli_query($this->link_id, $query);
        } # end function
        
/**
 * Function to fetch array
 * @author Bobby Easland 
 * @version 1.0
 * @param resource $resource_id
 * @param string $type MYSQL_BOTH or MYSQL_ASSOC
 * @return array 
 */        
        function FetchArray($resource_id, $type = MYSQLI_BOTH){
                return mysqli_fetch_array($resource_id, $type);
        } # end function
        
/**
 * Function to fetch the number of rows
 * @author Bobby Easland 
 * @version 1.0
 * @param resource $resource_id
 * @return mixed  
 */        
        function NumRows($resource_id){
                return mysqli_num_rows($resource_id);
        } # end function
/**
 * Function to fetch the last insertID
 * @author Bobby Easland 
 * @version 1.0
 * @return integer  
 */        
        function InsertID() {
                return mysqli_insert_id();
        }
        
/**
 * Function to free the resource
 * @author Bobby Easland 
 * @version 1.0
 * @param resource $resource_id
 * @return boolean
 */        
        function Free($resource_id){
                return @mysqli_free_result($resource_id);
        } # end function
/**
 * Function to add slashes
 * @author Bobby Easland 
 * @version 1.0
 * @param string $data
 * @return string 
 */        
        function Slashes($data){
                return addslashes($data);
        } # end function
/**
 * Function to perform DB inserts and updates - abstracted from osCommerce-MS-2.2 project
 * @author Bobby Easland 
 * @version 1.0
 * @param string $table Database table
 * @param array $data Associative array of columns / values
 * @param string $action insert or update
 * @param string $parameters
 * @return resource
 */        
        function DBPerform($table, $data, $action = 'insert', $parameters = '') {
                reset($data);
                if ($action == 'insert') {
                  $query = 'INSERT INTO `' . $table . '` (';
                  while (list($columns, ) = each($data)) {
                        $query .= '`' . $columns . '`, ';
                  }
                  $query = substr($query, 0, -2) . ') values (';
                  reset($data);
                  while (list(, $value) = each($data)) {
                        switch ((string)$value) {
                          case 'now()':
                                $query .= 'now(), ';
                                break;
                          case 'null':
                                $query .= 'null, ';
                                break;
                          default:
                                $query .= "'" . $this->Slashes($value) . "', ";
                                break;
                        }
                  }
                  $query = substr($query, 0, -2) . ')';
                } elseif ($action == 'update') {
                  $query = 'UPDATE `' . $table . '` SET ';
                  while (list($columns, $value) = each($data)) {
                        switch ((string)$value) {
                          case 'now()':
                                $query .= '`' .$columns . '`=now(), ';
                                break;
                          case 'null':
                                $query .= '`' .$columns .= '`=null, ';
                                break;
                          default:
                                $query .= '`' .$columns . "`='" . $this->Slashes($value) . "', ";
                                break;
                        }
                  }
                  $query = substr($query, 0, -2) . ' WHERE ' . $parameters;
                }
                return $this->Query($query);
        } # end function        
} # end class
/**
 * Ultimate SEO URLs Installer and Configuration Class
 *
 * Ultimate SEO URLs installer and configuration class offers a modular 
 * and easy to manage method of configuration.  The class enables the base
 * class to be configured and installed on the fly without the hassle of 
 * calling additional scripts or executing SQL.
 * @package Ultimate-SEO-URLs
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version 1.1
 * @link http://www.oscommerce-freelancers.com/ osCommerce-Freelancers
 * @copyright Copyright 2005, Bobby Easland 
 * @author Bobby Easland 
 */

/**
 * Ultimate SEO URLs Base Class
 *
 * Ultimate SEO URLs offers search engine optimized URLS for osCommerce
 * based applications. Other features include optimized performance and 
 * automatic redirect script.
 * @package Ultimate-SEO-URLs
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version 2.1
 * @link http://www.oscommerce-freelancers.com/ osCommerce-Freelancers
 * @copyright Copyright 2005, Bobby Easland 
 * @author Bobby Easland 
 */
class STORE_SEO_URL{
        /**
         * $cache is the per page data array that contains all of the previously stripped titles
        * @var array
         */
        var $cache;
        /**
         * $languages_id contains the language_id for this instance
        * @var integer
         */
        var $languages_id;
        /**
         * $attributes array contains all the required settings for class
        * @var array
         */
        var $attributes;
        /**
         * $base_url is the NONSSL URL for site
        * @var string
         */
        var $base_url;
        /**
         * $base_url_ssl is the secure URL for the site
        * @var string
         */
        var $base_url_ssl;
        /**
         * $performance array contains evaluation metric data
        * @var array
         */
        var $performance;
        /**
         * $timestamp simply holds the temp variable for time calculations
        * @var float
         */
        var $timestamp;
        /**
         * $reg_anchors holds the anchors used by the .htaccess rewrites
        * @var array
         */
        var $reg_anchors;
        /**
         * $cache_query is the resource_id used for database cache logic
        * @var resource
         */
        var $cache_query;
        /**
         * $cache_file is the basename of the cache database entry
        * @var string
         */
        var $cache_file;
        /**
         * $data array contains all records retrieved from database cache
        * @var array
         */
        var $data;
        /**
         * $need_redirect determines whether the URL needs to be redirected
        * @var boolean
         */
        var $need_redirect;
        /**
         * $is_seopage holds value as to whether page is in allowed SEO pages
        * @var boolean
         */
        var $is_seopage;
        /**
         * $uri contains the $_SERVER['REQUEST_URI'] value
        * @var string
         */
        var $uri;
        /**
         * $real_uri contains the $_SERVER['SCRIPT_NAME'] . '?' . $_SERVER['QUERY_STRING'] value
        * @var string
         */
        var $real_uri;
        /**
         * $uri_parsed contains the parsed uri value array
        * @var array
         */
        var $uri_parsed;
        /**
         * $path_info contains the getenv('PATH_INFO') value
        * @var string
         */
        var $path_info;
        /**
         * $DB is the database object
        * @var object
         */
        var $DB;
        /**
         * $installer is the installer object
        * @var object
         */
        var $installer;
        
/**
 * SEO_URL class constructor 
 * @author Bobby Easland 
 * @version 1.1
 * @param integer $languages_id
 */        
        function STORE_SEO_URL($languages_id){
            global $session_started, $SID;
                
                $this->DB = new SEO_DataBase(DB_SERVER, DB_SERVER_USERNAME, DB_DATABASE, DB_SERVER_PASSWORD);
                
                $this->languages_id = (int)$languages_id; 
                
                $this->data = array(); 
                
                $seo_pages = array(FILENAME_DEFAULT, 
                                   FILENAME_PRODUCT_INFO, 
                                                   FILENAME_POPUP_IMAGE,
                                                   FILENAME_PRODUCT_REVIEWS,
                                                   FILENAME_PRODUCT_REVIEWS_INFO);
                               
                $this->attributes = array('PHP_VERSION' => PHP_VERSION,
                                          'SESSION_STARTED' => $session_started,
                                                                  'SID' => $SID,
                                                                  'SEO_ENABLED' => defined('SEO_ENABLED') ? SEO_ENABLED : 'false',
                                                                  'SEO_ADD_CPATH_TO_PRODUCT_URLS' => defined('SEO_ADD_CPATH_TO_PRODUCT_URLS') ? SEO_ADD_CPATH_TO_PRODUCT_URLS : 'false',
                                                                  'SEO_ADD_CAT_PARENT' => defined('SEO_ADD_CAT_PARENT') ? SEO_ADD_CAT_PARENT : 'true',
                                                                  'SEO_URLS_USE_W3C_VALID' => defined('SEO_URLS_USE_W3C_VALID') ? SEO_URLS_USE_W3C_VALID : 'true',
                                                                  'USE_SEO_CACHE_GLOBAL' => defined('USE_SEO_CACHE_GLOBAL') ? USE_SEO_CACHE_GLOBAL : 'false',
                                                                  'USE_SEO_CACHE_PRODUCTS' => defined('USE_SEO_CACHE_PRODUCTS') ? USE_SEO_CACHE_PRODUCTS : 'false',
                                                                  'USE_SEO_CACHE_CATEGORIES' => defined('USE_SEO_CACHE_CATEGORIES') ? USE_SEO_CACHE_CATEGORIES : 'false',
                                                                  'USE_SEO_CACHE_MANUFACTURERS' => defined('USE_SEO_CACHE_MANUFACTURERS') ? USE_SEO_CACHE_MANUFACTURERS : 'false',
                                                                  'USE_SEO_CACHE_ARTICLES' => defined('USE_SEO_CACHE_ARTICLES') ? USE_SEO_CACHE_ARTICLES : 'false',
                                                                  'USE_SEO_CACHE_TOPICS' => defined('USE_SEO_CACHE_TOPICS') ? USE_SEO_CACHE_TOPICS : 'false',
                                                                  'USE_SEO_CACHE_INFO_PAGES' => defined('USE_SEO_CACHE_INFO_PAGES') ? USE_SEO_CACHE_INFO_PAGES : 'false',
                                                                  'USE_SEO_REDIRECT' => defined('USE_SEO_REDIRECT') ? USE_SEO_REDIRECT : 'false',
                                                                  'SEO_REWRITE_TYPE' => defined('SEO_REWRITE_TYPE') ? SEO_REWRITE_TYPE : 'false',
                                                                  'SEO_URLS_FILTER_SHORT_WORDS' => defined('SEO_URLS_FILTER_SHORT_WORDS') ? SEO_URLS_FILTER_SHORT_WORDS : 'false',
                                                                  'SEO_CHAR_CONVERT_SET' => defined('SEO_CHAR_CONVERT_SET') ? $this->expand(SEO_CHAR_CONVERT_SET) : 'false',
                                                                  'SEO_REMOVE_ALL_SPEC_CHARS' => defined('SEO_REMOVE_ALL_SPEC_CHARS') ? SEO_REMOVE_ALL_SPEC_CHARS : 'false',
                                                                  'SEO_PAGES' => $seo_pages,
                                                                  'SEO_INSTALLER' => $this->installer->attributes
                                                                  );                
                
                $this->base_url = HTTP_SERVER;
                $this->base_url_ssl = HTTPS_SERVER;                
                $this->cache = array();
                $this->timestamp = 0;
                
                $this->reg_anchors = array('products_id' => '-p-', 'pID' => '-pi-'
                                                                   );
                
                $this->performance = array('NUMBER_URLS_GENERATED' => 0,
                                                                   'NUMBER_QUERIES' => 0,                                                                   
                                                                   'CACHE_QUERY_SAVINGS' => 0,
                                                                   'NUMBER_STANDARD_URLS_GENERATED' => 0,
                                                                   'TOTAL_CACHED_PER_PAGE_RECORDS' => 0,
                                                                   'TOTAL_TIME' => 0,
                                                                   'TIME_PER_URL' => 0,
                                                                   'QUERIES' => array()
                                                                   );
                
                if ($this->attributes['USE_SEO_CACHE_GLOBAL'] == 'true'){
                        $this->cache_file = 'seo_urls_v2_';
                        $this->cache_gc();
                        if ( $this->attributes['USE_SEO_CACHE_PRODUCTS'] == 'true' ) $this->generate_products_cache();
                        
                } # end if
                if ($this->attributes['USE_SEO_REDIRECT'] == 'true'){
                        $this->check_redirect();
                } # end if
        } # end constructor
/**
 * Function to return SEO URL link SEO'd with stock generattion for error fallback
 * @author Bobby Easland 
 * @version 1.0
 * @param string $page Base script for URL 
 * @param string $parameters URL parameters
 * @param string $connection NONSSL/SSL
 * @param boolean $add_session_id Switch to add osCsid
 * @return string Formed href link 
 */	
	function href_link($page = '', $parameters = '', $add_session_id = true){
		$this->start($this->timestamp);
		$this->performance['NUMBER_URLS_GENERATED']++;
		if ( !in_array($page, $this->attributes['SEO_PAGES']) || $this->attributes['SEO_ENABLED'] == 'false' ) {
			return $this->stock_href_link($page, $parameters, $add_session_id);
		}
		$link = $this->base_url;
		$separator = '?';
		if ($this->not_null($parameters)) { 
			$link .= $this->parse_parameters($page, $parameters, $separator);	
		} else {
		  $link .= $page;
		}
		$link = $this->add_sid($link, $add_session_id, $separator); 
		$this->stop($this->timestamp, $time);
		$this->performance['TOTAL_TIME'] += $time;
		//convert cyrilic symbols to latin            
		$trdic = array(
		"¸"=>"yo",
		"æ"=>"zh",
		"ô"=>"ph",
		"õ"=>"kh",
		"ö"=>"ts",
		"÷"=>"ch",
		"ø"=>"sh",
		"ù"=>"sch",
		"ý"=>"e",
		"þ"=>"ju",
		"ÿ"=>"ja",
		
		"à"=>"a",
		"á"=>"b",
		"â"=>"v",
		"ã"=>"g",
		"ä"=>"d",
		"å"=>"e",
		"ç"=>"z",
		"è"=>"i",
		"é"=>"j",
		"ê"=>"k",
		"ë"=>"l",
		"ì"=>"m",
		"í"=>"n",
		"î"=>"o",
		"ï"=>"p",
		"ð"=>"r",
		"ñ"=>"s",
		"ò"=>"t",
		"ó"=>"u",
		"õ"=>"h",
		"ö"=>"c",
		"û"=>"y",
		
		"¨"=>"E",
		"Æ"=>"ZH",
		"Ô"=>"PH",
		"Õ"=>"KH",
		"Ö"=>"TS",
		"×"=>"CH",
		"Ø"=>"SH",
		"Ù"=>"SCH",
		"Ý"=>"E",
		"Þ"=>"JU",
		"ß"=>"JA",
		
		"À"=>"A",
		"Á"=>"B",
		"Â"=>"V",
		"Ã"=>"G",
		"Ä"=>"D",
		"Å"=>"E",
		"Ç"=>"Z",
		"È"=>"I",
		"É"=>"J",
		"Ê"=>"K",
		"Ë"=>"L",
		"Ì"=>"M",
		"Í"=>"N",
		
		"Î"=>"O",
		"Ï"=>"P",
		"Ð"=>"R",
		"Ñ"=>"S",
		"Ò"=>"T",
		"Ó"=>"U",
		"Õ"=>"H",
		"Ö"=>"C",
		"Û"=>"Y",
		
		// -----------------------
		  "Ú" => "",
		  "Ü" => "",
		  "ú" => "",
		  "ü" => ""
		);
		$link= strtr(stripslashes($link), $trdic);
		switch($this->attributes['SEO_URLS_USE_W3C_VALID']){
			case ('true'):
				if (!isset($_SESSION['customer_id']) && defined('ENABLE_PAGE_CACHE') && ENABLE_PAGE_CACHE == 'true' && class_exists('page_cache')){
					return $link;
				} else {
	 				//return htmlspecialchars(utf8_encode($link));
	 				return htmlentities(utf8_encode($link), ENT_QUOTES);
				}
				break;
			case ('false'):
				return $link;
				break;
		}
	} # end function
/**
 * Stock function, fallback use 
 */	
  function stock_href_link($page = '', $parameters = '', $connection = 'SSL', $add_session_id = true, $search_engine_safe = true) {
// ADD: SID KILLER    
    global $request_type, $session_started, $SID, $kill_sid;
// EOADD: SID KILLER 
    if (!$this->not_null($page)) {
      die('</td></tr></table></td></tr></table><br /><br /><font color="#ff0000"><b>Error!</b></font><br /><br /><b>Unable to determine the page link!<br /><br />');
    }
        if ($page == '/') $page = '';
    
      $link = HTTP_SERVER;
   
    if ($this->not_null($parameters)) {
      $link .= $page . '?' . $this->output_string($parameters);
      $separator = '&';
    } else {
      $link .= $page;
      $separator = '?';
    }
    while ( (substr($link, -1) == '&') || (substr($link, -1) == '?') ) $link = substr($link, 0, -1);
    if ( ($add_session_id == true) && ($session_started == true) && (SESSION_FORCE_COOKIE_USE == 'False') ) {
      if ($this->not_null($SID)) {
        $_sid = $SID;
      } elseif ( ( ($request_type == 'NONSSL') && ($connection == 'SSL') && (ENABLE_SSL == true) ) || ( ($request_type == 'SSL') && ($connection == 'NONSSL') ) ) {
        if (HTTP_COOKIE_DOMAIN != HTTPS_COOKIE_DOMAIN) {
          $_sid = $this->SessionName() . '=' . $this->SessionID();
        }
      }
    }
    if ( (SEARCH_ENGINE_FRIENDLY_URLS == 'true') && ($search_engine_safe == true) ) {
      while (strstr($link, '&&')) $link = str_replace('&&', '&', $link);
      $link = str_replace('?', '/', $link);
      $link = str_replace('&', '/', $link);
      $link = str_replace('=', '/', $link);
      $separator = '?';
    }
        switch(true){
                case (!isset($_SESSION['customer_id']) && defined('ENABLE_PAGE_CACHE') && ENABLE_PAGE_CACHE == 'true' && class_exists('page_cache')):
                        $page_cache = true;
                        $return = $link . $separator . '<osCsid>';
                        break;
                case (isset($_sid) && ( !$kill_sid )): 
                        $page_cache = false;
                        $return = $link . $separator . tep_output_string($_sid);
                        break;
                default:
                        $page_cache = false;
                        $return = $link;
                        break;
        } # end switch
        $this->performance['NUMBER_STANDARD_URLS_GENERATED']++;
        $this->cache['STANDARD_URLS'][] = $link;
        $time = 0;
        $this->stop($this->timestamp, $time);
        $this->performance['TOTAL_TIME'] += $time;
        switch(true){
                case ($this->attributes['SEO_URLS_USE_W3C_VALID'] == 'true' && !$page_cache):
                        return htmlspecialchars(utf8_encode($return));
                        break;
                default:
                        return $return;
                        break;
        }# end swtich
  } # end default tep_href function
/**
 * Function to append session ID if needed 
 * @author Bobby Easland 
 * @version 1.2
 * @param string $link 
 * @param boolean $add_session_id
 * @param string $connection
 * @param string $separator
 * @return string
 */        
        function add_sid( $link, $add_session_id, $separator ){
                global $request_type, $kill_sid; // global variable 
                if ( ($add_session_id) && ($this->attributes['SESSION_STARTED']) && (SESSION_FORCE_COOKIE_USE == 'False') ) {
                  if ($this->not_null($this->attributes['SID'])) {
                        $_sid = $this->attributes['SID'];
                  } elseif (ENABLE_SSL == true) {
                        if (HTTP_COOKIE_DOMAIN != HTTPS_COOKIE_DOMAIN) {
                          $_sid = $this->SessionName() . '=' . $this->SessionID();
                        }
                  }
                } 
                switch(true){
                        case (!isset($_SESSION['customer_id']) && defined('ENABLE_PAGE_CACHE') && ENABLE_PAGE_CACHE == 'true' && class_exists('page_cache')):
                                $return = $link . $separator . '<osCsid>';
                                break;
                        case ($this->not_null($_sid) && ( !$kill_sid )): ///// SID-KILLER ( change ) /// ORG: case ($this->not_null($_sid)):
                                $return = $link . $separator . tep_output_string($_sid);
                                break;
                        default:
                                $return = $link;
                                break;
                } # end switch
                return $return;
        } # end function
        
/**
 * SFunction to parse the parameters into an SEO URL 
 * @author Bobby Easland 
 * @version 1.2
 * @param string $page
 * @param string $params
 * @param string $separator NOTE: passed by reference
 * @return string 
 */        
        function parse_parameters($page, $params, &$separator){
			
                $p = explode('&', $params);
                krsort($p);
                $container = array();
                foreach ($p as $index => $valuepair){
                        $p2 = @explode('=', $valuepair); 
                        switch ($p2[0]){ 
                                case 'products_id':
                                        switch(true){
												
                                                case ( $page == FILENAME_PRODUCT_INFO && !$this->is_attribute_string($p2[1]) ):
                                                        $url = '/store/'.$this->make_url($page, $this->get_product_name($p2[1]), $p2[0], $p2[1], '.html', $separator);
                                                        break;
                                                
                                                default:
                                                        $container[$p2[0]] = $p2[1];
                                                        break;
                                        } # end switch
                                        break;
                                
                                
                                case 'pID':
                                        switch(true){
                                                case ($page == FILENAME_POPUP_IMAGE):
                                                $url = $this->make_url($page, $this->get_product_name($p2[1]), $p2[0], $p2[1], '.html', $separator);
                                                break;
                                        default:
                                                $container[$p2[0]] = $p2[1];
                                                break;
                                        } # end switch
                                        break;
                                
                                default:
                                        $container[$p2[0]] = $p2[1]; 
                                        break;
                        } # end switch
                } # end foreach $p
                $url = isset($url) ? $url : $page;
                if ( sizeof($container) > 0 ){
                        if ( $imploded_params = $this->implode_assoc($container) ){
                                $url .= $separator . $this->output_string( $imploded_params );
                                $separator = '&';
                        }
                }
                return $url;
        } # end function
/**
 * Function to return the generated SEO URL         
 * @author Bobby Easland 
 * @version 1.0
 * @param string $page
 * @param string $string Stripped, formed anchor
 * @param string $anchor_type Parameter type (products_id, cPath, etc.)
 * @param integer $id
 * @param string $extension Default = .html
 * @param string $separator NOTE: passed by reference
 * @return string
 */        
        function make_url($page, $string, $anchor_type, $id, $extension = '.html', &$separator){
                // Right now there is but one rewrite method since cName was dropped
                // In the future there will be additional methods here in the switch
                switch ( $this->attributes['SEO_REWRITE_TYPE'] ){
                        case 'Rewrite':
                                return $string . $this->reg_anchors[$anchor_type] . $id . $extension;
                                break;
                        default:
                                break;
                } # end switch
        } # end function
/**
 * Function to get the product name. Use evaluated cache, per page cache, or database query in that order of precedent        
 * @author Bobby Easland 
 * @version 1.1
 * @param integer $pID
 * @return string Stripped anchor text
 */        
        function get_product_name($pID){
                switch(true){
                        case ($this->attributes['USE_SEO_CACHE_GLOBAL'] == 'true' && defined('PRODUCT_NAME_' . $pID)):
                                $this->performance['CACHE_QUERY_SAVINGS']++;
                                $return = constant('PRODUCT_NAME_' . $pID);
                                $this->cache['PRODUCTS'][$pID] = $return;
                                break;
                        case ($this->attributes['USE_SEO_CACHE_GLOBAL'] == 'true' && isset($this->cache['PRODUCTS'][$pID])):
                                $this->performance['CACHE_QUERY_SAVINGS']++;
                                $return = $this->cache['PRODUCTS'][$pID];
                                break;
                        default:
                                $this->performance['NUMBER_QUERIES']++;
                                $sql = "SELECT products_name as pName 
                                                FROM ".TABLE_PRODUCTS_DESCRIPTION." 
                                                WHERE products_id='".(int)$pID."' 
                                                AND language_id='".(int)$this->languages_id."' 
                                                LIMIT 1 and";
                                $result = $this->DB->FetchArray( $this->DB->Query( $sql ) );
                                $pName = $this->strip( $result['pName'] );
                                $this->cache['PRODUCTS'][$pID] = $pName;
                                $this->performance['QUERIES']['PRODUCTS'][] = $sql;
                                $return = $pName;
                                break;                                                                
                } # end switch                
		//convert cyrilic symbols to latin
		$trdic = array(
		"¸"=>"yo",
		"æ"=>"zh",
		"ô"=>"ph",
		"õ"=>"kh",
		"ö"=>"ts",
		"÷"=>"ch",
		"ø"=>"sh",
		"ù"=>"sch",
		"ý"=>"e",
		"þ"=>"ju",
		"ÿ"=>"ja",
		
		"à"=>"a",
		"á"=>"b",
		"â"=>"v",
		"ã"=>"g",
		"ä"=>"d",
		"å"=>"e",
		"ç"=>"z",
		"è"=>"i",
		"é"=>"j",
		"ê"=>"k",
		"ë"=>"l",
		"ì"=>"m",
		"í"=>"n",
		"î"=>"o",
		"ï"=>"p",
		"ð"=>"r",
		"ñ"=>"s",
		"ò"=>"t",
		"ó"=>"u",
		"õ"=>"h",
		"ö"=>"c",
		"û"=>"y",
		
		"¨"=>"Yo",
		"Æ"=>"ZH",
		"Ô"=>"PH",
		"Õ"=>"KH",
		"Ö"=>"TS",
		"×"=>"CH",
		"Ø"=>"SH",
		"Ù"=>"SCH",
		"Ý"=>"E",
		"Þ"=>"JU",
		"ß"=>"JA",
		
		"À"=>"A",
		"Á"=>"B",
		"Â"=>"V",
		"Ã"=>"G",
		"Ä"=>"D",
		"Å"=>"E",
		"Ç"=>"Z",
		"È"=>"I",
		"É"=>"J",
		"Ê"=>"K",
		"Ë"=>"L",
		"Ì"=>"M",
		"Í"=>"N",
		
		"Î"=>"O",
		"Ï"=>"P",
		"Ð"=>"R",
		"Ñ"=>"S",
		"Ò"=>"T",
		"Ó"=>"U",
		"Õ"=>"H",
		"Ö"=>"C",
		"Û"=>"Y",
		
		// -----------------------
		  "Ú" => "",
		  "Ü" => "",
		  "ú" => "",
		  "ü" => ""
		);
		
		$txt=$return;
		return nl2br(strtr(stripslashes($txt), $trdic));
	} # end function
       
        function not_null($value) {
                if (is_array($value)) {
                        if (sizeof($value) > 0) {
                                return true;
                        } else {
                                return false;
                        }
                } else {
                        if (($value != '') && (strtolower($value) != 'null') && (strlen(trim($value)) > 0)) {
                                return true;
                        } else {
                                return false;
                        }
                }
        } # end function
/**
 * Function to check if the products_id contains an attribute 
 * @author Bobby Easland 
 * @version 1.1
 * @param integer $pID
 * @return boolean
 */        
        function is_attribute_string($pID){
                if ( is_numeric(strpos($pID, '{')) ){
                        return true;
                } else {
                        return false;
                }
        } # end function
/**
 * Function to check if the params contains a products_id 
 * @author Bobby Easland 
 * @version 1.1
 * @param string $params
 * @return boolean
 */        
        function is_product_string($params){
                if ( is_numeric(strpos('products_id', $params)) ){
                        return true;
                } else {
                        return false;
                }
        } # end function
/**
 * Function to check if cPath is in the parameter string  
 * @author Bobby Easland 
 * @version 1.0
 * @param string $params
 * @return boolean
 */        
        function is_cPath_string($params){
                if ( eregi('cPath', $params) ){
                        return true;
                } else {
                        return false;
                }
        } # end function
/**
 * Function used to output class profile
 * @author Bobby Easland 
 * @version 1.0
 */        
        function profile(){
                $this->calculate_performance();
                $this->PrintArray($this->attributes, 'Class Attributes');
                $this->PrintArray($this->cache, 'Cached Data');
        } # end function
/**
 * Function used to calculate and output the performance metrics of the class
 * @author Bobby Easland 
 * @version 1.0
 * @return mixed Output of performance data wrapped in HTML pre tags
 */        
        function calculate_performance(){
                foreach ($this->cache as $type){
                        $this->performance['TOTAL_CACHED_PER_PAGE_RECORDS'] += sizeof($type);                        
                }
                $this->performance['TIME_PER_URL'] = $this->performance['TOTAL_TIME'] / $this->performance['NUMBER_URLS_GENERATED'];
                return $this->PrintArray($this->performance, 'Performance Data');
        } # end function
        
/**
 * Function to strip the string of punctuation and white space 
 * @author Bobby Easland 
 * @version 1.1
 * @param string $string
 * @return string Stripped text. Removes all non-alphanumeric characters.
 */        
        function strip($string){
			$pattern = $this->attributes['SEO_REMOVE_ALL_SPEC_CHARS'] == 'true'
				? "/([^[:alnum:]])+/"
				: "/([[:punct:]])+/";
			$anchor = preg_replace($pattern, '', strtolower($string));
			$pattern = "/([[:space:]]|[[:blank:]])+/";
			
			$anchor = preg_replace($pattern, '-', $anchor);
			if ( is_array($this->attributes['SEO_CHAR_CONVERT_SET']) ) $anchor = strtr($anchor, $this->attributes['SEO_CHAR_CONVERT_SET']);
			
			return $this->short_name($anchor); // return the short filtered name
		} # end function
/**
 * Function to expand the SEO_CONVERT_SET group 
 * @author Bobby Easland 
 * @version 1.0
 * @param string $set
 * @return mixed
 */        
        function expand($set){
                if ( $this->not_null($set) ){
                        if ( $data = @explode(',', $set) ){
                                foreach ( $data as $index => $valuepair){
                                        $p = @explode('=>', $valuepair);
                                        $container[trim($p[0])] = trim($p[1]);
                                }
                                return $container;
                        } else {
                                return 'false';
                        }
                } else {
                        return 'false';
                }
        } # end function
/**
 * Function to return the short word filtered string 
 * @author Bobby Easland 
 * @version 1.0
 * @param string $str
 * @param integer $limit
 * @return string Short word filtered
 */        
        function short_name($str, $limit=3){
                if ( $this->attributes['SEO_URLS_FILTER_SHORT_WORDS'] != 'false' ) $limit = (int)$this->attributes['SEO_URLS_FILTER_SHORT_WORDS'];
                $foo = @explode('-', $str);
                foreach($foo as $index => $value){
                        switch (true){
                                case ( strlen($value) <= $limit ):
                                        continue;
                                default:
                                        $container[] = $value;
                                        break;
                        }                
                } # end foreach
                $container = ( sizeof($container) > 1 ? implode('-', $container) : $str );
                return $container;
        }
        
/**
 * Function to implode an associative array 
 * @author Bobby Easland 
 * @version 1.0
 * @param array $array Associative data array
 * @param string $inner_glue
 * @param string $outer_glue
 * @return string
 */        
        function implode_assoc($array, $inner_glue='=', $outer_glue='&') {
                $output = array();
                foreach( $array as $key => $item ){
                        if ( $this->not_null($key) && $this->not_null($item) ){
                                $output[] = $key . $inner_glue . $item;
                        }
                } # end foreach        
                return @implode($outer_glue, $output);
        }
/**
 * Function to print an array within pre tags, debug use 
 * @author Bobby Easland 
 * @version 1.0
 * @param mixed $array
 */        
        function PrintArray($array, $heading = ''){
                echo '<fieldset style="border-style:solid; border-width:1px;">' . "\n";
                echo '<legend style="background-color:#FFFFCC; border-style:solid; border-width:1px;">' . $heading . '</legend>' . "\n";
                echo '<pre style="text-align:left;">' . "\n";
                print_r($array);
                echo '</pre>' . "\n";
                echo '</fieldset><br/>' . "\n";
        } # end function
/**
 * Function to start time for performance metric 
 * @author Bobby Easland 
 * @version 1.0
 * @param float $start_time
 */        
        function start(&$start_time){
                $start_time = explode(' ', microtime());
        }
        
/**
 * Function to stop time for performance metric 
 * @author Bobby Easland 
 * @version 1.0
 * @param float $start
 * @param float $time NOTE: passed by reference
 */        
        function stop($start, &$time){
                $end = explode(' ', microtime());
                $time = number_format( array_sum($end) - array_sum($start), 8, '.', '' );
        }
/**
 * Function to translate a string 
 * @author Bobby Easland 
 * @version 1.0
 * @param string $data String to be translated
 * @param array $parse Array of tarnslation variables
 * @return string
 */        
        function parse_input_field_data($data, $parse) {
                return strtr(trim($data), $parse);
        }
        
/**
 * Function to output a translated or sanitized string 
 * @author Bobby Easland 
 * @version 1.0
 * @param string $sting String to be output
 * @param mixed $translate Array of translation characters
 * @param boolean $protected Switch for htemlspecialchars processing
 * @return string
 */        
        function output_string($string, $translate = false, $protected = false) {
                if ($protected == true) {
                  return htmlspecialchars($string);
                } else {
                  if ($translate == false) {
                        return $this->parse_input_field_data($string, array('"' => '&quot;'));
                  } else {
                        return $this->parse_input_field_data($string, $translate);
                  }
                }
        }
/**
 * Function to return the session ID 
 * @author Bobby Easland 
 * @version 1.0
 * @param string $sessid
 * @return string
 */        
        function SessionID($sessid = '') {
                if (!empty($sessid)) {
                  return session_id($sessid);
                } else {
                  return session_id();
                }
        }
        
/**
 * Function to return the session name 
 * @author Bobby Easland 
 * @version 1.0
 * @param string $name
 * @return string
 */        
        function SessionName($name = '') {
                if (!empty($name)) {
                  return session_name($name);
                } else {
                  return session_name();
                }
        }
/**
 * Function to generate products cache entries 
 * @author Bobby Easland 
 * @version 1.0
 */        
        function generate_products_cache(){
                $this->is_cached($this->cache_file . 'products', $is_cached, $is_expired);          
                if ( !$is_cached || $is_expired ) {
                $sql = "SELECT p.products_id as id, pd.products_name as name 
                        FROM ".TABLE_PRODUCTS." p 
                                LEFT JOIN ".TABLE_PRODUCTS_DESCRIPTION." pd 
                                ON p.products_id=pd.products_id 
                                AND pd.language_id='".(int)$this->languages_id."' 
                                WHERE p.products_status='1'";
                $product_query = $this->DB->Query( $sql );
                $prod_cache = '';
                while ($product = $this->DB->FetchArray($product_query)) {
                        $define = 'define(\'PRODUCT_NAME_' . $product['id'] . '\', \'' . $this->strip($product['name']) . '\');';
                        $prod_cache .= $define . "\n";
                        eval("$define");
                }
                $this->DB->Free($product_query);
                $this->save_cache($this->cache_file . 'products', $prod_cache, 'EVAL', 1 , 1);
                unset($prod_cache);
                } else {
                        $this->get_cache($this->cache_file . 'products');                
                }
        } # end function
                
/**
 * Function to save the cache to database 
 * @author Bobby Easland 
 * @version 1.0
 * @param string $name Cache name
 * @param mixed $value Can be array, string, PHP code, or just about anything
 * @param string $method RETURN, ARRAY, EVAL
 * @param integer $gzip Enables compression
 * @param integer $global Sets whether cache record is global is scope
 * @param string $expires Sets the expiration
 */        
        function save_cache($name, $value, $method='RETURN', $gzip=1, $global=0, $expires = '30/days'){
                $expires = $this->convert_time($expires);                
                if ($method == 'ARRAY' ) $value = serialize($value);
                $value = ( $gzip === 1 ? base64_encode(gzdeflate($value, 1)) : addslashes($value) );
                $sql_data_array = array('cache_id' => md5($name),
                                                                'cache_language_id' => (int)$this->languages_id,
                                                                'cache_name' => $name,
                                                                'cache_data' => $value,
                                                                'cache_global' => (int)$global,
                                                                'cache_gzip' => (int)$gzip,
                                                                'cache_method' => $method,
                                                                'cache_date' => date("Y-m-d H:i:s"),
                                                                'cache_expires' => $expires
                                                                );                                                                
                $this->is_cached($name, $is_cached, $is_expired);
                $cache_check = ( $is_cached ? 'true' : 'false' );
                switch ( $cache_check ) {
                        case 'true': 
                                $this->DB->DBPerform('cache', $sql_data_array, 'update', "cache_id='".md5($name)."'");
                                break;                                
                        case 'false':
                                $this->DB->DBPerform('cache', $sql_data_array, 'insert');
                                break;                                
                        default:
                                break;
                } # end switch ($cache check)                
                # unset the variables...clean as we go
                unset($value, $expires, $sql_data_array);                
        }# end function save_cache()
        
/**
 * Function to get cache entry 
 * @author Bobby Easland 
 * @version 1.0
 * @param string $name
 * @param boolean $local_memory
 * @return mixed
 */        
        function get_cache($name = 'GLOBAL', $local_memory = false){
                $select_list = 'cache_id, cache_language_id, cache_name, cache_data, cache_global, cache_gzip, cache_method, cache_date, cache_expires';
                $global = ( $name == 'GLOBAL' ? true : false ); // was GLOBAL passed or is using the default?
                switch($name){
                        case 'GLOBAL': 
                                $this->cache_query = $this->DB->Query("SELECT ".$select_list." FROM cache WHERE cache_language_id='".(int)$this->languages_id."' AND cache_global='1'");
                                break;
                        default: 
                                $this->cache_query = $this->DB->Query("SELECT ".$select_list." FROM cache WHERE cache_id='".md5($name)."' AND cache_language_id='".(int)$this->languages_id."'");
                                break;
                } # end switch ($name)
                $num_rows = $this->DB->NumRows($this->cache_query);
                if ( $num_rows ){ 
                        $container = array();
                        while($cache = $this->DB->FetchArray($this->cache_query)){
                                $cache_name = $cache['cache_name']; 
                                if ( $cache['cache_expires'] > date("Y-m-d H:i:s") ) { 
                                        $cache_data = ( $cache['cache_gzip'] == 1 ? gzinflate(base64_decode($cache['cache_data'])) : stripslashes($cache['cache_data']) );
                                        switch($cache['cache_method']){
                                                case 'EVAL': // must be PHP code
                                                        eval("$cache_data");
                                                        break;                                                        
                                                case 'ARRAY': 
                                                        $cache_data = unserialize($cache_data);                                                        
                                                case 'RETURN': 
                                                default:
                                                        break;
                                        } # end switch ($cache['cache_method'])                                        
                                        if ($global) $container['GLOBAL'][$cache_name] = $cache_data; 
                                        else $container[$cache_name] = $cache_data; // not global                                
                                } else { // cache is expired
                                        if ($global) $container['GLOBAL'][$cache_name] = false; 
                                        else $container[$cache_name] = false; 
                                }# end if ( $cache['cache_expires'] > date("Y-m-d H:i:s") )                        
                                if ( $this->keep_in_memory || $local_memory ) {
                                        if ($global) $this->data['GLOBAL'][$cache_name] = $container['GLOBAL'][$cache_name]; 
                                        else $this->data[$cache_name] = $container[$cache_name]; 
                                }                                                        
                        } # end while ($cache = $this->DB->FetchArray($this->cache_query))                        
                        unset($cache_data);
                        $this->DB->Free($this->cache_query);                        
                        switch (true) {
                                case ($num_rows == 1): 
                                        if ($global){
                                                if ($container['GLOBAL'][$cache_name] == false || !isset($container['GLOBAL'][$cache_name])) return false;
                                                else return $container['GLOBAL'][$cache_name]; 
                                        } else { // not global
                                                if ($container[$cache_name] == false || !isset($container[$cache_name])) return false;
                                                else return $container[$cache_name];
                                        } # end if ($global)                                        
                                case ($num_rows > 1): 
                                default: 
                                        return $container; 
                                        break;
                        }# end switch (true)                        
                } else { 
                        return false;
                }# end if ( $num_rows )                
        } # end function get_cache()
/**
 * Function to get cache from memory
 * @author Bobby Easland 
 * @version 1.0
 * @param string $name
 * @param string $method
 * @return mixed
 */        
        function get_cache_memory($name, $method = 'RETURN'){
                $data = ( isset($this->data['GLOBAL'][$name]) ? $this->data['GLOBAL'][$name] : $this->data[$name] );
                if ( isset($data) && !empty($data) && $data != false ){ 
                        switch($method){
                                case 'EVAL': // data must be PHP
                                        eval("$data");
                                        return true;
                                        break;
                                case 'ARRAY': 
                                case 'RETURN':
                                default:
                                        return $data;
                                        break;
                        } # end switch ($method)
                } else { 
                        return false;
                } # end if (isset($data) && !empty($data) && $data != false)
        } # end function get_cache_memory()
/**
 * Function to perform basic garbage collection for database cache system 
 * @author Bobby Easland 
 * @version 1.0
 */        
        function cache_gc(){
                $this->DB->Query("DELETE FROM cache WHERE cache_expires <= '" . date("Y-m-d H:i:s") . "'" );
        }
/**
 * Function to convert time for cache methods 
 * @author Bobby Easland 
 * @version 1.0
 * @param string $expires
 * @return string
 */        
        function convert_time($expires){ //expires date interval must be spelled out and NOT abbreviated !!
                $expires = explode('/', $expires);
                switch( strtolower($expires[1]) ){ 
                        case 'seconds':
                                $expires = mktime( date("H"), date("i"), date("s")+(int)$expires[0], date("m"), date("d"), date("Y") );
                                break;
                        case 'minutes':
                                $expires = mktime( date("H"), date("i")+(int)$expires[0], date("s"), date("m"), date("d"), date("Y") );
                                break;
                        case 'hours':
                                $expires = mktime( date("H")+(int)$expires[0], date("i"), date("s"), date("m"), date("d"), date("Y") );
                                break;
                        case 'days':
                                $expires = mktime( date("H"), date("i"), date("s"), date("m"), date("d")+(int)$expires[0], date("Y") );
                                break;
                        case 'months':
                                $expires = mktime( date("H"), date("i"), date("s"), date("m")+(int)$expires[0], date("d"), date("Y") );
                                break;
                        case 'years':
                                $expires = mktime( date("H"), date("i"), date("s"), date("m"), date("d"), date("Y")+(int)$expires[0] );
                                break;
                        default: // if something fudged up then default to 1 month
                                $expires = mktime( date("H"), date("i"), date("s"), date("m")+1, date("d"), date("Y") );
                                break;
                } # end switch( strtolower($expires[1]) )
                return date("Y-m-d H:i:s", $expires);
        } # end function convert_time()
/**
 * Function to check if the cache is in the database and expired  
 * @author Bobby Easland 
 * @version 1.0
 * @param string $name
 * @param boolean $is_cached NOTE: passed by reference
 * @param boolean $is_expired NOTE: passed by reference
 */        
        function is_cached($name, &$is_cached, &$is_expired){ // NOTE: $is_cached and $is_expired is passed by reference !!
                $this->cache_query = $this->DB->Query("SELECT cache_expires FROM cache WHERE cache_id='".md5($name)."' AND cache_language_id='".(int)$this->languages_id."' LIMIT 1 ");
                $is_cached = ( $this->DB->NumRows($this->cache_query ) > 0 ? true : false );
                if ($is_cached){ 
                        $check = $this->DB->FetchArray($this->cache_query);
                        $is_expired = ( $check['cache_expires'] <= date("Y-m-d H:i:s") ? true : false );
                        unset($check);
                }
                $this->DB->Free($this->cache_query);
        }# end function is_cached()
/**
 * Function to initialize the redirect logic
 * @author Bobby Easland 
 * @version 1.1
 */        
        function check_redirect(){
                $this->need_redirect = false; 
                $this->path_info = is_numeric(strpos(ltrim(getenv('PATH_INFO'), '/') , '/')) ? ltrim(getenv('PATH_INFO'), '/') : NULL;
                $this->uri = ltrim( basename($_SERVER['REQUEST_URI']), '/' );
                $this->real_uri = ltrim( basename($_SERVER['SCRIPT_NAME']) . '?' . $_SERVER['QUERY_STRING'], '/' );
                $this->uri_parsed = $this->not_null( $this->path_info )
                                                                ?        parse_url(basename($_SERVER['SCRIPT_NAME']) . '?' . $this->parse_path($this->path_info) )
                                                                :        parse_url(basename($_SERVER['REQUEST_URI']));                        
                $this->attributes['SEO_REDIRECT']['PATH_INFO'] = $this->path_info;                        
                $this->attributes['SEO_REDIRECT']['URI'] = $this->uri;
                $this->attributes['SEO_REDIRECT']['REAL_URI'] = $this->real_uri;                        
                $this->attributes['SEO_REDIRECT']['URI_PARSED'] = $this->uri_parsed;                        
                $this->need_redirect(); 
                $this->check_seo_page();                 
                if ( $this->need_redirect && $this->is_seopage && $this->attributes['USE_SEO_REDIRECT'] == 'true') $this->do_redirect();                        
        } # end function
        
/**
 * Function to check if the URL needs to be redirected 
 * @author Bobby Easland 
 * @version 1.2
 */        
        function need_redirect(){                
                foreach( $this->reg_anchors as $param => $value){
                        $pattern[] = $param;
                }
                switch(true){
                        case ($this->is_attribute_string($this->uri)):
                                $this->need_redirect = false;
                                break;
                        case ($this->uri != $this->real_uri && !$this->not_null($this->path_info)):
                                $this->need_redirect = false;
                                break;
                        case (is_numeric(strpos($this->uri, '.htm'))):
                                $this->need_redirect = false;
                                break;
                        case (@eregi("(".@implode('|', $pattern).")", $this->uri)):
                                $this->need_redirect = true;
                                break;
                        case (@eregi("(".@implode('|', $pattern).")", $this->path_info)):
                                $this->need_redirect = true;
                                break;
                        default:
                                break;                        
                } # end switch
                $this->attributes['SEO_REDIRECT']['NEED_REDIRECT'] = $this->need_redirect ? 'true' : 'false';
        } # end function set_seopage
        
/**
 * Function to check if it's a valid redirect page 
 * @author Bobby Easland 
 * @version 1.1
 */        
        function check_seo_page(){
                switch (true){
                        case (in_array($this->uri_parsed['path'], $this->attributes['SEO_PAGES'])):
                                $this->is_seopage = true;
                                break;
                        case ($this->attributes['SEO_ENABLED'] == 'false'):
                        default:
                                $this->is_seopage = false;
                                break;
                } # end switch
                $this->attributes['SEO_REDIRECT']['IS_SEOPAGE'] = $this->is_seopage ? 'true' : 'false';
        } # end function check_seo_page
        
/**
 * Function to parse the path for old SEF URLs 
 * @author Bobby Easland 
 * @version 1.0
 * @param string $path_info
 * @return array
 */        
        function parse_path($path_info){ 
                $tmp = @explode('/', $path_info);                 
                if ( sizeof($tmp) > 2 ){
                        $container = array();                                
                        for ($i=0, $n=sizeof($tmp); $i<$n; $i++) {
                                $container[] = $tmp[$i] . '=' . $tmp[$i+1]; 
                                $i++; 
                        }
                        return @implode('&', $container);                        
                } else { 
                        return @implode('=', $tmp);
                }                                
        } # end function parse_path
        
/**
 * Function to perform redirect 
 * @author Bobby Easland 
 * @version 1.0
 */        
        function do_redirect(){
                $url_fixed = str_replace('%26', '&', $this->uri_parsed['query']);
                $p = @explode('&', $url_fixed);
                foreach( $p as $index => $value ){                                                        
                        $tmp = @explode('=', $value);
                                switch($tmp[0]){
                                        case 'products_id':
                                                if ( $this->is_attribute_string($tmp[1]) ){
                                                        $pieces = @explode('{', $tmp[1]);                                                        
                                                        $params[] = $tmp[0] . '=' . $pieces[0];
                                                } else {
                                                        $params[] = $tmp[0] . '=' . $tmp[1];
                                                }
                                                break;
                                        default:
                                                $params[] = $tmp[0].'='.$tmp[1];
                                                break;                                                
                                }
                } # end foreach( $params as $var => $value )
                $params = ( sizeof($params) > 1 ? implode('&', $params) : $params[0] );                
                $url = $this->href_link($this->uri_parsed['path'], $params, false);
                switch(true){
                        case (defined('USE_SEO_REDIRECT_DEBUG') && USE_SEO_REDIRECT_DEBUG == 'true'):
                                $this->attributes['SEO_REDIRECT']['REDIRECT_URL'] = $url;
                                break;
                        case ($this->attributes['USE_SEO_REDIRECT'] == 'true'):
                                header("HTTP/1.0 301 Moved Permanently");
                                $url = str_replace('&amp;', '&', $url);
                                header("Location: $url"); // redirect...bye bye                
                                break;
                        default:
                                $this->attributes['SEO_REDIRECT']['REDIRECT_URL'] = $url;
                                break;
                } # end switch
        } # end function do_redirect        
} # end class
?>