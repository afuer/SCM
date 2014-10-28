<?php
include_once '../lib/DbManager.php';


$searchId = getParam('searchId');
$mode = getParam('mode');



$var = find("SELECT transaction_date, account_head, bill_amount, vat_amount, rebate_amount, 
remarks, cb_no, s.SOL_CODE, s.SOL_NAME,
gl.GL_ACCOUNT_ID, gl.GL_ACCOUNT_NAME

FROM vat_rebate vr
LEFT JOIN gl_account gl ON gl.GL_ACCOUNT_ID=vr.account_head
LEFT JOIN sol s ON s.SOL_ID=vr.sol
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

<div class="panel-header">VAT Rabate View</div>
<div style="padding: 20px 20px; background: white;">

    <table class="table" >
        <tr>
            <td width="200">CB No.:</td>
            <td><?php echo $var->cb_no; ?></td>
        </tr>
        <tr>
            <td>Transaction Date.:</td>
            <td><?php echo $var->transaction_date; ?></td>
        </tr>
        <tr>
            <td>Account Head:</td>
            <td><?php echo $var->GL_ACCOUNT_NAME; ?></td>
        </tr>
        <tr>
            <td>SOL:</td>
            <td><?php echo $var->SOL_NAME; ?></td>
        </tr>
        <tr>
            <td>Bill Amount:</td>
            <td><?php echo $var->bill_amount; ?></td>
        </tr>

        <tr>
            <td>VAT Amount:</td>
            <td><?php echo $var->vat_amount; ?></td>
        </tr>
        <tr>
            <td>Rebate Amount:</td>
            <td><?php echo $var->rebate_amount; ?></td>
        </tr>
        <tr>
            <td>Remarks:</td>
            <td><?php echo $var->remarks; ?></td>
        </tr>
    </table>
    <?php file_upload_view($searchId, "VAT_rebate"); ?>
<a href="vat_rebate_list.php" class="button">View Vat Rebate</a>
</div>


<?php
include '../body/footer.php';
?>