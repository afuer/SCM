<?php
include '../lib/DbManager.php';
include '../body/header.php';


$compersionid = getParam('compersionid');
$productid = getParam('productid');
$supplierids = getParam('supplierids');
$supplierids_list = explode(",", $supplierids);
$title = getParam('title');
$next = getParam('next');
$save = getParam('save');
$supplierid = getParam('supplierid');
$condition_name = getParam('condition_name');
$description = getParam('description');

if (isset($save)) {
    $xx = 0;
    foreach ($condition_name as $key => $val) {
        sql("INSERT INTO price_comparison_condition (comparisonid, `condition`, productid)
                        values('$compersionid', '$condition_name[$key]', '$productid')");

        $conditionid = insert_id();

        $yy = 0;
        foreach ($supplierid as $key2 => $value) {
            sql("INSERT INTO price_comparison_condition_details (comparisonid, supplier_id, `conditionid`, `value`)
				values('$compersionid', '{$supplierid[$yy]}', '$conditionid', '$description[$xx]')");
            $xx++;
            $yy++;
        }
    }
    echo "<script type='text/javascript'>window.opener.parent.location.reload();</script>";
    echo "<script type='text/javascript'>window.close();</script>";
}
?>

<form method="post">
    <fieldset>
        <legend>Product Terms and Conditions </legend>
        <?php
        if (!isset($next)) {
            ?>
            Number of Condition: <input type="text" name="con_num" value="" onchange="onChange($(this), 'condition');" />

            <div id="condition"></div>
            <button type="submit" name="next" class="button"/><span class = "icon plus"></span>Next</button>
            <?php
        } else {
            ?>      
            <table width="100%" class="ui-state-default">
                <thead>
                <th width="5%" align="center">S.L</th>
                <th width="25%">Title</th>
                <?php
                foreach ($supplierids_list as $key => $val) {
                    ?>
                    <th width="160"><?php echo findValue("SELECT s.SUPPLIER_NAME, s.SUPPLIER_ID 
                                        FROM supplier_price sup
                                        left join supplier s on sup.SUPPLIER_ID=s.SUPPLIER_ID
                                        where sup.SUPPLIER_ID='$val' and sup.PRODUCT_ID='$productid'"); ?>
                    </th>

                    <input type="hidden" name="supplierid[]" value="<?php echo $supplierids_list[$key]; ?>" />
                    <?php
                }
                ?>            
                </thead>
                <?php
                foreach ($title as $key => $val) {
                    $sl++;
                    ?>
                    <tr>
                        <td class="sn" align="center"><?php echo $sl . "."; ?></th>
                        <td><?php echo $title[$key]; ?>
                            <input type="hidden" name="condition_name[<?php echo $key; ?>]" value="<?php echo $title[$key]; ?>" />
                            </th>
                            <?php
                            foreach ($supplierids_list as $key => $val) {
                                ?>

                            <td><textarea name="description[]"><?php echo $value; ?></textarea></th>
                            <?php } ?>
                    </tr>
                <?php } ?>
            </table>  
            <input type="submit" name="save" class="button" value="Save"/>
        <?php } ?>

    </fieldset>
    <input type="hidden" name="compersionid" value="<?php echo $compersionid; ?>" />
</form>
<?php include '../body/footer.php'; ?>