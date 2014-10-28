<?php
include 'include.php';

$CheckBRANCH_NAME = getParam('CheckBRANCH_NAME');
$CheckDivision = getParam('CheckDivision');
?>

<script type="text/javascript">
    $(document).ready(function(){
        $('#CheckAllBRANCH_NAME').click(function(){
            $('input:checkbox[name=CheckBRANCH_NAMEEach]').prop("checked", true);
        });
        $('#ClearkAllBRANCH_NAME').click(function(){
            $('input:checkbox[name=CheckBRANCH_NAMEEach]').prop("checked", false);
        })
        
        $('#CheckAllDivision').click(function(){
            $('input:checkbox[name=CheckDivisionEach]').prop("checked", true);
        });
        $('#ClearkAllDivision').click(function(){
            $('input:checkbox[name=CheckDivisionEach]').prop("checked", false);
        })     
        
    });
</script>
<?php
if ($CheckBRANCH_NAME == '1' || ($CheckBRANCH_NAME == '1' && $CheckDivision == '1')) {
    $ResultBRANCH_NAMEList = query("SELECT BRANCH_NAMEID,BRANCH_NAME
    FROM BRANCH_NAME ORDER BY BRANCH_NAME");
    ?>

<fieldset> 
    <table class="ui-state-default"  style="width:47%; float:left;">

        <thead>
        <th width="20">SL</th>
        <th width="100">Select</th>
        <th width="20">BRANCH_NAME Name</th>
    </thead>
    <tbody>
        <tr>
            <td></td>
            <td><button type="button" class="button" id="CheckAllBRANCH_NAME">Check All</button> &nbsp;
                <button type="button" class="button" id="ClearkAllBRANCH_NAME">Clear All</button></td>
            <td></td>
        </tr>
        <?php
        $SL = 1;
        while ($RowBRANCH_NAMEList = fetch_object($ResultBRANCH_NAMEList)) {
            ?>
            <tr>
                <td><?php echo $SL . '.'; ?></td>
                <td><input type="checkbox" name="CheckBRANCH_NAMEEach"  value="1"></td>
                <td><?php echo $RowBRANCH_NAMEList->BRANCH_NAME; ?><input type="hidden" name="BRANCH_NAMEName[]" value="<?php echo $RowBRANCH_NAMEList->BRANCH_NAMEID; ?>" /></td>
            </tr>
            <?php
            $SL++;
        }
        ?>
    </tbody>
    </table>
    <?php
}
if ($CheckDivision == '1' || ($CheckBRANCH_NAME == '1' && $CheckDivision == '1')) {
    $ResultBRANCH_NAMEList = query("SELECT DIVISIONID,DIVISION
    FROM division ORDER BY DIVISION");
    ?>

    <table class="ui-state-default"  style="width:47%; float:right;">

        <thead>
        <th width="20">SL</th>
        <th width="100">Select</th>
        <th width="20">Division Name</th>
    </thead>
    <tbody>
        <tr>
            <td></td>
            <td><button type="button" class="button" id="CheckAllDivision">Check All</button> &nbsp; 
                <button type="button" class="button" id="ClearkAllDivision">Clear All</button></td>
            <td></td>
        </tr>
        <?php
        $SL = 1;
        while ($RowBRANCH_NAMEList = fetch_object($ResultBRANCH_NAMEList)) {
            ?>
            <tr>
                <td><?php echo $SL . '.'; ?></td>
                <td><input type="checkbox" name="CheckDivisionEach" value="1" ></td>
                <td><?php echo $RowBRANCH_NAMEList->DIVISION; ?> <input type="hidden" name="DivisionName[]" value="<?php echo $RowBRANCH_NAMEList->DIVISIONID; ?>" /> </td>
            </tr>
            <?php
            $SL++;
        }
        ?>
    </tbody>
    </table>
    <?php
}
?>
</fieldset> 
