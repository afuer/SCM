
$(document).ready(function() {

    $('#tab').tabs({
        border: true,
        onSelect: function(title, index) {
            //alert(index);
            if (index === 1) {
                StoreItemRequisitionApprovalList();
            }
            if (index === 0) {
                StoreItemRequisitionList();
            }

        }
    });
});


function StoreItemRequisitionList() {
    $('#StoreItemRequisitionList').datagrid({
        iconCls: 'icon-edit',
        pagination: 'true',
        toolbar: "#toolbar",
        rownumbers: 'true',
        singleSelect: true,
        pageSize: 20,
        pagePosition: 'pos',
        idField: 'PRODUCT_ID',
        url: 'get.php',
        columns: [[
                {field: 'REQUISITION_NO', title: 'Requisition No',
                    formatter: function(value, row, index) {
                        var reqId = row.REQUISITION_ID;
                        return '<a href="approve_store_product.php?req_id=' + reqId + '&details_status=1&approval_status=-1"  target="_blank"><span style="font-weight:bold;">' + row.REQUISITION_NO + '</span></a>';
                    }
                },
                {field: 'REQUISITION_DATE', title: 'Req Date'},
                {field: 'branch_dept', title: 'Branch/Dept'},
                {field: 'employeeName', title: 'Requisition From'},
                {field: 'STATUS_NAME', title: 'Requisition Status'}


            ]]

    });
}

function StoreItemRequisitionApprovalList() {
    $('#StoreItemRequisitionApprovalList').datagrid({
        iconCls: 'icon-edit',
        pagination: 'true',
        toolbar: "#toolbar",
        rownumbers: 'true',
        singleSelect: true,
        pageSize: 20,
        pagePosition: 'pos',
        idField: 'PRODUCT_ID',
        url: 'get.php?mode=pending',
        columns: [[
                {field: 'REQUISITION_NO', title: 'Requisition No',
                    formatter: function(value, row, index) {
                        var reqId = row.REQUISITION_ID;
                        return '<a href="store_product_for_approve.php?req_id=' + reqId + '&details_status=1&approval_status=-1"  target="_blank"><span style="font-weight:bold;">' + row.REQUISITION_NO + '</span></a>';
                    }
                },
                {field: 'REQUISITION_DATE', title: 'Req Date'},
                {field: 'branch_dept', title: 'Branch/Dept'},
                {field: 'employeeName', title: 'Requisition From'},
                {field: 'STATUS_NAME', title: 'Requisition Status'}


            ]]

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