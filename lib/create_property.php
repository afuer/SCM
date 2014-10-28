<?php
include '../lib/DbManager.php';
include("../body/master_header.php");


$search_id = getParam('search_id');
$mode = getParam('mode');
$object_id = getParam('object_id');


$page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);
$limit = (int) (!isset($_GET["limit"]) ? 100 : $_GET["limit"]);
$startpoint = ($page * $limit) - $limit;

//$sql_table = query("SHOW TABLES");
$odject_list = $db->rs2array("SHOW TABLES");


$total = 100;

//echo "SELECT DATA_TYPE, CHARACTER_MAXIMUM_LENGTH, COLUMN_TYPE FROM information_schema.COLUMNS WHERE TABLE_NAME ='$table_name' AND COLUMN_NAME='$field_name' AND TABLE_SCHEMA='$DB_NAME'";

if (isSave()) {
    $table_name = getParam('table_name');
    $field_name = getParam('field_name');
    $display_name = getParam('display_name');
    $input_type_id = getParam("input_type");
    $field_required = getParam('field_required');
    $required_symbol = getParam('required_symbol');
    $combo_sql = getParam('combo_sql');
    $search_file = getParam('search_file');
    $ajax_call = getParam('ajax_call');
    $ajax_sql = getParam('ajax_sql');
    $ajax_file_name = getParam('ajax_file_name');
    $OnChange_field = getParam('OnChange_field');
    $affected_field_name = getParam("affected_field_name");
    $display_name = getParam("display_name");
    $field_size = getParam('field_size');
    $field_type_value = getParam('field_type_value');
    $show = getParam('_show');
    $sort = getParam('_sort');

    $comments = $display_name . ',' . $show . ',' . $field_required . ',' . $ajax_call . ',' . $OnChange_field . ',' . $field_size . ',' . $input_type_id;

    //if ($mode == '') {
    //echo "SELECT DATA_TYPE, COLUMN_TYPE, CHARACTER_MAXIMUM_LENGTH FROM information_schema.COLUMNS WHERE TABLE_NAME ='$table_name' AND COLUMN_NAME='$field_name' AND TABLE_SCHEMA='$DB_NAME'";
    $info = $db->find("SELECT DATA_TYPE, COLUMN_TYPE, CHARACTER_MAXIMUM_LENGTH FROM information_schema.COLUMNS WHERE TABLE_NAME ='$table_name' AND COLUMN_NAME='$field_name' AND TABLE_SCHEMA='$DB_NAME'");
    if ($info->DATA_TYPE == 'varchar' || $info->DATA_TYPE == 'text' || $info->DATA_TYPE == 'int' || $info->DATA_TYPE == 'decimal') {
        //echo $info->COLUMN_TYPE.='=='; die();
        $sql = "ALTER TABLE `$table_name` MODIFY COLUMN `$field_name` $info->COLUMN_TYPE COMMENT '$comments' AFTER `$sort`;";
        $db->sql($sql);
        if ($input_type_id == 2) {
            $count = $db->findValue("SELECT * FROM master_object WHERE OBJECT_NAME='$table_name' AND FIELD_NAME='$field_name'");
            if ($count == 0) {
                $sql_combo = "INSERT INTO master_object(OBJECT_NAME, FIELD_NAME, SQL_QUERY, TR_TYPE, CREATED_BY, CREATED_DATE) VALUES('$table_name', '$field_name', '$combo_sql', 1, '$user_name', NOW())";
                $db->sql($sql_combo);
            } else {
                $sql_combo = "UPDATE master_object SET SQL_QUERY='$combo_sql', MODIFY_BY='$user_name', MODIFY_DATE=NOW() WHERE OBJECT_NAME='$table_name' AND FIELD_NAME='$field_name'";
                $db->sql($sql_combo);
            }
        } elseif ($input_type_id == 3 || $input_type_id == 4) {

            echo $count = $db->findValue("SELECT * FROM master_object WHERE OBJECT_NAME='$table_name' AND FIELD_NAME='$field_name'");
            if ($count == 0) {
                $sql_combo = "INSERT INTO master_object(OBJECT_NAME, FIELD_NAME, SQL_QUERY, TR_TYPE, CREATED_BY, CREATED_DATE) VALUES('$table_name', '$field_name', '$field_type_value', 1, '$user_name', NOW())";
                $db->sql($sql_combo);
            } else {
                $sql_combo = "UPDATE master_object SET SQL_QUERY='$field_type_value', MODIFY_BY='$user_name', MODIFY_DATE=NOW() WHERE OBJECT_NAME='$table_name' AND FIELD_NAME='$field_name'";
                $db->sql($sql_combo);
            }
        }
    } elseif ($info->DATA_TYPE == 'date') {
        $sql = "ALTER TABLE `$table_name` MODIFY COLUMN `$field_name` $info->DATA_TYPE COMMENT '$comments' AFTER `$sort`;";
        $db->sql($sql);
    }
    //sql($sql);
    //} else {
    //echo $updateSQL = "ALTER TABLE `$table_name` MODIFY COLUMN `$field_name`  varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL COMMENT '$comments' AFTER `$sort`;";
    //sql($updateSQL);
    //}
    echo "<script>location.replace('create_property.php?object_id=$object_id&mode=');</script>";
}


if ($mode == 'delete') {
    
}
//echo "<pre>";
if ($search_id) {
    $sql2 = "SELECT COLUMN_COMMENT, COLUMN_NAME, ORDINAL_POSITION
    FROM information_schema.COLUMNS 
    WHERE TABLE_NAME ='$object_id' AND COLUMN_NAME='$search_id' AND TABLE_SCHEMA='$DB_NAME'";
    $var = $db->find($sql2);

    $explode = explode(',', $var->COLUMN_COMMENT);

    //print_r($explode);

    $explode_zero = isset($explode[0]) ? $explode[0] : '';
    $explode_one = isset($explode[1]) ? $explode[1] : '';

    $explode_two = isset($explode[2]) ? $explode[2] : '';
    $explode_three = isset($explode[3]) ? $explode[3] : '';
    $explode_four = isset($explode[4]) ? $explode[4] : '';
    $explode_five = isset($explode[5]) ? $explode[5] : '20';
    $explode_six = isset($explode[6]) ? $explode[6] : '';

    $input_type = $db->findValue("SELECT input_type_id FROM master_input_type WHERE input_type_id='$explode_six'");

    $field_position = $var->ORDINAL_POSITION;
    //$decimal_place = $var->decimal_place;
} else {
    $field_position = isset($field_position) ? $field_position : '';
    $input_type = isset($input_type) ? $input_type : '';
    $search_id = isset($search_id) ? $search_id : '';
    $explode_zero = isset($explode[0]) ? $explode[0] : '';
    $explode_one = isset($explode[1]) ? $explode[1] : '';

    $explode_two = isset($explode[2]) ? $explode[2] : '';
    $explode_three = isset($explode[3]) ? $explode[3] : '';
    $explode_four = isset($explode[4]) ? $explode[4] : '';
    $explode_five = isset($explode[5]) ? $explode[5] : '20';
    $explode_six = isset($explode[6]) ? $explode[6] : '';
}

$input_type_list = $db->rs2array("SELECT input_type_id, input_type_display FROM master_input_type");
$tables_list = $db->rs2array("SHOW TABLES");
$sort_list = $db->rs2array("SELECT COLUMN_NAME, COLUMN_NAME FROM information_schema.COLUMNS WHERE TABLE_NAME ='$object_id' AND TABLE_SCHEMA='$DB_NAME'");
//echo"SELECT COLUMN_NAME, COLUMN_NAME FROM information_schema.COLUMNS WHERE TABLE_NAME ='$object_id' AND TABLE_SCHEMA='$DB_NAME'";

include_once ('pagination.php');

if ($mode == 'new' || $mode == 'search') {
    ?>

    <form action='' method='POST' id="form" title="Master Property Creator" class="form fc">
        <fieldset>
            <legend>Property Creator</legend>
            <table>
                <tr>
                    <td width="150">Table Name:</td>
                    <td><?php comboBox('table_name', $tables_list, $object_id, TRUE, 'required', 'ajax_field_name'); ?></td>
                </tr>
                <tr>
                    <td>Field Name:</td>
                    <td id="ajax_field_name"><input type="text" id="field_name" name="field_name" value="<?php echo $search_id; ?>" class="required"/></td>
                    <td>Display Label:</td>
                    <td id="ajax_display_name"><input type="text" name="display_name" value="<?php echo $explode_zero; ?>" class=""/></td>
                </tr>
                <tr>
                    <td>Input Type:</td>
                    <td><?php combobox("input_type", $input_type_list, $input_type, TRUE, 'required', 'ajax_input_type'); ?> </td>
                    <td colspan="2" id="ajax_input_type">
                        <?php
                        if ($input_type == '2') {
                            $combo_sql = $db->findValue("SELECT SQL_QUERY FROM master_object 
                                    WHERE object_name='$object_id' AND field_name='$search_id'");
                            echo "<textarea name='combo_sql' style='width: 300px; height: 80px;'>$combo_sql</textarea>";
                        } else if ($input_type == '3' || $input_type == '4') {
                            echo "<textarea name='field_type_value'></textarea>";
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>Field Required:</td>
                    <td>
                        Yes <input type='radio' class="regular-checkbox" name="field_required" value="Y" onChange="ajaxLoader('ajax_input_required.php?val=' + this.value + '', 'ajax_input_required', '<img src=../public/images/loading.gif />');" 
                        <?php
                        if ($explode_two == 'Y') {
                            echo 'checked';
                        }
                        ?> />
                        No <input type='radio' class="regular-checkbox" name="field_required" value="N" onChange="ajaxLoader('ajax_input_required.php?val=' + this.value + '', 'ajax_input_required', '<img src=../public/images/loading.gif />');" 
                        <?php
                        if ($explode_two == 'N') {
                            echo 'checked';
                        }
                        ?> />
                    </td>
                    <td>Ajax Call:</td>
                    <td>
                        Yes <input type='radio' class="regular-checkbox" name="ajax_call" value="Y" onChange="ajaxLoader('ajax_call_sql.php?val=' + this.value + '', 'ajax_call', '<img src=../public/images/loading.gif />');" <?php
                        if ($explode_three == 'Y') {
                            echo 'checked';
                        }
                        ?> />
                        No <input type='radio' class="regular-checkbox" name="ajax_call" value="N" onChange="ajaxLoader('ajax_call_sql.php?val=' + this.value + '', 'ajax_call', '<img src=../public/images/loading.gif />');" <?php
                        if ($explode_three == 'N') {
                            echo 'checked';
                        }
                        ?> />
                    </td>

                </tr>
                <tr>
                    <td>Required :</td>
                    <td id="ajax_input_required"><input type="text" name="required_symbol" disabled="true" value="<?php echo $explode_two; ?>"/></td>
                    <td>Ajax SQL:</td>
                    <td id="ajax_call">
                        <?php //if ($var->ajax_sql != '')  {
                        ?>

                        <textarea name='ajax_sql' cols='100' rows='4'><?php //echo $var->ajax_sql;                              ?></textarea>
                        <?php //}     ?>
                    </td>
                </tr>
                <tr>

                    <td>Ajax OnChange Field:</td>
                    <td><input type="text" id="property_id" name="OnChange_field" value="<?php echo $explode_four; ?>" size="20"/></td>
                    <td>Ajax Call File Name:</td>
                    <td><input type="text" name="ajax_file_name" value="<?php echo $var->ajax_file_name; ?>" size="20"/></td>
                </tr>
                <tr>
                    <td>Show In Form:</td>
                    <td><input type="checkbox" class="required" name="_show" value="y" <?php
                        if ($explode_one == 'y') {
                            echo 'checked';
                        }
                        ?> size="20"/></td>
                    <td>Field Size:</td>
                    <td><input type="text" name="field_size" class="number" value="<?php echo $explode_five; ?>" size="20"/></td>
                </tr>
                <tr>
                    <td>Sort:</td>
                    <td><?php comboBox('_sort', $sort_list, $field_position, TRUE, 'required'); ?></td>
                </tr>
            </table>
            <br/>
            <?php saveButton(); ?>
            <input type="hidden" name="mode" value="<?php echo $mode ?>" />
            <input type="hidden" name="search_id" value="<?php echo $search_id ?>" />
        </fieldset>
    </form>
<?php } else { ?>
    <br/><br/>
    <script type="text/javascript">
                            $(document).ready(function() {
                                $('#excel').click(function() {
                                    var data = $('#resultTable').table2CSV();

                                    $('#data').val(data);
                                    //window.location.href = '../lib/xl_print.php?data='+data;
                                });

                                $(".ui-state-default").delegate("tr", "click", function() {
                                    $(this).addClass("even DTTT_selected").siblings().removeClass("even DTTT_selected");
                                });

                                $("table.ui-state-default").tablesorter();
                                //$("table.ui-state-default").tableFilter();

                            });
    </script>


    <div class="table-header">Master Module Creator</div>
    <form action="" method="GET">
        <table>
            <tr>
                <td width="100">Object Name: </td>
                <td width="100"><?php comboBox('object_id', $odject_list, $object_id, FALSE); ?></td>
                <td width="100"><button type="submit" name="view" class="button">View</button></td>
                <td></td>
            </tr>
        </table>
    </form>
    <?php grid_top($total, $limit); ?>
    <div class='table-worp' >
        <table id="resultTable" class="ui-state-default" width="100%">
            <thead>
            <th>SL</th>
            <th>Object Name</th>
            <th>Field Name</th>
            <th>Field Type</th>
            <th>Show <br/> Allow Null</th>
            <th>Decimal Places</th>
            <th>Field Required</th>
            <th>Ajax Call</th>
            <th>Ajax File Name</th>
            <th>Field Max</th>
            <th width="20">Sort</th>
            <th colspan="2">Action</th>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM $object_id";
                $sl = 1;
                $result_sql = $db->query($sql);
                while ($meta = mysql_fetch_field($result_sql)) {
                    $fielf_sql = "SELECT COLUMN_COMMENT, COLUMN_NAME,
                                ORDINAL_POSITION, IS_NULLABLE, COLUMN_TYPE
                                FROM information_schema.COLUMNS 
                                WHERE TABLE_NAME ='$object_id' AND 
                                COLUMN_NAME='$meta->name' AND TABLE_SCHEMA='$DB_NAME'";
                    //echo "<br/>";
                    $comments = $db->find($fielf_sql);
                    $explode = explode(',', $comments->COLUMN_COMMENT);

                    //print_r($explode);

                    $explode_zero = isset($explode[0]) ? $explode[0] : '';
                    $explode_one = isset($explode[1]) ? $explode[1] : '';
                    $input_type = $db->findValue("SELECT input_type_display FROM master_input_type WHERE input_type_id='$explode_one'");
                    $explode_one = $explode_one == 'y' ? 'Yes' : 'No';

                    $explode_two = isset($explode[2]) ? $explode[2] : '';
                    $explode_three = isset($explode[3]) ? $explode[3] : '';
                    $explode_four = isset($explode[4]) ? $explode[4] : '';



                    echo "<tr>";
                    echo "<td width='20'>$sl</td>";
                    echo "<td><a href='create_property.php?mode=search&search_id=$meta->name&object_id=$object_id'>$object_id</a></td>";
                    echo "<td>$meta->name <br/>($explode_zero)</td>";
                    echo "<td>$input_type</td>";
                    echo "<td>$explode_one $comments->IS_NULLABLE</td>";
                    echo "<td></td>";
                    echo "<td></td>";
                    echo "<td></td>";
                    echo "<td></td>";
                    echo "<td>$comments->COLUMN_TYPE</td>";
                    echo "<td>$comments->ORDINAL_POSITION</td>";
                    echo "<td width='2'>";
                    editIcon("create_property.php?mode=search&search_id=");
                    echo "</td>";
                    echo "<td width='2'>";
                    deleteIcon("create_property.php?mode=delete&search_id=");
                    echo "</td>";
                    echo "</tr>";
                    $sl++;
                }
                ?>
            </tbody>
        </table>
    </div>
    <?php
    pagination($total, $page, '?', $limit);
}
$db->CloseDb();
include("../body/footer.php");
?>
