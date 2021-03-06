<?php
include '../lib/DbManager.php';
include '../body/header.php';
?>
<h2>Client Side Pagination in DataGrid</h2>
<div class="demo-info">
    <div class="demo-tip icon-tip"></div>
    <div>This sample shows how to implement client side pagination in DataGrid.</div>
</div>
<div style="margin:10px 0;"></div>
<table id="dg" title="Client Side Pagination" style="width:700px;height:300px" data-options="
       rownumbers:true,
       singleSelect:true,
       autoRowHeight:false,
       pagination:true,
       pageSize:10">
    <thead>
        <tr>
            <th field="REQUISITION_NO" width="80">Inv No</th>
            <th field="REQUISITION_DATE" width="100">Date</th>
            <th field="PresentLocation" width="80">Name</th>

        </tr>
    </thead>
</table>
<script>
    function getData() {
        var rows = [];

        $.post("ClientSidePagination_get.php", function(data) {

            for (var i = 1; i <= data; i++) {
                var amount = Math.floor(Math.random() * 1000);
                var price = Math.floor(Math.random() * 1000);
                rows.push({
                    inv: 'Inv No ' + i,
                    date: $.fn.datebox.defaults.formatter(new Date()),
                    name: 'Name ' + i,
                    amount: amount,
                    price: price,
                    cost: amount * price,
                    note: 'Note ' + i
                });
            }
        });



        return rows;
    }



    function pagerFilter(data) {
        alert(data);
        if (typeof data.length == 'number' && typeof data.splice == 'function') { // is array
            data = {
                total: data.length,
                rows: data
            }
        }
        var dg = $(this);
        var opts = dg.datagrid('options');
        var pager = dg.datagrid('getPager');
        pager.pagination({
            onSelectPage: function(pageNum, pageSize) {
                opts.pageNumber = pageNum;
                opts.pageSize = pageSize;
                pager.pagination('refresh', {
                    pageNumber: pageNum,
                    pageSize: pageSize
                });
                dg.datagrid('loadData', data);
            }
        });
        if (!data.originalRows) {
            data.originalRows = (data.rows);
        }
        var start = (opts.pageNumber - 1) * parseInt(opts.pageSize);
        var end = start + parseInt(opts.pageSize);
        data.rows = (data.originalRows.slice(start, end));
        return data;
    }
    $(function() {
        $('#dg').datagrid({loadFilter: pagerFilter}).datagrid('loadData', getData());
    });
</script>
</body>
</html>