<?php

include('include.php');
$val = getParam("val");

$required_list = array(array('email', 'Email'), array('number', 'Number'), array('date', 'Date'), array('url', 'Web'));
if ($val == "Y") {

    echo "<td>";
    comboBox('required_symbol', $required_list, '', TRUE);
    echo "</td>";
} else {
    echo "N/A";
}
?>






