<?php
include '../lib/DbManager.php';
include '../body/header.php';

include 'menu.php';

$menu = new menu();
$user_level_list = $menu->getDataUserLevel() ;
?>
<br/>


<div class="easyui-layout" style="width:100%; height:700px">   
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


            <div title="User Level List">
   
    <table class="ui-state-default" width="100%">
        <thead >
        <th Width="20">SL</th>
        <th>User Type</th>
        <th width="80">Action</th>
        </thead>
        <tbody>
            <?php while ($row = fetch_object($user_level_list)) {
                ?>
                <tr>
                    <td><?php echo++$SL; ?>.</td>
                    <td><?php echo $row->USER_LEVEL_NAME; ?></td>
                    <td><a  href="sysmenu_edit.php?search_id=<?php echo $row->USER_LEVEL_ID; ?>"> Edit </a></td>
                </tr>
            <?php }
            ?>

        </tbody>
    </table>



<?php include '../body/footer.php'; ?>


            </div>  

        </div>  
    </div>  
</div>

