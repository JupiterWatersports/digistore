<?php
/*
  $Id: attributeManagerInstant.class.php,v 1.0 21/02/06 Sam West$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Released under the GNU General Public License
  
  Copyright � 2006 Kangaroo Partners
  http://kangaroopartners.com
  osc@kangaroopartners.com
*/

class attributeManagerInstant extends attributeManager {
	
	/**
	 * @access private
	 */
	var $intPID;
	
	/**
	 * __construct() assigns pid, calls the parent construct, registers page actions
	 * @access public
	 * @author Sam West aka Nimmit - osc@kangaroopartners.com
	 * @param $intPID int
	 * @return void
	 */
	function attributeManagerInstant($intPID) {
		
		parent::attributeManager();
		
		$this->intPID = (int)$intPID;
		
		$this->registerPageAction('addAttributeToProduct','addAttributeToProduct');
		$this->registerPageAction('addOptionValueToProduct','addOptionValueToProduct');
		$this->registerPageAction('addNewOptionValueToProduct','addNewOptionValueToProduct');
		$this->registerPageAction('removeOptionFromProduct','removeOptionFromProduct');
		$this->registerPageAction('removeOptionValueFromProduct','removeOptionValueFromProduct');
		// QT Pro Plugin
		$this->registerPageAction('removeStockOptionValueFromProduct','removeStockOptionValueFromProduct');
		$this->registerPageAction('addStockToProduct','addStockToProduct');
        $this->registerPageAction('updateProductStockQuantity','updateProductStockQuantity');
		// QT Pro Plugin
		$this->registerPageAction('update','update');
		$this->registerPageAction('update2','update2');
		$this->registerPageAction('updatesort','updatesort');
		$this->registerPageAction('updatePrice','updatePrice');
		$this->registerPageAction('updateMsrp','updateMsrp');
		$this->registerPageAction('addNewSerial','addNewSerial');
		$this->registerPageAction('removeSerial','removeSerial');
		$this->registerPageAction('specialOrder','specialOrder');
        $this->registerPageAction('updateAll', 'updateAll');
        $this->registerPageAction('removeImage','removeImage');
        $this->registerPageAction('updateImages', 'updateImages');
        $this->registerPageAction('copyImages','copyImages');
        
		
		if(AM_USE_SORT_ORDER) {
			$this->registerPageAction('moveOption','moveOption');
			$this->registerPageAction('moveOptionValue','moveOptionValue');
		}
		
//----------------------------
// Change: Add download attributes function for AM
// @author Urs Nyffenegger ak mytool
// Function: register PageActions for Download options
//-----------------------------

		$this->registerPageAction('addDownloadAttributeToProduct','addDownloadAttributeToProduct');
		$this->registerPageAction('updateDownloadAttributeToProduct','updateDownloadAttributeToProduct');
		$this->registerPageAction('removeDownloadAttributeToProduct','removeDownloadAttributeToProduct');
//----------------------------
// EOF Change: download attributes for AM
//-----------------------------


	}
	
	//----------------------------------------------- page actions

	/**
	 * Adds the selected attribute to the current product
	 * @access public
	 * @author Sam West aka Nimmit - osc@kangaroopartners.com
	 * @param $get $_GET
	 * @return void
	 */
	function addAttributeToProduct($get) {
		//quantity_id
		$this->getAndPrepare('option_id', $get, $optionId);
		$this->getAndPrepare('option_value_id', $get, $optionValueId);
		$this->getAndPrepare('price', $get, $price);
		$this->getAndPrepare('quantity_id', $get, $quantity_id);
		$this->getAndPrepare('model_no', $get, $model_no);
		$this->getAndPrepare('serial_no', $get, $serial_no);
		$this->getAndPrepare('quantity', $get, $quantity);
		$this->getAndPrepare('prefix', $get, $prefix);
		$this->getAndPrepare('sortOrder', $get, $sortOrder);
        
		if((empty($price))||($price=='0')){
			$price='0.0000';
		}else{
			if((empty($prefix))||($prefix==' ')){
				$prefix='+';
			}
		}
		if(empty($prefix)){
			$prefix=' ';
		}

		$data = array(
			'products_id' => $this->intPID,
			'options_id' => $optionId,
			'options_values_id' => $optionValueId,
			'options_values_price' => $price,
			'options_upc' => $quantity_id,
			'options_model_no' => $model_no,
			'options_serial_no' => $serial_no,
			'options_quantity' => $quantity,
			'price_prefix' => $prefix
		);

        if (AM_USE_MPW) {
          $this->getAndPrepare('weight', $get, $weight);
          $this->getAndPrepare('weight_prefix', $get, $weight_prefix);
        
          if((empty($weight))||($weight=='0')){
            $weight='0.0000';
          }else{
            if((empty($weight_prefix))||($weight_prefix==' ')){
              $weight_prefix='+';
            }
          }
          if(empty($weight_prefix)){
            $weight_prefix=' ';
          }
          
          $data['options_values_weight'] = $weight;
          $data['weight_prefix'] = $weight_prefix;
        }
		
		if (AM_USE_SORT_ORDER) {
		
			// changes by mytool
			// get highest sort order value
			
			$insertIndex = -1;
			
			$result = $this -> getSortedProductAttributes( AM_FIELD_OPTION_SORT_ORDER );
			
			// search for the current Sort Order where the new value needs to be added
			$i = -1;
			while ( list($key, $val) = each($result) ) {
   				$i++;
   				if( $val['options_id'] == $optionId ){
   					$insertIndex = $i;
   				}
   			}

			// if InsertIndex is still -1 then this is a new option and will be added at the end
			if($insertIndex > -1){
				$i = -1;
				$newArray = array();
				
				for ($n=0; $n < count($result) ; $n++){
					$i++;
   					if( $i == $insertIndex ){
 						$i++;
   						$data[AM_FIELD_OPTION_SORT_ORDER] = $i;
  						$newArray[$i] = $result[$n]; 
  					} else {
  						$result[$n][AM_FIELD_OPTION_SORT_ORDER] = $i; 
   						$newArray[$i] = $result[$n]; 
   					}
   				}
				
				$this->updateSortedProductArray($newArray);
				
			} else {
				$lastrow = end($result);
	   			$data[AM_FIELD_OPTION_SORT_ORDER] = (int)$lastrow[AM_FIELD_OPTION_SORT_ORDER] + 1;
			}
			// EO mytool
		}
		
		// Check if product is a missusing Size and Color
        $check_query = tep_db_query("SELECT categories_id from products_to_categories where products_id = '".$data['products_id']."'");
        $check = tep_db_fetch_array($check_query);
        
        if(($data['options_id']=='175' ||  $data['options_id']=='255' || $data['options_id']=='256') && ($check['categories_id'] == '45')){
           
        echo'<div id="boxes2">
            <div id="dialog2" class="window backspace feedback_content popup" style="width:50%; padding:20px;">
                <a class="close agree" style="font-size:16px; float:right; color:#fff;" onclick="closePopup2();"><i class="fa fa-times" style="font-size: 12px; width: 30px; height: 30px;"></i></a>
                <div class="text_part" style="margin-top:0px;">
                    <h3>This is not an appropriate use of Size & Color as this is not a wearable product.</h3>
                </div>
            </div>
        </div>';
            
            
         } elseif ($data['options_id'] <> '176' && ($check['categories_id'] == '45')){
            echo'<div id="boxes2">
            <div id="dialog2" class="window backspace feedback_content popup" style="width:50%; padding:20px;">
                <a class="close agree" style="font-size:16px; float:right; color:#fff;" onclick="closePopup2();"><i class="fa fa-times" style="font-size: 12px; width: 30px; height: 30px;"></i></a>
                <div class="text_part" style="margin-top:0px;">
                    <h1>This sure looks like a kite to me so please use the <u>existing</u> "Kite Size" option</h1>
                </div>
            </div>
        </div>';
            
            
        } else {
		amDB::perform(TABLE_PRODUCTS_ATTRIBUTES, $data);
        }
	}
	
	/**
	 * Adds an existing option value to a product
	 * @see addAttributeToProduct()
	 */
	function addOptionValueToProduct($get) {
		$this->addAttributeToProduct($get);
	}
	
	/**
	 * Adds a new option value to the database then assigns it to the product
	 * @author Sam West aka Nimmit - osc@kangaroopartners.com
	 * @param $get $_GET
	 * @return void
	 */
	function addNewOptionValueToProduct($get) {
		$returnInfo = $this->addOptionValue($get);
		$get['option_value_id'] = $returnInfo['selectedOptionValue'];
		$this->addAttributeToProduct($get);
	}
	
	/**
	 * Removes a specific option and its option values from the current product
	 * @access public
	 * @author Sam West aka Nimmit - osc@kangaroopartners.com
	 * @param $get $_GET
	 * @return void
	 */
	function removeOptionFromProduct($get) {
		$this->getAndPrepare('option_id',$get,$optionId);
		amDB::query("delete from ".TABLE_PRODUCTS_ATTRIBUTES." where options_id = '$optionId' and products_id = '$this->intPID'");
		
		$this->updateSortOrder();
	}
	
	/**
	 * Removes a specific option value from a the current product
	 * @access public
	 * @author Sam West aka Nimmit - osc@kangaroopartners.com
	 * @param $get $_GET
	 * @return void
	 */
	function removeOptionValueFromProduct($get) {
		$this->getAndPrepare('option_id',$get,$optionId);
		$this->getAndPrepare('option_value_id',$get,$optionValueId);
		
		$this->getAndPrepare('admin', $get, $admin);
		$check_attributes_query = tep_db_query("select pa.options_serial_no, pov.products_options_values_name, pd.products_name from products_attributes pa, products_options_values pov, products p, products_description pd where p.products_id = pa.products_id and pd.products_id = p.products_id and p.products_id = '$this->intPID' and pov.products_options_values_id = '$optionValueId' and pa.options_values_id = pov.products_options_values_id");
		$check_attributes = tep_db_fetch_array($check_attributes_query);
			
		$admin_query =  tep_db_query("select admin_firstname from admin where admin_id = '".$admin."'");
		$admin2 = tep_db_fetch_array ($admin_query);
		
		$data2 = array( 'user_id' => $admin2['admin_firstname'],
		'action' => 'deleted '.$check_attributes['products_name'].' attribute '.$check_attributes['products_options_values_name'].' ',
		'old_data' => $check_attributes['options_serial_no'],
		
		);
		tep_db_perform('change_log' ,$data2);
		
		amDB::query("delete from ".TABLE_PRODUCTS_ATTRIBUTES." where options_id = '$optionId' and options_values_id = '$optionValueId' and products_id = '$this->intPID'");
		
		$this->updateSortOrder();
	}
	
	function removeSerial($get) {
		$this->getAndPrepare('option_id',$get,$optionId);
		$this->getAndPrepare('option_value_id',$get,$optionValueId);
		$this->getAndPrepare('products_attributes_id',$get, $attributeid);
		
		$this->getAndPrepare('admin', $get, $admin);
		$check_attributes_query = tep_db_query("select pa.options_serial_no, pov.products_options_values_name, pd.products_name from products_attributes pa, products_options_values pov, products p, products_description pd where p.products_id = pa.products_id and pd.products_id = p.products_id and p.products_id = '$this->intPID' and pov.products_options_values_id = '$optionValueId' and products_attributes_id = '".$attributeid."' and pa.options_values_id = pov.products_options_values_id");
		$check_attributes = tep_db_fetch_array($check_attributes_query);
			
		$admin_query =  tep_db_query("select admin_firstname from admin where admin_id = '".$admin."'");
		$admin2 = tep_db_fetch_array ($admin_query);
		
		$data2 = array( 'user_id' => $admin2['admin_firstname'],
		'action' => 'deleted '.$check_attributes['products_name'].' attribute '.$check_attributes['products_options_values_name'].' ',
		'old_data' => $check_attributes['options_serial_no'],
		
		);
		tep_db_perform('change_log' ,$data2);
		
		amDB::query("delete from ".TABLE_PRODUCTS_ATTRIBUTES." where options_id = '$optionId' and options_values_id = '$optionValueId' and products_id = '$this->intPID' and products_attributes_id= '$attributeid'");
		
		$this->updateSortOrder();
	}
	
	
//----------------------------
// Change: Add download attributes function for AM
// @author Urs Nyffenegger ak mytool
// Function: Add, delete and edit Download options
//-----------------------------

	function updateDownloadAttributeToProduct($get) {
		$this->getAndPrepare('option_id',$get,$optionId);
		$this->getAndPrepare('option_value_id',$get,$optionValueId);
		$this->getAndPrepare('products_attributes_filename',$get,$products_attributes_filename);
		$this->getAndPrepare('products_attributes_maxdays',$get,$products_attributes_maxdays);
		$this->getAndPrepare('products_attributes_maxcount',$get,$products_attributes_maxcount);
		$this->getAndPrepare('products_attributes_id',$get,$products_attributes_id);

		amDB::query('update '.TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD.' SET products_attributes_filename=\'' .$products_attributes_filename .'\', products_attributes_maxdays = '.$products_attributes_maxdays.', products_attributes_maxcount='.$products_attributes_maxcount.' where products_attributes_id = '.$products_attributes_id );
	}
	
	function addDownloadAttributeToProduct($get) {
		$this->getAndPrepare('option_id',$get,$optionId);
		$this->getAndPrepare('option_value_id',$get,$optionValueId);
		$this->getAndPrepare('products_attributes_filename',$get,$products_attributes_filename);
		$this->getAndPrepare('products_attributes_maxdays',$get,$products_attributes_maxdays);
		$this->getAndPrepare('products_attributes_maxcount',$get,$products_attributes_maxcount);
		$this->getAndPrepare('products_attributes_id',$get,$products_attributes_id);

		amDB::query('insert into '.TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD.' (products_attributes_id, products_attributes_filename, products_attributes_maxdays, products_attributes_maxcount) values('.$products_attributes_id.',\''.$products_attributes_filename.'\', '.$products_attributes_maxdays.', '.$products_attributes_maxcount.')');
	}
	
	function removeDownloadAttributeToProduct($get) {
		$this->getAndPrepare('option_id',$get,$optionId);
		$this->getAndPrepare('option_value_id',$get,$optionValueId);
		$this->getAndPrepare('products_attributes_id',$get,$products_attributes_id);

		amDB::query('delete from '.TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD.' where products_attributes_id='.$products_attributes_id );
	}
//----------------------------
// EOF Change: download attributes for AM
//-----------------------------


// Begin QT Pro Plugin	
    /**
     * Checks product quantity and sets product status consider STOCK_ALLOW_CHECKOUT setting
     * @access public
     * @author Peter aka RusNN 
     * @param $quantity Quantity of product in stock
     * @return void
     */
    function checkProductStatus($quantity) {
        if (($quantity < 1) && (STOCK_ALLOW_CHECKOUT == 'false')) {
          $data = array(
            'products_status' => '0'
          );
          amDB::perform(TABLE_PRODUCTS, $data, 'update', "products_id='" . $this->intPID . "'");
        }
    }

    /**
     * Sets the product quantity to a value calculating based on a sum of all products stock options
     * @access public
     * @author Peter aka RusNN 
     * @param void
     * @return void
     */
    function repairStock() {
        $query = amDB::query("select sum(products_stock_quantity) as summa from " . TABLE_PRODUCTS_STOCK . " where products_id='" . $this->intPID . "' and products_stock_quantity>0");
        $list = amDB::fetchArray($query);
        $data = array(
            'products_quantity' => (empty($list['summa'])) ? '0' : $list['summa']
        );
        amDB::perform(TABLE_PRODUCTS, $data, 'update', "products_id='" . $this->intPID . "'");
        
        $this->checkProductStatus($list['summa']);
    }

	/**
	 * Removes a specific stock option value from a the current product // for QT pro Plugin
	 * @access public
	 * @author Greg A. aka phocea - 
	 * @param $get $_GET
	 * @return void
	 */
	function removeStockOptionValueFromProduct($get) {
		$this->getAndPrepare('option_id',$get,$optionId);
		amDB::query("delete from ".TABLE_PRODUCTS_STOCK." where products_stock_id = '$optionId'");// and products_id = '$this->intPID'");

        $this->repairStock();
	}

    /**
     * Adds the selected attribute to the current product
     * @access public
     * @author Sam West aka Nimmit - osc@kangaroopartners.com
     * @author correction made by RusNN
     * @param $get $_GET
     * @return void
     */
	function addStockToProduct($get) {
        $inputok = true;
        
		// Work out how many option were sent
		while(list($v1,$v2)=each($get)) {
		  if (preg_match("/^option(\d+)$/",$v1,$m1)) {
		    if (is_numeric($v2) and ($v2==(int)$v2)) {
              $val_array[]=$m1[1]."-".$v2;
            } else {
              $inputok = false;
            }
      	  }
    	}
    		
    	if (($inputok)) {
            $this->getAndPrepare('stockQuantity',$get,$stockQuantity);

            if (!empty($val_array)) {
              // Products has at least one assigned option or options combination, so set quantity for option combination and total options quantity for product itself
    		  sort($val_array, SORT_NUMERIC);
    		  $val=join(",",$val_array);

    		  $q = amDB::query("select products_stock_id as stock_id from " . TABLE_PRODUCTS_STOCK . " where products_id ='$this->intPID' and products_stock_attributes='" . $val . "' order by products_stock_attributes");
    		  if (amDB::numRows($q) > 0) {
    			  $stock_item = amDB::fetchArray($q);
    			  $stock_id = $stock_item['stock_id'];
    			  if ($stockQuantity=intval($stockQuantity)) {
                      $data = array(
                          'products_stock_quantity' => (int)$stockQuantity
                      );
                      // New value for option combination - updates DB
    				  amDB::perform(TABLE_PRODUCTS_STOCK, $data, 'update', "products_stock_id=$stock_id");
    			  } else {
                      if (AM_DELETE_ZERO_STOCK) {
                        // If user inputs 0 (zero), delete such combination
    				    amDB::query("delete from " . TABLE_PRODUCTS_STOCK . " where products_stock_id=$stock_id");
                      } else {
                        // Set combination qty to 0
                        $data = array(
                            'products_stock_quantity' => '0'
                        );
                        // New value for option combination - updates DB
                        amDB::perform(TABLE_PRODUCTS_STOCK, $data, 'update', "products_stock_id=$stock_id");
                      }
        		  }
      		  } else {
                  // No such combination, insert new one
                  $data = array(
                     'products_id' => $this->intPID,
                     'products_stock_attributes' => $val,
                     'products_stock_quantity' => (int)$stockQuantity
                  );
        		  amDB::perform(TABLE_PRODUCTS_STOCK, $data);
        	  }
              
              $this->repairStock();
            } else {
              // No options available for the product, so sets the overall product quantity
              $data = array(
                  'products_quantity' => (empty($stockQuantity)) ? '0' : $stockQuantity
              );
              amDB::perform(TABLE_PRODUCTS, $data, 'update', "products_id='" . $this->intPID . "'");
              
              $this->checkProductStatus($stockQuantity);
            }
    	}
	}

	/**
	 * Updates the quantity on the products stock table
	 * @author Phocea
	 * @param $get $_GET
	 * @return void
	 */
	function updateProductStockQuantity($get) {
		$this->getAndPrepare('products_stock_id', $get, $products_stock_id);
		$this->getAndPrepare('productStockQuantity', $get, $productStockQuantity);		
		$data = array( 
			'products_stock_quantity' => $productStockQuantity
		);
		amDB::perform(TABLE_PRODUCTS_STOCK,$data, 'update',"products_stock_id='$products_stock_id'");

        $this->repairStock();
	}
// End QT Pro Plugin

	/**
	 * Updates the price and prefix in the products attribute table
	 * @author Sam West aka Nimmit - osc@kangaroopartners.com
	 * @param $get $_GET
	 * @return void
	 */
	function update($get) {
		$this->getAndPrepare('option_id', $get, $optionId);
		$this->getAndPrepare('option_value_id', $get, $optionValueId);
		$this->getAndPrepare('products_attributes_id',$get, $attributeid);
		$this->getAndPrepare('price', $get, $price);
		$this->getAndPrepare('quantity_id', $get, $quantity_id);
		$this->getAndPrepare('model_no', $get, $model_no);
		$this->getAndPrepare('serial_no', $get, $serial_no);
		$this->getAndPrepare('quantity', $get, $quantity);
		$this->getAndPrepare('prefix', $get, $prefix);
		$this->getAndPrepare('sortOrder', $get, $sortOrder);
		
		if((empty($price))||($price=='0')){
		  $price='0.0000';
		}else{
		  if((empty($prefix))||($prefix==' ')){
			$prefix='+';
		  }
		}
		
		$data = array( 
			'options_values_price' => $price,
			'price_prefix' => $prefix,
		);

        if (AM_USE_MPW) {
          $this->getAndPrepare('weight', $get, $weight);
          $this->getAndPrepare('weight_prefix', $get, $weight_prefix);

          if((empty($weight))||($weight=='0')){
            $weight='0.0000';
          }else{
            if((empty($weight_prefix))||($weight_prefix==' ')){
              $weight_prefix='+';
            }
          }
          
          $data['options_values_weight'] = $weight;
          $data['weight_prefix'] = $weight_prefix;
        }
          $data['options_upc'] = $quantity_id;
          $data['options_model_no'] = $model_no;
          $data['options_serial_no'] = $serial_no;
          $data['options_quantity'] = $quantity;

		if (AM_USE_SORT_ORDER) {
			$data[AM_FIELD_OPTION_VALUE_SORT_ORDER] = $sortOrder;
		}
		
		$this->getAndPrepare('admin', $get, $admin);
		
		$check_attributes_query = tep_db_query("select pov.products_options_values_name, pa.options_serial_no, pa.options_quantity, pd.products_name from products_attributes pa, products_options_values pov, products p, products_description pd where p.products_id = pa.products_id and pd.products_id = p.products_id and p.products_id = '$this->intPID' and products_attributes_id = '".$attributeid."' and pa.options_values_id = pov.products_options_values_id");
		$check_attributes = tep_db_fetch_array($check_attributes_query);
			
		$admin_query =  tep_db_query("select admin_firstname from admin where admin_id = '".$admin."' ");
		$admin2 = tep_db_fetch_array ($admin_query);
		
		if (($check_attributes['options_serial_no'] == $serial_no) && ($check_attributes['options_quantity'] != $quantity)){
		
		$data2 = array( 'user_id' => $admin2['admin_firstname'],
		'action' => 'changed '.$check_attributes['products_name'].' attribute '.$check_attributes['products_options_values_name'].' quantity',
		'old_data' => $check_attributes['options_quantity'],
		'new_data' => $quantity,
		);
		} elseif (($check_attributes['options_serial_no'] != $serial_no) && ($check_attributes['options_quantity'] == $quantity)){
		
		$data2 = array( 'user_id' => $admin2['admin_firstname'],
		'action' => 'changed '.$check_attributes['products_name'].' attribute '.$check_attributes['products_options_values_name'].' serial no',
		'old_data' => $check_attributes['options_serial_no'],
		'new_data' => $serial_no,
		);
		}
			
		tep_db_perform('change_log' ,$data2);
		
		amDB::perform(TABLE_PRODUCTS_ATTRIBUTES,$data, 'update',"products_id='$this->intPID' and options_id='$optionId' and options_values_id='$optionValueId'");

	}
	
	
	function update2($get) {
		$this->getAndPrepare('option_id', $get, $optionId);
		$this->getAndPrepare('option_value_id', $get, $optionValueId);
		$this->getAndPrepare('products_attributes_id',$get, $attributeid);
		$this->getAndPrepare('quantity_id', $get, $quantity_id);
		$this->getAndPrepare('model_no', $get, $model_no);
		$this->getAndPrepare('serial_no', $get, $serial_no);
		$this->getAndPrepare('quantity', $get, $quantity);
		
          $data['options_upc'] = $quantity_id;
          $data['options_model_no'] = $model_no;
          $data['options_serial_no'] = $serial_no;
          $data['options_quantity'] = $quantity;

		
		
		$this->getAndPrepare('admin', $get, $admin);
		
		$check_attributes_query = tep_db_query("select pov.products_options_values_name, pa.options_serial_no, pa.options_quantity, pd.products_name from products_attributes pa, products_options_values pov, products p, products_description pd where p.products_id = pa.products_id and pd.products_id = p.products_id and p.products_id = '$this->intPID' and products_attributes_id = '".$attributeid."' and pa.options_values_id = pov.products_options_values_id");
		$check_attributes = tep_db_fetch_array($check_attributes_query);
			
		$admin_query =  tep_db_query("select admin_firstname from admin where admin_id = '".$admin."' ");
		$admin2 = tep_db_fetch_array ($admin_query);
		
		if (($check_attributes['options_serial_no'] == $serial_no) && ($check_attributes['options_quantity'] != $quantity)){
		
		$data2 = array( 'user_id' => $admin2['admin_firstname'],
		'action' => 'changed '.$check_attributes['products_name'].' attribute '.$check_attributes['products_options_values_name'].' quantity',
		'old_data' => $check_attributes['options_quantity'],
		'new_data' => $quantity,
		);
		} elseif (($check_attributes['options_serial_no'] != $serial_no) && ($check_attributes['options_quantity'] == $quantity)){
		
		$data2 = array( 'user_id' => $admin2['admin_firstname'],
		'action' => 'changed '.$check_attributes['products_name'].' attribute '.$check_attributes['products_options_values_name'].' serial no',
		'old_data' => $check_attributes['options_serial_no'],
		'new_data' => $serial_no,
		);
		}
			
		tep_db_perform('change_log' ,$data2);
			
		amDB::perform(TABLE_PRODUCTS_ATTRIBUTES,$data, 'update',"products_id='$this->intPID' and options_id='$optionId' and options_values_id='$optionValueId' and products_attributes_id='$attributeid' ");
		
		

	}
	
	function updatesort($get) {
		$this->getAndPrepare('option_id', $get, $optionId);
		$this->getAndPrepare('option_value_id', $get, $optionValueId);
		$this->getAndPrepare('sortOrder', $get, $sortOrder);
		if (AM_USE_SORT_ORDER) {
			$data[AM_FIELD_OPTION_VALUE_SORT_ORDER] = $sortOrder;
		}
		amDB::perform(TABLE_PRODUCTS_ATTRIBUTES,$data, 'update',"products_id='$this->intPID' and options_id='$optionId' and options_values_id='$optionValueId'");
	}
	
	function updatePrice($get) {
		$this->getAndPrepare('option_id', $get, $optionId);
		$this->getAndPrepare('option_value_id', $get, $optionValueId);
		$this->getAndPrepare('price', $get, $price);
		$this->getAndPrepare('prefix', $get, $prefix);
		
		if((empty($price))||($price=='0')){
		  $price='0.0000';
		}else{
		  if((empty($prefix))||($prefix==' ')){
			$prefix= $prefix;
		  }
		}
		
		$data = array(
			'options_values_price' => $price,
			'price_prefix' => $prefix,
		);
		amDB::perform(TABLE_PRODUCTS_ATTRIBUTES,$data, 'update',"products_id='$this->intPID' and options_id='$optionId' and options_values_id='$optionValueId'");

	}
	
	function updateMsrp($get) {
		$this->getAndPrepare('option_id', $get, $optionId);
		$this->getAndPrepare('option_value_id', $get, $optionValueId);
		$this->getAndPrepare('msrp', $get, $msrp);
		
		$data = array(
			'options_values_msrp' => $msrp,
			'price_prefix' => '',
			
		);
		
		amDB::perform(TABLE_PRODUCTS_ATTRIBUTES,$data, 'update',"products_id='$this->intPID' and options_id='$optionId' and options_values_id='$optionValueId'");

	}
		
	function addNewSerial($get) {
		$this->getAndPrepare('option_id', $get, $optionId);
		$this->getAndPrepare('option_value_id', $get, $optionValueId);
		$this->getAndPrepare('msrp', $get, $msrp);
		$this->getAndPrepare('price', $get, $price);
		$this->getAndPrepare('invoice', $get, $invoice);
		$this->getAndPrepare('prefix', $get, $prefix);
		$this->getAndPrepare('sortOrder', $get, $sortOrder);
		
		if((empty($price))||($price=='0')){
		  $price='0.0000';
		}else{
		  if((empty($prefix))||($prefix==' ')){
			$prefix= $prefix;
		  }
		}
		
		$data = array(
			'products_id' => $this->intPID,
			'options_id' => $optionId,
			'options_values_id' => $optionValueId,
			'options_values_msrp' => $msrp,
			'options_values_price' => $price,
			'options_invoice_price' => $invoice,
			'options_upc' => $quantity_id,
			'options_model_no' => $model_no,
			'options_serial_no' => $serial_no,
			'options_quantity' => '1',
			'price_prefix' => $prefix
		);

        if (AM_USE_MPW) {
          $this->getAndPrepare('weight', $get, $weight);
          $this->getAndPrepare('weight_prefix', $get, $weight_prefix);

          if((empty($weight))||($weight=='0')){
            $weight='0.0000';
          }else{
            if((empty($weight_prefix))||($weight_prefix==' ')){
              $weight_prefix='+';
            }
          }
          
          $data['options_values_weight'] = $weight;
          $data['weight_prefix'] = $weight_prefix;
        }

		if (AM_USE_SORT_ORDER) {
			$data[AM_FIELD_OPTION_VALUE_SORT_ORDER] = $sortOrder;
		}
		
		$data2['attribute_special_order'] = '0';
		
		amDB::perform(TABLE_PRODUCTS_ATTRIBUTES,$data);
		amDB::perform(TABLE_PRODUCTS_ATTRIBUTES,$data2, 'update',"products_id='$this->intPID' and options_id='$optionId' and options_values_id='$optionValueId'");

	}
	
	function specialOrder($get) {
		$this->getAndPrepare('option_id', $get, $optionId);
		$this->getAndPrepare('option_value_id', $get, $optionValueId);
		$this->getAndPrepare('att_special_order', $get, $attspec);
		
		if ($attspec == 'true'){ $attspec1 = 1;}
		if ($attspec == 'false'){ $attspec1 = 0;}
		
		
		$data = array(
			
			'options_id' => $optionId,
			'options_values_id' => $optionValueId,
			'attribute_special_order' => $attspec1
		);


		amDB::perform(TABLE_PRODUCTS_ATTRIBUTES,$data, 'update',"products_id='$this->intPID' and options_id='$optionId' and options_values_id='$optionValueId'");

	}
	
	//----------------------------------------------- page actions end
	
	/**
	 * Returns all or the options and values in the database
	 * @access public
	 * @author Sam West aka Nimmit - osc@kangaroopartners.com
	 * @return array
	 */
	function getAllProductOptionsAndValues($reset = false) {
		if(0 === count($this->arrAllProductOptionsAndValues)|| true === $reset) {
			$this->arrAllProductOptionsAndValues = array();
			
			$allOptionsAndValues = $this->getAllOptionsAndValues();
			
//----------------------------
// Change: Add download attributes function for AM
// @author Urs Nyffenegger ak mytool
// Function: change query string to add the Download Table fields
//-----------------------------
			$queryString = "select pa.*, pad.products_attributes_filename, pad.products_attributes_maxdays, pad.products_attributes_maxcount from ".TABLE_PRODUCTS_ATTRIBUTES." as pa INNER JOIN ".TABLE_PRODUCTS_OPTIONS." po ON pa.options_id=po.products_options_id";  
			$queryString .= " LEFT JOIN ".TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " pad ON pa.products_attributes_id = pad.products_attributes_id";
			$queryString .= " where products_id = '$this->intPID' AND language_id=".(int)$this->getSelectedLanaguage()." order by ";
			$queryString .= !AM_USE_SORT_ORDER ?  "products_options_name, pa.products_attributes_id" : AM_FIELD_OPTION_VALUE_SORT_ORDER;
//----------------------------
// EOF Change: download attributes for AM
//-----------------------------
			$query = amDB::query($queryString);
			
			$optionsId = null;
			while($res = amDB::fetchArray($query)) {
			//print_R($res);
				if($res['options_id'] != $optionsId) {
					$optionsId = $res['options_id'];
					$this->arrAllProductOptionsAndValues[$optionsId]['name'] = $allOptionsAndValues[$optionsId]['name'];
				//	echo $this->arrAllProductOptionsAndValues[$optionsId]['name'];
				}
				if(!$allOptionsAndValues[$optionsId]['values'][$res['options_values_id']]){
					$getval=tep_db_fetch_array(tep_db_query("select products_options_values_name from products_options_values where products_options_values_id='".$res['options_values_id']."' limit 1"));
					if($getval){
						$allOptionsAndValues[$optionsId]['values'][$res['options_values_id']]=$getval['products_options_values_name'];
					}
				}
				$this->arrAllProductOptionsAndValues[$optionsId]['values'][$res['options_values_id']]['name'] = $allOptionsAndValues[$optionsId]['values'][$res['options_values_id']];
				$this->arrAllProductOptionsAndValues[$optionsId]['values'][$res['options_values_id']]['price'] = $res['options_values_price'];
				$this->arrAllProductOptionsAndValues[$optionsId]['values'][$res['options_values_id']]['msrp'] = $res['options_values_msrp'];
				$this->arrAllProductOptionsAndValues[$optionsId]['values'][$res['options_values_id']]['invoice'] = $res['options_invoice_price'];
				$this->arrAllProductOptionsAndValues[$optionsId]['values'][$res['options_values_id']]['upc'] = $res['options_upc'];
				$this->arrAllProductOptionsAndValues[$optionsId]['values'][$res['options_values_id']]['model_no'] = $res['options_model_no'];
				$this->arrAllProductOptionsAndValues[$optionsId]['values'][$res['options_values_id']]['serial_no'] = $res['options_serial_no'];
				$this->arrAllProductOptionsAndValues[$optionsId]['values'][$res['options_values_id']]['quantity'] = $res['options_quantity'];
				$this->arrAllProductOptionsAndValues[$optionsId]['values'][$res['options_values_id']]['prefix'] = $res['price_prefix'];
				$this->arrAllProductOptionsAndValues[$optionsId]['values'][$res['options_values_id']]['attribute_special_order'] = $res['attribute_special_order'];
				
        
                if (AM_USE_MPW) {
                  $this->arrAllProductOptionsAndValues[$optionsId]['values'][$res['options_values_id']]['weight'] = $res['options_values_weight'];
                  $this->arrAllProductOptionsAndValues[$optionsId]['values'][$res['options_values_id']]['weight_prefix'] = $res['weight_prefix'];
                }
//----------------------------
// Change: Add download attributes function for AM
// @author Urs Nyffenegger ak mytool
// Function: get the new Attributes
//-----------------------------
				$this->arrAllProductOptionsAndValues[$optionsId]['values'][$res['options_values_id']]['products_attributes_id'] = $res['products_attributes_id'];
				$this->arrAllProductOptionsAndValues[$optionsId]['values'][$res['options_values_id']]['products_attributes_filename'] = $res['products_attributes_filename'];
				$this->arrAllProductOptionsAndValues[$optionsId]['values'][$res['options_values_id']]['products_attributes_maxdays'] = $res['products_attributes_maxdays'];
				$this->arrAllProductOptionsAndValues[$optionsId]['values'][$res['options_values_id']]['products_attributes_maxcount'] = $res['products_attributes_maxcount'];
//----------------------------
// EOF Change: download attributes for AM
//-----------------------------
		
				if (AM_USE_SORT_ORDER) {
					$this->arrAllProductOptionsAndValues[$optionsId]['values'][$res['options_values_id']]['sortOrder'] = $res[AM_FIELD_OPTION_VALUE_SORT_ORDER];
				}
			}
		}
		return $this->arrAllProductOptionsAndValues;
	}
	
	function moveOptionUp() {
		$this->moveOption();
	}
	
	function moveOptionDown() {
		$this->moveOption('down');
	}
	
	function moveOption($get) {
		
		$extraValues = $this->getExtraValues($get['gets']);
		$direction = $get['dir'];
		$changes = false;
		$newArray = array();
		
		// Get current State -- is this necessary? or could we take the getAllProductOptionsAndValues?? i'll see later
		$sortedArray = $this->getSortedProductAttributes( AM_FIELD_OPTION_SORT_ORDER );	

		// now create new array with the optionsID unique
		$i =  - 1;
		$firstRow = current($sortedArray);
		$start_ID = $firstRow['options_id'];
		
		reset($sortedArray);
		
		while ( list($key, $val) = each($sortedArray)) {

			if( $val['options_id'] != $start_ID ){
				$i =  - 1;
				$start_ID  = $val['options_id'];
			} 
			
			$i++;
			$optionsArray[ $val['options_id'] ][$i] = $val;
			
		}
		
		// get position so we can swap
		$positionArray = array_keys($optionsArray);
		$position = array_search( (int)$extraValues['option_id'], $positionArray);
		
		if($direction == 'up'){
		
			if( $position > 0 ){
				$changes = true;
				$prevItem = $positionArray[ $position - 1];
				$ThisItem = $positionArray[$position];
				$positionArray[$position] = $prevItem;
				$positionArray[$position - 1] = $ThisItem;
			}
		
		} else {
		
			if( $position <  ( count($positionArray)-1 ) ){
				$changes = true;
				$nextItem = $positionArray[ $position + 1];
				$ThisItem = $positionArray[$position];
				$positionArray[$position] = $nextItem;
				$positionArray[$position + 1] = $ThisItem;
			}
		
		}

		// set new Sortvalues 
		$i =  - 1;
		while ( list($key, $val) = each($positionArray)) {
			while ( list($okey, $oval) = each( $optionsArray[ $val ]) ) {
					$i++;
					$oval[AM_FIELD_OPTION_SORT_ORDER] = $i;
					$newArray[$i] = $oval;
			 }
		}

		// update Database
		if($changes){
			$this->updateSortedProductArray($newArray);
		}
	}
	
	function moveOptionValue($get) {
	
		$extraValues = $this->getExtraValues($get['gets']);
		$direction = $get['dir'];
		$changes = false;
		$sortedArray = array();
		$newArray = array();

		$sortedArray = $this->getSortedProductAttributes( AM_FIELD_OPTION_VALUE_SORT_ORDER );
		
		$i = -1;
		
		// filter array
		while ( list($key, $val) = each($sortedArray) ) {
   			if( $val['options_id'] == $extraValues['option_id'] ){
   				$i++;
   				$newArray[$val[AM_FIELD_OPTION_VALUE_SORT_ORDER]] = $val;
   			}
   		}

		// get first and Last Row, so we can determine lowest and higest Sort order value later
		reset($newArray);
		
		$first = current($newArray);
		$firstSortValue = (int)$first[AM_FIELD_OPTION_VALUE_SORT_ORDER];
		$lastSortValue = $firstSortValue + count($newArray) - 1;
		
		while ( list($key, $val) = each($newArray) ) {
   			if( $val['products_attributes_id'] == $extraValues['products_attributes_id'] ){
    				$startSort = $val[AM_FIELD_OPTION_VALUE_SORT_ORDER];
			}
		}
		
		if($direction == 'up'){
			// ceiling_ only change if its not the top item
			if ($startSort > (int)$firstSortValue ){
				$changes = true;
				$newArray[$startSort][AM_FIELD_OPTION_VALUE_SORT_ORDER] = (int)$startSort - 1;
				$newArray[$startSort-1][AM_FIELD_OPTION_VALUE_SORT_ORDER] = (int)$startSort;
			}
		}else{
			// ceiling only change if its not the bottom item
			if ( $startSort < (int)$lastSortValue ){
				$changes = true;
				$newArray[$startSort][AM_FIELD_OPTION_VALUE_SORT_ORDER] = (int)$startSort + 1;
				$newArray[$startSort+1][AM_FIELD_OPTION_VALUE_SORT_ORDER] = (int)$startSort;
			}
		}
		
		// update Database
		if($changes){
			$this->updateSortedProductArray($newArray);
		}
		
	}
	
	function getExtraValues($gets){
		$arrExtraValues = array();
		$valuePairs = array();
		
		if(strpos($gets,'|')) 
			$valuePairs = explode('|',$gets);
		else 
			$valuePairs[] = $gets;
		
		foreach($valuePairs as $pair)
			if(strpos($pair,':')) {
				list($extraKey, $extraValue) = explode(':',$pair);	
				$arrExtraValues[$extraKey] = $extraValue;
			}
			
		return $arrExtraValues;	
	}
	
	function getSortedProductAttributes( $sortfield ){
	
		$sortedArray = array();
	
		$queryString = "select products_attributes_id, options_id, products_options_sort_order" .
						" from ".TABLE_PRODUCTS_ATTRIBUTES.
						" where products_id=".$this->intPID;
						
/*		if( $optionsID > -1){			
			$queryString .=	" AND options_id=".$optionsID;
		}
*/			
		$queryString .=	" ORDER BY ".$sortfield." asc, options_id asc";
		
		$result = amDB::getAll($queryString);
		
		//$i = (int)$result[0][$sortfield];
		$i=0;
		
		while(list($key, $val) = each($result)) {
			// set the sorting new
			$val[AM_FIELD_OPTION_VALUE_SORT_ORDER] = $i;
			$sortedArray[$i] = $val;
			$i++;
		}
		
		return $sortedArray;
	}
	
	
	function updateSortedProductArray($newArray){
	
		reset($newArray);
		while ( list($key, $val) = each($newArray)) {
			if( !empty($val['products_attributes_id'] )){
				amDB::perform(TABLE_PRODUCTS_ATTRIBUTES,$val,'update','products_attributes_id = ' . $val['products_attributes_id'] );
			}
		}
	}
	
	function updateSortOrder(){
	
			if (AM_USE_SORT_ORDER) {
				$newArray =  $this->getSortedProductAttributes( AM_FIELD_OPTION_VALUE_SORT_ORDER );
				$this->updateSortedProductArray( $newArray );
			}
	}
    
    function removeImage($get){
		$this->getAndPrepare('option_value_id', $get, $optionValueId);
        $z = $_GET['imageNum'];
        
        $imgs_array = array(
            'variants_image_sm_'.$z.'' => '',
            'variants_image_xl_'.$z.'' => '',
            'variants_image_zoom_'.$z.'' => '' 
        );
        
         tep_db_query("DELETE FROM variants_images WHERE options_values_id = '".$optionValueId."' and parent_id = '".$_GET['products_id']."'");
         
   }
        
    function updateImages($get){
        $pid = $_GET['products_id'];
        $image_name = $_FILES['variants_image_1'];
        $i = $_POST['image_number'];
        
        foreach($_POST['add_to_images'] as $k=> $optValue){
            
          if (($_POST['remove_additional_images_'.$z.''] == 'yes') or ($_POST['delete_additional_images_'.$z.''] == 'yes')) {         $image_data_array['variants_image_zoom_'.$i.''] = '';
              $image_data_array['variants_image_xl_'.$i.''] = '';
              $image_data_array['variants_image_sm_'.$i.''] = '';
          } else {       

              $products_image = new upload('variants_image_1');
              $products_image->set_destination(DIR_FS_CATALOG_IMAGES);
              if ($products_image->parse() && $products_image->save()) {
                  $image_data_array['variants_image_zoom_'.$i.''] = $products_image->filename;
                  $image_data_array['variants_image_xl_'.$i.''] = $products_image->medium['1'];
                  $image_data_array['variants_image_sm_'.$i.''] = $products_image->small['1']; 
              }
          }

            $images_data_array = array('options_values_id' => $optValue,
          'parent_id' => $pid);
            
            $img_data_array = array_merge($image_data_array, $images_data_array);
            
            $check_vimages_query = tep_db_query("select count(*) as count from variants_images where options_values_id = '".$optValue."' and parent_id = '".$pid."'");
            $check_vimages = tep_db_fetch_array($check_vimages_query);
            
            if($check_vimages['count'] < '1'){
                tep_db_perform('variants_images', $img_data_array);
            } else {
                tep_db_perform('variants_images', $image_data_array, 'update', "options_values_id = '" .$optValue. "' and parent_id = '".$pid."'");  
            }      
        }
    }
    
    function copyImages($get){
        $pid = $_GET['products_id'];
        
        $get_desired_values_query = tep_db_query("select * from variants_images where options_values_id = '".$_POST['copy_variants_id_images']."' and parent_id = '".$pid."'");
        $get_desired_values = tep_db_fetch_array($get_desired_values_query);
        
        for($i=1; $i < 7; $i++){
            $image_data_array['variants_image_sm_'.$i.''] = $get_desired_values['variants_image_sm_'.$i.''];
            $image_data_array['variants_image_xl_'.$i.''] = $get_desired_values['variants_image_xl_'.$i.'']; $image_data_array['variants_image_zoom_'.$i.''] = $get_desired_values['variants_image_zoom_'.$i.''];      
        }
      
        foreach($_POST['add_to_images'] as $k=> $optValue){
            $check_vimages_query = tep_db_query("select count(*) as count from variants_images where options_values_id = '".$optValue."' and parent_id = '".$pid."'");
            $check_vimages = tep_db_fetch_array($check_vimages_query);
            
            $images_data_array = array('options_values_id' => $optValue,
          'parent_id' => $pid);
        
            $img_data_array = array_merge($image_data_array, $images_data_array);
            
            if($check_vimages['count'] < '1'){
                tep_db_perform('variants_images', $img_data_array);
            } else {
                tep_db_perform('variants_images', $image_data_array, 'update', "options_values_id = '" .$optValue. "' and parent_id = '".$pid."'");  
            } 
        }
        
        
    }
    
    function updateAll($get){
        $pid = (int)$_GET['products_id'];
        $data = json_decode(stripslashes($_POST['data']) , true);
        $product = array();
        $options = array();
        
        foreach($data as $k=>$v){
            if(strpos($k,'[opt]')!==false){
                $k=explode('][',$k);
                $value_id=$k[1];
            if(!isset($options[$value_id])) $options[$value_id]=array();

            $options[$value_id]['attributes_id']=$k[1];
            $vname = trim($k[2],'] ');
            $options[$value_id][$vname]=addslashes($v);
            } else {
                $k=explode('][',$k);
                $k=trim($k[1],'] ');
                $product[$k]=addslashes($v);
            }
        }
      //  print_r($product); 
	  //  print_r($options);
        
    if($product['msrp']< $product['price']){
            echo '<div id="boxes" class="overlay" >
  <div class="reminder-container">
    <div class="col-12" style="padding-top:10px;">
        <h3 style="text-align:center; text-transform:uppercase; color:#fff;">The MSRP can not be less than the price
        </h3>
        <div class="col-12" style="text-align:center;">
        <a class="close agree" style="font-size:16px;" onclick="closeReminder();">Close</a>
        </div>
     </div>
     </div>
</div>
        ';
     
    } else {
             
        $count = 0;
        if($options && $pid){
		  foreach($options as $opt){
                $serial_no = $opt['serialno'];
                $quantity = $opt['qty'];
              
              // Update Changelog
                $check_attributes_query = tep_db_query("select pov.products_options_values_name, pa.options_serial_no, pa.options_quantity, pd.products_name from products_attributes pa, products_options_values pov, products p, products_description pd where p.products_id = pa.products_id and pd.products_id = p.products_id and p.products_id = '$pid' and products_attributes_id = '".$opt['attributes_id']."' and pa.options_values_id = pov.products_options_values_id ");
		        $check_attributes = tep_db_fetch_array($check_attributes_query);
			
		        $admin_query =  tep_db_query("select admin_firstname from admin where admin_id = '".$product['loginid']."'");
                $admin2 = tep_db_fetch_array ($admin_query);

                if (($check_attributes['options_serial_no'] == $serial_no) && ($check_attributes['options_quantity'] != $quantity)){

                $data2 = array('user_id' => $admin2['admin_firstname'],
                'action' => 'changed '.$check_attributes['products_name'].' attribute '.$check_attributes['products_options_values_name'].' quantity',
                'old_data' => $check_attributes['options_quantity'],
                'new_data' => $quantity
                );
                
                tep_db_perform('change_log',$data2);
                        
                } elseif (($check_attributes['options_serial_no'] != $serial_no) && ($check_attributes['options_quantity'] == $quantity)){

                	$data2 = array( 'user_id' => $admin2['admin_firstname'],
                	'action' => 'changed '.$check_attributes['products_name'].' attribute '.$check_attributes['products_options_values_name'].' serial no',
                	'old_data' => $check_attributes['options_serial_no'],
                	'new_data' => $serial_no
                	);
                    tep_db_perform('change_log',$data2);
                }
                     
                // Update all existing Attributes
              
              
				/*$sql = "UPDATE products_attributes SET options_quantity = '".$opt['qty']."' WHERE options_id='".$_POST['option_id']."' and options_values_id = '".$_POST['value_id']."' and products_attributes_id='".(int)$opt['attributes_id']."'";
				tep_db_query($sql);
              
                $sql = "UPDATE products_attributes SET options_serial_no = '".$opt['serialno']."' WHERE options_id='".$_POST['option_id']."' and options_values_id = '".$_POST['value_id']."' and products_attributes_id='".(int)$opt['attributes_id']."'";
				tep_db_query($sql);
              */
              $data_existing_att = array(
                  'options_quantity' => $opt['qty'],
                  'options_serial_no' => $opt['serialno'],
				  'created_at' => $opt['created_at']  
              );
              
              tep_db_perform("products_attributes", $data_existing_att, "update", "options_id='".$_POST['option_id']."' and options_values_id = '".$_POST['value_id']."' and products_attributes_id='".(int)$opt['attributes_id']."'");
                
       
            if((int)$opt['temp_attributes_id'] <> ''){
                
                $sql = "INSERT INTO products_attributes (products_id, options_id, options_values_id, options_values_msrp, options_values_price, options_invoice_price, price_prefix, sort_order, attribute_sort, products_options_sort_order, options_upc, options_model_no, options_serial_no, options_quantity, attribute_special_order) VALUES ('".$_GET['products_id']."','". $_POST['option_id']."','".$_POST['value_id']."','".$msrp."','".$price."','".$invoice."','".$prefix."','0','0','".(int)$opt['sortorder']."','".$quantity_id."','".$model_no."','".$opt['serialno']."','".(int)$opt['qty']."','0')";

                 tep_db_query($sql); 
            }
                
                // Clear out special orders once extra attributes are added
                $sql = "UPDATE products_attributes SET attribute_special_order = '0' WHERE options_id='".$_POST['option_id']."' and options_values_id = '".$_POST['value_id']."' and products_id = '".$pid."'";
				tep_db_query($sql);
                
                /*
                $attribute_id = $temp_attribute_id;
                $this->getAndPrepare('option_id', $get, $optionId);
                $this->getAndPrepare('option_value_id', $get, $optionValueId);
                $this->getAndPrepare('msrp', $get, $msrp);
                $this->getAndPrepare('price', $get, $price);
                $this->getAndPrepare('prefix', $get, $prefix);
                $this->getAndPrepare('sortOrder', $get, $sortOrder);

                $data = array(
                    'products_id' => $this->intPID,
                    'options_id' => $optionId,
                    'options_values_id' => $optionValueId,
                    'options_values_msrp' => $msrp,
                    'options_values_price' => $price,
                    'options_upc' => $quantity_id,
                    'options_model_no' => $model_no,
                    'options_serial_no' => $serial_no,
                    'options_quantity' => $quantity,
                    'price_prefix' => $prefix
                );
                 if (AM_USE_MPW) {
                  $this->getAndPrepare('weight', $get, $weight);
                  $this->getAndPrepare('weight_prefix', $get, $weight_prefix);

                  if((empty($weight))||($weight=='0')){
                    $weight='0.0000';
                  }else{
                    if((empty($weight_prefix))||($weight_prefix==' ')){
                      $weight_prefix='+';
                    }
                  }

                  $data['options_values_weight'] = $weight;
                  $data['weight_prefix'] = $weight_prefix;
                }

                if (AM_USE_SORT_ORDER) {
                    $data[AM_FIELD_OPTION_VALUE_SORT_ORDER] = $sortOrder;
                }

                $data2['attribute_special_order'] = '0';
                amDB::perform(TABLE_PRODUCTS_ATTRIBUTES,$data2, 'update',"products_id='$this->intPID' and options_id='$optionId' and options_values_id='$optionValueId' and products_attributes_id =  '$attribute_id' and sort");
            
            }
        */  
              if($opt['serialno'] <> ''){
                  $count++;
              }
          
            }
            
        }
        
        if($count < 2){
            // Possibly add special orders
            $sql = "UPDATE products_attributes SET attribute_special_order = '".$product['specialorder']."' WHERE options_id='".$_POST['option_id']."' and options_values_id = '".$_POST['value_id']."' and products_id = '".$pid."'";
            tep_db_query($sql); 
        }
        
        // Update Sort Order
        
         /*$sql = "UPDATE products_attributes SET products_options_sort_order = '".$product['sortorder']."' WHERE options_id='".$_POST['option_id']."' and options_values_id = '".$_POST['value_id']."' and products_id = '".$pid."'";
        tep_db_query($sql);
        
        // Update MSRP
	   $sql = "UPDATE products_attributes SET options_values_msrp = '".$product['msrp']."' WHERE options_id='".$_POST['option_id']."' and options_values_id = '".$_POST['value_id']."' and products_id = '".$pid."'";
        tep_db_query($sql);
        
        // Update Price
        $sql = "UPDATE products_attributes SET options_values_price = '".$product['price']."' WHERE options_id='".$_POST['option_id']."' and options_values_id = '".$_POST['value_id']."' and products_id = '".$pid."'";
        tep_db_query($sql);
        
        // Update Price Prefix
        if($product['msrp'] > 0){
        $sql = "UPDATE products_attributes SET price_prefix = '' WHERE options_id='".$_POST['option_id']."' and options_values_id = '".$_POST['value_id']."' and products_id = '".$pid."'";
        tep_db_query($sql);
        }
        */
        
        $data_all = array(
            'products_options_sort_order' => $product['sortorder'],
            'options_values_msrp' => $product['msrp'],
            'options_values_price' => $product['price'],
			'options_invoice_price' => $product['invoice'],
            'price_prefix' => '',
            'options_upc' => $product['upc'],
        );
        
        tep_db_perform("products_attributes", $data_all, "update", "options_id='".$_POST['option_id']."' and options_values_id = '".$_POST['value_id']."' and products_id = '".$pid."'");
    
      //  amDB::perform(TABLE_PRODUCTS_ATTRIBUTES,$data);
        
    }
    }
	
}
?>
