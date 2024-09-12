<?php
require_once('includes/application_top.php');
require(DIR_WS_INCLUDES . 'template-top.php');
$action = $_POST['action'];
    if($action == 'add'){
        $date = date('Y-m-d H:i:s',strtotime($_POST['date']));
        $account = $_POST['account'];

        foreach($_POST['name'] as $i => $val){

        $data = array(
            'date' => $date,
            'type' => strtolower($_POST['type']),
            'account' => $_POST['account'],
            'num' => '0',
            'name' => $_POST['name'][$i],
            'description' => $_POST['description'][$i],
            'payment_method' => $_POST['pay_method'][$i],
            'amount' => $_POST['pay_amount'][$i]
        );

         tep_db_perform('transactions', $data);
        }
    }

    if($_POST['action'] == 'addExcel'){

        foreach($_POST['name'] as $i => $val){

            $data = array(
                'date' => date('Y-m-d H:i:s',strtotime($_POST['date'][$i])),
                'type'=> 'expense',
                'account' => $_POST['account'][$i],
                'num' => '0',
                'name' => $_POST['name'][$i],
                'description' => $_POST['description'][$i],
                'payment_method' => $_POST['pay_method'][$i],
                'amount' => $_POST['amount'][$i]
            );

            tep_db_perform('transactions', $data);
        }
    }

    if($_POST['action'] == 'uploaded_file_form'){
        if ($_FILES['uploaded_file'] !== ''){
        $uploaded_file = new upload('uploaded_file');
        $uploaded_file->set_destination(DIR_FS_CATALOG_IMAGES);
            if ($uploaded_file->parse() && $uploaded_file->save()) {
                echo $uploaded_file;
            }
        }
    }

    if($_GET['action'] == 'addExcel'){
        tep_redirect(tep_href_link('add_expense.php',''));
    }

/*

 if ($action == 'uploaded_file'){
$uploaded_file = $_POST['file'];

$objReader = PHPExcel_IOFactory::createReadeuplr('Excel2007');
$objReader->setReadDataOnly(true);
$objPHPExcel = $objReader->load($uploaded_file);
$objWorksheet = $objPHPExcel->setActiveSheetIndex(4);


     echo '<table border=1>' . "\n";
foreach ($objWorksheet->getRowIterator() as $row) {
  echo '<tr>' . "\n";
  $cellIterator = $row->getCellIterator();
  $cellIterator->setIterateOnlyExistingCells(false); // This loops all cells,
                                                     // even if it is not set.
                                                     // By default, only cells
                                                     // that are set will be
                                                     // iterated.
  foreach ($cellIterator as $cell) {
    echo '<td>' . $cell->getValue() . '</td>' . "\n";
  }
  echo '</tr>' . "\n";
}
echo '</table>' . "\n";
 }

 */

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
    table {text-align: left;}

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
        border: 1px solid;
    }

    .account_dropdown .col-xs-6{padding-left:10px; padding-right:10px; white-space:nowrap;}

    .action-option{display:inline-block; width:100%; padding-top:10px; padding-bottom:10px;}
    .action-option:hover{background-color:#ddd; color:#fff;cursor: pointer;}

    @media (max-width:567px){
        .account_dropdown{left: -45px; /*width:100%;*/}
    }

</style>
<?php
echo '<div class="col-xs-12 form-group" style="margin-bottom:50px;">
    <div class="form-horizontal form-group">
        <h2> Add Expense</h2>
    </div>
    <form id="file-form" method="post" enctype="multipart/form-data">
    <input type="hidden" name="action" value="uploaded_file_form">
    <div class="col-xs-12 form-group" style="margin-bottom:30px; border-top:1px solid; border-bottom:1px solid; padding:15px 0px;">
    <input name="uploaded_file" type="file" class="form-group">

    <a id="file-submit" class="btn btns" style="background:#ccc;" onclick="submitFile();">Submit</a>
    </div>
    </form>

    <form id="file-cont-form" method="POST">
        <input type="hidden" name="action" value="addExcel">
        <div id="file-container" class="form-group"></div>
        <div id="lastLine" class="form-group" style="margin-bottom:30px; border-bottom:1px solid; padding-bottom:30px;">
            <a id="submitData" class="btn btns" style="background:#ccc; display:none;" onclick="submitExcel();">Submit Data</a>
        </div>
    </form>

    <form method="post">
<input type="hidden" name="action" value="add">
    <div class="col-xs-12 form-group">

        <div class="col-sm-4 form-group">
        <label class="form-name" style="display: inline-block; margin-bottom: .5rem;">Payment Date</label>
        <div class="input-append" id="datetimepicker">
            <label class="form-name" style="display: inline-block; margin-bottom: .5rem;">Payment Date</label>
            <input name="date" id="datetimepicker-input" class="form-control" style="width:200px; border-radius: 5px 0px 0px 5px; height:38px;">
            <span class="add-on" style="padding:5px 10px; height:38px;">
                <i data-time-icon="icon-time" data-date-icon="icon-calendar" class="fa fa-calendar"></i>
            </span>
            </div>
        </div>'; ?>
            <script>
            $(function() {
            var d = new Date();
            var month = d.getMonth()+1;
            var day = d.getDate();

            var output = d.getFullYear() + '-' +
            (month<10 ? '0' : '') + month + '-' +
            (day<10 ? '0' : '') + day;

            $("#datetimepicker-input").val(output);
                $('#datetimepicker').datetimepicker({
                pickTime: false,
                autoclose: true
                }).on('changeDate', function (ev) {
                    $(this).datetimepicker('hide');
                });
            });
            </script>
        <?php echo'
        <div class="col-sm-3 form-group">
            <label class=form-name" style="display: inline-block; margin-bottom: .5rem;">Account</label>
            <div class="account-holder" style="position:relative; width:250px;">
                <input name="account" type="text" id="account-input" class="form-control" style="width:250px;" />
                <input name="type" type="hidden" id="account-input-val" />
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
            <td><input name="name[0]" class="form-control"></td>
            <td><textarea name="description[0]" class="form-control" style="height:38px;"></textarea></td>
            <td><select name="pay_method[0]" class="form-control">
            <option></option>
            <option>Cash</option>
            <option>Check</option>
            <option>Credit Card</option>
            <option>Outstanding</option>
            <option>Payment Recon</option>
            <option>Paypal</option>
            <option>Sales Reciept</option>
            </select></td>
            <td><input name="pay_amount[0]" class="form-control"></td>
            <td style="vertical-align:middle;"><a data-toggle="tooltip" data-placement="top" title="Delete" class="btn btn-danger"><i class="fa fa-trash-o"></i></a></td>
        </tr>
        </tbody>
        </table>
        </div>

        <div class="col-xs-12" style="margin-bottom:50px;">
        <input class="btn" type="button" value="Add Row" onclick="addRow();">
        </div>
    </div>
    <div class="col-xs-12" id="save-container">
        <button class="btn" style="color:#fff; width: 130px; float: right; background: #0C0;margin:10px; margin-right:30px;" value="Save"/>Save</button>
        </div>
        </form>';

?>
<link rel="stylesheet" type="text/css" href="js/bootstrap-datetimepicker.min.css">
<script language="JavaScript" src="js/bootstrap-datetimepicker.min.js"></script>
<script src="js/autosize.js"></script>
<script>

     $("#file-form").submit(function(e){
        e.preventDefault();

        var formData = new FormData(this);
        $.ajax({
        type : 'POST',
        url  : 'add_expense.php',
        data : formData,
        success :  function(data) {
            $('#wrapper').html(data);

	       },
            cache: false,
        contentType: false,
        processData: false
        });
    });


    function submitFile(){
        $('#file-form').submit();

    };

    function submitExcel(){
        $("#file-cont-form").submit();
    };


    var i =1;
    function addRow(){

    $('.hello').after('<tr class="row'+i+'"><td><input name="name['+i+']" class="form-control"></td><td><textarea name="desciption['+i+']" class="form-control" style="height:38px;"></textarea></td><td><select name="pay_method['+i+']" class="form-control" ><option></option><option>Cash</option><option>Check</option><option>Credit Card</option><option>Outstanding</option><option>Payment Recon</option><option>Paypal</option><option>Sales Reciept</option></select></td><td><input name="pay_amount['+i+']" class="form-control"></td><td style="vertical-align:middle;"><a onclick="removeRow('+i+');"data-toggle="tooltip" data-placement="top" title="Delete" class="btn btn-danger danger-danger"><i class="fa fa-trash-o"></i></a></td></tr>');
       i++;

        autosize(document.querySelectorAll('textarea'));
    };

    function removeRow(i){
        $('.row'+i).remove();
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
