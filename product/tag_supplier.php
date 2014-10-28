<?php
include '../lib/DbManager.php';
include_once '../body/header.php';



$object_name = 'product';
$object_id = strtoupper($object_name) . '_ID';
$search_id = getParam('search_id');


$supplier_list = rs2array(query("SELECT SUPPLIER_ID, SUPPLIER_NAME FROM supplier  ORDER BY SUPPLIER_NAME ASC"));

if (isSave()) {

    $supplierId = getParam("supplierId");
    $productCode = getParam("productCode");

    $sql = "insert into supplier_price (SUPPLIER_ID, PRODUCT_ID, PRICE, PRODUCT_CODE)
			values ('$supplierId', '$search_id', 0, '$productCode')";
    sql($sql);
}


$query = "SELECT product_id, product_code, p.description, purchase_price, product_name, qty, c.CATEGORY_NAME, 
sc.CATEGORY_SUB_NAME, pg.group_name,
ut.unit_type_name, isactive, reorder_level, daily_expense, lead_time, reorder_qty, 
FREE_QTY, pt.product_type_name, at_actual

FROM product p 
left join category c on c.CATEGORY_ID=p.CATEGORY_ID
left join category_sub sc on sc.CATEGORY_SUB_ID=p.CATEGORY_SUB_ID 
left join product_group pg on pg.PRODUCT_GROUP_ID=p.PRODUCT_GROUP_ID

left join unit_type ut on ut.unit_type_id=p.unit_type_id 
left join product_type pt on pt.product_type_id=p.product_type_id 
 
WHERE p.product_id='$search_id'";
?>
<div class="easyui-layout" style="width:100%; height:600px;">  
    <div title="Search Option" data-options="region:'north',split:true, collapsed:true" title="West" style="height: 200px;">  
        <div class="easyui-accordion" data-options="fit:true,border:false">
            DD

        </div>  
    </div>

    <div data-options="region:'south',split:true" style="height:50px;">Button Part</div>

    <div data-options="region:'east', split:true, collapsed:true" title="East" style="width:250px;">  
        <ul class="easyui-tree" data-options="url:'TreeJson.php', animate:true, dnd:true"></ul>  
    </div> 

    <div data-options="region:'west',split:true, collapsed:true" title="West" style="width:200px;">  
        <div class="easyui-accordion" data-options="fit:true,border:false">  
            <div title="Title1" style="padding:10px;">  
                content1  
            </div>  
            <div title="Title2" data-options="selected:true" style="padding:10px;">  
                content2  
            </div>  
            <div title="Title3" style="padding:10px">  
                content3  
            </div>  
        </div>  
    </div>

    <div data-options="region:'center'"> 
        <div title="Search List" data-options="region:'center'">

        </div> 
        <div class="easyui-accordion" data-options="fit:true,border:false,plain:true">  

            <div title="Supplier Tag" data-options="selected:true">  
                <?php include '../lib/master_page_view.php'; ?>

                <form action="" method="POST">
                    <h2 class="center">Supplier Name and code</h2>
                    <table class="ui-state-default">
                        <thead>
                        <th>SL</th>
                        <th>Supplier Name</th>
                        <th>Supplier Code</th>
                        <th>Action</th>
                        </thead>
                        <?php
                        $supplierSql = "SELECT sp.SUPPLIER_ID, SUPPLIER_NAME, sp.PRODUCT_CODE, sp.PRICE
                        FROM supplier_price sp
                        LEFT JOIN supplier s on s.SUPPLIER_ID=sp.SUPPLIER_ID
                        WHERE sp.PRODUCT_ID='$search_id'";

                        $rs = query($supplierSql);
                        $db->CloseDb();
                        $i = 1;
                        while ($row = fetch_object($rs)) {
                            ?>
                            <tr>
                                <td><?php echo $i; ?></td>
                                <td><?php echo $row->SUPPLIER_NAME; ?></td>
                                <td width="100"><?php echo $row->PRODUCT_CODE; ?></td>
                                <td width="50"></td>
                            </tr>
                            <?php
                            $i++;
                        }
                        ?>

                        <tr>
                            <td><?php echo $i; ?></td>
                            <td><?php combobox("supplierId", $supplier_list, NULL, TRUE); ?></td>
                            <td width="100"><input type="text" name="productCode" value="<?php echo $row->PRODUCT_CODE; ?>"/></td>
                            <td width="50"><button type="submit" name = "save" class="button" /><span class = "icon plus"></span>Add Supplier Price</button></td>
                        </tr>
                    </table>  	
                </form>

            </div>  

        </div>  
    </div>  
</div>




<?php include '../body/footer.php'; ?>
