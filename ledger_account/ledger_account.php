<?php

class ledger_account {

    public function getDataGrid($offset, $rows, $search) {
        $result = array();
        $result["total"] = $this->count($search);

        $db = new DbManager();

        $db->OpenDb();
        $res = $search == "" ? " " : " WHERE lc.ACCOUNT_NAME LIKE '%$search%'";
        $limt = $search == '' ? " LIMIT $offset, $rows" : '';

        $sql = "SELECT lc.LEDGER_ACCOUNT_ID, lc.LEDGER_CODE, lc.LEDGER_NAME, 
            ul.LEDGER_NAME AS 'UNDER_NAME',
        CASE WHEN lc.LEDGER_STATUS ='1' THEN 'Yes' ELSE 'No' END AS 'ACTIVE'
        FROM ledger_account lc
        LEFT JOIN ledger_account ul ON ul.LEDGER_ACCOUNT_ID=lc.UNDER_LEDGER_ID
        $res ORDER BY lc.LEDGER_NAME $limt";
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
        $res = $search == "" ? '' : " WHERE ACCOUNT_NAME LIKE '%$search%'";
        $db = new DbManager();
        $db->OpenDb();
        $rs = query("SELECT count(*) FROM ledger_account $res");
        $row = fetch_row($rs);

        $db->CloseDb();

        return $row[0];
    }

    public function getAllCombo() {
        $db = new DbManager();
        $db->OpenDb();
        $sql = "SELECT lc.LEDGER_CODE AS 'UNDER_LEDGER_ID',CONCAT(lc.LEDGER_CODE,'-',lc.LEDGER_NAME) AS 'ACCOUNT_NAME'
        FROM ledger_account lc";
        $result = query($sql);
        $db->CloseDb();

        return $result;
    }

}

?>
