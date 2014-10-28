<?php
include_once '../lib/DbManager.php';

$object_name = 'category_sub';



$object_id = strtoupper($object_name) . '_ID';
include '../body/header.php';


include '../lib/master_page.php';
?>
<br/><br/>
<input type="hidden" name="object_name" id="object_name" value="<?php echo $object_name; ?>"/>
<input type="hidden" name="object_id" id="object_id" value="<?php echo $object_id; ?>"/>
<script type="text/javascript" src="include.js"></script>

<div class="easyui-layout" style="margin: auto; height:700px;">  
    <div Title='' data-options="region:'center'" style="background-color:white; padding: 10px 10px;"> 
        <table class="" id="dataGrid"></table> 
    </div>  
</div>


<div id="toolbar" style="padding:5px;height:auto">  
    <div id="toolbar">
        <a href="#" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="AddNew()">Add New</a>
        <a href="#" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="Edit()">Edit</a>
        <a href="#" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="Remove()">Remove</a>
    </div>

</div>

<?php
include '../body/footer.php';
?>