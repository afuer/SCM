<?php

class DAL extends DbManager {

    public function viewData($search_id) {
        $sql = "SELECT cr.REQUISITION_ID,cr.REQUISITION_NO,cr.REQ_DATE,cr.OFFICETYPE_ID,cr.BRANCH_DEPT_ID,cr.CREATED_BY,cr.REQUISITIONTYPE_ID,rt.REQUISITION_TYPE_NAME,
        cr.PRIOROTY_ID,p.PRIORITY_NAME,cr.PRESENT_LOCATION, 
        CONCAT(em.FIRST_NAME,' ',em.MIDDLE_NAME,' ',em.LAST_NAME,'(',em.CARD_NO,')') As 'presentLocation',
        CONCAT(emp.FIRST_NAME,' ',emp.MIDDLE_NAME,' ',emp.LAST_NAME,'(',emp.CARD_NO,')') As 'requisitionFrom',
        cr.REQUISITIONSTATUS_ID,rs.status_name,cr.DETAILS,cr.JUSTIFICATION,cr.COMMENTS,cr.FILE_PATH,ot.OFFICE_NAME,bd.BRANCH_DEPT_NAME,emp.EMPLOYEE_ID
        FROM civil_requisition cr
        LEFT JOIN requisition_type rt ON rt.REQUISITION_TYPE_ID = cr.REQUISITIONTYPE_ID
        LEFT JOIN priority p ON p.PRIORITY_ID = cr.PRIOROTY_ID
        LEFT JOIN employee em ON em.EMPLOYEE_ID = cr.PRESENT_LOCATION
        LEFT JOIN employee emp ON emp.CARD_NO = cr.CREATED_BY
        LEFT JOIN requisition_status rs ON rs.requisition_status_id = cr.REQUISITIONSTATUS_ID
        LEFT JOIN office_type ot ON ot.OFFICE_TYPE_ID = cr.OFFICETYPE_ID
        LEFT JOIN branch_dept bd ON bd.BRANCH_DEPT_ID = cr.BRANCH_DEPT_ID
        WHERE cr.REQUISITION_ID = '$search_id'  ORDER BY cr.REQUISITION_ID";
        $result = $this->find($sql);


        return $result;
    }

    public function GetEmployeeDetails($card_no) {
        $sql = "SELECT em.EMPLOYEE_ID, CONCAT(em.FIRST_NAME,' ',em.LAST_NAME, '->', em.CARD_NO,' (', d.DESIGNATION_NAME, ')') AS 'empName'
        FROM employee em
        LEFT JOIN designation d ON d.DESIGNATION_ID = em.DESIGNATION_ID
        WHERE CARD_NO ='$card_no'";
        $res = $this->find($sql);
        return json_encode($res);

    }

    public function SaveStackHolder($max, $SL, $STACK_HOLDER_TYPE_ID, $REQUISITION_ID, $MODULE, $EMPLOYEE_ID) {
        $user_name = get('employeeId');
        $sql = "insert into stack_holder (STACK_HOLDER_ID,SL,STACK_HOLDER_TYPE_ID,REQUISITION_ID,MODULE,EMPLOYEE_ID,CREATED_BY,CREATED_DATE) 
            VALUES('$max','$SL','$STACK_HOLDER_TYPE_ID','$REQUISITION_ID','$MODULE','$EMPLOYEE_ID','$user_name',NOW())";

        $result = $this->sql($sql);
        return $result;
    }

    public function ViewStackHolderAll($REQUISITION_ID, $module) {
        $sql = "SELECT sht.STACK_HOLDER_TYPE_NAME, em.CARD_NO, em.FIRST_NAME,em.MIDDLE_NAME,CONCAT(em.LAST_NAME,'->',d.DESIGNATION_NAME) AS 'employeeDetails'
        FROM stack_holder sh 
        LEFT JOIN stack_holder_type sht ON sht.STACK_HOLDER_TYPE_ID = sh.STACK_HOLDER_TYPE_ID
        LEFT JOIN employee em ON em.EMPLOYEE_ID = sh.EMPLOYEE_ID
        LEFT JOIN designation d ON d.DESIGNATION_ID = em.DESIGNATION_ID
        WHERE sh.REQUISITION_ID = '$REQUISITION_ID' and sh.MODULE ='$module'";
        $result = $this->sql($sql);
        return $result;
    }

    public function DeleteAll($search_id) {
        $sqlM = "DELETE FROM stack_holder
         WHERE REQUISITION_ID = '$search_id'";
        $resultM = $this->query($sqlM);
        return $resultM;
    }

}

?>
