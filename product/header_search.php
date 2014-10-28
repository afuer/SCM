<?php
$processDept = getParam('processDept');
$processDeptList = rs2array(query("SELECT PRODUCT_TYPE_ID, PRODUCT_TYPE_NAME FROM product_type ORDER BY PRODUCT_TYPE_NAME"));
$processDeptList = rs2array(query("SELECT PROCESS_DEPT_ID,PROCESS_DEPT_NAME FROM process_dept ORDER BY PROCESS_DEPT_NAME"));
$categorylist = rs2array(query("SELECT CATEGORY_ID, CATEGORY_NAME FROM category ORDER BY CATEGORY_NAME"));
$categorySubList = rs2array(query("SELECT CATEGORY_SUB_ID, CATEGORY_SUB_NAME FROM category_sub ORDER BY CATEGORY_SUB_NAME"));
$categoryUnderSubList = rs2array(query("SELECT CATEGORY_SUB_UNDER_ID, CATEGORY_SUB_UNDER_NAME FROM category_sub_under ORDER BY CATEGORY_SUB_UNDER_NAME"));
?>
<fieldset><legend>Search</legend>

    <table style="width: 800px; font-size: 11pt;">
        <tr>
            <td width='150'>Product Name:</td>
            <td><input type="text" name="ProductName" id="ProductName" class="ProductName"/></td>
            <td width='200'>Category:</td>
            <td><?php comboBox('CategoryId', $categorylist, NULL, TRUE, '', 'ajax_category_sub_id_search'); ?></td>
        </tr>
        <tr>
            <td>Category Sub:</td>
            <td id="ajax_category_sub_id_search"><?php comboBox('CategorySubId', $categorySubList, NULL, TRUE, '', 'ajax_category_under_sub_id_search'); ?></td>
            <td>Under Sub Category:</td>
            <td id="ajax_category_under_sub_id_search"><?php comboBox('CategoryUnderSubId', $categoryUnderSubList, NULL, TRUE); ?></td>
        </tr>
        <tr>
            <td width='100'>Process Dept:</td>
            <td><?php comboBox('ProcessDeptId', $processDeptList, NULL, TRUE); ?></td>
            <td>Product Type:</td>
            <td><input type="checkbox" name="ProductTypeId" class="ProductTypeId" id="ProductTypeId" value="1"/> IsStore</td>
        </tr>
        <tr>
            <td width='100'>Product Group:</td>
            <td>
                <input type="radio" name="ProductGroup" class="ProductType" id="" value="1" checked="true"/> Opex
                <input type="radio" name="ProductGroup" class="ProductType" id="" value="2"/> Capex
            </td>
            <td></td>
            <td></td>
        </tr>



    </table>
    <button class="easyui-linkbutton button" onclick="doSearch();" iconCls="icon-search">Search</button>
    <button type="button" class="easyui-linkbutton button" iconCls="icon-search" onclick="loadWindow();">Rest</button>
</fieldset>