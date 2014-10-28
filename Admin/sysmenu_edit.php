<?php
include_once '../lib/DbManager.php';

$search_id = getParam('search_id');

$user_level_sql = "SELECT ul.LEVEL_ID, MENU_MAIN_ID, MENU_SUB_ID, ul.LEVEL_NAME
FROM user_level AS ul
INNER JOIN master_user AS mu ON mu.USER_LEVEL_ID=ul.LEVEL_ID
WHERE mu.USER_TYPE_ID='$search_id' GROUP BY USER_TYPE_ID";

$user_level_sub_id = find($user_level_sql);

//$emp_row = findValue("select u.user_level_id from user AS u  WHERE u.user_type = '$search_id' GROUP BY user_level_id");
//$user_level_sub_id = find("SELECT menu_main_id,menu_sub_id FROM user_level WHERE level_id = '$emp_row'");

$user_level_sub_id_list = ($user_level_sub_id->MENU_SUB_ID != '' ? $user_level_sub_id->MENU_SUB_ID : '0');

$user_level_main_id_list = ($user_level_sub_id->MENU_MAIN_ID != '' ? $user_level_sub_id->MENU_MAIN_ID : '0');

/*
  $user_level_sql = "SELECT menu_main_id, menu_sub_id
  FROM user_level AS ul
  INNER JOIN `user` AS u ON u.user_level_id=ul.level_id
  WHERE u.user_type='$search_id' GROUP BY user_type";

  $user_level_sub_id = find($user_level_sql);
 */

//$user_level_sub_id_list = $user_level_sub_id->menu_sub_id;
//$user_level_main_id_list = $user_level_sub_id->menu_main_id;

$sysmenu = query("select sys_menu_id,menu_name FROM sys_menu Where _group = 'main' AND _show = '1' ");
?>





<?php include '../body/header.php'; ?>

<?php
if (isSave()) {


    $main_menu = getParam('main_menu');
    $main_menu_id = implode(',', $main_menu);

    $sub_menu = getParam('sub_menu');
    $sub_menu_id = implode(',', $sub_menu);



    if (isset($main_menu)) {

        $sql_update = "UPDATE user_level set 
        menu_main_id = '$main_menu_id',
        menu_sub_id = '$sub_menu_id' 
        Where level_id = '$user_level_sub_id->level_id' ";
        query($sql_update);
    }
    echo " <script>location.replace('sysmenu_edit.php?mode=search&search_id=$search_id');</script>";
}
?>
<script type="text/javascript" src="../public/js/nav.js"></script>
<style type="text/css">
    .edit-div{margin-top: 50px;}
    ul.sysmenu_edit input { border: 1px solid #8A8575; min-height: 0px; }
    ul.sysmenu_edit{margin-bottom: 10px; list-style: none;}
    ul.sysmenu_edit li{margin-left: 20px; list-style: none; font-weight: bold; font-size: 12pt;}
    ul.sysmenu_edit li ul {margin-left: 20px; list-style: none;}
    ul.sysmenu_edit li ul li{margin-left: 20px; list-style: none; font-weight: normal; font-size: 10pt;}



    /* nav menu styles */
    nav ul#nav, ul#nav li { list-style: none; }

    #nav { 
        display: block; 
        width: 280px;
        font-size: 70%;
        font-family: Arial,Tahoma,sans-serif;
    }

    #nav li { }

    #nav > li > a { 
        display: block; 
        padding: 10px 18px;
        font-size: 1.3em;
        font-weight: bold;
        color: #d4d4d4;
        text-decoration: none;
        border-bottom: 1px solid #212121;
        background-color: #343434;
        background: -webkit-gradient(linear, left top, left bottom, from(#343434), to(#292929));
        background: -webkit-linear-gradient(top, #343434, #292929);
        background: -moz-linear-gradient(top, #343434, #292929);
        background: -ms-linear-gradient(top, #343434, #292929);
        background: -o-linear-gradient(top, #343434, #292929);
        background: linear-gradient(top, #343434, #292929);
    }
    #nav > li > a:hover, #nav > li > a.open { 
        color: #e9e9e9;
        border-bottom-color: #384f76;
        background-color: #6985b5;
        background: -webkit-gradient(linear, left top, left bottom, from(#6985b5), to(#456397));
        background: -webkit-linear-gradient(top, #6985b5, #456397);
        background: -moz-linear-gradient(top, #6985b5, #456397);
        background: -ms-linear-gradient(top, #6985b5, #456397);
        background: -o-linear-gradient(top, #6985b5, #456397);
        background: linear-gradient(top, #6985b5, #456397);
    }

    #nav li ul { display: none; background: #4a5b78; }

    #nav li ul li a { 
        display: block; 
        background: none;
        padding: 10px 0px;
        padding-left: 30px;
        font-size: 1.1em;
        text-decoration: none;
        font-weight: bold;
        color: #e3e7f1;
        text-shadow: 1px 1px 0px #000;
    }
    #nav li ul li a:hover {
        background: #394963;
    }

</style>

<div class="edit-div">
    <h1><?php echo $user_level_sub_id->level_name ?> User Level Edit</h1>
    <br/>
    <form action="" method="POST" name='SysEdit'>
        <nav>
            <ul id="nav">
                <?php
                while ($sys = fetch_object($sysmenu)) {
                    $sqlChk = "SELECT menu_main_id FROM user_level WHERE level_id = '$user_level_sub_id->level_id' AND '$sys->sys_menu_id' in($user_level_main_id_list)";
                    $user_level = findValue($sqlChk);
                    if ($user_level != '') {
                        $checked = 'checked="checked"';
                    } else {
                        $checked = '';
                    }
                    ?> 

                    <li>

                        <a href="#"><?php echo $sys->sys_menu_id; ?>
                            <label for="<?php echo $sys->sys_menu_id; ?>">
                                <input type="checkbox" name="main_menu[]" id="<?php echo $sys->sys_menu_id; ?>" value="<?php echo $sys->sys_menu_id; ?>" class="MainMenu" <?php echo $checked; ?> />
                                <?php echo $sys->menu_name; ?>
                            </label>
                        </a> 

                        <ul>
                            <?php
                            $submenu = query("select  sys_menu_id,menu_name FROM sys_menu Where _show = '1' AND _subid ='$sys->sys_menu_id'");
                            while ($sys_sub = fetch_object($submenu)) {
                                $sqlChk = "SELECT menu_sub_id FROM user_level WHERE level_id = '$emp_row' AND '$sys_sub->sys_menu_id' in($user_level_sub_id_list)";
                                $user_level = findValue($sqlChk);
                                if ($user_level != '') {
                                    $checked = 'checked="checked"';
                                } else {
                                    $checked = '';
                                }
                                ?>
                                <li>
                                    <a href="#">
                                        <label for="<?php echo $sys_sub->sys_menu_id; ?>">
                                            <input type="checkbox" name="sub_menu[]" id="<?php echo $sys_sub->sys_menu_id; ?>" value="<?php echo $sys_sub->sys_menu_id; ?>" <?php echo $checked; ?> />
                                            <?php echo $sys_sub->menu_name; ?> 
                                    </a> 


                                    <ul>
                                        <?php
                                        $submenu_sub = query("select  sys_menu_id,menu_name FROM sys_menu Where _show = '1' AND _subid ='$sys_sub->sys_menu_id'");
                                        while ($sys_sub_sub = fetch_object($submenu_sub)) {
                                            $sqlChk = "SELECT menu_sub_id FROM user_level WHERE level_id = '$user_level_sub_id->level_id' AND '$sys_sub_sub->sys_menu_id' in($user_level_sub_id_list)";
                                            $user_level = findValue($sqlChk);
                                            if ($user_level != '') {
                                                $checked = 'checked="checked"';
                                            } else {
                                                $checked = '';
                                            }
                                            ?>
                                            <li>
                                                <a href="#">
                                                    <label for="<?php echo $sys_sub_sub->sys_menu_id; ?>">
                                                    <input type="checkbox" name="sub_menu[]" <label for="<?php echo $sys->sys_menu_id; ?>"> value="<?php echo $sys_sub_sub->sys_menu_id; ?>" <?php echo $checked; ?> >
                                                   <?php echo $sys_sub_sub->menu_name; ?>
                                                </a> 

                                                <?php
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
        </nav>
        <br/>
        <button type="submit" name="save" value="SaveSysMenu" class="button" onClick="return verify()">Save</button>
    </form>
</div>
<?php include '../body/footer.php'; ?>



