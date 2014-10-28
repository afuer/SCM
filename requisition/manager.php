<?php

class WorkFlowManager extends DbManager {

    public function InsertWorkFlowManager($Requisition_id, $Module, $EmployeeId) {

        $MaxSL = $this->MaxSl($Requisition_id);
        $lineManager = $this->GetLineManager($EmployeeId);

        $insert_sql = "INSERT INTO workflow_manager (REQUISITION_ID, MODULE_NAME, LINE_MANAGER_ID, LINE_MANAGER_DESIGNATION_ID, SL, CREATED_BY, CREATED_DATE) 
                    VALUES('$Requisition_id', '$Module', '$lineManager->LINE_MANAGER_ID', '$lineManager->DESIGNATION_ID', '$MaxSL', '$EmployeeId', NOW())";


        $this->sql($insert_sql);
    }

    public function CostApproval() {
        
    }

    public function FinanceApproval() {
        
    }

    public function ProductApproval($Requisition_id, $Module, $EmployeeId, $Designation, $comment, $lineManagerId) {

        $MaxSL = $this->MaxSl($Requisition_id);

        $updateRequisition = "UPDATE requisition SET
            USER_LEVEL_ID='10',
            PRESENT_LOCATION_ID='$lineManagerId',
            REQUISITION_STATUS_ID='3'
            WHERE REQUISITION_ID='$Requisition_id'";

        $updateRequisitionDeatils = "UPDATE requisition_details SET
            DETAILS_STATUS='1',
            STATUS_APP_LEVEL='-1'
            WHERE REQUISITION_ID='$Requisition_id'";


        $insert_sql = "INSERT INTO workflow_manager(REQUISITION_ID, MODULE_NAME, APPROVAL_COMMENT, EMPLOYEE_ID, DESIGNATION_ID, SL, APPROVE_STATUS, CREATED_BY, CREATED_DATE) 
                    VALUES('$Requisition_id', '$Module', '$comment', '$EmployeeId', '$Designation', '$MaxSL', 1, '$EmployeeId', NOW())";

        $this->sql($updateRequisition);
        $this->sql($updateRequisitionDeatils);
        $this->sql($insert_sql);
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
        WHERE wm.SL=
        (SELECT MAX(SL) FROM workflow_manager WHERE REQUISITION_ID='$Requisition_id') AND 
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

}

?>
