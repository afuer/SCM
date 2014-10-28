<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head>
        <title>jQuery EasyUI CRUD Demo</title>


        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="keywords" content="jquery,ui,easy,easyui,web">
        <meta name="description" content="easyui help you build your web page easily!">

        <link rel="stylesheet" type="text/css" href="../public/themes/default/easyui.css">
        <link rel="stylesheet" type="text/css" href="../public/themes/icon.css">
        <link rel="stylesheet" type="text/css" href="../public/themes/default/demo.css">
 
        <script type="text/javascript" src="../public/js/jquery-1.7.2.js"></script>
        <script type="text/javascript" src="../public/js/jquery.easyui.min.js"></script>
        <script type="text/javascript" src="../public/js/jquery.treegrid.js"></script>

    <h2>CheckBox Tree</h2>  
    <div class="demo-info">  
        <div class="demo-tip icon-tip"></div>  
        <div>Tree nodes with check boxes.</div>  
    </div>  
    <div style="margin:10px 0;">  
        <a href="#" class="easyui-linkbutton" onclick="getChecked()">GetChecked</a>   
        <br/>  
        <input type="checkbox" checked onchange="$('#tt').tree({cascadeCheck:$(this).is(':checked')})">CascadeCheck   
        <input type="checkbox" onchange="$('#tt').tree({onlyLeafCheck:$(this).is(':checked')})">OnlyLeafCheck  
    </div>  
    <div style="width: 400px;">
        <ul id="tt" class="easyui-tree"
            url="TreeJson.php"
            checkbox="true">
        </ul> 
    </div>
    <script type="text/javascript">  
        function getChecked(){  
            var nodes = $('#tt').tree('getChecked');  
            var s = '';  
            for(var i=0; i<nodes.length; i++){  
                if (s != '') s += ',';  
                s += nodes[i].id;  
            }  
            alert(s);  
        }  
    </script> 
</body>
</html>