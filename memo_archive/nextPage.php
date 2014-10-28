<?php
include_once '../lib/DbManager.php';
include '../body/header.php';

$searchID = getParam('id');


$payTypeList = rs2array(query("SELECT  PAY_TYPE_NAME, PAY_TYPE_NAME FROM pay_type ORDER BY PAY_TYPE_NAME"));

$selectSQL = "SELECT MEMO_TYPE, MEMO_DATE, MEMO_INFO_REF, MEMO_REF, BOARD_NO, BOARD_DATE, MEMO_DETAILS, MEMO_CATEGORY, APPROVED_AMOUNT, REMARKS,
    PAYMENT_METHOD, MEMO_SUBJECT, CREATED_BY, CREATED_DATE FROM memo_archive WHERE MEMO_ARCHIVE_ID='$searchID'";
$memoObj = find($selectSQL);


$appTypeSQL = "SELECT FIRST_NAME, approveType FROM mem_manage_emp_det ed
LEFT OUTER JOIN employee em ON em.EMPLOYEE_ID = ed.empID
WHERE memo_archive_id='$searchID' ORDER BY _sort";
$TypeResult = query($appTypeSQL);

$memoType = $memoObj->MEMO_TYPE;
$memoDate = $memoObj->MEMO_DATE;
$memoInfoRef = $memoObj->MEMO_INFO_REF;
$paymentMethod = $memoObj->PAYMENT_METHOD;
$memoDetails = $memoObj->MEMO_DETAILS;
$memoCategory = $memoObj->MEMO_CATEGORY;
$approveAmount = $memoObj->APPROVED_AMOUNT;
$remarks = $memoObj->REMARKS;
$payMethod = $memoObj->PAYMENT_METHOD;
$memoSub = $memoObj->MEMO_SUBJECT;
$memoRef = $memoObj->MEMO_REF;
$boardDate = $memoObj->BOARD_DATE;
$boardNo = $memoObj->BOARD_NO;


$moduleName = 'memo_archive';
$BasInfSQL = "SELECT MEMO_TYPE, MEMO_DATE, MEMO_INFO_REF, MEMO_REF, BOARD_NO, BOARD_DATE, MEMO_DETAILS, MEMO_CATEGORY, APPROVED_AMOUNT, REMARKS,
    PAYMENT_METHOD, MEMO_SUBJECT FROM memo_archive WHERE MEMO_ARCHIVE_ID='$searchID'";
$fileSQL = "SELECT ATTACH_TITTLE, ATTACH_FILE_PATH FROM memo_file_attach_list WHERE REQUEST_ID='$searchID' AND MODULE_NAME='$moduleName'";
$BasInfObj = find($BasInfSQL);
$fileResult = query($fileSQL);
//$db->CloseDb();

if (isSave()) {

    $paymentFor = getParam('pay_for');
    if ($paymentFor == 'supplier') {
        $paymentForID = getParam('pay_for_supplier');
    } else {
        $paymentForID = getParam('pay_for_employer');
    }

    //$paymentForID = getParam('pay_for_id'); #AMOUNT,#VAT,#TAX,#ADV_AMNT,#SCR_MONEY,#PNLT_AMNT
    $payDate = getParam('payment_date');
    $amount = getParam('AMOUNT');
    $payType = getParam('payment_type');
    $vat = getParam('VAT');
    $tax = getParam('TAX');
    $advanceAmnt = getParam('ADV_AMNT');
    $securityMoney = getParam('SCR_MONEY');
    $penalty = getParam('PNLT_AMNT');
    $payMode = getParam('pay_mode');
    $payModeNo = getParam('pay_mode_no');
    $remarks = getParam('REMARKS');
    $restAmount = getParam('REST_AMOUNT');
    $additionalAmount = getParam('ADDITIONAL_AMOUNT');
    $forfeitAmount = getParam('FORFEIT_AMOUNT');
    $cbNo = getParam('CB_NO');

    //$db = new DbManager();
    //$db->OpenDb();

    $insDetailsSQL = "INSERT INTO memo_archive_details (payment_for, payment_for_id, payment_date, pay_type, amount, vat, tax, advance_amount, security_money, penalty_amount, payment_mode, payment_mode_no, remarks, rest_amount, additional_amount, forfeit_amount, memo_archive_id,cb_no)
    VALUES ('$paymentFor', '$paymentForID', '$payDate', '$payType', '$amount', '$vat', '$tax', '$advanceAmnt', '$securityMoney', '$penalty', '$payMode', '$payModeNo', '$remarks', '$restAmount', '$additionalAmount', '$forfeitAmount', '$searchID', '$cbNo')";
    query($insDetailsSQL);

    echo "<script>location.replace('nextPage.php?id=$searchID');</script>";
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

        $('#AMOUNT,#VAT,#TAX,#ADV_AMNT,#SCR_MONEY,#PNLT_AMNT').change(function() {
            var claimAmnt = $('#AMOUNT').val();
            var vat = $('#VAT').val();
            var tax = $('#TAX').val();
            var advAmnt = $('#ADV_AMNT').val();
            var scrMoney = $('#SCR_MONEY').val();
            var pnltAmount = $('#PNLT_AMNT').val();
            vat == '' ? vat = 0 : vat = vat;
            tax == '' ? tax = 0 : tax = tax;
            advAmnt == '' ? advAmnt = 0 : advAmnt = advAmnt;
            scrMoney == '' ? scrMoney = 0 : scrMoney = scrMoney;
            pnltAmount == '' ? pnltAmount = 0 : pnltAmount = pnltAmount;

            $('#PAY_AMOUNT').val(claimAmnt - vat - tax - advAmnt - scrMoney - pnltAmount);
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
                        var absVal = Math.abs(totRestAmount);
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


</script>  


<div class="panel-header">Memo</div>
<div style="padding: 20px 20px; background: white;">

    <fieldset>
        <legend>BASIC INFORMATION</legend>
        <table class="table">
            <tr>
                <td width="150">MEMO NO : </td>
                <td> <?php echo $memoRef; ?> &nbsp;&nbsp;&nbsp;&nbsp;<strong>MEMO DATE:</strong> <?php echo bddate($memoDate); ?></td>
            </tr>
            <tr>                        
                <td>MEMO INFO REF</td>
                <td><?php echo $memoInfoRef; ?></td>
            </tr>

            <tr>                        
                <td>SUBJECT :</td>
                <td><?php echo $memoSub; ?></td>
            </tr>

            <tr>                        
                <td>Memo Type :</td>
                <td><?php echo $memoType; ?>
                    <?php if ($memoType == 'management') { ?>
                        <?php
                        echo '&nbsp;&nbsp;<b>Approval Persons :</b>';
                        while ($TypeObj = fetch_object($TypeResult)) {
                            echo++$proSL . '.' . $TypeObj->FIRST_NAME . ' (' . $TypeObj->approveType . '),';
                        }
                        ?>
                    </td>
                    <?php
                } if ($memoType == 'board') {
                    echo '<b>BOARD NO:</b>' . ' ' . $boardNo . '<b>DATE</b>' . bddate($boardDate);
                }
                ?>
            </tr>

            <tr>
                <td>CC Code/ Division/ Office :</td>
                <td>
                    <?php
                    $slNo = 0;
                    $divSQL = "SELECT cc.COST_CENTER_CODE, cc.COST_CENTER_NAME, dn.DIVISION_NAME

                    FROM mem_man_div_details md 
                    LEFT JOIN cost_center cc ON cc.COST_CENTER_ID=md.cost_center_id
                    LEFT JOIN division dn ON dn.DIVISION_ID = cc.DIVISION_ID 
                    WHERE md.memo_management_id='$searchID'";
                    $resultSQL = query($divSQL);
                    while ($objDiv = fetch_object($resultSQL)) {
                        echo++$slNo . '.' . $objDiv->COST_CENTER_CODE . $objDiv->COST_CENTER_NAME . $objDiv->DIVISION_NAME . ', ';
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td>MEMO CATEGORY :</td>
                <td><?php echo $memoCategory; ?></td>
            </tr>


            <tr>
                <td>DETAILS :</td>
                <td><?php echo $memoDetails; ?></td>
            </tr>
            <tr>
                <td>APPROVED AMOUNT :</td>
                <td><?php echo $approveAmount; ?></td>
            </tr>
            <tr>
                <td>REMARKS :</td>
                <td><?php echo $remarks; ?></td>
            </tr>

            <tr>
                <td>PAYMENT_METHOD:</td>
                <td><?php echo $paymentMethod; ?></td>
            </tr>
            <tr>
                <td>ATTACHMENTS:</td>
                <td >
                    <?php
                    $sl = 0;
                    $fileSQL = "SELECT ATTACH_TITTLE, ATTACH_FILE_PATH FROM file_attach_list WHERE REQUEST_ID='$searchID' AND MODULE_NAME='memo_archive'";
                    //$db->OpenDb();
                    $fileResult = query($fileSQL);
                    //$db->CloseDb();
                    while ($objInfo = fetch_object($fileResult)) {
                        echo++$sl . '.' . "<a href='<?php echo $objInfo->ATTACH_FILE_PATH; ?>'>$objInfo->ATTACH_TITTLE</a>" . ', ';
                    }
                    ?>
                </td>
            </tr>
        </table>
    </fieldset>

    <br />
    <br />

    <form method="post" enctype="multipart/form-data" id="fm" novalidate autocomplete="off">
        <input type="hidden" name="id" value="<?php echo $searchID; ?>"/>
        <table class="table">
            <tr>                        
                <td width='250'>PAYMENT FOR:</td>
                <td>
                    <input type="radio" id='supplier' name="pay_for" value="supplier"> Supplier 
                    <input type="radio" id='employer' name="pay_for" value="employer"> Employer
                </td>
            </tr>

            <tr id="classSupEmp">
                <td><label id='label_pay_for'>SUPPLIER/EMPLOYEE ID:</label></td>
                <td>
                    <div id="cgi"><input id="cg" type='text' name='pay_for_employer' class='easyui-validatebox' size="20"></div>
                    <div id="cgi1"><input id="cg1" type='text' name='pay_for_supplier' class='easyui-validatebox' size="20"></div>
                </td>
            </tr>
            <tr>
                <td>Payment Date:</td>
                <td><input type='text' name='payment_date' class='easyui-datebox' data-options="formatter:myformatter,parser:myparser" size="20"></td>
            </tr>
            <tr>
                <td>Payment Mood:</td>
                <td><?php comboBox('payment_type', $payTypeList, $pay_type, TRUE); ?></td>
            </tr>
            <tr>
                <td> Claim Amount:</td>
                <td><input type='text' name='AMOUNT' id='AMOUNT' class='easyui-validatebox' size="20"></td>
            </tr>
            <tr>   
                <td>VAT:</td>
                <td><input type='text' name='VAT' id='VAT' class='easyui-validatebox' size="20"></td>
            </tr>
            <tr>
                <td>TAX:</td>
                <td><input type='text' name='TAX' id='TAX' class='easyui-validatebox' size="20"></td>
            </tr>
            <tr>
                <td>Advance Amount:</td>
                <td><input type='text' name='ADV_AMNT' id='ADV_AMNT' class='easyui-validatebox' size="20"></td>
            </tr>
            <tr>
                <td>Security Money:</td>
                <td><input type='text' name='SCR_MONEY' id='SCR_MONEY' class='easyui-validatebox' size="20"></td>
            </tr>
            <tr>
                <td>Penalty Amount:</td>
                <td><input type='text' name='PNLT_AMNT' id='PNLT_AMNT' class='easyui-validatebox' size="20"></td>
            </tr>
            <tr>
                <td>Payable Amount:</td>
                <td><input type='text' name='PAY_AMOUNT' id='PAY_AMOUNT' class='easyui-validatebox' size="20"></td>
            </tr>
            <tr>
                <td>Payment Mode:</td>
                <td>
                    <input type="radio" name="pay_mode" id='pay_order' value="PO No." checked> PAY ORDER
                    <input type="radio" name="pay_mode" id='cb_no' value="A/C No"> ACCOUNT NO 
                </td>
            </tr>
            <tr>
                <td><label id='label_pay_mode'>PAY ORDER</label></td>
                <td><input type='text' name='pay_mode_no' class='easyui-validatebox' size="20"></td>
            </tr>
            <tr>
                <td>CB No:</td>
                <td><input type='text' name='CB_NO' class='easyui-validatebox' size="20"></td>
            </tr>
            <tr>
                <td>Rest Amount :</td>
                <td><input type='text' name='REST_AMOUNT' class='easyui-validatebox' size="20" id="REST_AMOUNT"></td>
            </tr>
            <tr>
                <td>Additional Amount:</td>
                <td><input type='text' id="ADDITIONAL_AMOUNT" name='ADDITIONAL_AMOUNT' class='easyui-validatebox' size="20"></td>
            </tr>
            <tr>
                <td>Forfeit Amount :</td>
                <td><input type='text' id="FORFEIT_AMOUNT" name='FORFEIT_AMOUNT' class='easyui-validatebox' size="20"></td>
            </tr>
            <tr>
                <td>Remarks :</td>
                <td><textarea name='REMARKS' placeholder="Enter your remarks here"></textarea></td>
            </tr>
        </table>
        <input type="submit" name="save" value="save" class="button">
    </form>
    <br />
    <table class="ui-state-default">
        <thead>
        <th width='40'>SL.</th>
        <th>Date</th>
        <th>Amount</th>
        <th>VAT</th>
        <th>TAX</th>
        <th>Advance Amount</th>
        <th>Security Money</th>
        <th>Penalty</th>
        <th>Additional Amount</th>
        <th>Rest Amount</th>
        <th>Forfeit</th>
        <th>Pay Mode</th>
        <th>Payment Mode & No.</th>
        <th>CB No.</th>
        <th>Remarks</th>
        </thead>
        <tbody>
            <?php
            //$db->OpenDb();
            $SQL = "SELECT payment_date, amount, payment_mode, payment_mode_no,cb_no,vat, tax, advance_amount, security_money, penalty_amount, additional_amount, rest_amount, forfeit_amount, pay_type, remarks FROM memo_archive_details WHERE memo_archive_id='$searchID'";
            $result = query($SQL);
            while ($obj = fetch_object($result)) {
                $totAmount+=$obj->amount;
                ?>
                <tr>
                    <td><?php echo++$SL; ?></td>
                    <td><?php echo bddate($obj->payment_date); ?></td>
                    <td align='right'><?php echo formatMoney($obj->amount); ?></td>
                    <td align='right'><?php echo formatMoney($obj->vat); ?></td>
                    <td align='right'><?php echo formatMoney($obj->tax); ?></td>
                    <td align='right'><?php echo formatMoney($obj->advance_amount); ?></td>
                    <td align='right'><?php echo formatMoney($obj->security_money); ?></td>
                    <td align='right'><?php echo formatMoney($obj->penalty_amount); ?></td>
                    <td align='right'><?php echo formatMoney($obj->additional_amount); ?></td>
                    <td align='right'><?php echo formatMoney($obj->rest_amount); ?></td>
                    <td align='right'><?php echo formatMoney($obj->forfeit_amount); ?></td>
                    <td align='right'><?php echo $obj->pay_type; ?></td>
                    <td align="center"><?php echo $obj->payment_mode . '(' . $obj->payment_mode_no . ')'; ?></td>
                    <td align="center"><?php echo $obj->cb_no; ?></td>
                    <td><?php echo $obj->remarks; ?></td>
                </tr>

                <?php
            }
            ?>

        </tbody>
        <tfoot>
            <tr>

                <td colspan="2" align="right"><b>Total Received </b></td>
                <td align="right"><b><?php echo formatMoney($totAmount); ?></b></td>
                <td colspan="11"></td>
                <td></td>
            </tr>
        </tfoot>
    </table>
    <a href="index.php" class="button">Memo List</a>
</div>