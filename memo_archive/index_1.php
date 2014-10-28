<?php
include_once '../lib/DbManager.php';

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
function editUser(){
    var row = $('#dg').datagrid('getSelected');
    alert(row.InsRequisitionID);
    if (row){
       // $('#dlg').dialog('open').dialog('setTitle','Edit User');
       // $('#fm').form('load',row);
        //url = 'ins_requisition.php?id='+row.productid;
        window.location.href = 'ins_requisition.php?searchId='+row.InsRequisitionID+'&mode=edit';
    }
}
</script>

<div id="dlg" class="easyui-dialog add-dialog" style="padding:10px 20px" closed="true" buttons="#dlg-buttons">
    <form method="post" enctype="multipart/form-data" id="fm" novalidate autocomplete="off">
        <table class="table">
            <tr class='fitem'><td width="150"></td></tr>
            <tr class='fitem'><td width="150"></td></tr>
            <tr class='fitem'><td width="150"></td></tr>
            <tr class='fitem'>                        
                <td><strong>MEMO INFO REF</strong></td>
                <td><input type='text' name='MEMO_INFO_REF' id="MEMO_INFO_REF" class='easyui-validatebox' value='' size="20" onchange="onChange($(this), 'ref_info');"></td>
                <td><label id="ref_info"></label></td>
            </tr>

            <tr class='fitem'>                        
                <td><strong> SUBJECT :</strong></td>
                <td><input type="text" name="SUBJECT" id="SUBJECT" class="easyui-validatebox"  size="45" /></td>
            </tr>

            <tr class='fitem'>                        
                <td><strong> MEMO TYPE :</strong></td>
                <td colspan="5">
                    <input type="radio" id="board" name="MEMO_TYPE" value="board"> Board 
                    <input type="radio" id="manage" name="MEMO_TYPE" value="management"> Management                </td>
            </tr>
            
            <tr class='fitem boardtr'>
                <td><strong>APPROVAL AUTHORITY :</strong></td>
                <td id='td_PAYMENT_METHOD'>&nbsp;</td>
                <td id='td_PAYMENT_METHOD'>&nbsp;</td>
                <td id='td_PAYMENT_METHOD'>&nbsp;</td>
            </tr>

            <tr classfitem managetr'>
                <td><strong>EMPLOYEE :</strong></td>
                <td><input type='text' name='REMARKS2' id='REMARKS2' class='easyui-validatebox' size="20" /></td>
                <td>APPROVAL TYPE : </td>
                <td>
                    <select name="select">
                        <option value="1"> Initiator</option>
                        <option value="2"> Recommended</option>
                        <option value="3"> Approved</option>
                    </select> 
                    <input name="submit" type="submit" class="button" value="ADD MORE">
                </td>
            </tr>

            <tr class='fitem'>
                <td><strong>MEMO DATE :</strong></td>
                <td id='td_MEMO_DATE' colspan="5">
                <input type='text' name='MEMO_DATE' id='MEMO_DATE' class="easyui-datebox" value='' size="20">
                </td>
            </tr>
            
            <tr class='fitem'>
                <td><strong>MEMO REFERENCE :</strong></td>
                <td id='td_MEMO_DATE'>
                    <input type='text' name='MEMO_DATE' id='MEMO_DATE' class='easyui-validatebox' value='' size="20" /></td>
            </tr>
            
            <tr class='fitem'>
                <td><b>DIVISION :</b></td><td colspan="2"><select>
                        <option value="1">Procurement</option>
                        <option value="2">Legal</option>
                        <option value="3">Mercedes</option>
                        <option value="4">Audi</option>
                    </select>
                </td>
                <td>
                    <input type="submit" class="button" value="ADD MORE">
                </td>
            </tr>
            
            <tr class='fitem'>
                <td><strong>MEMO_CATEGORY :</strong></td>
                <td id='td_MEMO_CATEGORY'> 
                    <input type="radio" name="category" value="1"> Opex
                    <input type="radio" name="category" value="2"> Capex
                </td>
            </tr>
            
            <tr class='fitem'>
                <td><strong>BOARD NO.</strong></td>
                <td id='td_PAYMENT_METHOD'>
                    <input type='text' name='REMARKS22' id='REMARKS22' class='easyui-validatebox' size="20">
                </td>
                <td id='td_PAYMENT_METHOD'>DATE</td>
                <td id='td_PAYMENT_METHOD'>
                    <input type='text' name='REMARKS222' id='REMARKS222' class='easyui-validatebox' size="20" />
                </td>
            </tr>
            
            <tr class='fitem'><td><label><strong> DETAILS :</strong></label></td>
                <td id='td_APPROVED_AMOUNT'>
                    <textarea placeholder="Enter memo details here" rows="1" cols="20"></textarea></td></tr>
            <tr class='fitem'><td><label><strong>APPROVED_AMOUNT :</strong></label></td>
                <td id='td_APPROVED_AMOUNT'>
                    <input type='text' name='APPROVED_AMOUNT' id='APPROVED_AMOUNT' class='easyui-validatebox' value='' size="20" />
                </td>
            </tr>
            <tr class='fitem'><td><label><strong>REMARKS :</strong></label></td>
                <td id='td_REMARKS'>
                    <input type='text' name='REMARKS' id='REMARKS' class='easyui-validatebox' value='' size="20" />
                </td>
            </tr>

            <tr class='fitem'>
                <td><strong>PAYMENT_METHOD :</strong></td>
                <td id='td_PAYMENT_METHOD'><select name="select2">
                        <option value="volvo">Cash</option>
                        <option value="saab">Check</option>
                    </select></td>
            </tr>
            
            <tr class='fitem'>
                <td><strong>ATTACH TITLE </strong></td>
                <td id='td_PAYMENT_METHOD'><input type='text' name='REMARKS23' id='REMARKS23' class='easyui-validatebox' size="20" /></td>
                <td id='td_PAYMENT_METHOD'><strong>ATTACH FILE </strong></td>
                <td id='td_PAYMENT_METHOD'><input type="file" name="file"></td>
                <td id='td_PAYMENT_METHOD'><input name="submit2" type="submit" class="button" value="ADD MORE"></td>
            </tr>
            <tr class='fitem'><td><label></label></td>
                <td id='td_PAYMENT_METHOD'></td></tr>
        </table>
    </form>
</div>
<br/><br/>

<div id="dlg123" class="easyui-dialog add-dialog" style="padding:10px 20px" closed="true" buttons="#dlg-buttons123">
    <form method="post" enctype="multipart/form-data" id="fm" novalidate autocomplete="off">
        <table class="table" width="768">
            <tr class='fitem'>                        
                <td width='209'><strong>MEMO INFO REF</strong></td>
                <td colspan="7">mem-ref-123</td>
            </tr>
            <tr class='fitem'>                        
                <td width='209'><strong> SUBJECT :</strong></td>
                <td colspan="7">Subject of this memo </td>
            </tr>
            <tr class='fitem'>
                <td><strong>ATTACH TITLE </strong></td>
                <td id='td_PAYMENT_METHOD' colspan="2"><input type='text' name='REMARKS23' id='REMARKS23' class='easyui-validatebox' size="20" /></td>
                <td width="95" id='td_PAYMENT_METHOD'><strong>ATTACH FILE </strong></td>
                <td colspan="3" id='td_PAYMENT_METHOD'><input type="file" name="file"></td>
                <td width="129" id='td_PAYMENT_METHOD'><input name="submit2" type="submit" class="button" value="ADD MORE"></td>
            </tr>
            <tr class='fitem'>
                <td>&nbsp;</td>
                <td colspan="7">&nbsp;</td>
            </tr>
            <tr class='fitem'>                        
                <td width='209'><strong> PAYMENT FOR:</strong></td>
                <td colspan="7">
                    <input type="radio" id="board" name="boardManagement" value="board">
                    SUPPLIER 
                    <input type="radio" id="manage" name="boardManagement" value="management">
                    EMPLOYEE                </td>
            </tr>

            <tr class='fitem'>
                <td><strong>SUPPLIER/
                        EMPLOYEE ID </strong></td>
                <td id='td_MEMO_DATE' colspan="7"><input type='text' name='MEMO_INFO_REF2' id='MEMO_INFO_REF' class='easyui-validatebox' value='' size="20" /> 
                    M/S ICS System Solutions, Banani, Dhaka </td>
            </tr>
            <tr class='fitem'>
                <td width='209'><label><strong>Payment DATE :</strong></label></td>
                <td id='td_MEMO_DATE' colspan="7">
                    <input type='text' name='MEMO_DATE' id='MEMO_DATE' class='easyui-validatebox' value='' size="20" /></td>
            </tr>
            <tr class='fitem'>
                <td width='209'><label><strong>PAYMENT TYPE  :</strong></label></td>
                <td id='td_MEMO_DATE' colspan="7"><select name="select3">
                        <option value="Partial ">Partial </option>
                        <option value="Final">Final</option>
                    </select></td>
            </tr>
            <tr class='fitem'><td width='209'><label><strong>AMOUNT :</strong></label></td>
                <td id='td_MEMO_CATEGORY' colspan="3"><input type='text' name='MEMO_DATE2' id='MEMO_DATE2' class='easyui-validatebox' value='' size="20" /></td>
                <td width="31" id='td_MEMO_CATEGORY'>VAT</td>
                <td width="162" id='td_MEMO_CATEGORY'><input type='text' name='MEMO_DATE3' id='MEMO_DATE3' class='easyui-validatebox' value='' size="20" /></td>
                <td width="31" id='td_MEMO_CATEGORY'>TAX</td>
                <td id='td_MEMO_CATEGORY'><input type='text' name='MEMO_DATE4' id='MEMO_DATE4' class='easyui-validatebox' value='' size="20" /></td>
            </tr>
            <tr class='fitem'>
                <td><strong>PAYMENT MODE </strong></td>
                <td id='td_APPROVED_AMOUNT' colspan="7"><input type="radio" id="radio" name="boardManagement" value="board">
                    PAY ORDER
                    <input type="radio" id="radio2" name="boardManagement" value="management">
                    CB NO </td>
            </tr>
            <tr class='fitem'><td width='209'><label><strong> PAY ORDER/CB NO:</strong></label></td>
                <td id='td_APPROVED_AMOUNT' colspan="7"><input type='text' name='MEMO_DATE22' id='MEMO_DATE22' class='easyui-validatebox' value='' size="20" /></td></tr>
            <tr class='fitem'><td width='209'><label><strong>REST AMOUNT :</strong></label></td>
                <td id='td_APPROVED_AMOUNT' colspan="7">
                    <input type='text' name='APPROVED_AMOUNT' id='APPROVED_AMOUNT' class='easyui-validatebox' value='' size="20" /></td></tr>
            <tr class='fitem'><td width='209'><label><strong>ADDITIONAL AMOUNT  :</strong></label></td>
                <td id='td_REMARKS' colspan="7">
                    <input type='text' name='REMARKS' id='REMARKS' class='easyui-validatebox' value='' size="20" /></td></tr>
            <tr class='boardtr'>
                <td><strong>FORFEIT AMOUNT  :</strong></td>
                <td width="76" id='td_PAYMENT_METHOD'>&nbsp;</td>
                <td colspan="2" id='td_PAYMENT_METHOD'>&nbsp;</td>
                <td colspan="4" id='td_PAYMENT_METHOD'>&nbsp;</td>
            </tr>

            <tr class='fitem'><td width='209'><label></label></td>
                <td id='td_PAYMENT_METHOD' colspan="7">&nbsp;</td></tr>
        </table>
    </form>
</div>



<input type="hidden" name="object_name" id="object_name" value="<?php echo $object_name; ?>"/>
<input type="hidden" name="object_id" id="object_id" value="<?php echo $object_id; ?>"/>
<input type="hidden" name="columnsData" id="columnsData" value="<?php echo $columnsData; ?>"/>

<div class="easyui-layout" style="width:100%; height:400px;">  
    <div title="Search Option" data-options="region:'north',split:true, collapsed:true" title="West" style="height: 200px;">  
        <div class="easyui-accordion" data-options="fit:true,border:false">  
            <table>
                <tr>
                    <td>Product Name: <input type="text" class="" id="searchName" /></td>
                </tr>
            </table>
            <button class="easyui-linkbutton" onclick="onClick('searchName');" iconCls="icon-search">Search</button>
        </div>  
    </div>

    <div data-options="region:'south',split:true" style="height:50px;">Button Part</div>

    <div data-options="region:'east', split:true, collapsed:true" title="East" style="width:250px;">  
        <ul class="easyui-tree" data-options="url:'TreeJson.php', animate:true, dnd:true"></ul>  
    </div> 

    <div data-options="region:'west',split:true, collapsed:true" title="West" style="width:200px;">  
        <div class="easyui-accordion" data-options="fit:true,border:false">  
            <div title="Title1" style="padding:10px;">  
                content1  
            </div>  
            <div title="Title2" data-options="selected:true" style="padding:10px;">  
                content2  
            </div>  
            <div title="Title3" style="padding:10px">  
                content3  
            </div>  
        </div>  
    </div>

    <div data-options="region:'center'">  
        <div class="easyui-accordion" data-options="fit:true,border:false,plain:true">  
            <div title="Division List">  
                <table class="" id="dataGrid" data-options="fit:true,fitColumns:true"></table> 
            </div>  

        </div>  
    </div>  
</div>
<div id="dlg-buttons">
    <a href="#" class="easyui-linkbutton" iconCls="icon-ok" onclick="saveUser();">Save</a>
    <a href="#" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close');">Cancel</a>
</div>

<div id="dlg-buttons123">
    <a href="#" class="easyui-linkbutton" iconCls="icon-ok" onclick="saveUser();">Save</a>
    <a href="#" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg123').dialog('close');">Cancel</a>
</div>


<div id="toolbar" style="padding:5px;height:auto">  
    <div id="toolbar">
        <a href="#" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newUser();">Add Memo</a>
        <a href="#" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editUser();">Edit Division</a>
        <a href="#" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="removeUser();">Remove Division</a>
        <a href="#" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newUser1234();">Transaction</a>
    </div>

</div>

<?php
include '../body/footer.php';
?>