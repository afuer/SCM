<?php
include_once '../lib/DbManager.php';
include('RequestDAL.php');
$ObjRequestnData = new RequestData();


$Request_statusList = array(array('0', 'Pending'), array('1', 'Approved'));

include("../body/header.php");
//include '../lib/pagination.php';
?>

<div Title='Requisition List' class="easyui-panel" style="width:1000px; height:700px;" >

    <form action="" method="GET" name='requisition'> 
        <fieldset class="fieldset">
            <legend>Search Form</legend>
            <table>
                <tr>
                    <td width="150">Start Date:</td>
                    <td width="150"><input type="text" name="Date_start" class="date" value="<?php echo $Date_start; ?>"/></td>
                    <td width="150">End Date:</td>
                    <td width="150"><input type="text" name="Date_end" class="date" value="<?php echo $Date_end; ?>"/></td>
                    <td width="150">Request Type:</td>
                    <td> <?php comboBox('RequestType', $RequestTypeList, $RequestType, TRUE) ?></td>
                </tr>
                <tr>
                    <td>Status:</td>

                    <td><?php comboBox('Request_status', $Request_statusList, $Request_status, TRUE) ?></td>
                    <td>Request From:</td>
                    <td><input type="text" name="RequestFrom" value="<?php echo $RequestFrom; ?>"/></td>
                    <td></td>
                    <td></td>
                </tr> 
            </table>
            <button type="submit" name="save" value="SaveRequist" class="button">Search</button>
        </fieldset>
    </form>
    <br/>

    <table class="ui-state-default">
        <thead>
        <th width="20">SL</th>
        <th >Requisition Comment</th>
        <th width="100">Request Type</th>
        <th >Request Form</th>
        <th width="150">Request Date</th>
        <th width="80">Status</th>
        <th colspan="4">Action</th>
        </thead>
        <tbody>
            <?php
            while ($RowOfRequestList = fetch_object($request_result)) {
                ?>
                <tr>
                    <td><?php echo++$sl; ?>.</td>
                    <td><?php echo $RowOfRequestList->REQUEST_COMMENT; ?> </td>
                    <td><?php echo $RowOfRequestList->REQUEST_NAME; ?> </td>
                    <td><?php echo $RowOfRequestList->CREATED_BY . $RowOfRequestList->FULL_NAME; ?> </td>
                    <td><?php echo $RowOfRequestList->CREATED_DATE; ?> </td>
                    <?php $status = ($RowOfRequestList->REQUEST_STATUS > 0 ? Approved : Pending); ?>
                    <td><?php echo $status; ?> </td>
                    <td align='center' width='5'><a href="AddRequest.php">Add</a></td>
                    <td align='center' width='5'><?php viewIcon("RequestView.php?mode=search&search_id=$RowOfRequestList->REQUEST_LIST_ID'"); ?> </td>
                    <td align='center' width='5'><?php editIcon("RequestEdit.php?mode=search&search_id=$RowOfRequestList->REQUEST_LIST_ID'"); ?> </td>
                    <td align='center' width='5'><?php deleteIcon("RequestEdit.php?mode=delete&search_id=$RowOfRequestList->REQUEST_LIST_ID"); ?></td>


                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>
</div>
<?php include("../body/footer.php"); ?>

<?php
// $RequestID =1;  $ModifyBy = 2010441;  UpdateRequestStatus($RequestID,$ModifyBy) ?>