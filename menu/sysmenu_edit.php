<?php
include '../lib/DbManager.php';
include '../body/header.php';

$db = new DbManager();



$search_id = getParam('search_id');


$user_level_sql = "SELECT ul.USER_LEVEL_ID, MENU_MAIN_ID, MENU_SUB_ID, ul.USER_LEVEL_NAME 
FROM user_level AS ul 
LEFT JOIN master_user AS u ON u.USER_LEVEL_ID=ul.USER_LEVEL_ID 
WHERE ul.USER_LEVEL_ID='$search_id'";

$user_level_sub_id = $db->find($user_level_sql);



$user_level_sub_id_list = ($user_level_sub_id->MENU_SUB_ID != '' ? $user_level_sub_id->MENU_SUB_ID : '0');

$user_level_main_id_list = ($user_level_sub_id->MENU_MAIN_ID != '' ? $user_level_sub_id->MENU_MAIN_ID : '0');


$sysmenu = $db->query("select  sys_menu_id,menu_name FROM sys_menu Where _group = 'main' AND _show = '1' AND SYS_MENU_ID !='1' ");
?>



<?php
if (isSave()) {


    $main_menu = getParam('main_menu');
    $main_menu_id = implode(',', $main_menu);

    $sub_menu = getParam('sub_menu');
    $sub_menu_id = implode(',', $sub_menu);




    $sql_update = "UPDATE user_level set 
        menu_main_id = '$main_menu_id',
        menu_sub_id = '$sub_menu_id' 
        Where USER_LEVEL_ID = '$search_id' ";


    $db->query($sql_update);



    echo " <script>location.replace('sysmenu_edit.php?mode=search&search_id=$search_id');</script>";
}
?>

<style type="text/css">
    .edit-div{margin-top: 50px;}
    ul.sysmenu_edit input { border: 1px solid #8A8575; min-height: 0px; }
    ul.sysmenu_edit{margin-bottom: 10px; list-style: none;}
    ul.sysmenu_edit li{margin-left: 20px; list-style: none; font-weight: bold; font-size: 12pt;}
    ul.sysmenu_edit li ul {margin-left: 20px; list-style: none;}
    ul.sysmenu_edit li ul li{margin-left: 20px; list-style: none; font-weight: normal; font-size: 10pt;}
</style>
<div class="easyui-layout" style="width:950px; margin: auto; height:600px;">  
    <div data-options="region:'center'" Title='Menu List' style="padding: 10px 10px; background-color:white; "> 


        <div class="edit-div">
            <h1 > User Level Edit For - <span style="color:#086606;"><?php echo $user_level_sub_id->USER_LEVEL_NAME ?> </span></h1>
            <br/>
            <form action="" method="POST" name='SysEdit'>
                <ul class="sysmenu_edit">

                    <?php
                    while ($sys = fetch_object($sysmenu)) {
                        $sqlChk = "SELECT menu_main_id FROM user_level WHERE USER_LEVEL_ID = '$search_id' AND '$sys->sys_menu_id' in($user_level_main_id_list)";

                        $user_level = $db->findValue($sqlChk);

                        if ($user_level != '') {
                            $checked = 'checked="checked"';
                        } else {
                            $checked = '';
                        }
                        ?> 
                        <li><input type="checkbox" name="main_menu[]"  value="<?php echo $sys->sys_menu_id; ?>" <?php echo $checked; ?> />
                            <?php echo $sys->menu_name; ?> 


                            <ul>
                                <?php
                                $submenu = $db->query("select  sys_menu_id,menu_name FROM sys_menu Where _show = '1' AND _subid ='$sys->sys_menu_id'");

                                while ($sys_sub = fetch_object($submenu)) {
                                    $sqlChk = "SELECT menu_sub_id FROM user_level WHERE USER_LEVEL_ID = '$search_id' AND '$sys_sub->sys_menu_id' in($user_level_sub_id_list)";

                                    $user_level = $db->findValue($sqlChk);

                                    if ($user_level != '') {
                                        $checked = 'checked="checked"';
                                    } else {
                                        $checked = '';
                                    }
                                    ?>
                                    <li> 
                                        <input type="checkbox" name="sub_menu[]"  value="<?php echo $sys_sub->sys_menu_id; ?>" <?php echo $checked; ?> />
                                        <?php echo $sys_sub->menu_name; ?> 

                                        <ul>
                                            <?php
                                            $submenu_sub = $db->query("select  sys_menu_id,menu_name FROM sys_menu Where _show = '1' AND _subid ='$sys_sub->sys_menu_id'");

                                            while ($sys_sub_sub = fetch_object($submenu_sub)) {
                                                $sqlChk = "SELECT menu_sub_id FROM user_level WHERE USER_LEVEL_ID = '$search_id' AND '$sys_sub_sub->sys_menu_id' in($user_level_sub_id_list)";

                                                $user_level = $db->findValue($sqlChk);

                                                if ($user_level != '') {
                                                    $checked = 'checked="checked"';
                                                } else {
                                                    $checked = '';
                                                }
                                                ?>
                                                <li> <input type="checkbox" name="sub_menu[]"  value="<?php echo $sys_sub_sub->sys_menu_id; ?>" <?php echo $checked; ?> >
                                                    <?php
                                                    echo $sys_sub_sub->menu_name;
                                                }
                                                ?>


                                        </ul>

                                    <?php } ?> 

                                </li>
                            </ul>

                        <li>

                            <?php
                        }
                        ?>
                </ul>

                <br/>
                <br/>

                <button type="submit" name="save" value="SaveSysMenu" class="button" onClick="return verify()">Save</button>
            </form>
        </div>
    </div>
</div>

<?php include '../body/footer.php'; ?>



