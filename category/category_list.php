<?php
include '../lib/DbManager.php';
include '../body/header.php';
?>
<br/><br/>



<script type="text/javascript">
    var url;
    $(document).ready(function() {

        $('#dg').datagrid({
            title: 'Category List',
            iconCls: 'icon-edit',
            pagination: 'true',
            toolbar: "#toolbar",
            singleSelect: true,
            pageSize: 10,
            pagePosition: 'pos',
            idField: 'CATEGORY_ID',
            url: 'category_list_getall.php',
            columns: [[
                    {field: 'CATEGOTY_NAME', title: 'Category Name'},
                    {field: 'PROCESS_DEPARTMENT_ID', title: 'Process Department Id'},
                    {field: 'DESCRIPTION', title: 'Description'},
                    {field: 'CREATED_BY', title: 'Created By '},
                    {field: 'CREATED_DATE', title: 'Created Date'}

                ]]

        });

    });



    function newUser() {
        $('#dlg').dialog('open').dialog('setTitle', 'Add Category');
        $('#fm').form('clear');
        url = 'category_save.php';
    }
    function editUser() {
        var row = $('#dg').datagrid('getSelected');
        //alert(row.productid);
        if (row) {
            $('#dlg').dialog('open').dialog('setTitle', 'Edit Category');
            $('#fm').form('load', row);
            url = 'category_update.php?category_id=' + row.CATEGORY_ID;
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
                //alert(result);
                var result = eval('(' + result + ')');
                if (result.success) {
                    $('#dlg').dialog('close');		// close the dialog
                    $('#dg').datagrid('reload');	// reload the user data
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
        var row = $('#dg').datagrid('getSelected');
        if (row) {
            $.messager.confirm('Confirm', 'Are you sure you want to remove this user?', function(r) {
                if (r) { 
                    $.post(
                    'category_remove.php', 
                    {category_id: row.CATEGORY_ID}, 
                    function(result) {
                        //alert(row.id);
                        
                        if (result.success) {
                            
                            $('#dg').datagrid('reload');	// reload the user data
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


<div class="table-top">Category List</div>

<table id="dg"></table>

<div id="toolbar" style="padding:5px;height:auto">  


    <div id="toolbar">
        <a href="#" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newUser()">Add Category</a>
        <a href="#" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editUser()">Edit Category</a>
        <a href="#" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="removeUser()">Remove Category</a>
    </div>


</div>





<?php include './category_add.php'; ?>