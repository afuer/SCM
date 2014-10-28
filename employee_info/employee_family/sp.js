
var url;
$(document).ready(function(){
                    
    $('#dg').datagrid({
        title:'Display Stock Item',
        iconCls:'icon-edit',
        pagination:'true',
        toolbar:"#toolbar",
        singleSelect:true,
        pageSize:10,  
        pagePosition:'pos',
                    
        idField:'itemid',
        url:'display_item_stock.php',
                   
        columns:[[
        {
            field:'productid',
            title:'Product ID', 
            sortable:"true"
        },

        {
            field:'item_code',
            title:'Item Code'
        },

        {
            field:'model',
            title:'Model'
        },

        {
            field:'model',
            title:'Model'
        },

        {
            field:'model',
            title:'Model'
        },

        {
            field:'model',
            title:'Model', 
            width:50
        },

        {
            field:'action',
            title:'Action',
            width:80,
            align:'center',
            formatter:function(value,row,index){
                                    
                //alert(row.productid);
                if (row.editing){
                    var s = '<a href="#" onclick="saverow(this)">Save</a> ';
                    var c = '<a href="#" onclick="cancelrow(this)">Cancel</a>';
                    return s+c;
                } else {
                    var e = '<a href="?id='+row.productid+'" onclick="link(this)">Edit</a> | ';
                    var d = '<a href="#" onclick="deleterow(this)">Delete</a>';
                    return e+d;
                }
            }
        }
        ]]
           
    });
                
});
            
            
         
function newUser(){
    $('#dlg').dialog('open').dialog('setTitle','New User');
    $('#fm').form('clear');
    url = 'save_user.php';
}



function editUser(){
    var row = $('#dg').datagrid('getSelected');
    alert(row.productid);
    if (row){
        $('#dlg').dialog('open').dialog('setTitle','Edit User');
        $('#fm').form('load',row);
        url = 'update_user.php?id='+row.productid;
    }
}

function saveUser(){
    //alert(22);
    $('#fm').form('submit',{
        url: url,
        onSubmit: function(){
            return $(this).form('validate');
        },
        success: function(result){
            //alert(result);
            var result = eval('('+result+')');
            if (result.success){
                $('#dlg').dialog('close'); // close the dialog
                $('#dg').datagrid('reload'); // reload the user data
            } else {
                $.messager.show({
                    title: 'Error',
                    msg: result.msg
                });
            }
        }
    });
}

function removeUser(){
    var row = $('#dg').datagrid('getSelected');
    if (row){
        $.messager.confirm('Confirm','Are you sure you want to remove this user?',function(r){
            if (r){
                $.post('remove_user.php',{
                    id:row.id
                },function(result){
                    if (result.success){
                        $('#dg').datagrid('reload');	// reload the user data
                    } else {
                        $.messager.show({	// show error message
                            title: 'Error',
                            msg: result.msg
                        });
                    }
                },'json');
            }
        });
    }
}
      