<?php
include_once '../lib/DbManager.php';
include 'include.php';
$db = new DbManager();
$db->OpenDb();
$memoTypeList = rs2array(query("SELECT memo_type, memo_type FROM memo_type"));
$db->CloseDb();
/*
  $object_name = 'memo_archive';
  $columnsData = "{field: 'MEMO_TYPE', title: 'MEMO_TYPE'},
  {field: 'MEMO_TYPE', title: 'MEMO_TYPE'},
  {field: 'MEMO_REF', title: 'MEMO_REF'},
  {field: 'MEMO_CATEGORY', title: 'MEMO_CATEGORY'},
  {field: 'MEMO_DETAILS', title: 'MEMO_DETAILS'},
  {field: 'APPROVED_AMOUNT', title: 'APPROVED_AMOUNT'},
  {field: 'REMARKS', title: 'REMARKS'},
  {field: 'PAYMENT_METHOD', title: 'PAYMENT_METHOD'},
  {field: 'MEMO_SUBJECT', title: 'MEMO_SUBJECT'},
  {field: 'MEMO_DETAILS', title: 'MEMO_DETAILS'},
  {field: 'MEMO_INFO_REF', title: 'MEMO_INFO_REF'},
  {field: 'REMARKS', title: 'REMARKS'}";

  $object_id = strtoupper($object_name) . '_ID';
 * 
 */
include '../body/header.php';

$db = new DbManager();
$db->OpenDb();

$db->CloseDb();


include_once '../body/body_header.php';
?>

<script type="text/javascript">
    $(document).ready(function() {
        $('#boardtr').hide();
        $('#managetr').hide();

        $('#board').click(function() {

            $('#managetr').hide();
            $('#boardtr').show();
        });
        $('#manage').click(function() {
            $('#boardtr').hide();
            $('#managetr').show();
        });
    });
</script>
<script>
    function myformatter(date) {
        var y = date.getFullYear();
        var m = date.getMonth() + 1;
        var d = date.getDate();
        return y + '-' + (m < 10 ? ('0' + m) : m) + '-' + (d < 10 ? ('0' + d) : d);
    }
    function myparser(s) {
        if (!s)
            return new Date();
        var ss = (s.split('-'));
        var y = parseInt(ss[0], 10);
        var m = parseInt(ss[1], 10);
        var d = parseInt(ss[2], 10);
        if (!isNaN(y) && !isNaN(m) && !isNaN(d)) {
            return new Date(y, m - 1, d);
        } else {
            return new Date();
        }
    }

    function editUser222() {
        var row = $('#dg').datagrid('getSelected');
        if (row) {
            window.location.href = 'nextPage.php?id=' + row.MEMO_ARCHIVE_ID;
            //alert('row.MEMO_ARCHIVE_ID');
        }
    }
    
    
    function removeUser() {
        var row = $('#dg').datagrid('getSelected');
        if (row) {
            window.location.href = 'RoughPurpose_view.php?id=' + row.MEMO_ARCHIVE_ID;
            //alert('row.MEMO_ARCHIVE_ID');
        }
    }

    function newUser() {
        window.location.href = 'RoughPurpose.php';
    }

</script>

<div id="dlg" class="easyui-dialog add-dialog" style="padding:10px 20px" closed="true" buttons="#dlg-buttons">

</div>
<div id="toolbar">  
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newUser()">New User</a>  
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editUser()">Edit User</a>  
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="destroyUser()">Remove User</a>  
    </div>  



 <div id="toolbar" style="padding:5px;height:auto">
        <a href="RoughPurpose.php" class="easyui-linkbutton" iconCls="icon-add" plain="true" target='_blank'>Add Memo</a>
        <a href="#" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editUser222();">Transaction</a>
        <a href="#" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="removeUser();">View</a>
        <a href="#" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newUser1234();">Transaction 1</a>
    </div>
<div class="easyui-layout" style="width:100%; height:400px;">  
    <div data-options="region:'east', split:true, collapsed:true" title="East" style="width:250px;">  
    </div> 
    <div data-options="region:'center'"> 

        <div class="easyui-accordion" data-options="fit:true,border:true,plain:false">  
            <div title="Memo List">  
                <div>
                    <fieldset>
                        <legend>Search</legend>
                        <form>
                        <table>
                            <tr>
                                <td>Memo Type  :</td>
                                <td><?php comboBox('memoType', $memoTypeList, $memoType, TRUE); ?></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td width='100'>Memo From  :</td>
                                <td width='200'><input class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" name='memo_from'></input></td>
                                <td width='100'>Memo To  :</td>
                                <td width='100'><input class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" name='memo_to'></input></td>
                            </tr>
                        </table>
                        <input type='submit' name='search' value='search' class="button">
                        </form>
                    </fieldset>
                    <br />
                </div>
                       <!-- <table class="" id="dataGrid" data-options="fit:true,fitColumns:true"></table> -->
                <?php makeGrid('memo_archive', 1200, 500); ?>
<table id="tt" class="easyui-datagrid" style="width:400px;height:auto;">  
    <thead>  
        <tr>  
            <th field="name1" width="50">Col 1</th>  
            <th field="name2" width="50">Col 2</th>  
            <th field="name3" width="50">Col 3</th>  
            <th field="name4" width="50">Col 4</th>  
            <th field="name5" width="50">Col 5</th>  
            <th field="name6" width="50">Col 6</th>  
        </tr>                            
    </thead>    
</table>  
                
            </div>  

        </div>  
    </div>  
</div>
<div id="dlg-buttons">
    <a href="#" class="easyui-linkbutton" iconCls="icon-ok" onclick="saveUser();">Save</a>
    <a href="#" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close');">Cancel</a>
</div>

<?php
include '../body/footer.php';
?>