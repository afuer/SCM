<?php

if ($_POST) {

    $memoType = getParam('MEMO_TYPE');
    $memoDate = getParam('MEMO_DATE');
    
    $memoInfoRef = getParam('MEMO_INFO_REF');
    
    $memoInfoRefs= implode (',',$memoInfoRef);
    
    //$paymentMethod = getParam('PAYMENT_METHOD');
    $memoDetails = getParam('MEMO_DETAILS');
    $memoCategory = getParam('MEMO_CATEGORY');
    $approveAmount = getParam('APPROVED_AMOUNT');
    $remarks = getParam('REMARKS');
    $payMethod = getParam('PAYMENT_METHOD');
    $memoSub = getParam('MEMO_SUBJECT');
    $memoRef = getParam('MEMO_REF');
    $boardDate = getParam('BOARD_DATE');
    $boardNo = getparam('BOARD_NO');

    $db = new DbManager();
    $db->OpenDb();
    
    $memoArchiveID = NextId('memo_archive', 'MEMO_ARCHIVE_ID');
    echo $insertSQL = "INSERT INTO memo_archive 
    (MEMO_ARCHIVE_ID, MEMO_TYPE, MEMO_DATE, MEMO_INFO_REF, MEMO_REF, BOARD_NO, BOARD_DATE, MEMO_DETAILS, MEMO_CATEGORY, APPROVED_AMOUNT, REMARKS,
    PAYMENT_METHOD, MEMO_SUBJECT, CREATED_BY, CREATED_DATE) 
    VALUES ('$memoArchiveID', '$memoType', '$memoDate', '$memoInfoRefs', '$memoRef', '$boardNo', '$boardDate', '$memoDetails', '$memoCategory', '$approveAmount', '$remarks', '$payMethod', '$memoSub',
        '$user_name', now())";

    query($insertSQL);
    $sID= NextId('memo_archive', 'MEMO_ARCHIVE_ID')-1;
    
    $memoArchiveIDmain = NextId('memo_archive', 'MEMO_ARCHIVE_ID')-1;
    
    $employeeID = getParam('employeeID');
    $apprvType= getParam('apprvType');
    $sl=0;
    
    foreach ($apprvType as $key => $value) {
        $sl++;
        //$product = $val['item'];
        //$key; 
        $empID= $employeeID [$key];
        $apprvType1 = $apprvType [$key]; 
        
        $empDetSQL="INSERT INTO mem_manage_emp_det (empID, approveType, _sort, memo_archive_id) VALUES 
           ('$empID', '$apprvType1','$sl', '$memoArchiveIDmain' )";
        query($empDetSQL);
    }
    
    $memoArchiveIDmain1 = NextId('memo_archive', 'MEMO_ARCHIVE_ID')-1;
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
    
    $memoArchiveIDmain2 = NextId('memo_archive', 'MEMO_ARCHIVE_ID')-1;
    $division= getParam('division');
    $sl1=0;
    foreach ($division as $key => $value) {
        $sl1++;
        $new_division = $division [$key];
        
        $insertDivSQL="INSERT INTO mem_man_div_details (division,_sort,memo_management_id) 
            VALUES ('$new_division', '$sl1', '$memoArchiveIDmain2')";
        query($insertDivSQL);
    }
    $db->CloseDb();
    echo "<script>location.replace('RoughPurpose_view.php?id=".$sID."');</script>";
    //echo "<script>window.location.href = '';<script>";
}

?>
