<?php
include_once '../lib/DbManager.php';


$searchId = getParam('searchId');
$mode = getParam('mode');
$module = 'VAT';

if (isSave()) {

    $targetFolder = '../documents/memo_archive/';
    $cb_no = getParam('cb_no');
    $transaction_date = getParam('transaction_date');
    $account_head = getParam('account_head');
    $sol = getParam('sol');
    $bill_amount = getParam('bill_amount');
    $vat_amount = getParam('vat_amount');
    $mushak_amount = getParam('mushak_amount');
    $remarks = mysql_real_escape_string(getParam('remarks'));

    if ($mode == 'edit' && $searchId != '') {
        $sqlUpdate = "UPDATE vat_11 SET
            cb_no='$cb_no',
            transaction_date='$transaction_date',
            account_head='$account_head',
            sol='$sol',
            bill_amount='$bill_amount',
            vat_amount='$vat_amount',
            mushak_amount='$mushak_amount',
            remarks='$remarks'
            WHERE vat_11_id='$searchId'";
        sql($sqlUpdate);
        file_upload_save($targetFolder, $searchId, $module);
    } elseif ($mode == '' && $searchId == '') {

        $insSQL = "INSERT INTO vat_11 (cb_no, transaction_date, account_head, sol, bill_amount, vat_amount, mushak_amount, remarks) 
        VALUES ('$cb_no', '$transaction_date', '$account_head', '$sol', '$bill_amount', '$vat_amount', '$mushak_amount', '$remarks' )";

        query($insSQL);
        $searchId=  insert_id();
        file_upload_save($targetFolder, $searchId, $module);
    }

    echo "<script>location.replace('vat_11_list.php');</script>";
}

if ($mode == 'delete' && $searchId != '') {
    $sqlDelete = "DELETE FROM vat_11 WHERE vat_11_id='$searchId'";
    sql($sqlDelete);
    echo "<script>location.replace('vat_11_list.php');</script>";
}

$solList = rs2array(query("SELECT SOL_ID, SOL_CODE, SOL_NAME FROM sol ORDER BY SOL_NAME"));
$glAccount = rs2array(query("SELECT GL_ACCOUNT_ID, GL_ACCOUNT_ID, GL_ACCOUNT_NAME
FROM gl_account gl
ORDER BY GL_ACCOUNT_NAME"));

$var = find("SELECT transaction_date, account_head, bill_amount, vat_amount, mushak_amount, remarks, cb_no, sol
FROM vat_11
WHERE vat_11_id='$searchId'");


include '../body/header.php';
?>

<div class="panel-header">VAT-11</div>
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
                    <td><input type="text" name="bill_amount" value="<?php echo $var->bill_amount; ?>"></td>
                </tr>
                <tr>
                    <td>VAT Amount:</td>
                    <td><input type="text" name="vat_amount" value="<?php echo $var->vat_amount; ?>"></td>
                </tr>
                <tr>
                    <td>Mushak 11 Amount:</td>
                    <td><input type="text" name="mushak_amount" value="<?php echo $var->mushak_amount; ?>"></td>
                </tr>
                <tr>
                    <td>Remarks:</td>
                    <td><textarea placeholder="Enter remark here" name="remarks"><?php echo $var->remarks; ?></textarea></td>
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
    <a href="vat_11_list.php" class="button">View Vat-11 List</a>
</div>


<?php
include '../body/footer.php';
?>