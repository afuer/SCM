

$(document).ready(function() {
    var requisitionFor = $('#requisitionFor').val();

    $('#tt').tabs({
        border: true,
        onSelect: function(title, index) {
            //alert(index);
            if (index === 1) {
                RequisitionApproval();
            }
            if (index === 0) {
                loadAllRequisition();
            }

        }
    });

    //loadAllRequisition();
    //RecalcProduct();
    //$('#requisitionHeader').load('ajax_requisition_header.php?requisitionFor=' + requisitionFor);
});


function addRequisition(requisition_type_id) {
    location.replace('requisition_new.php?mode=new&requisition_type_id=' + requisition_type_id + '&processDeptId=5');
}



function loadAllRequisition() {

    $('#allRequisition').datagrid({
        title: 'Category List',
        iconCls: 'icon-edit',
        pagination: 'true',
        toolbar: "#toolbar",
        rownumbers: 'true',
        singleSelect: true,
        pageSize: 10,
        pagePosition: 'top',
        idField: 'REQUISITION_ID',
        url: 'requisition_get.php?mode=all',
        columns: [[
                {field: 'REQUISITION_NO', title: 'Requisition No'},
                {field: 'REQUISITION_DATE', title: 'Date'},
                {field: 'REQUISITION_TYPE_NAME', title: 'Requisition Type'},
                {field: 'PROCESS_DEPT_NAME', title: 'Process Dept'},
                {field: 'PresentLocation', title: 'Present Location'},
                {field: 'status_name', title: 'Status'}
            ]]

    });
}

function RequisitionApproval() {

    $('#RequisitionApproval').datagrid({
        //title: 'Category List',
        iconCls: 'icon-edit',
        pagination: 'true',
        toolbar: "#toolbar",
        rownumbers: 'true',
        singleSelect: true,
        pageSize: 10,
        pagePosition: 'top',
        idField: 'REQUISITION_ID',
        url: 'requisition_get.php',
        columns: [[
                {field: 'REQUISITION_NO', title: 'Requisition Code'},
                {field: 'REQUISITION_DATE', title: 'Date'},
                {field: 'REQUISITION_TYPE_NAME', title: 'Requisition Type'},
                {field: 'PROCESS_DEPT_NAME', title: 'Process Dept'},
                {field: 'PresentLocation', title: 'Present Location'},
                {field: 'status_name', title: 'Status'}
            ]]

    });
}

function RequisitionApprove() {
    var row = $('#RequisitionApproval').datagrid('getSelected');
    if (row) {
        location.replace('../requisition/requisition_approve.php?mode=update&search_id=' + row.REQUISITION_ID);
    }
}
function editMyRequisition() {
    var row = $('#allRequisition').datagrid('getSelected');

    if (row.REQUISITION_STATUS_ID > 1) {
        alert("Already Approve, You can't Edit");
        return;
    }
    if (row) {
        location.replace('../requisition/requisition_edit.php?mode=update&search_id=' + row.REQUISITION_ID);
    }
}

function viewMyRequisition() {
    var row = $('#allRequisition').datagrid('getSelected');

    if (row) {
        location.replace('../requisition/requisition_view.php?mode=view&search_id=' + row.REQUISITION_ID);
    }
}

function NewRequisition() {

    location.replace('../requisition/requisition_type.php');
}





function saveRequisition() {
    //alert(22);
    $('#requisition').form('submit', {
        url: "requisition_save.php?action=new",
        onSubmit: function() {
            return $(this).form('validate');
        },
        success: function(result) {
            //$('#showResult').html(result);
            var result = eval('(' + result + ')');
            if (result.success) {
                //alert(result);
                location.replace('requisition_view.php?mode=cinfirm&search_id=' + result.id);
            } else {
                $.messager.show({
                    title: 'Error',
                    msg: result.msg
                });
            }
        }
    });
}

function updateRequisition() {
    var SearchId = $('#search_id').val();

    $('#requisition').form('submit', {
        url: "requisition_save.php?action=update&search_id=" + SearchId,
        onSubmit: function() {
            return $(this).form('validate');
        },
        success: function(result) {
            //$('#showResult').html(result);
            var result = eval('(' + result + ')');
            if (result.success) {
                location.replace('requisition_view.php?mode=cinfirm&search_id=' + result.id);
            } else {
                $.messager.show({
                    title: 'Error',
                    msg: result.msg
                });
            }
        }
    });
}

function loadWindow() {
    $('#allRequisition').datagrid('load', {
        mode: ''
    });
}

function doSearch() {
    $('#allRequisition').datagrid('load', {
        ReqNo: $('input[name="ReqNo"]').val(),
        ReqStatus: $('select[name="ReqStatus"] option:selected').val(),
        ReqType: $('select[name="ReqType"] option:selected').val(),
        ProcessDeptId:$('select[name="ProcessDeptId"] option:selected').val(),
        FromDate: $('input[name="FromDate"]').val(),
        ToDate: $('input[name="ToDate"]').val(),
        mode: 'search'
    });
}








