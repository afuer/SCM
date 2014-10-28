<?php
include '../lib/DbManager.php';
include("../body/header.php");
//checkPermission(72);

$search_id = getParam('search_id');
$mode = getParam('mode');

$m1 = getParam('m1');
$m2 = getParam('m2');
$m3 = getParam('m3');
$m4 = getParam('m4');
$m5 = getParam('m5');
$m6 = getParam('m6');
$m7 = getParam('m7');
$m8 = getParam('m8');
$m9 = getParam('m9');
$m10 = getParam('m10');
$m11 = getParam('m11');
$m12 = getParam('m12');


$years = array(array('2010', '2010'), array('2011', '2011'), array('2012', '2012'), array('2013', '2013'), array('2014', '2014'), array('2015', '2015'));
$accounts = rs2array(query("SELECT GL_ACCOUNT_ID,CONCAT(GL_ACCOUNT_NAME,' ','(',GL_ACCOUNT_CODE,')') FROM GL_ACCOUNT"));
$costcenters = rs2array(query("SELECT COST_CENTER_ID,COST_CENTER_NAME FROM cost_center"));
$this_year = date('Y');



$costcenter_id = getParam('costcenter_id');
$ac_no = getParam('ac_no');
$year_of = getParam('year_of');



if (isSave()) {
    $CblBudgetID = NextId('cbl_budget', 'CBL_BUDGET_ID');
    $InsertMainSQL = "INSERT INTO cbl_budget ( CBL_BUDGET_ID, COSTCENTER_ID, GL_ACCOUNT_ID, BUDGET_YEAR, BUDGET_COMMENT, CREATED_BY, CREATED_DATE) VALUES
        ('$CblBudgetID', '$costcenter_id', '$ac_no', '$year_of', '$comments', '$user_name', now())";
    sql($InsertMainSQL);
    $DetailsID = NextId('cbl_budget_details', 'CBL_BUDGET_DETAILS_ID');
    $InsertDetailsSQL = "INSERT INTO cbl_budget_details (CBL_BUDGET_DETAILS_ID, CBL_BUDGET_ID, JAN, FEB, MAR, APR, MAY, JUN,JUL, AUG, SEP,
            OCTO,NOV,DECE) VALUES ('$DetailsID','$CblBudgetID','$m1', '$m2', '$m3', '$m4', '$m5', '$m6', '$m7', '$m8', '$m9', '$m10', '$m11', '$m12' )";
    sql($InsertDetailsSQL);
}



//include("../body/header.php");
?>
<script language="javascript">
    function numbersonly(e, decimal) {
        var key;
        var keychar;

        if (window.event) {
            key = window.event.keyCode;
        }
        else if (e) {
            key = e.which;
        }
        else {
            return true;
        }
        keychar = String.fromCharCode(key);

        if ((key == null) || (key == 0) || (key == 8) || (key == 9) || (key == 13) || (key == 27)) {
            return true;
        }
        else if ((("0123456789").indexOf(keychar) > -1)) {
            return true;
        }
        else if (decimal && (keychar == ".")) {
            return true;
        }
        else
            return false;
    }


</script>
<div Title='Requisition List' class="easyui-panel" style="height:1000px;" >

    <a href="CBLBudgetList.php" class="button">List Budget</a>

    <form action="" method='POST' class="form">
        <input type='hidden' name='costcenterid' value='<?php //echo $row->id;  ?>'/>
        <table class="table">
            <tr>
                <th colspan="5">Create/ Edit Budget </th>
            </tr>

            <tr>
                <td><?php etr("Cost Center:") ?>
                </td>
                <td colspan="3"><?php comboBox("costcenter_id", $costcenters, $var->costcenter_id, TRUE, 'required'); ?></td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>Account No.</td>
                <td colspan=3><?php comboBox("ac_no", $accounts, $var->ac_no, TRUE, 'required'); ?>
                </td>
                <td>&nbsp;</td>
            </tr>

            <tr>
                <td>Budget Year:</td>
                <td><?php comboBox("year_of", $years, $this_year, TRUE, 'required') ?></td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>January :</td><td> 
                    <input type="text" name="m1" id="m1" size=15 value=<?php echo $var->m1; ?> ></td>
                <td>February :</td>
                <td><input type="text" name="m2" id="m2" size="15" value=<?php echo $var->m2; ?> ></td>

                <td>&nbsp;</td>
            </tr>

            <tr>
                <td>March :</td>
                <td><input type="text" name="m3" id="m3" size="15" value=<?php echo $var->m3; ?>  ></td>
                <td>April :</td>
                <td><input type="text" name="m4" id="m4" size="15" value=<?php echo $var->m4; ?>  ></td>
                <td>&nbsp;</td>
            </tr>

            <tr>
                <td>May :</td>
                <td><input type="text" name="m5" id="m5" size="15"  value=<?php echo $var->m5; ?> ></td>

                <td>June :</td>
                <td><input type="text" name="m6" id="m6" size="15"  value=<?php echo $var->m6; ?> ></td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>July :</td>
                <td><input type="text" name="m7" id="m7" size="15"  value=<?php echo $var->m7; ?> ></td>
                <td>August :</td>
                <td><input type="text" name="m8" id="m8" size="15"  value=<?php echo $var->m8; ?> ></td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>September :</td>
                <td><input type="text" name="m9" id="m9" size="15"  value=<?php echo $var->m9; ?> ></td>
                <td>October :</td>
                <td><input type="text" name="m10" id="m10" size="15"  value=<?php echo $var->m10; ?> ></td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>November :</td>
                <td><input type="text" name="m11" id="m11" size="15"  value=<?php echo $var->m11; ?> ></td>

                <td>December :</td>
                <td><input type="text" name="m12" id="m12" size="15"  value=<?php echo $var->m12; ?> ></td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>Comments :</td>
                <td colspan="3">
                    <textarea name="comments" id="textarea" cols="55" rows="2"><?php echo $var->comments; ?></textarea></td>
                <td>&nbsp;</td>
            </tr>
        </table>
        <input type="submit" class="button" name="save" value="Save"/>
        <input type="hidden" name="search_id" value="<?php echo $var->sl_no ?>">

    </form>
</div>

<?php include("../body/footer.php"); ?>