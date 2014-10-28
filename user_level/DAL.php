<?php

class DAL extends DbManager {

    public function getDataGrid($offset, $rows, $search) {
        $result = array();
        $result["total"] = $this->count($search);

        $res = $search == "" ? " " : " WHERE USER_LEVEL_NAME LIKE '%$search%'";
        $limt = $search == '' ? " LIMIT $offset, $rows" : '';

         $sql = "SELECT ul.USER_LEVEL_ID, USER_LEVEL_NAME, USER_LEVEL_GROUP_ID,ug.USER_GROUP_NAME,ul.SORT_,
        ul.REQUISITION_ROUTE_ID,rr.ROUTE_NAME,PROCESSING_TYPE_ID,pt.REQUISITION_PROCESSING_TYPE_NAME
        FROM user_level ul
        LEFT JOIN user_group ug ON ug.USER_GROUP_ID = ul.USER_LEVEL_GROUP_ID
        LEFT JOIN requisition_processing_type pt ON pt.REQUISITION_PROCESSING_TYPE_ID = ul.PROCESSING_TYPE_ID
        LEFT JOIN requisition_route rr ON rr.REQUISITION_ROUTE_ID = ul.REQUISITION_ROUTE_ID
        $res ORDER BY ul.USER_LEVEL_NAME $limt";
        
        $sql_result = $this->query($sql);
        

        $items = array();
        while ($row = fetch_object($sql_result)) {
            array_push($items, $row);
        }
        $result["rows"] = $items;

        return json_encode($result);
    }

    public function count($search) {
        $res = $search == "" ? '' : " WHERE USER_LEVEL_NAME LIKE '%$search%'";
       
        $rs = $this->query("SELECT count(*) FROM user_level $res");
        $row = fetch_row($rs);


        return $row[0];
    }

    public function getBranchAll() {
       
        $sql = "SELECT USER_LEVEL_ID, USER_LEVEL_NAME FROM user_level ORDER BY USER_LEVEL_NAME";
        $result = $this->query($sql);
       

        return $result;
    }

}

?>
