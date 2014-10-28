<?php

$db = new DbManager();

$SearchId = getParam('search_id');
$SearchId = base64_decode($SearchId);

$db->OpenDb();
$MaxRequisitionMainId = NextId('requisition', 'REQUISITION_ID');


$SearchId = $SearchId == '' ? $MaxRequisitionMainId : $SearchId;
if (isSave()) {

    $MaxApprovListID = NextId('gp_requesition', 'REQUISITION_ID');
    $requisition_no = OrderNo($MaxApprovListID);
    $PriorityID = getParam('priority');
    $RequisitionNo = getParam('RequisitionNo');
    $BRANCH_NAMEgID = getParam("BRANCH_NAMEgID");
    $DeptID = getParam("DeptID");
    $ExpenseType = getParam('ExpenseType');
    $RequisitionNo = getParam('RequisitionNo');

    $Attach_Title = getParam('AttachmentDetails');
    $Attach_File_Path = getParam('FileName');
    SaveUploadFile($MaxApprovListID, 'GP', $Attach_Title, $Attach_File_Path);



    $product = getParam('item');
    $price = getParam('price');
    $quantity = getParam('quantity');
    $remark = getParam('remark');
    $REQUISITION_DETAILS_ID = getParam('REQUISITION_DETAILS_ID');

    $sol = getParam(sol);
    $CostcenterId = getParam('CostcenterId');
    $CostcenterAmount = getParam('CostcenterAmount');
    $CostcenterPercent = getParam('CostcenterPercent');
    $CostcenterCCId = getParam('CostcenterCCId');


    $EmployeeDesignID = getParam('EmployeeDesignID');
    $EmployeeId = getParam('EmployeeId');
    $workflow_process = getParam('workflow_process');

    $Specification = getParam('Specification');
    $Justification = getParam('Justification');
    $Remark = getParam('Remark');


    $Workflow = getParam('Workflow');
    $ExpenseType = getParam('ExpenseType');
    $WdReported = getParam('WdReported');



    $FileManagementApprove = getParam('file_upload_done_ma');
    $FileBoardApprove = getParam('file_upload_done_ba');

    $AttachmentDetails = getParam('AttachmentDetails');
    $FileName = getParam('FileName');

    $Budget = getParam('Budget');
    $R1 = getParam('R1');
    $HelpDeskNo = getParam('help_desk');


    if ($mode == 'new') {
        $requisition_sql = "INSERT INTO gp_requesition (REQUISITION_ID,requisition_no,CREATED_BY,REQUISITION_DATE,PROCESS_DEP_ID,AMOUNT_TYPE_ID,WORKFLOW_TYPE_ID, WORKFLOW_GROUP_ID, EXPENSE_TYPE_ID, BUDGET, BRANCH_ID, DEPT_ID, SPECIFICATION, JUSTIFICATION, REMARK, MANAGEMENT_APPROVE_FILE, BOARD_APPROVE_FILE,PRIORITY_ID,HELP_DESK_NO)
        VALUES('$MaxApprovListID','$requisition_no','$user_name', NOW(),'$route','$R1','$Workflow', '$WdReported', '$ExpenseType','$Budget','$BRANCH_NAMEgID','$DeptID','$Specification','$Justification','$Remark','$ManagementApprove','$FileBoardApprove','$PriorityID','$HelpDeskNo')";
        sql($requisition_sql);




        $sl = 1;
        foreach ($product as $key => $val) {

            $MaxRequisitionDetails = NextId('gp_requesiton_details', 'REQUISITION_DETAILS_ID');
            $SqlInsertproduct = "INSERT INTO  gp_requesiton_details (REQUISITION_DETAILS_ID,REQUISITION_ID,PRODUCT_ID,UNIT_PRICE,QTY,PRODUCT_REMARK, SL)
            VALUES('$MaxRequisitionDetails','$MaxApprovListID','$product[$key]','$price[$key]','$quantity[$key]','$remark[$key]', '$sl')";
            sql($SqlInsertproduct);
            $sl++;
        }



        foreach ($CostcenterId as $key => $val) {
            $MaxRequisitionCcListId = NextId('gp_requisition_cc_list', 'requisition_cc_list_id');


            $SqlInsertCcLis = "INSERT INTO gp_requisition_cc_list (REQUISITION_CC_LIST_ID,REQUISITION_ID,REQUISITION_CC_ID,CC_AMOUNT,CC_PERCENT,SOL_ID, CREATED_BY, CREATED_DATE)
            VALUES('$MaxRequisitionCcListId','$MaxApprovListID','$CostcenterId[$key]','$CostcenterAmount[$key]','$CostcenterPercent[$key]','$sol[$key]', '$user_name', NOW())";
            sql($SqlInsertCcLis);
        }




        $sl = 1;
        foreach ($workflow_process as $key => $val) {
            $MaxRequisitionFlowListId = NextId('gp_requisition_flow_list', 'GP_REQUISITION_FLOW_LIST_ID');

            $SqlInsertWd = "INSERT INTO  gp_requisition_flow_list (GP_REQUISITION_FLOW_LIST_ID, REQUISITION_ID, EMPLOYEE_ID, DESIGNATION_ID, WORKFLOW_PROCESS_TYPE_ID, SL, APPROVE_STATUS, CREATED_BY, CREATED_DATE)
            VALUES('$MaxRequisitionFlowListId', '$MaxApprovListID', '$EmployeeId[$key]', '$EmployeeDesignID[$key]', '$workflow_process[$key]', '$sl', '0', '$user_name', NOW() )";
            sql($SqlInsertWd);
            $sl++;
        }




        if (isset($FileName)) {
            foreach ($FileName as $key => $val) {

                $MaxAttachmentId = NextId('gp_requisition_file_attach_list', 'REQUISITION_FILE_ATTACH_LIST_ID');

                $SqlInsertAttachment = "INSERT INTO  gp_requisition_file_attach_list (REQUISITION_FILE_ATTACH_LIST_ID, REQUISITION_ID, ATTACH_TITTLE, ATTACH_FILE_PATH, CREATED_BY, CREATED_DATE)
                VALUES('$MaxAttachmentId','$MaxApprovListID','$AttachmentDetails[$key]','$FileName[$key]', '$user_name', NOW())";
                sql($SqlInsertAttachment);
            }
        }



        $EncodedSearchID = base64_encode($MaxApprovListID);
    } else {

        query("UPDATE gp_requesition SET 
                PRIORITY_ID  ='$PriorityID',
                SPECIFICATION='$Specification',
                JUSTIFICATION='$Justification',
                REMARK='$Remark',   
                MANAGEMENT_APPROVE_FILE='$FileManagementApprove',
                BOARD_APPROVE_FILE='$FileBoardApprove',
                BUDGET = '$Budget'
                WHERE REQUISITION_ID='$SearchId'");





        foreach ($product as $key => $value) {


            $CheckBeforeProduct = findValue("SELECT COUNT(REQUISITION_DETAILS_ID)
            FROM gp_requesiton_details 
            WHERE REQUISITION_ID = '$SearchId' AND REQUISITION_DETAILS_ID = '$REQUISITION_DETAILS_ID[$key]'");


            if ($CheckBeforeProduct > 0) {
                $QueryUpdate = "UPDATE gp_requesiton_details SET 
                PRODUCT_ID='$product[$key]',
                UNIT_PRICE='$price[$key]',
                QTY='$quantity[$key]', 
                PRODUCT_REMARK ='$remark[$key]'
                WHERE REQUISITION_DETAILS_ID = '$REQUISITION_DETAILS_ID[$key]'";
                query($QueryUpdate);
            } else {
                $MaxRequisitionDetails = NextId('gp_requesiton_details', 'REQUISITION_DETAILS_ID');
                $SqlInsertproduct = "INSERT INTO  gp_requesiton_details (REQUISITION_DETAILS_ID, REQUISITION_ID,PRODUCT_ID,UNIT_PRICE,QTY,PRODUCT_REMARK)
                VALUES('$MaxRequisitionDetails','$SearchId','$product[$key]','$price[$key]','$quantity[$key]','$remark[$key]')";

                query($SqlInsertproduct);
            }
        }





        foreach ($CostcenterId as $key => $val) {

            $chk_req_sql = "SELECT COUNT(REQUISITION_CC_LIST_ID)
            FROM gp_requisition_cc_list 
            WHERE REQUISITION_ID = '$SearchId' AND REQUISITION_CC_LIST_ID = '$CostcenterCCId[$key]'";


            $CheckBefore = findValue($chk_req_sql);


            if ($CheckBefore > 0) {
                $QueryUpdate = "UPDATE gp_requisition_cc_list SET 
                REQUISITION_CC_ID='$CostcenterId[$key]',
                CC_AMOUNT='$CostcenterAmount[$key]',
                CC_PERCENT='$CostcenterPercent[$key]',
                SOL_ID='$sol[$key]',
                MODIFY_BY='$user_name',
                MODIFY_DATE=NOW()
                WHERE REQUISITION_CC_LIST_ID = '$CostcenterCCId[$key]'";
                sql($QueryUpdate);
            } else {
                $MaxRequisitionCcListId = NextId('gp_requisition_cc_list', 'requisition_cc_list_id');
                $SqlInsertCcLis = "INSERT INTO gp_requisition_cc_list (REQUISITION_CC_LIST_ID, REQUISITION_ID, REQUISITION_CC_ID, SOL_ID, CC_AMOUNT, CREATED_BY, CREATED_DATE)
                VALUES('$MaxRequisitionCcListId','$SearchId','$CostcenterId[$key]', '$sol[$key]', '$CostcenterAmount[$key]', '$user_name',  NOW())";

                sql($SqlInsertCcLis);
            }
        }

        $sl = 1;
        foreach ($workflow_process as $key => $val) {
            $chk_req_sql = "SELECT COUNT(*)
            FROM gp_requisition_flow_list 
            WHERE  GP_REQUISITION_FLOW_LIST_ID = '$workflow_process[$key]'";


            $CheckBefore = findValue($chk_req_sql);


            if ($CheckBefore == 1) {
                $QueryUpdate = "UPDATE gp_requisition_flow_list SET 
                EMPLOYEE_ID='$EmployeeId[$key]',
                DESIGNATION_ID='$EmployeeDesignID[$key]',
                WORKFLOW_PROCESS_TYPE_ID='$workflow_process[$key]',
                MODIFY_BY='$user_name',
                MODIFY_DATE=NOW()
                WHERE GP_REQUISITION_FLOW_LIST_ID = '$workflow_process[$key]'";
                sql($QueryUpdate);
            } else {
                $GP_REQUISITION_FLOW_LIST_ID = NextId('gp_requisition_flow_list', 'GP_REQUISITION_FLOW_LIST_ID');
                $SqlInsertWd = "INSERT INTO  gp_requisition_flow_list (GP_REQUISITION_FLOW_LIST_ID, REQUISITION_ID, EMPLOYEE_ID, DESIGNATION_ID, WORKFLOW_PROCESS_TYPE_ID, SL, APPROVE_STATUS, CREATED_BY, CREATED_DATE)
                VALUES('$GP_REQUISITION_FLOW_LIST_ID', '$SearchId', '$EmployeeId[$key]', '$EmployeeDesignID[$key]', '$workflow_process[$key]', '$sl', '0', '$user_name',  NOW())";
                sql($SqlInsertWd);
            }
            $sl++;
        }


        if (isset($FileName)) {
            foreach ($FileName as $key => $val) {

                $CheckBeforeAttachment = findValue("SELECT COUNT(REQUISITION_FILE_ATTACH_LIST_ID)
                FROM gp_requisition_file_attach_list 
                WHERE REQUISITION_ID = '$SearchId' AND REQUISITION_FILE_ATTACH_LIST_ID = '$key'");

                if ($CheckBeforeAttachment > 0) {
                    $QueryUpdateAttachment = "UPDATE gp_requisition_file_attach_list SET 
                    ATTACH_TITTLE='$AttachmentDetails[$key]',
                    ATTACH_FILE_PATH='$FileName[$key]',
                    MODIFY_BY='$user_name',
                    MODIFY_DATE=NOW()

                    WHERE REQUISITION_ID = '$SearchId' AND REQUISITION_FILE_ATTACH_LIST_ID = '$key'";
                    sql($QueryUpdateAttachment);
                } else {
                    $MaxAttachmentId = NextId('gp_requisition_file_attach_list', 'REQUISITION_FILE_ATTACH_LIST_ID');
                    $SqlInsertAttachment = "INSERT INTO gp_requisition_file_attach_list (REQUISITION_FILE_ATTACH_LIST_ID, REQUISITION_ID,ATTACH_TITTLE,ATTACH_FILE_PATH)
                                    VALUES('$MaxAttachmentId', '$SearchId','$AttachmentDetails[$key]','$FileName[$key]')";

                    sql($SqlInsertAttachment);
                }
            }
        }


        $EncodedSearchID = base64_encode($SearchId);
    }





    echo "<script>location.replace('RequisitionView.php?mode=view&search_id=$EncodedSearchID');</script>";
}


if ($mode == 'view' || $mode == 'search') {


    $ProductSQL = "SELECT rd.REQUISITION_DETAILS_ID,p.product_id,p.PRODUCT_NAME AS PRODUCT_NAME,u.UNIT_TYPE_NAME,rd.UNIT_PRICE,rd.QTY,rd.PRODUCT_REMARK
    FROM gp_requesition AS rm
    LEFT JOIN gp_requesiton_details AS rd ON rm.REQUISITION_ID = rd.REQUISITION_ID
    LEFT JOIN product As p ON p.product_id =rd.PRODUCT_ID 
    LEFT JOIN unit_type AS u ON u.UNIT_TYPE_ID =p.unit_type_ID
    WHERE rm.REQUISITION_ID = '$SearchId'";

    $ResultProduct = query($ProductSQL);

    $SqlCostCode = "SELECT rc.REQUISITION_CC_LIST_ID,rc.CC_AMOUNT,rc.CC_PERCENT, rc.REQUISITION_CC_ID,rc.BUDGET, 
    CONCAT(cc.cost_center_code,' - ',cc.cost_center_Name) AS 'CcAccount',s.SOL_CODE,rc.SOL_ID, s.SOL_NAME
    FROM gp_requesition AS rm
    LEFT JOIN gp_requisition_cc_list AS rc ON rm.REQUISITION_ID = rc.REQUISITION_ID
    LEFT JOIN cost_center AS cc ON rc.REQUISITION_CC_ID = cc.cost_center_id
    LEFT JOIN sol AS s ON s.SOL_ID = rc.SOL_ID
    WHERE rm.REQUISITION_ID ='$SearchId'";

    $ResultCostCode = query($SqlCostCode);

    $SqlCon = "SELECT fc.gp_requisition_for_contact_id As ContactId,ed.CARDNO,ed.FULL_NAME,ed.CONTACT,ed.EMAIL
    FROM gp_requisition_for_contact As fc
    LEFT JOIN gp_requesition As gr ON gr.REQUISITION_ID = fc.REQUISITION_ID
    LEFT JOIN employee_details As ed ON ed.CARDNO = fc.CARDNO
    WHERE fc.REQUISITION_ID = '$SearchId'";
    $SqlContact = query($SqlCon);




    $SqlWork = "SELECT rf.GP_REQUISITION_FLOW_LIST_ID, rf.EMPLOYEE_ID, rf.DESIGNATION_ID, 
    ed.FULL_NAME, wt.WORKFLOW_TYPE_NAME, d.DESIGNATION_NAME, wpt.WORKFLOW_PROCESS_NAME,
    rf.WORKFLOW_PROCESS_TYPE_ID
    FROM gp_requesition AS rm
    LEFT JOIN gp_requisition_flow_list AS rf ON rm.REQUISITION_ID = rf.REQUISITION_ID 
    LEFT JOIN employee_details As ed On ed.CARDNO = rf.EMPLOYEE_ID
    LEFT JOIN workflow_type AS wt ON wt.WORKFLOW_TYPE_ID = rm.WORKFLOW_TYPE_ID
    LEFT JOIN designation AS d ON d.DESIGNATION_ID =ed.DESIGNATION_ID
    LEFT JOIN workflow_process_type AS wpt ON wpt.WORKFLOW_PROCESS_TYPE_ID=rf.WORKFLOW_PROCESS_TYPE_ID
    WHERE rm.REQUISITION_ID ='$SearchId' ORDER BY wpt.WORKFLOW_PROCESS_TYPE_ID ";
    $ResultWorkFlow = query($SqlWork);

    $ResultRequisitionMain = find("SELECT rr.priority_NAME, rm.PRIORITY_ID,rm.REQUISITION_NO, rm.REQUISITION_DATE, rm.CREATED_BY,
            e.CARDNO, amt.AMOUNT_TYPE_ID, rm.BUDGET, et.EXPENSE_TYPE_ID, et.EXPENSE_TYPE_NAME, 
            wt.WORKFLOW_TYPE_NAME, rm.MANAGEMENT_APPROVE_FILE, rm.BOARD_APPROVE_FILE,
            rm.SPECIFICATION, rm.JUSTIFICATION, rm.REMARK, e.FULL_NAME, rm.WORKFLOW_TYPE_ID,
            AMOUNT_TYPE_NAME, workflow_name, HELP_DESK_NO,rr.PRIORITY_NAME,rm.PRIORITY_ID,
            rm.REQUISITION_STATUS_ID

            FROM gp_requesition AS rm
            LEFT JOIN expense_type AS et ON et.EXPENSE_TYPE_ID=rm.EXPENSE_TYPE_ID
            LEFT JOIN workflow_type AS wt ON wt.WORKFLOW_TYPE_ID=rm.WORKFLOW_TYPE_ID
            LEFT JOIN amount_type AS amt ON amt.AMOUNT_TYPE_ID=rm.AMOUNT_TYPE_ID 
            LEFT JOIN employee_details AS e ON e.CARDNO=rm.CREATED_BY
            LEFT JOIN workflow_group AS wg ON wg.workflow_group_id=rm.WORKFLOW_GROUP_ID
            LEFT JOIN priority AS rr ON rr.PRIORITY_ID = rm.PRIORITY_ID
            WHERE rm.REQUISITION_ID ='$SearchId'");


    $SqlAttachment = "SELECT REQUISITION_FILE_ATTACH_LIST_ID, ATTACH_TITTLE, ATTACH_FILE_PATH
    FROM gp_requisition_file_attach_list
    WHERE REQUISITION_ID = '$SearchId'";
    $ResultAttachment = query($SqlAttachment);


    $SqlCon = "SELECT fc.gp_requisition_for_contact_id As ContactId,ed.CARDNO,ed.FULL_NAME,ed.CONTACT,ed.EMAIL
    FROM gp_requisition_for_contact As fc
    LEFT JOIN gp_requesition As gr ON gr.REQUISITION_ID = fc.REQUISITION_ID
    LEFT JOIN employee_details As ed ON ed.CARDNO = fc.CARDNO
    WHERE fc.REQUISITION_ID = '$SearchId'";
    $SqlContact = query($SqlCon);
}

//$solList = rs2array(query("SELECT sol_id, sol_code, SOL_NAME FROM sol ORDER BY SOL_NAME"));
$ProductList = rs2array(query("SELECT product_id, PRODUCT_NAME, rr.ROUTE_NAME
FROM product AS p 
LEFT JOIN requisition_route AS rr ON rr.REQUISITION_ROUTE_ID=p.REQUISITION_ROUTE_ID
ORDER BY p.PRODUCT_NAME"));
$Costcenterids = rs2array(query("SELECT cost_center_id, cost_center_code, cost_center_Name FROM cost_center"));
//$WfGroupList = rs2array(query("SELECT workflow_group_id,workflow_name  FROM workflow_group"));
$StaffMember = find("SELECT ed.FIRST_NAME, ed.CARD_NO, ed.BRANCH_ID, ed.DEPARTMENT_ID FROM employee AS ed WHERE ed.CARD_NO = '$user_name'");
$SqlExpenseType = query("SELECT EXPENSE_TYPE_ID,EXPENSE_TYPE_NAME FROM expense_type");
//$SqlWorkFlowType = query("SELECT WORKFLOW_TYPE_ID, WORKFLOW_TYPE_NAME FROM workflow_type");
$PriorityList = rs2array(query("SELECT priority_id, priority_NAME FROM priority"));
//$WorkFlowProcessTypeList = rs2array(query("SELECT WORKFLOW_PROCESS_TYPE_ID,WORKFLOW_PROCESS_NAME  FROM workflow_process_type"));

$db->CloseDb();
?>