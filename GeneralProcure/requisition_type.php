<?php
include '../lib/DbManager.php';
include("../body/header.php");
?>

<div Title='Requisition Type' class="easyui-panel" style="width:1000px; height:700px;" >

    <form class="form" action="RequisitionNew.php?mode=new" method="GET" >
        <table class="table">
            <tr>
                <td width="120">Req. Process Dept:</td>
                <td>
                    <input type="radio" class="required" onClick="<?php echo onChange(ajax_process_dep); ?>" name="requisition_type_id" value="1"/> Procurement
                    <input type="radio" class="required" onClick="<?php echo onChange(ajax_process_dep); ?>" name="requisition_type_id" value="2"/> IT
                    <input type="radio" class="required" onClick="<?php echo onChange(ajax_process_dep); ?>" name="requisition_type_id" value="3"/> Brand
                    <input type="radio" class="required" onClick="<?php echo onChange(ajax_process_dep); ?>" name="requisition_type_id" value="4"/> GAD
                </td>
            </tr>
            <tr>
                <td width="150">Requisition Type:</td>
                <td id="ajax_process_dep"></td>

            </tr>
            <tr>
                <td><button type="submit" class="button" name="search">Submit</button></td>
                <td></td>
            </tr>
        </table>  
    </form>


</div>


<?php include '../body/footer.php'; ?>
