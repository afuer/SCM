<?php
include '../lib/DbManager.php';
include '../body/header.php';
include '../lib/pagination.php';


if (isSave()) {
    $RequisitionID = getParam('RequisitionID');

    foreach ($RequisitionID as $key => $value) {

        $sqlUpdate = "UPDATE gp_requesition SET ISPAID = '1'WHERE REQUISITION_ID = '$RequisitionID[$key]'";
        sql($sqlUpdate);
    }
}



$SqlPayment = "SELECT rm.REQUISITION_ID, rm.REQUISITION_NO, CONCAT(e.FULL_NAME,' (',rm.CREATED_BY, ')') AS 'Requisition_from', rm.REQUISITION_DATE,rm.CREATED_BY,SUM(ba.PAYABLE) As PAYABLE 
FROM gp_requesition As rm 
LEFT JOIN employee_details AS e ON e.CARDNO=rm.CREATED_BY 
LEFT JOIN budget_allocation AS ba ON ba.REQUISITION_ID = rm.REQUISITION_ID 
WHERE  rm.REQUISITION_STATUS_ID='8' AND rm.ISPAID = '0'  GROUP BY ba.REQUISITION_ID";
$Payment_result = query($SqlPayment);



$StatusList = rs2array(query("SELECT REQUISITION_ID, REQUISITION_NAME FROM requisition_status"));
$processsDepartmentList = rs2array(query("SELECT REQUISITION_ROUTE_ID, ROUTE_NAME FROM requisition_route"));
?>


<div Title='Payment List' class="easyui-panel" style="height:1000px;" >
    <form action="" method="POST" name='WorkFlowGroup' class="form" autocomplete="off">
        <table class="ui-state-default">
            <thead>
            <th width="30">S/N</th>
            <th width="90">PR No</th>
            <th width="80">PR Date</th>
            <th>PR From</th>
            <th width="100">Total Amount</th>
            <th width="100">Paid Upto Date</th>
            <th width="50">Balance</th>
            <th width="50">Paid Amount</th>
            <th width="20">Full Paid </th>
            <th width="20">Action </th>
            </thead>
            <tbody>
                <?php while ($RowOfPaymentList = fetch_object($Payment_result)) { ?>

                    <tr>
                        <td><?php echo++$SL; ?>.</td>
                        <td><?php echo $RowOfPaymentList->REQUISITION_NO; ?></td>
                        <td><?php echo bdDate($RowOfPaymentList->REQUISITION_DATE); ?></td>
                        <td><?php echo $RowOfPaymentList->Requisition_from; ?></td>
                        <td><?php echo $RowOfPaymentList->PAYABLE; ?></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td><input type="checkBox" name="RequisitionID[]" value="<?php echo $RowOfPaymentList->REQUISITION_ID; ?>"/></td>
                        <td><input type="checkBox" name="paid[]" value="<?php echo $RowOfPaymentList->REQUISITION_ID; ?>"/></td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
        <br/>
        <button type="submit" name="save" value="SavePayment" class="button">Save</button>
    </form>
</div>
<br/>


<?php include '../body/footer.php'; ?>






