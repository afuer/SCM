<?php
include_once '../lib/DbManager.php';

$id= getParam('id');
//$object_name = 'memo_archive';
/* $columnsData = "{field: 'MEMO_TYPE', title: 'MEMO_TYPE'},
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
 */
//$object_id = strtoupper($object_name) . '_ID';
include '../body/header.php';


if ($_POST) {
    $db = new DbManager();
    $db->OpenDb();
    $memoArchiveID = NextId('memo_archive', 'MEMO_ARCHIVE_ID');
    $db->CloseDb();

    

    $db = new DbManager();
    $db->OpenDb();
    
    $selectSQL = "SELECT MEMO_TYPE, MEMO_DATE, MEMO_INFO_REF, MEMO_REF, BOARD_NO, BOARD_DATE, MEMO_DETAILS, MEMO_CATEGORY, APPROVED_AMOUNT, REMARKS,
    PAYMENT_METHOD, MEMO_SUBJECT, CREATED_BY, CREATED_DATE FROM memo_archive WHERE MEMO_ARCHIVE_ID='$id'";

    $memoObj = find($selectSQL);

    $db->CloseDb();
    
    $memoType = $memoObj->MEMO_TYPE;
    $memoDate = $memoObj->MEMO_DATE;
    $memoInfoRef = $memoObj->MEMO_INFO_REF;
    $paymentMethod = $memoObj->PAYMENT_METHOD;
    $memoDetails = $memoObj->MEMO_DETAILS;
    $memoCategory = $memoObj->MEMO_CATEGORY;
    $approveAmount = $memoObj->APPROVED_AMOUNT;
    $remarks = $memoObj->REMARKS;
    $payMethod = $memoObj->PAYMENT_METHOD;
    $memoSub = $memoObj->MEMO_SUBJECT;
    $memoRef = $memoObj->MEMO_REF;
    $boardDate = $memoObj->BOARD_DATE;
    $boardNo = $memoObj->BOARD_NO;
    
}


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
<script src="../public/uploadify/jquery.uploadify-3.1.min.js"></script>
<link href="../public/uploadify/uploadify.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="../public/js/jquery.calculation.js"></script>







<div id="p" class="easyui-panel" title="Add Memo" style="width:1000px;height:500px;padding:10px;">  
    <form method="post" enctype="multipart/form-data" id="fm" novalidate autocomplete="off">
        <table class="table">
            <tr class='fitem'>                        
                <td width="120"><strong>MEMO INFO REF</strong></td>
                <td><input type='text' name='MEMO_INFO_REF' class='easyui-validatebox' value='<?php echo $a;?>' size="20" onchange="onChange($(this), 'ref_info');"></td>
                <td width="150"><label id="ref_info"></label></td>
            </tr>

            <tr class='fitem'>                        
                <td><strong> SUBJECT :</strong></td>
                <td><input type="text" name="MEMO_SUBJECT" class="easyui-validatebox"  value='<?php echo $a;?>' size="45" /></td>
            </tr>

            <tr class='fitem'>                        
                <td><strong> MEMO TYPE :</strong></td>
                <td colspan="5">
                    <?php echo $a;?>
                    <!--
                    <input type="radio" id="board" name="MEMO_TYPE" value="board"> Board 
                    <input type="radio" id="manage" name="MEMO_TYPE" value="management"> Management  -->       
                </td>
            </tr>

            <tr class='fitem' id="boardtr">
                <td><strong>BOARD NO.</strong></td>
                <td>
                    <input type='text' name='BOARD_NO' id='REMARKS22' class='easyui-validatebox' size="20" value='<?php echo $a;?>'>
                </td>
                <td><strong>DATE:</strong></td>
                <td>
                    <input type='text' name="BOARD_DATE" class='easyui-datebox' size="20">
                </td>
            </tr>
<!--
            <tr id="managetr">
                <td colspan="5">
                    <table id="productTab" class="ui-state-default">
                        <thead>
                        <th width="10">SL</th>
                        <th>EMPLOYEE</th>
                        <th width="100">APPROVAL TYPE</th>
                        <th width="50">ACTION</th>
                        </thead>
                        <tbody
                            <tr>
                                <td><?php echo++$proSL; ?></td>
                                <td><input style="width:100%" name="price[]" type="text" class="price number" id="price_product" value="" /></td>
                                <td><select name="select">
                                        <option value="Initiator">Initiator</option>
                                        <option value="Recommended">Recommended</option>
                                        <option value="Approved">Approved</option>
                                    </select></td>
                                <td><div class="remove">Remove</div></td>
                            </tr>
                        </tbody>
                    </table>
                    <a href="#" class="easyui-linkbutton" iconCls="icon-add" plain="true" title="productTab" onclick="RemoveTableTr('productTab');">Add</a>
                </td>
            </tr>
-->



            <!--
            <td><strong>EMPLOYEE :</strong> </td>
            <td><input type='text' name='EMPLOYEE_ID' class='easyui-validatebox'></td>
            <td><strong>APPROVAL TYPE:</strong></td>
            <td>
                <select name="select">
                    <option value="Initiator">Initiator</option>
                    <option value="Recommended">Recommended</option>
                    <option value="Approved">Approved</option>
                </select> 
            </td>
            <td>
                <input name="submit" type="submit" class="button" value="ADD MORE">
            </td> -->


            <tr class='fitem'>
                <td><strong>MEMO DATE :</strong></td>
                <td>
                    <input type='text' name='MEMO_DATE' class="easyui-datebox" value='<?php echo $a;?>' size="20">
                </td>
            </tr>

            <tr class='fitem'>
                <td><strong>MEMO REFERENCE :</strong></td>
                <td><input type='text' name='MEMO_REF' class='easyui-validatebox' value='<?php echo $a;?>' size="20" /></td>
            </tr>

            <tr class='fitem'>
                <td><strong>DIVISION :</strong></td>
                <td colspan="2">
                    <select name="division">
                        <option value="1">Procurement</option>
                        <option value="2">Legal</option>
                    </select>
                </td>
                <td>
                    <input type="submit" class="button" value="ADD MORE">
                </td>
            </tr>

            <tr class='fitem'>
                <td><strong>MEMO_CATEGORY :</strong></td>
                <td> 
                    <input type="radio" name="MEMO_CATEGORY" value="Opex"> Opex
                    <input type="radio" name="MEMO_CATEGORY" value="Capex"> Capex
                </td>
            </tr>



            <tr class='fitem'>
                <td><strong> DETAILS :</strong></td>
                <td><textarea placeholder="Enter memo details here" rows="1" cols="20" name="MEMO_DETAILS"><?php echo $a;?></textarea></td>
            </tr>
            <tr class='fitem'>
                <td><strong>APPROVED_AMOUNT :</strong></td>
                <td><input type='text' name='APPROVED_AMOUNT' id='APPROVED_AMOUNT' class='easyui-validatebox' value='<?php echo $a;?>' size="20"></td>
            </tr>
            <tr class='fitem'>
                <td><strong>REMARKS :</strong></td>
                <td><input type='text' name='REMARKS' id='REMARKS' class='easyui-validatebox' value='<?php echo $a;?>' size="20"></td>
            </tr>

            <tr class='fitem'>
                <td><strong>PAYMENT_METHOD :</strong></td>
                <td>
                    <select name="PAYMENT_METHOD">
                        <option value="single">Single</option>
                        <option value="installment">Installment</option>
                    </select>
                </td>
            </tr>
            <tr class='fitem'>
                <td>Attachment Tittle</td>
                <td><input name="textfield" type="text" size="60" id="AttachmentDetails" placeholder="Title"/></td>
                <td><input type='file' class='uploadify-button' id='file_upload' />
                    <input id="file_upload_done" class="text_field_display" type="text" />
                </td>
            </tr>
            <tr>
                <td colspan="5">
                    <table class="ui-state-default" id="attachment_tab">
                        <thead>
                        <th width="20">SL</th>
                        <th align="left">Attachment Tittle</th>
                        <th width="100" align="right">Action</th>
                        </thead>
                        <tbody></tbody>
                    </table>
                </td>
            </tr>
        </table>
        <input type="submit" name="save" value="save" class="button">
    </form>
</div>

<?php
include '../body/footer.php';
?>