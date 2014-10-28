<?php
$sql_main = "SELECT * FROM $main_object_name";
$sql_sub = "SELECT * FROM $sub_object_name";

if (isSave()) {

    $property_value = '';
    $property_field = '';

    $result_main_sql = query($sql_main);
    while ($meta = mysql_fetch_field($result_main_sql)) {
        //echo "SELECT COLUMN_COMMENT, COLUMN_NAME FROM information_schema.COLUMNS WHERE TABLE_NAME ='$object_name' AND COLUMN_NAME='$meta->name'";
        //die();
        $comments = find("SELECT COLUMN_COMMENT, COLUMN_NAME FROM information_schema.COLUMNS WHERE TABLE_NAME ='$main_object_name' AND COLUMN_NAME='$meta->name'");
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

    mysql_free_result($result_main_sql);

    //$result_value = preg_replace(strrev('/,/'), strrev(''), strrev($property_value), 1);
    //$property_value = strrev($result_value);
    //$result_field = preg_replace(strrev('/,/'), strrev(''), strrev($property_field), 1);
    //$property_field = strrev($result_field);

    $sql_insert = "INSERT INTO $object_name($property_field) values ($property_value)";
    sql($sql_insert);
    echo "<script>location.replace('$object_name.php?mode=');</script>";
}



if ($mode == 'new' || $mode == 'search') {
    ?>

    <form name="" class="" Action="" method="POST" title="Create new user">
        <fieldset>
            <legend>Create</legend>
            <table>
                <?php
                $result_main_show = query($sql_main);
                while ($show_meta = mysql_fetch_field($result_main_show)) {

                    $comments = find("SELECT COLUMN_COMMENT, COLUMN_NAME FROM information_schema.COLUMNS WHERE TABLE_NAME ='$main_object_name' AND COLUMN_NAME='$show_meta->name'");
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
                        echo "<tr>";

                        if ($explode_six == '1') {
                            echo "<td width='100'><strong>$explode_zero :</strong></td>";
                            echo "<td><input type='text' name='$show_meta->name' id='$show_meta->name' class='$show_meta->name' value=''/></td>";
                        } else {
                            $combo_sql = findValue("SELECT combo_sql FROM master_object WHERE object_name='$object_name' AND field_name='$show_meta->name'");
                            $data = rs2array(query($combo_sql));
                            echo "<td width='100'><strong>$explode_zero :</strong></td>";
                            echo "<td>" . combobox("$show_meta->name", $data, '', true) . "</td>";
                        }
                        echo "</tr>";
                    }
                }
                mysql_free_result($result_main_show);
                ?>
            </table>

            <table class="ui-state-default">
                <thead>
                <th width='20'>SI</th>
                <?php
                $sl = 0;
                $result_sub_show = query($sql_sub);
                while ($show_meta = mysql_fetch_field($result_sub_show)) {

                    $comments = find("SELECT COLUMN_COMMENT, COLUMN_NAME FROM information_schema.COLUMNS WHERE TABLE_NAME ='$sub_object_name' AND COLUMN_NAME='$show_meta->name'");
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


                        if ($explode_six == '1') {
                            echo "<th>$explode_zero</th>";
                        } else {
                            //check later
                            $combo_sql = findValue("SELECT combo_sql FROM master_object WHERE object_name='$object_name' AND field_name='$show_meta->name'");
                            $data = rs2array(query($combo_sql));
                            echo "<td width='100'><strong>$explode_zero :</strong></td>";
                            echo "<td>" . combobox("$show_meta->name", $data, '', true) . "</td>";
                        }
                    }
                }
                mysql_free_result($result_sub_show);
                ?>
                </thead>
                <tr></tr>
            </table>
            <button type="button" name="add" class="button forward float-right" id="add">Add</button>
            <br/>
            <?php saveButton() ?>
        </fieldset>
    </form>

<?php } ?>
