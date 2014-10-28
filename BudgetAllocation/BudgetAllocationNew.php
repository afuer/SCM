<?php
include '../lib/DbManager.php';
include("../body/header.php");
include 'BudgetAllocationDAL.php';

$Requisition_Id = getParam('search_id');
$flow_list_id = getParam('flow_list_id');


$BudgetAllocationCheck = findValue("SELECT * FROM budget_allocation WHERE REQUISITION_ID = '$Requisition_Id'");

$ObjRequisitionData = new RequisitionData();

if (isSave()) {

    $ExpenseAccount = getParam('ExpenseAccount');
    $price = getParam('totalPrice');
    $productId = getParam('productId');

    foreach ($ExpenseAccount as $key => $value) {
        $DetailId = getParam(RequisitionDetailID);
        $ProductGroupID = getParam('product_group');
        $TotalPrice = $price[$key];
        $cc_id = getParam('cc_id');
        //if ($BudgetAllocationCheck == '') {
        $ObjRequisitionData->Save("$Requisition_Id", "$DetailId[$key]", "$value", "$ProductGroupID[$key]", "$employeeId", "$cc_id[$key]", "$TotalPrice", "$productId[$key]");
        //sql("UPDATE requisition_details SET UNIT_PRICE='$price[$key]' WHERE REQUISITION_DETAILS_ID='$DetailId[$key]'");
        //}
    }
    echo "<script>location.replace('BudgetConfigaration.php?search_id=$Requisition_Id&flow_list_id=$flow_list_id');</script>";
}



$ResultRequisitionMain = find("SELECT rm.REQUISITION_NO, rm.REQUISITION_DATE, rm.CREATED_BY,
        e.CARD_NO, amt.AMOUNT_TYPE_ID, rm.PR_BUDGETED, et.EXPENSE_TYPE_ID, et.EXPENSE_TYPE_NAME, 
        wt.WORKFLOW_TYPE_NAME, rm.APPROVE_FILE_TYPE, rm.APPROVE_FILE_PATH,
        rm.SPECIFICATION, rm.JUSTIFICATION, rm.REMARK, 
	CONCAT(e.FIRST_NAME,' ',e.LAST_NAME, '->',e.CARD_NO,' (', d.DESIGNATION_NAME,')') AS FULL_NAME,
        AMOUNT_TYPE_NAME, workflow_name,rr.PRIORITY_NAME,rm.PRIORITY_ID

        FROM requisition AS rm
        LEFT JOIN expense_type AS et ON et.EXPENSE_TYPE_ID=rm.EXPENSE_TYPE_ID
        LEFT JOIN workflow_type AS wt ON wt.WORKFLOW_TYPE_ID=rm.WORKFLOW_TYPE_ID
        LEFT JOIN amount_type AS amt ON amt.AMOUNT_TYPE_ID=rm.AMOUNT_TYPE_ID 
        LEFT JOIN employee AS e ON e.EMPLOYEE_ID=rm.CREATED_BY
        LEFT JOIN workflow_group AS wg ON wg.workflow_group_id=rm.WORKFLOW_GROUP_ID
        LEFT JOIN priority AS rr ON rr.PRIORITY_ID = rm.PRIORITY_ID
        LEFT JOIN designation d ON d.DESIGNATION_ID=e.DESIGNATION_ID
        WHERE rm.REQUISITION_ID ='$Requisition_Id'");

$AccountList = $ObjRequisitionData->AccountList();
?>
<div Title='Requisition List' class="easyui-panel" style="height:1000px;" >

    <fieldset class="fieldset">
        <legend>Requisition Information</legend>
        <table>
            <tr> 
                <td width="80">PR No:  </td>
                <td width="200"><?php echo $ResultRequisitionMain->REQUISITION_NO; ?></td>
                <td width="150">Staff Member:</td>
                <td><?php echo $ResultRequisitionMain->FULL_NAME . ' (' . $ResultRequisitionMain->CARD_NO . ')'; ?></td>
            </tr>
            <tr>
                <td>Date:</td>
                <td><?php echo bddate($ResultRequisitionMain->REQUISITION_DATE); ?></td>
                <td>Location :</td>
                <td><?php echo user_location($ResultRequisitionMain->CREATED_BY); ?></td>
            </tr>
            <tr>
                <td>Req From:</td>
                <td><?php echo $ResultRequisitionMain->CREATED_BY; ?></td>
                <td>Priority:</td>
                <td><?php echo $ResultRequisitionMain->PRIORITY_NAME; ?></td>
            </tr>                    
        </table>
    </fieldset>
    <br/>
    <?php
    $CcList = $ObjRequisitionData->get_cc_list_by_requisition_id($Requisition_Id);
    while ($RowCc = fetch_object($CcList)) {
        $year = date('Y');
        ?>
        <h1>CC Account: <?php echo $RowCc->COST_CENTER_CODE . '-' . $RowCc->COST_CENTER_NAME . ' (' . $RowCc->CC_PERCENT . '%)'; ?> </h1>
        <!--<a class="button" target="_blank" href="../Finance/CBLBudgetList.php?costcenter_id='<?php echo $RowCc->REQUISITION_CC_ID; ?>'&year_of='<?php echo $year; ?>'">View Budget</a>-->

        <form action="" method="POST" class="form">
            <input type="hidden" name="flow_list_id" value="<?php echo $flow_list_id; ?>"/>

            <table class="ui-state-default" >
                <thead>
                <th width="30" >S/N</th>
                <th>Product Name </th>
                <th width="80">Qty</th>
                <th width="50">Price</th>
                <th width="100">Total</th>
                <th width="60">Line Budget</th>
                </thead>

                <tbody>  
                    <?php
                    $sl = 1;
                    $IncomingData = $ObjRequisitionData->GetData($Requisition_Id, $RowCc->REQUISITION_CC_ID);
                    while ($Row = fetch_object($IncomingData)) {
                        ?>  
                    <input type="hidden" name="totalPrice[]" value="<?php echo (($RowCc->CC_PERCENT * $Row->price) / 100); ?>" />
                    <input type="hidden" name="cc_id[]"  value="<?php echo $RowCc->REQUISITION_CC_ID; ?>"/>
                    <input type="hidden" name="RequisitionDetailID[]"  value="<?php echo $Row->REQUISITION_DETAILS_ID; ?>"/>
                    <input type="hidden" name="productId[]"  value="<?php echo $Row->product_id; ?>"/>
                    <tr>
                        <td><?php echo $sl; ?>.</td>
                        <td><?php echo $Row->PRODUCT_NAME; ?></td>
                        <td align="center"><?php echo $Row->quantity . ' ' . $Row->UNIT_TYPE_NAME; ?></td>
                        <td align="right"><?php echo $Row->unite_price; ?></td>
                        <td align="right"><?php echo (($RowCc->CC_PERCENT * $Row->price) / 100); ?></td>
                        <td align="center"><?php comboBox("ExpenseAccount[]", $AccountList, "$Row->EXPENCE_ACCOUNT_ID", TRUE, 'require') ?></td>
                    </tr>
                    <?php
                    $sl++;
                }
                ?>
                </tbody>
            </table>
            <br/>
        <?php } ?>

        <button type="submit" class="button" name="save" value="save">Update</button>
    </form>
</div>

<?php include '../body/footer.php'; ?>