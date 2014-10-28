<?php
include_once '../lib/DbManager.php';
//checkPermission(14);
include('product.inc.php');

$productid = getParam('productid');

if (isSave()) {
    $productid = getParam('productid');
    $description = getParam('description');
    $reorder_qty = getParam('reorder_qty');
    $reorder_level = getParam('reorder_level');
    $daily_expense = getParam('daily_expense');
    $lead_time = getParam('lead_time');
    $product_type = getParam('product_type');

    $requisition_for = getParam('requisition_for');
    $requisition_routeid = getParam('requisition_routeid');
    $model = getParam('model');

    $product_group = getParam('product_group');

    $maincategoryid = getParam("maincategoryid");
    $categoryid = getParam("categoryid");
    $subcategoryid = getParam("subcategoryid");
    $unittype = prepNull(getParam('unittype'));
    $purchase_price = getParam('purchase_price');
    $at_actual = getParam('at_actual');

    if (isNew()) {

        if (isEmpty($productid)) {
            $productid = findValue("select max(lpad(productid, 32, ' ')) from product", 0);
            $productid++;
        }
        if ($productid < 1000)
            $productid += 1000;
        if (isEmpty($unittype)) {
            $unittype = findValue("select unittype from category
                                       where categoryid=$categoryid");
            $unittype = prepNull($unittype);
        }
        if (strlen($maincategoryid) < 2) {
            $maincategoryid_code = "0" . $maincategoryid;
        } else {
            $maincategoryid_code = $maincategoryid;
        }

        if (strlen($categoryid) < 3) {
            $zero_val = 3 - strlen($categoryid);
            $zero = "";
            for ($x = 1; $x <= $zero_val; $x++) {
                $zero .="0";
            }
            $categoryid_code = $zero . $categoryid;
        } else {
            $categoryid_code = $categoryid;
        }

        if (strlen($subcategoryid) < 2) {
            $subcategoryid_code = "0" . $subcategoryid;
        } else {
            $subcategoryid_code = $subcategoryid;
        }
        $item_code = $maincategoryid_code . $categoryid_code . $subcategoryid_code . $productid;

        $sql = "insert into product (productid, item_code, model, description, reorder_qty, product_type, maincategoryid, categoryid, subcategoryid, unittype,  requisition_routeid,purchase_price, requisition_for, at_actual, reorder_level, daily_expense, lead_time)
                    values ('$productid', '$item_code', '$model', '$description', '$reorder_qty', '$product_type', '$maincategoryid', '$categoryid', '$subcategoryid', $unittype,  '$requisition_routeid', '$purchase_price', '$requisition_for', '$at_actual', '$reorder_level',  '$daily_expense',  '$lead_time')";
        sql($sql);


        if (oscommerce()) {

            $sql = "insert into products (products_quantity, products_model, products_price, products_date_added, products_weight, products_status, products_tax_class_id ) 
            values (0, '$model', 0, from_unixtime(time()), 0, 1, 1)";
            sql($sql);
            $oscommerceid = insert_id();
            $languages_id = findValue("select min(languages_id) from languages");
            sql("insert into products_description (products_id, language_id, products_name, products_description)
				values ($oscommerceid, $languages_id, '$model', '$description')");
            $categories_id = findValue("select min(categories_id) from categories");
            sql("insert into products_to_categories (products_id, categories_id) 
                    values ($oscommerceid, $categories_id)");
            sql("update product set oscommerceid=$oscommerceid where productid='$productid'");
        }
    } else {

        $updateSQL = "update product set
                model='$model',
                description='$description',
                reorder_qty = '$reorder_qty',
                reorder_level = '$reorder_level',
                daily_expense = '$daily_expense',
                lead_time = '$lead_time',
                product_type = '$product_type',
                categoryid=$categoryid,
                purchase_price= $purchase_price,
                requisition_routeid=$requisition_routeid,
                unittype=$unittype,
                subcategoryid=$subcategoryid,
                product_group= $product_type,
                requisition_for=$requisition_for,
		at_actual  = '$at_actual'
                where productid='$productid'";
        sql($updateSQL);
    }
    $count = getParam("supplier_count");
    $i = 0;
    while ($i < $count) {
        $supplierid = getParam("supplierid_$i");
        $old_productcode = getParam("old_productcode_$i");
        $productcode = getParam("productcode_$i");
        if ($productcode != $old_productcode) {
            $productcode = prepNull($productcode);
            sql("
				update supplier_price set supplier_productcode='$productcode'
				where productid=$productid and supplierid=$supplierid");
        }
        $i++;
    }
    $supplierid = getParam("supplierid_new");
    if (!isEmpty($supplierid)) {
        $productcode_new = getParam("productcode_new");
        sql("
			insert into supplier_price (supplierid, productid, price, supplier_productcode)
			values ($supplierid, $productid, 0, '$productcode_new')");
    }
}
if (isDelete()) {
    deleteProduct($productid);
    $productid = null;
}


$selectSQL = "select p.PRODUCT_ID,
p.PRODUCT_TYPE_ID,
p.REQUISITION_FOR,
PRODUCT_NAME,
p.PRODUCT_CODE,
p.DESCRIPTION,
PURCHASE_PRICE,
p.MAINCATEGORY_ID,
p.CATEGORY_ID,
p.SUBCATEGORY_ID,
p.PRODUCT_GROUP_ID,
p.REORDER_QTY,
p.REORDER_LEVEL,
p.DAILY_EXPENSE,
p.LEAD_TIME,
STOCK,
REQUISITION_ROUTE_ID,
p.UNIT_TYPE_ID,
p.AT_ACTUAL
from product p
left outer join category c on c.CATEGORY_ID=p.CATEGORY_ID
where p.PRODUCT_ID='$productid'
GROUP BY p.PRODUCT_ID";
$rec = find($selectSQL);


$CategorieList = rs2array(query("SELECT CATEGORY_ID, DESCRIPTION FROM category ORDER BY DESCRIPTION ASC"));
$ProductBrandList = rs2array(query("SELECT Product_Brand_ID, Product_Brand_NAME FROM product_brand ORDER BY Product_Brand_NAME"));
$unittypes = rs2array(query("SELECT UNIT_TYPE_ID, UNIT_TYPE_NAME FROM unit_type ORDER BY UNIT_TYPE_NAME"));
$suppliers = rs2array(query("SELECT SUPPLIER_ID, SUPPLIER_NAME FROM supplier ORDER BY SUPPLIER_NAME"));
$requisition_routes = rs2array(query("SELECT REQUISITION_ROUTE_ID, ROUTE_NAME FROM requisition_route ORDER BY ROUTE_NAME"));
$suppliers = rs2array(query("SELECT SUPPLIER_ID, SUPPLIER_NAME FROM supplier  order by SUPPLIER_NAME ASC"));
$product_groups = rs2array(query("SELECT PRODUCT_GROUP_ID, PRODUCT_GROUP_NAME FROM product_group ORDER BY PRODUCT_GROUP_NAME"));
$MainCategoryList = rs2array(query("SELECT MAIN_CATEGORY_ID, MAIN_CATEGOTY_NAME FROM main_category ORDER BY MAIN_CATEGOTY_NAME"));

include("../body/header.php");
?>

<div Title='Add new Product/ Item' class="easyui-panel" style="width:1000px; height:700px;" >
    <a href="ProductsList.php" class="button"> Product/ Item List</a>	

    <form name='postform' id="postform" action="" method="POST">
        <table>
            <tr>
                <td>Item Code:</td>
                <td>
                    <?php
                    if ($new) {
                        numberbox('productid', '', '', '', '', '', 'readonly=1');
                        echo "&nbsp;(" . tr("Leave empty for auto generated") . ")";
                    } else {
                        echo $rec->PRODUCT_CODE;
                        echo "<input type='hidden' name='productid' value='$productid'/>";
                    }
                    ?>
                </td>
            <tr>
                <td>Item Name:</td>
                <td><?php textbox("model", $rec->PRODUCT_NAME, 50) ?></td>
            </tr>


            <tr>
                <td>Description:</td>
                <td><textarea rows=3 cols=70 name='description'><?php echo $rec->DESCRIPTION ?></textarea></td>
            </tr>
            <tr>
                <td>Main Category:</td>
                <td><?php comboBox('maincategoryid', $MainCategoryList, $rec->MAINCATEGORY_ID, TRUE, '', 'ajax_category'); ?></td>
            </tr>
            <tr>
                <td>Category:</td>
                <td id="ajax_category"><?php comboBox('categoryid', $CategorieList, $rec->CATEGORY_ID, TRUE, '', 'ajax_subcategory'); ?></tr>
            <tr>
                <td>Product Brand:</td>
                <td id="ajax_subcategory"><?php comboBox('categoryid', $ProductBrandList, $rec->SUBCATEGORY_ID, TRUE, '', 'ajax_subcategory'); ?></td>
            </tr>
            <tr>
                <td><?php etr("Requisition Route") ?>:</td>
                <td><?php comboBox("requisition_routeid", $requisition_routes, $rec->REQUISITION_ROUTE_ID, false) ?></td>
            </tr>

            <tr>
                <td>Unit:</td>
                <td><?php combobox('unittype', $unittypes, $rec->UNIT_TYPE_ID, false) ?></td>
            </tr>
            <tr>
                <td>Product Low Level:</td>
                <td><?php textbox("reorder_level", $rec->REORDER_LEVEL, 22) ?></td>
            </tr>
            <tr>
                <td>Daily Expense(Avg):</td>
                <td><input  value="<?php echo $rec->DAILY_EXPENSE; ?>" name="daily_expense" size="28" onKeyPress='return numbersonly(event, false)'  />(Qty) </td>
            </tr>
            <tr>
                <td>Delivery Lead Time(Avg):</td>
                <td><input  value="<?php echo $rec->LEAD_TIME; ?>" name="lead_time" size="28" onKeyPress='return numbersonly(event, false)'  />(Days)</td>
            </tr>
            <tr>
                <td>Last Purchase Price:</td>
                <td><?php textbox("purchase_price", $rec->PURCHASE_PRICE, 22) ?></td>
            </tr>
            <tr>
                <td><?php etr("Item Group") ?>:</td>
                <td><?php combobox('product_type', $product_groups, $rec->PRODUCT_TYPE_ID, false) ?>  </td>
            </tr>

            <tr>
                <td>At Actual:</td>
                <td>
                    <input type="checkbox" name="at_actual" value="1" <?php
                    if ($rec->AT_ACTUAL == 1) {
                        echo "checked";
                    }
                    ?> />  
                </td>
            </tr>

            <tr>
                <td>Requisition for:</td>
                <td>
                    <div style="width:30%; float:left">
                        <input name="requisition_for" type="radio" value="0"<?php
                        if ($rec->REQUISITION_FOR == 0) {
                            echo "checked";
                        }
                        ?> />Store Item</div>
                    <div style="width:30%; float:left">
                        <input name="requisition_for" type="radio" value="1"<?php
                        if ($rec->REQUISITION_FOR == 1) {
                            echo "checked";
                        }
                        ?> />
                        Purchase		</div>		
                    <div style="width:30%; float:left">
                        <input name="requisition_for" type="radio" value="2"<?php
                        if ($rec->REQUISITION_FOR == 2) {
                            echo "checked";
                        }
                        ?> />
                        Maintenance	  </div>
                </td>
            </tr>

            <tr>
        </table>
        <br/>
        <h2>Supplier Name and code:</h2>
        <table>
            <?php
            if (!isEmpty($productid)) {
                $productid2 = isEmpty($productid) ? 0 : $productid;
                $rs = query("SELECT sp.SUPPLIER_ID, SUPPLIER_NAME, SUPPLIER_PRODUCT_CODE, PRODUCT_PRICE
			FROM supplier_price sp
			join supplier s on s.SUPPLIER_ID=sp.SUPPLIER_ID
			where PRODUCT_ID='$productid'");
                $i = 0;
                while ($row = fetch($rs)) {
                    hidden("supplierid_$i", $row->SUPPLIER_ID);
                    echo "<tr>";
                    echo "<td>$row->SUPPLIER_NAME:</td>";
                    echo "<td>";
                    textbox("productcode_$i", $row->SUPPLIER_PRODUCT_CODE, 30, true);
                    hidden("old_productcode_$i", $row->SUPPLIER_PRODUCT_CODE);
                    echo "</td>";
                    echo "</tr>";
                    $i++;
                }
                hidden("supplier_count", $i);
                echo "<tr>";
                echo "<td>";
                combobox("supplierid_new", $suppliers, null, true);
                echo "</td>";
                echo "<td>";
                textbox("productcode_new", $row->SUPPLIER_PRODUCT_CODE, 30, true);
                echo "</td>";
                echo "</tr>";
            }
            ?>
        </table>  	

        <br/>
        <button type="submit" name = "save" class="button"/><span class = "icon plus"></span>Save Item</button>

        <?php
        deleteButton();
        if (!$new) {
            
        }
        button("Add Item", "add", "button");
        ?>
    </form>
</div>

<?php include("../body/footer.php"); ?>