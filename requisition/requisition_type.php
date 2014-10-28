<?php
include '../lib/DbManager.php';
include("../body/header.php");
?>


<div class="easyui-layout" style="width:100%; height:700px; margin: auto;">  
    <div title="Search Option" data-options="region:'north',split:true, collapsed:true" title="West" style="height: 200px;">  
        <div class="easyui-accordion" data-options="fit:true,border:false">
            DD

        </div>  
    </div>

    <div data-options="region:'south',split:true" style="height:50px;">Button Part</div>

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

    <div Title='Requisition Type' data-options="region:'center',iconCls:'icon-ok'">  



        <form class="form" action="requisition_new.php" method="GET" >
            <table class="table">
                <tr>
                    <td width="120">Requisition Type:</td>
                    <td>
                        <input type="radio" class="required" onClick="<?php echo onChange(ajax_process_dep); ?>" name="requisition_type_id" value="1"  /> Store Requisition
                        <input type="radio" class="required" onClick="<?php echo onChange(ajax_process_dep); ?>" name="requisition_type_id" value="2"  /> Purchase Requisition
                    </td>
                </tr>
                <tr>
                    <td width="150">Req. Process Dept:</td>
                    <td id="ajax_process_dep"></td>

                </tr>
                <tr>
                    <td><button type="submit" class="button" name="search">Submit</button></td>
                    <td></td>
                </tr>
            </table>  
        </form>


    </div>
</div>


<?php include '../body/footer.php'; ?>
