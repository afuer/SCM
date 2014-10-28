<div id="dlg" class="easyui-dialog add-dialog" style="padding:10px 20px;" closed="true" buttons="#dlg-buttons">
    <form id="fm" method="post" novalidate autocomplete="off">
        <table class="table">
            <?php
            $sql = "SELECT * FROM $object_name";

            $result_show = $db->query($sql);
            while ($show_meta = mysql_fetch_field($result_show)) {
                //echo "SELECT COLUMN_COMMENT, COLUMN_NAME FROM information_schema.COLUMNS WHERE TABLE_NAME ='$object_name' AND COLUMN_NAME='$show_meta->name' AND TABLE_SCHEMA='$DB_NAME'";
                $comments = $db->find("SELECT COLUMN_COMMENT, COLUMN_NAME FROM information_schema.COLUMNS WHERE TABLE_NAME ='$object_name' AND COLUMN_NAME='$show_meta->name' AND TABLE_SCHEMA='$DB_NAME'");
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
                $explode_seven = isset($explode[7]) ? $explode[7] : '';

                if ($explode_one == 'y') {
                    $field_value = '';
                    $field_name = "$show_meta->name";
                    $field_value = isset($field_name) ? $var->$field_name : '';
                    echo "<tr>";

                    if ($explode_six == '1') {
                        //1 for varchar
                        $validate = $explode_two == 'Y' ? 'true' : 'false';
                        echo "<td width='120'><label>$explode_zero :</label></td>";
                        echo "<td id='td_$field_name'><input type='text' name='$field_name' id='$field_name' class='easyui-validatebox' data-options='required:$validate' /></td>";
                    } else if ($explode_six == '4') { //for CheckBox
                        echo "<td width='120'>$explode_zero :</td>";
                        //$field_name_array = $field_name . '[]';

                        $SQL_QUERY = $db->findValue("SELECT SQL_QUERY FROM master_object WHERE OBJECT_NAME='$object_name' AND FIELD_NAME='$field_name'");

                        $checkbox = explode(",", $SQL_QUERY);
                        echo "<td>";
                        foreach ($checkbox as $key => $value) {
                            $checkbox = $field_value == $value ? $checkbox = 'checked' : '';
                            echo "$value <input type='checkbox' id='$field_name$key' name='$field_name' value='$value' $checkbox />";
                        }
                        echo "</td>";
                    } elseif ($explode_six == '7') {
                        //7 for text
                        echo "<td width='120' valign='top'>$explode_zero :</td>";
                        echo "<td><textarea name='$field_name' id='$field_name' class='$field_name'>$field_value</textarea></td>";
                    } elseif ($explode_six == '2') {
                        $combo_sql = $db->findValue("SELECT SQL_QUERY FROM master_object WHERE OBJECT_NAME='$object_name' AND FIELD_NAME='$field_name'");

                        $data = $db->rs2array($combo_sql);
                        $OnChange = $explode_three == "" ? '' : "onChange($(this), '$explode_four');";
                        $validate = $explode_two == 'Y' ? 'true' : 'false';
                        ?>
                        <td width='120'><?php echo $explode_zero; ?>:</td>
                        <td id="<?php echo $show_meta->name; ?>" ><?php comboBox("$show_meta->name", $data, $field_value, TRUE, "$validate", '', '', "$OnChange"); ?></td>
                        <?php
                    } elseif ($explode_six == '3') {
                        $SQL_QUERY = $db->findValue("SELECT SQL_QUERY FROM master_object WHERE OBJECT_NAME='$object_name' AND FIELD_NAME='$field_name'");

                        $checkbox = explode(",", $SQL_QUERY);
                        ?>
                        <td width='120'><?php echo $explode_zero; ?> :</td>
                        <td>
                            <?php
                            foreach ($checkbox as $key => $value) {
                                $checkbox = $field_value == $key ? $checkbox = 'selected' : '';
                                echo "$value <input type='radio' id='$field_name$key' name='$show_meta->name' value='$key' $checkbox/>";
                            }
                            ?>

                        </td>
                        <?php
                    } else {
                        
                    }
                    echo "</tr>";
                }
            }
            mysql_free_result($result_show);
            ?>


        </table>
    </form>
</div>
<div id="dlg-buttons">
    <a href="#" class="easyui-linkbutton" iconCls="icon-ok" onclick="Save();">Save</a>
    <a href="#" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close');">Cancel</a>
</div>