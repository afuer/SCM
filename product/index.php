<?php

include_once '../lib/DbManager.php';


$processDept = getParam('processDept');


$productlist = $db->rs2array("SELECT PRODUCT_ID, PRODUCT_CODE, PRODUCT_NAME FROM product WHERE PROCESS_DEPT_ID='$processDept' ORDER BY PRODUCT_NAME");
$categorylist = $db->rs2array("SELECT CATEGORY_ID, CATEGORY_NAME FROM category ORDER BY CATEGORY_NAME");
$categorySubList = $db->rs2array("SELECT CATEGORY_SUB_ID, CATEGORY_SUB_NAME FROM category_sub ORDER BY CATEGORY_SUB_NAME");
$ProductBrandList = $db->rs2array("SELECT PRODUCT_BRAND_ID, PRODUCT_BRAND_NAME FROM product_brand ORDER BY PRODUCT_BRAND_NAME");
$processDeptList = $db->rs2array("SELECT PROCESS_DEPT_ID, PROCESS_DEPT_NAME FROM process_dept ORDER BY PROCESS_DEPT_NAME");
$UnitTypeList = $db->rs2array("SELECT UNIT_TYPE_ID, UNIT_TYPE_NAME FROM unit_type ORDER BY UNIT_TYPE_NAME");
$ProductGroupList = $db->rs2array("SELECT PRODUCT_GROUP_ID, GROUP_NAME FROM product_group");
$categoryUnderSubCategoryList = $db->rs2array("SELECT CATEGORY_SUB_UNDER_ID, CATEGORY_SUB_UNDER_NAME FROM category_sub_under ORDER BY CATEGORY_SUB_UNDER_NAME");

include '../body/header.php';
include 'add.php';

$object_name = 'product';
$object_id = strtoupper($object_name) . '_ID';
?>

<script type="text/javascript" src="include.js"></script>

<div class="easyui-layout" style="margin: auto; height:900px;">  
    <div Title='Product List' data-options="region:'center'" style="background-color:white; padding: 10px 10px;"> 
        <?php include './header_search.php'; ?>
        <table class="" id="dataGrid" data-options="fit:true,fitColumns:true"></table> 
    </div>  
</div>


<div id="toolbar" style="padding:5px; height:auto;">  
    <div id="toolbar">
        <a href="#" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newProduct();">Add Product</a>
        <a href="#" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editProduct();">Edit Product</a>
        <a href="#" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="removeProduct();">Remove Product</a>
        <a href="#" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="addSupplier();">Add Supplier</a>
    </div>
</div>

<?php

include '../body/footer.php';
?>