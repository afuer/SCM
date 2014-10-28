<?php
include '../lib/DbManager.php';
include './manager.php';
include("../body/header.php");

$poid = getParam('po_no');
$expence_id = getParam('expence_id');
$search_id = getParam('expence_id');
//$expence_id=21;

if (isSave()) {
    $lastApprovalId = getParam('lastApprovalId');
    $emp = getEmpinfo("$lastApprovalId");

    $appDate = getParam("app_date");
    $appSubject = getParam("app_subject");
    $appProposal = getParam("app_proposal");
    $costApproved_by = getParam("cost_proposal");
    $woWithDate = getParam("wo_with_date");
    $appPayment_through = getParam("app_payment_through");
    $appClearance_by = getParam("app_clearance_by");
    $appRecommendation = getParam("app_recommendation");

    $expanse_sql = "INSERT INTO approval_notes(DATE,SUBJECT,PROPOSAL_FOR,COST_APPROVED_BY,WO_NO_WITH_DATE,PAYMENT_THROUGH,CLEARANCE_PERFORMANCE_CERTIFICATE_BY,RECOMMENDATION,PO_NO) 
        values('$appDate','$appSubject','$appProposal','$costApproved_by','$woWithDate','$appPayment_through','$appClearance_by','$appRecommendation','$poid')";
    sql($expanse_sql);
    $lastId = mysql_insert_id();

    $sqlRequisition = "UPDATE fin_payment_approval_note SET 
        APPROVAL_NOTES_ID='$lastId',
        LAST_APPROVAL_ID='$emp->EMPLOYEE_ID'
        WHERE PAYMENT_ID='$expence_id'";
    sql($sqlRequisition);

    echo "<script>location.replace('payment_notes.php');</script>";
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
pm.MODE_NAME, exp.PAYMENT_NO, exp.NET_PAY, exp.LAST_APPROVAL_ID

FROM fin_payment_approval_note exp
left join purchase_order po on po.purchase_order_id=exp.purchase_order_id
left join supplier sup on sup.SUPPLIER_ID = po.supplier_id
LEFT JOIN payment_mode pm ON pm.PAYMENT_MODE_ID=exp.PAYMENT_MODE_ID
where exp.PAYMENT_ID='$expence_id'");

$client = findValue("SELECT `SUPPLIER_NAME` FROM `supplier` WHERE SUPPLIER_ID='$rec->BENEFICIARY_ID'");

$costApprovalSql = "SELECT e.FIRST_NAME, e.LAST_NAME, wm.DESIGNATION_ID,
d.DESIGNATION_NAME

FROM workflow_manager wm 
LEFT JOIN employee e ON e.EMPLOYEE_ID=wm.EMPLOYEE_ID
LEFT JOIN designation d ON d.DESIGNATION_ID=wm.DESIGNATION_ID
LEFT JOIN price_comparison pc ON pc.comparisonid=wm.REQUISITION_ID
LEFT JOIN purchase_order po ON po.comparison_id=pc.comparisonid
LEFT JOIN fin_payment_approval_note fpa ON fpa.PURCHASE_ORDER_ID=po.purchase_order_id

WHERE MODULE_NAME='CS' AND fpa.PAYMENT_ID='$expence_id'";

$resultCostApproval = query($costApprovalSql);

$bill_sql = "SELECT qty,unit_price,BRANCH_DEPT_NAME FROM purchase_order_details LEFT JOIN branch_dept ON purchase_order_details.branch_dept_id=branch_dept.BRANCH_DEPT_ID 
                    WHERE purchase_order_id='$rec->purchase_order_id'";
$billData = find($bill_sql);

$fin = find("SELECT CONCAT(e.FIRST_NAME,' ',e.LAST_NAME, '->', d.DESIGNATION_NAME) AS 'lastApproval'
FROM fin_payment_approval_note  fan
INNER JOIN purchase_order po ON po.purchase_order_id=fan.PURCHASE_ORDER_ID
INNER JOIN price_comparison pc ON pc.comparisonid=po.comparison_id
INNER JOIN requisition_approval ra ON ra.CS_ID=pc.comparisonid
INNER JOIN employee e ON e.EMPLOYEE_ID=ra.LAST_APPROVAL_ID
LEFT JOIN designation d ON d.DESIGNATION_ID=e.DESIGNATION_ID
WHERE fan.PAYMENT_ID='$expence_id'");
?>

<div class="easyui-layout" style="height:700px; margin: auto;">  
    <div title="External Expense Bill List" data-options="region:'center'" class="easyui-panel" style="padding: 5px 10px;" >

        <form action="" name="" method="post">
            <input type="hidden" name="expence_id" id="expence_id" value="<?php echo $expence_id ?>" />
            <input type="hidden" name="po_no" id="expence_id" value="<?php echo $rec->purchase_order_id ?>" />
            <input type="hidden" name="lastApprovalId" id="lastApprovalId" value="<?php echo $rec->LAST_APPROVAL_ID ?>" />
            <table class="table">
                <tr>
                    <td width="150">Date: </td>
                    <td><input name="app_date" type="text" class="easyui-datebox" value="<?php echo date("Y-m-d"); ?>" data-options="required:true, formatter:myformatter,parser:myparser"/></td>
                </tr>
                <tr>
                    <td valign="top">Subject: </td>
                    <td><textarea style="width: 90%;" name="app_subject"  id="app_subject" ><?php echo "Approval for payment of the bill of M/s." . $client . ' Tk. ' . $billData->unit_price * $billData->qty . ' Only '; ?></textarea></td>
                </tr>
                <tr>
                    <td valign="top">1. Proposal For: </td>
                    <td><textarea style="width: 90%;" name="app_proposal" id="app_proposal">Approval for payment of the bill of M/s.<?php echo $client; ?></textarea></td>
                </tr>
                <tr>
                    <td valign="top">2. Cost Approved By: </td>
                    <td><textarea style="width: 90%;" name="cost_proposal" id="app_proposal"><?php echo $fin->lastApproval; ?></textarea></td>
                </tr>
                <tr>
                    <td valign="top">3. W/O No with Date: </td>
                    <td><textarea style="width: 90%;" name="wo_with_date" id="app_proposal"><?php echo $rec->order_no . " Dated " . bddate($rec->order_date); ?></textarea></td>
                </tr>
                <tr>
                    <td >4. Particular of Bill: </td>
                </tr>
            </table>

            <table class="ui-state-default">
                <?php while ($row = mysql_fetch_object($resultCostApproval)) { ?>

                    <tr>
                        <td width="20"><?php echo++$sl; ?></td>
                        <td><?php echo $row->FIRST_NAME . ' ' . $row->LAST_NAME . '' . $row->DESIGNATION_NAME; ?></td>
                    </tr>
                    <?php
                }
                ?>
            </table>


            <table class="ui-state-default">
                <thead>
                    <tr>
                        <th>SL</th>
                        <th>Name of Branch/ATM Booth </th>
                        <th>Quantity</th>
                        <th>Unit Price </th>
                        <th>Total Amount </th>
                    </tr>
                </thead>
                <tr>
                    <td>01</td>
                    <td><?php echo $billData->BRANCH_DEPT_NAME; ?></td>
                    <td><?php echo $billData->qty; ?></td>
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

            <table>
                <?php
                $sql = "SELECT TAX_PERCENT,NET_PAY,TAX_AMOUNT,SECURITY_DEPOSIT_PERCENT,SECURITY_DEPOSIT,VAT_PERCENT,VAT_AMOUNT ,
                    NET_PAY, RECOMMEND_BILL
                    FROM fin_payment_approval_note WHERE PAYMENT_ID='$expence_id'";
                $calData = find($sql);
                ?>
                <tr>
                    <td colspan="2">a)Billing Amount : Total Bill Tk <?php echo $billData->unit_price * $billData->qty; ?><?php echo " Bill No " . $rec->order_no . ";Dated " . bddate($rec->order_date); ?></td>
                </tr>
                <tr>
                    <td colspan="2">b)Cumulative Bill(From to till date):Tk <?php
                        $total = $billData->unit_price * $billData->qty;
                        $sub = $calData->SECURITY_DEPOSIT + $calData->TAX_AMOUNT + $calData->VAT_AMOUNT;
                        echo $total - $sub;
                        ?>(Cumulative amount + Current Bill) </td>
                </tr>
                <tr>
                    <td colspan="2"><p>c)Calculation of payment :</p>
                        <p>Payment procedure of the subject bill may be  as per following criterion:</p>
                        <table class="table">
                            <tr>
                                <td>Total Bill :</td>
                                <td>Tk <?php echo $calData->RECOMMEND_BILL; ?></td>
                            </tr>
                            <tr>
                                <td> Less:<?php echo $calData->SECURITY_DEPOSIT ?>% Security Deposit</td>
                                <td> Tk <?php echo $calData->SECURITY_DEPOSIT_PERCENT ?></td>
                            </tr>
                            <tr>
                                <td>Less : VAT <?php echo $calData->VAT_PERCENT ?>%</td>
                                <td>Tk <?php echo $calData->VAT_AMOUNT ?></td>
                            </tr>
                            <tr>
                                <td>Less : Tax (<?php echo $calData->TAX_PERCENT ?>%)</td>
                                <td>Tk <?php echo $calData->TAX_AMOUNT ?></td>
                            </tr>
                            <tr>
                                <td>Net payable to <?php echo findValue("SELECT `SUPPLIER_NAME` FROM `supplier` WHERE SUPPLIER_ID='$rec->BENEFICIARY_ID'"); ?>
                                </td>
                                <td>Tk <?php
                                    $sub = $calData->SECURITY_DEPOSIT_PERCENT + $calData->TAX_AMOUNT + $calData->VAT_AMOUNT;
                                    echo formatMoney($calData->RECOMMEND_BILL - $sub);
                                    ?></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>5. Payment Through</td>
                    <td>Financial Administration Division(FAD),Head Office</td>
                </tr>
                <tr>
                    <td valign="top">Clearance/performance Certificate by: </td>
                    <td><textarea style="width: 90%;" name="app_clearance_by" id="app_clearance_by"></textarea></td>
                </tr>

                <tr>
                    <td valign="top">6. Recommendation: </td>
                    <td><textarea style="width: 90%;" name="app_recommendation" id="app_recommendation"></textarea></td>
                </tr>
                <tr>
                    <td valign="top">7. Head Of Expenditure: </td>
                    <td><textarea style="width: 90%;" name="app_clearance_by" id="app_clearance_by"></textarea></td>
                </tr>
            </table>

            <?php file_upload_html(TRUE); ?>

            <button type="submit" class="button" value="save" name="save">Submit</button>

        </form>

        <?php include '../requisition/ApprovalHistory.php'; ?>

    </div>
</div>
<?php include("../body/footer.php"); ?>