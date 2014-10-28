<?php

class employee_qualification {

    
    public function getDataQualification($employeeId) {

        $db = new DbManager();

        $db->OpenDb();

         $sql = "SELECT eq.EMPLOYEE_QUALIFICATION_ID,eq.EMPLOYEE_ID,eq.QUALIFICATION_AREA,eq.QUALIFICATION_TITLE,
        eq.INSTITUTE,eq.RESULT,eq.START_DATE,eq.END_DATE
        FROM employee_qualification eq
        WHERE eq.EMPLOYEE_ID='$employeeId'";

        $result = query($sql);
        $db->CloseDb();


        return $result;
    }

    public function getDataQualificationId($primaryId) {

        $db = new DbManager();

        $db->OpenDb();

        $sql = "SELECT eq.EMPLOYEE_QUALIFICATION_ID,eq.EMPLOYEE_ID,eq.QUALIFICATION_AREA,eq.QUALIFICATION_TITLE,
        eq.INSTITUTE,eq.RESULT,eq.START_DATE,eq.END_DATE
        FROM employee_qualification eq
        WHERE eq.EMPLOYEE_QUALIFICATION_ID='$primaryId'";

        $result = find($sql);
        $db->CloseDb();


        return $result;
    }

    public function updateMaster($employeeDTO, $user_name) {

        $db = new DbManager();

        $db->OpenDb();

        $sql = "UPDATE employee_qualification SET
        QUALIFICATION_AREA = '$employeeDTO->qualificationArea',
        QUALIFICATION_TITLE = '$employeeDTO->qualificationTitle',
        INSTITUTE = '$employeeDTO->institute',
        RESULT = '$employeeDTO->result',
        START_DATE = '$employeeDTO->quaStartDate',
        END_DATE = '$employeeDTO->quaEndDate',
        MODIFY_BY = '$user_name',
        MODIFY_DATE = NOW()
        WHERE EMPLOYEE_QUALIFICATION_ID = '$employeeDTO->primaryId'";


        $result = query($sql);
        $db->CloseDb();
        return $result;
    }

    public function saveMaster($employeeDTO, $user_name) {

        $db = new DbManager();
        $db->OpenDb();
        $maxMasterId = NextId('employee_qualification', 'EMPLOYEE_QUALIFICATION_ID');

        $sql = "INSERT INTO employee_qualification (EMPLOYEE_QUALIFICATION_ID,EMPLOYEE_ID,QUALIFICATION_AREA,QUALIFICATION_TITLE,
        INSTITUTE,RESULT,
        START_DATE,END_DATE,CREATED_BY,CREATED_DATE)
        VALUES('$maxMasterId','$employeeDTO->employeeId','$employeeDTO->qualificationArea','$employeeDTO->qualificationTitle',
        '$employeeDTO->institute','$employeeDTO->result','$employeeDTO->quaStartDate',
        '$employeeDTO->quaEndDate','$user_name',NOW())";

        $result = query($sql);
        $db->CloseDb();
        return $result;
    }

    public function removeMaster($removePrimaryId) {

        $db = new DbManager();
        $db->OpenDb();

        $sql = "DELETE FROM employee_qualification
         WHERE EMPLOYEE_QUALIFICATION_ID='$removePrimaryId'";

        $result = query($sql);
        $db->CloseDb();
        return $result;
    }



}

?>
