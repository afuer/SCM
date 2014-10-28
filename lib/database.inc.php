<?php

function query($sql) {

    global $conn;
    $db_type = get('DB_TYPE');

    if ($db_type == 'oci') {
        $q = oci_parse($conn, $sql) or die($sql);
        oci_execute($q);
    } elseif ($db_type == 'mysql') {
        $q = mysql_query($sql);
        /*
          $err = mysql_error();
          if (strlen($err) > 0) {
          if (mysql_errno() == 1451) {
          echo "<script>alert(\"Can't Remove\")</script>";
          }
          }

         */
    } else {
        return;
    }

    return $q;
}

function connect($DB_TYPE, $HOST, $DBUSER, $PASSWORD, $DBNAME) {
    try {
        if ($DB_TYPE == 'oci') {
            $conn = oci_connect($DBUSER, $PASSWORD, $DBNAME);
            set('conn', $conn);
            set('DB_TYPE', $DB_TYPE);
            //echo "Oracle Connected........................";
            //echo $db_type = get('DB_TYPE');
        } elseif ($DB_TYPE == 'mysql') {
            $conn = mysql_connect($HOST, $DBUSER, $PASSWORD);
            mysql_select_db($DBNAME) or die('Could not select database');
            //echo "Mysql Connected........................";

            set('DB_TYPE', $DB_TYPE);
            set('conn', $conn);
        } else {
            
        }

        //set('DB_TYPE', $DB_TYPE);
    } catch (PDOException $e) {
        echo $e->getMessage();
    }

    return $conn;
}

function select_db($dbname) {
    return mysql_select_db($dbname);
}

function fetch_row($query) {
    return mysql_fetch_row($query);
}

function fetch_assoc($query) {
    return mysql_fetch_assoc($query);
}

function fetch_array($query) {

    $db_type = get('DB_TYPE');

    if ($db_type == 'oci') {
        $num_rows = oci_fetch_array($query);
    } else {
        $num_rows = mysql_fetch_array($query);
    }

    return $num_rows;
}

function fetch_object($result) {

    $db_type = get('DB_TYPE');

    if ($db_type == 'oci') {
        $data_set = oci_fetch_object($result);
    } else {
        $data_set = mysql_fetch_object($result);
    }

    return $data_set;
}

function num_rows($rs) {
    $db_type = get('DB_TYPE');

    if ($db_type == 'oci') {
        $num_rows = oci_num_rows($rs);
    } else {
        $num_rows = mysql_num_rows($rs);
    }

    return $num_rows;
}

function affected_rows() {
    return mysql_affected_rows();
}

function find($sql, $dummy = false) {
    $result = query($sql);
    $row = fetch_object($result);

    //print_r(fetch_object($result));

    if (!$row) {
        if ($dummy)
            return new Dummy();
        else
            return null;
    }


    return $row;
}

function NextId($Table, $PrimaryKey) {
    $max = findValue("SELECT IFNULL(MAX($PrimaryKey),0)+1 FROM $Table");
    return $max;
}

function select_value($sql) {
    $q = query($sql);
    if (num_rows($q) == 0)
        return null;
    $row = fetch_array($q);
    return $row[0];
}

function sql($sql) {
    return query($sql);
}

function insert_id() {
    return mysql_insert_id();
}

function findValue($sql, $default = null) {
    $db = new DbManager();
    $db->OpenDb();
    $rs = query($sql);
    //$db->CloseDb();
    $row = fetch_array($rs);
    if ($row == null)
        return $default;
    if ($row[0] == null)
        return $default;

    return $row[0];
}

function rs2array($rs) {
    $result = array();
    while ($row = fetch_row($rs)) {
        $result[] = $row;
    }
    return $result;
}

?>