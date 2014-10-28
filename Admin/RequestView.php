<?php
include_once '../lib/DbManager.php';
include('RequestDAL.php');
$ObjRequestnData = new RequestData();


$search_id = getParam('search_id');

$RequestHeading = $ObjRequestnData->GetDataRequest($search_id);
  //$CcList = $ObjRequisitionData->get_gl_list_by_requisition_id($SearchId);

include("../body/header.php");

?>

<br/>
<form action="" method="POST" name='requisition' class="form">

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
                <td width="300"><?php echo $RequestHeading->REQUEST_COMMENT ;?> </td>
                <td width="100">Request Type:</td>
                <td><?php echo  $RequestHeading->REQUEST_NAME; ?></td>
            </tr>
        </table>
    </fieldset>
    <br/>
    
    <a href="RequestEdit.php?mode=search&search_id=<?php echo $search_id;?>"class="button"> Edit </a>
</form>


<?php include("../body/footer.php"); ?>