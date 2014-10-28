<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="keywords" content="jquery,ui,easy,easyui,web">
        <meta name="description" content="easyui help you build your web page easily!">


        <style type="text/css" media="screen">
            @import "../public/fancy_light_box/jquery.fancybox.css";
            @import "../public/DataTables/media/css/demo_table.css";
            @import "../public/DataTables/media/css/datatable.css";
            @import "../public/DataTables/media/css/demo_table_jui.css";
            @import "../jquery-ui/css/smoothness/jquery-ui-1.8.23.custom.css";
            @import "../public/css/css3buttons.css";
            @import "../public/css/Site.css";
            @import "../public/menu/css/superfish.css";
        </style>

        <link rel="stylesheet" type="text/css" href="../public/themes/default/easyui.css">
        <link rel="stylesheet" type="text/css" href="../public/themes/icon.css">
        <link rel="stylesheet" type="text/css" href="../public/themes/default/demo.css">
        
        <script type="text/javascript" src="../public/js/ajax.js"></script>
        <script type="text/javascript" src="../public/js/jquery-1.7.2.js"></script>
        <script type="text/javascript" src="../public/js/jquery.easyui.min.js"></script>
        <script type="text/javascript" src="../public/js/jquery.treegrid.js"></script>
        <script type='text/javascript' src='../public/fancy_light_box/jquery.fancybox.js'></script>
        <script type='text/javascript' src='../public/menu/js/superfish.js'></script>
        <script type='text/javascript' src='../public/jquerytablesorter/jquery.tablesorter.min.js'></script>
        <script type='text/javascript' src='../public/js/jquery.filtering.js'></script>
        <script type="text/javascript" src="../jquery-ui/js/jquery-ui-1.8.16.custom.min.js"></script>
        <script type="text/javascript" src="../public/DataTables/media/js/jquery.dataTables.min.js"></script>

        <title>The City Bank</title>

        <script type="text/javascript">
            $(document).ready(function() {
                $('.fancybox').fancybox();

                $("table:not(.ui-state-default) tr td:nth-child(odd)").css('font-weight', 'bold');
                //$("table:not(.ui-state-default) tr td:nth-child(1)").css('width', '200');
                $("table.ui-state-default thead tr:first th:nth-child(1)").css('width', '20');
                $("table.ui-state-default tr td:nth-child(1)").css('text-align', 'center');
            });

            function onChange($obj, $id) {
                var val = $obj.val();
                console.log(val);
                //$obj.addClass('ajaxLoading');
                $.ajax({
                    url: $id + '.php?val=' + val,
                    data: val,
                    success: function(respons) {
                        $('"' + $id + '"').html(respons)
                    },
                    dataType: 'text/hrml'
                });
            }

            //$(document).reay(function (){
            //$('.fancybox').fancybox();
            //});
        </script>

    </head>

    <body> 

        <div id='fw_header'>
            <a id="company-branding" href='../index.php'><img src="../public/images/PrimeBank.png" alt="City Bank"/></a>
            <div id="center"><?php //$userName == '' ? 'Guest' : get_switcher_menu($userName); ?></div>
        </div>

        <div id="fw_account">
            <div class="account_left"></div>
            <div class="account_right"></div>
            <a href='#'><?php echo $userName; ?></a> | <a href='../common/modules.php?logout=true' onClick='return log_out()'>Sign out</a>
        </div>

        <div id="content" >
            <br/><br/><br/>






