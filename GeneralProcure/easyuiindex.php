<?php
include './index.php';
include '../body/header.php';
?>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="keywords" content="jquery,ui,easy,easyui,web">
<meta name="description" content="easyui help you build your web page easily!">

<link rel="stylesheet" type="text/css" href="themes/default/easyui.css">
<link rel="stylesheet" type="text/css" href="themes/icon.css">
<link rel="stylesheet" type="text/css" href="themes/default/demo.css">

<script type="text/javascript" src="public/jQuery-v1.7.2.js"></script>
<script type="text/javascript" src="public/js/jquery.easyui.min.js"></script>
<script type="text/javascript" src="public/js/jquery.treegrid.js"></script>

<script type="text/javascript">
    var url;
    $(document).ready(function() {

        $('#dg').datagrid({
            title: 'Display Stock Item',
            iconCls: 'icon-edit',
            pagination: 'true',
            toolbar: "#toolbar",
            singleSelect: true,
            pageSize: 10,
            pagePosition: 'pos',
            idField: 'itemid',
            url: 'display_item_stock.php',
            columns: [[
                    {field: 'productid', title: 'Product ID', sortable: "true"},
                    {field: 'item_code', title: 'Item Code'},
                    {field: 'model', title: 'Model'},
                    {field: 'model', title: 'Model'},
                    {field: 'model', title: 'Model'},
                    {field: 'model', title: 'Model', width: 50},
                    {field: 'action', title: 'Action', width: 80, align: 'center',
                        formatter: function(value, row, index) {

                            //alert(row.productid);
                            if (row.editing) {
                                var s = '<a href="#" onclick="saverow(this)">Save</a> ';
                                var c = '<a href="#" onclick="cancelrow(this)">Cancel</a>';
                                return s + c;
                            } else {
                                var e = '<a href="?id=' + row.productid + '" onclick="link(this)">Edit</a> | ';
                                var d = '<a href="#" onclick="deleterow(this)">Delete</a>';
                                return e + d;
                            }
                        }
                    }
                ]]

        });

    });



    function newUser() {
        $('#dlg').dialog('open').dialog('setTitle', 'New User');
        $('#fm').form('clear');
        url = 'save_user.php';
    }
    function editUser() {
        var row = $('#dg').datagrid('getSelected');
        alert(row.productid);
        if (row) {
            $('#dlg').dialog('open').dialog('setTitle', 'Edit User');
            $('#fm').form('load', row);
            url = 'update_user.php?id=' + row.productid;
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
                    $.post('remove_user.php', {id: row.id}, function(result) {
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
</head>


<body>
    <h2>Basic CRUD Application</h2>

    <table id="dg"></table>

    <div id="toolbar" style="padding:5px;height:auto">  


        <div id="toolbar">
            <a href="#" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newUser()">New User</a>
            <a href="#" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editUser()">Edit User</a>
            <a href="#" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="removeUser()">Remove User</a>
        </div>
        <div>  
            Date From: <input class="easyui-datebox" style="width:80px">  
            To: <input class="easyui-datebox" style="width:80px">  
            Language:   
            <input class="easyui-combobox" style="width:100px"  
                   url=""  
                   valueField="id" textField="text">  
            <a href="#" class="easyui-linkbutton" iconCls="icon-search">Search</a>  
        </div>

    </div> 
    <div id="dlg" class="easyui-dialog add-dialog" style="padding:10px 20px" closed="true" buttons="#dlg-buttons">

        <form id="fm" method="post" novalidate autocomplete="off">
            <fieldset>
                <legend><div class="ftitle">User Information</div></legend>

                <table>
                    <tr class="fitem">
                        <td><label>First Name:</label></td>
                        <td><input name="firstname" class="easyui-validatebox" required="true"></td>
                        <td><label>First Name:</label></td>
                        <td><input name="item_code" class="easyui-validatebox" required="true"></td>
                        <td><label>First Name:</label></td>
                        <td><input name="firstname" class="easyui-validatebox" required="true"></td>
                    </tr>
                    <tr class="fitem">
                        <td><label>Last Name:</label></td>
                        <td><input name="lastname" class="easyui-validatebox" required="true"></td>
                    </tr>
                    <tr class="fitem">
                        <td><label>Phone:</label></td>
                        <td><input name="phone"></td>
                    </tr>
                    <tr class="fitem">
                        <td><label>Email:</label></td>
                        <td><input name="email" class="easyui-validatebox" validType="email"></td>
                    </tr>

                </table>
            </fieldset>
        </form>
    </div>
    <div id="dlg-buttons">
        <a href="#" class="easyui-linkbutton" iconCls="icon-ok" onclick="saveUser()">Save</a>
        <a href="#" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')">Cancel</a>
    </div>
</body>
</html>