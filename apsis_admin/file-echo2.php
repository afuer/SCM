<?php

$q = $_GET['mode'];

if ($q == 'q') {
    $targetFolder = '../documents/PR/'; // Relative to the root
    $random_digit = rand(000000, 999999);
    $id = isset($_POST['id']) ? $_POST['id'] : '';

    if (!file_exists($targetFolder))
        mkdir($targetFolder);


    if (!empty($_FILES)) {
        $tempFile = $_FILES['myfile']['tmp_name'];
        $targetPath = $targetFolder; //$_SERVER['DOCUMENT_ROOT'] . $targetFolder;
        // Validate the file type
        $fileTypes = array('jpg', 'JPG', 'jpeg', 'JPGE', 'gif', 'pdf', 'png', 'sql', 'xls', 'xlsx', 'doc', 'docx', 'ppt'); // File extensions
        $fileParts = pathinfo($_FILES['myfile']['name']);


        if (in_array($fileParts['extension'], $fileTypes)) {

            $file_name = basename($_FILES['myfile']['name'], '.' . $fileParts['extension']);
            $targetFile = str_replace('//', '/', $targetPath . $file_name . $random_digit) . $id . substr($_FILES['myfile']['name'], -4);
            move_uploaded_file($tempFile, $targetFile);

            $path = $targetFolder . $file_name . $random_digit . '.' . $fileParts['extension'];
        } else {
            echo 'Invalid file type.';
        }
    }
}
?>
