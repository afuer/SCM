<?php

class DAL {

    public function getDataGrid($offset, $rows, $search) {
        $result = array();
        $result["total"] = $this->count($search);

        $db = new DbManager();

        $db->OpenDb();
        $res = $search == "" ? " " : " WHERE SUPPLIER_NAME LIKE '%$search%'";
        $limt = $search == '' ? " LIMIT $offset, $rows" : '';

        $sql = "SELECT SUPPLIER_ID,SUPPLIER_NAME,SUPPLIER_ADDRESS,PHONE,MAIL,VAT_REG_NO, CASE WHEN VAT_PAID ='1' THEN 'Yes' ELSE 'NO' END AS 'VAT_PAID' ,TIN_REG_NO,WEB_ADDRESS
        FROM supplier
        $res ORDER BY SUPPLIER_NAME $limt";
        $sql_result = query($sql);
        $db->CloseDb();

        $items = array();
        while ($row = fetch_object($sql_result)) {
            array_push($items, $row);
        }
        $result["rows"] = $items;

        return json_encode($result);
    }

    public function count($search) {
        $res = $search == "" ? '' : " WHERE SUPPLIER_NAME LIKE '%$search%'";
        $db = new DbManager();
        $db->OpenDb();
        $rs = query("SELECT count(*) FROM supplier $res");
        $row = fetch_row($rs);

        $db->CloseDb();

        return $row[0];
    }

    

}

?>
