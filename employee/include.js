
var url, objectName, objectId, columnsData;
$(document).ready(function() {
    loadGrid('dataGrid');
});
function loadGrid() {
    objectName = $('#object_name').val();
    objectId = $('#object_id').val();
    $('#dataGrid').datagrid({
        //title: 'Category List',
        iconCls: 'icon-edit',
        pagination: 'true',
        toolbar: "#toolbar",
        rownumbers: 'true',
        singleSelect: true,
        pageSize: 20,
        pagePosition: 'pos',
        idField: objectId,
        url: 'get.php',
        columns: [[
                {field: 'CARD_NO', title: 'Card No'},
                {field: 'EMPLOYEE_NAME', title: 'Employee name'},
                {field: 'DESIGNATION_NAME', title: 'Designation'},
                {field: 'BRANCH_DEPT_NAME', title: 'Branch/Dept'},
                {field: 'DIVISION_NAME', title: 'Division'},
                {field: 'ISACTIVE', title: 'Active'}

            ]]

    });
}



function AddNew() {
    $('#dlg').dialog('open').dialog('setTitle', 'Add New');
    $('#fm').form('clear');
    url = 'save.php?mode=new';
}
function Edit() {
    var row = $('#dataGrid').datagrid('getSelected');
    //alert(row.productid);
    if (row) {
        $('#dlg').dialog('open').dialog('setTitle', 'Edit');
        $('#fm').form('load', row);
        url = 'save.php?mode=""&search_id=' + row.EMPLOYEE_ID;
    }
}
function Save() {
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
                $('#dlg').dialog('close'); // close the dialog
                $('#dataGrid').datagrid('reload'); // reload the user data
            } else {
                $.messager.show({
                    title: 'Error',
                    msg: result.msg
                });
            }
        }
    });
}

function Remove() {
    var row = $('#dataGrid').datagrid('getSelected');
    if (row) {
        $.messager.confirm('Confirm', 'Are you sure you want to remove this user?', function(r) {
            if (r) {
                $.post('save.php?mode=delete',
                        {search_id: row.EMPLOYEE_ID},
                function(result) {
                    if (result.success) {
                        $('#dataGrid').datagrid('reload'); // reload the user data
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







function editEmployeeInfo() {
    var row = $('#dataGrid').datagrid('getSelected');
    if (row) {
        $('#editEmployeeInfo').attr('target', '_blank');
        //window.open('../employee_info/index.php?employeeId=' + row.EMPLOYEE_ID, '_blank');
        //location.replace('../employee_info/index.php?employeeId=' + row.EMPLOYEE_ID);
        window.open('../employee_info/index.php?employeeId=' + row.EMPLOYEE_ID);
    }
}

function loadWindow() {
    $('#dataGrid').datagrid('load', {
        mode: ''
    });
}

function doSearch() {
    $('#dataGrid').datagrid('load', {
        dateFrom: $('input[name="DateFrom"]').val(),
        dateTo: $('input[name="DateTo"]').val(),
        cardNo: $("#cardNo").val(),
        firstName: $("#firstName").val(),
        designationId: $('input[name="designationId"]').val(),
        IsActive: $('input[name="IsActive"]:checked').val(),
        //department: $('input[name="department"]').val(),
        mode: 'search'
    });

}