<?php

include('include.php');
$SearchId = getParam('search_id');

if (isSave()) {
    $BRANCH_NAMEName = getparam('BRANCH_NAMEName');
    $DivisionName = getparam('DivisionName');
    $PartialAllSelect = getParam('PartialAllSelect');
    $CheckBRANCH_NAME = getParam('CheckBRANCH_NAME');
    $CheckDivision = getParam('CheckDivision');
    $CheckBRANCH_NAMEEach = getParam('CheckBRANCH_NAMEEach');
    $CheckDivisionEach = getParam('CheckDivisionEach');
    $ContactName = getParam(ContactName);
    $ContactNumberEmail = getParam(ContactNumberEmail);
    $chk[0] = $CheckBRANCH_NAME;
    $chk[1] = $CheckDivision;
    $RequisitionFor = implode(',', $chk);






    if (isset($BRANCH_NAMEName)) {
        foreach ($BRANCH_NAMEName as $key => $val) {
            if (isset($CheckBRANCH_NAMEEach[$key])) {
                echo $MaxRequisitionForDetails = NextId('gp_requisition_for_details', 'GP_REQUISITION_FOR_DETAILS_ID');
                $Sql = "INSERT into gp_requisition_for_details (GP_REQUISITION_FOR_DETAILS_ID, REQUISITION_ID, REQUISITION_FOR, 
                    BRANCH_NAME_ID, DIVISION_ID, ISPARTIAL)
                    VALUES('$MaxRequisitionForDetails', '$SearchId', '$RequisitionFor', '$BRANCH_NAMEName[$key]', '', '$PartialAllSelect' )";


                query($Sql);
            }
        }
    }


    if (isset($DivisionName)) {
        foreach ($DivisionName as $key => $val) {
            if (isset($CheckDivisionEach[$key])) {
                echo $MaxRequisitionDetailsDivision = NextId('gp_requisition_for_details', 'GP_REQUISITION_FOR_DETAILS_ID');
                $Sql = "INSERT into gp_requisition_for_details (GP_REQUISITION_FOR_DETAILS_ID, REQUISITION_ID, REQUISITION_FOR, 
                    BRANCH_NAME_ID, DIVISION_ID, ISPARTIAL)
             VALUES('$MaxRequisitionDetailsDivision', '$SearchId', '$RequisitionFor', '', '$DivisionName[$key]','$PartialAllSelect')";


                query($Sql);
            }
        }
    }


    if (isset($ContactName)) {
        foreach ($ContactName as $key => $val) {

            echo $MaxRequisitionForContact = NextId('gp_requisition_for_contact', 'gp_requisition_for_contact_id');
            $Sql = "INSERT into gp_requisition_for_contact (gp_requisition_for_contact_id, REQUISITION_ID, CONTACT_NAME, 
                    CONTACTNUMBEREMAIL)
             VALUES('$MaxRequisitionForContact', '$SearchId', '$ContactName[$key]','$ContactNumberEmail[$key]')";


            query($Sql);
        }
    }

    echo " <script>location.replace('RequisitionForView.php?mode=search&SearchId=$SearchId');</script>";
}
include("../body/header.php");
?>
<script type="text/javascript">
    $(document).ready(function(){
        
        var CheckBRANCH_NAME=0 ;var CheckDivision= 0;
      
         
        $('input:radio[name=PartialAllSelect]').live('change', function() {
            var PartialAllSelect = $('input:radio[name=PartialAllSelect]:checked').val();
            if(PartialAllSelect=='0'){
                if ($('#CheckBRANCH_NAME').is(':checked')) {CheckBRANCH_NAME=1;}
                if ($('#CheckDivision').is(':checked')) {CheckDivision=1;}     
                $.ajax({
                    url: "RequisitionForPartialAjax.php?CheckBRANCH_NAME="+CheckBRANCH_NAME+"&CheckDivision="+CheckDivision,
                    data: "data=",
                    type: "GET",
                    contentType: "application/json",
                    dataType: "text",
                    success: function (data) {
                        $("#BRANCH_NAMEDivisionList").html(data);
                    }
                });
            } 
            if(PartialAllSelect=='1'){
            
                if ($('#CheckBRANCH_NAME').is(':checked')) {CheckBRANCH_NAME=1;}
                if ($('#CheckDivision').is(':checked')) {CheckDivision=1;}     
                $.ajax({
                    url: "RequisitionForAllAjax.php?CheckBRANCH_NAME="+CheckBRANCH_NAME+"&CheckDivision="+CheckDivision,
                    data: "data=",
                    type: "GET",
                    contentType: "application/json",
                    dataType: "text",
                    success: function (data) {
                        $("#BRANCH_NAMEDivisionList").html(data);
                    }
                });
            }
            
        }); 
        
        $('#ADDDContact').click(function(){ 
            $('#TableContactList tbody>tr:last').clone(true).insertAfter('#TableContactList tbody>tr:last').val();                   
        });
        
        $('#DeleteContact').live("click", function(){
            $('#TrContact').remove();          
            return false;               
        }) 
        
      
    });
</script>

<form action="" method="POST" enctype="multipart/form-data" name='postform'>
    <fieldset class="fieldset">
        <legend>Selection Of BRANCH_NAME Or Division </legend>
        <table>
            <tr>
                <td width="200">Requisition For</td>
                <td width="10"><input type="checkbox" name="CheckBRANCH_NAME" id="CheckBRANCH_NAME" value="0"/></td>
                <td width="150">BRANCH_NAME</td>
                <td width="10"><input type="checkbox" name="CheckDivision" id="CheckDivision" value="1" /> </td>
                <td width="150">Division</td> 
                <td></td>
            </tr>
            <tr>
                <td>Selection</td>
                <td><input type="radio" name="PartialAllSelect" value="0" /></td>
                <td>Partial</td>
                <td><input type="radio" name="PartialAllSelect" value="1" /></td>
                <td>All</td>       
                <td></td>
            </tr>
        </table>
    </fieldset>
    <br/>
    <div id="BRANCH_NAMEDivisionList"></div>
    <fieldset class="fieldset">
        <legend>Contact With</legend>
        <table class="ui-state-default" id="TableContactList">
            <thead>
            <th width="20" >SL</th>
            <th>Name</th>
            <th>Cell/Email</th>
            <th>Action</th>
            </thead>
            <tbody>
                <tr id="TrContact">
                    <td align="center">1.</td>
                    <td><input type="text" name="ContactName[]" style="width:300px;" /> </td>
                    <td><input type="text" name="ContactNumberEmail[]" style="width:300px;"  /></td>
                    <td align="center"><button id="DeleteContact" > Delete </button> </td>
                </tr>

            </tbody>
            <tfoot >
            <th></th>
            <th></th>
            <th align="right"> <button type="button" class="button" id="ADDDContact"> Add Contact </button></th>
            </tfoot>
        </table>
    </fieldset>
    <button type="submit" name="save" value="SaveRequisitionFor" class="button">Save</button>
</form>


<?php include("../body/footer.php"); ?>