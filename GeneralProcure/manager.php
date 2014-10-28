<?php

class WorkFlowManager extends DbManager {

    public function InsertWorkFlowManager($Requisition_id, $Module, $EmployeeId) {

        $MaxSL = $this->MaxSl($Requisition_id);
        $lineManager = $this->GetLineManager($EmployeeId);

        $insert_sql = "INSERT INTO workflow_manager (REQUISITION_ID, MODULE_NAME, EMPLOYEE_ID, DESIGNATION_ID, SL, CREATED_BY, CREATED_DATE) 
                    VALUES('$Requisition_id', '$Module', '$lineManager->LINE_MANAGER_ID', '$lineManager->DESIGNATION_ID', '$MaxSL', '$EmployeeId', NOW())";


        $this->sql($insert_sql);
    }

    public function CostApproval() {
        
    }

    public function FinanceApproval() {
        
    }

    public function ItProductApproval($Requisition_id, $Module, $employeeId, $Designation, $comment, $lineManagerId) {

        $updateRequisition = "UPDATE requisition SET
            USER_LEVEL_ID=7,
            PRESENT_LOCATION_ID=null,
            REQUISITION_STATUS_ID='3'
            WHERE REQUISITION_ID='$Requisition_id'";


        $insert_sql = "INSERT INTO workflow_manager (REQUISITION_ID, MODULE_NAME, APPROVAL_COMMENT, EMPLOYEE_ID, DESIGNATION_ID, SL, APPROVE_STATUS, CREATED_BY, CREATED_DATE) 
                    VALUES('$Requisition_id', '$Module', '$comment', '$employeeId', '$Designation', '$MaxSL', 1, '$employeeId', NOW())";



        $this->sql($updateRequisition);
        $this->sql($insert_sql);
    }

    public function HeadItProductApproval($Requisition_id, $Module, $employeeId, $Designation, $comment, $lineManagerId) {

        $updateRequisition = "UPDATE requisition SET
            USER_LEVEL_ID=8,
            PRESENT_LOCATION_ID=null,
            REQUISITION_STATUS_ID='3'
            WHERE REQUISITION_ID='$Requisition_id'";


        $insert_sql = "INSERT INTO workflow_manager (REQUISITION_ID, MODULE_NAME, APPROVAL_COMMENT, EMPLOYEE_ID, DESIGNATION_ID, SL, APPROVE_STATUS, CREATED_BY, CREATED_DATE) 
                    VALUES('$Requisition_id', '$Module', '$comment', '$employeeId', '$Designation', '$MaxSL', 1, '$employeeId', NOW())";

        $this->sql($updateRequisition);
        $this->sql($insert_sql);
    }
    
     public function SendManageProduct($Requisition_id, $Module, $employeeId, $Designation, $comment) {

        $updateRequisition = "UPDATE requisition SET
            USER_LEVEL_ID=5,
            PRESENT_LOCATION_ID=null,
            REQUISITION_STATUS_ID='3'
            WHERE REQUISITION_ID='$Requisition_id'";


        $insert_sql = "INSERT INTO workflow_manager (REQUISITION_ID, MODULE_NAME, APPROVAL_COMMENT, EMPLOYEE_ID, DESIGNATION_ID, SL, APPROVE_STATUS, CREATED_BY, CREATED_DATE) 
                    VALUES('$Requisition_id', '$Module', '$comment', '$employeeId', '$Designation', '$MaxSL', 1, '$employeeId', NOW())";



        $this->sql($updateRequisition);
        $this->sql($insert_sql);
    }
    

    public function ProcessDeptApproval($Requisition_id, $Module, $employeeId, $Designation, $comment, $lineManagerId) {

        $updateRequisition = "UPDATE requisition SET
            USER_LEVEL_ID=5,
            PRESENT_LOCATION_ID=null,
            REQUISITION_STATUS_ID='3'
            WHERE REQUISITION_ID='$Requisition_id'";


        $insert_sql = "INSERT INTO workflow_manager (REQUISITION_ID, MODULE_NAME, APPROVAL_COMMENT, EMPLOYEE_ID, DESIGNATION_ID, SL, APPROVE_STATUS, CREATED_BY, CREATED_DATE) 
                    VALUES('$Requisition_id', '$Module', '$comment', '$employeeId', '$Designation', '$MaxSL', 1, '$employeeId', NOW())";



        $this->sql($updateRequisition);
        $this->sql($insert_sql);
    }

    public function ProductApproval($Requisition_id, $Module, $employeeId, $Designation, $comment, $lineManagerId) {

        $MaxSL = $this->MaxSl($Requisition_id);

        $reqInfo = find("SELECT PROCESS_DEPT_ID, REQUISITION_STATUS_ID, USER_LEVEL_ID FROM requisition WHERE REQUISITION_ID='$Requisition_id'");

        if ($Designation == '4') {

            if ($reqInfo->PROCESS_DEPT_ID == '5') {
                $this->ItProductApproval($Requisition_id, $Module, $employeeId, $Designation, $comment, $lineManagerId);
            } else {
                $updateRequisition = "UPDATE requisition SET
                USER_LEVEL_ID=5,
                PRESENT_LOCATION_ID=null,
                REQUISITION_STATUS_ID='3'
                WHERE REQUISITION_ID='$Requisition_id'";


                $insert_sql = "INSERT INTO workflow_manager (REQUISITION_ID, MODULE_NAME, APPROVAL_COMMENT, EMPLOYEE_ID, DESIGNATION_ID, SL, APPROVE_STATUS, CREATED_BY, CREATED_DATE) 
                    VALUES('$Requisition_id', '$Module', '$comment', '$employeeId', '$Designation', '$MaxSL', 1, '$employeeId', NOW())";

                $this->sql($updateRequisition);
                $this->sql($insert_sql);
            }
        } else {

            if ($reqInfo->REQUISITION_STATUS_ID == 3 && $reqInfo->PROCESS_DEPT_ID == 5) {

                if ($reqInfo->REQUISITION_STATUS_ID == 3 && $reqInfo->USER_LEVEL_ID == 7) {
                    $this->HeadItProductApproval($Requisition_id, $Module, $employeeId, $Designation, $comment, $lineManagerId);
                } elseif ($reqInfo->REQUISITION_STATUS_ID == 3 && $reqInfo->USER_LEVEL_ID == 8) {
                    $this->ProcessDeptApproval($Requisition_id, $Module, $employeeId, $Designation, $comment, $lineManagerId);
                }elseif ($reqInfo->REQUISITION_STATUS_ID == 3 && $reqInfo->USER_LEVEL_ID == 5) {
                    $this->SendManageProduct($Requisition_id, $Module, $employeeId, $Designation, $comment, $lineManagerId);
                }else {
                    $updateRequisition = "UPDATE requisition SET
                    PRESENT_LOCATION_ID='$lineManagerId',
                    REQUISITION_STATUS_ID='2'
                    WHERE REQUISITION_ID='$Requisition_id'";

                    $insert_sql = "INSERT INTO workflow_manager (REQUISITION_ID, MODULE_NAME, APPROVAL_COMMENT, EMPLOYEE_ID, DESIGNATION_ID, SL, APPROVE_STATUS, CREATED_BY, CREATED_DATE) 
                    VALUES('$Requisition_id', '$Module', '$comment', '$employeeId', '$Designation', '$MaxSL', 1, '$employeeId', NOW())";

                    $this->sql($updateRequisition);
                    $this->sql($insert_sql);
                }
            } else {

                $updateRequisition = "UPDATE requisition SET
                PRESENT_LOCATION_ID='$lineManagerId',
                REQUISITION_STATUS_ID='2'
                WHERE REQUISITION_ID='$Requisition_id'";

                $insert_sql = "INSERT INTO workflow_manager (REQUISITION_ID, MODULE_NAME, APPROVAL_COMMENT, EMPLOYEE_ID, DESIGNATION_ID, SL, APPROVE_STATUS, CREATED_BY, CREATED_DATE) 
                    VALUES('$Requisition_id', '$Module', '$comment', '$employeeId', '$Designation', '$MaxSL', 1, '$employeeId', NOW())";

                $this->sql($updateRequisition);
                $this->sql($insert_sql);
            }
        }
    }

    public function MaxSl($Requisition_id) {

        $result = $this->query("SELECT MAX(SL) FROM workflow_manager WHERE REQUISITION_ID='$Requisition_id'");


        $row = fetch_array($result);


        $SlMax = $row[0] == NULL ? 0 : $row[0] + 1;
        return $SlMax;
    }

    public function GetLineManager($EmployeeId) {

        $result = $this->query("SELECT FIRST_NAME, LINE_MANAGER_ID, DESIGNATION_ID FROM employee WHERE EMPLOYEE_ID='$EmployeeId'");

        $row = fetch_object($result);

        return $row;
    }

    public function workFlowerManager($Requisition_id, $Module, $EmployeeId) {

        $lineManagerSQL = "SELECT wm.LINE_MANAGER_ID,
	ed.LINE_MANAGER_ID AS NEXT_LINE_MANAGER,
	ed.DESIGNATION_ID
        FROM
	workflow_manager wm
        LEFT OUTER JOIN employee_details ed ON ed.CARDNO = wm.LINE_MANAGER_ID
        LEFT OUTER JOIN designation d ON d.DESIGNATION_ID = ed.DESIGNATION_ID
        WHERE wm.SL=(SELECT MAX(SL) FROM workflow_manager WHERE REQUISITION_ID='$Requisition_id') AND 
        wm.REQUISITION_ID='$Requisition_id' AND ed.CARDNO='$EmployeeId'";

        $lineManagerInfoObj = $this->find($lineManagerSQL);


        $lineManagerID = $lineManagerInfoObj->NEXT_LINE_MANAGER;
        $lineManagerDesignation = $lineManagerInfoObj->DESIGNATION_ID;


        $slNo = $this->FindValue("SELECT max(SL) FROM workflow_manager WHERE REQUISITION_ID='$Requisition_id'");
        $nextSerial = $slNo + 1;

        $requisitionType = $this->findValue("SELECT REQUISITION_REQUIREMENT_TYPE_ID FROM requisition WHERE REQUISITION_ID='$requisitionID'");
        $noOfNode = $this->findValue("SELECT NO_OF_APPROVAL_NODE FROM requisition_requirement_type WHERE REQUISITION_TYPE_ID='$requisitionType'");

        if ($noOfNode > $slNo) {
            $approvalNode = $this->findValue("SELECT APPROVAL_NODE FROM requisition WHERE REQUISITION_ID='$requisitionID'");
            $updatedApprovalNode = $approvalNode + 1;

            $updateRequisitionSQL = "UPDATE requisition SET APPROVAL_NODE='$updatedApprovalNode', PRESENT_LOCATION='$lineManagerID' WHERE REQUISITION_ID='$requisitionID'";
            $this->query($updateRequisitionSQL);

            $updateStatusWorkflowSQL = "UPDATE workflow_manager SET  APPROVE_STATUS='1' WHERE REQUISITION_ID='$requisitionID'
            AND SL='$slNo'";
            $this->query($updateStatusWorkflowSQL);

            $InsertSQL = "INSERT INTO workflow_manager (REQUISITION_ID, LINE_MANAGER_ID, LINE_MANAGER_DESIGNATION_ID, SL) 
            VALUES ('$requisitionID', '$lineManagerID', '$lineManagerDesignation', '$nextSerial')";
            $this->query($InsertSQL);
        } else {
            $updateSQL = "UPDATE requisition SET APPROVAL_NODE_TYPE='', APPROVAL_NODE='0' WHERE REQUISITION_ID='$requisitionID'";
            $this->query($updateSQL);
        }
    }

    public function SendFinance($csId) {

        $update = "UPDATE requisition_approval SET PRESENT_LOCATION_ID=null, USER_LEVEL_ID='17' WHERE CS_ID='$csId'";
        sql($update);
    }

    public function insertWorkFlow($requisitionId, $employeeId, $designation, $approvalComment) {
        sql("INSERT INTO workflow_manager(WORKFLOW_PROCESS_ID, MODULE_NAME, REQUISITION_ID, EMPLOYEE_ID, DESIGNATION_ID, APPROVE_STATUS, APPROVAL_COMMENT, CREATED_BY, CREATED_DATE)
            VALUES('1', '', '$requisitionId', '$employeeId', '$designation', '1', '$approvalComment', '$employeeId', NOW())");
    }

    public function SendHeadOfProcure($csId) {
        $update = "UPDATE requisition_approval SET PRESENT_LOCATION_ID=null, USER_LEVEL_ID='6' WHERE CS_ID='$csId'";
        sql($update);
        $sqlRequisition = "UPDATE requisition_approval SET `STATUS`=3 WHERE CS_ID='$csId'";
        sql($sqlRequisition);
    }
    
     public function SendHeadOfIT($csId) {
        $update = "UPDATE requisition_approval SET PRESENT_LOCATION_ID=null, USER_LEVEL_ID='8' WHERE CS_ID='$csId'";
        sql($update);
        $sqlRequisition = "UPDATE requisition_approval SET `STATUS`=3 WHERE CS_ID='$csId'";
        sql($sqlRequisition);
    }

    public function SendHeadOfPayment($csId) {
        $update = "UPDATE requisition_approval SET PRESENT_LOCATION_ID=null, USER_LEVEL_ID='25' WHERE CS_ID='$csId'";
        sql($update);
    }

    public function SendCFO($csId) {
        $update = "UPDATE requisition_approval SET PRESENT_LOCATION_ID=null, USER_LEVEL_ID='16' WHERE CS_ID='$csId'";
        sql($update);
    }

    public function ApprovalMatrix($requisitionId, $module, $csId, $employeeId, $approvalComment) {

        $sql = "SELECT wm.WORKFLOW_MANAGER_ID, 
        wm.EMPLOYEE_ID, wm.DESIGNATION_ID, SL, APPROVE_STATUS
        
        FROM workflow_manager wm
        WHERE REQUISITION_ID='$requisitionId' AND MODULE_NAME='$module' AND APPROVE_STATUS=0
        ORDER BY wm.WORKFLOW_PROCESS_ID LIMIT 0,1";
        $emp = find($sql);

        //echo $emp->WORKFLOW_MANAGER_ID.'===';

        $update = "UPDATE requisition_approval SET PRESENT_LOCATION_ID='$emp->EMPLOYEE_ID', USER_LEVEL_ID=null WHERE CS_ID='$csId'";
        sql($update);

        $countWorkflow = findValue($sql);
        if ($countWorkflow > 0) {
            $sqlWorkFlow = "UPDATE workflow_manager SET MODIFY_BY='$employeeId', APPROVE_STATUS=1, APPROVAL_COMMENT='$approvalComment' WHERE WORKFLOW_MANAGER_ID='$emp->WORKFLOW_MANAGER_ID'";
            sql($sqlWorkFlow);
            $sqlRequisition = "UPDATE requisition_approval SET `STATUS`=3 WHERE CS_ID='$csId'";
            sql($sqlRequisition);
        } else {
            $sqlWorkFlow = "UPDATE requisition_approval SET `STATUS`=10, USER_LEVEL_ID=10 WHERE CS_ID='$csId'";
            sql($sqlWorkFlow);

            $sqlWorkFlow = "UPDATE workflow_manager SET MODIFY_BY='$employeeId', APPROVE_STATUS=1, APPROVAL_COMMENT='$approvalComment' WHERE WORKFLOW_MANAGER_ID='$emp->WORKFLOW_MANAGER_ID'";
            sql($sqlWorkFlow);
        }
    }

}

?>
