<?php
include '../lib/DbManager.php';


$val = getParam("val");
for ($x = 1; $x <= $val; $x++) {
// 	
    ?>
    <tr>
        <td>Title Name: <input name="title[]" value="" /></td>
    </tr>
<?php } ?>