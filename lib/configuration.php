<?php

include '../conf/AppConfig.php';
if (DB_TYPE == 'oci') {
    include 'oci_lib/standard_include.php';

    $user_name = get('user_name');
    $user_type = get('user_type');
    $mode = getParam('mode');
} else {
    include 'standard_include.php';
}


?>
