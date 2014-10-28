<?php

class memo_archive {

    public function getAll($offset, $rows, $search) {
        $result = array();
        $result["total"] = $this->count($search);

        $db = new DbManager();

        $db->OpenDb();
        $res = $search == "" ? " " : " WHERE DIVISION_NAME LIKE '%$search%'";
        $limt = $search == '' ? " LIMIT $offset, $rows" : '';

        $sql = "SELECT MEMO_TYPE, MEMO_DATE, 	MEMO_REF, 	MEMO_CATEGORY, 	MEMO_DETAILS, 	APPROVED_AMOUNT, 	MEMO_ARCHIVE_ID, 	REMARKS,	PAYMENT_METHOD, 	MEMO_SUBJECT, 	MEMO_INFO_REF, 	CREATED_BY, 	CREATED_DATE, 	MODIFY_BY,	MODIFY_DATE
                FROM memo_archive LIMIT $offset, $rows";
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
        $res = $search == "" ? '' : " WHERE DIVISION_NAME LIKE '%$search%'";
        $db = new DbManager();
        $db->OpenDb();
        $rs = query("SELECT count(*) FROM memo_archive ");
        $row = fetch_row($rs);

        $db->CloseDb();

        return $row[0];
    }

    public function getDivisionAll() {
        $db = new DbManager();
        $db->OpenDb();
        $sql = "SELECT DIVISION_ID, DIVISION_NAME FROM division ORDER BY DIVISION_SORT";
        $result = query($sql);
        $db->CloseDb();

        return $result;
    }

}

?>
