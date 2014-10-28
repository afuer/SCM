<?php
include '../lib/DbManager.php';

$quantities2 = getParam('quantities2');
$chkproduct = getParam('chkproduct');
$chkpro = getParam('chkproduct');
$product_list = getParam('product_list');
$quantities2 = getParam('quantities2');
$cancel_poid = getParam('cancel_poid');

if (empty($product_list)) {
    $chkproduct2 = explode("~", $chkproduct);
    $product_list = implode(',', $chkproduct2);
} else {
    $chkproduct2 = explode(",", $product_list);
}
/*
  if (empty($quantities2)) {
  //$_SESSION['quantities2'] = $quantities2;
  foreach ($chkproduct00 as $key3 => $value3) {
  list($chkproduct[], $quantity_pre[]) = explode("~", $value3);
  }
  //$_SESSION['quantities2'] = implode("~", $quantity_pre);
  }
 */
$cmdcomp = getParam('cmdcomp');
$supplier_ids = getParam('supplier_ids');
$supplier_id_chk = getParam('supplier_id_chk');
if (isset($cmdcomp)) {
    foreach ($chkproduct2 as $key => $value) {
        foreach ($supplier_id_chk as $key2 => $value2) {
            //echo "select price from supplier_price where supplierid=$value2 and productid=$value <br>";

            $is_price = findValue("SELECT price FROM supplier_price WHERE SUPPLIER_ID='$value2' AND PRODUCT_ID='$value'");
            if (empty($is_price)) {
                //echo "insert into supplier_price (supplierid, productid, price, status) value ($value2, $value, '0', 1)";
                sql("insert into supplier_price (SUPPLIER_ID, PRODUCT_ID, price, status) value ($value2, $value, '0', 1)");
            }
        }
    }

    echo "<script type='text/javascript'>window.opener.parent.location.reload()</script>";
    echo "<script type='text/javascript'>window.close()</script>";
}


include("../body/header.php");
?>

<h2 style="color:#000066; "></h2><br />
<div class="easyui-layout" style="width:950px; margin: auto; height:600px;">  
    <div data-options="region:'center'" Title='Add Supplier for this Comparative Statement' style="padding: 10px 10px; background-color:white; "> 

        <form method="post" action="">

            <table class="easyui-datagrid">
                <thead>
                    <tr>
                        <th field="name1">SL</th>
                        <th field="name2">Chk</th>
                        <th field="name3">Supplier Name</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $q = query("Select SUPPLIER_ID, SUPPLIER_NAME FROM supplier");


                    $sl = 0;
                    $i = 0;
                    while ($row = fetch_object($q)) {
                        $is_insert = findValue("SELECT SUPPLIER_ID FROM supplier_price where SUPPLIER_ID='$row->SUPPLIER_ID' AND PRODUCT_ID IN($product_list)");
                        ?>
                        <tr>
                            <td><?php echo++$sl; ?></td>
                            <td width="20">
                                <input type="hidden" name="supplier_ids[<?php echo $i; ?>]" value="<?php echo $row->SUPPLIER_ID; ?>" />
                                <input type='checkbox' id="<?php echo $row->SUPPLIER_ID; ?>" name="supplier_id_chk[]" value="<?php echo $row->SUPPLIER_ID; ?>" <?php
                                if ($is_insert > 0) {
                                    echo "checked";
                                }
                                ?> />
                            </td>
                            <td><label for='<?php echo $row->SUPPLIER_ID; ?>' ><?php echo $row->SUPPLIER_NAME; ?></label></td>

                        </tr>
                        <?php
                    }
                    ?> 
                </tbody>
            </table>
            <input type="hidden" name="product_list" value="<?php echo $product_list; ?>" />
            <input type="hidden" name="chkproduct" value="<?php echo $chkproduct2; ?>" />
            <input type="hidden" name="quantities2" value="<?php echo $quantities2; ?>" />
            <input type="hidden" name="cancel_poid" value="<?php echo $cancel_poid; ?>" />

            <input type="submit" value="Update" name="cmdcomp" class="button"/>
        </form>
    </div>
</div>
<br/>

<?php include("../body/footer.php"); ?>