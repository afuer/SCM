<?php
include '../lib/DbManager.php';

include '../body/header.php';

$price_comparison = getParam('compersionid');
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
        $db->OpenDb();
        sql("INSERT INTO price_coparison_specification(comparisonid, `condition`, productid)
			values($price_comparison, '{$condition_name[$key]}', '$productid')");

        $conditionid = findValue("select max(conditionid) as conditionid from price_coparison_specification");

        $yy = 0;
        foreach ($supplierid as $key2 => $value) {

            sql("INSERT INTO price_comparison_specefication_details  
				(comparisonid, supplier_id, `conditionid`, `value`)
				values($price_comparison, '{$supplierid[$yy]}', $conditionid, '$description[$xx]')");
            $xx++;
            $yy++;
        }
    }
    echo "<script type='text/javascript'>window.opener.parent.location.reload()</script>";
    echo "<script type='text/javascript'>window.close()</script>";
}
?>


<div class="easyui-layout" style="width:950px; margin: auto; height:600px;">  
    <div data-options="region:'center'" Title='Add Specification' style="padding: 10px 10px; background-color:white; "> 

        <form method="post" name="frm_specification">
            <table width="100%">
                <?php
                if (!isset($next)) {
                    ?>
                    <tr>
                        <th width="150" style="font-family:Verdana, Arial, Helvetica, sans-serif; padding:2px;">Number of Specification </th>
                        <th width="150" align="left"><input type="text" name="con_num" value="" onchange="onChange($(this), 'condition');"/></th>
                    </tr>

                    <tr id="condition"></tr>
                    <tr>
                        <td colspan="2" >
                            <button type="submit" name="next" class="button"/><span class = "icon plus"></span>Next</button>
                        </td>
                    </tr>
                </table>
                <?php
            } else {
                ?>      
                <table width="100%" class="ui-state-default">
                    <thead>
                    <th width="20">S.L</th>
                    <th width="25%">Title</th>
                    <?php
                    foreach ($supplierids_list as $key => $val) {
                        ?>
                        <th width="160">
                            <?php echo findValue("SELECT s.SUPPLIER_NAME, s.SUPPLIER_ID
                                        from supplier_price sup
                                        left join supplier s on sup.SUPPLIER_ID=s.SUPPLIER_ID
                                        where sup.SUPPLIER_ID='$val' and sup.PRODUCT_ID='$productid'"); ?></th>

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
                            <td><?php echo $sl; ?></td>
                            <td><?php echo $title[$key]; ?>
                                <input type="hidden" name="condition_name[<?php echo $key; ?>]" value="<?php echo $title[$key]; ?>" />
                            </td>
                            <?php
                            foreach ($supplierids_list as $key => $val) {
                                ?>

                                <td><textarea name="description[]"></textarea></td>
                            <?php } ?>
                        </tr>
                    <?php } ?>
                </table>        
            <?php } ?>

            <button type="submit" name="save" class="button">Save</button>
        </form>
    </div>
</div>
<?php include '../body/footer.php'; ?>