<?php
include_once '../lib/DbManager.php';

$object_name = 'grade';
$object_id = strtoupper($object_name) . '_ID';
include '../body/header.php';

$db = new DbManager();
$db->OpenDb();
include '../lib/master_page.php';
$db->CloseDb();

include_once '../body/body_header.php';
?>
<br/><br/>
<script type="text/javascript" src="include.js"></script>
<input type="hidden" name="object_name" id="object_name" value="<?php echo $object_name; ?>"/>
<input type="hidden" name="object_id" id="object_id" value="<?php echo $object_id; ?>"/>

<div class="easyui-layout" style="width:100%; height:400px;">  
    <div title="Search Option" data-options="region:'north',split:true, collapsed:true" title="West" style="height: 200px;">  
        <div class="easyui-accordion" data-options="fit:true,border:false">  
            <table>
                <tr>
                    <td>Product Name: <input type="text" class="" id="searchName" /></td>
                </tr>
            </table>
            <button class="easyui-linkbutton" onclick="onClick('searchName');" iconCls="icon-search">Search</button>
        </div>  
    </div>

    <div data-options="region:'south',split:true" style="height:50px;">Button Part</div>

    <div data-options="region:'east', split:true, collapsed:true" title="East" style="width:250px;">  
        <ul class="easyui-tree" data-options="url:'TreeJson.php', animate:true, dnd:true"></ul>  
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
            <div title="Grade List">  
                <table class="" id="dataGrid" data-options="fit:true,fitColumns:true"></table> 
            </div>  

        </div>  
    </div>  
</div>


<div id="toolbar" style="padding:5px;height:auto">  
    <div id="toolbar">
        <a href="#" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newUser();">Add Grade</a>
        <a href="#" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editUser();">Edit Grade</a>
        <a href="#" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="removeUser();">Remove Grade</a>


    </div>

</div>





<?php
include '../body/footer.php';
?>