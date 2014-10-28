<?php
include_once '../lib/DbManager.php';


$savebutton = "&nbsp;&nbsp;Save&nbsp;&nbsp;";
$costcenter_code = getParam("costcenter_code");
$budget_year = getParam("budget_year");
$account_code = getParam("account_code");

$comments = getParam("comments");



if (isSave()) {

    $value = getParam("value");

    if (isNew()) {
        $sql = "insert into cost_center_code";
        $sql .= " (name, code, account_typeid, _show) ";
        $sql .= " values ('$description', '$centercode', '$account_typeid', 1) ";
        sql($sql);
        $costcenterid = mysql_insert_id();
    } else {
        $sql = "update cost_center_code ";
        $sql .= "set name='$description', account_typeid=$account_typeid, code='$centercode' ";
        $sql .= "where id=$costcenterid";
        sql($sql);
    }
}
if (!isEmpty($costcenterid)) {
    $sql = "select id, name, account_typeid, code
                 from cost_center_code
                 where id=$costcenterid";
    $d = query($sql);
    $row = fetch_object($d);
    $description = $row->name;
    $centercode = $row->code;
}


$budget_types = rs2array(query("SELECT budget_typeid, 	budget_name FROM `budget_type`"));
$accounts = rs2array(query("SELECT GL_ACCOUNT_ID, GL_ACCOUNT_CODE, GL_ACCOUNT_NAME FROM gl_account"));
$costcenters = rs2array(query("SELECT COST_CENTER_ID, COST_CENTER_CODE, COST_CENTER_NAME FROM cost_center"));
$title = "Add New Account";

include("../body/header.php");
?>
<div Title='New Budget Entry' class="easyui-panel" style="height:1000px;" >
    <form action="" method=POST>

        <input type='hidden' name='costcenterid' value='<?php echo $row->id; ?>'/>
        <table  class="table">
            <tr>
                <td><?php etr("Cost Center") ?>:</td>
                <td colspan="3"><?php comboBox("costcenter_code", $costcenters, $costcenter_code, false) ?></td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>Account No.</td>
                <td colspan=3><?php comboBox("account_code", $accounts, $account_name, false) ?>
                </td>
                <td>&nbsp;</td>
            </tr>

            <tr>
                <td>Budget Year:</td>
                <td><select name="budget_year">
                        <option value="2011">2011</option>
                        <option value="2012" selected >2012</option>
                        <option value="2013">2013</option>
                        <option value="2014">2014</option>
                    </select>
                </td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>January</td><td> 
                    <input type="text" name="m1" id="m1" size=15 /></td>
                <td>February</td>
                <td><input type="text" name="m2" id="m2" size="15" /></td>
                <td>&nbsp;</td>
            </tr>


            <tr>
                <td>March</td>
                <td><input type="text" name="m3" id="m3" size="15" /></td>
                <td>April</td>
                <td><input type="text" name="m4" id="m4" size="15" /></td>
                <td>&nbsp;</td>
            </tr>

            <tr>
                <td>May</td>
                <td><input type="text" name="m5" id="m5" size="15" /></td>
                <td>June</td>
                <td><input type="text" name="m6" id="m6" size="15" /></td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>July</td>
                <td><input type="text" name="m7" id="m7" size="15" /></td>
                <td>August</td>
                <td><input type="text" name="m8" id="m8" size="15" /></td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>September</td>
                <td><input type="text" name="m9" id="m9" size="15" /></td>
                <td>October</td>
                <td><input type="text" name="m10" id="m10" size="15" /></td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>November</td>
                <td><input type="text" name="m11" id="m11" size="15" /></td>
                <td>December</td>
                <td><input type="text" name="m12" id="m12" size="15" /></td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>Comments</td>
                <td colspan="3">
                    <textarea name="comments" id="textarea" cols="55" rows="2"></textarea></td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td><button type="submit" name = "save" class="button"/><span class = "icon plus"></span><?php echo $savebutton; ?></button></td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
        </table>
        </table>
    </form>
</div>
<?php include("../body/footer.php"); ?>