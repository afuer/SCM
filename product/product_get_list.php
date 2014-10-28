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
        <div class="easyui-accordion" data-options="fit:true,border:false,plain:true">  

            <div title="Product List" data-options="selected:true">
                <?php include './header_search.php'; ?>
                <table class="" id="dataGrid" data-options="fit:true,fitColumns:true"></table> 
            </div>  

        </div>  

    </div>  
</div>  
</div>


<?php
include '../body/footer.php';
?>