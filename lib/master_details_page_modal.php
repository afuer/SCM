<?php
$object_info = find("SELECT * FROM master_object where object_name='$object_name'");

$table_column = "$object_info->view_column";
$page_title = "$object_info->page_title";

$search_id = getParam('search_id');
$mode = getParam('mode');
$main_id = getParam('main_id');


$selectSQL = "select master_mod_id, object_id, display_name, field_name, field_type, input_type_id, search_file, combo_sql, field_length, decimal_place, 
field_required, required_symbol, allow_null, ajax_call, ajax_file_name, ajax_sql, affected_field_name, field_size, 
field_type_value, _show, _sort
from master_mod where object_id='$object_info->object_id' ORDER BY _sort";


$main_object_id = $main_object . '_id';
$object_name_id = $object_name . '_id';

//echo "SELECT carr_name FROM $main_object WHERE $main_object_id='$main_id'";
$main_object_name = findValue("SELECT carr_name FROM $main_object WHERE $main_object_id='$main_id'");


if (isSave()) {
    if ($mode == 'new') {

        $object_field_result = mysql_query($selectSQL);
        for ($i = 1; $i <= mysql_num_rows($object_field_result); $i++) {
            $property_name = mysql_fetch_object($object_field_result);
            //get value
            $get_value = getParam($property_name->field_name);

            $rel_escap = mysql_real_escape_string($get_value);

            // $==1 for insert main parent ID
            if ($i == 1) {
                $property_value.="'$main_id'";
            } else {
                $property_value.="'$rel_escap'";
            }
            $property_field.=$property_name->field_name;

            if ($i < mysql_num_rows($object_field_result)) {
                $property_field.=', ';
                $property_value.=', ';
            }
        }

        $sql_insert = "INSERT INTO $object_name($property_field) value ($property_value)";
        sql($sql_insert);
    } else {

        $object_field_result = mysql_query($selectSQL);
        for ($i = 1; $i <= mysql_num_rows($object_field_result); $i++) {
            $property_rows = mysql_fetch_object($object_field_result);
            //get value
            $property_name = $property_rows->field_name;
            $get_value = getParam($property_rows->field_name);

            $rel_escap = mysql_real_escape_string($get_value);
            // $==1 for insert main parent ID
            if ($i == 1) {
                $property_value.="'$main_id'";
            } else {
                $property_value = "'$rel_escap'";
            }

            $property_field.=$property_name . '=' . $property_value;


            if ($i < mysql_num_rows($object_field_result)) {
                $property_field.=', ';
            }
        }

        $sql_update = "Update $object_name set $property_field where $object_name_id ='$search_id'";
        sql($sql_update);
    }

    echo "<script>location.replace('$object_name.php?mode=&main_id=$main_id');</script>";
    //echo "<script type='text/javascript'>window.opener.parent.location.reload()</script>";
    //echo "<script type='text/javascript'>window.close()</script>";
}

if ($mode == 'delete') {
    $delete_sql = "delete from $object_name where $object_name_id ='$search_id'";
    sql($delete_sql);
    echo "<script>location.replace('$object_name.php?mode=&main_id=$main_id');</script>";
}
if ($mode == 'search' && isset($search_id)) {

    $object_field_result = mysql_query($selectSQL);
    for ($i = 1; $i <= mysql_num_rows($object_field_result); $i++) {
        $property_name = mysql_fetch_object($object_field_result);

        $property_field.=$property_name->field_name;

        if ($i < mysql_num_rows($object_field_result)) {
            $property_field.=', ';
        }
    }


    $sql2 = "select $property_field from $object_name where $object_name_id='$search_id'";
    $var = find($sql2);
}

?>


<?php if ($mode == 'new' || $mode == 'search') { ?>

    <form action='' class='form' method='POST' enctype='multipart/form-data'>
        <input type="hidden" name="main_id" value="<?php echo $main_id; ?>" />
        <fieldset>    
            <legend><h2><?php echo $page_title . ' ( ' . $main_object_name . ' ) '; ?></h2></legend>

            <table>
                <?php
                $rs = query($selectSQL);
                while ($row = mysql_fetch_array($rs)) {

                    //if ($row[_show] == '1') {

                    $convert_name = str_replace("_", " ", $row[display_name]);
                    $uc_name = ucwords($convert_name);
                    $ajux_sql = $row[master_mod_id];

                    ($uc_name != '') ? $uc_name = $uc_name . ' :' : '';

                    $display_field = $var->$row[field_name];

                    if ($row[field_required] == 'Y') {
                        $required = "class='required $row[required_symbol]'";
                        $required_combo = 'required';
                    } else {
                        $required = '';
                        $required_combo = '';
                    }

                    //$input_type=  findValue("SELECT input_type_name FROM master_input_type WHERE input_type_id='$row[input_type_id]'");
                    echo "<tr>";
                    if ($row[_show] == '1') {

                        echo "<td width='150'>$uc_name";

                        if ($row[field_required] == 'Y') {
                            echo "<em>*</em>";
                        } echo "</td>";

                        $ajax_file_name = findValue("SELECT (SELECT master_mod_id FROM master_mod WHERE master_mod.master_mod_id=mo.affected_field_name) AS 'ajax_file_name' FROM master_mod AS mo WHERE master_mod_id='$row[master_mod_id]'");
                        echo "<td id='$row[ajax_file_name]-$ajax_file_name'>";
                        if ($row[input_type_id] == '1') {


                            $maxlength = $row[field_type] == 'date' ? 20 : $row[field_length];

                            echo "<input size='$row[field_size]' type='text' autocomplete='on' placeholder='' name='$row[field_name]' maxlength='$maxlength' $required value='$display_field' />";
                        } else if ($row[input_type_id] == '2') {
                            $data = rs2array(query("$row[combo_sql]"));
                            $combo_name = "$row[field_name]";
                            combobox($combo_name, $data, $display_field, true, $required_combo, "$row[ajax_file_name]", $ajux_sql);
                        } else if ($row[input_type_id] == '4') {
                            if ($display_field == '1') {
                                $checkbox = 'checked';
                            }

                            echo "<input type='checkbox' size='$row[field_size]' name='$row[field_name]' value='1' $checkbox $required />";
                        } else if ($row[input_type_id] == '5') {

                            echo "<input size='$row[field_size]' id='search_value' type='text' autocomplete='on' placeholder='' name='$row[field_name]' maxlength='$row[field_length]' $required value='$display_field'/> ";
                            echo "<input type='button' class='button' value='Search' onclick=\"window.open('$row[search_file].php?id=search_value','popup','width=600,height=400,');\"  />";
                        } else if ($row[input_type_id] == '6') {

                            echo "<input type='file' size='$row[field_size]' id='user-file' name='$row[field_name]' maxlength='$row[field_length]' value='$display_field' $required/>";
                        } else if ($row[input_type_id] == '7') {

                            echo "<textarea name='$row[field_name]' rows='13' cols='25' >$display_field</textarea>";
                        } else if ($row[input_type_id] == '3') {


                            $redio_name = explode(",", $row[field_type_value]);

                            foreach ($redio_name as $key => $value) {
                                if ($display_field == $key) {
                                    $checked = 'checked';
                                }
                                echo "<input type='radio' name='$row[field_name]' value='$key' $checked $required />$value   ";
                            }
                        } else {
                            
                        }

                        echo "</td>";
                    }

                    if ($row[_show] == '1') {
                        if ($table_column == 2) {
                            $rows = mysql_fetch_array($rs);
                            $convert_name = str_replace("_", " ", $rows[display_name]);
                            $uc_name = ucwords($convert_name);
                            ($uc_name != '') ? $uc_name = $uc_name . ' :' : '';

                            $ajux_sql = $rows[master_mod_id];

                            $d_field = ($rows[field_name] != '') ? $rows[field_name] : '1';
                            $display_field = $var->$d_field;

                            if ($rows[field_required] == 'Y') {
                                $required = "class='required $row[required_symbol]'";
                                $required_combo = 'required';
                            } else {
                                $required = '';
                                $required_combo = '';
                            }
                            echo "<td width='150'>$uc_name";
                            if ($rows[field_required] == 'Y') {
                                echo "<em>*</em>";
                            } echo "</td>";

                            $ajax_file_name = findValue("SELECT (SELECT master_mod_id FROM master_mod WHERE master_mod.master_mod_id=mo.affected_field_name) AS 'ajax_file_name' FROM master_mod AS mo WHERE master_mod_id='$rows[master_mod_id]'");
                            echo "<td id='$rows[ajax_file_name]-$ajax_file_name'>";
                            if ($rows[input_type_id] == '1') {
                                echo "<input size='$rows[field_size]' type='text' autocomplete='on' placeholder='' name='$rows[field_name]' maxlength='$rows[field_max]' $required value='$display_field' />";
                            } else if ($rows[input_type_id] == '2') {
                                $data = rs2array(query("$rows[combo_sql]"));
                                $combo_name = "$rows[field_name]";
                                combobox($combo_name, $data, $display_field, true, $required_combo, "$rows[ajax_file_name]", $ajux_sql);
                            } else if ($rows[input_type_id] == '5') {

                                echo "<input size='$rows[field_size]' id='search_value' type='text' autocomplete='on' placeholder='' name='$rows[field_name]' maxlength='$rows[field_length]' $required value='$display_field'/> ";
                                echo "<input type='button' class='button' value='Search' onclick=\"window.open('$rows[search_file].php?id=search_value','popup','width=600,height=400,');\"  />";
                            } else if ($rows[input_type_id] == '6') {

                                echo "<input type='file' size='$rows[field_size]' id='user-file' name='$rows[field_name]' maxlength='$rows[field_length]' value='$display_field' $required/>";
                            } else if ($rows[input_type_id] == '7') {

                                echo "<textarea name='$rows[field_name]' rows='13' cols='25' >$display_field</textarea>";
                            } else if ($rows[input_type_id] == '3') {

                                $redio_name = explode(",", $rows[field_type_value]);

                                foreach ($redio_name as $key => $value) {
                                    if ($display_field == $key) {
                                        $checked = 'checked';
                                    }
                                    echo "<input type='radio' name='$rows[field_name]' value='$key' $checked $required />$value";
                                }
                            } else if ($rows[input_type_id] == '') {
                                
                            }

                            echo "</td>";
                        }
                    }
                    echo "</tr>";
                }
                ?>

            </table>

            <br/>
            <?php saveButton() ?>
            <input type="hidden" name="mode" value="<?php echo $mode ?>" />
            
            <input type="hidden" name="search_id" value="<?php echo $search_id ?>" />

        </fieldset>
    </form>

<?php } ?>
