$(document).ready(function() {
    loadGrid();
});




function loadGrid() {
    $('#dataGrid').datagrid({
        //title: 'Category List',
        iconCls: 'icon-edit',
        pagination: 'true',
        toolbar: "#toolbar",
        rownumbers: 'true',
        singleSelect: true,
        pageSize: 20,
        pagePosition: 'top',
        idField: 'PRODUCT_ID',
        url: 'product_get.php',
        columns: [[
                {field: 'PRODUCT_CODE', title: 'Product Code'},
                {field: 'PRODUCT_NAME', title: 'Product Name'},
                {field: 'CATEGORY_NAME', title: 'Category Name'},
                {field: 'CATEGORY_SUB_NAME', title: 'Sub Category Name'},
                {field: 'GROUP_NAME', title: 'Group Name'},
                {field: 'PROCESS_DEPT_NAME', title: 'Process Dept'},
                {field: 'UNIT_TYPE_NAME', title: 'Unit Type'},
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
    //alert(row.PRODUCT_ID);
    if (row) {
        $('#dlg').removeClass('display_none');
        $('#dlg').dialog('open').dialog('setTitle', 'Edit Product');
        $('#fm').form('load', row);
        url = 'product_save.php?mode=""&search_id=' + row.PRODUCT_ID;
    }
}

$('#SaveTransaction').click(function() {

    var form = $(".FormValidate");
    form.validate();

    if (form.valid()) {
        Save();
    }
});
function Save() {
    var form = $(".FormValidate");
    form.validate();

    if (form.valid()) {


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

    //alert(22);

}

function removeProduct() {
    //alert(22);
    var row = $('#dataGrid').datagrid('getSelected');
    if (row) {
        $.messager.confirm('Confirm', 'Are you sure you want to remove?', function(r) {
            if (r) {
                $.post('product_save.php?mode=delete',
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
        ProductName: $('input[name="ProductName"]').val(),
        CategoryId: $('select[name="CategoryId"] option:selected').val(),
        CategorySubId: $('select[name="CategorySubId"] option:selected').val(),
        CategoryUnderSubId: $('select[name="CategoryUnderSubId"] option:selected').val(),
        ProcessDeptId: $('select[name="ProcessDeptId"] option:selected').val(),
        ProductTypeId: $('input[name="ProductTypeId"]:checked').val(),
        ProductGroup: $('input[name="ProductGroup"]:checked').val(),
        mode: 'search'
    });
}

function addSupplier() {
    var row = $('#dataGrid').datagrid('getSelected');
    //alert(row.PRODUCT_ID);
    if (row) {
        location.replace('../product/tag_supplier.php?search_id=' + row.PRODUCT_ID);
    }
}

$('#fm').form('load', {
    is_active: '1'
});

