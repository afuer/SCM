<?php
include_once '../lib/DbManager.php';



$employeeId = getParam('employeeId');
$message = getParam('mess');

if( $message == 'done'){ echo '<br/><h2> password is updated successfully.</h2> ';}

?>


