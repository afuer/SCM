<?php
include '../lib/DbManager.php';
include './purchase_order_approval.php';
include("../body/header.php");

$exp_no = findValue("SELECT IFNULL(MAX(PAYMENT_ID), 0)+1 as exp_id FROM fin_payment_approval_note");
$poid = getParam('po_no');
$Module = "Expense Bill";

if (isSave()) {
    $manager = new PurchaseOrderApproval();


    $claim_id = getParam("claim_id");
    $modeid = getParam("modeid");
    $instrumentid = getParam('instrumentid');
    $supplierid = getParam('supplierid');
    $purpose = getParam('purpose');
    $comments = getParam('comments');
    $invoice_no = getParam('invoice_no');
    $invoice_date = getParam('invoice_date');
    $securityDeposit = getParam('securityDeposit');
    $securityAmount = getParam('securityAmount');
    $incomeTax = getParam('incomeTax');
    $incomeTaxAmount = getParam('incomeTaxAmount');
    $vat = getParam('vat');
    $vatAmount = getParam('vatAmount');
    $netBill = getParam('netBill');



    if (isEmpty($invoice_date))
        $invoice_date = date("Y-m-d");

    $exp_no = findValue("SELECT IFNULL(MAX(PAYMENT_ID), 0)+1 as exp_id FROM fin_payment_approval_note");

    $payentNo = OrderNo($exp_no);
    $expanse_sql = "INSERT INTO fin_payment_approval_note(PAYMENT_NO, PURCHASE_ORDER_ID, PAYMENT_MODE_ID, INSTRUMENT_ID, BENEFICIARY_ID, PURPOSE, COMMENTS, `STATUS`, USER_LEVEL_ID, EXPENSE_DATE, INVOICE_NO, SECURITY_DEPOSIT, SECURITY_DEPOSIT_PERCENT, TAX_PERCENT, TAX_AMOUNT, VAT_PERCENT, VAT_AMOUNT, NET_PAY, CREATED_BY, CREATED_DATE) 
        values('$payentNo', '$poid', '$modeid', '$instrumentid', '$supplierid', '$purpose', '$comments', '0', '5', NOW(), '$invoice_no', '$securityDeposit', '$securityAmount', '$incomeTax', '$incomeTaxAmount', '$vat', '$vatAmount', '$netBill', '$employeeId', NOW())";

    sql($expanse_sql);

    $lastId = mysql_insert_id();
    file_upload_save("../documents/payment_approval_docs/", "$lastId", "Payment Bill");

    $manager->ProductApproval($lastId, $Module, $EmployeeId, $Designation, $comment);
    financeProductApproval("$lastId", "$Module", "$lastId");



    echo "<script>location.replace('expense_form.php?po_no=$poid');</script>";
}



$modeids = rs2array(query("SELECT PAYMENT_MODE_ID, MODE_NAME FROM payment_mode WHERE _SHOW=1"));
$instrumentids = rs2array(query("select instrumentid, instrument_name from security_instrument where _show=1"));

$row = find("SELECT po.discount, po.vat,
            sum(poi.qty*poi.unit_price-((poi.discount/100)*(poi.qty*poi.unit_price))) as net_value
            from purchase_order_details poi
            left join purchase_order po on po.purchase_order_id=poi.purchase_order_id
            WHERE po.purchase_order_id='$poid' GROUP BY po.purchase_order_id");

$net_value = $row->net_value;
$total_discount = ($row->discount / 100) * $net_value;
$discount_less_amount = $net_value - $total_discount;
$total_vat = ($row->vat / 100) * $discount_less_amount;
$sub_total = ($net_value + $total_vat) - $total_discount;




$rec = find("select po.purchase_order_id,
                    po.order_no,
                    po.comparison_id,
                    po.order_date,
                    po.supp_ref,
                    po.orderids,
                    po.order_date,
                    po.supplier_id,
                    poi.product_id,
                    sup.SUPPLIER_NAME,
                    #sup.CREDIT_ACCOUNT,
                    sum(poi.qty*poi.unit_price) as total,
                    si.status_app_level
                    
                    from purchase_order po
                    left join purchase_order_details poi on po.purchase_order_id = poi.purchase_order_id
                    left join requisition_details si on poi.product_id= si.PRODUCT_ID
                    left join supplier sup on po.supplier_id=sup.SUPPLIER_ID
                    where po.purchase_order_id='$poid'
                    GROUP BY po.purchase_order_id");

$paid_amount = findValue("SELECT SUM(NET_PAY) as amount FROM fin_payment_approval_note WHERE PURCHASE_ORDER_ID='$rec->po_no' GROUP BY PURCHASE_ORDER_ID");
?>

<script>

    function securityAmountChange() {
        var securityPercent = $('#securityDepositId').val(),
                workOrderAmount = $('#wo_amount').val(),
                securityAmount = (securityPercent * workOrderAmount) / 100,
                val = securityAmount.toFixed(2);
        $("#securityAmountId").val(val);
        subTotalChange();
    }

    function incomeTaxAmountChange() {
        var incomeTax = $('#incomeTaxId').val(),
                workOrderAmount = $('#wo_amount').val(),
                taxAmount = (incomeTax * workOrderAmount) / 100,
                val = taxAmount.toFixed(2);
        $("#incomeTaxAmountId").val(val);
        subTotalChange();
    }

    function vatAmountChange() {
        var vat = $('#vatId').val(),
                workOrderAmount = $('#wo_amount').val(),
                vatAmount = (vat * workOrderAmount) / 100,
                val = vatAmount.toFixed(2);
        $("#vatAmount").val(val);
        subTotalChange();
    }

    function subTotalChange() {
        var securityAmount = parseFloat($('#securityAmountId').val()),
                incomeTax = parseFloat($('#incomeTaxAmountId').val()),
                vat = parseFloat($('#vatAmount').val()),
                total = (securityAmount + incomeTax + vat).toFixed(2);
        //$("#subTotalId").val(total);
        console.log(total);
        netBill();
    }

    function netBill() {
        var subTotal = parseFloat($('#subTotalId').val()),
                workOrderAmount = $('#wo_amount').val(),
                bill = (workOrderAmount - subTotal).toFixed(2);
        $("#netBillId").val(bill);
    }

    function netBill() {
        var subTotal = parseFloat($('#netBillId').val()),
                workOrderAmount = $('#wo_amount').val(),
                bill = (workOrderAmount - subTotal).toFixed(2);
        $("#netBillId").val(bill);
    }



    function EmployeeInfo(obj) {
        var Card_no, result, itemrow;
        Card_no = obj.val();

        itemrow = obj.closest('tr');
        $('#loder').show();
        $.ajax({
            url: "ajax_employee.php?card_no=" + Card_no,
            type: "GET",
            contentType: "application/json",
            dataType: "text",
            success: function(data) {
                result = JSON.parse(data);
                itemrow.find('#employee_details').html(result.empName);
                itemrow.find('#employee_id').val(result.EMPLOYEE_ID);
                itemrow.find('#designationId').val(result.DESIGNATION_ID);
                $('#loder').hide();
            }
        });
    }

    function removeStackHolder(requisition_id, module, mode) {

        var Requisition_id = requisition_id;
        alert(Requisition_id);
        var Module = module;
        var Mode = mode;
        $.messager.confirm('Confirm', 'Are you sure you want to destroy this user?', function(r) {
            if (r) {
                alert(Requisition_id, Module, Mode);
            }
        });


    }

    function DeleteStackHolder(Requisition_id, Module, Mode) {
        var Requisition_id1 = Requisition_id;
        var Module1 = Module;
        var Mode1 = Mode;
        $.ajax({
            type: "GET",
            url: 'stack_holder_delete.php?&mode=delete&search_id=' + Requisition_id1,
            success: function(data) { //alert (data);
                //console.log(data);
                //window.location.href = 'index.php?requisition_id='+ Requisition_id1;
                window.location.href = 'stack_holder_new.php?mode=' + Mode1 + '&module=' + Module1 + '&requisition_id=' + Requisition_id1;

            }
        });

    }
    function AddABoq(TableID) {
        var tr = $('#' + TableID + ' tbody>tr:last').clone(true);
        var td = tr.find('td:first');
        var sl = parseInt(td.text());
        td.text(sl + 1 + '.');
        tr.insertAfter('#' + TableID + ' tbody>tr:last').find('input, select').attr('class', 'add').val('');
    }
</script>



<div class="easyui-layout" style="margin: auto; height:1200px;">  
    <div Title='New Payment Approval Note' data-options="region:'center'" style="padding: 10px 10px; background-color:white; "> 

        <form name="expanse_form" action="" class="formValidate" method="POST" enctype="multipart/form-data" autocomplete="off">
            <input type="hidden" name="po_no" value="<?php echo $poid; ?>" />
            <input type="hidden" name="claim_id" value="<?php echo $claim_id; ?>" />
            <input type="hidden" name="po_products" value="<?php echo $po_product_list; ?>" />

            <table class="table">
                <tr>
                    <td align="right" width="170">Security Instrument:</td>
                    <td><?php combobox('instrumentid', $instrumentids, $instrumentid, true); ?></td>
                </tr>
                <tr>
                    <td align="right">Payment Type:</td>
                    <td><?php combobox('modeid', $modeids, $modeid, TRUE); ?></td>
                </tr>
                <tr>
                    <td align="right">Beneficiary: </td>
                    <td><?php echo $rec->SUPPLIER_NAME; ?> <input type="hidden" name="supplierid" value="<?php echo $rec->supplier_id; ?>" /></td>
                </tr>
                <tr>
                    <td align="right">Challan No/GRN: </td>
                    <td><?php
                        $challan_no = findValue("SELECT MAX(challanno) AS challanno FROM purchase_order_delivery WHERE poid='$poid'");
                        echo $challan_no;
                        ?>
                    </td>
                </tr>
                <tr>
                    <td align="right">Expense No: </td>
                    <td><?php echo OrderNo($exp_no); ?><input type="hidden" name="claim_id" value="<?php echo OrderNo($claim_id); ?>" /></td>
                </tr>

                <tr>
                    <td align="right">Purpose: </td>
                    <td><input type="text"  size="28" name="purpose" value="" /></td>
                </tr>
                <tr>
                    <td align="right">WO/PO No: </td>
                    <td>
                        <a href="po_view.php?poid=<?php echo $rec->poid; ?>" target=_blank><?php echo $rec->purchase_order_id; ?>
                            <input type="hidden" name="po_no" value="<?php echo $rec->purchase_order_id; ?>" />
                        </a>
                    </td>
                </tr>
                <tr>
                    <td align="right">WO/PO Date: </td>
                    <td><?php echo bddate($rec->order_date); ?><input type="hidden" name="po_date" value="<?php echo $rec->order_date; ?>" /></td>
                </tr>
                <tr>
                    <td align="right"><div align="right">Quotation/Bid Ref </div></td>
                    <td><input id="referance" tabindex="11" size="28" value="<?php echo $rec->supp_ref; ?>" name="referance" readonly="1" /></td>
                </tr>
            </table>

            <table class="ui-state-default">
                <thead>
                    <tr>
                        <th>S.No.</th>
                        <th>Date</th>
                        <th>Invoice No.</th>
                        <th>Pay. Type</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <?php
                $sql = "SELECT DATE_FORMAT(EXPENSE_DATE,'%e-%b %Y') AS EXPENSE_DATE, INVOICE_NO, NET_PAY, pm.MODE_NAME
                FROM fin_payment_approval_note  fpan
                LEFT JOIN payment_mode pm ON pm.PAYMENT_MODE_ID=fpan.PAYMENT_MODE_ID
                WHERE PURCHASE_ORDER_ID='$poid'";
                $result = query($sql);
                while ($row = fetch_object($result)) {
                    ?>
                    <tr>
                        <td><?php echo++$sl; ?></td>
                        <td><?php echo $row->EXPENSE_DATE; ?></td>
                        <td><?php echo $row->INVOICE_NO; ?></td>
                        <td><?php echo $row->MODE_NAME; ?></td>
                        <td align="right"><?php echo $row->NET_PAY; ?></td>
                    </tr>
                    <?php
                }
                ?>
            </table>
            <table class="table">
                <tr>
                    <td align="right" width="170">Invoice No: </td>
                    <td><input id="invoice_no"  size="28" tabindex="11" name="invoice_no" class="required" onchange="ajaxLoader('ajax_invoice_check.php?val=' + this.value + '&supplierid=<?php echo $rec->supplier_id; ?>', 'ajax_invoice', '<left><img src=../images/ajaxLoader.gif></left>');" />
                        <p id="ajax_invoice"><input type="hidden" name="is_duplicate" value="0" /></p>  </td>
                </tr>
                <tr>
                    <td align="right">Invoice Date: </td>
                    <td><input type="text" name="invoice_date" class="easyui-datebox" value="<?php $invoice_date; ?>" data-options="formatter:myformatter,parser:myparser"/></td>
                </tr>
                <tr>
                    <td align="right">WO/PO Value TK: </td>
                    <td><input type="text"  size="28" name="wo_amount" id="wo_amount" value="<?php echo $sub_total; ?>" readonly="readonly" /></td>
                </tr>

                <tr>
                    <td align="right">Recommend Bill%: </td>
                    <td><input type="text"  size="28" name="firstRecommendBill" id="billId" value="<?php ?>" /></td>
                </tr>

                <tr>
                    <td align="right">Security Deposit%: </td>
                    <td> <input type="text"  size="28" name="securityDeposit" id="securityDepositId" class="number" onchange="securityAmountChange();" value=""  /> Amount: <input type="text" name="securityAmount" id="securityAmountId" value="0" readonly="readonly"/></td>
                </tr>

                <tr>
                    <td align="right">Income Tax%: </td>
                    <td> <input type="text"  size="28" name="incomeTax" id="incomeTaxId" onchange="subTotalChange();"  value="" /> Amount: <input type="text" name="incomeTaxAmount" id="incomeTaxAmountId" value="0" /></td>
                </tr>

                <tr>
                    <td align="right">Vat% : </td>
                    <td> <input type="text"  size="28" name="vat" id="vatId" onchange="vatAmountChange();" value="" /> Amount: <input type="text" name="vatAmount" id="vatAmount" value="0" readonly="readonly"/></td>
                </tr>

                </tr>

                <tr>
                    <td align="right">Sub Total: </td>
                    <td><input type="text"  size="28" name="subTotal" id="subTotalId" value="0" onchange="netBill();" readonly="readonly" /></td>
                </tr>

                </tr>
                <tr>
                    <td align="right">Net Bill : </td>
                    <td><input type="text"  size="28" name="netBill" id="netBillId" value="0" readonly="readonly" /></td>
                </tr>
                <tr>
                    <td align="right" valign="top">Note(If any): </td>
                    <td><textarea name="comments" cols="32"></textarea></td>
                </tr>
            </table>

            <?php
            file_upload_edit($poid, $Module, TRUE);
            deligationAdd();
            ?>

            <div style="font-weight: bold; margin: 10px 0px;">This payable amount is  subject to  AIT &  VAT  and  other deductions  (If applicable)</div>

            <button class='button' type='submit' value='save' name='save'>Edit</button>
            <button class='button' type='submit' value='save' name='save'>Confirm</button>
            <button class='button' type='submit' value='save' name='save'>Return</button>

        </form>

    </div>
</div>

<?php include("../body/footer.php"); ?>