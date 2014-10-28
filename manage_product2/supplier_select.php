<?php
include '../lib/DbManager.php';
include '../body/header.php';

$compersionid = getParam('compersionid');
$productid = getParam('productid');
$save = getParam('save');

if (isset($save)) {
    $supplier_id = getParam('supplier_id');
    $quantity = getParam('quantity');
    $unite_price = getParam('unite_price');
    $selected = getParam('selected');

    foreach ($supplier_id as $key => $value) {
        if ($selected[$value] == "") {
            $selected[$value] = 0;
        }

        $sql = "Update price_comparison_details set
				unite_price =  '$unite_price[$key]',
			    quantity    =  '$quantity[$key]', 
				selected    =  '$selected[$value]'
			where comparison_id = '$compersionid' and productid=$productid and supplier_id=$value
		";
        sql($sql);
    }
    echo "<script type='text/javascript'>window.opener.parent.location.reload();</script>";
    echo "<script type='text/javascript'>window.close();</script>";
}
?>

<div class="easyui-layout" style="width:950px; margin: auto; height:600px;">  
    <div data-options="region:'center'" Title='Comparative Statement List' style="padding: 10px 10px; background-color:white; "> 

        <form name="frm_committee" method="post">
            <table width="100%" class="ui-state-default">
                <thead>
                    <tr>
                        <th width="30">SL.</th>
                        <th width="20%">Product Name </th>
                        <th width="20%">Supplier Name</th>
                        <th width="7%">Qty</th>
                        <th width="16%">Unite Price </th>
                        <th width="11%">Value</th>
                        <th width="19%">Selected </th>
                    </tr>
                </thead>
                <?php
                $query_s = query("SELECT *, p.PRODUCT_NAME, s.SUPPLIER_NAME, unite_price*quantity as total 

            FROM price_comparison_details pcd
            INNER JOIN product p ON p.PRODUCT_ID=pcd.productid
            INNER JOIN supplier s ON s.SUPPLIER_ID=pcd.supplier_id
            where comparison_id='$compersionid' and productid='$productid' 
            group by productid, pcd.supplier_id");

                while ($rec_s = fetch_object($query_s)) {
                    $sl++;
                    ?>
                    <tr>
                        <td><?php echo $sl . "."; ?></td>
                        <td>
                            <?php echo $rec_s->PRODUCT_NAME; ?></td>
                        <td>
                            <input type="hidden" name="supplier_id[]" size="10" value="<?php echo $rec_s->supplier_id; ?>">

                            <?php echo $rec_s->SUPPLIER_NAME; ?></td>

                        <td><input type="text" name="quantity[]" size="10" value="<?php echo $rec_s->quantity; ?>" readonly="true"/></td>
                        <td><input type="text" name="unite_price[]2" size="16" value="<?php echo $rec_s->unite_price; ?>" readonly="true" /></td>
                        <td><?php echo number_format($rec_s->total, 2, '.', ','); ?></td>
                    <input type="hidden" name="supplierid[]" value="<?php echo $supplierids_list[$key]; ?>" />
                    <td><input type="checkbox" <?php
                        if ($rec_s->selected == 1) {
                            echo "checked='checked'";
                        }
                        ?>  name="selected[<?php echo $rec_s->supplier_id; ?>]" value="1"></td>
                    </tr>
                    <?php
                }
                ?>   
                <input type="hidden" name="compersionid" value="<?php echo getParam('compersionid'); ?>" />         

            </table>  
            <input type="submit" name="save" class="button" value="Select Supplier">

            <input type="hidden" value="<?php echo $_REQUEST["productid"]; ?>" name="productid"/>
            <input type="hidden" value="<?php echo $_REQUEST["compersionid"]; ?>" name="compersionid"/>
            <input type="hidden" value="<?php echo $mode; ?>" name="mode"/>
        </form>
    </div>
</div>

<?php include("../body/footer.php"); ?>