<?php

class DAL extends DbManager {

    public function getDataGrid($offset, $rows) {
        $result = array();
        $result["total"] = $this->count($offset, $rows);


        $res = $search == "" ? " " : " AND FIRST_NAME LIKE '%$search%'";
        $limt = $search == '' ? " LIMIT $offset, $rows" : '';

         $sql = "SELECT em.EMPLOYEE_ID,em.CARD_NO,CONCAT(em.FIRST_NAME,' ',em.MIDDLE_NAME,' ',em.LAST_NAME) AS 'EMPLOYEE_NAME',
        em.DESIGNATION_ID,des.DESIGNATION_NAME, em.BRANCH_DEPT_ID, ISACTIVE, em.OFFICE_TYPE_ID,
        em.DIVISION_ID,divi.DIVISION_NAME,em.FIRST_NAME,em.MIDDLE_NAME,em.LAST_NAME,
        CONCAT(br.BRANCH_DEPT_NAME, '->', ot.OFFICE_NAME) AS BRANCH_DEPT_NAME,em.EMPLOYEE_TYPE_ID,et.EMPLOYEE_TYPE_NAME

        FROM employee em 
        LEFT JOIN designation des ON des.DESIGNATION_ID = em.DESIGNATION_ID
        LEFT JOIN branch_dept br ON br.BRANCH_DEPT_ID = em.BRANCH_DEPT_ID
        LEFT JOIN office_type ot ON ot.OFFICE_TYPE_ID = br.OFFICE_TYPE_ID
        LEFT JOIN division divi ON divi.DIVISION_ID = em.DIVISION_ID
        LEFT JOIN employee_type et ON et.EMPLOYEE_TYPE_ID = em.EMPLOYEE_TYPE_ID
        WHERE ISACTIVE='Yes' $res ORDER BY em.EMPLOYEE_ID DESC $limt";
        
        $sql_result = $this->query($sql);
       
        $items = array();
        while ($row = $this->fetch_object($sql_result)) {
            array_push($items, $row);
        }
        $result["rows"] = $items;

        return json_encode($result);
    }
    
    public function count($offset, $rows) {
        

       
        $sql = "SELECT count(*) 
            FROM employee em 
        LEFT JOIN designation des ON des.DESIGNATION_ID = em.DESIGNATION_ID
        LEFT JOIN branch_dept br ON br.BRANCH_DEPT_ID = em.BRANCH_DEPT_ID
        LEFT JOIN office_type ot ON ot.OFFICE_TYPE_ID = br.OFFICE_TYPE_ID
        LEFT JOIN division divi ON divi.DIVISION_ID = em.DIVISION_ID
        LEFT JOIN employee_type et ON et.EMPLOYEE_TYPE_ID = em.EMPLOYEE_TYPE_ID
        WHERE IsActive='Yes' LIMIT $offset, $rows";
      
        $rs = $this->query($sql);
     
        $row = $this->fetch_row($rs);

        return $row[0];
    }

    public function SearchCount($dto) {
        $res = '';

        if ($dto->dateFrom != '' AND $dto->dateTo != '') {
            $res.=" AND CREATED_DATE BETWEEN '$dto->dateFrom' AND '$dto->dateTo'";
        }

        $res .= $dto->cardNo == "" ? "" : " AND CARD_NO='$dto->cardNo'";
        $res .= $dto->firstName == "" ? "" : " AND FIRST_NAME LIKE '%$dto->firstName%'";
        $res .= $dto->IsActive == "" ? "" : " AND IsActive='$dto->IsActive'";
        $res .= $dto->designationId == "" ? "" : " AND DESIGNATION_ID='$dto->designationId'";

        $sql = "SELECT count(*) 
            FROM employee em 
        LEFT JOIN designation des ON des.DESIGNATION_ID = em.DESIGNATION_ID
        LEFT JOIN branch_dept br ON br.BRANCH_DEPT_ID = em.BRANCH_DEPT_ID
        LEFT JOIN office_type ot ON ot.OFFICE_TYPE_ID = br.OFFICE_TYPE_ID
        LEFT JOIN division divi ON divi.DIVISION_ID = em.DIVISION_ID
        LEFT JOIN employee_type et ON et.EMPLOYEE_TYPE_ID = em.EMPLOYEE_TYPE_ID
        WHERE 1 $res";
      
        $rs = $this->query($sql);
     
        $row = $this->fetch_row($rs);

        return $row[0];
    }

    public function search($offset, $rows, $dto) {
        $result = array();
        $result["total"] = $this->SearchCount($dto);
    
        $res = '';
        if ($dto->dateFrom != '' AND $dto->dateTo != '') {
            $res.=" AND em.CREATED_DATE BETWEEN '$dto->dateFrom' AND '$dto->dateTo'";
        }
        $res .= $dto->cardNo == "" ? "" : " AND em.CARD_NO='$dto->cardNo'";
        $res .= $dto->firstName == "" ? "" : " AND em.FIRST_NAME LIKE '%$dto->firstName%'";
        $res .= $dto->IsActive == "" ? " AND em.IsActive=''" : " AND em.IsActive='$dto->IsActive'";
        $res .= $dto->designationId == "" ? "" : " AND em.DESIGNATION_ID='$dto->designationId'";

        $sql = "SELECT em.EMPLOYEE_ID,em.CARD_NO,CONCAT(em.FIRST_NAME,' ',em.MIDDLE_NAME,' ',em.LAST_NAME) AS 'EMPLOYEE_NAME',
        em.DESIGNATION_ID,des.DESIGNATION_NAME, em.BRANCH_DEPT_ID, 
        (CASE WHEN ISACTIVE='' THEN 'No' ELSE ISACTIVE END) AS 'ISACTIVE',
        em.DIVISION_ID,divi.DIVISION_NAME,em.FIRST_NAME,em.MIDDLE_NAME,em.LAST_NAME,
        CONCAT(br.BRANCH_DEPT_NAME, '->', ot.OFFICE_NAME) AS BRANCH_DEPT_NAME,em.EMPLOYEE_TYPE_ID,
        et.EMPLOYEE_TYPE_NAME, br.OFFICE_TYPE_ID

        FROM employee em 
        LEFT JOIN designation des ON des.DESIGNATION_ID = em.DESIGNATION_ID
        LEFT JOIN branch_dept br ON br.BRANCH_DEPT_ID = em.BRANCH_DEPT_ID
        LEFT JOIN office_type ot ON ot.OFFICE_TYPE_ID= br.OFFICE_TYPE_ID
        LEFT JOIN division divi ON divi.DIVISION_ID = em.DIVISION_ID
        LEFT JOIN employee_type et ON et.EMPLOYEE_TYPE_ID = em.EMPLOYEE_TYPE_ID
        WHERE 1 $res ORDER BY em.EMPLOYEE_ID DESC LIMIT $offset, $rows";
        $sql_result = query($sql);

        

        $items = array();
        while ($row = fetch_object($sql_result)) {
            array_push($items, $row);
        }
        $result["rows"] = $items;

        return json_encode($result);
    }

}

?>
