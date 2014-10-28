<?php

class SolData {

    public function SaveSol($MaxSolId, $SolName, $SolCode, $DivisionID, $DepartmentID, $BranchID, $OfficeID) {
        $SqlSol = "INSERT INTO sol (SOL_ID,SOL_CODE,SOL_NAME,DIVISION_ID,DEPARTMENT_ID,BRANCH_ID,OFFICE_TYPE_ID,CREATED_BY,CREATED_DATE) 
                       Values('$MaxSolId','$SolCode','$SolName','$DivisionID','$DepartmentID','$BranchID','$OfficeID','$user_name',NOW())";
        return query($SqlSol);
    }

    function GetDataSol($SearchID) {
       $SqlSol = "SELECT  *
        FROM sol 
        WHERE SOL_ID = '$SearchID'";
        return find($SqlSol);
    }
    function GetDataSolView($SearchID){
        $sol_sql = "SELECT s.SOL_CODE,s.SOL_NAME,d.DEPARTMENT_NAME,di.DIVISION_NAME,
        b.BRANCH_NAME,ot.OFFICE_NAME,ed.FULL_NAME,s.CREATED_DATE
        FROM sol AS s
        LEFT JOIN department AS d ON d.DEPARTMENT_ID = s.DEPARTMENT_ID 
        LEFT JOIN division AS di ON di.DIVISION_ID = s.DIVISION_ID
        LEFT JOIN branch AS b ON b.BRANCH_ID = s.BRANCH_ID
        LEFT JOIN office_type AS ot ON ot.OFFICE_TYPE_ID = s.OFFICE_TYPE_ID
        LEFT JOIN employee_details AS ed ON ed.CARDNO = s.CREATED_BY
        WHERE s.SOL_ID = '$SearchID'";
        return find($sol_sql);
        
    }

    function EditSol($SearchID, $SolName, $SolCode, $DivisionID, $DepartmentID, $BranchID, $OfficeID) {

        $SqlUpdate = "UPDATE sol SET 
                SOL_CODE = '$SolCode',
                SOL_NAME = '$SolName',
                DIVISION_ID = '$DivisionID',
                DEPARTMENT_ID = '$DepartmentID',
                BRANCH_ID = '$BranchID',
                OFFICE_TYPE_ID = '$OfficeID',
                CREATED_BY= '$user_name',  
                CREATED_DATE =NOW()
                WHERE SOL_ID = '$SearchID'";
       

        return query($SqlUpdate);
       
    }

    function DeletRequest($SearchID) {
        $SqlDel = "DELETE FROM sol
     WHERE SOL_ID ='$SearchID'";
        return query($SqlDel);
    }

}

$DivisionList = rs2array(query("SELECT DIVISION_ID, DIVISION_NAME FROM division"));
$DepartmentList = rs2array(query("SELECT DEPARTMENT_ID, DEPARTMENT_NAME FROM department"));
$BranchList = rs2array(query("SELECT BRANCH_ID, BRANCH_NAME FROM branch"));
$OfficeList = rs2array(query("SELECT OFFICE_TYPE_ID, OFFICE_NAME FROM office_type"));



$page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);
$limit = (int) (!isset($_GET["limit"]) ? 20 : $_GET["limit"]);
$startpoint = ($page * $limit) - $limit;



$Date_start = getParam('Date_start');
$Date_end = getParam('Date_end');
$RequestType = getParam('RequestType');
$Request_status = getParam('Request_status');
$RequestFrom = getParam('RequestFrom');







$request_sql = "SELECT s.SOL_ID,s.SOL_CODE AS SOL_CODE, s.SOL_NAME,
d.DEPARTMENT_NAME,di.DIVISION_NAME,b.BRANCH_NAME,ot.OFFICE_NAME,ed.FULL_NAME,s.CREATED_DATE
FROM sol AS s
LEFT JOIN department AS d ON d.DEPARTMENT_ID = s.DEPARTMENT_ID 
LEFT JOIN division AS di ON di.DIVISION_ID = s.DIVISION_ID
LEFT JOIN branch AS b ON b.BRANCH_ID = s.BRANCH_ID
LEFT JOIN office_type AS ot ON ot.OFFICE_TYPE_ID = s.OFFICE_TYPE_ID
LEFT JOIN employee_details AS ed ON ed.CARDNO = s.CREATED_BY
ORDER BY s.SOL_ID DESC LIMIT $startpoint, $limit";
$request_result = query($request_sql);


$q = explode('FROM', $request_sql);
$sql_pa = "SELECT COUNT(*) as CoutTotal FROM $q[1]";
$q = explode('LIMIT', $sql_pa);
$sql_pa = "$q[0]";
$row = find($sql_pa);
$total = $row->CoutTotal;
?>