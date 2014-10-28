<?php
include '../lib/DbManager.php';
include("../body/header.php");
//include "../body/functions.php";
include "../lib/ibrahimconvert.php";
$expence_id = getParam('expence_id');
$search_id = getParam('expence_id');
$mode = getParam('mode');

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
pm.MODE_NAME, exp.PAYMENT_NO, exp.NET_PAY,comparison_id, sup.SUPPLIER_NAME

FROM fin_payment_approval_note exp
left join purchase_order po on po.purchase_order_id=exp.purchase_order_id
left join supplier sup on sup.SUPPLIER_ID = po.supplier_id
LEFT JOIN payment_mode pm ON pm.PAYMENT_MODE_ID=exp.PAYMENT_MODE_ID
where exp.PAYMENT_ID='$expence_id'");


$total_value = findValue("SELECT SUM(NET_PAY) as total 
FROM fin_payment_approval_note
WHERE PURCHASE_ORDER_ID='$rec->purchase_order_id' 
GROUP BY PURCHASE_ORDER_ID");
?>
<div class="easyui-layout" style="margin: auto; height:1200px;">  
    <div Title='New Payment Approval Note' data-options="region:'center'" style="padding: 10px 10px; background-color:white; "> 


        <div class="left"><img src="../public/images/PrimeBank.png" width="220" height="60" /></div>
        <hr>

        <br />
        <fieldset>
            <legend><strong>New Payment Approval Note Details</strong></legend>
            <br />
            <table class="table">
                <tr>
                    <td align="right">PAN Date:</td>
                    <td width="75" align="center"><strong>: </strong></td>
                    <td width="559"><?php echo $rec->EXPENSE_DATE; ?></td>
                </tr>
                <tr>
                    <td width="228" height="23"><div align="right">Expense No </div></td>
                    <td><div align="center"><strong>:</strong></div></td>
                    <td><?php echo $rec->PAYMENT_NO; ?></td>
                </tr>
                <tr>
                    <td width="228" height="23"><div align="right">Security Instrument </div></td>
                    <td><div align="center"><strong>:</strong></div></td>
                    <td><?php echo findValue("SELECT instrument_name FROM `security_instrument` where instrumentid='$rec->INSTRUMENT_ID'"); ?>      

                    </td>
                </tr> 

                <tr>
                    <td align="right"><div align="right">Name of Client </div></td>
                    <td><div align="center"><strong>:</strong></div></td>
                    <td><?php echo findValue("SELECT `SUPPLIER_NAME` FROM `supplier` WHERE SUPPLIER_ID='$rec->BENEFICIARY_ID'"); ?></td>
                </tr>
                <tr>
                    <td align="right">Payment Mode </td>
                    <td><div align="center"><strong>:</strong></div></td>
                    <td><?php echo $rec->MODE_NAME; ?></td>
                </tr>
                <tr>
                    <td align="right"><div align="right">Vendor Ref No </div></td>
                    <td><div align="center"><strong>:</strong></div></td>
                    <td> <?php echo $rec->SUPPLIER_NAME; ?></td>
                </tr>


                <tr>
                    <td><div align="right">Purpose</div></td>
                    <td><div align="center"><strong>:</strong></div></td>
                    <td><?php echo $rec->PURPOSE; ?></td>
                </tr>

                <tr>
                    <td><div align="right">WO/ PO No</div></td>
                    <td><div align="center"><strong>:</strong></div></td>
                    <td><a href="po_view.php?poid=<?php echo $rec->purchase_order_id; ?>" target="_blank" ><?php echo $rec->order_no; ?></a> <input type="hidden" name="po_no" value="<?php echo $rec->purchase_order_id; ?>"></td>
                </tr>
                <tr>
                    <td><div align="right">WO/ PO Date </div></td>
                    <td><div align="center"><strong>:</strong></div></td>
                    <td><?php echo bddate($rec->order_date); ?></td>
                </tr>
                <tr>
                    <td><div align="right">PR No</div></td>
                    <td><div align="center"><strong>:</strong></div></td>
                    <td><?php
                        $var = find("SELECT r.REQUISITION_NO, r.REQUISITION_ID 
                        FROM price_comparison_pro_req_qty pcq
                        INNER JOIN requisition r ON r.REQUISITION_ID=pcq.requisition_id
                        WHERE price_comparison_id='$rec->comparison_id' GROUP BY r.REQUISITION_ID");
                        echo "<a href='../manage_product/reco_details.php?reco_id=$var->REQUISITION_ID'> $var->REQUISITION_NO</a>";
                        ?>
                    </td>
                </tr>
                <tr>
                    <td><div align="right">Invoice No</div></td>
                    <td><div align="center"><strong>:</strong></div></td>
                    <td><?php echo $rec->INVOICE_NO; ?></td>
                </tr>
                <tr>
                    <td><div align="right">Invoice Date </div></td>
                    <td><div align="center"><strong>:</strong></div></td>
                    <td><?php echo bddate($rec->EXPENSE_DATE); ?></td>
                </tr>
                <tr>
                    <td><div align="right">Invoice amount (In TK) </div></td>
                    <td><div align="center"><strong>:</strong></div></td>
                    <td><?php
                        $amount = abs($rec->NET_PAY);
                        echo formatMoney($amount);
                        ?></td>
                </tr>
                <tr>
                    <td><div align="right">Invoice amount (In Words) </div></td>
                    <td><div align="center"><strong>:</strong></div></td>
                    <td><?php
                        $amount = abs($amount);

                        //convert_number_word($amount) . "Taka Only.";
                        ?>
                    </td>
                </tr>
            </table>

            <br/>
            <br/>
            <fieldset>
                <legend><strong>Payment Schedule</strong></legend>
                <table class="table">
                    <tr>
                        <td width="324" align="right"><div align="right">WO/ PO Value TK</div></td>
                        <td width="50"><div align="center"><strong>:</strong></div></td>
                        <td width="112"> <?php
                            $workOrderValue = findValue("SELECT SUM(IFNULL(qty,0)* IFNULL(unit_price,0)) 
                            FROM purchase_order_details WHERE purchase_order_id='$rec->purchase_order_id'");


                            echo formatMoney($workOrderValue);
                            ?></td>
                        <td width="0"></td>
                    </tr>

                    <tr>
                        <td> <div align="right">Bill up to date TK </div></td>
                        <td><div align="center"><strong>:</strong></div></td>
                        <td>
                            <?php echo formatMoney($total_value); ?></td>
                        </td>
                    </tr>
                    <tr>
                        <td><div align="right">Bill/Invoice amount TK </div></td>
                        <td><div align="center"><strong>:</strong></div></td>
                        <td><?php //echo formatMoney($rec->NET_PAY);   ?></td>
                    </tr>

                    <tr>
                        <td><div align="right">Remaining TK</div></td>
                        <td><div align="center"><strong>:</strong></div></td>
                        <td id="ajax_remaining"><?php
                            $remain_amount = $workOrderValue - ($total_value);
                            echo formatMoney($remain_amount);
                            ?></td>
                    </tr>

                    <tr>
                        <td colspan="4" align="left">This payable amount is  subject to  AIT &  VAT  and  other deductions  (If applicable)</td>
                    </tr>
                </table>
            </fieldset>

            <br />
            <?php
            file_upload_view("$expence_id", 'Payment Bill');
            echo "<br>";
            echo "<h2>History</h2>";

            $search_id = $var->REQUISITION_ID;
            include '../requisition/ApprovalHistory.php';
            ?>
        </fieldset>
        <br>
        <?php if ($mode == 'view') { ?>
            <a href="approval_notes.php?expence_id=<?php echo $expence_id ?>" class="button">Next</a>&nbsp;<a href="#" class="button">Edit</a>
        <?php } ?>

    </div>
</div>

<?php include '../body/footer.php'; ?>
