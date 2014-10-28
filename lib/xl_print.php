<?php

if (isset($_POST['data'])) {

    $payment_list = $_POST['data'];

    $d = explode("||", $payment_list);

    $filename = 'invoice'. "_" . date("Y-m-d_H-i", time());

//Generate the CSV file header
    header("Content-type: application/vnd.ms-excel");
    header("Content-disposition: csv" . date("Y-m-d") . ".csv");
    header("Content-disposition: filename=" . $filename . ".csv");

//Print the contents of out to the generated file.
    foreach ($d as $key => $value) {
        echo "$value\n";
    }

//Exit the script
    exit;
}
?>