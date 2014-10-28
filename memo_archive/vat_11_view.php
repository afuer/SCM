<?php
include_once '../lib/DbManager.php';


$searchId = getParam('searchId');
$mode = getParam('mode');
$module = 'VAT';



$var = find("SELECT transaction_date, account_head, bill_amount, vat_amount, 
    mushak_amount, remarks, cb_no, sol, s.SOL_NAME,   
    gl.GL_ACCOUNT_ID, gl.GL_ACCOUNT_NAME

FROM vat_11 vr
LEFT JOIN gl_account gl ON gl.GL_ACCOUNT_ID=vr.account_head
LEFT JOIN sol s ON s.SOL_ID=vr.sol
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
                    <td>Mushak 11 Amount:</td>
                    <td><?php echo $var->mushak_amount; ?></td>
                </tr>
                <tr>
                    <td>Remarks:</td>
                    <td><?php echo $var->remarks; ?></td>
                </tr>
            </table>
            <?php file_upload_view($searchId, $module); ?>
        </form>   
    </fieldset>
    <a href="vat_11_list.php" class="button">View Vat-11 List</a>
</div>


<?php
include '../body/footer.php';
?>