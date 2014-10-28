<?php

$targetFolder = '../documents/'; // Relative to the root
//$targetFolder_dir=mkdir($targetFolder . $_POST['id']);
$random_digit=rand(00000,99999);

if (!file_exists($targetFolder))
    mkdir($targetFolder);


if (!empty($_FILES)) {
    $tempFile = $_FILES['Filedata']['tmp_name'];
    $targetPath = $targetFolder; //$_SERVER['DOCUMENT_ROOT'] . $targetFolder;


    if (isset($_POST['id'])) {
        $targetFile = str_replace('//', '/', $targetPath.$random_digit) . $_POST['id'] .
                substr($_FILES['Filedata']['name'], -4);
    } else {
        $targetFile = str_replace('//', '/', $targetPath) . $_FILES['Filedata']['name'];
    }

    //$targetFile = rtrim($targetPath,'/') . '/' . $_FILES['Filedata']['name'];
    // Validate the file type
    $fileTypes = array('jpg', 'JPG', 'jpeg', 'JPGE', 'gif', 'pdf', 'png', 'sql', 'xls', 'xlsx', 'doc', 'docx', 'ppt'); // File extensions
    $fileParts = pathinfo($_FILES['Filedata']['name']);

    if (in_array($fileParts['extension'], $fileTypes)) {
        move_uploaded_file($tempFile, $targetFile);

        echo $targetFolder.$random_digit . $_POST['id'] .'.'. $fileParts['extension'];
    } else {
        echo 'Invalid file type.';
    }
} 