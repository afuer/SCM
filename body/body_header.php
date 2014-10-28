<script type="text/javascript">
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
            pageSize: 10,
            pagePosition: 'pos',
            idField: objectId,
            url: objectName + '_get.php',
            columns: [[<?php echo $columnsData; ?>]]

        });
    }

    function loadGrid(tableId) {
        objectName = $('#object_name').val();
        objectId = $('#object_id').val();
        //columnsData = $('#columnsData').val();
        //console.log(columnsData);


        $('#' + tableId).datagrid({
            //title: 'Category List',
            iconCls: 'icon-edit',
            pagination: 'true',
            toolbar: "#toolbar",
            rownumbers: 'true',
            singleSelect: true,
            pageSize: 10,
            pagePosition: 'pos',
            idField: objectId,
            url: objectName + '_get.php',
            columns: [[<?php echo $columnsData; ?>]]

        });
    }





    function newUser() {
        $('#dlg').dialog('open').dialog('setTitle', 'Add ' + objectName);
        $('#fm').form('clear');
        url = objectName + '_save.php?mode=new';
    }
    function editUser() {
        var row = $('#dataGrid').datagrid('getSelected');
        //alert(row.productid);
        if (row) {
            $('#dlg').dialog('open').dialog('setTitle', 'Edit ' + objectName);
            $('#fm').form('load', row);
            url = objectName + '_save.php?mode=""&search_id=' + row.<?php echo $object_id; ?>;
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
                alert(result);
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
                            {search_id: row.<?php echo $object_id; ?>},
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




</script>
