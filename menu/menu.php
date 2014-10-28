<?php

class menu {

    public function getDataUserLevel() {
        $db = new DbManager();
        $db->OpenDb();
        $sql = "SELECT USER_LEVEL_ID,USER_LEVEL_NAME
        FROM user_level  ORDER BY USER_LEVEL_NAME";
        $result = query($sql);
        $db->CloseDb();
        return $result;
    }

}

?>
