<?php

class employee extends DbManager {

    public function getEmployeeHeading($employeeId) {

        $sql = "SELECT em.FIRST_NAME,em.MIDDLE_NAME,em.LAST_NAME, em.EMPLOYEE_ID,em.CARD_NO,deg.DESIGNATION_NAME,eo.GRADE_ID,
        gr.GRADE_NAME,s.SUPPLIER_NAME,em.LINE_MANAGER_ID FROM employee em 
        LEFT JOIN employee_office_info eo ON eo.EMPLOYEE_ID = em.EMPLOYEE_ID 
        LEFT JOIN designation deg ON deg.DESIGNATION_ID = em.DESIGNATION_ID 
        LEFT JOIN grade gr ON gr.GRADE_ID = eo.GRADE_ID 
        LEFT JOIN supplier s ON s.SUPPLIER_ID = eo.SUPPLIER_ID
        WHERE em.EMPLOYEE_ID = '$employeeId'";

        $result = $this->find($sql);

        return $result;
    }

    public function supervisorHeading($EMPLOYEE_ID) {

        $sql = "SELECT CARD_NO,FIRST_NAME,MIDDLE_NAME,LAST_NAME,CONCAT('(',deg.DESIGNATION_NAME,')') AS 'DESIGNATION_NAME'
        FROM employee em
        LEFT JOIN designation deg ON deg.DESIGNATION_ID = em.DESIGNATION_ID
        WHERE em.EMPLOYEE_ID= '$EMPLOYEE_ID' ";

        $result = $this->find($sql);

        return $result;
    }

    public function getDataPersonalInfo($employeeId) {

       $sql = "SELECT em.EMPLOYEE_ID, em.CARD_NO, em.FIRST_NAME,em.MIDDLE_NAME,em.LAST_NAME,em.MARITAL_STATUS_ID,em.GANDER_ID,em.DATE_OF_MARRIAGE,em.BLOOD_GROUP_ID,
        em.NATIONALITY_ID,em.DATE_OF_BIRTH,em.RELIGION_ID,em.NATIONAL_ID,em.PASSPORT_NO,em.PASSPORT_ISSUE_DATE,em.PASSPORT_EXPIRE_DATE,em.TAX_ID,em.CELL_NO,
        em.PERSONAL_EMAIL,em.PABAX_NO,em.PABX_EXT,em.REFERENCE_INFO,em.EMERGENCY_PHONE_NO,em.HOME_PHONE_NO,
        ms.MARITAL_STATUS_NAME,g.GANDER_NAME,bg.BLOOD_GROUP_NAME,coun.COUNTRY_NAME,re.RELIGION_NAME

        FROM employee em 
        LEFT JOIN marital_status ms ON ms.MARITAL_STATUS_ID = em.MARITAL_STATUS_ID
        LEFT JOIN gander g ON g.GANDER_ID = em.GANDER_ID
        LEFT JOIN blood_group bg ON bg.BLOOD_GROUP_ID = em.BLOOD_GROUP_ID
        LEFT JOIN country coun ON coun.COUNTRY_ID = em.NATIONALITY_ID
        LEFT JOIN religion re ON re.RELIGION_ID = em.RELIGION_ID
        WHERE em.EMPLOYEE_ID = '$employeeId'";

        $result = $this->find($sql);

        return $result;
    }

    public function getDataOfficeInfo($employeeId) {


        $sql = "SELECT oi.EMPLOYEE_OFFICE_INFO_ID,oi.EMPLOYEE_TYPE_ID,et.EMPLOYEE_TYPE_NAME,oi.SUPPLIER_ID,s.SUPPLIER_NAME, 
        oi.OFFICE_TYPE_ID, ot.OFFICE_NAME,oi.OFFICE_PHONE_NO,oi.JOINING_DATE,oi.ASSIGNMENT_CATEGORY_ID,
        ac.ASSIGNMENT_CATEGORY_NAME,oi.OFFICE_EMAIL,oi.HANDICAP_INFO,oi.RETIREMENT_DATE,oi.LOCATION,
        emp.LINE_MANAGER_ID,oi.SALARY,gra.GRADE_NAME,oi.JOB,oi.MOBILE_BILL,oi.INTERNET_BILL,oi.OTHERS_BILL,
        oi.GRADE_ID,emp.IS_RELIEVER,emp.RELIEVER_EMP_ID
        FROM employee_office_info oi 
        LEFT JOIN employee_type et ON et.EMPLOYEE_TYPE_ID = oi.EMPLOYEE_TYPE_ID 
        LEFT JOIN supplier s ON s.SUPPLIER_ID = oi.SUPPLIER_ID
        LEFT JOIN employee emp ON emp.EMPLOYEE_ID = oi.EMPLOYEE_ID 
        LEFT JOIN grade gra ON gra.GRADE_ID = oi.GRADE_ID 
        LEFT JOIN office_type ot ON ot.OFFICE_TYPE_ID = oi.OFFICE_TYPE_ID
        LEFT JOIN assignment_category ac ON ac.ASSIGNMENT_CATEGORY_ID = oi.ASSIGNMENT_CATEGORY_ID 
        WHERE oi.EMPLOYEE_ID = '$employeeId'";

        $result = $this->find($sql);

        return $result;
    }

    public function getDataBankAccountInfo($employeeId) {


        $sql = "SELECT ai.EMPLOYEE_BANK_ACCOUNT_INFO_ID, ai.ACCOUNT_NUMBER,ai.ACCOUNT_TYPE_ID,act.ACCOUNT_TYPE_NAME,bd.BRANCH_DEPT_NAME,ai.BRANCH_ID 
        FROM employee_bank_account_info ai 
        LEFT JOIN account_type act ON act.ACCOUNT_TYPE_ID = ai.ACCOUNT_TYPE_ID 
        LEFT JOIN branch_dept bd ON bd.BRANCH_DEPT_ID = ai.BRANCH_ID
        WHERE ai.EMPLOYEE_ID = '$employeeId'";

        $result = $this->find($sql);

        return $result;
    }

    public function getDataFamilyInfo($employeeId) {


        $sql = "SELECT fi.EMPLOYEE_FAMILY_INFO_ID,fi.FAMILY_MEMBER_NAME,fi.FAMILY_RELATIONSHIP_TYPE,fi.IS_CBL_EMPLOYEE,
        fi.EMAIL,fi.CONTACT_PHONE_NO,fi.PROFESSION,fi.DATE_OF_BIRTH,CASE WHEN fi.IS_CBL_EMPLOYEE ='1' THEN 'Yes' ELSE 'No' END AS 'STATUS'
        FROM employee_family_info fi 
        WHERE fi.EMPLOYEE_ID = '$employeeId'";

        $result = $this->find($sql);

        return json_encode($result);
    }

    public function getDataAddress($employeeId) {

        $sql = "SELECT ea.EMPLOYEE_ADDRESS_ID,ea.EMPLOYEE_ID,ea.PERMANENT_ADDRESS1,ea.PERMANENT_ADDRESS2,ea.PERMANENT_POSTAL_CODE,
        ea.PERMANENT_THANA_ID,ea.PRESENT_ADDRESS1,ea.PRESENT_ADDRESS2,ea.PRESENT_POSTAL_CODE,ea.PRESENT_THANA_ID,
        th.THANA_NAME AS 'PRESENT_THANA',th1.THANA_NAME AS 'PERMANENT_THANA'
        FROM employee_address ea 
        LEFT JOIN thana th ON th.THANA_ID = ea.PRESENT_THANA_ID 
        LEFT JOIN thana th1 ON  th1.THANA_ID = ea.PERMANENT_THANA_ID
        WHERE ea.EMPLOYEE_ID = '$employeeId' ";

        $result = $this->find($sql);

        return $result;
    }

    public function getDataNominee($employeeId) {


        $sql = "SELECT ni.EMPLOYEE_NOMINEE_INFO_ID,ni.IS_FAMILY_MEMBER, CASE WHEN ni.IS_FAMILY_MEMBER = '1' THEN 'Yes' ELSE 'No' END AS 'STATUS',
        ni.NOMINEE_TYPE_ID, nt.NOMINEE_TYPE_NAME,ni.NOMINEE_NAME,ni.RELATIONSHIP,ni.DATE_OF_BIRTH,ni.NOMINEE_PERCENTAGE
        FROM employee_nominee_info ni
        LEFT JOIN nominee_type nt ON nt.NOMINEE_TYPE_ID = ni.NOMINEE_TYPE_ID
        WHERE ni.EMPLOYEE_ID = '$employeeId'";

        $result = $this->find($sql);

        return json_encode($result);
    }

    public function getDataEducation($employeeId) {


        $sql = "SELECT ei.EMPLOYEE_EDUCATION_INFO_ID,ei.EMPLOYEE_ID,ei.QUALIFICATION_TITLE,ei.MAJOR,ei.PASSING_YEAR,
        ei.CGPA_PERCENTAGE,ei.INSTITUTE_NAME,ei.`STATUS`,ei.START_DATE,ei.END_DATE,ei.CAREER_INFO
        FROM employee_education_info ei 
        WHERE ei.EMPLOYEE_ID = '$employeeId'";

        $result = $this->find($sql);

        return json_encode($result);
    }

    public function getDataLogin($employeeId) {

        $sql = "SELECT el.EMPLOYEE_ID,el.USER_NAME,el.USER_TYPE_ID,el.USER_PASS,el.EMPLOYEE_ID, el.USER_LEVEL_ID,el.ROUTE_ID, ul.USER_LEVEL_NAME,em.CARD_NO
        FROM master_user el 
        LEFT JOIN user_level ul ON ul.USER_LEVEL_ID = el.USER_LEVEL_ID
        LEFT JOIN employee em ON em.EMPLOYEE_ID = el.EMPLOYEE_ID
        WHERE el.EMPLOYEE_ID ='$employeeId'";

        $result = $this->find($sql);

        return $result;
    }

    public function getDataCardNo($employeeId) {

        $sql = "SELECT CARD_NO 
        FROM employee 
        WHERE EMPLOYEE_ID='$employeeId'";

        $result = $this->findValue($sql);

        return json_encode($result);
    }

    public function countAddress($employeeId) {


        $sql = "SELECT COUNT(*)
        FROM employee_address 
        WHERE EMPLOYEE_ID = '$employeeId'";

        $result = $this->findValue($sql);

        return $result;
    }

    public function countOffice($employeeId) {

        $sql = "SELECT COUNT(*)
        FROM employee_office_info 
        WHERE EMPLOYEE_ID = '$employeeId'";

        $result = $this->findValue($sql);

        return $result;
    }

    public function countBank($employeeId) {

        $sql = "SELECT COUNT(*)
        FROM employee_bank_account_info 
        WHERE EMPLOYEE_ID = '$employeeId'";

        $result = $this->findValue($sql);

        return $result;
    }

    public function countFamily($employeeId) {

        $sql = "SELECT COUNT(*)
        FROM employee_family_info 
        WHERE EMPLOYEE_ID = '$employeeId'";

        $result = $this->findValue($sql);

        return $result;
    }

    public function countNominee($employeeId) {

        $sql = "SELECT COUNT(*)
        FROM employee_nominee_info 
        WHERE EMPLOYEE_ID = '$employeeId'";

        $result = $this->findValue($sql);

        return $result;
    }

    public function countLogin($employeeId) {


        $sql = "SELECT COUNT(*)
        FROM master_user 
        WHERE EMPLOYEE_ID = '$employeeId'";

        $result = $this->findValue($sql);

        return $result;
    }

    public function countEducation($employeeId) {

        $sql = "SELECT COUNT(*)
        FROM employee_education_info 
        WHERE EMPLOYEE_ID = '$employeeId'";

        $result = $this->findValue($sql);

        return $result;
    }

    public function updateBasic($employeeDTO) {


        $sql = "UPDATE employee SET
        FIRST_NAME = '$employeeDTO->firstName',
        MIDDLE_NAME = '$employeeDTO->middleName',
        LAST_NAME = '$employeeDTO->lastName',
        MARITAL_STATUS_ID = '$employeeDTO->maritalStatusId',
        GANDER_ID = '$employeeDTO->ganderId',
        NATIONALITY_ID = '$employeeDTO->nationalityId',
        DATE_OF_BIRTH = '$employeeDTO->dateOfBirth',
        RELIGION_ID = '$employeeDTO->religionId',
        NATIONAL_ID = '$employeeDTO->nationalId',
        PASSPORT_NO = '$employeeDTO->passportNo',
        PASSPORT_ISSUE_DATE = '$employeeDTO->passportIssueDate',
        PASSPORT_EXPIRE_DATE = '$employeeDTO->passportExpireDate',
        CELL_NO = '$employeeDTO->sellNo',
        EMERGENCY_PHONE_NO = '$employeeDTO->emergencyPhoneNo',
        HOME_PHONE_NO = '$employeeDTO->homePhoneNo',
        PERSONAL_EMAIL = '$employeeDTO->personalEmail',
        PABAX_NO = '$employeeDTO->pabaxNo',
        PABX_EXT = '$employeeDTO->pabxExt',
        REFERENCE_INFO = '$employeeDTO->referenceInfo',
        MODIFY_BY = 'user_name',
        MODIFY_DATE = NOW()
        WHERE EMPLOYEE_ID ='$employeeDTO->employeeId' ";

        $result = $this->query($sql);

        return $result;
    }

    public function updateOfficeInfo($employeeDTO) {


        $sql = "UPDATE employee_office_info SET
        EMPLOYEE_TYPE_ID = '$employeeDTO->employeeTypeId',
        SUPPLIER_ID = '$employeeDTO->supplierId',
        SALARY = '$employeeDTO->salary',
        JOB = '$employeeDTO->job',
        GRADE_ID = '$employeeDTO->gradeId',
        OFFICE_TYPE_ID = '$employeeDTO->officeTypeId',
        OFFICE_PHONE_NO = '$employeeDTO->officePhoneNo',
        JOINING_DATE = '$employeeDTO->joiningDate',
        ASSIGNMENT_CATEGORY_ID = '$employeeDTO->assignmentCategoryId',
        HANDICAP_INFO = '$employeeDTO->handiCapInfo',
        OFFICE_EMAIL = '$employeeDTO->officeEmail',
        RETIREMENT_DATE = '$employeeDTO->retireMentDate',
        LOCATION = '$employeeDTO->location',
        MOBILE_BILL = '$employeeDTO->mobileBill',
        INTERNET_BILL = '$employeeDTO->internetBill',
        OTHERS_BILL = '$employeeDTO->othersBill',
        MODIFY_BY = '$user_name',
        MODIFY_DATE = NOW()
        WHERE EMPLOYEE_OFFICE_INFO_ID = '$employeeDTO->employeeOfficeinfoId'";


        $result = $this->query($sql);

        $lineManagerEmployeeId = $this->findValue("SELECT EMPLOYEE_ID FROM employee  WHERE CARD_NO ='$employeeDTO->lineManagerId'");
        $relieverEmployeeId = $this->findValue("SELECT EMPLOYEE_ID FROM employee  WHERE CARD_NO ='$employeeDTO->reliever_id'");



       
            $sqlLineManager = "UPDATE employee SET
            LINE_MANAGER_ID = '$lineManagerEmployeeId',
            IS_RELIEVER = '$employeeDTO->isReliever',
            RELIEVER_EMP_ID = '$relieverEmployeeId'
            WHERE EMPLOYEE_ID = '$employeeDTO->employeeId'";
            $this->query($sqlLineManager);

            return $result;
    }

    public function saveOffice($employeeDTO, $user_name) {


        $maxOfficeId = NextId('employee_office_info', 'EMPLOYEE_OFFICE_INFO_ID');


        $sql = "INSERT INTO employee_office_info (EMPLOYEE_OFFICE_INFO_ID,EMPLOYEE_ID,EMPLOYEE_TYPE_ID,SUPPLIER_ID,SALARY,JOB,
        GRADE_ID,OFFICE_TYPE_ID,OFFICE_PHONE_NO,JOINING_DATE,ASSIGNMENT_CATEGORY_ID,HANDICAP_INFO,OFFICE_EMAIL,
        RETIREMENT_DATE,LOCATION,MOBILE_BILL,INTERNET_BILL,OTHERS_BILL,CREATED_BY,CREATED_DATE)
        VALUES('$maxOfficeId','$employeeDTO->employeeId','$employeeDTO->employeeTypeId','$employeeDTO->supplierId',
         '$employeeDTO->salary',
        '$employeeDTO->job','$employeeDTO->gradeId',
        '$employeeDTO->officeTypeId','$employeeDTO->officePhoneNo','$employeeDTO->joiningDate',
        '$employeeDTO->assignmentCategoryId','$employeeDTO->handiCapInfo','$employeeDTO->officeEmail',
         '$employeeDTO->retireMentDate','$employeeDTO->location','$employeeDTO->mobileBill',
        '$employeeDTO->internetBill','$employeeDTO->othersBill','$user_name',NOW())";


        $result = $this->query($sql);

        $lineManagerEmployeeId = $this->findValue("SELECT EMPLOYEE_ID FROM employee  WHERE CARD_NO ='$employeeDTO->lineManagerId'");
        $relieverEmployeeId = $this->findValue("SELECT EMPLOYEE_ID FROM employee  WHERE CARD_NO ='$employeeDTO->reliever_id'");


        
            $sqlLineManager = "UPDATE employee SET
            LINE_MANAGER_ID = '$lineManagerEmployeeId',
            IS_RELIEVER = '$employeeDTO->isReliever',
            RELIEVER_EMP_ID = '$relieverEmployeeId'
            WHERE EMPLOYEE_ID = '$employeeDTO->employeeId'";
            $this->query($sqlLineManager);
        

        return $result;
    }

    public function updateBankAccountInfo($employeeBankAccountInfoId, $accountNumber, $accountTypeId, $branchId, $user_name) {



        $sql = "UPDATE employee_bank_account_info SET
        ACCOUNT_NUMBER = '$accountNumber',
        ACCOUNT_TYPE_ID = '$accountTypeId',
        BRANCH_ID = '$branchId',
        MODIFY_BY = '$user_name',
        MODIFY_DATE = NOW()
        WHERE EMPLOYEE_BANK_ACCOUNT_INFO_ID = '$employeeBankAccountInfoId'";


        $result = $this->query($sql);

        return $result;
    }

    public function saveBankInfo($employeeId, $accountNumber, $accountTypeId, $branchId, $user_name) {


        $maxBankId = NextId('employee_bank_account_info', 'EMPLOYEE_BANK_ACCOUNT_INFO_ID');


        $sql = "INSERT INTO employee_bank_account_info (EMPLOYEE_BANK_ACCOUNT_INFO_ID,EMPLOYEE_ID,ACCOUNT_NUMBER,
        ACCOUNT_TYPE_ID,BRANCH_ID,CREATED_BY,CREATED_DATE )
        
        VALUES('$maxBankId','$employeeId','$accountNumber','$accountTypeId',
        '$branchId','$user_name',NOW())";

        $result = $this->query($sql);

        return $result;
    }

    public function updateAddress($employeeDTO, $user_name) {


        $sql = "UPDATE employee_address SET
        PRESENT_ADDRESS1 = '$employeeDTO->presentAddress1',
        PERMANENT_ADDRESS1 = '$employeeDTO->permanentAddress1',
        PRESENT_ADDRESS2 = '$employeeDTO->presentAddress2',
        PERMANENT_ADDRESS2 = '$employeeDTO->permanentAddress2',
        PRESENT_THANA_ID = '$employeeDTO->presentThanaId',
        PERMANENT_THANA_ID = '$employeeDTO->permanentThanaId',
        PRESENT_POSTAL_CODE = '$employeeDTO->presentPostalCode',
        PERMANENT_POSTAL_CODE = '$employeeDTO->permanentPostalCode',
        MODIFY_BY = '$user_name',
        MODIFY_DATE = NOW()
        WHERE EMPLOYEE_ADDRESS_ID = '$employeeDTO->employeeAddressId'";


        $result = $this->query($sql);

        return $result;
    }

    public function saveAddress($employeeDTO, $user_name) {

        $MaxAddressId = NextId('employee_address', 'EMPLOYEE_ADDRESS_ID');

        $sql = "INSERT INTO employee_address (EMPLOYEE_ADDRESS_ID,EMPLOYEE_ID,PRESENT_ADDRESS1,PERMANENT_ADDRESS1,PRESENT_ADDRESS2,PERMANENT_ADDRESS2,
        PRESENT_THANA_ID,PERMANENT_THANA_ID,PRESENT_POSTAL_CODE,PERMANENT_POSTAL_CODE,CREATED_BY,CREATED_DATE)
        VALUES('$MaxAddressId','$employeeDTO->employeeId','$employeeDTO->presentAddress1','$employeeDTO->permanentAddress1',
        '$employeeDTO->presentAddress2','$employeeDTO->permanentAddress2','$employeeDTO->presentThanaId',
        '$employeeDTO->permanentThanaId','$employeeDTO->presentPostalCode','$employeeDTO->permanentPostalCode',
        '$user_name',NOW())";

        $result = $this->query($sql);

        return $result;
    }

    public function updateFamily($employeeDTO, $user_name) {


        $sql = "UPDATE employee_family_info SET
        FAMILY_MEMBER_NAME = '$employeeDTO->familyMemberName',
        FAMILY_RELATIONSHIP_TYPE = '$employeeDTO->familyRelationtype',
        IS_CBL_EMPLOYEE = '$employeeDTO->isCblEmployee',
        EMAIL = '$employeeDTO->email',
        CONTACT_PHONE_NO = '$employeeDTO->contactPhoneNo',
        PROFESSION = '$employeeDTO->profession',
        DATE_OF_BIRTH = '$employeeDTO->familyMemberDateOfBirth',
        MODIFY_BY = '$user_name',
        MODIFY_DATE = NOW()
        WHERE EMPLOYEE_FAMILY_INFO_ID = '$employeeDTO->employeeFamilyInfoId'";


        $result = $this->query($sql);

        return $result;
    }

    public function saveFamily($employeeDTO, $user_name) {

        $MaxfamilyId = NextId('employee_family_info', 'EMPLOYEE_FAMILY_INFO_ID');

        $sql = "INSERT INTO employee_family_info (EMPLOYEE_FAMILY_INFO_ID,EMPLOYEE_ID,FAMILY_MEMBER_NAME,FAMILY_RELATIONSHIP_TYPE,
        IS_CBL_EMPLOYEE,EMAIL,CONTACT_PHONE_NO,PROFESSION,DATE_OF_BIRTH,CREATED_BY,CREATED_DATE)
        
        VALUES('$MaxfamilyId','$employeeDTO->employeeId','$employeeDTO->familyMemberName','$employeeDTO->familyRelationtype',
        '$employeeDTO->isCblEmployee','$employeeDTO->email','$employeeDTO->contactPhoneNo',
        '$employeeDTO->profession','$employeeDTO->familyMemberDateOfBirth','$user_name',NOW())";


        $result = $this->query($sql);

        return $result;
    }

    public function updateNominee($employeeDTO, $user_name) {


        $sql = "UPDATE employee_nominee_info SET
        IS_FAMILY_MEMBER = '$employeeDTO->isFamilyMember',
        NOMINEE_TYPE_ID = '$employeeDTO->nomineeTypeId',
        NOMINEE_NAME = '$employeeDTO->nomineeName',
        RELATIONSHIP = '$employeeDTO->relationship',
        DATE_OF_BIRTH = '$employeeDTO->nomineeBirthday',
        NOMINEE_PERCENTAGE = '$employeeDTO->nomineePersentage',
        MODIFY_BY = '$user_name',
        MODIFY_DATE = NOW()
        WHERE EMPLOYEE_NOMINEE_INFO_ID = '$employeeDTO->employeeNomineeInfoId'";

        $result = $this->query($sql);

        return $result;
    }

    public function saveNominee($employeeDTO, $user_name) {

        $MaxNomineeId = NextId('employee_nominee_info', 'EMPLOYEE_NOMINEE_INFO_ID');

        $sql = "INSERT INTO employee_nominee_info (EMPLOYEE_NOMINEE_INFO_ID,EMPLOYEE_ID,IS_FAMILY_MEMBER,NOMINEE_TYPE_ID,
        NOMINEE_NAME,RELATIONSHIP,DATE_OF_BIRTH,NOMINEE_PERCENTAGE,CREATED_BY,CREATED_DATE)
        
        VALUES('$MaxNomineeId','$employeeDTO->employeeId','$employeeDTO->isFamilyMember','$employeeDTO->nomineeTypeId',
        '$employeeDTO->nomineeName','$employeeDTO->relationship','$employeeDTO->nomineeBirthday',
        '$employeeDTO->nomineePersentage','$user_name',NOW())";


        $result = $this->query($sql);

        return $result;
    }

    public function updateEducation($employeeDTO, $user_name) {



        $sql = "UPDATE employee_education_info SET
        QUALIFICATION_TITLE = '$employeeDTO->qualificationTitle',
        MAJOR = '$employeeDTO->major',
        PASSING_YEAR = '$employeeDTO->EducationPassingYear',
        CGPA_PERCENTAGE = '$employeeDTO->cgpaPercentage',
        INSTITUTE_NAME = '$employeeDTO->instituteName',
        STATUS = '$employeeDTO->educationStatus',
        START_DATE = '$employeeDTO->educationStartDate',
        END_DATE = '$employeeDTO->educationEndDate',
        CAREER_INFO = '$employeeDTO->careerInfo',
        MODIFY_BY = '$user_name',
        MODIFY_DATE = NOW()
        WHERE EMPLOYEE_EDUCATION_INFO_ID = '$employeeDTO->employeeEducationInfoId'";

        $result = $this->query($sql);

        return $result;
    }

    public function saveEducation($employeeDTO, $user_name) {

        $MaxEducationId = NextId('employee_education_info', 'EMPLOYEE_EDUCATION_INFO_ID');

        $sql = "INSERT INTO employee_education_info (EMPLOYEE_EDUCATION_INFO_ID,EMPLOYEE_ID,QUALIFICATION_TITLE,MAJOR,
        PASSING_YEAR,CGPA_PERCENTAGE,INSTITUTE_NAME,STATUS,START_DATE,END_DATE,CAREER_INFO,CREATED_BY,CREATED_DATE)
        
        VALUES('$MaxEducationId','$employeeDTO->employeeId','$employeeDTO->qualificationTitle','$employeeDTO->major',
        '$employeeDTO->EducationPassingYear','$employeeDTO->cgpaPercentage','$employeeDTO->instituteName',
        '$employeeDTO->educationStatus','$employeeDTO->educationStartDate','$employeeDTO->educationEndDate','$employeeDTO->careerInfo','$user_name',NOW())";


        $result = $this->query($sql);

        return $result;
    }

    public function updateLogin($employeeId, $passUserName, $passwordMd5, $levelId, $routeId, $user_name) {


        $sql = "UPDATE master_user SET
        USER_NAME = '$passUserName',
        USER_PASS = '$passwordMd5',
        USER_LEVEL_ID = '$levelId',
        ROUTE_ID = '$routeId',
        MODIFY_BY = '$user_name',
        MODIFY_DATE = NOW()
        WHERE EMPLOYEE_ID = '$employeeId'";


        $result = $this->query($sql);

        return $result;
    }

    public function saveLogin($passUserName, $passwordMd5, $employeeId, $levelId, $routeId, $user_name) {



        $sql = "INSERT INTO master_user (USER_NAME,USER_PASS,EMPLOYEE_ID,
        USER_LEVEL_ID,ROUTE_ID,CREATED_BY,CREATED_DATE )
        
        VALUES('$passUserName','$passwordMd5','$employeeId',
        '$levelId','$routeId','$user_name',NOW())";

        $result = $this->query($sql);

        return $result;
    }

    public function maritalStatusCombo() {

        $sql = "SELECT MARITAL_STATUS_ID,MARITAL_STATUS_NAME  FROM marital_status";
        $result = rs2array($this->query($sql));

        return json_encode($result);
    }

    public function ganderCombo() {

        $sql = "SELECT GANDER_ID,GANDER_NAME  FROM gander";
        $result = rs2array($this->query($sql));

        return json_encode($result);
    }

    public function countryCombo() {

        $sql = "SELECT COUNTRY_ID,COUNTRY_NAME  FROM country";
        $result = rs2array($this->query($sql));

        return json_encode($result);
    }

    public function religionCombo() {

        $sql = "SELECT RELIGION_ID,RELIGION_NAME  FROM religion";
        $result = rs2array($this->query($sql));

        return json_encode($result);
    }

    public function employeeTypeCombo() {

        $sql = "SELECT EMPLOYEE_TYPE_ID,EMPLOYEE_TYPE_NAME  FROM employee_type";
        $result = rs2array($this->query($sql));

        return json_encode($result);
    }

    public function organizationCombo() {

        $sql = "SELECT ORGANIZATION_ID,ORGANIZATION_NAME  FROM organization";
        $result = rs2array($this->query($sql));

        return json_encode($result);
    }

    public function employeeCombo() {

        $sql = "SELECT CARD_NO,CONCAT(CARD_NO,'-',FIRST_NAME,' ',MIDDLE_NAME,' ',LAST_NAME) AS 'employeeName'  FROM employee";
        $result = rs2array($this->query($sql));

        return json_encode($result);
    }

    public function gradeCombo() {

        $sql = "SELECT GRADE_ID,GRADE_NAME  FROM grade";
        $result = rs2array($this->query($sql));

        return json_encode($result);
    }

    public function officeTypeCombo() {

        $sql = "SELECT OFFICE_TYPE_ID,OFFICE_NAME  FROM office_type";
        $result = rs2array($this->query($sql));

        return json_encode($result);
    }

    public function assignmentCategoryCombo() {

        $sql = "SELECT ASSIGNMENT_CATEGORY_ID,ASSIGNMENT_CATEGORY_NAME  FROM assignment_category";
        $result = rs2array($this->query($sql));

        return json_encode($result);
    }

    public function accountTypeCombo() {

        $sql = "SELECT ACCOUNT_TYPE_ID,ACCOUNT_TYPE_NAME  FROM account_type";
        $result = rs2array($this->query($sql));

        return json_encode($result);
    }

    public function branchCombo() {

        $sql = "SELECT BRANCH_DEPT_ID,BRANCH_DEPT_NAME 
        FROM branch_dept
        WHERE OFFICE_TYPE_ID = '2' 
        ORDER BY BRANCH_DEPT_NAME";
        $result = rs2array($this->query($sql));

        return json_encode($result);
    }

    public function thanaCombo() {

        $sql = "SELECT THANA_ID,THANA_NAME  FROM thana";
        $result = rs2array($this->query($sql));

        return json_encode($result);
    }

    public function nomineeTypeCombo() {

        $sql = "SELECT NOMINEE_TYPE_ID,NOMINEE_TYPE_NAME  FROM nominee_type";
        $result = rs2array($this->query($sql));

        return json_encode($result);
    }

    public function userLevelCombo() {

        $sql = "SELECT USER_LEVEL_ID, USER_LEVEL_NAME  FROM user_level ORDER BY USER_LEVEL_NAME";
        $result = rs2array($this->query($sql));

        return json_encode($result);
    }

    public function routeCombo() {

        $sql = "SELECT REQUISITION_ROUTE_ID,ROUTE_NAME  FROM requisition_route";
        $result = rs2array($this->query($sql));

        return json_encode($result);
    }

    public function supplierCombo() {

        $sql = "SELECT SUPPLIER_ID,SUPPLIER_NAME FROM supplier  ORDER BY SUPPLIER_NAME";
        $result = rs2array($this->query($sql));

        return json_encode($result);
    }

    public function GetDataLinemanager($val) {

        $sql = "SELECT CARD_NO,FIRST_NAME,MIDDLE_NAME,LAST_NAME,CONCAT('(',deg.DESIGNATION_NAME,')') AS 'DESIGNATION_NAME' 
        FROM employee em
        LEFT JOIN designation deg ON deg.DESIGNATION_ID = em.DESIGNATION_ID
        WHERE em.CARD_NO= '$val' ";
        $result = $this->find($sql);

        return $result;
    }

    public function GetDataRoute($val) {

        $sql = "SELECT COUNT(*)
        FROM user_level ul 
        INNER JOIN requisition_route  rr ON rr.REQUISITION_ROUTE_ID = ul.REQUISITION_ROUTE_ID
        WHERE ul.USER_LEVEL_ID = '$val'";

        $result = $this->findValue($sql);

        return $result;
    }

    public function supplierName($val) {

        $sql = "SELECT SUPPLIER_NAME
                FROM supplier
                WHERE SUPPLIER_ID = '$val'";

        $result = $this->findValue($sql);

        return $result;
    }

}

?>
