
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
    $('#dataGrid').datagrid({// head office
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

function AddABoq(TableID) {
    var tr = $('#' + TableID + ' tbody>tr:last').clone(true);
    var td = tr.find('td:first');
    var sl = parseInt(td.text());
    td.text(sl + 1 + '.');
    tr.insertAfter('#' + TableID + ' tbody>tr:last').find('input, select').attr('class', 'add').val('');
}

function EmployeeInfo(obj) {
    var Card_no, result, itemrow;
    Card_no = obj.val();

    itemrow = obj.closest('tr');
    $('#loder').show();
    $.ajax({
        url: "ajax_employee.php?card_no=" + Card_no,
        type: "GET",
        contentType: "application/json",
        dataType: "text",
        success: function(data) {
            result = JSON.parse(data);
            itemrow.find('#employee_details').html(result.empName);
            itemrow.find('#employee_id').val(result.EMPLOYEE_ID);
            itemrow.find('#designationId').val(result.DESIGNATION_ID);
            $('#loder').hide();
        }
    });
}

function removeStackHolder(requisition_id, module, mode) {

    var Requisition_id = requisition_id;
    alert(Requisition_id);
    var Module = module;
    var Mode = mode;
    $.messager.confirm('Confirm', 'Are you sure you want to destroy this user?', function(r) {
        if (r) {
            alert(Requisition_id, Module, Mode);
        }
    });


}

function DeleteStackHolder(Requisition_id, Module, Mode) {
    var Requisition_id1 = Requisition_id;
    var Module1 = Module;
    var Mode1 = Mode;
    $.ajax({
        type: "GET",
        url: 'stack_holder_delete.php?&mode=delete&search_id=' + Requisition_id1,
        success: function(data) { //alert (data);
            //console.log(data);
            //window.location.href = 'index.php?requisition_id='+ Requisition_id1;
            window.location.href = 'stack_holder_new.php?mode=' + Mode1 + '&module=' + Module1 + '&requisition_id=' + Requisition_id1;

        }
    });

}




        