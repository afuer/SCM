
$(document).ready(function() {
    StoreItemRequisitionList();
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
                {field: 'employeeName', title: 'Created By'}


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