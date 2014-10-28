<?php
include_once '../lib/DbManager.php';




$memoRef = 'memo ref ' . NextId('memo_archive', 'MEMO_ARCHIVE_ID');
$divList = rs2array(query("SELECT cc.COST_CENTER_CODE, cc.COST_CENTER_CODE, cc.COST_CENTER_NAME, dn.DIVISION_NAME FROM cost_center cc
LEFT OUTER JOIN division dn ON dn.DIVISION_ID = cc.DIVISION_ID"));
$empList = rs2array(query("SELECT EMPLOYEE_ID, CARD_NO, FIRST_NAME FROM employee"));
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
    $memoRefNo = getParam('memoRefNo');
    //$memoRefNo=  getParam('memoRefNo');


    $memoArchiveID = NextId('memo_archive', 'MEMO_ARCHIVE_ID');
    $insertSQL = "INSERT INTO memo_archive (MEMO_ARCHIVE_ID, MEMO_TYPE, MEMO_REF_NO, MEMO_DATE, MEMO_INFO_REF, MEMO_REF, BOARD_NO, BOARD_DATE, MEMO_DETAILS, MEMO_CATEGORY, APPROVED_AMOUNT, REMARKS, PAYMENT_METHOD, MEMO_SUBJECT, CREATED_BY, CREATED_DATE) 
    VALUES ('$memoArchiveID', '$memoType', '$memoRefNo', '$memoDate', '$memoInfoRefs', '$memoRef', '$boardNo', '$boardDate', '$memoDetails', '$memoCategory', '$approveAmount', '$remarks', '$payMethod', '$memoSub','$user_name', now())";

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
    $FileName = getParam('attachFile');
    $moduleName = 'memo_archive';

    file_upload_save("../documents/memo_archive/", $memoArchiveID, $moduleName);

    $memoArchiveIDmain2 = NextId('memo_archive', 'MEMO_ARCHIVE_ID') - 1;
    $costCenterId = getParam('costCenterId');
    $sl1 = 0;
    foreach ($costCenterId as $key => $value) {
        $sl1++;

        $insertDivSQL = "INSERT INTO mem_man_div_details (cost_center_id,_sort,memo_management_id) 
            VALUES ('$costCenterId[$key]', '$sl1', '$memoArchiveIDmain2')";
        query($insertDivSQL);
    }
    echo "<script>location.replace('RoughPurpose_view.php?id=" . $sID . "');</script>";
}

include '../body/header.php';
?>

<script type="text/javascript" src="Requisition.js"></script>
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

<div class="panel-header">Memo List</div>
<div style="padding: 20px 20px; background: white;">

    <form method="post" enctype="multipart/form-data" id="fm" novalidate autocomplete="off">
        <table class="table">
            <tr>
                <td> Memo No. :</td>
                <td><input type='text' name='MEMO_REF' disabled="disabled" size="20" value="<?php echo $memoRef; ?>"/></td>
            <input type="hidden" name="memo_ref_hidden" value="<?php echo $memoRef; ?>">
            </tr>
            <tr>                        
                <td valign="top">Subject of the Memo :</td>
                <td>
                    <textarea name="MEMO_SUBJECT" placeholder="Subject of the Memo"></textarea>
                </td>
            </tr>

            <tr>
                <td valign="top">Previous Memo Reference:</td>
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
                <td>Memo Type :</td>
                <td>
                    <input type="radio" id="board" name="MEMO_TYPE" value="board"> Board 
                    <input type="radio" id="manage" name="MEMO_TYPE" value="management"> Management</td>
            </tr>


            <tr class="boardtr">
                <td></td>
                <td>
                    Board no.: <input type='text' name='BOARD_NO' data-options="required:true"> 
                    Date: <input type='text' name="BOARD_DATE" class='easyui-datebox' data-options="formatter:myformatter,parser:myparser">

                </td>
            </tr>
            <tr>
                <td>Memo Ref no.: </td>
                <td><input type='text' name='memoRefNo'> </td>
            </tr>
            <tr>
                <td valign="top">Employee :</td>
                <td>
                    <table id="productTab1" class="ui-state-default">
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
                    <button type="button" class="button" id="Add1" title="productTab" onclick="RemoveTableTr('productTab1');">Add More</button>
                </td>
            </tr>

            <tr>
                <td>Memo Approved Date :</td>
                <td><input type='text' name='MEMO_DATE' class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" size="20"></td>
            </tr>
            <tr>
                <td  valign="top">Cost Center :</td>
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
                <td valign="top"> Details :</td>
                <td><textarea placeholder="Enter memo details here" rows="3" cols="55" name="MEMO_DETAILS"></textarea></td>
            </tr>

            <tr>
                <td>Approved Amount:</td>
                <td><input type='text' name='APPROVED_AMOUNT' id='APPROVED_AMOUNT' class='easyui-validatebox' value='' size="20"></td>
            </tr>
            <tr class='fitem'>
                <td valign="top">Remarks :</td>
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
        </table>

        <?php file_upload_html(TRUE); ?>

        <input type="submit" name="save" value="save" id='save' class="button">
    </form>
    <a href="index.php" class="button">View Memo List</a>
</div>


<?php
include '../body/footer.php';
?>