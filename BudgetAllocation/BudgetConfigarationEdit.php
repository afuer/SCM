<?php
include '../lib/DbManager.php';
include 'BudgetAllocationDAL.php';


$Requisition_Id = getParam('search_id');
$flow_list_id = getParam('flow_list_id');

$ObjRequisitionData = new RequisitionData();

if (isSave()) {
    $ExpenseAccount = getParam('ExpenseAccount');
    if (isset($ExpenseAccount)) {
        foreach ($ExpenseAccount as $key => $value) {

            $ProductGroupID = getParam('product_group');
            $cc_id = getParam('cc_id');
            $ObjRequisitionData->Save("$Requisition_Id", "$value", "$ProductGroupID[$key]", "$user_name", "$cc_id[$key]");
        }
    }

    $AllocationID = getParam('AllocationID');

    if (isset($AllocationID)) {
        foreach ($AllocationID as $key => $value) {
            $TaxType = getParam('TaxType');
            $Tax = getParam('Tax');
            $Vat = getParam('Vat');
            $Payable = getParam('Payable');


            $ObjRequisitionData->UpdateBudgetConfig("$AllocationID[$key]", "$TaxType[$key]", "$Tax[$key]", "$Vat[$key]", "$Payable[$key]");
        }
    }

    echo "<script>location.replace('../GeneralProcure/RequisitionApproval.php?mode=search&search_id=$Requisition_Id&flow_list_id=$flow_list_id');</script>";
}






$ResultRequisitionMain = $ObjRequisitionData->ResultRequisitionMain($Requisition_Id);
//$AccountList = $ObjRequisitionData->AccountList();
//$product_group_list = rs2array(query("SELECT product_group_ID, PRODUCT_GROUP_NAME FROM product_group"));
$TaxTypeList = rs2array(query("SELECT TAX_TYPE_ID,TAX_TYPE FROM tax_type"));

include("../body/header.php");
?>
<script type="text/javascript" src="BudgetAllocation.js"></script>
<div Title='Requisition List' class="easyui-panel" style="height:1000px;" >
    <fieldset class="fieldset">
        <legend >Requisition Information</legend>
        <table>
            <tbody>
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
                    <td><?php echo $ResultRequisitionMain->CREATED_BY; ?></td>
                    <td>Priority:</td>
                    <td><?php echo $ResultRequisitionMain->PRIORITY_NAME; ?></td>
                </tr>                    
            </tbody>
        </table>
    </fieldset>
    <br/>
    <form method = "POST" action="" class="form">
        <?php
        $sl = 1;
        $CcList = $ObjRequisitionData->get_cc_list_by_requisition_id($Requisition_Id);
        while ($RowCc = fetch_object($CcList)) {
            $year = date('Y');
            ?>
            <h1>CC Account: <?php echo $RowCc->cost_center_name; ?></h1>

            <table class = "ui-state-default" >
                <thead>
                <th width = "20">SL</th>
                <th width = "100">GL Number</th>
                <th> GL Head</th>
                <th width = "100">Bill Amount</th>
                <th width = "150">Tax Info</th>
                <th width = "20">Tax(%)</th>
                <th width = "20">Vat(%)</th>
                <th width = "50">Tax Amount</th>
                <th width = "50">Vat Amount</th>
                <th width = "100">Net Payable</th>
                <th width="100">Group</th>
                <th></th>
                </thead>
                <tbody>
                    <?php
                    $sl = 1;
                    $IncomingData = $ObjRequisitionData->GetDataBudgetConfig($Requisition_Id, $RowCc->REQUISITION_CC_ID);
                    while ($Row = fetch_object($IncomingData)) {
                        $TaxAmount = ($Row->TOTAL * $Row->GlTax) / 100;
                        $VatAmount = ($Row->TOTAL * $Row->GlVat) / 100;
                        ?>
                        <tr>

                            <td><?php echo $sl; ?>.<input type="hidden" name="AllocationID[<?php echo $Row->BUDGET_ALLOCATION_ID; ?>]" value="<?php echo $Row->BUDGET_ALLOCATION_ID; ?>" /></td>
                            <td><?php echo $Row->GL_ACCOUNT_CODE; ?></td>
                            <td><?php echo $Row->GL_ACCOUNT_NAME; ?></td>
                            <td><input style="width: 100%;" type="text" value="<?php echo $Row->TOTAL; ?>" class="BillAmount" readonly="readonly"></td>
                            <td> <?php combobox("TaxType[$Row->BUDGET_ALLOCATION_ID]", $TaxTypeList, $Row->TAX_TYPE_ID, true, 'TaxVATStatus'); ?></td>
                            <td class="tax_td"><input style="width:100%; height: 25px;"  type="text" name="Tax[<?php echo $Row->BUDGET_ALLOCATION_ID; ?>]" value="<?php echo $Row->TAX; ?>" class="tax"/></td>
                            <td><input style="width:100%; height: 25px;" type="text" name="Vat[<?php echo $Row->BUDGET_ALLOCATION_ID; ?>]" value="<?php echo $Row->VAT; ?>" class="vat" /></td>
                            <td><input style="width:100%; height: 25px;" type="text" name="TaxAmount[<?php echo $Row->BUDGET_ALLOCATION_ID; ?>]" readonly="readonly" value="<?php echo $TaxAmount; ?>" class="tax_amount" /></td>
                            <td><input style="width:100%; height: 25px;" type="text" name="VatAmount[<?php echo $Row->BUDGET_ALLOCATION_ID; ?>]" readonly="readonly" value="<?php echo $VatAmount; ?>" class="vat_amount" /></td>
                            <td><input style="width:100%; height: 25px;" type="text" name="Payable[<?php echo $Row->BUDGET_ALLOCATION_ID; ?>]"  value="<?php echo $Row->PAYABLE; ?>" class="PayableAmount"/></td>
                            <td><?php echo $Row->PRODUCT_GROUP_NAME; ?></td>
                            <td><a target="_blank" href="../ManagementBudget/BudgetHistory.php?cc_id=<?php echo $RowCc->REQUISITION_CC_ID; ?>&year_of=<?php echo $year; ?>&gl_id=<?php echo $Row->GL_ACCOUNT_ID; ?>">View Budget</a></td>

                        </tr>
                        <?php
                        $sl++;
                    }
                    ?>
                </tbody>
            </table>

            <br/>
            <?php
        }
        ?>
        <button type="submit" class="button" name="save" value="save">Update</button>
        <a class="button" href="BudgetAllocationEdit.php?mode=search&search_id=<?php echo $Requisition_Id . '&flow_list_id=' . $flow_list_id; ?>">Back</a>
    </form>
</div>
<br/>


<?php include '../body/footer.php'; ?>