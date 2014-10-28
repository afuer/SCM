<?php
include '../lib/DbManager.php';
include 'BudgetAllocationDAL.php';
$ObjRequisitionData = new RequisitionData();

$Requisition_Id = getParam('search_id');
$flow_list_id = getParam('flow_list_id');


if (isSave()) {

    $ExpenseAccount = getParam('ExpenseAccount');
    foreach ($ExpenseAccount as $key => $value) {

        $ProductGroupID = getParam('product_group');

        $ObjRequisitionData->Update("$value", "$ProductGroupID[$key]", "$user_name", "$key");
    }
    echo "<script>location.replace('BudgetConfigaration.php?search_id=$Requisition_Id&flow_list_id=$flow_list_id');</script>";
}

$ResultRequisitionMain = find("SELECT rm.REQUISITION_NO, rm.REQUISITION_DATE, rm.CREATED_BY,
        e.CARDNO, amt.AMOUNT_TYPE_ID, rm.BUDGET, et.EXPENSE_TYPE_ID, et.EXPENSE_TYPE_NAME, 
        wt.WORKFLOW_TYPE_NAME, rm.MANAGEMENT_APPROVE_FILE, rm.BOARD_APPROVE_FILE,
        rm.SPECIFICATION, rm.JUSTIFICATION, rm.REMARK, e.FULL_NAME,
        AMOUNT_TYPE_NAME, workflow_name,rr.PRIORITY_NAME,rm.PRIORITY_ID

        FROM gp_requesition AS rm
        LEFT JOIN expense_type AS et ON et.EXPENSE_TYPE_ID=rm.EXPENSE_TYPE_ID
        LEFT JOIN workflow_type AS wt ON wt.WORKFLOW_TYPE_ID=rm.WORKFLOW_TYPE_ID
        LEFT JOIN amount_type AS amt ON amt.AMOUNT_TYPE_ID=rm.AMOUNT_TYPE_ID 
        LEFT JOIN employee_details AS e ON e.CARDNO=rm.CREATED_BY
        LEFT JOIN workflow_group AS wg ON wg.workflow_group_id=rm.WORKFLOW_GROUP_ID
        LEFT JOIN priority AS rr ON rr.PRIORITY_ID = rm.PRIORITY_ID
        WHERE rm.REQUISITION_ID ='$Requisition_Id'");



$AccountList = $ObjRequisitionData->AccountList();


include("../body/header.php");
?>
<div Title='Requisition List' class="easyui-panel" style="height:1000px;" >
    <fieldset class="fieldset"> 
        <table>
            <tr>
                <td width="150">Requisition no:  </td>
                <td><?php echo $ResultRequisitionMain->REQUISITION_NO; ?></td>
                <td width="150">Staff Member:</td>
                <td><?php echo $ResultRequisitionMain->GIVENNAME . '(' . $ResultRequisitionMain->CARDNO . ')'; ?></td>
            </tr>
            <tr>
                <td>Requisition date:</td>
                <td> <?php echo bddate($ResultRequisitionMain->REQUISITION_DATE); ?></td>
                <td>Location :</td>
                <td><?php echo user_location($ResultRequisitionMain->CREATED_BY); ?></td>
            </tr>
            <tr>
                <td>Created by:</td>
                <td><?php echo $ResultRequisitionMain->CARDNO; ?></td>
                <td>Priority:</td>
                <td><?php echo $ResultRequisitionMain->PRIORITY_NAME; ?></td>
            </tr>                    
        </table>

        <legend >Requisition Details</legend>
        <br/>
        <?php
        $CcList = $ObjRequisitionData->get_cc_list_by_requisition_id($Requisition_Id);
        while ($RowCc = fetch_object($CcList)) {
            $year = date('Y');
            ?>
            <h1>CC Account: <?php echo $RowCc->cost_center_name; ?></h1>
            <a class="button" target="_blank" href="../Finance/CBLBudgetList.php?costcenter_id=<?php echo $RowCc->REQUISITION_CC_ID; ?>&year_of=<?php echo $year; ?>">View Budget</a>
            <form action="" method="POST">
                <table class="ui-state-default" >
                    <thead>
                    <th width="10">SL</th>
                    <th>Product Name</th>
                    <th width="40">Qty</th>
                    <th width="30">Price</th>
                    <th width="100">Total Price</th>
                    <th>Line Budget</th>
                    </thead>

                    <tbody>  
                        <?php
                        $sl = 1;
                        $IncomingData = $ObjRequisitionData->GetFromBudgeAllocation("$Requisition_Id", "$RowCc->REQUISITION_CC_ID ");

                        while ($Row = fetch_object($IncomingData)) {
                            ?>  
                            <tr>
                                <td><?php echo $sl; ?>.</td>
                                <td><?php echo $Row->product_name ?></td>
                                <td align="center"><?php echo $Row->QTY . ' ' . $Row->UNIT_TYPE_NAME; ?></td>
                                <td align="right"><?php echo $Row->UNIT_PRICE; ?></td>
                                <td align="right"><?php echo formatMoney($Row->QTY * $Row->UNIT_PRICE); ?></td>
                                <td align="center"><?php comboBox("ExpenseAccount[$Row->BUDGET_ALLOCATION_ID]", $AccountList, $Row->EXPENCE_ACCOUNT_ID, TRUE) ?></td>
                            </tr>
                            <?php
                            $sl++;
                        }
                        ?>
                    </tbody>

                </table>
                <br/>
            <?php }
            ?>
            <button type="submit" class="button" name="save" value="save">Update</button>
        </form>

    </fieldset>
</div>
<?php include("../body/footer.php"); ?>