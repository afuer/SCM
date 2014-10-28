<?php
include 'include.php';

$CheckBRANCH_NAME = getParam('CheckBRANCH_NAME');
$CheckDivision = getParam('CheckDivision');
$BRANCH_NAMEList = rs2array(query("SELECT BRANCH_NAMEID,BRANCH_NAME FROM BRANCH_NAME ORDER BY BRANCH_NAME"));
$DivisionList = rs2array(query("SELECT DIVISIONID,DIVISION FROM division ORDER BY DIVISION"));
?>

<script type="text/javascript">
    $(document).ready(function(){
        $('#ADDBrach').click(function(){
            $('#TableBRANCH_NAMEList tbody>tr:last').clone(true).insertAfter('#TableBRANCH_NAMEList tbody>tr:last').val();                    
        });  
        $('#ADDDivision').click(function(){ 
            $('#TableDivisionList tbody>tr:last').clone(true).insertAfter('#TableDivisionList tbody>tr:last').val();                   
        });
        $('#DeleteBRANCH_NAME').live("click", function(){
            $('#TrBRANCH_NAME').remove();          
            return false;               
        }) 
        $('#DeleteDivision').live("click", function(){
            $('#TrDivision').remove();          
            return false;               
        }) 
        
    });
</script>
<?php if ($CheckBRANCH_NAME == '1' || ($CheckBRANCH_NAME == '1' && $CheckDivision == '1')) { ?>
    <fieldset class="fieldset">
        <table class="ui-state-default"  style="width:30%; float:left;" id="TableBRANCH_NAMEList">
            <thead>
            <th width="20">SL</th>
            <th>BRANCH_NAME Name</th>
            <th> Action</th>
            </thead>
            <tbody>

                <tr id="TrBRANCH_NAME">
                    <td >1.
                        <input style="display:none;" type="checkbox" name="CheckBRANCH_NAMEEach[]" value="1" checked="checked">
                    </td>
                    <td><?php combobox('BRANCH_NAMEName[]', $BRANCH_NAMEList, '', true); ?></td>
                    <td><button id="DeleteBRANCH_NAME"> Delete</button></td>
                </tr>
            </tbody>
            <tfoot >
            <th></th>
            <th align="right"> <button type="button" class="button" id="ADDBrach"> Add BRANCH_NAME </button></th>
            </tfoot>
        </table>
        <?php
    }
    if ($CheckDivision == '1' || ($CheckBRANCH_NAME == '1' && $CheckDivision == '1')) {
        ?>

        <table class="ui-state-default"  style="width:30%; float:right;" id="TableDivisionList">

            <thead>
            <th width="20">SL</th>
            <th width="20">Division Name</th>
            <th>Action</th>
            </thead>
            <tbody>
                <tr id="TrDivision">
                    <td> 1. 
                        <input style="display:none;" type="checkbox" name="CheckDivisionEach[]" value="1" checked="checked">
                    </td>
                    <td><?php combobox('DivisionName[]', $DivisionList, '', true); ?></td>
                    <td><button id="DeleteDivision"> Delete</button></td>
                </tr>

            </tbody>
            <tfoot >
            <th></th>
            <th align="right"> <button type="button" class="button" id="ADDDivision"> Add Division </button></th>
            </tfoot>
        </table>
        <?php
    }
    ?>
</fieldset>