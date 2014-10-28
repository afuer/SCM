 
<div id="dlg" class="easyui-dialog add-dialog" style="padding:10px 20px" closed="true" buttons="#dlg-buttons">

    <form id="fm" method="post" novalidate autocomplete="off">
        <fieldset>
            <legend><div class="ftitle">Category Information</div></legend>

            <table>
                <tr class="fitem">
                    
                    <td><label>Category Name:</label></td>
                    <td><input name="CATEGOTY_NAME" class="easyui-validatebox" required="true" size="45"/></td>
                </tr>
                <tr>
                    <td valign="top"><label>Category Department :</label></td>
                    <td > <input type="text" name="PROCESS_DEPARTMENT_ID" class="easyui-validatebox"  size="45" /> </td>
                </tr>
                <tr>
                    <td><label>Category Description:</label></td>
                    <td> 
                        <textarea name="DESCRIPTION" class="easyui-validatebox"  size="30"> </textarea>
                       
                    </td>
                </tr>




            </table>
        </fieldset>
    </form>
</div>
<div id="dlg-buttons">
    <a href="#" class="easyui-linkbutton" iconCls="icon-ok" onclick="saveUser()">Save</a>
    <a href="#" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')">Cancel</a>
</div>