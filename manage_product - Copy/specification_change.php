<?php
include '../lib/DbManager.php';
include '../body/header.php';

$comparisonid = getParam('comparisonid');

if (isset($_POST['save'])) {
    $conditionids = $_POST['conditionids'];
    $supplierids_list2 = $_POST['supplierids_list'];
    $description = $_POST['description'];
    $title = $_POST['title'];
    $number = 0;
    foreach ($conditionids as $key2 => $value2) {
        foreach ($supplierids_list2 as $key2 => $val2) {
            mysql_query("update price_coparison_specification pr_sp 
				left join price_comparison_specefication_details pr_det on pr_sp.conditionid=pr_det.conditionid
				set 
				pr_sp.`condition`='$title[$number]',
				pr_det.`value`='$description[$number]'
				where pr_sp.comparisonid=$comparisonid and pr_sp.conditionid=$value2 and pr_det.supplier_id=$val2");

            $number++;
        }
    }
    echo "<script type='text/javascript'>window.opener.parent.location.reload()</script>";
    echo "<script type='text/javascript'>window.close()</script>";
}
?>


<form method="post">
    <fieldset>
        <legend>Product Specification Change </legend>
        <table class="ui-state-default">
            <thead>
                <th width="5%" align="center">S.L</th>
                <th width="25%">Title</th>
                <?php
                $query_supp = query("select 
                s.SUPPLIER_NAME,
                com.supplier_id
                from price_comparison_details com
                left join supplier s on com.supplier_id=s.SUPPLIER_ID
                where comparison_id='$comparisonid'");
                $num_row = num_rows($query_supp);
                $width = 100 / $num_row;
                while ($rec_supp = fetch($query_supp)) {
                    $count++;
                    $supplierids_list[] = $rec_supp->supplier_id;
                    ?>
                    <th width="160">
                        <input type="hidden" name="supplierids_list[]" value="<?php echo $rec_supp->supplier_id; ?>" />
                        <?php echo $rec_supp->SUPPLIER_NAME; ?></th>
                <?php } ?>


            </thead>
            <?php
            $query_specification = query("SELECT `condition`, conditionid FROM `price_coparison_specification` where comparisonid='$comparisonid'");
            while ($rec_spec = fetch_object($query_specification)) {
                $sl++;
                ?>
                <tr>
                    <th align="center"><?php echo $sl . "."; ?></th>
                    <th><?php echo $rec_spec->condition; ?>
                        <input type="hidden" name="conditionids[]" value="<?php echo $rec_spec->conditionid; ?>" />
                    </th>
                    <?php
                    foreach ($supplierids_list as $key => $val) {
                        $value = findValue("select value from price_comparison_specefication_details where comparisonid='$comparisonid' and supplier_id='$val' and conditionid='$rec_spec->conditionid'");
                        ?>
                        <th>
                            <input type="hidden" name="title[]" value="<?php echo $rec_spec->condition; ?>" />
                            <textarea name="description[]"><?php echo $value; ?></textarea></th>
                    <?php } ?>

                </tr>
            <?php } ?>
        </table> 
        <button type="submit" class="button" name="save" >Save</button>
    </fieldset>

</form>
<br/>

