<?php

  require('includes/application_top.php');

  $query = tep_db_query("select * from board_checkout");

  if(isset($_POST['id'])){
    tep_db_query("UPDATE board_checkout SET expiration_date = '".$_POST['expiration_date']."' , shipping_fee = '".$_POST['shipping_fee']."', status = 1 where id = '".$_POST['id']."'");
    header("Refresh:0"); 
  }
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html;">
    <title><?php echo 'Board Checkout Shipping Page'; ?></title>
    <link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
    <link rel="stylesheet" type="text/css" href="includes/bootstrap-header.css">
    <link rel="stylesheet" type="text/css" href="includes/sb-admin.css">
    <script language="javascript" src="includes/general.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
    <link href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css" rel="stylesheet">
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
</head>
    <body>
        <div id="wrapper">
            <?php
                require(DIR_WS_INCLUDES . 'header-simple.php');
            ?>
            <div id="create-order-container">
                <h2 class="pageHeading"><?php echo 'Board Checkout Shipping Page'; ?></h2>
                <table id="example" class="display" style="width:100%">
                    <thead>
                        <tr>
                            <th>Customer Email</th>
                            <th>Customer Phone</th>
                            <th>Customer Address</th>
                            <th>Order Details</th>
                            <th>Expiration Date</th>
                            <th>Shipping Fee</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php  while ($result = tep_db_fetch_array($query)) { ?>
                            <tr>
                                <td><?php echo $result['customer_email']; ?></td>
                                <td><?php echo $result['phone']; ?></td>
                                <td><?php echo $result['address']; ?></td>
                                <td><?php echo $result['order_details']; ?></td>
                                <td><?php echo $result['expiration_date']; ?></td>
                                <td><?php echo $result['shipping_fee']; ?></td>
                                <td><?php
                                    if($result['status'] == 1) {
                                        echo "Approved";
                                    } elseif($result['status'] == 2) {
                                        echo "Cancelled";
                                    } else{
                                        echo "Pending";
                                    } ?></td>
                                <td><button type="button" class="btn btn-primary" data-toggle="modal" data-id="<?php echo $result['id']; ?>" data-shipping="<?php echo $result['shipping_fee']; ?>" data-expiration="<?php echo $result['expiration_date']; ?>" onclick="validateModal(this);" data-target="#addShippingFeeModal"><i class="fa fa-pencil" aria-hidden="true"></i></button>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <div class="modal fade" id="addShippingFeeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <form method = "post">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="addShippingFeeModalTitle">Add Shipping Fee</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="shippingFee">Shipping Fee</label>
                                        <input type="hidden" class="form-control" name="id" id="id" placeholder="Enter Shipping Fee">
                                        <input type="number" required class="form-control" name="shipping_fee" id="shippingFee" placeholder="Enter Shipping Fee">
                                    </div>
                                    <div class="form-group">
                                        <label for="expirationDate">Expiration Date</label>
                                        <input type="date" required name="expiration_date" class="form-control" id="expirationDate">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <?php require(DIR_WS_INCLUDES . 'footer-simple.php'); ?>
        </div>
        <script>
            $( document ).ready(function() {
                new DataTable('#example',{
                    order: [[4, 'desc']]
                });
            });
            function validateModal(data) {
                $('#shippingFee').val($(data).data('shipping'));
                $('#expirationDate').val($(data).data('expiration'));
                $('#id').val($(data).data('id'));
            }
        </script>
    </body>
</html>
<?php 
require(DIR_WS_INCLUDES . 'application_bottom.php'); 
?>

