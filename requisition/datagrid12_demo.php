<?php
include '../lib/DbManager.php';
include("../body/header.php");
?>

<script type="text/javascript">
    var products = [
        {productid: 'FI-SW-01', name: 'Koi'},
        {productid: 'K9-DL-01', name: 'Dalmation'},
        {productid: 'RP-SN-01', name: 'Rattlesnake'},
        {productid: 'RP-LI-02', name: 'Iguana'},
        {productid: 'FL-DSH-01', name: 'Manx'},
        {productid: 'FL-DLH-02', name: 'Persian'},
        {productid: 'AV-CB-01', name: 'Amazon Parrot'}
    ];
    $(function() {
        $('#productGrid').datagrid({
            title: 'Editable DataGrid',
            iconCls: 'icon-edit',
            rownumbers: 'true',
            width: 780,
            height: 250,
            singleSelect: true,
            idField: 'PRODUCT_ID',
            url: 'requisition_edit_product_get.php?search_id=48',
            columns: [[
                    {field: 'PRODUCT_NAME', title: 'Product Name', width: 250,
                        formatter: function(value) {
                            for (var i = 0; i < products.length; i++) {
                                if (products[i].productid === value)
                                    return products[i].name;
                            }
                            return value;
                        },
                        editor: {
                            type: 'combobox',
                            options: {
                                valueField: 'productid',
                                textField: 'name',
                                data: products,
                                required: true
                            }
                        }
                    },
                    {field: 'QTY', title: 'Qty', width: 180, editor: 'text'},
                    {field: 'action', title: 'Action', width: 120, align: 'center',
                        formatter: function(value, row, index) {
                            if (row.editing) {
                                var s = '<a href="#" onclick="saverow(this)">Save</a> ';
                                var c = '<a href="#" onclick="cancelrow(this)">Cancel</a>';
                                return s + c;
                            } else {
                                var e = '<a href="#" onclick="editrow(this)">Edit</a> ';
                                var d = '<a href="#" onclick="deleterow(this)">Delete</a>';
                                return e + d;
                            }
                        }
                    }
                ]],
            onBeforeEdit: function(index, row) {
                row.editing = true;
                updateActions(index);
            },
            onAfterEdit: function(index, row) {
                row.editing = false;
                updateActions(index);
            },
            onCancelEdit: function(index, row) {
                row.editing = false;
                updateActions(index);
            }
        });
    });
    function updateActions(index) {
        $('#productGrid').datagrid('updateRow', {
            index: index,
            row: {}
        });
    }
    function getRowIndex(target) {
        var tr = $(target).closest('tr.datagrid-row');
        return parseInt(tr.attr('datagrid-row-index'));
    }
    function editrow(target) {
        $('#productGrid').datagrid('beginEdit', getRowIndex(target));
    }
    function deleterow(target) {
        $.messager.confirm('Confirm', 'Are you sure?', function(r) {
            if (r) {
                $('#productGrid').datagrid('deleteRow', getRowIndex(target));
            }
        });
    }
    function saverow(target) {
        $('#productGrid').datagrid('endEdit', getRowIndex(target));
    }
    function cancelrow(target) {
        $('#productGrid').datagrid('cancelEdit', getRowIndex(target));
    }
    function insert() {
        var row = $('#productGrid').datagrid('getSelected');
        if (row) {
            var index = $('#productGrid').datagrid('getRowIndex', row);
        } else {
            index = 0;
        }
        $('#productGrid').datagrid('insertRow', {
            index: index,
            row: {
                status: 'P'
            }
        });
        $('#productGrid').datagrid('selectRow', index);
        $('#productGrid').datagrid('beginEdit', index);
    }
</script>

<div class="easyui-layout" style="width:1100px; margin: auto; height:550px;">  
    <div data-options="region:'center'" Title='Requisition Edit' style="padding: 10px 10px; background-color:white; "> 

        <a href="#" class="easyui-linkbutton" onclick="insert()">Insert Row</a>

        <table id="productGrid"></table>
    </div>
</div>

</body>
</html>