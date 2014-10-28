$(document).ready(function() {
    loadGrid();

    $('#CATEGORY_ID').change(function() {
        onChange($(this), 'SUB_CATEGORY_ID');
    });



    $('#PROCESS_DEPARTMENT_ID').combobox({
        url: 'requisition_route.php',
        valueField: 'REQUISITION_ROUTE_ID',
        textField: 'ROUTE_NAME'
    });

    $('#CATEGORY_ID').combobox({
        url: '../category/category_get_combo.php',
        valueField: 'CATEGORY_ID',
        textField: 'CATEGORY_NAME',
        onSelect: function(rec) {
            var url = '../category_sub/category_sub_get_combo?val=' + rec.CATEGORY_ID;
            $('#CATEGORY_SUB_ID').combobox('reload', url);
        }
    });

    $('#CATEGORY_SUB_ID').combobox({
        url: '../category_sub/category_sub_get_combo.php',
        valueField: 'CATEGORY_SUB_ID',
        textField: 'CATEGORY_SUB_NAME'
    });

    /*
     
     //For Search
     $('#categoryId').combobox({
     url: '../category/category_get_combo.php',
     valueField: 'CATEGORY_ID',
     textField: 'CATEGORY_NAME',
     onSelect: function(rec) {
     var url = '../category_sub/category_sub_get_combo?val=' + rec.CATEGORY_ID;
     $('#categorySubId').combobox('reload', url);
     }
     });
     
     //For Search
     $('#categorySubId').combobox({
     url: '../category_sub/category_sub_get_combo.php',
     valueField: 'CATEGORY_SUB_ID',
     textField: 'CATEGORY_SUB_NAME'
     });
     */



    $('#PRODUCT_GROUP_ID').combobox({
        url: 'product_group_get_combo.php',
        valueField: 'PRODUCT_GROUP_ID',
        textField: 'GROUP_NAME'
    });

    $('#REQUISITION_FOR').combobox({
        url: 'requisition_for_get_combo.php',
        valueField: 'REQUISITION_FOR_ID',
        textField: 'REQUISITION_FOR'
    });

    $('#UNIT_TYPE_ID').combobox({
        url: 'unit_get_combo.php',
        valueField: 'UNIT_TYPE_ID',
        textField: 'UNIT_TYPE_NAME'
    });

    $('#PRODUCT_BRAND_ID').combobox({
        url: '../product_brand/product_brand_get_combo.php',
        valueField: 'PRODUCT_BRAND_ID',
        textField: 'PRODUCT_BRAND_NAME'
    });

    $('#REQUISITION_ROUTE_ID').combobox({
        url: 'requisition_route_get_combo.php',
        valueField: 'REQUISITION_ROUTE_ID',
        textField: 'ROUTE_NAME'
    });

    $('#PRODUCT_BRAND_ID').combobox({
        url: '../product_brand/product_brand_get_combo.php',
        valueField: 'PRODUCT_BRAND_ID',
        textField: 'PRODUCT_BRAND_NAME'
    });



});




function loadGrid() {
    $('#dataGrid').datagrid({
        //title: 'Category List',
        iconCls: 'icon-edit',
        pagination: 'true',
        toolbar: "#toolbar",
        rownumbers: 'true',
        singleSelect: true,
        pageSize: 10,
        pagePosition: 'top',
        idField: 'PRODUCT_ID',
        url: 'product_get.php',
        columns: [[
                {field: 'PRODUCT_CODE', title: 'Product Code'},
                {field: 'PRODUCT_NAME', title: 'Product Name'},
                {field: 'CATEGORY_NAME', title: 'Category Name'},
                {field: 'CATEGORY_SUB_NAME', title: 'Sub Category Name'},
                {field: 'GROUP_NAME', title: 'Group Name'},
                {field: 'REQUISITION_ROUTE_TYPE_NAME', title: 'Route Name'},
                {field: 'UNIT_TYPE_NAME', title: 'Unit Type'},
                {field: 'REQUISITION_FOR', title: 'Requisition For'}
            ]]

    });
}

function newProduct() {
    $('#dlg').removeClass('display_none');

    $('#dlg').dialog('open').dialog('setTitle', 'Add Product');
    $('#fm').form('clear');
    url = 'product_save.php?mode=new';
}
function editProduct() {
    var row = $('#dataGrid').datagrid('getSelected');
    //alert(row.productid);
    if (row) {
        $('#dlg').dialog('open').dialog('setTitle', 'Edit Product');
        $('#fm').form('load', row);
        url = 'product_save.php?mode=""&search_id=' + row.PRODUCT_ID;
    }
}
function saveUser() {
    //alert(22);
    $('#fm').form('submit', {
        url: url,
        onSubmit: function() {
            return $(this).form('validate');
        },
        success: function(result) {
            //alert(result);
            var result = eval('(' + result + ')');
            if (result.success) {
                $('#dlg').dialog('close');		// close the dialog
                $('#dataGrid').datagrid('reload');	// reload the user data
            } else {
                $.messager.show({
                    title: 'Error',
                    msg: result.msg
                });
            }
        }
    });
}

function removeUser() {
    var row = $('#dataGrid').datagrid('getSelected');
    if (row) {
        $.messager.confirm('Confirm', 'Are you sure you want to remove this user?', function(r) {
            if (r) {
                $.post(objectName + '_save.php?mode=delete',
                        {search_id: row.PRODUCT_ID},
                function(result) {
                    if (result.success) {
                        $('#dataGrid').datagrid('reload');	// reload the user data
                    } else {
                        $.messager.show({// show error message
                            title: 'Error',
                            msg: result.msg
                        });
                    }
                }, 'json');
            }
        });
    }
}

function loadWindow() {
    $('#dataGrid').datagrid('load', {
        mode: ''
    });
}

function doSearch() {
    $('#dataGrid').datagrid('load', {
        productName: $("#productName").val(),
        categoryId: $('input[name="categoryId"]').val(),
        categorySubId: $('input[name="categorySubId"]').val(),
        requisitionFor: $("#requisitionFor").val(),
        mode: 'search'
    });
}

function addSupplier() {
    var row = $('#dataGrid').datagrid('getSelected');
    //alert(row.PRODUCT_ID);
    if (row) {
        location.replace('../product/tag_supplier?search_id=' + row.PRODUCT_ID);
    }
}

$('#fm').form('load', {
    is_active: '1'
});

