<?php
include '../lib/DbManager.php';
include("../body/header.php");


$CheckProduct = getParam('CheckProduct');
$qty = getParam('qty');
$save_price = getParam('save_price');
$price = getParam('price');



if ($_POST) {


    //Update Supplier Price List
    if ($save_price == 'UpdatePrice') {


        foreach ($price as $SupplierId => $Array) {
            foreach ($Array as $ProductId => $price) {

                $is_price = findValue("SELECT PRICE from supplier_price where SUPPLIER_ID='$SupplierId' and PRODUCT_ID='$ProductId'");

                if (!empty($is_price)) {
                    $sql = "UPDATE supplier_price SET PRICE='$price' WHERE PRODUCT_ID='$ProductId' AND SUPPLIER_ID='$SupplierId'";
                } else {
                    $sql = "insert into supplier_price (SUPPLIER_ID, PRODUCT_ID, PRICE) value ('$SupplierId', '$ProductId', '$price')";
                }
                sql($sql);
            }
        }
    }


    //Create CS
    $cmdcomp = getParam('cmdcomp');
    if ($cmdcomp == "Create Comparative Sheet") {
        //include '../requisition/manager.php';
        //$manager = new WorkFlowManager();
        //$lineManager = $manager->GetLineManager($user_name);
        $sql_comp = "insert into price_comparison (createby, date, cancel_poid, status, USER_LEVEL_ID) 
            values ('$userName', CURDATE(), '$cancel_poid', 1, '$UserLevelId')";
        sql($sql_comp);
        $compersionid = insert_id();





        // insert price_comparison_pro_req_qty 
        foreach ($CheckProduct as $key => $Arrayvalue) {

            foreach ($Arrayvalue as $ReqId => $value) {
                $cs_qty = $qty[$value][$ReqId];
                //echo 'Product ' . $value . ' Req ' . $ReqId . ' ' . $cs_qty . '<br/>';
                //$CS_QTY=  findValue("SELECT cs_qty FROM price_comparison_pro_req_qty WHERE requisition_id='$ReqId' AND PRODUCT_ID='$value'");

                $sql_price_req_qty = "insert into price_comparison_pro_req_qty (price_comparison_id, requisition_id, product_id, cs_qty) 
                                values ('$compersionid', '$ReqId', '$value', '$cs_qty')";
                sql($sql_price_req_qty);
            }
        }



        $quantities2 = getParam('quantities2');
        $supplierid = getParam('supplierid');
        $req = getParam('req');
        $cs_qty = getParam('cs_qty');

        $position = 1;
        $sl = 1;
        foreach ($price as $SupllierId => $Arrayvalue) {

            foreach ($CheckProduct as $ProductId => $SupplierPrice) {


                if ($supplierid[$SupllierId]) {
                    //echo'Price: ' . $price[$SupllierId][$ProductId] . ' SupplierId: ' . $SupllierId . ' ProductId:' . $ProductId . ' ReqId:' . $req[$SupllierId][$ProductId] . ' Qty:' . $cs_qty[$SupllierId][$ProductId] . '<br/>';

                    $cs_q = $cs_qty[$SupllierId][$ProductId];
                    $SupPrice = $price[$SupllierId][$ProductId];
                    $cs = findValue("SELECT CS_QTY FROM requisition_details WHERE PRODUCT_ID='$ProductId' AND REQUISITION_ID='$ReqId'");

                    $total_CS = $cs + $cs_q;
                    $update_sales_order_item = "UPDATE requisition_details SET 
                        STATUS_APP_LEVEL=1, 
                        DETAILS_STATUS=3,
                        CS_QTY='$total_CS'
                        WHERE PRODUCT_ID='$ProductId' AND REQUISITION_ID='$ReqId'";
                    sql($update_sales_order_item);
                    //echo "<br/>";
                    $sql = "insert into price_comparison_details (comparison_id, supplier_id, position, productid, unite_price, quantity,sl)
                    values('$compersionid', '$SupllierId', '$position', '$ProductId', '$SupPrice', '$cs_q','$sl')";
		if($SupPrice>0){
                    sql($sql);
			}
                    //echo "<br/>";
                }
            }
            $sl++;
        }

        $comparative_code = OrderNo($compersionid);
        //echo "UPDATE price_comparison SET comparative_code='$comparative_code' WHERE comparisonid='$compersionid'";
        sql("UPDATE price_comparison SET comparative_code='$comparative_code' WHERE comparisonid='$compersionid'");
        echo "<script>location.replace('comparative_supplier2.php?compersionid=$compersionid');</script>";
    }
}
?>   

<div class="easyui-layout" style="width:1100px; margin: auto; height:500px;">  
    <div data-options="region:'center'" Title='Add Supplier for this Comparative Statement' style="padding: 10px 10px; background-color:white; "> 

        <form method="post" action="">

            <table class="ui-state-default">
                <thead>
                <th width='20'>Ser</th>
                <th width='20'></th>
                <th align="center">Supplier Name</th>
                <?php
                foreach ($CheckProduct as $key => $Arrayvalue) {


                    $product_list .= $key . ',';

                    $ProductInfo = find("SELECT PRODUCT_NAME FROM product WHERE PRODUCT_ID='$key'");
                    ?>
                    <th align="center"><?php echo $ProductInfo->PRODUCT_NAME; ?></th>
                    <?php
                }
                $product_list = substr($product_list, 0, -1);
                ?>
                </thead>
                <?php
                $sql = "SELECT supp.SUPPLIER_ID, sprice.PRICE
                FROM supplier supp 
                LEFT JOIN supplier_price sprice on sprice.SUPPLIER_ID = supp.SUPPLIER_ID
                where sprice.PRODUCT_ID in ($product_list) 
                group by sprice.SUPPLIER_ID ORDER BY sprice.SUPPLIER_ID";


                $SqlResult = query($sql);

                while ($row = fetch_object($SqlResult)) {
                    $ser++;
                    $SupplierInfo = find("Select SUPPLIER_NAME from supplier where SUPPLIER_ID = '$row->SUPPLIER_ID'");
                    ?>
                    <tr>
                        <td><?php echo $ser; ?></td>
                        <td>
                            <input type='checkbox' value='<?php echo $row->SUPPLIER_ID; ?>' checked="checked" name='supplierid[<?php echo $row->SUPPLIER_ID; ?>]' /></td>
                        <td align='left'><?php echo $SupplierInfo->SUPPLIER_NAME; ?></td>
                        <?php
                        foreach ($CheckProduct as $key => $Arrayvalue) {

                            $price = findValue("select price from supplier_price where PRODUCT_ID='$key' and SUPPLIER_ID='$row->SUPPLIER_ID'");
                            ?>
                            <td>
                                <input type="text" name="price[<?php echo $row->SUPPLIER_ID; ?>][<?php echo $key; ?>]" value="<?php echo $price; ?>" />
                            </td>
                            <?php
                            foreach ($Arrayvalue as $ReqId => $value) {
                                $cs_qty = $qty[$value][$ReqId];
                                //echo 'Product ' . $value . ' Req ' . $ReqId . ' ' . $qty[$value][$ReqId] . '<br/>';


                                echo "<input type='hidden' name='CheckProduct[$value][$ReqId]' value='$value'/>";
                                echo "<input type='hidden' name='qty[$value][$ReqId]' value='$cs_qty' />";
                                echo "<input type='hidden' name='req[$row->SUPPLIER_ID][$key]' value='$ReqId'/>";
                                echo "<input type='hidden' name='cs_qty[$row->SUPPLIER_ID][$key]' value='$cs_qty'/>";
                            }
                            echo "<input type='hidden' name='supplier[$value]' value='$row->SUPPLIER_ID' />";
                        }
                        ?>

                    </tr>
                    <?php
                }
                ?> 
            </table>

            <input type="submit" name="btndelete" value="Delete" />
            <a class="button" href='../supplier/index.php' target="_blank">Add Supplier</a>
            <a class="button" href="existing_supplierslist.php?chkproduct=<?php echo $product_list; ?>&quantities2=<?php echo $quantities2; ?>" target="_blank">Existing Supplier</a>
            <button class="button" type="submit" name="save_price" value="UpdatePrice">Update Price</button>



            <?php
            if (empty($save_price)) {
                $button_value = "Pls Update Supplier Price first";
            } else {
                $button_value = "Create Comparative Sheet";
            }
            ?>
            <input type="submit" value="<?php echo $button_value; ?>" name="cmdcomp" <?php
            if (empty($save_price)) {
                echo "disabled";
            }
            ?>/>

            <input type="hidden" name="chkproduct" value="<?php echo $chkproduct; ?>" />
            <input type="hidden" name="quantities2" value="<?php echo $quantities2; ?>" />
            <input type="hidden" name="cancel_poid" value="<?php echo $cancel_poid; ?>" />
            <input type="hidden" name="orderids" value="<?php echo $orderids; ?>" />
            <input type="hidden" name="compersionid" value="<?php echo $compersionid; ?>" />


        </form>
    </div>
</div>

<?php include("../body/footer.php"); ?>
