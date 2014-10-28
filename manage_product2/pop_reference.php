<?php
include '../lib/DbManager.php';


$supplierids = getParam('supplierids');
$supplierids_list = explode(",", $supplierids);
$save = getParam('save');
$supplierid = getParam('supplierid');
$comparisonid = getParam('comparisonid');
$reference = getParam('reference');
if (isset($save)) {
    //$compersionid = findValue("select max(comparisonid) as comparisonid from price_comparison");
    foreach ($supplierid as $key => $value) {
        sql("insert into comparative_referance (comparison_id, supplier_id, reference)
            values($comparisonid, $value, '$reference[$key]')");
    }
    echo "<script type='text/javascript'>window.opener.parent.location.reload();</script>";
    echo "<script type='text/javascript'>window.close();</script>";
}
?>

<form method="post">
    <fieldset>
        <legend>Quotation Reference No </legend>
        <table width="100%" border="0" id="hor-minimalist-b">
            <tr>
                <th width="5%">S.L</th>
                <th width="25%">Supplier Name</th>
                <th width="25%">Reference No</th>
            </tr>
            <?php
            foreach ($supplierids_list as $key => $val) {
                $sl++;
                ?>
                <tr>
                    <td class="sn"><?php echo $sl; ?></td>
                    <td>
                        <?php echo findValue("SELECT s.SUPPLIER_NAME, s.SUPPLIER_ID
                    FROM supplier_price sup
                    left join supplier s on sup.SUPPLIER_ID=s.SUPPLIER_ID
                    where sup.SUPPLIER_ID='$val'"); ?></td>

                <input type="hidden" name="supplierid[]" value="<?php echo $supplierids_list[$key]; ?>" />
                <td>
                    <?php
                    $reference = findValue("select reference from comparative_referance where comparison_id='$comparisonid' and supplier_id='$val'");
                    ?>
                    <textarea name="reference[]"><?php echo $reference; ?></textarea></td>
                </tr>
                <?php
            }
            ?>   
            <input type="hidden" name="compersionid" value="<?php echo getParam('compersionid'); ?>" />         
            <tr>
                <td colspan="3"><input type="submit" name="save" class="button" value="Submit"></td>
            </tr>
        </table>                
    </fieldset>
</form>

<?php include '../body/footer.php'; ?>

