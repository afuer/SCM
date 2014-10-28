<?php

class PurchaseOrderApproval extends DbManager {

    function ProductApproval($Requisition_id, $Module, $EmployeeId, $Designation, $comment) {

        $MaxSL = $this->MaxSl($Requisition_id);
        $lineManager = $this->GetLineManager($EmployeeId);

        if ($Designation == '4') {
            $updateRequisition = "UPDATE fin_payment_approval_note SET
            USER_LEVEL_ID='5',
            PRESENT_LOCATION_ID='$lineManager->LINE_MANAGER_ID',
            STATUS='2'
            WHERE PAYMENT_ID='$Requisition_id'";

            $insert_sql = "INSERT INTO workflow_manager (REQUISITION_ID, MODULE_NAME, APPROVAL_COMMENT, LINE_MANAGER_ID, LINE_MANAGER_DESIGNATION_ID, SL, APPROVE_STATUS, CREATED_BY, CREATED_DATE) 
                    VALUES('$Requisition_id', '$Module', '$comment', '$EmployeeId', '$Designation', '$MaxSL', 1, '$EmployeeId', NOW())";

            $this->sql($updateRequisition);
            $this->sql($insert_sql);
        } else {

            $updateRequisition = "UPDATE fin_payment_approval_note SET
            PRESENT_LOCATION_ID='$lineManager->LINE_MANAGER_ID',
            STATUS_ID='2'
            WHERE PAYMENT_ID='$Requisition_id'";

            $insert_sql = "INSERT INTO workflow_manager (REQUISITION_ID, MODULE_NAME, APPROVAL_COMMENT, LINE_MANAGER_ID, LINE_MANAGER_DESIGNATION_ID, SL, APPROVE_STATUS, CREATED_BY, CREATED_DATE) 
                    VALUES('$Requisition_id', '$Module', '$comment', '$EmployeeId', '$Designation', '$MaxSL', 1, '$EmployeeId', NOW())";


            $this->sql($updateRequisition);
            $this->sql($insert_sql);
        }
    }

    public function MaxSl($Requisition_id) {

        $result = $this->query("SELECT MAX(SL) FROM workflow_manager WHERE REQUISITION_ID='$Requisition_id'");


        $row = fetch_array($result);


        $SlMax = $row[0] == NULL ? 0 : $row[0] + 1;
        return $SlMax;
    }

}

?>
