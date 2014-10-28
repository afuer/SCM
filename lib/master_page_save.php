<?php

$sql = "SELECT * FROM $object_name";


$mode = getParam('mode');
$search_id = getParam('search_id');



$property_value = '';
$property_field = '';
$property_name = '';


if ($mode == 'new') {

    $result_sql = query($sql);
    while ($meta = mysql_fetch_field($result_sql)) {
        //echo "SELECT COLUMN_COMMENT, COLUMN_NAME FROM information_schema.COLUMNS WHERE TABLE_NAME ='$object_name' AND COLUMN_NAME='$meta->name'";
        //die();
        $comments = find("SELECT COLUMN_COMMENT, COLUMN_NAME FROM information_schema.COLUMNS WHERE TABLE_NAME ='$object_name' AND COLUMN_NAME='$meta->name' AND TABLE_SCHEMA='$DB_NAME'");
        $explode = explode(',', $comments->COLUMN_COMMENT);

        //print_r($explode);
        //echo $explode[0].' '.$explode[2].'<br/>';

        $explode_zero = isset($explode[0]) ? $explode[0] : '';
        $explode_one = isset($explode[1]) ? $explode[1] : '';

        $explode_two = isset($explode[2]) ? $explode[2] : '';
        $explode_three = isset($explode[3]) ? $explode[3] : '';
        $explode_four = isset($explode[4]) ? $explode[4] : '';
        $explode_five = isset($explode[5]) ? $explode[5] : '';
        $explode_six = isset($explode[6]) ? $explode[6] : '';

        if ($explode_one == 'y') {
            //echo $meta->name . "<br/>";
            //echo "r ";
            //get property value
            $get_value = getParam($meta->name);
            //$get_value = $explode_six == 4 ? implode(',', $get_value) : $get_value;
            //echo $get_value = $explode_six == 4 ? $get_value : '';
            //$rel_escap = mysql_real_escape_string($get_value);
            $property_value.="'$get_value', ";

            //get property
            $property_field.=$meta->name . ', ';
        }
    }

    $property_field.='created_by,' . ' created_date';
    $property_value.="'$userName'," . ' CURDATE()';
    $maxID = NextId("$object_name", "$object_id");

    $sql_insert = "INSERT INTO $object_name($object_id,$property_field) values ($maxID, $property_value)";
    $result = sql($sql_insert);
} elseif ($mode == 'delete') {
    $sql_update = "DELETE FROM  $object_name WHERE $object_id ='$search_id'";
    $result = sql($sql_update);
} else {

    $result_sql = query($sql);
    while ($meta = mysql_fetch_field($result_sql)) {
        //echo "SELECT COLUMN_COMMENT, COLUMN_NAME FROM information_schema.COLUMNS WHERE TABLE_NAME ='$object_name' AND COLUMN_NAME='$meta->name'";
        //die();
        $comments = find("SELECT COLUMN_COMMENT, COLUMN_NAME FROM information_schema.COLUMNS WHERE TABLE_NAME ='$object_name' AND COLUMN_NAME='$meta->name' AND TABLE_SCHEMA='$DB_NAME'");
        $explode = explode(',', $comments->COLUMN_COMMENT);

        //print_r($explode);
        //echo $explode[0].' '.$explode[2].'<br/>';

        $explode_zero = isset($explode[0]) ? $explode[0] : '';
        $explode_one = isset($explode[1]) ? $explode[1] : '';

        $explode_two = isset($explode[2]) ? $explode[2] : '';
        $explode_three = isset($explode[3]) ? $explode[3] : '';
        $explode_four = isset($explode[4]) ? $explode[4] : '';
        $explode_five = isset($explode[5]) ? $explode[5] : '';
        $explode_six = isset($explode[6]) ? $explode[6] : '';

        if ($explode_one == 'y') {
            $property_name = $meta->name;
            $get_value = getParam($meta->name);

            $property_value = "'$get_value'";

            $property_field.=$property_name . '=' . $property_value . ', ';
        }
    }

    $property_field.="modify_by='$user_name', modify_by=CURDATE()";


    $sql_update = "Update $object_name set $property_field WHERE $object_id ='$search_id'";
    $result = sql($sql_update);
}
?>
