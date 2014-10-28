<?php
include '../lib/DbManager.php';
include '../body/header.php';
//die();

$compersionid = getParam('compersionid');
$new = getParam('new');
$group = getParam('group');
$cmdapproval = getParam('cmdapproval');
$recommendation = getParam('recommendation');
$position = getParam('position');

$product_list = getParam('product_list');
$supplier_list2 = getParam('supplier_list2');



sql("update price_comparison set recommendation ='$recommendation', comparative_code = '$comparative_code', group_item='$group' where comparisonid='$compersionid'");


if ($cmdapproval == "Update CS Info") {

    $position = getParam('position');
    $supplier_ids = explode(",", $supplier_list2);
    //print_r($supplier_ids);

    foreach ($supplier_ids as $key => $value) {
        $sql = "update price_comparison_details set position ='$position[$key]' where comparison_id='$compersionid' and supplier_id='$value'";
        sql($sql);
    }
    //echo "= = ";
    echo "<script>location.replace('evaluation_statement.php?comparison_id=$compersionid');</script>";
}
?>

<script type="text/javascript" src="js/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="js/ckeditor/adapters/jquery.js"></script>
<script type="text/javascript" src="js/ckeditor/config.js"></script>


<div class="easyui-layout" style="width:1100px; margin: auto; height:1500px;">  
    <div data-options="region:'center'" Title='Comparative Statement have been created!' style="padding: 10px 10px; background-color:white; "> 
        <form action="comparative_supplier2.php?product_list=<?php echo $product_list; ?>&new= <?php echo $new; ?>&compersionid=<?php echo $compersionid; ?>" name="frm_comparative" id="frm_comparative" method="post">

            <div style="padding-bottom: 5px; text-align: center;"><b>Comparative ID: </b> <?php echo po_no($compersionid); ?><br/>
                <b>Date:</b><?php echo date("D, d-M-Y"); ?>
            </div>

            <?php
            $sql_pro = query("SELECT productid FROM price_comparison_details where comparison_id='$compersionid' group by productid");
            while ($rec_pro = fetch_object($sql_pro)) {
                $sn = 0;
                $productid = $rec_pro->productid;
                ?>
                <input type="hidden" name="productid" value="<?php echo $rec_pro->productid; ?>" />
                <div style="padding-bottom: 10px;">
                    Product Name: <b><?php echo findValue("select PRODUCT_NAME from product where PRODUCT_ID='$rec_pro->productid'"); ?> </b>
                </div>

                <table class="ui-state-default" width="100%">
                    <thead>
                    <th width="30">SL.</th>
                    <th>Supplier</th>
                    <th>Position</th>
                    <th width='150' align="right">Unit Price</th>
                    <th width='100' align="center">Qty</th>
                    <th width='150' align="right">Total Price</th>
                    </thead>
                    <?php
                    $sql = "SELECT comparison_id,
                    pcd.supplier_id, productid,
                    unite_price,
                    position, selected,
                    s.SUPPLIER_NAME,
                    (
                        SELECT SUM(cs_qty) 
                        FROM price_comparison_pro_req_qty 
                        WHERE product_id='$rec_pro->productid' AND price_comparison_id='$compersionid'
                    ) AS 'quantity'

                    FROM price_comparison_details pcd
                    INNER JOIN supplier s ON s.SUPPLIER_ID=pcd.supplier_id
                    where comparison_id='$compersionid' and productid='$rec_pro->productid'";
                    $sql_pro_det = query($sql);
                    while ($pro_det = fetch_object($sql_pro_det)) {
                        $supplier_list[] = $pro_det->supplier_id;
                        $sn++;
                        ?>
                        <tr>
                            <td><?php echo $sn; ?></td>
                            <td><?php echo $pro_det->SUPPLIER_NAME; ?><input name='txtsuppid[]' value='<?php echo $suppliesarray[$i]; ?>' type='hidden'/></td>
									 <td><select name='position[]' id="position[]" style="width=100px;">
                                <option selected="selected"></option>
                                <?php
                                for ($x = 1; $x <= 30; $x++) {
                                    ?>
                                    <option value='<?php echo $x; ?>'<?php
                                    if ($sup_position == $x) {
                                        echo "selected";
                                    }
                                    ?>><?php echo $x; ?></option>
                                        <?php } ?>
                            </select></td>                            
                            <td align='right'><?php echo $pro_det->unite_price; ?><input name='txtunitprice[]' value='<?php echo $pro_det->unite_price; ?>' type='hidden'/></td>
                            <td align='center'><?php echo $pro_det->quantity; ?><input name='txtqty[]' value='<?php echo $pro_det->quantity; ?>' type='hidden' size='15' style='text-align:right'/></td>
                            <td align='right'><?php echo number_format($pro_det->unite_price * $pro_det->quantity, 2, '.', ','); ?><input name='txttotalprice[]' value='<?php echo $total_price; ?>' type='hidden'/></td>
                        </tr>
                        <?php
                    }
                    $supplier_list2 = implode(',', array_unique($supplier_list));

                    //print_r($supplier_list2);
                    ?>
                </table>

                <?php
                if ($group != 1) {
                    ?>
                    <a href="pop_specification.php?supplierids=<?php echo $supplier_list2; ?>&productid=<?php echo $productid; ?>&amp;count=<?php echo $count; ?>&compersionid=<?php echo $compersionid; ?>" class="button" target="_blank">Add Product Specification</a>
                    <a href="pop_service.php?supplierids=<?php echo $supplier_list2; ?>&productid=<?php echo $productid; ?>&count=<?php echo $count; ?>&compersionid=<?php echo $compersionid; ?>" class="button" target="_blank">Add Terms &amp; Condition</a>    


                    <?php
                } else {
                    ?>  
                    <a href="pop_specification.php?supplierids=<?php echo $supplier_list2; ?>&amp;count=<?php echo $count; ?>&compersionid=<?php echo $compersionid; ?>" class="button" target="_blank">Add Product Specification</a>
                    <a href="pop_service.php?supplierids=<?php echo $supplier_list2; ?>&count=<?php echo $count; ?>&compersionid=<?php echo $compersionid; ?>" class="button" target="_blank">Add Terms &amp; Condition</a>
                    <?php
                }
            }
            ?>

            <br/> <br/>
            

            <input name="button2" type="button" onclick="window.open('pop_reference.php?supplierids=<?php echo $supplier_list2; ?>&comparisonid=<?php echo $compersionid; ?>', 'popup', 'width=700,height=500,scrollbars=no,scrollbars=yes,toolbar=no,directories=no,location=no,menubar=no,status=no,left=300,top=110');
                    return false" value="Add Reference No" />  <input name="button4" type="button" onclick="window.open('evaluation_committee.php?productid=<?php echo $_REQUEST['productid']; ?>&quantity=<?php echo $total_qty; ?>&compersionid=<?php echo $compersionid; ?>', 'popup', 'width=700,height=500,scrollbars=no,scrollbars=yes,toolbar=no,directories=no,location=no,menubar=no,status=no,left=300,top=110');
                    return false" value="Add Committee Info" /><br /><br />

            <b>Comments:</b>
            <textarea name="recommendation" cols="70" rows="5"></textarea>
            <input type='hidden' name='productid' value='<?php echo $_REQUEST['productid']; ?>' />
            <input type='hidden' name='txtproductid' value='<?php echo $_REQUEST['productid']; ?>' />
            <input type='hidden' name='txttotalcount' value='<?php echo $count; ?>' />
            <input type='hidden' name='txttotalqty' value='<?php echo $total_qty; ?>' />
            <input type='hidden' name='supplier_list2' value='<?php echo $supplier_list2; ?>' />	
            <input type='hidden' name='group' value='<?php echo $group; ?>' />	
            <br/>
            <input type="submit" value="Update CS Info" name="cmdapproval" onclick="return possition_check();" />
        </form>
    </div>
</div>


<?php include("../body/footer.php"); ?>