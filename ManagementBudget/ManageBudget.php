<?php
include '../lib/DbManager.php';
include("../body/header.php");
//include '../lib/pagination.php';

$year_list = array(array('2010', '2010'), array('2011', '2011'), array('2012', '2012'), array('2013', '2013'), array('2014', '2014'), array('2015', '2015'));
$CapexOpexType = rs2array(query("SELECT PRODUCT_GROUP_ID, GROUP_NAME FROM product_group"));
$year = getParam('year');
$year = $year == '' ? date('Y') : $year;
$CapexOpex = getParam('CapexOpex');




//$page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);
//$limit = (int) (!isset($_GET["limit"]) ? 25 : $_GET["limit"]);
//$startpoint = ($page * $limit) - $limit;
//$total = findValue("SELECT COUNT(*) FROM gl_account");



$DesignationSQL = "SELECT DESIGNATION_ID, DESIGNATION_NAME FROM designation
WHERE ISAPPROVAL=1";


$ColumnNo = findValue("SELECT COUNT(DESIGNATION_ID) FROM designation
WHERE ISAPPROVAL=1");

//$CCSQL = "SELECT GL_ACCOUNT_ID,GL_ACCOUNT_NAME FROM gl_account LIMIT $startpoint,$limit";
$CCSQL = "SELECT GL_ACCOUNT_ID,GL_ACCOUNT_NAME FROM gl_account LIMIT 0,10";
$CCResult = query($CCSQL);
?>


<script>
    $(document).ready(function() {
        if ($('#CapexOpexID').val() === 2)
        {
            $('.div_cap_op').hide();
            $('.non_procure').hide();
        }
        $('.textBox').change(function() {

            var type = $('#CapexOpexID option:selected').val();

            if (type === '') {
                alert("Select Capex/Opex First");
                return;
            }

            var packagemain = {
                "amount_value": "",
                "designation": "",
                "gl_account": "",
                "proc_type": "",
                "year": ""
            };

            //console.log($(this).val()+' '+$(this).attr('sss')+' '+$(this).attr('id'));

            packagemain.amount_value = $(this).val();
            packagemain.designation = $(this).attr('designation');
            packagemain.gl_account = $(this).attr('account');
            packagemain.proc_type = $(this).attr('proc_type');
            packagemain.year = $('#yearID').val();
            packagemain.capex_opex_type = $('#CapexOpexID').val();
            //packagemain.year=$("#date").val(); 

            var jsonstr = JSON.stringify(packagemain);
            //alert(jsonstr);


            $.ajax({
                url: "AjaxManageBudget.php",
                data: "data=" + jsonstr,
                type: "GET",
                contentType: "application/json",
                dataType: "text",
                success: function(data) {
                    $("#event_list").html(data);
                }
            });

        });

        $('#yearID').change(function() {
            this.form.submit();
        });

        $('#CapexOpexID').change(function() {
            this.form.submit();

        });



    });
</script>

<div id="event_list"></div>
<div Title='Manage Budget' class="easyui-panel" style="height:1000px;" >
    <form method="POST" class="form">
        <table>
            <tr>
                <td width="100">Year:</td>
                <td><?php comboBox('year', $year_list, $year, FALSE, 'required'); ?></td>
                <td width="100">Capex/Opex:</td>
                <td><?php comboBox('CapexOpex', $CapexOpexType, $CapexOpex, TRUE); ?></td>
            <input type="hidden" name="bud_year" value="<?php echo $year; ?>">
            <input type="hidden" name="cap_op" value="<?php echo $CapexOpex; ?>">
            </tr>
        </table>


        <h1 align="center">Expense Account wise Management Budget</h1>
        <br />
        <?php //table_top($total, $limit); ?>
        <table class="ui-state-default">
            <thead>
            <th width="30">S/N</th>
            <th>GL ACCOUNT</th>
            <?php
            $DesignationResult = query($DesignationSQL);
            while ($HeadRow = fetch_object($DesignationResult)) {
                ?>
                <th  width="15"><?php echo $HeadRow->DESIGNATION_NAME; ?></th>
                <?php
            }
            ?>    
            </thead>
            <tbody>
                <?php while ($Row = fetch_object($CCResult)) { ?>
                    <tr>
                        <td><?php echo $Row->GL_ACCOUNT_ID; ?></td>
                        <td>
                            <?php echo $Row->GL_ACCOUNT_NAME; ?>
                            <div class="float-right div_cap_op" style="text-align: right">
                                Procure:<br/>
                                Non Procure:
                            </div>
                        </td>
                        <?php
                        $Head1 = query($DesignationSQL);
                        while ($HeadRowNew = fetch_object($Head1)) {

                            $ProcureSQL = "SELECT amount AS 'procure',  (
                            SELECT amount FROM delegation_authority 
                            WHERE gl_account_id='$Row->GL_ACCOUNT_ID' 
                            AND designation_id='$HeadRowNew->DESIGNATION_ID' AND year='$year' 
                            AND op_cap_type='$CapexOpex' AND EXPENSE_TYPE_ID='2'
                            ) AS nonProcure
                            FROM delegation_authority 
                            WHERE gl_account_id='$Row->GL_ACCOUNT_ID' 
                            AND designation_id='$HeadRowNew->DESIGNATION_ID' AND year='$year' 
                            AND op_cap_type='$CapexOpex' AND EXPENSE_TYPE_ID='1'";
                            $Amount = find($ProcureSQL);
                            ?>
                            <td>
                                <input size="8" type="text" designation="<?php echo $HeadRowNew->DESIGNATION_ID; ?>" account="<?php echo $Row->GL_ACCOUNT_ID; ?>" proc_type="1" name="txtbx[<?php echo $Row->GL_ACCOUNT_ID; ?>][<?php echo $HeadRowNew->DESIGNATION_ID; ?>]" class="textBox" value="<?php echo $Amount->procure; ?>" style="margin-bottom: 2px;"/>
                                <input size="8" type="text" designation="<?php echo $HeadRowNew->DESIGNATION_ID; ?>" account="<?php echo $Row->GL_ACCOUNT_ID; ?>" proc_type="2" name="txtbx_non_proc[<?php echo $Row->GL_ACCOUNT_ID; ?>][<?php echo $HeadRowNew->DESIGNATION_ID; ?>]" class="textBox non_procure" value="<?php echo $Amount->nonProcure; ?>" />
                            </td>
                            <?php
                        }
                        ?>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </form>
</div>
<?php
//pagination($total, $page, '?', $limit);
?>
<?php include '../body/footer.php'; ?>