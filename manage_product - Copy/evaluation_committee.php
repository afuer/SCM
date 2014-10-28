<?php
include '../lib/DbManager.php';

include '../body/header.php';

$compersionid = getParam('compersionid');
$save = $_GET['save'];
if (isset($save)) {
    $proposed_by = $_GET['proposed_by'];
    $name_proposed_by = $_GET['name_proposed_by'];
    $designation_proposed_by = $_GET['designation_proposed_by'];

    $reviewd_by = $_GET['reviewd_by'];
    $name_reviewd_by = $_GET['name_reviewd_by'];
    $designation_reviewd_by = $_GET['designation_reviewd_by'];

    $chairman = $_GET['chairman'];
    $name_chairman = $_GET['name_chairman'];
    $designation_chairman = $_GET['designation_chairman'];

    $member_1 = $_GET['member_1'];
    $name_member_1 = $_GET['name_member_1'];
    $designation_member_1 = $_GET['designation_member_1'];

    $member_2 = $_GET['member_2'];
    $name_member_2 = $_GET['name_member_2'];
    $designation_member_2 = $_GET['designation_member_2'];

    $secretary = $_GET['secretary'];
    $name_secretary = $_GET['name_secretary'];
    $designation_secretary = $_GET['designation_secretary'];

    $reviewed2 = $_GET['reviewed2'];
    $name_reviewed2 = $_GET['name_reviewed2'];
    $designation_reviewed2 = $_GET['designation_reviewed2'];

    $recommendation = $_GET['recommendation'];

    $date = date('Y-m-d');

    $sql = "Update price_comparison set
			date                           =  '$date',
			proposed_by                    =  '$proposed_by', 
			proposed_designation           =  '$designation_proposed_by', 
			proposed_name                  =  '$name_proposed_by', 
			reviewed_by                    =  '$reviewd_by', 
			reviewed_designation           =  '$designation_reviewd_by', 
			reviewed_name                  =  '$name_reviewd_by', 
			chairman                       =  '$chairman', 
			chairman_designation           =  '$designation_chairman', 
			chairman_name                  =  '$name_chairman', 
			member1                        =  '$member_1', 
			member1_designation            =  '$designation_member_1', 
			member1_name                   =  '$name_member_1', 
			member2                        =  '$member_2', 
			member2_designation            =  '$designation_member_2', 
			member2_name                   =  '$name_member_2', 
			member_secretary               =  '$secretary',   
			secretary_designation          =  '$designation_secretary', 
			member_secretary_name          =  '$name_secretary', 
			procurement_member             =  '$reviewed2', 
			procurement_member_designation =  '$designation_reviewed2', 
			procurement_member_name        =  '$name_reviewed2', 
			recommendation                 =  '$recommendation'
			where comparisonid = '$compersionid' ";

    sql($sql);
    echo "<script type='text/javascript'>window.opener.parent.location.reload()</script>";
    echo "<script type='text/javascript'>window.close()</script>";

    //print_r ($_GET);
}

$rec_com = find("select  
			date, 				         
			proposed_by,                
			proposed_designation,       
			proposed_name,             
			reviewed_by,                  
			reviewed_designation,          
			reviewed_name,             
			chairman,                  
			chairman_designation,     
			chairman_name,              
			member1,                     
			member1_designation,         
			member1_name,                  
			member2,                       
			member2_designation,        
			member2_name,                
			member_secretary,           
			secretary_designation,        
			member_secretary_name,       
			procurement_member,          
			procurement_member_designation, 
			procurement_member_name,      
			recommendation              
			from price_comparison
			where comparisonid = '$compersionid' ");
$proposed_by = $rec_com->proposed_name;
$proposed_designation = $rec_com->proposed_designation;

$proposed_card = findValue("select emp.CARD_NO from employee emp
left join designation deg on emp.DESIGNATION_ID=deg.DESIGNATION_ID
where emp.FIRST_NAME='$proposed_by' and deg.DESIGNATION_ID = '$proposed_designation'");

$reviewed_by = $rec_com->reviewed_name;
$reviewed_designation = $rec_com->reviewed_designation;

$reviewed_card = findValue("select emp.CARD_NO from employee emp
left join designation deg on emp.DESIGNATION_ID=deg.DESIGNATION_ID
where emp.FIRST_NAME='$reviewed_by' and deg.DESIGNATION_ID = '$reviewed_designation'");

$chairman = $rec_com->chairman_name;
$chairman_designation = $rec_com->chairman_designation;


$chairman_card = findValue("select emp.CARD_NO from employee emp
left join designation deg on emp.DESIGNATION_ID=deg.DESIGNATION_ID
where emp.FIRST_NAME='$chairman' and deg.DESIGNATION_ID = '$chairman_designation'");

$member1 = $rec_com->member1_name;
$member1_designation = $rec_com->member1_designation;

$member1_card = findValue("select emp.CARD_NO from employee emp
left join designation deg on emp.DESIGNATION_ID=deg.DESIGNATION_ID
where emp.FIRST_NAME='$member1' and deg.DESIGNATION_ID = '$member1_designation'");

$member2 = $rec_com->member2_name;
$member2_designation = $rec_com->member2_designation;

$member2_card = findValue("select emp.CARD_NO from employee emp
left join designation deg on emp.DESIGNATION_ID=deg.DESIGNATION_ID
where emp.FIRST_NAME='$member2' and deg.DESIGNATION_ID = '$member2_designation'");

$member_secretary = $rec_com->member_secretary_name;
$secretary_designation = $rec_com->secretary_designation;

$secretary_card = findValue("select emp.CARD_NO from employee emp
left join designation deg on emp.DESIGNATION_ID=deg.DESIGNATION_ID
where emp.FIRST_NAME='$member_secretary' and deg.DESIGNATION_ID = '$secretary_designation'");

$procurement_member = $rec_com->procurement_member_name;
$procur_member_desig = $rec_com->procurement_member_designation;

$procurement_card = findValue("select emp.CARD_NO from employee emp
left join designation deg on emp.DESIGNATION_ID=deg.DESIGNATION_ID
where emp.FIRST_NAME='$procurement_member' and deg.DESIGNATION_ID = '$procur_member_desig'");

$recommendation = $rec_com->recommendation;
?>


<script type="text/javascript" src="js/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="js/ckeditor/config.js"></script>



<form name="frm_committee" method="get">
    <fieldset><legend>Proposed Committee</legend>
        <table width="100%" border="0" id="hor-minimalist-b">
            <tr>
                <td width="17%">Proposed By </td>
                <td width="1%">&nbsp;</td>
                <td width="21%">

                    <input type="text" name="proposed_by" value="<?php echo $proposed_card; ?>"  onBlur="ajaxLoader('ajax_identity.php?val=' + this.value + '&name=proposed_by', 'ajax_proposed', '<left><img src=images/ajaxLoader.gif></left>')"></td>
                <td width="61%" id="ajax_proposed">
                    <input type="hidden" name="name_proposed_by" value="<?php echo $proposed_by; ?>" />
                    <input type="hidden" name="designation_proposed_by" value="<?php echo $proposed_designation; ?>" />
                    <?php echo $proposed_by; ?></td>
            </tr>
            <tr>
                <td>Reviewed By </td>
                <td>&nbsp;</td>
                <td><input type="text" name="reviewd_by" value="<?php echo $reviewed_card; ?>" onBlur="ajaxLoader('ajax_identity.php?val=' + this.value + '&name=reviewd_by', 'ajax_reviewd', '<left><img src=images/ajaxLoader.gif></left>')"></td>
                <td id="ajax_reviewd">
                    <input type="hidden" name="name_reviewd_by" value="<?php echo $reviewed_by; ?>" />
                    <input type="hidden" name="designation_reviewd_by" value="<?php echo $reviewed_designation; ?>" />
                    <?php echo $reviewed_by; ?></td>
            </tr>
        </table>
    </fieldset>
    <fieldset><legend>Procurement Committee</legend>
        <table width="100%" border="0">
            <tr>
                <td width="17%">Chairman</td>
                <td width="1%">&nbsp;</td>
                <td width="21%"><input type="text" name="chairman" value="<?php echo $chairman_card; ?>"onBlur="ajaxLoader('ajax_identity.php?val=' + this.value + '&name=chairman', 'ajax_chairman', '<left><img src=../public/images/ajaxLoader.gif></left>')"></td>
                <td width="61%" id="ajax_chairman">
                    <input type="hidden" name="name_chairman" value="<?php echo $chairman; ?>" />
                    <input type="hidden" name="designation_chairman" value="<?php echo $chairman_designation; ?>" />
                    <?php echo $chairman; ?></td>
            </tr>
            <tr>
                <td>Member</td>
                <td>&nbsp;</td>
                <td><input type="text" name="member_1" value="<?php echo $member1_card; ?>"onBlur="ajaxLoader('ajax_identity.php?val=' + this.value + '&name=member_1', 'ajax_member_1', '<left><img src=images/ajaxLoader.gif></left>')"></td>
                <td id="ajax_member_1">
                    <input type="hidden" name="name_member_1" value="<?php echo $member1; ?>" />
                    <input type="hidden" name="designation_member_1" value="<?php echo $member1_designation; ?>" />
                    <?php echo $member1; ?></td>
            </tr>
            <tr>
                <td>Member</td>
                <td>&nbsp;</td>
                <td><input type="text" name="member_2" value="<?php echo $member2_card; ?>"onBlur="ajaxLoader('ajax_identity.php?val=' + this.value + '&name=member_2', 'ajax_member_2', '<left><img src=images/ajaxLoader.gif></left>')"></td>
                <td id="ajax_member_2">
                    <input type="hidden" name="name_member_2" value="<?php echo $member2; ?>" />
                    <input type="hidden" name="designation_member_2" value="<?php echo $member2_designation; ?>" />
                    <?php echo $member2; ?></td>
            </tr>
            <tr>
                <td>Member Secretary</td>
                <td>&nbsp;</td>
                <td><input type="text" name="secretary" value="<?php echo $secretary_card; ?>"onBlur="ajaxLoader('ajax_identity.php?val=' + this.value + '&name=secretary', 'ajax_secretary', '<left><img src=images/ajaxLoader.gif></left>')"></td>
                <td id="ajax_secretary">
                    <input type="hidden" name="name_secretary" value="<?php echo $member_secretary; ?>" />
                    <input type="hidden" name="designation_secretary" value="<?php echo $secretary_designation; ?>" />
                    <?php echo $member_secretary; ?></td>
            </tr>
            <tr>
                <td>Reviewd By </td>
                <td>&nbsp;</td>
                <td><input type="text" name="reviewed2" value="<?php echo $procurement_card; ?>"onBlur="ajaxLoader('ajax_identity.php?val=' + this.value + '&name=reviewed2', 'ajax_reviewed2', '<left><img src=images/ajaxLoader.gif></left>')"></td>
                <td id="ajax_reviewed2">
                    <input type="hidden" name="name_reviewed2" value="<?php echo $procurement_member; ?>" />
                    <input type="hidden" name="designation_reviewed2" value="<?php echo $procur_member_desig; ?>" />
                    <?php echo $procurement_member; ?></td>
            </tr>
            <tr>
                <td colspan="3">Recommendation</td>
                <td id="ajax_reviewed2"><?php echo $chairman_name; ?></td>
            </tr>
            <tr>
                <td colspan="4">
                    <textarea  id="recommendation" name="recommendation"  rows="4" cols="62" ><?php echo $recommendation; ?></textarea>
                    <script type="text/javascript">
                        CKEDITOR.replace('recommendation');
                    </script>
                </td>
            </tr>
        </table>
    </fieldset>
    <br/>
    <button type="submit" name="save" class="button" value="">Save</button>

    <input type="hidden" value="<?php echo $_REQUEST["productid"]; ?>" name="productid"/>
    <input type="hidden" value="<?php echo $_REQUEST["quantity"]; ?>" name="quantity"/>
    <input type="hidden" value="<?php echo $_REQUEST["compersionid"]; ?>" name="compersionid"/>
    <input type="hidden" value="<?php echo $mode; ?>" name="mode"/>
</form>

<?php include '../body/footer.php'; ?>