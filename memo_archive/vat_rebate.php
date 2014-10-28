<?php
include_once '../lib/DbManager.php';


$searchId = getParam('searchId');
$mode = getParam('mode');
$module = 'VAT_rebate';

if (isSave()) {

    $targetFolder = '../documents/memo_archive/';
    $cb_no = getParam('cb_no');
    $transaction_date = getParam('transaction_date');
    $account_head = getParam('account_head');
    $sol = getParam('sol');
    $bill_amount = getParam('bill_amount');
    $vat_amount = getParam('vat_amount');
    $rebate_amount = getParam('rebate_amount');
    $remarks = mysql_real_escape_string(getParam('remarks'));



    if ($mode == 'edit' && $searchId != '') {
        $sqlUpdate = "UPDATE vat_rebate SET
            cb_no='$cb_no',
            transaction_date='$transaction_date',
            account_head='$account_head',
            sol='$sol',
            bill_amount='$bill_amount',
            vat_amount='$vat_amount',
            rebate_amount='$rebate_amount',
            remarks='$remarks'
            WHERE vat_rebate_id='$searchId'";
        sql($sqlUpdate);
        file_upload_save($targetFolder, $searchId, $module);
    } elseif ($mode == '' && $searchId == '') {

        $insSQL = "INSERT INTO vat_rebate (cb_no, transaction_date, account_head, sol,  bill_amount, vat_amount, rebate_amount, remarks) 
        VALUES ('$cb_no', '$transaction_date', '$account_head','$sol','$bill_amount', '$vat_amount', '$rebate_amount', '$remarks' )";

        query($insSQL);
        $searchId=  insert_id();
        file_upload_save($targetFolder, $searchId, $module);
    }

    echo "<script>location.replace('vat_rebate_list.php');</script>";
}

if ($mode == 'delete' && $searchId != '') {
    $sqlDelete = "DELETE FROM vat_rebate WHERE vat_rebate_id='$searchId'";
    sql($sqlDelete);
    echo "<script>location.replace('vat_rebate_list.php');</script>";
}

$glAccount = rs2array(query("SELECT GL_ACCOUNT_ID, CONCAT(GL_ACCOUNT_ID,'-',GL_ACCOUNT_NAME)AS gl, REBATE_PERCENTAGE
FROM gl_account gl
ORDER BY GL_ACCOUNT_NAME"));
$solList = rs2array(query("SELECT SOL_ID, SOL_CODE, SOL_NAME FROM sol ORDER BY SOL_NAME"));

$var = find("SELECT transaction_date, account_head, bill_amount, vat_amount, rebate_amount, remarks, cb_no, sol
FROM vat_rebate
WHERE vat_rebate_id='$searchId'");



include '../body/header.php';
?>
<title>VAT Rebate | Memo Archive</title>
<script type="text/javascript">
    $(document).ready(function() {

        $('#vat_amount').change(function() {
            var vatAmount = $('#vat_amount').val();
            var glID = $('[name=account_head]').val();

            $.ajax({url: "rebate.php?id=" + glID,
                success: function(result) {
                    var rebateAmount = vatAmount * (result / 100);
                    $("#rebate_amount").val(rebateAmount);
                }});
        });


        $('#gl').combogrid({
            panelWidth: 400,
            required: true,
            url: 'gl_comboccinfo.php',
            idField: 'GL_ACCOUNT_ID',
            textField: 'GL_ACCOUNT_NAME',
            mode: 'remote',
            fitColumns: true,
            columns: [[
                    {field: 'GL_ACCOUNT_ID', title: 'GL Code', width: 30},
                    {field: 'GL_ACCOUNT_NAME', title: 'GL Name', width: 50},
                    {field: 'REBATE_PERCENTAGE', title: 'Rebate Percentage', width: 20}
                ]]
        });
    });


</script>

<div class="panel-header">VAT Rabate</div>
<div style="padding: 20px 20px; background: white;">

    <fieldset>
        <legend>VAT-11 From</legend>
        <form method="POST" enctype="multipart/form-data">

            <table class="table" >
                <tr>
                    <td width="200">CB No.:</td>
                    <td><input type="text" name="cb_no" value="<?php echo $var->cb_no; ?>"></td>
                </tr>
                <tr>
                    <td>Transaction Date.:</td>
                    <td><input type="text" name="transaction_date" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" value="<?php echo $var->transaction_date; ?>"></td>
                </tr>
                <tr>
                    <td>Account Head:</td>
                    <td><?php comboBox('account_head', $glAccount, $var->account_head, TRUE); ?></td>
                </tr>
                <tr>
                    <td>SOL:</td>
                    <td><?php comboBox('sol', $solList, $var->sol, TRUE); ?></td>
                </tr>
                <tr>
                    <td>Bill Amount:</td>
                    <td><input type="text" name="bill_amount" id="bill_amount" value="<?php echo $var->bill_amount; ?>"></td>
                </tr>

                <tr>
                    <td>VAT Amount:</td>
                    <td><input type="text" name="vat_amount" id="vat_amount" value="<?php echo $var->vat_amount; ?>"></td>
                </tr>
                <tr>
                    <td>Rebate Amount:</td>
                    <td><input type="text" name="rebate_amount" id="rebate_amount" value="<?php echo $var->rebate_amount; ?>"></td>
                </tr>
                <tr>
                    <td>Remarks:</td>
                    <td><textarea placeholder="Enter remarks here" name="remarks"><?php echo $var->remarks; ?></textarea></td>
                </tr>
            </table>
            <?php
            if ($mode == 'edit') {
                file_upload_edit($searchId, $module, TRUE);
            } else {
                file_upload_html(TRUE);
            }
            ?>
            <input type="submit" name="save" class="button" value="Save"/>
        </form>   
    </fieldset>
    <a href="vat_rebate_list.php" class="button">View Vat Rebate</a>
</div>


<?php
include '../body/footer.php';
?>