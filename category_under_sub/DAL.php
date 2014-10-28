<?php

class DAL extends DbManager {

    public function getDataGrid($offset, $rows, $search) {
        $result = array();
        $result["total"] = $this->count($search);

        $res = $search == "" ? " " : " WHERE CATEGORY_SUB_UNDER_NAME LIKE '%$search%'";
        $limt = $search == '' ? " LIMIT $offset, $rows" : '';

        $sql = "SELECT cs.CATEGORY_SUB_UNDER_ID, cs.CATEGORY_SUB_UNDER_NAME, cs.CATEGORY_SUB_ID, c.CATEGORY_SUB_NAME, cs.DESCRIPTION
                FROM category_sub_under AS cs
                LEFT JOIN category_sub AS c ON c.CATEGORY_SUB_ID = cs.CATEGORY_SUB_ID
                $res ORDER BY CATEGORY_SUB_UNDER_NAME $limt";
        $sql_result = $this->query($sql);
        

        $items = array();
        while ($row = fetch_object($sql_result)) {
            array_push($items, $row);
        }
        $result["rows"] = $items;

        return json_encode($result);
    }

    public function count($search) {
        $res = $search == "" ? '' : " WHERE CATEGORY_SUB_UNDER_NAME LIKE '%$search%'";
        
        $rs = $this->query("SELECT count(*) FROM category_sub_under $res");
        $row = fetch_row($rs);

       

        return $row[0];
    }

    public function getAllCombo() {
        
        $sql = "SELECT CATEGORY_SUB_ID, CATEGORY_SUB_NAME FROM category_sub ORDER BY CATEGORY_SUB_NAME";
        $result = $this->query($sql);
       

        return $result;
    }

    public function getFilterCombo($categoryId) {
        $res = $categoryId == '' ? '' : " WHERE CATEGORY_ID='$categoryId' ";
        
        $sql = "SELECT CATEGORY_SUB_ID, CATEGORY_SUB_NAME FROM category_sub $res ORDER BY CATEGORY_SUB_NAME";
        $result = $this->query($sql);

        return $result;
    }

}

?>
