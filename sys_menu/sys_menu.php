<?php

class sys_menu {

    public function getAll($offset, $rows, $search) {
        $result = array();
        $result["total"] = $this->count($search);

        $db = new DbManager();
        $db->OpenDb();

        $sql = "SELECT sm.SYS_MENU_ID, sm.MENU_NAME, s.MENU_NAME AS 'UNDER', sm.LINKS,
        (CASE WHEN s._SHOW=1 THEN 'Yes' ELSE 'No' END) AS 'Status'
        FROM sys_menu sm
        LEFT JOIN sys_menu s ON s._SUBID=sm.SYS_MENU_ID
        ORDER BY sm.SYS_MENU_ID LIMIT $offset, $rows";
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
        $db = new DbManager();
        $db->OpenDb();
        $rs = query("SELECT count(*) FROM sys_menu");
        $row = fetch_row($rs);

        $db->CloseDb();

        return $row[0];
    }

}

?>
