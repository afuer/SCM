<table class="">
    <?php
    
    $sql = $query;

    $query_result = query(strtolower($sql));
    $result = query($sql);
    $row = mysql_fetch_array($result);
    $i = 1;
    while ($i < mysql_num_fields($query_result)) {
        $meta = mysql_fetch_field($query_result, $i);
        //$var = fetch_object($result, $i);
        $head = str_replace("_", " ", $meta->name);
        $field_name = "$meta->name";
        $field_value = isset($field_name) ? $var->$field_name : '';
        ?>
        <tr>
            <td width="150" ><?php echo $head; ?>: </td>
            <td><?php echo $row[$i]; ?></td>
        </tr>

        <?php
        $i++;
    }
    ?>

</table>
