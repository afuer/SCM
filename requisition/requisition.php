<?php

include_once './manager.php';

class requisition extends DbManager {

    public function save($dto) {

        $maxRequisitionId = NextId('requisition', 'REQUISITION_ID');
        $requisition_no = OrderNo($maxRequisitionId);


        $requisition_sql = "INSERT INTO requisition (REQUISITION_ID, REQUISITION_NO, PRESENT_LOCATION_ID, CREATED_BY, REQUISITION_DATE, SPECIFICATION, JUSTIFICATION, REMARK, FREE_TEXT, HELP_DESK, REQUISITION_TYPE_ID, BRANCH_DEPT_ID, OFFICE_TYPE_ID, CREATED_DATE)
        VALUES('$maxRequisitionId','$requisition_no', '$dto->lineManagerId', '$dto->EmployeeId', NOW(),'$dto->specification','$dto->justification','$dto->remark', '$dto->freeText', '$dto->helpDesk', '$dto->requisitionTypeId', '$dto->BranchDeptId', '$dto->OfficeType', NOW())";

        $result = $this->sql($requisition_sql);

        file_upload_save('../documents/requisition/', $maxRequisitionId, 'requisition');

        $processDept = array();
        foreach ($dto->productId as $key => $value) {
            $maxRequisitionDetailsId = NextId('requisition_details', 'REQUISITION_DETAILS_ID');

            $productId = $dto->productId;
            $qty = $dto->qty;
            $processDeptId = $dto->processDeptId;
            $remark = $dto->remark;
            $lastPrice = findValue("SELECT PURCHASE_PRICE FROM product WHERE PRODUCT_ID='$productId[$key]'");

            $sqlDetails = "INSERT INTO requisition_details (REQUISITION_DETAILS_ID, REQUISITION_ID, PRODUCT_ID, QTY, UNIT_PRICE, USER_COMMENT, PROCESS_DEPT_ID) 
                VALUES('$maxRequisitionDetailsId', '$maxRequisitionId', '$productId[$key]', '$qty[$key]', '$lastPrice', '$remark[$key]', '$processDeptId[$key]')";
            $result = $this->sql($sqlDetails);
            $processDept[] = $processDeptId[$key];
        }

        $processDept = array_unique($processDept);
        $processDept = implode(',', $processDept);



        sql("UPDATE requisition SET PROCESS_DEPT_ID='$processDept' WHERE REQUISITION_ID='$maxRequisitionId'");

        $dto->result = $result;
        $dto->requisitionId = $maxRequisitionId;

        return $dto;
    }

    public function update($dto) {


        file_upload_save('../documents/requisition/', "$dto->requisitionId", 'requisition');

        $update_sql = "UPDATE requisition SET
            JUSTIFICATION='$dto->justification', 
            SPECIFICATION='$dto->specification', 
            REMARK='$dto->remark',
            HELP_DESK='$dto->helpDesk',
            FREE_TEXT='$dto->freeText',
            MODIFY_BY='$dto->userName',
            MODIFY_DATE=NOW()
            WHERE REQUISITION_ID='$dto->requisitionId'";
        $result = $this->sql($update_sql);

        //print_r($dto->productId);

        if ($dto->productId != NULL) {
            foreach ($dto->productId as $key => $value) {
                $maxRequisitionDetailsId = NextId('requisition_details', 'REQUISITION_DETAILS_ID');

                $productId = $dto->productId;
                $qty = $dto->qty;
                $lastPrice = findValue("SELECT PURCHASE_PRICE FROM product WHERE PRODUCT_ID='$productId[$key]'");
                $remark = $dto->remark;


                $sqlDetails = "INSERT INTO requisition_details (REQUISITION_DETAILS_ID, REQUISITION_ID, PRODUCT_ID, QTY, UNIT_PRICE, USER_COMMENT) 
                VALUES('$maxRequisitionDetailsId', '$dto->requisitionId', '$productId[$key]', '$qty[$key]', '$lastPrice', '$remark[$key]')";
                $result = $this->sql($sqlDetails);
                //echo "<br/>";
            }
        }

        return $result;
    }

    public function delete() {
        
    }

    public function edit() {
        
    }

    public function RequisitionApproval($offset, $rows, $user_name, $UserLevelId) {
        $result = array();
        $result["total"] = $this->count($user_name);



        if ($UserLevelId == '10') {
            $res = " AND rq.USER_LEVEL_ID='$UserLevelId'";
        } elseif ($UserLevelId == '100') {
            $res = " AND rq.PRESENT_LOCATION_ID='$user_name'";
        }


        $sql = "SELECT rq.REQUISITION_ID, rq.REQUISITION_NO, rq.REQUISITION_DATE, e.FIRST_NAME, rs.status_name, 
        rt.REQUISITION_TYPE_NAME, rq.REQUISITION_STATUS_ID, pd.PROCESS_DEPT_NAME,
        rq.USER_LEVEL_ID, 
        (CASE WHEN USER_LEVEL_ID='$UserLevelId' THEN  
        (SELECT ul.USER_LEVEL_NAME FROM user_level ul WHERE ul.USER_LEVEL_ID='$UserLevelId')
        ELSE CONCAT(e.FIRST_NAME, ' ', e.LAST_NAME, '(',CARD_NO,')') END) AS 'PresentLocation'

        FROM requisition rq
        LEFT JOIN requisition_status rs ON rs.requisition_status_id=rq.REQUISITION_STATUS_ID
        LEFT JOIN requisition_type rt ON rt.REQUISITION_TYPE_ID=rq.REQUISITION_TYPE_ID
        LEFT JOIN employee e ON e.EMPLOYEE_ID=rq.PRESENT_LOCATION_ID
        LEFT JOIN process_dept pd ON pd.PROCESS_DEPT_ID=rq.PROCESS_DEPT_ID
        WHERE rq.REQUISITION_STATUS_ID>0 AND rq.PRESENT_LOCATION_ID='$user_name' AND rq.REQUISITION_TYPE_ID=1

        ORDER BY rq.REQUISITION_ID DESC LIMIT $offset, $rows";
        $sql_result = $this->query($sql);

        $items = array();
        while ($row = fetch_object($sql_result)) {
            array_push($items, $row);
        }
        $result["rows"] = $items;

        return json_encode($result);
    }

    public function getAll($offset, $rows, $user_name) {
        $result = array();
        $result["total"] = $this->countAll($user_name);


        $sql = "SELECT rq.REQUISITION_ID, rq.REQUISITION_NO, rq.REQUISITION_DATE, rs.status_name, 
        rt.REQUISITION_TYPE_NAME, pd.PROCESS_DEPT_NAME, rq.REQUISITION_STATUS_ID,
        (CASE WHEN USER_LEVEL_ID='10' THEN  
        (SELECT ul.USER_LEVEL_NAME FROM user_level ul WHERE ul.USER_LEVEL_ID='10')
        ELSE CONCAT(e.FIRST_NAME, ' ', e.LAST_NAME, '(',CARD_NO,')') END) AS 'PresentLocation'

        FROM requisition rq
        LEFT JOIN requisition_status rs ON rs.requisition_status_id=rq.REQUISITION_STATUS_ID
        LEFT JOIN requisition_type rt ON rt.REQUISITION_TYPE_ID=rq.REQUISITION_TYPE_ID
        LEFT JOIN process_dept pd ON pd.PROCESS_DEPT_ID=rq.PROCESS_DEPT_ID
        LEFT JOIN employee e ON e.EMPLOYEE_ID=rq.PRESENT_LOCATION_ID
        WHERE rq.CREATED_BY='$user_name' 
        ORDER BY rq.REQUISITION_ID DESC LIMIT $offset, $rows";
        $sql_result = $this->query($sql);

        $items = array();
        while ($row = fetch_object($sql_result)) {
            array_push($items, $row);
        }
        $result["rows"] = $items;

        return json_encode($result);
    }

    public function search($offset, $rows, $dto, $user_name) {
        $result = array();
        $result["total"] = $this->count($user_name, $dto);


        $res = '';
        $res .= $dto->requisitionNo == "" ? "" : " AND rq.REQUISITION_NO='$dto->requisitionNo'";
        $res .= $dto->ReqStatus == "" ? "" : " AND rq.REQUISITION_STATUS_ID='$dto->ReqStatus'";
        $res .= $dto->requisitionTypeId == "" ? "" : " AND rq.REQUISITION_TYPE_ID='$dto->requisitionTypeId'";
        $res .= $dto->processDeptId == "" ? "" : " AND rq.PROCESS_DEPT_ID='$dto->processDeptId'";
        $res .= $dto->FromDate == "" && $dto->ToDate == "" ? "" : " AND rq.REQUISITION_DATE BETWEEN '$dto->FromDate' AND '$dto->ToDate'";

        $sql = "SELECT rq.REQUISITION_ID, rq.REQUISITION_NO, rq.REQUISITION_DATE, rs.status_name, 
        rt.REQUISITION_TYPE_NAME, pd.PROCESS_DEPT_NAME, rq.REQUISITION_STATUS_ID,
        (CASE WHEN USER_LEVEL_ID='10' THEN  
        (SELECT ul.USER_LEVEL_NAME FROM user_level ul WHERE ul.USER_LEVEL_ID='10')
        ELSE CONCAT(e.FIRST_NAME,'(',CARD_NO,')') END) AS 'PresentLocation'

        FROM requisition rq
        LEFT JOIN requisition_status rs ON rs.requisition_status_id=rq.REQUISITION_STATUS_ID
        LEFT JOIN requisition_type rt ON rt.REQUISITION_TYPE_ID=rq.REQUISITION_TYPE_ID
        LEFT JOIN process_dept pd ON pd.PROCESS_DEPT_ID=rq.PROCESS_DEPT_ID
        LEFT JOIN employee e ON e.EMPLOYEE_ID=rq.PRESENT_LOCATION_ID
        WHERE rq.CREATED_BY='$user_name' $res ORDER BY rq.CREATED_DATE LIMIT $offset, $rows";
        $sql_result = $this->query($sql);


        $items = array();
        while ($row = fetch_object($sql_result)) {
            array_push($items, $row);
        }
        $result["rows"] = $items;

        return json_encode($result);
    }

    public function count($user_name, $dto = NULL) {


        $res = '';
        $res .= $dto->requisitionNo == "" ? "" : " AND rq.REQUISITION_NO='$dto->requisitionNo'";
        $res .= $dto->ReqStatus == "" ? "" : " AND rq.REQUISITION_STATUS_ID='$dto->ReqStatus'";
        $res .= $dto->processDeptId == "" ? "" : " AND rq.PROCESS_DEPT_ID='$dto->processDeptId'";
        $res .= $dto->requisitionTypeId == "" ? "" : " AND rq.REQUISITION_TYPE_ID='$dto->requisitionTypeId'";
        $res .= $dto->FromDate == "" && $dto->ToDate == "" ? "" : " AND rq.REQUISITION_DATE BETWEEN '$dto->FromDate' AND '$dto->ToDate'";




        $sql = "SELECT count(*) FROM requisition rq WHERE rq.CREATED_BY='$user_name' AND rq.REQUISITION_TYPE_ID=1 $res ";
        $rs = $this->query($sql);
        $row = fetch_row($rs);


        return $row[0];
    }

    public function countAll($user_name, $dto = NULL) {

        $rs = $this->query("SELECT count(*) FROM requisition rq WHERE rq.CREATED_BY='$user_name'");
        $row = fetch_row($rs);


        return $row[0];
    }

    public function getProductGroupAll() {

        $sql = "SELECT PRODUCT_GROUP_ID, GROUP_NAME FROM product_group";
        $result = $this->query($sql);

        return $result;
    }

}

?>
