<div id="dlg" class="easyui-dialog display_none" style="margin:auto; width:700px; height:600px;padding:10px 20px"  closed="true" buttons="#dlg-buttons">
    <form id="fm" method="post" novalidate autocomplete="off" class="form FormValidate">
        <table class="table">
            <tr class='fitem'>
                <td width='120'><label><strong>Product Code :</strong></label></td>
                <td id='td_PRODUCT_CODE'><input type='text' name='PRODUCT_CODE' id='PRODUCT_CODE' class='easyui-validatebox' value='' /></td>
            </tr>
            <tr class='fitem'>
                <td width='120'><label><strong>Product Name :</strong></label></td>
                <td id='td_PRODUCT_NAME'><input type='text' name='PRODUCT_NAME' id='PRODUCT_NAME' class='easyui-validatebox' value='' /></td>
            </tr>
            <tr class='fitem'>
                <td width='120' valign='top'><strong>Description :</strong></td>
                <td><textarea name='DESCRIPTION' id='DESCRIPTION' class='DESCRIPTION'></textarea></td>
            </tr>
            <tr class='fitem'>
                <td width='120'><label><strong>Category :</strong></label></td>
                <td id='td_CATEGORY_ID'><?php comboBox('CATEGORY_ID', $categorylist, NULL, TRUE, 'required', 'ajax_category_sub_id'); ?></td>
            </tr>
            <tr class='fitem'>
                <td width='120'><label><strong>Sub Category :</strong></label></td>
                <td id='ajax_category_sub_id'><?php comboBox('CATEGORY_SUB_ID', $categorySubList, NULL, TRUE); ?></td>
            </tr>
            <tr class='fitem'>
                <td width='120'><label><strong>Under Sub Category :</strong></label></td>
                <td id='ajax_category_under_sub_id'><?php comboBox('UNDER_SUB_CATEGORY_ID', $categoryUnderSubCategoryList, NULL, FALSE); ?></td>
            </tr>
            <tr class='fitem'><td width='120'><label><strong>Product Brand :</strong></label></td>
                <td id='td_PRODUCT_BRAND_ID'><?php comboBox('PRODUCT_BRAND_ID', $ProductBrandList, NULL, TRUE, 'required'); ?></td>
            </tr>
            <tr class='fitem'><td width='120'><label><strong>Process Dept :</strong></label></td>
                <td id='td_PROCESS_DEPT_ID'><?php comboBox('PROCESS_DEPT_ID', $processDeptList, NULL, TRUE, 'required'); ?></td>
            </tr>
            <tr class='fitem'>
                <td width='120'><label><strong>Product Type :</strong></label></td>
                <td>
                    <input type='radio' id='PRODUCT_TYPE_ID1' name='PRODUCT_TYPE_ID' value='1' checked="true"/>  Store  
                    <input type='radio' id='PRODUCT_TYPE_ID2' name='PRODUCT_TYPE_ID' value='2' /> Purchase 
                </td>
            </tr>
            <tr class='fitem'><td width='120'><label><strong>Unit :</strong></label></td>
                <td id='td_UNIT_TYPE_ID'><?php comboBox('UNIT_TYPE_ID', $UnitTypeList, NULL, TRUE, 'required'); ?></td>
            </tr>
            <tr class='fitem'>
                <td width='120'><label><strong>Product Low Level :</strong></label></td>
                <td id='td_REORDER_LEVEL'><input type='text' name='REORDER_LEVEL' id='REORDER_LEVEL' class='easyui-validatebox' value='' /></td>
            </tr>
            <tr class='fitem'>
                <td width='120'><label><strong>Daily Expense(Avg) :</strong></label></td>
                <td id='td_DAILY_EXPENSE'><input type='text' name='DAILY_EXPENSE' id='DAILY_EXPENSE' class='easyui-validatebox' value='' /></td>
            </tr>
            <tr class='fitem'>
                <td width='120'><label><strong>Lead Time(Avg) :</strong></label></td>
                <td id='td_LEAD_TIME'><input type='text' name='LEAD_TIME' id='LEAD_TIME' class='easyui-validatebox' value='' /></td>
            </tr>
            <tr class='fitem'>
                <td width='120'><label><strong>Purchase Price :</strong></label></td>
                <td id='td_LAST_PURCHASE_PRICE'><input type='text' name='PURCHASE_PRICE' id='LAST_PURCHASE_PRICE' class='easyui-validatebox' value='' /></td>
            </tr>
            <tr class='fitem'>
                <td width='120'><label><strong>Product Group :</strong></label></td>
                <td id='td_PRODUCT_GROUP_ID'><?php comboBox('PRODUCT_GROUP_ID', $ProductGroupList, NULL, TRUE, 'required'); ?></td>
            </tr>
            <tr class='fitem'>
                <td width='120'><strong> At Actual :</strong></td>
                <td>YES <input type='checkbox' id='AT_ACTUAL0' name='AT_ACTUAL' value='1' checked="true"/></td>
            </tr>
            <tr class='fitem'> 
                <td width='120'><strong> Active :</strong></td>
                <td>No <input type='radio' id='ISACTIVE0' name='ISACTIVE' value='0' />  YES <input type='radio' id='ISACTIVE1' name='ISACTIVE' value='1' checked/></td>
            </tr>
        </table>
    </form>
    <br/>
    <div id="">
        <button class="easyui-linkbutton button" iconCls="icon-ok" onclick="Save();">Save</button>
        <button class="easyui-linkbutton button" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close');">Cancel</button>
    </div>
</div>
