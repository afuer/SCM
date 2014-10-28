<?php
include_once '../lib/DbManager.php';
//$db = new DbManager();
//$db->OpenDb();
$memoRef = 'memo ref ' . NextId('memo_archive', 'MEMO_ARCHIVE_ID');
$divList = rs2array(query("SELECT cc.COST_CENTER_CODE, cc.COST_CENTER_CODE, cc.COST_CENTER_NAME, dn.DIVISION_NAME FROM cost_center cc
LEFT OUTER JOIN division dn ON dn.DIVISION_ID = cc.DIVISION_ID"));
$empList = rs2array(query("SELECT
	EMPLOYEE_ID,
	CARD_NO, FIRST_NAME
        FROM
	employee"));
$apprTypeList = rs2array(query("SELECT approval_type_name, approval_type_name FROM approval_type"));

if ($_POST) {
    $memoType = getParam('MEMO_TYPE');
    $memoDate = getParam('MEMO_DATE');

    $memoInfoRef = getParam('productId');
    $memoInfoRefs = implode(',', $memoInfoRef);
    $memoDetails = getParam('MEMO_DETAILS');
    $memoCategory = getParam('MEMO_CATEGORY');
    $approveAmount = getParam('APPROVED_AMOUNT');
    $remarks = getParam('REMARKS');
    $payMethod = getParam('PAYMENT_METHOD');
    $memoSub = getParam('MEMO_SUBJECT');
    $memoRef = getParam('memo_ref_hidden');
    $boardDate = getParam('BOARD_DATE');
    $boardNo = getparam('BOARD_NO');


    $memoArchiveID = NextId('memo_archive', 'MEMO_ARCHIVE_ID');
    $insertSQL = "INSERT INTO memo_archive 
    (MEMO_ARCHIVE_ID, MEMO_TYPE, MEMO_DATE, MEMO_INFO_REF, MEMO_REF, BOARD_NO, BOARD_DATE, MEMO_DETAILS, MEMO_CATEGORY, APPROVED_AMOUNT, REMARKS,
    PAYMENT_METHOD, MEMO_SUBJECT, CREATED_BY, CREATED_DATE) 
    VALUES ('$memoArchiveID', '$memoType', '$memoDate', '$memoInfoRefs', '$memoRef', '$boardNo', '$boardDate', '$memoDetails', '$memoCategory', '$approveAmount', '$remarks', '$payMethod', '$memoSub',
        '$user_name', now())";
    query($insertSQL);
    $sID = NextId('memo_archive', 'MEMO_ARCHIVE_ID') - 1;

    $memoArchiveIDmain = NextId('memo_archive', 'MEMO_ARCHIVE_ID') - 1;

    $employeeID = getParam('employeeID');
    $apprvType = getParam('apprvType');
    $sl = 0;

    foreach ($apprvType as $key => $value) {
        $sl++;
        //$product = $val['item'];
        //$key; 
        $empID = $employeeID [$key];
        $apprvType1 = $apprvType [$key];

        $empDetSQL = "INSERT INTO mem_manage_emp_det (empID, approveType, _sort, memo_archive_id) VALUES 
           ('$empID', '$apprvType1','$sl', '$memoArchiveIDmain' )";
        query($empDetSQL);
    }

    $memoArchiveIDmain1 = NextId('memo_archive', 'MEMO_ARCHIVE_ID') - 1;
    $AttachmentDetails = getParam('AttachmentDetails');
    $FileName = getParam('FileName');
    $moduleName = 'memo_archive';

    if (isset($FileName)) {
        foreach ($FileName as $key => $val) {

            $MaxAttachmentId = NextId('memo_file_attach_list', 'FILE_ATTACH_LIST_ID');
            $SqlInsertAttachment = "INSERT INTO  memo_file_attach_list (FILE_ATTACH_LIST_ID, MODULE_NAME, REQUEST_ID, ATTACH_TITTLE,ATTACH_FILE_PATH)VALUES
            ('$MaxAttachmentId','$moduleName','$memoArchiveIDmain1','$AttachmentDetails[$key]','$FileName[$key]')";
            sql($SqlInsertAttachment);
        }
    }

    $memoArchiveIDmain2 = NextId('memo_archive', 'MEMO_ARCHIVE_ID') - 1;
    $division = getParam('division');
    $sl1 = 0;
    foreach ($division as $key => $value) {
        $sl1++;
        $new_division = $division [$key];

        $insertDivSQL = "INSERT INTO mem_man_div_details (division,_sort,memo_management_id) 
            VALUES ('$new_division', '$sl1', '$memoArchiveIDmain2')";
        query($insertDivSQL);
    }
    //$db->CloseDb();
    echo "<script>location.replace('RoughPurpose_view.php?id=" . $sID . "');</script>";
    //echo "<script>window.location.href = '';<script>";
}

include '../body/header.php';
?>
<title>Create New Memo | Memo Archive</title>
<script src="../public/uploadify/jquery.uploadify-3.1.min.js"></script>
<link href="../public/uploadify/uploadify.css" rel="stylesheet" type="text/css"/>
<script src="Requisition.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
//        alert('AAA');
        $(".boardtr").hide();
        $('#managetr').hide();

        $('#board').click(function() {
            $('#managetr').hide();
            $(".boardtr").show();
            //$('#manLabel').text('Board Info');
        });

        $('#manage').click(function() {
            $(".boardtr").hide();
            $('#managetr').show();
            $('#manLabel').text('Approval Flow');
        });

        $('#cg').combogrid({
            panelWidth: 400,
            url: 'getdataForCombo.php',
            idField: 'EMPLOYEE_ID',
            textField: 'EM',
            mode: 'remote',
            fitColumns: true,
            columns: [[
                    {field: 'EMPLOYEE_ID', title: 'Employee ID', width: 20},
                    {field: 'CARD_NO', title: 'Card No', align: 'right', width: 20},
                    {field: 'FIRST_NAME', title: 'Employee Name', align: 'left', width: 40}
                ]]
        });



    });
</script>
<script type="text/javascript">
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

    function ddmmyyyyToDate(str) {
        var parts = str.split("-");                  // Gives us ["dd", "mm", "yyyy"]
        return new Date(parseInt(parts[0], 10), // Year
                parseInt(parts[1], 10) - 1, // Month (starts with 0)
                parseInt(parts[2], 10));     // Day of month
    }
</script>

<div class="easyui-layout" style="width:100%; height:700px;">  
    <div data-options="region:'east', split:true, collapsed:false" title="Notifications" style="width:250px;">  
        <h3>Notification</h3>
    </div>


    <div data-options="region:'center'"> 
        <div class="easyui-accordion" data-options="fit:true,border:false,plain:true">  
            <div title="Memo List">       
                <div id="dlg"  style="padding:10px 20px" closed="true" buttons="#dlg-buttons">
                    <form method="post" enctype="multipart/form-data" id="fm" novalidate autocomplete="off">
                        <table class="table">
                            <tr>
                                <td> Memo No. :</td>
                                <td><input type='text' name='MEMO_REF' disabled="disabled" size="20" value="<?php echo $memoRef; ?>"/></td>
                            <input type="hidden" name="memo_ref_hidden" value="<?php echo $memoRef; ?>">
                            </tr>
                            <tr>                        
                                <td>Subject of the Memo :</td>
                                <td>
                                    <textarea name="MEMO_SUBJECT" placeholder="Subject of the Memo"></textarea>
                                </td>
                            </tr>

                            <tr>
                                <td>Previous Memo Reference:</td>
                                <td>
                                    <table id="productGrid" class="ui-state-default">
                                        <thead>
                                        <th>SL</th>
                                        <th width="80">Memo Reference</th>
                                        <th width="80" colspan="2">Action</th>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                    <button type="button" class="button" id="Add" title="productTab" onclick="addCombo();">Add More</button>
                                </td>
                            </tr>


                            <tr>                        
                                <td><strong> Memo Type :</strong></td>
                                <td>
                                    <input type="radio" id="board" name="MEMO_TYPE" value="board"> Board 
                                    <input type="radio" id="manage" name="MEMO_TYPE" value="management"> Management</td>
                            </tr>


                            <tr class="boardtr">
                                <td></td>
                                <td>Board no.: <input type='text' name='BOARD_NO' data-options="required:true"> 
                                    Date: <input type='text' name="BOARD_DATE" class='easyui-datebox' data-options="formatter:myformatter,parser:myparser"></td>
                            </tr>
                            <tr class="boardtr">
                                <td></td>
                                <td><table id="productTab1" class="ui-state-default">
                                        <thead>
                                        <th>SL</th>
                                        <th>Employee</th>
                                        <th width="50">Approval Type</th>
                                        <th width="80">Action</th>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><?php echo++$proSL; ?></td>
                                                <td>
                                                    <?php comboBox('employeeID[]', $empList, $empID, FALSE); ?>
                                                </td> 
                                                <td><?php comboBox('apprvType[]', $apprTypeList, $approvalType, FALSE); ?></td>
                                                <td align="center"><div class="remove"><?php image("delete.png"); ?></div></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <a href="#" class="easyui-linkbutton" iconCls="icon-add" plain="true" title="productTab" onclick="RemoveTableTr('productTab1');">Add</a>
                                </td>
                            </tr>

                            <tr id="managetr">
                                <td><label id="manLabel"></label></td>
                                <td>
                                    <table id="productTab" class="ui-state-default">
                                        <thead>
                                        <th>SL</th>
                                        <th>Employee</th>
                                        <th width="50">Approval Type</th>
                                        <th width="80">Action</th>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><?php echo++$proSL1; ?></td>
                                                <td>
                                                    <?php comboBox('employeeID[]', $empList, $empID, FALSE); ?>
                                                </td> 
                                                <!--
                                                <td><select name="apprvType[]">
                                                        <option value="Initiator">Initiator</option>
                                                        <option value="Recommended">Recommended</option>
                                                        <option value="Approved">Approved</option>
                                                    </select>
                                                </td>
                                                -->
                                                <td><?php comboBox('apprvType[]', $apprTypeList, $approvalType, FALSE); ?></td>
                                                <td align="center"><div class="remove"><?php image("delete.png"); ?></div></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <a href="#" class="easyui-linkbutton" iconCls="icon-add" plain="true" title="productTab" onclick="RemoveTableTr('productTab');">Add</a>
                                </td>
                            </tr>

                            <tr>
                                <td>Memo Approved Date :</td>
                                <td>
                                    <input type='text' name='MEMO_DATE' class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" size="20">
                                </td>
                            </tr>
                            <tr>
                                <td>CC:</td>
                                <td>
                                    <table id="productGrid1" class="ui-state-default">
                                        <thead>
                                        <th>SL</th>
                                        <th width="80">CC Code/ Division/ Office</th>
                                        <th width="50">Action</th>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                    <button type="button" class="button" id="Add1" title="productTab" onclick="addComboForCC();">Add More</button>
                                </td>
                            </tr>

                            <tr>
                                <td>Memo Category :</td>
                                <td> 
                                    <input type="radio" name="MEMO_CATEGORY" value="Opex"> Opex
                                    <input type="radio" name="MEMO_CATEGORY" value="Capex"> Capex
                                    <input type="radio" name="MEMO_CATEGORY" value="Both"> Both
                                </td>
                            </tr>



                            <tr>
                                <td> Details :</td>
                                <td><textarea placeholder="Enter memo details here" rows="3" cols="55" name="MEMO_DETAILS"></textarea></td>
                            </tr>

                            <tr>
                                <td>Approved Amount:</td>
                                <td><input type='text' name='APPROVED_AMOUNT' id='APPROVED_AMOUNT' class='easyui-validatebox' value='' size="20"></td>
                            </tr>
                            <tr class='fitem'>
                                <td>Remarks :</td>
                                <td><textarea placeholder="Enter remarks here" rows="3" cols="55" name="REMARKS"></textarea></td>
                            </tr>

                            <tr class='fitem'>
                                <td>Payment Method :</td>
                                <td>
                                    <select name="PAYMENT_METHOD">
                                        <option></option>
                                        <option value="single">Single</option>
                                        <option value="installment">Installment</option>
                                    </select>
                                </td>
                            </tr>


                            <tr class='fitem'>
                                <td>Attachment Title</td>
                                <td><input name="textfield" type="text" size="60" id="AttachmentDetails" placeholder="Title" class="easyui-validatebox" data-options="required:true"/></td>
                                <td><input type='file' class='uploadify-button' id='file_upload' />
                                    <input id="file_upload_done" class="text_field_display" type="text" />
                                </td>
                            </tr>


                            <tr>
                                <td></td>
                                <td>
                                    <table class="ui-state-default" id="attachment_tab">
                                        <thead>
                                        <th width="20">SL</th>
                                        <th align="left">Attachment Title</th>
                                        <th width="100" align="right">Action</th>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </td>
                            </tr>
                        </table>
                        <input type="submit" name="save" value="save" id='save' class="button">
                    </form>
                </div>
            </div>          
        </div>  
    </div>  
</div>


<?php
include '../body/footer.php';
?>