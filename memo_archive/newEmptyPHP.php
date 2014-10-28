<?php 
include_once '../lib/DbManager.php';
include '../body/header.php';
?>



<div id="p" class="easyui-panel" title="Add Memo" style="width:1000px;height:500px;padding:10px;">
    <form method="post" enctype="multipart/form-data" id="fm" novalidate autocomplete="off">
        <table class="table" width="768">
            <tr class='fitem'>                        
                <td><strong>MEMO INFO REF</strong></td>
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
<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 
 */

?>
