<?php
include '../lib/DbManager.php';
include("../body/header.php");
include 'BudgetAllocationDAL.php';
$ObjRequisitionData = new RequisitionData();

$SearchId = getParam('search_id');


$ResultRequisitionMain = find("SELECT rm.REQUISITION_NO, rm.REQUISITION_DATE, rm.CREATED_BY,
        e.CARDNO, amt.AMOUNT_TYPE_ID, rm.BUDGET, et.EXPENSE_TYPE_ID, et.EXPENSE_TYPE_NAME, 
        wt.WORKFLOW_TYPE_NAME, rm.MANAGEMENT_APPROVE_FILE, rm.BOARD_APPROVE_FILE,
        rm.SPECIFICATION, rm.JUSTIFICATION, rm.REMARK, e.FULL_NAME,
        AMOUNT_TYPE_NAME, workflow_name

        FROM gp_requesition AS rm
        LEFT JOIN expense_type AS et ON et.EXPENSE_TYPE_ID=rm.EXPENSE_TYPE_ID
        LEFT JOIN workflow_type AS wt ON wt.WORKFLOW_TYPE_ID=rm.WORKFLOW_TYPE_ID
        LEFT JOIN amount_type AS amt ON amt.AMOUNT_TYPE_ID=rm.AMOUNT_TYPE_ID 
        LEFT JOIN employee_details AS e ON e.CARDNO=rm.CREATED_BY
        LEFT JOIN workflow_group AS wg ON wg.workflow_group_id=rm.WORKFLOW_GROUP_ID
        WHERE rm.REQUISITION_ID ='$SearchId'");




?>


<fieldset class="fieldset"> 
    <legend>GL Wise Allocation</legend>
    <?php
    $CcList = $ObjRequisitionData->get_gl_list_by_requisition_id($SearchId);
    while ($RowCc = fetch_object($CcList)) {
        ?>
        <h1>PR: <?php echo $RowCc->REQUISITION_NO . '-' . $RowCc->GL_ACCOUNT_CODE; ?></h1>

        <form action="" method="POST" class="form">
            <table class="ui-state-default" >
                <thead>
                <th width="10">SL</th>
                <th>Product Name </th>
                <th width="40">Qty</th>
                <th width="50">Price</th>
                <th width="100">Total</th>
                <th width="300">Line Budget</th>

                </thead>

                <tbody>  
                    <?php
                    $sl = 1;
                    $IncomingData = $ObjRequisitionData->GetDataGL($SearchId, $RowCc->GL_TYPE_ID);
                    while ($Row = fetch_object($IncomingData)) {
                        ?>  
                        <tr>
                            <td><?php echo $sl; ?></td>
                            <td><?php echo $Row->PRODUCT_NAME; ?></td>
                            <td align="center"><?php echo $Row->QTY . ' ' . $Row->UNIT_TYPE_NAME; ?></td>
                            <td align="right"><?php echo $Row->UNIT_PRICE; ?></td>
                            <td align="right"><?php echo $Row->total; ?></td>
                            <td > <?php echo $Row->GL_ACCOUNT_NAME . '-' . $Row->GL_ACCOUNT_CODE; ?></td>

                        </tr>
                        <?php
                        $sl++;
                    }
                    ?>
                </tbody>
            </table>
        </form>
        <br/>
    <?php } ?>


    <?php include '../body/footer.php'; ?>
