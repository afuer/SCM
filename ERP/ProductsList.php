<?php
include_once '../lib/DbManager.php';
//checkPermission(36);
include("../body/header.php");

$mode = getParam('mode');
$orderid = getParam('orderid');

$model = getParam('model');
$locationid = getParam('locationid');
$maincategory = getParam('maincategory');
$categoryid = getParam('categoryid');
$subcategoryid = getParam('subcategoryid');
$requisition_for = getParam('requisition_for');
$requisition_routeid = getParam('requisition_routeid');


$salesorder = getParam('salesorder');

$purchaseorderreturn = getParam('purchaseorderreturn');


$condition = "where 1 AND";
if ($model) {
    $condition .= " PRODUCT_NAME like '$model%' AND";
}

if ($maincategory) {
    $condition .=" p.MAINCATEGORY_ID=$maincategory AND";
}

if ($categoryid) {
    $condition .=" p.CATEGORY_ID=$categoryid AND";
}

if ($requisition_for) {
    if ($requisition_for == 2) {
        $requisition_for = 0;
    }
    $condition .=" p.REQUISITION_FOR='$requisition_for' AND";
}


if ($subcategoryid) {
    $condition .=" p.subcategoryid='$subcategoryid' AND";
}

if ($requisition_routeid) {
    $condition .=" p.requisition_routeid='$requisition_routeid' AND";
}

$condition = substr($condition, 0, -4);


$del_productid = getParam("del_productid");
if (!isEmpty($del_productid)) {
    deleteProduct($del_productid);
}


$selectSQL = "SELECT P.PRODUCT_ID,
        P.REORDER_QTY,
        P.PRODUCT_CODE, PRODUCT_NAME,
        CONCAT(MC.MAIN_CATEGOTY_NAME, '-> ',CA.DESCRIPTION,'-> ', P.PRODUCT_NAME) AS DETAILS,
        P.UNIT_TYPE_ID, ut.UNIT_TYPE_NAME,
        (CASE WHEN P.ISACTIVE=1 THEN 'ACTIVE' ELSE 'INACTIVE' END) AS 'STATUS'

        FROM PRODUCT P 
        LEFT JOIN MAIN_CATEGORY MC ON MC.MAIN_CATEGORY_ID=P.MAINCATEGORY_ID
        LEFT JOIN CATEGORY CA ON CA.CATEGORY_ID=P.CATEGORY_ID
        LEFT JOIN unit_type AS ut ON ut.UNIT_TYPE_ID=p.UNIT_TYPE_ID
	$condition ORDER BY PRODUCT_NAME";



if ($_POST['sortBy'] && $_POST['sortIn'])
    $selectSQL .="ORDER BY p.$_POST[sortBy] $_POST[sortIn]";

$mode = getParam('mode');
$orderid = getParam('orderid');

$MainCategoryList = rs2array(query("SELECT MAIN_CATEGORY_ID, MAIN_CATEGOTY_NAME FROM main_category ORDER BY MAIN_CATEGOTY_NAME"));
$CategorieList = rs2array(query("SELECT CATEGORY_ID, DESCRIPTION FROM category ORDER BY DESCRIPTION ASC"));
$requisition_routes = rs2array(query("SELECT REQUISITION_ROUTE_ID, ROUTE_NAME FROM requisition_route ORDER BY ROUTE_NAME"));
$RequisitionFor = array(array('0', 'Store Requisition'), array('1', 'Purchase Requisition'));
?>


<div Title='Product/ Item List' class="easyui-panel" style="width:1000px; height:700px;" >

    <form action="" method="GET">
        <fieldset>
            <legend>Search</legend>

            <table>
                <tr>
                    <td class=label>Requisition for:</td>
                    <td><?php comboBox('requisition_for', $RequisitionFor, $requisition_for, true, 'required'); ?></td>
                </tr>
                <tr>
                    <td>Item Name:</td>
                    <td><input type="text" name="model" value="" id='suggest4' onKeyUp="valueExist();"/> </td>
                    <td>Main Category:</td>
                    <td><?php comboBox('maincategory', $MainCategoryList, $maincategory, true, 'required', ''); ?></td>
                </tr>
                <tr>
                    <td>Category:</td>
                    <td id="ajax_catid"><?php comboBox('categoryid', $CategorieList, $categoryid, true, 'required', 'ajax_subcategory'); ?></td>
                    <td>
                        <?php
                        if ($user == "admin") {
                            etr("Requisition Route");
                        }
                        ?>
                    </td>
                    <td>
                        <?php
                        if ($user == "admin") {
                            comboBox("requisition_routeid", $requisition_routes, $rec->requisition_routeid, false);
                        }
                        ?>
                    </td>
                </tr>
            </table>  
            <?php searchButton(); ?> (Please Click Search Button for item list)
        </fieldset>
        </table> 
    </form>


    <?php
    if ($mode == "selectproduct") {
        ?>
        <form action="../requisition/salesorder.php" method=POST>
        <?php } else { ?>
            <form action="" method=POST> 
            <?php }
            ?>

            <input type='hidden' name='mode' value='<?php echo $mode ?>'/>
            <input type='hidden' name='orderid' value='<?php echo $orderid ?>'/>   
            <?php
            $search = getParam('search');
            if (isset($search)) {
                ?> 
                <button type="submit" name = "insertnewdata" class="button"/><span class = "icon plus"></span>Insert Item requisition</button>
                <table class="ui-state-default">
                    <thead>
                    <th>SL</th>
                    <th width="20"></th>
                    <th width="80" align="center">Item Code</th>
                    <th>Main Category->Category->Item</th>
                    <th>Unit/ Size</th>
                    <th>Status</th>
                    </thead>
                    <tbody>
                        <?php
                        $rs = query($selectSQL);
                        while ($row = fetch_object($rs)) {
                            $sl++;
                            $href = "ProductNew.php?mode=search&productid=$row->PRODUCT_ID";
                            echo "<tr>";
                            echo "<td class='sn'>$sl.</td>
                    <td><input type='checkbox' name='ck[]' id='$row->PRODUCT_ID' value='$row->PRODUCT_ID' /></td>
                    <td><label for='$row->PRODUCT_ID'>$row->PRODUCT_CODE</level></td>";

                            if ($mode == "selectproduct") {
                                echo "<td><label for='$row->PRODUCT_ID' >$row->DETAILS</level></td>";
                            } else {
                                echo "<td><a href='$href'>$row->DETAILS</a></td> ";
                            }
                            echo "<td align='right'>$row->UNIT_TYPE_NAME</td>";
                            echo"<td>$row->STATUS</td> ";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>

                <br/>
                <button type="submit" name = "insertnewdata" class="button"/><span class = "icon plus"></span>Insert Item requisition</button>
                <?php
                if ($user == "admin") {
                    button("Add Item", "add", "product.php");
                }
            }
            ?>
        </form>
</div>

<?php include("../body/footer.php"); ?>