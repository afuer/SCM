<?php
include_once '../lib/DbManager.php';
include("../body/header.php");

$processDeptId = getParam('processDeptId');


include("RequisitionDAL.php");



$requisition_type_id = getParam('requisition_type_id');
$processDeptId = getParam('processDeptId');
$requisitionId = NextId('requisition', 'REQUISITION_ID');

$sql = "SELECT FIRST_NAME, LAST_NAME FROM employee e WHERE EMPLOYEE_ID='$employeeId'";
$RquisitionType = $db->findValue("SELECT REQUISITION_TYPE_NAME FROM requisition_type WHERE REQUISITION_TYPE_ID='$requisition_type_id'");
$processDept = $db->findValue("SELECT PROCESS_DEPT_NAME FROM process_dept WHERE PROCESS_DEPT_ID='$processDeptId'");
$var = find($sql);

$costCenterList = $db->rs2array("SELECT COST_CENTER_ID, COST_CENTER_CODE, COST_CENTER_NAME FROM cost_center ORDER BY COST_CENTER_NAME");
//$costCenterList = $db->rs2array("SELECT COST_CENTER_ID, COST_CENTER_NAME FROM cost_center ORDER BY COST_CENTER_NAME");
$solList = GetSole();
$productList = $db->rs2array("SELECT p.PRODUCT_ID, p.PRODUCT_NAME
        FROM product p
        WHERE p.PROCESS_DEPT_ID='$processDeptId' AND p.PRODUCT_TYPE_ID='$requisition_type_id' 
        ORDER BY PRODUCT_NAME");

comboBox('sol', $solList, '', TRUE, 'autoHight');
comboBox('costCenter', $costCenterList, '', TRUE, 'autoHight');
comboBox('product', $productList, '', TRUE, 'autoHight');
?>
<link rel="stylesheet" type="text/css" href="../jquery-ui/css/ui-lightness/jquery-ui-1.8.16.custom.css" />
<script type="text/javascript" src="../jquery-ui/js/jquery-ui-1.8.16.custom.min.js"></script>
<script type="text/javascript" src="../public/js/jquery.calculation.js"></script>

<script src="Requisition.js"></script>
<script src = "include.js" type = "text/javascript" ></script>
<script src="requisition_new.js" type="text/javascript" ></script>
<script type='text/javascript' src="../public/js/jquery-ui-autocomplete.js"></script>
<script type='text/javascript' src="../public/js/jquery.select-to-autocomplete.min.js"></script>


<script type="text/javascript">
    $(document).ready(function() {
        $('.add-button').click(function() {
            $(this).closest('tr').after('<tr><td></td><td colspan="3">CC: <input type="text"/></td></tr>');
            //$("<tr>").insertAfter($(this).parents("tr").eq(0));


        });
        //allAutocomplate();

    });

    function addCcaaa(obj) {
        var newtr = '<div class="cc"><input type="text" class="Costcenter"/> <input type="text"/></div>';


        $('.Costcenter', newtr).autocomplete({
            source: 'autocomplate_search_product.php',
            minLength: 1,
            select: function(evt, ui)
            {
                var itemrow = $(this).closest('tr');
                itemrow.find('.unit').html(ui.item.unit);
                itemrow.find('.price').text(ui.item.price);
            }
        });
        obj.closest('td').append(newtr);
    }

    function addCc(obj) {

        var itemrow = obj.closest('tr');
        var productId = itemrow.find('.product').val();

        var newtr = '<div class="fc left-td float-left">\n\
        <select class="costCenter" name="CostCenter[' + productId + ']" placeholder="Cost Center">' + $('#costCenterID').html() + '</select>\n\
        <select class="sol" name="sol[' + productId + ']" placeholder="Sol">' + $('#solID').html() + '</select>\n\
        <input type="text" class="Amount" name="Amount[' + productId + ']" placeholder="Amount"/>\n\
        </div>';

        obj.closest('tr').next('tr').find('.subTd').append(newtr);
        //allAutocomplate();

        //console.log(product);
    }

    function allAutocomplate() {
        $('select').selectToAutocomplete();
    }

</script>

<style type="text/css">


    .autoHight{position: fixed; bottom: -100px;}

    .cc{background-color: #EEFFEE;}
    div.cc{padding: 5px 0px;}
    table.tableSub{margin: 5px 5px;}
    #productGrid{
        border-collapse: collapse;
    }
    .left-td{margin-left: 15px; color: gray;}

    table#productGrid tr td {
        border-bottom: 1px dotted #DADADA;
        font-size: 10pt;
        padding: 2px 10px;
    }
    .float-left{padding: 1px 5px;}


    table#productGrid th {
        background: url("../../jquery-ui/css/ui-lightness/images/ui-bg_glass_100_f6f6f6_1x400.png") repeat-x scroll 50% 50% #F6F6F6;
        border: 1px solid #CCCCCC;
        color: black;
        cursor: pointer;
        height: 25px;
        padding-left: 5px;
        text-align: center;
        text-transform: capitalize;
    }


    form table:not(.ui-state-default) tr td input[type="text"] {
        border-color: gainsboro;
        height: 20px;
        width: 200px;
    }


    form table tr td select {
        border-color: gainsboro;
        height: 25px;
        max-width: 250px;
        min-width: 200px;
    }
</style>

<div Title='Requisition New' class="easyui-panel" style="height:1000px;" > 

    <form action="" method="POST" name='requisition' class="formValidate" autocomplete="off" enctype="multipart/form-data">
        <input type="hidden" name="mode" value="new" />
        <input type="hidden" name="search_id" value="<?php echo $search_id ?>" />
        <input type="hidden" name="requisition_type_id" value="<?php echo $requisition_type_id ?>" />
        <!--<input type="hidden" name="processDeptId[]" value="<?php echo $processDeptId ?>" />-->

<?php include './ajax_requisition_header.php'; ?>
        <hr>
        <table id="productGrid" style="width: 800px;" >
            <thead>
            <th width="450">Product</th>
            <th width="150">Qty</th>
            <th width="150">Price</th>
            <th width="80">Total</th>
            <th width="150">Remark</th>
            <th width="120">Action</th>
            </thead>
            <tbody></tbody>
        </table>
        <div style="margin-left: 545px; font-weight: bold;" id="ProductGrantTotal">00.00</div>

        <button type="button" class="button" title="productTab" onclick="addtr();">Add More</button>
        <a href="../product/product_get_list.php" target="_blank" class="button">View Product List</a>
        <hr><br/>

        <fieldset class="fieldset" style="width: 780px;"> 
            <legend>File Attachment</legend>
            <table id="addAttach" class="table">
                <tr>
                    <td width="180" colspan="2"><input type="radio" name="approval" value="Board"/> Board Approval <input type="radio" name="approval" value="Management"/> Management Approval</td>

                </tr>
                <tr>
                    <td>Attach File: </td>
                    <td><input type="file" name="file_one"/></td>
                </tr>

            </table>
        </fieldset>
        <br/>
<?php file_upload_html(TRUE); ?>
        <fieldset class="fieldset" style="width: 780px;"> 
            <legend>Comments</legend>
            <table>
                <tr>
                    <td valign="top">Specification:</td>
                    <td><textarea name="specification" style="width: 200px;" class="required" ></textarea></td>
                    <td valign="top">Justification:</td>
                    <td><textarea name="justification" style="width: 200px;" class="required" ></textarea></td>       
                </tr>
                <tr>
                    <td valign="top">Remark:</td>
                    <td colspan="3"><textarea name="remark" style="width: 500px;" ></textarea></td>
                </tr>                
            </table>
        </fieldset>
        <button type="submit" name="save" value="SaveRequisition" class="button">Save</button>

    </form>

</div>



<?php include '../body/footer.php'; ?>
