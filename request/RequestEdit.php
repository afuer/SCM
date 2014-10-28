<?php
include('include.php');
include('RequestDAL.php');
$ObjRequestnData = new RequestData();


$search_id = getParam('search_id');
$mode = getParam('mode');
$RequestHeading = $ObjRequestnData->GetDataRequest($search_id);


$RequestTypeList = rs2array(query("SELECT REQUEST_TYPE_ID, REQUEST_NAME FROM request_type ORDER BY  REQUEST_TYPE_ID"));

include("../body/header.php");

if ($mode == 'delete') {
    $ObjRequestnData->DeletRequest($search_id);
    echo "<script>location.replace('RequestList.php');</script>";
}

if (isSave()) {
    if ($mode == 'search') {
        $RequestComment = getParam('RequestComment');
        $RequestId = getParam('RequestId');
        $RequestTypeID = getParam('RequestTypeID');

        $ObjRequestnData->EditRequest($RequestComment,$RequestId,$RequestTypeID) ;
 
    }

    echo "<script>location.replace('RequestList.php');</script>";
}
?>




<br/>
<form action="" method="POST" name='requisition' class="form">
    <input type="hidden" name="RequestId" value="<?php echo $RequestHeading->REQUEST_LIST_ID; ?>" />
    <fieldset class="fieldset">
        <legend >Requisition Information</legend>
        <table>
            <tbody>
                <tr>
                    <td width="150">Requisition no:  </td>
                    <td><?php echo $RequestHeading->REQUEST_LIST_ID; ?></td>
                    <td width="150">Staff Member:</td>
                    <td><?php echo $RequestHeading->FULL_NAME . '(' . $RequestHeading->CREATED_BY . ')'; ?></td>
                </tr>
                <tr>
                    <td>Requisition date:</td>
                    <td> <?php echo bddate($RequestHeading->CREATED_DATE); ?></td>
                    <td>Location :</td>
                    <td><?php echo user_location($RequestHeading->CREATED_BY); ?></td>
                </tr>
                <tr>
                    <td>Created by:</td>
                    <td><?php echo $RequestHeading->CREATED_BY; ?></td>
                    <td></td>
                    <td></td>

                </tr>                    
            </tbody>
        </table>
    </fieldset>
    <br/>

    <fieldset class="fieldset">
        <legend>Product Information</legend>
        <table>
            <tr>
                <td width="100">Comment:</td>
                <td width="300" >
                    <textarea  name="RequestComment"><?php echo $RequestHeading->REQUEST_COMMENT; ?> </textarea> 

                </td>
                <td width="100">Request Type:</td>
                <td>   <?php comboBox('RequestTypeID', $RequestTypeList, $RequestHeading->REQUEST_TYPE, False); ?></td>
            </tr>
        </table>
    </fieldset>
    <br/>
    <button type="submit" name="save" value="SaveRequist" class="button">Save</button>
</form>


<?php include("../body/footer.php"); ?>