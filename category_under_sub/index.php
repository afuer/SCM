<?php
include_once '../lib/DbManager.php';

$object_name = 'category_sub_under';
$object_id = strtoupper($object_name) . '_ID';


include '../lib/master_page.php';


include '../body/header.php';


?>
<br/><br/>
<input type="hidden" name="object_name" id="object_name" value="<?php echo $object_name; ?>"/>
<input type="hidden" name="object_id" id="object_id" value="<?php echo $object_id; ?>"/>
<script type="text/javascript" src="include.js"></script>

<div class="easyui-layout" style="width:100%; height:700px;">  

    <div data-options="region:'east', split:true, collapsed:true" title="East" style="width:250px;">  
        <ul class="easyui-tree" data-options="url:'', animate:true, dnd:true"></ul>  
    </div> 

    <div data-options="region:'west',split:true, collapsed:true" title="West" style="width:200px;">  
        <div class="easyui-accordion" data-options="fit:true,border:false">  
            <div title="Title1" style="padding:10px;">  
                content1  
            </div>  
            <div title="Title2" data-options="selected:true" style="padding:10px;">  
                content2  
            </div>  
            <div title="Title3" style="padding:10px">  
                content3  
            </div>  
        </div>  
    </div>

    <div data-options="region:'center'">  
        <div class="easyui-accordion" data-options="fit:true,border:false,plain:true">  
            <div title="Under Category Sub List">  
                <table class="" id="dataGrid" data-options="fit:true,fitColumns:true"></table> 
            </div>  

        </div>  
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