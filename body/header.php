<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
        <meta name="keywords" content="jquery,ui,easy,easyui,web"/>
        <meta http-equiv="X-UA-Compatible" content="IE=8"/>
        


        <style type="text/css" media="screen">
            @import "../public/fancy_light_box/jquery.fancybox.css";
            @import "../public/css/Site.css";
            @import "../public/css/css3buttons.css";
            @import "../public/menu/css/superfish.css";
        </style>

        <link rel="stylesheet" type="text/css" href="../public/themes/default/easyui.css"/>
        <link rel="stylesheet" type="text/css" href="../public/themes/icon.css"/>


        <script type="text/javascript" src="../public/js/jquery-1.7.2.js"></script>
        <script type="text/javascript" src="../public/js/jquery.easyui.min.js"></script>
        <script type="text/javascript" src="../public/js/jquery.treegrid.js"></script>
        <script type='text/javascript' src='../public/fancy_light_box/jquery.fancybox.js'></script>
        <script type='text/javascript' src='../public/menu/js/superfish.js'></script>
        <script type='text/javascript' src='../public/menu/js/hoverIntent.js'></script>
        <script type='text/javascript' src='../public/js/headerScript.js'></script>
        <script type='text/javascript' src='../public/js/ajax.js'></script>
        <script type='text/javascript' src='../public/js/jquery.validate.min.js'></script>

        <!--[if IE 8]>
            <meta http-equiv='cache-control' content='no-cache'>
            <meta http-equiv='expires' content='0'>
            <meta http-equiv='pragma' content='no-cache'>
            <script type='text/javascript' src='../public/js/html5.js'></script>
        <![endif]-->

        <title>City Bank</title>

        <script type="text/javascript">
            $(document).ready(function() {

                jQuery('ul.sf-menu').superfish();
                $("table.ui-state-default thead tr:first th:nth-child(1)").css('width', '20');
                $('.fancybox').fancybox();
                $("#datagrid-btable").delegate("tr", "click", function() {
                    //$(this).addClass("even DTTT_selected").siblings().removeClass("even DTTT_selected");
                });
                
                //$("Table:not('[class^=datagride]') tr td input:text").css('width', '200');

            });

        </script>

    </head>

    <body> 

        <div id='fw_header'>
            <a id="company-branding" href='../index.php'><img src="../public/images/CityBank.png" alt="City Bank"  /></a>
            <div id="center"><?php $BranchDeptName . ', ' . $userName == '' ? 'Guest' : get_switcher_menu($userName); ?></div>
        </div>

        <div id="fw_account">
            <div class="account_left"></div>
            <div class="account_right"></div>
            <a href='#'><?php echo $BranchDeptName . ', ' . $userName; ?></a> | <a href='../common/modules.php?logout=true'>Sign out</a>
        </div>

        <div id="content" >
            <br/><br/><br/><br/>