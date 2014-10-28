<?php
include_once '../lib/DbManager.php';

$object_name = 'product_brand';
$object_id = strtoupper($object_name) . '_ID';
include '../body/header.php';


include '../lib/master_page.php';


//include_once '../body/body_header.php';
?>
<br/><br/>
<input type="hidden" name="object_name" id="object_name" value="<?php echo $object_name; ?>"/>
<input type="hidden" name="object_id" id="object_id" value="<?php echo $object_id; ?>"/>
<script type="text/javascript" src="include.js"></script>

<div class="easyui-layout" style="margin: auto; height:550px;">  
    <div data-options="region:'center'" Title='Profuct Brand List' style="background-color:white; "> 
        <table class="" id="dataGrid" data-options="fit:true,fitColumns:true"></table> 
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