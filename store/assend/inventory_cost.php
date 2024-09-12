<?php

require('includes/application_top.php');
require(DIR_WS_INCLUDES . 'template-top.php');
require(DIR_WS_CLASSES . 'currencies.php');
$currencies = new currencies();

?>
<div style="clear:both"> </div>
<h1>Inventory Cost</h1>

<div class="column-12 form-group">
  <a id="selectAll" class="btn btn-primary">Select All</a>

</div>

<table class="table table-bordered" style="max-width:650px; background:#fff;">
  <thead>
    <th></th>
    <th align="left">Category</th>
    <th>MSRP</th>
    <th>Cost</th>
  </thead>
  <tbody>
    <?php $get_main_categories_query = tep_db_query("SELECT * FROM categories c, categories_description cd WHERE c.categories_id = cd.categories_id AND c.categories_id <> '759' AND c.parent_id = '0' ORDER BY c.sort_order ASC");
          while($get_main_categories = tep_db_fetch_array($get_main_categories_query)){
            $main_products_total = 0;
            $main_products_invoice_total = 0;

            $main_attributes_total = 0;
            $main_attributes_invoice_total = 0;

            $running_main_price_total = 0;
            $running_main_invoice_total = 0;

            //Get Products from Main category
            $get_main_products_query = tep_db_query("SELECT * FROM products p, products_to_categories p2c WHERE p2c.categories_id = '".$get_main_categories['categories_id']."' AND p.products_id = p2c.products_id AND p.products_quantity > 0 AND p.products_special_order <> '1'");

            while($get_main_products = tep_db_fetch_array($get_main_products_query)){

              //Check for attributes
              $check_for_main_attributes_query = tep_db_query("SELECT options_values_price, options_values_price, options_quantity FROM products_attributes WHERE products_id = '".$get_main_products['products_id']."' AND options_quantity > '0' AND attribute_special_order <> '1'");

              if(tep_db_num_rows($check_for_main_attributes_query) > 0){

                while($check_for_main_attributes = tep_db_fetch_array($check_for_main_attributes_query)){
                  //Check if attribute has a price of 0 or not
                  if($check_for_main_attributes['options_values_price'] > '0'){
                    $main_attributes_total += $check_for_main_attributes['options_values_price']*$check_for_main_attributes['options_quantity'];
                  } else {
                    $main_attributes_total += $get_main_products['products_price']*$check_for_main_attributes['options_quantity'];
                  }

                  if($check_for_main_attributes['options_values_invoice_price'] > '0'){
                    $main_attributes_invoice_total += $check_for_main_attributes['options_values_invoice_price']*$check_for_main_attributes['options_quantity'];
                  } else {
                    $main_attributes_invoice_total += $get_main_products['invoice_price']*$check_for_main_attributes['options_quantity'];
                  }

                }
              } else {
                $main_products_total += $get_main_products['products_price']*$get_main_products['products_quantity'];
                $main_products_invoice_total += $get_main_products['invoice_price']*$get_main_products['products_quantity'];
              }
            }
            $running_main_price_total = $main_products_total + $main_attributes_total;
            $running_main_invoice_total = $main_products_invoice_total + $main_attributes_invoice_total;

            //Check if category has second level   ".$get_main_categories['categories_id']."
            $get_round2_category_query = tep_db_query("SELECT c.parent_id, cd.categories_name, c.categories_id FROM categories c, categories_description cd WHERE c.categories_id = cd.categories_id AND c.parent_id = '".$get_main_categories['categories_id']."' ORDER BY c.sort_order ASC");

            if(tep_db_num_rows($get_round2_category_query) > 0){

          	   while($get_round2_category =  tep_db_fetch_array($get_round2_category_query)){

          	      $get_round3_category_query = tep_db_query("SELECT c.parent_id, cd.categories_name, c.categories_id FROM categories c, categories_description cd WHERE c.parent_id = '". $get_round2_category['categories_id']."' AND c.categories_id = cd.categories_id ORDER BY c.sort_order");

                  //Check if Category has a third level
                  if(tep_db_num_rows($get_round3_category_query) > '0'){


                    while($get_round3_category =  tep_db_fetch_array($get_round3_category_query)){
                      $running_products_total3 = '0';
                      $running_products_invoice_total3 = '0';

                      $running_attributes_total3 = '0';
                      $running_attributes_invoice_total3 = '0';

                      $running_price_total3 = '0';
                      $running_invoice_total3 = '0';


                      //Get Products in this category   ".$get_round3_category['categories_id']."
                      $get_products3_query = tep_db_query("SELECT * FROM products p, products_to_categories p2c WHERE p2c.categories_id = '".$get_round3_category['categories_id']."' AND p.products_id = p2c.products_id AND p.products_quantity > 0 AND p.products_special_order <> '1'");

                      while($get_products3 = tep_db_fetch_array($get_products3_query)){

                        //Check for attributes
                        $check_for_attributes3_query = tep_db_query("SELECT options_values_price, options_values_price, options_quantity FROM products_attributes WHERE products_id = '".$get_products3['products_id']."' AND options_quantity > '0' AND attribute_special_order <> '1'");

                        if(tep_db_num_rows($check_for_attributes3_query) > 0){

                          while($check_for_attributes3 = tep_db_fetch_array($check_for_attributes3_query)){
                            //Check if attribute has a price of 0 or not
                            if($check_for_attributes3['options_values_price'] > '0'){
                              $running_attributes_total3 += $check_for_attributes3['options_values_price']*$check_for_attributes3['options_quantity'];
                            } else {
                              $running_attributes_total3 += $get_products3['products_price']*$check_for_attributes3['options_quantity'];
                            }

                            if($check_for_attributes3['options_values_invoice_price'] > '0'){
                              $running_attributes_invoice_total3 += $check_for_attributes3['options_values_invoice_price']*$check_for_attributes3['options_quantity'];
                            } else {
                              $running_attributes_invoice_total3 += $get_products3['invoice_price']*$check_for_attributes3['options_quantity'];
                            }
                          }
                        } else {
                          $running_products_total3 += $get_products3['products_price']*$get_products3['products_quantity'];
                          $running_products_invoice_total3 += $get_products3['invoice_price']*$get_products3['products_quantity'];
                        }
                      }
                      $running_price_total3 = $running_products_total3 + $running_attributes_total3;
                      $running_invoice_total3 = $running_products_invoice_total3 + $running_attributes_invoice_total3;

                            echo '<tr>
                            <td><input class="checker" name="category" type="checkbox" value="'.$running_price_total3.'" data-valuetwo="'.$running_invoice_total3.'"></td>
                            <td>
                            <a target="_blank" href="stockcheck.php?cPath='.$get_round3_category['categories_id'].'">'.$get_round3_category['categories_name'].'</a>
                            </td>
                            <td align="center">'.$currencies->format($running_price_total3).'</td>
                            <td align="center">'.$currencies->format($running_invoice_total3).'</td>
                                 </tr>';

                    }
                  } else {


                      $running_products_price_total2 = '0';
                      $running_products_invoice_total2 = '0';

                      $running_attributes_price_total2 = '0';
                      $running_attributes_invoice_total2 = '0';

                      $running_price_total2= '0';
                      $running_invoice_total2 = '0';

                      //Get Products in this category
                      $get_products2_query = tep_db_query("SELECT * FROM products p, products_to_categories p2c WHERE p2c.categories_id = '".$get_round2_category['categories_id']."' AND p.products_id = p2c.products_id AND p.products_quantity > 0 AND p.products_special_order <> '1'");

                      while($get_products2 = tep_db_fetch_array($get_products2_query)){

                        //Check for attributes
                        $check_for_attributes2_query = tep_db_query("SELECT options_values_price, options_values_price, options_quantity FROM products_attributes WHERE products_id = '".$get_products2['products_id']."' AND options_quantity > '0' AND attribute_special_order <> '1'");

                        if(tep_db_num_rows($check_for_attributes2_query) > 0){

                          while($check_for_attributes2 = tep_db_fetch_array($check_for_attributes2_query)){
                            //Check if attribute has a price of 0 or not
                            if($check_for_attributes2['options_values_price'] > '0'){
                              $running_attributes_price_total2 += $check_for_attributes2['options_values_price']*$check_for_attributes2['options_quantity'];
                            } else {
                              $running_attributes_price_total2 += $get_products2['products_price']*$check_for_attributes2['options_quantity'];
                            }

                            if($check_for_attributes2['options_values_invoice_price'] > '0'){
                              $running_attributes_invoice_total2 += $check_for_attributes2['options_values_invoice_price']*$check_for_attributes2['options_quantity'];
                            } else {
                              $running_attributes_invoice_total2 += $get_products2['invoice_price']*$check_for_attributes2['options_quantity'];
                            }

                          }
                        } else {
                          $running_products_price_total2 += $get_products2['products_price']*$get_products2['products_quantity'];
                          $running_products_invoice_total2+= $get_products2['invoice_price']*$get_products2['products_quantity'];
                        }
                      }
                      $running_price_total2 = $running_products_price_total2 + $running_attributes_price_total2;
                      $running_invoice_total2 = $running_products_invoice_total2+ $running_attributes_invoice_total2;

                      echo '<tr>
                         <td><input class="checker" type="checkbox" value="'.$running_price_total2.'" data-valuetwo="'.$running_invoice_total2.'"></td>
                         <td align="left"><a target="_blank" href="stockcheck.php?cPath='.$get_round2_category['categories_id'].'">'.$get_round2_category['categories_name'].'</a></td>
                         <td align="center">'.$currencies->format($running_price_total2).'</td>
                         <td align="center">'.$currencies->format($running_invoice_total2).'</td>
                       </tr>';

                }
              }

             } else {
               echo '<tr>
                    <td><input class="checker" type="checkbox" value="'.$running_main_price_total.'" data-valuetwo="'.$running_main_invoice_total.'"></td>
                    <td align="left"><a target="_blank" href="stockcheck.php?cPath='.$get_main_categories['categories_id'].'">'.$get_main_categories['categories_name'].'</a></td>
                    <td align="center">'.$currencies->format($running_main_price_total).'</td>
                    <td align="center">'.$currencies->format($running_main_invoice_total).'</td>
                  </tr>';
             }
           }
          ?>
    <tr>
      <td></td>
      <td>Total</td>
      <td><div id="price_total"> </div></td>
      <td><div id="invoice_total"> </div></td>
    </tr>


  </tbody>


</table>


<script>
const formatToCurrency = amount => {
  return "$" + amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, "$&,");
};

function updateTotal(){
      var total = 0;
      var totalInv = 0;
      $('input:checkbox:checked').each(function(){
       total += isNaN(parseInt($(this).val())) ? 0 : parseInt($(this).val());
       totalInv += isNaN(parseInt($(this).attr("data-valuetwo"))) ? 0 : parseInt($(this).attr("data-valuetwo"));
      });

      $("#price_total").html(formatToCurrency(total));
      $("#invoice_total").html(formatToCurrency(totalInv));
};


var clicked = false;
$("#selectAll").on("click", function() {
  $(".checker").prop("checked", !clicked);
  clicked = !clicked;
  this.innerHTML = clicked ? 'Deselect All' : 'Select All';

  updateTotal();
});

var $chkboxes = $(".checker");
var lastChecked = null;

$chkboxes.click(function (e){
  if (!lastChecked) {
    lastChecked = this;
    return;
  }

  if (e.shiftKey) {
    var start = $chkboxes.index(this);
    var end = $chkboxes.index(lastChecked);

    $chkboxes.slice(Math.min(start,end), Math.max(start,end)+ 1).prop('checked', lastChecked.checked);
  }

  lastChecked = this;

  updateTotal();
})

$('input:checkbox').change(function (){
  updateTotal();
})

</script>
