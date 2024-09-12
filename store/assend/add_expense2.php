<?php

require_once('includes/application_top.php');

$action = $_GET['action'];
    if($action == 'update'){

        $date = date('Y-m-d H:i:s',strtotime($_POST['date']));
        $year = date('Y', strtotime($_POST['date']));
        $account = $_POST['account'];

        $data = array(
            'date' => $date,
            'type' => strtolower($_POST['type']),
            'account' => $_POST['account'],
            'num' => '0',
            'name' => $_POST['name'],
            'description' => $_POST['description'],
            'payment_method' => $_POST['pay_method'],
            'amount' => $_POST['pay_amount']
        );

         tep_db_perform('transactions', $data, "update", 'id= "'.$_POST['id'].'"');
    }

    if($action == 'delete'){
        $id = $_POST['id'];

        $delete_query = tep_db_query("DELETE FROM transactions where id = '".$id."'");

    }



    $get_info_query = tep_db_query("SELECT * from transactions where id = ".$_POST['id']."");
    $get_info = tep_db_fetch_array($get_info_query);

    $payment_array = array(array('id'=>'', 'text'=>''),array('id'=> 'Cash', 'text'=> 'Cash'),
    array('id'=> 'Check', 'text'=> 'Check'),
    array('id'=> 'Credit Card', 'text'=> 'Credit Card'),
    array('id'=> 'Outstanding', 'text'=> 'Outstanding'),
    array('id'=> 'Payment Recon', 'text'=> 'Payment Recon'),
    array('id'=> 'Paypal', 'text'=> 'Paypal'),
    array('id'=> 'Sales Reciept', 'text'=> 'Sales Reciept'));


?>

<link rel="stylesheet" type="text/css" href="js/bootstrap-datetimepicker.min.css">
<style>#save-container {
position: fixed;
bottom: 0px;
left: 0px;
right: 0px;
width: 100%;
height: 55px;
border-bottom: 1px solid;
z-index: 100;
background:#8c8c8c;
}

    .drop-down-icon{    position: absolute;
    right: 0px;
    bottom: 0px;
    font-size: 1.2rem;
    background-color: #ddd;
    padding: 7px 12px;
    border-radius: 0px 5px 5px 0px;}
    .drop-down-icon:hover{background-color: #09f; color:#fff;}

    .account_dropdown{width:420px;
    position: absolute;
    left:0px;
    z-index: 400;
    background: #fff;
    height:400px;
    overflow: auto;
        border:1px solid;
    }

    .account_dropdown .col-xs-6{padding-left:10px; padding-right:10px; white-space:nowrap;}

    .action-option{display:inline-block; width:100%; padding-top:10px; padding-bottom:10px;}
    .action-option:hover{background-color:#ddd; color:#fff;cursor: pointer;}

    @media (max-width:567px){
        .account_dropdown{left: -45px; /*width:100%;*/}
    }

    table {text-align: left;}
</style>
<form method="post" id="popUpForm">
<?php
echo '<div class="col-xs-12 form-group" style="margin-bottom:50px; margin-top:40px;">

<input type="hidden" name="id" value="'.$_POST['id'].'">
    <div class="col-xs-12 form-group">

        <div class="col-sm-4">
        <label class="form-name" style="display: inline-block; margin-bottom: .5rem;">Payment Date</label>
        <div class="input-append" id="datetimepicker">
            <label class="form-name" style="display: inline-block; margin-bottom: .5rem;">Payment Date</label>
            <input name="date" id="datetimepicker-input" class="form-control" style="width:200px; border-radius: 5px 0px 0px 5px; height:38px;" value="'.$get_info['date'].'">

            </div>
        </div>'; ?>

        <?php $get_stuff_query = tep_db_query("SELECT type, account FROM transactions WHERE id = '".$_POST['id']."'");
              $get_stuff = tep_db_fetch_array($get_stuff_query);
        echo'
        <div class="col-sm-3 form-group">
            <label class=form-name" style="display: inline-block; margin-bottom: .5rem;">Account</label>
            <div class="account-holder" style="position:relative; width:250px;">
                <input name="account" type="text" id="account-input" class="form-control" style="width:250px;" value="'.$get_stuff['account'].'"/>
                <input name="type" type="hidden" id="account-input-val" value="'.$get_stuff['type'].'" />
                <span class="drop-down-icon">
                    <i class="fa fa-caret-down"></i>
                    <i class="fa fa-caret-up" style="display:none;"></i>
                </span>

                <div class="account_dropdown" role="listbox" style="display:none;">
                    <div role="option" class="form-group" style="display:inline-block; width:100%;">
                    </div>';

    $get_categories_names_query = tep_db_query("SELECT * FROM transactions_categories");

    while($get_categories_names = tep_db_fetch_array($get_categories_names_query)){

            echo '<div class="action-option" role="option" data-id="'.$get_categories_names['category_type'].'" data-name="'.$get_categories_names['category_name'].'">
                    <div class="col-xs-6">'.$get_categories_names['category_name'].'</div>
                    <div class="col-xs-6" style="text-align:right;"><i>'.$get_categories_names['category_type'].'</i></div>
                </div>';
    }
            echo '</div>
            </div>
        </div>


        <div style="width: 40px; height: 40px; display: block; float: right; font-size: 25px;">
            <a id="close" onclick="closeThis();"><i class="fa fa-close"></i></a>
        </div>

    </div>

        <div class="col-xs-12 form-group">
        <table class="table table-bordered" style="background-color:#fff;">
            <thead>
                <tr>
                <th style="width:20%">NAME</th>
                <th style="width:50%">DESCRIPTION</th>
                <th style="width:15%">PAYMENT METHOD</th>
                <th style="width:13%">AMOUNT</th>
                <th style="width:5%"></th>
                </tr>
            </thead>
        <tbody>
        <tr class="hello">
            <td><input name="name" class="form-control" value="'.$get_info['name'].'"></td>
            <td>'.tep_draw_textarea_field('description', 'soft', '70', '5', $get_info['description'],  'style="height:38px;" class="form-control"').'</td>
            <td>'.tep_draw_pull_down_menu('pay_method',$payment_array, $get_info['payment_method'], 'class="form-control"').'</td>
            <td><input name="pay_amount" class="form-control" value="'.$get_info['amount'].'"></td>
            <td style="vertical-align:middle;"><a onclick="deleteThis();" data-placement="top" title="Delete" class="btn btn-danger"><i class="fa fa-trash-o"></i></a></td>
        </tr>
        </tbody>
        </table>
        </div>

    </div>
    <div class="col-xs-12" id="save-container">
        <a onclick="submitForm2();" class="btn" style="color:#fff; width: 130px; float: right; background: #0C0;margin:10px; margin-right:30px;" value="Save">Save</a>
        </div>
        </form>';

?>

<script src="js/autosize.js"></script>
<script>
    function closeThis(){
       $("#expense-container").html();
       $("#expense-container").hide();
    }

    autosize(document.querySelectorAll('textarea'));

    $(".drop-down-icon").on("click", function(){
        $(".drop-down-icon .fa-caret-down").toggle();
        $(".drop-down-icon .fa-caret-up").toggle();
        $(".account_dropdown").toggle();
    })

    $("#account-input").on("click", function(){
        $(".account_dropdown").toggle();
    })

    $(".action-option").on("click", function(){
        var id = $(this).data("id");
        var name = $(this).data("name");

        $('#account-input').val(name);
        $('#account-input-val').val(id);
        $(".account_dropdown").hide();
    })

</script>

  <?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
