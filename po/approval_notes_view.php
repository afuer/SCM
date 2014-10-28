<?php
include '../lib/DbManager.php';
include './manager.php';
include("../body/header.php");

$mode = getParam('mode');
$poid = getParam('po_no');
$approvalNotesId = getParam("approval_notes_id");
$expence_id = getParam('expence_id');
$comments = getParam('comments');
$search_id = $expence_id;
$lastApprovalId = getParam('lastApprovalId');
//echo $employeeId;

if (isSave()) {
    $module = "Fin Approval";
    $emp = getEmpinfo("$lastApprovalId");

    $sql = "INSERT INTO workflow_manager(REQUISITION_ID, MODULE_NAME, WORKFLOW_PROCESS_ID, EMPLOYEE_ID, DESIGNATION_ID, APPROVAL_COMMENT, APPROVE_STATUS, CREATED_BY, CREATED_DATE) 
            VALUES('$expence_id','$module', '1', '$lineManagerId','$Designation','$comments','1', '$employeeId',NOW())";
    sql($sql);

    //echo "<br/>";

    if ($Designation == 4) {
        $sqlRequisition = "UPDATE fin_payment_approval_note SET PRENENT_LOCATION_ID=null, `STATUS`='3', USER_LEVEL_ID='108', LAST_APPROVAL_ID='$emp->EMPLOYEE_ID' WHERE PAYMENT_ID='$expence_id'";
        sql($sqlRequisition);
    } else {
        if ($emp->EMPLOYEE_ID == "$employeeId") {
            $sqlRequisition = "UPDATE fin_payment_approval_note SET 
                `STATUS`='4', 
                USER_LEVEL_ID='108',
                LAST_APPROVAL_ID='$emp->EMPLOYEE_ID',
                PRENENT_LOCATION_ID=NULL 
                WHERE PAYMENT_ID='$expence_id'";
            sql($sqlRequisition);
        } else {
            echo $sqlRequisition = "UPDATE fin_payment_approval_note SET 
                USER_LEVEL_ID=NULL,
                LAST_APPROVAL_ID='$emp->EMPLOYEE_ID',
                PRENENT_LOCATION_ID='$lineManagerId' 
                WHERE PAYMENT_ID='$expence_id'";
            sql($sqlRequisition);
        }
    }


    //echo "<script>location.replace('payment_notes.php');</script>";
}




$rec = find("SELECT exp.BENEFICIARY_ID,
po.purchase_order_id, po.order_no,
po.orderids, po.office_type, 
po.branch_dept_id, 
po.order_date,
po.supp_ref,
exp.CREATED_BY,
exp.PAYMENT_MODE_ID, 
exp.INSTRUMENT_ID,
exp.PURPOSE, 
exp.COMMENTS, 
exp.EXPENSE_DATE, 
exp.INVOICE_NO, exp.INVOICE_DATE,
pm.MODE_NAME, exp.PAYMENT_NO, exp.NET_PAY,
exp.EXPENSE_DATE, an.DATE, LAST_APPROVAL_ID, 
exp.APPROVAL_NOTES_ID, e.CARD_NO, e.LAST_NAME, e.FIRST_NAME

FROM fin_payment_approval_note exp
LEFT JOIN employee e ON e.EMPLOYEE_ID=exp.LAST_APPROVAL_ID
left join purchase_order po on po.purchase_order_id=exp.purchase_order_id
left join supplier sup on sup.SUPPLIER_ID = po.supplier_id
LEFT JOIN payment_mode pm ON pm.PAYMENT_MODE_ID=exp.PAYMENT_MODE_ID
LEFT JOIN approval_notes an ON an.APPROVAL_NOTES_ID=exp.APPROVAL_NOTES_ID
where exp.PAYMENT_ID='$expence_id'");

//echo "select DATE,SUBJECT,PROPOSAL_FOR,COST_APPROVED_BY,WO_NO_WITH_DATE,PAYMENT_THROUGH,CLEARANCE_PERFORMANCE_CERTIFICATE_BY,RECOMMENDATION,PO_NO 
//  from approval_notes where APPROVAL_NOTES_ID='$rec->APPROVAL_NOTES_ID'";
$row = find("select DATE,SUBJECT,PROPOSAL_FOR,COST_APPROVED_BY,WO_NO_WITH_DATE,PAYMENT_THROUGH,CLEARANCE_PERFORMANCE_CERTIFICATE_BY,RECOMMENDATION,PO_NO 
    from approval_notes where APPROVAL_NOTES_ID='$rec->APPROVAL_NOTES_ID'");
?>


<div class="panel-header">External Expense Bill List</div>
<div style="padding: 5px 10px; background: white;" >

    <table width="96%" class="table">
        <tr>
            <td width="17%">Date</td>
            <td width="83%"><?php echo $row->DATE ?></td>
        </tr>
        <tr>
            <td>Subject</td>
            <td><?php echo $row->SUBJECT ?></td>
        </tr>
        <tr>
            <td>1. Proposal For </td>
            <td><?php echo $row->PROPOSAL_FOR ?></td>
        </tr>
        <tr>
            <td>2. Cost Approved By </td>
            <td><?php echo $row->COST_APPROVED_BY ?></td>
        </tr>
        <tr>
            <td>3. W/O No with Date </td>
            <td><?php echo $row->WO_NO_WITH_DATE ?></td>
        </tr>
        <tr>
            <td colspan="2">4. Particular of Bill </td>
        </tr>
    </table>

    <table width="800" class="ui-state-default">
        <?php
        $bill_sql = "SELECT qty,unit_price,BRANCH_DEPT_NAME, ot.OFFICE_NAME
        FROM purchase_order po
        LEFT JOIN purchase_order_details pod ON pod.purchase_order_id=po.purchase_order_id
        LEFT JOIN branch_dept ON po.branch_dept_id=branch_dept.BRANCH_DEPT_ID 
        LEFT JOIN office_type ot ON ot.OFFICE_TYPE_ID=po.office_type
        WHERE po.purchase_order_id='$rec->purchase_order_id'";

        $billData = find($bill_sql);
        ?>
        <thead>
        <th>SL</th>
        <th>Name of Branch/ATM Booth </th>
        <th>Quantity</th>
        <th>Unit Price </th>
        <th>Total Amount </th>
        </thead>
        <tr>
            <td>01</td>
            <td><?php echo $billData->OFFICE_NAME . '->' . $billData->BRANCH_DEPT_NAME ?></td>
            <td><?php echo $billData->qty ?></td>
            <td><?php echo formatMoney($billData->unit_price); ?></td>
            <td><?php echo formatMoney($billData->unit_price * $billData->qty); ?></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td>Grand Total: </td>
            <td><?php echo formatMoney($billData->unit_price * $billData->qty); ?></td>
        </tr>
    </table>

    <table class="table">
        <?php
        $sql = "SELECT TAX_PERCENT,NET_PAY,TAX_AMOUNT,SECURITY_DEPOSIT_PERCENT,SECURITY_DEPOSIT,VAT_PERCENT,VAT_AMOUNT ,
                    NET_PAY, RECOMMEND_BILL
                    FROM fin_payment_approval_note WHERE PAYMENT_ID='$expence_id'";
        $calData = find($sql);
        ?>
        <tr>
            <td colspan="2">a) Billing Amount : Total Bill Tk <?php echo $calData->RECOMMEND_BILL; ?><?php echo " Bill No " . $rec->order_no . "; Dated " . bddate($rec->order_date); ?></td>
        </tr>
        <tr>
            <td colspan="2">b) Cumulative Bill (From to till date):Tk <?php
                $sub = $calData->SECURITY_DEPOSIT + $calData->TAX_AMOUNT + $calData->VAT_AMOUNT;
                echo $calData->RECOMMEND_BILL - $sub;
                ?>(Cumulative amount + Current Bill) </td>
        </tr>
        <tr>

            <td colspan="2"><p>c) Calculation of payment :</p>
                <p>payment procedure of the subject bill may be  as per following criterion:</p>
                <table class="table" style="width: 500px;">
                    <tr>
                        <td>Total Bill :</td>
                        <td>Tk <?php echo $calData->RECOMMEND_BILL; ?></td>
                    </tr>
                    <tr>
                        <td> Less: <?php echo $calData->SECURITY_DEPOSIT_PERCENT; ?>% Security Deposit</td>
                        <td> Tk <?php echo $calData->SECURITY_DEPOSIT_PERCENT ?></td>
                    </tr>
                    <tr>
                        <td>Less : VAT <?php echo $calData->VAT_PERCENT ?>%</td>
                        <td>Tk <?php echo $calData->VAT_AMOUNT ?></td>
                    </tr>
                    <tr>
                        <td>Less : Income Tax (<?php echo $calData->TAX_PERCENT ?>%)</td>
                        <td>Tk <?php echo $calData->TAX_AMOUNT ?></td>
                    </tr>
                    <tr>
                        <td>Net payable to M/s <?php echo findValue("SELECT `SUPPLIER_NAME` FROM `supplier` WHERE SUPPLIER_ID='$calData->BENEFICIARY_ID'"); ?>	  </td>
                        <td>Tk <?php
                            $sub = $calData->SECURITY_DEPOSIT_PERCENT + $calData->TAX_AMOUNT + $calData->VAT_AMOUNT;
                            echo $calData->RECOMMEND_BILL - $sub;
                            ?></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td width="300">5. Payment Through</td>
            <td><?php echo $row->PAYMENT_THROUGH ?></td>
        </tr>
        <tr>
            <td>Clearance/Performance Certificate by</td>
            <td><?php echo $row->CLEARANCE_PERFORMANCE_CERTIFICATE_BY ?></td>
        </tr>
        <tr>
            <td>6. Recommendation</td>
            <td><?php echo $row->RECOMMENDATION ?></td>
        </tr>
        <tr>
            <td>7. Head Of Expenditure </td>
            <td>&nbsp;</td>
        </tr>
    </table>
    <?php
    file_upload_view($expence_id, 'Payment Bill');
    echo "<h2>History</h2>";
    include '../requisition/ApprovalHistory.php';
    ?>

    <form action="" method="POST">
        <input type="hidden" name="expence_id" value="<?php echo $expence_id; ?>" />
        <table class="table">
            <tr>
                <td>Last Approval Person:</td>
                <td><input type="text" name="lastApprovalId" value="<?php echo $rec->CARD_NO; ?>" /><?php echo $rec->FIRST_NAME.' '.$rec->LAST_NAME; ?></td>
            </tr>
            <tr>
                <td valign="top" width="80">Comments:</td>
                <td><textarea name="comments" style="width: 80%"></textarea><br/></td>
            </tr>
        </table>


        <button type="submit" class="button" name="save" value="save">Submit</button>
    </form>


    <br/><br/><br/><br/>
</div>
<?php include("../body/footer.php"); ?>