<?php
include_once '../lib/DbManager.php';
include_once '../body/body_header.php';
include '../body/header.php';

$searchID = getParam('id');

$db = new DbManager();
$db->OpenDb();


$selectSQL = "SELECT MEMO_TYPE, MEMO_DATE, MEMO_INFO_REF, MEMO_REF, BOARD_NO, BOARD_DATE, MEMO_DETAILS, MEMO_CATEGORY, APPROVED_AMOUNT, REMARKS,
    PAYMENT_METHOD, MEMO_SUBJECT, CREATED_BY, CREATED_DATE FROM memo_archive WHERE MEMO_ARCHIVE_ID='$searchID'";

$memoObj = find($selectSQL);

$appTypeSQL="SELECT FIRST_NAME, approveType FROM mem_manage_emp_det ed
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

<div class="easyui-layout" style="width:100%; height:700px;">  
    <div data-options="region:'east', split:true, collapsed:false" title="Notifications" style="width:250px;">  
        
    </div>
    <div data-options="region:'center'"> 
        <div class="easyui-accordion" data-options="fit:true,border:false,plain:true">  
            <div title="Memo ">       
                <div id="dlg"  style="padding:10px 20px" closed="true" buttons="#dlg-buttons">
                    <fieldset>
                        <legend>BASIC INFORMATION</legend>
                        <table class="table">
                            <tr class='fitem'>
                                <td>MEMO NO : </td><td> <?php echo $memoRef; ?> &nbsp;&nbsp;&nbsp;&nbsp;<strong><b>MEMO DATE :</b></strong> <?php echo $memoDate; ?></td>
                            </tr>
                            <tr class='fitem'>                        
                                <td width="200">MEMO INFO REF</td>
                                <td width="550"><?php echo $memoInfoRef; ?></td>
                            </tr>

                            <tr class='fitem'>                        
                                <td>SUBJECT :</td>
                                <td><?php echo $memoSub; ?></td>
                            </tr>

                            <tr class='fitem'>                        
                                <td> MEMO TYPE :</td>
                                <td><?php echo $memoType; ?></td>
                            </tr>
                            
                            <?php if ($memoType=='board'){ ?>
                            <tr class='fitem'>
                                <td></td>
                                <td> BOARD NO:
                                <?php echo $boardNo;?>  DATE:<?php echo $boardDate;?> </td>
                            </tr>
                            <?php } 
                            if($memoType=='management') { ?>
                            
                                <td>
                                   <?php 
                                     while($TypeObj = fetch_object($TypeResult)){
                                       echo ++$proSL.'.'.$TypeObj->FIRST_NAME.' ('.$TypeObj->approveType.')|';
                                     } ?>
                                </td>
                            <?php } ?>
                            <tr class='fitem'>
                                
                            </tr>

                            
                            <tr class='fitem'>
                                <td>DIVISION :</td>
                                <td>
                                    <table class="ui-state-default" id="cost_center" >
                                        <thead>
                                        <th width="30">SL</th>
                                        <th>Division</th>                        
                                        </thead>
                                        <tbody>
                                            <?php
                                            $slNo = 0;
                                            
                                            $divSQL = "SELECT CONCAT(cc.COST_CENTER_CODE,'-',cc.COST_CENTER_NAME,'-', dn.DIVISION_NAME) AS CcDiv FROM cost_center cc
                                LEFT OUTER JOIN division dn ON dn.DIVISION_ID = cc.DIVISION_ID
                                LEFT OUTER JOIN mem_man_div_details dd ON dd.division = cc.COST_CENTER_CODE 
                                WHERE dd.memo_management_id='$searchID'";
                                            $db->OpenDb();
                                            $resultSQL = query($divSQL);
                                            $db->CloseDb();
                                            while ($objDiv = fetch_object($resultSQL)) {
                                                ?>
                                                <tr>
                                                    <td><?php echo++$slNo; ?></td>
                                                    <td><?php echo $objDiv->CcDiv; ?></td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>


                            <tr class='fitem'>
                                <td>MEMO_CATEGORY :</td>
                                <td><?php echo $memoCategory; ?></td>
                            </tr>


                            <tr class='fitem'>
                                <td>DETAILS :</td>
                                <td><?php echo $memoDetails; ?></td>
                            </tr>
                            <tr class='fitem'>
                                <td>APPROVED_AMOUNT :</td>
                                <td><?php echo $approveAmount; ?></td>
                            </tr>
                            <tr class='fitem'>
                                <td>REMARKS :</td>
                                <td><?php echo $remarks; ?></td>
                            </tr>

                            <tr class='fitem'>
                                <td>PAYMENT_METHOD:</td>
                                <td><?php echo $paymentMethod; ?></td>
                            </tr>
                            <tr class='fitem'>
                                <td>ATTACHMENTS</td>
                                <td >
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>
                                    <table class="ui-state-default" id="attachment_tab">
                                        <thead>
                                        <th width="30">SL</th>
                                        <th align="left">Attachment Tittle</th>
                                        <th align='center'>Action</th>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $sl = 0;
                                            $fileSQL = "SELECT ATTACH_TITTLE, ATTACH_FILE_PATH FROM memo_file_attach_list WHERE REQUEST_ID='$searchID' AND MODULE_NAME='memo_archive'";
                                            $db->OpenDb();
                                            $fileResult = query($fileSQL);
                                            $db->CloseDb();
                                            while ($objInfo = fetch_object($fileResult)) {
                                                ?>
                                                <tr>
                                                    <td><?php echo++$sl; ?></td>
                                                    <td><?php echo $objInfo->ATTACH_TITTLE; ?></td>
                                                    <td align='center'><a href='<?php echo $objInfo->ATTACH_FILE_PATH; ?>'>view</a></td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </td>
                                <td></td>
                            </tr>
                        </table>
                    </fieldset>
               
            <br />
            <br />
            
                    <form method="post" enctype="multipart/form-data" id="fm" novalidate autocomplete="off">
                        <table class="table">
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
                                
                                <td>PAYMENT DATE :</td>
                                <td><input type='text' name='payment_date' class='easyui-datebox' data-options="formatter:myformatter,parser:myparser" size="20"></td>
                            </tr>
                            <tr class='fitem'>
                                
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
                </div>
            </div>          
        </div>  
    </div>  
</div>