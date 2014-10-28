<?php

class employee_career {

    public function getEmployeeHeading($employeeId) {

        $db = new DbManager();

        $db->OpenDb();

       $sql = "SELECT CONCAT(em.FIRST_NAME,' ',em.MIDDLE_NAME,' ',em.LAST_NAME) AS 'employee_name', em.EMPLOYEE_ID,deg.DESIGNATION_NAME,em.GRADE_ID,gr.GRADE_NAME,org.ORGANIZATION_NAME,oi.SUPERVISOR_ID
        FROM employee em
        LEFT JOIN designation deg ON deg.DESIGNATION_ID = em.DESIGNATION_ID
        LEFT JOIN grade gr ON gr.GRADE_ID = em.GRADE_ID 
        LEFT JOIN employee_office_info oi ON oi.EMPLOYEE_ID = em.EMPLOYEE_ID
        LEFT JOIN organization org ON org.ORGANIZATION_ID = oi.ORGANIZATION_ID
        WHERE em.EMPLOYEE_ID = '$employeeId'";

        $result = find($sql);
        $db->CloseDb();


        return json_encode($result);
    }

    public function supervisorHeading($cadrNo) {
        $db = new DbManager();
        $db->OpenDb();
        $sql = "SELECT CARD_NO,CONCAT(CARD_NO,'-',FIRST_NAME,' ',MIDDLE_NAME,' ',LAST_NAME) AS 'employeeName'  FROM employee WHERE CARD_NO= '$cadrNo' ";
        $result = find($sql);
        $db->CloseDb();
        return json_encode($result);
    }

    public function getDataCareerInfo($employeeId) {

        $db = new DbManager();

        $db->OpenDb();

        $sql = "SELECT ci.EMPLOYEE_CAREER_INFO_ID, ci.EMPLOYEE_ID,ci.ORGANIZATION_NAME,ci.DESIGNATION_ID,de.DESIGNATION_NAME,
        ci.YEAR_OF_EXPERIENCE,ci.CAREER_START_DATE,ci.CAREER_END_DATE,ci.`STATUS`,CASE WHEN ci.`STATUS` = '1' THEN 'Approved' ELSE 'Pending' END AS 'CAREER_STATUS'
        FROM employee_career_info ci
        LEFT JOIN designation de ON de.DESIGNATION_ID = ci.DESIGNATION_ID
        WHERE ci.EMPLOYEE_ID = '$employeeId'";

        $result = query($sql);
        $db->CloseDb();


        return $result;
    }

    public function getDataCareerId($careerId) {

        $db = new DbManager();

        $db->OpenDb();

        $sql = "SELECT ci.EMPLOYEE_CAREER_INFO_ID, ci.EMPLOYEE_ID,ci.ORGANIZATION_NAME,ci.DESIGNATION_ID,de.DESIGNATION_NAME,
        ci.YEAR_OF_EXPERIENCE,ci.CAREER_START_DATE,ci.CAREER_END_DATE,ci.`STATUS`,CASE WHEN ci.`STATUS` = '1' THEN 'Approved' ELSE 'Pending' END AS 'CAREER_STATUS'
        FROM employee_career_info ci
        LEFT JOIN designation de ON de.DESIGNATION_ID = ci.DESIGNATION_ID
        WHERE ci.EMPLOYEE_CAREER_INFO_ID = '$careerId'";

        $result = find($sql);
        $db->CloseDb();


        return $result;
    }

    public function updateCareer($employeeDTO, $user_name) {

        $db = new DbManager();

        $db->OpenDb();

        $sql = "UPDATE employee_career_info SET
        ORGANIZATION_NAME = '$employeeDTO->organizationName',
        DESIGNATION_ID = '$employeeDTO->designationId',
        YEAR_OF_EXPERIENCE = '$employeeDTO->yearOfExperience',
        CAREER_START_DATE = '$employeeDTO->careerStartDate',
        CAREER_END_DATE = '$employeeDTO->careerEndDate',
        STATUS = '$employeeDTO->status',
        MODIFY_BY = '$user_name',
        MODIFY_DATE = NOW()
        WHERE EMPLOYEE_CAREER_INFO_ID = '$employeeDTO->careerId'";


        $result = query($sql);
        $db->CloseDb();
        return $result;
    }

    public function saveCareer($employeeDTO, $user_name) {

        $db = new DbManager();
        $db->OpenDb();
        $maxCareerId = NextId('employee_career_info', 'EMPLOYEE_CAREER_INFO_ID');

        $sql = "INSERT INTO employee_career_info (EMPLOYEE_CAREER_INFO_ID,EMPLOYEE_ID,ORGANIZATION_NAME,DESIGNATION_ID,
        YEAR_OF_EXPERIENCE,CAREER_START_DATE,
        CAREER_END_DATE,STATUS,CREATED_BY,CREATED_DATE)
        VALUES('$maxCareerId','$employeeDTO->employeeId','$employeeDTO->organizationName','$employeeDTO->designationId',
        '$employeeDTO->yearOfExperience','$employeeDTO->careerStartDate','$employeeDTO->careerEndDate',
        '$employeeDTO->status','$user_name',NOW())";

        $result = query($sql);
        $db->CloseDb();
        return $result;
    }

    public function removeCareer($removeCareerId) {

        $db = new DbManager();
        $db->OpenDb();

        $sql = "DELETE FROM employee_career_info
         WHERE EMPLOYEE_CAREER_INFO_ID='$removeCareerId'";

        $result = query($sql);
        $db->CloseDb();
        return $result;
    }

    public function designationCombo() {
        $db = new DbManager();
        $db->OpenDb();
        $sql = "SELECT DESIGNATION_ID,DESIGNATION_NAME  FROM designation ORDER BY DESIGNATION_NAME";
        $result = rs2array(query($sql));
        $db->CloseDb();
        return $result;
    }

}

?>
