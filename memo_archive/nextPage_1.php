<?php
include_once '../lib/DbManager.php';
include_once '../body/body_header.php';
include '../body/header.php';

$searchID = getParam('id');

$db = new DbManager();
$db->OpenDb();
$moduleName = 'memo_archive';
$BasInfSQL = "SELECT MEMO_TYPE, MEMO_DATE, MEMO_INFO_REF, MEMO_REF, BOARD_NO, BOARD_DATE, MEMO_DETAILS, MEMO_CATEGORY, APPROVED_AMOUNT, REMARKS,
    PAYMENT_METHOD, MEMO_SUBJECT FROM memo_archive WHERE MEMO_ARCHIVE_ID='$searchID'";
$fileSQL = "SELECT ATTACH_TITTLE, ATTACH_FILE_PATH FROM memo_file_attach_list WHERE REQUEST_ID='$searchID' AND MODULE_NAME='$moduleName'";
$BasInfObj = find($BasInfSQL);
$fileResult = query($fileSQL);
$db->CloseDb();

if (isSave()) {

    echo $paymentFor = getParam('pay_for');
    if ($paymentFor == 'supplier') {
        $paymentForID = getParam('pay_for_supplier');
    } else {

        $paymentForID = getParam('pay_for_employer');
    }

    //$paymentForID = getParam('pay_for_id');
    $payDate = getParam('payment_date');
    $amount = getParam('AMOUNT');
    $payType = getParam('payment_type');
    $vat = getParam('VAT');
    $tax = getParam('TAX');
    $payMode = getParam('pay_mode');
    $payModeNo = getParam('pay_mode_no');
    $remarks = getParam('REMARKS');
    $restAmount = getParam('REST_AMOUNT');
    $additionalAmount = getParam('ADDITIONAL_AMOUNT');
    $forfeitAmount = getParam('FORFEIT_AMOUNT');
    $cbNo = getParam('CB_NO');



    // rest_amount,additional_amount, forfeit_amount,

    $db = new DbManager();
    $db->OpenDb();

    $insDetailsSQL = "INSERT INTO memo_archive_details 
(payment_for, payment_for_id, payment_date, pay_type, amount, vat, tax, payment_mode, payment_mode_no, remarks, rest_amount, additional_amount, forfeit_amount, memo_archive_id,cb_no)
VALUES ('$paymentFor', '$paymentForID', '$payDate', '$payType', '$amount', '$vat', '$tax', '$payMode', '$payModeNo', '$remarks', '$restAmount', '$additionalAmount', '$forfeitAmount', '$searchID', '$cbNo')";
    query($insDetailsSQL);

    $db->CloseDb();
}
?>

<script type="text/javascript">
    $(document).ready(function() {
        $('#classSupEmp').hide();
        $('#cgi').hide();
        $('#cgi1').hide();
        $('#supplier').click(function() {
            $('#label_pay_for').text('SUPPLIER');
            $('#classSupEmp').fadeIn();
            $('#cgi1').fadeIn();
            $('#cgi').hide();
        });

        $('#employer').click(function() {
            $('#label_pay_for').text('EMPLOYER');
            $('#classSupEmp').fadeIn();
            $('#cgi').fadeIn();
            $('#cgi1').hide();
        });

        $('#cb_no').click(function() {
            $('#label_pay_mode').text('ACCOUNT NO');
        });

        $('#pay_order').click(function() {
            $('#label_pay_mode').text('PAY ORDER');
        });

        $('#cg').combogrid({
            panelWidth: 500,
            url: 'getdataForCombo.php',
            idField: 'EMPLOYEE_ID',
            textField: 'EM',
            mode: 'remote',
            fitColumns: true,
            columns: [[
                    {field: 'EMPLOYEE_ID', title: 'Employee ID', width: 20},
                    {field: 'CARD_NO', title: 'Card No', align: 'right', width: 20},
                    {field: 'FIRST_NAME', title: 'Employee Name', align: 'left', width: 40}
                ]]
        });

        $('#cg1').combogrid({
            panelWidth: 500,
            url: 'getdataForCombo1.php',
            idField: 'SUPPLIER_ID',
            textField: 'EM',
            mode: 'remote',
            fitColumns: true,
            columns: [[
                    {field: 'SUPPLIER_ID', title: 'SUPPLIER ID', width: 10},
                    {field: 'SUPPLIER_NAME', title: 'SUPPLIER NAME', align: 'left', width: 20}
                ]]
        });

        $('#AMOUNT').change(function() {

            var amnt = $('#AMOUNT').val();
            //var ID =$searchID; 
            //alert(ID);
            $.ajax({url: 'ajaxRestAmount.php?id=' +<?php echo $searchID; ?>,
                success: function(data) {
                    var totRestAmount = data - amnt;
                    if (totRestAmount < 0) {
                        $("#REST_AMOUNT").val('0');
                        var absVal= Math.abs(totRestAmount);
                        $("#ADDITIONAL_AMOUNT").val(absVal);
                    }
                    else if (totRestAmount > 0) {
                        var payType = $('#payment_type').val();
                        if (payType == 'Final') {
                            $("#FORFEIT_AMOUNT").val(totRestAmount);
                            $("#ADDITIONAL_AMOUNT").val('0');
                            $("#REST_AMOUNT").val('0');
                        }
                        else {
                            $("#REST_AMOUNT").val(totRestAmount);
                            $("#FORFEIT_AMOUNT").val('0');
                            $("#ADDITIONAL_AMOUNT").val('0');
                        }

                    }
                    else {
                        $("#FORFEIT_AMOUNT").val('0');
                        $("#ADDITIONAL_AMOUNT").val('0');
                        $("#REST_AMOUNT").val('0');
                    }

                }
            });

        });
    });
</script>
<script type="text/javascript">
    $(function() {
        $('#cg12').combogrid({
            panelWidth: 500,
            url: 'getdataForCombo.php',
            idField: 'SUPPLIER_ID',
            textField: 'SUPPLIER_NAME',
            mode: 'remote',
            fitColumns: true,
            columns: [[
                    {field: 'SUPPLIER_ID', title: 'SUPPLIER ID', width: 60},
                    {field: 'SUPPLIER_NAME', title: 'SUPPLIER_NAME', width: 80}
                ]]
        });
        $("input[name='mode']").change(function() {
            var mode = $(this).val();
            $('#cg1').combogrid({
                mode: mode
            });
        });
    });

    function myformatter(date) {
        var y = date.getFullYear();
        var m = date.getMonth() + 1;
        var d = date.getDate();
        return y + '-' + (m < 10 ? ('0' + m) : m) + '-' + (d < 10 ? ('0' + d) : d);
    }
    function myparser(s) {
        if (!s)
            return new Date();
        var ss = (s.split('-'));
        var y = parseInt(ss[0], 10);
        var m = parseInt(ss[1], 10);
        var d = parseInt(ss[2], 10);
        if (!isNaN(y) && !isNaN(m) && !isNaN(d)) {
            return new Date(y, m - 1, d);
        } else {
            return new Date();
        }
    }
</script>  

<div id="p" class="easyui-panel" title="Basic Panel" style="width:1000px;height:auto;padding:10px;">
    <fieldset>
        <legend>Basic Info</legend>
        <table class="table">
            <tr class='fitem'>                        
                <td><strong>MEMO INFO REF</strong></td>
                <td><?php echo $BasInfObj->MEMO_INFO_REF; ?></td>
            </tr>
            <tr class='fitem'>                        
                <td><strong>MEMO DATE</strong></td>
                <td><?php echo $BasInfObj->MEMO_DATE; ?></td>
            </tr>
            <tr class='fitem'>                        
                <td><strong>MEMO CATEGORY</strong></td>
                <td><?php echo $BasInfObj->MEMO_CATEGORY; ?></td>
            </tr>
            <tr class='fitem'>                        
                <td><strong>APPROVED AMOUNT</strong></td>
                <td><?php echo $BasInfObj->APPROVED_AMOUNT; ?></td>
            </tr>
            <tr class='fitem'> 

                <td><strong> SUBJECT :</strong></td>
                <td><?php echo $BasInfObj->MEMO_SUBJECT; ?></td>
            </tr>
            <tr><td>Attachments</td></tr>
            <tr> 
                <td colspan="5">
                    <table class="ui-state-default" id="attachment_tab">
                        <thead>
                        <th>SL</th>
                        <th>Attachment Tittle</th>
                        <th align="right">Action</th>
                        </thead>
                        <tbody>
                            <?php
                            $sl = 0;
                            while ($fileObj = fetch_object($fileResult)) {
                                ?>
                                <tr>
                                    <td><?php echo++$sl; ?></td>
                                    <td><?php echo $fileObj->ATTACH_TITTLE; ?></td>
                                    <td align='center'><a href='<?php echo $fileObj->ATTACH_FILE_PATH; ?>'>view</a></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                </td>
            </tr>
        </table>
        </table>
    </fieldset>
    <br />

    <form method="post" enctype="multipart/form-data" id="fm" novalidate autocomplete="off">
        <table>
            <tr class='fitem'>                        
                <td>PAYMENT FOR:</td>
                <td>
                    <input type="radio" id='supplier' name="pay_for" value="supplier"> Supplier 
                    <input type="radio" id='employer' name="pay_for" value="employer"> Employer
                </td>
            </tr>

            <tr class='fitem' id="classSupEmp">
                <td><label id='label_pay_for'>SUPPLIER/EMPLOYEE ID:</label></td>
                <td>
                    <div id="cgi"><input id="cg" type='text' name='pay_for_employer' class='easyui-validatebox' size="20"></div>
                    <div id="cgi1"><input id="cg1" type='text' name='pay_for_supplier' class='easyui-validatebox' size="20"></div>
                </td>

            </tr>
            <tr class='fitem'>
                <td>Payment DATE :</td>
                <td><input type='text' name='payment_date' class='easyui-datebox' data-options="formatter:myformatter,parser:myparser" size="20"></td>
            </tr>
            <tr class='fitem'>
                <td>PAYMENT TYPE :</td>
                <td><select id="payment_type" name="payment_type">
                        <option value="Partial ">Partial </option>
                        <option value="Final">Final</option>
                    </select>
                </td>
            </tr>
            <tr class='fitem'>
                <td>AMOUNT :</td>
                <td><input type='text' name='AMOUNT' id='AMOUNT' class='easyui-validatebox' size="20"></td>
            </tr>
            <tr class='fitem'>   
                <td>VAT</td>
                <td><input type='text' name='VAT' id='VAT' class='easyui-validatebox' size="20"></td>
            </tr>
            <tr class='fitem'>
                <td>TAX</td>
                <td><input type='text' name='TAX' id='TAX' class='easyui-validatebox' size="20"></td>
            </tr>
            <tr class='fitem'>
                <td>PAYMENT MODE:</td>
                <td>
                    <input type="radio" name="pay_mode" id='pay_order' value="pay_order"> PAY ORDER
                    <input type="radio" name="pay_mode" id='cb_no' value="cb_no"> ACCOUNT NO 
                </td>
            </tr>
            <tr class='fitem'>
                <td><label id='label_pay_mode'>PAY ORDER/CB NO:</label></td>
                <td><input type='text' name='pay_mode_no' class='easyui-validatebox' size="20"></td>
            </tr>
            <tr class='fitem'>
                <td>CB NO:</td>
                <td><input type='text' name='CB_NO' class='easyui-validatebox' size="20"></td>
            </tr>
            <tr class='fitem'>
                <td>REST AMOUNT :</td>
                <td><input type='text' name='REST_AMOUNT' class='easyui-validatebox' size="20" id="REST_AMOUNT"></td>
            </tr>
            <tr class='fitem'>
                <td>ADDITIONAL AMOUNT:</td>
                <td><input type='text' id="ADDITIONAL_AMOUNT" name='ADDITIONAL_AMOUNT' class='easyui-validatebox' size="20"></td>
            </tr>
            <tr class='fitem'>
                <td>FORFEIT AMOUNT :</td>
                <td><input type='text' id="FORFEIT_AMOUNT" name='FORFEIT_AMOUNT' class='easyui-validatebox' size="20"></td>
            </tr>
            <tr class='fitem'>
                <td>REMARKS :</td>
                <td><textarea name='REMARKS' placeholder="Enter your remarks here"></textarea></td>
            </tr>
        </table>
        <input type="submit" name="save" value="save" class="button">
    </form>
    <?php
    //$sql="SELECT amount,remarks,payment_date FROM memo_archive_details";
    ?>
    <table class="ui-state-default">
        <thead>
        <th>SL.</th>
        <th>Amount</th>
        <th>Date</th>
        <th>Remarks</th>
        </thead>
        <tbody>
            <?php ?>
            <tr>
                <td>1.</td>
                <td align='right'>1000.00</td>
                <td>No issues...</td>
            </tr>
        </tbody>
    </table>
</div>