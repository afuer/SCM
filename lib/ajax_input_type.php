<?php

include('include.php');
$val = getParam("val");


if ($val == 2) {

    echo "<td><textarea name='combo_sql' cols='100' rows='4' placeholder='Combo SQL'></textarea></td>";
} else if ($val == 3 || $val == 4) {

    echo "<td><textarea name='field_type_value' cols='100' rows='4' placeholder='Coma Separated Value'></textarea></td>";
} else if ($val == 5) {

    echo "<td><input type='text' name='search_file' placeholder='Search File Name'/></td>";
}
?>






