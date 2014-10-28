<?php
include '../lib/DbManager.php';

include("../body/header.php");



$claim_id = getParam("claim_id");


$employeeid = findValue("select employeeid from user where username='$user_name' ");


if (!isset($claim_id)) {
    $exp_no = findValue("select MAX(expence_id)+1 as exp_id from expence_bill");

    sql("insert into expence_bill 
		            (expence_id,  expense_type,employee_id, createdby) 
	           	  values ('$exp_no', 'E', '$employeeid', '$employeeid')");
    $claim_id = insert_id();
}


$poid = getParam('po_no');
$po_no = getParam('po_no');


$reportedid = getParam('reportedid');
$submit = getParam("submit");

$modeid = getParam("modeid");
$instrumentid = getParam('instrumentid');
$referance = getParam('referance');
$supplierid = getParam('supplier_id');
$purpose = getParam('purpose');
$comments = getParam('comments');
$po_date = getParam('po_date');
$po_amount = getParam('po_amount');
$productid = getParam("productid");
$costcenterid = getParam('costcenterid');
//$gl_head        = getParam('gl_head');
$invoice_no = getParam('invoice_no');
$challan_no = getParam('challan_no');
$approved_by = getParam('approved_by');

$invoice_date = getParam('invoice_date');
$credit_account = getParam("credit_account ");
$attachment = getParam('attachment');
$referance = getParam('referance');
$bill_amount = getParam('bill_amount');
$po_amount = getParam('po_amount');
if (isEmpty($invoice_date))
    $invoice_date = date("Y-m-d");


$approved_by = findValue("select employeeid from employee where cardno='$approved_by' ");

if (isset($submit)) {
    if ($_FILES['attachment_file']['name'] != "") {
        $forDB = upload_file($_FILES, 'attachment_file', "../documents/payment_approval_docs/$exp_no");
    }


    $costcenter_id = getParam('costcenter_id');
    $costcenter_amount_persent = getParam('costcenter_amount_persent');

    $costcenter_ids_payment = '';


    //$product_list = implode(',', $chkproduct); echo join(' - ', array_implode($a));
    foreach ($costcenter_id as $key => $value) {
        if ($value > 0) {
            $costcenter_ids_payment .= $value . " ";
            //echo $value; echo "<br>";
        }
    }

    $costcenter_ids_payment = trim($costcenter_ids_payment);
    // echo "$a"; echo "<br>";

    $costcenter_ids_payment = str_replace(" ", ",", $costcenter_ids_payment);


    $costcenter_amounts_payment = '';


    foreach ($costcenter_amount_persent as $key => $value) {
        if ($value > 0) {
            $costcenter_amounts_payment .= $value . " ";
            // echo $value; echo "<br>";
        }
    }
    $costcenter_amounts_payment = trim($costcenter_amounts_payment);
    $costcenter_amounts_payment = str_replace(" ", ",", $costcenter_amounts_payment);

    // echo   $approved_by_emp_id=findValue("SELECT employeeid  FROM employee where cardno=$approved_by");


    sql("UPDATE  `expence_bill` SET `costcenterid` = '" . $costcenter_ids_payment . "',
                      `costcenter_amounts_persent` = '" . $costcenter_amounts_payment . "',
                        modeid= '$modeid',
                        process_by ='$user',
                      	approved_by='$approved_by',
                      `instrumentid` = '$instrumentid',
                      `beneficiary_id` = '$supplierid',
                       challan_no='$challan_no',
                      `referance` = '$referance',
                      `comments` = '$comments',
                       purpose ='$purpose',
                      `attachment` = '$forDB',
                      `approval_status` = '0',
                      submit_to_recommend= '$reportedid',
                      `status` = '1'
                       WHERE `expence_id` ='$claim_id'");



    $expanse_details = "insert into expence_bill_details 
			 (expence_id, po_no,  po_date, po_amount, invoice_no, invoice_date, 	referance,	quantity,unite_price, amount ) values (
			 '$claim_id',	'$po_no', '$po_date','$po_amount', '$invoice_no','$invoice_date','$referance', '1', '$bill_amount','$bill_amount') ";
    sql($expanse_details);
    ?>
    <script type="text/javascript">    	 
        window.location = "expense_list_general.php"     
    </script>

    <?php
}
?>

<?php
$modeids = rs2array(query("select modeid, mode_name from payment_mode where _show=1"));
$instrumentids = rs2array(query("select instrumentid, instrument_name from security_instrument where _show=1"));
$costcenterids = rs2array(query("select code,code, name from cost_center_code"));
$suppliers = rs2array(query("SELECT  supplierid,  name FROM  supplier ORDER BY name"));
$po_no = $rec->po_no;

$row = find("select po.discount, po.vat,
			sum(poi.quantity*poi.unitprice-((poi.discount/100)*(poi.quantity*poi.unitprice))) as net_value
		  from purchaseorder_item poi
		  left join purchaseorder po on po.poid=poi.poid
		   where po.poid='$poid' group by po.poid");

$net_value = $row->net_value;
$total_discount = ($row->discount / 100) * $net_value;
$discount_less_amount = $net_value - $total_discount;
$total_vat = ($row->vat / 100) * $discount_less_amount;
$sub_total = ($net_value + $total_vat) - $total_discount;
$paid_amount = findValue("select sum(amount) as amount from expence_bill_details where po_no='$po_no'");

?>
<style>
    .odd
    {
        background-color:#E9E9E9;

    }
</style>

<script type="text/javascript">

    function dropdown(sel){
        if(sel.options.selectedIndex == 0){
            alert('Please choose an option');
            return false;
        }
        else if(sel.options.selectedIndex == 1){
            document.getElementById('showhidediv').style.display = 'none';
        }
        else{    
            document.getElementById('showhidediv').style.display = 'block';
        }
    }

    $(document).ready(function(){
        //alert("ffff");
        $(".hidden_document__upload").hide();
	   
        $("#add_more_document").click(function(){
            var cnt =0;
            $(".hidden_document__upload").each(function(){
                //alert(this);
                if(cnt==0) {
                    $(this).removeClass('hidden_document__upload').show();				   
                } 
                cnt++;
            });
        });
	   
        //loan
        $(".hidden_document_loan").hide();
	   
        $("#add_more_loan").click(function(){
            var cnt =0;
            $(".hidden_document_loan").each(function(){
                //alert(this);
                if(cnt==0) {
                    $(this).removeClass('hidden_document_loan').show();				   
                } 
                cnt++;
            });
        });
	   
	   
        // 
        $("#lawyer_firmidID").change(function(){
            $.get('ajax_response.php?id=lawyer_firmidID&val='+this.value, function(data) {
                $('#lawyer_idID').html(data);
            });
        });
		
        // 
	   
        $(".hidden_lawyer_tr").hide();
        $("#AddlawyerButtonID").click(function(){
            var cnt =0;
            $(".hidden_lawyer_tr").each(function(){
                if(cnt==0) {
                    $(this).removeClass('hidden_lawyer_tr').show();				   
                } 
                cnt++;

            });
        });

          
          

        $(".checkfiletypes").change(function(){
            var ext = $(this).val().split('.').pop().toLowerCase();
            if($.inArray(ext, ['doc','pdf','jpg','jpeg','gif','png']) == -1) {
                alert('invalid extension!');
                $(this).val('');
            }
        });
              
              
              
        $(".details_of_security").hide();
              
              
        $(".securitycombo").change(function(){
            if($(this).val()=='1')
                $($(this).attr('rel')).show();     
            if($(this).val()=='0')
                $($(this).attr('rel')).hide();      
        });
              
        $(".hidden_security_tr").hide();
              
        $("#security_add_button").click(function(){
            var cnt =0;
            $(".hidden_security_tr").each(function(){
                if(cnt==0) {
                    $(this).removeClass('hidden_security_tr').show();				   
                } 
                if(cnt==1) {
                    $(this).removeClass('hidden_security_tr');				   
                } 
                cnt++;

            });    
        });
             
        $("#ulis_nameID").blur(function(){
            $("#loading_message").html("Loading...").show();
            $.get("ajax.php?module=ulis&action=checkexits&val="+$(this).val(),function(data){
                $("#loading_message").hide();      
                //alert(data);
                if(data=='0')
                    $("#loading_message").html("Already Exits").show().addClass("error");
               
            });               
        });   
    });

</script> 
<script>
    function verify()
    {
        if(document.expanse_form.modeid.selectedIndex==0)
        {
            alert('Please select a payment mode type');
            document.expanse_form.modeid.focus();
            return false;
        }
        if(document.expanse_form.invoice_no.value=='')
        {
            alert('Please Input a invoice no');
            document.expanse_form.invoice_no.focus();
            return false;
        }
        if(document.expanse_form.is_duplicate.value==1)
        {
            alert('This Invoice no is already exist');
            document.expanse_form.is_duplicate.focus();
            return false;
        }
        if(document.expanse_form.bill_amount.value=='')
        {
            alert('Please Input a invoice amount');
            document.expanse_form.bill_amount.focus();
            return false;
        }
        if(document.expanse_form.remain_amount.value < 0)
        {
            alert('Please Input a valid amount');
            document.expanse_form.bill_amount.focus();
            return false;
        }
    }

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

        if ((key==null) || (key==0) || (key==8) ||  (key==9) || (key==13) || (key==27) ) {
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

<h2 style="color:#000066; ">New Payment Approval Note  &nbsp; &nbsp; &nbsp; ||  &nbsp; &nbsp; &nbsp;    <a href='expense_list_general.php'>View all Expense bills</a></h2><br /><br />
<div id="sub_menu">
    <a href="javascript:history.go(-1)" class="button"><span class = "icon leftarrow"></span> Go back </a>
</div>

<form name="expanse_form" action="" method="POST" enctype="multipart/form-data">

    <table id="hor-minimalist-b" align="left">
        <tr>
            <td width=159 height="23"><div align="right">Expense No </div></td>
            <td>&nbsp;</td>
            <td><?php echo $claim_id; ?>
                <input type="hidden" name="claim_id" value="<?php echo $claim_id; ?>" /></td>
        </tr>
     <!--  <tr>
         <td align="right"><div align="right">Cost Center Code</div></td>
         <td>&nbsp;</td>
         <td>
<?php
//	$orderids = explode(",", $rec->orderids);
//combobox('costcenter_id[]', $costcenterids, '', false);
?>	 </td>
       </tr>
        -->
        <tr>
            <td align=right>Cost Center Code1: </td>
            <td>&nbsp;</td>
            <td colspan=2> <?php combobox('costcenter_id[]', $costcenterids, '', true); ?>	</td> 
            <td><input type=text name='costcenter_amount_persent[]'> </td> <td>%</td>
        </tr>

        <tr class="hidden_document__upload">
            <td align=right>Cost Center Code2: </td>
            <td>&nbsp;</td>
            <td colspan=2>  <?php combobox('costcenter_id[]', $costcenterids, '', true); ?></td>  
            <td><input type=text name='costcenter_amount_persent[]'> </td> <td>%</td>
        </tr>
        <tr class="hidden_document__upload">
            <td align=right>Cost Center Code3: </td>
            <td>&nbsp;</td>
            <td colspan=2>  <?php combobox('costcenter_id[]', $costcenterids, '', true); ?></td>
            <td> <input type=text name='costcenter_amount_persent[]'> </td><td>%</td>
        </tr>
        <tr class="hidden_document__upload">
            <td align=right>Cost Center Code4: </td>
            <td>&nbsp;</td>
            <td colspan=2> <?php combobox('costcenter_id[]', $costcenterids, '', true); ?></td>  
            <td> <input type=text name='costcenter_amount_persent[]'> </td> <td>%</td>
        </tr>
        <tr class="hidden_document__upload">
            <td align=right>Cost Center Code5: </td>
            <td>&nbsp;</td>
            <td colspan=2> <?php combobox('costcenter_id[]', $costcenterids, '', true); ?></td>  
            <td> <input type=text name='costcenter_amount_persent[]'> </td> <td>%</td>
        </tr>
        <tr class="hidden_document__upload">
            <td align=right>Cost Center Code6: </td>
            <td>&nbsp;</td>
            <td colspan=2> <?php combobox('costcenter_id[]', $costcenterids, '', true); ?></td>  
            <td> <input type=text name='costcenter_amount_persent[]'> </td> <td>%</td>
        </tr>
        <tr class="hidden_document__upload">
            <td align=right>Cost Center Code7: </td>
            <td>&nbsp;</td>
            <td colspan=2> <?php combobox('costcenter_id[]', $costcenterids, '', true); ?></td>  
            <td> <input type=text name='costcenter_amount_persent[]'> </td><td>%</td>
        </tr>
        <tr class="hidden_document__upload">
            <td align=right>Cost Center Code8: </td>
            <td>&nbsp;</td>
            <td colspan=2> <?php combobox('costcenter_id[]', $costcenterids, '', true); ?></td>  
            <td> <input type=text name='costcenter_amount_persent[]'> </td>  <td>%</td>
        </tr>
        <tr class="hidden_document__upload">
            <td align=right>Cost Center Code9: </td>
            <td>&nbsp;</td>
            <td colspan=2> <?php combobox('costcenter_id[]', $costcenterids, '', true); ?></td>  
            <td> <input type=text name='costcenter_amount_persent[]'> </td><td>%</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td colspan=2><input id="add_more_document" type="button" value="Add More"></td>
        </tr>






        <tr>
            <td><div align="right">Security Instrument</div></td>
            <td>&nbsp;</td>
            <td  colspan=3><?php combobox('instrumentid', $instrumentids, $instrumentid, true); ?></td>
        </tr>
        <tr>
            <td><div align="right">Payment Type</div></td>
            <td>&nbsp;</td>
            <td colspan=3><?php combobox('modeid', $modeids, $modeid, true); ?></td>
        </tr>

        <tr>
            <td><div align="right">Approved by </div></td>
            <td>&nbsp;</td>
            <td  colspan=3>
                <input type="text" name="approved_by"  id="approved_by" size="25" onChange="ajaxLoader('ajax_approved_by_name.php?val='+this.value+'', 'ajax_approved_by', '<left><img src=../images/ajaxLoader.gif></left>');">
                <p id="ajax_approved_by"></td>
        </tr>


        <tr>
            <td><div align="right">Beneficiary</div></td>

            <td>&nbsp;</td>
            <td align="left">
                <select name="supplier_id" onchange="ajaxLoader('ajax_invoice_history_check.php?val='+this.value+'', 'ajax_invoice_history_check', '<left><img src=../images/ajaxLoader.gif></left>');">
                    <option></option>
<?php
$main_query = query("SELECT  supplierid,  name FROM  supplier ORDER BY name");
while ($rec_m = fetch($main_query)) {
    ?>
                        <option value="<?php echo $rec_m->supplierid; ?>"><?php echo $rec_m->name; ?> </option>
<?php } ?>
                </select>



            </td>
        </tr>

        <tr>
            <td valign=top><div align="right" > WO/PO No </div></td>   <td> </td>
            <td align="left" id="ajax_invoice_history_check"><input type="text" name="" value="" size=30></td>

            </td>
        </tr>   


        <tr>
            <td height="23"><div align="right">Challan No/ GRN</div></td>
            <td>&nbsp;</td>
            <td colspan=3><input type="text" name="challan_no" value="" size=30></td>
        </tr>


        <tr>
            <td><div align="right">Purpose</div></td>
            <td>&nbsp;</td>
            <td colspan=3><input type="text"  size="28" name="purpose" value="" /></td>
        </tr>
        <!--
        <tr>
          <td><div align="right">WO/PO No</div></td>
          <td>&nbsp;</td>
          <td colspan=2>
          <input type="text"  size="28" name="po_no" value=""  onblur="ajaxLoader2('ajax_invoice_history_check.php?val='+this.value+'&supplierid=<?php echo $rec->supplierid; ?>','ajax_invoice_history_check','<left><img src=../images/ajaxLoader.gif></left>');" /> 
         </td>  <td id="ajax_invoice_history_check"> 
        </tr>
        
        -->

        <tr>
            <td align="right"><div align="right">Quotation/Bid Ref </div></td>
            <td>&nbsp;</td>
            <td colspan=3><input type="text"  size="28" name="referance" value="" />
            </td>
        </tr>

        <tr>
            <td><div align="right">Invoice Date </div></td>
            <td>&nbsp;</td>
            <td colspan=3><?php dateBox('invoice_date', $invoice_date); ?></td>
        </tr>

        <tr>
            <td><div align="right">Bill/Invoice amount TK</div></td>
            <td>&nbsp;</td>
            <td colspan=3><input type="text"  size="28" name="bill_amount" value="" onKeyPress='return numbersonly(event, false)' /></td>
        </tr>

        <tr>
            <td><div align="right">Note(If any)</div></td>
            <td>&nbsp;</td>
            <td colspan=3><textarea name="comments" cols="32"></textarea></td>
        </tr>
        <tr>
            <td width=159><div align="right">Document</div></td>
            <td>&nbsp;</td>
            <td colspan=3><input type="file" name="attachment_file" /></td>
        </tr>

        <tr>
            <td colspan="3" align="center">This payable amount is  subject to  AIT &  VAT  and  other deductions  (If applicable)</td>
        </tr>

        <tr>

            <td class=label align="right"> Reported Person: </td>
            <td>&nbsp;</td>
            <td><select name="reportedid">
<?php
$sql_rep = query("SELECT
                `user`.username as username,
                employee.cardno as cardno,
                employee.givenname as name,
                `user`.employeeid
                FROM
                `user`
                INNER JOIN employee ON `user`.employeeid = employee.employeeid
                where userlevel=23");

while ($reported = fetch($sql_rep)) {
    ?>
                        <option value="<?php echo $reported->employeeid; ?>"><?php echo $reported->name . '&nbsp;(' . $reported->cardno; ?>)</option>
                    <?php } ?>
                </select></td>


        </tr>


        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        <input type="hidden" name="productid" value="<?php echo $productid; ?>" />
        <td colspan=3>
            <input class=buttons tabindex=16 type=submit value='Submit' name='submit' onclick="return verify()" />	  </td>
        </tr>
    </table>
</form>
<?php include("../body/footer.php"); ?>