
var url, objectName, objectId;
$(document).ready(function() {

        $('#tt').tabs({
        border: true,
        onSelect: function(title, index) {
            //alert(index);
            if (index === 0) {
                loadGrid();
            }
            if (index === 1) {
                loadGrid1();
            }
            
            if (index === 2) {
                loadGrid2();
            }

        }
    });
    

});

function loadGrid() {
    objectName = $('#object_name').val();
    objectId = $('#object_id').val();
    $('#dataGrid').datagrid({ // head office
        //title: 'Category List',
        iconCls: 'icon-edit',
        pagination: 'true',
        toolbar: ".toolbar",
        rownumbers: 'true',
        singleSelect: true,
        pageSize: 10,
        pagePosition: 'pos',
        idField: objectId,
        url: 'get.php?id=1',
        columns: [[
                {field: 'PROCESS_DEPT_NAME', title: 'Process Dept'},
                {field: 'LD', title: 'Designation'},
                {field: 'APPROVAL_LIMIT', title: 'Approval Limit'}
            ]]
    });
}

function loadGrid1() {  //Plant
    objectName = $('#object_name').val();
    objectId = $('#object_id').val();
    $('#dataGrid1').datagrid({
        //title: 'Category List',
        iconCls: 'icon-edit',
        pagination: 'true',
        toolbar: ".toolbar",
        rownumbers: 'true',
        singleSelect: true,
        pageSize: 10,
        pagePosition: 'pos',
        idField: objectId,
        url: 'get.php?id=3',
        columns: [[
                {field: 'LOCATION_NAME', title: 'Route'},
                {field: 'LD', title: 'Designation'},
                {field: 'APPROVAL_LIMIT', title: 'Approval Limit'}
            ]]
    });
}

function loadGrid2() { //LUMP
    objectName = $('#object_name').val();
    objectId = $('#object_id').val();
    $('#dataGrid2').datagrid({
        //title: 'Category List',
        iconCls: 'icon-edit',
        pagination: 'true',
        toolbar: ".toolbar",
        rownumbers: 'true',
        singleSelect: true,
        pageSize: 10,
        pagePosition: 'pos',
        idField: objectId,
        url: 'get.php?id=2',
        columns: [[
                {field: 'LOCATION_NAME', title: 'Route'},
                {field: 'LD', title: 'Designation'},
                {field: 'APPROVAL_LIMIT', title: 'Approval Limit'}
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
        url = 'save.php?mode=""&search_id=' + row.DELEGATION_AUTHORITY_ID;
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
                        {
                            search_id: row.DELEGATION_AUTHORITY_ID
                        },
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
        location.replace('../employee_info/index.php?employeeId=' + row.EMPLOYEE_ID);
    }
}

function loadWindow() {
    $('#dataGrid').datagrid('load', {
        mode: ''
    });
}

function doSearch() {
    $('#dataGrid').datagrid('load', {
        cardNo: $("#cardNo").val(),
        firstName: $("#firstName").val(),
        officeTypeId: $('input[name="officeTypeId"]').val(),
        designationId: $('input[name="designationId"]').val(),
        branchDevision: $('input[name="branchDevision"]').val(),
        mode: 'search'
    });
}