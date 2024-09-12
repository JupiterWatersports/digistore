<?php
/**
 * Class that encapsulates the product object.
 * Responsible for retrieving attributes associated with a 
 * particular object.
 * 
 * Author: Sunny Chow
 */
class ProductObject
{
	private $product_id;
	
	function __construct($pid)
	{
		$this->product_id = mysql_escape_string($pid);	
	}
	
	function getNumberAttributes()
	{
	    $attributes_query = tep_db_query("select count(*) as count from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id = '" . (int)$this->product_id . "'");
   		$attributes = tep_db_fetch_array($attributes_query);

   		return $attributes['count'];

	}
	
	function getProductAttributes()
	{
		$languages_id = 1;
    	$product_info_query = tep_db_query("select p.products_id, pd.products_name, pd.products_description, p.products_model, p.products_quantity, p.products_image, pd.products_url, p.products_price, p.products_tax_class_id, p.products_date_added, p.products_date_available, p.manufacturers_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = '" . $this->product_id . "' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "'");
    	return tep_db_fetch_array($product_info_query);
	}

	function getProductOptions()
	{
		$languages_id = 1;
		$options = array();
		$products_attributes_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" . $this->product_id  . "' and patrib.options_id = popt.products_options_id and popt.language_id = '" . (int)$languages_id . "'");
  		$products_attributes = tep_db_fetch_array($products_attributes_query);
		if ($products_attributes['total'] == 0)
		{
			return NULL;
		}

  		$products_options_name_query = tep_db_query("select distinct popt.products_options_id, popt.products_options_name from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" . $this->product_id . "' and patrib.options_id = popt.products_options_id and popt.language_id = '" . (int)$languages_id . "' order by popt.products_options_name");
		while ($products_options_name = tep_db_fetch_array($products_options_name_query))
		{	
				$options[$products_options_name['products_options_name']] = $products_options_name['products_options_id'];
		}
	
		return $options;
	}

	function getOptionAttributes($option_id)
	{
		global $currencies;
		$option_id = mysql_escape_string($option_id);
		
		$languages_id = 1;
		$attributes = array();

    $products_options_query = tep_db_query("select pov.products_options_values_id, pov.products_options_values_name, pa.options_values_price, pa.price_prefix from " . TABLE_PRODUCTS_ATTRIBUTES . " pa, " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov where pa.products_id = '" . $this->product_id . "' and pa.options_id = '" . $option_id  . "' and pa.options_values_id = pov.products_options_values_id and pov.language_id = '" . (int)$languages_id . "'");

    while ($products_options = tep_db_fetch_array($products_options_query)) 
		{
			// Add option price if present.
      if ($products_options['options_values_price'] != '0') {
        $products_options['products_options_values_name'] .= ' (' . $products_options['price_prefix'] . $currencies->display_price($products_options['options_values_price'], tep_get_tax_rate($product_info['products_tax_class_id'])) .') ';
      }
			$attributes[] = array('id' => $products_options['products_options_values_id'], 'text' => $products_options['products_options_values_name'], 'price' => $product_ );
    }

		return $attributes;
	}

	function getOptionVals($optionPairs)
	{
		// Error Conditions.
		if (!isset($optionPairs)) return '';
		if ($optionPairs == null) return '';
		if (count($optionPairs) == 0) return '';

		$options = $this->getProductOptions();

		$result = array();
		foreach ($optionPairs as $oID => $aID)
		{
			// Set to safe defaults.
			$selOptionName = $oID;
			$selAttribName = $aID;

			$attributes = $this->getOptionAttributes($oID);
			$selOptionName = array_search($oID, $options);
			foreach ($attributes as $attrib)
			{
				if ( $aID == $attrib['id'])
					$selAttribName = $attrib['text'];
			}

			$result[$selOptionName] = $selAttribName;
		}	

		return $result;
	}

	function getPID()
	{
		return $this->product_id;
	}
};
?>
