<?php
include '../lib/DbManager.php';

$CheckProduct = getParam('CheckProduct');
$chkproduct2 = getParam('chkproduct2');
$quantities2 = getParam('quantities2');

$cancel_poid = getParam('cancel_poid');
$orderids = getParam('orderids');
$compersionid = getParam('compersionid');


// Update Supplier Price Product Wise
$update_price = getParam('save_price');
$supplierid = getParam('supplierid');


if (isset($update_price)) {

    foreach ($supplierid as $key => $value) {
        $ProductProce = getParam('price_' . $key);
        //print_r($ProductProce);
        foreach ($ProductProce as $ProductKey => $value) {
            $is_price = findValue("SELECT PRICE from supplier_price where SUPPLIER_ID='$key' and PRODUCT_ID='$ProductKey'");

            if (!empty($is_price)) {
                echo $sql = "Update supplier_price set price ='$ProductProce[$ProductKey]' where SUPPLIER_ID='$key' and PRODUCT_ID='$ProductKey'";
            } else {
                echo $sql = "insert into supplier_price (SUPPLIER_ID, PRODUCT_ID, PRICE) value ('$key', '$ProductKey', '$ProductProce[$ProductKey]')";
            }
            sql($sql);
        }
    }
    
}



// Create CS
$cmdcomp = getParam('cmdcomp');
if ($cmdcomp == "Create Comparative Sheet") {
    $sql_comp = "insert into price_comparison (createby, date, cancel_poid, status, location) values ('$userName', 'NOW()', '$cancel_poid', 1, 5)";
    sql($sql_comp);
    $compersionid = insert_id();

    $orderid_p = "";
    $quantities2 = getParam('quantities2');


    foreach ($CheckProduct as $key => $val) {
        $sql = "SELECT si.REQUISITION_ID 
                from requisition_details si
                left join product pro on pro.PRODUCT_ID= si.PRODUCT_ID
                left join requisition so on so.REQUISITION_ID= si.REQUISITION_ID 
                WHERE si.PRODUCT_ID = '$key' and si.DETAILS_STATUS=1 
                AND si.STATUS_APP_LEVEL = -1 AND so.REQUISITION_TYPE_ID=2
                GROUP BY si.PRODUCT_ID, si.REQUISITION_ID";

        $sql_ord = query($sql);
        while ($rec_ord = fetch_object($sql_ord)) {

            if ($orderid_p != $rec_ord->REQUISITION_ID) {
                $orderid[] = $rec_ord->REQUISITION_ID;
                $orderid_p = $rec_ord->REQUISITION_ID;
            }
            $update_sales_order_item = "UPDATE requisition_details SET STATUS_APP_LEVEL=1, DETAILS_STATUS=3 WHERE PRODUCT_ID='$key' AND REQUISITION_ID='$rec_ord->REQUISITION_ID' AND STATUS_APP_LEVEL=-1";
            sql($update_sales_order_item);
                    }

        $supplierid = getParam('supplierid');
        $position = 1;
        foreach ($supplierid as $Suplierkey => $value) {
            $ProductPrice = getParam('price_' . $Suplierkey);
            foreach ($ProductPrice as $ProductKey => $value) {
                $sql = "insert into price_comparison_details (comparison_id, supplier_id, position, productid, unite_price, quantity)
                    values('$compersionid', '$Suplierkey', '$position', '$ProductKey', '$value', '$val')";
                sql($sql);
                $position++;
            }
        }
    }

    //die();

    if (!empty($cancel_poid)) {
        $orderids_list = findValue("select orderids from purchaseorder where poid ='$cancel_poid'");
    } else {
        $orderids_list = array_unique($orderid);
        $orderids_list = implode(",", $orderids_list);
    }


    $comparison_orderid = findValue("select orderid from price_comparison where comparisonid='$compersionid'");
    $sql_com = "update price_comparison set orderid='$orderids_list' where comparisonid='$compersionid'";
    if ($comparison_orderid < 1) {
        sql($sql_com);
    }

    $group = getParam('group');
    echo "<script>location.replace('comparative_supplier2.php?compersionid=$compersionid&group=$group');</script>";
}





$btndelete = getParam('btndelete');
if (isset($btndelete)) {
    foreach ($chkproduct as $key => $val) {
        $supplierids = getParam('supplierid');
        foreach ($supplierids as $key2 => $value2) {
            list($supplierid, $price, $quantity) = explode("-", $value2);
            sql("delete from supplier_price where supplierid=$supplierid and productid in ($product_list)");
        }
    }
}


include("../body/header.php");
?>   

<h2 style="color:#000066; ">Add Supplier for this Comparative Statement</h2><br />



<form method="post" action="">

    <table class="ui-state-default">
        <thead>
        <th width='20'>Ser</th>
        <th width='20'></th>
        <th align="center">Supplier Name</th>
        <?php
        $number_product = count($chkproduct);
        $pro_num = 0;
        foreach ($CheckProduct as $key => $value) {
            $product_list = $key . ',';
            $ProductInfo = find("SELECT PRODUCT_NAME FROM product WHERE PRODUCT_ID='$key'");
            ?>
            <th align="center"><?php echo $ProductInfo->PRODUCT_NAME; ?></th>
            <?php
        }
        $product_list = substr($product_list, 0, -1);
        ?>
        </thead>
        <?php
        //$product_list = implode(',', $CheckProduct);

        $sql = "SELECT supp.SUPPLIER_ID, sprice.PRICE
            FROM supplier supp 
            LEFT JOIN supplier_price sprice on sprice.SUPPLIER_ID = supp.SUPPLIER_ID
            where sprice.PRODUCT_ID in ($product_list) 
            group by sprice.SUPPLIER_ID ORDER BY sprice.SUPPLIER_ID";


        $SqlResult = query($sql);




        $i = 0;
        $x = 0;



        while ($row = fetch_object($SqlResult)) {
            $ser++;
            $SupplierInfo = find("Select SUPPLIER_NAME from supplier where SUPPLIER_ID = '$row->SUPPLIER_ID'");
            ?>
            <tr>
                <td><?php echo $ser; ?></td>
                <td>
                    <input type='checkbox' value='<?php echo $row->SUPPLIER_ID; ?>' id='<?php echo $row->SUPPLIER_ID; ?>' checked="checked" name='supplierid[<?php echo $row->SUPPLIER_ID; ?>]' />
                </td>
                <td align='left'><label for='<?php echo $row->SUPPLIER_ID; ?>' ><?php echo $SupplierInfo->SUPPLIER_NAME; ?></label></td>
                <?php
                foreach ($CheckProduct as $key => $value) {

                    $price = findValue("select price from supplier_price where PRODUCT_ID='$key' and SUPPLIER_ID='$row->SUPPLIER_ID'");
                    ?>
                    <td>
                        <input type="text" name="price_<?php echo $row->SUPPLIER_ID; ?>[<?php echo $key; ?>]" value="<?php echo $price; ?>" />
                    </td>
                    <?php
                    $x++;
                }
                ?>
            </tr>
            <?php
            $i++;
        }
        ?> 
    </table>

    <table>
        <tr>
            <td><input type="submit" name="btndelete" value="Delete" /></td>
            <td><input type=button value='New Supplier' name='new2' onclick="window.location.href = 'supplier.php?chkproduct=<?php echo $chkproduct2; ?>'"/></td>
            <td><a class="button" href="existing_supplierslist.php?chkproduct=<?php echo $product_list; ?>&quantities2=<?php echo $quantities2; ?>" target="_blank">Existing Supplier</a></td>
            <td><input type="submit" name="save_price" value="Update Price" /></td>
            <?php
            if (!empty($update_price)) {
                ?>          <td>

                    <input type="checkbox" name="group" value="1" />
                </td>
                <td>Group Entry </td>  <?php } ?>
            <td>
                <?php
                if (empty($update_price)) {
                    $button_value = "Pls Update Supplier Price first";
                } else {
                    $button_value = "Create Comparative Sheet";
                }
                ?>
                <input type="submit" value="<?php echo $button_value; ?>" name="cmdcomp" <?php
                if (empty($update_price)) {
                    echo "disabled";
                }
                ?>/></td>
        </tr>
    </table>
    <input type="hidden" name="chkproduct" value="<?php echo $chkproduct; ?>" />
    <input type="hidden" name="quantities2" value="<?php echo $quantities2; ?>" />
    <input type="hidden" name="cancel_poid" value="<?php echo $cancel_poid; ?>" />
    <input type="hidden" name="orderids" value="<?php echo $orderids; ?>" />
    <input type="hidden" name="compersionid" value="<?php echo $compersionid; ?>" />


</form>

<?php include("../body/footer.php"); ?>