<?php
include '../lib/DbManager.php';
include '../body/header.php';

$object_name = 'workflow_group_details';
$object_id = strtoupper($object_name) . '_ID';

//include '../lib/master_page.php';
?>

<input type="hidden" name="object_name" id="object_name" value="<?php echo $object_name; ?>"/>
<input type="hidden" name="object_id" id="object_id" value="<?php echo $object_id; ?>"/>
<script type="text/javascript" src="include_workflow_details.js"></script>


<table class="" id="dataGrid" data-options="fit:true,fitColumns:true"></table> 

<div id="toolbar" style="padding:5px;height:auto">  
    <div id="toolbar">
        <a href="WorkflowGroupDeatilsNew.php?mode=new" class="easyui-linkbutton" iconCls="icon-add" plain="true">Add New</a>
        <a href="#" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="Edit()">Edit</a>
        <a href="#" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="Remove()">Remove</a>
    </div>
</div>


<?php
//include '../lib/master_grid_page.php';
include '../body/footer.php';
?>




