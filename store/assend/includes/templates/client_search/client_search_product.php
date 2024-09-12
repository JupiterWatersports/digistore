<?php
$pID = $_GET['pID'];
	if (isset($_GET['products_serial'])){
	$productdata = $_GET['products_serial'];
	}
	elseif (isset($_GET['product_options_id'])){ 
	$productdata = $_GET['product_options_id']; }
	elseif (isset($_GET['pID'])) {
	$productdata = $_GET['pID'];
	} else {
		$productdata = ''; }

?>

<!doctype html>
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<link rel="stylesheet" type="text/css" href="includes/templates/client_search/client_search.css">

<script type="text/javascript" src="includes/javascript/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="includes/javascript/client_search/client_search_product.js"></script>

</head>

<body>
<div id="wrapper">
	<!-- header //-->
	<?php
  require(DIR_WS_INCLUDES . 'header.php');
?>
<!-- header_eof //-->
  
	    <form id="find_client" action="<?=tep_href_link(FILENAME_CLIENT_SEARCH);?>" method="post">
		<hr>
		    <div class="form-group" style="margin-top:30px;">
			<div class="col-xs-3" style="margin-top:10px;"><?=CLIENT_SEARCH_TEXT_PRODUCT?></div>
		<div class="col-xs-9">
			    
		<script type="text/javascript" src="ext/jquery/ui/head_Prod_controller.js"></script>
		<link rel="stylesheet" type="text/css" href="head_live.css" />
		<script type='javascript'>
		$('#product_id').focus(function() { 
		  $('this').val(''); 
		});
		</script>
<?php
	if (isset($_GET['products_serial'])){
		echo '<input type="text" style="width:400px;" id="product_id" class="form-control" size="20" name="products_serial" value="'.$productdata.'" placeholder="Search Products"  autocomplete="off">
		<input type="hidden" name="product_id" value="'.$pID.'">';
	}
	elseif (isset($_POST['product_id'])) {
		echo '<select style="width:100%;" name="product_id" class="form-control"><option value="'.$_POST['product_id'].'">'.$_POST['product_name'].'</option></select>';
	} elseif (isset($_GET['product_options_id'])) { ?>
	<input type="text" style="width:400px;" id="product_id" class="form-control" size="20" name="option_value_id" value="<?php echo $productdata; ?>" placeholder="Search Products"  autocomplete="off">
	<input type="hidden" name="product_id" value="<?php echo $pID; ?>">
 <?php
	}  else { ?>
<input type="text" style="width:400px;" id="product_id" class="form-control" size="20" name="product_id" value="<?php echo $productdata; ?>" placeholder="Search Products"  autocomplete="off" >
<?php } ?>
</br>
<input type="text" style="width:400px;" id="product_id" class="form-control" size="20" name="product_name" placeholder="Search Peoples Laziness aka Custom"  autocomplete="off" >
<div id="ProdresultsContainerOrder" style=""></div></div></div>
		
        <div class="col-xs-12 form-group" style="margin-top:10px;">
        
		    <div class="col-sm-2 form-group">
			<label><?=CLIENTSEARCH_PRODUCT_PERIOD?></label>
			<input type="checkbox" value="yes" name="filter" id="filter">
            </div>
		    
		     <div style="width:300px; float:left;">
		 	<div style="width:250px; float:left;">
			<div style="text-align:right; float: left; width:50px;">&nbsp;</div>
			<div style="text-align:right; float: left; width:45px;"><?=CLIENTSEARCH_PRODUCT_MONTH?></div>
            <div style="text-align:right; float: left; width:40px;"><?=CLIENTSEARCH_PRODUCT_DAY?></div>
			<div style="text-align:right; float: left; width:50px;"><?=CLIENTSEARCH_PRODUCT_YEAR?></div>
		    </div>
            
		   <div style="width:250px; margin-top:10px; float:left;">
		<div style="text-align:right; float: left; width:50px;"><?=CLIENTSEARCH_PRODUCT_FROM?></div>
			 <div class="col-sm-2  col-xs-3" style="text-align:right;"><input id="from_month" type="text" name="from_month" style="width:30px;" maxlength="2" value="01"></div>
            <div class="col-sm-2  col-xs-3" style="text-align:right;"><input id="from_day" type="text" name="from_day" style="width:30px;" maxlength="2" value="01"></div>
			<div class="col-sm-2  col-xs-3" style="text-align:right;"><input id="from_year" type="text" name="from_year" style="width:60px;" maxlength="4"></div>
		    </div>

		      <div style="width:250px; margin-top:10px; float:left;">
			<div style="text-align:right; float: left; width:50px;"><?=CLIENTSEARCH_PRODUCT_TO?></div>
			<div class="col-sm-2  col-xs-3" style="text-align:right;"><input id="to_month" type="text" name="to_month" style="width:30px;" maxlength="2" value="12"></div>
            <div class="col-sm-2  col-xs-3" style="text-align:right;"><input id="to_day" type="text" name="to_day" style="width:30px;" maxlength="2" value="31"></div>
			<div class="col-sm-2  col-xs-3" style="text-align:right;"><input id="to_year" type="text" name="to_year" style="width:60px;" maxlength="4"></div>
		    </div>
            </div>
            </div>
		    <hr>
		   
			<input type="submit" style="width:120px; margin-left:25%; margin-top:10px;" value="<?=CLIENTSEARCH_PRODUCT_FIND?>" class="cl_se_find_but">
			<input type="hidden" name="action" value="find_clients">
		 
	    </form>
	
  <!-- body_text_eof //-->
<div style="margin-bottom:10px;">&nbsp;</div>
<!-- footer //-->
<?php include(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</div>
</body>
</html>

