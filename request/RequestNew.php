<?php
include '../lib/DbManager.php';
include('RequestDAL.php');
$ObjRequestnData = new RequestData();

$RequestTypeID = getParam('request_id');
//$RequestTypeID = 1;
$mode = getParam('mode');


$MaxRequestId = NextId('request_list', 'REQUEST_LIST_ID');

if (isSave()) {
    $RequestComment = getParam('RequestComment');
    if ($mode == 'new') {
        $ObjRequestnData->SaveRequest($MaxRequestId, $RequestComment, $user_name, $RequestTypeID);
    }
    echo "<script>location.replace('RequestList.php');</script>";
}

include("../body/header.php");
?>
<br/>
<form action="" method="POST" name='request' class="form">

    <fieldset class="fieldset">
        <legend>Request For</legend>
        <table>
            <tr>
                <td width="100">To:</td>
                <td>Admin</td>
            </tr>
            <tr>
                <td width="100">Comment:</td>
                <td><textarea  name="RequestComment"> </textarea> </td>
            </tr>
        </table>
        <button type="submit" name="save" value="SaveRequist" class="button">Save</button>
    </fieldset>
</form>


<?php include("../body/footer.php"); ?>