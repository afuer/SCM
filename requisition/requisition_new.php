<?php
include '../lib/DbManager.php';
include("../body/header.php");

$requisition_type_id = getParam('requisition_type_id');
$processDeptId = getParam('processDeptId');
$processDept = getParam('processDept');
//$search_id = getParam('search_id');
//$search_id = $search_id == '' ? $userName : $search_id;
$MaxRequisitionMainId = NextId('requisition', 'REQUISITION_ID');
$sql = "SELECT FIRST_NAME, LAST_NAME FROM employee e WHERE EMPLOYEE_ID='$employeeId'";
$RquisitionType = $db->findValue("SELECT REQUISITION_TYPE_NAME FROM requisition_type WHERE REQUISITION_TYPE_ID='$requisition_type_id'");
//$processDept = $db->findValue("SELECT PROCESS_DEPT_NAME FROM process_dept WHERE PROCESS_DEPT_ID='$processDeptId'");

//$requisitionId = NextId('requisition', 'requisition_id');
$var = find($sql);
$requisitionForName = $db->findValue("SELECT REQUISITION_TYPE_NAME FROM requisition_type WHERE REQUISITION_TYPE_ID='$requisitionFor'");


$costCenterList = $db->rs2array("SELECT COST_CENTER_ID, COST_CENTER_CODE, COST_CENTER_NAME FROM cost_center ORDER BY COST_CENTER_NAME");
//$costCenterList = $db->rs2array("SELECT COST_CENTER_ID, COST_CENTER_NAME FROM cost_center ORDER BY COST_CENTER_NAME");
$solList = rs2array(query("SELECT sol_id, sol_code, SOL_NAME FROM sol ORDER BY SOL_NAME"));
$productList = $db->rs2array("SELECT p.PRODUCT_ID, p.PRODUCT_NAME
        FROM product p
        WHERE p.PROCESS_DEPT_ID='$processDeptId' AND p.PRODUCT_TYPE_ID='$requisition_type_id' 
        ORDER BY PRODUCT_NAME");

comboBox('sol', $solList, '', TRUE, 'autoHight');
comboBox('costCenter', $costCenterList, '', TRUE, 'autoHight');
comboBox('product', $productList, '', TRUE, 'autoHight');
?>

<script src = "include.js" type = "text/javascript" ></script>
<script src="requisition_new.js" type="text/javascript" ></script>

<script>
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
        border: 1px dotted #DADADA;
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
<link rel="stylesheet" type="text/css" href="../jquery-ui/css/ui-lightness/jquery-ui-1.8.16.custom.css" />
<script type="text/javascript" src="../jquery-ui/js/jquery-ui-1.8.16.custom.min.js"></script>
<script type='text/javascript' src="../public/js/jquery-ui-autocomplete.js"></script>
<script type='text/javascript' src="../public/js/jquery.select-to-autocomplete.min.js"></script>

<div Title='Requisition New' class="easyui-panel" style="height:800px;" > 

    <form action="" method="POST" name='requisition' id='requisition' autocomplete="off" enctype="multipart/form-data">
        <input type="hidden" name="requisitionId" id="requisitionId" value="<?php echo $maxRequisitionId ?>" />
        <input type="hidden" name="mode" value="<?php echo $mode ?>" />
        <input type="hidden" name="search_id" value="<?php echo $search_id ?>" />
        <input type="hidden" name="requisition_type_id" id="requisition_type_id" value="<?php echo $requisition_type_id; ?>"/>
        <input type="hidden" name="processDept" id="requisition_type_id" value="<?php echo $processDept; ?>"/>

        <?php include '../GeneralProcure/ajax_requisition_header.php'; ?>
        <br/>

        <table id="productGrid" style="width: 800px;">
            <thead>
            <th width="450">Product</th>
            <th width="150">Qty</th>
            <th width="150">Remark</th>
            <th width="50">Action</th>
            </thead>
            <tbody></tbody>
        </table>
        <div style="margin-left: 685px; font-weight: bold;" id="ProductGrantTotal">00.00</div>

        <button type="button" class="button" title="productTab" onclick="addtr();">Add More</button>
        <a href="../Admin/RequestNew.php?mode=new&request_id=1" target="_blank" class="button">Add New Product Request</a>
        <a href="../ERP/ProductsList.php" target="_blank" class="button">View Product List</a>
        <hr style="width: 800px;"><br/>

        <?php file_upload_html(TRUE); ?>

        <br/>
        <fieldset class="fieldset" style="width: 780px;"> 
            <legend>Comments</legend>
            <table>
                <tr>
                    <td valign="top">Specification:</td>
                    <td><textarea name="specification" style="width: 200px;" class="easyui-validatebox" data-options="required:true"></textarea></td>
                    <td valign="top">Justification:</td>
                    <td><textarea name="justification" style="width: 200px;" class="easyui-validatebox" data-options="required:true"></textarea></td>       
                </tr>
                <tr>
                    <td valign="top">On The behalf Off:</td>
                    <td><textarea name="freeText" style="width: 200px;" class="easyui-validatebox" data-options="required:true"></textarea></td>
                    <td valign="top">Help Desk No:</td>
                    <td><textarea name="helpDesk" style="width: 200px;" class="easyui-validatebox" data-options="required:true"></textarea></td>       
                </tr>
                <tr>
                    <td valign="top">Remark:</td>
                    <td colspan="3"><textarea name="remark" style="width: 500px;" class="easyui-validatebox" data-options="required:true"></textarea></td>
                </tr>                
            </table>
        </fieldset>
        <button type="button" name="save" value="SaveRequisition" class="button" onclick="saveRequisition();">Save</button>
    </form>

</div>


<?php include '../body/footer.php'; ?>
