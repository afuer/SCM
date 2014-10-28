<?php

include_once 'manager.php';
$SearchId = getParam('search_id');
//$SearchId = base64_decode($SearchId);

$MaxRequisitionMainId = NextId('requisition', 'REQUISITION_ID');

$SearchId = $SearchId == '' ? $MaxRequisitionMainId : $SearchId;

if (isSave()) {

    $manager = new WorkFlowManager();

    $PriorityID = getParam('priority');
    $RequisitionNo = getParam('RequisitionNo');
    $DeptID = getParam("DeptID");
    $ExpenseType = getParam('ExpenseType');
    $RequisitionNo = getParam('RequisitionNo');

    $product = getParam('product');
    //$productid = getParam('productid');
    $productList = getParam('productid');
    $price = getParam('price');
    $quantity = getParam('qty');
    $remark = getParam('remark');
    $REQUISITION_DETAILS_ID = getParam('REQUISITION_DETAILS_ID');
    $processDeptId = getParam('processDeptId');
    $requisition_type_id = getParam('requisition_type_id');

    $EmployeeDesignID = getParam('EmployeeDesignID');
    $EmployeeId = getParam('EmployeeId');
    $workflow_process = getParam('workflow_process');

    $Specification = getParam('specification');
    $Justification = getParam('justification');
    $Remark = getParam('remark');

    $approval = getParam('approval');
    $approval_file = getParam('approval_file');


    $Budget = getParam('Budget');
    $R1 = getParam('R1');
    $HelpDeskNo = getParam('help_desk');

    $CostCenter = getParam('CostCenter');
    $sol = getParam('sol');
    $CostCenterAmount = getParam('Amount');




    //echo '<pre>';
    //print_r($productid);


    if ($mode == 'new') {


        if ($product) {
            $sl = 1;
            $it = '';
            $procure = '';
            $MaxId = NextId('requisition', 'REQUISITION_ID');
            foreach ($product as $key => $value) {

                if ($processDeptId[$key] == 2) {
                    if ($it == '') {
                        $MaxItId = NextId('requisition', 'REQUISITION_ID');
                        $requisition_no = OrderNo($MaxItId);
                        $path = file_upload_single("../documents/PR/");

                        $requisition_sql = "INSERT INTO requisition (REQUISITION_ID, PARENT_REQUISITION_ID, REQUISITION_NO, PRESENT_LOCATION_ID, CREATED_BY, REQUISITION_DATE, SPECIFICATION, JUSTIFICATION, REMARK, PROCESS_DEPT_ID, REQUISITION_TYPE_ID, BRANCH_DEPT_ID, OFFICE_TYPE_ID, CREATED_DATE, APPROVE_FILE_TYPE, APPROVE_FILE_PATH, REQUISITION_STATUS_ID)
                        VALUES('$MaxItId','$MaxId' ,'$requisition_no', '$lineManagerId', '$employeeId', NOW(),'$Specification','$Justification','$Remark', '$processDeptId[$key]', '$requisition_type_id', '$BranchDeptId', '$OfficeTypeId', NOW(), '$approval', '$path', 1)";
                        sql($requisition_sql);

                        file_upload_save('../documents/PR/', $MaxItId, 'requisition');
                    }
                    $maxRequisitionDetailsId = NextId('requisition_details', 'REQUISITION_DETAILS_ID');

                    $productId = find("SELECT PRODUCT_ID, PURCHASE_PRICE FROM product WHERE PRODUCT_ID='$product[$key]'");

                    if ($productId->PRODUCT_ID == '') {
                        $MaxProductId = NextId('product', 'PRODUCT_ID');
                        sql("INSERT INTO product(PRODUCT_ID, PRODUCT_NAME, DESCRIPTION, ISACTIVE, CREATED_BY, CREATED_DATE)
                        VALUES('$MaxProductId', '$productList[$key]', 'Requisition Product', 1, '$employeeId', NOW())");
                        $productId = insert_id();
                    } else {
                        $productId = $product[$key];
                    }

                    $sqlDetails = "INSERT INTO requisition_details (REQUISITION_DETAILS_ID, REQUISITION_ID, PRODUCT_ID, QTY, UNIT_PRICE, USER_COMMENT) 
                    VALUES('$maxRequisitionDetailsId', '$MaxItId', '$productId', '$quantity[$key]', '$price[$key]', '$remark[$key]')";
                    sql($sqlDetails);
                } elseif ($processDeptId[$key] == 5) {
                    if ($procure == '') {
                        $MaxProcureId = NextId('requisition', 'REQUISITION_ID');

                        $requisition_no = OrderNo($MaxProcureId);
                        $path = file_upload_single("../documents/PR/");

                        $requisition_sql = "INSERT INTO requisition (REQUISITION_ID, PARENT_REQUISITION_ID, REQUISITION_NO, PRESENT_LOCATION_ID, CREATED_BY, REQUISITION_DATE, SPECIFICATION, JUSTIFICATION, REMARK, PROCESS_DEPT_ID, REQUISITION_TYPE_ID, BRANCH_DEPT_ID, OFFICE_TYPE_ID, CREATED_DATE, APPROVE_FILE_TYPE, APPROVE_FILE_PATH, REQUISITION_STATUS_ID)
                        VALUES('$MaxProcureId', '$MaxId', '$requisition_no', '$lineManagerId', '$employeeId', NOW(),'$Specification','$Justification','$Remark', '$processDeptId[$key]', '$requisition_type_id', '$BranchDeptId', '$OfficeTypeId', NOW(), '$approval', '$path', 1)";
                        sql($requisition_sql);

                        file_upload_save('../documents/PR/', $MaxProcureId, 'requisition');
                    }

                    $productId = find("SELECT PRODUCT_ID, PURCHASE_PRICE FROM product WHERE PRODUCT_ID='$product[$key]'");

                    if ($productId->PRODUCT_ID == '') {

                        $MaxProductId = NextId('product', 'PRODUCT_ID');
                        sql("INSERT INTO product(PRODUCT_ID, PRODUCT_NAME, DESCRIPTION, ISACTIVE, CREATED_BY, CREATED_DATE)
                        VALUES('$MaxProductId', '$productList[$key]', 'Requisition Product', 1, '$employeeId', NOW())");
                        $productId = insert_id();
                    } else {
                        $productId = $product[$key];
                    }
                    
                    $maxRequisitionDetailsId = NextId('requisition_details', 'REQUISITION_DETAILS_ID');

                    $sqlDetails = "INSERT INTO requisition_details(REQUISITION_DETAILS_ID, REQUISITION_ID, PRODUCT_ID, QTY, UNIT_PRICE, USER_COMMENT) 
                    VALUES('$maxRequisitionDetailsId', '$MaxProcureId', '$productId', '$quantity[$key]', '$price[$key]', '$remark[$key]')";
                    sql($sqlDetails);
                } else {
                    $processDept = getParam('processDept');
                    $requisition_no = OrderNo($MaxId);

                    $requisition_sql = "INSERT INTO requisition (REQUISITION_ID, PARENT_REQUISITION_ID, REQUISITION_NO, PRESENT_LOCATION_ID, CREATED_BY, REQUISITION_DATE, SPECIFICATION, JUSTIFICATION, REMARK, PROCESS_DEPT_ID, REQUISITION_TYPE_ID, BRANCH_DEPT_ID, OFFICE_TYPE_ID, CREATED_DATE, APPROVE_FILE_TYPE, APPROVE_FILE_PATH, REQUISITION_STATUS_ID)
                        VALUES('$MaxId','$MaxId' ,'$requisition_no', '$lineManagerId', '$employeeId', NOW(),'$Specification','$Justification','$Remark', '$processDept', '$requisition_type_id', '$BranchDeptId', '$OfficeTypeId', NOW(), '$approval', '$path', 1)";
                    sql($requisition_sql);

                    file_upload_save('../documents/PR/', $MaxItId, 'requisition');
                    //print_r($productList);

                    foreach ($productList as $key => $value) {

                        $sqlProduct = "INSERT INTO product(PRODUCT_CODE, PRODUCT_NAME, DESCRIPTION, PRODUCT_GROUP_ID) 
                        VALUES('Test', '$value', 'Gad Brand', 1)";
                        sql($sqlProduct);

                        $productIdLast = insert_id();

                        $productId = find("SELECT PRODUCT_ID, PURCHASE_PRICE FROM product WHERE PRODUCT_ID='$productIdLast'");

                        $sqlDetails = "INSERT INTO requisition_details(REQUISITION_DETAILS_ID, REQUISITION_ID, PRODUCT_ID, QTY, UNIT_PRICE, USER_COMMENT) 
                        VALUES('$maxRequisitionDetailsId', '$MaxId', '$productIdLast', '$quantity[$key]', '$price[$key]', '$remark[$key]')";
                        sql($sqlDetails);
                        //echo "<br>";
                    }
                }
                $it = $processDeptId[$key] == 2 || $it != '' ? 2 : '';
                $procure = $processDeptId[$key] == 5 || $procure != '' ? 5 : '';

                $sl++;
            }
        }
    } else {


        query("UPDATE requesition SET 
        PRIORITY_ID  ='$PriorityID',
        SPECIFICATION='$Specification',
        JUSTIFICATION='$Justification',
        REMARK='$Remark',   
        APPROVE_FILE_TYPE='$approval',
        APPROVE_FILE='',
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
    }

    echo "<script>location.replace('RequisitionView.php?mode=view&search_id=$MaxId');</script>";
}


if ($mode == 'view' || $mode == 'search') {


    $ProductSQL = "SELECT rd.REQUISITION_DETAILS_ID,p.product_id,p.PRODUCT_NAME AS PRODUCT_NAME, 
        u.UNIT_TYPE_NAME,rd.UNIT_PRICE,rd.QTY,rd.USER_COMMENT
        FROM requisition AS r
        LEFT JOIN requisition_details AS rd ON r.REQUISITION_ID = rd.REQUISITION_ID
        LEFT JOIN product As p ON p.product_id =rd.PRODUCT_ID 
        LEFT JOIN unit_type AS u ON u.UNIT_TYPE_ID =p.unit_type_ID
        WHERE r.REQUISITION_ID = '$SearchId'";

    $ResultProduct = query($ProductSQL);

    $SqlCostCode = "SELECT rc.REQUISITION_CC_LIST_ID,rc.CC_AMOUNT,rc.CC_PERCENT, rc.REQUISITION_CC_ID,rc.BUDGET, 
    CONCAT(cc.cost_center_code,' - ',cc.cost_center_Name) AS 'CcAccount',s.SOL_CODE,rc.SOL_ID, s.SOL_NAME
    FROM requisition AS rm
    LEFT JOIN requisition_cc_list AS rc ON rm.REQUISITION_ID = rc.REQUISITION_ID
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
    FROM requisition AS rm
    LEFT JOIN requisition_flow_list AS rf ON rm.REQUISITION_ID = rf.REQUISITION_ID 
    LEFT JOIN employee As ed On ed.EMPLOYEE_ID = rf.EMPLOYEE_ID
    LEFT JOIN workflow_type AS wt ON wt.WORKFLOW_TYPE_ID = rm.WORKFLOW_TYPE_ID
    LEFT JOIN designation AS d ON d.DESIGNATION_ID =ed.DESIGNATION_ID
    LEFT JOIN workflow_process_type AS wpt ON wpt.WORKFLOW_PROCESS_ID=rf.WORKFLOW_PROCESS_TYPE_ID
    WHERE rm.REQUISITION_ID ='$SearchId' ORDER BY wpt.WORKFLOW_PROCESS_ID ";
    $ResultWorkFlow = query($SqlWork);

    $sql_main = "SELECT  r.REQUISITION_NO, r.REQUISITION_DATE, e.CARD_NO, r.SPECIFICATION, 
        r.JUSTIFICATION, r.REMARK, r.CREATED_BY, r.APPROVE_FILE_PATH, 
        r.APPROVE_FILE_TYPE, amt.AMOUNT_TYPE_NAME, 
        et.EXPENSE_TYPE_ID, et.EXPENSE_TYPE_NAME, r.WORKFLOW_TYPE_ID,
        wt.WORKFLOW_TYPE_NAME, WORKFLOW_NAME, REQUISITION_TYPE_ID, PROCESS_DEPT_ID,
        CONCAT(e.FIRST_NAME,' ',e.LAST_NAME) AS FULL_NAME

        FROM requisition AS r
        LEFT JOIN employee e ON e.EMPLOYEE_ID=r.CREATED_BY
        LEFT JOIN expense_type AS et ON et.EXPENSE_TYPE_ID=r.EXPENSE_TYPE_ID
        LEFT JOIN workflow_type AS wt ON wt.WORKFLOW_TYPE_ID=r.WORKFLOW_TYPE_ID
        LEFT JOIN amount_type AS amt ON amt.AMOUNT_TYPE_ID=r.AMOUNT_TYPE_ID 
        LEFT JOIN workflow_group AS wg ON wg.workflow_group_id=r.WORKFLOW_GROUP_ID
        WHERE r.REQUISITION_ID='$SearchId'";

    $ResultRequisitionMain = find($sql_main);


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



//$StaffMember = find("SELECT ed.FULL_NAME, ed.CARDNO, ed.BRANCH_ID, ed.DEPARTMENT_ID FROM employee_details AS ed WHERE ed.CARDNO = '$user_name'");
$SqlExpenseType = query("SELECT EXPENSE_TYPE_ID,EXPENSE_TYPE_NAME FROM expense_type");
$SqlWorkFlowType = query("SELECT WORKFLOW_TYPE_ID, WORKFLOW_TYPE_NAME FROM workflow_type");
$PriorityList = rs2array(query("SELECT priority_id, priority_NAME FROM priority"));
$WorkFlowProcessTypeList = rs2array(query("SELECT WORKFLOW_PROCESS_ID,WORKFLOW_PROCESS_NAME  FROM workflow_process_type"));

function requisitionWiseProductView($SearchId) {
    $sql = "SELECT r.REQUISITION_NO, rd.REQUISITION_DETAILS_ID,p.PRODUCT_ID,p.PRODUCT_NAME AS PRODUCT_NAME, 
            u.UNIT_TYPE_NAME,rd.UNIT_PRICE,rd.QTY,rd.USER_COMMENT
            FROM requisition AS r
            LEFT JOIN requisition_details AS rd ON r.REQUISITION_ID = rd.REQUISITION_ID
            LEFT JOIN product As p ON p.product_id =rd.PRODUCT_ID 
            LEFT JOIN unit_type AS u ON u.UNIT_TYPE_ID =p.unit_type_ID
            WHERE r.REQUISITION_ID = '$SearchId'
            ORDER BY r.REQUISITION_ID";
    return query($sql);
}

function GetProduct() {
    return rs2array(query("SELECT product_id, PRODUCT_NAME
    FROM product AS p 
    LEFT JOIN requisition_route AS rr ON rr.REQUISITION_ROUTE_ID=p.REQUISITION_ROUTE_ID
    ORDER BY p.PRODUCT_NAME"));
}

function GetCostCenter() {
    return rs2array(query("SELECT cost_center_id, cost_center_code, cost_center_Name FROM cost_center"));
}

function GetSole() {
    return rs2array(query("SELECT sol_id, sol_code, SOL_NAME FROM sol ORDER BY SOL_NAME"));
}

function WorkFlowList() {
    return rs2array(query("SELECT workflow_group_id,workflow_name  FROM workflow_group"));
}

function getProductListByTypeProcessDept($productType, $processDept) {
    $sql = "SELECT  PRODUCT_ID, PRODUCT_CODE, PRODUCT_NAME FROM product
    WHERE PRODUCT_TYPE_ID='$productType' AND PROCESS_DEPT_ID='$processDept'
    ORDER BY PRODUCT_NAME";
    return rs2array(query($sql));
}

?>