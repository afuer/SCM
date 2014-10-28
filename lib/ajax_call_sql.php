<?php

include('include.php');
$val = getParam("val");


if ($val == "Y") {

    echo "<td><textarea name='ajax_sql' cols='100' rows='4' placeholder='Ajax SQL'></textarea></td>";
} else {
    echo "<td><textarea name='ajax_sql' disabled='true' cols='100' rows='4'></textarea></td>";
}
?>






