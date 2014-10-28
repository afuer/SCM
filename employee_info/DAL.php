<?php

class DAL {

    public function getData() {


        $db = new DbManager();

        $sql = "SELECT mb.MOBILE_BILL_ID,mb.MOBILE,mb.USER_NAME,d.DIVISION_NAME,bd.BRANCH_DEPT_NAME,des.DESIGNATION_NAME,
mb.BILL_AMOUNT,mb.BILL_AMOUNT - oi.MOBILE_BILL AS 'Excess',mb.APPROVE_AMOUNT,mb.STATUS,oi.OFFICE_PHONE_NO,oi.EMPLOYEE_ID,oi.MOBILE_BILL
FROM mobile_bill mb 
LEFT JOIN division d ON d.DIVISION_ID = mb.DIVISION_ID 
LEFT JOIN designation des ON des.DESIGNATION_ID = mb.DESIGNATION_ID
LEFT JOIN branch_dept bd ON bd.BRANCH_DEPT_ID = mb.BRANCH_DEPT_ID 
LEFT JOIN employee_office_info oi ON oi.OFFICE_PHONE_NO = mb.MOBILE ORDER BY mb.MOBILE";
        $db->OpenDb();
        $result = query($sql);
        $db->CloseDb();


        return $result;
    }

    public function saveMobileBill($MOBILE_BILL_ID, $STATUS, $APPROVE_AMOUNT) {


        $db = new DbManager();

        $sql = "UPDATE mobile_bill SET 
            STATUS = '$STATUS',
            APPROVE_AMOUNT = '$APPROVE_AMOUNT'
            WHERE MOBILE_BILL_ID = '$MOBILE_BILL_ID'
            ";
        $db->OpenDb();
        $result = query($sql);
        $db->CloseDb();


        return $result;
    }

}

?>
