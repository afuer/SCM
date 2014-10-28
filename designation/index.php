<?php
include_once '../lib/DbManager.php';

$object_name = 'designation';
$object_id = strtoupper($object_name) . '_ID';
include '../body/header.php';

$db = new DbManager();
//$db->OpenDb();
include '../lib/master_page.php';
//$db->CloseDb();
//include_once '../body/body_header.php';
?>


<input type="hidden" name="object_name" id="object_name" value="<?php echo $object_name; ?>"/>
<input type="hidden" name="object_id" id="object_id" value="<?php echo $object_id; ?>"/>
<script type="text/javascript" src="include.js"></script>

<div Title='Designation' class="easyui-panel" style="height:1000px;" >

    <table title="Designation List" class="" id="dataGrid" data-options="fit:true,fitColumns:true"></table> 
</div>  

<div id="toolbar">  
    <div id="toolbar">
        <a href="#" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="AddNew()">Add New</a>
        <a href="#" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="Edit()">Edit</a>
        <a href="#" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="Remove()">Remove</a>
    </div>
</div>





<?php
include '../body/footer.php';
?>