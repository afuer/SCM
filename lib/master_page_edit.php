<?php
$sql = "SELECT * FROM $object_name";
$search_id = getParam('search_id');
$object_name_id = $object_name . '_id';

if (isSave()) {

    $property_value = '';
    $property_field = '';
    $property_name = '';

    if ($mode != 'search') {

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
                //echo "<br/>";
                //echo "r ";
                //get property value
                $get_value = getParam($meta->name);
                //$rel_escap = mysql_real_escape_string($get_value);
                $property_value.="'$get_value', ";

                //get property
                $property_field.=$meta->name . ', ';
            }
        }

        $property_field.='created_by,' . ' created_date';
        $property_value.="'$user_name'," . ' CURDATE()';

        $sql_insert = "INSERT INTO $object_name($property_field) values ($property_value)";
        sql($sql_insert);
        //die();
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


        $sql_update = "Update $object_name set $property_field where $object_name_id ='$search_id'";
        sql($sql_update);
        //die();
        mysql_free_result($result_sql);

        echo "<script>location.replace('$object_name.php?mode=');</script>";
    }
}

$var = find("SELECT * FROM $object_name WHERE $object_name_id ='$search_id'");




if ($mode == 'new' || $mode == 'search') {
    ?>

    <form name="" class="" Action="" method="POST" title="Create New user">
        <fieldset>
            <legend>Create</legend>
            <table>
                <?php
                $result_show = query($sql);
                while ($show_meta = mysql_fetch_field($result_show)) {

                    $comments = find("SELECT COLUMN_COMMENT, COLUMN_NAME FROM information_schema.COLUMNS WHERE TABLE_NAME ='$object_name' AND COLUMN_NAME='$show_meta->name'");
                    $explode = explode(',', $comments->COLUMN_COMMENT);

                    //echo "<pre>";
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
                        $field_value = '';
                        $field_name = "$show_meta->name";
                        $field_value = $var->$field_name;
                        echo "<tr>";

                        if ($explode_six == '1') {
                            //1 for varchar


                            echo "<td width='100'><strong>$explode_zero :</strong></td>";
                            echo "<td><input type='text' name='$field_name' id='$field_name' class='$field_name' value='$field_value'/></td>";
                        } elseif ($explode_six == '7') {
                            //7 for text
                            echo "<td width='100'><strong>$explode_zero :</strong></td>";
                            echo "<td><textarea name='$field_name' id='$field_name' class='$field_name'>$field_value</textarea></td>";
                        } else {
                            $combo_sql = findValue("SELECT combo_sql FROM master_object WHERE object_name='$object_name' AND field_name='$show_meta->name'");
                            $data = rs2array(query($combo_sql));
                            echo "<td width='100'><strong>$explode_zero :</strong></td>";
                            echo "<td>" . combobox("$show_meta->name", $data, '', true) . "</td>";
                        }
                        echo "</tr>";
                    }
                }
                mysql_free_result($result_show);
                ?>


            </table>
            <?php saveButton(); ?>
        </fieldset>
    </form>

<?php } ?>