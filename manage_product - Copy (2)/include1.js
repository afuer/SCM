
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
        pageSize: 10,
        pagePosition: 'pos',
        idField: 'PRODUCT_ID',
        url: 'get.php',
        columns: [[
                {field: 'PRODUCT_CODE', title: 'Product Code'},
                {field: 'PRODUCT_NAME', title: 'Product Name'},
                {field: 'quantities', title: 'Req Qty', align: 'right',
                    formatter: function(value, row, index) {
                        var ProductId = row.PRODUCT_ID;
                        return '<a href="approve_store_product.php?productid=' + ProductId + '&details_status=1&approval_status=-1"  target="_blank"><span style="font-weight:bold;">' + row.quantities + '</span></a>';
                    }
                },
                {field: 'stock', title: 'Stock Qty', align: 'right'},
                {field: 'allocated', title: 'Allocated Qty', align: 'right'},
                {field: 'availableqty', title: 'Available Qty', align: 'right',
                    formatter: function(value, row, index) {
                        var stock = row.stock === null ? 0 : row.stock;
                        var allocated = row.allocated === null ? 0 : row.allocated;
                        var availble = stock - allocated;
                        return '<a href="?id=' + row.PRODUCT_ID + '" onclick="link(this)">' + availble + '</a>';
                    }
                }
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